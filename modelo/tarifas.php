<?php
include('conexion.php');
session_start();
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
if(!isset($_SESSION['Array_empresa']['TARIFA_CARGADORES'])){
    ?>
  <script type="text/javascript">
      self.location='Admin.php';
      alert('Se ha suspendido la sesión');
  </script>
  <?php
}?>
<?php
if($_POST['band']==0){
	$select_liquidador = $_POST['select_liquidador'];
	$echo = '';
	if($select_liquidador==-1){
		$echo.='<button class="btn btn-success navbar-right" style="margin-right: 15px;" onclick="open_modal_tarifas()">Crear tarifa <span class="glyphicon glyphicon-plus"></span></button>
        <br><br>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <table id="example1" class="table table-hover table-condensed table-bordered table-responsive table-striped">
                        <thead>
                            <th>Historial</th>
                            <th>Proveedor</th>
                            <th>Maquinaria</th>
                            <th>Vigente Desde</th>
                            <th>Vigente Hasta</th>
                            <th>Tarifa TM</th>
                            <th>Tarifa Tiempo</th>
                            <th>Iva</th>
                        </thead>
                        <tbody id="tbody_tarifas"></tbody>
                </table>
            </div>
        </div>';
	}else{
		$echo.='<button class="btn btn-success navbar-right" style="margin-right: 15px;" onclick="open_modal_tarifas()">Crear tarifa <span class="glyphicon glyphicon-plus"></span></button>
        <br><br>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <table id="example1" class="table table-hover table-condensed table-bordered table-responsive table-striped">
                        <thead>
                            <th>Historial</th>
                            <th>Proveedor</th>
                            <th>Reporte</th>
                            <th>Vigente Desde</th>
                            <th>Vigente Hasta</th>
                            <th>Tarifa</th>
                        </thead>
                        <tbody id="tbody_tarifas"></tbody>
                </table>
            </div>
        </div>';
	}
	echo $echo;
}elseif ($_POST['band'] == 1){
	$Fecha_Registro = date('Y-m-d H:i:s');
	$equipo = $_POST['equipo'];
	$proveedor = $_POST['proveedor'];
	$vigente_desde = $_POST['vigente_desde'];
	$iva = $_POST['iva'];
	$tipo_tarifa = $_POST['tipo_tarifa'];
	$trf_tm = $_POST['trf_tm'];
	$trf_horo = $_POST['trf_horo'];

	 $sql = "SELECT * FROM TarifaMaquinaria
            INNER JOIN Equipos ON TarifaMaquinaria.idEquipo = Equipos.idEquipo
            WHERE TarifaMaquinaria.idEquipo='$equipo' AND TarifaMaquinaria.Fecha_Hasta = '1900-01-01'";
    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows2=sqlsrv_num_rows($res);
    if ($rows2 > 0) 
	{	while($values = sqlsrv_fetch_array($res)){
			$fecha_ant = date_format($values['Fecha_Desde'],'Y-m-d');
		}
		if($vigente_desde > $fecha_ant){
			$vigente_hasta = date('Y-m-d',strtotime($vigente_desde . ' - 1 days'));
			$sql = "UPDATE TarifaMaquinaria SET Fecha_Hasta='$vigente_hasta' WHERE idEquipo='$equipo' AND Fecha_Hasta='1900-01-01'";
			$result = sqlsrv_query($conn,$sql);
			if ($result){
				$sql = "INSERT INTO TarifaMaquinaria(idProveedor,idEquipo, Fecha_Desde, Fecha_Hasta, Tarifa_Toneladas, Tarifa_Horometro, Fecha_Registro, Tipo_Tarifa, Iva) 
					VALUES ('$proveedor','$equipo','$vigente_desde','1900-01-01','$trf_tm','$trf_horo','$Fecha_Registro','$tipo_tarifa','$iva')";
				$result = sqlsrv_query($conn,$sql);
				if ($result){
					echo 1;
				}
			}
		}else{
			echo 2;
		}
	}else{
		$sql = "INSERT INTO TarifaMaquinaria(idProveedor,idEquipo, Fecha_Desde, Fecha_Hasta, Tarifa_Toneladas, Tarifa_Horometro, Fecha_Registro, Tipo_Tarifa, Iva) 
				VALUES ('$proveedor','$equipo','$vigente_desde','1900-01-01','$trf_tm','$trf_horo','$Fecha_Registro','$tipo_tarifa','$iva')";
		$result = sqlsrv_query($conn,$sql);
		if ($result){
			echo 1;
		}
	}
}elseif($_POST['band'] == 2){
	$select_liquidador = $_POST['select_liquidador'];
	$Nombre='';
	if($select_liquidador==-1){	
		$equipo = $_POST['equipo'];
		$echo='<center><label id="machine"></label></center>
                    <table class="table table-hover table-condensed table-bordered table-responsive table-striped">
                        <thead id="table_head">
                            <th>Vigente Desde</th>
                            <th>Vigente Hasta</th>
                            <th>Tarifa TM</th>
                            <th>Tarifa Tempo</th>
                            <th>Iva</th>
                        </thead>
                		<tbody id="table_history">';
		$sql = "SELECT * FROM TarifaMaquinaria
	            INNER JOIN Equipos ON TarifaMaquinaria.idEquipo = Equipos.idEquipo
	            WHERE TarifaMaquinaria.idEquipo='$equipo' AND TarifaMaquinaria.Fecha_Hasta != '1900-01-01'
	            ORDER BY TarifaMaquinaria.Fecha_Desde DESC";
	    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	    $rows2=sqlsrv_num_rows($res);
	    if($rows2>0){ 	
	    	$json = array();
			while($row = sqlsrv_fetch_array($res)){
				if(date_format($row['Fecha_Hasta'],'Y-m-d') == '1900-01-01'){
					$fecha_ult = 'INDEFINIDO';
				}else{
					$fecha_ult = date_format($row['Fecha_Hasta'],'Y-m-d');
				}
				$Nombre = $row['Descripcion']."-".$row['Identificacion'];
				$echo.='<tr>';
				$echo.='<td>'.date_format($row['Fecha_Desde'],'Y-m-d').'</td>';
				$echo.='<td>'.$fecha_ult.'</td>';
				$echo.='<td>'.number_format($row['Tarifa_Toneladas'],0,',','.').'</td>';
				$echo.='<td>'.number_format($row['Tarifa_Horometro'],0,',','.').'</td>';
				$echo.='<td>'.$row['Iva'].'</td>';
				$echo.='</tr>';
			}
			$echo.='</tbody>
            </table>';
		}else{
			$echo='<div clas="col-sm-4"></div><div clas="col-sm-4"><center><h5>--- NO HAY DATOS DISPONIBLES ---</h5></center></div>';
		}
	}else{
		$idLiquidacion = $_POST['idLiquidacion'];
		$id = $_POST['id'];
		$idProveedor = $_POST['idProveedor'];
		$echo='<center><label id="machine"></label></center>
                    <table class="table table-hover table-condensed table-bordered table-responsive table-striped">
                        <thead id="table_head">
                            <th>Proveedor</th>
                            <th>Reporte</th>
                            <th>Vigente Desde</th>
                            <th>Vigente Hasta</th>
                            <th>Tarifa</th>
                        </thead>
                		<tbody id="table_history">';
        $sql = "SELECT DocumentoLiquidacionTarifas.idLiquidacion,Liquidaciones.Descripcion AS ModoLiquidacion,DocumentoLiquidacionTarifas.id,qualityReportTree.name AS Reporte,
DocumentoLiquidacionTarifas.idProveedor,Proveedores.RazonSocial AS Proveedor,DocumentoLiquidacionTarifas.FechaDesde,DocumentoLiquidacionTarifas.FechaHasta,DocumentoLiquidacionTarifas.Tarifa
FROM DocumentoLiquidacionTarifas INNER JOIN Liquidaciones() AS Liquidaciones ON DocumentoLiquidacionTarifas.idLiquidacion=Liquidaciones.idLiquidacion
INNER JOIN qualityReportTree ON DocumentoLiquidacionTarifas.id=qualityReportTree.id
INNER JOIN Proveedores ON DocumentoLiquidacionTarifas.idProveedor=Proveedores.idProveedor
	WHERE DocumentoLiquidacionTarifas.FechaHasta!='1900-01-01' AND DocumentoLiquidacionTarifas.idLiquidacion='$select_liquidador' AND DocumentoLiquidacionTarifas.id='$id' AND DocumentoLiquidacionTarifas.idProveedor='$idProveedor'
	ORDER BY Proveedores.RazonSocial,qualityReportTree.name";
		$res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	    $rows2=sqlsrv_num_rows($res);
	    if($rows2>0){ 	
	    	$json = array();
			while($row = sqlsrv_fetch_array($res)){
				if(date_format($row['FechaHasta'],'Y-m-d') == '1900-01-01'){
					$fecha_ult = 'INDEFINIDO';
				}else{
					$fecha_ult = date_format($row['FechaHasta'],'Y-m-d');
				}
				$Nombre = utf8_encode($row['ModoLiquidacion']);
				$echo.='<tr>';
				$echo.='<td>'.utf8_encode($row['Proveedor']).'</td>';
				$echo.='<td>'.utf8_encode($row['Reporte']).'</td>';
				$echo.='<td>'.date_format($row['FechaDesde'],'Y-m-d').'</td>';
				$echo.='<td>'.$fecha_ult.'</td>';
				$echo.='<td>'.number_format($row['Tarifa'],0,',','.').'</td>';
				$echo.='</tr>';
			}
			$echo.='</tbody>
            </table>';
		}else{
			$echo='<div clas="col-sm-4"></div><div clas="col-sm-4"><center><h5>--- NO HAY DATOS DISPONIBLES ---</h5></center></div>';
		}
	}
	echo $Nombre.'||'.$echo;
}elseif($_POST['band'] == 3){ //CARGA TODAS LAS TARIFAS VIGENTES
	$select_liquidador = $_POST['select_liquidador'];
	if($select_liquidador==-1){
		$sql = "SELECT Proveedores.RazonSocial,Equipos.idEquipo,Equipos.Descripcion,Equipos.Identificacion,TarifaMaquinaria.Tarifa_Horometro,Tarifa_Toneladas,Iva,Fecha_Desde,Fecha_Hasta FROM TarifaMaquinaria
	            INNER JOIN Equipos ON TarifaMaquinaria.idEquipo = Equipos.idEquipo 
	            INNER JOIN Proveedores ON Equipos.idPropietario=Proveedores.idProveedor
	            WHERE TarifaMaquinaria.Fecha_Hasta = '1900-01-01' ORDER BY Proveedores.RazonSocial";
	    $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	    $rows=sqlsrv_num_rows($resultado);
	    $echo = '';
	    if($rows > 0){
	    	while($data = sqlsrv_fetch_array($resultado)){
	            $echo.='<tr>';
	            $echo.='<td><center><button class="btn btn-dark" data-toggle="modal" data-target="#Historial" onclick="historial(\''.$data['idEquipo'].'\')"><span class="glyphicon glyphicon-eye-open"></span></button></center>
	                </td>';
	            $echo.='<td>'.utf8_encode($data['RazonSocial']).'</td>';
	            $echo.='<td>'.$data['Descripcion'].' '.$data['Identificacion'].'</td>';
	            $echo.='<td>'.date_format($data['Fecha_Desde'],'Y-m-d').'</td>';
	            $echo.='<td><center>VIGENTE</center></td>';
	            $echo.='<td>'.number_format($data['Tarifa_Toneladas'],0,',','.').'</td>';
	            $echo.='<td>'.number_format($data['Tarifa_Horometro'],0,',','.').'</td>';
	            $echo.='<td>'.$data['Iva'].'%</td>';
	            $echo.='</tr>';
	        }
	    }else{
	        $echo.='<center><b>No hay tarifas por el momento.</b></center>';
	    }
	}else{
		$sql="SELECT DocumentoLiquidacionTarifas.idLiquidacion,Liquidaciones.Descripcion AS ModoLiquidacion,DocumentoLiquidacionTarifas.id,qualityReportTree.name AS Reporte,
DocumentoLiquidacionTarifas.idProveedor,Proveedores.RazonSocial AS Proveedor,DocumentoLiquidacionTarifas.FechaDesde,DocumentoLiquidacionTarifas.FechaHasta,DocumentoLiquidacionTarifas.Tarifa
FROM DocumentoLiquidacionTarifas INNER JOIN Liquidaciones() AS Liquidaciones ON DocumentoLiquidacionTarifas.idLiquidacion=Liquidaciones.idLiquidacion
INNER JOIN qualityReportTree ON DocumentoLiquidacionTarifas.id=qualityReportTree.id
INNER JOIN Proveedores ON DocumentoLiquidacionTarifas.idProveedor=Proveedores.idProveedor
	WHERE DocumentoLiquidacionTarifas.FechaHasta='1900-01-01' AND DocumentoLiquidacionTarifas.idLiquidacion='$select_liquidador'
	ORDER BY Proveedores.RazonSocial,qualityReportTree.name";
	    $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	    $rows=sqlsrv_num_rows($resultado);
	    $echo = '';
	    if($rows > 0){
	    	while($data = sqlsrv_fetch_array($resultado)){
	    		$array_data = $data['idLiquidacion'].'||'.$data['id'].'||'.$data['idProveedor'];
	            $echo.='<tr>';
	            $echo.='<td><center><button class="btn btn-dark" data-toggle="modal" data-target="#Historial" onclick="historial(\''.$array_data.'\')"><span class="glyphicon glyphicon-eye-open"></span></button></center>
	                </td>';
	            $echo.='<td>'.utf8_encode($data['Proveedor']).'</td>';
	            $echo.='<td>'.utf8_encode($data['Reporte']).'</td>';
	            $echo.='<td>'.date_format($data['FechaDesde'],'Y-m-d').'</td>';
	            $echo.='<td><center>VIGENTE</center></td>';
	            $echo.='<td>'.number_format($data['Tarifa'],0,',','.').'</td>';
	            $echo.='</tr>';
	        }
	    }else{
	        $echo.='<center><b>No hay tarifas por el momento.</b></center>';
	    }
	}
    echo $echo;
}elseif($_POST['band'] == 4){ //CARGA DATOS DEL MODAL PARA REGISTRO DE TARIFAS
	$select_liquidador = $_POST['select_liquidador'];
	if($select_liquidador==-1){
		$echo = '<div class="modal-body">
	                <div class="row">
	                    <div class="col-sm-3">
	                        <label>Proveedor</label>
	                        <select class="form-control" id="proveedor" onchange="load_cargador_proveedor()">
	                            <option selected="true" disabled="">--- Seleccione ---</option>';
	                            $sql_proveedores="SELECT Proveedores.RazonSocial, Proveedores.idProveedor FROM Equipos 
	                                INNER JOIN Proveedores ON Equipos.idPropietario=Proveedores.idProveedor
	                                    AND Equipos.clase_equipo='7A975CD6-2672-430D-B29E-7149A03D9410'
	                                GROUP BY Proveedores.RazonSocial, Proveedores.idProveedor";
	                            $result = sqlsrv_query($conn,$sql_proveedores);
	                            while($cliente = sqlsrv_fetch_array($result)){
	                                $echo.='<option value="'.$cliente['idProveedor'].'">'.utf8_encode($cliente['RazonSocial']).'</option>';
	                            }
	                $echo.='</select>
	                    </div>
	                    <div class="col-sm-3">
	                        <label>Equipo</label>
	                        <select class="form-control" id="equipo" onchange="load_date_limit()">
	                            <option selected="true" disabled="">Seleccione proveedor</option>
	                        </select>
	                    </div>
	                    <div class="col-sm-3">
	                        <label>Fecha Vigente Desde:</label>
	                        <input type="date" id="vigente_desde" class="form-control" max="'.date('Y-m-d').'">
	                    </div>
	                    <div class="col-sm-3">
	                        <label>Tipo de Tarifa</label>
	                        <select class="form-control" id="tipo_tarifa" onchange="ver()">
	                            <option value="1">TODOS</option>
	                            <option value="2">Toneladas</option>
	                            <option value="3">Tiempo</option>
	                        </select>
	                    </div>
	                </div>
	                <br><br>
	                <div class="row">
	                    <center>
	                        <div class="col-sm-2"></div>
	                        <div class="col-sm-3">
	                            <label id="title_tm">Valor Tarifa x TM</label>
	                            <input id="trf_tm" type="number" name="" class="form-control">
	                        </div>
	                        <div class="col-sm-3">
	                            <label id="title_horo">Valor Tarifa x Tiempo</label>
	                            <input id="trf_horo" type="number" name="" class="form-control">
	                        </div>
	                        <div class="col-sm-1">
	                            <label>Iva</label>
	                            <input type="text" id="iva" class="form-control">
	                        </div>
	                        <div class="col-sm-1">
	                            <br><button class="btn btn-default btn-xs navbar-left" style="margin-top: 10px;">%</button>
	                        </div>
	                    </center>
	                </div>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-warning" id="registrar">Registrar Tarifa</button>
	            </div>';
   	}else{
   		$echo = '<div class="modal-body">
	                <div class="row">
	                    <div class="col-sm-4">
	                        <label>Proveedor</label>
	                        <select class="form-control" id="proveedor">
	                            <option selected="true" disabled="">--- Seleccione ---</option>';
	                            $sql = "SELECT * FROM ProveedoresGrupos INNER JOIN Proveedores on ProveedoresGrupos.idProveedor=Proveedores.idProveedor AND ProveedoresGrupos.idAgrupacion='6B28903D-CCD4-475E-A75B-75E5A6B191DA' ORDER BY RazonSocial";
								$resul=sqlsrv_query($conn,$sql,$params,$options);
								$row = sqlsrv_num_rows($resul);
								if($row>0){
									while($aa=sqlsrv_fetch_array($resul)){
										$echo.='<option value="'.$aa['idProveedor'].'">'.utf8_encode($aa['RazonSocial']).'</option>';
									}
								}
	                $echo.='</select>
	                    </div>
	                    <div class="col-sm-4">
	                        <label>Reporte Variable</label>
	                        <select class="form-control" id="reporte_variable">
	                            <option selected="true" disabled="">Seleccione</option>';
	                            $sql = "SELECT * FROM dbo.qualityReportTree WHERE id_parent='00000000-0000-0000-0000-000000000066' ORDER BY name";
								$res = sqlsrv_query($conn,$sql);		
					        	while($rows = sqlsrv_fetch_array($res)){
					        		$id=$rows['id'];
					        		$report=$rows['name'];
					        	$echo.='<option value="'.$id.'">'.utf8_encode($report).'</option>';
					        	}
	                 $echo.='</select>
	                    </div>
	                    <div class="col-sm-2">
	                        <label>Fecha Inicio:</label>
	                        <input type="date" id="vigente_desde" class="form-control" max="'.date('Y-m-d').'">
	                    </div>
                        <div class="col-sm-2">
                            <label id="title_tm">Valor Tarifa</label>
                            <input id="tarifa" type="number" name="" class="form-control">
                        </div>
	                </div>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-warning" id="registrar_special">Registrar Tarifa</button>
	            </div>';
   	}
    echo $echo;
}elseif($_POST['band'] == 5){ //BUSCA LOS EQUIPOS DE UN PROVEEDOR
	$proveedor = $_POST['proveedor'];
	$echo = '<option selected="true" disabled="">--- Seleccione ---</option>';
	$sql = "SELECT * FROM Equipos WHERE idPropietario='$proveedor' AND clase_equipo='7A975CD6-2672-430D-B29E-7149A03D9410'";
	$resultado=sqlsrv_query($conn,$sql,$params,$options);
	$filas=sqlsrv_num_rows($resultado);
	if($filas>0){
		while($aa = sqlsrv_fetch_array($resultado)){
			$echo.='<option value="'.$aa['idEquipo'].'">'.utf8_encode($aa['Descripcion'].' '.$aa['Identificacion']).'</option>';
		}
	}
	echo $echo;
}elseif($_POST['band'] == 6){ //BUSCA LA ULTIMA FECHA VIGENTE DE TARIFA
	$equipo = $_POST['equipo'];
	$sql = "SELECT ISNULL(CASE WHEN MAX(Fecha_Desde)>MAX(Fecha_Hasta) THEN MAX(Fecha_Desde) ELSE MAX(Fecha_Hasta) END,'1900-01-01') AS FechaUltima FROM TarifaMaquinaria 
	WHERE idEquipo='$equipo'"; 
	$resultado=sqlsrv_query($conn,$sql,$params,$options);
	$filas=sqlsrv_num_rows($resultado);
	if($filas>0){
		while($aa = sqlsrv_fetch_array($resultado)){
			$FechaUltima = date_format($aa['FechaUltima'],'Y-m-d');
		}
	}
	echo $FechaUltima;
}elseif ($_POST['band'] == 7){
	$Fecha_Registro = date('Y-m-d H:i:s');
	$select_liquidador = $_POST['select_liquidador'];
	$reporte_variable = $_POST['reporte_variable'];
	$proveedor = $_POST['proveedor'];
	$vigente_desde = $_POST['vigente_desde'];
	$tarifa = $_POST['tarifa'];
	/*$sql = "SELECT * FROM TarifaMaquinaria
            INNER JOIN Equipos ON TarifaMaquinaria.idEquipo = Equipos.idEquipo
            WHERE TarifaMaquinaria.idEquipo='$equipo' AND TarifaMaquinaria.Fecha_Hasta = '1900-01-01'";*/
    $sql = "SELECT * FROM DocumentoLiquidacionTarifas 
    WHERE idLiquidacion='$select_liquidador' AND idProveedor='$proveedor' AND id='$reporte_variable' AND FechaHasta='1900-01-01'";
    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows2=sqlsrv_num_rows($res);
    if ($rows2 > 0){	
    	while($values = sqlsrv_fetch_array($res)){
			$fecha_ant = date_format($values['FechaDesde'],'Y-m-d');
		}
		if($vigente_desde > $fecha_ant){
			$vigente_hasta = date('Y-m-d',strtotime($vigente_desde . ' - 1 days'));
			$sql = "UPDATE DocumentoLiquidacionTarifas SET FechaHasta='$vigente_hasta' 
				WHERE idLiquidacion='$select_liquidador' AND idProveedor='$proveedor' AND id='$reporte_variable' AND FechaHasta='1900-01-01'";
			$result = sqlsrv_query($conn,$sql);
			if ($result){
				$sql = "INSERT INTO DocumentoLiquidacionTarifas(idLiquidacion,id,idProveedor,FechaDesde,FechaHasta,Tarifa) 
					VALUES ('$select_liquidador','$reporte_variable','$proveedor','$vigente_desde','1900-01-01','$tarifa')";
				$result = sqlsrv_query($conn,$sql);
				if ($result){
					echo 1;
				}
			}
		}else{
			echo 2;
		}
	}else{
		$sql = "INSERT INTO DocumentoLiquidacionTarifas(idLiquidacion,id,idProveedor,FechaDesde,FechaHasta,Tarifa) 
					VALUES ('$select_liquidador','$reporte_variable','$proveedor','$vigente_desde','1900-01-01','$tarifa')";
		$result = sqlsrv_query($conn,$sql);
		if ($result){
			echo 1;
		}
	}
}
?>