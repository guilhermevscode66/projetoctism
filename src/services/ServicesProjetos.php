<?php
use Controller\MailController;
use Controller\ProjetosController;

require_once '../../vendor/autoload.php';

// Normaliza e sanitiza entradas POST para evitar notices e XSS
$__expected_post_keys = array_merge(array_keys($_POST ?? []), ['nome','id']);
foreach ($__expected_post_keys as $k) {
    if (!isset($_POST[$k])) $_POST[$k] = null;
}
foreach ($_POST as $k => $v) {
    if (is_string($v)) $_POST[$k] = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

// Insert e Update
if ($_POST) {
    //cria uma sessão para armazenar os dados que vão voltar para o formulário em caso de erro
    session_start();
    $_SESSION['p']=$_POST;
//elimina caracteres especiais dos campos de cadastro de projetos...
$_POST['nome']=htmlspecialchars($_POST['nome']);
//fim da parte de protecao sql
    //mensagem de email para o cadastro
    $sub='naoresponder, cadastro de projeto, Night Wind';
    $msg= '<p> Presado orientador, Foi efetuado o cadastro de um novo projeto no sistema.</p> <p> Você pode ver todos os projetos cadastrados neste link</p> \n<a href="../../listarprojetos.php"> Conferir meus projetos</a> \n <p>Atenciosamente,</p> \n <p>Sistema de notificação do Night Wind.</p>\n<p> OBS: Esse email foi gerado automaticamente, não é necessário responder. </p>';

    $controller = new ProjetosController;
    $total = 0;

    if (empty($_POST['id'])) {
        $total = $controller->create($_POST);
    } else {
        $total = $controller->update($_POST['id'], $_POST);
    }

    if ($total > 0) {
        //se total maior que zero conseguiu cadastrar, envio o email de cadastrado com sucesso
         header('location:\listarProjetos.php');
}}
        
    


 else { //se não vier de post
    // Delete
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
        $controller = new ProjetosController;
        $total = $controller->delete($id);
        if ($total > 0) {
            // se total for maior que zero conseguiu deletar e envia o email de aviso
            $email= new MailController;
            $sub=$email->mail->Subject='naoresponder, projeto deletado, Night Wind';
            $msg = $email->mail->Body='<p> Presado orientador, Um projeto foi deletado do sistema.</p>
            <p> nome do projeto:' . $_POST['nome']. '</p>
             <p> Veja seus projetos nesse link:</p> \n
<a href="../../listarprojetos.php">Conferir meus projetos</a>
              \n <p>Atenciosamente</p>,\n <p>Sistema de notificação do Night Wind.</p> \n<p> OBS: esse email foi gerado automaticamente, não é necessário responder. </p>';
            if($email->send()){
            header ('location:\listarProjetos.php');
        }} else {// se não conseguiu deletar apenas redireciona.
            header ('location:\listarProjetos.php?msg=2');
        }
    }
}
