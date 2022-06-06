<?php
    ob_start(); //Envía la salida a un buffer (memoria) en vez de al usuario
?>
<h3>Importación para carga inicial</h3>
<p>Importación de roles</p> <input type="file" name="archivo_roles"/>



    <?php
    $contenido = ob_get_clean();
    require '../app/vistas/plantilla.php';
    ?>

