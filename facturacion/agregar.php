<?php
session_start();
error_reporting(0);
include_once("../modelo/conexion.php");
$Fecha1 = date('Y-m-d');
include('../vista/tipo_dispositivo.php');
if ($tablet_browser > 0) {
    // Si es tablet has lo que necesites
    //print 'es tablet';
    ?>
    <script type="text/javascript">
        self.location='../vista/Admin.php';
        alert('Accede desde un ordenador');
    </script>
    <?php
}
else if ($mobile_browser > 0) {
    // Si es dispositivo mobil has lo que necesites
    //print 'es un mobil';
    ?>
    <script type="text/javascript">
        self.location='../vista/Admin.php';
        alert('Accede desde un ordenador');
    </script>
    <?php
}
else {
    // Si es ordenador de escritorio has lo que necesites
    //print 'es un ordenador de escritorio';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="width=auto, initial-scale=0.8">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />	
	<title>Consultar</title>
	<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<!--<script type="text/javascript" src="../js/AlertaDiv.js" ></script>
	<script type="text/javascript" src="../js/ConfirmacionDiv.js" ></script>
	<script language="javascript" src="../js/dhtmlgoodies_calendar.js" ></script>
	<script language="javascript" src="../js/jpfunction.js" ></script>
	<script type="text/javascript" src="../js/moment.js" ></script>
	<link href = "../css/bootstrap.min.css" rel= "stylesheet">-->
	<link href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css" type="text/css" rel="stylesheet">
	<!--<script language="JavaScript" src="../frame_tamaño.js"></script>-->
	<script language="JavaScript">
	   	$(document).ready(function(){
	   		$('#href').hide();
	   		document.getElementById('title_factura').innerHTML = 'Pre-Liquidacion: 0';
	   		llenar_pre_fact();
    		//$('#selector-cliente select').html('<option value="0">No Existe</option>');
    		//$('#ver_tablas').hide();
		});

		function select_cliente(){
			document.getElementById('fact_pendientes').disabled="";
			var cliente=$('#cliente').val();
			$('#input_cliente').val(cliente);
			llenar_pre_fact();
		}

		function Nuevo()
	   	{	var b = 1;
	   		texto="Seleccione:<br><br>\n";
	   		if($('#empresa').val()=='0'){
	   			texto+= "\n- Una empresa<br>";
				b=0;
	   		}
	   		if($('#cliente').val()=='0')
	   		{	texto+= "\n- Un Cliente<br>";
				b=0;  	}

			if(b==0)
			{  alert(texto);  }
			else
			{	$('#form2').submit();
				$('#operacion').val(40);
				$('#consultas').submit();	

	   		}
	   	}

	   	function validar()
	   	{ 	centinela=1;
	   		var empresa = $('#empresa').val();
	   		var cliente = $('#cliente').val();
	   		var factura = $('#select_fact').val();
	   		var clasificacion = $('#selec_produtos').val();
	   		var fecha_ingreso = $('#fecha_ingreso').val();
	   		var fecha_salida = $('#fecha_salida').val();
	   		texto="Seleccione estos campos:<br><br>\n";

			if(empresa == '0')
			{   texto+= "\n- Seleccione una empresa<br>";
				centinela=0;
			}
			/*if(fecha_ingreso == '')
			{   texto+= "\n- Seleccione una fecha de ingreso<br>";
				centinela=0;
			}
			if(fecha_salida == '')
			{   texto+= "\n- Seleccione una fecha de salida<br>";
				centinela=0;
			}*/
			if(fecha_ingreso > fecha_salida)
			{   texto+= "\n- Fecha ingreso mayor que: "+fecha_salida+"<br>";
				centinela=0;
			}
			if(centinela!=0)
			{  $('#operacion').val(30);
				$('#consultas').submit();	
			}
		}
	</script>
</head>
	<!--<script language="javascript" src="../js/validar.js" ></script>-->
	
<body>
	<?php
	include('Header.php');
		   	$seleccion_emp = "";
		   	$seleccion_trans = "";
		   	if($_SERVER['REQUEST_METHOD']=='POST')
		 	{	//$seleccion_emp = $_POST['empresa'];
		 		//$seleccion_trans = $_POST['transportador'];
			}
			//1B2B9F37-386A-4509-BFF4-5EFBB1267698 FACTURA
			//24B7153E-AB4C-4DB7-81BD-67BC87AF014C EMPRESA
			$sql="SELECT Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, 
         Registro_tique_cargadores.id_usuario, Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, 
         Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado, Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, 
         Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, Usuarios.NombreUsuarioLargo, P.NombreCorto AS proveedor, Registro_tique_cargadores.horometro_ini, Registro_tique_cargadores.horometro_fin, 
         TarifaMaquinaria.Tarifa_Toneladas, TarifaMaquinaria.Tarifa_Horometro, TarifaMaquinaria.Iva, horometro_descuento_cargadores.tm_despacho, D.Descripcion AS Destino_cargue
        FROM Registro_tique_cargadores INNER JOIN
           Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
           Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
           Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
           Proveedores AS P ON Equipos.idPropietario = P.idProveedor INNER JOIN
           Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario INNER JOIN
           TarifaMaquinaria ON Registro_tique_cargadores.id_maquinaria = TarifaMaquinaria.idEquipo LEFT JOIN
           horometro_descuento_cargadores ON Registro_tique_cargadores.id_registro = horometro_descuento_cargadores.idRegistro LEFT JOIN
           Destino AS D ON horometro_descuento_cargadores.id_destino=D.idDestino
        WHERE (Registro_tique_cargadores.estado = '3') AND (Registro_tique_cargadores.id_registro IN
             (SELECT        numerotransaccion
               FROM            Factura_Venta_Detalle
               WHERE        (id_factura_venta = '".$_POST['fact_pendientes']."'))) AND (Registro_tique_cargadores.id_proveedor = '0247FA36-ABF5-4A35-9E8B-BE7C574243DE') AND 
         (TarifaMaquinaria.Fecha_Hasta = '1900-01-01')
        ORDER BY Registro_tique_cargadores.cod_reporte DESC";
        $corre = sqlsrv_query($conn,$sql);
		$ya_paso = "paso";
		$posi = 0;
		$posi1 = 0;
		$lines = 0;
		$actividad = "aaa";
		$id = 0;
		$letra_fin = 'K';
		    while($save = sqlsrv_fetch_array($corre)){
		      $cod_reporte = $save['cod_reporte'];
		      $id_registro = $save['id_registro'];
		      $Array_id_regstro[$id]=$save['id_registro'];
		      $id++;
		    }
		    $Array_id_regstro = implode("','", $Array_id_regstro);
		    $query = "SELECT Actividades.Descripcion, subactividades_cargadores.Descripcion SubActividad,sum(horometro.total_horas) total_horas
				FROM horometro INNER JOIN
                     Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
                     subactividades_cargadores ON horometro.idActividad = subactividades_cargadores.idActividad AND horometro.idSubActividad = subactividades_cargadores.idSubactividad
				WHERE (horometro.id_registro in ('$Array_id_regstro')) and horometro.tipo_tarifa=0
				GROUP BY Actividades.Descripcion, subactividades_cargadores.Descripcion
				ORDER BY Actividades.Descripcion, subactividades_cargadores.Descripcion";
		      $corre1 = sqlsrv_query($conn,$query);
		      while($save1 = sqlsrv_fetch_array($corre1)){
		      	if($actividad == 'aaa'){
		      		$actividad = $save1['Descripcion'];
		      		$posi1++;
		      	}elseif($actividad == $save1['Descripcion']){
		      		$posi1++;
		      	}else{
		      		$Array_destino_cant[$actividad] = $posi1;
		      		$posi1 = 1;	
		      	}
		          $Array_destino_nom[$posi]['Act']=$save1['Descripcion'].'-'.$save1['SubActividad'];
		          $Array_destino_nom[$posi]['position']=$letra_fin;
		          $Array_destino_nom[$posi]['horas']=$save1['total_horas'];
		          $letra_fin++;
		          $posi++;
		      }
		      $Array_destino_cant[$actividad] = $posi1;
		    /*echo "<pre>";
		    print_r($Array_destino_cant);
			echo "</pre>";*/
$position = 0;
$result = array();
foreach($Array_destino_nom as $t) {
	$repeat=false;
	for($i=0;$i<count($result);$i++)
	{	if($result[$i]['Act']==$t['Act'])
		{	//if($result[$i]['SubAct']==$t['SubAct'])
			{	$position++;
				$result[$i]['horas']=$t['horas'];
				$result[$i]['position']=$t['position'];
				$repeat=true;
				break;
			}
		}
	}
	if($repeat==false)
		$result[] = array('Act' => $t['Act'], 'position' => $t['position'], 'horas' => $t['horas']);
}
/*echo "<pre>";
print_r($result);
$letra_fin = 'J';
print_r($Array_position_nom);
echo "</pre>";*/
	?>
<!--<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>-->
<div class="container-fluid"> 
    <div class="row" style="margin-left: -10px; margin-top: 5px;">
    	<div class="col-sm-6" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px;">
    		<div class="row">
    			<div class="col-sm-7" style="margin-top: 15px;">
    				<div class="row">
    					<div class="col-sm-1"></div>
    					<div class="col-sm-11">
    						<center>
    							<h4 id="title_factura"></h4>
    						</center>
    					</div>
    				</div>
    				<div class="row">
    					<div class="col-sm-5"><h6>Fecha pre-liquidación: </h6></div>
    					<div class="col-sm-7"><input type="text" style="border-color: #3CB6EB;" name="fecha_pre" id="fecha_pre" class="input-sm form-control" readonly></div>
    				</div>
    				<div class="row">
    					<div class="col-sm-5"><h6>Fecha factura: </h6></div>
    					<div class="col-sm-7"><input type="date" style="border-color: #3CB6EB;" id="fecha_fact_asentada" class="input-sm form-control" max="<?php echo $Fecha1; ?>" disabled ></div>
    				</div>
    				<div class="row">
    					<div class="col-sm-5"><h6>Factura Asociada: </h6></div>
    					<div class="col-sm-7"><input type="text" name="fact_asociada" id="fact_asociada" class="form-control" disabled="" placeholder="Ej: 0277.."></div>
    				</div>
    				<div class="row" style="margin-bottom: 5px;">
		    			<div class="col-sm-4">
		    				<button id="guardar" class="btn btn-warning navbar-left" onclick="asentar_liq()" disabled>Guardar <span class="glyphicon glyphicon-save"></span></button>
		    			</div>
		    		</div>
    			</div>
    			<div class="col-sm-5">
    				<div class="row" style="margin-top: 15px;">
    					<div class="col-sm-2"><h6>Horas:</h6></div>
    					<div class="col-sm-3"><h5 id="title_tm_despacho" ></h5></div>
    					<div class="col-sm-1"><h6>$</h6></div>
    					<div class="col-sm-4"><h5 id="title_tm_llegada"></h5></div>
    				</div>
    				<div class="row">
    					<div class="col-sm-2"><h6>Tm:</h6></div>
    					<div class="col-sm-3"><h5 id="title_tm_llegada2"></h5></div>
    					<div class="col-sm-1"><h6>$</h6></div>
    					<div class="col-sm-4"><h5 id="title_tm_llegada1"></h5></div>
    				</div>
    				<div class="row">
    					<div class="col-sm-2"><h6>Tiques:</h6></div>
    					<div class="col-sm-4"><h5 id="title_viajes"></h5></div>
    					<div class="col-sm-1"></div>
    				</div>
    				<div class="row" style="margin-top: 35px; margin-bottom: 5px;">
		    			<div class="col-sm-2"></div>
		    			<div class="col-sm-6">
		    				<button id="Nuevo_reg" class="btn btn-primary navbar-right" disabled onclick="Nuevo()">Nueva 
		    					<span class="glyphicon glyphicon-plus"></span></button>
		    			</div>
		    			<div class="col-sm-1">
	    				<center>
	    				</center>
	    			</div>
		    		</div>
    			</div>
    		</div>
    	</div>
    	<div class="col-sm-6" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px;">
    		<form name="consultas" id="consultas" action="ctr.php" accept-charset="utf-8" method="POST">
    		 	<input type="hidden" name="operacion" id="operacion">
    		 	<div class="row" style="margin-top: 5px; margin-bottom: 5px">
	    			<div class="col-sm-2">
	    				<h6>Empresa: </h6>
	    			</div>
	    			<div class="col-sm-8">	    				       
				        <select name="empresa" style="border-color: #3CB6EB;" id="empresa" class="combo form-control" onchange="llenar_pre_fact(); validar();">
				        	<option value="0">Seleccione</option>
				        	<?php
				        	$sql = "SELECT NombreCorto, idProveedor FROM proveedores WHERE Empresa=1 ORDER BY NombreCorto";
							$empresa = sqlsrv_query($conn,$sql);		
				        	while($rows = sqlsrv_fetch_array($empresa)) 
				        	{	$id_empresa=$rows['idProveedor'];
				        		$nom_empresa=$rows['NombreCorto'];
				        	?><option value="<?php echo $id_empresa; ?>" <?php if(isset($_POST['empresa'])){if($_POST['empresa'] == $id_empresa){  echo 'selected';	}} ?>><?php echo utf8_encode($nom_empresa); ?></option>
				        <?php }
				        	?>
				        </select>
	    			</div>
	    		</div>
	    		<div class="row" style="margin-bottom: 5px;">
	    			<div class="col-sm-2">
			    		 <h6>Cliente: </h6>
			    	</div>
					<div class="col-sm-6" id="selector-cliente"> <!-- -->
						<!--<input type="hidden" name="input_cliente" id="input_cliente" value="<?php if(isset($_POST['cliente'])){if($_POST['cliente'] != '0'){  echo $_POST['cliente'];	}} ?>">-->
						<select id="cliente" style="border-color: #3CB6EB;" name="cliente" class=" form-control" onchange="select_cliente(); validar();"> 
				        	<option>Seleccione</option>
				        	<?php 
				        	$consulta = "SELECT * FROM Proveedores WHERE idProveedor in (SELECT idProveedor FROM  vProveedoresInAgrupacion WHERE Alias='PC') ORDER BY RazonSocial";
				        	$res = sqlsrv_query($conn,$consulta);
				        	while($provee = sqlsrv_fetch_array($res)){
				        		?><option value="<?php echo $provee['idProveedor']; ?>" <?php if(isset($_POST['cliente'])){ if($_POST['cliente'] == utf8_encode($provee['idProveedor'])){ echo 'selected'; }else{ echo 0; } }else{ echo 0; } ?>><?php echo utf8_encode($provee['RazonSocial']); ?></option><?php
				        	}
				        	?>
				        </select>
					</div>
	    		</div>
	    		<div class="row">
	    			<div class="col-sm-2">
	    				<h6>Fecha Ingreso:</h6>
	    			</div>
	    			<div class="col-sm-4">
	    				<input type="date" style="border-color: #3CB6EB;" class="input-sm form-control" name="fecha_ingreso" id="fecha_ingreso" required="" onchange="validar();" value="<?php if(isset($_POST['fecha_ingreso'])){  echo $_POST['fecha_ingreso'];	} ?>">
	    			</div>
	    		</div>
	    		<div class="row">
	    			<div class="col-sm-2" >
	    				<h6>Fecha salida:</h6>
	    			</div>
	    			<div class="col-sm-4">
	    				<input type="date" style="border-color: #3CB6EB;" size="100%" class="input-sm form-control" name="fecha_salida" id="fecha_salida" required="" onchange="validar();" value="<?php if(isset($_POST['fecha_salida'])){  echo $_POST['fecha_salida'];	} ?>">
	    			</div>
	    		</div>
	    		<div class="row">
	    			<div class="col-sm-2">
	    				<h6>Pre-Liquidacion:</h6>
	    			</div>
	    			<div class="col-sm-4">
	    				<input class="input-sm form-control" type="hidden" name="selected" id="selected" value="<?php if(isset($_POST['fact_pendientes'])){	if($_POST['fact_pendientes'] != '0'){  echo $_POST['fact_pendientes'];	}else{ echo 0;} } ?>">
	    				<select id="fact_pendientes" style="border-color: #3CB6EB;" name="fact_pendientes" class="combo form-control" onchange="validar()" disabled>
				        	<option value="0">- - </option>
				        </select>
	    			</div>
	    		</div>
	    	</form>
    	</div>
	</div>
	<div class="row" id="ver_tablas">
		<div class="col-sm-6">
    		
    		<!--<div class="col-sm-6">
    		<?php 
    		$sq = "SELECT Registro_tique_cargadores.cod_reporte, tiempos_cargadores_actividad.idDestino, Destino.Descripcion from tiempos_cargadores_actividad inner join Registro_tique_cargadores ON tiempos_cargadores_actividad.idRegistro=Registro_tique_cargadores.id_registro left JOIN Destino ON tiempos_cargadores_actividad.idDestino=Destino.idDestino
    		where tiempos_cargadores_actividad.idRegistro in (SELECT idRegistro from horometro_descuento_cargadores)
    		 group by Registro_tique_cargadores.cod_reporte, tiempos_cargadores_actividad.idDestino, Destino.Descripcion order by Registro_tique_cargadores.cod_reporte";
    		$w = sqlsrv_query($conn,$sq);
    		while($dd = sqlsrv_fetch_array($w)){
    			echo $dd['cod_reporte'].' - '.$dd['Descripcion'].'<br>';
    		}
    		?>
    		</div><div class="col-sm-6">
    		<?php
    		$sq = "SELECT Registro_tique_cargadores.cod_reporte, horometro_descuento_cargadores.id_destino, Destino.Descripcion from horometro_descuento_cargadores inner join Registro_tique_cargadores ON horometro_descuento_cargadores.idRegistro=Registro_tique_cargadores.id_registro left join Destino on horometro_descuento_cargadores.id_destino=Destino.idDestino
    		group by Registro_tique_cargadores.cod_reporte, horometro_descuento_cargadores.id_destino, Destino.Descripcion order by Registro_tique_cargadores.cod_reporte";
    		$w = sqlsrv_query($conn,$sq);
    		while($dd = sqlsrv_fetch_array($w)){
    			echo $dd['cod_reporte'].' - '.$dd['Descripcion'].'<br>';
    		}
    		?></div>-->
    		<center><h4 id="tittle_factura">FACTURA</h4></center>
    		<a href="../plantillas/reporte_liquidacion_tique1.php?empresa=<?php echo $_POST['empresa']; ?>&factura=<?php echo $_POST['fact_pendientes']; ?>"><button id="href" class="btn btn-success btn-xs" style="margin-bottom: 5px;"><span class="glyphicon glyphicon-eye-open"></span> Liquidacion</button></a>
    		<div class="table-responsive" style="height: 550px;">
    		<form name="form2" id="form2">
    			<table class="table table-hover table-condensed table-bordered table-responsive table-striped">
        			<thead>
            			<tr>
	        				<th>Tique</th>
	        				<th>Patio</th>
	        				<th>Cargador</th>
	        				<th>Operario</th>
	        				<th>Jornada inicial</th>
	        				<th>Jornada final</th>
	        				<th>Hrs.</th>
	        				<th>Fecha distribución</th>
	        			</tr>
	            	</thead>
	            	<tbody id="table3">
	                <?php
	                $consulta_fact = 0;
	                if(isset($lista_preFac)){
	                	//echo 'lista_preFac';
	            		$consulta_fact = $lista_preFac;		
	         		}
	         		if(isset($nueva_preFac)){
	         			//echo 'nueva_preFac';
	         			$consulta_fact = $nueva_preFac;		
	         		}
	         		//echo $consulta_fact;
	         		// VARIABLES
	         		$numero_fact = 0;
	         		$horas = 0;
	         		$cant_tiques = 0;
	         		$tot_tarifa = 0;
	         		$tot_tarifa1 = 0;
	         		$tm = 0;
	         		$valor_hora = 0;
					$valor_tm = 0;
	            	$tm_llegada = 0;
	            	$cont_viajes = 0;
	            	$fecha_pre_liq=0;
	            	//
	         		if($consulta_fact != 0){
	         			?><script type="text/javascript"> document.getElementById('fact_asociada').disabled=false; </script><?php
		         		while($fact = sqlsrv_fetch_array($consulta_fact))
	            		{	$estado = $fact['estado'];
	            			$fact_aso = $fact['factura_asociada'];
	            			$fecha_fact = date_format($fact['fecha_factura'],'Y-m-d'); ?>
	            			<input type="hidden" id="id_factura_venta" value="<?php echo $fact['id_factura_venta']; ?>"> <?php
	            			$numero_fact = $fact['prefijo_factura'].' - '.$fact['numero_factura'];
	            			$fecha_pre_liq = date_format($fact['fecha_elaboracion'], 'd/m/Y');

	            			/*$sql= "SELECT Registro_tique_cargadores.id_registro, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.cod_reporte, 
							                         Usuarios.NombreUsuarioLargo, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.fecha_fin_jornada, TarifaMaquinaria.Tarifa_Toneladas, 
							                         TarifaMaquinaria.Tarifa_Horometro, SUM(tiempos_cargadores_actividad.TM_total) AS tm_despacho, tiempos_cargadores_actividad.tipo_tarifa
							FROM Registro_tique_cargadores INNER JOIN
							                         Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
							                         Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
							                         Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario INNER JOIN
							                         Factura_Venta_Detalle ON Registro_tique_cargadores.id_registro = Factura_Venta_Detalle.numerotransaccion INNER JOIN
							                         tiempos_cargadores_actividad ON Registro_tique_cargadores.id_registro = tiempos_cargadores_actividad.idRegistro LEFT OUTER JOIN
							                         TarifaMaquinaria ON Registro_tique_cargadores.id_maquinaria = TarifaMaquinaria.idEquipo
							WHERE (Factura_Venta_Detalle.id_factura_venta = '".$fact['id_factura_venta']."') AND (TarifaMaquinaria.Fecha_Hasta = '1900-01-01')
							GROUP BY Registro_tique_cargadores.id_registro, Destino.Descripcion, Equipos.Descripcion, Equipos.Identificacion, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.cod_reporte, 
							                         Usuarios.NombreUsuarioLargo, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.fecha_fin_jornada, TarifaMaquinaria.Tarifa_Toneladas, 
							                         TarifaMaquinaria.Tarifa_Horometro,  tiempos_cargadores_actividad.tipo_tarifa
							ORDER BY Registro_tique_cargadores.cod_reporte DESC";*/
							$sql="SELECT  Registro_tique_cargadores.id_registro, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
								Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.cod_reporte, Usuarios.NombreUsuarioLargo, 
							    Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.fecha_fin_jornada, 
							    horometro_descuento_cargadores.valor_descuento, horometro_descuento_cargadores.tm_despacho, TarifaMaquinaria.Tarifa_Toneladas, TarifaMaquinaria.Tarifa_Horometro
							FROM Registro_tique_cargadores INNER JOIN
							     Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
							     Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
		                         Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario INNER JOIN
		                         Factura_Venta_Detalle ON Registro_tique_cargadores.id_registro = Factura_Venta_Detalle.numerotransaccion LEFT JOIN
		                         horometro_descuento_cargadores ON Registro_tique_cargadores.id_registro = horometro_descuento_cargadores.idRegistro INNER JOIN
		                         TarifaMaquinaria ON Registro_tique_cargadores.id_maquinaria = TarifaMaquinaria.idEquipo
							WHERE (Factura_Venta_Detalle.id_factura_venta = '".$fact['id_factura_venta']."') and TarifaMaquinaria.Fecha_Hasta = '1900-01-01'
							ORDER BY Registro_tique_cargadores.cod_reporte DESC";
						 	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	            			$params = array();
						   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
						   	$resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
						   	$cont_viajes=sqlsrv_num_rows($resultado);
						   	if($cont_viajes > 0){
						   		while($user = sqlsrv_fetch_array($resultado)){
						   			$cant_tiques++;
						   			$tot_tarifa+=$user['Tarifa_Horometro'];
						   			$tot_tarifa1+=$user['Tarifa_Toneladas'];
									$horas+=$user['horas_trabajadas'];
									$tm+=$user['tm_despacho'];
								//$tm_llegada+=$user['ToneladasRecepcion'];
								//$fecha_prefac=date_format($user['fecha_elaboracion'],'d-m-Y');
								?>
						   		<tr id="<?php echo $a;?>">
		                			<td hidden=""><input type="checkbox" name="facturacion[]" value="<?php echo $user['id_registro']; ?>"></td>
		                			<td><?php echo $user['cod_reporte']; ?></td>
		                			<td><?php echo utf8_encode($user['Descripcion']); ?></td>
		                			<td><?php echo utf8_encode($user['NombreCargador']).' - '.$user['Identificacion']; ?></td>
		                			<td><?php echo utf8_encode($user['NombreUsuarioLargo']); ?></td>
		                			<td><?php echo date_format($user['fecha_apertura_tique'],'Y-m-d'); ?></td>
		                			<td><?php echo date_format($user['fecha_cierre_tique'],'Y-m-d'); ?></td>
					                <td><?php echo number_format($user['horas_trabajadas'],1, ',', '.'); ?></td>
					                <td><?php echo date_format($user['fecha_fin_jornada'],'Y-m-d'); ?></td>
					            </tr>
						   	<?php
						   		}
						   		$tot_tarifa=$tot_tarifa/$cant_tiques;
						   		$valor_hora = $tot_tarifa*$horas;
						   		$tot_tarifa1=$tot_tarifa1/$cant_tiques;
						   		$valor_tm = $tot_tarifa1*$tm;
						   	}
	            			?>
	         	<?php 	}
	         		}?>
	         		<input type="hidden" id="fact_aso" value="<?php echo $fact_aso; ?>">
	         		<input type="hidden" id="fecha_fact" value="<?php echo $fecha_fact; ?>">
	         		<input type="hidden" id="tm_despacho" value="<?php echo number_format($horas,1); ?>">
	         		<input type="hidden" id="tm_llegada" value="<?php echo number_format($valor_hora); ?>">
	         		<input type="hidden" id="tm_llegada1" value="<?php echo number_format($valor_tm); ?>">
	         		<input type="hidden" id="tm_llegada2" value="<?php echo number_format($tm,2); ?>">
	         		<input type="hidden" id="cont_viajes" value="<?php echo $cont_viajes; ?>">
	         		<input type="hidden" id="fecha_prefac" value="<?php echo $fecha_pre_liq; ?>">
	         		<input type="hidden" id="prefijo_fact" value="<?php echo $numero_fact; ?>">
	         		<input type="hidden" id="input_guardar" value="0">
	            	</tbody>
	            	<tbody id="table2"></tbody>
    			</table>
    		</form>
    		</div>
		</div>
		<div class="col-sm-6">
    		<center><h4>TIQUETES</h4></center>
    		<div class="table-responsive" style="height: 550px;">
    		<form name="form3" id="form3">
    			<table class="table table-hover table-condensed table-bordered table-responsive table-striped">
        		<thead>
        			<tr>
        				<th>Tique</th>
        				<th>Patio</th>
        				<th>Cargador</th>
        				<th>Operario</th>
        				<th>Jornada inicial</th>
        				<th>Jornada final</th>
        				<th>Hrs.</th>
        				<th>Fecha distribución</th>
        			</tr>
	            </thead>
        		<tbody id="table1">
            		<?php
            		$a = 0;
            		if(isset($viajes))
		        	{  	?><script type="text/javascript"> document.getElementById('Nuevo_reg').disabled=false; document.getElementById('fact_pendientes').disabled=false; </script><?php
		        		while($user = sqlsrv_fetch_array($viajes))
	            		{	?>
	            			<tr id="<?php echo $a;?>">
	                			<td hidden=""><input type="checkbox" name="viajes[]" value="<?php echo $user['id_registro']; ?>"></td>
	                			<td><?php echo $user['cod_reporte']; ?></td>
	                			<td><?php echo utf8_encode($user['Descripcion']); ?></td>
	                			<td><?php echo utf8_encode($user['NombreCargador']).' - '.$user['Identificacion']; ?></td>
	                			<td><?php echo utf8_encode($user['NombreUsuarioLargo']); ?></td>
	                			<td><?php echo date_format($user['fecha_apertura_tique'],'Y-m-d'); ?></td>
	                			<td><?php echo date_format($user['fecha_cierre_tique'],'Y-m-d'); ?></td>
				                <td><?php echo number_format($user['horas_trabajadas'],1, ',', '.'); ?></td>
				                <td><?php echo date_format($user['fecha_fin_jornada'],'Y-m-d'); ?></td>
				            </tr>
	         	<?php 	}
	         		}?>
        		</tbody>
        		<tbody id="table4"></tbody>
    		</table>
			</form>
			</div>
		</div>
	</div>
	<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="multiselect.js" type="text/javascript"></script>
	<script type="text/javascript">
		<?php 
		if($estado == 0){
		?>
		    if (window.document.addEventListener){
		       window.document.addEventListener("keydown", myFunction2, false);
		    } else {
		       window.document.attachEvent("onkeydown", myFunction2);
		    }
		<?php
		}
		?>

	    function llenar_pre_fact(){
			var empresa = $('#empresa').val();
			//var busqueda = $('#busqueda').val();
			var cliente = $('#cliente').val();
		//	alert(cliente);
			//$('#input_cliente').val(cliente);
			var band = 2;	
			var selected = 0
			<?php if(isset($_POST['fact_pendientes'])){
				?>
				$('#href').show();
				var fact_aso = $('#fact_aso').val();
				var fecha_fact = $('#fecha_fact').val();
				$('#fecha_fact_asentada').val(fecha_fact);
				$('#fact_asociada').val(fact_aso);
				var prefijo_fact = $('#prefijo_fact').val();
				var tm_despacho = $('#tm_despacho').val();
				var tm_llegada =$('#tm_llegada').val();
				var tm_llegada1 =$('#tm_llegada1').val();
				var tm_llegada2 =$('#tm_llegada2').val();
				var cont_viajes = $('#cont_viajes').val();
				var fecha_prefac = $('#fecha_prefac').val();
				document.getElementById('title_factura').innerHTML = 'Pre-Liquidacion: '+prefijo_fact;
				document.getElementById('title_tm_despacho').innerHTML = tm_despacho;
				document.getElementById('title_tm_llegada').innerHTML = tm_llegada;
				document.getElementById('title_tm_llegada1').innerHTML = tm_llegada1;
				document.getElementById('title_tm_llegada2').innerHTML = tm_llegada2;
				document.getElementById('title_viajes').innerHTML = cont_viajes;
				document.getElementById('fecha_pre').value = fecha_prefac;
				document.getElementById('fact_pendientes').disabled="";
				if($('#selected').val() != '0'){
					document.getElementById('Nuevo_reg').disabled="";
				}else{
					document.getElementById('Nuevo_reg').disabled=true;
				}
				<?php
				if($_POST['fact_pendientes'] != '0'){
					?>
					document.getElementById('guardar').disabled="";
					document.getElementById('fecha_fact_asentada').disabled=false;
					selected = $('#selected').val();
			//		alert(selected);
					//cliente = $('#input_cliente').val();
					<?php
				}else{
				?>	document.getElementById('selected').value='0';
					document.getElementById('guardar').disabled=true;
					document.getElementById('Nuevo_reg').disabled=false;
					document.getElementById('fecha_fact_asentada').disabled=true;					
				<?php
				}
				if($estado == 1){
					?>
					document.getElementById('tittle_factura').style="margin-bottom: -15px;";
					document.getElementById('fact_asociada').disabled=true;
					document.getElementById('Nuevo_reg').disabled=true;
					document.getElementById('guardar').disabled=true;
					document.getElementById('fecha_fact_asentada').disabled=true;	
					<?php
				}
			} ?>
			//if(empresa != '0' && cliente != ""){
				$.post("buscar.php", {band: band, empresa: empresa, cliente: cliente, seleccionado: selected}, 
		    	function(mensaje) {
		            $('#fact_pendientes').html(mensaje).fadeIn();
		        });
			/*}else{
				$('#fact_pendientes').html('<option value="0">- -</option>');
			}*/
		}

		function asentar_liq(){
			var fecha_fact_asentada = $('#fecha_fact_asentada').val();
			var id_factura_venta = $('#id_factura_venta').val();
			var fact_asociada = $('#fact_asociada').val();
			var tm_despacho = $('#tm_despacho').val();
			var tm_llegada = $('#tm_llegada').val();
			var tm_llegada1 = $('#tm_llegada1').val();
			var cont_viajes = $('#cont_viajes').val();
			var empresa=$('#empresa').val();
			var operacion = 50;
			var band = 0;
			texto="Falta:<br>\n";
			if(fecha_fact_asentada == ''){
				texto+= "\n- Seleccionar una fecha de facturación<br>";
				band = 1;
			}
			if(cont_viajes == 0){
				texto+= "\n- Asociar viajes a la factura<br>";
				band = 1;
			}
			if(band ==1)
			{  alert(texto);  }
			else{
				cadena = "id_factura_venta=" + id_factura_venta+
                        "&fecha_fact_asentada=" + fecha_fact_asentada +
                        "&fact_asociada=" + fact_asociada +
                        "&tm_despacho=" + tm_despacho +
                        "&tm_llegada=" + tm_llegada +
                        "&tm_llegada1=" + tm_llegada1 +
                        "&empresa=" + empresa +
                        "&operacion=" + operacion;
				$.ajax({
	                type: "POST",
	                url: "ctr.php",
	                data: cadena,
	                success: function (r){
	                	console.log(r);
	                	d = r.split("||");
	                	console.log(d)
	                	if(d[0] == 1){
	                		alert('Se asentó correctamente la factura: '+d[1]);
	                		//$('#form2').submit();
	                		//$('#operacion').val(30);
							//$('#consultas').submit();
							llenar_pre_fact();
							$('#selected').val('0');
							document.getElementById('guardar').disabled=true;
							document.getElementById('fecha_fact_asentada').disabled=true;
	                	}else if(d[0] == 2){
	                		//alert(r);
	                		alert('La fecha de la factura es menor a la última factura asentada.');
	                	}else{
	                		alert(d);
	                	}
	                }
	            });
			}
		}

	    function myFunction2(evnt)
	    {   var band = 0;
	        var band1 = 0;
	        var operacion = 20;
	        var id_factura_venta = $('#id_factura_venta').val();
	        var ev = (evnt) ? evnt : event;
	        var code=(ev.which) ? ev.which : event.keyCode;
	        if (code == 13 ){
	            var aplica = new Array();
	            for (i=0;i<document.form2.elements.length;i++)
	            {   if(document.form2.elements[i].name == "viajes[]"){
	                    aplica[i] = "'"+(document.form2.elements[i].value)+"'"; 
	                    band = 1;                        
	                }
	            }
	            for (i=0;i<document.form3.elements.length;i++)
	            {   if(document.form3.elements[i].name == "facturacion[]"){
	                    aplica[i] = "'"+(document.form3.elements[i].value)+"'"; 
	                    band = 2;                       
	                }
	            }
	            if(band == 1){
	                var array_viajes =aplica.toString();
	                cadena = "array=" + array_viajes+
	                        "&band=" + band+
	                        "&id_factura_venta=" + id_factura_venta +
	                        "&operacion=" + operacion;
	            }else if(band == 2){
	                var array_viajes =aplica.toString();
	                cadena = "array=" + array_viajes+
	                        "&band=" + band+
	                        "&id_factura_venta=" + id_factura_venta +
	                        "&operacion=" + operacion;
	            }
	            //alert(cadena);
	            $.ajax({
	                type: "POST",
	                url: "ctr.php",
	                data: cadena,
	                success: function (r) {
	                   //console.log(r);
	                    if(r == 1){
	                        //self.location = "agregar.php";
	                        $('#operacion').val(30);
							$('#consultas').submit();
	                    }else{
	                        alert('No se pudo actualizar');
	                    }                    
	                }
	            });            
	        }
	    }
	    $(function () {
	        var valores = '';
	        $('#table1').multiSelect({
	            actcls: 'info', 
	            selector: 'tr', 
	            except: ['tbody'], 
	            statics: ['.danger', '[data-no="1"]'], 
	            callback: function (items){
	                        //document.form1.elements[i].checked=1;
	                //data+=items;
	               // var tr=$(items).parents("tr").appendTo("#table2 tbody");
	                $('#table2').empty().append(items.clone().removeClass('info').addClass('warning'));
	            }
	        });
	    })
	    $(function () {
	        var valores = '';
	        $('#table3').multiSelect({
	            actcls: 'info', 
	            selector: 'tr', 
	            except: ['tbody'], 
	            statics: ['.danger', '[data-no="1"]'], 
	            callback: function (items){
	                //data+=items;
	               // var tr=$(items).parents("tr").appendTo("#table2 tbody");
	                $('#table4').empty().append(items.clone().removeClass('info').addClass('success'));
	            }
	        });
	    })    

	</script>
</div>
</body>
</html>