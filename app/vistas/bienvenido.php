<?php
ob_start(); //Envía la salida a un buffer (memoria) en vez de al usuario
?>
<!--<div class="row">-->
    <!--<div class="col-1"></div>-->
    <!--<div class="col-xl-6 col-lg-8 col-md-12 col-sm-12 col-12 centrar_texto centrar_texto">-->
        <h3 class="centrar_texto">Bienvenido <?php 
            if(Sesiones::existe_variable_sesion()){
                echo Sesiones::obtener_nombre();
            }
        ?></h3><br><br>
        <img src="imagenes/logo_juan_bosco.png" class="imagen_logo"/>
        <h1>Gestión de Calidad</h1> <br>
        <h1>I.E.S. Juan Bosco</h1>
        
        <form action="index.php?comando=enviar_mail" method="POST">
            <input type="submit" value="Enviar Mail Prueba">
        </form>
    <!--</div>-->

    <!--<div class="col-1"></div>-->
<!--</div>-->
<?php
//Guarda el contenido del buffer (el código html y php ya ejecutado) en la variable contenido
$contenido = ob_get_clean();
require '../app/vistas/plantilla.php';
?>