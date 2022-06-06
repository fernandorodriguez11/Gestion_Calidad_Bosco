<?php

session_start();

require '../app/controladores/ControladorPersonal.php';
require '../app/controladores/ControladorDocumentos.php';
require '../app/controladores/ControladorImportacion.php';
require '../app/modelos/Personal.php';
require '../app/modelos/Sesiones.php';
require '../app/modelos/Documentos.php';
require '../app/modelos/DocumentosLineas.php';
require '../app/modelos/TiposPreguntas.php';
require '../app/modelos/Respuestas.php';
require '../app/modelos/Rol.php';
require '../app/modelos/Asignaturas.php';
require '../app/modelos/Cursos.php';
require '../app/modelos/Respondido.php';
require '../app/utils/config.php';
require '../app/utils/funciones.php';
require '../app/utils/dompdf/autoload.inc.php';

    
// Enrutamiento
$map = array(
    'inicio'=>array('controlador'=>'ControladorPersonal','metodo'=>'inicio', 'publica' => true),
    'loggin'=>array('controlador'=>'ControladorPersonal','metodo'=>'loggin', 'publica' => true),
    'bienvenido'=>array('controlador'=>'ControladorPersonal','metodo'=>'bienvenido', 'publica' => false),
    'obten_asignatura_cursos'=>array('controlador'=>'ControladorPersonal','metodo'=>'obten_asignatura_cursos','publica' => false),
    'datos_personales'=>array('controlador'=>'ControladorPersonal','metodo'=>'datos_personales','publica' => false),
    'change_password'=>array('controlador'=>'ControladorPersonal','metodo'=>'change_password','publica' => false),
    'cerrar_sesion'=>array('controlador'=>'ControladorPersonal','metodo'=>'cerrar_sesion','publica' => false),
    'enviar_mail'=>array('controlador'=>'ControladorPersonal','metodo'=>'enviar_mail','publica' => false),
    'gestion_documentos'=>array('controlador'=>'ControladorDocumentos','metodo'=>'gestion_documentos','publica' => false),
    'insertar_documento'=>array('controlador'=>'ControladorDocumentos','metodo'=>'insertar_documento','publica' => false),
    'cargar_documento'=>array('controlador'=>'ControladorDocumentos','metodo'=>'cargar_documento','publica' => false),
    'carga_datos_documento'=>array('controlador'=>'ControladorDocumentos','metodo'=>'carga_datos_documento','publica' => false),
    'cargar_contenido'=>array('controlador'=>'ControladorDocumentos','metodo'=>'cargar_contenido','publica' => false),
    'guardar_respuestas'=>array('controlador'=>'ControladorDocumentos','metodo'=>'guardar_respuestas','publica' => false),
    'listado_control_final_evaluacion'=>array('controlador'=>'ControladorDocumentos','metodo'=>'listado_control_final_evaluacion','publica' => false),
    'dar_alta'=>array('controlador'=>'ControladorDocumentos','metodo'=>'dar_alta','publica' => false),
    'dar_baja'=>array('controlador'=>'ControladorDocumentos','metodo'=>'dar_baja','publica' => false),
    'descargar_documento'=>array('controlador'=>'ControladorDocumentos','metodo'=>'descargar_documento','publica' => false),
    'carga_contestado'=>array('controlador'=>'ControladorDocumentos','metodo'=>'carga_contestado','publica' => false),
    'esta_fuera_rango'=>array('controlador'=>'ControladorDocumentos','metodo'=>'esta_fuera_rango','publica' => false),
    'importar_datos'=>array('controlador'=>'ControladorImportacion','metodo'=>'importar_datos','publica' => false)
    );


//Comprobamos la cookie de usuario. Si tiene cookie pero todavía no lo hemos identificado,
//iniciamos sesión
if(!Sesiones::existe_variable_sesion() && isset($_COOKIE['codigo_cookie'])){
    $usuario = new Personal();
    if($usuario->obtener_cookie($_COOKIE['codigo_cookie']))
    {
        Sesiones::iniciarSesion($usuario);
        //Si el comando es inicio lo cambiamos a listar_mensajes para que no le aparezca el formulario de login
        if($comando=='inicio')
            $comando='bienvenido';
    }
    else
    {
        guardar_mensaje(("No puedes acceder a esta sección de la web"));
        header("location: index.php");
        die();
    }
}

//Si ya ha iniciado sesión e intenta entrar en inicio lo redirigimos a listar_mensajes
if(Sesiones::existe_variable_sesion() && $map[$_GET['comando']]=='inicio')
{
    $comando = 'bienvenido';
}

/*
//Comprobamos la sesión. Si no ha iniciado sesión y la página no es pública redirigimos a index.php
if(!Sesiones::existe_variable_sesion() && !$map[$_GET['comando']]['publica']){
    //guardar_mensaje("Debes iniciar sesión para acceder a la página solicitada");
    //header("location: index.php");
    $comando = 'inicio';
    die();
}
*/

//Parseo de la ruta.
//Si el comando no existe o está vacío nos manda al inicio 
if(!isset($_GET['comando']) || empty($_GET['comando'])){
    $comando = 'inicio';
}
// Si no está en el mapa que es nuestro array muestra un mensaje de error.
elseif(!isset($map[$_GET['comando']])){
    //La pagina no existe
    guardar_mensaje("esta pagina no existe ". $_GET['comando']);
    $comando = 'inicio';
    
}else{
    $comando = $_GET['comando'];
}

//Ejecución del controlador.
$nombre_clase = $map[$comando]['controlador'];
$nombre_metodo = $map[$comando]['metodo'];

$objeto = new $nombre_clase();
$objeto->$nombre_metodo();