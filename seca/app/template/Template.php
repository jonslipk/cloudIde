<?php
    
    
//    if(!defined('PROJECT_DIR'))
//        define('PROJECT_DIR', 'rtgi');
//
//    if(!defined('APPLICATION_DIR'))
//        define('APPLICATION_DIR', 'app/VIEW');
//
//    if(!defined('REQUEST_URI'))
//        define('REQUEST_URI',str_replace('/'.PROJECT_DIR,'',$_SERVER['REQUEST_URI']));
//
//    if(!defined('PATH_ABSOLUTO_VIEW'))
//        define('PATH_ABSOLUTO_VIEW', 'http://'.$_SERVER['HTTP_HOST'].'/'.PROJECT_DIR.'/'.APPLICATION_DIR.'/');
//
//    if(!defined('PATH_ABSOLUTO'))
//        define('PATH_ABSOLUTO', 'http://'.$_SERVER['HTTP_HOST'].'/'.PROJECT_DIR.'/app/');
//
//    if(!defined('APPLICATION_TITLE'))
//        define('APPLICATION_TITLE', 'NO NAME');

    //include_once getenv('DOCUMENT_ROOT').'/'.PROJECT_DIR.'/app/action/TLoad.php';

    class Template {

        private $security;
        private $modulo;
        
        /*
         * Instancia do Objeto Template para esta classe.
         */
        private static $instancia = null;

        /*
         * Template::getInstancia
         *
         * @package template/
         * @param Void
         * @return Objeto Template
         * @tutorial: Verifica se existe uma instancia do Objeto Template, caso contrario
         * da um new e retorna o objeto
         */
       
	public static function getInstancia($modulo = ""){
            if(self::$instancia == null){
                self::$instancia = new Template($modulo = "");
            }
            return self::$instancia;
        }
		
        /*
         * Template::__clone
         *
         * @package template/
         * @param Void
         * @return Null
         * @tutorial: Impede que este objeto seja clonado
         * @exception: Clone não é permitido.
         */
        public function __clone() {
            trigger_error('Clone não é permitido.', E_USER_ERROR);
        }

        public function Template($modulo = ""){
            
            $this->security = Seguranca::getInstancia();
            $this->modulo = $modulo;

            /*Verificação de Segurança para usuarios logados no sistema.
             */
            if(!$this->security->verificarLogon($this->security->getUsuario())){
                 header( 'Location: ../index.php');
                 exit();
            }
        }

        public function getSecurity(){
            return $this->security;
        }

        public function getToolsNav(){
            $objToolsNav = Tools::getInstancia($this->security,$this->modulo);
            $objToolsNav->getToolsNav(PATH_ABSOLUTO_VIEW);
        }

//        public function getToolsBar(array $array = null){
//            $objToolsNav = Tools::getInstancia($this->security,$this->user,$this->modulo);
//            $objToolsNav->getToolsBar($array);
//        }
        
        
        public function getDOC(){
            echo '<!DOCTYPE html>';
        }
        
        protected function getMeta(){

            $objMeta = new Elemento('meta');
            $objMeta->setProperty('http-equiv', 'content-type');
            $objMeta->content = 'text/html; charset=ISO-8859-1';
            $objMeta->show();

        }

        public function alertMessage($id,$message){

            $objAlert = new Elemento('div');
            $objAlert->id = $id;
            $objAlert->class = 'alertMessage';
            $objAlert->add('&nbsp;'.$message);

            $objAlert->show();
        
        }

        protected function getTitle($path = ''){

            if($path!='')
                $path = '/'.$path;

            $objTitle = new Elemento('title');
            $objTitle->add(APPLICATION_TITLE.$path);
            $objTitle->show();

        }

        protected function getLinks(array $arrayLink = null){

            $objLinkFav = new Elemento('link');
            $objLinkFav->rel = 'shortcut icon';
            $objLinkFav->href = PATH_ABSOLUTO_VIEW.'img/favicon.ico';
            $objLinkFav->type = 'image/x-icon';        
            $objLinkFav->show();

            $objLink = new Elemento('link');
            if($arrayLink!=null){
                foreach ($arrayLink as $key => $value) {
                    $objLink->href = PATH_ABSOLUTO_VIEW.'css/'.$key.'.css';
                    $objLink->rel = 'stylesheet';
                    $objLink->type = 'text/css';
                    $objLink->media = $value;
                    $objLink->show();
                }
            }
        }

        protected function getScript(array $arrayScript = null){

            //Script Padrão
            $objScriptJQ = new Elemento('script');
            $objScriptJQ->type = 'text/javascript';
            $objScriptJQ->src = PATH_ABSOLUTO_VIEW.'js/lib/jquery.min.js';
            $objScriptJQ->show();
			
			//Script populate
            $objScriptJQ = new Elemento('script');
            $objScriptJQ->type = 'text/javascript';
            $objScriptJQ->src = PATH_ABSOLUTO_VIEW.'js/lib/populate.js';
            $objScriptJQ->show();
			
			//Script gerais
            $objScriptJQ = new Elemento('script');
            $objScriptJQ->type = 'text/javascript';
            $objScriptJQ->src = PATH_ABSOLUTO_VIEW.'js/lib/gerais.js';
            $objScriptJQ->show();
			
			

            $objScript = new Elemento('script');
            if($arrayScript!=null){
                foreach ($arrayScript as $value) {
                    $objScript->type = 'text/javascript';
                    $objScript->src = PATH_ABSOLUTO_VIEW.'js/'.$value.'.js';
                    $objScript->show();
                }
            }
        }

        public function clearSession(array $array){
            
            foreach($array as $k){
                session_unregister($k);
            }

        }

        public function getHead($path = '', array $arrayLink = null, array $arrayScript = null){
            
            $this->getMeta();
            $this->getTitle($path);
            $this->getLinks($arrayLink);
            $this->getScript($arrayScript);
            
        }
		
        public function getTopo(){

            $objTopo = new Elemento("div");
            $objTopo->id = "topo";
            
            $objLogo = new Elemento("div");
            $objLogo->id = "logo_vm";

            $objImgLogo = new Elemento("img");
            $objImgLogo->src = PATH_ABSOLUTO_VIEW."img/logo_vm.png";
            $objImgLogo->border = '0';
            $objImgLogo->class = 'logo_topo';
            $objLogo->add($objImgLogo);
            $objTopo->add($objLogo);

            $objH2 = new Elemento("h2");
            $objH2->add("Sistema Administrativo ".APPLICATION_TITLE);
            $objTopo->add($objH2);

            $objTools = Tools::getInstancia($this->security,$this->modulo);
            $objTopo->add($objTools->getToolsUsuario(PATH_ABSOLUTO_VIEW));
            $objTopo->add($objTools->getToolsBar());

            $objTopo->show();
			
        }

       
        public function getFooter(){
            echo '<br style=\'clear: both;\' />
                    <div id=\'footer\'>
                   		<hr/>
						Contato: (71) 3325-2525 ou vidamelhor@gmail.com
                    </div>';
        }
        
    }

?>