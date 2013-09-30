<?php
     /**
     * Util
     *
     * @package System
     * @author Secretaria de Desenvolvimento Social e Combate a Pobreza - Coordenação de Modernização
     * @author Desenvolvedores: Robermarlei Oliveira, Samuel Guimarães e Fábio Elísio
     * @copyright 2011
     * @version 1.5
     * @access Public
     * @tutorial Classe com a função de manipular objetos e array baseado em entidades
     */
    class Util {

        /*
         * Instancia do Objeto Util para esta classe.
         */
        private static $instancia = null;
        
        /**
         * Objeto factory
         */
        private $factory;

        
        public function Util(){
            $this->factory = Factory::getInstancia();
        }
        
        /*
         * Util::getInstancia
         *
         * @package ACTION/
         * @param Void
         * @return Objeto Util
         * @tutorial: Verifica se existe uma instancia do Objeto Util, caso contrario
         * da um new e retorna o objeto
         */
        public static function getInstancia(){
            if(self::$instancia == null){
                self::$instancia = new Util();
            }
            return self::$instancia;
        }

        /*
         * Util::__clone
         *
         * @package ACTION/
         * @param Void
         * @return Null
         * @tutorial: Impede que este objeto seja clonado
         * @exception: Clone não é permitido.
         */
        public function __clone() {
            trigger_error('Clone não é permitido.', E_USER_ERROR);
        }
        
        /**
         * Util::copiarPropriedades
         * @package ACTION/
         * @param Array array
         * @param Object objeto
         * @param boolean persistence
         * @tutorial: Metodo que popula os objetos para impressão em VIEW ou persistência no Banco de Dados
         */        
        public function copiarPropridades(array $array, $objeto, $persistence = false){
            $reflect  = new ReflectionClass(get_class($objeto));
            $props = $reflect->getProperties(ReflectionProperty::IS_PRIVATE);
            
            $object = clone $objeto;
            
            foreach ($props as $prop){
                $key = array_search($prop->getName(), $objeto->getColMap());
                if (isset($array[$key]) && $key == true) {
                    $reflect->getMethod("set".ucfirst($prop->getName()))->invoke($object,$array[$key]);
                }
            }
            
            //Collections
            if($reflect->getMethod("getCollections")->invoke($object) != NULL){
                foreach($reflect->getMethod("getCollections")->invoke($object) as $key=>$prop){
                    $collection = substr($key, strpos($key,':')+1, strlen($key));
                    if(isset($array[$collection])){
                        if(is_array($array[$collection])){
                            foreach ($array[$collection] as $nKey=>$val){
                                $reflect->getMethod("set".ucfirst($collection))->invoke($object,$val);
                            }
                        }
                    }
                }
            }
            
            if($persistence) {
                $object = $this->unrenderizar($object);
            } else {
                $this->obterLog($array, $object);
            }

            return $object;
            
        }
        
        /**
         * Prepara o statement para Inserts e Updates na Base de Dados
         * Util::obterPropriedades
         * 
         * @access publico
         * @param array[] $array Array de Propriedades de Persistencia
         * @return array[] $arrayValue Array de Dados setados com os atributos do objeto
         * 
         */
        public function obterPropriedades(array $array, $objeto){
            
            $arrayValue = null;
            $reflect  = new ReflectionClass(get_class($objeto));
            
            try {
                foreach($array as $column=>$tipo){
                    if($tipo === "objeto"){
                        $object = $reflect->getMethod("get".ucfirst($column))->invoke($objeto);
                        if(is_object($object)){
                            $arrayCampos[] = $object->getId();
                        } else {
                            $arrayCampos[] = $reflect->getMethod("get".ucfirst($column))->invoke($objeto);
                        }                        
                    } else {
                        $arrayCampos[] = $reflect->getMethod("get".ucfirst($column))->invoke($objeto);
                    }
                    
                }
                if($objeto->getCollections() != null){
                    foreach($objeto->getCollections() as $key=>$collection){
                        $objeto_temp = $this->factory->fabricar(substr($key, 0, strpos($key,':')));
                        $arrayValue['alterId:'.$collection] = (strpos($collection,':'))? 
                                substr($collection, strpos($collection,':')+1, strlen($collection)) : array_search('id',$objeto_temp->getColMap());
                        
                        $arrayValue[$collection] = $reflect->
                                getMethod("get".ucfirst(substr($key, strpos($key,':')+1, strlen($key))))->
                                invoke($objeto);
                    }                    
                }                
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            
            $arrayValue[] = $arrayCampos;
            return $arrayValue;

        }

        public function copiarObjeto($objeto){

            $reflect  = new ReflectionClass(get_class($objeto));
            $array = $reflect->getMethod("getColMap")->invoke($objeto);
            
            $arrayPop = array();

            foreach($array as $k=>$y){
                $arrayPop[$k] = $reflect->getMethod("get".ucfirst($y))->invoke($objeto);
            }

            return $arrayPop;
            
        }

        private function obterLog(array $array, $objeto){

            if (isset($array["ide_usuario_criador"])) {
                $objeto->setIdUsuarioCriador($array['ide_usuario_criador']);
            }
            if (isset($array["dat_criacao"])) {
                $objeto->setDataCriacao($array['dat_criacao']);
            }
            if (isset($array["ide_usuario_atualizador"])) {
                $objeto->setIdUsuarioAtualizador($array['ide_usuario_atualizador']);
            }
            if (isset($array["dat_criacao"])) {
                $objeto->setDataAtualizacao($array['dat_criacao']);
            }
            return $objeto;
        }

        public function renderizar($objeto){

            $reflect  = new ReflectionClass(get_class($objeto));
            $array = $reflect->getMethod("getPersistence")->invoke($objeto);

            $f = $this->factory->fabricar("FormatHelper");

            foreach ($array as $k=>$y) {
                switch ($y) {
                    case "texto":
                        if(!mb_check_encoding($reflect->getMethod("get".ucfirst($k))->invoke($objeto),"UTF-8")) {
                            $reflect->getMethod("set".ucfirst($k))->invoke($objeto,utf8_encode($reflect->getMethod("get".ucfirst($k))->invoke($objeto)));
                        }
                        break;
                    case "cpf":                        
                            $reflect->getMethod("set".ucfirst($k))->invoke($objeto,$f->formatCPF($reflect->getMethod("get".ucfirst($k))->invoke($objeto)));
                        break;
                    case "telefone":
                            $reflect->getMethod("set".ucfirst($k))->invoke($objeto,$f->formatTelefone($reflect->getMethod("get".ucfirst($k))->invoke($objeto)));
                        break;
                    case "concat":
                            if(is_string($reflect->getMethod("get".ucfirst($k))->invoke($objeto)))
                                $reflect->getMethod("set".ucfirst($k))->invoke($objeto,$f->unconcatenarBD($reflect->getMethod("get".ucfirst($k))->invoke($objeto)));
                        break;
                    case "cep":
                            $reflect->getMethod("set".ucfirst($k))->invoke($objeto,$f->formatCEP($reflect->getMethod("get".ucfirst($k))->invoke($objeto)));
                        break;
                    case "data":
                            $reflect->getMethod("set".ucfirst($k))->invoke($objeto,$f->formatData($f->dataInversaToNormal($reflect->getMethod("get".ucfirst($k))->invoke($objeto))));
                        break;
                }
            }
            return $objeto;
        }

        public function unrenderizar($objeto){
            
            $reflect  = new ReflectionClass(get_class($objeto));
            $array = $reflect->getMethod("getPersistence")->invoke($objeto);
            
            $f = $this->factory->fabricar("FormatHelper");

            foreach ($array as $k=>$y) {
                switch ($y) {
                    case "texto":
                        if(mb_check_encoding($reflect->getMethod("get".ucfirst($k))->invoke($objeto),"UTF-8")) {
                            $reflect->getMethod("set".ucfirst($k))->invoke($objeto,utf8_decode($reflect->getMethod("get".ucfirst($k))->invoke($objeto)));
                        }
                        break;
                    case "cpf":
                        $reflect->getMethod("set".ucfirst($k))->invoke($objeto,$f->unformatCPF($reflect->getMethod("get".ucfirst($k))->invoke($objeto)));
                        break;
                    case "telefone": 
                        $reflect->getMethod("set".ucfirst($k))->invoke($objeto,$f->unformatTelefone($reflect->getMethod("get".ucfirst($k))->invoke($objeto)));
                        break;
                    case "cep":
                        $reflect->getMethod("set".ucfirst($k))->invoke($objeto,$f->unformatCEP($reflect->getMethod("get".ucfirst($k))->invoke($objeto)));
                        break;
                    case "concat":
                        if(is_array($reflect->getMethod("get".ucfirst($k))->invoke($objeto))){
                            if($reflect->getMethod("get".ucfirst($k))->invoke($objeto) != null){
                                $reflect->getMethod("set".ucfirst($k))->invoke($objeto,$f->concatenarBD($reflect->getMethod("get".ucfirst($k))->invoke($objeto)));
                            }
                        }
                        break;
                    case "data":
                        $reflect->getMethod("set".ucfirst($k))->invoke($objeto,$f->dataNormalToInversa($reflect->getMethod("get".ucfirst($k))->invoke($objeto)));
                        break;
                    
                    case "objeto":
                        if(is_object($reflect->getMethod("get".ucfirst($k))->invoke($objeto))){
                            
                        }
                        
                }
            }
            
            return $objeto;
        }

    }
    
?>