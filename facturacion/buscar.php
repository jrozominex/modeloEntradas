<?php
	include ("../modelo/conexion.php");
if($_POST['band'] == 1){
	$consultaBusqueda = $_POST['valorBusqueda'];
	$seleccionado = $_POST['seleccionado'];
	//Filtro anti-XSS
	$caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
	$caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
	$consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);
	$mensaje = "";
	if (isset($consultaBusqueda)) 
	{	//$consulta = "SELECT idProveedor, RazonSocial,Alias FROM Proveedores WHERE RazonSocial LIKE '%$consultaBusqueda%'";

		$consulta = "SELECT idProveedor, RazonSocial,Alias FROM Proveedores WHERE idProveedor in 
		(SELECT idProveedor FROM  vProveedoresInAgrupacion WHERE Alias='PC') AND RazonSocial LIKE '%$consultaBusqueda%'
		ORDER BY RazonSocial";

		$params = array();
	   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
	   	$resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
	   	$filas=sqlsrv_num_rows($resultado);  
	  // 	return($resultado);
	   	if ($filas == 0) {
			$mensaje = '<option value="0">No se encontró</option>';//"<p>No hay ningún usuario con ese nombre y/o apellido</p>";
		} else {
			//echo 'Resultados para <strong>'.$consultaBusqueda.'</strong>';
			$mensaje .= '<option value="0">Seleccione</option>';
			while($resultados = sqlsrv_fetch_array($resultado)) {
				$nombre = utf8_encode($resultados['RazonSocial']);
				$apellido = $resultados['Alias'];
				$id = $resultados['RazonSocial'];
				//Output
				if($nombre == $seleccionado){
					$mensaje.= '<option value="'.$id.'" selected>'.$nombre.'</option>';
				}else{
					$mensaje.= '<option value="'.$id.'">'.$nombre.'</option>';
				}
			};//Fin while $resultados
		}; //Fin else $filas
	};//Fin isset $consultaBusqueda
	//Devolvemos el mensaje que tomará jQuery
	echo $mensaje;
}elseif($_POST['band'] == 2){
	$empresa = $_POST['empresa'];
	$id_cliente = $_POST['cliente'];
	$seleccionado = $_POST['seleccionado'];
	$mensaje = "";
	if (isset($empresa) && isset($id_cliente)) 
	{ 	/*$consulta = "SELECT idProveedor FROM Proveedores WHERE RazonSocial = '$nombre_cliente'";
		$params = array();
	   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
	   	$resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
	   	$filas=sqlsrv_num_rows($resultado);  
		while($rows = sqlsrv_fetch_array($resultado)) {
			$id_cliente = $rows['idProveedor'];
		}*/
		$consulta = "SELECT * FROM Factura_Venta WHERE id_empresa='$empresa' AND id_cliente='$id_cliente' /*AND
		estado=0*/ and tipo_factura=1";
		$consulta1 = $consulta;
		$params = array();
	   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
	   	$resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
	   	$filas=sqlsrv_num_rows($resultado);  
	  // 	return($resultado);
	   	if ($filas == 0) {
			$mensaje = '<option value="0" selected>No hay pre-facturas</option>';//"<p>No hay ningún usuario con ese nombre y/o apellido</p>";
		} else {
			$valor = 0;
			$res = sqlsrv_query($conn,$consulta1);
			while($resultados = sqlsrv_fetch_array($res)) {
				if($seleccionado == $resultados['id_factura_venta']){
					$valor++;
				}
			}
			//echo 'Resultados para <strong>'.$consultaBusqueda.'</strong>';
			if($seleccionado == 0){
				$mensaje .= '<option value="0" selected>Seleccione</option>';
			}else{
				if($valor == 0){
					$mensaje .= '<option value="0" selected>Seleccione</option>';
				}else{
					$mensaje .= '<option value="0">Seleccione</option>';
				}
			}
			while($resultados = sqlsrv_fetch_array($resultado)) {
				$numerofact = $resultados['prefijo_factura'].' - '.$resultados['numero_factura'];
				//$apellido = $resultados['Alias'];
				$id = $resultados['id_factura_venta'];
				//Output
				if($id == $seleccionado){
					$mensaje.= '<option value="'.$id.'" selected>'.$numerofact.'</option>';
				}else{
					$mensaje.= '<option value="'.$id.'">'.$numerofact.'</option>';
				}
			};//Fin while $resultados
		}; //Fin else $filas
	};//Fin isset $consultaBusqueda
	//Devolvemos el mensaje que tomará jQuery
	echo $mensaje;
	//echo $seleccionado;
}elseif($_POST['band'] == 3){
	$empresa = $_POST['empresa'];
	$destino = $_POST['destino'];
	$fecha_ingreso = $_POST['fecha_ingreso'];
	$fecha_salida = $_POST['fecha_salida'];
	$seleccionado = $_POST['seleccionado'];
	$sql="SELECT distinct (Clasificacion) FROM tz_MovimientoTransporte 
	WHERE RecepcionadoEn='$destino' AND idEmpresa ='$empresa' and Cast(FechaRegistro as Date) between '$fecha_ingreso' and '$fecha_salida'";
	$params = array();
   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
   	$resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
   	$filas=sqlsrv_num_rows($resultado);  
   	if ($filas == 0) {
		$mensaje = '<option value="0">No existe</option>';//"<p>No hay ningún usuario con ese nombre y/o apellido</p>";
	} else {
		//echo 'Resultados para <strong>'.$consultaBusqueda.'</strong>';
		$mensaje .= '<option value="0">Seleccione</option>';
		while($resultados = sqlsrv_fetch_array($resultado)) {
			$nombre = utf8_encode($resultados['Clasificacion']);
			//Output
			if($nombre == $seleccionado){
				$mensaje.= '<option value="'.$nombre.'" selected>'.$nombre.'</option>';
			}else{
				$mensaje.= '<option value="'.$nombre.'">'.$nombre.'</option>';
			}	
		};//Fin while $resultados
	}; //Fin else $filas
	//Devolvemos el mensaje que tomará jQuery
echo $mensaje;
}

?>