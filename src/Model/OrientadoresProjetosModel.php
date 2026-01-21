<?php

namespace Model;
//importa o estagiarios model e o projetos model para verificar se existem os ids de estagiarios e projetos nas respectivas tabelas e incluir na tabela orientadoresprojetos
use Model\EstagiariosModel;


class orientadoresprojetosModel
{
    public $total;

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

    protected $idorientador;

    public function getidorientador()
    {
        return $this->idorientador;
    }

    public function setidorientador($idorientador): self

    {
        $this->idorientador = $idorientador;
        return $this;
    }

    public function __construct() {}

    // Métodos de Banco de Dados

    public function loadById($id)
    {
        $db = new ConexaoMysql();
        $db->conectar();
        $id = (int)$id;
        $resultList = $db->consultarPrepared('SELECT * FROM orientadoresprojetos WHERE id = ?', 'i', [$id]);
$db->desconectar();
        $this->total = $db->total;
        

if(empty($this->total)){
            return null;        
}
            foreach ($resultList as $value) {
                $this->id = $value['id'];
                $this->idorientador = $value['idorientador'];
                $this->idprojeto = $value['idprojeto'];
            }
        
        return $this;
    }

    public function loadAll()
    {
        $db = new ConexaoMysql();
        $db->conectar();
        $resultList = $db->consultarPrepared('SELECT * FROM orientadoresprojetos');
        $db->desconectar();
        $this->total = $db->total;
        if(empty($this->total)){
            return null;
        }   
        $resultListObject = [];
        foreach ($resultList as $value) {
            $obj = new  orientadoresprojetosModel;
            $obj->id = $value['id'];
            $obj->idorientador = $value['idorientador'];
            $obj->idprojeto = $value['idprojeto'];
            $resultListObject[] =  $obj;
        }
        return $resultListObject;
    }

    public function save()
    {
        $db = new ConexaoMysql();
        $db->conectar();
            if (empty($this->getId())) {
                $idori = (int)$this->getidorientador();
                $idproj = (int)$this->getIdprojeto();
                $db->executarPrepared('INSERT INTO orientadoresprojetos (idorientador, idprojeto) VALUES (?, ?)', 'ii', [$idori, $idproj]);

            } else {
                $db->executarPrepared('UPDATE orientadoresprojetos SET idorientador = ?, idprojeto = ? WHERE id = ?', 'iii', [(int)$this->idorientador
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
        $db->executarPrepared('DELETE FROM orientadoresprojetos WHERE id = ?', 'i', [(int)$id]);
        $db->desconectar();
        $this->total = $db->total;
        return $this->total;
    }
}
