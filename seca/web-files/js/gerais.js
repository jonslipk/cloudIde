$(function(){
    $("#imprimir").click(function(){
        window.print();
    });
    
    $("#localizar").click(function(){
        alert("Localizando...");
    });
    
    $("#principal").click(function(){
        $(window.document.location).attr("href","index.php");
    });
})