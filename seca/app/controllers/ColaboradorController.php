<?php

class ColaboradorController extends Controller implements ControllerInterface {

    private $_redirect;
    private $filtro = false;
    public function __construct() {
        parent::__construct();
        $this->_Template->addCss(PATH_WEBFILES . "css/site.css");
        $this->_redirect = $this->_Factory->fabricar("RedirectorHelper");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/gerais.js");

        $this->_Template->addTitle(NAME_SIS . " > " . str_replace("Controller", "", $this->_controller));
    }

    public function index() {
        $this->_Template->addJavaScript(WEBFILES . "js/index_Colaborador.js");
        $dados = array();
        $dados['usuario'] = $this->_Security->getUsuario();
        $this->viewTemplate('colaborador/index', $dados);
    }

    /**
     * ColaboradorController::cadastrar
     * @tutorial Preparação da tela do view de Colaborador
     * 
     */
    public function cadastrar() {

        $this->_Template->addJavaScript(WEBFILES . "js/jquery.validate.js");
        $this->_Template->addJavaScript(WEBFILES . "js/util.validate.js");
        $this->_Template->addJavaScript(WEBFILES . "js/mask.js");
        $this->_Template->addJavaScript(WEBFILES . "js/colaborador_cadastro.js");

        $_colaborador = $this->_Security->getUsuario()->getColaborador();
        $objActionTipoColaborador = $this->_Factory->fabricar("TipoColaboradorAction");
       
        $dados['listTipoColaborador'] = $this->_Template->addOptionSelect($objActionTipoColaborador->
                        obterResponsabilidade($_colaborador));

        $objActionMunicipio = $this->_Factory->fabricar('MunicipioAction');
        $dados['listMunicipio'] = $this->_Template->addOptionSelect(
                $objActionMunicipio->listar("","nom_municipio"));
        
        $objActionTipoLogradouro = $this->_Factory->fabricar('TipoLogradouroAction');
        $dados['listTipoLogradouro'] = $this->_Template->addOptionSelect(
                $objActionTipoLogradouro->listar(),"descricao");
                        
        $this->viewTemplate('colaborador/cadastro', $dados);
    }

    public function listar() {

        
        # Fabricar 
        $objColaboradorAction = $this->_Factory->fabricar('ColaboradorAction');
        $objActionMunicipio = $this->_Factory->fabricar('MunicipioAction');
        $objActionControle = $this->_Factory->fabricar('ControleAction');
        
        $_colaborador = $this->_Security->getUsuario()->getColaborador();
        
        
        if(!empty($_POST['filtro'])){
            $dados['filtro'] = $_POST['filtro'];   
        switch ($_colaborador->getControle()->getTipo()) {
            case 1:
                $fil_municipio = ($_POST['ide_municipio'] != "")? "AND ide_municipio={$_POST['ide_municipio']}" : "" ;
                $dados['listColaborador'] = $objColaboradorAction->
                listar("ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema = ".OID_SIS." AND ide_tipo_colaborador in(1,2)){$fil_municipio}","nom_colaborador",true);
                break;
            case 2:
                $fil_municipio = ($_POST['ide_municipio'] != "")? "AND ide_municipio={$_POST['ide_municipio']}" : "" ;
               $dados['listColaborador'] = $objColaboradorAction->
                listar("ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema = ".OID_SIS."AND ide_tipo_colaborador in(3,4)){$fil_municipio}","nom_colaborador",true);
                break;
            case 3:
                $dados['listColaborador'] = $objColaboradorAction->
                listar("ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema = ".OID_SIS." AND ide_colaborador_responsavel = {$_colaborador->getId()})","nom_colaborador",true);
                break;
            case 4:
                $dados['listColaborador'] = $objColaboradorAction->
                listar("ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema = ".OID_SIS." AND ide_colaborador_responsavel = {$_colaborador->getId()})","nom_colaborador",true);
                break;
                
           default:
                $dados['listColaborador'] = array();
                break;
        }
        }else{
            
         $dados['filtro'] = FALSE;
         
           
        }
        switch ($_colaborador->getControle()->getTipo()) {
               
            case 1:
                
                $dados['listMunicipios'] = $this->_Template->addOptionSelect(
                        $objActionMunicipio->listar("ide_municipio IN (SELECT DISTINCT ide_municipio from pessoal.colaborador WHERE ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema = ".OID_SIS." AND ide_tipo_colaborador in(1,2)))")); 
                
                break;
            case 2:
               $dados['listMunicipios'] = $this->_Template->addOptionSelect($objActionMunicipio->listar("ide_municipio IN (SELECT DISTINCT ide_municipio from pessoal.colaborador WHERE ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema = ".OID_SIS." AND ide_tipo_colaborador in(3,4)))")); 
                break;
            case 3:
               $dados['listMunicipios'] = $this->_Template->addOptionSelect($objActionMunicipio->listar("ide_municipio IN (SELECT DISTINCT ide_municipio from pessoal.colaborador WHERE ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema = ".OID_SIS." AND ide_colaborador_responsavel = {$_colaborador->getId()}))")); 
                break;
            case 4:
                $dados['listMunicipios'] = $this->_Template->addOptionSelect($objActionMunicipio->listar("ide_municipio IN (SELECT DISTINCT ide_municipio from pessoal.colaborador WHERE ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema = ".OID_SIS." AND ide_colaborador_responsavel = {$_colaborador->getId()}))")); 
                break;
                
           default:
                $dados['listMunicipios'] = array();
                break;
        }
        
        $this->viewTemplate('colaborador/lista', $dados);
    }

