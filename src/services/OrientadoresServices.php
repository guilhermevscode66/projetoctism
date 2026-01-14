<?php
use Controller\MailController;
use Controller\OrientadoresController;

require_once '../../vendor/autoload.php';
require_once '../../config.php';
require_once '../../shared/csrf.php';
session_start();
// Insert e Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        
        $_SESSION['error'] = 'csrf_fail';
        header('Location:../../manterorientadores.php');
        exit();
    }
    //cria uma sessão para armazenar os dados que vão voltar para o formulário em caso de erro
    
    $_SESSION['p'] = $_POST;
    //elimina caracteres especiais dos campos de cadastro de orientadores...
    $id = (int)($_POST['id'] ?? 0);
$nome = $_POST['nome'];
$matricula = $_POST['matricula'];
$matricula = trim($matricula);
    $email = $_POST['email'];
$email = trim($email);


    //fim da parte de protecao sql
    
    //valida se o email é válido e a matrícula é numérica
    
    if(!is_numeric($_POST['matricula'])){
    $_SESSION['error']='matricula_invalida';
    header('location:../../manterorientadores.php');
    exit();
}
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    $_SESSION['error']='email_invalido';
    header('location:../../manterorientadores.php');
    exit();
    }
    //se chegou aqui, dados válidos
        $controller = new OrientadoresController;
        $total = 0;
//passar os dados
$dados =[
    'nomeorientador' => $nome,
    'matricula' => $matricula,
    'email' => $email
];
        if (empty($id)) {
//verifica se a matrícula já existe    $orientadorExistente = $controller->loadByMatricula($matricula);
    $orientadorExistente = $controller->loadByMatricula($matricula);    
    // Se o retorno não for nulo, significa que já existe um orientador com essa matrícula
    if ($orientadorExistente) {
        $_SESSION['error'] = 'matricula_duplicada';
        header('Location: ../../manterorientadores.php');
        exit();
    }

            $total = $controller->create($dados);
        } else {
            $total = $controller->update($id, $dados);
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
                    unset($_SESSION['p']); // Limpa o rascunho do form se deu certo
        header('Location:../../aviso_sobre_email_enviado.php');
        exit();
    }
    else{
        // se falhar no envio do email, redireciona para manterorientadores com mensagem de erro
        $_SESSION['error']='email_fail';
        header('location:../../manterorientadores.php'); 
        exit();
        }              
    }
        else{
            //se o cadastro falhar
        $_SESSION['error']='db_fail';
            header('location:../../manterorientadores.php');
            exit();
        }
    // Delete
    } elseif (isset($_GET['id'])) {
        $id =(int) $_REQUEST['id'];
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
            exit();
        } else { // se não conseguiu deletar apenas redireciona.
         $_SESSION['error']='delete_error';
            header('Location:../../listarorientadores.php');
            exit();
        }
    }

?>