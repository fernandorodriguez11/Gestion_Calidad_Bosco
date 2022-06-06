$(document).ready(function () {
    comprueba_rangos();
});

/**
 * 
 * @param {type} respuestas_documento
 * 
 * Función que genere el contenido de las preguntas para que el usuario pueda 
 * responder.
 * 
 * @returns {undefined}
 */
function CargaContenido(respuestas_documento) {

    //console.log(respuestas_documento);

    $.each(respuestas_documento, function (index, respuesta) {
        $("<label/>", {
            "id": "titulo_" + respuesta['id_documentos_lineas'],
            style: "font-weight: bold; font-size: x-large; font-style:chalkduster"
        }).appendTo("#contenido_respuestas");

        $("#titulo_" + respuesta['id_documentos_lineas']).html(respuesta['titulo']);


        $("<br/>", {

        }).appendTo("#contenido_respuestas");


        $("<label/>", {
            "id": "descripcion_" + respuesta['id_documentos_lineas'],
            style: "font-style: italic; font-size: 20px; color: black"
        }).appendTo("#contenido_respuestas");

        $("<br/>", {

        }).appendTo("#contenido_respuestas");

        $("<br/>", {

        }).appendTo("#contenido_respuestas");

        $("#descripcion_" + respuesta['id_documentos_lineas']).html(respuesta['descripcion']);


        if (respuesta['tipo'] == "texto") {

            $("<textarea/>", {
                "id": "textarea_" + respuesta['id_documentos_lineas'],
                "rows": "5",
                "name": "textarea_" + respuesta['id_documentos_lineas'],
                "class": "gestion_documentos_input form-control form-control-lg",
                style: "margin-bottom: 10px;"
            }).appendTo("#contenido_respuestas");

        } else if (respuesta['tipo'] == "puntuacion") {
            $("<div/>", {
                "id": "contenedor_numeros_" + respuesta['id_documentos_lineas'],
                "class": "labelsPuntuaciones"
            }).appendTo("#contenido_respuestas");

            $("<label/>", {
                "id": "etiqueta1_" + respuesta['id_documentos_lineas']
            }).appendTo("#contenedor_numeros_" + respuesta['id_documentos_lineas']);

            $("#etiqueta1_" + +respuesta['id_documentos_lineas']).html("1");

            $("<label/>", {
                "id": "etiqueta2_" + respuesta['id_documentos_lineas']
            }).appendTo("#contenedor_numeros_" + respuesta['id_documentos_lineas']);

            $("#etiqueta2_" + +respuesta['id_documentos_lineas']).html("2");

            $("<label/>", {
                "id": "etiqueta3_" + respuesta['id_documentos_lineas']
            }).appendTo("#contenedor_numeros_" + respuesta['id_documentos_lineas']);

            $("#etiqueta3_" + +respuesta['id_documentos_lineas']).html("3");

            $("<label/>", {
                "id": "etiqueta4_" + respuesta['id_documentos_lineas']
            }).appendTo("#contenedor_numeros_" + respuesta['id_documentos_lineas']);

            $("#etiqueta4_" + +respuesta['id_documentos_lineas']).html("4");

            $("<label/>", {
                "id": "etiqueta5_" + respuesta['id_documentos_lineas']
            }).appendTo("#contenedor_numeros_" + respuesta['id_documentos_lineas']);

            $("#etiqueta5_" + +respuesta['id_documentos_lineas']).html("5");


            $("<div/>", {
                "id": "contenedor_radios_" + respuesta['id_documentos_lineas'],
                "class": "inputsPuntuaciones"
            }).appendTo("#contenido_respuestas");

            $("<input/>", {
                "id": "radio1_" + respuesta['id_documentos_lineas'],
                "name": "radio_" + respuesta['id_documentos_lineas'],
                "type": "radio",
                "value": "1"
            }).appendTo("#contenedor_radios_" + respuesta['id_documentos_lineas']);

            $("<input/>", {
                "id": "radio2_" + respuesta['id_documentos_lineas'],
                "name": "radio_" + respuesta['id_documentos_lineas'],
                "type": "radio",
                "value": "2"
            }).appendTo("#contenedor_radios_" + respuesta['id_documentos_lineas']);

            $("<input/>", {
                "id": "radio3_" + respuesta['id_documentos_lineas'],
                "name": "radio_" + respuesta['id_documentos_lineas'],
                "type": "radio",
                "value": "3"
            }).appendTo("#contenedor_radios_" + respuesta['id_documentos_lineas']);

            $("<input/>", {
                "id": "radio4_" + respuesta['id_documentos_lineas'],
                "name": "radio_" + respuesta['id_documentos_lineas'],
                "type": "radio",
                "value": "4"
            }).appendTo("#contenedor_radios_" + respuesta['id_documentos_lineas']);

            $("<input/>", {
                "id": "radio5_" + respuesta['id_documentos_lineas'],
                "name": "radio_" + respuesta['id_documentos_lineas'],
                "type": "radio",
                "value": "5"
            }).appendTo("#contenedor_radios_" + respuesta['id_documentos_lineas']);
        } else if (respuesta['tipo'] == "calculo_porcentaje") {

            $("<div/>", {
                "id": "contenedor_porcentajes_texto_" + respuesta['id_documentos_lineas'],
                "class": "texto"
            }).appendTo("#contenido_respuestas");

            $("<p/>", {
                "id": "p_previstas_" + respuesta['id_documentos_lineas'],
                style: "font-size: 18px; font-family:bookman"
            }).appendTo("#contenedor_porcentajes_texto_" + respuesta['id_documentos_lineas']);

            $("#p_previstas_" + respuesta['id_documentos_lineas']).html("Previstos");

            $("<p/>", {
                "id": "p_impartidas_" + respuesta['id_documentos_lineas'],
                style: "font-size: 18px; font-family:bookman"
            }).appendTo("#contenedor_porcentajes_texto_" + respuesta['id_documentos_lineas']);

            $("#p_impartidas_" + respuesta['id_documentos_lineas']).html("Impartidos");

            $("<p/>", {
                "id": "p_porc_" + respuesta['id_documentos_lineas'],
                style: "font-size: 18px; font-family:bookman"
            }).appendTo("#contenedor_porcentajes_texto_" + respuesta['id_documentos_lineas']);

            $("#p_porc_" + respuesta['id_documentos_lineas']).html("%");

            $("<div/>", {
                "id": "contenedor_porcentajes_" + respuesta['id_documentos_lineas'],
                "class": "puntuacion"
            }).appendTo("#contenido_respuestas");

            $("<input/>", {
                "id": "number_previstas_" + respuesta['id_documentos_lineas'],
                "name": "number_previstas_" + respuesta['id_documentos_lineas'],
                "type": "number",
                class: "form-control",
                "placeholder": "0",
                min: 1
            }).appendTo("#contenedor_porcentajes_" + respuesta['id_documentos_lineas']);

            $("<input/>", {
                "id": "number_impartidas_" + respuesta['id_documentos_lineas'],
                "name": "number_impartidas_" + respuesta['id_documentos_lineas'],
                "type": "number",
                class: "form-control",
                "placeholder": "0",
                min: 1
            }).appendTo("#contenedor_porcentajes_" + respuesta['id_documentos_lineas']);

            $("<input/>", {
                "id": "number_porcentaje_" + respuesta['id_documentos_lineas'],
                "name": "number_porcentaje_" + respuesta['id_documentos_lineas'],
                "type": "number",
                class: "form-control",
                readonly: "readonly",
                "placeholder": "0",
                "value": "0"
            }).appendTo("#contenedor_porcentajes_" + respuesta['id_documentos_lineas']);


            $("#number_previstas_" + respuesta['id_documentos_lineas']).on("focusout", CalculoPorcentaje);
            $("#number_impartidas_" + respuesta['id_documentos_lineas']).on("focusout", CalculoPorcentaje);

        }
        $("<br/>", {

        }).appendTo("#contenido_respuestas");
        $("<br/>", {

        }).appendTo("#contenido_respuestas");
    });
    
    CargaBotonRespuesta();
    
}

