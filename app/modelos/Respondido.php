<?php

class respondido{
    
	/*Variables de la clase respondido*/
    private $id_respondido;
    private $id_personal;
    private $id_documentos;
    private $id_rol;
    private $id_curso;
    private $id_asignatura;
    private $respondido;
    
	/*Getters para obtener los valores de las variables*/
    function getId_respondido() {
        return $this->id_respondido;
    }

    function getId_personal() {
        return $this->id_personal;
    }

    function getId_documentos() {
        return $this->id_documentos;
    }

    function getId_rol() {
        return $this->id_rol;
    }

    function getId_curso() {
        return $this->id_curso;
    }

    function getId_asignatura() {
        return $this->id_asignatura;
    }

    function getRespondido() {
        return $this->respondido;
    }

    function setId_respondido($id_respondido) {
        $this->id_respondido = $id_respondido;
    }

	/* Setters para modificar las variables*/
    function setId_personal($id_personal) {
        $this->id_personal = $id_personal;
    }

    function setId_documentos($id_documentos) {
        $this->id_documentos = $id_documentos;
    }

    function setId_rol($id_rol) {
        $this->id_rol = $id_rol;
    }

    function setId_curso($id_curso) {
        $this->id_curso = $id_curso;
    }

    function setId_asignatura($id_asignatura) {
        $this->id_asignatura = $id_asignatura;
    }

    function setRespondido($respondido) {
        $this->respondido = $respondido;
    }

	/* 
     * Función que inserta el id del personal de documentos de rol cursos y 
     * asignaturas para poner el campo respondido a 1 para controlar que un 
     * documento ya ha sido respondido. 
     */
    public function insertar_sies_respondido(){
        
        $conexion = conexion_bd();
        
        $consulta = $conexion->prepare("INSERT INTO respondido (id_personal, id_documentos, "
                . "id_rol,id_asignatura, id_curso, respondido) "
                . "VALUES (?,?,?,?,?,1)");
        
        if(!$consulta){
            echo 'Error: '.$conexion->error;
            die();
        }
        
        $id_personal = $this->getId_personal();
        $id_documentos = $this->getId_documentos();
        //$id_respuesta = $this->getId_respuesta();
        $id_rol = $this->getId_rol();
        $id_curso = $this->getId_curso();
        $id_asignatura = $this->getId_asignatura();
        
        if(!$consulta->bind_param("iiiii", $id_personal,$id_documentos,$id_rol,
                $id_asignatura,$id_curso)){
            echo 'Error en la consulta'. $consulta->error;
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
    
	/*
     * Función para obtener de la base de datos el campo respondido para
     * comprobar si el documento ya ha sido respondido o no.
     */
    public function saber_si_esta_respondido(){
        $conexion = conexion_bd();
        
        $consulta = $conexion->prepare("Select respondido.respondido "
                . "From respondido "
                . "Where id_personal = ? and id_documentos = ? and id_rol = ? "
                . "and id_curso = ? and id_asignatura = ?");
        
        if(!$consulta){
            echo 'Error '.$conexion->error;
            die();
        }
        
        $id_personal = $this->getId_personal();
        $id_documentos = $this->getId_documentos();
        $id_rol = $this->getId_rol();
        $id_curso = $this->getId_curso();
        $id_asignatura = $this->getId_asignatura();
        
        if(!$consulta->bind_param("iiiii", $id_personal,$id_documentos,$id_rol,
                $id_asignatura,$id_curso)){
            echo 'Error en la consulta'. $consulta->error;
            die();
        }
        
        if (!$consulta->execute()) {
            echo "Error al ejecutar la consulta " . $consulta->error;
            die();
        }

        if (!$result = $consulta->get_result()->fetch_assoc()) {
            return false;
        }

        $this->setRespondido($result['respondido']);
        
        $consulta->close();
        $conexion->close();

        return true;
        
    }
}

