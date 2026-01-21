<?php
// 1. Lógica de controle SEMPRE no topo
require_once 'vendor/autoload.php';
require_once 'shared/header.php'; // Certifique-se que o session_start() está aqui dentro

use Controller\BancoHorasController;

// 2. Recuperação de IDs com prioridade para Sessão (mais seguro)
$idprojeto = $_SESSION['idprojeto'] ?? $_GET['idprojeto'] ?? null;
$idestagiario = $_SESSION['idestagiario'] ?? $_GET['idestagiario'] ?? null;

// Redirecionamento preventivo (Idealmente isso deveria estar antes do header.php)
if (!$idprojeto || !$idestagiario) {
    echo "<script>window.location.href='home.php';</script>"; // Fallback caso o header já tenha sido enviado
    exit;
}

// 3. Gerenciamento de Mensagens
$alerta = null;
if (isset($_SESSION['success'])) {
    if ($_SESSION['success'] === 'salvo_com_sucesso') {
        $alerta = ['classe' => 'alert-success', 'texto' => 'Horas registradas com sucesso.'];
    }
    unset($_SESSION['success']);
}

$bancoController = new BancoHorasController();
$loadbanco = $bancoController->loadByIpes($idprojeto, $idestagiario);
?>

<div class="container mt-4">
    <h2>Horas Registradas</h2>

    <?php if ($alerta): ?>
        <div class="alert <?= $alerta['classe'] ?> alert-dismissible fade show" role="alert">
            <?= $alerta['texto'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Início do Turno</th>
                <th scope="col">Fim do Turno</th>
                <th scope="col">Data</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($loadbanco)): ?>
                <tr>
                    <td colspan="3" class="text-center">Nenhum registro encontrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($loadbanco as $registro): ?>
                    <tr>
                        <td><?= htmlspecialchars($registro->getHoraEntrada()) ?></td>
                        <td><?= htmlspecialchars($registro->getHoraSaida()) ?></td>
                        <td>
                            <?php 
                                // Ajuste conforme o retorno do seu banco (String vs Timestamp)
                                $dataRaw = $registro->getData();
                                $dataTimestamp = is_numeric($dataRaw) ? $dataRaw : strtotime($dataRaw);
                                echo date('d/m/Y H:i', $dataTimestamp); 
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="mt-3">
        <a href="home.php" class="btn btn-secondary">Voltar</a>
    </div>
</div>

<?php require_once 'shared/footer.php'; ?>