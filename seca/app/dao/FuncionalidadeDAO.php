<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class FuncionalidadeDAO extends ModelDAO {
    
    public function FuncionalidadeDAO(){
        parent::__construct();
        $this->objeto = $this->_Factory->fabricar("Funcionalidade");
    }
}
?>
