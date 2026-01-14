<?php

require_once 'vendor/autoload.php';
require_once 'shared/header.php';
if(isset($msg)){
if($msg ==='erro_nao_encontrado'){
$alerta = ['classe' => 'alert-warning', 'texto' => ' Estagiário não encontrado.'];
}
elseif($msg === 'sucesso_delete'){
    $alerta = ['classe' => 'alert-success', 'texto' => ' Estagiário excluído com sucesso.'];
}elseif($msg ==='successo_update'){
    $alerta = ['classe' => 'alert-success', 'texto' => ' Estagiário atualizado com sucesso.'];

} elseif ($msg === 'erro_delete') {
    $alerta = ['classe' => 'alert-danger', 'texto' => 'Erro ao excluir o estagiário. Por favor, tente novamente'];


} elseif ($msg === 'erro_permissao') {
    $alerta = ['classe' => 'alert-warning', 'texto' => 'Você não tem permissão para excluir este estagiário!'];
}
}
use Controller\EstagiariosController;
$controller = new EstagiariosController();
$estagiarios = $controller->loadAll();

// ID do orientador logado (ajuste conforme seu sistema de login)
$idLogado = $_SESSION['idorientador'] ?? null; 
?>

<div class="container mt-4">
    <h2>Lista de Estagiários</h2>
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
                            <a class="btn btn-sm btn-warning" href="registrohoras.php?idestagiario=<?= $est->getId() ?>">Horas</a>
                            
                            <?php if ($est->getidorientador() == $idLogado): ?>
                                <a class="btn btn-sm btn-info" href="manterestagiarios.php?id=<?= $est->getId() ?>">Editar</a>
                                <a class="btn btn-sm btn-danger" href="src/services/EstagiariosServices.php?id=<?= $est->getId() ?>" 
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