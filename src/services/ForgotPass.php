<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
require_once '../../vendor/autoload.php';
use Controller\OrientadoresController;
use Controller\EstagiariosController;
use Controller\MailController;

// Normaliza e sanitiza entradas POST para evitar notices e XSS
$__expected_post_keys = array_merge(array_keys($_POST ?? []), ['email','idestagiario','idorientador']);
foreach ($__expected_post_keys as $k) {
    if (!isset($_POST[$k])) $_POST[$k] = null;
}
foreach ($_POST as $k => $v) {
    if (is_string($v)) $_POST[$k] = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

//recebe o e-mail via post
if($_POST){
    //cria a sessão pra armazenar os dados que voltam em caso de erro
    session_start();
    $_SESSION['p'] = $_POST;
if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
$_SESSION['error'] = 'email_invalido';
header('Location:../../forgot-password.php');
exit();
}
//se chegou até aqui o email é válido
//verifica se o email existe na tabela estagiarios
if($_POST['idestagiario']){
    //instansia o objeto
$controller = new EstagiariosController;
//carrega o estagiario
$load = $controller->loadById($_POST['idestagiario']);
if($load->getEmail()){
    $email = new MailController;
    $email->mail->clearAddresses();
    $email->mail->addAddress($load->getEmail());
    $email->mail->Subject = 'Não responda, recuperação de senha - Night Wind';
    $email->mail->Body = '<!DOCTYPE html><html lang="pt-br"><body>' .
        '<h1>Recuperação de senha</h1>' .
        '<p>Olá. Recebemos uma solicitação de alteração de senha para o sistema de banco de horas para a conta de ' . htmlspecialchars($load->getNomeCompleto()) . '. Se foi você que pediu a alteração, clique no link abaixo para criar uma nova senha. Se não foi, ignore esta mensagem.</p>' .
        '<p><a href="http://localhost:80/setpassword.php?idestagiario=' . (int)$load->getId() . '">Redefinir senha</a></p>' .
        '<p>Atenciosamente: Equipe Night Wind</p>' .
        '<footer>Esse email foi gerado automaticamente, não é necessário responder.</footer>' .
        '</body></html>';
    if ($email->mail->send()) {
unset($_SESSION['p'], $_SESSION['error']); //limpa erros e dados não utilizados
header('Location:../../aviso_sobre_email_enviado.php');
exit();
}
else{
    //se falhou ao enviar o email
    $_SESSION['error'] = 'email_falhou';
    header('location:../../forgot-password.php');
    exit();
}
}
else{
    $_SESSION['error'] = 'email_nao_existe';
    header('location:../../forgot-password.php');
    exit();

}
}

else if($_POST['idorientador']){ //verifica se existe na tabela dos orientadores
    //instansia o objeto
$controller = new OrientadoresController;
//carrega o estagiario
$load = $controller->loadById($_POST['idorientador']);
if($load->getEmail()){
    $email = new MailController;
    $email->mail->clearAddresses();
    $email->mail->addAddress($load->getEmail());
    $email->mail->Subject = 'Não responda, recuperação de senha - Night Wind';
    $email->mail->Body = '<!DOCTYPE html><html lang="pt-br"><body>' .
        '<h1>Recuperação de senha</h1>' .
        '<p>Olá. Recebemos uma solicitação de alteração de senha para a conta de ' . htmlspecialchars($load->getnomeorientador()) . '. Se foi você que pediu a alteração, clique no link abaixo para criar uma nova senha. Se não foi, ignore esta mensagem.</p>' .
        '<p><a href="http://localhost:80/setpassword.php?idorientador=' . (int)$load->getId() . '">Redefinir senha</a></p>' .
        '<p>Atenciosamente: Equipe Night Wind</p>' .
        '<footer>Esse email foi gerado automaticamente, não é necessário responder.</footer>' .
        '</body></html>';
    if ($email->mail->send()) {
unset($_SESSION['p'], $_SESSION['error']); //limpa erros e dados não utilizados
header('Location:../../aviso_sobre_email_enviado.php');
exit();
}
else{
    //se falhou ao enviar o email
    $_SESSION['error'] = 'email_falhou';
    header('location:../../forgot-password.php');
    exit();
}
}
else{ //se o email não existir na tabela de orientadores
    $_SESSION['error'] = 'email_nao_existe';
    header('location:../../forgot-password.php');
    exit();

}
}
}
else{
    //se não vier de post redireciona
    header('Location:../../index.php');
}