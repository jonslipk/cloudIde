<?php

class Bairro extends Model {

    private $id;
    private $nome;
    private $municipio;
    private $status;

    public function Bairro() {

        $this->tabela = 'bairro';
        $this->schema = 'geografico';

        $this->colMap = array(
            'ide_bairro' => 'id',
            'nom_bairro' => 'nome',
            'ide_municipio' => 'municipio',
            'des_status' => 'status'
        );

        $this->colPersist = array(
            'nome' => 'texto',
            'municipio' => 'objeto',
            'status' => 'texto'
        );

        $this->relations = array(
            'municipio' => 'Municipio'
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

    public function getMunicipio() {
        return $this->municipio;
    }

    public function setMunicipio($municipio) {
        $this->municipio = $municipio;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

}

?>