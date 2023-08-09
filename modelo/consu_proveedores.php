<?php
include('conexion.php');

$id_maquina = $_POST['id_maquina'];
$patio = $_POST['patio'];
$cliente = $_POST['cliente'];
$patio_diferent = 0;
$cliente_diferent = 0;

$sql = "SELECT * FROM Registro_tique_cargadores WHERE id_maquinaria='$id_maquina' AND estado=1";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
$rows=sqlsrv_num_rows($res);
if ($rows > 0){
	while($repeat = sqlsrv_fetch_array($res)){
		if ($repeat['id_patio'] != $patio){
			$patio_diferent++;
		}else
		if ($repeat['id_proveedor'] == $cliente){
			$cliente_diferent++;
		}
	}
}


$sql = "SELECT RazonSocial FROM Proveedores WHERE idProveedor=(SELECT idPropietario FROM Equipos WHERE idEquipo='$id_maquina')";
$res = sqlsrv_query($conn,$sql);
while($provee = sqlsrv_fetch_array($res)){
	$proveedor = utf8_encode($provee['RazonSocial']);
}
$data = $patio_diferent."||".
		$cliente_diferent."||".
		$proveedor;
echo $data;
?>