<?php
ob_start(); //Envía la salida a un buffer (memoria) en vez de al usuario
?>
<?php if($todos_cursos == null && $todos_roles == null && 
        $documents == null && $roles == null): ?>
<h1>Aún no tienes ningún documento para descargar.</h1>
<?php else: ?>
<h1>Documentos a descargar</h1>
<?php foreach($todos_cursos as $cursos): ?>
<p> <?= $cursos['nombre_curso'] ?><p>
<?php foreach($documents as $documento): ?>
 <?php if($cursos['id_curso'] == $documento['id_curso']): ?>
<div class="documentoDow">
    <div class="nameDocDow" title="Nombre del Documento"><?= $documento['nombre_documento'] ?></div>
    <a class="descargarDoc" href="index.php?comando=descargar_documento&id_documento=<?= $documento['id_documentos'] ?>&id_curso=<?=$documento['id_curso']?>">
        <i class="fas fa-file-download" title="descargar_documento"></i>
    </a>
</div>
<?php endif; ?>
<?php endforeach; ?>
<?php endforeach; ?>

<?php foreach($todos_roles as $rol):?>
<p> <?= $rol->getRol(); ?><p>
<?php foreach($roles as $document): ?>
<?php if($rol->getId_Rol() == $document['id_rol']): ?>
<div class="documentoDow">
    <div class="nameDocDow" title="Nombre del Documento"><?= $document['nombre_documento'] ?></div>
    <a class="descargarDoc" href="index.php?comando=descargar_documento&id_documento=<?= $document['id_documentos'] ?>&id_rol=<?=$document['id_rol'] ?>">
        <i class="fas fa-file-download" title="descargar_documento"></i>
    </a>
</div>    
<?php endif;?>
<?php endforeach; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php
$contenido = ob_get_clean();
require '../app/vistas/plantilla.php';
?>