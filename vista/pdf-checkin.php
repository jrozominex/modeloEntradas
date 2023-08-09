<?php

include '../modelo/conexion.php';
require('../fpdf/mc_table.php');

$Codigo = $_GET['Codigo'];
$Total_factura = $_GET['Total'];
$fecha_salida = date('Y-m-d');
ini_set ('date.timezone', 'America/Bogota');
$hora_salida = date("g:i A");

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

//CHECKIN
$consult1 = "SELECT * FROM checkin WHERE Codigo='$Codigo'";
$quer1 = conexion()->query($consult1);
$ro1 = $quer1->fetch_assoc();
//HABITACIÓN
$consult2 = "SELECT * FROM habitacion WHERE Codigo='" . $ro1['Num_habitacion'] . "' ";
$quer2 = conexion()->query($consult2);
$ro2 = $quer2->fetch_assoc();
//HABITACIÓN
$consult3 = "SELECT * FROM tipohab WHERE Codigo='" . $ro2['Categoria'] . "' ";
$quer3 = conexion()->query($consult3);
$ro3 = $quer3->fetch_assoc();

//+++++++++++++++++++++++++HEADER++++++++++++++++++++++++++++++++++
//'L','mm','letter'
$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 15);
// Logo
$pdf->Image('../Imagenes/Factura.png', 10, 12, 40);
$pdf->Ln(10);

// Movernos a la derecha
$pdf->Cell(60);
// TítuloiCell(90, 5, utf8_decode('SERVICIO NACIONAL DE APRENDIZAJE SENA GESTION DE INFRAESTRUCTURA Y LOGISTICA FORMATO SOLICITUD DE BIENES'), 0, 'C');
// Salto d
$pdf->MultiCell(90, 3, utf8_decode('HOSPEDAJE DON MARIO'), 0, 'C');
$pdf->Ln(4);
$pdf->Cell(60);
$pdf->MultiCell(90, 3, utf8_decode('CHECKOUT - 0').$ro1['Codigo'], 0, 'C');
// Salto de línea
//Lineas Color negro del header
$pdf->SetDrawColor(0, 0, 0);

$pdf->Line(10, 11, 10, 53); //Vertical left
$pdf->Line(200, 11, 200, 53); //Vertical rigth

$pdf->Line(10, 11, 200, 11);  //Horizontal top
$pdf->Line(10, 53, 200, 53);  //Horizontal buttom
//+++++++++++++++++++++++++FORMULARIO++++++++++++++++++++++++++++++++++
//Lineas Color blanco
$pdf->Ln(15);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(255, 255, 255);
//Fila 1
$pdf->Ln(14);
$pdf->Cell(30, 6, 'Fecha ingreso:', 0, 0, 'C', 1);
$pdf->Cell(30, 6, $ro1['Fecha_ingreso'], 'B', 0, 'C', 1);
$pdf->Cell(1);
$pdf->Cell(30, 6, 'Fecha salida:', 0, 0, 'C', 1);
$pdf->Cell(30, 6, $fecha_salida, 'B', 0, 'C', 1);
$pdf->Cell(1);
$pdf->Cell(30, 6, 'Hora salida:', 0, 0, 'C', 1);
$pdf->Cell(30, 6, $hora_salida, 'B', 1, 'C', 1);
//Fila 2
$pdf->Ln(4);
$pdf->Cell(5);
$pdf->Cell(20, 6, 'Nombres:', 0, 0, 'C', 1);
$pdf->Cell(60, 6, utf8_decode($ro1['Nombres'].' '.$ro1['Apellidos']), 'B', 0, 'C', 1);
$pdf->Cell(1);
$pdf->Cell(45, 6, 'Numero de documento:', 0, 0, 'C', 1);
$pdf->Cell(40, 6, $ro1['Num_documento'], 'B', 0, 'C', 1);
$pdf->Ln(4);
//Fila 3
if ($ro1['Nombre_acompanante'] != NULL && $ro1['Documento_acompanante'] != 0) {
    $pdf->Ln(6);
    $pdf->Cell(1);
    $pdf->Cell(40, 6, utf8_decode('Nombre acompañante:'), 0, 0, 'C', 1);
    $pdf->Cell(60, 6, utf8_decode($ro1['Nombre_acompanante']), 'B', 0, 'C', 1);
    $pdf->Cell(1);
    $pdf->Cell(48, 6, utf8_decode('Documento acompañante:'), 0, 0, 'C', 2);
    $pdf->Cell(30, 6, $ro1['Documento_acompanante'], 'B', 1, 'C', 1);

//Fila 5
    $pdf->Ln(6);
}else {
    $pdf->Ln(6);
}
$pdf->Cell(5);
$pdf->Cell(35, 6, utf8_decode('Número habitación:'), 0, 0, 'C', 1);
$pdf->Cell(2.5);
$pdf->Cell(15, 6, $ro1['Num_habitacion'], 'B', 0, 'C', 1);
$pdf->Cell(1.5);
$pdf->Cell(20, 6, 'Categoria:', 0, 0, 'C', 1);
$pdf->Cell(50, 6, $ro3['Nombre'], 'B', 0, 'C', 1);
$pdf->Cell(2);
$pdf->Cell(30, 6, utf8_decode('Precio habitación:'), 0, 0, 'C', 1);
$pdf->Cell(2.5);
$pdf->Cell(20, 6, '$ '.$ro2['Precio'], 'B', 1, 'C', 1);
$pdf->Ln(10);

