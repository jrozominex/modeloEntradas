<?php
session_start();
require_once '../librerias/lib_excel/PHPExcel.php';
require_once '../modelo/conexion.php';
//error_reporting(0);
if ($_SESSION["logueado"] == TRUE && ($_SESSION["permisoIngresar"] == 'CONSULTAS_OFICINA' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES' || $_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO')){
    $usuario = $_SESSION['usuario'];
    $Fecha = date('Y-m-d');
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
}elseif($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){
    //header("Location: incio.php");
    ?>
    <script type="text/javascript">
        self.location='inicio.php';
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
$empresa=$_GET['empresa'];
$id_factura = $_GET['factura'];
$fecha_inicio=date('Y-m-d');
$fecha_fin=date('Y-m-d');
//$acopio=$_GET['acopio'];
$sql="SELECT Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, 
    Registro_tique_cargadores.id_usuario, Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, 
    Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado, Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, 
    Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, Usuarios.NombreUsuarioLargo, P.NombreCorto AS proveedor, Registro_tique_cargadores.horometro_ini, Registro_tique_cargadores.horometro_fin, 
    TarifaMaquinaria.Tarifa_Toneladas, TarifaMaquinaria.Tarifa_Horometro, TarifaMaquinaria.Iva, horometro_descuento_cargadores.tm_despacho, D.Descripcion AS Destino_cargue
    FROM Registro_tique_cargadores INNER JOIN
       Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
       Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
       Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
       Proveedores AS P ON Equipos.idPropietario = P.idProveedor INNER JOIN
       Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario INNER JOIN
       TarifaMaquinaria ON Registro_tique_cargadores.id_maquinaria = TarifaMaquinaria.idEquipo LEFT JOIN
       horometro_descuento_cargadores ON Registro_tique_cargadores.id_registro = horometro_descuento_cargadores.idRegistro LEFT JOIN
       Destino AS D ON horometro_descuento_cargadores.id_destino=D.idDestino
    WHERE (Registro_tique_cargadores.estado = '3') AND (Registro_tique_cargadores.id_registro IN
        (SELECT numerotransaccion FROM Factura_Venta_Detalle WHERE (id_factura_venta = '$id_factura'))) 
    AND (Registro_tique_cargadores.id_proveedor = '$empresa') AND (TarifaMaquinaria.Fecha_Hasta = '1900-01-01')
    ORDER BY Registro_tique_cargadores.cod_reporte DESC";
//ECHO $sql."<br><br>";
/*Lo primero que hacemos es definir la zona horaria, debido a que vamos a trabajar con datos de tipo 
fecha y luego manda un error si no tenemos asignada una zona horaria*/
date_default_timezone_set('America/Bogota');
/*La siguiente línea determina si se está accediendo al archivo vía HTTP o CLI(command line interface), 
el archivo solo se va a mostrar si se accede desde un navegador web(HTTP).  */
if (PHP_SAPI == 'cli')
    die('Este archivo solo se puede ver desde un navegador web');
//ECHO 'CASA';
// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("REPORTE MAQUINARIA") // Nombre del autor
    ->setLastModifiedBy("REPORTE MAQUINARIA") //Ultimo usuario que lo modificó
    ->setTitle("REPORTE DE MAQUINARIA CON EXCEL") // Titulo
    ->setSubject("Reporte Excel con PHP y SQL-Server") //Asunto
    ->setDescription("REPORTE MAQUINARIA") //Descripción
    ->setKeywords("REPORTE MAQUINARIA") //Etiquetas
    ->setCategory("REPORTE DE EXCEL"); //Categorias

//estilo para las celdas****************************************
$corre = sqlsrv_query($conn,$sql);
$posi = 0;
$id=0;
while($save = sqlsrv_fetch_array($corre)){
    $id_registro = $save['id_registro'];
    $Array_id_regstro[$id]=$save['id_registro'];
    $id++;
    $query = "SELECT Destino.Descripcion, sum(tiempos_cargadores_actividad.TM_total) as TM_total
        FROM tiempos_cargadores_actividad INNER JOIN
        Registro_tique_cargadores ON tiempos_cargadores_actividad.idRegistro = Registro_tique_cargadores.id_registro LEFT OUTER JOIN
        Destino ON tiempos_cargadores_actividad.idDestino = Destino.idDestino
        where tiempos_cargadores_actividad.idRegistro='$id_registro' and 
        tiempos_cargadores_actividad.idSubActividad='FD78D664-C2B4-4994-A72A-4D55A62FB462'
        GROUP BY Destino.Descripcion ORDER BY Destino.Descripcion";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );     
    $corre1=sqlsrv_query($conn,utf8_decode($query),$params,$options);
    $cont_viajes=sqlsrv_num_rows($corre1);
    if($cont_viajes > 0){
        while($save1 = sqlsrv_fetch_array($corre1)){
            $Array_destino_nom[$posi]['nom']=$save1['Descripcion'];
            $Array_destino_nom[$posi]['tm']=$save1['TM_total'];
            $posi++;
        }
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$posi1 = 0;
$posi = 0;
$actividad = "aaa";
$letra_fin = 'K';
$Array_id_regstro = implode("','", $Array_id_regstro);

$query = "SELECT Actividades.Descripcion, subactividades_cargadores.Descripcion AS SubActividad,sum(horometro.total_horas) AS total_horas
    FROM horometro INNER JOIN Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
    subactividades_cargadores ON horometro.idActividad = subactividades_cargadores.idActividad AND horometro.idSubActividad = subactividades_cargadores.idSubactividad
WHERE (horometro.id_registro in ('$Array_id_regstro')) and horometro.tipo_tarifa=0
GROUP BY Actividades.Descripcion, subactividades_cargadores.Descripcion
ORDER BY Actividades.Descripcion, subactividades_cargadores.Descripcion";
//$objPHPExcel->setActiveSheetIndex(0)
    //->setCellValue('O2',$query);
$corre1 = sqlsrv_query($conn,utf8_decode($query));
while($save1 = sqlsrv_fetch_array($corre1)){
    if($actividad == 'aaa'){
        $actividad = utf8_encode($save1['Descripcion']);
        $posi1++;
    }elseif($actividad == utf8_encode($save1['Descripcion'])){
        $posi1++;
    }else{
        $Array_act_cant[$actividad] = $posi1;
        $posi1 = 1; 
    }
    $Array_act_position[utf8_encode($save1['Descripcion']).'-'.utf8_encode($save1['SubActividad'])] = $letra_fin;
    $Array_act_nom[$posi]['Act']=utf8_encode($save1['Descripcion']).'-'.utf8_encode($save1['SubActividad']);
    $Array_act_nom[$posi]['position']=$letra_fin;
    $Array_act_nom[$posi]['horas']=$save1['total_horas'];
    $letra_fin++;
    $posi++;
}
$Array_act_cant[$actividad] = $posi1;
$i = 0;
$name = $Array_act_nom[$i]['position'];
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($cont_viajes > 0){
    $result = array();
    foreach($Array_destino_nom as $t) {
        $repeat=false;
        for($i=0;$i<count($result);$i++)
        {
            if($result[$i]['nom']==$t['nom'])
            {
                $result[$i]['tm']+=$t['tm'];
                $repeat=true;
                break;
            }
        }
        if($repeat==false)
            $result[] = array('nom' => $t['nom'], 'tm' => $t['tm']);
    }
}
$estiloTituloReporte = array(
  'font' => array('name'=>'Calibri','bold'=>true,'italic'=>false,'strike'=>false,'size'=>14,
  'color'=>array('rgb'=>'FFFFFF')),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('rgb'=>'7FB3D5')),
  'borders' => array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_NONE)),
  'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'=>0,'wrap'=>TRUE)
);
$estiloTituloReporte_1 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'italic'=>false,'strike'=>false,'size'=>11,
  'color'=>array('rgb'=>'000000')),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('rgb'=>'FFFFFF')),
  'borders' => array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_NONE)),
  'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'=>0,'wrap'=>TRUE)
);
$estiloTituloReporte_2 = array(
  'font' => array('name'=>'Calibri','bold'=>false,'italic'=>false,'strike'=>false,'size'=>11,
  'color'=>array('rgb'=>'000000')),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('rgb'=>'FFFFFF')),
  'borders' => array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_NONE)),
  'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'=>0,'wrap'=>TRUE)
);


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$estiloTituloColumnas = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,'rotation'=>90,'startcolor'=>array('rgb'=>'DCE6F1'),
  'endcolor' => array('argb' => 'DCE6F1')),
  'borders' => array('top'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM,'color'=>array('rgb'=>'FFFFFF')),
  'bottom'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM, 'color'=>array('rgb'=>'DCE6F1'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);
