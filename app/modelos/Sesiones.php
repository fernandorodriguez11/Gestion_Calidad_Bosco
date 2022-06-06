<?php

class Sesiones{
    
    /*
     * Función para comvertir el objeto Personal en una variable sesión 
     */
    static public function iniciarSesion($usuario){
        $_SESSION['usuario'] = serialize($usuario);
    }
    
    /*
     * Función para eliminar la variable sesión
     */
    static public function cerrarSesion(){
        session_destroy();
        session_unset();
    }
    
    /*
     * Función para comprobar si existe la variable sesión
     */
    static public function existe_variable_sesion(){
        return isset($_SESSION['usuario']);
    }
    
    /*
     * Función para obtener el id del usuario que inicia sesión
     */
    static public function obtener_id(){
        $usuario = unserialize($_SESSION['usuario']);
        return $usuario->getId();
    }
    
    /*
     * Función para obtener el nombre del usuario que inicia sesión
     */
    static public function obtener_nombre(){
        $usuario = unserialize($_SESSION['usuario']);
        return $usuario->getNombre();
    }
    
    /*
     * Función para obtener el apellidos del usuario que inicia sesión
     */
    static public function obtener_apellidos(){
        $usuario = unserialize($_SESSION['usuario']);
        return $usuario->getApellidos();
    }
    
    /*
     * Función para obtener el email del usuario que inicia sesión
     */
    static public function obtener_email(){
        $usuario = unserialize($_SESSION['usuario']);
        return $usuario->getEmail();
    }
    
	/*
	 * Función para saber si el usuario es administrador para controlar una ventana
	*/
    static public function saber_sies_admin(){
        
        $usuario = unserialize($_SESSION['usuario']);
        
        $id = $usuario->getId();
        
        $personal = new Personal();
        
        $roles = $personal->obtener_roles_usuarios($id);
        
        foreach($roles as $rol){
            
            if ($rol['rol'] == "Administrador"){
                return true;
            }
            
        }
    }
}

