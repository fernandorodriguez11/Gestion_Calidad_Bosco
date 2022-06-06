<?php

class Personal
{

    //<editor-fold defaultstate="collapsed" desc="Variables">
    private $id;
    private $nombre;
    private $apellidos;
    private $email;
    private $password;
    private $cookie;

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Getters">
    function getId()
    {
        return $this->id;
    }

    function getNombre()
    {
        return $this->nombre;
    }

    function getApellidos()
    {
        return $this->apellidos;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getPassword()
    {
        return $this->password;
    }

    function getCookie()
    {
        return $this->cookie;
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Setters">
    function setId($id)
    {
        $this->id = $id;
    }

    function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    function setEmail($email)
    {
        $email = $email;
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        }
    }

    function setPassword($password)
    {
        $this->password = $password;
    }

    function setCookie($cookie)
    {
        $this->cookie = $cookie;
    }

    //</editor-fold>

    /**
     * Función para obtener el id del usuario 
     */
    public function obtener_id()
    {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare("select * from personal where email = ?");

        if (!$consulta) {
            echo "Error en la consulta preparada: " . $conexion->error;
            die();
        }

        if (!$consulta->bind_param("s", $email)) {
            echo "Error en el bind_param " . $conexion->error;
            die();
        }

        if (!$consulta->execute()) {
            echo "Error al ejecutar la consulta " . $conexion->error;
            die();
        }

        if (!$result = $consulta->get_result()->fetch_assoc()) {
            return false;
        }

        //Asigna los valores a las propiedades del objeto
        $this->setId($result['id_personal']);
        $this->setNombre($result['nombre']);
        $this->setEmail($result['email']);
        //$this->setApellidos($result['apellidos']);
        $this->setPassword($result['password']);
        $this->setCookie($result['cookie']);

        $consulta->close();
        $conexion->close();

        return true;
    }

    public function loggin_por_id()
    {
        $conexion = conexion_bd();

        $consulta = $conexion->prepare("select * from personal where id_personal = ?");

        if (!$consulta) {
            echo "Error en la consulta preparada: " . $conexion->error;
            die();
        }

        $id_personal = $this->getid();

        if (!$consulta->bind_param("i", $id_personal)) {
            echo "Error en el bind_param " . $conexion->error;
            die();
        }

        if (!$consulta->execute()) {
            echo "Error al ejecutar la consulta " . $conexion->error;
            die();
        }

        if (!$result = $consulta->get_result()->fetch_assoc()) {
            return false;
        }

        //Asigna los valores a las propiedades del objeto
        $this->setId($result['id_personal']);
        $this->setNombre($result['nombre']);
        $this->setEmail($result['email']);
        //$this->setApellidos($result['apellidos']);
        $this->setPassword($result['password']);
        $this->setCookie($result['cookie']);

        $consulta->close();
        $conexion->close();

        return true;
    }

    /**
     * Función que pasando por parámetros el email obtenemos todos sus datos.
     * 
     * @param type $email
     * @return boolean
     */
    public function obtener_email($email)
    {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $conexion = conexion_bd();

        $consulta = $conexion->prepare("select * from personal where email = ?");

        if (!$consulta) {
            echo "Error en la consulta preparada: " . $conexion->error;
            die();
        }

        if (!$consulta->bind_param("s", $email)) {
            echo "Error en el bind_param " . $conexion->error;
            die();
        }

        if (!$consulta->execute()) {
            echo "Error al ejecutar la consulta " . $conexion->error;
            die();
        }

        if (!$result = $consulta->get_result()->fetch_assoc()) {
            return false;
        }

        //Asigna los valores a las propiedades del objeto
        $this->setId($result['id_personal']);
        $this->setNombre($result['nombre']);
        $this->setEmail($result['email']);
        //$this->setApellidos($result['apellidos']);
        $this->setPassword($result['password']);
        $this->setCookie($result['cookie']);

        $consulta->close();
        $conexion->close();

        return true;
    }

    /**
     * Función para obtener la cookie de la base de datos.
     */
    public function obtener_cookie($cookie)
    {
        //Comprobamos el el parámetro de entrada sea un número
        $cookie = $cookie;
        $conexion = conexion_bd();
        if (!$consulta = $conexion->prepare("select * from personal where cookie=?")) {
            echo "Error en la consulta preparada: " . $conexion->error;
            die();
        }
        if (!$consulta->bind_param('s', $cookie)) {
            echo "Error en el bind_param: " . $conexion->error;
            die();
        }
        if (!$consulta->execute()) {
            echo "Error al ejecutar la consulta: " . $conexion->error;
            die();
        }
        //Obtenemos un array asociativo con todas las filas
        if (!$result = $consulta->get_result()->fetch_assoc()) {
            return false;   //Si no existe el usuario con ese id devolvemos false;
        }
        //Asignar los valores a las propiedades del objeto
        $this->setId($result['id_personal']);
        $this->setNombre($result['nombre']);
        $this->setEmail($result['email']);
        $this->setPassword($result['password']);
        $this->setCookie($result['cookie']);

        //Cerramos los recursos abiertos
        $conexion->close();
        $consulta->close();

        return true;
    }

    /*
     * Función para insertar un personal en la base de datos.
     */
    public function Insertar()
    {
        $conexion = conexion_bd();

        $consulta_preparada = $conexion->prepare("INSERT INTO personal "
            . "(nombre, apellidos, email, password, cookie) "
            . "values (?,?,?,?,?)");
        if (!$consulta_preparada) {
            echo "Error en la consulta" . $consulta_preparada->error;
            die();
        }

        $nombre = $this->getNombre();
        $apellidos = $this->getApellidos();
        $email = $this->getEmail();
        $password = $this->getPassword();
        $cookie = $this->getCookie();

        $consulta_preparada->bind_param('sssss', $nombre, $apellidos, $email, $password, $cookie);
        if (!$consulta_preparada->execute()) {
            //Si entra aquí es que ha devuelto falso y ha fallado la consulta
            guardar_mensaje("<b>Ha ocurrido un error en la funcion Insertar de la clase Personal.php</b> <br><br>" . $consulta_preparada->error);
            die();  //Paramos la ejecución;
        }
        $num_filas_insertadas = $consulta_preparada->affected_rows;
        $conexion->close();
        $consulta_preparada->close();
        if ($num_filas_insertadas == 1) {
            return true;
        } else {
            guardar_mensaje("Ha habido un error al guardar el Personal");
            return false;
        }
    }

    /*
     * Función para insertar en la tabla compuesta por roles y personal.
     */
    public function InsertaRelacionPersonalRol($email, $nombre_rol)
    {

        $rol = new Rol();

        $this->obtener_email($email);
        $rol->obtener_rol_por_nombre($nombre_rol);

        $conexion = conexion_bd();

        $consulta_preparada = $conexion->prepare("INSERT INTO personal_rol "
            . "(id_personal, id_rol) "
            . "values (?,?)");
        if (!$consulta_preparada) {
            echo "Error en la consulta" . $consulta_preparada->error;
            die();
        }

        $id_personal = $this->getId();
        $id_rol = $rol->getId_rol();

        $consulta_preparada->bind_param('ii', $id_personal, $id_rol);
        if (!$consulta_preparada->execute()) {
            //Si entra aquí es que ha devuelto falso y ha fallado la consulta
            guardar_mensaje("<b>Ha ocurrido un error en la funcion Insertar de la clase Personal.php</b> <br><br>" . $consulta_preparada->error);
            die();  //Paramos la ejecución;
        }
        $num_filas_insertadas = $consulta_preparada->affected_rows;
        $conexion->close();
        $consulta_preparada->close();
        if ($num_filas_insertadas == 1) {
            return true;
        } else {
            guardar_mensaje("Ha habido un error al guardar el Personal");
            return false;
        }
    }

    /*
     * Función para obtener los roles que tiene el usuario.
     */
    public function obtener_roles_usuarios($id_personal)
    {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare("Select rol.rol 
            From personal INNER JOIN personal_rol ON  personal.id_personal = personal_rol.id_personal
            inner join rol on personal_rol.id_rol = rol.id_rol
            where personal.id_personal = ?");

        if (!$consulta) {
            echo 'Error' . $conexion->error;
            die();
        }

        if (!$consulta->bind_param('i', $id_personal)) {
            echo 'Error en la consulta' . $consulta->error;
            die();
        }

        if (!$consulta->execute()) {
            echo 'Error en la ejecución' . $consulta->error;
            die();
        }

        if (!$resultado = $consulta->get_result()->fetch_all(MYSQLI_ASSOC)) {
            return false;
        }

        return $resultado;
    }
    
    /*
     * Función para obtener los cursos del usuario.
     */
    public function obtener_cursos_usuarios($id_personal)
    {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare("Select cursos.nombre_curso 
            From personal INNER JOIN personal_curso ON  personal.id_personal = personal_curso.id_personal
            inner join cursos on personal_curso.id_curso = cursos.id_curso
            where personal.id_personal = ?");

        if (!$consulta) {
            echo 'Error' . $conexion->error;
            die();
        }

        if (!$consulta->bind_param('i', $id_personal)) {
            echo 'Error en la consulta' . $consulta->error;
            die();
        }

        if (!$consulta->execute()) {
            echo 'Error en la ejecución' . $consulta->error;
            die();
        }

        if (!$resultado = $consulta->get_result()->fetch_all(MYSQLI_ASSOC)) {
            return false;
        }

        return $resultado;
    }
    
    /*
     * Función para obtener las asignaturas del usuario según el curso.
     */
    public function obtener_asignaturas_usuarios_curso($id_personal,$nombre_curso)
    {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare("Select asignaturas.nombre_asignatura 
            From personal INNER JOIN personal_asignatura ON  personal.id_personal = personal_asignatura.id_personal
            inner join asignaturas on personal_asignatura.id_asignatura = asignaturas.id_asignatura 
            inner join cursos on asignaturas.id_curso = cursos.id_curso 
            where personal.id_personal = ? AND cursos.nombre_curso = ?");

        if (!$consulta) {
            echo 'Error' . $conexion->error;
            die();
        }

        if (!$consulta->bind_param('is', $id_personal,$nombre_curso)) {
            echo 'Error en la consulta' . $consulta->error;
            die();
        }

        if (!$consulta->execute()) {
            echo 'Error en la ejecución' . $consulta->error;
            die();
        }

        if (!$resultado = $consulta->get_result()->fetch_all(MYSQLI_ASSOC)) {
            return false;
        }

        return $resultado;
    }

    /*
     * Función que actualiza la contraseña en la base de datos.
     */
    public function cambiar_password($actual_password, $nueva_password)
    {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare("UPDATE personal set personal.password = ?"
            . " where password = ? and id_personal = ?");

        if (!$consulta) {
            echo 'Error' . $conexion->error;
            die();
        }
        
        $nueva_password = limpiar_datos($nueva_password);
        
        $id_personal = Sesiones::obtener_id();

        if (!$consulta->bind_param('ssi', $nueva_password, $actual_password, $id_personal)) {
            echo 'Error en la consulta' . $consulta->error;
            die();
        }

        if (!$consulta->execute()) {
            echo 'Error en la ejecución' . $consulta->error;
            die();
        }

        $num_filas_actualizadas = $conexion->affected_rows;
        $conexion->close();
        $consulta->close();

        if ($num_filas_actualizadas == 1) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Función para obtener el nombre del usuario según el rol.
     */
    public function obtener_personal_por_rol($rol)
    {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare("Select personal.* "
            . "From personal inner join personal_rol on personal.id_personal = personal_rol.id_personal "
            . "inner join rol on personal_rol.id_rol = rol.id_rol "
            . "where rol.rol = ?");

        if (!$consulta) {
            echo 'Error' . $conexion->error;
            die();
        }

        if (!$consulta->bind_param('s', $rol)) {
            echo 'Error en la consulta' . $consulta->error;
            die();
        }

        if (!$consulta->execute()) {
            echo 'Error en la ejecución' . $consulta->error;
            die();
        }

        if (!$result = $consulta->get_result()->fetch_assoc()) {
            return false;
        }

        //Asigna los valores a las propiedades del objeto
        $this->setId($result['id_personal']);
        $this->setNombre($result['nombre']);
        $this->setEmail($result['email']);
        $this->setApellidos($result['apellidos']);
        $this->setPassword($result['password']);
        $this->setCookie($result['cookie']);

        $consulta->close();
        $conexion->close();

        return true;
    }

    public function enviar_mail($id_documento)
    {


        // Varios destinatarios
        $para = $this->getEmail();
        $nombre = $this->getNombre();
        $id_usuario = $this->getId();
        // título
        $título = 'Prueba enviar formulario';

        // mensaje
        $mensaje = '
<html>
<head>
  <title>Confirmación de correo</title>
</head>
<body>
  <a href="https://webjuanpiqueras94.000webhostapp.com/Calidad_Bosco/web/index.php?comando=cargar_contenido&id_documento=' . $id_documento . '&id_usuario=' . $id_usuario . '">Contesta a este documento</a>
</body>
</html>
';

        // Para enviar un correo HTML, debe establecerse la cabecera Content-type
        $cabeceras = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Cabeceras adicionales
        $cabeceras .= "Para: $nombre <$para>" . "\r\n";
        $cabeceras .= 'De: Administrador <administracion@iesjuanboco.com>' . "\r\n";

        // Enviarlo
        if (mail($para, $título, $mensaje, $cabeceras)) {
            return true;
        } else {
            return false;
        }
    }

    /*
    function iniciarSesion($id){

    }*/
}