/*
 * Función que obtiene las preguntas de la base de datos
 * 
 * @returns {undefined}
 */
function CargaDatosDocumento() {

    $.ajax({
        url: 'index.php?comando=carga_datos_documento',
        dataType: "json",
        type: "POST",
        data: {'id_documento': getQueryVariable("id_documento")},
        success: function (datos)
        {
            CargaContenido(datos);
        },
        beforeSend: function (xhs) {

        },
        complete: function () {

        }
    });
}

/*
 * Función que obtiene si el documento ya ha sido contestado por el usuario. Si
 * el documento ha sido contestado aparecerá un mensaje informativo de que no 
 * puede contestar más de una vez. Si no lo ha contestado aparece el botón para
 * contestar.
 * 
 * @returns {undefined}
 */
function CargaBotonRespuesta() {

    $.ajax({
        url: 'index.php?comando=carga_contestado',
        dataType: "json",
        type: "GET",
        data: {'id_documento': getQueryVariable("id_documento"),
               'id_rol': getQueryVariable("id_rol"),
               'id_curso': getQueryVariable("id_curso"),
               'id_asignatura': getQueryVariable("id_asignatura")
            },
        success: function (datos)
        {
            if (datos != 1) {
                $("<input/>", {
                    "type": "submit",
                    "value": "Enviar Respuestas del Formulario"
                }).appendTo("#contenido_respuestas");
            } else {
                $("<h3/>", {
                    "text": "Ya has respondido este documento"
                }).appendTo("#contenido_respuestas");
            }
        },
        beforeSend: function (xhs) {

        },
        complete: function () {

        }
    });
}

