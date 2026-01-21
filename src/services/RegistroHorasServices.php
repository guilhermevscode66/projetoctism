<?php
use Controller\BancoHorasController;

require_once '../../vendor/autoload.php';
require_once '../../shared/csrf.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $_SESSION['error'] = 'csrf_fail';
        header('Location:../../registrohoras.php');
        exit();
    }

    $idprojeto = $_POST['idprojeto'] ?? null;
    $idestagiario = $_POST['idestagiario'] ?? null;
    $hora_entrada = $_POST['hora_entrada'] ?? null;
    $hora_saida = $_POST['hora_saida'] ?? null;

    // Salva na sessão IMEDIATAMENTE para garantir que a página de origem tenha os dados ao voltar
    $_SESSION['idprojeto'] = $idprojeto;
    $_SESSION['idestagiario'] = $idestagiario;

    // Validações de Horas
    if ($hora_entrada > $hora_saida || $hora_saida < $hora_entrada) {
        $_SESSION['error'] = 'hora_invalida';
        header("Location:../../registrohoras.php?idprojeto=$idprojeto&idestagiario=$idestagiario");
        exit();
    }

    if (empty($idprojeto) || empty($idestagiario) || empty($hora_entrada) || empty($hora_saida)) {
        $_SESSION['error'] = 'faltando_dados';
        header("Location:../../registrohoras.php?idprojeto=$idprojeto&idestagiario=$idestagiario");
        exit();
    }

    $controller = new BancoHorasController;
    $dados = [
        'idprojeto' => $idprojeto,
        'idestagiario' => $idestagiario,
        'hora_entrada' => $hora_entrada, 
        'hora_saida' => $hora_saida
    ];

    $total = empty($_POST['id']) ? $controller->create($dados) : $controller->update($_POST['id'], $dados);

    if ($total > 0) {
        $_SESSION['success'] = 'salvo_com_sucesso';
        header('Location:../../horastrabalhadas.php');
        exit();
    } else {
        $_SESSION['error'] = 'erro_ao_salvar';
        header("Location:../../registrohoras.php?idprojeto=$idprojeto&idestagiario=$idestagiario");
        exit();
    }
}
