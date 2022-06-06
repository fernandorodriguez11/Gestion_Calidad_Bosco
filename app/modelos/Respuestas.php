<?php

class Respuestas{
    
    private $id_respuesta;
    private $id_documentos_lineas;
    private $id_personal;
    private $respuestas;
    private $previstas;
    private $impartidas;
    private $fecha_respuesta;
    private $asignatura;
    private $curso;
    private $rol;
    
    function getId_respuesta() {
        return $this->id_respuesta;
    }

    function getId_documentos_lineas() {
        return $this->id_documentos_lineas;
    }

    function getId_personal() {
        return $this->id_personal;
    }

    function getRespuestas() {
        return $this->respuestas;
    }
    
    function getFecha_respuesta() {
        return $this->fecha_respuesta;
    }

    function getPrevistas() {
        return $this->previstas;
    }

    function getImpartidas() {
        return $this->impartidas;
    }
    
    function getAsignatura() {
        return $this->asignatura;
    }

    function getCurso() {
        return $this->curso;
    }

    function getRol() {
        return $this->rol;
    }
    
    function setId_respuesta($id_respuesta) {
        $this->id_respuesta = $id_respuesta;
    }

    function setId_documentos_lineas($id_documentos_lineas) {
        $this->id_documentos_lineas = $id_documentos_lineas;
    }

    function setId_personal($id_personal) {
        $this->id_personal = $id_personal;
    }

    function setRespuestas($respuestas) {
        $this->respuestas = $respuestas;
    }

    function setPrevistas($previstas) {
        $this->previstas = $previstas;
    }

    function setImpartidas($impartidas) {
        $this->impartidas = $impartidas;
    }
    
    function setFecha_respuesta($fecha_respuesta) {
        $this->fecha_respuesta = $fecha_respuesta;
    }
    
    function setAsignatura($asignatura) {
        $this->asignatura = $asignatura;
    }

    function setCurso($curso) {
        $this->curso = $curso;
    }

    function setRol($rol) {
        $this->rol = $rol;
    }

    /**
     * Función para insertar respuestas en la base de datos.
     * 
     * @return boolean true si la respuesta es insertada correctamente en la
     * base de datos y false si no es insertada correctamente.
     */
    public function insertar_respuesta(){
        
        $conexion = conexion_bd();
        
        $consulta = $conexion->prepare("INSERT INTO respuestas (id_documentos_lineas,"
                . "id_personal,respuestas,previstas,impartidas, fecha_respuesta, asignatura, curso, rol)"
                . " VALUES(?,?,?,?,?,now(),?,?,?)");
        
        if(!$consulta){
            echo 'Error en la consulta: '.$conexion->error;
            die();
        }
        
        $idl = $this->getId_documentos_lineas();
        $idp = $this->getId_personal();
        $respuestas = limpiar_datos($this->getRespuestas());
        $previstas = limpiar_datos($this->getPrevistas());
        $impartidas = limpiar_datos($this->getImpartidas());
        //$fecha = $this->getFecha_respuesta();
        $asignatura = $this->getAsignatura();
        $curso = $this->getCurso();
        $rol = $this->getRol();
        if(!$consulta->bind_param('iissssss', $idl,$idp,$respuestas,$previstas,$impartidas,
                $asignatura, $curso, $rol)){
            echo 'Error en el bind_param: '.$consulta->error;
            die();
        }
        
        if(!$consulta->execute()){
            echo 'Error en la ejecución: '.$consulta->error;
            die();
        }
        
        $num_filas_insertadas = $consulta->affected_rows;
        $consulta->close();
        $conexion->close();
        
        
        if ($num_filas_insertadas == 1) {
            return true;
        } else {
            return false;
        }
        
    }
    
   public function dowload_documentos($id_documentos, $id_curso, $id_asignatura){
        
        $conexion = conexion_bd();
        
        $consulta = $conexion->prepare("Select personal.nombre, asignaturas.nombre_asignatura,
            cursos.nombre_curso, cursos.abreviatura as abc, respuestas.*, documentos.nombre_documento, asignaturas.abreviatura as aba
            From personal inner join respuestas on personal.id_personal = respuestas.id_personal 
            inner join documentos_lineas on respuestas.id_documentos_lineas = documentos_lineas.id_documentos_lineas 
            inner join documentos on documentos_lineas.id_documentos = documentos.id_documentos 
            inner join asignatura_documentos on documentos.id_documentos = asignatura_documentos.id_documentos 
            inner join asignaturas on asignatura_documentos.id_asignatura = asignaturas.id_asignatura 
            inner join cursos on asignaturas.id_curso = cursos.id_curso 
            where respuestas.curso = cursos.id_curso 
            and respuestas.asignatura = asignaturas.id_asignatura 
            and documentos.id_documentos = ? AND respuestas.curso = ? AND respuestas.asignatura = ?
            Group by respuestas.id_respuesta");
        
        if(!$consulta){
            echo 'Error'. $conexion->error;
            die();
        }
        
        if(!$consulta->bind_param("iii", $id_documentos, $id_curso, $id_asignatura)){
            echo 'Error en la consulta'. $conexion->error;
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
    
    public function dowload_documentos_rol($id_documentos, $id_rol){
        
        $conexion = conexion_bd();
        
        $consulta = $conexion->prepare("Select personal.nombre, respuestas.*, documentos.nombre_documento, rol.rol 
            From personal inner join respuestas on personal.id_personal = respuestas.id_personal 
            inner join documentos_lineas on respuestas.id_documentos_lineas = documentos_lineas.id_documentos_lineas 
            inner join documentos on documentos_lineas.id_documentos = documentos.id_documentos 
            inner join rol_documentos on documentos.id_documentos = rol_documentos.id_documentos 
            inner join rol on rol_documentos.id_rol = rol.id_rol 
            where respuestas.rol = rol.id_rol and 
            documentos.id_documentos = ? AND respuestas.rol = ?
            Group by respuestas.id_respuesta");
        
        if(!$consulta){
            echo 'Error'. $conexion->error;
            die();
        }
        
        if(!$consulta->bind_param("ii", $id_documentos, $id_rol)){
            echo 'Error en la consulta'. $conexion->error;
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
}