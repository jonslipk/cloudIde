<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ControleDAO extends ModelDAO {
    
    public function ControleDAO(){
        parent::__construct();
        $this->objeto = $this->_Factory->fabricar("Controle");
    }
    
}
?>
