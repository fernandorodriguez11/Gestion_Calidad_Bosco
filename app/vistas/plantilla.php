<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
 <link type="text/css" href="../web/jquery-ui-1.12.1.custom/jquery-ui.css" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="../web/css/sweetalert2.min.css">
        <link type="text/css" href="../web/css/estilo_indice.css" rel="stylesheet">
        <script type="text/javascript" src="../web/js/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="../web/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
        <script src="../web/js/sweetalert2.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="../web/js/js_inicio.js"></script>
        <script type="text/javascript" src="../web/js/agregar_contenido.js"></script>
        <script type="text/javascript" src="../web/js/cambia_passwords.js"></script>
        <script type="text/javascript" src="../web/js/muestra_asignaturas.js"></script>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body class="fondo">
        <?php if(!Sesiones::existe_variable_sesion()){
                header('location: index.php');
            }
        ?>
        <div id="contenedor" class="container">
            <div id="portada" class="row">
                <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12 col-12 portada">
                    <img id="imagen_portada" src="imagenes/fotoPortada.jpg"/>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12 col-12 portada">
                    <br>
                </div>
            </div>

            <div id="contenido" class="row">
                <?php if (!isset($_GET['id_usuario'])): ?>
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-6 columna_izquierda">
                <?php else: ?>
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-6 columna_izquierda">
                <?php endif ?>
                    <ul id="menu" class="ensanchar_a_div">

                        <li class="color_fondo">
                            <div>
                                <?php if (!isset($_GET['id_usuario'])): ?>
                                    <a href="index.php?comando=bienvenido">Inicio</a>
                                <?php else: ?>
                                    <a name="index.php?comando=bienvenido">Inicio</a>
                                <?php endif ?>
                            </div>
                        </li>

                        <li class="color_fondo">
                            <div>
                                <?php if (!isset($_GET['id_usuario'])): ?>
                                    <a href="index.php?comando=cargar_documento">Documentos</a>
                                <?php else: ?>
                                    <a name="index.php?comando=cargar_documento">Documentos</a>
                                <?php endif ?>
                            </div>
                        </li>
                        
                        <li class="color_fondo">
                            <div>
                                <?php if (!isset($_GET['id_usuario'])): ?>
                                    <a href="index.php?comando=datos_personales">Perfil</a>
                                <?php else: ?>
                                    <a name="index.php?comando=datos_personales">Perfil</a>
                                <?php endif ?>
                            </div> 
                        </li>
                        <?php if(Sesiones::saber_sies_admin() == true): ?>
                        <li class="color_fondo">
                            <div>
                                <?php if (!isset($_GET['id_usuario'])): ?>
                                    <a href="index.php?comando=gestion_documentos">Mantenimiento</a>
                                <?php else: ?>
                                    <a name="index.php?comando=gestion_documentos">Mantenimiento</a>
                                <?php endif ?>
                            </div>
                        
                         <ul>
                             <li>
                                <div>
                                    <?php if (!isset($_GET['id_usuario'])): ?>
                                        <a href="index.php?comando=gestion_documentos">Gestión Documentos</a>
                                    <?php else: ?>
                                        <a name="index.php?comando=gestion_documentos">Gestión Documentos</a>
                                    <?php endif ?>
                                </div>
                            </li>

                             <li>
                                 <div>
                                    <?php if (!isset($_GET['id_usuario'])): ?>
                                        <a href="index.php?comando=importar_datos">Importar Datos</a>
                                    <?php else: ?>
                                        <a name="index.php?comando=importar_datos">Importar Datos</a>
                                    <?php endif ?>
                                </div>
                            </li>
                         </ul>
                        </li>
                        
                        <li class="color_fondo">
                            <div>
                                <?php if (!isset($_GET['id_usuario'])): ?>
                                    <a href="">Listados Y Estadísticas</a>
                                <?php else: ?>
                                    <a name="">Listados Y Estadísticas</a>
                                <?php endif ?>
                            </div>
                        
                         <ul>
                             <li>
                                <div>
                                    <?php if (!isset($_GET['id_usuario'])): ?>
                                        <a href="index.php?comando=listado_control_final_evaluacion">Listado Control Final de Evaluación</a>
                                    <?php else: ?>
                                        <a name="index.php?comando=listado_control_final_evaluacion">Listado Control Final de Evaluación</a>
                                    <?php endif ?>
                                </div>
                            </li>
                         </ul>
                        </li>
                        <?php endif;?>

                        <li class="color_fondo">
                            <div>
                                <?php if (!isset($_GET['id_usuario'])): ?>
                                    <a href="index.php?comando=cerrar_sesion">Cerrar Sesión</a>
                                <?php else: ?>
                                    <a name="index.php?comando=cerrar_sesion">Cerrar Sesión</a>
                                <?php endif ?>
                            </div>
                        </li>
                    </ul>
                </div>
                
                <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 centrar_texto">
                        <?php echo $contenido?>
                </div>
                
                <div class="col-xl-3 col-lg-8 col-md-9 col-sm-10 col-12 columna_derecha">

                    <div id="datepicker" class="inicio_calendario"></div>

                </div>

            </div>
        </div>
    </body>
</html>

