<?php 
include('conexion.php');

$fecha = date('Y-m-d H:i:s');
if($_POST['band'] == 1){
	$idRegistro = $_POST['idRegistro'];
	$jsonstring = "";
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//$sql = "UPDATE horometro SET total_horas='2.9' WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
	//$res = sqlsrv_query($conn,$sql);
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$sql_select = "SELECT * FROM Registro_tique_cargadores WHERE id_Registro='$idRegistro'";
	$res = sqlsrv_query($conn,$sql_select);
	while($ticket = sqlsrv_fetch_array($res)){
		$observaciones = $ticket['observaciones'];
		$patio = $ticket['id_patio'];
	}
	$sql = "SELECT * FROM horometro WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows=sqlsrv_num_rows($res);
    if($rows > 0)
    {	$total = 0;
		while($row = sqlsrv_fetch_array($res)){
			$total += $row['total_horas'];
		}
		$total = number_format($total,1);
		//echo 'horas: '.$total.'<br>';
		if($total != "0"){
			$sql = "SELECT SUM(total_horas) AS TOTAL FROM horometro WHERE id_registro='$idRegistro' AND horometro_inicial is null AND horometro_final is null";
			$params = array();
		    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
		    $result=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
		    $rows=sqlsrv_num_rows($result);
		    if($rows > 0)
			{ 	while($row = sqlsrv_fetch_array($result)){
					$total1 = $row['TOTAL'];
				}
				$total1 = number_format($total1,1);
				//echo 'utiliza: '.$total1.'<br>';
				$sql = "SELECT valor_descuento AS Descuento FROM horometro_descuento_cargadores WHERE idRegistro='$idRegistro'";
				$params = array();
			    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
			    $rows=sqlsrv_num_rows($res);
			    if ($rows > 0) 
				{	while($val = sqlsrv_fetch_array($res)){
						$t = $val['Descuento']; // TIEMPO DE DESCUENTOS
					}
					$totalizado = $total1 + $t;
				}else{
					$totalizado = $total1;
				}
				$totalizado = number_format($totalizado,1);
				//echo 'utiliza + desc: '.$totalizado.'<br>';
				$total_resta = $total-$totalizado;
				$total_resta = number_format($total_resta,1);
				//echo '<br>'.$total_resta;
				if($total_resta == '0.0'){
					/*$sql = "UPDATE horometro SET total_horas='0' WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
					$res = sqlsrv_query($conn,$sql);
					if($res){*/
						$texto = "Ya se asignaron todos los tiempos";
						$val = 2;/*
					}else{
						$texto = "No se pudo";
						$val = 2;
					}*/
				}else{
					$texto = "Faltan ".$total_resta." horas por asignar";
					$val = 1;
					//echo "Faltan ".$total_resta." horas por asignar";
				}
			}else{
				$texto = "Faltan ".$total." horas por asignar";
				$val = 1;
			}
		}else{
			$texto = "Ya se asignaron todos los tiempos";
			$val = 2;
		}
	}else{
		$texto = "No hay tiempos para asignar";
		$val = 3;
	}
	$sql = "SELECT DestinoZonas.Zona, DestinoZonas.idZona FROM DestinoZonas WHERE DestinoZonas.idDestino = '$patio' 
			ORDER BY DestinoZonas.Zona";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows2=sqlsrv_num_rows($res);
    if ($rows2 > 0) 
	{ 	$json = array();
		while($row = sqlsrv_fetch_array($res)){
			$json[] = array(
			    'idZona' => $row['idZona'],
			    'Zona' => utf8_encode($row['Zona'])
			);
		}
		$jsonstring = json_encode($json);
		//echo $jsonstring;
	}
	$botones='';
	$sql = "SELECT Actividades.idActividad, Actividades.Descripcion
		FROM Actividades_cargadores_destinos INNER JOIN
			Actividades ON Actividades_cargadores_destinos.idActividad = Actividades.idActividad
		WHERE Actividades_cargadores_destinos.idDestino='$patio' AND Actividades_cargadores_destinos.Estado=1
			AND Actividades.Descripcion not in('STANDBY')";
	//$sql = "SELECT * FROM Actividades WHERE idTipoActividad='00000000-0000-0000-0000-000000000001' AND Descripcion not in('STANDBY')";
    $resul = sqlsrv_query($conn,$sql);
    while ($actividad = sqlsrv_fetch_array($resul)){
    	$idActividad = $actividad['idActividad'];
    	$descripcion = utf8_encode($actividad['Descripcion']);
    	$botones.='<div class="col-xs-6  col-sm-4  col-md-4  col-lg-4  col-xl-4" style="margin-bottom: 15px;">
    	<center><button type="button" id="'.$idActividad.'" class="btn btn-default" value="'.$idActividad.'" onclick="actividad('.chr(34).$idActividad.chr(34).')">'.$descripcion.'</button>
            </center>
        </div>';
    }

	$datos =$texto."||". 
			$val."||".
			utf8_encode($observaciones)."||".
			$patio."||".
			$jsonstring."||".
			$botones;
	echo $datos;
}elseif ($_POST['band'] == 2){
	$idRegistro = $_POST['idRegistro'];
	$idActividad = $_POST['actividad'];
	$i = 1;
	$tm_apilar_room = 0;
	$tm_alimentar_room = 0;
	$tm_apilar_sobretamaño = 0;
	$tm_alimentar_sobretamaño = 0;
	$tm_apilar_molienda = 0;
	$tm_alimentar_molienda = 0;
	$sql = "SELECT * FROM horometro WHERE id_registro='$idRegistro' AND idActividad='$idActividad'"; //DESPACHO
	$params = array();
    $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
    $result=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows=sqlsrv_num_rows($result);
    $i = 0;
    if ($rows > 0) 
	{	$sub_actividad = Array();
		$out = 0;
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$sql_tempos = "SELECT * FROM tiempos_cargadores_actividad 
		WHERE idRegistro ='$idRegistro' 
		AND idSubactividad in ('77DE71D6-2FE4-46C3-8615-9B1C3D8F94D0','B6E8C730-CCB4-45E5-80A8-4DA9EF894B56','36D45DE3-9E29-47C4-9B18-960FF8C485CE','DCEB2375-E476-45F6-9D59-FF9C0B49A5B5','5A6F9DFF-4AC5-47D6-B3DA-6FEE1308DD7D','326D6364-121D-41B7-A188-9EE90B1E5F8B')";
		$params = array();
    	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
    	$res=sqlsrv_query($conn,utf8_decode($sql_tempos),$params,$options);
    	$ro=sqlsrv_num_rows($res);
    	if($ro > 0){
    		while($tm = sqlsrv_fetch_array($res)){
    			//////////////////////////////// CLASIFICAR ROOM ////////////////////////////////////////
    			if($tm['idSubActividad'] == '77DE71D6-2FE4-46C3-8615-9B1C3D8F94D0' || $tm['idSubActividad'] == 'B6E8C730-CCB4-45E5-80A8-4DA9EF894B56'){
    				//////////////////////////////// ALIMENTAR ////////////////////////////////////
    				//echo 'room:';
    				if($tm['idSubActividad'] == '77DE71D6-2FE4-46C3-8615-9B1C3D8F94D0'){
    					$tm_alimentar_room+=$tm['TM_total'];
    					//echo 'alimentar;';
    				//////////////////////////////// APILAR //////////////////////////////////////
    				}else{
    					//echo 'apilar;';
    					$tm_apilar_room+=$tm['TM_total'];
    				}
    			//////////////////////////////// CLASIFICAR SOBRETAMAÑO ////////////////////////////////////////
    			}elseif($tm['idSubActividad'] == '5A6F9DFF-4AC5-47D6-B3DA-6FEE1308DD7D' || $tm['idSubActividad'] == '326D6364-121D-41B7-A188-9EE90B1E5F8B') {
    				//////////////////////////////// ALIMENTAR ////////////////////////////////////
    				if($tm['idSubActividad'] == '5A6F9DFF-4AC5-47D6-B3DA-6FEE1308DD7D'){
    					$tm_alimentar_sobretamaño+=$tm['TM_total'];
    				//////////////////////////////// APILAR //////////////////////////////////////
    				}else{
    					$tm_apilar_sobretamaño+=$tm['TM_total'];
    				}
    			//////////////////////////////// MOLIENDA ////////////////////////////////////////
    			}elseif($tm['idSubActividad'] == 'DCEB2375-E476-45F6-9D59-FF9C0B49A5B5' || $tm['idSubActividad'] == '36D45DE3-9E29-47C4-9B18-960FF8C485CE'){
    				//////////////////////////////// ALIMENTAR ////////////////////////////////////
    				if($tm['idSubActividad'] == 'DCEB2375-E476-45F6-9D59-FF9C0B49A5B5'){
    					$tm_alimentar_molienda+=$tm['TM_total'];
    				//////////////////////////////// APILAR //////////////////////////////////////
    				}else{
    					$tm_apilar_molienda+=$tm['TM_total'];
    				}
    			}
    		}
    	}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		while($rows = sqlsrv_fetch_array($result)){
			if ($rows['idSubActividad'] == '9764605C-3098-48AB-8EA4-1248D504FA05'){
				$out++; 	
			}
			$sub_actividad[$i] = "'".$rows['idSubActividad']."'";
			$i++;
		}
	    if ($out == 0)
		{	$a = implode(",", $sub_actividad);
			$sql = "SELECT * FROM subactividades_cargadores WHERE idActividad='$idActividad' AND Descripcion IN ('APILAR', 'ALIMENTAR', 'CARGAR DESPACHO', 'STANDBY', 'MVTO. X CALIDAD', 'OFICIOS VARIOS', 'TRABAJOS MCOS.')";
			$params = array();
		    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
		    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
		    $rows2=sqlsrv_num_rows($res);
		    if ($rows2 > 0) 
			{ 	$json = array();
				while($row = sqlsrv_fetch_array($res)){
					$json[] = array(
					    'idSubactividad' => $row['idSubactividad'],
					    'Descripcion' => utf8_encode($row['Descripcion'])
					);
				}
				$jsonstring = json_encode($json);
				//echo $jsonstring;
			}else{
				//echo 0;
				$jsonstring = 0;
			}
		}else{
			$sql = "SELECT * FROM subactividades_cargadores WHERE idActividad='$idActividad' AND Descripcion IN ('APILAR', 'ALIMENTAR')";
			$params = array();
		    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
		    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
		    $rows2=sqlsrv_num_rows($res);
		    if ($rows2 > 0) 
			{ 	$json = array();
				while($row = sqlsrv_fetch_array($res)){
					$json[] = array(
					    'idSubactividad' => $row['idSubactividad'],
					    'Descripcion' => utf8_encode($row['Descripcion'])
					);
				}
				$jsonstring = json_encode($json);
				//echo $jsonstring;
			}else{
				//echo 0;
				$jsonstring = 0;
			}
		}
	}else{
		$sql = "SELECT * FROM subactividades_cargadores WHERE idActividad='$idActividad' AND Descripcion IN ('APILAR', 'ALIMENTAR', 'CARGAR DESPACHO','STANDBY','MVTO. X CALIDAD', 'OFICIOS VARIOS','TRABAJOS MCOS.')";
		$params = array();
	    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	    $rows=sqlsrv_num_rows($res);
	    $i = 0;
	    if ($rows > 0) 
		{	$json = array();
			while($row = sqlsrv_fetch_array($res)){
				$json[] = array(
				    'idSubactividad' => $row['idSubactividad'],
				    'Descripcion' => utf8_encode($row['Descripcion'])
				);
			}
			$jsonstring = json_encode($json);
			//echo $jsonstring;
		}else{
			//echo 0;
			$jsonstring = 0;
		}
	}
	//////////////////////////////// CLASIFICAR ROOM ////////////////////////////////////////
	if($idActividad == '404D205C-7D03-4E84-9AC1-03EE5A8D83F9'){
		$salida = $tm_alimentar_room."||".
			  	  $tm_apilar_room;
	//////////////////////////////// CLASIFICAR SOBRETAMAÑO ////////////////////////////////
	}elseif($idActividad == '6A173460-B5ED-4CB6-B73A-ECDDF5BE3734'){
		$salida = $tm_alimentar_sobretamaño."||".
			  	  $tm_apilar_sobretamaño;
	//////////////////////////////// MOLIENDA /////////////////////////////////////////////
	}elseif($idActividad == '0FFB9BAF-3DB6-47B2-8E7F-272D2C118AD0'){
		$salida = $tm_alimentar_molienda."||".
			  	  $tm_apilar_molienda;
	}else{
		$salida = $tm_alimentar_room."||".
			  	  $tm_apilar_room;
	}
	/*
	echo $tm_alimentar_room.'---';
	echo $tm_apilar_room.'---';

	echo $tm_alimentar_sobretamaño.'---';
	echo $tm_apilar_sobretamaño.'---';

	echo $tm_alimentar_molienda.'---';
	echo $tm_apilar_molienda.'---';
	*/
	$output = $salida."||".
			  $jsonstring;
	echo $output;

}elseif ($_POST['band'] == 3){
	$fecha = date('Y-m-d H:i:s');
	$idRegistro = $_POST['idRegistro'];
	$producto = $_POST['producto'];
	$actividad = $_POST['actividad'];
	$sub_actividad = $_POST['sub_actividad'];
	$n_paladas = $_POST['n_paladas'];
	$TM_palada = $_POST['TM_Palada'];
	$TM_alimentadas = $_POST['TM_alimentadas'];
    $TM_apiladas = $_POST['TM_apiladas'];
    $pila = $_POST['pila'];
    $equipo = $_POST['equipo'];
    $total_apilar = 0;
    $Zona = $_POST['zona'];
    $tempo_clasif = $_POST['tempo_clasif'];
	//$TempXpalada = $_POST['TempXpalada'];
	//$TempReEst = $_POST['TempReEst'];
	$TempMaquinaEst = $_POST['TempMaquinaEst'];
	//$TempMaquinaEst = number_format($TempMaquinaEst,1,',','.');
	$TotalTM = $_POST['TotalTM'];
	$TotalTM_calculado = $n_paladas*$TM_palada;
	if($TotalTM_calculado == $TotalTM){
		$ajuste = 0;
	}elseif($TotalTM_calculado > $TotalTM){
		$ajuste = $TotalTM_calculado - $TotalTM;
	}else{
		$ajuste = $TotalTM - $TotalTM_calculado;
	}
	if($sub_actividad == '77DE71D6-2FE4-46C3-8615-9B1C3D8F94D0' && $sub_actividad == 'DCEB2375-E476-45F6-9D59-FF9C0B49A5B5' && $sub_actividad == '5A6F9DFF-4AC5-47D6-B3DA-6FEE1308DD7D'){
		$total_apilar = $TM_apiladas + $TotalTM;
	}
	/********************************************************************************************************************************/
	$sql11 = "SELECT SUM(total_horas) AS TOTAL FROM horometro WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
	$res11 = sqlsrv_query($conn,$sql11);
	while($row1 = sqlsrv_fetch_array($res11)){
		$total = number_format($row1['TOTAL'],1);
	}
	//echo 'horas: '.$total;
	$sql1 = "SELECT SUM(total_horas) AS TOTAL FROM horometro WHERE id_registro='$idRegistro' AND horometro_inicial is null AND horometro_final is null";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $result1=sqlsrv_query($conn,utf8_decode($sql1),$params,$options);
    $rows=sqlsrv_num_rows($result1);
    if ($rows > 0) 
	{ 	while($rowss = sqlsrv_fetch_array($result1)){
			$total1 = number_format($rowss['TOTAL'],1,',','.');
		}
		$sql = "SELECT SUM(valor_descuento) AS Descuento FROM horometro_descuento_cargadores WHERE idRegistro='$idRegistro'";
		$params = array();
	    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	    $rows=sqlsrv_num_rows($res);
	    if ($rows > 0) 
		{	while($val = sqlsrv_fetch_array($res)){
				$t = number_format($val['Descuento'],1);// TIEMPO DE DESCUENTOS
			}
			$totalizado = $total1 + $t;
		}else{
			$totalizado = $total1;
		}
		//echo 'distribuido: '.$totalizado;
		$total_resta = $total-$totalizado;
		$total_resta = number_format($total_resta,1);
		$TempMaquinaEst = number_format($TempMaquinaEst,1);
		//echo 'faltante: '.$total_resta;
		//echo 'utilizado: '.$TempMaquinaEst;
		//if($total_apilar < $TM_alimentadas){
			/********************************************************************************************************************************/
			if ($total_resta > $TempMaquinaEst){
				$select = "SELECT Equipos.Descripcion, Registro_tique_cargadores.id_maquinaria FROM Registro_tique_cargadores
							INNER JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo
							WHERE Registro_tique_cargadores.id_registro='$idRegistro'";
				$resu = sqlsrv_query($conn,$select);
				while($rowsss = sqlsrv_fetch_array($resu)){
					$maquinaria = $rowsss['id_maquinaria'];
				}
				$sql = "INSERT INTO tiempos_cargadores_actividad(idDistribucion, idMaquinaria, idSubActividad, idClasificacion, cantidad, TMxPalada, tiempohorometro, idRegistro, TM_total, ajuste_TM, idPila, idEquipo, idZona, horas_clasif, tipo_tarifa)
						VALUES (NEWID(),'$maquinaria','$sub_actividad','$producto','$n_paladas','$TM_palada','$TempMaquinaEst','$idRegistro','$TotalTM','$ajuste','$pila','$equipo','$Zona','$tempo_clasif','0')";
				$resul1 = sqlsrv_query($conn,$sql);
				if ($resul1){
					$sql_insert = "INSERT INTO horometro(id_horometro, id_registro, total_horas, idActividad, idSubActividad, fecha_registro_horometro, fecha_cierre_horometro, idClasificacion, tipo_tarifa)
									VALUES (NEWID(),'$idRegistro','$TempMaquinaEst','$actividad','$sub_actividad','$fecha','$fecha','$producto','0')";
					$result = sqlsrv_query($conn,$sql_insert);
				}
				//echo $sql.' ----';
				//echo $sql_insert;
			}elseif (bccomp($total_resta, $TempMaquinaEst,2) == 0){
				$select = "SELECT Equipos.Descripcion, Registro_tique_cargadores.id_maquinaria FROM Registro_tique_cargadores 
							INNER JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo
							WHERE Registro_tique_cargadores.id_registro='$idRegistro'";
				$resu = sqlsrv_query($conn,$select);
				while($rowsss = sqlsrv_fetch_array($resu)){
					$maquinaria = $rowsss['id_maquinaria'];
				}
				$sql = "INSERT INTO tiempos_cargadores_actividad(idDistribucion, idMaquinaria, idSubActividad, idClasificacion, cantidad, TMxPalada, tiempohorometro, idRegistro, TM_total, ajuste_TM, idPila, idEquipo, idZona, horas_clasif, tipo_tarifa)
						VALUES (NEWID(),'$maquinaria','$sub_actividad','$producto','$n_paladas','$TM_palada','$TempMaquinaEst','$idRegistro','$TotalTM','$ajuste','$pila','$equipo','$Zona','$tempo_clasif','0')";
				$resul1 = sqlsrv_query($conn,$sql);
				if ($resul1){
					$sql_insert = "INSERT INTO horometro(id_horometro, id_registro, total_horas, idActividad, idSubActividad, fecha_registro_horometro, fecha_cierre_horometro, idClasificacion, tipo_tarifa)
									VALUES (NEWID(),'$idRegistro','$TempMaquinaEst','$actividad','$sub_actividad','$fecha','$fecha','$producto','0')";
					$result = sqlsrv_query($conn,$sql_insert);
					if ($result){
						//$sql = "DELETE FROM horometro WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
						//$sql = "UPDATE horometro SET total_horas='0' WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
						//$res = sqlsrv_query($conn,$sql);
					}
				}
			}else{
				echo 1;
			}
		/*}else{
			echo 2;
		}*/
	}else{	
		echo 1;
	}
}elseif ($_POST['band'] == 4){
	$idRegistro = $_POST['idRegistro'];
	$producto = $_POST['producto'];
	$actividad = $_POST['actividad'];
	$sub_actividad = $_POST['sub_actividad'];
	$n_vehiculos = $_POST['n_vehiculos'];
	$TempXvehiculo = $_POST['TempXvehiculo'];
	$TempReEstVehiculo = $_POST['TempReEstVehiculo'];
	$TempMaquinaEstVehiculo = $_POST['TempMaquinaEstVehiculo'];
	$TotalTM = $_POST['TotalTM'];

	/********************************************************************************************************************************/

	$sql = "SELECT SUM(total_horas) AS TOTAL FROM horometro WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
	$res = sqlsrv_query($conn,$sql);
	while($row = sqlsrv_fetch_array($res)){
		$total = $row['TOTAL'];
	}
	$sql = "SELECT SUM(total_horas) AS TOTAL FROM horometro WHERE id_registro='$idRegistro' AND horometro_inicial is null AND horometro_final is null";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $result=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows=sqlsrv_num_rows($result);
    if ($rows > 0) 
	{ 	while($row = sqlsrv_fetch_array($result)){
			$total1 = $row['TOTAL'];
		}
		$sql = "SELECT valor_descuento AS Descuento FROM horometro_descuento_cargadores WHERE idRegistro='$idRegistro'";
		$params = array();
	    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	    $rows=sqlsrv_num_rows($res);
	    if ($rows > 0) 
		{	while($val = sqlsrv_fetch_array($res)){
				$t = $val['Descuento']; // TIEMPO DE DESCUENTOS
			}
			$totalizado = $total1 + $t;
		}else{
			$totalizado = $total1;
		}
		$total_resta = $total-$totalizado;

	/********************************************************************************************************************************/
		if ($TempMaquinaEstVehiculo < $total_resta){
			$select = "SELECT Equipos.Descripcion, Registro_tique_cargadores.id_maquinaria FROM Registro_tique_cargadores 
						INNER JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo
						WHERE Registro_tique_cargadores.id_registro='$idRegistro'";
			$resu = sqlsrv_query($conn,$select);
			while($row = sqlsrv_fetch_array($resu)){
				$maquinaria = $row['id_maquinaria'];
			}
			$sql = "INSERT INTO tiempos_cargadores_actividad(idDistribucion, idMaquinaria, idSubActividad, idClasificacion, cantidad, tiempohora, tiempopromedio, tiempohorometro, idRegistro, TM_total, tipo_tarifa)
					VALUES (NEWID(),'$maquinaria','$sub_actividad','$producto','$n_vehiculos','$TempReEstVehiculo','$TempXvehiculo','$TempMaquinaEstVehiculo','$idRegistro','$TotalTM','0')";
			$res = sqlsrv_query($conn,$sql);
			if ($res){
				$sql_insert = "INSERT INTO horometro(id_horometro, id_registro, total_horas, idActividad, idSubActividad, fecha_registro_horometro, fecha_cierre_horometro, idClasificacion, tipo_tarifa)
								VALUES (NEWID(),'$idRegistro','$TempMaquinaEstVehiculo','$actividad','$sub_actividad','$fecha','$fecha','$producto','0')";
				$result = sqlsrv_query($conn,$sql_insert);
			}
		}elseif (bccomp($total_resta, $TempMaquinaEstVehiculo,2) == 0){
			$select = "SELECT Equipos.Descripcion, Registro_tique_cargadores.id_maquinaria FROM Registro_tique_cargadores 
						INNER JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo
						WHERE Registro_tique_cargadores.id_registro='$idRegistro'";
			$resu = sqlsrv_query($conn,$select);
			while($row = sqlsrv_fetch_array($resu)){
				$maquinaria = $row['id_maquinaria'];
			}
			$sql = "INSERT INTO tiempos_cargadores_actividad(idDistribucion, idMaquinaria, idSubActividad, idClasificacion, cantidad, tiempohora, tiempopromedio, tiempohorometro, idRegistro, TM_total, tipo_tarifa)
					VALUES (NEWID(),'$maquinaria','$sub_actividad','$producto','$n_vehiculos','$TempReEstVehiculo','$TempXvehiculo','$TempMaquinaEstVehiculo','$idRegistro','$TotalTM','0')";
			$res = sqlsrv_query($conn,$sql);
			if ($res){
				$sql_insert = "INSERT INTO horometro(id_horometro, id_registro, total_horas, idActividad, idSubActividad, fecha_registro_horometro, fecha_cierre_horometro, idClasificacion, tipo_tarifa)
								VALUES (NEWID(),'$idRegistro','$TempMaquinaEstVehiculo','$actividad','$sub_actividad','$fecha','$fecha','$producto','0')";
				$result = sqlsrv_query($conn,$sql_insert);
				if ($result){
					//$sql = "DELETE FROM horometro WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
					//$sql = "UPDATE horometro SET total_horas='0' WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
					//$res = sqlsrv_query($conn,$sql);
				}
			}
		}
	}
}elseif ($_POST['band'] == 5){
	$idRegistro = $_POST['idRegistro'];
	$actividad = $_POST['actividad'];
	$i = 1;
	$jsonstring = 0;
	$band = 1;
	$sql = "SELECT * FROM horometro WHERE id_registro='$idRegistro' AND idActividad='$actividad'";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $result=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows=sqlsrv_num_rows($result);
    $i = 0;
    if ($rows > 0) 
	{	$sub_actividad = Array();
		while($rows = sqlsrv_fetch_array($result)){
			$sub_actividad[$i] = "'".$rows['idSubActividad']."'";
			$i++;
		}
		$a = implode(",", $sub_actividad);
		$sql = "SELECT clasificacion.Descripcion, tiempos_cargadores_actividad.idDistribucion, subactividades_cargadores.Descripcion AS sub_actividad, tiempos_cargadores_actividad.idSubactividad  FROM tiempos_cargadores_actividad 
				LEFT JOIN clasificacion ON tiempos_cargadores_actividad.idClasificacion=clasificacion.idClasificacion
				INNER JOIN subactividades_cargadores ON tiempos_cargadores_actividad.idSubactividad=subactividades_cargadores.idSubactividad
				WHERE tiempos_cargadores_actividad.idSubactividad IN ($a) AND tiempos_cargadores_actividad.idRegistro='$idRegistro'";
		$params = array();
	    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	    $rows=sqlsrv_num_rows($res);
	    if($rows > 0){
			$json = array();
			while($datos = sqlsrv_fetch_array($res)){
				$json[] = array(
				    'idDistribucion' => $datos['idDistribucion'],
				    'Descripcion' => utf8_encode($datos['Descripcion']),
				    'sub_actividad' => utf8_encode($datos['sub_actividad']),
				    'idSubactividad' => $datos['idSubactividad']
				);
			}
			$jsonstring = json_encode($json);
			//echo $jsonstring;
		}
	}
	$salida = $band."||".
			  $jsonstring;
	echo $salida;

}elseif ($_POST['band'] == 6){
	$idDistribucion = $_POST['idDistribucion'];
	$sql = "SELECT * FROM tiempos_cargadores_actividad WHERE idDistribucion='$idDistribucion'";
	$res = sqlsrv_query($conn,$sql);
	while($d = sqlsrv_fetch_array($res)){
		$id_registro = $d['idRegistro'];
		$clasific = $d['idClasificacion'];
		$subact = $d['idSubActividad'];
		$sql = "SELECT * FROM horometro WHERE id_Registro='$id_registro' AND idClasificacion='$clasific' AND idSubactividad='$subact'";
		$res = sqlsrv_query($conn,$sql);
		while($horo = sqlsrv_fetch_array($res)){
			$id_horometro = $horo['id_horometro'];
		}
		if($d['cantidad'] == null){
			$ban=3;
			$datos =$ban."||".
					$d['tiempohorometro']."||".
					$d['idDistribucion']."||".
					$d['TM_total']."||".
					$id_horometro;
		}elseif ($d['TMxPalada'] == NULL){
			$ban=2;
			$datos =$ban."||". 
					$d['cantidad']."||".
					$d['tiempohora']."||".
					$d['tiempopromedio']."||".
					$d['tiempohorometro']."||".
					$d['idDistribucion']."||".
					$d['TM_total']."||".
					$id_horometro;
		}else{
			$ban=1;
			$datos =$ban."||". 
					$d['cantidad']."||".
					$d['TMxPalada']."||".
					$d['tiempopromedio']."||".
					$d['tiempohora']."||".
					$d['tiempohorometro']."||".
					$d['idDistribucion']."||".
					$d['TM_total']."||".
					$id_horometro."||".
					$d['horas_clasif'];
		}
	}
	echo $datos;
}elseif ($_POST['band'] == 7){
	$fecha = date('Y-m-d H:i:s');
	$idRegistro = $_POST['idRegistro'];
	$producto = $_POST['producto'];
	$actividad = $_POST['actividad'];
	$sub_actividad = $_POST['sub_actividad'];
	$idDistribucion = $_POST['idDistribucion'];
	$idHorometro = $_POST['idHorometro'];
	$n_paladas = $_POST['n_paladas'];
	$TM_palada = $_POST['TM_Palada'];
	$TotalTM = $_POST['TotalTM'];
	// NUEVO
	$pila = $_POST['pila'];
	$equipo = $_POST['equipo'];
	$zona = $_POST['zona'];
	$tempo_clasif = $_POST['tempo_clasif'];
	//$TempXpalada = $_POST['TempXpalada'];
	//$TempReEst = $_POST['TempReEst'];
	$TempMaquinaEst = $_POST['TempMaquinaEst'];
	$TotalTM_calculado = $n_paladas*$TM_palada;
	if($TotalTM_calculado == $TotalTM){
		$ajuste = 0;
	}elseif($TotalTM_calculado > $TotalTM){
		$ajuste = $TotalTM_calculado - $TotalTM;
	}else{
		$ajuste = $TotalTM - $TotalTM_calculado;
	}

	$sql11 = "SELECT * FROM tiempos_cargadores_actividad WHERE idDistribucion='$idDistribucion'";
	$res11 = sqlsrv_query($conn,$sql11);
	while($row1 = sqlsrv_fetch_array($res11)){
		$cant_d = $row1['cantidad'];
		$TMxPalada = $row1['TMxPalada'];
		$tiempo_d = $row1['tiempohora'];
		$tiempopromedio_d = $row1['tiempopromedio'];
		$tiempohorometro = $row1['tiempohorometro'];
	}
	if ($tiempohorometro > $TempMaquinaEst){
		$calculado = $TempMaquinaEst - $tiempohorometro;
	}else if ($tiempohorometro < $TempMaquinaEst){
		$calculado = $TempMaquinaEst - $tiempohorometro;
	}else{
		$calculado = 0;
	}
	/********************************************************************************************************************************/
	echo $sql11 = "SELECT SUM(total_horas) AS TOTAL FROM horometro WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
	$res11 = sqlsrv_query($conn,$sql11);
	while($row1 = sqlsrv_fetch_array($res11)){
		$total = $row1['TOTAL'];
	}
	echo '$total='.$total;
	$sql1 = "SELECT SUM(total_horas) AS TOTAL FROM horometro WHERE id_registro='$idRegistro' AND horometro_inicial is null AND horometro_final is null";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $result1=sqlsrv_query($conn,utf8_decode($sql1),$params,$options);
    $rows=sqlsrv_num_rows($result1);
    if ($rows > 0) 
	{	//ECHO "PASÓ";
		while($rowss = sqlsrv_fetch_array($result1)){
			$total1 = $rowss['TOTAL'];
		}
		$sql = "SELECT valor_descuento AS Descuento FROM horometro_descuento_cargadores WHERE idRegistro='$idRegistro'";
		$params = array();
	    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	    $rows=sqlsrv_num_rows($res);
	    if ($rows > 0) 
		{	while($val = sqlsrv_fetch_array($res)){
				$t = $val['Descuento']; // TIEMPO DE DESCUENTOS
			}
			$totalizado = $total1 + $t;
		}else{
			$totalizado = $total1;
		}
		echo '$totalizado='.$totalizado;
		$total_resta = $total-$totalizado;
		echo '$total_resta='.$total_resta;
			/********************************************************************************************************************************/
		echo '$calculado='.$calculado;
		if ($total_resta > $calculado){
			$select = "SELECT Equipos.Descripcion, Registro_tique_cargadores.id_maquinaria FROM Registro_tique_cargadores 
						INNER JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo
						WHERE Registro_tique_cargadores.id_registro='$idRegistro'";
			$resu = sqlsrv_query($conn,$select);
			while($rowsss = sqlsrv_fetch_array($resu)){
				$maquinaria = $rowsss['id_maquinaria'];
			}
			if($TempMaquinaEst != 0){
				$sql = "UPDATE tiempos_cargadores_actividad SET idClasificacion='$producto', cantidad='$n_paladas', TMxPalada='$TM_palada', 
				tiempohorometro='$TempMaquinaEst',TM_total='$TotalTM', idPila='$pila', idEquipo='$equipo', idZona='$zona', horas_clasif='$tempo_clasif'
						WHERE idDistribucion='$idDistribucion'";
				$sql_insert = "UPDATE horometro SET total_horas='$TempMaquinaEst', fecha_cierre_horometro='$fecha', idClasificacion='$producto'
									WHERE id_horometro='$idHorometro'";
			}else{
				$sql = "DELETE FROM tiempos_cargadores_actividad WHERE idDistribucion='$idDistribucion'";
				$sql_insert = "DELETE FROM horometro WHERE id_horometro='$idHorometro'";
			}
			$resul1 = sqlsrv_query($conn,$sql);
			$result = sqlsrv_query($conn,$sql_insert);
			if($resul1){
				echo 1;
			}
		}elseif (bccomp($total_resta, $calculado,2) == 0){
			$select = "SELECT Equipos.Descripcion, Registro_tique_cargadores.id_maquinaria FROM Registro_tique_cargadores 
						INNER JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo
						WHERE Registro_tique_cargadores.id_registro='$idRegistro'";
			$resu = sqlsrv_query($conn,$select);
			while($rowsss = sqlsrv_fetch_array($resu)){
				$maquinaria = $rowsss['id_maquinaria'];
			}
			if($TempMaquinaEst != 0){
				$sql = "UPDATE tiempos_cargadores_actividad SET idClasificacion='$producto', cantidad='$n_paladas', TMxPalada='$TM_palada', 
				tiempohorometro='$TempMaquinaEst',TM_total='$TotalTM', idPila='$pila', idEquipo='$equipo', idZona='$zona', horas_clasif='$tempo_clasif'
						WHERE idDistribucion='$idDistribucion'";
			}else{
				$sql = "DELETE FROM tiempos_cargadores_actividad WHERE idDistribucion='$idDistribucion'";
			}
			$resul1 = sqlsrv_query($conn,$sql);
			if ($resul1){
				if($TempMaquinaEst != 0){
					$sql_insert = "UPDATE horometro SET total_horas='$TempMaquinaEst', fecha_cierre_horometro='$fecha', idClasificacion='$producto'
									WHERE id_horometro='$idHorometro'";
				}else{
					$sql_insert = "DELETE FROM horometro WHERE id_horometro='$idHorometro'";
				}
				$result = sqlsrv_query($conn,$sql_insert);
				if ($result){
					//$sql = "DELETE FROM horometro WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
					//$sql = "UPDATE horometro SET total_horas='0' WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
					//$res = sqlsrv_query($conn,$sql);
					//if($res){
						echo 1;
					//}
				}
			}
		}
	}else{
		echo 1;
	}
}elseif ($_POST['band'] == 8){
	$fecha = date('Y-m-d H:i:s');
	$idRegistro = $_POST['idRegistro'];
	$producto = $_POST['producto'];
	$actividad = $_POST['actividad'];
	$sub_actividad = $_POST['sub_actividad'];
	$n_vehiculos = $_POST['n_vehiculos'];
	$TempXvehiculo = $_POST['TempXvehiculo'];
	$TempReEstVehiculo = $_POST['TempReEstVehiculo'];
	$TempMaquinaEstVehiculo = $_POST['TempMaquinaEstVehiculo'];
	$idDistribucion = $_POST['idDistribucion'];
	$idHorometro = $_POST['idHorometro'];
	/********************************************************************************************************************************/
	$sql11 = "SELECT * FROM tiempos_cargadores_actividad WHERE idDistribucion='$idDistribucion'";
	$res11 = sqlsrv_query($conn,$sql11);
	while($row1 = sqlsrv_fetch_array($res11)){
		$cant_d = $row1['cantidad'];
		$TMxPalada = $row1['TMxPalada'];
		$tiempo_d = $row1['tiempohora'];
		$tiempopromedio_d = $row1['tiempopromedio'];
		$tiempohorometro = $row1['tiempohorometro'];
	}
	if ($tiempohorometro > $TempMaquinaEstVehiculo){
		$calculado = $TempMaquinaEstVehiculo - $tiempohorometro;
	}else if ($tiempohorometro < $TempMaquinaEstVehiculo){
		$calculado = $TempMaquinaEstVehiculo - $tiempohorometro;
	}else{
		$calculado = 0;
	}
	$sql = "SELECT SUM(total_horas) AS TOTAL FROM horometro WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
	$res = sqlsrv_query($conn,$sql);
	while($row = sqlsrv_fetch_array($res)){
		$total = $row['TOTAL']; //TIEMPO POR DISTRIBUIR
	}
	$sql = "SELECT SUM(total_horas) AS TOTAL FROM horometro WHERE id_registro='$idRegistro' AND horometro_inicial is null AND horometro_final is null";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $result=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows=sqlsrv_num_rows($result);
    if ($rows > 0) 
	{ 	while($row = sqlsrv_fetch_array($result)){
			$total1 = $row['TOTAL']; // TIEMPO DISTRIBUIDO
		}
		$sql = "SELECT valor_descuento AS Descuento FROM horometro_descuento_cargadores WHERE idRegistro='$idRegistro'";
		$params = array();
	    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	    $rows=sqlsrv_num_rows($res);
	    if ($rows > 0) 
		{	while($val = sqlsrv_fetch_array($res)){
				$t = $val['Descuento']; // TIEMPO DE DESCUENTOS
			}
			$totalizado = $total1 + $t;
		}else{
			$totalizado = $total1;
		}
		$total_resta = $total-$totalizado;

	/********************************************************************************************************************************/
		if ($calculado < $total_resta){
			$select = "SELECT Equipos.Descripcion, Registro_tique_cargadores.id_maquinaria FROM Registro_tique_cargadores 
						INNER JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo
						WHERE Registro_tique_cargadores.id_registro='$idRegistro'";
			$resu = sqlsrv_query($conn,$select);
			while($row = sqlsrv_fetch_array($resu)){
				$maquinaria = $row['id_maquinaria'];
			}
			if($TempMaquinaEstVehiculo != 0){
				$sql = "UPDATE tiempos_cargadores_actividad SET idClasificacion='$producto', cantidad='$n_vehiculos', tiempohora='$TempReEstVehiculo', tiempopromedio='$TempXvehiculo', tiempohorometro='$TempMaquinaEstVehiculo'
						WHERE idDistribucion='$idDistribucion'";
			}else{
				$sql = "DELETE FROM tiempos_cargadores_actividad WHERE idDistribucion='$idDistribucion'";
			}
			$resul1 = sqlsrv_query($conn,$sql);
			if ($resul1){
				if($TempMaquinaEstVehiculo != 0){
					$sql_insert = "UPDATE horometro SET total_horas='$TempMaquinaEstVehiculo', fecha_cierre_horometro='$fecha', idClasificacion='$producto'
									WHERE id_horometro='$idHorometro'";
				}else{
					$sql_insert = "DELETE FROM horometro WHERE id_horometro='$idHorometro'";
				}
				$result = sqlsrv_query($conn,$sql_insert);
				if($result){
					echo 1;
				}
			}
		}elseif (bccomp($total_resta, $TempMaquinaEstVehiculo,2) == 0){
			$select = "SELECT Equipos.Descripcion, Registro_tique_cargadores.id_maquinaria FROM Registro_tique_cargadores 
						INNER JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo
						WHERE Registro_tique_cargadores.id_registro='$idRegistro'";
			$resu = sqlsrv_query($conn,$select);
			while($row = sqlsrv_fetch_array($resu)){
				$maquinaria = $row['id_maquinaria'];
			}
			if($TempMaquinaEstVehiculo != 0){
				$sql = "UPDATE tiempos_cargadores_actividad SET idClasificacion='$producto', cantidad='$n_vehiculos', tiempohora='$TempReEstVehiculo', tiempopromedio='$TempXvehiculo', tiempohorometro='$TempMaquinaEstVehiculo'
						WHERE idDistribucion='$idDistribucion'";
			}else{
				$sql = "DELETE FROM tiempos_cargadores_actividad WHERE idDistribucion='$idDistribucion'";
			}
			$resul1 = sqlsrv_query($conn,$sql);
			if ($resul1){
				if($TempMaquinaEstVehiculo != 0){
					$sql_insert = "UPDATE horometro SET total_horas='$TempMaquinaEstVehiculo', fecha_cierre_horometro='$fecha', idClasificacion='$producto'
									WHERE id_horometro='$idHorometro'";
				}else{
					$sql_insert = "DELETE FROM horometro WHERE id_horometro='$idHorometro'";
				}
				$result = sqlsrv_query($conn,$sql_insert);
				if ($result){
					//$sql = "DELETE FROM horometro WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
					//$sql = "UPDATE horometro SET total_horas='0' WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
					//$res = sqlsrv_query($conn,$sql);
					echo 1;
				}
			}
		}
	}
}elseif ($_POST['band'] == 9){
	$idRegistro = $_POST['idRegistro'];
	$ValorDescuento = $_POST['ValorDescuento'];
	$motivoDescuento = utf8_decode($_POST['motivoDescuento']);
	$TotalTM_Despacho = $_POST['TotalTM_Despacho'];
	$usuario = $_POST['usuario'];

	$sql = "SELECT * FROM horometro WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows=sqlsrv_num_rows($res);
    if ($rows > 0)
    {	$total = 0;
		while($row = sqlsrv_fetch_array($res)){
			$total += $row['total_horas']; //TIEMPO POR DISTRIBUIR
		}
		$sql = "SELECT SUM(total_horas) AS TOTAL FROM horometro WHERE id_registro='$idRegistro' AND horometro_inicial is null AND horometro_final is null";
		$params = array();
	    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	    $result=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	    $rows=sqlsrv_num_rows($result);
	    if ($rows > 0)
		{ 	while($row = sqlsrv_fetch_array($result)){
				$total1 = $row['TOTAL'];// TIEMPO DISTRIBUIDO
			}
			$total_resta = $total-$total1;
			//echo $ValorDescuento;
			if ($total_resta > $ValorDescuento){
				$sql = "INSERT INTO horometro_descuento_cargadores (idDescuento, idRegistro, valor_descuento, fecharegistro, idusuario, descripcion, tm_despacho)
				VALUES (NEWID(),'$idRegistro','$ValorDescuento','$fecha','$usuario','$motivoDescuento','$TotalTM_Despacho')";
				$res = sqlsrv_query($conn,$sql);
				if($res){
					$select = "SELECT Equipos.Descripcion, Registro_tique_cargadores.horas_trabajadas FROM Registro_tique_cargadores 
						INNER JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo
						WHERE Registro_tique_cargadores.id_registro='$idRegistro'";
					$resu = sqlsrv_query($conn,$select);
					while($rowsss = sqlsrv_fetch_array($resu)){
						$total_horas_trabajadas = $rowsss['horas_trabajadas'];
					}
					$horas_trabajadas = $total_horas_trabajadas-$ValorDescuento;
					/*$sql = "UPDATE Registro_tique_cargadores SET horas_trabajadas='$horas_trabajadas' WHERE id_Registro='$idRegistro'";
					$res = sqlsrv_query($conn,$sql);
					if($res){*/
						echo 1;
					//}
				}	
			}elseif (bccomp($total_resta, $ValorDescuento,2) == 0){
				$sql = "INSERT INTO horometro_descuento_cargadores (idDescuento, idRegistro, valor_descuento, fecharegistro, idusuario, descripcion, tm_despacho)
				VALUES (NEWID(),'$idRegistro','$ValorDescuento','$fecha','$usuario','$motivoDescuento','$TotalTM_Despacho')";
				$res = sqlsrv_query($conn,$sql);
				/*$sql = "DELETE FROM horometro WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
				$res = sqlsrv_query($conn,$sql);*/
				if ($res){
					$select = "SELECT Equipos.Descripcion, Registro_tique_cargadores.horas_trabajadas FROM Registro_tique_cargadores 
						INNER JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo
						WHERE Registro_tique_cargadores.id_registro='$idRegistro'";
					$resu = sqlsrv_query($conn,$select);
					while($rowsss = sqlsrv_fetch_array($resu)){
						$total_horas_trabajadas = $rowsss['horas_trabajadas'];
					}
					$horas_trabajadas = $total_horas_trabajadas-$ValorDescuento;
					/*$sql = "UPDATE Registro_tique_cargadores SET horas_trabajadas='$horas_trabajadas' WHERE id_Registro='$idRegistro'";
					$res = sqlsrv_query($conn,$sql);
					if($res){*/
						echo 1;
					//}
				}
			}else{
				echo 2;
			}
		}
	}else{
		$sql = "INSERT INTO horometro_descuento_cargadores (idDescuento, idRegistro, valor_descuento, fecharegistro, idusuario, descripcion, tm_despacho)
				VALUES (NEWID(),'$idRegistro','$ValorDescuento','$fecha','$usuario','$motivoDescuento','$TotalTM_Despacho')";
		$res = sqlsrv_query($conn,$sql);
		if ($res){
			$select = "SELECT Equipos.Descripcion, Registro_tique_cargadores.horas_trabajadas FROM Registro_tique_cargadores 
						INNER JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo
						WHERE Registro_tique_cargadores.id_registro='$idRegistro'";
			$resu = sqlsrv_query($conn,$select);
			while($rowsss = sqlsrv_fetch_array($resu)){
				$total_horas_trabajadas = $rowsss['horas_trabajadas'];
			}
			$horas_trabajadas = $total_horas_trabajadas-$ValorDescuento;
			/*$sql = "UPDATE Registro_tique_cargadores SET horas_trabajadas='$horas_trabajadas' WHERE id_Registro='$idRegistro'";
			$res = sqlsrv_query($conn,$sql);
			if($res){*/
				echo 1;
			//}
		}
	}
}elseif ($_POST['band'] == 10){
	$idRegistro = $_POST['idRegistro'];
	$usuario = $_POST['usuario'];

	$sql = "SELECT * FROM horometro_descuento_cargadores WHERE idRegistro='$idRegistro'";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows=sqlsrv_num_rows($res);
    if ($rows > 0) 
	{	while($d = sqlsrv_fetch_array($res)){
			$datos1 = $d['idDescuento']."||".
					  $d['valor_descuento']."||".
					  $d['descripcion']."||".
					  $d['tm_despacho'];
		}
		echo $datos1;
	}else{
		echo 0;
	}
}elseif ($_POST['band'] == 11){
	$idRegistro = $_POST['idRegistro'];
	$ValorDescuento = $_POST['ValorDescuento'];
	$motivoDescuento = utf8_decode($_POST['motivoDescuento']);
	$TotalTM_Despacho = $_POST['TotalTM_Despacho'];
	$total=0;
	$sql = "SELECT * FROM horometro WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows=sqlsrv_num_rows($res);
    if ($rows > 0)
	{	while($row = sqlsrv_fetch_array($res)){
			$total += $row['total_horas']; //TIEMPO POR DISTRIBUIR
		}
		$sql = "SELECT SUM(total_horas) AS TOTAL FROM horometro WHERE id_registro='$idRegistro' AND horometro_inicial is null AND horometro_final is null";
		$params = array();
	    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	    $result=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	    $rows=sqlsrv_num_rows($result);
	    if ($rows > 0) 
		{ 	while($row = sqlsrv_fetch_array($result)){
				$total1 = $row['TOTAL']; // TIEMPO DISTRIBUIDO
			}
			$total_resta = $total-$total1;
			$ValorDescuento = (float) $ValorDescuento;
			if ($ValorDescuento < $total_resta){
				$select1 = "SELECT * FROM horometro_descuento_cargadores WHERE idRegistro='$idRegistro'";
				$resu1 = sqlsrv_query($conn,$select1);
				while($rowsss1 = sqlsrv_fetch_array($resu1)){
					$horoDescuento = $rowsss1['valor_descuento'];
				}
				$select = "SELECT * FROM Registro_tique_cargadores WHERE id_registro='$idRegistro'";
				$resu = sqlsrv_query($conn,$select);
				while($rowsss = sqlsrv_fetch_array($resu)){
					$total_horas_trabajadas = $rowsss['horas_trabajadas'];
				}
				if($total_horas_trabajadas >= $ValorDescuento){
					if($ValorDescuento == 0){
						$sql = "DELETE FROM horometro_descuento_cargadores WHERE idRegistro='$idRegistro'";
					}else{
						$sql = "UPDATE horometro_descuento_cargadores SET valor_descuento='$ValorDescuento', descripcion='$motivoDescuento', tm_despacho='$TotalTM_Despacho' WHERE idRegistro='$idRegistro'";
					}
					$res = sqlsrv_query($conn,$sql);
					if ($res){
						if($ValorDescuento > $horoDescuento){
							$horas_trabajadas = $total_horas_trabajadas-$ValorDescuento;
						}elseif($ValorDescuento < $horoDescuento){
							if($ValorDescuento == 0){
								$horas_trabajadas = $total_horas_trabajadas+$horoDescuento;
							}else{
								$horas_trabajadas = $total_horas_trabajadas+$ValorDescuento;
							}
						}
						/*$sql = "UPDATE Registro_tique_cargadores SET horas_trabajadas='$horas_trabajadas' WHERE id_Registro='$idRegistro'";
						$res = sqlsrv_query($conn,$sql);
						if($res){*/
							echo 1;
						//}
					}
				}else{
					echo 2;
				}
			}elseif (bccomp($total_resta, $ValorDescuento,2) == 0){
				$select = "SELECT * FROM horometro_descuento_cargadores WHERE idRegistro='$idRegistro'";
				$resu = sqlsrv_query($conn,$select);
				while($rowsss = sqlsrv_fetch_array($resu)){
					$horoDescuento = $rowsss['valor_descuento'];
				}
				$select = "SELECT * FROM Registro_tique_cargadores WHERE id_registro='$idRegistro'";
				$resu = sqlsrv_query($conn,$select);
				while($rowsss = sqlsrv_fetch_array($resu)){
					$total_horas_trabajadas = $rowsss['horas_trabajadas'];
				}
				if($total_horas_trabajadas >= $ValorDescuento){
					if($ValorDescuento == 0){
						$sql = "DELETE FROM horometro_descuento_cargadores WHERE idRegistro='$idRegistro'";
					}else{
						$sql = "UPDATE horometro_descuento_cargadores SET valor_descuento='$ValorDescuento', descripcion='$motivoDescuento', tm_despacho='$TotalTM_Despacho' WHERE idRegistro='$idRegistro'";
					}
					$res = sqlsrv_query($conn,$sql);
					if ($res){
						//$sql12 = "DELETE FROM horometro WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
						//$sql12 = "UPDATE horometro SET total_horas='0' WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
						//$res12 = sqlsrv_query($conn,$sql12);
						//if($res12){
							/*if($ValorDescuento > $horoDescuento){
								$horas_trabajadas = $total_horas_trabajadas-$ValorDescuento;
							}elseif($ValorDescuento < $horoDescuento){
								if($ValorDescuento == 0){
									$horas_trabajadas = $total_horas_trabajadas+$horoDescuento;
								}else{
									$horas_trabajadas = $total_horas_trabajadas+$ValorDescuento;
								}
							}
							$sql = "UPDATE Registro_tique_cargadores SET horas_trabajadas='$horas_trabajadas' WHERE id_Registro='$idRegistro'";
							$res = sqlsrv_query($conn,$sql);
							if($res){*/
								echo 1;
							//}
						//}
					}
				}else{
					echo 2;
				}
			}
		}
	}else{
		$select = "SELECT * FROM horometro_descuento_cargadores WHERE idRegistro='$idRegistro'";
		$resu = sqlsrv_query($conn,$select);
		while($rowsss = sqlsrv_fetch_array($resu)){
			$horoDescuento = $rowsss['valor_descuento'];
		}
		$select = "SELECT * FROM Registro_tique_cargadores WHERE id_registro='$idRegistro'";
		$resu = sqlsrv_query($conn,$select);
		while($rowsss = sqlsrv_fetch_array($resu)){
			$total_horas_trabajadas = $rowsss['horas_trabajadas'];
		}
		if($total_horas_trabajadas >= $ValorDescuento){
			if($ValorDescuento == 0){
				$sql = "DELETE FROM horometro_descuento_cargadores WHERE idRegistro='$idRegistro'";
			}else{
				$sql = "UPDATE horometro_descuento_cargadores SET valor_descuento='$ValorDescuento', descripcion='$motivoDescuento', tm_despacho='$TotalTM_Despacho' WHERE idRegistro='$idRegistro'";
			}
			$res = sqlsrv_query($conn,$sql);
			if ($res){
				if($ValorDescuento > $horoDescuento){
					$horas_trabajadas = $total_horas_trabajadas-$ValorDescuento;
				}elseif($ValorDescuento < $horoDescuento){
					if($ValorDescuento == 0){
						$horas_trabajadas = $total_horas_trabajadas+$horoDescuento;
					}else{
						$horas_trabajadas = $total_horas_trabajadas+$ValorDescuento;
					}
				}
				/*$sql = "UPDATE Registro_tique_cargadores SET horas_trabajadas='$horas_trabajadas' WHERE id_Registro='$idRegistro'";
				$res = sqlsrv_query($conn,$sql);
				if($res){*/
					echo 1;
				//}
			}
		}else{
			echo 2;
		}
	}
}elseif($_POST['band'] == 12){
	$tiquete = $_POST['tiquete'];
	$observaciones = $_POST['observaciones'];
	$sql = "UPDATE Registro_tique_cargadores SET observaciones='$observaciones' WHERE id_registro='$tiquete'";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $result=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows=sqlsrv_num_rows($result);
    echo $rows;
}elseif($_POST['band'] == 13){
	$id_proveedor = $_POST['id_proveedor'];
	$sql = "SELECT * FROM Proveedores WHERE idProveedor='$id_proveedor'";
	$res = sqlsrv_query($conn,$sql);
	while($provee = sqlsrv_fetch_array($res)){
		$RazonSocial = $provee['RazonSocial'];
	}
	echo $RazonSocial;
}elseif($_POST['band'] == 14){
	$idRegistro = $_POST['idRegistro'];
	$producto = $_POST['producto'];
	if($producto == null || $producto == '0'){
		$producto = '00000000-0000-0000-0000-000000000000';
	}
	$actividad = $_POST['actividad'];
	$sub_actividad = $_POST['sub_actividad'];
	$destino = $_POST['destino'];
	$time_est = $_POST['time_est'];
	$TotalTM = $_POST['totalize_tm'];
	$estado = $_POST['estado'];
	/********************************************************************************************************************************/

	$sql = "SELECT SUM(total_horas) AS TOTAL FROM horometro WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
	$res = sqlsrv_query($conn,$sql);
	while($row = sqlsrv_fetch_array($res)){
		$total = $row['TOTAL'];
	}
	//echo 'total horas: '.$total.'<br>';
	$sql = "SELECT SUM(total_horas) AS TOTAL FROM horometro WHERE id_registro='$idRegistro' AND horometro_inicial is null AND horometro_final is null";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $result=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows=sqlsrv_num_rows($result);
    if ($rows > 0) 
	{ 	while($row = sqlsrv_fetch_array($result)){
			$total1 = $row['TOTAL'];
		}
		//echo 'horas utiliza: '.$total1.'<br>';
		$sql = "SELECT valor_descuento AS Descuento FROM horometro_descuento_cargadores WHERE idRegistro='$idRegistro'";
		$params = array();
	    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	    $rows=sqlsrv_num_rows($res);
	    if ($rows > 0) 
		{	while($val = sqlsrv_fetch_array($res)){
				$t = $val['Descuento']; //TIEMPO DE DESCUENTOS
			}
			$totalizado = $total1 + $t;
		}else{
			$totalizado = $total1;
		}
		//echo 'horas + desc: '.$totalizado.'<br>';
		$total_resta = $total-$totalizado;
	/********************************************************************************************************************************/
		if ($time_est < $total_resta){
			$select = "SELECT Equipos.Descripcion, Registro_tique_cargadores.id_maquinaria FROM Registro_tique_cargadores 
						INNER JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo
						WHERE Registro_tique_cargadores.id_registro='$idRegistro'";
			$resu = sqlsrv_query($conn,$select);
			while($row = sqlsrv_fetch_array($resu)){
				$maquinaria = $row['id_maquinaria'];
			}
			$sql = "INSERT INTO tiempos_cargadores_actividad(idDistribucion, idMaquinaria, idSubActividad, idClasificacion, idDestino, tiempohorometro, idRegistro, TM_total, tipo_Tarifa)
					VALUES (NEWID(),'$maquinaria','$sub_actividad','$producto','$destino','$time_est','$idRegistro','$TotalTM','$estado')";
			$res = sqlsrv_query($conn,$sql);
			if ($res){
				$sql_insert = "INSERT INTO horometro(id_horometro, id_registro, total_horas, idActividad, idSubActividad, fecha_registro_horometro, fecha_cierre_horometro, idClasificacion, tipo_tarifa)
								VALUES (NEWID(),'$idRegistro','$time_est','$actividad','$sub_actividad','$fecha','$fecha','$producto','$estado')";
				$result = sqlsrv_query($conn,$sql_insert);
				if($result){
					echo $sql_insert;
				}
			}
		}elseif (bccomp($total_resta, $time_est,2) == 0){
			$select = "SELECT Equipos.Descripcion, Registro_tique_cargadores.id_maquinaria FROM Registro_tique_cargadores 
						INNER JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo
						WHERE Registro_tique_cargadores.id_registro='$idRegistro'";
			$resu = sqlsrv_query($conn,$select);
			while($row = sqlsrv_fetch_array($resu)){
				$maquinaria = $row['id_maquinaria'];
			}
			$sql = "INSERT INTO tiempos_cargadores_actividad(idDistribucion, idMaquinaria, idSubActividad, idClasificacion, idDestino, tiempohorometro, idRegistro, TM_total, tipo_tarifa)
					VALUES (NEWID(),'$maquinaria','$sub_actividad','$producto','$destino','$time_est','$idRegistro','$TotalTM','$estado')";
			$res = sqlsrv_query($conn,$sql);
			if ($res){
				$sql_insert = "INSERT INTO horometro(id_horometro, id_registro, total_horas, idActividad, idSubActividad, fecha_registro_horometro, fecha_cierre_horometro, idClasificacion, tipo_tarifa)
								VALUES (NEWID(),'$idRegistro','$time_est','$actividad','$sub_actividad','$fecha','$fecha','$producto','$estado')";
				$result = sqlsrv_query($conn,$sql_insert);
				if ($result){
					//$sql = "DELETE FROM horometro WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
					//$sql = "UPDATE horometro SET total_horas='0' WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
					//$res = sqlsrv_query($conn,$sql);
				}
			}
		}
	}
}elseif($_POST['band'] == 15){
	$fecha = date('Y-m-d H:i:s');
	$idRegistro = $_POST['idRegistro'];
	$producto = $_POST['producto'];
	$actividad = $_POST['actividad'];
	$sub_actividad = $_POST['sub_actividad'];
	$time_est = $_POST['time_est'];
	$TotalTM = $_POST['totalize_tm'];
	$idDistribucion = $_POST['idDistribucion'];
	$idHorometro = $_POST['idHorometro'];
	$idDestino = $_POST['idDestino'];
	/********************************************************************************************************************************/
	$sql11 = "SELECT * FROM tiempos_cargadores_actividad WHERE idDistribucion='$idDistribucion'";
	$res11 = sqlsrv_query($conn,$sql11);
	while($row1 = sqlsrv_fetch_array($res11)){
		$tiempohorometro = $row1['tiempohorometro'];
	}
	if ($tiempohorometro > $time_est){
		$calculado = $time_est - $tiempohorometro;
	}else if ($tiempohorometro < $time_est){
		$calculado = $time_est - $tiempohorometro;
	}else{
		$calculado = 0;
	}
	$sql = "SELECT SUM(total_horas) AS TOTAL FROM horometro WHERE id_registro='$idRegistro' AND idActividad is null AND idSubActividad is null";
	$res = sqlsrv_query($conn,$sql);
	while($row = sqlsrv_fetch_array($res)){
		$total = $row['TOTAL']; //TIEMPO POR DISTRIBUIR
	}
	$sql = "SELECT SUM(total_horas) AS TOTAL FROM horometro WHERE id_registro='$idRegistro' AND horometro_inicial is null AND horometro_final is null";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $result=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows=sqlsrv_num_rows($result);
    if ($rows > 0) 
	{ 	while($row = sqlsrv_fetch_array($result)){
			$total1 = $row['TOTAL'];// TIEMPO DISTRIBUIDO
		}
		$sql = "SELECT valor_descuento AS Descuento FROM horometro_descuento_cargadores WHERE idRegistro='$idRegistro'";
		$params = array();
	    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	    $rows=sqlsrv_num_rows($res);
	    if ($rows > 0) 
		{	while($val = sqlsrv_fetch_array($res)){
				$t = $val['Descuento']; // TIEMPO DE DESCUENTOS
			}
			$totalizado = $total1 + $t;
		}else{
			$totalizado = $total1;
		}
		//echo $calculado.' - '; 
		$total_resta = $total-$totalizado;
		//echo $total_resta;
	/********************************************************************************************************************************/
		if ($calculado < $total_resta){
			$select = "SELECT Equipos.Descripcion, Registro_tique_cargadores.id_maquinaria FROM Registro_tique_cargadores 
						INNER JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo
						WHERE Registro_tique_cargadores.id_registro='$idRegistro'";
			$resu = sqlsrv_query($conn,$select);
			while($row = sqlsrv_fetch_array($resu)){
				$maquinaria = $row['id_maquinaria'];
			}
			if($time_est != 0){
				$sql = "UPDATE tiempos_cargadores_actividad SET idClasificacion='$producto', tiempohorometro='$time_est', TM_total='$TotalTM', idDestino='$idDestino'
						WHERE idDistribucion='$idDistribucion'";
			}else{
				$sql = "DELETE FROM tiempos_cargadores_actividad WHERE idDistribucion='$idDistribucion'";
			}
			$resul1 = sqlsrv_query($conn,$sql);
			if ($resul1){
				if($time_est != 0){
					$sql_insert = "UPDATE horometro SET total_horas='$time_est', fecha_cierre_horometro='$fecha', idClasificacion='$producto'
									WHERE id_horometro='$idHorometro'";
				}else{
					$sql_insert = "DELETE FROM horometro WHERE id_horometro='$idHorometro'";
				}
				$result = sqlsrv_query($conn,$sql_insert);
				if($result){
					echo 1;
				}
			}
		}elseif (bccomp($total_resta, $calculado,2) == 0){
			$select = "SELECT Equipos.Descripcion, Registro_tique_cargadores.id_maquinaria FROM Registro_tique_cargadores 
						INNER JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo
						WHERE Registro_tique_cargadores.id_registro='$idRegistro'";
			$resu = sqlsrv_query($conn,$select);
			while($row = sqlsrv_fetch_array($resu)){
				$maquinaria = $row['id_maquinaria'];
			}
			if($time_est != 0){
				$sql = "UPDATE tiempos_cargadores_actividad SET idClasificacion='$producto', tiempohorometro='$time_est', TM_total='$TotalTM', idDestino='$idDestino'
						WHERE idDistribucion='$idDistribucion'";
			}else{
				$sql = "DELETE FROM tiempos_cargadores_actividad WHERE idDistribucion='$idDistribucion'";
			}
			$resul1 = sqlsrv_query($conn,$sql);
			if ($resul1){
				if($time_est != 0){
					$sql_insert = "UPDATE horometro SET total_horas='$time_est', fecha_cierre_horometro='$fecha', idClasificacion='$producto'
									WHERE id_horometro='$idHorometro'";
				}else{
					$sql_insert = "DELETE FROM horometro WHERE id_horometro='$idHorometro'";
				}
				$result = sqlsrv_query($conn,$sql_insert);
				if($result){
					echo 1;
				}
			}
		}
	}
}elseif ($_POST['band'] == 16){
	$idRegistro = $_POST['idRegistro'];
	$usuario = $_POST['usuario'];

	$sql = "SELECT * FROM horometro WHERE id_registro='$idRegistro' AND idSubactividad='EF629169-C06B-4414-AD73-97E3B76628F4'";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows=sqlsrv_num_rows($res);
    if ($rows > 0)
	{	while($d = sqlsrv_fetch_array($res)){
			$datos1 = $d['total_horas']."||".
					  $d['Observaciones'];
		}
		echo $datos1;
	}else{
		echo 0;
	}
}elseif ($_POST['band'] == 17){
	$idRegistro = $_POST['idRegistro'];
	$ValorStandby = $_POST['ValorStandby'];
	$motivoStandby = utf8_decode($_POST['motivoStandby']);
	$usuario = $_POST['usuario'];
	$sql = "INSERT INTO horometro(id_horometro, id_registro, total_horas, idActividad, idSubActividad, fecha_registro_horometro, fecha_cierre_horometro, Observaciones)
			VALUES (NEWID(),'$idRegistro','$ValorStandby','7E51DEAF-1782-46A1-BE54-4418C8F1D80B','EF629169-C06B-4414-AD73-97E3B76628F4','$fecha','$fecha','$motivoStandby')";
	$res = sqlsrv_query($conn,$sql);
	if($res){
		echo 1;
	}
}elseif ($_POST['band'] == 18){
	$idRegistro = $_POST['idRegistro'];
	$ValorStandby = $_POST['ValorStandby'];
	$motivoStandby = utf8_decode($_POST['motivoStandby']);
	$total=0;
	if($ValorStandby == 0){
		$sql = "DELETE FROM horometro WHERE id_registro='$idRegistro' AND idSubActividad='EF629169-C06B-4414-AD73-97E3B76628F4'";
	}else{
		$sql = "UPDATE horometro SET total_horas='$ValorStandby', Observaciones='$motivoStandby' WHERE id_registro='$idRegistro' AND idSubActividad='EF629169-C06B-4414-AD73-97E3B76628F4'";
	}
	$res = sqlsrv_query($conn,$sql);
	if ($res){
		echo 1;
	}
}elseif ($_POST['band'] == 19){
	$idRegistro = $_POST['idRegistro'];
	$actividad = $_POST['actividad'];
	$i = 1;
	$band = 2;
	$jsonstring = 0;
	$jsonstring1 = 0;
	$sql = "SELECT * FROM horometro WHERE id_registro='$idRegistro' AND idActividad='$actividad'";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $result=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows=sqlsrv_num_rows($result);
    $i = 0;
    if ($rows > 0) 
	{	$sub_actividad = Array();
		while($rows = sqlsrv_fetch_array($result)){
			$sub_actividad[$i] = "'".$rows['idSubActividad']."'";
			$i++;
		}
		$a = implode(",", $sub_actividad);
		$sql = "SELECT clasificacion.Descripcion, tiempos_cargadores_actividad.idDistribucion, subactividades_cargadores.Descripcion AS sub_actividad, 
				tiempos_cargadores_actividad.idSubactividad, Equipos.Descripcion as Equipo, Equipos.Identificacion
				FROM tiempos_cargadores_actividad 
				LEFT JOIN Equipos ON tiempos_cargadores_actividad.idEquipo = Equipos.idEquipo
				LEFT JOIN clasificacion ON tiempos_cargadores_actividad.idClasificacion=clasificacion.idClasificacion
				INNER JOIN subactividades_cargadores ON tiempos_cargadores_actividad.idSubactividad=subactividades_cargadores.idSubactividad
				WHERE tiempos_cargadores_actividad.idSubactividad IN ($a) AND tiempos_cargadores_actividad.idRegistro='$idRegistro'";
		$params = array();
	    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	    $rows=sqlsrv_num_rows($res);
	    if($rows > 0){
			$json = array();
			$json1 = array();
			while($datos = sqlsrv_fetch_array($res)){
				if($datos['sub_actividad'] == 'ALIMENTAR'){
					$json[] = array(
					    'idDistribucion' => $datos['idDistribucion'],
					    'Descripcion' => utf8_encode($datos['Descripcion']),
					    'sub_actividad' => utf8_encode($datos['sub_actividad']),
					    'equipo' => utf8_encode($datos['Equipo'])." - ".$datos['Identificacion'],
					    'idSubactividad' => $datos['idSubactividad']
					);
				}elseif($datos['sub_actividad'] == 'APILAR'){
					$json1[] = array(
					    'idDistribucion' => $datos['idDistribucion'],
					    'Descripcion' => utf8_encode($datos['Descripcion']),
					    'sub_actividad' => utf8_encode($datos['sub_actividad']),
					    'equipo' => utf8_encode($datos['Equipo'])." - ".$datos['Identificacion'],
					    'idSubactividad' => $datos['idSubactividad']
					);
				}
			}
			$jsonstring = json_encode($json);
			$jsonstring1 = json_encode($json1);
			//echo $jsonstring;
		}
	}
	$salida = $band."||".
			  $jsonstring."||".
			  $jsonstring1;
	echo $salida;
}elseif($_POST['band'] == 20){
	$producto = $_POST['producto'];
	$idRegistro = $_POST['idRegistro'];

	$sql_ticket = "SELECT id_maquinaria FROM Registro_tique_cargadores WHERE id_registro='$idRegistro'";
	$r = sqlsrv_query($conn,$sql_ticket);
	while ($ticket = sqlsrv_fetch_array($r)) {
		$idEquipo = $ticket['id_maquinaria'];
	}

	$sql = "SELECT tara FROM Cargadores_Tara WHERE idClasificacion='$producto' AND idEquipo='$idEquipo'";
	$res = sqlsrv_query($conn,$sql);
	while($tara = sqlsrv_fetch_array($res)){
		echo $tara['tara'];
	}
}elseif($_POST['band'] == 21){
	$idRegistro = $_POST['idRegistro'];
	$sql_ticket = "SELECT id_maquinaria FROM Registro_tique_cargadores inner join 
	TarifaMaquinaria ON Registro_tique_cargadores.id_maquinaria=TarifaMaquinaria.idEquipo
	WHERE id_registro='$idRegistro' and TarifaMaquinaria.Fecha_Hasta = '1900-01-01' and TarifaMaquinaria.Tipo_Tarifa!='3'";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $res=sqlsrv_query($conn,utf8_decode($sql_ticket),$params,$options);
    $rows=sqlsrv_num_rows($res);
    echo $rows;
}elseif($_POST['band'] == 22){
	$idRegistro = $_POST['idRegistro'];
	$sql = "SELECT * FROM Registro_tique_cargadores where id_registro='$idRegistro'";
	$res = sqlsrv_query($conn,$sql);
	while($tique = sqlsrv_fetch_array($res)){
		$horo_ini = $tique['horometro_ini'];
		$horo_fin = $tique['horometro_fin'];
	}
	$count = 0;
	$total_horas = $horo_fin-$horo_ini;
	$total_horas = number_format($total_horas,1);
	$actualiza_1 = "UPDATE Registro_tique_cargadores set horas_trabajadas='$total_horas', estado=4 WHERE id_registro='$idRegistro'";
	$res_1 = sqlsrv_query($conn,$actualiza_1);
	if($res_1){
		$consulta = "SELECT * FROM horometro where id_registro='$idRegistro' and idActividad is null";
		$con = sqlsrv_query($conn,$consulta);
		while($horos = sqlsrv_fetch_array($con)){
			$total_horas = $horos['horometro_final']-$horos['horometro_inicial'];
			$total_horas = number_format($total_horas,1);
			$actualiza_2 = "UPDATE horometro set total_horas='$total_horas' where id_registro='$idRegistro' and idActividad is null";
			$res_2 = sqlsrv_query($conn,$actualiza_2);
			if($res_2){
				$count++;
			}
		}
		if($count > 0){
			echo 1;
		}
	}
}elseif($_POST['band'] == 23){
	$idRegistro = $_POST['idRegistro'];
	$sql_select = "SELECT horas_trabajadas FROM Registro_tique_cargadores WHERE id_Registro='$idRegistro'";
	$res = sqlsrv_query($conn,$sql_select);
	while($ticket = sqlsrv_fetch_array($res)){
		$horas = $ticket['horas_trabajadas'];
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$sql = "SELECT sum(total_horas) as total_horas FROM horometro where id_registro='$idRegistro' AND horometro_inicial is null AND horometro_final is null and tipo_tarifa=1";
	$res = sqlsrv_query($conn,$sql);
	while($a1 = sqlsrv_fetch_array($res)){
		$var_1 = $a1['total_horas'];
	}
	$sql_2 = "SELECT sum(tiempohorometro) as tiempohorometro FROM tiempos_cargadores_actividad WHERE idRegistro='$idRegistro' and tipo_tarifa=1";
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
	$sql = "SELECT valor_descuento AS Descuento FROM horometro_descuento_cargadores WHERE idRegistro='$idRegistro'";
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

	$sql_update = "UPDATE Registro_tique_cargadores SET horas_trabajadas='$descuento', estado='3' WHERE id_registro='$idRegistro'";
	$result = sqlsrv_query($conn,$sql_update);
	if ($result){
		$SQL = "UPDATE horometro SET total_horas='0' WHERE id_registro='$idRegistro' AND idActividad is null";
		$res = sqlsrv_query($conn,$SQL);
		if($res){
			echo 1;
		}
	}
}elseif($_POST['band'] == 24){
	$Year = date('Y')-1;
	$idDistribucion = $_POST['seleccionado'];
	$idRegistro = $_POST['idRegistro'];
	$mensaje = "";
	$mensaje0 = "";
	$mensaje1 = "";
	$mensaje2 = "";
	$mensaje3 = "";
	$sql_select = "SELECT id_patio FROM Registro_tique_cargadores WHERE id_Registro='$idRegistro'";
	$res = sqlsrv_query($conn,$sql_select);
	while($ticket = sqlsrv_fetch_array($res)){
		$patio = $ticket['id_patio'];
	}
	if($idDistribucion != '0'){
		$data = "SELECT * FROM tiempos_cargadores_actividad where idDistribucion='$idDistribucion'";
		$qqq = sqlsrv_query($conn,$data);
		while($Adata = sqlsrv_fetch_array($qqq)){
			$idPila = $Adata['idPila'];
			$idProducto = $Adata['idClasificacion'];
			$idEquipo = $Adata['idEquipo'];
			$idZona = $Adata['idZona'];
			$idDestino = $Adata['idDestino'];
		}
	}else{
		$idPila = '0';
		$idProducto = '00000000-0000-0000-0000-000000000000';
		$idEquipo = '0';
		$idZona = '0';
		$idDestino = '00000000-0000-0000-0000-000000000000';
		$mensaje0 += '<option value="0" selected>Seleccione</option>';
		$mensaje1 += '<option value="0" selected>Seleccione</option>';
		$mensaje2 += '<option value="0" selected>Seleccione</option>';
		$mensaje3 += '<option value="0" selected>Seleccione</option>';
		$mensaje4 += '<option value="0" selected>Seleccione</option>';
	}
	//echo $idPila."||".$idProducto."||".$idEquipo."||".$idZona;
	$sql="SELECT * FROM Pila_informes";
	$params = array();
   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
   	$resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
   	if($idPila == '0' || $idPila == null){
		$mensaje0 .= '<option value="0">Seleccione</option>';
   	}
	while($resultados = sqlsrv_fetch_array($resultado)) {
		$nombre = $resultados['id_pila'];
		//Output
		if($nombre == $idPila){
			$mensaje0.= '<option value="'.$nombre.'" selected>'.utf8_encode($resultados['descripcion']).'</option>';
		}else{
			$mensaje0.= '<option value="'.$nombre.'">'.utf8_encode($resultados['descripcion']).'</option>';
		}
	};//Fin while $resultados
	//echo $mensaje0;
	//$mensaje+=$mensaje0;
	$sql="SELECT DISTINCT(Clasificacion),Clasificacion.idClasificacion FROM tz_MovimientoTransporte  
        INNER JOIN Clasificacion ON tz_MovimientoTransporte.Clasificacion=Clasificacion.Descripcion
        WHERE year(FechaRegistro)>='$Year' ORDER BY Clasificacion";
	$params = array();
   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
   	$resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
   	if($idProducto == '00000000-0000-0000-0000-000000000000'){
		$mensaje1 .= '<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>';
   	}
	while($resultados = sqlsrv_fetch_array($resultado)) {
		$nombre = $resultados['idClasificacion'];
		//Output
		if($nombre == $idProducto){
			$mensaje1.= '<option value="'.$nombre.'" selected>'.utf8_encode($resultados['Clasificacion']).'</option>';
		}else{
			$mensaje1.= '<option value="'.$nombre.'">'.utf8_encode($resultados['Clasificacion']).'</option>';
		}	
	};//Fin while $resultados
	//$mensaje+=$mensaje1;
	$sql="SELECT * FROM Equipos where clase_equipo != '7A975CD6-2672-430D-B29E-7149A03D9410'";
	$params = array();
   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
   	$resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
   	if($idEquipo == 0){
		$mensaje2 .= '<option value="0">Seleccione</option>';
   	}
	while($resultados = sqlsrv_fetch_array($resultado)) {
		$nombre = $resultados['idEquipo'];
		//Output
		if($nombre == $idEquipo){
			$mensaje2.= '<option value="'.$nombre.'" selected>'.utf8_encode($resultados['Descripcion']." - ".$resultados['Identificacion']).'</option>';
		}else{
			$mensaje2.= '<option value="'.$nombre.'">'.utf8_encode($resultados['Descripcion']." - ".$resultados['Identificacion']).'</option>';
		}	
	};//Fin while $resultados
	//$mensaje+=$mensaje2;
	$sql="SELECT DestinoZonas.Zona, DestinoZonas.idZona FROM DestinoZonas WHERE DestinoZonas.idDestino = '$patio' 
			ORDER BY DestinoZonas.Zona";
	$params = array();
   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
   	$resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
   	if($idZona == '0'){
		$mensaje3.= '<option value="0">Seleccione</option>';
   	}
	while($resultados = sqlsrv_fetch_array($resultado)) {
		$nombre = $resultados['idZona'];
		//Output
		if($nombre == $idZona){
			$mensaje3.= '<option value="'.$nombre.'" selected>'.utf8_encode($resultados['Zona']).'</option>';
		}else{
			$mensaje3.= '<option value="'.$nombre.'">'.utf8_encode($resultados['Zona']).'</option>';
		}	
	};//Fin while $resultados
	//$mensaje+=$mensaje3;
	$sql="SELECT DISTINCT tz_MovimientoTransporte.RecepcionadoEn, Destino.idDestino
      FROM tz_MovimientoTransporte INNER JOIN
           Destino ON tz_MovimientoTransporte.RecepcionadoEn = Destino.Descripcion
      WHERE (YEAR(tz_MovimientoTransporte.FechaRegistro) >= $Year)
      order by RecepcionadoEn";
	$params = array();
   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
   	$resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
   	if($idDestino == '00000000-0000-0000-0000-000000000000'){
		$mensaje4.= '<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>';
   	}
	while($resultados = sqlsrv_fetch_array($resultado)) {
		$nombre = $resultados['idDestino'];
		//Output
		if($nombre == $idDestino){
			$mensaje4.= '<option value="'.$nombre.'" selected>'.utf8_encode($resultados['RecepcionadoEn']).'</option>';
		}else{
			$mensaje4.= '<option value="'.$nombre.'">'.utf8_encode($resultados['RecepcionadoEn']).'</option>';
		}	
	};//Fin while $resultados
	//$mensaje+=$mensaje3;
	$mensaje = $mensaje0."||".
				$mensaje1."||".
				$mensaje2."||".
				$mensaje3."||".
				$mensaje4."||".
	//////////////////////////////////////////////////////////////////////////////////////
				$idPila."||".
				$idProducto."||".
				$idEquipo."||".
				$idZona."||".
				$idDestino;
	echo $mensaje;
	//Devolvemos el mensaje que tomará jQuery
}elseif($_POST['band'] == 25){
	$idRegistro = $_POST['idRegistro'];
	$sql="SELECT id_patio, id_maquinaria, fecha_apertura_tique, fecha_cierre_tique, id_proveedor, remision
		FROM Registro_tique_cargadores 	WHERE id_registro='$idRegistro'";
	$res = sqlsrv_query($conn,$sql);
	while($data = sqlsrv_fetch_array($res)){
		$patio = $data['id_patio'];
		$proveedor = $data['id_proveedor'];
		$remision = $data['remision'];
		$maquinaria = $data['id_maquinaria'];
		$fecha_apertura_tique = date_format($data['fecha_apertura_tique'],'Y-m-d');
		$fecha_cierre_tique = date_format($data['fecha_cierre_tique'],'Y-m-d');
	}
	$select_patio = "";
	$select_proveedor = "";
	$sql="SELECT * FROM Destino WHERE OperacionCargador=1";
	$params = array();
   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
   	$resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	while($resultados = sqlsrv_fetch_array($resultado)){
		$nombre = $resultados['idDestino'];
		//Output
		if($nombre == $patio){
			$select_patio.= '<option value="'.$nombre.'" selected>'.utf8_encode($resultados['Descripcion']).'</option>';
		}else{
			$select_patio.= '<option value="'.$nombre.'">'.utf8_encode($resultados['Descripcion']).'</option>';
		}	
	}
	$sql="SELECT * FROM Proveedores WHERE idProveedor in (SELECT idProveedor FROM  vProveedoresInAgrupacion WHERE Alias='CC') ORDER BY NombreCorto";
	$params = array();
   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
   	$resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	while($resultados = sqlsrv_fetch_array($resultado)){
		$nombre = $resultados['idProveedor'];
		//Output
		if($nombre == $proveedor){
			$select_proveedor.= '<option value="'.$nombre.'" selected>'.utf8_encode($resultados['RazonSocial']).'</option>';
		}else{
			$select_proveedor.= '<option value="'.$nombre.'">'.utf8_encode($resultados['RazonSocial']).'</option>';
		}	
	}
	$sql="SELECT * FROM Equipos WHERE idEquipo='$maquinaria'";
	$params = array();
   	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
   	$resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	while($resultados = sqlsrv_fetch_array($resultado)){
		$equipo = $resultados['Descripcion'].' - '.$resultados['Identificacion'];
		//Output
	}
	$mensaje = $select_patio."||".
				$select_proveedor."||".
				$remision."||".
				$equipo."||".
				$fecha_apertura_tique."||".
				$fecha_cierre_tique;
	echo $mensaje;
}elseif($_POST['band'] == 26){
	$patio = $_POST['patio'];
	$proveedor = $_POST['proveedor'];
	$remision = $_POST['remision'];
	$fecha_apertura = $_POST['fecha_apertura'];
	$fecha_cierre = $_POST['fecha_cierre'];
	$idRegistro = $_POST['idRegistro'];

	$sql = "UPDATE Registro_tique_cargadores SET id_patio='$patio',fecha_ini_jornada='$fecha_apertura', fecha_apertura_tique='$fecha_apertura',
		fecha_fin_jornada='$fecha_cierre', fecha_cierre_tique='$fecha_cierre', id_proveedor='$proveedor', remision='$remision'
		WHERE id_registro='$idRegistro'";
	$res = sqlsrv_query($conn,$sql);
	if($res){
		$sql1 = "UPDATE horometro SET fecha_registro_horometro='$fecha_apertura', fecha_cierre_horometro='$fecha_cierre'
		WHERE id_registro='$idRegistro'";
		$res1 = sqlsrv_query($conn,$sql1);
		if($res1){
			//echo 1;
			$sql1 = "UPDATE horometro_descuento_cargadores SET fecharegistro='$fecha_apertura' WHERE idRegistro='$idRegistro'";
			$res1 = sqlsrv_query($conn,$sql1);
			if($res1){
				echo 1;
			}
		}
	}
}
?>