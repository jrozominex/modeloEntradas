<?php 
include('conexion.php');
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
if ($_POST['bandera'] == 1){
	$echo ='<center><button class="btn btn-success" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertar" onclick="Limpiar()">
		Registrar actividad <span class="glyphicon glyphicon-plus"></span></button></center>
        <br>';
    $echo.='<table class="table table-hover table-condensed table-bordered table-responsive table-striped">';
    $sql_actividad = "SELECT * FROM Actividades  WHERE idTipoActividad='00000000-0000-0000-0000-000000000001' ORDER BY Descripcion";
    $num = 1;
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $resultado=sqlsrv_query($conn,utf8_decode($sql_actividad),$params,$options);
    $rows=sqlsrv_num_rows($resultado);
    if ($rows > 0){ 
     	$echo.='<thead>
            <th>N°</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </thead>';
        while ($actividad = sqlsrv_fetch_array($resultado)){
        	$Descripcion =utf8_encode($actividad['Descripcion']);
        	$idActividad = $actividad['idActividad'];
        	$echo.='<tr>
            <td>'.$num.'</td>
            <td>'.$Descripcion.'</td>
            <td><center>
                <button class="btn btn-primary btn-xs" title="Crear Sub-Actividad" data-toggle="modal" data-target="#modalSub_actividad" onclick="sub_actividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.','.'\''.'1'.'\''.')"><span class="glyphicon glyphicon-plus"></span></button>
                <button class="btn btn-warning btn-xs" title="Editar actividad" data-toggle="modal" data-target="#modalInsertar" onclick="VerActividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.')"><span class="glyphicon glyphicon-edit"></span></button>
            </center></td>
        </tr>';
        $sql_subactividad = "SELECT idActividad, idSubactividad, Descripcion as Descripcion_sub FROM Subactividades_cargadores WHERE idActividad='".$idActividad."'";
        $res = sqlsrv_query($conn,$sql_subactividad);
        $a1 = 1;
        while($SubActividad = sqlsrv_fetch_array($res)){
        	$idSubactividad = $SubActividad['idSubactividad'];
        	$Descripcion_sub = utf8_encode($SubActividad['Descripcion_sub']);
        	$mini_num = $num.".".$a1;
            $echo.='<tr>
                <td>'.$mini_num.'</td>
                <td colspan="2"><a href="" data-toggle="modal" data-target="#modalSub_actividad" onclick="VerSubActividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.','.'\''.$idSubactividad.'\''.','.'\''.$Descripcion_sub.'\''.')"><p style="color: green;">'.$Descripcion_sub.'</p></a></td>
            </tr>';
        $a1++;
        }
        $num++;
        }
    }else{
        $echo.='<br><br><center>No hay actividades registradas por el momento</center>';
    }
    $echo.='</table>';
}elseif($_POST['bandera'] == 2){
	$echo='<center>
		<button class="btn btn-success" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertar" onclick="Limpiar()">Registrar actividad <span class="glyphicon glyphicon-plus"></span></button></center>
        <br>';
    $echo.='<table class="table table-hover table-condensed table-bordered table-responsive table-striped">';
    $sql_actividad = "SELECT * FROM Actividades  WHERE idTipoActividad='00000000-0000-0000-0000-000000000002'";
    $num = 1;
    $resultado=sqlsrv_query($conn,utf8_decode($sql_actividad),$params,$options);
    $rows=sqlsrv_num_rows($resultado);
    if ($rows > 0){ 
    	$echo.='<thead>
            <th>N°</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </thead>';
        while ($actividad = sqlsrv_fetch_array($resultado)){
        	$Descripcion = utf8_encode($actividad['Descripcion']);
        	$idActividad = $actividad['idActividad'];
            $echo.='<tr>
                <td>'.$num.'</td>
                <td>'.$Descripcion.'</td>
                <td><center>
                    <button class="btn btn-primary btn-xs" title="Crear Sub-Actividad" data-toggle="modal" data-target="#modalSub_actividad" onclick="sub_actividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.','.'\''.'1'.'\''.')"><span class="glyphicon glyphicon-plus"></span></button>
                    <button class="btn btn-warning btn-xs" title="Editar actividad" data-toggle="modal" data-target="#modalInsertar" onclick="VerActividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.')"><span class="glyphicon glyphicon-edit"></span></button>
                </center></td>
            </tr>';
            $sql_subactividad = "SELECT idActividad, idSubactividad, Descripcion as Descripcion_sub FROM Subactividades_cargadores WHERE idActividad='".$actividad['idActividad']."'";
            $res = sqlsrv_query($conn,$sql_subactividad);
            $a1 = 1;
            while($SubActividad = sqlsrv_fetch_array($res)){
            	$idSubactividad = $SubActividad['idSubactividad'];
    			$Descripcion_sub = utf8_encode($SubActividad['Descripcion_sub']);
            	$echo.='<tr>
                    <td>'.$mini_num = $num.".".$a1.'</td>
                    <td colspan="2"><a href="" data-toggle="modal" data-target="#modalSub_actividad" onclick="VerSubActividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.','.'\''.$idSubactividad.'\''.','.'\''.$Descripcion_sub.'\''.')"><p style="color: green;">'.$Descripcion_sub.'</p></a></td>
                </tr>';
                $a1++;
            }
            $num++;
        }
    }else{
        $echo.='<br><br><center>No hay actividades registradas por el momento</center>';
    }
    $echo.='</table>';
}elseif($_POST['bandera'] == 3){
	$echo ='<center><button class="btn btn-success" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertar" onclick="Limpiar()">
		Registrar actividad <span class="glyphicon glyphicon-plus"></span></button></center>
        <br>';
    $echo.='<table class="table table-hover table-condensed table-bordered table-responsive table-striped">';
        $sql_actividad = "SELECT * FROM Actividades  WHERE idTipoActividad='00000000-0000-0000-0000-000000000003'";
        $num = 1;
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $resultado=sqlsrv_query($conn,utf8_decode($sql_actividad),$params,$options);
        $rows=sqlsrv_num_rows($resultado);
        if ($rows > 0){ 
        	$echo.='<thead>
                <th>N°</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </thead>';
            while ($actividad = sqlsrv_fetch_array($resultado)){
            	$Descripcion = utf8_encode($actividad['Descripcion']);
        		$idActividad = $actividad['idActividad'];
            $echo.='<tr>
                    <td>'.$num.'</td>
                    <td>'.$Descripcion.'</td>
                    <td><center>
                        <button class="btn btn-primary btn-xs" title="Crear Sub-Actividad" data-toggle="modal" data-target="#modalSub_actividad" onclick="sub_actividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.','.'\''.'1'.'\''.')"><span class="glyphicon glyphicon-plus"></span></button>
                        <button class="btn btn-warning btn-xs" title="Editar actividad" data-toggle="modal" data-target="#modalInsertar" onclick="VerActividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.')"><span class="glyphicon glyphicon-edit"></span></button>
                    </center></td>
                </tr>';
                $sql_subactividad = "SELECT idActividad, idSubactividad, Descripcion as Descripcion_sub FROM Subactividades_cargadores WHERE idActividad='".$actividad['idActividad']."'";
                $res = sqlsrv_query($conn,$sql_subactividad);
                $a1 = 1;
                while($SubActividad = sqlsrv_fetch_array($res)){
                	$idSubactividad = $SubActividad['idSubactividad'];
    				$Descripcion_sub = utf8_encode($SubActividad['Descripcion_sub']);
                $echo.='<tr>
                        <td>'.$mini_num = $num.".".$a1.'</td>
                        <td colspan="2"><a href="" data-toggle="modal" data-target="#modalSub_actividad" onclick="VerSubActividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.','.'\''.$idSubactividad.'\''.','.'\''.$Descripcion_sub.'\''.')"><p style="color: green;">'.$Descripcion_sub.'</p></a></td>
                    </tr>';
                $a1++;
                }
                $num++;
            }
        }else{
            $echo.='<br><br><center>No hay actividades registradas por el momento</center>';
        }
        $echo.='</table>';
}elseif($_POST['bandera'] == 4){
	$echo ='<center><button class="btn btn-success" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertar" onclick="Limpiar()">Registrar actividad <span class="glyphicon glyphicon-plus"></span></button></center>
        <br>';
    $echo.='<table class="table table-hover table-condensed table-bordered table-responsive table-striped">';
        $sql_actividad = "SELECT * FROM Actividades  WHERE idTipoActividad='00000000-0000-0000-0000-000000000004'";
        $num = 1;
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $resultado=sqlsrv_query($conn,utf8_decode($sql_actividad),$params,$options);
        $rows=sqlsrv_num_rows($resultado);
        if ($rows > 0){ 
        	$echo.='<thead>
                <th>N°</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </thead>';
            while ($actividad = sqlsrv_fetch_array($resultado)){
            	$Descripcion = utf8_encode($actividad['Descripcion']);
        		$idActividad = $actividad['idActividad'];
            $echo.='<tr>
                    <td>'.$num.'</td>
                    <td>'.$Descripcion.'</td>
                    <td><center>
                        <button class="btn btn-primary btn-xs" title="Crear Sub-Actividad" data-toggle="modal" data-target="#modalSub_actividad" onclick="sub_actividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.','.'\''.'1'.'\''.')"><span class="glyphicon glyphicon-plus"></span></button>
                        <button class="btn btn-warning btn-xs" title="Editar actividad" data-toggle="modal" data-target="#modalInsertar" onclick="VerActividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.')"><span class="glyphicon glyphicon-edit"></span></button>
                    </center></td>
                </tr>';
                $sql_subactividad = "SELECT idActividad, idSubactividad, Descripcion as Descripcion_sub FROM Subactividades_cargadores WHERE idActividad='".$actividad['idActividad']."'";
                $res = sqlsrv_query($conn,$sql_subactividad);
                $a1 = 1;
                while($SubActividad = sqlsrv_fetch_array($res)){
                	$idSubactividad = $SubActividad['idSubactividad'];
    				$Descripcion_sub = utf8_encode($SubActividad['Descripcion_sub']);
                	$echo.='<tr>
                        <td>'.$mini_num = $num.".".$a1.'</td>
                        <td colspan="2"><a href="" data-toggle="modal" data-target="#modalSub_actividad" onclick="VerSubActividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.','.'\''.$idSubactividad.'\''.','.'\''.$Descripcion_sub.'\''.')"><p style="color: green;">'.$Descripcion_sub.'</p></a></td>
                    </tr>';
                    $a1++;
                }
                $num++;
            }
        }else{
            $echo.='<br><br><center>No hay actividades registradas por el momento</center>';
        }
        $echo.='</table>';
}elseif($_POST['bandera'] == 5){
	$echo ='<center><button class="btn btn-success" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertar" onclick="Limpiar()">Registrar actividad <span class="glyphicon glyphicon-plus"></span></button></center>
    <br>';
    $echo.='<table class="table table-hover table-condensed table-bordered table-responsive table-striped">';
    $sql_actividad = "SELECT * FROM Actividades  WHERE idTipoActividad='00000000-0000-0000-0000-000000000005'";
    $num = 1;
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $resultado=sqlsrv_query($conn,utf8_decode($sql_actividad),$params,$options);
    $rows=sqlsrv_num_rows($resultado);
    if ($rows > 0){ 
    	$echo.='<thead>
            <th>N°</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </thead>';
        while ($actividad = sqlsrv_fetch_array($resultado)){
        	$Descripcion = utf8_encode($actividad['Descripcion']);
        	$idActividad = $actividad['idActividad'];
        	$echo.='<tr>
                <td>'.$num.'</td>
                <td>'.$Descripcion.'</td>
                <td><center>
                    <button class="btn btn-primary btn-xs" title="Crear Sub-Actividad" data-toggle="modal" data-target="#modalSub_actividad" onclick="sub_actividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.','.'\''.'1'.'\''.')"><span class="glyphicon glyphicon-plus"></span></button>
                    <button class="btn btn-warning btn-xs" title="Editar actividad" data-toggle="modal" data-target="#modalInsertar" onclick="VerActividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.')"><span class="glyphicon glyphicon-edit"></span></button>
                </center></td>
            </tr>';
            $sql_subactividad = "SELECT idActividad, idSubactividad, Descripcion as Descripcion_sub FROM Subactividades_cargadores WHERE idActividad='".$actividad['idActividad']."'";
            $res = sqlsrv_query($conn,$sql_subactividad);
            $a1 = 1;
            while($SubActividad = sqlsrv_fetch_array($res)){
            	$idSubactividad = $SubActividad['idSubactividad'];
    			$Descripcion_sub = utf8_encode($SubActividad['Descripcion_sub']);
            	$echo.='<tr>
                    <td>'.$mini_num = $num.".".$a1.'</td>
                    <td colspan="2"><a href="" data-toggle="modal" data-target="#modalSub_actividad" onclick="VerSubActividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.','.'\''.$idSubactividad.'\''.','.'\''.$Descripcion_sub.'\''.')"><p style="color: green;">'.$Descripcion_sub.'</p></a></td>
                </tr>';
                $a1++;
            }
            $num++;
        }
    }else{
        $echo.='<br><br><center>No hay actividades registradas por el momento</center>';
    }
    $echo.='</table>';
}elseif($_POST['bandera'] == 6){
	$echo ='<center><button class="btn btn-success" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertar" onclick="Limpiar()">Registrar actividad <span class="glyphicon glyphicon-plus"></span></button></center>
        <br>';
    $echo.='<table class="table table-hover table-condensed table-bordered table-responsive table-striped">';
    $sql_actividad = "SELECT * FROM Actividades  WHERE idTipoActividad='00000000-0000-0000-0000-000000000006'";
    $num = 1;
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $resultado=sqlsrv_query($conn,utf8_decode($sql_actividad),$params,$options);
    $rows=sqlsrv_num_rows($resultado);
    if ($rows > 0){ 
    	$echo.='<thead>
            <th>N°</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </thead>';
        while ($actividad = sqlsrv_fetch_array($resultado)){
        	$Descripcion = utf8_encode($actividad['Descripcion']);
    		$idActividad = $actividad['idActividad'];
        	$echo.='<tr>
                <td>'.$num.'</td>
                <td>'.$Descripcion.'</td>
                <td><center>
                    <button class="btn btn-primary btn-xs" title="Crear Sub-Actividad" data-toggle="modal" data-target="#modalSub_actividad" onclick="sub_actividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.','.'\''.'1'.'\''.')"><span class="glyphicon glyphicon-plus"></span></button>
                    <button class="btn btn-warning btn-xs" title="Editar actividad" data-toggle="modal" data-target="#modalInsertar" onclick="VerActividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.')"><span class="glyphicon glyphicon-edit"></span></button>
                </center></td>
            </tr>';
            $sql_subactividad = "SELECT idActividad, idSubactividad, Descripcion as Descripcion_sub FROM Subactividades_cargadores WHERE idActividad='".$actividad['idActividad']."'";
            $res = sqlsrv_query($conn,$sql_subactividad);
            $a1 = 1;
            while($SubActividad = sqlsrv_fetch_array($res)){
            	$idSubactividad = $SubActividad['idSubactividad'];
    			$Descripcion_sub = utf8_encode($SubActividad['Descripcion_sub']);
            	$echo.='<tr>
                    <td>'.$mini_num = $num.".".$a1.'</td>
                    <td colspan="2"><a href="" data-toggle="modal" data-target="#modalSub_actividad" onclick="VerSubActividad('.'\''.$idActividad.'\''.','.'\''.$Descripcion.'\''.','.'\''.$idSubactividad.'\''.','.'\''.$Descripcion_sub.'\''.')"><p style="color: green;">'.$Descripcion_sub.'</p></a></td>
                </tr>';
                $a1++;
            }
            $num++;
        }
    }else{
        $echo.='<br><br><center>No hay actividades registradas por el momento</center>';
    }
    $echo.='</table>';
}elseif($_POST['bandera'] == 7){
	$echo ='<center><button class="btn btn-success" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertarCategoria">Registrar categorias <span class="glyphicon glyphicon-plus"></span></button></center>
    	<br>';
    $echo.='<div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <table class="table table-hover table-condensed table-bordered table-responsive table-striped">';
            $sql_actividad = "SELECT * FROM EquiposGrupos";
            $num = 1;
            $params = array();
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $resultado=sqlsrv_query($conn,utf8_decode($sql_actividad),$params,$options);
            $rows=sqlsrv_num_rows($resultado);
            if ($rows > 0){ 
            	$echo.='<thead>
                    <th>N°</th>
                    <th>Descripción</th>
                    <th>Grupo Maquinaria</th>
                </thead>';
                while ($actividad = sqlsrv_fetch_array($resultado)){
                    $idxid = $actividad['idGrupo'];
                	$Descripcion = utf8_encode($actividad['Descripcion']);
                    $estado = $actividad['AplicaGrupo'];
                	$echo.='<tr>
                        <td>'.$num.'</td>
                        <td>'.$Descripcion.'</td>
                        <td><input type="checkbox" onclick="habilitar_grupo('.'\''.$idxid.'\''.','.'\''.$estado.'\''.')" id="habilita_grupo"';
                                    if($estado == 1){
                                        $echo.=' checked>';
                                    }elseif($estado == 0){
                                        $echo.='>';
                                    }
                                $echo.='</td>
                    </tr>';
                    $num++;
                }
            }else{
                $echo.='<br><br><center>No hay actividades registradas por el momento</center>';
            }
            $echo.='</table>
        </div>
        <div class="col-sm-4"></div>';
}elseif($_POST['bandera'] == 8){
	$echo ='<center><button class="btn btn-success" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertarPila">Registrar Pila <span class="glyphicon glyphicon-plus"></span></button></center>
    <br>';
    $echo.='<div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <table class="table table-hover table-condensed table-bordered table-responsive table-striped">';
            $sql_actividad = "SELECT * FROM Pila_informes";
            $num = 1;
            $params = array();
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $resultado=sqlsrv_query($conn,utf8_decode($sql_actividad),$params,$options);
            $rows=sqlsrv_num_rows($resultado);
            if ($rows > 0){ 
            	$echo.='<thead>
                    <th>N°</th>
                    <th>Descripción</th>
                </thead>';
                while ($actividad = sqlsrv_fetch_array($resultado)){
                	$Descripcion = utf8_encode($actividad['descripcion']);
                	$echo.='<tr>
                        <td>'.$num.'</td>
                        <td>'.$Descripcion.'</td>
                    </tr>';
                    $num++;
                }
            }else{
                $echo.='<br><br><center>No hay actividades registradas por el momento</center>';
            }
            $echo.='</table>
        </div>
        <div class="col-sm-4"></div>
    </div>';
}elseif($_POST['bandera'] == 9){
	$echo='<center><button class="btn btn-success" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertarRendimiento">Registrar Rendimiento <span class="glyphicon glyphicon-plus"></span></button></center>
    <br>';
    $echo.='<div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
            <table id="example1" class="row-border hover order-column compact">
                <thead>
                    <tr>
                    <th>MATERIAL</th>';
                    $position = 0;
                    /*sql = "DELETE "*/
                    $sql = "SELECT DISTINCT(Equipos.Descripcion) as cargador, Equipos.Identificacion
                            FROM Cargadores_Tara INNER JOIN Equipos ON Cargadores_Tara.idEquipo=Equipos.idEquipo
                            ORDER BY Equipos.Descripcion, Equipos.Identificacion";
                    $r = sqlsrv_query($conn,$sql);
                    while($a = sqlsrv_fetch_array($r)){
                        $Array_cargador[$a['cargador'].' - '.$a['Identificacion']] = $position;
                        $position++;
                        $echo.='<th>'.$a['cargador']." - ".$a['Identificacion'].'</th>';
                    }
                    $echo.='</tr>
                </thead>
                <tbody>';
                $sql = "SELECT DISTINCT(Clasificacion.Descripcion)
                        FROM Cargadores_Tara INNER JOIN Clasificacion ON Cargadores_Tara.idClasificacion=Clasificacion.idClasificacion 
                        ORDER BY Clasificacion.Descripcion";
                $r = sqlsrv_query($conn,$sql);
                while($a = sqlsrv_fetch_array($r)){
                	$Descripcion = utf8_encode($a['Descripcion']);
                    $position1 = 0;
                    $echo.='<tr>
                        <td>'.$Descripcion.'</td>';
                        $sql1 = "SELECT Cargadores_Tara.tara, Equipos.Descripcion as cargador, Equipos.identificacion, 
                            Clasificacion.Descripcion
                            FROM Cargadores_Tara LEFT JOIN Equipos ON Cargadores_Tara.idEquipo=Equipos.idEquipo
                            LEFT JOIN Clasificacion ON Cargadores_Tara.idClasificacion=Clasificacion.idClasificacion 
                            WHERE Clasificacion.Descripcion='".$Descripcion."'
                            ORDER BY Clasificacion.Descripcion, Equipos.Descripcion, Equipos.Identificacion";
                        $r1 = sqlsrv_query($conn,$sql1);
                        while($b = sqlsrv_fetch_array($r1)){
                        	$tara = number_format($b['tara'],1);
                            $var_a = $Array_cargador[$b['cargador'].' - '.$b['identificacion']];
                            $mostrar_texto = 'El equipo '.$b['cargador'].' - '.$b['identificacion'].' con el producto '.$Descripcion.' es de '.$tara;
                            if($var_a == $position1){
                                $echo.='<td align="center" onclick="prueba('.'\''.$mostrar_texto.'\''.')">'.$tara.'</td>';
                                $position1++;
                            }else{
                                $resta = $var_a-$position1;
                                for ($i=$position1; $i < $resta; $i++){
                                    $echo.='<td align="center">0.0</td>';
                                    $position1++;
                                }
                                $echo.='<td align="center">'.$tara.'</td>';
                                $position1++;
                            }
                        }
                        if($position != $position1){
                            $recorre = $position-$position1;
                            //echo '<br>';
                            for ($i=0; $i < $recorre; $i++){
                                $echo.='<td align="center">0.0</td>';
                            }
                        }
                    $echo.='</tr>';
                }
                $echo.='</tbody>
            </table>
        </div>
    </div>';
}elseif($_POST['bandera'] == 10){
	$echo='<center>
	    <button class="btn btn-success" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertarActividadDestinos">
	        Registrar Agrupación 
	        <span class="glyphicon glyphicon-plus"></span>
	    </button>
	</center>
	<br>';
	$echo.='<div class="row">
	    <div class="col-sm-1"></div>
	    <div class="col-sm-10">
	        <table class="table table-hover table-condensed table-bordered table-responsive table-striped">
	            <thead>
	                <th>Destino</th>
	                <th>Actividad</th>
	                <th>Cargadores</th>
                    <th>Produccón</th>
	                <th>Acciones</th>
	            </thead>
	            <tbody>'; 
	                $sql = "SELECT Actividades_cargadores_destinos.idxid, Actividades_cargadores_destinos.Estado, Actividades_cargadores_destinos.Produccion, Actividades.Descripcion as Actividad, 
                        Destino.Descripcion AS Destino
	                FROM Actividades_cargadores_destinos INNER JOIN
	                Actividades ON Actividades_cargadores_destinos.idActividad = Actividades.idActividad INNER JOIN
	                Destino ON Actividades_cargadores_destinos.idDestino = Destino.idDestino
                    ORDER BY Destino.Descripcion, Actividades.Descripcion";
	                $res = sqlsrv_query($conn,$sql);
	                while($aa = sqlsrv_fetch_array($res)){
	                    $idxid = $aa['idxid'];
	                    $estado =$aa['Estado'];
                        $produccion = $aa['Produccion'];

	                        $echo.='<tr>
	                            <td>'.utf8_encode($aa['Destino']).'</td>
                                <td>'.utf8_encode($aa['Actividad']).'</td>
	                            <td><center><input type="checkbox" onclick="change_habilitar('.'\''.'Estado'.'\''.','.'\''.$idxid.'\''.','.'\''.$estado.'\''.')" id="habilita_cargador"';
	                                if($estado == 1){
	                                    $echo.=' checked>';
                                    }elseif($aa['Estado'] == 0){
	                                    $echo.='>';
                                    }
	                            $echo.='</center></td>
                                <td><center><input type="checkbox" onclick="change_habilitar('.'\''.'Produccion'.'\''.','.'\''.$idxid.'\''.','.'\''.$produccion.'\''.')" id="habilita_cargador"';
                                    if($produccion == 1){
                                        $echo.='checked>';
                                    }elseif($aa['Produccion'] == 0){
                                        $echo.='>';
                                    }
                                $echo.='</center></td>
	                            <td>
	                                <button class="btn btn-danger btn-xs" title="Editar Agrupación" onclick="editar_ActividadDestinos('.'\''.$idxid.'\''.','.'\''.$estado.'\''.')"><span class="glyphicon glyphicon-trash"></span></button>
	                            </td>
	                        </tr>';
	                }
	            $echo.='</tbody>
	        </table>
	    </div>
	</div>';
}elseif($_POST['bandera'] == 11){
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $echo='<center>
        <button class="btn btn-success" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertarRecetasProduccion" onclick="cargar_clasif_recetas()">
            Registrar Recetas
            <span class="glyphicon glyphicon-plus"></span>
        </button>
    </center>
    <br>';
    $position_array = 0;
    $SQL_DETALLE = "SELECT Clasificacion.idClasificacion, Clasificacion.Descripcion FROM Recetas_produccion_detalle 
                INNER JOIN Clasificacion ON Recetas_produccion_detalle.idClasificacion=Clasificacion.idClasificacion 
            GROUP BY Clasificacion.idClasificacion, Clasificacion.Descripcion
            ORDER BY Clasificacion.Descripcion";
    $resultado_DETALLE=sqlsrv_query($conn,$SQL_DETALLE);

    while($aa = sqlsrv_fetch_array($resultado_DETALLE)){
        $array_material_alimentado[$position_array] = '['.utf8_encode($aa['Descripcion']).']';
        $position_array++;
    }
    $implode_array = implode(",", $array_material_alimentado);
    $SQL = "SELECT *
FROM (SELECT Recetas_produccion.idReceta,Recetas_produccion.idEmpresa, Proveedores.NombreCorto AS Empresa, Destino.Descripcion AS Destino, Recetas_produccion.Descripcion AS Receta,ISNULL(CAST(SUBSTRING(Recetas_produccion.Descripcion,CHARINDEX('-', Recetas_produccion.Descripcion)+1,LEN(Recetas_produccion.Descripcion)-CHARINDEX('-', Recetas_produccion.Descripcion)) AS INT),0) AS Consecutivo,
        Recetas_produccion.FechaRegistro,Recetas_produccion.idUsuario, Recetas_produccion.Habilitado, Clasificacion.Descripcion AS Producido,
        Clasificacion_2.Descripcion AS Alimentado, Recetas_produccion_detalle.porcentaje,Pilas.Descripcion AS PilaProducido
    FROM Recetas_produccion 
    INNER JOIN Recetas_produccion_detalle ON Recetas_produccion.idReceta=Recetas_produccion_detalle.idReceta
    INNER JOIN Proveedores ON Recetas_produccion.idEmpresa=Proveedores.idProveedor 
    INNER JOIN Destino ON Recetas_produccion.idDestino=Destino.idDestino
    INNER JOIN Clasificacion ON Recetas_produccion.idClasificacion=Clasificacion.idClasificacion
    LEFT jOIN Pilas ON Recetas_produccion.idPila=Pilas.idPila
    INNER JOIN Clasificacion AS Clasificacion_2 ON Recetas_produccion_detalle.idClasificacion=Clasificacion_2.idClasificacion
    GROUP BY Recetas_produccion.idReceta,Recetas_produccion.idEmpresa, Proveedores.NombreCorto, Destino.Descripcion, Recetas_produccion.Descripcion, 
        Recetas_produccion.FechaRegistro,Recetas_produccion.idUsuario, Recetas_produccion.Habilitado, Clasificacion.Descripcion,
        Clasificacion_2.Descripcion, Recetas_produccion_detalle.porcentaje,Pilas.Descripcion
    ) AS SourceTable PIVOT (SUM([porcentaje]) FOR [Alimentado] IN ($implode_array)) AS PivotTable
ORDER BY Empresa,Destino,Producido,Consecutivo";

    $resultado=sqlsrv_query($conn,utf8_decode($SQL),$params,$options);
    $rows=sqlsrv_num_rows($resultado);
    $echo.='<div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">';
    if($rows > 0){
        $echo.='<table class="table table-hover table-condensed table-bordered table-responsive table-striped" style="text-align: center">
                <thead>
                <tr>
                    <th rowspan="2"><center>Acciones</center></th>
                    <th rowspan="2"><center>Empresa</center></th>
                    <th rowspan="2"><center>Patio Preparación</center></th>
                    <th rowspan="2"><center>Material Producido</center></th>
                    <th rowspan="2"><center>Pila</center></th>
                    <th rowspan="2"><center>Receta</center></th>
                    <th colspan="'.count($array_material_alimentado).'"><center>Material Alimentado</center></th>
                </tr>
                <tr>';
        for ($i=0; $i < count($array_material_alimentado); $i++){
            $echo.='<th>'.substr($array_material_alimentado[$i],1,-1).'</th>';
        }
        $echo.='</tr></thead>';

        while($recetas = sqlsrv_fetch_array($resultado)){
            $Habilitado = $recetas['Habilitado'];
            $idReceta = $recetas['idReceta'];
            $NomReceta = utf8_encode($recetas['Receta']);
            $Empresa = utf8_encode($recetas['Empresa']);
            $Destino = utf8_encode($recetas['Destino']);
            $PilaProducido = utf8_encode($recetas['PilaProducido']);
            $sql_preparacion_mezcla = "SELECT * FROM Preparacion_recetas WHERE idReceta='$idReceta'";
            $resultado_preparacion_mezcla=sqlsrv_query($conn,utf8_decode($sql_preparacion_mezcla),$params,$options);
            $rows_preparacion_mezcla=sqlsrv_num_rows($resultado_preparacion_mezcla);

            $echo.='<tr style="color: ';
            if($Habilitado==1){
                $echo.='';
                $color = 'warning';
                $point = 'off';
                $title = 'Inhabilitar';
            }else{
                $echo.='blue';
                $color = 'success';
                $point = 'ok';
                $title = 'Habilitar';
            }
            $echo.='">';
            $echo.='<td style="vertical-align: middle">';
            if($Habilitado==1){
                $echo.='<button class="btn btn-primary" onclick="load_pilas_receta(\''.$idReceta.'\',0)"><span class="glyphicon glyphicon-tent"></span></button> ';
            }
            $echo.='<button class="btn btn-'.$color.'" onclick="inhabilitar_receta(\''.$idReceta.'\',\''.$Habilitado.'\')" title="'.$title.'"><span class="glyphicon glyphicon-'.$point.'"></span></button>';
                if($rows_preparacion_mezcla==0){
                    $echo.=' <button type="button" title="Eliminar receta" class="btn btn-danger" onclick="delete_receta_produccion(\''.$idReceta.'\')"><span class="glyphicon glyphicon-trash"></span></button>';
                }
            $echo.='</td>';
            $echo.='<td style="vertical-align: middle"><center>'.($Empresa).'</center></td>
                <td style="vertical-align: middle"><center>'.($Destino).'</center></td>
                <td style="vertical-align: middle"><center>'.utf8_encode($recetas['Producido']).'</center></td>
                <td style="vertical-align: middle"><center>'.$PilaProducido.'</center></td>
                <td style="vertical-align: middle"><center>'.$NomReceta.'</center></td>';
            for($j=0; $j<count($array_material_alimentado); $j++){
                /*if($recetas[$array_material_alimentado[$j]] == ''){
                    $tm_array_clasi = number_format(0,2);
                }else{
                    $tm_array_clasi = number_format($recetas[$array_material_alimentado[$j]],2);
                }*/
                $tm_array_clasi = $recetas[utf8_decode(substr($array_material_alimentado[$j],1,-1))];
                $echo.= '<td align="center">'.$tm_array_clasi.'</td>';
            }
            $echo.='</tr>';
        }
    }
    $echo.='</div>
    </div>';
}elseif($_POST['bandera'] == 12){
    //onclick="cargar_codigosDestino()"
    $echo = '<div class="row"><div class="col-sm-6"><center>
        <button class="btn btn-success" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertarCodigosEmpresa">
            Registrar Codigos Empresa
            <span class="glyphicon glyphicon-plus"></span>
        </button>
    </center>
    <br>';
    $SQL = "SELECT Empresa_codigos.*, Proveedores.NombreCorto 
        FROM Empresa_codigos 
            INNER JOIN Proveedores ON Empresa_codigos.idEmpresa=Proveedores.idProveedor 
        ORDER BY Empresa_codigos.codigo_empresa";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $resultado=sqlsrv_query($conn,utf8_decode($SQL),$params,$options);
    $rows=sqlsrv_num_rows($resultado);
    if($rows>0){
        $echo.='<table class="table table-hover table-condensed table-bordered table-responsive table-striped">
                <thead>
                    <th>Empresa</th>
                    <th>Codigo Empresa</th>
                </thead>
                <tbody>';
        while($aa = sqlsrv_fetch_array($resultado)){
            $echo.='<tr><td>'.utf8_encode($aa['NombreCorto']).'</td><td>'.$aa['codigo_empresa'].'</td></tr>';
        }
    }
    $echo.='</tbody></table></div><div class="col-sm-6">';
    $echo.='<center>
        <button class="btn btn-success" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertarCodigosDestino">
            Registrar Codigos Destino
            <span class="glyphicon glyphicon-plus"></span>
        </button>
    </center>
    <br>';
    $SQL = "SELECT Destino_codigos.codigo_destino, Destino.Descripcion AS Destino 
        FROM Destino_codigos INNER JOIN Destino ON Destino_codigos.idDestino=Destino.idDestino ORDER BY Destino_codigos.codigo_destino";
    $params = array();
    $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resultado=sqlsrv_query($conn,utf8_decode($SQL),$params,$options);
    $rows=sqlsrv_num_rows($resultado);
    if($rows>0){
        $echo.='<table class="table table-hover table-condensed table-bordered table-responsive table-striped">
                <thead>
                    <th>Destino</th>
                    <th>Codigo Destino</th>
                </thead>';
        while($aa = sqlsrv_fetch_array($resultado)){
            $echo.='<tr><td>'.utf8_encode($aa['Destino']).'</td><td>'.$aa['codigo_destino'].'</td></tr>';
        }
    }
}elseif($_POST['bandera'] == 13){
    //onclick="cargar_codigosDestino()"
    $echo='<center>
        <!--<button class="btn btn-success" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertarJerarquiaClasificacion">
            Registrar Jerarquias
            <span class="glyphicon glyphicon-plus"></span>
        </button>-->
        <select class="form-control" id="producto_jerarquia" onchange="load_clasificacion_jerarquia()">
            <option value="0" selected disabled>Seleccione</option>';
        $sql_producto = "SELECT * FROM Productos ORDER BY Descripcion";
        $res_producto = sqlsrv_query($conn,$sql_producto);
        while($aa = sqlsrv_fetch_array($res_producto)){
            $echo.='<option value="'.$aa['idProducto'].'">'.utf8_encode($aa['Descripcion']).'</option>';
        }
        $echo.='</select></center><br><div id="div_clasificacion_jerarquia"></div>';
}elseif($_POST['bandera'] == 14){
    $producto_jerarquia = $_POST['producto_jerarquia'];
    //ondragover="evdragover(event)" ondrop="evdrop(event)"
    $echo ='<div class="row"><div class="col-sm-5" style="border: 1px solid; border-radius: 5px;">';
    $SQL = "SELECT Clasificacion.Descripcion, Clasificacion.idClasificacion, ISNULL(ClasificacionJerarquia.Jerarquia,0) AS Jerarquia 
        FROM Clasificacion 
        LEFT JOIN ClasificacionJerarquia ON Clasificacion.idClasificacion=ClasificacionJerarquia.idClasificacion
        WHERE idProducto='$producto_jerarquia'
        ORDER BY ClasificacionJerarquia.Jerarquia,Clasificacion.Descripcion";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $resultado=sqlsrv_query($conn,utf8_decode($SQL),$params,$options);
    $rows=sqlsrv_num_rows($resultado);
    if($rows>0){
        $echo.='<div class="row" style="border: 1px solid;">
            <div class="col-sm-6">
                <h4 style="text-decoration: underline;">Clasificaciones </h4>
            </div>
            <div class="col-sm-2" style="vertical-align: middle">
                <center>
                    <button class="btn btn-xs" style="vertical-align: middle" onclick="return_array_selected()"><span class="glyphicon glyphicon-ok"></span></button>
                </center>
            </div>
            </div><br>
            <div id="form_clasificacion">';
        while($aa = sqlsrv_fetch_array($resultado)){
            $echo.='<div class="row">
                <div class="col-sm-6">'.utf8_encode($aa['Descripcion']).'</div>
                <div class="col-sm-2">
                    <input type="number" class="form-control input-sm" value="'.$aa['Jerarquia'].'" id="'.$aa['idClasificacion'].'">
                </div>
            </div>';
        }
        $echo.='</div>';
    }else{
        $echo.='<center><h4 style="text-decoration: underline;">No hay registros</h4></center>';
    }
    //ondragover="evdragover(event)" ondrop="evdrop(event,this)"
    /*$echo.='</div><div class="col-sm-1"></div><div class="col-sm-5" style="border: 1px solid; border-radius: 5px;">';
    $SQL = "SELECT Clasificacion.Descripcion AS Clasificacion, ClasificacionJerarquia.Jerarquia 
        FROM ClasificacionJerarquia 
        INNER JOIN Clasificacion ON ClasificacionJerarquia.idClasificacion=Clasificacion.idClasificacion 
        WHERE Clasificacion.idProducto='$producto_jerarquia'
        ORDER BY Jerarquia";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $resultado=sqlsrv_query($conn,utf8_decode($SQL),$params,$options);
    $rows=sqlsrv_num_rows($resultado);
    if($rows>0){
        $echo.='<center><h4 style="text-decoration: underline;">Jerarquias</h4></center><ul>';
        while($aa = sqlsrv_fetch_array($resultado)){
            $echo.='<li>'.utf8_encode($aa['Clasificacion']).'</li>';
        }
        $echo.='</ul>';
    }else{
        $echo.='<center><h4 style="text-decoration: underline;">No hay asignados</h4></center>';
    }*/
    $echo.='</div></div>';
}elseif($_POST['bandera']==15){
    $empresa_produccion = $_POST['empresa_produccion'];
    $patio_produccion = $_POST['patio_produccion'];
    if($empresa_produccion!='0' && $patio_produccion!='0'){
        $sql = "SELECT * FROM Empresa_codigos WHERE idEmpresa='$empresa_produccion'";
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $resultado=sqlsrv_query($conn,$sql,$params,$options);
        $rows=sqlsrv_num_rows($resultado);
        if($rows>0){
            while ($aa = sqlsrv_fetch_array($resultado)) {
                $echo = $aa['codigo_empresa'];
            }
        }
        $sql = "SELECT * FROM Destino_codigos WHERE idDestino='$patio_produccion'";
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $resultado=sqlsrv_query($conn,$sql,$params,$options);
        $rows=sqlsrv_num_rows($resultado);
        if($rows>0){
            while ($aa = sqlsrv_fetch_array($resultado)) {
                $echo = $echo.''.$aa['codigo_destino'].'-';
            }
        }
        $sql="SELECT TOP 1 ISNULL(Descripcion,1) AS Descripcion,
            ISNULL(CAST(SUBSTRING(Descripcion,CHARINDEX('-', Descripcion)+1,LEN(Descripcion)-CHARINDEX('-', Descripcion)) AS INT),0) AS Consecutivo
        FROM Recetas_produccion 
        WHERE idDestino='$patio_produccion' AND idEmpresa='$empresa_produccion'
        ORDER BY CAST(SUBSTRING(Descripcion,CHARINDEX('-', Descripcion)+1,LEN(Descripcion)-CHARINDEX('-', Descripcion)) AS INT) DESC";
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $resultado=sqlsrv_query($conn,$sql,$params,$options);
        $rows=sqlsrv_num_rows($resultado);
        if($rows>0){
            while ($aa = sqlsrv_fetch_array($resultado)){
                $echo = $echo.''.($aa['Consecutivo']+1);
            }
        }else{
            $echo = $echo.'1';
        }
    }else{
        $echo = '';
    }
}elseif($_POST['band']==16){
    $idReceta = $_POST['idReceta'];
    $sql = "SELECT Recetas_produccion.Descripcion,Recetas_produccion.idDestino,Recetas_produccion.idEmpresa,Clasificacion.Descripcion AS Clasificacion,Recetas_produccion_detalle.idClasificacion,Recetas_produccion_detalle.porcentaje,
    Recetas_produccion_pila.idPila,Pilas.Descripcion AS Pila, ISNULL(MAX(Recetas_produccion_pila.fechaInicio),'1900-01-01') AS fechaInicio,ISNULL(Recetas_produccion_pila.fechaFin,'1900-01-01') AS fechaFin,(SELECT ISNULL(DATEADD(DAY,1,MAX(fechaFin)),'1900-01-01') FROM Recetas_produccion_pila AS A 
    WHERE A.idReceta=Recetas_produccion_detalle.idReceta AND A.idClasificacion=Recetas_produccion_detalle.idClasificacion) AS fechaUltima, Recetas_produccion_pila.porcentajePila
FROM Recetas_produccion 
INNER JOIN Recetas_produccion_detalle ON Recetas_produccion.idReceta=Recetas_produccion_detalle.idReceta 
    AND Recetas_produccion.idReceta='$idReceta' 
LEFT JOIN Recetas_produccion_pila ON Recetas_produccion_detalle.idReceta=Recetas_produccion_pila.idReceta 
    AND Recetas_produccion_detalle.idClasificacion=Recetas_produccion_pila.idClasificacion AND Recetas_produccion_pila.fechaFin='1900-01-01'
LEFT JOIN Pilas ON Recetas_produccion_pila.idPila=Pilas.idPila
INNER JOIN Clasificacion ON Recetas_produccion_detalle.idClasificacion=Clasificacion.idClasificacion 
GROUP BY Recetas_produccion.Descripcion,Recetas_produccion_detalle.idReceta,Recetas_produccion.idDestino,Recetas_produccion.idEmpresa,Clasificacion.Descripcion,Recetas_produccion_detalle.idClasificacion,Recetas_produccion_detalle.porcentaje, Recetas_produccion_pila.idPila,
Pilas.Descripcion,Recetas_produccion_pila.fechaFin,Recetas_produccion_pila.porcentajePila 
ORDER BY Recetas_produccion.Descripcion,Clasificacion.Descripcion";
    $params = array();
    $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resultado=sqlsrv_query($conn,$sql,$params,$options);
    $rows=sqlsrv_num_rows($resultado);
    $echo = '<div class="modal-body">';
    $idClasificacion_constant = '';
    if($rows>0){
        while($aa = sqlsrv_fetch_array($resultado)){
            $idEmpresa = $aa['idEmpresa'];
            $idDestino = $aa['idDestino'];
            $idClasificacion = $aa['idClasificacion'];
            $Clasificacion = utf8_encode($aa['Clasificacion']);
            $Porcentaje = $aa['porcentaje'].'%';
            $porcentajePila = $aa['porcentajePila'];
            $idPila = $aa['idPila'];
            $Pila = utf8_encode($aa['Pila']);
            $fechaInicio = date_format($aa['fechaInicio'],'Y-m-d');
            $fechaFin = date_format($aa['fechaFin'],'Y-m-d');
            $fechaUltima = date_format($aa['fechaUltima'],'Y-m-d');
            if($idClasificacion_constant == ''){
                $idClasificacion_constant = $idClasificacion;
                $echo.= '<label style="color: #E92512">'.$Clasificacion.' ('.$Porcentaje.')</label>&nbsp;<span id="span_'.$idClasificacion_constant.'" class="glyphicon glyphicon-eye-open" onclick="open_historial_pilas(\''.$idReceta.'\',\''.$idClasificacion_constant.'\')"></span>
                <div class="row form-group center-block" id="div_'.$idClasificacion_constant.'" style="border: 1px solid; border-radius: 5px; background-color: #CEDD65;"></div>';
                if($Pila<>''){
                    $echo.='<div class="row form-group center-block">
                        <div class="col-sm-3">
                            <label>Pila</label>
                            <input type="text" id="pila_'.$idPila.'" list="listPila_'.$idPila.'" class="form-control 1" placeholder="Escriba una pila"';
                            if($fechaFin=='1900-01-01'){
                                $echo.='value="'.$Pila.'"';
                            }
                            $echo.=' disabled>
                        </div>
                        <div class="col-sm-2">
                            <label>Porcentaje %</label>
                            <input type="number" class="form-control 1" id="porcentaje_'.$idPila.'"';
                            if($fechaFin=='1900-01-01'){
                                $echo.='value="'.$porcentajePila.'"';
                            }
                    $echo.=' disabled>
                        </div>
                        <div class="col-sm-3">
                            <label>Fecha Inicio</label>
                            <input type="date" class="form-control 1" id="fechaInicio_'.$idPila.'" min="'.$fechaUltima.'"';
                            if($fechaInicio<>'1900-01-01' && $fechaFin=='1900-01-01'){
                                $echo.='value="'.$fechaInicio.'"';
                            }
                    $echo.=' disabled>
                        </div>
                        <div class="col-sm-3">
                            <label>Fecha Fin</label>
                            <input type="date" class="form-control 1" id="fechaFin_'.$idPila.'" ';
                            if($fechaInicio<>'1900-01-01'){
                                $echo.='min="'.$fechaInicio.'"';
                            }else{
                                $echo.='min="'.$fechaUltima.'"';
                            }
                    $echo.='>
                        </div>
                        <div class="col-sm-1" style="vertical-align: middle">
                            <button class="btn btn-warning" style="margin-top: 25px;" onclick="save_pilas_receta(\''.$idReceta.'\',\''.$idClasificacion_constant.'\',\''.$idPila.'\')"><span class="glyphicon glyphicon-ok"></span></button>
                        </div>
                    </div><br>';
                }
            }elseif($idClasificacion_constant==$idClasificacion){
                if($Pila<>''){
                    $echo.='<div class="row form-group center-block">
                        <div class="col-sm-3">
                            <label>Pila</label>
                            <input type="text" id="pila_'.$idPila.'" list="listPila_'.$idPila.'" class="form-control 1" placeholder="Escriba una pila"';
                            if($fechaFin=='1900-01-01'){
                                $echo.='value="'.$Pila.'"';
                            }
                            $echo.=' disabled>
                        </div>
                        <div class="col-sm-2">
                            <label>Porcentaje %</label>
                            <input type="number" class="form-control 1" id="porcentaje_'.$idPila.'"';
                            if($fechaFin=='1900-01-01'){
                                $echo.='value="'.$porcentajePila.'"';
                            }
                    $echo.=' disabled>
                        </div>
                        <div class="col-sm-3">
                            <label>Fecha Inicio</label>
                            <input type="date" class="form-control 1" id="fechaInicio_'.$idPila.'" min="'.$fechaUltima.'"';
                            if($fechaInicio<>'1900-01-01' && $fechaFin=='1900-01-01'){
                                $echo.='value="'.$fechaInicio.'"';
                            }
                    $echo.=' disabled>
                        </div>
                        <div class="col-sm-3">
                            <label>Fecha Fin</label>
                            <input type="date" class="form-control 1" id="fechaFin_'.$idPila.'" ';
                            if($fechaInicio<>'1900-01-01'){
                                $echo.='min="'.$fechaInicio.'"';
                            }else{
                                $echo.='min="'.$fechaUltima.'"';
                            }
                    $echo.='>
                        </div>
                        <div class="col-sm-1" style="vertical-align: middle">
                            <button class="btn btn-warning" style="margin-top: 25px;" onclick="save_pilas_receta(\''.$idReceta.'\',\''.$idClasificacion_constant.'\',\''.$idPila.'\')"><span class="glyphicon glyphicon-ok"></span></button>
                        </div>
                    </div><br>';
                }
            }else{
                $sql_pila="SELECT Pilas.Descripcion,Pilas.idPila FROM Movimiento 
        INNER JOIN PilasDetalle ON Movimiento.NumeroTransaccion=PilasDetalle.NumeroTransaccion
        INNER JOIN Pilas ON PilasDetalle.idPila=Pilas.idPila
    WHERE  ((Movimiento.idDestino='$idDestino' AND Movimiento.idDestinoAcopio IS NULL) OR Movimiento.idDestinoAcopio='$idDestino')
        AND Movimiento.idEmpresa='$idEmpresa' AND ((Movimiento.idClasificacion='$idClasificacion_constant') 
            OR Pilas.idPila='2693FC3F-DBD2-44EE-B7DE-4B53C752D3F9') 
            AND Pilas.idPila NOT IN (SELECT idPila FROM Recetas_produccion_pila WHERE idReceta='$idReceta' 
    AND Recetas_produccion_pila.idClasificacion='$idClasificacion_constant' AND fechaFin=(SELECT MAX(fechaFin) FROM Recetas_produccion_pila WHERE Recetas_produccion_pila.idReceta='$idReceta' 
    AND Recetas_produccion_pila.idClasificacion='$idClasificacion_constant')) 
    AND Pilas.idPila NOT IN (SELECT idPila FROM Recetas_produccion_pila WHERE fechaFin='1900-01-01' AND idReceta='$idReceta' 
    AND Recetas_produccion_pila.idClasificacion='$idClasificacion_constant')
    GROUP BY Pilas.Descripcion,Pilas.idPila,Pilas.FechaRegistro ORDER BY Pilas.FechaRegistro DESC,Pilas.Descripcion";
                $echo.='<div class="row form-group center-block">
                <div class="col-sm-3">
                    <label>Pila</label>
                    <input type="text" id="pila_'.$idClasificacion_constant.'" list="listPila_'.$idClasificacion_constant.'" class="form-control 1" placeholder="Escriba una pila" onchange="load_min_dates(\''.$idClasificacion_constant.'\',\''.$idDestino.'\')">
                    <datalist id="listPila_'.$idClasificacion_constant.'">';                     

                    $resultado_pila=sqlsrv_query($conn,$sql_pila,$params,$options);
                    $filas=sqlsrv_num_rows($resultado_pila);
                    if($filas>0){
                        while($bb = sqlsrv_fetch_array($resultado_pila)){
                            $echo.='<option value="'.utf8_encode($bb['Descripcion']).'"><option>';
                        }
                    }
            $echo.='</datalist>
                </div>
                <div class="col-sm-2">
                    <label>Porcentaje %</label>
                    <input type="number" class="form-control 1" id="porcentaje_'.$idClasificacion_constant.'">
                </div>
                <div class="col-sm-3">
                    <label>Fecha Inicio</label>
                    <input type="date" class="form-control 1" id="fechaInicio_'.$idClasificacion_constant.'">
                </div>
                <div class="col-sm-3">
                    <label>Fecha Fin</label>
                    <input type="date" class="form-control 1" id="fechaFin_'.$idClasificacion_constant.'">
                </div>
                <div class="col-sm-1" style="vertical-align: middle">
                    <button class="btn btn-success" style="margin-top: 25px;" onclick="save_pilas_receta(\''.$idReceta.'\',\''.$idClasificacion_constant.'\',\'00000000-0000-0000-0000-000000000000\')"><span class="glyphicon glyphicon-plus"></span></button>
                </div>
            </div><br>';
                $idClasificacion_constant = $idClasificacion;
                $echo.= '<label style="color: #E92512">'.$Clasificacion.' ('.$Porcentaje.')</label>&nbsp;<span id="span_'.$idClasificacion_constant.'" class="glyphicon glyphicon-eye-open" onclick="open_historial_pilas(\''.$idReceta.'\',\''.$idClasificacion_constant.'\')"></span>
                <div class="row form-group center-block" id="div_'.$idClasificacion_constant.'" style="border: 1px solid; border-radius: 5px; background-color: #CEDD65;"></div>';
                if($Pila<>''){
                    $echo.='<div class="row form-group center-block">
                        <div class="col-sm-3">
                            <label>Pila</label>
                            <input type="text" id="pila_'.$idPila.'" list="listPila_'.$idPila.'" class="form-control 1" placeholder="Escriba una pila"';
                            if($fechaFin=='1900-01-01'){
                                $echo.='value="'.$Pila.'"';
                            }
                            $echo.=' disabled>
                        </div>
                        <div class="col-sm-2">
                            <label>Porcentaje %</label>
                            <input type="number" class="form-control 1" id="porcentaje_'.$idPila.'"';
                            if($fechaFin=='1900-01-01'){
                                $echo.='value="'.$porcentajePila.'"';
                            }
                    $echo.=' disabled>
                        </div>
                        <div class="col-sm-3">
                            <label>Fecha Inicio</label>
                            <input type="date" class="form-control 1" id="fechaInicio_'.$idPila.'" min="'.$fechaUltima.'"';
                            if($fechaInicio<>'1900-01-01' && $fechaFin=='1900-01-01'){
                                $echo.='value="'.$fechaInicio.'"';
                            }
                    $echo.=' disabled>
                        </div>
                        <div class="col-sm-3">
                            <label>Fecha Fin</label>
                            <input type="date" class="form-control 1" id="fechaFin_'.$idPila.'" ';
                            if($fechaInicio<>'1900-01-01'){
                                $echo.='min="'.$fechaInicio.'"';
                            }else{
                                $echo.='min="'.$fechaUltima.'"';
                            }
                    $echo.='>
                        </div>
                        <div class="col-sm-1" style="vertical-align: middle">
                            <button class="btn btn-warning" style="margin-top: 25px;" onclick="save_pilas_receta(\''.$idReceta.'\',\''.$idClasificacion_constant.'\',\''.$idPila.'\')"><span class="glyphicon glyphicon-ok"></span></button>
                        </div>
                    </div><br>';
                }
            }
        }
        $echo.='<div class="row form-group center-block">
                <div class="col-sm-3">
                    <label>Pila</label>
                    <input type="text" id="pila_'.$idClasificacion_constant.'" list="listPila_'.$idClasificacion_constant.'" class="form-control 1" placeholder="Escriba una pila" onchange="load_min_dates(\''.$idClasificacion_constant.'\',\''.$idDestino.'\'))">
                    <datalist id="listPila_'.$idClasificacion_constant.'">';
                    $sql_pila="SELECT Pilas.Descripcion,Pilas.idPila FROM Movimiento 
        INNER JOIN PilasDetalle ON Movimiento.NumeroTransaccion=PilasDetalle.NumeroTransaccion
        INNER JOIN Pilas ON PilasDetalle.idPila=Pilas.idPila
    WHERE  ((Movimiento.idDestino='$idDestino' AND Movimiento.idDestinoAcopio IS NULL) OR Movimiento.idDestinoAcopio='$idDestino')
        AND Movimiento.idEmpresa='$idEmpresa' AND ((Movimiento.idClasificacion='$idClasificacion_constant') 
            OR Pilas.idPila='2693FC3F-DBD2-44EE-B7DE-4B53C752D3F9') 
            AND Pilas.idPila NOT IN (SELECT idPila FROM Recetas_produccion_pila WHERE idReceta='$idReceta' 
    AND Recetas_produccion_pila.idClasificacion='$idClasificacion_constant' AND fechaFin=(SELECT MAX(fechaFin) FROM Recetas_produccion_pila WHERE Recetas_produccion_pila.idReceta='$idReceta' 
    AND Recetas_produccion_pila.idClasificacion='$idClasificacion_constant')) 
    AND Pilas.idPila NOT IN (SELECT idPila FROM Recetas_produccion_pila WHERE fechaFin='1900-01-01' AND idReceta='$idReceta' 
    AND Recetas_produccion_pila.idClasificacion='$idClasificacion_constant')
    GROUP BY Pilas.Descripcion,Pilas.idPila,Pilas.FechaRegistro ORDER BY Pilas.FechaRegistro DESC,Pilas.Descripcion";
                    $resultado_pila=sqlsrv_query($conn,$sql_pila,$params,$options);
                    $filas=sqlsrv_num_rows($resultado_pila);
                    if($filas>0){
                        while($bb = sqlsrv_fetch_array($resultado_pila)){
                            $echo.='<option value="'.utf8_encode($bb['Descripcion']).'"><option>';
                        }
                    }
            $echo.='</datalist>
                </div>
                <div class="col-sm-2">
                    <label>Porcentaje %</label>
                    <input type="number" class="form-control 1" id="porcentaje_'.$idClasificacion_constant.'">
                </div>
                <div class="col-sm-3">
                    <label>Fecha Inicio</label>
                    <input type="date" class="form-control 1" id="fechaInicio_'.$idClasificacion_constant.'">
                </div>
                <div class="col-sm-3">
                    <label>Fecha Fin</label>
                    <input type="date" class="form-control 1" id="fechaFin_'.$idClasificacion_constant.'">
                </div>
                <div class="col-sm-1" style="vertical-align: middle">
                    <button class="btn btn-success" style="margin-top: 25px;" onclick="save_pilas_receta(\''.$idReceta.'\',\''.$idClasificacion_constant.'\',\'00000000-0000-0000-0000-000000000000\')"><span class="glyphicon glyphicon-plus"></span></button>
                </div>
            </div><br>';
    }
    //<button type="button" class="btn btn-info" onclick="save_pilas_receta(\''.$idReceta.'\')">Guardar</button>
    $echo.='</div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
    </div>';
}elseif($_POST['band']==17){
    $idReceta = $_POST['idReceta'];
    $idClasificacion = $_POST['idClasificacion'];
    $sql = "SELECT Recetas_produccion.idReceta,Recetas_produccion.Descripcion,Recetas_produccion_pila.porcentajePila,Recetas_produccion.idDestino,Recetas_produccion.idEmpresa,Clasificacion.Descripcion AS Clasificacion,
Clasificacion.idClasificacion, Recetas_produccion_pila.idPila,Pilas.Descripcion AS Pila, Recetas_produccion_pila.fechaInicio,Recetas_produccion_pila.fechaFin
FROM Recetas_produccion 
INNER JOIN Recetas_produccion_detalle ON Recetas_produccion.idReceta=Recetas_produccion_detalle.idReceta 
INNER JOIN Recetas_produccion_pila ON Recetas_produccion_detalle.idReceta=Recetas_produccion_pila.idReceta AND Recetas_produccion_pila.idReceta='$idReceta'
    AND Recetas_produccion_detalle.idClasificacion=Recetas_produccion_pila.idClasificacion AND Recetas_produccion_pila.idClasificacion='$idClasificacion'
    AND Recetas_produccion_pila.fechaFin<>'1900-01-01'
INNER JOIN Pilas ON Recetas_produccion_pila.idPila=Pilas.idPila 
INNER JOIN Clasificacion ON Recetas_produccion_detalle.idClasificacion=Clasificacion.idClasificacion 
ORDER BY Recetas_produccion.Descripcion,Clasificacion.Descripcion";
    $params = array();
    $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resultado=sqlsrv_query($conn,$sql,$params,$options);
    $rows=sqlsrv_num_rows($resultado);
    $echo = '<center>Historial</center>';
    if($rows>0){
        while($aa = sqlsrv_fetch_array($resultado)){
            $idEmpresa = $aa['idEmpresa'];
            $idDestino = $aa['idDestino'];
            $idClasificacion = $aa['idClasificacion'];
            $Clasificacion = utf8_encode($aa['Clasificacion']);
            $Pila = utf8_encode($aa['Pila']);
            $porcentajePila=number_format($aa['porcentajePila'],2,'.',',');
            $fechaInicio = date_format($aa['fechaInicio'],'Y-m-d');
            $fechaFin = date_format($aa['fechaFin'],'Y-m-d');
            $echo.= '<div class="col-sm-3">
                    <label>Pila</label><br>'.$Pila.'
                </div>
                <div class="col-sm-3">
                    <label>Porcentaje</label><br>'.$porcentajePila.'%
                </div>
                <div class="col-sm-3">
                    <label>Fecha Inicio</label><br>'.$fechaInicio.'
                </div>
                <div class="col-sm-3">
                    <label>Fecha Fin</label><br>'.$fechaFin.'
                </div>
            </div>';
        }
    }else{
        $echo.='<center><label>No hay pilas en el historial</label></center>';
    }
}elseif($_POST['band']==18){
    $idClasificacion = $_POST['idClasificacion'];
    $idDestino=$_POST['idDestino'];
    $pila = $_POST['pila'];
    $sql = "SELECT DATEADD(DAY,1,ISNULL((CASE WHEN (MAX(Recetas_produccion_pila.fechaFin)='1900-01-01') THEN MAX(Recetas_produccion_pila.fechaInicio) ELSE MAX(Recetas_produccion_pila.fechaFin) END),'1900-01-01')) as fechaFin FROM Recetas_produccion_pila 
    INNER JOIN Pilas ON Recetas_produccion_pila.idPila=Pilas.idPila AND Recetas_produccion_pila.idClasificacion='$idClasificacion' 
    AND Pilas.Descripcion='$pila' AND Recetas_produccion_pila.idDestino='$idDestino'";
    $params = array();
    $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resultado=sqlsrv_query($conn,$sql,$params,$options);
    while($aa = sqlsrv_fetch_array($resultado)){
        $echo = date_format($aa['fechaFin'],'Y-m-d');
    }
}
echo $echo;
?>