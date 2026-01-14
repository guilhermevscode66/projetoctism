<?php
use Controller\OrientadoresController;
require_once 'shared/header.php';
require_once 'vendor/autoload.php';
require_once 'shared/csrf.php';

$controller = new OrientadoresController;
$orientador = null;

// Busca orientador se o ID estiver na URL
if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $orientador = $controller->loadById($id);
}

// Recupera dados da sessão caso tenha ocorrido um erro de validação
$p = $_SESSION['p'] ?? [];
$error = $_SESSION['error'] ?? null;

// Limpa a sessão após carregar nas variáveis locais
unset($_SESSION['p'], $_SESSION['error']);
?>

<div class="container mt-4">
    <h1 class="mb-4">
        <?= ($orientador && $orientador->getId()) ? 'Editar Orientador' : 'Novo Orientador' ?>
    </h1>
<?php if($error=='db_fail'): ?>
    <div class="alert alert-danger" role="alert">
        Erro ao salvar no banco de dados. Tente novamente mais tarde.
    </div>
    <?php endif; ?>
    <form method="post" action="src/services/OrientadoresServices.php">
        <?php csrf_input(); ?>
        
        <input type="hidden" name="id" value="<?= $orientador ? $orientador->getId() : ($p['id'] ?? '') ?>">

        <div class="mb-3">
            <label for="nome" class="form-label">Nome completo</label>
            <input
                type="text"
                class="form-control"
                name="nome"
                id="nome"
                value="<?= htmlspecialchars($p['nome'] ?? ($orientador ? $orientador->getNome() : '')) ?>"
                placeholder="Seu nome completo"
            />
            <?php if($error == 'nome_invalido'): ?>
                <small class="text-danger">Nome inválido.</small>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="matricula" class="form-label">Matrícula</label>
            <input
                type="text"
                class="form-control"
                name="matricula"
                id="matricula"
                value="<?= htmlspecialchars($p['matricula'] ?? ($orientador ? $orientador->getMatricula() : '')) ?>"
                placeholder="Digite sua Matrícula"
            />
            <?php if($error == 'matricula_invalida'): ?>
                <small class="text-danger">A matrícula informada é inválida.</small>
            <?php elseif($error == 'matricula_duplicada'): ?>
                <small class="text-danger">A matrícula informada já está em uso por outro orientador.</small>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input
                type="email" 
                class="form-control"
                name="email"
                id="email"
                value="<?= htmlspecialchars($p['email'] ?? ($orientador ? $orientador->getEmail() : '')) ?>"
                placeholder="Digite seu e-mail"
            />
            <?php if($error == 'email_invalido'): ?>
                <small class="text-danger">O endereço de e-mail informado é inválido.</small>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-success">
            <?= ($orientador && $orientador->getId()) ? 'Atualizar Orientador' : 'Cadastrar Orientador' ?>
        </button>
        
        <a href="home.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php require_once 'shared/footer.php'; ?>