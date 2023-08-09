<?php
session_start();
require_once '../modelo/conexion.php';
//error_reporting(0);
if ($_SESSION["logueado"] == TRUE && ($_SESSION["permisoIngresar"] == 'CONSULTAS_OFICINA' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES')){
    $usuario = $_SESSION['usuario'];
    $Fecha = date('Y-m-d');
    $Fecha_actual = date('Y-m-d',strtotime($Fecha . ' - 15 days'));
    ini_set('date.timezone', 'America/Bogota');
    $hora = date("g:i A");
    $sql_usuario = "SELECT * FROM Usuarios WHERE idUsuario='$usuario'";
    $result = sqlsrv_query($conn,$sql_usuario);
    while ($row = sqlsrv_fetch_array($result)){
      $Nombre = $row['NombreUsuarioLargo'];
    }

}elseif($_SESSION['permisoIngresar'] == 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES'){
    header("Location: MantenimientoCargadores.php");
}elseif($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){
    header("Location: incio.php");
}elseif($_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO'){
    header("Location: inicio_patio.php");
}else{
    header("Location: ../index.php");
    die();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <?php include './libreria.php'; ?>
</head>
<body>
    <br><br>
    <table class="table table-hover table-condensed table-bordered table-responsive table-striped">
        <tr>
            <th rowspan="2"><center>EMPRESA</center></th>
            <th rowspan="2"><center>ACOPIO</center></th>
            <th rowspan="2"><center>OPERACION</center></th>
            <th rowspan="2"><center>PROVEEDOR</center></th>
            <th rowspan="2"><center>FECHA TIQUE</center></th>
            <th rowspan="2"><center>OPERARIO</center></th>
            <th rowspan="2"><center>EQUIPO</center></th>
            <th rowspan="2"><center>COD REPORTE</center></th>
            <th colspan="2"><center>CLASIFICAR ROOM</center></th>
            <th colspan="2"><center>CLASIFICAR SOBRETAMAÑO</center></th>
            <th><center>DESPACHO<center></th>
            <th colspan="3"><center>ENTRADAS</center></th>
            <th colspan="2"><center>MOLIENDA</center></th>
            <th><center>STANDBAY</center></th>
         </tr>
         <tr>
            <td><center>APILAR</center></td>
            <td><center>ALIMENTAR</center></td>
            <td><center>APILAR</center></td>
            <td><center>ALIMENTAR</center></td>
            <td><center>CARGAR DESPACHO</center></td>
            <td><center>APILAR</center></td>
            <td><center>MVTO. X CALIDAD</center></td>
            <td><center>OFICIOS VARIOS</center></td>
            <td><center>APILAR</center></td>
            <td><center>ALIMENTAR</center></td>
            <td>STANDBAY</td>
         </tr>
        <?php
        $sql = "SELECT Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                         Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado,
                         Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                         Usuarios.NombreUsuarioLargo
                    FROM Registro_tique_cargadores INNER JOIN
                         Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                         Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                         Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                         Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
                    WHERE Registro_tique_cargadores.Estado = '3'
                    ORDER BY Registro_tique_cargadores.cod_reporte DESC";
        $res = sqlsrv_query($conn,$sql);
        while($r = sqlsrv_fetch_array($res)){
            $band = 0;
            $cont = 0;
            $cont1 = 0;
            $cont2 = 0;
            $cont3 = 0;
            $cont4 = 0;
            $cont5 = 0;
            $Array_valor = Array();
            ?>
            <tr>
            <td><?php echo $r['NombreCorto']; ?></td>
            <td><?php echo $r['Descripcion']; ?></td>
            <td><?php echo $r['servicio_clasificacion']; ?></td>
            <td><?php echo 'PROVEEDOR'; ?></td>
            <td><?php echo date_format($r['fecha_apertura_tique'],'Y-m-d'); ?></td>
            <td><?php echo $r['NombreUsuarioLargo']; ?></td>
            <td><?php echo $r['NombreCargador'].' - '.$r['Identificacion']; ?></td>
            <td><?php echo $r['cod_reporte']; ?></td>
            <?php
            $sql_1 = "SELECT horometro.fecha_cierre_horometro ,horometro.id_horometro, horometro.id_registro, horometro.horometro_inicial, 
                horometro.horometro_final, horometro.total_horas, horometro.idActividad, horometro.fecha_registro_horometro, 
                Actividades.Descripcion, SubActividades_cargadores.Descripcion as Descripcion_sub, horometro.observaciones
                            FROM horometro LEFT JOIN
                            Actividades ON horometro.idActividad = Actividades.idActividad
                            LEFT JOIN SubActividades_cargadores ON horometro.idSubActividad=SubActividades_cargadores.idSubActividad
                           WHERE horometro.id_registro='". $r['id_registro'] ."' ORDER BY Actividades.Descripcion,SubActividades_cargadores.Descripcion";
            $res_1 = sqlsrv_query($conn,$sql_1);
            while($horometro = sqlsrv_fetch_array($res_1)){

                /*if($horometro['Descripcion'] != ""){
                    echo '<td>'.utf8_encode($horometro['Descripcion']).'</td>';
                }else{
                    echo '<td>FALTA POR ASIGNAR</td>';
                }
                if($horometro['Descripcion_sub'] != ""){
                    echo '<td>'.utf8_encode($horometro['Descripcion_sub']).'</td>';
                }else{
                    echo '<td>FALTA POR ASIGNAR</td>';
                }*/
                if($band == 0){
                    if($horometro['Descripcion'] == 'CLASIFICAR ROOM'){
                        $cont++;
                        if($horometro['Descripcion_sub'] == 'APILAR'){
                            echo '<td>'.number_format($horometro['total_horas'],1,',','.').'</td>';
                            $Array_valor[0] = number_format($horometro['total_horas'],1,',','.');
                            $paso = 1;
                        }elseif($horometro['Descripcion_sub'] == 'ALIMENTAR'){
                            echo '<td>0,0</td>';
                            echo '<td>'.number_format($horometro['total_horas'],1,',','.').'</td>';
                            $Array_valor[1] = number_format($horometro['total_horas'],1,',','.');
                            $paso = 2;
                            $cont++;
                        }elseif($horometro['Descripcion_sub'] != 'ALIMENTAR'){
                            echo '<td>0,0</td>';
                            $Array_valor[1] = 0;
                            $paso = 2;
                        }
                    }else{
                        if($cont == 0){
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                        }elseif($cont == 1) {
                            echo '<td>0,0</td>';
                        }elseif($cont == 2){
                        }
                        if(utf8_encode($horometro['Descripcion']) == 'CLASIFICAR SOBRETAMAÑO'){
                            $cont1++;
                            $band++;
                            if($horometro['Descripcion_sub'] == 'APILAR'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }elseif($horometro['Descripcion_sub'] == 'ALIMENTAR'){
                                echo '<td>0,00</td>';
                                echo '<td>'.number_format($horometro['total_horas'],1,',','.').'</td>';
                                $cont1++;
                            }elseif($horometro['Descripcion_sub'] != 'ALIMENTAR'){
                                echo '<td>0,00</td>';
                            }
                        }elseif(utf8_encode($horometro['Descripcion']) == 'DESPACHO'){
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            $band+=2;
                            $cont2++;
                            if($horometro['Descripcion_sub'] == 'CARGAR DESPACHO'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }else{
                                echo '<td>0,0</td>';
                            }
                        }elseif(utf8_encode($horometro['Descripcion']) == 'ENTRADAS'){
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            $band+=3;
                            $cont3++;
                            if($horometro['Descripcion_sub'] == 'APILAR'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }elseif($horometro['Descripcion_sub'] == 'MVTO. X CALIDAD'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }elseif($horometro['Descripcion_sub'] == 'OFICIOS VARIOS'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }else{
                                echo '<td>0,0</td>';
                            }
                        }elseif(utf8_encode($horometro['Descripcion']) == 'MOLIENDA'){
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            $cont4++;
                            $band+=4;
                            if($horometro['Descripcion_sub'] == 'APILAR'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }elseif($horometro['Descripcion_sub'] == 'ALIMENTAR'){
                                echo '<td>0,00</td>';
                                echo '<td>'.number_format($horometro['total_horas'],1,',','.').'</td>';
                                $cont1++;
                            }elseif($horometro['Descripcion_sub'] != 'ALIMENTAR'){
                                echo '<td>0,00</td>';
                            }
                        }elseif(utf8_encode($horometro['Descripcion']) == 'STANDBAY'){
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            $band+=5;
                            $cont5++;
                            if($horometro['Descripcion_sub'] == 'STANDBAY'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }else{
                                echo '<td>0,0</td>';
                            }
                        }
                        
                    }
                    
                }elseif($band == 1){
                    if(utf8_encode($horometro['Descripcion']) == 'CLASIFICAR SOBRETAMAÑO'){
                        $cont1++;
                        if($horometro['Descripcion_sub'] == 'APILAR'){
                            echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                        }elseif($horometro['Descripcion_sub'] == 'ALIMENTAR'){
                            echo '<td>0,00</td>';
                            echo '<td>'.number_format($horometro['total_horas'],1,',','.').'</td>';
                            $cont1++;
                        }elseif($horometro['Descripcion_sub'] != 'ALIMENTAR'){
                            echo '<td>0,00</td>';
                        }
                    }else{
                        if($cont1 == 0){
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                        }elseif($cont1 == 1) {
                            echo '<td>0,0</td>';
                        }elseif($cont1 == 2){
                        }
                        if(utf8_encode($horometro['Descripcion']) == 'DESPACHO'){
                            $band++;
                            $cont2++;
                            if($horometro['Descripcion_sub'] == 'CARGAR DESPACHO'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }else{
                                echo '<td>0,0</td>';
                            }
                        }elseif(utf8_encode($horometro['Descripcion']) == 'ENTRADAS'){
                            echo '<td>0,0</td>';
                            $band+=2;
                            $cont3++;
                            if($horometro['Descripcion_sub'] == 'APILAR'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }elseif($horometro['Descripcion_sub'] == 'MVTO. X CALIDAD'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }elseif($horometro['Descripcion_sub'] == 'OFICIOS VARIOS'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }else{
                                echo '<td>0,0</td>';
                            }
                        }elseif(utf8_encode($horometro['Descripcion']) == 'MOLIENDA'){
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            $cont4++;
                            $band+=3;
                            if($horometro['Descripcion_sub'] == 'APILAR'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }elseif($horometro['Descripcion_sub'] == 'ALIMENTAR'){
                                echo '<td>0,00</td>';
                                echo '<td>'.number_format($horometro['total_horas'],1,',','.').'</td>';
                                $cont1++;
                            }elseif($horometro['Descripcion_sub'] != 'ALIMENTAR'){
                                echo '<td>0,00</td>';
                            }
                        }elseif(utf8_encode($horometro['Descripcion']) == 'STANDBAY'){
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            $band+=4;
                            $cont5++;
                            if($horometro['Descripcion_sub'] == 'STANDBAY'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }else{
                                echo '<td>0,0</td>';
                            }
                        }
                    }
                    
                }elseif($band == 2){
                    if(utf8_encode($horometro['Descripcion']) == 'DESPACHO'){
                        $cont2++;
                        if($horometro['Descripcion_sub'] == 'CARGAR DESPACHO'){
                            echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                        }else{
                            echo '<td>0,0</td>';
                        }
                    }else{
                        if($cont2 == 0){
                            echo '<td>0,0</td>';
                        }elseif($cont2 == 1) {  }
                        elseif(utf8_encode($horometro['Descripcion']) == 'ENTRADAS'){
                            echo '<td>0,0</td>';
                            $band++;
                            $cont3++;
                            if($horometro['Descripcion_sub'] == 'APILAR'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }elseif($horometro['Descripcion_sub'] == 'MVTO. X CALIDAD'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }elseif($horometro['Descripcion_sub'] == 'OFICIOS VARIOS'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }else{
                                echo '<td>0,0</td>';
                            }
                        }elseif(utf8_encode($horometro['Descripcion']) == 'MOLIENDA'){
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            $cont4++;
                            $band+=2;
                            if($horometro['Descripcion_sub'] == 'APILAR'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }elseif($horometro['Descripcion_sub'] == 'ALIMENTAR'){
                                echo '<td>0,00</td>';
                                echo '<td>'.number_format($horometro['total_horas'],1,',','.').'</td>';
                                $cont1++;
                            }elseif($horometro['Descripcion_sub'] != 'ALIMENTAR'){
                                echo '<td>0,00</td>';
                            }
                        }elseif(utf8_encode($horometro['Descripcion']) == 'STANDBAY'){
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            $band+=3;
                            $cont5++;
                            if($horometro['Descripcion_sub'] == 'STANDBAY'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }else{
                                echo '<td>0,0</td>';
                            }
                        }

                    }
                    $band++;
                }elseif($band == 3){
                    if(utf8_encode($horometro['Descripcion']) == 'ENTRADAS'){
                        $cont3++;
                        if($horometro['Descripcion_sub'] == 'APILAR'){
                            echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                        }elseif($horometro['Descripcion_sub'] == 'MVTO. X CALIDAD'){
                            echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                        }elseif($horometro['Descripcion_sub'] == 'OFICIOS VARIOS'){
                            echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                        }else{
                            echo '<td>0,0</td>';
                        }
                    }else{
                        if($cont3 == 0){
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                        }elseif($cont3 == 1) {
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                        }elseif($cont3 == 2){
                            echo '<td>0,0</td>';
                        }elseif($cont3 == 3){   }
                        if(utf8_encode($horometro['Descripcion']) == 'MOLIENDA'){
                            $cont4++;
                            $band++;
                            if($horometro['Descripcion_sub'] == 'APILAR'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }elseif($horometro['Descripcion_sub'] == 'ALIMENTAR'){
                                echo '<td>0,00</td>';
                                echo '<td>'.number_format($horometro['total_horas'],1,',','.').'</td>';
                                $cont1++;
                            }elseif($horometro['Descripcion_sub'] != 'ALIMENTAR'){
                                echo '<td>0,00</td>';
                            }
                        }elseif(utf8_encode($horometro['Descripcion']) == 'STANDBAY'){
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                            $band+=2;
                            $cont5++;
                            if($horometro['Descripcion_sub'] == 'STANDBAY'){
                                echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                            }else{
                                echo '<td>0,0</td>';
                            }
                        }
                    }
                }elseif($band == 4){
                    if(utf8_encode($horometro['Descripcion']) == 'MOLIENDA'){
                        $cont4++;
                        if($horometro['Descripcion_sub'] == 'APILAR'){
                            echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                        }elseif($horometro['Descripcion_sub'] == 'ALIMENTAR'){
                            $cont4++;
                            echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                        }else{
                            echo '<td>0,0</td>';
                        }
                    }else{
                        if($cont4 == 0){
                            echo '<td>0,0</td>';
                            echo '<td>0,0</td>';
                        }elseif($cont4 == 1){
                            echo '<td>0,0</td>';
                        }elseif($cont4 == 2){   }
                        
                    }
                }elseif($band == 5){
                    if(utf8_encode($horometro['Descripcion']) == 'STANDBAY'){
                        if($horometro['Descripcion_sub'] == 'STANDBAY'){
                            echo '<td>'.number_format($horometro['total_horas'],2,',','.').'</td>';
                        }else{
                            echo '<td>0,0</td>';
                        }
                    }else{
                        echo '<td>0,0</td>';
                    }
                    $band++;
                }
            }
            /*$sql_2 = "SELECT * FROM tiempos_cargadores_actividad 
                    INNER JOIN Equipos ON tiempos_cargadores_actividad.idMaquinaria = Equipos.idEquipo
                    INNER JOIN SubActividades_cargadores ON tiempos_cargadores_actividad.idSubActividad = SubActividades_cargadores.idSubActividad
                    INNER JOIN ";*/
            /*$sql_2 = "SELECT * FROM TarifaMaquinaria WHERE Fecha_Hasta='1900-01-01' AND idEquipo='".$r['id_maquinaria']."'";
            $res_2 = sqlsrv_query($conn,$sql_2);
            while($tarifa = sqlsrv_fetch_array($res_2)){
                echo '<td>'.$tarifa['Tarifa_Toneladas'].'</td>';
                echo '<td>'.$tarifa['Tarifa_Horometro'].'</td>';
                echo '<td>'.$tarifa['Iva'].'</td>';
            }
            $sql_3 = "SELECT * FROM horometro_descuento_cargadores WHERE IdRegistro='".$r['id_registro']."'";
            $res_3 = sqlsrv_query($conn,$sql_3);
            while($desc = sqlsrv_fetch_array($res_3)){
                echo '<td>'.utf8_encode($desc['descripcion']).'</td>';
                echo '<td>'.$desc['valor_descuento'].'</td>';
            }*/
        }
        ?>
    </table>
</body>
</html>