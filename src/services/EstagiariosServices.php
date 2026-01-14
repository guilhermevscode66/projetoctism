<?php
use Controller\EstagiariosController;
use Controller\MailController;
require_once '../../vendor/autoload.php';
require_once '../../shared/csrf.php';
require_once '../../config.php';
session_start();
// Processamento via POST (Insert/Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        
        $_SESSION['error'] = 'csrf_fail';
        header('Location:../../manterestagiarios.php');
        exit();
    }

    
    $_SESSION['p'] = $_POST;

    // Validação de e-mail e matrícula
    $id =(int) $_POST['id'] ?? null;
    $nome= $_POST['nomecompleto'];
    $email = trim($_POST['email']); 
    $matricula = trim($_POST['matricula']);
    $supervisor = $_POST['supervisor'];
    $projeto = $_POST['idprojeto'];
        $orientador = $_POST['idorientador'];
        $minHoras= $_POST['minhoras'];
        if($nome =='' || $email=='' || $matricula==''|| $supervisor=='' || $projeto=='' || $orientador=='' || $minHoras==''){
            
            header('Location:../../manterestagiarios.php?cod=campos_vazios');
            exit();
        }
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
    //recebe osdados do formulário
    $dados  = [
        'nomecompleto' => $nome,
        'email' => $email,
        'matricula' => $matricula,
        'supervisor' => $supervisor,
        'idprojeto' => $projeto,
        'idorientador' => $orientador,
        'minhoras' => $minHoras
    ];
    if (empty($id)) {
        //verifica se a matrícula já existe
        $estagiarioExistente = $controller->loadByMatricula($matricula);    
        // Se o retorno não for nulo, significa que já existe
        if ($estagiarioExistente) {
            $_SESSION['error'] = 'matricula_duplicada';
            header('Location: ../../manterestagiarios.php');
            exit();
        }
        $total = $controller->create($_POST);
    } else {
        $total = $controller->update($_POST['id'], $_POST);
    }
if ($total > 0) {
        $est = $controller->loadByMatricula($matricula);
        $idNovoEstagiario = $est->getId();
        $recipient = $est->getEmail();
        $nomeEstagiario = $est->getNomecompleto();

        // Só envia e-mail de "Boas-vindas/Criar senha" se for um NOVO cadastro
        if (empty($_POST['id'])) {
            $mail = new MailController();
            $mail->mail->clearAddresses();
            $mail->mail->addAddress($recipient);
            
            $subject = 'Criação de Senha — Sistema de Horas';
            $title = 'Bem-vindo(a) ao Sistema!';
            
            $message = "<p>Olá, <strong>" . htmlspecialchars($nomeEstagiario) . "</strong>.</p>";
            $message .= "<p>Seu cadastro como estagiário foi realizado com sucesso.</p>";
            $message .= "<p>Para começar a registrar suas horas, clique no botão abaixo para definir sua senha de acesso.</p>";

            // Note que passamos idestagiario na URL para diferenciar do orientador na setpassword.php
            $link = BASE_URL . '/setpassword.php?idestagiario=' . (int)$idNovoEstagiario;
            
            $mail->setTemplate($subject, $title, $nomeEstagiario, $message, 'Criar minha senha', $link);

            if ($mail->send()) {
                unset($_SESSION['p']); // Limpa rascunho do formulário
                header('Location:../../aviso_sobre_email_enviado.php');
                exit();
            } else {
                // Se o e-mail falhar, redireciona com erro, mas o cadastro no banco já foi feito
                $_SESSION['error'] = 'email_fail';
                header('Location:../../manterestagiarios.php?cod=erro_email');
                exit();
            }
        } else {
            // Se for apenas um UPDATE, redireciona direto para a lista
            unset($_SESSION['p']);
            header('Location:../../listarestagiarios.php?msg=sucesso_update');
            exit();
        }
    }
    }

// 3. Processamento via GET (Delete)


// Bloco de Delete
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    session_start();
    
    $idEstagiario = (int)$_GET['id'];
    $idLogado = $_SESSION['idlogado'] ?? null; // Sua variável de sessão

    if (!$idLogado) {
        header('Location: ../../index.php?error=sessao_expirada');
        exit();
    }

    $controller = new EstagiariosController();
    
    // 1. Primeiro, carregamos o estagiário para verificar quem é o orientador dele
    $estagiario = $controller->loadById($idEstagiario);

    if (!$estagiario) {
        header('Location: ../../listarestagiarios.php?msg=erro_nao_encontrado');
        exit();
    }

    // 2. VALIDAÇÃO DE PROPRIEDADE (O "Pulo do Gato")
    // Verificamos se o idorientador do estagiário no banco é igual ao idlogado na sessão
    if ($estagiario->getidorientador() == $idLogado) {
        
        $sucesso = $controller->delete($idEstagiario);
        
        if ($sucesso) {
            header('Location: ../../listarestagiarios.php?msg=sucesso_delete');
        } else {
            header('Location: ../../listarestagiarios.php?msg=erro_delete');
        }
        
    } else {
        // Tentativa de invasão ou erro de permissão
        // Logar essa tentativa pode ser útil para segurança
        error_log("Tentativa de exclusão não autorizada: Orientador $idLogado tentou excluir Estagiário $idEstagiario");
        header('Location: ../../listarestagiarios.php?msg=erro_permissao');
    }
    exit();
}
?>