<?php
require_once './shared/header.php';
//require_once './src/controller/autenticationController.php';

use Controller\ProjetosController;


require_once './vendor/autoload.php';

$controller = new ProjetosController();


// Se estiver editando, carrega os dados

//var_dump($projetos);
if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    $projetos = $controller->loadById($id);
}
//recupera os dados da sessão
$p=[];
$error=null;
if(isset($_SESSION['p'])){
$p=$_SESSION['p'];
$error=$_SESSION['error'];
unset($_SESSION['p'], $_SESSION['error']);
}
?>

<div class="container my-4">
    <h1 class="mb-4"> <?= isset($projetos) &&$projetos->getId()? 'Editar Projeto' : 'Novo Projeto' ?></h1>
    <div class="card p-4">
        <h4>Informe os dados do projetos:</h4>
        <form action="./src/services/ServicesProjetos.php" method="POST">
            <?php require_once 'shared/csrf.php'; csrf_input(); ?>
            <div class="mb-3">
                <input type="hidden" name="id" value="<?php echo(@$projetos?$projetos->getId():'') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Nome:</label>
                <input
                 type="text"
                  name="nome"
                   class="form-control"
                    value="<?php echo(isset($p['nome'])?$p['nome']:'')?>"
                    placeholder="Digite o nome do projeto"
                    />
                    <?php
                    if(isset($error) &&$error='nome_proj_invalido'){
                        echo '<small class="text-danger"> Nome inválido</small>';
                    }
                    ?>
            </div>
            <br>
            <button type="submit" class="btn btn-success">Salvar</button>

            <a href="listarprojetos.php" class="btn btn-secondary">Voltar</a>

        </form>
    </div>
</div>
<?php
require_once './shared/footer.php';
?>