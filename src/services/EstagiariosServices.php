<?php
use Controller\EstagiariosController;
use Controller\MailController;
require_once '../../vendor/autoload.php';

// Normaliza e sanitiza entradas POST para evitar notices e XSS
$__expected_post_keys = array_merge(array_keys($_POST ?? []), ['nomecompleto','nome','email','matricula','supervisor','idprojeto','idorientador','id','senha1','senha2','idestagiario','hora_entrada','hora_saida','tipousuario']);
foreach ($__expected_post_keys as $k) {
    if (!isset($_POST[$k])) $_POST[$k] = null;
}
foreach ($_POST as $k => $v) {
    if (is_string($v)) $_POST[$k] = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}


// Insert e Update
if ($_POST) {
    //cria uma sessão para que quando o usuário retornar para o formulario por um erro, não perca os dados
    session_start();
    $_SESSION['p']=$_POST;
// elimina os caracteres especiais dos campos de cadastro de usuarios...
    $_POST['nomecompleto']=htmlspecialchars($_POST['nomecompleto']);
    $_POST['email']=htmlspecialchars($_POST['email']);
$_POST['matricula']=htmlspecialchars($_POST['matricula']);

$_POST['supervisor']=htmlspecialchars($_POST['supervisor']);
$_POST['idprojeto']= htmlspecialchars($_POST['idprojeto']);
$_POST['idorientador']= htmlspecialchars($_POST['idorientador']);

//fim da parte de protecao sql
//validação,  redirect e erros
//se o email informado é válido e a matrícula é numérica  
if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
    if(is_numeric($_POST['matricula'])){
        $matricula =$_POST['matricula'];
    $controller = new EstagiariosController;
    $total = 0;

    if (empty($_POST['id'])) {
        $total = $controller->create($_POST);
    } else {
        $total = $controller->update($_POST['id'], $_POST);
    }

    if ($total > 0) {
        //se o total for maior que 0 conseguiu cadastrar
        //envia o email para criar uma senha

        // carregar estagiário recém-criado para obter id e email
        $est = $controller->loadByMatricula($matricula);
        $recipient = $est->getEmail();

        $nomeParaEmail = '';
        if (!empty($_POST['nomecompleto'])) {
            $nomeParaEmail = htmlspecialchars($_POST['nomecompleto']);
        } elseif (method_exists($est, 'getNomeCompleto')) {
            $nomeParaEmail = htmlspecialchars($est->getNomeCompleto());
        }

        $email = new MailController;
        $email->mail->clearAddresses();
        $email->mail->addAddress($recipient);
        $email->mail->Subject = 'naoresponda, criação de senha - Night Wind';
        $email->mail->Body = '<!DOCTYPE html><html lang="pt-br"><body>' .
            '<p>Olá, bem-vindo/a ao sistema de banco de horas do Night Wind. Recebemos uma solicitação de criação de senha para a conta de nome ' . $nomeParaEmail . ' e email ' . htmlspecialchars($recipient) . '.</p>' .
            '<p>Se foi você que solicitou, por favor clique no link abaixo para criar sua senha:</p>' .
            '<p><a href="http://localhost:80/setpassword.php?idestagiario=' . (int)$est->getId() . '">Crie sua senha aqui</a></p>' .
            '<p>Atenciosamente: Equipe Night Wind.</p>' .
            '<footer>Esse email foi gerado automaticamente, não é necessário responder.</footer>' .
            '</body></html>';

        if ($email->mail->send()) {
            header('Location:../../aviso_sobre_email_enviado.php');
        }

        
    } 
        
    
}
}
//se o email informado não é válido ou a senha não é numérica 

else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    header('Location:../../manterestagiarios.php?cod=email_invalido');
    
}
else if(!is_numeric($_POST['matricula'])){    
    header('Location:../../manterestagiarios.php?cod=matricula_invalida');

}
//fim da validacao
}//fim do post
 else { //se não vier de post
    // Delete
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
        $controller = new EstagiariosController;
        $total = $controller->delete($id);
        if ($total > 0) {
            //se o total for maior que zero conseguiu deletar envia o email de aviso
            $email = new MailController;
            $sub= $email ->mail->Subject='naoresponder, estagiario deletado, Night Wind';
            $msg= $email->mail->Body='<p>Presado estagiário, você foi excluído de um projeto do night Wind</p>.';
            if($email->send()){

            header ('location:\listarEstagiarios.php');
            exit;
            }
        } else {
            header ('location:\listarEstagiarios.php?msg=4');
            exit;
        }
    }
 }