<?php

class BairroDAO extends ModelDAO {

	public function BairroDAO(){
		parent::__construct();
		$this->objeto = $this->_Factory->fabricar('Bairro');
	}

}

?>