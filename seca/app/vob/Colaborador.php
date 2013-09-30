<?php

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 */
class Colaborador extends Model {

    private $id;
    private $nome;
    private $numeroCPF;
    private $dataNascimento;
    private $numeroTelefone1;
    private $numeroTelefone2;
    private $enderecoReferencia;
    private $email;
    private $bairro; //objeto
    private $logradouro;
    private $municipio; //objeto
    private $tipoLogradouro; //objeto
    private $numeroLogradouro;
    private $numeroCEP;
    private $nomeMae;
    //private $tipoEscolaridade;
    private $controle; 
    private $endereco = "";

    public function Colaborador() {

        $this->tabela = 'colaborador';
        $this->schema = 'pessoal';
        $this->colMap = array(
            'ide_colaborador' => 'id',
            'nom_colaborador' => 'nome',
            'num_cpf' => 'numeroCPF',
            'dat_nascimento' => 'dataNascimento',
            'num_telefone1' => 'numeroTelefone1',
            'num_telefone2' => 'numeroTelefone2',
            'des_endereco_referencia' => 'enderecoReferencia',
            'des_email' => 'email',
            'ide_bairro' => 'bairro',
            'nom_logradouro' => 'logradouro',
            'ide_municipio' => 'municipio',
            'ide_tipo_logradouro' => 'tipoLogradouro',
            'num_logradouro' => 'numeroLogradouro',
            'num_cep' => 'numeroCEP',
            'nom_mae' => 'nomeMae',
                //'ide_tipo_escolaridade'=>'tipoEscolaridade'    
        );
        $this->colPersist = array(
            'nome' => 'texto',
            'numeroCPF' => 'cpf',
            'dataNascimento' => 'data',
            'numeroTelefone1' => 'telefone',
            'numeroTelefone2' => 'telefone',
            'enderecoReferencia' => 'texto',
            'email' => 'texto',
            'bairro' => 'objeto',
            'logradouro' => 'texto',
            'municipio' => 'texto',
            'tipoLogradouro' => 'objeto',
            'numeroLogradouro' => 'inteiro',
            'numeroCEP' => 'cep',
            'nomeMae' => 'texto',
                //'tipoEscolaridade'=>'objeto'
        );

        $this->relations = array(
            'municipio' => 'Municipio',
            'bairro' => 'Bairro',
            'tipoLogradouro' => 'TipoLogradouro',
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

    public function getNumeroCPF() {
        return $this->numeroCPF;
    }

    public function setNumeroCPF($numeroCPF) {
        $this->numeroCPF = $numeroCPF;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function setDataNascimento($dataNascimento) {
        $this->dataNascimento = $dataNascimento;
    }

    public function getNumeroTelefone1() {
        return $this->numeroTelefone1;
    }

    public function setNumeroTelefone1($numeroTelefone1) {
        $this->numeroTelefone1 = $numeroTelefone1;
    }

    public function getNumeroTelefone2() {
        return $this->numeroTelefone2;
    }

    public function setNumeroTelefone2($numeroTelefone2) {
        $this->numeroTelefone2 = $numeroTelefone2;
    }

    public function getEnderecoReferencia() {
        return $this->enderecoReferencia;
    }

    public function setEnderecoReferencia($enderecoReferencia) {
        $this->enderecoReferencia = $enderecoReferencia;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    public function getLogradouro() {
        return $this->logradouro;
    }

    public function setLogradouro($logradouro) {
        $this->logradouro = $logradouro;
    }

    public function getMunicipio() {
        return $this->municipio;
    }

    public function setMunicipio($municipio) {
        $this->municipio = $municipio;
    }

    public function getTipoLogradouro() {
        return $this->tipoLogradouro;
    }

    public function setTipoLogradouro($tipoLogradouro) {
        $this->tipoLogradouro = $tipoLogradouro;
    }

    public function getNumeroLogradouro() {
        return $this->numeroLogradouro;
    }

    public function setNumeroLogradouro($numeroLogradouro) {
        $this->numeroLogradouro = $numeroLogradouro;
    }

    public function getNumeroCEP() {
        return $this->numeroCEP;
    }

    public function setNumeroCEP($numeroCEP) {
        $this->numeroCEP = $numeroCEP;
    }

    public function getNomeMae() {
        return $this->nomeMae;
    }

    public function setNomeMae($nomeMae) {
        $this->nomeMae = $nomeMae;
    }

    public function getControle() {
        return $this->controle;
    }

    public function setControle($controle) {
        $this->controle = $controle;
    }

    public function getEndereco() {
        $endereco = "";
        if ($this->getTipoLogradouro() != "") {
            $endereco .= $this->getTipoLogradouro()->getDescricao();
        }

        if ($this->getLogradouro() != "") {

            if (strlen($endereco) != 0) {
                $endereco .= " ";
            }

            $endereco .= $this->getLogradouro();
        }

        if ($this->getNumeroLogradouro() != "") {

            if (strlen($endereco) != 0) {
                $endereco .= " , ";
            }

            $endereco .= "N°" . $this->getNumeroLogradouro();
        }

        if ($this->getBairro() != null && $this->getBairro()->getNome() != null) {

            if (strlen($endereco) != 0) {
                $endereco .= " , ";
            }

            $endereco .= $this->getBairro()->getNome();
        }

        if ($this->getMunicipio() != null) {

            if (strlen($endereco) != 0) {
                $endereco .= " - ";
            }

            $endereco .= $this->getMunicipio()->getNome() . " - BA";
        }

        if ($this->getEnderecoReferencia() != "") {
            $endereco .= " ( " . $this->getEnderecoReferencia() . " )";
        }

        return $endereco;
    }

    public function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

}

?>
