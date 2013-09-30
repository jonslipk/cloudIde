<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class EntregaDAO extends ModelDAO {
    
    public function EntregaDAO(){
        parent::__construct();
        $this->objeto = $this->_Factory->fabricar("Entrega");
    }
}
?>
