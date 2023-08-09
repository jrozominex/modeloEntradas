<?php
session_start();
require_once '../modelo/conexion.php';
require('../fpdf/mc_table.php');
//error_reporting(0);
if ($_SESSION["logueado"] == TRUE && $_SESSION["usuario"] && ($_SESSION['permisoIngresar'] != 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] != 'MECANICO_CARGADORES')){
    $usuario = $_SESSION['usuario'];
    $Fecha = date('d-M-Y');
    $Hora_actual = date('h:i');
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
$distribuir = 0;
$tiquete = $_GET['idTiquete'];
$tipo_informe = $_GET['tipo_informe'];
$sql_tiquete = "SELECT Registro_tique_cargadores.horometro_fin, Registro_tique_cargadores.horometro_ini, Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, 
                Registro_tique_cargadores.id_usuario, Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, 
                Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.RazonSocial, 
                Proveedores.idProveedor, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, Usuarios.NombreUsuarioLargo, Registro_tique_cargadores.Observaciones, Registro_tique_cargadores.remision,
                Equipos.idPropietario
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
    $horometro_ini = $tiques['horometro_ini'];
    $horometro_fin = $tiques['horometro_fin'];
    $reporte = $tiques['cod_reporte'];
    $remision = $tiques['remision'];
    $patio = $tiques['Descripcion'];
    $cargador = $tiques['NombreCargador'];
    $PropietarioCargador = $tiques['idPropietario'];
    $id = $tiques['Identificacion'];
    $cliente = $tiques['RazonSocial'];
    $registrado_por = $tiques['NombreUsuarioLargo'];
    $horas_trabajadas = $tiques['horas_trabajadas'];
    $Observaciones = $tiques['Observaciones'];
}
$num = 0;
$num1 = 0;
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
$pdf->Image('../imagenes/cargador.jpg', 11, 12, 30);
$pdf->Ln(6);

$pdf->Cell(60);
$pdf->MultiCell(90, 3, utf8_decode('TIQUETE N° '.$reporte/*.' REMISION '.$remision*/), 0, 'C');
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60);
$pdf->MultiCell(90, 3, utf8_decode(/*'TIQUETE N° '.$reporte.*/' REMISION '.$remision), 0, 'C');
$pdf->Ln(4);
$pdf->Cell(60);

$pdf->Line(10, 11, 10, 35); //Vertical left
$pdf->Line(200, 11, 200, 35); //Vertical rigth

