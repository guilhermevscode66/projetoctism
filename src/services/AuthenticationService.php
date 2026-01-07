<?php
require_once '../../vendor/autoload.php';
//importo a orientadores controller e estagiarios
use Controller\EstagiariosController;
use Controller\OrientadoresController;
//inicia sessão
session_start();
// Normaliza e sanitiza entradas POST para evitar notices e XSS
$__expected_post_keys = array_merge(array_keys($_POST ?? []), ['matricula','senha','tipousuario']);
foreach ($__expected_post_keys as $k) {
    if (!isset($_POST[$k])) $_POST[$k] = null;
}
foreach ($_POST as $k => $v) {
    if (is_string($v)) $_POST[$k] = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
//verifica se é post:
    if($_POST){

        //verifica os dados
        if(!isset($_POST['matricula'], $_POST['senha'], $_POST['tipousuario'])){
            $_SESSION['error'] = 'faltando_dados';
            header('location:../../index.php');
            exit;
        }
        //sanitização
    $matricula = filter_var($_POST['matricula'], FILTER_SANITIZE_STRING);
$senha = $_POST['senha'];
$tipousuario = (int) $_POST['tipousuario'];
//valida o tipo de usuario
if(!in_array($tipousuario, [1, 2])){
$_SESSION['error'] = 'tipo_usuario_invalido';
header('location:../../index.php');
exit;
}
//se existirem dados temporários, remove
if(isset($_SESSION['p'])){
    unset($_SESSION['p']);
}

$loginSucesso= false;
try{
if($tipousuario==1){
    $controller = new EstagiariosController;
    $loginSucesso = $controller->login($matricula, $senha);
}
elseif($tipousuario ==2){
    $controller = new OrientadoresController;
    $loginSucesso = $controller->login($matricula, $senha);
}
if($loginSucesso){
    //regenera o id da sessão
    session_regenerate_id(true);
    //redireciona pra home.php
    header('location:../../home.php');
    exit;
}
else{

//se houver erro registra no log e coloca na sessão
$_SESSION['error'] = 'credenciais_invalidas';
error_log("tentativa de login falhou para matrícula: $matricula");
}
}
catch(Exception $e){
    $_SESSION['error'] = 'erro_sistema';
    error_log("Erro no login:".$e->getMessage());
}
//se chegou aqui login falhou
header('location:../../index.php');
exit;
}
    else{//se não é post redireciona
header('Location:../../index.php');
exit;
        }