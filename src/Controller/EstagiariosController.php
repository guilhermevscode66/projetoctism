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
    public function loadByEmail($email){
        $model = new EstagiariosModel();
        return $model->loadByEmail($email); // Retorna um estagiario específico
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
        var_dump($data);
        die("Recebndo dados para criação de estagiario");
        $model->setNomecompleto($data['nomecompleto'] ?? '');
        $model->setMatricula($data['matricula'] ?? '');
        $model->setEmail($data['email'] ?? '');
        $model->setSupervisor($data['supervisor'] ?? '');
        $model->setMinHoras(isset($data['MinHoras']) ? $data['MinHoras'] : 0);

        $model->setIdprojeto(isset($data['idprojeto']) ? (int)$data['idprojeto'] : null);
        $model->setIdorientador(isset($data['idorientador']) ? (int)$data['idorientador'] : null);
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
        $model->setNomecompleto($data['nomecompleto'] ?? '');
        $model->setEmail($data['email'] ?? '');
        $model->setMatricula($data['matricula'] ?? '');
        $model->setSupervisor($data['supervisor'] ?? '');
        $model->setMinHoras(isset($data['MinHoras']) ? $data['MinHoras'] : 0);
        $model->setidprojeto(isset($data['idprojeto']) ? (int)$data['idprojeto'] : null);
        $model->setidorientador(isset($data['idorientador']) ? (int)$data['idorientador'] : null);

        return $model->save(); // Atualiza no banco
    }

    public function delete($id) {
        $model = new EstagiariosModel();
        return $model->delete($id); // Exclui do banco
    }
    
}

                        

    

