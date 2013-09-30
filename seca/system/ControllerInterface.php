<?php
/**
 * Contrato de Implementaчуo de Controllers
 * 
 * ControllerInterface
 * @package system
 * @access public
 * 
 */
interface ControllerInterface {

    public function index();

    public function listar();

    public function informar();

    public function cadastrar();

    public function inserir();

    /**
     * sss
     */
    public function editar();

    public function atualizar();
    
    /**
     * ControllerInterface::excluir
     * 
     */
    public function excluir();

    public function deletar();
}

?>