<?php
require_once 'shared/header.php';

use Controller\BancoHorasController;
use Controller\EstagiariosController;

require_once 'vendor/autoload.php';
?>
<div class="mb -4">
  <h2> Horas registradas</h2>
  <table class="table">
    <thead>
      <tr>
        
        <th scope="col">início do turno</th>
        <th scope="col">fim do turno</th>
        <th scope="col">Data</th>
      </tr>
    </thead>
    <tbody>
      
 <?php     $banco = new BancoHorasController; ?>
      

  <?php  $idprojeto= $_REQUEST['idprojeto']; ?>
  <?php $idestagiario = $_REQUEST['idestagiario']; ?>

  
    <?php   $loadbanco = $banco->loadByIpes($idprojeto, $idestagiario);?>
     <?php // var_dump($loadbanco);
          //percorre o banco
          foreach ($loadbanco as $valuebanco): ?>

             <tr>
               <td><?php echo  $valuebanco->getHoraEntrada(); ?> </td>
               <td> <?php echo $valuebanco->getHoraSaida() ; ?></td>
               <td> <?php echo date('d/m/y H:m:s', $valuebanco->getData()); ?></td>
    </tr>
          <?php endforeach; ?>

     
      
    </tbody>
  </table>
<a href="home.php"> Voltar </a>

</div>
<?php
require_once 'shared/footer.php';
?>