<?php

class PrincipalController extends Controller {

    public function PrincipalController() {
        parent::__construct();
        $this->_Template->addCss(PATH_WEBFILES . "css/site.css");
        //$this->_Template->addCss(PATH_WEBFILES."css/theme-dark.css");
        $this->_Template->addTitle(NAME_SIS . " > " . str_replace("Controller", "", $this->_controller));
    }

    public function index() {
        
        $dados['objColaborador'] = $this->_Security->getUsuario()->getColaborador();
        $objActionColaborador = $this->_Factory->fabricar('ColaboradorAction');
        $objActionMunicipio = $this->_Factory->fabricar('MunicipioAction');
     
        switch ($dados['objColaborador']->getControle()->getTipo()) {
            case 1:
            case 2:
                $dados['listMunicipios'] = $this->_Template->addOptionSelect($objActionMunicipio->listar("ide_municipio IN (SELECT DISTINCT ide_municipio from pessoal.colaborador WHERE ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema = ".OID_SIS." AND ide_tipo_colaborador = 5))")); 
               
                break;
            case 3:
                $dados['listPipeiros_top'] = $objActionColaborador->
                listar("ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema = ".OID_SIS." AND ide_colaborador_responsavel = {$dados['objColaborador']->getId()})");
                $dados['nameH2']= "Coordenadores Municípais";
                break;
            case 4:
                $dados['listPipeiros_top'] = $objActionColaborador->
                listar("ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema = ".OID_SIS." AND ide_colaborador_responsavel = {$dados['objColaborador']->getId()})");
                $dados['nameH2']= "Pipeiros";
                break;
            default:
                break;
        }
        //$dados['listMunicipios'] = $this->_Template->addOptionSelect($objActionMunicipio->listar()); 
        $this->viewTemplate('principal/index', $dados);
    }

    public function localizar() {

        $objProjetoAction = $this->_Factory->fabricar('ProjetoAction');
        $listProjeto = $objProjetoAction->listar("ativo = 1");
        $dados['listProjeto'] = $this->_Template->addOptionSelect($listProjeto, "Descricao");

        $this->viewTemplate('principal/localiza', $dados);
    }

}

?>
