<?php

/**
 * UsuarioController
 * @access public
 * @version 
 * 
 */
class UsuarioController extends Controller implements ControllerInterface {

    private $_redirect;

    public function UsuarioController() {
        parent::__construct();
        $this->_redirect = $this->_Factory->fabricar("RedirectorHelper");
        $this->_Template->addCss(PATH_WEBFILES . "css/site.css");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/gerais.js");

        $this->_Template->addTitle(NAME_SIS . " >" . str_replace("Controller", "", $this->_controller));
    }

    /*
     * UsuarioController::logar
     * 
     */

    public function logar() {
        
        $objUsuario = $this->_Factory->fabricar("Usuario");
        $objColaborador = $this->_Factory->fabricar("Colaborador");
        $objFormatHelper = FormatHelper::getInstancia();

        $objUsuario->setSenha(md5(addslashes(trim($_POST['senha']))));
        $objColaborador->setNumeroCPF($objFormatHelper->unformatCPF($_POST['num_cpf']));
        $objUsuario->setColaborador($objColaborador);

        $objActionUsuario = $this->_Factory->fabricar("UsuarioAction");
        $flag = $objActionUsuario->logar($objUsuario);
        if ($flag === 0) {
            $this->_redirect->goToController("Principal");
        } else {
            $this->_redirect->setUrlParameter("error", $flag);
            $this->_redirect->goToControllerAction("Index", 'logon');
        }
    }

    /**
     * UsuarioController::deslogar
     */
    public function deslogar() {
        $this->_Security->destruirSessao();
        $this->_redirect->goToIndex();
    }

    public function cadastrar() {

        $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.validate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/usuario_cadastro.js");

        $objActionColaborador = $this->_Factory->fabricar('ColaboradorAction');

        $_colaborador = $this->_Security->getUsuario()->getColaborador();
        switch ($_colaborador->getControle()->getTipo()) {
            case 1:
                $dados['listColaborador'] = $this->_Template->addOptionSelect($objActionColaborador->
                                listar("ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema = " . OID_SIS . " AND ide_tipo_colaborador IN (
                SELECT ide_tipo_colaborador FROM pessoal.responsabilidade WHERE ide_tipo_responsavel = {$_colaborador->getControle()->getTipo()}))"));
                break;
            case 2:
            case 3:
            case 4:
                $dados['listColaborador'] = $this->_Template->addOptionSelect($objActionColaborador->
                                listar("ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema =  " . OID_SIS . " AND ide_colaborador_responsavel = {$_colaborador->getId()} )", "", true));
                break;
            default:
                break;
        }
         $dados['listColaborador'] = $this->_Template->addOptionSelect(
                $objActionColaborador->listarColaboradoresSemPerfil("nom_colaborador")
        );


        $objPerfil = $this->_Factory->fabricar("PerfilAction");
        #Perfil Listado de Acordo com o tipo de colaborador
        $dados['listPerfil'] = $objPerfil->
                listar("des_status = 'A' AND ide_sistema = " . OID_SIS . " AND ide_perfil IN (SELECT ide_perfil FROM seguranca.autoridade WHERE ide_tipo_colaborador = {$_colaborador->getControle()->getTipo()})");

        $this->viewTemplate('usuario/cadastro', $dados);
    }

    public function gerenciar() {

        $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.validate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/usuario_gerencia.js");

        $dataHelper = $this->_Factory->fabricar('DataHelper');
        $dados['ultimo_log'] = $dataHelper->timeStampParaData($this->_Security->getUsuario()->getDataUltimoAcesso(), 1);

        $this->viewTemplate('usuario/gerencia', $dados);
    }

    public function alterarSenha() {

        $objActionUsuario = $this->_Factory->fabricar("UsuarioAction");
        $objUsuario = $objActionUsuario->obterPorId($this->_Security->getUsuario()->getId());

        $objUsuario->setSenha(md5($_POST['des_senha_new']));
        $objUsuario->setIdUsuarioAtualizador($this->_Security->getUsuario()->getId());
        $objUsuario->setDataAtualizacao(mktime());

        $objActionUsuario->salvar($objUsuario);

        $this->_redirect->goToAction('deslogar');
    }

    /**
     * UsuarioController::inserir
     * 
     */
    public function inserir() {

        $objUsuarioAction = $this->_Factory->fabricar("UsuarioAction");
        $objUsuario = $this->_Factory->fabricar("Usuario");
        $objUtil = $this->_Factory->fabricar("Util");
        $objUsuario = $objUtil->copiarPropridades($_POST, $objUsuario, true);
        #perfil será encaminhando para usuario__perfil
        $objUsuario->setPerfil($_POST["ide_perfil"]);
        #Convertendo a senha para md5
        $objUsuario->setSenha(md5($objUsuario->getSenha()));

        if ($objUsuario->getStatus() == "")
            $objUsuario->setStatus("D");

        $objUsuario->setIdUsuarioCriador($this->_Security->getUsuario()->getId());
        $arrayReturn = $objUsuarioAction->salvar($objUsuario);

        if ($arrayReturn[0])
            $objUsuarioAction->salvarPerfilUsuario($objUsuario);

        $this->_redirect->goToAction('listar');
    }

