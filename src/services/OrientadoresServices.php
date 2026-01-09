<?php
use Controller\MailController;
use Controller\OrientadoresController;

require_once '../../vendor/autoload.php';
require_once '../../config.php';
require_once '../../shared/csrf.php';

// Normaliza e sanitiza entradas POST para evitar notices e XSS
$__expected_post_keys = array_merge(array_keys($_POST ?? []), ['nome','matricula','email','id']);
foreach ($__expected_post_keys as $k) {
    if (!isset($_POST[$k])) $_POST[$k] = null;
}
foreach ($_POST as $k => $v) {
    if (is_string($v)) $_POST[$k] = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

// Insert e Update
if ($_POST) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        session_start();
        $_SESSION['error'] = 'csrf_fail';
        header('Location:../../manterorientadores.php');
        exit();
    }
    //cria uma sessão para armazenar os dados que vão voltar para o formulário em caso de erro
    session_start();
    $_SESSION['p'] = $_POST;
    //elimina caracteres especiais dos campos de cadastro de orientadores...
    $_POST['nome'] = htmlspecialchars($_POST['nome']);
    $_POST['matricula'] = htmlspecialchars($_POST['matricula']);
    $_POST['email'] = htmlspecialchars($_POST['email']);
    //fim da parte de protecao sql
    
    //valida se o email é válido e a matrícula é numérica
    
    if(is_numeric($_POST['matricula'])){
    if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $controller = new OrientadoresController;
        $total = 0;

        if (empty($_POST['id'])) {
            $total = $controller->create($_POST);
        } else {
            $total = $controller->update($_POST['id'], $_POST);
        }
if($total>0){
    //envia o email para criar uma senha
    // carregar orientador recém-criado para obter email/id
    $orient = $controller->loadByMatricula($_POST['matricula']);
    $recipient = $orient->getEmail();

    $email = new MailController();
    $email->mail->clearAddresses();
    $email->mail->addAddress($recipient);
    $subject = 'Não responda — Criação de senha';
    $title = 'Criação de senha';
    $name = htmlspecialchars($_POST['nome']);
    $message = '<p>Bem-vindo/a ao sistema de controle de horários. Recebemos uma solicitação de criação de senha para a conta de nome ' . $name . ' e email ' . htmlspecialchars($recipient) . '.</p>' .
               '<p>Se foi você que solicitou, clique no botão abaixo para criar sua senha:</p>';
    $email->setTemplate($subject, $title, $name, $message, 'Criar senha', BASE_URL . '/setpassword.php?idorientador=' . (int)$orient->getId());
    if ($email->send()) {
        header('Location:../../aviso_sobre_email_enviado.php');
    }
        }              
    }
        else{
            header('location:../../manterorientadores.php');
        }
    }else{
        header('location:../../manterorientadores.php');
    }
}else{
    // Delete
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
        $controller = new OrientadoresController;
        // carrega antes de deletar para obter dados para notificação
        $orient = $controller->loadById($id);
        $orientName = method_exists($orient, 'getnomeorientador') ? $orient->getnomeorientador() : '';
        $recipient = method_exists($orient, 'getEmail') ? $orient->getEmail() : null;
        $total = $controller->delete($id);
        if ($total > 0) {
            $email = new MailController();
            if (!empty($recipient)) {
                $subject = 'Não responda — Orientador removido';
                $title = 'Orientador removido do sistema';
                $name = $orientName ?: 'Orientador';
                $message = '<p>O orientador <strong>' . htmlspecialchars($orientName, ENT_QUOTES, 'UTF-8') . '</strong> foi removido do sistema.</p>';
                $email->setTemplate($subject, $title, $name, $message, 'Ver orientadores', BASE_URL . '/listarorientadores.php');
                $email->send();
            }
            header('Location:../../manterorientadores.php');
        } else { // se não conseguiu deletar apenas redireciona.
            header('Location:../../listarorientadores.php?msg=2');
        }
    }
}
?>