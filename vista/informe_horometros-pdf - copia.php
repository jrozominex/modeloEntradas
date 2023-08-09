<?php
session_start();
require_once '../modelo/conexion.php';
require('../fpdf/mc_table.php');
//error_reporting(0);
if ($_SESSION["logueado"] == TRUE && $_SESSION["usuario"]) {
    $usuario = $_SESSION['usuario'];
    $Fecha = date('Y-m-d');
    $Hora_actual = date('H:i:s');
    $Fecha_actual = date('Y-m-d',strtotime($Fecha . ' - 15 days'));
    ini_set('date.timezone', 'America/Bogota');
    $hora = date("g:i A");
    $sql_usuario = "SELECT * FROM Usuarios WHERE idUsuario='$usuario'";
    $result = sqlsrv_query($conn,$sql_usuario);
    while ($row = sqlsrv_fetch_array($result)){
      $Nombre = $row['NombreUsuarioLargo'];
    }
}else{
    header("Location: ../index.php");
    die();
}
$distribuir = 0;
$tiquete = $_GET['idTiquete'];
$sql_tiquete = "SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, 
                Registro_tique_cargadores.id_usuario, Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, 
                Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.RazonSocial, 
                Proveedores.idProveedor, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, Usuarios.NombreUsuarioLargo, Registro_tique_cargadores.Observaciones
    FROM Registro_tique_cargadores INNER JOIN
         Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
         Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
         Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
         Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
    WHERE Registro_tique_cargadores.id_registro='$tiquete'";
$result = sqlsrv_query($conn,utf8_decode($sql_tiquete));
while($tiques = sqlsrv_fetch_array($result)){
    $id_maquinaria = $tiques['id_maquinaria'];
    $jornada_inicial = $tiques['fecha_ini_jornada'];
    $jornada_final = $tiques['fecha_fin_jornada'];
    $reporte = $tiques['cod_reporte'];
    $patio = $tiques['Descripcion'];
    $cargador = $tiques['NombreCargador'];
    $id = $tiques['Identificacion'];
    $cliente = $tiques['RazonSocial'];
    $registrado_por = $tiques['NombreUsuarioLargo'];
    $horas_trabajadas = $tiques['horas_trabajadas'];
    $Observaciones = $tiques['Observaciones'];
}
$num = 0;
if($reporte < 10){
    $reporte = '00'.$reporte;
}elseif($reporte < 100){
    $reporte = '0'.$reporte;
}else{
    $reporte = $reporte;
}

function GenerateWord(){
    //Get a random word
    $nb = rand(3, 10);
    $w = '';
    for ($i = 1; $i <= $nb; $i++)
        $w .= chr(rand(ord('a'), ord('z')));
    return $w;
}

function GenerateSentence(){
    //Get a random sentence
    $nb = rand(1, 10);
    $s = '';
    for ($i = 1; $i <= $nb; $i++)
        $s .= GenerateWord() . ' ';
    return substr($s, 0, -1);
}

//+++++++++++++++++++++++++HEADER++++++++++++++++++++++++++++++++++

$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 18);
// Logo
$pdf->Cell(15);
$pdf->Image('../imagenes/cargador.jpg', 11, 12, 60);
$pdf->Ln(20);

$pdf->Cell(60);
$pdf->MultiCell(90, 3, utf8_decode('TIQUETE N° '.$reporte), 0, 'C');
$pdf->Ln(4);
$pdf->Cell(60);

$pdf->Line(10, 11, 10, 53); //Vertical left
$pdf->Line(200, 11, 200, 53); //Vertical rigth

