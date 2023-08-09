<?php
session_start();
include ("../modelo/conexion.php");
class DAO 
{	var $fecha;
	var $hora;
	var $usuarioin;
	
	public function DAO()
	{	$fecha=date("Y/m/d");
		$hora=date("Y/m/d/h:i:s");
		$usuarioin=$_SESSION["usuario"];
	}

	public function buscar_empresa()
	{	global $conn;
		$sql = "SELECT NombreCorto, idProveedor FROM proveedores WHERE Empresa=1 ORDER BY NombreCorto";
		$resultado = sqlsrv_query($conn,$sql);		
		return($resultado);
		sqlsrv_close($conn);
	}

	public function generar_pre_liquid($id_empresa,$id_cliente)
	{	global $conn;
		$fecha= date('Y-m-d H:i:s');
		$id_usuario=$_SESSION["usuario"];
		if($id_empresa=='24B7153E-AB4C-4DB7-81BD-67BC87AF014C')
			$prefijo='P-MX';
		elseif($id_empresa=='0247FA36-ABF5-4A35-9E8B-BE7C574243DE')
			$prefijo='P-GL';
		elseif($id_empresa=='EFE16A94-8055-40E1-8A27-0F282D605F35')
			$prefijo='P-SX';
		elseif($id_empresa=='4A442AA8-6532-4F4F-8CED-51A6999DDB5E')
			$prefijo='P-LA';
		elseif($id_empresa=='8B263A3E-316A-443F-874C-7300A01585E3')
			$prefijo='P-LM';
		elseif($id_empresa=='B34A99EE-662D-4A40-B355-D2C84F615E91')
			$prefijo='P-LT';

		/*$consulta = "SELECT idProveedor FROM Proveedores WHERE RazonSocial = '$nombre_cliente'";
		$params = array();
	   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
	   	$resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
	   	$filas=sqlsrv_num_rows($resultado);  
		while($rows = sqlsrv_fetch_array($resultado)) {
			$id_cliente = $rows['idProveedor'];
		}*/
		$consulta = "SELECT MAX(numero_factura) as Numero_factura FROM Factura_Venta WHERE estado=0 
			AND id_empresa='$id_empresa' and tipo_factura=1";
		//$res = sqlsrv_query($conn,$consulta);
		$params = array();
   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
   	$resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
   	$filas=sqlsrv_num_rows($resultado);
   	if($filas > 0){
			while($num_fact = sqlsrv_fetch_array($resultado)){
				$consecutivo = $num_fact['Numero_factura']+1;
			}
		}else{
			$consecutivo = 1;
		}
		$sql = "INSERT INTO Factura_Venta (id_factura_venta, prefijo_factura,numero_factura, factura_asociada, fecha_factura, fecha_elaboracion, id_usuario, id_empresa, id_cliente, tm_salida, tm_llegada, Valor3, estado, tipo_factura) 
				VALUES (NEWID(),'$prefijo','$consecutivo','0','1900-01-01','$fecha','$id_usuario','$id_empresa','$id_cliente','0','0','0','0','1')";
		$resultado = sqlsrv_query($conn,$sql);

		$select = "SELECT * FROM Factura_Venta WHERE Numero_factura='$consecutivo' and prefijo_factura='$prefijo' and estado=0 and tipo_factura=1";
		$resultado = sqlsrv_query($conn,$select);
		return($resultado);
		sqlsrv_close($conn);
	}

	public function buscar_destino()
	{	$año=date("Y");
		global $conn;
	 	$sql = "SELECT DISTINCT tz_MovimientoTransporte.RecepcionadoEn, Destino.idDestino
             FROM tz_MovimientoTransporte INNER JOIN
                  Destino ON tz_MovimientoTransporte.RecepcionadoEn = Destino.Descripcion
             WHERE (tz_MovimientoTransporte.RecepcionadoEn not in ('Patio','DEVOLUCION','DEVOLUCIONES')) 
             AND (YEAR(tz_MovimientoTransporte.FechaRegistro)>='$año'-1)
             order by RecepcionadoEn";
		$resultado = sqlsrv_query($conn,$sql);		
		return($resultado);
		sqlsrv_close($conn);
	}

	public function ingresar_fact($tok,$id_factura_venta)
	{	global $conn;
		//$sql = "UPDATE Registro_tique_cargadores SET Estado='5' WHERE id_registro=$tok";
		$sql = "INSERT INTO factura_venta_detalle (id_factura_venta,numerotransaccion) VALUES('$id_factura_venta',$tok)";
		$resultado = sqlsrv_query($conn,$sql);
		if($resultado)
		{	$var=1;	}
		else
		{	$var=0;	}
		return($var);
		sqlsrv_close($conn);
	}
	
	public function sacar_fact($tok)
	{	global $conn;
		//$sql = "UPDATE Registro_tique_cargadores SET Estado='4' WHERE id_registro=$tok";
		$sql = "DELETE FROM factura_venta_detalle WHERE numerotransaccion=$tok";
		$resultado = sqlsrv_query($conn,$sql);
		if($resultado)
		{	$var=1;	}
		else
		{	$var=0;	}
		
		return($var);
		sqlsrv_close($conn);
	}

