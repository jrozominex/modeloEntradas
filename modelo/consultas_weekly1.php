<?php
include('conexion.php');

if ($_POST['band']==1)//compras carbon
{	$fecha_inicio = $_POST['fecha_inicio'];
	$fecha_fin = $_POST['fecha_fin'];
	$semana_actual = $_POST['semana_actual'];
	$year=date('Y');
	$jsonstring = "";
	//VARIABLES
	$compras = 0;
	$despachos = 0;
	$inventario = 0;
	$others = 0;
	$inventario_puerto_brisa = 0;
	$exportaciones_pto_brisa = 0;
	$sql = "SELECT * FROM WEEKLY WHERE Periodo='$semana_actual' AND id_valor like '%CARBON%' AND id_valor<>'C_CARBON'";//id_reporte, Anno, Periodo, id_valor, valor
	$res = sqlsrv_query($conn,$sql);
	while($b = sqlsrv_fetch_array($res)){
	    //$b['id_reporte'].' - ';//$b['Anno'].' - ';//$b['Periodo'].' - ';//$b['id_valor'].' - ';//$b['valor'].' - ';
	    if($b['id_valor'] == 'T_C_CARBON'){
	    	$compras = $b['Valor'];
	    }elseif($b['id_valor'] == 'D_CARBON'){
	    	$despachos = $b['Valor'];
	    }elseif($b['id_valor'] == 'INV_CARBON'){
	    	$inventario = $b['Valor'];
	    }
    }
    $inventario_final = $inventario+$compras-$despachos;
    $saldo_inventario = $inventario_final-$inventario;
    $sql = "SELECT * FROM WEEKLY WHERE Periodo='$semana_actual' AND id_valor='INV_PTO_BRISA'";//id_reporte, Anno, Periodo, id_valor, valor
	$res = sqlsrv_query($conn,$sql);
	while($b = sqlsrv_fetch_array($res)){
	    //$b['id_reporte'].' - ';//$b['Anno'].' - ';//$b['Periodo'].' - ';//$b['id_valor'].' - ';//$b['valor'].' - ';
	    $inventario_puerto_brisa = $b['Valor'];
    }
	$sql_salidas_pto = "SELECT isnull(SUM(EmbarqueDetalles.Tonelaje),0) as TM FROM EmbarqueDetalles INNER JOIN 
		Embarque ON EmbarqueDetalles.NumeroEmbarque = Embarque.NumeroEmbarque INNER JOIN 
		Origenes ON EmbarqueDetalles.idOrigen = Origenes.idOrigen INNER JOIN 
		Proveedores ON Origenes.idProveedor = Proveedores.idProveedor INNER JOIN 
		Destino ON Embarque.idDestino = Destino.idDestino 
	WHERE (YEAR(Embarque.FechaPartida) = '$year') AND EmbarqueDetalles.idClasificacion<>(select idClasificacion from Clasificacion where Descripcion='Tèrmico 35x110 mm') AND 
		Embarque.idDestino=(select idDestino from Destino where Descripcion='PUERTO BRISA') and 
		EmbarqueDetalles.idProducto=(SELECT idProducto from Productos where Descripcion='Térmico') AND Proveedores.RazonSocial='MINEX COMPAÑÍA INTERNACIONAL S.A.S. C.I.' AND 
		CAST(FechaPartida AS DATE) BETWEEN '$fecha_inicio' AND '$fecha_fin'";

	$res_salida_pto = sqlsrv_query($conn,utf8_decode($sql_salidas_pto));
	while($salida_pto = sqlsrv_fetch_array($res_salida_pto)){
		$exportaciones_pto_brisa = $salida_pto['TM'];
	}
	$inventario_final_puerto_brisa = $inventario_puerto_brisa+$despachos-$exportaciones_pto_brisa;
	/************************************************SEMANA POR LA QUE SE ESTA CONSULTANDO ****************************************/
	$sql_compras = "SELECT isnull(sum(Toneladas), 0) as Tm, Proveedor FROM tz_MovimientoTransporte WHERE TipoMovimiento = 'Recepción' 
	AND Empresa = 'MINEX' AND Producto = 'Carbón' AND Proveedor NOT IN ('SANOHA LTDA') AND cast(FechaRegistro as date) BETWEEN '$fecha_inicio' 
	AND '$fecha_fin' GROUP BY Proveedor ORDER BY sum(Toneladas) desc";

	$res1 = sqlsrv_query($conn,utf8_decode($sql_compras));
	$json = array();
	while($rows1 = sqlsrv_fetch_array($res1)){
		if($rows1['Tm'] > 0){
			if($compras > 0){
				$rendimiento = number_format(($rows1['Tm']/$compras)*100,2);
			}else{
				$rendimiento = 0;
			}
		}else{
			$rendimiento = 0;
		}
		if($rendimiento >= 20){
			$json[] = array(
		    	'Proveedor' => $rows1['Proveedor'],
		    	'Tm' => number_format($rows1['Tm'],2,',','.')
			);
		}elseif($rendimiento < 20){
			$others+=$rows1['Tm'];
		}
	}
	if($compras > 0){
		$jsonstring = json_encode($json);
	}else{
		$jsonstring = "";
		$others = 0;
	}
/************************************************************************************************************************/	
	$sql_ventas = "SELECT Proveedor,isnull(SUM(ToneladasRecepcion),0) as Tm, RecepcionadoEn FROM tz_MovimientoTransporte 
	WHERE tipoMovimiento='Traslado' AND cast(SalidaDestino as date) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND Empresa='MINEX' 
	AND RecepcionadoEn in ('PUERTO BRISA', 'PUERTO COMPAS') AND producto='CARBÓN' AND Clasificacion<>'Tèrmico 35x110 mm' 
	AND Proveedor NOT IN ('SANOHA LTDA')
	group by Proveedor, RecepcionadoEn order by sum(Toneladas) desc";

	$res_ventas = sqlsrv_query($conn,utf8_decode($sql_ventas));
	$jsonventas = array();
	while($rows = sqlsrv_fetch_array($res_ventas)){
		$jsonventas[] = array(
		    'Proveedor' => $rows['RecepcionadoEn'],
		    'Tm' => number_format($rows['Tm'],2)
		);
	}
	$jsonstring_ventas = json_encode($jsonventas);
	//SALIDAS
	$datos = number_format($inventario,2)."||". //0
			 number_format($compras,2)."||". //1
			 $jsonstring."||". 	  //2
			 $others."||". 		  //3
			 number_format($despachos,2)."||".//4
			 $jsonstring_ventas."||".  //5
			 number_format($inventario_final,2)."||". //6
			 $saldo_inventario."||".  //7
			 number_format($inventario_puerto_brisa,2)."||". //8
			 number_format($exportaciones_pto_brisa,2)."||".  //9
			 number_format($inventario_final_puerto_brisa,2); //10
	echo $datos;

}elseif($_POST['band'] == 2){// EXPORTACION COQUE
	$fecha_inicio = $_POST['fecha_inicio'];
	$fecha_fin = $_POST['fecha_fin'];
	$semana_actual = $_POST['semana_actual'];
	$year=date('Y');
	$jsonstring = "";
	//VARIABLES
	$compras = 0;
	$despachos = 0;
	$inventario = 0;
	$inventario_puerto = 0;
	$exportaciones_puerto = 0;
	$sql = "SELECT * FROM WEEKLY WHERE Periodo='$semana_actual' AND id_valor like '%COQUE%'";//id_reporte, Anno, Periodo, id_valor, valor
	$res = sqlsrv_query($conn,$sql);
	while($b = sqlsrv_fetch_array($res)){
	    //$b['id_reporte'].' - ';//$b['Anno'].' - ';//$b['Periodo'].' - ';//$b['id_valor'].' - ';//$b['valor'].' - ';
	    if($b['id_valor'] == 'C_COQUE'){
	    	$compras = $b['Valor'];
	    }elseif($b['id_valor'] == 'D_COQUE'){
	    	$despachos = $b['Valor'];
	    }elseif($b['id_valor'] == 'INV_COQUE'){
	    	$inventario = $b['Valor'];
	    }elseif($b['id_valor'] == 'INV_PTO_COQUE'){
	    	$inventario_puerto = $b['Valor'];
	    }
    }
    $inventario_final = $inventario+$compras-$despachos;
    $saldo_inventario = $inventario_final-$inventario;

    /*$sql_salidas_pto = "SELECT SUM(EmbarqueDetalles.Tonelaje) AS TM FROM EmbarqueDetalles INNER JOIN 
    	Embarque ON EmbarqueDetalles.NumeroEmbarque = Embarque.NumeroEmbarque
		WHERE (CAST(Embarque.FechaPartida AS DATE) BETWEEN '$fecha_inicio' AND '$fecha_fin')";*/

	$sql_salidas_pto = "SELECT isnull(SUM(EmbarqueDetalles.Tonelaje),0) as TM FROM EmbarqueDetalles INNER JOIN 
	Embarque ON EmbarqueDetalles.NumeroEmbarque = Embarque.NumeroEmbarque INNER JOIN 
	Origenes ON EmbarqueDetalles.idOrigen = Origenes.idOrigen INNER JOIN 
	Proveedores ON Origenes.idProveedor = Proveedores.idProveedor 
	WHERE (YEAR(Embarque.FechaPartida) = '$year') AND Embarque.idDestino=(select idDestino from Destino where Descripcion='PUERTO BRISA') AND 
	EmbarqueDetalles.idProducto=(SELECT idProducto from Productos where Descripcion='COQUE') AND Proveedores.RazonSocial='MINEX COMPAÑÍA INTERNACIONAL S.A.S. C.I.' AND 
	CAST(FechaPartida AS DATE) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
	$res_salida_pto = sqlsrv_query($conn,utf8_decode($sql_salidas_pto));
	while($salida_pto = sqlsrv_fetch_array($res_salida_pto)){
		$exportaciones_puerto = $salida_pto['TM'];
	}
	$inventario_final_puerto = $inventario_puerto+$despachos-$exportaciones_puerto;
	/************************************************SEMANA POR LA QUE SE ESTA CONSULTANDO ****************************************/
	$sql_compras = "SELECT isnull(SUM(Toneladas),0) as Tm, Proveedor FROM tz_MovimientoTransporte WHERE tipoMovimiento='Traslado' 
	AND cast(FechaRegistro as date) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND Empresa='MINEX' AND producto='COQUE' 
	GROUP BY Proveedor ORDER BY sum(Toneladas) desc";

	$res1 = sqlsrv_query($conn,utf8_decode($sql_compras));
	$json = array();
	while($rows1 = sqlsrv_fetch_array($res1)){
		$json[] = array(
	    	'Proveedor' => $rows1['Proveedor'],
	    	'Tm' => number_format($rows1['Tm'],2,',','.')
		);
	}
	if($compras > 0){
		$jsonstring = json_encode($json);
	}else{
		$jsonstring = "a";
	}
/************************************************************************************************************************/	
	/*$sql_ventas = "SELECT Proveedor,isnull(SUM(ToneladasRecepcion),0) as Tm, RecepcionadoEn FROM tz_MovimientoTransporte 
	WHERE tipoMovimiento='Traslado' AND cast(SalidaDestino as date) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND Empresa='MINEX' 
	AND RecepcionadoEn in ('PUERTO BRISA', 'PUERTO COMPAS') AND producto='CARBÓN' AND Clasificacion<>'Tèrmico 35x110 mm' 
	AND Proveedor NOT IN ('SANOHA LTDA')
	group by Proveedor, RecepcionadoEn order by sum(Toneladas) desc";*/

	$sql_ventas = "SELECT isnull(SUM(ToneladasRecepcion),0) as Tm, RecepcionadoEn FROM tz_MovimientoTransporte WHERE tipoMovimiento='Traslado' 
	AND cast(SalidaDestino as date) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND Empresa='MINEX' AND producto='COQUE'
	group by Proveedor, RecepcionadoEn order by sum(Toneladas) desc";

	$res_ventas = sqlsrv_query($conn,utf8_decode($sql_ventas));
	$jsonventas = array();
	while($rows = sqlsrv_fetch_array($res_ventas)){
		$jsonventas[] = array(
		    'Proveedor' => $rows['RecepcionadoEn'],
		    'Tm' => number_format($rows['Tm'],2)
		);
	}
	$jsonstring_ventas = json_encode($jsonventas);
	//SALIDAS
	$datos = number_format($inventario,2)."||". //0
			 number_format($compras,2)."||". //1
			 $jsonstring."||". 	  //2
			 number_format($despachos,2)."||".//3
			 $jsonstring_ventas."||".  //4
			 number_format($inventario_final,2)."||". //5
			 $saldo_inventario."||".  //6
			 number_format($inventario_puerto,2)."||". //7
			 number_format($exportaciones_puerto,2)."||".  //8
			 number_format($inventario_final_puerto,2); //9
	echo $datos;
}elseif($_POST['band'] == 3){ //INVENTARIO DE PUERTO BRISA

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