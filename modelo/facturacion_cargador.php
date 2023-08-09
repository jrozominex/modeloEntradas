<?php
include('conexion.php');
session_start();
$idUsuario = $_SESSION['idUsuario'];
// VARIABLES GLOBALES
$fechaRegistro = date('Y-m-d');
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
if($_POST['band']==0){
	$cliente = $_POST['cliente'];
	$proveedor = $_POST['proveedor'];
	$sql = "SELECT * FROM FacturaCargador WHERE idCliente='$cliente' AND idProveedor='$proveedor' AND estado=0";
	$resul=sqlsrv_query($conn,$sql,$params,$options);
	$row = sqlsrv_num_rows($resul);
	if($row>0){
		$echo='<option value="0" selected>NUEVA</option>';
		while($aa = sqlsrv_fetch_array($resul)){
			$echo.='<option value="'.$aa['idFacturaCargador'].'">'.$aa['prefijoFactura'].'-'.$aa['numeroFactura'].'</option>';
		}
	}else{
		$echo='<option value="-1" selected>No hay disponibles</option>';
	}
	$sql_data = "SELECT * FROM FacturaCargador WHERE idCliente='$cliente' AND idProveedor='$proveedor' AND estado=1";
	$resul_data=sqlsrv_query($conn,$sql_data,$params,$options);
	$row_data = sqlsrv_num_rows($resul_data);
	echo $echo.'||'.$row.'||'.$row_data;
}elseif($_POST['band']==1){
	$cliente = $_POST['cliente'];
	$proveedor = $_POST['proveedor'];
	$fechaIni = $_POST['fechaIni'];
	$fechaFin = $_POST['fechaFin'];
	$echo='';
	$sql = "SELECT idRegistro,codReporte,fechaTiquete,remision,Destino,Equipo,Usuario,TiempoTiquete,dbo.GET_TarifaCargador(idEquipo,fechaTiquete) AS Tarifa,
			(TiempoTiquete*dbo.GET_TarifaCargador(idEquipo,fechaTiquete)) AS Valor
        FROM vTiquetesCargadores 
        WHERE idCliente='$cliente' AND idProveedor='$proveedor' AND fechaTiquete BETWEEN '$fechaIni' AND '$fechaFin' AND estado=3 AND TiempoTiquete<>0
        	AND idRegistro NOT IN (SELECT idRegistro FROM FacturaCargadorDetalle)
        GROUP BY idRegistro,codReporte,fechaTiquete,remision,Destino,Equipo,idEquipo,Usuario,TiempoTiquete
        ORDER BY fechaTiquete DESC,Destino";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
	$row = sqlsrv_num_rows($resul);
	if($row>0){
		while($aa = sqlsrv_fetch_array($resul)){
			$echo.='<tr id="'.$aa['idRegistro'].'" onclick="select_tiquete(\''.$aa['idRegistro'].'\',\'out\')">';
			$echo.='<td>'.$aa['codReporte'].'</td>';
			$echo.='<td>'.date_format($aa['fechaTiquete'],'Y-m-d').'</td>';
			$echo.='<td>'.$aa['remision'].'</td>';
			$echo.='<td>'.utf8_encode($aa['Destino']).'</td>';
			$echo.='<td>'.utf8_encode($aa['Equipo']).'</td>';
			$echo.='<td>'.$aa['TiempoTiquete'].'</td>';
			//$echo.='<td>'.$aa[''].'</td>';
			$echo.='<td>'.number_format($aa['Tarifa'],0).'</td>';
			$echo.='<td>'.number_format($aa['Valor'],0).'</td>';
			$echo.='</tr>';
		}
	}else{
		$echo='<tr><td colspan="8" align="center"><b>No hay tiquetes disponibles</b></td></tr>';
	}
	echo $echo.'||'.$row;
}elseif($_POST['band']==2){
	$cliente = $_POST['cliente'];
	$proveedor = $_POST['proveedor'];
	$tipoFactura = $_POST['tipo_factura'];
	if($tipoFactura==0){
		$prefijoFactura='FC';
	}elseif($tipoFactura==1){
		$prefijoFactura='FP';
	}									

	$sql = "SELECT * FROM FacturaCargador WHERE idCliente='$cliente' AND idProveedor='$proveedor' AND estado=0 AND tipoFactura='$tipoFactura'";
	$resul=sqlsrv_query($conn,$sql,$params,$options);
	$row = sqlsrv_num_rows($resul);
	if($row==0){
		$sql = "SELECT ISNULL(MAX(numeroFactura),0) AS numeroFactura FROM FacturaCargador WHERE tipoFactura='$tipoFactura'";
		$resul=sqlsrv_query($conn,$sql);
		while ($aa = sqlsrv_fetch_array($resul)){
			$numeroFactura = $aa['numeroFactura']+1;
		}
		$insert = "INSERT INTO facturaCargador (idFacturaCargador,prefijoFactura,numeroFactura,facturaAsociada,fechaFactura,fechaElaboracion,idUsuario,
			idCliente,idProveedor,Valor,Tiempo,estado,tipoFactura) 
		VALUES(NEWID(),'$prefijoFactura','$numeroFactura','0','1900-01-01','$fechaRegistro','$idUsuario','$cliente','$proveedor',0,0,0,'$tipoFactura')";
		$res = sqlsrv_query($conn,$insert);
		if($res){
			$sql = "SELECT * FROM FacturaCargador WHERE idCliente='$cliente' AND idProveedor='$proveedor' AND estado=0 AND tipoFactura='$tipoFactura'";
			$resul=sqlsrv_query($conn,$sql);
			while ($aa = sqlsrv_fetch_array($resul)){
				echo $aa['idFacturaCargador'];
			}
		}else{
			echo 2;
		}
	}else{
		echo 0;
	}
}elseif($_POST['band']==3){
	$pre_liquidacion = $_POST['pre_liquidacion'];
	$echo='';
	$Valor = 0;
	if($pre_liquidacion==-1){
		echo 0;
	}else{
		$sql = "SELECT vTiquetesCargadores.idRegistro,codReporte,fechaTiquete,remision,Destino,Equipo,Usuario,TiempoTiquete,FacturaCargadorDetalle.Tarifa,
				(TiempoTiquete*FacturaCargadorDetalle.Tarifa) AS Valor
	        FROM vTiquetesCargadores INNER JOIN FacturaCargadorDetalle ON vTiquetesCargadores.idRegistro=FacturaCargadorDetalle.idRegistro
	        WHERE FacturaCargadorDetalle.idFacturaCargador='$pre_liquidacion'
	        GROUP BY vTiquetesCargadores.idRegistro,codReporte,fechaTiquete,remision,Destino,Equipo,Usuario,TiempoTiquete,FacturaCargadorDetalle.Tarifa
	        ORDER BY fechaTiquete DESC,Destino";
	    $resul=sqlsrv_query($conn,$sql,$params,$options);
		$row = sqlsrv_num_rows($resul);
		if($row>0){
			while($aa = sqlsrv_fetch_array($resul)){
				$echo.='<tr id="'.$aa['idRegistro'].'" onclick="select_tiquete(\''.$aa['idRegistro'].'\',\'in\')">';
				$echo.='<td>'.$aa['codReporte'].'</td>';
				$echo.='<td>'.date_format($aa['fechaTiquete'],'Y-m-d').'</td>';
				$echo.='<td>'.$aa['remision'].'</td>';
				$echo.='<td>'.utf8_encode($aa['Destino']).'</td>';
				$echo.='<td>'.utf8_encode($aa['Equipo']).'</td>';
				$echo.='<td>'.$aa['TiempoTiquete'].'</td>';
				//$echo.='<td>'.$aa[''].'</td>';
				$echo.='<td>'.number_format($aa['Tarifa'],0).'</td>';
				$echo.='<td>'.number_format($aa['Valor'],0).'</td>';
				$Valor = $Valor+$aa['Valor'];
				$echo.='</tr>';
			}
		}else{
			$echo='<tr><td colspan="8" align="center"><b>No hay tiquetes disponibles</b></td></tr>';
		}

		$sql = "SELECT FacturaCargador.prefijoFactura,FacturaCargador.numeroFactura,FacturaCargador.fechaElaboracion,FacturaCargador.tipoFactura,ISNULL(SUM(TiquetesCargadores.TiempoTiquete),0) AS TiempoTiquete,
			COUNT(TiquetesCargadores.idRegistro) AS CantTiquete,ISNULL(SUM(TiquetesCargadores.TiempoTiquete*FacturaCargadorDetalle.Tarifa),0) AS Valor
		FROM FacturaCargador 
			LEFT JOIN FacturaCargadorDetalle ON FacturaCargador.idFacturaCargador=FacturaCargadorDetalle.idFacturaCargador
			LEFT JOIN TiquetesCargadores ON FacturaCargadorDetalle.idRegistro=TiquetesCargadores.idRegistro
		WHERE FacturaCargador.idFacturaCargador='$pre_liquidacion'
		GROUP BY FacturaCargador.prefijoFactura,FacturaCargador.numeroFactura,FacturaCargador.fechaElaboracion,FacturaCargador.tipoFactura";
		$resul=sqlsrv_query($conn,$sql,$params,$options);
		$row = sqlsrv_num_rows($resul);
		if($row>0){
			while($aa=sqlsrv_fetch_array($resul)){
				echo date_format($aa['fechaElaboracion'],'Y-m-d').'||'.
				$aa['tipoFactura'].'||'.
				$aa['TiempoTiquete'].'||'.
				$aa['CantTiquete'].'||'.
				number_format($Valor,0).'||'.
				$aa['prefijoFactura'].'-'.$aa['numeroFactura'].'||'.
				$echo;
			}
		}else{
			echo 0;
		}
	}
}elseif($_POST['band']==4){
	$pre_liquidacion = $_POST['pre_liquidacion'];
	$array_id = explode(",",$_POST['array_id']);
	$ioption = $_POST['ioption'];
	$count_exit = 0;
	if($ioption=='out'){
		for ($i=0; $i < count($array_id); $i++){
			$sql = "SELECT dbo.GET_TarifaCargador(idEquipo,fechaTiquete) AS Tarifa FROM TiquetesCargadores WHERE idRegistro='$array_id[$i]'";
			$res = sqlsrv_query($conn,$sql);
			while($aa = sqlsrv_fetch_array($res)){
				$Tarifa = number_format($aa['Tarifa'],0);
			}
			$insert = "INSERT INTO FacturaCargadorDetalle (idFacturaCargador,idRegistro,Tarifa) VALUES ('$pre_liquidacion','$array_id[$i]','$Tarifa')";
			$res = sqlsrv_query($conn,$insert);
			if($res){
				$count_exit++;
			}
		}
	}elseif($ioption=='in'){
		$txt_array_id = implode("','", $array_id);
		$delete = "DELETE FROM FacturaCargadorDetalle WHERE idFacturaCargador='$pre_liquidacion' AND idRegistro IN ('$txt_array_id')";
		$res = sqlsrv_query($conn,$delete);
		if($res){
			$count_exit=count($array_id);
		}
	}
	if($count_exit==count($array_id)){
		echo 1;
	}else{
		echo 2;
	}
}elseif($_POST['band']==5){
	$fecha_fact_asentada = $_POST['fecha_fact_asentada'];
	$fact_asociada = $_POST['fact_asociada'];
	$pre_liquidacion = $_POST['pre_liquidacion'];

	$sql = "SELECT COUNT(FacturaCargadorDetalle.idFacturaCargador) AS Tiquetes,SUM(TiempoTiquete) AS Tiempo,SUM(TiempoTiquete*Tarifa) AS Valor FROM FacturaCargadorDetalle 
		INNER JOIN TiquetesCargadores ON FacturaCargadorDetalle.idRegistro=TiquetesCargadores.idRegistro
	WHERE idFacturaCargador='$pre_liquidacion'";
	$resul=sqlsrv_query($conn,$sql,$params,$options);
	$row = sqlsrv_num_rows($resul);
	if($row>0){
		while($aa=sqlsrv_fetch_array($resul)){
			$Tiquetes = $aa['Tiquetes'];
			$Tiempo = $aa['Tiempo'];
			$Valor = $aa['Valor'];
		}
		$update = "UPDATE FacturaCargador SET facturaAsociada='$fact_asociada',fechaFactura='$fecha_fact_asentada',Valor=$Valor,Tiempo=$Tiempo,estado=1 WHERE idFacturaCargador='$pre_liquidacion'";
		$res = sqlsrv_query($conn,$update);
		if($res){
			echo 1;
		}else{
			echo 2;
		}
	}else{
		echo 0;
	}
}elseif($_POST['band']==6){
	$select_liquidador = $_POST['select_liquidador'];
	$echo = '';
	if($select_liquidador==-1){
		$echo.='<div class="row" style="margin-left: 10px; margin-right: 10px; margin-top: 5px;">
    	<div class="col-sm-6" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px;">
    		<div class="row">
    			<div class="col-sm-12">
					<center>
						<h4 id="title_pre_liquidacion" style="font-weight: bold;">Pre-liquidacion NUEVA</h4>
					</center>
				</div>
				<div class="col-sm-6" style="margin-top: 15px;">
    				<div class="row" style="margin-bottom: 7px;">
    					<div class="col-sm-5">
    						<label>Fecha pre-liquidación: </label>
    					</div>
    					<div class="col-sm-7">
    						<input type="date" style="border-color: #3CB6EB;" id="fecha_pre_liquidacion" class="input-sm form-control" value="'.date('Y-m-d').'" disabled="">
    					</div>
    				</div>
    				<div class="row" style="margin-bottom: 7px;">
    					<div class="col-sm-5">
    						<label>Tipo factura: </label>
    					</div>
    					<div class="col-sm-7">
    						<select class="form-control" id="tipo_factura" disabled="">
    							<option value="-1" selected disabled>Seleccione</option>
    							<option value="0">Factura de cobro</option>
    							<option value="1">Factura de pago</option>
    						</select>
    					</div>
    				</div>
    				<div class="row" style="margin-bottom: 7px;">
    					<div class="col-sm-5">
    						<label>Fecha factura: </label>
    					</div>
    					<div class="col-sm-7">
    						<input type="date" style="border-color: #3CB6EB;" id="fecha_fact_asentada" class="input-sm form-control" max="'.date('Y-m-d').'" disabled >
    					</div>
    				</div>
    				<div class="row" style="margin-bottom: 7px;">
    					<div class="col-sm-5">
    						<label>Factura Asociada: </label>
    					</div>
    					<div class="col-sm-7">
    						<input type="text" id="fact_asociada" class="form-control" disabled="" placeholder="Ej: 0277..">
    					</div>
    				</div>
    			</div>
    			<div class="col-sm-6">
    				<div class="row" style="margin-top: 15px;">
    					<div class="col-sm-1">
    						<label><span class="glyphicon glyphicon-time"></span></label>
    					</div>
    					<div class="col-sm-4">
    						<input type="" class="form-control" id="title_horas" disabled="" value="0.0">
    					</div>
    					<div class="col-sm-1">
    						<label><span class="glyphicon glyphicon-usd"></span></label>
    					</div>
    					<div class="col-sm-5">
    						<input type="" class="form-control" id="title_valor" disabled="" value="$ 0,00">
    					</div>
    				</div>
    				<div class="row"><br>
    					<div class="col-sm-1"><label><span class="glyphicon glyphicon-folder-open"></span></label></div>
    					<div class="col-sm-4"><input type="" class="form-control" id="title_tiquete" disabled="" value="0"></div>
    				</div>
    				<div class="row">
		    			<div class="col-sm-5"></div>
		    			<div class="col-sm-3" style="margin-top: 10px;">
		    				<button id="Nuevo_reg" style="" class="btn btn-primary navbar-right" disabled onclick="Nueva_liquidacion()">Nueva 
		    					<span class="glyphicon glyphicon-plus"></span>
		    				</button>
		    			</div>
		    			<div class="col-sm-3" style="margin-top: 10px;">
		    				<button id="guardar" class="btn btn-warning navbar-right" onclick="asentar_liquidacion()" disabled>
		    					Asentar <span class="glyphicon glyphicon-save"></span>
		    				</button>
		    			</div>
	    			</div>
    			</div>
    		</div>
    	</div>
    	<div class="col-sm-6" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px;">
    		 	<div class="row" style="margin-top: 13px; margin-bottom: 5px">
	    			<div class="col-sm-2">
	    				<label>Cliente: </label>
	    			</div>
	    			<div class="col-sm-6">	    				       
				        <select style="border-color: #3CB6EB;" id="cliente" class="combo form-control" onchange="load_pre_liquidaciones(0)">
				        	<option value="0" selected="" disabled="">Seleccione</option>';
				        	$sql = "SELECT Proveedores.* FROM ProveedoresGrupos INNER JOIN Proveedores ON ProveedoresGrupos.idProveedor=Proveedores.idProveedor
                				WHERE idAgrupacion='0E2B352C-AD00-489D-8581-FF3B14A2C8AF' ORDER BY RazonSocial";
							$res = sqlsrv_query($conn,$sql);		
				        	while($rows = sqlsrv_fetch_array($res)){
				        		$id_empresa=$rows['idProveedor'];
				        		$nom_empresa=$rows['NombreCorto'];
				        	$echo.='<option value="'.$id_empresa.'">'.utf8_encode($nom_empresa).'</option>';
				        	}
				        $echo.='</select>
	    			</div>
	    		</div>
	    		<div class="row" style="margin-bottom: 7px;">
	    			<div class="col-sm-2">
			    		 <label>Proveedor: </label>
			    	</div>
					<div class="col-sm-6" id="selector-cliente">
						<select id="proveedor" style="border-color: #3CB6EB;" class="form-control" onchange="load_pre_liquidaciones(0)"> 
				        	<option value="0" selected="" disabled="">Seleccione</option>';
				        	$consulta = "SELECT Proveedores.RazonSocial, Proveedores.idProveedor FROM Equipos 
		                    INNER JOIN Proveedores ON Equipos.idPropietario=Proveedores.idProveedor
		                         AND Equipos.clase_equipo='7A975CD6-2672-430D-B29E-7149A03D9410'
		                    GROUP BY Proveedores.RazonSocial, Proveedores.idProveedor ORDER BY RazonSocial";
				        	$res = sqlsrv_query($conn,$consulta);
				        	while($provee = sqlsrv_fetch_array($res)){
				        		$echo.='<option value="'.$provee['idProveedor'].'">'.utf8_encode($provee['RazonSocial']).'</option>';
				        	}
				        $echo.='</select>
					</div>
					<div class="col-sm-3">
						<button class="btn btn-success" onclick="search_historial_liquidaciones()" id="btn_load_liq" data-toggle="modal" data-target="#modalHistorialLiquidaciones"><span class="glyphicon glyphicon-list-alt"></span></button>
					</div>
	    		</div>
	    		<div class="row" style="margin-bottom: 7px;">
	    			<div class="col-sm-2">
	    				<label>Fecha Inicio:</label>
	    			</div>
	    			<div class="col-sm-3">
	    				<input type="date" style="border-color: #3CB6EB;" class="input-sm form-control" id="fechaIni">
	    			</div>
	    		</div>
	    		<div class="row" style="margin-bottom: 7px;">
	    			<div class="col-sm-2" >
	    				<label>Fecha Fin:</label>
	    			</div>
	    			<div class="col-sm-4">
	    				<div class="row">
	    					<div class="col-sm-9">
	    						<input type="date" style="border-color: #3CB6EB;" class="input-sm form-control" id="fechaFin">
	    					</div>
	    					<div class="col-sm-3">
	    						<button class="btn btn-primary" onclick="search_tiquetes()"><span class="glyphicon glyphicon-search"></span></button>
	    					</div>
	    				</div>
	    			</div>
	    		</div>
	    		<div class="row" style="margin-bottom: 9px;">
	    			<div class="col-sm-2">
	    				<label>Pre-Liquidacion:</label>
	    			</div>
	    			<div class="col-sm-4">
	    				<select id="pre_liquidacion" style="border-color: #3CB6EB;" class="combo form-control" onchange="load_data_pre_liquidacion()" disabled>
				        	<option value="0">Seleccione filtros</option>
				        </select>
	    			</div>
	    		</div>
	    	</form>
    	</div>
	</div>
	<div class="row" id="ver_tablas">
		<div class="col-sm-6">
			<div class="row">
				<div class="col-sm-2">
					<button id="href" class="btn btn-success btn-xs" onclick="descargar_pdf()"><span class="glyphicon glyphicon-eye-open"></span> Liquidacion</button>
				</div>
				<div class="col-sm-8">
					<center><h4 id="title_factura">FACTURA NUEVA</h4></center>
				</div>
				<div class="col-sm-2" style="margin-top: 5px;">
					<button class="btn btn-danger navbar-right" id="in_tiquete" style="margin-right: 2px;" disabled="" onclick="mov_tiquete(\'in\')">
						<span class="glyphicon glyphicon-arrow-right"></span></button>
				</div>
			</div>
    		<div class="table-responsive">
    			<table id="table_factura" class="table table-hover table-condensed table-bordered table-responsive table-striped">
        			<thead>
        				<th onclick="select_all_tiquetes(\'in\')">Tiquete</th>
        				<th>Fecha</th>
        				<th>Remisión</th>
        				<th>Patio</th>
        				<th>Cargador</th>
        				<th>Hrs.</th>
        				<th>Tarifa</th>
        				<th>Total</th>
	            	</thead>
	            	<tbody id="tbody_factura">
	            		<tr><td colspan="8" align="center"><b>No hay tiquetes asociados a una factura</b></td></tr>
	            	</tbody>
    			</table>
    		</div>
		</div>
		<div class="col-sm-6">
			<div class="row">
				<div class="col-sm-2" style="margin-top: 5px;">
					<button class="btn btn-success" id="out_tiquete" style="margin-right: 5px;" disabled="" onclick="mov_tiquete(\'out\')">
						<span class="glyphicon glyphicon-arrow-left"></span></button>
				</div>
				<div class="col-sm-10">
					<center><h4>TIQUETES</h4></center>
				</div>
			</div>
    		<div class="table-responsive">
    			<table id="table_tiquete" class="table table-hover table-condensed table-bordered table-responsive table-striped">
	        		<thead>
        				<th onclick="select_all_tiquetes(\'out\')">Tiquete</th>
        				<th>Fecha</th>
        				<th>Remisión</th>
        				<th>Patio</th>
        				<th>Cargador</th>
        				<th>Hrs.</th>
        				<th>Tarifa</th>
        				<th>Total</th>
		            </thead>
	        		<tbody id="tbody_tiquete"></tbody>
    			</table>
			</div>
		</div>
	</div>';
	}else{
		$echo.='<div class="row" style="margin-left: 10px; margin-right: 10px; margin-top: 5px;">
    	<div class="col-sm-6" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px;">
    		<div class="row">
    			<div class="col-sm-12">
					<center>
						<h4 id="title_pre_liquidacion" style="font-weight: bold;">Pre-liquidacion NUEVA</h4>
					</center>
				</div>
				<div class="col-sm-6" style="margin-top: 15px;">
    				<div class="row" style="margin-bottom: 7px;">
    					<div class="col-sm-5">
    						<label>Fecha pre-liquidación: </label>
    					</div>
    					<div class="col-sm-7">
    						<input type="date" style="border-color: #3CB6EB;" id="fecha_pre_liquidacion" class="input-sm form-control" value="'.date('Y-m-d').'" disabled="">
    					</div>
    				</div>
    				<div class="row" style="margin-bottom: 7px;">
    					<div class="col-sm-5">
    						<label>Fecha factura: </label>
    					</div>
    					<div class="col-sm-7">
    						<input type="date" style="border-color: #3CB6EB;" id="fecha_fact_asentada" class="input-sm form-control" max="'.date('Y-m-d').'" disabled >
    					</div>
    				</div>
    				<div class="row" style="margin-bottom: 7px;">
    					<div class="col-sm-5">
    						<label>Factura Asociada: </label>
    					</div>
    					<div class="col-sm-7">
    						<input type="text" id="fact_asociada" class="form-control" disabled="" placeholder="Ej: 0277..">
    					</div>
    				</div>
    			</div>
    			<div class="col-sm-6">
    				<div class="row" style="margin-top: 15px;">
    					<div class="col-sm-1">
    						<label><span class="glyphicon glyphicon-tent"></span></label>
    					</div>
    					<div class="col-sm-4">
    						<input type="" class="form-control" id="title_horas" disabled="" value="0.0">
    					</div>
    					<div class="col-sm-1">
    						<label><span class="glyphicon glyphicon-usd"></span></label>
    					</div>
    					<div class="col-sm-5">
    						<input type="" class="form-control" id="title_valor" disabled="" value="$ 0,00">
    					</div>
    				</div>
    				<div class="row"><br>
    					<div class="col-sm-1"><label><span class="glyphicon glyphicon-folder-open"></span></label></div>
    					<div class="col-sm-4"><input type="" class="form-control" id="title_tiquete" disabled="" value="0"></div>
    				</div>
    				<div class="row">
		    			<div class="col-sm-5"></div>
		    			<div class="col-sm-3" style="margin-top: 10px;">
		    				<button id="Nuevo_reg" style="" class="btn btn-primary navbar-right" disabled onclick="Nueva_liquidacion_new()">Nueva 
		    					<span class="glyphicon glyphicon-plus"></span>
		    				</button>
		    			</div>
		    			<div class="col-sm-3" style="margin-top: 10px;">
		    				<button id="guardar" class="btn btn-warning navbar-right" onclick="asentar_liquidacion()" disabled>
		    					Asentar <span class="glyphicon glyphicon-save"></span>
		    				</button>
		    			</div>
	    			</div>
    			</div>
    		</div>
    	</div>
    	<div class="col-sm-6" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px;">
    			<div class="row" style="margin-top: 13px; margin-bottom: 5px">
    					<div class="col-sm-2">
    						<label>Proveedor: </label>
    					</div>
    					<div class="col-sm-10">
    						<select class="form-control" id="proveedor" onchange="load_pre_liquidaciones_new(0)">
    							<option value="-1" selected disabled>Seleccione</option>';
    							$sql = "SELECT Proveedores.* FROM ProveedoresGrupos 
				        		INNER JOIN Proveedores ON ProveedoresGrupos.idProveedor=Proveedores.idProveedor
				        		INNER JOIN Liquidaciones() AS LQ ON ProveedoresGrupos.idAgrupacion=LQ.idAgrupacion AND LQ.idLiquidacion='$select_liquidador'
                				/*WHERE LQ.idLiquidacion='$select_liquidador'*/ ORDER BY RazonSocial";
								$resul=sqlsrv_query($conn,$sql,$params,$options);
								$row = sqlsrv_num_rows($resul);
								if($row>0){
									while($aa=sqlsrv_fetch_array($resul)){
										$echo.='<option value="'.$aa['idProveedor'].'">'.utf8_encode($aa['RazonSocial']).'</option>';
									}
								}
    				$echo.='</select>
    					</div>
    				</div>
    		 	<div class="row" style="margin-top: 13px; margin-bottom: 5px">
	    			<div class="col-sm-2">
	    				<label>Reporte variable: </label>
	    			</div>
	    			<div class="col-sm-8">	    				       
				        <select style="border-color: #3CB6EB;" id="reporte_variable" class="combo form-control">
				        	<option value="0" selected="" disabled="">Seleccione</option>';
				        	$sql = "SELECT * FROM dbo.qualityReportTree WHERE id_parent='00000000-0000-0000-0000-000000000066' /*t_level=1*/ ORDER BY name";
							$res = sqlsrv_query($conn,$sql);		
				        	while($rows = sqlsrv_fetch_array($res)){
				        		$id=$rows['id'];
				        		$report=$rows['name'];
				        	$echo.='<option value="'.$id.'">'.utf8_encode($report).'</option>';
				        	}
				        $echo.='</select>
	    			</div>
	    			<div class="col-sm-2">
						<button class="btn btn-success" onclick="search_historial_liquidaciones()" id="btn_load_liq" data-toggle="modal" data-target="#modalHistorialLiquidaciones"><span class="glyphicon glyphicon-list-alt"></span></button>
					</div>
	    		</div>
	    		<div class="row" style="margin-top: 13px; margin-bottom: 5px">
	    			<div class="col-sm-2">
	    				<label>Fecha Inicio:</label>
	    			</div>
	    			<div class="col-sm-3">
	    				<input type="date" style="border-color: #3CB6EB;" class="input-sm form-control" id="fechaIni">
	    			</div>
	    			<div class="col-sm-2" >
	    				<label>Fecha Fin:</label>
	    			</div>
	    			<div class="col-sm-4">
	    				<div class="row">
	    					<div class="col-sm-9">
	    						<input type="date" style="border-color: #3CB6EB;" class="input-sm form-control" id="fechaFin">
	    					</div>
	    					<div class="col-sm-3">
	    						<button class="btn btn-primary" id="btn_search_new" onclick="search_tiquetes_new()"><span class="glyphicon glyphicon-search"></span></button>
	    					</div>
	    				</div>
	    			</div>
	    		</div>
	    		<div class="row" style="margin-top: 11px; margin-bottom: 5px">
	    			<div class="col-sm-2">
	    				<label>Pre-Liquidacion:</label>
	    			</div>
	    			<div class="col-sm-4">
	    				<select id="pre_liquidacion" style="border-color: #3CB6EB;" class="combo form-control" onchange="load_data_pre_liquidacion_new()" disabled>
				        	<option value="0">Seleccione filtros</option>
				        </select>
	    			</div>
	    		</div>
	    	</form>
    	</div>
	</div>
	<div class="row" id="ver_tablas">
		<div class="col-sm-6">
			<div class="row">
				<div class="col-sm-2">
					<button id="href" class="btn btn-success btn-xs" onclick="descargar_pdf()"><span class="glyphicon glyphicon-eye-open"></span> Liquidacion</button>
				</div>
				<div class="col-sm-8">
					<center><h4 id="title_factura">FACTURA NUEVA</h4></center>
				</div>
				<div class="col-sm-2" style="margin-top: 5px;">
					<button class="btn btn-danger navbar-right" id="in_tiquete" style="margin-right: 2px;" disabled="" onclick="mov_tiquete(\'in\')">
						<span class="glyphicon glyphicon-arrow-right"></span></button>
				</div>
			</div>
    		<div class="table-responsive">
    			<table id="table_factura" class="table table-hover table-condensed table-bordered table-responsive table-striped">
        			<thead>
        				<th onclick="select_all_tiquetes(\'in\')">Fecha</th>
        				<th>Tiquete</th>
        				<th>Empresa</th>
        				<th>Patio</th>
        				<th>Transaccion</th>
        				<th>Tm</th>
        				<th>Tarifa</th>
        				<th>Total</th>
		            </thead>
	            	<tbody id="tbody_factura">
	            		<tr><td colspan="8" align="center"><b>No hay tiquetes asociados a una factura</b></td></tr>
	            	</tbody>
    			</table>
    		</div>
		</div>
		<div class="col-sm-6">
			<div class="row">
				<div class="col-sm-2" style="margin-top: 5px;">
					<button class="btn btn-success" id="out_tiquete" style="margin-right: 5px;" disabled="" onclick="mov_tiquete(\'out\')">
						<span class="glyphicon glyphicon-arrow-left"></span></button>
				</div>
				<div class="col-sm-10">
					<center><h4>TIQUETES</h4></center>
				</div>
			</div>
    		<div class="table-responsive" id="div_table_tiquete">
    			<table id="table_tiquete" class="table table-hover table-condensed table-bordered table-responsive table-striped">
	        		<thead>
        				<th onclick="select_all_tiquetes(\'out\')">Fecha</th>
        				<th>Tiquete</th>
        				<th>Empresa</th>
        				<th>Patio</th>
        				<th>Transaccion</th>
        				<th>Tm</th>
        				<th>Tarifa</th>
        				<th>Total</th>
		            </thead>
	        		<tbody id="tbody_tiquete"></tbody>
    			</table>
			</div>
		</div>
	</div>';
	}
	echo $echo;
}elseif($_POST['band']==7){
	$reporte_variable = $_POST['reporte_variable'];
	$proveedor = $_POST['proveedor'];
	$select_liquidador = $_POST['select_liquidador'];
	$fechaIni = $_POST['fechaIni'];
	$fechaFin = $_POST['fechaFin'];
	$echo='';
	$sql = "SELECT * FROM qualityReportTree WHERE id='$reporte_variable'";
	$res = sqlsrv_query($conn,$sql);
	while($aa = sqlsrv_fetch_array($res)){
		$name = utf8_encode($aa['name']); 
	}
	$sql = "SELECT Query.NumeroTransaccion,CAST(FechaRegistro AS date) AS FechaRegistro,Proveedores.NombreCorto AS Empresa,TiqueteEmpresa,
		(CASE WHEN (Movimiento.idDestinoAcopio IS NULL) THEN ISNULL(Destino_3.Descripcion, 'Patio') ELSE ISNULL(Destino_2.Descripcion, 'N/D') END) AS Patio,Transat.Descripcion AS Transaccion,Toneladas,
		ISNULL(dbo.GET_TarifaMaquila('$select_liquidador','$proveedor','$reporte_variable',CAST(FechaRegistro AS date)),0) AS Tarifa
	FROM dbo.QUERYVARS_DETALLE('$name','$fechaIni','$fechaFin') AS Query 
		INNER JOIN Movimiento ON Query.NumeroTransaccion=Movimiento.NumeroTransaccion
		LEFT JOIN Destino AS Destino_2 ON dbo.Movimiento.idDestino = Destino_2.idDestino
		LEFT JOIN Destino AS Destino_3 ON dbo.Movimiento.idDestinoAcopio = Destino_3.idDestino
		INNER JOIN Proveedores ON Movimiento.idEmpresa=Proveedores.idProveedor
		INNER JOIN MovimientoTransaccion ON Movimiento.NumeroTransaccion=MovimientoTransaccion.NumeroTransaccion
		INNER JOIN TransaccionesMovimiento() AS Transat ON MovimientoTransaccion.iTransaccion=Transat.iTransaccion
		/*LEFT JOIN MovimientoLiquidacion ON Query.NumeroTransaccion<>MovimientoLiquidacion.numerotransaccion*/
		WHERE Query.NumeroTransaccion NOT IN (SELECT NumeroTransaccion FROM MovimientoLiquidacion)
	ORDER BY CAST(FechaRegistro AS date) DESC";
    $resul=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	$row = sqlsrv_num_rows($resul);
	if($row>0){
		while($aa = sqlsrv_fetch_array($resul)){
			$echo.='<tr id="'.$aa['NumeroTransaccion'].'" onclick="select_tiquete(\''.$aa['NumeroTransaccion'].'\',\'out\')">';
			$echo.='<td>'.date_format($aa['FechaRegistro'],'Y-m-d').'</td>';
			$echo.='<td>'.$aa['TiqueteEmpresa'].'</td>';
			$echo.='<td>'.$aa['Empresa'].'</td>';
			$echo.='<td>'.utf8_encode($aa['Patio']).'</td>';
			$echo.='<td>'.utf8_encode($aa['Transaccion']).'</td>';
			$echo.='<td>'.number_format($aa['Toneladas'],2).'</td>';
			$echo.='<td>$ '.number_format($aa['Tarifa'],0).'</td>';
			$Valor = number_format($aa['Toneladas']*$aa['Tarifa'],0);
			$echo.='<td>$ '.$Valor.'</td>';
			//$echo.='<td>0.00</td>';
			//$echo.='<td>0.00</td>';
			$echo.='</tr>';
		}
	}else{
		$echo='<tr><td colspan="8" align="center"><b>No hay tiquetes disponibles</b></td></tr>';
	}
	echo $echo.'||'.$row;
}elseif($_POST['band']==8){
	$liquidador = $_POST['liquidador'];
	$proveedor = $_POST['proveedor'];
	$sql = "SELECT * FROM DocumentoLiquidacion WHERE idLiquidacion='$liquidador' AND idProveedor='$proveedor' AND FechaLiquidacion='1900-01-01'";
	$resul=sqlsrv_query($conn,$sql,$params,$options);
	$row = sqlsrv_num_rows($resul);
	if($row>0){
		$echo='<option value="0" selected>NUEVA</option>';
		while($aa = sqlsrv_fetch_array($resul)){
			$echo.='<option value="'.$aa['NumeroLiquidacion'].'">PRE-LIQ #'.$aa['Consecutivo'].'</option>';
		}
	}else{
		$echo='<option value="-1" selected>No hay disponibles</option>';
	}
	$sql_data = "SELECT * FROM DocumentoLiquidacion WHERE idLiquidacion='$liquidador' AND idProveedor='$proveedor' AND FechaLiquidacion!='1900-01-01'";
	$resul_data=sqlsrv_query($conn,$sql_data,$params,$options);
	$row_data = sqlsrv_num_rows($resul_data);
	echo $echo.'||'.$row.'||'.$row_data;
}elseif($_POST['band']==9){
	$proveedor = $_POST['proveedor'];
	$select_liquidador = $_POST['select_liquidador'];
	$reporte_variable = $_POST['reporte_variable'];

	$sql = "SELECT * FROM DocumentoLiquidacion WHERE idLiquidacion='$select_liquidador' AND idProveedor='$proveedor' AND id='$reporte_variable' AND FechaLiquidacion='1900-01-01'";
	$resul=sqlsrv_query($conn,$sql,$params,$options);
	$row = sqlsrv_num_rows($resul);
	if($row==0){
		$insert = "INSERT INTO DocumentoLiquidacion (NumeroLiquidacion,idLiquidacion,idProveedor,id,FechaRegistro,FechaLiquidacion,idUsuario) 
			VALUES (NEWID(),'$select_liquidador','$proveedor','$reporte_variable',CAST(GETDATE() AS date),'1900-01-01','$idUsuario')";
		$res = sqlsrv_query($conn,$insert);
		if($res){
			$sql = "SELECT * FROM DocumentoLiquidacion WHERE idLiquidacion='$select_liquidador' AND idProveedor='$proveedor' AND id='$reporte_variable' AND FechaLiquidacion='1900-01-01'";
			$resul=sqlsrv_query($conn,$sql);
			while ($aa = sqlsrv_fetch_array($resul)){
				echo $aa['NumeroLiquidacion'];
			}
		}else{
			echo 2;
		}
	}else{
		echo 0;
	}
}elseif($_POST['band']==10){
	$pre_liquidacion = $_POST['pre_liquidacion'];
	$echo='';
	$Valor = 0;
	if($pre_liquidacion==-1){
		echo 0;
	}else{
	    $sql = "SELECT MovimientoLiquidacion.NumeroTransaccion,FechaRegistro,Empresa,TiqueteEmpresa,
			(CASE WHEN (TipoMovimiento='Traslado') THEN DespachadoDesde ELSE RecepcionadoEn END) AS Patio,Transaccion,Toneladas,Tarifa,(Toneladas*Tarifa) AS Valor
			FROM MovimientoLiquidacion INNER JOIN tz_MovimientoTransporte ON MovimientoLiquidacion.NumeroTransaccion=tz_MovimientoTransporte.NumeroTransaccion 
			WHERE NumeroLiquidacion='$pre_liquidacion'
			ORDER BY tz_MovimientoTransporte.FechaRegistro DESC";

	    $resul=sqlsrv_query($conn,$sql,$params,$options);
		$row = sqlsrv_num_rows($resul);
		if($row>0){
			while($aa = sqlsrv_fetch_array($resul)){
				$echo.='<tr id="'.$aa['NumeroTransaccion'].'" onclick="select_tiquete(\''.$aa['NumeroTransaccion'].'\',\'in\')">';
				$echo.='<td>'.date_format($aa['FechaRegistro'],'Y-m-d').'</td>';
				$echo.='<td>'.$aa['TiqueteEmpresa'].'</td>';
				$echo.='<td>'.$aa['Empresa'].'</td>';
				$echo.='<td>'.utf8_encode($aa['Patio']).'</td>';
				$echo.='<td>'.utf8_encode($aa['Transaccion']).'</td>';
				$echo.='<td>'.number_format($aa['Toneladas'],2).'</td>';
				$echo.='<td>'.number_format($aa['Tarifa'],0).'</td>';
				$echo.='<td>'.number_format($aa['Valor'],0).'</td>';
				//$echo.='<td>0.00</td>';
				//$echo.='<td>0.00</td>';
				$Valor = $Valor+$aa['Valor'];
				$echo.='</tr>';
			}
		}else{
			$echo='<tr><td colspan="8" align="center"><b>No hay tiquetes disponibles</b></td></tr>';
		}
		$sql = "SELECT FacturaCargador.prefijoFactura,FacturaCargador.numeroFactura,FacturaCargador.fechaElaboracion,FacturaCargador.tipoFactura,ISNULL(SUM(TiquetesCargadores.TiempoTiquete),0) AS TiempoTiquete,
			COUNT(TiquetesCargadores.idRegistro) AS CantTiquete,ISNULL(SUM(TiquetesCargadores.TiempoTiquete*1000),0) AS Valor
		FROM FacturaCargador 
			LEFT JOIN FacturaCargadorDetalle ON FacturaCargador.idFacturaCargador=FacturaCargadorDetalle.idFacturaCargador
			LEFT JOIN TiquetesCargadores ON FacturaCargadorDetalle.idRegistro=TiquetesCargadores.idRegistro
		WHERE FacturaCargador.idFacturaCargador='$pre_liquidacion'
		GROUP BY FacturaCargador.prefijoFactura,FacturaCargador.numeroFactura,FacturaCargador.fechaElaboracion,FacturaCargador.tipoFactura";

		$sql = "SELECT DocumentoLiquidacion.Consecutivo,DocumentoLiquidacion.FechaRegistro,DocumentoLiquidacion.FechaLiquidacion,
			ISNULL(SUM(Movimiento.Toneladas),0) AS Tm_total,COUNT(Movimiento.NumeroTransaccion) AS CantTiquete,ISNULL(SUM(Movimiento.Toneladas*MovimientoLiquidacion.Tarifa),0) AS Valor
		FROM DocumentoLiquidacion 
			LEFT JOIN MovimientoLiquidacion ON DocumentoLiquidacion.NumeroLiquidacion=MovimientoLiquidacion.NumeroLiquidacion
			LEFT JOIN Movimiento ON MovimientoLiquidacion.NumeroTransaccion=Movimiento.NumeroTransaccion
		WHERE DocumentoLiquidacion.NumeroLiquidacion='$pre_liquidacion'
		GROUP BY DocumentoLiquidacion.Consecutivo,DocumentoLiquidacion.FechaRegistro,DocumentoLiquidacion.FechaLiquidacion";
		$resul=sqlsrv_query($conn,$sql,$params,$options);
		$row = sqlsrv_num_rows($resul);
		if($row>0){
			while($aa=sqlsrv_fetch_array($resul)){
				echo date_format($aa['FechaRegistro'],'Y-m-d').'||'.
				number_format($aa['Tm_total'],2,'.','').'||'.
				$aa['CantTiquete'].'||'.
				number_format($Valor,0).'||'.
				$aa['Consecutivo'].'||'.
				$echo;
			}
		}else{
			echo 0;
		}
	}
}elseif($_POST['band']==11){
	$pre_liquidacion = $_POST['pre_liquidacion'];
	$array_id = explode(",",$_POST['array_id']);
	$ioption = $_POST['ioption'];
	$count_exit = 0;
	if($ioption=='out'){
		for ($i=0; $i < count($array_id); $i++){
			$sql = "SELECT dbo.GET_TarifaMaquila(idLiquidacion,idProveedor,id,(SELECT CAST(FechaRegistro AS date) FROM Movimiento WHERE NumeroTransaccion='$array_id[$i]')) AS Tarifa
			FROM DocumentoLiquidacion WHERE NumeroLiquidacion='$pre_liquidacion'";
			$res = sqlsrv_query($conn,$sql);
			while($aa = sqlsrv_fetch_array($res)){
				$Tarifa = number_format($aa['Tarifa'],0);
			}
			//$Tarifa=0;
			$insert = "INSERT INTO MovimientoLiquidacion (NumeroLiquidacion,NumeroTransaccion,Tarifa) VALUES ('$pre_liquidacion','$array_id[$i]','$Tarifa')";
			$res = sqlsrv_query($conn,$insert);
			if($res){
				$count_exit++;
			}
		}
	}elseif($ioption=='in'){
		$txt_array_id = implode("','", $array_id);
		$delete = "DELETE FROM MovimientoLiquidacion WHERE NumeroLiquidacion='$pre_liquidacion' AND NumeroTransaccion IN ('$txt_array_id')";
		$res = sqlsrv_query($conn,$delete);
		if($res){
			$count_exit=count($array_id);
		}
	}
	if($count_exit==count($array_id)){
		echo 1;
	}else{
		echo 2;
	}
}elseif($_POST['band']==12){
	$fecha_fact_asentada = $_POST['fecha_fact_asentada'];
	$fact_asociada = $_POST['fact_asociada'];
	$pre_liquidacion = $_POST['pre_liquidacion'];

	$proveedor = $_POST['proveedor'];
	$select_liquidador = $_POST['select_liquidador'];
	$reporte_variable = $_POST['reporte_variable'];

	$sql = "SELECT * FROM DocumentoLiquidacion WHERE NumeroLiquidacion='$pre_liquidacion' AND idProveedor='$proveedor' AND idLiquidacion='$select_liquidador' AND id='$reporte_variable' AND FechaLiquidacion='1900-01-01'";
	$resul=sqlsrv_query($conn,$sql,$params,$options);
	$row = sqlsrv_num_rows($resul);
	if($row>0){
		$update = "UPDATE DocumentoLiquidacion SET FacturaAsociada='$fact_asociada',FechaLiquidacion='$fecha_fact_asentada'";
		$res = sqlsrv_query($conn,$update);
		if($res){
			echo 1;
		}else{
			echo 2;
		}
	}else{
		echo 0;
	}
}elseif($_POST['band']==13){
	$echo='';
	$select_liquidador=$_POST['select_liquidador'];
	$pre_liquidacion=$_POST['pre_liquidacion'];
	if($select_liquidador==-1){
		$NombreLiquidacion = 'Cargadores';
		$sql="SELECT FacturaCargador.*, Proveedores.RazonSocial FROM FacturaCargador INNER JOIN Proveedores ON FacturaCargador.idProveedor=Proveedores.idProveedor 
		WHERE idFacturaCargador='$pre_liquidacion'";
		$res = sqlsrv_query($conn,$sql);
		while($aa = sqlsrv_fetch_array($res)){
			$NumeroFactura = $aa['prefijoFactura'].'-'.$aa['numeroFactura'];
			$FechaPre_liquidacion = date_format($aa['fechaElaboracion'],'Y-m-d');
			$RazonSocial = utf8_encode($aa['RazonSocial']);
			$facturaAsociada = $aa['facturaAsociada'];
			$FechaFactura = date_format($aa['fechaFactura'],'Y-m-d');
		}
		$echo.='<table width="100%" align="center">
				<tr>';
		$echo.='<td rowspan="4"><img align="left" src="../Imagenes/minex.png" width="120" height="35"></td>
					<td rowspan="4" align="right"><b>LIQUIDACIÓN DE '.strtoupper($NombreLiquidacion).'</b></td>
					<td align="right"> Factura # '.$NumeroFactura.'</td>				
				</tr>
				<tr>
					<td align="right"> Fecha Pre-liquidacion: '.$FechaPre_liquidacion.'</td>
				</tr>
				<tr>
					<td align="right"> Factura Asociada: '.$facturaAsociada.'</td>
				</tr>
				<tr>
					<td align="right"> Fecha Factura: '.$FechaFactura.'</td>
				</tr>
			</table>
			<table width="100%" align="center">	
				<tr style="background-color: #A9E2F3">
					<td align="center" colspan="2"><b>'.$RazonSocial.'</b></td>
				</tr>
			</table>
		</div>
		<div class="container table-responsive" >	
			<table width="50%" align="center" style="border:1px solid #000000; border-radius: 5em;">					
				<tr style="background-color: #8CC1EE">
					<td align="center">Centro Trabajo</td>
					<td align="center">Hrs</td>
					<td align="center">Total</td>
				</tr>';
				//<td align="center">Cant. Tiquetes</td>
				$sum_tiquete = 0;
				$sum_horas = 0;
				$sum_total = 0;
				$sql = "SELECT Destino,descuento,sum(Tiempo) AS Tiempo,SUM(Tiempo*FacturaCargadorDetalle.Tarifa) AS Total FROM vTiquetesCargadores 
INNER JOIN FacturaCargadorDetalle ON vTiquetesCargadores.idRegistro=FacturaCargadorDetalle.idRegistro AND FacturaCargadorDetalle.idFacturaCargador='$pre_liquidacion' 
AND vTiquetesCargadores.idActividad!='00000000-0000-0000-0000-000000000000'
group by Destino,descuento ORDER BY Destino,descuento DESC";
				$resul=sqlsrv_query($conn,$sql,$params,$options);
				$row = sqlsrv_num_rows($resul);
				if($row>0){
					while($aa = sqlsrv_fetch_array($resul)){
						if($aa['descuento']==1){
							$Tiempo=$aa['Tiempo']*-1;
							$Total = 0;
						}else{
							$Tiempo=$aa['Tiempo'];
							$Total = $aa['Total'];
						}
								
						$echo.='<tr>';
						$echo.='<td>'.utf8_encode($aa['Destino']).'</td>';
						//$echo.='<td align="right">'.number_format($aa['Cantidad'],0).'</td>';
						$echo.='<td align="right">'.number_format($Tiempo,2).'</td>';
						$echo.='<td align="right">'.number_format($Total,0).'</td>';
						$echo.='</tr>';
						//$sum_tiquete+=$aa['Cantidad'];
						if($Tiempo>0){
							$sum_horas+=$Tiempo;
						}
						$sum_total+=$Total;
					}
				}
				//<td align="right">'.number_format($sum_tiquete,0).'</td>
				$echo.='<tr style="background-color: #A9E2F3">
					<td align="center">Total Facturado</td>
					<td align="right">'.number_format($sum_horas,2).'</td>
					<td align="right">'.number_format($sum_total,0).'</td>
				</tr>';
			$echo.='</table>
		</div>';
		$echo.='<table width="100%" align="center" style="border-radius: 5px; margin-top: 5px; border-collapse: collapse; border-spacing: 1px; border: 1px solid #000; " >					
				<tr style="background-color: #8CC1EE">
					<td align="center" colspan="8"><b>INFORME DETALADO DEL MOVIMIENTO</b></td>
				</tr>
				<tr style="background-color: #A9E2F3">
					<td width="10%" align="center">Tiquete</td>
					<td align="center">Fecha</td>
					<td align="center">Remisión</td>
					<td align="center">Patio</td>
					<td align="center">Cargador</td>
					<td width="10%" align="center">Hrs.</td>
					<td width="10%" align="center">Tarifa</td>
					<td width="10%" align="center">Total</td>
				</tr>';
				$sql = "SELECT vTiquetesCargadores.idRegistro,codReporte,fechaTiquete,remision,Destino,Equipo,Usuario,TiempoTiquete,FacturaCargadorDetalle.Tarifa,
					(TiempoTiquete*FacturaCargadorDetalle.Tarifa) AS Valor
		        FROM vTiquetesCargadores INNER JOIN FacturaCargadorDetalle ON vTiquetesCargadores.idRegistro=FacturaCargadorDetalle.idRegistro
		        WHERE FacturaCargadorDetalle.idFacturaCargador='$pre_liquidacion' AND horometroInicial=0
		        GROUP BY vTiquetesCargadores.idRegistro,codReporte,fechaTiquete,remision,Destino,Equipo,Usuario,TiempoTiquete,FacturaCargadorDetalle.Tarifa
		        ORDER BY Destino,fechaTiquete DESC";
			    $resul=sqlsrv_query($conn,$sql,$params,$options);
				$row = sqlsrv_num_rows($resul);
				if($row>0){
					while($aa = sqlsrv_fetch_array($resul)){
						$echo.='<tr>';
						$echo.='<td>'.$aa['codReporte'].'</td>';
						$echo.='<td>'.date_format($aa['fechaTiquete'],'Y-m-d').'</td>';
						$echo.='<td>'.$aa['remision'].'</td>';
						$echo.='<td>'.utf8_encode($aa['Destino']).'</td>';
						$echo.='<td>'.utf8_encode($aa['Equipo']).'</td>';
						$echo.='<td align="right">'.$aa['TiempoTiquete'].'</td>';
						//$echo.='<td>'.$aa[''].'</td>';
						$echo.='<td align="right">'.number_format($aa['Tarifa'],0).'</td>';
						$echo.='<td align="right">'.number_format($aa['Valor'],0).'</td>';
						//$Valor = $Valor+$aa['Valor'];
						$echo.='</tr>';
					}
				}else{
					$echo='<tr><td colspan="8" align="center"><b>No hay tiquetes disponibles</b></td></tr>';
				}
		$echo.='</table>';
	}else{
		$sql="SELECT * FROM Liquidaciones() WHERE idLiquidacion='$select_liquidador'";
		$res = sqlsrv_query($conn,$sql);
		while($aa = sqlsrv_fetch_array($res)){
			$NombreLiquidacion = utf8_encode($aa['Descripcion']);
		}
		$sql="SELECT DocumentoLiquidacion.*, Proveedores.RazonSocial FROM DocumentoLiquidacion INNER JOIN Proveedores ON DocumentoLiquidacion.idProveedor=Proveedores.idProveedor 
		WHERE NumeroLiquidacion='$pre_liquidacion'";
		$res = sqlsrv_query($conn,$sql);
		while($aa = sqlsrv_fetch_array($res)){
			$NumeroFactura = $aa['Consecutivo'];
			$FechaPre_liquidacion = date_format($aa['FechaRegistro'],'Y-m-d');
			$RazonSocial = utf8_encode($aa['RazonSocial']);
			$facturaAsociada = $aa['FacturaAsociada'];
			$FechaFactura = date_format($aa['FechaLiquidacion'],'Y-m-d');
		}
		$echo.='<table width="100%" align="center">	 				
				<tr>';
		$echo.='<td rowspan="4"><img align="left" src="../Imagenes/minex.png" width="120" height="35"></td>
					<td rowspan="4" align="right"><b>LIQUIDACIÓN DE '.strtoupper($NombreLiquidacion).'</b></td>
					<td align="right"> Factura # '.$NumeroFactura.'</td>				
				</tr>
				<tr>
					<td align="right"> Fecha Pre-liquidacion: '.$FechaPre_liquidacion.'</td>
				</tr>
				<tr>
					<td align="right"> Factura Asociada: '.$facturaAsociada.'</td>
				</tr>
				<tr>
					<td align="right"> Fecha Factura: '.$FechaFactura.'</td>
				</tr>
			</table>
			<table width="100%" align="center">	
				<tr style="background-color: #A9E2F3">
					<td align="center" colspan="2"><b>'.$RazonSocial.'</b></td>
				</tr>
			</table>
		</div>
		<div class="container table-responsive">	
			<table width="50%" align="center" style="border:1px solid #000000">					
				<tr style="background-color: #8CC1EE">
					<td align="center">Reporte</td>
					<td align="center">Centro Trabajo</td>
					<td align="center">Tm</td>
					<td align="center">Total</td>
				</tr>';
				//<td align="center">Cant. Tiquetes</td>
				$sum_tiquete = 0;
				$sum_horas = 0;
				$sum_total = 0;
				$sql = "SELECT QualityReportTree.name,(CASE WHEN (TipoMovimiento='Traslado') THEN DespachadoDesde ELSE RecepcionadoEn END) AS Patio,SUM(tz_MovimientoTransporte.Toneladas) AS Toneladas,
	SUM(MovimientoLiquidacion.Tarifa*tz_MovimientoTransporte.Toneladas) AS Valor
FROM DocumentoLiquidacion 
	INNER JOIN qualityReportTree ON DocumentoLiquidacion.id=qualityReportTree.id
	INNER JOIN MovimientoLiquidacion ON DocumentoLiquidacion.NumeroLiquidacion=MovimientoLiquidacion.NumeroLiquidacion
	INNER JOIN tz_MovimientoTransporte ON MovimientoLiquidacion.numerotransaccion=tz_MovimientoTransporte.NumeroTransaccion
	WHERE DocumentoLiquidacion.NumeroLiquidacion='$pre_liquidacion'
GROUP BY QualityReportTree.name,(CASE WHEN (TipoMovimiento='Traslado') THEN DespachadoDesde ELSE RecepcionadoEn END)";
				$resul=sqlsrv_query($conn,$sql,$params,$options);
				$row = sqlsrv_num_rows($resul);
				if($row>0){
					while($aa = sqlsrv_fetch_array($resul)){
						$echo.='<tr>';
						$echo.='<td>'.utf8_encode($aa['name']).'</td>';
						$echo.='<td>'.utf8_encode($aa['Patio']).'</td>';
						$echo.='<td align="right">'.number_format($aa['Toneladas'],2).'</td>';
						$echo.='<td align="right">'.number_format($aa['Valor'],0).'</td>';
						$echo.='</tr>';
						$sum_horas+=$aa['Toneladas'];
						$sum_total+=$aa['Valor'];
					}
				}
				//<td align="right">'.number_format($sum_tiquete,0).'</td>
				$echo.='<tr style="background-color: #A9E2F3">
					<td align="center" colspan="2">Total Facturado</td>
					<td align="right">'.number_format($sum_horas,2).'</td>
					<td align="right">'.number_format($sum_total,0).'</td>
				</tr>';
			$echo.='</table>
		</div>';
		
		$echo.='<table width="100%" align="center" style="margin-top: 5px; border-collapse: collapse; border-spacing: 1px; border: 1px solid #000;" >					
				<tr style="background-color: #8CC1EE">
					<td align="center" colspan="8"><b>INFORME DETALADO DEL MOVIMIENTO</b></td>
				</tr>
				<tr style="background-color: #A9E2F3">
					<td width="10%" align="center">Tiquete</td>
					<td align="center">Fecha</td>
					<td align="center">Remisión</td>
					<td align="center">Patio</td>
					<td align="center">Placa</td>
					<td width="10%" align="center">Tm.</td>
					<td width="10%" align="center">Tarifa</td>
					<td width="10%" align="center">Total</td>
				</tr>';
				$sql = "SELECT tz_MovimientoTransporte.TiqueteEmpresa,tz_MovimientoTransporte.FechaRegistro,tz_MovimientoTransporte.Remision,(CASE WHEN (TipoMovimiento='Traslado') THEN DespachadoDesde ELSE RecepcionadoEn END) AS Patio,
	tz_MovimientoTransporte.Placa_a,tz_MovimientoTransporte.Toneladas,MovimientoLiquidacion.Tarifa,(MovimientoLiquidacion.Tarifa*tz_MovimientoTransporte.Toneladas) AS Valor
FROM MovimientoLiquidacion 
	INNER JOIN tz_MovimientoTransporte ON MovimientoLiquidacion.numerotransaccion=tz_MovimientoTransporte.NumeroTransaccion
WHERE MovimientoLiquidacion.NumeroLiquidacion='$pre_liquidacion'
ORDER BY tz_MovimientoTransporte.FechaRegistro DESC,(CASE WHEN (TipoMovimiento='Traslado') THEN DespachadoDesde ELSE RecepcionadoEn END)";
			    $resul=sqlsrv_query($conn,$sql,$params,$options);
				$row = sqlsrv_num_rows($resul);
				if($row>0){
					while($aa = sqlsrv_fetch_array($resul)){
						$echo.='<tr>';
						$echo.='<td>'.$aa['TiqueteEmpresa'].'</td>';
						$echo.='<td>'.date_format($aa['FechaRegistro'],'Y-m-d').'</td>';
						$echo.='<td>'.$aa['Remision'].'</td>';
						$echo.='<td>'.utf8_encode($aa['Patio']).'</td>';
						$echo.='<td>'.utf8_encode($aa['Placa_a']).'</td>';
						$echo.='<td align="right">'.number_format($aa['Toneladas'],2).'</td>';
						$echo.='<td align="right">'.number_format($aa['Tarifa'],0).'</td>';
						$echo.='<td align="right">'.number_format($aa['Valor'],0).'</td>';
						$echo.='</tr>';
					}
				}else{
					$echo='<tr><td colspan="8" align="center"><b>No hay tiquetes disponibles</b></td></tr>';
				}
		$echo.='</table>';
	}
$echo.='</table>
		<br>
		<table align="center">
			<tr>
				<td>==> Fecha Consulta '.date('Y-m-d').' <== </td>
			</tr>
		</table>';
	echo $echo;
}elseif($_POST['band']==14){
	$select_liquidador = $_POST['select_liquidador'];
	$proveedor = $_POST['proveedor'];
	$variable = $_POST['variable'];
	$echo = '';
	if($select_liquidador==-1){
		$echo.='<div class="modal-body">
	        <div class="row form-group center-block">
	        	<div class="col-sm-12">';
		$sql = "SELECT * FROM FacturaCargador WHERE idProveedor='$proveedor' AND idCliente='$variable'";
		$resul=sqlsrv_query($conn,$sql,$params,$options);
		$row = sqlsrv_num_rows($resul);
		if($row>0){
			$echo.='<table class="table table-hover table-condensed table-bordered table-responsive table-striped">
        			<thead>
        				<th>Liquidación</th>
        				<th>Fecha Liquidación</th>
        				<th>Factura Asociada</th>
        				<th>Fecha factura</th>
        				<th>Hrs.</th>
        				<th>Total</th>
        				<th><span class="glyphicon glyphicon-open-file"></span></th>
	            	</thead>
	            	<tbody>';
			while($aa = sqlsrv_fetch_array($resul)){
				$echo.='<tr>';
				$echo.='<td>'.$aa['prefijoFactura'].'-'.$aa['numeroFactura'].'</td>';
				$echo.='<td>'.date_format($aa['fechaElaboracion'],'Y-m-d').'</td>';
				$echo.='<td>'.$aa['facturaAsociada'].'</td>';
				$echo.='<td>'.date_format($aa['fechaFactura'],'Y-m-d').'</td>';
				$echo.='<td>'.number_format($aa['Tiempo'],2).'</td>';
				$echo.='<td>'.number_format($aa['Valor'],0).'</td>';
				$echo.='<td><button class="btn btn-danger" onclick="descargar_pdf_historial(\''.$aa['idFacturaCargador'].'\')"><span class="glyphicon glyphicon-open-file"></span></button></td>';
			}
			$echo.='</tbody>
			</table>';
		}
		$echo.='</div>
			</div>
		</div>
		<div class="modal-footer"></div>';
	}else{
		$echo.='<div class="modal-body">
	        <div class="row form-group center-block">
	        	<div class="col-sm-12">';
		$sql = "SELECT DocumentoLiquidacion.NumeroLiquidacion,DocumentoLiquidacion.Consecutivo,DocumentoLiquidacion.FacturaAsociada,DocumentoLiquidacion.FechaRegistro,DocumentoLiquidacion.FechaLiquidacion,Proveedores.RazonSocial,
	QualityReportTree.name,SUM(Movimiento.Toneladas) AS Toneladas,SUM(MovimientoLiquidacion.Tarifa*Movimiento.Toneladas) AS Valor
FROM DocumentoLiquidacion 
	INNER JOIN Proveedores ON DocumentoLiquidacion.idProveedor=Proveedores.idProveedor 
	INNER JOIN qualityReportTree ON DocumentoLiquidacion.id=qualityReportTree.id
	INNER JOIN MovimientoLiquidacion ON DocumentoLiquidacion.NumeroLiquidacion=MovimientoLiquidacion.NumeroLiquidacion
	INNER JOIN Movimiento ON MovimientoLiquidacion.numerotransaccion=Movimiento.NumeroTransaccion
	WHERE DocumentoLiquidacion.idProveedor='$proveedor' AND DocumentoLiquidacion.idLiquidacion='$select_liquidador' AND DocumentoLiquidacion.FechaLiquidacion!='1900-01-01'
GROUP BY DocumentoLiquidacion.NumeroLiquidacion,DocumentoLiquidacion.Consecutivo,DocumentoLiquidacion.FacturaAsociada,DocumentoLiquidacion.FechaRegistro,DocumentoLiquidacion.FechaLiquidacion,Proveedores.RazonSocial,
	QualityReportTree.name";
		$resul=sqlsrv_query($conn,$sql,$params,$options);
		$row = sqlsrv_num_rows($resul);
		if($row>0){
			$echo.='<table class="table table-hover table-condensed table-bordered table-responsive table-striped">
        			<thead>
        				<th>Liquidación</th>
        				<th>Fecha Liquidación</th>
        				<th>Reporte</th>
        				<th>Factura Asociada</th>
        				<th>Fecha factura</th>
        				<th>Tm.</th>
        				<th>Total</th>
        				<th><span class="glyphicon glyphicon-open-file"></span></th>
	            	</thead>
	            	<tbody>';
			while($aa = sqlsrv_fetch_array($resul)){
				$echo.='<tr>';
				$echo.='<td>'.$aa['Consecutivo'].'</td>';
				$echo.='<td>'.date_format($aa['FechaRegistro'],'Y-m-d').'</td>';
				$echo.='<td>'.utf8_encode($aa['name']).'</td>';
				$echo.='<td>'.$aa['FacturaAsociada'].'</td>';
				$echo.='<td>'.date_format($aa['FechaLiquidacion'],'Y-m-d').'</td>';
				$echo.='<td>'.number_format($aa['Toneladas'],2).'</td>';
				$echo.='<td>'.number_format($aa['Valor'],0).'</td>';
				$echo.='<td><button class="btn btn-danger" onclick="descargar_pdf_historial(\''.$aa['NumeroLiquidacion'].'\')"><span class="glyphicon glyphicon-open-file"></span></button></td>';
			}
			$echo.='</tbody>
			</table>';
		}
		$echo.='</div>
			</div>
		</div>
		<div class="modal-footer"></div>';
	}
	echo $echo;
}
?>