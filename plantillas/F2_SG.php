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
        <link rel="stylesheet" type="text/css" href="../librerias/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../librerias/alertifyjs/css/alertify.css">
        <link rel="stylesheet" type="text/css" href="../librerias/alertifyjs/css/themes/bootstrap.css">

        <script src="../librerias/jquery-3.3.1.min.js"></script>
        <script src="../librerias/bootstrap/js/bootstrap.js"></script>
        <script src="../librerias/alertifyjs/alertify.js"></script>
        <script src="../librerias/jquery.min.js" type="text/javascript"></script>
        <script src="../librerias/moment.js" type="text/javascript"></script>

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
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
                                <option>--- Seleccione ---</option>
                                <?php 
                                $sql_1 = "SELECT * FROM Proveedores WHERE idProveedor in (SELECT idProveedor FROM  vProveedoresInAgrupacion WHERE Alias='CC') and idProveedor in ('$empresas') ORDER BY NombreCorto";
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
                                $sql_destino = "SELECT * FROM Destino WHERE OperacionCargador=1 ORDER BY Descripcion";
                                $result = sqlsrv_query($conn,$sql_destino);
                                while ($destino = sqlsrv_fetch_array($result)){
                                    ?><option value="<?php echo $destino['idDestino']; ?>" <?php if(isset($_POST['acopio'])){ if($_POST['acopio'] == $destino['idDestino']){ echo 'selected';  }} ?>><?php echo utf8_encode($destino['Descripcion']); ?></option><?php
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
            <a href="../vista/descarga_excel.php?empresa=<?php echo $_POST['empresa']; ?>&fecha_inicio=<?php echo $_POST['fecha_inicio']; ?>&fecha_fin=<?php echo $_POST['fecha_fin'] ?>&acopio=<?php echo $_POST['acopio']; ?>"><img src="../Imagenes/excel.png" width="25" height="25"></a>
            <?php
            //table table-hover table-condensed table-bordered table-responsive table-striped
        ?>
        <div class="row">
            <div class="col-sm-12 table-responsive" style="height: 550px;">
                <table id="example1" class="row-border hover order-column compact">
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
                            <th colspan="2" style="vertical-align: middle; text-align: center; background-color: #CFBAA8;">Molienda</th>
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
                            <th style="vertical-align: middle; text-align: center; background-color: #CFBAA8;">Alimentar</th>
                            <th style="vertical-align: middle; text-align: center; background-color: #CFBAA8;">Apilar</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $res = sqlsrv_query($conn,$sql);
                    while($r = sqlsrv_fetch_array($res))
                    {   // VARIABLES CONTADORES
                        $paso = 0;
                        $band = 0;
                        $cont_room = 0;
                        $cont_sobreta = 0;
                        $cont_despacho = 0;
                        $cont_entradas = 0;
                        $cont_molienda = 0;
                        $cont_standby = 0;
                        $total_horas = 0;
                        // VARIABLES DE SUMA
                        $actividad = "activdad1";
                        $subactividad = "";
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
                        ?>
                        <tr>
                            <td style="vertical-align: middle; text-align: center;"><a href="../vista/informe_horometros-pdf.php?idTiquete=<?php echo $r['id_registro']; ?>&tipo_informe=2" target="_blank"><?php echo $r['cod_reporte']; ?></a></td>
                            <td style="vertical-align: middle; text-align: center;"><?php echo $r['NombreCorto']; ?></td>
                            <td style="vertical-align: middle; text-align: center;"><?php echo utf8_encode($r['Descripcion']); ?></td>
                            <?php
                            if($r['servicio_clasificacion'] == 1){
                                ?><td style="vertical-align: middle; text-align: center;">SERVICIO DE CLASIFICACIÓN</td><?php
                            }else{
                                ?><td style="vertical-align: middle; text-align: center;">CARBÓN</td><?php
                            }?>
                            <td style="vertical-align: middle; text-align: center;"><?php echo utf8_encode($r['proveedor']); ?></td>
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
                                         WHERE horometro.id_registro='". $r['id_registro'] ."' AND horometro.idActividad is not null AND horometro.tipo_tarifa<>'1' ORDER BY Actividades.Descripcion,SubActividades_cargadores.Descripcion";
                            $res_1 = sqlsrv_query($conn,$sql_1);
                            while($horometro = sqlsrv_fetch_array($res_1)){
                                if($band == 0){
                                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    $activdad = utf8_encode($horometro['Descripcion']);
                                    $subactividad = utf8_encode($horometro['Descripcion_sub']);
                                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    if($activdad == 'CLASIFICAR ROOM'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $band=1;
                                        if($subactividad == 'ALIMENTAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_room_alimentar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_room = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'APILAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?><td style="vertical-align: middle; text-align: center;">0,0</td><?php
                                            $total_room_apilar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_room = 2;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'CLASIFICAR SOBRETAMAÑO'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $band=2;
                                        ?>
                                        <td style="vertical-align: middle; text-align: center;">0</td>
                                        <td style="vertical-align: middle; text-align: center;">0</td>
                                        <?php
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'ALIMENTAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_sobretamaño_alimentar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_sobreta = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'APILAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                                            $total_sobretamaño_apilar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_sobreta = 2;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    }elseif($activdad == 'DESPACHO'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $band=3;
                                        ?>
                                        <td style="vertical-align: middle; text-align: center;">0</td>
                                        <td style="vertical-align: middle; text-align: center;">0</td>
                                        <td style="vertical-align: middle; text-align: center;">0</td>
                                        <td style="vertical-align: middle; text-align: center;">0</td>
                                        <?php
                                        if($subactividad == 'CARGAR DESPACHO'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_cargar_despacho+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_despacho = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'ENTRADAS'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $band=4;
                                        ?>
                                        <td style="vertical-align: middle; text-align: center;">0</td>
                                        <td style="vertical-align: middle; text-align: center;">0</td>
                                        <td style="vertical-align: middle; text-align: center;">0</td>
                                        <td style="vertical-align: middle; text-align: center;">0</td>
                                        <td style="vertical-align: middle; text-align: center;">0</td>
                                        <?php
                                        if($subactividad == 'APILAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_entradas_apilar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_entradas = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'MVTO. X CALIDAD'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                                            $total_entradas_mvto+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_entradas = 2;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'OFICIOS VARIOS'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                            $total_entradas_varios+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_entradas = 3;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'MOLIENDA'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $band=5;
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
                                        if($subactividad == 'ALIMENTAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_molienda_alimentar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_molienda = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'APILAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?> <td style="vertical-align: middle; text-align: center;">0</td> <?php
                                            $total_molienda_apilar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_molienda = 2;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'STANDBY'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $band=6;
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
                                        if($subactividad == 'STANDBY'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_standby+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_standby = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////        
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }
                                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                }elseif($band == 1){
                                    $activdad = utf8_encode($horometro['Descripcion']);
                                    $subactividad = utf8_encode($horometro['Descripcion_sub']);
                                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    if($activdad == 'CLASIFICAR ROOM'){ 
                                        //$activdad = utf8_encode($horometro['Descripcion']);
                                        //$subactividad = utf8_encode($horometro['Descripcion_sub']);
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'ALIMENTAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_room_alimentar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_room = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'APILAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            if($cont_room == 1){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_room_alimentar,1); ?></td><?php
                                            }else if($cont_room == 0){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo 'asdas'; ?></td><?php
                                            } 
                                            $total_room_apilar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_room = 2;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'CLASIFICAR SOBRETAMAÑO'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $activdad = utf8_encode($horometro['Descripcion']);
                                        $subactividad = utf8_encode($horometro['Descripcion_sub']);
                                        $band=2;
                                        if($cont_room == 1){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_room_alimentar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($cont_room == 2){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_room_apilar,1); ?></td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                        $paso = 0;
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'ALIMENTAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_sobretamaño_alimentar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_sobreta = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'APILAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                                            $total_sobretamaño_apilar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_sobreta = 2;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    }elseif($activdad == 'DESPACHO'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $activdad = utf8_encode($horometro['Descripcion']);
                                        $subactividad = utf8_encode($horometro['Descripcion_sub']);
                                        $band=3;
                                        if($cont_room == 1){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_room_alimentar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($cont_room == 2){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_room_apilar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                        $paso = 0;
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'CARGAR DESPACHO'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_cargar_despacho+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_despacho = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'ENTRADAS'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $activdad = utf8_encode($horometro['Descripcion']);
                                        $subactividad = utf8_encode($horometro['Descripcion_sub']);
                                        $band=4;
                                        if($cont_room == 1){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_room_alimentar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($cont_room == 2){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_room_apilar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                        $paso = 0;
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'APILAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_entradas_apilar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_entradas = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'MVTO. X CALIDAD'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                                            $total_entradas_mvto+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_entradas = 2;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'OFICIOS VARIOS'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                            $total_entradas_varios+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_entradas = 3;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'MOLIENDA'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $activdad = utf8_encode($horometro['Descripcion']);
                                        $subactividad = utf8_encode($$horometro['Descripcion_sub']);
                                        $band=5;
                                        if($cont_room == 1){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_room_alimentar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($cont_room == 2){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_room_apilar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                        $paso = 0;
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'ALIMENTAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_molienda_alimentar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_molienda = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'APILAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?> <td style="vertical-align: middle; text-align: center;">0</td> <?php
                                            $total_molienda_apilar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_molienda = 2;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'STANDBY'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $activdad = utf8_encode($horometro['Descripcion']);
                                        $subactividad = utf8_encode($$horometro['Descripcion_sub']);
                                        $band=6;
                                        if($cont_room == 1){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_room_alimentar,1); ?></td>
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
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($cont_room == 2){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_room_apilar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'STANDBY'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_standby+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_standby = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////        
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }
                                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                }elseif($band == 2){
                                    $activdad = utf8_encode($horometro['Descripcion']);
                                    $subactividad = utf8_encode($horometro['Descripcion_sub']);
                                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    if($activdad == 'CLASIFICAR SOBRETAMAÑO'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'ALIMENTAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_sobretamaño_alimentar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_sobreta = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'APILAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            if($cont_sobreta == 1){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php  echo number_format($total_sobretamaño_alimentar,1); ?></td><?php
                                            }elseif($cont_sobreta == 0){
                                                ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                                            }
                                            $total_sobretamaño_apilar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_sobreta = 2;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    }elseif($activdad == 'DESPACHO'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $activdad = utf8_encode($horometro['Descripcion']);
                                        $subactividad = utf8_encode($horometro['Descripcion_sub']);
                                        $band=3;
                                        if($cont_sobreta == 1){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_sobretamaño_alimentar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($cont_sobreta == 2){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_sobretamaño_apilar,1); ?></td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                        $paso = 0;
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'CARGAR DESPACHO'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_cargar_despacho+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_despacho = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'ENTRADAS'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $activdad = utf8_encode($horometro['Descripcion']);
                                        $subactividad = utf8_encode($$horometro['Descripcion_sub']);
                                        $band=4;
                                        if($cont_sobreta == 1){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_sobretamaño_alimentar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($cont_sobreta == 2){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_sobretamaño_apilar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                        $paso = 0;
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'APILAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_entradas_apilar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_entradas = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'MVTO. X CALIDAD'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                                            $total_entradas_mvto+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_entradas = 2;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'OFICIOS VARIOS'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                            $total_entradas_varios+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_entradas = 3;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'MOLIENDA'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $activdad = utf8_encode($horometro['Descripcion']);
                                        $subactividad = utf8_encode($$horometro['Descripcion_sub']);
                                        $band=5;
                                        if($cont_sobreta == 1){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_sobretamaño_alimentar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($cont_sobreta == 2){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_sobretamaño_apilar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                        $paso = 0;
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'ALIMENTAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_molienda_alimentar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_molienda = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'APILAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?> <td style="vertical-align: middle; text-align: center;">0</td> <?php
                                            $total_molienda_apilar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_molienda = 2;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'STANDBY'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $activdad = utf8_encode($horometro['Descripcion']);
                                        $subactividad = utf8_encode($$horometro['Descripcion_sub']);
                                        $band=6;
                                        if($cont_sobreta == 1){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_sobretamaño_alimentar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($cont_sobreta == 2){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_sobretamaño_apilar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                        $paso = 0;
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'STANDBY'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_standby+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_standby = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////        
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }
                                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                }elseif($band == 3){
                                    $activdad = utf8_encode($horometro['Descripcion']);
                                    $subactividad = utf8_encode($horometro['Descripcion_sub']);
                                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    if($activdad == 'DESPACHO'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'CARGAR DESPACHO'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_cargar_despacho+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_despacho = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'ENTRADAS'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $activdad = utf8_encode($horometro['Descripcion']);
                                        $subactividad = utf8_encode($horometro['Descripcion_sub']);
                                        $band=4;
                                        if($cont_despacho == 1){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_cargar_despacho,1); ?></td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                        $paso = 0;
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'APILAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_entradas_apilar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_entradas = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'MVTO. X CALIDAD'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                                            $total_entradas_mvto+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_entradas = 2;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'OFICIOS VARIOS'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                            $total_entradas_varios+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_entradas = 3;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'MOLIENDA'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $activdad = utf8_encode($horometro['Descripcion']);
                                        $subactividad = utf8_encode($$horometro['Descripcion_sub']);
                                        $band=5;
                                        if($cont_despacho == 1){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_cargar_despacho,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                        $paso = 0;
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'ALIMENTAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_molienda_alimentar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_molienda = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'APILAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?> <td style="vertical-align: middle; text-align: center;">0</td> <?php
                                            $total_molienda_apilar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_molienda = 2;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'STANDBY'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $activdad = utf8_encode($horometro['Descripcion']);
                                        $subactividad = utf8_encode($$horometro['Descripcion_sub']);
                                        $band=6;
                                        if($cont_despacho == 1){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_cargar_despacho,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                        $paso = 0;
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'STANDBY'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_standby+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_standby = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////        
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }
                                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                }elseif($band == 4){
                                    $activdad = utf8_encode($horometro['Descripcion']);
                                    $subactividad = utf8_encode($horometro['Descripcion_sub']);
                                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    if($activdad == 'ENTRADAS'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'APILAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_entradas_apilar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_entradas = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'MVTO. X CALIDAD'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            if($cont_entradas == 1){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php  echo number_format($total_entradas_apilar,1); ?></td><?php
                                             }elseif($cont_entradas == 0){
                                                ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                                            }
                                            $total_entradas_mvto+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_entradas = 2;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'OFICIOS VARIOS'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            if($cont_entradas == 1){
                                                ?>
                                                <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_entradas_apilar,1); ?></td>
                                                <td style="vertical-align: middle; text-align: center;">0</td>
                                                <?php
                                            }elseif($cont_entradas == 2){
                                                ?><td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_entradas_mvto,1); ?></td><?php
                                            }
                                            $total_entradas_varios+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_entradas = 3;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'MOLIENDA'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $activdad = utf8_encode($horometro['Descripcion']);
                                        $subactividad = utf8_encode($horometro['Descripcion_sub']);
                                        $band=5;
                                        if($cont_entradas == 1){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_entradas_apilar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($cont_entradas == 2){
                                            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_entradas_mvto,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($cont_entradas == 3){
                                            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_entradas_varios,1); ?></td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                        $paso = 0;
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'ALIMENTAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_molienda_alimentar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_molienda = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'APILAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?> <td style="vertical-align: middle; text-align: center;">0</td> <?php
                                            $total_molienda_apilar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_molienda = 2;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'STANDBY'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $activdad = utf8_encode($horometro['Descripcion']);
                                        $subactividad = utf8_encode($$horometro['Descripcion_sub']);
                                        $band=6;
                                        if($cont_entradas == 1){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_entradas_apilar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($cont_entradas == 2){
                                            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_entradas_mvto,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($cont_entradas == 3){
                                            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_entradas_varios,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                        $paso = 0;
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'STANDBY'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_standby+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_standby = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////        
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }
                                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                }elseif($band == 5){
                                    $activdad = utf8_encode($horometro['Descripcion']);
                                    $subactividad = utf8_encode($horometro['Descripcion_sub']);
                                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    if($activdad == 'MOLIENDA'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'ALIMENTAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_molienda_alimentar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_molienda = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($subactividad == 'APILAR'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            if($cont_molienda == 1){ 
                                                ?> <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_molienda_alimentar,1); ?></td> <?php
                                            }elseif ($cont_molienda == 0) {
                                                ?> <td style="vertical-align: middle; text-align: center;">0</td> <?php
                                            }
                                            $total_molienda_apilar+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_molienda = 2;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }elseif($activdad == 'STANDBY'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        $activdad = utf8_encode($horometro['Descripcion']);
                                        $subactividad = utf8_encode($horometro['Descripcion_sub']);
                                        $band=6;
                                        if($cont_molienda == 1){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_molienda_alimentar,1); ?></td>
                                            <td style="vertical-align: middle; text-align: center;">0</td>
                                            <?php
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        }elseif($cont_molienda == 2){
                                            ?>
                                            <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_molienda_apilar,1); ?></td>
                                            <?php
                                        }
                                        $paso = 0;
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'STANDBY'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_standby+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_standby = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////        
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }
                                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                }elseif($band == 6){
                                    $activdad = utf8_encode($horometro['Descripcion']);
                                    $subactividad = utf8_encode($horometro['Descripcion_sub']);
                                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    if($activdad == 'STANDBY'){
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                        if($subactividad == 'STANDBY'){
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                            $total_standby+=$horometro['total_horas'];
                                            $total_horas+=$horometro['total_horas'];
                                            $cont_standby = 1;
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////        
                                        }
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }
                                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                                }
                            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                            }
                            //echo $band;
                            ///FIN VARIABLES BAND
                            if($band == 0){
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
                            }elseif($band == 1){
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
                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_room_alimentar,1); ?></td>
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
                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_room_apilar,1); ?></td>
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
                            }elseif($band == 2){
                                if($cont_sobreta == 0){
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
                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_sobretamaño_alimentar,1); ?></td>
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
                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_sobretamaño_apilar,1); ?></td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }
                            }elseif($band == 3){
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
                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_cargar_despacho,1); ?></td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }
                            }elseif($band == 4){
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
                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_entradas_apilar,1); ?></td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }elseif($cont_entradas == 2){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_entradas_mvto,1); ?></td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }elseif($cont_entradas == 3){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_entradas_varios,1); ?></td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }
                            }elseif($band == 5){
                                if($cont_molienda == 0){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }elseif($cont_molienda == 1){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_molienda_alimentar,1); ?></td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }elseif($cont_molienda == 2){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_molienda_apilar,1); ?></td>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }
                            }elseif($band == 6){
                                if($cont_standby == 0){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;">0</td>
                                    <?php
                                }elseif($cont_standby == 1){
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;"><?php echo number_format($total_standby,1); ?></td>
                                    <?php
                                }
                            }
                            ?>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            var table = $('#example1').DataTable( {
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                //"dom": '<"top"i>rt<"bottom"flp><"clear">',
                //"dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
                //"paging":   true,
                /*"language": {
                    "decimal": ",",
                    "thousands": "."
                },
                "language": {
                    "lengthMenu": "Display _MENU_ records per page",
                    "zeroRecords": "Nothing found - sorry",
                    "info": "Showing page _PAGE_ of _PAGES_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(filtered from _MAX_ total records)"
                },*/
                "ordering": true,
                "info":     true,
                stateSave: true,
                scrollY:        '50vh',
                "scrollX": true,
                "scrollCollapse": true,
                "paging":         true
            } );
        })
        </script>
</body>
</html>