<?php
namespace Model;

use mysqli;
use Exception;

class ConexaoMysql {

    protected $mysqli;
    protected $server = '127.0.0.1'; //Endereço do servidor
    protected $user = 'root'; //Usuario que acessa o banco
    protected $pass = ''; //Senha do usuário
    protected $dataBase = 'estagioctism'; //Nome da base de dados

    //Informa o TOTAL de qualquer registro afetado (SELECT, INSERT, UPDATE, DELETE) na base. */
    public $total = 0;

    public function __construct() {
    
    }

    //Informa o ultimo id do registro inserido  na base de dados 
    public $lastInsertId = 0; //Retorna a chave primária do registro

    public function getConnection(){
        return $this->mysqli;
   }

    //Converte datas para o banco
    public function ConvertToDate($data){
        $data = explode('-', $data);
        return ' ' . $data[2] . '-' . $data[1] . '-' . $data[0];
    }

    /** Conectar com banco de dados */
    public function Conectar(){
        $this->mysqli = new mysqli($this->server, $this->user, $this-> pass, $this->dataBase);
        //Verifica se Não(!) conseguiu conectar
        if ($this->mysqli->errno) {
           echo("Problema na conexao com banco de dados. Erro:" . $this->mysqli->connect_errno);
           exit();
        }
        
        $this->mysqli->set_charset('utf8');
    }

    /** Realiza as consultas (SELECT) */
    public function Consultar($sql){
        try {
            //Receber o parametro $sql e realizar a consulta            
            if ($result = $this->mysqli->query($sql)){
                //Atualizar o contador informando o número de registros retornados na consulta
                $this->total = $result->num_rows;
                return $result;
            } else {
                $this->total = 0;
                return null;
            }
        } catch (Exception $exc) {
            //Desconectar....
            $this->Desconectar();
        }
    }
    /** Realiza INSERT, UPDATE e DELETE */
    public function Executar($sql){
        try {
            //Realiza a query(INSERT, UPDATE e DELETE)
            if ($resultado = $this->mysqli->query($sql)){
                //Guarda o último ID inserido na tabela
                $lastId = $this->mysqli->insert_id;
                $this->lastInsertId = $this->mysqli->insert_id;
                
                //Atualiza o contador com os registos afetados
                $this->total = $this->mysqli->affected_rows;
                //Comita a transação
                $this->mysqli->commit();

                return $this->lastInsertId;
            }
            else {
                //Nenhum registro foi afetado a partir da consulta enviada.
                $this->total = 0;
                //return "Nenhum registro foi afetado.";
                throw new Exception('Erro');
            }
        } catch (Exception $exc) {
            //Em caso de erro volta ao estado anterior.
            $this->mysqli->rollback();
        }
    }
    public function Desconectar(){
        if ($this->mysqli) {
            $this->mysqli->close();
            $this->mysqli = null;
        }
    }

    /** Escapa valores para uso em consultas SQL. */
    public function escape($value){
        $this->ensureConnected();
        return $this->mysqli->real_escape_string($value);
    }

    /**
     * Executa uma consulta preparada (SELECT) e retorna array associativo.
     * @param string $sql SQL com placeholders (?)
     * @param string|null $types tipos para bind_param (ex: 'is')
     * @param array $params valores para bind
     * @return array
     */
    public function consultarPrepared($sql, $types = null, $params = []){
        $this->ensureConnected();
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            $this->total = 0;
            return [];
        }
        if ($types && count($params) > 0) {
            $refs = [];
            $refs[] = $types;
            for ($i = 0; $i < count($params); $i++) {
                $refs[] = &$params[$i];
            }
            call_user_func_array([$stmt, 'bind_param'], $refs);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $this->total = $result->num_rows;
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $result->free();
            $stmt->close();
            return $data;
        }
        $this->total = 0;
        $stmt->close();
        return [];
    }

    /**
     * Executa uma instrução preparada (INSERT/UPDATE/DELETE).
     * Retorna lastInsertId se houver, caso contrário affected rows.
     */
    public function executarPrepared($sql, $types = null, $params = []){
        $this->ensureConnected();
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            $this->total = 0;
            return 0;
        }
        if ($types && count($params) > 0) {
            $refs = [];
            $refs[] = $types;
            for ($i = 0; $i < count($params); $i++) {
                $refs[] = &$params[$i];
            }
            call_user_func_array([$stmt, 'bind_param'], $refs);
        }
        $stmt->execute();
        $this->lastInsertId = $this->mysqli->insert_id;
        $this->total = $stmt->affected_rows;
        $stmt->close();
        return $this->lastInsertId ? $this->lastInsertId : $this->total;
    }

    /** Garantir que exista conexão ativa */
    private function ensureConnected(){
        if (!$this->mysqli || !@($this->mysqli->ping())) {
            $this->Conectar();
        }
    }

}