<?php
class Controller extends System {

    protected $_Factory;
    protected $_Security;
    protected $_Template;

    public function Controller() {
        parent::__construct();
        $this->_Factory = Factory::getInstancia();
        $this->_Security = SecurityHelper::getInstancia();
        $this->_Template = $this->_Factory->fabricar("TemplateHelper");
    }
    
    private function view($nome, $vars = null) {
        if (is_array($vars) && count($vars) > 0) {
            extract($vars, EXTR_PREFIX_ALL, 'view');
        }
        return require_once ( VIEWS . $nome . '.phtml');
    }

    protected function viewTemplate($nomePage, $dados = null, $nomeTema = null) {
        //inicia o bufferl
        ob_start();
        
        $view_path = PATH_APP . "view/" . $nomePage . '.phtml';

        // Verificar se a view existe
        if(!file_exists($view_path))
            header ("Location: index.php?Erro/erro404");
                
                
        $this->view($nomePage, $dados);
        $dados['conteudo'] = ob_get_clean();
               
        //template configurações padrão
        $this->_Template->addIcon("favicon.ico");
        
        //css style default - Ordem Inversa para Entrar na Pagina
        $this->_Template->addCss(PATH_WEBFILES."css/modern-responsive.css",true); //2 entrar
        $this->_Template->addCss(PATH_WEBFILES."css/modern.css",true); //1 entrar
        
        //javascript style default
        $this->_Template->addJavaScript(PATH_WEBFILES."js/dropdown.js");//5
        $this->_Template->addJavaScript(PATH_WEBFILES."js/moder-ui.js",true);//4        
        //$this->_Template->addJavaScript(PATH_WEBFILES."js/tile-slider.js",true);//3      
        $this->_Template->addJavaScript(PATH_WEBFILES."js/jquery.mousewheel.min.js",true);//2    
        $this->_Template->addJavaScript(PATH_WEBFILES."js/jquery-1.8.2.min.js",true); //1
        
        $dados['header'] = $this->_Template->displayHeader();
        $dados['footer'] = $this->_Template->displayFooter();
        
        if (is_array($dados) && count($dados) > 0) {
            extract($dados, EXTR_PREFIX_ALL, 'view');
        }

        $nomeTema = ($nomeTema != null)? $nomeTema : 'default';
        return require_once ( TEMPLATE . $nomeTema . '.phtml');
    }

}

?>
