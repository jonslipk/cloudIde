<?php

class FuncionalidadeController extends Controller implements ControllerInterface {

    private $_redirect;

    public function FuncionalidadeController() {
        parent::__construct();
        $this->_Template->addCss(PATH_WEBFILES . "css/site.css");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/gerais.js");
        $this->_redirect = $this->_Factory->fabricar("RedirectorHelper");
    }

    public function index() {
        $dados['usuario'] = $this->_Security->getUsuario();
        $dados['header'] = $this->_Template->displayHeader();
        $dados['footer'] = $this->_Template->displayFooter();

        $objFuncionalidadeAction = $this->_Factory->fabricar("FuncionalidadeAction");
        $dados['funcionalidade'] = $objFuncionalidadeAction->listar();


        $this->viewTemplate('funcionalidade/index', $dados);
    }

    public function listar() {

        $this->_Template->addTitle("Funcionalidades");
        $dados['toolbar'] = $this->_Template->addToolBar(__METHOD__, array('novo'));

        $objFuncionalidadeAction = $this->_Factory->fabricar("FuncionalidadeAction");
        $dados['listFuncionalidades'] = $objFuncionalidadeAction->listar();

        $this->viewTemplate("funcionalidade/lista", $dados);
    }

    public function cadastrar() {

        $this->_Template->addTitle("Nova Funcionalidade");

        $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.validate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/funcionalidade_cadastro.js");

        $objActionPerfil = $this->_Factory->fabricar('PerfilAction');
        $dados['listPerfil'] = $this->_Template->addOptionSelect($objActionPerfil->listar());

        $this->viewTemplate("funcionalidade/cadastro", $dados);
    }

    public function inserir() {
        $objFuncionalidadeAction = $this->_Factory->fabricar("FuncionalidadeAction");
        $objFuncionalidade = $this->_Factory->fabricar("Funcionalidade");
        $obUtil = $this->_Factory->fabricar("Util");
        $objFuncionalidade = $obUtil->copiarPropridades($_POST, $objFuncionalidade, true);
        if ($objFuncionalidade->getStatus() == "")
            $objFuncionalidade->setStatus("D");
        $objFuncionalidade->setIdUsuarioCriador($this->_Security->getUsuario()->getId());
        $objFuncionalidade->setDataCriacao(mktime());
        $objFuncionalidadeAction->salvar($objFuncionalidade);
        $this->_redirect->goToAction('listar');
    }

    public function informar() {
        $dados['toolbar'] = $this->_Template->addToolBar(__METHOD__, array('editar'));
        $objFuncionalidadeAction = $this->_Factory->fabricar("FuncionalidadeAction");
        $objFuncionalidade = $objFuncionalidadeAction->obterPorId($this->getParam('id'), true);
        $dados['objFuncionalidade'] = $objFuncionalidade;
        $this->viewTemplate('funcionalidade/informa', $dados);
    }

    public function editar() {

        $this->_Template->addTitle("Editar Funcionalidade");

        $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.validate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.populate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/funcionalidade_edita.js");

        $objFuncionalidadeAction = $this->_Factory->fabricar("FuncionalidadeAction");
        $objFuncionalidade = $objFuncionalidadeAction->obterPorId($this->getParam('id'), true);

        $dados['perfis'] = $this->_Template->addOptionSelect($objFuncionalidade->getPerfis());
        $dados['objFuncionalidade'] = $objFuncionalidade;

        $populate = PopulateHelper::getInstancia();
        $dados['json_objeto'] = json_encode($populate->objetoToArray($objFuncionalidade, true));

        $objActionPerfil = $this->_Factory->fabricar("PerfilAction");
        $arrayObjperfis = $objActionPerfil->listar();

        $dados['listPerfil'] = $this->_Template->addOptionSelect($populate->
                        obterDiferenca($arrayObjperfis, $objFuncionalidade->getPerfis()));

        $this->viewTemplate('funcionalidade/edita', $dados);
    }

    public function atualizar() {

        $objFuncionalidadeAction = $this->_Factory->fabricar("FuncionalidadeAction");

        $objFuncionalidade = $objFuncionalidadeAction->obterPorId($_POST['ide_funcionalidade']);

        $obUtil = Util::getInstancia();
        
        $objFuncionalidade = $obUtil->copiarPropridades($_POST, $objFuncionalidade, true);
       
        if ($objFuncionalidade->getStatus() == "")
            $objFuncionalidade->setStatus("D");
        
        $objFuncionalidade->setIdUsuarioAtualizador($this->_Security->getUsuario()->getId());
        $objFuncionalidade->setDataAtualizacao(mktime());
        
        $objFuncionalidadeAction->salvar($objFuncionalidade);
        
        $this->_redirect->setUrlParameter('id', $_POST['ide_funcionalidade']);
        $this->_redirect->goToAction('informar');
    }

    public function deletar() {
        
    }

    public function excluir() {
        
    }

}

?>
