<?php
//session_start();
include_once("../modelo/conexion.php");
$Fecha1 = date('Y-m-d');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
	   		document.getElementById('title_factura').innerHTML = 'Pre-Liquidacion: 0';
	   		llenar_pre_fact();
		});

		function select_cliente(){
			//document.getElementById('fact_pendientes').disabled="";
			var cliente=$('#cliente').val();
			llenar_pre_fact();
			llenar_producto();
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
			{  alertaDIV.newInnerDivAlert(texto);  }
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
			if(fecha_ingreso == '')
			{   texto+= "\n- Seleccione una fecha de ingreso<br>";
				centinela=0;
			}
			if(fecha_salida == '')
			{   texto+= "\n- Seleccione una fecha de salida<br>";
				centinela=0;
			}
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
<body>
	<?php include('Header.php'); ?>
	<?php
		   	$seleccion_emp = "";
		   	$seleccion_trans = "";
		   	if($_SERVER['REQUEST_METHOD']=='POST')
		 	{	//$seleccion_emp = $_POST['empresa'];
		 		//$seleccion_trans = $_POST['transportador'];
			}
	?>
<div class="container-fluid"> 
    <form name="consultas" id="consultas" action="ctr.php" accept-charset="utf-8" method="POST">
    <div class="row" style="">
    	<div class="col-sm-6" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px;">
    		<div class="row">
    			<div class="col-sm-7">
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
		    			<div class="col-sm-4">
		    				<button id="guardar" class="btn btn-warning navbar-left" style="margin-left: 2px;" onclick="asentar_liq()" disabled>Guardar <span class="glyphicon glyphicon-save"></span></button>
		    			</div>
		    			<div class="col-sm-1"></div>
		    			<div class="col-sm-3">
		    				<h6>N° Factura:</h6>
		    			</div>
		    			<div class="col-sm-4">
		    				<input type="text" name="numero_factura" id="numero_factura" class="input-sm form-control" style="border-color: #3CB6EB;">
		    			</div>
		    		</div>
		    		<div class="row">
		    			<div class="col-sm-5"></div>
		    			<div class="col-sm-2"><h6>Factura: </h6></div>
		    			<div class="col-sm-5">
		    				<input type="" name="selected_fact" id="selected_fact" value="<?php if(isset($_POST['select_fact'])){	if($_POST['select_fact'] != '0'){  echo $_POST['select_fact'];	}else{ echo 0;} } ?>">
		    				<select class="form-control input-sm" name="select_fact" id="select_fact" onchange="validar()">
		    					<option></option>
		    				</select>
		    			</div>
		    		</div>
    			</div>
    			<div class="col-sm-5">
    				<div class="row" style="margin-top: 15px;">
    					<div class="col-sm-6"><h6>Horas:</h6></div>
    					<div class="col-sm-4"><h5 id="title_tm_despacho" ></h5></div>
    					<div class="col-sm-1"></div>
    				</div>
    				<div class="row">
    					<div class="col-sm-6"><h6>Costo $$:</h6></div>
    					<div class="col-sm-4"><h5 id="title_tm_llegada"></h5></div>
    					<div class="col-sm-1"></div>
    				</div>
    				<div class="row">
    					<div class="col-sm-6"><h6>Cant. Tiques:</h6></div>
    					<div class="col-sm-4"><h5 id="title_viajes"></h5></div>
    					<div class="col-sm-1"></div>
    				</div>
    				<div class="row">
		    			<div class="col-sm-2"></div>
		    			<div class="col-sm-10">
		    				<button id="Nuevo_reg" class="btn btn-primary navbar-right" style="margin-right: 2px;" disabled onclick="Nuevo()">Nueva 
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
    		
    		 	<input type="hidden" name="operacion" id="operacion">
    		 	<div class="row" style="margin-top: 5px; margin-bottom: 5px">
	    			<div class="col-sm-2">
	    				<h6>Cliente: </h6>
	    			</div>
	    			<div class="col-sm-3">  				       
				        <select name="empresa" style="border-color: #3CB6EB;" id="empresa" class="combo form-control" onchange="llenar_pre_fact(); validar();">
				        	<option value="0">Seleccione</option>
				        	<?php
				        	$sql = "SELECT NombreCorto, idProveedor FROM proveedores WHERE Empresa='1' ORDER BY NombreCorto";
							$empresa = sqlsrv_query($conn,$sql);		
				        	while($rows = sqlsrv_fetch_array($empresa)) 
				        	{	$id_empresa=$rows['idProveedor'];
				        		$nom_empresa=$rows['NombreCorto'];
				        	?><option value="<?php echo $id_empresa; ?>" <?php if(isset($_POST['empresa'])){if($_POST['empresa'] == $id_empresa){  echo 'selected';	}} ?>><?php echo $nom_empresa ?></option>
				        <?php }
				        	?>
				        </select>
	    			</div>
	    		</div>
	    		<div class="row" style="margin-bottom: 5px;">
	    			<div class="col-sm-2">
			    		 <h6>Proveedor: </h6>
			    	</div>
					<div class="col-sm-5" id="selector-cliente">
						<select id="cliente" style="border-color: #3CB6EB;" name="cliente" class=" form-control" onchange="select_cliente(); validar();"> 
				        	<?php 
				        	$consulta = "SELECT * FROM Proveedores WHERE idProveedor in (SELECT idProveedor FROM  vProveedoresInAgrupacion WHERE Alias='PC') ORDER BY RazonSocial";
				        	$res = sqlsrv_query($conn,$consulta);
				        	while($provee = sqlsrv_fetch_array($res)){
				        		?><option value="<?php echo $provee['idProveedor']; ?>" <?php if(isset($_POST['cliente'])){ if($_POST['cliente'] == $provee['idProveedor']){ echo $_POST['cliente']; }else{ echo 0; } }else{ echo 0; } ?>><?php echo $provee['RazonSocial']; ?></option><?php
				        	}
				        	?>
				        </select>
					</div>
	    		</div>
	    		<div class="row">
	    			<div class="col-sm-2">
	    				<h6>Fecha Ingreso: </h6>
	    			</div>
	    			<div class="col-sm-4">
	    				<input type="date" style="border-color: #3CB6EB;" class="input-sm form-control" name="fecha_ingreso" id="fecha_ingreso" required="" onchange="llenar_producto(); validar();" value="<?php if(isset($_POST['fecha_ingreso'])){  echo $_POST['fecha_ingreso'];	} ?>">
	    			</div>
	    		</div>
	    		<div class="row">
	    			<div class="col-sm-2" >
	    				<h6>Fecha salida: </h6>
	    			</div>
	    			<div class="col-sm-4">
	    				<input type="date" style="border-color: #3CB6EB;" size="100%" class="input-sm form-control" name="fecha_salida" id="fecha_salida" required="" onchange="llenar_producto(); validar();" value="<?php if(isset($_POST['fecha_salida'])){  echo $_POST['fecha_salida'];	} ?>">
	    			</div>
	    		</div>
	    		<div class="row" style="margin-bottom: 5px;">
	    			<div class="col-sm-3">
	    				<h6>Pre-Liquidacion: </h6>
	    			</div>
	    			<div class="col-sm-4" style="margin-left: -55px;">
	    				<input class="input-sm form-control" type="hidden" name="selected" id="selected" value="<?php if(isset($_POST['fact_pendientes'])){	if($_POST['fact_pendientes'] != '0'){  echo $_POST['fact_pendientes'];	}else{ echo 0;} } ?>">
	    				<select id="fact_pendientes" style="border-color: #3CB6EB;" name="fact_pendientes" class="combo form-control" onchange="validar()" disabled>
				        	<option value="0">- - </option>
				        </select>
	    			</div>
	    		</div>
	    	
    	</div>
	</div></form>
	<div class="row" id="ver_tablas">
		<div class="col-sm-6">
    		<center><h4>FACTURA</h4></center>
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
	         		// VARIABLES
	         		$numero_fact = 0;
	         		$tm_despacho = 0;
	            	$tm_llegada = 0;
	            	$cont_viajes = 0;
	            	$fecha_pre_liq=0;
	            	//
	         		if($consulta_fact != 0){
		         		while($fact = sqlsrv_fetch_array($consulta_fact))
	            		{	?> <input type="hidden" id="id_factura_venta" value="<?php echo $fact['id_factura_venta']; ?>"> <?php
	            			$numero_fact = $fact['prefijo_factura'].' - '.$fact['numero_factura'];
	            			$fecha_pre_liq = date_format($fact['fecha_elaboracion'], 'd/m/Y');

	            			/*$sql = "SELECT * FROM factura_venta_detalle
									inner join Registro_tique_cargadores 
									On Factura_Venta_Detalle.numerotransaccion = Registro_tique_cargadores.id_registro
						 			WHERE id_factura_venta='".$fact['id_factura_venta']."'";*/

						 	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						 	$sql="SELECT  Registro_tique_cargadores.id_registro, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
								Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.cod_reporte, Usuarios.NombreUsuarioLargo, 
								Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.fecha_fin_jornada
							FROM Registro_tique_cargadores INNER JOIN
					        	Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
					        	Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
					        	Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario INNER JOIN 
					        	factura_venta_detalle ON Registro_tique_cargadores.id_registro=factura_venta_detalle.numerotransaccion
							WHERE id_factura_venta='".$fact['id_factura_venta']."'
							ORDER BY Registro_tique_cargadores.cod_reporte DESC";
						 	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	            			$params = array();
						   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
						   	$resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
						   	$cont_viajes=sqlsrv_num_rows($resultado);
						   	if($cont_viajes > 0){
						   		while($user = sqlsrv_fetch_array($resultado)){
								$tm_despacho+=number_format($user['horas_trabajadas'],1);
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
						   	}
	            			?>
	         	<?php 	}
	         		}?>
	         		<input type="hidden" id="tm_despacho" value="<?php echo $tm_despacho; ?>">
	         		<input type="hidden" id="tm_llegada" value="<?php echo $tm_llegada; ?>">
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
		        	{  	while($user = sqlsrv_fetch_array($viajes))
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
	    if (window.document.addEventListener){
	       window.document.addEventListener("keydown", myFunction2, false);
	    } else {
	       window.document.attachEvent("onkeydown", myFunction2);
	    }

	    function llenar_pre_fact(){
			var empresa = $('#empresa').val();
			var busqueda = $('#busqueda').val();
			var cliente = $('#cliente').val();
			var band = 2;	
			var selected = 0
			var selected_fact = 0;
			<?php if($_POST['fact_pendientes'] != '0'){
				?>
				document.getElementById('selected_fact').value='0';
				var prefijo_fact = $('#prefijo_fact').val();
				var tm_despacho = $('#tm_despacho').val();
				var tm_llegada = $('#tm_llegada').val();
				var cont_viajes = $('#cont_viajes').val();
				var fecha_prefac = $('#fecha_prefac').val();
				document.getElementById('title_factura').innerHTML = 'Pre-Liquidacion: '+prefijo_fact;
				document.getElementById('numero_factura').value = 'Ej: '+prefijo_fact;
				document.getElementById('title_tm_despacho').innerHTML = tm_despacho;
				document.getElementById('title_tm_llegada').innerHTML = tm_llegada;
				document.getElementById('title_viajes').innerHTML = cont_viajes;
				document.getElementById('fecha_pre').value = fecha_prefac;
				document.getElementById('fact_pendientes').disabled="";
				<?php
				if($_POST['fact_pendientes'] != '0'){
					?>
					document.getElementById('guardar').disabled="";
					document.getElementById('fecha_fact_asentada').disabled=false;
					selected = $('#selected').val();
					<?php
				}else{
				?>	document.getElementById('selected').value='0';
					document.getElementById('guardar').disabled=true;
					document.getElementById('fecha_fact_asentada').disabled=true;					
				<?php
				}
			}elseif($_POST['selected_fact'] != '0'){
				?>
				alert('asda');
				document.getElementById('selected').value='0';
				var prefijo_fact = $('#prefijo_fact').val();
				var tm_despacho = $('#tm_despacho').val();
				var tm_llegada = $('#tm_llegada').val();
				var cont_viajes = $('#cont_viajes').val();
				var fecha_prefac = $('#fecha_prefac').val();
				document.getElementById('title_factura').innerHTML = 'Factura: '+prefijo_fact;
				document.getElementById('numero_factura').value = prefijo_fact;
				document.getElementById('title_tm_despacho').innerHTML = tm_despacho;
				document.getElementById('title_tm_llegada').innerHTML = tm_llegada;
				document.getElementById('title_viajes').innerHTML = cont_viajes;
				document.getElementById('fecha_pre').value = fecha_prefac;
				document.getElementById('fact_pendientes').disabled=false;
				<?php
				if($_POST['select_fact'] != '0'){
					?>
					document.getElementById('guardar').disabled="";
					document.getElementById('fecha_fact_asentada').disabled=true;
					selected_fact = $('#selected_fact').val();
					<?php
				}else{
				?>	document.getElementById('selected_fact').value='0';
					document.getElementById('guardar').disabled=true;
					document.getElementById('fecha_fact_asentada').disabled=true;					
				<?php
				}
			} ?>
				$.post("buscar.php", {band: band, empresa: empresa, cliente: cliente, seleccionado: selected, seleccionado_fact: selected_fact}, 
		    	function(mensaje) {
		    		d = mensaje.split("||");
		            $('#fact_pendientes').html(d[0]).fadeIn();
		            $('#select_fact').html(d[1]).fadeIn();
		        });
		}

		function asentar_liq(){
			var fecha_fact_asentada = $('#fecha_fact_asentada').val();
			var numero_factura = $('#numero_factura').val();
			console.log(numero_factura);
			var id_factura_venta = $('#id_factura_venta').val();
			var tm_despacho = $('#tm_despacho').val();
			var tm_llegada = $('#tm_llegada').val();
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
                        "&tm_despacho=" + tm_despacho +
                        "&tm_llegada=" + tm_llegada +
                        "&empresa=" + empresa +
                        "&numero_factura=" + numero_factura +
                        "&operacion=" + operacion;
				$.ajax({
	                type: "POST",
	                url: "ctr.php",
	                data: cadena,
	                success: function (r){
	                	console.log(r);
	                	d = r.split("||");
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
	                   console.log(r);
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