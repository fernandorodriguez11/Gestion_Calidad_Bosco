$array_contenido = [];

$(document).ready(function () {
    Eventos();
});

function Eventos(){
    $("#button_agregar_contenido").click(AgregarContenido);
}

function AgregarContenido(){
    $array_contenido.push([$("#documento_titulo").val(), $("#documento_descripcion").val(), $("#selectpicker_tipo").val()]);

    $("#documento_titulo").val("");
    $("#documento_descripcion").val("");

}