<?php
$Servidor = "10.1.1.3";
$Usuario = "sa";
$Password = "Emdeso2020";

$BaseDeDatos = "Traz";
$connectionInfo = array("Database" => $BaseDeDatos, "UID" => $Usuario, "PWD" => $Password);
$conn = sqlsrv_connect($Servidor, $connectionInfo) or die(" Error ::: !!! El servidor no puede conectar con la base de datos, verifique que los datos del servidor, usuario y password sean los correctos !!! ");
$param = "";
$option = "";


//$n_rows=sqlsrv_num_rows($stmt);
//$row=0;
//$n_col= sqlsrv_num_fields($stmt);

$url = $_SERVER['REQUEST_URI'];
$comp = explode('/', $url); //extensión de la URL file.php/extension
$post_data = file_get_contents('php://input');
// $post_data = '1317B661-9928-435F-9FD5-63A240652F7B';
// echo $post_data;
// echo $comp[4];
switch ($comp[4]) {
    case 'puertos':
        $sql = "SELECT  vVentasList.idLoadingPort, vVentasList.LoadingPort FROM rpt.Proy_CostoVentas INNER JOIN vVentasList ON rpt.Proy_CostoVentas.numerotransaccion = vVentasList.numerotransaccion GROUP BY vVentasList.idLoadingPort, vVentasList.LoadingPort";
        $json = "{\"puertos\":[ ";
        $stmt = sqlsrv_query($conn, $sql);
        if ($stmt) {
            while ($aa = sqlsrv_fetch_array($stmt)) {
                $json .= "{\"id\":\"" . $aa["idLoadingPort"] . "\",\"text\":\"" . $aa["LoadingPort"] . "\"},";
            }
            ;
            $json = substr($json, 0, strlen($json) - 1);
        }
        ;
        $json .= "]}";
        break;
    case 'ventas':
        $list_record = json_decode($post_data, true);
        $json = "{\"ventas\": [{\"id\": \"general\", \"text\": \"Ventas en modelo\", \"group\": false, \"expanded\": true, \"nodes\": [";
        $sql = "SELECT  rpt.Proy_CostoVentas.numerotransaccion, vVentasList.NumeroOrden, vVentasList.LoadingPort, vVentasList.Cargo, vVentasList.LayCanEnd, vVentasList.LayCanStart, vVentasList.idLoadingPort FROM   rpt.Proy_CostoVentas INNER JOIN vVentasList ON rpt.Proy_CostoVentas.numerotransaccion = vVentasList.numerotransaccion WHERE vVentasList.idLoadingPort='" . $list_record['id'] . "' GROUP BY rpt.Proy_CostoVentas.numerotransaccion, vVentasList.NumeroOrden, vVentasList.LoadingPort, vVentasList.Cargo, vVentasList.LayCanEnd, vVentasList.LayCanStart, vVentasList.idLoadingPort";

         // $sql = "SELECT  rpt.Proy_CostoVentas.numerotransaccion, vVentasList.NumeroOrden, vVentasList.LoadingPort, vVentasList.Cargo, vVentasList.LayCanEnd, vVentasList.LayCanStart, vVentasList.idLoadingPort FROM   rpt.Proy_CostoVentas INNER JOIN vVentasList ON rpt.Proy_CostoVentas.numerotransaccion = vVentasList.numerotransaccion WHERE vVentasList.idLoadingPort='1317B661-9928-435F-9FD5-63A240652F7B' GROUP BY rpt.Proy_CostoVentas.numerotransaccion, vVentasList.NumeroOrden, vVentasList.LoadingPort, vVentasList.Cargo, vVentasList.LayCanEnd, vVentasList.LayCanStart, vVentasList.idLoadingPort";




        $stmt = sqlsrv_query($conn, $sql);
        if ($stmt) {
            while ($aa = sqlsrv_fetch_array($stmt)) {
                $json .= "{\"id\":\"" . $aa['numerotransaccion'] . "\",\"text\":\"" . $aa['NumeroOrden'] . "\",\"icon\": \"fa fa-pencil-square-o\"},";
            };
            $json = substr($json, 0, strlen($json) - 1);
            $json .= "]}]}";
        }
        break;
    case 'costos':
        $list_record = json_decode($post_data, true);
        $sql="select rpt.jsonModeloCostos ('".$list_record['id']."') as json";
        $stmt = sqlsrv_query($conn, $sql);
        if ($stmt) {
            while ($aa = sqlsrv_fetch_array($stmt)) {
                $json=$aa['json'];
            }
        }
        break;
    case 'detalle':
      $json = "{";
        $list_record = json_decode($post_data, true);
        $sql="SELECT  Vessel, Cargo, Volume, cast(LayCanStart as varchar(20)) as LayCanStart, cast(LayCanEnd as varchar(20)) as LayCanEnd, cast(DateBeginingProj as varchar(20)) as DateBeginingProj, cast(DateEndingProj as varchar(20)) as DateEndingProj FROM vVentasList WHERE numerotransaccion='".$list_record['id']."'";
        $stmt = sqlsrv_query($conn, $sql);
        if ($stmt) {
            while ($aa = sqlsrv_fetch_array($stmt)) {
                $json.="\"Vessel\":\"".$aa['Vessel']."\",";
                $json.="\"Cargo\":\"".$aa['Cargo']."\",";
                $json.="\"Volume\":".$aa['Volume'].",";
                $json.="\"LayCanStart\":\"".$aa['LayCanStart']."\",";
                $json.="\"LayCanEnd\":\"".$aa['LayCanEnd']."\",";
                $json.="\"DateBeginingProj\":\"".$aa['DateBeginingProj']."\",";
                $json.="\"DateEndingProj\":\"".$aa['DateEndingProj']."\"";
            }
        };        
        $json .="}";
        break;
        default:
            // code...
            break;
    }
    echo utf8_encode($json);
?>