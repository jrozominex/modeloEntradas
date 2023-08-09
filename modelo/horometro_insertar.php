<?php
/**************************************************************************/
				/**********TABLA DE RESULTADOS**********/
//ECHO 1 = REGISTRO EXITOSO
//ECHO 3 = HAY ACTIVIDADES SIN FINALIZAR
//ECHO 4 = EL HOROMETRO DE LA ACTIVIDAD ES MENOR AL HOROMETRO DE LA MAQUINA
/**************************************************************************/

include('conexion.php');
$registro = $_POST['registro'];
$sql_consulta = "SELECT * FROM Registro_tique_cargadores WHERE id_registro='$registro'";
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

if ($_POST['band'] == 1){
	$LATAM = 0;
	$fecha = $_POST['fecha_horaIni'];
	$count = 0;
	$val = 0;
	$totalize = 0;
	$hora_inicial = $_POST['hora_inicial'];
	$actividad = utf8_encode($_POST['actividad']);
	$sub_actividad = $_POST['sub_actividad'];
	//if ($hora_inicial == $horometro_maquina){
		$sql_horo = "SELECT * FROM horometro";
		$res_horo = sqlsrv_query($conn,$sql_horo);
		while($horo = sqlsrv_fetch_array($res_horo)){
			$horo['id_horometro'].";";
			if ($horo['horometro_final'] == 0 && $horo['total_horas'] == 0 && $horo['fecha_cierre_horometro'] = ''){
				$sql = "SELECT * FROM Registro_tique_cargadores WHERE id_registro='".$horo['id_registro']."'";
				$res = sqlsrv_query($conn,$sql);
				while($act = sqlsrv_fetch_array($res)){
					if ($act['id_maquinaria'] == $maquina){
						$count++;
					}
				}
			}
		}
		if ($count == 0){
			//if($horas_trabajadas < 12){
				$sql_insert = "INSERT INTO horometro (id_horometro, id_registro, horometro_inicial, horometro_final, total_horas, idActividad, idSubActividad, fecha_registro_horometro)
				VALUES (NEWID(),'$registro','$hora_inicial','0','0','$actividad','$sub_actividad','$fecha')";
				$result = sqlsrv_query($conn,$sql_insert);
				if ($result){
					$sql_update = "UPDATE Equipos SET horometro_final='$hora_inicial' WHERE idEquipo='$maquina'";
					$res_update = sqlsrv_query($conn,$sql_update);
					if ($res_update){
						$val = 1;
						$sql = "SELECT Equipos.horometro_final, Equipos.horometro_mantto
						FROM Equipos INNER JOIN proveedores ON Equipos.idPropietario=proveedores.idProveedor
						WHERE Equipos.idPropietario ='4A442AA8-6532-4F4F-8CED-51A6999DDB5E' AND Equipos.idEquipo='$maquina'";
						//$res = sqlsrv_query($conn,$sql);
						$params = array();
			            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			            $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
			            $rows=sqlsrv_num_rows($res);
			            if ($rows > 0) 
			            {	while($detail = sqlsrv_fetch_array($res)){
								$LATAM++;
								$horometro_final = $detail['horometro_final'];
								$horometro_mantto = $detail['horometro_mantto'];
							}
							$totalize = $horometro_mantto-$horometro_final;
						}
					}
				}
			/*}else{
				$val = 2;
			}*/
		}else {
			$val = 3;
		}
	/*}else{
		$val = 4;
	}*/
	$mostrar = $val."||".
			   $totalize."||".
			   $LATAM;
	echo $mostrar;
}else if ($_POST['band'] == 2){
	$LATAM = 0;
	$fecha = $_POST['fecha_horaIni'];
	$fecha_cierre_horometro = $_POST['fecha_cierre'];
	$horometro = $_POST['horometro'];
	$hora_inicial = $_POST['hora_inicial'];
	$t = $_POST['total'];
	$val = $hora_inicial+$t;
	$band = 0;
	$totalize = 0;
	$hora_final = $_POST['hora_final'];
	if ($hora_final > $horometro_maquina){
		$total_horas = number_format($hora_final-$hora_inicial,1);
		//if($total_horas <= 12){
			$total = $horas_trabajadas+$total_horas;
			//if($total <= 12){
				$sql_insert = "UPDATE horometro SET horometro_final='$hora_final', total_horas='$total_horas', fecha_cierre_horometro='$fecha_cierre_horometro' WHERE id_horometro='$horometro'";
				$result = sqlsrv_query($conn,$sql_insert);
				if ($result){
					$i = $vida_util+$total_horas;
					$sql_update = "UPDATE Equipos SET horometro_final='$hora_final', horometro_vida_util='$i' WHERE idEquipo='$maquina'";
					$res1_update = sqlsrv_query($conn,$sql_update);
					$update = "UPDATE Registro_tique_cargadores SET horas_trabajadas='$total' WHERE id_registro='$registro'";
					$res_update = sqlsrv_query($conn,$update);
					if ($res_update){
						$band = 1;
						$sql = "SELECT Equipos.horometro_final, Equipos.horometro_mantto
						FROM Equipos INNER JOIN proveedores ON Equipos.idPropietario=proveedores.idProveedor
						WHERE Equipos.idPropietario ='4A442AA8-6532-4F4F-8CED-51A6999DDB5E' AND Equipos.idEquipo='$maquina'";
						//$res = sqlsrv_query($conn,$sql);
						$params = array();
			            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			            $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
			            $rows=sqlsrv_num_rows($res);
			            if ($rows > 0) 
			            {	while($detail = sqlsrv_fetch_array($res)){
			            		$LATAM++;
								$horometro_final = $detail['horometro_final'];
								$horometro_mantto = $detail['horometro_mantto'];
							}
							$totalize = $horometro_mantto-$horometro_final;
						}
					}
				}
			/*}else{
				$band = 6;
			}*/
		/*}else{
			$band = 5;
		}*/
	}else{
		$band = 4;
	}
	$mostrar = $band."||".
			   $totalize."||".
			   $LATAM;
	echo $mostrar;
}else if ($_POST['band'] == 3){
	/*$sql = "DELETE FROM Mantenimiento_equipos";
	$res = sqlsrv_query($conn,$sql);*/
	$id_mantto = $_POST['id_mantto'];
	$fechaMtto = $_POST['fechaMtto'];
	$horo_iniciaMtto = $_POST['horo_iniciaMtto'];
	$horo_finaMtto = $_POST['horo_finaMtto'];
	$idMaquinaria = $_POST['idMaquinaria'];

	//if ($horo_finaMtto >= $horometro_maquina){
		$i = 0;
		$mantenimientos = Array();

		$sql = "SELECT * FROM Mantenimiento_equipos WHERE idEquipo='$idMaquinaria'";
		$params = array();
	    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	    $result=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	    $rows=sqlsrv_num_rows($result);
	    if ($rows > 0) 
		{	while($rows = sqlsrv_fetch_array($result)){
				if($rows['total_horas'] == 0){
					$mantenimientos[$i] = "'".$rows['idxid']."'";
					$i++;
				}
			}
			$a = implode(",", $mantenimientos);
			$total_horas = $horo_finaMtto-$horo_iniciaMtto;
			$total_horas = number_format($total_horas,2,',','.');
			$sql_insert = "UPDATE Mantenimiento_equipos SET horo_mtto_final='$horo_finaMtto', total_horas='$total_horas', fecha_final_mtto='$fechaMtto' WHERE idxid IN ($a)";
			$result = sqlsrv_query($conn,$sql_insert);
			if ($result){
				$i = $vida_util+$horo_finaMtto;
				$sql_update = "UPDATE Equipos SET horometro_final='$horo_finaMtto', horometro_vida_util='$i' WHERE idEquipo='$maquina'";
				$res1_update = sqlsrv_query($conn,$sql_update);
				if ($res1_update){
					echo 1;
				}
			}
		}
	/*}else{
		echo 4;
	}*/
}elseif ($_POST['band'] == 4){
	if ($horometro_maquina == $horometro_mantto){
		echo 0;
	}else{
		$resta = $horometro_mantto - $horometro_maquina;
		echo $resta;
	}
}else if ($_POST['band'] == 5){
	$LATAM = 0;
	$fecha = $_POST['fecha_horaIni'];
	$fecha_cierre_horometro = $_POST['fecha_cierre'];
	$horometro = $_POST['horometro'];
	$hora_inicial = $_POST['hora_inicial'];
	$t = $_POST['total'];
	$val = $hora_inicial+$t;
	$hora_final = $_POST['hora_final'];
	$band = 0;
	$totalize = 0;
	$observaciones = utf8_decode($_POST['observaciones']);
	if ($hora_final > $horometro_maquina){
		$total_horas = $hora_final-$hora_inicial;
		if($total_horas <= 12){
			$total = $horas_trabajadas+$total_horas;
			if($total <= 12){
				$sql_insert = "UPDATE horometro SET horometro_final='$hora_final', total_horas='$total_horas', fecha_cierre_horometro='$fecha_cierre_horometro', observaciones='$observaciones' WHERE id_horometro='$horometro'";
				$result = sqlsrv_query($conn,$sql_insert);
				if ($result){
					$i = $vida_util+$total_horas;
					$sql_update = "UPDATE Equipos SET horometro_final='$hora_final', horometro_vida_util='$i' WHERE idEquipo='$maquina'";
					$res1_update = sqlsrv_query($conn,$sql_update);
					$update = "UPDATE Registro_tique_cargadores SET horas_trabajadas='$total' WHERE id_registro='$registro'";
					$res_update = sqlsrv_query($conn,$update);
					if ($res_update){
						$band = 1;
						$sql = "SELECT Equipos.horometro_final, Equipos.horometro_mantto
						FROM Equipos INNER JOIN proveedores ON Equipos.idPropietario=proveedores.idProveedor
						WHERE Equipos.idPropietario ='4A442AA8-6532-4F4F-8CED-51A6999DDB5E' AND Equipos.idEquipo='$maquina'";
						//$res = sqlsrv_query($conn,$sql);
						$params = array();
			            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			            $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
			            $rows=sqlsrv_num_rows($res);
			            if ($rows > 0) 
			            {	while($detail = sqlsrv_fetch_array($res)){
			            		$LATAM++;
								$horometro_final = $detail['horometro_final'];
								$horometro_mantto = $detail['horometro_mantto'];
							}
							$totalize = $horometro_mantto-$horometro_final;
						}
					}
				}
			}else{
				$band = 6;
			}
		}else{
			$band = 5;
		}
	}else{
		$band = 4;
	}
	$mostrar = $band."||".
			   $totalize."||".
			   $LATAM;
	echo $mostrar;
}else if ($_POST['band'] == 10){
	$LATAM = 0;
	$fecha = $_POST['fecha_horaIni'];
	$count = 0;
	$band = 0;
	$totalize = 0;
	$hora_inicial = $_POST['hora_inicial'];
	//if ($hora_inicial == $horometro_maquina){
		$sql_horo = "SELECT * FROM horometro";
		$res_horo = sqlsrv_query($conn,$sql_horo);
		while($horo = sqlsrv_fetch_array($res_horo)){
			if ($horo['horometro_final'] == 0 && $horo['total_horas'] == 0 && $horo['fecha_cierre_horometro'] == ''){
				$sql = "SELECT * FROM Registro_tique_cargadores WHERE id_registro='".$horo['id_registro']."'";
				$res = sqlsrv_query($conn,$sql);
				while($act = sqlsrv_fetch_array($res)){
					if ($act['id_maquinaria'] == $maquina){
						$count++;
					}
				}
			}
		}
		if ($count == 0){
			if($horas_trabajadas < 12){
				$sql_insert = "INSERT INTO horometro (id_horometro, id_registro, horometro_inicial, horometro_final, total_horas, fecha_registro_horometro)
				VALUES (NEWID(),'$registro','$hora_inicial','0','0','$fecha')";
				$result = sqlsrv_query($conn,$sql_insert);
				if ($result){
					$sql_update = "UPDATE Equipos SET horometro_final='$hora_inicial' WHERE idEquipo='$maquina'";
					$res_update = sqlsrv_query($conn,$sql_update);
					if ($res_update){
						$band = 1;
						$sql = "SELECT Equipos.horometro_final, Equipos.horometro_mantto
						FROM Equipos INNER JOIN proveedores ON Equipos.idPropietario=proveedores.idProveedor
						WHERE Equipos.idPropietario ='4A442AA8-6532-4F4F-8CED-51A6999DDB5E' AND Equipos.idEquipo='$maquina'";
						//$res = sqlsrv_query($conn,$sql);
						$params = array();
			            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			            $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
			            $rows=sqlsrv_num_rows($res);
			            if ($rows > 0) 
			            {	while($detail = sqlsrv_fetch_array($res)){
								$LATAM++;
								$horometro_final = $detail['horometro_final'];
								$horometro_mantto = $detail['horometro_mantto'];
							}
							if($LATAM != 0){
								$totalize = $horometro_mantto-$horometro_final;
							}
						}
					}
				}
			}else{
				$band = 2;
			}
		}else {
			$band=3;
		}
	/*}else {
		$band=4;
	}*/
	$mostrar = $band."||".
			   $totalize."||".
			   $LATAM;
	echo $mostrar;
}else if ($_POST['band'] == 20){
	/*$sql = "DELETE FROM horometro";
	$res = sqlsrv_query($conn,$sql);
	$sql = "DELETE FROM Registro_tique_cargadores";
	$res = sqlsrv_query($conn,$sql);
	$sql = "DELETE FROM Mantenimiento_equipos";
	$res = sqlsrv_query($conn,$sql);
	*/
	$LATAM = 0;
	$fecha = $_POST['fecha_horaIni'];
	$contar = 0;
	$hora_inicial = $_POST['hora_inicial'];
	$idUsuario = $_POST['idUsuario'];
	$tipoMantto = $_POST["tipoMantto"];
	$tok = explode(',',$tipoMantto); 
	$option = 0;
	$count = count($tok);
	//if ($hora_inicial == $horometro_maquina){
		$sql_horo = "SELECT * FROM horometro";
		$res_horo = sqlsrv_query($conn,$sql_horo);
		while($horo = sqlsrv_fetch_array($res_horo)){
			if ($horo['horometro_final'] == 0 && $horo['total_horas'] == 0 && $horo['fecha_cierre_horometro'] = ''){
				$sql = "SELECT * FROM Registro_tique_cargadores WHERE id_registro='".$horo['id_registro']."'";
				$res = sqlsrv_query($conn,$sql);
				while($act = sqlsrv_fetch_array($res)){
					if ($act['id_maquinaria'] == $maquina){
						$contar++;
					}
				}
			}
		}
		if ($contar == 0){
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
				$sql_update = "UPDATE Equipos SET horometro_final='$hora_inicial' WHERE idEquipo='$maquina'";
				$res_update = sqlsrv_query($conn,$sql_update);
				if ($res_update){
					$val = 1;
					$sql = "SELECT Equipos.horometro_final, Equipos.horometro_mantto
					FROM Equipos INNER JOIN proveedores ON Equipos.idPropietario=proveedores.idProveedor
					WHERE Equipos.idPropietario ='4A442AA8-6532-4F4F-8CED-51A6999DDB5E' AND Equipos.idEquipo='$maquina'";
					//$res = sqlsrv_query($conn,$sql);
					$params = array();
		            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
		            $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
		            $rows=sqlsrv_num_rows($res);
		            if ($rows > 0) 
		            {	while($detail = sqlsrv_fetch_array($res)){
		            		$LATAM++;
							$horometro_final = $detail['horometro_final'];
							$horometro_mantto = $detail['horometro_mantto'];
						}
						$totalize = $horometro_mantto-$horometro_final;
					}
				}
			}
		}else {
			$val = 3;
		}
	/*}else {
		$val = 4;
	}*/
	$mostrar = $val."||".
			   $totalize."||".
			   $LATAM;
	echo $mostrar;
}
?>