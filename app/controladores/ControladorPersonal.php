<?php

class ControladorPersonal {

    /**
     * Función que comprueba si existe la variable sesión del usuario. Si
     * existe te lleva a la vista vienvenido y sino a la pantalla de loggin.
     */
    public function inicio() {

        if (Sesiones::existe_variable_sesion()) {
            header('location: index.php?comando=bienvenido');
        } else {
            require 'index.html';
        }
    }

    /*
     * Función que comprueba si el email y la contraseña que introduce el 
     * usuario existen en la base de datos para poder iniciar sesión.
     */
    public function loggin() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $personal = new Personal();

            if (!$personal->obtener_email($_POST['email_usuario'])) {
                header('location: index.php?comando=inicio');
                //Modificar cuando trabajemos con contraseñas codificadas
            } else if ($_POST['password_usuario'] == $personal->getPassword()) {
                Sesiones::iniciarSesion($personal);
                
                if(isset($_POST['cookie'])){
                    setcookie('codigo_cookie', $personal->getCookie(), time()+60*60*24*31);
                }
                
                header('Location: index.php?comando=bienvenido');
            } else {
                header('Location: index.php');
            }
        }
    }

    /*
     * Función encargad de mostrar la pantalla de inicio una vez el usuario
     * ha iniciado sesión
     */
    public function bienvenido() {

        require '../app/vistas/bienvenido.php';
    }

    /*
     * Función para cerrar sesión
     */
    public function cerrar_sesion() {
        Sesiones::cerrarSesion();
        setcookie('codigo_cookie','',time()-10);
        header('location: index.php');
    }

    /*
     * Función para enviar un documento por mail
     */
    public function enviar_mail() {


// Varios destinatarios
        $para = "fernan.sniper@gmail.com";

// título
        $título = 'Comprobación';

// mensaje
        $mensaje = '
<html>
<head>
  <title>Confirmación de correo</title>
</head>
<body>
  <p>Documento a rellenar</p>
  <a href="https://webjuanpiqueras94.000webhostapp.com/Calidad_Bosco/web/index.php?comando=cargar_contenido&id_documento=1&id_usuario=1">Documento 1</a>
</body>
</html>
';

// Para enviar un correo HTML, debe establecerse la cabecera Content-type
        $cabeceras = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Cabeceras adicionales
        $cabeceras .= 'Para: Fernando <fernan.sniper@gmail.com>' . "\r\n";
        $cabeceras .= 'De: Administrador <administracion@iesjuanboco.com>' . "\r\n";

// Enviarlo
        if (mail($para, $título, $mensaje, $cabeceras)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Función para mostrar los datos del usuario en una vista. Obteniendo
     * los roles y cursos del usuario.
     */
    public function datos_personales() {

        $usuario = Sesiones::obtener_id();

        $personal = new Personal();

        $personal_roles = $personal->obtener_roles_usuarios($usuario);
        $personal_cursos = $personal->obtener_cursos_usuarios($usuario);
        
        require '../app/vistas/datos_personales.php';
    }
    
    /**
     * Función llamada por ajax que recibe dos parámetros por post que son 
     * la contraseña antigua y la nueva para poder hacer un cambio de 
     * la contraseña.
     */
    public function change_password(){
        
        $personal = new Personal();
        
        $actual = $_POST['antigua'];
        $nueva = $_POST['confirmar'];
        
        if($personal->cambiar_password($actual, $nueva) == false){
            echo('error');
        }else{
            echo('correcto');
        }
    }
    
    /**
     * Función llamada por ajax que recibe un parámetro por get el nombre del 
     * curso para obtener sus asignaturas desde la bd.
     */
    public function obten_asignatura_cursos(){
        
        $personal = new Personal();
        
        $usuario = Sesiones::obtener_id();
        
        if($personal->obtener_asignaturas_usuarios_curso($usuario, $_GET['curso'])
                == false){
            echo 'error';
        }else{
            $personal_asignaturas = $personal->obtener_asignaturas_usuarios_curso($usuario, $_GET['curso']);
            print_r(json_encode($personal_asignaturas));
        }
    }

}
