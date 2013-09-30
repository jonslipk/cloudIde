<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormatHelper
 * @package system
 * @subpackage helpers
 * @author jonas
 */
class FormatHelper {
    /*
     * Instancia do Objeto ACTFactory para esta classe.
     */

    private static $instancia = null;

    /**
     * ACTFactory::getInstancia
     *
     * @package system
     * @subpackage helpers
     * @param Void
     * @return Objeto ACTFactory
     * @tutorial: Verifica se existe uma instancia do Objeto ACTFactory, caso contrario
     * da um new e retorna o objeto
     */

    public static function getInstancia() {
        if (self::$instancia == null) {
            self::$instancia = new FormatHelper();
        }
        return self::$instancia;
    }

    /**
     * ACTFactory::__clone
     *
     * @package system
     * @subpackage helpers
     * @param Void
     * @return Null
     * @tutorial: Impede que este objeto seja clonado
     * @exception: Clone nao e permitido.
     */

    public function __clone() {
        trigger_error('Clone não é permitido.', E_USER_ERROR);
    }

     /**
     * @tutorial: Formatação para numero de CPF
     * FormatHelper::formatCPF
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $cpf
     * @return string cpf_formatado ###.###.###-## 
     */
    public function formatCPF($cpf) {

        $cpf_unformat = $this->limparString($cpf);

        if (strlen($cpf_unformat) == 11) {
            $cpf_pri = substr($cpf_unformat, 0, 3);
            $cpf_seg = substr($cpf_unformat, 3, 3);
            $cpf_ter = substr($cpf_unformat, 6, 3);
            $cpf_ver = substr($cpf_unformat, 9, 2);

            $cpfFormat = $cpf_pri . "." . $cpf_seg . "." . $cpf_ter . "-" . $cpf_ver;

            return $cpfFormat;
        } else {

             return "CPF não tem a quantidade de caracteres para formatar";
        }
    }

     /**
     * @tutorial: Formatação para numero de CPF
     * FormatHelper::unformatCPF
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $cpf
     * @return string cpf_formatado ###.###.###-## 
     */
    public function unformatCPF($cpf) {

        $cpf_format = $this->limparString($cpf);
        
        if (strlen($cpf_format) == 11) {
        $cpf_format = str_replace(".", "", $cpf_format);
        $cpf_format = str_replace("-", "", $cpf_format);

        $cpf_unformat = $cpf_format;

        return $cpf_unformat;
        }else{
            return "CPF não tem a quantidade de caracteres para unformatar"; 
        }
    }

     /**
     * @tutorial: Formatação para numero de CEP
     * FormatHelper::formatCEP
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $cep
     * @return string $cepFormat
     */
    public function formatCEP($cep) {

        if (strlen($cep) > 8)
            return $cep;

        $cep_unformat = $cep;

        $cep_pri = substr($cep_unformat, 0, 5);
        $cep_seg = substr($cep_unformat, 5, 3);

        $cepFormat = $cep_pri . "-" . $cep_seg;

        return $cepFormat;
    }

     /**
     * @tutorial: Formatação para numero de CEP
     * FormatHelper::unformatCEP
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $cep
     * @return string $cep_unformat
     */
    public function unformatCEP($cep) {

        $cep_format = $cep;

        $cep_format = str_replace(".", "", $cep_format);
        $cep_format = str_replace("-", "", $cep_format);

        $cep_unformat = $cep_format;

        return $cep_unformat;
    }

    /**
     * @tutorial: Formatação para numero de telefone
     * FormatHelper::formatTelefone
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $tel
     * @return string $tel_format
     */
    public function formatTelefone($tel) {

        $telefone_unformat = $tel;

        if (strlen($telefone_unformat) != 10) {
            return $telefone_unformat;
        }

        $telefone_prefixo = substr($telefone_unformat, 0, 2);
        $telefone_pri = substr($telefone_unformat, 2, 4);
        $telefone_seg = substr($telefone_unformat, 6, 4);

        $telefone_format = "(" . $telefone_prefixo . ")" . $telefone_pri . "-" . $telefone_seg;
        return $telefone_format;
    }

