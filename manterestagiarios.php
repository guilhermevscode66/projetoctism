<?php
use Controller\EstagiariosController;
use Controller\ProjetosController;
use Controller\OrientadoresController;

require_once 'shared/header.php';
require_once 'vendor/autoload.php';
require_once 'shared/csrf.php';
$controller = new EstagiariosController;
if(isset($_REQUEST['id'])){
    $id =$_REQUEST['id'];
    $estagiario = $controller->loadById($id);
}
//recupera os dados da sessão da services
$p=[];
// normalizar código de retorno (evita notices caso não exista)
$cod = isset($_REQUEST['cod']) ? $_REQUEST['cod'] : null;

if(isset($_SESSION['p'])){
$p=$_SESSION['p'];

//limpa a sessão após usar
unset($_SESSION['p']);
}
?>
<div>
<h1 class ="mb-4"><?=isset($estagiario)&&$estagiario->getId() ? 'Editar estagiário' : 'Novo estagiário'?></h1>
</div>
<form method="post" action="src/services/EstagiariosServices.php">
    <?php csrf_input(); ?>
<input type="hidden" name ="id" value="<?php echo(isset($estagiario)?$estagiario->getId():'' );?>"/>
<div class="mb-3">
    <label for="nomecompleto" class="form-label"> Nome completo </label>
    <input
        type="text"
        class="form-control"
        name="nomecompleto"
        id="nomecompleto"
        value="<?php echo(isset($p['nomecompleto'])?$p['nomecompleto']:'');?>"
        placeholder="Seu nome completo"
    />
    
</div>
<div class="mb-3">
    <label for="email" class="form-label"> e-mail</label>
    <input
        type="text"
        class="form-control"
        name="email"
        value="<?php echo(isset($p['email'])?$p['email']:'');?>"
        id="email"
        
        placeholder=" Digite seu e-mail"
    />
    <?php
    if($cod === 'email_invalido'){
        echo '<small class="text-danger"> O endereço de e-mail informado é inválido </small>';
    }

    ?>
</div>
<br>
<div class="mb-3">
    <label for="matricula" class="form-label"> Matrícula</label>
    <input
        type="text"
        class="form-control"
        name="matricula"
        value="<?php echo(isset($p['matricula'])?$p['matricula']:'');?>"
        id="matricula"
        placeholder=" Digite suaMatrícula"
    />
    <?php
    if($cod === 'matricula_invalida'){
        echo '<small class="text-danger"> A matrícula informada é inválida </small>';
    }

    ?>
</div>
<div class="mb-3">
    <br>
</div>
<div class="mb-3">
    <label for="supervisor" class="form-label">supervisor</label>
    <input
        type="text"
        class="form-control"
        name="supervisor"
        id="supervisor"
        value="<?php echo(isset($p['supervisor'])?$p['supervisor']:'');?>"
        placeholder="Digite o nome do seu supervisor"
    />
</div>

<div class="mb-3">
    <label for="MinHoras" class="form-label"> Horas Mínimas</label>
    <input
        type="number"
        min="200"
        max="400"
        class="form-control"
        name="MinHoras"
        id="MinHoras"
        
        placeholder="Mínimo de horas do estágio"
    />
    
</div>


    <label for="projeto" class="form-label">Projeto</label>
    
    <select name="idprojeto" id="projeto">
        
<?php

$projetosController= new ProjetosController;
$projetoslist=$projetosController->loadAll();

foreach($projetoslist as $projeto){
   echo '<option  value="'. $projeto->getId().'">'.$projeto->getNome().'</option>';
}
?>
    </select>
  <br>  
</div>
<div class="mb-3">
    <label for="Orientador" class="form-label">Orientador</label>
    <select name="idorientador">
<?php
$orientadoresController= new OrientadoresController;

$orientadoreslist = $orientadoresController->loadAll();
foreach($orientadoreslist as $value){
echo ' <option value = "'.$value->getId().'" > '.$value->getnomeorientador().' </option>';

}

?>
</select>
<br>

<button type="submit" class="btn btn-success"> <?= isset($estagiario)&& $estagiario->getId()? 'Atualizar estagiário': 'Cadastrar estagiário'?> </button>
    
</form>
<a href="home.php"> Cancelar</a>

<?php
require_once 'shared/footer.php';
?>