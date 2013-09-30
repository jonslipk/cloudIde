<?php
    /**
     * @abstract
     * @package /DAO
     * @version 1.0
     */
    abstract class ModelDAO {

        protected $conn;
        protected $instance;
        protected $objeto;
        protected $_Factory;

        public function ModelDAO(){
            $this->_Factory = Factory::getInstancia();
        }

        protected function getConexao(){
            $this->instance = ConexaoPadrao::getInstancia();
            $this->conn = $this->instance->adodbConexao();
            if($this->conn->databaseType == "mssql"){
                $this->conn->SetFetchMode(ADODB_FETCH_ASSOC);
            }
        }

        public function listar($where, $order){

            $this->getConexao();
            
            $lista = null;
            if($this->objeto != null){
                $array = $this->conn->MetaColumnNames($this->objeto->getTabela());

                if($array != null){
                    $listCol = implode(",", $array);

                    if($where != ""){$where = " WHERE ".$where." ";}
                    if($order != ""){$order = " ORDER BY ".$order." ";}

                    $sql = "SELECT {$listCol} FROM {$this->objeto->getTabela()}{$where}{$order}";
                    $rs = $this->conn->Execute($sql);

                    $this->logError($rs,'listar',$sql);

                    $util = $this->_Factory->fabricar('Util');

                    $lista = array();
                    if($rs != null){
                        foreach ($rs as $array) {
                            $lista[] = $util->copiarPropridades($array, $this->objeto);
                        }
                    } 
                }
            }
            return $lista;
            
        }
        
        public function collection($base, $objeto, $objeto_temp, $alterId){
            
            $this->getConexao();
            
            $priK = array_search('id',$objeto->getColMap());
            $segK = ($alterId != "")? $alterId : array_search('id',$objeto_temp->getColMap());
            
            $lista = null;
            $sql = "SELECT {$priK},{$segK} FROM {$this->objeto->getSchema()}.{$base} WHERE "
                   .array_search('id',$objeto->getColMap())." = {$objeto->getId()}";
            $rs = $this->conn->Execute($sql);
            
            $this->logError($rs,__METHOD__,$sql);

            if($rs != null){
                foreach ($rs as $array) {
                    $lista[] = $array[$segK];
                }
            }
            
            return $lista;
        }
        
        public function innerJoin($entity, $arrayObjeto, $where){
                        
        }
        
        /*
         * Usuario::inserir
         */
        public function inserir($objeto){
           
            $this->getConexao();
            $arrayResult = null;

            $array = $this->conn->MetaColumnNames($objeto->getTabela());
            $stmtValue = str_repeat(", ? ", count($objeto->getPersistence()) - 1);

            foreach ($objeto->getPersistence() as $persistence=>$tipo){
                 $coluns .= ", ".array_search($persistence, $objeto->getColMap());
            }
            
            // ----- VERIFICAÇÃO DE SEGURANÇA ------
            if(array_search("ide_usuario_criador", $array)){
                $flagUC = true;
                $coluns .= ", ide_usuario_criador";
                $stmtValue .= ", ?";
            }
            
            if(array_search("dat_criacao", $array)) {
                $flagDC = true;
                $coluns .= ", dat_criacao";
                $stmtValue .= ", ?";            
            }
            // ----- VERIFICAÇÃO DE SEGURANÇA ------

            $coluns = substr_replace($coluns,"",0,2);
            $stmt = $this->conn->Prepare("INSERT INTO {$objeto->getTabela()} ({$coluns}) VALUES (? {$stmtValue})");
            $util = $this->_Factory->fabricar('Util');
            $arrayValue = $util->obterPropriedades($objeto->getPersistence(), $objeto);
            
            if($flagUC){
                $arrayValue[0][] = $objeto->getIdUsuarioCriador();
            }
            if($flagDC){
                $arrayValue[0][] = time();
            }

            $rs = $this->conn->Execute($stmt, $arrayValue[0]);
            $this->logError($rs,__METHOD__,$stmt);
          
            if($rs){
                $arrayResult[] = true;
                if($this->conn->databaseType == "mssql"){
                    $IdCurrent = $this->conn->Execute("SELECT @@IDENTITY as lastval");
                } else if(strripos($this->conn->databaseType,"postgres") === 0) {
                    $IdCurrent = $this->conn->Execute("SELECT LASTVAL()");
                }

                foreach ($IdCurrent as $i){
                    $objeto->setId($i['lastval']);
                }
                
                if($objeto->getCollections() != null){
                    foreach($objeto->getCollections() as $key=>$collection){
                         $base = (strpos($collection,':'))? 
                                    substr($collection, 0, strpos($collection,':')) : $collection;
                         $stmt = $this->conn->Prepare("INSERT INTO {$this->objeto->getSchema()}.". $base
                                ." (".array_search('id',$objeto->getColMap()).", ".$arrayValue["alterId:".$collection].") VALUES (?,?)");
                        foreach($arrayValue[$collection] as $dado){
                            $ok = $this->conn->Execute($stmt, array($objeto->getId(), $dado));
                            $this->logError($ok,'inserirCollection',$stmt);
                        }
                    }
                }                
                
                $arrayResult[] = $objeto;
            } else {
                $arrayResult[] = false;
                $arrayResult[] = $objeto;
            }

            $this->conn->close();            
            return $arrayResult;
          }

        public function atualizar($objeto){
            $flag = false;
            $coluns = "";
            $this->getConexao();

            $array = $this->conn->MetaColumnNames($objeto->getTabela());
            foreach ($objeto->getPersistence() as $persistence=>$tipo){
                $coluns .= array_search($persistence, $objeto->getColMap())."=?, ";
            }
            $coluns = substr_replace($coluns,"",strlen($coluns)-2,strlen($coluns)-1);

            if(array_search("ide_usuario_atualizador", $array)){
                $flag = true;
                $coluns .= ", ide_usuario_atualizador=?, dat_atualizacao=?";
            }
            
            $id = array_search("id",$objeto->getColMap());

            $stmt = $this->conn->Prepare("UPDATE {$objeto->getTabela()} SET {$coluns} WHERE {$id} = ? ");
           
            $util = $this->_Factory->fabricar('Util');
            $arrayValue = $util->obterPropriedades($objeto->getPersistence(), $objeto);
            
            if($flag){
                $arrayValue[0][] = $objeto->getIdUsuarioAtualizador();
                $arrayValue[0][] = time();
            }
            
            $arrayValue[0][] = $objeto->getId();
            
            $rs = $this->conn->Execute($stmt, $arrayValue[0]);
            $this->logError($rs,__METHOD__,$stmt);
            
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

        public function deletar($objeto){

            $this->getConexao();

            $id = array_search("id",$objeto->getColMap());
            
            $stmt = $this->conn->Prepare("DELETE FROM {$objeto->getTabela()} WHERE {$id} = ?");
            $rs = $this->conn->Execute($stmt, array($objeto->getId()));
            $this->logError($rs,__METHOD__,$stmt);

            $this->conn->close();

            return $rs;
        }

        public function obterQuantidade($where, $groupBy){
            
            $this->getConexao();
            
            $total = 0;
            
            if($where != ""){$where = " WHERE ".$where." ";}
            if($groupBy != ""){$campos = $groupBy.", ";$groupBy = " GROUP BY {$groupBy}";}

            $sql = "SELECT {$campos}COUNT(*) AS total FROM {$this->objeto->getTabela()}{$where}{$groupBy}"; 
            
            $rs = $this->conn->Execute($sql);
            $this->logError($rs,__METHOD__,$sql);

            $this->conn->close();

            if($rs != null){
                $arrayKeys = explode(",",$campos);
                unset($arrayKeys[array_search(" ", $arrayKeys)]);
                $i = 0;
                foreach ($rs as $total) {
                    foreach($arrayKeys as $key){
                        $arrayTotal[$i][trim($key)] = $total[trim($key)];
                    }
                    $arrayTotal[$i]["total"] = $total['total'];
                    $i++;
                }
            } else {
                throw new Exception("Falha ao Executar a Query, Consulte o logError.");
            }
            
            if(count($arrayTotal) == 1 && $groupBy == ""){
                $total = $arrayTotal[0]['total'];
            } else {
                $total = $arrayTotal;
            }
            
            return $total;

        }

        public function obterSoma($coluns, $colunSoma, $where){

            $array = null;

            $this->getConexao();

            if($where != ""){$where = " WHERE ".$where." ";}

            if($coluns != ""){
                $group = " GROUP BY ".$coluns." ";
                $s = ",";
            }

            $sql = "SELECT {$coluns}{$s} sum({$colunSoma}) AS total FROM {$this->objeto->getTabela()}{$where}{$group}";
            
            $rs = $this->conn->Execute($sql);
            $this->logError($rs,__METHOD__,$sql);
            
            $this->conn->close();

            if($rs != null){
                foreach ($rs as $y) {
                    $arrayTemp = null;
                    foreach($y as $k=>$x){
                        if(!is_integer($k)){
                            $arrayTemp[$k] = $x;
                        }
                    }
                    $array[] = $arrayTemp;
                }
            }
            return $array;
        }

        protected function logError($rs, $acao, $sql){
            if (!$rs) {
                $fp = fopen("config/logError.txt", "a");
                fwrite($fp, "(".date("d-m-Y H:i:s").") C/A: {$_SERVER['QUERY_STRING']} EM: {$acao} {$this->objeto->getTabela()} ");
                fwrite($fp, $this->conn->ErrorMsg()." #SQL#: [");
                fwrite($fp, $sql."]\n\n");
                fclose($fp);
            }
        }

    }
?>