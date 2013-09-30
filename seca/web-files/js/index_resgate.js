$(function(){
    
    $("#fCadastro").validate({
        rules: {  
            num_cpf : {  
                cpf: 'valid',
                remote: 'index.php?c=Colaborador&a=colaboradorRtgi'
            }
        },
        messages:{
            num_cpf:{
                remote:'O CPF informado já faz parte do SECA.'
            }
        }
    });
    
    $("#num_cpf").mask("999.999.999-99");
    
});