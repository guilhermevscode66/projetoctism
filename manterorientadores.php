<?php
use Controller\OrientadoresController;
use Controller\ProjetosController;
require_once 'shared/header.php';
require_once 'vendor/autoload.php';
$controller = new OrientadoresController;
if(isset($_REQUEST['id'])){
    $id =$_REQUEST['id'];
    $orientador = $controller->loadById($id);
}
//recupera os dados da sessão da services
$p=[];
$error=null;
if(isset($_SESSION['p'])&& isset($_SESSION['error'])){
$p=$_SESSION['p'];
$error=$_SESSION['error'];
//limpa a sessão após usar
unset($_SESSION['p'], $_SESSION['error']);
}
?>
<div>
<h1 class ="mb-4"><?=isset($orientador)&&$estagiario->getId() ? 'Editar orientador' : 'Novo orientador'?></h1>
</div>
<form method="post" action="src/services/OrientadoresServices.php">
<input type="hidden" value="<?php echo(isset($orientador)?$orientador->getId():'' )?>"/>
<div class="mb-3">
    <label for="nomecompleto" class="form-label"> Nome completo </label>
    <input
        type="text"
        class="form-control"
        name="nome"
        id="nome"
        value="<?php echo(isset($p['nomecompleto'])?$p['nomecompleto']:'');?>"
        placeholder="Seu nome completo"
    />
    <?php
if(isset($error)&& $error =='nome_invalido'){
    echo '<small class="text-danger"> Nome inválido. </small>';
}
    ?>
</div>
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
    if(isset($error) && $error == 'matricula_invalida'){
        echo '<small class="text-danger"> A matrícula informada é inválida </small>';
    }

    ?>
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
    if(isset($error)&& $error =='email_invalido'){
        echo '<small class="text-danger"> O endereço de e-mail informado é inválido </small>';
    }

    ?>
</div>
<div class="mb-3">
<select name="idprojeto">
<?php
$controller = new ProjetosController;
$projetos = $controller->loadAll();
foreach($projetos as $value){
echo '<option value ="'.$value->getId().'"> '.$value->getNome().'</option> ';

}
?>
</select>

</div>

<button type="submit" class="btn btn-success"> <?= isset($orientador)&& $orientador->getId()? 'Atualizar orientador': 'Cadastrar orientador'?> </button>
    
<a href="home.php"> Cancelar</a>
    </form>
<?php
require_once 'shared/footer.php';
?>
