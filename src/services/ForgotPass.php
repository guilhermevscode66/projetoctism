<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../vendor/autoload.php';
require_once '../../config.php';
require_once '../../shared/csrf.php';

use Controller\OrientadoresController;
use Controller\EstagiariosController;
use Controller\MailController;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_POST) {
    // 1. Verifica CSRF
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $_SESSION['error'] = 'csrf_fail';
        header('Location:../../forgot-password.php');
        exit();
    }

    // Salva os dados para persistência no formulário (exceto CSRF)
    $_SESSION['p'] = $_POST;

    // 2. Coleta e limpa o e-mail (sem usar htmlspecialchars aqui para não quebrar a validação)
    $emailDigitado = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);

    if (!filter_var($emailDigitado, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'email_invalido';
        header('Location:../../forgot-password.php');
        exit();
    }

    $idEstagiario = $_POST['idestagiario'] ?? null;
    $idOrientador = $_POST['idorientador'] ?? null;

    $userFound = null;
    $type = null;

    // 3. Lógica de busca: Se você tem o ID, use-o. Se não, busque pelo e-mail.
    $estagiarioCtrl = new EstagiariosController();
    $orientadorCtrl = new OrientadoresController();

    if (!empty($idEstagiario)) {
        $userFound = $estagiarioCtrl->loadById($idEstagiario);
        $type = 'estagiario';
    } elseif (!empty($idOrientador)) {
        $userFound = $orientadorCtrl->loadById($idOrientador);
        $type = 'orientador';
    } else {
        // Se os IDs vierem vazios, tentamos buscar pelo e-mail em ambas as tabelas
        // Nota: Você precisará ter um método findByEmail nos seus controllers
        $userFound = $estagiarioCtrl->loadByEmail($emailDigitado);
        $type = 'estagiario';
        
        if (!$userFound) {
            $userFound = $orientadorCtrl->loadByEmail($emailDigitado);
            $type = 'orientador';
        }
    }

    // 4. Se encontrou o usuário e o e-mail coincide
    if ($userFound && strtolower($userFound->getEmail()) === strtolower($emailDigitado)) {
        
        $mail = new MailController();
        $mail->mail->clearAddresses();
        $mail->mail->addAddress($userFound->getEmail());
        
        $subject = 'Recuperação de senha';
        $title   = 'Recuperação de senha';
        $name    = ($type === 'estagiario') ? $userFound->getNomeCompleto() : $userFound->getnomeorientador();
        
        $link = BASE_URL . "/setpassword.php?id{$type}=" . (int)$userFound->getId();
        $message = "<p>Recebemos uma solicitação de alteração de senha. Clique no botão abaixo para prosseguir:</p>";

        $mail->setTemplate($subject, $title, $name, $message, 'Redefinir senha', $link);

        if ($mail->send()) {
            unset($_SESSION['p'], $_SESSION['error']);
            header('Location:../../aviso_sobre_email_enviado.php');
            exit();
        } else {
            $_SESSION['error'] = 'email_falhou';
        }
    } else {
        $_SESSION['error'] = 'email_nao_existe';
    }

    header('Location:../../forgot-password.php');
    exit();

} else {
    header('Location:../../index.php');
    exit();
}