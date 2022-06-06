<?php
ob_start(); //Envía la salida a un buffer (memoria) en vez de al usuario
?>
        <h3 class="centrar_texto">Gestión Documentos</h3>
        <?=        mostrar_mensaje()?>
        <form action="" method="POST">
            <div>
                <fieldset>
                    <legend class="clase_legend">Datos</legend>
                    <input type="checkbox" name="baja" /> Baja<br>
                    <label class="gestion_documentos_label"><i class="fas fa-file-alt"></i> Nombre</label>
                    <input class="gestion_documentos_input" type="text" name="documento_nombre"/><br><br>
                    <label class="gestion_documentos_label"><i class="fas fa-tags"></i> Rol</label><br> 

                    <!-- Aqui cargarán los Roles, Cursos y Asignaturas que hayan guardados en la base de datos  -->

                    <select id="selectpicker_roles" class="selectpicker" multiple data-live-search="true">
                        <?php foreach ($lista_roles as $rol): ?>
                        <option><?= $rol->getRol() ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" id="documento_roles" class="ocultar" name="documento_roles"/><br>
                    
                    <label class="gestion_documentos_label"><i class="fas fa-tags"></i> Cursos</label><br> 
                    <select id="selectpicker_cursos" class="selectpicker" multiple data-live-search="true">
                        <?php foreach ($lista_cursos as $cursos): ?>
                        <option><?= $cursos->getNombre_curso(); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" id="documento_cursos" class="ocultar" name="documento_cursos"/><br>
                    
                    <label class="gestion_documentos_label"><i class="fas fa-tags"></i> Asignaturas</label><br> 
                    <select id="selectpicker_asignaturas" class="selectpicker" multiple data-live-search="true">
                        <?php foreach ($lista_asignaturas as $asignaturas): ?>
                        <option><?= $asignaturas->getNombre_asignatura(); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" id="documento_asignaturas" class="ocultar" name="documento_asignaturas"/>
                
                </fieldset>
            </div>
            <div>
                <fieldset>
                    <legend class="clase_legend">Eventos</legend>
                    <div id="calendarios">
                        <p class="gestion_documentos_label"><i class="fas fa-calendar-day calendario_verde"></i> Fecha Inicio</p>
                       
                        <p class="gestion_documentos_label"> <i class="fas fa-calendar-day calendario_rojo"></i> Fecha Fin</p><br>
                        

                    </div>

                    <div id="datepickers">
                        <div id="datepicker1" class="inicio_calendario1"></div>
                        <input id="documento_fecha_inicio" class="ocultar" type="text" name="documento_fecha_inicio"/>
                        <div id="datepicker2" class="inicio_calendario2"></div>
                        <input id="documento_fecha_fin" type="text" class="ocultar" name="documento_fecha_fin"/>

                    </div>
                </fieldset>
            </div>
            <div>
                <fieldset>
                    <legend class="clase_legend_contenido">Contenido</legend>
                    <div>
                        <label class="gestion_documentos_label"><i class="fas fa-file-signature"></i> Titulo</label><br>
                        <input id="titulo" class="gestion_documentos_input" type="text" name="documento_titulo"/><br>
                        <label class="gestion_documentos_label"><i class="fas fa-align-justify"></i> Descripción</label><br>
                        <textarea id="descripcion" rows="5" class="gestion_documentos_input" name="documento_tipo"></textarea><br><br>
                        <label class="gestion_documentos_label"><i class="fas fa-sliders-h"></i> Tipo</label>
                    <select id="selectpicker_tipos" class="selectpicker tipos" data-live-search="true">
                        <option disabled selected>Ninguno seleccionado</option>
                        <?php foreach ($tipos_preguntas as $tipos): ?>
                        <option><?= $tipos->getTipo(); ?></option>
                        <?php endforeach; ?>
                    </select>
                        <br><br><input id="todoContenido" type="text" class="ocultar" name="arrayContenido"> 
                       <button type="button" id="agregar_contenido">Agregar contenido</button><br>
                       
                       <fieldset>
                            <legend class="clase_legend_contenido">Contenido Añadido</legend>
                            <div class="documento">
                                <div id="title" class="titu"></div>
                                <p></p>
                                <div id="type" class="tip"></div>
                            </div>
                       </fieldset>
                        
                    </div>
                </fieldset>
            </div>
            <br>

            <input id="documento_guardar" name="button_registro_registrar" type="submit" value="Registrar">
        </form>
<?php
//Guarda el contenido del buffer (el código html y php ya ejecutado) en la variable contenido
$contenido = ob_get_clean();
require '../app/vistas/plantilla.php';
?>