$pdf->Line(10, 11, 200, 11);  //Horizontal top
$pdf->Line(10, 35, 200, 35);  //Horizontal buttom
//+++++++++++++++++++++++++FORMULARIO++++++++++++++++++++++++++++++++++
//Lineas Color blanco
$pdf->Ln(10);
//$pdf->Cell(55);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(255, 255, 255);
//Fila 1
$pdf->Cell(30, 6, 'Registrado por:', 0, 0, 'B', 1);
$pdf->Cell(80, 6, $registrado_por, 'B', 0, 'C', 1);
/*$pdf->Ln(8);
$pdf->Cell(30, 6, 'Verificado por:', 0, 0, 'B', 1);
$pdf->Cell(80, 6, $registrado_por, 'B', 0, 'C', 1);*/
$pdf->Ln(10);
//$pdf->Cell(24);
$pdf->Cell(28, 6, 'Jornada inicial:', 0, 0, 'C', 1);
$pdf->Cell(40, 6, date_format($jornada_inicial, 'd-M-y  h:i'), 'B', 0, 'C', 1);
$pdf->Cell(1);
$pdf->Cell(28, 6, 'Jornada final:', 0, 0, 'C', 1);
$pdf->Cell(40, 6,date_format($jornada_final, 'd-M-y  h:i'), 'B', 0, 'C', 1);
$pdf->Ln(10);
//$pdf->Cell(24);
$pdf->Cell(32, 6, 'Horometro inicial:', 0, 0, 'C', 1);
$pdf->Cell(20, 6, number_format($horometro_ini,1,',','.'), 'B', 0, 'C', 1);
$pdf->Cell(1);
$pdf->Cell(32, 6, 'Horometro final:', 0, 0, 'C', 1);
$pdf->Cell(20, 6, number_format($horometro_fin,1,',','.'), 'B', 0, 'C', 1);
//$pdf->Cell(10);
$pdf->Cell(25, 6, 'Total horas:', 0, 0, 'C', 1);
$pdf->Cell(15, 6, number_format($horas_trabajadas,1,',','.'), 'B', 0, 'C', 1);
$pdf->Ln(10);
//$pdf->Cell(40);
$pdf->Cell(12, 6, 'Patio:', 0, 0, 'C', 1);
$pdf->Cell(50, 6, $patio, 'B', 0, 'C', 1);
$pdf->Cell(1);
$pdf->Cell(25, 6, 'Cargador:', 0, 0, 'C', 1);
$pdf->Cell(40, 6, $cargador." - ".$id, 'B', 0, 'C', 1);
$pdf->Ln(10);
//$pdf->Cell(45);
$pdf->Cell(15, 6, 'Cliente:', 0, 0, 'C', 1);
$pdf->Cell(90, 6, $cliente, 'B', 0, 'C', 1);
$pdf->Ln(10);
//+++++++++++++++++++++++++TABLA++++++++++++++++++++++++++++++++++
if($PropietarioCargador == '4A442AA8-6532-4F4F-8CED-51A6999DDB5E'){
    $sql = "SELECT horometro.fecha_cierre_horometro ,horometro.id_horometro, horometro.id_registro, horometro.horometro_inicial, 
            horometro.horometro_final, horometro.total_horas, horometro.idActividad, horometro.fecha_registro_horometro, 
            Actividades.Descripcion, SubActividades_cargadores.Descripcion as Descripcion_sub, horometro.observaciones
            FROM horometro LEFT JOIN
            Actividades ON horometro.idActividad = Actividades.idActividad
            LEFT JOIN SubActividades_cargadores ON horometro.idSubActividad=SubActividades_cargadores.idSubActividad
           WHERE horometro.id_registro='". $_GET['idTiquete'] ."' AND horometro.horometro_inicial IS NOT NULL
           ORDER BY horometro.horometro_inicial";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows=sqlsrv_num_rows($res);
    if ($rows > 0){
        $pdf->Ln(1);
        $pdf->Cell(25, 6, 'TIEMPOS:', 0, 0, 'C', 1);
        $pdf->Ln(5);
        //$pdf->Cell(20);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(232, 232, 232);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(35, 6, 'Horometro Inicial', 1, 0, 'C', 1);
        $pdf->Cell(35, 6, 'Horometro Final', 1, 0, 'C', 1);
        $pdf->Cell(30, 6, 'Tiempo', 1, 0, 'C', 1);
        $pdf->Ln(8);
        //contenido de la tabla
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetWidths(array(35, 35, 30));
        //$pdf->SetWidths(array(35, 35));
        while($desc = sqlsrv_fetch_array($res)){
            $horas = $desc['horometro_final'] - $desc['horometro_inicial'];
            $pdf->Row1(array(number_format($desc['horometro_inicial'],1), number_format($desc['horometro_final'],1), number_format($horas,1)));
            //$pdf->Row1(array(number_format($desc['horometro_inicial'],1), number_format($desc['horometro_final'],1)));
        }
        $pdf->Ln(2);
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql = "SELECT * FROM horometro_descuento_cargadores 
        /*INNER JOIN Usuarios ON horometro_descuento_cargadores.idusuario = Usuarios.idUsuario*/
        WHERE idRegistro='$tiquete'";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
$rows=sqlsrv_num_rows($res);
if ($rows > 0){
    $pdf->Ln(1);
    $pdf->Cell(25, 6, 'DESCUENTOS:', 0, 0, 'C', 1);
    $pdf->Ln(5);
    //$pdf->Cell(20);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(232, 232, 232);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(-3);
    $pdf->Cell(30, 6, 'Fecha Registro', 1, 0, 'C', 1);
    $pdf->Cell(23, 6, 'Tiempo', 1, 0, 'C', 1);
    if ($patio == 'LA LEJIA'){
        $pdf->Cell(18, 6, 'Tm', 1, 0, 'C', 1);
    } 
    $pdf->Cell(123, 6, 'Descripcion', 1, 0, 'C', 1);
    $pdf->Ln(8);
    //contenido de la tabla
    $pdf->SetFont('Arial', '', 10);
    if ($patio == 'LA LEJIA'){
        $pdf->SetWidths(array(30, 23, 18, 123));
        while($desc = sqlsrv_fetch_array($res)){
            $pdf->Cell(-3);
            $pdf->Row1(array(date_format($desc['fecharegistro'],'d-M-y h:i'), number_format($desc['valor_descuento'],1)." Horo.", number_format($desc['tm_despacho'],2),utf8_encode($desc['descripcion'])));
        }
    }else{
        $pdf->SetWidths(array(30, 23, 123));
        while($desc = sqlsrv_fetch_array($res)){
            $pdf->Cell(-3);
            $pdf->Row1(array(date_format($desc['fecharegistro'],'d-M-y h:i'), number_format($desc['valor_descuento'],1)." Horo.", utf8_encode($desc['descripcion'])));
        }
    }

    
    $pdf->Ln(2);
}//+++++++++++++++++++++++++TABLA++++++++++++++++++++++++++++++++++
if($tipo_informe == 2){
    $consulta = "SELECT * FROM horometro WHERE id_registro='$tiquete' AND idSubActividad='EF629169-C06B-4414-AD73-97E3B76628F4'";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $res=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
    $rows=sqlsrv_num_rows($res);
    if ($rows > 0){
        //$pdf->Ln(4);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Cell(15, 6, 'STANDBY:', 0, 0, 'C', 1);
        $pdf->Ln(5);
        //$pdf->Ln(5);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(232, 232, 232);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(-3);
        $pdf->Cell(30, 6, 'Fecha Registro', 1, 0, 'C', 1);
        $pdf->Cell(23, 6, 'Tiempo', 1, 0, 'C', 1);
        $pdf->Cell(140, 6, 'Descripcion', 1, 0, 'C', 1);
        $pdf->Ln(8);
        //contenido de la tabla
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetWidths(array(30, 23, 140));
        while($desc = sqlsrv_fetch_array($res)){
            $pdf->Cell(-3);
            //$pdf->Cell(10, 6, $desc['total_horas'], 'B', 0, 'C', 1);
            $pdf->Row1(array(date_format($desc['fecha_registro_horometro'],'d-M-y h:i'), $desc['total_horas']." Horo.", utf8_encode($desc['Observaciones'])));
        }
        $pdf->Ln(2);
    }
    //Titulos de la tabla
    $consulta = "SELECT Actividades.Descripcion, subactividades_cargadores.Descripcion AS Descripcion_sub, Clasificacion.Descripcion AS Clasificacion, 
    tiempos_cargadores_actividad.tiempohorometro AS total_horas, tiempos_cargadores_actividad.TM_total
    FROM tiempos_cargadores_actividad INNER JOIN
         subactividades_cargadores ON tiempos_cargadores_actividad.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
         Actividades ON subactividades_cargadores.idActividad = Actividades.idActividad LEFT JOIN
         Clasificacion ON tiempos_cargadores_actividad.idClasificacion = Clasificacion.idClasificacion
    WHERE (tiempos_cargadores_actividad.idRegistro = '$tiquete') AND (tiempos_cargadores_actividad.tipo_tarifa <> 1) AND
        subactividades_cargadores.idSubactividad!='EF629169-C06B-4414-AD73-97E3B76628F4'
    ORDER BY Actividades.Descripcion, Descripcion_sub";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $resultado=sqlsrv_query($conn,utf8_decode($consulta),$params,$options);
    $rows=sqlsrv_num_rows($resultado);
    if ($rows > 0)
    {   $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Cell(25, 6, 'ACTIVIDADES:', 0, 0, 'C', 1);
        $pdf->Ln(5);
        //$pdf->Cell(20);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(232, 232, 232);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(-3);
        //$pdf->Cell(12, 6, 'Inicio', 1, 0, 'C', 1);
        //$pdf->Cell(12, 6, 'Fin', 1, 0, 'C', 1);
        $pdf->Cell(53, 6, 'Actividad', 1, 0, 'C', 1);
        $pdf->Cell(44, 6, 'SubActividad', 1, 0, 'C', 1);
        $pdf->Cell(40, 6, utf8_decode('Clasificación'), 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'Horo.', 1, 0, 'C', 1);
        $pdf->Cell(18, 6, 'TM', 1, 0, 'C', 1);
        $pdf->Ln(8);
        $count_tm = 0;
        $count_horo = 0;
        //contenido de la tabla
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetWidths(array(53, 44, 40, 15, 18));
        $pdf->SetAligns(array('L','L','L','R','R'));
        while ($row = sqlsrv_fetch_array($resultado))
        {   $tm = $row['TM_total'];
            $pdf->Cell(-3);
            if ($row['Clasificacion'] == null){
                $count_tm+=$tm;
                $count_horo+=$row['total_horas'];
                
                //$pdf->Cell(20);
                $pdf->Row1(array($row['Descripcion'], $row['Descripcion_sub'], "--------------------------------",number_format($row['total_horas'],1,',','.'),$tm));
                $num++;
            }else{
                if($row['Descripcion'] == null){
                    //$distribuir++;
                    //$pdf->Cell(20);
                    $pdf->Row1(array("FALTA DISTRIBUIR TIEMPOS", "FALTA DISTRIBUIR TIEMPOS", "FALTA DISTRIBUIR TIEMPOS",number_format($row['total_horas'],1,',','.'),$tm));
                    $num++;
                }else{
                    $count_tm+=$tm;
                    $count_horo+=$row['total_horas'];
                    //$pdf->Cell(20);
                    $pdf->Row1(array($row['Descripcion'], $row['Descripcion_sub'], $row['Clasificacion'],number_format($row['total_horas'],1,',','.'),$tm));
                    $num++;
                }
            }
        }

        $pdf->Ln(1);
        /*$pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', '', 10);*/
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(-3);
        //$pdf->SetWidths(array(11, 11, 55, 50, 40, 20, 20));
        $pdf->SetWidths(array(53, 44, 40, 15, 18));
        $pdf->Row1(array('TOTAL ACTIVIDADES: ', $num, "TOTALES: ",number_format($count_horo,1,',','.').' H.',number_format($count_tm,2,',','.')));
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Ln(5);
    }
    $consulta1 = "SELECT horometro.fecha_cierre_horometro ,horometro.id_horometro, horometro.id_registro, horometro.horometro_inicial, 
                    horometro.horometro_final, horometro.total_horas, horometro.idActividad, horometro.fecha_registro_horometro, 
                    Actividades.Descripcion, SubActividades_cargadores.Descripcion as Descripcion_sub, horometro.observaciones,
                    Clasificacion.Descripcion as Clasificacion, horometro.idSubActividad, horometro.idClasificacion
                FROM horometro LEFT JOIN Actividades ON horometro.idActividad = Actividades.idActividad
                LEFT JOIN SubActividades_cargadores ON horometro.idSubActividad=SubActividades_cargadores.idSubActividad
                LEFT JOIN Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
                WHERE horometro.id_registro='$tiquete' AND horometro.idSubActividad!='EF629169-C06B-4414-AD73-97E3B76628F4' and horometro.tipo_tarifa=1
                ORDER BY Actividades.Descripcion, SubActividades_cargadores.Descripcion";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $resultado1=sqlsrv_query($conn,utf8_decode($consulta1),$params,$options);
    $rows1=sqlsrv_num_rows($resultado1);
    if ($rows1 > 0)
    {   $pdf->Cell(25, 6, 'DESPACHOS:', 0, 0, 'C', 1);
        $pdf->Ln(5);
        //$pdf->Cell(20);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(232, 232, 232);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(-3);
        $pdf->Cell(12, 6, 'Inicio', 1, 0, 'C', 1);
        $pdf->Cell(12, 6, 'Fin', 1, 0, 'C', 1);
        $pdf->Cell(53, 6, 'Actividad', 1, 0, 'C', 1);
        $pdf->Cell(44, 6, 'SubActividad', 1, 0, 'C', 1);
        $pdf->Cell(40, 6, utf8_decode('Clasificación'), 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'Horo.', 1, 0, 'C', 1);
        $pdf->Cell(18, 6, 'TM', 1, 0, 'C', 1);
        $pdf->Ln(8);
        $count_tm1 = 0;
        $count_horo1 = 0;
        //contenido de la tabla
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetWidths(array(12, 12, 53, 44, 40, 15, 18));
        $pdf->SetAligns(array('C','C','L','L','L','R','R'));
        while ($row1 = sqlsrv_fetch_array($resultado1))
        {   $tm1 = 0;
            $pdf->Cell(-3);
            if($row1['horometro_inicial'] == '' && $row1['horometro_final'] == ''){
                $sql11 = "SELECT * FROM tiempos_cargadores_actividad
                    WHERE idRegistro='$tiquete' AND idSubActividad='". $row1['idSubActividad'] ."' AND idClasificacion='".$row1['idClasificacion']."'";
                $params = array();
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
                $resultado11=sqlsrv_query($conn,utf8_decode($sql11),$params,$options);
                $rows1=sqlsrv_num_rows($resultado11);
                if ($rows1 > 0)
                {   while($times1 = sqlsrv_fetch_array($resultado11)){
                    $tm1 = $times1['TM_total'];
                    }
                }
            }
            if ($row1['Clasificacion'] == null){
                $count_tm1+=$tm1;
                $count_horo1+=$row1['total_horas'];
                
                //$pdf->Cell(20);
                $pdf->Row1(array(date_format($row1['fecha_registro_horometro'], 'H:i'),date_format($row1['fecha_cierre_horometro'], 'H:i'),$row1['Descripcion'], $row1['Descripcion_sub'], "--------------------------------",number_format($row1['total_horas'],1,',','.'),$tm1));
                $num1++;
            }else{
                if($row1['Descripcion'] == null){
                    //$distribuir++;
                    //$pdf->Cell(20);
                    $pdf->Row1(array(date_format($row1['fecha_registro_horometro'], 'H:i'),date_format($row1['fecha_cierre_horometro'], 'H:i'),"FALTA DISTRIBUIR TIEMPOS", "FALTA DISTRIBUIR TIEMPOS", "FALTA DISTRIBUIR TIEMPOS",number_format($row1['total_horas'],1,',','.'),$tm1));
                    $num1++;
                }else{
                    $count_tm1+=$tm1;
                    $count_horo1+=$row1['total_horas'];
                    //$pdf->Cell(20);
                    $pdf->Row1(array(date_format($row1['fecha_registro_horometro'], 'H:i'),date_format($row1['fecha_cierre_horometro'], 'H:i'),$row1['Descripcion'], $row1['Descripcion_sub'], $row1['Clasificacion'],number_format($row1['total_horas'],1,',','.'),$tm1));
                    $num1++;
                }
            }
        }

        $pdf->Ln(1);
        /*$pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', '', 10);*/
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(-3);
        //$pdf->SetWidths(array(11, 11, 55, 50, 40, 20, 20));
        $pdf->SetWidths(array(12, 12, 53, 44, 40, 15, 18));
        $pdf->Row1(array(date_format($jornada_inicial, 'H:i'),date_format($jornada_final, 'H:i'),'TOTAL ACTIVIDADES: ', $num1, "TOTALES: ",number_format($count_horo1,1,',','.').' H.',number_format($count_tm1,2,',','.')));
    }
}
$pdf->Ln(8);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 6, 'Observaciones:', 0, 1, 'C', 1);
//$pdf->Ln(8);
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
$pdf->Ln(12);
$pdf->Cell(55);
$pdf->Cell(35, 6, 'Fecha de consulta:', 0, 0, 'C', 1);
$pdf->Cell(45, 6, $Fecha." Hora : ".$Hora_actual, 0, 0, 'C', 1);

$pdf->Output();