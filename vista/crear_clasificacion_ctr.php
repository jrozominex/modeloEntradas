<?php
include('../modelo/conexion.php');
session_start();

if(isset($_POST['band'])){
	if($_POST['band']=='cargar_estructura'){
		$echo='<div class="row">';
		$echo.='<div class="col-sm-3">
			<label>Material:</label>
			<select class="form-control" id="material"></select>
			</div>';
		$echo.='<div class="col-sm-3">
			<label>Producto:</label>
			<select class="form-control" id="producto"></select>
			</div>';
			$sql="SELECT * FROM GruposParametros_new ORDER BY Orden";
			$res=sqlsrv_query($conn,$sql);
				while($rows=sqlsrv_fetch_array($res)){
					$idGrupoParametro=$rows['idGrupoParametro'];
					$Descripcion=$rows['Descripcion'];

					$echo.='<div class="col-sm-3">
						<label>'.$Descripcion.':</label>
						<select class="form-control" id="'.$idGrupoParametro.'">';
						$sql2="SELECT * FROM Parametros_new WHERE idGrupoParametro='$idGrupoParametro' ORDER BY orden";
						$res2=sqlsrv_query($conn,$sql2);
						while($rows2=sqlsrv_fetch_array($res2)){
							$idParametro=$rows2['idParametro'];
							$Descripcion2=$rows2['Descripcion'];
							$echo.='<option value="'.$idParametro.'">'.$Descripcion2.'</option>';
						}
					$echo.='</select>
						</div>';
				}
		$echo.='</div>';

	}
	echo $echo;
}

?>