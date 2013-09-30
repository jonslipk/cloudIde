<?php

/**
 * Funcionalidade
 * @package System/Modelo
 * 
 */
class Funcionalidade extends Model {
    
    private $id;
    private $nome;
    private $descricao;
    private $status;
    private $perfis = array();

    public function Funcionalidade(){
        
        $this->tabela = 'funcionalidade';
        $this->schema = 'seguranca';
        $this->colMap = array(
                'ide_funcionalidade'=>'id',
                'nom_funcionalidade'=>'nome',
                'des_funcionalidade'=>'descricao',
                'des_status'=>'status'
            );
        $this->colPersist = array(
                'nome'=>'texto',
                'descricao'=>'texto',
                'status'=>'texto'
            );
        $this->collections = array(
            'Perfil:perfis'=>'perfil__funcionalidade'
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
    
    public function getPerfis() {
        return $this->perfis;
    }

    public function setPerfis($perfis) {
        $this->perfis[] = $perfis;
    }
}
?>