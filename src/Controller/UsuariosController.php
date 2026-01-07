<?php
namespace Controller;
use Model\UsuariosModel;

class UsuariosController
{
    public function loadAll() {
        $model = new UsuariosModel;

        return $model->loadAll(); // Retorna todos os projetoss

    }

    public function loadById($id) {
        $model = new UsuariosModel;
        return $model->loadById($id); // Retorna um projetos específico
    }

    public function create($data) {
        $model = new UsuariosModel;
        $model->settipo($data['tipo']);
        return $model->save(); // Salva no banco
    }

    public function update($id, $data) {
        $model = new UsuariosModel;
        $model->setId($id);
        $model->settipo($data['tipo']);
        return $model->save(); // Atualiza no banco
    }

    public function delete($id) {
        $model = new UsuariosModel;
        return $model->delete($id); // Exclui do banco
    }
    public function Login($id, $data)
    {
        $controller= new UsuariosController;
        if($data == $controller->loadById($id)){
            return true;
        }
        else{
            return false;
        }
    }
}
