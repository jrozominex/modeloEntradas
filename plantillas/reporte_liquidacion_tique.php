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

if($empresa != 1){
      /*$sql="SELECT Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, 
                         Registro_tique_cargadores.id_usuario, Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, Registro_tique_cargadores.horas_trabajadas, 
                         Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado, Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, 
                         Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, Usuarios.NombreUsuarioLargo, P.NombreCorto AS proveedor,
                         Registro_tique_cargadores.horometro_ini, Registro_tique_cargadores.horometro_fin
        FROM            Registro_tique_cargadores INNER JOIN
                                 Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                                 Proveedores AS P ON Equipos.idPropietario = P.idProveedor INNER JOIN
                                 Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
        WHERE        (Registro_tique_cargadores.estado = '3') AND Registro_tique_cargadores.id_registro in (select numerotransaccion FROM Factura_venta_detalle where id_factura_venta='$id_factura') and
                                 (Registro_tique_cargadores.id_proveedor = '$empresa') 
        ORDER BY Registro_tique_cargadores.cod_reporte DESC";*/
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
             (SELECT        numerotransaccion
               FROM            Factura_Venta_Detalle
               WHERE        (id_factura_venta = '$id_factura'))) AND (Registro_tique_cargadores.id_proveedor = '$empresa') AND 
         (TarifaMaquinaria.Fecha_Hasta = '1900-01-01')
        ORDER BY Registro_tique_cargadores.cod_reporte DESC";
}
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
while($save = sqlsrv_fetch_array($corre)){
  $id_registro = $save['id_registro'];
  $query = "SELECT Destino.Descripcion, sum(tiempos_cargadores_actividad.TM_total) as TM_total
    FROM tiempos_cargadores_actividad INNER JOIN
       Registro_tique_cargadores ON tiempos_cargadores_actividad.idRegistro = Registro_tique_cargadores.id_registro LEFT OUTER JOIN
       Destino ON tiempos_cargadores_actividad.idDestino = Destino.idDestino
    where tiempos_cargadores_actividad.idRegistro='$id_registro' and 
    tiempos_cargadores_actividad.idSubActividad='FD78D664-C2B4-4994-A72A-4D55A62FB462'
    GROUP BY Destino.Descripcion ORDER BY Destino.Descripcion";
  $corre1 = sqlsrv_query($conn,$query);
  while($save1 = sqlsrv_fetch_array($corre1)){
      $Array_destino_nom[$posi]['nom']=$save1['Descripcion'];
      $Array_destino_nom[$posi]['tm']=$save1['TM_total'];
      $posi++;
  }
}
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
$var = 0;
$letra_ini = 'W';
$letra_fin = 'V';
for ($i=0; $i < count($result); $i++) { 
  $letra_fin++;
  $objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue($letra_fin.'9',$result[$i]['nom']);
  $Array_position_nom[$result[$i]['nom']] = $letra_fin;
}
$objPHPExcel->setActiveSheetIndex(0)
  ->mergeCells($letra_ini.'8:'.$letra_fin.'8')
  ->setCellValue($letra_ini.'8','DESTINOS');
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
    /*->mergeCells('E8:E9')
    ->setCellValue('E8',$titulosColumnas[3])*/
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
    ->mergeCells('K8:L8')
    ->setCellValue('K8','CLASIFICAR ROOM')
    ->setCellValue('K9',$titulosColumnas[14])
    ->setCellValue('L9',$titulosColumnas[15])
    ->mergeCells('M8:N8')
    ->setCellValue('M8','CLASIFICAR SOBRETAMAÑO')
    ->setCellValue('M9',$titulosColumnas[14])
    ->setCellValue('N9',$titulosColumnas[15])
    ->mergeCells('O8:O9')
    ->setCellValue('O8',$titulosColumnas[16])
    ->mergeCells('P8:R8')
    ->setCellValue('P8','ENTRADAS')
    ->setCellValue('P9',$titulosColumnas[17])
    ->setCellValue('Q9',$titulosColumnas[18])
    ->setCellValue('R9',$titulosColumnas[19])
    ->mergeCells('S8:T8')
    ->setCellValue('S8','MOLIENDA')
    ->setCellValue('S9',$titulosColumnas[14])
    ->setCellValue('T9',$titulosColumnas[15])
    ->mergeCells('U8:U9')
    ->setCellValue('U8',$titulosColumnas[20])
    ->mergeCells('V8:V9')
    ->setCellValue('V8','TM CARGUE')
    ->setSharedStyle($estilo_informacion1, 'A8:'.$letra_fin.'9');  //Titulo de las columnas
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$letra_fin.'1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A7:'.$letra_fin.'7')->applyFromArray($estiloTituloReporte_1);
//$objPHPExcel->setActiveSheetIndex(0)->setSharedStyle($estilo_informacion, 'I2:T2');
$objPHPExcel->getActiveSheet()->getStyle('A8:I9')->applyFromArray($estiloTituloColumnas);
$objPHPExcel->getActiveSheet()->getStyle('J8:J9')->applyFromArray($estiloTituloColumnas1);
$objPHPExcel->getActiveSheet()->getStyle('K8:L9')->applyFromArray($estiloTituloColumnas2);
$objPHPExcel->getActiveSheet()->getStyle('M8:N9')->applyFromArray($estiloTituloColumnas3);
$objPHPExcel->getActiveSheet()->getStyle('O8:Q9')->applyFromArray($estiloTituloColumnas4);
$objPHPExcel->getActiveSheet()->getStyle('P8:R9')->applyFromArray($estiloTituloColumnas5);
$objPHPExcel->getActiveSheet()->getStyle('S8:T9')->applyFromArray($estiloTituloColumnas6);
$objPHPExcel->getActiveSheet()->getStyle('U8:U9')->applyFromArray($estiloTituloColumnas7);
$objPHPExcel->getActiveSheet()->getStyle('V8:V9')->applyFromArray($estiloTituloColumnas8);
$objPHPExcel->getActiveSheet()->getStyle($letra_ini.'8:'.$letra_fin.'9')->applyFromArray($estiloTituloColumnas9);
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
{ $count_registros++;
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
    /*$sql_1 = "SELECT horometro.fecha_cierre_horometro ,horometro.id_horometro, horometro.id_registro, horometro.horometro_inicial, 
        horometro.horometro_final, horometro.total_horas, horometro.idActividad, horometro.fecha_registro_horometro, 
        Actividades.Descripcion, SubActividades_cargadores.Descripcion as Descripcion_sub, horometro.observaciones
        FROM horometro LEFT JOIN
        Actividades ON horometro.idActividad = Actividades.idActividad
        LEFT JOIN SubActividades_cargadores ON horometro.idSubActividad=SubActividades_cargadores.idSubActividad
        WHERE horometro.id_registro='". $r['id_registro'] ."' ORDER BY Actividades.Descripcion,SubActividades_cargadores.Descripcion";*/

    $sql_1 = "SELECT horometro.fecha_cierre_horometro, horometro.id_horometro, horometro.id_registro, horometro.horometro_inicial, 
          horometro.horometro_final, horometro.total_horas, horometro.idActividad, horometro.fecha_registro_horometro, 
          Actividades.Descripcion, subactividades_cargadores.Descripcion AS Descripcion_sub, horometro.Observaciones, 
          SUM(tiempos_cargadores_actividad.TM_total) AS TM_total
        FROM horometro INNER JOIN
         tiempos_cargadores_actividad ON horometro.id_registro = tiempos_cargadores_actividad.idRegistro LEFT OUTER JOIN
         Actividades ON horometro.idActividad = Actividades.idActividad LEFT OUTER JOIN
         subactividades_cargadores ON horometro.idSubActividad = subactividades_cargadores.idSubactividad
        WHERE (horometro.id_registro = '". $r['id_registro'] ."') AND horometro.idActividad is not null  and horometro.tipo_tarifa<>1 AND tiempos_cargadores_actividad.tipo_tarifa<>1
        GROUP BY horometro.fecha_cierre_horometro, horometro.id_horometro, horometro.id_registro, horometro.horometro_inicial, 
          horometro.horometro_final, horometro.total_horas, horometro.idActividad, horometro.fecha_registro_horometro, 
          Actividades.Descripcion, subactividades_cargadores.Descripcion, horometro.Observaciones
        ORDER BY Actividades.Descripcion, SubActividades_cargadores.Descripcion";

    $res_1 = sqlsrv_query($conn,$sql_1);
    while($horometro = sqlsrv_fetch_array($res_1)){
        /***********************************************************************************************************************************************************************/
        if($band == 0){
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
            $activdad = utf8_encode($horometro['Descripcion']);
            $subactividad = utf8_encode($horometro['Descripcion_sub']);
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
            if($activdad == 'CLASIFICAR ROOM'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $band=1;
                if($subactividad == 'ALIMENTAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_room_alimentar+=$horometro['total_horas'];
                    $total_total_room_alimentar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_room = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'APILAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('K'.$i,0);
                    $total_room_apilar+=$horometro['total_horas'];
                    $total_total_room_apilar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_room = 2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'CLASIFICAR SOBRETAMAÑO'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $band=2;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('K'.$i,0)
                    ->setCellValue('L'.$i,0);
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'ALIMENTAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_sobretamaño_alimentar+=$horometro['total_horas'];
                    $total_total_sobretamaño_alimentar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_sobreta = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'APILAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('M'.$i,0);
                    $total_sobretamaño_apilar+=$horometro['total_horas'];
                    $total_total_sobretamaño_apilar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_sobreta = 2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            }elseif($activdad == 'DESPACHO'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $band=3;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('K'.$i,0)
                    ->setCellValue('L'.$i,0)
                    ->setCellValue('M'.$i,0)
                    ->setCellValue('N'.$i,0);
                if($subactividad == 'CARGAR DESPACHO'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_cargar_despacho+=$horometro['total_horas'];
                    $total_total_cargar_despacho+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_despacho = 1;
                    $tm_despacho+=$horometro['TM_total'];
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'ENTRADAS'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $band=4;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('K'.$i,0)
                    ->setCellValue('L'.$i,0)
                    ->setCellValue('M'.$i,0)
                    ->setCellValue('N'.$i,0)
                    ->setCellValue('O'.$i,0);
                if($subactividad == 'APILAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_entradas_apilar+=$horometro['total_horas'];
                    $total_total_entradas_apilar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_entradas = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'MVTO. X CALIDAD'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('P'.$i,0);
                    $total_entradas_mvto+=$horometro['total_horas'];
                    $total_total_entradas_mvto+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_entradas = 2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'OFICIOS VARIOS'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('P'.$i,0)
                    ->setCellValue('Q'.$i,0);
                    $total_entradas_varios+=$horometro['total_horas'];
                    $total_total_entradas_varios+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_entradas = 3;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'MOLIENDA'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $band=5;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('K'.$i,0)
                    ->setCellValue('L'.$i,0)
                    ->setCellValue('M'.$i,0)
                    ->setCellValue('N'.$i,0)
                    ->setCellValue('O'.$i,0)
                    ->setCellValue('P'.$i,0)
                    ->setCellValue('Q'.$i,0)
                    ->setCellValue('R'.$i,0);
                if($subactividad == 'ALIMENTAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_molienda_alimentar+=$horometro['total_horas'];
                    $total_total_molienda_alimentar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_molienda = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'APILAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('S'.$i,0);
                    $total_molienda_apilar+=$horometro['total_horas'];
                    $total_total_molienda_apilar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_molienda = 2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'STANDBY'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $band=6;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('K'.$i,0)
                    ->setCellValue('L'.$i,0)
                    ->setCellValue('M'.$i,0)
                    ->setCellValue('N'.$i,0)
                    ->setCellValue('O'.$i,0)
                    ->setCellValue('P'.$i,0)
                    ->setCellValue('Q'.$i,0)
                    ->setCellValue('R'.$i,0)
                    ->setCellValue('S'.$i,0)
                    ->setCellValue('T'.$i,0);
                if($subactividad == 'STANDBY'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_standby+=$horometro['total_horas'];
                    $total_total_standby+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_standby = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////        
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        }elseif($band == 1){
          $activdad = utf8_encode($horometro['Descripcion']);
          $subactividad = utf8_encode($horometro['Descripcion_sub']);
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
            if($activdad == 'CLASIFICAR ROOM'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'ALIMENTAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_room_alimentar+=$horometro['total_horas'];
                    $total_total_room_alimentar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_room = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'APILAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    if($cont_room == 1){
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('K'.$i,number_format($total_room_alimentar,1));
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('K'.$i,0);
                    }
                    $total_room_apilar+=$horometro['total_horas'];
                    $total_total_room_apilar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_room = 2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'CLASIFICAR SOBRETAMAÑO'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $activdad = utf8_encode($horometro['Descripcion']);
                $subactividad = utf8_encode($horometro['Descripcion_sub']);
                $band=2;
                if($cont_room == 1){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('K'.$i,number_format($total_room_alimentar,1))
                        ->setCellValue('L'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($cont_room == 2){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('L'.$i,number_format($total_room_apilar,1));
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
                $paso = 0;
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'ALIMENTAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_sobretamaño_alimentar+=$horometro['total_horas'];
                    $total_total_sobretamaño_alimentar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_sobreta = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'APILAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('M'.$i,0);
                    ?><td style="vertical-align: middle; text-align: center;">0</td><?php
                    $total_sobretamaño_apilar+=$horometro['total_horas'];
                    $total_total_sobretamaño_apilar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_sobreta = 2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            }elseif($activdad == 'DESPACHO'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $activdad = utf8_encode($horometro['Descripcion']);
                $subactividad = utf8_encode($horometro['Descripcion_sub']);
                $band=3;
                if($cont_room == 1){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('K'.$i,number_format($total_room_alimentar,1))
                        ->setCellValue('L'.$i,0)
                        ->setCellValue('M'.$i,0)
                        ->setCellValue('N'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($cont_room == 2){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('L'.$i,number_format($total_room_apilar,1))
                        ->setCellValue('M'.$i,0)
                        ->setCellValue('N'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
                $paso = 0;
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'CARGAR DESPACHO'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_cargar_despacho+=$horometro['total_horas'];
                    $total_total_cargar_despacho+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_despacho = 1;
                    $tm_despacho+=$horometro['TM_total'];
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'ENTRADAS'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $activdad = utf8_encode($horometro['Descripcion']);
                $subactividad = utf8_encode($horometro['Descripcion_sub']);
                $band=4;
                if($cont_room == 1){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('K'.$i,number_format($total_room_alimentar,1))
                        ->setCellValue('L'.$i,0)
                        ->setCellValue('M'.$i,0)
                        ->setCellValue('N'.$i,0)
                        ->setCellValue('O'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($cont_room == 2){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('L'.$i,number_format($total_room_apilar,1))
                        ->setCellValue('M'.$i,0)
                        ->setCellValue('N'.$i,0)
                        ->setCellValue('O'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
                $paso = 0;
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'APILAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_entradas_apilar+=$horometro['total_horas'];
                    $total_total_entradas_apilar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_entradas = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'MVTO. X CALIDAD'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('P'.$i,0);
                    $total_entradas_mvto+=$horometro['total_horas'];
                    $total_total_entradas_mvto+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_entradas = 2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'OFICIOS VARIOS'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('P'.$i,0)
                        ->setCellValue('Q'.$i,0);
                    $total_entradas_varios+=$horometro['total_horas'];
                    $total_total_entradas_varios+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_entradas = 3;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'MOLIENDA'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $activdad = utf8_encode($horometro['Descripcion']);
                $subactividad = utf8_encode($horometro['Descripcion_sub']);
                $band=5;
                if($cont_room == 1){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('K'.$i,number_format($total_room_alimentar,1))
                        ->setCellValue('L'.$i,0)
                        ->setCellValue('M'.$i,0)
                        ->setCellValue('N'.$i,0)
                        ->setCellValue('O'.$i,0)
                        ->setCellValue('P'.$i,0)
                        ->setCellValue('Q'.$i,0)
                        ->setCellValue('R'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($cont_room == 2){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('L'.$i,number_format($total_room_apilar,1))
                        ->setCellValue('M'.$i,0)
                        ->setCellValue('N'.$i,0)
                        ->setCellValue('O'.$i,0)
                        ->setCellValue('P'.$i,0)
                        ->setCellValue('Q'.$i,0)
                        ->setCellValue('R'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
                $paso = 0;
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'ALIMENTAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_molienda_alimentar+=$horometro['total_horas'];
                    $total_total_molienda_alimentar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_molienda = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'APILAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('S'.$i,0);
                    $total_molienda_apilar+=$horometro['total_horas'];
                    $total_total_molienda_apilar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_molienda = 2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'STANDBY'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $activdad = utf8_encode($horometro['Descripcion']);
                $subactividad = utf8_encode($horometro['Descripcion_sub']);
                $band=6;
                if($cont_room == 1){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('K'.$i,number_format($total_room_alimentar,1))
                        ->setCellValue('L'.$i,0)
                        ->setCellValue('M'.$i,0)
                        ->setCellValue('N'.$i,0)
                        ->setCellValue('O'.$i,0)
                        ->setCellValue('P'.$i,0)
                        ->setCellValue('Q'.$i,0)
                        ->setCellValue('R'.$i,0)
                        ->setCellValue('S'.$i,0)
                        ->setCellValue('T'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($cont_room == 2){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('L'.$i,number_format($total_room_apilar,1))
                        ->setCellValue('M'.$i,0)
                        ->setCellValue('N'.$i,0)
                        ->setCellValue('O'.$i,0)
                        ->setCellValue('P'.$i,0)
                        ->setCellValue('Q'.$i,0)
                        ->setCellValue('R'.$i,0)
                        ->setCellValue('S'.$i,0)
                        ->setCellValue('T'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'STANDBY'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_standby+=$horometro['total_horas'];
                    $total_total_standby+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_standby = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////        
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        }elseif($band == 2){
          $activdad = utf8_encode($horometro['Descripcion']);
          $subactividad = utf8_encode($horometro['Descripcion_sub']);
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
            if($activdad == 'CLASIFICAR SOBRETAMAÑO'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'ALIMENTAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_sobretamaño_alimentar+=$horometro['total_horas'];
                    $total_total_sobretamaño_alimentar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_sobreta = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'APILAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    if($cont_sobreta == 1){
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('M'.$i,number_format($total_sobretamaño_alimentar,1));
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('M'.$i,0);
                    }
                    $total_sobretamaño_apilar+=$horometro['total_horas'];
                    $total_total_sobretamaño_apilar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_sobreta = 2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            }elseif($activdad == 'DESPACHO'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $activdad = utf8_encode($horometro['Descripcion']);
                $subactividad = utf8_encode($horometro['Descripcion_sub']);
                $band=3;
                if($cont_sobreta == 1){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('M'.$i,number_format($total_sobretamaño_alimentar,1))
                        ->setCellValue('N'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($cont_sobreta == 2){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('N'.$i,number_format($total_sobretamaño_apilar,1));
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
                $paso = 0;
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'CARGAR DESPACHO'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_cargar_despacho+=$horometro['total_horas'];
                    $total_total_cargar_despacho+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_despacho = 1;
                    $tm_despacho+=$horometro['TM_total'];
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'ENTRADAS'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $activdad = utf8_encode($horometro['Descripcion']);
                $subactividad = utf8_encode($horometro['Descripcion_sub']);
                $band=4;
                if($cont_sobreta == 1){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('M'.$i,number_format($total_sobretamaño_alimentar,1))
                        ->setCellValue('N'.$i,0)
                        ->setCellValue('O'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($cont_sobreta == 2){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('N'.$i,number_format($total_sobretamaño_apilar,1))
                        ->setCellValue('O'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
                $paso = 0;
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'APILAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_entradas_apilar+=$horometro['total_horas'];
                    $total_total_entradas_apilar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_entradas = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'MVTO. X CALIDAD'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('P'.$i,0);
                    $total_entradas_mvto+=$horometro['total_horas'];
                    $total_total_entradas_mvto+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_entradas = 2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'OFICIOS VARIOS'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('P'.$i,0)
                        ->setCellValue('Q'.$i,0);
                    $total_entradas_varios+=$horometro['total_horas'];
                    $total_total_entradas_varios+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_entradas = 3;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'MOLIENDA'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $activdad = utf8_encode($horometro['Descripcion']);
                $subactividad = utf8_encode($horometro['Descripcion_sub']);
                $band=5;
                if($cont_sobreta == 1){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('M'.$i,number_format($total_sobretamaño_alimentar,1))
                        ->setCellValue('N'.$i,0)
                        ->setCellValue('O'.$i,0)
                        ->setCellValue('P'.$i,0)
                        ->setCellValue('Q'.$i,0)
                        ->setCellValue('R'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($cont_sobreta == 2){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('N'.$i,number_format($total_sobretamaño_apilar,1))
                        ->setCellValue('O'.$i,0)
                        ->setCellValue('P'.$i,0)
                        ->setCellValue('Q'.$i,0)
                        ->setCellValue('R'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
                $paso = 0;
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'ALIMENTAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_molienda_alimentar+=$horometro['total_horas'];
                    $total_total_molienda_alimentar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_molienda = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'APILAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('S'.$i,0);
                    $total_molienda_apilar+=$horometro['total_horas'];
                    $total_total_molienda_apilar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_molienda = 2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'STANDBY'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $activdad = utf8_encode($horometro['Descripcion']);
                $subactividad = utf8_encode($horometro['Descripcion_sub']);
                $band=6;
                if($cont_sobreta == 1){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('M'.$i,number_format($total_sobretamaño_alimentar,1))
                        ->setCellValue('N'.$i,0)
                        ->setCellValue('O'.$i,0)
                        ->setCellValue('P'.$i,0)
                        ->setCellValue('Q'.$i,0)
                        ->setCellValue('R'.$i,0)
                        ->setCellValue('S'.$i,0)
                        ->setCellValue('T'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($cont_sobreta == 2){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('N'.$i,number_format($total_sobretamaño_apilar,1))
                        ->setCellValue('O'.$i,0)
                        ->setCellValue('P'.$i,0)
                        ->setCellValue('Q'.$i,0)
                        ->setCellValue('R'.$i,0)
                        ->setCellValue('S'.$i,0)
                        ->setCellValue('T'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
                $paso = 0;
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'STANDBY'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_standby+=$horometro['total_horas'];
                    $total_total_standby+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_standby = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////        
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        }elseif($band == 3){
          $activdad = utf8_encode($horometro['Descripcion']);
          $subactividad = utf8_encode($horometro['Descripcion_sub']);
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
            if($activdad == 'DESPACHO'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'CARGAR DESPACHO'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_cargar_despacho+=$horometro['total_horas'];
                    $total_horas+=$horometro['total_horas'];
                    $cont_despacho = 1;
                    $tm_despacho+=$horometro['TM_total'];
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'ENTRADAS'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $activdad = utf8_encode($horometro['Descripcion']);
                $subactividad = utf8_encode($horometro['Descripcion_sub']);
                $band=4;
                if($cont_despacho == 1){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('O'.$i,number_format($total_cargar_despacho,1));
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
                $paso = 0;
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'APILAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_entradas_apilar+=$horometro['total_horas'];
                    $total_total_entradas_apilar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_entradas = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'MVTO. X CALIDAD'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('P'.$i,0);
                    $total_entradas_mvto+=$horometro['total_horas'];
                    $total_total_entradas_mvto+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_entradas = 2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'OFICIOS VARIOS'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('P'.$i,0)
                        ->setCellValue('Q'.$i,0);
                    $total_entradas_varios+=$horometro['total_horas'];
                    $total_total_entradas_varios+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_entradas = 3;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'MOLIENDA'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $activdad = utf8_encode($horometro['Descripcion']);
                $subactividad = utf8_encode($horometro['Descripcion_sub']);
                $band=5;
                if($cont_despacho == 1){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('O'.$i,number_format($total_cargar_despacho,1))
                        ->setCellValue('P'.$i,0)
                        ->setCellValue('Q'.$i,0)
                        ->setCellValue('R'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
                $paso = 0;
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'ALIMENTAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_molienda_alimentar+=$horometro['total_horas'];
                    $total_total_molienda_alimentar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_molienda = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'APILAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('S'.$i,0);
                    $total_molienda_apilar+=$horometro['total_horas'];
                    $total_total_molienda_apilar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_molienda = 2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'STANDBY'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $activdad = utf8_encode($horometro['Descripcion']);
                $subactividad = utf8_encode($horometro['Descripcion_sub']);
                $band=6;
                if($cont_despacho == 1){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('O'.$i,number_format($total_cargar_despacho,1))
                        ->setCellValue('P'.$i,0)
                        ->setCellValue('Q'.$i,0)
                        ->setCellValue('R'.$i,0)
                        ->setCellValue('S'.$i,0)
                        ->setCellValue('T'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
                $paso = 0;
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'STANDBY'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_standby+=$horometro['total_horas'];
                    $total_total_standby+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_standby = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////        
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        }elseif($band == 4){
          $activdad = utf8_encode($horometro['Descripcion']);
          $subactividad = utf8_encode($horometro['Descripcion_sub']);
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
            if($activdad == 'ENTRADAS'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'APILAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_entradas_apilar+=$horometro['total_horas'];
                    $total_total_entradas_apilar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_entradas = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'MVTO. X CALIDAD'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    if($cont_entradas == 1){
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('P'.$i,number_format($total_entradas_apilar,1));
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('P'.$i,0);
                    }
                    $total_entradas_mvto+=$horometro['total_horas'];
                    $total_total_entradas_mvto+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_entradas = 2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'OFICIOS VARIOS'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    if($cont_entradas == 1){
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('P'.$i,number_format($total_entradas_apilar,1))
                            ->setCellValue('Q'.$i,0);
                    }elseif($cont_entradas == 2){
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('Q'.$i,number_format($total_entradas_mvto,1));
                    }
                    $total_entradas_varios+=$horometro['total_horas'];
                    $total_total_entradas_varios+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_entradas = 3;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'MOLIENDA'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $activdad = utf8_encode($horometro['Descripcion']);
                $subactividad = utf8_encode($horometro['Descripcion_sub']);
                $band=5;
                if($cont_entradas == 1){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('P'.$i,number_format($total_entradas_apilar,1))
                        ->setCellValue('Q'.$i,0)
                        ->setCellValue('R'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($cont_entradas == 2){
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('Q'.$i,number_format($total_entradas_mvto,1))
                        ->setCellValue('R'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($cont_entradas == 3){
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('R'.$i,number_format($total_entradas_varios,1));
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
                $paso = 0;
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'ALIMENTAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_molienda_alimentar+=$horometro['total_horas'];
                    $total_total_molienda_alimentar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_molienda = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'APILAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('S'.$i,0);
                    $total_molienda_apilar+=$horometro['total_horas'];
                    $total_total_molienda_apilar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_molienda = 2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'STANDBY'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $activdad = utf8_encode($horometro['Descripcion']);
                $subactividad = utf8_encode($horometro['Descripcion_sub']);
                $band=6;
                if($cont_entradas == 1){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('P'.$i,number_format($total_entradas_apilar,1))
                        ->setCellValue('Q'.$i,0)
                        ->setCellValue('R'.$i,0)
                        ->setCellValue('S'.$i,0)
                        ->setCellValue('T'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($cont_entradas == 2){
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('Q'.$i,number_format($total_entradas_mvto,1))
                        ->setCellValue('R'.$i,0)
                        ->setCellValue('S'.$i,0)
                        ->setCellValue('T'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($cont_entradas == 3){
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('R'.$i,number_format($total_entradas_varios,1))
                        ->setCellValue('S'.$i,0)
                        ->setCellValue('T'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
                $paso = 0;
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'STANDBY'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_standby+=$horometro['total_horas'];
                    $total_total_standby+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_standby = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////        
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        }elseif($band == 5){
          $activdad = utf8_encode($horometro['Descripcion']);
          $subactividad = utf8_encode($horometro['Descripcion_sub']);
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
            if($activdad == 'MOLIENDA'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'ALIMENTAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_molienda_alimentar+=$horometro['total_horas'];
                    $total_total_molienda_alimentar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_molienda = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($subactividad == 'APILAR'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    if($cont_molienda == 1){
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('S'.$i,number_format($total_molienda_alimentar,1));
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('S'.$i,0);
                    }
                    $total_molienda_apilar+=$horometro['total_horas'];
                    $total_total_molienda_apilar+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_molienda = 2;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }elseif($activdad == 'STANDBY'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                $activdad = utf8_encode($horometro['Descripcion']);
                $subactividad = utf8_encode($horometro['Descripcion_sub']);
                $band=6;
                if($cont_molienda == 1){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('S'.$i,number_format($total_molienda_alimentar,1))
                        ->setCellValue('S'.$i,0);
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                }elseif($cont_molienda == 2){
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('T'.$i,number_format($total_molienda_apilar,1));
                }
                $paso = 0;
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'STANDBY'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_standby+=$horometro['total_horas'];
                    $total_total_standby+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_standby = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////        
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        }elseif($band == 6){
          $activdad = utf8_encode($horometro['Descripcion']);
          $subactividad = utf8_encode($horometro['Descripcion_sub']);
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
            if($activdad == 'STANDBY'){
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
                if($subactividad == 'STANDBY'){
                ///////////////////////////////////////////////////////////////////////////////////////////////////////
                    $total_standby+=$horometro['total_horas'];
                    $total_total_standby+=number_format($horometro['total_horas'],1);
                    $total_horas+=$horometro['total_horas'];
                    $cont_standby = 1;
                ///////////////////////////////////////////////////////////////////////////////////////////////////////        
                }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////

        /**********************************************************************************************************************************************************************/
        $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estiloTituloReporte_2);
        /*$objPHPExcel->setActiveSheetIndex(0)
            //->setCellValue('K'.$i,$total_horas)
            ->setCellValue('X'.$i,date_format($r['fecha_ini_jornada'],'Y-m-d H:i:s'))
            ->setCellValue('Y'.$i,date_format($r['fecha_fin_jornada'],'Y-m-d H:i:s'));*/
    }
    ///FIN VARIABLES BAND
    if($band == 0){
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('K'.$i,0)
        ->setCellValue('L'.$i,0)
        ->setCellValue('M'.$i,0)
        ->setCellValue('N'.$i,0)
        ->setCellValue('O'.$i,0)
        ->setCellValue('P'.$i,0)
        ->setCellValue('Q'.$i,0)
        ->setCellValue('R'.$i,0)
        ->setCellValue('S'.$i,0)
        ->setCellValue('T'.$i,0)
        ->setCellValue('U'.$i,0);
    }elseif($band == 1){
        if($cont_room == 0){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('K'.$i,0)
            ->setCellValue('L'.$i,0)
            ->setCellValue('M'.$i,0)
            ->setCellValue('N'.$i,0)
            ->setCellValue('O'.$i,0)
            ->setCellValue('P'.$i,0)
            ->setCellValue('Q'.$i,0)
            ->setCellValue('R'.$i,0)
            ->setCellValue('S'.$i,0)
            ->setCellValue('T'.$i,0)
            ->setCellValue('U'.$i,0);
        }elseif($cont_room == 1){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('K'.$i,number_format($total_room_alimentar,1))
            ->setCellValue('L'.$i,0)
            ->setCellValue('M'.$i,0)
            ->setCellValue('N'.$i,0)
            ->setCellValue('O'.$i,0)
            ->setCellValue('P'.$i,0)
            ->setCellValue('Q'.$i,0)
            ->setCellValue('R'.$i,0)
            ->setCellValue('S'.$i,0)
            ->setCellValue('T'.$i,0)
            ->setCellValue('U'.$i,0);
        }elseif($cont_room == 2){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('L'.$i,number_format($total_room_apilar,1))
            ->setCellValue('M'.$i,0)
            ->setCellValue('N'.$i,0)
            ->setCellValue('O'.$i,0)
            ->setCellValue('P'.$i,0)
            ->setCellValue('Q'.$i,0)
            ->setCellValue('R'.$i,0)
            ->setCellValue('S'.$i,0)
            ->setCellValue('T'.$i,0)
            ->setCellValue('U'.$i,0);
        }
    }elseif($band == 2){
        if($cont_sobreta == 0){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('M'.$i,0)
            ->setCellValue('N'.$i,0)
            ->setCellValue('O'.$i,0)
            ->setCellValue('P'.$i,0)
            ->setCellValue('Q'.$i,0)
            ->setCellValue('R'.$i,0)
            ->setCellValue('S'.$i,0)
            ->setCellValue('T'.$i,0)
            ->setCellValue('U'.$i,0);
        }elseif($cont_sobreta == 1){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('M'.$i,number_format($total_sobretamaño_alimentar,1))
            ->setCellValue('N'.$i,0)
            ->setCellValue('O'.$i,0)
            ->setCellValue('P'.$i,0)
            ->setCellValue('Q'.$i,0)
            ->setCellValue('R'.$i,0)
            ->setCellValue('S'.$i,0)
            ->setCellValue('T'.$i,0)
            ->setCellValue('U'.$i,0);
        }elseif($cont_sobreta == 2){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('N'.$i,number_format($total_sobretamaño_apilar,1))
            ->setCellValue('O'.$i,0)
            ->setCellValue('P'.$i,0)
            ->setCellValue('Q'.$i,0)
            ->setCellValue('R'.$i,0)
            ->setCellValue('S'.$i,0)
            ->setCellValue('T'.$i,0)
            ->setCellValue('U'.$i,0);
        }
    }elseif($band == 3){
        if($cont_despacho == 0){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('O'.$i,0)
            ->setCellValue('P'.$i,0)
            ->setCellValue('Q'.$i,0)
            ->setCellValue('R'.$i,0)
            ->setCellValue('S'.$i,0)
            ->setCellValue('T'.$i,0)
            ->setCellValue('U'.$i,0);
        }elseif($cont_despacho == 1){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('O'.$i,number_format($total_cargar_despacho,1))
            ->setCellValue('P'.$i,0)
            ->setCellValue('Q'.$i,0)
            ->setCellValue('R'.$i,0)
            ->setCellValue('S'.$i,0)
            ->setCellValue('T'.$i,0)
            ->setCellValue('U'.$i,0);
        }
    }elseif($band == 4){
        if($cont_entradas == 0){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('P'.$i,0)
            ->setCellValue('Q'.$i,0)
            ->setCellValue('R'.$i,0)
            ->setCellValue('S'.$i,0)
            ->setCellValue('T'.$i,0)
            ->setCellValue('U'.$i,0);
        }elseif($cont_entradas == 1){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('P'.$i,number_format($total_entradas_apilar,1))
            ->setCellValue('Q'.$i,0)
            ->setCellValue('R'.$i,0)
            ->setCellValue('S'.$i,0)
            ->setCellValue('T'.$i,0)
            ->setCellValue('U'.$i,0);
        }elseif($cont_entradas == 2){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('Q'.$i,number_format($total_entradas_mvto,1))
            ->setCellValue('R'.$i,0)
            ->setCellValue('S'.$i,0)
            ->setCellValue('T'.$i,0)
            ->setCellValue('U'.$i,0);
        }elseif($cont_entradas == 3){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('R'.$i,number_format($total_entradas_varios,1))
            ->setCellValue('S'.$i,0)
            ->setCellValue('T'.$i,0)
            ->setCellValue('U'.$i,0);
        }
    }elseif($band == 5){
        if($cont_molienda == 0){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('S'.$i,0)
            ->setCellValue('T'.$i,0)
            ->setCellValue('U'.$i,0);
        }elseif($cont_molienda == 1){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('S'.$i,number_format($total_molienda_alimentar,1))
            ->setCellValue('T'.$i,0)
            ->setCellValue('U'.$i,0);
        }elseif($cont_molienda == 2){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('T'.$i,number_format($total_molienda_apilar,1))
            ->setCellValue('U'.$i,0);
        }
    }elseif($band == 6){
        if($cont_standby == 0){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('U'.$i,0);
        }elseif($cont_standby == 1){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('U'.$i,number_format($total_standby,1));
        }
    }
    $tm_despacho = 0;
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
      ->setCellValue('V'.$i,$tm_despacho);
    $corre11 = sqlsrv_query($conn,$query1);
    while($save11 = sqlsrv_fetch_array($corre11)){
      $var_a = $Array_position_nom[utf8_encode($save11['Descripcion'])];
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue($var_a.$i,$save11['TM_total']);
    }
    $i++;
}//CIERRE WHILE TIQUETES
$count_registros;
$Tarifa_Toneladas = $Tarifa_Toneladas/$count_registros;
$Tarifa_Horometro = $Tarifa_Horometro/$count_registros;
$Iva = $Iva/$count_registros;
if($Iva == 0){
$objPHPExcel->setActiveSheetIndex(0)
  ->setCellValueExplicit('I5','=H5*I7')
  ->setCellValueExplicit('K5','=H5*K7')
  ->setCellValueExplicit('L5','=H5*L7')
  ->setCellValueExplicit('M5','=H5*M7')
  ->setCellValueExplicit('N5','=H5*N7')
  ->setCellValueExplicit('O5','=H5*O7')
  ->setCellValueExplicit('P5','=H5*P7')
  ->setCellValueExplicit('Q5','=H5*Q7')
  ->setCellValueExplicit('R5','=H5*R7')
  ->setCellValueExplicit('S5','=H5*S7')
  ->setCellValueExplicit('T5','=H5*T7')
  ->setCellValueExplicit('U5','=H5*U7');
}else{
  $objPHPExcel->setActiveSheetIndex(0)
  ->setCellValueExplicit('I5','=(I7*H5)+(I7*H5*H4/100)')
  ->setCellValueExplicit('K5','=(K7*H5)+(K7*H5*H4/100)')
  ->setCellValueExplicit('L5','=(L7*H5)+(L7*H5*H4/100)')
  ->setCellValueExplicit('M5','=(M7*H5)+(M7*H5*H4/100)')
  ->setCellValueExplicit('N5','=(N7*H5)+(N7*H5*H4/100)')
  ->setCellValueExplicit('O5','=(O7*H5)+(O7*H5*H4/100)')
  ->setCellValueExplicit('P5','=(P7*H5)+(P7*H5*H4/100)')
  ->setCellValueExplicit('Q5','=(Q7*H5)+(Q7*H5*H4/100)')
  ->setCellValueExplicit('R5','=(R7*H5)+(R7*H5*H4/100)')
  ->setCellValueExplicit('S5','=(S7*H5)+(S7*H5*H4/100)')
  ->setCellValueExplicit('T5','=(T7*H5)+(T7*H5*H4/100)')
  ->setCellValueExplicit('U5','=(U7*H5)+(U7*H5*H4/100)');
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
  ->setCellValue('H6',$Tarifa_Toneladas)
  ->setCellValueExplicit('I7','=SUBTOTALES(9;I8:I'.$i.')')
  ->setCellValueExplicit('J7','=SUBTOTALES(9;J8:J'.$i.')')
  ->setCellValueExplicit('K7','=SUBTOTALES(9;K8:K'.$i.')')
  ->setCellValueExplicit('L7','=SUBTOTALES(9;L8:L'.$i.')')
  ->setCellValueExplicit('M7','=SUBTOTALES(9;M8:M'.$i.')')
  ->setCellValueExplicit('N7','=SUBTOTALES(9;N8:N'.$i.')')
  ->setCellValueExplicit('O7','=SUBTOTALES(9;O8:O'.$i.')')
  ->setCellValueExplicit('P7','=SUBTOTALES(9;P8:P'.$i.')')
  ->setCellValueExplicit('Q7','=SUBTOTALES(9;Q8:Q'.$i.')')
  ->setCellValueExplicit('R7','=SUBTOTALES(9;R8:R'.$i.')')
  ->setCellValueExplicit('S7','=SUBTOTALES(9;S8:S'.$i.')')
  ->setCellValueExplicit('T7','=SUBTOTALES(9;T8:T'.$i.')')
  ->setCellValueExplicit('U7','=SUBTOTALES(9;U8:U'.$i.')')
  ->setCellValueExplicit('V7','=SUBTOTALES(9;V8:V'.$i.')');
  
 for ($aa=$letra_ini; $aa <= $letra_fin; $aa++) { 
   $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValueExplicit($aa.'5','=(O5/V7)*'.$aa.'7')
    ->setCellValueExplicit($aa.'6','=H6*'.$aa.'7')
    ->setCellValueExplicit($aa.'7','=SUBTOTALES(9;'.$aa.'8:'.$aa.$i.')');
 }
  

$objPHPExcel->setActiveSheetIndex(0)
  ->setCellValueExplicit('I7','=SUBTOTALES(9;I8:I'.$i.')')
  ->setCellValueExplicit('J7','=SUBTOTALES(9;J8:J'.$i.')')
  ->setCellValueExplicit('K7','=SUBTOTALES(9;K8:K'.$i.')')
  ->setCellValueExplicit('L7','=SUBTOTALES(9;L8:L'.$i.')')
  ->setCellValueExplicit('M7','=SUBTOTALES(9;M8:M'.$i.')')
  ->setCellValueExplicit('N7','=SUBTOTALES(9;N8:N'.$i.')')
  ->setCellValueExplicit('O7','=SUBTOTALES(9;O8:O'.$i.')')
  ->setCellValueExplicit('P7','=SUBTOTALES(9;P8:P'.$i.')')
  ->setCellValueExplicit('Q7','=SUBTOTALES(9;Q8:Q'.$i.')')
  ->setCellValueExplicit('R7','=SUBTOTALES(9;R8:R'.$i.')')
  ->setCellValueExplicit('S7','=SUBTOTALES(9;S8:S'.$i.')')
  ->setCellValueExplicit('T7','=SUBTOTALES(9;T8:T'.$i.')')
  ->setCellValueExplicit('U7','=SUBTOTALES(9;U8:U'.$i.')');
$objPHPExcel->getActiveSheet()->getStyle('H4:U6')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
  /*->setCellValue('I7',number_format($total_total_horas,1))
  ->setCellValue('J7',number_format($total_total_descuentos,1))
  ->setCellValue('K7',number_format($total_total_room_alimentar,1))
  ->setCellValue('L7',number_format($total_total_room_apilar,1))
  ->setCellValue('M7',number_format($total_total_sobretamaño_alimentar,1))
  ->setCellValue('N7',number_format($total_total_sobretamaño_apilar,1))
  ->setCellValue('O7',number_format($total_total_cargar_despacho,1))
  ->setCellValue('P7',number_format($total_total_entradas_apilar,1))
  ->setCellValue('Q7',number_format($total_total_entradas_mvto,1))
  ->setCellValue('R7',number_format($total_total_entradas_varios,1))
  ->setCellValue('S7',number_format($total_total_molienda_alimentar,1))
  ->setCellValue('T7',number_format($total_total_molienda_apilar,1))
  ->setCellValue('U7',number_format($total_total_standby),1);*/

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