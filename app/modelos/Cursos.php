<?php

class Cursos {

    private $id_curso;
    private $nombre_curso;
    private $abreviatura;
    private $id_tutor;
    
    function getId_curso() {
        return $this->id_curso;
    }
    
    function getNombre_curso() {
        return $this->nombre_curso;
    }
    
    function getAbreviatura() {
        return $this->abreviatura;
    }
    
    function getId_tutor() {
        return $this->id_tutor;
    }

    function setId_curso($id_curso) {
        $this->id_curso = $id_curso;
    }
    
    function setNombre_curso($nombre_curso) {
        $this->nombre_curso = $nombre_curso;
    }
    
    function setAbreviatura($abreviatura) {
        $this->abreviatura = $abreviatura;
    }
    
    function setId_tutor($id_tutor) {
        $this->id_tutor = $id_tutor;
    }

    /**
     * Función para obtener todos los cursos de la base de datos
     * 
     * @return Array de Cursos
     */
    function obtener_todos_cursos() {

        $cursos = array();

        $conexion = conexion_bd();

        $consulta = 'Select * from cursos group by cursos.nombre_curso';

        if (!$result = $conexion->query($consulta)) {
            echo "Error en la consulta: " . $conexion->error;
            die();
        }

        $filas = $result->fetch_all(MYSQLI_ASSOC);

        $conexion->close();

        foreach ($filas as $fila) {
            $curso = new Cursos();
            $curso->setId_curso($fila['id_curso']);
            $curso->setNombre_curso($fila['nombre_curso']);
            $curso->setAbreviatura($fila['abreviatura']);
            $curso->setId_tutor($fila['id_tutor']);
            $cursos[] = $curso;
        }

        return $cursos;
    }
	
	/**
     * Función para obtener todos los documentos con respuestas de los cursos de la base de datos
     * 
     * @return Array de Cursos
     */
    function obtener_todos_cursosRes() {

        $cursos = array();

        $conexion = conexion_bd();

        $consulta = 'SELECT * FROM cursos inner join curso_documentos on cursos.id_curso = curso_documentos.id_curso 
inner JOIN documentos on curso_documentos.id_documentos = documentos.id_documentos 
inner join respondido on cursos.id_curso = respondido.id_curso 
group by cursos.id_curso';

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
     * @param type $id el id del curso
     * 
     * Función para obtener los cursos según el id del curso
     * 
     * @return boolean true si existe y false si no.
     */
    public function CargaDatos($id) {
        $conexion = conexion_bd();

        $consulta_preparada = $conexion->prepare("SELECT * FROM cursos WHERE id_cursos = ?");

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

        $this->setId_curso($fila['id_curso']);
        $this->setNombre_curso($fila['nombre_curso']);
        $this->setAbreviatura($fila['abreviatura']);
        

        $consulta_preparada->close();
        $conexion->close();

        return true;
    }
    
    /**
     * 
     * @param type $curso nombre del curso a buscar
     * 
     * Función que obtiene los cursos cuyo nombre sea igual al pasado por 
     * parámetros
     * 
     * @return boolean true si existe el curso y false si no
     */
    public function obtener_curso_por_nombre($curso) {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare('Select id_curso from cursos where nombre_curso = ?');

        if (!$consulta) {
            echo 'Error en la consulta: ' . $conexion->error;
            die();
        }

        if (!$consulta->bind_param('s', $curso)) {
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
        $this->setId_curso($result['id_curso']);
        $this->setNombre_curso($result['nombre_curso']);
        $this->setAbreviatura($result['abreviatura']);
        */
        $consulta->close();
        $conexion->close();

        return $result;
    }

    /**
     * 
     * Función para insertar cursos en las base de datos.
     * 
     * @return boolean true si es insertado correctamente y false si no.
     */
    public function Insertar() {
        $conexion = conexion_bd();

        $consulta_preparada = $conexion->prepare("INSERT INTO cursos "
                . "(nombre_curso, abreviatura) "
                . "values (?,?) ");

        if (!$consulta_preparada) {
            echo "Error en la consulta" . $consulta_preparada->error;
            die();
        }

        $nombre_curso = $this->getNombre_curso();
        $abreviatura = $this->getAbreviatura();

        if(!$consulta_preparada->bind_param('ss', $nombre_curso, $abreviatura)){
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
     * Función para actualizar un curso en la base de datos.
     * 
     * @return boolean true si el curso es actualizado correctamente y false
     * si no es modificado correctamente.
     */
    public function Modificar() {
        $conexion = conexionBD();

        $consulta_preparada = $conexion->prepare("UPDATE cursos "
                . "SET nombre_curso = ?, "
                . "abreviatura = ? "
                . "WHERE id_curso = ?");

        if (!$consulta_preparada) {
            echo "Error en la consulta" . $consulta_preparada->error;
            die();
        }

        $nombre_curso = $this->getNombre_curso();
        $abreviatura = $this->getAbreviatura();
        $id_curso = $this->getId_curso();

        if(!$consulta_preparada->bind_param('ssi', $nombre_curso, $abreviatura, 
                $id_curso)){
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
     * 
     * Función para obtener los documentos relacionados a un curso.
     * 
     * @return Array de Cursos o false.
     */
    function obtener_cursos_documentos(){
    
        $conexion = conexion_bd();
        
        $consulta = $conexion->prepare("Select cursos.nombre_curso, cursos.id_curso "
                . "From cursos inner join curso_documentos on "
                . "cursos.id_curso = curso_documentos.id_curso "
                . "inner join documentos on curso_documentos.id_documentos = documentos.id_documentos "
                . "inner join personal_curso on cursos.id_curso = personal_curso.id_curso "
                . "inner join personal on personal_curso.id_personal = personal.id_personal "
                . "where personal.id_personal = ? Group By cursos.nombre_curso");
        
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
     * Función para eliminar el curso vinculado a un documento.
     */
    public static function eliminar_curso_documento($id_documentos) {
     
        $conexion = conexion_bd();
        
        $consulta = $conexion->prepare("Delete From curso_documentos where id_documentos = ?");
        
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
