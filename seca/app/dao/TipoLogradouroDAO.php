<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class TipoLogradouroDAO extends ModelDAO {
    
    public function TipoLogradouroDAO(){
        parent::__construct();
        $this->objeto = $this->_Factory->fabricar("TipoLogradouro");
    }
    
}
?>
