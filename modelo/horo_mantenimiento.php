<?php
include('conexion.php');
$id = $_POST['valor_maquina'];
$band = 0;
$sql= "SELECT * FROM detalle_equipos WHERE idEquipos='$id'";
$result = sqlsrv_query($conn,$sql);
while ($horometro_maquina = sqlsrv_fetch_array($result)){
	if ($horometro_maquina['horometro_vida_util'] >= $horometro_maquina['horometro_mantto']){
		$band = 1;
	}
}
echo $band;
?>