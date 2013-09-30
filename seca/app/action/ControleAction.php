<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ControleAction extends ModelAction {

    private $_Factory;

    public function ControleAction() {

        if (!isset($_SESSION)) {
            session_start();
        }
        $this->_Factory = Factory::getInstancia();
        parent::__construct($this->_Factory->fabricar("ControleDAO"));
    }

    //RN
    private function validarSalvar($objControle) {
        if($objControle instanceof Controle){
            return true;
        } 
        return false;
    }

    public function salvar($objControle) {

        if ($this->validarSalvar($objControle))
            return parent::salvar($objControle);
    }

    public function obterPorId($value, $lazy = false) {

        if (is_object($value)) {
            if ($value instanceof Controle) {
                if ($value->getId() != null) {
                    $objeto = parent::obterPorId($value, $lazy);
                }
            }
        } else {
            $objeto = $this->_Factory->fabricar("Controle");
            $objeto->setId($value);
            $objeto = parent::obterPorId($objeto, $lazy);
        }

        return $objeto;
    }
}

?>
