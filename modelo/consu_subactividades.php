<?php 
include('conexion.php');
$idActividad = $_POST['actividad'];

$sql = "SELECT * FROM subactividades_cargadores WHERE idActividad='$idActividad'";
$res = sqlsrv_query($conn,$sql);
$json = array();
while($row = sqlsrv_fetch_array($res)){
	$json[] = array(
	    'idSubactividad' => $row['idSubactividad'],
	    'Descripcion' => utf8_encode($row['Descripcion'])
	);
}
$jsonstring = json_encode($json);
echo $jsonstring;
?>