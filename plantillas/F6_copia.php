<?php
session_start();
include('../modelo/conexion.php');
if ($_SESSION["logueado"] == TRUE && ($_SESSION["permisoIngresar"] == 'CONSULTAS_OFICINA' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES')){
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
        if($_POST['fecha_inicio']>$_POST['fecha_fin'])
        {   echo '<script type="text/javascript"> alert("Fecha Inicial Mayor que Fecha Final");</script>';
        }elseif($acopio == 1){
            $sql_consulta = "SELECT Actividades.Descripcion AS Actividad, 
                            subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, horometro.total_horas,
                            Equipos.Descripcion AS Maquina, Equipos.Identificacion AS Prefijo, Proveedores.Razonsocial AS Proveedor, 
                            TarifaMaquinaria.Tarifa_Horometro, Tiempos_cargadores_actividad.TM_total, 
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
                                AND Registro_tique_cargadores.estado='3'
                            GROUP BY  horometro.total_horas, horometro.idActividad, Actividades.Descripcion, 
                                      subactividades_cargadores.Descripcion, Destino.Descripcion, Equipos.Descripcion, 
                                      Equipos.Identificacion, Proveedores.Razonsocial, TarifaMaquinaria.Tarifa_Horometro,
                                      Tiempos_cargadores_actividad.TM_total, Clasificacion.Descripcion
                            ORDER BY Destino.Descripcion, Equipos.Descripcion, Actividades.Descripcion, subactividades_cargadores.Descripcion";
        }else{
            $sql_consulta = "SELECT Actividades.Descripcion AS Actividad, 
                            subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, horometro.total_horas,
                            Equipos.Descripcion AS Maquina, Equipos.Identificacion AS Prefijo, Proveedores.Razonsocial AS Proveedor, TarifaMaquinaria.Tarifa_Horometro
                            FROM horometro INNER JOIN
                                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN 
                                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                Proveedores ON Equipos.idPropietario=Proveedores.idProveedor LEFT JOIN 
                                TarifaMaquinaria ON Equipos.idEquipo = TarifaMaquinaria.idEquipo
                            WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                                (Registro_tique_cargadores.id_proveedor = '$empresa') AND Registro_tique_cargadores.id_patio='$acopio' 
                                AND CAST(TarifaMaquinaria.Fecha_Hasta AS date) = '1900-01-01' AND Registro_tique_cargadores.estado='3'
                            GROUP BY  horometro.total_horas, horometro.idActividad, Actividades.Descripcion, 
                                      subactividades_cargadores.Descripcion, Destino.Descripcion, Equipos.Descripcion, 
                                      Equipos.Identificacion, Proveedores.Razonsocial, TarifaMaquinaria.Tarifa_Horometro
                            ORDER BY Actividades.Descripcion, subactividades_cargadores.Descripcion, Destino.Descripcion";
        }
    }
}
$var_acopio = 'acopio';
$count_acopio = 0;

$valor_por_acopio = 0;

