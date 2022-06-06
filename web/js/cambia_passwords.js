$(function () {

    $("#cambiaPass").click(cambiarPassword);

});

/*
 * Función que se encarga de cambiar las contraseñas del usuario. Comprueba
 * que la contraseña actual es la misma que hay en la bd. Y comprueba que la 
 * nueva sea distinta a la antigua.
 * @returns {undefined}
 */
function cambiarPassword() {

    var antigua = $("#pAntigua").val();
    var nueva = $("#pNueva").val();
    var confirmar = $("#pConfirmada").val();

    if (confirmar != nueva) {

        if (confirmar == "") {
            /*
             * Mensaje de error por dejar el campo de confirmar contraseña vacío
             */
            swal({
                title: "Error",
                text: "El campo de confirmación de contraseña no puede \n\
                        estar vacío.",
                type: "warning",
                showConfirmButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "OK",
                closeOnConfirm: false
            });
            $("#pNueva").css("border", "1px solid #ced4da");
            $("#pConfirmada").css("border", "3px solid red");
        } else if (nueva == "") {
            /*
             * Mensaje de error por dejar el campo de la nueva contraseña vacío
             */
            swal({
                title: "Error",
                text: "El campo de nueva contraseña no puede \n\
                        estar vacío.",
                type: "warning",
                showConfirmButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "OK",
                closeOnConfirm: false
            });
            $("#pConfirmada").css("border", "1px solid #ced4da");
            $("#pNueva").css("border", "3px solid red");
        } else {
            /*
             * Mensaje de error si el usuario escribe mal la contraseña nueva
             * y la contraseña de confirmación
             */
            swal({
                title: "Error",
                text: "La nueva contraseña y confirmación no son iguales.",
                type: "warning",
                showConfirmButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "OK",
                closeOnConfirm: false
            });
            //Resaldta estos dos inputs por haber fallado
            $("#pNueva").css("border", "3px solid red");
            $("#pConfirmada").css("border", "3px solid red");
        }

    } else {

        $.ajax({
            url: 'index.php?comando=change_password',
            type: 'POST',
            data: {
                antigua: antigua,
                nueva: nueva,
                confirmar: confirmar
            },
            success: function (resultado) {
                //Muestro mensaje de error al no poder cambiar correctamente la contraseña
                if (resultado == 'error') {
                    swal({
                        title: "Error",
                        text: "No ha sido posible cambiar la contraseña. La \n\
                        contraseña antigua no es correcta.",
                        type: "warning",
                        showConfirmButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "OK",
                        closeOnConfirm: false
                    });
                    //Pongo los otros campos con sus colores por defecto
                    $("#pNueva").css("border", "1px solid #ced4da");
                    $("#pConfirmada").css("border", "1px solid #ced4da");
                    //resalto este campo para que el usuario sepa que el error está ahí
                    $("#pAntigua").css("border", "3px solid red");
                    
                } else {
                    //Muestro mensaje de exito al cambiar la contraseña correctamente
                    swal({
                        title: "Exito",
                        text: "Contraseña cambiada correctamente",
                        type: "success",
                        showConfirmButton: true,
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "OK",
                        closeOnConfirm: false
                    });
                    
                    //Pongo por defecto los colores de los inputs
                    $("#pAntigua").css("border", "1px solid #ced4da");
                    $("#pConfirmada").css("border", "1px solid #ced4da");
                    $("#pNueva").css("border", "1px solid #ced4da");
                    
                    //Cierro el datatoogle
                    $("#change").click();
                    $("#pAntigua").val("");
                    $("#pNueva").val("");
                    $("#pConfirmada").val("");
                }
            }
        });
    }
}