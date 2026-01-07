<?php
namespace Model;

class EstagiariosModel
{
    protected $total;
protected $idprojeto;
protected $idorientador;
    
    protected $id;
protected $email;
protected $nome_projeto;
protected $nome_orientador;
    public function getId()
    {
        return $this->id;
    }

    public $lastInsertId;

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    protected $nomecompleto;

    public function getNomeCompleto()
    {
        return $this->nomecompleto;
    }

    public function setNomeCompleto($nomecompleto): self
    {
        $this->nomecompleto = $nomecompleto;
        return $this;
    }

    protected $matricula;

    public function getMatricula()
    {
        return $this->matricula;
    }

    public function setMatricula($matricula): self
    {
        $this->matricula = $matricula;
        return $this;
    }

    protected $projeto;

    public function getProjeto()
    {
        return $this->projeto;
    }

    public function setProjeto($projeto): self
    {
        $this->projeto = $projeto;
        return $this;
    }

    protected $orientador;

    public function getOrientador()
    {
        return $this->orientador;
    }

    public function setOrientador($orientador): self
    {
        $this->orientador = $orientador;
        return $this;
    }

    protected $supervisor;

    public function getSupervisor()
    {
        return $this->supervisor;
    }

    public function setSupervisor($supervisor): self
    {
        $this->supervisor = $supervisor;
        return $this;
    }

    protected $MinHoras;

    public function getMinHoras()
    {
        return $this->MinHoras;
    }

    public function setMinHoras($MinHoras): self
    {
        $this->MinHoras = $MinHoras;
        return $this;
    }
protected $senha;
    public function __construct() {}

    // Métodos de Banco de Dados

    public function loadById($id)
    {
                $db = new ConexaoMysql();
                $db->conectar();
                $id = (int) $id;
                $resultList = $db->consultarPrepared('SELECT * FROM estagiarios WHERE id = ?', 'i', [$id]);

                $db->desconectar();
                $this->total = $db->total;
                if ($this->total > 0) {
                        foreach ($resultList as $value) {
                                $this->id = $value['id'];
                                $this->nomecompleto = $value['nomecompleto'];
                                $this->email = $value['email'];
                                $this->matricula = $value['matricula'];
                                $this->supervisor = $value['supervisor'];
                                $this->MinHoras = $value['MinHoras'];
                                $this->senha = $value['senha'];
                                $this->idprojeto = $value['idprojeto'];
                                $this->idorientador = $value['idorientador'];
                        }
                }
        return $this;
    }
public function loadByMatricula($matricula){
        $db = new ConexaoMysql();
                $db->conectar();
                $resultList = $db->consultarPrepared('SELECT * FROM estagiarios WHERE matricula = ?', 's', [$matricula]);
                $db->desconectar();

                $this->total = $db->total;

                if ($this->total > 0) {
                        foreach ($resultList as $value) {
                                $this->id = $value['id'];
                                $this->nomecompleto = $value['nomecompleto'];
                                $this->email = $value['email'];
                                $this->matricula = $value['matricula'];
                                $this->supervisor = $value['supervisor'];
                                $this->MinHoras = $value['MinHoras'];
                                $this->senha = $value['senha'];
                                $this->idprojeto = $value['idprojeto'];
                                $this->idorientador = $value['idorientador'];
                        }
                }
                return $this;
}


    public function loadAll()
    {
        $db = new ConexaoMysql();
        $db->conectar();
$sql ='SELECT
estagiarios.*,
projetos.nome_projeto,
orientadores.nome_orientador
FROM estagiarios
INNER JOIN projetos ON estagiarios.idprojeto = projetos.id
INNER JOIN orientadores ON estagiarios.idorientador = orientadores.id
;';

        $resultList = $db->consultarPrepared($sql);
        $db->desconectar();
        $this->total = $db->total;
        $resultListObject=[];
         foreach ($resultList as $value) {
            $obj = new  EstagiariosModel;
            
            $obj->id = $value['id'];
            $obj->nomecompleto = $value['nomecompleto'];
            $obj->email=$value['email'];
            $obj->matricula = $value['matricula'];
            $obj->projeto = $value['projeto'];
            $obj->orientador = $value['orientador'];
            $obj->supervisor = $value['supervisor'];
            $obj->MinHoras = $value['MinHoras'];
            $obj->senha = $value['senha'];
            $obj->idprojeto =$value['idprojeto'];
            $obj->nome_projeto= $value['nome_projeto'];
            $obj->nome_orientador = $value['nome_orientador'];
           $resultListObject[] =  $obj;

        }
        return $resultListObject;
    }
    
    public function save()
    {
        $db = new ConexaoMysql();
        $db->conectar();
        if (empty($this->getId())) {
            $nome = $this->nomecompleto;
            $email = $this->email;
            $matricula = $this->matricula;
            $supervisor = $this->supervisor;
            $minHoras = $this->MinHoras;
            $idprojeto = empty($this->idprojeto) ? null : (int)$this->idprojeto;
            $idorientador = empty($this->idorientador) ? null : (int)$this->idorientador;
            $db->executarPrepared('INSERT INTO estagiarios (nomecompleto, email, matricula, supervisor, MinHoras, idprojeto, idorientador) VALUES (?, ?, ?, ?, ?, ?, ?)', 'ssssiii', [$nome, $email, $matricula, $supervisor, $minHoras, $idprojeto, $idorientador]);
        } else {
            $sql = 'UPDATE estagiarios SET ';
            $nome = $this->nomecompleto;
            $email = $this->email;
            $idprojeto = empty($this->idprojeto) ? null : (int)$this->idprojeto;
            $orientador = $this->orientador;
            $supervisor = $this->supervisor;
            $db->executarPrepared('UPDATE estagiarios SET nomecompleto = ?, email = ?, idprojeto = ?, orientador = ?, supervisor = ? WHERE id = ?', 'ssissi', [$nome, $email, $idprojeto, $orientador, $supervisor, (int)$this->id]);
        }
        
        $this->lastInsertId = $db->lastInsertId; // busca o ultimo id inserido na base de dados.
        $db->desconectar();
        $this->total = $db->total;
  
        return $this->total;
    }

    public function saveSenha(){
        $db = new ConexaoMysql;
        $db->Conectar();
        $senha = $this->senha;
        $result = $db->executarPrepared('UPDATE estagiarios SET senha = ? WHERE id = ?', 'si', [$senha, (int)$this->id]);
        
        $this->total = $db->total;
        $db->Desconectar();
        return $this->total;
    }
    public function delete($id)
    {
        $db = new ConexaoMysql();
        $db->conectar();
        $id = (int) $id;
        $db->executarPrepared('DELETE FROM estagiarios WHERE id = ?', 'i', [$id]);
        $db->desconectar();
        $this->total = $db->total;
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
 * Get the value of nome_projeto
 */
public function getNomeProjeto() {
return $this->nome_projeto;
}

/**
 * Set the value of nome_projeto
 */
public function setNomeProjeto($nome_projeto): self {
$this->nome_projeto = $nome_projeto;
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

/**
 * Get the value of nome_orientador
 */
public function getNomeOrientador() {
return $this->nome_orientador;
}

/**
 * Set the value of nome_orientador
 */
public function setNomeOrientador($nome_orientador): self {
$this->nome_orientador = $nome_orientador;
return $this;
}

/**
 * Get the value of idorientador
 */
public function getIdorientador() {
return $this->idorientador;
}

/**
 * Set the value of idorientador
 */
public function setIdorientador($idorientador): self {
$this->idorientador = $idorientador;
return $this;
}
}