/*
 * Función que comprueba si la fecha actual está dentro de los rangos de las fechas
 * del documento para poder contestarlo o no. Si la fecha actual es susperior
 * a la fecha fin del documento, aparecerá un mensaje informativo diciendo
 * que el documento ha expirado. Si es menor carga los campos para contestar.
 *  
 * @returns {undefined}
 */
function comprueba_rangos() {

    $.ajax({
        url: 'index.php?comando=esta_fuera_rango',
        dataType: "json",
        type: "GET",
        data: {'id_documento': getQueryVariable("id_documento")},
        success: function (fechas)
        {
            var fechaDeHoy = new Date();
            
            var mes = fechaDeHoy.getMonth()+1;
            mes = addZero(mes);
            var dia =fechaDeHoy.getDate();
            dia = addZero(dia);
            
            var fechaCompuesta = fechaDeHoy.getFullYear()+"-"+mes+"-"+dia;

            if(fechas['fecha_inicio'] > fechaCompuesta){
                $("<h1/>", {
                    "text": "No es posible contestar este documento porque \n\
                    aun no está disponible. Podrá contestarlo a partir del día: "+fechas['fecha_inicio']
                }).appendTo("#contenido_respuestas");
            }else{
               if(fechas['fecha_fin'] < fechaCompuesta){
                   $("<h1/>", {
                    "text": "No es posible contestar este documento porque \n\
                    ya ha expirado. El límite era hasta el día: "+fechas['fecha_fin']
                    }).appendTo("#contenido_respuestas");
                }else{
                    CargaDatosDocumento();
                }
            }
        },
        beforeSend: function (xhs) {

        },
        complete: function () {

        }
    });
}

function addZero(dia) {
    if (dia < 10) {
        dia = '0' + dia;
    }
    return dia;
}

/*
 * 
 * @param {type} variable
 * 
 * Función que recoge de la url el id_documento.
 * 
 * @returns {getQueryVariable.pair|Boolean}
 */
function getQueryVariable(variable) {
    // Estoy asumiendo que query es window.location.search.substring(1);
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    //alert(vars);
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) {
            return pair[1];
        }
    }
    return false;
}

/**
 * 
 * @param {type} e
 * 
 * Función para calcular el porcentaje entre los campos previstas e impartidos.
 * 
 * @returns {undefined}
 */
function CalculoPorcentaje(e) {
    id = e.currentTarget.id;
    id_linea_documento = id.split("_")[2];

    num_impartidas = parseFloat($("#number_impartidas_" + id_linea_documento).val());
    num_previstas = parseFloat($("#number_previstas_" + id_linea_documento).val());

    resultado = parseInt((num_impartidas * 100) / num_previstas);


    $("#number_porcentaje_" + id_linea_documento).val(resultado);
}