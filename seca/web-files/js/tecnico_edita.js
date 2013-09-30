$(function(){
    $('#ide_municipio').change(function(){
        var municipio = $(this).val();
        if( $(this).val() ) {
            $('.alert-notice').empty();
            $.getJSON('index.php?Bairro/listarBairroPorMunicipio/id/'+municipio, 
            function(j){
                var options = '<option value="">Selecione</option>';
                if(j){
                    for (var i = 0; i < j.length; i++) {
                        options += '<option value="' + j[i].id + '">' + j[i].nome + '</option>';
                    }	
                } else {
                    $('.alert-notice').append("<div class='notices'>"+
                    "<div class='bg-color-blueDark'>"+
                    "<div class='notice-icon'> <img/> </div>"+
                    "<div class='notice-header'>Problemas com sua solicitação</div>"+
                    "<div class='notice-text'>O Município escolhido não possui bairros associados.</div>"+
                    "</div>");
                }
                $('#ide_bairro').html(options).show();
            });
        } else {
            $('#ide_bairro').html('<option value="">Selecione</option>');
        }
    });
    
    $('#fil_municipio').change(function(){
        var municipio = $(this).val();
        if( $(this).val() ) {
            $('#notice').empty();
            $.getJSON('index.php?Bairro/listarBairroPorMunicipio/id/'+municipio, 
            function(j){
                var options_abr = '';
                if(j){
                    for (var i = 0; i < j.length; i++) {
                        options_abr += '<option value="' + j[i].id + '">' + j[i].nome + '</option>';
                    }	
                } else {
                    $('#notice').append("<div class='notices'>"+
                    "<div class='bg-color-orangeDark'>"+
                    "<div class='notice-icon'> <img/> </div>"+
                    "<div class='notice-header'>Problemas com sua solicitação</div>"+
                    "<div class='notice-text'>O Município escolhido não possui bairros associados.</div>"+
                    "</div>");
                }
                $('#lst_bairros').html(options_abr).show();
            });
        } else {
            //$('#ide_bairro').html('<option value="">Selecione</option>');
        }
    });
    
    $("#fCadastro").validate({
        rules: {  
            dat_nascimento : {  
                idade: true
            },
            des_email:{
                email: true
            }
        }
    });
    
    $("#num_cpf").mask("999.999.999-99");
    $("#num_cep").mask("99999-999");
    $("#num_telefone1").mask("(99)9999-9999");
    $("#num_telefone2").mask("(99)9999-9999");
    $("#dat_nascimento").mask("99/99/9999");
    
});