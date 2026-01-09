<?php
    // Inicia a sessão antes de qualquer output
require_once 'shared/header.php';
require_once 'vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Recupera os dados da sessão quando há erros
$p = null;
$error = null;

if (isset($_SESSION['p']) && isset($_SESSION['error'])) {
    $p = $_SESSION['p']; // Assume-se que 'p' seja um objeto ou array vindo do serviço
    $error = $_SESSION['error'];
    // Limpa as chaves da sessão depois de usar
    unset($_SESSION['p'], $_SESSION['error']);
}

// Inicializa variáveis para evitar erros de "Undefined variable"
$idestagiario = $_GET['idestagiario'] ?? ''; 
$idorientador = $_GET['idorientador'] ?? '';
?>

<?php require_once 'shared/csrf.php'; ?>

<div class="container mt-5">
    <form action="src/services/ForgotPass.php" method="post" class="col-md-6 offset-md-3">
        <?php csrf_input(); ?>

        <h1>Recuperar senha</h1>
        <p>Confirme seu e-mail: Vamos enviar um e-mail com um link para redefinir sua senha.</p>

        <input type="hidden" name="idestagiario" value="<?php echo htmlspecialchars($idestagiario); ?>"/>
        <input type="hidden" name="idorientador" value="<?php echo htmlspecialchars($idorientador); ?>"/>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input 
                type="text" 
                name="email" 
                id="email"
                class="form-control" 
                placeholder="Ex: voce.fulano@email.com"
                value="<?php echo (is_object($p) ? $p->getEmail() : ($p['email'] ?? '')); ?>"
            />
            
            <?php if (isset($error)): ?>
                <div class="mt-2">
                    <?php if ($error == 'email_invalido'): ?>
                        <small class="text-danger">Formato de e-mail inválido. Insira um e-mail com um formato válido ex: voce.fulano@email.com</small>
                    <?php elseif ($error == 'email_falhou'): ?>
                        <small class="text-danger">Erro ao enviar o e-mail para redefinir a senha.</small>
                    <?php elseif ($error == 'email_nao_existe'): ?>
                        <small class="text-danger">O e-mail informado não existe em nossa base de dados.</small>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-success">Avançar</button>
        <a href="index.php" class="btn btn-link">Voltar</a>
    </form>
</div>

<?php
require_once 'shared/footer.php';
?>