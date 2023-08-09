<?php
require_once '../modelo/conexion.php';
session_start();
$fecha_registro = date('Y-m-d H:i:s');
$idUsuario = $_SESSION['idUsuario'];
if($_POST['band']==1){
    $tipo_maquinaria = $_POST['tipo_maquinaria'];
    $actividad = $_POST['actividad'];
    $proveedor = $_POST['proveedor'];
    $mensaje = "";
    if (isset($tipo_maquinaria)) 
    {   $consulta = "SELECT Equipos.idEquipo, Equipos.Descripcion, Equipos.Identificacion FROM Equipos 
            INNER JOIN EquiposGrupos ON Equipos.clase_equipo = EquiposGrupos.idGrupo
            WHERE EquiposGrupos.Descripcion!='Cargador' AND Equipos.clase_equipo='$tipo_maquinaria' 
                AND Equipos.idPropietario='$proveedor' AND Equipos.idEquipo IN (SELECT idEquipo FROM EquiposActividad WHERE idActividad='$actividad')
            ORDER BY Equipos.Descripcion";

        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);     
        $resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
        $filas=sqlsrv_num_rows($resultado);  
      //return($resultado);
        if ($filas == 0) {
            $mensaje = '<option value="0" selected>No hay maquinaria</option>';//"<p>No hay ningún usuario con ese nombre y/o apellido</p>";
        }else{
            //echo 'Resultados para <strong>'.$consultaBusqueda.'</strong>';
            $mensaje .= '<option value="0" selected>Seleccione</option>';
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
}else if($_POST['band']==2){
    $empresa = $_POST['empresa'];
    $seleccionado = $_POST['seleccionado'];
    $mensaje = "";
    if(isset($empresa)){
        $sql="SELECT UsuariosDetalle.idUsuario, UsuariosDetalle.Nombre1+' '+UsuariosDetalle.Apellido1 as nombre, UsuariosEmpresa.iTipoEmpleado 
                FROM UsuariosEmpresa inner join UsuariosDetalle on UsuariosEmpresa.idUsuario= UsuariosDetalle.idUsuario 
                where idEmpresa='24B7153E-AB4C-4DB7-81BD-67BC87AF014C' and  iTipoEmpleado=1
            ORDER BY UsuariosDetalle.Nombre1,UsuariosDetalle.Apellido1";
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
        $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
        $filas=sqlsrv_num_rows($resultado);  
      //return($resultado);
        if ($filas == 0) {
            $mensaje = '<option value="0" selected>No Hay Usuarios</option>';//"<p>No hay ningún usuario con ese nombre y/o apellido</p>";
        }else{               
            if($seleccionado == 0){
                $mensaje .= '<option value="0" selected>Seleccione</option>';
            }else{
                $mensaje .= '<option value="0">Seleccione</option>';
            }
            while($resultados = sqlsrv_fetch_array($resultado)) {
                $nombre = utf8_encode($resultados['nombre']);
                $id = $resultados['idUsuario'];
                //Output
                if($id == $seleccionado){
                    $mensaje.= '<option value="'.$id.'" selected>'.$nombre.'</option>';
                }else{
                    $mensaje.= '<option value="'.$id.'">'.$nombre.'</option>';
                }
            }//Fin while $resultados
        } //Fin else $filas
    }//Fin isset $consultaBusqueda
    //Devolvemos el mensaje que tomará jQuery
    echo $mensaje; 

}elseif($_POST['band'] == 3){
    $numerotransaccion = $_POST['numerotransaccion'];
    $div_materiales = '';
    $recibo = $_POST['recibo'];
    $fecha = $_POST['fecha'];
    $semana = $_POST['semana'];
    $empresa = $_POST['empresa'];
    $proveedor = $_POST['proveedor'];
    $usuario = $_POST['usuario'];
    $actividad = $_POST['actividad'];
    $Equipo = $_POST['Equipo'];
    $patio = $_POST['patio'];
    $material_alimentado = $_POST['material_alimentado'];
    $material_objetivo = $_POST['material_objetivo'];
    $pila = $_POST['pila'];
    $tm_alimen = $_POST['tm_alimen'];
    $horas_alimen = $_POST['horas_alimen'];
    $tm_acumulado = 0;
    $porcen_acumulado = 0;
    $material_producido = $_POST['material_producido'];
    $tm_producido = $_POST['tm_producido'];
    $json = array();
    if($numerotransaccion == '0'){
        $sql_newid = "SELECT NEWID() AS id";
        $result = sqlsrv_query($conn,$sql_newid);
        while($aa = sqlsrv_fetch_array($result)){
            $numerotransaccion = $aa['id'];
        }
        $sql_num_recibo = "SELECT ISNULL(MAX(num_recibo),0) AS num_recibo FROM servicio_clasificacion";
        $res_num_recibo = sqlsrv_query($conn,$sql_num_recibo);
        while ($rows_num_recibo = sqlsrv_fetch_array($res_num_recibo)) {
            $recibo = $rows_num_recibo['num_recibo']+1;
        }
        $Fecha_actual = date('Y-m-d');
        /*$sql = "SELECT Cerrado FROM CierrePeriodosDetalle WHERE fecha='$fecha' AND Cerrado='0'";
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
        $resultado=sqlsrv_query($conn,$sql,$params,$options);
        $filas=sqlsrv_num_rows($resultado);
        if($filas > 0){*/
            $sql_insert1 = "INSERT INTO servicio_clasificacion (id_clasif, num_recibo, fecha, empresa, actividad, equipo, patio, pila, proveedor, usuario, horas_equipo, tm_aliment, material, semana, objetivo,FechaRegistro) VALUES ('$numerotransaccion','$recibo','$fecha','$empresa','$actividad','$Equipo','$patio','$pila','$proveedor','$usuario','$horas_alimen','$tm_alimen','$material_alimentado','$semana','$material_objetivo','$Fecha_actual')";
            $res = sqlsrv_query($conn,$sql_insert1);
        //}
        if($res){
            $select = "SELECT NEWID() AS id_detalle";
            $res = sqlsrv_query($conn,$select);
            while ($aa = sqlsrv_fetch_array($res)) {
                $id_detalle = $aa['id_detalle'];
            }
            $sql_insert2 = "INSERT INTO servicio_clasificacion_detalle (id_clasif_detalle, id_clasif, material, tm) VALUES ('$id_detalle','$numerotransaccion','$material_producido','$tm_producido')";
            $res = sqlsrv_query($conn,$sql_insert2);
            if($res){
                $sql_material = "SELECT servicio_clasificacion_detalle.id_clasif_detalle,servicio_clasificacion_detalle.tm, Clasificacion.Descripcion
                    FROM servicio_clasificacion_detalle INNER JOIN
                    Clasificacion ON servicio_clasificacion_detalle.material = Clasificacion.idClasificacion
                    WHERE servicio_clasificacion_detalle.id_clasif='$numerotransaccion'"; 
                $res_material = sqlsrv_query($conn,$sql_material);
                while($mat = sqlsrv_fetch_array($res_material)){
                    $porcentaje = ($mat['tm']/$tm_alimen)*100;
                    $json[] = array(
                        'id_detalle' => $mat['id_clasif_detalle'],
                        'Material' => utf8_encode($mat['Descripcion']),
                        'tm' => $mat['tm'],
                        'porcentaje' => number_format($porcentaje,2)
                    );
                    $tm_acumulado+=$mat['tm'];
                    $porcen_acumulado+=$porcentaje;
                }
                $jsonstring = json_encode($json);
                /*$div_materiales.=' <div class="row">
                            <div class="col-sm-6"><center><a href="javascript:modificar_prod('.$id_detalle.')">'.$nom_material.'</a></center></div>
                            <div class="col-sm-6"><center>'.$tm_producido.'</center></div>                          
                        </div>';*/
                echo $numerotransaccion."||".$jsonstring."||".$tm_acumulado."||".number_format($porcen_acumulado,2);
            }
        }
    }else{
        $sql_verficar = "SELECT * FROM servicio_clasificacion_detalle WHERE id_clasif='$numerotransaccion' AND material='$material_producido'";
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
        $resultado=sqlsrv_query($conn,$sql_verficar,$params,$options);
        $fila_verif=sqlsrv_num_rows($resultado);
        if($fila_verif <= 0){
            $select = "SELECT NEWID() AS id_detalle";
            $res = sqlsrv_query($conn,$select);
            while ($aa = sqlsrv_fetch_array($res)) {
                $id_detalle = $aa['id_detalle'];
            }
            $sql_insert3 = "INSERT INTO servicio_clasificacion_detalle (id_clasif_detalle, id_clasif, material, tm) VALUES ('$id_detalle','$numerotransaccion','$material_producido','$tm_producido')";
            $res = sqlsrv_query($conn,$sql_insert3);
            if($res){
                $sql_material = "SELECT servicio_clasificacion_detalle.id_clasif_detalle,servicio_clasificacion_detalle.tm, Clasificacion.Descripcion
                    FROM servicio_clasificacion_detalle INNER JOIN
                    Clasificacion ON servicio_clasificacion_detalle.material = Clasificacion.idClasificacion
                    WHERE servicio_clasificacion_detalle.id_clasif='$numerotransaccion'"; 
                $res_material = sqlsrv_query($conn,$sql_material);
                while($mat = sqlsrv_fetch_array($res_material)){
                    $porcentaje = ($mat['tm']/$tm_alimen)*100;
                    $json[] = array(
                        'id_detalle' => $mat['id_clasif_detalle'],
                        'Material' => utf8_encode($mat['Descripcion']),
                        'tm' => $mat['tm'],
                        'porcentaje' => number_format($porcentaje,2)
                    );
                    $tm_acumulado+=$mat['tm'];
                    $porcen_acumulado+=$porcentaje;
                }
                $jsonstring = json_encode($json);
                //echo $numerotransaccion."||".$jsonstring."||".$tm_acumulado."||".number_format($porcen_acumulado,2);
            }
        }
        echo $numerotransaccion."||".$jsonstring."||".$tm_acumulado."||".number_format($porcen_acumulado,2);
    }
}elseif($_POST['band'] == 4){
    $grupo = $_POST['grupo'];
    $numerotransaccion = $_POST['numerotransaccion'];
    if($numerotransaccion != '0'){
        $sql = "SELECT * FROM servicio_clasificacion_detalle WHERE id_clasif='$numerotransaccion'";
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
        $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
        $borra=sqlsrv_num_rows($resultado);
        if($borra > 0){
            $sql_delete = "DELETE FROM servicio_clasificacion_detalle WHERE id_clasif='$numerotransaccion'";
            $result = sqlsrv_query($conn,$sql_delete);
        }
    }else{
        $borra = 0;
    }
    $mensaje = '<option value="0" selected>Seleccione</option>';
    if($grupo=='7ED3628D-9AD7-41FE-82CD-DE46F224ED39')
        $consulta = "SELECT * FROM Clasificacion WHERE idProducto IN('$grupo','18A23F2B-B74A-466A-B888-C7ACF2BB53BC') ORDER BY Descripcion";
    else
        $consulta = "SELECT * FROM Clasificacion WHERE idProducto='$grupo' ORDER BY Descripcion";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
    $resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
    $filas=sqlsrv_num_rows($resultado);  
    while($resultados = sqlsrv_fetch_array($resultado)) {
        $nombre = utf8_encode($resultados['Descripcion']);
        $id = $resultados['idClasificacion'];
        //Output
        if($id == $seleccionado){
            $mensaje.= '<option value="'.$id.'" selected>'.$nombre.'</option>';
        }else{
            $mensaje.= '<option value="'.$id.'">'.$nombre.'</option>';
        }
    }
    echo $mensaje.'||'.$borra; 
}elseif($_POST['band'] == 5){
    //$grupo = $_POST['grupo'];
    $idClasificacion = $_POST['idClasificacion'];
    $txt_array_clasificacion = $_POST['txt_array_clasificacion'];
    $txt_variable = "";
    if($idClasificacion<>$txt_array_clasificacion){
        $txt_variable = " AND (Clasificacion.idClasificacion NOT IN ('$txt_array_clasificacion') OR Clasificacion.idClasificacion IN ('$idClasificacion'))";
    }
    /*$sql = "SELECT * FROM servicio_clasificacion_detalle WHERE id_clasif_detalle='$id_detalle'";
    $res = sqlsrv_query($conn,$sql);
    while($aa = sqlsrv_fetch_array($res)){
        $id_material  = $aa['material'];
        $tm = $aa['tm'];
    }*/
    $mensaje = '';
    $material_alimentado = $_POST['material_alimentado'];
    $grupo_material = $_POST['grupo_material'];
    $mensaje = '<option value="0" selected disabled>Seleccione</option>';
    $consulta = "SELECT Clasificacion.idClasificacion, Clasificacion.Descripcion FROM Clasificacion 
        INNER JOIN ClasificacionJerarquia ON Clasificacion.idClasificacion=ClasificacionJerarquia.idClasificacion
        WHERE idProducto='$grupo_material' $txt_variable 
            AND Jerarquia >= (SELECT Jerarquia FROM ClasificacionJerarquia WHERE idClasificacion='$material_alimentado') 
        ORDER BY Clasificacion.Descripcion";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);     
    $resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
    $filas=sqlsrv_num_rows($resultado);
    if($filas>0){
        while($resultados = sqlsrv_fetch_array($resultado)) {
            $nombre = utf8_encode($resultados['Descripcion']);
            $id = $resultados['idClasificacion'];
            //Output
            if($id == $idClasificacion){
                $mensaje.= '<option value="'.$id.'" selected>'.$nombre.'</option>';
            }else{
                $mensaje.= '<option value="'.$id.'">'.$nombre.'</option>';
            }

        }
    }else{
        $mensaje='<option value="0" selected disabled>No hay jerarquia asignada</option>';
    }
    echo $mensaje;
}elseif($_POST['band'] == 6){
    $numerotransaccion = $_POST['numerotransaccion'];
    $id_detalle = $_POST['id_detalle'];
    $material = $_POST['material'];
    $tm = $_POST['tm'];
    $tm_alimen = $_POST['tm_alimen'];
    $tm_acumulado = 0;
    $porcen_acumulado = 0;
    $json = array();
    $jsonstring = '';
    $sql_verficar = "SELECT * FROM servicio_clasificacion_detalle WHERE id_clasif='$numerotransaccion' AND material='$material'
        AND id_clasif_detalle<>'$id_detalle'";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
    $resultado=sqlsrv_query($conn,$sql_verficar,$params,$options);
    $fila_verif=sqlsrv_num_rows($resultado);
    if($fila_verif <= 0){
        if($tm != '0'){
            $update = "UPDATE servicio_clasificacion_detalle SET material='$material', tm='$tm' WHERE id_clasif_detalle='$id_detalle'";
        }else{
            $update = "DELETE FROM servicio_clasificacion_detalle WHERE id_clasif_detalle='$id_detalle'";
        }
        $res = sqlsrv_query($conn,$update);
        if($res){
            $sql_material = "SELECT servicio_clasificacion_detalle.id_clasif_detalle,servicio_clasificacion_detalle.tm, Clasificacion.Descripcion
                FROM servicio_clasificacion_detalle INNER JOIN
                Clasificacion ON servicio_clasificacion_detalle.material = Clasificacion.idClasificacion
                WHERE servicio_clasificacion_detalle.id_clasif='$numerotransaccion'"; 
            $params = array();
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
            $res_material=sqlsrv_query($conn,$sql_material,$params,$options);
            $fila_material=sqlsrv_num_rows($res_material);
            if($fila_material > 0){
                while($mat = sqlsrv_fetch_array($res_material)){
                    $porcentaje = ($mat['tm']/$tm_alimen)*100;
                    $json[] = array(
                        'id_detalle' => $mat['id_clasif_detalle'],
                        'Material' => utf8_encode($mat['Descripcion']),
                        'tm' => $mat['tm'],
                        'porcentaje' => number_format($porcentaje,2)
                    );
                    $tm_acumulado+=$mat['tm'];
                    $porcen_acumulado+=$porcentaje;
                }
                $jsonstring = json_encode($json);
            }
            //echo $jsonstring."||".$tm_acumulado."||".number_format($porcen_acumulado,2);
        }
    }else{
        $jsonstring='1';
    }
    echo $jsonstring."||".$tm_acumulado."||".number_format($porcen_acumulado,2);
}elseif ($_POST['band'] == 7) {
    //$numerotransaccion = $_POST['numerotransaccion'];
    $recibo = $_POST['recibo'];
    $fecha = $_POST['fecha'];
    $semana = $_POST['semana'];
    $empresa = $_POST['empresa'];
    $proveedor = $_POST['proveedor'];
    //$usuario = $_POST['usuario'];
    $actividad = $_POST['actividad'];
    $Equipo = $_POST['Equipo'];
    $patio = $_POST['patio'];
    $material_alimentado = $_POST['material_alimentado'];
    $material_objetivo = $_POST['material_objetivo'];
    $pila = $_POST['pila'];
    $horas_equipo = $_POST['horas_equipo'];
    $tm_alimen = $_POST['tm_alimen'];
    $array_clasif_id = $_POST['array_clasif_id'];
    $array_clasif_name = $_POST['array_clasif_name'];
    $array_clasif_tm = $_POST['array_clasif_tm'];
    $array_clasif_pila = $_POST['array_clasif_pila'];
    $get_without_inv = $_POST['get_without_inv'];


    $sql_pila = "select idPila from pilas where Descripcion = '".$pila."' ";
    $result = sqlsrv_query($conn,$sql_pila);
    while($aa = sqlsrv_fetch_array($result)){
    $pila = $aa['idPila'];
    }


    $sql_clasif = "SELECT * FROM Clasificacion WHERE idClasificacion IN ('$array_clasif_id')";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
    $res_clasif=sqlsrv_query($conn,$sql_clasif,$params,$options);
    $fila_clasif=sqlsrv_num_rows($res_clasif);
    
    $array_clasif_id = explode("','", $array_clasif_id);
    $array_clasif_name = explode("','", $array_clasif_name);
    $array_clasif_tm = explode("','", $array_clasif_tm);
    $array_clasif_pila = explode("','", $array_clasif_pila);
    $pasa_inventario=0;
    $cant_insert = 0;
    if(count($array_clasif_id)==$fila_clasif){
        $date_now = date('Y-m-d');
        $date_now_ant = date("Y-m-d",strtotime($date_now."- 2 days"));
        $echo_errors='';
        if($fecha<$date_now_ant){
            $sql_periodo="SELECT dbo.Get_controlPeriodo('$fecha','$patio','2A745DBA-68AA-4E1F-82B5-66ED1D5C9A44') AS Cerrado";
            $resul=sqlsrv_query($conn,$sql_periodo,$params,$options);
            while($aa = sqlsrv_fetch_array($resul)){
                $habilita_periodo=$aa['Cerrado'];
            }
        }else{
            $habilita_periodo=0;
        }
        if($habilita_periodo==0){
            $sql_validate_inv = "SELECT SaldoFinal FROM [dbo].[Get_SaldoInventarioDestinoClasificacion]('$empresa','$patio','$material_alimentado','$fecha','$fecha') GROUP BY SaldoFinal";
            $resul_validate_inv = sqlsrv_query($conn,$sql_validate_inv);
            while($aa= sqlsrv_fetch_array($resul_validate_inv)){
                if($aa['SaldoFinal']>=$tm_alimen){
                    $pasa_inventario++;
                }else{
                    $echo_errors.= 'El '.utf8_encode($aa['Clasificacion']).' no tiene saldo suficiente';
                }
            }
            if($get_without_inv==1){
                $pasa_inventario=1;
            }
            if($pasa_inventario==1){
                $sql_newid = "SELECT NEWID() AS id";
                $result = sqlsrv_query($conn,$sql_newid);
                while($aa = sqlsrv_fetch_array($result)){
                    $numerotransaccion = $aa['id'];
                }
                $sql_num_recibo = "SELECT ISNULL(MAX(num_recibo),0) AS num_recibo FROM servicio_clasificacion";
                $res_num_recibo = sqlsrv_query($conn,$sql_num_recibo);
                while ($rows_num_recibo = sqlsrv_fetch_array($res_num_recibo)) {
                    $recibo = $rows_num_recibo['num_recibo']+1;
                }
                $Fecha_actual = date('Y-m-d');
                $sql_insert1 = "INSERT INTO servicio_clasificacion (id_clasif, num_recibo, fecha, empresa, actividad, equipo, patio, pila, proveedor, usuario, horas_equipo, tm_aliment, material, semana, objetivo,FechaRegistro) VALUES ('$numerotransaccion','$recibo','$fecha','$empresa','$actividad','$Equipo','$patio','$pila','$proveedor','$idUsuario','$horas_equipo','$tm_alimen','$material_alimentado','$semana','$material_objetivo','$Fecha_actual')";
                $res = sqlsrv_query($conn,$sql_insert1);
                if($res){
                    for ($i=0; $i < count($array_clasif_id); $i++){
                       
                        $select = "SELECT NEWID() AS id_detalle";
                        $res = sqlsrv_query($conn,$select);
                        while ($aa = sqlsrv_fetch_array($res)){
                            $id_detalle = $aa['id_detalle'];
                        }

                        $sql_pila = "select idPila from pilas where Descripcion = '".$array_clasif_pila[$i]."' ";
                        $result_p = sqlsrv_query($conn,$sql_pila);
                        while($aa_p = sqlsrv_fetch_array($result_p)){
                        $pila2 = $aa_p['idPila'];
                        }


                        $sql_insert2 = "INSERT INTO servicio_clasificacion_detalle (id_clasif_detalle, id_clasif, material, tm,idPila_detalle) VALUES ('$id_detalle','$numerotransaccion','$array_clasif_id[$i]','$array_clasif_tm[$i]','$pila2')";
                        $res = sqlsrv_query($conn,$sql_insert2);
                        if($res){
                            $cant_insert++;
                        }
                    }
                }
                //}
                if($cant_insert==count($array_clasif_id)){
                    echo 1; //Registró correctamente
                }else{
                    echo 0; //Error al registrar
                }
            }else{
                echo 2; //No hay inventario disponible
            }
        }else{
            echo 3; //El periodo está cerrado
        }
    }
}elseif($_POST['band'] == 8){
    $fecha = $_POST['fecha'];
    $filas = 1;
    /*$sql = "SELECT Cerrado FROM CierrePeriodosDetalle WHERE fecha='$fecha' AND Cerrado='0'";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
    $resultado=sqlsrv_query($conn,$sql,$params,$options);
    $filas=sqlsrv_num_rows($resultado);*/
    $dia   = substr($fecha,8,2);
    $mes = substr($fecha,5,2);
    $anio = substr($fecha,0,4); 
    $semana = date('W',  mktime(0,0,0,$mes,$dia,$anio));
    echo $filas."||".$semana;
    
}elseif ($_POST['band'] == 10) {
    $fecha_ini= $_POST['fecha_ini'];
    $fecha_fin= $_POST['fecha_fin'];
    $empresa_1= $_POST['empresa_1'];
    $proveedor_1= $_POST['proveedor_1'];
    $patio_1= $_POST['patio_1'];
    $actividad_1= $_POST['actividad_1'];
    $tipo_maquinaria_1= $_POST['tipo_maquinaria_1'];
    $Equipo_1= $_POST['Equipo_1'];
    $grupo_material_1= $_POST['grupo_material_1'];
    $material_alimentado_1= $_POST['material_alimentado_1'];

    $text= " servicio_clasificacion.empresa = '$empresa_1' AND CAST(servicio_clasificacion.fecha AS DATE) BETWEEN '$fecha_ini' AND '$fecha_fin' ";
    if ($proveedor_1=='0'){
        $text1= '';
    }else{
        $text1= " AND servicio_clasificacion.proveedor ='$proveedor_1'";
    }
    if ($patio_1=='0'){
        $text2='';
    }else{
        $text2= " AND servicio_clasificacion.patio ='$patio_1'";   
    }
    if ($actividad_1=='0'){
        $text3='';
    }else{
        $text3= " AND servicio_clasificacion.actividad ='$actividad_1'";   
    }
    if ($tipo_maquinaria_1=='0'){
        $text4='';
    }else{
        $text4= " AND Equipos.clase_equipo ='$tipo_maquinaria_1'";   
    }
    if ($Equipo_1=='0'){
        $text5='';
    }else{
        $text5= " AND servicio_clasificacion.equipo ='$Equipo_1'";      
    }
    if ($grupo_material_1=='0'){
        $text6='';
    }else{
        $text6= " AND clasificacion.idProducto ='$grupo_material_1'";      
    }
    if ($material_alimentado_1=='0'){
         $text7='';
    }else{
        $text7= " AND servicio_clasificacion.material ='$material_alimentado_1'";         
    }

    $where = $text.$text1.$text2.$text3.$text4.$text5.$text6.$text7;

    $resul_array= array();
    $i=0;//consulta para sacar las clasificaicones de material ese rango de fecha
    $sql_clasi="SELECT '['+Clasificacion.Descripcion+']' as material
        FROM servicio_clasificacion INNER JOIN
            servicio_clasificacion_detalle ON servicio_clasificacion.id_clasif = servicio_clasificacion_detalle.id_clasif INNER JOIN
            Clasificacion ON servicio_clasificacion_detalle.material = Clasificacion.idClasificacion INNER JOIN
            Equipos ON servicio_clasificacion.equipo=Equipos.idEquipo
        WHERE $where
        GROUP by Clasificacion.Descripcion";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resul_cla=sqlsrv_query($conn,$sql_clasi,$params,$options);
    $rows = sqlsrv_num_rows($resul_cla);
    if($rows==0)
         $resul_array[$i]='[0]';
    else{
        while ($rows=sqlsrv_fetch_array($resul_cla)) {
            $resul_array[$i] =$rows['material'];
            $i++;
        }
    }
    $count_materiales=count($resul_array);
    $cadena= implode(',',$resul_array);

    $resul_array_clasi= array();
  
    $sql_clasi="SELECT Clasificacion.Descripcion as material/*, servicio_clasificacion_detalle.objetivo*/
        FROM servicio_clasificacion INNER JOIN
            servicio_clasificacion_detalle ON servicio_clasificacion.id_clasif = servicio_clasificacion_detalle.id_clasif INNER JOIN
            Clasificacion ON servicio_clasificacion_detalle.material = Clasificacion.idClasificacion INNER JOIN
            Equipos ON servicio_clasificacion.equipo=Equipos.idEquipo
        WHERE $where
        GROUP by Clasificacion.Descripcion ";
    $resul_cla= sqlsrv_query($conn, $sql_clasi);
    $k=0;
    while ($rows=sqlsrv_fetch_array($resul_cla)) {
        $resul_array_clasi[$k] =$rows['material'];
        $k++;
    }

    $sql_pivot="SELECT *
    FROM
    (   SELECT [servicio_clasificacion].[id_clasif], [Destino].[Descripcion] AS Destino, [servicio_clasificacion].[num_recibo], [servicio_clasificacion].[fecha],
        [servicio_clasificacion].[horas_equipo], [servicio_clasificacion].[tm_aliment], [Pilas].[Descripcion] AS pila, [servicio_clasificacion].[semana], 
        [servicio_clasificacion_detalle].[tm], [Proveedores_1].[Alias] as empresa, [Proveedores].[RazonSocial] AS proveedor,
        [Equipos].[Descripcion]+' - '+[Equipos].[Identificacion] as equipo,[Actividades].[Descripcion] AS actividad, 
        [UsuariosDetalle].[Nombre1]+' '+[UsuariosDetalle].[Apellido1] as usuario, [Clasificacion].[Descripcion] AS material_alimen,
        [Clas_obj].[Descripcion] AS material_objetivo, [Clasificacion_1].[Descripcion] AS material_producido,[servicio_clasificacion].[FechaRegistro]
    FROM Clasificacion INNER JOIN
        servicio_clasificacion INNER JOIN
        servicio_clasificacion_detalle ON servicio_clasificacion.id_clasif = servicio_clasificacion_detalle.id_clasif INNER JOIN
        Destino ON servicio_clasificacion.patio=Destino.idDestino INNER JOIN
        Proveedores AS Proveedores_1 ON servicio_clasificacion.empresa = Proveedores_1.idProveedor INNER JOIN
        Proveedores ON servicio_clasificacion.proveedor = Proveedores.idProveedor INNER JOIN
        Equipos ON servicio_clasificacion.equipo = Equipos.idEquipo INNER JOIN
        Actividades ON servicio_clasificacion.actividad = Actividades.idActividad INNER JOIN
        UsuariosDetalle ON servicio_clasificacion.usuario = UsuariosDetalle.idUsuario ON Clasificacion.idClasificacion = servicio_clasificacion.material INNER JOIN
        Clasificacion AS Clasificacion_1 ON servicio_clasificacion_detalle.material = Clasificacion_1.idClasificacion INNER JOIN
        Clasificacion AS Clas_obj ON servicio_clasificacion.objetivo = Clas_obj.idClasificacion INNER JOIN
        Pilas ON servicio_clasificacion.pila=Pilas.idPila
        WHERE $where
    ) AS SourceTable PIVOT(SUM([tm]) FOR [material_producido] IN(".$cadena.")) AS PivotTable 
    order by Destino,[fecha] DESC;";
    //echo utf8_encode($sql_pivot);

    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resul_pivot=sqlsrv_query($conn,$sql_pivot,$params,$options);
    $rows = sqlsrv_num_rows($resul_pivot);
    if($rows==0){
        echo 1;
    }else{
        $tabla_resultados='<br><div class="row table-responsive1"><div class="col-sm-12">';
        $tabla_resultados.='<table class="table table-bordered table-condensed" border="1" align="center">
            <thead>
            <tr>';
        if(isset($_SESSION['Array_empresa']['ELIMINAR_CLASIFICACION_MOLIENDA'])){
            $tabla_resultados.= '<th align ="center" style="vertical-align: middle; background-color: #82DBB8;"><button class="btn btn-xs" readonly><span class="glyphicon glyphicon-trash"></span></button></th>';
        }
        $tabla_resultados.= '<th align ="center" style="background-color: #82DBB8;"><b>Patio </b></th>';
        $tabla_resultados.= '<th align ="center" style="background-color: #82DBB8;"><b>Nº Recibo </b></th>';
        $tabla_resultados.= '<th align ="center" style="background-color: #82DBB8;"><b>Usuario </b></th>';
        $tabla_resultados.= '<th align="center" style="background-color: #82DBB8;" width="7%" ><b>Fecha </b></th>';
        $tabla_resultados.= '<th align="center" style="background-color: #82DBB8;"><b>Semana</b></th>';
        $tabla_resultados.= '<th align="center" style="background-color: #82DBB8;"><b>Mes</b></th>';
        $tabla_resultados.= '<th align="center" style="background-color: #82DBB8;"><b>Empresa</b></th>';
        $tabla_resultados.= '<th align="center" style="background-color: #82DBB8;"><b>Actividad</b></th>';
        $tabla_resultados.= '<th align="center" style="background-color: #82DBB8;"><b>Equipo</b></th>';
        $tabla_resultados.= '<th align="center" style="background-color: #82DBB8;"><b>Pila</b></th>'; 
        $tabla_resultados.= '<th align="center" style="background-color: #82DBB8;"><b>Material</b></th>'; 
        $tabla_resultados.= '<th align="center" style="background-color: #82DBB8;"><b>Origen del Material</b></th>';
        $tabla_resultados.= '<th align="center" style="background-color: #82DBB8;"><b>Material Objetivo</b></th>'; 
        $tabla_resultados.= '<th align="center" style="background-color: #82DBB8;"><b>Hrs. Equipo</b></th>'; 
        $tabla_resultados.= '<th align="center" style="background-color: #82DBB8;"><b>Tm Alimentado</b></th>';
        for($j=0; $j<$count_materiales; $j++){
            $tabla_resultados.= '<th align="center" style="background-color: #82DBB8;"><b>'.utf8_encode($resul_array_clasi[$j]).'</b></th>';
        }
        $tabla_resultados.= '</tr></thead>';
        $k=0;
        while($rows1=sqlsrv_fetch_array($resul_pivot)){
            $id_clasif = $rows1['id_clasif'];
            $txt_id_clasif = "'".$id_clasif."'";
            $Destino=utf8_encode($rows1['Destino']);
            $recibo = $rows1['num_recibo'];
            $txt_recibo = "'".$recibo."'";
            $fecha=date_format($rows1['fecha'],'d-m-Y');
            $mes = date_format($rows1['fecha'],'m');
            $semana = date_format($rows1['fecha'],'W');
            $empresa = utf8_encode($rows1['empresa']);
            $actividad = utf8_encode($rows1['actividad']);
            $equipo = utf8_encode($rows1['equipo']);
            $pila = utf8_encode($rows1['pila']);
            $proveedor = utf8_encode($rows1['proveedor']);
            $material_alimen = utf8_encode($rows1['material_alimen']);
            $material_objetivo = utf8_encode($rows1['material_objetivo']);
            $horas_equipo = number_format($rows1['horas_equipo'],1);
            $tm_alimen = number_format($rows1['tm_aliment'],2);

            $tabla_resultados.= '<tr>';
            if(isset($_SESSION['Array_empresa']['ELIMINAR_CLASIFICACION_MOLIENDA'])){
                $tabla_resultados.= '<td style="vertical-align: middle"><button class="btn btn-xs" onclick="delete_tiquete_clasificacion('.$txt_id_clasif.','.$txt_recibo.')"><span class="glyphicon glyphicon-trash"></span></button></td>';
            }
            $tabla_resultados.= '<td align ="center">'.$Destino.'</td>';
            $tabla_resultados.= '<td align ="center" title="Registrado por '.$rows1['usuario'].', el dia '.date_format($rows1['FechaRegistro'],'Y-m-d').'">'.$recibo.'</td>';
            $tabla_resultados.= '<td align ="center">'.$rows1['usuario'].'</td>';
            $tabla_resultados.= '<td align ="center">'.$fecha.'</td>';
            $tabla_resultados.= '<td align ="center">'.$semana.'</td>';
            $tabla_resultados.= '<td align ="center">'.$mes.'</td>';
            $tabla_resultados.= '<td align ="center">'.$empresa.'</td>';
            $tabla_resultados.= '<td align ="center">'.$actividad.'</td>';
            $tabla_resultados.= '<td align ="center">'.$equipo.'</td>';
            $tabla_resultados.= '<td align ="center">'.$pila.'</td>';
            $tabla_resultados.= '<td align ="center">'.$material_alimen.'</td>';
            $tabla_resultados.= '<td align ="center">'.$proveedor.'</td>';
            $tabla_resultados.= '<td align ="center">'.$material_objetivo.'</td>';
           
            $tabla_resultados.= '<td align ="center">'.$horas_equipo.'</td>';
            $tabla_resultados.= '<td align ="center">'.$tm_alimen.'</td>';
            for($j=0; $j<$count_materiales; $j++){
                if($rows1[$resul_array_clasi[$j]] == ''){
                    $tm_array_clasi = number_format(0,2);
                    $css="color: red;";
                }else{
                    $tm_array_clasi = number_format($rows1[$resul_array_clasi[$j]],2);
                    $css="";
                }
                $tabla_resultados.= '<td align="center" style="'.$css.'">'.$tm_array_clasi.'</td>';
            }
            $tabla_resultados.= '</tr>';
            $k++;
        }
        $tabla_resultados.='</table></div></div>';
        echo $tabla_resultados;
    }    
}else if($_POST['band']==12){
    $tipo_maquinaria = $_POST['tipo_maquinaria'];
    $lugar_trabajo = $_POST['lugar_trabajo'];
    $mensaje = "";
    if (isset($tipo_maquinaria)){
        $consulta = "SELECT Equipos.idEquipo, Equipos.Descripcion, Equipos.Identificacion FROM servicio_clasificacion
            INNER JOIN Equipos ON servicio_clasificacion.equipo=Equipos.idEquipo
        WHERE servicio_clasificacion.patio='$lugar_trabajo' AND Equipos.clase_equipo='$tipo_maquinaria'
        GROUP BY Equipos.idEquipo, Equipos.Descripcion, Equipos.Identificacion 
        ORDER BY Equipos.Descripcion, Equipos.Identificacion";
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
        $resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
        $filas=sqlsrv_num_rows($resultado);
        if($filas == 0){
            $mensaje = '<option value="0" selected>Todos</option>';
        }else{
            $mensaje .= '<option value="0" selected>Todos</option>';
            while($resultados = sqlsrv_fetch_array($resultado)){
                $maquina = $resultados['Descripcion'].' - '.$resultados['Identificacion'];
                $id = $resultados['idEquipo'];
                $mensaje.= '<option value="'.$id.'">'.$maquina.'</option>';
            }
        }
    }
    echo $mensaje;
}elseif($_POST['band'] == 14){
    $grupo = $_POST['grupo'];
    $lugar_trabajo = $_POST['lugar_trabajo'];
    $mensaje = '<option value="0" selected>Todos</option>';
    $consulta = "SELECT Clasificacion.idClasificacion, Clasificacion.Descripcion FROM servicio_clasificacion
        INNER JOIN Clasificacion ON servicio_clasificacion.material=Clasificacion.idClasificacion
    WHERE servicio_clasificacion.patio='$lugar_trabajo' AND Clasificacion.idProducto='$grupo'
    GROUP BY Clasificacion.idClasificacion, Clasificacion.Descripcion ORDER BY Clasificacion.Descripcion";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
    $resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
    $filas=sqlsrv_num_rows($resultado);
    while($resultados = sqlsrv_fetch_array($resultado)){
        $nombre = utf8_encode($resultados['Descripcion']);
        $id = $resultados['idClasificacion'];
        $mensaje.= '<option value="'.$id.'">'.$nombre.'</option>';
    }
    echo $mensaje;
}elseif($_POST['band'] == 15){
    $patio = $_POST['patio'];
    $proveedor = $_POST['proveedor'];

    $consulta = "SELECT EquiposActividad.idActividad FROM Equipos
        INNER JOIN EquiposActividad ON Equipos.idEquipo=EquiposActividad.idEquipo
    WHERE Equipos.idPropietario='$proveedor' 
    GROUP BY EquiposActividad.idActividad";
    $var_count = 0;
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
    $resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
    $filas=sqlsrv_num_rows($resultado);
    if($filas>0){
        while($resultados = sqlsrv_fetch_array($resultado)) {
            $idActividad = $resultados['idActividad'];
            $array_actividad[$var_count] = $idActividad;
            $var_count++;
        }
    }
    $array_actividad = implode("','", $array_actividad);
    $mensaje = '<option value="0" selected disabled>Seleccione</option>';
    $consulta = "SELECT Actividad,idActividad FROM vActividades_cargadores_destinos WHERE idDestino='$patio' AND idActividad IN ('$array_actividad')
    GROUP BY Actividad,idActividad";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
    $filas=sqlsrv_num_rows($resultado);
    if($filas>0){
        while($resultados = sqlsrv_fetch_array($resultado)){
            $nombre = utf8_encode($resultados['Actividad']);
            $id = $resultados['idActividad'];
            $mensaje.= '<option value="'.$id.'">'.$nombre.'</option>';
        }
    }else{
        $mensaje='<option value="0">No hay actividades asignadas</option>';
    }
    echo $mensaje;
}elseif($_POST['band'] == 16){
    $proveedor = $_POST['proveedor'];
    $actividad = $_POST['actividad'];
    $mensaje = '<option value="0" selected disabled>Seleccione</option>';
    $consulta = "SELECT EquiposGrupos.idGrupo, EquiposGrupos.Descripcion FROM Equipos
    INNER JOIN EquiposGrupos ON Equipos.clase_equipo=EquiposGrupos.idGrupo
        WHERE Equipos.idEquipo IN (SELECT idEquipo FROM EquiposActividad WHERE idActividad='$actividad') 
    AND Equipos.idPropietario='$proveedor' 
    GROUP BY EquiposGrupos.idGrupo, EquiposGrupos.Descripcion ORDER BY EquiposGrupos.Descripcion";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
    $resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
    $filas=sqlsrv_num_rows($resultado);
    if($filas>0){
        while($resultados = sqlsrv_fetch_array($resultado)) {
            $nombre = utf8_encode($resultados['Descripcion']);
            $id = $resultados['idGrupo'];
            $mensaje.= '<option value="'.$id.'">'.$nombre.'</option>';
        }
    }else{
        $mensaje= '<option value="0">No hay equipos asignados</option>';
    }
    echo $mensaje; 
}elseif($_POST['band'] == 17){
    $id_clasif = $_POST['id_clasif'];
    $num_recibo = $_POST['num_recibo'];

    $delete_detalle = "DELETE FROM servicio_clasificacion_detalle WHERE id_clasif='$id_clasif'";
    $res_detalle = sqlsrv_query($conn,$delete_detalle);
    if($res_detalle){
        $delete = "DELETE FROM servicio_clasificacion WHERE id_clasif='$id_clasif'";
        $res = sqlsrv_query($conn,$delete);
        if($res){
            echo 1;
        }
    }
}elseif($_POST['band'] == 18){
    $grupo = $_POST['grupo'];
    $numerotransaccion = $_POST['numerotransaccion'];

    $mensaje = '<option value="0" selected>Seleccione</option>';
    $consulta = "SELECT * FROM Clasificacion 
        WHERE idProducto='$grupo' AND idClasificacion NOT IN (SELECT material FROM servicio_clasificacion_detalle WHERE id_clasif='$numerotransaccion') 
        ORDER BY Descripcion";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
    $resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
    $filas=sqlsrv_num_rows($resultado);  
    while($resultados = sqlsrv_fetch_array($resultado)) {
        $nombre = utf8_encode($resultados['Descripcion']);
        $id = $resultados['idClasificacion'];
        //Output
        $mensaje.= '<option value="'.$id.'">'.$nombre.'</option>';
    }
    echo $mensaje;
}elseif($_POST['band'] == 19){ 
    $material_alimentado = $_POST['material_alimentado'];
    $grupo_material = $_POST['grupo_material'];
    $variable = $_POST['variable'];
    $txt_variable = "";
    if($variable<>'0' && $variable <> '' && $variable <> NULL){
        $txt_variable = " AND Clasificacion.idClasificacion NOT IN ('$variable')";
    }
    $mensaje = '<option value="0" selected disabled>Seleccione</option>';
    if($grupo_material=='7ED3628D-9AD7-41FE-82CD-DE46F224ED39'){ //PRODUCTO MEZCLAS
        $consulta = "SELECT Clasificacion.idClasificacion, Clasificacion.Descripcion FROM Clasificacion 
            LEFT JOIN ClasificacionJerarquia ON Clasificacion.idClasificacion=ClasificacionJerarquia.idClasificacion
            WHERE idProducto IN('$grupo_material','18A23F2B-B74A-466A-B888-C7ACF2BB53BC') $txt_variable /*AND Clasificacion.bMateriaPrima=0*/
                AND Jerarquia >= (SELECT Jerarquia FROM ClasificacionJerarquia WHERE idClasificacion='$material_alimentado') 
            ORDER BY Clasificacion.Descripcion";
    }elseif($grupo_material=='F81E2A07-8341-4239-A288-89E4E5FE310A' /*&& 
        ($material_alimentado=='D1512A6D-2B58-4D56-864F-7833F0AABD46' || $material_alimentado=='393869E3-9996-490C-991A-CE0F3914FE26' || $material_alimentado=='D0E4C4D1-7536-45CA-A4BC-D9F151A5DED7' || $material_alimentado=='DFDEA288-25EC-4092-BBC8-A49A946EDBD1')*/){ //PRODUCTO COQUIZABLES
            $consulta = "SELECT Clasificacion.idClasificacion, Clasificacion.Descripcion FROM Clasificacion 
            LEFT JOIN ClasificacionJerarquia ON Clasificacion.idClasificacion=ClasificacionJerarquia.idClasificacion
            WHERE idProducto IN('$grupo_material','18A23F2B-B74A-466A-B888-C7ACF2BB53BC') $txt_variable  
            ORDER BY Clasificacion.Descripcion";
    }else{
        $anexo="AND Jerarquia >= (SELECT Jerarquia FROM ClasificacionJerarquia WHERE idClasificacion='$material_alimentado') $txt_variable";
        if($grupo_material=='F81E2A07-8341-4239-A288-89E4E5FE310A'){
            $anexo="";
        }
        $consulta = "SELECT Clasificacion.idClasificacion, Clasificacion.Descripcion FROM Clasificacion 
            LEFT JOIN ClasificacionJerarquia ON Clasificacion.idClasificacion=ClasificacionJerarquia.idClasificacion
            WHERE idProducto='$grupo_material' /*AND Clasificacion.bMateriaPrima=0*/ $anexo ORDER BY Clasificacion.Descripcion";
    }
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);     
    $resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
    $filas=sqlsrv_num_rows($resultado);
    if($filas>0){
        while($resultados = sqlsrv_fetch_array($resultado)){
            $nombre = utf8_encode($resultados['Descripcion']);
            $id = $resultados['idClasificacion'];
            //Output
            $mensaje.= '<option value="'.$id.'">'.$nombre.'</option>';
        }
    }else{
        $mensaje='<option value="0" selected disabled>No hay jerarquia asignada</option>';
    }
    echo $mensaje;
}elseif($_POST['band'] == 100){   // excel
    $fecha_ini= $_POST['fecha_1'];
    $fecha_fin= $_POST['fecha_2'];
    $empresa_1= $_POST['empresa_1'];
    $proveedor_1= $_POST['proveedor_1'];
    $patio_1= $_POST['patio_1'];
    $actividad_1= $_POST['actividad_1'];
    $tipo_maquinaria_1= $_POST['tipo_maquinaria_1'];
    $Equipo_1= $_POST['Equipo_1'];
    $grupo_material_1= $_POST['grupo_material_1'];
    $material_alimentado_1= $_POST['material_alimentado_1'];
    
    $text= " servicio_clasificacion.empresa = '$empresa_1' AND CAST(servicio_clasificacion.fecha AS DATE) BETWEEN '$fecha_ini' AND '$fecha_fin' ";
    if ($proveedor_1=='0'){
        $text1= '';
    }else{
        $text1= " AND servicio_clasificacion.proveedor ='$proveedor_1'";
    }
    if ($patio_1=='0'){
        $text2='';
    }else{
        $text2= " AND servicio_clasificacion.patio ='$patio_1'";   
    }
    if ($actividad_1=='0'){
        $text3='';
    }else{
        $text3= " AND servicio_clasificacion.actividad ='$actividad_1'";   
    }
    if ($tipo_maquinaria_1=='0'){
        $text4='';
    }else{
        $text4= " AND Equipos.clase_equipo ='$tipo_maquinaria_1'";   
    }
    if ($Equipo_1=='0'){
        $text5='';
    }else{
        $text5= " AND servicio_clasificacion.equipo ='$Equipo_1'";      
    }
    if ($grupo_material_1=='0'){
        $text6='';
    }else{
        $text6= " AND clasificacion.idProducto ='$grupo_material_1'";      
    }
    if ($material_alimentado_1=='0'){
         $text7='';
    }else{
        $text7= " AND servicio_clasificacion.material ='$material_alimentado_1'";         
    }

    $where = $text.$text1.$text2.$text3.$text4.$text5.$text6.$text7;

    $resul_array= array();
    $i=0;//consulta para sacar las clasificaicones de material ese rango de fecha
    $sql_clasi="SELECT '['+Clasificacion.Descripcion+']' as material
        FROM servicio_clasificacion INNER JOIN
            servicio_clasificacion_detalle ON servicio_clasificacion.id_clasif = servicio_clasificacion_detalle.id_clasif INNER JOIN
            Clasificacion ON servicio_clasificacion_detalle.material = Clasificacion.idClasificacion
        WHERE CAST(servicio_clasificacion.fecha as date) between '$fecha_ini' and '$fecha_fin' AND servicio_clasificacion.empresa ='$empresa_1'
        GROUP by Clasificacion.Descripcion";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resul_cla=sqlsrv_query($conn,$sql_clasi,$params,$options);
    $rows = sqlsrv_num_rows($resul_cla);
    if($rows==0)
         $resul_array[$i]='[0]';
    else{
        while ($rows=sqlsrv_fetch_array($resul_cla)) {
            $resul_array[$i] =$rows['material'];
            $i++;
        }
    }
    $count_materiales=count($resul_array);
    $cadena= implode(',',$resul_array);
    $resul_array_clasi= array();
  
    $sql_clasi="SELECT Clasificacion.Descripcion as material
        FROM servicio_clasificacion INNER JOIN
            servicio_clasificacion_detalle ON servicio_clasificacion.id_clasif = servicio_clasificacion_detalle.id_clasif INNER JOIN
            Clasificacion ON servicio_clasificacion_detalle.material = Clasificacion.idClasificacion
        WHERE CAST(servicio_clasificacion.fecha as date) between '$fecha_ini' and '$fecha_fin' AND servicio_clasificacion.empresa ='$empresa_1'
        GROUP by Clasificacion.Descripcion ";
    $resul_clasi= sqlsrv_query($conn, $sql_clasi);

    $sql_pivot="SELECT *
    FROM
    (   SELECT [servicio_clasificacion].[id_clasif], [servicio_clasificacion].[num_recibo], [servicio_clasificacion].[fecha],
        [servicio_clasificacion].[horas_equipo], [servicio_clasificacion].[tm_aliment], [servicio_clasificacion].[pila], [servicio_clasificacion].[semana], 
        [servicio_clasificacion_detalle].[tm], [Proveedores_1].[Alias] as empresa, [Proveedores].[RazonSocial] AS proveedor,
        [Equipos].[Descripcion]+' - '+[Equipos].[Identificacion] as equipo, [Destino].[Descripcion] AS patio, 
        [Actividades].[Descripcion] AS actividad, [UsuariosDetalle].[Nombre1]+' '+[UsuariosDetalle].[Apellido1] as usuario, [Clasificacion].[Descripcion] AS material_alimen,
        [Clas_obj].[Descripcion] AS material_objetivo, [Clasificacion_1].[Descripcion] AS material_producido
    FROM Clasificacion INNER JOIN
        servicio_clasificacion INNER JOIN
        servicio_clasificacion_detalle ON servicio_clasificacion.id_clasif = servicio_clasificacion_detalle.id_clasif INNER JOIN
        Proveedores AS Proveedores_1 ON servicio_clasificacion.empresa = Proveedores_1.idProveedor INNER JOIN
        Proveedores ON servicio_clasificacion.proveedor = Proveedores.idProveedor INNER JOIN
        Equipos ON servicio_clasificacion.equipo = Equipos.idEquipo INNER JOIN
        Destino ON servicio_clasificacion.patio = Destino.idDestino INNER JOIN
        Actividades ON servicio_clasificacion.actividad = Actividades.idActividad INNER JOIN
        UsuariosDetalle ON servicio_clasificacion.usuario = UsuariosDetalle.idUsuario ON Clasificacion.idClasificacion = servicio_clasificacion.material INNER JOIN
        Clasificacion AS Clasificacion_1 ON servicio_clasificacion_detalle.material = Clasificacion_1.idClasificacion INNER JOIN
        Clasificacion AS Clas_obj ON servicio_clasificacion.objetivo = Clas_obj.idClasificacion

        WHERE $where
    ) AS SourceTable PIVOT(SUM([tm]) FOR [material_producido] IN(".$cadena.")) AS PivotTable 
    ORDER BY [num_recibo];";

    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resul_pivot=sqlsrv_query($conn,$sql_pivot,$params,$options);
    $rows = sqlsrv_num_rows($resul_pivot);
    if($rows==0)
        echo 1;
    else{ 
        require ("servicio_clasif_excel.php");
    }
}elseif($_POST['band'] == 199){
    $lugar_trabajo = $_POST['lugar_trabajo'];
    $fecha_min = '1900-01-01';
    $SQL = "SELECT * FROM Destino WHERE Descripcion='$lugar_trabajo'";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resul=sqlsrv_query($conn,$SQL,$params,$options);
    $rows = sqlsrv_num_rows($resul);
    if($rows>0){
        while($aa = sqlsrv_fetch_array($resul)){
            $idDestino = $aa['idDestino'];
        }
        $SQL_DATE = "SELECT ISNULL(MAX(FechaPreparacion),'1900-01-01') as FechaPreparacion FROM Preparacion_recetas WHERE idDestino='$idDestino'";
        $res_SQL_DATE = sqlsrv_query($conn,$SQL_DATE);
        while($aa = sqlsrv_fetch_array($res_SQL_DATE)){
            $fecha_max = date_format($aa['FechaPreparacion'],'Y-m-d');
        }
    }else{
        $idDestino = '0';
    }
    echo $idDestino.'||'.$fecha_max;
}elseif($_POST['band'] == 200){
    $echo = '';
    $receta_preparacion = $_POST['receta_preparacion'];
    $toneladas_preparacion = $_POST['toneladas_preparacion'];
    $SQL = "SELECT Clasificacion.Descripcion AS Clasificacion, Recetas_produccion_detalle.porcentaje FROM Recetas_produccion_detalle 
INNER JOIN Clasificacion ON Recetas_produccion_detalle.idClasificacion=Clasificacion.idClasificacion
WHERE Recetas_produccion_detalle.idReceta='$receta_preparacion'";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resul=sqlsrv_query($conn,$SQL,$params,$options);
    $resul1=sqlsrv_query($conn,$SQL,$params,$options);
    $resul2=sqlsrv_query($conn,$SQL,$params,$options);
    $rows = sqlsrv_num_rows($resul);
    if($rows>0){
        $echo.='<table class="table table-hover table-condensed table-bordered table-responsive table-striped"><tr><th>Clasificación</th>';
        while($aa = sqlsrv_fetch_array($resul)){
            $echo.='<th>'.utf8_encode($aa['Clasificacion']).'</th>';
        }
        $echo.='</tr>';
        $echo.='<tr><th>Procentaje</th>';
        while($aa = sqlsrv_fetch_array($resul1)){
            $echo.='<td>'.utf8_encode($aa['porcentaje']).'%</td>';
        }
        $echo.='</tr>';
        $echo.='<tr><th>Toneladas</th>';
        while($aa = sqlsrv_fetch_array($resul2)){
            if($_POST['select_recepcion'] == 0){
                $tm_porcentaje = $toneladas_preparacion*$aa['porcentaje']/100;
            }else{
                $tm_porcentaje = 0;
            }
            $echo.='<td>'.number_format($tm_porcentaje,2).'</td>';
        }
        $echo.='</tr></table>';
    }
    echo $echo;
}elseif($_POST['band'] == 201){
    $lugar_trabajo_produccion = $_POST['lugar_trabajo_produccion'];
    $fecha_preparacion = $_POST['fecha_preparacion'];
    $receta_preparacion = $_POST['receta_preparacion'];
    $origen_preparacion = $_POST['origen_preparacion'];
    $idDestino = NULL;
    $pasa = 0;
    if($_POST['select_recepcion'] == 1){
        $SQL_ORIGEN = "SELECT * FROM Destino WHERE Descripcion='$origen_preparacion'";
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
        $resul=sqlsrv_query($conn,$SQL_ORIGEN,$params,$options);
        $rows = sqlsrv_num_rows($resul);
        if($rows>0){
            while($aa = sqlsrv_fetch_array($resul)){
                $idDestino = $aa['idDestino'];
            }
        }
    }
    $toneladas_preparacion = $_POST['toneladas_preparacion'];
    if($_POST['select_recepcion'] == 1){
        if($idDestino <> NULL){
            //$pasa = 1;
            $INSERT = "INSERT INTO Preparacion_recetas VALUES (NEWID(),'$receta_preparacion','$lugar_trabajo_produccion','$idDestino','$toneladas_preparacion','$fecha_registro','$fecha_preparacion')";
            $res = sqlsrv_query($conn,$INSERT);
            if($res){
                echo 1;
            }else{
                echo 2;
            }
        }else{
            echo 3;
        }
    }else{
        $INSERT = "INSERT INTO Preparacion_recetas (idPreparacion,idReceta,idDestino,Toneladas,FechaRegistro,FechaPreparacion) VALUES (NEWID(),'$receta_preparacion','$lugar_trabajo_produccion','$toneladas_preparacion','$fecha_registro','$fecha_preparacion')";
        $res = sqlsrv_query($conn,$INSERT);
        if($res){
            echo 1;
        }else{
            echo 2;
        }
    }
}elseif($_POST['band'] == 202){
    $lugar_trabajo_produccion = $_POST['lugar_trabajo_produccion'];
    $SQL = "SELECT Alimentacion_hornos_recetas.Hornos, Alimentacion_hornos_recetas.Tara_hornos, Alimentacion_hornos_recetas.Hornos, Alimentacion_hornos_recetas.idAlimentacion_hornos, Alimentacion_hornos_recetas.FechaAlimentacion,
Recetas_produccion.Descripcion AS Receta, ISNULL(SUM(Alimentacion_hornos_recetas.Toneladas),0) AS Toneladas,
ISNULL(SUM(Produccion_hornos_recetas.Toneladas),0) AS Toneladas_Produccion, ISNULL(SUM(Produccion_hornos_recetas.Hornos),0) AS Hornos_Produccion
FROM alimentacion_hornos_recetas 
INNER JOIN Recetas_produccion ON alimentacion_hornos_recetas.idReceta=Recetas_produccion.idReceta 
LEFT JOIN Produccion_hornos_recetas ON Alimentacion_hornos_recetas.idAlimentacion_hornos=Produccion_hornos_recetas.idAlimentacion_hornos
WHERE idDestino='$lugar_trabajo_produccion' AND alimentacion_hornos_recetas.Ajuste=0
GROUP BY alimentacion_hornos_recetas.Hornos, Alimentacion_hornos_recetas.Tara_hornos, Alimentacion_hornos_recetas.Hornos, Alimentacion_hornos_recetas.idAlimentacion_hornos, Alimentacion_hornos_recetas.FechaAlimentacion,
Recetas_produccion.Descripcion
    ORDER BY Alimentacion_hornos_recetas.FechaAlimentacion DESC";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resul=sqlsrv_query($conn,$SQL,$params,$options);
    $rows = sqlsrv_num_rows($resul);
    $count = 0;
    $echo='<center><h4>Deshornes Pendientes</h4></center><table class="table table-hover table-condensed table-bordered table-responsive table-striped">
            <tr>
                <th>Fecha</th>
                <th>Receta</th>
                <th>N° Hornos</th>
                <th>Tara Hornos</th>
                <th>TM Alimen.</th>
                <th>N° Deshornos</th>
                <th>TM Deshorno</th>
            </tr>';
    if($rows>0){
        $date_pass = '';
        $horno_acumulada = 0;
        $tm_acumulada = 0;
        $tara_acumulada = 0;
        $deshorne_acumulada = 0;
        $tm_deshorne_acumulada = 0;
        $pasa = 0;
        while($aa = sqlsrv_fetch_array($resul)){
            //$toneladas_alimentadas = $aa['Hornos']*$aa['Tara_hornos'];
            if($aa['Hornos']!=$aa['Hornos_Produccion']){
                if($date_pass=='' || $date_pass == date_format($aa['FechaAlimentacion'],'Y-m-d')){
                    $date_pass = date_format($aa['FechaAlimentacion'],'Y-m-d');
                    $echo.='<tr id="'.$aa['idAlimentacion_hornos'].'" class="seleccionado" onclick="cargar_produccion_pendiente(\''.$aa['idAlimentacion_hornos'].'\');">';
                    $echo.='<td>'.date_format($aa['FechaAlimentacion'],'Y-m-d').'</td>';
                    $echo.='<td>'.utf8_encode($aa['Receta']).'</td>';
                    $echo.='<td>'.$aa['Hornos'].'</td>';
                    $echo.='<td>'.number_format($aa['Tara_hornos'],2).'</td>';
                    $echo.='<td>'.number_format($aa['Toneladas'],2).'</td>';
                    $echo.='<td>'.$aa['Hornos_Produccion'].'</td>';
                    $echo.='<td>'.number_format($aa['Toneladas_Produccion'],2).'</td>';
                    $echo.='</tr>';
                    $horno_acumulada+=$aa['Hornos'];
                    $tm_acumulada+=$aa['Toneladas'];
                    $tara_acumulada+=$aa['Tara_hornos'];
                    $deshorne_acumulada+=$aa['Hornos_Produccion'];
                    $tm_deshorne_acumulada+=$aa['Toneladas_Produccion'];
                    $pasa++;
                    //echo $pasa.'a';
                }elseif($date_pass <> date_format($aa['FechaAlimentacion'],'Y-m-d')){
                    if($pasa>1){
                        $tara_acumulada = number_format($tara_acumulada/$pasa,2,'.','');
                        $echo.='<tr style="background-color: #B8AA43;"><td colspan="2"><center><b>'.$date_pass.'</b></center></td><td><b>'.$horno_acumulada.'</b></td><td><b>PROMEDIO '.$tara_acumulada.'</b></td><td><b>'.number_format($tm_acumulada,2,'.',',').'</b></td><td><b>'.$deshorne_acumulada.'</b></td><td><b>'.number_format($tm_deshorne_acumulada,2,'.',',').'</b></td></tr>';
                    }
                    $horno_acumulada = 0;
                    $tm_acumulada = 0;
                    $tara_acumulada = 0;
                    $deshorne_acumulada = 0;
                    $tm_deshorne_acumulada = 0;
                    $pasa = 0;
                    $date_pass = date_format($aa['FechaAlimentacion'],'Y-m-d');
                    $echo.='<tr id="'.$aa['idAlimentacion_hornos'].'" class="seleccionado" onclick="cargar_produccion_pendiente(\''.$aa['idAlimentacion_hornos'].'\');">';
                    $echo.='<td>'.date_format($aa['FechaAlimentacion'],'Y-m-d').'</td>';
                    $echo.='<td>'.utf8_encode($aa['Receta']).'</td>';
                    $echo.='<td>'.$aa['Hornos'].'</td>';
                    $echo.='<td>'.number_format($aa['Tara_hornos'],2).'</td>';
                    $echo.='<td>'.number_format($aa['Toneladas'],2).'</td>';
                    $echo.='<td>'.$aa['Hornos_Produccion'].'</td>';
                    $echo.='<td>'.number_format($aa['Toneladas_Produccion'],2).'</td>';
                    $echo.='</tr>';
                    $horno_acumulada+=$aa['Hornos'];
                    $tm_acumulada+=$aa['Toneladas'];
                    $tara_acumulada+=$aa['Tara_hornos'];
                    $deshorne_acumulada+=$aa['Hornos_Produccion'];
                    $tm_deshorne_acumulada+=$aa['Toneladas_Produccion'];
                    $pasa++;
                    //echo $pasa.'b';
                }
                $count++;
            }
        }
        
        if($pasa>1){
            $echo.='<tr style="background-color: #B8AA43;"><td colspan="2"><center><b>'.$date_pass.'</b></center></td><td><b>'.$horno_acumulada.'</b></td><td><b>'.$tara_acumulada.'</b></td><td><b>'.number_format($tm_acumulada,2,'.',',').'</b></td><td><b>'.$deshorne_acumulada.'</b></td><td><b>'.number_format($tm_deshorne_acumulada,2,'.',',').'</b></td></tr>';
        }
        if($count == 0){
            $echo.='<tr><td colspan="6"><center>No hay deshornes pendientes</center></td></tr>';
        }
    }else{
        $echo.='<tr><td colspan="6"><center>No hay deshornes pendientes</center></td></tr>';
    }
    $echo.='</table>';
    echo $echo;
}elseif($_POST['band'] == 203){
    $lugar_trabajo_produccion = $_POST['lugar_trabajo_produccion'];
    $fecha_alimentacion = $_POST['fecha_alimentacion'];
    $receta_alimentacion = $_POST['receta_alimentacion'];
    $hornos_alimentacion = $_POST['hornos_alimentacion'];
    $tara_hornos_alimentacion = $_POST['tara_hornos_alimentacion'];
    $toneladas_alimentacion = $_POST['toneladas_alimentacion'];
    $ajuste_alimentacion = $_POST['ajuste_alimentacion'];
    ///////////////////////////////////////////////////////////////////////////////
    
    $SQL2 = "SELECT SUM(Toneladas) as Toneladas FROM Preparacion_recetas 
        WHERE idDestino='$lugar_trabajo_produccion' AND idReceta='$receta_alimentacion'";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resul2=sqlsrv_query($conn,$SQL2,$params,$options);
    $rows2 = sqlsrv_num_rows($resul2);
    if($rows2>0){
        while($aa2 = sqlsrv_fetch_array($resul2)){
            $tm_preparacion = $aa2['Toneladas'];
        }
    }else{
        $tm_preparacion = 0;
    }

    $SQL3 = "SELECT SUM(Toneladas) as Toneladas FROM Alimentacion_hornos_recetas 
        WHERE idDestino='$lugar_trabajo_produccion' AND idReceta='$receta_alimentacion'";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resul3=sqlsrv_query($conn,$SQL3,$params,$options);
    $rows3 = sqlsrv_num_rows($resul3);
    if($rows3>0){
        while($aa3 = sqlsrv_fetch_array($resul3)){
            $tm_alimentacion = $aa3['Toneladas'];
        }
    }else{
        $tm_alimentacion = 0;
    }
    $tm_inventario = $tm_preparacion-$tm_alimentacion;
    ///////////////////////////////////////////////////////////////////////////////
    if($tm_inventario>=$toneladas_alimentacion){
        $INSERT = "INSERT INTO alimentacion_hornos_recetas VALUES (NEWID(),'$receta_alimentacion','$lugar_trabajo_produccion','$fecha_alimentacion','$hornos_alimentacion','$tara_hornos_alimentacion','$toneladas_alimentacion','$ajuste_alimentacion','$fecha_registro')";
        $res = sqlsrv_query($conn,$INSERT);
        if($res){
            echo 1;
        }else{
            echo 2;
        }
    }else{
        echo 3;
    }
}elseif($_POST['band'] == 204){
    //$lugar_trabajo_produccion = $_POST['lugar_trabajo_produccion'];
    $idAlimentacion = $_POST['idAlimentacion'];
    $SQL_DATE = "SELECT * FROM alimentacion_hornos_recetas WHERE idAlimentacion_hornos='$idAlimentacion'";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resul_date=sqlsrv_query($conn,$SQL_DATE,$params,$options);
    $rows_date = sqlsrv_num_rows($resul_date);
    if($rows_date>0){
        while ($aa = sqlsrv_fetch_array($resul_date)) {
            $hornos_alimentacion = $aa['Hornos'];
            $fecha_min_produccion = date_format($aa['FechaAlimentacion'],'Y-m-d');
        }
    }
    $SQL_HORNOS = "SELECT ISNULL(SUM(Hornos),0) AS Hornos FROM Produccion_hornos_recetas WHERE idAlimentacion_hornos='$idAlimentacion'";
    $res_hornos = sqlsrv_query($conn,$SQL_HORNOS);
    while($aa_hornos = sqlsrv_fetch_array($res_hornos)){
        $SUM_HORNOS_PRODUCCION = $aa_hornos['Hornos'];
    }
    $echo='<table class="table table-hover table-condensed table-bordered table-responsive table-striped">
        <tr>
            <th>Fecha</th>
            <th>N° Hornos</th>
            <th>Tara Hornos</th>
            <th>TM Deshorno</th>
            <th>Ajuste</th>
        </tr>';
    if($hornos_alimentacion > $SUM_HORNOS_PRODUCCION){
        $SQL = "SELECT * FROM Produccion_hornos_recetas WHERE idAlimentacion_hornos='$idAlimentacion'";
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
        $resul=sqlsrv_query($conn,$SQL,$params,$options);
        $rows = sqlsrv_num_rows($resul);
        
        if($rows>0){
            while($aa = sqlsrv_fetch_array($resul)){
                $toneladas_alimentadas = $aa['Hornos']*$aa['Tara_hornos'];
                $echo.='<tr id="'.$aa['idProduccion_hornos'].'" class="seleccionado_1" onclick="cargue_modificar_produccion(\''.$aa['idProduccion_hornos'].'\');">';
                //$echo.='<tr>';
                $echo.='<td>'.date_format($aa['FechaProduccion'],'Y-m-d').'</td>';
                $echo.='<td>'.$aa['Hornos'].'</td>';
                $echo.='<td>'.number_format($aa['Tara_hornos'],2).'</td>';
                $echo.='<td>'.number_format($aa['Toneladas'],2).'</td>';
                if($aa['Ajuste'] == 1){
                    $echo.='<td><center><button class="btn btn-success btn-xs"><span class="glyphicon glyphicon-ok"></span></button></center></td>';
                }else{
                    $echo.='<td><center>N/A</center></td>';
                }
                $echo.='</tr>';
            }
        }
    }else{
        $echo.='<tr><td colspan="5"><center>No hay más deshornes.</center></td></tr>';
    }
    $fecha_min_produccion = date("Y-m-d",strtotime($fecha_min_produccion."+ 1 days")); 
    echo $echo.'||'.$fecha_min_produccion;
}elseif($_POST['band'] == 205){
    $idAlimentacion = $_POST['idAlimentacion'];
    $fecha_produccion = $_POST['fecha_produccion'];
    $hornos_produccion = $_POST['hornos_produccion'];
    $tara_hornos_produccion = $_POST['tara_hornos_produccion'];
    $toneladas_produccion = $_POST['toneladas_produccion'];
    $ajuste_produccion = $_POST['ajuste_produccion'];
    $SQL = "SELECT Hornos, (SELECT SUM(Hornos) FROM Produccion_hornos_recetas 
            WHERE idAlimentacion_hornos=alimentacion_hornos_recetas.idAlimentacion_hornos) as Hornos_Produccion 
        FROM alimentacion_hornos_recetas WHERE idAlimentacion_hornos='$idAlimentacion'";
    $res = sqlsrv_query($conn,$SQL);
    while($aa = sqlsrv_fetch_array($res)){
        $hornos_alimentacion = $aa['Hornos'];
        $total_hornos_produccion = $aa['Hornos_Produccion'];
    }
    $total_hornos_produccion+=$hornos_produccion;
    if($hornos_alimentacion >= $total_hornos_produccion){
        $INSERT = "INSERT INTO Produccion_hornos_recetas VALUES ('$idAlimentacion',NEWID(),'$fecha_produccion','$hornos_produccion','$tara_hornos_produccion','$toneladas_produccion','$ajuste_produccion','$fecha_registro')";
        $res = sqlsrv_query($conn,$INSERT);
        if($res){
            echo 1;
        }else{
            echo 2;
        }
    }else{
        echo 3;
    }
}elseif($_POST['band'] == 206){
    $echo = '';
    $lugar_trabajo_produccion = $_POST['lugar_trabajo_produccion'];
    $consu_patio = "SELECT Descripcion FROM Destino WHERE idDestino='$lugar_trabajo_produccion'";
    $res_patio = sqlsrv_query($conn,$consu_patio);
    while($aa_patio = sqlsrv_fetch_array($res_patio)){
        $nombre_patio = utf8_encode($aa_patio['Descripcion']);
    }
    $fecha_min_preparacion = "1900-01-01";
    $SQL = "SELECT * FROM Recetas_produccion WHERE idReceta IN (SELECT idReceta FROM Preparacion_recetas WHERE idDestino='$lugar_trabajo_produccion') 
        ORDER BY Descripcion";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resul=sqlsrv_query($conn,$SQL,$params,$options);
    $rows = sqlsrv_num_rows($resul);
    if($rows>0){
        while($aa1 = sqlsrv_fetch_array($resul)){
            $echo.='<div class="col-sm-4">';
            $idReceta = $aa1['idReceta'];
            $SQL2 = "SELECT ISNULL(SUM(Toneladas),0) as Toneladas, ISNULL(MIN(FechaPreparacion),'1900-01-01') AS FechaPreparacion FROM Preparacion_recetas 
                WHERE idDestino='$lugar_trabajo_produccion' AND idReceta='$idReceta'";
            $params = array();
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
            $resul2=sqlsrv_query($conn,$SQL2,$params,$options);
            $rows2 = sqlsrv_num_rows($resul2);
            if($rows2>0){
                while($aa2 = sqlsrv_fetch_array($resul2)){
                    $tm_preparacion = number_format($aa2['Toneladas'],2,'.','');
                    $fecha_min_preparacion = date_format($aa2['FechaPreparacion'],'Y-m-d');
                }
            }else{
                $tm_preparacion = 0;
                $fecha_min_preparacion = "1900-01-01";
            }

            $SQL2_1 = "SELECT ISNULL(SUM(Toneladas),0) as Toneladas FROM Preparacion_recetas 
                WHERE idDestino='$lugar_trabajo_produccion' AND idReceta='$idReceta' AND idOrigen IS NULL";
            $params = array();
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
            $resul2_1=sqlsrv_query($conn,$SQL2_1,$params,$options);
            $rows2_1 = sqlsrv_num_rows($resul2_1);
            if($rows2_1>0){
                while($aa2_1 = sqlsrv_fetch_array($resul2_1)){
                    $tm_preparacion_recepcion = number_format($aa2_1['Toneladas'],2,'.','');
                }
            }else{
                $tm_preparacion_recepcion = 0;
            }

            $SQL3 = "SELECT ISNULL(SUM(Toneladas),0) as Toneladas FROM Alimentacion_hornos_recetas 
                WHERE idDestino='$lugar_trabajo_produccion' AND idReceta='$idReceta'";
            $params = array();
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
            $resul3=sqlsrv_query($conn,$SQL3,$params,$options);
            $rows3 = sqlsrv_num_rows($resul3);
            if($rows3>0){
                while($aa3 = sqlsrv_fetch_array($resul3)){
                    $tm_alimentacion = number_format($aa3['Toneladas'],2,'.','');
                }
            }else{
                $tm_alimentacion = 0;
            }
            $tm_inventario = $tm_preparacion-$tm_alimentacion;
            $tm_inventario_recepcion = $tm_preparacion_recepcion-$tm_alimentacion;
            $echo.='<center>';
            $echo.='<label style="color: green;">MEZCLA - '.utf8_encode($aa1['Descripcion']).' &nbsp; </label><label> '.number_format($tm_inventario,2).' TM </label><br>';
            //$echo.='<label style="color: green;">Preparación: &nbsp;</label><label><u> '.number_format($tm_inventario,2).' Tm &nbsp;</u></label>';
            //$echo.='<label style="color: green;">Recepción: &nbsp;</label><label><u> '.number_format($tm_inventario_recepcion,2).' Tm</u></label>';
            $echo.='</center>';
            $SQL4 = "SELECT Clasificacion.Descripcion AS Clasificacion, Recetas_produccion_detalle.porcentaje FROM Recetas_produccion_detalle 
                INNER JOIN Clasificacion ON Recetas_produccion_detalle.idClasificacion=Clasificacion.idClasificacion
                WHERE Recetas_produccion_detalle.idReceta='$idReceta'";
            $params = array();
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
            $resul4=sqlsrv_query($conn,$SQL4,$params,$options);
            $resul4_1=sqlsrv_query($conn,$SQL4,$params,$options);
            $resul4_2=sqlsrv_query($conn,$SQL4,$params,$options);
            $rows4 = sqlsrv_num_rows($resul4);
            if($rows4>0){
                $echo.='<table class="table table-hover table-condensed table-bordered table-responsive table-striped">';
                $echo.='<tr><th>Clasificación</th>';
                while($aa4 = sqlsrv_fetch_array($resul4)){
                    $echo.='<th>'.utf8_encode($aa4['Clasificacion']).'</th>';
                }
                /*$echo.='</tr>';
                $echo.='<tr><th>Procentaje</th>';
                while($aa4_1 = sqlsrv_fetch_array($resul4_1)){
                    $echo.='<td>'.utf8_encode($aa4_1['porcentaje']).'%</td>';
                }*/
                $echo.='</tr>';
                $echo.='<tr><th>Toneladas</th>';
                while($aa4_2 = sqlsrv_fetch_array($resul4_2)){
                    $tm_porcentaje = $tm_inventario_recepcion*$aa4_2['porcentaje']/100;
                    $echo.='<td>'.number_format($tm_porcentaje,2).'</td>';
                }
                $echo.='</tr></table>';
            }
            //$echo.='</div>';
            $echo.='</div>';
        }
    }
    echo $echo.'||'.$nombre_patio."||".$fecha_min_preparacion;
}elseif($_POST['band'] == 207){
    $idProduccion = $_POST['idProduccion'];
    $SQL = "SELECT * FROM Produccion_hornos_recetas WHERE idProduccion_hornos='$idProduccion'";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
    $resul=sqlsrv_query($conn,$SQL,$params,$options);
    $rows = sqlsrv_num_rows($resul);
    if($rows>0){
        while($aa = sqlsrv_fetch_array($resul)){
            $FechaProduccion = date_format($aa['FechaProduccion'],'Y-m-d');
            $Hornos = $aa['Hornos'];
            $Tara_hornos = $aa['Tara_hornos'];
            $Toneladas = $aa['Toneladas'];
            $Ajuste = $aa['Ajuste'];
        }
    }
    echo $FechaProduccion.'||'.$Hornos.'||'.$Tara_hornos.'||'.$Toneladas.'||'.$Ajuste;
}elseif($_POST['band'] == 208){
    $idProduccion = $_POST['idProduccion'];
    //////////////////////////////////////////////////////////////
    $idAlimentacion = $_POST['idAlimentacion'];
    $fecha_produccion = $_POST['fecha_produccion'];
    $hornos_produccion = $_POST['hornos_produccion'];
    $tara_hornos_produccion = $_POST['tara_hornos_produccion'];
    $toneladas_produccion = $_POST['toneladas_produccion'];
    $ajuste_produccion = $_POST['ajuste_produccion'];
    if(($hornos_produccion == '' || $hornos_produccion == '0') && ($tara_hornos_produccion == '' || $tara_hornos_produccion == '0') && ($toneladas_produccion == '' || $toneladas_produccion == '0')){
        $DELETE = "DELETE FROM Produccion_hornos_recetas WHERE idProduccion_hornos='$idProduccion'";
        $res_delete = sqlsrv_query($conn,$DELETE);
        if($res_delete){
            echo 1;
        }else{
            echo 2;
        }
    }else{
        $SQL = "SELECT Hornos, (SELECT SUM(Hornos) FROM Produccion_hornos_recetas 
                WHERE idAlimentacion_hornos=alimentacion_hornos_recetas.idAlimentacion_hornos) as Hornos_Produccion 
            FROM alimentacion_hornos_recetas WHERE idAlimentacion_hornos='$idAlimentacion'";
        $res = sqlsrv_query($conn,$SQL);
        while($aa = sqlsrv_fetch_array($res)){
            $hornos_alimentacion = $aa['Hornos'];
            $total_hornos_produccion = $aa['Hornos_Produccion'];
        }
        $SQL_PRODUCCION = "SELECT * FROM Produccion_hornos_recetas WHERE idProduccion_hornos='$idProduccion'";
        $res_produccion = sqlsrv_query($conn,$SQL_PRODUCCION);
        while($aa_prod = sqlsrv_fetch_array($res_produccion)){
            //$hornos_alimentacion = $aa['Hornos'];
            $total_hornos_produccion = $total_hornos_produccion-$aa_prod['Hornos'];
        }
        $total_hornos_produccion+=$hornos_produccion;
        if($hornos_alimentacion >= $total_hornos_produccion){
            $UPDATE = "UPDATE Produccion_hornos_recetas SET FechaProduccion='$fecha_produccion',Hornos='$hornos_produccion',
                Tara_hornos='$tara_hornos_produccion',Toneladas='$toneladas_produccion',Ajuste='$ajuste_produccion' WHERE idProduccion_hornos='$idProduccion'";
            //$INSERT = "INSERT INTO Produccion_hornos_recetas VALUES ('$idAlimentacion',NEWID(),'$fecha_produccion','$hornos_produccion','$tara_hornos_produccion','$toneladas_produccion','$ajuste_produccion','$fecha_registro')";
            $res = sqlsrv_query($conn,$UPDATE);
            if($res){
                echo 1;
            }else{
                echo 2;
            }
        }else{
            echo 3;
        }
    }
}
?>