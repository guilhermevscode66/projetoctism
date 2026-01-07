<?php
namespace Controller;
use Model\OrientadoresModel;

class OrientadoresController
{
//função de login
public function login($matricula, $senha){
        $model = new OrientadoresModel();
        $load = $model->loadByMatricula($matricula);
        if($matricula ==$load->getMatricula($matricula) && $senha == $load->getSenha()){
            session_start();
        $_SESSION['idorientador'] = $load->getId();    
    
            return true;
        }else{
            return false;
        }
    }
//fim da função


    public function loadAll() {
        $model = new OrientadoresModel();

        return $model->loadAll(); // Retorna todos os projetoss

    }

    public function loadById($id) {
        $model = new OrientadoresModel();
        return $model->loadById($id); // Retorna um projetos específico
    }

    public function loadByMatricula($matricula){
        $model = new OrientadoresModel;
        return $model->loadByMatricula($matricula);
    }
    
    public function create($data) {
        $model = new orientadoresModel();
        $model->setnomeorientador($data['nome']);
        $model->setMatricula($data['matricula']);
$model->setEmail($data['email']);
$model->setidprojeto($data['idprojeto']);
        return $model->save(); // Salva no banco
    }

    public function update($id, $data) {
        $model = new OrientadoresModel();
        
        $model->setnomeorientador($data['nome']);
        $model->setEmail($data['email']);
$model->setidprojeto($data['idprojeto']);
        
        return $model->save(); // Atualiza no banco
    }

    
public function CreateSenha($id, $data){
    $model = new OrientadoresModel;
    $model->setSenha($data['senha']);
    return $model->saveSenha();
}

    public function delete($id) {
        $model = new OrientadoresModel();
        return $model->delete($id); // Exclui do banco
    }
}
