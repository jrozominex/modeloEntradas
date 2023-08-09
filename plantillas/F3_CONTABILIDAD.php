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
if (isset($_POST['buscar'])){

    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $empresa = $_POST['empresa'];
    $acopio = $_POST['acopio'];
    if($_POST['fecha_inicio']>$_POST['fecha_fin'])
    {   echo '<script type="text/javascript"> alert("Fecha Inicial Mayor que Fecha Final");</script>';
    }elseif($acopio == 1){
        $sql_consulta = "SELECT Actividades.Descripcion AS Actividad, 
                        subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, horometro.total_horas
                        FROM horometro left JOIN
                            Actividades ON horometro.idActividad = Actividades.idActividad left JOIN
                            subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                            Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                            Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino
                        WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                            (Registro_tique_cargadores.id_proveedor = '$empresa') AND Registro_tique_cargadores.estado='3'
                        GROUP BY  horometro.total_horas, horometro.idActividad, Actividades.Descripcion, 
                                             subactividades_cargadores.Descripcion, Destino.Descripcion
                        ORDER BY Actividades.Descripcion, subactividades_cargadores.Descripcion, Destino.Descripcion ASC";
    }else{
        $sql_consulta = "SELECT Actividades.Descripcion AS Actividad, 
                        subactividades_cargadores.Descripcion AS SubActividad,Destino.Descripcion AS Acopio, horometro.total_horas
                        FROM horometro INNER JOIN
                            Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                            subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                            Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                            Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino
                        WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') AND 
                            (Registro_tique_cargadores.id_proveedor = '$empresa') AND Registro_tique_cargadores.id_patio='$acopio' AND Registro_tique_cargadores.estado='3'
                        GROUP BY  horometro.total_horas, horometro.idActividad, Actividades.Descripcion,
                                             subactividades_cargadores.Descripcion, Destino.Descripcion
                        ORDER BY Actividades.Descripcion, subactividades_cargadores.Descripcion, Destino.Descripcion";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=0.8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="../librerias/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../librerias/alertifyjs/css/alertify.css">
        <link rel="stylesheet" type="text/css" href="../librerias/alertifyjs/css/themes/default.css">

        <script src="../librerias/jquery-3.3.1.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../librerias/estilos.css">
        <script src="../librerias/bootstrap/js/bootstrap.js"></script>
        <script src="../librerias/alertifyjs/alertify.js"></script>
        <script type="text/javascript">
            function calcular(valor,num){
                valor = valor.replace(',','');
                //console.log(valor);
                //document.getElementById(id).value = document.getElementById(id).value.replace('.',',') 
                //var regresar = valor.toString().replace(/\./g,','); 
                var tm = $('#'+num).val();
                var valor_tm = valor/tm;
                //var valor_mostrar = valor_tm+',000';
                var input = document.getElementById(num+num).innerHTML = valor_tm.toLocaleString();
                //console.log(num);
                //alert(input);
            }
        </script>
    </head>
<body>
    <?php include('./header.php'); ?>
    <div class="container">
        <?php
        $paso_acopio = "a";
        $num_acopio = 0;
        $paso_maquina = "m";
        $num_maquina = 0;
        $Array = Array();
        $Array_num = 0;
        ?>
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="../vista/Admin.php"><i class=" fa fa-home fa-sm"></i>Inicio</a></li>
            <li><a href="../vista/consultas.php"><i class=" fa fa-home fa-sm"></i>Consultas</a></li>
            <li><span class="active">Soporte Contabilidad</span></li>
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
                            <button type="" class="btn btn-success" name="buscar" id="buscar">Buscar</button>
                        </div>
                        <div class="col-sm-4"></div>
                    </div>
                </form>
                <br>
                <table border="0" class="table table-hover table-condensed table-bordered table-responsive table-striped">
                    <?php if (isset($_POST['buscar'])){ 
                    $var_act = 'Actividad';
                    $var_actividad = 0;
                    $var_subact = 'subact';
                    $var_var = 0;
                    $var_var2 = 0;
                    $cant_suma = 0;
                    $suma_tarif = 0;
                    $var_acopio = 'acopio';
                        if($_POST['acopio'] == 1){
                           $sql_consulta_copia = "SELECT  Actividades.Descripcion AS Actividad, subactividades_cargadores.Descripcion AS SubActividad, 
                                                Destino.Descripcion AS Acopio, SUM(horometro.total_horas) AS total_horas, avg(TarifaMaquinaria.Tarifa_Horometro) as Tarif_Horo, 
                                                avg(TarifaMaquinaria.Tarifa_Toneladas) as Tarif_TM, avg(TarifaMaquinaria.Iva)as Iva, sum(tiempos_cargadores_actividad.TM_total) as TM
                                            FROM horometro INNER JOIN
                                                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                                                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                                Proveedores ON Equipos.idPropietario=Proveedores.idProveedor LEFT JOIN 
                                                tiempos_cargadores_actividad ON subactividades_cargadores.idSubactividad = tiempos_cargadores_actividad.idSubActividad INNER JOIN
                                                TarifaMaquinaria ON Registro_tique_cargadores.id_maquinaria = TarifaMaquinaria.idEquipo
                                            WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') 
                                            AND (Registro_tique_cargadores.id_proveedor = '$empresa') and TarifaMaquinaria.Fecha_Hasta = '1900-01-01' 
                                            AND Registro_tique_cargadores.estado='3'
                                            GROUP BY  Destino.Descripcion, Actividades.Descripcion, subactividades_cargadores.Descripcion
                                            ORDER BY Acopio, Actividad, SubActividad";
                        }else{

                                $sql_consulta_copia = "SELECT  Actividades.Descripcion AS Actividad, subactividades_cargadores.Descripcion AS SubActividad, 
                                                Destino.Descripcion AS Acopio, SUM(horometro.total_horas) AS total_horas, avg(TarifaMaquinaria.Tarifa_Horometro) as Tarif_Horo, 
                                                avg(TarifaMaquinaria.Tarifa_Toneladas) as Tarif_TM, avg(TarifaMaquinaria.Iva)as Iva, sum(tiempos_cargadores_actividad.TM_total) as TM
                                            FROM horometro INNER JOIN
                                                Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                                                subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
                                                Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
                                                Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                                                Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                                Proveedores ON Equipos.idPropietario=Proveedores.idProveedor LEFT JOIN 
                                                tiempos_cargadores_actividad ON subactividades_cargadores.idSubactividad = tiempos_cargadores_actividad.idSubActividad INNER JOIN
                                                TarifaMaquinaria ON Registro_tique_cargadores.id_maquinaria = TarifaMaquinaria.idEquipo
                                            WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '$fecha_inicio' AND '$fecha_fin') 
                                            AND (Registro_tique_cargadores.id_proveedor = '$empresa') and TarifaMaquinaria.Fecha_Hasta = '1900-01-01' 
                                            AND Registro_tique_cargadores.estado='3' AND (Registro_tique_cargadores.id_patio='$acopio')
                                            GROUP BY Destino.Descripcion, Actividades.Descripcion, subactividades_cargadores.Descripcion
                                            ORDER BY Acopio, Actividad, SubActividad";
                        }
                        $q = $sql_consulta_copia;
                        $params = array();
                        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                        $r=sqlsrv_query($conn,$q,$params,$options);
                        $rows=sqlsrv_num_rows($r);
                        if ($rows > 0){
                        ?>
                        <thead>
                            <th>Acopio</th>
                            <th>Tarifa</th>
                            <th>Actividad</th>
                            <th>SubActividad</th>
                            <th style="width: 3%;">TM</th>
                            <th>Horas</th>
                            <th>$ x Horo</th>
                            <th>$ x TM</th>
                            <!--<th>TOTALIZADO</th>-->
                        </thead>
                        <?php
                        $r = sqlsrv_query($conn,$q);
                        while($d = sqlsrv_fetch_array($r)){
                            /************************** ACOPIOS ******************/
                            if($var_acopio == 'acopio'){
                                $cant_suma++;
                                $suma_tarif += $d['Tarif_Horo'];
                                $var_var++;
                                $var_acopio = $d['Acopio'];
                            }elseif($var_acopio == $d['Acopio']){
                                $cant_suma++;
                                $suma_tarif += $d['Tarif_Horo'];
                                $var_var++;
                            }elseif($var_acopio != $d['Acopio']){
                                $var_tarifa = $suma_tarif/$cant_suma;
                                $Array_tarifa[$var_acopio] = number_format($var_tarifa,0,',',',');
                                $Array_acopio[$var_acopio] = $var_var;
                                $suma_tarif = 0;
                                $cant_suma = 0;
                                $var_var = 1;
                                $var_acopio = $d['Acopio'];
                                $cant_suma++;
                                $suma_tarif += $d['Tarif_Horo'];
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
                        $var_tarifa = $suma_tarif/$cant_suma;
                        $Array_tarifa[$var_acopio] = number_format($var_tarifa,0,',',',');
                        //print_r($Array_tarifa);
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
                        $num = 0;
                        $res_copia = sqlsrv_query($conn,$sql_consulta_copia);
                        while($data = sqlsrv_fetch_array($res_copia)){
                            $TMxTarif = $Array_tarifa[$data['Acopio']]*$data['total_horas'];
                            ?>
                            <tr>
                                <?php
                                if($var_acopio == 'acopio'){
                                    $total_hours += $data['total_horas'];
                                    $num++;
                                    if($num == 11){
                                        $num++;
                                    }
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;" rowspan="<?php echo $Array_acopio[$data['Acopio']]; ?>"><center><?php echo utf8_encode($data['Acopio']);?></center></td>
                                    <td style="vertical-align: middle; text-align: center;" rowspan="<?php echo $Array_acopio[$data['Acopio']]; ?>"><center><?php echo $Array_tarifa[$data['Acopio']]; ?></center></td>
                                    <td style="vertical-align: middle;" rowspan="<?php echo $Array_act[$data['Acopio']][$data['Actividad']]; ?>"><?php echo utf8_encode($data['Actividad']); ?></td>
                                    <td><?php echo utf8_encode($data['SubActividad']); ?></td>
                                    <td><!--<?php echo $data['TM']; ?>--> <input type="number" id="<?php echo $num; ?>" class="input-sm" onkeyup="calcular('<?php echo number_format($TMxTarif,3,'',''); ?>','<?php echo $num; ?>')"></td>
                                    <td><?php echo $data['total_horas']; ?></td>
                                    <td><?php echo number_format($TMxTarif,3,',','.'); ?></td>
                                    <td><p id="<?php echo $num.$num; ?>"></p></td>
                                    <?php
                                    $var_acopio = $data['Acopio'];
                                    $var_act = utf8_encode($data['Actividad']);
                                }elseif($var_acopio == $data['Acopio']){
                                    if($var_act != utf8_encode($data['Actividad'])){
                                        ?>
                                        <td style="vertical-align: middle;" rowspan="<?php echo $Array_act[$data['Acopio']][utf8_encode($data['Actividad'])]; ?>"><?php echo utf8_encode($data['Actividad']);?></td>
                                        <?php
                                        $num++;
                                        if($num == 11){
                                            $num++;
                                        }
                                    }
                                    $num++; 
                                    if($num == 11){
                                        $num++;
                                    }
                                    ?>
                                    <td style="vertical-align: middle;"><?php echo utf8_encode($data['SubActividad']); ?></td>
                                    <td><!--<?php echo $data['TM']; ?>--> <input type="number" id="<?php echo $num; ?>" class="input-sm" onkeyup="calcular('<?php echo number_format($TMxTarif,3,'',''); ?>','<?php echo $num; ?>')"></td>
                                    <td><?php echo $data['total_horas']; ?></td>
                                    <td><?php echo number_format($TMxTarif,3,',','.'); ?></td>
                                    <td><p id="<?php echo $num.$num; ?>"></p></td>
                                    <?php
                                    $var_act = utf8_encode($data['Actividad']);
                                    $total_hours += $data['total_horas'];
                                }elseif($var_acopio != $data['Acopio']){
                                    ?>
                                    </tr>
                                    <tr style="background-color: powderblue;">
                                        <td colspan="5"><center><b>SUBTOTAL ACOPIO: <?php echo $var_acopio; ?></b></center></td>
                                        <td><b><?php echo $total_hours; ?></b></td>
                                        <td><b>0</b></td>
                                        <td><b>0</b></td>
                                    </tr>
                                    <?php 
                                    $total_total += $total_hours;
                                    $total_hours = 0; 
                                    $num++;
                                    if($num == 11){
                                        $num++;
                                    }
                                    ?>
                                    <td style="vertical-align: middle; text-align: center;" rowspan="<?php echo $Array_acopio[$data['Acopio']]; ?>"><center><?php echo utf8_encode($data['Acopio']);?></center></td>
                                    <td style="vertical-align: middle; text-align: center;" rowspan="<?php echo $Array_acopio[$data['Acopio']]; ?>"><center><?php echo $Array_tarifa[$data['Acopio']]; ?></center></td>
                                    <td style="vertical-align: middle;" rowspan="<?php echo $Array_act[$data['Acopio']][utf8_encode($data['Actividad'])]; ?>"><?php echo utf8_encode($data['Actividad']);?></td>
                                    <td><?php echo utf8_encode($data['SubActividad']); ?></td>
                                    <td><!--<?php echo $data['TM']; ?>--> <input type="number" id="<?php echo $num; ?>" class="input-sm" onkeyup="calcular('<?php echo number_format($TMxTarif,3,'',''); ?>','<?php echo $num; ?>')"></td>
                                    <td><?php echo $data['total_horas']; ?></td>
                                    <td><?php echo number_format($TMxTarif,3,',','.'); ?></td>
                                    <td><p id="<?php echo $num.$num; ?>"></p></td>
                                    <?php
                                    $var_acopio = $data['Acopio'];
                                    $var_act = utf8_encode($data['Actividad']);
                                    $total_hours += $data['total_horas'];
                                }
                                ?>
                            </tr>
                            <?php
                        }// cierre while
                        $total_total += $total_hours;
                        //echo $var_acopio;
                        ?>
                        <tr style="background-color: powderblue;">
                            <td colspan="5"><center><b>SUBTOTAL ACOPIO: <?php echo $var_acopio; ?></b></center></td>
                            <td><b><?php echo $total_hours; ?></b></td>
                            <td><b>0</b></td>
                            <td><b>0</b></td>
                        </tr>
                        <tr style="background-color: green;">
                            <td colspan="5"><center><b>TOTAL: </b></center></td>
                            <td><b><?php echo $total_total; ?></b></td>
                            <td><b>0</b></td>
                            <td><b>0</b></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>