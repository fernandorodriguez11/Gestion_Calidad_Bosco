<?php
ob_start(); //Envía la salida a un buffer (memoria) en vez de al usuario
?>
<?php if(Sesiones::saber_sies_admin() != true): ?>
<?php if(!$roles || !$document):?>
<h2>No hay documentos relacionados a este usuario</h2>
<?php else: ?>
<?php foreach ($roles as $r): ?>
    <button class="btn btn-info botones_roles" type="button" data-toggle="collapse" data-target="#rolDocuments<?= $r['rol'] ?>">
        <h2>Documentos del Rol: <?= $r['rol'] ?><i style="font-size:18px; margin-left: 15px;" class="fas fa-align-justify"></i></h2>
    </button><br>
    <div class="collapse" id="rolDocuments<?= $r['rol'] ?>">
    <div class="documento1">
        <div class="nombreDocumento1" title="Nombre del Documento">Nombre Documento</div>
        <div class="fechaInicio1" title="Fecha de Inicio" >fecha inicio</div>
        <div class="fechaFin1" title="Fecha de Fin" size="30px">fecha fin</div>
    </div>
    <?php foreach ($document as $docu): ?>
        <?php if ($r['rol'] == $docu['rol']): ?>
            <?php if ($docu['baja'] == 1): ?>
                <div class="documento">
                    <div class="documentoBaja nombreDocumento" title="Nombre del Documento"><?= $docu['nombre_documento'] ?></div>
                    <div class="fechaInicio" title="Fecha de Inicio" ><?= $docu['fecha_inicio'] ?></div>
                    <div class="fechaFin" title="Fecha de Fin"><?= $docu['fecha_fin'] ?></div>
                    <!--<a class="modificarDocumento" href="index.php?comando=dar_alta&id_documento=<= $docu['id_documentos'] ?>" >
                        <i class="fas fa-plus-circle" style="color: green" title="dar de alta el documento"></i>
                    </a>-->
                </div>
            <?php else : ?>
                <div class="documento">
                    <div class="nombreDocumento" title="Nombre del Documento"><?= $docu['nombre_documento'] ?></div>
                    <div class="fechaInicio" title="Fecha de Inicio" ><?= $docu['fecha_inicio'] ?></div>
                    <div class="fechaFin" title="Fecha de Fin"><?= $docu['fecha_fin'] ?></div>
                    <a class="verContenido" href="index.php?comando=cargar_contenido&id_documento=<?= $docu['id_documentos']; ?>&id_rol=<?=$docu['id_rol'];?>">
                        <i class="fas fa-eye" title="ver documento"></i></a>
                    <!--<a class="modificarDocumento" href="index.php?comando=dar_baja&id_documento=<= $docu['id_documentos'] ?>">
                        <i class="fas fa-minus-circle" style="color: red" title="dar de baja el documento"></i>
                    </a>-->
                </div>
            <?php endif ?>
        <?php endif ?>
    <?php endforeach ?>
    </div>
<?php endforeach ?>
<?php endif;?>

<?php if(!$group || !$group_subject || !$subject):?>

