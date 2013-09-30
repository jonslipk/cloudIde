<?php

/**
 * @package HELPERS 
 */
class TemplateHelper {

    private $head;
    private $title;
    private $metaTag;
    private $icon;
    private $css;
    private $javaScript;

    #construtor

    public function TemplateHelper() {
        $this->head = array();
        $this->javaScript = array();
        $this->css = array();
        $this->metaTag = array();
        $this->title = null;
        $this->icon = null;
    }

    #atribuir valor ao doctype

    private function doctype() {
        return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
    }

    #inicia a tag html

    private function startHtml() {
        return "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
    }

    #iniar a tag head

    private function startHead() {
        return "<head>";
    }

    #finalizar a tag head

    private function exitHead() {
        return "</head>";
    }

    #adicionar titulo a pagina

    public function addTitle($title) {
        $this->title = "<title>{$title}</title>";
    }

    #adiciona meta tag keywords ao head - no caso de mais de palavra colocar virgula

    public function addMetaKeyWords($keywords) {
        $this->metaTag[] = "<META NAME=\"Keywords\" CONTENT=\"{$keywords}\">";
    }

    #adiciona meta tag description ao head 

    public function addMetaDescription($description) {
        $this->metaTag[] = "<META NAME=\"Description\" CONTENT=\"{$description}\">";
    }

    #adiciona meta tag author ao head 

    public function addMetaAuthor($author) {
        $this->metaTag[] = "<META NAME=\"Author\" CONTENT=\"{$author}\">";
    }

    #adiciona meta tag author ao head - pt-br

    public function addMetaLanguage($language) {
        $this->metaTag[] = "<meta http-equiv=\"content-language\" content=\"{$language}\">";
    }

    public function addOptionSelect(array $lista, $value = 'nome') {
        
        $options = null;      
        $method = "get".ucfirst($value);
        
        if ($lista != null) {
            foreach ($lista as $objeto) {                
                $options.="<option value='{$objeto->getId()}'>{$objeto->$method()}</option>";
            }
        }

        return $options;
    }

    #adiciona meta tag icon

    public function addIcon($urlIcon) {
        $this->icon = "<link rel='shortcut icon' href='" . PATH_IMAGE . "{$urlIcon}' type='image/x-icon' />";
    }

    public function addToolBar($scopo, array $icons = array(), $id="") {
        return ToolBarHelper::getInstancia()->getToolBar($scopo, $icons, $id);
    }

    #adicionar java script

    public function addJavaScript($localizacao, $global = false) {
        # define a prioridade
        $prioridade = ($global == true) ? true : $global;
        if ($prioridade) {
            $js = "<script language=\"javascript\" src=\"{$localizacao}\"></script>";
            array_unshift($this->javaScript, $js);
        } else {
            $this->javaScript[] = "<script language=\"javascript\" src=\"{$localizacao}\"></script>";
        }
    }

    #adicionar css

    public function addCss($localizacao, $global = false) {
        # define a prioridade
        $prioridade = ($global == true) ? true : $global;

        if ($prioridade) {
            $css = "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$localizacao}\" />";
            array_unshift($this->css, $css);
        } else {
            $this->css[] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$localizacao}\" />";
        }
    }

    #adiciona elemento ao head

    public function addElementHead($element) {
        $this->head[] = $element;
    }

    #retorna o head

    private function displayHead() {

        #inicia a tag head
        $head = $this->startHead() . "\n";

        # Define o titulo da pagina
        $title = ($this->title != null) ? $this->title . "\n" : "";
        $head.= $title;

        # Define icon da pagina
        $icon = ($this->icon != null) ? $this->icon . "\n" : "";
        $head.= $icon;

        # Define as Meta tags
        foreach ($this->metaTag as $value) {
            $head.= $value . "\n";
        }

        # Definir os Css
        foreach ($this->css as $value) {
            $head.= $value . "\n";
        }

        # Definir os javaScript
        foreach ($this->javaScript as $value) {
            $head.= $value . "\n";
        }


        #lista os elementos atribuidos ao head
        foreach ($this->head as $value) {
            $head.= $value . "\n";
        }

        #finalizar a tag head
        $head.= $this->exitHead() . "\n";

        return $head;
    }

    # Inicia a tag body

    private function startBody() {
        return "<body>";
    }

    #retorna o header

    private function startMenu() {
        return "<div class='page' id='menu'>
        <div class='nav-bar bg-color-blueDark'>
            <div class='nav-bar-inner padding10'>
                <span class='pull-menu'></span>
                <a href='index.php?Principal'><span class='element brand'>SECA</span></a>
            <span class='divider'></span>
            <ul class='menu'>
            <li data-role='dropdown'>
            <a>Técnico</a>
            <ul class='dropdown-menu'>
            <li><a href='index.php?Colaborador/cadastrar'>Cadastrar</a></li>
            <li><a href='index.php?Colaborador/resgatar'>Resgatar</a></li>
            <li><a href='index.php?Colaborador/listar'>Listar</a></li>
            </ul>
            </li>
            <li data-role='dropdown'>
            <a>Entregas</a>
            <ul class='dropdown-menu'>
            <li><a href='index.php?Entrega/listar'>Lista</a></li>
            <li><a href='index.php?Entrega/cadastrar'>Cadastrar</a></li>
            <li><a href='index.php?Entrega/deletar'>Excluir</a></li>
            <li><a href='index.php?Entrega/localizar'>Localizar</a></li>
            <li class='divider'></li>
            <li><a href='#'>Gerar Remessa</a></li>
            </ul>
            </li>
            
            <li data-role='dropdown'>
            <a>Usuário</a>
            <ul class='dropdown-menu'>
            <li><a href='index.php?Usuario/listar'>Lista</a></li>
            <li><a href='index.php?Usuario/cadastrar'>Cadastrar</a></li>
            <li><a href='index.php?Usuario/gerenciar'>Alterar Senha</a></li>
            </ul>
            </li>
            <li><a href='index.php?Usuario/deslogar'>Sair</a></li>
            </ul>
            </div>
            </div>
        </div>";
    }
    
    public function displayHeader() {

        #doctype
        $header = $this->doctype() . "\n";

        #inicia html
        $header.= $this->startHtml() . "\n";

        #recebe o head
        $header.= $this->displayHead();

        #inicia o body
        $header.= $this->startBody() . "\n";
        
        #inicia o menu --- Item de Sistema
        $header.= $this->startMenu() . "\n";

        return $header;
    }

    public function displayFooter() {
        return "<div class='page' id='rodape'>
        <div class='nav-bar bg-color-blueDark'>
            <div class='nav-bar-inner padding10'>
                <span class='element'>Desenvolvido pela CMO</span>
            </div>
        </div>
    </div>";
    }

}

?>