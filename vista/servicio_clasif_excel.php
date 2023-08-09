<?php
    require_once '../librerias/lib_excel/PHPExcel.php';
   
/*Lo primero que hacemos es definir la zona horaria, debido a que vamos a trabajar con datos de tipo 
fecha y luego manda un error si no tenemos asignada una zona horaria*/
    date_default_timezone_set('America/Bogota');
/*La siguiente línea determina si se está accediendo al archivo vía HTTP o CLI(command line interface), 
el archivo solo se va a mostrar si se accede desde un navegador web(HTTP).  */
    if (PHP_SAPI == 'cli')
        die('Este archivo solo se puede ver desde un navegador web');
 
// Se crea el objeto PHPExcel
    $objPHPExcel = new PHPExcel();

    $objPHPExcel->getProperties()->setCreator("Global") // Nombre del autor
    ->setLastModifiedBy("Global") //Ultimo usuario que lo modificó
    ->setTitle("Reporte Global con Excel") // Titulo
    ->setSubject("Reporte Excel con PHP y SQL-Server") //Asunto
    ->setDescription("Reporte de Compras") //Descripción
    ->setKeywords("Reporte Global") //Etiquetas
    ->setCategory("Reporte excel"); //Categorias
//estilo para las celdas****************************************
    $estiloTituloReporte = array(
        'font' => array('name'=>'Verdana','bold'=>true,'italic'=>false,'strike'=>false,'size'=>14,
            'color'=>array('rgb'=>'FFFFFF')),
        'fill' => array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('rgb'=>'7FB3D5')),
        'borders' => array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_NONE)),
        'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'=>0,'wrap'=>TRUE)
    );
 
    $estiloTituloColumnas = array(
        'font' => array('name'=>'Arial','bold'=>true,'color'=>array('rgb'=>'FFFFFF'), 'size'=>'10'),
        'fill' => array('type'=>PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,'rotation'=>90,'startcolor'=>array('rgb'=>'7FB3D5'),
            'endcolor' => array('argb' => 'FF431a5d')),
        'borders' => array('top'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM,
                '   color'=>array('rgb'=>'143860')),
                'bottom'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM,
                    'color'=>array('rgb'=>'143860'))),
        'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
    );
    
    $estilo_informacion = new PHPExcel_Style();
    $estilo_informacion->applyFromArray( array('font'=>array('name'=>'Calibri','size'=>'10','color'=>array('rgb'=>'000000')),
      'borders' => array('right'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47')))
    ));

    $estiloInformacion = new PHPExcel_Style();
    $estiloInformacion->applyFromArray( array('font'=>array('name'=>'Calibri','size'=>'10','color'=>array('rgb'=>'000000')),
        'borders' => array('left'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47'))),
         'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            'right'=>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 'wrap'=>TRUE)
    ));

    $estilo_sub_clasificacion_centrado = new PHPExcel_Style();
    $estilo_sub_clasificacion_centrado->applyFromArray( array('font'=>array('name'=>'Calibri','size'=>'10','color'=>array('rgb'=>'000000')),
      'borders' => array('right'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47'))),
      'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
           'right'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'wrap'=>TRUE)
   ));

    $estiloTotal = new PHPExcel_Style();
    $estiloTotal->applyFromArray( array('font'=>array('name'=>'Arial','bold'=>true,'color'=>array('rgb'=>'000000')),
        'fill' => array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('rgb'=>'7FB3D5')),
         'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE),
        'borders' => array('left'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'7FB3D5')))
    ));

    $objPHPExcel -> getActiveSheet() -> setShowGridlines(false); // oculta las lineas de las celdas

    $tituloReporte = "SERVICIO DE CLASIFICACION"; 
    $titulosColumnas = array('Nº RECIBO','FECHA','SEMANA','MES','EMPRESA','ACTIVIDAD','EQUIPO','PATIO','PILA','MATERIAL','ORIGEN DEL MATERIAL','MATERIAL OBJETIVO','Hrs. EQUIPO','TM ALIMENTADO');
