<?php
session_start();
require_once '../modelo/conexion.php';
//error_reporting(0);
if ($_SESSION["logueado"] == TRUE && $_SESSION["permisoIngresar"] == 'CARGADORES-SERVER'){
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
}else{
    ?>
    <script type="text/javascript">
        self.location='../index.php';
        alert('Inicia sesión primero');
    </script>
    <?php
    die();
}
$css_registro_tique = 'opacity: 0.3;';
$ruta_registro_tique = '#';
if(isset($_SESSION['Array_empresa']['REGISTRO_TIQUETES'])){
    $css_registro_tique = '';
    $ruta_registro_tique = 'inicio.php';
}
$css_verificar_tique = 'opacity: 0.3;';
$ruta_verficar_tique = '#';
if(isset($_SESSION['Array_empresa']['VERIFICAR_TIQUETES'])){
    $css_verificar_tique = '';
    $ruta_verficar_tique = 'inicio_patio.php';
}
$css_consulta = 'opacity: 0.3;';
$ruta_consulta = '#';
if(isset($_SESSION['Array_empresa']['CONSULTAS'])){
    $css_consulta = '';
    $ruta_consulta = 'consultas.php';
}
$css_insumo = 'opacity: 0.3;';
$ruta_insumo = '#';
if(isset($_SESSION['Array_empresa']['INSUMOS'])){
    $css_insumo = '';
    $ruta_insumo = '#';
}
$css_mantenimiento = 'opacity: 0.3;';
$ruta_mantenimiento = '#';
if(isset($_SESSION['Array_empresa']['MANTENIMIENTOS'])){
    $css_mantenimiento = '';
    $ruta_mantenimiento = 'MantenimientoCargadores.php';
}
$css_administracion = 'opacity: 0.3;';
$ruta_administracion = '#';
if(isset($_SESSION['Array_empresa']['ADMINISTRACION'])){
    $css_administracion = '';
    $ruta_administracion = 'actividades.php';
}
$css_maquinaria = 'opacity: 0.3;';
$ruta_maquinaria = '#';
if(isset($_SESSION['Array_empresa']['MAQUINARIA'])){
    $css_maquinaria = '';
    $ruta_maquinaria = 'maquinaria.php';
}
$css_produccion = 'opacity: 0.3;';
$ruta_produccion = '#';
if(isset($_SESSION['Array_empresa']['PRODUCCION'])){
    $css_produccion = '';
    $ruta_produccion = 'modulo_produccion_v2.php';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=0.8">
    <meta charset="UTF-8">
    <title><?php echo $_SESSION['permisoIngresar']; ?></title>
    <?php include './libreria.php'; ?>
    <style>
        /* Remove the navbar's default rounded borders and increase the bottom margin */ 
        .navbar {
            margin-bottom: 50px;
            border-radius: 0;
        }
        /* Remove the jumbotron's default bottom margin */ 
        .jumbotron {
            margin-bottom: 0;
            background-position: all;
        }
        .grow:hover
        {
        -webkit-transform: scale(0.8);
        -ms-transform: scale(0.8);
        transform: scale(0.8);
        }
        /* Add a gray background color and some padding to the footer */
        footer {
            background-color: #f2f2f2;
            padding: 25px;
        }
    </style>
</head>
<body>
    <?php include './Header.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
                <center>
                    <a href="<?php echo $ruta_registro_tique; ?>">
                        <img class="grow" src="../Imagenes/registro.png" style="<?php echo $css_registro_tique; ?> width:30%" alt="Image">
                    </a>
                    <h3>Registro Tiquetes</h3>
                </center>
            </div>
            <div class="col-sm-3">
                <center>
                    <a href="<?php echo $ruta_verficar_tique; ?>">
                        <img class="grow" src="../Imagenes/verificar.svg" style="<?php echo $css_verificar_tique; ?> width:30%" alt="Image">
                    </a>
                    <h3>Verificar Tiquetes</h3>
                </center>
            </div>
            <div class="col-sm-3">
                <center>
                    <a href="<?php echo $ruta_consulta; ?>">
                        <img class="grow" src="../Imagenes/Consultas-icon.jpg" style="<?php echo $css_consulta; ?> width:18%" alt="Image">
                    </a>
                    <h3>Consultas</h3>
                </center>
            </div>
            <div class="col-sm-3">
                <center>
                    <a href="<?php echo $ruta_insumo; ?>">
                        <img class="grow" src="../Imagenes/registro.png" style="<?php echo $css_insumo; ?> width:30%" alt="Image">
                    </a>
                    <h3>Insumos</h3>
                </center>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-sm-3">
                <center>
                    <a href="<?php echo $ruta_mantenimiento; ?>">
                        <img class="grow" src="../Imagenes/mantenimiento_admin.png" style="<?php echo $css_mantenimiento; ?> width:40%" alt="Image"></a>
                    <h3>Mantenimientos</h3>
                </center>
            </div>
            <div class="col-sm-3">
                <center>
                    <a href="<?php echo $ruta_administracion; ?>">
                        <img class="grow" src="../Imagenes/Administracion.png" style="<?php echo $css_administracion; ?> width:30%" alt="Image">
                    </a>
                    <h3>Administración</h3>
                </center>
            </div>
            <div class="col-sm-3">
                <center>
                    <a href="<?php echo $ruta_maquinaria; ?>">
                        <img class="grow" src="../Imagenes/950F - C06.jpg" style="<?php echo $css_maquinaria; ?> width:40%" alt="Image">
                    </a>
                    <h3>Maquinaria</h3>
                </center>
            </div>
            <div class="col-sm-3">
                <center>
                    <a href="<?php echo $ruta_produccion; ?>">
                        <img class="grow" src="../Imagenes/BD.png" style="<?php echo $css_produccion; ?> width:30%" alt="Image"></a>
                    <h3>Producción</h3>
                </center>
            </div>
        </div>
    </div>
</body>
<br><br><br><br>
<footer>
    <a href="ayuda.php"><button class="btn btn-default navbar-right" style="margin-right: 3px; margin-top: -15px;"><span class="glyphicon glyphicon-question-sign"></span></button></a>
</footer>
</html>
