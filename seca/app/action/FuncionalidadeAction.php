<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class FuncionalidadeAction extends ModelAction {
    
    private $_Factory;

    public function FuncionalidadeAction() {
        
        if (!isset($_SESSION)){
            session_start();
        }
        $this->_Factory = Factory::getInstancia();
        parent::__construct($this->_Factory->fabricar("FuncionalidadeDAO"));

    }
    
    //RN
    private function validarSalvar($objFuncionalidade){
        if($objFuncionalidade instanceof Funcionalidade){
            return true;
        } 
        return false;
    }

    public function salvar($objFuncionalidade) {
        
        if($this->validarSalvar($objFuncionalidade))
            return parent::salvar($objFuncionalidade);
        
    }
    
    
    public function obterPorId($value, $lazy = false) {

        if (is_object($value)) {
            if ($value instanceof Funcionalidade) {
                if ($value->getId() != null) {
                    $objeto = parent::obterPorId($value, $lazy);
                }
            }
        } else {
            $objeto = $this->_Factory->fabricar("Funcionalidade");
            $objeto->setId($value);
            $objeto = parent::obterPorId($objeto, $lazy);
        }

        return $objeto;
    }
    
}
?>
