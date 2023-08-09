<?php
session_start();
require_once '../modelo/conexion.php';
//error_reporting(0);
if ($_SESSION["logueado"] == TRUE && $_SESSION["usuario"]) {
    $id_mantto = $_GET['id_mantto'];
    $sql = "SELECT Mantenimiento_equipos.idxid, Mantenimiento_equipos.idEquipo, Mantenimiento_equipos.horo_mtto_inicial,
            Mantenimiento_equipos.horo_mtto_final, Mantenimiento_equipos.total_horas, Mantenimiento_equipos.fecha_inicial_mtto, 
            Mantenimiento_equipos.fecha_final_mtto, Mantenimiento_equipos.observaciones, Equipos.Descripcion, Equipos.Identificacion, 
            Proveedores.RazonSocial, EquiposGrupos.Descripcion AS Categoria, Usuarios.NombreUsuarioLargo
    FROM Mantenimiento_equipos INNER JOIN
        Equipos ON Mantenimiento_equipos.idEquipo = Equipos.idEquipo INNER JOIN
        detalle_equipos ON Mantenimiento_equipos.idEquipo = detalle_equipos.idEquipos INNER JOIN
        Proveedores ON Equipos.idPropietario = Proveedores.idProveedor INNER JOIN
        EquiposGrupos ON detalle_equipos.clase_equipo = EquiposGrupos.idGrupo INNER JOIN
        Usuarios ON Mantenimiento_equipos.id_usuario = Usuarios.idUsuario
    WHERE Mantenimiento_equipos.idxid='$id_mantto'";
    $res = sqlsrv_query($conn,$sql);
    while($mantto = sqlsrv_fetch_array($res)){
        $horo_mtto_inicial = $mantto['horo_mtto_inicial'];
        $horo_mtto_final = $mantto['horo_mtto_final'];
        $fecha_inicial_mtto = $mantto['fecha_inicial_mtto'];
        $flota = $mantto['Categoria'];
        $equipo = $mantto['Descripcion']." - ".$mantto['Identificacion'];
        $horo_resultado = $horo_mtto_final-$horo_mtto_inicial;
    }
    $usuario = $_SESSION['usuario'];
    $Fecha = date('Y-m-d');
    $fecha_hora = date('Y-m-d H:i:s');
    $Fecha_actual = date('Y-m-d',strtotime($Fecha . ' - 15 days'));
    ini_set('date.timezone', 'America/Bogota');
    $hora = date("g:i A");
    $sql_usuario = "SELECT * FROM Usuarios WHERE idUsuario='$usuario'";
    $result = sqlsrv_query($conn,$sql_usuario);
    while ($row = sqlsrv_fetch_array($result)){
      $Nombre = $row['NombreUsuarioLargo'];
    }
} else {
    header("Location: ../index.php");
    die();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>PLANTILLA 2 GENERAL -  WILSON</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php include 'libreria.php'; ?>
        <script type="text/javascript" src="../controlador/mantenimiento.js"></script>
    </head>
<body> 
    <?php
    include 'Header.php';
    ?>
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10" >
            <br><br>
            <tr>
                <h1> <center><img src="IMAGENES\logo_latam_1.jpg" border="0" width="300" height="70"></center></h1>
            </tr>
                <tr>
                <h1> <center>LATAM COAL MINES S.A.S.</center></h1>
            </tr>
            
            <table border="2" width="100%" class="table table-hover table-condensed table-bordered table-responsive table-striped">
               <tr>
                  <th colspan="8" style="background-color:#2AA2E3; color:white;"><h3><center>PLAN DE MANTENIMIENTO PREVENTIVO  250HRS</center></h3></th>
               </tr>

               <tr>
                  <td style="width: 10%"><center>FRECUENCIA</center></td>
                  <td style="width: 10%" colspan="2"><center>TIEMPO DE EJECUCIÓN</center></td>
                  <td style="width: 25%"><center> FECHA Y HORA DE EJECUCIÓN</center></td>
                  <td style="width: 10%"><center> FLOTA</center></td>
                  <td style="width: 10%"><center> EQUIPO</center></td>
                  <td style="width: 15%;"><center> HOROMETRO</center></td>
                  <td style="width: 20%"><center> TIPO</center></td>   
               </tr>
               <tr>
                  <td rowspan="2"><center>250 (HRS)</center></td>
                  <td style="width: 10%"><center>H. INICIAL</center></td>
                  <td style="width: 10%"><center>H. FINAL</center></td>
                  <td rowspan="2"><center><?php echo date_format($fecha_inicial_mtto, 'Y/m/d H:i:s'); ?></center></td>
                  <td rowspan="2"><center><?php echo $flota; ?></center></td>
                  <td rowspan="2"><center><?php echo $equipo; ?></center></td>
                  <td rowspan="2"><center><?php echo number_format($horo_resultado,1,',','.'); ?></center></td>
                  <td rowspan="2"><center>Variable Tipo Eventual</center></td>
               </tr>
               <tr>
                    <td><center><?php echo $horo_mtto_inicial; ?></center></td>
                    <td><center><?php echo $horo_mtto_final; ?></center></td>
               </tr>                  
               <tr>
                  <th colspan="8" style="background-color:#FFFBC5"><H4><center></center></H4></th>
               </tr>
            </table>
            <form name="form3" id="form3">
                <table border="2" width="100%" class="table table-hover table-condensed table-bordered table-responsive table-striped">
                 <tr>
                    <th colspan="5" style="background-color:#2AA2E3;color:black;"><H4><center>DESCRIPCIÓN DEL TRABAJO</center></H4></th>
                 </tr>
                 <tr>
                    <!--<th colspan="5" style="background-color:#FFFBC5"><H4><center></center></H4></th>-->
                 </tr>
                 <tr>
                    <th style="width: 2%;"><center>SECCIÓN</center></th>
                    <th style="width: 1%"><center>N°</center></th>
                    <th style="width: 65%;"><center>ACTIVIDADES</center></th>
                    <th><center>APLICADO</center></th>
                    <th><center><p title="Debe registrar las personas que hicieron dicha actividad">OBSERVACIONES</p></center></th>
                 </tr>
                 <?php
                  $sql_actividad = "SELECT Actividades.idActividad, Subactividades_cargadores.idSubactividad, Subactividades_cargadores.Descripcion as Descripcion_sub,
                  Actividades.Descripcion FROM Actividades
                  INNER JOIN Subactividades_cargadores ON Actividades.idActividad=Subactividades_cargadores.idActividad
                  WHERE idTipoActividad='00000000-0000-0000-0000-000000000003' order by Actividades.Descripcion";
                  $params = array();
                  $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                  $resultado=sqlsrv_query($conn,utf8_decode($sql_actividad),$params,$options);
                  $rowss=sqlsrv_num_rows($resultado);
                  if ($rowss > 0) 
                  {     $num = 1;
                        $paso = 0;

                        $tem_pat="a";
                        $a=0;
                        $array_patio= array();  //Para sabar cuantos registros hay por patio
                        $cont_pat=0;
                        while($rows = sqlsrv_fetch_array($resultado)){
                            if ($tem_pat<>$rows['Descripcion'])
                            {   if($tem_pat=="a")
                                {   $tem_pat=$rows['Descripcion'];
                                    $cont_pat+=1;
                                }
                                else
                                {   $tem_pat=$rows['Descripcion'];
                                    $array_patio[$a]=$cont_pat;
                                    $cont_pat=1;
                                    $a+=1;
                                }
                            }
                            else
                            {   $cont_pat+=1; }
                        }
                        $array_patio[$a]=$cont_pat;
                        $resultado=sqlsrv_query($conn,utf8_decode($sql_actividad));
                        $tem_pat="a";
                        $a=0;
                      while($rows = sqlsrv_fetch_array($resultado)){
                          ?>
                          <tr>
                              <?php 
                                if ($tem_pat<>$rows['Descripcion'])
                                {   if($tem_pat=="a")
                                    {   ?><td rowspan="<?php echo $array_patio[$a]; ?>"><center><?php echo utf8_encode($rows['Descripcion']); ?></center></td>
                                        <?php
                                        $tem_pat=$rows['Descripcion'];
                                        $a+=1;
                                    /*  if ($patio=='FATIBAR')
                                        {   $cont_pat+=1;}   */
                                        //$cont_pat+=1;
                                    }
                                    else
                                    {   ?><td rowspan="<?php echo $array_patio[$a]; ?>"><center><?php echo utf8_encode($rows['Descripcion']); ?></center></td>
                                        <?php
                                        $tem_pat=$rows['Descripcion'];
                                        //$array_patio[$a]=$cont_pat;
                                        //$cont_pat=1;
                                    /*  if ($patio=='FATIBAR')
                                        {   $cont_pat+=1; }   */
                                        $a+=1;
                                    }
                                }
                                else
                                {   $cont_pat+=1; }
                              ?>
                              <td><center><?php echo $num; ?></center></td>
                              <td><?php echo utf8_encode($rows['Descripcion_sub']); ?></td>
                              <td><center><input type="checkbox" name="checkeds[]" value="<?php echo $rows['idSubactividad']; ?>"></center></td>
                              <td><textarea cols="45" rows="1" id="<?php echo $rows['idSubactividad']; ?>i"></textarea></td>
                          </tr>
                          <?php
                          $num++;
                      }
                }?>
          </table>
        </form>
        <table border="2" width="100%" class="table table-hover table-condensed table-bordered table-responsive table-striped">
             <tr>
                <th colspan="8" style="background-color:#2AA2E3;color:black;"><H4><center>COMBUSTIBLES</center></H4></th>
            </tr>
            <tr>
                <th colspan="8" style="background-color:#FFFBC5"><H4><center></center></H4></th>
            </tr>
            <tr>
                <th colspan="4"><center>FLUIDOS</center></th>
                <th colspan="5"><center>FILTROS</center></th>
            </tr>
            <tr>
                <th colspan="2" style="width: 10%"><center>COMPONENTE</center></th>
                <th style="width: 10%;"><center>CANTIDAD</center></th>
                <th style="width: 29%;"><center>MARCA</center></th>
                <th style="width: 65%;"><center>TIPO</center></th>
                <th><center>NUMERO DE PARTE</center></th>
                <th><center>CANTIDAD</center></th>
                <th><center>OBSERVACIONES</center></th>
            </tr>
            <tr>
                <th colspan="2">ACEITE TRANSMISION</th>
                <td><input type="number" class="form-control input-sm"></td>
                <td><input type="text" class="form-control input-sm"></td>
                <th>ACEITE MOTOR</th>
                <td><input type="text" class="form-control input-sm"></td>
                <td><input type="number" class="form-control input-sm"></td>
                <td><textarea cols="40" rows="1"></textarea></td>
            </tr>
            <tr>
                <th colspan="2">ACEITE MOTOR</th>
                <td><input type="number" class="form-control input-sm"></td>
                <td><input type="text" class="form-control input-sm"></td>
                <th>COMBUSTIBLE</th>
                <td><input type="text" class="form-control input-sm"></td>
                <td><input type="number" class="form-control input-sm"></td>
                <td><textarea cols="40" rows="1"></textarea></td>
            </tr>
            <tr>
                <th colspan="2">ACEITE DIFERENCIAL</th>
                <td><input type="number" class="form-control input-sm"></td>
                <td><input type="text" class="form-control input-sm"></td>
                <th>TRAMPA AGUA</th>
                <td><input type="text" class="form-control input-sm"></td>
                <td><input type="number" class="form-control input-sm"></td>
                <td><textarea cols="40" rows="1"></textarea></td>
            </tr>
            <tr>
                <th colspan="2">ACEITE HIDRAULICO</th>
                <td><input type="number" class="form-control input-sm"></td>
                <td><input type="text" class="form-control input-sm"></td>
                <th>ACEITE HIDRAULICO</th>
                <td><input type="text" class="form-control input-sm"></td>
                <td><input type="number" class="form-control input-sm"></td>
                <td><textarea cols="40" rows="1"></textarea></td>
            </tr>
            <tr>
                <th colspan="2">REFRIGERANTE</th>
                <td><input type="number" class="form-control input-sm"></td>
                <td><input type="text" class="form-control input-sm"></td>
                <th>AIRE PURIFICADOR PRIM</th>
                <td><input type="text" class="form-control input-sm"></td>
                <td><input type="number" class="form-control input-sm"></td>
                <td><textarea cols="40" rows="1"></textarea></td>
            </tr>
            <tr>
                <th colspan="2">DESENGRASANTE</th>
                <td><input type="number" class="form-control input-sm"></td>
                <td><input type="text" class="form-control input-sm"></td>
                <th>AIRE PURIFICADOR SEC</th>
                <td><input type="text" class="form-control input-sm"></td>
                <td><input type="number" class="form-control input-sm"></td>
                <td><textarea cols="40" rows="1"></textarea></td>
            </tr>
            <tr>
                <th colspan="2">GRASA</th>
                <td><input type="number" class="form-control input-sm"></td>
                <td><input type="text" class="form-control input-sm"></td>
                <th>A/A</th>
                <td><input type="text" class="form-control input-sm"></td>
                <td><input type="number" class="form-control input-sm"></td>
                <td><textarea cols="40" rows="1"></textarea></td>
            </tr>
            <tr>
                <th colspan="2"><input type="text" size="30" class="form-control" placeholder="OTRO..."></th>
                <td><input type="number" class="form-control input-sm"></td>
                <td><input type="text" class="form-control input-sm"></td>
                <th><input type="text" class="form-control input-sm"></th>
                <td><input type="text" class="form-control input-sm"></td>
                <td><input type="number" class="form-control input-sm"></td>
                <td><textarea cols="40" rows="1"></textarea></td>
            </tr>
            <tr>
                <th colspan="8" style="background-color:#FFFBC5"><H4><center></center></H4></th>
            </tr>
             <tr>
                <th colspan="8" style="background-color:#2AA2E3;color:black;"><H4><center>AUTORIZACIONES</center></H4></th>
            </tr>
            <tr>
                <th colspan="8" style="background-color:#FFFBC5"><H4><center></center></H4></th>
            </tr>
            <tr>
                <th colspan="3"><center>REALIZADO POR:</center></th>
                <th colspan="3"><center>VERIFICADO POR:</center></th>
                <th colspan="3"><center>APROBADO POR:</center></th>
            </tr>

            <tr>
            </tr>
            <tr>
                <type aling=center>
                <th colspan="3"><input type="password" class="form-control" id="realizado_por"></th>
                <th colspan="3"><input type="password" class="form-control" id="verificado_por"></th>
                <th colspan="3"><input type="password" class="form-control" id="aprobado_por"></th>
            </tr> 
            
            <tr>
                <th colspan="8" style="background-color:#2AA2E3;color:black;"><H4><center>OBSERVACIONES GENERALES</center><H4></th>
            </tr>
             <tr>
                <td colspan="8">
                    <textarea rows="10" cols="150"></textarea>
                </td>
            </tr>
        </table>
        <button class="btn btn-success navbar-right" style="margin-right: 3px;" onclick="Pasos()">Finalizar</button>
        <br><br><br>
    </div>
</body>
</html>