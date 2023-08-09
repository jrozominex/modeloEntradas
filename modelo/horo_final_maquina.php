<?php
include('conexion.php');
$id = $_POST['valor_maquina'];

$sql= "SELECT * FROM detalle_equipos WHERE idEquipos='$id'";
$result = sqlsrv_query($conn,$sql);
while ($horometro_maquina = sqlsrv_fetch_array($result)){
	$fin = $horometro_maquina['horometro_final'];
	$vida = $horometro_maquina['horometro_vida_util'];
	$mantto = $horometro_maquina['horometro_mantto'];
}
echo $fin."||".
	$vida."||".
	$mantto;
?>