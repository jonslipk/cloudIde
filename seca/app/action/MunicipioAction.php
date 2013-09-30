<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class MunicipioAction extends ModelAction {

    private $_Factory;

    public function MunicipioAction() {

        if (!isset($_SESSION)) {
            session_start();
        }
        $this->_Factory = Factory::getInstancia();
        parent::__construct($this->_Factory->fabricar("MunicipioDAO"));
    }
    
    public function obterPorId($value, $lazy = false) {

        if (is_object($value)) {
            if ($value instanceof Municipio) {
                if ($value->getId() != null) {
                    $objeto = parent::obterPorId($value, $lazy);
                }
            }
        } else {
            $objeto = $this->_Factory->fabricar("Municipio");
            $objeto->setId($value);
            $objeto = parent::obterPorId($objeto, $lazy);
        }

        return $objeto;
    }
    
}

?>
