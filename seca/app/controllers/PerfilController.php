<?php

class PerfilController extends Controller implements ControllerInterface {

    private $_redirect;

    public function PerfilController() {
        parent::__construct();
        $this->_Template->addCss(PATH_WEBFILES . "css/site.css");
        $this->_redirect = $this->_Factory->fabricar("RedirectorHelper");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/gerais.js");
        $this->_Template->addTitle(NAME_SIS ." >". str_replace("Controller", "", $this->_controller));
    }

    public function index() {
        $dados['usuario'] = $this->_Security->getUsuario();
        $dados['header'] = $this->_Template->displayHeader();
        $dados['footer'] = $this->_Template->displayFooter();

        $objPerfilAction = $this->_Factory->fabricar("PerfilAction");
        $dados['perfil'] = $objPerfilAction->listar("", "", true);


        $this->viewTemplate('perfil/index', $dados);
    }

    public function listar() {
        $dados['toolbar'] = $this->_Template->addToolBar(__METHOD__,array('novo'));
        $this->_Template->addTitle("Perfis");
        $objActionUsuario = $this->_Factory->fabricar("PerfilAction");
        $dados['listPerfil'] = $objActionUsuario->listar();
        $this->viewTemplate('perfil/lista', $dados);
    }

    public function cadastrar() {

        $this->_Template->addTitle("Novo Perfil");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.validate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/perfil_cadastro.js");
        
        $objActionFuncionalidade = $this->_Factory->fabricar('FuncionalidadeAction');
        $dados['listFuncionalidade'] = $this->_Template->addOptionSelect($objActionFuncionalidade->listar());       
        
        $this->viewTemplate('perfil/cadastro',$dados);
    }

    public function inserir() {

        $objPerfilAction = $this->_Factory->fabricar("PerfilAction");
        $objPerfil = $this->_Factory->fabricar("Perfil");
        $objUtil = $this->_Factory->fabricar("Util");
        $objPerfil = $objUtil->copiarPropridades($_POST, $objPerfil, true);

        if ($objPerfil->getStatus() == "")
            $objPerfil->setStatus("D");

        $objPerfil->setIdUsuarioCriador($this->_Security->getUsuario()->getId());
        $objPerfil->setDataCriacao(mktime());

        $objPerfilAction->salvar($objPerfil);


        $this->_redirect->goToAction('listar');
    }
    
    public function informar() {

        $this->_Template->addTitle("Dados do Perfil");
        $dados['toolbar'] = $this->_Template->addToolBar(__METHOD__,array('editar'));
        $this->_Template->addJavaScript(WEBFILES . "js/pagecontrol.js");
        
        $objActionPerfil = $this->_Factory->fabricar("PerfilAction");
        
        $dados['perfil'] = $objActionPerfil->obterPorId($this->getParam("id"),true);
        $objActionUsuario = $this->_Factory->fabricar("UsuarioAction");
        $dados['listUsuario'] = $objActionUsuario->listar("ide_perfil = {$dados['perfil']->getId()}","",true);
        
        $dados['total_funcionalidades'] = count($dados['perfil']->getFuncionalidades());
        $dados['total_usuarios'] = count($dados['listUsuario']);
        
        $dados['perfil_status'] = ($dados['perfil']->getStatus() == "A")? "Ativo" : "Desativado";
        
        $this->viewTemplate('perfil/informa', $dados);
    }
    

    public function editar() {
        
        $this->_Template->addTitle("Editar Perfil");
        
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.validate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.populate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/perfil_edita.js");
        
        $objActionPerfil = $this->_Factory->fabricar("PerfilAction");
        $objPerfil = $objActionPerfil->obterPorId($this->getParam('id'), true);
        //$dados['objActionPerfil'] = $objActionPerfil;        
        
        $populate = PopulateHelper::getInstancia();
        $dados['json_objeto'] = json_encode($populate->objetoToArray($objPerfil,true));
        
        $objActionFuncionalidade = $this->_Factory->fabricar("FuncionalidadeAction");
        $arrayObjFuncionalidade = $objActionFuncionalidade->listar();
        
        $dados['listFuncionalidade'] = $this->_Template->addOptionSelect($populate->
                obterDiferenca($arrayObjFuncionalidade, $objPerfil->getFuncionalidades()));
        
        $dados['perfil_funcionalidades'] = $this->_Template->addOptionSelect($objPerfil->getFuncionalidades());
        
        $this->viewTemplate('perfil/edita', $dados);
    }

    public function atualizar() {
        
        $objPerfilAction = $this->_Factory->fabricar("PerfilAction");
        $objPerfil = $objPerfilAction->obterPorId($_POST['ide_perfil']);
        $obUtil = Util::getInstancia();
        $objPerfil = $obUtil->copiarPropridades($_POST, $objPerfil, true);
        
        if ($objPerfil->getStatus() == "")
            $objPerfil->setStatus("D");
        
        $objPerfil->setIdUsuarioAtualizador($this->_Security->getUsuario()->getId());
        
        $objPerfilAction->salvar($objPerfil);
        $this->_redirect->goToAction('listar');
        
    }     
public function deletar() {
        
    }
public function excluir() {
        
    }

}

?>
