$(function(){
    $("#fCadastro").validate({
        rules:{
            ide_colaborador:{
                remote:'index.php?c=Usuario&a=verificarUsuarioUnico'
            }            
        },
        messages:{
            ide_colaborador:{remote:'Esse colaborador ja possui usu�rio de acesso, resgate o usu�rio.'}
        }
    });
});