$estiloTituloColumnas1 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,'rotation'=>90,'startcolor'=>array('rgb'=>'83CFD3'),
  'endcolor' => array('argb' => '83CFD3')),
  'borders' => array('top'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM,'color'=>array('rgb'=>'FFFFFF')),
  'bottom'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM, 'color'=>array('rgb'=>'83CFD3'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);
$estiloTituloColumnas2 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,'rotation'=>90,'startcolor'=>array('rgb'=>'649DE5'),
  'endcolor' => array('argb' => '649DE5')),
  'borders' => array('top'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM,'color'=>array('rgb'=>'FFFFFF')),
  'bottom'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM, 'color'=>array('rgb'=>'649DE5'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);
$estiloTituloColumnas3 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,'rotation'=>90,'startcolor'=>array('rgb'=>'B6B8BB'),
  'endcolor' => array('argb' => 'B6B8BB')),
  'borders' => array('top'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM,'color'=>array('rgb'=>'FFFFFF')),
  'bottom'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM, 'color'=>array('rgb'=>'B6B8BB'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);
$estiloTituloColumnas4 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,'rotation'=>90,'startcolor'=>array('rgb'=>'EEA747'),
  'endcolor' => array('argb' => 'EEA747')),
  'borders' => array('top'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM,'color'=>array('rgb'=>'FFFFFF')),
  'bottom'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM, 'color'=>array('rgb'=>'EEA747'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);
