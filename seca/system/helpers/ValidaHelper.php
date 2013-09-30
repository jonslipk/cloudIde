<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @tutorial Classe responsavel por Validação dos Campos inseridos no banco
 * de dados
 * @package HELPERS
 * @author samuel
 */
class ValidaHelper {
    
    private static $instancia = null;
    
     public static function getInstancia(){
            if(self::$instancia == null){
                self::$instancia = new ValidaHelper();
            }
            return self::$instancia;
        }

    /**
     * @tutorial verifica se a data passada é válida. Em relação a dia de 01 a 31
     * a depender do mês, mês de 01 a 12 e ano ilimitado. idependente do formato da data
     * ddmmaaaa ou aaaammdd. Metodo utilizado pela metodo dataParaTimeStamp
     * @access private
     * @param <timestamp> $data
     * @return <booleano>
     */
    public function validaData($data) {
        $data = split("[-,/,:,' ']", $data);
        
        foreach ($data as $value) {
            if(!is_numeric($value)){
                return false;
            }
        }
        
        if (!checkdate($data[1], $data[0], $data[2]) and !checkdate($data[1], $data[2], $data[0])) {
            return false;
        }
        return true;
    }
    
    /**
     * Verifica se o cpf é válido
     * @param type $cpf
     * @return boolean 
     */
    public function validaCPF($cpf) { // Verifiva se o número digitado contém todos os digitos
        $cpf = str_pad(str_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);

        // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
        if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
            return false;
        } else {   // Calcula os números para verificar se o CPF é verdadeiro
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }

