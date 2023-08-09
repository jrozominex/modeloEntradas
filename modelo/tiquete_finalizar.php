<?php
include('conexion.php');
$id_registro = $_POST['registro'];

$sql_consulta = "SELECT * FROM Registro_tique_cargadores WHERE id_registro='$id_registro'";
$res = sqlsrv_query($conn,$sql_consulta);
while($tiquete = sqlsrv_fetch_array($res)){
	$horas_trabajadas = $tiquete['horas_trabajadas'];
	$maquina=$tiquete['id_maquinaria'];
}


$sql = "SELECT * FROM Equipos WHERE idEquipo='$maquina'";
$resultado = sqlsrv_query($conn,$sql);
while ($equipo = sqlsrv_fetch_array($resultado)) {
	$horometro_maquina = $equipo['horometro_final'];
	$vida_util = $equipo['horometro_vida_util'];
	$horometro_mantto = $equipo['horometro_mantto'];
}
//$fecha_fin = $_POST['fecha_fin'];
//$jornada_final = $_POST['jornada_final'];
//$fecha_jor_fin = $fecha_fin." ".$jornada_final;
$count = 0;
$fecha = date('Y-m-d H:i:s');
if ($_POST['band'] == 1){
	$total_horas = 0;
	$sql_consulta = "SELECT * FROM horometro WHERE id_registro='$id_registro'";
	$res = sqlsrv_query($conn,$sql_consulta);
	while ($horometro = sqlsrv_fetch_array($res) ) {
		$total_horas += $horometro['total_horas'];
		if ($horometro['horometro_final'] == 0){
			$count++;
		}
	}
	if ($count == 0){
		if($total_horas != 0){
			$sql_update = "UPDATE Registro_tique_cargadores SET fecha_fin_jornada='$fecha', horas_trabajadas='$total_horas',  estado='2', horometro_fin='$horometro_maquina' WHERE id_registro='$id_registro'";
			$result = sqlsrv_query($conn,$sql_update);
			if ($result){
				echo 1;
			}
		}else{
			echo 2;
		}
	}else{
		echo 3;
	}
}elseif ($_POST['band'] == 2){
	$remision = $_POST['remision'];
	$sql_select = "SELECT horas_trabajadas FROM Registro_tique_cargadores WHERE id_Registro='$id_registro'";
	$res = sqlsrv_query($conn,$sql_select);
	while($ticket = sqlsrv_fetch_array($res)){
		$horas = $ticket['horas_trabajadas'];
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$sql = "SELECT sum(total_horas) as total_horas FROM horometro where id_registro='$id_registro' AND horometro_inicial is null AND horometro_final is null and tipo_tarifa=1";
	$res = sqlsrv_query($conn,$sql);
	while($a1 = sqlsrv_fetch_array($res)){
		$var_1 = $a1['total_horas'];
	}
	$sql_2 = "SELECT sum(tiempohorometro) as tiempohorometro FROM tiempos_cargadores_actividad WHERE idRegistro='$id_registro' and tipo_tarifa=1";
	$res2 = sqlsrv_query($conn,$sql_2);
	while($a2 = sqlsrv_fetch_array($res2)){
		$var_2 = $a2['tiempohorometro'];
	}
	if($var_1 == $var_2){
		$tempo_cargue_tm = $var_1;
	}else{
		$tempo_cargue_tm = 0;
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$sql = "SELECT valor_descuento AS Descuento FROM horometro_descuento_cargadores WHERE idRegistro='$id_registro'";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows=sqlsrv_num_rows($res);
    if ($rows > 0) 
	{	while($val = sqlsrv_fetch_array($res)){
			$t = $val['Descuento']; //TIEMPO DE DESCUENTOS
		}
	}else{
		$t = 0;
	}
	$descuento = $horas-($t+$tempo_cargue_tm);

	$sql_update = "UPDATE Registro_tique_cargadores SET fecha_cierre_tique='$fecha', horas_trabajadas='$descuento', estado='3', remision='$remision' WHERE id_registro='$id_registro'";
	$result = sqlsrv_query($conn,$sql_update);
	if ($result){
		echo 1;
	}
}elseif($_POST['band'] == 3){
	$sql_delete = "DELETE FROM Registro_tique_cargadores WHERE id_registro='$id_registro'";
	$res = sqlsrv_query($conn,$sql_delete);
	if($res){
		echo 1;
	}
}
?>