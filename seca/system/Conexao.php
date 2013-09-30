<?php

include_once getenv('DOCUMENT_ROOT') . "/adodb5/adodb.inc.php";

/**
 * @author OPEN COMPUTADORES LTDA
 * @version 1.0
 * @package DAO
 * @category Seguran�a
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @tutorial Classe responsavel por instanciar um conexão ao banco de dados
 */
abstract class Conexao {

    /**
     * @example postgres, mssql 
     * @access protected
     */
    protected $tipo;

    /**
     * @access protected
     */
    protected $server;

    /**
     * @access protected
     */
    protected $user;

    /**
     * @access protected
     */
    protected $password;

    /**
     * @access protected
     */
    protected $database;


    /**
     * @tutorial Construtor responsavel por instanciar um conexão ao banco de dados
     * @access public 
     * @return atributos de conexão preenchidos com os dados para conexão padrão
     */
    public function Conexao() {

        $dados = $this->getDadosConexao();
        $this->tipo = $dados['tipo'];
        $this->server = $dados['server'];
        $this->user = $dados['user'];
        $this->password = $dados['password'];
        $this->database = $dados['database'];
    }

    abstract function getDadosConexao();

    /**
     * @tutorial Método responvel por conectar ao Banco de Dados utilizando a lib 
     * ADODB, utilizando os parametro private da classe $server, $user, $password e $database. 
     * @access public
     * @return INstancia de Conexão
     */
    public function adodbConexao() {

        $db = ADONewConnection($this->tipo);
        $db->Connect($this->server, $this->user, $this->password, $this->database);

        return $db;
    }

}

?>
