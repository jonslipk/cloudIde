<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class MunicipioDAO extends ModelDAO {

    public function MunicipioDAO() {
        parent::__construct();
        $this->objeto = $this->_Factory->fabricar("Municipio");
    }

}

?>
