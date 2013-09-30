<?php

class TipoColaborador extends Model {

    private $id;
    private $nome;
    private $status;
    private $responsabilidade = array();
    
    public function TipoColaborador() {

        $this->tabela = 'tipo_colaborador';
        $this->schema = 'pessoal';
        $this->colMap = array(
            'ide_tipo_colaborador' => 'id',
            'nom_tipo_colaborador' => 'nome',
            'des_status' => 'status',            
        );
        $this->colPersist = array(
            'nome'=>'texto',
            'status'=>'texto'
        );
    }
   
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getResponsabilidade() {
        return $this->responsabilidade;
    }

    public function setResponsabilidade($responsabilidade) {
        $this->responsabilidade[] = $responsabilidade;
    }

}

?>