    public function inserir() {
        
        $_colaborador = $this->_Security->getUsuario()->getColaborador();
        
        $objColaboradorAction = $this->_Factory->fabricar("ColaboradorAction");
        $objColaborador = $this->_Factory->fabricar("Colaborador");
        $objUtil = Util::getInstancia();
        $objColaborador = $objUtil->copiarPropridades($_POST, $objColaborador, true);
        $objColaborador->setIdUsuarioCriador($this->_Security->getUsuario()->getId());
        
        #Controle do novo colaborador
        $objControle = $this->_Factory->fabricar("Controle");
        $objControle = $objUtil->copiarPropridades($_POST, $objControle, true);
        $objControle->setStatus("A"); //RN seca, todos os colaboradores são Ativos
        if(!in_array($_POST["ide_tipo_colaborador"], array(1,2))){ #Administrador Geral ou Coordenador Estadual
            $objControle->setResponsavel($_colaborador->getId());
        }        
        
        $objControle->setSistema(OID_SIS);    
        
        $objColaborador->setControle($objControle);
        $objColaborador->setIdUsuarioCriador($this->_Security->getUsuario()->getId());
        $objColaboradorAction->salvar($objColaborador);
        
        $this->_redirect->goToControllerAction("Principal","index");
    }

    public function informar() {

       // $_colaborador = $this->_Security->getUsuario()->getColaborador();
        $dados['toolbar'] = $this->_Template->addToolBar(__METHOD__, array('editar'), $this->getParam('id'));
                
        $this->_Template->addJavaScript(WEBFILES . "js/pagecontrol.js");
        
        $objColaboradorAction = $this->_Factory->fabricar("ColaboradorAction");
        $objControleAction = $this->_Factory->fabricar("ControleAction");
        
        
        $dados['objColaborador'] = $objColaboradorAction->obterPorId($this->getParam('id'), true);
        $dados['listColaborador'] = $objColaboradorAction->listar("ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema = ".OID_SIS." AND ide_colaborador_responsavel = {$dados['objColaborador']->getId()})");
        $listControle= $objControleAction->listar("ide_colaborador={$this->getParam('id')} AND ide_sistema = ".OID_SIS."","",true);
        $dados['objControle'] = $listControle[0];
        $dados['objFormatHelper'] = FormatHelper::getInstancia();
        
        $this->viewTemplate('colaborador/informa', $dados);
    }

