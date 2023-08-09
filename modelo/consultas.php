<?php
require_once '../modelo/conexion.php';
include ("../../minex-server/clase_encrip.php");
session_start();
$idUsuario = $_SESSION['idUsuario'];
if(!isset($_SESSION['Array_empresa']['PRODUCCION'])){
    ?>
  <script type="text/javascript">
      self.location='Admin.php';
      alert('Se ha suspendido la sesión');
  </script>
  <?php
}?>
<?php
// VARIABLES GLOBALES
$fecha_registro = date('Y-m-d H:i:s');
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
//////////////////////////////////////////////////////////////
if($_POST['band']==1){ //OPCION PARA CARGAR REPORTE HORAS (CARGADORES)
  $echo='';
}elseif($_POST['band']==2){//OPCION PARA CARGAR LIQUIDACIONES (CARGADORES)
  $txt_array_empresa = implode("','", $_SESSION['Array_empresa']['CONSULTAS']);
  $echo = '';
  $echo.='<div class="row">
          <div class="col-xs-6 col-sm-2">
              <label>Empresa</label>
              <select class="form-control" id="empresa">';
              $sql_empresa = "SELECT Empresa,idEmpresa FROM vInventarioSaldo 
                  WHERE idEmpresa IN ('$txt_array_empresa')
                  GROUP BY Empresa,idEmpresa ORDER BY Empresa";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idEmpresa'].'">'.utf8_encode($aa['Empresa']).'</option>';
                  }
              }
              $echo.='</select>
          </div>
          <div class="col-xs-6 col-sm-2">
              <label>Proveedor</label>
              <select class="form-control" id="proveedor">
                <option value="0">TODOS</option>';
              $sql_empresa = "SELECT Proveedor,idProveedor FROM vTiquetesCargadores 
                  GROUP BY Proveedor,idProveedor ORDER BY Proveedor";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idProveedor'].'">'.utf8_encode($aa['Proveedor']).'</option>';
                  }
              }
              $echo.='</select>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2">
              <label>Unidad de negocio</label>
              <select class="form-control" id="idUnidadNegocio">
                  <option value="0">TODOS</option>';
              $sql_empresa = "SELECT * FROM UnidadDeNegocio ORDER BY Descripcion";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idUnidadNegocio'].'">'.utf8_encode($aa['Descripcion']).'</option>';
                  }
              }
      $echo.='</select>
      </div>
          <div class="col-xs-6 col-sm-2">
              <label>Destino</label>
              <select class="form-control" id="centro_trabajo">
                  <option value="00000000-0000-0000-0000-000000000000">TODOS</option>';
              $sql_empresa = "SELECT Destino,idDestino FROM vTiquetesCargadores 
                  WHERE idCliente IN ('$txt_array_empresa')
                  GROUP BY Destino,idDestino ORDER BY Destino";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idDestino'].'">'.utf8_encode($aa['Destino']).'</option>';
                  }
              }
              $echo.='</select>
          </div>
          <div class="col-xs-6 col-sm-2">
              <label>Fecha Inicio</label>
              <input type="date" id="fecha_ini" class="form-control" max="'.date('Y-m-d').'">
          </div>
          <div class="col-xs-6 col-sm-2">
              <label>Fecha Fin</label>
              <input type="date" id="fecha_fin" class="form-control" max="'.date('Y-m-d').'">
          </div>
          <div class="col-xs-12 col-sm-12">
              <center>
                <button class="btn btn-primary" id="btn_search" onclick="load_data_search()" style="margin-top: 25px;"><span class="glyphicon glyphicon-refresh"></span></button>
                <button class="btn btn-success" id="btn_excel" onclick="generar_excel()" style="margin-top: 25px;"><span class="glyphicon glyphicon-export"></span></button>
              </center>
          </div>
          <form method="POST" id="form_excel">
            <input type="hidden" id="tabla_excel" name="tabla_excel">
          </form>
      </div>
      <div class="row">
          <br>
          <div class="col-sm-12" id="div_inventario_body"></div>
      </div>';
  echo $echo;
}elseif($_POST['band']==3){//OPCION PARA CARGAR PROMEDIO (CARGADORES)
  $echo='';
}elseif($_POST['band']==4){//OPCION PARA CARGAR REPORTE PREPARACION MEZCLA (PRODUCCION)
  $echo='';
  $txt_array_empresa = implode("','", $_SESSION['Array_empresa']['PREPARACION_MEZCLA']);
  $echo.='<div style="border: 1px solid; margin-left: 5px; margin-right: 5px; border-radius: 5px;">
        <div class="row">
          <center><h3>PREPARACION MEZCLAS</h3></center>
          <div class="col-sm-2">
            <label>Empresa</label>
            <select class="form-control" id="empresa_preparacion_serach">
              <option value="0">Todos</option>';
              $sql = "SELECT Proveedores.NombreCorto as Empresa, Proveedores.idProveedor 
                FROM Preparacion_recetas 
                INNER JOIN Proveedores ON Preparacion_recetas.idEmpresa=Proveedores.idProveedor
                WHERE Proveedores.idProveedor IN ('$txt_array_empresa')
                GROUP BY Proveedores.NombreCorto, Proveedores.idProveedor ORDER BY Proveedores.NombreCorto";
              $params = array();
                    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);     
                    $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
                    $filas=sqlsrv_num_rows($resultado);
                    while($aa = sqlsrv_fetch_array($resultado)){
                $echo.='<option value="'.$aa['idProveedor'].'">'.utf8_encode($aa['Empresa']).'</option>';
              }
        $echo.='</select><br>
          </div>
          <div class="col-sm-2">
            <label>Centro Trabajo</label>
            <select class="form-control" id="destino_preparacion_serach">
              <option value="0">Todos</option>';
              $sql = "SELECT Proveedores.NombreCorto as Empresa, Proveedores.idProveedor 
                FROM Preparacion_recetas 
                INNER JOIN Proveedores ON Preparacion_recetas.idEmpresa=Proveedores.idProveedor
                WHERE Proveedores.idProveedor IN ('$txt_array_empresa')
                GROUP BY Proveedores.NombreCorto, Proveedores.idProveedor ORDER BY Proveedores.NombreCorto";
              $params = array();
                    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);     
                    $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
                    $filas=sqlsrv_num_rows($resultado);
                    while($aa = sqlsrv_fetch_array($resultado)){
                $echo.='<option value="'.$aa['idProveedor'].'">'.utf8_encode($aa['Empresa']).'</option>';
              }
        $echo.='</select><br>
          </div>
          <div class="col-sm-2">
            <label>Mezcla Producida</label>
            <select class="form-control" id="mezcla_producida_search">
              <option value="0">Todos</option>';
              $sql = "SELECT Clasificacion.idClasificacion,Clasificacion.Descripcion
                  FROM Preparacion_recetas 
                  INNER JOIN Recetas_produccion ON Preparacion_recetas.idReceta=Recetas_produccion.idReceta
                  INNER JOIN Clasificacion ON Recetas_produccion.idClasificacion=Clasificacion.idClasificacion
                GROUP BY Clasificacion.idClasificacion,Clasificacion.Descripcion ORDER BY Clasificacion.Descripcion";
              $params = array();
                    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);     
                    $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
                    $filas=sqlsrv_num_rows($resultado);
                    while($aa = sqlsrv_fetch_array($resultado)){
                $echo.='<option value="'.$aa['idClasificacion'].'">'.utf8_encode($aa['Descripcion']).'</option>';
              }
      $echo.='  </select>
          </div>
          <div class="col-sm-2">
            <label>Fecha Inicio</label>';
            $sql_date = "SELECT ISNULL(MIN(CAST(FechaPreparacion AS date)),'1900-01-01') AS FechaPreparacion 
            FROM vPreparacion_recetas";
            $res_date=sqlsrv_query($conn,$sql_date,$params,$options);
            while($aa = sqlsrv_fetch_array($res_date)){
              $fecha_min = date_format($aa['FechaPreparacion'],'Y-m-d');
            }
        $echo.='<input type="date" id="fecha_inicio_search" class="form-control" min="'.$fecha_min.'" max="'.date('Y-m-d').'">
          </div>
          <div class="col-sm-2">
            <label>Fecha Fin</label>
            <input type="date" id="fecha_fin_search" class="form-control" min="'.$fecha_min.'" max="'.date('Y-m-d').'">
          </div>
          <div class="col-sm-2">
            <button class="btn btn-primary" id="btn_search_prep" style="margin-top: 25px" onclick="search_registers_preparacion()">Buscar <span class="glyphicon glyphicon-search"></span></button>
          </div>
          <br>
        </div>
        <div class="row" id="div_resultados_preparacion">
          <center><b>No hay registros encontrados</b></center>
        </div><br>
      </div>';
    echo $echo;
}elseif($_POST['band']==5){//OPCION PARA CARGAR REPORTE COQUIZACION (PRODUCCION)
  $echo='';
  $echo.='
    <div style="border: 1px solid; margin-left: 5px; margin-right: 5px; border-radius: 5px;">
    <center><h3>COQUIZACIÒN</h3></center>
    <div class="row">
      <div class="col-sm-2">
        <label>Empresa</label>
        <select class="form-control" id="empresa">';
          $txt_array_empresa = implode("','",$_SESSION['Array_empresa']['CONSULTAS']);
          $sql_emp = "SELECT Empresa,idEmpresa FROM vCoquizacion WHERE idEmpresa IN ('$txt_array_empresa') GROUP BY Empresa,idEmpresa ORDER BY Empresa";
          $resul_emp=sqlsrv_query($conn,$sql_emp,$params,$options);
          $rows_emp = sqlsrv_num_rows($resul_emp);
          if($rows_emp>0){
            $echo.='<option value="0" selected>TODOS</option>';
            while($aa = sqlsrv_fetch_array($resul_emp)){
              $echo.='<option value="'.$aa['idEmpresa'].'">'.utf8_encode($aa['Empresa']).'</option>';
            }
          }else{
            $echo.='<option value="0" disabled selected>No hay registros</option>';
          }
$echo.='</select>
      </div>
      <div class="col-sm-2">
        <label>Centro Trabajo</label>
        <select class="form-control" id="centro_trabajo">';
          $sql_emp = "SELECT NombrePlanta,idPlanta FROM vCoquizacion GROUP BY NombrePlanta,idPlanta ORDER BY NombrePlanta";
          $resul_emp=sqlsrv_query($conn,$sql_emp,$params,$options);
          $rows_emp = sqlsrv_num_rows($resul_emp);
          if($rows_emp>0){
            $echo.='<option value="0" selected>TODOS</option>';
            while($aa = sqlsrv_fetch_array($resul_emp)){
              $echo.='<option value="'.$aa['idPlanta'].'">'.utf8_encode($aa['NombrePlanta']).'</option>';
            }
          }else{
            $echo.='<option value="0" disabled selected>No hay registros</option>';
          }
$echo.='</select>
      </div>
      <div class="col-sm-2">
        <label>Clasificación Alimentada</label>
        <select class="form-control" id="clasificacion_alimentada">';
          $sql_clasif1 = "SELECT ClasificacionAlimentacion,idClasificacionAlimentacion FROM vCoquizacion GROUP BY ClasificacionAlimentacion,idClasificacionAlimentacion ORDER BY ClasificacionAlimentacion";
          $resul_clasif1=sqlsrv_query($conn,$sql_clasif1,$params,$options);
          $rows_clasif1 = sqlsrv_num_rows($resul_clasif1);
          if($rows_clasif1>0){
            $echo.='<option value="0" selected>TODOS</option>';
            while($aa = sqlsrv_fetch_array($resul_clasif1)){
              $echo.='<option value="'.$aa['idClasificacionAlimentacion'].'">'.utf8_encode($aa['ClasificacionAlimentacion']).'</option>';
            }
          }else{
            $echo.='<option value="0" disabled selected>No hay registros</option>';
          }
$echo.='</select>
      </div>
      <div class="col-sm-2">
                <label>Clasificación Producida</label>
                <select class="form-control" id="clasificacion_producida">';
                    $sql_clasif2 = "SELECT ClasificacionProduccion,idClasificacionProduccion FROM vCoquizacion GROUP BY ClasificacionProduccion,idClasificacionProduccion ORDER BY ClasificacionProduccion";
                    $resul_clasif2=sqlsrv_query($conn,$sql_clasif2,$params,$options);
                    $rows_clasif2 = sqlsrv_num_rows($resul_clasif2);
                    if($rows_clasif2>0){
                        $echo.='<option value="0" selected>TODOS</option>';
                        while($aa = sqlsrv_fetch_array($resul_clasif2)){
                            $echo.='<option value="'.$aa['idClasificacionProduccion'].'">'.utf8_encode($aa['ClasificacionProduccion']).'</option>';
                        }
                    }else{
                        $echo.='<option value="0" disabled selected>No hay registros</option>';
                    }
$echo.='</select>
            </div>
      <div class="col-sm-2">
        <label>Fecha Inicio</label>';
          $sql_date = "SELECT ISNULL(MIN(CAST(FechaAlimentacion AS date)),'1900-01-01') AS FechaAlimentacion 
          FROM Alimentacion_hornos_recetas
            INNER JOIN Hornos ON Alimentacion_hornos_recetas.idHorno=Hornos.idHorno";
          $res_date=sqlsrv_query($conn,$sql_date,$params,$options);
          while($aa = sqlsrv_fetch_array($res_date)){
            $fecha_min = date_format($aa['FechaAlimentacion'],'Y-m-d');
          }
  $echo.='<input class="form-control" id="fecha_ini" type="date" min="'.$fecha_min.'" max="'.date('Y-m-d').'">
      </div>
      <div class="col-sm-2">
        <label>Fecha Fin</label>
        <input class="form-control" id="fecha_fin" type="date" min="'.$fecha_min.'" max="'.date('Y-m-d').'">
      </div>
      <center>
        <button class="btn btn-primary " style="margin-top: 20px;" onclick="search_registers_coquizacion()">Buscar <span class="glyphicon glyphicon-search"></span></button>
      </center>
    </div><br></div>
    <div id="div_resultado_hornos"></div>
    <br><br><br>';
  echo $echo;
}elseif($_POST['band']==6){//OPCION PARA CARGAR REPORTE CLASIFICACION O MOLIENDA (PRODUCCION)
  $echo='';
}elseif($_POST['band']==7){//BUSCA REGISTROS DE COQUIZACION (PRODUCCION)
  $echo='';
  $txt_sql='';
  $txt_sql_ali='';
  $txt_sql_prod='';
  $empresa = $_POST['empresa'];
  $centro_trabajo = $_POST['centro_trabajo'];
  $clasificacion_alimentada = $_POST['clasificacion_alimentada'];
  $clasificacion_producida = $_POST['clasificacion_producida'];
  $fecha_ini = $_POST['fecha_ini'];
  $fecha_fin = $_POST['fecha_fin'];
  $txt_sql_ali.="(CAST(FechaAlimentacion AS date) BETWEEN '$fecha_ini' AND '$fecha_fin') ";
  $txt_sql_prod.="(CAST(FechaProduccion AS date) BETWEEN '$fecha_ini' AND '$fecha_fin') ";
  if($empresa != '0'){
      $txt_sql.= "AND idEmpresa='$empresa' ";
  }
  if($centro_trabajo != '0'){
      $txt_sql.= "AND idPlanta='$centro_trabajo' ";
  }
  if($clasificacion_alimentada != '0'){
      $txt_sql_ali.= "AND idClasificacionAlimentacion='$clasificacion_alimentada' ";
  }
  if($clasificacion_producida != '0'){
      $txt_sql_prod.= "AND idClasificacionProduccion='$clasificacion_producida' ";
  }
  $echo.='<br><div class="row"><div class="col-sm-6"><div class="table-responsive1">
    <center><h4>Alimentación</h4></center>
  <table class="table table-hover table-condensed table-bordered table-responsive table-striped" id="table_ali" border="1" align="center">';
  $SQL_ALIMENTACION = "SELECT Empresa,NombrePlanta,Zona,CAST(FechaAlimentacion AS date) AS Fecha,COUNT(idAlimentacion_hornos) AS Hornos,SUM(Indice_carga) AS Tm_alimentada,
    ClasificacionAlimentacion
  FROM vCoquizacion
  WHERE $txt_sql_ali $txt_sql
  GROUP BY Empresa,NombrePlanta,Zona,CAST(FechaAlimentacion AS date),ClasificacionAlimentacion
  ORDER BY Empresa,NombrePlanta,Zona,CAST(FechaAlimentacion AS date) DESC,ClasificacionAlimentacion";
  $resul_alimentacion=sqlsrv_query($conn,$SQL_ALIMENTACION,$params,$options);
  $rows_alimentacion = sqlsrv_num_rows($resul_alimentacion);
  if($rows_alimentacion>0){
    $echo.='<thead>
            <th>Empresa</th>
            <th>Centro Trabajo</th>
            <th>Zona</th>
            <th>Fecha</th>
            <th># Hornos</th>
            <th>TM</th>
            <th>Clasificación</th>
            </thead>
            <tbody>';
    while($aa=sqlsrv_fetch_array($resul_alimentacion)){
      $echo.='<tr>';
      $echo.='<td>'.utf8_encode($aa['Empresa']).'</td>';
      $echo.='<td>'.utf8_encode($aa['NombrePlanta']).'</td>';
      $echo.='<td>'.utf8_encode($aa['Zona']).'</td>';
      $echo.='<td>'.date_format($aa['Fecha'],'Y-m-d').'</td>';
      $echo.='<td>'.$aa['Hornos'].'</td>';
      $echo.='<td>'.number_format($aa['Tm_alimentada'],2).'</td>';
      $echo.='<td>'.utf8_encode($aa['ClasificacionAlimentacion']).'</td>';
      $echo.='</tr>';
    }
    $echo.='</tbody>';
  }
  $echo.='</table></div></div>';
  $echo.='<div class="col-sm-6"><div class="table-responsive1">
    <center><h4>Producción</h4></center>
  <table class="table table-hover table-condensed table-bordered table-responsive table-striped" id="table_prod" border="1" align="center">';
  $SQL_PRODUCCION = "SELECT Empresa,NombrePlanta,Zona,CAST(FechaProduccion AS date) AS Fecha,COUNT(idProduccion_hornos) AS Hornos,
    SUM(Indice_deshorne) AS Tm_alimentada,ClasificacionProduccion
  FROM vCoquizacion
  WHERE $txt_sql_prod $txt_sql
  GROUP BY Empresa,NombrePlanta,Zona,CAST(FechaProduccion AS date),ClasificacionProduccion
  ORDER BY Empresa,NombrePlanta,Zona,CAST(FechaProduccion AS date) DESC,ClasificacionProduccion";
  $resul_produccion=sqlsrv_query($conn,$SQL_PRODUCCION,$params,$options);
  $rows_produccion = sqlsrv_num_rows($resul_produccion);
  if($rows_produccion>0){
    $echo.='<thead>
            <th>Empresa</th>
            <th>Centro Trabajo</th>
            <th>Zona</th>
            <th>Fecha</th>
            <th># Hornos</th>
            <th>TM</th>
            <th>Clasificación</th>
            </thead>
            <tbody>';
    while($aa=sqlsrv_fetch_array($resul_produccion)){
      $echo.='<tr>';
      $echo.='<td>'.utf8_encode($aa['Empresa']).'</td>';
      $echo.='<td>'.utf8_encode($aa['NombrePlanta']).'</td>';
      $echo.='<td>'.utf8_encode($aa['Zona']).'</td>';
      $echo.='<td>'.date_format($aa['Fecha'],'Y-m-d').'</td>';
      $echo.='<td>'.$aa['Hornos'].'</td>';
      $echo.='<td>'.number_format($aa['Tm_alimentada'],2).'</td>';
      $echo.='<td>'.utf8_encode($aa['ClasificacionProduccion']).'</td>';
      $echo.='</tr>';
    }
    $echo.='</tbody>';
  }
  $echo.='</table></div></div></div>';
  echo $echo;
}elseif($_POST['band']==8){
  $txt_array_empresa = implode("','", $_SESSION['Array_empresa']['CONSULTAS']);
  $echo = '';
  $echo.='<div class="row">
          <div class="col-xs-6 col-sm-1"></div>
          <div class="col-xs-6 col-sm-2">
              <label>Empresa</label>
              <select class="form-control" id="empresa">';
              $sql_empresa = "SELECT Empresa,idEmpresa FROM vInventarioSaldo 
                  WHERE idEmpresa IN ('$txt_array_empresa')
                  GROUP BY Empresa,idEmpresa ORDER BY Empresa";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idEmpresa'].'">'.utf8_encode($aa['Empresa']).'</option>';
                  }
              }
              $echo.='</select>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2">
              <label>Unidad de negocio</label>
              <select class="form-control" id="idUnidadNegocio">
                  <option value="0">TODOS</option>';
              $sql_empresa = "SELECT * FROM UnidadDeNegocio ORDER BY Descripcion";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idUnidadNegocio'].'">'.utf8_encode($aa['Descripcion']).'</option>';
                  }
              }
      $echo.='</select>
      </div>
          <div class="col-xs-6 col-sm-2">
              <label>Destino</label>
              <select class="form-control" id="centro_trabajo">
                  <option value="00000000-0000-0000-0000-000000000000">TODOS</option>';
              $sql_empresa = "SELECT Destino,idDestino FROM vInventarioSaldo 
                  WHERE idEmpresa IN ('$txt_array_empresa')
                  GROUP BY Destino,idDestino ORDER BY Destino";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idDestino'].'">'.utf8_encode($aa['Destino']).'</option>';
                  }
              }
              $echo.='</select>
          </div>
          <div class="col-xs-6 col-sm-2">
              <label>Fecha Inicio</label>
              <input type="date" id="fecha_ini" class="form-control" max="'.date('Y-m-d').'" value="'.date('Y-m-d').'">
          </div>
          <div class="col-xs-6 col-sm-2">
              <label>Fecha Fin</label>
              <input type="date" id="fecha_fin" class="form-control" max="'.date('Y-m-d').'" value="'.date('Y-m-d').'">
          </div>
          <div class="col-xs-6 col-sm-1">
              <button class="btn btn-primary" id="btn_actualizar_inv" onclick="load_body_inventory(1)" style="margin-top: 25px;"><span class="glyphicon glyphicon-refresh"></span></button>
              <button class="btn btn-success" id="btn_excel" onclick="generar_excel()" style="margin-top: 25px;"><span class="glyphicon glyphicon-export"></span></button>
          </div>
          <form method="POST" id="form_excel">
            <input type="hidden" id="tabla_excel" name="tabla_excel">
          </form>
      </div>
      <div class="row">
          <br>
          <div class="col-sm-12" id="div_inventario_body"></div>
      </div>';
  echo $echo;
}elseif($_POST['band']==9){
  $idEmpresa = $_POST['idEmpresa'];
  $idUnidadNegocio = $_POST['idUnidadNegocio'];
  $txt_UnidadDeNegocio = "";
  if($idUnidadNegocio!='0'){
      $txt_UnidadDeNegocio=" WHERE Clasificacion.UnidadDeNegocio='$idUnidadNegocio'";
  }
  $idDestino = $_POST['idDestino'];
  $FechaInicioSaldo = $_POST['FechaInicioSaldo'];
  $FechaFinSaldo = $_POST['FechaFinSaldo'];
  $echo = '<div class="table-responsive1">';
  $sql_group = "SELECT Clasificacion/*,SUM(SaldoFinal) AS SaldoFinal*/ FROM dbo.Get_SaldoInventario('$idEmpresa','$idDestino','$FechaInicioSaldo','$FechaFinSaldo') AS a 
    INNER JOIN Clasificacion ON a.idClasificacion=Clasificacion.idClasificacion $txt_UnidadDeNegocio GROUP BY Clasificacion ORDER BY Clasificacion";
  $resul_group=sqlsrv_query($conn,$sql_group,$params,$options);
  $rows_group=sqlsrv_num_rows($resul_group);
  if($rows_group>0){
    $echo.='<br><table class="table table-bordered table-condensed" id="table_inventory_detail">
            <thead>
            <th>Centro Trabajo</th>';
    $const_clasificacion='';
    $position_clasificacion=0;
    while($aa = sqlsrv_fetch_array($resul_group)){
      $clasificacion = utf8_encode($aa['Clasificacion']);
      //$saldoFinal = $aa['SaldoFinal'];
      $echo.='<th>'.$clasificacion.'</th>';
      //$array_totales_columns[$position_clasificacion]=$saldoFinal;
        

        $const_clasificacion=$clasificacion;
        $array_clasificacion[$position_clasificacion]=$const_clasificacion;
        $position_clasificacion++;
    }
    $echo.='</thead>
            <tbody>';
    $const_destino='';
    $position_clasificacion=0;
    $sql="SELECT FechaFinSaldo,UnidadDeNegocio.Descripcion AS UnidadDeNegocio,Destino,Clasificacion,ISNULL(SaldoFinal,0) AS SaldoFinal 
FROM dbo.Get_SaldoInventario('$idEmpresa','$idDestino','$FechaInicioSaldo','$FechaFinSaldo') AS a
  INNER JOIN Clasificacion ON a.idClasificacion=Clasificacion.idClasificacion
  INNER JOIN UnidadDeNegocio ON Clasificacion.UnidadDeNegocio=UnidadDeNegocio.idUnidadNegocio $txt_UnidadDeNegocio 
GROUP BY FechaFinSaldo,Destino,UnidadDeNegocio.Descripcion,Clasificacion,SaldoFinal
ORDER BY FechaFinSaldo,UnidadDeNegocio.Descripcion,Destino,Clasificacion";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    while($bb = sqlsrv_fetch_array($resul)){
      $destino = utf8_encode($bb['Destino']);
      $clasificacion = utf8_encode($bb['Clasificacion']);
      $saldoFinal = number_format($bb['SaldoFinal'],2);
      //SUMA ARRAY_TOTALES
      if(isset($array_totales_columns[$clasificacion])){
        $array_totales_columns[$clasificacion]+=$bb['SaldoFinal'];
      }else{
        $array_totales_columns[$clasificacion]=$bb['SaldoFinal'];
      }
      if($const_destino==''){
        $const_destino=$destino;
        $echo.='<tr>';
        $echo.='<td>'.$const_destino.'</td>';
        for ($i=$position_clasificacion; $i < count($array_clasificacion); $i++){ 
          if($array_clasificacion[$i]==$clasificacion){
            $echo.='<td>'.$saldoFinal.'</td>';
            $position_clasificacion=$i+1;
            break;
          }else{
            $echo.='<td>0</td>';
          }
        }
      }elseif($const_destino==$destino){
        for ($i=$position_clasificacion; $i < count($array_clasificacion); $i++){ 
          if($array_clasificacion[$i]==$clasificacion){
            $echo.='<td>'.$saldoFinal.'</td>';
            $position_clasificacion=$i+1;
            break;
          }else{
            $echo.='<td>0</td>';
          }
        }
      }elseif($const_destino<>$destino){
        for ($i=$position_clasificacion; $i <count($array_clasificacion); $i++){ 
            $echo.='<td>0</td>';
        }
        $echo.='</tr>';
        $const_destino=$destino;
        $position_clasificacion=0;
        $echo.='<tr>';
        $echo.='<td>'.$const_destino.'</td>';
        for ($i=$position_clasificacion; $i < count($array_clasificacion); $i++){ 
          if($array_clasificacion[$i]==$clasificacion){
            $echo.='<td>'.$saldoFinal.'</td>';
            $position_clasificacion=$i+1;
            break;
          }else{
            $echo.='<td>0</td>';
          }
        }
      }
    }
    for ($i=$position_clasificacion; $i <count($array_clasificacion); $i++){ 
      $echo.='<td>0</td>';
    }
    $echo.='</tr><tr><td><b>TOTALES</b></td>';
    for ($i=0; $i <count($array_clasificacion); $i++){
      $echo.='<td><b>'.number_format($array_totales_columns[$array_clasificacion[$i]],2).'</b></td>';
    }
    $echo.='</tr></tbody>
        </table>';
    /*echo '<pre>';
    print_r($array_clasificacion);
    echo '</pre>';*/
    $echo.='</table>
    </div>';
    echo $echo;
  }
}elseif($_POST['band']==10){
  $txt_array_empresa = implode("','", $_SESSION['Array_empresa']['CONSULTAS']);
  $echo = '<center><h1>Inventarios</h1></center><br>';
  $echo.='<div class="row">
          <div class="col-sm-1"></div>
          <div class="col-xs-6 col-sm-2">
              <label>Empresa</label>
              <select class="form-control" id="empresa">';
              $sql_empresa = "SELECT Empresa,idEmpresa FROM vInventarioSaldo 
                  WHERE idEmpresa IN ('$txt_array_empresa')
                  GROUP BY Empresa,idEmpresa ORDER BY Empresa";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idEmpresa'].'">'.utf8_encode($aa['Empresa']).'</option>';
                  }
              }
              $echo.='</select>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2">
              <label>Unidad de negocio</label>
              <select class="form-control" id="idUnidadNegocio">
                  <option value="0" disabled selected>Seleccione</option>';
              $sql_empresa = "SELECT * FROM UnidadDeNegocio ORDER BY Descripcion";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idUnidadNegocio'].'">'.utf8_encode($aa['Descripcion']).'</option>';
                  }
              }
      $echo.='</select>
      </div>
          <div class="col-xs-6 col-sm-2">
              <label>Destino</label>
              <select class="form-control" id="centro_trabajo">
                  <option value="00000000-0000-0000-0000-000000000000">TODOS</option>';
              $sql_empresa = "SELECT Destino,idDestino FROM vInventarioSaldo 
                  WHERE idEmpresa IN ('$txt_array_empresa')
                  GROUP BY Destino,idDestino ORDER BY Destino";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idDestino'].'">'.utf8_encode($aa['Destino']).'</option>';
                  }
              }
              $echo.='</select>
          </div>';
          $echo.='<div class="col-xs-6 col-sm-2">
              <label>Fecha Desde</label>
              <input class="form-control" type="date" id="fecha_desde">
              </div>';
          $echo.='<div class="col-xs-6 col-sm-2">
              <label>Fecha Hasta</label>
              <input class="form-control" type="date" id="fecha_hasta">
              </div>';
    /*$echo.='<div class="col-xs-6 col-sm-1">
              <label>Año</label>
              <select class="form-control" id="anno_inv" onchange="change_anno()">
                  <option value="0" disabled selected>Seleccione</option>';
                  for ($i=(date('Y')-1); $i <= date('Y'); $i++) { 
                    $echo.='<option value="'.$i.'">'.$i.'</option>';
                  }
              $echo.='</select>
            </div>';
    $echo.='<div class="col-xs-6 col-sm-1">
              <label>Mes-Semana</label>
              <select class="form-control" id="semana_inv">
                  <option value="-1" disabled selected>Seleccione año</option>';
              $echo.='</select>
            </div>';*/
  $echo.='<div class="col-xs-6 col-sm-1">
              <button class="btn btn-primary" id="btn_actualizar_inv" onclick="load_body_inventory(2)" style="margin-top: 25px;"><span class="glyphicon glyphicon-refresh"></span></button>
              <button class="btn btn-success" id="btn_excel" onclick="generar_excel()" style="margin-top: 25px;"><span class="glyphicon glyphicon-export"></span></button>
          </div>
          <div class="col-xs-6 col-sm-2">
            <form method="POST" id="form_excel">
              <input type="hidden" id="tabla_excel" name="tabla_excel">
            </form>
          </div>
        </div>
      <div class="row">
          <br>
          <div class="col-sm-12" id="div_inventario_body"></div>
      </div>';
  echo $echo;
}elseif($_POST['band']==11){
  $anno_inv = $_POST['anno_inv'];
  $sql = "SELECT * FROM semana_weekly WHERE YEAR(FechaInicio)='$anno_inv' AND FechaFin<=CAST(GETDATE() AS date) ORDER BY Semana";
  $resul=sqlsrv_query($conn,$sql,$params,$options);
  $rows = sqlsrv_num_rows($resul);
  $echo='';
  if($rows>0){
    $echo.='<option value=0 >Todas</option>';
    while($aa = sqlsrv_fetch_array($resul)){
      $echo.='<option value="'.$aa['Mes'].'-'.$aa['Semana'].'">'.$aa['Mes'].'-'.$aa['Semana'].'</option>';
    }
  }else
    $echo.='<option value=-1>No hay semanas</option>';
  echo $echo;
}elseif($_POST['band']==12){
  $idEmpresa = $_POST['idEmpresa'];
  $idUnidadNegocio = $_POST['idUnidadNegocio'];
  $idDestino = $_POST['idDestino'];
  $FechaInicio = $_POST['fecha_desde'];
  $FechaFin = $_POST['fecha_hasta'];
  //$anno = $_POST['anno'];
  //$semana = explode("-",$_POST['semana']);
  $echo = '<div class="table-responsive1">';
  /*
  $sql_semana = "SELECT * FROM semana_weekly WHERE YEAR(FechaInicio)='$anno' AND Mes='$semana[0]' 
    AND Semana='$semana[1]'";
  $res_semana = sqlsrv_query($conn,$sql_semana);
  while($as = sqlsrv_fetch_array($res_semana)){
    $FechaInicio = date_format($as['FechaInicio'],'Y-m-d');
    $FechaFin = date_format($as['FechaFin'],'Y-m-d');
  }
  */
  $sql_clasif = "SELECT Destino,Clasificacion,SaldoInicial,SaldoFinal FROM [dbo].[Get_SaldoInventarioDestino_v2_detailReport] ('$idEmpresa','$idDestino','00000000-0000-0000-0000-000000000000','$FechaInicio','$FechaFin') AS a
  INNER JOIN Clasificacion ON a.idClasificacion=Clasificacion.idClasificacion AND Clasificacion.UnidadDeNegocio='$idUnidadNegocio'
GROUP BY Destino,Clasificacion,SaldoInicial,SaldoFinal ORDER BY Clasificacion,Destino";
  $resul_clasif=sqlsrv_query($conn,$sql_clasif,$params,$options);
  $rows_clasif = sqlsrv_num_rows($resul_clasif);
  if($rows_clasif>0){
    $position_clasificacion=0;
    $echo.='<br><table class="table table-bordered table-condensed" id="table_inventory_detail">
            <thead>
            <th align="center">Zona</th>
            <th align="center">Material</th>
            <th align="center">Destino</th>
            <th align="center">Acopio</th>
            <th align="center">Mes</th>
            <th align="center">Semana</th>
            <th align="center">Proveedor</th>
            <th align="center">Movimiento</th>
            <th align="center">Localidad</th>
            <th align="center">Detalle</th>
            <th align="center">Modalidad</th>';
    $array_title_clasif = [];
    while($aa = sqlsrv_fetch_array($resul_clasif)){
      $clasificacion = utf8_encode($aa['Clasificacion']);
      $destino=utf8_encode($aa['Destino']);
      $SaldoInicial=number_format($aa['SaldoInicial'],2,'.','');
      $SaldoFinal=number_format($aa['SaldoFinal'],2,'.','');
      if(is_bool(array_search($clasificacion, $array_title_clasif))){
        $array_title_clasif[$position_clasificacion] = $clasificacion;
        $position_clasificacion++;
      }
      $array_saldo_inv[$destino][$clasificacion.'||SaldoInicial']=$SaldoInicial;
      $array_saldo_inv[$destino][$clasificacion.'||SaldoFinal']=$SaldoFinal;
    }
    for ($i=0; $i < count($array_title_clasif); $i++) { 
      $echo.='<th align="center">'.$array_title_clasif[$i].'</th>'; 
    }
    $echo.='</thead><tbody>';
    //echo '<pre>';
    //print_r($array_title_clasif);
    //print_r($array_saldo_inv);
    //echo '</pre>';

    $sql="SELECT 'NDS' AS Zona, UnidadDeNegocio.Descripcion as UnidadDeNegocio,Destino,SaldoInicial,SaldoFinal,Empresa, 
  (CASE WHEN (Proceso='Entradas' OR TransaccionDetalle='Producción') THEN 'Entradas' ELSE 'Salidas' END) AS Movimiento,
  (CASE WHEN (a.iTransacion=0 OR a.iTransacion=1 OR a.iTransacion=6 OR a.iTransacion=14) THEN Localidad ELSE '' END) AS Localidad,Proceso,TransaccionDetalle,dbo.esTransaccionFacturable(iTransacion) as Modalidad,Clasificacion,SUM(ToneladasProceso) AS ToneladasProceso
FROM [dbo].[Get_SaldoInventarioDestino_v2_detailReport] ('$idEmpresa','$idDestino','00000000-0000-0000-0000-000000000000','$FechaInicio','$FechaFin') AS a
  INNER JOIN Clasificacion ON a.idClasificacion=Clasificacion.idClasificacion AND Clasificacion.UnidadDeNegocio='$idUnidadNegocio'
  LEFT JOIN  UnidadDeNegocio ON Clasificacion.UnidadDeNegocio=UnidadDeNegocio.idUnidadNegocio
GROUP BY UnidadDeNegocio.Descripcion,Destino,SaldoInicial,SaldoFinal,Empresa, 
  (CASE WHEN (Proceso='Entradas' OR TransaccionDetalle='Producción') THEN 'Entradas' ELSE 'Salidas' END),
  (CASE WHEN (a.iTransacion=0 OR a.iTransacion=1 OR a.iTransacion=6 OR a.iTransacion=14) THEN Localidad ELSE '' END),Proceso,TransaccionDetalle,iTransacion,Clasificacion
ORDER BY UnidadDeNegocio.Descripcion,Destino,Empresa,(CASE WHEN (Proceso='Entradas' OR TransaccionDetalle='Producción') THEN 'Entradas' ELSE 'Salidas' END),Localidad,Proceso,
  TransaccionDetalle,Clasificacion";
    $res = sqlsrv_query($conn,utf8_decode($sql));
    $var_constant='';
    $position_clasificacion=0;
    $total_movimientos = [];
    while($aa = sqlsrv_fetch_array($res)){
      $Zona = utf8_encode($aa['Zona']);
      $UnidadDeNegocio=utf8_encode($aa['UnidadDeNegocio']);
      $Destino=utf8_encode($aa['Destino']);
      $SaldoInicial=number_format($aa['SaldoInicial'],2,'.','');
      $saldoFinal=number_format($aa['SaldoFinal'],2,'.','');
      $Empresa=utf8_encode($aa['Empresa']);
      $Movimiento=utf8_encode($aa['Movimiento']);
      $Localidad=utf8_encode($aa['Localidad']);
      $Proceso=utf8_encode($aa['Proceso']);
      $TransaccionDetalle=utf8_encode($aa['TransaccionDetalle']);
      $Modalidad=utf8_encode($aa['Modalidad']);
      $Clasificacion=utf8_encode($aa['Clasificacion']);
      $ToneladasProceso=number_format($aa['ToneladasProceso'],2,'.','');
      $mezcla_variables = $Zona.'||'.$UnidadDeNegocio.'||'.$Destino.'||'.$Empresa.'||'.$Movimiento.'||'.$Localidad.'||'.$Proceso.'||'.$TransaccionDetalle;
      if($var_constant==''){
        $var_constant=$mezcla_variables;
        //// INICIO INVENTARIO INICIAL
        $echo.='<tr  style="background-color: powderblue; font-weight: bolder;">';
        $echo.='<td>'.$Zona.'</td>';
        $echo.='<td>'.$UnidadDeNegocio.'</td>';
        $echo.='<td>PRODUCCION</td>';
        $echo.='<td>'.$Destino.'</td>';
        $echo.='<td>'.$semana[0].'</td>';
        $echo.='<td>'.$semana[1].'</td>';
        $echo.='<td>'.$Empresa.'</td>';
        $echo.='<td>Entradas</td>';
        $echo.='<td></td>';
        $echo.='<td>Inv. Inicial</td>';
        $echo.='<td>NF</td>';
        for ($i=0; $i < count($array_title_clasif); $i++){
          if(isset($array_saldo_inv[$Destino][$array_title_clasif[$i].'||SaldoInicial'])){
            $echo.='<td>'.$array_saldo_inv[$Destino][$array_title_clasif[$i].'||SaldoInicial'].'</td>';
          }else{
            $echo.='<td>0.00</td>';
          }
        }
        $echo.='</tr>';
        //// FIN INVENTARIO INICIAL
        $echo.='<tr>';
        $echo.='<td>'.$Zona.'</td>';
        $echo.='<td>'.$UnidadDeNegocio.'</td>';
        $echo.='<td>PRODUCCION</td>';
        $echo.='<td>'.$Destino.'</td>';
        $echo.='<td>'.$semana[0].'</td>';
        $echo.='<td>'.$semana[1].'</td>';
        $echo.='<td>'.$Empresa.'</td>';
        $echo.='<td>'.$Movimiento.'</td>';
        if(isset($total_movimientos[$Clasificacion]))
          $total_movimientos[$Clasificacion]+=$aa['ToneladasProceso'];
        else
          $total_movimientos[$Clasificacion]=$aa['ToneladasProceso'];

        $echo.='<td>'.$Localidad.'</td>';
        if($Proceso=='Entradas' || $Proceso=='Salidas')
          $echo.='<td>'.$TransaccionDetalle.'</td>';
        else
          $echo.='<td>'.$Proceso.'-'.$TransaccionDetalle.'</td>';
        $echo.='<td>'.$Modalidad.'</td>';
        for ($i=$position_clasificacion; $i < count($array_title_clasif); $i++){
          if($array_title_clasif[$i]==$Clasificacion){
            $echo.='<td>'.$ToneladasProceso.'</td>';
            $position_clasificacion=$i+1;
            break;
          }else{
            $echo.='<td>0.00</td>';
          }
        }
      }elseif($var_constant==$mezcla_variables){
        if(isset($total_movimientos[$Clasificacion]))
          $total_movimientos[$Clasificacion]+=$aa['ToneladasProceso'];
        else
          $total_movimientos[$Clasificacion]=$aa['ToneladasProceso'];

        for ($i=$position_clasificacion; $i < count($array_title_clasif); $i++){
          if($array_title_clasif[$i]==$Clasificacion){
            $echo.='<td>'.$ToneladasProceso.'</td>';
            $position_clasificacion=$i+1;
            break;
          }else{
            $echo.='<td>0.00</td>';
          }
        }
      }elseif($var_constant<>$mezcla_variables){
        for ($i=$position_clasificacion; $i < count($array_title_clasif); $i++){
          $echo.='<td>0.00</td>';
        }
        $position_clasificacion=0;
        $explode = explode("||", $var_constant);
        $echo.='</tr>';
        if($explode[2]!=$Destino){
          //if($explode[4]!=$Movimiento){
            if($explode[4]=='Entradas')
              $css='#8AB658';
            else
              $css='#E8994E';
            $echo.='<tr style="background-color: '.$css.'; font-weight: bolder;">';
            $echo.='<td>'.$explode[0].'</td>';
            $echo.='<td>'.$explode[1].'</td>';
            $echo.='<td>PRODUCCION</td>';
            $echo.='<td>'.$explode[2].'</td>';
            $echo.='<td>'.$semana[0].'</td>';
            $echo.='<td>'.$semana[1].'</td>';
            $echo.='<td>'.$explode[3].'</td>';
            $echo.='<td>'.$explode[4].'</td>';
            $echo.='<td></td>';
            $echo.='<td>TOTAL '.$explode[4].'</td>';
            $echo.='<td></td>';
            for ($i=0; $i < count($array_title_clasif); $i++){
              if(isset($total_movimientos[$array_title_clasif[$i]]))
                $echo.='<td>'.number_format($total_movimientos[$array_title_clasif[$i]],2,'.','').'</td>';
              else
                $echo.='<td>0.00</td>';
            }
            $total_movimientos = [];
            $echo.='</tr>';
          //}
          //// INICIO INVENTARIO FINAL
          $echo.='<tr style="background-color: powderblue; font-weight: bolder;">';
          $echo.='<td>'.$explode[0].'</td>';
          $echo.='<td>'.$explode[1].'</td>';
          $echo.='<td>PRODUCCION</td>';
          $echo.='<td>'.$explode[2].'</td>';
          $echo.='<td>'.$semana[0].'</td>';
          $echo.='<td>'.$semana[1].'</td>';
          $echo.='<td>'.$explode[3].'</td>';
          $echo.='<td>Salidas</td>';
          $echo.='<td></td>';
          $echo.='<td>Inv. Final</td>';
          $echo.='<td>NF</td>';
          for ($i=0; $i < count($array_title_clasif); $i++){
            if(isset($array_saldo_inv[$explode[2]][$array_title_clasif[$i].'||SaldoFinal'])){
              $echo.='<td>'.$array_saldo_inv[$explode[2]][$array_title_clasif[$i].'||SaldoFinal'].'</td>';
            }else{
              $echo.='<td>0.00</td>';
            }
          }
          $echo.='</tr>';
          //// FIN INVENTARIO FINAL
          /************************************************************************************/
          //// INICIO INVENTARIO INICIAL
          $echo.='<tr style="background-color: powderblue; font-weight: bolder;">';
          $echo.='<td>'.$Zona.'</td>';
          $echo.='<td>'.$UnidadDeNegocio.'</td>';
          $echo.='<td>PRODUCCION</td>';
          $echo.='<td>'.$Destino.'</td>';
          $echo.='<td>'.$semana[0].'</td>';
          $echo.='<td>'.$semana[1].'</td>';
          $echo.='<td>'.$Empresa.'</td>';
          $echo.='<td>Entradas</td>';
          $echo.='<td></td>';
          $echo.='<td>Inv. Inicial</td>';
          $echo.='<td>NF</td>';
          for ($i=0; $i < count($array_title_clasif); $i++){
            if(isset($array_saldo_inv[$Destino][$array_title_clasif[$i].'||SaldoInicial'])){
              $echo.='<td>'.$array_saldo_inv[$Destino][$array_title_clasif[$i].'||SaldoInicial'].'</td>';
            }else{
              $echo.='<td>0.00</td>';
            }
          }
          $echo.='</tr>';
          //// FIN INVENTARIO INICIAL
        }else{
          if($explode[4]!=$Movimiento){
            if($explode[4]=='Entradas')
              $css='#8AB658';
            else
              $css='#E8994E';
            $echo.='<tr style="background-color: '.$css.'; font-weight: bolder;">';
            $echo.='<td>'.$explode[0].'</td>';
            $echo.='<td>'.$explode[1].'</td>';
            $echo.='<td>PRODUCCION</td>';
            $echo.='<td>'.$explode[2].'</td>';
            $echo.='<td>'.$semana[0].'</td>';
            $echo.='<td>'.$semana[1].'</td>';
            $echo.='<td>'.$explode[3].'</td>';
            $echo.='<td>'.$explode[4].'</td>';
            $echo.='<td></td>';
            $echo.='<td>TOTAL '.$explode[4].'</td>';
            $echo.='<td></td>';
            for ($i=0; $i < count($array_title_clasif); $i++){
              if(isset($total_movimientos[$array_title_clasif[$i]]))
                $echo.='<td>'.number_format($total_movimientos[$array_title_clasif[$i]],2,'.','').'</td>';
              else
                $echo.='<td>0.00</td>';
            }
            $echo.='</tr>';
            $total_movimientos = [];
          }
        }
        $var_constant=$mezcla_variables;
        $echo.='<tr>';
        $echo.='<td>'.$Zona.'</td>';
        $echo.='<td>'.$UnidadDeNegocio.'</td>';
        $echo.='<td>PRODUCCION</td>';
        $echo.='<td>'.$Destino.'</td>';
        $echo.='<td>'.$semana[0].'</td>';
        $echo.='<td>'.$semana[1].'</td>';
        $echo.='<td>'.$Empresa.'</td>';
        $echo.='<td>'.$Movimiento.'</td>';
        if(isset($total_movimientos[$Clasificacion]))
          $total_movimientos[$Clasificacion]+=$aa['ToneladasProceso'];
        else
          $total_movimientos[$Clasificacion]=$aa['ToneladasProceso'];

        $echo.='<td>'.$Localidad.'</td>';
        if($Proceso=='Entradas' || $Proceso=='Salidas')
          $echo.='<td>'.$TransaccionDetalle.'</td>';
        else
          $echo.='<td>'.$Proceso.'-'.$TransaccionDetalle.'</td>';
        $echo.='<td>'.$Modalidad.'</td>';
        for ($i=$position_clasificacion; $i < count($array_title_clasif); $i++){
          if($array_title_clasif[$i]==$Clasificacion){
            $echo.='<td>'.$ToneladasProceso.'</td>';
            $position_clasificacion=$i+1;
            break;
          }else{
            $echo.='<td>0.00</td>';
          }
        }
      }
    }
    for ($i=$position_clasificacion; $i < count($array_title_clasif); $i++){
      $echo.='<td>0.00</td>';
    }
    $position_clasificacion=0;
    $explode = explode("||", $var_constant);
    $echo.='</tr>';
    //if($explode[2]!=$Destino){
      //if($explode[4]!=$Movimiento){
        if($explode[4]=='Entradas')
          $css='#8AB658';
        else
          $css='#E8994E';
        $echo.='<tr style="background-color: '.$css.'; font-weight: bolder;">';
        $echo.='<td>'.$explode[0].'</td>';
        $echo.='<td>'.$explode[1].'</td>';
        $echo.='<td>PRODUCCION</td>';
        $echo.='<td>'.$explode[2].'</td>';
        $echo.='<td>'.$semana[0].'</td>';
        $echo.='<td>'.$semana[1].'</td>';
        $echo.='<td>'.$explode[3].'</td>';
        $echo.='<td>'.$explode[4].'</td>';
        $echo.='<td></td>';
        $echo.='<td>TOTAL '.$explode[4].'</td>';
        $echo.='<td></td>';
        for ($i=0; $i < count($array_title_clasif); $i++){
          if(isset($total_movimientos[$array_title_clasif[$i]]))
            $echo.='<td>'.number_format($total_movimientos[$array_title_clasif[$i]],2,'.','').'</td>';
          else
            $echo.='<td>0.00</td>';
        }
        $total_movimientos = [];
        $echo.='</tr>';
      //}
      //// INICIO INVENTARIO FINAL
      $echo.='<tr style="background-color: powderblue; font-weight: bolder;">';
      $echo.='<td>'.$explode[0].'</td>';
      $echo.='<td>'.$explode[1].'</td>';
      $echo.='<td>PRODUCCION</td>';
      $echo.='<td>'.$explode[2].'</td>';
      $echo.='<td>'.$semana[0].'</td>';
      $echo.='<td>'.$semana[1].'</td>';
      $echo.='<td>'.$explode[3].'</td>';
      $echo.='<td>Salidas</td>';
      $echo.='<td></td>';
      $echo.='<td>Inv. Final</td>';
      $echo.='<td>NF</td>';
      for ($i=0; $i < count($array_title_clasif); $i++){
        if(isset($array_saldo_inv[$explode[2]][$array_title_clasif[$i].'||SaldoFinal'])){
          $echo.='<td>'.$array_saldo_inv[$explode[2]][$array_title_clasif[$i].'||SaldoFinal'].'</td>';
        }else{
          $echo.='<td>0.00</td>';
        }
      }
      $echo.='</tr>';
      //// FIN INVENTARIO FINAL
    $echo.='</tbody></table></div>';
  }
  echo $echo;
}elseif($_POST['band']==13){
  $txt_where="";
  $idEmpresa = $_POST['idEmpresa'];
  $idProveedor = $_POST['idProveedor'];
  if($idProveedor!='0'){
    $txt_where.=" AND vTiquetesCargadores.idProveedor='$idProveedor'";
  }
  $idUnidadNegocio = $_POST['idUnidadNegocio'];
  if($idUnidadNegocio!='0'){
      $txt_where.=" AND vTiquetesCargadores.idUnidadNegocio='$idUnidadNegocio'";
  }
  $idDestino = $_POST['idDestino'];
  if($idDestino!='00000000-0000-0000-0000-000000000000'){
    $txt_where=" AND vTiquetesCargadores.idDestino='$idDestino'";
  }
  $FechaInicioSaldo = $_POST['FechaInicioSaldo'];
  $FechaFinSaldo = $_POST['FechaFinSaldo'];
  $echo = '<div class="table-responsive1">';
  $sql="SELECT Proveedor,Equipo,UnidadDeNegocio,Destino,Actividad,CASE WHEN (Actividad='DESCUENTO') THEN SUM(vTiquetesCargadores.Tiempo)*-1 ELSE SUM(vTiquetesCargadores.Tiempo) END AS Horas,Tarifa, CASE WHEN (Actividad='DESCUENTO') THEN 0 ELSE SUM(vTiquetesCargadores.Tiempo*Tarifa) END AS Valor,
  (FacturaCargador.prefijoFactura+'-'+CAST(FacturaCargador.numeroFactura AS Varchar(10))) AS Factura,FacturaCargador.facturaAsociada
FROM vTiquetesCargadores 
  LEFT JOIN FacturaCargadorDetalle ON vTiquetesCargadores.idRegistro=FacturaCargadorDetalle.idRegistro
  LEFT JOIN FacturaCargador ON FacturaCargadorDetalle.idFacturaCargador=FacturaCargador.idFacturaCargador
WHERE fechaTiquete BETWEEN '$FechaInicioSaldo' AND '$FechaFinSaldo' AND vTiquetesCargadores.idCliente='$idEmpresa' AND idActividad<>'00000000-0000-0000-0000-000000000000'
  $txt_where
GROUP BY Proveedor,Equipo,UnidadDeNegocio,Destino,Actividad,Tarifa,FacturaCargador.prefijoFactura,FacturaCargador.numeroFactura,FacturaCargador.facturaAsociada
ORDER BY Proveedor,Equipo,UnidadDeNegocio,Destino,Actividad";
  $resul=sqlsrv_query($conn,$sql,$params,$options);
  $rows=sqlsrv_num_rows($resul);
  if($rows>0){
    $echo.='<br><table class="table table-bordered table-condensed" id="table_inventory_detail">
            <thead>
            <th>Proveedor</th>
            <th>Equipo</th>
            <th>UnidadDeNegocio</th>
            <th>Destino</th>
            <th>Actividad</th>
            <th>Producto</th>
            <th>Horas</th>
            <th>Tarifa</th>
            <th>Valor</th>
            <th>Factura</th>
            <th>Fact. Asociada</th>
            </thead>
            <tbody>';
    while($aa = sqlsrv_fetch_array($resul)){
      $Proveedor=utf8_encode($aa['Proveedor']);
      $Equipo=utf8_encode($aa['Equipo']);
      $UnidadDeNegocio=utf8_encode($aa['UnidadDeNegocio']);
      $Destino=utf8_encode($aa['Destino']);
      $Actividad=utf8_encode($aa['Actividad']);
      $Producto=utf8_encode($aa['Producto']);
      $Horas=number_format($aa['Horas'],2,'.','');
      $Tarifa=number_format($aa['Tarifa']);
      $Valor=number_format($aa['Valor']);
      $Factura=utf8_encode($aa['Factura']);
      $FactAsociada=utf8_encode($aa['facturaAsociada']);
      $echo.='<tr>';
      $echo.='<td>'.$Proveedor.'</td>';
      $echo.='<td>'.$Equipo.'</td>';
      $echo.='<td>'.$UnidadDeNegocio.'</td>';
      $echo.='<td>'.$Destino.'</td>';
      $echo.='<td>'.$Actividad.'</td>';
      $echo.='<td>'.$Producto.'</td>';
      $echo.='<td>'.$Horas.'</td>';
      $echo.='<td>'.$Tarifa.'</td>';
      $echo.='<td>'.$Valor.'</td>';
      $echo.='<td>'.$Factura.'</td>';
      $echo.='<td>'.$FactAsociada.'</td>';
      $echo.='</tr>';
    }
    $echo.='</tbody></table>';
  }
  $echo.='</div>';
  echo $echo;
}elseif($_POST['band']==14){
  $txt_array_empresa = implode("','", $_SESSION['Array_empresa']['CONSULTAS']);
  $echo = '';
  $echo.='<div class="row">
          <div class="col-xs-6 col-sm-1"></div>
          <div class="col-xs-6 col-sm-2">
              <label>Empresa</label>
              <select class="form-control" id="empresa">';
              $sql_empresa = "SELECT Empresa,idEmpresa FROM vInventarioSaldo 
                  WHERE idEmpresa IN ('$txt_array_empresa')
                  GROUP BY Empresa,idEmpresa ORDER BY Empresa";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idEmpresa'].'">'.utf8_encode($aa['Empresa']).'</option>';
                  }
              }
              $echo.='</select>
          </div>
          <div class="col-xs-6 col-sm-2">
              <label>Destino</label>
              <select class="form-control" id="centro_trabajo">
                  <option value="00000000-0000-0000-0000-000000000000">TODOS</option>';
              $sql_empresa = "SELECT Destino,idDestino FROM vInventarioSaldo 
                  WHERE idEmpresa IN ('$txt_array_empresa')
                  GROUP BY Destino,idDestino ORDER BY Destino";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idDestino'].'">'.utf8_encode($aa['Destino']).'</option>';
                  }
              }
              $echo.='</select>
          </div>
          <div class="col-xs-6 col-sm-2">
              <label>Fecha Inicio</label>
              <input type="date" id="fecha_ini" class="form-control" max="'.date('Y-m-d').'" value="'.date('Y-m-d').'">
          </div>
          <div class="col-xs-6 col-sm-2">
              <label>Fecha Fin</label>
              <input type="date" id="fecha_fin" class="form-control" max="'.date('Y-m-d').'" value="'.date('Y-m-d').'">
          </div>
          <div class="col-xs-6 col-sm-1">
              <button class="btn btn-primary" id="btn_loader_consumos" onclick="search_consumos()" style="margin-top: 25px;"><span class="glyphicon glyphicon-refresh"></span></button>
              <button class="btn btn-success" id="btn_excel" onclick="generar_excel()" style="margin-top: 25px;"><span class="glyphicon glyphicon-export"></span></button>
          </div>
          <form method="POST" id="form_excel">
            <input type="hidden" id="tabla_excel" name="tabla_excel">
          </form>
      </div>
      <div class="row">
          <br>
          <div class="col-sm-12" id="div_inventario_body"></div>
      </div>';
  echo $echo;
}elseif($_POST['band']==15){
  $txt_where="";
  $idEmpresa = $_POST['idEmpresa'];
  /*$idUnidadNegocio = $_POST['idUnidadNegocio'];
  if($idUnidadNegocio!='0'){
      $txt_where.=" AND vTiquetesCargadores.idUnidadNegocio='$idUnidadNegocio'";
  }*/
  $idDestino = $_POST['idDestino'];
  $FechaInicioSaldo = $_POST['FechaInicioSaldo'];
  $FechaFinSaldo = $_POST['FechaFinSaldo'];
  $echo = '<div class="table-responsive1">';

  $sql_group = "SELECT Clasificacion,Proceso,case Proceso when 'Entradas' then 1 when 'Coquización' then 2 when 'Salidas' then 3 end  as orden
FROM Get_SaldoInventarioDestino_v2('$idEmpresa','$idDestino','00000000-0000-0000-0000-000000000000','$FechaInicioSaldo','$FechaFinSaldo') 
WHERE Clasificacion IN ('Mezcla Reactivo','Mezcla Metalúrgico','Coque ROO-Reactivo','Coque ROO-Metalúrgico') AND Proceso IN ('Entradas','Salidas','Coquización') 
AND TransaccionDetalle IN ('Traslado entre Acopio','Alimentación','Producción','Manejo de muestras') 
GROUP BY Clasificacion,Proceso
ORDER BY Clasificacion DESC,Proceso , orden";
    $resul_group=sqlsrv_query($conn,utf8_decode($sql_group));

  $sql = "  SELECT  Destino,Clasificacion,SaldoInicial,Proceso,SUM(ToneladasProceso) as ToneladasProceso,SaldoFinal 
    ,case Proceso when 'Entradas' then 1 when 'Coquización' then 2 when 'Salidas' then 3 end  as orden
FROM Get_SaldoInventarioDestino_v2('$idEmpresa','$idDestino','00000000-0000-0000-0000-000000000000','$FechaInicioSaldo','$FechaFinSaldo') 
WHERE Clasificacion IN ('Mezcla Reactivo','Mezcla Metalúrgico','Coque ROO-Reactivo','Coque ROO-Metalúrgico') AND Proceso IN ('Entradas','Salidas','Coquización') 
AND TransaccionDetalle IN ('Traslado entre Acopio','Alimentación','Producción','Manejo de muestras') 
GROUP BY Destino,Clasificacion,SaldoInicial,Proceso,SaldoFinal
ORDER BY Destino,Clasificacion DESC,orden ";
  $resul=sqlsrv_query($conn,utf8_decode($sql));

/*$sql_group = "SELECT Clasificacion,Proceso,case Proceso when 'Entradas' then 1 when 'Coquización' then 2 when 'Salidas' then 3 end  as orden
FROM Get_SaldoInventarioDestino_v2('24B7153E-AB4C-4DB7-81BD-67BC87AF014C','00000000-0000-0000-0000-000000000000','2022-07-01','2022-07-15') 
WHERE Clasificacion IN ('Mezcla Reactivo','Mezcla Metalúrgico','Coque ROO-Reactivo','Coque ROO-Metalúrgico') AND Proceso IN ('Entradas','Salidas','Coquización') 
AND TransaccionDetalle IN ('Traslado entre Acopio','Alimentación','Producción') 
GROUP BY Clasificacion,Proceso
ORDER BY Clasificacion DESC,Proceso , orden";
    $resul_group=sqlsrv_query($conn,utf8_decode($sql_group));

    $sql = "  SELECT  Destino,Clasificacion,SaldoInicial,Proceso,TransaccionDetalle,SUM(ToneladasProceso) as ToneladasProceso,SaldoFinal 
    ,case Proceso when 'Entradas' then 1 when 'Coquización' then 2 when 'Salidas' then 3 end  as orden
FROM Get_SaldoInventarioDestino_v2('24B7153E-AB4C-4DB7-81BD-67BC87AF014C','00000000-0000-0000-0000-000000000000','2022-07-01','2022-07-15') 
WHERE Clasificacion IN ('Mezcla Reactivo','Mezcla Metalúrgico','Coque ROO-Reactivo','Coque ROO-Metalúrgico') AND Proceso IN ('Entradas','Salidas','Coquización') 
AND TransaccionDetalle IN ('Traslado entre Acopio','Alimentación','Producción') 
GROUP BY Destino,Clasificacion,SaldoInicial,Proceso,TransaccionDetalle,SaldoFinal
ORDER BY Destino,Clasificacion DESC,orden ";
  $resul=sqlsrv_query($conn,utf8_decode($sql));*/



$array_proceso=[];
$array_clasificacion = [];
$var_position_proceso=0;
$var_position_clasificacion=0;
$col_clasificacion=0;
$BAND_CUENTA_SUBNIVEL=0;
$const_clasificacion = '';

$const_idDestino = '';
$const_idClasificacion = '';
$rowspan_general = 1;
$colspan_title=0;


    while($aa = sqlsrv_fetch_array($resul_group)){

            $clasificacion = utf8_encode($aa['Clasificacion']);
            $proceso = utf8_encode($aa['Proceso']);

            if($const_clasificacion == '' || $const_clasificacion==$clasificacion){

                $const_clasificacion = $clasificacion;
                $array_proceso[$const_clasificacion][$var_position_proceso] = $proceso;
                $col_clasificacion++;
                $var_position_proceso++;
                $BAND_CUENTA_SUBNIVEL++;

            }else{

                $array_clasificacion[$var_position_clasificacion] = $const_clasificacion;
                $var_position_clasificacion++;
                $col_clasificacion = 0;
                $var_position_proceso = 0;
                if($const_clasificacion <> 'Entradas' && $const_clasificacion <> 'Coquización' && $const_clasificacion <> 'Salidas'){
                    $BAND_CUENTA_SUBNIVEL++;
                }
                $const_clasificacion = $clasificacion;
                $array_proceso[$const_clasificacion][$var_position_proceso] = $proceso;
                $col_clasificacion++;
                $var_position_proceso++;
                $BAND_CUENTA_SUBNIVEL++;

            }
  }
    //fin while


    $array_clasificacion[$var_position_clasificacion] = $const_clasificacion;
        $var_position_clasificacion++;
        if(isset($array_proceso)){
            $rowspan_general++;
        }

        if($const_clasificacion <> 'Entradas' && $const_clasificacion <> 'Coquización' && $const_clasificacion <> 'Salidas'){
            $BAND_CUENTA_SUBNIVEL++;
        }
        
        $colspan=$BAND_CUENTA_SUBNIVEL;   

        $echo .= ' <div class="row">
         <div class="col-sm-12">
         <table id="example1" class=" table table-hover table-condensed table-bordered table-responsive table-striped">
         <thead>';  

        $echo.='<th rowspan="'.$rowspan_general.'"  colspan="1" align="center" style="background-color: #CFE2F9;  vertical-align: inherit;" >Planta</th>';    
    

    $colspan_title=1;


    $fila_superior = '';
    $fila_inferior = '';
    $posicion_array_clasificacion =0;
    $iteracion_color=0;

    foreach ($array_proceso as $value) {

      $color = '#0AA1DD';     
    
          if(explode(' ',$array_clasificacion[$iteracion_color])[0]=='Mezcla'){
            $color='#79DAE8';
          }
          $iteracion_color++;


      $colsp = count($value)+2;
        $fila_superior.= '<th rowspan="1" colspan="'.$colsp.'" style="background-color: '.$color.'; vertical-align: middle; text-align: center;">'.$array_clasificacion[$posicion_array_clasificacion].'</th>';

        $fila_inferior.='<th rowspan="1" colspan="1" style="background-color: '.$color.'; vertical-align: middle; text-align: center;">Saldo Inicial</th>';
        $tem_entrada='';
        $tem_coquiz='';
        $tem_salida='';

        for ($i=0; $i < count($value); $i++){

          if($value[$i]=='Entradas'){
            $tem_entrada='<th rowspan="1" colspan="1" style="background-color: '.$color.'; vertical-align: middle; text-align: center;">'.$value[$i].'</th>'; 
          }

          if($value[$i]=='Coquización'){
            $tem_coquiz='<th rowspan="1" colspan="1" style="background-color: '.$color.'; vertical-align: middle; text-align: center;">'.$value[$i].'</th>'; 
          }

          if($value[$i]=='Salidas'){
            $tem_salida='<th rowspan="1" colspan="1" style="background-color: '.$color.'; vertical-align: middle; text-align: center;">'.$value[$i].'</th>'; 
          }              
      }

      $fila_inferior.=$tem_entrada.$tem_coquiz.$tem_salida;
      $fila_inferior.='<th rowspan="1" colspan="1" style="background-color: '.$color.'; vertical-align: middle; text-align: center;">Saldo Final</th>';
      $posicion_array_clasificacion++;
    }//fin for proceso


    $fila_superior.='</tr><tr>';
    $echo.=$fila_superior.$fila_inferior;
  $echo.='</tr>';
    $echo.='</tr>';
    $echo.='</thead>';



  ////////////////////////////////VALORES////////////////////////////
    $const_clasificacion = '';          
    $const_destino = '';
    $saldo=false;
    $ultima_clasificacion_insertada=0;
    $ultimo_proceso_insertad0=0;
    $final_anterior=0;
    $array_sum = [];

    $clasif_anterior='';
    $dest_anterior='';
    $inicio_anterior = 0;
    $fin_anterior = 0;

    while($aa = sqlsrv_fetch_array($resul)){

        $Destino = $aa['Destino'];
        $Clasificacion = utf8_encode($aa['Clasificacion']);
        $SaldoInicial = number_format($aa['SaldoInicial'],2);
        $Proceso = utf8_encode($aa['Proceso']);
        $TransaccionDetalle = $aa['TransaccionDetalle'];
        $ToneladasProceso = number_format($aa['ToneladasProceso'],2);
        $SaldoFinal = number_format($aa['SaldoFinal'],2);   


        //sumas//
        if(isset($array_sum[$Clasificacion][$Proceso]))
          $array_sum[$Clasificacion][$Proceso]+= $aa['ToneladasProceso'];//$ToneladasProceso;

        else 
          $array_sum[$Clasificacion][$Proceso]=$aa['ToneladasProceso'];//$ToneladasProceso;


        if($clasif_anterior != $Clasificacion&&$clasif_anterior!=''||($Destino!=$dest_anterior && $clasif_anterior == $Clasificacion&&$dest_anterior!='')){

          if(isset($array_sum[$clasif_anterior]['Inicial'])){ 
          $array_sum[$clasif_anterior]['Inicial'] += $inicio_anterior;
          $array_sum[$clasif_anterior]['Final'] += $fin_anterior;
            }else{
            $array_sum[$clasif_anterior]['Inicial'] = $inicio_anterior;
          $array_sum[$clasif_anterior]['Final'] = $fin_anterior;
            }
        }

     $clasif_anterior=$Clasificacion;
     $inicio_anterior = $aa['SaldoInicial'];
       $fin_anterior = $aa['SaldoFinal'];
       $dest_anterior = $Destino;
    




        //////////////////////////Destino nuevo///////////////////////
        if($const_destino == ''){

          $echo.= '<tr role="row" class="odd"><td class="">'.$Destino.'</td>';
          $const_destino=$Destino;

        }elseif($const_destino != $Destino){
                      
      if($ultimo_proceso_insertad0!=3){ 

              $contiene_entrada =false;
              $contiene_coquizacion = false;
              $contiene_salidas = false;

              for ($j=0; $j < count($array_proceso[$const_clasificacion]); $j++) {
                  if($array_proceso[$const_clasificacion][$j]=='Entradas')$contiene_entrada = true;
                  if($array_proceso[$const_clasificacion][$j]=='Coquización')$contiene_coquizacion = true;
                  if($array_proceso[$const_clasificacion][$j]=='Salidas')$contiene_salidas = true;            
              }

              if($contiene_entrada&&$ultimo_proceso_insertad0==1){

                                 if($contiene_coquizacion) $echo.= '<td class="">00.0</td>';
                                 if($contiene_salidas) $echo.= '<td class="">00.0</td>';
                                 $echo.= '<td class="">'.$final_anterior.'</td>';
                                 $ultima_clasificacion_insertada++;
                                 $saldo=false;
                                 $ultimo_proceso_insertad0=3;                            

                                 }elseif($contiene_coquizacion && $ultimo_proceso_insertad0==2){

                                 if($contiene_salidas) $echo.= '<td class="">00.0</td>';
                                 $echo.= '<td class="">'.$final_anterior.'</td>';
                                 $ultima_clasificacion_insertada++;//pasaria a 4
                                 $saldo=false;
                                 $ultimo_proceso_insertad0=3;

                            }    
                        }
                                         
                               for ($j=$ultima_clasificacion_insertada; $j <count($array_clasificacion); $j++) {
                                    for ($i=0; $i <count($array_proceso[$array_clasificacion[$j]])+2 ; $i++) { 
                                        $echo.= '<td class="">0.00</td>';
                                    }           
                                }                               
                                                         
                            $echo.= '</tr>';
                            $echo.= '<tr role="row" class="odd"><td class="">'.$Destino.'</td>';
                            $const_destino=$Destino;
                            $ultima_clasificacion_insertada=0;  
                            $ultimo_proceso_insertad0=0;  
                            $saldo=false;
                            $const_clasificacion='';    
                    }
             ///////////////////////////////////Fin Destino ////////////////////////////////////////
 
             

             //////////////////////////////////Clasificaciones//////////////////////////////
                    
                    //finaliza la clasificacion para insertar la nueva
                if($const_clasificacion!=''&&$const_clasificacion!=$Clasificacion&&$ultimo_proceso_insertad0!=3){
                  
                     $contiene_entrada =false;
                     $contiene_coquizacion = false;
                     $contiene_salidas = false;

                                for ($j=0; $j < count($array_proceso[$const_clasificacion]); $j++) {
                                    if($array_proceso[$const_clasificacion][$j]=='Entradas')$contiene_entrada = true;
                                    if($array_proceso[$const_clasificacion][$j]=='Coquización')$contiene_coquizacion = true;
                                    if($array_proceso[$const_clasificacion][$j]=='Salidas')$contiene_salidas = true;            
                                }                   
            

                        if($contiene_entrada&&$ultimo_proceso_insertad0==1){
                          
                            if($contiene_coquizacion) $echo.= '<td class="">00.0</td>';
                            if($contiene_salidas) $echo.= '<td class="">00.0</td>';
                            $echo.= '<td class="">'.$final_anterior.'</td>';
                            $ultima_clasificacion_insertada++;
                            $saldo=false;
                            $ultimo_proceso_insertad0=3;                            

                        }elseif($contiene_coquizacion && $ultimo_proceso_insertad0==2){
                          
                            if($contiene_salidas) $echo.= '<td class="">00.0</td>';
                            $echo.= '<td class="">'.$final_anterior.'</td>';
                            $ultima_clasificacion_insertada++;
                            $saldo=false;
                            $ultimo_proceso_insertad0=3;

                        }                       

                }


                for ($i=$ultima_clasificacion_insertada; $i < count($array_clasificacion); $i++) {

                             $contiene_entrada =false;
                             $contiene_coquizacion = false;
                             $contiene_salidas = false;

                             for ($j=0; $j < count($array_proceso[$array_clasificacion[$i]]); $j++) { //cuantas col 
                                if($array_proceso[$array_clasificacion[$i]][$j]=='Entradas')$contiene_entrada = true;
                                if($array_proceso[$array_clasificacion[$i]][$j]=='Coquización')$contiene_coquizacion = true;
                                if($array_proceso[$array_clasificacion[$i]][$j]=='Salidas')$contiene_salidas = true;            
                             }
                       

                            if($Clasificacion==$array_clasificacion[$i]){
                                
                                $ultima_clasificacion_insertada=$i;
                                $const_clasificacion=$Clasificacion;                    
                                
                                        // procesos
                                        if(!$saldo){
                                            $echo.='<td>'.$SaldoInicial.'</td>';
                                            $saldo=true;

                                            if($contiene_entrada&&$Proceso=='Entradas'){

                                             $echo.= '<td class="">'.$ToneladasProceso.' </td>';
                                             $ultimo_proceso_insertad0=1;
                                             $final_anterior=$SaldoFinal;

                                            }elseif($contiene_coquizacion&&$Proceso=='Coquización'){

                                            if($contiene_entrada)$echo.= '<td class="">00.0</td>';
                                            $echo.= '<td class="">'.$ToneladasProceso.'</td>';
                                            $final_anterior=$SaldoFinal;
                                            $ultimo_proceso_insertad0=2;

                                            }else{ 

                                            if($contiene_entrada)$echo.= '<td class="">00.0 </td>';
                                            if($contiene_coquizacion)$echo.= '<td class="">00.0 </td>';
                                            $echo.= '<td class="">'.$ToneladasProceso.'</td>'; 
                                            $echo.= '<td class="">'.$SaldoFinal.'</td>';
                                            $ultimo_proceso_insertad0=3;
                                            $ultima_clasificacion_insertada++;
                                            $saldo=false;

                                            }
                                        }else{
                                            //segunda en adelante
                                            if($contiene_coquizacion&&$Proceso=='Coquización'){ 
                                                $echo.= '<td class="">'.$ToneladasProceso.'</td>'; 
                                                $final_anterior=$SaldoFinal;
                                                $ultimo_proceso_insertad0=2;
                                            }else{
                                                if($contiene_coquizacion&&$ultimo_proceso_insertad0!=2)
                                                    $echo.= '<td class="">00.0 </td>';
                                                $echo.= '<td class="">'.$ToneladasProceso.'</td>'; 
                                                $echo.= '<td class="">'.$SaldoFinal.'</td>';
                                                $ultimo_proceso_insertad0=3;
                                                $ultima_clasificacion_insertada++;
                                                $saldo=false;

                                            }                                           
                                        }
                                        break;
                            }else{

                                        for ($k=0; $k <count($array_proceso[$array_clasificacion[$i]])+2; $k++) {
                                            $echo.= '<td class="">0.00</td>';
                                        }
                                        $ultimo_proceso_insertad0=3;                                        
                            }
                }//FIN FOR 
        }//fin while

$array_sum[$clasif_anterior]['Inicial'] += $inicio_anterior;
$array_sum[$clasif_anterior]['Final'] += $fin_anterior;

// echo 'uci'.$ultima_clasificacion_insertada.'upi'.$ultimo_proceso_insertad0;

    //cerrar las columnas faltantes

//cerrar las columnas faltantes
        if($ultima_clasificacion_insertada <= count($array_proceso)-1){


            $contiene_entrada =false;
            $contiene_coquizacion = false;
            $contiene_salidas = false;

            for ($j=0; $j < count($array_proceso[$array_clasificacion[$ultima_clasificacion_insertada]]); $j++) { //cuantas col tiene
                if($array_proceso[$const_clasificacion][$j]=='Entradas')$contiene_entrada = true;
                if($array_proceso[$const_clasificacion][$j]=='Coquización')$contiene_coquizacion = true;
                if($array_proceso[$const_clasificacion][$j]=='Salidas')$contiene_salidas = true;            
            } 

            
            if($ultimo_proceso_insertad0==1&&$contiene_coquizacion&&$contiene_salidas) {
                $echo.= '<td class="">00.0</td>'; $echo.= '<td class="">00.0</td>'; $echo.= '<td class="">'.$SaldoFinal.'</td>';
            }elseif(($ultimo_proceso_insertad0==1&&$contiene_coquizacion&&!$contiene_salidas)||($ultimo_proceso_insertad0==1&&!$contiene_coquizacion&&$contiene_salidas)){
                $echo.= '<td class="">00.0</td>'; $echo.= '<td class="">'.$SaldoFinal.'</td>';
            }elseif($ultimo_proceso_insertad0==1&&!$contiene_coquizacion&&!$contiene_salidas){
                $echo.= '<td class="">'.$SaldoFinal.'</td>';
            }

            if($ultimo_proceso_insertad0==2&&$contiene_salidas) {
                $echo.= '<td class="">00.0</td>'; $echo.= '<td class="">'.$SaldoFinal.'</td>';
            }elseif($ultimo_proceso_insertad0==2&&!$contiene_salidas){
                $echo.= '<td class="">'.$SaldoFinal.'</td>';
            }


            //agregar col faltantes

            for ($i=$ultima_clasificacion_insertada+1; $i < count($array_proceso); $i++) { 

                for ($j=0; $j < count($array_proceso[$array_clasificacion[$i]])+2; $j++) { 
                    $echo.= '<td class="">00.0</td>';
                }               
            }
        }

          $echo.='<tr><td align="center" style="font-weight: bolder;">TOTAL</td>';

           for ($i=0; $i < count($array_sum); $i++) { 
            
             $echo.='<td style="font-weight: bolder;">'.$array_sum[$array_clasificacion[$i]]['Inicial'].'</td>';
             if(isset($array_sum[$array_clasificacion[$i]]['Entradas']))
             $echo.='<td style="font-weight: bolder;">'.number_format($array_sum[$array_clasificacion[$i]]['Entradas'],2).'</td>';
             if(isset($array_sum[$array_clasificacion[$i]]['Coquización']))
             $echo.='<td style="font-weight: bolder;">'.number_format($array_sum[$array_clasificacion[$i]]['Coquización'],2).'</td>';
             if(isset($array_sum[$array_clasificacion[$i]]['Salidas']))
             $echo.='<td style="font-weight: bolder;">'.number_format($array_sum[$array_clasificacion[$i]]['Salidas'],2).'</td>';
             $echo.='<td style="font-weight: bolder;">'.$array_sum[$array_clasificacion[$i]]['Final'].'</td>';
           }

          $echo.='</tr>';

           $echo.='</table></div></div>';
        echo $echo;
        // echo '<pre>';
        // echo print_r($array_proceso);
        // echo '</pre>';
        
        // echo '<pre>';
        // echo print_r($array_sum);
        // echo '</pre>';
// }//fin if filas
}elseif($_POST['band']==16){ //MUESTRA CONSUMOS DE MAQUILA
  $txt_array_empresa = implode("','", $_SESSION['Array_empresa']['CONSULTAS']);
  $echo = '';
  $echo.='<div class="row">
          <div class="col-sm-1"></div>
          <div class="col-xs-6 col-sm-2">
              <label>Empresa</label>
              <select class="form-control" id="empresa">';
              $sql_empresa = "SELECT Empresa,idEmpresa FROM vInventarioSaldo 
                  WHERE idEmpresa IN ('$txt_array_empresa')
                  GROUP BY Empresa,idEmpresa ORDER BY Empresa";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idEmpresa'].'">'.utf8_encode($aa['Empresa']).'</option>';
                  }
              }
              $echo.='</select>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2">
              <label>Unidad de negocio</label>
              <select class="form-control" id="idUnidadNegocio">
                  <option value="0" disabled selected>Seleccione</option>';
              $sql_empresa = "SELECT * FROM UnidadDeNegocio ORDER BY Descripcion";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idUnidadNegocio'].'">'.utf8_encode($aa['Descripcion']).'</option>';
                  }
              }
      $echo.='</select>
      </div>
          <div class="col-xs-6 col-sm-2">
              <label>Destino</label>
              <select class="form-control" id="centro_trabajo">
                  <option value="00000000-0000-0000-0000-000000000000">TODOS</option>';
              $sql_empresa = "SELECT Destino,idDestino FROM vInventarioSaldo 
                  WHERE idEmpresa IN ('$txt_array_empresa')
                  GROUP BY Destino,idDestino ORDER BY Destino";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idDestino'].'">'.utf8_encode($aa['Destino']).'</option>';
                  }
              }
              $echo.='</select>
          </div>';
    $echo.='<div class="col-xs-6 col-sm-1">
              <label>Año</label>
              <select class="form-control" id="anno_inv" onchange="change_anno()">
                  <option value="0" disabled selected>Seleccione</option>';
                  for ($i=(date('Y')-1); $i <= date('Y'); $i++) { 
                    $echo.='<option value="'.$i.'">'.$i.'</option>';
                  }
              $echo.='</select>
            </div>';
    $echo.='<div class="col-xs-6 col-sm-1">
              <label>Semana Despacho</label>
              <select class="form-control" id="semana_inv">
                  <option value="-1" disabled selected>Seleccione año</option>';
              $echo.='</select>
            </div>';
    $echo.='<div class="col-xs-6 col-sm-1">
              <label>Semana Llegada</label>
              <select class="form-control" id="semana_inv_llegada">
                  <option value="-1" disabled selected>Seleccione año</option>';
              $echo.='</select>
            </div>';
  $echo.='<div class="col-xs-6 col-sm-1">
              <button class="btn btn-primary" id="btn_search" onclick="search_transitos()" style="margin-top: 25px;"><span class="glyphicon glyphicon-refresh"></span></button>
              <button class="btn btn-success" id="btn_excel" onclick="generar_excel()" style="margin-top: 25px;"><span class="glyphicon glyphicon-export"></span></button>
          </div>
          <div class="col-xs-6 col-sm-2">
            <form method="POST" id="form_excel">
              <input type="hidden" id="tabla_excel" name="tabla_excel">
            </form>
          </div>
        </div>
      <div class="row">
          <br>
          <div class="col-sm-12" id="div_inventario_body"></div>
      </div>
    <div class="modal fade" id="modalDetailsTransito" tabindex="0" role="dialog" style="z-index: 5000;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="tittle_modal_creacion"></h4>
                </div>
                <div id="div_menu_modal_creacion"></div>
            </div>
    </div>';
  echo $echo;
}elseif($_POST['band']==17){
  $idEmpresa = $_POST['idEmpresa'];
  $idUnidadNegocio = $_POST['idUnidadNegocio'];
  $idDestino = $_POST['idDestino'];
  $anno = $_POST['anno'];
  $semana = explode("-",$_POST['semana']);
  $txt_semana_d="";
  $semana_llegada = explode("-",$_POST['semana_llegada']);
  $txt_semana_r="";
  if($semana<>0){
    $txt_semana_d="dbo.Get_semana_weekly(CAST(Movimiento.FechaRegistro AS date))='$semana[1]'";
    $txt_semana=$txt_semana_d;
  }
  if($semana_llegada[1]<>0){
    $txt_semana_r="dbo.Get_semana_weekly(CAST(MovimientoOperacionDestinoFinal.FEchaSalidaPatio AS date))='$semana_llegada[1]'"; 
    $txt_semana=$txt_semana_r;
  }
  if($txt_semana_d<>"" && $txt_semana_r<>""){
    $txt_semana=$txt_semana_d." OR ".$txt_semana_r;
  }

  $echo = '<div class="table-responsive1">';
  $sql = "SELECT YEAR(Movimiento.FechaRegistro) AS Anno,dbo.Get_semana_weekly(CAST(Movimiento.FechaRegistro AS date)) AS Semana,ISNULL(dbo.Get_semana_weekly(CAST(MovimientoOperacionDestinoFinal.FEchaSalidaPatio AS date)),99) AS SemanaLlegada,
  Departamentos.Descripcion AS Departamento,UnidadDeNegocio.Descripcion AS UnidadDeNegocio,Clasificacion.Descripcion AS Clasificacion,Destino_2.Descripcion AS DespachadoDesde,Destino.Descripcion AS RecepcionadoEn,
  SUM(Toneladas) AS Toneladas,SUM(ToneladasRecepcion) AS ToneladasRecepcion, 
  (CASE WHEN ((dbo.Get_semana_weekly(Movimiento.FechaRegistro)=dbo.Get_semana_weekly(MovimientoOperacionDestinoFinal.FEchaSalidaPatio)) OR ISNULL(dbo.Get_semana_weekly(MovimientoOperacionDestinoFinal.FEchaSalidaPatio),0)='$semana_llegada[1]') THEN SUM(Movimiento.Toneladas-Movimiento.ToneladasRecepcion) ELSE 0 END) AS Mermas, 
  (CASE WHEN ((dbo.Get_semana_weekly(Movimiento.FechaRegistro)<>ISNULL(dbo.Get_semana_weekly(MovimientoOperacionDestinoFinal.FEchaSalidaPatio),0)) AND ISNULL(dbo.Get_semana_weekly(MovimientoOperacionDestinoFinal.FEchaSalidaPatio),0)<>'$semana_llegada[1]') THEN SUM(Movimiento.Toneladas) ELSE 0 END) AS Transito
FROM Movimiento
  INNER JOIN Destino ON Movimiento.idDestino=Destino.idDestino AND Destino.idClase='0DC1FDD5-B80E-4BC8-B6E4-7853C8C80C6D'
  INNER JOIN Destino AS Destino_2 ON Movimiento.idDestinoAcopio=Destino_2.idDestino
  INNER JOIN Departamentos ON Destino_2.idDepartamento=Departamentos.idDepartamento
  LEFT JOIN MovimientoOperacionDestinoFinal ON Movimiento.NumeroTransaccion=MovimientoOperacionDestinoFinal.numerotransaccion
  INNER JOIN Clasificacion ON Movimiento.idClasificacion=Clasificacion.idClasificacion
  INNER JOIN UnidadDeNegocio ON Clasificacion.UnidadDeNegocio=UnidadDeNegocio.idUnidadNegocio
WHERE YEAR(FechaRegistro)='$anno' AND idEmpresa='$idEmpresa' AND Clasificacion.UnidadDeNegocio='$idUnidadNegocio'
  AND ( $txt_semana )
GROUP BY YEAR(Movimiento.FechaRegistro),dbo.Get_semana_weekly(Movimiento.FechaRegistro),dbo.Get_semana_weekly(MovimientoOperacionDestinoFinal.FEchaSalidaPatio),Departamentos.Descripcion,UnidadDeNegocio.Descripcion,
  Clasificacion.Descripcion,Destino_2.Descripcion,Destino.Descripcion
ORDER BY YEAR(Movimiento.FechaRegistro),Departamentos.Descripcion,UnidadDeNegocio.Descripcion,Clasificacion.Descripcion,Destino_2.Descripcion,Destino.Descripcion,dbo.Get_semana_weekly(Movimiento.FechaRegistro),ISNULL(dbo.Get_semana_weekly(CAST(MovimientoOperacionDestinoFinal.FEchaSalidaPatio AS date)),99)";
  $resul=sqlsrv_query($conn,$sql,$params,$options);
  $rows = sqlsrv_num_rows($resul);
  if($rows>0){
    $echo.='<br><table class="table table-bordered table-condensed" id="table_inventory_detail">
            <thead>
            <th align="center">Año</th>
            <th align="center">Semana</th>
            <th align="center">Semana Llegada</th>
            <th align="center">Departamento</th>
            <th align="center">UnidadDeNegocio</th>
            <th align="center">Clasificación</th>
            <th align="center">Despachado Desde</th>
            <th align="center">Recepcionado En</th>
            <th align="center">Tm</th>
            <th align="center">Tm Recepcionadas</th>
            <th align="center">Mermas</th>
            <th align="center">Transito</th>
            </thead>
            <tbody>';
    while($aa=sqlsrv_fetch_array($resul)){
      $data = $aa['Anno'].'||'.$aa['Semana'].'||'.
              $aa['SemanaLlegada'].'||'.utf8_encode($aa['Clasificacion']).'||'.
              utf8_encode($aa['DespachadoDesde']).'||'.utf8_encode($aa['RecepcionadoEn']);

      $echo.='<tr>';
      $echo.='<td>'.$aa['Anno'].'</td>';
      $echo.='<td>'.$aa['Semana'].'</td>';
      if($aa['SemanaLlegada']==99){
        $SemanaLlegada='---';
      }else{
        $SemanaLlegada=$aa['SemanaLlegada'];
      }
      $echo.='<td>'.$SemanaLlegada.'</td>';
      $echo.='<td>'.utf8_encode($aa['Departamento']).'</td>';
      $echo.='<td>'.utf8_encode($aa['UnidadDeNegocio']).'</td>';
      $echo.='<td>'.utf8_encode($aa['Clasificacion']).'</td>';
      $echo.='<td>'.utf8_encode($aa['DespachadoDesde']).'</td>';
      $echo.='<td>'.utf8_encode($aa['RecepcionadoEn']).'</td>';
      $echo.='<td><a onclick="search_transitos_details(\''.$data.'\')">'.number_format($aa['Toneladas'],2).'</a></td>';
      $echo.='<td>'.number_format($aa['ToneladasRecepcion'],2).'</td>';
      $echo.='<td>'.number_format($aa['Mermas'],2).'</td>';
      $echo.='<td>'.number_format($aa['Transito'],2).'</td>';
      $echo.='</tr>';
    }
  }
  $echo.='</div>';
  echo $echo;
}elseif($_POST['band']==18){
  $data = explode("||",$_POST['data']);
  if($data[2]<>99)
    $SemanaWeeklyLlegada=$data[2];
  else
    $SemanaWeeklyLlegada=0; 
  $echo = '<div class="table-responsive1">';
  $sql="SELECT FechaRegistro,SemanaWeekly,TiqueteEmpresa,Toneladas,SalidaDestino,SemanaWeeklyllegada,ToneladasRecepcion,DespachadoDesde,Transportador,Placa_a,Conductor,Proveedor,Origen,Clasificacion,RecepcionadoEn,Lote,Transaccion FROM tz_MovimientoTransporte WHERE (YEAR(FechaRegistro)='$data[0]' OR YEAR(SalidaDestino)='$data[0]') AND (SemanaWeekly='$data[1]' AND SemanaWeeklyllegada='$SemanaWeeklyLlegada') AND Clasificacion='$data[3]' AND DespachadoDesde='$data[4]' AND RecepcionadoEn='$data[5]'";
  $resul=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
  $rows = sqlsrv_num_rows($resul);
  if($rows>0){
    $echo.='<br><table class="table table-bordered table-condensed" id="table_inventory_detail">
            <thead>
            <th align="center">Fecha</th>
            <th align="center">Semana</th>
            <th align="center">Tiquete Empresa</th>
            <th align="center">TM</th>
            <th align="center">Fecha Llegada</th>
            <th align="center">Semana Llegada</th>
            <th align="center">TM Recepcionado</th>
            <th align="center">Despachado Desde</th>
            <th align="center">Transportador</th>
            <th align="center">Placa</th>
            <th align="center">Conductor</th>
            <th align="center">Proveedor</th>
            <th align="center">Origen</th>
            <th align="center">Clasificación</th>
            <th align="center">RecepcionadoEn</th>
            <th align="center">Lote</th>
            <th align="center">Transacción</th>
            </thead>
            <tbody>';
    while($aa=sqlsrv_fetch_array($resul)){
        $echo.='<tr>';
        $echo.='<td>'.date_format($aa['FechaRegistro'],'Y-m-d').'</td>';
        $echo.='<td>'.$aa['SemanaWeekly'].'</td>';
        $echo.='<td>'.$aa['TiqueteEmpresa'].'</td>';
        $echo.='<td>'.number_format($aa['Toneladas'],2).'</td>';
        $echo.='<td>'.date_format($aa['SalidaDestino'],'Y-m-d').'</td>';
        $echo.='<td>'.$aa['SemanaWeeklyllegada'].'</td>';
        $echo.='<td>'.number_format($aa['ToneladasRecepcion'],2).'</td>';
        $echo.='<td>'.utf8_encode($aa['DespachadoDesde']).'</td>';
        $echo.='<td>'.utf8_encode($aa['Transportador']).'</td>';
        $echo.='<td>'.utf8_encode($aa['Placa_a']).'</td>';
        $echo.='<td>'.utf8_encode($aa['Conductor']).'</td>';
        $echo.='<td>'.utf8_encode($aa['Proveedor']).'</td>';
        $echo.='<td>'.utf8_encode($aa['Origen']).'</td>';
        $echo.='<td>'.utf8_encode($aa['Clasificacion']).'</td>';
        $echo.='<td>'.utf8_encode($aa['RecepcionadoEn']).'</td>';
        $echo.='<td>'.utf8_encode($aa['Lote']).'</td>';
        $echo.='<td>'.utf8_encode($aa['Transaccion']).'</td>';
        $echo.='</tr>';
    }
  }
  echo $echo;
}elseif($_POST['band']==19){
  $echo = '';
  $echo.='<div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
          <div class="row">
            <div class="col-sm-4">
              <label>Empresa</label>
              <select class="form-control" id="empresa">
                <option value="-1">Seleccione</option>';
                $sql_empresa = "SELECT Alias,idProveedor FROM Proveedores WHERE Empresa=1
                  GROUP BY Alias,idProveedor ORDER BY Alias";
                $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
                $rows = sqlsrv_num_rows($resul);
                if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                    $idProveedor=($aa['idProveedor']);
                    $Alias=utf8_encode($aa['Alias']);
                    $echo.='<option value="'.$idProveedor.'">'.$Alias.'</option>';
                  }
                }
              $echo.='</select>
            </div>
            <div class="col-sm-3">
              <label>Fecha Inicio</label>
              <input type="date" class="form-control" id="fecha_ini">
            </div>
            <div class="col-sm-3">
              <label>Fecha Fin</label>
              <input type="date" class="form-control" id="fecha_fin">
            </div>
            <div class="col-sm-2">
              <button type="button" id="btn_search_transito" onclick="search_transito_acopio()" class="btn btn-success">Buscar <span class="glyphicon glyphicon-search"></span></button>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
        </div>
      </div>';
  echo $echo;
}elseif($_POST['band']==20){
  $empresa=($_POST['empresa']);
  $fecha_ini=$_POST['fecha_ini'];
  $fecha_fin=$_POST['fecha_fin'];
  $echo='{"Despachos":{"records": [';
  $sqlD="SELECT DespachadoDesde,RecepcionadoEn,Transaccion,SUM(Toneladas) AS Toneladas,COUNT(TiqueteEmpresa) AS Cantidad
  FROM tz_MovimientoTransporte 
  WHERE FechaRegistro BETWEEN '$fecha_ini' AND '$fecha_fin' AND idEmpresa='$empresa' AND TipoMovimiento='Traslado'
  GROUP BY DespachadoDesde,RecepcionadoEn,Transaccion
  ORDER BY DespachadoDesde,RecepcionadoEn,Transaccion";
  $resulD=sqlsrv_query($conn,utf8_decode($sqlD),$params,$options);
  while($aa=sqlsrv_fetch_array($resulD)){
    $DespachadoDesde=utf8_encode($aa['DespachadoDesde']);
    $RecepcionadoEn=utf8_encode($aa['RecepcionadoEn']);
    $Transaccion=utf8_encode($aa['Transaccion']);
    $Toneladas=number_format($aa['Toneladas'],2);
    $Cantidad=number_format($aa['Cantidad']);
    $echo.='{"lDespachadoDesde":"'.$DespachadoDesde.'","lRecepcionadoEn":"'.$RecepcionadoEn.'","lTransaccion":"'.$Transaccion.'","lToneladas":"'.$Toneladas.'","lViajes":"'.$Cantidad.'"},';
  }
  $echo = substr($echo, 0, strlen($echo) - 1);
  $echo.=']},"Recepcion":{"records": [';

  $sqlR="SELECT RecepcionadoEn,Origen,Proveedor,Transaccion,SUM(Toneladas) AS Toneladas,COUNT(TiqueteEmpresa) AS Cantidad
  FROM tz_MovimientoTransporte  
  WHERE FechaRegistro BETWEEN '$fecha_ini' AND '$fecha_fin' AND idEmpresa='$empresa' AND TipoMovimiento='Recepción'
  GROUP BY RecepcionadoEn,Origen,Proveedor,Transaccion
  ORDER BY RecepcionadoEn,Origen,Proveedor";
  $resulR=sqlsrv_query($conn,utf8_decode($sqlR),$params,$options);
  while($aa=sqlsrv_fetch_array($resulR)){
    $RecepcionadoEn=utf8_encode($aa['RecepcionadoEn']);
    $Origen=utf8_encode($aa['Origen']);
    $Proveedor=utf8_encode($aa['Proveedor']);
    $Transaccion=utf8_encode($aa['Transaccion']);
    $Toneladas=number_format($aa['Toneladas'],2);
    $Cantidad=number_format($aa['Cantidad']);
    $echo.='{"lRecepcionadoEn":"'.$RecepcionadoEn.'","lOrigen":"'.$Origen.'","lProveedor":"'.$Proveedor.'","lTransaccion":"'.$Transaccion.'","lToneladas":"'.$Toneladas.'","lViajes":"'.$Cantidad.'"},';
  }
  $echo = substr($echo, 0, strlen($echo) - 1);
  $echo.=']}}';
  echo $echo;
}elseif($_POST['band']==21){

  //inicio 21

  $txt_array_empresa = implode("','", $_SESSION['Array_empresa']['CONSULTAS']);
  $echo = '';
  $echo.='<div class="row">
          <div class="col-xs-6 col-sm-1"></div>
          <div class="col-xs-6 col-sm-2">
              <label>Empresa</label>
              <select class="form-control" id="empresa">';
              $sql_empresa = "SELECT Empresa,idEmpresa FROM vInventarioSaldo 
                  WHERE idEmpresa IN ('$txt_array_empresa')
                  GROUP BY Empresa,idEmpresa ORDER BY Empresa";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idEmpresa'].'">'.utf8_encode($aa['Empresa']).'</option>';
                  }
              }
              $echo.='</select>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2">
              <label>Patio</label>
              <select class="form-control" id="patio">
                  <option value="00000000-0000-0000-0000-000000000000">TODOS</option>';
              $sql_empresa = "SELECT Destino,idDestino FROM vInventarioSaldo 
                  WHERE idEmpresa IN ('$txt_array_empresa')
                  GROUP BY Destino,idDestino ORDER BY Destino";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idDestino'].'">'.utf8_encode($aa['Destino']).'</option>';
                  }
              }
              $echo.='</select>
      </div>          
          <div class="col-xs-6 col-sm-2">
              <label>Fecha Inicio</label>
              <input type="date" id="fecha_ini" class="form-control" max="'.date('Y-m-d').'" value="'.date('Y-m-d').'">
          </div>
          <div class="col-xs-6 col-sm-2">
              <label>Fecha Fin</label>
              <input type="date" id="fecha_fin" class="form-control" max="'.date('Y-m-d').'" value="'.date('Y-m-d').'">
          </div>
          <div class="col-xs-6 col-sm-1">
              <button class="btn btn-primary" id="btn_consultar_movxpila" onclick="reporte_movimiento_pila(1)" style="margin-top: 25px;"><span class="glyphicon glyphicon-refresh"></span></button>
              <button class="btn btn-success" id="btn_excel" onclick="generar_excel()" style="margin-top: 25px;"><span class="glyphicon glyphicon-export"></span></button>
          </div>
          <form method="POST" id="form_excel">
            <input type="hidden" id="tabla_excel" name="tabla_excel">
          </form>
      </div>
      <div class="row">
          <br>
          <div class="col-sm-12" id="div_movimientos_body"></div>
      </div>';
  echo $echo;
  //fin 21
}elseif($_POST['band']==22){

      $idEmpresa = $_POST['idEmpresa'];
      $idpatio = $_POST['idPatio']; 
      $FechaInicioSaldo = $_POST['FechaInicioSaldo'];
      $FechaFinSaldo = $_POST['FechaFinSaldo'];
      $echo = '';


      $echo.='<br><table class="table table-bordered table-condensed" id="table_inventory_detail">
            <thead>
            <th>Empresa</th>
            <th>Destino</th>
            <th>Clasificación</th>
            <th>Fecha Saldo</th>
            <th>Saldo Inicial</th>
            <th>Proceso</th>
            <th>Detalle</th>
            <th>Pila</th>
            <th>Toneladas</th>
            <th>Fecha Final</th>
            <th>Saldo Final</th>
            </thead>
            <tbody>';


      $sql=" SELECT Empresa,Destino,Clasificacion,FechaSaldo,SaldoInicial,Proceso,TransaccionDetalle,Pila,ToneladasProceso,FechaFinSaldo,SaldoFinal 
             FROM Get_SaldoInventarioPILA('$idEmpresa','$idpatio','00000000-0000-0000-0000-000000000000','00000000-0000-0000-0000-000000000000','$FechaInicioSaldo','$FechaFinSaldo')
             ORDER BY Empresa,Destino,Clasificacion,Pila,Proceso,TransaccionDetalle ";

      $resul=sqlsrv_query($conn,$sql,$params,$options);      
      while($aa = sqlsrv_fetch_array($resul)){

      $Empresa=utf8_encode($aa['Empresa']);
      $Destino=utf8_encode($aa['Destino']);
      $Clasificacion=utf8_encode($aa['Clasificacion']);
      $FechaSaldo=date_format($aa['FechaSaldo'],'Y-m-d');
      $SaldoInicial=$aa['SaldoInicial'];
      $Proceso=utf8_encode($aa['Proceso']);
      $TransaccionDetalle=utf8_encode($aa['TransaccionDetalle']);
      $Pila=$aa['Pila'];
      $ToneladasProceso=number_format($aa['ToneladasProceso'],2);
      $FechaFinSaldo=date_format($aa['FechaFinSaldo'],'Y-m-d');
      $SaldoFinal=number_format($aa['SaldoFinal'],2);

      $echo.='<tr>';
      $echo.='<td>'.$Empresa.'</td>';
      $echo.='<td>'.$Destino.'</td>';
      $echo.='<td>'.$Clasificacion.'</td>';
      $echo.='<td>'.$FechaSaldo.'</td>';
      $echo.='<td>'.$SaldoInicial.'</td>';
      $echo.='<td>'.$Proceso.'</td>';
      $echo.='<td>'.$TransaccionDetalle.'</td>';
      $echo.='<td>'.$Pila.'</td>';
      $echo.='<td>'.$ToneladasProceso.'</td>';
      $echo.='<td>'.$FechaFinSaldo.'</td>';
      $echo.='<td>'.$SaldoFinal.'</td>';
      $echo.='</tr>';
    }

 
    $echo.='</table></div>';
    echo $echo;
  }elseif($_POST['band']==23){
    $txt_array_empresa = implode("','", $_SESSION['Array_empresa']['CONSULTAS']);
  $echo = '';
  $echo.='<div class="row" style="background-color: gray; border-radius:5px;">
          <div class="col-xs-6 col-sm-1"></div>
          <div class="col-xs-6 col-sm-2">
              <label>Empresa</label>
              <select class="form-control" id="empresa">';
              $sql_empresa = "SELECT Empresa,idEmpresa FROM vInventarioSaldo 
                  WHERE idEmpresa IN ('$txt_array_empresa')
                  GROUP BY Empresa,idEmpresa ORDER BY Empresa";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idEmpresa'].'">'.utf8_encode($aa['Empresa']).'</option>';
                  }
              }
              $echo.='</select>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2">
              <label>Unidad de negocio</label>
              <select class="form-control" id="idUnidadNegocio">
                  <option value="0">VALORES NULOS</option>';
              $sql_empresa = "SELECT * FROM UnidadDeNegocio ORDER BY Descripcion";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idUnidadNegocio'].'">'.utf8_encode($aa['Descripcion']).'</option>';
                  }
              }
      $echo.='</select>
      </div>
          <div class="col-xs-6 col-sm-2">
              <label>Destino</label>
              <select class="form-control" id="centro_trabajo">
                  <option value="00000000-0000-0000-0000-000000000000">TODOS</option>';
              $sql_empresa = "SELECT Destino,idDestino FROM vInventarioSaldo 
                  WHERE idEmpresa IN ('$txt_array_empresa')
                  GROUP BY Destino,idDestino ORDER BY Destino";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idDestino'].'">'.utf8_encode($aa['Destino']).'</option>';
                  }
              }
              $echo.='</select>
          </div>
          <!--<div class="col-xs-6 col-sm-2">
              <label>Fecha Inicio</label>
              <input type="date" id="fecha_ini" class="form-control" max="'.date('Y-m-d').'" value="'.date('Y-m-d').'">
          </div>-->
          <div class="col-xs-6 col-sm-2">
              <label>Fecha corte</label>
              <input type="date" id="fecha_fin" class="form-control" max="'.date('Y-m-d').'" value="'.date('Y-m-d').'">
          </div>
          <div class="col-xs-6 col-sm-1">
              <button class="btn btn-primary" id="btn_actualizar_inv" onclick="load_body_inventory(3)" style="margin-top: 25px;"><span class="glyphicon glyphicon-refresh"></span></button>
              <button class="btn btn-success" id="btn_excel" onclick="generar_excel()" style="margin-top: 25px;"><span class="glyphicon glyphicon-export"></span></button>
          </div>
          <form method="POST" id="form_excel">
            <input type="hidden" id="tabla_excel" name="tabla_excel">
          </form>
      </div>
      <div class="row">
          <br>
          <div class="col-sm-12" id="div_inventario_body"></div>
      </div>';
  echo $echo;
}elseif($_POST['band']==24){
    $idEmpresa = $_POST['idEmpresa'];
    $idUnidadNegocio = $_POST['idUnidadNegocio'];
    //$txt_UnidadDeNegocio = "";
    if($idUnidadNegocio!='0'){
        $txt_UnidadDeNegocio=" WHERE a.idUnidadNegocio='$idUnidadNegocio'";
    }else{
      $txt_UnidadDeNegocio=" WHERE a.idUnidadNegocio IS NULL";
    }
    $idDestino = $_POST['idDestino'];
    //$FechaInicioSaldo = $_POST['FechaInicioSaldo'];
    $FechaFinSaldo = $_POST['FechaFinSaldo'];
    $echo = '<div class="table-responsive1">';
    $sql_group = "SELECT Clasificacion
FROM (
  SELECT a.Empresa,a.Destino,UnidadDeNegocio.Descripcion AS UnidadDeNegocio,a.Clasificacion,a.Pila,a.SaldoFinal
  FROM dbo.Get_SaldoInventarioPILA('$idEmpresa','$idDestino','00000000-0000-0000-0000-000000000000','00000000-0000-0000-0000-000000000000','$FechaFinSaldo','$FechaFinSaldo') AS a 
  LEFT JOIN UnidadDeNegocio ON a.idUnidadNegocio=UnidadDeNegocio.idUnidadNegocio $txt_UnidadDeNegocio 
  GROUP BY a.Empresa,a.Destino,UnidadDeNegocio.Descripcion,a.Clasificacion,a.Pila,a.SaldoFinal
  ) AS tabl
GROUP BY Clasificacion
ORDER BY Clasificacion";
    $resul_group=sqlsrv_query($conn,$sql_group,$params,$options);
    $rows_group=sqlsrv_num_rows($resul_group);
    if($rows_group>0){
      $echo.='<br><table class="table table-bordered table-condensed" id="table_inventory_detail">
              <thead>
              <th>Centro Trabajo</th>';
      $const_clasificacion='';
      $position_clasificacion=0;
      while($aa = sqlsrv_fetch_array($resul_group)){
        $clasificacion = utf8_encode($aa['Clasificacion']);
        //$saldoFinal = $aa['SaldoFinal'];
        $echo.='<th>'.$clasificacion.'</th>';
        //$array_totales_columns[$position_clasificacion]=$saldoFinal;
          

          $const_clasificacion=$clasificacion;
          $array_clasificacion[$position_clasificacion]=$const_clasificacion;
          $position_clasificacion++;
      }
      $echo.='</thead>
              <tbody>';
      $const_destino='';
      $position_clasificacion=0;

      $sql="SELECT Empresa,Destino,UnidadDeNegocio,Clasificacion,ISNULL(SUM(SaldoFinal),0) AS SaldoFinal/*,Pila*/
FROM (
  SELECT a.Empresa,a.Destino,UnidadDeNegocio.Descripcion AS UnidadDeNegocio,a.Clasificacion,a.Pila,a.SaldoFinal
  FROM dbo.Get_SaldoInventarioPILA('$idEmpresa','$idDestino','00000000-0000-0000-0000-000000000000','00000000-0000-0000-0000-000000000000','$FechaFinSaldo','$FechaFinSaldo') AS a 
  LEFT JOIN UnidadDeNegocio ON a.idUnidadNegocio=UnidadDeNegocio.idUnidadNegocio $txt_UnidadDeNegocio 
  GROUP BY a.Empresa,a.Destino,UnidadDeNegocio.Descripcion,a.Clasificacion,a.Pila,a.SaldoFinal
  ) AS tabl
GROUP BY Empresa,Destino,UnidadDeNegocio,Clasificacion
ORDER BY Empresa,Destino,UnidadDeNegocio,Clasificacion/*,Pila*/";
      $resul=sqlsrv_query($conn,$sql,$params,$options);
      while($bb = sqlsrv_fetch_array($resul)){
        $destino = utf8_encode($bb['Destino']);
        $clasificacion = utf8_encode($bb['Clasificacion']);
        $saldoFinal = number_format($bb['SaldoFinal'],2);
        //SUMA ARRAY_TOTALES
        if(isset($array_totales_columns[$clasificacion])){
          $array_totales_columns[$clasificacion]+=$bb['SaldoFinal'];
        }else{
          $array_totales_columns[$clasificacion]=$bb['SaldoFinal'];
        }
        if($const_destino==''){
          $const_destino=$destino;
          $echo.='<tr>';
          $echo.='<td>'.$const_destino.'</td>';
          for ($i=$position_clasificacion; $i < count($array_clasificacion); $i++){ 
            if($array_clasificacion[$i]==$clasificacion){
              $echo.='<td>'.$saldoFinal.'</td>';
              $position_clasificacion=$i+1;
              break;
            }else{
              $echo.='<td>0</td>';
            }
          }
        }elseif($const_destino==$destino){
          for ($i=$position_clasificacion; $i < count($array_clasificacion); $i++){ 
            if($array_clasificacion[$i]==$clasificacion){
              $echo.='<td>'.$saldoFinal.'</td>';
              $position_clasificacion=$i+1;
              break;
            }else{
              $echo.='<td>0</td>';
            }
          }
        }elseif($const_destino<>$destino){
          for ($i=$position_clasificacion; $i <count($array_clasificacion); $i++){ 
              $echo.='<td>0</td>';
          }
          $echo.='</tr>';
          $const_destino=$destino;
          $position_clasificacion=0;
          $echo.='<tr>';
          $echo.='<td>'.$const_destino.'</td>';
          for ($i=$position_clasificacion; $i < count($array_clasificacion); $i++){ 
            if($array_clasificacion[$i]==$clasificacion){
              $echo.='<td>'.$saldoFinal.'</td>';
              $position_clasificacion=$i+1;
              break;
            }else{
              $echo.='<td>0</td>';
            }
          }
        }
      }
      for ($i=$position_clasificacion; $i <count($array_clasificacion); $i++){ 
        $echo.='<td>0</td>';
      }
      $echo.='</tr><tr><td><b>TOTALES</b></td>';
      for ($i=0; $i <count($array_clasificacion); $i++){
        $echo.='<td><b>'.number_format($array_totales_columns[$array_clasificacion[$i]],2).'</b></td>';
      }
      $echo.='</tr></tbody>
          </table>';
      /*echo '<pre>';
      print_r($array_clasificacion);
      echo '</pre>';*/
      $echo.='</table>
      </div>';
      echo $echo;
    }
}elseif($_POST['band']==25){
  $txt_array_empresa = implode("','", $_SESSION['Array_empresa']['CONSULTAS']);
  $echo = '<center><h1>Inventarios v2 (pilas)</h1></center><br>';
  $echo.='<div class="row">
          <div class="col-sm-1"></div>
          <div class="col-xs-6 col-sm-2">
              <label>Empresa</label>
              <select class="form-control" id="empresa">';
              $sql_empresa = "SELECT Empresa,idEmpresa FROM vInventarioSaldo 
                  WHERE idEmpresa IN ('$txt_array_empresa')
                  GROUP BY Empresa,idEmpresa ORDER BY Empresa";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idEmpresa'].'">'.utf8_encode($aa['Empresa']).'</option>';
                  }
              }
              $echo.='</select>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2">
              <label>Unidad de negocio</label>
              <select class="form-control" id="idUnidadNegocio">
                  <option value="0" disabled selected>Seleccione</option>';
              $sql_empresa = "SELECT * FROM UnidadDeNegocio ORDER BY Descripcion";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idUnidadNegocio'].'">'.utf8_encode($aa['Descripcion']).'</option>';
                  }
              }
      $echo.='</select>
      </div>
          <div class="col-xs-6 col-sm-2">
              <label>Destino</label>
              <select class="form-control" id="centro_trabajo">
                  <option value="00000000-0000-0000-0000-000000000000">TODOS</option>';
              $sql_empresa = "SELECT Destino,idDestino FROM vInventarioSaldo 
                  WHERE idEmpresa IN ('$txt_array_empresa')
                  GROUP BY Destino,idDestino ORDER BY Destino";
              $resul=sqlsrv_query($conn,$sql_empresa,$params,$options);
              $rows = sqlsrv_num_rows($resul);
              if($rows>0){
                  while($aa = sqlsrv_fetch_array($resul)){
                      $echo.='<option value="'.$aa['idDestino'].'">'.utf8_encode($aa['Destino']).'</option>';
                  }
              }
              $echo.='</select>
          </div>';
          $echo.='<div class="col-xs-6 col-sm-2">
              <label>Fecha Desde</label>
              <input class="form-control" type="date" id="fecha_desde">
              </div>';
          $echo.='<div class="col-xs-6 col-sm-2">
              <label>Fecha Hasta</label>
              <input class="form-control" type="date" id="fecha_hasta">
              </div>';
    /*$echo.='<div class="col-xs-6 col-sm-1">
              <label>Año</label>
              <select class="form-control" id="anno_inv" onchange="change_anno()">
                  <option value="0" disabled selected>Seleccione</option>';
                  for ($i=(date('Y')-1); $i <= date('Y'); $i++) { 
                    $echo.='<option value="'.$i.'">'.$i.'</option>';
                  }
              $echo.='</select>
            </div>';
    $echo.='<div class="col-xs-6 col-sm-1">
              <label>Mes-Semana</label>
              <select class="form-control" id="semana_inv">
                  <option value="-1" disabled selected>Seleccione año</option>';
              $echo.='</select>
            </div>';*/
  $echo.='<div class="col-xs-6 col-sm-1">
              <button class="btn btn-primary" id="btn_actualizar_inv" onclick="load_body_inventory_pila()" style="margin-top: 25px;"><span class="glyphicon glyphicon-refresh"></span></button>
              <button class="btn btn-success" id="btn_excel" onclick="generar_excel()" style="margin-top: 25px;"><span class="glyphicon glyphicon-export"></span></button>
          </div>
          <div class="col-xs-6 col-sm-2">
            <form method="POST" id="form_excel">
              <input type="hidden" id="tabla_excel" name="tabla_excel">
            </form>
          </div>
        </div>
      <div class="row">
          <br>
          <div class="col-sm-12" id="div_inventario_body"></div>
      </div>';
  echo $echo;
}elseif($_POST['band']==26){
  $idEmpresa = $_POST['idEmpresa'];
  $idUnidadNegocio = $_POST['idUnidadNegocio'];
  $idDestino = $_POST['idDestino'];
  $FechaInicio = $_POST['fecha_desde'];
  $FechaFin = $_POST['fecha_hasta'];
  $echo = '<div class="table-responsive1">';

  $sql_clasif = "SELECT Empresa,Destino,UnidadDeNegocio,Clasificacion,ROUND(SUM(SaldoInicial),2) AS SaldoInicial,ROUND(SUM(SaldoFinal),2) AS SaldoFinal,Position FROM
(
SELECT Empresa,Destino,UnidadDeNegocio,Clasificacion,Pila,FechaSaldo,SaldoInicial,FechaFinSaldo,SaldoFinal,b.Position
  FROM Get_SaldoInventarioPILA_detailReport('$idEmpresa','$idDestino','00000000-0000-0000-0000-000000000000','00000000-0000-0000-0000-000000000000','$FechaInicio','$FechaFin') a LEFT JOIN Position_clasificacion b ON a.idUnidadNegocio=b.idUnidadNegocio AND a.idClasificacion=b.idClasificacion
  WHERE a.idUnidadNegocio='$idUnidadNegocio'
GROUP BY Empresa,Destino,UnidadDeNegocio,Clasificacion,Pila,FechaSaldo,SaldoInicial,FechaFinSaldo,SaldoFinal,b.Position
) AS THEAD
GROUP BY Empresa,Destino,UnidadDeNegocio,Clasificacion,Position
ORDER BY Empresa,UnidadDeNegocio,Position,Clasificacion";
  $resul_clasif=sqlsrv_query($conn,$sql_clasif,$params,$options);
  $rows_clasif = sqlsrv_num_rows($resul_clasif);
  if($rows_clasif>0){
    $position_clasificacion=0;
    $echo.='<br><table class="table table-bordered table-condensed" id="table_inventory_detail">
            <thead>
            <th align="center">Zona</th>
            <th align="center">Material</th>
            <th align="center">Destino</th>
            <th align="center">Acopio</th>
            <th align="center">Mes</th>
            <th align="center">Semana</th>
            <th align="center">Proveedor</th>
            <th align="center">Movimiento</th>
            <th align="center">Localidad</th>
            <th align="center">Detalle</th>
            <th align="center">Modalidad</th>';
    $array_title_clasif = [];
    while($aa = sqlsrv_fetch_array($resul_clasif)){
      $clasificacion = utf8_encode($aa['Clasificacion']);
      $destino=utf8_encode($aa['Destino']);
      $SaldoInicial=number_format($aa['SaldoInicial'],2,'.','');
      $SaldoFinal=number_format($aa['SaldoFinal'],2,'.','');
      if(is_bool(array_search($clasificacion, $array_title_clasif))){
        $array_title_clasif[$position_clasificacion] = $clasificacion;
        $position_clasificacion++;
      }
      $array_saldo_inv[$destino][$clasificacion.'||SaldoInicial']=$SaldoInicial;
      $array_saldo_inv[$destino][$clasificacion.'||SaldoFinal']=$SaldoFinal;
    }
    for ($i=0; $i < count($array_title_clasif); $i++) { 
      $echo.='<th align="center">'.$array_title_clasif[$i].'</th>'; 
    }
    $echo.='</thead><tbody>';
    /*echo '<pre>';
    print_r($array_title_clasif);
    echo '</pre>';
    echo '<pre>';
    print_r($array_saldo_inv);
    echo '</pre>';*/

    $sql="SELECT CASE WHEN (Empresa='MINEX IS SAS') THEN 'INTERIOR' ELSE 'NDS' END AS Zona,UnidadDeNegocio,'PRODUCCION' AS Uso,Destino,Empresa,(CASE WHEN (Proceso='Entradas' OR TransaccionDetalle='Producción') THEN 'ENTRADAS' ELSE 'SALIDAS' END) AS Movimiento,Localidad,(CASE WHEN (Proceso='Entradas' OR Proceso='Salidas') THEN TransaccionDetalle ELSE Proceso + '-' + TransaccionDetalle END) AS Detalle,
  esFacturable as Modalidad,Clasificacion,Proceso,PositionProceso,iTransacion,TransaccionDetalle,SUM(ToneladasProceso) AS ToneladasProceso
FROM Get_SaldoInventarioPILA_detailReport('$idEmpresa','$idDestino','00000000-0000-0000-0000-000000000000','00000000-0000-0000-0000-000000000000','$FechaInicio','$FechaFin') WHERE idUnidadNegocio='$idUnidadNegocio'
GROUP BY Empresa,Destino,Localidad,Clasificacion,Proceso,PositionProceso,iTransacion,TransaccionDetalle,esFacturable,UnidadDeNegocio
ORDER BY Empresa,Destino,UnidadDeNegocio,(CASE WHEN (Proceso='Entradas' OR TransaccionDetalle='Producción') THEN 'Entradas' ELSE 'Salidas' END),Proceso,TransaccionDetalle,Localidad,Clasificacion";
    $res = sqlsrv_query($conn,utf8_decode($sql));
    $var_constant='';
    $txt_constant='';
    $cursor_variable=0;
    $position_clasificacion=0;
    $total_movimientos = [];
    $save_clasif_variables = [];
    $detail_clasif_variables = [];
    while($aa = sqlsrv_fetch_array($res)){
      $Zona = utf8_encode($aa['Zona']);
      $UnidadDeNegocio=utf8_encode($aa['UnidadDeNegocio']);
      $Uso=utf8_encode($aa['Uso']);
      $Destino=utf8_encode($aa['Destino']);
      //$SaldoInicial=number_format($aa['SaldoInicial'],2,'.','');
      //$saldoFinal=number_format($aa['SaldoFinal'],2,'.','');
      $Empresa=utf8_encode($aa['Empresa']);
      $Movimiento=utf8_encode($aa['Movimiento']);
      $Localidad=utf8_encode($aa['Localidad']);
      $Proceso=utf8_encode($aa['Proceso']);
      $TransaccionDetalle=utf8_encode($aa['TransaccionDetalle']);
      $Modalidad=utf8_encode($aa['Modalidad']);
      $Clasificacion=utf8_encode($aa['Clasificacion']);
      $ToneladasProceso=number_format($aa['ToneladasProceso'],2,'.','');
      $Detalle = utf8_encode($aa['Detalle']);
        
      if($Proceso!='-' && $TransaccionDetalle!='-' && $ToneladasProceso!=0){
        $mezcla_variables = $Zona.'||'. //0
                            $UnidadDeNegocio.'||'. //1
                            $Uso.'||'. //2
                            $Destino.'||'. //3
                            $Empresa.'||'. //4
                            $Movimiento.'||'. //5
                            $Localidad.'||'. //6
                            $Detalle.'||'. //7
                            $Modalidad; //8

        if($var_constant==''){
          $txt_constant=$Zona.'||'.
                        $UnidadDeNegocio.'||'.
                        $uso.'||'.
                        $Destino.'||'.
                        $Empresa.'||'.
                        'INVENTARIO_I'.'||'.
                        'N/A'.'||'.
                        'Inv. Inicial'.'||'.
                        'F';
          //INSERTA LAS cOlumnas dE texto
          $save_clasif_variables[$cursor_variable][0]=$txt_constant;
          //TRAE LOS INVENTARIOS INICIALES
          for ($i=0; $i < count($array_title_clasif); $i++){
            if(isset($array_saldo_inv[$Destino][$array_title_clasif[$i].'||SaldoInicial'])){
              $detail_clasif_variables[$array_title_clasif[$i]]=$array_saldo_inv[$Destino][$array_title_clasif[$i].'||SaldoInicial'];
            }
          }

          $save_clasif_variables[$cursor_variable][1]=$detail_clasif_variables;
          $detail_clasif_variables=[];
          $cursor_variable++;

          //SUMARIZA PARA LOS TOTALES (ENTRADAS) O (SALIDAS)
          if(isset($total_movimientos[$Clasificacion]))
            $total_movimientos[$Clasificacion]+=$aa['ToneladasProceso'];
          else
            $total_movimientos[$Clasificacion]=$aa['ToneladasProceso'];

          $var_constant=$mezcla_variables;
          $save_clasif_variables[$cursor_variable][0]=$var_constant;
          $detail_clasif_variables[$Clasificacion]=$ToneladasProceso;
        }elseif($var_constant==$mezcla_variables){
          $detail_clasif_variables[$Clasificacion]=$ToneladasProceso;
          //SUMARIZA PARA LOS TOTALES (ENTRADAS) O (SALIDAS)
          if(isset($total_movimientos[$Clasificacion]))
            $total_movimientos[$Clasificacion]+=$aa['ToneladasProceso'];
          else
            $total_movimientos[$Clasificacion]=$aa['ToneladasProceso'];
        }elseif($var_constant<>$mezcla_variables){
          $save_clasif_variables[$cursor_variable][1]=$detail_clasif_variables;
          $detail_clasif_variables=[];
          $cursor_variable++;

          $explode = explode("||", $var_constant);
          if($explode[5]!=$Movimiento){
            /************************************************************************************/
            //TOTAL MOVIMIENTO
            $txt_constant=$explode[0].'||'.
                        $explode[1].'||'.
                        $explode[2].'||'.
                        $explode[3].'||'.
                        $explode[4].'||'.
                        $explode[5].'||'.
                        'N/A'.'||'.
                        'TOTAL '.$explode[5].'||'.
                        '';
            $save_clasif_variables[$cursor_variable][0]=$txt_constant;
            for ($i=0; $i < count($array_title_clasif); $i++){
              if(isset($total_movimientos[$array_title_clasif[$i]]))
                $detail_clasif_variables[$array_title_clasif[$i]]=number_format($total_movimientos[$array_title_clasif[$i]],2,'.','');
                //$echo.='<td>'.number_format($total_movimientos[$array_title_clasif[$i]],2,'.','').'</td>';
            }
            
            $save_clasif_variables[$cursor_variable][1]=$detail_clasif_variables;
            $detail_clasif_variables=[];
            $cursor_variable++;
            $total_movimientos=[];
            /************************************************************************************/
          }
          
          if($explode[3]!=$Destino){
            //INVENTARIO FINAL
            $txt_constant=$explode[0].'||'.
                        $explode[1].'||'.
                        $explode[2].'||'.
                        $explode[3].'||'.
                        $explode[4].'||'.
                        'INVENTARIO_F'.'||'.
                        'N/A'.'||'.
                        'Inv. Final'.'||'.
                        '';
            $save_clasif_variables[$cursor_variable][0]=$txt_constant;

            for ($i=0; $i < count($array_title_clasif); $i++){
              if(isset($array_saldo_inv[$explode[3]][$array_title_clasif[$i].'||SaldoFinal'])){
                $detail_clasif_variables[$array_title_clasif[$i]]=$array_saldo_inv[$explode[3]][$array_title_clasif[$i].'||SaldoFinal'];
                //$echo.='<td>'.$array_saldo_inv[$explode[3]][$array_title_clasif[$i].'||SaldoFinal'].'</td>';
              }
            }
            $save_clasif_variables[$cursor_variable][1]=$detail_clasif_variables;
            $detail_clasif_variables=[];
            $cursor_variable++;
            //FIN INVENTARIO FINAL
            //INVENTARIO INICIAL
            $txt_constant=$Zona.'||'.
                          $UnidadDeNegocio.'||'.
                          $uso.'||'.
                          $Destino.'||'.
                          $Empresa.'||'.
                          'INVENTARIO_I'.'||'.
                          'N/A'.'||'.
                          'Inv. Inicial'.'||'.
                          'F';
            //INSERTA LAS cOlumnas dE texto
            $save_clasif_variables[$cursor_variable][0]=$txt_constant;
            //TRAE LOS INVENTARIOS INICIALES
            for ($i=0; $i < count($array_title_clasif); $i++){
              if(isset($array_saldo_inv[$Destino][$array_title_clasif[$i].'||SaldoInicial'])){
                $detail_clasif_variables[$array_title_clasif[$i]]=$array_saldo_inv[$Destino][$array_title_clasif[$i].'||SaldoInicial'];
              }
            }

            $save_clasif_variables[$cursor_variable][1]=$detail_clasif_variables;
            $detail_clasif_variables=[];
            $cursor_variable++;
          }
          
          $var_constant=$mezcla_variables;
          $save_clasif_variables[$cursor_variable][0]=$var_constant;
          $detail_clasif_variables[$Clasificacion]=$ToneladasProceso;
          if(isset($total_movimientos[$Clasificacion]))
            $total_movimientos[$Clasificacion]+=$aa['ToneladasProceso'];
          else
            $total_movimientos[$Clasificacion]=$aa['ToneladasProceso'];
        }
      }
    }
  }
  /********************************************************/
  $save_clasif_variables[$cursor_variable][1]=$detail_clasif_variables;
  $detail_clasif_variables=[];
  $cursor_variable++;

  $explode = explode("||", $var_constant);
  //if($explode[5]!=$Movimiento){
    /************************************************************************************/
    //TOTAL MOVIMIENTO
    $txt_constant=$explode[0].'||'.
                $explode[1].'||'.
                $explode[2].'||'.
                $explode[3].'||'.
                $explode[4].'||'.
                $explode[5].'||'.
                'N/A'.'||'.
                'TOTAL '.$explode[5].'||'.
                '';
    $save_clasif_variables[$cursor_variable][0]=$txt_constant;
    for ($i=0; $i < count($array_title_clasif); $i++){
      if(isset($total_movimientos[$array_title_clasif[$i]]))
        $detail_clasif_variables[$array_title_clasif[$i]]=number_format($total_movimientos[$array_title_clasif[$i]],2,'.','');
        //$echo.='<td>'.number_format($total_movimientos[$array_title_clasif[$i]],2,'.','').'</td>';
    }
    
    $save_clasif_variables[$cursor_variable][1]=$detail_clasif_variables;
    $detail_clasif_variables=[];
    $cursor_variable++;
    $total_movimientos=[];
    /************************************************************************************/
  //}
  
  //if($explode[3]!=$Destino){
    //INVENTARIO FINAL
    $txt_constant=$explode[0].'||'.
                $explode[1].'||'.
                $explode[2].'||'.
                $explode[3].'||'.
                $explode[4].'||'.
                'INVENTARIO_F'.'||'.
                'N/A'.'||'.
                'Inv. Final'.'||'.
                '';
    $save_clasif_variables[$cursor_variable][0]=$txt_constant;

    for ($i=0; $i < count($array_title_clasif); $i++){
      if(isset($array_saldo_inv[$explode[3]][$array_title_clasif[$i].'||SaldoFinal'])){
        $detail_clasif_variables[$array_title_clasif[$i]]=$array_saldo_inv[$explode[3]][$array_title_clasif[$i].'||SaldoFinal'];
        //$echo.='<td>'.$array_saldo_inv[$explode[3]][$array_title_clasif[$i].'||SaldoFinal'].'</td>';
      }
    }
    $save_clasif_variables[$cursor_variable][1]=$detail_clasif_variables;
    $detail_clasif_variables=[];
    $cursor_variable++;
    //FIN INVENTARIO FINAL
  //}
  /********************************************************/
  for ($i=0; $i < count($save_clasif_variables); $i++){
    $explode=explode("||", $save_clasif_variables[$i][0]);
    //ASIGNA COLORES A LA FILA
    if($explode[7]=='TOTAL ENTRADAS'){
      $css='style="background-color: #8AB658; font-weight: bolder;"';
    }elseif($explode[7]=='TOTAL SALIDAS'){
      $css='style="background-color: #E8994E; font-weight: bolder;"';
    }elseif($explode[7]=='Inv. Inicial' || $explode[7]=='Inv. Final'){
      $css='style="background-color: powderblue; font-weight: bolder;"';
    }else{
      $css='';
    }
    $echo.='<tr '.$css.'>';
    $echo.='<td>'.$explode[0].'</td>';
    $echo.='<td>'.$explode[1].'</td>';
    $echo.='<td>'.$explode[2].'</td>';
    $echo.='<td>'.$explode[3].'</td>';
    $echo.='<td></td>';
    $echo.='<td></td>';
    $echo.='<td>'.$explode[4].'</td>';
    $echo.='<td>'.$explode[5].'</td>';
    $echo.='<td>'.$explode[6].'</td>';
    $echo.='<td>'.$explode[7].'</td>';
    $echo.='<td>'.$explode[8].'</td>';
    for ($j=0; $j < count($array_title_clasif); $j++){
      $var_clasif=$save_clasif_variables[$i][1];
      if(isset($var_clasif[$array_title_clasif[$j]])){
        $echo.='<td>'.$var_clasif[$array_title_clasif[$j]].'</td>';
      }else{
        $echo.='<td>0.00</td>';
      }
      //$echo.='<th align="center">'.$array_title_clasif[$i].'</th>';
    }
    $echo.='</tr>';
  }
  /*echo '<pre>';
  print_r($save_clasif_variables);
  echo '</pre>';*/
  echo $echo;
}
?>