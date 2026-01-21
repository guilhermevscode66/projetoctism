<?php
use Controller\EstagiariosController;
require_once 'vendor/autoload.php';
require_once 'shared/header.php';

// Inicializa a variável de alerta
$alerta = null;

if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);

    if ($msg === 'erro_nao_encontrado') {
        $alerta = ['classe' => 'alert-warning', 'texto' => 'Estagiário não encontrado.'];
    } elseif ($msg === 'sucesso_delete') {
        $alerta = ['classe' => 'alert-success', 'texto' => 'Estagiário excluído com sucesso.'];
    } elseif ($msg === 'successo_update') {
        $alerta = ['classe' => 'alert-success', 'texto' => 'Estagiário atualizado com sucesso.'];
    } elseif ($msg === 'erro_delete') {
        $alerta = ['classe' => 'alert-danger', 'texto' => 'Erro ao excluir o estagiário.'];
    } elseif ($msg === 'erro_permissao') {
        $alerta = ['classe' => 'alert-warning', 'texto' => 'Você não tem permissão para realizar esta ação!'];
    } elseif ($msg === 'sucesso_finalizar_horas') {
        $alerta = ['classe' => 'alert-success', 'texto' => 'Estágio marcado como concluído com sucesso.'];
    } elseif ($msg === 'erro_finalizar_horas') {
        $alerta = ['classe' => 'alert-danger', 'texto' => 'Erro ao concluir o estágio.'];
    }
}

$controller = new EstagiariosController();
$estagiarios = $controller->loadAll();
$idLogado = $_SESSION['idorientador'] ?? null;
?>

<div class="container mt-4">
    <h2>Lista de Estagiários</h2>

    <?php if ($alerta): ?>
        <div class="alert <?= $alerta['classe'] ?> alert-dismissible fade show" role="alert">
            <?= $alerta['texto'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nome</th>
                    <th>Projeto</th>
                    <th>Orientador</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estagiarios as $est): ?>
                    <tr>
                        <td><?= htmlspecialchars($est->getNomecompleto()) ?></td>
                        <td><?= htmlspecialchars($est->getNomeProjeto()) ?></td>
                        <td><?= htmlspecialchars($est->getNomeOrientador()) ?></td>
                        <td>
                            <?php if ($est->getIdorientador() == $idLogado): ?>
                                <a class="btn btn-sm btn-outline-primary" href="manterestagiarios.php?id=<?= $est->getId() ?>">Editar</a>

                                <?php if ($est->getStatus() === 'concluido'): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Concluído
                                    </span>
                                <?php else: ?>
                                    <a class="btn btn-sm btn-info" 
                                       href="src/services/EstagiariosServices.php?action=finalizar&idestagiario=<?= $est->getId() ?>" 
                                       onclick="return confirm('Marcar esse estagiário como concluído?');">
                                       Concluir Estágio
                                    </a>
                                <?php endif; ?>

                                <a class="btn btn-sm btn-danger" 
                                   href="src/services/EstagiariosServices.php?id=<?= $est->getId() ?>&action=delete"
                                   onclick="return confirm('Excluir este estagiário?')">Excluir</a>

                            <?php else: ?>
                                <span class="badge bg-secondary">Somente leitura</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'shared/footer.php'; ?>