<?php

class ErroController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->_Template->addCss(PATH_WEBFILES . "css/site.css");
    }

    public function erro404() {
        $this->_Template->addTitle("404");
        $this->viewTemplate('erros/404');
    }

    public function erro500() {
        $this->viewTemplate('erros/500');
    }    

}

?>
