<?php
require_once 'shared/header.php';
require_once 'vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
use Controller\EstagiariosController;
use Controller\OrientadoresController;

if(isset($_REQUEST['idestagiario'])){
    

    $idestagiario = $_REQUEST['idestagiario'];
    $estagiario = new EstagiariosController;
    $load = $estagiario->loadById($idestagiario);
}
else if(isset($_REQUEST['idorientador'])){
    $idorientador = $_REQUEST['idorientador'];
    $orientador = new OrientadoresController;
$load = $orientador->loadById($idorientador);
}
else{ //se não tiver nenhum id
    die('Nenhum id especificado. Acesse esta página com um id válido.');
    }
//recupera os dados da sessão quando ha erros
$p=[];
$error= null;
if(isset($_SESSION['p']) && isset($_SESSION['error'])){
    $p = $_SESSION['p'];
$error= $_SESSION['error'];
// limpa as chaves da sessão depois de usar
unset($_SESSION['p'], $_SESSION['error']);
}

?>
<h1> Criar uma senha de acesso</h1>
<p> Crie uma senha para fazer login no sistema de banco de horas: </p>

<form action= "src/services/PassVerify.php" method="post">
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.mostrarsenha');
    
    buttons.forEach(function(toggleButton){
    toggleButton.addEventListener('click', function() {
        const targetId= this.getAttribute('data-target');
const passwordField= document.getElementById(targetId);
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            this.textContent = 'Ocultar senha';
            passwordField.setAttribute('aria-label', 'Senha visível');
        } else {
            passwordField.type = 'password';
            this.textContent = 'Mostrar senha';
            passwordField.setAttribute('aria-label', 'Senha oculta');
        }
    });
});
});
</script>
<!-- fim do js -->

<div class="mb-4">
    <input 
    type="hidden"
    name="idestagiario"
    value="<?php echo(isset($idestagiario)?$idestagiario:'' ); ?>"/>
    
    <input 
    type="hidden"
    name="idorientador"
    value="<?php echo(isset($idorientador)?$idorientador:'' ); ?>"/>

    <label> Senha</label>
    <input
     type="password" 
     name="senha1"
     id="senha1"
     class="campo-senha"
     placeholder="Digite uma senha"
     minlength="8"
     maxlength="32"
     value="<?php echo(isset($p['senha'])? $p['senha'] :'')?> "
     />
          <?php
if(isset($error) && $error=='senha_invalida'){
    echo '<small class = "text-danger"> As senhas devem ter no mínimo 8 e no máximo 32 caracteres.</small>';
}
if(isset($error) && $error=='senhas_diferentes'){
    echo '<small class ="text-danger"> As senhas não conferem. </small>';
}
if(isset($error) && $error=='email_falhou'){
    echo '<small class ="text-danger"> Não foi possível enviar a confirmação por e-mail</small>';
}
if(isset($error) && $error =='cadastro_falhou'){
echo '<small class ="text-danger"> Não foi possível cadastrar a senha</small>';

}

?>

     <button type="button" id="btn1" class="mostrarsenha" data-target="senha1" > Mostrar Senha</button>

</div>

<div class="mb-4">
    <label> Confirmar senha</label>
    <input
     type="password" 
     name="senha2"
     id="senha2"
     class="campo-senha"
     placeholder="Digite novamente a mesma  senha"
     value="<?php echo(isset($p['confirmasenha'])? $p['confirmasenha'] :'')?> "
     />
     <button type="button"  id="btn2" class="mostrarsenha" data-target="senha2" > Mostrar Senha</button>

</div>

<button type="submit" class="btn btn-success"> Prosseguir</button>
</form>
<?php
require_once 'shared/footer.php';
?>