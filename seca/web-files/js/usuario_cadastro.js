$(function(){
    $("#fCadastro").validate({
        rules:{
            ide_colaborador:{
                remote:'index.php?c=Usuario&a=verificarUsuarioUnico'
            }            
        },
        messages:{
            ide_colaborador:{remote:'Esse colaborador ja possui usuário de acesso, resgate o usuário.'}
        }
    });
});