<?php

namespace Controller;

use Model\EstagiariosprojetosModel;

class EstagiariosProjetosController
{
    public function loadAll() {
        $model = new EstagiariosprojetosModel();
        return $model->loadAll(); // Retorna todos os estagiariosprojetoss
    }

    public function loadById($id) {
        $model = new EstagiariosprojetosModel();
        return $model->loadById($id); // Retorna um estagiariosprojetos específico
    }

    public function create($data) {
        $model = new EstagiariosprojetosModel();
        $model->setidprojeto($data['idprojeto']);
        $model->setIdestagiarios($data['idestagiarios']);
        return $model->save(); // Salva no banco
    }

    public function update($id, $data) {
        $model = new EstagiariosprojetosModel();
        $model->setId($id);
        $model->setidprojeto($data['idprojeto']);
        $model->setIdestagiarios($data['idestagiarios']);
        return $model->save(); // Atualiza no banco
    }

    public function delete($id) {
        $model = new EstagiariosprojetosModel();
        return $model->delete($id); // Exclui do banco
    }
}