<?php else: ?>
<?php foreach ($group as $cursos): ?>
    <button class="btn-info botones_cursos" type="button" data-toggle="collapse" data-target="#cursosDocuments<?= $cursos['id_curso'] ?>">
        <h4>Curso: <?= $cursos['nombre_curso'] ?><i style="font-size:18px; margin-left: 15px;" class="fas fa-angle-down"></i></h4>
    </button>
    <div class="collapse navbar-collapse" id="cursosDocuments<?= $cursos['id_curso'] ?>">
    <?php foreach ($group_subject as $asignaturas_agrupadas): ?>
        <?php if ($cursos['id_curso'] == $asignaturas_agrupadas['id_curso']): ?>
        <button class="btn-danger botones_asignaturas" type="button" data-toggle="collapse" data-target="#asigDocuments<?= $asignaturas_agrupadas['id_asignatura']?>">
                <h5>Asignaturas: <?= $asignaturas_agrupadas['nombre_asignatura']; ?>
                <i style="font-size:18px; margin-left: 15px;" class="fas fa-align-justify"></i></h5>
        </button>
        <div class="collapse navbar-collapse" id="asigDocuments<?= $asignaturas_agrupadas['id_asignatura']?>">
            <div class="documento1">
                <div class="nombreDocumento1" title="Nombre del Documento">Nombre Documento</div>
                <div class="fechaInicio1" title="Fecha de Inicio" >fecha inicio</div>
                <div class="fechaFin1" title="Fecha de Fin" size="30px">fecha fin</div>
            </div>
            <?php foreach ($subject as $asignaturas): ?>
                <?php if ($asignaturas_agrupadas['id_asignatura'] == $asignaturas['id_asignatura']): ?>
                    <?php if ($asignaturas['baja'] == 1): ?>
                        <div class="documento">
                            <div class="documentoBaja nombreDocumento" title="Nombre del Documento"><?= $asignaturas['nombre_documento'] ?></div>
                            <div class="fechaInicio" title="Fecha de Inicio" ><?= $asignaturas['fecha_inicio'] ?></div>
                            <div class="fechaFin" title="Fecha de Fin"><?= $asignaturas['fecha_fin'] ?></div>
                            <!--<a class="modificarDocumento" href="index.php?comando=dar_alta&id_documento=<= $docu['id_documentos'] ?>" >
                                <i class="fas fa-plus-circle" style="color: green" title="dar de alta el documento"></i>
                            </a>-->
                        </div>
                    <?php else : ?>

                        <div class="documento">
                            <div class="nombreDocumento" title="Nombre del Documento"><?= $asignaturas['nombre_documento'] ?></div>
                            <div class="fechaInicio" title="Fecha de Inicio" ><?= $asignaturas['fecha_inicio'] ?></div>
                            <div class="fechaFin" title="Fecha de Fin"><?= $asignaturas['fecha_fin'] ?></div>
                            <a class="verContenido" href="index.php?comando=cargar_contenido&id_documento=<?= $asignaturas['id_documentos']; ?>&id_curso=<?= $asignaturas_agrupadas['id_curso'];?>&id_asignatura=<?=$asignaturas['id_asignatura'];?>">
                                <i class="fas fa-eye" title="ver documento"></i></a>
                            <!--<a class="modificarDocumento" href="index.php?comando=dar_baja&id_documento=<= $docu['id_documentos'] ?>">
                                <i class="fas fa-minus-circle" style="color: red" title="dar de baja el documento"></i>
                            </a>-->
                        </div>
                    <?php endif ?>
                <?php endif; ?>

            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
    </div>
<?php endforeach; ?>
<?php endif;?>

<input id="pruebaBaja"/>
<label>Documento dado de Baja</label>   
<?php else: ?>
    <h1>Todos los documentos</h1>
    <?php foreach($todos_documentos as $documentos_admin): ?>
        <?php if ($documentos_admin['baja'] == 1): ?>
            <div class="documento">
                <div class="documentoBaja nombreDocumento" title="Nombre del Documento"><?= $documentos_admin['nombre_documento'] ?></div>
                <div class="fechaInicio" title="Fecha de Inicio" ><?= $documentos_admin['fecha_inicio'] ?></div>
                <div class="fechaFin" title="Fecha de Fin"><?= $documentos_admin['fecha_fin'] ?></div>
                <a class="modificarDocumento" href="index.php?comando=dar_alta&id_documento=<?= $documentos_admin['id_documentos'] ?>" >
                   <i class="fas fa-plus-circle" style="color: green" title="dar de alta el documento"></i>
                </a>
            </div>
        <?php else : ?>
            <div class="documento">
                <div class="nombreDocumento" title="Nombre del Documento"><?= $documentos_admin['nombre_documento'] ?></div>
                <div class="fechaInicio" title="Fecha de Inicio" ><?= $documentos_admin['fecha_inicio'] ?></div>
                <div class="fechaFin" title="Fecha de Fin"><?= $documentos_admin['fecha_fin'] ?></div>
                <a class="verContenido" href="index.php?comando=cargar_contenido&id_documento=<?= $documentos_admin['id_documentos'] ?>">
                    <i class="fas fa-eye" title="ver documento"></i></a>
                <a class="modificarDocumento" href="index.php?comando=dar_baja&id_documento=<?= $documentos_admin['id_documentos'] ?>">
                    <i class="fas fa-minus-circle" style="color: red" title="dar de baja el documento"></i>
                </a>
            </div>
        <?php endif ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php
$contenido = ob_get_clean();
require '../app/vistas/plantilla.php';
?>

