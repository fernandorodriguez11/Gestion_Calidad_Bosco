<?php
class Asignaturas{

    private $id_asignatura;
    private $nombre_asignatura;
    private $abreviatura;
    private $id_curso;
    
    function getId_asignatura() {
        return $this->id_asignatura;
    }

    function getNombre_asignatura() {
        return $this->nombre_asignatura;
    }

    function getAbreviatura() {
        return $this->abreviatura;
    }

    function getId_curso() {
        return $this->id_curso;
    }

    function setId_asignatura($id_asignatura) {
        $this->id_asignatura = $id_asignatura;
    }

    function setNombre_asignatura($nombre_asignatura) {
        $this->nombre_asignatura = $nombre_asignatura;
    }

    function setAbreviatura($abreviatura) {
        $this->abreviatura = $abreviatura;
    }

    function setId_curso($id_curso) {
        $this->id_curso = $id_curso;
    }
    
    /**
     * Función para obtener todos las asignaturas de la base de datos
     * 
     * @return Array de Asignaturas
     */
    function obtener_todas_asignaturas() {

        $asignaturas = array();

        $conexion = conexion_bd();

        $consulta = 'Select * from asignaturas Group By asignaturas.nombre_asignatura';

        if (!$result = $conexion->query($consulta)) {
            echo "Error en la consulta: " . $conexion->error;
            die();
        }

        $filas = $result->fetch_all(MYSQLI_ASSOC);

        $conexion->close();

        foreach ($filas as $fila) {
            $asignatura = new Asignaturas();
            $asignatura->setId_asignatura($fila['id_asignatura']);
            $asignatura->setNombre_asignatura($fila['nombre_asignatura']);
            $asignatura->setAbreviatura($fila['abreviatura']);
            $asignatura->setId_curso($fila['id_curso']);
            $asignaturas[] = $asignatura;
        }

        return $asignaturas;
    }
	
	/**
     * Función para obtener todos los documentos con respuestas de las asignaturas
     *  de la base de datos
     * 
     * @return Array de Asignaturas
     */
    function obtener_todas_asignaturasRes() {

        $conexion = conexion_bd();

        $consulta = 'SELECT * FROM asignaturas inner join asignatura_documentos on asignaturas.id_asignatura = asignatura_documentos.id_asignatura
inner JOIN documentos on asignatura_documentos.id_documentos = documentos.id_documentos 
inner join respondido on asignaturas.id_asignatura = respondido.id_asignatura 
group by asignaturas.id_asignatura';

        if (!$result = $conexion->query($consulta)) {
            echo "Error en la consulta: " . $conexion->error;
            die();
        }

        $filas = $result->fetch_all(MYSQLI_ASSOC);

        $conexion->close();

        return $filas;
    }
    
    /**
     * 
     * @param type $id el id de la asignatura
     * 
     * Función para obtener las asignaturas según el id de la asignatura
     * 
     * @return boolean true si existe y false si no.
     */
    public function carga_asignaturas($id) {
        
        $conexion = conexion_bd();

        $consulta_preparada = $conexion->prepare("SELECT * FROM asignaturas WHERE id_asignatura = ?");

        if (!$consulta_preparada) {
            guardar_mensaje("<b>Ha ocurrido un error en la funcion CargaDatos de la clase Cursos.php</b> <br><br>" . $conexion->error);
            die();
        }

        if (!$consulta_preparada->bind_param('i', $id)) {
            guardar_mensaje("<b>Ha ocurrido un error en la funcion CargaDatos de la clase Cursos.php</b> <br><br>" . $consulta_preparada->error);
            die();
        }

        if (!$consulta_preparada->execute()) {
            guardar_mensaje("<b>Ha ocurrido un error en la funcion CargaDatos de la clase Cursos.php</b> <br><br>" . $consulta_preparada->error);
            die();
        }

        if (!$fila = $consulta_preparada->get_result()->fetch_assoc()) {
            guardar_mensaje("<b>Ha ocurrido un error en la funcion CargaDatos de la clase Cursos.php</b> <br><br>" . $consulta_preparada->error);
            return false;
        }

        $this->setId_asignatura($fila['id_asignatura']);
        $this->setNombre_asignatura($fila['nombre_asignatura']);
        $this->setAbreviatura($fila['abreviatura']);
        $this->setId_curso($fila['id_curso']);

        $consulta_preparada->close();
        $conexion->close();

        return true;
    }
    
