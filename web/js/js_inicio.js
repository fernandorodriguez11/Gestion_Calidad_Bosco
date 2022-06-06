$(document).ready(function () {
    iniciar();
});

/* Función que llama a dos funciones. Una para cargar el controlador del 
 * calendario y otro para cargar el evento de crear un documento*/
function iniciar() {
    //Carga Valores
    CargaControles();

    //Carga Eventos
    CargaEventos();

}

function CargaControles() {
    CargaControlCalendario();
    CargaControlAcordeon();
    CargaComboBox();
    CargaControlSelectPicker();
}
//Función que contiene si la función de hacer click en el botón de guardar documento
function CargaEventos() {
    $("#documento_guardar").click(GuardaDatosDocumento);
}
/**
 * Carga los calendarios
 */
function CargaControlCalendario() {

    $("#datepicker").datepicker({
        firstDay: 1,
        monthNames: ['Enero', 'Febrero', 'Marzo',
            'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre',
            'Octubre', 'Noviembre', 'Diciembre'],
        dayNamesMin: ['D', 'L', 'M', 'X', 'J', 'V', 'S']
    });

    $("#datepicker").datepicker();

    $("#datepicker1").datepicker({
        firstDay: 1,
        monthNames: ['Enero', 'Febrero', 'Marzo',
            'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre',
            'Octubre', 'Noviembre', 'Diciembre'],
        dayNamesMin: ['D', 'L', 'M', 'X', 'J', 'V', 'S']
    });

    $("#datepicker1").datepicker();

    $("#datepicker2").datepicker({
        firstDay: 1,
        monthNames: ['Enero', 'Febrero', 'Marzo',
            'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre',
            'Octubre', 'Noviembre', 'Diciembre'],
        dayNamesMin: ['D', 'L', 'M', 'X', 'J', 'V', 'S']
    });

    $("#datepicker2").datepicker();
}

function CargaControlAcordeon() {
    $("#accordion").accordion();
    $("#menu").menu();

    $("#accordion").accordion({
        collapsible: true,
        active: false,
        header: "h3"
    });
}

function CargaComboBox() {
    $("#number")
            .selectmenu()
            .selectmenu("menuWidget")
            .addClass("overflow");
}

function CargaControlSelectPicker() {
    $('select').selectpicker();

}

function EventosControlSelectPicker() {
    $('.selectpicker').on('show.bs.select', function (e) {
        // on show
    });

    $('.selectpicker').on('shown.bs.select', function (e) {
        // on shown
    });

    $('.selectpicker').on('hide.bs.select', function (e) {
        // on hide
    });

    $('.selectpicker').on('hidden.bs.select', function (e) {
        // do hidden
    });

    $('.selectpicker').on('loaded.bs.select', function (e) {
        // on loaded
    });

    $('.selectpicker').on('rendered.bs.select', function (e) {
        // on rendered
    });

    $('.selectpicker').on('refreshed.bs.select', function (e) {
        // on refreshed
    });

    $('.selectpicker').on('changed.bs.select', function (e) {
        // on changed
    });

}

function MetodosControlSelectPicker() {
    // Sets the selected value
    $('.selectpicker').selectpicker('val', 'JQuery');
    $('.selectpicker').selectpicker('val', ['jQuery', 'Script']);

// Selects all items
    $('.selectpicker').selectpicker('selectAll');

// Clears all
    $('.selectpicker').selectpicker('deselectAll');

// Re-render
    $('.selectpicker').selectpicker('render');


// Sets styles
// Replace Class
    $('.selectpicker').selectpicker('setStyle', 'btn-danger');
// Add Class
    $('.selectpicker').selectpicker('setStyle', 'btn-large', 'add');
// Remove Class
    $('.selectpicker').selectpicker('setStyle', 'btn-large', 'remove');

// Refreshes
    $('.selectpicker').selectpicker('refresh');

// Toggles the drop down list
    $('.selectpicker').selectpicker('toggle');

// Hides the drop down list
    $('.selectpicker').selectpicker('hide');

// Shows the drop down list
    $('.selectpicker').selectpicker('show');

// Destroys the drop down list
    $('.selectpicker').selectpicker('destroy');
}

/**
 * Función encargada de obtener los datos del calendario y de los tres 
 * selectpicker de cursos roles y asignaturas.
 * 
 */
function GuardaDatosDocumento() {
    var fecha_inicio = $("#datepicker1").val();
    var fecha_fin = $("#datepicker2").val();
    var roles_documento = $("#selectpicker_roles").val();
    var cursos_documento = $("#selectpicker_cursos").val();
    var asignaturas_documento = $("#selectpicker_asignaturas").val();
    
    $("#documento_fecha_inicio").val(fecha_inicio.substr(6, 4) + "-" + fecha_inicio.substr(0, 2) + "-" + fecha_inicio.substr(3, 2));
    $("#documento_fecha_fin").val(fecha_fin.substr(6, 4) + "-" + fecha_fin.substr(0, 2) + "-" + fecha_fin.substr(3, 2));
    $("#documento_roles").val(roles_documento);
    $("#documento_cursos").val(cursos_documento);
    $("#documento_asignaturas").val(asignaturas_documento);
    
}