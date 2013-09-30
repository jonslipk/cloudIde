<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class TipoColaboradorAction extends ModelAction {

    private $_Factory;

    public function TipoColaboradorAction() {

        if (!isset($_SESSION)) {
            session_start();
        }
        $this->_Factory = Factory::getInstancia();
        parent::__construct($this->_Factory->fabricar("TipoColaboradorDAO"));
    }

    public function obterPorId($value, $lazy = false) {

        if (is_object($value)) {
            if ($value instanceof TipoColaborador) {
                if ($value->getId() != null) {
                    $objeto = parent::obterPorId($value, $lazy);
                }
            }
        } else {
            $objeto = $this->_Factory->fabricar("TipoColaborador");
            $objeto->setId($value);
            $objeto = parent::obterPorId($objeto, $lazy);
        }

        return $objeto;
    }
    
    public function obterResponsabilidade(Colaborador $objColaborador){
        return $this->objetoDAO->obterResponsabilidade($objColaborador);
    }

}

?>
