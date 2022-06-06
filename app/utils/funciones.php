<?php
/**
 * Función que comprueba que los datos son válidos.
 * @param type $datos
 * @return dato vaálido
 */
function limpiar_datos($datos){
    return trim(htmlentities($datos));
}

function guardar_mensaje($mensaje) {
    $_SESSION['mensaje_flash'][] = $mensaje;
}

function mostrar_mensaje() {
    if (isset($_SESSION['mensaje_flash'])) {
        foreach ($_SESSION['mensaje_flash'] as $mensaje)
        {
            echo "<h3 id=\"mensaje_flash\">$mensaje</h3>";
        }
        
        unset($_SESSION['mensaje_flash']);
    }
}
/**
 * Función que se encarga de conectarse con la base de datos gestion_calidad
 * 
 * @return $conexion que es la conexiond e la base de datos
 */
function conexion_bd(){
    
    $conexion = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_BD);
    mysqli_set_charset($conexion, 'utf8'); 
    if ($conexion->connect_error) {
        die("La conexión falló: " . $conexion->connect_error) . "<br>";
    }
    
    return $conexion;
 
}

/**
 * Función que comprueba que si el email y la contraseña introducida por el 
 * usuario son iguales a las guardadas en la base de datos, para poder iniciar 
 * sesión. 
 * 
 * @param type $email_usuario
 * @param type $password
 * @return boolean
 */
function comprobar_inicio_sesion($email_usuario, $password){
    
    $conexion = conexion_bd();
    
    $email_usuario = validar_datos($email_usuario);
    $password = validar_datos($password);
    
    if(!filter_var($email_usuario,FILTER_VALIDATE_EMAIL)){
        return false;
    }
    
    $consulta = $conexion->prepare("SELECT * FROM personal where email = ? "
            . "and password = ?");
    
    if(!$consulta){
        echo "Error en la consulta".$consulta->error;
        die();
    }
    
    $consulta->bind_param("ss",$email_usuario, $password);
    
    if(!$consulta->execute()){
        echo "Ha ocurrido un error en la select".$consulta->error;
        die();
    }
    
    return $consulta->get_result()->fetch_assoc();
    
}

/**
 * Función para comprobar que la cookie es la misma que la de la base de datos.
 * @param type $codigo_cookie
 * @return type
 */
function obtener_cookie($cookie){
    
    $cookie = validar_datos($cookie);
    
    $conexion = conexion_bd();
    
    $consulta = $conexion->prepare("Select * from personal where cookie = ?");
    
    if(!$consulta){
        echo "Error en la consulta".$consulta->error;
        die();
    }
    
    $consulta->bind_param("s",$cookie);
    
    if(!$consulta->execute()){
        echo "Ha ocurrido un error en la select".$consulta->error;
        die();
    }
    
    return $consulta->get_result()->fetch_assoc();
    
}