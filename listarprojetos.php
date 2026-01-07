<?php
require_once 'shared/header.php';
//require_once './src/controller/authenticationController.php';
use Controller\ProjetosController;
require_once './vendor/autoload.php';
?>

<div class="mb -4">    
    <h1 class="mb-4"> Lista deProjetos</h1>
    
    
    <a href="manterProjetos.php" class="btn btn-primary">Novo projeto</a>
    
<table class="table">
<thead>
    <tr>
        <th>Id</th>
        <th>Nome</th>
        <th>Data de criação</th>
        <th>Ações</th>
    </tr>
</thead>
<tbody>
    <?php $controller = new ProjetosController; ?>
    <?php $dados = $controller->loadAll(); ?>
<?php if (!empty($dados)): ?>
<?php foreach ($dados as $projetos): ?>
<tr>
    <td> <?php echo $projetos->getId();?> </td>
<td> <?php echo $projetos->getNome(); ?> </td>
<td> <?php echo date('d/m/Y H:i:s', strtotime($projetos->getDatacriacao())); ?></td>
 <td> <a href="manterProjetos.php?id=<?php echo $projetos->getId(); ?>" class="btn btn-warning btn-sm  mb-2 w-100">Editar</a> </td>
<td> <a onclick="return confirm('\Deseja excluir esse projeto?\');" src/services/ServicesProjetos.php?id=<?php echo $projetos->getId(); ?>" class="btn btn-danger btn-sm  mb-2 w-100">Excluir</a> </td>
</tr>
<?php endforeach; ?>

            <?php else: ?>
                    <tr>
                        <td colspan="4">Nenhum projetos cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<a href="home.php">Voltar</a>

<?php
require_once './shared/footer.php';
?>