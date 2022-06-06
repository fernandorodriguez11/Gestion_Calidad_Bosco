<?php

use Dompdf\Dompdf;

class ControladorDocumentos {
    /*
     * Función que carga la vista para poder crear un documento con sus 
     * respectivas preguntas. A este documento se le asignan roles, cursos y
     * asignaturas.
     */
    public function gestion_documentos() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { //Insertamos el documento
            $documentos = new Documentos();
            //Cargamos los datos
            if (empty($_POST['documento_nombre'])) {
                guardar_mensaje("Nombre del documento obligatorio");
                header("location: index.php?comando=gestion_documentos");
                die();
            }
            $documentos->setNombre_documento($_POST['documento_nombre']);

            if (isset($_POST['baja'])) {
                $documentos->setBaja(1);
            } else {
                $documentos->setBaja(0);
            }

            $documentos->setFecha_inicio($_POST['documento_fecha_inicio']);
            $documentos->setFecha_fin($_POST['documento_fecha_fin']);

            if (empty($_POST['arrayContenido']) || !isset($_POST['arrayContenido'])) {
                guardar_mensaje("Es necesario que añadas contenido");
                header("location: index.php?comando=gestion_documentos");
                die();
            }

            if ($documentos->Insertar()) {

                $nuevoId = $documentos->ObtenerUltimoIdDocumento();

                $documento_lineas = new DocumentosLineas();

                $contenido_en_string = explode("/", $_POST['arrayContenido']);
                unset($contenido_en_string[0]);
                $contenido = array_chunk($contenido_en_string, 3);

                for ($i = 0; $i < count($contenido); $i++) {

                    $documento_lineas->setId_documentos($nuevoId['id_documentos']);
                    $documento_lineas->setTitulo($contenido[$i][0]);
                    $documento_lineas->setDescripcion($contenido[$i][1]);

                    $tipos = new TiposPreguntas();
                    $tipos->obtener_id_pregunta($contenido[$i][2]);

                    $documento_lineas->setId_tipo($tipos->getIdTipoPregunta());

                    $documento_lineas->insertar_contenido();
                }

                header("location: index.php?comando=bienvenido");
            } else {
                guardar_mensaje("Error al insertar el mensaje");
                require '../app/vistas/insertar_mensaje.php';
            }
        } else { //Cargamos la ventana para insertar documentos
            if (Sesiones::existe_variable_sesion()) {

                $rol = New Rol();
                $lista_roles = $rol->obtener_todos_roles();

                $curso = New Cursos();
                $lista_cursos = $curso->obtener_todos_cursos();

                $asignatura = New Asignaturas();
                $lista_asignaturas = $asignatura->obtener_todas_asignaturas();

                $type_preguntas = new TiposPreguntas();
                $tipos_preguntas = $type_preguntas->obtener_todas_preguntas();

                require '../app/vistas/gestion_documentos.php';
            } else {
                require 'index.html';
            }
        }
    }

    /*
     * Función para obtener todos los documentos que tiene asignado el usuariio
     * según su rol, curso y asignaturas.
     */

    public function cargar_documento() {

        $id_personal = Sesiones::obtener_id();

        $documento = new Documentos();
        $rol = new Rol();
        $asignatura = new Asignaturas();
        $curso = new Cursos();

        //$documentos = $documento->obtener_todos_documentos();
        $roles = $rol->obtener_rol_por_usuario($id_personal);
        $document = $documento->obtener_documentos_rol();
        $group_subject = $asignatura->agrupar_asignaturas();
        $group = $curso->obtener_cursos_documentos();
        $subject = $asignatura->obtener_asignaturas_documentos();

        $todos_documentos = $documento->obtener_todos_documentos();

        require '../app/vistas/mostrar_documentos.php';
    }

    /*
     * Función llamada en ajax. Nos obtiene los tipos de preguntas para 
     * poder crear una vista.
     */

    public function carga_datos_documento() {

        $documentoLinea = new DocumentosLineas();
        $documentoLinea->setId_documentos($_POST['id_documento']);

        $contenidos = $documentoLinea->obtener_documentos_tiposPreguntas();

        print_r(json_encode($contenidos));
    }

    /**
     * Función llamada por ajax para saber si un documento está respondido o no.
     */
    public function carga_contestado() {

        $respondido = new Respondido();

        $respondido->setId_documentos($_GET['id_documento']);

        //Posible cambio
        $respondido->setId_personal(Sesiones::obtener_id());

        //Compruebo si responde por asignatura y curso o por rol
        if (Sesiones::saber_sies_admin() == true) {
            $respondido->setId_rol(1);
        } else {
            if (!isset($_GET['id_rol'])) {

                $respondido->setId_rol(0);
            } else {
                $respondido->setId_rol($_GET['id_rol']);
            }
        }

        if (!isset($_GET['id_asignatura']) && !isset($_GET['id_curso'])) {
            $respondido->setId_asignatura(0);
            $respondido->setId_curso(0);
        } else {
            $respondido->setId_asignatura($_GET['id_asignatura']);
            $respondido->setId_curso($_GET['id_curso']);
        }

        $respondidos = $respondido->saber_si_esta_respondido();

        print_r(json_encode($respondido->getRespondido()));

        //print_r(json_encode($respondidos));
    }

    /**
     * Función que es llamada pora ajax para comprobar si un documento está 
     * habilitado para responder o no lo está.
     */
    public function esta_fuera_rango() {

        $documento = new Documentos();

        $documento->setId_documento($_GET['id_documento']);

        $documentos = $documento->documento_fuera_rango();

        print_r(json_encode($documentos));
        /* if ($documento->documento_fuera_rango() == true) {
          print_r(json_encode('fuera de rango'));
          } else {
          print_r(json_encode('dentro de rango'));
          } */
    }

    /*
     * Función que es llamada por ajax. Con pasandole por get el id_documento
     * obtenemos los tipos de preguntas para poder insertar las respuestas.
     */

    public function cargar_contenido() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') { //Insertamos las lineas del documento
            $documentoLinea = new DocumentosLineas();

            $documentoLinea->setId_documentos($_GET['id_documento']);

            $contenidos = $documentoLinea->obtener_documentos_tiposPreguntas();

            $respuestas = new Respuestas();

            for ($index = 0; $index < count($contenidos); $index ++) {
                $respuestas->setId_documentos_lineas($contenidos[$index]['id_documentos_lineas']);
                $respuestas->setId_personal(Sesiones::obtener_id());

                if ($contenidos[$index]['tipo'] == "texto") {
                    $valor = 'textarea_' . $contenidos[$index]['id_documentos_lineas'];
                    $respuestas->setRespuestas($_POST[$valor]);
                    $respuestas->setPrevistas(0);
                    $respuestas->setImpartidas(0);
                } else if ($contenidos[$index]['tipo'] == "puntuacion") {
                    $respuestas->setRespuestas($_POST['radio_' . $contenidos[$index]['id_documentos_lineas']]);
                    $respuestas->setPrevistas(0);
                    $respuestas->setImpartidas(0);
                } else if ($contenidos[$index]['tipo'] == "calculo_porcentaje") {

                    $respuestas->setRespuestas($_POST['number_porcentaje_' . $contenidos[$index]['id_documentos_lineas']]);
                    $respuestas->setPrevistas($_POST['number_previstas_' . $contenidos[$index]['id_documentos_lineas']]);
                    $respuestas->setImpartidas($_POST['number_impartidas_' . $contenidos[$index]['id_documentos_lineas']]);
                }

                if (!isset($_GET['id_rol'])) {
                    if (Sesiones::saber_sies_admin() == true) {
                        $respuestas->setRol(1);
                    } else {
                        $respuestas->setRol(0);
                    }
                } else {
                    $respuestas->setRol($_GET['id_rol']);
                }

                if (!isset($_GET['id_asignatura']) && !isset($_GET['id_curso'])) {
                    $respuestas->setAsignatura(0);
                    $respuestas->setCurso(0);
                } else {
                    $respuestas->setAsignatura($_GET['id_asignatura']);
                    $respuestas->setCurso($_GET['id_curso']);
                }

                $respuestas->insertar_respuesta();
            }
            //Una vez insertadas las respuestas se pone respondido a 1
            $respondido = new Respondido();

            $respondido->setId_documentos($_GET['id_documento']);

            //Posible cambio
            $respondido->setId_personal(Sesiones::obtener_id());

            //Compruebo si responde por asignatura y curso o por rol
            if (!isset($_GET['id_rol'])) {
                if (Sesiones::saber_sies_admin() == true) {
                    $respondido->setId_rol(1);
                } else {
                    $respondido->setId_rol(0);
                }
            } else {
                $respondido->setId_rol($_GET['id_rol']);
            }

            if (!isset($_GET['id_asignatura']) && !isset($_GET['id_curso'])) {
                $respondido->setId_asignatura(0);
                $respondido->setId_curso(0);
            } else {
                $respondido->setId_asignatura($_GET['id_asignatura']);
                $respondido->setId_curso($_GET['id_curso']);
            }

            $respondido->insertar_sies_respondido();

            header("location: index.php?comando=cerrar_sesion");
        } else {

            if (isset($_GET['id_usuario'])) {
                $personal = new Personal();
                $personal->setId($_GET['id_usuario']);
                $personal->loggin_por_id();

                Sesiones::iniciarSesion($personal);
            }

            $documentoLinea = new DocumentosLineas();
            $documentoLinea->setId_documentos($_GET['id_documento']);

            $contenidos = $documentoLinea->obtener_documentos_tiposPreguntas();

            require '../app/vistas/contenido_documentos.php';
        }
    }

    /**
     * Función para listar en una vista html los documentos a los que se puede
     * obtener un informe.
     */
    public function listado_control_final_evaluacion() {

        $documentos = new Documentos();
        $rol = new Rol();
        $cursos = new Cursos();
        
        $todos_cursos = $cursos->obtener_cursos_doc();
        
        $documents = $documentos->documentos_cursos_con_respuestas();
        
        $roles = $documentos->documentos_roles_con_respuestas();
        
        $todos_roles = $rol->obtener_todos_roles();
        require "../app/vistas/listado_documentos_a_descargar.php";
    }

    /* Función que obtiene las respuestas de la base de datos para mostrar
     * un informe en formato pdf 
     */

    public function descargar_documento() {

        $respuestas = new Respuestas();

        $id_documento = $_GET['id_documento'];

        $variable = "";
        $dompdf = new Dompdf();
        if (!isset($_GET['id_curso'])) {
            
        } else {
            $id_curso = $_GET['id_curso'];
            $todas_respuestas = $respuestas->dowload_documentos($id_documento, $id_curso);

            foreach ($todas_respuestas as $respuesta) {

                $nombre = $respuesta['nombre'];
                $asignatura = $respuesta['aba'];
                $curso = $respuesta['abc'];
                $nombre_documento = $respuesta['nombre_documento'];
                $previstas = $respuesta['previstas'];
                $impartidas = $respuesta['impartidas'];
                $total = $respuesta['respuestas'];
                $variable .= '<tr><td style="text-align: center;">' . $nombre . '</td>
      <td style="text-align: center;">' . $nombre_documento . '</td>
      <td style="text-align: center;">' . $asignatura . '</td>
      <td colspan="2" style="text-align: center;">' . $previstas . '</td>
      <td colspan="2" style="text-align: center;">' . $impartidas . '</td>
      <td colspan="4" style="text-align: center;">' . $total . '</td></tr>';
            }
            $dompdf->loadHtml('<html>
      <table border="1">
      <tr>
      <td valign="top" style="font-size: 11px; padding-right: 25px; padding-left: 25px; font-weight:bold;">CURSO ESCOLAR</td>
      <td colspan="6" style="font-size:18px; font-weight:bold; padding-top: 25px; padding-bottom: 25px; padding-right: 230px; padding-left: 230px;">CONTROL FINAL DE EVALUACIÓN - TUTOR/A-</td>
      <td colspan="4" valign="top" style="font-size: 11px; padding-right: 18px; padding-left: 2px; font-weight:bold;">EVALUACIÓN</td>
      </tr>
      <tr>
      <td colspan="9" style="padding: 8px; background-color: grey;" ></td>
      </tr>
      <tr>
      <td valign="top" style="font-size: 11px; padding-bottom: 40px; padding-right: 25px; padding-left: 2px; font-weight:bold;">CURSO Y GRUPO: <span style="font-size: 16px">' . $curso . '</span></td>
      <td valign="top" style="font-size: 11px; padding-bottom: 40px; padding-right: 25px; padding-left: 2px; font-weight:bold;">Nº DE ALUMNOS:</td>
      <td colspan="5" valign="top" style="font-size: 11px; padding-bottom: 40px; padding-right: 25px; padding-left: 2px; font-weight:bold;">TUTOR/A:</td>
      <td colspan="4" valign="top" style="font-size: 11px; padding-bottom: 40px; padding-right: 18px; padding-left: 2px; font-weight:bold;">FIRMA:</td>
      </tr>
      <tr>
      <td colspan="9" style="padding: 8px; background-color: grey;" ></td>
      </tr>
      <tr>
      <td colspan="2" valign="top" style="font-size: 11px; padding-bottom: 0px; padding-right: 150px; padding-left: 150px; font-weight:bold;">PROFESOR/A</td>
      <td valign="top" style="font-size: 11px; padding-bottom: 0px; padding-right: 110px; padding-left: 110px; font-weight:bold;">AREA, MATERIA O MODULO</td>
      <td colspan="9">
      <table colspan="9" style="width:100%" border="1">
      <tr>
      <td colspan="9" valign="top" style="text-align: center; font-size: 11px; padding-bottom: 2px; padding-top: 2px; padding-right: 0px; padding-left: 0px; font-weight:bold;">NÚMERO DE UNIDADES, TEMAS O BLOQUES IMPARTIDOS</td>
      </tr>
      <tr>
      <td colspan="3" valign="top" style="text-align: center; font-size: 11px; padding-bottom: 2px; padding-top: 2px; padding-right: 17px; padding-left: 17px; font-weight:bold;">PREVISTOS</td>
      <td colspan="3" valign="top" style="text-align: center; font-size: 11px; padding-bottom: 2px; padding-top: 2px; padding-right: 16px; padding-left: 16px; font-weight:bold;">IMPARTIDOS</td>
      <td colspan="3" valign="top" style="font-size: 11px; padding-bottom: 2px; padding-top: 2px; padding-right: 43px; padding-left: 43px; font-weight:bold;">%</td>
      </tr>
      </table>
      </td>
      
      </tr>
     
      ' . $variable . '
      
      <tr>
      <td colspan="3" style="font-weight: bold;">Adjuntar copia con el Acta de Sesión de Evaluación</std>
      <td colspan="4" style="font-weight: bold; padding-bottom: 1px; padding-top: 1px; padding-right: 1px; padding-left: 125px;">MEDIA:</td>
      <td colspan="4" style=" padding-bottom: 1px; padding-top: 1px; padding-right: 30px; padding-left: 50px;">x</td>
      </tr>
      </table></html>');
        }

        if (!isset($_GET['id_rol'])) {
            
        } else {
            $id_rol = $_GET['id_rol'];
            $todas_respuestas = $respuestas->dowload_documentos_rol($id_documento, $id_rol);

            foreach ($todas_respuestas as $respuesta) {

                $nombre = $respuesta['nombre'];
                //$asignatura = $respuesta['aba'];
                $rol = $respuesta['rol'];
                $nombre_documento = $respuesta['nombre_documento'];
                $previstas = $respuesta['previstas'];
                $impartidas = $respuesta['impartidas'];
                $total = $respuesta['respuestas'];
                $variable .= '<tr><td style="text-align: center;">' . $nombre . '</td>
      <td style="text-align: center;">' . $nombre_documento . '</td>
      <td style="text-align: center;"></td>
      <td colspan="2" style="text-align: center;">' . $previstas . '</td>
      <td colspan="2" style="text-align: center;">' . $impartidas . '</td>
      <td colspan="4" style="text-align: center;">' . $total . '</td></tr>';
            }
            $dompdf->loadHtml('<html>
      <table border="1">
      <tr>
      <td valign="top" style="font-size: 11px; padding-right: 25px; padding-left: 25px; font-weight:bold;">CURSO ESCOLAR</td>
      <td colspan="6" style="font-size:18px; font-weight:bold; padding-top: 25px; padding-bottom: 25px; padding-right: 230px; padding-left: 230px;">CONTROL FINAL DE EVALUACIÓN - TUTOR/A-</td>
      <td colspan="4" valign="top" style="font-size: 11px; padding-right: 18px; padding-left: 2px; font-weight:bold;">EVALUACIÓN</td>
      </tr>
      <tr>
      <td colspan="9" style="padding: 8px; background-color: grey;" ></td>
      </tr>
      <tr>
      <td valign="top" style="font-size: 11px; padding-bottom: 40px; padding-right: 25px; padding-left: 2px; font-weight:bold;">ROL: <span style="font-size: 16px">' . $rol . '</span></td>
      <td valign="top" style="font-size: 11px; padding-bottom: 40px; padding-right: 25px; padding-left: 2px; font-weight:bold;">Nº DE ALUMNOS:</td>
      <td colspan="5" valign="top" style="font-size: 11px; padding-bottom: 40px; padding-right: 25px; padding-left: 2px; font-weight:bold;">TUTOR/A:</td>
      <td colspan="4" valign="top" style="font-size: 11px; padding-bottom: 40px; padding-right: 18px; padding-left: 2px; font-weight:bold;">FIRMA:</td>
      </tr>
      <tr>
      <td colspan="9" style="padding: 8px; background-color: grey;" ></td>
      </tr>
      <tr>
      <td colspan="2" valign="top" style="font-size: 11px; padding-bottom: 0px; padding-right: 150px; padding-left: 150px; font-weight:bold;">PROFESOR/A</td>
      <td valign="top" style="font-size: 11px; padding-bottom: 0px; padding-right: 110px; padding-left: 110px; font-weight:bold;">AREA, MATERIA O MODULO</td>
      <td colspan="9">
      <table colspan="9" style="width:100%" border="1">
      <tr>
      <td colspan="9" valign="top" style="text-align: center; font-size: 11px; padding-bottom: 2px; padding-top: 2px; padding-right: 0px; padding-left: 0px; font-weight:bold;">NÚMERO DE UNIDADES, TEMAS O BLOQUES IMPARTIDOS</td>
      </tr>
      <tr>
      <td colspan="3" valign="top" style="text-align: center; font-size: 11px; padding-bottom: 2px; padding-top: 2px; padding-right: 17px; padding-left: 17px; font-weight:bold;">PREVISTOS</td>
      <td colspan="3" valign="top" style="text-align: center; font-size: 11px; padding-bottom: 2px; padding-top: 2px; padding-right: 16px; padding-left: 16px; font-weight:bold;">IMPARTIDOS</td>
      <td colspan="3" valign="top" style="font-size: 11px; padding-bottom: 2px; padding-top: 2px; padding-right: 43px; padding-left: 43px; font-weight:bold;">%</td>
      </tr>
      </table>
      </td>
      
      </tr>
     
      ' . $variable . '
      
      <tr>
      <td colspan="3" style="font-weight: bold;">Adjuntar copia con el Acta de Sesión de Evaluación</std>
      <td colspan="4" style="font-weight: bold; padding-bottom: 1px; padding-top: 1px; padding-right: 1px; padding-left: 125px;">MEDIA:</td>
      <td colspan="4" style=" padding-bottom: 1px; padding-top: 1px; padding-right: 30px; padding-left: 50px;">x</td>
      </tr>
      </table></html>');
        }

        //$dompdf->setPaper(array(0, 0, 841.89, 1080.55), 'landscape');

        $dompdf->setPaper(array(0, 0, 841.89, 1000), 'landscape');

        $dompdf->render();

        //$dompdf->stream('Listado de Control Final de Evaluación '.$nombre_documento);

        $dompdf->stream('LCFE ' . $nombre_documento);
    }

    /*
     * Función para dar de alta un documento dado de baja
     */

    public function dar_alta() {

        $documento = new Documentos();

        $documento->setId_documento($_GET['id_documento']);

        $documento->dar_alta_documento();

        header('Location: index.php?comando=cargar_documento');
    }

    /*
     * Función para dar de baja un documento dado de alta
     */

    public function dar_baja() {

        $documento = new Documentos();

        $documento->setId_documento($_GET['id_documento']);

        $documento->dar_baja_documento();

        header('Location: index.php?comando=cargar_documento');
    }

}