<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 */

class Entrega extends Model {
    
    private $id;
    private $colaborador;
    private $municipio;
    private $comunidade;
    private $valorNota;
    private $numNota;
    private $quilometragem;
    private $status;
    private $locked;
    private $quantidadeBeneficiados;
    
    public function Entrega(){
        
        $this->tabela = 'Entrega';
        $this->schema = 'seca';
        $this->colMap = array(
                'ide_entrega'=>'id',
                'ide_colaborador'=>'colaborador',
                'ide_municipio'=>'municipio',
                'des_comunidade'=>'comunidade',
                'num_nota'=>'numNota',
                'vlr_nota'=>'valorNota',
                'num_quilometragem'=>'quilometragem',
                'des_status'=>'status',
                'des_locked'=>'locked',
                'qtd_pessoas_beneficiadas'=>'quantidadeBeneficiados'
            );
        $this->colPersist = array(
                'colaborador'=>'objeto',
                'municipio'=>'objeto',
                'comunidade'=>'texto',
                'numNota'=>'inteiro',
                'valorNota'=>'',
                'quilometragem'=>'inteiro',
                'status'=>'texto',
                'locked'=>'texto',
                'quantidadeBeneficiados'=>'inteiro'
            );
        
        $this->relations = array(
                'municipio'=>'Municipio',
                'colaborador'=>'Colaborador'
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

    
    public function getMunicipio() {
        return $this->municipio;
    }

    public function setMunicipio($municipio) {
        $this->municipio = $municipio;
    }

    public function getComunidade() {
        return $this->comunidade;
    }

    public function setComunidade($comunidade) {
        $this->comunidade = $comunidade;
    }

    public function getValorNota() {
        return $this->valorNota;
    }

    public function setValorNota($valorNota) {
        $this->valorNota = $valorNota;
    }

    public function getNumNota() {
        return $this->numNota;
    }

    public function setNumNota($numNota) {
        $this->numNota = $numNota;
    }

    public function getQuilometragem() {
        return $this->quilometragem;
    }

    public function setQuilometragem($quilometragem) {
        $this->quilometragem = $quilometragem;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getLocked() {
        return $this->locked;
    }

    public function setLocked($locked) {
        $this->locked = $locked;
    }

    public function getQuantidadeBeneficiados() {
        return $this->quantidadeBeneficiados;
    }

    public function setQuantidadeBeneficiados($quantidadeBeneficiados) {
        $this->quantidadeBeneficiados = $quantidadeBeneficiados;
    }



        
}
?>
