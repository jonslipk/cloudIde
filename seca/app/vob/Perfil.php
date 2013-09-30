<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 */

class Perfil extends Model {
    
    private $id;
    private $nome;
    private $status;
    private $descricao;
    private $funcionalidades = array();
    
    public function Perfil(){
        
        $this->tabela = 'perfil';
        $this->schema = 'seguranca';
        $this->colMap = array(
                'ide_perfil'=>'id',
                'nom_perfil'=>'nome',
                'des_perfil'=>'descricao',
                'des_status'=>'status'                
            );
        $this->colPersist = array(
                'nome'=>'texto',
                'descricao'=>'texto',
                'status'=>'texto'                
            );
        
        $this->collections = array(
                'Funcionalidade:funcionalidades'=>'perfil__funcionalidade'
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

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getFuncionalidades() {
        return $this->funcionalidades;
    }

    public function setFuncionalidades($funcionalidades) {
        $this->funcionalidades[] = $funcionalidades;
    }


        
}
?>
