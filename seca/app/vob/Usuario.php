<?php

class Usuario extends Model {

    private $id;
    private $senha;
    private $status;
    private $acessos;
    private $dataUltimoAcesso;
    private $tryLogon;
    private $perfil;
    private $colaborador; //tipo objeto

    public function Usuario() {

        $this->tabela = 'usuario';
        $this->schema = 'seguranca';
        $this->colMap = array(
            'ide_usuario' => 'id',
            'des_senha' => 'senha',
            'des_status' => 'status',
            'num_acessos' => 'acessos',
            'dat_ultimo_acesso' => 'dataUltimoAcesso',
            'try_logon' => 'tryLogon',
            'ide_colaborador' => 'colaborador'
        );
        
        $this->colPersist = array(
            'senha'=>'senha',
            'status' => 'texto',
            'acessos' => 'inteiro',
            'dataUltimoAcesso' => 'inteiro',
            'tryLogon' => 'inteiro',
            'colaborador' => 'objeto'
        );
        
        $this->relations = array('colaborador'=>'Colaborador');
        
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getAcessos() {
        return $this->acessos;
    }

    public function setAcessos($acessos) {
        $this->acessos = $acessos;
    }

    public function getDataUltimoAcesso() {
        return $this->dataUltimoAcesso;
    }

    public function setDataUltimoAcesso($dataUltimoAcesso) {
        $this->dataUltimoAcesso = $dataUltimoAcesso;
    }

    public function getTryLogon() {
        return $this->tryLogon;
    }

    public function setTryLogon($tryLogon) {
        $this->tryLogon = $tryLogon;
    }
        
    public function getPerfil() {
        return $this->perfil;
    }

    public function setPerfil($perfil) {
        $this->perfil = $perfil;
    }
    
    public function getColaborador() {
        return $this->colaborador;
    }

    public function setColaborador($colaborador) {
        $this->colaborador = $colaborador;
    }

}

?>
