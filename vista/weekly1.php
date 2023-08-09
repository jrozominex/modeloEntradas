<?php 
session_start(); 
error_reporting(0);
require_once '../modelo/conexion.php';
$a = date('Y');
$semana = date('W')-1;
$weeks = date("W", mktime(0,0,0,12,28,$a));
$Year = date('Y')-1;
if(isset($_POST['input_week'])){
    $week = $_POST['input_week'];
}else{
    $week = $semana;
}
$year = $a;
for($day=1; $day<8; $day++)
{
    $fecha_semana[$day] = date('Y-m-d', strtotime($year."W".$week.$day)); echo "<br>";
}
for($last_day1=1; $last_day1<8; $last_day1++)
{
   $last_1ra_semana[$last_day1] = date('Y-m-d', strtotime($year."W".'01'.$last_day1));
}
$i = 0;
$sql_consulta = "SELECT Proveedores.RazonSocial
FROM EmbarqueDetalles INNER JOIN Embarque ON EmbarqueDetalles.NumeroEmbarque = Embarque.NumeroEmbarque INNER JOIN
    Origenes ON EmbarqueDetalles.idOrigen = Origenes.idOrigen INNER JOIN 
    Proveedores ON Origenes.idProveedor = Proveedores.idProveedor
WHERE (YEAR(Embarque.FechaPartida) = '$a') GROUP BY Proveedores.RazonSocial ORDER BY Proveedores.RazonSocial";

$sql_consulta = "SELECT Proveedores.RazonSocial FROM EmbarqueDetalles INNER JOIN Embarque ON EmbarqueDetalles.NumeroEmbarque = Embarque.NumeroEmbarque INNER JOIN 
    Origenes ON EmbarqueDetalles.idOrigen = Origenes.idOrigen INNER JOIN Proveedores ON Origenes.idProveedor = Proveedores.idProveedor INNER JOIN 
    Destino ON Embarque.idDestino = Destino.idDestino 
    WHERE (YEAR(Embarque.FechaPartida) = '$a') AND EmbarqueDetalles.idClasificacion<>(select idClasificacion from Clasificacion 
    where Descripcion='Tèrmico 35x110 mm')  and 
    EmbarqueDetalles.idProducto=(SELECT idProducto from Productos where Descripcion='Térmico') GROUP BY Proveedores.RazonSocial ORDER BY Proveedores.RazonSocial";
    //AND Embarque.idDestino=(select idDestino from Destino where Descripcion='PUERTO BRISA')
$res = sqlsrv_query($conn,utf8_decode($sql_consulta));
while($embarques = sqlsrv_fetch_array($res)){
    if (utf8_encode($embarques['RazonSocial']) == 'MINEX COMPAÑÍA INTERNACIONAL S.A.S. C.I.'){
        $Proveedor = 'PUERTO BRISA';
    }else{
        $Proveedor = $embarques['RazonSocial'];
    }
    $array_acopio[$i] = utf8_encode($Proveedor);
    $array_nombres[utf8_encode($Proveedor)] = $i;
    $i++;
}
$count_array = count($array_acopio);

?>
<!DOCTYPE html>
<html>
<head>
    <?php include './libreria.php'; ?>
    <script type="text/javascript" src="../controlador/ctr_weekly.js"></script>
    <title>WEEKLY</title>
