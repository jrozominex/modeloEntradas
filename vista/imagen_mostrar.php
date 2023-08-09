<?php
require_once '../modelo/conexion.php';
include ("../../clase_encrip.php");
$idRegistro= ENCR::descript($_GET['id']);
# Buscamos la imagen a mostrar
$sql = "SELECT * FROM TiquetesCargadores WHERE idRegistro='$idRegistro'";
$resultado_img=sqlsrv_query($conn,$sql);
while($rows=sqlsrv_fetch_array($resultado_img)){
	$fotos=$rows["imgDOC"];
	$extension = $rows['extDOC'];
}
# Mostramos la imagen
$fotoss = base64_decode($fotos);
if($extension != 'pdf'){
	header("Content-type: image/jpeg");
	header("Content-Disposition: inline; filename=Servicios");
	echo ($fotoss);
	return($fotoss);
}else{
	file_put_contents('archivo.pdf',$fotoss);
	header('Content-type: application/pdf');
//	header('Content-Disposition: attachment; filename="archivo.pdf"');
	echo $fotoss;
	return($fotoss);
}
sqlsrv_close($conn);
?>