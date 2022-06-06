<?php

class Documentos {

// <editor-fold defaultstate="collapsed" desc="Variables">
    private $id_documento;
    private $nombre_documento;
    private $baja;
    private $fecha_inicio;
    private $fecha_fin;

    // </editor-fold>
//<editor-fold defaultstate="collapsed" desc="Propiedades">
    function getId_documento() {
        return $this->id_documento;
    }

    function getNombre_documento() {
        return $this->nombre_documento;
    }

    function getBaja() {
        return $this->baja;
    }

    function getFecha_inicio() {
        return $this->fecha_inicio;
    }

    function getFecha_fin() {
        return $this->fecha_fin;
    }

    function setId_documento($id_documento) {
        $this->id_documento = $id_documento;
    }

    function setNombre_documento($nombre_documento) {
        $this->nombre_documento = $nombre_documento;
    }

    function setBaja($baja) {
        $this->baja = $baja;
    }

    function setFecha_inicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }

    function setFecha_fin($fecha_fin) {
        $this->fecha_fin = $fecha_fin;
    }

// </editor-fold>
//<editor-fold defaultstate="collapsed" desc="Funciones">

    /**
     * Función que carga los datos por un id
     * @param type $id_documentos id del usuario
     * @return boolean Devuelve true si ha cargado correctamente y false si ha dado algún fallo
     */
    public function CargaDatos($id) {
        $conexion = conexion_bd();

        $consulta_preparada = $conexion->prepare("SELECT * FROM documentos WHERE id_documentos = ?");
        if (!$consulta_preparada) {
            guardar_mensaje("<b>Ha ocurrido un error en la funcion CargaDatos de la clase Documentos.php</b> <br><br>" . $conexion->error);
            die();
        }

        if (!$consulta_preparada->bind_param('i', $id)) {
            guardar_mensaje("<b>Ha ocurrido un error en la funcion CargaDatos de la clase Documentos.php</b> <br><br>" . $consulta_preparada->error);
            die();
        }

        if (!$consulta_preparada->execute()) {
            guardar_mensaje("<b>Ha ocurrido un error en la funcion CargaDatos de la clase Documentos.php</b> <br><br>" . $consulta_preparada->error);
            die();
        }

        if (!$fila = $consulta_preparada->get_result()->fetch_assoc()) {
            guardar_mensaje("<b>Ha ocurrido un error en la funcion CargaDatos de la clase Documentos.php</b> <br><br>" . $consulta_preparada->error);
            return false;
        }

        $this->setId_documento($fila['id_documentos']);
        $this->setNombre_documento($fila['nombre_documento']);
        $this->setBaja($fila['baja']);
        $this->setFecha_inicio($fila['fecha_inicio']);
        $this->setFecha_fin($fila['fecha_fin']);

        $consulta_preparada->close();
        $conexion->close();

        return true;
    }

    /**
     * Función que inserta un registro en la tabla
     * @return boolean Devuelve true si lo ha insertado correctamente y 
     * false si ha dado algún fallo
     */
    public function Insertar() {

        if (empty($_POST['documento_roles'])) {
            guardar_mensaje("Debes de seleccionar al menos un rol");
            header("location: index.php?comando=gestion_documentos");
            die();
        } else if (empty($_POST['documento_cursos'])) {
            guardar_mensaje("Debes de seleccionar al menos un curso");
            header("location: index.php?comando=gestion_documentos");
            die();
        } else if (empty($_POST['documento_asignaturas'])) {
            guardar_mensaje("Debes de seleccionar al menos una asignatura");
            header("location: index.php?comando=gestion_documentos");
            die();
        }
        if ($this->InsertaDocumento()) {

            $array_roles = explode(",", $_POST['documento_roles']);
            $array_cursos = explode(",", $_POST['documento_cursos']);
            $array_asignaturas = explode(",", $_POST['documento_asignaturas']);

            if ($this->InsertaRolDocumento($array_roles, $this->ObtenerUltimoIdDocumento())) {
                
            }

            if ($this->InsertaCursoDocumento($array_cursos, $this->ObtenerUltimoIdDocumento())) {
                
            }

            if ($this->InsertaAsignaturaDocumento($array_asignaturas, $this->ObtenerUltimoIdDocumento())) {
                
            }

            return true;
        }
    }

    /**
     * Función para insertar un Documento en la base de datos.
     * 
     * @return boolean true si es insertado correctamente y false si no.
     */
    public function InsertaDocumento() {
        $conexion = conexion_bd();

        $consulta_preparada = $conexion->prepare("INSERT INTO documentos "
                . "(nombre_documento, baja, fecha_inicio, fecha_fin) "
                . "values (?,?,?,?)");
        if (!$consulta_preparada) {
            echo "Error en la consulta" . $consulta_preparada->error;
            die();
        }

        $nombre_documento = limpiar_datos($this->getNombre_documento());

        $baja = $this->getBaja();

        $fecha_inicio = date($this->getFecha_inicio());
        $fecha_fin = date($this->getFecha_fin());

        $consulta_preparada->bind_param('ssss', $nombre_documento, $baja, $fecha_inicio, $fecha_fin);
        if (!$consulta_preparada->execute()) {
            //Si entra aquí es que ha devuelto falso y ha fallado la consulta
            guardar_mensaje("<b>Ha ocurrido un error en la funcion Insertar de la clase Documentos.php</b> <br><br>" . $consulta_preparada->error);
            die();  //Paramos la ejecución;
        }
        $num_filas_insertadas = $consulta_preparada->affected_rows;
        $conexion->close();
        $consulta_preparada->close();
        if ($num_filas_insertadas == 1) {
            //guardar_mensaje("Documento creado correctamente");
            return true;
        } else {
            guardar_mensaje("Ha habido un error al guardar el documento");
            return false;
        }
    }

    /**
     * Función que inserta un registro en la tabla rol_documentos.
     * 
     * @return boolean Devuelve true si lo ha insertado correctamente y 
     * false si ha dado algún fallo
     */
    public function InsertaRolDocumento($roles, $id_documento) {

        $rol_variable = new Rol();

        foreach ($roles as $rol) {
            $conexion = conexion_bd();

            $consulta_preparada = $conexion->prepare("INSERT INTO rol_documentos "
                    . "(id_documentos, id_rol) "
                    . "values (?,?)");
            if (!$consulta_preparada) {
                echo "Error en la consulta" . $consulta_preparada->error;
                die();
            }

            $rol_variable->obtener_rol_por_nombre(trim($rol));
            $id_rol = $rol_variable->getId_rol();
            $id_doc = $id_documento['id_documentos'];
            $consulta_preparada->bind_param('ii', $id_doc, $id_rol);
            if (!$consulta_preparada->execute()) {
                //Si entra aquí es que ha devuelto falso y ha fallado la consulta
                guardar_mensaje("<b>Ha ocurrido un error en la funcion Insertar de la clase Documentos.php</b> <br><br>" . $consulta_preparada->error);
                die();  //Paramos la ejecución;
            }
            $num_filas_insertadas = $consulta_preparada->affected_rows;
            $conexion->close();
            $consulta_preparada->close();
            if ($num_filas_insertadas != 1) {
                guardar_mensaje("Ha habido un error al guardar el documento");
                return false;
            }
        }
        return true;
    }

    /**
     * Función que inserta un registro en la tabla curso_documentos.
     * 
     * @return boolean Devuelve true si lo ha insertado correctamente y 
     * false si ha dado algún fallo
     */
    public function InsertaCursoDocumento($cursos, $id_documento) {

        $curso_variable = new Cursos();

        foreach ($cursos as $curso) {
            $conexion = conexion_bd();

            $consulta_preparada = $conexion->prepare("INSERT INTO curso_documentos "
                    . "(id_documentos, id_curso) "
                    . "values (?,?)");
            if (!$consulta_preparada) {
                echo "Error en la consulta" . $consulta_preparada->error;
                die();
            }

            $cursos_id = $curso_variable->obtener_curso_por_nombre(trim($curso));

            foreach ($cursos_id as $cur) {

                $id_curso = $cur['id_curso'];
                $id_doc = $id_documento['id_documentos'];
                $consulta_preparada->bind_param('ii', $id_doc, $id_curso);
                if (!$consulta_preparada->execute()) {
                    //Si entra aquí es que ha devuelto falso y ha fallado la consulta
                    guardar_mensaje("<b>Ha ocurrido un error en la funcion Insertar de la clase Documentos.php</b> <br><br>" . $consulta_preparada->error);
                    die();  //Paramos la ejecución;
                }
            }
            
            $num_filas_insertadas = $consulta_preparada->affected_rows;
            $conexion->close();
            $consulta_preparada->close();
            if ($num_filas_insertadas != 1) {
                guardar_mensaje("Ha habido un error al guardar el documento");
                return false;
            }
        }
        return true;
    }

    /**
     * Función que inserta un registro en la tabla asignatura_documentos.
     * 
     * @return boolean Devuelve true si lo ha insertado correctamente y 
     * false si ha dado algún fallo
     */
    public function InsertaAsignaturaDocumento($asignaturas, $id_documento) {

        $asignatura_variable = new Asignaturas();

        foreach ($asignaturas as $asignatura) {
            $conexion = conexion_bd();

            $consulta_preparada = $conexion->prepare("INSERT INTO asignatura_documentos "
                    . "(id_documentos, id_asignatura) "
                    . "values (?,?)");
            if (!$consulta_preparada) {
                echo "Error en la consulta" . $consulta_preparada->error;
                die();
            }

            $asignatura_id = $asignatura_variable->obtener_asignatura_por_nombre(trim($asignatura));
            foreach ($asignatura_id as $asig) {

                $id_asignatura = $asig['id_asignatura'];
                $id_doc = $id_documento['id_documentos'];
                $consulta_preparada->bind_param('ii', $id_doc, $id_asignatura);
                if (!$consulta_preparada->execute()) {
                    //Si entra aquí es que ha devuelto falso y ha fallado la consulta
                    guardar_mensaje("<b>Ha ocurrido un error en la funcion Insertar de la clase Documentos.php</b> <br><br>" . $consulta_preparada->error);
                    die();  //Paramos la ejecución;
                }
            }
            $num_filas_insertadas = $consulta_preparada->affected_rows;
            $conexion->close();
            $consulta_preparada->close();
            if ($num_filas_insertadas != 1) {
                guardar_mensaje("Ha habido un error al guardar el documento");
                return false;
            }
        }
        return true;
    }

    /**
     * Función que modifica un registro en la tabla documentos
     * @return boolean Devuelve true si lo ha modificado correctamente y 
     * false si ha dado algún fallo
     */
    public function Modificar() {
        $conexion = conexionBD();

        $consulta_preparada = $conexion->prepare("UPDATE documentos "
                . "SET nombre_documento = ?, "
                . "baja = ?");
        if (!$consulta_preparada) {
            echo "Error en la consulta" . $consulta_preparada->error;
            die();
        }

        $nombre_documento = $this->nombre_documento;
        $baja = $this->baja;

        $consulta_preparada->bind_param('ss', $nombre_documento, $baja);
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

    /*
     * Función para obtener el id del último documento.
     */
    public function ObtenerUltimoIdDocumento() {
        $conexion = conexion_bd();

        $consulta = 'SELECT id_documentos FROM documentos ORDER BY id_documentos DESC LIMIT 1;';

        if (!$result = $conexion->query($consulta)) {
            echo "Error en la consulta: " . $conexion->error;
            die();
        }

        $filas = $result->fetch_all(MYSQLI_ASSOC);

        $conexion->close();

        if (count($filas) > 0) {
            return $filas[0];
        } else {
            return 1;
        }
    }

    /**
     * Función que elimina un registro en la tabla
     * @return boolean Devuelve true si lo ha eliminado correctamente y 
     * false si ha dado algún fallo
     */
    public static function eliminar_documento($id_documentos) {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare("Delete From documentos where id_documentos = ?");

        if (!$consulta) {
            echo 'Error en la consulta: ' . $conexion->error;
            die();
        }

        if (!$consulta->bind_param('i', $id_documentos)) {
            echo 'Error en la consulta: ' . $conexion->error;
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

    /**
     * Función para obtener todos los documentos
     * @return \Documentos
     */
    public function obtener_todos_documentos() {

        $documentos = array();

        $conexion = conexion_bd();

        $consulta = 'Select * from documentos';

        if (!$result = $conexion->query($consulta)) {
            echo "Error en la consulta: " . $conexion->error;
            die();
        }

        if (!$filas = $result->fetch_all(MYSQLI_ASSOC)) {
            return false;
        }

        $conexion->close();
        /*
          foreach ($filas as $fila) {

          $documento = new Documentos();
          $documento->setId_documento($fila['id_documentos']);
          $documento->setNombre_documento($fila['nombre_documento']);
          $documento->setBaja($fila['baja']);
          $documento->setFecha_inicio($fila['fecha_inicio']);
          $documento->setFecha_fin($fila['fecha_fin']);
          $documentos[] = $documento;
          } */

        return $filas;
    }

    /**
     * Función para obtener todos los documentos relacionados con los respectivos
     * roles.
     * 
     * @return /Documento y false si no.
     */
    public function obtener_documentos_rol() {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare('Select rol.id_rol, rol.rol, documentos.* from '
                . 'documentos inner join rol_documentos on documentos.id_documentos = rol_documentos.id_documentos '
                . 'inner join rol on rol_documentos.id_rol = rol.id_rol '
                . 'inner join personal_rol on rol.id_rol = personal_rol.id_rol '
                . 'inner join personal on personal_rol.id_personal = personal.id_personal '
                . 'where personal.id_personal = ?');

        if (!$consulta) {
            echo 'Error en la consulta: ' . $conexion->error;
            die();
        }

        $id_personal = Sesiones::obtener_id();

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
     * Consulta sql para dar de alta un documento
     */

    public function dar_alta_documento() {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare("Update documentos set baja = 0 where id_documentos = ?");

        if (!$consulta) {
            echo 'Error en la consulta: ' . $conexion->error;
            die();
        }

        $id_documentos = $this->getId_documento();

        if (!$consulta->bind_param('i', $id_documentos)) {
            echo 'Error en la consulta: ' . $conexion->error;
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
     * Consulta sql para dar de baja un documento
     */

    public function dar_baja_documento() {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare("Update documentos set baja = 1 where id_documentos = ?");

        $id_documentos = $this->getId_documento();

        if (!$consulta) {
            echo 'Error en la consulta: ' . $conexion->error;
            die();
        }

        if (!$consulta->bind_param('i', $id_documentos)) {
            echo 'Error en la consulta: ' . $conexion->error;
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

    /**
     * Función que obtiene los documentos del día actual de la base de datos para enviarlos ppor e-mail
     */
    public function documentos_rango_fecha() {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare("Select 'Rol' as consulta, rol.rol as nombre_asignado, personal.id_personal, personal.nombre, documentos.id_documentos, 
        documentos.nombre_documento, personal.email
        From documentos inner join documentos_lineas on documentos.id_documentos = documentos_lineas.id_documentos
        inner join rol_documentos on rol_documentos.id_documentos = documentos.id_documentos
        inner join rol on rol.id_rol = rol_documentos.id_rol
        inner join personal_rol on personal_rol.id_rol = rol.id_rol
        inner join personal on personal.id_personal = personal_rol.id_personal
        WHERE documentos.fecha_inicio = CONVERT(now(),DATE)
        GROUP BY documentos.id_documentos, personal.nombre
        
        UNION ALL
        
        Select 'Curso' as consulta, cursos.nombre_curso as nombre_asignado, personal.id_personal, personal.nombre, documentos.id_documentos,
         documentos.nombre_documento, personal.email
        From documentos inner join documentos_lineas on documentos.id_documentos = documentos_lineas.id_documentos
        inner join curso_documentos on curso_documentos.id_documentos = documentos.id_documentos
        inner join cursos on cursos.id_curso = curso_documentos.id_curso
        inner join personal_curso on personal_curso.id_curso = cursos.id_curso
        inner join personal on personal.id_personal = personal_curso.id_personal
        WHERE documentos.fecha_inicio = CONVERT(now(),DATE)
        GROUP BY documentos.id_documentos, personal.nombre
        
        UNION ALL
        
        Select 'Asignatura' as consulta, asignaturas.nombre_asignatura as nombre_asignado, personal.id_personal, personal.nombre, documentos.id_documentos,
         documentos.nombre_documento, personal.email
        From documentos inner join documentos_lineas on documentos.id_documentos = documentos_lineas.id_documentos
        inner join asignatura_documentos on asignatura_documentos.id_documentos = documentos.id_documentos
        inner join asignaturas on asignaturas.id_asignatura = asignatura_documentos.id_asignatura
        inner join personal_asignatura on personal_asignatura.id_asignatura = asignaturas.id_asignatura
        inner join personal on personal.id_personal = personal_asignatura.id_personal
        WHERE documentos.fecha_inicio = CONVERT(now(),DATE)
        GROUP BY documentos.id_documentos, personal.nombre");

        if (!$consulta) {
            echo "Error en la consulta. " . $conexion->error;
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
     * Función que obtiene de la base de datos los documentos que han sido res-
     * pondidos.
     */

    public function documentos_cursos_con_respuestas() {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare("Select documentos.*, respuestas.*, cursos.nombre_curso, respondido.id_curso, respondido.id_asignatura 
FROM respuestas inner join documentos_lineas on respuestas.id_documentos_lineas = documentos_lineas.id_documentos_lineas
inner join documentos on documentos_lineas.id_documentos = documentos.id_documentos
inner join respondido on documentos.id_documentos = respondido.id_documentos
inner join cursos on respondido.id_curso = cursos.id_curso
inner join asignaturas on respondido.id_asignatura = respondido.id_asignatura
GROUP by respondido.id_asignatura, nombre_documento");

        if (!$consulta) {
            echo 'Error' . $conexion->error;
            die();
        }

        if (!$consulta->execute()) {
            echo "Error al ejecutar la consulta " . $consulta->error;
            die();
        }

        if (!$result = $consulta->get_result()->fetch_all(MYSQLI_ASSOC)) {
            return $result;
        }

        $consulta->close();
        $conexion->close();

        return $result;
    }

    /*
     * Función que obtiene de la base de datos los documentos que han sido res-
     * pondidos.
     */

    public function documentos_roles_con_respuestas() {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare("Select documentos.*, respuestas.*, rol.rol, rol.id_rol 
From respondido inner join rol on respondido.id_rol = rol.id_rol
inner join documentos on respondido.id_documentos = documentos.id_documentos
inner join documentos_lineas on documentos.id_documentos = documentos_lineas.id_documentos
inner join respuestas on documentos_lineas.id_documentos_lineas = respuestas.id_documentos_lineas
GROUP by respondido.id_respondido");

        if (!$consulta) {
            echo 'Error' . $conexion->error;
            die();
        }

        if (!$consulta->execute()) {
            echo "Error al ejecutar la consulta " . $consulta->error;
            die();
        }

        if (!$result = $consulta->get_result()->fetch_all(MYSQLI_ASSOC)) {
            return $result;
        }

        $consulta->close();
        $conexion->close();

        return $result;
    }

    /*
     * Función para comprobar si un documento está fuera de rango o no
     */

    public function documento_fuera_rango() {

        $conexion = conexion_bd();

        $consulta = $conexion->prepare("Select fecha_inicio, fecha_fin from documentos where id_documentos = ? ");

        if (!$consulta) {
            echo 'Error ' . $conexion->error;
            die();
        }

        $id_documentos = $this->getId_documento();

        if (!$consulta->bind_param("i", $id_documentos)) {
            echo 'Error en la consulta' . $consulta->error;
            die();
        }

        if (!$consulta->execute()) {
            echo "Error al ejecutar la consulta " . $consulta->error;
            die();
        }

        if (!$result = $consulta->get_result()->fetch_assoc()) {
            return false;
        }

        $consulta->close();
        $conexion->close();

        return $result;
    }

// </editor-fold>
}
