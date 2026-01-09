<?php
use Controller\EstagiariosController;
use Controller\MailController;
require_once '../../vendor/autoload.php';
require_once '../../shared/csrf.php';
require_once '../../config.php';

// 1. Normalização de dados
$__expected_post_keys = array_merge(array_keys($_POST ?? []), ['nomecompleto','email','matricula','supervisor','idprojeto','idorientador','id']);
foreach ($__expected_post_keys as $k) {
    if (!isset($_POST[$k])) $_POST[$k] = null;
}

// 2. Processamento via POST (Insert/Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        session_start();
        $_SESSION['error'] = 'csrf_fail';
        header('Location:../../manterestagiarios.php');
        exit();
    }

    session_start();
    $_SESSION['p'] = $_POST;

    // Validação de e-mail e matrícula
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        header('Location:../../manterestagiarios.php?cod=email_invalido');
        exit();
    }
    
    if (!is_numeric($_POST['matricula'])) {
        header('Location:../../manterestagiarios.php?cod=matricula_invalida');
        exit();
    }

    // Se passou na validação, processa
    $controller = new EstagiariosController();
    $matricula = $_POST['matricula'];
    
    if (empty($_POST['id'])) {
        $total = $controller->create($_POST);
    } else {
        $total = $controller->update($_POST['id'], $_POST);
    }

    if ($total > 0) {
        $est = $controller->loadByMatricula($matricula);
        // Lógica de envio de e-mail de boas-vindas aqui...
        header('Location:../../aviso_sobre_email_enviado.php');
        exit();
    }

} 
// 3. Processamento via GET (Delete)
else if (isset($_GET['id'])) {
    
    $id = (int)$_GET['id'];
    $controller = new EstagiariosController();
    
    $est = $controller->loadById($id);

    if ($est) {
        $recipient = method_exists($est, 'getEmail') ? $est->getEmail() : null;
        $nomeEstagiario = method_exists($est, 'getNomecompleto') ? $est->getNomecompleto() : 'Usuário';

        $total = $controller->delete($id);

        if ($total > 0) {
            if (!empty($recipient)) {
                try {
                    $email = new MailController();
                            // 1. Primeiro define o template (isso geralmente limpa o corpo do e-mail)
        $email->setTemplate(
            'Não responda — Cadastro Removido', 
            'Estagiário removido', 
            $nomeEstagiario, 
            '<p>O cadastro do estagiário foi removido do sistema.</p>', 
            'Acessar Sistema', 
            BASE_URL . '/listarestagiarios.php'
        );

                            // Garante que não existam destinatários residuais na memória da classe
        if (isset($email->mail)) {
            $email->mail->clearAddresses();
            $email->mail->addAddress($recipient);
        }
                    $subject = 'Não responda — Cadastro Removido';
                    $title = 'Estagiário removido';
                    $message = '<p>O cadastro foi removido do sistema.</p>';
                    
                    $email->setTemplate($subject, $title, $nomeEstagiario, $message, 'Ver sistema', BASE_URL . '/listarestagiarios.php');
                    $email->send();
                } catch (Exception $e) {
                    // Erro no envio do e-mail não deve travar a deleção
                }
            }
            header('Location: ../../listarestagiarios.php?msg=sucesso_delete');
            exit();
        }
    }
    
    header('Location: ../../listarestagiarios.php?msg=erro_delete');
    exit();
}