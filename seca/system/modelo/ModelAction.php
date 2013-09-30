<?php

/**
 * ModelAction
 * @abstract
 * @package Modelo
 * @access public
 * 
 */
abstract class ModelAction {

    protected $objetoDAO;
    private $_Factory;
    protected $_util;

    public function ModelAction($objetoDAO) {
        $this->_Factory = Factory::getInstancia();
        $this->objetoDAO = $objetoDAO;
        $this->_util = $this->_Factory->fabricar('Util');
    }

    protected function obterPorId($objeto, $lazy) {
        $lista = $this->listar(array_search("id", $objeto->getColMap()) . " = '{$objeto->getId()}'", "", $lazy);
        if ($lista != null) {
            $objeto = $lista[0];
        }
        return $objeto;
    }

    public function listar($where = "", $order = "", $lazy = false, $limit = null, $offset = null) {

        $lista = $this->objetoDAO->listar($where, $order, $lazy);
        if ($lazy) {
            if ($lista != null) {
                foreach ($lista as $object) {
                    //Collections
                    if ($object->getCollections() != null) {
                        foreach ($object->getCollections() as $key => $collection) {
                            $base = (strpos($collection, ':')) ?
                                    substr($collection, 0, strpos($collection, ':')) : $collection;
                            $alterId = (strpos($collection, ':')) ?
                                    substr($collection, strpos($collection, ':') + 1, strlen($collection)) : "";
                            $objeto_temp = $this->_Factory->
                                    fabricar(substr($key, 0, strpos($key, ':')));
                            $lista_collection = $this->objetoDAO->
                                    collection($base, $object, $objeto_temp, $alterId);
                            if ($lista_collection != null) {
                                $this->objetoDAO = $this->_Factory->
                                        fabricar(substr($key, 0, strpos($key, ':')) . "DAO");
                                foreach ($lista_collection as $id_objeto) {
                                    $objeto = $this->_Factory->fabricar(substr($key, 0, strpos($key, ':')));
                                    $objeto->setId($id_objeto);
                                    $action = $this->_Factory->fabricar(substr($key, 0, strpos($key, ':')) . "Action");
                                    $objeto = $action->obterPorId($objeto, false);
                                    $reflection = new ReflectionMethod($object, "set" . ucfirst(substr($key, strpos($key, ':') + 1, strlen($key))));
                                    $reflection->invoke($object, $objeto);
                                }
                            }
                        }
                    }

                    //Relations
                    if ($object->getRelations() != null) {
                        foreach ($object->getRelations() as $key => $relation) {
                            $objAction = $this->_Factory->fabricar($relation . "Action");
                            $reflection = new ReflectionMethod($object, "get" . ucfirst($key));
                            $objeto = $objAction->obterPorId($reflection->invoke($object));
                            $reflection = new ReflectionMethod($object, "set" . ucfirst($key));
                            $reflection->invoke($object, $objeto);
                        }
                    }
                }
            }
        }

        return $lista;
    }

    public function innerJoin(array $entity, $where = "") {
        echo "Não Implementado";
    }

    public function obterQuantidade($where = "", $groupBy = "") {

        $total = null;
        $total = $this->objetoDAO->obterQuantidade($where,$groupBy);
        return $total;
    }
        
    private function validarAtualizar($objeto) {

        if ($objeto->getId() == null || $objeto->getId() == "" || $objeto->getId() == 0) {
            return false;
        }

        return true;
    }

    protected function salvar($objeto) {

        if ($this->validarAtualizar($objeto)) {
            $flag = $this->objetoDAO->atualizar($objeto);
        } else {
            $flag = $this->objetoDAO->inserir($objeto);
        }

        return $flag;
    }

    private function validarDeletar($objeto) {
        if ($objeto->getId() == "" || $objeto->getId() == null || $objeto->getId() == 0) {
            throw new Exception("Impossível Deletar caso não seja informado 1 ID");
            return false;
        }
        return true;
    }

    protected function deletar($objeto) {

        if ($this->validarDeletar($objeto)) {
            $flag = $this->objetoDAO->deletar($objeto);
            return $flag;
        }
    }

    protected function obterSoma($objeto, array $coluns, $colunSoma, $where = "") {

        $arrayPersist = $objeto->getPersistence();
        if ($arrayPersist != null) {
            $nColum = "";
            if ($coluns != null) {
                foreach ($coluns as $v) {
                    $nColum .= ", " . array_search($v, $objeto->getColMap());
                }
                $nColum = substr_replace($nColum, "", 0, 2);
            }
        }

        return $this->objetoDAO->obterSoma($nColum, array_search($colunSoma, $objeto->getColMap()), $where);
    }

    /*
     * ModelAction::popular
     *
     * @access Public
     */

    public function popular($objeto, $renderizado = false) {
        if ($renderizado) {
            $objeto = $this->renderizar($objeto);
        }
        return $this->_util->copiarObjeto($objeto);
    }

    /*
     * ModelAction::renderizar
     */

    public function renderizar($objeto) {
        return $this->_util->renderizar($objeto);
    }

    /*
     * ModelAction::renderizar
     */

    public function unrenderizar($objeto) {
        return $this->_util->unrenderizar($objeto);
    }

}

?>