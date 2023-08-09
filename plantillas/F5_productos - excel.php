<?php
session_start();
require_once '../modelo/conexion.php';
require_once '../librerias/lib_excel/PHPExcel.php';
$Fecha = date('Y-m-d');
$empresa = $_GET['empresa'];
$operacion = $_GET['operacion'];
$tipo_maquinaria = $_GET['tipo_maquinaria'];
$equipo = $_GET['equipo'];
$acopio = $_GET['acopio'];
$fecha_inicio = $_GET['fecha_inicio'];
$fecha_fin = $_GET['fecha_fin'];
//error_reporting(0);
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
$estiloTituloReporte = array(
  'font' => array('name'=>'Calibri','bold'=>true,'italic'=>false,'strike'=>false,'size'=>14,
  'color'=>array('rgb'=>'FFFFFF')),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('rgb'=>'366DA1')),
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
  'borders' => array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47'))),
  'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,'rotation'=>0,'wrap'=>TRUE)
);
/**********************************************************************************************************************/
$estiloTituloColumnas1 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('rgb'=>'DDD9C4')),
  'borders' => array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER)
);
$estiloTituloColumnas2 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('rgb'=>'92D050')),
  'borders' => array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);
$estiloTituloColumnas2_1 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('rgb'=>'EBF1DE')),
  'borders' => array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);
$estiloTituloColumnas3 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('rgb'=>'8CB5E2')),
  'borders' => array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);
$estiloTituloColumnas3_1 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('rgb'=>'D2DDEB')),
  'borders' => array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);
$estiloTituloColumnas4 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('rgb'=>'FFC000')),
  'borders' => array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);
$estiloTituloColumnas4_1 = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'000000'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('rgb'=>'EFDE91')),
  'borders' => array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);

