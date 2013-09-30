<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 */

class Municipio extends Model {

    private $id;
    private $nome;
    private $estado;
    private $logitude;
    private $latitude;
    
    private $territorios = array();

    public function Municipio() {

        $this->tabela = 'municipio';
        $this->schema = 'geografico';
        $this->colMap = array(
            'ide_municipio' => 'id',
            'nom_municipio' => 'nome',
            'ide_estado'    => 'estado',
            'lat_municipio' => 'latitude',
            'lon_municipio' => 'logitude'
        );
        $this->colPersist = array(
            'nome'=>'texto',
            'estado'=>'inteiro',
            'latitude'=>'texto',
            'logitude'=>'texto'
        );
        
        $this->collections = array("Territorio:territorios"=>"area_especial_municipio");
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
    
    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getLogitude() {
        return $this->logitude;
    }

    public function setLogitude($logitude) {
        $this->logitude = $logitude;
    }

    public function getLatitude() {
        return $this->latitude;
    }

    public function setLatitude($latitude) {
        $this->latitude = $latitude;
    }
    
    public function getTerritorios() {
        return $this->territorios;
    }

    public function setTerritorios($territorios) {
        $this->territorios[] = $territorios;
    }



}
?>
