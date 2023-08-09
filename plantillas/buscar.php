<?php
include('../modelo/conexion.php');

if ($_POST['band'] == 1) 
{   $id_empresa = $_POST['empresa'];
    $id_cliente = $_POST['cliente'];
    $seleccionado = $_POST['seleccionado'];
    $mensaje = "";
    $consulta = "SELECT * FROM Factura_Venta WHERE id_empresa='$id_empresa' AND id_cliente='$id_cliente' AND
    estado=1 and tipo_factura=1";
    $consulta1 = $consulta;
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
    $resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
    $filas=sqlsrv_num_rows($resultado);  
  //    return($resultado);
    if ($filas == 0) {
        $mensaje = '<option value="0" selected>No hay pre-facturas</option>';//"<p>No hay ningún usuario con ese nombre y/o apellido</p>";
    } else {
        $valor = 0;
        $res = sqlsrv_query($conn,$consulta1);
        while($resultados = sqlsrv_fetch_array($res)) {
            if($seleccionado == $resultados['id_factura_venta']){
                $valor++;
            }
        }
        //echo 'Resultados para <strong>'.$consultaBusqueda.'</strong>';
        if($seleccionado == 0){
            $mensaje .= '<option value="0" selected>Seleccione</option>';
        }else{
            if($valor == 0){
                $mensaje .= '<option value="0" selected>Seleccione</option>';
            }else{
                $mensaje .= '<option value="0">Seleccione</option>';
            }
        }
        while($resultados = sqlsrv_fetch_array($resultado)) {
            $numerofact = $resultados['prefijo_factura'].' - '.$resultados['numero_factura'];
            //$apellido = $resultados['Alias'];
            $id = $resultados['id_factura_venta'];
            //Output
            if($id == $seleccionado){
                $mensaje.= '<option value="'.$id.'" selected>'.$numerofact.'</option>';
            }else{
                $mensaje.= '<option value="'.$id.'">'.$numerofact.'</option>';
            }
        };//Fin while $resultados
    }; //Fin else $filas
    echo $mensaje;
}elseif($_POST['band'] == 2){
    $tipo_maquinaria = $_POST['tipo_maquinaria'];
    $seleccionado = $_POST['seleccionado'];
    $mensaje = "";
    if (isset($tipo_maquinaria)) 
    {   $consulta = "SELECT Equipos.idEquipo, Equipos.Descripcion, Equipos.Identificacion FROM Equipos 
            INNER JOIN detalle_equipos ON Equipos.idEquipo=detalle_equipos.idEquipos
            INNER JOIN EquiposGrupos ON detalle_equipos.clase_equipo = EquiposGrupos.idGrupo
            WHERE EquiposGrupos.Descripcion!='Cargador' AND detalle_equipos.clase_equipo='$tipo_maquinaria'";
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
        $resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
        $filas=sqlsrv_num_rows($resultado);  
      //return($resultado);
        if ($filas == 0) {
            $mensaje = '<option value="0" selected>No hay maquinaria</option>';//"<p>No hay ningún usuario con ese nombre y/o apellido</p>";
        }else{
            //echo 'Resultados para <strong>'.$consultaBusqueda.'</strong>';
            if($seleccionado == 0){
                $mensaje .= '<option value="0" selected>TODAS</option>';
            }else{
                $mensaje .= '<option value="0">TODAS</option>';
            }
            while($resultados = sqlsrv_fetch_array($resultado)) {
                $maquina = $resultados['Descripcion'].' - '.$resultados['Identificacion'];
                $id = $resultados['idEquipo'];
                //Output
                if($id == $seleccionado){
                    $mensaje.= '<option value="'.$id.'" selected>'.$maquina.'</option>';
                }else{
                    $mensaje.= '<option value="'.$id.'">'.$maquina.'</option>';
                }
            }//Fin while $resultados
        } //Fin else $filas
    }//Fin isset $consultaBusqueda
    //Devolvemos el mensaje que tomará jQuery
    echo $mensaje;  
} //Fin isset $consultaBusqueda
//Devolvemos el mensaje que tomará jQuery
?>