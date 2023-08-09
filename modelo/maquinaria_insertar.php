<?php
include('conexion.php');
session_start();
$idUsuario = $_SESSION['idUsuario'];
// VARIABLES GLOBALES
$fecha_registro = date('Y-m-d H:i:s');
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
if($_POST['band']==0){
	$echo = '';
	$sql = "SELECT Proveedores.RazonSocial,Proveedores.NombreCorto,Equipos.idPropietario,
		EquiposGrupos.Descripcion as Grupo_equipo,EquiposGrupos.idGrupo,Equipos.Descripcion + '-' + Equipos.Identificacion as Equipo,
		Equipos.idEquipo,Equipos.horometro_final,Equipos.horometro_vida_util, Equipos.horometro_mantto, EquiposGrupos.AplicaGrupo
    FROM Equipos 
        INNER JOIN proveedores ON Equipos.idPropietario=proveedores.idProveedor 
        INNER JOIN EquiposGrupos ON Equipos.clase_equipo = EquiposGrupos.idGrupo 
    ORDER BY Proveedores.RazonSocial, EquiposGrupos.Descripcion, Equipos.Descripcion";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);
    if($rows>0){
        while($eq = sqlsrv_fetch_array($resul)){
        $man =  $eq['horometro_mantto']-$eq['horometro_vida_util'];
    	$echo.='<tr>
                <td style="vertical-align: middle;">'.utf8_encode($eq['RazonSocial']).'</td>
                <td style="vertical-align: middle;">'.$eq['Grupo_equipo'].'</td>
                <td style="vertical-align: middle;">'.$eq['Equipo'].'</td>
                <td style="vertical-align: middle;">
                    <center>
                        <button class="btn btn-xs" data-toggle="modal" data-target="#modalActivities" onclick="load_activities(\''.$eq['idEquipo'].'\')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </center>
                </td>
                <td style="vertical-align: middle;">
                    <center>
                        <button class="btn btn-xs" data-toggle="modal" data-target="#modalActivities" onclick="load_activities(\''.$eq['idEquipo'].'\')">
                            <span class="glyphicon glyphicon-folder-open"></span>
                        </button>
                    </center>
                </td>
                <td style="vertical-align: middle;"><center>';
                if($eq['AplicaGrupo']==0){
                	$echo.='N/A';
                	//$echo.='<button class="btn btn-xs"><span class="glyphicon glyphicon-paperclip"></span></button>';
                	//$echo.='<button class="btn btn-xs" onclick="vincular_equipo(\''.$eq['idEquipo'].'\')"><span class="glyphicon glyphicon-paperclip"></span></button>';
                }else{
                	$echo.='<button class="btn btn-xs btn-primary" onclick="vincular_equipo(\''.$eq['idEquipo'].'\')"><span class="glyphicon glyphicon-paperclip"></span></button>';
                }
                $echo.='</center></td><td style="vertical-align: middle;">
                    <center>
                        '.number_format($eq['horometro_final'],1,',','.').'<br>
                        <button title="Reiniciar horometro." class="btn btn-default btn-xs navbar-right" style="margin-right: 3px;" data-toggle="modal" data-target="#modalConfirmar" onclick="ver(\''.$eq['idEquipo'].'\')">
                            <span class="glyphicon glyphicon-refresh"></span>
                        </button>
                        <button title="Horometro maquinaria." class="btn btn-danger btn-xs navbar-right" style="margin-right: 3px;" data-toggle="modal" data-target="#modalHoroFinal" onclick="ver1(\''.$eq['idEquipo'].'\')">Horo.</button>
                    </center>
                </td>
                <td style="vertical-align: middle;">'.number_format($eq['horometro_vida_util'],1,',','.').'</td>
                <td style="vertical-align: middle;">';
                if ($eq['idPropietario'] == '4A442AA8-6532-4F4F-8CED-51A6999DDB5E'){
                    if ($man <= 0){
                    $echo.='<button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#ModalMantto1" onclick="verHoroMantto(\''.$eq['idEquipo'].'\')">Horo.</button>';
                    }else{
                        $echo.=number_format($man,1,',','.');
                    }
                }else{
                    $echo.='<center>N/A</center>';
                }
                $echo.='</td>
            </tr>';
        }
    }else{
    	$echo.='<center>No hay Maquinaria / Equipos registrados</center>';
    }
    echo $echo;
}elseif ($_POST['band'] == 1){
	$propietario = $_POST['propietario'];
	$marca = $_POST['marca'];
	//$modelo = $_POST['modelo'];
	$id = $_POST['id'];
	$grupo = $_POST['grupo'];
	$horometro_ini = '0';
	$horometro_mantto = '0';
	//$mantto = $horometro_ini + $horometro_mantto;
	$mantto = '0';

	$sql_NEWID = "SELECT NEWID() AS NEWID";
	$res = sqlsrv_query($conn,$sql_NEWID);
	while($aa = sqlsrv_fetch_array($res)){
		$NEWID = $aa['NEWID'];
	}

	$sql = "INSERT INTO Equipos (idEquipo, Descripcion, Identificacion, idPropietario,clase_equipo,horometro_final,horometro_vida_util,horometro_mantto) 
		VALUES ('$NEWID','$marca','$id','$propietario','$grupo','$horometro_ini','$horometro_ini','$mantto')";
	$result = sqlsrv_query($conn,$sql);
	if($result){
								// ASOCIAR LA MAQUINA A UN GRUPO //
		$sql = "INSERT INTO EquipoGrupoDetalle (idEquipo, idGrupo) VALUES ('$NEWID','$grupo')";
		$result = sqlsrv_query($conn,$sql);
		if($result){
			echo 1;
		}
	}
}elseif ($_POST['band'] == 2){
	$equipo = $_POST['registro'];
	$sql = "UPDATE detalle_equipos SET horometro_final='0' WHERE idEquipos='$equipo'";
	$res = sqlsrv_query($conn,$sql);
	if ($res){
		echo 1;
	}
}elseif ($_POST['band'] == 3){
	$equipo = $_POST['registro'];
	$horos = $_POST['contrasena'];
	$sql = "UPDATE detalle_equipos SET horometro_final='$horos', horometro_vida_util='$horos', horometro_mantto='0' WHERE idEquipos='$equipo'";
	$res = sqlsrv_query($conn,$sql);
	if ($res){
		echo 1;
	}
}elseif($_POST['band'] == 4){
	$equipo = $_POST['equipo'];
	$horometro = $_POST['horometro'];
	$sql = "SELECT * FROM detalle_equipos WHERE idEquipos='$equipo'";
	$res = sqlsrv_query($conn,$sql);
	while($detalle = sqlsrv_fetch_array($res)){
		$horo_vida = $detalle['horometro_vida_util'];
	}
	$horo_total = $horo_vida+$horometro;
	$sql1 = "UPDATE detalle_equipos SET horometro_mantto='$horo_total' WHERE idEquipos='$equipo'";
	$res1 = sqlsrv_query($conn,$sql1);
	if($res1){
		echo 1;
	}
}elseif($_POST['band'] == 5){
	$echo = '';
	$idEquipos = $_POST['idEquipos'];
	$sql = "SELECT Actividades.Descripcion, Actividades.idActividad, equiposActividad.idEquipo FROM equiposActividad 
		INNER JOIN Actividades ON EquiposActividad.idActividad=Actividades.idActividad WHERE idEquipo='$idEquipos'";
	$resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	$filas=sqlsrv_num_rows($resultado);
	if($filas>0){
		$echo.='<table class="table table-bordered"><tr><td colspan="2">Actividad Asignada</td></tr>';
		while($aa = sqlsrv_fetch_array($resultado)){
			$idActividad = "'".$aa['idActividad']."'";
			$idEquipo = "'".$aa['idEquipo']."'";
			$echo.='<tr><td><center>'.utf8_encode($aa['Descripcion']).'</center></td><td><center><button class="btn btn-default" onclick="delete_activitie_machine('.$idEquipo.','.$idActividad.')"><span class="glyphicon glyphicon-trash"></span></button></center></td></tr>';
		}
	}else{
		$echo.='<center><h4>No hay actividades asignadas</h4></center>';
	}
	echo $echo;
}elseif($_POST['band'] == 6){
	$echo = '';
	$idEquipos = $_POST['idEquipos'];
	$sql = "SELECT * FROM Actividades WHERE idTipoActividad='00000000-0000-0000-0000-000000000001' AND 
	idActividad NOT IN (SELECT idActividad FROM EquiposActividad WHERE idEquipo='$idEquipos')";
	$resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	$filas=sqlsrv_num_rows($resultado);
	if($filas>0){
		while($aa = sqlsrv_fetch_array($resultado)){
			$echo.='<option value="'.$aa['idActividad'].'">'.utf8_encode($aa['Descripcion']).'</td></tr>';
		}
	}else{
		$echo ='<option>Ya están asignadas</option>';
	}
	echo $echo;
}elseif($_POST['band'] == 7){
	$idEquipo_actividad = $_POST['idEquipo_actividad'];
	$actividad_maquinas = $_POST['actividad_maquinas'];
	$insert = "INSERT INTO equiposActividad (idEquipo, idActividad) VALUES ('$idEquipo_actividad','$actividad_maquinas')";
	$res = sqlsrv_query($conn,$insert);
	if($res){
		echo 1;
	}
}elseif($_POST['band'] == 8){
	$idEquipo = $_POST['idEquipo'];
	$idActividad = $_POST['idActividad'];
	$delete = "DELETE FROM equiposActividad WHERE idEquipo='$idEquipo' AND idActividad='$idActividad'";
	$res = sqlsrv_query($conn,$delete);
	if($res){
		echo 1;
	}
}elseif($_POST['band'] == 9){
	$echo = '<option value="0" selected="" disabled="">Seleccione</option>';
	$grupo = $_POST['grupo'];
	$sql = "SELECT Proveedores.* FROM ProveedoresGrupos 
		INNER JOIN Proveedores ON ProveedoresGrupos.idProveedor=Proveedores.idProveedor
		WHERE idAgrupacion IN (SELECT idAgrupacion FROM EquiposGrupos_Agrupaciones 
			WHERE idgrupo='$grupo')
		ORDER BY Proveedores.RazonSocial";
	$resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	$filas=sqlsrv_num_rows($resultado);
	if($filas>0){
		while($aa = sqlsrv_fetch_array($resultado)){
			$echo.='<option value="'.$aa['idProveedor'].'">'.utf8_encode($aa['RazonSocial']).'</td></tr>';
		}
	}else{
		$echo ='<option>No hay proveedores de equipos</option>';
	}
	echo $echo;
}elseif($_POST['band'] == 10){
	$idEquipo = $_POST['idEquipo'];
	$echo = '';
	$sql_equipo = "SELECT * FROM Equipos WHERE idEquipo='$idEquipo'";
	$res = sqlsrv_query($conn,$sql_equipo);
	while($aa = sqlsrv_fetch_array($res)){
		$idEquipo = $aa['idEquipo'];
		$NombreEquipo = utf8_encode($aa['Descripcion']).'-'.$aa['Identificacion'];
		$echo.='<input type="hidden" id="idEquipo_agrupacion" value="'.$idEquipo.'">';
	}
	$echo.='<center><h3 style="margin-top: -5px">'.$NombreEquipo.'</h3></center>';

	$sql = "SELECT idEquipoVinculado, Equipos_2.Descripcion + '-' + Equipos_2.Identificacion AS NombreEquipoVinculado 
		FROM Equipos INNER JOIN detalle_equipos ON Equipos.idEquipo=detalle_equipos.idEquipo 
			INNER JOIN Equipos AS Equipos_2 ON detalle_equipos.idEquipoVinculado=Equipos_2.idEquipo
		WHERE Equipos.idEquipo='$idEquipo'";
	$resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	$filas=sqlsrv_num_rows($resultado);
	if($filas>0){
	$echo.='<div class="row">
				<div class="col-sm-12">
					<table class="table table-bordered">
						<tr>
							<td colspan="2">Equipo Asignada</td>
						</tr>';
		while($aa = sqlsrv_fetch_array($resultado)){
			$NombreEquipoVinculado = $aa['NombreEquipoVinculado'];
			$idEquipoVinculado = $aa['idEquipoVinculado'];
			$echo.='<tr>';
			$echo.='	<td>'.$NombreEquipoVinculado.'</td>';
			$echo.='	<td>
							<center>
								<button class="btn btn-default" onclick="delete_group_machine(\''.$idEquipo.'\',\''.$idEquipoVinculado.'\')">
									<span class="glyphicon glyphicon-trash"></span>
								</button>
							</center>
						</td>';
			$echo.='</tr>';
			
		}
	$echo.='		</table>
				</div>
			</div>';
	}
	echo $echo;
}elseif($_POST['band'] == 11){
	$idEquipo_agrupacion = $_POST['idEquipo_agrupacion'];
	$listGrupo_agrupacion = $_POST['listGrupo_agrupacion'];
	$echo = '';
	$sql = "SELECT * FROM Equipos WHERE clase_equipo='$listGrupo_agrupacion' 
		AND idEquipo!='$idEquipo_agrupacion' AND idEquipo NOT IN (SELECT idEquipoVinculado FROM detalle_equipos WHERE idEquipo='$idEquipo_agrupacion')
		ORDER BY Descripcion,Identificacion";
	$resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	$filas=sqlsrv_num_rows($resultado);
	if($filas>0){
		$echo='<option value="0" selected disabled>Seleccione</option>';
		while($aa = sqlsrv_fetch_array($resultado)){
			$echo.='<option value="'.$aa['idEquipo'].'">'.utf8_encode($aa['Descripcion'].'-'.$aa['Identificacion']).'</option>';
		}
	}else{
		$echo='<option value="0">No hay equipos</option>';
	}
	echo $echo;
}elseif($_POST['band'] == 12){
	$listEquipo_vinculado = $_POST['listEquipo_vinculado'];
	$idEquipo_agrupacion = $_POST['idEquipo_agrupacion'];
	
	$insert = "INSERT INTO detalle_equipos (idEquipo,idEquipoVinculado) VALUES ('$idEquipo_agrupacion','$listEquipo_vinculado')";
	$res = sqlsrv_query($conn,$insert);
	if($res){
		echo 1;
	}
}elseif($_POST['band'] == 13){
	$idEquipo = $_POST['idEquipo'];
	$idEquipoVinculado = $_POST['idEquipoVinculado'];

	$delete = "DELETE FROM detalle_equipos WHERE idEquipo='$idEquipo' AND idEquipoVinculado='$idEquipoVinculado'";
	$res = sqlsrv_query($conn,$delete);
	if($res){
		echo 1;
	}
}
?>