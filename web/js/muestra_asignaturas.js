$(function(){
    
    $("#cursos").change(obtenSusAsignaturas);
    
});

/* Función que es utilizada cuando el usuario elige un curso para que aparezcan 
 * las asignaturas vinculadas a ese curso.*/
function obtenSusAsignaturas(){
    
    var cursos = $("#cursos").val();
    
    //Cada vez que cambio de curso borro las asiganturas que haya en el select.
    $("#asignaturas").find($(".paraBorrar")).remove();

    $.ajax({
        url: 'index.php?comando=obten_asignatura_cursos',
        data:{'curso': cursos},
        type: 'GET',
        datatype: 'json',
        success: function(result){
            var resultado = JSON.parse(result);
            
            //Recorro las asignaturas del curso y las muestro en el select
            for(var i = 0; i <= resultado.length; i++){
                
                var asignaturas = resultado[i]["nombre_asignatura"];
                
                var opciones = $("<option/>",{id: 'asig'+i,
                    class: "paraBorrar",
                    text: asignaturas});

                $("#asignaturas").append(opciones);
                $("#asignaturas").selectpicker("refresh");
            }
        }
    });
}

