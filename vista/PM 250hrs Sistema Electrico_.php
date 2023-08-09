<?php
session_start();
require_once '../modelo/conexion.php';
//error_reporting(0);
if ($_SESSION["logueado"] == TRUE && ($_SESSION["permisoIngresar"] != 'AUXILIAR_PATIO' || $_SESSION['permisoIngresar'] != 'PATIO_CARGADORES')){
    $id_mantto = $_GET['id_mantto'];
    $sql = "SELECT Mantenimiento_equipos.tipo_mtto, Mantenimiento_equipos.realizado_por, Mantenimiento_equipos.aprobado_por, 
            Mantenimiento_equipos.verificado_por, Mantenimiento_equipos.id_usuario, Mantenimiento_equipos.idxid, Mantenimiento_equipos.idEquipo, 
            Mantenimiento_equipos.horo_mtto_final, Mantenimiento_equipos.total_horas, Mantenimiento_equipos.fecha_inicial_mtto, 
            Mantenimiento_equipos.fecha_final_mtto, Mantenimiento_equipos.observaciones, Equipos.Descripcion, Equipos.Identificacion, 
            Proveedores.RazonSocial, EquiposGrupos.Descripcion AS Categoria, Usuarios.NombreUsuarioLargo, Mantenimiento_equipos.horo_mtto_inicial
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
        $elaborado_por = $mantto['realizado_por'];
        $verificado_por = $mantto['verificado_por'];
        $aprobado_por = $mantto['aprobado_por'];
        $observaciones = utf8_encode($mantto['observaciones']);
        $nombre_operador = $mantto['NombreUsuarioLargo'];
        $tipo_mantto = $mantto['tipo_mtto'];
        //PARA REDIRECCIONAR
        $idEquipo = $mantto['idEquipo'];
        $Identificacion = $mantto['Identificacion'];
        $modelo = $mantto['Descripcion'];
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
}?>
<!DOCTYPE html>
<html>
    <head>
        <title>SISTEMA ELECTRICO</title>
        <meta name="viewport" content="width=auto, initial-scale=0.8">
        <?php include 'libreria.php'; ?>
        <script type="text/javascript" src="../controlador/mantenimiento.js"></script>
    </head>
