$(window).load(function(){
    var dtHoje = new Date();
    var dia = dtHoje.getDate();
    var mes = (dtHoje.getMonth() + 1);
    var ano = dtHoje.getFullYear();

    if(mes < 10){
        mes = "0" + mes;
    }
    var data = ano + "-" + mes + "-" + dia;
    
    $('#notice').empty();
    $.getJSON('index.php?Grupo/listarGruposDeViajens/in/'+data, 
        function(j){

            var options = '';	
            if(j){
                for (var i = 0; i < j.length; i++) {                                  
                    options += '<tr><td><a href="index.php?Grupo/cadastrar/in/'+dataFormat(j[i].inicio)+'/end/'+dataFormat(j[i].fim)+'">'+ j[i].inicio+" ---- " +j[i].fim +'</a></td>'+'<td>'+j[i].total +'</td>'+'</tr>';
                }	
            } else {
                $('#notice').append("<div class='notices'>"+
                    "<div class='bg-color-yellow'>"+
                    "<div class='notice-icon'> <img/> </div>"+
                    "<div class='notice-header'>Problemas com sua solicitação</div>"+
                    "<div class='notice-text'>O Município que selecionou não possui técnicos cadastrados.</div>"+
                    "</div>");
            }

            $('#tabelaviagens').html(options).show();
        });    
    
    
});


$(function(){
   
    $("#pesquisar").click(function(){        
        var inicio =  $("#dt_inicio").val();
        var dtInicio = inicio.split("/");
        inicio = dtInicio[2] + "-" + dtInicio[1] + "-" + dtInicio[0];
        
        var fim = $("#dt_fim").val();
        var dtFim = fim.split("/");
        fim = dtFim[2] + "-" + dtFim[1] + "-" + dtFim[0];

        if(( inicio != "" )||(fim != "") ) {
            $('#notice').empty();
            $.getJSON('index.php?Grupo/listarGruposDeViajens/in/'+inicio+'/end/'+fim, 
                function(j){
                
                    var options = '';	
                    if(j){
                        for (var i = 0; i < j.length; i++) {                                  
                            options += '<tr><td><a href="index.php?Grupo/cadastrar/in/'+dataFormat(j[i].inicio)+'/end/'+dataFormat(j[i].fim)+'">'+ j[i].inicio+" ---- " +j[i].fim +'</a></td>'+'<td>'+j[i].total +'</td>'+'</tr>';
                        }	
                    } else {
                        $('#notice').append("<div class='notices'>"+
                            "<div class='bg-color-yellow'>"+
                            "<div class='notice-icon'> <img/> </div>"+
                            "<div class='notice-header'>Problemas com sua solicitação</div>"+
                            "<div class='notice-text'>O Município que selecionou não possui técnicos cadastrados.</div>"+
                            "</div>");
                    }
               
                    $('#tabelaviagens').html(options).show();
                });
        } else {
            $('#tabelaviagens').html('vazio');
            
        }
    });
        
    
    $("#fCadastro").validate({
        //        rules: {  
        //            "Dt_Partida":{  
        //                validade:true 
        //            },
        //             "Dt_Retorno":{
        //                   maior:true
        //                 }
        //                 
        //            }
        //            
        
        });   
    
    $("#dt_inicio").mask("99/99/9999");
    $("#dt_fim").mask("99/99/9999");
    
    jQuery.fn.selectmove = function(){
        var $div = $(this);

        $div.find('#remove_todos').click(function(){
            $div.find('select:eq(1) option').each(function(){
                $(this).remove().appendTo($div.find('select').eq(0));
            });    
        });
        $div.find('#move_todos').click(function(){
            $div.find('select:eq(0) option').each(function(){
                $(this).remove().appendTo($div.find('select').eq(1));
            });    
        });
        $div.find('#move_sel').click(function(){
            $div.find('select').eq(0).find('option:selected').remove().appendTo($div.find('select').eq(1));
        });
        $div.find('#remove_sel').click(function(){
            $div.find('select').eq(1).find('option:selected').remove().appendTo($div.find('select').eq(0));
        });
    }
    $('#select_move').selectmove();   
    $('#select_move_abr').selectmove();
    
   
   
 
});

 function dataFormat(dt){
       
        var data = dt;
        var dia = data.substr(0,2);
        var mes = data.substr(2,2);
        var ano = data.substr(4,4);
        
        var datas = ano +"-"+mes+"-"+dia;
       return datas;
    }
   