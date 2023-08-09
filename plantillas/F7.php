<?php
session_start();
include('../modelo/conexion.php');
if ($_SESSION["logueado"] == TRUE && ($_SESSION["permisoIngresar"] == 'CONSULTAS_OFICINA' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES' || $_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO')){
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
        //
        $empresa = $_POST['empresa'];
        $operacion = $_POST['operacion'];
        $tipo_maquinaria = $_POST['tipo_maquinaria'];
        $equipo = $_POST['Equipo'];
        $acopio = $_POST['acopio'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        //
        if($_POST['fecha_inicio']>$_POST['fecha_fin'])
        {   echo '<script type="text/javascript"> alert("Fecha Inicial Mayor que Fecha Final");</script>';
        }elseif($equipo == "0" && $acopio == "0"){
            $sql_consulta = "SELECT Equipos_1.Descripcion AS Equipo, Equipos_1.Identificacion AS Equipo_identity, 
                Clasificacion.Descripcion AS Clasificacion, Destino.Descripcion AS Acopio,DestinoZonas.Zona,Pila_informes.descripcion AS Pila, 
                sum(Registro_tique_cargadores.horas_trabajadas) AS horas_trabajadas, sum(horometro.total_horas) AS total_horas, 
                Actividades.Descripcion AS Actividades, subactividades_cargadores.Descripcion AS SubActividades,
                sum(tiempos_cargadores_actividad.cantidad) AS paladas, sum(tiempos_cargadores_actividad.TM_total) AS TM_total
            FROM Pila_informes INNER JOIN
                tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
                Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
                DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
                horometro INNER JOIN
                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
                    tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
                Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
            WHERE (CAST( Registro_tique_cargadores.fecha_apertura_tique AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (Registro_tique_cargadores.id_proveedor = '$empresa') AND 
                (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'ALIMENTAR')
                and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '$tipo_maquinaria') and tiempos_cargadores_actividad.tipo_tarifa<>1 and horometro.tipo_tarifa<>1
            GROUP BY Actividades.Descripcion, subactividades_cargadores.Descripcion,
                    Registro_tique_cargadores.servicio_clasificacion, Destino.Descripcion,
                    Clasificacion.Descripcion, DestinoZonas.Zona, Equipos_1.Descripcion, Equipos_1.Identificacion, Pila_informes.descripcion
            ORDER BY Destino.Descripcion, Equipos_1.Descripcion, Actividades.Descripcion, Clasificacion.Descripcion";
        }elseif($equipo == "0" && $acopio != "0"){
            $sql_consulta = "SELECT Equipos_1.Descripcion AS Equipo, Equipos_1.Identificacion AS Equipo_identity, 
                Clasificacion.Descripcion AS Clasificacion, Destino.Descripcion AS Acopio,DestinoZonas.Zona,Pila_informes.descripcion AS Pila, 
                sum(Registro_tique_cargadores.horas_trabajadas) AS horas_trabajadas, sum(horometro.total_horas) AS total_horas, 
                Actividades.Descripcion AS Actividades, subactividades_cargadores.Descripcion AS SubActividades,
                sum(tiempos_cargadores_actividad.cantidad) AS paladas, sum(tiempos_cargadores_actividad.TM_total) AS TM_total
            FROM Pila_informes INNER JOIN
                tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
                Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
                DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
                horometro INNER JOIN
                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
                    tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
                Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
            WHERE (CAST( Registro_tique_cargadores.fecha_apertura_tique AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                (Registro_tique_cargadores.id_proveedor = '$empresa') AND (Registro_tique_cargadores.estado = '3') AND 
                (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'ALIMENTAR')
                AND tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '$tipo_maquinaria') and tiempos_cargadores_actividad.tipo_tarifa<>1 and horometro.tipo_tarifa<>1
                AND Registro_tique_cargadores.id_patio='$acopio'
            GROUP BY Actividades.Descripcion, subactividades_cargadores.Descripcion,
                    Registro_tique_cargadores.servicio_clasificacion, Destino.Descripcion,
                    Clasificacion.Descripcion, DestinoZonas.Zona, Equipos_1.Descripcion, Equipos_1.Identificacion, Pila_informes.descripcion
            ORDER BY Destino.Descripcion, Equipos_1.Descripcion, Actividades.Descripcion, Clasificacion.Descripcion";

        }elseif($equipo != "0" && $acopio == "0"){
            $sql_consulta = "SELECT Equipos_1.Descripcion AS Equipo, Equipos_1.Identificacion AS Equipo_identity, 
                Clasificacion.Descripcion AS Clasificacion, Destino.Descripcion AS Acopio,DestinoZonas.Zona,Pila_informes.descripcion AS Pila, 
                sum(Registro_tique_cargadores.horas_trabajadas) AS horas_trabajadas, sum(horometro.total_horas) AS total_horas, 
                Actividades.Descripcion AS Actividades, subactividades_cargadores.Descripcion AS SubActividades,
                sum(tiempos_cargadores_actividad.cantidad) AS paladas, sum(tiempos_cargadores_actividad.TM_total) AS TM_total
            FROM Pila_informes INNER JOIN
                tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
                Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
                DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
                horometro INNER JOIN
                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
                    tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
                Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
            WHERE (CAST( Registro_tique_cargadores.fecha_apertura_tique AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (Registro_tique_cargadores.id_proveedor = '$empresa') AND 
                (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'ALIMENTAR')
                AND tiempos_cargadores_actividad.idEquipo = '$equipo' and tiempos_cargadores_actividad.tipo_tarifa<>1 and horometro.tipo_tarifa<>1
            GROUP BY Actividades.Descripcion, subactividades_cargadores.Descripcion,
                    Registro_tique_cargadores.servicio_clasificacion, Destino.Descripcion,
                    Clasificacion.Descripcion, DestinoZonas.Zona, Equipos_1.Descripcion, Equipos_1.Identificacion, Pila_informes.descripcion
            ORDER BY Destino.Descripcion, Equipos_1.Descripcion, Actividades.Descripcion, Clasificacion.Descripcion";
        }elseif($equipo != "0" && $acopio != "0"){
            $sql_consulta = "SELECT Equipos_1.Descripcion AS Equipo, Equipos_1.Identificacion AS Equipo_identity, 
                Clasificacion.Descripcion AS Clasificacion, Destino.Descripcion AS Acopio,DestinoZonas.Zona,Pila_informes.descripcion AS Pila, 
                sum(Registro_tique_cargadores.horas_trabajadas) AS horas_trabajadas, sum(horometro.total_horas) AS total_horas, 
                Actividades.Descripcion AS Actividades, subactividades_cargadores.Descripcion AS SubActividades,
                sum(tiempos_cargadores_actividad.cantidad) AS paladas, sum(tiempos_cargadores_actividad.TM_total) AS TM_total
            FROM Pila_informes INNER JOIN
                tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
                Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
                DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
                horometro INNER JOIN
                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
                    tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
                Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
            WHERE (CAST( Registro_tique_cargadores.fecha_apertura_tique AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (Registro_tique_cargadores.id_proveedor = '$empresa') AND 
                (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'ALIMENTAR')
                AND tiempos_cargadores_actividad.idEquipo = '$equipo' AND Registro_tique_cargadores.id_patio='$acopio' and tiempos_cargadores_actividad.tipo_tarifa<>1 and horometro.tipo_tarifa<>1
            GROUP BY Actividades.Descripcion, subactividades_cargadores.Descripcion,
                    Registro_tique_cargadores.servicio_clasificacion, Destino.Descripcion,
                    Clasificacion.Descripcion, DestinoZonas.Zona, Equipos_1.Descripcion, Equipos_1.Identificacion, Pila_informes.descripcion
            ORDER BY Destino.Descripcion, Equipos_1.Descripcion, Actividades.Descripcion, Clasificacion.Descripcion";
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <REPORTE CARGADORES></REPORTE CARGADORES> 
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
    <?php //echo $sql_consulta; ?>
    <div class="container-fluid">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="../vista/Admin.php"><i class=" fa fa-home fa-sm"></i>Inicio</a></li>
            <li><a href="../vista/consultas.php"><i class=" fa fa-home fa-sm"></i>Consultas</a></li>
            <li><span class="active">Resumen Clasificación</span></li>
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
                                <option value="">---------------------</option>
                                <?php 
                                $sql = "SELECT * FROM Proveedores WHERE idProveedor in (SELECT idProveedor FROM  vProveedoresInAgrupacion WHERE Alias='CC') and idProveedor in ('$empresas') ORDER BY NombreCorto";
                                $result = sqlsrv_query($conn,$sql);
                                while($cliente = sqlsrv_fetch_array($result)){
                                    ?><option value="<?php echo $cliente['idProveedor']; ?>" <?php if(isset($_POST['empresa'])){ if($_POST['empresa'] == $cliente['idProveedor']){ echo 'selected';  }} ?> ><?php echo utf8_encode($cliente['NombreCorto']); ?></option><?php
                                } ?>
                            </select>
                        </div>
                         <div class="col-sm-3">
                            <label>Tipo Operación:</label>
                            <select class="form-control" name="operacion" id="operacion">
                                <option value="">---------------------</option>
                                <option value="1" <?php if(isset($_POST['operacion'])){ if($_POST['operacion'] == 1){ echo 'selected';  }} ?>>SERVICIO CLASIFICACIÓN</option>
                                <option value="0" <?php if(isset($_POST['operacion'])){ if($_POST['operacion'] == 0){ echo 'selected';  }} ?>>CARBÓN</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Tipo Maquinaria:</label>
                            <select class="form-control" name="tipo_maquinaria" id="tipo_maquinaria" onchange="llenar_select()">
                                <option value="">---------------------</option>
                                <?php 
                                $sql = "SELECT * FROM EquiposGrupos WHERE Descripcion!='Cargador'";
                                $result = sqlsrv_query($conn,$sql);
                                while ($grupo = sqlsrv_fetch_array($result)) {
                                    ?>
                                    <option value="<?php echo $grupo['idGrupo']; ?>" <?php if(isset($_POST['tipo_maquinaria'])){ if($_POST['tipo_maquinaria'] == $grupo['idGrupo']){ echo 'selected';  }} ?>><?php echo strtoupper(utf8_encode($grupo['Descripcion'])); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Maquinaria:</label>
                            <input type="hidden" id="selected" name="selected" value="<?php if(isset($_POST['Equipo'])){ if($_POST['Equipo'] != '0'){  echo $_POST['Equipo'];  }else{ echo 0;} } ?>">
                            <select class="form-control" name="Equipo" id="Equipo"></select>
                        </div>                                         
                    </div>
                      <br>
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Patio:</label>
                            <select class="form-control" name="acopio" id="acopio">
                                <option value="0">TODOS</option>
                                <?php
                                $sql_destino = "SELECT * FROM Destino WHERE OperacionCargador=1 ORDER BY Descripcion";
                                $result = sqlsrv_query($conn,$sql_destino);
                                while ($destino = sqlsrv_fetch_array($result)){
                                    ?><option value="<?php echo $destino['idDestino']; ?>" <?php if(isset($_POST['acopio'])){ if($_POST['acopio'] == $destino['idDestino']){ echo 'selected';  }} ?>><?php echo utf8_encode($destino['Descripcion']); ?></option><?php
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Fecha Inicio:</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php if(isset($_POST['fecha_inicio'])){ if(!empty($_POST['fecha_inicio'])){ echo $_POST['fecha_inicio'];  }} ?>" required>
                        </div>
                        <div class="col-sm-3">
                            <label>Fecha Fin:</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?php if(isset($_POST['fecha_fin'])){ if(!empty($_POST['fecha_fin'])){ echo $_POST['fecha_fin'];  }} ?>" required>
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
            </div>
        </div>
        <?php 
        if (isset($_POST['buscar'])){
            $clase_equipo = $_POST['tipo_maquinaria'];
            $proveedor = $_POST['empresa'];
            ?>
            <a href="F5_productos - excel.php?empresa=<?php echo $_POST['empresa']; ?>&operacion=<?php echo $_POST['operacion']; ?>&tipo_maquinaria=<?php echo $_POST['tipo_maquinaria']; ?>&equipo=<?php echo $_POST['Equipo']; ?>&acopio=<?php echo $_POST['acopio']; ?>&fecha_inicio=<?php echo $_POST['fecha_inicio']; ?>&fecha_fin=<?php echo $_POST['fecha_fin']; ?>"><img src="../Imagenes/excel.png" width="25" height="25"></a>
            <?php
        }
        ?>
        <!--   -->
        <?php
        $patio = "patio";
        $num = 0;
        $num_rendimiento = 0;
        $position = 0;
        $position1 = 0;
        $paso = 0;
        //echo $sql_consulta;
        $r = sqlsrv_query($conn,utf8_decode($sql_consulta));
        while($clasif = sqlsrv_fetch_array($r)){
            //echo $clasif['Pila'];
            if($patio == "patio"){
                $patio = $clasif['Acopio'];
                ?>
                <h3 style="color: red;"><?php echo $patio; ?></h3>
                <?php 
                $sql = "SELECT  Clasificacion.Descripcion AS Clasificacion
                FROM            Pila_informes INNER JOIN
                                         tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
                                         Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
                                         DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
                                         horometro INNER JOIN
                                         Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                         subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                         Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                         Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                                         Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                         Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
                                         tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
                                         Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                WHERE        (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (Registro_tique_cargadores.id_proveedor = '$proveedor') AND 
                                         (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'APILAR')
                                         and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '$clase_equipo')
                                         AND Destino.Descripcion='".$clasif['Acopio']."' AND Pila_informes.descripcion='".utf8_encode($clasif['Pila'])."' AND Equipos_1.Descripcion='".$clasif['Equipo']."' 
                                         AND Equipos_1.Identificacion='".$clasif['Equipo_identity']."' and tiempos_cargadores_actividad.tipo_tarifa<>1 and horometro.tipo_tarifa<>1
                GROUP BY  Clasificacion.Descripcion
                ORDER BY Clasificacion.Descripcion";
                $result = sqlsrv_query($conn,utf8_decode($sql));
                while($prod = sqlsrv_fetch_array($result)){
                    $Array_clasif[$num] = $prod['Clasificacion'];
                    $num++;
                }
                $count = count($Array_clasif);
                ?>
                <table class="table table-hover table-condensed table-bordered table-responsive table-striped">
                    <tr>
                        <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;"><center>EQUIPO</center></th>
                        <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;"><center>MATERIAL PROCESADO</center></th>
                        <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;"><center>PATIO</center></th>
                        <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;"><center>ZONA</center></th>
                        <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;"><center>PILA</center></th>
                        <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;"><center>HORAS CARGADOR</center></th>
                        <th colspan="3" style="vertical-align: middle; text-align: center; background-color: #92D050; text-align: center;">ALIMENTACION</th>
                        <th colspan="<?php echo $count; ?>" style="vertical-align: middle; text-align: center; background-color: #8CB5E2; text-align: center;">PRODUCTOS OBTENIDOS <span style="color: red;">TM</span></th>
                        <th colspan="<?php echo $count+2; ?>" style="vertical-align: middle; text-align: center; background-color: #FFC000; text-align: center;">RENDIMIENTOS</th>
                    </tr>
                    <tr>
                        <th style="background-color: #EBF1DE; vertical-align: middle; text-align: center;"><center>HORAS CLAS</center></th>
                        <th style="background-color: #EBF1DE; vertical-align: middle; text-align: center;"><center>PALADAS</center></th>
                        <th style="background-color: #EBF1DE; vertical-align: middle; text-align: center;"><center>TM</center></th>
                        <?php 
                        /*$sql = "SELECT  Clasificacion.Descripcion AS Clasificacion
                        FROM            Pila_informes INNER JOIN
                                                 tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
                                                 Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
                                                 DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
                                                 horometro INNER JOIN
                                                 Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                                 subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                                 Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                                                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                                 Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
                                                 tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
                                                 Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                        WHERE        (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (Registro_tique_cargadores.id_proveedor = '24B7153E-AB4C-4DB7-81BD-67BC87AF014C') AND 
                                                 (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'APILAR')
                                                 and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '569D8AD0-A401-4AFE-BD52-A91974C7D2B0')
                                                 AND Destino.Descripcion='".$clasif['Acopio']."' AND Pila_informes.descripcion='".utf8_encode($clasif['Pila'])."' AND Equipos_1.Descripcion='".$clasif['Equipo']."' 
                                                 AND Equipos_1.Identificacion='".$clasif['Equipo_identity']."'
                        GROUP BY  Clasificacion.Descripcion
                        ORDER BY Clasificacion.Descripcion";*/
                        $result = sqlsrv_query($conn,utf8_decode($sql));
                        while($prod = sqlsrv_fetch_array($result)){
                            //$Array_clasif[$num] = $prod['Clasificacion'];
                            //$num++;
                            ?>
                            <th style="background-color: #D2DDEB; vertical-align: middle; text-align: center;"><?php echo utf8_encode($prod['Clasificacion']); ?></th>
                            <?php
                        }
                        for ($i=0; $i < $num; $i++) { 
                            ?>
                            <th style="background-color: #EFDE91; vertical-align: middle; text-align: center;"><?php echo utf8_encode($Array_clasif[$i]); ?></th>
                            <?php
                        }
                        $num = 0;
                        ?>
                        <th style="background-color: #EFDE91; vertical-align: middle; text-align: center;">PAL/H</th>
                        <th style="background-color: #EFDE91; vertical-align: middle; text-align: center;">TM/H</th>
                        <!--<th>horo. final</th>
                        <th>Total hrs.</th>
                        <th>alimentacion</th>
                        <th>apilar</th>
                        <th>Hrs. Cargador</th>
                        <th>Paladas Alimen.</th>
                        <th>hrs. clasif.</th>
                        <th>TM Alimen.</th>-->
                    </tr>
                    <tbody>
                    
                        <tr>
                            <td><?php echo $clasif['Equipo']." - ".$clasif['Equipo_identity']; ?></td>
                            <td><?php echo utf8_encode($clasif['Clasificacion']) ?></td>
                            <td><?php echo utf8_encode($clasif['Acopio']) ?></td>
                            <td><?php echo strtoupper(utf8_encode($clasif['Zona'])) ?></td>
                            <td><?php echo utf8_encode($clasif['Pila']) ?></td>
                            <?php 
                            $val = 0;
                            $horas_clasif = 0;
                            $sql = "SELECT  subactividades_cargadores.Descripcion AS SubActividades, SUM(horometro.total_horas) AS total_horas,
                                            SUM(tiempos_cargadores_actividad.horas_clasif) AS horas_clasif
                                    FROM Pila_informes INNER JOIN
                                 tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
                                 Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
                                 DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
                                 horometro INNER JOIN
                                 Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                 subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                 Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                 Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
                                 tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
                                 Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                            WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (Registro_tique_cargadores.id_proveedor = '$empresa') AND 
                                 (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion in ('ALIMENTAR','APILAR'))
                                 and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo != '7A975CD6-2672-430D-B29E-7149A03D9410') and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '$clase_equipo')
                            GROUP BY subactividades_cargadores.Descripcion
                            ORDER BY subactividades_cargadores.Descripcion";
                            $consul = sqlsrv_query($conn,utf8_decode($sql));
                            while($aa = sqlsrv_fetch_array($consul)){
                                $val+= $aa['total_horas'];
                                $horas_clasif+= $aa['horas_clasif'];
                            }
                            ?>
                            <!----------------------------------------------------------------------------------------------------------->

                            <td style="text-align: right;"><?php echo $val; ?></td>
                            <td style="text-align: right;"><?php echo $val; ?></td>
                            <!--<td><?php echo 'operador' ?></td>
                            <td><?php echo utf8_encode($clasif['Actividades']) ?></td>
                            <td><?php echo $clasif['SubActividades'] ?></td>
                            <td><?php echo $clasif['horometro_ini'] ?></td>
                            <td><?php echo $clasif['horometro_fin'] ?></td>
                            <td><?php echo $clasif['Cargador']." - ".$clasif['Identificacion'] ?></td>
                            <td><?php echo $clasif['Proveedor'] ?></td>-->
                            <td style="text-align: right;"><?php echo $clasif['paladas'] ?></td>
                            <td style="text-align: right;"><?php echo $clasif['TM_total'] ?></td>
                            <!--<td><?php echo $clasif['SubActividades'] ?></td>-->
                            <?php 
                            $sql = "SELECT  Clasificacion.Descripcion AS Clasificacion, sum(tiempos_cargadores_actividad.TM_total) AS TM_total
                            FROM            Pila_informes INNER JOIN
                                                     tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
                                                     Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
                                                     DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
                                                     horometro INNER JOIN
                                                     Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                                     subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                                     Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                                     Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                                                     Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                                     Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
                                                     tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
                                                     Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                            WHERE        (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (Registro_tique_cargadores.id_proveedor = '$proveedor') AND 
                                                     (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'APILAR')
                                                     and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '$clase_equipo')
                                                     AND Destino.Descripcion='".$clasif['Acopio']."' AND Pila_informes.descripcion='".utf8_encode($clasif['Pila'])."' AND Equipos_1.Descripcion='".$clasif['Equipo']."' 
                                                     AND Equipos_1.Identificacion='".$clasif['Equipo_identity']."' and tiempos_cargadores_actividad.tipo_tarifa<>1 and horometro.tipo_tarifa<>1
                            GROUP BY  Clasificacion.Descripcion
                            ORDER BY Clasificacion.Descripcion";
                            $result = sqlsrv_query($conn,utf8_decode($sql));
                            while($prod = sqlsrv_fetch_array($result)){
                                if(utf8_encode($Array_clasif[$position]) == utf8_encode($prod['Clasificacion'])){
                                    $position++; 
                                    $position1++;
                                    $Array_rendimiento[$num_rendimiento] = $prod['TM_total'];
                                    $num_rendimiento++;
                                    ?>
                                    <td style="text-align: right;"><?php echo $prod['TM_total']; ?></td>
                                    <?php
                                }else{
                                    $position+=2;
                                    $position1+=2;
                                    $Array_rendimiento[$num_rendimiento] = 0;
                                    $num_rendimiento++;
                                    ?>
                                    <td style="text-align: right;">0</td>
                                    <td style="text-align: right;"><?php echo $prod['TM_total']; ?></td>
                                    <?php
                                    $Array_rendimiento[$num_rendimiento] = $prod['TM_total'];
                                    $num_rendimiento++;
                                }
                            }
                            if($count == $position1){
                                $position=0;
                                $position1=0;
                            }elseif($count > $position1){
                                $resta = $count-$position1;
                                for ($i=0; $i < $resta; $i++) {
                                    ?>
                                    <td style="text-align: right;">0</td>
                                    <?php
                                }
                                $position=0;
                                $position1=0;
                            }
                            for ($i=0; $i < $num_rendimiento; $i++) {
                                if($Array_rendimiento[$i] <> 0){
                                    $paso++;
                                    ?>
                                    <td style="text-align: right;"><?php echo number_format(($Array_rendimiento[$i]/$clasif['TM_total'])*100,2,',','.').' %'; ?></td>
                                    <?php
                                }else{
                                    $paso++;
                                    ?>
                                    <td style="text-align: right;">0</td>
                                    <?php
                                }
                            }
                            if($count == $paso){
                                $paso=0;
                            }elseif($count > $paso){
                                $resta1 = $count-$paso;
                                for ($i=0; $i < $resta1; $i++) {
                                    ?>
                                    <td style="text-align: right;">0</td>
                                    <?php
                                }
                                $paso=0;
                            }
                            $num_rendimiento = 0;
                            ?>
                            <td style="text-align: right;"><?php echo number_format($clasif['paladas']/$clasif['total_horas'],2,',','.'); ?></td>
                            <td style="text-align: right;"><?php echo number_format($clasif['TM_total']/$clasif['total_horas'],2,',','.'); ?></td>
                        </tr>
                    </tbody>
                
        <?php
            }elseif($patio == $clasif['Acopio']){
                ?>
                <tr>
                    <td><?php echo $clasif['Equipo']." - ".$clasif['Equipo_identity']; ?></td>
                    <td><?php echo utf8_encode($clasif['Clasificacion']) ?></td>
                    <td><?php echo utf8_encode($clasif['Acopio']) ?></td>
                    <td><?php echo strtoupper(utf8_encode($clasif['Zona'])) ?></td>
                    <td><?php echo utf8_encode($clasif['Pila']) ?></td>
                    <?php 
                    $val = 0;
                    $horas_clasif = 0;
                    $sql = "SELECT  subactividades_cargadores.Descripcion AS SubActividades, SUM(horometro.total_horas) AS total_horas,
                                    SUM(tiempos_cargadores_actividad.horas_clasif) AS horas_clasif
                            FROM Pila_informes INNER JOIN
                         tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
                         Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
                         DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
                         horometro INNER JOIN
                         Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                         subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                         Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                         Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                         Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                         Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
                         tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
                         Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                    WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (Registro_tique_cargadores.id_proveedor = '$empresa') AND 
                         (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion in ('ALIMENTAR','APILAR'))
                         and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo != '7A975CD6-2672-430D-B29E-7149A03D9410') and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '$clase_equipo')
                    GROUP BY subactividades_cargadores.Descripcion
                    ORDER BY subactividades_cargadores.Descripcion";
                    $consul = sqlsrv_query($conn,utf8_decode($sql));
                    while($aa = sqlsrv_fetch_array($consul)){
                        $val+= $aa['total_horas'];
                        $horas_clasif+= $aa['horas_clasif'];
                    }
                    ?>
                    <!----------------------------------------------------------------------------------------------------------->

                    <td style="text-align: right;"><?php echo $val; ?></td>
                    <td style="text-align: right;"><?php echo $val; ?></td>
                    <!--<td><?php echo 'operador' ?></td>
                    <td><?php echo utf8_encode($clasif['Actividades']) ?></td>
                    <td><?php echo $clasif['SubActividades'] ?></td>
                    <td><?php echo $clasif['horometro_ini'] ?></td>
                    <td><?php echo $clasif['horometro_fin'] ?></td>
                    <td><?php echo $clasif['Cargador']." - ".$clasif['Identificacion'] ?></td>
                    <td><?php echo $clasif['Proveedor'] ?></td>-->
                    <td style="text-align: right;"><?php echo $clasif['paladas'] ?></td>
                    <td style="text-align: right;"><?php echo $clasif['TM_total'] ?></td>
                    <!--<td><?php echo $clasif['SubActividades'] ?></td>-->
                    <?php 
                    $sql = "SELECT  Clasificacion.Descripcion AS Clasificacion, sum(tiempos_cargadores_actividad.TM_total) AS TM_total
                    FROM            Pila_informes INNER JOIN
                                             tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
                                             Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
                                             DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
                                             horometro INNER JOIN
                                             Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                             subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                             Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                             Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                                             Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                             Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
                                             tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
                                             Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                    WHERE        (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (Registro_tique_cargadores.id_proveedor = '$proveedor') AND 
                                             (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'APILAR')
                                             and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '$clase_equipo')
                                             AND Destino.Descripcion='".$clasif['Acopio']."' AND Pila_informes.descripcion='".utf8_encode($clasif['Pila'])."' AND Equipos_1.Descripcion='".$clasif['Equipo']."' 
                                             AND Equipos_1.Identificacion='".$clasif['Equipo_identity']."' and tiempos_cargadores_actividad.tipo_tarifa<>1 and horometro.tipo_tarifa<>1
                    GROUP BY  Clasificacion.Descripcion
                    ORDER BY Clasificacion.Descripcion";
                    $result = sqlsrv_query($conn,utf8_decode($sql));
                    while($prod = sqlsrv_fetch_array($result)){
                        if(utf8_encode($Array_clasif[$position]) == utf8_encode($prod['Clasificacion'])){
                            $position++; 
                            $position1++;
                            $Array_rendimiento[$num_rendimiento] = $prod['TM_total'];
                            $num_rendimiento++;
                            ?>
                            <td style="text-align: right;"><?php echo $prod['TM_total']; ?></td>
                            <?php
                        }else{
                            $position+=2;
                            $position1+=2;
                            $Array_rendimiento[$num_rendimiento] = 0;
                            $num_rendimiento++;
                            ?>
                            <td style="text-align: right;">0</td>
                            <td style="text-align: right;"><?php echo $prod['TM_total']; ?></td>
                            <?php
                            $Array_rendimiento[$num_rendimiento] = $prod['TM_total'];
                            $num_rendimiento++;
                        }
                    }
                    if($count == $position1){
                        $position=0;
                        $position1=0;
                    }elseif($count > $position1){
                        $resta = $count-$position1;
                        for ($i=0; $i < $resta; $i++) {
                            ?>
                            <td style="text-align: right;">0</td>
                            <?php
                        }
                        $position=0;
                        $position1=0;
                    }
                    for ($i=0; $i < $num_rendimiento; $i++) {
                        if($Array_rendimiento[$i] <> 0){
                            $paso++;
                            ?>
                            <td style="text-align: right;"><?php echo number_format(($Array_rendimiento[$i]/$clasif['TM_total'])*100,2,',','.').' %'; ?></td>
                            <?php
                        }else{
                            $paso++;
                            ?>
                            <td style="text-align: right;">0</td>
                            <?php
                        }
                    }
                    if($count == $paso){
                        $paso=0;
                    }elseif($count > $paso){
                        $resta1 = $count-$paso;
                        for ($i=0; $i < $resta1; $i++) {
                            ?>
                            <td style="text-align: right;">0</td>
                            <?php
                        }
                        $paso=0;
                    }
                    $num_rendimiento = 0;
                    ?>
                    <td style="text-align: right;"><?php echo number_format($clasif['paladas']/$clasif['total_horas'],2,',','.'); ?></td>
                    <td style="text-align: right;"><?php echo number_format($clasif['TM_total']/$clasif['total_horas'],2,',','.'); ?></td>
                </tr>
                <?php
            }elseif($patio != $clasif['Acopio']){
                $patio = $clasif['Acopio'];
                ?>
                </table>
                <h3  style="color: red;"><?php echo $patio; ?></h3>
                <table class="table table-hover table-condensed table-bordered table-responsive table-striped">
                    <tr>
                        <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;"><center>EQUIPO</center></th>
                        <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;"><center>MATERIAL PROCESADO</center></th>
                        <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;"><center>PATIO</center></th>
                        <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;"><center>ZONA</center></th>
                        <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;"><center>PILA</center></th>
                        <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;"><center>HORAS CARGADOR</center></th>
                        <th colspan="3" style="vertical-align: middle; text-align: center; background-color: #92D050; text-align: center;">ALIMENTACION</th>
                        <th colspan="2" style="vertical-align: middle; text-align: center; background-color: #8CB5E2; text-align: center;">PRODUCTOS OBTENIDOS <span style="color: red;">TM</span></th>
                        <th colspan="4" style="vertical-align: middle; text-align: center; background-color: #FFC000; text-align: center;">RENDIMIENTOS</th>
                    </tr>
                    <tr>
                        <th style="background-color: #EBF1DE; vertical-align: middle; text-align: center;"><center>HORAS CLAS</center></th>
                        <th style="background-color: #EBF1DE; vertical-align: middle; text-align: center;"><center>PALADAS</center></th>
                        <th style="background-color: #EBF1DE; vertical-align: middle; text-align: center;"><center>TM</center></th>
                        <?php 
                        $sql = "SELECT  Clasificacion.Descripcion AS Clasificacion
                        FROM            Pila_informes INNER JOIN
                                                 tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
                                                 Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
                                                 DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
                                                 horometro INNER JOIN
                                                 Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                                 subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                                 Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                                                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                                 Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
                                                 tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
                                                 Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                        WHERE        (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (Registro_tique_cargadores.id_proveedor = '$proveedor') AND 
                                                 (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'APILAR')
                                                 and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '$clase_equipo')
                                                 AND Destino.Descripcion='".$clasif['Acopio']."' AND Pila_informes.descripcion='".utf8_encode($clasif['Pila'])."' AND Equipos_1.Descripcion='".$clasif['Equipo']."' 
                                                 AND Equipos_1.Identificacion='".$clasif['Equipo_identity']."' and tiempos_cargadores_actividad.tipo_tarifa<>1 and horometro.tipo_tarifa<>1
                        GROUP BY  Clasificacion.Descripcion
                        ORDER BY Clasificacion.Descripcion";
                        $result = sqlsrv_query($conn,utf8_decode($sql));
                        while($prod = sqlsrv_fetch_array($result)){
                            $Array_clasif[$num] = $prod['Clasificacion'];
                            $num++;
                            ?>
                            <th style="background-color: #D2DDEB; vertical-align: middle; text-align: center;"><?php echo utf8_encode($prod['Clasificacion']); ?></th>
                            <?php
                        }
                        for ($i=0; $i < $num; $i++) { 
                            ?>
                            <th style="background-color: #EFDE91; vertical-align: middle; text-align: center;"><?php echo utf8_encode($Array_clasif[$i]); ?></th>
                            <?php
                        }
                        $num = 0;
                        $count = count($Array_clasif);
                        ?>
                        <th style="background-color: #EFDE91; vertical-align: middle; text-align: center;">PALA/H</th>
                        <th style="background-color: #EFDE91; vertical-align: middle; text-align: center;">TM/H</th>
                        <!--<th>horo. final</th>
                        <th>Total hrs.</th>
                        <th>alimentacion</th>
                        <th>apilar</th>
                        <th>Hrs. Cargador</th>
                        <th>Paladas Alimen.</th>
                        <th>hrs. clasif.</th>
                        <th>TM Alimen.</th>-->
                    </tr>
                    <tbody>
                        <tr>
                            <td><?php echo $clasif['Equipo']." - ".$clasif['Equipo_identity']; ?></td>
                            <td><?php echo utf8_encode($clasif['Clasificacion']) ?></td>
                            <td><?php echo utf8_encode($clasif['Acopio']) ?></td>
                            <td><?php echo strtoupper(utf8_encode($clasif['Zona'])) ?></td>
                            <td><?php echo utf8_encode($clasif['Pila']) ?></td>
                            <?php 
                            $val = 0;
                            $horas_clasif = 0;
                            $sql = "SELECT  subactividades_cargadores.Descripcion AS SubActividades, SUM(horometro.total_horas) AS total_horas,
                                            SUM(tiempos_cargadores_actividad.horas_clasif) AS horas_clasif
                                    FROM Pila_informes INNER JOIN
                                 tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
                                 Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
                                 DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
                                 horometro INNER JOIN
                                 Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                 subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                 Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                 Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
                                 tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
                                 Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                            WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (Registro_tique_cargadores.id_proveedor = '$empresa') AND 
                                 (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion in ('ALIMENTAR','APILAR'))
                                 and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo != '7A975CD6-2672-430D-B29E-7149A03D9410') and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '$clase_equipo')
                            GROUP BY subactividades_cargadores.Descripcion
                            ORDER BY subactividades_cargadores.Descripcion";
                            $consul = sqlsrv_query($conn,utf8_decode($sql));
                            while($aa = sqlsrv_fetch_array($consul)){
                                $val+= $aa['total_horas'];
                                $horas_clasif+= $aa['horas_clasif'];
                            }
                            ?>
                            <!----------------------------------------------------------------------------------------------------------->

                            <td style="text-align: right;"><?php echo $val; ?></td>
                            <td style="text-align: right;"><?php echo $val; ?></td>
                            <!--<td><?php echo 'operador' ?></td>
                            <td><?php echo utf8_encode($clasif['Actividades']) ?></td>
                            <td><?php echo $clasif['SubActividades'] ?></td>
                            <td><?php echo $clasif['horometro_ini'] ?></td>
                            <td><?php echo $clasif['horometro_fin'] ?></td>
                            <td><?php echo $clasif['Cargador']." - ".$clasif['Identificacion'] ?></td>
                            <td><?php echo $clasif['Proveedor'] ?></td>-->
                            <td style="text-align: right;"><?php echo $clasif['paladas'] ?></td>
                            <td style="text-align: right;"><?php echo $clasif['TM_total'] ?></td>
                            <!--<td><?php echo $clasif['SubActividades'] ?></td>-->
                            <?php 
                            $sql = "SELECT  Clasificacion.Descripcion AS Clasificacion, sum(tiempos_cargadores_actividad.TM_total) AS TM_total
                            FROM            Pila_informes INNER JOIN
                                                     tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
                                                     Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
                                                     DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
                                                     horometro INNER JOIN
                                                     Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                                     subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                                     Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                                     Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                                                     Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                                     Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
                                                     tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
                                                     Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                            WHERE        (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (Registro_tique_cargadores.id_proveedor = '$proveedor') AND 
                                                     (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'APILAR')
                                                     and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '$clase_equipo')
                                                     AND Destino.Descripcion='".$clasif['Acopio']."' AND Pila_informes.descripcion='".utf8_encode($clasif['Pila'])."' AND Equipos_1.Descripcion='".$clasif['Equipo']."' 
                                                     AND Equipos_1.Identificacion='".$clasif['Equipo_identity']."' and tiempos_cargadores_actividad.tipo_tarifa<>1 and horometro.tipo_tarifa<>1
                            GROUP BY  Clasificacion.Descripcion
                            ORDER BY Clasificacion.Descripcion";
                            $result = sqlsrv_query($conn,utf8_decode($sql));
                            while($prod = sqlsrv_fetch_array($result)){
                                if(utf8_encode($Array_clasif[$position]) == utf8_encode($prod['Clasificacion'])){
                                    $position++; 
                                    $position1++;
                                    $Array_rendimiento[$num_rendimiento] = $prod['TM_total'];
                                    $num_rendimiento++;
                                    ?>
                                    <td style="text-align: right;"><?php echo $prod['TM_total']; ?></td>
                                    <?php
                                }else{
                                    $position+=2;
                                    $position1+=2;
                                    $Array_rendimiento[$num_rendimiento] = 0;
                                    $num_rendimiento++;
                                    ?>
                                    <td style="text-align: right;">0</td>
                                    <td style="text-align: right;"><?php echo $prod['TM_total']; ?></td>
                                    <?php
                                    $Array_rendimiento[$num_rendimiento] = $prod['TM_total'];
                                    $num_rendimiento++;
                                }
                            }
                            if($count == $position1){
                                $position=0;
                                $position1=0;
                            }elseif($count > $position1){
                                $resta = $count-$position1;
                                for ($i=0; $i < $resta; $i++) {
                                    ?>
                                    <td style="text-align: right;">0</td>
                                    <?php
                                }
                                $position=0;
                                $position1=0;
                            }
                            for ($i=0; $i < $num_rendimiento; $i++) {
                                if($Array_rendimiento[$i] <> 0){
                                    $paso++;
                                    ?>
                                    <td style="text-align: right;"><?php echo number_format(($Array_rendimiento[$i]/$clasif['TM_total'])*100,2,',','.').' %'; ?></td>
                                    <?php
                                }else{
                                    $paso++;
                                    ?>
                                    <td style="text-align: right;">0</td>
                                    <?php
                                }
                            }
                            if($count == $paso){
                                $paso=0;
                            }elseif($count > $paso){
                                $resta1 = $count-$paso;
                                for ($i=0; $i < $resta1; $i++) {
                                    ?>
                                    <td style="text-align: right;">0</td>
                                    <?php
                                }
                                $paso=0;
                            }
                            $num_rendimiento = 0;
                            ?>
                            <td style="text-align: right;"><?php echo number_format($clasif['paladas']/$clasif['total_horas'],2,',','.'); ?></td>
                            <td style="text-align: right;"><?php echo number_format($clasif['TM_total']/$clasif['total_horas'],2,',','.'); ?></td>
                        </tr>
                    </tbody>
                <?php
            }
        }
        ?>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            llenar_select();
        });
        function llenar_select(){
            var tipo_maquinaria = $('#tipo_maquinaria').val();
            var selected = $('#selected').val();
            band = 2;
            $.post("buscar.php", {tipo_maquinaria: tipo_maquinaria, seleccionado: selected, band: band}, 
            function(mensaje) {
                //console.log(mensaje);
                $('#Equipo').html(mensaje).fadeIn();
            });
        }
    </script>
</body>
</html>