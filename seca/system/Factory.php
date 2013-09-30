<?php
    /**
     * Factory
     *
     * Classe fábrica responsável pela criação de Objetos do Sistema
     * @package System
     * @author Secretaria de Desenvolvimento Social e Combate a Pobreza - CMO
     * @copyright 2011
     * @version 1.4
     * @access Public
     * @tutorial 
     */
    class Factory {

        /*
         * Instancia do Objeto ACTFactory para esta classe.
         */
        private static $instancia = null;

        /*
         * ACTFactory::getInstancia
         *
         * @package ACTION/
         * @param Void
         * @return Objeto ACTFactory
         * @tutorial: Verifica se existe uma instancia do Objeto ACTFactory, caso contrario
         * da um new e retorna o objeto
         */
        public static function getInstancia(){
            if(self::$instancia == null){
                self::$instancia = new Factory();
            }
            return self::$instancia;
        }

        /*
         * ACTFactory::__clone
         *
         * @package ACTION/
         * @param Void
         * @return Null
         * @tutorial: Impede que este objeto seja clonado
         * @exception: Clone nao e permitido.
         */
        public function __clone() {
            trigger_error('Clone não é permitido.', E_USER_ERROR);
        }
        
        public function fabricar($objeto){
            ucfirst($objeto);
            return new $objeto();
        }
        
    }
?>
