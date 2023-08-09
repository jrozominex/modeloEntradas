<?php
session_start();
require_once '../modelo/conexion.php';
error_reporting(0);
if ($_SESSION["logueado"] == TRUE && ($_SESSION["permisoIngresar"] != 'AUXILIAR_PATIO' || $_SESSION['permisoIngresar'] != 'PATIO_CARGADORES')){
    $usuario = $_SESSION['usuario'];
    $Fecha = date('Y-m-d');
    $fecha_hora = date('d-M-y h:i');
    $Fecha_actual = date('Y-m-d',strtotime($Fecha . ' - 15 days'));
    ini_set('date.timezone', 'America/Bogota');
    $hora = date("g:i A");
    $sql_usuario = "SELECT * FROM Usuarios WHERE idUsuario='$usuario'";
    $result = sqlsrv_query($conn,$sql_usuario);
    while ($row = sqlsrv_fetch_array($result)){
      $Nombre = $row['NombreUsuarioLargo'];
      $operador = $row['NombreUsuarioLargo'];
      $Password = $row['Password'];
    }
    if(isset($_GET['patio'])){
        $id_patio = $_GET['patio'];
        $sql = "SELECT * FROM Destino WHERE idDestino='$id_patio'";
        $res = sqlsrv_query($conn,$sql);
        while($sol = sqlsrv_fetch_array($res)){
            $patio = $sol['Descripcion'];
        }
        $maquinaria = $_GET['maquinaria'];
        $sql = "SELECT * FROM detalle_equipos
                INNER JOIN Equipos ON detalle_equipos.idEquipos=Equipos.idEquipo
                INNER JOIN EquiposGrupos ON detalle_equipos.clase_equipo = EquiposGrupos.idGrupo
                WHERE idEquipos='$maquinaria'";
        $res = sqlsrv_query($conn,$sql);
        while($mac = sqlsrv_fetch_array($res)){
            $maquina = $mac['modelo']." - ".$mac['Identificacion'];
            $modelo = $mac['Descripcion'];
            $horometro_final = $mac['horometro_final'];
        }
    }else{
        $maquinaria = $_GET['maquinaria'];
        $tique = $_GET['tique'];
        $sql="SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, 
                     Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                     Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, 
                     Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado, Registro_tique_cargadores.fecha_cierre_tique, 
                     Registro_tique_cargadores.id_proveedor, Proveedores.RazonSocial, Destino.Descripcion AS PATIO, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                     Usuarios.NombreUsuarioLargo, detalle_equipos.horometro_final, Equipos.idPropietario, EquiposGrupos.Descripcion AS Categoria,
                     Destino.idDestino
                FROM Registro_tique_cargadores INNER JOIN
                     Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                     Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                     Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                     Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario INNER JOIN
                     detalle_equipos ON Equipos.idEquipo = detalle_equipos.idEquipos INNER JOIN
                     EquiposGrupos ON detalle_equipos.clase_equipo = EquiposGrupos.idGrupo
                WHERE Registro_tique_cargadores.id_registro='$tique'";

        $res = sqlsrv_query($conn,$sql);
        while($datos = sqlsrv_fetch_array($res)){
            $operador = $datos['NombreUsuarioLargo'];
            $maquina = $datos['NombreCargador']." - ".$datos['Identificacion'];
            $id_patio = $datos['idDestino'];
            $patio = $datos['PATIO'];
            $modelo = $datos['Categoria'];
            $horometro_final = $datos['horometro_final'];
        }
    }

    $sql1 = "SELECT * FROM ReporteFallasMaquinaria WHERE id_maquinaria='$maquinaria' AND (id_jefeMantto IS NULL OR id_mecanico IS NULL)";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $result=sqlsrv_query($conn,utf8_decode($sql1),$params,$options);
    $rows=sqlsrv_num_rows($result);
    if ($rows > 0){
        while($DataReport = sqlsrv_fetch_array($result)){
            $FechaRegistro = $DataReport['FechaRegistro'];
            $FechaCierre = $DataReport['FechaCierre'];
            $idReporteFalla = $DataReport['idReporteFalla'];
            $descripcion_falla = $DataReport['DescripcionFalla'];
            $horo_reporte_falla = $DataReport['horo_reporte_falla'];
            $HoroReporte_inicial = $DataReport['HoroReporte_inicial'];
            $HoroReporte_final = $DataReport['HoroReporte_final'];
            $id_operadorReport = $DataReport['id_operador'];
            $id_mecanicoReport = $DataReport['id_mecanico'];
            $id_jefeManttoReport = $DataReport['id_jefeMantto'];
            $observaciones = $DataReport['Observaciones'];
        }
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
        <title>REPORTE FALLAS</title>
        <?php include 'libreria.php'; ?>
        <meta name="viewport" content="width=auto, initial-scale=0.7">
    </head>
<body> 
    <?php
    include 'Header.php';
    ?>
    <div class="container">
            <br><br>
            <tr>
                <h1> <center><img src="../Imagenes/LOGO LATAM.jpg" border="0" width="300" height="70"></center></h1>
            </tr>
            <tr>
                <h1> <center>LATAM COAL MINES S.A.S.</center></h1>
            </tr>
            <table border="4" class="table table-hover table-condensed table-bordered table-responsive table-striped">
             <tr>
                <th colspan="4" style="background-color:#2AA2E3; color:white;"><h3><center> NOTIFICACION DE FALLAS </center></h3></th>
            </tr>
            <tr>
                <th colspan="4" style="background-color:#FFFBC5"><H4><center></center></H4></th>
            </tr>
            <tr>
                <th colspan="4" style="background-color:#2AA2E3; color:white;"><h3><center> DATOS DEL USUARIO </center></h3></th>
            </tr>

            <tr>
                <td><b><center>OPERADOR</center></b></td>
                <td colspan="2"><b><center>Fecha - Hora Apertura</center></b></td>
                <td colspan="2"><b><center>Fecha - Hora Cierre</center></b></td>
            </tr>
             <tr>
                <input type="hidden" id="operador" value="<?php echo $usuario; ?>">
                <td>
                    <center>
                        <?php 
                        if($rows > 0){
                            if($id_operadorReport != null){
                                echo $id_operadorReport;
                            }
                        }else{
                            echo $operador;
                        } ?>
                    </center>
                </td>
                <input type="hidden" id="fechaRegistro" value="<?php echo $fecha_hora; ?>">
                <td colspan="2">
                    <center>
                    <?php 
                        if ($rows > 0){
                            if ($FechaRegistro != 0){
                                echo date_format($FechaRegistro,'d-M-y h:i');
                            }else{
                                echo $fecha_hora;
                            }
                        }else{
                            echo $fecha_hora;
                        }
                    ?>
                    </center>
                </td>
                <td colspan="2">
                    <center>
                        <?php 
                        if ($rows > 0){
                            if ($FechaCierre != 0){
                                echo date_format($FechaCierre,'d-M-y h:i');
                            }else{
                                echo $fecha_hora;
                            }
                        }else{
                            echo $fecha_hora;
                        }
                    ?>
                    </center>
                </td>
            </tr>
        </table>
        <table border="4" class="table table-hover table-condensed table-bordered table-responsive table-striped">
            <tr>
                <th colspan="6" style="background-color:#2AA2E3;color:black;"><H4><center>DATOS DEL EQUIPO</center></H4></th>
            </tr>
            <tr>
                <th><center>CODIGO</center></th>
                <th><center>MODELO</center></th>
                <th colspan="3"><center>HOROMETRO</center></th>
                <th><center>LUGAR DE TRABAJO</center></th>
            </tr>
            <tr>
                <input type="hidden" id="maquinaria" value="<?php echo $maquinaria; ?>">
                <td rowspan="2"><center><?php echo $maquina; ?></center></td>
                <td rowspan="2"><center><?php echo $modelo; ?></center></td>
                <td><center>H. REPORTE</center></td>
                <td><center>H. INICIAL</center></td>
                <td><center>H. FINAL</center></td>
                <input type="hidden" id="patio" value="<?php echo $id_patio;  ?>">
                <td rowspan="2"><center><?php echo $patio; ?></center></td>
            </tr>
            <tr>
                <input type="hidden" id="horo_reporte" value="<?php echo $horometro_final; ?>">
                <td>
                    <center>
                        <?php 
                        if ($rows > 0){
                            if ($horo_reporte_falla <> 0){
                                echo $horo_reporte_falla;
                            }else{
                                echo $horometro_final;
                            }
                        }else{
                            echo $horometro_final;
                        }
                        ?>
                    </center>
                </td>
                <td style="width: 17%"><input type="text" id="horometro_inicial" class="form-control" <?php if($rows > 0){ if ($HoroReporte_inicial == null){ if($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){ echo "readonly"; }else{  echo 'value="'.$horometro_final.'"';  } }else{ if ($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){ echo 'readonly value="'.$HoroReporte_inicial.'"'; }else{  echo 'readonly value="'.$HoroReporte_inicial.'"';  }}}else{if($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){ echo "readonly"; }else{  echo 'value="'.$horometro_final.'"';  }} ?>></td>
                <td style="width: 17%"><input type="text" id="horometro_final" class="form-control" <?php if($rows > 0){ if ($HoroReporte_final == null && $HoroReporte_inicial != null){ if($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){ echo "readonly"; }else{  echo 'placeholder="Digite el horometro final"';  } }else{ if ($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){ echo 'readonly value="'.$HoroReporte_final.'"'; }else{  echo 'readonly value="'.$HoroReporte_final.'"';  }}}else{if($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){ echo "readonly"; }else{  echo 'placeholder="Digite el horometro final"';  }} ?>></td>
            </tr>
        </table>
        <table border="4" class="table table-hover table-condensed table-bordered table-responsive table-striped">
            <tr>
                <th colspan="4" style="background-color:#2AA2E3;color:black;"><H4><center>TIPO DE FALLA  /  DESCRIPCION DE LA FALLA </center></H4></th>
            </tr>
             
            <tr>
                <td colspan="4"><center><textarea class="form-control" placeholder="Sea lo más claro y preciso con la descripción de la falla" id="reporte_falla" rows="10" cols="139" <?php if($rows > 0){ if ($descripcion_falla == null){ if($_SESSION['permisoIngresar'] != 'OPERADOR_CARGADOR'){ echo "readonly"; } }else{ echo "readonly"; }}else{ if($_SESSION['permisoIngresar'] != 'OPERADOR_CARGADOR'){ echo "readonly"; } }?>>
<?php if($rows > 0){ if ($descripcion_falla != null){ echo $descripcion_falla;  }}?></textarea></center>
                </td>         
            </tr>
            </table>
            <table border="4" class="table table-hover table-condensed table-bordered table-responsive table-striped">
             <tr>
                <th colspan="3" style="background-color:#2AA2E3;color:black;"><H4><center>AUTORIZACIONES</center></H4></th>
            </tr>
            <tr>
                <th><center>OPERADOR:</center></th>
                <th><center>MECANICO:</center></th>
                <th><center>JEFE MANTENIMIENTO:</center></th>
            </tr>
            <tr>
            </tr>
            <tr>
                <input type="hidden" id="Password_usu" value="<?PHP echo $Password;?>">
                <type aling=center>
                <th><center>
                    <?php 
                    if ($rows == 0){
                    ?>
                        <input type="password" class="form-control" id="operador1" <?php if($_SESSION['permisoIngresar'] != 'OPERADOR_CARGADOR'){ echo "readonly"; } ?>>
                    <?php
                    }else{
                        echo $id_operadorReport;
                    }
                    ?>
                </center></th>
                <th><center>
                    <?php
                    if ($rows == 0){
                    ?>
                    <input type="password" class="form-control" id="mecanico1" <?php if ($_SESSION['permisoIngresar'] != 'MECANICO_CARGADORES'){ echo "readonly"; } ?>>
                    <?php
                    }else{
                        if ($id_mecanicoReport == null){
                            ?>
                            <input type="password" class="form-control" id="mecanico1" <?php if ($_SESSION['permisoIngresar'] != 'MECANICO_CARGADORES'){ echo "readonly"; } ?>>
                        <?php
                        }else{
                            echo $id_mecanicoReport;
                        }
                    }
                    ?>
                </center></th>
                <th><center>
                    <?php
                    if ($rows == 0){
                    ?>
                    <input type="password" class="form-control" id="jefe_mantto1" <?php if($_SESSION['permisoIngresar'] != 'MTTO_CARGADORES'){  echo "readonly"; }  ?>>
                    <?php
                    }else{
                        if ($id_jefeManttoReport == null){
                            ?>
                            <input type="password" class="form-control" id="jefe_mantto1" <?php if($_SESSION['permisoIngresar'] != 'MTTO_CARGADORES'){  echo "readonly"; }  ?>>
                            <?php
                        }else{
                            echo $id_jefeManttoReport;
                        }
                    }
                    ?>
                </center></th>
            </tr> 
            
            <tr>
                <th colspan="3" style="background-color:#2AA2E3;color:black;"><H4><center>OBSERVACIONES</center><H4></th>
            </tr>
             <tr>
                <td colspan="3">
                    <center>
                        <?php 
                        if ($rows > 0){
                            if ($id_operadorReport != null && $observaciones != null && $id_mecanicoReport != null && $id_jefeManttoReport != null){
                                
                                ?><textarea class="form-control" rows="8" cols="145" id="observaciones"><?php echo $observaciones.'.'; ?></textarea><?php

                            }elseif($id_operadorReport != null && ($id_mecanicoReport == null || $id_jefeManttoReport == null)){

                                if ($_SESSION['permisoIngresar'] != 'OPERADOR_CARGADOR'){

                            ?><textarea class="form-control" rows="10" cols="139" id="observaciones"><?php echo $observaciones.'.'; ?>

<?php echo '* '.$Nombre.' : ';  ?></textarea><?php

                                }else{

                                ?><textarea class="form-control" rows="10" cols="139" id="observaciones" readonly=""><?php echo $observaciones.'.'; ?></textarea><?php

                                }
                            }
                        }else{ 

                            ?><textarea class="form-control" rows="10" cols="139" id="observaciones">
<?php echo '* '.$Nombre.' :';  ?> </textarea><?php
                        }   ?>
                    </center>
                </td>
            </tr>
        </table>
        <?php 
            if ($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){
                if ($rows > 0){
                    if ($id_operadorReport == null){
                    ?><button class="btn btn-success navbar-right" style="margin-right: 3px;" onclick="Pasos('operador')">Finalizar</button><?php
                    }
                }else{
                    ?><button class="btn btn-success navbar-right" style="margin-right: 3px;" onclick="Pasos('operador')">Finalizar</button><?php
                }
            }elseif($_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES'){
                if ($rows > 0){
                    if ($HoroReporte_inicial != null && $HoroReporte_final == null){
                    ?>
                        <button class="btn btn-success navbar-right" style="margin-right: 3px;" onclick="Pasos('mecanicoFin')">Finalizar</button>
                    <?php
                    }elseif($id_mecanicoReport == null){
                    ?>
                        <button class="btn btn-success navbar-right" style="margin-right: 3px;" onclick="Pasos('mecanico')">Finalizar</button>
                    <?php
                    }
                }
            }elseif($_SESSION['permisoIngresar'] == 'MTTO_CARGADORES'){
                if ($rows > 0){
                    if ($HoroReporte_inicial != null && $HoroReporte_final == null){
                    ?>
                        <button class="btn btn-success navbar-right" style="margin-right: 3px;" onclick="Pasos('jefe_manttoFin')">Finalizar</button>
                    <?php
                    }elseif($id_jefeManttoReport == null){
                    ?>
                        <button class="btn btn-success navbar-right" style="margin-right: 3px;" onclick="Pasos('jefe_mantto')">Finalizar</button>
                    <?php
                    }
                }
            }
            ?>
            <br><br><br>
            <input type="hidden" id="idReporteFalla" value="<?php echo $idReporteFalla; ?>">
    </div>
    <script type="text/javascript">
        function Pasos(rol){
            if (rol == 'operador'){
                if ($('#operador1').val() == $('#Password_usu').val()){
                    operador = $('#operador').val();
                    fechaRegistro = $('#fechaRegistro').val();
                    horo_inicial = $('#horo_reporte').val();
                    maquinaria = $('#maquinaria').val();
                    patio = $('#patio').val();
                    if ($('#reporte_falla').val() == ""){
                        alert('Escriba el motivo de la falla');
                    }else{
                        reporte_falla = $('#reporte_falla').val();
                    }
                    band = 1;
                    observaciones = $('#observaciones').val();
                    cadena = "band=" + band +
                            "&operador=" + operador +
                            "&fechaRegistro=" + fechaRegistro +
                            "&horo_inicial=" + horo_inicial +
                            "&maquinaria=" + maquinaria +
                            "&patio=" + patio +
                            "&reporte_falla=" + reporte_falla +
                            "&observaciones=" + observaciones;
                    $.ajax({
                        type: "POST",
                        url: "../modelo/reporte_fallas.php",
                        data: cadena,
                        success: function (r) {
                            console.log(r);
                            if (r == 1){
                                alert("Se realizó correctamente");
                                self.location="inicio.php";
                            }
                        }
                    });
                }else{
                    alert('La contraseña es incorrecta');
                }
            }else if (rol == 'mecanico'){
                if ($('#mecanico1').val() == $('#Password_usu').val()){
                    band = 2;
                    mecanico = $('#operador').val();
                    idReporteFalla = $('#idReporteFalla').val();
                    horometro_inicial = $('#horometro_inicial').val();
                    //horometro_final = $('#horometro_final').val();
                    observaciones = $('#observaciones').val();
                    cadena = "band=" + band +
                            "&mecanico=" + mecanico +
                            "&idReporteFalla=" + idReporteFalla +
                            "&horometro_inicial=" + horometro_inicial +
                            //"&horometro_final=" + horometro_final +
                            "&observaciones=" + observaciones;
                    $.ajax({
                        type: "POST",
                        url: "../modelo/reporte_fallas.php",
                        data: cadena,
                        success: function (r) {
                            console.log(r);
                            if (r == 1){
                                alert("Se finalizó correctamente");
                                self.location="MantenimientoCargadores.php";
                            }
                        }
                    });
                }else{
                    alert('La contraseña es incorrecta');
                }
            }else if(rol == 'jefe_mantto'){
                if ($('#jefe_mantto1').val() == $('#Password_usu').val()){
                    band = 3;
                    jefe_mantto = $('#operador').val();
                    idReporteFalla = $('#idReporteFalla').val();
                    horometro_inicial = $('#horometro_inicial').val();
                    horometro_final = $('#horometro_final').val();
                    observaciones = $('#observaciones').val();
                    cadena = "band=" + band +
                            "&jefe_mantto=" + jefe_mantto +
                            "&idReporteFalla=" + idReporteFalla +
                            "&horometro_inicial=" + horometro_inicial +
                            "&horometro_final=" + horometro_final +
                            "&observaciones=" + observaciones;
                    $.ajax({
                        type: "POST",
                        url: "../modelo/reporte_fallas.php",
                        data: cadena,
                        success: function (r) {
                            console.log(r);
                            if (r == 1){
                                alert("Se finalizó correctamente");
                                self.location="MantenimientoCargadores.php";
                            }
                        }
                    });
                }else{
                    alert('La contraseña es incorrecta');
                }
            }else if (rol == 'mecanicoFin'){
                //if ($('#mecanico1').val() == $('#Password_usu').val()){
                    band = 4;
                    mecanico = $('#operador').val();
                    idReporteFalla = $('#idReporteFalla').val();
                    //horometro_inicial = $('#horometro_inicial').val();
                    horometro_final = $('#horometro_final').val();
                    observaciones = $('#observaciones').val();
                    cadena = "band=" + band +
                            "&mecanico=" + mecanico +
                            "&idReporteFalla=" + idReporteFalla +
                            //"&horometro_inicial=" + horometro_inicial +
                            "&horometro_final=" + horometro_final +
                            "&observaciones=" + observaciones;
                    $.ajax({
                        type: "POST",
                        url: "../modelo/reporte_fallas.php",
                        data: cadena,
                        success: function (r) {
                            console.log(r);
                            if (r == 1){
                                alert("Se finalizó correctamente");
                                self.location="MantenimientoCargadores.php";
                            }
                        }
                    });
                /*}else{
                    alert('La contraseña es incorrecta');
                }*/
            }else if(rol == 'jefe_manttoFin'){
                //if ($('#jefe_mantto1').val() == $('#Password_usu').val()){
                    band = 5;
                    jefe_mantto = $('#operador').val();
                    idReporteFalla = $('#idReporteFalla').val();
                    //horometro_inicial = $('#horometro_inicial').val();
                    horometro_final = $('#horometro_final').val();
                    observaciones = $('#observaciones').val();
                    cadena = "band=" + band +
                            "&jefe_mantto=" + jefe_mantto +
                            "&idReporteFalla=" + idReporteFalla +
                            //"&horometro_inicial=" + horometro_inicial +
                            "&horometro_final=" + horometro_final +
                            "&observaciones=" + observaciones;
                    $.ajax({
                        type: "POST",
                        url: "../modelo/reporte_fallas.php",
                        data: cadena,
                        success: function (r) {
                            console.log(r);
                            if (r == 1){
                                alert("Se finalizó correctamente");
                                self.location="MantenimientoCargadores.php";
                            }
                        }
                    });
                /*}else{
                    alert('La contraseña es incorrecta');
                }*/
            }
        }
    </script>
</body>
</html>