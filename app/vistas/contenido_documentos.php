<?php
ob_start(); //Envía la salida a un buffer (memoria) en vez de al usuario
?>

<form action="" method="post">
    <div id="contenido_respuestas">
        
    </div>
</form>

<script type="text/javascript" src="js/js_contenido_documentos.js"></script>
<?php
$contenido = ob_get_clean();
require '../app/vistas/plantilla.php';
?>