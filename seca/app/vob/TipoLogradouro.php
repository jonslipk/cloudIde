<?php

class TipoLogradouro extends Model {

    private $id;
    private $descricao;
    private $status;
    
    public function TipoLogradouro() {

        $this->tabela = 'tipo_logradouro';
        $this->schema = 'geografico';
        $this->colMap = array(
            'ide_tipo_logradouro' => 'id',
            'des_tipo_logradouro' => 'descricao',
            'des_status' => 'status'
        );
        $this->colPersist = array(
            'descricao'=>'texto',
            'status'=>'texto'
        );
    }
   
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
  
}

?>