$estiloTituloColumnas5 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,'rotation'=>90,'startcolor'=>array('rgb'=>'FCD5B4'),
  'endcolor' => array('argb' => 'FCD5B4')),
  'borders' => array('top'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM,'color'=>array('rgb'=>'FFFFFF')),
  'bottom'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM, 'color'=>array('rgb'=>'FCD5B4'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);
$estiloTituloColumnas6 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,'rotation'=>90,'startcolor'=>array('rgb'=>'CFBAA8'),
  'endcolor' => array('argb' => 'CFBAA8')),
  'borders' => array('top'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM,'color'=>array('rgb'=>'FFFFFF')),
  'bottom'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM, 'color'=>array('rgb'=>'CFBAA8'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);
$estiloTituloColumnas7 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,'rotation'=>90,'startcolor'=>array('rgb'=>'DDD9C4'),
  'endcolor' => array('argb' => 'DDD9C4')),
  'borders' => array('top'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM,'color'=>array('rgb'=>'FFFFFF')),
  'bottom'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM, 'color'=>array('rgb'=>'DDD9C4'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);
$estiloTituloColumnas8 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,'rotation'=>90,'startcolor'=>array('rgb'=>'FFFF00'),
  'endcolor' => array('argb' => 'FFFF00')),
  'borders' => array('top'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM,'color'=>array('rgb'=>'FFFFFF')),
  'bottom'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM, 'color'=>array('rgb'=>'FFFF00'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);
