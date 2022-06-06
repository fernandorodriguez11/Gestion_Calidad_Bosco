<?php

class Rol {

    private $id_rol;
    private $rol;

    function getId_rol() {
        return $this->id_rol;
    }

    function getRol() {
        return $this->rol;
    }

    function setId_rol($id_rol) {
        $this->id_rol = $id_rol;
    }

    function setRol($rol) {
        $this->rol = $rol;
    }

    /*
     * Función para obtener el rol segun el id_rol pasado por parámetros.
     */
    public function obtener_rol($id_rol) {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare('Select * from rol where id_rol = ?');

        if (!$consulta) {
            echo 'Error en la consulta: ' . $conexion->error;
            die();
        }

        if (!$consulta->bind_param('i', $id_rol)) {
            echo 'Error en la consulta: ' . $conexion->error;
            die();
        }

        if (!$consulta->execute()) {
            echo "Error al ejecutar la consulta " . $consulta->error;
            die();
        }

        if (!$result = $consulta->get_result()->fetch_assoc()) {
            return false;
        }

        //Asigna los valores a las propiedades del objeto
        $this->setId_rol($result['id_rol']);
        $this->setRol($result['rol']);

        $consulta->close();
        $conexion->close();

        return true;
    }
    
    /*
     * Función para obtener los roles del usuario. El id del usuario se pasa por
     * parámetros.
     */
    public function obtener_rol_por_usuario($id_personal) {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare('Select rol.rol from '
                . 'personal inner join personal_rol on personal.id_personal = personal_rol.id_personal'
                . ' inner join rol on personal_rol.id_rol = rol.id_rol'
                . ' where personal.id_personal = ?');

        if (!$consulta) {
            echo 'Error en la consulta: ' . $conexion->error;
            die();
        }

        if (!$consulta->bind_param('i', $id_personal)) {
            echo 'Error en la consulta: ' . $conexion->error;
            die();
        }

        if (!$consulta->execute()) {
            echo "Error al ejecutar la consulta " . $consulta->error;
            die();
        }

        if (!$result = $consulta->get_result()->fetch_all(MYSQLI_ASSOC)) {
            return false;
        }

        $consulta->close();
        $conexion->close();

        return $result;
    }

    /*
     * Función para obtener los roles cuyos nombres sean igual al pasado
     * por parámetros.
     */
    public function obtener_rol_por_nombre($rol) {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare('Select * from rol where rol = ?');

        if (!$consulta) {
            echo 'Error en la consulta: ' . $conexion->error;
            die();
        }

        if (!$consulta->bind_param('s', $rol)) {
            echo 'Error en la consulta: ' . $conexion->error;
            die();
        }

        if (!$consulta->execute()) {
            echo "Error al ejecutar la consulta " . $consulta->error;
            die();
        }

        if (!$result = $consulta->get_result()->fetch_assoc()) {
            return false;
        }

        //Asigna los valores a las propiedades del objeto
        $this->setId_rol($result['id_rol']);
        $this->setRol($result['rol']);

        $consulta->close();
        $conexion->close();

        return true;
    }

    /**
     * Función que devuelve todos los roles en un array.
     * 
     * @return \Rol
     */
    function obtener_todos_roles() {

        $roles = array();

        $conexion = conexion_bd();

        $consulta = 'Select * from rol group by rol.rol';

        if (!$result = $conexion->query($consulta)) {
            echo "Error en la consulta: " . $conexion->error;
            die();
        }

        $filas = $result->fetch_all(MYSQLI_ASSOC);

        $conexion->close();

        foreach ($filas as $fila) {
            $rol = new Rol();
            $rol->setId_rol($fila['id_rol']);
            $rol->setRol($fila['rol']);
            $roles[] = $rol;
        }

        return $roles;
    }

