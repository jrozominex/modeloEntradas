<?php 
if(isset($_POST['operacion'])){
	$id_empresa = $_POST['empresa'];
	$id_cliente = $_POST['cliente'];
	$fecha_ini = $_POST['fecha_ingreso'];
	$fecha_fin = $_POST['fecha_salida'];
	if($_POST['operacion'] == 1){
		$sql_viajes="SELECT  Registro_tique_cargadores.id_registro, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
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
		$viajes = sqlsrv_query($conn,utf8_decode($sql_viajes));
	}elseif($_POST['operacion'] == 2){
		$fecha= date('Y-m-d H:i:s');
		
		$id_usuario=$_SESSION["usuario"];
		if($id_empresa=='24B7153E-AB4C-4DB7-81BD-67BC87AF014C')
			$prefijo='C-MX';
		elseif($id_empresa=='0247FA36-ABF5-4A35-9E8B-BE7C574243DE')
			$prefijo='C-GL';
		elseif($id_empresa=='EFE16A94-8055-40E1-8A27-0F282D605F35')
			$prefijo='C-SX';
		elseif($id_empresa=='4A442AA8-6532-4F4F-8CED-51A6999DDB5E')
			$prefijo='C-LA';
		elseif($id_empresa=='8B263A3E-316A-443F-874C-7300A01585E3')
			$prefijo='C-LM';
		elseif($id_empresa=='B34A99EE-662D-4A40-B355-D2C84F615E91')
			$prefijo='C-LT';

		$consulta = "SELECT MAX(numero_factura) as Numero_factura FROM Factura_Venta WHERE estado=0 
			AND id_empresa='$id_empresa'";
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

		$sql = "INSERT INTO Factura_Venta (id_factura_venta, prefijo_factura,numero_factura, fecha_factura, fecha_elaboracion, id_usuario, id_empresa, id_cliente, tm_salida, tm_llegada, estado) 
				VALUES (NEWID(),'$prefijo','$consecutivo','1900-01-01','$fecha','$id_usuario','$id_empresa','$id_cliente','0','0','0')";
		$resultado = sqlsrv_query($conn,$sql);

		$select = "SELECT * FROM Factura_Cargador WHERE Numero_factura='$consecutivo' and prefijo_factura='$prefijo' and estado=0";
		$nueva_preFac = sqlsrv_query($conn,$select);
	}
}
?>