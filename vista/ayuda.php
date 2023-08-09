<?php
session_start();
require_once '../modelo/conexion.php';
//error_reporting(0);
if ($_SESSION["logueado"] == TRUE && ($_SESSION["permisoIngresar"] == 'CONSULTAS_OFICINA' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES')){
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
}elseif($_SESSION['permisoIngresar'] == 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES'){
    //header("Location: MantenimientoCargadores.php");
    ?>
    <script type="text/javascript">
        self.location='MantenimientoCargadores.php';
        alert('No tienes permiso para acceder a este ambiente');
    </script>
    <?php
}elseif($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){
    //header("Location: incio.php");
    ?>
    <script type="text/javascript">
        self.location='inicio.php';
        alert('No tienes permiso para acceder a este ambiente');
    </script>
    <?php
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
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<?php include './libreria.php'; ?>
    <meta name="viewport" content="width=auto, initial-scale=0.8">
</head>
<body>
	<?php include './Header.php'; ?>
	<div class="container">
		<center><h1>Guia de Ayuda</h1></center>
		<ul class="page-breadcrumb breadcrumb">
            <li><a href="Admin.php"><i class=" fa fa-home fa-sm"></i> Inicio</a></li>
            <li><span class="active">Ayuda</span></li>
        </ul>
		<div class="row">
            <div class="col-sm-4">
                <center>
                    <a href="detalle.php?var=1">
                        <img class="grow" src="../Imagenes/registro.png" class="" style="width:40%" alt="Image">
                    </a>
                    <h3>Registro Tiquetes</h3>
                </center>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-6" style="background-color: #1BABBD;">
            	<p> Esta opción se encuentra disponible para la creación y consulta de tiquetes de maquinaria.
Entre la información los usuarios podrán consultar tiquetes activos y aquellos que han sido completados (Finalizado), durante el día presente o el día anterior a su registro.

Los datos básicos en pantalla son:

   Numero de tiquete
   Cliente
   Lugar
   Maquina
   Hora y fecha del inicio de la Jornada
   Hora y fecha de finalización de la Jornada
   Horas trabajadas
   Estados del Tiquete
</p>
            </div>
		</div>
		<!-- -------->
		<div class="row">
			<br>
			<div class="col-sm-1"></div>
			<div class="col-sm-6" style="background-color: #45E7BD;">
            	<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
            	<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-4">
                <center>
                    <a href="detalle.php?var=2">
                        <img class="grow" src="../Imagenes/verificar.svg" class="" style="width:40%" alt="Image">
                    </a>
                    <h3>Verificar Tiquetes</h3>
                </center>
            </div>
		</div>
		<!-------->
		<div class="row">
			<br>
            <div class="col-sm-4">
                <center>
                    <a href="detalle.php?var=3">
                        <img class="grow" src="../Imagenes/Consultas-icon.jpg" class="" style="width:28%" alt="Image">
                    </a>
                    <h3>Consultas</h3>
                </center>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-6" style="background-color: #E88A1A;">
            	<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
            	<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
            </div>
		</div>
		<!-- -------->
		<div class="row">
			<br>
			<div class="col-sm-1"></div>
			<div class="col-sm-6" style="background-color: powderblue;">
            	<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
            	<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-4">
                <center>
                    <a href="detalle.php?var=4">
                        <img class="grow" src="../Imagenes/mantenimiento_admin.png" class="" style="width:50%" alt="Image"></a>
                    </a>
                    <h3>Mantenimientos</h3>
                </center>
            </div>
		</div>
		<!---------->
		<div class="row">
			<br>
            <div class="col-sm-4">
                <center>
                    <a href="detalle.php?var=5">
                        <img class="grow" src="../Imagenes/Administracion.png" class="" style="width:40%" alt="Image">
                    </a>
                    <h3>Administración</h3>
                </center>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-6" style="background-color: #ABAA99;">
            	<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
            	<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
            </div>
		</div>
		<!-- -------->
		<div class="row">
			<br>
			<div class="col-sm-1"></div>
			<div class="col-sm-6" style="background-color: #E5DA2E;">
            	<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
            	<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-4">
                <center>
                    <a href="detalle.php?var=6">
                        <img class="grow" src="../Imagenes/950F - C06.jpg" class="" style="width:50%" alt="Image">
                    </a>
                    <h3>Maquinaria</h3>
                </center>
            </div>
		</div>
		<!---------->
		<div class="row">
			<br>
            <div class="col-sm-4">
                <center>
                    <a href="detalle.php?var=7">
                        <img class="grow" src="../Imagenes/Tarifa.png" class="" style="width:40%" alt="Image">
                    </a>
                    <h3>Tarifas</h3>
                </center>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-6" style="background-color: #F7F4EF">
            	<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
            	<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
            </div>
		</div>
		<br><br>
	</div>
</body>

</html>