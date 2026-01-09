<?php
namespace Controller;
use Model\OrientadoresModel;

class OrientadoresController
{


    public function loadAll() {
        $model = new OrientadoresModel();

        return $model->loadAll(); // Retorna todos os projetoss

    }
public function loadByEmail($email){
        $model = new OrientadoresModel();
        return $model->loadByEmail($email); // Retorna um orientador específico
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

    
public function CreateSenha($id, $senha){
    $model = new OrientadoresModel;
    $model->setId($id);
    $model->setSenha($senha);
    return $model->saveSenha();
}

    public function delete($id) {
        $model = new OrientadoresModel();
        return $model->delete($id); // Exclui do banco
    }
}