	/**
     * Función que devuelve todos los documentos con respuestas de los roles en un array.
     * 
     * @return \Rol
     */
    function obtener_todos_rolesRes() {

        $roles = array();

        $conexion = conexion_bd();

        $consulta = 'SELECT rol.* FROM rol 
inner join rol_documentos on rol.id_rol = rol_documentos.id_rol 
inner JOIN documentos on rol_documentos.id_documentos = documentos.id_documentos
inner join respondido on rol.id_rol = respondido.id_rol
group by rol.id_rol';

        if (!$result = $conexion->query($consulta)) {
            echo "Error en la consulta: " . $conexion->error;
            die();
        }

        $filas = $result->fetch_all(MYSQLI_ASSOC);

        $conexion->close();

        return $filas;
    }
	
    /*
     * Funcion para borrar roles de la base de datos.
     
    function borrar_roles() {
        $conexion = conexion_bd();

        $sql = "DELETE FROM rol";

        $conexion->query($sql);
        $conexion->close();
    }
*/
    /*
     * Función para insertar rol en la base de datos.
     */
    function inserta_rol() {
        $conexion = conexion_bd();

        $consulta = $conexion->prepare('INSERT INTO rol (rol) VALUES (?)');
        if (!$consulta) {
            echo 'error en la consulta: ' . $conexion->error;
            die();
        }

        $rol_unico = $this->getRol();
        $rol_unico = $rol_unico . "";

        if (!$consulta->bind_param("s", $rol_unico)) {
            echo ("Error: " . $conexion->error);
            die();
        }

        if (!$consulta->execute()) {
            echo 'error en la ejecucion ' . $consulta->error;
            die();
        }

        $num_filas_modificadas = $conexion->affected_rows;
        $conexion->close();
        $consulta->close();

        if ($num_filas_modificadas == 1) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Función para modificar el rol en la base de datos.
     */
    function modificar_rol($rol, $id_rol) {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare('Update rol set rol = ? where id_rol = ?');

        if (!$consulta) {
            echo 'error en la consulta: ' . $conexion->error;
            die();
        }

        if (!$consulta->bind_param("si", $rol, $id_rol)) {
            echo ("Error: " . $conexion->error);
            die();
        }

        if (!$consulta->execute()) {
            echo 'error en la ejecucion ' . $consulta->error;
            die();
        }

        $num_filas_modificadas = $conexion->affected_rows;
        $conexion->close();
        $consulta->close();

        if ($num_filas_modificadas == 1) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Función para borrar un rol de la base de datos.
     */
    function borrar_rol($id_rol) {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare('Delete From rol where id_rol = ?');

        if (!$consulta) {
            echo 'Error en la consulta: ' . $conexion->error;
            die();
        }

        if (!$consulta->bind_param('i', $id_rol)) {
            echo 'Error en la consulta: ' . $conexion->error;
            die();
        }

        if (!$consulta->execute()) {
            echo 'Error al borrar rol: ' . $consulta->error;
            die();
        }

        $filas_borradas = $conexion->affected_rows;

        $consulta->close();
        $conexion->close();

        if ($filas_borradas != 1) {
            return false;
        } else {
            return true;
        }
    }

    /*
     * Función para eliminar un rol vinculado a un documento
     */
    public static function eliminar_rol_documento($id_documentos) {
     
        $conexion = conexion_bd();
        
        $consulta = $conexion->prepare("Delete From rol_documentos where id_documentos = ?");
        
        if(!$consulta){
            echo 'Error en la consulta: '. $conexion->error;
            die();
        }
        
        if(!$consulta->bind_param('i',$id_documentos)){
            echo 'Error en la consulta: '. $conexion->error;
            die();
        }
        
        if (!$consulta->execute()) {
            echo 'Error en la ejecución' . $consulta->error;
            die();
        }

        $num_filas_borradas = $conexion->affected_rows;
        $conexion->close();
        $consulta->close();

        if ($num_filas_borradas == 1) {
            return true;
        } else {
            return false;
        }
        
    }
}