$pdf->Line(10, 11, 200, 11);  //Horizontal top
$pdf->Line(10, 53, 200, 53);  //Horizontal buttom
//+++++++++++++++++++++++++FORMULARIO++++++++++++++++++++++++++++++++++
//Lineas Color blanco
$pdf->Ln(20);
//$pdf->Cell(55);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(255, 255, 255);
//Fila 1
$pdf->Cell(30, 6, 'Registrado por:', 0, 0, 'B', 1);
$pdf->Cell(80, 6, $registrado_por, 'B', 0, 'C', 1);
$pdf->Ln(10);
//$pdf->Cell(24);
$pdf->Cell(35, 6, 'Jornada inicial:', 0, 0, 'C', 1);
$pdf->Cell(45, 6, date_format($jornada_inicial, 'Y-m-d  H:i:s'), 'B', 0, 'C', 1);
$pdf->Cell(1);
$pdf->Cell(35, 6, 'Jornada final:', 0, 0, 'C', 1);
$pdf->Cell(45, 6,date_format($jornada_final, 'Y-m-d  H:i:s'), 'B', 0, 'C', 1);
$pdf->Ln(10);
//$pdf->Cell(40);
$pdf->Cell(15, 6, 'Patio:', 0, 0, 'C', 1);
$pdf->Cell(50, 6, $patio, 'B', 0, 'C', 1);
$pdf->Cell(1);
$pdf->Cell(25, 6, 'Cargador:', 0, 0, 'C', 1);
$pdf->Cell(40, 6, $cargador." - ".$id, 'B', 0, 'C', 1);
$pdf->Ln(10);
//$pdf->Cell(45);
$pdf->Cell(15, 6, 'Cliente:', 0, 0, 'C', 1);
$pdf->Cell(90, 6, $cliente, 'B', 0, 'C', 1);
$pdf->Cell(24);
$pdf->Cell(25, 6, 'Total horas:', 0, 0, 'C', 1);
$pdf->Cell(15, 6, $horas_trabajadas, 'B', 0, 'C', 1);
$pdf->Ln(10);
//+++++++++++++++++++++++++TABLA++++++++++++++++++++++++++++++++++
$sql = "SELECT * FROM horometro_descuento_cargadores 
        INNER JOIN Usuarios ON horometro_descuento_cargadores.idusuario = Usuarios.idUsuario
        WHERE idRegistro='$tiquete'";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
$rows=sqlsrv_num_rows($res);
if ($rows > 0){
    $pdf->Ln(4);
    $pdf->Cell(25, 6, 'DESCUENTOS:', 0, 0, 'C', 1);
    $pdf->Ln(5);
    //$pdf->Cell(20);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(232, 232, 232);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(55, 6, 'Realizado por:', 1, 0, 'C', 1);
    $pdf->Cell(38, 6, 'Fecha Registro', 1, 0, 'C', 1);
    $pdf->Cell(23, 6, 'Descuento', 1, 0, 'C', 1);
    $pdf->Cell(74, 6, 'Descripcion', 1, 0, 'C', 1);
    $pdf->Ln(8);
    //contenido de la tabla
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetWidths(array(55, 38, 23, 74));

    while($desc = sqlsrv_fetch_array($res)){
        $pdf->Row1(array($desc['NombreUsuarioLargo'], date_format($desc['fecharegistro'],'Y-m-d H:i:s'), $desc['valor_descuento']." Horo.", utf8_encode($desc['descripcion'])));
    }
    $pdf->Ln(6);
    $pdf->Cell(240, 0, '-----------------------------------------------------------------------------------------------------------------------------------------------------------------', 0, 0, 'B', 1);
    $pdf->Ln(2);
    $pdf->Cell(240, 0, '-----------------------------------------------------------------------------------------------------------------------------------------------------------------', 0, 0, 'B', 1);
    $pdf->Ln(6);
}
//Titulos de la tabla
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(25, 6, 'ACTIVIDADES:', 0, 0, 'C', 1);
$consulta = "SELECT horometro.fecha_cierre_horometro ,horometro.id_horometro, horometro.id_registro, horometro.horometro_inicial, 
                horometro.horometro_final, horometro.total_horas, horometro.idActividad, horometro.fecha_registro_horometro, 
                Actividades.Descripcion, SubActividades_cargadores.Descripcion as Descripcion_sub, horometro.observaciones
            FROM horometro LEFT JOIN Actividades ON horometro.idActividad = Actividades.idActividad
            LEFT JOIN SubActividades_cargadores ON horometro.idSubActividad=SubActividades_cargadores.idSubActividad
            WHERE id_registro='$tiquete'";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
