<?php

class BairroController extends Controller implements ControllerInterface {

    private $_redirect;

    public function BairroController() {
        parent::__construct();
        $this->_redirect = $this->_Factory->fabricar('RedirectorHelper');
        $this->_Template->addCss(PATH_WEBFILES . "css/site.css");
    }

    public function index() {
        $this->_redirect->goToAction("listar");
    }

    public function listar() {
        $objMunicipioAction = $this->_Factory->fabricar("BairroAction");
        $dados["listBairro"] = $objMunicipioAction->listar();
        $this->viewTemplate('bairro/lista', $dados);
    }

    public function cadastrar() {

        $this->_Template->addTitle("SIPIA - Cadastro de bairros");

        $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.validate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/bairro_cadastro.js");

        $objMunicipioAction = $this->_Factory->fabricar('MunicipioAction');
        $dados['listMunicipio'] = $this->_Template->addOptionSelect($objMunicipioAction->listar());

        $this->viewTemplate("bairro/cadastro", $dados);
    }

    public function inserir() {

        $objBairroAction = $this->_Factory->fabricar("BairroAction");
        $objBairro = $this->_Factory->fabricar("Bairro");
        $obUtil = $this->_Factory->fabricar("Util");

        $objBairro = $obUtil->copiarPropridades($_POST, $objBairro);

        if ($objBairro->getStatus() == "") {
            $objBairro->setStatus("D");
        }

        $objBairroAction->salvar($objBairro);

        $this->_redirect->goToAction("listar");
    }

    public function obterBairrosPorMunicipio() {

        $id = $this->getParam("id");
        $id1 = $this->getParam("id");
        $id2 = $this->getParam("id");

        # Lista de Bairro
        $objBairroAction = $this->_Factory->fabricar('BairroAction');
        $ArrayObjBairro = $objBairroAction->listar("ide_municipio = '{$id}'");

        if ($ArrayObjBairro != null) {
            foreach ($ArrayObjBairro as $objBairro) {
                $arrayJson[] = array('id' => $objBairro->getId(), 'nome' => utf8_encode($objBairro->getNome()));
            }
        } else {
            $arrayJson = false;
        }
        
            
        echo json_encode($arrayJson);
        
    }

    public function atualizar() {
        
    }

    public function deletar() {
        
    }

    public function editar() {
        
    }

    public function excluir() {
        
    }

    public function informar() {
        
    }
    
    /**
     * BairroController::listarBairroPorMunicipio
     */
    public function listarBairroPorMunicipio() {
        $objBairroAction = $this->_Factory->fabricar("BairroAction");
        $arrayObjBairro = $objBairroAction->listar("ide_municipio = '{$this->getParam("id")}'","nom_bairro");
        if ($arrayObjBairro != null) {
            foreach ($arrayObjBairro as $objBairro) {
                $arrayJson[] = array('id' => $objBairro->getId(), 'nome' => utf8_encode($objBairro->getNome()));
            }
        } else {
            $arrayJson = false;
        }
        echo json_encode($arrayJson);
    }
    
}

?>