<body> 
    <?php
    include 'Header.php';
    ?>
    <div class="container">
        <button class="btn btn-danger navbar-right" onclick="redireccionar()"><span class="glyphicon glyphicon-arrow-left"></span></button>

            <input type="hidden" id="id_mantto" value="<?php echo $id_mantto; ?>">
            <center>
                <!--<img src="../Imagenes/LOGO LATAM.jpg" border="0" width="45
                    echo $sql_actividad;0" height="140"></center>-->
                <!--   class="table table-hover table-condensed table-bordered table-responsive table-striped"  -->
                <h3 style="color: #7E5D56;">
                    <center>
                        <?php 
                        if ($tipo_mantto == 4){
                            echo "PLAN DE MANTENIMIENTO PREVENTIVO  250HRS - SISTEMA ELECTRICO";
                        }elseif($tipo_mantto == 3){
                            echo "PLAN DE MANTENIMIENTO PREVENTIVO  250HRS - LAVADO MAQUINARIA";
                        }elseif($tipo_mantto == 2){
                            echo "PLAN DE MANTENIMIENTO PREVENTIVO  250HRS";
                        }elseif($tipo_mantto == 1){
                            echo "PLAN DE MANTENIMIENTO PREVENTIVO";
                        }
                        ?>
                    </center>
                </h3>
            <table border="1" width="" class=" table-hover table-bordered table-responsive">
                <tr>
                    <td><center>FRECUENCIA</center></td>
                    <td colspan="2"><center>TIEMPO DE EJECUCIÓN</center></td>
                    <td colspan="2"><center>HORA DE EJECUCIÓN</center></td>
                    <td><center> FLOTA Y EQUIPO</center></td>
                    <!--<td><center> EQUIPO</center></td>-->
                    <td><center> HOROMETRO</center></td>
                    <!--<td><center> TIPO</center></td>-->
                </tr>                
                <tr>
                    <td rowspan="2"><center>250 (HRS)</center></td>
                    <td><center>H. INICIAL</center></td>
                    <td><center>H. FINAL</center></td>
                    <td rowspan="2" colspan="2"><center><?php echo date_format($fecha_inicial_mtto, 'Y/m/d H:i'); ?></center></td>
                    <td rowspan="2"><center><?php echo $flota.' '.$equipo; ?></center></td>
                    <!--<td rowspan="2"><center><?php echo $equipo; ?></center></td>-->
                    <td rowspan="2"><center><?php echo number_format($horo_resultado,1,',','.'); ?></center></td>
                    <!--<td rowspan="2"><center>Variable Tipo Eventual</center></td>-->
                 </tr>
                 <tr>
                     <td><center><?php echo $horo_mtto_inicial; ?></center></td>
                     <td><center><?php echo $horo_mtto_final; ?></center></td>
                 </tr>
            </table>
            <H3 style="color: #7E5D56;"><center>DESCRIPCIÓN DEL TRABAJO</center></H3>
            <form name="form3" id="form3">
                <label>REALIZADO POR:</label>
                <?php 
                if ($tipo_mantto != 1){
                    if($elaborado_por!= null){ ?>
                        <input type="text" class="form-control" readonly id="textareai" value="">
                    <?php
                    }else{?>
                        <textarea cols="45" class="form-control" rows="1" id="textareai"></textarea>
                    <?php 
                    }
                }else{
                    if($aprobado_por!= null){?>
                        <input type="text" class="form-control" readonly id="textareai" value="">
                    <?php
                    }else{?>
                        <textarea cols="45" class="form-control" rows="1" id="textareai"></textarea>
                    <?php 
                    } 
                }?>
                <br>
                <table border="1" width="" class="table table-hover table-bordered table-responsive">
                    <tr>
                        <th><center>SECCIÓN</center></th>
                        <th><center>N°</center></th>
                        <th><center>ACTIVIDADES</center></th>
                        <input type="hidden" id="var">
                        <th ><center><p onclick="Seleccionar_todo()">APLICADO</p></center></th>
                        <!--<th><center><p title="Debe registrar las personas que hicieron dicha actividad">REALIZADO POR</p></center></th>-->
                    </tr>

                    <?php
                    if($tipo_mantto == 4){
                        $sql_actividad = "SELECT Actividades.idActividad, Subactividades_cargadores.idSubactividad, Subactividades_cargadores.Descripcion as Descripcion_sub,
                        Actividades.Descripcion FROM Actividades
                        INNER JOIN Subactividades_cargadores ON Actividades.idActividad=Subactividades_cargadores.idActividad
                        WHERE idTipoActividad='00000000-0000-0000-0000-000000000005' order by Actividades.Descripcion";
                    }elseif($tipo_mantto == 3){
                        $sql_actividad = "SELECT Actividades.idActividad, Subactividades_cargadores.idSubactividad, Subactividades_cargadores.Descripcion as Descripcion_sub,
                        Actividades.Descripcion FROM Actividades
                        INNER JOIN Subactividades_cargadores ON Actividades.idActividad=Subactividades_cargadores.idActividad
                        WHERE idTipoActividad='00000000-0000-0000-0000-000000000004' order by Actividades.Descripcion";
                    }elseif($tipo_mantto == 2){
                        $sql_actividad = "SELECT Actividades.idActividad, Subactividades_cargadores.idSubactividad, Subactividades_cargadores.Descripcion as Descripcion_sub,
                        Actividades.Descripcion FROM Actividades
                        INNER JOIN Subactividades_cargadores ON Actividades.idActividad=Subactividades_cargadores.idActividad
                        WHERE idTipoActividad='00000000-0000-0000-0000-000000000003' order by Actividades.Descripcion";
                    }elseif($tipo_mantto == 1){
                        $sql_actividad = "SELECT Actividades.idActividad, Subactividades_cargadores.idSubactividad, Subactividades_cargadores.Descripcion as Descripcion_sub,
                        Actividades.Descripcion FROM Actividades
                        INNER JOIN Subactividades_cargadores ON Actividades.idActividad=Subactividades_cargadores.idActividad
                        WHERE idTipoActividad='00000000-0000-0000-0000-000000000002' order by Actividades.Descripcion";
                    }
                    //echo $sql_actividad;
                    $params = array();
                    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                    $resultado=sqlsrv_query($conn,utf8_decode($sql_actividad),$params,$options);
                    $rowss=sqlsrv_num_rows($resultado);
                    if ($rowss > 0) 
                    {   $num = 1;
                        $paso = 0;
                        $tem_pat="a";
                        $a=0;
                        $array_patio= array();  //Para sabar cuantos registros hay por patio
                        $cont_pat=0;
                        while($rows = sqlsrv_fetch_array($resultado)){
                            $sql1 = "SELECT * FROM Mtto_plantilla_actividades WHERE id_mtto_equipo='$id_mantto'";
                            $params1 = array();
                            $options1 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                            $resultado1=sqlsrv_query($conn,utf8_decode($sql1),$params1,$options1);
                            $rowss1=sqlsrv_num_rows($resultado1);
                            if ($rowss1 > 0) 
                            {   while($d = sqlsrv_fetch_array($resultado1)){
                                    if($d['id_actividad'] == $rows['idSubactividad']){
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
                                }
                            }else{
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
                        }
                        $array_patio[$a]=$cont_pat;
                        $resultado=sqlsrv_query($conn,utf8_decode($sql_actividad));
                        $tem_pat="a";
                        $a=0;
                        while($rows = sqlsrv_fetch_array($resultado)){
                            $sql1 = "SELECT * FROM Mtto_plantilla_actividades WHERE id_mtto_equipo='$id_mantto'";
                            $params1 = array();
                            $options1 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                            $resultado1=sqlsrv_query($conn,utf8_decode($sql1),$params1,$options1);
                            $rowss1=sqlsrv_num_rows($resultado1);
                            if ($rowss1 > 0) 
                            {   while($d = sqlsrv_fetch_array($resultado1)){
                                    if($d['id_actividad'] == $rows['idSubactividad']){
                                        ?>
                                        <tr>
                                            <?php 
                                            if ($tem_pat<>$rows['Descripcion'])
                                            {   if($tem_pat=="a")
                                                {   ?><td style="vertical-align: middle;" rowspan="<?php echo $array_patio[$a]; ?>"><center><?php echo '11'.utf8_encode($rows['Descripcion']); ?></center></td>
                                                    <?php
                                                    $tem_pat=$rows['Descripcion'];
                                                    $a+=1;
                                                /*  if ($patio=='FATIBAR')
                                                    {   $cont_pat+=1;}   */
                                                    //$cont_pat+=1;
                                                }
                                                else
                                                {   ?><td style="vertical-align: middle;" rowspan="<?php echo $array_patio[$a]; ?>"><center><?php echo utf8_encode($rows['Descripcion']); ?></center></td>
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
                                            <td><center>
                                                <?php 
                                                if($tipo_mantto != 1){
                                                    if($elaborado_por== null){?>
                                                        <input type="checkbox" name="checkeds[]" value="<?php echo $rows['idSubactividad']; ?>">
                                                    <?php
                                                    }else{ ?>
                                                        <script type="text/javascript">
                                                            $('#textareai').val('<?php echo $d['personal']; ?>');
                                                        </script>
                                                        <span class="<?php if($d['id_actividad'] == $rows['idSubactividad']){ echo 'glyphicon glyphicon-ok'; }else{ echo 'glyphicon glyphicon-delete'; } ?>"></span>
                                                    <?php 
                                                    } 
                                                }else{
                                                    if($aprobado_por== null){ ?>
                                                        <input type="checkbox" name="checkeds[]" value="<?php echo $rows['idSubactividad']; ?>">
                                                    <?php
                                                    }else{ ?>
                                                        <script type="text/javascript">
                                                            //$('#textareai').val('asd');
                                                            $('#textareai').val('<?php echo $d['personal']; ?>');
                                                        </script>
                                                        <span class="<?php if($d['id_actividad'] == $rows['idSubactividad']){ echo 'glyphicon glyphicon-ok'; }else{ echo 'glyphicon glyphicon-delete'; } ?>"></span>
                                                    <?php 
                                                    } 
                                                }
                                                ?>
                                            </center></td>
                                            <!--<td>
                                                <?php 
                                                if ($tipo_mantto != 1){
                                                    if($elaborado_por!= null){ 
                                                    ?>
                                                        <input type="text" class="form-control" readonly id="<?php echo $rows['idSubactividad']; ?>i" value="<?php if($d['id_actividad'] == $rows['idSubactividad']){ echo $d['personal']; } ?>">
                                                    <?php
                                                    }else{  ?>
                                                        <textarea cols="45" class="form-control" rows="1" id="<?php echo $rows['idSubactividad']; ?>i"></textarea>
                                                    <?php 
                                                    } 
                                                }else{
                                                    if($aprobado_por!= null){ 
                                                    ?>
                                                        <input type="text" class="form-control" readonly id="<?php echo $rows['idSubactividad']; ?>i" value="<?php if($d['id_actividad'] == $rows['idSubactividad']){ echo $d['personal']; } ?>">
                                                    <?php
                                                    }else{  ?>
                                                        <textarea cols="45" class="form-control" rows="1" id="<?php echo $rows['idSubactividad']; ?>i"></textarea>
                                                    <?php 
                                                    } 
                                                }
                                                ?>
                                            </td>-->
                                        </tr>
                                        <?php
                                        $num++;
                                    }
                                }
                            }else{
                                ?>
                                <tr>
                                    <?php 
                                    if ($tem_pat<>$rows['Descripcion'])
                                    {   if($tem_pat=="a")
                                        {   ?><td style="vertical-align: middle;" rowspan="<?php echo $array_patio[$a]; ?>"><center><?php echo utf8_encode($rows['Descripcion']); ?></center></td>
                                            <?php
                                            $tem_pat=$rows['Descripcion'];
                                            $a+=1;
                                        /*  if ($patio=='FATIBAR')
                                            {   $cont_pat+=1;}   */
                                            //$cont_pat+=1;
                                        }
                                        else
                                        {   ?><td style="vertical-align: middle;" rowspan="<?php echo $array_patio[$a]; ?>"><center><?php echo utf8_encode($rows['Descripcion']); ?></center></td>
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
                                    <!--<td><textarea cols="45" class="form-control" rows="1" id="<?php echo $rows['idSubactividad']; ?>i"></textarea></td>-->
                                </tr>
                            <?php
                            $num++;
                            }
                        }
                    }?>
                </table>
                <?php if ($tipo_mantto == 2){ ?>
                <table border="2" width="100%" class="table table-hover table-condensed table-bordered table-responsive table-striped">
                    <tr>
                        <th colspan="8" style="background-color:#2AA2E3;color:black;"><H4><center>COMBUSTIBLES</center></H4></th>
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
                        <td><textarea cols="40" class="form-control" rows="1"></textarea></td>
                    </tr>
                    <tr>
                        <th colspan="2">ACEITE MOTOR</th>
                        <td><input type="number" class="form-control input-sm"></td>
                        <td><input type="text" class="form-control input-sm"></td>
                        <th>COMBUSTIBLE</th>
                        <td><input type="text" class="form-control input-sm"></td>
                        <td><input type="number" class="form-control input-sm"></td>
                        <td><textarea cols="40" class="form-control" rows="1"></textarea></td>
                    </tr>
                    <tr>
                        <th colspan="2">ACEITE DIFERENCIAL</th>
                        <td><input type="number" class="form-control input-sm"></td>
                        <td><input type="text" class="form-control input-sm"></td>
                        <th>TRAMPA AGUA</th>
                        <td><input type="text" class="form-control input-sm"></td>
                        <td><input type="number" class="form-control input-sm"></td>
                        <td><textarea cols="40" class="form-control" rows="1"></textarea></td>
                    </tr>
                    <tr>
                        <th colspan="2">ACEITE HIDRAULICO</th>
                        <td><input type="number" class="form-control input-sm"></td>
                        <td><input type="text" class="form-control input-sm"></td>
                        <th>ACEITE HIDRAULICO</th>
                        <td><input type="text" class="form-control input-sm"></td>
                        <td><input type="number" class="form-control input-sm"></td>
                        <td><textarea cols="40" class="form-control" rows="1"></textarea></td>
                    </tr>
                    <tr>
                        <th colspan="2">REFRIGERANTE</th>
                        <td><input type="number" class="form-control input-sm"></td>
                        <td><input type="text" class="form-control input-sm"></td>
                        <th>AIRE PURIFICADOR PRIM</th>
                        <td><input type="text" class="form-control input-sm"></td>
                        <td><input type="number" class="form-control input-sm"></td>
                        <td><textarea cols="40" class="form-control" rows="1"></textarea></td>
                    </tr>
                    <tr>
                        <th colspan="2">DESENGRASANTE</th>
                        <td><input type="number" class="form-control input-sm"></td>
                        <td><input type="text" class="form-control input-sm"></td>
                        <th>AIRE PURIFICADOR SEC</th>
                        <td><input type="text" class="form-control input-sm"></td>
                        <td><input type="number" class="form-control input-sm"></td>
                        <td><textarea cols="40" class="form-control" rows="1"></textarea></td>
                    </tr>
                    <tr>
                        <th colspan="2">GRASA</th>
                        <td><input type="number" class="form-control input-sm"></td>
                        <td><input type="text" class="form-control input-sm"></td>
                        <th>A/A</th>
                        <td><input type="text" class="form-control input-sm"></td>
                        <td><input type="number" class="form-control input-sm"></td>
                        <td><textarea cols="40" class="form-control" rows="1"></textarea></td>
                    </tr>
                    <tr>
                        <th colspan="2"><input type="text" size="30" class="form-control" placeholder="OTRO..."></th>
                        <td><input type="number" class="form-control input-sm"></td>
                        <td><input type="text" class="form-control input-sm"></td>
                        <th><input type="text" class="form-control input-sm"></th>
                        <td><input type="text" class="form-control input-sm"></td>
                        <td><input type="number" class="form-control input-sm"></td>
                        <td><textarea cols="40" class="form-control" rows="1"></textarea></td>
                    </tr>
                </table>
            <?php } ?>
                <H3 style="color: #7E5D56;"><center>AUTORIZACIONES</center></H3>
                <table border="1" width="100%" class="table table-hover table-bordered table-responsive">
                    <tr>
                        <?php if($tipo_mantto != 1){ ?><th colspan="2"><center>MECANICO:</center></th><?php }?>
                        <?php if($tipo_mantto != 1){ ?><th colspan="1"><center>JEFE MANTENIMIENTO:</center></th><?php }?>
                        <th colspan="2"><center>OPERADOR:</center></th>
                    </tr>
                    <tr>
                        <input type="hidden" id="id_usuario" value="<?php echo $usuario; ?>">
                        <input type="hidden" id="Password_usu" value="<?PHP echo $Password;?>">
                        <?php if($tipo_mantto != 1){ ?>
                        <th colspan="2" style="height: 25px">
                            <?php if($elaborado_por == null){ ?>
                                <input type="password" id="realizado_por" class="form-control" <?php if ($_SESSION['permisoIngresar'] != 'MECANICO_CARGADORES'){echo "readonly";}else{
                                    echo 'placeholder=" Digite su contraseña."';
                                } ?>>
                            <?php
                            }else{
                                echo '<center>'.$elaborado_por.'</center>';
                            } ?>
                        </th>
                        <?php }?>
                        <?php if($tipo_mantto != 1){ ?>
                        <th colspan="1" style="height: 25px">
                            <?php if($verificado_por == null){ ?>
                                <input type="password" id="verificado_por" class="form-control" <?php if ($_SESSION['permisoIngresar'] != 'MTTO_CARGADORES'){echo "readonly";}else{
                                    echo 'placeholder=" Digite su contraseña."';
                                } ?>>
                            <?php
                            }else{
                                echo '<center>'.$verificado_por.'</center>';
                            } ?>
                        </th>
                        <?php }?>
                        <th colspan="2" style="height: 25px">
                            <?php 
                            if ($aprobado_por == null){
                            ?>
                                <input type="password" id="aprobado_por" class="form-control" <?php if ($_SESSION['permisoIngresar'] != 'OPERADOR_CARGADOR'){echo "readonly";}else{
                                    echo 'placeholder=" Digite su contraseña."';
                                } ?>>
                            <?php 
                            }else{
                                echo '<center>'.$aprobado_por.'</center>';
                            } ?>    
                        </th>
                    </tr> 
                    <tr>
                        <th colspan="5" style="color: #7E5D56;"><H3><center>OBSERVACIONES GENERALES</center></H3></th>
                    </tr>
                     <tr>
                        <td colspan="5">
                            <input type="hidden" id="valor_observacion" <?php if($observaciones != null){ echo 'value="'.$observaciones.'"'; } ?>>
                            <center>
                                <?php 
                                if($tipo_mantto == 1){
                                    ?><textarea rows="8" class="form-control" cols="145" id="observaciones"><?php echo $observaciones.'.'; ?></textarea><?php
                                }elseif ($observaciones != null && $elaborado_por != null && $verificado_por != null && $aprobado_por != null){
                                ?>
                                <textarea rows="8" class="form-control" cols="145" id="observaciones"><?php echo $observaciones.'.'; ?></textarea>

                                <?php }elseif($observaciones != null && $elaborado_por != null && ($verificado_por == null || $aprobado_por == null)){
                                    ?>
                                <textarea rows="8" class="form-control" cols="145" id="observaciones"><?php echo $observaciones.'.'; ?>

<?php echo '* '.$Nombre.' :';  ?></textarea>
                                <?php
                                }else{
                                    ?>
                                    <textarea rows="8" class="form-control" cols="145" id="observaciones">
<?php echo '* '.$Nombre.' :';  ?> </textarea>
                                <?php
                                } ?>
                            </center>
                        </td>
                    </tr>
                </table>
            </form>
            <?php 
            if ($_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES'){
                if ($elaborado_por == null){
                ?>
                    <button class="btn btn-success navbar-right" style="margin-right: 3px;" onclick="Pasos('mecanico')">Finalizar</button>
                <?php
                }else{
                ?>
                    <!--<button class="btn btn-success navbar-right" style="margin-right: 3px;" onclick="Pasos('comentario')">Enviar Comentarios</button>-->
                <?php 
                }
            }elseif($_SESSION['permisoIngresar'] == 'MTTO_CARGADORES'){
                if ($verificado_por == null){
                ?>
                    <button class="btn btn-success navbar-right" style="margin-right: 3px;" onclick="Pasos('jefe_mantto')">Finalizar</button>
                <?php
                }else{
                ?>
                    <!--<button class="btn btn-success navbar-right" style="margin-right: 3px;" onclick="Pasos('comentario')">Enviar Comentarios</button>-->
                <?php 
                }
            }elseif($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){
                if ($aprobado_por == null){
                    if ($tipo_mantto != 1){
                    ?>
                        <button class="btn btn-success navbar-right" style="margin-right: 3px;" onclick="Pasos('operador')">Finalizar</button>
                    <?php
                    }else{
                    ?>
                        <button class="btn btn-success navbar-right" style="margin-right: 3px;" onclick="Pasos('Mtto_rutina')">Finalizar</button>
                    <?php
                    }
                }else{
                ?>
                    <!--<button class="btn btn-success navbar-right" style="margin-right: 3px;" onclick="Pasos('comentario')">Enviar Comentarios</button>-->
                <?php 
                }
            }
            ?>
            <br><br><br>
    </div>
    <script type="text/javascript">
        /*$(document).ready(function(){
          function reorient(e) {
            var portrait = (window.orientation % 90 == 0);
            $("body > div").css("-webkit-transform", !portrait ? "rotate(-90deg)" : "");
          }
          window.onorientationchange = reorient;
          window.setTimeout(reorient, 0);
        });*/
        function redireccionar(){
            self.location = "mantenimiento.php?id_maquinaria=<?php echo $idEquipo; ?>&modelo=<?php echo $modelo; ?>&serial=<?php echo $Identificacion; ?>";
        }
    </script>
</body>
</html>