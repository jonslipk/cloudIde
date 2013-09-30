<?php

class IndexController extends Controller{

    private $_redirect;

    public function IndexController() {
        parent::__construct();
        $this->_redirect = $this->_Factory->fabricar("RedirectorHelper");
    }

    public function index() {
         $this->_redirect->goToAction('logon');
    }

    public function logon() {
        $this->_Template->addCss(PATH_WEBFILES."css/theme-dark.css");
        $this->_Template->addTitle("Login");       
        
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.validate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/util.validate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/mask.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/index_logon.js");
                
        $dados['error'] = $this->isParam('error');
        if($this->isParam('error')){
            switch ($this->getParam("error")) {
                case 1:
                    $dados['message'] = "Perfil desativado, contate o administrador";
                    break;
                case 2:
                    $dados['message'] = "Tentativa de login incorreta, seu login sera bloqueado após 3 tentativas";
                    break;
                case 3:
                    $dados['message'] = "Usuário ou Senha incorretos tente novamente";
                    break;
                case 4:
                    $dados['message'] = "Acesso bloqueado ao sistema, contate o administrador";
                    break;
                case 5:
                    $dados['message'] = "Perfil sem privilégios de acesso ao sistema, contate o administrador";
                    break;
                case 6:
                    $dados['message'] = "Controles de Autorização e Autenticação desativados, contate o administrador";
                    break;
                default:
                    $dados['message'] = "É constrangedor, mas, ocorreu uma falha para acessar nosso sistema, contate o administrador";
                    break;
            }            
        }
        
        $this->viewTemplate('index/logon',$dados,'grade');
    }


}

?>
