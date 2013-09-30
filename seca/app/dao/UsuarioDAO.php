<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class UsuarioDAO extends ModelDAO {
    
    public function UsuarioDAO(){
        parent::__construct();
        $this->objeto = $this->_Factory->fabricar("Usuario");
    }
    
    public function obterPerfilUsuario($objUsuario){
        
        $this->getConexao();
        
        $sql = "SELECT p.* FROM seguranca.perfil p
                WHERE ide_perfil IN (
                    SELECT ide_perfil FROM seguranca.usuario__perfil WHERE ide_usuario = {$objUsuario->getId()}) 
                AND ide_sistema = ".OID_SIS."";

        $rs = $this->conn->Execute($sql);
        $this->logError($rs, __METHOD__, $sql);

        $util = Util::getInstancia();
        $objeto = $this->_Factory->fabricar('Perfil');
        
        $lista = array();
        if ($rs != null) {
            foreach ($rs as $array) {
                $lista[] = $util->copiarPropridades($array, $objeto);
            }
        }
        
        return $lista;
        
    }
    
    public function salvarPerfilUsuario(Usuario $objUsuario){
        
        $this->getConexao();
        $sql = "DELETE FROM seguranca.usuario__perfil WHERE ide_usuario = {$objUsuario->getId()} AND ide_perfil = {$objUsuario->getPerfil()}";
        $rs = $this->conn->Execute($sql);
        
        $stmt = "INSERT INTO seguranca.usuario__perfil(ide_usuario, ide_perfil) VALUES (?, ?);";
        $rs = $this->conn->Execute($stmt, array($objUsuario->getId(),$objUsuario->getPerfil()));
        
        if($rs){
            $arrayResult[] = true;
            $arrayResult[] = $objeto;
        } else {
            $arrayResult[] = false;
            $arrayResult[] = $objeto;
        }
        
        $this->conn->close();            
        return $arrayResult;
        
    }
    
    public function logar(Usuario $objUsuario){
        
        $this->getConexao();
        
        $sql = "SELECT u.* FROM seguranca.usuario u
                INNER JOIN pessoal.colaborador c ON u.ide_colaborador = c.ide_colaborador
                WHERE des_senha = '{$objUsuario->getSenha()}' AND c.num_cpf = '{$objUsuario->getColaborador()->getNumeroCPF()}' AND u.des_status = 'A'";

        $rs = $this->conn->Execute($sql);
        $this->logError($rs, __METHOD__, $sql);

        $util = Util::getInstancia();
        $objeto = $this->_Factory->fabricar('Usuario');
        
        $lista = array();
        if ($rs != null) {
            foreach ($rs as $array) {
                $lista[] = $util->copiarPropridades($array, $objeto);
            }
        }
        
        return $lista;
        
    }
}
?>
