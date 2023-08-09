<?php
session_start();
require_once '../modelo/conexion.php';
//error_reporting(0);
if ($_SESSION["logueado"] == TRUE && ($_SESSION["permisoIngresar"] != 'AUXILIAR_PATIO' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES')){
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
        $horo_mtto_inicial = $mantto['horo_mtto_inicial'];
        $horo_mtto_final = $mantto['horo_mtto_final'];
        $fecha_inicial_mtto = $mantto['fecha_inicial_mtto'];
        $flota = $mantto['Categoria'];
        $equipo = $mantto['Descripcion']." - ".$mantto['Identificacion'];
        $horo_resultado = $horo_mtto_final-$horo_mtto_inicial;
        $elaborado_por = $mantto['id_usuario'];
        $observaciones = $mantto['observaciones'];
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
}elseif($_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO'){
    //header("Location: inicio_patio.php");
    ?>
    <script type="text/javascript">
        self.location='inicio_patio.php';
        alert('No tienes permiso para acceder a este ambiente');
    </script>
    <?php
}elseif($_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){
    //header("Location: Admin.php");
    ?>
    <script type="text/javascript">
        self.location='Admin.php';
        alert('No tienes permiso para acceder a este ambiente');
    </script>
    <?php
}else{
    //header("Location: ../index.php");
    ?>
    <script type="text/javascript">
        self.location='../index.php';
        alert('Inicia sesión primero');
    </script>
    <?php
    die();
}
 ?>
 <!DOCTYPE html>
<html>
    <head>
        <title>PLANTILLA</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php include 'libreria.php'; ?>
        <script type="text/javascript" src="../controlador/mantenimiento.js"></script>
    </head>
<body> 
    <?php
    include 'Header.php';
    ?>
    <div class="container">
        <br>
        <h2><center>MANTENIMIENTO EVENTUAL - DIARIO</center></h2>
        <br>
        <input type="hidden" id="id_mantto" value="<?php echo $id_mantto; ?>">
        <input type="hidden" id="seleccionados">
        <input type="hidden" id="personal">
        <form name="form3" id="form3">
            <table border="3" width="100%" class="table table-hover table-condensed table-bordered table-responsive table-striped" >
                 <tr>
                    <th colspan="8" style="background-color:#2AA2E3; color:white;"><h3><center>PLAN DE MANTENIMIENTO PREVENTIVO</center></h3></th>
                </tr>
                <tr>
                    <td style="width: 6%"><center><p>FRECUENCIA</p></center></td>
                    <td style="width: 10%" colspan="2"><center>TIEMPO DE EJECUCIÓN</center></td>
                    <td style="width: 18%"><center> FECHA Y HORA DE EJECUCIÓN</center></td>
                    <td style="width: 10%"><center> FLOTA</center></td>
                    <td style="width: 10%"><center> EQUIPO</center></td>
                    <td style="width: 8%"><center> HOROMETRO</center></td>
                    <td><center> TIPO</center></td>   
                </tr>
                <tr>
                    <td rowspan="2"><center>DIARIA</center></td>
                    <td style="width: 10%"><center>HORO. INICIAL</center></td>
                    <td style="width: 10%"><center>HORO. FINAL</center></td>
                    <td rowspan="2"><center><?php echo date_format($fecha_inicial_mtto, 'Y/m/d H:i:s') ?></center></td>
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
                    <th colspan="8" style="background-color:#2AA2E3;color:black;"><H3><center>DESCRIPCIÓN DEL TRABAJO</center></H3></th>
                </tr>
                <tr>
                    <th><center>SECCIÓN</center></th>
                    <th><center>N°</center></th>
                    <th colspan="4"><center>ACTIVIDADES</center></th>
                    <th><center>EJECUTADO</center></th>
                    <th><center><p title="Debe registrar las personas que hicieron dicha actividad">REALIZADO POR:</p></center></th>
                </tr>
                <!-- EMPIEZA EL WHILE -->
                <?php
                $sql_actividad = "SELECT Actividades.idActividad, Subactividades_cargadores.idSubactividad, Subactividades_cargadores.Descripcion as Descripcion_sub,
                Actividades.Descripcion FROM Actividades
                INNER JOIN Subactividades_cargadores ON Actividades.idActividad=Subactividades_cargadores.idActividad
                WHERE idTipoActividad='00000000-0000-0000-0000-000000000002' order by Subactividades_cargadores.Descripcion";
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
                        {   while($d = sqlsrv_fetch_array($resultado1)){  
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
                                    <td colspan="4"><?php echo utf8_encode($rows['Descripcion_sub']); ?></td>
                                    <td><center><input type="checkbox" name="checkeds[]" <?php if($d['id_actividad'] == $rows['idSubactividad']){ echo "checked"; } ?> value="<?php echo $rows['idSubactividad']; ?>"></center></td>
                                    <td><textarea cols="45" rows="1" id="<?php echo $rows['idSubactividad']; ?>i" <?php if($d['id_actividad'] == $rows['idSubactividad']){ echo $d['personal']; } ?>></textarea></td>
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
                                <td colspan="4"><?php echo utf8_encode($rows['Descripcion_sub']); ?></td>
                                <td><center><input type="checkbox" name="checkeds[]" value="<?php echo $rows['idSubactividad']; ?>"></center></td>
                                <td><textarea cols="45" rows="1" id="<?php echo $rows['idSubactividad']; ?>i"></textarea></td>
                            </tr>
                            <?php
                            $num++;
                        }
                    }
                }?>
                <tr>
                        <input type="hidden" id="Password_usu" value="<?php echo $Password;?>">
                        <input type="hidden" id="id_usuario" value="<?php echo $usuario; ?>">
                        <input type="hidden" id="realizado_por" value="<?php echo $Password;?>">
                    <th colspan="8" style="background-color:#2AA2E3;color:black;"><H4><center>OBSERVACIONES GENERALES</center></H4></th>
                </tr>
                 <tr>
                    <td colspan="8">
                        <center><textarea rows="10" cols="150" id="observaciones" <?php if($observaciones != null){ echo $observaciones; } ?>></textarea></center>
                    </td>
                </tr>
            </table>
        </form>
        <button class="btn btn-success navbar-right" style="margin-right: 3px;" onclick="Pasos()">Finalizar</button>
        <br><br><br>
    </div>
</body>
</html>