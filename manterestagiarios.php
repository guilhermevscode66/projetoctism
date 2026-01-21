<?php
use Controller\EstagiariosController;
use Controller\ProjetosController;
use Controller\OrientadoresController;

require_once 'shared/header.php';
require_once 'vendor/autoload.php';
require_once 'shared/csrf.php';

$controller = new EstagiariosController;
$estagiario = null;

if(isset($_REQUEST['id'])){
    $id = (int)$_REQUEST['id'];
    $estagiario = $controller->loadById($id);
}

$p = $_SESSION['p'] ?? [];
$cod = $_REQUEST['cod'] ?? null;
unset($_SESSION['p']);

// Helper para facilitar a lógica de "selected" e preenchimento
function getValue($field, $obj, $session) {
    if (isset($session[$field])) return htmlspecialchars($session[$field]);
    if ($obj && method_exists($obj, 'get' . $field)) return htmlspecialchars($obj->{'get' . $field}());
    return '';
}


// 1. Captura o código da URL de forma segura
$cod = $_GET['cod'] ?? null;
$alerta = null;

// 2. Define a mensagem baseada no código (evitando o Fatal Error)
if ($cod) {
    switch ($cod) {
        case 'email_duplicado':
            $alerta = ['classe' => 'alert-danger', 'texto' => 'Este e-mail já está cadastrado para outro usuário.'];
            break;
        case 'nome_duplicado':
            $alerta = ['classe' => 'alert-danger', 'texto' => 'Já existe um estagiário com este nome completo.'];
            break;
        case 'matricula_duplicada':
            $alerta = ['classe' => 'alert-danger', 'texto' => 'Esta matrícula já pertence a outro estagiário.'];
            break;
        case 'email_invalido':
            $alerta = ['classe' => 'alert-warning', 'texto' => 'O formato do e-mail digitado é inválido.'];
            break;
        case 'campos_vazios':
            $alerta = ['classe' => 'alert-warning', 'texto' => 'Por favor, preencha todos os campos.'];
            break;
        case 'erro_cadastro':
            $alerta = ['classe' => 'alert-danger', 'texto' => 'Erro ao salvar no banco de dados. Tente novamente.'];
            break;
        case 'erro_email':
            $alerta = ['classe' => 'alert-warning', 'texto' => 'Estagiário salvo, mas o e-mail de ativação falhou.'];
            break;
    }
}
?>



<div class="container mt-4">
<?php if ($alerta): ?>
        <div class="alert <?= $alerta['classe'] ?> alert-dismissible fade show" role="alert">
            <strong>Atenção:</strong> <?= $alerta['texto'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <h1 class="mb-4"><?= ($estagiario && $estagiario->getId()) ? 'Editar estagiário' : 'Novo estagiário' ?></h1>

    <form method="post" action="src/services/EstagiariosServices.php">
        <?php csrf_input(); ?>
        <input type="hidden" name="id" value="<?= $estagiario ? $estagiario->getId() : '' ?>"/>
<?php if($cod === 'campos_vazios'): ?>
    <div class="alert alert-danger" role="alert">
        Por favor, preencha todos os campos obrigatórios.
    </div>
    <?php endif; ?>
    <?php if($cod==='erro_cadastro'): ?>
    <div class="alert alert-danger" role="alert">   
        Erro ao salvar o estagiário. Por favor, tente novamente.
    </div>
    <?php endif; ?>
        <div class="mb-3">
            <?php if($cod==='erro_csrf'): ?>
            <div class="alert alert-danger" role="alert">
                Erro de validação. Por favor, recarregue a página e tente novamente.    
            </div>
            <?php endif; ?>
            <label for="nomecompleto" class="form-label">Nome completo</label>
            <input type="text" class="form-control" name="nomecompleto" id="nomecompleto" 
                   value="<?= htmlspecialchars($p['nomecompleto'] ?? ($estagiario ? $estagiario->getNomeCompleto() : '')) ?>" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" name="email" id="email" 
                       value="<?= htmlspecialchars($p['email'] ?? ($estagiario ? $estagiario->getEmail() : '')) ?>">
                

                
            </div>
            <div class="col-md-6 mb-3">
                
                
                <label for="matricula" class="form-label">Matrícula</label>
                <input type="text" class="form-control" name="matricula" id="matricula" 
                       value="<?= htmlspecialchars($p['matricula'] ?? ($estagiario ? $estagiario->getMatricula() : '')) ?>">


            </div>
        </div>

        <div class="mb-3">
            <label for="supervisor" class="form-label">Supervisor (Empresa)</label>
            <input type="text" class="form-control" name="supervisor" id="supervisor" 
                   value="<?= htmlspecialchars($p['supervisor'] ?? ($estagiario ? $estagiario->getSupervisor() : '')) ?>">
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="projeto" class="form-label">Projeto</label>
                <select name="idprojeto" id="projeto" class="form-select">
                    <option value="">Selecione o Projeto</option>
                    <?php
                    $projetosController = new ProjetosController;
                    foreach($projetosController->loadAll() as $proj) {
                        $selected = ($proj->getId() == ($p['idprojeto'] ?? ($estagiario ? $estagiario->getIdProjeto() : ''))) ? 'selected' : '';
                        echo "<option value='{$proj->getId()}' $selected>{$proj->getNome()}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label for="idorientador" class="form-label">Orientador</label>
                <select name="idorientador" class="form-select">
                    <option value="">Selecione o Orientador</option>
                    <?php
                    $orientadoresController = new OrientadoresController;
                    foreach($orientadoresController->loadAll() as $orient) {
                        $selected = ($orient->getId() == ($p['idorientador'] ?? ($estagiario ? $estagiario->getIdOrientador() : ''))) ? 'selected' : '';
                        echo "<option value='{$orient->getId()}' $selected>{$orient->getnomeorientador()}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label for="MinHoras" class="form-label">Horas Mínimas</label>
                <input type="number" class="form-control" name="MinHoras" id="MinHoras" min="200" max="400" 
                       value="<?= $p['MinHoras'] ?? ($estagiario ? $estagiario->getMinHoras() : '200') ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">
                <?= ($estagiario && $estagiario->getId()) ? 'Atualizar' : 'Cadastrar' ?> Estagiário
            </button>
            <a href="home.php" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once 'shared/footer.php'; ?>