$(document).ready(function(){
    
    $("#fCadastro").validate({
        rules: {  
            "Dt_Partida":{  
                validade:true 
            },
             "Dt_Retorno":{
                   maior:true
                 },
            "datachegada":{
                   data:true
                 },
                 "Onibus_Partida":{  
                validade:true 
            },
             "Onibus_Retorno":{
                   maior:true
                 }
                 
            }
    });   
    
    $("#dt_partida").mask("99/99/9999 99:99");
    $("#dt_retorno").mask("99/99/9999 99:99");
    $("#onibus_partida").mask("99/99/9999 99:99");
    $("#onibus_retorno").mask("99/99/9999 99:99");
    $("#datachegada").mask("99/99/9999");
    
      $(".listar_tabela").live('click',function(e){
        var id_participante = $("#ide_municipio").val();
        var key = id_participante;
        //var label_id_participante = $('#ide_municipio').find('option').filter(':selected').text(); 
                      
        if(id_participante == "" ){
            alert("Informe os técnicos que iram participar da turma");
        } else {
            $.ajax({
                url:"index.php?Colaborador/listaPipeiros",
                data:{
                    colaboradores:id_participante
                },
                type: "POST",
                dataType : "json",
                success: function(obj){
                    if(obj.flag > 0){
                        if($("#table-populate").hide){
                            $("#table-populate").fadeIn(1000);
                        }
                        //var arrayAttr = new Array(label_id_participante);
                        var table = document.getElementById("tbody-populate");
                        var linha = table.insertRow(-1);
                        var coluna1 = "";
                        $(linha).attr("id",key);
                        for(var i=0;i<arrayAttr.length;i++){
                            coluna1 = linha.insertCell(-1);
                            coluna1.innerHTML = arrayAttr[i];
                        }
                        coluna1 = linha.insertCell(-1);
                        //coluna1.innerHTML = "<label class=\"editRow\">Editar</label><label class=\"delRow\">X</label>";
                        coluna1.innerHTML = "<label class=\"rmv_linha\">Excluir</label>";
                    
                    } else {
                        alert("O técnico já foi escolhido.");
                    }                    
                }
            });
        }      
    });
    
    $("#tbody-populate tr td .rmv_linha").live("click",function(){
        var objRemove = {
            key      : $(this).parents("tr").attr("id"),
            tabId    : $("#tbody-populate")
        }
        $.ajax({
            url:"index.php?Viagem/removerParticipante",
            data:{
                key:objRemove.key
                },
            type: "POST",
            dataType : "json",
            success : function(obj){
                if(obj.flag > 0){
                    $("#tbody-populate #"+objRemove.key).remove();
                    if($("#tbody-populate tr td .rmv_linha").length == 0){
                        $("#table-populate").fadeOut("normal");
                    }
                }else{
                    alert("Houve algum problema para deletar tente de novo mais tarde");
                }
            }
        });
    });
    
     $("#add_linha").live('click',function(e){
        var id_participante = $("#roteiros").val();
        var data = $('#datachegada').val();
        var key = id_participante ;
        var label_id_participante = $('#roteiros').find('option').filter(':selected').text(); 
        var label_data = $('#datachegada').val();
        
                      
        if((id_participante == "")|| (data == "") ){
            alert("Informe os destino ou data para o roteiro");
        } else {
            $.ajax({
                url:"index.php?Viagem/adicionarDestino",
                data:{
                    roteiros:id_participante,
                    dataChegada:data
                },
                type: "POST",
                dataType : "json",
                success: function(obj){
                    if(obj.flag > 0){
                        if($("#table-destinos").hide){
                            $("#table-destinos").fadeIn(1000);
                        }
                        var arrayAttr = new Array(label_id_participante,label_data);
                        var table = document.getElementById("tbody-destinos");
                        var linha = table.insertRow(-1);
                        var coluna1 = "";
                        $(linha).attr("id",key);
                        for(var i=0;i<arrayAttr.length;i++){
                            coluna1 = linha.insertCell(-1);
                            coluna1.innerHTML = arrayAttr[i];
                        }
                        coluna1 = linha.insertCell(-1);
                       
                        //coluna1.innerHTML = "<label class=\"editRow\">Editar</label><label class=\"delRow\">X</label>";
                        coluna1.innerHTML = "<label class=\"rmv_linha\">Excluir</label>";
                    
                    } else {
                        alert("O técnico já foi escolhido.");
                    }                    
                }
            });
        }      
    });
    
    $("#tbody-destinos tr td .rmv_linha").live("click",function(){
        var objRemove = {
            key      : $(this).parents("tr").attr("id"),
            tabId    : $("#tbody-destinos")
        }
        $.ajax({
            url:"index.php?Viagem/removerDestino",
            data:{
                key:objRemove.key
                },
            type: "POST",
            dataType : "json",
            success : function(obj){
                if(obj.flag > 0){
                    $("#tbody-destinos #"+objRemove.key).remove();
                    if($("#tbody-destinos tr td .rmv_linha").length == 0){
                        $("#table-destinos").fadeOut("normal");
                    }
                }else{
                    alert("Houve algum problema para deletar tente de novo mais tarde");
                }
            }
        });
    });
    
    $('#cod_veiculo').change(function(){
        var veiculo =  $('#tipoVeiculo').val();
        
        if (veiculo == 2){
            $("#onibus").fadeIn(1000);
        }else{
            $("#onibus").fadeOut("normal");
        }
        
        
        
        
    });
 
});

