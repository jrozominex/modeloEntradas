<?php
session_start();
require_once '../modelo/conexion.php';
//error_reporting(0);
if ($_SESSION["logueado"] == TRUE && $_SESSION["usuario"]) {
    $id_mantto = $_GET['id_mantto'];
    $sql = "SELECT Mantenimiento_equipos.id_usuario, Mantenimiento_equipos.idxid, Mantenimiento_equipos.idEquipo, Mantenimiento_equipos.horo_mtto_inicial,
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
        $elaborado_por = $mantto['id_usuario'];
        $horo_mtto_inicial = $mantto['horo_mtto_inicial'];
        $horo_mtto_final = $mantto['horo_mtto_final'];
        $fecha_inicial_mtto = $mantto['fecha_inicial_mtto'];
        $flota = $mantto['Categoria'];
        $equipo = $mantto['Descripcion']." - ".$mantto['Identificacion'];
        $horo_resultado = $horo_mtto_final-$horo_mtto_inicial;
        $observaciones = $mantto['observaciones'];
        $nombre_operador = $mantto['NombreUsuarioLargo'];
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
      $Password = $row['Password'];
    }
}else {
    header("Location: ../index.php");
    die();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>PLANTILLA 3 LAVADO -  WILSON</title>
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
            <input type="hidden" id="id_mantto" value="<?php echo $id_mantto; ?>">
            <input type="hidden" id="seleccionados">
            <input type="hidden" id="personal">
            <tr>
                <h1> <center><img src="IMAGENES\logo_latam_1.jpg" border="0" width="300" height="70"></center></h1>
            </tr>
                <tr>
                <h1> <center>LATAM COAL MINES S.A.S.</center></h1>
            </tr>
            
            <table border="2" width="100%" class="table table-hover table-condensed table-bordered table-responsive table-striped">
               <tr>
                  <th colspan="8" style="background-color:#2AA2E3; color:white;"><h3><center>PLAN DE MANTENIMIENTO PREVENTIVO  250HRS - LAVADO MAQUINARIA</center></h3></th>
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
                  WHERE idTipoActividad='00000000-0000-0000-0000-000000000004' order by Subactividades_cargadores.Descripcion";
                  $params = array();
                  $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                  $resultado=sqlsrv_query($conn,utf8_decode($sql_actividad),$params,$options);
                  $rowss=sqlsrv_num_rows($resultado);
                  if ($rowss > 0) 
                  {   $num = 1;
                      $paso = 0;
                      while($rows = sqlsrv_fetch_array($resultado)){
                        $sql1 = "SELECT * FROM Mtto_plantilla_actividades WHERE id_mtto_equipo='$id_mantto'";
                        $params1 = array();
                        $options1 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                        $resultado1=sqlsrv_query($conn,utf8_decode($sql1),$params1,$options1);
                        $rowss1=sqlsrv_num_rows($resultado1);
                        if ($rowss1 > 0) 
                        { while($d = sqlsrv_fetch_array($resultado1)){
                            ?>
                            <tr>
                                <?php 
                                if ($paso == 0){
                                    ?>
                                    <td rowspan="<?php echo $rowss; ?>"><center><?php echo utf8_encode($rows['Descripcion']); ?></center></td>
                                    <?php
                                    $paso = 1;
                                }
                                ?>
                                <td><center><?php echo $num; ?></center></td>
                                <td><?php echo utf8_encode($rows['Descripcion_sub']); ?></td>
                                <td><center>
                                  <?php if($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES'){ ?>
                                    <input type="checkbox" name="checkeds[]" value="<?php echo $rows['idSubactividad']; ?>">
                                  <?php
                                  }else{  ?>
                                    <span class="<?php if($d['id_actividad'] == $rows['idSubactividad']){ echo 'glyphicon glyphicon-ok'; }else{ echo 'glyphicon glyphicon-delete'; } ?>"></span>
                                    <?php 
                                  } ?>
                                </center></td>
                                <td>
                                  <?php if($_SESSION['permisoIngresar'] != 'ADMIN_CARGADORES'){ 
                                    ?>
                                    <input type="text" class="form-control" readonly id="<?php echo $rows['idSubactividad']; ?>i" value="<?php if($d['id_actividad'] == $rows['idSubactividad']){ echo $d['personal']; } ?>">
                                    <?php
                                  }else{  ?>
                                    <textarea cols="45" rows="1" id="<?php echo $rows['idSubactividad']; ?>i"></textarea>
                                    <?php 
                                  } ?>
                                </td>
                            </tr>
                            <?php
                            $num++;
                          }
                        }else{
                          ?>
                          <tr>
                              <?php 
                              if ($paso == 0){
                                  ?>
                                  <td rowspan="<?php echo $rowss; ?>"><center><?php echo utf8_encode($rows['Descripcion']); ?></center></td>
                                  <?php
                                  $paso = 1;
                              }
                              ?>
                              <td><center><?php echo $num; ?></center></td>
                              <td><?php echo utf8_encode($rows['Descripcion_sub']); ?></td>
                              <td><center><input type="checkbox" name="checkeds[]" value="<?php echo $rows['idSubactividad']; ?>"></center></td>
                              <td><textarea cols="45" rows="1" id="<?php echo $rows['idSubactividad']; ?>i"></textarea></td>
                          </tr>
                          <?php
                          $num++;
                        }
                      }
                  }?>
                </table>
                <table border="2" width="100%" class="table table-hover table-condensed table-bordered table-responsive table-striped">
                  <tr>
                    <th colspan="5" style="background-color:#2AA2E3;color:black;"><H4><center>AUTORIZACIONES</center></H4></th>
                 </tr>
                 <tr>
                    <th colspan="2"><center>REALIZADO POR:</center></th>
                    <th colspan="2"><center>VERIFICADO POR:</center></th>
                    <th colspan="1"><center>APROBADO POR:</center></th>
                 </tr>

                 <tr>
                 </tr>
                 <tr>
                  <input type="hidden" id="id_usuario" value="<?php echo $usuario; ?>">
                    <input type="hidden" id="Password_usu" value="<?PHP echo $Password;?>">
                    <th colspan="2"><center>
                      <?php 
                      if ($elaborado_por == null || $elaborado_por == $usuario){
                      ?>
                        <input type="password" id="realizado_por" class="form-control">
                      <?php 
                      }else{
                        echo $elaborado_por;
                      } ?>
                    </center></th>
                    <input type="" id="nombre" value="<?php echo $nombre_operador;  ?>">
                    <th colspan="2"><center><input type="password" id="verificado_por" class="form-control" <?php if ($_SESSION['permisoIngresar'] != 'MECANICO_CARGADORES'){echo "readonly";} ?>></center></th>
                    <th colspan="1"><center><input type="password" id="aprobado_por" class="form-control" <?php if ($_SESSION['permisoIngresar'] != 'MTTO_CARGADORES'){echo "readonly";} ?>></center></th>
                 </tr> 
                 
                 <tr>
                     <th colspan="5" style="background-color:#2AA2E3;color:black;"><H4><center>OBSERVACIONES GENERALES</center></H4></th>
                 </tr>
                  <tr>
                  <td colspan="5">
                    <input type="hidden" id="valor_observacion" <?php if($observaciones != null){ echo 'value="'.$observaciones.'"'; } ?>>
                      <center><textarea rows="10" cols="160" id="observaciones"></textarea></center>
                  </td>
              </tr>
          </table>
        </form>
        <button class="btn btn-success navbar-right" style="margin-right: 3px;" onclick="Pasos()">Finalizar</button>
        <br><br><br>
    </div>
    <script type="text/javascript">
      $(document).ready(function () {
        if ($('#valor_observacion').val() != 0){
          document.getElementById('observaciones').innerHTML = $('#nombre').val()+': '+$('#valor_observacion').val();
        }
      });
    </script>
</body>
</html>