    /**
     * 
     * @param type $asignatura nombre de la asignatura a buscar
     * 
     * Función que obtiene las asignaturas cuyo nombre sea igual al pasado por 
     * parámetros
     * 
     * @return boolean true si existe la asignatura y false si no
     */
    public function obtener_asignatura_por_nombre($asignatura) {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare('Select id_asignatura from asignaturas'
                . ' where nombre_asignatura = ?');

        if (!$consulta) {
            echo 'Error en la consulta: ' . $conexion->error;
            die();
        }

        if (!$consulta->bind_param('s', $asignatura)) {
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

        /*Asigna los valores a las propiedades del objeto
        $this->setId_asignatura($result['id_asignatura']);
        $this->setNombre_asignatura($result['nombre_asignatura']);
        $this->setAbreviatura($result['abreviatura']);
        $this->setId_curso($result['id_curso']);*/

        $consulta->close();
        $conexion->close();
        
         return $result;

        //return true;
    }

    /**
     * 
     * Función para insertar asignaturas en la base de datos.
     * 
     * @return boolean true si es insertado correctamente y false si no.
     */
    public function Insertar() {
        $conexion = conexion_bd();

        $consulta_preparada = $conexion->prepare("INSERT INTO asignaturas "
                . "(nombre_asignatura, abreviatura, id_curso) "
                . "values (?,?,?) ");

        if (!$consulta_preparada) {
            echo "Error en la consulta" . $consulta_preparada->error;
            die();
        }

        $nombre_asignatura = $this->getNombre_asignatura();
        $abreviatura = $this->getAbreviatura();
        $id_curso = $this->getId_curso();

        if(!$consulta_preparada->bind_param('ssi', $nombre_asignatura, $abreviatura,
                $id_curso)){
            echo "Error en la consulta" . $consulta_preparada->error;
            die();
        }
        if (!$consulta_preparada->execute()) {
            //Si entra aquí es que ha devuelto falso y ha fallado la consulta
            guardar_mensaje("<b>Ha ocurrido un error en la funcion Insertar de la clase Cursos.php</b> <br><br>" . $consulta_preparada->error);
            die();  //Paramos la ejecución;
        }

        $num_filas_insertadas = $consulta_preparada->affected_rows;
        $conexion->close();
        $consulta_preparada->close();
        
        if ($num_filas_insertadas == 1) {
            guardar_mensaje("Curso creado correctamente");
            return true;
        } else {
            guardar_mensaje("Ha habido un error al guardar el curso");
            return false;
        }
    }

    /**
     * 
     * Función para actualizar una asignatura en la base de datos.
     * 
     * @return boolean true si la asignatura es actualizado correctamente y false
     * si no es modificado correctamente.
     */
    public function Modificar() {
        $conexion = conexionBD();

        $consulta_preparada = $conexion->prepare("UPDATE asignaturas "
                . "SET nombre_asignatura = ?, "
                . "abreviatura = ? "
                . "WHERE id_asignatura = ?");

        if (!$consulta_preparada) {
            echo "Error en la consulta" . $consulta_preparada->error;
            die();
        }

        $nombre_asignatura = $this->getNombre_asignatura();
        $abreviatura = $this->getAbreviatura();
        $id_asignatura = $this->getId_asignatura();
        
        if(!$consulta_preparada->bind_param('ssi', $nombre_asignatura, $abreviatura,
                $id_asignatura)){
            echo 'Error en la consulta preparada: '.$conexion->error;
            die();
        }
        if (!$consulta_preparada->execute()) {
            //Si entra aquí es que ha devuelto falso y ha fallado la consulta
            echo "Ha ocurrido un error en la select" . $consulta_preparada->error;
            die();  //Paramos la ejecución;
        }
        $num_filas_modificadas = $conexion->affected_rows;
        
        $conexion->close();
        $consulta_preparada->close();
        
        if ($num_filas_modificadas > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Función sql para agrupar por asignaturas
     * 
     * @return array de Asignaturas o false
     */
    function agrupar_asignaturas(){
    
        $conexion = conexion_bd();

        $consulta = $conexion->prepare("Select asignaturas.*, asignaturas.id_curso "
                . "From asignaturas inner join personal_asignatura on asignaturas.id_asignatura = personal_asignatura.id_asignatura "
                . "inner join personal on personal_asignatura.id_personal = personal.id_personal "
                . "where personal.id_personal = ? Group By asignaturas.id_asignatura");
        
        if(!$consulta){
            echo 'Error: '.$conexion->error;
            die();
        }
        
        $id_personal = Sesiones::obtener_id();
        
        if(!$consulta->bind_param("i",$id_personal)){
            echo 'Error: '.$consulta->error;
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
     * Obtengo todos los documentos asociados a cada asignatura.
     */
     function obtener_asignaturas_documentos(){
    
        $conexion = conexion_bd();

        $consulta = $conexion->prepare("Select asignaturas.nombre_asignatura, "
                . "documentos.*, asignatura_documentos.id_asignatura "
                . "From asignatura_documentos inner join asignaturas on "
                . "asignaturas.id_asignatura = asignatura_documentos.id_asignatura "
                . "inner join documentos on asignatura_documentos.id_documentos = documentos.id_documentos "
                . "inner join personal_asignatura on asignaturas.id_asignatura = personal_asignatura.id_asignatura "
                . "inner join personal on personal_asignatura.id_personal = personal.id_personal "
                . "where personal.id_personal = ?");
        
        if(!$consulta){
            echo 'Error: '.$conexion->error;
            die();
        }
        
        $id_personal = Sesiones::obtener_id();
        
        if(!$consulta->bind_param("i",$id_personal)){
            echo 'Error: '.$consulta->error;
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
    
    /**
     * Función para eliminar el documento de una asignatura.
     * @param type $id_documentos
     * @return boolean
     */
    public static function eliminar_asignatura_documento($id_documentos) {
     
        $conexion = conexion_bd();
        
        $consulta = $conexion->prepare("Delete From asignatura_documentos where id_documentos = ?");
        
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