    public function editar() {

        $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.validate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/util.validate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/mask.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.populate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/Colaborador_edita.js");

        #Dados do Municipio
        $objActionMunicipio = $this->_Factory->fabricar('MunicipioAction');
        $dados['listMunicipio'] = $this->_Template->addOptionSelect(
                $objActionMunicipio->listar("", "nom_municipio"));
        
        #Dados tipo de logradouro
        $objActionTipoLogradouro = $this->_Factory->fabricar('TipoLogradouroAction');
        $dados['listTipoLogradouro'] = $this->_Template->addOptionSelect(
                $objActionTipoLogradouro->listar(), "descricao");

        #Dados do Colaborador
        $objColaboradorAction = $this->_Factory->fabricar("ColaboradorAction");
        $dados['objColaborador'] = $objColaboradorAction->obterPorId($this->getParam('id'));

        #Complementação de Informações
      $objControleAction = $this->_Factory->fabricar("ControleAction");
        $arrayObjControle = $objControleAction->listar("ide_colaborador = {$dados['objColaborador']->getId()} AND ide_sistema = " . OID_SIS . "");
        $dados['objControle'] = $arrayObjControle[0];

        $_colaborador = $this->_Security->getUsuario()->getColaborador();
        $objActionTipoColaborador = $this->_Factory->fabricar("TipoColaboradorAction");
        $dados['listTipoColaborador'] = $this->_Template->addOptionSelect($objActionTipoColaborador->
                        obterResponsabilidade($_colaborador));
        #Carregando Bairro do Municipio
        $objBairroAction = $this->_Factory->fabricar("BairroAction");
        $dados['listBairro'] = $this->_Template->addOptionSelect(
                $objBairroAction->listar());
        
//        $objActionEmpreendedor = $this->_Factory->fabricar('EmpreendedorAction');
//        $dados['flagStatus'] = ($objActionEmpreendedor->
//                obterQuantidade("ide_Colaborador = {$dados['objColaborador']->getId()}") > 0)? true : false ;
        $controleArray['ide_tipo_colaborador'] = $dados['objControle']->getTipo();       
        $populate = PopulateHelper::getInstancia();
        $dados['json_objeto'] = json_encode($populate->objetoToArray($dados['objColaborador'], true,$controleArray));
        $this->viewTemplate('colaborador/edita', $dados);
    }

    public function atualizar() {

        $objColaboradorAction = $this->_Factory->fabricar("ColaboradorAction");
        $objColaborador = $this->_Factory->fabricar("Colaborador");

        $objColaborador = $objColaboradorAction->obterPorId($_POST['ide_colaborador']);
        $objUtil = $this->_Factory->fabricar("Util");
       
        $objColaborador = $objUtil->copiarPropridades($_POST, $objColaborador, true);
                
        $objColaborador->setIdUsuarioAtualizador($this->_Security->getUsuario()->getId());

        $objControleAction = $this->_Factory->fabricar("ControleAction");
        $objControle = $objControleAction->obterPorId($_POST['ide_controle']);
        ($_POST['des_status'] == "") ? $objControle->setStatus("D") : $objControle->setStatus("A");
        if($objControle->getTipo() != $_POST['ide_tipo_colaborador']) {$objControle->setTipo($_POST['ide_tipo_colaborador']);} 
        $objColaborador->setControle($objControle);
     
        $objColaboradorAction->salvar($objColaborador);
        $this->_redirect->setUrlParameter('id', $_POST['ide_colaborador']);
        $this->_redirect->goToAction('informar');
    }

    //Ajax
    public function verificarCPFUnico() {
        $objColaboradorAction = $this->_Factory->fabricar("ColaboradorAction");
        $objFormatHelper = FormatHelper::getInstancia();
        $total = $objColaboradorAction->obterQuantidade("num_cpf = '{$objFormatHelper->unformatCPF($this->getParam("num_cpf"))}'");
        echo ($total > 0) ? 'false' : 'true';
    }
    
    public function deletar() {
        
    }

    public function excluir() {
        
    }
    
