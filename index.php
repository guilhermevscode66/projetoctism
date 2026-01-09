<?php
require_once 'shared/header.php';
require_once 'vendor/autoload.php';
require_once 'shared/csrf.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

//importo a usuarioscontroller
use Controller\UsuariosController;



?>
<div class="container mt-4">
<h2 class="mb-4">Autenticação</h2>
<p> Faça login para acessar o sistema de controle de horários</p>
<p> Qual seu tipo de usuário?</p>

<form method="post" action="src/services/AuthenticationService.php">
    <select name="tipousuario">
        <?php
    $controller = new UsuariosController;
    
    $usuarioslist = $controller->loadAll();
    foreach($usuarioslist as $value){
    echo ' <option value = "'.$value->getId().'" > '.$value->gettipo().'</option>';
    }
    ?>
    </select>
    <?php csrf_input(); ?>

<div class="mb-3">
    <label for="usuario" class="form-label">Usuário (matrícula)</label>
    <input
        type="text"
        class="form-control"
        name="matricula"
        id="matricula"
        
        placeholder="Usuário"
    />
    <?php
     if(isset($error)){
        switch($error){
            case 'faltando_dados':
                echo '<small class ="text-danger"> Todos os dados são obrigatórios</small>';
                break;
                case 'tipo_usuario_invalido':
                    echo '<small class ="text-danger"> Tipo de usuário inválido</small>';
                    break;
                    case 'credenciais_invalidas':
                        echo '<small class="text-danger"> Credenciais inválidas</small>';
                        break;
                        case 'erro_sistema':
                            echo '<small class ="text-da ger"> Erro no login.</small>';
                            break;
                            default:
                           echo '<small class="text-danger"> Ocorreu um erro. tente novamente mais tarde.</small>';
                           break;
        }
        }
      ?>
</div>
<div class="mb-3">
    <label for="senha" class="form-label">senha</label>
    <input
        type="password"
        class="form-control"
        name="senha"
        id="senha"

        placeholder="Digite sua senha"
    /> 
    <button type="button" class="mostrarsenha" data-target="senha" aria-controls="senha" aria-pressed="false" aria-label="Mostrar senha">Mostrar senha</button>
</div>

<button class="btn btn-success">Entrar</button>

</form>
<div class="mt-3">
    <a href="forgot-password.php" class="me-3">Esqueci minha senha</a>
    <a href="home.php">Como usar o sistema?</a>
</div>
</div>

<?php
require_once 'shared/footer.php';
?>