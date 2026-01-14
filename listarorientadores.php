<?php
require_once 'vendor/autoload.php';
require_once 'shared/header.php';

use Controller\OrientadoresController;
$controller = new OrientadoresController();
$orientadores = $controller->loadAll();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Orientadores Cadastrados</h2>
        <a href="manterorientadores.php" class="btn btn-primary">Novo Orientador</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Matrícula</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orientadores as $o): ?>
                <tr>
                    <td><?= htmlspecialchars($o->getMatricula()) ?></td>
                    <td><?= htmlspecialchars($o->getnomeorientador()) ?></td>
                    <td><?= htmlspecialchars($o->getEmail()) ?></td>
                    <td>
                        <a href="manterorientadores.php?id=<?= $o->getId() ?>" class="btn btn-sm btn-info">Editar</a>
                        <a href="src/services/OrientadoresServices.php?id=<?= $o->getId() ?>" 
                           class="btn btn-sm btn-danger" onclick="return confirm('Excluir orientador?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>