$estiloFila_ultima = array(
    'font' => array('name'=>'Calibri','color'=>array('rgb'=>'000000'), 'size'=>'10'),
    'borders' => array('bottom'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47')))
);

/**********************************************************************************************************************/
$estiloInformacion_izquierda = new PHPExcel_Style();
$estiloInformacion_izquierda->applyFromArray( array('font'=>array('name'=>'Calibri','size'=>'10','color'=>array('rgb'=>'000000')),
  'borders' => array('right'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
));

$estiloInformacion_centrado = new PHPExcel_Style();
$estiloInformacion_centrado->applyFromArray( array('font'=>array('name'=>'Calibri','size'=>'10','color'=>array('rgb'=>'000000')),
  'borders' => array('right'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
));

$estiloInformacion_derecha = new PHPExcel_Style();
$estiloInformacion_derecha->applyFromArray( array('font'=>array('name'=>'Calibri','size'=>'10','color'=>array('rgb'=>'000000')),
  'borders' => array('right'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color'=>array('rgb'=>'3a2a47'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
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
$tituloReporte = "RELACIÓN DE SALIDA DIARIA CARBON";
//  $titulosColumnas = array('EMPRESA','ACOPIO','OPERACION','PROVEEDOR','FECHA TIQUETE','OPERARIO',
//    'EQUIPO','N° TIQUETE','CLASIFICAR ROOM','CLASIFICAR SOBRETAMAÑO','DESPACHO','ENTRADAS','MOLIENDA','STANDBY'
//   ,'ALIMENTAR','APILAR','CARGAR DESPACHO','APILAR','MVTO. x CALIDAD','OFICIOS VARIOS','STANDBY','DESCUENTOS');
$sql_empresa = "SELECT RazonSocial from Proveedores where idProveedor='$empresa'";
$res=sqlsrv_query($conn,utf8_encode($sql_empresa));
while ($rows =sqlsrv_fetch_array($res))
  { $nomb_empre=utf8_encode($rows['RazonSocial']); }

$objPHPExcel->setActiveSheetIndex(0)
->mergeCells('P5:R5')  
->mergeCells('A1:R1') 
->mergeCells('A4:B4')
->mergeCells('A5:B5')
->mergeCells('H5:I5')
->setCellValue('A1',$nomb_empre)
->setCellValue('A4','Fecha Inicio')
->setCellValue('A5','Fecha Fin')
->setCellValue('H5','Fecha Consulta')
->setCellValue('C5',$fecha_inicio)
->setCellValue('C5',$fecha_fin)
->setCellValue('J5',$Fecha)
->setCellValue('A6','FECHA') // Titulo del reporte
->setCellValue('B6','PATIO ') // Titulo del reporte
->setCellValue('C6','ZONA')
->setCellValue('D6','PILA') // Titulo del reporte
->setCellValue('E6','MATERIAL PROCESADO')
->setCellValue('F6','EQUIPO')
->setCellValue('G6','TIQUETE')  //Titulo de las columnas
->setCellValue('H6','OPERADOR')
->setCellValue('I6','CARGADOR UTILIZADO')
->setCellValue('J6','HORO. INICIAL')
->setCellValue('K6','HORO. FINAL')
->setCellValue('L6','TOTAL HRS.')  //Titulo de las columnas
->setCellValue('M6','ALIMENTAR')
->setCellValue('N6','APILAR')  //Titulo de las columnas
->setCellValue('O6','HORAS CARGADOR')
->setCellValue('P5','ALIMENTACION')
->setCellValue('P6','PALADAS')
->setCellValue('Q6','HRS. CLASIF')
->setCellValue('R6','TM');

  //Titulo de las columnas
$sql1 = "SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_usuario, Equipos_1.Descripcion AS Equipo, 
        Equipos_1.Identificacion AS Equipo_identity, Clasificacion.Descripcion AS Clasificacion,
        Destino.Descripcion AS Acopio,DestinoZonas.Zona,Pila_informes.descripcion AS Pila, Registro_tique_cargadores.horas_trabajadas,
        horometro.total_horas, Actividades.Descripcion AS Actividades, Registro_tique_cargadores.horometro_ini,
        subactividades_cargadores.Descripcion AS SubActividades, Registro_tique_cargadores.fecha_apertura_tique, 
        Registro_tique_cargadores.horometro_fin,  Equipos.Descripcion AS Cargador, Equipos.Identificacion,
        SUM(tiempos_cargadores_actividad.cantidad) AS paladas, SUM(tiempos_cargadores_actividad.TM_total) AS TM_total,
        Usuarios.NombreUsuarioLargo
    FROM Pila_informes INNER JOIN
         tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
         Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
         DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
         horometro INNER JOIN
         Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
         subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
         Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
         Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario INNER JOIN
         Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
         Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
         Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
         tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
         Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
    WHERE (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '2019-09-27' AND '2019-09-27') AND (Registro_tique_cargadores.id_proveedor = '24B7153E-AB4C-4DB7-81BD-67BC87AF014C') AND 
         (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'ALIMENTAR')
         and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '569D8AD0-A401-4AFE-BD52-A91974C7D2B0')
    GROUP BY Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_usuario, Equipos_1.Descripcion, 
        Equipos_1.Identificacion, Clasificacion.Descripcion,
        Destino.Descripcion ,DestinoZonas.Zona,Pila_informes.descripcion, Registro_tique_cargadores.horas_trabajadas,
        horometro.total_horas, Actividades.Descripcion, Registro_tique_cargadores.horometro_ini,
        subactividades_cargadores.Descripcion, Registro_tique_cargadores.fecha_apertura_tique, 
        Registro_tique_cargadores.horometro_fin,  Equipos.Descripcion, Equipos.Identificacion, Usuarios.NombreUsuarioLargo
    ORDER BY Destino.Descripcion, Equipos_1.Descripcion, Actividades.Descripcion, Clasificacion.Descripcion";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$res=sqlsrv_query($conn,$sql1,$params,$options);
$rows=sqlsrv_num_rows($res);
//
$num = 0;
$num_rendimiento = 0;
$position = 0;
$position1 = 0;
$paso = 0;
if ($rows > 0){ 
    $sql = "SELECT  Clasificacion.Descripcion AS Clasificacion
    FROM Pila_informes INNER JOIN
         tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
         Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
         DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
         horometro INNER JOIN
         Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
         subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
         Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
         Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
         Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
         Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
         tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
         Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
    WHERE        (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '2019-09-27' AND '2019-09-27') AND (Registro_tique_cargadores.id_proveedor = '24B7153E-AB4C-4DB7-81BD-67BC87AF014C') AND 
         (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'APILAR')
         and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '569D8AD0-A401-4AFE-BD52-A91974C7D2B0')
    GROUP BY  Clasificacion.Descripcion
    ORDER BY Clasificacion.Descripcion";
    $result = sqlsrv_query($conn,$sql);
    $letra='R'; 
    while($prod = sqlsrv_fetch_array($result)){
        $letra++;
        $Array_clasif[$num] = $prod['Clasificacion'];
        $Array_position[$prod['Clasificacion']] = $num;
        $num++;
        $objPHPExcel->setActiveSheetIndex(0) 
        ->setCellValue($letra.'6',utf8_encode($prod['Clasificacion']));
    }
    $titulo_ini = $letra;
    $titulo_ini++;
    $objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('S5:'.$letra.'5')
        ->setCellValue('S5','PRODUCTOS OBTENIDOS');
    $objPHPExcel->getActiveSheet()->getStyle('S5:'.$letra.'5')->applyFromArray($estiloTituloColumnas3);
    $objPHPExcel->getActiveSheet()->getStyle('S6:'.$letra.'6')->applyFromArray($estiloTituloColumnas3_1);
    for ($i=0; $i < $num; $i++){
        $letra++;
        $objPHPExcel->setActiveSheetIndex(0) 
            ->setCellValue($letra.'6',utf8_encode($Array_clasif[$i]));
    }
    $letra++;
    $objPHPExcel->setActiveSheetIndex(0) 
            ->setCellValue($letra.'6','PALA/H');
    $letra++;
    $objPHPExcel->setActiveSheetIndex(0) 
        ->setCellValue($letra.'6','TM/H');
    $num = 0;
    $count = count($Array_clasif);
    $objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells($titulo_ini.'5:'.$letra.'5')
        ->setCellValue($titulo_ini.'5','RENDIMIENTOS');
        $objPHPExcel->getActiveSheet()->getStyle($titulo_ini.'5:'.$letra.'5')->applyFromArray($estiloTituloColumnas4);
        $objPHPExcel->getActiveSheet()->getStyle($titulo_ini.'6:'.$letra.'6')->applyFromArray($estiloTituloColumnas4_1);
    $fila = 6;
    while ($data = sqlsrv_fetch_array($res)){
      $fila++;
      $valor = 0;
      $id_registro = $data['id_registro'];

      $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$fila,date_format($data['fecha_apertura_tique'],'d-m-Y'))
          ->setCellValue('B'.$fila, $data['Acopio'])
          ->setCellValue('C'.$fila,$data['Zona'])
          ->setCellValue('D'.$fila,$data['Pila'])
          ->setCellValue('E'.$fila,utf8_encode($data['Clasificacion']))
          ->setCellValue('F'.$fila,$data['Equipo'])
          ->setCellValue('G'.$fila,$data['cod_reporte'])
          ->setCellValue('H'.$fila,$data['NombreUsuarioLargo'])
          ->setCellValue('I'.$fila,$data['Cargador'])
          ->setCellValue('J'.$fila,$data['horometro_ini'])
          ->setCellValue('K'.$fila,$data['horometro_fin'])
          ->setCellValue('L'.$fila,number_format($data['horas_trabajadas'],1));
          $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion_izquierda, 'A'.$fila.':B'.$fila);
          $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion_centrado, 'C'.$fila.':I'.$fila);
      $sql = "SELECT  subactividades_cargadores.Descripcion AS SubActividades, SUM(horometro.total_horas) AS total_horas
      FROM Pila_informes INNER JOIN
           tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
           Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
           DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
           horometro INNER JOIN
           Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
           subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
           Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
           Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
           Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
           Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
           tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
           Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
      WHERE (tiempos_cargadores_actividad.idRegistro='$id_registro') AND (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '2019-09-27' AND '2019-09-27') AND (Registro_tique_cargadores.id_proveedor = '24B7153E-AB4C-4DB7-81BD-67BC87AF014C') AND 
           (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion in ('ALIMENTAR','APILAR'))
           and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '569D8AD0-A401-4AFE-BD52-A91974C7D2B0')
      GROUP BY subactividades_cargadores.Descripcion
      ORDER BY subactividades_cargadores.Descripcion";
      $consul = sqlsrv_query($conn,$sql);
      $letra = 'L';
      while($a = sqlsrv_fetch_array($consul)){
          $letra++;
          if($a['SubActividades'] == 'ALIMENTAR'){
              $valor+=$a['total_horas'];
          }elseif($a['SubActividades'] == 'APILAR'){
              $valor+=$a['total_horas'];
          }
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue($letra.$fila,$a['total_horas']);
      }
      $letra = 'R';
      $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('O'.$fila,$valor)
          ->setCellValue('P'.$fila,$data['paladas'])
          ->setCellValue('Q'.$fila,$data['total_horas'])
          ->setCellValue('R'.$fila,$data['TM_total']);

      $sql = "SELECT  Clasificacion.Descripcion AS Clasificacion, sum(tiempos_cargadores_actividad.TM_total) AS TM_total
      FROM Pila_informes INNER JOIN
         tiempos_cargadores_actividad ON Pila_informes.id_pila = tiempos_cargadores_actividad.idPila INNER JOIN
         Equipos AS Equipos_1 ON tiempos_cargadores_actividad.idEquipo = Equipos_1.idEquipo INNER JOIN
         DestinoZonas ON tiempos_cargadores_actividad.idZona = DestinoZonas.idZona RIGHT OUTER JOIN
         horometro INNER JOIN
         Actividades ON horometro.idActividad = Actividades.idActividad INNER JOIN
         subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad INNER JOIN
         Registro_tique_cargadores ON horometro.id_registro = Registro_tique_cargadores.id_registro INNER JOIN
         Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
         Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
         Proveedores ON Equipos.idPropietario = Proveedores.idProveedor ON tiempos_cargadores_actividad.idRegistro = horometro.id_registro AND tiempos_cargadores_actividad.idSubActividad = horometro.idSubActividad AND 
         tiempos_cargadores_actividad.idClasificacion = horometro.idClasificacion LEFT OUTER JOIN
         Clasificacion ON horometro.idClasificacion = Clasificacion.idClasificacion
      WHERE        (CAST(horometro.fecha_registro_horometro AS date) BETWEEN '2019-09-27' AND '2019-09-27') AND (Registro_tique_cargadores.id_proveedor = '24B7153E-AB4C-4DB7-81BD-67BC87AF014C') AND 
        (Registro_tique_cargadores.estado = '3') AND (Actividades.Descripcion IN ('CLASIFICAR ROOM', 'CLASIFICAR SOBRETAMAÑO', 'MOLIENDA')) AND (subactividades_cargadores.Descripcion = 'APILAR')
        and tiempos_cargadores_actividad.idEquipo in (SELECT idEquipos from detalle_equipos WHERE clase_equipo = '569D8AD0-A401-4AFE-BD52-A91974C7D2B0')
        AND (tiempos_cargadores_actividad.idRegistro='$id_registro')
      GROUP BY  Clasificacion.Descripcion
      ORDER BY Clasificacion.Descripcion";
      $result = sqlsrv_query($conn,$sql);

      while($prod = sqlsrv_fetch_array($result)){
        //$letra++;
        if(utf8_encode($Array_clasif[$position]) == utf8_encode($prod['Clasificacion'])){
            $letra++;
            $position++; 
            $position1++;
            $Array_rendimiento[$num_rendimiento] = $prod['TM_total'];
            $num_rendimiento++;
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue($letra.$fila,$prod['TM_total']);
            
        }else{
            $position+=2;
            $position1+=2;
            $Array_rendimiento[$num_rendimiento] = 0;
            $num_rendimiento++;
            $letra++;
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue($letra.$fila,'0');
            $letra++;
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue($letra.$fila,$prod['TM_total']);
            
            $Array_rendimiento[$num_rendimiento] = $prod['TM_total'];
            $num_rendimiento++;
        }
      }//cierre while interno
      if($count == $position1){
        $position=0;
        $position1=0;
      }elseif($count > $position1){
        $resta = $count-$position1;
        for ($i=0; $i < $resta; $i++) {
          $letra++;
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($letra.$fila,'0');
                          
        }
        $position=0;
        $position1=0;
      }
        //
      for ($i=0; $i < $num_rendimiento; $i++) {
        if($Array_rendimiento[$i] <> 0){
          $paso++;
          $letra++;
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($letra.$fila,number_format(($Array_rendimiento[$i]/$data['TM_total'])*100,2,'.',',').' %');
            
        }else{
          $paso++;
          $letra++;
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($letra.$fila,'0');
            
        }
        //$letra++;
      }
      if($count == $paso){
          $paso=0;
      }elseif($count > $paso){
          $resta1 = $count-$paso;
          for ($i=0; $i < $resta1; $i++) {
            $letra++;
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue($letra.$fila,'0');
            
          }
          $paso=0;
      }
      $letra++;
      $num_rendimiento = 0;
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($letra.$fila,number_format($data['paladas']/$valor,2,'.',','));
      $letra++;
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($letra.$fila,number_format($data['TM_total']/$valor,2,'.',',')); 
      $valor = 0;
      $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion_derecha, 'C'.$fila.':'.$letra.$fila);
    } //cierre while  exterior
    //print_r($Array_clasif);
      //print_r($Array_rendimiento);
     $objPHPExcel->getActiveSheet()->getStyle('A1:R1')->applyFromArray($estiloTituloReporte);
     $objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':'.$letra.$fila)->applyFromArray($estiloFila_ultima);
     $objPHPExcel->getActiveSheet()->getStyle('A6:O6')->applyFromArray($estiloTituloColumnas1);
     $objPHPExcel->getActiveSheet()->getStyle('P5:R5')->applyFromArray($estiloTituloColumnas2);
     $objPHPExcel->getActiveSheet()->getStyle('P6:R6')->applyFromArray($estiloTituloColumnas2_1);

    $objPHPExcel->getActiveSheet()->freezePaneByColumnAndRow(0,7);

    for($j = 'A'; $j <= $letra; $j++)
    {   $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($j)->setAutoSize(TRUE);   

    }
    //=SUBTOTALES(9;M7:M'.$fila.') EL 9 SUMA LOS VALORES Y EL 2 CUENTA LOS REGISTRO
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('M3','=SUBTOTALES(9;M7:M'.$fila.')');
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('N3','=SUBTOTALES(9;N7:N'.$fila.')');
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('O3','=SUBTOTALES(9;O7:O'.$fila.')');
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('P3','=SUBTOTALES(9;P7:P'.$fila.')');
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('Q3','=SUBTOTALES(9;Q7:Q'.$fila.')');
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('R3','=SUBTOTALES(9;R7:R'.$fila.')');
    $vocal = 'R';
    for ($i=0; $i < $count; $i++) {
      $vocal++;
      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit($vocal.'3','=SUBTOTALES(9;'.$vocal.'7:'.$vocal.$fila.')');
    }
    for ($i=0; $i < $count; $i++) {
      $vocal++;
      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit($vocal.'3','=SUBTOTALES(9;'.$vocal.'7:'.$vocal.$fila.')');
    }
    $vocal++;
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit($vocal.'3','=SUBTOTALES(9;'.$vocal.'7:'.$vocal.$fila.')');
    $vocal++;
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit($vocal.'3','=SUBTOTALES(9;'.$vocal.'7:'.$vocal.$fila.')');
 /*   $highestRow = $$objPHPExcel->getActiveSheet()->getHighestRow();
      for ($row = 1; $row <= $highestRow; $row++){
          $sheet->getStyle('D'.$row)->getAlignment()->setWrapText(true);
      }
*/
}// cierre si trae filas
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="REPORTE_MAQUINARIA.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>