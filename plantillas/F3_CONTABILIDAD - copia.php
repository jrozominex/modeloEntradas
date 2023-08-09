<?php 
include('../modelo/conexion.php');
$sql_consulta = "";
$res_consulta = "";
if (isset($_POST['buscar'])){

    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $empresa = $_POST['empresa'];
    $acopio = $_POST['acopio'];
    if($_POST['fecha_inicio']>$_POST['fecha_fin'])
    {   echo '<script type="text/javascript"> alert("Fecha Inicial Mayor que Fecha Final");</script>';
    }elseif($acopio == 1){
        /*$sql_consulta = "SELECT horometro.id_horometro, horometro.id_registro, horometro.horometro_final, horometro.total_horas, horometro.idActividad,
                horometro.fecha_registro_horometro, Actividades.Descripcion AS Actividad, SubActividades_cargadores.Descripcion as SubActividad,
                Destino.Descripcion AS Acopio, Equipos.Descripcion AS Cargador
                FROM horometro
                    LEFT JOIN Actividades ON horometro.idActividad = Actividades.idActividad
                    LEFT JOIN SubActividades_cargadores ON horometro.idSubActividad=SubActividades_cargadores.idSubActividad
                    INNER JOIN Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro
                    INNER JOIN Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino
                    INNER JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo
                WHERE CAST(horometro.fecha_registro_horometro as date) BETWEEN '$fecha_inicio' AND '$fecha_fin'
                AND Registro_tique_cargadores.id_proveedor='$empresa'
                ORDER BY Registro_tique_cargadores.id_patio DESC";
                */
        $sql_consulta = "SELECT Actividades.Descripcion AS Actividad, 
                        subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, horometro.total_horas
                        FROM horometro left JOIN
                            Actividades ON horometro.idActividad = Actividades.idActividad left JOIN
                            subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                            Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                            Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino
                        WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                            (Registro_tique_cargadores.id_proveedor = '$empresa')
                        GROUP BY  horometro.total_horas, horometro.idActividad, Actividades.Descripcion, 
                                             subactividades_cargadores.Descripcion, Destino.Descripcion
                        ORDER BY Actividades.Descripcion, subactividades_cargadores.Descripcion, Destino.Descripcion ASC";

        $sql_consulta_distinct = "SELECT distinct(Destino.Descripcion) AS Acopio 
                                FROM horometro
                            INNER JOIN Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                            Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino
                                 WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                            (Registro_tique_cargadores.id_proveedor = '$empresa')"; 

        //$sql_consulta_copia = $sql_consulta;
    }else{
        /*$sql_consulta = "SELECT horometro.id_horometro, horometro.id_registro, horometro.horometro_final, horometro.total_horas, horometro.idActividad,
                horometro.fecha_registro_horometro, Actividades.Descripcion AS Actividad, SubActividades_cargadores.Descripcion as SubActividad,
                Destino.Descripcion AS Acopio
                FROM horometro
                    LEFT JOIN Actividades ON horometro.idActividad = Actividades.idActividad
                    LEFT JOIN SubActividades_cargadores ON horometro.idSubActividad=SubActividades_cargadores.idSubActividad
                    INNER JOIN Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro
                    INNER JOIN Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino
                WHERE CAST(horometro.fecha_registro_horometro as date) BETWEEN '$fecha_inicio' AND '$fecha_fin'
                AND Registro_tique_cargadores.id_proveedor='$empresa' AND Registro_tique_cargadores.id_patio='$acopio'
                ORDER BY Registro_tique_cargadores.id_patio DESC";*/
        $sql_consulta = "SELECT Actividades.Descripcion AS Actividad, 
                        subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, horometro.total_horas
                        FROM horometro INNER JOIN
                            Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                            subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                            Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                            Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino
                        WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                            (Registro_tique_cargadores.id_proveedor = '$empresa') AND Registro_tique_cargadores.id_patio='$acopio'
                        GROUP BY  horometro.total_horas, horometro.idActividad, Actividades.Descripcion,
                                             subactividades_cargadores.Descripcion, Destino.Descripcion
                        ORDER BY Actividades.Descripcion, subactividades_cargadores.Descripcion, Destino.Descripcion";
        //$sql_consulta_copia = $sql_consulta;

        $sql_consulta_distinct = "SELECT distinct(Destino.Descripcion) AS Acopio 
                                FROM horometro
                            INNER JOIN Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                            Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino
                                 WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                            (Registro_tique_cargadores.id_proveedor = '$empresa') AND (Registro_tique_cargadores.id_patio='$acopio')"; 
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <REPORTE CARGADORES></REPORTE CARGADORES> 
        <title>REPORTE CARGADORES -  WILSON</title>
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
    <div class="container">
        <?php
        $paso_acopio = "a";
        $num_acopio = 0;
        $paso_maquina = "m";
        $num_maquina = 0;
        $Array = Array();
        $Array_num = 0;
        $res = sqlsrv_query($conn,$sql_consulta_distinct);
        //$res1 = $res
        while($a = sqlsrv_fetch_array($res)){
            if($paso_acopio == 'a'){
                $num_acopio++;
                //$Array[$a['Acopio']][$num_acopio] = $a['Cargador'];
                $Array_acopio[$Array_num] = $a['Acopio'];
                $paso_acopio = $a['Acopio'];
                //$paso_maquina = $a['Cargador'];
                $Array_num++;
            }elseif($paso_acopio == $a['Acopio']){
               //if($paso_maquina != $a['Cargador']){
                  //  $num_acopio = 1;
                    //$paso_maquina = $a['Cargador'];
                    //$Array[$a['Acopio']][$num_acopio] = $a['Cargador'];
                    //$Array_acopio[$Array_num] = $a['Acopio'];
                    //$Array_num++;
                //}
            }elseif($paso_acopio != $a['Acopio']){
                $num_acopio = 1;
                //$paso_maquina = 'm';
                //$Array[$a['Acopio']][$num_acopio] = $a['Cargador'];
                $Array_acopio[$Array_num] = $a['Acopio'];
                $paso_acopio = $a['Acopio'];
               // $paso_acopio = 'a';
                //$paso_maquina = $a['Cargador'];
                $Array_num++;
            }
        }

        if($sql_consulta != ''){
            //print_r($Array);
            //echo "<br>";
            //print_r($Array_acopio);
        }
        //echo $num_acopio;
        ?>
        <div class="row">
            <div class="col-sm-12">
                <br>
                <form method="post">
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Empresa:</label>
                            <select id="empresa"  name="empresa" class="form-control">
                                <option>--- Seleccione ---</option>
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
                <br>
                <!--<table border="0" class="table table-hover table-condensed table-bordered table-responsive table-striped">
                    <tr class="negrita">
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center>TARIFA</center></td>
                        <?php 
                        if($sql_consulta != ''){
                            for ($i=0; $i < count($Array_acopio) ; $i++) { 
                                ?>
                                <td colspan="4" style="background-color:#FDFEFE" style="width: 30px"><center>$$$$$$</center></td>
                                <?php
                            }
                        }
                        ?>
                    </tr>
                    <tr class="negrita">
                        <td style="background-color:#FDFEFE" style="width: 30px"><center></center></td>
                        <td style="background-color:#F7DC6F" style="width: 30px"><center>ACOPIO</center></td>
                        <?php 
                        if($sql_consulta != ''){
                            for ($i=0; $i < count($Array_acopio) ; $i++) { 
                                ?>
                                <td colspan="4" style="background-color:#FDFEFE" style="width: 30px"><center><?php echo $Array_acopio[$i]; ?></center></td>
                                <?php
                            }
                        }
                        ?>
                    </tr>
                    <tr class="negrita">
                        <td colspan="2" style="background-color:#FDFEFE" style="width: 30px"><center></center></td>
                        <?php 
                        if($sql_consulta != ''){
                            $sumas = count($Array_acopio)*4;
                            $pesos = 1;
                            for ($i=0; $i < $sumas ; $i++) { 
                                if($pesos != 4){
                                ?>
                                    <td style="width: 20px"><center>SUMA</center></td>
                                <?php
                                $pesos++;
                                }else{
                                    ?>
                                    <td rowspan="2" style="width: 40px"><center>$</center></td>
                                    <?php
                                    $pesos = 1;
                                }
                            }
                        }
                        ?>
                    </tr>
                    <tr class="negrita">
                        <td style="width: 30px"><center>ACTIVIDAD</center></td>
                        <td style="width: 40px"><center>SUBACTIVIDAD</center></td>
                        <?php 
                        if($sql_consulta != ''){
                            $sumas = count($Array_acopio)*3;
                            $pesos = 1;
                            for ($i=0; $i < $sumas ; $i++) { 
                                if($pesos == 1){
                                ?>
                                    <td style="width: 40px"><center>TM</center></td>
                                <?php
                                $pesos++;
                                }elseif($pesos == 2){
                                    ?>
                                    <td style="width: 40px"><center>$ x TM</center></td>
                                    <?php
                                    $pesos++;
                                }elseif($pesos == 3){
                                    ?>
                                    <td style="width: 40px"><center>HORAS</center></td>
                                    <?php
                                    $pesos = 1;
                                }
                            }
                        }
                        ?>
                    </tr>
                </table>-->
                <table border="0" class="table table-hover table-condensed table-bordered table-responsive table-striped">
                    <thead>
                        <th>Acopio</th>
                        <th>Tarifa</th>
                        <th>Actividad</th>
                        <th>SubActividad</th>
                        <th>TM</th>
                        <th>$ x TM</th>
                        <th>Horas</th>
                        <th>$</th>
                    </thead>
                    <?php
                    $var_act = 'Actividad';
                    $var_actividad = 0;
                    $var_subact = 'subact';
                    $var_var = 0;
                    $var_var2 = 0;
                    $var_acopio = 'acopio';

                    if($_POST['acopio'] == 1){
                        $sql_consulta_copia = "SELECT Actividades.Descripcion AS Actividad,
                            subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, SUM(horometro.total_horas) AS total_horas
                            FROM horometro INNER JOIN
                                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino
                            WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND
                                (Registro_tique_cargadores.id_proveedor = '$empresa')
                            GROUP BY  horometro.idActividad, Actividades.Descripcion,
                                                 subactividades_cargadores.Descripcion, Destino.Descripcion
                            ORDER BY Destino.Descripcion, Actividades.Descripcion, subactividades_cargadores.Descripcion";
                    }else{
                        $sql_consulta_copia = "SELECT Actividades.Descripcion AS Actividad,
                            subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, SUM(horometro.total_horas) AS total_horas
                            FROM horometro INNER JOIN
                                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino
                            WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND
                                (Registro_tique_cargadores.id_proveedor = '$empresa') AND (Registro_tique_cargadores.id_patio='$acopio')
                            GROUP BY  horometro.idActividad, Actividades.Descripcion,
                                                 subactividades_cargadores.Descripcion, Destino.Descripcion
                            ORDER BY Destino.Descripcion, Actividades.Descripcion, subactividades_cargadores.Descripcion";
                    }
                    $q = $sql_consulta_copia;
                    $r = sqlsrv_query($conn,$sql_consulta);
                    while($d = sqlsrv_fetch_array($r)){
                        /************************** ACOPIOS ******************/
                        if($var_acopio == 'acopio'){
                            $var_var++;
                            $var_acopio = $d['Acopio'];
                        }elseif($var_acopio == $d['Acopio']){

                            $var_var++;
                        }elseif($var_acopio != $d['Acopio']){
                            $Array_acopio[$var_acopio] = $var_var;
                            //$i++;
                            $var_var = 1;
                            $var_acopio = $d['Acopio'];
                        }
                        /************************** ACTIVIDADES ******************/
                        if($var_act == 'Actividad'){
                            $var_actividad++;
                            $var_act = utf8_encode($d['Actividad']);
                        }elseif($var_act == utf8_encode($d['Actividad'])){
                            $var_actividad++;

                        }elseif($var_act != $d['Actividad']){
                            //echo $d['Actividad'].";";
                            $Array_act[$d['Acopio']][$var_act] = $var_actividad;
                            //$i++;
                            $var_actividad = 1;
                            $var_act = utf8_encode($d['Actividad']);
                        }
                    }
                    $Array_acopio[$var_acopio] = $var_var;
                    //print_r($Array_acopio);
                    //echo "<br>";
                    $Array_act[$var_acopio][$var_act] = $var_var;
                    //print_r($Array_act);
                    $var_act = 'Actividad';
                    $var_actividad = 0;
                    $var_subact = 'subact';
                    $var_var = 0;
                    $var_var2 = 0;
                    $var_acopio = 'acopio';
                    $total_hours = 0;
                    $total_total = 0;
                    $res_copia = sqlsrv_query($conn,$sql_consulta_copia);
                    while($data = sqlsrv_fetch_array($res_copia)){
                        ?>
                        <tr>
                            <?php 
                            if($var_acopio == 'acopio'){
                                $total_hours += $data['total_horas'];
                                ?>
                                <td rowspan="<?php echo $Array_acopio[$data['Acopio']]; ?>"><center><?php echo utf8_encode($data['Acopio']);?></center></td>
                                <td rowspan="<?php echo $Array_acopio[$data['Acopio']]; ?>"><center> TARIFA</center></td>
                                <td rowspan="<?php echo $Array_act[$data['Acopio']][$data['Actividad']]; ?>"><?php echo utf8_encode($data['Actividad']); ?></td>
                                <td><?php echo utf8_encode($data['SubActividad']); ?></td>
                                <td>TM</td>
                                <td>TM x Tarifa</td>
                                <td><?php echo $data['total_horas']; ?></td>
                                <td>Total</td>
                                <?php
                                $var_acopio = $data['Acopio'];
                                $var_act = utf8_encode($data['Actividad']);
                            }elseif($var_acopio == $data['Acopio']){
                                if($var_act != utf8_encode($data['Actividad'])){
                                    ?>
                                    <td rowspan="<?php echo $Array_act[$data['Acopio']][utf8_encode($data['Actividad'])]; ?>"><center><?php echo utf8_encode($data['Actividad']);?></center></td>
                                    <?php
                                }
                                ?>
                                <td><?php echo utf8_encode($data['SubActividad']); ?></td>
                                <td>TM</td>
                                <td>TM x Tarifa</td>
                                <td><?php echo $data['total_horas']; ?></td>
                                <td>Total</td>
                                <?php
                                $var_act = utf8_encode($data['Actividad']);
                                $total_hours += $data['total_horas'];
                            }elseif($var_acopio != $data['Acopio']){
                                ?>
                                </tr>
                                <tr style="background-color: powderblue;">
                                    <td colspan="6"><center><b>SUBTOTAL ACOPIO: <?php echo $var_acopio; ?></b></center></td>
                                    <td><?php echo $total_hours; ?></td>
                                    <td>0</td>
                                </tr>
                                <?php 
                                $total_total += $total_hours;
                                $total_hours = 0; 
                                ?>
                                <td rowspan="<?php echo $Array_acopio[$data['Acopio']]; ?>"><center><?php echo utf8_encode($data['Acopio']);?></center></td>
                                <td rowspan="<?php echo $Array_acopio[$data['Acopio']]; ?>"><center>TARIFA</center></td>
                                <td rowspan="<?php echo $Array_act[$data['Acopio']][utf8_encode($data['Actividad'])]; ?>"><center><?php echo utf8_encode($data['Actividad']);?></center></td>
                                <td><?php echo utf8_encode($data['SubActividad']); ?></td>
                                <td>TM</td>
                                <td>TM x Tarifa</td>
                                <td><?php echo $data['total_horas']; ?></td>
                                <td>Total</td>
                                <?php
                                $var_acopio = $data['Acopio'];
                                $var_act = utf8_encode($data['Actividad']);
                                $total_hours += $data['total_horas'];
                            }
                            ?>
                        </tr>

                        <?php
                        /*if($var_act == 'act'){
                        ?>
                        <tr>
                            <td rowspan="<?php if($data['Actividad'] == 'DESPACHO'){ echo 1; }else{  echo 2;  } ?>"><?php echo utf8_encode($data['Actividad']); ?></td>
                            <td><?php echo utf8_encode($data['SubActividad']); ?></td>
                            <?php 
                            for ($i=0; $i < count($Array_acopio) ; $i++){
                                if($Array_acopio[$i] == $data['Acopio']){
                                ?>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo $data['total_horas']; ?></td>
                                    <td></td>
                            <?php
                                $var_acopio = $Array_acopio[$i];
                                }else{
                                   ?>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                   <?php 
                                }
                            }
                        $var_act = $data['Actividad'];
                        $var_subact = $data['SubActividad'];
                        }elseif($var_act == $data['Actividad'] && $var_subact == $data['SubActividad']){
                            for ($i=0; $i < count($Array_acopio) ; $i++) {
                                if($var_acopio == $Array_acopio[$i]){
                                    if($Array_acopio[$i] == $data['Acopio']){
                                    ?>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $data['total_horas']; ?></td>
                                        <td></td>
                                <?php
                                    $var_acopio = $Array_acopio[$i+1];
                                break;
                                   // $var_acopio = $Array_acopio[$i];
                                    }else{
                                       ?>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                       <?php 
                                       $var_acopio = $Array_acopio[$i+1];
                                    }
                                }else{
                                    ?>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                   <?php
                                   $var_acopio = $Array_acopio[$i+1];   
                                }
                            }
                        }elseif($var_act == $data['Actividad'] && $var_subact != $data['SubActividad']){
                            ?>
                        </tr>
                        <tr>
                            <td><?php echo utf8_encode($data['SubActividad']); ?></td>
                            <?php 
                            for ($i=0; $i < count($Array_acopio) ; $i++) {
                                if($Array_acopio[$i] == $data['Acopio']){
                                ?>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo $data['total_horas']; ?></td>
                                    <td></td>;
                            <?php
                                $var_acopio = $Array_acopio[$i];
                                break;
                                
                                }else{
                                 ?>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                       <?php 
                                }
                            }
                            $var_subact = $data['SubActividad'];
                        }elseif($var_act != $data['Actividad']){
                            ?>
                        </tr>
                        <tr>
                            <td rowspan="<?php if($data['Actividad'] == 'DESPACHO'){ echo 1; }else{  echo 2;  } ?>"><?php echo utf8_encode($data['Actividad']); ?></td>
                            <td><?php echo utf8_encode($data['SubActividad']); ?></td>
                            <?php 
                            for ($i=0; $i < count($Array_acopio) ; $i++) { 
                                if($Array_acopio[$i] == $data['Acopio']){
                                ?>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo $data['total_horas']; ?></td>
                                    <td></td>
                            <?php
                                $var_acopio = $Array_acopio[$i];
                                break;
                                }else{
                                   ?>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                   <?php 
                                }
                            }
                        $var_act = $data['Actividad'];
                        $var_subact = $data['SubActividad'];
                        }*/
                    }// cierre while
                    $total_total += $total_hours;
                    //echo $var_acopio;
                    ?>
                    <tr style="background-color: powderblue;">
                        <td colspan="6"><center><b>SUBTOTAL ACOPIO: <?php echo $var_acopio; ?></b></center></td>
                        <td><?php echo $total_hours; ?></td>
                        <td>0</td>
                    </tr>
                    <tr style="background-color: green;">
                        <td colspan="6"><center><b>TOTAL: </b></center></td>s
                        <td><?php echo $total_total; ?></td>
                        <td>0</td>
                    </tr>
                    <!--<tr class="negrita">
                        <td style="width: 30px"><center>DESPACHO</center></td>
                        <td style="width: 30px">CARGAR DESPACHO</td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                    </tr>
                    <tr class="negrita">
                        <td rowspan="2" style="width: 30px"><center>CLASIFICAR ROOM</center></td>
                        <td style="width: 30px">ALIMENTAR</td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                    </tr>
                    <tr class="negrita">
                        <td style="width: 30px">APILAR</td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                    </tr>
                    <tr class="negrita">
                        <td rowspan="2" style="width: 30px"><center>CLASIFICAR SOBRETAMAÑO</center></td>
                        <td style="width: 30px">ALIMENTAR</td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                    </tr>
                    <tr class="negrita">
                        <td style="width: 30px">APILAR</td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                    </tr>
                    <tr class="negrita">
                        <td style="width: 30px"><center>VARIOS</center></td>
                        <td style="width: 30px">TRABAJOS MECANICOS</td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                    </tr>
                    <tr class="negrita">
                        <td rowspan="2" style="width: 30px"><center>MOLIENDA</center></td>
                        <td style="width: 30px">ALIMENTAR</td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                    </tr>
                    <tr class="negrita">
                        <td style="width: 30px">APILAR</td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                    </tr>
                    <tr class="negrita">
                        <td rowspan="4" style="width: 30px"><center>ENTRADAS</center></td>
                        <td style="width: 30px">APILAR</td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                    </tr>
                    <tr class="negrita">
                        <td style="width: 30px">MOV.CALIDAD</td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                    </tr>
                    <tr class="negrita">
                        <td style="width: 30px">OFICIOS VARIOS</td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                        <td style="width: 40px"><center></center></td>
                    </tr>-->
                </table>
            </div>
        </div>
    </div>
</body>
</html>


