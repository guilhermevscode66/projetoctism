<?php
require_once '../../vendor/autoload.php';
use Controller\EstagiariosController;
use Controller\OrientadoresController;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    // Limpa qualquer login anterior antes de tentar um novo
unset($_SESSION['idestagiario'], $_SESSION['idorientador']);
}

require_once '../../shared/csrf.php';

if ($_POST) {
    // 1. CSRF Check
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $_SESSION['error'] = 'csrf_fail';
        header('location:../../index.php');
        exit;
    }

    // 2. Coleta de dados (EVITE htmlspecialchars na SENHA antes do verify)
    $matricula = htmlspecialchars(strip_tags(trim($_POST['matricula'] ?? '')), ENT_QUOTES, 'UTF-8');
    $senhaRaw = $_POST['senha'] ?? ''; // Pegamos a senha bruta para o password_verify
    $tipousuario = (int) ($_POST['tipousuario'] ?? 0);

    if (empty($matricula) || empty($senhaRaw) || !in_array($tipousuario, [1, 2])) {
        $_SESSION['error'] = 'faltando_dados';
        header('location:../../index.php');
        exit;
    }

    $loginSucesso = false;

    try {
        if ($tipousuario == 1) { // ESTAGIÁRIO
            $controller = new EstagiariosController();
            $user = $controller->loadByMatricula($matricula);

            if ($user->total > 0 && password_verify($senhaRaw, $user->getSenha())) {
                $_SESSION['idestagiario'] = $user->getId();
                $loginSucesso = true;
            }
        } 
        elseif ($tipousuario == 2) { // ORIENTADOR
            $controller = new OrientadoresController();
            $user = $controller->loadByMatricula($matricula);

            if ($user->total > 0 && password_verify($senhaRaw, $user->getSenha())) {
                $_SESSION['idorientador'] = $user->getId();
                $loginSucesso = true;
            }
        }

        if ($loginSucesso) {
            unset($_SESSION['error'], $_SESSION['p']);
            session_regenerate_id(true);
            header('location:../../home.php');
            exit;
        } else {
            $_SESSION['error'] = 'credenciais_invalidas';
            error_log("Tentativa de login falhou para matrícula: $matricula");
            header('location:../../index.php');
            exit;
        }

    } catch (Exception $e) {
        $_SESSION['error'] = 'erro_sistema';
        error_log("Erro no login: " . $e->getMessage());
        header('location:../../index.php');
        exit;
    }
} else {
    header('Location:../../index.php');
    exit;
}