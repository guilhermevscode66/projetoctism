<?php
require_once 'shared/header.php';
use Controller\EstagiariosController;
use Controller\OrientadoresController;
require_once 'vendor/autoload.php';
?>
<h2 class="mt-4"> Sistema de controle de horários</h2>
<p> Este site possibilita que voc~e consiga registrar as horas do seu estágio de uma forma fácil e conveniente.</p>
<h3 class="mt-4">Como usar?</h3>
<ul class="list-group list-group-numbered">
    <li class="list-group-item active">Para conseguir cadastrar novos estagiários ou projetos ou visualizar as suas informações, você precisa fazer login com sua matrícula e senha do portal do aluno.</li>
    <li class="list-group-item">Para cadastrar um novo estagiário, clique em "novo estagiário" e para cadastrar um novo projeto clique em "Novo projeto"</li>
    <li class="list-group-item">Para registrar as horas que você trabalhou, clique em "registrar horas" vai aparecer a data atual com as horas, minutos e segundos, para registrar como hora de entrada clique em "registrar como hora de entrada" e para registrar como hora de saída, clique em "hora de  saída"</li>
    
</ul>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['idestagiario'])) {
    $idestagiario = $_SESSION['idestagiario'];
    $controller = new EstagiariosController;
    $load = $controller->loadById($idestagiario);

    echo '<a tabindex="0" href="registrohoras.php?idprojeto=' . $load->getidprojeto() . '&idestagiario=' . $load->getId() . '" class="badge bg-primary">Lançar horas</a>';
    echo '<a href="horastrabalhadas.php?idestagiario=' . $load->getId() . '&idprojeto=' . $load->getidprojeto() . '" class="badge bg-primary" tabindex="0">ver suas horas registradas</a>';

} elseif (!empty($_SESSION['idorientador'])) {
    $idorientador = $_SESSION['idorientador'];
    $controller = new OrientadoresController;
    $load = $controller->loadById($idorientador);

    echo '<a tabindex="0" href="manterprojetos.php" class="badge bg-primary">Novo projeto</a>';
    echo '<a tabindex="0" href="listarprojetos.php?idorientador=' . $load->getId() . '" class="badge bg-primary">Listar projetos</a>';
    echo '<a tabindex="0" href="manterestagiarios.php?idorientador=' . $load->getId() . '" class="badge bg-primary">Novo estagiário</a>';
    echo '<a tabindex="0" href="listarestagiarios.php?idorientador=' . $load->getId() . '" class="badge bg-primary">Listar estagiários</a>';

} else {
    echo '<strong>Faça login para poder acessar o sistema de banco de horas.</strong> ';
    echo '<a href="index.php" class="badge bg-primary">Fazer login</a>';
}
?>   


<?php
require_once 'shared/footer.php';
?>