	public function asentar_pre_liquid($id_empresa,$id_factura_venta,$fecha_fact_asentada,$tm_despacho,$tm_llegada,$tm_llegada1,$fact_asociada)
	{	global $conn;
		$id_usuario=$_SESSION["usuario"];
		$tm_despacho = strtr($tm_despacho,',','.');
		$tm_llegada = strtr($tm_llegada,',','.');
		$band = 0;
		if($id_empresa=='24B7153E-AB4C-4DB7-81BD-67BC87AF014C')
			$prefijo='MX';
		elseif($id_empresa=='0247FA36-ABF5-4A35-9E8B-BE7C574243DE')
			$prefijo='GL';
		elseif($id_empresa=='EFE16A94-8055-40E1-8A27-0F282D605F35')
			$prefijo='SX';
		elseif($id_empresa=='4A442AA8-6532-4F4F-8CED-51A6999DDB5E')
			$prefijo='LA';
		elseif($id_empresa=='8B263A3E-316A-443F-874C-7300A01585E3')
			$prefijo='LM';
		elseif($id_empresa=='B34A99EE-662D-4A40-B355-D2C84F615E91')
			$prefijo='LT';

		$tm_llegada = number_format($tm_llegada,0,'','');
		$tm_llegada1 = number_format($tm_llegada1,0,'','');

		$select = "SELECT MAX(fecha_factura) as fecha_max, MAX(numero_factura) as factura_max FROM Factura_Venta
					 WHERE id_empresa='$id_empresa' AND estado='1' and tipo_factura=1";
		$params = array();
   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
   	$resultado=sqlsrv_query($conn,utf8_decode($select),$params,$options);
   	$d=sqlsrv_num_rows($resultado);
   	if($d > 0){
   		while ($max =  sqlsrv_fetch_array($resultado)) {
   			$fact_anterior = $max['factura_max'];
   			if($max['factura_max'] <> NULL){
   				$consecutivo = $max['factura_max']+1;
   				$fecha_max = date_format($max['fecha_max'],'Y-m-d');
   			}else{
   				$consecutivo = 1;
   			}
   		}
   	}else{
   		$consecutivo = 1;
   	}
   	if($d > 0){
   		if($fact_anterior<> NULL){
		   	if($fecha_max <= $fecha_fact_asentada){
		   		$sql1 = "UPDATE Factura_Venta SET prefijo_factura='$prefijo', numero_factura='$consecutivo', 
		   		factura_asociada='$fact_asociada',fecha_factura='$fecha_fact_asentada', id_usuario='$id_usuario', 
		   		tm_salida='$tm_despacho', tm_llegada='$tm_llegada', Valor3='$tm_llegada1', estado='1'
		   		WHERE id_factura_venta='$id_factura_venta'";
		   		$res = sqlsrv_query($conn,$sql1);
		   		if($res){
		   			$band = 1;
		   		}
		   	}else{
		   		$band = 2;
		   	}
		}else{
		   	$sql = "UPDATE Factura_Venta SET prefijo_factura='$prefijo', numero_factura='$consecutivo', factura_asociada='$fact_asociada',
		   		fecha_factura='$fecha_fact_asentada', id_usuario='$id_usuario', tm_salida='$tm_despacho', 
		   		tm_llegada='$tm_llegada', Valor3='$tm_llegada1', estado='1'
		   		WHERE id_factura_venta='$id_factura_venta'";
	   		$res = sqlsrv_query($conn,$sql);
	   		if($res){
	   			$band = 1;
	   		}
		}
	   }else{
	   	$sql = "UPDATE Factura_Venta SET prefijo_factura='$prefijo', numero_factura='$consecutivo', factura_asociada='$fact_asociada',
	   		fecha_factura='$fecha_fact_asentada', id_usuario='$id_usuario', tm_salida='$tm_despacho', tm_llegada='$tm_llegada', Valor3='$tm_llegada1', estado='1'
	   		WHERE id_factura_venta='$id_factura_venta'";
   		$res = sqlsrv_query($conn,$sql);
   		if($res){
   			$band = 1;
   		}
	   }
	   $string = $band."||".
	   			$prefijo."-".$consecutivo;
		return($string);
		sqlsrv_close($conn);
	}

	public function buscar_viajes($id_empresa,$id_cliente,$fecha_ini,$fecha_fin)
	{	global $conn;
		$sql="SELECT  Registro_tique_cargadores.id_registro, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
			Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.cod_reporte, Usuarios.NombreUsuarioLargo, 
			Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.fecha_fin_jornada
		FROM Registro_tique_cargadores INNER JOIN
        	Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
        	Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
        	Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
		WHERE Registro_tique_cargadores.id_registro not in (SELECT numerotransaccion FROM factura_venta_detalle) AND Registro_tique_cargadores.estado='3' AND Registro_tique_cargadores.id_proveedor='$id_empresa' AND 
		Equipos.idPropietario='$id_cliente' AND CAST(Registro_tique_cargadores.fecha_apertura_tique as DATE) 
		BETWEEN '$fecha_ini' AND '$fecha_fin'
		ORDER BY Registro_tique_cargadores.cod_reporte DESC";
		/*$sql = "SELECT * FROM movimiento 
			WHERE idEmpresa='$id_empresa' AND idCliente='$id_cliente' AND idDestino='$id_destino' AND 
			CAST(FechaRegistro AS date)BETWEEN '$fecha_ini' AND '$fecha_fin'";*/
		$resultado = sqlsrv_query($conn,utf8_decode($sql));
		return($resultado);
		sqlsrv_close($conn);
	}

	public function buscar_pre_liquid($pre_fact)
	{	global $conn;
		$select = "SELECT * FROM Factura_Venta WHERE id_factura_venta='$pre_fact'";
		$resultado = sqlsrv_query($conn,$select);
		return($resultado); ///busca la pre-liquidacion

		sqlsrv_close($conn);
	}
	
}
?>
