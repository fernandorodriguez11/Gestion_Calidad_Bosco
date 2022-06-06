<?php

class ControladorImportacion
{

    public function importar_datos_antiguo()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $lista_roles = array();
            $lista_profesores = array();
            $lista_alumnos = array();

            //ELIMINAMOS SI EXISTEN LOS FICHEROS
            //////////////////////////////////////////////////////////////////////////

            if (file_exists("files_import/Roles.csv")) {
                unlink("files_import/Roles.csv");
            }

            if (file_exists("files_import/Profesores.csv")) {
                unlink("files_import/Profesores.csv");
            }

            if (file_exists("files_import/Alumnos.csv")) {
                unlink("files_import/Alumnos.csv");
            }


            //////////////////////////////////////////////////////////////////////////
            //COPIAMOS LOS ARCHIVOS A NUESTRO DIRECTORIO
            //////////////////////////////////////////////////////////////////////////
            $comprobador = true;

            for ($index = 0; count($_FILES) < $index; $index++) {
                if ($_FILES[$index]['type'] != 'application/vnd.ms-excel' && $_FILES[$index]['type'] != '') {
                    $comprobador = false;
                    guardar_mensaje("Formato no valido de archivo");
                    header('Location: index.php?comando=importar_datos');
                    die();
                }
            }

            if ($comprobador) {
                if ($_FILES['archivo_roles']['tmp_name'] != "") {
                    move_uploaded_file($_FILES['archivo_roles']['tmp_name'], "files_import/Roles.csv");
                }

                if ($_FILES['archivo_profesores']['tmp_name'] != "") {
                    move_uploaded_file($_FILES['archivo_profesores']['tmp_name'], "files_import/Profesores.csv");
                }

                if ($_FILES['archivo_alumnos']['tmp_name'] != "") {
                    move_uploaded_file($_FILES['archivo_alumnos']['tmp_name'], "files_import/Alumnos.csv");
                }
            }

            //////////////////////////////////////////////////////////////////////////
            //IMPORTACIÓN ROLES
            //////////////////////////////////////////////////////////////////////////
            if (file_exists("files_import/Roles.csv")) {

                $linea = 0;
                $archivo = fopen("files_import/roles.csv", "r");

                while (($datos = fgetcsv($archivo, ",")) == true) {
                    $num = count($datos);
                    $linea++;
                    //Recorremos las columnas de esa linea

                    if ($datos[2] != "CARGO" && !in_array(utf8_decode($datos[2]), $lista_roles) && trim(utf8_decode($datos[2])) != "") {
                        $rol = new Rol();
                        if (!$rol->obtener_rol_por_nombre(utf8_decode($datos[2]))) {
                            if (!in_array(utf8_decode($datos[2]), $lista_roles)) {
                                $lista_roles[] = utf8_decode($datos[2]);
                            }
                        }
                    }
                }
                fclose($archivo);

                foreach ($lista_roles as $rol_unico) {
                    $rol = new Rol();
                    $rol->setRol($rol_unico);
                    $rol->inserta_rol();
                }
            }

