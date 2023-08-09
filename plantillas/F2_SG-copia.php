<?php 
session_start();
include('../modelo/conexion.php');
if ($_SESSION["logueado"] == TRUE && ($_SESSION["permisoIngresar"] == 'CONSULTAS_OFICINA' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES' || $_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO')){
    $usuario = $_SESSION['usuario'];
    $Fecha = date('Y-m-d');
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
}else{
    header("Location: ../index.php");
    die();
}
$sql_consulta = "";
$res_consulta = "";
if (isset($_POST['buscar'])){
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $empresa = $_POST['empresa'];
    $acopio = $_POST['acopio'];
    if($_POST['fecha_inicio']>$_POST['fecha_fin'])
    {   echo '<script type="text/javascript"> alert("Fecha Inicial Mayor que Fecha Final");</script>';
    }elseif($empresa == 1){
        if($acopio == 1){
            $sql = "SELECT Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.id_registro, 
        Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, 
        Registro_tique_cargadores.id_usuario, Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, 
        Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado,
        Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, 
        Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, P.NombreCorto as proveedor, Usuarios.NombreUsuarioLargo, 
        Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.horometro_ini, Registro_tique_cargadores.horometro_fin
                FROM Registro_tique_cargadores INNER JOIN
                     Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                     Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                     Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                     Proveedores AS P ON Equipos.idPropietario = P.idProveedor INNER JOIN
                     Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
                WHERE Registro_tique_cargadores.Estado = '3' AND CAST (Registro_tique_cargadores.fecha_cierre_tique AS DATE) between '$fecha_inicio' AND '$fecha_fin' 
                    and Registro_tique_cargadores.horas_trabajadas>0
                ORDER BY Registro_tique_cargadores.cod_reporte DESC";
        }else{
            $sql = "SELECT Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.id_registro, 
    Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, 
    Registro_tique_cargadores.id_usuario, Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, 
    Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado, 
    Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, 
    Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, Usuarios.NombreUsuarioLargo, P.NombreCorto as proveedor, 
    Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.horometro_ini, Registro_tique_cargadores.horometro_fin
            FROM Registro_tique_cargadores INNER JOIN
                 Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                 Proveedores AS P ON Equipos.idPropietario = P.idProveedor INNER JOIN
                 Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
            WHERE Registro_tique_cargadores.Estado = '3' AND CAST (Registro_tique_cargadores.fecha_cierre_tique AS DATE) between '$fecha_inicio' AND '$fecha_fin'
                  AND Registro_tique_cargadores.id_patio ='$acopio' and Registro_tique_cargadores.horas_trabajadas>0
            ORDER BY Registro_tique_cargadores.cod_reporte DESC";
        }
    }else{
        if($acopio == 1){
            $sql = "SELECT Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.id_registro, 
            Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, 
            Registro_tique_cargadores.id_usuario, Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, 
            Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado, 
            Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, 
            Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, Usuarios.NombreUsuarioLargo, P.NombreCorto as proveedor, 
            Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.horometro_ini, Registro_tique_cargadores.horometro_fin
                    FROM Registro_tique_cargadores INNER JOIN
                         Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                         Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                         Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                         Proveedores AS P ON Equipos.idPropietario = P.idProveedor INNER JOIN
                         Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
                    WHERE Registro_tique_cargadores.Estado = '3' AND CAST (Registro_tique_cargadores.fecha_cierre_tique AS DATE) between '$fecha_inicio' AND '$fecha_fin' 
                          AND Registro_tique_cargadores.id_proveedor='$empresa' and Registro_tique_cargadores.horas_trabajadas>0
                    ORDER BY Registro_tique_cargadores.cod_reporte DESC";
          }else{
            $sql = "SELECT Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.id_registro, 
            Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, 
            Registro_tique_cargadores.id_usuario, Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, 
            Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado,
            Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, 
            Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, Usuarios.NombreUsuarioLargo, P.NombreCorto as proveedor, 
            Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.horometro_ini, Registro_tique_cargadores.horometro_fin
                    FROM Registro_tique_cargadores INNER JOIN
                         Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                         Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                         Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                         Proveedores AS P ON Equipos.idPropietario = P.idProveedor INNER JOIN
                         Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
                    WHERE Registro_tique_cargadores.Estado = '3' AND CAST (Registro_tique_cargadores.fecha_cierre_tique AS DATE) between '$fecha_inicio' AND '$fecha_fin'
                          AND Registro_tique_cargadores.id_proveedor='$empresa' AND Registro_tique_cargadores.id_patio ='$acopio' and Registro_tique_cargadores.horas_trabajadas>0
                    ORDER BY Registro_tique_cargadores.cod_reporte DESC";
          }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
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
    <div class="container-fluid">
        <center><h1>Informe SG</h1></center>
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="../vista/Admin.php"><i class=" fa fa-home fa-sm"></i>Inicio</a></li>
            <li><a href="../vista/consultas.php"><i class=" fa fa-home fa-sm"></i>Consultas</a></li>
            <li><span class="active">Resumen Actividades</span></li>
        </ul>
        <div class="row">
            <div class="col-sm-12">
                <form method="post">
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Empresa:</label>
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
                        <div class="col-sm-3">
                            <label>Fecha Inicio:</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php if(isset($_POST['fecha_inicio'])){ if(!empty($_POST['fecha_inicio'])){ echo $_POST['fecha_inicio'];  }} ?>">
                        </div>
                        <div class="col-sm-3">
                            <label>Fecha Fin:</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?php if(isset($_POST['fecha_fin'])){ if(!empty($_POST['fecha_fin'])){ echo $_POST['fecha_fin'];  }} ?>">
                        </div>
                        <div class="col-sm-3">
                            <label>Acopio:</label>
                            <select class="form-control" name="acopio" id="acopio">
                                <option value="1">TODOS</option>
                                <?php
                                $sql_destino = "SELECT DISTINCT tz_MovimientoTransporte.DespachadoDesde, Destino.idDestino
                                              FROM tz_MovimientoTransporte INNER JOIN
                                                   Destino ON tz_MovimientoTransporte.DespachadoDesde = Destino.Descripcion
                                              WHERE (tz_MovimientoTransporte.DespachadoDesde <> 'Patio') AND (YEAR(tz_MovimientoTransporte.FechaRegistro) >= 2018)
                                              order by DespachadoDesde ASC";
                                $result = sqlsrv_query($conn,$sql_destino);
                                while ($destino = sqlsrv_fetch_array($result)){
                                    ?><option value="<?php echo $destino['idDestino']; ?>" <?php if(isset($_POST['acopio'])){ if($_POST['acopio'] == $destino['idDestino']){ echo 'selected';  }} ?>><?php echo utf8_encode($destino['DespachadoDesde']); ?></option><?php
                                } ?>
                            </select>
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
        <?php 
        if (isset($_POST['buscar'])){
            ?>
            <a href="../vista/descarga_excel-copia.php?empresa=<?php echo $_POST['empresa']; ?>&fecha_inicio=<?php echo $_POST['fecha_inicio']; ?>&fecha_fin=<?php echo $_POST['fecha_fin'] ?>&acopio=<?php echo $_POST['acopio']; ?>"><img src="../Imagenes/excel.png" width="25" height="25"></a>
            <?php
        ?>
        <div class="row table-responsive" style="height: 300px;">
            <div class="col-sm-12 table-responsive">
                <table class="table table-hover table-condensed table-bordered table-responsive table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle; text-align: center; background-color: #DCE6F1;">N° tiquete</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center; background-color: #DCE6F1;">Empresa</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center; background-color: #DCE6F1;">Acopio</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center; background-color: #DCE6F1;">Operación</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center; background-color: #DCE6F1;">Proveedor</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center; background-color: #DCE6F1;">Fecha Tique</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center; background-color: #DCE6F1;">Operario</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center; background-color: #DCE6F1;">Equipo</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center; background-color: #DCE6F1;">Horo. Inicial</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center; background-color: #DCE6F1;">Horo. Final</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center; background-color: #DCE6F1;">Total Hrs.</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center; background-color: #83CFD3;">Desc. %</th>
                            <th colspan="2" style="vertical-align: middle; text-align: center; background-color: #649DE5;">Clasificar Room</th>
                            <th colspan="2" style="vertical-align: middle; text-align: center; background-color: #B6B8BB;">Clasificar Sobretamaño</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center; background-color: #EEA747;">Cargar Despacho</th>
                            <th colspan="3" style="vertical-align: middle; text-align: center; background-color: #FCD5B4;">Entradas</th>
                            <th colspan="2" style="vertical-align: middle; text-align: center; background-color: #FCD5B4;">Molienda</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center; text-align: center; background-color: #DDD9C4;">Standby</th>
                        </tr>
                        <tr>
                            <th style="vertical-align: middle; text-align: center; background-color: #649DE5;">Alimentar</th>
                            <th style="vertical-align: middle; text-align: center; background-color: #649DE5;">Apilar</th>
                            <th style="vertical-align: middle; text-align: center; background-color: #B6B8BB;">Alimentar</th>
                            <th style="vertical-align: middle; text-align: center; background-color: #B6B8BB;">Apilar</th>
                            <th style="vertical-align: middle; text-align: center; background-color: #F8D857;">Apilar</th>
                            <th style="vertical-align: middle; text-align: center; background-color: #FF0000;">Mvto. x Calidad</th>
                            <th style="vertical-align: middle; text-align: center; background-color: #FF0000;">Oficios Varios</th>
                            <th style="vertical-align: middle; text-align: center; background-color: #FCD5B4;">Alimentar</th>
                            <th style="vertical-align: middle; text-align: center; background-color: #FCD5B4;">Apilar</th>
                        </tr>
                    </thead>
                    <?php 
                    $total_descuentos = 0;
                    $total_room_alimentar = 0;
                    $total_room_apilar = 0;
                    $total_sobretamaño_alimentar = 0;
                    $total_sobretamaño_apilar = 0;
                    $total_cargar_despacho = 0;
                    $total_entradas_apilar = 0;
                    $total_entradas_mvto = 0;
                    $total_entradas_varios = 0;
                    $total_molienda_alimentar = 0;
                    $total_molienda_apilar = 0;
                    $total_standby = 0;
                    $res = sqlsrv_query($conn,$sql);
                    while($r = sqlsrv_fetch_array($res))
                    {   $paso = 0;
                        $band = 0;
                        $cont_room = 0;
                        $cont_sobreta = 0;
                        $cont_despacho = 0;
                        $cont_entradas = 0;
                        $cont_molienda = 0;
                        $cont_standby = 0;
                        $total_horas = 0; 
                        ?>
                        <tr>
                            <td style="vertical-align: middle; text-align: center;"><a href="tiquete_detalle.php?tiquete=<?php echo $r['cod_reporte']; ?>"><?php echo $r['cod_reporte']; ?></a></td>
                            <td style="vertical-align: middle; text-align: center;"><?php echo $r['NombreCorto']; ?></td>
                            <td style="vertical-align: middle; text-align: center;"><?php echo $r['Descripcion']; ?></td>
                            <?php
                            if($r['servicio_clasificacion'] == 1){
                                ?><td style="vertical-align: middle; text-align: center;">SERVICIO DE CLASIFICACIÓN</td><?php
                            }else{
                                ?><td style="vertical-align: middle; text-align: center;">CARBÓN</td><?php
                            }
                            ?>
                            <td style="vertical-align: middle; text-align: center;"><?php echo $r['proveedor']; ?></td>
                            <td style="vertical-align: middle; text-align: center;"><?php echo date_format($r['fecha_apertura_tique'],'Y-m-d'); ?></td>
                            <td style="vertical-align: middle; text-align: center;"><?php echo $r['NombreUsuarioLargo']; ?></td>
                            <td style="vertical-align: middle; text-align: center;"><?php echo $r['NombreCargador'].' - '.$r['Identificacion']; ?></td>
                            <td style="vertical-align: middle; text-align: center;"><?php echo $r['horometro_ini']; ?></td>
                            <td style="vertical-align: middle; text-align: center;"><?php echo $r['horometro_fin']; ?></td>
                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($r['horas_trabajadas'],1); ?></td>
                            <?php 
                            // HOROMETROS DE DESCUENTO
                            $sql_desc = "SELECT * FROM horometro_descuento_cargadores WHERE idRegistro='". $r['id_registro'] ."'";
                            $params = array();
                            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                            $res_desc=sqlsrv_query($conn,utf8_decode($sql_desc),$params,$options);
                            $rows=sqlsrv_num_rows($res_desc);
                            if ($rows > 0) 
                            {   while($desc = sqlsrv_fetch_array($res_desc)){
                                    ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($desc['valor_descuento'],1,'.',','); ?></td><?php
                                    $total_descuentos+=$desc['valor_descuento'];
                                }
                            }else{
                                ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                            }
                            //FIN HOROMETROS DE DESCUENTO
                            //INICIO DE TIEMPO DE ACTIVIDADES
                           $sql_1 = "SELECT horometro.fecha_cierre_horometro ,horometro.id_horometro, horometro.id_registro, horometro.horometro_inicial, 
                              horometro.horometro_final, horometro.total_horas, horometro.idActividad, horometro.fecha_registro_horometro, 
                              Actividades.Descripcion, SubActividades_cargadores.Descripcion as Descripcion_sub, horometro.observaciones
                                          FROM horometro LEFT JOIN
                                          Actividades ON horometro.idActividad = Actividades.idActividad
                                          LEFT JOIN SubActividades_cargadores ON horometro.idSubActividad=SubActividades_cargadores.idSubActividad
                                         WHERE horometro.id_registro='". $r['id_registro'] ."' AND horometro.idActividad is not null ORDER BY Actividades.Descripcion,SubActividades_cargadores.Descripcion";
                            $res_1 = sqlsrv_query($conn,$sql_1);
                            while($horometro = sqlsrv_fetch_array($res_1)){
                              /*?><td><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td><?php*/
                                if($band == 0){
                                    if($horometro['Descripcion'] == 'CLASIFICAR ROOM'){
                                        $cont_room++;
                                        if($horometro['Descripcion_sub'] == 'ALIMENTAR'){
                                            if($paso == 0){
                                                ?>
                                                <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td><?php
                                                $total_room_alimentar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                                $paso = 1;
                                            }
                                        }elseif($horometro['Descripcion_sub'] == 'APILAR'){                    
                                            if($paso == 0){
                                                ?>
                                                <td style="vertical-align: middle; text-align: center;">0</td>
                                                <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td><?php
                                                $total_room_apilar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                                $cont_room++;
                                                //$band++;
                                                $paso = 2;
                                            }elseif($paso == 1){
                                                ?>
                                                <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td><?php
                                                //$band++;
                                                $total_room_apilar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                                $paso = 2;
                                            }          
                                        }
                                    }//FIN CLASIFICAR ROOM
                                    else{
                                        $paso = 0;
                                        if($cont_room == 0){
                                            ?><td style="vertical-align: middle; text-align: center;">0</td>
                                             <td style="vertical-align: middle; text-align: center;">0</td><?php
                                        }elseif($cont_room == 1) {
                                            ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                                        }
                                        if(utf8_encode($horometro['Descripcion']) == 'CLASIFICAR SOBRETAMAÑO'){
                                            $cont_sobreta++;
                                            $band++;
                                            if($horometro['Descripcion_sub'] == 'ALIMENTAR'){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td><?php
                                                $total_sobretamaño_alimentar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                                $paso = 1;
                                            }elseif($horometro['Descripcion_sub'] == 'APILAR'){
                                                if($paso == 0){
                                                    ?><td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td><?php
                                                    $total_sobretamaño_apilar+=$horometro['total_horas'];
                                                    $total_horas+=$horometro['total_horas'];
                                                    $cont_sobreta++;
                                                }else{
                                                    ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td><?php
                                                    $total_sobretamaño_apilar+=$horometro['total_horas'];
                                                    $total_horas+=$horometro['total_horas'];
                                                }
                                                $paso = 2;                    
                                            }
                                        }elseif(utf8_encode($horometro['Descripcion']) == 'DESPACHO'){
                                            ?><td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td><?php
                                            $band+=2;
                                            $cont_despacho++;
                                            if($horometro['Descripcion_sub'] == 'CARGAR DESPACHO'){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td><?php
                                                $total_cargar_despacho+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }
                                        }elseif(utf8_encode($horometro['Descripcion']) == 'ENTRADAS'){
                                            ?><td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td><?php
                                            $band+=3;
                                            $cont_entradas++;
                                            if($horometro['Descripcion_sub'] == 'APILAR'){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td><?php
                                                $total_entradas_apilar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                                $paso = 1;
                                            }elseif($horometro['Descripcion_sub'] == 'MVTO. X CALIDAD'){
                                                if($paso == 1){
                                                    ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td><?php
                                                }else{
                                                    ?><td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td><?php
                                                    $cont_entradas++;
                                                }
                                                $paso = 2;
                                                $total_entradas_mvto+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }elseif($horometro['Descripcion_sub'] == 'OFICIOS VARIOS'){
                                                if($paso == 0){
                                                    ?><td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td><?php
                                                    $cont_entradas+=2;
                                                }elseif($paso == 1){
                                                    ?><td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td><?php
                                                    $cont_entradas++;
                                                }else{
                                                    ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td><?php
                                                }
                                                $paso = 3;
                                                $total_entradas_varios+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }
                                        }elseif(utf8_encode($horometro['Descripcion']) == 'MOLIENDA'){
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                            $cont_molienda++;
                                            $band+=4;
                                            if($horometro['Descripcion_sub'] == 'ALIMENTAR'){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td><?php
                                                $total_molienda_alimentar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                                $paso = 1;
                                            }elseif($horometro['Descripcion_sub'] == 'APILAR'){
                                                if($paso == 0){
                                                    ?>
                                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td>
                                                    <?php
                                                }else{
                                                    ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td><?php
                                                }
                                                $paso = 2;
                                                $total_molienda_apilar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }
                                        }elseif(utf8_encode($horometro['Descripcion']) == 'STANDBY'){
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                            $band+=5;
                                            $cont_standby++;
                                            if($horometro['Descripcion_sub'] == 'STANDBY'){
                                                ?>
                                                <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.',','); ?></td>
                                                <?php
                                                $total_standby+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }
                                        }                  
                                    }
                                }///FIN BAND == 0
                                ///FIN BAND == 0
                                ///FIN BAND == 0
                                ///FIN BAND == 0
                                elseif($band == 1){
                                    if(utf8_encode($horometro['Descripcion']) == 'CLASIFICAR SOBRETAMAÑO'){
                                        $cont_sobreta++;
                                        if($horometro['Descripcion_sub'] == 'ALIMENTAR'){
                                            if($paso == 0){
                                                ?>
                                                <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                $total_sobretamaño_alimentar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                                $paso=1;
                                            }
                                        }elseif($horometro['Descripcion_sub'] == 'APILAR'){
                                            if($paso == 0){
                                                ?>
                                                <td style="vertical-align: middle; text-align: center;">0</td>
                                                <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td>
                                                <?php
                                                $total_sobretamaño_apilar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                                $cont_sobreta++;
                                                $paso = 2;
                                            }else{
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                $total_sobretamaño_apilar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                                $paso = 3;
                                            }
                                        }
                                    }else{
                                        $paso = 0;
                                        if($cont_sobreta == 0){
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        }elseif($cont_sobreta == 1) {
                                            ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                                        }
                                        if(utf8_encode($horometro['Descripcion']) == 'DESPACHO'){
                                            $band++;
                                            $cont_despacho++;
                                            if($horometro['Descripcion_sub'] == 'CARGAR DESPACHO'){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                $total_cargar_despacho+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }
                                        }elseif(utf8_encode($horometro['Descripcion']) == 'ENTRADAS'){
                                            ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                                            $band+=2;
                                            $cont_entradas++;
                                            if($horometro['Descripcion_sub'] == 'APILAR'){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                $paso = 1;
                                                $total_entradas_apilar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }elseif($horometro['Descripcion_sub'] == 'MVTO. X CALIDAD'){
                                                if($paso == 1){
                                                    ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                }else{
                                                    ?>
                                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td>
                                                    <?php
                                                    $cont_entradas++;
                                                }
                                                $paso = 2;
                                                $total_entradas_mvto+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }elseif($horometro['Descripcion_sub'] == 'OFICIOS VARIOS'){
                                                if($paso == 0){
                                                    ?>
                                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td>
                                                    <?php
                                                    $cont_entradas+=2;
                                                }elseif($paso == 1){
                                                    ?>
                                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td>
                                                    <?php
                                                    $cont_entradas++;
                                                }else{
                                                    ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                }
                                                $paso = 3;
                                                $total_entradas_varios+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }
                                        }elseif(utf8_encode($horometro['Descripcion']) == 'MOLIENDA'){
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                            $cont_molienda++;
                                            $band+=3;
                                            if($horometro['Descripcion_sub'] == 'ALIMENTAR'){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                $paso = 1;
                                                $total_molienda_alimentar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }elseif($horometro['Descripcion_sub'] == 'APILAR'){
                                                if($paso == 0){
                                                    ?>
                                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td>
                                                    <?php
                                                    $cont_molienda++;
                                                }else{
                                                    ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                }
                                                $paso = 2;
                                                $total_molienda_apilar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                                $cont_sobreta++;
                                            }
                                        }elseif(utf8_encode($horometro['Descripcion']) == 'STANDBY'){
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php                  
                                            $band+=4;
                                            $cont_standby++;
                                            if($horometro['Descripcion_sub'] == 'STANDBY'){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                $total_standby+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }
                                        }
                                    }
                                }elseif($band == 2){
                                    if(utf8_encode($horometro['Descripcion']) == 'DESPACHO'){
                                        $cont_despacho++;
                                        if($horometro['Descripcion_sub'] == 'CARGAR DESPACHO'){
                                            $band++;
                                            ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                            $total_cargar_despacho+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                        }
                                    }else{
                                        $paso = 0;
                                        if($cont_despacho == 0){
                                            ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                                        }
                                        if(utf8_encode($horometro['Descripcion']) == 'ENTRADAS'){
                                            $band++;
                                            $cont_entradas++;
                                            if($horometro['Descripcion_sub'] == 'APILAR'){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                $total_entradas_apilar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                                $paso = 1;
                                            }elseif($horometro['Descripcion_sub'] == 'MVTO. X CALIDAD'){
                                                if($paso == 1){
                                                    ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                }else{
                                                    ?>
                                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                    $cont_entradas++;
                                                }
                                                $paso = 2;
                                                $total_entradas_mvto+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }elseif($horometro['Descripcion_sub'] == 'OFICIOS VARIOS'){
                                                if($paso == 0){
                                                    ?>
                                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td>
                                                    <?php
                                                    $cont_entradas+=2;
                                                }elseif($paso == 1){
                                                    ?>
                                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td>
                                                    <?php
                                                    $cont_entradas++;
                                                }else{
                                                    ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                }
                                                $paso = 3;
                                                $total_entradas_varios+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }
                                        }elseif(utf8_encode($horometro['Descripcion']) == 'MOLIENDA'){
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                            $cont_molienda++;
                                            $band+=2;
                                            if($horometro['Descripcion_sub'] == 'ALIMENTAR'){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                $paso = 1;
                                                $total_molienda_alimentar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }elseif($horometro['Descripcion_sub'] == 'APILAR'){
                                                if($paso == 0){
                                                    ?>
                                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td>
                                                    <?php
                                                    $cont_molienda++;
                                                }else{
                                                    ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                }
                                                $paso = 2;
                                                $total_molienda_apilar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                                $cont_sobreta++;
                                            }
                                        }elseif(utf8_encode($horometro['Descripcion']) == 'STANDBY'){
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                            $band+=3;
                                            $cont_standby++;
                                            if($horometro['Descripcion_sub'] == 'STANDBY'){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                $total_standby+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }
                                        }
                                    }
                                }elseif($band == 3){
                                    if(utf8_encode($horometro['Descripcion']) == 'ENTRADAS'){
                                        $cont_entradas++;
                                        if($horometro['Descripcion_sub'] == 'APILAR'){
                                             if($paso == 1){
                                                echo '<td>0</td>';
                                             }else{
                                               ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                               $total_entradas_apilar+=$horometro['total_horas'];
                                               $total_horas+=$horometro['total_horas'];
                                               $paso=1;
                                            }
                                        }elseif($horometro['Descripcion_sub'] == 'MVTO. X CALIDAD'){
                                            if($paso == 1){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                            }else{
                                                ?>
                                                <td style="vertical-align: middle; text-align: center;">0</td>
                                                <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td>
                                                <?php
                                                $cont_molienda++;
                                            }
                                            $paso = 2;
                                            $total_entradas_mvto+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                        }elseif($horometro['Descripcion_sub'] == 'OFICIOS VARIOS'){
                                            if($paso == 0){
                                                ?>
                                                <td style="vertical-align: middle; text-align: center;">0</td>
                                                <td style="vertical-align: middle; text-align: center;">0</td>
                                                <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td>
                                                <?php
                                                $cont_entradas+=2;
                                            }elseif($paso == 1){
                                                ?>
                                                <td style="vertical-align: middle; text-align: center;">0</td>
                                                <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td>
                                                <?php
                                                $cont_entradas++;
                                            }else{
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                            }
                                            $paso = 3;
                                            $total_entradas_varios+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                        }
                                    }else{
                                        $paso = 0;
                                        if($cont_entradas == 0){
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        }elseif($cont_entradas == 1) {
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        }elseif($cont_entradas == 2){
                                            ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                                        }
                                        if(utf8_encode($horometro['Descripcion']) == 'MOLIENDA'){
                                            $cont_molienda++;
                                            $band++;
                                            if($horometro['Descripcion_sub'] == 'ALIMENTAR'){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                $total_molienda_alimentar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                                $paso=1;
                                            }elseif($horometro['Descripcion_sub'] == 'APILAR'){
                                                if($paso == 0){
                                                    ?>
                                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td>
                                                    <?php
                                                    $cont_molienda++;
                                                }else{
                                                    ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                }
                                                $paso = 2;
                                                $total_molienda_apilar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                                $cont_sobreta++;
                                            }
                                        }elseif(utf8_encode($horometro['Descripcion']) == 'STANDBY'){
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                            $band+=2;
                                            $cont_standby++;
                                            if($horometro['Descripcion_sub'] == 'STANDBY'){
                                                ?><td><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                $total_standby+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }
                                        }
                                    }
                                }elseif($band == 4){
                                    if(utf8_encode($horometro['Descripcion']) == 'MOLIENDA'){
                                        $cont_molienda++;
                                        if($horometro['Descripcion_sub'] == 'ALIMENTAR'){
                                            ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                            $total_molienda_alimentar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                        }elseif($horometro['Descripcion_sub'] == 'APILAR'){
                                            if($paso == 0){
                                                $cont_molienda++;
                                                ?>
                                                <td style="vertical-align: middle; text-align: center;">0</td>
                                                <td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td>
                                                <?php
                                                $total_molienda_apilar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                                $band++;
                                            }else{
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                $total_molienda_apilar+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                                $band++;
                                            }                       
                                        }
                                    }else{
                                        if($cont_molienda == 0){
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        }elseif($cont_molienda == 1){
                                            ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                                        }
                                        if(utf8_encode($horometro['Descripcion']) == 'STANDBY'){
                                             $cont_standby++;
                                             $band++;
                                            if($horometro['Descripcion_sub'] == 'STANDBY'){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                                $total_standby+=$horometro['total_horas'];
                                                $total_horas+=$horometro['total_horas'];
                                            }
                                        }
                                    }
                                }elseif($band == 5){
                                    if(utf8_encode($horometro['Descripcion']) == 'STANDBY'){
                                       $cont_standby++;
                                        if($horometro['Descripcion_sub'] == 'STANDBY'){
                                            ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($horometro['total_horas'],1,'.','.'); ?></td><?php
                                            $total_standby+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                        }
                                    }else{
                                        ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                                    }
                                    $band++;
                                }
                            }
                            if($band == 0){
                                if($cont_room == 0){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }elseif($cont_room == 1){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }elseif($cont_room == 2){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }
                            }elseif($band == 1){
                                if($cont_sobreta){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }elseif($cont_sobreta == 1){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }elseif($cont_sobreta == 2){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }
                            }elseif($band == 2){
                                if($cont_despacho == 0){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }elseif($cont_despacho == 1){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }
                            }elseif($band == 3){
                                if($cont_entradas == 0){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }elseif($cont_entradas == 1){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }elseif($cont_entradas == 2){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }elseif($cont_entradas == 3){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }
                            }elseif($band == 4){
                                if($cont_molienda == 0){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }elseif($cont_molienda == 1){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }elseif($cont_molienda == 2){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }
                            }elseif($band == 5){
                                if($cont_standby == 0){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }
                              }
                            ?>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    <?php } ?>
    </div>
</body>
</html>