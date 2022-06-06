<?php
ob_start(); //Envía la salida a un buffer (memoria) en vez de al usuario
?>

<h1>Importación de datos</h1><br>
<form action="" method="POST" enctype="multipart/form-data">
    <h3>Importación de Roles</h3> 
    <input type="file" name="archivo_roles"/><br><br>

    <h3>Importación de Asignaturas</h3> 
    <input type="file" name="archivo_asignaturas"/><br><br>

    <h3>Importación de Cursos</h3> 
    <input type="file" name="archivo_cursos"/><br><br>

    <h3>Importación de Profesores</h3> 
    <input type="file" name="archivo_profesores"/><br><br>
    
    <h3>Importación de Alumnos</h3> 
    <input type="file" name="archivo_alumnos"/><br><br>

    <input name="button_importar" type="submit" value="Importar"/>
</form>

<?php
$contenido = ob_get_clean();
require '../app/vistas/plantilla.php';
?>