                $d = ((10 * $d) % 11) % 10;

                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }

    /**
     * Verifica se o e-mail é valido
     * @param type $email
     * @return boolean 
     */
    public function validaEmail($email) {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Verifica se o Cep é válido
     * @param type $cep
     * @return boolean 
     */
    public function validaCep($cep) {
        //retira espacos em branco
        $cep = trim($cep);
        //expressao regular para avaliar o cep
        $avaliaCep = ereg("^[0-9]{5}-[0-9]{3}$", $cep);
        //verifica o resultado
        if ((!$avaliaCep)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * validaCnpj
     *
     * Esta função testa se um Cnpj é valido ou não. 
     *
     * 
     * @param	string		$cnpj			Guarda o Cnpj como ele foi digitado pelo cliente
     * @param	array		$num			Guarda apenas os números do Cnpj
     * @param	boolean		$cnpjValido	Guarda o retorno da função
     * @param	int			$multiplica 	Auxilia no Calculo dos Dígitos verificadores
     * @param	int			$soma			Auxilia no Calculo dos Dígitos verificadores
     * @param	int			$resto			Auxilia no Calculo dos Dígitos verificadores
     * @param	int			$dg				Dígito verificador
     * @return	boolean						"true" se o Cnpj é válido ou "false" caso o contrário
     *
     */
    function validaCnpj($cnpj) {
        //Etapa 1: Cria um array com apenas os digitos numéricos, isso permite receber o cnpj em diferentes formatos como "00.000.000/0000-00", "00000000000000", "00 000 000 0000 00" etc...
        $j = 0;
        for ($i = 0; $i < (strlen($cnpj)); $i++) {
            if (is_numeric($cnpj[$i])) {
                $num[$j] = $cnpj[$i];
                $j++;
            }
        }
        //Etapa 2: Conta os dígitos, um Cnpj válido possui 14 dígitos numéricos.
        if (count($num) != 14) {
            $cnpjValido = false;
        }
        //Etapa 3: O número 00000000000 embora não seja um cnpj real resultaria um cnpj válido após o calculo dos dígitos verificares e por isso precisa ser filtradas nesta etapa.
        if ($num[0] == 0 && $num[1] == 0 && $num[2] == 0 && $num[3] == 0 && $num[4] == 0 && $num[5] == 0 && $num[6] == 0 && $num[7] == 0 && $num[8] == 0 && $num[9] == 0 && $num[10] == 0 && $num[11] == 0) {
            $cnpjValido = false;
        }
        //Etapa 4: Calcula e compara o primeiro dígito verificador.
        else {
            $j = 5;
            for ($i = 0; $i < 4; $i++) {
                $multiplica[$i] = $num[$i] * $j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $j = 9;
            for ($i = 4; $i < 12; $i++) {
                $multiplica[$i] = $num[$i] * $j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $resto = $soma % 11;
            if ($resto < 2) {
                $dg = 0;
            } else {
                $dg = 11 - $resto;
            }
            if ($dg != $num[12]) {
                $cnpjValido = false;
            }
        }
        //Etapa 5: Calcula e compara o segundo dígito verificador.
        if (!isset($cnpjValido)) {
            $j = 6;
            for ($i = 0; $i < 5; $i++) {
                $multiplica[$i] = $num[$i] * $j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $j = 9;
            for ($i = 5; $i < 13; $i++) {
                $multiplica[$i] = $num[$i] * $j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $resto = $soma % 11;
            if ($resto < 2) {
                $dg = 0;
            } else {
                $dg = 11 - $resto;
            }
            if ($dg != $num[13]) {
                $cnpjValido = false;
            } else {
                $cnpjValido = true;
            }
        }
        //Trecho usado para depurar erros.
        /*
          if($cnpjValido==true)
          {
          echo "<p><font color=\"GREEN\">Cnpj é Válido</font></p>";
          }
          if($cnpjValido==false)
          {
          echo "<p><font color=\"RED\">Cnpj Inválido</font></p>";
          }
         */
        //Etapa 6: Retorna o Resultado em um valor booleano.
        return $cnpjValido;
    }

    /**
     *  Essa função retornará true para os seguintes casos:

     * $var = ??; (uma string vazia)
     * $var = 0; (um inteiro valendo zero)
     * $var = ?0?; (uma string contendo zero)
     * $var = NULL; (variáveis nulas)
     * $var = FALSE; (variáveis falsas)
     * $var = array(); (um array vazio)
     * var $var; (uma variável declarada, sem valor, dentro de uma classe)

     * @param type $variavel
     * @return boolean 
     */
    public function validaIsNull($variavel) {

        if (empty($variavel)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verificar se a string correspode ao tamanho minimo de caracteres
     * @param type $variavel
     * @param type $requireLenght
     * @return boolean 
     */
    public function minLength($string, $requireLenght) {
        # se o tamanho da variavel for menor que o requerido
        if (strlen($string) < $requireLenght) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Metodo de validação
     * Retorna true se não ocorrer erros e false se ocorrer um erro
     * @param type $Objeto
     * @param type $ArrayRequire
     * @return boolean 
     */
    public function validarObjeto($Objeto, $ArrayRequire) {

        foreach ($ArrayRequire as $getCampo => $array) {

            foreach ($array as $validacao => $valor) {

                if ($validacao == "empty") {
                    # Verifica se campo é nulo (TRUE = se campo é nulo)
                    if ($this->validaIsNull($Objeto->$getCampo())) {
                        return false;
                        #echo "<hr>{$getCampo} = EMPTY (FALSE)";
                    }
                }

                if ($validacao == 'length') {
                    # Verifica se o tamanho do campo é menor que o requirido (True = se for menor)
                    if ($this->minLength($Objeto->$getCampo(), $valor)) {
                        return false;
                        #echo "<hr>{$getCampo} = LENGTH (FALSE)";
                    }
                }

                if ($validacao == 'cpf') {
                    # Se CPF é falso
                    if (!$this->validaCPF($Objeto->$getCampo())) {
                        return false;
                        #echo "<hr>{$getCampo} = CPF (FALSO)";
                    }
                }

                if ($validacao == "email") {
                    # se email é falso
                    if (!$this->validaEmail($Objeto->$getCampo())) {
                        return false;
                        #echo "<hr>{$getCampo} = EMAIL (FALSO)";
                    }
                }
               
            }
        }

        return true;
        
    }

}

?>
