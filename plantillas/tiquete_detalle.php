<?php 
session_start();
include('../modelo/conexion.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../librerias/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../librerias/alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="../librerias/alertifyjs/css/themes/default.css">

    <script src="../librerias/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../librerias/estilos.css">
    <script src="../librerias/bootstrap/js/bootstrap.js"></script>
    <script src="../librerias/alertifyjs/alertify.js"></script>
</head>
<body>
	<?php echo $_GET['tiquete']; ?>
	<?php include('./header.php'); ?>
    <div class="container-fluid">
    	<center><h1>Tiquete N° <?php echo $_GET['tiquete']; ?></h1></center>
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="../vista/Admin.php"><i class=" fa fa-home fa-sm"></i>Inicio</a></li>
            <li><a href="../vista/consultas.php"><i class=" fa fa-home fa-sm"></i>Consultas</a></li>
            <li><a href="F2_SG-copia.php"><i class=" fa fa-home fa-sm"></i>Resumen Actividades</a></li>
            <li><span class="active">Tiquete N° <?php echo $_GET['tiquete']; ?></span></li>
        </ul>
    </div>
</body>
</html>