    public function transferir(){
        
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.validate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/Colaborador_transfere.js");
        
        $_Colaborador = $this->_Security->getUsuario()->getColaborador();
        
        $objColaboradorAction = $this->_Factory->fabricar("ColaboradorAction");
        $dados['listColaboradors'] = $this->_Template->addOptionSelect($objColaboradorAction->
                listar("ide_unis = {$_Colaborador->getUnis()} AND ide_tipo_Colaborador = 3", "nom_Colaborador"));
        
        $objActionEmpreendedor = $this->_Factory->fabricar('EmpreendedorAction');
        $dados['listEmpreendedor'] = $this->_Template->addOptionSelect($objActionEmpreendedor->
                        listar("ide_Colaborador = {$_Colaborador->getId()}", "nom_empreendedor"));
                
        $this->viewTemplate('colaborador/transfere', $dados);
        
    }
    #Ajax
    public function listaPorMunicipio(){
        
       $objColaboradorAction = $this->_Factory->fabricar("ColaboradorAction");
       
       $dados['listPipeiros_top'] = $objColaboradorAction->
                listar("ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema = ".OID_SIS." AND ide_tipo_colaborador = 5 ) and ide_municipio = {$_POST['ide_municipio']} ");
       
        $this->viewTemplate('colaborador/listarPorMunicipio', $dados);
    }
    public function resgatar() {

        $objColaboradorAction = $this->_Factory->fabricar("ColaboradorAction");

        if (!$this->isParam('key')) {
            $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.validate.js");
            $this->_Template->addJavaScript(PATH_WEBFILES . "js/util.validate.js");
            $this->_Template->addJavaScript(PATH_WEBFILES . "js/mask.js");
            $this->_Template->addJavaScript(PATH_WEBFILES . "js/colaborador_resgate.js");
            $this->viewTemplate('colaborador/resgate');
        } else {
            $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.validate.js");
            $this->_Template->addJavaScript(PATH_WEBFILES . "js/util.validate.js");
            $this->_Template->addJavaScript(PATH_WEBFILES . "js/colaborador_resgatar.js");
            
            $validar = $this->_Factory->fabricar("ValidaHelper");

            if (!$validar->validaCPF(strrev($this->getParam("key")))) {
                $this->_redirect->goToAction("cadastrar");
            }

            $objColaboradorSystem = $objColaboradorAction->obterColaboradorPorCpf(strrev($this->getParam("key")));

            $objFormatHelper = FormatHelper::getInstancia();
            $objColaborador = $objColaboradorAction->colaboradorRTGIporCpf(strrev($this->getParam("key")));


            if (!$objColaboradorSystem) {
                $this->_redirect->goToAction("cadastrar");
            }

            if ($objColaborador != null) {
                $this->_redirect->goToAction("cadastrar");
            }

            unset($objColaborador);

            $dados['objFormatHelper'] = $objFormatHelper;
            $dados['objColaborador'] = $objColaboradorAction->obterPorId($objColaboradorSystem->getId(), true);

            $objActionTipoColaborador = $this->_Factory->fabricar("TipoColaboradorAction");
            
            $_colaborador = $this->_Security->getUsuario()->getColaborador();
            $dados['listTipoColaborador'] = $this->_Template->addOptionSelect($objActionTipoColaborador->
                            obterResponsabilidade($_colaborador));

            $this->viewTemplate('colaborador/resgate_colaborador', $dados);
        }
    }

    public function recuperar() {

        if (isset($_POST['num_cpf'])) {

            $objFormatHelper = FormatHelper::getInstancia();
            $objColaboradorAction = $this->_Factory->fabricar("ColaboradorAction");
            $objColaborador = $objColaboradorAction->obterColaboradorPorCpf($objFormatHelper->unformatCPF($_POST["num_cpf"]));

            if (!$objColaborador) { // Não existe colaborador na base de dados - cadastrar
                $this->_redirect->goToAction('cadastrar');
            } else {
                $this->_redirect->setUrlParameter("key", strrev($objFormatHelper->unformatCPF($_POST["num_cpf"])));
                $this->_redirect->goToAction('resgatar');
            }
        } elseif (isset($_POST['ide_colaborador'])) {
            
            $_colaborador = $this->_Security->getUsuario()->getColaborador();

            $objUtil = Util::getInstancia();

            $objColaboradorAction = $this->_Factory->fabricar("ColaboradorAction");
            $objColaborador = $this->_Factory->fabricar("Colaborador");
            $objColaborador = $objColaboradorAction->obterPorId($_POST['ide_colaborador'], true);

            $objControle = $this->_Factory->fabricar("Controle");
            $objControle = $objUtil->copiarPropridades($_POST, $objControle, true);
            $objControle->setStatus("A"); //RN seca, todos os colaboradores são Ativos

            if (!in_array($_POST["ide_tipo_colaborador"], array(1, 2))) { #Administrador Geral ou Coordenador Estadual
                $objControle->setResponsavel($_colaborador->getId());
            }

            $objControle->setSistema(OID_SIS);

            $objActionControle = $this->_Factory->fabricar("ControleAction");         
            $objActionControle->salvar($objControle);

            $this->_redirect->goToAction("listar");
                       
        } else {
            $this->_redirect->goToAction("cadastrar");
        }
    }
 # Ajax

    public function colaboradorRtgi() {
        $objColaboradorAction = $this->_Factory->fabricar("ColaboradorAction");
        $objFormatHelper = FormatHelper::getInstancia();
        $objColaborador = $objColaboradorAction->colaboradorRTGIporCpf($objFormatHelper->unformatCPF($this->getParam("num_cpf")));
        echo ($objColaborador == null ) ? 'true' : 'false';
    }
    
    public function filtrar(){
        
    }
    

}

?>
