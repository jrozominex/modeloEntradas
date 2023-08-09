<?php
session_start();
require_once '../modelo/conexion.php';
//include('../modelo/security.php');
error_reporting(0);
if ($_SESSION["logueado"] == TRUE && ($_SESSION["permisoIngresar"] != 'MECANICO_CARGADORES' || $_SESSION['permisoIngresar'] != 'MTTO_CARGADORES' || $_SESSION['OPERADOR_CARGADOR'])){
    $usuario = $_SESSION['usuario'];
    $Fecha = date('Y-m-d H:i:s');
    $Fecha_ant = date('Y-m-d',strtotime($Fecha . ' - 7 days'));
    $Year = date('Y')-1;
    $Fecha_actual = date('Y-m-d',strtotime($Fecha . ' - 15 days'));
    ini_set('date.timezone', 'America/Bogota');
    $hora = date("g:i A");
    $sql_usuario = "SELECT * FROM Usuarios WHERE idUsuario='$usuario'";
    $result = sqlsrv_query($conn,$sql_usuario);
    while ($row = sqlsrv_fetch_array($result)){
      $Nombre = $row['NombreUsuarioLargo'];
      $password = $row['Password'];
    }
}elseif($_SESSION['permisoIngresar'] == 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES'){
    //header("Location: MantenimientoCargadores.php");
    ?>
    <script type="text/javascript">
        self.location='MantenimientoCargadores.php';
        alert('No tienes permiso para acceder a este ambiente');
    </script>
    <?php
}elseif($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){
    //header("Location: inicio.php");
    ?>
    <script type="text/javascript">
        self.location='inicio.php';
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
    <?php include './libreria.php'; ?>
    <meta name="viewport" content="width=auto, initial-scale=0.7">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="../controlador/patio.js"></script>
    <script type="text/javascript" src="../controlador/patio_editar.js"></script>
    <title></title>
    <style type="text/css">
/*#contenedor {
    width:200px;
        height:200px;
}*/
.loader {
  font-size: 20px;
  margin: 45% auto;
  width: 1em;
  height: 1em;
  border-radius: 50%;
  position: center;
  text-indent: -9999em;
  -webkit-animation: load4 1.3s infinite linear;
  animation: load4 1.3s infinite linear;
}
@-webkit-keyframes load4 {
  0%,
  100% {
    box-shadow: 0em -3em 0em 0.2em #DFB12D, 2em -2em 0 0em #DFB12D, 3em 0em 0 -0.5em #DFB12D, 2em 2em 0 -0.5em #DFB12D, 0em 3em 0 -0.5em #DFB12D, -2em 2em 0 -0.5em #DFB12D, -3em 0em 0 -0.5em #DFB12D, -2em -2em 0 0em #DFB12D;
  }
  12.5% {
    box-shadow: 0em -3em 0em 0em #ffffff, 2em -2em 0 0.2em #ffffff, 3em 0em 0 0em #ffffff, 2em 2em 0 -0.5em #ffffff, 0em 3em 0 -0.5em #ffffff, -2em 2em 0 -0.5em #ffffff, -3em 0em 0 -0.5em #ffffff, -2em -2em 0 -0.5em #ffffff;
  }
  25% {
    box-shadow: 0em -3em 0em -0.5em #ffffff, 2em -2em 0 0em #ffffff, 3em 0em 0 0.2em #ffffff, 2em 2em 0 0em #ffffff, 0em 3em 0 -0.5em #ffffff, -2em 2em 0 -0.5em #ffffff, -3em 0em 0 -0.5em #ffffff, -2em -2em 0 -0.5em #ffffff;
  }
  37.5% {
    box-shadow: 0em -3em 0em -0.5em #ffffff, 2em -2em 0 -0.5em #ffffff, 3em 0em 0 0em #ffffff, 2em 2em 0 0.2em #ffffff, 0em 3em 0 0em #ffffff, -2em 2em 0 -0.5em #ffffff, -3em 0em 0 -0.5em #ffffff, -2em -2em 0 -0.5em #ffffff;
  }
  50% {
    box-shadow: 0em -3em 0em -0.5em #ffffff, 2em -2em 0 -0.5em #ffffff, 3em 0em 0 -0.5em #ffffff, 2em 2em 0 0em #ffffff, 0em 3em 0 0.2em #ffffff, -2em 2em 0 0em #ffffff, -3em 0em 0 -0.5em #ffffff, -2em -2em 0 -0.5em #ffffff;
  }
  62.5% {
    box-shadow: 0em -3em 0em -0.5em #ffffff, 2em -2em 0 -0.5em #ffffff, 3em 0em 0 -0.5em #ffffff, 2em 2em 0 -0.5em #ffffff, 0em 3em 0 0em #ffffff, -2em 2em 0 0.2em #ffffff, -3em 0em 0 0em #ffffff, -2em -2em 0 -0.5em #ffffff;
  }
  75% {
    box-shadow: 0em -3em 0em -0.5em #ffffff, 2em -2em 0 -0.5em #ffffff, 3em 0em 0 -0.5em #ffffff, 2em 2em 0 -0.5em #ffffff, 0em 3em 0 -0.5em #ffffff, -2em 2em 0 0em #ffffff, -3em 0em 0 0.2em #ffffff, -2em -2em 0 0em #ffffff;
  }
  87.5% {
    box-shadow: 0em -3em 0em 0em #ffffff, 2em -2em 0 -0.5em #ffffff, 3em 0em 0 -0.5em #ffffff, 2em 2em 0 -0.5em #ffffff, 0em 3em 0 -0.5em #ffffff, -2em 2em 0 0em #ffffff, -3em 0em 0 0em #ffffff, -2em -2em 0 0.2em #ffffff;
  }
}
@keyframes load4 {
  0%,
  100% {
    box-shadow: 0em -3em 0em 0.2em #DFB12D, 2em -2em 0 0em #DFB12D, 3em 0em 0 -0.5em #DFB12D, 2em 2em 0 -0.5em #DFB12D, 0em 3em 0 -0.5em #DFB12D, -2em 2em 0 -0.5em #DFB12D, -3em 0em 0 -0.5em #DFB12D, -2em -2em 0 0em #DFB12D;
  }
  12.5% {
    box-shadow: 0em -3em 0em 0em #DF932D, 2em -2em 0 0.2em #DF932D, 3em 0em 0 0em #DF932D, 2em 2em 0 -0.5em #DF932D, 0em 3em 0 -0.5em #DF932D, -2em 2em 0 -0.5em #DF932D, -3em 0em 0 -0.5em #DF932D, -2em -2em 0 -0.5em #DF932D;
  }
  25% {
    box-shadow: 0em -3em 0em -0.5em #DF632D, 2em -2em 0 0em #DF632D, 3em 0em 0 0.2em #DF632D, 2em 2em 0 0em #DF632D, 0em 3em 0 -0.5em #DF632D, -2em 2em 0 -0.5em #DF632D, -3em 0em 0 -0.5em #DF632D, -2em -2em 0 -0.5em #DF632D;
  }
  37.5% {
    box-shadow: 0em -3em 0em -0.5em #DF502D, 2em -2em 0 -0.5em #DF502D, 3em 0em 0 0em #DF502D, 2em 2em 0 0.2em #DF502D, 0em 3em 0 0em #DF502D, -2em 2em 0 -0.5em #DF502D, -3em 0em 0 -0.5em #DF502D, -2em -2em 0 -0.5em #DF502D;
  }
  50% {
    box-shadow: 0em -3em 0em -0.5em #DF632D, 2em -2em 0 -0.5em #DF632D, 3em 0em 0 -0.5em #DF632D, 2em 2em 0 0em #DF632D, 0em 3em 0 0.2em #DF632D, -2em 2em 0 0em #DF632D, -3em 0em 0 -0.5em #DF632D, -2em -2em 0 -0.5em #DF632D;
  }
  62.5% {
    box-shadow: 0em -3em 0em -0.5em #DF932D, 2em -2em 0 -0.5em #DF932D, 3em 0em 0 -0.5em #DF932D, 2em 2em 0 -0.5em #DF932D, 0em 3em 0 0em #DF932D, -2em 2em 0 0.2em #DF932D, -3em 0em 0 0em #DF932D, -2em -2em 0 -0.5em #DF932D;
  }
  75% {
    box-shadow: 0em -3em 0em -0.5em #DFB12D, 2em -2em 0 -0.5em #DFB12D, 3em 0em 0 -0.5em #DFB12D, 2em 2em 0 -0.5em #DFB12D, 0em 3em 0 -0.5em #DFB12D, -2em 2em 0 0em #DFB12D, -3em 0em 0 0.2em #DFB12D, -2em -2em 0 0em #DFB12D;
  }
  87.5% {
    box-shadow: 0em -3em 0em 0em #DFC42D, 2em -2em 0 -0.5em #DFC42D, 3em 0em 0 -0.5em #DFC42D, 2em 2em 0 -0.5em #DFC42D, 0em 3em 0 -0.5em #DFC42D, -2em 2em 0 0em #DFC42D, -3em 0em 0 0em #DFC42D, -2em -2em 0 0.2em #DFC42D;
  }
}
    </style>
</head>
<body>
    <?php
    include 'Header.php';
    ?>
    <div class="container-fluid">
    <?php
    /*$num = 1;
    $sql1 = "SELECT Destino.Descripcion, DestinoZonas.Zona FROM DestinoZonas
    INNER JOIN Destino ON DestinoZonas.idDestino = Destino.idDestino
    ORDER BY Destino.Descripcion";
    $r = sqlsrv_query($conn,$sql1);
    while($a = sqlsrv_fetch_array($r)){
        echo $num.'. <b>'.utf8_encode($a['Descripcion']).'</b> - '.utf8_encode($a['Zona']).'<br>';
        $num++;
    }
    $num = 0;*/
    ?>
    <!--<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d15802.053075812042!2d-72.60327794999999!3d8.049009349999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2sco!4v1566934162749!5m2!1ses-419!2sco" width="800" height="600" frameborder="0" style="border:3" allowfullscreen=""></iframe>-->
    <!--<a href="https://goo.gl/maps/eMSX1Lnm66uhoE3a7">Aca</a>-->
        <!-- TIQUETES ABIERTOS -->
        <!-- TIQUETES ABIERTOS -->
        <?php //|| $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES' $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA' || 
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
                     WHERE Equipos.idPropietario = '4A442AA8-6532-4F4F-8CED-51A6999DDB5E' and Registro_tique_cargadores.estado='1'
                    ORDER BY Registro_tique_cargadores.cod_reporte DESC";
            }elseif ($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA'){
            $sql = "SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                         Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado,
                         Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                         Usuarios.NombreUsuarioLargo, Registro_tique_cargadores.remision
                    FROM Registro_tique_cargadores INNER JOIN
                         Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                         Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                         Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                         Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
                    WHERE Registro_tique_cargadores.estado='1' /*and (CAST(Registro_tique_cargadores.fecha_apertura_tique as date) >= '$Fecha_ant' or Registro_tique_cargadores.estado='1') */
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
              $sql = "SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                       Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado,
                       Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                       Usuarios.NombreUsuarioLargo, Registro_tique_cargadores.remision
                  FROM Registro_tique_cargadores INNER JOIN
                       Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                       Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                       Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                       Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
                  WHERE Registro_tique_cargadores.estado='1' AND Registro_tique_cargadores.id_proveedor in ('$empresas') AND 
                  (CAST(Registro_tique_cargadores.fecha_ini_jornada AS date) >= '$Fecha_ant' or Registro_tique_cargadores.estado='1') AND
                  Registro_tique_cargadores.id_usuario='$usuario'
                       ORDER BY Registro_tique_cargadores.cod_reporte DESC";
            }else{
              $sql = "SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                       Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado,
                       Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                       Usuarios.NombreUsuarioLargo, Registro_tique_cargadores.remision
                  FROM Registro_tique_cargadores INNER JOIN
                       Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                       Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                       Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                       Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
                  WHERE Registro_tique_cargadores.estado='1' AND 
                  (CAST(Registro_tique_cargadores.fecha_ini_jornada AS date) >= '$Fecha_ant' or Registro_tique_cargadores.estado='1') AND
                  Registro_tique_cargadores.id_usuario='$usuario'
                       ORDER BY Registro_tique_cargadores.cod_reporte DESC";
            }
        }
            $params = array();
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
            $rows=sqlsrv_num_rows($resultado);
            if($rows > 0) 
            {   ?>
                <center><h4 style="color: #87C84C;">Tiquetes abiertos</h4></center>
                <div class="table-responsive">
                <table id="example2" class="table table-hover table-bordered">
                    <!--<thead>
                        <tr>
                            <th style="width: 7%; vertical-align: middle;"><center>N° tiquete</center></th>
                            <th style="width: 8%; vertical-align: middle;"><center>Cliente</center></th>
                            <th style="width: 10%; vertical-align: middle;"><center>Operario</center></th>
                            <th style="width: 15%; vertical-align: middle;"><center>Lugar</center></th>
                            <th style="width: 9%; vertical-align: middle;"><center>Cargador</center></th>
                            <th style="width: 8%; vertical-align: middle;"><center>Jornada Inicial</center></th>
                            <th style="width: 8%; vertical-align: middle;"><center>Jornada Final</center></th>
                            <th style="width: 4%; vertical-align: middle;"><center>Hrs.</center></th>
                            <th style="width: 9%; vertical-align: middle;"><center>Estado</center></th>
                            <th><center>Fecha Distribución</center></th>
                            <th style="width: 20%; vertical-align: middle;"><center>Acciones</center></th>
                        </tr>
                    </thead>-->
                    <thead>
                        <tr>
                            <th style="width: 7%; vertical-align: middle;"><center>N° tiquete</center></th>
                            <th style="width: 8%; vertical-align: middle;"><center>Cliente</center></th>
                            <th style="width: 10%; vertical-align: middle;"><center>Operario</center></th>
                            <th style="width: 15%; vertical-align: middle;"><center>Lugar</center></th>
                            <th style="width: 9%; vertical-align: middle;"><center>Cargador</center></th>
                            <th style="width: 10%; vertical-align: middle;"><center>Jornada Inicial</center></th>
                            <th style="width: 10%; vertical-align: middle;"><center>Jornada Final</center></th>
                            <th style="width: 4%; vertical-align: middle;"><center>Hrs.</center></th>
                            <th style="width: 10%; vertical-align: middle;"><center>Estado</center></th>
                            <th><center>Fecha Distribución</center></th>
                            <th style=" <?php if ($_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){?>  width: 20%; <?php }else{ ?>  width: 10%; <?php } ?> vertical-align: middle;">
                                <center>Acciones</center>
                            </th>
                        </tr>
                    </thead>
                    <?php
                    $count_horas = 0;
                    while ($datos = sqlsrv_fetch_array($resultado)) {
                        $count_horas += $datos['horas_trabajadas'];
                        $datos_registro = $datos['id_registro']. "||" .
                                          $datos['cod_reporte']. "||" .
                                          date_format($datos['fecha_apertura_tique'],'Y-m-d  H:i:s'). "||" .
                                          utf8_encode($datos['Descripcion']). "||" .
                                          utf8_encode($datos['NombreCargador']). "||" .
                                          date_format($datos['fecha_ini_jornada'],'Y-m-d  H:i:s'). "||" .
                                          utf8_encode($datos['NombreCorto']);
                        ?>
                        <tr>
                            <td><center><?php echo $datos['cod_reporte']; ?></center></td>
                            <td><?php echo utf8_encode($datos['NombreCorto']); ?></td>
                            <td><?php echo utf8_encode($datos['NombreUsuarioLargo']); ?></td>
                            <td><?php echo $datos['Descripcion']; ?></td>
                            <td><?php echo $datos['NombreCargador']." - ".$datos['Identificacion'] ; ?></td>
                            <td><?php echo date_format($datos['fecha_ini_jornada'], 'M-d  h:i'); ?></td>
                            <td>
                                <?php 
                                if ($datos['fecha_fin_jornada'] == 0){
                                    echo "<center>"." ---------------"."</center>";
                                }else{
                                    echo date_format($datos['fecha_fin_jornada'], 'M-d  h:i');
                                }
                                ?>
                            </td>
                            <td><?php echo number_format($datos['horas_trabajadas'],1,',','.'); ?></td>
                            <td style="background-color: #E15A5A;">
                                <center><b>Activo</b></center>
                            </td>
                            <td>----------------- </td>
                            <td>
                                <?php 
                                if ($datos['fecha_fin_jornada'] == 0){
                                    ?>
                                    <center>
                                        <?php 
                                        if ($datos['estado'] == 2){
                                        ?>  <button title="finalizado por el usuario" class="btn btn-success">
                                                <span class="glyphicon glyphicon-user"></span>
                                            </button>
                                        <?php } ?>
                                        <a href="horometros.php?idTiquete=<?php echo $datos['id_registro']; ?>&Reporte=<?php echo $datos['cod_reporte']; ?>">
                                            <button class="btn btn-default" title="Ver actividades">
                                                <span class="glyphicon glyphicon-eye-open"></span>
                                            </button>
                                        </a>
                                        <!--<a title="Informe PDF" href="informe_horometros-pdf.php?idTiquete=<?php echo $datos['id_registro']; ?>" target="_blank"><img src="../Imagenes/pdf-solictud.png" width="30" height="40"/></a>-->
                                    </center>
                                <?php
                                }else{
                                    ?>
                                    <center>
                                        
                                    </center>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
              </div>
            <?php
            //echo $count_horas;
            }else{
                echo "<br><br>";
                echo "<center>No hay tiquetes iniciados por el operario</center><br><br><br>";
            }?>
        <!-- TIQUETES CERRADOS -->
        <!-- TIQUETES CERRADOS -->
        <?php //|| $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'
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
                     WHERE Equipos.idPropietario = '4A442AA8-6532-4F4F-8CED-51A6999DDB5E' and Registro_tique_cargadores.estado<>'1'
                    ORDER BY Registro_tique_cargadores.cod_reporte DESC";
            }elseif ($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA'){
            $sql = "SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                         Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado,
                         Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                         Usuarios.NombreUsuarioLargo, Registro_tique_cargadores.remision, Registro_tique_cargadores.horometro_ini, Registro_tique_cargadores.horometro_fin
                    FROM Registro_tique_cargadores INNER JOIN
                         Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                         Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                         Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                         Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
                    WHERE Registro_tique_cargadores.estado='2' or Registro_tique_cargadores.estado='3' or Registro_tique_cargadores.estado='4' /*and (CAST(Registro_tique_cargadores.fecha_apertura_tique as date) >= '$Fecha_ant' or Registro_tique_cargadores.estado='2')*/
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
              $sql = "SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                           Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado,
                           Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                           Usuarios.NombreUsuarioLargo, Registro_tique_cargadores.remision, Registro_tique_cargadores.horometro_ini, Registro_tique_cargadores.horometro_fin
                      FROM Registro_tique_cargadores INNER JOIN
                           Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                           Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                           Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                           Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
                      WHERE /*(CAST(Registro_tique_cargadores.fecha_ini_jornada AS date) >= '$Fecha_ant' or Registro_tique_cargadores.estado='2') AND*/
                      Registro_tique_cargadores.id_proveedor in ('$empresas') AND (Registro_tique_cargadores.estado='2' or Registro_tique_cargadores.estado='3' or Registro_tique_cargadores.estado='4')
                      AND Registro_tique_cargadores.id_usuario='$usuario'
                      ORDER BY Registro_tique_cargadores.cod_reporte DESC";
            }else{
              $sql = "SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                           Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado,
                           Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                           Usuarios.NombreUsuarioLargo, Registro_tique_cargadores.remision, Registro_tique_cargadores.horometro_ini, Registro_tique_cargadores.horometro_fin
                      FROM Registro_tique_cargadores INNER JOIN
                           Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                           Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                           Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                           Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
                      WHERE (Registro_tique_cargadores.estado='2' or Registro_tique_cargadores.estado='3' or Registro_tique_cargadores.estado='4')
                      AND Registro_tique_cargadores.id_usuario='$usuario'
                      ORDER BY Registro_tique_cargadores.cod_reporte DESC";
            }
          }
            $params = array();
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
            $rows=sqlsrv_num_rows($resultado);
            $asignar = 0;
            if($rows > 0) 
            {   ?>
                <center><h4 style="color: #C8634C;">Tiquetes Cerrados</h4></center>
                <div class="table-responsive">
                <table id="example1" class="row-border hover order-column compact">
                    <thead>
                        <tr>
                            <th style="width: 7%; vertical-align: middle;"><center>Remision</center></th>
                            <th style="width: 7%; vertical-align: middle;"><center>N° tiquete</center></th>
                            <th style="width: 8%; vertical-align: middle;"><center>Cliente</center></th>
                            <th style="width: 10%; vertical-align: middle;"><center>Operario</center></th>
                            <th style="width: 15%; vertical-align: middle;"><center>Lugar</center></th>
                            <th style="width: 9%; vertical-align: middle;"><center>Cargador</center></th>
                            <th style="width: 9%; vertical-align: middle;"><center>Horo ini</center></th>
                            <th style="width: 9%; vertical-align: middle;"><center>Horo Fin</center></th>
                            <th style="width: 10%; vertical-align: middle;"><center>Jornada Inicial</center></th>
                            <th style="width: 10%; vertical-align: middle;"><center>Jornada Final</center></th>
                            <th style="width: 4%; vertical-align: middle;"><center>Hrs.</center></th>
                            <th style="width: 10%; vertical-align: middle;"><center>Estado</center></th>
                            <th><center>Fecha Distribución</center></th>
                            <th style=" <?php if ($_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){?>  width: 20%; <?php }else{ ?>  width: 10%; <?php } ?> vertical-align: middle;">
                                <center>Acciones</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count_horas = 0;
                    while($datos = sqlsrv_fetch_array($resultado)){
                        $count_horas += $datos['horas_trabajadas'];
                        $datos_registro = $datos['id_registro']. "||" .
                                          $datos['cod_reporte']. "||" .
                                          date_format($datos['fecha_apertura_tique'],'Y-m-d  H:i:s'). "||" .
                                          utf8_encode($datos['Descripcion']). "||" .
                                          utf8_encode($datos['NombreCargador']). "||" .
                                          date_format($datos['fecha_ini_jornada'],'Y-m-d  H:i:s'). "||" .
                                          utf8_encode($datos['NombreCorto']);
                            ?>
                            <tr>
                            <td><center><?php if($datos['remision'] != null){ echo $datos['remision']; }else{ echo 0; }?></center></td>
                            <td><center><?php echo $datos['cod_reporte']; ?></center></td>
                            <td><?php echo utf8_encode($datos['NombreCorto']); ?></td>
                            <td><?php echo utf8_encode($datos['NombreUsuarioLargo']); ?></td>
                            <td><?php echo $datos['Descripcion']; ?></td>
                            <td><?php echo $datos['NombreCargador']." - ".$datos['Identificacion'] ; ?></td>
                            <td><?php echo '<b>'.$datos['horometro_ini'].'</b>'; ?></td>
                            <td><?php echo '<b>'.$datos['horometro_fin'].'</b>'; ?></td>
                            <td><?php echo date_format($datos['fecha_ini_jornada'], 'M-d  h:i')/*.'<br> <b>Hi: '.$datos['horometro_ini'].'</b>'*/; ?></td>
                            <td>
                                <?php 
                                if ($datos['fecha_fin_jornada'] == 0){
                                    echo "<center>"." ------------------- "."</center>";
                                }else{
                                    echo date_format($datos['fecha_fin_jornada'], 'M-d  h:i')/*.'<br> <b>Hf: '.$datos['horometro_fin'].'</b>'*/;
                                }
                                ?>
                            </td>
                            <td><?php echo number_format($datos['horas_trabajadas'],1,',','.'); ?></td>
                            <?php
                            if($datos['estado'] == 1){
                                ?>
                                <td style="background-color: #E15A5A;">
                                    <center><b>Activo</b></center>
                                <?php
                            }elseif ($datos['estado'] == 2){
                                ?>
                                <td style="background-color: #F0AF44;">
                                    <center><b>Por Distribuir</b></center>
                                <?php
                            }elseif ($datos['estado'] == 3){
                                ?>
                                <td style="background-color: #94C27C;">
                                    <center><b>Revisado</b></center>
                                <?php
                            }elseif ($datos['estado'] == 4){
                                ?>
                                <td style="background-color: #B57CC2;">
                                    <center><b>Corrección</b></center>
                                <?php
                            } ?>
                            </td>
                            <td><?php echo date_format($datos['fecha_cierre_tique'],'d-M h:i'); ?></td>
                            <td>
                                <?php
                                if($datos['fecha_fin_jornada'] != 0){
                                    ?>
                                    <center>
                                        <?php
                                        if ($_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES' || $_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){
                                            if ($datos['estado'] == 2){
                                            $sql = "SELECT horometro.fecha_cierre_horometro ,horometro.id_horometro, horometro.id_registro, horometro.horometro_inicial, horometro.horometro_final, horometro.total_horas, horometro.idActividad, horometro.fecha_registro_horometro, Actividades.Descripcion, SubActividades_cargadores.Descripcion as Descripcion_sub
                                                    FROM horometro LEFT JOIN
                                                    Actividades ON horometro.idActividad = Actividades.idActividad
                                                    LEFT JOIN SubActividades_cargadores ON horometro.idSubActividad=SubActividades_cargadores.idSubActividad
                                                    WHERE horometro.id_registro='". $datos['id_registro'] ."'";
                                            $result=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
                                            while ($horometro = sqlsrv_fetch_array($result)) {
                                                if ($horometro['Descripcion'] == "" && $horometro['Descripcion_sub'] == "" && $horometro['total_horas'] != 0){
                                                    $asignar++;
                                                    $tique = $horometro['id_registro'];
                                                }
                                            }
                                            if ($asignar != 0 && $datos['id_registro'] == $tique){
                                            ?>
                                                <button class="btn btn-default" title="Distribuir Tiempos" data-toggle="modal" data-target="#modalAsignarTemp" onclick="datos('<?php echo $datos['id_registro']; ?>')"><span class="glyphicon glyphicon-list-alt"></span></button>
                                            <?php 
                                            }else
                                            {?>
                                                <button class="btn btn-success" title="Los Tiempos Ya Fueron Distribuidos" data-toggle="modal" data-target="#modalAsignarTemp" onclick="datos('<?php echo $datos['id_registro']; ?>')"><span class="glyphicon glyphicon-list-alt"></span></button>
                                            <?php 
                                            }?>
                                            <button id="button" class="btn <?php if ($datos['estado'] == '3'){ echo 'btn-success';}else { echo 'btn-default';}?>" data-toggle="modal" data-target="#modalConfirmar" onclick="ver('<?php echo $datos['id_registro']; ?>')" <?php if($asignar != 0 && $datos['id_registro'] == $tique){ echo 'disabled="true"'; echo 'title="Falta asignar tiempos"'; }else{ echo 'title="Aprobar tiquete"'; } ?>>
                                                <span class="glyphicon glyphicon-ok"></span>
                                            </button>
                                            <a href="horometros.php?idTiquete=<?php echo $datos['id_registro']; ?>&Reporte=<?php echo $datos['cod_reporte']; ?>">
                                                <button class="btn btn-default" title="Ver actividades">
                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                </button>
                                            </a>
                                            <?php 
                                            }
                                        }?>
                                        <button class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalInforme" onclick="informe('<?php echo $datos['id_registro']; ?>')">
                                          <!--<a title="Informe PDF" href="informe_horometros-pdf.php?idTiquete=<?php echo $datos['id_registro']; ?>" target="_blank">-->
                                            <img src="../Imagenes/pdf-solictud.png" width="20" height="30"/>
                                          <!--</a>-->
                                        </button>
                                        <?php
                                        if ($datos['estado'] == 3 && $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES'){
                                            ?>
                                            <button class="btn btn-light" title="Editar Tiquete # <?php echo $datos['cod_reporte']; ?>" data-toggle="modal" onclick="editar_tiquete('<?php echo $datos['id_registro']; ?>',1)">
                                                <!--<span class="glyphicon glyphicon-pencil"></span>-->
                                                <span class="glyphicon glyphicon-erase"></span>
                                            </button>
                                            <?PHP
                                        }elseif($datos['estado'] == 4 && $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES'){
                                            ?>
                                            <button class="btn btn-danger" title="Editar Tiquete # <?php echo $datos['cod_reporte']; ?>" data-toggle="modal" onclick="editar_tiquete('<?php echo $datos['id_registro']; ?>',2)">
                                                <!--<span class="glyphicon glyphicon-pencil"></span>-->
                                                <span class="glyphicon glyphicon-th"></span>
                                            </button>
                                            <?PHP
                                        }?>
                                    </center>
                                <?php
                                }else{
                                    ?>
                                    <center>
                                        
                                    </center>
                                    <?php
                                }?>
                            </td>
                        </tr>
                        <?php
                    }?>
                    </tbody>
                </table>
              </div>
            <?php
            //echo $count_horas;
            }else{
                echo "<br><br>";
                echo "<center>No hay tiquetes finalizados por el operario</center>";
            }?>
    </div>
    <div class="modal fade" id="modalConfirmar" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirmación de identidad</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="horometro">
                    <div class="row form-group center-block">
                        <div class="col-sm-12">
                            <label>Digite la remisión del proveedor:</label>
                            <input  type="text" id="remisa" class="form-control">
                        </div>
                    </div>
                    <div class="row form-group center-block" style="background-color: powderblue;"><h4></h4></div>
                    <div class="row form-group center-block">
                        <div class="col-sm-12">
                            <label>Digite su clave:</label>
                            <input  type="password" id="contrasena" class="form-control">
                            <input type="hidden" id="clave" value="<?php echo $password; ?>">
                            <input type="hidden" id="registro">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="confirmar">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
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
    <?php include('modal.php'); ?>
    <script type="text/javascript">
      function informe(idTiquete){
        $('#idTiquete').val(idTiquete);
      }
        function ver(id_registro){
            registro = id_registro;
            $('#registro').val(registro);
        }
        function cerrar(){
            for(i=0;i<document.form.elements.length; i++)
            {  if(document.form.elements[i].type=="button")
                {   $('#'+document.form.elements[i].value).removeClass('btn-success'); 
                    $('#'+document.form.elements[i].value).addClass('btn-default'); 
                }
            }
            $('#checked').hide();
            $('#divDescuento').hide();
            $('#divStandby').hide();
            $('#volverAct').hide();
            $('#volverDesc').show();
            $('#FaltaAsignar').show();
            $('#ActDetallado').show();
            $('#divProducto').hide();
            $('#bordeAsignados').hide();
            $('#divAsignados').hide();
            $('#borde').hide();
            $('#div_subactividad').hide();
            $('#bordePlantilla').hide();
            $('#plantilla_cargue').hide();
            $('#calculado_cargue').hide();
            $('#plantilla_paladas').hide();
            $('#plantilla_apila_entra').hide();
            $('#calculado').hide();
            $('#GrabarDatos').hide();
            $('#GrabarDatos1').hide();
            $('#GrabarDatos2').hide();
            $('#ModificarDatos').hide();
            $('#ModificarDatos1').hide();
            $('#ModificarDatos2').hide();
            $('#div_equipos').hide();
            $('#div_equipos1').hide();
            $('#div_clasif').hide();
            $('#volverStandby').hide();
            $('#div_pila').hide();
            $('#ProductoObjetivoLabel').hide();
            $('#ProductoObjetivoSelect').hide();
            self.location = "inicio_patio.php";
        }
        $(document).ready(function () {
            $('#loader').hide();
            //$('#modal_body').show();
            var table = $('#example1').DataTable( {
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                //"dom": '<"top"i>rt<"bottom"flp><"clear">',
                //"dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
                //"paging":   true,
                /*"language": {
                    "decimal": ",",
                    "thousands": "."
                },
                "language": {
                    "lengthMenu": "Display _MENU_ records per page",
                    "zeroRecords": "Nothing found - sorry",
                    "info": "Showing page _PAGE_ of _PAGES_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(filtered from _MAX_ total records)"
                },*/
                "ordering": true,
                "info":     true,
                stateSave: true,
                //scrollY:        '50vh',
                //"scrollX": true,
                "scrollCollapse": true,
                "paging":         true
            } );
            var table1 = $('#example2').DataTable( {
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                //"dom": '<"top"i>rt<"bottom"flp><"clear">',
                //"dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
                //"paging":   true,
                /*"language": {
                    "decimal": ",",
                    "thousands": "."
                },
                "language": {
                    "lengthMenu": "Display _MENU_ records per page",
                    "zeroRecords": "Nothing found - sorry",
                    "info": "Showing page _PAGE_ of _PAGES_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(filtered from _MAX_ total records)"
                },*/
                "ordering": true,
                "info":     true,
                stateSave: true,
                //scrollY:        '50vh',
                //"scrollX": true,
                "scrollCollapse": true,
                "paging":         true
            } );

            /*$('#example1 tbody').on('click', 'tr', function () {
                var data = table.row( this ).data();
                alert( 'You clicked on '+data[0]+'\'s row' );
            } );*/

            $('#div_red').hide();
            //
            $('#DestinoLabel').hide();
            $('#DestinoSelect').hide();
            $('#div_pila').hide();
            $('#ProductoObjetivoLabel').hide();
            $('#ProductoObjetivoSelect').hide();
            $('#checked').hide();
            $('#divDescuento').hide();
            $('#divStandby').hide();
            $('#volverStandby').hide();
            $('#volverAct').hide();
            $('#volverDesc').show();
            $('#FaltaAsignar').show();
            $('#ActDetallado').show();
            $('#divProducto').hide();
            $('#bordeAsignados').hide();
            $('#divAsignados').hide();
            $('#divAsignados1').hide();
            $('#borde').hide();
            $('#div_subactividad').hide();
            $('#bordePlantilla').hide();
            $('#plantilla_cargue').hide();
            $('#calculado_cargue').hide();
            $('#plantilla_paladas').hide();
            $('#plantilla_apila_entra').hide();
            $('#calculado').hide();
            $('#GrabarDatos').hide();
            $('#GrabarDatos1').hide();
            $('#GrabarDatos2').hide();
            $('#ModificarDatos').hide();
            $('#ModificarDatos1').hide();
            $('#ModificarDatos2').hide();
            $('#div_equipos').hide();
            $('#div_equipos1').hide();
            $('#div_clasif').hide();
            band = $('#band').val();
            $('#confirmar').click(function (){
                if ($('#clave').val() != $('#contrasena').val()){
                    alert('La contraseña es incorrecta');
                }else{
                    registro = $('#registro').val();
                    remision = $('#remisa').val();
                    contrasena = $('#contrasena').val();
                    clave = $('#clave').val();
                    band = 2;
                    cadena = "registro=" + registro +
                            "&remision=" + remision +
                            "&band=" + band;
                    $.ajax({
                        type: "POST",
                        url: "../modelo/tiquete_finalizar.php",
                        data: cadena,
                        success: function (r) {
                            console.log(r);
                            if (r == 1) {
                                alert("Finalizado con exito");
                                self.location = "inicio_patio.php";
                            } else if(r == 3) {
                                alert("Hay actividades sin finalizar");
                            }else{
                                alertify.error("La información no se pudó actualizar");
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>