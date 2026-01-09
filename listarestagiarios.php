<?php
require_once 'vendor/autoload.php';
require_once 'shared/header.php';

// ... seus requires anteriores ...

$msg = $_GET['msg'] ?? null;
$alerta = '';

if ($msg === 'sucesso_delete') {
    $alerta = ['classe' => 'alert-success', 'texto' => 'Estagiário removido com sucesso!'];
} elseif ($msg === 'erro_delete') {
    $alerta = ['classe' => 'alert-danger', 'texto' => 'Erro ao tentar remover o estagiário.'];
}

use Controller\EstagiariosController;
use Controller\OrientadoresController;

$controller = new EstagiariosController();
$estagiarios = $controller->loadAll();
?>

<div>
    <h2 class="mt-4">Lista de estagiários</h2>
</div>
<?php if ($alerta): ?>
    <div class="alert <?php echo $alerta['classe']; ?> alert-dismissible fade show" role="alert">
        <strong>Aviso:</strong> <?php echo $alerta['texto']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="table-responsive-md">
    <table class="table table-primary">
        <thead>
            <tr>
                <th scope="col">Nome completo</th>
                <th scope="col">Email</th>
                <th scope="col">Matrícula</th>
                <th scope="col">Projeto</th>
                <th scope="col">Orientador</th>
                <th scope="col">Supervisor</th>
                <th scope="col">Mínimo de horas</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($estagiarios)): ?>
                <?php foreach ($estagiarios as $value): ?>
                    <tr>
                        <td><?php echo $value->getNomecompleto(); ?></td>
                        <td><?php echo $value->getEmail(); ?></td>
                        <td><?php echo $value->getMatricula(); ?></td>
                        <td><?php echo $value->getNomeProjeto(); ?></td>
                        <td><?php echo $value->getNomeOrientador(); ?></td>
                        <td><?php echo $value->getSupervisor(); ?></td>
                        <td><?php echo $value->getMinHoras(); ?></td>
                        <td>
                            <a class="btn btn-sm btn-warning" href="registrohoras.php?idprojeto=<?php echo $value->getidprojeto(); ?>&idestagiario=<?php echo $value->getId(); ?>">Horas</a>
                            
                            <a class="btn btn-sm btn-info" href="manterestagiarios.php?id=<?php echo $value->getId(); ?>">Editar</a>
                            
                            <a class="btn btn-sm btn-danger" href="src/services/EstagiariosServices.php?id=<?php echo $value->getId(); ?>" 
                               onclick="return confirm('Deseja excluir esse estagiário? Essa ação é irreversível.');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Nenhum estagiário cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<a href="home.php" class="btn btn-secondary">Voltar</a>
<script>
    // Espera o documento carregar completamente
    document.addEventListener('DOMContentLoaded', function() {
        // Seleciona todos os alertas na página
        const alertas = document.querySelectorAll('.alert');

        alertas.forEach(function(alerta) {
            // Define um tempo de 4 segundos (4000ms) antes de começar a sumir
            setTimeout(function() {
                // Adiciona um efeito de transição suave usando CSS
                alerta.style.transition = "opacity 0.6s ease";
                alerta.style.opacity = "0";

                // Após a transição terminar, remove o elemento do layout
                setTimeout(function() {
                    alerta.remove();
                }, 600); 
            }, 4000);
        });
    });
</script>

<?php
require_once 'shared/footer.php';
?>