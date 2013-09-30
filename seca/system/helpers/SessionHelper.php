<?php
    /**
    * SessionHelper
    *
    * @author Secretaria de Desenvolvimento Social e Combate a Pobreza - Coordenação de Modernização
    * @author Desenvolvedores: Robermarlei Oliveira, Samuel Guimarães e Fábio Elísio
    * @copyright 2011
    * @version 1.0
    * @package HELPERS
    * @access Public
    * @tutorial Classe Responsável pela população de sessões unidas ao Script Populate que comanda o Adicionar e Remover
    */
   class SessionHelper {

        private $array;
        private $session;

         /*
         * SessionPopulate::SessionPopulate
         *
         * @param session
         * @return Void
         * @access Public
         * @tutorial: Construtor da classe recebe o nome da sessão que será iniciada pela classe
         */
        public function SessionHelper($session){

            if (!isset($_SESSION))
                session_start();
            
            $this->session = $session;
            $this->startSession($session);
            
        }

        private function startSession($session){

            if(!isset($_SESSION[$session])) {
                session_register($this->session);
                $_SESSION[$this->session] = new ArrayObject();
            }
            
        }

        private function atualizarDados() {
            $_SESSION[$this->session] = $this->array;
	}

	private function getArray(){
            $this->array = $_SESSION[$this->session];
	}
        
        public function obterPorKey($key) {

            $this->getArray();

            if (isset($this->array[$key]))
                return $this->array[$key];

            return false;
            
	}

        public function adicionarElemento($objeto, $key){

            $this->getArray();

            if (!$this->verificarKeyExistente($key)) {
                $this->array[$key] = $objeto;
                $this->atualizarDados();
                return true;
            } else {
                return false;
            }
            
	}

        public function obterSoma($variavel){

            $this->getArray();
            $soma = 0;

            if($this->obterQuantidade() > 0){
                for ($iterator = $this->array->getIterator(); $iterator->valid(); $iterator->next()) {
                    $key = $iterator->key();
                    break;
                }

                $reflect  = new ReflectionClass(get_class($this->array[$key]));
                $props = $reflect->getProperties(ReflectionProperty::IS_PRIVATE);

                foreach ($props as $prop){
                    if($prop->getName() == $variavel){
                        for ($iterator = $this->array->getIterator(); $iterator->valid(); $iterator->next()) {
                            $soma += $reflect->getMethod("get".ucfirst($prop->getName()))->invoke($iterator->current());
                        }
                    }
                }
            }
            return $soma;
        }

        private function verificarKeyExistente($key){

            $this->getArray();

            for ($iterator = $this->array->getIterator(); $iterator->valid(); $iterator->next()) {
                    if($iterator->key() == $key) {
                            return true;
                    }
            }

            return false;

	}

        public function obterQuantidade(){

            $this->getArray();
            return $this->array->count();
            
        }

        public function removerElemento($key){

            $this->getArray();
            
            if ($this->verificarKeyExistente($key)) {
                unset($this->array[$key]);
                $this->atualizarDados();

                return true;
            } else {
                return false;
            }
	}

        public function removerTodos(){
            $this->getArray = null;
	}

        public function clear(){
            session_unregister($this->session);
        }

    }
?>