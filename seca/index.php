<?php
$ini = parse_ini_file('config/config.ini',true);

foreach ($ini['incluides'] as $file){
    include_once 'system/'.$file.".php"; 
}

//Application
define('NAME_SIS', strtoupper($ini['application']['nome']));
define('DESCRICAO_SIS', $ini['application']['descricao']);
define('OID_SIS', $ini['application']['oid']);
define('PROJECT', $ini['application']['nome']."/");
define('APP', 'app/');
define('TEMPLATE', 'app/template/');
define('CONTROLLERS', 'app/controllers/');
define('VIEWS', 'app/view/');
define('WEBFILES', 'web-files/');

//Paths
define('PATH',getenv('DOCUMENT_ROOT')."/".PROJECT);
define('PATH_SYSTEM',getenv('DOCUMENT_ROOT')."/".PROJECT."system/");
define('PATH_APP',getenv('DOCUMENT_ROOT')."/".PROJECT.APP);
define('PATH_IMAGE', 'http://'.$_SERVER['HTTP_HOST'].'/'.PROJECT.WEBFILES."images/");
define('PATH_WEBFILES', 'http://'.$_SERVER['HTTP_HOST'].'/'.PROJECT.WEBFILES);

define('VERSAO',$ini['versionamento']['versao']);

$start = new System();
$start->run();
?>