<?php
include('conexion.php');
$operador = $_POST['operador'];
$registro = $_POST['registro'];
//$fecha = $_POST['fecha'];
$fecha_actual = date('Y-m-d H:i:s');
$lugar = $_POST['lugar'];
$cargador = $_POST['cargador'];
//$hora_actual = date('H:i:s');
//$jornada_inicial = $_POST['jornada_inicial'];
//$fecha_jor_ini = $fecha." ".$jornada_inicial;
//$fecha_hora = $fecha_actual." ".$hora_actual;
$cliente = $_POST['cliente'];
$servicio_clasif = $_POST['servicio_clasif'];

$sql = "SELECT * FROM detalle_equipos WHERE idEquipos='$cargador'";
$resultado = sqlsrv_query($conn,$sql);
while ($equipo = sqlsrv_fetch_array($resultado)) {
	$horometro_maquina = $equipo['horometro_final'];
	$vida_util = $equipo['horometro_vida_util'];
	$horometro_mantto = $equipo['horometro_mantto'];
}

$sql_insert = "INSERT INTO Registro_tique_cargadores (id_registro, cod_reporte, id_patio, id_maquinaria, id_usuario, id_proveedor, fecha_ini_jornada, fecha_apertura_tique, estado, servicio_clasificacion, horometro_ini, remision) VALUES (NEWID(),'$registro','$lugar','$cargador','$operador','$cliente','$fecha_actual','$fecha_actual','1','$servicio_clasif','$horometro_maquina','0')";
$result = sqlsrv_query($conn,$sql_insert);
if ($result){
	echo 1;
}
//echo $result;
?>