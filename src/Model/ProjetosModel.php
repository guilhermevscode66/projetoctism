<?php

namespace Model;
use Model\ConexaoMysql;
class ProjetosModel
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

    protected $nome;

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome): self
    {
        $this->nome = $nome;
        return $this;
    }

    protected $datacriacao;

    public function getDatacriacao()
    {
        return $this->datacriacao;
    }

    public function setDatacriacao($datacriacao): self
    {
        $this->datacriacao = $datacriacao;
        return $this;
    }

    public function __construct() {}

    // Métodos de Banco de Dados

    public function loadById($id)
    {
        $db = new ConexaoMysql();
                $db->conectar();
                $id = (int) $id;
                $resultList = $db->consultarPrepared('SELECT * FROM projetos WHERE id = ?', 'i', [$id]);

                $db->desconectar();
                $this->total = $db->total;
                if ($this->total > 0) {
                        foreach ($resultList as $value) {
                                $this->id = $value['id'];
                                $this->nome = $value['nome_projeto'];
                                $this->datacriacao = $value['data_criacao'];
                        }
                }
        return $this;
    }

    public function loadAll()
    {
        $db = new ConexaoMysql();
        $db->conectar();
        $resultList = $db->consultarPrepared('SELECT * FROM projetos;');
        $db->desconectar();
        $this->total = $db->total;
        $resultListObject=[];
       
         foreach ($resultList as $value) {
            $obj = new  ProjetosModel;
            $obj->id = $value['id'];
            $obj->nome = $value['nome_projeto'];
            $obj->datacriacao = $value['data_criacao'];
           $resultListObject[] =  $obj;

        
        }

        return $resultListObject;
    }

    public function save()
    {
        $db = new ConexaoMysql();
        $db->conectar();
        if (empty($this->getId())) {
            $nome = $this->nome;
            $db->executarPrepared('INSERT INTO projetos (nome_projeto, data_criacao ) VALUES (?, now() );', 's', [$nome]);
        } else {
            $nome = $this->nome;
            $db->executarPrepared('UPDATE projetos SET nome_projeto = ? WHERE id = ?', 'si', [$nome, (int)$this->id]);
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
        // limpar referências: remover associação em estagiarios e em estagiariosprojetos
        $db->executarPrepared('UPDATE estagiarios SET idprojeto = NULL WHERE idprojeto = ?', 'i', [(int)$id]);
        $db->executarPrepared('DELETE FROM estagiariosprojetos WHERE idprojeto = ?', 'i', [(int)$id]);
        // por fim, remover o projeto
        $db->executarPrepared('DELETE FROM projetos WHERE id = ?', 'i', [(int)$id]);
        $this->total = $db->total;
        $db->desconectar();
        return $this->total;
    }
}
