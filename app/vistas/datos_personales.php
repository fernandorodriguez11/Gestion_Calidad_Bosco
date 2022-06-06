<?php
ob_start();
?>
<!--
<div id="notiCorrect" class="alert alert-success" style="display: none">
    <button id="bCorrect" type="button" class="close">&times;</button>
    <strong>Contraseña cambiada correctamente</strong>
</div>
<div id="notiInCorrect" class="alert alert-danger " role="alert" style="display: none">
    <button  id="bIncorrect" type="button" data-dismiss="alert" class="close">&times;</button>
    <strong>La contraseña no se ha podido cambiar</strong>
</div>-->

<div class="form-group">
    <label for="name" class="control-label">Nombre</label>
    <input type="text" class="form-control" id="name" name="nombre"
           value="<?= Sesiones::obtener_nombre() ?>" disabled>
</div>    

<div class="form-group">
    <label for="email" class="control-label">Email</label>
    <input type="email" class="form-control" id="email" name="email" 
           value="<?= Sesiones::obtener_email() ?>" disabled>
</div>                                                   

<div class="form-group">
    <label for="roles" class="control-label">Roles</label>
    <select class="form-control" id="roles">
        <?php foreach ($personal_roles as $roles): ?>
            <option><?= $roles['rol'] ?></option>
        <?php endforeach; ?>
    </select>                    
</div>   
<div class="form-group">
    <label for="cursos" class="control-label">Cursos</label>
    <select class="form-control" id="cursos" >
        <option selected="true" disabled="disabled">Sus cursos</option>
        <?php foreach ($personal_cursos as $cursos): ?>
            <option><?= $cursos['nombre_curso'] ?></option>
        <?php endforeach; ?>
    </select>                    
</div>   
<div class="form-group">
    <label for="asignaturas" class="control-label">Asignaturas</label>
    <select class="form-control" id="asignaturas" >
        <option class="paraBorrar" selected="true"disabled="disabled">
            Tiene que seleccionar un curso</option>
    </select>                    
</div>   

<div id="changePassword">
    <button id="change" type="button" class="btn btn-primary" data-toggle="collapse"
            data-target="#old, #new, #con, #guardar" style="margin-bottom: 5%; margin-top: 5%;">Cambiar Contraseña</button>

    <div id="old" class="form-group collapse">
        <label for="pAntigua" class="control-label">Contraseña Actual</label>
        <input type="password" class="form-control" id="pAntigua"  name="passwordAntigua" value="">
    </div> 

    <div id="new" class="form-group collapse">
        <label for="pNueva" class="control-label">Nueva Contraseña</label>
        <input type="password" class="form-control" id="pNueva" name="passwordNueva" value="">
    </div> 

    <div id="con" class="form-group collapse">
        <label for="cambiaPass" class="control-label">Confirmar Contraseña</label>
        <input type="password" class="form-control" id="pConfirmada" name="passwordConfirmada">
    </div> 

    <div id="guardar" class="form-group collapse">
        <button type="button" id="cambiaPass" class="btn btn-primary">Confirmar</button>
    </div>
    
</div>

<?php
//Guarda el contenido del buffer (el código html y php ya ejecutado) en la variable contenido
$contenido = ob_get_clean();
require '../app/vistas/plantilla.php';
?>