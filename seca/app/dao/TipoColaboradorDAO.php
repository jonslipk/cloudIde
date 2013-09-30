<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class TipoColaboradorDAO extends ModelDAO {
    
    public function TipoColaboradorDAO(){
        parent::__construct();
        $this->objeto = $this->_Factory->fabricar("TipoColaborador");
    }
    
    /**
     * TipoColaboradorDAO::obterResponsabilidade
     * @return Array [lista] Objeto TipoColaborador
     * 
     */
    public function obterResponsabilidade($objColaborador){
        
        $this->getConexao();
        
        $sql = "SELECT ide_tipo_colaborador, nom_tipo_colaborador, des_status
                    FROM pessoal.tipo_colaborador 
                    WHERE ide_tipo_colaborador IN 
                        (SELECT ide_tipo_colaborador FROM pessoal.responsabilidade WHERE ide_tipo_responsavel = {$objColaborador->getControle()->getTipo()} AND ide_sistema = ".OID_SIS.")";

        $rs = $this->conn->Execute($sql);
        $this->logError($rs, __METHOD__, $sql);

        $util = Util::getInstancia();
        $objeto = $this->_Factory->fabricar('TipoColaborador');
        
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
