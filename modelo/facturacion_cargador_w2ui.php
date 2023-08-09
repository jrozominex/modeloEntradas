<?php
include('conexion.php');
include ("../../clase_encrip.php");
session_start();
$idUsuario = $_SESSION['idUsuario'];
// VARIABLES GLOBALES
$fechaRegistro = date('Y-m-d');
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
$json='';
if($_GET['band']==-1){
	$json.='<div class="row" style="margin-left: 10px; margin-right: 10px; margin-top: 0px;">
            <div class="col-sm-4"></div>
            <div class="col-sm-4" style="background-color: powderblue; border-radius: 5px;">
                <center><label>MODO DE LIQUIDACIÓN</label></center>
                <select class="form-control" id="select_liquidador" onchange="load_body_liquidaciones()">
                    <option selected="" disabled="">Seleccione</option>
                    <option value="-1"><b>Cargadores</option>';
                    $sql = "SELECT * FROM Liquidaciones()";
                    $res = sqlsrv_query($conn,$sql);
                    while($aa=sqlsrv_fetch_array($res)){
                        $json.='<option value="'.$aa['idLiquidacion'].'">'.utf8_encode($aa['Descripcion']).'</option>';
                    }
                $json.='</select><br>
            </div>
        </div><br>';
    echo $json;
}elseif($_GET['band']==0){
	$post_data = file_get_contents('php://input');    
   	$list_record = json_decode($post_data, true);
   	$cliente = ENCR::descript($list_record['cliente']);
	$proveedor = ENCR::descript($list_record['proveedor']);
	$sql = "SELECT * FROM FacturaCargador WHERE idCliente='$cliente' AND idProveedor='$proveedor' AND estado=0";
	$resul=sqlsrv_query($conn,$sql,$params,$options);
	$row = sqlsrv_num_rows($resul);
	$echo='';
	if($row>0){
		$echo='<option value="0" selected>NUEVA</option>';
		while($aa = sqlsrv_fetch_array($resul)){
			$echo.='<option value="'.ENCR::encript($aa['idFacturaCargador']).'">'.$aa['prefijoFactura'].'-'.$aa['numeroFactura'].'</option>';
		}
	}else{
		$echo='<option value="-1" selected>No hay disponibles</option>';
	}
	$sql_data = "SELECT * FROM FacturaCargador WHERE idCliente='$cliente' AND idProveedor='$proveedor' AND estado=1";
	$resul_data=sqlsrv_query($conn,$sql_data,$params,$options);
	$row_data = sqlsrv_num_rows($resul_data);
	echo $echo.'||'.$row.'||'.$row_data;
}elseif($_GET['band']==1){
	$post_data = file_get_contents('php://input');
   	$list_record = json_decode($post_data, true);
	$cliente = ENCR::descript($list_record['cliente']);
	$proveedor = ENCR::descript($list_record['proveedor']);
	$fechaIni = $list_record['fechaIni'];
	$fechaFin = $list_record['fechaFin'];
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
	$echo.='{"records": [';
	if($row>0){
		while($aa = sqlsrv_fetch_array($resul)){
			$idRegistro = ENCR::encript($aa['idRegistro']);
			$codReporte=$aa['codReporte'];
			$fechaTiquete=date_format($aa['fechaTiquete'],'Y-m-d');
			$remision=$aa['remision'];
			$Destino=utf8_encode($aa['Destino']);
			$Equipo=utf8_encode($aa['Equipo']);
			$TiempoTiquete=number_format($aa['TiempoTiquete']);
			$Tarifa=number_format($aa['Tarifa'],0);
			$Valor=number_format($aa['Valor'],0);
			/*$echo.='<tr id="'.$aa['idRegistro'].'" onclick="select_tiquete(\''.$aa['idRegistro'].'\',\'out\')">';
			$echo.='<td>'.$aa['codReporte'].'</td>';
			$echo.='<td>'.date_format($aa['fechaTiquete'],'Y-m-d').'</td>';
			$echo.='<td>'.$aa['remision'].'</td>';
			$echo.='<td>'.utf8_encode($aa['Destino']).'</td>';
			$echo.='<td>'.utf8_encode($aa['Equipo']).'</td>';
			$echo.='<td>'.$aa['TiempoTiquete'].'</td>';
			//$echo.='<td>'.$aa[''].'</td>';
			$echo.='<td>'.number_format($aa['Tarifa'],0).'</td>';
			$echo.='<td>'.number_format($aa['Valor'],0).'</td>';
			$echo.='</tr>';*/
			$echo.='{"recid":"'.$idRegistro.'","ltiquete":"'.$codReporte.'","lfecha":"'.$fechaTiquete.'","lremision":"'.$remision.'","lpatio":"'.$Destino.'","lcargador":"'.$Equipo.'","lhoras":"'.$TiempoTiquete.'","ltarifa":"'.$Tarifa.'","ltotal":"'.$Valor.'"},';
		}
		$echo = substr($echo, 0, strlen($echo) - 1);
	}else{
		$echo.='{}';
	}
	$echo.=']}';
	echo $echo.'||'.$row;
}elseif($_GET['band']==2){
	$post_data = file_get_contents('php://input');    
   	$list_record = json_decode($post_data, true);
	$cliente = ENCR::descript($list_record['cliente']);
	$proveedor = ENCR::descript($list_record['proveedor']);
	$tipoFactura = ENCR::descript($list_record['tipo_factura']);
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
}elseif($_GET['band']==3){
	$post_data = file_get_contents('php://input');    
   	$list_record = json_decode($post_data, true);
	$pre_liquidacion = ENCR::descript($list_record['pre_liquidacion']);
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
		$echo.='{"records": [';
		if($row>0){
			while($aa = sqlsrv_fetch_array($resul)){
				$idRegistro = ENCR::encript($aa['idRegistro']);
				$codReporte=$aa['codReporte'];
				$fechaTiquete=date_format($aa['fechaTiquete'],'Y-m-d');
				$remision=$aa['remision'];
				$Destino=utf8_encode($aa['Destino']);
				$Equipo=utf8_encode($aa['Equipo']);
				$TiempoTiquete=number_format($aa['TiempoTiquete'],2);
				$Tarifa=number_format($aa['Tarifa'],0);
				$Valor=number_format($aa['Valor'],0);
				$Valor=$Valor+$aa['Valor'];
				$echo.='{"recid":"'.$idRegistro.'","ltiquete":"'.$codReporte.'","lfecha":"'.$fechaTiquete.'","lremision":"'.$remision.'","lpatio":"'.$Destino.'","lcargador":"'.$Equipo.'","lhoras":"'.$TiempoTiquete.'","ltarifa":"'.$Tarifa.'","ltotal":"'.$Valor.'"},';
			}
			$echo = substr($echo, 0, strlen($echo) - 1);
			$echo.=']}';
		}else{
			$echo.=']}';
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
}elseif($_GET['band']==4){
	$post_data = file_get_contents('php://input');
   	$list_record = json_decode($post_data, true);
	$pre_liquidacion = ENCR::descript($list_record['pre_liquidacion']);
	$array_id = ($list_record['array_id']);
	$ioption = $list_record['ioption'];
	$count_exit = 0;
	if($ioption=='in'){
		for ($i=0; $i < count($array_id); $i++){
			$id_for = ENCR::descript($array_id[$i]);
			$sql = "SELECT dbo.GET_TarifaCargador(idEquipo,fechaTiquete) AS Tarifa FROM TiquetesCargadores 
				WHERE idRegistro='$id_for'";
			$res = sqlsrv_query($conn,$sql);
			while($aa = sqlsrv_fetch_array($res)){
				$Tarifa = number_format($aa['Tarifa'],0);
			}
			$insert = "INSERT INTO FacturaCargadorDetalle (idFacturaCargador,idRegistro,Tarifa) VALUES ('$pre_liquidacion','$id_for','$Tarifa')";
			$res = sqlsrv_query($conn,$insert);
			if($res){
				$count_exit++;
			}
		}
	}elseif($ioption=='out'){
		for ($i=0; $i < count($array_id); $i++){
			$id_for = ENCR::descript($array_id[$i]);
			$array_id[$i]=$id_for;
		}
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
}elseif($_GET['band']==5){
	$post_data = file_get_contents('php://input');    
   	$list_record = json_decode($post_data, true);
	$fecha_fact_asentada = $list_record['fecha_fact_asentada'];
	$fact_asociada = $list_record['fact_asociada'];
	$pre_liquidacion = ENCR::descript($list_record['pre_liquidacion']);

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
}elseif($_GET['band']==6){
	$post_data = file_get_contents('php://input');    
   	$list_record = json_decode($post_data, true);
   	$select_liquidador = $list_record['select_liquidador'];
   	$div_left='';
	$div_right = '';
	if($select_liquidador==-1){
		$div_left.='<div class="row" style="margin-left: 10px; margin-right: 10px; margin-top: 5px;">
    	<div class="col-sm-12" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px;">
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
    	</div></div>';
$div_right.='<div class="row" style="margin-left: 10px; margin-right: 10px; margin-top: 5px;">
    		<div class="col-sm-12" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px;">
    		 	<div class="row" style="margin-top: 13px; margin-bottom: 5px">
	    			<div class="col-sm-2">
	    				<label>Cliente: </label>
	    			</div>
	    			<div class="col-sm-6">	    				       
				        <select style="border-color: #3CB6EB;" id="cliente" class="combo form-control" onchange="load_pre_liquidaciones(0)">
				        	<option value="0" selected="" disabled="">Seleccione</option>';
				        	$sql = "SELECT Proveedores.* FROM ProveedoresGrupos INNER JOIN Proveedores ON ProveedoresGrupos.idProveedor=Proveedores.idProveedor 
				        	AND ProveedoresGrupos.idAgrupacion='0E2B352C-AD00-489D-8581-FF3B14A2C8AF' ORDER BY RazonSocial";
							$res = sqlsrv_query($conn,$sql);		
				        	while($rows = sqlsrv_fetch_array($res)){
				        		$id_empresa=ENCR::encript($rows['idProveedor']);
				        		$nom_empresa=$rows['NombreCorto'];
				        	$div_right.='<option value="'.$id_empresa.'">'.utf8_encode($nom_empresa).'</option>';
				        	}
				        $div_right.='</select>
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
				        		$div_right.='<option value="'.ENCR::encript($provee['idProveedor']).'">'.utf8_encode($provee['RazonSocial']).'</option>';
				        	}
				        $div_right.='</select>
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
	    						<button class="btn btn-primary" id="btn_search" onclick="search_tiquetes()"><span class="glyphicon glyphicon-search"></span></button>
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
	</div>';
	}else{
		$div_left.='<div class="row" style="margin-left: 10px; margin-right: 10px; margin-top: 5px;">
    	<div class="col-sm-12" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px;">
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
    	</div></div>';
$div_right.='<div class="row" style="margin-left: 10px; margin-right: 10px; margin-top: 5px;">
		<div class="col-sm-12" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px;">
    			<div class="row" style="margin-top: 13px; margin-bottom: 5px">
    					<div class="col-sm-1">
    						<label>Empresa: </label>
    					</div>
    					<div class="col-sm-5">
    						<select class="form-control" id="empresa" onchange="load_pre_liquidaciones_new(0)">
    							<option value="-1" selected disabled>Seleccione</option>';
    							$sql = "SELECT * FROM Proveedores WHERE Empresa=1 ORDER BY Alias";
								$resul=sqlsrv_query($conn,$sql,$params,$options);
								$row = sqlsrv_num_rows($resul);
								if($row>0){
									while($aa=sqlsrv_fetch_array($resul)){
										$div_right.='<option value="'.ENCR::encript($aa['idProveedor']).'">'.utf8_encode($aa['Alias']).'</option>';
									}
								}
    				$div_right.='</select>
    					</div>
    					<div class="col-sm-1">
    						<label>Proveedor: </label>
    					</div>
    					<div class="col-sm-5">
    						<select class="form-control" id="proveedor" onchange="load_pre_liquidaciones_new(0)">
    							<option value="-1" selected disabled>Seleccione</option>';
    							$sql = "SELECT * FROM Proveedores INNER JOIN ProveedoresGrupos on Proveedores.idProveedor=ProveedoresGrupos.idProveedor
								WHERE idAgrupacion='6B28903D-CCD4-475E-A75B-75E5A6B191DA' ORDER BY RazonSocial";
								$resul=sqlsrv_query($conn,$sql,$params,$options);
								$row = sqlsrv_num_rows($resul);
								if($row>0){
									while($aa=sqlsrv_fetch_array($resul)){
										$div_right.='<option value="'.ENCR::encript($aa['idProveedor']).'">'.utf8_encode($aa['RazonSocial']).'</option>';
									}
								}
    				$div_right.='</select>
    					</div>
    				</div>
    		 	<div class="row" style="margin-top: 13px; margin-bottom: 5px">
	    			<div class="col-sm-2">
	    				<label>Reporte variable: </label>
	    			</div>
	    			<div class="col-sm-8">	    				       
				        <select style="border-color: #3CB6EB;" id="reporte_variable" class="combo form-control" onchange="load_pre_liquidaciones_new(0)">
				        	<option value="0" selected="" disabled="">Seleccione</option>';
				        	$sql = "SELECT * FROM dbo.qualityReportTree WHERE id_parent='00000000-0000-0000-0000-000000000066' /*t_level=1*/ ORDER BY name";
							$res = sqlsrv_query($conn,$sql);		
				        	while($rows = sqlsrv_fetch_array($res)){
				        		$id=ENCR::encript($rows['id']);
				        		$report=$rows['name'];
				        	$div_right.='<option value="'.$id.'">'.utf8_encode($report).'</option>';
				        	}
				        $div_right.='</select>
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
	</div>';
	}
	echo $div_left.'||'.$div_right;
}elseif($_GET['band']==7){
	$post_data = file_get_contents('php://input');    
   	$list_record = json_decode($post_data, true);
	$reporte_variable = ENCR::descript($list_record['reporte_variable']);
	$proveedor = ENCR::descript($list_record['proveedor']);
	$select_liquidador = $list_record['select_liquidador'];
	$fechaIni = $list_record['fechaIni'];
	$fechaFin = $list_record['fechaFin'];
	$echo='';
	$sql = "SELECT * FROM qualityReportTree WHERE id='$reporte_variable'";
	$res = sqlsrv_query($conn,$sql);
	while($aa = sqlsrv_fetch_array($res)){
		$name = utf8_encode($aa['name']); 
	}
	$sql = "SELECT Query.NumeroTransaccion,FechaRegistro,Empresa,TiqueteEmpresa,
		(CASE WHEN (TipoMovimiento='Traslado') THEN DespachadoDesde ELSE RecepcionadoEn END) AS Patio,Transaccion,Toneladas,
		ISNULL(dbo.GET_TarifaMaquila('$select_liquidador','$proveedor','$reporte_variable',FechaRegistro),0) AS Tarifa
	FROM dbo.QUERYVARS_DETALLE('$name','$fechaIni','$fechaFin') AS Query
		INNER JOIN tz_MovimientoTransporte ON Query.NumeroTransaccion=tz_MovimientoTransporte.NumeroTransaccion
		/*LEFT JOIN MovimientoLiquidacion ON Query.NumeroTransaccion<>MovimientoLiquidacion.numerotransaccion*/
		WHERE Query.NumeroTransaccion NOT IN (SELECT NumeroTransaccion FROM MovimientoLiquidacion)
	ORDER BY FechaRegistro DESC";
    $resul=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	$row = sqlsrv_num_rows($resul);
	$echo.='{"records": [';
	if($row>0){
		while($aa = sqlsrv_fetch_array($resul)){
			$NumeroTransaccion=ENCR::encript($aa['NumeroTransaccion']);
			$FechaTiquete=date_format($aa['FechaRegistro'],'Y-m-d');
			$TiqueteEmpresa=$aa['TiqueteEmpresa'];
			$Empresa=utf8_encode($aa['Empresa']);
			$Patio=utf8_encode($aa['Patio']);
			$Transaccion=utf8_encode($aa['Transaccion']);
			$Toneladas=number_format($aa['Toneladas'],2);
			$Tarifa=number_format($aa['Tarifa'],0);
			$Valor=number_format($aa['Toneladas']*$aa['Tarifa'],0);
			//$Valor+=$Valor;
			$echo.='{"recid":"'.$NumeroTransaccion.'","lfecha":"'.$FechaTiquete.'","ltiquete":"'.$TiqueteEmpresa.'","lempresa":"'.$Empresa.'","lpatio":"'.$Patio.'","ltransaccion":"'.$Transaccion.'","ltm":"'.$Toneladas.'","ltarifa":"'.$Tarifa.'","ltotal":"'.$Valor.'"},';
		}
		$echo = substr($echo,0,strlen($echo)-1);
	}else{
		$echo.='{}';
	}
	$echo.=']}';
	echo $echo.'||'.$row;
}elseif($_GET['band']==8){
	$post_data = file_get_contents('php://input');
   	$list_record = json_decode($post_data, true);
	$liquidador = ($list_record['liquidador']);
	$empresa = ENCR::descript($list_record['empresa']);
	$proveedor = ENCR::descript($list_record['proveedor']);
	$reporte_variable = ENCR::descript($list_record['reporte_variable']);
	$sql = "SELECT * FROM DocumentoLiquidacion 
	WHERE idLiquidacion='$liquidador' AND idEmpresa='$empresa' AND idProveedor='$proveedor' AND id='$reporte_variable' AND FechaLiquidacion='1900-01-01'";
	$resul=sqlsrv_query($conn,$sql,$params,$options);
	$row = sqlsrv_num_rows($resul);
	if($row>0){
		$echo='<option value="0" selected>NUEVA</option>';
		while($aa = sqlsrv_fetch_array($resul)){
			$echo.='<option value="'.ENCR::encript($aa['NumeroLiquidacion']).'">PRE-LIQ #'.$aa['Consecutivo'].'</option>';
		}
	}else{
		$echo='<option value="-1" selected>No hay disponibles</option>';
	}
	$sql_data = "SELECT * FROM DocumentoLiquidacion 
	WHERE idLiquidacion='$liquidador' AND idEmpresa='$empresa' AND idProveedor='$proveedor' AND id='$reporte_variable' AND FechaLiquidacion!='1900-01-01'";
	$resul_data=sqlsrv_query($conn,$sql_data,$params,$options);
	$row_data = sqlsrv_num_rows($resul_data);
	echo $echo.'||'.$row.'||'.$row_data;
}elseif($_GET['band']==9){
	$post_data = file_get_contents('php://input');
   	$list_record = json_decode($post_data, true);
	$proveedor = ENCR::descript($list_record['proveedor']);
	$select_liquidador = $list_record['select_liquidador'];
	$reporte_variable = ENCR::descript($list_record['reporte_variable']);

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
}elseif($_GET['band']==10){
	$post_data = file_get_contents('php://input');
   	$list_record = json_decode($post_data, true);
	$pre_liquidacion = ENCR::descript($list_record['pre_liquidacion']);
	$echo='';
	$ValorTotal = 0;
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
		$echo.='{"records": [';
		if($row>0){
			while($aa = sqlsrv_fetch_array($resul)){
				$NumeroTransaccion=ENCR::encript($aa['NumeroTransaccion']);
				$FechaTiquete=date_format($aa['FechaRegistro'],'Y-m-d');
				$TiqueteEmpresa=$aa['TiqueteEmpresa'];
				$Empresa=utf8_encode($aa['Empresa']);
				$Patio=utf8_encode($aa['Patio']);
				$Transaccion=utf8_encode($aa['Transaccion']);
				$Toneladas=number_format($aa['Toneladas'],2);
				$Tarifa=number_format($aa['Tarifa'],0);
				$Valor=number_format($aa['Valor'],0);
				$ValorTotal+=$aa['Valor'];
				$echo.='{"recid":"'.$NumeroTransaccion.'","lfecha":"'.$FechaTiquete.'","ltiquete":"'.$TiqueteEmpresa.'","lempresa":"'.$Empresa.'","lpatio":"'.$Patio.'","ltransaccion":"'.$Transaccion.'","ltm":"'.$Toneladas.'","ltarifa":"'.$Tarifa.'","ltotal":"'.$Valor.'"},';
			}
			$echo = substr($echo, 0, strlen($echo) - 1);
		}else{
			$echo.='{}';
		}
		$echo.=']}';

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
				number_format($ValorTotal,0).'||'.
				$aa['Consecutivo'].'||'.
				$echo;
			}
		}else{
			echo 0;
		}
	}
}elseif($_GET['band']==11){
	$post_data = file_get_contents('php://input');
   	$list_record = json_decode($post_data, true);
	$pre_liquidacion = ENCR::descript($list_record['pre_liquidacion']);
	$array_id = $list_record['array_id'];
	$ioption = $list_record['ioption'];
	$count_exit = 0;
	if($ioption=='in'){
		for ($i=0; $i < count($array_id); $i++){
			$id_for = ENCR::descript($array_id[$i]);
			$sql = "SELECT dbo.GET_TarifaMaquila(idLiquidacion,idProveedor,id,(SELECT CAST(FechaRegistro AS date) FROM Movimiento WHERE NumeroTransaccion='$id_for')) AS Tarifa
			FROM DocumentoLiquidacion WHERE NumeroLiquidacion='$pre_liquidacion'";
			$res = sqlsrv_query($conn,$sql);
			while($aa = sqlsrv_fetch_array($res)){
				$Tarifa = number_format($aa['Tarifa'],0);
			}
			$insert = "INSERT INTO MovimientoLiquidacion (NumeroLiquidacion,NumeroTransaccion,Tarifa) VALUES ('$pre_liquidacion','$id_for','$Tarifa')";
			$res = sqlsrv_query($conn,$insert);
			if($res){
				$count_exit++;
			}
		}
	}elseif($ioption=='out'){
		for ($i=0; $i < count($array_id); $i++){
			$id_for = ENCR::descript($array_id[$i]);
			$array_id[$i]=$id_for;
		}
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
}elseif($_GET['band']==12){
	$post_data = file_get_contents('php://input');
   	$list_record = json_decode($post_data, true);
	$fecha_fact_asentada = $list_record['fecha_fact_asentada'];
	$fact_asociada = $list_record['fact_asociada'];
	$pre_liquidacion = ENCR::descript($list_record['pre_liquidacion']);

	$proveedor = ENCR::descript($list_record['proveedor']);
	$select_liquidador = $list_record['select_liquidador'];
	$reporte_variable = ENCR::descript($list_record['reporte_variable']);
	$empresa = ENCR::descript($list_record['empresa']);

	$sql = "SELECT * FROM DocumentoLiquidacion WHERE NumeroLiquidacion='$pre_liquidacion' AND idProveedor='$proveedor' AND idLiquidacion='$select_liquidador' AND id='$reporte_variable' AND idEmpresa='$empresa'";
	$resul=sqlsrv_query($conn,$sql,$params,$options);
	$row = sqlsrv_num_rows($resul);
	if($row>0){
		$update = "UPDATE DocumentoLiquidacion SET FacturaAsociada='$fact_asociada',FechaLiquidacion='$fecha_fact_asentada'
		WHERE NumeroLiquidacion='$pre_liquidacion' AND idProveedor='$proveedor' AND idLiquidacion='$select_liquidador' AND id='$reporte_variable' AND idEmpresa='$empresa'";
		$res = sqlsrv_query($conn,$update);
		if($res){
			echo 1;
		}else{
			echo 2;
		}
	}else{
		echo 0;
	}
}elseif($_GET['band']==13){
	$post_data = file_get_contents('php://input');
   	$list_record = json_decode($post_data, true);
	$echo='';
	$select_liquidador=$list_record['select_liquidador'];
	$pre_liquidacion=ENCR::descript($list_record['pre_liquidacion']);
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
		<div class="container table-responsive">	
			<table width="50%" align="center" style="border:1px solid #000000">					
				<tr style="background-color: #8CC1EE">
					<td align="center">Centro Trabajo</td>
					<td align="center">Hrs</td>
					<td align="center">Total</td>
				</tr>';
				//<td align="center">Cant. Tiquetes</td>
				$sum_tiquete = 0;
				$sum_horas = 0;
				$sum_total = 0;
				$sql = "SELECT Destino,descuento,SUM(Tiempo) AS Tiempo,SUM(Tiempo*FacturaCargadorDetalle.Tarifa) AS Total 
				FROM vTiquetesCargadores INNER JOIN FacturaCargadorDetalle ON vTiquetesCargadores.idRegistro=FacturaCargadorDetalle.idRegistro 
				WHERE idFacturaCargador='$pre_liquidacion' AND horometroInicial=0
				GROUP BY Destino,descuento ORDER BY Destino,descuento DESC";
				$resul=sqlsrv_query($conn,$sql,$params,$options);
				$row = sqlsrv_num_rows($resul);
				if($row>0){
					while($aa = sqlsrv_fetch_array($resul)){
						$echo.='<tr>';
						$echo.='<td>'.utf8_encode($aa['Destino']).'</td>';
						//$echo.='<td align="right">'.number_format($aa['Cantidad'],0).'</td>';
						$echo.='<td align="right">'.number_format($aa['Tiempo'],2).'</td>';
						$echo.='<td align="right">'.number_format($aa['Total'],0).'</td>';
						$echo.='</tr>';
						//$sum_tiquete+=$aa['Cantidad'];
						$sum_horas+=$aa['Tiempo'];
						$sum_total+=$aa['Total'];
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
		$echo.='<table width="100%" align="center" style="margin-top: 5px; border-collapse: collapse; border-spacing: 1px; border: 1px solid #000;" >					
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
}elseif($_GET['band']==14){
	$post_data = file_get_contents('php://input');    
   	$list_record = json_decode($post_data, true);
	$select_liquidador = $list_record['select_liquidador'];
	$proveedor = ENCR::descript($list_record['proveedor']);
	$variable = ENCR::descript($list_record['variable']);
	$echo = '';
	if($select_liquidador==-1){
		$echo.='<div class="modal-body">
	        <div class="row form-group center-block">
	        	<div class="col-sm-12">';
		$sql = "SELECT * FROM FacturaCargador WHERE idProveedor='$proveedor' AND idCliente='$variable' AND estado=1";
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
        				<th><center><span class="glyphicon glyphicon-open-file"></span></center></th>
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
				$echo.='<td><center>
				<button class="btn btn-danger" onclick="descargar_pdf_historial(\''.ENCR::encript($aa['idFacturaCargador']).'\')">
					<span class="glyphicon glyphicon-open-file"></span>
				</button>
				<button class="btn btn-primary" onclick="desasentar(\''.ENCR::encript($aa['idFacturaCargador']).'\')">
					<span class="glyphicon glyphicon-refresh"></span>
				</button></center>
				</td>';
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
	QualityReportTree.name,SUM(tz_MovimientoTransporte.Toneladas) AS Toneladas,SUM(MovimientoLiquidacion.Tarifa*tz_MovimientoTransporte.Toneladas) AS Valor
FROM DocumentoLiquidacion 
	INNER JOIN Proveedores ON DocumentoLiquidacion.idProveedor=Proveedores.idProveedor 
	INNER JOIN qualityReportTree ON DocumentoLiquidacion.id=qualityReportTree.id
	LEFT JOIN MovimientoLiquidacion ON DocumentoLiquidacion.NumeroLiquidacion=MovimientoLiquidacion.NumeroLiquidacion
	LEFT JOIN tz_MovimientoTransporte ON MovimientoLiquidacion.numerotransaccion=tz_MovimientoTransporte.NumeroTransaccion
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
				$echo.='<td><center>
				<button class="btn btn-danger" onclick="descargar_pdf_historial(\''.ENCR::encript($aa['NumeroLiquidacion']).'\')">
					<span class="glyphicon glyphicon-open-file"></span>
				</button>
				<button class="btn btn-primary" onclick="desasentar(\''.ENCR::encript($aa['NumeroLiquidacion']).'\')">
					<span class="glyphicon glyphicon-refresh"></span>
				</button></center>
				</td>';
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
}elseif($_GET['band']==15){
	$post_data = file_get_contents('php://input');
   	$list_record = json_decode($post_data, true);
	$select_liquidador = $list_record['select_liquidador'];
	$idLiquidacion = ENCR::descript($list_record['variable']);
	if($select_liquidador==-1){
		$sql="UPDATE FacturaCargador SET estado=0 WHERE idFacturaCargador='$idLiquidacion'";
		$resul=sqlsrv_query($conn,$sql);
		if($resul){
			echo 1;
		}else{
			echo 0;
		}
	}else{
		$sql="UPDATE DocumentoLiquidacion SET FechaLiquidacion='1900-01-01',FacturaAsociada=NULL WHERE NumeroLiquidacion='$idLiquidacion' AND idLiquidacion='$select_liquidador'";
		$resul=sqlsrv_query($conn,$sql);
		if($resul){
			echo 1;
		}else{
			echo 0;
		}
	}
}elseif($_GET['band']==16){
	$post_data = file_get_contents('php://input');
   	$list_record = json_decode($post_data, true);
   	$fecha_fact_asentada = $list_record['fecha_fact_asentada'];
	$fact_asociada = $list_record['fact_asociada'];
	$pre_liquidacion = ENCR::descript($list_record['pre_liquidacion']);
	$sql="SELECT * FROM FacturaCargador WHERE idFacturaCargador='$pre_liquidacion'";
	$res=sqlsrv_query($conn,$sql,$params,$options);
	while($aa=sqlsrv_fetch_array($res)){
		$Consecutivo=$aa['prefijoFactura'].'-'.$aa['numeroFactura'];
		$idCliente=$aa['idCliente'];
		$idProveedor=$aa['idProveedor'];
	}
	$sql_dates="SELECT MAX(fechaTiquete) AS Max,MIN(fechaTiquete) AS Min FROM FacturaCargadorDetalle A INNER JOIN TiquetesCargadores B ON A.idRegistro=B.idRegistro WHERE A.idFacturaCargador='$pre_liquidacion'";
	$res=sqlsrv_query($conn,$sql_dates);
	while($aa=sqlsrv_fetch_array($res)){
		$FechaMax=date_format($aa['Max'],'Y-m-d');
		$FechaMin=date_format($aa['Min'],'Y-m-d');
	}
	$Total_valor=0;
	$Tm=0;
	$CANT_viaje=0;
	$title='Pre-liquidacion # '.$Consecutivo.' <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-saved"></span></button>';
	$echo='<div class="container-fluid"><div class="row"><div class="col-sm-5">';
	$echo.= '<br><h4><b>Resumen</b></h4><table class="table table-hover table-condensed table-bordered table-responsive table-striped">';
	$echo.='<thead>
			<th>Concepto</th>
			<th>Tm</th>
			<th>Valor</th>
			<th>CANT. Viajes</th>
			</thead>
			<tbody>';
	$sql="SELECT * FROM dbo.LIQUIDA_Cargadores('$idCliente','$idProveedor','$pre_liquidacion','$FechaMin','$FechaMax')";
	$resul=sqlsrv_query($conn,$sql,$params,$options);
	$row = sqlsrv_num_rows($resul);
	if($row>0){
		while($aa=sqlsrv_fetch_array($resul)){
			$name=utf8_encode($aa['name']);
			$Tm=number_format($aa['Tiempo'],2);
			$CANT_viaje=number_format($aa['CANT_VIAJES'],0);
			$echo.='<tr>';
			//$echo.='<td>'.$aa['iConcepto'].'</td>';
			$echo.='<td>'.$name.'</td>';
			$echo.='<td>'.$Tm.'</td>';
			$echo.='<td>'.number_format($aa['Valor'],0).'</td>';
			$echo.='<td>'.$CANT_viaje.'</td>';
			$echo.='</tr>';
			$Total_valor+=$aa['Valor'];
		}
		$echo.='<tr><td colspan=""><b>TOTAL 👋</b></td><td><b>'.$Tm.'</b></td><td><b>'.number_format($Total_valor,0).'</b></td><td><b>'.$CANT_viaje.'</b></td></tr>';
	}

	$echo.='</tbody></table></div><div class="col-sm-7">
		<br><h4><b>Relación tiquetes</b></h4>
		<table class="table table-hover table-condensed table-bordered table-responsive table-striped">
			<thead>
			<th>Fecha</th>
			<th>Tiquete</th>
			<th>Remisión</th>
			<th>Empresa</th>
			<th>Patio</th>
			<th>Cargador</th>
			<th>Hrs</th>
			<th>Tarifa</th>
			<th>Total</th>
			</thead>';
	$sql= "SELECT B.idRegistro, B.codReporte, A.Tarifa, B.remision, B.fechaTiquete, B.Destino, B.Cliente, B.Proveedor, B.Equipo, B.TiempoTiquete, (B.TiempoTiquete*A.Tarifa) AS Valor
	FROM FacturaCargadorDetalle AS A INNER JOIN
		vTiquetesCargadores AS B ON A.idRegistro = B.idRegistro AND B.idActividad = '00000000-0000-0000-0000-000000000000'
	WHERE A.idFacturaCargador='$pre_liquidacion'
	GROUP BY B.idRegistro, B.codReporte, A.Tarifa, B.remision, B.fechaTiquete, B.Destino, B.Cliente, B.Proveedor, B.Equipo, B.TiempoTiquete
	ORDER BY B.fechaTiquete DESC,B.codReporte DESC";
	    $resul=sqlsrv_query($conn,$sql,$params,$options);
		$row = sqlsrv_num_rows($resul);
		if($row>0){
			while($aa = sqlsrv_fetch_array($resul)){
				$idRegistro=ENCR::encript($aa['idRegistro']);
				$fechaTiquete=date_format($aa['fechaTiquete'],'Y-m-d');
				$codReporte=$aa['codReporte'];
				$remision=$aa['remision'];
				$Cliente=utf8_encode($aa['Cliente']);
				$Destino=utf8_encode($aa['Destino']);
				$Equipo=utf8_encode($aa['Equipo']);
				$TiempoTiquete=number_format($aa['TiempoTiquete'],2);
				$Tarifa=number_format($aa['Tarifa'],0);
				$Valor=number_format($aa['Valor'],0);
				$echo.='<tr>';
				$echo.='<td>'.$fechaTiquete.'</td>';
				$echo.='<td>'.$codReporte.'</td>';
				$echo.='<td>'.$remision.'</td>';
				$echo.='<td>'.$Cliente.'</td>';
				$echo.='<td>'.$Destino.'</td>';
				$echo.='<td>'.$Equipo.'</td>';
				$echo.='<td>'.$TiempoTiquete.'</td>';
				$echo.='<td>'.$Tarifa.'</td>';
				$echo.='<td>'.$Valor.'</td>';
				$echo.='</tr>';
			}
		}

	$echo.='</table></div></div>';
	echo $title.'||'.$echo;
}elseif($_GET['band']==17){
	$post_data = file_get_contents('php://input');
   	$list_record = json_decode($post_data, true);
	$fecha_fact_asentada = $list_record['fecha_fact_asentada'];
	$fact_asociada = $list_record['fact_asociada'];
	$pre_liquidacion = ENCR::descript($list_record['pre_liquidacion']);
	$proveedor = ENCR::descript($list_record['proveedor']);
	$select_liquidador = $list_record['select_liquidador'];
	$reporte_variable = ENCR::descript($list_record['reporte_variable']);
	$empresa = ENCR::descript($list_record['empresa']);

	$sql="SELECT * FROM DocumentoLiquidacion WHERE NumeroLiquidacion='$pre_liquidacion'";
	$res=sqlsrv_query($conn,$sql,$params,$options);
	while($aa=sqlsrv_fetch_array($res)){
		$Consecutivo=$aa['Consecutivo'];
	}
	$Total_valor=0;
	$Tm=0;
	$CANT_viaje=0;
	$title='Pre-liquidacion # '.$Consecutivo.' <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-saved"></span></button>';
	$echo='<div class="container-fluid"><div class="row"><div class="col-sm-5">';
	$echo.= '<br><h4><b>Resumen</b></h4><table class="table table-hover table-condensed table-bordered table-responsive table-striped">';
	$echo.='<thead>
			<th>Concepto</th>
			<th>Tm</th>
			<th>Valor</th>
			<th>CANT. Viajes</th>
			</thead>
			<tbody>';
	$sql="SELECT * FROM dbo.LIQUIDA_Proceso('$proveedor','$reporte_variable','$select_liquidador','$pre_liquidacion','2022-09-01','2022-10-05')";
	$resul=sqlsrv_query($conn,$sql,$params,$options);
	$row = sqlsrv_num_rows($resul);
	if($row>0){
		while($aa=sqlsrv_fetch_array($resul)){
			$name=utf8_encode($aa['name']);
			$Tm=number_format($aa['Volumen'],2);
			$CANT_viaje=number_format($aa['CANT_VIAJES'],0);
			$echo.='<tr>';
			//$echo.='<td>'.$aa['iConcepto'].'</td>';
			$echo.='<td>'.$name.'</td>';
			$echo.='<td>'.$Tm.'</td>';
			$echo.='<td>'.number_format($aa['Valor'],0).'</td>';
			$echo.='<td>'.$CANT_viaje.'</td>';
			$echo.='</tr>';
			$Total_valor+=$aa['Valor'];
		}
		$echo.='<tr><td colspan=""><b>TOTAL</b></td><td><b>'.$Tm.'</b></td><td><b>'.number_format($Total_valor,0).'</b></td><td><b>'.$CANT_viaje.'</b></td></tr>';
	}
	$echo.='</tbody></table></div><div class="col-sm-7">
		<br><h4><b>Relación viajes</b></h4>
		<table class="table table-hover table-condensed table-bordered table-responsive table-striped">
			<thead>
			<th>Fecha</th>
			<th>Tiquete</th>
			<th>Empresa</th>
			<th>Patio</th>
			<th>Transacción</th>
			<th>Toneladas</th>
			<th>Tarifa</th>
			<th>Total</th>
			</thead>
		';
	$sql = "SELECT MovimientoLiquidacion.NumeroTransaccion,FechaRegistro,Empresa,TiqueteEmpresa,
			(CASE WHEN (TipoMovimiento='Traslado') THEN DespachadoDesde ELSE RecepcionadoEn END) AS Patio,Transaccion,Toneladas,Tarifa,(Toneladas*Tarifa) AS Valor
			FROM MovimientoLiquidacion INNER JOIN tz_MovimientoTransporte ON MovimientoLiquidacion.NumeroTransaccion=tz_MovimientoTransporte.NumeroTransaccion 
			WHERE NumeroLiquidacion='$pre_liquidacion'
			ORDER BY tz_MovimientoTransporte.FechaRegistro DESC";
	    $resul=sqlsrv_query($conn,$sql,$params,$options);
		$row = sqlsrv_num_rows($resul);
		if($row>0){
			while($aa = sqlsrv_fetch_array($resul)){
				$NumeroTransaccion=ENCR::encript($aa['NumeroTransaccion']);
				$FechaTiquete=date_format($aa['FechaRegistro'],'Y-m-d');
				$TiqueteEmpresa=$aa['TiqueteEmpresa'];
				$Empresa=utf8_encode($aa['Empresa']);
				$Patio=utf8_encode($aa['Patio']);
				$Transaccion=utf8_encode($aa['Transaccion']);
				$Toneladas=number_format($aa['Toneladas'],2);
				$Tarifa=number_format($aa['Tarifa'],0);
				$Valor=number_format($aa['Valor'],0);
				$echo.='<tr>';
				$echo.='<td>'.$FechaTiquete.'</td>';
				$echo.='<td>'.$TiqueteEmpresa.'</td>';
				$echo.='<td>'.$Empresa.'</td>';
				$echo.='<td>'.$Patio.'</td>';
				$echo.='<td>'.$Transaccion.'</td>';
				$echo.='<td>'.$Toneladas.'</td>';
				$echo.='<td>'.$Tarifa.'</td>';
				$echo.='<td>'.$Valor.'</td>';
				$echo.='</tr>';
			}
		}

	$echo.='</table></div></div>';
	echo $title.'||'.$echo;
}
?>