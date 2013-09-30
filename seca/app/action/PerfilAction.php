<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class PerfilAction extends ModelAction {
    
    private $_Factory;

    public function PerfilAction() {
        
        if (!isset($_SESSION)){
            session_start();
        }
        $this->_Factory = Factory::getInstancia();
        parent::__construct($this->_Factory->fabricar("PerfilDAO"));

    }

    //RN
    private function validarSalvar($objPerfil){
        if($objPerfil instanceof Perfil){
            return true;
        } 
        return false;
    }

    public function salvar($objPerfil) {
        
        if($this->validarSalvar($objPerfil))
            return parent::salvar($objPerfil);

    }    
    
     public function obterPorId($value, $lazy = false){

        if(is_object($value)){
            if($value instanceof Perfil){
                if($value->getId() != null){
                    $objeto = parent::obterPorId($value, $lazy);
                }
            }
        } else {
            $objeto = $this->_Factory->fabricar("Perfil");
            $objeto->setId($value);
            $objeto = parent::obterPorId($objeto, $lazy);
        }

        return $objeto;
    }
    
}
?>
