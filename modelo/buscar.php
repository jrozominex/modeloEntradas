<?php 
error_reporting(0);
session_start();
include('conexion.php');
include ("../../clase_encrip.php");
if ($_POST['band'] == 1){
	$propietario = $_POST['propietario'];
	$mensaje = "";
	if (isset($propietario)) 
	{   $consulta = "SELECT Equipos.idEquipo, Equipos.Descripcion, Equipos.Identificacion FROM Equipos 
	        INNER JOIN EquiposGrupos ON Equipos.clase_equipo = EquiposGrupos.idGrupo
	        WHERE Equipos.idPropietario='$propietario'";
	    $params = array();
	    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
	    $resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
	    $filas=sqlsrv_num_rows($resultado);
	  //return($resultado);
	    if ($filas == 0) {
	        $mensaje = '<option value="0" selected>No hay maquinaria</option>';//"<p>No hay ningún usuario con ese nombre y/o apellido</p>";
	    }else{
	        //echo 'Resultados para <strong>'.$consultaBusqueda.'</strong>';
	        $mensaje .= '<option value="0"> --- Seleccione --- </option>';
	        while($resultados = sqlsrv_fetch_array($resultado)) {
	            $maquina = $resultados['Descripcion'].' - '.$resultados['Identificacion'];
	            $id = $resultados['idEquipo'];
	            //Output
	            $mensaje.= '<option value="'.$id.'">'.$maquina.'</option>';
	        }//Fin while $resultados
	    } //Fin else $filas
	}//Fin isset $consultaBusqueda
	//Devolvemos el mensaje que tomará jQuery
	echo $mensaje;
}elseif($_POST['band'] == 2){
	$sql = "SELECT * FROM Clasificacion ORDER BY Descripcion";
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$resultado=sqlsrv_query($conn,$sql,$params,$options);
	/********************************************************************************************************************/
	$select_evento='<div class="col-sm-6"><label>Clasificación</label><input type="text" id="select_clasif_recetas" list="evento_clasif" class="form-control" placeholder="Escriba una clasificación">
		<datalist id="evento_clasif">';
	while($aa = sqlsrv_fetch_array($resultado)){
		$Descripcion = utf8_encode($aa['Descripcion']);
		$select_evento.='<option value="'.$Descripcion.'"></option>';
	}
	$select_evento.='</datalist></div>';
	$select_evento.='<div class="col-sm-3"><label>Porcentaje %</label><input type="number" id="porcentaje_clasif_recetas" class="form-control" min="0"></div>';
	$select_evento.='<div class="col-sm-3"><button type="button" class="btn btn-success" style="margin-top: 15px;" onclick="agregar_clasif_recetas(\'0\')"><span class="glyphicon glyphicon-floppy-saved"></span></button></div>';
	echo $select_evento;
}elseif($_POST['band'] == 3){
	$text_clasif_recetas = explode('||',$_POST['text_clasif_recetas']);
	$text_porcentaje_clasif_recetas = explode('||',$_POST['text_porcentaje_clasif_recetas']);
	$inp_evento = $_POST['select_clasif_recetas'];
	$porcentaje_clasif_recetas = $_POST['porcentaje_clasif_recetas'];
	$Eliminar_lista = $_POST['Eliminar_lista'];
	/********************************************************************************************************************/
	if($Eliminar_lista!='0'){
		$position_eliminar = array_search($Eliminar_lista, $text_clasif_recetas);
		unset($text_clasif_recetas[$position_eliminar]);
		unset($text_porcentaje_clasif_recetas[$position_eliminar]);
		////// IMPLODE
		$text_clasif_recetas = implode("||", $text_clasif_recetas);
		$text_porcentaje_clasif_recetas = implode("||", $text_porcentaje_clasif_recetas);
		///// EXPLODE
		$text_clasif_recetas = explode("||", $text_clasif_recetas);
		$text_porcentaje_clasif_recetas = explode("||", $text_porcentaje_clasif_recetas);
	}
	$select_evento = '';
	$sum_procentaje = 0;
	for ($i=0; $i < count($text_porcentaje_clasif_recetas); $i++) { 
		$sum_procentaje+=$text_porcentaje_clasif_recetas[$i];
	}
	$sum_procentaje+=$porcentaje_clasif_recetas;

	if($inp_evento!=''){
		$sql_evento_clasif = "SELECT * FROM Clasificacion WHERE Descripcion='$inp_evento'";
		$params = array();
		$options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
		$resultado=sqlsrv_query($conn,$sql_evento_clasif,$params,$options);
		if($resultado>0){
			while($aa = sqlsrv_fetch_array($resultado)){
				$inp_evento = utf8_encode($aa['Descripcion']);
			}
		}else{
			$inp_evento = '';
		}
	}

	if($sum_procentaje<=100){
		if($text_clasif_recetas[count($text_clasif_recetas)-1] == ''){
			$text_porcentaje_clasif_recetas[count($text_clasif_recetas)-1] = $porcentaje_clasif_recetas;
			if($inp_evento!=''){
				$text_clasif_recetas[count($text_clasif_recetas)-1] = $inp_evento;
			}
		}else{
			$text_porcentaje_clasif_recetas[count($text_clasif_recetas)] = $porcentaje_clasif_recetas;
			if($inp_evento!=''){
				$text_clasif_recetas[count($text_clasif_recetas)] = $inp_evento;
			}
		}
		/********************************************************************************************************************/
		$band = 0;
		if(count($text_clasif_recetas)==1){
			if($text_clasif_recetas[$band] != ''){
				$new_array_evn[$band] = $text_clasif_recetas[$band];
			}else{
				$new_array_evn[$band] = '';
			}
			$band++;
		}else{
			for ($i=0; $i < count($text_clasif_recetas); $i++){
				if($text_clasif_recetas[$i] != ''){
					$new_array_evn[$band] = $text_clasif_recetas[$i];
					$band++;
				}
			}
		}
		$new_array_evn = implode("','", $new_array_evn);
		$sql = "SELECT * FROM Clasificacion WHERE Descripcion NOT IN ('$new_array_evn') ORDER BY Descripcion";
		$params = array();
		$options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
		$resultado=sqlsrv_query($conn,$sql,$params,$options);
		/********************************************************************************************************************/
		$select_evento='<div class="row"><div class="col-sm-6"><label>Clasificación</label><input type="text" id="select_clasif_recetas" list="evento_clasif" class="form-control" placeholder="Escriba una clasificación">
			<datalist id="evento_clasif">';
		while($aa = sqlsrv_fetch_array($resultado)){
			$Descripcion = utf8_encode($aa['Descripcion']);
			$VAR_VALIDATE = array_search($Descripcion, $text_clasif_recetas);
			if($VAR_VALIDATE==''){
				$select_evento.='<option value="'.$Descripcion.'"></option>';
			}
		}
		$select_evento.='</datalist></div>';
		$select_evento.='<div class="col-sm-3"><label>Porcentaje %</label><input type="number" id="porcentaje_clasif_recetas" class="form-control" min="0"></div>';
		$select_evento.='<div class="col-sm-3"><button type="button" class="btn btn-success" style="margin-top: 15px;" onclick="agregar_clasif_recetas(\'0\')"><span class="glyphicon glyphicon-floppy-saved"></span></button></div></div><br><div class="row form-group center-block" style="background-color: powderblue;">&nbsp;</div>';
		$num = 3;
		$select_evento.='<div class="row"><div class="col-sm-1"></div>';
		$select_evento.='<div class="col-sm-10">
		<label>'.$sum_procentaje.'% TOTAL ACUMULADO</label></div>';
		$select_evento.='</div>';
		for ($i=0; $i < count($text_clasif_recetas); $i++){
			if($text_clasif_recetas[$i] != ''){
				//if($num == 3){
					$num = 0;
					$select_evento.='<div class="row"><div class="col-sm-1"></div>';
				//}
				$select_evento.='<div class="col-sm-10">
				<button type="button" class="btn btn-warning btn-xs" onclick="agregar_clasif_recetas(\''.$text_clasif_recetas[$i].'\')"><span class="glyphicon glyphicon-trash"></span></button>
				<label>'.$text_porcentaje_clasif_recetas[$i].'% '.$text_clasif_recetas[$i].'</label></div>';
				$num++;
				//if($num == 3){
					$select_evento.='</div>';
				//}
			}
		}
		$text_porcentaje_clasif_recetas = implode("||", $text_porcentaje_clasif_recetas);
		$text_clasif_recetas = implode("||", $text_clasif_recetas);
	}
	echo $select_evento.'!!'.$text_clasif_recetas.'!!'.$text_porcentaje_clasif_recetas.'!!'.$sum_procentaje;
}elseif($_POST['band'] == 4){
	$inserto = 0;
	$text_clasif_recetas = explode('||',$_POST['text_clasif_recetas']);
	$text_porcentaje_clasif_recetas = explode('||',$_POST['text_porcentaje_clasif_recetas']);
	$nombre_receta_produccion = $_POST['nombre_receta_produccion'];
	$mezcla_producida = $_POST['mezcla_producida'];
	$pila_receta=$_POST['pila_receta'];
	$permite_crear=$_POST['permite_crear'];
	$empresa = $_POST['empresa'];
	$patio = $_POST['patio'];
	//$destino_mezcla_producida = $_POST['destino_mezcla_producida'];
	$usuario = $_SESSION['usuario'];
	$Fecha = date('Y-m-d');

	$sql_pila="SELECT * FROM Pilas WHERE Descripcion='$pila_receta'";
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$resultado=sqlsrv_query($conn,utf8_decode($sql_pila),$params,$options);
	$filas=sqlsrv_num_rows($resultado);
	if($filas>0){
		while($aa=sqlsrv_fetch_array($resultado)){
			$idPila=$aa['idPila'];
		}
	}else{
		if($permite_crear==0){
			echo 'nnn';
			break;
		}else{
			$sql_newid="SELECT NEWID() AS NEWID";
			$res=sqlsrv_query($conn,$sql_newid);
			while($aa=sqlsrv_fetch_array($res)){
				$idPila=$aa['NEWID'];
			}
			$insert_pila="INSERT INTO Pilas VALUES ('$idPila','$pila_receta',CAST(GETDATE() AS date),'1900-01-01')";
			$res = sqlsrv_query($conn,utf8_decode($insert_pila));
		}
	}

	$var_total_porcentaje = 0;
	for ($i=0; $i < count($text_porcentaje_clasif_recetas); $i++) { 
		$var_total_porcentaje+=$text_porcentaje_clasif_recetas[$i];
	}
	if($var_total_porcentaje==100){
		if(count($text_clasif_recetas)>0){
			$SQL_NEWID = "SELECT NEWID() AS NEWID";
			$res = sqlsrv_query($conn,$SQL_NEWID);
			while($aa = sqlsrv_fetch_array($res)){
				$idReceta = $aa['NEWID'];
			}
			$INSERT = "INSERT INTO Recetas_produccion (idReceta,idEmpresa,idDestino,Descripcion,idClasificacion,idPila,FechaRegistro,idUsuario,Habilitado) 
				VALUES ('$idReceta','$empresa','$patio','$nombre_receta_produccion','$mezcla_producida','$idPila','$Fecha','$usuario',1)";
			$res = sqlsrv_query($conn,$INSERT);
			if($res){
				for ($i=0; $i < count($text_clasif_recetas); $i++){
					if($text_clasif_recetas[$i] != ''){
						$SQL = "SELECT * FROM Clasificacion WHERE Descripcion='$text_clasif_recetas[$i]'";
						$params = array();
						$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
						$resultado=sqlsrv_query($conn,utf8_decode($SQL),$params,$options);
						while($aa = sqlsrv_fetch_array($resultado)){
							$idClasificacion = $aa['idClasificacion'];
						}
						$INSERT_DETALLE = "INSERT INTO Recetas_produccion_detalle VALUES ('$idReceta','$idClasificacion','$text_porcentaje_clasif_recetas[$i]')";
						$res_detalle = sqlsrv_query($conn,$INSERT_DETALLE);
						if($res_detalle){
							$inserto++;
						}
					}
				}
			}
		}
	}else{
		echo '';
	}
	if($inserto == count($text_clasif_recetas)){
		echo 1;
	}else{
		echo 2;
	}
}elseif($_POST['band'] == 5){
	$destino_codigo = ENCR::descript($_POST['destino_codigo']);
	$codigo = $_POST['codigo'];
	$insert = "INSERT INTO Destino_codigos (idDestino, codigo_destino) VALUES ('$destino_codigo','$codigo')";
	$res = sqlsrv_query($conn,$insert);
	if($res){
		echo 1;
	}else{
		echo 0;
	}
}elseif($_POST['band'] == 6){
	$idDestino = $_POST['idDestino'];
	$select_actividad = '<option value="0" selected="" disabled="">Seleccione</option>';
	$sql = "SELECT * FROM Actividades WHERE idTipoActividad='00000000-0000-0000-0000-000000000001' 
		AND idActividad NOT IN (SELECT idActividad FROM Actividades_cargadores_destinos WHERE idDestino='$idDestino') 
		ORDER BY Descripcion";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
    $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $filas=sqlsrv_num_rows($resultado);
    if($filas>0){
    	while ($aa = sqlsrv_fetch_array($resultado)) {
    		$select_actividad.='<option value="'.$aa['idActividad'].'">'.utf8_encode($aa['Descripcion']).'</option>';
    	}
    }
    echo $select_actividad;

}elseif($_POST['band'] == 7){
	$id_empresa = ENCR::descript($_POST['id_empresa']);
	$codigo_empresa = $_POST['codigo_empresa'];
	$sql = "SELECT * FROM Empresa_codigos where idEmpresa='$id_empresa'";
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
    $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $filas=sqlsrv_num_rows($resultado);
    if($filas==0){
    	$ejecutar= "INSERT INTO Empresa_codigos (idEmpresa,codigo_empresa) VALUES('$id_empresa','$codigo_empresa')";
    	$resultado=sqlsrv_query($conn,utf8_decode($ejecutar),$params,$options);
    	echo 1;
    }else
    	echo 0;
}
?>