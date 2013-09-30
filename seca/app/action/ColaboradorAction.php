<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ColaboradorAction extends ModelAction {

    private $_Factory;
     private $_Security;

    public function ColaboradorAction() {

        if (!isset($_SESSION)) {
            session_start();
        }
        $this->_Factory = Factory::getInstancia();
        $this->_Security = SecurityHelper::getInstancia();
        parent::__construct($this->_Factory->fabricar("ColaboradorDAO"));
    }

    //RN
    private function validarSalvar($objColaborador) {
        return true;
    }

    public function salvar($objColaborador) {

        if ($this->validarSalvar($objColaborador))
            $arrayReturn = parent::salvar($objColaborador);
        
        #Salvando o controle do Colaborador para esse sistema
        if($arrayReturn[0]){
            $objActionControle = $this->_Factory->fabricar("ControleAction");
            $objColaborador->getControle()->setColaborador($arrayReturn[1]->getId());
            $objActionControle->salvar($objColaborador->getControle());            
            return $arrayReturn;
        }
        
        return $arrayReturn;
            
        
    }

    public function obterPorId($value, $lazy = false) {

        if (is_object($value)) {
            if ($value instanceof Colaborador) {
                if ($value->getId() != null) {
                    $objeto = parent::obterPorId($value, $lazy);
                }
            }
        } else {
            $objeto = $this->_Factory->fabricar("Colaborador");
            $objeto->setId($value);
            $objeto = parent::obterPorId($objeto, $lazy);
        }

        return $objeto;
    }
    
    /**
     * ColaboradorAction::obterControle
     * @return Objeto Controle
     */
    public function obterControle(Colaborador $objColaborador){
        
        $objActionControle = $this->_Factory->fabricar("ControleAction");
        $arrayControle = $objActionControle->listar("ide_colaborador = {$objColaborador->getId()} AND ide_sistema = ".OID_SIS."");
        return $arrayControle[0];
        
    }
     /**
     * ColaboradorAction::colaboradorRTGIporCpf
     * @return bollean
     */
    public function colaboradorRTGIporCpf($cpf) {

        $colaborador = $this->listar("num_cpf = '{$cpf}'");

        if ($colaborador[0] != null) {
            if (!$this->obterControle($colaborador[0])) {
                return false;
            } else {
                return $colaborador[0];
            }
        } else {
            return false;
        }
    }

    /**
     * ColaboradorAction::obterColaboradorPorCpf
     * @return Objeto Colaborador
     */
    public function obterColaboradorPorCpf($cpf) {

        $colaborador = $this->listar("num_cpf = '{$cpf}'");

        if ($colaborador[0] != null) {
            return $colaborador[0];
        } else {
            return false;
        }
    }
    
    /**
     * Lista de colaboradores que não possue perfil de usuario no systema
     * @param type $order
     * @param type $lazy
     * @param type $limit
     * @param type $offset
     * @return type 
     */
    public function listarColaboradoresSemPerfil($order = "", $lazy = false, $limit = null, $offset = null) {

        $_colaborador = $this->_Security->getUsuario()->getColaborador();
        ($_colaborador->getControle()->getTipo() == 3) ? $responsavel = "AND ide_colaborador_responsavel = {$_colaborador->getId()}" : $responsavel = "";                             
        $where = "ide_colaborador IN (
                        SELECT controle.ide_colaborador
                        FROM pessoal.controle, pessoal.colaborador
                        WHERE controle.ide_colaborador = colaborador.ide_colaborador 
                            AND controle.ide_sistema = '" . OID_SIS . "'
                            AND controle.ide_tipo_colaborador IN (
                                SELECT DISTINCT ide_tipo_colaborador 
                                FROM pessoal.responsabilidade 
                                WHERE ide_tipo_responsavel  = {$_colaborador->getControle()->getTipo()}                       
                            ) {$responsavel}  
                            AND controle.ide_colaborador NOT IN(
                                SELECT ide_colaborador FROM seguranca.usuario u
                                INNER JOIN seguranca.usuario__perfil up ON u.ide_usuario = up.ide_usuario
                                INNER JOIN seguranca.perfil p ON p.ide_perfil = up.ide_perfil
                                WHERE p.ide_sistema = '" . OID_SIS . "'	
					AND ide_colaborador NOT IN (
						SELECT ide_colaborador 
                                                                FROM seguranca.usuario 
                                                                WHERE ide_usuario NOT IN(
                                                                        SELECT ide_usuario 
                                                                        FROM seguranca.usuario__perfil 
                                                                        WHERE ide_perfil IN (
                                                                                SELECT ide_perfil 
                                                                                FROM seguranca.perfil
                                                                                WHERE ide_sistema = '" . OID_SIS . "'
                                                                        ))
					)
                            )
                    )";

        return $this->listar($where, $order, $lazy, $limit, $offset);
    }

    /**
     * Lista de colaboradores que possue perfil de usuario no systema
     * @param type $order
     * @param type $lazy
     * @param type $limit
     * @param type $offset
     * @return type 
     */
    public function listarColaboradoresComPerfil($order = "", $lazy = false, $limit = null, $offset = null) {

        $_colaborador = $this->_Security->getUsuario()->getColaborador();
        ($_colaborador->getControle()->getTipo() == 3) ? $responsavel = "AND ide_colaborador_responsavel = {$_colaborador->getId()}" : $responsavel = ""; 
        
        $where = "ide_colaborador IN (
                                    SELECT controle.ide_colaborador
                                    FROM pessoal.controle, pessoal.colaborador
                                    WHERE controle.ide_colaborador = colaborador.ide_colaborador 
                                        AND controle.ide_sistema = '" . OID_SIS . "'
                                        AND controle.ide_tipo_colaborador IN (
                                            SELECT ide_tipo_colaborador 
                                            FROM pessoal.responsabilidade 
                                            WHERE ide_tipo_responsavel  = {$_colaborador->getControle()->getTipo()}                        
                                        ) {$responsavel}
                                        AND controle.ide_colaborador IN(
                                            SELECT ide_colaborador FROM seguranca.usuario u
                                            INNER JOIN seguranca.usuario__perfil up ON u.ide_usuario = up.ide_usuario
                                            INNER JOIN seguranca.perfil p ON p.ide_perfil = up.ide_perfil
                                            WHERE p.ide_sistema = '" . OID_SIS . "'
                                        )AND controle.ide_colaborador NOT IN (
                                                                SELECT ide_colaborador 
                                                                FROM seguranca.usuario 
                                                                WHERE ide_usuario NOT IN(
                                                                        SELECT ide_usuario 
                                                                        FROM seguranca.usuario__perfil 
                                                                        WHERE ide_perfil IN (
                                                                                SELECT ide_perfil 
                                                                                FROM seguranca.perfil
                                                                                WHERE ide_sistema = '" . OID_SIS . "'
                                                                        )))
                                )";

        return $this->listar($where, $order, $lazy, $limit, $offset);
    }

}


?>
