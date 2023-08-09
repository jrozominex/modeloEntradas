<?php
require_once '../librerias/lib_excel/PHPExcel.php';
session_start();
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
$empresa=$_GET['empresa'];
$fecha_inicio=$_GET['fecha_inicio'];
$fecha_fin=$_GET['fecha_fin'];
 $acopio=$_GET['acopio'];

if($empresa == 1){
  if($acopio == 1){
    $sql = "SELECT Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.id_registro, 
    Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, 
    Registro_tique_cargadores.id_usuario, Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, 
    Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado,
    Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, 
    Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, P.NombreCorto as proveedor, Usuarios.NombreUsuarioLargo, 
    Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.horometro_ini, Registro_tique_cargadores.horometro_fin
            FROM Registro_tique_cargadores INNER JOIN
                 Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                 Proveedores AS P ON Equipos.idPropietario = P.idProveedor INNER JOIN
                 Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
            WHERE Registro_tique_cargadores.Estado = '3' AND CAST (Registro_tique_cargadores.fecha_cierre_tique AS DATE) between '$fecha_inicio' AND '$fecha_fin' 
                and Registro_tique_cargadores.horas_trabajadas>0
            ORDER BY Registro_tique_cargadores.cod_reporte DESC";
  }else{
    $sql = "SELECT Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.id_registro, 
    Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, 
    Registro_tique_cargadores.id_usuario, Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, 
    Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado, 
    Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, 
    Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, Usuarios.NombreUsuarioLargo, P.NombreCorto as proveedor, 
    Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.horometro_ini, Registro_tique_cargadores.horometro_fin
            FROM Registro_tique_cargadores INNER JOIN
                 Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                 Proveedores AS P ON Equipos.idPropietario = P.idProveedor INNER JOIN
                 Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
            WHERE Registro_tique_cargadores.Estado = '3' AND CAST (Registro_tique_cargadores.fecha_cierre_tique AS DATE) between '$fecha_inicio' AND '$fecha_fin'
                  AND Registro_tique_cargadores.id_patio ='$acopio' and Registro_tique_cargadores.horas_trabajadas>0
            ORDER BY Registro_tique_cargadores.cod_reporte DESC";
  }
}else{
  if($acopio == 1){
    $sql = "SELECT Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.id_registro, 
    Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, 
    Registro_tique_cargadores.id_usuario, Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, 
    Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado, 
    Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, 
    Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, Usuarios.NombreUsuarioLargo, P.NombreCorto as proveedor, 
    Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.horometro_ini, Registro_tique_cargadores.horometro_fin
            FROM Registro_tique_cargadores INNER JOIN
                 Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                 Proveedores AS P ON Equipos.idPropietario = P.idProveedor INNER JOIN
                 Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
            WHERE Registro_tique_cargadores.Estado = '3' AND CAST (Registro_tique_cargadores.fecha_cierre_tique AS DATE) between '$fecha_inicio' AND '$fecha_fin' 
                  AND Registro_tique_cargadores.id_proveedor='$empresa' and Registro_tique_cargadores.horas_trabajadas>0
            ORDER BY Registro_tique_cargadores.cod_reporte DESC";
  }else{
    $sql = "SELECT Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.id_registro, 
    Registro_tique_cargadores.cod_reporte, Registro_tique_cargadores.id_patio, Registro_tique_cargadores.id_maquinaria, 
    Registro_tique_cargadores.id_usuario, Registro_tique_cargadores.fecha_ini_jornada, Registro_tique_cargadores.fecha_fin_jornada, 
    Registro_tique_cargadores.horas_trabajadas, Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado,
    Registro_tique_cargadores.fecha_cierre_tique, Registro_tique_cargadores.id_proveedor, Proveedores.NombreCorto, Destino.Descripcion, 
    Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, Usuarios.NombreUsuarioLargo, P.NombreCorto as proveedor, 
    Registro_tique_cargadores.servicio_clasificacion, Registro_tique_cargadores.horometro_ini, Registro_tique_cargadores.horometro_fin
            FROM Registro_tique_cargadores INNER JOIN
                 Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor INNER JOIN
                 Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino INNER JOIN
                 Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo INNER JOIN
                 Proveedores AS P ON Equipos.idPropietario = P.idProveedor INNER JOIN
                 Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario
            WHERE Registro_tique_cargadores.Estado = '3' AND CAST (Registro_tique_cargadores.fecha_cierre_tique AS DATE) between '$fecha_inicio' AND '$fecha_fin'
                  AND Registro_tique_cargadores.id_proveedor='$empresa' AND Registro_tique_cargadores.id_patio ='$acopio' and Registro_tique_cargadores.horas_trabajadas>0
            ORDER BY Registro_tique_cargadores.cod_reporte DESC";
  }
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

$estiloTituloColumnas = array(
  'font' => array('name'=>'Calibri','bold'=>true,'color'=>array('rgb'=>'FFFFFF'), 'size'=>'10'),
  'fill' => array('type'=>PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,'rotation'=>90,'startcolor'=>array('rgb'=>'7FB3D5'),
  'endcolor' => array('argb' => 'FF431a5d')),
  'borders' => array('top'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM,'color'=>array('rgb'=>'FFFFFF')),
  'bottom'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM, 'color'=>array('rgb'=>'143860'))),
  'alignment'=>array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap'=>TRUE)
);

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
$tituloReporte = "RELACIÓN DE SALIDA DIARIA CARBON";
$titulosColumnas = array('EMPRESA','ACOPIO','OPERACION','PROVEEDOR','FECHA TIQUETE','OPERARIO',
  'EQUIPO','N° TIQUETE','CLASIFICAR ROOM','CLASIFICAR SOBRETAMAÑO','DESPACHO','ENTRADAS','MOLIENDA','STANDBY'
  ,'ALIMENTAR','APILAR','CARGAR DESPACHO','APILAR','MVTO. x CALIDAD','OFICIOS VARIOS','STANDBY','DESCUENTOS');
