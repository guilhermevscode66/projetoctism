<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
require_once '../../vendor/autoload.php';
use Controller\OrientadoresController;
use Controller\EstagiariosController;
use Controller\MailController;

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

                        
            $total = $controller->CreateSenha($_POST['idestagiario'], $_POST['senha1']);
                        error_log("CreateSenha retornou: " . $total);
            
            if($total > 0){
                                error_log("Senha criada com sucesso para estagiário");
                $estagiario = $controller->loadById($_POST['idestagiario']);
                                error_log("Estagiário carregado: " . ($estagiario ? "SIM" : "NÃO"));
                $email = new MailController;
                $email->mail->clearAddresses();
                $email->mail->addAddress($estagiario->getEmail());
                $email->mail->Subject = 'não responda, cadastro de estagiário Night Wind';
                $email->mail->Body = '<!DOCTYPE html><html lang="pt-br"><body>' .
                    '<h1>Boas vindas à equipe Night Wind!</h1>' .
                    '<p>Olá, ' . htmlspecialchars($estagiario->getNomeCompleto()) . '.</p>' .
                    '<p>Bem-vindo/a ao sistema de banco de horas da equipe Night Wind. Aqui você pode registrar suas horas de trabalho de uma forma fácil e rápida, além de poder consultar todas as suas horas lançadas.</p>' .
                    '<p><a class="badge bg-primary" href="http://localhost:80/home.php?idestagiario=' . (int)$estagiario->getId() . '">Conheça o sistema de banco de horas da Night Wind</a></p>' .
                    '<p>Atenciosamente: Equipe Night Wind</p>' .
                    '<footer>Esse email foi gerado automaticamente, não é necessário responder.</footer>' .
                    '</body></html>';
                if ($email->send()) {
                    $_SESSION['idestagiario'] = $estagiario->getId();
                    unset($_SESSION['p']); // Limpa dados temporários
                    unset($_SESSION['error']); // Limpa erros anteriores
                    header('Location:../../home.php');
                    exit();
                } else {
                    $_SESSION['error'] = 'email_falhou';
                    header('Location:../../setpassword.php');
                    exit();
                }
            } else {
                error_log("Falha ao criar senha para estagiário");
                $_SESSION['error'] = 'cadastro_falhou';
              //  header('Location:../../setpassword.php');
//                exit();
            }
            
        } elseif(isset($_POST['idorientador']) && !empty($_POST['idorientador'])){
                        error_log("Processando como orientador. ID: " . $_POST['idorientador']);
            $controller = new OrientadoresController;
                        error_log("Controller de orientadores instanciado");
            $total = $controller->CreateSenha($_POST['idorientador'], $_POST['senha1']);
                        error_log("CreateSenha retornou: " . $total);
            
            if($total > 0){
                                error_log("Senha criada com sucesso para orientador");
                $orientador = $controller->loadById($_POST['idorientador']);
                $email = new MailController;
                $email->mail->clearAddresses();
                $email->mail->addAddress($orientador->getEmail());
                $email->mail->Subject = 'não responda, cadastro de orientador Night Wind';
                $email->mail->Body = '<!DOCTYPE html><html lang="pt-br"><body>' .
                    '<h1>Boas vindas à equipe Night Wind!</h1>' .
                    '<p>Olá, ' . htmlspecialchars($orientador->getnomeorientador()) . ', bem-vindo/a ao sistema de banco de horas da equipe Night Wind.</p>' .
                    '<p>Aqui você pode cadastrar seus estagiários e verificar suas informações de forma fácil.</p>' .
                    '<p><a class="badge bg-primary" href="http://localhost:80/home.php?idorientador=' . (int)$orientador->getId() . '">Conheça o sistema de banco de horas da Night Wind</a></p>' .
                    '<p>Atenciosamente: Equipe Night Wind</p>' .
                    '<footer>Esse email foi gerado automaticamente, não é necessário responder.</footer>' .
                    '</body></html>';
                if ($email->send()) {
                    $_SESSION['idorientador'] = $orientador->getId();
                    unset($_SESSION['p']);
                    unset($_SESSION['error']);
                    header('Location:../../home.php');
                    exit();
                } else {
                    $_SESSION['error'] = 'email_falhou';
                    header('Location:../../setpassword.php');
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