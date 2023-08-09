<?php
require_once '../modelo/conexion.php';
include ("../../clase_encrip.php");
session_start();
$idUsuario = $_SESSION['idUsuario'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);

$idRegistro = ENCR::descript($_POST['idRegistro']);
$sql = "SELECT idDestino,codReporte,ISNULL(SUM(Tiempo),0) AS TiempoAsignado 
FROM vTiquetesCargadores WHERE idRegistro='$idRegistro' AND idActividad='00000000-0000-0000-0000-000000000000'
GROUP BY idDestino,codReporte";
$resul=sqlsrv_query($conn,$sql,$params,$options);
$rows = sqlsrv_num_rows($resul);
if($rows>0){
    while($aa = sqlsrv_fetch_array($resul)){
        $codReporte = $aa['codReporte'];
        $idDestino = $aa['idDestino'];
        $TiempoAsignado = number_format($aa['TiempoAsignado'],1);
    }
}
$sql = "SELECT ISNULL(SUM(Tiempo),0) AS TiempoConsumido 
FROM vTiquetesCargadores WHERE idRegistro='$idRegistro' AND idActividad<>'00000000-0000-0000-0000-000000000000'";
$resul=sqlsrv_query($conn,$sql,$params,$options);
$rows = sqlsrv_num_rows($resul);    
if($rows>0){
    while($aa = sqlsrv_fetch_array($resul)){
        $TiempoConsumido = number_format($aa['TiempoConsumido'],1);
    }
}
$TiempoRestante = $TiempoAsignado-$TiempoConsumido;
if($TiempoRestante==0){
	// contiene el mensaje a mostrar al usuario
    $message="";
    // si vale 1 todo ha ido bien, 0 ha habido algun problema
    $ok=1;
    if($_FILES){
        foreach($_FILES as $file){
            if($file["error"]==UPLOAD_ERR_OK){
                // movemos el archivo a la carpeta donde se encuentra este archivo
                move_uploaded_file($file["tmp_name"], "./".$file["name"]);
                $message.="La imagen ".$file["name"]." se ha recibido correctamente\n";
                $message.="El formato es ".end(explode(".", $file["name"]))."\n";
                $message.="el tamaño es ".$file["size"]." KB \n";
                $extension = end(explode(".", $file["name"]));
                $binario_contenido  = base64_encode(file_get_contents($file["name"]));
            }
        }
        $sql = "UPDATE TiquetesCargadores SET estado=3,imgDOC='$binario_contenido',extDOC='$extension' WHERE idRegistro='$idRegistro'";
	    $res = sqlsrv_query($conn,$sql);
    }else{
        $message.="No se ha recibido ningun archivo\n";
        $ok=0;
    }
    # devolvemos un json con la información
    echo json_encode(array("ok"=>$ok,"message"=>$message));
}
/*
// contiene el mensaje a mostrar al usuario
$message="";
 
// si vale 1 todo ha ido bien, 0 ha habido algun problema
$ok=1;

if($_POST["idRegistro"])
{
    $message.="El nombre recibido es: ".$_POST["idRegistro"]."\n";
}else{
    $message.="No se ha recibido el nombre\n";
    $ok=0;
}
 
if($_FILES)
{
    foreach($_FILES as $file)
    {
        if($file["error"]==UPLOAD_ERR_OK)
        {
            // movemos el archivo a la carpeta donde se encuentra este archivo
            move_uploaded_file($file["tmp_name"], "./".$file["name"]);
            $message.="La imagen ".$file["name"]." se ha recibido correctamente\n";
            $message.="El formato es ".end(explode(".", $file["name"]))."\n";
            $message.="el tamaño es ".$file["size"]." KB \n";
            $binario_contenido  = base64_encode(file_get_contents($archivotempo));
        }
    }
}else{
    $message.="No se ha recibido ningun archivo\n";
    $ok=0;
}
 
# devolvemos un json con la información
echo json_encode(array("ok"=>$ok,"message"=>$message));
*/
?>