<?php
use Controller\MailController;
use Controller\OrientadoresController;

require_once '../../vendor/autoload.php';
require_once '../../config.php';
require_once '../../shared/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Insert e Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        header('Location:../../manterorientadores.php?error=csrf_fail');
        exit();
    }
    
    // Mantemos 'p' na sessão para o formulário não resetar, 
    // mas o erro passamos via URL para garantir a exibição.
    $_SESSION['p'] = $_POST;
    
    $id = (int)($_POST['id'] ?? 0);
    $nome = trim($_POST['nome'] ?? '');
    $matricula = trim($_POST['matricula'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Validações básicas
    if (empty($nome) || empty($matricula) || empty($email)) {
        header('Location:../../manterorientadores.php?error=campos_vazios');
        exit();
    }

    if (!is_numeric($matricula)) {
        header('Location:../../manterorientadores.php?error=matricula_invalida');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location:../../manterorientadores.php?error=email_invalido');
        exit();
    }

    $controller = new OrientadoresController;

    // --- VERIFICAÇÃO DE DUPLICIDADE ---

    // 1. Verifica E-mail
    $checkEmail = $controller->loadByEmail($email);
    if ($checkEmail && ($id === 0 || $checkEmail->getId() != $id)) {
        header('Location:../../manterorientadores.php?error=email_duplicado');
        exit();
    }

    // 2. Verifica Nome
    $checkNome = $controller->loadByNome($nome);
    if ($checkNome && ($id === 0 || $checkNome->getId() != $id)) {
        header('Location:../../manterorientadores.php?error=nome_duplicado');
        exit();
    }

    // 3. Verifica Matrícula
    $checkMatricula = $controller->loadByMatricula($matricula);
    if ($checkMatricula && ($id === 0 || $checkMatricula->getId() != $id)) {
        header('Location:../../manterorientadores.php?error=matricula_duplicada');
        exit();
    }

    $dados = [
        'nomeorientador' => $nome,
        'matricula' => $matricula,
        'email' => $email
    ];

    $total = ($id === 0) ? $controller->create($dados) : $controller->update($id, $dados);

    if ($total > 0) {
        unset($_SESSION['p']);
        if ($id === 0) {
            $orient = $controller->loadByMatricula($matricula);
            $mail = new MailController();
            $mail->mail->addAddress($orient->getEmail());
            
            $subject = 'Criação de senha — Sistema de Horas';
            $link = BASE_URL . '/setpassword.php?idorientador=' . (int)$orient->getId();
            $mail->setTemplate($subject, 'Criação de senha', $nome, "Clique para definir sua senha.", 'Criar senha', $link);
            
            if ($mail->send()) {
                header('Location:../../aviso_sobre_email_enviado.php');
            } else {
                header('Location:../../manterorientadores.php?error=email_fail');
            }
        } else {
            header('Location:../../listarorientadores.php?msg=sucesso_update');
        }
        exit();
    } else {
        header('Location:../../manterorientadores.php?error=db_fail');
        exit();
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Delete
    $id = (int)$_GET['id'];
    $controller = new OrientadoresController;
    $orient = $controller->loadById($id);
    
    if ($orient && $controller->delete($id)) {
        header('Location:../../listarorientadores.php?msg=sucesso_delete');
        exit();
    }
    header('Location:../../listarorientadores.php?error=delete_error');
    exit();
}