// Se combinan las celdas A1 hasta E1, para colocar ahí el titulo del reporte
  /*  $objPHPExcel->setActiveSheetIndex(0)
    ->mergeCells('A1:I1');*/
    //Como pueden apreciar para asignar contenido a una celda se selecciona primero la hoja con 
    //setActiveSheetIndex(Indice de hoja) y después con setCellValue(celda, valor) 
    //asignamos el contenido a la celda deseada
    $k=0;
    $resul_array_clasi= array();
    while ($rows=sqlsrv_fetch_array($resul_clasi)) {
        $resul_array_clasi[$k] =$rows['material'];
        $k++;
    }
    $hoy = date('Y-m-d');
    $objPHPExcel->setActiveSheetIndex(0)        // Se agregan los titulos del reporte
    ->setCellValue('B1',$tituloReporte)         // Titulo del reporte       
    ->setCellValue('B4',$titulosColumnas[0])    //Titulo de las columnas
    ->setCellValue('C4',$titulosColumnas[1])
    ->setCellValue('D4',$titulosColumnas[2])
    ->setCellValue('E4',$titulosColumnas[3])
    ->setCellValue('F4',$titulosColumnas[4])
    ->setCellValue('G4',$titulosColumnas[5])
    ->setCellValue('H4',$titulosColumnas[6])
    ->setCellValue('I4',$titulosColumnas[7])
    ->setCellValue('J4',$titulosColumnas[8])
    ->setCellValue('K4',$titulosColumnas[9])
    ->setCellValue('L4',$titulosColumnas[10])
    ->setCellValue('M4',$titulosColumnas[11])
    ->setCellValue('N4',$titulosColumnas[12])
    ->setCellValue('O4',$titulosColumnas[13])
    ->setCellValue('B2','Fecha Inicio')
    ->setCellValue('B3','Fecha Fin')
    ->setCellValue('D2',$fecha_ini)
    ->setCellValue('D3',$fecha_fin)
    ->setCellValue('I3','Fecha Consulta')
    ->setCellValue('K3',$hoy);   

    // inserta dinamicamente los titulos de la clasificacion
    $l='P';
    $count_materiales=count($resul_array_clasi);
    for($j=0; $j<$count_materiales; $j++){
        $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue($l.'4', $resul_array_clasi[$j]);
        $l++;   
    }
    $objPHPExcel->setActiveSheetIndex(0)
     ->mergeCells('B1:'.$l.'1'); 
    $objPHPExcel->getActiveSheet()->getStyle('B4:'.$l.'4')->applyFromArray($estiloTituloColumnas);
    $objPHPExcel->getActiveSheet()->getStyle('B1:'.$l.'1')->applyFromArray($estiloTituloReporte);
    //$objPHPExcel->getActiveSheet()->setSharedStyle($estiloTituloColumnas, 'B4:R4');

    $j=5;
    while($rows1=sqlsrv_fetch_array($resul_pivot))
    {   $recibo = $rows1['num_recibo'];
        $fecha=date_format($rows1['fecha'],'d-m-Y');
        $mes = date_format($rows1['fecha'],'m');
        $semana = date_format($rows1['fecha'],'W');
        $empresa = $rows1['empresa'];
        $actividad = $rows1['actividad'];
        $equipo = $rows1['equipo'];
        $patio = $rows1['patio'];
        $pila = $rows1['pila'];
        $proveedor = $rows1['proveedor'];
        $material_alimen = $rows1['material_alimen'];
        $material_objetivo = $rows1['material_objetivo'];
        $horas_equipo = $rows1['horas_equipo'];
        $tm_alimen = $rows1['tm_aliment'];

        $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue('B'.$j, $recibo)
         ->setCellValue('C'.$j, $fecha)
         ->setCellValue('D'.$j, $mes)
         ->setCellValue('E'.$j, $semana)
         ->setCellValue('F'.$j, $empresa)
         ->setCellValue('G'.$j, $actividad) 
         ->setCellValue('H'.$j, $equipo)
         ->setCellValue('I'.$j, $patio)
         ->setCellValue('J'.$j, $pila)
         ->setCellValue('K'.$j, $proveedor)
         ->setCellValue('L'.$j, $material_alimen)
         ->setCellValue('M'.$j, $material_objetivo)
         ->setCellValue('N'.$j, $horas_equipo)
         ->setCellValue('O'.$j, $tm_alimen)
         ->setSharedStyle($estilo_informacion, 'C'.$j.':C'.$j)
         ->setSharedStyle($estilo_informacion, 'F'.$j.':O'.$j)
         ->setSharedStyle($estilo_sub_clasificacion_centrado, 'B'.$j.':B'.$j)
         ->setSharedStyle($estilo_sub_clasificacion_centrado, 'D'.$j.':E'.$j);

        // inserta los dinamicamente los valores de los productos
        $l='P';
        $count_materiales=count($resul_array_clasi);
        for($m=0; $m<$count_materiales; $m++){
            $objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue($l.$j, $rows1[$resul_array_clasi[$m]])
             ->setSharedStyle($estilo_informacion, $l.$j.':'.$l.$j);;
            $l++;                
        }
     //   $objPHPExcel->getActiveSheet()->getStyle('B'.$j.':I'.$j)->applyFromArray($estiloInformacionparcial);
        $j++;
    }  // cierre  while
   //     $objPHPExcel->setActiveSheetIndex(0)
    //    ->mergeCells('A'.$j.':B'.$j);
   /*     $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue('A'.$j, 'TOTAL GENERAL')
         ->setCellValue('G'.$j, number_format($ton,2, ',', '.'))
         ->setCellValue('H'.$j,' -  .')
         ->setCellValue('I'.$j, number_format(abs($valor),0, ',', '.'));
*/
     /*   $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($estiloTituloReporte);
        $objPHPExcel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($estiloInformacionparcial);
        $objPHPExcel->getActiveSheet()->setSharedStyle($estiloTotal, 'A'.$j.':I'.$j);*/
/*Ahora procedemos a asignar el ancho de las columnas de forma automática en base al contenido de 
cada una de ellas y lo hacemos con un ciclo de la siguiente forma.  */
        for($j = 'B'; $j <= $l; $j++)
        {   $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($j)->setAutoSize(TRUE);   
       // $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':L'.$j)->applyFromArray($estiloInformacion);
        }
          $objPHPExcel->setActiveSheetIndex(0);
     //   $objPHPExcel->getActiveSheet()->getStyle('G3:I'.($j))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);

   
/*  Hasta este punto ya se tiene el archivo con los datos ahora procedemos a aplicar el formato a las 
celdas. Para ello vamos a crear 3 variables, la primera va a contener el estilo del título del reporte, 
la segunda el estilo del título de las columnas y la tercera el estilo de la información de los alumnos.*/
/*Ahora procedemos a asignar el ancho de las columnas de forma automática en base al contenido de 
cada una de ellas y lo hacemos con un ciclo de la siguiente forma.  */

//se da formato de numeros con 2 decimales 
//Bien, ahora solo agregamos algunos detalles mas
// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Global');
 
// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
$objPHPExcel->setActiveSheetIndex(0);

// Inmovilizar paneles
//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
//$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

//Ya para terminar vamos a enviar el archivo para que el usuario lo descargue.

// Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte_diario.xlsx"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
sqlsrv_close($conn);

?>