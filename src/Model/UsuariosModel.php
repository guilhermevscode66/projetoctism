<?php

namespace Model;
use Model\ConexaoMysql;
class UsuariosModel
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

    protected $tipo;

    public function gettipo()
    {
        return $this->tipo;
    }

    public function settipo($tipo): self
    {
        $this->tipo = $tipo;
        return $this;
    }
protected $email;

    
    public function __construct() {}

    // Métodos de Banco de Dados

    public function loadById($id)
    {
        $db = new ConexaoMysql();
        $db->conectar();
                $id = (int)$id;
                $resultList = $db->consultarPrepared('SELECT * FROM usuarios WHERE id = ?', 'i', [$id]);
                if ($db->total > 0) {
                        foreach ($resultList as $value) {
                                $this->id = $value['id'];
                                $this->tipo = $value['tipo_projeto'];
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
        $resultList = $db->consultarPrepared('SELECT * FROM usuarios');
        $db->desconectar();
        $this->total = $db->total;
        $resultListObject=[];
       
         foreach ($resultList as $value) {
            $obj = new  UsuariosModel;
            $obj->id = $value['id'];
            $obj->tipo = $value['tipo'];
           $resultListObject[] =  $obj;

        
        }
        return $resultListObject;
    }

    public function save()
    {
        $db = new ConexaoMysql();
        $db->conectar();
        if (empty($this->getId())) {
            $tipo = $this->tipo;
            $db->executarPrepared('INSERT INTO usuarios (tipo) VALUES (?)', 's', [$tipo]);
        } else {
            $sql = 'UPDATE usuarios SET ';
            $tipo = $this->tipo;
            $db->executarPrepared('UPDATE usuarios SET tipo = ? WHERE id = ?', 'si', [$tipo, (int)$this->id]);
        }
        //echo $sql;
        $db->desconectar();
        $this->total = $db->total;
        return $this->total;
    }

    public function delete($id)
    {
        $db = new ConexaoMysql();
        $db->conectar();
        $db->executarPrepared('DELETE FROM usuarios WHERE id = ?', 'i', [(int)$id]);
        $db->desconectar();
        $this->total = $db->total;
        return $this->total;
    }

}

