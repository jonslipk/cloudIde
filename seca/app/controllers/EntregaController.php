<?php

class EntregaController extends Controller implements ControllerInterface {

    private $_redirect;
    public function EntregaController() {
        parent::__construct();
        $this->_Template->addCss(PATH_WEBFILES . "css/site.css");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/gerais.js");
        $this->_redirect = $this->_Factory->fabricar("RedirectorHelper");
        
    }

    public function index() {
        
    }


    public function atualizar() {
        
    }

    public function cadastrar() {
        $this->_Template->addJavaScript(WEBFILES . "js/jquery.validate.js");
        $this->_Template->addJavaScript(WEBFILES . "js/util.validate.js");
        $this->_Template->addJavaScript(WEBFILES . "js/mask.js");
        
        $objActionMunicipio = $this->_Factory->fabricar('MunicipioAction');
        $dados['listMunicipio'] = $this->_Template->addOptionSelect(
                $objActionMunicipio->listar("","nom_municipio"));
        
        $objActionColaborador = $this->_Factory->fabricar('ColaboradorAction');
        $dados['listColaborador'] = $this->_Template->addOptionSelect(
                $objActionColaborador->
                listar("ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_tipo_colaborador = 5 AND ide_sistema = ".OID_SIS.")"));
        
        
        $this->viewTemplate('entrega/cadastro', $dados);
    }

    public function deletar() {
     $objEntregaAction = $this->_Factory->fabricar("EntregaAction");   
     $entregas = $objEntregaAction->listar("","",true);
    //echo "<pre>";
     foreach ($entregas as $objEntrega) {
         $arrayColaboradores[] = $objEntrega->getColaborador();
         
     }
      
     //var_dump($arrayColaboradores);
    
     $dados['objColaborador'] = $arrayColaboradores;
             
             
     $this->viewTemplate('entrega/deleta',$dados);
    }

    public function editar() {
        
        
    }

    public function excluir() {
        
    }

    public function informar() {
        $objEntregaAction = $this->_Factory->fabricar("EntregaAction");
        $dados['objEntrega'] = $objEntregaAction->obterPorId($this->getParam("id"));
        $objActionColaborador = $this->_Factory->fabricar('ColaboradorAction');
        $dados['objColaborador'] = $objActionColaborador->obterporId($dados["objEntrega"]->getColaborador());
        $objActionMunicipio = $this->_Factory->fabricar('MunicipioAction');
        $dados['objMunicipio'] = $objActionMunicipio->obterPorId($dados["objEntrega"]->getMunicipio()); 
//        echo "<pre>";
//        var_dump($dados['objEntrega']);
        $this->viewTemplate('entrega/informa', $dados);
    }

    public function inserir() {
        $objEntregaAction = $this->_Factory->fabricar("EntregaAction");
        $objEntrega = $this->_Factory->fabricar("Entrega");
        $objUtil = Util::getInstancia();
        $objEntrega = $objUtil->copiarPropridades($_POST, $objEntrega, true);
      
        $objEntrega->setIdUsuarioCriador($this->_Security->getUsuario()->getId());
        $objEntrega->setDataCriacao(mktime());
        $objEntregaAction->salvar($objEntrega);
        
        $this->_redirect->goToAction('listar');
    }

    public function listar() {
        $objEntregaAction = $this->_Factory->fabricar("EntregaAction");
        $dados['objColaborador'] = $this->_Security->getUsuario()->getColaborador();
       
       
     
        switch ($dados['objColaborador']->getControle()->getTipo()) {
            case 1:
            case 2:
                $dados['listEntrega'] = $objEntregaAction->listar("","",true);
               
                break;
            case 3:
                $dados['listEntrega'] = $objEntregaAction->listar("ide_municipio = {$dados['objColaborador']->getMunicipio()} ");
               
                break;
            case 4:
               $dados['listEntrega'] = $objEntregaAction->listar("ide_municipio = {$dados['objColaborador']->getMunicipio()} ");
                break;
            default:
                break;
        }
        $this->viewTemplate('entrega/lista', $dados);
    }

}

?>
