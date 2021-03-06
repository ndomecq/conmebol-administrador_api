<?php
    $app->get('/v1/100/dominio', function($request) {
        require __DIR__.'/../src/connect.php';
        
        $sql00  = "SELECT
        a.DOMFICCOD         AS          tipo_codigo,
        a.DOMFICORD         AS          tipo_orden,
        a.DOMFICNOI         AS          tipo_nombre_ingles,
        a.DOMFICNOC         AS          tipo_nombre_castellano,
        a.DOMFICNOP         AS          tipo_nombre_portugues,
        a.DOMFICPAT         AS          tipo_path,
        a.DOMFICVAL         AS          tipo_dominio,
        a.DOMFICOBS         AS          tipo_observacion,
        a.DOMFICUSU         AS          auditoria_usuario,
        a.DOMFICFEC         AS          auditoria_fecha_hora,
        a.DOMFICDIP         AS          auditoria_ip,

        b.DOMFICCOD         AS          tipo_estado_codigo,
        b.DOMFICNOI         AS          tipo_estado_ingles,
        b.DOMFICNOC         AS          tipo_estado_castellano,
        b.DOMFICNOP         AS          tipo_estado_portugues
        
        FROM [CSF_SFHOLOX].[adm].[DOMFIC] a
        INNER JOIN [CSF_SFHOLOX].[adm].[DOMFIC] b ON a.DOMFICEST = b.DOMFICCOD

        ORDER BY a.DOMFICVAL, a.DOMFICORD";

        try {
            $connMSSQL  = getConnectionMSSQLv1();
            $stmtMSSQL00= $connMSSQL->prepare($sql00);
            $stmtMSSQL00->execute();
            
            while ($rowMSSQL00 = $stmtMSSQL00->fetch()) {
                $detalle    = array(
                    'tipo_codigo'                               => $rowMSSQL00['tipo_codigo'],
                    'tipo_estado_codigo'                        => $rowMSSQL00['tipo_estado_codigo'],
                    'tipo_estado_ingles'                        => trim(strtoupper(strtolower($rowMSSQL00['tipo_estado_ingles']))),
                    'tipo_estado_castellano'                    => trim(strtoupper(strtolower($rowMSSQL00['tipo_estado_castellano']))),
                    'tipo_estado_portugues'                     => trim(strtoupper(strtolower($rowMSSQL00['tipo_estado_portugues']))),
                    'tipo_orden'                                => $rowMSSQL00['tipo_orden'],
                    'tipo_nombre_ingles'                        => trim(strtoupper(strtolower($rowMSSQL00['tipo_nombre_ingles']))),
                    'tipo_nombre_castellano'                    => trim(strtoupper(strtolower($rowMSSQL00['tipo_nombre_castellano']))),
                    'tipo_nombre_portugues'                     => trim(strtoupper(strtolower($rowMSSQL00['tipo_nombre_portugues']))),
                    'tipo_path'                                 => trim(strtolower($rowMSSQL00['tipo_path'])),
                    'tipo_dominio'                              => trim(strtoupper(strtolower($rowMSSQL00['tipo_dominio']))),
                    'tipo_observacion'                          => trim(strtoupper(strtolower($rowMSSQL00['tipo_observacion']))),
                    'auditoria_usuario'                         => trim(strtoupper(strtolower($rowMSSQL00['auditoria_usuario']))),
                    'auditoria_fecha_hora'                      => $rowMSSQL00['auditoria_fecha_hora'],
                    'auditoria_ip'                              => trim(strtoupper(strtolower($rowMSSQL00['auditoria_ip'])))
                );

                $result[]   = $detalle;
            }

            if (isset($result)){
                header("Content-Type: application/json; charset=utf-8");
                $json = json_encode(array('code' => 200, 'status' => 'ok', 'message' => 'Success SELECT', 'data' => $result), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
            } else {
                $detalle = array(
                    'tipo_codigo'                               => '',
                    'tipo_estado_codigo'                        => '',
                    'tipo_estado_ingles'                        => '',
                    'tipo_estado_castellano'                    => '',
                    'tipo_estado_portugues'                     => '',
                    'tipo_orden'                                => '',
                    'tipo_nombre_ingles'                        => '',
                    'tipo_nombre_castellano'                    => '',
                    'tipo_nombre_portugues'                     => '',
                    'tipo_path'                                 => '',
                    'tipo_dominio'                              => '',
                    'tipo_observacion'                          => '',
                    'auditoria_usuario'                         => '',
                    'auditoria_fecha_hora'                      => '',
                    'auditoria_ip'                              => ''
                );

                header("Content-Type: application/json; charset=utf-8");
                $json = json_encode(array('code' => 204, 'status' => 'ok', 'message' => 'No hay registros', 'data' => $detalle), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
            }

            $stmtMSSQL00->closeCursor();
            $stmtMSSQL00 = null;
        } catch (PDOException $e) {
            header("Content-Type: application/json; charset=utf-8");
            $json = json_encode(array('code' => 204, 'status' => 'failure', 'message' => 'Error SELECT: '.$e), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
        }

        $connMSSQL  = null;
        
        return $json;
    });

    $app->get('/v1/100/auditoria/{codigo}', function($request) {
        require __DIR__.'/../src/connect.php';

        $val01      = $request->getAttribute('codigo');
        
        if (isset($val01)) {
            $sql00  = "SELECT
            a.DOMFICCOD         AS          tipo_codigo,
            a.DOMFICORD         AS          tipo_orden,
            a.DOMFICNOI         AS          tipo_nombre_ingles,
            a.DOMFICNOC         AS          tipo_nombre_castellano,
            a.DOMFICNOP         AS          tipo_nombre_portugues,
            a.DOMFICPAT         AS          tipo_path,
            a.DOMFICVAL         AS          tipo_dominio,
            a.DOMFICOBS         AS          tipo_observacion,
            a.DOMFICIDD         AS          auditoria_codigo,
            a.DOMFICMET         AS          auditoria_metodo,
            a.DOMFICUSU         AS          auditoria_usuario,
            a.DOMFICFEC         AS          auditoria_fecha_hora,
            a.DOMFICDIP         AS          auditoria_ip,

            b.DOMFICCOD         AS          tipo_estado_codigo,
            b.DOMFICNOI         AS          tipo_estado_ingles,
            b.DOMFICNOC         AS          tipo_estado_castellano,
            b.DOMFICNOP         AS          tipo_estado_portugues
            
            FROM [CSF_SFHOLOX].[aud].[DOMFIC] a
            INNER JOIN [CSF_SFHOLOX].[adm].[DOMFIC] b ON a.DOMFICEST = b.DOMFICCOD
            
            WHERE a.DOMFICVAL = ?
            
            ORDER BY a.DOMFICIDD DESC";

            try {
                $connMSSQL  = getConnectionMSSQLv1();
                $stmtMSSQL00= $connMSSQL->prepare($sql00);
                $stmtMSSQL00->execute([$val01]);
                
                while ($rowMSSQL00 = $stmtMSSQL00->fetch()) {
                    $detalle    = array(
                        'tipo_codigo'                               => $rowMSSQL00['tipo_codigo'],
                        'tipo_estado_codigo'                        => $rowMSSQL00['tipo_estado_codigo'],
                        'tipo_estado_ingles'                        => trim(strtoupper(strtolower($rowMSSQL00['tipo_estado_ingles']))),
                        'tipo_estado_castellano'                    => trim(strtoupper(strtolower($rowMSSQL00['tipo_estado_castellano']))),
                        'tipo_estado_portugues'                     => trim(strtoupper(strtolower($rowMSSQL00['tipo_estado_portugues']))),
                        'tipo_orden'                                => $rowMSSQL00['tipo_orden'],
                        'tipo_nombre_ingles'                        => trim(strtoupper(strtolower($rowMSSQL00['tipo_nombre_ingles']))),
                        'tipo_nombre_castellano'                    => trim(strtoupper(strtolower($rowMSSQL00['tipo_nombre_castellano']))),
                        'tipo_nombre_portugues'                     => trim(strtoupper(strtolower($rowMSSQL00['tipo_nombre_portugues']))),
                        'tipo_path'                                 => trim(strtolower($rowMSSQL00['tipo_path'])),
                        'tipo_dominio'                              => trim(strtoupper(strtolower($rowMSSQL00['tipo_dominio']))),
                        'tipo_observacion'                          => trim(strtoupper(strtolower($rowMSSQL00['tipo_observacion']))),
                        'auditoria_codigo'                          => trim(strtoupper(strtolower($rowMSSQL00['auditoria_codigo']))),
                        'auditoria_metodo'                          => trim(strtoupper(strtolower($rowMSSQL00['auditoria_metodo']))),
                        'auditoria_usuario'                         => trim(strtoupper(strtolower($rowMSSQL00['auditoria_usuario']))),
                        'auditoria_fecha_hora'                      => $rowMSSQL00['auditoria_fecha_hora'],
                        'auditoria_ip'                              => trim(strtoupper(strtolower($rowMSSQL00['auditoria_ip'])))
                    );

                    $result[]   = $detalle;
                }

                if (isset($result)){
                    header("Content-Type: application/json; charset=utf-8");
                    $json = json_encode(array('code' => 200, 'status' => 'ok', 'message' => 'Success SELECT', 'data' => $result), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
                } else {
                    $detalle = array(
                        'tipo_codigo'                               => '',
                        'tipo_estado_codigo'                        => '',
                        'tipo_estado_ingles'                        => '',
                        'tipo_estado_castellano'                    => '',
                        'tipo_estado_portugues'                     => '',
                        'tipo_orden'                                => '',
                        'tipo_nombre_ingles'                        => '',
                        'tipo_nombre_castellano'                    => '',
                        'tipo_nombre_portugues'                     => '',
                        'tipo_path'                                 => '',
                        'tipo_dominio'                              => '',
                        'tipo_observacion'                          => '',
                        'auditoria_codigo'                          => '',
                        'auditoria_metodo'                          => '',
                        'auditoria_usuario'                         => '',
                        'auditoria_fecha_hora'                      => '',
                        'auditoria_ip'                              => ''
                    );

                    header("Content-Type: application/json; charset=utf-8");
                    $json = json_encode(array('code' => 204, 'status' => 'ok', 'message' => 'No hay registros', 'data' => $detalle), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
                }

                $stmtMSSQL00->closeCursor();
                $stmtMSSQL00 = null;
            } catch (PDOException $e) {
                header("Content-Type: application/json; charset=utf-8");
                $json = json_encode(array('code' => 204, 'status' => 'failure', 'message' => 'Error SELECT: '.$e), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
            }
        } else {
            header("Content-Type: application/json; charset=utf-8");
            $json = json_encode(array('code' => 400, 'status' => 'error', 'message' => 'Verifique, algún campo esta vacio.'), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
        }

        $connMSSQL  = null;
        
        return $json;
    });

    $app->get('/v1/100/solicitud', function($request) {
        require __DIR__.'/../src/connect.php';
        
        $sql00  = "SELECT
        a.DOMSOLCOD         AS          tipo_permiso_codigo,
        a.DOMSOLEST         AS          tipo_estado_codigo,
        a.DOMSOLTST         AS          tipo_solicitud_codigo,
        a.DOMSOLPC1         AS          tipo_permiso_codigo1,
        a.DOMSOLPC2         AS          tipo_permiso_codigo2,
        a.DOMSOLPC3         AS          tipo_permiso_codigo3,
        a.DOMSOLORD         AS          tipo_orden_numero,
        a.DOMSOLDIC         AS          tipo_dia_cantidad,
        a.DOMSOLDIO         AS          tipo_dia_corrido,
        a.DOMSOLDIU         AS          tipo_dia_unidad,
        a.DOMSOLADJ         AS          tipo_archivo_adjunto,
        a.DOMSOLOBS         AS          tipo_observacion,
        a.DOMSOLUSU         AS          auditoria_usuario,
        a.DOMSOLFEC         AS          auditoria_fecha_hora,
        a.DOMSOLDIP         AS          auditoria_ip
        
        FROM [CSF_SFHOLOX].[adm].[DOMSOL] a

        ORDER BY a.DOMSOLTST, a.DOMSOLORD";

        try {
            $connMSSQL  = getConnectionMSSQLv1();

            $stmtMSSQL00= $connMSSQL->prepare($sql00);
            $stmtMSSQL00->execute();
            
            while ($rowMSSQL00 = $stmtMSSQL00->fetch()) {
                $sql01  = '';

                switch ($rowMSSQL00['tipo_estado_codigo']) {
                    case 'A':
                        $tipo_estado_nombre = 'ACTIVO';
                        break;
                    
                    case 'I':
                        $tipo_estado_nombre = 'INACTIVO';
                        break;
                }

                switch ($rowMSSQL00['tipo_solicitud_codigo']) {
                    case 'L':
                        $tipo_solicitud_nombre  = 'LICENCIA';
                        $sql01                  = "SELECT U_NOMBRE AS tipo_permiso_nombre FROM [CSF].[dbo].[@A1A_TILC] WHERE U_CODIGO = ?";
                        break;
                    
                    case 'P':
                        $tipo_solicitud_nombre  = 'PERMISO';
                        $sql01                  = "SELECT U_NOMBRE AS tipo_permiso_nombre FROM [CSF].[dbo].[@A1A_TIPE] WHERE U_CODIGO = ?";
                        break;
    
                    case 'I':
                        $tipo_solicitud_nombre  = 'INASISTENCIA';
                        $sql01                  = "SELECT U_DESAMP AS tipo_permiso_nombre FROM [CSF].[dbo].[@A1A_TIIN] WHERE U_CODIGO = ?";
                        break;
                }

                $stmtMSSQL01= $connMSSQL->prepare($sql01);
                $stmtMSSQL01->execute([trim(strtoupper($rowMSSQL00['tipo_permiso_codigo3']))]);
                $rowMSSQL01 = $stmtMSSQL01->fetch(PDO::FETCH_ASSOC);

                $tipo_permiso_nombre = $rowMSSQL01['tipo_permiso_nombre'];

                $detalle    = array(
                    'tipo_permiso_codigo'                       => $rowMSSQL00['tipo_permiso_codigo'],
                    'tipo_estado_codigo'                        => $rowMSSQL00['tipo_estado_codigo'],
                    'tipo_estado_nombre'                        => trim(strtoupper($tipo_estado_nombre)),
                    'tipo_solicitud_codigo'                     => $rowMSSQL00['tipo_solicitud_codigo'],
                    'tipo_solicitud_nombre'                     => trim(strtoupper($tipo_solicitud_nombre)),
                    'tipo_permiso_codigo1'                      => $rowMSSQL00['tipo_permiso_codigo1'],
                    'tipo_permiso_codigo2'                      => $rowMSSQL00['tipo_permiso_codigo2'],
                    'tipo_permiso_codigo3'                      => trim(strtoupper($rowMSSQL00['tipo_permiso_codigo3'])),
                    'tipo_permiso_nombre'                       => trim(strtoupper($tipo_permiso_nombre)),
                    'tipo_orden_numero'                         => $rowMSSQL00['tipo_orden_numero'],
                    'tipo_dia_cantidad'                         => $rowMSSQL00['tipo_dia_cantidad'],
                    'tipo_dia_corrido'                          => trim(strtoupper($rowMSSQL00['tipo_dia_corrido'])),
                    'tipo_dia_unidad'                           => trim(strtoupper($rowMSSQL00['tipo_dia_unidad'])),
                    'tipo_archivo_adjunto'                      => trim(strtoupper($rowMSSQL00['tipo_archivo_adjunto'])),
                    'tipo_observacion'                          => trim(strtoupper($rowMSSQL00['tipo_observacion'])),
                    'auditoria_usuario'                         => trim(strtoupper($rowMSSQL00['auditoria_usuario'])),
                    'auditoria_fecha_hora'                      => $rowMSSQL00['auditoria_fecha_hora'],
                    'auditoria_ip'                              => trim(strtoupper($rowMSSQL00['auditoria_ip']))
                );

                $result[]   = $detalle;

                $stmtMSSQL01->closeCursor();
                $stmtMSSQL01 = null;
            }

            if (isset($result)){
                header("Content-Type: application/json; charset=utf-8");
                $json = json_encode(array('code' => 200, 'status' => 'ok', 'message' => 'Success SELECT', 'data' => $result), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
            } else {
                $detalle = array(
                    'tipo_permiso_codigo'                       => '',
                    'tipo_estado_codigo'                        => '',
                    'tipo_estado_nombre'                        => '',
                    'tipo_solicitud_codigo'                     => '',
                    'tipo_solicitud_nombre'                     => '',
                    'tipo_permiso_codigo1'                      => '',
                    'tipo_permiso_codigo2'                      => '',
                    'tipo_permiso_codigo3'                      => '',
                    'tipo_permiso_nombre'                       => '',
                    'tipo_orden_numero'                         => '',
                    'tipo_dia_cantidad'                         => '',
                    'tipo_dia_corrido'                          => '',
                    'tipo_dia_unidad'                           => '',
                    'tipo_archivo_adjunto'                      => '',
                    'tipo_observacion'                          => '',
                    'auditoria_usuario'                         => '',
                    'auditoria_fecha_hora'                      => '',
                    'auditoria_ip'                              => ''
                );

                header("Content-Type: application/json; charset=utf-8");
                $json = json_encode(array('code' => 204, 'status' => 'ok', 'message' => 'No hay registros', 'data' => $detalle), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
            }

            $stmtMSSQL00->closeCursor();
            $stmtMSSQL00 = null;
        } catch (PDOException $e) {
            header("Content-Type: application/json; charset=utf-8");
            $json = json_encode(array('code' => 204, 'status' => 'failure', 'message' => 'Error SELECT: '.$e), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
        }

        $connMSSQL  = null;
        
        return $json;
    });

    $app->get('/v1/100/solicitud/{codigo}', function($request) {
        require __DIR__.'/../src/connect.php';
        
        $val01      = $request->getAttribute('codigo');
        
        if (isset($val01)) {
            $sql00  = "SELECT
            a.DOMSOLCOD         AS          tipo_permiso_codigo,
            a.DOMSOLEST         AS          tipo_estado_codigo,
            a.DOMSOLTST         AS          tipo_solicitud_codigo,
            a.DOMSOLPC1         AS          tipo_permiso_codigo1,
            a.DOMSOLPC2         AS          tipo_permiso_codigo2,
            a.DOMSOLPC3         AS          tipo_permiso_codigo3,
            a.DOMSOLORD         AS          tipo_orden_numero,
            a.DOMSOLDIC         AS          tipo_dia_cantidad,
            a.DOMSOLDIO         AS          tipo_dia_corrido,
            a.DOMSOLDIU         AS          tipo_dia_unidad,
            a.DOMSOLADJ         AS          tipo_archivo_adjunto,
            a.DOMSOLOBS         AS          tipo_observacion,
            a.DOMSOLUSU         AS          auditoria_usuario,
            a.DOMSOLFEC         AS          auditoria_fecha_hora,
            a.DOMSOLDIP         AS          auditoria_ip
            
            FROM [CSF_SFHOLOX].[adm].[DOMSOL] a

            WHERE a.DOMSOLCOD = ?

            ORDER BY a.DOMSOLTST, a.DOMSOLORD";

            try {
                $connMSSQL  = getConnectionMSSQLv1();

                $stmtMSSQL00= $connMSSQL->prepare($sql00);
                $stmtMSSQL00->execute([$val01]);
                
                while ($rowMSSQL00 = $stmtMSSQL00->fetch()) {
                    $sql01  = '';

                    switch ($rowMSSQL00['tipo_estado_codigo']) {
                        case 'A':
                            $tipo_estado_nombre = 'ACTIVO';
                            break;
                        
                        case 'I':
                            $tipo_estado_nombre = 'INACTIVO';
                            break;
                    }

                    switch ($rowMSSQL00['tipo_solicitud_codigo']) {
                        case 'L':
                            $tipo_solicitud_nombre  = 'LICENCIA';
                            $sql01                  = "SELECT U_NOMBRE AS tipo_permiso_nombre FROM [CSF].[dbo].[@A1A_TILC] WHERE U_CODIGO = ?";
                            break;
                        
                        case 'P':
                            $tipo_solicitud_nombre  = 'PERMISO';
                            $sql01                  = "SELECT U_NOMBRE AS tipo_permiso_nombre FROM [CSF].[dbo].[@A1A_TIPE] WHERE U_CODIGO = ?";
                            break;
        
                        case 'I':
                            $tipo_solicitud_nombre  = 'INASISTENCIA';
                            $sql01                  = "SELECT U_DESAMP AS tipo_permiso_nombre FROM [CSF].[dbo].[@A1A_TIIN] WHERE U_CODIGO = ?";
                            break;
                    }

                    $stmtMSSQL01= $connMSSQL->prepare($sql01);
                    $stmtMSSQL01->execute([trim(strtoupper($rowMSSQL00['tipo_permiso_codigo3']))]);
                    $rowMSSQL01 = $stmtMSSQL01->fetch(PDO::FETCH_ASSOC);

                    $tipo_permiso_nombre = $rowMSSQL01['tipo_permiso_nombre'];

                    $detalle    = array(
                        'tipo_permiso_codigo'                       => $rowMSSQL00['tipo_permiso_codigo'],
                        'tipo_estado_codigo'                        => $rowMSSQL00['tipo_estado_codigo'],
                        'tipo_estado_nombre'                        => trim(strtoupper($tipo_estado_nombre)),
                        'tipo_solicitud_codigo'                     => $rowMSSQL00['tipo_solicitud_codigo'],
                        'tipo_solicitud_nombre'                     => trim(strtoupper($tipo_solicitud_nombre)),
                        'tipo_permiso_codigo1'                      => $rowMSSQL00['tipo_permiso_codigo1'],
                        'tipo_permiso_codigo2'                      => $rowMSSQL00['tipo_permiso_codigo2'],
                        'tipo_permiso_codigo3'                      => trim(strtoupper($rowMSSQL00['tipo_permiso_codigo3'])),
                        'tipo_permiso_nombre'                       => trim(strtoupper($tipo_permiso_nombre)),
                        'tipo_orden_numero'                         => $rowMSSQL00['tipo_orden_numero'],
                        'tipo_dia_cantidad'                         => $rowMSSQL00['tipo_dia_cantidad'],
                        'tipo_dia_corrido'                          => trim(strtoupper($rowMSSQL00['tipo_dia_corrido'])),
                        'tipo_dia_unidad'                           => trim(strtoupper($rowMSSQL00['tipo_dia_unidad'])),
                        'tipo_archivo_adjunto'                      => trim(strtoupper($rowMSSQL00['tipo_archivo_adjunto'])),
                        'tipo_observacion'                          => trim(strtoupper($rowMSSQL00['tipo_observacion'])),
                        'auditoria_usuario'                         => trim(strtoupper($rowMSSQL00['auditoria_usuario'])),
                        'auditoria_fecha_hora'                      => $rowMSSQL00['auditoria_fecha_hora'],
                        'auditoria_ip'                              => trim(strtoupper($rowMSSQL00['auditoria_ip']))
                    );

                    $result[]   = $detalle;

                    $stmtMSSQL01->closeCursor();
                    $stmtMSSQL01 = null;
                }

                if (isset($result)){
                    header("Content-Type: application/json; charset=utf-8");
                    $json = json_encode(array('code' => 200, 'status' => 'ok', 'message' => 'Success SELECT', 'data' => $result), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
                } else {
                    $detalle = array(
                        'tipo_permiso_codigo'                       => '',
                        'tipo_estado_codigo'                        => '',
                        'tipo_estado_nombre'                        => '',
                        'tipo_solicitud_codigo'                     => '',
                        'tipo_solicitud_nombre'                     => '',
                        'tipo_permiso_codigo1'                      => '',
                        'tipo_permiso_codigo2'                      => '',
                        'tipo_permiso_codigo3'                      => '',
                        'tipo_permiso_nombre'                       => '',
                        'tipo_orden_numero'                         => '',
                        'tipo_dia_cantidad'                         => '',
                        'tipo_dia_corrido'                          => '',
                        'tipo_dia_unidad'                           => '',
                        'tipo_archivo_adjunto'                      => '',
                        'tipo_observacion'                          => '',
                        'auditoria_usuario'                         => '',
                        'auditoria_fecha_hora'                      => '',
                        'auditoria_ip'                              => ''
                    );

                    header("Content-Type: application/json; charset=utf-8");
                    $json = json_encode(array('code' => 204, 'status' => 'ok', 'message' => 'No hay registros', 'data' => $detalle), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
                }

                $stmtMSSQL00->closeCursor();
                $stmtMSSQL00 = null;
            } catch (PDOException $e) {
                header("Content-Type: application/json; charset=utf-8");
                $json = json_encode(array('code' => 204, 'status' => 'failure', 'message' => 'Error SELECT: '.$e), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
            }
        } else {
            header("Content-Type: application/json; charset=utf-8");
            $json = json_encode(array('code' => 400, 'status' => 'error', 'message' => 'Verifique, algún campo esta vacio.'), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
        }

        $connMSSQL  = null;
        
        return $json;
    });

    $app->get('/v1/200/solicitudes', function($request) {
        require __DIR__.'/../src/connect.php';
        
        $sql01  = "SELECT
            a.SOLFICCOD         AS          solicitud_codigo,
            a.SOLFICEST         AS          solicitud_estado_codigo,
            a.SOLFICDOC         AS          solicitud_documento,
            a.SOLFICFH1         AS          solicitud_fecha_desde,
            a.SOLFICFH2         AS          solicitud_fecha_hasta,
            a.SOLFICFHC         AS          solicitud_fecha_cantidad,
            a.SOLFICHO1         AS          solicitud_hora_desde,
            a.SOLFICHO2         AS          solicitud_hora_hasta,
            a.SOLFICHOC         AS          solicitud_hora_cantidad,
            a.SOLFICADJ         AS          solicitud_adjunto,
            a.SOLFICUSC         AS          solicitud_usuario_colaborador,
            a.SOLFICFCC         AS          solicitud_fecha_hora_colaborador,
            a.SOLFICIPC         AS          solicitud_ip_colaborador, 
            a.SOLFICOBC         AS          solicitud_observacion_colaborador,
            a.SOLFICUSS         AS          solicitud_usuario_superior,
            a.SOLFICFCS         AS          solicitud_fecha_hora_superior,
            a.SOLFICIPS         AS          solicitud_ip_superior,
            a.SOLFICOBS         AS          solicitud_observacion_superior,
            a.SOLFICUST         AS          solicitud_usuario_talento,
            a.SOLFICFCT         AS          solicitud_fecha_hora_talento,
            a.SOLFICIPT         AS          solicitud_ip_talento,
            a.SOLFICOBT         AS          solicitud_observacion_talento,
            a.SOLFICUSU         AS          auditoria_usuario,
            a.SOLFICFEC         AS          auditoria_fecha_hora,
            a.SOLFICDIP         AS          auditoria_ip,

            b.DOMSOLCOD         AS          tipo_permiso_codigo,
            b.DOMSOLEST         AS          tipo_estado_codigo,
            b.DOMSOLTST         AS          tipo_solicitud_codigo,
            b.DOMSOLPC1         AS          tipo_permiso_codigo1,
            b.DOMSOLPC2         AS          tipo_permiso_codigo2,
            b.DOMSOLPC3         AS          tipo_permiso_codigo3,
            b.DOMSOLORD         AS          tipo_orden_numero,
            b.DOMSOLDIC         AS          tipo_dia_cantidad,
            b.DOMSOLDIO         AS          tipo_dia_corrido,
            b.DOMSOLDIU         AS          tipo_dia_unidad,
            b.DOMSOLADJ         AS          tipo_archivo_adjunto,
            b.DOMSOLOBS         AS          tipo_observacion

            FROM [CSF_SFHOLOX].[hum].[SOLFIC] a
            INNER JOIN [CSF_SFHOLOX].[adm].[DOMSOL] b ON a.SOLFICTST = b.DOMSOLCOD";

        $sql03  = "SELECT
            a.CedulaEmpleado            AS          documento,
            a.ApellidoPaterno           AS          apellido_1,
            a.ApellidoMaterno           AS          apellido_2,
            a.PrimerNombre              AS          nombre_1,
            a.SegundoNombre             AS          nombre_2,
            a.NombreEmpleado            AS          nombre_completo,
            a.Sexo                      AS          tipo_sexo_codigo,
            a.EstadoCivil               AS          estado_civil_codigo,
            a.Email                     AS          email,
            a.FechaNacimiento           AS          fecha_nacimiento,
            a.CodigoCargo               AS          cargo_codigo,
            a.Cargo                     AS          cargo_nombre,
            a.CodigoGerencia            AS          gerencia_codigo,
            a.Gerencia                  AS          gerencia_nombre,
            a.CodigoDepto               AS          departamento_codigo,
            a.Departamento              AS          departamento_nombre,         
            a.CodCargoSuperior          AS          superior_cargo_codigo,
            a.NombreCargoSuperior       AS          superior_cargo_nombre,
            a.Manager                   AS          superior_manager_nombre,
            a.EmailManager              AS          superior_manager_email

            FROM [CSF].[dbo].[empleados_AxisONE] a

            WHERE a.CedulaEmpleado = ?";

        try {
            $connMSSQL  = getConnectionMSSQLv1();
            
            $stmtMSSQL01= $connMSSQL->prepare($sql01);
            $stmtMSSQL01->execute([]);

            while ($rowMSSQL01 = $stmtMSSQL01->fetch()) {
                switch ($rowMSSQL01['solicitud_estado_codigo']) {
                    case 'I':
                        $solicitud_estado_nombre = 'INGRESADO';
                        break;
                    
                    case 'A':
                        $solicitud_estado_nombre = 'AUTORIZADO';
                        break;
                    
                    case 'P':
                        $solicitud_estado_nombre = 'APROBADO';
                        break;

                    case 'C':
                        $solicitud_estado_nombre = 'ANULADO';
                        break;
                }

                switch ($rowMSSQL01['tipo_solicitud_codigo']) {
                    case 'L':
                        $tipo_solicitud_nombre  = 'LICENCIA';
                        $sql02                  = "SELECT U_NOMBRE AS tipo_permiso_nombre FROM [CSF].[dbo].[@A1A_TILC] WHERE U_CODIGO = ?";
                        break;
                    
                    case 'P':
                        $tipo_solicitud_nombre  = 'PERMISO';
                        $sql02                  = "SELECT U_NOMBRE AS tipo_permiso_nombre FROM [CSF].[dbo].[@A1A_TIPE] WHERE U_CODIGO = ?";
                        break;
    
                    case 'I':
                        $tipo_solicitud_nombre  = 'INASISTENCIA';
                        $sql02                  = "SELECT U_DESAMP AS tipo_permiso_nombre FROM [CSF].[dbo].[@A1A_TIIN] WHERE U_CODIGO = ?";
                        break;
                }

                $stmtMSSQL02= $connMSSQL->prepare($sql02);
                $stmtMSSQL02->execute([trim(strtoupper($rowMSSQL01['tipo_permiso_codigo3']))]);
                $rowMSSQL02 = $stmtMSSQL02->fetch(PDO::FETCH_ASSOC);

                $stmtMSSQL03= $connMSSQL->prepare($sql03);
                $stmtMSSQL03->execute([trim(strtoupper($rowMSSQL01['solicitud_documento']))]);
                $rowMSSQL03 = $stmtMSSQL03->fetch(PDO::FETCH_ASSOC);

                $tipo_permiso_nombre= $rowMSSQL02['tipo_permiso_nombre'];
                $solicitud_persona  = $rowMSSQL03['nombre_completo'];

                $detalle    = array(
                    'tipo_permiso_codigo'               => $rowMSSQL01['tipo_permiso_codigo'],
                    'tipo_permiso_nombre'               => trim(strtoupper($tipo_permiso_nombre)),
                    'solicitud_codigo'                  => $rowMSSQL01['solicitud_codigo'],
                    'solicitud_estado_codigo'           => $rowMSSQL01['solicitud_estado_codigo'],
                    'solicitud_estado_nombre'           => trim(strtoupper($solicitud_estado_nombre)),
                    'solicitud_documento'               => trim(strtoupper($rowMSSQL01['solicitud_documento'])),
                    'solicitud_persona'                 => trim(strtoupper($solicitud_persona)),
                    'solicitud_fecha_desde_1'           => $rowMSSQL01['solicitud_fecha_desde'],
                    'solicitud_fecha_desde_2'           => date("d/m/Y", strtotime($rowMSSQL01['solicitud_fecha_desde'])),
                    'solicitud_fecha_hasta_1'           => $rowMSSQL01['solicitud_fecha_hasta'],
                    'solicitud_fecha_hasta_2'           => date("d/m/Y", strtotime($rowMSSQL01['solicitud_fecha_hasta'])),
                    'solicitud_fecha_cantidad'          => $rowMSSQL01['solicitud_fecha_cantidad'],
                    'solicitud_hora_desde'              => trim(strtoupper($rowMSSQL01['solicitud_hora_desde'])),
                    'solicitud_hora_hasta'              => trim(strtoupper($rowMSSQL01['solicitud_hora_hasta'])),
                    'solicitud_hora_cantidad'           => $rowMSSQL01['solicitud_hora_cantidad'],
                    'solicitud_adjunto'                 => trim(strtolower($rowMSSQL01['solicitud_adjunto'])),
                    'solicitud_usuario_colaborador'     => trim(strtoupper($rowMSSQL01['solicitud_usuario_colaborador'])),
                    'solicitud_fecha_hora_colaborador'  => date("d/m/Y", strtotime($rowMSSQL01['solicitud_fecha_hora_colaborador'])),
                    'solicitud_ip_colaborador'          => trim(strtoupper($rowMSSQL01['solicitud_ip_colaborador'])),
                    'solicitud_observacion_colaborador' => trim(strtoupper($rowMSSQL01['solicitud_observacion_colaborador'])),
                    'solicitud_usuario_superior'        => trim(strtoupper($rowMSSQL01['solicitud_usuario_superior'])),
                    'solicitud_fecha_hora_superior'     => date("d/m/Y", strtotime($rowMSSQL01['solicitud_fecha_hora_superior'])),
                    'solicitud_ip_superior'             => trim(strtoupper($rowMSSQL01['solicitud_ip_superior'])),
                    'solicitud_observacion_superior'    => trim(strtoupper($rowMSSQL01['solicitud_observacion_superior'])),
                    'solicitud_usuario_talento'         => trim(strtoupper($rowMSSQL01['solicitud_usuario_talento'])),
                    'solicitud_fecha_hora_talento'      => date("d/m/Y", strtotime($rowMSSQL01['solicitud_fecha_hora_talento'])),
                    'solicitud_ip_talento'              => trim(strtoupper($rowMSSQL01['solicitud_ip_talento'])),
                    'solicitud_observacion_talento'     => trim(strtoupper($rowMSSQL01['solicitud_observacion_talento'])),
                    'auditoria_usuario'                 => trim(strtoupper($rowMSSQL01['auditoria_usuario'])),
                    'auditoria_fecha_hora'              => date("d/m/Y", strtotime($rowMSSQL01['auditoria_fecha_hora'])),
                    'auditoria_ip'                      => trim(strtoupper($rowMSSQL01['auditoria_ip'])),

                    'gerencia_codigo'                   => $rowMSSQL03['gerencia_codigo'],
                    'gerencia_nombre'                   => trim(strtoupper($rowMSSQL03['gerencia_nombre'])),
                    'tipo_sexo_codigo'                  => trim(strtoupper($rowMSSQL03['tipo_sexo_codigo'])),
                    'colaborador_edad'                  => date('Y') - date('Y', strtotime($rowMSSQL03['fecha_nacimiento'])),
                    'departamento_codigo'               => $rowMSSQL03['departamento_codigo'],
                    'departamento_nombre'               => trim(strtoupper($rowMSSQL03['departamento_nombre']))
                );

                $result[]   = $detalle;
            }

            if (isset($result)){
                header("Content-Type: application/json; charset=utf-8");
                $json = json_encode(array('code' => 200, 'status' => 'ok', 'message' => 'Success SELECT', 'data' => $result), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
            } else {
                $detalle    = array(
                    'tipo_solicitud_codigo'             => '',
                    'tipo_permiso_nombre'               => '',
                    'solicitud_codigo'                  => '',
                    'solicitud_estado_codigo'           => '',
                    'solicitud_estado_nombre'           => '',
                    'solicitud_documento'               => '',
                    'solicitud_fecha_desde_1'           => '',
                    'solicitud_fecha_desde_2'           => '',
                    'solicitud_fecha_hasta_1'           => '',
                    'solicitud_fecha_hasta_2'           => '',
                    'solicitud_fecha_cantidad'          => '',
                    'solicitud_hora_desde'              => '',
                    'solicitud_hora_hasta'              => '',
                    'solicitud_hora_cantidad'           => '',
                    'solicitud_adjunto'                 => '',
                    'solicitud_usuario_colaborador'     => '',
                    'solicitud_fecha_hora_colaborador'  => '',
                    'solicitud_ip_colaborador'          => '',
                    'solicitud_observacion_colaborador' => '',
                    'solicitud_usuario_superior'        => '',
                    'solicitud_fecha_hora_superior'     => '',
                    'solicitud_ip_superior'             => '',
                    'solicitud_observacion_superior'    => '',
                    'solicitud_usuario_talento'         => '',
                    'solicitud_fecha_hora_talento'      => '',
                    'solicitud_ip_talento'              => '',
                    'solicitud_observacion_talento'     => '',
                    'auditoria_usuario'                 => '',
                    'auditoria_fecha_hora'              => '',
                    'auditoria_ip'                      => '',
                    'gerencia_codigo'                   => '',
                    'gerencia_nombre'                   => '',
                    'tipo_sexo_codigo'                  => '',
                    'colaborador_edad'                  => '',
                    'departamento_codigo'               => '',
                    'departamento_nombre'               => ''
                );

                header("Content-Type: application/json; charset=utf-8");
                $json = json_encode(array('code' => 204, 'status' => 'ok', 'message' => 'No hay registros', 'data' => $detalle), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
            }

            $stmtMSSQL01->closeCursor();
            $stmtMSSQL01 = null;

            $stmtMSSQL02->closeCursor();
            $stmtMSSQL02 = null;

            $stmtMSSQL03->closeCursor();
            $stmtMSSQL03 = null;
        } catch (PDOException $e) {
            header("Content-Type: application/json; charset=utf-8");
            $json = json_encode(array('code' => 204, 'status' => 'failure', 'message' => 'Error SELECT: '.$e), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
        }

        $connMSSQL  = null;
        
        return $json;
    });

    $app->get('/v1/200/solicitudes/{tipo}/{codigo}/{estado}', function($request) {
        require __DIR__.'/../src/connect.php';
        
        $val01      = $request->getAttribute('tipo');
        $val02      = $request->getAttribute('codigo');
        $val03      = $request->getAttribute('estado');
        
        if (isset($val01) && isset($val02)) {            
            if ($val01 == '1') {
                $sql00  = "SELECT
                a.IDEmpleado                AS          codigo,
                a.Estado                    AS          estado,
                a.CedulaEmpleado            AS          documento,
                a.ApellidoPaterno           AS          apellido_1,
                a.ApellidoMaterno           AS          apellido_2,
                a.PrimerNombre              AS          nombre_1,
                a.SegundoNombre             AS          nombre_2,
                a.NombreEmpleado            AS          nombre_completo,
                a.Sexo                      AS          tipo_sexo_codigo,
                a.EstadoCivil               AS          estado_civil_codigo,
                a.Email                     AS          email,
                a.FechaNacimiento           AS          fecha_nacimiento,
                a.IDUsuario                 AS          usuario_id,
                a.UsuarioSAP                AS          usuario_sap,
                a.IDTarjeta                 AS          tarjeta_id,
                a.CodigoCargo               AS          cargo_codigo,
                a.Cargo                     AS          cargo_nombre,
                a.CodigoGerencia            AS          gerencia_codigo,
                a.Gerencia                  AS          gerencia_nombre,
                a.CodigoDepto               AS          departamento_codigo,
                a.Departamento              AS          departamento_nombre,         
                a.CodCargoSuperior          AS          superior_cargo_codigo,
                a.NombreCargoSuperior       AS          superior_cargo_nombre,
                a.Manager                   AS          superior_manager_nombre,
                a.EmailManager              AS          superior_manager_email

                FROM [CSF].[dbo].[empleados_AxisONE] a
                LEFT OUTER JOIN [CSF].[dbo].[empleados_AxisONE] b ON a.CodCargoSuperior = b.CodigoCargo

                WHERE a.CedulaEmpleado = ?";
            } elseif ($val01 == '2') {
                $sql00  = "SELECT
                a.IDEmpleado                AS          codigo,
                a.Estado                    AS          estado,
                a.CedulaEmpleado            AS          documento,
                a.ApellidoPaterno           AS          apellido_1,
                a.ApellidoMaterno           AS          apellido_2,
                a.PrimerNombre              AS          nombre_1,
                a.SegundoNombre             AS          nombre_2,
                a.NombreEmpleado            AS          nombre_completo,
                a.Sexo                      AS          tipo_sexo_codigo,
                a.EstadoCivil               AS          estado_civil_codigo,
                a.Email                     AS          email,
                a.FechaNacimiento           AS          fecha_nacimiento,
                a.IDUsuario                 AS          usuario_id,
                a.UsuarioSAP                AS          usuario_sap,
                a.IDTarjeta                 AS          tarjeta_id,
                a.CodigoCargo               AS          cargo_codigo,
                a.Cargo                     AS          cargo_nombre,
                a.CodigoGerencia            AS          gerencia_codigo,
                a.Gerencia                  AS          gerencia_nombre,
                a.CodigoDepto               AS          departamento_codigo,
                a.Departamento              AS          departamento_nombre,         
                a.CodCargoSuperior          AS          superior_cargo_codigo,
                a.NombreCargoSuperior       AS          superior_cargo_nombre,
                a.Manager                   AS          superior_manager_nombre,
                a.EmailManager              AS          superior_manager_email

                FROM [CSF].[dbo].[empleados_AxisONE] a
                INNER JOIN [CSF].[dbo].[empleados_AxisONE] b ON a.CodCargoSuperior = b.CodigoCargo

                WHERE b.CedulaEmpleado = ?";
            } elseif ($val01 == '3') {
                $sql00  = "SELECT
                a.IDEmpleado                AS          codigo,
                a.Estado                    AS          estado,
                a.CedulaEmpleado            AS          documento,
                a.ApellidoPaterno           AS          apellido_1,
                a.ApellidoMaterno           AS          apellido_2,
                a.PrimerNombre              AS          nombre_1,
                a.SegundoNombre             AS          nombre_2,
                a.NombreEmpleado            AS          nombre_completo,
                a.Sexo                      AS          tipo_sexo_codigo,
                a.EstadoCivil               AS          estado_civil_codigo,
                a.Email                     AS          email,
                a.FechaNacimiento           AS          fecha_nacimiento,
                a.IDUsuario                 AS          usuario_id,
                a.UsuarioSAP                AS          usuario_sap,
                a.IDTarjeta                 AS          tarjeta_id,
                a.CodigoCargo               AS          cargo_codigo,
                a.Cargo                     AS          cargo_nombre,
                a.CodigoGerencia            AS          gerencia_codigo,
                a.Gerencia                  AS          gerencia_nombre,
                a.CodigoDepto               AS          departamento_codigo,
                a.Departamento              AS          departamento_nombre,         
                a.CodCargoSuperior          AS          superior_cargo_codigo,
                a.NombreCargoSuperior       AS          superior_cargo_nombre,
                a.Manager                   AS          superior_manager_nombre,
                a.EmailManager              AS          superior_manager_email

                FROM [CSF].[dbo].[empleados_AxisONE] a
                INNER JOIN [CSF].[dbo].[empleados_AxisONE] b ON a.CodCargoSuperior = b.CodigoCargo";
            } elseif ($val01 == '4') {
                $sql00  = "SELECT
                a.IDEmpleado                AS          codigo,
                a.Estado                    AS          estado,
                a.CedulaEmpleado            AS          documento,
                a.ApellidoPaterno           AS          apellido_1,
                a.ApellidoMaterno           AS          apellido_2,
                a.PrimerNombre              AS          nombre_1,
                a.SegundoNombre             AS          nombre_2,
                a.NombreEmpleado            AS          nombre_completo,
                a.Sexo                      AS          tipo_sexo_codigo,
                a.EstadoCivil               AS          estado_civil_codigo,
                a.Email                     AS          email,
                a.FechaNacimiento           AS          fecha_nacimiento,
                a.IDUsuario                 AS          usuario_id,
                a.UsuarioSAP                AS          usuario_sap,
                a.IDTarjeta                 AS          tarjeta_id,
                a.CodigoCargo               AS          cargo_codigo,
                a.Cargo                     AS          cargo_nombre,
                a.CodigoGerencia            AS          gerencia_codigo,
                a.Gerencia                  AS          gerencia_nombre,
                a.CodigoDepto               AS          departamento_codigo,
                a.Departamento              AS          departamento_nombre,         
                a.CodCargoSuperior          AS          superior_cargo_codigo,
                a.NombreCargoSuperior       AS          superior_cargo_nombre,
                a.Manager                   AS          superior_manager_nombre,
                a.EmailManager              AS          superior_manager_email

                FROM [CSF].[dbo].[empleados_AxisONE] a
                LEFT OUTER JOIN [CSF].[dbo].[empleados_AxisONE] b ON a.CodCargoSuperior = b.CodigoCargo

                WHERE a.CedulaEmpleado = ? OR b.CedulaEmpleado = ?";
            } elseif ($val01 == '5') {
                $sql00  = "SELECT
                c.IDEmpleado                AS          codigo,
                c.Estado                    AS          estado,
                c.CedulaEmpleado            AS          documento,
                c.ApellidoPaterno           AS          apellido_1,
                c.ApellidoMaterno           AS          apellido_2,
                c.PrimerNombre              AS          nombre_1,
                c.SegundoNombre             AS          nombre_2,
                c.NombreEmpleado            AS          nombre_completo,
                c.Sexo                      AS          tipo_sexo_codigo,
                c.EstadoCivil               AS          estado_civil_codigo,
                c.Email                     AS          email,
                c.FechaNacimiento           AS          fecha_nacimiento,
                c.IDUsuario                 AS          usuario_id,
                c.UsuarioSAP                AS          usuario_sap,
                c.IDTarjeta                 AS          tarjeta_id,
                c.CodigoCargo               AS          cargo_codigo,
                c.Cargo                     AS          cargo_nombre,
                c.CodigoGerencia            AS          gerencia_codigo,
                c.Gerencia                  AS          gerencia_nombre,
                c.CodigoDepto               AS          departamento_codigo,
                c.Departamento              AS          departamento_nombre,         
                c.CodCargoSuperior          AS          superior_cargo_codigo,
                c.NombreCargoSuperior       AS          superior_cargo_nombre,
                c.Manager                   AS          superior_manager_nombre,
                c.EmailManager              AS          superior_manager_email

                FROM [CSF].[dbo].[empleados_AxisONE] a
                LEFT OUTER JOIN [CSF].[dbo].[empleados_AxisONE] b ON a.CodigoCargo = b.CodCargoSuperior
                LEFT OUTER JOIN [CSF].[dbo].[empleados_AxisONE] c ON b.CodigoCargo = c.CodCargoSuperior

                WHERE a.CedulaEmpleado = ?";
            }

            if ($val03 == 'I' || $val03 == 'A' || $val03 == 'P' || $val03 == 'C') {
                $sql01  = "SELECT
                    a.SOLFICCOD         AS          solicitud_codigo,
                    a.SOLFICEST         AS          solicitud_estado_codigo,
                    a.SOLFICDOC         AS          solicitud_documento,
                    a.SOLFICFH1         AS          solicitud_fecha_desde,
                    a.SOLFICFH2         AS          solicitud_fecha_hasta,
                    a.SOLFICFHC         AS          solicitud_fecha_cantidad,
                    a.SOLFICHO1         AS          solicitud_hora_desde,
                    a.SOLFICHO2         AS          solicitud_hora_hasta,
                    a.SOLFICHOC         AS          solicitud_hora_cantidad,
                    a.SOLFICADJ         AS          solicitud_adjunto,
                    a.SOLFICUSC         AS          solicitud_usuario_colaborador,
                    a.SOLFICFCC         AS          solicitud_fecha_hora_colaborador,
                    a.SOLFICIPC         AS          solicitud_ip_colaborador, 
                    a.SOLFICOBC         AS          solicitud_observacion_colaborador,
                    a.SOLFICUSS         AS          solicitud_usuario_superior,
                    a.SOLFICFCS         AS          solicitud_fecha_hora_superior,
                    a.SOLFICIPS         AS          solicitud_ip_superior,
                    a.SOLFICOBS         AS          solicitud_observacion_superior,
                    a.SOLFICUST         AS          solicitud_usuario_talento,
                    a.SOLFICFCT         AS          solicitud_fecha_hora_talento,
                    a.SOLFICIPT         AS          solicitud_ip_talento,
                    a.SOLFICOBT         AS          solicitud_observacion_talento,
                    a.SOLFICUSU         AS          auditoria_usuario,
                    a.SOLFICFEC         AS          auditoria_fecha_hora,
                    a.SOLFICDIP         AS          auditoria_ip,

                    b.DOMSOLCOD         AS          tipo_permiso_codigo,
                    b.DOMSOLEST         AS          tipo_estado_codigo,
                    b.DOMSOLTST         AS          tipo_solicitud_codigo,
                    b.DOMSOLPC1         AS          tipo_permiso_codigo1,
                    b.DOMSOLPC2         AS          tipo_permiso_codigo2,
                    b.DOMSOLPC3         AS          tipo_permiso_codigo3,
                    b.DOMSOLORD         AS          tipo_orden_numero,
                    b.DOMSOLDIC         AS          tipo_dia_cantidad,
                    b.DOMSOLDIO         AS          tipo_dia_corrido,
                    b.DOMSOLDIU         AS          tipo_dia_unidad,
                    b.DOMSOLADJ         AS          tipo_archivo_adjunto,
                    b.DOMSOLOBS         AS          tipo_observacion

                    FROM [CSF_SFHOLOX].[hum].[SOLFIC] a
                    INNER JOIN [CSF_SFHOLOX].[adm].[DOMSOL] b ON a.SOLFICTST = b.DOMSOLCOD

                    WHERE a.SOLFICDOC = ? AND a.SOLFICEST = ?
                    
                    ORDER BY a.SOLFICCOD DESC";
            } elseif ($val03 == 'PC') {
                $val03  = 'C';
                $sql01  = "SELECT
                    a.SOLFICCOD         AS          solicitud_codigo,
                    a.SOLFICEST         AS          solicitud_estado_codigo,
                    a.SOLFICDOC         AS          solicitud_documento,
                    a.SOLFICFH1         AS          solicitud_fecha_desde,
                    a.SOLFICFH2         AS          solicitud_fecha_hasta,
                    a.SOLFICFHC         AS          solicitud_fecha_cantidad,
                    a.SOLFICHO1         AS          solicitud_hora_desde,
                    a.SOLFICHO2         AS          solicitud_hora_hasta,
                    a.SOLFICHOC         AS          solicitud_hora_cantidad,
                    a.SOLFICADJ         AS          solicitud_adjunto,
                    a.SOLFICUSC         AS          solicitud_usuario_colaborador,
                    a.SOLFICFCC         AS          solicitud_fecha_hora_colaborador,
                    a.SOLFICIPC         AS          solicitud_ip_colaborador, 
                    a.SOLFICOBC         AS          solicitud_observacion_colaborador,
                    a.SOLFICUSS         AS          solicitud_usuario_superior,
                    a.SOLFICFCS         AS          solicitud_fecha_hora_superior,
                    a.SOLFICIPS         AS          solicitud_ip_superior,
                    a.SOLFICOBS         AS          solicitud_observacion_superior,
                    a.SOLFICUST         AS          solicitud_usuario_talento,
                    a.SOLFICFCT         AS          solicitud_fecha_hora_talento,
                    a.SOLFICIPT         AS          solicitud_ip_talento,
                    a.SOLFICOBT         AS          solicitud_observacion_talento,
                    a.SOLFICUSU         AS          auditoria_usuario,
                    a.SOLFICFEC         AS          auditoria_fecha_hora,
                    a.SOLFICDIP         AS          auditoria_ip,

                    b.DOMSOLCOD         AS          tipo_permiso_codigo,
                    b.DOMSOLEST         AS          tipo_estado_codigo,
                    b.DOMSOLTST         AS          tipo_solicitud_codigo,
                    b.DOMSOLPC1         AS          tipo_permiso_codigo1,
                    b.DOMSOLPC2         AS          tipo_permiso_codigo2,
                    b.DOMSOLPC3         AS          tipo_permiso_codigo3,
                    b.DOMSOLORD         AS          tipo_orden_numero,
                    b.DOMSOLDIC         AS          tipo_dia_cantidad,
                    b.DOMSOLDIO         AS          tipo_dia_corrido,
                    b.DOMSOLDIU         AS          tipo_dia_unidad,
                    b.DOMSOLADJ         AS          tipo_archivo_adjunto,
                    b.DOMSOLOBS         AS          tipo_observacion

                    FROM [CSF_SFHOLOX].[hum].[SOLFIC] a
                    INNER JOIN [CSF_SFHOLOX].[adm].[DOMSOL] b ON a.SOLFICTST = b.DOMSOLCOD

                    WHERE a.SOLFICDOC = ? AND (a.SOLFICEST = 'P' OR a.SOLFICEST = ?) AND a.SOLFICUST <> ''
                    
                    ORDER BY a.SOLFICCOD DESC";
            } elseif ($val03 == 'T') {
                $sql01  = "SELECT
                    a.SOLFICCOD         AS          solicitud_codigo,
                    a.SOLFICEST         AS          solicitud_estado_codigo,
                    a.SOLFICDOC         AS          solicitud_documento,
                    a.SOLFICFH1         AS          solicitud_fecha_desde,
                    a.SOLFICFH2         AS          solicitud_fecha_hasta,
                    a.SOLFICFHC         AS          solicitud_fecha_cantidad,
                    a.SOLFICHO1         AS          solicitud_hora_desde,
                    a.SOLFICHO2         AS          solicitud_hora_hasta,
                    a.SOLFICHOC         AS          solicitud_hora_cantidad,
                    a.SOLFICADJ         AS          solicitud_adjunto,
                    a.SOLFICUSC         AS          solicitud_usuario_colaborador,
                    a.SOLFICFCC         AS          solicitud_fecha_hora_colaborador,
                    a.SOLFICIPC         AS          solicitud_ip_colaborador, 
                    a.SOLFICOBC         AS          solicitud_observacion_colaborador,
                    a.SOLFICUSS         AS          solicitud_usuario_superior,
                    a.SOLFICFCS         AS          solicitud_fecha_hora_superior,
                    a.SOLFICIPS         AS          solicitud_ip_superior,
                    a.SOLFICOBS         AS          solicitud_observacion_superior,
                    a.SOLFICUST         AS          solicitud_usuario_talento,
                    a.SOLFICFCT         AS          solicitud_fecha_hora_talento,
                    a.SOLFICIPT         AS          solicitud_ip_talento,
                    a.SOLFICOBT         AS          solicitud_observacion_talento,
                    a.SOLFICUSU         AS          auditoria_usuario,
                    a.SOLFICFEC         AS          auditoria_fecha_hora,
                    a.SOLFICDIP         AS          auditoria_ip,

                    b.DOMSOLCOD         AS          tipo_permiso_codigo,
                    b.DOMSOLEST         AS          tipo_estado_codigo,
                    b.DOMSOLTST         AS          tipo_solicitud_codigo,
                    b.DOMSOLPC1         AS          tipo_permiso_codigo1,
                    b.DOMSOLPC2         AS          tipo_permiso_codigo2,
                    b.DOMSOLPC3         AS          tipo_permiso_codigo3,
                    b.DOMSOLORD         AS          tipo_orden_numero,
                    b.DOMSOLDIC         AS          tipo_dia_cantidad,
                    b.DOMSOLDIO         AS          tipo_dia_corrido,
                    b.DOMSOLDIU         AS          tipo_dia_unidad,
                    b.DOMSOLADJ         AS          tipo_archivo_adjunto,
                    b.DOMSOLOBS         AS          tipo_observacion

                    FROM [CSF_SFHOLOX].[hum].[SOLFIC] a
                    INNER JOIN [CSF_SFHOLOX].[adm].[DOMSOL] b ON a.SOLFICTST = b.DOMSOLCOD

                    WHERE a.SOLFICDOC = ? AND a.SOLFICEST <> ?
                    
                    ORDER BY a.SOLFICCOD DESC";
            }

            try {
                $connMSSQL  = getConnectionMSSQLv1();
                
                $stmtMSSQL00= $connMSSQL->prepare($sql00);

                if ($val01 == '1' || $val01 == '2') {
                    $stmtMSSQL00->execute([$val02]);
                } elseif ($val01 == '3') {
                    $stmtMSSQL00->execute([]);
                } elseif ($val01 == '4') {
                    $stmtMSSQL00->execute([$val02, $val02]);
                } elseif ($val01 == '5') {
                    $stmtMSSQL00->execute([$val02]);
                }

                $stmtMSSQL01= $connMSSQL->prepare($sql01);

                while ($rowMSSQL00 = $stmtMSSQL00->fetch()) {
                    $stmtMSSQL01->execute([$rowMSSQL00['documento'], $val03]);

                    while ($rowMSSQL01 = $stmtMSSQL01->fetch()) {
                        switch ($rowMSSQL01['solicitud_estado_codigo']) {
                            case 'I':
                                $solicitud_estado_nombre = 'INGRESADO';
                                break;
                            
                            case 'A':
                                $solicitud_estado_nombre = 'AUTORIZADO';
                                break;
                            
                            case 'P':
                                $solicitud_estado_nombre = 'APROBADO';
                                break;
    
                            case 'C':
                                $solicitud_estado_nombre = 'ANULADO';
                                break;
                        }
    
                        switch ($rowMSSQL01['tipo_solicitud_codigo']) {
                            case 'L':
                                $tipo_solicitud_nombre  = 'LICENCIA';
                                $sql02                  = "SELECT U_NOMBRE AS tipo_permiso_nombre FROM [CSF].[dbo].[@A1A_TILC] WHERE U_CODIGO = ?";
                                break;
                            
                            case 'P':
                                $tipo_solicitud_nombre  = 'PERMISO';
                                $sql02                  = "SELECT U_NOMBRE AS tipo_permiso_nombre FROM [CSF].[dbo].[@A1A_TIPE] WHERE U_CODIGO = ?";
                                break;
            
                            case 'I':
                                $tipo_solicitud_nombre  = 'INASISTENCIA';
                                $sql02                  = "SELECT U_DESAMP AS tipo_permiso_nombre FROM [CSF].[dbo].[@A1A_TIIN] WHERE U_CODIGO = ?";
                                break;
                        }
    
                        $stmtMSSQL02= $connMSSQL->prepare($sql02);
                        $stmtMSSQL02->execute([trim(strtoupper($rowMSSQL01['tipo_permiso_codigo3']))]);
                        $rowMSSQL02 = $stmtMSSQL02->fetch(PDO::FETCH_ASSOC);

                        $tipo_permiso_nombre= $rowMSSQL02['tipo_permiso_nombre'];
                        $solicitud_persona  = $rowMSSQL00['nombre_completo'];
    
                        $detalle    = array(
                            'tipo_permiso_codigo'               => $rowMSSQL01['tipo_permiso_codigo'],
                            'tipo_permiso_nombre'               => trim(strtoupper($tipo_permiso_nombre)),
                            'solicitud_codigo'                  => $rowMSSQL01['solicitud_codigo'],
                            'solicitud_estado_codigo'           => $rowMSSQL01['solicitud_estado_codigo'],
                            'solicitud_estado_nombre'           => trim(strtoupper($solicitud_estado_nombre)),
                            'solicitud_documento'               => trim(strtoupper($rowMSSQL01['solicitud_documento'])),
                            'solicitud_persona'                 => trim(strtoupper($solicitud_persona)),
                            'solicitud_fecha_desde_1'           => $rowMSSQL01['solicitud_fecha_desde'],
                            'solicitud_fecha_desde_2'           => date("d/m/Y", strtotime($rowMSSQL01['solicitud_fecha_desde'])),
                            'solicitud_fecha_hasta_1'           => $rowMSSQL01['solicitud_fecha_hasta'],
                            'solicitud_fecha_hasta_2'           => date("d/m/Y", strtotime($rowMSSQL01['solicitud_fecha_hasta'])),
                            'solicitud_fecha_cantidad'          => $rowMSSQL01['solicitud_fecha_cantidad'],
                            'solicitud_hora_desde'              => trim(strtoupper($rowMSSQL01['solicitud_hora_desde'])),
                            'solicitud_hora_hasta'              => trim(strtoupper($rowMSSQL01['solicitud_hora_hasta'])),
                            'solicitud_hora_cantidad'           => $rowMSSQL01['solicitud_hora_cantidad'],
                            'solicitud_adjunto'                 => trim(strtolower($rowMSSQL01['solicitud_adjunto'])),
                            'solicitud_usuario_colaborador'     => trim(strtoupper($rowMSSQL01['solicitud_usuario_colaborador'])),
                            'solicitud_fecha_hora_colaborador'  => date("d/m/Y", strtotime($rowMSSQL01['solicitud_fecha_hora_colaborador'])),
                            'solicitud_ip_colaborador'          => trim(strtoupper($rowMSSQL01['solicitud_ip_colaborador'])),
                            'solicitud_observacion_colaborador' => trim(strtoupper($rowMSSQL01['solicitud_observacion_colaborador'])),
                            'solicitud_usuario_superior'        => trim(strtoupper($rowMSSQL01['solicitud_usuario_superior'])),
                            'solicitud_fecha_hora_superior'     => date("d/m/Y", strtotime($rowMSSQL01['solicitud_fecha_hora_superior'])),
                            'solicitud_ip_superior'             => trim(strtoupper($rowMSSQL01['solicitud_ip_superior'])),
                            'solicitud_observacion_superior'    => trim(strtoupper($rowMSSQL01['solicitud_observacion_superior'])),
                            'solicitud_usuario_talento'         => trim(strtoupper($rowMSSQL01['solicitud_usuario_talento'])),
                            'solicitud_fecha_hora_talento'      => date("d/m/Y", strtotime($rowMSSQL01['solicitud_fecha_hora_talento'])),
                            'solicitud_ip_talento'              => trim(strtoupper($rowMSSQL01['solicitud_ip_talento'])),
                            'solicitud_observacion_talento'     => trim(strtoupper($rowMSSQL01['solicitud_observacion_talento'])),
                            'auditoria_usuario'                 => trim(strtoupper($rowMSSQL01['auditoria_usuario'])),
                            'auditoria_fecha_hora'              => date("d/m/Y", strtotime($rowMSSQL01['auditoria_fecha_hora'])),
                            'auditoria_ip'                      => trim(strtoupper($rowMSSQL01['auditoria_ip']))
                        );
    
                        $result[]   = $detalle;

                        $stmtMSSQL02->closeCursor();
                        $stmtMSSQL02 = null;
                    }
                }

                if (isset($result)){
                    header("Content-Type: application/json; charset=utf-8");
                    $json = json_encode(array('code' => 200, 'status' => 'ok', 'message' => 'Success SELECT', 'data' => $result), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
                } else {
                    $detalle    = array(
                        'tipo_solicitud_codigo'             => '',
                        'tipo_permiso_nombre'               => '',
                        'solicitud_codigo'                  => '',
                        'solicitud_estado_codigo'           => '',
                        'solicitud_estado_nombre'           => '',
                        'solicitud_documento'               => '',
                        'solicitud_fecha_desde_1'           => '',
                        'solicitud_fecha_desde_2'           => '',
                        'solicitud_fecha_hasta_1'           => '',
                        'solicitud_fecha_hasta_2'           => '',
                        'solicitud_fecha_cantidad'          => '',
                        'solicitud_hora_desde'              => '',
                        'solicitud_hora_hasta'              => '',
                        'solicitud_hora_cantidad'           => '',
                        'solicitud_adjunto'                 => '',
                        'solicitud_usuario_colaborador'     => '',
                        'solicitud_fecha_hora_colaborador'  => '',
                        'solicitud_ip_colaborador'          => '',
                        'solicitud_observacion_colaborador' => '',
                        'solicitud_usuario_superior'        => '',
                        'solicitud_fecha_hora_superior'     => '',
                        'solicitud_ip_superior'             => '',
                        'solicitud_observacion_superior'    => '',
                        'solicitud_usuario_talento'         => '',
                        'solicitud_fecha_hora_talento'      => '',
                        'solicitud_ip_talento'              => '',
                        'solicitud_observacion_talento'     => '',
                        'auditoria_usuario'                 => '',
                        'auditoria_fecha_hora'              => '',
                        'auditoria_ip'                      => ''
                    );

                    header("Content-Type: application/json; charset=utf-8");
                    $json = json_encode(array('code' => 204, 'status' => 'ok', 'message' => 'No hay registros', 'data' => $detalle), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
                }

                $stmtMSSQL00->closeCursor();
                $stmtMSSQL00 = null;

                $stmtMSSQL01->closeCursor();
                $stmtMSSQL01 = null;
            } catch (PDOException $e) {
                header("Content-Type: application/json; charset=utf-8");
                $json = json_encode(array('code' => 204, 'status' => 'failure', 'message' => 'Error SELECT: '.$e), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
            }
        } else {
            header("Content-Type: application/json; charset=utf-8");
            $json = json_encode(array('code' => 400, 'status' => 'error', 'message' => 'Verifique, algún campo esta vacio.'), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
        }

        $connMSSQL  = null;
        
        return $json;
    });

    $app->get('/v1/200/solicitudes/grafico/tipocab/{sexo}/{tipo}', function($request) {
        require __DIR__.'/../src/connect.php';
        
        $val01  = $request->getAttribute('sexo');
        $val02  = $request->getAttribute('tipo');

        $sql01  = "SELECT count(*) AS solicitud_cantidad, 'TOTAL_COLABORADOR' AS solicitud_tipo
        FROM [CSF].[dbo].[empleados_AxisONE] a
        WHERE a.SEXO = ?
        UNION
        SELECT count(*)  AS solicitud_cantidad, 'CON_SOLICITUD' AS solicitud_tipo
        FROM [CSF].[dbo].[empleados_AxisONE] a
        WHERE a.SEXO = ? AND EXISTS (SELECT * FROM [CSF_SFHOLOX].[hum].[SOLFIC] b WHERE b.SOLFICEST <> 'C' AND b.SOLFICTST = ? AND a.CedulaEmpleado = b.SOLFICDOC COLLATE SQL_Latin1_General_CP1_CI_AS)
        UNION
        SELECT count(*)  AS solicitud_cantidad, 'SIN_SOLICITUD' AS solicitud_tipo
        FROM [CSF].[dbo].[empleados_AxisONE] a
        WHERE a.SEXO = ? AND NOT EXISTS (SELECT * FROM [CSF_SFHOLOX].[hum].[SOLFIC] b WHERE b.SOLFICEST <> 'C' AND b.SOLFICTST = ? AND a.CedulaEmpleado = b.SOLFICDOC COLLATE SQL_Latin1_General_CP1_CI_AS)";

        try {
            $connMSSQL  = getConnectionMSSQLv1();
            
            $stmtMSSQL01= $connMSSQL->prepare($sql01);
            $stmtMSSQL01->execute([$val01, $val01, $val02, $val01, $val02]);

            while ($rowMSSQL01 = $stmtMSSQL01->fetch()) {
                $detalle    = array(
                    'solicitud_tipo'            => $rowMSSQL01['solicitud_tipo'],
                    'solicitud_cantidad'        => $rowMSSQL01['solicitud_cantidad']
                );

                $result[]   = $detalle;
            }

            if (isset($result)){
                header("Content-Type: application/json; charset=utf-8");
                $json = json_encode(array('code' => 200, 'status' => 'ok', 'message' => 'Success SELECT', 'data' => $result), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
            } else {
                $detalle    = array(
                    'solicitud_tipo'            => '',
                    'solicitud_cantidad'        => ''
                );

                header("Content-Type: application/json; charset=utf-8");
                $json = json_encode(array('code' => 204, 'status' => 'ok', 'message' => 'No hay registros', 'data' => $detalle), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
            }

            $stmtMSSQL01->closeCursor();
            $stmtMSSQL01 = null;
        } catch (PDOException $e) {
            header("Content-Type: application/json; charset=utf-8");
            $json = json_encode(array('code' => 204, 'status' => 'failure', 'message' => 'Error SELECT: '.$e), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
        }

        $connMSSQL  = null;
        
        return $json;
    });

    $app->get('/v1/200/solicitudes/grafico/tipodet/{sexo}/{tipo}', function($request) {
        require __DIR__.'/../src/connect.php';
        
        $val01  = $request->getAttribute('sexo');
        $val02  = $request->getAttribute('tipo');

        $sql01  = "SELECT a.CedulaEmpleado AS solicitud_documento, a.NombreEmpleado AS solicitud_persona, 'CON_SOLICITUD' AS solicitud_tipo
        FROM [CSF].[dbo].[empleados_AxisONE] a
        WHERE a.SEXO = ? AND EXISTS (SELECT * FROM [CSF_SFHOLOX].[hum].[SOLFIC] b WHERE b.SOLFICEST <> 'C' AND b.SOLFICTST = ? AND a.CedulaEmpleado = b.SOLFICDOC COLLATE SQL_Latin1_General_CP1_CI_AS)
        UNION
        SELECT a.CedulaEmpleado AS solicitud_documento, a.NombreEmpleado AS solicitud_persona, 'SIN_SOLICITUD' AS solicitud_tipo
        FROM [CSF].[dbo].[empleados_AxisONE] a
        WHERE a.SEXO = ? AND NOT EXISTS (SELECT * FROM [CSF_SFHOLOX].[hum].[SOLFIC] b WHERE b.SOLFICEST <> 'C' AND b.SOLFICTST = ? AND a.CedulaEmpleado = b.SOLFICDOC COLLATE SQL_Latin1_General_CP1_CI_AS)";

        try {
            $connMSSQL  = getConnectionMSSQLv1();
            
            $stmtMSSQL01= $connMSSQL->prepare($sql01);
            $stmtMSSQL01->execute([$val01, $val02, $val01, $val02]);

            while ($rowMSSQL01 = $stmtMSSQL01->fetch()) {
                $detalle    = array(
                    'solicitud_documento'           => $rowMSSQL01['solicitud_documento'],
                    'solicitud_persona'             => $rowMSSQL01['solicitud_persona'],
                    'solicitud_tipo'                => $rowMSSQL01['solicitud_tipo']
                );

                $result[]   = $detalle;
            }

            if (isset($result)){
                header("Content-Type: application/json; charset=utf-8");
                $json = json_encode(array('code' => 200, 'status' => 'ok', 'message' => 'Success SELECT', 'data' => $result), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
            } else {
                $detalle    = array(
                    'solicitud_documento'           => '',
                    'solicitud_persona'             => '',
                    'solicitud_tipo'                => ''
                );

                header("Content-Type: application/json; charset=utf-8");
                $json = json_encode(array('code' => 204, 'status' => 'ok', 'message' => 'No hay registros', 'data' => $detalle), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
            }

            $stmtMSSQL01->closeCursor();
            $stmtMSSQL01 = null;
        } catch (PDOException $e) {
            header("Content-Type: application/json; charset=utf-8");
            $json = json_encode(array('code' => 204, 'status' => 'failure', 'message' => 'Error SELECT: '.$e), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
        }

        $connMSSQL  = null;
        
        return $json;
    });

    $app->get('/v1/200/exportar/tipo/{codigo}/{estado}', function($request) {
        require __DIR__.'/../src/connect.php';
        
        $val01      = $request->getAttribute('codigo');
        $val02      = $request->getAttribute('estado');
        
        if (isset($val01)) {
            $sql00  = "SELECT
                a.SOLAXICOD         AS          solicitud_detalle_codigo,
                a.SOLAXICAB         AS          solicitud_detalle_cabecera,
                a.SOLAXIEST         AS          solicitud_detalle_estado,
                a.SOLAXISOL         AS          solicitud_detalle_solicitud,
                a.SOLAXIDOC         AS          solicitud_detalle_empleado,
                a.SOLAXIFED         AS          solicitud_detalle_fecha_desde,
                a.SOLAXIFEH         AS          solicitud_detalle_fecha_hasta,
                a.SOLAXIAPD         AS          solicitud_detalle_aplicacion_desde,
                a.SOLAXIAPH         AS          solicitud_detalle_aplicacion_hasta,
                a.SOLAXICAN         AS          solicitud_detalle_cantidad_dia,
                a.SOLAXITIP         AS          solicitud_detalle_tipo,
                a.SOLAXIDIA         AS          solicitud_detalle_cantidad_diaria,
                a.SOLAXIUNI         AS          solicitud_detalle_unidad,
                a.SOLAXICOM         AS          solicitud_detalle_comentario,
                a.SOLAXIIDP         AS          solicitud_detalle_people_gate,
                a.SOLAXICON         AS          solicitud_detalle_cantidad_convertida,         
                a.SOLAXICLA         AS          solicitud_detalle_clase,
                a.SOLAXILIN         AS          solicitud_detalle_evento,
                a.SOLAXIORI         AS          solicitud_detalle_origen,
                a.SOLAXIGRU         AS          solicitud_detalle_grupo,
                a.SOLAXIUSU         AS          auditoria_usuario,
                a.SOLAXIFEC         AS          auditoria_fecha_hora,
                a.SOLAXIDIP         AS          auditoria_ip

                FROM [CSF_SFHOLOX].[hum].[SOLAXI] a

                WHERE a.SOLAXISOL = ? AND a.SOLAXIEST = ?";

            try {
                $connMSSQL  = getConnectionMSSQLv1();

                $stmtMSSQL00= $connMSSQL->prepare($sql00);
                $stmtMSSQL00->execute([$val01, $val02]);

                while ($rowMSSQL00 = $stmtMSSQL00->fetch()) {
                    $detalle    = array(
                        'solicitud_detalle_codigo'                      => $rowMSSQL00['solicitud_detalle_codigo'],
                        'solicitud_detalle_cabecera'                    => $rowMSSQL00['solicitud_detalle_cabecera'],
                        'solicitud_detalle_estado'                      => trim(strtoupper($rowMSSQL00['solicitud_detalle_estado'])),
                        'solicitud_detalle_solicitud'                   => trim(strtoupper($rowMSSQL00['solicitud_detalle_solicitud'])),
                        'solicitud_detalle_empleado'                    => trim(strtoupper($rowMSSQL00['solicitud_detalle_empleado'])),
                        'solicitud_detalle_fecha_desde'                 => date("d/m/Y", strtotime($rowMSSQL00['solicitud_detalle_fecha_desde'])),
                        'solicitud_detalle_fecha_hasta'                 => date("d/m/Y", strtotime($rowMSSQL00['solicitud_detalle_fecha_hasta'])),
                        'solicitud_detalle_aplicacion_desde'            => date("d/m/Y", strtotime($rowMSSQL00['solicitud_detalle_aplicacion_desde'])),
                        'solicitud_detalle_aplicacion_hasta'            => date("d/m/Y", strtotime($rowMSSQL00['solicitud_detalle_aplicacion_hasta'])),
                        'solicitud_detalle_cantidad_dia'                => $rowMSSQL00['solicitud_detalle_cantidad_dia'],
                        'solicitud_detalle_tipo'                        => trim(strtoupper($rowMSSQL00['solicitud_detalle_tipo'])),
                        'solicitud_detalle_cantidad_diaria'             => $rowMSSQL00['solicitud_detalle_cantidad_diaria'],
                        'solicitud_detalle_unidad'                      => $rowMSSQL00['solicitud_detalle_unidad'],
                        'solicitud_detalle_comentario'                  => trim(strtoupper($rowMSSQL00['solicitud_detalle_comentario'])),
                        'solicitud_detalle_people_gate'                 => trim(strtoupper($rowMSSQL00['solicitud_detalle_people_gate'])),
                        'solicitud_detalle_cantidad_convertida'         => trim(strtoupper($rowMSSQL00['solicitud_detalle_cantidad_convertida'])),
                        'solicitud_detalle_clase'                       => trim(strtoupper($rowMSSQL00['solicitud_detalle_clase'])),
                        'solicitud_detalle_evento'                      => trim(strtoupper($rowMSSQL00['solicitud_detalle_evento'])),
                        'solicitud_detalle_origen'                      => trim(strtoupper($rowMSSQL00['solicitud_detalle_origen'])),
                        'solicitud_detalle_grupo'                       => $rowMSSQL00['solicitud_detalle_grupo'],
                        'auditoria_usuario'                             => trim(strtoupper($rowMSSQL00['auditoria_usuario'])),
                        'auditoria_fecha_hora'                          => date("d/m/Y H:i:s", strtotime($rowMSSQL00['auditoria_fecha_hora'])),
                        'auditoria_ip'                                  => trim(strtoupper($rowMSSQL00['auditoria_ip']))
                    );

                    $result[]   = $detalle;
                }

                if (isset($result)){
                    header("Content-Type: application/json; charset=utf-8");
                    $json = json_encode(array('code' => 200, 'status' => 'ok', 'message' => 'Success SELECT', 'data' => $result), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
                } else {
                    $detalle    = array(
                        'solicitud_detalle_codigo'                      => '',
                        'solicitud_detalle_cabecera'                    => '',
                        'solicitud_detalle_estado'                      => '',
                        'solicitud_detalle_solicitud'                   => '',
                        'solicitud_detalle_empleado'                    => '',
                        'solicitud_detalle_fecha_desde'                 => '',
                        'solicitud_detalle_fecha_hasta'                 => '',
                        'solicitud_detalle_aplicacion_desde'            => '',
                        'solicitud_detalle_aplicacion_hasta'            => '',
                        'solicitud_detalle_cantidad_dia'                => '',
                        'solicitud_detalle_tipo'                        => '',
                        'solicitud_detalle_cantidad_diaria'             => '',
                        'solicitud_detalle_unidad'                      => '',
                        'solicitud_detalle_comentario'                  => '',
                        'solicitud_detalle_people_gate'                 => '',
                        'solicitud_detalle_cantidad_convertida'         => '',
                        'solicitud_detalle_clase'                       => '',
                        'solicitud_detalle_evento'                      => '',
                        'solicitud_detalle_origen'                      => '',
                        'solicitud_detalle_grupo'                       => '',
                        'auditoria_usuario'                             => '',
                        'auditoria_fecha_hora'                          => '',
                        'auditoria_ip'                                  => ''
                    );

                    header("Content-Type: application/json; charset=utf-8");
                    $json = json_encode(array('code' => 204, 'status' => 'ok', 'message' => 'No hay registros', 'data' => $detalle), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
                }

                $stmtMSSQL00->closeCursor();
                $stmtMSSQL00 = null;
            } catch (PDOException $e) {
                header("Content-Type: application/json; charset=utf-8");
                $json = json_encode(array('code' => 204, 'status' => 'failure', 'message' => 'Error SELECT: '.$e), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
            }
        } else {
            header("Content-Type: application/json; charset=utf-8");
            $json = json_encode(array('code' => 400, 'status' => 'error', 'message' => 'Verifique, algún campo esta vacio.'), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
        }

        $connMSSQL  = null;
        
        return $json;
    });

    $app->get('/v1/200/comprobante', function($request) {
        require __DIR__.'/../src/connect.php';
        
        $sql00  = "SELECT
            a.COMFICCOD         AS          comprobante_codigo,
            a.COMFICPER         AS          comprobante_periodo,
            a.COMFICDOC         AS          comprobante_documento,
            a.COMFICADJ         AS          comprobante_adjunto,
            a.COMFICOBS         AS          comprobante_observacion,
            a.COMFICAUS         AS          auditoria_usuario,
            a.COMFICAFH         AS          auditoria_fecha_hora,
            a.COMFICAIP         AS          auditoria_ip,

            b.DOMFICCOD         AS          tipo_estado_codigo,
            b.DOMFICNOI         AS          tipo_estado_ingles,
            b.DOMFICNOC         AS          tipo_estado_castellano,
            b.DOMFICNOP         AS          tipo_estado_portugues,

            c.DOMFICCOD         AS          tipo_comprobante_codigo,
            c.DOMFICNOI         AS          tipo_comprobante_ingles,
            c.DOMFICNOC         AS          tipo_comprobante_castellano,
            c.DOMFICNOP         AS          tipo_comprobante_portugues,

            d.DOMFICCOD         AS          tipo_mes_codigo,
            d.DOMFICNOI         AS          tipo_mes_ingles,
            d.DOMFICNOC         AS          tipo_mes_castellano,
            d.DOMFICNOP         AS          tipo_mes_portugues

            FROM [CSF_SFHOLOX].[hum].[COMFIC] a
            INNER JOIN [CSF_SFHOLOX].[adm].[DOMFIC] b ON a.COMFICEST = b.DOMFICCOD
            INNER JOIN [CSF_SFHOLOX].[adm].[DOMFIC] c ON a.COMFICTCC = b.DOMFICCOD
            INNER JOIN [CSF_SFHOLOX].[adm].[DOMFIC] d ON a.COMFICTMC = b.DOMFICCOD
            
            ORDER BY a.COMFICCOD DESC";

        $sql01  = "SELECT
            a.CedulaEmpleado            AS          documento,
            a.ApellidoPaterno           AS          apellido_1,
            a.ApellidoMaterno           AS          apellido_2,
            a.PrimerNombre              AS          nombre_1,
            a.SegundoNombre             AS          nombre_2,
            a.NombreEmpleado            AS          nombre_completo,
            a.Sexo                      AS          tipo_sexo_codigo,
            a.EstadoCivil               AS          estado_civil_codigo,
            a.Email                     AS          email,
            a.FechaNacimiento           AS          fecha_nacimiento,
            a.CodigoCargo               AS          cargo_codigo,
            a.Cargo                     AS          cargo_nombre,
            a.CodigoGerencia            AS          gerencia_codigo,
            a.Gerencia                  AS          gerencia_nombre,
            a.CodigoDepto               AS          departamento_codigo,
            a.Departamento              AS          departamento_nombre,         
            a.CodCargoSuperior          AS          superior_cargo_codigo,
            a.NombreCargoSuperior       AS          superior_cargo_nombre,
            a.Manager                   AS          superior_manager_nombre,
            a.EmailManager              AS          superior_manager_email

            FROM [CSF].[dbo].[empleados_AxisONE] a

            WHERE a.CedulaEmpleado = ?";

        try {
            $connMSSQL  = getConnectionMSSQLv1();
            
            $stmtMSSQL00= $connMSSQL->prepare($sql00);
            $stmtMSSQL01= $connMSSQL->prepare($sql01);

            $stmtMSSQL00->execute();

            while ($rowMSSQL00 = $stmtMSSQL00->fetch()) {
                $stmtMSSQL01->execute([trim(strtoupper(strtolower($rowMSSQL00['comprobante_documento'])))]);
                $rowMSSQL01 = $stmtMSSQL01->fetch(PDO::FETCH_ASSOC);

                $detalle    = array(
                    'comprobante_codigo'                => $rowMSSQL00['comprobante_codigo'],
                    'comprobante_periodo'               => $rowMSSQL00['comprobante_periodo'],
                    'comprobante_colaborador'           => trim(strtoupper(strtolower($rowMSSQL01['nombre_completo']))),
                    'comprobante_documento'             => trim(strtoupper(strtolower($rowMSSQL00['comprobante_documento']))),
                    'comprobante_adjunto'               => trim(strtolower($rowMSSQL00['comprobante_adjunto'])),
                    'comprobante_observacion'           => trim(strtoupper(strtolower($rowMSSQL00['comprobante_observacion']))),

                    'auditoria_usuario'                 => trim(strtoupper(strtolower($rowMSSQL01['auditoria_usuario']))),
                    'auditoria_fecha_hora'              => date("d/m/Y", strtotime($rowMSSQL01['auditoria_fecha_hora'])),
                    'auditoria_ip'                      => trim(strtoupper(strtolower($rowMSSQL01['auditoria_ip']))),

                    'tipo_estado_codigo'                => $rowMSSQL00['tipo_estado_codigo'],
                    'tipo_estado_nombre'                => trim(strtoupper(strtolower($rowMSSQL00['tipo_estado_castellano']))),

                    'tipo_comprobante_codigo'           => $rowMSSQL00['tipo_comprobante_codigo'],
                    'tipo_comprobante_nombre'           => trim(strtoupper(strtolower($rowMSSQL00['tipo_comprobante_castellano']))),

                    'tipo_mes_codigo'                   => $rowMSSQL00['tipo_mes_codigo'],
                    'tipo_mes_nombre'                   => trim(strtoupper(strtolower($rowMSSQL00['tipo_mes_castellano'])))
                );

                $result[]   = $detalle;
            }

            if (isset($result)){
                header("Content-Type: application/json; charset=utf-8");
                $json = json_encode(array('code' => 200, 'status' => 'ok', 'message' => 'Success SELECT', 'data' => $result), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
            } else {
                $detalle    = array(
                    'comprobante_codigo'                => '',
                    'comprobante_periodo'               => '',
                    'comprobante_colaborador'           => '',
                    'comprobante_documento'             => '',
                    'comprobante_adjunto'               => '',
                    'comprobante_observacion'           => '',
                    'auditoria_usuario'                 => '',
                    'auditoria_fecha_hora'              => '',
                    'auditoria_ip'                      => '',
                    'tipo_estado_codigo'                => '',
                    'tipo_estado_nombre'                => '',
                    'tipo_comprobante_codigo'           => '',
                    'tipo_comprobante_nombre'           => '',
                    'tipo_mes_codigo'                   => '',
                    'tipo_mes_nombre'                   => ''
                );

                header("Content-Type: application/json; charset=utf-8");
                $json = json_encode(array('code' => 204, 'status' => 'ok', 'message' => 'No hay registros', 'data' => $detalle), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
            }

            $stmtMSSQL00->closeCursor();
            $stmtMSSQL01->closeCursor();

            $stmtMSSQL00 = null;
            $stmtMSSQL01 = null;
        } catch (PDOException $e) {
            header("Content-Type: application/json; charset=utf-8");
            $json = json_encode(array('code' => 204, 'status' => 'failure', 'message' => 'Error SELECT: '.$e), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
        }

        $connMSSQL  = null;
        
        return $json;
    });