            //////////////////////////////////////////////////////////////////////////
            //IMPORTACIÓN PROFESORES
            //////////////////////////////////////////////////////////////////////////
            if (file_exists("files_import/Profesores.csv")) {

                $linea = 0;
                $archivo = fopen("files_import/profesores.csv", "r");

                while (($datos = fgetcsv($archivo, ",")) == true) {
                    $profesor = array();
                    $num = count($datos);
                    $linea++;
                    //Recorremos las columnas de esa linea
                    //CODIGO
                    if ($datos[0] != "CODIGO" && trim(utf8_decode($datos[0])) != "") {
                        $profesor[0] = utf8_decode($datos[0]);
                    }

                    //NOMBRE
                    if ($datos[2] != "NOMBRE" && trim(utf8_decode($datos[2])) != "") {
                        $profesor[2] = utf8_decode($datos[2]);
                    }

                    //APELLIDOS
                    if ($datos[1] != "APELLIDOS" && trim(utf8_decode($datos[1])) != "") {
                        $profesor[2] .= ' ' . utf8_decode($datos[1]);
                    }

                    //PASSWORD
                    if (($datos[9] != "PASSWORD") && trim(utf8_decode($datos[9])) != "") {
                        if ($datos[9] != "Column10") {
                            //$profesor[3] = utf8_decode($datos[9]);
                            $profesor[3] = "1234";
                        }
                    } else {
                        $profesor[3] = "1234";
                    }

                    //EMAIL
                    if ($datos[19] != "EMAIL" && trim(utf8_decode($datos[19])) != "") {
                        $profesor[4] = utf8_decode($datos[19]);
                    }

                    if (count($profesor) > 0) {
                        $personal = new Personal();
                        if (!$personal->obtener_email($profesor[4])) {
                            $lista_profesores[] = $profesor;
                        }
                    }
                }

                fclose($archivo);

                foreach ($lista_profesores as $profesor_unico) {
                    $personal = new Personal();

                    $personal->setNombre($profesor_unico[2]);
                    //$personal->setApellidos($profesor_unico[1]);
                    $personal->setEmail($profesor_unico[4]);
                    $personal->setPassword($profesor_unico[3]);
                    $personal->setCookie('');

                    $personal->Insertar();
                }
            }

            //////////////////////////////////////////////////////////////////////////
            //UNIÓN ENTRE PROFESORES Y ROLES
            //////////////////////////////////////////////////////////////////////////

            foreach ($lista_profesores as $profesor_unico) {
                $codigo_profesor = $profesor_unico[0];

                foreach ($lista_roles as $rol_unico) {
                    if ($codigo_profesor == $rol_unico[0]) {
                        $personal->InsertaRelacionPersonalRol($profesor_unico[4], $rol_unico[1]);
                    }
                }
            }

            //////////////////////////////////////////////////////////////////////////
            //IMPORTACIÓN ALUMNOS
            //////////////////////////////////////////////////////////////////////////
            if (file_exists("files_import/Alumnos.csv")) {

                $linea = 0;
                $archivo = fopen("files_import/alumnos.csv", "r");

                while (($datos = fgetcsv($archivo, ",")) == true) {
                    $alumno = array();
                    $num = count($datos);
                    $linea++;
                    //Recorremos las columnas de esa linea
                    //CODIGO
                    if ($datos[0] != "ALUMNO" && trim($datos[0]) != "") {
                        $alumno[0] = $datos[0];
                    }

                    //APELLIDOS
                    if ($datos[1] != "APELLIDOS" && trim($datos[1]) != "") {
                        $alumno[1] = $datos[1];
                    }

                    //NOMBRE
                    if ($datos[2] != "NOMBRE" && trim($datos[2]) != "") {
                        $alumno[2] = $datos[2];
                    }

                    //PASSWORD
                    if ($datos[2] != "NOMBRE" && trim($datos[2]) != "") {
                        $alumno[3] = "";
                    }

                    //EMAIL
                    if ($datos[19] != "EMAIL" && trim($datos[22]) != "") {
                        $alumno[4] = $datos[19];
                    }

                    if (count($alumno) > 0) {
                        $personal = new Personal();
                        if (!$personal->obtener_email($alumno[4])) {
                            $lista_alumnos[] = $alumno;
                        }
                    }
                }

                fclose($archivo);

                foreach ($lista_alumnos as $alumno_unico) {
                    $personal = new Personal();

                    $personal->setNombre($alumno_unico[2]);
                    $personal->setApellidos($alumno_unico[1]);
                    $personal->setEmail($alumno_unico[4]);
                    $personal->setPassword($alumno_unico[3]);
                    $personal->setCookie('');

                    $personal->Insertar();
                }
            }

            //////////////////////////////////////////////////////////////////////////
            //UNIÓN ENTRE ALUMNOS Y ROLES
            //////////////////////////////////////////////////////////////////////////


            foreach ($lista_alumnos as $alumno_unico) {
                $personal->InsertaRelacionPersonalRol($profesor_unico[4], $rol_unico[1]);
            }

