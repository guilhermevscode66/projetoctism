<?php
// Mover o session_start para o topo evita avisos de "headers already sent"

require_once 'shared/header.php';
require_once 'vendor/autoload.php';

use Controller\EstagiariosController;
use Controller\OrientadoresController;

?>
<div class="container mt-4">
    <h2 class="mt-4"> Sistema de controle de horários</h2>
    <p> Este site possibilita que os estagiários consigam registrar as horas do seu estágio de uma forma fácil e conveniente. Os orientadores conseguem gerenciar seus projetos e estagiários.</p>
    
    <h3 class="mt-4">Como usar?</h3>
    <ul class="list-group list-group-numbered">
        <li class="list-group-item active">Para conseguir cadastrar novos estagiários ou projetos, ou visualizar as suas informações, você precisa fazer login com sua matrícula e senha.</li>
        <li class="list-group-item">Para cadastrar um novo estagiário, clique em "novo estagiário" e para cadastrar um novo projeto clique em "Novo projeto"</li>
        <li class="list-group-item">Para registrar as horas trabalhadas, clique em "registrar horas". Ajuste os valores e clique em salvar para adicionar à lista.</li>
        <li class="list-group-item">Para visualizar as horas já registradas, clique em "ver suas horas registradas".</li>
    </ul>
    
    <div class="mt-4 d-flex gap-2 flex-wrap">
    <?php
    // Lógica para ESTAGIÁRIO
    if (!empty($_SESSION['idestagiario'])) {
        $controller = new EstagiariosController;
        $load = $controller->loadById($_SESSION['idestagiario']);

        // Verifica se o objeto foi carregado com sucesso (se existe no banco)
        if ($load && $load->getId()) {
            $idProjeto = $load->getidprojeto() ?? 0;
            $idEstag = $load->getId();

            echo '<a tabindex="0" href="registrohoras.php?idprojeto=' . $idProjeto . '&idestagiario=' . $idEstag . '" class="btn btn-primary">Lançar horas</a>';
            echo '<a href="horastrabalhadas.php?idestagiario=' . $idEstag . '&idprojeto=' . $idProjeto . '" class="btn btn-primary" tabindex="0">ver suas horas registradas</a>';
        } else {
            echo '<div class="alert alert-warning">Erro ao carregar dados do estagiário. Faça login novamente.</div>';
        }

    // Lógica para ORIENTADOR
    } elseif (!empty($_SESSION['idorientador'])) {
        $controller = new OrientadoresController;
        $load = $controller->loadById($_SESSION['idorientador']);

        if ($load && $load->getId()) {
            $idOri = $load->getId();

            echo '<a tabindex="0" href="manterprojetos.php" class="btn btn-primary">Novo projeto</a>';
            echo '<a tabindex="0" href="listarprojetos.php?idorientador=' . $idOri . '" class="btn btn-primary">Listar projetos</a>';
            echo '<a tabindex="0" href="manterestagiarios.php?idorientador=' . $idOri . '" class="btn btn-primary">Novo estagiário</a>';
            echo '<a tabindex="0" href="listarestagiarios.php?idorientador=' . $idOri . '" class="btn btn-primary">Listar estagiários</a>';
            echo '<a tabindex="0" href="manterorientadores.php?idorientador=' . $idOri . '" class="btn btn-warning">Editar meus dados</a>';
        } else {
            echo '<div class="alert alert-warning">Erro ao carregar dados do orientador. Faça login novamente.</div>';
        }

    // Caso não esteja logado
    } else {
        echo '<strong>Faça login para poder acessar o sistema de banco de horas.</strong> ';
        echo '<a href="index.php" class="btn btn-primary ms-2">Fazer login</a>';
    }
    ?>   
    </div>
</div>

<?php
require_once 'shared/footer.php';
?>