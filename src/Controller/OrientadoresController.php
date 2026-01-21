<?php
namespace Controller;
use Model\OrientadoresModel;

class OrientadoresController
{


    public function loadAll() {
        $model = new OrientadoresModel();

        return $model->loadAll(); // Retorna todos os projetoss

    }

    public function loadByNome($nome){
        $model = new OrientadoresModel();
        return $model->loadByNome($nome); // Retorna um orientador específico
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
    
    public function finalisarVinculo($idestagiario, $idprojeto) {
        $model = new OrientadoresModel;
        return $model->finalisarVinculo($idestagiario, $idprojeto);
    }

    public function finalisarHoras($idestagiario, $idprojeto){
        $model = new OrientadoresModel;
        return $model->finalisarHoras($idestagiario, $idprojeto);
    }
    public function create($data) {
            
        $model = new OrientadoresModel();
        $model->setnomeorientador($data['nomeorientador']);
        $model->setMatricula($data['matricula']);
$model->setEmail($data['email']);
         $model->save(); // Salva no banco
         $idNovoOrientador = $model->lastInsertId;
$idprojeto=$model->getidprojeto();
        $OrientadoresProjetosModel= new OrientadoresProjetosModel;
        $OrientadoresProjetosModel->setidorientador($idNovoOrientador);
        $OrientadoresProjetosModel->setidprojeto($idprojeto);
        return   $OrientadoresProjetosModel->save($data);

    }

    public function update($id, $data) {
        $model = new OrientadoresModel();
            $model->setId($id);   
        $model->setnomeorientador($data['nomeorientador']);
        $model->setEmail($data['email']);
        
         $model->save(); // Atualiza no banco
         $idprojeto=$model->getidprojeto();
        $OrientadoresProjetosModel= new OrientadoresProjetosModel;
        $OrientadoresProjetosModel->setidorientador($idNovoOrientador);
        $OrientadoresProjetosModel->setidprojeto($idprojeto);
        return   $OrientadoresProjetosModel->save($data);

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
