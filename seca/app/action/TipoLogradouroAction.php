<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class TipoLogradouroAction extends ModelAction {

    private $_Factory;

    public function TipoLogradouroAction() {

        if (!isset($_SESSION)) {
            session_start();
        }
        $this->_Factory = Factory::getInstancia();
        parent::__construct($this->_Factory->fabricar("TipoLogradouroDAO"));
    }

    public function obterPorId($value, $lazy = false) {

        if (is_object($value)) {
            if ($value instanceof TipoLogradouro) {
                if ($value->getId() != null) {
                    $objeto = parent::obterPorId($value, $lazy);
                }
            }
        } else {
            $objeto = $this->_Factory->fabricar("TipoLogradouro");
            $objeto->setId($value);
            $objeto = parent::obterPorId($objeto, $lazy);
        }

        return $objeto;
    }


}

?>
