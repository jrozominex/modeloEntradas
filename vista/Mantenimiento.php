<?php
session_start();
require_once '../modelo/conexion.php';
include('../modelo/security.php');
error_reporting(0);
if ($_SESSION["logueado"] == TRUE && $_SESSION["permisoIngresar"] != 'AUXILIAR_PATIO') {
    $usuario = $_SESSION['usuario'];
    $Fecha = date('Y-m-d');
    $Fecha_actual = date('Y-m-d',strtotime($Fecha . ' - 15 days'));
    ini_set('date.timezone', 'America/Bogota');
    $hora = date("g:i A");
    $sql_usuario = "SELECT * FROM Usuarios WHERE idUsuario='$usuario'";
    $result = sqlsrv_query($conn,$sql_usuario);
    while ($row = sqlsrv_fetch_array($result)){
      $Nombre = $row['NombreUsuarioLargo'];
      $password = $row['Password'];
    }
}elseif($_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO'){
    //header("Location: inicio_patio.php");
    ?>
    <script type="text/javascript">
        self.location='inicio_patio.php';
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
}/*
$sql = "DELETE FROM tiempos_cargadores_actividad";
$res = sqlsrv_query($conn,$sql);
$sql1 = "DELETE FROM horometro";
$res1 = sqlsrv_query($conn,$sql1);
$sql2 = "DELETE FROM Registro_tique_cargadores";
$res2 = sqlsrv_query($conn,$sql2);
$sql3 = "DELETE FROM horometro_descuento_cargadores";
$res3 = sqlsrv_query($conn,$sql3);
$sql4 = "DELETE FROM Mantenimiento_equipos";
$res4 = sqlsrv_query($conn,$sql4);
$sql5 = "DELETE FROM Mtto_plantilla_actividades";
$res5 = sqlsrv_query($conn,$sql5);*/
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=auto, initial-scale=0.8">
    <?php include './libreria.php'; ?>
    <script type="text/javascript" src="../controlador/mantenimiento.js"></script>
    <title></title>
