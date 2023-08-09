<?php
include('conexion.php');

if($_POST['band'] == 1){
	$operador = $_POST['operador'];
	$fechaRegistro = $_POST['fechaRegistro'];
	$horo_inicial = $_POST['horo_inicial'];
	$maquinaria = $_POST['maquinaria'];
	$patio = $_POST['patio'];
	$reporte_falla = $_POST['reporte_falla'];
	$observaciones = utf8_decode($_POST['observaciones']);
	$sql = "INSERT INTO ReporteFallasMaquinaria (idReporteFalla, FechaRegistro, id_operador, id_maquinaria, id_patio, DescripcionFalla, Observaciones, horo_reporte_falla) 
	VALUES (NEWID(),'$fechaRegistro','$operador','$maquinaria','$patio','$reporte_falla','$observaciones','$horo_inicial')";
	$res = sqlsrv_query($conn,$sql);
	if ($res){
		echo 1;
	}
}elseif($_POST['band'] == 2){
	//$contar = 0;
	$fecha = date('Y-m-d H:i:s');
	$mecanico = $_POST['mecanico'];
	$idReporteFalla = $_POST['idReporteFalla'];
	$horometro_inicial = $_POST['horometro_inicial'];
	//$horometro_final = $_POST['horometro_final'];
	$observaciones = $_POST['observaciones'];
	$sql = "UPDATE ReporteFallasMaquinaria SET HoroReporte_inicial='$horometro_inicial', id_mecanico='$mecanico', Observaciones='$observaciones' WHERE idReporteFalla='$idReporteFalla'";
	$res = sqlsrv_query($conn,$sql);
	if ($res){
		$sql = "SELECT * FROM ReporteFallasMaquinaria WHERE idReporteFalla='$idReporteFalla'";
		$result = sqlsrv_query($conn,$sql);
		while($report = sqlsrv_fetch_array($result)){
			$id_maquinaria = $report['id_maquinaria'];
		}
		$sql_update = "UPDATE detalle_equipos SET horometro_final='$horometro_inicial' WHERE idEquipos='$id_maquinaria'";
		$res_update = sqlsrv_query($conn,$sql_update);
		if ($res_update){
			echo 1;
		}

	}
}elseif($_POST['band'] == 3){
	$contar = 0;
	$fecha = date('Y-m-d H:i:s');
	$jefe_mantto = $_POST['jefe_mantto'];
	$idReporteFalla = $_POST['idReporteFalla'];
	$horometro_inicial = $_POST['horometro_inicial'];
	//$horometro_final = $_POST['horometro_final'];
	$observaciones = utf8_decode($_POST['observaciones']);
	$sql = "UPDATE ReporteFallasMaquinaria SET HoroReporte_inicial='$horometro_inicial', id_jefeMantto='$jefe_mantto', Observaciones='$observaciones' WHERE idReporteFalla='$idReporteFalla'";
	$res = sqlsrv_query($conn,$sql);
	if ($res){
		$sql = "SELECT * FROM ReporteFallasMaquinaria WHERE idReporteFalla='$idReporteFalla'";
		$result = sqlsrv_query($conn,$sql);
		while($report = sqlsrv_fetch_array($result)){
			$id_maquinaria = $report['id_maquinaria'];
		}
		$sql_update = "UPDATE detalle_equipos SET horometro_final='$horometro_inicial' WHERE idEquipos='$id_maquinaria'";
		$res_update = sqlsrv_query($conn,$sql_update);
		if ($res_update){
			echo 1;
		}
	}
}elseif($_POST['band'] == 4){
	$contar = 0;
	$fecha = date('Y-m-d H:i:s');
	$mecanico = $_POST['mecanico'];
	$idReporteFalla = $_POST['idReporteFalla'];
	//$horometro_inicial = $_POST['horometro_inicial'];
	$horometro_final = $_POST['horometro_final'];
	$observaciones = utf8_decode($_POST['observaciones']);

	$sql = "SELECT * FROM ReporteFallasMaquinaria WHERE idReporteFalla='$idReporteFalla'";
	$result = sqlsrv_query($conn,$sql);
	while($report = sqlsrv_fetch_array($result)){
		$id_maquinaria = $report['id_maquinaria'];
		if ($report['id_jefeMantto'] <> null){
			$contar = 1;
		}
	}
	if ($contar == 1){
		$sql = "UPDATE ReporteFallasMaquinaria SET HoroReporte_final='$horometro_final', id_mecanico='$mecanico', Observaciones='$observaciones', FechaCierre='$fecha' WHERE idReporteFalla='$idReporteFalla'";
	}else{
		$sql = "UPDATE ReporteFallasMaquinaria SET HoroReporte_final='$horometro_final', id_mecanico='$mecanico', Observaciones='$observaciones' WHERE idReporteFalla='$idReporteFalla'";
	}
	$res = sqlsrv_query($conn,$sql);
	if ($res){
		$sql_update = "UPDATE detalle_equipos SET horometro_final='$horometro_final' WHERE idEquipos='$id_maquinaria'";
		$res_update = sqlsrv_query($conn,$sql_update);
		if ($res_update){
			echo 1;		
		}

	}
}elseif($_POST['band'] == 5){
	$contar = 0;
	$fecha = date('Y-m-d H:i:s');
	$jefe_mantto = $_POST['jefe_mantto'];
	$idReporteFalla = $_POST['idReporteFalla'];
	//$horometro_inicial = $_POST['horometro_inicial'];
	$horometro_final = $_POST['horometro_final'];
	$observaciones = utf8_decode($_POST['observaciones']);

	$sql = "SELECT * FROM ReporteFallasMaquinaria WHERE idReporteFalla='$idReporteFalla'";
	$result = sqlsrv_query($conn,$sql);
	while($report = sqlsrv_fetch_array($result)){
		$id_maquinaria = $report['id_maquinaria'];
		if ($report['id_mecanico'] <> null){
			$contar = 1;
		}
	}
	if($contar == 1){
		$sql = "UPDATE ReporteFallasMaquinaria SET HoroReporte_final='$horometro_final', id_jefeMantto='$jefe_mantto', Observaciones='$observaciones', FechaCierre='$fecha' WHERE idReporteFalla='$idReporteFalla'";
	}else{
		$sql = "UPDATE ReporteFallasMaquinaria SET HoroReporte_final='$horometro_final', id_jefeMantto='$jefe_mantto', Observaciones='$observaciones' WHERE idReporteFalla='$idReporteFalla'";
	}
	$res = sqlsrv_query($conn,$sql);
	if ($res){
		$sql_update = "UPDATE detalle_equipos SET horometro_final='$horometro_final' WHERE idEquipos='$id_maquinaria'";
		$res_update = sqlsrv_query($conn,$sql_update);
		if ($res_update){
			echo 1;
		}
	}
}
?>