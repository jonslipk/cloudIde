<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @tutorial Classe responsavel por Valida��o dos Campos inseridos no banco
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
     * @tutorial verifica se a data passada � v�lida. Em rela��o a dia de 01 a 31
     * a depender do m�s, m�s de 01 a 12 e ano ilimitado. idependente do formato da data
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
     * Verifica se o cpf � v�lido
     * @param type $cpf
     * @return boolean 
     */
    public function validaCPF($cpf) { // Verifiva se o n�mero digitado cont�m todos os digitos
        $cpf = str_pad(str_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);

        // Verifica se nenhuma das sequ�ncias abaixo foi digitada, caso seja, retorna falso
        if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
            return false;
        } else {   // Calcula os n�meros para verificar se o CPF � verdadeiro
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
     * Verifica se o e-mail � valido
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
     * Verifica se o Cep � v�lido
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
     * Esta fun��o testa se um Cnpj � valido ou n�o. 
     *
     * 
     * @param	string		$cnpj			Guarda o Cnpj como ele foi digitado pelo cliente
     * @param	array		$num			Guarda apenas os n�meros do Cnpj
     * @param	boolean		$cnpjValido	Guarda o retorno da fun��o
     * @param	int			$multiplica 	Auxilia no Calculo dos D�gitos verificadores
     * @param	int			$soma			Auxilia no Calculo dos D�gitos verificadores
     * @param	int			$resto			Auxilia no Calculo dos D�gitos verificadores
     * @param	int			$dg				D�gito verificador
     * @return	boolean						"true" se o Cnpj � v�lido ou "false" caso o contr�rio
     *
     */
    function validaCnpj($cnpj) {
        //Etapa 1: Cria um array com apenas os digitos num�ricos, isso permite receber o cnpj em diferentes formatos como "00.000.000/0000-00", "00000000000000", "00 000 000 0000 00" etc...
        $j = 0;
        for ($i = 0; $i < (strlen($cnpj)); $i++) {
            if (is_numeric($cnpj[$i])) {
                $num[$j] = $cnpj[$i];
                $j++;
            }
        }
        //Etapa 2: Conta os d�gitos, um Cnpj v�lido possui 14 d�gitos num�ricos.
        if (count($num) != 14) {
            $cnpjValido = false;
        }
        //Etapa 3: O n�mero 00000000000 embora n�o seja um cnpj real resultaria um cnpj v�lido ap�s o calculo dos d�gitos verificares e por isso precisa ser filtradas nesta etapa.
        if ($num[0] == 0 && $num[1] == 0 && $num[2] == 0 && $num[3] == 0 && $num[4] == 0 && $num[5] == 0 && $num[6] == 0 && $num[7] == 0 && $num[8] == 0 && $num[9] == 0 && $num[10] == 0 && $num[11] == 0) {
            $cnpjValido = false;
        }
        //Etapa 4: Calcula e compara o primeiro d�gito verificador.
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
        //Etapa 5: Calcula e compara o segundo d�gito verificador.
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
          echo "<p><font color=\"GREEN\">Cnpj � V�lido</font></p>";
          }
          if($cnpjValido==false)
          {
          echo "<p><font color=\"RED\">Cnpj Inv�lido</font></p>";
          }
         */
        //Etapa 6: Retorna o Resultado em um valor booleano.
        return $cnpjValido;
    }

    /**
     *  Essa fun��o retornar� true para os seguintes casos:

     * $var = ??; (uma string vazia)
     * $var = 0; (um inteiro valendo zero)
     * $var = ?0?; (uma string contendo zero)
     * $var = NULL; (vari�veis nulas)
     * $var = FALSE; (vari�veis falsas)
     * $var = array(); (um array vazio)
     * var $var; (uma vari�vel declarada, sem valor, dentro de uma classe)

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
     * Metodo de valida��o
     * Retorna true se n�o ocorrer erros e false se ocorrer um erro
     * @param type $Objeto
     * @param type $ArrayRequire
     * @return boolean 
     */
    public function validarObjeto($Objeto, $ArrayRequire) {

        foreach ($ArrayRequire as $getCampo => $array) {

            foreach ($array as $validacao => $valor) {

                if ($validacao == "empty") {
                    # Verifica se campo � nulo (TRUE = se campo � nulo)
                    if ($this->validaIsNull($Objeto->$getCampo())) {
                        return false;
                        #echo "<hr>{$getCampo} = EMPTY (FALSE)";
                    }
                }

                if ($validacao == 'length') {
                    # Verifica se o tamanho do campo � menor que o requirido (True = se for menor)
                    if ($this->minLength($Objeto->$getCampo(), $valor)) {
                        return false;
                        #echo "<hr>{$getCampo} = LENGTH (FALSE)";
                    }
                }

                if ($validacao == 'cpf') {
                    # Se CPF � falso
                    if (!$this->validaCPF($Objeto->$getCampo())) {
                        return false;
                        #echo "<hr>{$getCampo} = CPF (FALSO)";
                    }
                }

                if ($validacao == "email") {
                    # se email � falso
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
