<?php

class DocumentosLineas{
    
    private $id;
    private $id_documentos;
    private $titulo;
    private $descripcion;
    private $id_tipo;
    
    //<editor-fold defaultstate="collapsed" desc="Getters">
    
    function getId() {
        return $this->id;
    }

    function getId_documentos() {
        return $this->id_documentos;
    }

    function getTitulo() {
        return $this->titulo;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getId_tipo() {
        return $this->id_tipo;
    }    
    //</editor-fold>
    
    //<editor-fold defaultstate="collapsed" desc="Setters">
    function setId($id) {
        $this->id = $id;
    }

    function setId_documentos($id_documentos) {
        $this->id_documentos = $id_documentos;
    }

    function setTitulo($titulo) {
        $this->titulo = limpiar_datos($titulo);
    }

    function setDescripcion($descripcion) {
        $this->descripcion = limpiar_datos($descripcion);
    }

    function setId_tipo($id_tipo) {
        $this->id_tipo = $id_tipo;
    }
    //</editor-fold>
    
    
    /**
     * Función para insertar el contenido de los documentos.
     */
    function insertar_contenido(){
        
        $conexion = conexion_bd();
        
        $consulta = $conexion->prepare("INSERT INTO documentos_lineas (id_documentos,titulo,descripcion,id_tipo)"
                . " VALUES(?,?,?,?)");
        
        if(!$consulta){
            echo 'Error en la consulta: '.$conexion->error;
            die();
        }
        
        $id_doc = (int) $this->getId_documentos();
        $titulo = limpiar_datos($this->getTitulo());
        $descripcion = limpiar_datos($this->getDescripcion());
        
        $tipo = $this->getId_tipo();
        
        if($titulo == null || $tipo == null){
            Rol::eliminar_rol_documento($id_doc);
            Cursos::eliminar_curso_documento($id_doc);
            Asignaturas::eliminar_asignatura_documento($id_doc);
            Documentos::eliminar_documento($id_doc);
            guardar_mensaje("El contenido no puede estar vacio");
            header("location: index.php?comando=gestion_documentos");
            die();
        }
        
        if(!$consulta->bind_param("issi", $id_doc, $titulo, $descripcion, $tipo)){
            echo 'Error en el bind param: '.$conexion->error;
            die();
        }
        
        if (!$consulta->execute()) {
            echo 'error en la ejecucion ' . $consulta->error;
            die();
        }

        $num_filas_insertadas = $conexion->affected_rows;
        $conexion->close();
        $consulta->close();

        if ($num_filas_insertadas == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Función para obtener el contenido del documento pasando por parámetros
     * el id del documento padre.
     * 
     * @param type $id_documentos
     * @return boolean
     */
    function obtener_documentos_lineas($id_documentos){
        
        $contenidos = Array();
        
        $conexion = conexion_bd();
        
        $consulta = $conexion->prepare('Select * From documentos_lineas where'
                . ' id_documentos = ?');
        
        if(!$consulta){
            echo 'Error en la consulta sql: '. $conexion->error;
            die();
        }
        
        if(!$consulta->bind_param('i', $id_documentos)){
            echo 'Error en la consulta:'. $conexion->error;
            die();
        }
        if (!$consulta->execute()) {
            echo "Error al ejecutar la consulta: " . $conexion->error;
            die();
        }
        
        if (!$filas = $consulta->get_result()->fetch_all(MYSQLI_ASSOC)) {
            return false;
        }

        //Cerramos los recursos abiertos
        $conexion->close();
        $consulta->close();
        
        foreach($filas as $fila){
            $contenido = new DocumentosLineas();
            $contenido->setId($fila['id_documentos_lineas']);
            $contenido->setId_documentos($fila['id_documentos']);
            $contenido->setTitulo($fila['titulo']);
            $contenido->setDescripcion($fila['descripcion']);
            $contenido->setId_tipo($fila['id_tipo']);
            $contenidos[] = $contenido;
        }

        return $contenidos;
    }
    
    
    /**Función para obtener el tipo de la pregunta y todos el contenido de los
     * documentos
     * 
     * Return un array del resultado.
     */
    
    public function obtener_documentos_tiposPreguntas() {
        
        $conexion = conexion_bd();
        
        $consulta = $conexion->prepare('Select tipos_preguntas.tipo, documentos_lineas.* from '
                . 'documentos_lineas inner join tipos_preguntas on '
                . 'documentos_lineas.id_tipo = tipos_preguntas.id_tipo '
                . 'where documentos_lineas.id_documentos = ? order by documentos_lineas.id_documentos_lineas');
        
        if(!$consulta){
            echo 'Error en la consulta: '. $conexion->error;
            die();
        }
        
        $id_documentos =  $this->getId_documentos();

        if(!$consulta->bind_param('i',$id_documentos)){
            echo 'Error en la consulta: '. $conexion->error;
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