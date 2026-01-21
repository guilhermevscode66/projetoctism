<?php

namespace Model;
//importa o estagiarios model e o projetos model para verificar se existem os ids de estagiarios e projetos nas respectivas tabelas e incluir na tabela estagiariosprojetos
use Model\EstagiariosModel;


class EstagiariosProjetosModel
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
protected $data_limite;
    public function getdata_limite()
    {
        return $this->data_limite;
    }
    public function setdata_limite($data_limite): self
    {
        $this->data_limite = $data_limite;
        return $this;
    }
protected $estagiario_finalizado;

    public function getestagiario_finalizado()
    {
        return $this->estagiario_finalizado;
    }

    public function setestagiario_finalizado($estagiario_finalizado): self
    {
        $this->estagiario_finalizado = $estagiario_finalizado;
        return $this;
    }

    protected $status;
    public function getStatus()
    {
        return $this->status;
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
                $this->idestagiario = $value['idestagiario'];
                $this->idprojeto = $value['idprojeto'];
$this->data_limite = $value['data_limite'];
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
            $obj->idestagiario = $value['idestagiario'];
            $obj->idprojeto = $value['idprojeto'];
            $obj    ->data_limite = $value['data_limite'];
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
        
       
        return $this->total;
    }
public function finalizarVinculo($idestagiario, $idprojeto) {
    $db = new ConexaoMysql();
    $db->conectar();
    
    // 1. Verifica se o vínculo existe
    $sql = 'SELECT data_limite FROM estagiariosprojetos WHERE idestagiario = ? AND idprojeto = ?;';
    $vinculo = $db->ExecutarPrepared($sql, 'ii', [(int)$idestagiario, (int)$idprojeto]);
    
    if (empty($vinculo)) {
        $db->Desconectar();
        return 'vinculo_nao_encontrado';
    }

    // 2. Se o vínculo existe, prossegue para marcar como concluído
    // Nota: Usamos aspas duplas fora para o 'concluido' dentro do SQL funcionar
    $sqlMark = "UPDATE estagiariosprojetos 
                SET status = 'concluido', data_conclusao_real = CURRENT_DATE 
                WHERE idestagiario = ? AND idprojeto = ?;";
    
    $db->ExecutarPrepared($sqlMark, 'ii', [(int)$idestagiario, (int)$idprojeto]);
    
    $total = $db->total; // Pega o número de linhas afetadas
    $db->Desconectar();
    $this->estagiario_finalizado = true;
    return $this->estagiario_finalizado;
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