</head>
<body onload="pepitojimenez()">
    <?php
    include 'Header.php';
    ?>
    <center><h3>Mantenimiento de Maquina <?php echo $_GET['modelo']." - ".$_GET['serial']; ?></h3></center>
    <div class="container">
        <br>
        <a href="<?php if ($_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES' || $_SESSION['permisoIngresar'] == 'MTTO_CARGADORES'){   echo 'MantenimientoCargadores.php';  }elseif($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){   echo 'Admin.php';  }else{   echo 'inicio.php';  } ?>"><button class="btn btn-default navbar-right" style="margin-right: 7px;"><span class="glyphicon glyphicon-home"></span></button> </a>
        <?php
        if ($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES' || $_SESSION['permisoIngresar'] == 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){
        ?>
            <button class="btn btn-success navbar-right" style="margin-right: 15px;" data-toggle="modal" data-target="#modalMantenimiento" onclick="maquina('<?php echo $_GET['id_maquinaria']; ?>')">Iniciar mantenimiento <span class="glyphicon glyphicon-plus"></span></button>
            <?php
        }
        ?><br><br>
            <center><button class="btn btn-default" id="r" onclick="cambiarDiv('r')">REPORTE FALLAS</button> &nbsp; <button id="m" class="btn btn-default" onclick="cambiarDiv('m')">MANTENIMIENTOS</button></center><br>
        <div class="table-responsive" id="divReporte">
            <table class="table table-hover table-condensed table-bordered table-responsive table-striped">
                <?php 
                $sql = "SELECT ReporteFallasMaquinaria.idReporteFalla, ReporteFallasMaquinaria.FechaRegistro, ReporteFallasMaquinaria.HoroReporte_inicial, 
                        ReporteFallasMaquinaria.HoroReporte_final, ReporteFallasMaquinaria.DescripcionFalla, ReporteFallasMaquinaria.Observaciones, 
                        ReporteFallasMaquinaria.FechaCierre, ReporteFallasMaquinaria.horo_reporte_falla, Usuarios.NombreUsuarioLargo as mecanico, Usuarios_1.NombreUsuarioLargo AS Jefe_mtto, 
                        Usuarios_2.NombreUsuarioLargo AS Operador, Equipos.Descripcion as maquina, Equipos.Identificacion, Destino.Descripcion AS Patio
                        FROM ReporteFallasMaquinaria LEFT OUTER JOIN
                        Usuarios ON ReporteFallasMaquinaria.id_mecanico = Usuarios.idUsuario LEFT OUTER JOIN
                        Usuarios AS Usuarios_1 ON ReporteFallasMaquinaria.id_jefeMantto = Usuarios_1.idUsuario INNER JOIN
                        Usuarios AS Usuarios_2 ON ReporteFallasMaquinaria.id_operador = Usuarios_2.idUsuario INNER JOIN
                        Equipos ON ReporteFallasMaquinaria.id_maquinaria = Equipos.idEquipo INNER JOIN
                        Destino ON ReporteFallasMaquinaria.id_patio = Destino.idDestino
                        WHERE (ReporteFallasMaquinaria.id_maquinaria = '". $_GET['id_maquinaria'] ."') AND AND (id_jefeMantto IS NOT NULL OR id_mecanico IS NOT NULL)";
                $params = array();
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
                $rows=sqlsrv_num_rows($resultado);
                if ($rows > 0){
                    ?>
                    <thead>
                        <th>Fecha Registro</th>
                        <th>Fecha cierre</th>
                        <th>Horo Reporte Falla</th>
                        <th>Operador</th>
                        <th>Horo inicial</th>
                        <th>Horo final</th>
                        <th>Maquina</th>
                        <th>Patio</th>
                        <th>Falla</th>
                        <th>Mecanico</th>
                        <th>Jefe Mantto</th>
                        <th>Observaciones</th>
                    </thead>
                    <?php
                    while($report = sqlsrv_fetch_array($res)){
                        ?>
                        <tr>
                            <td><?php echo date_format($report['FechaRegistro'],'Y-m-d H:i:s'); ?></td>
                            <td><?php echo $report['FechaCierre'] ?></td>
                            <td><?php echo $report['horo_reporte_falla']; ?></td>
                            <td><?php echo $report['Operador']; ?></td>
                            <td><?php echo $report['HoroReporte_inicial']; ?></td>
                            <td><?php echo $report['HoroReporte_final']; ?></td>
                            <td><?php echo $report['maquina']; ?></td>
                            <td><?php echo $report['Patio']; ?></td>
                            <td><?php echo $report['DescripcionFalla']; ?></td>
                            <td><?php echo $report['mecanico']; ?></td>
                            <td><?php echo $report['Jefe_mtto']; ?></td>
                            <td><?php echo $report['Observaciones']; ?></td>
                        </tr>
                        <?php
                    }
                }else{
                    echo "<br><br><center>No hay reportes de fallas realizados o finalizados</center>";
                }
                ?>
            </table>
        </div>
        <div class="table-responsive" id="divMantto">
            <?php 
            if ($_SESSION['permisoIngresar'] != 'OPERADOR_CARGADOR'){
                $sql = "SELECT Mantenimiento_equipos.realizado_por, Mantenimiento_equipos.verificado_por, Mantenimiento_equipos.aprobado_por, Mantenimiento_equipos.id_usuario, 
                                Mantenimiento_equipos.tipo_mtto, Mantenimiento_equipos.idxid, Mantenimiento_equipos.idEquipo, Mantenimiento_equipos.horo_mtto_inicial,
                                Mantenimiento_equipos.horo_mtto_final, Mantenimiento_equipos.total_horas, Mantenimiento_equipos.fecha_inicial_mtto, 
                                Mantenimiento_equipos.fecha_final_mtto, Mantenimiento_equipos.observaciones, Equipos.Descripcion, Equipos.Identificacion, 
                                Proveedores.RazonSocial, EquiposGrupos.Descripcion AS Categoria, Usuarios.NombreUsuarioLargo
                        FROM Mantenimiento_equipos INNER JOIN
                            Equipos ON Mantenimiento_equipos.idEquipo = Equipos.idEquipo INNER JOIN
                            detalle_equipos ON Mantenimiento_equipos.idEquipo = detalle_equipos.idEquipos INNER JOIN
                            Proveedores ON Equipos.idPropietario = Proveedores.idProveedor INNER JOIN
                            EquiposGrupos ON detalle_equipos.clase_equipo = EquiposGrupos.idGrupo INNER JOIN
                            Usuarios ON Mantenimiento_equipos.id_usuario = Usuarios.idUsuario
                        WHERE Mantenimiento_equipos.idEquipo='". $_GET['id_maquinaria'] ."' ORDER BY tipo_mtto";
            }else{
                $sql = "SELECT Mantenimiento_equipos.realizado_por, Mantenimiento_equipos.verificado_por, Mantenimiento_equipos.aprobado_por, 
                                Mantenimiento_equipos.id_usuario, Mantenimiento_equipos.tipo_mtto, Mantenimiento_equipos.idxid, Mantenimiento_equipos.idEquipo, 
                                Mantenimiento_equipos.horo_mtto_inicial, Mantenimiento_equipos.horo_mtto_final, Mantenimiento_equipos.total_horas, 
                                Mantenimiento_equipos.fecha_inicial_mtto, Mantenimiento_equipos.fecha_final_mtto, Mantenimiento_equipos.observaciones, 
                                Equipos.Descripcion, Equipos.Identificacion, Proveedores.RazonSocial, EquiposGrupos.Descripcion AS Categoria, Usuarios.NombreUsuarioLargo
                        FROM Mantenimiento_equipos INNER JOIN
                            Equipos ON Mantenimiento_equipos.idEquipo = Equipos.idEquipo INNER JOIN
                            detalle_equipos ON Mantenimiento_equipos.idEquipo = detalle_equipos.idEquipos INNER JOIN
                            Proveedores ON Equipos.idPropietario = Proveedores.idProveedor INNER JOIN
                            EquiposGrupos ON detalle_equipos.clase_equipo = EquiposGrupos.idGrupo INNER JOIN
                            Usuarios ON Mantenimiento_equipos.id_usuario = Usuarios.idUsuario WHERE Mantenimiento_equipos.idEquipo='". $_GET['id_maquinaria'] ."' ORDER BY tipo_mtto";
            }
            $params = array();
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
            $rows=sqlsrv_num_rows($resultado);
            if ($rows > 0){
                ?>
                <table class="table table-hover table-condensed table-bordered table-responsive table-striped">
                    <thead>
                        <tr>
                            <!--<th>Maquinaria</th>-->
                            <th>fecha inicio</th>
                            <th>fecha final</th>
                            <th>horo inicial</th>
                            <th>Horo final</th>
                            <th>total horas</th>
                            <th>Tipo Mtto.</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <?php
                    while($data = sqlsrv_fetch_array($resultado)){
                        $valores1 = $data['idxid']."||".
                                    date_format($data['fecha_inicial_mtto'],'Y-m-d H:i:s')."||".
                                    $data['horo_mtto_inicial']."||".
                                    $data['Descripcion']."||".
                                    $data['Identificacion']."||".
                                    $data['RazonSocial']."||".
                                    $data['Categoria']."||".
                                    $data['NombreUsuarioLargo']."||".
                                    //$datos['id_registro']."||".
                                    $data['idEquipo'];
                    ?>
                        <tr>
                            <!--<td><?php echo $data['Descripcion']." - ".$data['Identificacion']; ?></td>-->
                            <td><?php echo date_format($data['fecha_inicial_mtto'],'Y-m-d H:i:s'); ?></td>
                            <td><?php echo date_format($data['fecha_final_mtto'],'Y-m-d H:i:s'); ?></td>
                            <td><?php echo $data['horo_mtto_inicial']; ?></td>
                            <td><?php echo $data['horo_mtto_final']; ?></td>
                            <td><?php echo $data['total_horas']; ?></td>
                            <td>
                                <?php 
                                if ($data['tipo_mtto'] == 1){
                                    echo "Diario";
                                }elseif($data['tipo_mtto'] == 2){
                                    echo "General";
                                }elseif($data['tipo_mtto'] == 3){
                                    echo "Lavado";
                                }elseif($data['tipo_mtto'] == 4){
                                    echo "Sistema Electrico";
                                }
                                ?>
                            </td>
                            <td>
                                <center>
                                <?php 
                                if ($data['id_usuario'] == $usuario){
                                    if ($data['horo_mtto_final'] != 0){
                                        $sql1 = "SELECT * FROM Mtto_plantilla_actividades WHERE id_mtto_equipo='".$data['idxid']."'";
                                        $params1 = array();
                                        $options1 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                                        $resultado1=sqlsrv_query($conn,utf8_decode($sql1),$params1,$options1);
                                        $rows1=sqlsrv_num_rows($resultado1);
                                        if ($rows1 > 0){
                                            if ($_SESSION['permisoIngresar'] != 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA'){
                                                ?>
                                                <a href="<?php if($_SESSION['permisoIngresar'] != 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] != 'CONSULTAS_OFICINA'){ echo 'PM 250hrs Sistema Electrico_.php?id_mantto='.$data['idxid']; } ?>">
                                                    <button type="button" class="btn btn-default btn-xs" title="Finalizado"><span class="glyphicon glyphicon-ok"></span></button>
                                                </a>
                                                <?php
                                            }else{
                                             if($data['verificado_por'] == null){
                                                ?>
                                                <a href="<?php if($_SESSION['permisoIngresar'] != 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] != 'CONSULTAS_OFICINA'){ echo 'PM 250hrs Sistema Electrico_.php?id_mantto='.$data['idxid'];}?>">
                                                    <button type="button" class="btn btn-success btn-xs">Verificar</button>
                                                </a>
                                            <?php
                                             }else{
                                                ?>
                                                <a href="<?php if($_SESSION['permisoIngresar'] != 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] != 'CONSULTAS_OFICINA'){ echo 'PM 250hrs Sistema Electrico_.php?id_mantto='.$data['idxid'];}?>">
                                                    <button type="button" class="btn btn-default btn-xs" title="Finalizado"><span class="glyphicon glyphicon-ok"></span></button>
                                                </a>
                                                <?php
                                             }
                                            }
                                        }else{
                                            if ($_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA'){
                                                ?>
                                                <a href="<?php if($_SESSION['permisoIngresar'] != 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] != 'CONSULTAS_OFICINA'){ echo 'PM 250hrs Sistema Electrico_.php?id_mantto='.$data['idxid']; }?>">
                                                    <button type="button" class="btn btn-warning btn-xs">Revisar</button>
                                                </a>
                                            <?php
                                            }else{
                                                if ($data['tipo_mtto'] == 1){
                                                    ?>
                                                    <a href="<?php if($_SESSION['permisoIngresar'] != 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] != 'CONSULTAS_OFICINA'){ echo 'PM 250hrs Sistema Electrico_.php?id_mantto='.$data['idxid']; }?>">
                                                        <button type="button" class="btn btn-warning btn-xs">Revisar</button>
                                                    </a>
                                                    <?php
                                                }else{
                                                    echo "Sin finalizar";
                                                }
                                            }
                                        }
                                    }else{
                                        ?>
                                        <button class="btn btn-danger btn-xs" title="Hay un mantenimiento en proceso" data-toggle="modal" data-target="<?php if($_SESSION['permisoIngresar'] != 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] != 'CONSULTAS_OFICINA'){ echo '#modalFinalizarMtto'; } ?>" onclick="VerMtto('<?php echo $valores1; ?>')">
                                            <span class="glyphicon glyphicon-wrench"></span>
                                        </button>
                                        <?php
                                    }
                                }elseif ($_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA'){
                                    if ($data['horo_mtto_final'] != 0){
                                        $sql1 = "SELECT * FROM Mtto_plantilla_actividades WHERE id_mtto_equipo='".$data['idxid']."'";
                                        $params1 = array();
                                        $options1 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                                        $resultado1=sqlsrv_query($conn,utf8_decode($sql1),$params1,$options1);
                                        $rows1=sqlsrv_num_rows($resultado1);
                                        if ($rows1 > 0){
                                            ?>
                                            <a href="<?php if($_SESSION['permisoIngresar'] != 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] != 'CONSULTAS_OFICINA'){ echo 'PM 250hrs Sistema Electrico_.php?id_mantto='.$data['idxid']; }?>">
                                                <button type="button" class="btn btn-default btn-xs" title="Finalizado"><span class="glyphicon glyphicon-ok"></span></button>
                                            </a>
                                            <?php
                                        }else{
                                            if ($_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA'){
                                                ?>
                                                <a href="<?php if($_SESSION['permisoIngresar'] != 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] != 'CONSULTAS_OFICINA'){ echo 'PM 250hrs Sistema Electrico_.php?id_mantto='.$data['idxid']; }?>">
                                                    <button type="button" class="btn btn-warning btn-xs">Revisar</button>
                                                </a>
                                            <?php
                                            }else{
                                                echo "Sin finalizar";
                                            }
                                        }
                                    }else{
                                        echo "No ha terminado el mantenimiento";
                                    }
                                }elseif ($_SESSION['permisoIngresar'] == 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA'){
                                    if ($data['realizado_por'] != NULL){
                                        if ($data['verificado_por'] == NULL){
                                        ?>
                                            <a href="<?php if($_SESSION['permisoIngresar'] != 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] != 'CONSULTAS_OFICINA'){ echo 'PM 250hrs Sistema Electrico_.php?id_mantto='.$data['idxid']; }?>">
                                                <button type="button" class="btn btn-success btn-xs">Verificar</button>
                                            </a>
                                        <?php
                                            }else{
                                                ?>
                                                <a href="<?php if($_SESSION['permisoIngresar'] != 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] != 'CONSULTAS_OFICINA'){ echo 'PM 250hrs Sistema Electrico_.php?id_mantto='.$data['idxid']; }?>">
                                                    <button type="button" class="btn btn-default btn-xs" title="Finalizado"><span class="glyphicon glyphicon-ok"></span></button>
                                                </a>
                                            <?php
                                        }
                                    }else{
                                        echo "Sin finalizar";
                                    }
                                }elseif ($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA'){
                                    if ($data['realizado_por'] != NULL){
                                        if ($data['verificado_por'] != NULL){
                                            if ($data['aprobado_por'] == NULL){
                                            ?>
                                            <a href="<?php if($_SESSION['permisoIngresar'] != 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] != 'CONSULTAS_OFICINA'){ echo 'PM 250hrs Sistema Electrico_.php?id_mantto='.$data['idxid']; }?>">
                                                <button type="button" class="btn btn-success btn-xs">Aprobar</button>
                                            </a>
                                        <?php
                                            }else{
                                                ?>
                                                <a href="<?php if($_SESSION['permisoIngresar'] != 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] != 'CONSULTAS_OFICINA'){ echo 'PM 250hrs Sistema Electrico_.php?id_mantto='.$data['idxid']; }?>">
                                                    <button type="button" class="btn btn-default btn-xs" title="Finalizado"><span class="glyphicon glyphicon-ok"></span></button>
                                                </a>
                                                <?php
                                            }
                                        }else{
                                            echo "Sin verificar";
                                        }
                                    }else{
                                        if ($data['tipo_mtto'] == 1){
                                            ?>
                                            <a href="<?php if($_SESSION['permisoIngresar'] != 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] != 'CONSULTAS_OFICINA'){ echo 'PM 250hrs Sistema Electrico_.php?id_mantto='.$data['idxid']; }?>">
                                                <button type="button" class="btn btn-warning btn-xs">Revisar</button>
                                            </a>
                                            <?php
                                        }else{
                                            echo "Sin finalizar";
                                        }
                                    }
                                }
                                ?>
                                </center>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                    
                </table>
                <?php
            }else{
                echo "<br><br>";
                echo "<center>No hay mantenimientos registrados por el momento</center>";
            }
            ?>
        </div>
    </div>
    <div class="modal fade" id="modalMantenimiento" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Iniciar mantenimiento</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idUsuario" value="<?php echo $usuario; ?>">
                    <div class="row form-group center-block">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <label>Hora inicio actividad</label>
                            <input type="text" id="FechaHora_ini" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-2">
                            <!--<label>Maquinaria</label>
                            <select class="form-control" id="maquinaria" onchange="Tipo_maquina()">
                                <option selected="true">--- Seleccione ---</option>
                                <?php 
                                $sql = "SELECT * FROM EquiposGrupos";
                                $res = sqlsrv_query($sql);
                                while($tipo = sqlsrv_fetch_array($res)){
                                    ?>
                                    <option value="1"><?php echo $tipo['Descripcion']; ?></option>
                                    <?php
                                }
                                ?>
                                <option value="1">Cargadores</option>
                                <option value="2">Power Screen</option>
                                <option value="3">Trituradora</option>
                                <option value="4">Clasificadora</option>
                            </select>-->
                        </div>
                        <div class="col-sm-4" id="select_cargador">
                            <label>Cargador</label>
                            <input type="hidden" id="cargador" value="<?php echo $_GET['id_maquinaria']; ?>">
                            <input type="text" id="cargador1" class="form-control" value="<?php echo $_GET['modelo'].'- '.$_GET['serial']; ?>" readonly>
                            <!--<select class="form-control" id="cargador" onchange="maquina()">
                                <option selected="true">--- Seleccione ---</option>
                                <?php 
                                $sql = "SELECT * FROM Equipos WHERE idEquipo = '". $_GET['id_maquinaria'] ."'";
                                $result = sqlsrv_query($conn,$sql);
                                while ($cargador = sqlsrv_fetch_array($result)) {
                                    ?>
                                    <option value="<?php echo $cargador['idEquipo']; ?>"><?php echo utf8_encode($cargador['Descripcion']." - ".$cargador['Identificacion']); ?></option>
                                    <?php
                                }
                                ?>
                            </select>-->
                        </div>
                        <div class="col-sm-4" id="div_horo_inicial">
                            <label>Horometro inicial</label>
                            <input type="text" id="horometro_inicial" class="form-control" readonly="" placeholder="Seleccione maquina">
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4" id="div_life_machine">
                            <label> Vida Maquina</label>
                            <input type="text" id="horometro_vida" class="form-control" readonly="" placeholder="Seleccione maquina">
                        </div>
                        <div class="col-sm-4" id="div_mantto">
                            <label> Mantenimiento</label>
                            <input type="text" id="horometro_mantto" class="form-control" readonly="" placeholder="Seleccione maquina">
                        </div>
                    </div>
                    <div class="row form-group center-block" style="background-color: powderblue;" id="title"><center><h4>Mantenimientos</h4></center></div>
                    <div class="row form-group center-block" id="ActMantto">
                        <div class="col-xs-4 col-sm-4"></div>
                        <div class="col-xs-6 col-sm-6">
                            <input type="hidden" id="TipoMantto">
                            <form name="form3">
                                <?php
                                if ($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES'){    
                                    ?>
                                    <input type="checkbox" id="1" value="1"> Eventual <br>
                                    <input type="checkbox" id="2" value="2"> General <br>
                                    <input type="checkbox" id="3" value="3"> Lavado Programado <br>
                                    <input type="checkbox" id="4" value="4"> Sistema Electrico Progamado<br>
                                    <?PHP
                                }else{
                                    if ($_SESSION['permisoIngresar'] != 'OPERADOR_CARGADOR'){
                                        ?>
                                        <input type="checkbox" id="2" value="2"> General <br>
                                        <input type="checkbox" id="3" value="3"> Lavado Programado <br>
                                        <input type="checkbox" id="4" value="4"> Sistema Electrico Progamado<br>
                                        <?PHP
                                    }else{
                                        ?>
                                        <input type="checkbox" id="1" value="1"> Eventual <br>
                                        <?php
                                    }
                                }
                                ?>
                            </form>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" onclick="AggMantenimiento()" id="AgregarActividad">
                        Iniciar Mantenimiento
                    </button>
                </div>
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
    <script>
        function pepitojimenez(){
            if($('#abcd').val() == ' 17:43:0'){
                alert('La hora llegó');
            }
        var fecha= new Date();
        var m = moment(); 
        var horas= fecha.getHours();
        var minutos = fecha.getMinutes();
        var segundos = fecha.getSeconds();
        document.getElementById('FechaHora_ini').value=''+'<?php echo $Fecha = date('Y-m-d'); ?>'+' '+horas+':'+minutos+':'+segundos+'';
        document.getElementById('fechaMtto').value=''+'<?php echo $Fecha = date('Y-m-d'); ?>'+' '+horas+':'+minutos+':'+segundos+'';
        setTimeout('pepitojimenez()',1000);
        }
        $('#FinalizarMtto').click(function (){
            FinalizarMtto();
        });

        function redireccionar(){
            self.location = "mantenimiento.php?id_maquinaria=<?php echo $_GET['id_maquinaria']; ?>&modelo=<?php echo $_GET['modelo']; ?>&serial=<?php echo $_GET['serial']; ?>";
        }
    </script>
</body>
</html>