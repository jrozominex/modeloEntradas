<?php
include('conexion.php');
include ("../../clase_encrip.php");
session_start();
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
if(!isset($_SESSION['Array_empresa']['TARIFA_CARGADORES'])){
    ?>
  <script type="text/javascript">
      self.location='Admin.php';
      alert('Se ha suspendido la sesión');
  </script>
  <?php
}
$json='';
if($_GET['band']==-1){
   $post_data = file_get_contents('php://input');
   $list_record = json_decode($post_data, true);
   $select_liquidador = $list_record['select_liquidador'];
   if($select_liquidador==-1){
      $json = '<div class="modal-body">
                   <div class="row">
                       <div class="col-sm-4">
                           <label>Empresa</label>
                           <select class="form-control" id="empresa">
                               <option selected="true" disabled="">--- Seleccione ---</option>';
                               $sql = "SELECT * FROM Proveedores WHERE Empresa=1 ORDER BY Alias";
                        $resul=sqlsrv_query($conn,$sql,$params,$options);
                        $row = sqlsrv_num_rows($resul);
                        if($row>0){
                           while($aa=sqlsrv_fetch_array($resul)){
                              $json.='<option value="'.ENCR::encript($aa['idProveedor']).'">'.utf8_encode($aa['Alias']).'</option>';
                           }
                        }
                        $json.='</select></div>
                       <div class="col-sm-4">
                           <label>Proveedor</label>
                           <select class="form-control" id="proveedor" onchange="load_cargador_proveedor()">
                               <option selected="true" disabled="">--- Seleccione ---</option>';
                               $sql = "SELECT Proveedores.RazonSocial, Proveedores.idProveedor FROM Equipos 
                                   INNER JOIN Proveedores ON Equipos.idPropietario=Proveedores.idProveedor
                                       AND Equipos.clase_equipo='7A975CD6-2672-430D-B29E-7149A03D9410'
                                   GROUP BY Proveedores.RazonSocial, Proveedores.idProveedor";
                        $resul=sqlsrv_query($conn,$sql,$params,$options);
                        $row = sqlsrv_num_rows($resul);
                        if($row>0){
                           while($aa=sqlsrv_fetch_array($resul)){
                              $json.='<option value="'.ENCR::encript($aa['idProveedor']).'">'.utf8_encode($aa['RazonSocial']).'</option>';
                           }
                        }
                   $json.='</select>
                       </div>
                        <div class="col-sm-4">
                           <label>Equipo</label>
                           <select class="form-control" id="equipo">
                               <option selected="true" disabled="">Seleccione proveedor</option>
                           </select>
                       </div>';
                       
                   $json.='</div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-warning" id="registrar_special">Registrar Documento</button>
               </div>';
   }else{
      $json = '<div class="modal-body">
                   <div class="row">
                       <div class="col-sm-4">
                           <label>Empresa</label>
                           <select class="form-control" id="empresa">
                               <option selected="true" disabled="">--- Seleccione ---</option>';
                               $sql = "SELECT * FROM Proveedores WHERE Empresa=1 ORDER BY Alias";
                        $resul=sqlsrv_query($conn,$sql,$params,$options);
                        $row = sqlsrv_num_rows($resul);
                        if($row>0){
                           while($aa=sqlsrv_fetch_array($resul)){
                              $json.='<option value="'.ENCR::encript($aa['idProveedor']).'">'.utf8_encode($aa['Alias']).'</option>';
                           }
                        }
                        $json.='</select></div>
                       <div class="col-sm-4">
                           <label>Proveedor</label>
                           <select class="form-control" id="proveedor">
                               <option selected="true" disabled="">--- Seleccione ---</option>';
                               $sql = "SELECT Proveedores.* FROM ProveedoresGrupos INNER JOIN Proveedores ON ProveedoresGrupos.idProveedor=Proveedores.idProveedor 
                     AND ProveedoresGrupos.idAgrupacion IN (SELECT idAgrupacion FROM Liquidaciones() WHERE idLiquidacion='$select_liquidador') ORDER BY RazonSocial";
                        $resul=sqlsrv_query($conn,$sql,$params,$options);
                        $row = sqlsrv_num_rows($resul);
                        if($row>0){
                           while($aa=sqlsrv_fetch_array($resul)){
                              $json.='<option value="'.ENCR::encript($aa['idProveedor']).'">'.utf8_encode($aa['RazonSocial']).'</option>';
                           }
                        }
                   $json.='</select>
                       </div>
                       <div class="col-sm-4">
                           <label>Reporte Variable</label>
                           <select class="form-control" id="reporte_variable">
                               <option selected="true" disabled="">Seleccione</option>';
                               $sql = "SELECT * FROM dbo.qualityReportTree WHERE id_parent='00000000-0000-0000-0000-000000000066' ORDER BY name";
                        $res = sqlsrv_query($conn,$sql);    
                        while($rows = sqlsrv_fetch_array($res)){
                           $id=ENCR::encript($rows['id']);
                           $report=$rows['name'];
                        $json.='<option value="'.$id.'">'.utf8_encode($report).'</option>';
                        }
                    $json.='</select>
                       </div>';
                   $json.='</div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-warning" id="registrar_special">Registrar Documento</button>
               </div>';
   }
}elseif($_GET['band']==0){
   $json.='<div class="row" style="margin-left: 10px; margin-right: 10px; margin-top: 0px;">
            <div class="col-sm-4"></div>
            <div class="col-sm-4" style="background-color: powderblue; border-radius: 5px;">
                <center><label>MODO DE LIQUIDACIÓN</label></center>
                <select class="form-control" id="select_liquidador" onchange="load_body_documents()">
                    <option selected="" disabled="">Seleccione</option>
                    <option value="-1"><b>Cargadores</option>';
                    $sql = "SELECT * FROM Liquidaciones()";
                    $res = sqlsrv_query($conn,$sql);
                    while($aa=sqlsrv_fetch_array($res)){
                        $json.='<option value="'.$aa['idLiquidacion'].'">'.utf8_encode($aa['Descripcion']).'</option>';
                    }
                $json.='</select><br>
            </div>
            <div class="col-sm-1"><button class="btn btn-success navbar-left" style="margin-right: 15px;" onclick="open_modal_tarifas()" data-toggle="modal" data-target="#modalInsertar">Crear Documento <span class="glyphicon glyphicon-plus"></span></button></div>
        </div><br>';
}elseif($_GET['band']==1){
   $post_data = file_get_contents('php://input');
   $list_record = json_decode($post_data, true);
   $select_liquidador = $list_record['select_liquidador'];
   if($select_liquidador==-1){
      $sql="SELECT a.idDocumento,a.idEquipo,Equipos.Descripcion + '-' + Equipos.Identificacion AS Equipo,a.idProveedor,Proveedores.RazonSocial,P2.Alias AS Empresa
      FROM DocumentoMaquinaria AS a
      INNER JOIN Equipos ON a.idEquipo=Equipos.idEquipo
      INNER JOIN Proveedores ON a.idProveedor=Proveedores.idProveedor
      INNER JOIN Proveedores AS P2 ON a.idEmpresa=P2.idProveedor";
      $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
      $rows=sqlsrv_num_rows($resultado);
      $json .= '{"columns": [{"field":"fempresa", "text":"Empresa", "sortable": "true"},{"field":"fproveedor", "text":"Proveedor", "sortable": "true"},{"field":"fequipo", "text":"Equipo", "sortable": "true"}],';
      $json.='"records": [';
      if($rows > 0){
         while($data = sqlsrv_fetch_array($resultado)){
            $idDocumento=ENCR::encript($data['idDocumento']);
            $Equipo=utf8_encode($data['Equipo']);
            $proveedor=utf8_encode($data['RazonSocial']);
            $Empresa=utf8_encode($data['Empresa']);
            //$array_data = $data['idLiquidacion'].'||'.$data['id'].'||'.$data['idProveedor'];
            $json.='{"recid":"'.$idDocumento.'","fempresa":"'.$Empresa.'","fproveedor":"'.$proveedor.'","fequipo":"'.$Equipo.'"},';
         }
         $json = substr($json, 0, strlen($json) - 1);
      }
   }else{
      $sql="SELECT a.idDocumentoLiquidacion,a.idLiquidacion,a.idReporte,QualityReportTree.name,a.idProveedor,Proveedores.RazonSocial,P2.Alias AS Empresa
      FROM DocumentoLiquidaciones AS a
      INNER JOIN QualityReportTree ON a.idReporte=QualityReportTree.id
      INNER JOIN Proveedores ON a.idProveedor=Proveedores.idProveedor
      INNER JOIN Proveedores AS P2 ON a.idEmpresa=P2.idProveedor
      WHERE a.idLiquidacion='$select_liquidador'";
      $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
      $rows=sqlsrv_num_rows($resultado);
      $json .= '{"columns": [{"field":"fempresa", "text":"Empresa", "sortable": "true"},{"field":"freporte", "text":"Reporte", "sortable": "true"},{"field":"fproveedor", "text":"Proveedor", "sortable": "true"}],';
      $json.='"records": [';
      if($rows > 0){
         while($data = sqlsrv_fetch_array($resultado)){
            $idDocumentoLiquidacion=ENCR::encript($data['idDocumentoLiquidacion']);
            $reporte=utf8_encode($data['name']);
            $proveedor=utf8_encode($data['RazonSocial']);
            $Empresa=utf8_encode($data['Empresa']);
            //$array_data = $data['idLiquidacion'].'||'.$data['id'].'||'.$data['idProveedor'];
            $json.='{"recid":"'.$idDocumentoLiquidacion.'","fempresa":"'.$Empresa.'","freporte":"'.$reporte.'","fproveedor":"'.$proveedor.'"},';
         }
         $json = substr($json, 0, strlen($json) - 1);
      }
   }
   $json.= ']}';
}elseif($_GET['band']==2){
   $post_data = file_get_contents('php://input');
   $list_record = json_decode($post_data, true);
   $idDocumentoLiquidacion = ENCR::descript($list_record['idDocumentoLiquidacion']);
   $sql = "SELECT * FROM DocumentoLiquidaciones WHERE idDocumentoLiquidacion='$idDocumentoLiquidacion'";
   $res = sqlsrv_query($conn,$sql);
   while($aa=sqlsrv_fetch_array($res)){
      $idReporte=$aa['idReporte'];
   }
   $sql_structure_table = "SELECT name FROM sys.columns WHERE object_id = OBJECT_ID('dbo.QualityParameterReport') AND system_type_id NOT IN (36,40)";
   $res = sqlsrv_query($conn,$sql_structure_table);
   $band_array=0;
   while ($aa=sqlsrv_fetch_array($res)) {
      $structure_table[$band_array]=$aa['name'];
      $band_array++;
   }
   $sql_report="SELECT * FROM QualityParameterReport WHERE idReporte IN (SELECT idReporte FROM QualityReportTreeDetail 
      WHERE id IN (SELECT id FROM QualityReportTree WHERE id='$idReporte' OR id_parent='$idReporte'))";
   $res = sqlsrv_query($conn,$sql_report);
   $json='';
   while($aa=sqlsrv_fetch_array($res)){
      $json.='<div class="row" style="border: 1px solid; border-radius: 5px;"><center><label>Reporte: '.$aa['Descripcion'].'</label></center>';
      for ($i=0; $i < count($structure_table); $i++) { 
         if($aa[$structure_table[$i]]<>NULL){
            $json.='<div class="col-sm-4"><p>'.$structure_table[$i].'</p><input type="text" class="form-control" disabled="true" value="'.utf8_encode($aa[$structure_table[$i]]).'"></div>';
         }
      }
      $json.='</div>';
   }
}elseif($_GET['band']==3){
   $post_data = file_get_contents('php://input');
   $list_record = json_decode($post_data, true);
   $idDocumentoLiquidacion = ENCR::descript($list_record['idDocumentoLiquidacion']);
   $select_liquidador = $list_record['select_liquidador'];
   if($select_liquidador==-1){
      $sql = "SELECT * FROM DocumentoMaquinaria WHERE idDocumento='$idDocumentoLiquidacion'";
      $res = sqlsrv_query($conn,$sql);
      while($aa=sqlsrv_fetch_array($res)){
         $idEmpresa=$aa['idEmpresa'];
         $idProveedor=$aa['idProveedor'];
         $idEquipo=$aa['idEquipo'];
      }
      $sql_tarifas="SELECT * FROM TarifaMaquinaria WHERE idDocumento='$idDocumentoLiquidacion' OR (idEquipo='$idEquipo' AND idProveedor='$idProveedor' AND idDocumento IS NULL)";
      $resultado=sqlsrv_query($conn,($sql_tarifas),$params,$options);
      $rows=sqlsrv_num_rows($resultado);
      $json.='{"records": [';
      if($rows > 0){
         while($data = sqlsrv_fetch_array($resultado)){
            $Tipo_Tarifa=$data['Tipo_Tarifa'];
            if($Tipo_Tarifa==2){
               $nameTarifa='Toneladas';
            }elseif($Tipo_Tarifa==3){
               $nameTarifa='Tiempo';
            }
            $FechaDesde=date_format($data['Fecha_Desde'],'m/d/Y');
            $FechaHasta=date_format($data['Fecha_Hasta'],'m/d/Y');
            $Tarifa=number_format($data['Tarifa_Horometro'],0,'','');
            $idxid=ENCR::encript($data['idxid']);
            $json.='{"recid":"'.$idxid.'","Tipo_Tarifa":"'.$nameTarifa.'","Fecha_Desde":"'.$FechaDesde.'","Fecha_Hasta":"'.$FechaHasta.'","Tarifa_Horometro":"'.$Tarifa.'"},';
         }
         $json = substr($json, 0, strlen($json) - 1);
      }
   }else{
      $sql = "SELECT * FROM DocumentoLiquidaciones WHERE idDocumentoLiquidacion='$idDocumentoLiquidacion'";
      $res = sqlsrv_query($conn,$sql);
      while($aa=sqlsrv_fetch_array($res)){
         $idLiquidacion=$aa['idLiquidacion'];
         $idReporte=$aa['idReporte'];
         $idProveedor=$aa['idProveedor'];
      }
      $sql_tarifas="SELECT * FROM DocumentoLiquidacionTarifas WHERE idLiquidacion='$idLiquidacion' AND id='$idReporte' AND idProveedor='$idProveedor'";
      $resultado=sqlsrv_query($conn,($sql_tarifas),$params,$options);
      $rows=sqlsrv_num_rows($resultado);
      $json .= '{"columns": [{"field":"FechaDesde", "text":"Fecha Desde", "render":"date","editable": { "type": "date" }},{"field":"FechaHasta", "text":"Fecha Hasta", "render":"date","editable": { "type": "date" }},{"field":"Tarifa", "text":"Tarifa", "render":"money","editable": { "type": "money" }}],';
      $json.='"records": [';
      if($rows > 0){
         while($data = sqlsrv_fetch_array($resultado)){
            $FechaDesde=date_format($data['FechaDesde'],'m/d/Y');
            $FechaHasta=date_format($data['FechaHasta'],'m/d/Y');
            $Tarifa=number_format($data['Tarifa'],0,'','');

            //$array_data = $data['idLiquidacion'].'||'.$data['id'].'||'.$data['idProveedor'];
            $json.='{"recid":"'.ENCR::encript($idLiquidacion.'||'.$idReporte.'||'.$idProveedor.'||'.$FechaDesde.'||'.$FechaHasta).'","FechaDesde":"'.$FechaDesde.'","FechaHasta":"'.$FechaHasta.'","Tarifa":"'.$Tarifa.'"},';
         }
         $json = substr($json, 0, strlen($json) - 1);
      }
   }
   $json .= ']}';
}elseif($_GET['band']==4){
   $json=0;
   $post_data = file_get_contents('php://input');
   $list_record = json_decode($post_data, true);
   $idLiquidacion="";$idReporte="";$idProveedor="";$FechaDesde="";$FechaHasta="";
   $recid = ENCR::descript($list_record['idDocumentoLiquidacion']);
   $select_liquidador=$list_record['select_liquidador'];
   $list_recordGetchanges = $list_record['getChanges'];
   if($select_liquidador==-1){
      $sql_id = "SELECT * FROM DocumentoMaquinaria WHERE idDocumento='$recid'";
      $res_id = sqlsrv_query($conn,$sql_id);
      while($aa = sqlsrv_fetch_array($res_id)){
         $idEmpresa_ctrl=$aa['idEmpresa'];
         $idEquipo_ctrl=$aa['idEquipo'];
         $idProveedor_ctrl=$aa['idProveedor'];
      }
      for ($i=0; $i < count($list_recordGetchanges); $i++){
         //print_r($list_record[$i]);
         $a = $list_recordGetchanges[$i];
         $tipo_sql=0; //UPDATE > 0 || INSERT == 0
         $txt_sql="";
         foreach ($a as $k => $v){
            if($k=='recid'){
               $idGetChanges=ENCR::descript($v);
               $sql_validate="SELECT * FROM TarifaMaquinaria WHERE idxid='$idGetChanges'";
               $resultado=sqlsrv_query($conn,($sql_validate),$params,$options);
               $tipo_sql=sqlsrv_num_rows($resultado);
               if($tipo_sql==0){
                  $txt_sql_column="INSERT INTO TarifaMaquinaria (idProveedor,idEquipo,Fecha_Registro,idDocumento,idxid";
                  $txt_sql=" VALUES ('$idProveedor_ctrl','$idEquipo_ctrl',GETDATE(),'$recid',NEWID()";
               }else{
                  $txt_sql="UPDATE TarifaMaquinaria SET ";
               }
            }else{
               //echo "\$a[$k] => $v.\n";
               if($tipo_sql==0){
                  $txt_sql_column.=",$k";
                  $txt_sql.=",'$v'";
               }else{
                  $txt_sql.="$k='$v',";
               }
            }
         }
         if($tipo_sql==0){
            $txt_sql.=")";
            $txt_sql_column.=")";
            $txt_sql = $txt_sql_column.$txt_sql;
         }else{
            $txt_sql = substr($txt_sql, 0, strlen($txt_sql) - 1);
            $txt_sql.=" WHERE idDocumento='$recid' AND idxid='$idGetChanges'";
         }
      }
   }else{
      $sql_id = "SELECT * FROM DocumentoLiquidaciones WHERE idDocumentoLiquidacion='$recid'";
      $res_id = sqlsrv_query($conn,$sql_id);
      while($aa = sqlsrv_fetch_array($res_id)){
         $idLiquidacion_ctrl=$aa['idLiquidacion'];
         $idReporte_ctrl=$aa['idReporte'];
         $idProveedor_ctrl=$aa['idProveedor'];
      }
      for ($i=0; $i < count($list_recordGetchanges); $i++){
         //print_r($list_record[$i]);
         $a = $list_recordGetchanges[$i];
         $tipo_sql=0; //UPDATE > 0 || INSERT == 0
         $txt_sql="";
         foreach ($a as $k => $v){
            if($k=='recid'){
               $recid=ENCR::descript($v);
               $recid = explode("||", $recid);
               if(count($recid)>1){
                  $idLiquidacion = $recid[0];$idReporte = $recid[1];$idProveedor=$recid[2];$FechaDesde=$recid[3];$FechaHasta=$recid[4];
               }
               $sql_validate="SELECT * FROM DocumentoLiquidacionTarifas WHERE idLiquidacion='$idLiquidacion_ctrl' AND id='$idReporte_ctrl' AND idProveedor='$idProveedor_ctrl' AND FechaDesde='$FechaDesde' AND FechaHasta='$FechaHasta'";
               $resultado=sqlsrv_query($conn,($sql_validate),$params,$options);
               $tipo_sql=sqlsrv_num_rows($resultado);
               if($tipo_sql==0){
                  $txt_sql_column="INSERT INTO DocumentoLiquidacionTarifas (idLiquidacion,id,idProveedor";
                  $txt_sql=" VALUES ('$idLiquidacion_ctrl','$idReporte_ctrl','$idProveedor_ctrl'";
               }else{
                  $txt_sql="UPDATE DocumentoLiquidacionTarifas SET ";
               }
            }else{
               //echo "\$a[$k] => $v.\n";
               if($tipo_sql==0){
                  $txt_sql_column.=",$k";
                  $txt_sql.=",'$v'";
               }else{
                  $txt_sql.="$k='$v',";
               }
            }
         }
         if($tipo_sql==0){
            $txt_sql.=")";
            $txt_sql_column.=")";
            $txt_sql = $txt_sql_column.$txt_sql;
         }else{
            $txt_sql = substr($txt_sql, 0, strlen($txt_sql) - 1);
            $txt_sql.=" WHERE idLiquidacion='$idLiquidacion_ctrl' AND id='$idReporte_ctrl' AND idProveedor='$idProveedor_ctrl' AND FechaDesde='$FechaDesde' AND FechaHasta='$FechaHasta'";
         }
      }
   }
   //echo $txt_sql;
   $res_txt_sql=sqlsrv_query($conn,$txt_sql);
   if($res_txt_sql){
      $json++;
   }
   if(count($list_recordGetchanges)==$json){
      $json = 1;
   }else{
      $json = 0;
   }
}elseif($_GET['band']==5){
   $post_data = file_get_contents('php://input'); 
   $list_record = json_decode($post_data, true);
   $idDocumentoLiquidacion = ENCR::descript($list_record['idDocumentoLiquidacion']);
   $select_liquidador = $list_record['select_liquidador'];
   if($select_liquidador==-1){
      $sql = "SELECT * FROM DocumentoMaquinaria WHERE idDocumento='$idDocumentoLiquidacion'";
      $res = sqlsrv_query($conn,$sql);
      while($aa=sqlsrv_fetch_array($res)){
         $idEquipo=$aa['idEquipo'];
         $idProveedor=$aa['idProveedor'];
      }
      $sql_tarifas="SELECT a.*,b.Descripcion AS Concepto,c.Descripcion AS ModoDes FROM DescuentoMaquinaria AS a INNER JOIN ConceptoAjuste() AS b ON a.iconcepto=b.iconcepto INNER JOIN AcuerdosDescuentoModo() AS c ON a.modo=c.Modo WHERE idDocumento='$idDocumentoLiquidacion'";
      $resultado=sqlsrv_query($conn,($sql_tarifas),$params,$options);
      $rows=sqlsrv_num_rows($resultado);
      $json.='{"records": [';
      if($rows > 0){
         while($data = sqlsrv_fetch_array($resultado)){
            $idxid=ENCR::encript($data['idxid']);
            $Concepto=$data['Concepto'];
            $Referencia=$data['Referencia'];
            $Modo=$data['ModoDes'];
            $FechaDesde=date_format($data['Vigente_desde'],'m/d/Y');
            $FechaHasta=date_format($data['Vigente_hasta'],'m/d/Y');
            $Tarifa=number_format($data['valor'],0,'','');
            $json.='{"recid":"'.$idxid.'","iconcepto":"'.$Concepto.'","Referencia":"'.$Referencia.'","modo":"'.$Modo.'","Vigente_desde":"'.$FechaDesde.'","Vigente_hasta":"'.$FechaHasta.'","valor":"'.$Tarifa.'"},';
         }
         $json = substr($json, 0, strlen($json) - 1);
      }
   }else{
      $sql = "SELECT * FROM DocumentoLiquidaciones WHERE idDocumentoLiquidacion='$idDocumentoLiquidacion'";
      $res = sqlsrv_query($conn,$sql);
      while($aa=sqlsrv_fetch_array($res)){
         $idLiquidacion=$aa['idLiquidacion'];
         $idReporte=$aa['idReporte'];
         $idProveedor=$aa['idProveedor'];
      }
      $sql_tarifas="SELECT a.*,b.Descripcion AS Concepto,c.Descripcion AS ModoDes FROM DocumentoLiquidacionDescuentos AS a INNER JOIN ConceptoAjuste() AS b ON a.iconcepto=b.iconcepto INNER JOIN AcuerdosDescuentoModo() AS c ON a.modo=c.Modo WHERE idLiquidacion='$idLiquidacion' AND idReporte='$idReporte' AND idProveedor='$idProveedor'";
      $resultado=sqlsrv_query($conn,($sql_tarifas),$params,$options);
      $rows=sqlsrv_num_rows($resultado);
      $json.='{"records": [';
      if($rows > 0){
         while($data = sqlsrv_fetch_array($resultado)){
            $idxid=ENCR::encript($data['idxid']);
            $Concepto=$data['Concepto'];
            $Referencia=$data['Referencia'];
            $Modo=$data['ModoDes'];
            $FechaDesde=date_format($data['Vigente_desde'],'m/d/Y');
            $FechaHasta=date_format($data['Vigente_hasta'],'m/d/Y');
            $Tarifa=number_format($data['valor'],0,'','');
            $json.='{"recid":"'.$idxid.'","iconcepto":"'.$Concepto.'","Referencia":"'.$Referencia.'","modo":"'.$Modo.'","Vigente_desde":"'.$FechaDesde.'","Vigente_hasta":"'.$FechaHasta.'","valor":"'.$Tarifa.'"},';
         }
         $json = substr($json, 0, strlen($json) - 1);
      }
   }
   $json .= ']}';
}elseif($_GET['band']==6){
   $json=0;
   $post_data = file_get_contents('php://input');
   $list_record = json_decode($post_data, true);
   $select_liquidador = $list_record['select_liquidador'];
   $idxid="";
   $recidDoc = ENCR::descript($list_record['idDocumentoLiquidacion']);
   $list_recordGetchanges = $list_record['getChanges'];
   if($select_liquidador==-1){
      $sql_id = "SELECT * FROM DocumentoMaquinaria WHERE idDocumento='$recidDoc'";
      $res_id = sqlsrv_query($conn,$sql_id);
      while($aa = sqlsrv_fetch_array($res_id)){
         $idEmpresa_ctrl=$aa['idEmpresa'];
         $idEquipo_ctrl=$aa['idEquipo'];
         $idProveedor_ctrl=$aa['idProveedor'];
      }
      for ($i=0; $i < count($list_recordGetchanges); $i++){
         //print_r($list_record[$i]);
         $a = $list_recordGetchanges[$i];
         $tipo_sql=0;   //UPDATE>0 || INSERT==0
         $txt_sql="";
         foreach ($a as $k => $v){
            if($k=='recid'){
               $recid=ENCR::descript($v);
               $sql_validate="SELECT * FROM DescuentoMaquinaria WHERE idxid='$recid'";
               $resultado=sqlsrv_query($conn,($sql_validate),$params,$options);
               $tipo_sql=sqlsrv_num_rows($resultado);
               if($tipo_sql==0){
                  $txt_sql_column="INSERT INTO DescuentoMaquinaria (idDocumento,idxid";
                  $txt_sql=" VALUES ('$recidDoc',NEWID()";
               }else{
                  $txt_sql="UPDATE DescuentoMaquinaria SET ";
               }
            }else{
               //echo "\$a[$k] => $v.\n";
               if($k=='iconcepto' || $k=='modo')
                  $v = ENCR::descript($v);
               if($tipo_sql==0){
                  $txt_sql_column.=",$k";
                  $txt_sql.=",'$v'";
               }else{
                  $txt_sql.="$k='$v',";
               }
            }
         }
         if($tipo_sql==0){
            $txt_sql.=")";
            $txt_sql_column.=")";
            $txt_sql = $txt_sql_column.$txt_sql;
         }else{
            $txt_sql = substr($txt_sql, 0, strlen($txt_sql) - 1);
            $txt_sql.=" WHERE idxid='$recid'";
         }
      }
   }else{
      $sql_id = "SELECT * FROM DocumentoLiquidaciones WHERE idDocumentoLiquidacion='$recid'";
      $res_id = sqlsrv_query($conn,$sql_id);
      while($aa = sqlsrv_fetch_array($res_id)){
         $idLiquidacion_ctrl=$aa['idLiquidacion'];
         $idReporte_ctrl=$aa['idReporte'];
         $idProveedor_ctrl=$aa['idProveedor'];
      }
      //print_r($list_recordGetchanges);
      for ($i=0; $i < count($list_recordGetchanges); $i++){
         //print_r($list_record[$i]);
         $a = $list_recordGetchanges[$i];
         $tipo_sql=0; //UPDATE > 0 || INSERT == 0
         $txt_sql="";
         foreach ($a as $k => $v){
            if($k=='recid'){
               $recid=ENCR::descript($v);
               $sql_validate="SELECT * FROM DocumentoLiquidacionDescuentos WHERE idxid='$recid'";
               $resultado=sqlsrv_query($conn,($sql_validate),$params,$options);
               $tipo_sql=sqlsrv_num_rows($resultado);
               if($tipo_sql==0){
                  $txt_sql_column="INSERT INTO DocumentoLiquidacionDescuentos (idLiquidacion,idReporte,idProveedor,idxid";
                  $txt_sql=" VALUES ('$idLiquidacion_ctrl','$idReporte_ctrl','$idProveedor_ctrl',NEWID()";
               }else{
                  $txt_sql="UPDATE DocumentoLiquidacionDescuentos SET ";
               }
            }else{
               //echo "\$a[$k] => $v.\n";
               if($k=='iconcepto' || $k=='modo')
                  $v = ENCR::descript($v);
               if($tipo_sql==0){
                  $txt_sql_column.=",$k";
                  $txt_sql.=",'$v'";
               }else{
                  $txt_sql.="$k='$v',";
               }
            }
         }
         if($tipo_sql==0){
            $txt_sql.=")";
            $txt_sql_column.=")";
            $txt_sql = $txt_sql_column.$txt_sql;
         }else{
            $txt_sql = substr($txt_sql, 0, strlen($txt_sql) - 1);
            $txt_sql.=" WHERE idxid='$recid'";
         }
      }
   }
   //echo $txt_sql;
   $res_txt_sql=sqlsrv_query($conn,$txt_sql);
   if($res_txt_sql){
      $json++;
   }
   if(count($list_recordGetchanges)==$json){
      $json = 1;
   }else{
      $json = 0;
   }
   //$idDocumentoLiquidacion = ENCR::descript($list_record['idDocumentoLiquidacion']);
}elseif($_GET['band']==7){
   $post_data = file_get_contents('php://input'); 
   $list_record = json_decode($post_data, true);
   $type_list = $list_record['type_list'];
   if($type_list=='ConceptoAjuste'){
      $sql="SELECT * FROM ConceptoAjuste() WHERE iConcepto IN (1,3,6,14,16) ORDER BY Descripcion";
      $res = sqlsrv_query($conn,$sql);
      $json='[';
      while($aa = sqlsrv_fetch_array($res)){
         $json.='{"id":"'.ENCR::encript($aa['iConcepto']).'","text": "'.utf8_encode($aa['Descripcion']).'"},';
      }
   }elseif($type_list=='Modo'){
      $sql="SELECT * FROM AcuerdosDescuentoModo() ORDER BY Descripcion";
      $res = sqlsrv_query($conn,$sql);
      $json='[';
      while($aa = sqlsrv_fetch_array($res)){
         $json.='{"id":"'.ENCR::encript($aa['Modo']).'","text": "'.utf8_encode($aa['Descripcion']).'"},';
      }
   }elseif($type_list=='ModoTarifa'){
      $json='[';
      $json.='{"id":"2","text": "Toneladas"},';
      $json.='{"id":"3","text": "Tiempo"},';
   }
   $json = substr($json, 0, strlen($json) - 1).']';
}elseif($_GET['band']==8){
   $post_data = file_get_contents('php://input'); 
   $list_record = json_decode($post_data, true);
   $id = explode("||",ENCR::descript($list_record['variable']));
   $recid = ENCR::descript($list_record['idDocumentoLiquidacion']);
   $select_liquidador = $list_record['select_liquidador'];
   if($select_liquidador==-1){
      $sql_id = "SELECT * FROM DocumentoMaquinaria WHERE idDocumento='$recid'";
      $res_id = sqlsrv_query($conn,$sql_id);
      while($aa = sqlsrv_fetch_array($res_id)){
         $idEmpresa_ctrl=$aa['idEmpresa'];
         $idProveedor_ctrl=$aa['idProveedor'];
         $idEquipo_ctrl=$aa['idEquipo'];
      }
      //$json=0;
      $sql_delete="DELETE FROM TarifaMaquinaria WHERE idxid='$id[0]'";
   }else{
      $sql_id = "SELECT * FROM DocumentoLiquidaciones WHERE idDocumentoLiquidacion='$recid'";
      $res_id = sqlsrv_query($conn,$sql_id);
      while($aa = sqlsrv_fetch_array($res_id)){
         $idLiquidacion_ctrl=$aa['idLiquidacion'];
         $idReporte_ctrl=$aa['idReporte'];
         $idProveedor_ctrl=$aa['idProveedor'];
      }
      //$json=0;
      $sql_delete="DELETE FROM DocumentoLiquidacionTarifas WHERE idLiquidacion='$idLiquidacion_ctrl' AND id='$idReporte_ctrl' AND idProveedor='$idProveedor_ctrl' AND FechaDesde='$id[3]' AND FechaHasta='$id[4]'";
   }
   $res_delete=sqlsrv_query($conn,$sql_delete);
   if($res_delete){
      $json=1;
   }
}elseif($_GET['band']==9){
   $post_data = file_get_contents('php://input'); 
   $list_record = json_decode($post_data, true);
   $idxid = ENCR::descript($list_record['variable']);
   $recid = ENCR::descript($list_record['idDocumentoLiquidacion']);
   $select_liquidador = $list_record['select_liquidador'];
   if($select_liquidador==-1){
      $sql_id = "SELECT * FROM DocumentoMaquinaria WHERE idDocumento='$recid'";
      $res_id = sqlsrv_query($conn,$sql_id);
      while($aa = sqlsrv_fetch_array($res_id)){
         $idEmpresa_ctrl=$aa['idEmpresa'];
         $idProveedor_ctrl=$aa['idProveedor'];
         $idEquipo_ctrl=$aa['idEquipo'];
      }
      //$json=0;
      $sql_delete="DELETE FROM DescuentoMaquinaria WHERE idxid='$idxid'";
   }else{
      $sql_id = "SELECT * FROM DocumentoLiquidaciones WHERE idDocumentoLiquidacion='$recid'";
      $res_id = sqlsrv_query($conn,$sql_id);
      while($aa = sqlsrv_fetch_array($res_id)){
         $idLiquidacion_ctrl=$aa['idLiquidacion'];
         $idReporte_ctrl=$aa['idReporte'];
         $idProveedor_ctrl=$aa['idProveedor'];
      }
      //$json=0;
      $sql_delete="DELETE FROM DocumentoLiquidacionDescuentos WHERE idxid='$idxid'";
   }
   $res_delete=sqlsrv_query($conn,$sql_delete);
   if($res_delete){
      $json=1;
   }
}elseif($_GET['band']==10){
   $post_data = file_get_contents('php://input'); 
   $list_record = json_decode($post_data, true);
   $idLiquidacion = ($list_record['select_liquidador']);
   $idProveedor = ENCR::descript($list_record['proveedor']);
   $variable = ENCR::descript($list_record['variable']);
   $idEmpresa = ENCR::descript($list_record['empresa']);
   if($idLiquidacion==-1){
      $sql= "SELECT * FROM DocumentoMaquinaria WHERE idEquipo='$variable' AND idProveedor='$idProveedor' AND idEmpresa='$idEmpresa'";
      $resultado=sqlsrv_query($conn,($sql),$params,$options);
      $rows=sqlsrv_num_rows($resultado);
      $json=0;
      if($rows==0){
         $insert="INSERT INTO DocumentoMaquinaria (idDocumento,idEquipo,idProveedor,Vigente,idEmpresa) VALUES (NEWID(),'$variable','$idProveedor',1,'$idEmpresa')";
         $res = sqlsrv_query($conn,$insert);
         if($res){
            $json=1;
         }
      }
   }else{
      $sql= "SELECT * FROM DocumentoLiquidaciones WHERE idLiquidacion='$idLiquidacion' AND idReporte='$variable' AND idProveedor='$idProveedor' AND idEmpresa='$idEmpresa'";
      $resultado=sqlsrv_query($conn,($sql),$params,$options);
      $rows=sqlsrv_num_rows($resultado);
      $json=0;
      if($rows==0){
         $insert="INSERT INTO DocumentoLiquidaciones (idDocumentoLiquidacion,idLiquidacion,idReporte,idProveedor,Vigente,idEmpresa) VALUES (NEWID(),'$idLiquidacion','$variable','$idProveedor',1,'$idEmpresa')";
         $res = sqlsrv_query($conn,$insert);
         if($res){
            $json=1;
         }
      }
   }
}elseif($_GET['band']==11){
   $post_data = file_get_contents('php://input'); 
   $list_record = json_decode($post_data, true);
   $proveedor = ENCR::descript($list_record['proveedor']);
   $empresa = ENCR::descript($list_record['empresa']);
   $json='<option selected="true" disabled="">--- Seleccione ---</option>';
   $sql = "SELECT * FROM Equipos WHERE idPropietario='$proveedor' AND clase_equipo='7A975CD6-2672-430D-B29E-7149A03D9410' 
      AND idEquipo NOT IN (SELECT idEquipo FROM DocumentoMaquinaria WHERE Vigente=1 AND idEmpresa='$empresa')";
   $resultado=sqlsrv_query($conn,$sql,$params,$options);
   $filas=sqlsrv_num_rows($resultado);
   if($filas>0){
      while($aa = sqlsrv_fetch_array($resultado)){
         $json.='<option value="'.ENCR::encript($aa['idEquipo']).'">'.utf8_encode($aa['Descripcion'].' '.$aa['Identificacion']).'</option>';
      }
   }
}
echo $json;
?>