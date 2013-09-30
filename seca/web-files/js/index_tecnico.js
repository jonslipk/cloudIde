/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){
    $("#somar").click(function(){
        $.ajax({
            url: "index.php?Tecnico/somar",
            type: "post",
            data:{
                a:$("#valor1").val(),
                b:$("#valor2").val()                
            },
            dataType: "json",
                                    
            success: function(data){
                $("#resultado").append(data.resp);
            }
        });
    });
});
