<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class EntregaAction extends ModelAction {
    
    private $_Factory;

    public function EntregaAction() {
        
        if (!isset($_SESSION)){
            session_start();
        }
        $this->_Factory = Factory::getInstancia();
        parent::__construct($this->_Factory->fabricar("EntregaDAO"));

    }

    //RN
    private function validarSalvar($objEntrega){
        if($objEntrega instanceof Entrega){
            return true;
        } 
        return false;
    }

    public function salvar($objEntrega) {
        
        if($this->validarSalvar($objEntrega))
            return parent::salvar($objEntrega);

    }    
    
     public function obterPorId($value, $lazy = false){

        if(is_object($value)){
            if($value instanceof Entrega){
                if($value->getId() != null){
                    $objeto = parent::obterPorId($value, $lazy);
                }
            }
        } else {
            $objeto = $this->_Factory->fabricar("Entrega");
            $objeto->setId($value);
            $objeto = parent::obterPorId($objeto, $lazy);
        }

        return $objeto;
    }
    
}
?>
