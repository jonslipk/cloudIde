<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * @package HELPERS
 */

class SecurityHelper {

    private $usuario;
    private static $instancia = null;
    private $seguranca;

    public static function getInstancia() {
        if (self::$instancia == null) {
            self::$instancia = new SecurityHelper();
        }
        return self::$instancia;
    }

    public function __clone() {
        trigger_error('Clone não é permitido.', E_USER_ERROR);
    }

    public function SecurityHelper() {
        if (!isset($_SESSION))
            session_start();

        $this->seguranca = $this->getDadosSeguranca();

        if ($this->isLogon()) {
            $this->usuario = unserialize($_SESSION[$this->seguranca['sessao']]);
        } else {
            $flag = (stripos($_SERVER['QUERY_STRING'], "Usuario/logar") === 0 ||
                    stripos($_SERVER['QUERY_STRING'], "Index/logon") === 0) ? true : false;
            if (!$flag) {
                $_factory = Factory::getInstancia();
                $redirect = $_factory->fabricar("RedirectorHelper");
                $redirect->goToControllerAction('Index','logon');
            }
        }
        
        //Verificar Acesso ao Controller Especifico
        
    }

    public function iniciarSessao(Usuario &$objUsuario) {
        $_SESSION[$this->seguranca['sessao']] = serialize($objUsuario);
    }

    public function destruirSessao() {
        unset($_SESSION[$this->seguranca['sessao']]);
    }

    private function getDadosSeguranca() {
        $ini = parse_ini_file('config/config.ini', true);
        return $ini['seguranca'];
    }

    public function getUsuario() {
        return $this->usuario;
    }

    private function isLogon() {
        if (isset($_SESSION[$this->seguranca['sessao']]))
            return true;

        return false;
    }

    public function isAllowed($funcionalidade) {
               
        if(strpos($funcionalidade,"::") > 0){
            $funcionalidade = str_replace(array("controller","::"), array("","_"), strtolower($funcionalidade));
        }

        if($this->usuario->getPerfil()->getFuncionalidades() != null){
            foreach($this->usuario->getPerfil()->getFuncionalidades() as $objFuncionalidade){
                if(strcasecmp($funcionalidade, $objFuncionalidade->getNome()) == 0){
                    return true;
                }
            }
        }
        return false;

    }

}

?>
