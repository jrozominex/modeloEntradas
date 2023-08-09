<?php
include('conexion.php');
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
if ($_POST['band'] == 1){
	$actividad = strtoupper(utf8_decode($_POST['actividad']));
	$TipoAct = $_POST['TipoAct'];
	$sql = "INSERT INTO Actividades (idActividad, Descripcion, idTipoActividad) VALUES (NEWID(),'$actividad','$TipoAct')";
	$result = sqlsrv_query($conn,$sql);
	if ($result){
		echo 1;
	}
}elseif ($_POST['band'] == 2){
	$actividad = $_POST['idActividad'];
	$consulta = "SELECT * FROM Actividades WHERE idActividad='$actividad'";
	$res = sqlsrv_query($conn,$consulta);
	while($aa = sqlsrv_fetch_array($res)){
		$idTipoActividad = $aa['idTipoActividad'];
	}
	$subactividad = strtoupper(utf8_decode($_POST['subactividad']));
	//$sql = "DELETE FROM subactividades_cargadores WHERE idSubactividad='57791C09-9011-4C3F-9F9A-7EE4199676DB'";
	$sql = "INSERT INTO subactividades_cargadores (idActividad, idSubactividad, Descripcion) VALUES ('$actividad',NEWID(),'$subactividad')";
	$result = sqlsrv_query($conn,$sql);
	if ($result){
		echo '1'."||".$idTipoActividad;
	}
}elseif($_POST['band'] == 3){
	$actividad = $_POST['idActividad'];
	$TipoAct = $_POST['TipoAct'];
	$Descripcion = strtoupper(utf8_decode($_POST['Descripcion']));
	//
	$sql = "UPDATE Actividades SET Descripcion='$Descripcion', idTipoActividad='$TipoAct' WHERE idActividad='$actividad'";
	$res = sqlsrv_query($conn,$sql);
	if($res){
		echo 1;
	}
}elseif($_POST['band'] == 4){
	$actividad = $_POST['idActividad'];
	$consulta = "SELECT * FROM Actividades WHERE idActividad='$actividad'";
	$res = sqlsrv_query($conn,$consulta);
	while($aa = sqlsrv_fetch_array($res)){
		$idTipoActividad = $aa['idTipoActividad'];
	}
	$SubActividad1 = $_POST['idSubActividad'];
	$Descripcion = strtoupper(utf8_decode($_POST['Descripcion']));
	//
	$sql = "UPDATE subactividades_cargadores SET Descripcion='$Descripcion' WHERE idSubactividad='$SubActividad1'";
	$res = sqlsrv_query($conn,$sql);
	if($res){
		echo '1'."||".$idTipoActividad;
	}
}elseif($_POST['band'] == 5){
	$Descripcion = $_POST['Descripcion'];
	$list_agrupacion = $_POST['list_agrupacion'];
	$sql_NEWID = "SELECT NEWID() AS NEWID";
	$res = sqlsrv_query($conn,$sql_NEWID);
	while($aa = sqlsrv_fetch_array($res)){
		$NEWID = $aa['NEWID'];
	}
	//
	$sql = "INSERT INTO EquiposGrupos (idGrupo, Descripcion) VALUES ('$NEWID','$Descripcion')";
	$res = sqlsrv_query($conn,$sql);
	if($res){
		$sql_2 = "INSERT INTO EquiposGrupos_Agrupaciones (idGrupo,idAgrupacion) VALUES ('$NEWID','$list_agrupacion')";
		$res_2 = sqlsrv_query($conn,$sql_2);
		if($res_2){
			echo 1;
		}
	}
}elseif($_POST['band'] == 6){
	$Descripcion = strtoupper(utf8_decode($_POST['Descripcion']));
	$Fecha = date('Y-m-d');
	//
	$sql = "INSERT INTO Pila_informes(id_pila, descripcion, fecha_registro) VALUES (NEWID(),'$Descripcion','$Fecha')";
	$res = sqlsrv_query($conn,$sql);
	if($res){
		echo 1;
	}
}elseif($_POST['band'] == 7){
	$idEquipo = $_POST['equipo'];
	$idClasificacion = $_POST['clasificacion'];
	$tara = $_POST['tara'];
	//
	$sql = "INSERT INTO Cargadores_Tara(idEquipo, idClasificacion, tara) VALUES ('$idEquipo','$idClasificacion','$tara')";
	$res = sqlsrv_query($conn,$sql);
	if($res){
		echo 1;
	}
}elseif($_POST['band'] == 8){
	$idActividad = $_POST['idActividad'];
	$idDestino = $_POST['idDestino'];
	$checkbox_cargadores = $_POST['checkbox_cargadores'];
	$checkbox_produccion = $_POST['checkbox_produccion'];
	//
	$insert = "INSERT INTO Actividades_cargadores_destinos VALUES(NEWID(),'$idActividad','$idDestino',$checkbox_cargadores,$checkbox_produccion)";
	$res = sqlsrv_query($conn,$insert);
	if($res){
		echo 1;
	}
}elseif($_POST['band'] == 9){
	$idxid = $_POST['idxid'];
	if($_POST['estado'] == 1)
		$estado = 0;
	elseif($_POST['estado'] == 0)
		$estado = 1;
	$sql = "UPDATE Actividades_cargadores_destinos SET Estado=$estado WHERE idxid='$idxid'";
	$res = sqlsrv_query($conn,$sql);
	if($res){
		echo 1;
	}
}elseif($_POST['band'] == 10){
	$idxid = $_POST['idxid'];
	$estado = $_POST['estado'];
	$sql = "DELETE FROM Actividades_cargadores_destinos WHERE idxid='$idxid'";
	$res = sqlsrv_query($conn,$sql);
	if($res){
		echo 1;
	}
}elseif($_POST['band'] == 11){
	$idReceta = $_POST['idReceta'];
	$sql = "DELETE FROM Recetas_produccion_detalle WHERE idReceta='$idReceta'";
	$res = sqlsrv_query($conn,$sql);
	if($res){
		$sql1 = "DELETE FROM Recetas_produccion WHERE idReceta='$idReceta'";
		$res1 = sqlsrv_query($conn,$sql1);
		if($res1){
			echo 1;
		}
	}
}elseif($_POST['band'] == 12){
	$input_id = explode(',',$_POST['input_id']);
	$input_value = explode(',',$_POST['input_value']);
	$var = 0;
	$igual_total = 0;
	//$array_clasificacion = 
	$position_clasif = 0;
	for ($i=0; $i < count($input_id); $i++){
		$igual = 0;
		for ($j=0; $j < count($input_value); $j++){
			if($input_value[$i] == $input_value[$j]){
				$igual++;
			}
		}
		if($igual==1){
			$igual_total++;
			$sql = "SELECT * FROM ClasificacionJerarquia WHERE idClasificacion='$input_id[$i]'";
			$params = array();
		    $options = array("Scrollable"=>SQLSRV_CURSOR_KEYSET);
		    $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
		    $rows=sqlsrv_num_rows($resultado);
		    if($rows==0){
		    	$insert = "INSERT INTO ClasificacionJerarquia (idClasificacion,Jerarquia) VALUES ('$input_id[$i]','$input_value[$i]')";
		    	$res = sqlsrv_query($conn,$insert);
		    	if($res){
		    		$var++;
		    	}
			}else{
				$update = "UPDATE ClasificacionJerarquia SET Jerarquia='$input_value[$i]' WHERE idClasificacion='$input_id[$i]'";
				$res = sqlsrv_query($conn,$update);
				if($res){
					$var++;
				}
			}
		}else{
			$Array_clasif[$position_clasif] = $input_id[$i];
			$position_clasif++;
		}
	}
	if(count($input_id)==$var && $igual_total==count($input_value)){
		echo 1;
	}else{
		$aa = implode(",", $Array_clasif);
		echo $aa;
	}
}elseif($_POST['band']==13){
	$idxid = $_POST['idxid'];
	$action = $_POST['action'];
	$estado = $_POST['estado'];
	if($estado==1){
		$estado=0;
	}else{
		$estado=1;
	}
	$update = "UPDATE Actividades_cargadores_destinos SET $action=$estado WHERE idxid='$idxid'";
	$res = sqlsrv_query($conn,$update);
	if($res){
		echo 1;
	}
}elseif($_POST['band']==14){
	$idReceta = $_POST['idReceta'];
	$sql = "SELECT * FROM Recetas_produccion WHERE idReceta='$idReceta'";
	$res = sqlsrv_query($conn,$sql);
	while($aa = sqlsrv_fetch_array($res)){
		$Habilitado = $aa['Habilitado'];
	}
	if($Habilitado==0){
		$Habilitado=1;
	}else{
		$Habilitado=0;
	}
	$update = "UPDATE Recetas_produccion SET Habilitado=$Habilitado WHERE idReceta='$idReceta'";
	$res = sqlsrv_query($conn,$update);
	if($res){
		echo 1;
	}
}elseif($_POST['band']==15){
	$idxid = $_POST['idxid'];
	$estado = $_POST['estado'];
	if($estado==1){
		$estado=0;
	}else{
		$estado=1;
	}
	$update = "UPDATE EquiposGrupos SET AplicaGrupo=$estado WHERE idGrupo='$idxid'";
	$res = sqlsrv_query($conn,$update);
	if($res){
		echo 1;
	}
}elseif($_POST['band']==16){
	$idReceta = $_POST['idReceta'];
	$idClasificacion = $_POST['idClasificacion'];
	$idPila = $_POST['idPila'];
	$pila = $_POST['pila'];
	$porcentaje = $_POST['porcentaje'];
	$fechaInicio = $_POST['fechaInicio'];
	$fechaFin = $_POST['fechaFin'];
	if($fechaFin=='' || $fechaFin == null){
		$fechaFin = '1900-01-01';
	}
    if($pila != ''){
        $sql = "SELECT * FROM Pilas WHERE Descripcion='$pila'";
        $resultado=sqlsrv_query($conn,$sql,$params,$options);
        $filas=sqlsrv_num_rows($resultado);
        if($filas>0){
            while($aa = sqlsrv_fetch_array($resultado)){
                $idPilaNew = $aa['idPila'];
            }
            $sql_porcentaje = "SELECT ISNULL(SUM(porcentajePila),0) AS Sum_Porcentaje FROM recetas_produccion_pila WHERE idReceta='$idReceta' AND idClasificacion='$idClasificacion' AND fechaFin='1900-01-01'";
            $res = sqlsrv_query($conn,$sql_porcentaje);
            while($values = sqlsrv_fetch_array($res)){
            	$sum_procentaje = $values['Sum_Porcentaje'];
            }
            $sql = "SELECT porcentaje FROM Recetas_produccion_detalle WHERE idReceta='$idReceta' AND idClasificacion='$idClasificacion'";
            $res = sqlsrv_query($conn,$sql);
            while($values = sqlsrv_fetch_array($res)){
            	$total_procentaje = $values['porcentaje'];
            }
        }
    }
    if($idPila == '00000000-0000-0000-0000-000000000000'){
    	$var_sum = ($sum_procentaje+$porcentaje);
    }else{
    	$var_sum = $sum_procentaje;
    }
    //if($total_procentaje>=$var_sum){
    if($var_sum<=100){
	    if($pila != '' && $fechaInicio != '' && $fechaFin != ''){
	        //$igual_total++;
	        $sql = "SELECT * FROM Recetas_produccion_pila WHERE idReceta='$idReceta' AND idClasificacion='$idClasificacion' AND idPila='$idPilaNew' AND fechaFin='1900-01-01'";
	        $resultado=sqlsrv_query($conn,$sql,$params,$options);
	        $rows=sqlsrv_num_rows($resultado);
	        if($rows==0){
	            $insert = "INSERT INTO Recetas_produccion_pila (idReceta,idClasificacion,idPila,fechaInicio,fechaFin,porcentajePila) VALUES ('$idReceta','$idClasificacion','$idPilaNew','$fechaInicio','$fechaFin',$porcentaje)";
	            $res = sqlsrv_query($conn,$insert);
	            if($res){
	                $var++;
	            }
	        }else{
	            $update = "UPDATE Recetas_produccion_pila SET fechaFin='$fechaFin' WHERE idReceta='$idReceta' AND idClasificacion='$idClasificacion' AND idPila='$idPilaNew' AND fechaInicio='$fechaInicio'";
	            $res = sqlsrv_query($conn,$update);
	            if($res){
	                $var++;
	            }
	        }
	        echo $var;
	    }else{
	        //$Array_clasif[$position_clasif] = $idClasificacion;
	        //$position_clasif++;
	        echo 'asd';
	    }
	}else{
		echo 2;
	}
}
?>