</head>
<body>
    <?php include('./header.php'); ?>
    <div class="container">
        <?php  
      /*$sql = "SELECT * FROM WEEKLY WHERE Periodo='$week' AND id_valor like '%COQUE%' AND id_valor<>'C_CARBON'";//id_reporte, Anno, Periodo, id_valor, valor
$res = sqlsrv_query($conn,$sql);
while($b = sqlsrv_fetch_array($res)){
    echo $b['id_reporte'].' - ';
    echo $b['Anno'].' - ';
    echo $b['Periodo'].' - ';
    echo $b['id_valor'].' - ';
    echo $b['Valor'].'<br>';
}
 $sql = "SELECT * FROM WEEKLY_DETAILS WHERE id_valor like '%COQUE%'";
$res = sqlsrv_query($conn,$sql);
while($b = sqlsrv_fetch_array($res)){
    echo '<b>'.$b['id_valor'].' - </b>';
    echo utf8_encode($b['SQL_QUERY']).'<br>';
}*/
        ?>
        <form id="form_week" name="" method="POST">
        <div class="row">
            <input type="hidden" name="fecha_inicio" id="fecha_inicio" value="<?php echo $fecha_semana[1]; ?>">
            <input type="hidden" name="fecha_fin" id="fecha_fin" value="<?php echo $fecha_semana[7]; ?>">
            <input type="hidden" id="year" value="<?php echo $a; ?>">
            <div class="col-sm-4">
                <label>TIPO DE INFORME:</label>
                <select name="select_informe" id="select_informe" class="form-control" onchange="tipo_informe()">
                    <option value="0" <?PHP if(isset($_POST['select_informe'])){ if($_POST['select_informe'] == '0'){ echo 'selected'; } } ?>>SELECCIONE</option>
                    <option value="1" <?PHP if(isset($_POST['select_informe'])){ if($_POST['select_informe'] == '1'){ echo 'selected'; } } ?>>EXPORTACIONES</option>
                    <option value="2" <?PHP if(isset($_POST['select_informe'])){ if($_POST['select_informe'] == '2'){ echo 'selected'; } } ?>>CLIENTES</option>
                </select>
            </div>
            <div class="col-sm-4" id="div_cliente">
                <label>CLIENTE:</label>
                <select name="select_cliente" class="form-control" id="select_cliente" onchange="tipo_cliente()">
                    <option value="0" <?PHP if(isset($_POST['select_productos'])){ if($_POST['select_productos'] == '0'){ echo 'selected'; } } ?>>SELECCIONE</option>
                    <?php 
                    $sql = "SELECT distinct(RecepcionadoEn) FROM tz_MovimientoTransporte 
                        WHERE tipoMovimiento='Traslado' AND FechaRegistro BETWEEN '$fecha_semana[1]' AND '$fecha_semana[7]' AND Empresa='MINEX' 
                        AND RecepcionadoEn not in ('PUERTO BRISA', 'PUERTO COMPAS', 'LA PRADERA') and producto='CARBÓN' 
                        AND Clasificacion<>'Tèrmico 35x110 mm'";
                    $res = sqlsrv_query($conn,utf8_decode($sql));
                    while($clientes = sqlsrv_fetch_array($res)){
                        ?>
                        <option value="<?php echo $clientes['RecepcionadoEn']; ?>" <?PHP if(isset($_POST['select_cliente'])){ if($_POST['select_cliente'] == $clientes['RecepcionadoEn']){ echo 'selected'; } } ?>><?php echo $clientes['RecepcionadoEn']; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-4" id="div_productos">
                <label>MATERIAL:</label>
                <select name="select_productos" class="form-control" id="select_productos" onchange="tipo_prod()">
                    <option value="0" <?php if(isset($_POST['select_productos'])){ if($_POST['select_productos'] == '0'){ echo 'selected'; } } ?>>SELECCIONE</option>
                    <option value="CARBON" <?php if(isset($_POST['select_productos'])){ if($_POST['select_productos'] == 'CARBON'){ echo 'selected'; } } ?>>CARBON</option>
                    <option value="COQUE" <?php if(isset($_POST['select_productos'])){ if($_POST['select_productos'] == 'COQUE'){ echo 'selected'; } } ?>>COQUE</option>
                    <option value="GRANULADO" <?php if(isset($_POST['select_productos'])){ if($_POST['select_productos'] == 'GRANULADO'){ echo 'selected'; } } ?>>GRANULADO</option>
                </select>
            </div>
            <!--<form id="form_week" name="" method="POST">-->
            <div class="col-sm-1">
                <button class="btn btn-success" onclick="mover('atras')"><span class="glyphicon glyphicon-chevron-left"></span></button>  
            </div>
            <div class="col-sm-2">
                <input type="hidden" name="num_weeks" id="num_weeks" value="<?php echo $weeks; ?>">
                <input type="hidden" name="input_week" id="input_week" value="<?php if(isset($_POST['input_week'])){ echo $_POST['input_week']; }else{ echo $semana; }?>">
                <center><h4 id="semanas"><?php echo $a.' - semana '.$semana; ?></h4></center>
            </div>
            <div class="col-sm-1" style="text-align: right;">
                <button class="btn btn-success" onclick="mover('adelante')"><span class="glyphicon glyphicon-chevron-right"></span></button>
            </div>
            <input type="hidden" name="bandera" id="bandera">
            <!--</form>-->
        </div>
        <br>
        </form>
        <div id="inv_cucuta">
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <center>
                        <label>WEEKLY COAL BUYING AND TRANSPORTING ACTIVITY SHEET</label><br>
                        <label>SUMMARY - EXPORT OPERATION</label>
                        <h6>DATE INITIAL - DATE END.   In Metrics Tons</h6>
                    </center>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <table class="table table-bordered table-condensed table-striped table-hover" border="1">
                        <thead>
                            <tr>
                                <th></th>
                                <th>CUCUTA</th>
                                <th id="td_transit1">TRANSIT</th>
                                <th>PTO. BRISA</th>
                                <th id="td_palmarejo1">PALMAREJO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th  style="border-bottom: 2px solid;"><label>INITIAL INVENTORY</label></th>
                                <th  style="border-bottom: 2px solid;"><label id="summary_cucuta"></label></th>
                                <th id="td_transit2" style="border-bottom: 2px solid;">1,588.00</th>
                                <th  style="border-bottom: 2px solid;"><label id="summary_pto_brisa"></label></th>
                                <th id="td_palmarejo2" style="border-bottom: 2px solid;">4,849.00</th>
                            </tr>
                            <tr>
                                <td>INPUTS</td>
                                <td><p id="input_cucuta"></p></td>
                                <td id="td_transit3">0.00</td>
                                <td><p id="input_pto_brisa"></p></td>
                                <td id="td_palmarejo3">0.00</td>
                            </tr>
                            <tr>
                                <td>OUTPUTS</td>
                                <td><p id="output_cucuta"></p></td>
                                <td id="td_transit4">0.00</td>
                                <td><p id="output_pto_brisa"></p></td>
                                <td id="td_palmarejo4">0.00</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th align="center">CURRENT INVENTORY</th>
                                <th><p id="resumen_current_cucuta"></p></th>
                                <th id="td_transit5">1,588.00</th>
                                <th><p id="resumen_current_pto_brisa"></p></th>
                                <th id="td_palmarejo5">4,849.00</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
            <center>
                <h4 style="background-color: #D5D5D5;">
                    <button class="btn btn-default btn-xs" style="margin-left: -15px;" onclick="conjuntos('INVENTORY CUCUTA')"><span id="span_1" class="glyphicon glyphicon-minus"></span></button>
                    INVENTORY CUCUTA
                </h4>
            </center>
            <!----------------------------------------------- INICIO INVENTORY_CUCUTA ----------------------------------------------------------------->
            <div id="INVENTORY_CUCUTA">
                <div class="row" style="border-bottom: 1px solid;">
                    <div class="col-sm-4">
                        <label>INITIAL INVENTORY</label>
                    </div>
                    <div class="col-sm-2">
                        <label id="INITIAL_INVENTORY"></label>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-8" style="border-bottom: 1px solid;">
                                <label>Total Purchases</label>
                            </div>
                            <div class="col-sm-4" style="border-bottom: 1px solid;">
                                <label id="Total_Purchases"></label>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-8" id="Purchases"></div>
                            <div class="col-sm-4">
                                <center><img src="../img/img.jpg" width="100%"></center>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-7" id="Others"></div>
                        </div>
                        <!----------------------------------------------------------------------------------->
                        <div class="row">
                            <div class="col-sm-8" style="border-bottom: 1px solid;">
                                <label>Total Coal Trucked</label>
                            </div>
                            <div class="col-sm-4" style="border-bottom: 1px solid;">
                                <label id="Total_Coal_Trucked"></label>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-8" id="Trucked"></div>
                            <div class="col-sm-4">
                                <center><img src="../img/img.jpg" width="100%"></center>
                            </div>
                        </div>
                        <!---------------------------------------------------------------------------------------------->
                        <br>
                        <div class="row">
                            <div class="col-sm-4"><label>Total Sold</label></div>
                            <div class="col-sm-4"><label>Local Sales</label></div>
                        </div>
                    </div>
                    <div class="col-sm-6" style="vertical-align: middle;">
                        <img src="../img/grafico.PNG" width="100%">
                    </div>
                </div>
                <div class="row" style="border: 1px solid; background-color: #D5D5D5;">
                    <div class="col-sm-4"><label>CURRENT INVENTORY</label></div>
                    <div class="col-sm-1"><label id="CURRENT_INVENTORY_CUCUTA"></label></div>
                    <div class="col-sm-2"><span id="span_rendimiento"></span> <label id="RENDIMIENTO_INVENTORY_CUCUTA"></label></div>
                </div>
            </div>
            <!----------------------------------------------- FIN INVENTORY_CUCUTA ----------------------------------------------------------------->
            <div id="div_inventory_in_transit">
                <center>
                    <h4 style="background-color: #AFCB54;">
                        <button class="btn btn-default btn-xs" style="margin-left: -15px;" onclick="conjuntos('INVENTORY IN TRANSIT')"><span id="span_2" class="glyphicon glyphicon-minus"></span></button>
                        INVENTORY IN TRANSIT
                    </h4>
                </center>
                <!----------------------------------------------- INICIO INVENTORY_IN_TRANSIT ----------------------------------------------------------------->
                <div id="INVENTORY_IN_TRANSIT">
                    <div class="row">
                        <div class="col-sm-6" style="border-bottom: 1px solid; text-align: center;">
                            <label>UREÑA</label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-4" style="border-top: 1px solid; border-bottom: 1px solid; border-color: gray;">
                            <label>INITIAL INVENTORY</label>
                        </div>
                        <div class="col-sm-2" style="border-top: 1px solid; border-bottom: 1px solid; border-color: gray;">
                            <label>1,588.00</label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="row">
                                <div class="col-sm-6" style="text-align: left;">Coal Received from Cucuta</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6" style="text-align: left;">Coal trucked to Palmarejo</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6" style="border-bottom: 1px solid; text-align: center;">
                            <img src="../img/graf.jpg" width="50%">
                        </div>
                    </div>
                    <br>
                    <div class="row" style="border: 1px solid; background-color: #AFCB54;">
                        <div class="col-sm-4"><label>CURRENT INVENTORY</label></div>
                        <div class="col-sm-1"><label>1,588.00</label></div>
                        <!--<div class="col-sm-1"><label>2.186</label></div>-->
                    </div>
                </div>
                <br>
            </div>
            <!----------------------------------------------- FIN INVENTORY_IN_TRANSIT ----------------------------------------------------------------->
            <button class="btn btn-default btn-xs" onclick="conjuntos('DIVISIONS')"><span id="span_3" class="glyphicon glyphicon-minus"></span></button>
            <!----------------------------------------------- INICIO DIVISIONS ----------------------------------------------------------------->
            <div id="DIVISIONS">
                <br>
                <div class="row">
                    <div class="col-sm-6" style="border: 1px solid; background-color: #D5D5D5">
                        <center><label>INVENTORY PTO. BRISA</label></center>
                    </div>
                    <div class="col-sm-6" style="border: 1px solid; background-color: #D5D5D5">
                        <center><label>SUMMARY</label></center>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row" style="border: 1px solid;">
                            <div>
                                <div class="col-sm-8" style="border-bottom: 1px solid;">
                                    <label>INITIAL INVENTORY</label>
                                </div>
                                <div class="col-sm-4" style="border-bottom: 1px solid;">
                                    <label id="INVENTORY_PTO_BRISA" style="text-align: right;"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10" style="margin-left: 5px;">
                                    <!--<div class="row">
                                        <div class="col-sm-10" style="text-align: left;">Coal Received from Centromin</div>
                                    </div>-->
                                    <div class="row">
                                        <div class="col-sm-9" style="text-align: left; margin-top: 3px;">Coal Received from Cucuta</div>
                                        <input type="hidden" id="input_Coal_Received_from_Cucuta">
                                        <div class="col-sm-2" style="text-align: right; margin-top: 3px;"><p style="text-align: right;" id="Coal_Received_from_Cucuta"></p></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-9" style="text-align: left; margin-top: 3px;">Coal Sold (loaded/exported)</div>
                                        <div class="col-sm-2" style="text-align: right; margin-top: 3px;"><p style="text-align: right;" id="Coal_Sold_loaded_exported"></p></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12" style="border: 1px solid; background-color: #D5D5D5;">
                                <div class="row">
                                    <div class="col-sm-8"><label>CURRENT INVENTORY</label></div>
                                    <div class="col-sm-4"><label id="CURRENT_INVENTORY_PTO_BRISA"></label></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6" style="border: 1px solid;">
                        <div class="row" style="border-bottom: 1px solid;">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3" style="margin-bottom: -5px;">
                                <center><label>CUCUTA</label></center>
                            </div>
                            <div class="col-sm-3" style="margin-bottom: -5px;">
                                <center><label>TRANSIT</label></center>
                            </div>
                            <div class="col-sm-3" style="margin-bottom: -5px;">
                                <center><label>PTO. BRISA</label></center>
                            </div>
                        </div>
                        <div class="row" style="border-bottom: 1px solid; border-top: 1px solid;">
                            <div class="col-sm-4" style="margin-bottom: -5px;">
                                <label>INITIAL INVENTORY</label>
                            </div>
                            <div class="col-sm-2"  style="margin-bottom: -5px;">
                                <center><label id="Inventary_expo_cucuta">#</label></center>
                            </div>
                            <div class="col-sm-3"  style="margin-bottom: -5px;">
                                <center><label>1,588.00</label></center>
                            </div>
                            <div class="col-sm-2"  style="margin-bottom: -5px;">
                                <center><label id="Inventary_pto_brisa">adad</label></center>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4" style="margin-top: -4px; margin-bottom: -3px;">
                                <h6>INPUTS</h6>
                            </div>
                            <div class="col-sm-2" style="margin-bottom: -5px;">
                                <center><P id="inputs_expo_cucuta">#</P></center>
                            </div>
                            <div class="col-sm-3" style="margin-bottom: -5px;">
                                <center><p>0.00</p></center>
                            </div>
                            <div class="col-sm-2" style="margin-bottom: -5px;">
                                <center><P id="inputs_expo_pto_brisa">ada</P></center>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4" style="margin-top: -10px; margin-bottom: -9px;">
                                <h6>OUTPUTS</h6>
                            </div>
                            <div class="col-sm-2" style="margin-bottom: -7px;">
                                <center><P id="outputs_cliente_cucuta">adad</P></center>
                            </div>
                            <div class="col-sm-3" style="margin-bottom: -7px;">
                                <center><p>0.00</p></center>
                            </div>
                            <div class="col-sm-2" style="margin-bottom: -7px;">
                                <center><P id="outputs_pto_brisa">#</P></center>
                            </div>
                        </div>
                        <div class="row" style="border: 1px solid; background-color: #D5D5D5;">
                            <div class="col-sm-4"><center><label>CURRENT INVENTORY</label></center></div>
                            <div class="col-sm-2"><center><label id="TOTAL_CURRENT_CUCUTA">asdasd</label></center></div>
                            <div class="col-sm-3"><center><label>1,588.00</label></center></div>
                            <div class="col-sm-2"><center><label id="TOTAL_CURRENT_PTO_BRISA">dad</label></center></div>
                        </div>  
                    </div>
                </div>
                <br>
                <div class="row" id="div_inventory_palmarejo">
                    <div class="col-sm-4">
                        <div class="row" style="border: 1px solid;">
                            <div>
                                <div class="col-sm-12" style="border-bottom: 1px solid; text-align: center; background-color: #D5D5D5;">
                                    <label>INVENTORY PALMAREJO</label>
                                </div>
                            </div>
                            <div class="col-sm-8" style="border-bottom: 1px solid;">
                                <label style="margin-left: 5px;">INITIAL INVENTORY</label>
                            </div>
                            <div class="col-sm-4" style="border-bottom: 1px solid;">
                                <label style="text-align: right;">4,849.00</label>
                            </div>
                            <div class="row">
                                <div class="col-sm-10" style="margin-left: 5px;">
                                    <div class="row">
                                        <div class="col-sm-9" style="text-align: left;">Coal Received from ureña</div>
                                        <div class="col-sm-3" style="text-align: left;"><center>0.00</center></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-9" style="text-align: left;">Coal Sold (loaded/exported)</div>
                                        <div class="col-sm-3" style="text-align: left;"><center>0.00</center></div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="col-sm-12" style="border: 1px solid; background-color: #D5D5D5;">
                                    <div class="row">
                                        <div class="col-sm-8"><label>CURRENT INVENTORY</label></div>
                                        <div class="col-sm-4"><label>4,849.00</label></div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!----------------------------------------------- FIN DIVISIONS ----------------------------------------------------------------->
            <!----------------------------------------------- INICIO TABLES ----------------------------------------------------------------->
            <div class="row">
                <br>
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <table class="table table-bordered table-condensed table-striped table-hover">
                        <thead style="background-color: #ED7D31;">
                            <th style="text-align: center;">VESSEL</th>
                            <th style="text-align: center;">DATE</th>
                            <?php 
                            for ($i=0; $i < $count_array; $i++) {
                                echo '<th style="text-align: center;">'.$array_acopio[$i].'</th>';
                            }
                            ?>
                            <th style="text-align: center;">TOTAL VESSEL</th>
                        </thead>
                        <!--<tbody id="tbody_buques">-->
                        <tbody>
                            <?php 
                            //echo '<b>posiciones: </b>';print_r($array_acopio); echo "<br>";
                            //echo '<b>nombres: </b>';print_r($array_nombres); echo "<br>";
                            $moto_nave = 'moto_nave';
                            $acopio = '';
                            $num = 0;
                            $total_vessel = 0;
                            $total_total_vessel = 0;
                            for ($i=0; $i < $count_array; $i++) { 
                                $position[$i] = 0;
                            }
                            if(isset($_POST['select_productos'])){
                                if($_POST['select_productos'] == 'CARBON'){
                                    $sql_consulta1 = "SELECT Embarque.NombreMotoNave, Embarque.FechaPartida, Proveedores.RazonSocial, EmbarqueDetalles.Tonelaje, Destino.Descripcion 
                                    FROM EmbarqueDetalles INNER JOIN Embarque ON EmbarqueDetalles.NumeroEmbarque = Embarque.NumeroEmbarque INNER JOIN 
                                    Origenes ON EmbarqueDetalles.idOrigen = Origenes.idOrigen INNER JOIN Proveedores ON Origenes.idProveedor = Proveedores.idProveedor INNER JOIN 
                                    Destino ON Embarque.idDestino = Destino.idDestino 
                                    WHERE (CAST(Embarque.FechaPartida AS DATE) BETWEEN '$last_1ra_semana[1]' AND '$fecha_semana[7]') AND EmbarqueDetalles.idClasificacion<>(select idClasificacion from Clasificacion 
                                    where Descripcion='Tèrmico 35x110 mm') and 
                                    EmbarqueDetalles.idProducto=(SELECT idProducto from Productos where Descripcion='Térmico') 
                                    ORDER BY Embarque.FechaPartida, Proveedores.RazonSocial";
                                }elseif($_POST['select_productos'] == 'COQUE'){
                                    $sql_consulta1 = "SELECT Embarque.NombreMotoNave, Embarque.FechaPartida, Proveedores.RazonSocial, EmbarqueDetalles.Tonelaje, Destino.Descripcion 
                                    FROM EmbarqueDetalles INNER JOIN Embarque ON EmbarqueDetalles.NumeroEmbarque = Embarque.NumeroEmbarque INNER JOIN 
                                    Origenes ON EmbarqueDetalles.idOrigen = Origenes.idOrigen INNER JOIN Proveedores ON Origenes.idProveedor = Proveedores.idProveedor INNER JOIN 
                                    Destino ON Embarque.idDestino = Destino.idDestino 
                                    WHERE (CAST(Embarque.FechaPartida AS DATE) BETWEEN '$last_1ra_semana[1]' AND '$fecha_semana[7]') AND EmbarqueDetalles.idClasificacion<>(select idClasificacion from Clasificacion 
                                    where Descripcion='Tèrmico 35x110 mm') and 
                                    EmbarqueDetalles.idProducto=(SELECT idProducto from Productos where Descripcion='COQUE') 
                                    ORDER BY Embarque.FechaPartida, Proveedores.RazonSocial";
                                }elseif($_POST['select_productos'] == 'GRANULADO'){
                                    $sql_consulta1 = "";
                                }
                            }
                            

                            $res = sqlsrv_query($conn,utf8_decode($sql_consulta1));
                            while ($buques = sqlsrv_fetch_array($res)){
                                if (utf8_encode($buques['RazonSocial']) == 'MINEX COMPAÑÍA INTERNACIONAL S.A.S. C.I.'){
                                    $Proveedor = 'PUERTO BRISA';
                                }else{
                                    $Proveedor = $buques['RazonSocial'];
                                }?>
                                <?php
                                if($moto_nave == 'moto_nave'){
                                    $moto_nave = utf8_encode($buques['NombreMotoNave']);
                                    $acopio  = $buques['RazonSocial'];
                                    ?>
                                    <tr>
                                        <td><?php echo $buques['NombreMotoNave']; ?></td>
                                        <td><?php echo date_format($buques['FechaPartida'],'F d, Y'); ?></td>
                                    <?php
                                    for ($i=0; $i < $count_array; $i++) { 
                                        if($array_nombres[$Proveedor] == $num){
                                            echo '<td align="right">'.number_format($buques['Tonelaje'],2).'</td>';
                                            $total_vessel+=$buques['Tonelaje'];
                                            $num++;
                                            break;
                                        }else{
                                            echo '<td align="right">0,00</td>';
                                            $num++;
                                        }
                                    }
                                    $position[$num] += $buques['Tonelaje'];
                                }elseif($moto_nave == utf8_encode($buques['NombreMotoNave'])){
                                    for ($i=0; $i < $count_array; $i++) { 
                                        if($array_nombres[$Proveedor] == $num){
                                            echo '<td align="right">'.number_format($buques['Tonelaje'],2).'</td>';
                                            $total_vessel+=$buques['Tonelaje'];
                                            $num++;
                                            break;
                                        }else{
                                            echo '<td align="right">0,00</td>';
                                            $num++;
                                        }
                                    }
                                    $position[$num] += $buques['Tonelaje'];
                                }elseif(utf8_encode($moto_nave) != utf8_encode($buques['NombreMotoNave'])){
                                    if($num < $count_array){
                                        for ($i=$num; $i < $count_array; $i++) {
                                            echo '<td align="right">0,00</td>';
                                        }
                                    }
                                    echo '<td align="right">'.number_format($total_vessel,2).'</td>';
                                    $total_total_vessel+=$total_vessel;
                                    $total_vessel = 0;
                                    $num = 0;
                                    $acopio  = $buques['RazonSocial'];
                                    $moto_nave = utf8_encode($buques['NombreMotoNave']);
                                    ?>
                                    </tr>
                                    <tr>
                                        <td><?php echo $buques['NombreMotoNave']; ?></td>
                                        <td><?php echo date_format($buques['FechaPartida'],'F d, Y'); ?></td>
                                    <?php
                                    for ($i=0; $i < $count_array; $i++){
                                        if($array_nombres[$Proveedor] == $num){
                                            echo '<td align="right">'.number_format($buques['Tonelaje'],2).'</td>';
                                            $total_vessel+=$buques['Tonelaje'];
                                            $num++;
                                            break;
                                        }else{
                                            echo '<td align="right">0,00</td>';
                                            $num++;
                                        }
                                    }
                                    $position[$num] += $buques['Tonelaje'];
                                }
                            }
                            for ($i=$num; $i < $count_array; $i++) {
                                echo '<td align="right">0,00</td>';
                            }
                            echo '<td align="right">'.number_format($total_vessel,2).'</td>';
                            $total_total_vessel+=$total_vessel;
                            ?>
                        </tbody>
                        <!--<tfoot style="background-color: #ED7D31;" id="tfoot_buque"></tfoot>-->
                        <tfoot style="background-color: #ED7D31;">
                            <tr>
                                <th colspan="2" style="text-align: center;">TOTAL <?php echo $year; ?></th>
                                <?php
                                for ($i=1; $i <= $count_array; $i++) { 
                                    echo '<th style="text-align: right;">'.number_format($position[$i],2).'</th>';
                                }
                                ?>
                                <th style="text-align: right;"><?php echo number_format($total_total_vessel,2); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!----------------------------------------------- FIN TABLES ----------------------------------------------------------------->
        </div>
 <!----------------------------------------------  plantilla o div  clientes-------------------------------------------------------------->
        <div id="clientes">
            <center>
                <h4 style="background-color: #D5D5D5;">
                    <button class="btn btn-default btn-xs" style="margin-left: -15px;" onclick="conjuntos('INVENTORY CUCUTA')"><span id="span_1" class="glyphicon glyphicon-minus"></span></button>
                    INVENTORY CUCUTA
                </h4>
            </center>
            <!----------------------------------------------- INICIO INVENTORY_CUCUTA ----------------------------------------------------------------->
            <div id="INVENTORY_CUCUTA">
                <div class="row" style="border-bottom: 1px solid;">
                    <div class="col-sm-4">
                        <label>INITIAL INVENTORY</label>
                    </div>
                    <div class="col-sm-2">
                        <label id="INITIAL_INVENTORY"></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-8" style="border-bottom: 1px solid;">
                                <label>Total Purchases</label>
                            </div>
                            <div class="col-sm-4" style="border-bottom: 1px solid;">
                                <label id="Total_Purchases_clientes"></label>
                            </div>
                        </div>
                        <div class="row">
                            <!--<div class="col-sm-1" style="vertical-align: middle; text-align: center; margin-top: 15px;"> 100%</div>-->
                            <div class="col-sm-8" id="Purchases_calenturitas"></div>
                            <div class="col-sm-4">
                                <center><img src="../img/img.jpg" width="100%"></center>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-7" id="Others"></div>
                        </div>
                        <!----------------------------------------------------------------------------------->
                        <div class="row">
                            <div class="col-sm-8" style="border-bottom: 1px solid;">
                                <label>Total Coal Trucked</label>
                            </div>
                            <div class="col-sm-4" style="border-bottom: 1px solid;">
                                <label id="Total_Coal_Trucked_ventas_cliente"></label>
                            </div>
                        </div>
                        <div class="row">
                            <!--<div class="col-sm-2" style="vertical-align: middle; text-align: center;"> 100%</div>-->
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-10" id="name_trucked_clientes"></div>
                                    <div class="col-sm-2" id="value_trucked_clientes"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <center><img src="../img/img.jpg" width="100%"></center>
                            </div>
                        </div>
                        <!---------------------------------------------------------------------------------------------->
                        <div class="row">
                            <div class="col-sm-4"><label>Total Sold</label></div>
                            <div class="col-sm-4"><label>Local Sales</label></div>
                        </div>
                    </div>
                    <div class="col-sm-6" style="vertical-align: middle;">
                        <img src="../img/grafico.PNG" width="100%">
                    </div>
                </div>
                <div class="row" style="border: 1px solid; background-color: #D5D5D5;">
                    <div class="col-sm-8"><label>CURRENT INVENTORY</label></div>
                    <div class="col-sm-2"><label id="CURRENT_INVENTORY_CLIENTE"></label></div>
                    <div class="col-sm-2"><label id="RENDIMIENTO_INVENTORY_CLIENTE"></label></div>
                </div>
            </div>
            <br>
            <button class="btn btn-default btn-xs" onclick="conjuntos_clientes('DIVISIONS')"><span id="span_clientes" class="glyphicon glyphicon-minus"></span></button>
            <!----------------------------------------------- INICIO DIVISIONS ----------------------------------------------------------------->
            <div id="DIVISIONS">
                <br>
                <div class="row">
                    <div class="col-sm-6" style="border: 1px solid; background-color: #D5D5D5">
                        <label id="title_inventory_despacho"></label>
                    </div>
                    <div class="col-sm-6" style="border: 1px solid; background-color: #D5D5D5">
                        <label>SUMMARY</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row" style="border: 1px solid;">
                            <div>
                                <div class="col-sm-8" style="border-bottom: 1px solid;">
                                    <label>INITIAL INVENTORY</label>
                                </div>
                                <div class="col-sm-4" style="border-bottom: 1px solid;">
                                    <label id="INVENTORY_PTO_BRISA_cliente" style="text-align: right;"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10" style="margin-left: 5px;">
                                  <div class="row">
                                        <div class="col-sm-12" style="text-align: left;">&nbsp;</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-9" style="text-align: left;">Coal Received from Cucuta</div>
                                        <input type="hidden" id="input_Coal_Received_from_Cucuta">
                                        <div class="col-sm-2" style="text-align: right;"><p style="text-align: right;" id="Coal_Received_from_Cucuta_cliente"></p></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-9" style="text-align: left;">Coal Sold (loaded/exported)</div>
                                        <div class="col-sm-2" style="text-align: right;"><p style="text-align: right;" id="Coal_Sold_loaded_exported"></p></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12" style="text-align: left;">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="row" style="border: 1px solid;">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4">
                                <label>CUCUTA</label>
                            </div>
                            <div class="col-sm-4">
                                <label id="label_summary_inventory"></label>
                            </div>
                        </div>
                        <div class="row" style="border: 1px solid;">
                            <div class="col-sm-4">
                                <label>PREVIOUS BALANCE</label>
                            </div>
                            <div class="col-sm-4">
                                <label id="Inventary_cliente_cucuta">#</label>
                            </div>
                            <div class="col-sm-4">
                                <label id="Inventary_cliente"></label>
                            </div>
                        </div>
                        <div class="row" style="border: 1px solid;">
                            <div class="col-sm-4">
                                <h6>INPUTS</h6>
                            </div>
                            <div class="col-sm-4">
                                <P id="inputs_cliente_cucuta">#</P>
                            </div>
                            <div class="col-sm-4">
                                <P id="inputs_cliente"></P>
                            </div>
                        </div>
                        <div class="row" style="border: 1px solid;">
                            <div class="col-sm-4">
                                <h6>OUTPUTS</h6>
                            </div>
                            <div class="col-sm-4">
                                <P id="outputs_cliente_cucuta"></P>
                            </div>
                            <div class="col-sm-4">
                                <P id="outputs_cliente">#</P>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6" style="border: 1px solid; background-color: #D5D5D5;">
                        <div class="row">
                            <div class="col-sm-8"><label>CURRENT INVENTORY</label></div>
                            <div class="col-sm-4"><label id="CURRENT_INVENTORY_PTO_BRISA"></label></div>
                        </div>
                    </div>
                    <div class="col-sm-6" style="border: 1px solid; background-color: #D5D5D5;">
                        <div class="row">
                            <div class="col-sm-8"><label>TOTAL SOLD</label></div>
                            <div class="col-sm-4"><label id="TOTAL_SOLD_CLIENTES"></label></div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <!----------------------------------------------- FIN DIVISIONS ----------------------------------------------------------------->
            <!----------------------------------------------- INICIO TABLES ----------------------------------------------------------------->
            <div class="row">
                <br>
                <div class="col-sm-8">
                    <table class="table table-bordered table-condensed table-striped table-hover">
                        <thead style="background-color: #ED7D31;">
                            <th>ORDER #</th>
                            <th>STATUS</th>
                            <th>MT CONTRACTED</th>
                            <th>MT RECEIVED</th>
                            <th>MT PENDING TO DISPATCH</th>
                        </thead>
                        <tbody id="tbody_orders">
                            <tr>
                                <td>ORDER # 1</td>
                                <td>CLOSED</td>
                                <td>3.350</td>
                                <td>24.070</td>
                                <td>-734</td>
                            </tr>
                        </tbody>
                        <tfoot style="background-color: #ED7D31;" id="tfoot_orders"></tfoot>
                    </table>
                </div>
            </div>
            <!----------------------------------------------- FIN TABLES ----------------------------------------------------------------->
            <div class="row">
                <div class="col-sm-6">
                    <center>
                        <label>WEEKLY COAL BUYING AND TRANSPORTING ACTIVITY SHEET</label><br>
                        <label>SUMMARY - EXPORT OPERATION</label>
                        <h6>DATE INITIAL - DATE END.   In Metrics Tons</h6>
                    </center>
                </div>
            </div>
            <div class="row">
                <br>
                <div class="col-sm-8">
                    <table class="table table-bordered table-condensed table-striped table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>CUCUTA</th>
                                <th>TRANSIT</th>
                                <th>PTO. BRISA</th>
                                <th>PALMAREJO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th  style="border-bottom: 1px solid;"><label>INITIAL INVENTORY</label></th>
                                <th  style="border-bottom: 1px solid;"><label id="summary_cucuta"></label></th>
                                <th  style="border-bottom: 1px solid;">1.588</th>
                                <th  style="border-bottom: 1px solid;"><label id="summary_pto_brisa"></label></th>
                                <th  style="border-bottom: 1px solid;">4.849</th>
                            </tr>
                            <tr>
                                <td>INPUTS</td>
                                <td><p id="input_cucuta"></p></td>
                                <td></td>
                                <td><p id="input_pto_brisa"></p></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>OUTPUTS</td>
                                <td><p id="output_cucuta"></p></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th align="center">CURRENT INVENTORY</th>
                                <th></th>
                                <th>1.588</th>
                                <th></th>
                                <th>4.849</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!-------------------------------------------------------------------------------------------------------------------------------------->
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            <?php
            if(isset($_POST['select_informe'])){ 
                if($_POST['select_informe'] == '1'){
                    if(isset($_POST['select_productos'])){
                        ?>
                        $('#inv_cucuta').show();
                        $('#clientes').hide();
                        $('#div_productos').show();
                        $('#div_cliente').hide();
                        tipo_prod();
                        <?php
                    } 
                }else{
                    ?>
                    $('#inv_cucuta').hide();
                    $('#clientes').show();
                    $('#div_productos').hide();
                    $('#div_cliente').show();
                    tipo_cliente();
                    <?php
                }
            }else{
                ?>
                $('#cambio_semanas').hide();
                $('#inv_cucuta').hide();
                $('#clientes').hide();
                $('#div_productos').hide();
                $('#div_clasif').hide();
                $('#div_cliente').hide();
                <?php
            }?>
            /*if($('#bandera').val() != '1'){
                //alert($('#bandera').val());
                
            }*/
            var recargo = 'recargo';
            mover(recargo);
        });
        
    </script>
</body>
</html>