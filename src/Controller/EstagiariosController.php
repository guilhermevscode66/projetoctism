<?php
namespace Controller;
use Model\EstagiariosModel;
use Model\EstagiariosProjetosModel;

class EstagiariosController
{
    public function __construct() {}
    public function loadAll() {
        $model = new EstagiariosModel();
        return $model->loadAll(); // Retorna todos os estagiarioss
    }
    
    public function loadById($id) {
        $model = new EstagiariosModel();
        return $model->loadById($id); // Retorna um estagiarios específico
    }
public function loadByMatricula($matricula){
    $model = new EstagiariosModel;
    return $model->loadByMatricula($matricula);
}
    public function create($data) {
        $model = new EstagiariosModel();
        $model->setNomecompleto($data['nomecompleto']);
        $model->setMatricula($data['matricula']);
        $model->setEmail($data['email']);
        $model->setSupervisor($data['supervisor']);
        $model->setMinHoras($data['MinHoras']);
        
        $model->setIdprojeto($data['idprojeto']);
$model->setIdorientador($data['idorientador']);
        $model->save(); // Salva no banco

        $idNovoEstagiario = $model->lastInsertId;
$idprojeto=$model->getidprojeto();
        $estagiariosProjetosModel = new EstagiariosProjetosModel;
        $estagiariosProjetosModel->setIdestagiarios($idNovoEstagiario);
        $estagiariosProjetosModel->setidprojeto($idprojeto);
        return   $estagiariosProjetosModel->save($data);

    }
    
public function CreateSenha($id, $senha){
        error_log("CreateSenha chamado: ID=$id");
            
    $model = new EstagiariosModel;
    $model->setId($id);
    $model->setSenha($senha);
    return $model->saveSenha();
}
    public function update($id, $data) {
        $model = new EstagiariosModel();
        $model->setId($id);
        $model->setNomecompleto($data['nomecompleto']);
        $model->setEmail($data['email']);
        $model->setMatricula($data['matricula']);
        $model->setSupervisor($data['supervisor']);
        $model->setMinHoras($data['MinHoras']);
$model->setidprojeto($data['idprojeto']);
$model->setidorientador($data['idorientador']);

        return $model->save(); // Atualiza no banco
    }

    public function delete($id) {
        $model = new EstagiariosModel();
        return $model->delete($id); // Exclui do banco
    }
    public function login($matricula, $senha){
        $model = new estagiariosModel();
$load= $model->loadByMatricula($matricula);

    if($matricula ==$load->getMatricula() && $senha = $load->getSenha()){
session_start();
        $_SESSION['idestagiario'] = $load->getId();    
           
            return true;
    }else{
        return false;
    }
}

            
            }

    

