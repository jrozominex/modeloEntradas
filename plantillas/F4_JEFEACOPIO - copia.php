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
                            subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, SUM(horometro.total_horas) AS total_horas,
                            Equipos.Descripcion AS Maquina, Equipos.Identificacion AS Prefijo, Proveedores.Razonsocial AS Proveedor, TarifaMaquinaria.Tarifa_Horometro
                            FROM horometro INNER JOIN
                                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN 
                                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                Proveedores ON Equipos.idPropietario=Proveedores.idProveedor LEFT JOIN 
                                TarifaMaquinaria ON Equipos.idEquipo = TarifaMaquinaria.idEquipo
                            WHERE (CAST(Registro_tique_cargadores.fecha_apertura_tique AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                                (Registro_tique_cargadores.id_proveedor = '$empresa')  AND CAST(TarifaMaquinaria.Fecha_Hasta AS date) = '1900-01-01'
                                AND Registro_tique_cargadores.estado='3' and horometro.tipo_tarifa<>1
                            GROUP BY  horometro.idActividad, Actividades.Descripcion, subactividades_cargadores.Descripcion, Destino.Descripcion, Equipos.Descripcion, 
                                      Equipos.Identificacion, Proveedores.Razonsocial, TarifaMaquinaria.Tarifa_Horometro
                            ORDER BY Destino.Descripcion, Equipos.Descripcion, Actividades.Descripcion, subactividades_cargadores.Descripcion";
        }else{
            $sql_consulta = "SELECT Actividades.Descripcion AS Actividad, 
                            subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, SUM(horometro.total_horas) AS total_horas,
                            Equipos.Descripcion AS Maquina, Equipos.Identificacion AS Prefijo, Proveedores.Razonsocial AS Proveedor, TarifaMaquinaria.Tarifa_Horometro
                            FROM horometro INNER JOIN
                                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN 
                                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                Proveedores ON Equipos.idPropietario=Proveedores.idProveedor LEFT JOIN 
                                TarifaMaquinaria ON Equipos.idEquipo = TarifaMaquinaria.idEquipo
                            WHERE (CAST(Registro_tique_cargadores.fecha_apertura_tique AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                                (Registro_tique_cargadores.id_proveedor = '$empresa') AND Registro_tique_cargadores.id_patio='$acopio' 
                                AND CAST(TarifaMaquinaria.Fecha_Hasta AS date) = '1900-01-01' AND Registro_tique_cargadores.estado='3' and horometro.tipo_tarifa<>1
                            GROUP BY  Actividades.Descripcion,subactividades_cargadores.Descripcion, Destino.Descripcion, Equipos.Descripcion, 
                                      Equipos.Identificacion, Proveedores.Razonsocial, TarifaMaquinaria.Tarifa_Horometro
                            ORDER BY Destino.Descripcion, Equipos.Descripcion, Actividades.Descripcion, subactividades_cargadores.Descripcion";
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
$tarifa_tempo = 0;
$res = sqlsrv_query($conn,$sql_consulta);
while($d = sqlsrv_fetch_array($res)){
    if($var_acopio == 'LA LEJIA'){
        $tarifa_tempo = $d['Tarifa_Horometro'];
    }
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
        $valor_por_acopio+= number_format($d['total_horas'],1);
        $total_corte+= number_format($d['total_horas'],1);
        $total_corte_count += number_format($d['total_horas'],1);
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
        $valor_por_acopio+= number_format($d['total_horas'],1);
        $total_corte+= number_format($d['total_horas'],1);
        $total_corte_count += number_format($d['total_horas'],1);
    }elseif($var_acopio != $d['Acopio']){
        $Array_total_corte[$var_acopio] = $total_corte_count;
        $Array_valores_acopio[$var_acopio] = $valor_por_acopio;
        $valor_por_acopio = 0;
        $total_corte_count = 0;
        
        $count_acopio++;
        /**********************************/
        $Array_maquina[$var_acopio][$var_maquina] = $count_maquina;
        $count_maquina = 1;
        $var_maquina = $d['Maquina']." - ".$d['Prefijo'];
        /*if($var_maquina == 'maquina'){
            $count_maquina++;
            $var_maquina = $d['Maquina']." - ".$d['Prefijo'];
        }elseif($var_maquina == $d['Maquina']." - ".$d['Prefijo']){
            $count_maquina++;
            $var_maquina = $d['Maquina']." - ".$d['Prefijo'];
        }elseif($var_maquina != $d['Maquina']." - ".$d['Prefijo']){
            $Array_maquina[$var_acopio][$var_maquina] = $count_maquina;
            $count_maquina = 1;
            $var_maquina = $d['Maquina']." - ".$d['Prefijo'];
        }*/
        //$count_maquina = 0;
        $Array_acopio[$var_acopio] = $count_acopio;
        $count_acopio = 1;
        $var_acopio = $d['Acopio'];
        $valor_por_acopio+= number_format($d['total_horas'],1);
        $total_corte+= number_format($d['total_horas'],1);
        $total_corte_count += number_format($d['total_horas'],1);
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
//print_r($Array_maquina); echo "<br>";*/

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
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="../vista/Admin.php"><i class=" fa fa-home fa-sm"></i>Inicio</a></li>
            <li><a href="../vista/consultas.php"><i class=" fa fa-home fa-sm"></i>Consultas</a></li>
            <li><span class="active">Resumen Acopios</span></li>
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
                            <button class="btn btn-success" name="buscar" id="buscar">Buscar</button>
                        </div>
                        <div class="col-sm-4"></div>
                    </div>
                </form>
                <br>
                <?php
                $params = array();
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                $res=sqlsrv_query($conn,$sql_consulta,$params,$options);
                $rows=sqlsrv_num_rows($res);
                if ($rows > 0) 
                {   if($_POST['empresa'] == '0247FA36-ABF5-4A35-9E8B-BE7C574243DE'){

                        $sql_tm = "SELECT Destino.Descripcion AS Acopio, Equipos.Descripcion AS Maquina, Equipos.Identificacion AS Prefijo, 
                                Proveedores.RazonSocial AS Proveedor, TarifaMaquinaria.Tarifa_Toneladas, sum(tiempos_cargadores_actividad.TM_total) as total_horas
                            FROM Registro_tique_cargadores INNER JOIN
                                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                 Proveedores ON Equipos.idPropietario = Proveedores.idProveedor INNER JOIN
                                 tiempos_cargadores_actividad ON Registro_tique_cargadores.id_registro = tiempos_cargadores_actividad.idRegistro LEFT OUTER JOIN
                                 TarifaMaquinaria ON Equipos.idEquipo = TarifaMaquinaria.idEquipo
                            WHERE (CAST(Registro_tique_cargadores.fecha_apertura_tique AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') 
                            AND (Registro_tique_cargadores.id_proveedor = '$empresa') AND 
                                 (Registro_tique_cargadores.id_patio IN ('5BDAFEA5-3288-4917-B4C8-A23D022AAF46')) AND (CAST(TarifaMaquinaria.Fecha_Hasta AS date) = '1900-01-01') AND (Registro_tique_cargadores.estado = '3') AND tiempos_cargadores_actividad.tipo_tarifa=1
                            GROUP BY Destino.Descripcion, Equipos.Descripcion, Equipos.Identificacion, Proveedores.RazonSocial, TarifaMaquinaria.Tarifa_Toneladas
                            ORDER BY Acopio";
                        $var_acopio_lejia = 'acopio';
                        $count_acopio_lejia = 0;

                        $valor_por_acopio_lejia = 0;

                        $var_maquina_lejia = 'maquina';
                        $count_maquina_lejia = 0;

                        $var_act_lejia = 'Actividad';
                        $count_act_lejia = 0;

                        $total_corte_lejia = 0;
                        $total_corte_count_lejia = 0;
                        $res_tm = sqlsrv_query($conn,$sql_tm);
                        while($d1 = sqlsrv_fetch_array($res_tm)){
                            
                            $tarifa_tm = $d1['Tarifa_Toneladas'];

                            if($var_acopio_lejia == 'acopio'){
                                $count_acopio_lejia++;
                                $var_acopio_lejia = $d1['Acopio'];
                                if($var_maquina_lejia == 'maquina'){
                                    $count_maquina_lejia++;
                                    $var_maquina_lejia = $d1['Maquina']." - ".$d1['Prefijo'];
                                }elseif($var_maquina_lejia == $d1['Maquina']." - ".$d1['Prefijo']){
                                    $count_maquina_lejia++;
                                    $var_maquina_lejia = $d1['Maquina']." - ".$d1['Prefijo'];
                                }elseif($var_maquina_lejia != $d1['Maquina']." - ".$d1['Prefijo']){
                                    $Array_maquina_lejia[$var_acopio_lejia][$var_maquina_lejia] = $count_maquina_lejia;
                                    $count_maquina_lejia = 1;
                                    $var_maquina_lejia = $d1['Maquina']." - ".$d1['Prefijo'];
                                }
                                $valor_por_acopio_lejia+= $d1['total_horas'];
                                $total_corte_lejia+= $d1['total_horas'];
                                $total_corte_count_lejia+= $d1['total_horas'];
                            }elseif($var_acopio_lejia == $d1['Acopio']){
                                //$count_acopio_lejia++;
                                if($var_maquina_lejia == 'maquina'){
                                    $count_maquina_lejia++;
                                    $var_maquina_lejia = $d1['Maquina']." - ".$d1['Prefijo'];
                                }elseif($var_maquina_lejia == $d1['Maquina']." - ".$d1['Prefijo']){
                                    //$count_maquina_lejia++;
                                    $var_maquina_lejia = $d1['Maquina']." - ".$d1['Prefijo'];
                                }elseif($var_maquina_lejia != $d1['Maquina']." - ".$d1['Prefijo']){
                                    //$count_acopio_lejia++;
                                    $Array_maquina_lejia[$var_acopio_lejia][$var_maquina_lejia] = $count_maquina_lejia;
                                    $count_maquina_lejia = 1;
                                    $var_maquina_lejia = $d1['Maquina']." - ".$d1['Prefijo'];
                                }
                                $valor_por_acopio_lejia+= $d1['total_horas'];
                                $total_corte_lejia+= $d1['total_horas'];
                                $total_corte_count_lejia += $d1['total_horas'];
                            }elseif($var_acopio_lejia != $d1['Acopio']){
                                $Array_total_corte_lejia[$var_acopio_lejia] = $total_corte_count_lejia;
                                $Array_valores_acopio_lejia[$var_acopio_lejia] = $valor_por_acopio_lejia;
                                $valor_por_acopio_lejia = 0;
                                $total_corte_count_lejia = 0;
                                
                                //$count_acopio_lejia++;
                                $Array_maquina_lejia[$var_acopio_lejia][$var_maquina_lejia] = $count_maquina_lejia;
                                $count_maquina_lejia = 1;
                                $var_maquina_lejia = $d1['Maquina']." - ".$d1['Prefijo'];
                                $Array_acopio_lejia[$var_acopio_lejia] = $count_acopio_lejia;
                                $count_acopio_lejia = 1;
                                $var_acopio_lejia = $d1['Acopio'];
                                $valor_por_acopio_lejia+= $d1['total_horas'];
                                $total_corte_lejia+= $d1['total_horas'];
                                $total_corte_count_lejia += $d1['total_horas'];
                            }
                        }
                        //$count_acopio_lejia++;
                        $Array_acopio_lejia[$var_acopio_lejia] = $count_acopio_lejia;
                        $Array_maquina_lejia[$var_acopio_lejia][$var_maquina_lejia] = $count_maquina_lejia;
                        $Array_act_lejia[$var_acopio_lejia][$var_act_lejia] = $count_act_lejia;
                        $Array_valores_acopio_lejia[$var_acopio_lejia] = $valor_por_acopio_lejia;
                        $Array_total_corte_lejia[$var_acopio_lejia] = $total_corte_count_lejia;
                        //
                        $total_lejia_tempo = $Array_valores_acopio['LA LEJIA']*$tarifa_tempo;
                        $total_lejia_tm = $Array_valores_acopio_lejia['LA LEJIA']*$tarifa_tm;
                        $costo_general = number_format($total_lejia_tempo+$total_lejia_tm,0);
                        //
                        $params1 = array();
                        $options1 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                        $r=sqlsrv_query($conn,$sql_tm,$params1,$options1);
                        $ros=sqlsrv_num_rows($r);
                        if ($ros > 0) 
                        {   ?>
                            <center><h3>COSTO CARGADOR: $<?php echo $costo_general; ?></h3></center><br>
                            <label style="color: red">Costo por Toneladas:</label>
                            <table class="table table-hover table-condensed table-bordered table-responsive table-striped">
                                <thead>
                                    <th>Acopio</th>
                                    <th>Maquinaria</th>
                                    <th>Proveedor</th>
                                    <th>Actividad</th>
                                    <th>SubActividad</th>
                                    <th>TM</th>
                                    <th>Tarifa</th>
                                    <th>Total $$$</th>
                                    <th>Uso</th>
                                    <th>Uso General</th>
                                </thead>
                                <?php
                                $paso_acopio_lejia = 'acopio';
                                $valor_acopio_lejia = 0;
                                $porcentaje_lejia = 0;
                                $paso_maquina_lejia = 'maquina';
                                $valor_maquina_lejia = 0;
                                $paso_act_lejia = 'act';
                                $valor_act_lejia = 0;
                                $totales_maquina_lejia = 0;
                                $totales_acopio_lejia = 0;
                                $llenar_lejia = 0;
                                //$res = sqlsrv_query($conn,$sql_consulta);
                                while($data1 = sqlsrv_fetch_array($r)){
                                    if($paso_acopio_lejia == 'acopio'){   ?>
                                    <tr>
                                        <td rowspan="<?php echo $Array_acopio_lejia[$data1['Acopio']] ?>" style="vertical-align: middle; text-align: center;"><center><?php echo $data1['Acopio']; ?></center></td>
                                        <td rowspan="<?php echo $Array_maquina_lejia[$data1['Acopio']][$data1['Maquina'].' - '.$data1['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo $data1['Maquina']." - ".$data1['Prefijo']; ?></center></td>
                                        <td rowspan="<?php echo $Array_maquina_lejia[$data1['Acopio']][$data1['Maquina'].' - '.$data1['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo utf8_encode($data1['Proveedor']); ?></center></td>
                                        <td><center><?php echo 'DESPACHO'; ?></center></td>
                                        <td><center><?php echo 'DESPACHO'; ?></center></td>
                                        <td><center><?php echo $data1['total_horas']; ?></center></td>
                                        <td rowspan="<?php echo $Array_maquina_lejia[$data1['Acopio']][$data1['Maquina'].' - '.$data1['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo number_format($data1['Tarifa_Toneladas'],0,',','.'); ?></center></td>
                                        <td><center><?php $resul_lejia = $data1['Tarifa_Toneladas'] * $data1['total_horas']; echo '$ '.number_format($resul_lejia,0,',','.'); ?></center></td>
                                        <td><center><?php $uso_lejia = $Array_valores_acopio_lejia[$data1['Acopio']]; $llenar_lejia = ($data1['total_horas']/$uso_lejia)*100; echo number_format($llenar_lejia,0,',','.')."%"; ?></center></td>
                                    <td rowspan="<?php echo $Array_acopio_lejia[$data1['Acopio']] ?>" style="vertical-align: middle; text-align: center;"><center><b><?php echo number_format(($Array_total_corte_lejia[$data1['Acopio']]/$total_corte_lejia)*100,0,',','.')."%"; ?></b></center></td>
                                    </tr>
                                        <?php
                                        $totales_maquina_lejia += $resul_lejia;
                                        $totales_acopio_lejia += $resul_lejia; 
                                        $porcentaje_lejia += $llenar_lejia;
                                        $paso_acopio_lejia = $data1['Acopio'];
                                        $paso_maquina_lejia = $data1['Maquina']." - ".$data1['Prefijo'];
                                        $valor_maquina_lejia += $data1['total_horas'];
                                        $valor_acopio_lejia += $data1['total_horas'];
                                    }elseif($paso_acopio_lejia == $data1['Acopio']){
                                        if($paso_maquina_lejia == $data1['Maquina']." - ".$data1['Prefijo']){   ?>
                                         <tr>   
                                            <td><center><?php echo 'DESPACHO'; ?></center></td>
                                            <td><center><?php echo 'DESPACHO'; ?></center></td>
                                            <td><center><?php echo $data1['total_horas']; ?></center></td>
                                            <td><center><?php $resul_lejia = $data1['Tarifa_Toneladas'] * $data1['total_horas']; echo number_format($resul_lejia,0,',','.'); ?></center></td>
                                            <td><center><?php $uso_lejia = $Array_valores_acopio_lejia[$data1['Acopio']]; $llenar_lejia = ($data1['total_horas']/$uso_lejia)*100; echo number_format($llenar_lejia,0,',','.')."%"; ?></center></td>
                                        </tr>
                                        <?php
                                        $totales_maquina_lejia += $resul_lejia;
                                        $totales_acopio_lejia += $resul_lejia; 
                                        $porcentaje_lejia += $llenar_lejia;
                                        $valor_maquina_lejia += $data1['total_horas'];
                                        $valor_acopio_lejia += $data1['total_horas'];
                                        }elseif($paso_maquina_lejia != $data1['Maquina']." - ".$data1['Prefijo']){  ?>
                                            <tr style="background-color: #CFD9C9;">
                                                <td colspan="4"><center><b>SUBTOTAL MAQUINA: <?php echo $paso_maquina_lejia; ?></b></center></td>
                                                <td><center><b><?php echo $valor_maquina_lejia; ?></b></center></td>
                                                <td></td>
                                                <td><center><b><?php echo number_format($totales_maquina_lejia,0,',','.'); ?></b></center></td>
                                                <td><center><b><?php echo number_format($porcentaje_lejia,0,',','.')."%"; ?></b></center></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="<?php echo $Array_maquina_lejia[$data1['Acopio']][$data1['Maquina'].' - '.$data1['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo $data1['Maquina']." - ".$data1['Prefijo']; ?></center></td>
                                                <td rowspan="<?php echo $Array_maquina_lejia[$data1['Acopio']][$data1['Maquina'].' - '.$data1['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo utf8_encode($data1['Proveedor']); ?></center></td>
                                                <td><center><?php echo 'DESPACHO'; ?></center></td>
                                                <td><center><?php echo 'DESPACHO'; ?></center></td>
                                                <td><center><?php echo $data1['total_horas']; ?></center></td>
                                                <td rowspan="<?php echo $Array_maquina_lejia[$data1['Acopio']][$data1['Maquina'].' - '.$data1['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo number_format($data1['Tarifa_Toneladas'],0,',','.'); ?></center></td>
                                                <td><center><?php $resul_lejia = $data1['Tarifa_Toneladas'] * $data1['total_horas']; echo '$ '.number_format($resul_lejia,0,',','.'); ?></center></td>
                                                <td><center><?php $uso_lejia = $Array_valores_acopio_lejia[$data1['Acopio']]; $llenar_lejia = ($data1['total_horas']/$uso_lejia)*100; echo number_format($llenar_lejia,0,',','.')."%"; ?></center></td>
                                            </tr>
                                            <?php
                                            $totales_maquina_lejia = 0;
                                            $porcentaje_lejia = 0;
                                            $valor_acopio_lejia += $data1['total_horas'];
                                            $valor_maquina_lejia = 0;
                                            //$paso_maquina = $data1['Maquina']." - ".$data1['Prefijo'];
                                            $paso_acopio_lejia = $data1['Acopio'];
                                            $paso_maquina_lejia = $data1['Maquina']." - ".$data1['Prefijo'];
                                            $valor_maquina_lejia += $data1['total_horas'];
                                            $totales_maquina_lejia += $resul_lejia;
                                            $totales_acopio_lejia += $resul_lejia; 
                                        }
                                    }elseif($paso_acopio_lejia != $data1['Acopio']){   ?>
                                    <tr style="background-color: #CFD9C9;">
                                        <td colspan="4"><center><b>SUBTOTAL MAQUINA: <?php echo $paso_maquina_lejia; ?></b></center></td>
                                        <td><center><b><?php echo $valor_maquina_lejia; ?></b></center></td>
                                        <td></td>
                                        <td><center><b><?php echo number_format($totales_maquina_lejia,0,',','.'); ?></b></center></td>
                                        <td><center><b><?php echo number_format($porcentaje_lejia,0,',','.')."%"; ?></b></center></td>
                                    </tr>
                                    <tr style="background-color: #DBDD71;">
                                        <td colspan="5"><center><b>SUBTOTAL ACOPIO: <?php echo $paso_acopio_lejia; ?></b></center></td>
                                        <td><center><b><?php echo $valor_acopio_lejia; ?></b></center></td>
                                        <td></td>
                                        <td><center><b><?php echo number_format($totales_acopio_lejia,0,',','.'); ?></b></center></td>
                                        <td><center><b>100%</b></center></td>
                                    </tr>
                                    <tr style="background-color: white; border:0px;">
                                        <td colspan="10">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="<?php echo $Array_acopio_lejia[$data1['Acopio']] ?>" style="vertical-align: middle; text-align: center;"><center><?php echo $data1['Acopio']; ?></center></td>
                                        <td rowspan="<?php echo $Array_maquina_lejia[$data1['Acopio']][$data1['Maquina'].' - '.$data1['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo $data1['Maquina']." - ".$data1['Prefijo']; ?></center></td>
                                        <td rowspan="<?php echo $Array_maquina_lejia[$data1['Acopio']][$data1['Maquina'].' - '.$data1['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo utf8_encode($data1['Proveedor']); ?></center></td>
                                        <td><center><?php echo 'DESPACHO'; ?></center></td>
                                        <td><center><?php echo 'DESPACHO'; ?></center></td>
                                        <td><center><?php echo $data1['total_horas']; ?></center></td>
                                        <td rowspan="<?php echo $Array_maquina_lejia[$data1['Acopio']][$data1['Maquina'].' - '.$data1['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo number_format($data1['Tarifa_Toneladas'],0,',','.'); ?></center></td>
                                        <td><center><?php $resul_lejia = $data1['Tarifa_Toneladas'] * $data1['total_horas']; echo '$ '.number_format($resul,0,',','.'); ?></center></td>
                                        <td><center><?php $uso_lejia = $Array_valores_acopio[$data1['Acopio']]; $llenar_lejia = ($data1['total_horas']/$uso)*100; echo number_format($llenar_lejia,0,',','.')."%"; ?></center></td>
                                        <td rowspan="<?php echo $Array_acopio_lejia[$data1['Acopio']] ?>" style="vertical-align: middle; text-align: center;"><center><b><?php echo number_format(($Array_total_corte_lejia[$data1['Acopio']]/$total_corte_lejia)*100,0,',','.')."%"; ?></center></td>
                                        <?php
                                        $totales_maquina = 0;
                                        $totales_acopio = 0;
                                        $porcentaje = 0;
                                        $valor_maquina = 0;
                                        $valor_acopio = 0;
                                        $paso_acopio = $data1['Acopio'];
                                        $paso_maquina = $data1['Maquina']." - ".$data1['Prefijo'];
                                        $paso_act = $data1['Actividad'];
                                        $valor_maquina += $data1['total_horas'];
                                        $valor_acopio += $data1['total_horas'];
                                        $porcentaje += $llenar;
                                        $totales_maquina += $resul;
                                        $totales_acopio += $resul; 
                                    }
                                }   ?>
                                <!--<tr style="background-color: #CFD9C9;">
                                    <td colspan="4"><center><b>SUBTOTAL MAQUINA: <?php echo $paso_maquina_lejia; ?></b></center></td>
                                    <td><center><b><?php echo $valor_maquina_lejia; ?></b></center></td>
                                    <td></td>
                                    <td><center><b><?php echo number_format($totales_maquina_lejia,0,',','.'); ?></b></center></td>
                                </tr>-->
                                <tr style="background-color: #DBDD71;">
                                    <td colspan="5"><center><b>SUBTOTAL ACOPIO: <?php echo $paso_acopio_lejia; ?></b></center></td>
                                    <td><center><b><?php echo $valor_acopio_lejia; ?></b></center></td>
                                    <td></td>
                                    <td><center><b><?php echo '$ '.number_format($totales_acopio_lejia,0,',','.'); ?></b></center></td>
                                    <td><b> </b></td>
                                </tr>
                            </table>
                            <?php
                        }
                    }
                    ?>
                    <label style="color: red">Costo por Tiempo:</label>
                    <table class="table table-hover table-condensed table-bordered table-responsive table-striped">
                        <thead>
                            <th>Acopio</th>
                            <th>Maquinaria</th>
                            <th>Proveedor</th>
                            <th>Actividad</th>
                            <th>SubActividad</th>
                            <th>Horas</th>
                            <th>Tarifa</th>
                            <th>Total $$$</th>
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
                                <td rowspan="<?php echo $Array_acopio[$data['Acopio']] ?>" style="vertical-align: middle; text-align: center;"><center><?php echo $data['Acopio']; ?></center></td>
                                <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo $data['Maquina']." - ".$data['Prefijo']; ?></center></td>
                                <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo utf8_encode($data['Proveedor']); ?></center></td>
                                <td><center><?php echo utf8_encode($data['Actividad']); ?></center></td>
                                <td><center><?php echo utf8_decode($data['SubActividad']); ?></center></td>
                                <td><center><?php echo number_format($data['total_horas'],1); ?></center></td>
                                <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo number_format($data['Tarifa_Horometro'],0,',','.'); ?></center></td>
                                <td><center><?php $resul = $data['Tarifa_Horometro'] * number_format($data['total_horas'],1); echo '$ '.number_format($resul,0,',','.'); ?></center></td>
                                <td><center><?php $uso = $Array_valores_acopio[$data['Acopio']]; $llenar = (number_format($data['total_horas'],1)/$uso)*100; echo number_format($llenar,0,',','.')."%"; ?></center></td>
                                <td rowspan="<?php echo $Array_acopio[$data['Acopio']] ?>" style="vertical-align: middle; text-align: center;"><center><b><?php echo number_format(($Array_total_corte[$data['Acopio']]/$total_corte)*100,0,',','.')."%"; ?></b></center></td>
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
                                    <td><center><?php echo number_format($data['total_horas'],1); ?></center></td>
                                    <td><center><?php $resul = $data['Tarifa_Horometro'] * number_format($data['total_horas'],1); echo '$ '.number_format($resul,0,',','.'); ?></center></td>
                                    <td><center><?php $uso = $Array_valores_acopio[$data['Acopio']]; $llenar = (number_format($data['total_horas'],1)/$uso)*100; echo number_format($llenar,0,',','.')."%"; ?></center></td>
                                </tr>
                                <?php
                                $totales_maquina += $resul;
                                $totales_acopio += $resul; 
                                $porcentaje += $llenar;
                                $valor_maquina += $data['total_horas'];
                                $valor_acopio += $data['total_horas'];
                                }elseif($paso_maquina != $data['Maquina']." - ".$data['Prefijo']){  ?>
                                    <tr style="background-color: #CFD9C9;">
                                        <td colspan="4"><center><b>SUBTOTAL MAQUINA: <?php echo $paso_maquina; ?></b></center></td>
                                        <td><center><b><?php echo number_format($valor_maquina,1); ?></b></center></td>
                                        <td></td>
                                        <td><center><b><?php echo '$ '.number_format($totales_maquina,0,',','.'); ?></b></center></td>
                                        <td><center><b><?php echo number_format($porcentaje,0,',','.')."%"; ?></b></center></td>
                                    </tr>
                                    <tr>
                                        <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo $data['Maquina']." - ".$data['Prefijo']; ?></center></td>
                                        <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo utf8_encode($data['Proveedor']); ?></center></td>
                                        <td><center><?php echo utf8_encode($data['Actividad']); ?></center></td>
                                        <td><center><?php echo utf8_decode($data['SubActividad']); ?></center></td>
                                        <td><center><?php echo number_format($data['total_horas'],1); ?></center></td>
                                        <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo number_format($data['Tarifa_Horometro'],0,',','.'); ?></center></td>
                                        <td><center><?php $resul = $data['Tarifa_Horometro'] * number_format($data['total_horas'],1); echo '$ '.number_format($resul,0,',','.'); ?></center></td>
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
                            <tr style="background-color: #CFD9C9;">
                                <td colspan="4"><center><b>SUBTOTAL MAQUINA: <?php echo $paso_maquina; ?></b></center></td>
                                <td><center><b><?php echo number_format($valor_maquina,1); ?></b></center></td>
                                <td></td>
                                <td><center><b><?php echo '$ '.number_format($totales_maquina,0,',','.'); ?></b></center></td>
                                <td><center><b><?php echo number_format($porcentaje,0,',','.')."%"; ?></b></center></td>
                            </tr>
                            <tr style="background-color: #DBDD71;">
                                <td colspan="5"><center><b>SUBTOTAL ACOPIO: <?php echo $paso_acopio; ?></b></center></td>
                                <td><center><b><?php echo number_format($valor_acopio,1); ?></b></center></td>
                                <td></td>
                                <td><center><b><?php echo '$ '.number_format($totales_acopio,0,',','.'); ?></b></center></td>
                                <td><center><b>100%</b></center></td>
                            </tr>
                            <tr style="background-color: white; border:0px;">
                                <td colspan="10">&nbsp;</td>
                            </tr>
                            <tr>
                                <td rowspan="<?php echo $Array_acopio[$data['Acopio']] ?>" style="vertical-align: middle; text-align: center;"><center><?php echo $data['Acopio']; ?></center></td>
                                <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo $data['Maquina']." - ".$data['Prefijo']; ?></center></td>
                                <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo utf8_encode($data['Proveedor']); ?></center></td>
                                <td><center><?php echo utf8_encode($data['Actividad']); ?></center></td>
                                <td><center><?php echo utf8_decode($data['SubActividad']); ?></center></td>
                                <td><center><?php echo number_format($data['total_horas'],1); ?></center></td>
                                <td rowspan="<?php echo $Array_maquina[$data['Acopio']][$data['Maquina'].' - '.$data['Prefijo']]; ?>" style="vertical-align: middle; text-align: center;"><center><?php echo number_format($data['Tarifa_Horometro'],0,',','.'); ?></center></td>
                                <td><center><?php $resul = $data['Tarifa_Horometro'] * number_format($data['total_horas'],1); echo '$ '.number_format($resul,0,',','.'); ?></center></td>
                                <td><center><?php $uso = $Array_valores_acopio[$data['Acopio']]; $llenar = ($data['total_horas']/$uso)*100; echo number_format($llenar,0,',','.')."%"; ?></center></td>
                                <td rowspan="<?php echo $Array_acopio[$data['Acopio']] ?>" style="vertical-align: middle; text-align: center;"><center><b><?php echo number_format(($Array_total_corte[$data['Acopio']]/$total_corte)*100,0,',','.')."%"; ?></center></td>
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
                        <tr style="background-color: #CFD9C9;">
                            <td colspan="4"><center><b>SUBTOTAL MAQUINA: <?php echo $paso_maquina; ?></b></center></td>
                            <td><center><b><?php echo number_format($valor_maquina,1); ?></b></center></td>
                            <td></td>
                            <td><center><b><?php echo '$ '.number_format($totales_maquina,0,',','.'); ?></b></center></td>
                            <td><center><b><?php echo number_format($porcentaje,0,',','.')."%"; ?></b></center></td>
                        </tr>
                        <tr style="background-color: #DBDD71;">
                            <td colspan="5"><center><b>SUBTOTAL ACOPIO: <?php echo $paso_acopio; ?></b></center></td>
                            <td><center><b><?php echo number_format($valor_acopio,1); ?></b></center></td>
                            <td></td>
                            <td><center><b><?php echo '$ '.number_format($totales_acopio,0,',','.'); ?></b></center></td>
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


