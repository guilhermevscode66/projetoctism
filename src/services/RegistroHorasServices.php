<?php
use Controller\BancoHorasController;


require_once '../../vendor/autoload.php';

// Normaliza e sanitiza entradas POST para evitar notices e XSS
$__expected_post_keys = array_merge(array_keys($_POST ?? []), ['hora_entrada','hora_saida','idprojeto','idestagiario','id']);
foreach ($__expected_post_keys as $k) {
    if (!isset($_POST[$k])) $_POST[$k] = null;
}
foreach ($_POST as $k => $v) {
    if (is_string($v)) $_POST[$k] = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

// Insert e Update
if ($_POST) {

    $controller = new BancoHorasController;
    $total = 0;
    //verifica se existe o idprojeto e idestagiario
    if (isset($_POST['idprojeto']) && isset($_POST['idestagiario'])) {
        $_POST['idprojeto'] = $_POST['idprojeto'];
        $_POST['idestagiario'] = $_POST['idestagiario'];
        if (empty($_POST['id'])) { //id auto increment gerado pelo banco, não é o estagiariosprojeto_id
            $total = $controller->create($_POST);
        } else {
            $total = $controller->update($_POST['id'], $_POST);
        }
        //se o total for maior que zero, redireciona para horastrabalhadas.php

        if ($total > 0) {
            header('location:\horastrabalhadas.php?idprojeto='.$_POST['idprojeto'].'&idestagiario='.$_POST['idestagiario']);
        } else {
            //se não conseguiu salvar redireciona com mensagem de erro
            header('location:\registrohoras.php?cod=erro');

        }
    }
}
