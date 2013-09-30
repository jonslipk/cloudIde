<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 */

class Territorio extends Model {

    private $id;
    private $codigo;
    private $nome;
    private $estado;
    private $municipios = array();
    
    public function Territorio() {

        $this->tabela = 'area_especial';
        $this->schema = 'geografico';
        $this->colMap = array(
            'ide_area_especial' => 'id',
            'cod_area_especial' => 'codigo',
            'nom_area_especial' => 'nome',
            'ide_estado'=>'estado'
        );
        $this->colPersist = array(
            'codigo'=>'inteiro',
            'nome'=>'texto',
            'estado'=>'objeto'
        );
        
        $this->relations = array(
            'estado'=>'Estado'
        );
        
        $this->collections = array(
              'Municipio:municipios'=>'area_especial_municipio'
        );
    }

   
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getMunicipios() {
        return $this->municipios;
    }

    public function setMunicipios($municipios) {
        $this->municipios[] = $municipios;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

}

?>
