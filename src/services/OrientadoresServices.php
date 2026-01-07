<?php
use Controller\MailController;
use Controller\OrientadoresController;

require_once '../../vendor/autoload.php';

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

    $email = new MailController;
    $email->mail->clearAddresses();
    $email->mail->addAddress($recipient);
    $email->mail->Subject = 'naoresponda, criação de senha - Night Wind';
    $email->mail->Body = '<!DOCTYPE html><html lang="pt-br"><body>' .
        '<p>Olá, bem-vindo/a ao sistema de banco de horas do Night Wind. Recebemos uma solicitação de criação de senha para a conta de nome ' . htmlspecialchars($_POST['nome']) . ' e email ' . htmlspecialchars($recipient) . '.</p>' .
        '<p>Se foi você que solicitou, clique no link abaixo para criar sua senha:</p>' .
        '<p><a href="http://localhost:80/setpassword.php?idorientador=' . (int)$orient->getId() . '">Crie sua senha aqui</a></p>' .
        '<p>Atenciosamente: equipe Night Wind.</p>' .
        '<footer>Esse email foi gerado automaticamente, não é necessário responder</footer>' .
        '</body></html>';
    if ($email->mail->send()) {
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
        $total = $controller->delete($id);
        if ($total > 0) {
            // se total for maior que zero conseguiu deletar e envia o email de aviso
            $email = new MailController;
            $sub = $email->mail->Subject = 'naoresponder, orientador deletado, Night Wind';
            $msg = $email->mail->Body = '<p> Presado orientador, Um orientador foi deletado do sistema.</p>
            <p> nome do orientador:' . $_POST['nome'] . '</p>
              <p>Atenciosamente</p>, <p>Sistema de notificação do Night Wind.</p> <p> OBS: esse email foi gerado automaticamente, não é necessário responder. </p>';
            if ($email->send()) {
                header('location:\manterorientadores.php');
            }
        } else { // se não conseguiu deletar apenas redireciona.
            header('Location:\listarorientadores.php?msg=2');
        }
    }
}
?>