<?php
session_start();
require_once '../modelo/conexion.php';
require('../fpdf/mc_table.php');
error_reporting(0);
if ($_SESSION["logueado"] == TRUE && $_SESSION["usuario"] && ($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR' || $_SESSION["permisoIngresar"] == 'CONSULTAS_OFICINA' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES')){
    $usuario = $_SESSION['usuario'];
    $Fecha = date('d-M-y H:i');
    $Fecha_actual = date('Y-m-d',strtotime($Fecha . ' - 15 days'));
    ini_set('date.timezone', 'America/Bogota');
    $hora = date("g:i A");
    $sql_usuario = "SELECT * FROM Usuarios WHERE idUsuario='$usuario'";
    $result = sqlsrv_query($conn,$sql_usuario);
    while ($row = sqlsrv_fetch_array($result)){
      $Nombre = $row['NombreUsuarioLargo'];
    }
}elseif($_SESSION['permisoIngresar'] == 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES'){
    //header("Location: MantenimientoCargadores.php");
    ?>
    <script type="text/javascript">
        self.location='MantenimientoCargadores.php';
        alert('No tienes permiso para acceder a este ambiente');
    </script>
    <?php
}elseif($_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO'){
  //header("Location: inicio_patio.php");
  ?>
  <script type="text/javascript">
      self.location='inicio_patio.php';
      alert('No tienes permiso para acceder a este ambiente');
  </script>
  <?php
}else{
  //header("Location: ../index.php");
  ?>
    <script type="text/javascript">
        self.location='../index.php';
        alert('Inicia sesión primero');
    </script>
    <?php
  die();
}
/*if ($_GET['idTiquete_consulta'] != 0){
  $tiquete_consu = $_GET['idTiquete_consulta'];  
}
if ($_GET['cliente_pdf'] != 0){
  $cliente_consu = $_GET['cliente_pdf'];  
}
if ($_GET['patio_pdf'] != 0){
  $patio_consu = $_GET['patio_pdf'];
}
if ($_GET['cargador_pdf'] != 0){
  $cargador_consu = $_GET['cargador_pdf'];  
}
if ($_GET['fecha1_pdf'] != 0){
  $fecha1_consu = $_GET['fecha1_pdf'];
}
if ($_GET['fecha2_pdf'] != 0){
  $fecha2_consu = $_GET['fecha2_pdf'];
}*/
$sql_consulta = "";
$fecha_inicio = $_GET['fecha1_pdf'];
$fecha_fin = $_GET['fecha2_pdf'];
if ($_GET['cliente_pdf'] != '0'){
    $cliente_consu = $_GET['cliente_pdf'];
    $sql_consulta = "SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                 Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, 
                 Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.RazonSocial, Proveedores.NombreCorto, Proveedores.idProveedor, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                 Usuarios.NombreUsuarioLargo
            FROM Registro_tique_cargadores INNER JOIN
                 Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                 Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
            WHERE CAST(Registro_tique_cargadores.fecha_ini_jornada as date) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND Proveedores.idProveedor='$cliente_consu'
            ORDER BY Registro_tique_cargadores.cod_reporte DESC";
    $res_consulta = 1;
}else if($_GET['patio_pdf'] != '0'){
    if ($_GET['cargador_pdf'] != '0'){
        $patio = $_GET['patio_pdf'];
        $cargador = $_GET['cargador_pdf'];
        $sql_consulta = "SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                 Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, 
                 Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.RazonSocial, Proveedores.idProveedor, Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                 Usuarios.NombreUsuarioLargo
            FROM Registro_tique_cargadores INNER JOIN
                 Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                 Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
            WHERE CAST(Registro_tique_cargadores.fecha_ini_jornada as date) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND Registro_tique_cargadores.id_patio='$patio' AND Registro_tique_cargadores.id_maquinaria='$cargador'
            ORDER BY Registro_tique_cargadores.cod_reporte DESC";
        $res_consulta = 3;
    }else{
        $patio = $_GET['patio_pdf'];
        $sql_consulta = "SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                 Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, 
                 Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.RazonSocial, Proveedores.idProveedor, Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                 Usuarios.NombreUsuarioLargo
            FROM Registro_tique_cargadores INNER JOIN
                 Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                 Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
            WHERE CAST(Registro_tique_cargadores.fecha_ini_jornada as date) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND Registro_tique_cargadores.id_patio='$patio'
            ORDER BY Registro_tique_cargadores.cod_reporte DESC";
        $res_consulta = 4;
    }
}else if($_GET['patio_pdf'] == '0'){
    if ($_GET['cargador_pdf'] != '0'){
        $patio = $_GET['patio_pdf'];
        $cargador = $_GET['cargador_pdf'];
        $sql_consulta = "SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                 Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, 
                 Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.RazonSocial, Proveedores.idProveedor, Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                 Usuarios.NombreUsuarioLargo
            FROM Registro_tique_cargadores INNER JOIN
                 Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                 Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
            WHERE CAST(Registro_tique_cargadores.fecha_ini_jornada as date) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND Registro_tique_cargadores.id_maquinaria='$cargador'
            ORDER BY Registro_tique_cargadores.cod_reporte DESC";
        $res_consulta = 5;
    }else{
        $sql_consulta = "SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                 Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, 
                 Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.RazonSocial, Proveedores.idProveedor, Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                 Usuarios.NombreUsuarioLargo
            FROM Registro_tique_cargadores INNER JOIN
                 Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                 Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
            WHERE CAST(Registro_tique_cargadores.fecha_ini_jornada as date) BETWEEN '$fecha_inicio' AND '$fecha_fin'
            ORDER BY Registro_tique_cargadores.cod_reporte DESC";
        $res_consulta = 6;
    }
}else if($_GET['cliente_pdf'] == '0'){
    $sql_consulta = "SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, Registro_tique_cargadores.id_usuario, 
                 Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, 
                 Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.RazonSocial, Proveedores.idProveedor, Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, 
                 Usuarios.NombreUsuarioLargo
            FROM Registro_tique_cargadores INNER JOIN
                 Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                 Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
            WHERE CAST(Registro_tique_cargadores.fecha_ini_jornada as date) BETWEEN '$fecha_inicio' AND '$fecha_fin'
            ORDER BY Registro_tique_cargadores.cod_reporte DESC";
    $res_consulta = 2;
}
//echo $res_consulta;
$num = 0;
/*if ($reporte < 10){
$reporte = '00'.$reporte;
}else if ($reporte < 100){
    $reporte = '0'.$reporte;
}else{
    $reporte = $reporte;
}*/

