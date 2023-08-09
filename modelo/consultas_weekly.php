<?php
include('conexion.php');

if ($_POST['band']==1)//compras carbon
{	$fecha_inicio = $_POST['fecha_inicio'];
	$fecha_fin = $_POST['fecha_fin'];
	$ra_semana = '01';
	$semana_actual = $_POST['semana_actual'];
	$semana_anterior = $semana_actual-1;
	if($semana_anterior < 10){
		$semana_anterior = '0'.$semana_anterior;
	}
	$year=date('Y');
	$jsonstring = "";
	//
	$last_year = date('Y');
	if($last_year == '2019'){
		if(($semana_actual-1) <> 0){
			$weeks = date("W", mktime(0,0,0,12,28,$last_year));
			//PRIMERA SEMANA AÑO ANTERIOR
			for($last_day1=1; $last_day1<8; $last_day1++)
			{
			   $last_1ra_semana[$last_day1] = date('Y-m-d', strtotime($last_year."W".'01'.$last_day1));
			}
			//ULTIMA SEMANA AÑO ANTERIOR
			for($last_day1=1; $last_day1<8; $last_day1++)
			{
			   $last_ultima_semana[$last_day1] = date('Y-m-d', strtotime($last_year."W".$semana_anterior.$last_day1));
			}
			/************************************************ INVENTARIO DE PRINCIPIO DE AÑO ****************************************/
			//COMPRAS
				$sql_compras_ini_año="SELECT sum(Toneladas) as Tm FROM tz_MovimientoTransporte WHERE TipoMovimiento='Recepción' 
					and FechaRegistro  between '$last_1ra_semana[1]' and '$last_ultima_semana[7]' and Empresa='MINEX' and producto='CARBÓN' 
					AND  Proveedor<>'SANOHA LTDA'";
				$res = sqlsrv_query($conn,utf8_decode($sql_compras_ini_año));
				while($inv_com = sqlsrv_fetch_array($res)){
					$TM_inventario_ini_año = $inv_com['Tm'];
				}
			//GRANULADO
					
				$sql_granulado = " SELECT SUM(Toneladas) AS TM  FROM tz_MovimientoTransporte WHERE tipoMovimiento='Traslado' 
					AND IngresoDestino  BETWEEN '$last_1ra_semana[1]' AND '$last_ultima_semana[7]' AND Clasificacion='Tèrmico 35x110 mm' AND Empresa='MINEX' 
					and Producto='CARBÓN' and RecepcionadoEn in ('PUERTO BRISA','PUERTO COMPAS')
			 		ORDER BY SUM(Toneladas) DESC";
			 	$res_granulado = sqlsrv_query($conn,utf8_decode($sql_granulado));
			 	while($rows = sqlsrv_fetch_array($res_granulado)){
			 		$total_granulado=$rows['TM'];
			 	}
			//CLIENTES 
				//NACIONALES
			 	$sql_clientes = " SELECT SUM(Toneladas) AS TM  FROM tz_MovimientoTransporte WHERE tipoMovimiento='Traslado' 
					AND IngresoDestino  BETWEEN '$last_1ra_semana[1]' AND '$last_ultima_semana[7]' AND Empresa='MINEX' AND RecepcionadoEn 
					not in ('PUERTO BRISA', 'PUERTO COMPAS') and producto='CARBÓN' AND Clasificacion<>'Tèrmico 35x110 mm'
			 		ORDER BY SUM(Toneladas) DESC";
			 	$res_clientes = sqlsrv_query($conn,utf8_decode($sql_clientes));
			 	while($rows = sqlsrv_fetch_array($res_clientes)){
			 		$total_clientes=$rows['TM'];
			 	}
		 	//DESPACHOS
			 	$sql_ventas_ini_año="SELECT sum(Toneladas) as Tm FROM tz_MovimientoTransporte WHERE TipoMovimiento='Traslado' 
					AND SalidaDestino  between '$last_1ra_semana[1]' AND '$last_ultima_semana[7]' AND Empresa='MINEX' AND producto='CARBÓN'
					AND Clasificacion<>'Tèrmico 35x110 mm' AND RecepcionadoEn in ('PUERTO BRISA','PUERTO COMPAS')";
				$res = sqlsrv_query($conn,utf8_decode($sql_ventas_ini_año));
				while($inv_com = sqlsrv_fetch_array($res)){
					$TM_inventario_ventas = $inv_com['Tm']; 
				}
				//total inventario compras =
				$TM_inventario_ini_año = $TM_inventario_ini_año - ($total_clientes+$total_granulado);
		 	//$total_semana_inv = $TM_inventario_ini_año-$TM_inventario_ventas;
			$total_semana_inv = $TM_inventario_ini_año-$TM_inventario_ventas + 9490;
		}else{
			$total_semana_inv = 9490;
		}
		/************************************************************************************************************************/
		/*	$sql_ventas="SELECT sum(Toneladas) as Tm from tz_MovimientoTransporte where tipoMovimiento='Traslado' 
			and fecharegistro  between '$last_1ra_semana[1]' and '$last_ultima_semana[7]' and empresa='MINEX'";
			$res_ventas = sqlsrv_query($conn,utf8_decode($sql_ventas));
			while($rows = sqlsrv_fetch_array($res_ventas)){
				$total_despacho_inv+= $rows['Tm'];
			}
			$total_semana_inv = $TM_inventario-$total_despacho_inv;*/
	}
	/****************************************************************************************/
	for($day1=1; $day1<8; $day1++)
	{
	   $fecha_1ra_semana[$day1] = date('Y-m-d', strtotime($year."W".$ra_semana.$day1));
	}
	//
	for($day=1; $day<8; $day++)
	{
	    $fecha_semana_anterior[$day] = date('Y-m-d', strtotime($year."W".$semana_anterior.$day));
	}
	$total_compras = 0;
	$total_compras = 0;
	$total_granulado = 0;
	$total_clientes = 0;
	$others = 0;
	$total_despacho = 0;
	$total_despacho_inv = 0;
	/************************************************ INVENTARIO DE PRINCIPIO DE AÑO ****************************************/
		/*$sql_compras_sem_anterior="SELECT sum(Toneladas) as Tm FROM tz_MovimientoTransporte WHERE TipoMovimiento='Recepción' 
			and FechaRegistro  between '$fecha_1ra_semana[1]' and '$fecha_semana_anterior[7]' and Empresa='MINEX'";
		$res = sqlsrv_query($conn,utf8_decode($sql_compras_sem_anterior));
		while($inv_com = sqlsrv_fetch_array($res)){
			$TM_inventario = $inv_com['Tm'];
		}
		$sql_granulado = " SELECT SUM(Toneladas) AS TM  FROM tz_MovimientoTransporte WHERE tipoMovimiento='Traslado' 
			AND IngresoDestino  BETWEEN '$fecha_1ra_semana[1]' AND '$fecha_semana_anterior[7]' AND Clasificacion='Tèrmico 35x110 mm' AND Empresa='MINEX'
			AND Proveedor = 'MINEX COMPAÑÍA INTERNACIONAL S.A.S. C.I.'
	 		ORDER BY SUM(Toneladas) DESC";
	 	$res_granulado = sqlsrv_query($conn,utf8_decode($sql_granulado));
	 	while($rows = sqlsrv_fetch_array($res_granulado)){
	 		$total_granulado_inv=number_format($rows['TM'],2);
	 	}
	 	$TM_inventario = $TM_inventario-$total_granulado_inv;
	 	
	/************************************************************************************************************************/
		/*$sql_ventas="SELECT sum(Toneladas) as Tm from tz_MovimientoTransporte where tipoMovimiento='Traslado' 
		and fecharegistro  between '$fecha_1ra_semana[1]' and '$fecha_semana_anterior[7]' and empresa='MINEX' and Clasificacion<>'Tèrmico 35x110 mm'";
		$res_ventas = sqlsrv_query($conn,utf8_decode($sql_ventas));
		while($rows = sqlsrv_fetch_array($res_ventas)){
			$total_despacho_inv+= $rows['Tm'];
		}
		$total_semana_inv = $TM_inventario-$total_despacho_inv;*/

	/************************************************SEMANA POR LA QUE SE ESTA CONSULTANDO ****************************************/
	$sql_compras="SELECT Proveedor, sum(Toneladas) as Tm, RecepcionadoEn from tz_MovimientoTransporte where tipoMovimiento='Recepción' 
		and cast(fecharegistro as date)  between '$fecha_inicio' and '$fecha_fin' and empresa='MINEX' AND Producto='Carbón'
		AND  Proveedor<>'SANOHA LTDA'
	group by Proveedor, RecepcionadoEn order by sum(Toneladas) desc";
	$res1 = sqlsrv_query($conn,utf8_decode($sql_compras));
	$json = array();
	while($rows1 = sqlsrv_fetch_array($res1)){
		$total_compras+= $rows1['Tm'];
		if($rows1['Tm'] >= 500){
			$json[] = array(
		    	'Proveedor' => $rows1['Proveedor'],
		    	'Tm' => number_format($rows1['Tm'],2,',','.')
			);
		}elseif($rows1['Tm'] <= 500){
			$others+=$rows1['Tm'];
		}
	}
	$jsonstring = json_encode($json);
	//echo $jsonstring;
	$sql_granulado = " SELECT SUM(Toneladas) AS TM  FROM tz_MovimientoTransporte WHERE tipoMovimiento='Traslado' 
		AND IngresoDestino  BETWEEN '$fecha_inicio' AND '$fecha_fin' AND Clasificacion='Tèrmico 35x110 mm' AND Empresa='MINEX' 
		and Producto='CARBÓN' and RecepcionadoEn in ('PUERTO BRISA','PUERTO COMPAS') AND  Proveedor<>'SANOHA LTDA'
 		ORDER BY SUM(Toneladas) DESC";
 	$res_granulado = sqlsrv_query($conn,utf8_decode($sql_granulado));
 	while($rows = sqlsrv_fetch_array($res_granulado)){
 		$total_granulado=$rows['TM'];
 	}

 	$sql_clientes = " SELECT SUM(Toneladas) AS TM  FROM tz_MovimientoTransporte WHERE tipoMovimiento='Traslado' 
		AND IngresoDestino  BETWEEN '$fecha_inicio' AND '$fecha_fin' AND Empresa='MINEX' AND RecepcionadoEn 
		not in ('PUERTO BRISA', 'PUERTO COMPAS') and producto='CARBÓN' AND Clasificacion<>'Tèrmico 35x110 mm' AND  Proveedor<>'SANOHA LTDA'
 		ORDER BY SUM(Toneladas) DESC";
 	$res_clientes = sqlsrv_query($conn,utf8_decode($sql_clientes));
 	while($rows = sqlsrv_fetch_array($res_clientes)){
 		$total_clientes=$rows['TM'];
 	}
 	/*echo $total_compras." - ";
 	echo $total_granulado." - ";
 	echo $total_clientes;*/
 	$total_compras = $total_compras-$total_granulado-$total_clientes;
 	if($total_compras < 0){
 		$total_compras = 0;
 		$jsonstring = "";
 	}
 	$others = $others-$total_granulado;
 	
/************************************************************************************************************************/
	$sql_ventas="SELECT sum(Toneladas) as Tm, RecepcionadoEn from tz_MovimientoTransporte where tipoMovimiento='Traslado' 
	and SalidaDestino  between '$fecha_inicio' and '$fecha_fin' and empresa='MINEX' and RecepcionadoEn in ('PUERTO BRISA', 'PUERTO COMPAS')
	AND Clasificacion<>'Tèrmico 35x110 mm' AND  Proveedor<>'SANOHA LTDA'
	group by  RecepcionadoEn";
	$res_ventas = sqlsrv_query($conn,utf8_decode($sql_ventas));
	$jsonventas = array();
	while($rows = sqlsrv_fetch_array($res_ventas)){
		$total_despacho+= $rows['Tm'];
		$jsonventas[] = array(
		    'Proveedor' => $rows['RecepcionadoEn'],
		    'Tm' => number_format($rows['Tm'],2)
		);
	}
	$jsonstring_ventas = json_encode($jsonventas);
	//
	$total_semana = $total_semana_inv+$total_compras-$total_despacho;
	$rendimiento = $total_semana-$total_semana_inv;
	//SALIDAS
	$datos = number_format($total_semana_inv,2)."||". //0
			 number_format($total_compras,2)."||". //1
			 $jsonstring."||". 	  //2
			 $others."||". 		  //3
			 number_format($total_despacho,2)."||".//4
			 $jsonstring_ventas."||".  //5
			 number_format($total_semana,2)."||". //6
			 number_format($rendimiento,2); //7
	echo $datos;

}elseif($_POST['band'] == 2){// INFORMACIÓN DE LOS BUQUES
	$year = date('Y');
	$total_tm = 0;
	$jsonstring = "";
	$sql_buques = "SELECT Embarque.NombreMotoNave ,Destino.Descripcion ,EmbarqueDetalleEx.Volumen ,Embarque.FechaPartida  FROM EmbarqueDetalleEx
INNER JOIN Destino ON EmbarqueDetalleEx.idDestino=Destino.idDestino
INNER JOIN Embarque ON EmbarqueDetalleEx.numeroembarque = Embarque.NumeroEmbarque
 WHERE EmbarqueDetalleEx.idProducto='18A23F2B-B74A-466A-B888-C7ACF2BB53BC' 
 AND EmbarqueDetalleEx.idClasificacion<>'B8FA0851-B1DF-40D1-8149-6C04B0CB7B44' AND EmbarqueDetalleEx.Volumen<>0
 AND YEAR(Embarque.FechaPartida)='$year'";
	$res_buques = sqlsrv_query($conn,$sql_buques);
	$json = array();
	while($buques = sqlsrv_fetch_array($res_buques)){
		$total_tm += $buques['Volumen'];
		$json[] = array(
		    'NombreMotoNave' => utf8_encode($buques['NombreMotoNave']),
		    'FechaPartida' => date_format($buques['FechaPartida'],'F D, Y'),
		    'PesoAforo' => number_format($buques['Volumen'],2),
		);
	}
	$jsonstring = json_encode($json);
	$datos = $jsonstring."||".
			number_format($total_tm,2)."||".
			$year;
	echo $datos;
}elseif($_POST['band'] == 3){ //INVENTARIO DE PUERTO BRISA
	$year = date('Y');
	$fecha_inicio = $_POST['fecha_inicio'];
	$fecha_fin = $_POST['fecha_fin'];
	$semana_actual = $_POST['semana_actual'];
	$semana_anterior = $semana_actual-1;
	if($semana_anterior == 0){
		$semana_anterior = '01';
	}elseif($semana_anterior < 10){
		$semana_anterior = '0'.$semana_anterior;
	}
	//
	$weeks = date("W", mktime(0,0,0,12,28,$year));
	//PRIMERA SEMANA AÑO ANTERIOR
	for($last_day1=1; $last_day1<8; $last_day1++)
	{
	   $last_1ra_semana[$last_day1] = date('Y-m-d', strtotime($year."W".'01'.$last_day1));
	}
	//ULTIMA SEMANA AÑO ANTERIOR
	for($last_day1=1; $last_day1<8; $last_day1++)
	{
	   $last_ultima_semana[$last_day1] = date('Y-m-d', strtotime($year."W".$semana_anterior.$last_day1));
	}
	$sql_inventario="SELECT sum(Toneladas) as Tm FROM tz_MovimientoTransporte WHERE TipoMovimiento='Traslado' 
				and SalidaDestino between '$last_1ra_semana[1]' and '$last_ultima_semana[7]' and Empresa='MINEX' and producto='CARBÓN'
				and RecepcionadoEn='PUERTO BRISA'";
	$res = sqlsrv_query($conn,utf8_decode($sql_inventario));
	while($ingresos = sqlsrv_fetch_array($res)){
		$total_ingresos = $ingresos['Tm'];
	}
	//echo $total_ingresos.' - ';
	$sql_buques = "SELECT SUM(EmbarqueDetalleEx.Volumen) AS Volumen FROM EmbarqueDetalleEx
		INNER JOIN Embarque ON EmbarqueDetalleEx.numeroembarque = Embarque.NumeroEmbarque
		 WHERE EmbarqueDetalleEx.idProducto='18A23F2B-B74A-466A-B888-C7ACF2BB53BC' 
		 AND EmbarqueDetalleEx.idClasificacion<>'B8FA0851-B1DF-40D1-8149-6C04B0CB7B44' AND EmbarqueDetalleEx.Volumen<>0
		 AND CAST(Embarque.FechaPartida AS DATE) between '$last_1ra_semana[1]' AND '$last_ultima_semana[7]' 
		 AND EmbarqueDetalleEx.idDestino='1317B661-9928-435F-9FD5-63A240652F7B'";
	$res = sqlsrv_query($conn,$sql_buques);
	while($buques = sqlsrv_fetch_array($res)){
		$total_embarques = $buques['Volumen'];
	}
	//echo $total_embarques.' - ';
	if(($semana_anterior = $semana_actual-1) <> 0){
		$inventario_puerto_brisa = $total_ingresos-$total_embarques + 31558;
	}else{
		$inventario_puerto_brisa = 31558;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	$sql_buques = "SELECT SUM(EmbarqueDetalleEx.Volumen) AS Volumen FROM EmbarqueDetalleEx
		INNER JOIN Embarque ON EmbarqueDetalleEx.numeroembarque = Embarque.NumeroEmbarque
		 WHERE EmbarqueDetalleEx.idProducto='18A23F2B-B74A-466A-B888-C7ACF2BB53BC' 
		 AND EmbarqueDetalleEx.idClasificacion<>'B8FA0851-B1DF-40D1-8149-6C04B0CB7B44' AND EmbarqueDetalleEx.Volumen<>0
		 AND CAST(Embarque.FechaPartida AS DATE) between '$fecha_inicio' AND '$fecha_fin' 
		 AND EmbarqueDetalleEx.idDestino='1317B661-9928-435F-9FD5-63A240652F7B'";
	$res = sqlsrv_query($conn,$sql_buques);
	while($buques = sqlsrv_fetch_array($res)){
		$total_embarques_semana = $buques['Volumen'];
	}
	$sql_ventas="SELECT sum(Toneladas) as Tm from tz_MovimientoTransporte where tipoMovimiento='Traslado' 
	and SalidaDestino  between '$fecha_inicio' and '$fecha_fin' and empresa='MINEX' and RecepcionadoEn in ('PUERTO BRISA', 'PUERTO COMPAS')
	AND Clasificacion<>'Tèrmico 35x110 mm'";
	$res_ventas = sqlsrv_query($conn,utf8_decode($sql_ventas));
	while($rows = sqlsrv_fetch_array($res_ventas)){
		$total_despacho= $rows['Tm'];
	}
	$current = ($inventario_puerto_brisa+$total_despacho)-$total_embarques_semana;
	echo $datos = number_format($inventario_puerto_brisa,2)."||".
				  number_format($total_embarques_semana,2)."||".
				  number_format($current,2);

}elseif($_POST['band'] == 4){//CLIENTES (PRODECO)
	$year = date('Y');
	$fecha_inicio = $_POST['fecha_inicio'];
	$fecha_fin = $_POST['fecha_fin'];
	$semana_actual = $_POST['semana_actual'];
	$semana_anterior = $semana_actual-1;
	$cliente = $_POST['select_cliente'];
	$total_compras_prodeco = 0;
	//CONSULTAS
	$sql_clientes_llegadas = "SELECT SUM(Toneladas) AS TM,Proveedor  FROM tz_MovimientoTransporte WHERE tipoMovimiento='Traslado' 
		AND FechaRegistro  BETWEEN '$fecha_inicio' AND '$fecha_fin' AND Empresa='MINEX' AND RecepcionadoEn in ('MINA CALENTURITAS') 
		and producto='CARBÓN' AND Clasificacion<>'Tèrmico 35x110 mm' Group by Proveedor order by SUM(Toneladas) desc";
 	$res_clientes = sqlsrv_query($conn,utf8_decode($sql_clientes_llegadas));
 	$jsoncompras = array();
 	while($rows = sqlsrv_fetch_array($res_clientes)){
 		$total_compras_prodeco+=$rows['TM'];
 		$jsoncompras[] = array(
		    'Proveedor' => utf8_encode($rows['Proveedor']),
		    'Tm' => number_format($rows['TM'],2)
		);
 	}
 	$jsonstring_compra = json_encode($jsoncompras);

 	$sql_clientes_despachos="SELECT SUM(ToneladasRecepcion) AS TM  FROM tz_MovimientoTransporte WHERE tipoMovimiento='Traslado' 
 		AND SalidaDestino  BETWEEN '$fecha_inicio' AND '$fecha_fin' AND Empresa='MINEX' AND RecepcionadoEn in ('MINA CALENTURITAS') 
 		AND producto='CARBÓN' AND Clasificacion<>'Tèrmico 35x110 mm'";
	$res_clientes = sqlsrv_query($conn,utf8_decode($sql_clientes_despachos));
	$jsonventas = array();
 	while($rows = sqlsrv_fetch_array($res_clientes)){
		$Tm= $rows['TM'];
 	}
 	$Inventari_final=$total_compras_prodeco-$Tm;


 	echo $datos= $total_compras_prodeco
 		."||".$jsonstring_compra
 		."||".number_format($Tm,2)
 		.'||'.number_format($Inventari_final,2);

}elseif($_POST['band'] == 5){//CLIENTES (NACIONALES)
	$year = date('Y');
	$fecha_inicio = $_POST['fecha_inicio'];
	$fecha_fin = $_POST['fecha_fin'];
	$semana_actual = $_POST['semana_actual'];
	$semana_anterior = $semana_actual-1;
	$cliente = $_POST['select_cliente'];
	$total_compras_nacionales = 0;
	//CONSULTAS
	$sql_clientes_llegadas = "SELECT SUM(Toneladas) AS TM,Proveedor  FROM tz_MovimientoTransporte WHERE tipoMovimiento='Traslado' 
		AND FechaRegistro  BETWEEN '$fecha_inicio' AND '$fecha_fin' AND Empresa='MINEX' AND RecepcionadoEn not in ('MINA CALENTURITAS', 'PUERTO BRISA', 'PUERTO COMPAS') 
		and producto='CARBÓN' AND Clasificacion<>'Tèrmico 35x110 mm' Group by Proveedor order by SUM(Toneladas) desc";
 	$res_clientes = sqlsrv_query($conn,utf8_decode($sql_clientes_llegadas));
 	$jsoncompras = array();
 	while($rows = sqlsrv_fetch_array($res_clientes)){
 		$total_compras_nacionales+=$rows['TM'];
 		$jsoncompras[] = array(
		    'Proveedor' => utf8_encode($rows['Proveedor']),
		    'Tm' => number_format($rows['TM'],2)
		);
 	}
 	$jsonstring_compra = json_encode($jsoncompras);

 	$sql_clientes_despachos="SELECT SUM(ToneladasRecepcion) AS TM  FROM tz_MovimientoTransporte WHERE tipoMovimiento='Traslado' 
 		AND SalidaDestino  BETWEEN '$fecha_inicio' AND '$fecha_fin' AND Empresa='MINEX' AND RecepcionadoEn not in ('MINA CALENTURITAS', 'PUERTO BRISA', 'PUERTO COMPAS') 
 		AND producto='CARBÓN' AND Clasificacion<>'Tèrmico 35x110 mm'";
	$res_clientes = sqlsrv_query($conn,utf8_decode($sql_clientes_despachos));
	$jsonventas = array();
 	while($rows = sqlsrv_fetch_array($res_clientes)){
		$Tm= $rows['TM'];
 	}
 	$Inventari_final=$total_compras_nacionales-$Tm;

 	echo $datos= $total_compras_nacionales
 		."||".$jsonstring_compra
 		."||".number_format($Tm,2)
 		.'||'.number_format($Inventari_final,2);
}