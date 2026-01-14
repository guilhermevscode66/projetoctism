<?php

namespace Model;
//importa o estagiarios model e o projetos model para verificar se existem os ids de estagiarios e projetos nas respectivas tabelas e incluir na tabela estagiariosprojetos
use Model\EstagiariosModel;


class EstagiariosProjetosModel
{
    protected $total;

    protected $id;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    protected $idprojeto;

    public function getidprojeto()
    {
        return $this->idprojeto;
    }

    public function setidprojeto($idprojeto): self
    {
        $this->idprojeto = $idprojeto;
        return $this;
    }

    protected $idestagiario;

    public function getidestagiario()
    {
        return $this->idestagiario;
    }

    public function setidestagiario($idestagiario): self

    {
        $this->idestagiario = $idestagiario;
        return $this;
    }

    public function __construct() {}

    // Métodos de Banco de Dados

    public function loadById($id)
    {
        $db = new ConexaoMysql();
        $db->conectar();
        $id = (int)$id;
        $resultList = $db->consultarPrepared('SELECT * FROM estagiariosprojetos WHERE id = ?', 'i', [$id]);
        if ($db->total > 0) {
            foreach ($resultList as $value) {
                $this->id = $value['id'];
                $this->idprojeto = $value['idprojeto'];
                $this->idestagiario
 = $value['idestagiario
'];
            }
        }
        $db->desconectar();
        $this->total = $db->total;
        return $this;
    }

    public function loadAll()
    {
        $db = new ConexaoMysql();
        $db->conectar();
        $resultList = $db->consultarPrepared('SELECT * FROM estagiariosprojetos');
        $db->desconectar();
        $this->total = $db->total;
        $resultListObject = [];
        foreach ($resultList as $value) {
            $obj = new  EstagiariosprojetosModel;
            $obj->id = $value['id'];
            $obj->idprojeto = $value['idprojeto'];
            $obj->idestagiario
 = $value['idestagiario
'];
            $resultListObject[] =  $obj;
        }
        return $resultListObject;
    }

    public function save()
    {
        $db = new ConexaoMysql();
        $db->conectar();
            if (empty($this->getId())) {
                $idest = (int)$this->getidestagiario
();
                $idproj = (int)$this->getIdprojeto();
                $db->executarPrepared('INSERT INTO estagiariosprojetos (idestagiario
, idprojeto) VALUES (?, ?)', 'ii', [$idest, $idproj]);

            } else {
                $db->executarPrepared('UPDATE estagiariosprojetos SET idestagiario
 = ?, idprojeto = ? WHERE id = ?', 'iii', [(int)$this->idestagiario
, (int)$this->idprojeto, (int)$this->id]);
            }
            
        $db->desconectar();
        $this->total = $db->total;
        $db->desconectar();
       
        return $this->total;
    }

    public function delete($id)
    {
        $db = new ConexaoMysql();
        $db->conectar();
        $db->executarPrepared('DELETE FROM estagiariosprojetos WHERE id = ?', 'i', [(int)$id]);
        $db->desconectar();
        $this->total = $db->total;
        return $this->total;
    }
}