function GenerateWord() {
    //Get a random word
  $nb = rand(3, 10);
  $w = '';
  for ($i = 1; $i <= $nb; $i++)
    $w .= chr(rand(ord('a'), ord('z')));
  return $w;
}

function GenerateSentence() {
    //Get a random sentence
  $nb = rand(1, 10);
  $s = '';
  for ($i = 1; $i <= $nb; $i++)
    $s .= GenerateWord() . ' ';
  return substr($s, 0, -1);
}

//+++++++++++++++++++++++++HEADER++++++++++++++++++++++++++++++++++
//$pdf = new PDF_MC_Table('L','mm',array(80,210));
//$pdf = new PDF_MC_Table('L','mm','A4');
$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 18);
// Logo
$pdf->Cell(15);
$pdf->Image('../imagenes/cargador.jpg', 11, 12, 30);
$pdf->Ln(12);

$pdf->Cell(60);
$pdf->MultiCell(90, 3, utf8_decode('INFORME DE TIQUETES'), 0, 'C');
$pdf->Ln(4);
$pdf->Cell(60);

$pdf->Line(10, 11, 10, 35); //Vertical left
$pdf->Line(200, 11, 200, 35); //Vertical rigth

$pdf->Line(10, 11, 200, 11);  //Horizontal top
$pdf->Line(10, 35, 200, 35);  //Horizontal buttom
//+++++++++++++++++++++++++FORMULARIO++++++++++++++++++++++++++++++++++
//+++++++++++++++++++++++++TABLA++++++++++++++++++++++++++++++++++
//Titulos de la tabla
//$consulta = "SELECT * FROM horometro where id_registro='$tiquete'";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$resultado=sqlsrv_query($conn,utf8_decode($sql_consulta),$params,$options);
$rows=sqlsrv_num_rows($resultado);
if ($rows > 0)
{   $pdf->Ln(18);
    //$pdf->Cell(20);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(232, 232, 232);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(15, 6, utf8_decode('Tique'), 1, 0, 'C', 1);
    $pdf->Cell(30, 6, 'Cliente', 1, 0, 'C', 1);
    $pdf->Cell(40, 6, 'Lugar', 1, 0, 'C', 1);
    $pdf->Cell(30, 6, 'Cargador', 1, 0, 'C', 1);
    $pdf->Cell(28, 6, 'Jornada Inicial', 1, 0, 'C', 1);
    $pdf->Cell(28, 6, 'Jornada Final', 1, 0, 'C', 1);
    $pdf->Cell(15, 6, 'Hrs.', 1, 0, 'C', 1);
    //$pdf->Cell(15, 6, 'Estado', 1, 0, 'C', 1);
    $pdf->Ln(8);
    //contenido de la tabla
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetWidths(array(15, 30, 40, 30, 28, 28, 15/*, 15*/));
    $pdf->SetAligns(array('L','L','L','L','C','C','R'));
    while ($row = sqlsrv_fetch_array($resultado)) {
      $count_horas += $row['horas_trabajadas'];
      if (date_format($row['fecha_fin_jornada'],'Y-m-d H:i:s') == NULL){
          $fecha_format = "0";
          $estado = "En proceso";
      }else{
         $fecha_format = date_format($row['fecha_fin_jornada'],'d-M-y H:i');
         $estado = utf8_decode("Finalizó");
      }
        //$pdf->Cell(20);
        $pdf->Row1(array($row['cod_reporte'], $row['NombreCorto'], $row['Descripcion'], $row['NombreCargador'], date_format($row['fecha_ini_jornada'],'d-M-y H:i'), $fecha_format, number_format($row['horas_trabajadas'],1,',','.')/*, $estado*/));
        $num++;
    }
    $pdf->Ln(3);
    $pdf->Cell(150);
    $pdf->Cell(20, 6, 'Total horas:', 0, 0, 'C', 1);
    $pdf->Cell(15, 6, number_format($count_horas,1,',','.'), 0, 0, 'C', 1);
    $pdf->Ln(30);
    $pdf->Cell(60);
    $pdf->Cell(29, 6, 'Fecha de consulta:', 0, 0, 'C', 1);
    $pdf->Cell(35, 6, $Fecha, 0, 0, 'C', 1);
    $pdf->Ln(8);
    $pdf->SETXY(155,38);
    $pdf->Cell(30, 6, 'Total registros:', 0, 0, 'C', 1);
    $pdf->Cell(10, 6, $num, 0, 0, 'C', 1);
}else{
    $pdf->Ln(12);
    $pdf->Cell(80);
    $pdf->Cell(30, 6, '------ No hay registros de horometros en este tiquete ------', 0, 0, 'C', 1);
}
$pdf->Output();