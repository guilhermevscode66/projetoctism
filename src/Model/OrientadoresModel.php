<?php
namespace Model;
use Model\ConexaoMysql;
class OrientadoresModel
{
    public $total;

    protected $id;
protected $matricula;
protected $idprojeto;
    public function getId()
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    protected $nome_orientador;

    public function getnomeorientador()
    {
        return $this->nome_orientador;
    }

    public function setnomeorientador($nome_orientador): self
    {
        $this->nome_orientador = $nome_orientador;
        return $this;
    }
protected $email;
protected $senha;
    
public function __construct() {}

    // Métodos de Banco de Dados
/**
     * Carrega os dados do orientador através do e-mail
     * @param string $email
     * @return self
     */
    public function loadByEmail($email)
    {
        $db = new ConexaoMysql();
        $db->conectar();
        
        // Usamos 's' pois o e-mail é uma string
        $resultList = $db->consultarPrepared('SELECT * FROM orientadores WHERE email = ?', 's', [$email]);
        
        $db->desconectar();
        $this->total = $db->total;

        if ($this->total > 0) {
            foreach ($resultList as $value) {
                $this->id = $value['id'];
                $this->nome_orientador = $value['nome_orientador'];
                $this->matricula = $value['matricula'];
                $this->email = $value['email'];
                $this->senha = $value['senha'];
                $this->idprojeto = $value['idprojeto'];
            }
        }
        
        return $this;
    }
    public function loadById($id)
    {
        $db = new ConexaoMysql();
                $db->conectar();
                $id = (int) $id;
                $resultList = $db->consultarPrepared('SELECT * FROM orientadores WHERE id = ?', 'i', [$id]);
                $db->desconectar();
                $this->total = $db->total;
                if ($this->total > 0) {
                        foreach ($resultList as $value) {
                                $this->id = $value['id'];
                                $this->nome_orientador = $value['nome_orientador'];
                                $this->email = $value['email'];
                                $this->senha = $value['senha'];
                        }
                }
        return $this;
    }

        
public function loadByMatricula($matricula){
$db = new ConexaoMysql();
        $db->conectar();
        $resultList = $db->consultarPrepared('SELECT * FROM orientadores WHERE matricula = ?', 's', [$matricula]);
        $db->desconectar();
        $this->total = $db->total;
    
        if ($this->total > 0) {
          foreach ($resultList as $value) {
            $this->id = $value['id'];
            $this->nome_orientador = $value['nome_orientador'];
            $this->matricula = $value['matricula'];
            $this->email=$value['email'];
            $this->senha = $value['senha'];
          }
        }
        return $this;


}

    public function loadAll()
    {
        $db = new ConexaoMysql();
        $db->conectar();
        $resultList = $db->consultarPrepared('SELECT * FROM orientadores;');
        $db->desconectar();
        $this->total = $db->total;
        $resultListObject=[];
       
         foreach ($resultList as $value) {
            $obj = new  OrientadoresModel;
            $obj->id = $value['id'];
            $obj-> nome_orientador= $value['nome_orientador'];
            $obj->matricula= $value['matricula'];
            $obj->email = $value['email'];
            $obj->senha = $value['senha'];
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
            $nome = $this->nome_orientador;
            $matricula = $this->matricula;
            $email = $this->email;
            $idprojeto = empty($this->idprojeto) ? null : (int)$this->idprojeto;
            $db->executarPrepared('INSERT INTO orientadores (nome_orientador, matricula, email, idprojeto) VALUES (?, ?, ?, ?)', 'sssi', [$nome, $matricula, $email, $idprojeto]);
        } else {
            $nome = $this->nome_orientador;
            $email = $this->email;
            $idprojeto = empty($this->idprojeto) ? null : (int)$this->idprojeto;
            $db->executarPrepared('UPDATE orientadores SET nome_orientador = ?, email = ?, idprojeto = ? WHERE id = ?', 'ssii', [$nome, $email, $idprojeto, (int)$this->id]);
        }

        $this->total = $db->total;
        $db->desconectar();
        return $this->total;
    }

    public function saveSenha(){
        $db = new ConexaoMysql;
        $db->Conectar();
        // Armazena a senha com hash seguro
        $senha = $this->senha;
        $hashed = password_hash($senha, PASSWORD_DEFAULT);
        $db->executarPrepared('UPDATE orientadores SET senha = ? WHERE id = ?', 'si', [$hashed, (int)$this->id]);
        $this->total= $db->total;
        $db->Desconectar();
        return $this->total;
    }

    public function delete($id)
    {
        $db = new ConexaoMysql();
        $db->conectar();
        // limpar referências em estagiarios antes de remover orientador
        $db->executarPrepared('UPDATE estagiarios SET idorientador = NULL WHERE idorientador = ?', 'i', [(int)$id]);
        // remover o orientador
        $db->executarPrepared('DELETE FROM orientadores WHERE id = ?', 'i', [(int)$id]);
        $this->total = $db->total;
        $db->desconectar();
        return $this->total;
    }

/**
 * Get the value of email
 */
public function getEmail() {
return $this->email;
}

/**
 * Set the value of email
 */
public function setEmail($email): self {
$this->email = $email;
return $this;
}

    
/**
 * Get the value of senha
 */
public function getSenha() {
return $this->senha;
}

/**
 * Set the value of senha
 */
public function setSenha($senha): self {
$this->senha = $senha;
return $this;
}

/**
 * Get the value of matricula
 */
public function getMatricula() {
return $this->matricula;
}

/**
 * Set the value of matricula
 */
public function setMatricula($matricula): self {
$this->matricula = $matricula;
return $this;
}

/**
 * Get the value of idprojeto
 */
public function getidprojeto() {
return $this->idprojeto;
}

/**
 * Set the value of idprojeto
 */
public function setidprojeto($idprojeto): self {
$this->idprojeto = $idprojeto;
return $this;
}
}