$estiloTituloColumnas9 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,'rotation'=>90,'startcolor'=>array('rgb'=>'CC602E'),
  'endcolor' => array('argb' => 'CC602E')),
  'borders' => array('top'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM,'color'=>array('rgb'=>'FFFFFF')),
  'bottom'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM, 'color'=>array('rgb'=>'CC602E'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$estiloInformacion = new PHPExcel_Style();
$estiloInformacion->applyFromArray( array('font'=>array('name'=>'Calibri','size'=>'10','color'=>array('rgb'=>'000000')),
  'borders' => array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47')))
));
$estiloTotal = new PHPExcel_Style();
$estiloTotal->applyFromArray( array('font'=>array('name'=>'Arial','bold'=>true,'color'=>array('rgb'=>'000000')),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('rgb'=>'7FB3D5')),
  'borders' => array('left'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'7FB3D5')))
));  
 /* $estilo_sub_origen = new PHPExcel_Style();
  $estilo_sub_origen->applyFromArray( array('font'=>array('name'=>'Calibri','size'=>'10','color'=>array('rgb'=>'000000')),
      'fill' => array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('rgb'=>'C5D9F1')),
      'borders' => array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47')))
  ));
*/
$estilo_informacion = new PHPExcel_Style();
$estilo_informacion->applyFromArray( array('font'=>array('name'=>'Calibri','size'=>'10','color'=>array('rgb'=>'000000')),
  'borders' => array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47')))
));
$estilo_informacion1 = new PHPExcel_Style();
$estilo_informacion1->applyFromArray( array('font'=>array('name'=>'Calibri','size'=>'10','color'=>array('rgb'=>'000000')),
  'borders' => array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'FFFFFF')))
));
// fin estilo celdas  
$objPHPExcel-> getActiveSheet()-> setShowGridlines(false); // oculta las lineas de las celdas  
$tituloReporte = "INFORME DE TIQUETES DE CARGADOR";
$titulosColumnas = array('EMPRESA','ACOPIO','OPERACION','PROVEEDOR','FECHA TIQUETE','OPERARIO',
  'EQUIPO','N° TIQUETE','CLASIFICAR ROOM','CLASIFICAR SOBRETAMAÑO','DESPACHO','ENTRADAS','MOLIENDA','STANDBY'
  ,'ALIMENTAR','APILAR','CARGAR DESPACHO','APILAR','MVTO. x CALIDAD','OFICIOS VARIOS','STANDBY','DESCUENTOS');

for ($i=0; $i < count($Array_act_nom); $i++){
    $name = $Array_act_nom[$i]['Act'];
    $place = $Array_act_nom[$i]['position'];
    $objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells($place.'8:'.$place.'9')
        ->setCellValue($place.'8',$name);
}
$letra_ini = $place;
$var = 0;

$letra_ini++;
$position_cargue = $letra_ini;
$letra_fin = $letra_ini;
//$letra_fin++;
if($cont_viajes > 0){
    for ($i=0; $i < count($result); $i++){
        $letra_fin++;
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($letra_fin.'9',$result[$i]['nom']);
        $Array_position_nom[$result[$i]['nom']] = $letra_fin;
    }
    $objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells($letra_fin.'8:'.$letra_fin.'8')
        ->setCellValue($letra_fin.'8','DESTINOS');
}
// Se combinan las celdas A1 hasta E1, para colocar ahí el titulo del reporte 
// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)
    ->mergeCells('A1:'.$letra_fin.'1')
    ->mergeCells('A7:H7')
    ->mergeCells('A2:B2')
    ->mergeCells('A3:B3')
    ->setCellValue('A1',$tituloReporte) // Titulo del reporte
    ->setCellValue('A2','FECHA INICIO: ') // Titulo del reporte
    ->setCellValue('C2',$fecha_inicio)
    ->setCellValue('A3','FECHA FIN: ') // Titulo del reporte
    ->setCellValue('C3',$fecha_fin)
    ->setCellValue('A7','TOTALES: ') // Titulo del reporte
    ->setCellValue('G4','IVA: ')
    ->setCellValue('G5','BASE HORA: ')
    ->setCellValue('G6','BASE TM: ')
    ->mergeCells('A8:A9')
    ->setCellValue('A8',$titulosColumnas[7])
    ->mergeCells('B8:B9')
    ->setCellValue('B8',$titulosColumnas[1])
    ->mergeCells('C8:C9')
    ->setCellValue('C8',$titulosColumnas[2])
    ->mergeCells('D8:D9')
    ->setCellValue('D8',$titulosColumnas[4])
    ->mergeCells('E8:E9')
    ->setCellValue('E8',$titulosColumnas[5])  //Titulo de las columnas
    ->mergeCells('F8:F9')
    ->setCellValue('F8',$titulosColumnas[6])
    ->mergeCells('G8:G9')
    ->setCellValue('G8','HOROMETRO INICIAL')  //Titulo de las columnas
    ->mergeCells('H8:H9')
    ->setCellValue('H8','HOROMETRO FINAL')
    ->mergeCells('I8:I9')
    ->setCellValue('I8','TOTAL HORAS')
    ->mergeCells('J8:J9')
    ->setCellValue('J8','DESCUENTOS')
    ->setSharedStyle($estilo_informacion1, 'A8:'.$letra_fin.'9');
