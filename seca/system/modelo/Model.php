<?php
    
    /**
     * Model
     * @abstract
     * @package /VOB
     * @author Open Computadores
     * @version 1.0
     */
    abstract class Model {

        /**
         * @var String
         */
        protected $tabela;
        /**
         * @var String
         */
        protected $schema;
        /**
         * @var Array
         */
        protected $colMap;
        /**
         * @var Array
         */
        protected $colPersist;
        /**
         * @var Integer
         */
        protected $collections = array();
        
        protected $relations = array();

        protected $idUsuarioCriador;
        /**
         * @var Integer
         */
        protected $dataCriacao;
        /**
         * @var Integer
         */
        protected $idUsuarioAtualizador;
        /**
         * @var Integer
         */
        protected $dataAtualizacao;

        public function getColMap(){
            return $this->colMap;
        }

        public function getPersistence(){
            return $this->colPersist;
        }

        public function getTabela(){
            if($this->schema != "" or $this->schema != null){
                return $this->schema.".".$this->tabela;
            } else {
                return $this->tabela;
            }
        }

        public function getSchema(){
            return $this->schema;
        }
        
        public function getCollections() {
            return $this->collections;
        }
        
        public function getRelations() {
            return $this->relations;
        }                
        /**
         * Log de Segurança
         */
        public function getIdUsuarioCriador(){
                return $this->idUsuarioCriador;
        }

        public function setIdUsuarioCriador($valor){
                $this->idUsuarioCriador = $valor;
        }

        public function getDataCriacao(){
                return $this->dataCriacao;
        }

        public function setDataCriacao($valor){
                $this->dataCriacao = $valor;
        }

        public function getIdUsuarioAtualizador(){
                return $this->idUsuarioAtualizador;
        }

        public function setIdUsuarioAtualizador($valor){
                $this->idUsuarioAtualizador = $valor;
        }

        public function getDataAtualizacao(){
                return $this->dataAtualizacao;
        }

        public function setDataAtualizacao($valor){
                $this->dataAtualizacao = $valor;
        }
        
    }

?>