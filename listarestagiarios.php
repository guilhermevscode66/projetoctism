<?php
require_once 'vendor/autoload.php';
require_once 'shared/header.php';
use Controller\EstagiariosController;
use Controller\OrientadoresController;
$controller= new EstagiariosController();
$estagiarios=$controller->loadAll();

 
//var_dump($estagiarios);

//echo count($estagiarios);

?>
<!-- tabela de lista de usuarios-->
<div>
<h2 class="mt-4"> Lista de estagiários</h2>
</div>
<div
    class="table-responsive-md"
>
    <table
        class="table table-primary"
    >
        <thead>
            <tr>
                <th scope="col">Nome completo</th>
                <th scope="col">Email</th>
                <th scope="col">Matrícula</th>
                <th scope="col">Projeto</th>
                <th scope="col">Orientador</th>
                <th scope="col">supervisor</th>
                <th scope="col">Mínimo de horas</th>
                                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
           <?php if(!empty($estagiarios)): ?>
 

  
                <?php foreach($estagiarios as $value): ?>
               
<tr><td> <?php echo $value->getNomecompleto(); ?> </td>
 <td> <?php echo $value->getEmail();?> </td>
  <td><?php echo $value->getMatricula();?> </td>
<td><?php echo $value->getNomeProjeto(); ?> </td>
  <td><?php echo $value->getNomeOrientador(); ?></td>
  <td><?php echo $value->getSupervisor();?> </td>
   <td><?php echo $value->getMinHoras(); ?></td>


 <td> <a class="btn btn-warning" href="registrohoras.php?idprojeto=<?php echo $value->getidprojeto();?> &idestagiario=<?php echo $value->getId(); ?>"> Lançar horas </a> </td>
 <td> <a class="btn btn-warning" href="manterestagiarios.php?id=<?php echo $value->getId(); ?> > Editar estagiário </a> </td>
  <td> <a class="btn btn-danger" href="src/services/EstagiariosServices.php?id=<?php echo $value->getId(); ?>" onclick="return confirm('\Deseja excluir esse estagiário? Essa ação é irreversível.\');"> Excluir estagiário </a> </td></tr>
<?php endforeach; ?>

                 <?php else: ?>
  echo '<tr><td colspan="4">Nenhuma projetos cadastrada.</td></tr>';
                 <?php endif; ?>
                 
        </tbody>
    </table>
</div>

<a href="home.php"> Voltar</a>

<?php
require_once 'shared/footer.php';