if($cont_viajes > 0){
    $objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells($letra_ini.'8:'.$letra_ini.'9')
        ->setCellValue($letra_ini.'8','TM CARGUE');
    $objPHPExcel->getActiveSheet()->getStyle($letra_ini.'8:'.$letra_ini.'9')->applyFromArray($estiloTituloColumnas8);
    $objPHPExcel->getActiveSheet()->getStyle($letra_fin.'8:'.$letra_fin.'9')->applyFromArray($estiloTituloColumnas9);
}
    //Titulo de las columnas
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$letra_fin.'1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A7:'.$letra_fin.'7')->applyFromArray($estiloTituloReporte_1);
//$objPHPExcel->setActiveSheetIndex(0)->setSharedStyle($estilo_informacion, 'I2:T2');
$objPHPExcel->getActiveSheet()->getStyle('A8:I9')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->getStyle('J8:J9')->applyFromArray($estiloTituloColumnas1);
$objPHPExcel->getActiveSheet()->getStyle('K8:'.$place.'9')->applyFromArray($estiloTituloColumnas5);
//$objPHPExcel->getActiveSheet()->getStyle('I3:S3')->applyFromArray($estilo_sub_origen);
$res = sqlsrv_query($conn,$sql);
$i=10;
$total_total_horas = 0;
$total_total_descuentos = 0;
$total_total_room_alimentar = 0;
$total_total_room_apilar = 0;
$total_total_sobretamaño_alimentar = 0;
$total_total_sobretamaño_apilar = 0;
$total_total_cargar_despacho = 0;
$total_total_entradas_apilar = 0;
$total_total_entradas_mvto = 0;
$total_total_entradas_varios = 0;
$total_total_molienda_alimentar = 0;
$total_total_molienda_apilar = 0;
$total_total_standby = 0;
///////////////////////////////////////////////////////////////////////////////
$Tarifa_Toneladas = 0;
$Tarifa_Horometro = 0;
$Iva = 0;
$count_registros = 0;
while($r = sqlsrv_fetch_array($res))
{   $count_registros++;
    $Tarifa_Toneladas+=$r['Tarifa_Toneladas'];
    $Tarifa_Horometro+=$r['Tarifa_Horometro'];
    $Iva+=$r['Iva'];
    //////////////////////////////////////////////////////////////////////////////////////////
    $proveedor = utf8_encode($r['proveedor']);
    $empresa = utf8_encode($r['NombreCorto']);
    $paso = 0;
    $band = 0;
    $cont_room = 0;
    $cont_sobreta = 0;
    $cont_despacho = 0;
    $cont_entradas = 0;
    $cont_molienda = 0;
    $cont_standby = 0;
    $total_horas = 0;
    $tm_despacho = 0;
    ////////////////////////////////////////////////////////////
    $total_descuentos = 0;
    $total_room_alimentar = 0;
    $total_room_apilar = 0;
    $total_sobretamaño_alimentar = 0;
    $total_sobretamaño_apilar = 0;
    $total_cargar_despacho = 0;
    $total_entradas_apilar = 0;
    $total_entradas_mvto = 0;
    $total_entradas_varios = 0;
    $total_molienda_alimentar = 0;
    $total_molienda_apilar = 0;
    $total_standby = 0;
    //////////////////////////////////////////////////////////
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,$r['cod_reporte'])
        //->setCellValue('B'.$i,$r['NombreCorto'])
        ->setCellValue('B'.$i,$r['Descripcion']);
    if($r['servicio_clasificacion'] == 1){
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C'.$i,'SERVICIO CLASIFICACIÓN');
    }else{
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C'.$i,'CARBÓN');
    }
    $objPHPExcel->setActiveSheetIndex(0)
        //->setCellValue('E'.$i,$r['proveedor'])
        ->setCellValue('D'.$i,date_format($r['fecha_apertura_tique'],'Y-m-d'))
        ->setCellValue('E'.$i,$r['NombreUsuarioLargo'])
        ->setCellValue('F'.$i,$r['NombreCargador'].' - '.$r['Identificacion'])
        ->setCellValue('G'.$i,$r['horometro_ini'])
        ->setCellValue('H'.$i,$r['horometro_fin'])
        ->setCellValue('I'.$i,number_format($r['horas_trabajadas'],1));
    $total_total_horas+=number_format($r['horas_trabajadas'],1);
    $sql_desc = "SELECT * FROM horometro_descuento_cargadores WHERE idRegistro='". $r['id_registro'] ."'";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $res_desc=sqlsrv_query($conn,utf8_decode($sql_desc),$params,$options);
    $rows=sqlsrv_num_rows($res_desc);
    if ($rows > 0) 
    {   while($desc = sqlsrv_fetch_array($res_desc)){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('J'.$i,number_format($desc['valor_descuento'],1,'.',','));
            $total_descuentos+=$desc['valor_descuento'];
            $total_total_descuentos+=number_format($desc['valor_descuento'],1);
        }
    }else{
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J'.$i,0);
    }
    $sql_1 = "SELECT Actividades.Descripcion,subactividades_cargadores.Descripcion AS Descripcion_sub,  
            SUM(tiempos_cargadores_actividad.tiempohorometro) AS total_horas, SUM(tiempos_cargadores_actividad.TM_total) AS TM_total
      FROM subactividades_cargadores INNER JOIN
         Actividades ON subactividades_cargadores.idActividad = Actividades.idActividad INNER JOIN
         tiempos_cargadores_actividad ON subactividades_cargadores.idSubactividad = tiempos_cargadores_actividad.idSubActividad
      WHERE (tiempos_cargadores_actividad.idRegistro = '". $r['id_registro'] ."') AND (tiempos_cargadores_actividad.tipo_tarifa = 0)
      GROUP BY subactividades_cargadores.Descripcion, Actividades.Descripcion";
    $res_1 = sqlsrv_query($conn,$sql_1);
    while($horometro = sqlsrv_fetch_array($res_1)){
        $var_b = $Array_act_position[$horometro['Descripcion'].'-'.$horometro['Descripcion_sub']];
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($var_b.$i,$horometro['total_horas']);
    }
    $tm_despacho = 0;
    if($cont_viajes > 0){
        $query1 = "SELECT Destino.Descripcion, sum(tiempos_cargadores_actividad.TM_total) as TM_total
            FROM tiempos_cargadores_actividad inner join Destino ON tiempos_cargadores_actividad.idDestino=Destino.idDestino
            where tiempos_cargadores_actividad.idRegistro='". $r['id_registro'] ."' and 
            tiempos_cargadores_actividad.idSubActividad='FD78D664-C2B4-4994-A72A-4D55A62FB462'
            GROUP BY Destino.Descripcion";
        $corre11 = sqlsrv_query($conn,$query1);
        while($save11 = sqlsrv_fetch_array($corre11)){
            $tm_despacho+=$save11['TM_total'];
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($letra_ini.$i,$tm_despacho);
        $corre11 = sqlsrv_query($conn,$query1);
        while($save11 = sqlsrv_fetch_array($corre11)){
            $var_a = $Array_position_nom[utf8_encode($save11['Descripcion'])];
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($var_a.$i,$save11['TM_total']);
        }
        $i++;
    }
}//CIERRE WHILE TIQUETES
$count_registros;
$Tarifa_Toneladas = $Tarifa_Toneladas/$count_registros;
$Tarifa_Horometro = $Tarifa_Horometro/$count_registros;
$Iva = $Iva/$count_registros;
if($Iva == 0){
    for ($a='I'; $a <= $place; $a++) { 
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValueExplicit($a.'5','=H5*'.$a.'7');
    }
}else{
    for ($a='I'; $i <= $place; $a++) { 
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValueExplicit($a.'5','=('.$a.'7*H5)+('.$a.'7*H5*H4/100)');
    }
}
$objPHPExcel->setActiveSheetIndex(0)
    ->mergeCells('A5:B5')
    ->mergeCells('A6:B6')
    ->setCellValue('A5','EMPRESA:')
    ->setCellValue('C5',$empresa)
    ->setCellValue('A6','PROVEEDOR: ') // Titulo del reporte
    ->setCellValue('C6',$proveedor)
    ->setCellValue('H4',$Iva)
    ->setCellValue('H5',$Tarifa_Horometro)
    ->setCellValue('H6',$Tarifa_Toneladas);
