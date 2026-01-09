<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
require_once '../../vendor/autoload.php';
use Controller\OrientadoresController;
use Controller\EstagiariosController;
use Controller\MailController;

require_once '../../shared/csrf.php';
require_once '../../config.php';

// Inicia a sessão no início do script
session_start();
// Log para ver o que está chegando
error_log("=== DEBUG PassVerify.php ===");
error_log("POST data: " . print_r($_POST, true));
error_log("SESSION data: " . print_r($_SESSION, true));

// Recebe as senhas via post
// Normaliza e sanitiza entradas POST para evitar notices e XSS
 $__expected_post_keys = array_merge(array_keys($_POST ?? []), ['senha1','senha2','idestagiario','idorientador','id']);
foreach ($__expected_post_keys as $k) {
    if (!isset($_POST[$k])) $_POST[$k] = null;
}
// Não escapar senhas; apenas sanitizar outros campos
$__skip_sanitize = ['senha1','senha2'];
foreach ($_POST as $k => $v) {
    if (is_string($v) && !in_array($k, $__skip_sanitize, true)) {
        $_POST[$k] = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    }
}

if($_POST){

    // CSRF validation
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $_SESSION['error'] = 'csrf_fail';
        header('Location:../../setpassword.php');
        exit();
    }
    error_log("POST recebido");
        
    // Não escapar as senhas (preservar caracteres); apenas trim
    if (is_string($_POST['senha1'])) $_POST['senha1'] = trim($_POST['senha1']);
    if (is_string($_POST['senha2'])) $_POST['senha2'] = trim($_POST['senha2']);
    
    // Armazena POST na sessão
    $_SESSION['p'] = $_POST;
    
    // Verifica se as senhas são iguais
    if($_POST['senha2'] != $_POST['senha1']){
                error_log("Erro: senhas diferentes");
        $_SESSION['error'] = 'senhas_diferentes';
        header('Location:../../setpassword.php');
        exit();
    }
    
    // Verifica comprimento da senha
    $senhaLength = strlen($_POST['senha1']);
    if($senhaLength < 8 || $senhaLength > 32){
                error_log("Erro: senha com comprimento inválido: $senhaLength");
        $_SESSION['error'] = 'senha_invalida';
        header('Location:../../setpassword.php');
        exit();
    }
        error_log("Senha validada com sucesso");
    // Se chegou aqui, as senhas são válidas
    try {
                error_log("Verificando IDs...");
                                error_log("idestagiario: " . (isset($_POST['idestagiario']) ? $_POST['idestagiario'] : 'NÃO SETADO'));
        error_log("idorientador: " . (isset($_POST['idorientador']) ? $_POST['idorientador'] : 'NÃO SETADO'));
        error_log("id: " . (isset($_POST['id']) ? $_POST['id'] : 'NÃO SETADO'));
        
        if(isset($_POST['idestagiario']) && !empty($_POST['idestagiario'])){
                        error_log("Processando como estagiário. ID: " . $_POST['idestagiario']);
            $controller = new EstagiariosController;
                        error_log("Controller de estagiários instanciado");

            // Carrega estagiário antes de alterar para verificar se já existia senha
            $estagiario = $controller->loadById($_POST['idestagiario']);
            $is_update = false;
            if ($estagiario && method_exists($estagiario, 'getSenha') && $estagiario->getSenha() !== null && $estagiario->getSenha() !== '') {
                $is_update = true;
            }

            $total = $controller->CreateSenha($_POST['idestagiario'], $_POST['senha1']);
                        error_log("CreateSenha retornou: " . $total);

            if($total > 0){
                                error_log("Senha criada/atualizada com sucesso para estagiário");
                $estagiario = $controller->loadById($_POST['idestagiario']);
                $email = new MailController;
                $email->mail->clearAddresses();
                $email->mail->addAddress($estagiario->getEmail());
                if ($is_update) {
                    $subject = 'Não responda — Senha atualizada';
                    $title = 'Senha atualizada com sucesso';
                    $name = $estagiario->getNomeCompleto();
                    $message = '<p>Sua senha foi atualizada com sucesso. Caso não tenha sido você, entre em contato com a equipe responsável.</p>';
                    $email->setTemplate($subject, $title, $name, $message, 'Ir para o sistema', BASE_URL . '/home.php?idestagiario=' . (int)$estagiario->getId());
                } else {
                    $subject = 'Não responda — Boas-vindas';
                    $title = 'Bem-vindo ao sistema';
                    $name = $estagiario->getNomeCompleto();
                    $message = '<p>Bem-vindo/a ao sistema de controle de horários. Aqui você pode registrar suas horas de trabalho e consultar seus lançamentos.</p>';
                    $email->setTemplate($subject, $title, $name, $message, 'Acessar sistema', BASE_URL . '/home.php?idestagiario=' . (int)$estagiario->getId());
                }
                if ($email->send()) {
                    $_SESSION['idestagiario'] = $estagiario->getId();
                    unset($_SESSION['p']); // Limpa dados temporários
                    unset($_SESSION['error']); // Limpa erros anteriores
                    $redirect = '../../home.php';
                    if (!headers_sent()) {
                        header('Location: ' . $redirect);
                        exit();
                    }
                    echo '<!doctype html><html><head><meta charset="utf-8"><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($redirect) . '"></head><body><script>window.location.href="' . addslashes($redirect) . '";</script></body></html>';
                    exit();
                } else {
                    $_SESSION['error'] = 'email_falhou';
                    $redirect = '../../setpassword.php';
                    if (!headers_sent()) {
                        header('Location: ' . $redirect);
                        exit();
                    }
                    echo '<!doctype html><html><head><meta charset="utf-8"><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($redirect) . '"></head><body><script>window.location.href="' . addslashes($redirect) . '";</script></body></html>';
                    exit();
                }
            } else {
                error_log("Falha ao criar senha para estagiário");
                $_SESSION['error'] = 'cadastro_falhou';
  //              header('Location:../../setpassword.php');
//                exit();
            }
            
                } elseif(isset($_POST['idorientador']) && !empty($_POST['idorientador'])){
                                                error_log("Processando como orientador. ID: " . $_POST['idorientador']);
                        $controller = new OrientadoresController;
                                                error_log("Controller de orientadores instanciado");

                        // Carrega orientador antes de alterar para verificar se já existia senha
                        $orientador = $controller->loadById($_POST['idorientador']);
                        $is_update = false;
                        if ($orientador && method_exists($orientador, 'getSenha') && $orientador->getSenha() !== null && $orientador->getSenha() !== '') {
                                $is_update = true;
                        }

                        $total = $controller->CreateSenha($_POST['idorientador'], $_POST['senha1']);
                                                error_log("CreateSenha retornou: " . $total);

                        if($total > 0){
                                                                error_log("Senha criada/atualizada com sucesso para orientador");
                                $orientador = $controller->loadById($_POST['idorientador']);
                                $email = new MailController;
                                $email->mail->clearAddresses();
                                $email->mail->addAddress($orientador->getEmail());
                                if ($is_update) {
                                    $subject = 'Não responda — Senha atualizada';
                                    $title = 'Senha atualizada com sucesso';
                                    $name = $orientador->getnomeorientador();
                                    $message = '<p>Sua senha foi atualizada com sucesso. Caso não tenha sido você, entre em contato com a equipe responsável.</p>';
                                    $email->setTemplate($subject, $title, $name, $message, 'Ir para o sistema', BASE_URL . '/home.php?idorientador=' . (int)$orientador->getId());
                                } else {
                                    $subject = 'Não responda — Boas-vindas';
                                    $title = 'Bem-vindo ao sistema';
                                    $name = $orientador->getnomeorientador();
                                    $message = '<p>Bem-vindo/a ao sistema de controle de horários. Aqui você pode gerenciar estagiários e projetos de forma simples.</p>';
                                    $email->setTemplate($subject, $title, $name, $message, 'Acessar sistema', BASE_URL . '/home.php?idorientador=' . (int)$orientador->getId());
                                }
                if ($email->send()) {
                    $_SESSION['idorientador'] = $orientador->getId();
                    unset($_SESSION['p']);
                    unset($_SESSION['error']);
                    $redirect = '../../home.php';
                    if (!headers_sent()) {
                        header('Location: ' . $redirect);
                        exit();
                    }
                    echo '<!doctype html><html><head><meta charset="utf-8"><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($redirect) . '"></head><body><script>window.location.href="' . addslashes($redirect) . '";</script></body></html>';
                    exit();
                } else {
                    $_SESSION['error'] = 'email_falhou';
                    $redirect = '../../setpassword.php';
                    if (!headers_sent()) {
                        header('Location: ' . $redirect);
                        exit();
                    }
                    echo '<!doctype html><html><head><meta charset="utf-8"><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($redirect) . '"></head><body><script>window.location.href="' . addslashes($redirect) . '";</script></body></html>';
                    exit();
                }
            } else {
                                error_log("Falha ao criar senha para orientador");
                $_SESSION['error'] = 'cadastro_falhou';
                header('Location:../../setpassword.php');
                exit();
            }
        } else if(!isset($_POST['id']))  {
//como não tem id, redireciona com erro
            $_SESSION['error'] = 'id_nao_encontrado';
            header('Location:../../setpassword.php');
            exit();
        }
        
    } catch(Exception $e) {
        $_SESSION['error'] = 'erro_sistema';
        error_log("Erro em PassVerify.php: " . $e->getMessage());
        header('Location:../../setpassword.php');
        exit();
    }
} else {
    // Se não foi POST, redireciona
    header('Location:../../setpassword.php');
    exit();
}