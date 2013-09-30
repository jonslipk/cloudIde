$(function(){
    
    $("#ini-start").css('display','none');
    $("#menu").remove();
    $("#rodape").remove();
    $("#num_cpf").focus();
        
    $("#fLogin").validate({
        rules:{
            num_cpf : {  
                cpf: 'valid'
            }
        }
    });
    
    $("#num_cpf").mask("999.999.999-99");
});