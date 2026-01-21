<?php
require_once 'shared/header.php';
require_once 'vendor/autoload.php';
if( isset($_GET['idprojeto']) && isset($_GET['idestagiario']) ){
  $idprojeto= $_GET['idprojeto'];
  $idestagiario = $_GET['idestagiario'];
  

}
//veririfica se teve mensagem de erro na sessão
if( isset($_SESSION['error'])){
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    if($error ==='faltando_dados'){
        $alerta = ['classe' => 'alert-danger', 'texto' => ' Por favor, preencha todos os campos obrigatórios.'];
    }elseif($error === 'hora_invalida'){
        $alerta = ['classe' => 'alert-danger', 'texto' => ' A hora de entrada não pode ser maior que a hora de saída.'];
    }elseif($error === 'erro_ao_salvar'){
        $alerta = ['classe' => 'alert-danger', 'texto' => ' Ocorreu um erro ao salvar o registro de horas. Por favor, tente novamente.'];
    }
}
use Controller\BancoHorasController;
$controller=new BancoHorasController;
?>
<h2> Registro de horas</h2>
<script>
window.addEventListener("load", function() {
    // Pega data/hora atual
    let agora = new Date();

    // Formata hora:minuto (ex: 09:05)
    let horas = String(agora.getHours()).padStart(2, "0");
    let minutos = String(agora.getMinutes()).padStart(2, "0");
    let horaAtual = `${horas}:${minutos}`;

    
    // Formata data: Dia de semana, Dia, Mês, Ano
    let opcoes = { weekday: "long", year: "numeric", month: "long", day: "numeric" };
    let dataFormatada = agora.toLocaleDateString("pt-BR", opcoes);

    // Mostra no parágrafo
    document.getElementById("dataAtual").textContent = " data:" + dataFormatada;
});
</script>
<p id="dataAtual"></p>

<div class="mb-4">


<form id="FormBancoHoras" method="post" action="src/services/RegistroHorasServices.php">
  <?php require_once 'shared/csrf.php'; csrf_input(); ?>
  <input type="hidden" name = "idprojeto" value= "<?php echo(isset($_GET['idprojeto'])? $_GET['idprojeto']: '')?>">
  <input type="hidden" name = "idestagiario" value= "<?php echo(isset($_GET['idestagiario'])? $_GET['idestagiario']: '')?>">
  
  
  <label>Hora Entrada:</label for="hora_entrada">
  <input type="time" name="hora_entrada" placeholder="hora de início do seu turno">
</div>
<div class="mb-4">
  <label for="hora_saida">Hora Saída:</label>
  <input type="time" name="hora_saida" placeholder="Hora de fim do seu turno">
</div>
<br>
  <button type="submit" onclick="e()">Salvar</button>
</div>
</form>
<?php
require_once 'shared/footer.php';
?>