    public function atualizar() {

        $objUsuarioAction = $this->_Factory->fabricar("UsuarioAction");
        $objUsuario = $this->_Factory->fabricar("Usuario");
        $objUsuario = $objUsuarioAction->obterPorId($_POST['ide_usuario']);

        $objUtil = $this->_Factory->fabricar("Util");
        $objUsuario = $objUtil->copiarPropridades($_POST, $objUsuario, true);

        if ($objUsuario->getStatus() == "")
            $objUsuario->setStatus("D");

        $objUsuario->setIdUsuarioAtualizador($this->_Security->getUsuario()->getId());
        $objUsuarioAction->salvar($objUsuario);

        $this->_redirect->setUrlParameter('id', $_POST['ide_usuario']);
        $this->_redirect->goToAction('informar');
    }

    public function informar() {

        $this->_Template->addTitle("Informações Usuário");

        $dados['toolbar'] = $this->_Template->addToolBar(__METHOD__, array('editar'));

        $objUsuarioAction = $this->_Factory->fabricar("UsuarioAction");
        $objUsuario = $objUsuarioAction->obterPorId($this->getParam('id'), true);
        $dados['objUsuario'] = $objUsuario;
        $this->viewTemplate('usuario/informa', $dados);
    }

    public function listar() {

        $objActionUsuario = $this->_Factory->fabricar("UsuarioAction");
        $_colaborador = $this->_Security->getUsuario()->getColaborador();
        switch ($_colaborador->getControle()->getTipo()) {
            case 1:
               $dados['listUsuario'] = $objActionUsuario->listar("ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema =  " . OID_SIS . " AND ide_tipo_colaborador in(1,2))", "", true);
                break;
            case 2:
                $dados['listUsuario'] = $objActionUsuario->
                listar("ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema =  " . OID_SIS . " AND ide_colaborador_responsavel = {$this->_Security->getUsuario()->getColaborador()->getId()} )", "", true);
                break;
            case 3:
                  $dados['listUsuario'] = $objActionUsuario->
                listar("ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema =  " . OID_SIS . " AND ide_colaborador_responsavel = {$this->_Security->getUsuario()->getColaborador()->getId()} )", "", true);
                break;
            case 4:
                $dados['listUsuario'] = $objActionUsuario->
                listar("ide_colaborador IN (SELECT ide_colaborador FROM pessoal.controle WHERE ide_sistema =  " . OID_SIS . " AND ide_colaborador_responsavel = {$this->_Security->getUsuario()->getColaborador()->getId()} )", "", true);
                break;
            default:
                break;
        }
        
        $this->viewTemplate('usuario/lista', $dados);
    }

    public function editar() {

        $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.validate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/jquery.populate.js");
        $this->_Template->addJavaScript(PATH_WEBFILES . "js/usuario_edita.js");

        $objActionUsuario = $this->_Factory->fabricar("UsuarioAction");
        $objUsuario = $objActionUsuario->obterPorId($this->getParam('id'));
        $dados['objUsuario'] = $objUsuario;

        $objPerfil = $this->_Factory->fabricar("PerfilAction");
        $dados['listPerfil'] = $objPerfil->listar("des_status = 'A'");

        $populate = PopulateHelper::getInstancia();
        $dados['json_objeto'] = json_encode($populate->objetoToArray($objUsuario));

        $this->viewTemplate('usuario/edita', $dados);
    }

    #Ajax

    public function verificarUsuarioUnico() {
        $objUsuarioAction = $this->_Factory->fabricar("UsuarioAction");
        $total = $objUsuarioAction->obterQuantidade("ide_colaborador = '{$this->getParam("ide_colaborador")}' 
                                AND ide_usuario IN (
                                    select ide_usuario from seguranca.usuario__perfil 
                                    where ide_perfil IN (select ide_perfil from seguranca.perfil where ide_sistema = " . OID_SIS . "))");
        echo ($total > 0) ? 'false' : 'true';
    }

    //Ajax
    public function obterUsuario() {
        $objActionUsuario = $this->_Factory->fabricar("UsuarioAction");
        $objUsuario = $objActionUsuario->obterPorId($_POST['id']);

        $array = $this->object_to_array($objUsuario);
        echo json_encode($array);
    }

    //Ajax
    public function isLogin() {
        $objActionUsuario = $this->_Factory->fabricar("UsuarioAction");
        $total = $objActionUsuario->obterQuantidade("log_usuario = '{$this->getParam('log_usuario')}'");
        echo ($total > 0) ? 'false' : 'true';
    }

    //Ajax
    public function isUsuario() {
        $objActionUsuario = $this->_Factory->fabricar("UsuarioAction");
        $total = $objActionUsuario->obterQuantidade("ide_tecnico = '{$this->getParam('ide_tecnico')}'");
        echo ($total > 0) ? 'false' : 'true';
    }

    //Ajax
    public function isPassword() {
        $objActionUsuario = $this->_Factory->fabricar("UsuarioAction");
        $senhaCriptografada = md5($this->getParam('des_senha'));
        $total = $objActionUsuario->obterQuantidade("ide_usuario = {$this->_Security->getUsuario()->getId()}
            AND des_senha = '{$senhaCriptografada}'");
        echo ($total > 0) ? 'true' : 'false';
    }

    //Essa merda sai daqui
    function object_to_array($mixed) {
        if (is_object($mixed))
            $mixed = (array) $mixed;
        if (is_array($mixed)) {
            $new = array();
            foreach ($mixed as $key => $val) {
                $key = preg_replace("/^\\0(.*)\\0/", "", $key);
                $new[$key] = $this->object_to_array($val);
            }
        }
        else
            $new = $mixed;
        return $new;
    }

    public function deletar() {
        
    }

    public function excluir() {
        
    }

    public function index() {
        
    }

}

?>