            //////////////////////////////////////////////////////////////////////////
            //ELIMINAMOS SI EXISTEN LOS FICHEROS
            //////////////////////////////////////////////////////////////////////////
            if (file_exists("files_import/Roles.csv")) {
                unlink("files_import/Roles.csv");
            }

            if (file_exists("files_import/Profesores.csv")) {
                unlink("files_import/Profesores.csv");
            }

            if (file_exists("files_import/Alumnos.csv")) {
                unlink("files_import/Alumnos.csv");
            }


            //////////////////////////////////////////////////////////////////////////
        } else {
            require '../app/vistas/importacion_datos.php';
        }
    }
    public function importar_datos()
    {


        $lista_profesores = array();
        $lista_alumnos = array();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (file_exists("files_import/Roles.csv")) {
                unlink("files_import/Roles.csv");
            }

            if (file_exists("files_import/Profesores.csv")) {
                unlink("files_import/Profesores.csv");
            }

            if (file_exists("files_import/Alumnos.csv")) {
                unlink("files_import/Alumnos.csv");
            }




            for ($index = 0; count($_FILES) < $index; $index++) {
                if ($_FILES[$index]['type'] != 'application/vnd.ms-excel' && $_FILES[$index]['type'] != '') {
                    $comprobador = false;
                    guardar_mensaje("Formato no valido de archivo");
                    header('Location: index.php?comando=importar_datos');
                    die();
                }
            }

            if ($comprobador) {
                if ($_FILES['archivo_roles']['tmp_name'] != "") {
                    move_uploaded_file($_FILES['archivo_roles']['tmp_name'], "files_import/Roles.csv");
                }

                if ($_FILES['archivo_profesores']['tmp_name'] != "") {
                    move_uploaded_file($_FILES['archivo_profesores']['tmp_name'], "files_import/Profesores.csv");
                }

                if ($_FILES['archivo_alumnos']['tmp_name'] != "") {
                    move_uploaded_file($_FILES['archivo_alumnos']['tmp_name'], "files_import/Alumnos.csv");
                }
            }
        } else {
            require '../app/vistas/importacion_datos.php';
        }
    }

    public function importa_roles_archivo()
    {
        $lista_roles = array();

        //////////////////////////////////////////////////////////////////////////
        //COPIAMOS LOS ARCHIVOS A NUESTRO DIRECTORIO
        //////////////////////////////////////////////////////////////////////////
        $comprobador = true;


        for ($index = 0; count($_FILES) < $index; $index++) {
            if ($_FILES[$index]['type'] != 'application/vnd.ms-excel' && $_FILES[$index]['type'] != '') {
                $comprobador = false;
                guardar_mensaje("Formato no valido de archivo");
                header('Location: index.php?comando=importar_datos');
                die();
            }
        }

        if ($comprobador) {
            if ($_FILES['archivo_roles']['tmp_name'] != "") {
                move_uploaded_file($_FILES['archivo_roles']['tmp_name'], "files_import/Roles.csv");
            }
        }



        //////////////////////////////////////////////////////////////////////////
        //IMPORTACIÓN ROLES
        //////////////////////////////////////////////////////////////////////////
        if (file_exists("files_import/Roles.csv")) {

            $linea = 0;
            $archivo = fopen("files_import/roles.csv", "r");

            while (($datos = fgetcsv($archivo, ",")) == true) {
                $num = count($datos);
                $linea++;
                //Recorremos las columnas de esa linea

                if ($datos[2] != "CARGO" && !in_array(utf8_decode($datos[2]), $lista_roles) && trim(utf8_decode($datos[2])) != "") {
                    $lista_roles[] = $datos;
                }
            }

            fclose($archivo);

            $rol = new Rol();

            for ($index = 0; count($lista_roles) < $index; $index++){
                $rol->inserta_importacion_rol($lista_roles[$index][0], $lista_roles[$index][1], $lista_roles[$index][2]);
            }



        }
    }
}
