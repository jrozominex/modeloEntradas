<?php
session_start();
require_once '../modelo/conexion.php';
error_reporting(0);
if ($_SESSION["logueado"] == TRUE && ($_SESSION["permisoIngresar"] == 'CONSULTAS_OFICINA' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES')){
    $usuario = $_SESSION['usuario'];
    $Fecha = date('Y-m-d');
    $Fecha_actual = date('Y-m-d',strtotime($Fecha . ' - 15 days'));
    ini_set('date.timezone', 'America/Bogota');
    $hora = date("g:i A");
    $Year = date('Y')-1;
    $sql_usuario = "SELECT * FROM Usuarios WHERE idUsuario='$usuario'";
    $result = sqlsrv_query($conn,$sql_usuario);
    while ($row = sqlsrv_fetch_array($result)){
      $Nombre = $row['NombreUsuarioLargo'];
    }
include('../vista/tipo_dispositivo.php');
if ($tablet_browser > 0) {
    // Si es tablet has lo que necesites
    //print 'es tablet';
    ?>
    <script type="text/javascript">
        self.location='Admin.php';
        alert('Accede desde un ordenador');
    </script>
    <?php
}
else if ($mobile_browser > 0) {
    // Si es dispositivo mobil has lo que necesites
    //print 'es un mobil';
    ?>
    <script type="text/javascript">
        self.location='Admin.php';
        alert('Accede desde un ordenador');
    </script>
    <?php
}
else {
    // Si es ordenador de escritorio has lo que necesites
    //print 'es un ordenador de escritorio';
}

}elseif($_SESSION['permisoIngresar'] == 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES'){
    //header("Location: MantenimientoCargadores.php");
    ?>
    <script type="text/javascript">
        self.location='MantenimientoCargadores.php';
        alert('No tienes permiso para acceder a este ambiente');
    </script>
    <?php
}elseif($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){
    //header("Location: incio.php");
    ?>
    <script type="text/javascript">
        self.location='inicio.php';
        alert('No tienes permiso para acceder a este ambiente');
    </script>
    <?php
}elseif($_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO'){ 
    //header("Location: inicio_patio.php");
    ?>
    <script type="text/javascript">
        self.location='inicio_patio.php';
        alert('No tienes permiso para acceder a este ambiente');
    </script>
    <?php
}else{
    header("Location: ../index.php");
    ?>
    <script type="text/javascript">
        self.location='../index.php';
        alert('Inicia sesión primero');
    </script>
    <?php
    die();
}
$sql_consulta = "";
if (isset($_POST['buscar'])){
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $empresa = $_POST['empresa'];
    $acopio = $_POST['acopio'];
    $producto = $_POST['producto'];
    ///////////////////////////////////////////////////////////////////////////
    $tipo_maquinaria = $_POST['tipo_maquinaria'];
    ///////////////////////////////////////////////////////////////////////////
    if($_POST['fecha_inicio']>$_POST['fecha_fin'])
    {   echo '<script type="text/javascript"> alert("Fecha Inicial Mayor que Fecha Final");</script>';
    }elseif($acopio == 1){
        if($producto == 1){
            $sql_consulta = "SELECT Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, 
                            Actividades.Descripcion as Actividades, subactividades_cargadores.Descripcion AS Subactividades, Clasificacion.Descripcion AS Clasificacion,
                            horometro.total_horas, tiempos_cargadores_actividad.cantidad, tiempos_cargadores_actividad.TMxPalada, 
                            tiempos_cargadores_actividad.tiempohora, tiempos_cargadores_actividad.tiempopromedio, tiempos_cargadores_actividad.tiempohorometro, 
                            tiempos_cargadores_actividad.TM_total, Destino.Descripcion AS Acopio, Proveedores.Razonsocial AS Proveedor
                        FROM Registro_tique_cargadores LEFT JOIN
                            horometro ON Registro_tique_cargadores.id_registro = horometro.id_registro LEFT JOIN
                            tiempos_cargadores_actividad ON horometro.idSubActividad = tiempos_cargadores_actividad.idSubActividad 
                            AND horometro.idClasificacion = tiempos_cargadores_actividad.idClasificacion 
                            AND horometro.id_registro = tiempos_cargadores_actividad.idRegistro LEFT JOIN
                            Actividades ON horometro.idActividad = Actividades.idActividad LEFT JOIN
                            subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad 
                            AND Actividades.idActividad = subactividades_cargadores.idActividad LEFT JOIN
                            Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion LEFT JOIN
                            Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino LEFT JOIN
                            Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo LEFT JOIN
                            Proveedores ON Equipos.idPropietario=Proveedores.idProveedor 
                        WHERE (Registro_tique_cargadores.id_proveedor = '$empresa') AND (Registro_tique_cargadores.estado = 3) 
                            AND (Registro_tique_cargadores.fecha_apertura_tique BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (horometro.idActividad IS NOT NULL)
                            AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'ALIMENTAR')
                         and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '569D8AD0-A401-4AFE-BD52-A91974C7D2B0') and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '$tipo_maquinaria')
                        ORDER BY Registro_tique_cargadores.cod_reporte, Actividades.Descripcion, subactividades_cargadores.Descripcion, Clasificacion.Descripcion
                        /*GROUP BY Actividades.Descripcion, subactividades_cargadores.Descripcion*/";

            //$sql_consulta = "SELECT * FROM Registro_tique_cargadores WHERE id_proveedor='$empresa' AND fecha_apertura_tique between '$fecha_inicio' AND '$fecha_fin'";
        }else{
            $sql_consulta = "SELECT Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, 
                            Actividades.Descripcion as Actividades, subactividades_cargadores.Descripcion AS Subactividades, Clasificacion.Descripcion AS Clasificacion,
                            horometro.total_horas, tiempos_cargadores_actividad.cantidad, tiempos_cargadores_actividad.TMxPalada, 
                            tiempos_cargadores_actividad.tiempohora, tiempos_cargadores_actividad.tiempopromedio, tiempos_cargadores_actividad.tiempohorometro, 
                            tiempos_cargadores_actividad.TM_total, Destino.Descripcion AS Acopio, Proveedores.Razonsocial AS Proveedor
                        FROM Registro_tique_cargadores LEFT JOIN
                            horometro ON Registro_tique_cargadores.id_registro = horometro.id_registro LEFT JOIN
                            tiempos_cargadores_actividad ON horometro.idSubActividad = tiempos_cargadores_actividad.idSubActividad 
                            AND horometro.idClasificacion = tiempos_cargadores_actividad.idClasificacion 
                            AND horometro.id_registro = tiempos_cargadores_actividad.idRegistro LEFT JOIN
                            Actividades ON horometro.idActividad = Actividades.idActividad LEFT JOIN
                            subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad 
                            AND Actividades.idActividad = subactividades_cargadores.idActividad LEFT JOIN
                            Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion LEFT JOIN
                            Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino LEFT JOIN
                            Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo LEFT JOIN
                            Proveedores ON Equipos.idPropietario=Proveedores.idProveedor 
                        WHERE (Registro_tique_cargadores.id_proveedor = '$empresa') AND horometro.idClasificacion='$producto' AND (Registro_tique_cargadores.estado = 3) 
                            AND (Registro_tique_cargadores.fecha_apertura_tique BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (horometro.idActividad IS NOT NULL)
                            AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'ALIMENTAR')
                         and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '569D8AD0-A401-4AFE-BD52-A91974C7D2B0') and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '$tipo_maquinaria')
                        ORDER BY Registro_tique_cargadores.cod_reporte, Actividades.Descripcion, subactividades_cargadores.Descripcion, Clasificacion.Descripcion";
            /*$sql_consulta = "SELECT * 
            FROM Registro_tique_cargadores 
            LEFT JOIN horometro ON Registro_tique_cargadores.id_registro = horometro.id_registro
            WHERE id_proveedor='$empresa' fecha_apertura_tique between '$fecha_inicio' AND '$fecha_fin' AND horometro.idClasificacion='$producto'";*/
        }
    }else{
        if($producto == 1){
            $sql_consulta = "SELECT Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, 
                            Actividades.Descripcion as Actividades, subactividades_cargadores.Descripcion AS Subactividades, Clasificacion.Descripcion AS Clasificacion,
                            horometro.total_horas, tiempos_cargadores_actividad.cantidad, tiempos_cargadores_actividad.TMxPalada, 
                            tiempos_cargadores_actividad.tiempohora, tiempos_cargadores_actividad.tiempopromedio, tiempos_cargadores_actividad.tiempohorometro, 
                            tiempos_cargadores_actividad.TM_total, Destino.Descripcion AS Acopio, Proveedores.Razonsocial AS Proveedor
                        FROM Registro_tique_cargadores LEFT JOIN
                            horometro ON Registro_tique_cargadores.id_registro = horometro.id_registro LEFT JOIN
                            tiempos_cargadores_actividad ON horometro.idSubActividad = tiempos_cargadores_actividad.idSubActividad 
                            AND horometro.idClasificacion = tiempos_cargadores_actividad.idClasificacion 
                            AND horometro.id_registro = tiempos_cargadores_actividad.idRegistro LEFT JOIN
                            Actividades ON horometro.idActividad = Actividades.idActividad LEFT JOIN
                            subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad 
                            AND Actividades.idActividad = subactividades_cargadores.idActividad LEFT JOIN
                            Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion LEFT JOIN
                            Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino LEFT JOIN
                            Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo LEFT JOIN
                            Proveedores ON Equipos.idPropietario=Proveedores.idProveedor 
                        WHERE (Registro_tique_cargadores.id_proveedor = '$empresa') AND Registro_tique_cargadores.id_patio='$acopio' AND (Registro_tique_cargadores.estado = 3) 
                            AND (Registro_tique_cargadores.fecha_apertura_tique BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (horometro.idActividad IS NOT NULL)
                            AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'ALIMENTAR')
                         and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '569D8AD0-A401-4AFE-BD52-A91974C7D2B0') and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '$tipo_maquinaria')
                        ORDER BY Registro_tique_cargadores.cod_reporte, Actividades.Descripcion, subactividades_cargadores.Descripcion, Clasificacion.Descripcion";
            /*$sql_consulta = "SELECT * FROM Registro_tique_cargadores 
            WHERE fecha_apertura_tique between '$fecha_inicio' AND '$fecha_fin' AND id_patio='$acopio'";*/
        }else{
            $sql_consulta = "SELECT Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, 
                            Actividades.Descripcion as Actividades, subactividades_cargadores.Descripcion AS Subactividades, Clasificacion.Descripcion AS Clasificacion,
                            horometro.total_horas, tiempos_cargadores_actividad.cantidad, tiempos_cargadores_actividad.TMxPalada, 
                            tiempos_cargadores_actividad.tiempohora, tiempos_cargadores_actividad.tiempopromedio, tiempos_cargadores_actividad.tiempohorometro, 
                            tiempos_cargadores_actividad.TM_total, Destino.Descripcion AS Acopio, Proveedores.Razonsocial AS Proveedor
                        FROM Registro_tique_cargadores LEFT JOIN
                            horometro ON Registro_tique_cargadores.id_registro = horometro.id_registro LEFT JOIN
                            tiempos_cargadores_actividad ON horometro.idSubActividad = tiempos_cargadores_actividad.idSubActividad 
                            AND horometro.idClasificacion = tiempos_cargadores_actividad.idClasificacion 
                            AND horometro.id_registro = tiempos_cargadores_actividad.idRegistro LEFT JOIN
                            Actividades ON horometro.idActividad = Actividades.idActividad LEFT JOIN
                            subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad 
                            AND Actividades.idActividad = subactividades_cargadores.idActividad LEFT JOIN
                            Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion LEFT JOIN
                            Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino LEFT JOIN
                            Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo LEFT JOIN
                            Proveedores ON Equipos.idPropietario=Proveedores.idProveedor 
                        WHERE (Registro_tique_cargadores.id_proveedor = '$empresa') AND horometro.idClasificacion='$producto' AND (Registro_tique_cargadores.estado = 3) 
                            AND (Registro_tique_cargadores.fecha_apertura_tique BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (horometro.idActividad IS NOT NULL)
                            AND Registro_tique_cargadores.id_patio='$acopio' AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'ALIMENTAR')
                         and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '569D8AD0-A401-4AFE-BD52-A91974C7D2B0') and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '$tipo_maquinaria')
                        ORDER BY Registro_tique_cargadores.cod_reporte, Actividades.Descripcion, subactividades_cargadores.Descripcion, Clasificacion.Descripcion";
            /*$sql_consulta = "SELECT * 
            FROM Registro_tique_cargadores 
            INNER JOIN horometro ON Registro_tique_cargadores.id_registro = horometro.id_registro
            WHERE id_proveedor='$empresa' AND fecha_apertura_tique between '$fecha_inicio' AND '$fecha_fin' AND horometro.idClasificacion='$producto' AND id_patio='$acopio'";*/
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php include '../vista/libreria.php'; ?>
</head>
<body>
     <?php include('Header.php'); 
     //echo $sql_consulta;
     ?>
    <div class="container-fluid">
        <center><h1>Informe SG</h1></center>
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="../vista/Admin.php"><i class=" fa fa-home fa-sm"></i>Inicio</a></li>
            <li><a href="../vista/consultas.php"><i class=" fa fa-home fa-sm"></i>Consultas</a></li>
            <li><span class="active">Resumen SG</span></li>
        </ul>
        <div class="row">
            <div class="col-sm-12">
                <br>
                <form method="post">
                    <div class="row">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-2">
                            <label>Cliente:</label>
                            <select id="empresa"  name="empresa" class="form-control">
                                <option>--- Seleccione ---</option>
                                <?php 
                                $sql_1 = "SELECT * FROM Proveedores WHERE idProveedor in (SELECT idProveedor FROM  vProveedoresInAgrupacion WHERE Alias='CC') ORDER BY NombreCorto";
                                $result = sqlsrv_query($conn,$sql_1);
                                while($cliente = sqlsrv_fetch_array($result)){
                                    ?><option value="<?php echo $cliente['idProveedor']; ?>" <?php if(isset($_POST['empresa'])){ if($_POST['empresa'] == $cliente['idProveedor']){ echo 'selected';  }} ?> ><?php echo utf8_encode($cliente['NombreCorto']); ?></option><?php
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label>Acopio:</label>
                            <select class="form-control" name="acopio" id="acopio">
                                <option value="1">TODOS</option>
                                <?php
                                $sql_destino = "SELECT * FROM Destino WHERE OperacionCargador=1 ORDER BY Descripcion";
                                $result = sqlsrv_query($conn,$sql_destino);
                                while ($destino = sqlsrv_fetch_array($result)){
                                    ?><option value="<?php echo $destino['idDestino']; ?>" <?php if(isset($_POST['acopio'])){ if($_POST['acopio'] == $destino['idDestino']){ echo 'selected';  }} ?>><?php echo utf8_encode($destino['Descripcion']); ?></option><?php
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label>Producto:</label>
                            <select id="producto" name="producto" class="form-control">
                                <option value="1">TODOS</option>
                                <?php 
                                $sql = "SELECT DISTINCT(Clasificacion),Clasificacion.idClasificacion FROM tz_MovimientoTransporte  
                                        INNER JOIN Clasificacion ON tz_MovimientoTransporte.Clasificacion=Clasificacion.Descripcion
                                        WHERE year(FechaRegistro)>='$Year' ORDER BY Clasificacion";
                                $res = sqlsrv_query($conn,$sql);
                                while($clasificacion = sqlsrv_fetch_array($res)){
                                    ?>
                                    <option value="<?php echo $clasificacion['idClasificacion']; ?>" <?php if(isset($_POST['producto'])){ if($_POST['producto'] == $clasificacion['idClasificacion']){ echo 'selected';  }} ?>><?php echo utf8_encode($clasificacion['Clasificacion']); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label>TIPO MAQUINARIA:</label>
                            <select class="form-control" name="tipo_maquinaria" id="tipo_maquinaria">
                                <option value="0">---------------------</option>
                                <?php 
                                $sql = "SELECT * FROM EquiposGrupos WHERE Descripcion!='Cargador'";
                                $result = sqlsrv_query($conn,$sql);
                                while ($grupo = sqlsrv_fetch_array($result)){
                                    ?>
                                    <option value="<?php echo $grupo['idGrupo']; ?>" <?php if(isset($_POST['tipo_maquinaria'])){ if($_POST['tipo_maquinaria'] == $grupo['idGrupo']){ echo 'selected';  }} ?>><?php echo strtoupper(utf8_encode($grupo['Descripcion'])); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label>Fecha Inicio:</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php if(isset($_POST['fecha_inicio'])){ if(!empty($_POST['fecha_inicio'])){ echo $_POST['fecha_inicio'];  }} ?>" required>
                        </div>
                        <div class="col-sm-2">
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
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <br>
                <?php
                $sql1 = "SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_usuario, Equipos_1.Descripcion AS Equipo, 
                        Equipos_1.Identificacion AS Equipo_identity, Clasificacion.Descripcion AS Clasificacion,
                        Destino.Descripcion AS Acopio,DestinoZonas.Zona,Pila_informes.descripcion AS Pila, Registro_tique_cargadores.horas_trabajadas,
                        horometro.total_horas, Actividades.Descripcion AS Actividades, Registro_tique_cargadores.horometro_ini,
                        subactividades_cargadores.Descripcion AS SubActividades, Registro_tique_cargadores.fecha_apertura_tique, 
                        Registro_tique_cargadores.horometro_fin,  Equipos.Descripcion AS Cargador, Equipos.Identificacion,
                        SUM(tiempos_cargadores_actividad.cantidad) AS paladas,/* sum(tiempos_cargadores_actividad.TMxPalada), */
                        SUM(tiempos_cargadores_actividad.TM_total) AS TM_total/*, sum(tiempos_cargadores_actividad.ajuste_TM) AS ajuste_TM*/
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
                         (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'ALIMENTAR')
                         and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo != '7A975CD6-2672-430D-B29E-7149A03D9410')
                          and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '$tipo_maquinaria')
                    GROUP BY Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_usuario, Equipos_1.Descripcion, 
                        Equipos_1.Identificacion, Clasificacion.Descripcion,
                        Destino.Descripcion ,DestinoZonas.Zona,Pila_informes.descripcion, Registro_tique_cargadores.horas_trabajadas,
                        horometro.total_horas, Actividades.Descripcion, Registro_tique_cargadores.horometro_ini,
                        subactividades_cargadores.Descripcion, Registro_tique_cargadores.fecha_apertura_tique, 
                        Registro_tique_cargadores.horometro_fin,  Equipos.Descripcion, Equipos.Identificacion
                    ORDER BY Destino.Descripcion, Equipos_1.Descripcion, Actividades.Descripcion, Clasificacion.Descripcion";
                $params = array();
                //echo $sql1;
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                $res=sqlsrv_query($conn,utf8_decode($sql1),$params,$options);
                $rows=sqlsrv_num_rows($res);
                //
                $num = 0;
                $num_rendimiento = 0;
                $position = 0;
                $position1 = 0;
                $paso = 0;
                if ($rows > 0){
                    //echo $sql_consulta;
                    ?>
                    <table class="table table-hover table-condensed table-bordered table-responsive table-striped">
                        <thead>
                            <tr>
                                <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;"><center>FECHA</center></th>
                                <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;"><center>PATIO</center></th>
                                <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;">ZONA</th>
                                <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;">PILA</th>
                                <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;">MATERIAL PROCESADO</th>
                                <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;">EQUIPO</th>
                                <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;">TIQUETE</th>
                                <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;">OPERADOR</th>
                                <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;">CARGADOR UTILIZADO</th>
                                <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;">HORO. INICIAL</th>
                                <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;">HORO. FINAL</th>
                                <th rowspan="2" style="background-color: #DDD9C4; vertical-align: middle; text-align: center;">TOTAL HRS.</th>
                                <th rowspan="2" style="background-color: #B8CCE4; vertical-align: middle; text-align: center;">ALIMENTAR</th>
                                <th rowspan="2" style="background-color: #B8CCE4; vertical-align: middle; text-align: center;">APILAR</th>
                                <th rowspan="2" style="background-color: #B8CCE4; vertical-align: middle; text-align: center;">HRS. CARGADOR</th>
                                <th colspan="3" style="vertical-align: middle; text-align: center; background-color: #92D050; text-align: center;">ALIMENTACION</th>
                                <th colspan="3" style="vertical-align: middle; text-align: center; background-color: #8CB5E2; text-align: center;">PRODUCTOS OBTENIDOS <span style="color: red;">TM</span></th>
                                <th colspan="5" style="vertical-align: middle; text-align: center; background-color: #FFC000; text-align: center;">RENDIMIENTOS</th>
                            </tr>
                            <tr>
                                <th style="background-color: #EBF1DE; vertical-align: middle; text-align: center;">PALADAS</th>
                                <th style="background-color: #EBF1DE; vertical-align: middle; text-align: center;">HRS. CLASI</th>
                                <th style="background-color: #EBF1DE; vertical-align: middle; text-align: center;">TM</th>
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
                                WHERE        (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (Registro_tique_cargadores.id_proveedor = '$empresa') AND 
                                                         (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'APILAR')
                                                         and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo != '7A975CD6-2672-430D-B29E-7149A03D9410')
                                                          and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '$tipo_maquinaria')
                                GROUP BY  Clasificacion.Descripcion
                                ORDER BY Clasificacion.Descripcion";
                                $result = sqlsrv_query($conn,utf8_decode($sql));
                                while($prod = sqlsrv_fetch_array($result)){
                                    $Array_clasif[$num] = utf8_encode($prod['Clasificacion']);
                                    $Array_position[utf8_encode($prod['Clasificacion'])] = $num;
                                    $num++;
                                    ?>
                                    <th style="width: 11%; background-color: #D2DDEB; vertical-align: middle; text-align: center;"><?php echo utf8_encode($prod['Clasificacion']); ?></th>
                                    <?php
                                }
                                ?>
                                <?php
                                for ($i=0; $i < $num; $i++){
                                    ?>
                                    <th style="width: 11%; background-color: #EFDE91; vertical-align: middle; text-align: center;"><?php echo $Array_clasif[$i]; ?></th>
                                    <?php
                                }
                                $num = 0;
                                $count = count($Array_clasif);
                                ?>
                                <th style="background-color: #EFDE91; vertical-align: middle; text-align: center;">PALA./H</th>
                                <th style="background-color: #EFDE91; vertical-align: middle; text-align: center;">TM./H</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            while ($data = sqlsrv_fetch_array($res)) {
                                $valor = 0;
                                $id_registro = $data['id_registro'];
                                ?>
                                <tr>
                                    <td><?php echo date_format($data['fecha_apertura_tique'],'d-m-Y'); ?></td>
                                    <td><?php echo utf8_encode($data['Acopio']); ?></td>
                                    <!--<td align="center"><?php echo $data['cod_reporte']; ?></td>-->
                                    <td><?php echo utf8_encode($data['Zona']); ?></td>
                                    <td align="center"><?php echo utf8_encode($data['Pila']); ?></td>
                                    <td><?php echo utf8_encode($data['Clasificacion']); ?></td>
                                    <td><?php echo utf8_encode($data['Equipo']); ?></td>
                                    <td><?php echo utf8_encode($data['cod_reporte']); ?></td>
                                    <td><?php echo $data['id_usuario']; ?></td>
                                    <td><?php echo $data['Cargador']; ?></td>
                                    <td style="text-align: right;"><?php echo $data['horometro_ini']; ?></td>
                                    <td style="text-align: right;"><?php echo $data['horometro_fin']; ?></td>
                                    <td style="text-align: right;"><?php echo number_format($data['horas_trabajadas'],1); ?></td>
                                    <?php 
                                    $sql = "SELECT  subactividades_cargadores.Descripcion AS SubActividades, SUM(horometro.total_horas) AS total_horas
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
                                    WHERE (tiempos_cargadores_actividad.idRegistro='$id_registro') AND (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (Registro_tique_cargadores.id_proveedor = '$empresa') AND 
                                         (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion in ('ALIMENTAR','APILAR'))
                                         and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo != '7A975CD6-2672-430D-B29E-7149A03D9410')
                                    GROUP BY subactividades_cargadores.Descripcion
                                    ORDER BY subactividades_cargadores.Descripcion";
                                    $consul = sqlsrv_query($conn,utf8_decode($sql));
                                    while($a = sqlsrv_fetch_array($consul)){
                                        if($a['SubActividades'] == 'ALIMENTAR'){
                                            $valor+=$a['total_horas'];
                                        }elseif($a['SubActividades'] == 'APILAR'){
                                            $valor+=$a['total_horas'];
                                        }
                                        ?>
                                        <td style="text-align: right;"><?php echo $a['total_horas']; ?></td>
                                        <?php
                                    }
                                    ?>
                                    <td style="text-align: right;"><?php echo $valor;  ?></td>
                                    <td style="text-align: right;"><?php echo $data['paladas']; ?></td>
                                    <td style="text-align: right;"><?php echo $data['total_horas']; ?></td>
                                    <td style="text-align: right;"><?php echo $data['TM_total']; ?></td>
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
                                    WHERE        (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND (Registro_tique_cargadores.id_proveedor = '$empresa') AND 
                                                             (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'APILAR')
                                                             and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo != '7A975CD6-2672-430D-B29E-7149A03D9410')
                                                             AND (tiempos_cargadores_actividad.idRegistro='$id_registro')
                                    GROUP BY  Clasificacion.Descripcion
                                    ORDER BY Clasificacion.Descripcion";
                                    $result = sqlsrv_query($conn,utf8_decode($sql));
                                    while($prod = sqlsrv_fetch_array($result)){
                                        if($Array_clasif[$position] == utf8_encode($prod['Clasificacion'])){
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
                                            <td style="text-align: right;"><?php echo number_format(($Array_rendimiento[$i]/$data['TM_total'])*100,2,',','.').' %'; ?></td>
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
                                    <td style="text-align: right;"><?php echo number_format($data['paladas']/$valor,2,',','.'); ?></td>
                                    <td style="text-align: right;"><?php echo number_format($data['TM_total']/$valor,2,',','.'); ?></td>
                                </tr>
                                <?php
                                $valor = 0;
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                }
                /*echo '<pre>';
                print_r($Array_clasif);
                print_r($Array_rendimiento);
                echo '</pre>';*/
                ?>
            </div>
        </div>
    </div>
</body>
</html>