$var_maquina = 'maquina';
$count_maquina = 0;

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
        if($var_maquina == 'maquina'){
            $count_maquina++;
            $var_maquina = $d['Maquina']." - ".$d['Prefijo'];
        }elseif($var_maquina == $d['Maquina']." - ".$d['Prefijo']){
            $count_maquina++;
            $var_maquina = $d['Maquina']." - ".$d['Prefijo'];
        }elseif($var_maquina != $d['Maquina']." - ".$d['Prefijo']){
            $Array_maquina[$var_acopio][$var_maquina] = $count_maquina;
            $count_maquina = 1;
            $var_maquina = $d['Maquina']." - ".$d['Prefijo'];
        }
        $valor_por_acopio+= $d['total_horas'];
        $total_corte+= $d['total_horas'];
        $total_corte_count += $d['total_horas'];
    }elseif($var_acopio == $d['Acopio']){
        $count_acopio++;
        /**********************************/
        if($var_maquina == 'maquina'){
            $count_maquina++;
            $var_maquina = $d['Maquina']." - ".$d['Prefijo'];
        }elseif($var_maquina == $d['Maquina']." - ".$d['Prefijo']){
            $count_maquina++;
            $var_maquina = $d['Maquina']." - ".$d['Prefijo'];
        }elseif($var_maquina != $d['Maquina']." - ".$d['Prefijo']){
            $count_acopio++;
            $Array_maquina[$var_acopio][$var_maquina] = $count_maquina;
            $count_maquina = 1;
            $var_maquina = $d['Maquina']." - ".$d['Prefijo'];
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
        if($var_maquina == 'maquina'){
            $count_maquina++;
            $var_maquina = $d['Maquina']." - ".$d['Prefijo'];
        }elseif($var_maquina == $d['Maquina']." - ".$d['Prefijo']){
            $count_maquina++;
            $var_maquina = $d['Maquina']." - ".$d['Prefijo'];
        }elseif($var_maquina != $d['Maquina']." - ".$d['Prefijo']){
            $Array_maquina[$var_acopio][$var_maquina] = $count_maquina;
            $count_maquina = 1;
            $var_maquina = $d['Maquina']." - ".$d['Prefijo'];
        }
        $count_maquina = 0;
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
$Array_maquina[$var_acopio][$var_maquina] = $count_maquina;
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
    <div class="container">
        <center><h1>Informe Tiquetes</h1></center>
        <div class="row">
            <div class="col-sm-12">
                <br>
                <form method="post">
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Empresa:</label>
                            <select id="empresa"  name="empresa" class="form-control">
                                <option value="">--- Seleccione ---</option>
                                <?php 
                                $sql = "SELECT * FROM Proveedores WHERE idProveedor in (SELECT idProveedor FROM  vProveedoresInAgrupacion WHERE Alias='CC') ORDER BY NombreCorto";
                                $result = sqlsrv_query($conn,$sql);
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
                <br><br>
                <?php
                $params = array();
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                $res=sqlsrv_query($conn,$sql_consulta,$params,$options);
                $rows=sqlsrv_num_rows($res);
                if ($rows > 0) 
                { ?>
                    <table class="table table-hover table-condensed table-bordered table-responsive table-striped">
                        <thead>
                            <th>Acopio</th>
                            <th>Maquinaria</th>
                            <th>Proveedor</th>
                            <th>Actividad</th>
                            <th>SubActividad</th>
                            <th>Clasificación</th>
                            <th>Horas</th>
                            <th>TM</th>
                            <th>Tarifa</th>
                            <th>Total</th>
                            <th>Uso</th>
                            <th>Uso General</th>
                        </thead>
                         
                        <?php
                        $paso_acopio = 'acopio';
                        $valor_acopio = 0;
                        $porcentaje = 0;
                        $paso_maquina = 'maquina';
                        $valor_maquina = 0;
                        $paso_act = 'act';
                        $valor_act = 0;
                        $totales_maquina = 0;
                        $totales_acopio = 0; 
                        //$res = sqlsrv_query($conn,$sql_consulta);
                        while($data = sqlsrv_fetch_array($res)){
                            if($paso_acopio == 'acopio'){   ?>
                            <tr>
                                <td rowspan="<?php echo $Array_acopio[$data['Acopio']] ?>"><center><?php echo $data['Acopio']; ?></center></td>
                                <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>"><center><?php echo $data['Maquina']." - ".$data['Prefijo']; ?></center></td>
                                <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>"><center><?php echo utf8_encode($data['Proveedor']); ?></center></td>
                                <td><center><?php echo utf8_encode($data['Actividad']); ?></center></td>
                                <td><center><?php echo utf8_decode($data['SubActividad']); ?></center></td>
                                <td><center><?php echo utf8_decode($data['Clasificacion']); ?></center></td>
                                <td><center><?php echo $data['total_horas']; ?></center></td>
                                <td><center><?php echo $data['TM_total']; ?></center></td>
                                <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>"><center><?php echo number_format($data['Tarifa_Horometro'],0,',','.'); ?></center></td>
                                <td><center><?php $resul = $data['Tarifa_Horometro'] * $data['total_horas']; echo number_format($resul,0,',','.'); ?></center></td>
                                <td><center><?php $uso = $Array_valores_acopio[$data['Acopio']]; $llenar = ($data['total_horas']/$uso)*100; echo number_format($llenar,0,',','.')."%"; ?></center></td>
                                <td rowspan="<?php echo $Array_acopio[$data['Acopio']] ?>"><center><b><?php echo number_format(($Array_total_corte[$data['Acopio']]/$total_corte)*100,0,',','.')."%"; ?></b></center></td>
                            </tr>
                                <?php
                                $totales_maquina += $resul;
                                $totales_acopio += $resul; 
                                $porcentaje += $llenar;
                                $paso_acopio = $data['Acopio'];
                                $paso_maquina = $data['Maquina']." - ".$data['Prefijo'];
                                $paso_act = $data['Actividad'];
                                $valor_maquina += $data['total_horas'];
                                $valor_acopio += $data['total_horas'];
                            }elseif($paso_acopio == $data['Acopio']){
                                if($paso_maquina == $data['Maquina']." - ".$data['Prefijo']){   ?>
                                 <tr>   
                                    <td><center><?php echo utf8_encode($data['Actividad']); ?></center></td>
                                    <td><center><?php echo utf8_decode($data['SubActividad']); ?></center></td>
                                    <td><center><?php echo utf8_decode($data['Clasificacion']); ?></center></td>
                                    <td><center><?php echo $data['total_horas']; ?></center></td>
                                    <td><center><?php echo $data['TM_total']; ?></center></td>
                                    <td><center><?php $resul = $data['Tarifa_Horometro'] * $data['total_horas']; echo number_format($resul,0,',','.'); ?></center></td>
                                    <td><center><?php $uso = $Array_valores_acopio[$data['Acopio']]; $llenar = ($data['total_horas']/$uso)*100; echo number_format($llenar,0,',','.')."%"; ?></center></td>
                                </tr>
                                <?php
                                $totales_maquina += $resul;
                                $totales_acopio += $resul; 
                                $porcentaje += $llenar;
                                $valor_maquina += $data['total_horas'];
                                $valor_acopio += $data['total_horas'];
                                }elseif($paso_maquina != $data['Maquina']." - ".$data['Prefijo']){  ?>
                                    <tr style="background-color: powderblue;">
                                        <td colspan="6"><center><b>SUBTOTAL MAQUINA: <?php echo $paso_maquina; ?></b></center></td>
                                        <td><center><b><?php echo $valor_maquina; ?></b></center></td>
                                        <td></td>
                                        <td><center><b><?php echo number_format($totales_maquina,0,',','.'); ?></b></center></td>
                                        <td><center><b><?php echo number_format($porcentaje,0,',','.')."%"; ?></b></center></td>
                                    </tr>
                                    <tr>
                                        <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>"><center><?php echo $data['Maquina']." - ".$data['Prefijo']; ?></center></td>
                                        <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>"><center><?php echo utf8_encode($data['Proveedor']); ?></center></td>
                                        <td><center><?php echo utf8_encode($data['Actividad']); ?></center></td>
                                        <td><center><?php echo utf8_decode($data['SubActividad']); ?></center></td>
                                        <td><center><?php echo utf8_decode($data['Clasificacion']); ?></center></td>
                                        <td><center><?php echo $data['total_horas']; ?></center></td>
                                        <td><center><?php echo $data['TM_total']; ?></center></td>
                                        <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>"><center><?php echo number_format($data['Tarifa_Horometro'],0,',','.'); ?></center></td>
                                        <td><center><?php $resul = $data['Tarifa_Horometro'] * $data['total_horas']; echo number_format($resul,0,',','.'); ?></center></td>
                                        <td><center><?php $uso = $Array_valores_acopio[$data['Acopio']]; $llenar = ($data['total_horas']/$uso)*100; echo number_format($llenar,0,',','.')."%"; ?></center></td>
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
                                    $porcentaje += $llenar;
                                    $totales_maquina += $resul;
                                    $totales_acopio += $resul; 
                                }
                            }elseif($paso_acopio != $data['Acopio']){   ?>
                            <tr style="background-color: powderblue;">
                                <td colspan="6"><center><b>SUBTOTAL MAQUINA: <?php echo $paso_maquina; ?></b></center></td>
                                <td><center><b><?php echo $valor_maquina; ?></b></center></td>
                                <td></td>
                                <td><center><b><?php echo number_format($totales_maquina,0,',','.'); ?></b></center></td>
                                <td><center><b><?php echo number_format($porcentaje,0,',','.')."%"; ?></b></center></td>
                            </tr>
                            <tr style="background-color: white; border:0px;">
                                <td colspan="12">&nbsp;</td>
                            </tr>
                            <tr style="background-color: yellow;">
                                <td colspan="7"><center><b>SUBTOTAL ACOPIO: <?php echo $paso_acopio; ?></b></center></td>
                                <td><center><b><?php echo $valor_acopio; ?></b></center></td>
                                <td></td>
                                <td><center><b><?php echo number_format($totales_acopio,0,',','.'); ?></b></center></td>
                                <td><center><b>100%</b></center></td>
                            </tr>
                            
                            <tr>
                                <td rowspan="<?php echo $Array_acopio[$data['Acopio']] ?>"><center><?php echo $data['Acopio']; ?></center></td>
                                <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>"><center><?php echo $data['Maquina']." - ".$data['Prefijo']; ?></center></td>
                                <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>"><center><?php echo utf8_encode($data['Proveedor']); ?></center></td>
                                <td><center><?php echo utf8_encode($data['Actividad']); ?></center></td>
                                <td><center><?php echo utf8_decode($data['SubActividad']); ?></center></td>
                                <td><center><?php echo utf8_decode($data['Clasificacion']); ?></center></td>
                                <td><center><?php echo $data['total_horas']; ?></center></td>
                                <td><center><?php echo $data['TM_total']; ?></center></td>
                                <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>"><center><?php echo number_format($data['Tarifa_Horometro'],0,',','.'); ?></center></td>
                                <td><center><?php $resul = $data['Tarifa_Horometro'] * $data['total_horas']; echo number_format($resul,0,',','.'); ?></center></td>
                                <td><center><?php $uso = $Array_valores_acopio[$data['Acopio']]; $llenar = ($data['total_horas']/$uso)*100; echo number_format($llenar,0,',','.')."%"; ?></center></td>
                                <td rowspan="<?php echo $Array_acopio[$data['Acopio']] ?>"><center><b><?php echo number_format(($Array_total_corte[$data['Acopio']]/$total_corte)*100,0,',','.')."%"; ?></center></td>
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
                                $porcentaje += $llenar;
                                $totales_maquina += $resul;
                                $totales_acopio += $resul; 
                            }
                        }   ?>
                        <tr style="background-color: powderblue;">
                            <td colspan="6"><center><b>SUBTOTAL MAQUINA: <?php echo $paso_maquina; ?></b></center></td>
                            <td><center><b><?php echo $valor_maquina; ?></b></center></td>
                            <td></td>
                            <td><center><b><?php echo number_format($totales_maquina,0,',','.'); ?></b></center></td>
                            <td><center><b><?php echo number_format($porcentaje,0,',','.')."%"; ?></b></center></td>
                        </tr>
                        <tr style="background-color: yellow;">
                            <td colspan="7"><center><b>SUBTOTAL ACOPIO: <?php echo $paso_acopio; ?></b></center></td>
                            <td><center><b><?php echo $valor_acopio; ?></b></center></td>
                            <td></td>
                            <td><center><b><?php echo number_format($totales_acopio,0,',','.'); ?></b></center></td>
                            <td><center><b>100%</b></center></td>
                        </tr>
                    </table>
                <?php
                }?>
            </div>
        </div>
    </div>
</body>
</html>