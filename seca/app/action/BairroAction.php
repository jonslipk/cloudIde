<?php

class BairroAction extends ModelAction {

    private $_Factory;

    public function BairroAction() {

        if (!isset($_SESSION)) {
            session_start();
        }

        $this->_Factory = Factory::getInstancia();
        parent::__construct($this->_Factory->fabricar('BairroDAO'));
    }

    //RN
    private function validarSalvar($objBairro){
        if($objBairro instanceof Bairro){
            return true;
        } 
        return false;
    }

    public function salvar($objBairro) {
        
        if($this->validarSalvar($objBairro))
            return parent::salvar($objBairro);
        
    }

    public function obterPorId($value, $lazy = false) {

        if (is_object($value)) {
            if ($value instanceof Bairro) {
                if ($value->getId() != null) {
                    $objeto = parent::obterPorId($value, $lazy);
                }
            }
        } else {
            $objeto = $this->_Factory->fabricar("Bairro");
            $objeto->setId($value);
            $objeto = parent::obterPorId($objeto, $lazy);
        }

        return $objeto;
    }    
    
}

?>