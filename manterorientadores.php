<?php
use Controller\OrientadoresController;
require_once 'shared/header.php';
require_once 'vendor/autoload.php';
require_once 'shared/csrf.php';

$controller = new OrientadoresController;
$orientador = null;

// 1. Busca orientador para edição
if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $orientador = $controller->loadById($id);
}

// 2. RECUPERAÇÃO DE DADOS E ERROS (Prioridade para GET, fallback para SESSION)
$p = $_SESSION['p'] ?? [];
$error = $_GET['error'] ?? $_SESSION['error'] ?? null;

// Limpa as sessões de erro/rascunho para não repetirem ao dar F5
unset($_SESSION['p'], $_SESSION['error']);
?>

<div class="container mt-4">
    <h1 class="mb-4">
        <?= ($orientador && $orientador->getId()) ? 'Editar Orientador' : 'Novo Orientador' ?>
    </h1>

    <?php if($error === 'db_fail'): ?>
        <div class="alert alert-danger">Erro ao salvar no banco de dados. Tente novamente.</div>
    <?php endif; ?>

    <?php if($error === 'campos_vazios'): ?>
        <div class="alert alert-warning">Por favor, preencha todos os campos obrigatórios.</div>
    <?php endif; ?>

    <?php if($error === 'csrf_fail'): ?>
        <div class="alert alert-danger">Sessão expirada ou erro de validação. Recarregue a página.</div>
    <?php endif; ?>

    <form method="post" action="src/services/OrientadoresServices.php">
        <?php csrf_input(); ?>
        
        <input type="hidden" name="id" value="<?= $orientador ? $orientador->getId() : ($p['id'] ?? '') ?>">

        <div class="mb-3">
            <label for="nome" class="form-label">Nome completo</label>
            <input type="text" class="form-control" name="nome" id="nome" 
                   value="<?= htmlspecialchars($p['nome'] ?? ($orientador ? $orientador->getNome() : '')) ?>" required>
            <?php if($error === 'nome_duplicado'): ?>
                <small class="text-danger">Este nome já está cadastrado no sistema.</small>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="matricula" class="form-label">Matrícula</label>
            <input type="text" class="form-control" name="matricula" id="matricula" 
                   value="<?= htmlspecialchars($p['matricula'] ?? ($orientador ? $orientador->getMatricula() : '')) ?>" required>
            <?php if($error === 'matricula_invalida'): ?>
                <small class="text-danger">A matrícula deve ser numérica.</small>
            <?php elseif($error === 'matricula_duplicada'): ?>
                <small class="text-danger">Esta matrícula já pertence a outro orientador.</small>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" id="email" 
                   value="<?= htmlspecialchars($p['email'] ?? ($orientador ? $orientador->getEmail() : '')) ?>" required>
            <?php if($error === 'email_invalido'): ?>
                <small class="text-danger">Digite um e-mail válido.</small>
            <?php elseif($error === 'email_duplicado'): ?>
                <small class="text-danger">Este e-mail já está em uso.</small>
            <?php elseif($error === 'email_fail'): ?>
                <small class="text-warning">Cadastro salvo, mas o e-mail de ativação não pôde ser enviado.</small>
            <?php endif; ?>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">
                <?= ($orientador && $orientador->getId()) ? 'Salvar Alterações' : 'Cadastrar Orientador' ?>
            </button>
            <a href="listarorientadores.php" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
</div>

<?php require_once 'shared/footer.php'; ?>