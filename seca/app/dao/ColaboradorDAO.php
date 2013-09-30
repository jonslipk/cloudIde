<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ColaboradorDAO extends ModelDAO {
    
    public function ColaboradorDAO(){
        parent::__construct();
        $this->objeto = $this->_Factory->fabricar("Colaborador");
    }

}
?>
