<?php
session_start();
require_once '../modelo/conexion.php';
error_reporting(0);
if ($_SESSION["logueado"] == TRUE && ($_SESSION["permisoIngresar"] != 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] != 'MECANICO_CARGADORES')){
    $usuario = $_SESSION['usuario'];
    $Fecha = date('Y-m-d');
    $fecha_hora = date('Y-m-d H:i:s');
    $Fecha_actual = date('Y-m-d',strtotime($Fecha . ' - 15 days'));
    $Fecha_ant = date('Y-m-d',strtotime($Fecha . ' - 7 days'));
    ini_set('date.timezone', 'America/Bogota');
    $hora = date("g:i A");
    $sql_usuario = "SELECT * FROM Usuarios WHERE idUsuario='$usuario'";
    $result = sqlsrv_query($conn,$sql_usuario);
    while ($row = sqlsrv_fetch_array($result)){
      $Nombre = $row['NombreUsuarioLargo'];
    }
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
//
include('tipo_dispositivo.php');
if ($tablet_browser > 0) {
// Si es tablet has lo que necesites
   //print 'es tablet';
}
else if ($mobile_browser > 0) {
// Si es dispositivo mobil has lo que necesites
   //print 'es un mobil';
}
else {
// Si es ordenador de escritorio has lo que necesites
   //print 'es un ordenador de escritorio';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!--<meta name="viewport" content="width=auto, initial-scale=0.8">-->
    <title>Control Cargadores</title>
    <?php include './libreria.php'; ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="../controlador/archivo.js"></script>
</head>
<body onload="pepitojimenez()">
    <?php
    include 'Header.php';
    ?>
    <div class="container-fluid" style=" padding: 20px;" id="cuerpo">
      <input type="hidden" id="titulo">
        <input type="hidden" id="band" value="<?php echo $res_consulta; ?>">
        <center>
        </center>
        <button id="home" class="btn btn-default navbar-right" style="margin-right: 7px;"><span class="glyphicon glyphicon-home"></span></button>
        <?php if ($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR' || $_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){ ?>
        <button class="btn btn-success navbar-right" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertar">Crear tiquete <span class="glyphicon glyphicon-plus"></span></button>
        <?php } ?>
        <!--<button type="button" id="boton_consulta" class="btn btn-default navbar-right" style="margin-right: 15px;">Consultas</button>-->
        <?php if ($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR' || $_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){ ?>
        <button type="button" class="btn btn-primary navbar-right" style="margin-right: 15px;" title="Reporte de falla" data-toggle="modal" data-target="#modalReportar"><img src="../Imagenes/señal-alerta-png-4.png" width="30"></button>
        <?php } ?>
        <!--DATOS DE PRODUCTOS-->
        <!--DATOS DE PRODUCTOS-->
        <div class="row" id="div">
            <br><br>
            <?php
            if($_SESSION['permisoIngresar'] == 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES'){
              $sql="SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, 
                     Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                     Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, 
                     Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado, Registro_tique_cargadores.fecha_cierre_tique, 
                     Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                     Usuarios.NombreUsuarioLargo, Equipos.horometro_final, Equipos.idPropietario, Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.remision
                FROM Registro_tique_cargadores LEFT JOIN
                     Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor LEFT JOIN
                     Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino LEFT JOIN
                     Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo LEFT JOIN
                     Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
                     WHERE Equipos.idPropietario = '4A442AA8-6532-4F4F-8CED-51A6999DDB5E'
                    ORDER BY Registro_tique_cargadores.cod_reporte DESC";
            }elseif ($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA'){
                $sql="SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, 
                             Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                             Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, 
                             Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado, Registro_tique_cargadores.fecha_cierre_tique, 
                             Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                             Usuarios.NombreUsuarioLargo, Equipos.horometro_final, Equipos.idPropietario, Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.remision
                        FROM Registro_tique_cargadores LEFT JOIN
                             Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor LEFT JOIN
                             Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino LEFT JOIN
                             Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo LEFT JOIN
                             Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario 
                        /*WHERE CAST(Registro_tique_cargadores.fecha_apertura_tique as date) >= '$Fecha_ant'*/
                        ORDER BY Registro_tique_cargadores.cod_reporte DESC";
            }else{
                if($_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){
                    $empresas = $_SESSION['Array_empresa']['PATIO_CARGADORES'];    
                }elseif($_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO'){
                    $empresas = $_SESSION['Array_empresa']['AUXILIAR_PATIO'];
                }elseif($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){
                    $empresas = $_SESSION['Array_empresa']['OPERADOR_CARGADOR'];
                }
                $value = 0; 
                for ($i=0; $i < count($empresas); $i++) { 
                    if($empresas[$i] == '4A442AA8-6532-4F4F-8CED-51A6999DDB5E'){
                        $value = 1;
                    }
                }
                $empresas = implode("','", $empresas);
                if($value == 0){
                    $sql="SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, 
                             Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                             Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, 
                             Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado, Registro_tique_cargadores.fecha_cierre_tique, 
                             Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                             Usuarios.NombreUsuarioLargo, Equipos.horometro_final, Equipos.idPropietario, Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.remision
                        FROM Registro_tique_cargadores LEFT JOIN
                             Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor LEFT JOIN
                             Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino LEFT JOIN
                             Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo LEFT JOIN
                             Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario 
                             WHERE Registro_tique_cargadores.id_proveedor in ('$empresas') AND Registro_tique_cargadores.id_usuario='$usuario'
                            ORDER BY Registro_tique_cargadores.cod_reporte DESC";
                }else{
                    $sql="SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, 
                             Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                             Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, 
                             Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado, Registro_tique_cargadores.fecha_cierre_tique, 
                             Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                             Usuarios.NombreUsuarioLargo, Equipos.horometro_final, Equipos.idPropietario, Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.remision
                        FROM Registro_tique_cargadores LEFT JOIN
                             Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor LEFT JOIN
                             Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino LEFT JOIN
                             Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo LEFT JOIN
                             Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario 
                             WHERE Registro_tique_cargadores.id_usuario='$usuario'
                            ORDER BY Registro_tique_cargadores.cod_reporte DESC";
                }
            }
            $params = array();
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
            $rows=sqlsrv_num_rows($resultado);
            if ($rows > 0) 
            {   ?>
                <table id="example1" class="row-border hover order-column compact">
                    <thead>
                        <tr>
                            <th style="width: 7%">Remision</th>
                            <th style="width: 7%">N° tiquete</th>
                            <th>Cliente</th>
                            <th>Lugar</th>
                            <th style="width: 9%">Cargador</th>
                            <th style="width: 10%">Jornada Inicial</th>
                            <th style="width: 10%">Jornada Final</th>
                            <th style="width: 5%">Hrs.</th>
                            <th style="width: 8%">Estado</th>
                            <th style="width: 10%"><center>Acciones</center></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count_horas = 0;
                    while ($datos = sqlsrv_fetch_array($resultado)){
                        $a = 0;
                        $b = 0;
                        $c = 0;
                        $count_horas += $datos['horas_trabajadas'];
                        $datos_registro = $datos['id_registro']. "||" .
                                          $datos['cod_reporte']. "||" .
                                          date_format($datos['fecha_apertura_tique'],'Y-m-d  H:i:s'). "||" .
                                          utf8_encode($datos['Descripcion']). "||" .
                                          utf8_encode($datos['NombreCargador']). "||" .
                                          date_format($datos['fecha_ini_jornada'],'Y-m-d  H:i:s'). "||" .
                                          utf8_encode($datos['NombreCorto'])."||".
                                          $datos['idPropietario']. "||" .
                                          $datos['Identificacion'];
                        //$activ = $datos['horometro_final'];
                        ?>
                        <tr>
                            <td><center><?php if($datos['remision'] != null){ echo $datos['remision']; }else{ echo 0; } ?></center></td>
                            <td><center><?php echo $datos['cod_reporte']; ?></center></td>
                            <td><?php echo utf8_encode($datos['NombreCorto']); ?></td>
                            <td><?php echo $datos['Descripcion']; ?></td>
                            <td><?php echo $datos['NombreCargador']." - ".$datos['Identificacion']; ?></td>
                            <td><?php echo date_format($datos['fecha_ini_jornada'], 'M-d  h:i'); ?></td>
                            <td>
                                <?php 
                                if ($datos['fecha_fin_jornada'] == 0){
                                    echo "<center>"." ---------------------- "."</center>";
                                }else{
                                    echo date_format($datos['fecha_fin_jornada'], 'M-d  h:i');
                                }
                                ?>
                            </td>
                            <td><center><?php echo number_format($datos['horas_trabajadas'],1,',','.'); ?></center></td>
                            <?php
                            if ($datos['estado'] == 1){
                                ?>
                                <td style="background-color: #E15A5A;">
                                    <center><b>Activo</b></center>
                                <?php
                            }elseif ($datos['estado'] == 2){
                                ?>
                                <td style="background-color: #F0AF44;">
                                    <center><a href="inicio_patio.php"><b>Por Distribuir</b></a></center>
                                <?php
                            }elseif ($datos['estado'] == 3){
                                ?>
                                <td style="background-color: #94C27C;">
                                    <center>Finalizado</center>
                                <?php
                            }elseif ($datos['estado'] == 4){
                                ?>
                                <td style="background-color: #B57CC2;">
                                    <center><b>Corrección</b></center>
                                <?php
                            }?>
                            </td>
                            <td>
                                <?php 
                                if ($datos['fecha_fin_jornada'] == 0){
                                    $sql = "SELECT horometro.fecha_cierre_horometro ,horometro.id_horometro, horometro.id_registro, horometro.horometro_inicial, 
                                            horometro.horometro_final, horometro.total_horas, horometro.idActividad, horometro.fecha_registro_horometro, 
                                            Actividades.Descripcion, SubActividades_cargadores.Descripcion as Descripcion_sub
                                            FROM horometro LEFT JOIN
                                            Actividades ON horometro.idActividad = Actividades.idActividad
                                            LEFT JOIN SubActividades_cargadores ON horometro.idSubActividad=SubActividades_cargadores.idSubActividad
                                            WHERE horometro.id_registro='". $datos['id_registro'] ."' AND horometro_final=0";
                                    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                                    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
                                    $rows=sqlsrv_num_rows($res);
                                    if ($rows > 0)
                                    {   while ($horo = sqlsrv_fetch_array($res)) {
                                            if ($horo['horometro_final'] == 0){
                                                if ($horo['Descripcion'] != "" && $horo['Descripcion_sub'] != ""){
                                                    $band = 1;
                                                    $valores =  $band.",". 
                                                                utf8_encode($horo['id_registro']). "," .
                                                                $horo['horometro_inicial']. "," .
                                                                utf8_encode($horo['Descripcion']). "," .
                                                                $horo['id_horometro']. ",".
                                                                date_format($horo['fecha_registro_horometro'],'Y-m-d H:i:s').",".
                                                                utf8_encode($horo['Descripcion_sub']);
                                                }else{
                                                    $band = 0;
                                                    $valores =  $band.",".
                                                                utf8_encode($horo['id_registro']). "," .
                                                                $horo['horometro_inicial']. "," .
                                                                //utf8_encode($horo['Descripcion']). "," .
                                                                $horo['id_horometro']. ",".
                                                                date_format($horo['fecha_registro_horometro'],'Y-m-d H:i:s');//.",".
                                                                //utf8_encode($horo['Descripcion_sub']);
                                                }
                                                $a++;
                                                $a1 = $horo['id_registro'];
                                            }
                                        }
                                    }else{
                                        $sql = "SELECT Mantenimiento_equipos.idxid, Mantenimiento_equipos.idEquipo, Mantenimiento_equipos.horo_mtto_inicial,
                                                        Mantenimiento_equipos.horo_mtto_final, Mantenimiento_equipos.total_horas, Mantenimiento_equipos.fecha_inicial_mtto, 
                                                        Mantenimiento_equipos.fecha_final_mtto, Mantenimiento_equipos.observaciones, Equipos.Descripcion, Equipos.Identificacion, 
                                                        Proveedores.NombreCorto, EquiposGrupos.Descripcion AS Categoria, Usuarios.NombreUsuarioLargo, Mantenimiento_equipos.id_usuario
                                                FROM Mantenimiento_equipos INNER JOIN
                                                    Equipos ON Mantenimiento_equipos.idEquipo = Equipos.idEquipo INNER JOIN
                                                    Proveedores ON Equipos.idPropietario = Proveedores.idProveedor INNER JOIN
                                                    EquiposGrupos ON Equipos.clase_equipo = EquiposGrupos.idGrupo INNER JOIN
                                                    Usuarios ON Mantenimiento_equipos.id_usuario = Usuarios.idUsuario
                                                WHERE Mantenimiento_equipos.idEquipo='".$datos['id_maquinaria']."' AND horo_mtto_final=0";
                                        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                                        $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
                                        $rows=sqlsrv_num_rows($res);
                                        if ($rows > 0) 
                                        {   while($mantto = sqlsrv_fetch_array($res)){
                                                if ($mantto['horo_mtto_final'] == 0){
                                                    $valores1 = $mantto['idxid']."||".
                                                                date_format($mantto['fecha_inicial_mtto'],'Y-m-d H:i:s')."||".
                                                                $mantto['horo_mtto_inicial']."||".
                                                                $mantto['Descripcion']."||".
                                                                $mantto['Identificacion']."||".
                                                                $mantto['NombreCorto']."||".
                                                                $mantto['Categoria']."||".
                                                                $mantto['NombreUsuarioLargo']."||".
                                                                $datos['id_registro']."||".
                                                                $mantto['idEquipo'];
                                                    $b++;
                                                    $iniciado_por = $mantto['id_usuario'];
                                                    $a2 = $mantto['idEquipo'];
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <center>
                                        <?php 
                                        if($a != 0 && $a1 == $datos['id_registro']){
                                            if ($datos['id_proveedor'] == '24B7153E-AB4C-4DB7-81BD-67BC87AF014C' || $datos['id_proveedor'] == '0247FA36-ABF5-4A35-9E8B-BE7C574243DE'){
                                                if ($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR' || $_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){
                                            ?>
                                               <button class="btn btn-danger btn-xs" title="Finalizar actividad" data-toggle="modal" data-target="#modalFinalizarAct" onclick="FinalizarActividad('<?php echo $valores; ?>','1')">
                                                    <span class="glyphicon glyphicon-minus"></span>
                                                </button> 
                                            <?php
                                                }
                                            }else{
                                              ?>
                                                <button class="btn btn-danger btn-xs" title="Finalizar actividad" data-toggle="modal" data-target="#modalFinalizarAct1" onclick="FinalizarActividad1('<?php echo $valores; ?>','1')">
                                                    <span class="glyphicon glyphicon-minus"></span>
                                                </button> 
                                            <?php  
                                            }
                                        }elseif($b != 0 && $a2 == $datos['id_maquinaria']){
                                            if ($iniciado_por == $usuario){
                                                if ($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR' || $_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){
                                            ?>  <button class="btn btn-danger btn-xs" title="Hay un mantenimiento en proceso" data-toggle="modal" data-target="#modalFinalizarMtto" onclick="FinalizarActividad('<?php echo $valores1; ?>','2')">
                                                    <span class="glyphicon glyphicon-wrench"></span>
                                                </button>
                                            <?php
                                                }
                                            }else{
                                            ?>  <button class="btn btn-danger btn-xs" title="Hay un mantenimiento en proceso">
                                                    <span class="glyphicon glyphicon-wrench"></span>
                                                </button>
                                            <?php
                                            }
                                        }else{
                                            if ($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR' || $_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){
                                            ?>
                                            <button class="btn btn-info btn-xs" title="Iniciar actividad" data-toggle="modal" data-target="#modalHorometro" onclick="datos('<?php echo $datos['id_registro']; ?>','<?php echo $datos['horometro_final']; ?>','2')">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                            <?php
                                            }
                                        }
                                        if ($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR' || $_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){
                                        ?>
                                        <button id="button" title="Finalizar tiquete" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalFinalizar" onclick="ver('<?php echo $datos_registro; ?>')">
                                            <span class="glyphicon glyphicon-ok"></span>
                                        </button>
                                        <?PHP 
                                        }
                                        ?>
                                        <a href="horometros.php?idTiquete=<?php echo $datos['id_registro']; ?>&Reporte=<?php echo $datos['cod_reporte']; ?>&final=<?php echo $datos['horometro_final']; ?>">
                                            <button class="btn btn-default btn-xs" title="ver horometros">
                                                <span class="glyphicon glyphicon-eye-open"></span>
                                            </button>
                                        </a>
                                    </center>
                                <?php
                                }else{
                                    ?>
                                    <center>
                                        <button class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalInforme" onclick="informe('<?php echo $datos['id_registro']; ?>')">
                                          <!--<a title="Informe PDF" href="informe_horometros-pdf.php?idTiquete=<?php echo $datos['id_registro']; ?>" target="_blank">-->
                                            <img src="../Imagenes/pdf-solictud.png" width="20" height="30"/>
                                          <!--</a>-->
                                        </button>
                                    </center>
                                    <?php
                                }
                                ?>
                            </td>
                            <?php 
                            if ($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR' || $_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){
                            } ?>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                    <!--<tr>
                        <td  colspan="5"></td>
                        <td><b>Total horas:</b></td>
                        <td><center><b><?php echo number_format($count_horas,1,',','.'); ?></b></center></td>
                    </tr>-->
                </table>
            <?php
            } else {
                echo "<br><br><br><br><center>No hay tiquetes registrados por el momento</center>";
            }
            ?>
        </div>
        <p id="valoresFecha"></p>
        <input type="hidden" id="abcd">
        <input type="hidden" id="valoresFechaInput">
        <script>
            function pepitojimenez(){
                if($('#abcd').val() == ' 12:0:52' || $('#abcd').val() == ' 18:30:0'){
                    //alert('Ya vete a descansar wapo :v');
                }
            var fecha= new Date();
            var m = moment(); 
            var horas= fecha.getHours();
            var minutos = fecha.getMinutes();
            var segundos = fecha.getSeconds();
            //document.getElementById('valoresFecha').innerHTML=' '+horas+':'+minutos+':'+segundos+'';
            document.getElementById('abcd').value=' '+horas+':'+minutos+':'+segundos+'';
            document.getElementById('valoresFechaInput').value=''+'<?php echo $Fecha = date('Y-m-d'); ?>'+' '+horas+':'+minutos+':'+segundos+'';
            document.getElementById('fechaMtto').value=''+'<?php echo $Fecha = date('Y-m-d'); ?>'+' '+horas+':'+minutos+':'+segundos+'';
            document.getElementById('fecha').value=''+'<?php echo $Fecha = date('Y-m-d'); ?>'+' '+horas+':'+minutos+':'+segundos+'';
            document.getElementById('fecha1').value=''+'<?php echo $Fecha = date('Y-m-d'); ?>'+' '+horas+':'+minutos+':'+segundos+'';
            document.getElementById('FechaHora_ini').value=''+'<?php echo $Fecha = date('Y-m-d'); ?>'+' '+horas+':'+minutos+':'+segundos+'';
            setTimeout('pepitojimenez()',1000);
            }
        </script>
    </div>
    
    <!-- MODAL PARA REGISTRAR DATOS DEL TIQUETE-->
    <!-- MODAL PARA REGISTRAR DATOS DEL TIQUETE-->
    <div class="modal fade" tabindex="0" id="modalInsertar" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Registro Tiquete</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_operador" value="<?php echo $usuario; ?>">
                    <input type="number" id="servicio_clasif" hidden="">
                    <div class="row form-group">
                        <div class="col-xs-2  col-sm-4  col-md-4  col-lg-4  col-xl-4"></div>
                        <div class="col-xs-4  col-sm-2  col-md-2  col-lg-2  col-xl-2">
                            <label>TIQUETE N°</label>
                        </div>
                        <div class="col-xs-4  col-sm-2  col-md-2  col-lg-2  col-xl-2">
                            <input type="text" id="id_registro" class="form-control" value="<?php
                            $sql = "SELECT MAX(cod_reporte) as Maximo FROM Registro_tique_cargadores";
                            $result = sqlsrv_query($conn, $sql);
                            if ($result) {
                                while ($row = sqlsrv_fetch_array($result)) {
                                    $valor = $row['Maximo'] + 1;
                                }
                            }
                            echo $valor;
                            ?>" readonly>
                        </div>
                        <!--<div class="col-sm-5">
                            <label>Proveedor</label>
                            <input type="text" id="buscar_proveedor" class="form-control" placeholder="Proveedor..." readonly="">
                        </div>-->
                    </div>
                    <div class="row form-group">
                        <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                            <label>Lugar</label>
                        </div>
                        <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                            <select class="form-control" id="lugar">
                                <option selected="true">--- Seleccione ---</option>
                                <?php 
                                $sql_destino = "SELECT * FROM Destino WHERE OperacionCargador=1 ORDER BY Descripcion";
                                $result = sqlsrv_query($conn,$sql_destino);
                                while ($destino = sqlsrv_fetch_array($result)){
                                ?>
                                    <option value="<?php echo $destino['idDestino']; ?>"><?php echo utf8_encode($destino['Descripcion']); ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                            <label>Cliente</label>
                        </div>
                        <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                            <?php
                            $val = 0; 
                            if($_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){
                                $empresas = $_SESSION['Array_empresa']['PATIO_CARGADORES'];    
                            }elseif($_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO'){
                                $empresas = $_SESSION['Array_empresa']['AUXILIAR_PATIO'];
                            }elseif($_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA'){
                                $empresas = $_SESSION['Array_empresa']['CONSULTAS_OFICINA'];
                            }elseif($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES'){
                                $empresas = $_SESSION['Array_empresa']['ADMIN_CARGADORES'];
                            }elseif($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){
                                $empresas = $_SESSION['Array_empresa']['OPERADOR_CARGADOR'];
                            }
                            for ($i=0; $i < count($empresas); $i++) { 
                                if($empresas[$i] == '4A442AA8-6532-4F4F-8CED-51A6999DDB5E'){
                                    $val = 1;
                                }
                            }
                            $empresas = implode("','", $empresas);
                            ?>
                            <select class="form-control" id="cliente">
                                <option>--- Seleccione ---</option>
                                <?php
                                if($val == 0){
                                    $sql = "SELECT * FROM Proveedores WHERE idProveedor in (SELECT idProveedor FROM  vProveedoresInAgrupacion WHERE Alias='CC') and idProveedor in ('$empresas') ORDER BY NombreCorto";
                                }else{
                                    $sql = "SELECT * FROM Proveedores WHERE idProveedor in (SELECT idProveedor FROM  vProveedoresInAgrupacion WHERE Alias='CC') ORDER BY NombreCorto";
                                }
                                //$sql = "SELECT * FROM proveedores where Empresa=1";
                                $result = sqlsrv_query($conn,$sql);
                                while($cliente = sqlsrv_fetch_array($result)){
                                    ?>
                                    <option value="<?php echo $cliente['idProveedor']; ?>"><?php echo utf8_encode($cliente['NombreCorto']); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                            <label>Proveedor</label>
                        </div>
                        <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                            <select class="form-control" id="propietario" onchange="llenar_select()">
                                <option>--- Seleccione ---</option>
                                <?php 
                                $sql = "SELECT * FROM Proveedores WHERE idProveedor in (SELECT idProveedor FROM  vProveedoresInAgrupacion WHERE Alias='PC') ORDER BY RazonSocial";
                                $res = sqlsrv_query($conn,$sql);
                                while($propietario = sqlsrv_fetch_array($res)){
                                    ?>
                                    <option value="<?php echo $propietario['idProveedor']; ?>">
                                        <?php echo utf8_encode($propietario['RazonSocial']); ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="row form-group" id="select_cargador">
                            <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <label>Equipo</label>
                            </div>
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <select class="form-control" name="cargador" id="cargador"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-xs-2  col-sm-4  col-md-4  col-lg-4  col-xl-4"></div>
                        <div class="col-xs-8  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                            <center>
                                <label>Tipo de operación</label>
                                <button class="btn btn-default" id="opcion_1" onclick="operacion('1')">Clasificación de terceros</button>
                                <button class="btn btn-default" id="opcion_2" onclick="operacion('0')">Operación Minex</button>
                            </center>
                        </div>
                        <div class="col-xs-2  col-sm-4  col-md-4  col-lg-4  col-xl-4"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" id="registrar">
                        Registrar Tiquete
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <div class="modal fade" id="modalHorometro" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Iniciar Actividad</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idUsuario" value="<?php echo $usuario; ?>">
                    <input type="hidden" id="registroHorometro">
                    <input type="hidden" id="var" value="0">
                    <div class="row form-group center-block">
                        <div class="col-sm-1  col-md-1  col-lg-1  col-xl-1"></div>
                        <div class="col-xs-5  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                            <label>Horometro inicial:</label>
                            <input  type="text" id="horo_inicial" class=" form-control">
                        </div>
                        <div class="col-xs-2  col-sm-2  col-md-2  col-lg-2  col-xl-2"></div>
                        <div class="col-xs-5  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                            <label>Hora inicio actividad</label>
                            <input type="text" id="FechaHora_ini" class="form-control" readonly>
                        </div>
                        <div class="col-sm-1  col-md-1  col-lg-1  col-xl-1">
                            <input type="hidden" id="actividad">
                            <input type="hidden" id="sub_actividad">
                        </div>
                    </div>
                    <div class="row form-group center-block" style="background-color: powderblue;">
                        <input type="hidden" id="punto">
                        <center>
                            <h4>
                                <!--<button id="btn_1" class="btn btn-default btn-xs" onclick="MostrarAct('1')">ACTIVIDADES DETALLADAS</button>
                                &nbsp;-->
                                <button id="btn_2" class="btn btn-default btn-xs" onclick="MostrarAct('2')">DISTRIBUCIÓN DE ACTIVIDADES</button>
                                &nbsp;
                                <button id="btn_3" class="btn btn-default btn-xs" onclick="MostrarAct('3')">MANTENIMIENTO </button>
                            </h4>
                        </center>
                    </div>
                    <div class="row form-group center-block" id="ActMantto">
                        <div class="col-xs-5  col-sm-5  col-md-5  col-lg-5  col-xl-5"></div>
                        <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                            <input type="hidden" id="TipoMantto">
                            <form name="form3">
                                <?php 
                                if ($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){
                                ?>
                                    <input type="checkbox" id="1" value="1"> Eventual
                                <?php }else{ ?>
                                    <input type="checkbox" id="2" value="2"> General <br>
                                    <input type="checkbox" id="3" value="3"> Lavado Programado <br>
                                    <input type="checkbox" id="4" value="4"> Sistema Electrico Progamado<br>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                    <div class="row form-group center-block" id="ActDetallado">
                        <form id="form" name="form">
                            <?php
                            $sql = "SELECT * FROM Actividades WHERE idTipoActividad='00000000-0000-0000-0000-000000000001'";
                            $resul = sqlsrv_query($conn,$sql);
                            while ($actividad = sqlsrv_fetch_array($resul)) {
                                if($actividad['Descripcion'] != 'STANDBAY'){
                                ?>
                                    <div class="col-sm-4" style="margin-top: 15px;">
                                        <center><button type="button" id="<?php echo $actividad['idActividad']; ?>" class="btn btn-default" value="<?php echo $actividad['idActividad']; ?>" onclick="actividad('<?php echo $actividad['idActividad']; ?>')" ><?php echo utf8_encode($actividad['Descripcion']); ?></button>
                                        </center>
                                    </div>
                                <?php
                                }
                            }
                            ?>
                        </form>
                    </div>
                    <div class="row form-group center-block" style="background-color: powderblue;" id="borde"><center><h4>SubActividades</h4></center></div>
                    <div class="row form-group center-block">
                        <div class="col-xs-8  col-sm-8  col-md-8  col-lg-8  col-xl-8" id="div_subactividad">
                            <form id="frm" name="frm"></form>
                        </div>
                    </div>
                    <div class="row form-group center-block" id="ActDistribucion">
                        <center>
                            <h5 id="conten-actividad"></h5>
                        </center>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="AgregarHorometro">
                        Iniciar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="0" id="modalFinalizar" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Reporte Diario</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="registro_fin">
                    <div class="row form-group center-block">
                        <div class="col-xs-2  col-sm-4  col-md-4  col-lg-4  col-xl-4"></div>
                        <div class="col-xs-8  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                            <label>REPORTE DIARIO</label>
                            <input type="text" id="reporte_fin" class="form-control" readonly>
                        </div>
                        <div class="col-xs-2  col-sm-4  col-md-4  col-lg-4  col-xl-4"></div>
                    </div>
                    <br>
                    <div class="row form-group">
                        <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                            <label>Proveedor</label>
                        </div>
                        <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                            <input type="text" class="form-control" id="proveedor" readonly="" placeholder="Proveedor... ">
                        </div>
                        <div class="col-sm-1  col-md-1  col-lg-1  col-xl-1"></div>
                        <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                            <label>Lugar: </label>
                        </div>
                        <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                            <input type="text" class="form-control" id="lugar_fin" readonly="">
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                            <label>Cargador:</label>
                        </div>
                        <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                            <input type="text" class="form-control" id="cargador_fin" readonly="">
                        </div>
                        <div class="col-sm-1  col-md-1  col-lg-1  col-xl-1"></div>
                        <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                            <label>Jornada inicial:</label>
                        </div>
                        <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                            <input type="text" id="jornada_inicial_fin" class="form-control" readonly="">
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                            <label>Clientes:</label>
                        </div>
                        <div class="col-xs-5  col-sm-5  col-md-5  col-lg-5  col-xl-5">
                            <input type="text" class="form-control" id="cliente_fin" readonly="">
                        </div>
                        <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1"></div>
                        <!--<div class="col-sm-2">
                            <label>Hora Jornada final*</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="time" id="jornada_final_fin" class="form-control">
                        </div>-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" id="finalizar">
                        Finalizar tiquete
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalFinalizarAct" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Finalizar Actividad</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="horometro1">
                    <input type="hidden" id="registroHorometro1">
                    <div class="row form-group center-block">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            <label>Fecha Registro</label>
                            <input type="text" id="fecha_inic" class="form-control" readonly="">
                        </div>
                        <div class="col-sm-4">
                            <label>Fecha Cierre</label>
                            <input type="text" id="fecha" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-4">
                            <label>Horometro inicial:</label>
                            <input  type="text" id="horo_inicia" class=" form-control" readonly="">
                        </div>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            <label>Horometro final:</label>
                            <input  type="text" id="horo_fina" class="form-control" autofocus="autofocus" >
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <label id="title_act">Actividad</label>
                            <input type="text" class="form-control" id="Activida" readonly="">
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-6">
                            <label id="title_subact">SubActividad</label>
                            <input type="text" class="form-control" id="SubActivida" readonly="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="FinalizarActividad">
                        Finalizar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalFinalizarAct1" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Finalizar Actividad</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="horometro11">
                    <input type="hidden" id="registroHorometro11">
                    <div class="row form-group center-block">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            <label>Fecha Registro</label>
                            <input type="text" id="fecha_inic1" class="form-control" readonly="">
                        </div>
                        <div class="col-sm-4">
                            <label>Fecha Cierre</label>
                            <input type="text" id="fecha1" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-4">
                            <label>Horometro inicial:</label>
                            <input  type="text" id="horo_inicia1" class=" form-control" readonly="">
                        </div>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            <label>Horometro final:</label>
                            <input  type="text" id="horo_fina1" class="form-control" autofocus="autofocus" >
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <label id="title_act1">Actividad</label>
                            <input type="text" class="form-control" id="Activida1" readonly="">
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-6">
                            <label id="title_subact1">SubActividad</label>
                            <input type="text" class="form-control" id="SubActivida1" readonly="">
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <label>Observaciones</label>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <center><textarea cols="77" rows="4" id="observacionesHoro"></textarea></center>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="FinalizarActividad1">
                        Finalizar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalFinalizarMtto" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Finalizar Mantenimiento</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idMantto">
                    <input type="hidden" id="registroHorometroMtto">
                    <input type="hidden" id="idMaquinaria">
                    <div class="row form-group center-block">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            <label>Fecha Inicio Mantto.</label>
                            <input type="text" id="fecha_inicMtto" class="form-control" readonly="">
                        </div>
                        <div class="col-sm-4">
                            <label>Fecha Fin Mantto.</label>
                            <input type="text" id="fechaMtto" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-4">
                            <label>Horometro inicial:</label>
                            <input  type="text" id="horo_iniciaMtto" class=" form-control" readonly="">
                        </div>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            <label>Horometro final:</label>
                            <input  type="text" id="horo_finaMtto" class="form-control" autofocus="autofocus" >
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-3">
                            <label>Maquinaria</label>
                            <input type="text" id="MaquinariaMtto" class="form-control" readonly="">
                        </div>
                        <div class="col-sm-3">
                            <label>Modelo</label>
                            <input type="text" id="cargadorMtto" class="form-control" readonly="">
                        </div>
                        <div class="col-sm-6">
                            <label>Propietario</label>
                            <input type="text" id="propietarioMtto" class="form-control" readonly="">
                        </div>
                    </div>
                    <br>
                    <div class="row form-group center-block">
                            <label>Operador</label>
                            <input type="text" id="operadorMtto" class="form-control" readonly="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="FinalizarMtto">
                        Finalizar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalReportar" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Reporte de Falla</h4>
                </div>
                <div class="modal-body">
                    <div class="row form-group center-block">
                        <div class="col-sm-1">
                            <label>Patio:</label>
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control" id="patio_report">
                                <option selected="true">--- Seleccione ---</option>
                                <?php 
                                $sql_destino = "SELECT DISTINCT tz_MovimientoTransporte.DespachadoDesde, Destino.idDestino
                                              FROM tz_MovimientoTransporte INNER JOIN
                                                   Destino ON tz_MovimientoTransporte.DespachadoDesde = Destino.Descripcion
                                              WHERE (tz_MovimientoTransporte.DespachadoDesde <> 'Patio') AND (YEAR(tz_MovimientoTransporte.FechaRegistro) >= 2018)
                                              order by DespachadoDesde";
                                $result = sqlsrv_query($conn,$sql_destino);
                                while ($destino = sqlsrv_fetch_array($result)){
                                ?>
                                    <option value="<?php echo $destino['idDestino']; ?>"><?php echo utf8_encode($destino['DespachadoDesde']); ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label>Cargador</label>
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control" id="cargador_report">
                                <option selected="true">--- Seleccione ---</option>
                                <?php
                                $sql = "SELECT Equipos.idEquipo, Equipos.Descripcion, Equipos.Identificacion
                                        FROM Equipos
                                        WHERE clase_equipo='7A975CD6-2672-430D-B29E-7149A03D9410'";
                                $result = sqlsrv_query($conn,$sql);
                                while ($cargador = sqlsrv_fetch_array($result)){
                                    ?>
                                    <option value="<?php echo $cargador['idEquipo']; ?>"><?php echo utf8_encode($cargador['Descripcion']." - ".$cargador['Identificacion']); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-warning" onclick="redict()">Realizar reporte de fallas</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalInforme" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Generar Tiquete</h4>
                </div>
                <form action="informe_horometros-pdf.php" method="GET" target="_blank">
                  <div class="modal-body">
                      <div class="row">
                          <input type="hidden" name="idTiquete" id="idTiquete">
                          <div class="col-sm-5">
                              <label>Tiquete para:</label>
                          </div>
                          <div class="col-sm-7">
                            <select class="form-control" id="tipo_informe" name="tipo_informe">
                                <option value="0">Selccione</option>
                                <option value="1">Proveedor</option>
                                <option value="2">Cliente</option>
                              </select>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">
                          Generar &nbsp;<span class="glyphicon glyphicon-download-alt"></span>
                      </button>
                  </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
      function informe(idTiquete){
        $('#idTiquete').val(idTiquete);
      }
        $(document).ready(function () {
            $('#example1').DataTable( {
                "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                "ordering": true,
                "info":     true,
                stateSave: true,
                scrollY:        '50vh',
                //"scrollX": true,
                "scrollCollapse": true,
                "paging":         true
            } );

            $('#select_cargador').hide();
            $('#borde').hide();
            $('#ActDetallado').hide();
            $('#ActMantto').hide();
            $('#ActDistribucion').hide();
            band = $('#band').val();
            if (band == 0){
                document.getElementById('titulo').innerHTML = 'Registro tiquetes';
                $('#fechas').hide();
                $('#div_consulta').hide();
                //$('#div_subactividad').hide();
            }else{
                $('#div').hide();
                $('#div_consulta').show();
                $('#menu').show();
                $('#resultado').show();
                $('#pdf_consulta').show();
                document.getElementById('titulo').innerHTML = 'Consulta tiquetes';
                var Tipo = document.getElementById("Tipo_consulta").value;
                alert(Tipo);
                if (Tipo == 1){
                    document.getElementById("cliente_consulta").value="0";
                    document.getElementById("patio_consulta").value="0";
                    document.getElementById("cargador_consulta").value="0";
                    document.getElementById("fecha_inicio_consulta").value="";
                    document.getElementById("fecha_fin_consulta").value="";
                    $('#div_tiquete').show();
                    $('#div_cliente').hide();
                    $('#div_cargador').hide();
                    $('#div_buscar').show();
                    $('#fechas').hide();
                    $('#pdf_consulta').show();
                }else if (Tipo == 2){
                    document.getElementById("patio_consulta").value="0";
                    document.getElementById("cargador_consulta").value="0";
                    document.getElementById("tiquete_consulta").value="";
                    $('#div_tiquete').hide();
                    $('#div_cliente').show();
                    $('#div_cargador').hide();
                    $('#div_buscar').show();
                    $('#fechas').show();
                    $('#pdf_consulta').show();
                }else if (Tipo == 3){
                    document.getElementById("cliente_consulta").value="0";
                    document.getElementById("tiquete_consulta").value="";
                    $('#div_tiquete').hide();
                    $('#div_cliente').hide();
                    $('#div_cargador').show();
                    $('#div_buscar').show();
                    $('#fechas').show();
                    $('#pdf_consulta').show();
                }else{
                    $('#resultado').hide();
                    $('#div_tiquete').hide();
                    $('#div_cliente').hide();
                    $('#div_cargador').hide();
                    $('#div_buscar').hide();
                    $('#fechas').hide();
                    $('#pdf_consulta').show();
                }
            }
            /*if(!alertify.myAlert){
              //define a new dialog
              alertify.dialog('myAlert',function(){
                return{
                  main:function(message){
                    this.message = message;
                  },
                  setup:function(){
                      return { 
                        buttons:[{text: "Accept!", key:27//Esc}],
                        focus: { element:0 }
                      };
                  },
                  prepare:function(){
                    this.setContent(this.message);
                  }
              }});
            }
            //launch it.
            alertify.myAlert("Browser dialogs made easy!");
            */
            $(document).on('click', '#boton_consulta', (e) => {
                //alertify.confirm('falla');
                //recargar();
                //self.location = "inicio.php";
                document.getElementById('titulo').innerHTML = 'Consulta tiquetes';
                document.getElementById("fecha_inicio_consulta").value="";
                document.getElementById("fecha_fin_consulta").value="";
                $('#div').hide();
                $('#div_consulta').show();
                $('#resultado').hide();
                $('#div_tiquete').hide();
                $('#div_cliente').hide();
                $('#div_cargador').hide();
                $('#div_buscar').hide();
                $('#fechas').hide();
                $('#pdf_consulta').hide();
            });
            $(document).on('click', '#home', (e) => {
                document.getElementById('titulo').innerHTML = 'Registro tiquetes';
                document.getElementById("band").value="";
                document.getElementById("fecha_inicio_consulta").value="";
                document.getElementById("fecha_fin_consulta").value="";
                $('#div').show();
                $('#div_consulta').hide();
                $('#fechas').hide();
                $('#pdf_consulta').hide();
            });
            $(document).on('click', '#buscar', (e) => {
                $('#div').hide();
                $('#div_consulta').show();
                $('#menu').show();
                $('#resultado').show();
                $('#pdf_consulta').show();
            });
            $('#registrar').click(function (){
                operador = $('#id_operador').val();
                registro  = $('#id_registro').val();
                servicio_clasif = $('#servicio_clasif').val();
                /*if(servicio_clasif == 'on'){
                    servicio_clasif = 1;
                }else{
                    servicio_clasif = 0;
                }*/
                lugar = $('#lugar').val();
                cargador = $('#cargador').val();
                cliente = $('#cliente').val();
                insertar(operador, registro, /*fecha,*/ lugar, cargador, /*jornada_inicial,*/ cliente,servicio_clasif);
            });
            $('#AgregarHorometro').click(function (){
                //var horo_inicial = document.getElementById("horo_inicial").value;
                //var horo_final = document.getElementById("horo_final").value;
                /*if (horo_inicial > horo_final){
                    alert('El horometro final es mayor al inicial.');
                }else{*/
                    AgregarHorometro();
                //}
            });
            $('#finalizar').click(function () {
                FinalizarTiquete();
            });
        });
        $('#FinalizarActividad').click(function (){
            AgregarHorometroUpdate();
        });
        $('#FinalizarActividad1').click(function (){
            AgregarHorometroUpdate1();
        });
        $('#FinalizarMtto').click(function (){
            FinalizarMtto();
        });
        function redict(){
            patio = $('#patio_report').val();
            cargador = $('#cargador_report').val();
            self.location = "REPORTE DE FALLAS.php?maquinaria="+cargador+"&patio="+patio;
        }
    </script>
</body>
</html>