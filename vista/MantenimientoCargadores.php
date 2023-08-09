<?php
session_start();
require_once '../modelo/conexion.php';
//error_reporting(0);
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
}
/*$sql = "DELETE FROM tiempos_cargadores_actividad";
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
$res5 = sqlsrv_query($conn,$sql5);
$sql6 = "DELETE FROM ReporteFallasMaquinaria";
$res6 = sqlsrv_query($conn,$sql6);
//$sql7 = "DELETE FROM TarifaMaquinaria";
//$res7 = sqlsrv_query($conn,$sql7);*/
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=auto, initial-scale=0.7">
    <?php include './libreria.php'; ?>
    <script type="text/javascript" src="../controlador/mantenimiento.js"></script>
    <title></title>
    <style type="text/css">
        .grow:hover
        {
        -webkit-transform: scale(1.1);
        -ms-transform: scale(1.1);
        transform: scale(1.1);
        }
    </style>
</head>
<body>
    <?php include 'Header.php'; ?>
    <center><h3>Mantenimiento de Maquinaria</h3></center>
    <div class="container">
        <br>
        <?php 
        if ($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){
        ?>
            <a href="inicio.php"><button class="btn btn-default navbar-right" style="margin-right: 7px;"><span class="glyphicon glyphicon-home"></span></button> </a>
        <?php
        }
        if($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){
        ?>
            <a href="Admin.php"><button class="btn btn-default navbar-right" style="margin-right: 7px;"><span class="glyphicon glyphicon-home"></span></button> </a>
        <?php
        }
        ?>
        <br><br>
        <div class="row" id="div">
            <?php
            if ($_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){
                $sql = "SELECT detalle_equipos.idEquipos, detalle_equipos.modelo, detalle_equipos.serial_motor, detalle_equipos.serial_chasis, detalle_equipos.marca, detalle_equipos.placa, detalle_equipos.capacidad, detalle_equipos.peso_bruto, EquiposGrupos.Descripcion as DescripcionGrupo, detalle_equipos.horometro_final, detalle_equipos.horometro_vida_util, detalle_equipos.horometro_mantto, proveedores.RazonSocial, equipos.Identificacion
                    FROM detalle_equipos 
                    INNER JOIN equipos ON detalle_equipos.idEquipos= equipos.idEquipo
                    INNER JOIN proveedores ON Equipos.idPropietario=proveedores.idProveedor
                    INNER JOIN EquiposGrupos ON detalle_equipos.clase_equipo = EquiposGrupos.idGrupo
                    WHERE detalle_equipos.clase_equipo = '7A975CD6-2672-430D-B29E-7149A03D9410'";
            }else{
                $sql = "SELECT detalle_equipos.idEquipos, detalle_equipos.modelo, detalle_equipos.serial_motor, detalle_equipos.serial_chasis, 
                    detalle_equipos.marca, detalle_equipos.placa, detalle_equipos.capacidad, detalle_equipos.peso_bruto, 
                    EquiposGrupos.Descripcion as DescripcionGrupo, detalle_equipos.horometro_final, detalle_equipos.horometro_vida_util, 
                    detalle_equipos.horometro_mantto, proveedores.RazonSocial, equipos.Identificacion
                    FROM detalle_equipos 
                    INNER JOIN equipos ON detalle_equipos.idEquipos= equipos.idEquipo
                    INNER JOIN proveedores ON Equipos.idPropietario=proveedores.idProveedor
                    INNER JOIN EquiposGrupos ON detalle_equipos.clase_equipo = EquiposGrupos.idGrupo 
                    /*WHERE Equipos.idPropietario='4A442AA8-6532-4F4F-8CED-51A6999DDB5E' AND
                        detalle_equipos.clase_equipo = '7A975CD6-2672-430D-B29E-7149A03D9410'*/";
            }
            $r = sqlsrv_query($conn,$sql);
            while($eq = sqlsrv_fetch_array($r)){
                ?>
                <div class="col-xs-6  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                    <center><label class="grow"><?php echo $eq['modelo']." - ".$eq['Identificacion']; ?></label>
                        <?php 
                        $sql = "SELECT * FROM Registro_tique_cargadores WHERE id_maquinaria='". $eq['idEquipos'] ."' AND estado=1";
                        $params = array();
                        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                        $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
                        $rowss=sqlsrv_num_rows($resultado);
                        if ($rowss > 0){
                           $sql = "SELECT * FROM ReporteFallasMaquinaria WHERE id_maquinaria='". $eq['idEquipos'] ."' AND (id_jefeMantto IS NULL OR id_mecanico IS NULL)";
                            $params = array();
                            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                            $result=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
                            $rows=sqlsrv_num_rows($result);
                            if ($rows > 0){
                            ?>  <a href="REPORTE DE FALLAS.php?maquinaria=<?php echo $eq['idEquipos']; ?>">
                                    <button class="btn btn-warning btn-xs" title="Hay un reporte de falla"><img class="grow" src="../Imagenes/señal-alerta-png-4.png" width="13" height="14"></button>
                                </a>
                    <?php    }
                        }else{
                            $sql = "SELECT * FROM ReporteFallasMaquinaria WHERE id_maquinaria='". $eq['idEquipos'] ."' AND (id_jefeMantto IS NULL OR id_mecanico IS NULL)";
                            $params = array();
                            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                            $result=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
                            $rows=sqlsrv_num_rows($result);
                            if ($rows > 0){
                                while($metadata = sqlsrv_fetch_array($result)){
                                    $patio = $metadata['id_patio'];
                                }
                            ?>  <a href="REPORTE DE FALLAS.php?maquinaria=<?php echo $eq['idEquipos']; ?>&patio=<?php echo $patio; ?>">
                                    <button class="btn btn-warning btn-xs" title="Hay un reporte de falla"><img class="grow" src="../Imagenes/señal-alerta-png-4.png" width="13" height="14"></button>
                                </a>
                    <?php   }
                        }?>
                    <br>
                    <div >
                    <a href="Mantenimiento.php?id_maquinaria=<?php echo $eq['idEquipos']; ?>&modelo=<?php echo $eq['modelo']; ?>&serial=<?php echo $eq['Identificacion']; ?>"><img class="grow" src="../Imagenes/1.png" width="200" height="150">
                    </a>
                    </div>
                    </center>
                    <br><br>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</body>
</html>