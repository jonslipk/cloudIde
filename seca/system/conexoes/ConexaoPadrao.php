<?php
/**
 * @author OPEN COMPUTADORES LTDA
 * @version 1.0
 * @package DAO
 * @category Seguran�a
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @tutorial Classe responsavel por instanciar um conexão ao banco de dados
 */
class ConexaoPadrao extends Conexao {

 
    private static $instancia = null;

    /**
     * @tutorial Método que verifica se uma instancia de conexão ja foi criada,
     * caso contrário instância uma nova conexão.
     * 	A static Method
     * @static
     * @access public 
     * @return instância de conexão com o banco.
     */
    public static function getInstancia() {
        if (self::$instancia == null) {
            self::$instancia = new ConexaoPadrao();
        }

        return self::$instancia;
    }

     /**
     * @tutorial Método utilizando o método Exception::__clone, ao tentar clonar o
     * objeto será retornado um erro fatal.
     * @access public
     * @return Null 
     * @exception: Clone nao e permitido.
     */
    public function __clone() {
        trigger_error('Clone n�o permitido.', E_USER_ERROR);
    }
    
    
    public function getDadosConexao() {
        $ini = parse_ini_file('config/config.ini', true);
        return $ini['database'];
    }

}

?>