     /**
     * @tutorial: Formataçao pra numero de telefone
     * FormatHelper::unformatTelefone
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $tel
     * @return string $tel_unformat
     */
    public function unformatTelefone($tel) {

        $telefone_format = $tel;

        $telefone_format = str_replace("(", "", $telefone_format);
        $telefone_format = str_replace(")", "", $telefone_format);
        $telefone_format = str_replace("-", "", $telefone_format);

        $telefone_unformat = $telefone_format;

        return $telefone_unformat;
    }

    /**
     * @tutorial: Formataçao para numero decimal
     * FormatHelper::formatNumeroDecimal
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $valor
     * @return string $numero_format
     */
    public function formatNumeroDecimal($valor) {

        $numero_format = $valor;

        $numero_format = str_replace(".", "", $numero_format);
        $numero_format = str_replace(",", ".", $numero_format);

        return $numero_format;
    }

    /**
     * @tutorial: Formatação para número em reais em decimal
     * FormatHelper::formatValorInteiro
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $valor (1200)
     * @return real $valorFomatado (1.200)
     */
    public function formatValorInteiro($valor) {

        if ($valor == null)
            return null;

        $numberUnformat = $valor;

        $numberFormat = number_format($numberUnformat, 0, ',', '.');

        return $numberFormat;
    }

    /**
     * @tutorial: Formatação de numero inteiro para numero monetário
     * FormatHelper::formatNumeroMonetario
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $valor
     * @return valor valor monetario
     */
    public function formatNumeroMonetario($valor) {

        if ($valor == null)
            return null;

        $numberUnformat = $valor;

        $numberFormat = number_format($numberUnformat, 2, ',', '.');

        return $numberFormat;
    }

    /**
     * @tutorial: Formataçao para numero decimal
     * FormatHelper::unformatNumeroDecimal
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $valor
     * @return string $numero_unformat
     */
    public function unformatNumeroDecimal($valor) {

        $numero_format = $valor;

        $numero_unformat = str_replace(".", ",", $numero_format);


        return $numero_unformat;
    }

    /**
     * @tutorial: Formataçao para numero da conta
     * FormatHelper::formatConta
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $contadigito
     * @return string $conta_format
     */
    public function formatConta($contadigito) {

        $conta_unformat = $contadigito;

        $tamanho = strlen($conta_unformat);
        $cont = $tamanho - 1;

        $conta = substr($conta_unformat, 0, $cont);
        $digito = substr($conta_unformat, $cont, $tamanho);

        $conta_format = $conta . "-" . $digito;
        return $conta_format;
    }

    /**
     * @tutorial: Formataçao para numero da conta
     * FormatHelper::unformatConta
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $contadigito
     * @return string $conta
     */
    public function unformatConta($contadigito) {

        $conta_format = $contadigito;

        $conta = str_replace("-", "", $conta_format);


        return $conta;
    }

    /**
     * @tutorial: Formataçao para numero da agencia
     * FormatHelper::formatAgencia
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $agenciadigito
     * @return string $agencia_format
     */
    public function formatAgencia($agenciadigito) {

        $agencia_unformat = $agenciadigito;

        $tamanho = strlen($agencia_unformat);
        $cont = $tamanho - 1;

        $agencia = substr($agencia_unformat, 0, $cont);
        $digito = substr($agencia_unformat, $cont, $tamanho);

        $agencia_format = $agencia . "-" . $digito;
        return $agencia_format;
    }

    /**
     * @tutorial: Formatação de uma string de qualquer forma desejada
     * FormatHelper::formatMascaras
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $mascara (data)##/##/## (cpf)###.###.###-## (hora)##:## 
     * @param type $string
     * @return type $string
     */
    public function formatMascaras($mascara, $string) {
        $string = str_replace(" ", "", $string);
        for ($i = 0; $i < strlen($string); $i++) {
            $mascara[strpos($mascara, "#")] = $string[$i];
        }
        return $mascara;
    }

