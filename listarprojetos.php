<?php
require_once 'config.php';
require_once 'shared/header.php';
require_once './vendor/autoload.php';

use Controller\ProjetosController;

// Lógica de mensagens
$msg = $_GET['msg'] ?? null;
$controller = new ProjetosController();
$dados = $controller->loadAll();
?>

<div class="container mt-4">
    <?php if ($msg === 'sucesso_delete'): ?>
        <div class="alert alert-success alert-dismissible fade show">Projeto removido com sucesso!</div>
    <?php elseif ($msg === 'erro_delete'): ?>
        <div class="alert alert-danger alert-dismissible fade show">Erro ao tentar remover o projeto.</div>
    <?php elseif ($msg === 'sucesso_save'): ?>
        <div class="alert alert-success alert-dismissible fade show">Projeto salvo com sucesso!</div>
    <?php endif; ?>

    <h1 class="mb-4">Lista de Projetos</h1>
    
    <div class="mb-3">
        <a href="manterProjetos.php" class="btn btn-primary">Novo projeto</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Data de criação</th>
                <th style="width: 200px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($dados)): ?>
                <?php foreach ($dados as $projeto): ?>
                    <tr>
                        <td><?php echo $projeto->getId(); ?></td>
                        <td><?php echo $projeto->getNome()??'Nome não informado'; ?></td>
                        <td> 
    <?php 
        $data = $projeto->getDatacriacao();
        echo $data ? date('d/m/Y H:i', strtotime($data)) : 'Data não informada'; 
    ?>
</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="manterProjetos.php?id=<?php echo $projeto->getId(); ?>" 
                                   class="btn btn-warning btn-sm flex-fill">Editar</a>
                                
                                <a href="src/services/ServicesProjetos.php?id=<?php echo $projeto->getId(); ?>" 
                                   onclick="return confirm('Deseja excluir esse projeto?');" 
                                   class="btn btn-danger btn-sm flex-fill">Excluir</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Nenhum projeto cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="mt-3">
        <a href="home.php" class="btn btn-secondary">Voltar</a>
    </div>
</div>
<script>
    // Espera o documento carregar completamente
    document.addEventListener('DOMContentLoaded', function() {
        // Seleciona todos os alertas na página
        const alertas = document.querySelectorAll('.alert');

        alertas.forEach(function(alerta) {
            // Define um tempo de 4 segundos (4000ms) antes de começar a sumir
            setTimeout(function() {
                // Adiciona um efeito de transição suave usando CSS
                alerta.style.transition = "opacity 0.6s ease";
                alerta.style.opacity = "0";

                // Após a transição terminar, remove o elemento do layout
                setTimeout(function() {
                    alerta.remove();
                }, 600); 
            }, 4000);
        });
    });
</script>

<?php
require_once './shared/footer.php';
?>