<?php 
session_start(); 
require_once '../modelo/conexion.php';
$a = date('Y');
$semana = date('W');

$weeks = date("W", mktime(0,0,0,12,28,$a));

//PRIMERA MITAD DE SEMANAS
/*for ($i=0; $i < ($weeks/2); $i++) { 
    $array_week[$i] = $i;
}
//SEGUNDA MITAD DE SEMANAS
$continue_week = ($weeks/2);
for ($i=$continue_week; $i < ($weeks); $i++) { 
    $array_week1[$i] = $i;
}*/
$Year = date('Y')-1;
?>
<!DOCTYPE html>
<html>
<head>
    <?php include './libreria.php'; ?>
    <script type="text/javascript" src="../controlador/mantenimiento.js"></script>
    <title>WEEKLY</title>
</head>
<body>
    <?php include 'Header.php'; ?>
    <div class="container">
        <?php 
        if(isset($_POST['input_week'])){
            $week = $_POST['input_week'];
            $year = $a;
            for($day=1; $day<8; $day++)
            {
                echo date('Y-m-d', strtotime($year."W".$week.$day))."\n";
            }
        }
        ?>
        <input type="hidden" id="year" value="<?php echo $a; ?>">
        <div class="row">
            <div class="col-sm-4">
                <label>TIPO DE INFORME:</label>
                <select id="select_informe" class="form-control" onchange="tipo_informe()">
                    <option value="0">SELECCIONE</option>
                    <option value="1">EXPORTACIONES</option>
                    <option value="2">CLIENTES</option>
                </select>
            </div>
            <div class="col-sm-4" id="div_productos">
                <label>MATERIAL:</label>
                <select class="form-control" id="select_productos" onchange="tipo_prod()">
                    <option value="0">SELECCIONE</option>
                    <option value="1">CARBON</option>
                    <option value="2">COQUE</option>
                    <option value="3">GRANULADO</option>
                </select>
            </div>
            <div class="col-sm-4" id="div_clasif">
                <label>MATERIAL:</label>
                <select class="form-control" id="select_clasif" onchange="tipo_clasif()">
                    <option value="0">Ninguno</option>
                    <?php 
                    $sql = "SELECT DISTINCT(Clasificacion),Clasificacion.idClasificacion FROM tz_MovimientoTransporte  
                            INNER JOIN Clasificacion ON tz_MovimientoTransporte.Clasificacion=Clasificacion.Descripcion
                            WHERE year(FechaRegistro)>='$Year' ORDER BY Clasificacion";
                    $res = sqlsrv_query($conn,$sql);
                    while($clasificacion = sqlsrv_fetch_array($res)){
                        ?>
                        <option value="<?php echo $clasificacion['idClasificacion']; ?>"><?php echo utf8_encode($clasificacion['Clasificacion']); ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-4">
                <button class="btn btn-success" onclick="mover('atras')"><span class="glyphicon glyphicon-chevron-left"></span></button>  
            </div>
            <form id="form_week" name="" method="POST">
                <div class="col-sm-4">
                    <input type="" name="num_weeks" id="num_weeks" value="<?php echo $weeks; ?>">
                    <input type="" name="input_week" id="input_week" value="<?php if(isset($_POST['input_week'])){ echo $_POST['input_week']; }else{ echo $semana; }?>">
                    <center><h1 id="semanas"><?php echo $a.' - semana '.$semana; ?></h1></center>
                </div>
            </form>
            <div class="col-sm-4" style="text-align: right;">
                <button class="btn btn-success" onclick="mover('adelante')"><span class="glyphicon glyphicon-chevron-right"></span></button>
            </div>
        </div>
        <?php 
        /*for ($i=0; $i < ($weeks/2); $i++) { 
            //$array_week[$i] = $i;
            ?>
            <button class="btn btn-default"><?php echo $a.' - '.$i; ?></button>
            <?php
        }*/
        ?>
        <div id="inv_cucuta">
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
                        <label>12548.57</label>
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
                                <label>12548.57</label>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-2" style="vertical-align: middle; text-align: center;"> 100%</div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-10" style="text-align: left;"> IRT</div>
                                    <div class="col-sm-2" style="text-align: right;"> 0</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-10" style="text-align: left;"> Others</div>
                                    <div class="col-sm-2" style="text-align: right;"> 6.599</div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <img src="../img/img.jpg" width="100%">
                            </div>
                        </div>
                        <!----------------------------------------------------------------------------------->
                        <div class="row">
                            <div class="col-sm-8" style="border-bottom: 1px solid;">
                                <label>Total Coal Trucked</label>
                            </div>
                            <div class="col-sm-4" style="border-bottom: 1px solid;">
                                <label>12548.57</label>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-2" style="vertical-align: middle; text-align: center;"> 100%</div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-10" style="text-align: left;">Coal Trucked Pto. Brisa </div>
                                    <div class="col-sm-2" style="text-align: right;">4454</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-10" style="text-align: left;">Coal Trucked Pto. Compas</div>
                                    <div class="col-sm-2" style="text-align: right;">4454</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-10" style="text-align: left;">Coal Trucked Calenturitas</div>
                                    <div class="col-sm-2" style="text-align: right;">4454</div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <img src="../img/img.jpg" width="100%">
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
                    <div class="col-sm-8"><label>CURRENT INVENTORY</label></div>
                    <div class="col-sm-2"><label>18.434</label></div>
                    <div class="col-sm-2"><label>2.186</label></div>
                </div>
            </div>
            <!----------------------------------------------- FIN INVENTORY_CUCUTA ----------------------------------------------------------------->
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
                        <label>12548.57</label>
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
                    <div class="col-sm-1"><label>18.434</label></div>
                    <div class="col-sm-1"><label>2.186</label></div>
                </div>
            </div>
            <br>
            <!----------------------------------------------- FIN INVENTORY_IN_TRANSIT ----------------------------------------------------------------->
            <button class="btn btn-default btn-xs" onclick="conjuntos('DIVISIONS')"><span id="span_3" class="glyphicon glyphicon-minus"></span></button>
            <!----------------------------------------------- INICIO DIVISIONS ----------------------------------------------------------------->
            <div id="DIVISIONS">
                <br>
                <div class="row">
                    <div class="col-sm-6" style="border: 1px solid; background-color: #D5D5D5">
                        <label>INVENTORY PTO. BRISA</label>
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
                                    <label>8.784</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10" style="margin-left: 5px;">
                                    <div class="row">
                                        <div class="col-sm-10" style="text-align: left;">Coal Received from Centromin</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-9" style="text-align: left;">Coal Received from Cucuta</div>
                                        <div class="col-sm-2" style="text-align: center;">2.765</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-10" style="text-align: left;">Coal Sold (loaded/exported)</div>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row" style="border: 1px solid;">
                            <br>
                        </div>
                    </div>
                    <br><br>
                </div>
                <br>
                <div class="row">
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
                                <label style="text-align: right;">4.849</label>
                            </div>
                            <div class="row">
                                <div class="col-sm-10" style="margin-left: 5px;">
                                    <div class="row">
                                        <div class="col-sm-10" style="text-align: left;">Coal Received from Centromin</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-9" style="text-align: left;">Coal Received from Cucuta</div>
                                        <div class="col-sm-2" style="text-align: center;">2.765</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-10" style="text-align: left;">Coal Sold (loaded/exported)</div>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
            <!----------------------------------------------- FIN DIVISIONS ----------------------------------------------------------------->
            <!----------------------------------------------- INICIO TABLES ----------------------------------------------------------------->
            <div class="row">
                <br>
                <div class="col-sm-8">
                    <table class="table table-bordered table-condensed table-striped table-hover">
                        <thead style="background-color: #ED7D31;">
                            <th>VESSEL</th>
                            <th>DATE</th>
                            <th>TM SANOHA</th>
                            <th>TM - CENTROMIN</th>
                            <th>TM - PTO BRISA</th>
                            <th>TOTAL VESSEL</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>MV UBC TORONTO</td>
                                <td>January 06, 2019</td>
                                <td>1.160</td>
                                <td>3.350</td>
                                <td>24.070</td>
                                <td>28.580</td>
                            </tr>
                            <tr>
                                <td>MV UBC TORONTO</td>
                                <td>January 06, 2019</td>
                                <td>1.160</td>
                                <td>3.350</td>
                                <td>24.070</td>
                                <td>28.580</td>
                            </tr>
                            <tr>
                                <td>MV UBC TORONTO</td>
                                <td>January 06, 2019</td>
                                <td>1.160</td>
                                <td>3.350</td>
                                <td>24.070</td>
                                <td>28.580</td>
                            </tr>
                            <tr>
                                <td>MV UBC TORONTO</td>
                                <td>January 06, 2019</td>
                                <td>1.160</td>
                                <td>3.350</td>
                                <td>24.070</td>
                                <td>28.580</td>
                            </tr>
                        </tbody>
                        <tfoot style="background-color: #ED7D31;">
                            <tr>
                                <td colspan="2" align="center">TOTAL 2019</td>
                                <td>1.160</td>
                                <td>3.350</td>
                                <td>104.020</td>
                                <td>108.530</td>
                            </tr>
                        </tfoot>
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
                                <th  style="border-bottom: 1px solid;">27.958</th>
                                <th  style="border-bottom: 1px solid;">1.588</th>
                                <th  style="border-bottom: 1px solid;">11.548</th>
                                <th  style="border-bottom: 1px solid;">4.849</th>
                            </tr>
                            <tr>
                                <td>INPUTS</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>OUTPUTS</td>
                                <td></td>
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
        <div id="clientes">
            <center>
                <h4 style="background-color: #D5D5D5;">
                    <button class="btn btn-default btn-xs" style="margin-left: -15px;" onclick="conjuntos1('INVENTORY CUCUTA')"><span id="span_a" class="glyphicon glyphicon-minus"></span></button>
                    INVENTORY CUCUTA
                </h4>
            </center>
            <!----------------------------------------------- INICIO INVENTORY_CUCUTA ----------------------------------------------------------------->
            <div id="INVENTORY_CUCUTA1">
                <div class="row" style="border-bottom: 1px solid;">
                    <div class="col-sm-4">
                        <label>INITIAL INVENTORY</label>
                    </div>
                    <div class="col-sm-2">
                        <label>12548.57</label>
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
                                <label>12548.57</label>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-2" style="vertical-align: middle; text-align: center;"> 100%</div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-10" style="text-align: left;"> IRT</div>
                                    <div class="col-sm-2" style="text-align: right;"> 0</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-10" style="text-align: left;"> Others</div>
                                    <div class="col-sm-2" style="text-align: right;"> 6.599</div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <img src="../img/img.jpg" width="100%">
                            </div>
                        </div>
                        <!----------------------------------------------------------------------------------->
                        <div class="row">
                            <div class="col-sm-8" style="border-bottom: 1px solid;">
                                <label>Total Coal Trucked</label>
                            </div>
                            <div class="col-sm-4" style="border-bottom: 1px solid;">
                                <label>12548.57</label>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-2" style="vertical-align: middle; text-align: center;"> 100%</div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-10" style="text-align: left;">Coal Trucked Pto. Brisa </div>
                                    <div class="col-sm-2" style="text-align: right;">4454</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-10" style="text-align: left;">Coal Trucked Pto. Compas</div>
                                    <div class="col-sm-2" style="text-align: right;">4454</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-10" style="text-align: left;">Coal Trucked Calenturitas</div>
                                    <div class="col-sm-2" style="text-align: right;">4454</div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <img src="../img/img.jpg" width="100%">
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
                    <div class="col-sm-8"><label>CURRENT INVENTORY</label></div>
                    <div class="col-sm-2"><label>18.434</label></div>
                    <div class="col-sm-2"><label>2.186</label></div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-6" style="border: 1px solid; background-color: #D5D5D5">
                        <label>INVENTORY PTO. BRISA</label>
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
                                    <label>8.784</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10" style="margin-left: 5px;">
                                    <div class="row">
                                        <div class="col-sm-10" style="text-align: left;">Coal Received from Centromin</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-9" style="text-align: left;">Coal Received from Cucuta</div>
                                        <div class="col-sm-2" style="text-align: center;">2.765</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-10" style="text-align: left;">Coal Sold (loaded/exported)</div>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row" style="border: 1px solid;">
                            <br>
                        </div>
                    </div>
                    <br><br>
                </div>
                <br>
                <div class="row">
                    <br>
                    <div class="col-sm-7">
                        <table class="table table-bordered table-condensed table-striped table-hover">
                            <thead>
                                <tr style="background-color: #ED7D31;">
                                    <th># ORDER</th>
                                    <th>STATUS</th>
                                    <th>MT CONTRACTED</th>
                                    <th>MT RECEIVED</th>
                                    <th>MT PENDING TO DISPATCH</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>ORDER # 1</td>
                                    <td>CLOSED</td>
                                    <td>20.000</td>
                                    <td>20.734</td>
                                    <td>-734</td>
                                </tr>
                                <tr>
                                    <td>ORDER # 2</td>
                                    <td>CLOSED</td>
                                    <td>120.000</td>
                                    <td>120.070</td>
                                    <td>-70</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #ED7D31;">
                                    <th colspan="2" align="center">TOTAL ORDERS</th>
                                    <th>620.235</th>
                                    <th>621.128</th>
                                    <th>-48</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#inv_cucuta').hide();
            $('#clientes').hide();
            $('#div_productos').hide();
            $('#div_clasif').hide();
            var recargo = 'recargo';
            mover(recargo);
        });
        function tipo_informe(){
            var select_informe = $('#select_informe').val();
            if(select_informe == 1){
                //$('#inv_cucuta').show();
                $('#div_productos').show();
                $('#clientes').hide();
            }else if(select_informe == 2){
                $('#inv_cucuta').hide();
                $('#div_productos').hide();
                $('#clientes').show();
            }else{
                $('#inv_cucuta').hide();
                $('#div_productos').hide();
                $('#clientes').hide();
            }
        }
        function tipo_prod(){
            var select_productos = $('#select_productos').val();
            if(select_productos != 0){
                $('#inv_cucuta').show();
                $('#div_productos').show();
                if(select_productos == 3){
                    $('#div_clasif').show();
                }else{
                    $('#div_clasif').hide();
                }
            }else{
                $('#inv_cucuta').hide();
                //$('#div_productos').hide();
                $('#div_clasif').hide();
            }

        }
        function conjuntos(nombre) {
            if (nombre == 'INVENTORY CUCUTA') {
                if($('#span_1').hasClass('glyphicon-minus')){
                    $('#INVENTORY_CUCUTA').hide();
                    $('#span_1').removeClass('glyphicon-minus');
                    $('#span_1').addClass('glyphicon-plus');
                }else{
                    $('#INVENTORY_CUCUTA').show();
                    $('#span_1').removeClass('glyphicon-plus');
                    $('#span_1').addClass('glyphicon-minus');
                }
            }else if(nombre == 'INVENTORY IN TRANSIT'){
                if($('#span_2').hasClass('glyphicon-minus')){
                    $('#INVENTORY_IN_TRANSIT').hide();
                    $('#span_2').removeClass('glyphicon-minus');
                    $('#span_2').addClass('glyphicon-plus');
                }else{
                    $('#INVENTORY_IN_TRANSIT').show();
                    $('#span_2').removeClass('glyphicon-plus');
                    $('#span_2').addClass('glyphicon-minus');
                }
            }else if(nombre == 'DIVISIONS'){
                if($('#span_3').hasClass('glyphicon-minus')){
                    $('#DIVISIONS').hide();
                    $('#span_3').removeClass('glyphicon-minus');
                    $('#span_3').addClass('glyphicon-plus');
                }else{
                    $('#DIVISIONS').show();
                    $('#span_3').removeClass('glyphicon-plus');
                    $('#span_3').addClass('glyphicon-minus');
                }
            }
        }
        function mover(movimiento){
            year = $('#year').val();
            numero_semanas = $('#num_weeks').val();
            semana_actual = $('#input_week').val();
            if(movimiento == 'atras'){
                if((semana_actual-1) > 0){
                    $('#input_week').val(semana_actual-1);
                    document.getElementById('semanas').innerHTML = year + ' - semana ' + (semana_actual-1);
                    $('#form_week').submit();
                }
            }else if(movimiento == 'adelante'){
                if((semana_actual+1) <= numero_semanas){
                    $('#input_week').val(parseInt(semana_actual)+1);
                    document.getElementById('semanas').innerHTML = year + ' - semana ' + (parseInt(semana_actual)+1);
                    $('#form_week').submit();
                }
            }else if(movimiento == 'recargo'){
                document.getElementById('semanas').innerHTML = year + ' - semana ' + (parseInt(semana_actual));
            }
        }
    </script>
</body>
</html>