<?php
    /**
    * SessionPopulate
    *
    * @package System
    * @subpackage Helper
    * @author Secretaria de Desenvolvimento Social e Combate a Pobreza - Coordenação de Modernização
    * @author Desenvolvedores: Robermarlei Oliveira, Samuel Guimarães e Fábio Elísio
    * @copyright 2011
    * @version 1.0
    * @access Public
    */
   class PopulateHelper {
       /*
     * Instancia do Objeto ACTFactory para esta classe.
     */

    private static $instancia = null;

    /*
     * ACTFactory::getInstancia
     *
     * @package HELPERS
     * @param Void
     * @return Objeto ACTFactory
     * @tutorial: Verifica se existe uma instancia do Objeto ACTFactory, caso contrario
     * da um new e retorna o objeto
     */

    public static function getInstancia() {
        if (self::$instancia == null) {
            self::$instancia = new PopulateHelper();
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
         /**
         * SessionPopulate::SessionPopulate
         *
         * @param session
         * @return Void
         * @access Public
         */
        public function PopulateHelper(){
            
        }
        
        /**
         * Converte um objeto do VOB para um array com os atributos
         * 
         * PopulateHelper::objetoToArray
         * @access public
         * @package system
         * @subpackage helpers
         * @param Object $objeto Objeto para ser transformado em array
         * @param Boolean $renderizar Flag que determina se o retorno do array deverá vir com o objeto renderizado para tela
         * @return array[] $arrayRet Array do Objeto convertido
         * 
         */        
        public function objetoToArray($objeto, $renderizar = false, $arrayAdd = ""){
            
            if($renderizar){
                $objUtil = Util::getInstancia();
                $objeto = $objUtil->renderizar($objeto);
            }
                
            $arrayColMap = $objeto->getColMap();
            $arrayObj = (array) $objeto;
            $arrayRet = array();
            foreach($arrayObj as $key=>$val){
                $arrayRet[array_search(preg_replace("/^\\0(.*)\\0/", "", $key),$arrayColMap)] = $val;
            }
            
            //verificar a possibilidade de log de segurança
            unset ($arrayRet[0]);
            
            if($arrayAdd != ""){
                
                foreach ($arrayAdd as $key => $value) {
                    $arrayRet[$key] = $value;
                }
                
            }

            return $arrayRet;
        }
        
        /**
         * Obtem a diferença entre os arrays enviados a partir do atributo informado
         * PopulateHelper::obterDiferenca
         * @access public
         * @package system
         * @subpackage helpers
         * @param array[] $array1Objeto Array de Objetos 1
         * @param array[] $array2Objeto Array de Objetos 2
         * @return array[] $arrayReturn Array com os objetos que está em um e não contem no outro
         */        
        public function obterDiferenca(array $array1Objeto, array $array2Objeto, $attr = "id"){
            
            $arrayReturn = array();
            
            if(count($array1Objeto)>count($array2Objeto)){
                $array1 = $array1Objeto;
                $array2 = $array2Objeto;
            } else {
                $array2 = $array1Objeto;
                $array1 = $array2Objeto;
            }
            
            foreach($array1 as $obj1){
                $metodo = new ReflectionMethod(get_class($obj1),"get".ucfirst($attr));
                $flag = false;
                foreach($array2 as $obj2){
                    if($metodo->invoke($obj1) == $metodo->invoke($obj2)){
                        $flag = true;
                        break;
                        echo $metodo->invoke($obj2)."<br>";
                    }
                }
                
                if(!$flag)
                    $arrayReturn[] = $obj1;
                
            }
            return $arrayReturn;
        }
        
    }
?>