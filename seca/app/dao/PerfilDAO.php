<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class PerfilDAO extends ModelDAO {
    
    public function PerfilDAO(){
        parent::__construct();
        $this->objeto = $this->_Factory->fabricar("Perfil");
    }
}
?>
