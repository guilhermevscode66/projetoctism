<?php
namespace Model;
class BancoHorasModel
{
    protected $total;
protected $idprojeto;
protected $idestagiario;

    protected $data;
    /**
     * Get the value of data
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Set the value of data
     */
    public function setData($data): self {
        $this->data = $data;
        return $this;
    }
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

    protected $hora_entrada;

    public function getHoraEntrada()
    {
        return $this->hora_entrada;
    }

    public function setHoraEntrada($hora_entrada): self
    {
        $this->hora_entrada = $hora_entrada;
        return $this;
    }

    protected $hora_saida;

    public function getHoraSaida()
    {
        return $this->hora_saida;
    }

    public function setHoraSaida($hora_saida): self
    {
        $this->hora_saida = $hora_saida;
        return $this;
    }


    public function __construct() {}

    // Métodos de Banco de Dados

    public function loadById($id)
    {
        $db = new ConexaoMysql();
        $db->conectar();
        $id = (int) $id;
        $resultList = $db->consultarPrepared('SELECT * FROM bancohoras WHERE id = ?', 'i', [$id]);
        if ($db->total > 0) {
        
          foreach ($resultList as $value) {
            $this->id=$value['id'];
            $this->hora_entrada = $value['hora_entrada'];
            $this->hora_saida = $value['hora_saida'];
        $this->data= $value['data'];
          }
}
        $db->desconectar();
        $this->total = $db->total;
        return $this;
    }
public function loadByIpes($idprojeto, $idestagiario){

    $db = new ConexaoMysql();
        $db->conectar();
        $idprojeto = (int) $idprojeto;
        $idestagiario = (int) $idestagiario;
        $resultList = $db->consultarPrepared('SELECT * FROM bancohoras WHERE idprojeto = ? AND idestagiario = ?', 'ii', [$idprojeto, $idestagiario]);
        $db->desconectar();
        $this->total = $db->total;
        $listahoras= [];
        if ($db->total > 0) {
        
          foreach ($resultList as $value) {

            $obj = new BancoHorasModel;
            $obj->id=$value['id'];
            $obj->hora_entrada = $value['hora_entrada'];
            $obj->hora_saida = $value['hora_saida'];
        $obj->data= strtotime($value['data']);
        $obj->idprojeto= $value['idprojeto'];
        $obj->idestagiario = $value['idestagiario'];
        $listahoras[]=$obj;
          }
          return $listahoras;
}

    }
    public function loadAll()
    {
        $db = new ConexaoMysql();
        $db->conectar();
        $sql = 'SELECT * FROM bancohoras;';
        $resultList = $db->consultarPrepared($sql);
        $db->desconectar();
        $this->total = $db->total;
        $resultListObject=[];
         foreach ($resultList as $value) {
            $obj = new  BancoHorasModel;
            $obj->id = $value['id'];
            $obj->hora_entrada = $value['hora_entrada'];
            $obj->hora_saida = $value['hora_saida'];
            $obj->data= $value['data'];
            $obj->idprojeto = $value['idprojeto'];
            $obj->idestagiario = $value['idestagiario'];
           $resultListObject[] =  $obj;

        }
        return $resultListObject;
    }

    public function save()
    {
        $db = new ConexaoMysql();
        $db->conectar();
        if (empty($this->getId())) {
            $idprojeto = (int)$this->idprojeto;
            $idestagiario = (int)$this->idestagiario;
            $hora_entrada = $this->hora_entrada;
            $hora_saida = $this->hora_saida;
            $db->executarPrepared('INSERT INTO bancohoras (idprojeto, idestagiario, hora_entrada, hora_saida, data) VALUES (?, ?, ?, ?, now())', 'iiss', [$idprojeto, $idestagiario, $hora_entrada, $hora_saida]);
        }
        
         else {
            $hora_entrada = $this->hora_entrada;
            $hora_saida = $this->hora_saida;
            $db->executarPrepared('UPDATE bancohoras SET hora_entrada = ?, hora_saida = ? WHERE id = ?', 'ssi', [$hora_entrada, $hora_saida, (int)$this->id]);
        }
        $this->total = $db->total;
        $db->desconectar();
        return $this->total;
    }

    public function delete($id)
    {
        $db = new ConexaoMysql();
        $db->conectar();
        $db->executarPrepared('DELETE FROM bancohoras WHERE id = ?', 'i', [(int)$id]);
        $db->desconectar();
        $this->total = $db->total;
        return $this->total;
    }

/**
 * Get the value of idprojeto
 */
public function getIdprojeto() {
return $this->idprojeto;
}

/**
 * Set the value of idprojeto
 */
public function setIdprojeto($idprojeto): self {
$this->idprojeto = $idprojeto;
return $this;
}

/**
 * Get the value of idestagiario
 */
public function getIdestagiario() {
return $this->idestagiario;
}

/**
 * Set the value of idestagiario
 */
public function setIdestagiario($idestagiario): self {
$this->idestagiario = $idestagiario;
return $this;
}
}
    
