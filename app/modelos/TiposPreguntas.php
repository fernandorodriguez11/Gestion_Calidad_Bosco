<?php

class TiposPreguntas{
    
    private $idTipoPregunta;
    private $tipo;
    
    function getIdTipoPregunta() {
        return $this->idTipoPregunta;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setIdTipoPregunta($idTipoPregunta) {
        $this->idTipoPregunta = $idTipoPregunta;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    /**
     * Función para obtener todos los tipos de preguntas de la base de datos.
     * 
     * @return \TiposPreguntas
     */
    function obtener_todas_preguntas(){
        
        $preguntas = Array();
        
        $conexion = conexion_bd();
        
        $consulta = 'Select * from tipos_preguntas';
        
        if(!$resultado = $conexion->query($consulta)){
            echo 'Error en la consulta: '.$conexion->error;
            die();
        }
        
        $filas = $resultado->fetch_all(MYSQLI_ASSOC);
        
        $conexion->close();
        
        foreach($filas as $fila){
            $pregunta = new TiposPreguntas();
            
            $pregunta->setIdTipoPregunta($fila['id_tipo']);
            $pregunta->setTipo($fila['tipo']);
            
            $preguntas[] = $pregunta;
        }
        
        return $preguntas;
        
    }
    
    /*
     * Función para obtener los tipos de preguntas asignados al id pasado por
     * parámetros.
     */
    function obtener_tipos_preguntas($id_tipo){
        
        $conexion = conexion_bd();
        
        $consulta = $conexion->prepare('Select * from tipos_preguntas where '
                . 'id_tipo = ?');
        
        if(!$consulta){
            echo 'Error en la consulta: '.$conexion->error;
            die();
        }
        
        if(!$consulta->bind_param('i', $id_tipo)){
            echo 'Error en la consulta: '.$conexion->error;
            die();
        }
        
        $resultado = $consulta->execute();
        
        if(!$resultado){
            echo 'Error en la ejecucion: '.$consulta->error;
            die();
        }
        
        if(!$fila = $resultado->get_result()->fetch_assoc()){
            return false;
        }
        
        //return $fila;
        $this->setIdTipoPregunta($fila['id_tipo']);
        $this->setTipo($fila['tipo']);
        
        $consulta->close();
        $conexion->close();
        
        return true;
    }
    
    /*
     * Función para obtener el id del tipo de pregunta de la base de datos
     * pasando por parámetros el nombre del tipo de pregunta.
     */
    function obtener_id_pregunta($tipo){
        
        $conexion = conexion_bd();
        
        $consulta = $conexion->prepare('Select * from tipos_preguntas where '
                . 'tipo = ?');
        
        if(!$consulta){
            echo 'Error en la consulta: '.$conexion->error;
            die();
        }
        
        if(!$consulta->bind_param('s', $tipo)){
            echo 'Error en la consulta: '.$conexion->error;
            die();
        }
        
        $resultado = $consulta->execute();
        
        if(!$resultado){
            echo 'Error en la ejecucion: '.$consulta->error;
            die();
        }
        
        if(!$fila = $consulta->get_result()->fetch_assoc()){
            return false;
        }
        
        //return $fila;
        $this->setIdTipoPregunta($fila['id_tipo']);
        $this->setTipo($fila['tipo']);
        
        $consulta->close();
        $conexion->close();
        
        return true;
    }
    
    /*
     * Función estática para insertar los tipos de preguntas en la base de 
     * datos.
     */
    static function inserta_tipoPregunta($pregunta){
        $conexion = conexion_bd();

        $consulta = $conexion->prepare('INSERT INTO tipos_preguntas (tipo) VALUES (?)');

        if (!$consulta) {
            echo 'error en la consulta: ' . $conexion->error;
            die();
        }
        
        $rol = $this->getRol();
        
        if (!$consulta->bind_param("s", $pregunta)) {
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
}