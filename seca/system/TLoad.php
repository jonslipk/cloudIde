<?php
    function __autoload($TClass){

        $tree = array(
            'vob'=>'app',
            'action'=>'app',
            'dao'=>'app',
            'ws'=>'app',
            'template'=>'app',
            'conexoes'=>'system',
            'helpers'=>'system',
            'modelo'=>'system'
            );
        
        foreach ($tree as $dir=>$escopo){
            if(file_exists(PATH.$escopo."/".$dir."/".$TClass.".php")){
                require_once PATH.$escopo."/".$dir."/".$TClass.".php";
                break;
            }
        }        
    }
?>
