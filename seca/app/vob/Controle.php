<?php
class Controle extends Model {

    private $id;
    private $colaborador;
    private $sistema;
    private $tipo; //objeto
    private $responsavel; //objeto
    private $status;
    
    public function Controle() {

        $this->tabela = 'controle';
        $this->schema = 'pessoal';
        $this->colMap = array(
            'ide_controle' => 'id',
            'ide_colaborador' => 'colaborador',
            'ide_sistema' => 'sistema',
            'ide_tipo_colaborador' => 'tipo',
            'ide_colaborador_responsavel' => 'responsavel',
            'des_status'=>'status'
        );
        $this->colPersist = array(
            'colaborador'=>'objeto',
            'sistema'=>'inteiro',
            'tipo'=>'objeto',
            'responsavel'=>'objeto',
            'status'=>'texto',
        );
        
        $this->relations = array(
            'tipo'=>'TipoColaborador',
            'responsavel'=>'Colaborador'
        );
    }

   
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getColaborador() {
        return $this->colaborador;
    }

    public function setColaborador($colaborador) {
        $this->colaborador = $colaborador;
    }

    public function getSistema() {
        return $this->sistema;
    }

    public function setSistema($sistema) {
        $this->sistema = $sistema;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function getResponsavel() {
        return $this->responsavel;
    }

    public function setResponsavel($responsavel) {
        $this->responsavel = $responsavel;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

}

?>