// Se combinan las celdas A1 hasta E1, para colocar ahí el titulo del reporte 
// Se agregan los titulos del reporte
$objPHPExcel->setActiveSheetIndex(0)
  ->mergeCells('A1:Y1')     
  ->mergeCells('A4:H4')
  ->mergeCells('A2:B2')
  ->mergeCells('A3:B3')
  ->setCellValue('A1',$tituloReporte) // Titulo del reporte
  ->setCellValue('A2','FECHA INICIO: ') // Titulo del reporte
  ->setCellValue('C2',$fecha_inicio)
  ->setCellValue('A3','FECHA FIN: ') // Titulo del reporte
  ->setCellValue('C3',$fecha_fin)
  ->setCellValue('A4','TOTALES: ') // Titulo del reporte
  ->setCellValue('A5',$titulosColumnas[7])
  ->setCellValue('B5',$titulosColumnas[0])  //Titulo de las columnas
  ->setCellValue('C5',$titulosColumnas[1])
  ->setCellValue('D5',$titulosColumnas[2])
  ->setCellValue('E5',$titulosColumnas[3])
  ->setCellValue('F5',$titulosColumnas[4])
  ->setCellValue('G5',$titulosColumnas[5])  //Titulo de las columnas
  ->setCellValue('H5',$titulosColumnas[6])
  ->setCellValue('I5','HOROMETRO INICIAL')  //Titulo de las columnas
  ->setCellValue('J5','HOROMETRO FINAL')
  ->setCellValue('K5','TOTAL HORAS')
  ->setCellValue('L5','DESCUENTOS')
  ->setCellValue('M5','CLASIFICAR ROOM-'.$titulosColumnas[14])
  ->setCellValue('N5',' CLASIFICAR ROOM-'.$titulosColumnas[15])
  ->setCellValue('O5','CLASIFICAR SOBRETAMAÑO-'.$titulosColumnas[14])
  ->setCellValue('P5','CLASIFICAR SOBRETAMAÑO-'.$titulosColumnas[15])
  ->setCellValue('Q5',$titulosColumnas[16])
  ->setCellValue('R5','ENTRADAS-'.$titulosColumnas[17])
  ->setCellValue('S5','ENTRADAS-'.$titulosColumnas[18])
  ->setCellValue('T5','ENTRADAS-'.$titulosColumnas[19])
  ->setCellValue('U5','MOLIENDA-'.$titulosColumnas[14])
  ->setCellValue('V5','MOLIENDA-'.$titulosColumnas[15])
  ->setCellValue('W5',$titulosColumnas[20])
  ->setCellValue('X5','JORNADA INICIAL') 
  ->setCellValue('Y5','JORNADA FINAL') 
  ->setSharedStyle($estilo_informacion1, 'A5:Y5');  //Titulo de las columnas
$objPHPExcel->getActiveSheet()->getStyle('A1:Y1')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('A4:Y4')->applyFromArray($estiloTituloReporte_1);
//$objPHPExcel->setActiveSheetIndex(0)->setSharedStyle($estilo_informacion, 'I2:T2');
$objPHPExcel->getActiveSheet()->getStyle('A5:Y5')->applyFromArray($estiloTituloColumnas);
    //  $objPHPExcel->getActiveSheet()->getStyle('I3:S3')->applyFromArray($estilo_sub_origen);
