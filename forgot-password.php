<?php
require_once 'shared/header.php';
require_once 'vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
<form action="src/services/ForgotPass.php" method="post">

<div class="mb-4">
    <input 
    type="hidden"
    name="idestagiario"
    value="<?php echo(isset($idestagiario)?$idestagiario:'' ); ?>"/>
    
    <input 
    type="hidden"
    name="idorientador"
    value="<?php echo(isset($idorientador)?$idorientador:'' ); ?>"/>
<h1> Recuperar senha</h1>
<p> Confirme seu e-mail: Vamos enviar um e-mail com um link para redefinir sua senha.</p>
<input
type="text"
name="email"
placeholder="Confirme seu e-mail"
value="<?php echo(isset($p)?$p->getEmail(): '')?>"/>
<?php
if(isset($error)){

if( $error == 'email_invalido'){
    echo '<small class="text-danger"> Formato de e-mail inválido. Insira um e-mail com um formato válido ex: voce.fulano@email.com</small>';
}
else if( $error =='email_falhou'){
    echo   ' <small class="text-danger"> Erro ao enviar o e-mail para redefinir a senha.</small>';
}
else if($error =='email_nao_existe'){
    echo '<small class ="text-danger"> O e-mail informado não existe em nossa base de dados</small>';
}
}
?>
<button type="submit" class="btn btn-success"> Avançar</button>
</form>
<a href="index.php">Voltar</a>
<?php
require_once 'shared/footer.php';
?>