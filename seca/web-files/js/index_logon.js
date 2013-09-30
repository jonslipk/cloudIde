/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){
    
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
