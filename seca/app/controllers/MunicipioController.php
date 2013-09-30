<?php

class MunicipioController extends Controller implements ControllerInterface {

    public function __construct() {
        parent::__construct();
        $this->_Template->addCss(PATH_WEBFILES . "css/site.css");

        $this->_Template->addTitle(NAME_SIS . " > " . str_replace("Controller", "", $this->_controller));
    }

    public function index() {
        $this->_redirect->goToAction('listar');
    }

    public function listar() {
        # Fabricar MunicipioAction
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/municipio_lista.js");

        $objMunicipioAction = $this->_Factory->fabricar('MunicipioAction');
        $dados['listMunicipio'] = $objMunicipioAction->listar("1=1 LIMIT 30 OFFSET 1");
        $this->viewTemplate('municipio/lista', $dados);
    }

    public function informar() {
        $objMunicipioAction = $this->_Factory->fabricar("MunicipioAction");
        $objMunicipio = $objMunicipioAction->obterPorId($this->getParam("id"));
        $dados['objMunicipio'] = $objMunicipio;
        $this->viewTemplate('municipio/informa', $dados);
    }

    //Ajax
    public function obterMunicipios() {
        $objMunicipioAction = $this->_Factory->fabricar("MunicipioAction");
        $arrayObjMunicipio = $objMunicipioAction->listar();
        if ($arrayObjMunicipio != null) {
            foreach ($arrayObjMunicipio as $objMunicipio) {
                $arrayJson[] = array('id' => $objMunicipio->getId(), 'nome' => utf8_encode($objMunicipio->getNome()));
            }
        } else {
            $arrayJson = false;
        }
        echo json_encode($arrayJson);
    }

    //Ajax
    public function paginar() {
        $objMunicipioAction = $this->_Factory->fabricar("MunicipioAction");
        $arrayObjMunicipio = $objMunicipioAction->listar("1=1 LIMIT 30 OFFSET " . (($this->getParam('page') - 1) * 30 + 1) . "");
        if ($arrayObjMunicipio != null) {
            foreach ($arrayObjMunicipio as $objMunicipio) {
                $arrayJson[] = array('id' => $objMunicipio->getId(), 'nome' => utf8_encode($objMunicipio->getNome()));
            }
        } else {
            $arrayJson = false;
        }
        echo json_encode($arrayJson);
    }
    
    //AJAX
    public function obterMunicipiosCapacitacao() {
        
        $objMunicipioAction = $this->_Factory->fabricar("MunicipioAction");
        $objActionCapacitacao = $this->_Factory->fabricar('CapacitacaoAction');
        $objCapacitacao = $objActionCapacitacao->obterPorId($this->getParam('id'),true);
        $stringMunicipios = "";
        $index = 0;
        foreach ($objCapacitacao->getMunicipios() as $objMunicipios) {
            $arrayObjMunicipios[] = $objMunicipios->getId();
            if($index == 0){
                 $stringMunicipios.= $arrayObjMunicipios[$index];
            }else{
                $stringMunicipios.=",".$arrayObjMunicipios[$index];
               
            }
            $index++;
        } 
                 
       $arrayObjMunicipio = $objMunicipioAction->listar("ide_municipio not in({$stringMunicipios})");
        if ($arrayObjMunicipio != null) {
            foreach ($arrayObjMunicipio as $objMunicipio) {
                $arrayJson[] = array('id' => $objMunicipio->getId(), 'nome' => utf8_encode($objMunicipio->getNome()));
            }
        } else {
            $arrayJson = false;
        }
        echo json_encode($arrayJson);
    }

    public function atualizar() {
        
    }

    public function cadastrar() {
        
    }

    public function deletar() {
        
    }

    public function editar() {
        
    }

    public function excluir() {
        
    }

    public function inserir() {
        
    }

    /**
     * MunicipioController::obterMunicipiosPorLocalidade
     * @access public
     * @since Versão 1.335
     * 
     */
    public function obterMunicipiosPorLocalidade() {

        if ($this->getParam("id") > 2900000) { //Municipios da Bahia
            $objMunicipioAction = $this->_Factory->fabricar("MunicipioAction");
            $arrayObjMunicipio = $objMunicipioAction->listar("ide_municipio = '{$this->getParam("id")}'");
            if ($arrayObjMunicipio != null) {
                foreach ($arrayObjMunicipio as $objMunicipio) {
                    $arrayJson[] = array('id' => $objMunicipio->getId(), 'nome' => utf8_encode($objMunicipio->getNome()));
                }
            } else {
                $arrayJson = false;
            }
        } else {
            $objTerritorioAction = $this->_Factory->fabricar("TerritorioAction");
            $objTerritorio = $objTerritorioAction->obterPorId($this->getParam("id"), true);
            if ($objTerritorio != null) {
                foreach ($objTerritorio->getMunicipios() as $objMunicipio) {
                    $arrayJson[] = array('id' => $objMunicipio->getId(), 'nome' => utf8_encode($objMunicipio->getNome()));
                }
            } else {
                $arrayJson = false;
            }
        }
        echo json_encode($arrayJson);
    }
}

?>