$res = sqlsrv_query($conn,$sql);
$i=6;
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
while($r = sqlsrv_fetch_array($res))
{ $paso = 0;
  $band = 0;
  $cont_room = 0;
  $cont_sobreta = 0;
  $cont_despacho = 0;
  $cont_entradas = 0;
  $cont_molienda = 0;
  $cont_standby = 0;
  $total_horas = 0;    
  $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$i,$r['cod_reporte'])
    ->setCellValue('B'.$i,$r['NombreCorto'])
    ->setCellValue('C'.$i,$r['Descripcion']);
  if($r['servicio_clasificacion'] == 1){
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('D'.$i,'SERVICIO CLASIFICACIÓN');
  }else{
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('D'.$i,'CARBÓN');
  }
  $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('E'.$i,$r['proveedor'])
    ->setCellValue('F'.$i,date_format($r['fecha_apertura_tique'],'Y-m-d'))
    ->setCellValue('G'.$i,$r['NombreUsuarioLargo'])
    ->setCellValue('H'.$i,$r['NombreCargador'].' - '.$r['Identificacion'])
    ->setCellValue('I'.$i,$r['horometro_ini'])
    ->setCellValue('J'.$i,$r['horometro_fin']);
  $sql_desc = "SELECT * FROM horometro_descuento_cargadores WHERE idRegistro='". $r['id_registro'] ."'";
  $params = array();
  $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
  $res_desc=sqlsrv_query($conn,utf8_decode($sql_desc),$params,$options);
  $rows=sqlsrv_num_rows($res_desc);
  if ($rows > 0) 
  { while($desc = sqlsrv_fetch_array($res_desc)){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('L'.$i,number_format($desc['valor_descuento'],1,'.',','));
      $total_descuentos+=$desc['valor_descuento'];
    }
  }else{
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('L'.$i,0);
  }
  $sql_1 = "SELECT horometro.fecha_cierre_horometro ,horometro.id_horometro, horometro.id_registro, horometro.horometro_inicial, 
          horometro.horometro_final, horometro.total_horas, horometro.idActividad, horometro.fecha_registro_horometro, 
          Actividades.Descripcion, SubActividades_cargadores.Descripcion as Descripcion_sub, horometro.observaciones
                      FROM horometro LEFT JOIN
                      Actividades ON horometro.idActividad = Actividades.idActividad
                      LEFT JOIN SubActividades_cargadores ON horometro.idSubActividad=SubActividades_cargadores.idSubActividad
                     WHERE horometro.id_registro='". $r['id_registro'] ."' ORDER BY Actividades.Descripcion,SubActividades_cargadores.Descripcion";
  $res_1 = sqlsrv_query($conn,$sql_1);
  while($horometro = sqlsrv_fetch_array($res_1)){
    if($band == 0){
      if($horometro['Descripcion'] == 'CLASIFICAR ROOM'){
        $cont_room++;
        if($horometro['Descripcion_sub'] == 'ALIMENTAR'){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('M'.$i,number_format($horometro['total_horas'],1,'.',','));
          $total_room_alimentar+=$horometro['total_horas'];
          $total_horas+=$horometro['total_horas'];
          $paso = 1;
        }elseif($horometro['Descripcion_sub'] == 'APILAR'){                    
          if($paso == 0){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('M'.$i,'0')
              ->setCellValue('N'.$i,number_format($horometro['total_horas'],1,'.',','));
            $total_room_apilar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
            $cont_room++;
            $band++;
          }else{
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('N'.$i,number_format($horometro['total_horas'],1,'.',','));
            $band++;
            $total_room_apilar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }          
        }
      }//FIN CLASIFICAR ROOM
      else{
        $paso = 0;
        if($cont_room == 0){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('M'.$i,'0')
            ->setCellValue('N'.$i,'0');           
        }elseif($cont_room == 1) {
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('N'.$i,'0');  
        }
        if(utf8_encode($horometro['Descripcion']) == 'CLASIFICAR SOBRETAMAÑO'){
          $cont_sobreta++;
          $band++;
          if($horometro['Descripcion_sub'] == 'ALIMENTAR'){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('O'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_sobretamaño_alimentar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
            $paso = 1;
          }elseif($horometro['Descripcion_sub'] == 'APILAR'){
            if($paso == 0){
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('O'.$i,'0')
                ->setCellValue('P'.$i,number_format($horometro['total_horas'],1,'.','.'));
              $total_sobretamaño_apilar+=$horometro['total_horas'];
              $total_horas+=$horometro['total_horas'];
              $cont_sobreta++;
            }else{
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('Q'.$i,number_format($horometro['total_horas'],1,'.','.'));
              $total_sobretamaño_apilar+=$horometro['total_horas'];
              $total_horas+=$horometro['total_horas'];
            }
            $paso = 2;                    
          }
        }elseif(utf8_encode($horometro['Descripcion']) == 'DESPACHO'){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('O'.$i,'0')
            ->setCellValue('P'.$i,'0');  
          $band+=2;
          $cont_despacho++;
          if($horometro['Descripcion_sub'] == 'CARGAR DESPACHO'){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('Q'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_cargar_despacho+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }
        }elseif(utf8_encode($horometro['Descripcion']) == 'ENTRADAS'){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('O'.$i,'0')
            ->setCellValue('P'.$i,'0')
            ->setCellValue('Q'.$i,'0'); 
          $band+=3;
          $cont_entradas++;
          if($horometro['Descripcion_sub'] == 'APILAR'){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('R'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_entradas_apilar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
            $paso = 1;
          }elseif($horometro['Descripcion_sub'] == 'MVTO. X CALIDAD'){
            if($paso == 1){
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('S'.$i,number_format($horometro['total_horas'],1,'.','.'));
            }else{
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('R'.$i,'0')
                ->setCellValue('S'.$i,number_format($horometro['total_horas'],1,'.','.'));
              $cont_entradas++;
            }
            $paso = 2;
            $total_entradas_mvto+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }elseif($horometro['Descripcion_sub'] == 'OFICIOS VARIOS'){
            if($paso == 0){
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('R'.$i,'0')
                ->setCellValue('S'.$i,'0')
                ->setCellValue('T'.$i,number_format($horometro['total_horas'],1,'.','.'));
              $cont_entradas+=2;
            }elseif($paso == 1){
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('S'.$i,'0')
                ->setCellValue('T'.$i,number_format($horometro['total_horas'],1,'.','.'));
              $cont_entradas++;
            }else{
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('T'.$i,number_format($horometro['total_horas'],1,'.','.'));
            }
            $paso = 3;
            $total_entradas_varios+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }
        }elseif(utf8_encode($horometro['Descripcion']) == 'MOLIENDA'){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('O'.$i,'0')
            ->setCellValue('P'.$i,'0')
            ->setCellValue('Q'.$i,'0')
            ->setCellValue('R'.$i,'0')
            ->setCellValue('S'.$i,'0')
            ->setCellValue('T'.$i,'0'); 
          $cont_molienda++;
          $band+=4;
          if($horometro['Descripcion_sub'] == 'ALIMENTAR'){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('U'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_molienda_alimentar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
            $paso = 1;
          }elseif($horometro['Descripcion_sub'] == 'APILAR'){
            if($paso == 0){
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('U'.$i,'0')
                ->setCellValue('V'.$i,number_format($horometro['total_horas'],1,'.','.'));
            }else{
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('V'.$i,number_format($horometro['total_horas'],1,'.','.'));
            }
            $paso = 2;
            $total_molienda_apilar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }
        }elseif(utf8_encode($horometro['Descripcion']) == 'STANDBAY'){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('O'.$i,'0')
            ->setCellValue('P'.$i,'0')
            ->setCellValue('Q'.$i,'0')
            ->setCellValue('R'.$i,'0')
            ->setCellValue('S'.$i,'0')
            ->setCellValue('T'.$i,'0')
            ->setCellValue('U'.$i,'0')
            ->setCellValue('V'.$i,'0'); 
          $band+=5;
          $cont_standby++;
          if($horometro['Descripcion_sub'] == 'STANDBAY'){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('W'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_standby+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }
        }                  
      }
    }elseif($band == 1){
      if(utf8_encode($horometro['Descripcion']) == 'CLASIFICAR SOBRETAMAÑO'){
        $cont_sobreta++;
        if($horometro['Descripcion_sub'] == 'ALIMENTAR'){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('O'.$i,number_format($horometro['total_horas'],1,'.','.'));
          $total_sobretamaño_alimentar+=$horometro['total_horas'];
          $total_horas+=$horometro['total_horas'];
          $paso=1;
        }elseif($horometro['Descripcion_sub'] == 'APILAR'){
          if($paso == 0){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('O'.$i,'0')
              ->setCellValue('P'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_sobretamaño_apilar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
            $cont_sobreta++;
            $band++;
          }else{
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('P'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_sobretamaño_apilar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
            $band++;
          }
        }
      }else{
        $paso = 0;
        if($cont_sobreta == 0){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('O'.$i,'0')
            ->setCellValue('P'.$i,'0');
        }elseif($cont_sobreta == 1) {
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('P'.$i,'0');
        }
        if(utf8_encode($horometro['Descripcion']) == 'DESPACHO'){
          $band++;
          $cont_despacho++;
          if($horometro['Descripcion_sub'] == 'CARGAR DESPACHO'){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('Q'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_cargar_despacho+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }
        }elseif(utf8_encode($horometro['Descripcion']) == 'ENTRADAS'){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('Q'.$i,'0');
          $band+=2;
          $cont_entradas++;
          if($horometro['Descripcion_sub'] == 'APILAR'){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('R'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $paso = 1;
            $total_entradas_apilar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }elseif($horometro['Descripcion_sub'] == 'MVTO. X CALIDAD'){
            if($paso == 1){
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('S'.$i,number_format($horometro['total_horas'],1,'.','.'));
            }else{
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('R'.$i,'0')
                ->setCellValue('S'.$i,number_format($horometro['total_horas'],1,'.','.'));
              $cont_entradas++;
            }
            $paso = 2;
            $total_entradas_mvto+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }elseif($horometro['Descripcion_sub'] == 'OFICIOS VARIOS'){
            if($paso == 0){
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('R'.$i,'0')
                ->setCellValue('S'.$i,'0')
                ->setCellValue('T'.$i,number_format($horometro['total_horas'],1,'.','.'));
              $cont_entradas+=2;
            }elseif($paso == 1){
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('S'.$i,'0')
                ->setCellValue('T'.$i,number_format($horometro['total_horas'],1,'.','.'));
              $cont_entradas++;
            }else{
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('T'.$i,number_format($horometro['total_horas'],1,'.','.'));
            }
            $paso = 3;
            $total_entradas_varios+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }
        }elseif(utf8_encode($horometro['Descripcion']) == 'MOLIENDA'){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('Q'.$i,'0')
            ->setCellValue('R'.$i,'0')
            ->setCellValue('S'.$i,'0')
            ->setCellValue('T'.$i,'0');
          $cont_molienda++;
          $band+=3;
          if($horometro['Descripcion_sub'] == 'ALIMENTAR'){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('U'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $paso = 1;
            $total_molienda_alimentar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }elseif($horometro['Descripcion_sub'] == 'APILAR'){
            if($paso == 0){
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('U'.$i,'0')
                ->setCellValue('V'.$i,number_format($horometro['total_horas'],1,'.','.'));
              $cont_molienda++;
            }else{
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('V'.$i,number_format($horometro['total_horas'],1,'.','.'));
            }
            $paso = 2;
            $total_molienda_apilar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
            $cont_sobreta++;
          }
        }elseif(utf8_encode($horometro['Descripcion']) == 'STANDBAY'){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('Q'.$i,'0')
            ->setCellValue('R'.$i,'0')
            ->setCellValue('S'.$i,'0')
            ->setCellValue('T'.$i,'0')
            ->setCellValue('U'.$i,'0')
            ->setCellValue('V'.$i,'0');                    
          $band+=4;
          $cont_standby++;
          if($horometro['Descripcion_sub'] == 'STANDBAY'){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('W'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_standby+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }
        }
      }
    }elseif($band == 2){
      if(utf8_encode($horometro['Descripcion']) == 'DESPACHO'){
        $cont_despacho++;
        if($horometro['Descripcion_sub'] == 'CARGAR DESPACHO'){
          $band++;
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('Q'.$i,number_format($horometro['total_horas'],1,'.','.'));
          $total_cargar_despacho+=$horometro['total_horas'];
          $total_horas+=$horometro['total_horas'];
        }
      }else{
        $paso = 0;
        if($cont_despacho == 0){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('Q'.$i,'0');
        }
        if(utf8_encode($horometro['Descripcion']) == 'ENTRADAS'){
          $band++;
          $cont_entradas++;
          if($horometro['Descripcion_sub'] == 'APILAR'){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('R'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_entradas_apilar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
            $paso = 1;
          }elseif($horometro['Descripcion_sub'] == 'MVTO. X CALIDAD'){
            if($paso == 1){
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('S'.$i,number_format($horometro['total_horas'],1,'.','.'));
            }else{
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('R'.$i,'0')
                ->setCellValue('S'.$i,number_format($horometro['total_horas'],1,'.','.'));
              $cont_entradas++;
            }
            $paso = 2;
            $total_entradas_mvto+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }elseif($horometro['Descripcion_sub'] == 'OFICIOS VARIOS'){
            if($paso == 0){
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('R'.$i,'0')
                ->setCellValue('S'.$i,'0')
                ->setCellValue('T'.$i,number_format($horometro['total_horas'],1,'.','.'));
              $cont_entradas+=2;
            }elseif($paso == 1){
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('S'.$i,'0')
                ->setCellValue('T'.$i,number_format($horometro['total_horas'],1,'.','.'));
              $cont_entradas++;
            }else{
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('T'.$i,number_format($horometro['total_horas'],1,'.','.'));
            }
            $paso = 3;
            $total_entradas_varios+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }
        }elseif(utf8_encode($horometro['Descripcion']) == 'MOLIENDA'){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('R'.$i,'0')
            ->setCellValue('S'.$i,'0')
            ->setCellValue('T'.$i,'0');
          $cont_molienda++;
          $band+=2;
          if($horometro['Descripcion_sub'] == 'ALIMENTAR'){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('U'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $paso = 1;
            $total_molienda_alimentar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }elseif($horometro['Descripcion_sub'] == 'APILAR'){
            if($paso == 0){
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('U'.$i,'0')
                ->setCellValue('V'.$i,number_format($horometro['total_horas'],1,'.','.'));
              $cont_molienda++;
            }else{
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('V'.$i,number_format($horometro['total_horas'],1,'.','.'));
            }
            $paso = 2;
            $total_molienda_apilar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
            $cont_sobreta++;
          }
        }elseif(utf8_encode($horometro['Descripcion']) == 'STANDBAY'){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('R'.$i,'0')
            ->setCellValue('S'.$i,'0')
            ->setCellValue('T'.$i,'0')
            ->setCellValue('U'.$i,'0')
            ->setCellValue('V'.$i,'0');
          $band+=3;
          $cont_standby++;
          if($horometro['Descripcion_sub'] == 'STANDBAY'){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('W'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_standby+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }
        }
      }
    }elseif($band == 3){
      if(utf8_encode($horometro['Descripcion']) == 'ENTRADAS'){
        $cont_entradas++;
        if($horometro['Descripcion_sub'] == 'APILAR'){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('R'.$i,number_format($horometro['total_horas'],1,'.','.'));
          $total_entradas_apilar+=$horometro['total_horas'];
          $total_horas+=$horometro['total_horas'];
          $paso=1;
        }elseif($horometro['Descripcion_sub'] == 'MVTO. X CALIDAD'){
          if($paso == 1){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('S'.$i,number_format($horometro['total_horas'],1,'.','.'));
          }else{
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('S'.$i,'0')
              ->setCellValue('T'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $cont_molienda++;
          }
          $paso = 2;
          $total_entradas_mvto+=$horometro['total_horas'];
          $total_horas+=$horometro['total_horas'];
        }elseif($horometro['Descripcion_sub'] == 'OFICIOS VARIOS'){
          if($paso == 0){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('R'.$i,'0')
              ->setCellValue('S'.$i,'0')
              ->setCellValue('T'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $cont_entradas+=2;
          }elseif($paso == 1){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('S'.$i,'0')
              ->setCellValue('T'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $cont_entradas++;
          }else{
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('T'.$i,number_format($horometro['total_horas'],1,'.','.'));
          }
          $paso = 3;
          $total_entradas_varios+=$horometro['total_horas'];
          $total_horas+=$horometro['total_horas'];
        }
      }else{
        $paso = 0;
        if($cont_entradas == 0){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('R'.$i,'0')
            ->setCellValue('S'.$i,'0')
            ->setCellValue('T'.$i,'0');
        }elseif($cont_entradas == 1) {
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('S'.$i,'0')
            ->setCellValue('T'.$i,'0');
        }elseif($cont_entradas == 2){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('S'.$i,'0');
        }
        if(utf8_encode($horometro['Descripcion']) == 'MOLIENDA'){
          $cont_molienda++;
          $band++;
          if($horometro['Descripcion_sub'] == 'ALIMENTAR'){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('U'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_molienda_alimentar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
            $paso=1;
          }elseif($horometro['Descripcion_sub'] == 'APILAR'){
            if($paso == 0){
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('U'.$i,'0')
                ->setCellValue('V'.$i,number_format($horometro['total_horas'],1,'.','.'));
              $cont_molienda++;
            }else{
              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('V'.$i,number_format($horometro['total_horas'],1,'.','.'));
            }
            $paso = 2;
            //->setCellValue('V'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_molienda_apilar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
            $cont_sobreta++;
          }
        }elseif(utf8_encode($horometro['Descripcion']) == 'STANDBAY'){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('U'.$i,'0')
            ->setCellValue('V'.$i,'0');
          $band+=2;
          $cont_standby++;
          if($horometro['Descripcion_sub'] == 'STANDBAY'){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('W'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_standby+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }
        }
      }
    }elseif($band == 4){
      if(utf8_encode($horometro['Descripcion']) == 'MOLIENDA'){
        $cont_molienda++;
        if($horometro['Descripcion_sub'] == 'ALIMENTAR'){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('U'.$i,number_format($horometro['total_horas'],1,'.','.'));
          $total_molienda_alimentar+=$horometro['total_horas'];
          $total_horas+=$horometro['total_horas'];
        }elseif($horometro['Descripcion_sub'] == 'APILAR'){
          if($paso == 0){
            $cont_molienda++;
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('U'.$i,'0')
              ->setCellValue('V'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_molienda_apilar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
            $band++;
          }else{
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('V'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_molienda_apilar+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
            $band++;
          }                       
        }
      }else{
        if($cont_molienda == 0){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('U'.$i,'0')
            ->setCellValue('V'.$i,'0');
        }elseif($cont_molienda == 1){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('V'.$i,'0');
        }
        if(utf8_encode($horometro['Descripcion']) == 'STANDBAY'){
          if($horometro['Descripcion_sub'] == 'STANDBAY'){
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('W'.$i,number_format($horometro['total_horas'],1,'.','.'));
            $total_standby+=$horometro['total_horas'];
            $total_horas+=$horometro['total_horas'];
          }
        }
      }
    }elseif($band == 5){
      if(utf8_encode($horometro['Descripcion']) == 'STANDBAY'){
        if($horometro['Descripcion_sub'] == 'STANDBAY'){
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('W'.$i,number_format($horometro['total_horas'],1,'.','.'));
          $total_standby+=$horometro['total_horas'];
          $total_horas+=$horometro['total_horas'];
        }
      }else{
        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('W'.$i,'0');
      }
      $band++;
    }
  }//CIERRE WHILE ACTIVIDADES
  if($band == 0){
    if($cont_room == 0){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('M'.$i,'0')
        ->setCellValue('N'.$i,'0')
        ->setCellValue('O'.$i,'0')
        ->setCellValue('P'.$i,'0')
        ->setCellValue('Q'.$i,'0')
        ->setCellValue('R'.$i,'0')
        ->setCellValue('S'.$i,'0')
        ->setCellValue('T'.$i,'0')
        ->setCellValue('U'.$i,'0')
        ->setCellValue('V'.$i,'0')
        ->setCellValue('W'.$i,'0');
    }elseif($cont_room == 1){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('N'.$i,'0')
        ->setCellValue('O'.$i,'0')
        ->setCellValue('P'.$i,'0')
        ->setCellValue('Q'.$i,'0')
        ->setCellValue('R'.$i,'0')
        ->setCellValue('S'.$i,'0')
        ->setCellValue('T'.$i,'0')
        ->setCellValue('U'.$i,'0')
        ->setCellValue('V'.$i,'0')
        ->setCellValue('W'.$i,'0');
    }elseif($cont_room == 2){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('O'.$i,'0')
        ->setCellValue('P'.$i,'0')
        ->setCellValue('Q'.$i,'0')
        ->setCellValue('R'.$i,'0')
        ->setCellValue('S'.$i,'0')
        ->setCellValue('T'.$i,'0')
        ->setCellValue('U'.$i,'0')
        ->setCellValue('V'.$i,'0')
        ->setCellValue('W'.$i,'0');
    }
  }elseif($band == 1){
    if($cont_sobreta){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('O'.$i,'0')
        ->setCellValue('P'.$i,'0')
        ->setCellValue('Q'.$i,'0')
        ->setCellValue('R'.$i,'0')
        ->setCellValue('S'.$i,'0')
        ->setCellValue('T'.$i,'0')
        ->setCellValue('U'.$i,'0')
        ->setCellValue('V'.$i,'0')
        ->setCellValue('W'.$i,'0');
    }elseif($cont_sobreta == 1){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('P'.$i,'0')
        ->setCellValue('Q'.$i,'0')
        ->setCellValue('R'.$i,'0')
        ->setCellValue('S'.$i,'0')
        ->setCellValue('T'.$i,'0')
        ->setCellValue('U'.$i,'0')
        ->setCellValue('V'.$i,'0')
        ->setCellValue('W'.$i,'0');
    }elseif($cont_sobreta == 2){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('Q'.$i,'0')
        ->setCellValue('R'.$i,'0')
        ->setCellValue('S'.$i,'0')
        ->setCellValue('T'.$i,'0')
        ->setCellValue('U'.$i,'0')
        ->setCellValue('V'.$i,'0')
        ->setCellValue('W'.$i,'0');
    }
  }elseif($band == 2){
    if($cont_despacho == 0){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('Q'.$i,'0')
        ->setCellValue('R'.$i,'0')
        ->setCellValue('S'.$i,'0')
        ->setCellValue('T'.$i,'0')
        ->setCellValue('U'.$i,'0')
        ->setCellValue('V'.$i,'0')
        ->setCellValue('W'.$i,'0');
    }elseif($cont_despacho == 1){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('R'.$i,'0')
        ->setCellValue('S'.$i,'0')
        ->setCellValue('T'.$i,'0')
        ->setCellValue('U'.$i,'0')
        ->setCellValue('V'.$i,'0')
        ->setCellValue('W'.$i,'0');
    }
  }elseif($band == 3){
    if($cont_entradas == 0){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('R'.$i,'0')
        ->setCellValue('S'.$i,'0')
        ->setCellValue('T'.$i,'0')
        ->setCellValue('U'.$i,'0')
        ->setCellValue('V'.$i,'0')
        ->setCellValue('W'.$i,'0');
    }elseif($cont_entradas == 1){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('S'.$i,'0')
        ->setCellValue('T'.$i,'0')
        ->setCellValue('U'.$i,'0')
        ->setCellValue('V'.$i,'0')
        ->setCellValue('W'.$i,'0');
    }elseif($cont_entradas == 2){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('T'.$i,'0')
        ->setCellValue('U'.$i,'0')
        ->setCellValue('V'.$i,'0')
        ->setCellValue('W'.$i,'0');
    }elseif($cont_entradas == 3){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('U'.$i,'0')
        ->setCellValue('V'.$i,'0')
        ->setCellValue('W'.$i,'0');
    }
  }elseif($band == 4){
    if($cont_molienda == 0){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('U'.$i,'0')
        ->setCellValue('V'.$i,'0')
        ->setCellValue('W'.$i,'0');
    }elseif($cont_molienda == 1){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('V'.$i,$cont_molienda)
        ->setCellValue('W'.$i,'0');
    }elseif($cont_molienda == 2){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('W'.$i,'0');
    }
  }elseif($band == 5){
    if($cont_standby == 0){
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('W'.$i,'0');
    }
  }
  $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($estiloTituloReporte_2);
  $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('K'.$i,$total_horas)
    ->setCellValue('X'.$i,date_format($r['fecha_ini_jornada'],'Y-m-d H:i:s'))
    ->setCellValue('Y'.$i,date_format($r['fecha_fin_jornada'],'Y-m-d H:i:s'));
  $i++;
}//CIERRE WHILE TIQUETES
$objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('L4',$total_descuentos)
  ->setCellValue('M4',$total_room_alimentar)
  ->setCellValue('N4',$total_room_apilar)
  ->setCellValue('O4',$total_sobretamaño_alimentar)
  ->setCellValue('P4',$total_sobretamaño_apilar)
  ->setCellValue('Q4',$total_cargar_despacho)
  ->setCellValue('R4',$total_entradas_apilar)
  ->setCellValue('S4',$total_entradas_mvto)
  ->setCellValue('T4',$total_entradas_varios)
  ->setCellValue('U4',$total_molienda_alimentar)
  ->setCellValue('V4',$total_molienda_apilar)
  ->setCellValue('W4',$total_standby);
/*  Hasta este punto ya se tiene el archivo con los datos ahora procedemos a aplicar el formato a las 
celdas. Para ello vamos a crear 3 variables, la primera va a contener el estilo del título del reporte, 
la segunda el estilo del título de las columnas y la tercera el estilo de la información de los alumnos.
Ahora procedemos a asignar el ancho de las columnas de forma automática en base al contenido de 
cada una de ellas y lo hacemos con un ciclo de la siguiente forma.  */

for($j = 'A'; $j <= 'Y'; $j++)
  {   $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($j)->setAutoSize(TRUE);  
   // $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':L'.$j)->applyFromArray($estiloInformacion);
  }
$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);
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