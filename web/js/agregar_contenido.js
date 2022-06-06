$(function(){
    
    $("#agregar_contenido").click(agregamos_contenidos);
    
});

//Función para agregar el contenido a la vista para poder insertarlo en la bd
function agregamos_contenidos(){
    
    $("#todoContenido").val($("#todoContenido").val()+"/"+$("#titulo").val()+"/"
            +$("#descripcion").val()+"/"+$("#selectpicker_tipos").val());
    
    $("#title").html($("#title").html()+$("#titulo").val()+"<br>");
    $("#type").html($("#type").html()+$("#selectpicker_tipos").val()+"<br>");
    
    $("#titulo").val("");
    $("#descripcion").val("");
    
}
