<?php 

     $documentos_hoy = new Documentos();

     $resultados = $documentos_hoy->documentos_rango_fecha();

     foreach($resultados as $resultado){

        $id_personal = $resultado['id_personal'];
        $id_documentos = $resultado['id_documentos'];
        $nombre_asignado = $resultado['nombre_asignado'];
        $nombre = $resultado['nombre'];
        $consulta = $rsultado['consulta'];
        $nombre_documento = $resultado['nombre_documento'];
        $para = $resultado['email'];

         // título
        $título = 'Prueba enviar formulario';


            // mensaje
        $mensaje = '
            <html>
            <head>
            <title></title>
            </head>
            <body>
            <h3>Documento para '.$nombre_asignado.'</h3>
            Pulse <a href="https://webjuanpiqueras94.000webhostapp.com/Calidad_Bosco/web/index.php?comando=cargar_contenido&id_documento=' . $id_documentos . '&id_usuario=' . $id_personal . '">aquí </a> para contestar al documento 
            <strong>'.$nombre_documento.'</strong>.
            </body>
            </html>
        ';
        
            // Para enviar un correo HTML, debe establecerse la cabecera Content-type
        $cabeceras = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
            // Cabeceras adicionales
        $cabeceras .= "Para: $nombre <$para>" . "\r\n";
        $cabeceras .= 'De: Administrador <admin@iesjuanboco.es>' . "\r\n";
        
            // Enviarlo
        if (mail($para, $título, $mensaje, $cabeceras)) {
            return true;
        } else {
            return false;
        }

     }
    

     















?>