    /**
     * @tutorial: Formataçao para data
     * FormatHelper::formatData
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $data
     * @return string $data_format
     */
    public function formatData($data) {

        $data_unformat = $data;
        $ano = substr($data_unformat, 4, 4);
        $mes = substr($data_unformat, 2, 2);
        $dia = substr($data_unformat, 0, 2);
        $data_format = $dia . "/" . $mes . "/" . $ano;

        return $data_format;
    }

    /**
     * @tutorial: Formataçao para data
     * FormatHelper::unformatData
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $data
     * @return string $unfor;
     */
    public function unformatHora($hora) {

        $hora_format = $hora;

        $unfor = str_replace(":", "", $hora_format);

        return $unfor;
    }
    
    /**
     * @tutorial: Formataçao para data
     * FormatHelper::unformatData
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $data
     * @return string $unfor;
     */
    public function unformatData($data) {

        $data_format = $data;

        $unfor = str_replace("/", "", $data_format);

        return $unfor;
    }

  /**
   * @tutorial: Função limparString com os caracteres ("<", ">", "\\", "/", "=", "'", "?",".","-")
   * FormatHelper::limparString
   * @package system
   * @subpackage helpers
   * @access public
   * @param type $string
   * @return type string
   */
    public function limparString($string) {
        
        $get = str_replace(array("<", ">", "\\", "/", "=", "'", "?",".","-"), "", $string);

        return $get;
    }

    
    /**
     * @tutorial: Formataçao para data
     * FormatHelper::dataNormalToInversa
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $valor
     * @return string $dataInversa
     */
      public function dataNormalToInversa($valor) {

        $dataInversa = null;
        $isBarra = substr_count($valor, "/");
        
        if($isBarra == 2){
            $dataNormal = explode("/", $valor);
            $dataInversa = $dataNormal[2].$dataNormal[1].$dataNormal[0];
        } else {
            $unfor = str_replace("/", "", $valor);
            $dataInversa = substr($unfor,4,4).substr($unfor,2,2).substr($unfor,0,2);
        }
        
        return $dataInversa;

    }

    /**
     * @tutorial: Formataçao para data
     * FormatHelper::dataInversaToNormal
     * @package system
     * @subpackage helpers
     * @access public
     * @param type $valor
     * @return string $dataInversa
     */
    public function dataInversaToNormalTR($valor) {

        $dataInversa = null;
        $isBarra = substr_count($valor, "-");
         
        if($isBarra!=0){
            $dataNormal = explode("-", $valor);
            $dataInversa = $dataNormal[2].$dataNormal[1].$dataNormal[0];
        } else {
            $dataInversa = substr($valor,6,2).substr($valor,4,2).substr($valor,0,4);
                   
        }

        return $dataInversa;

    }
     public function dataInversaToNormal($valor) {

        $dataInversa = null;
        $isBarra = substr_count($valor, "/");
         
        if($isBarra!=0){
            $dataNormal = explode("/", $valor);
            $dataInversa = $dataNormal[2].$dataNormal[1].$dataNormal[0];
        } else {
            $dataInversa = substr($valor,6,2).substr($valor,4,2).substr($valor,0,4);
                   
        }

        return $dataInversa;

    }
    
    public function dataInversaToNormal2($valor) {

        $dataInversa = null;
        $isBarra = substr_count($valor, "");
         
        if($isBarra!=0){
            $dataNormal = explode("", $valor);
            $dataInversa = $dataNormal[2].$dataNormal[1].$dataNormal[0];
        } else {
            $dataInversa = substr($valor,6,2).substr($valor,4,2).substr($valor,0,4);
                   
        }

        return $dataInversa;

    }

        /**
         * @tutorial: Função para retirar a concatenação do Banco
         * FormatHelper::unconcatenarBD
         * @package system
         * @subpackage helpers
         * @access public
         * @param String $stringConcat
         * @return Array $array
         */
        public function unconcatenarBD($stringConcat) {

            $array = null;

            if($stringConcat == null){
                return $stringConcat;
            }

            if(!is_string($stringConcat))
                throw new Exception ("A função recebe apenas STRING");

            if(strlen(':') < 1){
                throw new Exception ("A STRING não contém divisor necessário | : |");
            }

            $array = explode(':',substr_replace($stringConcat,'',0,1),-1);

            return $array;

        }    
   
}

?>
