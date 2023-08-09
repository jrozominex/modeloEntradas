<?php
include('conexion.php');

if ($_POST['band'] == 1){
	$id_mantto = $_POST['id_mantto'];
	$id_usuario = $_POST['id_usuario'];
	$observaciones = $_POST['observaciones'];
	/**********************************************************/
	$SubActividad = $_POST['SubActividad'];
	$tok_sub = explode(',',$SubActividad); 
	$count_sub = count($tok_sub);
	/*********************************************************/
	$Personal = $_POST['Personal'];
	//$tok_per = explode(',',$Personal); 
	//$count_per = count($tok_per);
	/********************************************************/
	$inserto = 0;
	for ($i = 0; $i<$count_sub; $i++){
		if ($tok_sub[$i] <> '')
		{ 	$sql = "INSERT INTO Mtto_plantilla_actividades (id_mtto_equipo, id_actividad, personal) VALUES ('$id_mantto','$tok_sub[$i]','$Personal')";
			$res = sqlsrv_query($conn,$sql);
			if ($res){
				$update = "UPDATE Mantenimiento_equipos SET realizado_por='$id_usuario', observaciones='$observaciones' WHERE idxid='$id_mantto'";
				$res_up = sqlsrv_query($conn,$update);
				if($res_up){
					$inserto++;
				}
			}
		}
	}
	if ($inserto != 0){
		echo 1;
	}
}elseif($_POST['band'] == 2){
	$id_mantto = $_POST['id_mantto'];
	$id_usuario = $_POST['id_usuario'];
	$observaciones = $_POST['observaciones'];

	$update = "UPDATE Mantenimiento_equipos SET verificado_por='$id_usuario', observaciones='$observaciones' WHERE idxid='$id_mantto'";
	$res_up = sqlsrv_query($conn,$update);
	if($res_up){
		echo 1;
	}
}elseif($_POST['band'] == 3){
	$id_mantto = $_POST['id_mantto'];
	$id_usuario = $_POST['id_usuario'];
	$observaciones = $_POST['observaciones'];

	$update = "UPDATE Mantenimiento_equipos SET aprobado_por='$id_usuario', observaciones='$observaciones' WHERE idxid='$id_mantto'";
	$res_up = sqlsrv_query($conn,$update);
	if($res_up){
		$sql = "SELECT * FROM Mantenimiento_equipos WHERE idxid='$id_mantto'";
		$res = sqlsrv_query($conn,$sql);
		while($mantto = sqlsrv_fetch_array($res)){
			$maquina = $mantto['idEquipo'];
		}
		$up = "SELECT horometro_mantto FROM detalle_equipos WHERE idEquipos='$maquina'";
		$resup = sqlsrv_query($conn,$up);
		while($machine = sqlsrv_fetch_array($resup)){
			$horometro_mantto = $machine['horometro_mantto'];
		}
		$horometro_mantto = $horometro_mantto + 250;
		$update = "UPDATE detalle_equipos SET horometro_mantto='$horometro_mantto' WHERE idEquipos='$maquina'";
		$resup1 = sqlsrv_query($conn,$update);
		if ($resup1){
			echo 1;
		}
	}
}elseif($_POST['band'] == 4){
	$maquina = $_POST['valor_maquina'];
	$contar = 0;
	$sql_horo = "SELECT * FROM horometro";
	$res_horo = sqlsrv_query($conn,$sql_horo);
	while($horo = sqlsrv_fetch_array($res_horo)){
		if ($horo['horometro_final'] == 0 && $horo['total_horas'] == 0){
			$sql = "SELECT * FROM Registro_tique_cargadores WHERE id_registro='".$horo['id_registro']."'";
			$res = sqlsrv_query($conn,$sql);
			while($act = sqlsrv_fetch_array($res)){
				if ($act['id_maquinaria'] == $maquina){
					$contar++;
				}
			}
		}
	}

	$sql= "SELECT * FROM detalle_equipos WHERE idEquipos='$maquina'";
	$result = sqlsrv_query($conn,$sql);
	while ($horometro_maquina = sqlsrv_fetch_array($result)){
		$fin = $horometro_maquina['horometro_final'];
		$vida = $horometro_maquina['horometro_vida_util'];
		$mantto = $horometro_maquina['horometro_mantto'];
	}
	echo $fin."||".
		$vida."||".
		$mantto."||".
		$contar;
}elseif($_POST['band'] == 5){
	$maquina = $_POST['maquina'];
	$sql = "SELECT * FROM detalle_equipos WHERE idEquipos='$maquina'";
	$resultado = sqlsrv_query($conn,$sql);
	while ($equipo = sqlsrv_fetch_array($resultado)) {
		$horometro_maquina = $equipo['horometro_final'];
		$vida_util = $equipo['horometro_vida_util'];
		$horometro_mantto = $equipo['horometro_mantto'];
	}
	if ($vida_util == $horometro_mantto){
		echo 0;
	}else{
		$resta = $horometro_mantto - $vida_util;
		echo $resta;
	}
}elseif($_POST['band'] == 6){
	$maquina = $_POST['maquina'];
	$fecha = $_POST['fecha_horaIni'];
	//$contar = 0;
	$hora_inicial = $_POST['horo_inicial'];
	$idUsuario = $_POST['idUsuario'];
	$tipoMantto = $_POST["tipoMantto"];
	$tok = explode(',',$tipoMantto); 
	$option = 0;
	$count = count($tok);

	for ($i = 0; $i<$count; $i++){
		if ($tok[$i] <> '')
		{ 	$sql_insert = "INSERT INTO Mantenimiento_equipos (idxid, idEquipo, horo_mtto_inicial, horo_mtto_final, total_horas, fecha_inicial_mtto,id_usuario, tipo_mtto)
							VALUES (NEWID(),'$maquina','$hora_inicial','0','0','$fecha','$idUsuario','$tok[$i]')";
			$result = sqlsrv_query($conn,$sql_insert);	
			if ($result){
				$option = 1;
			}		
		}
	}
	if ($option == 1){
		$sql_update = "UPDATE detalle_equipos SET horometro_final='$hora_inicial' WHERE idEquipos='$maquina'";
		$res_update = sqlsrv_query($conn,$sql_update);
		if ($res_update){
			echo 1;
		}
	}
}else if ($_POST['band'] == 7){
	/*$sql = "DELETE FROM Mantenimiento_equipos";
	$res = sqlsrv_query($conn,$sql);*/
	$id_mantto = $_POST['id_mantto'];
	$fechaMtto = $_POST['fechaMtto'];
	$horo_iniciaMtto = $_POST['horo_iniciaMtto'];
	$horo_finaMtto = $_POST['horo_finaMtto'];
	$idMaquinaria = $_POST['idMaquinaria'];

	$sql = "SELECT * FROM detalle_equipos WHERE idEquipos='$idMaquinaria'";
	$resultado = sqlsrv_query($conn,$sql);
	while ($equipo = sqlsrv_fetch_array($resultado)) {
		$horometro_maquina = $equipo['horometro_final'];
		$vida_util = $equipo['horometro_vida_util'];
		$horometro_mantto = $equipo['horometro_mantto'];
	}

	//if ($horo_finaMtto > $horometro_maquina){
		$i = 0;
		$total_horas = $horo_finaMtto-$horo_iniciaMtto;
		$total_horas = number_format($total_horas,2,',','.');

		$sql_insert = "UPDATE Mantenimiento_equipos SET horo_mtto_final='$horo_finaMtto', total_horas='$total_horas', fecha_final_mtto='$fechaMtto' WHERE idxid ='$id_mantto'";
		$result = sqlsrv_query($conn,$sql_insert);
		if ($result){
			$i = $vida_util+$total_horas;
			$sql_update = "UPDATE detalle_equipos SET horometro_final='$horo_finaMtto', horometro_vida_util='$i' WHERE idEquipos='$idMaquinaria'";
			$res1_update = sqlsrv_query($conn,$sql_update);
			if ($res1_update){
				echo 1;
			}
		}
	/*}else{
		echo 4;
	}*/
}elseif ($_POST['band'] == 8){
	$id_mantto = $_POST['id_mantto'];
	$id_usuario = $_POST['id_usuario'];
	$observaciones = $_POST['observaciones'];
	/**********************************************************/
	$SubActividad = $_POST['SubActividad'];
	$tok_sub = explode(',',$SubActividad); 
	$count_sub = count($tok_sub);
	/*********************************************************/
	$Personal = $_POST['Personal'];
	//$tok_per = explode(',',$Personal); 
	//$count_per = count($tok_per);
	/********************************************************/
	$inserto = 0;
	for ($i = 0; $i<$count_sub; $i++){ 
		if ($tok_sub[$i] <> '')
		{ 	$sql = "INSERT INTO Mtto_plantilla_actividades (id_mtto_equipo, id_actividad, personal) VALUES ('$id_mantto','$tok_sub[$i]','$Personal')";
			$res = sqlsrv_query($conn,$sql);
			if ($res){
				$update = "UPDATE Mantenimiento_equipos SET aprobado_por='$id_usuario', observaciones='$observaciones' WHERE idxid='$id_mantto'";
				$res_up = sqlsrv_query($conn,$update);
				if($res_up){
					$inserto++;
				}
			}
		}
	}
	if ($inserto != 0){
		echo 1;
	}
}