$rows=sqlsrv_num_rows($resultado);
if ($rows > 0)
{   $pdf->Ln(5);
    //$pdf->Cell(20);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(232, 232, 232);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(60, 6, 'Actividad', 1, 0, 'C', 1);
    $pdf->Cell(40, 6, 'SubActividad', 1, 0, 'C', 1);
    $pdf->Cell(35, 6, 'Horometro inicial', 1, 0, 'C', 1);
    $pdf->Cell(35, 6, 'Horometro final', 1, 0, 'C', 1);
    $pdf->Cell(20, 6, 'Horas', 1, 0, 'C', 1);
    $pdf->Ln(8);

    //contenido de la tabla
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetWidths(array(60, 40, 35, 35, 20));
    while ($row = sqlsrv_fetch_array($resultado)){
        if ($row['horometro_inicial'] == null && $row['horometro_final'] == null){
            //$pdf->Cell(20);
            $pdf->Row1(array($row['Descripcion'], $row['Descripcion_sub'], "NO APLICA", "NO APLICA", number_format($row['total_horas'],2,',','.')));
            $num++;
        }else{
            if($row['Descripcion'] == null){
                $distribuir++;
                //$pdf->Cell(20);
                $pdf->Row1(array("FALTA DISTRIBUIR TIEMPOS", "FALTA DISTRIBUIR TIEMPOS", $row['horometro_inicial'], $row['horometro_final'], number_format($row['total_horas'],2,',','.')));
                $num++;
            }else{
                //$pdf->Cell(20);
                $pdf->Row1(array($row['Descripcion'], $row['Descripcion_sub'], $row['horometro_inicial'], $row['horometro_final'], number_format($row['total_horas'],2,',','.')));
                $num++;
            }
        }
    }    
    $sql1 = "SELECT * FROM tiempos_cargadores_actividad 
            LEFT JOIN Clasificacion ON tiempos_cargadores_actividad.idClasificacion = Clasificacion.idClasificacion
    WHERE idMaquinaria='$id_maquinaria'";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $resultado1=sqlsrv_query($conn,utf8_decode($sql1),$params,$options);
    $rows=sqlsrv_num_rows($resultado1);
    if ($rows > 0)
    {   $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Cell(25, 6, utf8_decode('DISTRIBUCIÓN:'), 0, 0, 'C', 1);
        $pdf->Ln(5);
        //$pdf->Cell(20);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(232, 232, 232);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(48, 6, 'Producto', 1, 0, 'C', 1);
        $pdf->Cell(20, 6, utf8_encode('N. paladas'), 1, 0, 'C', 1);
        $pdf->Cell(22, 6, 'TMxPalada', 1, 0, 'C', 1);
        $pdf->Cell(28, 6, 'TiempoxPalada', 1, 0, 'C', 1);
        $pdf->Cell(20, 6, 'Total TM', 1, 0, 'C', 1);
        $pdf->Cell(20, 6, 'Horometro', 1, 0, 'C', 1);
        $pdf->Cell(32, 6, 'Horas Trabajadas', 1, 0, 'C', 1);
        $pdf->Ln(8);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetWidths(array(48, 20, 22, 28, 20, 20, 32));
        while($times = sqlsrv_fetch_array($resultado1)){
            if($times['Descripcion'] == null){
                //$totalTM = $times['cantidad']*$times['TMxPalada'];
                //$pdf->Cell(20);
                $pdf->Row2(array("CARGUE", $times['cantidad']." UND", "0 TM", $times['tiempopromedio']." Seg", $times['TM_total']." TM", $times['tiempohorometro']." Horo", $times['tiempohora']." Hrs"));
            }else{
                $totalTM = $times['cantidad']*$times['TMxPalada'];
                //$pdf->Cell(20);
                $pdf->Row2(array($times['Descripcion'], $times['cantidad']." UND", $times['TMxPalada']." TM", $times['tiempopromedio']." Seg", $times['TM_total']." TM", $times['tiempohorometro']." Horo", $times['tiempohora']." Hrs"));
                //$num++;
            }
        }
    }
    $pdf->Ln(6);
    $pdf->Cell(30, 6, 'Observaciones:', 0, 1, 'C', 1);
//    $pdf->Ln(8);
    //$pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(255, 255, 255);
    if($Observaciones != null){
        $pdf->MultiCell(180, 4, $Observaciones, 0, 0, 'C');
    }else{
        $pdf->Ln(4);
        $pdf->Cell(90);
        $pdf->Cell(20, 4, "NO HAY OBSERVACIONES.", 0, 0, 'C');
        $pdf->Ln(4);
    }
    $pdf->Ln(10);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(150);
    $pdf->Cell(30, 6, 'Total actividades:', 0, 0, 'C', 1);
    $pdf->Cell(10, 6, $num, 0, 0, 'C', 1);
}else{
    $pdf->Ln(12);
    $pdf->Cell(80);
    $pdf->Cell(30, 6, '------ No hay registros de horometros en este tiquete ------', 0, 0, 'C', 1);
}
$pdf->Ln(20);
$pdf->Cell(55);
$pdf->Cell(35, 6, 'Fecha de consulta:', 0, 0, 'C', 1);
$pdf->Cell(45, 6, $Fecha." Hora : ".$Hora_actual, 0, 0, 'C', 1);

$pdf->Output();