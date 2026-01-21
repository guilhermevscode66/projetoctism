<?php
use Controller\EstagiariosController;
use Controller\MailController;

require_once '../../vendor/autoload.php';
require_once '../../shared/csrf.php';
require_once '../../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        header('Location:../../manterestagiarios.php?cod=erro_csrf');
        exit();
    }

    $_SESSION['p'] = $_POST;

    // Sanatização e Atribuição
    $id         = !empty($_POST['id']) ? (int)$_POST['id'] : null;
    $nome       = trim($_POST['nomecompleto'] ?? '');
    $email      = trim($_POST['email'] ?? ''); 
    $matricula  = trim($_POST['matricula'] ?? '');
    $supervisor = $_POST['supervisor'] ?? '';
    $projeto    = $_POST['idprojeto'] ?? '';
    $orientador = $_POST['idorientador'] ?? '';
    $minHoras   = $_POST['MinHoras'] ?? '';

    // Validação de Campos Vazios
    if(empty($nome) || empty($email) || empty($matricula) || empty($supervisor) || empty($projeto) || empty($orientador) || empty($minHoras)){
        header('Location:../../manterestagiarios.php?cod=campos_vazios');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location:../../manterestagiarios.php?cod=email_invalido');
        exit();
    }
    
    if (!is_numeric($matricula)) {
        header('Location:../../manterestagiarios.php?cod=matricula_invalida');
        exit();
    }

    $controller = new EstagiariosController();
    $dados = [
        'nomecompleto' => $nome,
        'email'        => $email,
        'matricula'    => $matricula,
        'supervisor'   => $supervisor,
        'MinHoras'     => $minHoras,
        'idprojeto'    => $projeto,
        'idorientador' => $orientador
    ];

    if (empty($id)) {
        // CREATE
        $estagiarioExistente = $controller->loadByMatricula($matricula);    
         

        if($estagiarioExistente!==null){
            header('Location: ../../manterestagiarios.php?cod=matricula_duplicada');
            exit();
        }
        $total = $controller->create($dados);
    } else {
        // UPDATE
        $total = $controller->update($id, $dados);
    }

    // VERIFICAÇÃO DE SUCESSO NO BANCO
    if ($total > 0) {
        unset($_SESSION['p']); // Limpa rascunho pois deu certo

        if (empty($id)) {
            // Se foi INSERT, envia e-mail
            $est = $controller->loadByMatricula($matricula);
            $mail = new MailController();
            $mail->mail->clearAddresses();
            $mail->mail->addAddress($est->getEmail());
            
            $subject = 'Criação de Senha — Sistema de Horas';
            $title   = 'Bem-vindo(a) ao Sistema!';
            $message = "<p>Olá, <strong>" . htmlspecialchars($est->getNomecompleto()) . "</strong>.</p>";
            $message .= "<p>Seu cadastro foi realizado com sucesso. Clique abaixo para definir sua senha.</p>";
            $link    = BASE_URL . '/setpassword.php?idestagiario=' . (int)$est->getId();
            
            $mail->setTemplate($subject, $title, $est->getNomecompleto(), $message, 'Criar minha senha', $link);

            if ($mail->send()) {
                header('Location:../../aviso_sobre_email_enviado.php');
            } else {
                header('Location:../../manterestagiarios.php?cod=erro_email');
            }
        } else {
            // Se foi UPDATE, apenas redireciona para a lista
            header('Location:../../listarestagiarios.php?msg=sucesso_update');
        }
        exit();
    } else {
        // Se o banco retornou 0 (nenhuma linha afetada ou erro de SQL)
        header('Location:../../manterestagiarios.php?cod=erro_cadastro');
        exit();
    }
}
//bloco para finalizar  as horas  de estagiários:
// Bloco para finalizar as horas de estagiários
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'finalizar') {
    
    $idEstagiario = (int)($_GET['idestagiario'] ?? 0);
    
    // SEGURANÇA: Pegar o ID do orientador da SESSÃO, não do GET
    $idLogado = $_SESSION['idorientador'] ?? null; 

    if (!$idLogado) {
        header('Location: ../../index.php?error=sessao_expirada');
        exit();
    }

    $controller = new EstagiariosController();
    
    // 1. Precisamos carregar o estagiário para validar se ele pertence ao orientador logado
    $estagiario = $controller->loadById($idEstagiario);

    if (!$estagiario) {
        $_SESSION['msg'] = 'erro_nao_encontrado';
        header('Location: ../../listarestagiarios.php');
        exit();
    }

    // 2. Validação de Permissão (Hierarquia)
    if ($estagiario->getIdOrientador() != $idLogado) {
        $_SESSION['msg'] = 'erro_permissao';
        header('Location: ../../listarestagiarios.php');
        exit();
    }
    
    // 3. Pegar o ID do projeto vinculado ao estagiário
    // Se o seu objeto estagiário já tem o ID do projeto, usamos ele:
    $idProjeto = $estagiario->getidprojeto(); 

    // 4. Executa a finalização
    $resultado = $controller->finalizarHoras($idEstagiario, $idProjeto);

    if ($resultado === true) {
        $_SESSION['msg'] = 'sucesso_finalizar_horas';
    } else {
        $_SESSION['msg'] = 'erro_finalizar_horas';
    }

    header('Location: ../../listarestagiarios.php');
    exit();
}


// Bloco de Delete (Mantido e ajustado)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_DELETE['id'])) {
    $idEstagiario = (int) $_GET['id'];
    $idLogado = $_GET['idlogado'] ?? null;

    if (!$idLogado) {
        $_SESSION =['msg' => 'sessao_expirada'];
        header('Location: ../../index.php');
        exit();
    }

    $controller = new EstagiariosController();
    $estagiario = $controller->loadById($idEstagiario);

    if (!$estagiario) {
        $_SESSION=['msg' => 'erro_nao_encontrado'];
        header('Location: ../../listarestagiarios.php');
        exit();
    }

    if ($estagiario->getidorientador() == $idLogado) {
        if ($controller->delete($idEstagiario)) {
            $_SESSION=['msg' => 'sucesso_delete'];
            header('Location: ../../listarestagiarios.php');
        } else {
            $_SESSION=['msg' => 'erro_delete'];
            header('Location: ../../listarestagiarios.php');
        }
    } else {
        $_SESSION=['msg' => 'erro_permissao'];
        header('Location: ../../listarestagiarios.php');
    }
    exit();
}