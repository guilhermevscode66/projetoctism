<?php
namespace Controller;
use Model\BancoHorasModel;
use Controller\MailController;
class BancoHorasController
{
    public function __construct()
    {    }
    public function loadAll() {
        $model = new BancoHorasModel;
        return $model->loadAll(); // Retorna todos os bancoHorass
    }

    public function loadByIpes($idprojeto, $idestagiario){
        $model = new BancoHorasModel;
        $result = $model->loadByIpes($idprojeto, $idestagiario);
        //var_dump($result);
        return $result;
    }
    public function loadById($id) {
        $model = new BancoHorasModel();
        return $model->loadById($id); // Retorna um bancoHoras específico
    }

    public function create($data) {
        $model = new BancoHorasModel();
        $model->setHoraEntrada($data['hora_entrada']);
        $model->setHoraSaida($data['hora_saida']);
        $model->setIdprojeto($data['idprojeto']);
        $model->setIdestagiario($data['idestagiario']);

        return $model->save(); // Salva no banco
    }

    public function update($id, $data) {
        $model = new BancoHorasModel();
        $model->setHoraEntrada($data['hora_entrada']);
        $model->setHoraSaida($data['hora_saida']);
        
        return $model->save(); // Atualiza no banco
    }

    public function delete($id) {
        $model = new BancoHorasModel();
        return $model->delete($id); // Exclui do banco
    }
    public function sendEmail(){
        if(isset($_REQUEST['estagiariosprojeto_id'])){
$controller = new MailController;
return $controller->send();
        }
    }
}
