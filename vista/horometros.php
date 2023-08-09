<?php
session_start();
//error_reporting(0);
require_once '../modelo/conexion.php';
if ($_SESSION["logueado"] == TRUE && ($_SESSION["permisoIngresar"] != 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] != 'MECANICO_CARGADORES')){
    $usuario = $_SESSION['usuario'];
    $Fecha = date('Y-m-d H:i:s');
    $Fecha_actual = date('Y-m-d',strtotime($Fecha . ' - 15 days'));
    ini_set('date.timezone', 'America/Bogota');
    $hora = date("g:i A");
    $sql_usuario = "SELECT * FROM Usuarios WHERE idUsuario='$usuario'";
    $result = sqlsrv_query($conn,$sql_usuario);
    while ($row = sqlsrv_fetch_array($result)){
      $Nombre = $row['NombreUsuarioLargo'];
    }
}elseif($_SESSION['permisoIngresar'] == 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES'){
    //header("Location: MantenimientoCargadores.php");
    ?>
    <script type="text/javascript">
        self.location='MantenimientoCargadores.php';
        alert('No tienes permiso para acceder a este ambiente');
    </script>
    <?php
}else{
    //header("Location: ../index.php");
    ?>
    <script type="text/javascript">
        self.location='../index.php';
        alert('Inicia sesión primero');
    </script>
    <?php
    die();
}
$reporte = $_GET['Reporte'];
if ($reporte < 10){
$reporte = '00'.$reporte;
}else if ($reporte < 100){
    $reporte = '0'.$reporte;
}else{
    $reporte = $reporte;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<?php include './libreria.php'; ?>
    <meta name="viewport" content="width=auto, initial-scale=0.8">
	<script type="text/javascript" src="../controlador/archivo.js"></script>
</head>
<body>
	<?php
    include 'Header.php';
    ?>
	<div class="container-fluid">
		<center><h3>Actividades del tiquete N° <?php echo $reporte; ?></h3></center>
		<br>
        <br>
        <a href="<?php if($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){   echo 'Admin.php';  }elseif($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){   echo 'inicio.php';  }elseif($_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO'){   echo 'inicio_patio.php';  } ?>"><button class="btn btn-default navbar-right" style="margin-right: 7px;"><span class="glyphicon glyphicon-home"></span></button></a>
        <br><br>
		<div class="table-responsive">
			<table class="table table-hover table-condensed table-bordered table-responsive table-striped">
			    <?php
			    $sql = "SELECT horometro.fecha_cierre_horometro ,horometro.id_horometro, horometro.id_registro, horometro.horometro_inicial, 
                horometro.horometro_final, horometro.total_horas, horometro.idActividad, horometro.fecha_registro_horometro, 
                Actividades.Descripcion, SubActividades_cargadores.Descripcion as Descripcion_sub, horometro.observaciones
                            FROM horometro LEFT JOIN
                            Actividades ON horometro.idActividad = Actividades.idActividad
                            LEFT JOIN SubActividades_cargadores ON horometro.idSubActividad=SubActividades_cargadores.idSubActividad
			    		   WHERE horometro.id_registro='". $_GET['idTiquete'] ."'";
			    $params = array();
	            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	            $result=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	            $rows=sqlsrv_num_rows($result);
	            if ($rows > 0) 
            	{   ?>
				    <tr>
                        <th style="width: 23%">Actividad</th>
                        <th style="width: 17%">SubActividad</th>
				        <th style="width: 12%">Horometro Inicial</th>
				        <!--<th>Horometro Final</th>-->
                        <th style="width: 12%">Horas de trabajo</th>
                        <th style="width: 14%">Horometro final</th>
				        <th style="width: 11%">Horas Maquina</th>
				        <th style="width: 8%"><center>Estado</center></th>
				    </tr>
				    <?php
                    $count_horas = 0;
                    $count_debe = 0;
                    $id = 0;
				    while ($horometro = sqlsrv_fetch_array($result)) {
                        //echo $horometro['id_horometro'].' - ';
                        //if ($horometro['Descripcion'] != "" && $horometro['Descripcion_sub'] != "" && $horometro['total_horas'] != 0){
                            $id++;
                            if ($horometro['Descripcion'] != "" && $horometro['Descripcion_sub'] != ""){
                                $count_horas += $horometro['total_horas'];
                            }else{
                                $count_debe += $horometro['total_horas'];
                            }
    				    	 $datos1 = utf8_encode($horometro['id_registro']). "," .
    				    			 $horometro['horometro_inicial']. "," .
    				    			 utf8_encode($horometro['Descripcion']). "," .
    				    			 $horometro['id_horometro']. ",".
                                     date_format($horometro['fecha_registro_horometro'],'Y-m-d H:i:s').",".
                                     utf8_encode($horometro['Descripcion_sub']);

                            $v = utf8_encode($horometro['Descripcion']);
    				        ?>
    				        <tr>
                                <td><?php
                                    if ($horometro['Descripcion'] != ""){
                                        echo utf8_encode($horometro['Descripcion']);  
                                    }else{
                                        if ($horometro['observaciones'] == ""){
                                            echo "<p style='color: red;'>--- POR ASIGNAR ---</p>";
                                        }else{
                                            echo utf8_encode($horometro['observaciones']);
                                        }
                                    }?>
                                </td>
                                <td><?php
                                    if ($horometro['Descripcion_sub'] != ""){
                                        echo utf8_encode($horometro['Descripcion_sub']);     
                                    }else{
                                        if ($horometro['observaciones'] == ""){
                                            echo "<p style='color: red;'>--- POR ASIGNAR ---</p>";
                                        }else{
                                            echo utf8_encode($horometro['observaciones']);
                                        }
                                    }?>
                                </td>
    				            <td><?php 
                                    if ($horometro['horometro_inicial'] == null){
                                        echo "<b>NO APLICA</b>";
                                    }else{
                                        echo $horometro['horometro_inicial']; 
                                    }
                                    ?>
                                </td>
    				            <!--<td><?php echo $horometro['horometro_final']; ?></td>-->
                                <td>
                                    <?php 
                                    if ($horometro['horometro_final'] == 0){
                                        ?>
                                        <script type="text/javascript">
                                            $(document).ready(function () {
                                                var n = moment('<?php echo date_format($horometro['fecha_registro_horometro'],'Y-m-d H:i:s'); ?>');
                                                var m = moment('<?php echo $Fecha; ?>');
                                                var id = '<?php echo $id; ?>';
                                                var minutes_inic = (n.hour()*60) + n.minute();
                                                //console.log("ini "+minutes_inic);
                                                var minutes_fin = (m.hour()*60) + m.minute(); 
                                                //console.log(minutes_fin);
                                                var t = (minutes_fin-minutes_inic);
                                                //console.log(t);
                                                var total = t/60;
                                                //console.log(total);
                                                var round = Math.round(total * 10) / 10;
                                                document.getElementById('temp'+id).innerHTML = round+' Hrs.';
                                                //console.log(round);
                                            });
                                        </script>
                                        <p id="temp<?php echo $id; ?>"></p>
                                    <?php
                                    }else{
                                    ?>
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                            var n = moment('<?php echo date_format($horometro['fecha_registro_horometro'],'Y-m-d H:i:s'); ?>');
                                            //var m = moment('<?php echo $Fecha; ?>');
                                            var m = moment('<?php echo date_format($horometro['fecha_cierre_horometro'],'Y-m-d H:i:s'); ?>');
                                            var id = '<?php echo $id; ?>';
                                            var minutes_inic = (n.hour()*60) + n.minute(); 
                                            //console.log("ini2 "+minutes_inic);
                                            var minutes_fin = (m.hour()*60) + m.minute(); 
                                            //console.log(minutes_fin);
                                            var t = (minutes_fin-minutes_inic);
                                            //console.log(t);
                                            var total = t/60;
                                            //console.log(total);
                                            var round = Math.round(total * 100) / 100;
                                            document.getElementById('temp'+id).innerHTML = round+' Hrs.';
                                            //console.log(round);
                                        });
                                    </script>
                                    <p id="temp<?php echo $id; ?>"></p>
                                <?php } ?>
                                </td>

                                <td>
                                    <?php 
                                    if ($horometro['horometro_final'] == 0){
                                        if ($horometro['horometro_inicial'] == null){
                                            echo "<b>NO APLICA</b>";
                                        }else{
                                            echo $horometro['horometro_inicial']; 
                                        ?>
                                        <script type="text/javascript">
                                            $(document).ready(function () {
                                                var n = moment('<?php echo date_format($horometro['fecha_registro_horometro'],'Y-m-d H:i:s'); ?>');
                                                var m = moment('<?php echo $Fecha; ?>');
                                                var horo_ini = <?php echo $horometro['horometro_inicial']; ?>;
                                                var id = '<?php echo $id; ?>';
                                                var minutes_inic = (n.hour()*60) + n.minute(); 
                                                var minutes_fin = (m.hour()*60) + m.minute(); 

                                                var t = (minutes_fin-minutes_inic)/60;
                                                var total = horo_ini+t;
                                                var round = Math.round(total * 10) / 10;
                                                document.getElementById('tiempo'+id).innerHTML = round+' Aproximado.';
                                                //console.log(horo_ini);
                                                //console.log(round);
                                            });
                                        </script>
                                        <?php
                                        }
                                    }else{
                                        ?>
                                        <script type="text/javascript">
                                            $(document).ready(function () {
                                                var n = moment('<?php echo date_format($horometro['fecha_registro_horometro'],'Y-m-d H:i:s'); ?>');
                                                var m = moment('<?php echo date_format($horometro['fecha_cierre_horometro'],'Y-m-d H:i:s'); ?>');
                                                var horo_fin = <?php echo $horometro['horometro_final']; ?>;
                                                //console.log(horo_fin);
                                                var id = <?php echo $id; ?>;
                                                var minutes_inic = (n.hour()*60) + n.minute(); 
                                                //console.log(minutes_inic);
                                                var minutes_fin = (m.hour()*60) + m.minute(); 
                                                //console.log(minutes_fin);
                                                var t = (minutes_fin-minutes_inic);
                                                //console.log(t);
                                                var total = t/60;
                                                //console.log(total);
                                                var round = Math.round(total * 10) / 10;
                                                document.getElementById('tiempo'+id).innerHTML = 'Finalizó en: '+horo_fin;
                                                //console.log(round);
                                            });
                                        </script>
                                        <?php
                                    }
                                    ?>
                                    <p id="tiempo<?php echo $id; ?>"></p>
                                </td>
    				            <td>
                                    <center>
                                        <?php echo number_format($horometro['total_horas'],2,',','.'); ?></center>
                                    </td>
    				            <td><center>
                                    <?php
                                    if ($horometro['horometro_final'] == 0){
                                        if ($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){ ?>
            				            	<button id="b" title="Finalizar tiquete" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalHorometro1" onclick="datos1('<?php echo $datos1; ?>')">
                                                <span class="glyphicon glyphicon-ok"></span>
                                            </button>
                                        <?php
                                        }else{
                                            echo "<b>NO APLICA</b>";
                                        }
                                    }else{
                                        echo "FINALIZÓ";
                                    }
                                    ?>
                                </center></td>
    				        </tr>
    				        <?php
                        //}
				    }
                    ?>
                    <?php 
                    if ($count_debe != 0){
                        ?>
                        <tr>
                            <td colspan="5" align="right">
                                Falta Asignar:
                                <br>Asignado:
                                <br><b>TOTAL HORAS:</b>
                            </td>
                            <td>
                                <center>
                                    <?php echo number_format($count_debe,1,',','.'); ?>
                                    <br><?php echo number_format($count_horas,1,',','.'); ?>
                                    <br><b><?php $count_total = ($count_debe - $count_horas); echo number_format($count_total,1,',','.'); ?></b>
                                </center>
                            </td>
                        </tr>
                        <?php
                    }else{
                        ?>
                        <tr>
                            <td colspan="5" align="right"><b>TOTAL HORAS:</b></td>
                            <td><center><b><?php echo number_format($count_horas,1,',','.'); ?></b></center></td>
                        </tr>
                        <?php
                    }
				}else{
					echo "<br><br><center>No hay Actividades registradas por el momento</center>";
				}
			    ?>
			</table>
		</div>
	</div>
	<!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <div class="modal fade" id="modalHorometro1" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Finalizar Actividad</h4>
                </div>
                <div class="modal-body">
                	<input type="hidden" id="horometro1">
                    <input type="hidden" id="registroHorometro1">
                    <div class="row form-group center-block">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            <label>Fecha Registro</label>
                            <input type="text" id="fecha_inic" class="form-control" readonly="">
                        </div>
                        <div class="col-sm-4">
                            <label>Fecha Cierre</label>
                            <input type="text" id="fecha" value="<?php echo $Fecha; ?>" class="form-control" readonly>
                        </div>
                        
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-4">
                            <label>Horometro inicial:</label>
                            <input  type="text" id="horo_inicia" class=" form-control" readonly="">
                        </div>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            <label>Horometro final:</label>
                            <input  type="text" id="horo_fina" class="form-control" autofocus="autofocus">
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <label>Actividad</label>
                            <input type="text" class="form-control" id="Activida" readonly="">
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-6">
                            <label>SubActividad</label>
                            <input type="text" class="form-control" id="SubActivida" readonly="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="AgregarHorometro">
                        Finalizar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO
    <div class="modal fade" id="modalHorometroAgregar" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Iniciar Actividad</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="registroHorometro_h">
                    <input type="hidden" id="var" value="10">
                    <div class="row form-group center-block">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-4">
                            <label>Horometro inicial:</label>
                            <input  type="text" id="horo_inicial_h" class=" form-control" readonly="">
                        </div>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            <label>Hora inicio actividad</label>
                            <input type="text" id="FechaHora_ini" class="form-control" readonly value="<?php echo $f = date('H:i:s'); ?>">
                        </div>
                        <div class="col-sm-1"></div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-2"></div>
                       <div class="col-sm-8">
                            <label>Actividad</label>
                            <select class="form-control" id="actividad_h">
                                <option>--- Seleccione ---</option>
                                <?php
                                $sql = "SELECT * FROM Actividades";
                                $res = sqlsrv_query($conn,$sql);
                                while($actividad = sqlsrv_fetch_array($res)){
                                ?>
                                <option value="<?php echo $actividad['idActividad']; ?>"><?php echo utf8_encode($actividad['Descripcion']); ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" id="AgregarHorometro1">
                        Agregar
                    </button>
                </div>
            </div>
        </div>
    </div>-->
    <script type="text/javascript">
    	$('#AgregarHorometro').click(function (){
            //horo_inicial = $('#horo_inicia').val();
            //horo_final = $('#horo_fina').val();
            //console.log(horo_inicial);
            //console.log(horo_final);
            //if (horo_inicial > horo_final){
                //alert('El horometro final es menor al inicial.');
            //}else{
                AgregarHorometroUpdate();
            //}
        });
        $('#AgregarHorometro1').click(function (){
            AgregarHorometro();
        });
    </script>
</body>
</html>