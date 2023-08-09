<?php
session_start();
include('../modelo/conexion.php');
if ($_SESSION["logueado"] == TRUE && ($_SESSION["permisoIngresar"] == 'CONSULTAS_OFICINA' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES')){
    $usuario = $_SESSION['usuario'];
    $Fecha = date('Y-m-d');
    $Year = date('Y');
    $Fecha_actual = date('Y-m-d',strtotime($Fecha . ' - 15 days'));
    ini_set('date.timezone', 'America/Bogota');
    $hora = date("g:i A");
    $sql_usuario = "SELECT * FROM Usuarios WHERE idUsuario='$usuario'";
    $result = sqlsrv_query($conn,$sql_usuario);
    while ($row = sqlsrv_fetch_array($result)){
      $Nombre = $row['NombreUsuarioLargo'];
    }
}elseif($_SESSION['permisoIngresar'] == 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES'){
    header("Location: MantenimientoCargadores.php");
}elseif($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){
    header("Location: incio.php");
}elseif($_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO'){
    header("Location: inicio_patio.php");
}else{
    header("Location: ../index.php");
    die();
}
$sql_consulta = "";
$res_consulta = "";
if(isset($_POST['buscar'])){
    if($_POST['fecha_inicio'] == ""){
        echo '<script type="text/javascript"> alert("Seleccione una fecha inicio");</script>';
    }elseif($_POST['fecha_fin'] == ""){
        echo '<script type="text/javascript"> alert("Seleccione una fecha fin");</script>';
    }elseif($_POST['empresa'] == ""){
        echo '<script type="text/javascript"> alert("Seleccione una empresa");</script>';
    }elseif ($_POST['fecha_inicio'] != "" && $_POST['fecha_fin'] != "" && $_POST['empresa'] != ""){
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $empresa = $_POST['empresa'];
        $acopio = $_POST['acopio'];
        $proveedor = $_POST['proveedor'];
        $clasificacion = $_POST['clasificacion'];
        if($_POST['fecha_inicio']>$_POST['fecha_fin'])
        {   echo '<script type="text/javascript"> alert("Fecha Inicial Mayor que Fecha Final");</script>';
        }elseif($acopio == "0" && $proveedor=="0" && $clasificacion=="0"){
            $sql_consulta = "SELECT Actividades.Descripcion AS Actividad, 
                            subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, SUM(horometro.total_horas) AS total_horas,
                            Equipos.Descripcion AS Maquina, Equipos.Identificacion AS Prefijo, Proveedores.Razonsocial AS Proveedor, 
                            TarifaMaquinaria.Tarifa_Horometro, SUM(Tiempos_cargadores_actividad.TM_total) AS TM_total, 
                            Clasificacion.Descripcion AS Clasificacion
                            FROM horometro INNER JOIN
                                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN 
                                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                Proveedores ON Equipos.idPropietario=Proveedores.idProveedor LEFT JOIN 
                                TarifaMaquinaria ON Equipos.idEquipo = TarifaMaquinaria.idEquipo LEFT JOIN 
                                Tiempos_cargadores_actividad ON horometro.id_registro = Tiempos_cargadores_actividad.idRegistro
                                AND horometro.idSubActividad = Tiempos_cargadores_actividad.idSubActividad 
                                AND horometro.idClasificacion = Tiempos_cargadores_actividad.idClasificacion LEFT JOIN
                                Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                            WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                                (Registro_tique_cargadores.id_proveedor = '$empresa')  AND CAST(TarifaMaquinaria.Fecha_Hasta AS date) = '1900-01-01'
                                AND Registro_tique_cargadores.estado='3' and horometro.tipo_tarifa<>1
                            GROUP BY  horometro.idActividad, Actividades.Descripcion, 
                                      subactividades_cargadores.Descripcion, Destino.Descripcion, Equipos.Descripcion, 
                                      Equipos.Identificacion, Proveedores.Razonsocial, TarifaMaquinaria.Tarifa_Horometro,
                                      Clasificacion.Descripcion
                            ORDER BY Destino.Descripcion, Equipos.Descripcion, Actividades.Descripcion, subactividades_cargadores.Descripcion";
        }elseif($acopio == "0" && $proveedor=="0" && $clasificacion<>"0"){
             $sql_consulta = "SELECT Actividades.Descripcion AS Actividad, 
                            subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, SUM(horometro.total_horas) AS total_horas,
                            Equipos.Descripcion AS Maquina, Equipos.Identificacion AS Prefijo, Proveedores.Razonsocial AS Proveedor, 
                            TarifaMaquinaria.Tarifa_Horometro, SUM(Tiempos_cargadores_actividad.TM_total) AS TM_total, 
                            Clasificacion.Descripcion AS Clasificacion
                            FROM horometro INNER JOIN
                                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN 
                                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                Proveedores ON Equipos.idPropietario=Proveedores.idProveedor LEFT JOIN 
                                TarifaMaquinaria ON Equipos.idEquipo = TarifaMaquinaria.idEquipo LEFT JOIN 
                                Tiempos_cargadores_actividad ON horometro.id_registro = Tiempos_cargadores_actividad.idRegistro
                                AND horometro.idSubActividad = Tiempos_cargadores_actividad.idSubActividad 
                                AND horometro.idClasificacion = Tiempos_cargadores_actividad.idClasificacion LEFT JOIN
                                Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                            WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                                 horometro.idClasificacion='$clasificacion' and (Registro_tique_cargadores.id_proveedor = '$empresa')  AND CAST(TarifaMaquinaria.Fecha_Hasta AS date) = '1900-01-01'
                                AND Registro_tique_cargadores.estado='3' and horometro.tipo_tarifa<>1
                            GROUP BY  horometro.idActividad, Actividades.Descripcion, 
                                      subactividades_cargadores.Descripcion, Destino.Descripcion, Equipos.Descripcion, 
                                      Equipos.Identificacion, Proveedores.Razonsocial, TarifaMaquinaria.Tarifa_Horometro,
                                      Clasificacion.Descripcion
                            ORDER BY Destino.Descripcion, Equipos.Descripcion, Actividades.Descripcion, subactividades_cargadores.Descripcion";
        }elseif($acopio == "0" && $proveedor<>"0" && $clasificacion=="0"){
           $sql_consulta = "SELECT Actividades.Descripcion AS Actividad, 
                            subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, SUM(horometro.total_horas) AS total_horas,
                            Equipos.Descripcion AS Maquina, Equipos.Identificacion AS Prefijo, Proveedores.Razonsocial AS Proveedor, 
                            TarifaMaquinaria.Tarifa_Horometro, SUM(Tiempos_cargadores_actividad.TM_total) AS TM_total, 
                            Clasificacion.Descripcion AS Clasificacion
                            FROM horometro INNER JOIN
                                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN 
                                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                Proveedores ON Equipos.idPropietario=Proveedores.idProveedor LEFT JOIN 
                                TarifaMaquinaria ON Equipos.idEquipo = TarifaMaquinaria.idEquipo LEFT JOIN 
                                Tiempos_cargadores_actividad ON horometro.id_registro = Tiempos_cargadores_actividad.idRegistro
                                AND horometro.idSubActividad = Tiempos_cargadores_actividad.idSubActividad 
                                AND horometro.idClasificacion = Tiempos_cargadores_actividad.idClasificacion LEFT JOIN
                                Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                            WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                                Equipos.idPropietario='$proveedor' and  (Registro_tique_cargadores.id_proveedor = '$empresa')  AND CAST(TarifaMaquinaria.Fecha_Hasta AS date) = '1900-01-01'
                                AND Registro_tique_cargadores.estado='3' and horometro.tipo_tarifa<>1
                            GROUP BY  horometro.idActividad, Actividades.Descripcion, 
                                      subactividades_cargadores.Descripcion, Destino.Descripcion, Equipos.Descripcion, 
                                      Equipos.Identificacion, Proveedores.Razonsocial, TarifaMaquinaria.Tarifa_Horometro,
                                      Clasificacion.Descripcion
                            ORDER BY Destino.Descripcion, Equipos.Descripcion, Actividades.Descripcion, subactividades_cargadores.Descripcion";
        }elseif($acopio == "0" && $proveedor<>"0" && $clasificacion<>"0"){
           $sql_consulta = "SELECT Actividades.Descripcion AS Actividad, 
                            subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, SUM(horometro.total_horas) AS total_horas,
                            Equipos.Descripcion AS Maquina, Equipos.Identificacion AS Prefijo, Proveedores.Razonsocial AS Proveedor, 
                            TarifaMaquinaria.Tarifa_Horometro, SUM(Tiempos_cargadores_actividad.TM_total) AS TM_total, 
                            Clasificacion.Descripcion AS Clasificacion
                            FROM horometro INNER JOIN
                                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN 
                                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                Proveedores ON Equipos.idPropietario=Proveedores.idProveedor LEFT JOIN 
                                TarifaMaquinaria ON Equipos.idEquipo = TarifaMaquinaria.idEquipo LEFT JOIN 
                                Tiempos_cargadores_actividad ON horometro.id_registro = Tiempos_cargadores_actividad.idRegistro
                                AND horometro.idSubActividad = Tiempos_cargadores_actividad.idSubActividad 
                                AND horometro.idClasificacion = Tiempos_cargadores_actividad.idClasificacion LEFT JOIN
                                Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                            WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                                 horometro.idClasificacion='$clasificacion' and Equipos.idPropietario='$proveedor' and 
                                 (Registro_tique_cargadores.id_proveedor = '$empresa')  AND CAST(TarifaMaquinaria.Fecha_Hasta AS date) = '1900-01-01'
                                AND Registro_tique_cargadores.estado='3' and horometro.tipo_tarifa<>1
                            GROUP BY  horometro.idActividad, Actividades.Descripcion, 
                                      subactividades_cargadores.Descripcion, Destino.Descripcion, Equipos.Descripcion, 
                                      Equipos.Identificacion, Proveedores.Razonsocial, TarifaMaquinaria.Tarifa_Horometro,
                                      Clasificacion.Descripcion
                            ORDER BY Destino.Descripcion, Equipos.Descripcion, Actividades.Descripcion, subactividades_cargadores.Descripcion";
        }elseif($acopio <>"0" && $proveedor=="0" && $clasificacion=="0"){
             $sql_consulta = "SELECT Actividades.Descripcion AS Actividad, 
                            subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, SUM(horometro.total_horas) AS total_horas,
                            Equipos.Descripcion AS Maquina, Equipos.Identificacion AS Prefijo, Proveedores.Razonsocial AS Proveedor, 
                            TarifaMaquinaria.Tarifa_Horometro, SUM(Tiempos_cargadores_actividad.TM_total) AS TM_total, 
                            Clasificacion.Descripcion AS Clasificacion
                            FROM horometro INNER JOIN
                                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN 
                                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                Proveedores ON Equipos.idPropietario=Proveedores.idProveedor LEFT JOIN 
                                TarifaMaquinaria ON Equipos.idEquipo = TarifaMaquinaria.idEquipo LEFT JOIN 
                                Tiempos_cargadores_actividad ON horometro.id_registro = Tiempos_cargadores_actividad.idRegistro
                                AND horometro.idSubActividad = Tiempos_cargadores_actividad.idSubActividad 
                                AND horometro.idClasificacion = Tiempos_cargadores_actividad.idClasificacion LEFT JOIN
                                Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                            WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND Registro_tique_cargadores.id_patio='$acopio' 
                             AND (Registro_tique_cargadores.id_proveedor = '$empresa')  AND CAST(TarifaMaquinaria.Fecha_Hasta AS date) = '1900-01-01'
                                AND Registro_tique_cargadores.estado='3' and horometro.tipo_tarifa<>1
                            GROUP BY  horometro.idActividad, Actividades.Descripcion, 
                                      subactividades_cargadores.Descripcion, Destino.Descripcion, Equipos.Descripcion, 
                                      Equipos.Identificacion, Proveedores.Razonsocial, TarifaMaquinaria.Tarifa_Horometro,
                                      Clasificacion.Descripcion
                            ORDER BY Destino.Descripcion, Equipos.Descripcion, Actividades.Descripcion, subactividades_cargadores.Descripcion";
        }elseif($acopio <>"0" && $proveedor=="0" && $clasificacion<>"0"){
             $sql_consulta = "SELECT Actividades.Descripcion AS Actividad, 
                            subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, SUM(horometro.total_horas) AS total_horas,
                            Equipos.Descripcion AS Maquina, Equipos.Identificacion AS Prefijo, Proveedores.Razonsocial AS Proveedor, 
                            TarifaMaquinaria.Tarifa_Horometro, SUM(Tiempos_cargadores_actividad.TM_total) AS TM_total, 
                            Clasificacion.Descripcion AS Clasificacion
                            FROM horometro INNER JOIN
                                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN 
                                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                Proveedores ON Equipos.idPropietario=Proveedores.idProveedor LEFT JOIN 
                                TarifaMaquinaria ON Equipos.idEquipo = TarifaMaquinaria.idEquipo LEFT JOIN 
                                Tiempos_cargadores_actividad ON horometro.id_registro = Tiempos_cargadores_actividad.idRegistro
                                AND horometro.idSubActividad = Tiempos_cargadores_actividad.idSubActividad 
                                AND horometro.idClasificacion = Tiempos_cargadores_actividad.idClasificacion LEFT JOIN
                                Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                            WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                                Registro_tique_cargadores.id_patio='$acopio' and horometro.idClasificacion='$clasificacion' and (Registro_tique_cargadores.id_proveedor = '$empresa')  AND CAST(TarifaMaquinaria.Fecha_Hasta AS date) = '1900-01-01'
                                AND Registro_tique_cargadores.estado='3' and horometro.tipo_tarifa<>1
                            GROUP BY  horometro.idActividad, Actividades.Descripcion, 
                                      subactividades_cargadores.Descripcion, Destino.Descripcion, Equipos.Descripcion, 
                                      Equipos.Identificacion, Proveedores.Razonsocial, TarifaMaquinaria.Tarifa_Horometro,
                                      Clasificacion.Descripcion
                            ORDER BY Destino.Descripcion, Equipos.Descripcion, Actividades.Descripcion, subactividades_cargadores.Descripcion";
        }elseif($acopio <>"0" && $proveedor<>"0" && $clasificacion=="0"){
           $sql_consulta = "SELECT Actividades.Descripcion AS Actividad, 
                            subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, SUM(horometro.total_horas) AS total_horas,
                            Equipos.Descripcion AS Maquina, Equipos.Identificacion AS Prefijo, Proveedores.Razonsocial AS Proveedor, 
                            TarifaMaquinaria.Tarifa_Horometro, SUM(Tiempos_cargadores_actividad.TM_total) AS TM_total, 
                            Clasificacion.Descripcion AS Clasificacion
                            FROM horometro INNER JOIN
                                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN 
                                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                Proveedores ON Equipos.idPropietario=Proveedores.idProveedor LEFT JOIN 
                                TarifaMaquinaria ON Equipos.idEquipo = TarifaMaquinaria.idEquipo LEFT JOIN 
                                Tiempos_cargadores_actividad ON horometro.id_registro = Tiempos_cargadores_actividad.idRegistro
                                AND horometro.idSubActividad = Tiempos_cargadores_actividad.idSubActividad 
                                AND horometro.idClasificacion = Tiempos_cargadores_actividad.idClasificacion LEFT JOIN
                                Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                            WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                                Registro_tique_cargadores.id_patio='$acopio' and Equipos.idPropietario='$proveedor' and 
                                (Registro_tique_cargadores.id_proveedor = '$empresa')  AND CAST(TarifaMaquinaria.Fecha_Hasta AS date) = '1900-01-01'
                                AND Registro_tique_cargadores.estado='3' and horometro.tipo_tarifa<>1
                            GROUP BY  horometro.idActividad, Actividades.Descripcion, 
                                      subactividades_cargadores.Descripcion, Destino.Descripcion, Equipos.Descripcion, 
                                      Equipos.Identificacion, Proveedores.Razonsocial, TarifaMaquinaria.Tarifa_Horometro,
                                      Clasificacion.Descripcion
                            ORDER BY Destino.Descripcion, Equipos.Descripcion, Actividades.Descripcion, subactividades_cargadores.Descripcion";
        }elseif($acopio <>"0" && $proveedor<>"0" && $clasificacion<>"0"){
            $sql_consulta = "SELECT Actividades.Descripcion AS Actividad, 
                            subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, SUM(horometro.total_horas) AS total_horas,
                            Equipos.Descripcion AS Maquina, Equipos.Identificacion AS Prefijo, Proveedores.Razonsocial AS Proveedor, 
                            TarifaMaquinaria.Tarifa_Horometro, SUM(Tiempos_cargadores_actividad.TM_total) AS TM_total, 
                            Clasificacion.Descripcion AS Clasificacion
                            FROM horometro INNER JOIN
                                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN 
                                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                Proveedores ON Equipos.idPropietario=Proveedores.idProveedor LEFT JOIN 
                                TarifaMaquinaria ON Equipos.idEquipo = TarifaMaquinaria.idEquipo LEFT JOIN 
                                Tiempos_cargadores_actividad ON horometro.id_registro = Tiempos_cargadores_actividad.idRegistro
                                AND horometro.idSubActividad = Tiempos_cargadores_actividad.idSubActividad 
                                AND horometro.idClasificacion = Tiempos_cargadores_actividad.idClasificacion LEFT JOIN
                                Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                            WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                                Registro_tique_cargadores.id_patio='$acopio'  and Equipos.idPropietario='$proveedor' and 
                                horometro.idClasificacion='$clasificacion' and (Registro_tique_cargadores.id_proveedor = '$empresa')  AND CAST(TarifaMaquinaria.Fecha_Hasta AS date) = '1900-01-01'
                                AND Registro_tique_cargadores.estado='3' and horometro.tipo_tarifa<>1
                            GROUP BY  horometro.idActividad, Actividades.Descripcion, 
                                      subactividades_cargadores.Descripcion, Destino.Descripcion, Equipos.Descripcion, 
                                      Equipos.Identificacion, Proveedores.Razonsocial, TarifaMaquinaria.Tarifa_Horometro,
                                      Clasificacion.Descripcion
                            ORDER BY Destino.Descripcion, Equipos.Descripcion, Actividades.Descripcion, subactividades_cargadores.Descripcion";
        }
    }
}
$var_acopio = 'acopio';
$count_acopio = 0;

$valor_por_acopio = 0;

$var_proveedor = 'maquina';
$count_proveedor = 0;

$var_act = 'Actividad';
$count_act = 0;

$total_corte = 0;
$total_corte_count = 0;
$res = sqlsrv_query($conn,$sql_consulta);
while($d = sqlsrv_fetch_array($res)){
/************************** ACOPIOS ******************/
    if($var_acopio == 'acopio'){
        $count_acopio++;
        $var_acopio = $d['Acopio'];
        /**********************************/
        if($var_proveedor == 'maquina'){
            $count_proveedor++;
            $var_proveedor = $d['Proveedor'];
        }elseif($var_proveedor == $d['Proveedor']){
            $count_proveedor++;
            $var_proveedor = $d['Proveedor'];
        }elseif($var_proveedor != $d['Proveedor']){
            $Array_maquina[$var_acopio][$var_proveedor] = $count_proveedor;
            $count_proveedor = 1;
            $var_proveedor = $d['Proveedor'];
        }
        $valor_por_acopio+= $d['total_horas'];
        $total_corte+= $d['total_horas'];
        $total_corte_count += $d['total_horas'];
    }elseif($var_acopio == $d['Acopio']){
        $count_acopio++;
        /**********************************/
        if($var_proveedor == 'maquina'){
            $count_proveedor++;
            $var_proveedor = $d['Proveedor'];
        }elseif($var_proveedor == $d['Proveedor']){
            $count_proveedor++;
           // $var_maquina = $d['Maquina']." - ".$d['Prefijo'];
        }elseif($var_proveedor != $d['Proveedor']){
            $count_acopio++;
            $Array_maquina[$var_acopio][$var_proveedor] = $count_proveedor;
            $count_proveedor = 1;
            $var_proveedor = $d['Proveedor'];
        }
        $valor_por_acopio+= $d['total_horas'];
        $total_corte+= $d['total_horas'];
        $total_corte_count += $d['total_horas'];
    }elseif($var_acopio != $d['Acopio']){
        $Array_total_corte[$var_acopio] = $total_corte_count;
        $Array_valores_acopio[$var_acopio] = $valor_por_acopio;
        $valor_por_acopio = 0;
        $total_corte_count = 0;        
        $count_acopio++;
        /**********************************/
       /* if($var_maquina == 'maquina'){
            $count_proveedor++;
            $var_maquina = $d['Maquina']." - ".$d['Prefijo'];
        }elseif($var_maquina == $d['Maquina']." - ".$d['Prefijo']){
            $count_proveedor++;
           // $var_maquina = $d['Maquina']." - ".$d['Prefijo'];
        }else*if($var_maquina != $d['Maquina']." - ".$d['Prefijo']){*/
            $Array_maquina[$var_acopio][$var_proveedor] = $count_proveedor;
            $count_proveedor = 1;
            $var_proveedor = $d['Proveedor'];
      //  }

        $Array_acopio[$var_acopio] = $count_acopio;
        $count_acopio = 1;
        $var_acopio = $d['Acopio'];
        $valor_por_acopio+= $d['total_horas'];
        $total_corte+= $d['total_horas'];
        $total_corte_count += $d['total_horas'];
    }
/************************** MAQUINA ******************/

/************************** ACTIVIDADES ******************/
    /*if($var_act == 'Actividad'){
        $count_act++;
        $var_act = utf8_encode($d['Actividad']);
    }elseif($var_act == utf8_encode($d['Actividad'])){
        $count_act++;
    }elseif($var_act != $d['Actividad']){
        $Array_act[$var_acopio][$var_act] = $count_act;
        $count_act = 1;
        $var_act = utf8_encode($d['Actividad']);
    }*/
}
$count_acopio++;
$Array_acopio[$var_acopio] = $count_acopio;
$Array_maquina[$var_acopio][$var_proveedor] = $count_proveedor;
$Array_act[$var_acopio][$var_act] = $count_act;
$Array_valores_acopio[$var_acopio] = $valor_por_acopio;
$Array_total_corte[$var_acopio] = $total_corte_count;
/*echo "<br><br><br><br>";
echo '<b>Array acopios: </b>'; print_r($Array_acopio); echo "<br>";echo "<br>";
echo '<b>Array maquinas: </b>'; print_r($Array_maquina); echo "<br>";echo "<br>";
echo '<b>Array actividades: </b>'; print_r($Array_act); echo "<br>";echo "<br>";
echo '<b>Array totales: </b>'; print_r($Array_total_corte); echo "<br>";echo "<br>";
//print_r($Array_maquina); echo "<br>";
echo '<b>Array total acopio: </b>'; print_r($Array_valores_acopio); echo "<br>";echo "<br>";*/
?>
<!DOCTYPE html>
<html>
    <head>
        <title>RESUMEN CARGADOR JEFE ACOPIO</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="../librerias/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../librerias/alertifyjs/css/alertify.css">
        <link rel="stylesheet" type="text/css" href="../librerias/alertifyjs/css/themes/default.css">

        <script src="../librerias/jquery-3.3.1.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../librerias/estilos.css">
        <script src="../librerias/bootstrap/js/bootstrap.js"></script>
        <script src="../librerias/alertifyjs/alertify.js"></script>
    </head>
<body>
    <?php include('./header.php'); ?>
    <div class="container">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="../vista/Admin.php"><i class=" fa fa-home fa-sm"></i>Inicio</a></li>
            <li><a href="../vista/consultas.php"><i class=" fa fa-home fa-sm"></i>Consultas</a></li>
            <li><span class="active">Informe Tiques</span></li>
        </ul>
        <div class="row">
            <div class="col-sm-12">
                <form method="post">
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Cliente:</label>
                            <?php 
                            if($_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){
                                $empresas = $_SESSION['Array_empresa']['PATIO_CARGADORES'];    
                            }elseif($_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO'){
                                $empresas = $_SESSION['Array_empresa']['AUXILIAR_PATIO'];
                            }elseif($_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA'){
                                $empresas = $_SESSION['Array_empresa']['CONSULTAS_OFICINA'];
                            }elseif($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES'){
                                $empresas = $_SESSION['Array_empresa']['ADMIN_CARGADORES'];
                            }
                            $empresas = implode("','", $empresas);
                            ?>
                            <select id="empresa"  name="empresa" class="form-control">
                                <option value="">--- Seleccione ---</option>
                                <?php 
                                $sql = "SELECT * FROM Proveedores WHERE idProveedor in (SELECT idProveedor FROM  vProveedoresInAgrupacion WHERE Alias='CC') and idProveedor in ('$empresas') ORDER BY NombreCorto";
                                $result = sqlsrv_query($conn,$sql);
                                while($cliente = sqlsrv_fetch_array($result)){
                                    ?><option value="<?php echo $cliente['idProveedor']; ?>" <?php if(isset($_POST['empresa'])){ if($_POST['empresa'] == $cliente['idProveedor']){ echo 'selected';  }} ?> ><?php echo utf8_encode($cliente['NombreCorto']); ?></option><?php
                                } ?>
                            </select>
                        </div>
                         <div class="col-sm-3">
                            <label>Patio:</label>
                            <select class="form-control" name="acopio" id="acopio">
                                <option value="0">--- Todos ---</option>
                                <?php
                                $sql_destino = "SELECT * FROM Destino WHERE OperacionCargador=1 ORDER BY Descripcion";
                                $result = sqlsrv_query($conn,$sql_destino);
                                while ($destino = sqlsrv_fetch_array($result)){
                                    ?><option value="<?php echo $destino['idDestino']; ?>" <?php if(isset($_POST['acopio'])){ if($_POST['acopio'] == $destino['idDestino']){ echo 'selected';  }} ?>><?php echo utf8_encode($destino['Descripcion']); ?></option><?php
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Proveedor:</label>
                            <select class="form-control" id="proveedor" name="proveedor">
                                <option value=0>--- Todos ---</option>
                                <?php 
                                $sql = "SELECT * FROM Proveedores WHERE idProveedor in (SELECT idProveedor FROM  vProveedoresInAgrupacion WHERE Alias='PC') ORDER BY RazonSocial";
                                $res = sqlsrv_query($conn,$sql);
                                while($propietario = sqlsrv_fetch_array($res)){
                                    ?>
                                    <option value="<?php echo $propietario['idProveedor']; ?>" <?php if(isset($_POST['proveedor'])){ if($_POST['proveedor'] == $propietario['idProveedor']){ echo 'selected';  }} ?>>
                                        <?php echo utf8_encode($propietario['RazonSocial']); ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Clasificación:</label>
                             <select  class="form-control" id="clasificacion" name="clasificacion">
                                <option value=0>Todos</option>
                                <?php 
                                $sql = "SELECT DISTINCT(Clasificacion),Clasificacion.idClasificacion FROM tz_MovimientoTransporte  
                                        INNER JOIN Clasificacion ON tz_MovimientoTransporte.Clasificacion=Clasificacion.Descripcion
                                        WHERE year(FechaRegistro)>='$Year' ORDER BY Clasificacion";
                                $res = sqlsrv_query($conn,$sql);
                                while($clasificacion = sqlsrv_fetch_array($res)){
                                    ?>
                                    <option value="<?php echo $clasificacion['idClasificacion']; ?>" <?php if(isset($_POST['clasificacion'])){ if($_POST['clasificacion'] == $clasificacion['idClasificacion']){ echo 'selected';  }} ?>><?php echo utf8_encode($clasificacion['Clasificacion']); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>                                         
                    </div>
                      <br>
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Fecha Inicio:</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php if(isset($_POST['fecha_inicio'])){ if(!empty($_POST['fecha_inicio'])){ echo $_POST['fecha_inicio'];  }} ?>">
                        </div>
                        <div class="col-sm-3">
                            <label>Fecha Fin:</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?php if(isset($_POST['fecha_fin'])){ if(!empty($_POST['fecha_fin'])){ echo $_POST['fecha_fin'];  }} ?>">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-2">
                            <button type="" class="btn btn-success" name="buscar" id="buscar">Buscar</button>
                        </div>
                        <div class="col-sm-4"></div>
                    </div>
                </form>
                <br><br>
                <?php
                $params = array();
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                $res=sqlsrv_query($conn,$sql_consulta,$params,$options);
                $rows=sqlsrv_num_rows($res);
                if ($rows > 0) 
                { //echo $sql_consulta;
                  ?>
                    <table class="table table-hover table-condensed table-bordered table-responsive table-striped">
                        <thead>
                            <th>Acopio</th>
                        <!--    <th>Maquinaria</th> -->
                            <th>Proveedor</th>
                            <th>Actividad</th>
                            <th>SubActividad</th>
                            <th>Clasificación</th>
                            <th>Horas</th>
                            <th><center>TM</center></th>
                            <th>Tarifa</th>
                            <th><center>Total</center></th>
                            <th>Rendimiento</th>
                           <!-- <th>Uso General</th>-->
                        </thead>
                         
                        <?php
                        $paso_acopio = 'acopio';
                        $valor_acopio = 0;
                        $porcentaje = 0;
                        $paso_maquina = 'maquina';
                        $paso_proveedor = 'a';
                        $valor_maquina = 0;
                        $paso_act = 'act';
                        $valor_act = 0;
                        $totales_maquina = 0;
                        $totales_acopio = 0;
                        $tm_sub_maquina=0;
                        $tm_sub_acopio=0; 
                        //$res = sqlsrv_query($conn,$sql_consulta);
                        while($data = sqlsrv_fetch_array($res)){
                            if($paso_acopio == 'acopio'){   ?>
                            <tr>
                                <td style="vertical-align: middle;" rowspan="<?php echo $Array_acopio[$data['Acopio']]; ?>"><center><?php echo $data['Acopio']; ?></center></td>
                            <!--    <td style="vertical-align: middle;" rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>"><center><?php echo $data['Maquina']." - ".$data['Prefijo']; ?></center></td>-->
                                <td style="vertical-align: middle;" rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Proveedor']]; ?>"><center><?php echo utf8_encode($data['Proveedor']); ?></center></td>
                                <td><center><?php echo utf8_encode($data['Actividad']); ?></center></td>
                                <td><center><?php echo utf8_encode($data['SubActividad']); ?></center></td>
                                <td><?php echo utf8_encode($data['Clasificacion']); ?></td>
                                <td><center><?php echo $data['total_horas']; ?></center></td>
                                <td align="right"><?php echo number_format($data['TM_total'],2,',','.'); ?></td>
                                <td style="vertical-align: middle;" rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Proveedor']]; ?>"><center><?php echo number_format($data['Tarifa_Horometro'],0,',','.'); ?></center></td>
                                <td align="right"><?php $resul = $data['Tarifa_Horometro'] * $data['total_horas']; echo number_format($resul,0,',','.'); ?></td>
                                <td align="right"><?php echo number_format(($data['TM_total']/$data['total_horas']),2,',','.'); ?></td>
                             <!--   <td style="vertical-align: middle;" rowspan="<?php echo $Array_acopio[$data['Acopio']] ?>"><center><b><?php echo number_format(($Array_total_corte[$data['Acopio']]/$total_corte)*100,0,',','.')."%"; ?></b></center></td>-->
                            </tr>
                                <?php
                                $totales_maquina += $resul;
                                $totales_acopio += $resul; 
                                $porcentaje += ($data['TM_total']/$data['total_horas']);
                                $paso_acopio = $data['Acopio'];
                                $paso_maquina = $data['Maquina']." - ".$data['Prefijo'];
                                $paso_act = $data['Actividad'];
                                $paso_proveedor=$data['Proveedor'];
                                $valor_maquina += $data['total_horas'];
                                $valor_acopio += $data['total_horas'];
                                $tm_sub_maquina+=$data['TM_total'];
                                $tm_sub_acopio+=$data['TM_total'];
                            }elseif($paso_acopio == $data['Acopio']){
                                if($paso_proveedor == $data['Proveedor']){   ?>
                                 <tr>   
                                    <td><center><?php echo utf8_encode($data['Actividad']); ?></center></td>
                                    <td><center><?php echo utf8_encode($data['SubActividad']); ?></center></td>
                                    <td><?php echo utf8_encode($data['Clasificacion']); ?></td>
                                    <td><center><?php echo $data['total_horas']; ?></center></td>
                                    <td align="right"><?php echo number_format($data['TM_total'],2,',','.'); ?></td>
                                    <td align="right"><?php $resul = $data['Tarifa_Horometro'] * $data['total_horas']; echo number_format($resul,0,',','.'); ?></td>
                                    <td align="right"><?php $uso = $Array_valores_acopio[$data['Acopio']]; echo number_format(($data['TM_total']/$data['total_horas']),2,',','.'); ?></td>
                                </tr>
                                <?php
                                   $totales_maquina += $resul;
                                   $totales_acopio += $resul; 
                                   $porcentaje += ($data['TM_total']/$data['total_horas']);
                                   $valor_maquina += $data['total_horas'];
                                   $valor_acopio += $data['total_horas'];
                                   $tm_sub_maquina+=$data['TM_total'];
                                   $tm_sub_acopio+=$data['TM_total'];
                                }elseif($paso_proveedor != $data['Proveedor']){  ?>
                                 <tr style="background-color: powderblue;">
                                     <td  colspan="4"><center><b>SUBTOTAL : <?php echo $paso_proveedor; ?></b></center></td>
                                     <td><center><b><?php echo $valor_maquina; ?></b></center></td>
                                     <td align="right"><b><?php echo number_format($tm_sub_maquina,2,',','.'); ?></b></td>
                                     <td></td>                                     
                                     <td align="right"><b><?php echo number_format($totales_maquina,0,',','.'); ?></b></td>
                                     <td align="right"><b><?php //echo number_format($porcentaje,2,',','.'); ?></b></td>
                                 </tr>
                                 <tr>
                                  <!--   <td style="vertical-align: middle;" rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>"><center><?php echo $data['Maquina']." - ".$data['Prefijo']; ?></center></td>-->
                                     <td style="vertical-align: middle;" rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Proveedor']]; ?>"><center><?php echo utf8_encode($data['Proveedor']); ?></center></td>
                                     <td><center><?php echo utf8_encode($data['Actividad']); ?></center></td>
                                     <td><center><?php echo utf8_encode($data['SubActividad']); ?></center></td>
                                     <td><?php echo utf8_encode($data['Clasificacion']); ?></td>
                                     <td><center><?php echo $data['total_horas']; ?></center></td>
                                     <td align="right"><?php echo number_format($data['TM_total'],2,',','.'); ?></td>
                                     <td style="vertical-align: middle;" rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Proveedor']]; ?>"><center><?php echo number_format($data['Tarifa_Horometro'],0,',','.'); ?></center></td>
                                     <td align="right"><?php $resul = $data['Tarifa_Horometro'] * $data['total_horas']; echo number_format($resul,0,',','.'); ?></td>
                                     <td align="right"><?php $uso = $Array_valores_acopio[$data['Acopio']]; echo number_format(($data['TM_total']/$data['total_horas']),2,',','.'); ?></td>
                                 </tr>
                                    <?php
                                    $totales_maquina = 0;
                                    $porcentaje = 0;
                                    $valor_acopio += $data['total_horas'];
                                    $valor_maquina = 0;
                                    //$paso_maquina = $data['Maquina']." - ".$data['Prefijo'];
                                    $paso_acopio = $data['Acopio'];
                                    $paso_maquina = $data['Maquina']." - ".$data['Prefijo'];
                                    $paso_act = $data['Actividad'];
                                    $valor_maquina += $data['total_horas'];
                                    $porcentaje += ($data['TM_total']/$data['total_horas']);
                                    $totales_maquina += $resul;
                                    $totales_acopio += $resul;
                                    $tm_sub_maquina= $data['TM_total'];
                                    $tm_sub_acopio+=$data['TM_total'];
                                    $paso_proveedor=$data['Proveedor'];
                                }
                            }elseif($paso_acopio != $data['Acopio']){   ?>
                            <tr style="background-color: powderblue;">
                                <td colspan="4"><center><b>SUBTOTAL: <?php echo $paso_proveedor; ?></b></center></td>
                                <td><center><b><?php echo $valor_maquina; ?></b></center></td>
                                <td align="right"><b><?php echo number_format($tm_sub_acopio,2,',','.'); ?></b></td>
                                <td></td>
                                <td align="right"><b><?php echo number_format($totales_maquina,0,',','.'); ?></b></td>
                                <td align="right"><b><?php //echo number_format($porcentaje,2,',','.'); ?></b></td>
                            </tr>
                            <tr style="background-color: yellow;">
                                <td colspan="5"><center><b>SUBTOTAL ACOPIO: <?php echo $paso_acopio; ?></b></center></td>
                                <td><center><b><?php echo $valor_acopio; ?></b></center></td>
                                <td align="right"><b><?php echo number_format($tm_sub_acopio,2,',','.'); ?></b></td>
                                <td></td>
                                <td align="right"><b><?php echo number_format($totales_acopio,0,',','.'); ?></b></td>
                                <td align="right"><b></b></td>
                            </tr>
                            <tr style="background-color: white; border:0px;">
                                <td colspan="10">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;" rowspan="<?php echo $Array_acopio[$data['Acopio']] ?>"><center><?php echo $data['Acopio']; ?></center></td>
                              <!--  <td style="vertical-align: middle;" rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>"><center><?php echo $data['Maquina']." - ".$data['Prefijo']; ?></center></td>-->
                                <td style="vertical-align: middle;" rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Proveedor']]; ?>"><center><?php echo utf8_encode($data['Proveedor']); ?></center></td>
                                <td><center><?php echo utf8_encode($data['Actividad']); ?></center></td>
                                <td><center><?php echo utf8_encode($data['SubActividad']); ?></center></td>
                                <td><?php echo utf8_encode($data['Clasificacion']); ?></td>
                                <td><center><?php echo $data['total_horas']; ?></center></td>
                                <td align="right"><?php echo number_format($data['TM_total'],2,',','.'); ?></td>
                                <td style="vertical-align: middle;" rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Proveedor']]; ?>"><center><?php echo number_format($data['Tarifa_Horometro'],0,',','.'); ?></center></td>
                                <td align="right"><?php $resul = $data['Tarifa_Horometro'] * $data['total_horas']; echo number_format($resul,0,',','.'); ?></td>
                                <td align="right"><?php $uso = $Array_valores_acopio[$data['Acopio']]; echo number_format(($data['TM_total']/$data['total_horas']),2,',','.'); ?></td>
                              <!--  <td style="vertical-align: middle;" rowspan="<?php echo $Array_acopio[$data['Acopio']] ?>"><center><b><?php echo number_format(($Array_total_corte[$data['Acopio']]/$total_corte)*100,0,',','.')."%"; ?></center></td>-->
                                <?php
                                $totales_maquina = 0;
                                $totales_acopio = 0;
                                $porcentaje = 0;
                                $valor_maquina = 0;
                                $valor_acopio = 0;
                                $paso_acopio = $data['Acopio'];
                                $paso_maquina = $data['Maquina']." - ".$data['Prefijo'];
                                $paso_act = $data['Actividad'];
                                $valor_maquina += $data['total_horas'];
                                $valor_acopio += $data['total_horas'];
                                $porcentaje += ($data['TM_total']/$data['total_horas']);
                                $totales_maquina += $resul;
                                $totales_acopio += $resul; 
                                $tm_sub_maquina=$data['TM_total'];
                                $tm_sub_acopio=$data['TM_total'];
                                $paso_proveedor=$data['Proveedor'];
                            }
                        }  //cierre while ?>
                        <tr style="background-color: powderblue;">
                            <td colspan="4"><center><b>SUBTOTAL: <?php echo $paso_proveedor; ?></b></center></td>
                            <td><center><b><?php echo $valor_maquina; ?></b></center></td>
                            <td align="right"><b><?php echo number_format($tm_sub_maquina,2,',','.'); ?></b></td>
                            <td></td>
                            <td align="right"><b><?php echo number_format($totales_maquina,0,',','.'); ?></b></td>
                            <td align="right"><b><?php //echo number_format($porcentaje,2,',','.'); ?></b></td>
                        </tr>
                        <tr style="background-color: yellow;">
                            <td colspan="5"><center><b>SUBTOTAL ACOPIO: <?php echo $paso_acopio; ?></b></center></td>
                            <td><center><b><?php echo $valor_acopio; ?></b></center></td>
                            <td align="right"><b><?php echo number_format($tm_sub_acopio,2,',','.'); ?></b></td>
                            <td></td>
                            <td align="right"><b><?php echo number_format($totales_acopio,0,',','.'); ?></b></td>
                            <td align="right"><b></b></td>
                        </tr>
                    </table>
                <?php
                unset($Array_maquina);
                unset($Array_acopio);
                unset($Array_valores_acopio);
                unset($Array_total_corte);
                }?>
            </div>
        </div>
    </div>
</body>
</html>