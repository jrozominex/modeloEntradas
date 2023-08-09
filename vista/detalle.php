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
        alert('inicia sesión primero');
    </script>
    <?php
    die();
}
if($_GET['var'] == 1){
	$var = 'Registro tiquetes';
}elseif($_GET['var'] == 2){
	$var = 'Verificar tiquetes';
}elseif($_GET['var'] == 3){
	$var = 'Consultas';
}elseif($_GET['var'] == 4){
	$var = 'Mantenimientos';
}elseif($_GET['var'] == 5){
	$var = 'Administración';
}elseif($_GET['var'] == 6){
	$var = 'Maquinaria';
}elseif($_GET['var'] == 7){
	$var = 'Tarifas';
}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="viewport" content="width=auto, initial-scale=0.8">
	<?php include './libreria.php'; ?>
</head>
<body>
	<?php include './Header.php'; ?>
	<div class="container">
		<center><h1>Guia de Ayuda</h1></center>
		<ul class="page-breadcrumb breadcrumb">
            <li><a href="Admin.php"><i class=" fa fa-home fa-sm"></i> Inicio</a></li>
            <li><a href="ayuda.php"><i class=" fa fa-home fa-sm"></i> Ayuda</a></li>
            <li><span class="active"><?php echo $var; ?></span></li>
        </ul>
        <?php 
    	if($_GET['var'] == 1){
			?>
			<center><h3 style="background-color: #1BABBD;">Tiquetes registrados en el día</h3></center>
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<img src="../Imagenes/Guia-ayuda/Opcion1-1.png" width="100%" border="1">
				</div>
			</div>
			<!-------------------------------------------------------------------------------------->
			<center><h3 style="background-color: #1BABBD;">Registro de tiquetes</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-6">
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion1-2-1.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion1-2-1.png" width="100%" border="1">
					</a>
				</div>
				<div class="col-sm-6">
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion1-2.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion1-2.png" width="100%" border="1">
					</a>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<Lorem> El operador de bascula debe dar Clic en el botón “Crear Tiquete”
Seguidamente se abrirá un cuadro de dialogo para lo cual se recomienda leer las instrucciones del enlace.

CHEQUEOS PREVIOS AL REGISTRO DE TIQUETES EN EL APLICATIVO MAQUINARIA Y EQUIPOS

Es muy importante que el usuario tenga presente las siguientes observaciones para el correcto funcionamiento:
</Lorem>
				</div>
			</div>
			<!-------------------------------------------------------------------------------------->
			<center><h3 style="background-color: #1BABBD;">Registro de actividades</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-6">
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion1-3-1.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion1-3-1.png" width="100%" border="1">
					</a>
				</div>
				<div class="col-sm-6">
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion1-3.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion1-3.png" width="100%" border="1">
					</a>
				</div>
			</div>
			<div class="row">
				<br>
				<div class="col-sm-4">
					<center><h4>Actividades detalladas</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion1-4.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion1-4.png" width="100%" border="1">
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4>Actividades de distribución</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion1-5.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion1-5.png" width="100%" border="1">
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4>Mantenimientos</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion1-6.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion1-6.png" width="100%" border="1">
					</a>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
				</div>
				<div class="col-sm-4">
					<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
				</div>
				<div class="col-sm-4">
					<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
				</div>
			</div>
			<!-------------------------------------------------------------------------------------->
			<center><h3 style="background-color: #1BABBD;">Vista finalizar actividad</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<img src="../Imagenes/Guia-ayuda/Opcion1-7.png" width="100%" border="1">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
				</div>
			</div>
			<!-------------------------------------------------------------------------------------->
			<center><h3 style="background-color: #1BABBD;">Vista finalizar tiquete</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<img src="../Imagenes/Guia-ayuda/Opcion1-8.png" width="100%" border="1">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
				</div>
			</div>
			<!-------------------------------------------------------------------------------------->
			<center><h3 style="background-color: #1BABBD;">Vista Reporte falla</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<img src="../Imagenes/Guia-ayuda/Opcion1-9.png" width="100%" border="1">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
				</div>
			</div>
			<!-------------------------------------------------------------------------------------->
			<center><h3 style="background-color: #1BABBD;">Vista Reporte falla</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<img src="../Imagenes/Guia-ayuda/Opcion1-10.png" width="100%" border="1">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
				</div>
			</div>
			<!-------------------------------------------------------------------------------------->
			<center><h3 style="background-color: #1BABBD;">Vista Consultas</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<img src="../Imagenes/Guia-ayuda/Opcion1-11.png" width="100%" border="1">
				</div>
			</div>
			<div class="row">
				<br>
				<div class="col-sm-4">
					<center><h4>Consulta por tiquete</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion1-12.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion1-12.png" width="100%" border="1">
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4>Consulta por cliente</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion1-13.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion1-13.png" width="100%" border="1">
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4>Consulta por cargador</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion1-14.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion1-14.png" width="100%" border="1">
					</a>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
				</div>
				<div class="col-sm-4">
					<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
				</div>
				<div class="col-sm-4">
					<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
				</div>
			</div>
			<?php
			/*********************************************************************************************/
			/*********************************************************************************************/
		}elseif($_GET['var'] == 2){
			?>
			<center><h3 style="background-color: #45E7BD;">Consulta de tiquetes registrados en el día</h3></center>
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<img src="../Imagenes/Guia-ayuda/Opcion2-1.png" width="100%" border="1">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<Lorem> ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</Lorem>
				</div>
			</div>
			<!-------------------------------------------------------------------------------------->
			<center><h3 style="background-color: #45E7BD;">Distribuir tiempos del tiquete</h3></center>
			<div class="row">
				<div class="col-sm-4">
					<center><h4><b>Paso N° 1: </b>Hacer click en el botón indicado</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion2-1-1.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion2-1-1.png" width="100%" border="1">
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 2: </b>Luego saldrá esta ventana</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion2-2.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion2-2.png" width="100%" border="1">
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 3: </b>Elegir una actividad a distribuir</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion2-2-1.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion2-2-1.png" width="100%" border="1">
					</a>
				</div>
			</div>
			<div class="row">
				<br><br>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 4: </b>Luego saldrá un menú de productos</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion2-3.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion2-3.png" width="100%" border="1">
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 5: </b>Elegir un producto</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion2-3-1.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion2-3-1.png" width="100%" border="1">
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 6: </b>Luego saldrán subactividades; seleccionar una</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion2-3-2.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion2-3-2.png" width="100%" border="1">
					</a>
				</div>
			</div>
			<div class="row">
				<br><br>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 7: </b>Luego saldrá el menú para definir los valores</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion2-4.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion2-4.png" width="100%" border="1">
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 8: </b>Ingresa los valores y presiona calcular</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion2-4-1.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion2-4-1.png" width="100%" border="1">
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 9: </b>Luego obtendrá los valores totales</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion2-5.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion2-5.png" width="100%" border="1">
					</a>
				</div>
			</div>
			<!-------------------------------------------------------------------------------------->
			<br>
			<center><h3 style="background-color: #45E7BD;">Realizar un descuento</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 1: </b>Hacer click en el boton descuentos</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion2-7.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion2-7.png" width="100%" border="1">
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 2: </b>Luego obtendrá los valores totales</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion2-6.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion2-6.png" width="100%" border="1">
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 3: </b>Ingresa el tiempo a descontar, el motivo del descuento y presionas el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion2-8.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion2-8.png" width="100%" border="1">
					</a>
				</div>
			</div>
			<br>
			<center><h3 style="background-color: #45E7BD;">Volver a distribuir actividades</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-6">
					<center><h4><b>Paso N° 1: </b>Hacer click en el boton Asignar tiempos</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion2-10.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion2-10.png" width="100%" border="1">
					</a>
				</div>
				<div class="col-sm-6">
					<center><h4><b>Paso N° 2: </b>Volverá al inicio de la ventana</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion2-2.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion2-2.png" width="100%" border="1">
					</a>
				</div>
			</div>
			<br>
			<center><h3 style="background-color: #45E7BD;">Realizar una observación</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<center><h4><b>Paso N° 1: </b>Escribes las observaciones y dar click en el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion2-9.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion2-9.png" width="100%" border="1">
					</a>
				</div>
			</div>
			<br><br>
			<?php
		}elseif($_GET['var'] == 3){
			?>
			<br>
			<center><h3 style="background-color: #E88A1A;">Consultar informes</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion3-1.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion3-1.png" width="100%" border="1">
					</a>
				</div>
			</div>
			<br>
			<center><h3 style="background-color: #E88A1A;">Tipos de informes</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-3">
					<center><h4><b>Opción N° 1: </b>Ver tiquetes registrados en un periodo</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion3-1-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion3-1-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-3">
					<center><h4><b>Opción N° 2: </b></h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion3-1-2.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion3-1-2.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-3">
					<center><h4><b>Opción N° 3: </b></h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion3-1-3.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion3-1-3.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-3">
					<center><h4><b>Opción N° 4: </b></h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion3-1-4.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion3-1-4.png" width="100%" border="1">
					</a>
				</div>
			</div>
			<br><br>
			<?php
		}elseif($_GET['var'] == 4){
			?>
			<br>
			<center><h3 style="background-color: powderblue;">Mantenimientos</h3></center>
			<div class="row">
				<br>
				<center><h4>Puede ver o realizar mantenimientos por maquinas</h4></center>
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion4-1.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion4-1.png" width="100%" border="1">
					</a>
				</div>
			</div>
			<br>
			<div class="row">
				<br>
				<div class="col-sm-6">
					<center><h4><b>Paso N° 1: </b>Selecciona una maquina</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion4-1-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion4-1-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-6">
					<center><h4><b>Paso N° 2: </b>Verás el menú de la maquina seleccionada</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion4-2.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion4-2.png" width="100%" border="1">
						</center>
					</a>
				</div>
			</div>
			<div class="row">
				<br><br>
				<div class="col-sm-6">
					<center><h4><b>Opción N° 1: </b>Dar click en el boton reporte de fallas para ver las fallas reportadas</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion4-2-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion4-2-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-6">
					<center><h4><b>Opción N° 2: </b>Dar click en el boton mantenimiento para ver los mantenimientos</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion4-2-2.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion4-2-2.png" width="100%" border="1">
						</center>
					</a>
				</div>
			</div>
			<br>
			<center><h3 style="background-color: powderblue;">Iniciar mantenimiento</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 1: </b>Dar click en el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion4-2-3.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion4-2-3.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 2: </b>Se abrirá está ventana con datos de la maquina</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion4-3.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion4-3.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 3: </b>Seleccionas los mantenimientos a realizar y damos click en el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion4-3-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion4-3-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
			</div>
			<br>
			<center><h3 style="background-color: powderblue;">Ver reporte mantenimiento</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 1: </b>Dar click en el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion4-6.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion4-6.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<center><h4><b>Paso N° 2: </b>Se abrirá está ventana </h4></center>
				<div class="col-sm-4">
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen1('../Imagenes/Guia-ayuda/Opcion4-5.png','../Imagenes/Guia-ayuda/Opcion4-5-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion4-5.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-4">
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen1('../Imagenes/Guia-ayuda/Opcion4-5.png','../Imagenes/Guia-ayuda/Opcion4-5-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion4-5-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
			</div>
			<br><br>
			<?php
		}elseif($_GET['var'] == 5){
			?>
			<center><h3 style="background-color: #ABAA99;">Actividades por categorias</h3></center>
			<div class="row">
				<div class="col-sm-4">
					<center><h4><b>Paso N° 1: </b>La pantalla inicial es la siguiente</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion5-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion5-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 2: </b>Seleecionas una categoria de actividad</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion5-1-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion5-1-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 3: </b>Se visualizan todas las actividades pertenecientes a esa categoria</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion5-1-2.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion5-1-2.png" width="100%" border="1">
						</center>
					</a>
				</div>
			</div>
			<br>
			<center><h3 style="background-color: #ABAA99;">Registrar una nueva actividad</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 1: </b>Dar click en el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion5-2.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion5-2.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 2: </b>Se abrirá está ventana </h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion5-2-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion5-2-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 3: </b>Complete los datos como se le indica y de click en el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion5-2-2.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion5-2-2.png" width="100%" border="1">
						</center>
					</a>
				</div>
			</div>
			<br>
			<center><h3 style="background-color: #ABAA99;">Registrar una nueva subactividad</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 1: </b>Dar click en el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion5-3.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion5-3.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 2: </b>Se abrirá está ventana, ingresa la subactividad y presiona el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion5-3-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion5-3-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-4">
					<center><h4><b>Paso N° 3: </b>Luego aparecerá la subactividad</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion5-3-3.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion5-3-3.png" width="100%" border="1">
						</center>
					</a>
				</div>
			</div>
			<br>
			<center><h3 style="background-color: #ABAA99;">Modificar una actividad</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-6">
					<center><h4><b>Paso N° 1: </b>Dar click en el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion5-4.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion5-4.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-6">
					<center><h4><b>Paso N° 2: </b>Se abrirá está ventana, ingresa la subactividad y presiona el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion5-4-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion5-4-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
			</div>
			<br>
			<center><h3 style="background-color: #ABAA99;">Modificar una subactividad</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-6">
					<center><h4><b>Paso N° 1: </b>Dar click en el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion5-5.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion5-5.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-6">
					<center><h4><b>Paso N° 2: </b>Se abrirá está ventana, ingresa la subactividad y presiona el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion5-5-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion5-5-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
			</div>
			<br><br>
			<?php
		}elseif($_GET['var'] == 6){
			?>
			<br>
			<center><h3 style="background-color: #E5DA2E;">Maquinaria registrada</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion6.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion6.png" width="100%" border="1">
					</a>
				</div>
			</div>
			<br>
			<center><h3 style="background-color: #E5DA2E;">Registrar una maquinaria nueva</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-6">
					<center><h4><b>Paso N° 1: </b>Dar click en el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion6-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion6-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-6">
					<center><h4><b>Paso N° 2: </b>Se abrirá está ventana, ingresa los datos y presiona el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion6-1-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion6-1-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
			</div>
			<br>
			<center><h3 style="background-color: #E5DA2E;">Reiniciar el horometro de una maquinaria</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-6">
					<center><h4><b>Paso N° 1: </b>Dar click en el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion6-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion6-2.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-6">
					<center><h4><b>Paso N° 2: </b>Se abrirá está ventana, ingresa la contraseña y presiona el boton; El horometro del equipo volverá a 0</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion6-2-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion6-2-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
			</div>
			<br><br>
			<?php
		}elseif($_GET['var'] == 7){
			?>
			<br>
			<center><h3 style="background-color: #F7F4EF">Tarifas</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion6.png')">
						<img src="../Imagenes/Guia-ayuda/Opcion7.png" width="100%" border="1">
					</a>
				</div>
			</div>
			<br>
			<center><h3 style="background-color: #F7F4EF">Crear una nueva tarifa</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-6">
					<center><h4><b>Paso N° 1: </b>Dar click en el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion7-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion7-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-6">
					<center><h4><b>Paso N° 2: </b>Se abrirá está ventana, Ingresar los datos y hacer click en el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion7-1-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion7-1-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
			</div>
			<br>
			<center><h3 style="background-color: #F7F4EF">Ver el historial de tarifas</h3></center>
			<div class="row">
				<br>
				<div class="col-sm-6">
					<center><h4><b>Paso N° 1: </b>Dar click en el boton</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion7-2.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion7-2.png" width="100%" border="1">
						</center>
					</a>
				</div>
				<div class="col-sm-6">
					<center><h4><b>Paso N° 2: </b>Se abrirá está ventana, En ella verás todas las tarifas creadas vigentes y no vigentes</h4></center>
					<a href="" data-toggle="modal" data-target="#Imagen" onclick="Imagen('../Imagenes/Guia-ayuda/Opcion7-2-1.png')">
						<center>
							<img src="../Imagenes/Guia-ayuda/Opcion7-2-1.png" width="100%" border="1">
						</center>
					</a>
				</div>
			</div>
			<br><br>
			<?php
		}
    	?>
	</div>
	<!------------------------------ MODAL IMAGEN 100% ---------------------------------->
	<div class="modal fade" id="Imagen" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-lg" role="document" id="body"></div>
    </div>
    <!------------------------------ FIN MODAL IMAGEN 100% ---------------------------------->
    <script type="text/javascript">
    	function Imagen(r){
    		event.preventDefault();
    		var bodyImagen = '<img src="'+r+'" width="100%" border="1">';
    		$('#body').html(bodyImagen);
    	}
    	function Imagen1(r,s){
    		event.preventDefault();
    		var bodyImagen = '<img src="'+r+'" width="100%" border="1"><img src="'+s+'" width="100%" border="1">';
    		$('#body').html(bodyImagen);
    	}
    </script>
</body>
</html>