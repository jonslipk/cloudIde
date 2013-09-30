$(function(){
    $("#senha").click(function(){
        $("#alterar_senha").toggle('slow');
    });
    
    $("#fCadastro").validate({
        rules:{
            des_senha:{
                required:true,
                remote:'index.php?c=Usuario&a=isPassword'
            },
            des_senha_new:{
                required:true
            },
            des_senha_conf:{
                required:true,
                equalTo: '#des_senha_new'
            }
        },
        messages:{
            des_senha:{remote:'A senha informada não corresponde a senha do usuário.'}
        }
    });
});