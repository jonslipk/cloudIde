<?php
/**
 * UsuarioAction
 * @package action
 * 
 */
class UsuarioAction extends ModelAction {

    private $_Factory;

    public function UsuarioAction() {

        if (!isset($_SESSION)) {
            session_start();
        }
        $this->_Factory = Factory::getInstancia();
        parent::__construct($this->_Factory->fabricar("UsuarioDAO"));
    }

    public function obterPorId($value, $lazy = false) {

        if (is_object($value)) {
            if ($value instanceof Usuario) {
                if ($value->getId() != null) {
                    $objeto = parent::obterPorId($value, $lazy);
                }
            }
        } else {
            $objeto = $this->_Factory->fabricar("Usuario");
            $objeto->setId($value);
            $objeto = parent::obterPorId($objeto, $lazy);
        }

        return $objeto;
    }

    //RN
    private function validarSalvar($objUsuario) {
        if($objUsuario instanceof Usuario){
            return true;
        } 
        return false;
    }
    
    /**
     * UsuarioAction::salvar
     * @access public
     */
    public function salvar($objUsuario) {

        if ($this->validarSalvar($objUsuario))
            $arrayReturn = parent::salvar($objUsuario);
        
        return $arrayReturn;
    }
    
    /**
     * UsuarioAction::salvarPerfilUsuario
     * @access private
     */
    public function salvarPerfilUsuario(Usuario $objUsuario){
        return $this->objetoDAO->salvarPerfilUsuario($objUsuario);
    }
    
    public function obterPerfilUsuario(Usuario $objUsuario){
        return $this->objetoDAO->obterPerfilUsuario($objUsuario);
    }
    
    /**
     * UsuarioAction::logar
     * @param objeto Usuario
     * @access publico
     * 
     */
    public function logar(Usuario $objUsuario) {

        $arrayReturn = array();
        $arrayObjUsuario = $this->objetoDAO->logar($objUsuario);
        
        if ($arrayObjUsuario != null) {
            $objUsuario = $this->obterPorId($arrayObjUsuario[0], true);
            $objActionColaborador = $this->_Factory->fabricar("ColaboradorAction");
            $objUsuario->getColaborador()->setControle($objActionColaborador->obterControle($objUsuario->getColaborador()));
            if($objUsuario->getColaborador()->getControle() != null){
                if ($objUsuario->getStatus() == "A" && $objUsuario->getColaborador()->getControle()->getStatus() == "A") {
                    $perfil = $this->obterPerfilUsuario($objUsuario);
                    $objUsuario->setPerfil($perfil[0]); #Apenas 1 Perfil por Usuário para cada Sistema
                    if($objUsuario->getPerfil()!=null){
                        if ($objUsuario->getPerfil()->getStatus() == "A") {
                            $objActionPerfil = $this->_Factory->fabricar("PerfilAction");
                            $objUsuario->setPerfil($objActionPerfil->obterPorId($objUsuario->getPerfil(), true));
                            if ($objUsuario->getPerfil()->getFuncionalidades() != null) {

                                $objUsuario->setAcessos($objUsuario->getAcessos() + 1);
                                $objUsuario->setDataUltimoAcesso(time());
                                $objUsuario->setTryLogon(0);

                                $this->salvar($objUsuario);
                                $objSecurity = $this->_Factory->fabricar("SecurityHelper");
                                $objSecurity->iniciarSessao($objUsuario);
                                return 0;
                            } else {
                                return 5;
                            }
                        } else {
                            return 1;
                        }
                    } else {
                        return 1;
                    }
                } else {
                    return 4;
                }
            } else {
                return 6;
            }
        } else {
            $objActionColaborador = $this->_Factory->fabricar("ColaboradorAction");
            $arrayObjColaborador = $objActionColaborador->
                    listar("num_cpf = '{$objUsuario->getColaborador()->getNumeroCPF()}' AND des_status = 'A'");
            if ($arrayObjColaborador != null) {
                $objUsuario = $this->listar("ide_colaborador = {$arrayObjColaborador[0]->getId()}");
                $objUsuario = $objUsuario[0];
                
                if ($objUsuario->getTryLogon() >= 2) {
                    $objUsuario->setStatus("D");
                }
                
                $objUsuario->setTryLogon($objUsuario->getTryLogon() + 1);
                $this->salvar($objUsuario);

                return 2;
            } else {
                return 3;
            }
        }
    }

    /**
     * Função para gerar senhas aleatórias
     *
     * @param integer $tamanho Tamanho da senha a ser gerada
     * @param boolean $maiusculas Se terá letras maiúsculas
     * @param boolean $numeros Se terá números
     *
     * @return string A senha gerada
     */
    public function gerarSenhaRandomica($tamanho = 8, $maiusculas = true, $numeros = true) {

        return 1234;
        
        $min = 'abcdefghijklmnopqrstuvwxyz';
        $mai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';

        $return = '';
        $caracteres = '';

        $caracteres .= $min;

        if ($maiusculas)
            $caracteres .= $mai;
        if ($numeros)
            $caracteres .= $num;


        $len = strlen($caracteres);

        for ($n = 1; $n <= $tamanho; $n++) {
            $rand = mt_rand(1, $len);
            $return .= $caracteres[$rand - 1];
        }

        return $return;
    }

}

?>