for ($a='I'; $a <= $letra_fin; $a++) { 
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValueExplicit($a.'7','=SUBTOTALES(9;'.$a.'8:'.$a.$i.')');
}
$lugar_cargue_summary = 'nada';
for ($a=0; $a < count($Array_act_nom); $a++){
    $name = $Array_act_nom[$a]['Act'];
    if($name == 'CARGAR DESPACHO'){
        $lugar_cargue_summary = $Array_act_nom[$a]['position'];  
    }
}
if($lugar_cargue_summary != 'nada'){
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValueExplicit($a.'5','=('.$lugar_cargue_summary.'5/'.$position_cargue.'7)*'.$a.'7');
}
for($aa=$letra_ini; $aa <= $letra_fin; $aa++) {
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValueExplicit($aa.'6','=H6*'.$aa.'7')
        ->setCellValueExplicit($aa.'7','=SUBTOTALES(9;'.$aa.'8:'.$aa.$i.')');
}
$objPHPExcel->getActiveSheet()->getStyle('H4:U6')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);

/*  Hasta este punto ya se tiene el archivo con los datos ahora procedemos a aplicar el formato a las 
celdas. Para ello vamos a crear 3 variables, la primera va a contener el estilo del título del reporte, 
la segunda el estilo del título de las columnas y la tercera el estilo de la información de los alumnos.
Ahora procedemos a asignar el ancho de las columnas de forma automática en base al contenido de 
cada una de ellas y lo hacemos con un ciclo de la siguiente forma.  */

for($j = 'A'; $j <= $letra_fin; $j++)
  {   //$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($j)->setAutoSize(TRUE);  
   // $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':L'.$j)->applyFromArray($estiloInformacion);
  }
$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,10);
//se da formato de numeros con 2 decimales 
//  $objPHPExcel->getActiveSheet()->getStyle('H3:J'.($i))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
//Bien, ahora solo agregamos algunos detalles mas
// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('REPORTE MAQUINARIA');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
$objPHPExcel->setActiveSheetIndex(0);

// Inmovilizar paneles
//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
//$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

//Ya para terminar vamos a enviar el archivo para que el usuario lo descargue.

// Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="REPORTE_MAQUINARIA.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>