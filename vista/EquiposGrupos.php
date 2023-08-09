<?php
session_start();
require_once '../modelo/conexion.php';
//error_reporting(0);
if ($_SESSION["logueado"] == TRUE && $_SESSION["usuario"]) {
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
} else {
    header("Location: ../index.php");
    die();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
    <meta name="viewport" content="width=auto, initial-scale=1.0">
	<?php include './libreria.php'; ?>
</head>
<body>
	<?php include 'Header.php'; ?>
	<div class="container">
		<center>
            <h2>Clases de Maquinaria</h2>
        </center>
        <a href="inicio_patio.php">
        	<button class="btn btn-default navbar-right" style="margin-right: 7px;">
        		<span class="glyphicon glyphicon-home"></span>
        	</button>
    	</a>
    	<br><br><br>
    	<div class="col-sm-4"></div>
    	<div class="col-sm-3 table-responsive">
            <table class="table table-hover table-condensed table-bordered table-responsive table-striped">
            	<?php 
            	$sql = "SELECT * FROM EquiposGrupos";
            	$params = array();
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
                $rows=sqlsrv_num_rows($resultado);
                if ($rows > 0){
                	?>
                	<thead>
                		<th>Clase</th>
                		<th>Acciones</th>
                	</thead>
                	<?php
                	while($grupos = sqlsrv_fetch_array($resultado)){
                		?>
                		<tr>
                			<td><?php echo $grupos['Descripcion']; ?></td>
                			<td><center><button class="btn btn-warning">Editar</button></center></td>
                		</tr>
                		<?php
                	}
                }
            	?>
            </table>
        </div>
	</div>
</body>
</html>