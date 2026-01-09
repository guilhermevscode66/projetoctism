<?php
use Controller\MailController;
use Controller\ProjetosController;

require_once '../../vendor/autoload.php';
require_once '../../shared/csrf.php';
require_once '../../config.php'; // Certifique-se de que o config está aqui para o BASE_URL

// 1. Normalização e sanitização
$__expected_post_keys = array_merge(array_keys($_POST ?? []), ['nome','id', 'csrf_token']);
foreach ($__expected_post_keys as $k) {
    if (!isset($_POST[$k])) $_POST[$k] = null;
}

// 2. Processamento via POST (Insert/Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        session_start();
        $_SESSION['error'] = 'csrf_fail';
        header('Location:../../manterprojetos.php');
        exit();
    }

    session_start();
    $_SESSION['p'] = $_POST;

    $controller = new ProjetosController();
    $total = 0;

    if (empty($_POST['id'])) {
        $total = $controller->create($_POST);
    } else {
        $total = $controller->update($_POST['id'], $_POST);
    }

    if ($total > 0) {
        // Redireciona para a listagem com mensagem de sucesso
        header('Location: ../../listarprojetos.php?msg=sucesso_save');
        exit();
    } else {
        header('Location: ../../listarprojetos.php?msg=erro_save');
        exit();
    }

} 
// 3. Processamento via GET (Delete)
else if (isset($_GET['id'])) {
    
    $id = (int)$_GET['id'];
    $controller = new ProjetosController();
    
    // Carrega antes de deletar para recuperar o nome do projeto para o e-mail
    $proj = $controller->loadById($id);

    if ($proj) {
        $projName = method_exists($proj, 'getNome') ? $proj->getNome() : 'Projeto';
        
        $total = $controller->delete($id);

        if ($total > 0) {
            try {
                $email = new MailController();
                $subject = 'Não responda — Projeto removido';
                $title = 'Projeto removido do sistema';
                $message = '<p>O projeto <strong>' . htmlspecialchars($projName) . '</strong> foi removido do sistema.</p>';
                
                // Nota: Para projetos, você precisa definir para quem enviar o e-mail. 
                // Se a classe MailController não tiver um destinatário padrão, adicione:
                // $email->mail->addAddress('admin@seu-sistema.com'); 

                $email->setTemplate($subject, $title, 'Orientador', $message, 'Ver projetos', BASE_URL . '/listarprojetos.php');
                $email->send();
            } catch (Exception $e) {
                // Erro de e-mail não impede o redirecionamento
            }

            header('Location: ../../listarprojetos.php?msg=sucesso_delete');
            exit();
        }
    }
    
    header('Location: ../../listarprojetos.php?msg=erro_delete');
    exit();
}