$consulta2 = "SELECT SUM(Subtotal) as TotalPrecios FROM detalle_checkout WHERE Id_checkin='$Codigo'";
$resultado2 = conexion()->query($consulta2);
$fila = $resultado2->fetch_assoc(); //que te devuelve un array asociativo con el nombre del campo
$TotalPrecios = $fila['TotalPrecios'];
$total = $Total_factura - $TotalPrecios;
$consulta = "SELECT * FROM detalle_checkout WHERE Id_checkin='$Codigo'";
$query = conexion()->query($consulta);
if ($query->num_rows > 0) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->MultiCell(180, 3, utf8_decode('PRODUCTOS CONSUMIDOS'), 0, 'C');
//+++++++++++++++++++++++++TABLA++++++++++++++++++++++++++++++++++
//Titulos de la tabla
    $pdf->Ln(4);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(232, 232, 232);
    $pdf->SetFont('Arial', 'B', 10);

    $pdf->Cell(10, 6, 'Item', 1, 0, 'C', 1);
    $pdf->Cell(30, 6, 'Fecha', 1, 0, 'C', 1);
    $pdf->Cell(35, 6, 'Producto', 1, 0, 'C', 1);
    $pdf->Cell(26, 6, 'Precio unidad', 1, 0, 'C', 1);
    $pdf->Cell(26, 6, 'Cantidad', 1, 0, 'C', 1);
    $pdf->Cell(30, 6, 'Total consumido', 1, 0, 'C', 1);
    $pdf->Ln(6);

//contenido de la tabla
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetWidths(array(10, 30, 35, 26, 26, 30));

    $num = 1;


    while ($row = $query->fetch_assoc()) {
        $pdf->Row1(array($num, utf8_decode($row['Fecha']), utf8_decode($row['Nombre']), '$ ' . utf8_decode($row['Precio']), '   ' . utf8_decode($row['Cantidad']), '$ ' . utf8_decode($row['Subtotal'])));
        $num++;
    }
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->MultiCell(255, 6, '        Total consumido:   $ ' . $TotalPrecios, 0, 'C');
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->MultiCell(270, 6, utf8_decode('Habitación:        $ ' . $total), 0, 'C');
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->MultiCell(263, 6, 'TOTAL FACTURA:   $ ' . $Total_factura, 0, 'C');
}else {
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->MultiCell(255, 6, '              TOTAL:                    $ ' . $total, 0, 'C');
}
$pdf->Output();

