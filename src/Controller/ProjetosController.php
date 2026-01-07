<?php

namespace Controller;

use Model\ProjetosModel;

class ProjetosController
{
    public function loadAll() {
        $model = new ProjetosModel();
        
        return $model->loadAll(); // Retorna todos os projetoss

    }

    public function loadById($id) {
        $model = new ProjetosModel();
        return $model->loadById($id); // Retorna um projetos específico
    }

    public function create($data) {
        $model = new ProjetosModel();
        $model->setNome($data['nome']);
        
        return $model->save(); // Salva no banco
    }

    public function update($id, $data) {
        $model = new ProjetosModel();
        $model->setNome($data['nome']);
        return $model->save(); // Atualiza no banco
    }

    public function delete($id) {
        $model = new ProjetosModel();
        return $model->delete($id); // Exclui do banco
    }
}
