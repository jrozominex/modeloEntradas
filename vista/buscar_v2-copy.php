<?php
require_once '../modelo/conexion.php';
include ("../../clase_encrip.php");
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
if($_POST['band']==0){ // CARGA LA VISTA PARA EL MODULO DE CLASIFICACION
    //<button type="button" class="btn btn-primary" id="descargar" disabled onclick="descargar_informe()"> EXCEL</button>
	$usuario = $_SESSION['usuario'];
	$lugar_trabajo = $_POST['lugar_trabajo'];
	$echo = '';
	$txt_array_empresa = implode("','",$_SESSION['Array_empresa']['CLASIFICACION']);
	if(isset($_SESSION['Array_empresa']['CLASIFICACION'])){
	$echo.= '<div class="container" id="div_registro_clasif">
        <div class="row" id="div">';
                $sql_empresa = "SELECT idProveedor, Alias FROM Proveedores WHERE Empresa=1 AND idProveedor IN ('$txt_array_empresa') ORDER BY Alias";
                $resul_empresa = sqlsrv_query($conn,$sql_empresa);

                $sql_recibo= "SELECT ISNULL(MAX(num_recibo),0) AS numero FROM servicio_clasificacion";
                $resul_recibo = sqlsrv_query($conn,$sql_recibo);
                while($rows = sqlsrv_fetch_array($resul_recibo)){
                   $numero=$rows['numero']+1; }
                $slq_proveedores="SELECT Proveedores.RazonSocial, Proveedores.idProveedor FROM Equipos 
                    INNER JOIN Proveedores ON Equipos.idPropietario=Proveedores.idProveedor
                    INNER JOIN equiposactividad ON Equipos.idEquipo=equiposactividad.idEquipo
                    --WHERE clase_equipo IN ('B91B4F78-EF10-4406-A941-2D1DF812C783','569D8AD0-A401-4AFE-BD52-A91974C7D2B0')
                    GROUP BY Proveedores.RazonSocial, Proveedores.idProveedor";
                $resul_proveedores = sqlsrv_query($conn,$slq_proveedores);                    

                $sql_clasificacion="SELECT * FROM Productos ORDER BY Descripcion";
                $resul_clasificacion = sqlsrv_query($conn,$sql_clasificacion);

                $sql_actividad="SELECT idActividad, Descripcion from Actividades where idTipoActividad='00000000-0000-0000-0000-000000000007'";
                $resul_actividad = sqlsrv_query($conn,$sql_actividad);

                
        $echo.='<div class="col-sm-12" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; margin-bottom: 8px;">
                <input type="hidden" id="numerotransaccion" value="0">
                <center>
                    <input type="hidden" id="recibo" value="<?php echo $numero; ?>">
                    <label><h5><b>Recibo N° '.$numero.'</b></h5></label>
                </center>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-2"><h6>Fecha: </h6></div>
                            <div class="col-sm-4"><input type="date" id="fecha" class="form-control input-sm block" onchange="periodos()" max="'.date('Y-m-d').'"></div>
                            <div class="col-sm-2" style="margin-left: 25px;"><h6>Semana: </h6></div>
                            <div class="col-sm-3"><input type="text" id="semana" class="form-control input-sm" readonly=""></div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-3"><h6>Empresa: </h6></div>
                            <div class="col-sm-8">
                                <select id="empresa" class="form-control block" onchange="load_pila_consumo()">
                                    <option value="0" selected disabled>Seleccione</option>';
                                    while($rows = sqlsrv_fetch_array($resul_empresa)){
                                        $id_empresa=$rows['idProveedor'];
                                        $nom_empresa=$rows['Alias'];
                                        $echo.='<option value="'.$id_empresa.'">'.$nom_empresa.'</option>';
                                    }
                        $echo.='</select>
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-3"><h6>Proveedor Equipo: </h6></div>
                            <div class="col-sm-8">
                                <select id="proveedor" class="form-control block" onchange="load_actividad()">
                                    <option value="0" selected disabled>Seleccione</option>';
                                    while($rows = sqlsrv_fetch_array($resul_proveedores)){
                                        $idProveedor=$rows['idProveedor'];
                                        $nom_proveedor=utf8_encode($rows['RazonSocial']);
                                        $echo.='<option value="'.$idProveedor.'">'.$nom_proveedor.'</option>';
                                    }
                        $echo.='</select> 
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <input type="hidden" id="usuario" value="'.$usuario.'">
                        
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-3"><h6>Activdad: </h6></div>
                             <div class="col-sm-8">
                                <select id="actividad" class="form-control block" onchange="load_clase_equipo()">
                                    <option value="0" selected disabled>Seleccione</option>
                                </select> 
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-3"><h6>Clase Equipo: </h6></div>
                             <div class="col-sm-8">
                                <input type="hidden" name="input_tipo_maquina" id="input_tipo_maquina" value="">
                                <select id="tipo_maquinaria" class="form-control block" onchange="llenar_sel_equipo();">
                                    <option value="0" selected disabled>Seleccione</option>
                                </select> 
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                            <div class="col-sm-3"><h6>Equipo: </h6></div>
                            <div class="col-sm-8">
                                <select id="Equipo" class="form-control block">
                                    <option value="0" selected disabled>Seleccione</option>
                                </select> 
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3"><h6>Grupo material: </h6></div>
                            <div class="col-sm-8">
                                <select id="grupo_material" class="form-control block" onchange="llenar_sel_material()">
                                    <option value="0" selected disabled>Seleccione</option>';
                                    while($rows = sqlsrv_fetch_array($resul_clasificacion)){
                                        $idClasificacion1=$rows['idProducto'];
                                        $nom_clasiif1=utf8_encode($rows['Descripcion']);
                                        $echo.='<option value="'.$idClasificacion1.'">'.$nom_clasiif1.'</option>';
                                    }
                        $echo.='</select> 
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3"><h6>Material Alimen: </h6></div>
                            <div class="col-sm-8">
                                <select id="material_alimentado" class="form-control block" onchange="load_material_producido(\'0\'); load_pila_consumo()">
                                    <option value="0" selected disabled>Seleccione</option>
                                </select> 
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3"><h6>Material Objetivo: </h6></div>
                            <div class="col-sm-8">
                                <select id="material_objetivo" class="form-control block">
                                    <option value="0" selected disabled>Seleccione</option>
                                </select> 
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3"><h6>Zona:</h6></div>
                            <div class="col-sm-8">
                                <select id="zona" class="form-control block" onchange="load_pila_consumo()">';
                                    $sql_zona = "SELECT * FROM DestinoZonas WHERE idDestino='$lugar_trabajo'";
                                    $params = array();
                                    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);     
                                    $resultado=sqlsrv_query($conn,$sql_zona,$params,$options);
                                    $filas=sqlsrv_num_rows($resultado);
                                    if($filas>0){
                                        $echo.='<option value="0" selected disabled>Seleccione</option>';
                                        while($aa = sqlsrv_fetch_array($resultado)){
                                            $echo.='<option value="'.$aa['idZona'].'">'.utf8_encode($aa['Zona']).'</option>';
                                        }
                                    }else{
                                        $echo.='<option value="0" selected disabled>No hay zonas</option>';
                                    }
                                $echo.='</select> 
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3"><h6>Pila consumo:</h6></div>
                            <div class="col-sm-8">
                                <select class="form-control block" id="pila">
                                    <option value="0" selected disabled>Seleccione</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3"><h6>Horas Equipo.:</h6></div>
                            <div class="col-sm-8"><input type="number" id="horas_alimen" class="form-control block"></div>
                        </div>
                        <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3"><h6>Toneladas Alimen.:</h6></div>
                            <div class="col-sm-8"><input type="number" id="tm_alimen" class="form-control block"></div>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
            <div class="col-sm-12" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; margin-bottom: 8px;">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="row" style="margin-top: 15px;">
                            <div class="col-sm-5"><h6>Material Producido: </h6></div>
                            <div class="col-sm-7">
                                <select id="materia_prod" class="form-control">
                                    <option value="0" selected="" disabled=""> Seleccione</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-5"><h6>Toneladas :</h6></div>
                             <div class="col-sm-7"><input type="number" id="tm_producido" class="form-control"> </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-12">
                                <center>
                                    <button id="Nuevo_reg" class="btn btn-primary"  onclick="material_producido(1)">Nuevo Producto 
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </center>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="col-sm-4" style="margin-top: 15px;">
                        <form name="formulario" id="formulario" action="" method="POST">
                            <center>
                                <input type="hidden" id="tm_acumulado1" value="0">
                                <label>Material Producido</label>
                                <div class="row">
                                    <div class="col-sm-5" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; background-color: #33C7A8"> <center><b>Material</b></center></div>
                                    <div class="col-sm-3" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; background-color: #33C7A8"> <center>Toneladas</b></center></div>
                                    <div class="col-sm-4" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; background-color: #33C7A8"> <center><b>Rendimiento</b></center></div>
                                </div>
                                <div id="div_material_prod"></div>
                                <div class="row">
                                    <div class="col-sm-5" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; background-color: #33C7A8"> <center></center></div>
                                    <div class="col-sm-3" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; background-color: #33C7A8"> <center><b id="tm_acumulado"></b></center></div>
                                    <div class="col-sm-4" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; background-color: #33C7A8"> <center><b id="porcen_acumulado"></b></center></div>
                                </div>
                            </center>
                        </form>
                    </div>
                    <div class="col-sm-4" id="div_modificar_prod" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px;">
                        <div class="row" style="margin-top: 15px;">                       
                            <div class="col-sm-5"><h6>Material Producido: </h6></div>
                            <div class="col-sm-7">
                                <select id="materia_prod_mod" class="form-control">
                                    <option value="0"> Seleccione</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-5"><h6>Toneladas :</h6></div>
                             <div class="col-sm-7"><input type="number" id="tm_producido_mod" class="form-control"><input type="hidden" id="tm_producido_mod_1" readonly=""></div>                     
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-12">
                                <b style="color: red">Nota:</b>
                                <h6 style="color: red"> Para eliminar un material producido, se coloca <b>0</b> en las toneladas y dar click en Modificar Producto</h6>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-12">
                                <input type="hidden" id="id_detalle_mod">
                                <center>
                                    <button id="Nuevo_reg_mod" class="btn btn-warning"  onclick="modificar_producido()">Modificar Producto 
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </center>
                            </div>
                        </div>
                        <br>
                    </div>           
                </div>                
            </div>
        </div>
            <div class="row" style="margin-top: 5px;">
                <div class="col-sm-12">
                    <center>
                        <button id="guardar" class="btn btn-success" onclick="guardar_registro()" disabled="">Guardar registro <span class="glyphicon glyphicon-save"></span></button>
                    </center>
                </div>
            </div>
        </div>
    </div><br>';
	}
    $echo.='<div id="div_consulta_clasif">
    	<div class="row">
    		<div class="col-sm-1"></div>
    		<div class="col-sm-10" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; margin-bottom: 8px;">
        		<center>
            		<label><h5><b>Consultas</b></h5></label>
        		</center>
        		<div class="row">
        		<form name="form1" id="form1">
            		<div class="col-sm-6">
                		<div class="row">
                    		<div class="col-sm-2"><h6>Fecha desde: </h6></div>';
                    		$SQL_DATE = "SELECT ISNULL(MIN(fecha),'1900-01-01') AS Fecha FROM servicio_clasificacion 
                    			WHERE patio='$lugar_trabajo'";
                    		$res_DATE = sqlsrv_query($conn,$SQL_DATE);
                    		while($aa = sqlsrv_fetch_array($res_DATE)){
                    			$fecha_min = date_format($aa['Fecha'],'Y-m-d');
                    		}
                    $echo.='<div class="col-sm-4"><input type="date" id="fecha_1" name="fecha_1" class="form-control input-sm" min="'.$fecha_min.'" max="'.date('Y-m-d').'"></div>
                    		<div class="col-sm-2"><h6>Fecha hasta: </h6></div>
                    		<div class="col-sm-4"><input type="date" id="fecha_2" name="fecha_2" class="form-control  input-sm" min="'.$fecha_min.'" max="'.date('Y-m-d').'"></div>
                		</div>
                		<div class="row" style="margin-top: 10px">
                    		<div class="col-sm-3"><h6>Empresa: </h6></div>
                    		<div class="col-sm-8">
                         		<input type="hidden" name="input_empresa" id="input_empresa_1" value="">
                        		<select id="empresa_1" name="empresa_1" class="form-control" onchange="activar_botton();">
                            		<option value="0" selected disabled>Seleccione</option>';
                            		$SQL_EMP = "SELECT Proveedores.idProveedor,Proveedores.Alias FROM servicio_clasificacion 
                        				INNER JOIN Proveedores ON servicio_clasificacion.empresa=Proveedores.idProveedor 
                        			WHERE servicio_clasificacion.patio='$lugar_trabajo' AND Proveedores.idProveedor IN ('$txt_array_empresa')
                        			GROUP BY Proveedores.idProveedor,Proveedores.Alias";
                            		$resul_EMP=sqlsrv_query($conn,$SQL_EMP,$params,$options);
								    $rows_EMP = sqlsrv_num_rows($resul_EMP);
								    if($rows_EMP>0){
								    	while($aa = sqlsrv_fetch_array($resul_EMP)){
								    		$id_empresa=$aa['idProveedor'];
	                                		$nom_empresa=utf8_encode($aa['Alias']);
	                                		$echo.='<option value="'.$id_empresa.'">'.$nom_empresa.'</option>';	
								    	}
								    }else{
								    	$echo.='<option value="-1">No hay registros</option>';
								    }
                        $echo.='</select>
                    		</div>
                    		<div class="col-sm-1"></div>
                		</div>
                		<div class="row" style="margin-top: 10px">
                    		<div class="col-sm-3"><h6>Proveedor: </h6></div>
                    		<div class="col-sm-8">
                        		<select id="proveedor_1" name="proveedor_1" class="form-control">
                            		<option value="0">Todos</option>';
                            		$SQL_PROVEE = "SELECT Proveedores.idProveedor, Proveedores.RazonSocial 
									FROM servicio_clasificacion
										INNER JOIN Proveedores ON servicio_clasificacion.proveedor=Proveedores.idProveedor
									WHERE servicio_clasificacion.patio='$lugar_trabajo'
									GROUP BY Proveedores.idProveedor, Proveedores.RazonSocial 
									ORDER BY Proveedores.RazonSocial";
									$resul_PROVEE=sqlsrv_query($conn,$SQL_PROVEE,$params,$options);
								    $rows_PROVEE = sqlsrv_num_rows($resul_PROVEE);
								    if($rows_PROVEE>0){
			                            while($rows = sqlsrv_fetch_array($resul_PROVEE)){
			                                $idProveedor=$rows['idProveedor'];
			                                $nom_proveedor=utf8_encode($rows['RazonSocial']);
			                                $echo.='<option value="'.$idProveedor.'">'.$nom_proveedor.'</option>';
			                            }
			                        }else{
			                        	$echo.='<option value="-1">No hay registros</option>';
			                        }
		                $echo.='</select>
		                    </div>
		                    <div class="col-sm-1"></div>
		                </div>';
		                $echo.='
		                <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
		                    <div class="col-sm-3"><h6>Activdad: </h6></div>
		                     <div class="col-sm-8">
		                        <select id="actividad_1" name="actividad_1" class="form-control">
		                            <option value="0">Todos</option>';
		                            $SQL_ACT = "SELECT Actividades.idActividad, Actividades.Descripcion FROM servicio_clasificacion
										INNER JOIN Actividades ON servicio_clasificacion.actividad=Actividades.idActividad
									WHERE servicio_clasificacion.patio='$lugar_trabajo'
									GROUP BY Actividades.idActividad, Actividades.Descripcion ORDER BY Actividades.Descripcion";
									$resul_ACT=sqlsrv_query($conn,$SQL_ACT,$params,$options);
								    $rows_ACT = sqlsrv_num_rows($resul_ACT);
								    if($rows_ACT>0){
				                        while($rows = sqlsrv_fetch_array($resul_ACT)){   
				                            $idActividad=$rows['idActividad'];
				                            $nom_actividad=utf8_encode($rows['Descripcion']);
				                            $echo.='<option value="'.$idActividad.'">'.$nom_actividad.'</option>';
				                        }
				                    }else{
				                    	$echo.='<option value="-1">No hay registros</option>';
				                    }
		                $echo.='</select> 
		                    </div>
		                </div>
		            </div>
		            <div class="col-sm-6">
		                <div class="row">
		                    <div class="col-sm-1"></div>
		                    <div class="col-sm-3"><h6>Clase Equipo: </h6></div>
		                    <div class="col-sm-8">
		                        <input type="hidden" name="input_tipo_maquina_1" id="input_tipo_maquina_1" value="">
		                        <select id="tipo_maquinaria_1" name="tipo_maquinaria_1" class="form-control" onchange=" llenar_sel_equipo_consulta();">
		                            <option value="0">Todos</option>';
		                            $SQL_CLASE_EQUIPO = "SELECT EquiposGrupos.idGrupo,EquiposGrupos.Descripcion FROM servicio_clasificacion
										INNER JOIN Equipos ON servicio_clasificacion.equipo=Equipos.idEquipo
										INNER JOIN EquiposGrupos ON Equipos.clase_equipo=EquiposGrupos.idGrupo
									WHERE servicio_clasificacion.patio='$lugar_trabajo'
									GROUP BY EquiposGrupos.idGrupo,EquiposGrupos.Descripcion ORDER BY EquiposGrupos.Descripcion";
									$resul_CLASE_EQUIPO=sqlsrv_query($conn,$SQL_CLASE_EQUIPO,$params,$options);
								    $rows_CLASE_EQUIPO = sqlsrv_num_rows($resul_CLASE_EQUIPO);
								    if($rows_CLASE_EQUIPO>0){
			                            while($rows = sqlsrv_fetch_array($resul_CLASE_EQUIPO)){
			                               $idGrupo=$rows['idGrupo'];
			                                $nom_grupo=utf8_encode($rows['Descripcion']);
			                                $echo.='<option value="'.$idGrupo.'">'.$nom_grupo.'</option>';
			                            }
			                        }else{
			                        	$echo.='<option value="-1">No hay registros</option>';
			                        }
		                $echo.='</select> 
		                    </div>
		                </div>
		                <div class="row" style="margin-top: 10px">
		                    <div class="col-sm-1"></div>
		                    <div class="col-sm-3"><h6>Equipo: </h6></div>
		                    <div class="col-sm-8">
		                        <select id="Equipo_1" name="Equipo_1" class="form-control">
		                            <option value="0">Todos</option>
		                        </select> 
		                    </div>
		                </div>
		                <div class="row" style="margin-top: 10px">
		                    <div class="col-sm-1"></div>
		                    <div class="col-sm-3"><h6>Grupo material: </h6></div>
		                    <div class="col-sm-8">
		                        <select id="grupo_material_1" name="grupo_material_1" class="form-control" onchange="llenar_sel_material_consul()">
		                            <option value="0">Todos</option>';
		                            $SQL_MATERIAL = "SELECT Productos.idProducto, Productos.Descripcion FROM servicio_clasificacion
										INNER JOIN Clasificacion ON servicio_clasificacion.material=Clasificacion.idClasificacion
										INNER JOIN Productos ON Clasificacion.idProducto=Productos.idProducto
									WHERE servicio_clasificacion.patio='$lugar_trabajo'
									GROUP BY Productos.idProducto, Productos.Descripcion ORDER BY Productos.Descripcion";
									$resul_MATERIAL=sqlsrv_query($conn,$SQL_MATERIAL,$params,$options);
								    $rows_MATERIAL = sqlsrv_num_rows($resul_MATERIAL);
								    if($rows_MATERIAL>0){
			                            while($rows = sqlsrv_fetch_array($resul_MATERIAL)){
			                                $idClasificacion1=$rows['idProducto'];
			                                $nom_clasiif1=utf8_encode($rows['Descripcion']);
			                                $echo.='<option value="'.$idClasificacion1.'">'.$nom_clasiif1.'</option>';
			                            }
			                        }else{
			                        	$echo.='<option value="-1">No hay registros</option>';
			                        }
		                $echo.='</select> 
		                    </div>
		                </div>
		                <div class="row" style="margin-top: 10px">
		                    <div class="col-sm-1"></div>
		                    <div class="col-sm-3"><h6>Material Alimen: </h6></div>
		                    <div class="col-sm-8">
		                        <select id="material_alimentado_1" name="material_alimentado_1" class="form-control">
		                            <option value="0">Todos</option>
		                        </select> 
		                    </div>
		                </div>
		                <div class="row" style="margin-top: 10px">
		                    <div class="col-sm-8"></div>
		                    <div class="col-sm-4">
		                        <button type="button" class="btn btn-primary" id="generar" disabled onclick="generar_informe()"> Buscar</button>
		                        <input type="hidden" name="band" id="band" value="100">   
		                        <input type="hidden" name="patio_1" id="patio_1" value="'.$lugar_trabajo.'">                    
		                    </div>
		                </div>           
		            </div>
		        </form>
		        </div>
    		</div><br>
		</div>
		</div>
		<div id="div_resultados"></div>
	</div>';
    echo $echo;
}elseif($_POST['band']==1){ //CARGA LOS PROCESOS POR EL CENTRO DE TRABAJO (BARRA GRIS DE ACTIVIDADES)
	$lugar_trabajo = $_POST['lugar_trabajo'];
	$echo_informativo = '';
	$echo_menu = '<div class="navbar-header">
		    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex2-collapse">
		      <span class="sr-only">Desplegar navegación</span>
		      <span class="icon-bar"></span>
		      <span class="icon-bar"></span>
		      <span class="icon-bar"></span>
		    </button>
		  </div>
		  <div class="collapse navbar-collapse navbar-ex2-collapse">
		    <ul class="nav navbar-nav">';
    if($lugar_trabajo==-1){
        $echo_menu.='<li><a href="#" onclick="load_report(\'cargador\')" class="cargador onclick_report" data-toggle="collapse" data-target=".navbar-ex2-collapse"><span class="glyphicon glyphicon-list-alt"></span> Informe Cargadores</a></li>';
        $echo_menu.='<li><a href="#" onclick="load_report(\'producción\')" class="producción onclick_report" data-toggle="collapse" data-target=".navbar-ex2-collapse"><span class="glyphicon glyphicon-list-alt"></span> Informe Producción</a></li>';
        //$echo_informativo.='</div>';
        $echo_menu.='</ul></div>';
    }else{
        $SQL = "SELECT * FROM vActividades_cargadores_destinos WHERE idDestino='$lugar_trabajo' AND Produccion=1 ORDER BY Actividad DESC";
        $resul=sqlsrv_query($conn,$SQL,$params,$options);
        $rows = sqlsrv_num_rows($resul);
        if($rows>0){
        	$var_pasa = 0;
            while($aa = sqlsrv_fetch_array($resul)){
                $idDestino = $aa['idDestino'];
                $idActividad = $aa['idActividad'];
                if($idActividad=='4D2E8879-C5C8-4503-B33A-C0101FE1E1A0'){
                	$echo_menu.='<li><a href="#" onclick="cargar_menu_body(2,0)" data-toggle="collapse" data-target=".navbar-ex2-collapse"><span class="glyphicon glyphicon-fire"></span> Coquización</a></li>';
                	$echo_menu.='<li><a href="#" onclick="cargar_menu_body(3,0)" data-toggle="collapse" data-target=".navbar-ex2-collapse"><span class="glyphicon glyphicon-cog"></span> Estructura Hornos</a></li>';
                }elseif($idActividad=='DFE61B95-C48C-46A9-8695-8B1A4720E4A1'){
                	$echo_menu.='<li><a href="#" onclick="cargar_menu_body(5,0)" data-toggle="collapse" data-target=".navbar-ex2-collapse"><span class="glyphicon glyphicon-oil"></span> Preparación mezclas</a></li>';
                }elseif($idActividad=='404D205C-7D03-4E84-9AC1-03EE5A8D83F9' || $idActividad=='0FFB9BAF-3DB6-47B2-8E7F-272D2C118AD0'){
                	if($var_pasa==0){
                		$var_pasa = 1;
                		$echo_menu.='<li><a href="#" onclick="cargar_menu_body(0,0)" data-toggle="collapse" data-target=".navbar-ex2-collapse"><span class="glyphicon glyphicon-copyright-mark"></span> Clasificación y Molienda</a></li>';
                	}
                }
            }
            $echo_menu.='<li><a href="#" onclick="cargar_menu_body(4,0)" data-toggle="collapse" data-target=".navbar-ex2-collapse"><span class="glyphicon glyphicon-tent"></span> Inventarios</a></li>';
            $echo_menu.='<li><a href="#" onclick="cargar_menu_body(32,0)" data-toggle="collapse" data-target=".navbar-ex2-collapse"><span class="glyphicon glyphicon-hourglass"></span> Cargadores</a></li>';
        }else{
            $idDestino = '0';
        }
        $echo_informativo = '<div class="col-sm-12">
    		<div class="row">
    			<button class="btn btn-xs btn-secondary"></button>&nbsp;Deshornado&nbsp;
    			<button class="btn btn-xs btn-warning"></button>&nbsp;En producción&nbsp;
    			<button class="btn btn-xs btn-danger"></button>&nbsp;Sobreproducción&nbsp;
                <button class="btn btn-xs" style="background-color: #639B39;"></button>&nbsp;Mantenimiento&nbsp;
    			<input type="hidden" id="checked_hornos" name="checked_hornos">
    		</div>';
    	if(isset($_SESSION['Array_empresa']['COQUIZACION'])){
    		$echo_informativo.='<button class="btn btn-xs btn-primary" style="float: right;" id="btn_abrir_alimentacion" onclick="abrir_modal_alimentacion()">Abrir Alimentación</button>&nbsp;
    			<button class="btn btn-xs btn-primary" style="float: right;" id="btn_abrir_produccion" onclick="abrir_modal_produccion()">Abrir Deshorne</button>&nbsp;';
    	}
    	$echo_informativo.='</div>';
        $echo_menu.='</ul></div>';
    }
    echo $idDestino.'||'.$echo_menu.'||'.$echo_informativo;

}elseif($_POST['band']==2){ //CARGA EL MODULO DE COQUIZACION
	$lugar_trabajo = $_POST['lugar_trabajo'];
	$txt_baterias = '';
	$SQL_PLANTAS = "SELECT * FROM Plantas WHERE idDestinoAsociado='$lugar_trabajo'";
	$resul_plantas=sqlsrv_query($conn,$SQL_PLANTAS,$params,$options);
    $rows_plantas = sqlsrv_num_rows($resul_plantas);
	if($rows_plantas>0){
		while($data_plantas = sqlsrv_fetch_array($resul_plantas)){
			$idPlanta = $data_plantas['idDestino'];
		}
        if(isset($_SESSION['Array_empresa']['ELIMINAR_COQUIZACION'])){
            $txt_only_hornos = "";
        }else{
            $txt_only_hornos = "AND Ajuste=0";
        }
		$SQL_BATERIAS = "SELECT * FROM Baterias WHERE idPlanta='$idPlanta' $txt_only_hornos ORDER BY NumBateria,Descripcion";
		$resul_baterias=sqlsrv_query($conn,$SQL_BATERIAS,$params,$options);
	    $rows_baterias = sqlsrv_num_rows($resul_baterias);
		if($rows_baterias>0){
			$txt_baterias.='<center><span style="text-decoration: underline;" id="cant_hornos_selected">Hornos seleccionados: 0</span></center><form id="form"  method="POST">';
			while($data_baterias = sqlsrv_fetch_array($resul_baterias)){
				$idBateria = $data_baterias['idBateria'];
				$NombreBateria = utf8_encode($data_baterias['Descripcion']);
				$SQL_HORNOS = "SELECT * FROM Hornos WHERE idBateria='$idBateria' $txt_only_hornos ORDER BY NumHorno";
				$resul_hornos=sqlsrv_query($conn,$SQL_HORNOS,$params,$options);
			    $rows_hornos = sqlsrv_num_rows($resul_hornos);
			    $rowspan_bateria = number_format(($rows_hornos/2),0);
				if($rowspan_bateria<1){
					$rowspan_bateria=1;
				}
				$count_hornos = 0;
				$txt_baterias.='
				<div class="row">
					<div class="col-sm-12"><b> BATERIA - '.$NombreBateria.' </b>
				    	<div class="table-responsive">
				    		<!--<button type="button" class="btn btn-xs" onclick="seleccionar_pares(\''.$NombreBateria.'\')"><span class="glyphicon glyphicon-list"></span> Pares</button>
				    		<button type="button" class="btn btn-xs" onclick="seleccionar_impares(\''.$NombreBateria.'\')"><span class="glyphicon glyphicon-list"></span> Impares</button>-->
				    		<center><table class="table table-hover table-condensed table-bordered table-responsive table-striped">
				    			<tr>';
				while($data_hornos = sqlsrv_fetch_array($resul_hornos)){
					$idHorno= $data_hornos['idHorno'];
					$NombreHorno = utf8_encode($data_hornos['NumHorno']);
                    $AliasHorno = utf8_encode($data_hornos['Alias']);
					$indice_carga = number_format($data_hornos['Capacidad'],2);
					$indice_deshorne = number_format($data_hornos['CapacidadDeshorne'],2);
					$tipoHorno = '';
					$tiempo_coquizacion = $data_hornos['Jornada'];
                    $iEstado = $data_hornos['iEstado'];
					$css= '';
					if ($NombreHorno%2==0){
					    $tipoHorno = 'par';
					}else{
						$tipoHorno = 'impar';
					}
					$txt_alimentacion = '';
					$SQL_ALIMENTACION = "SELECT Alimentacion_hornos_recetas.* FROM Alimentacion_hornos_recetas 
                    LEFT JOIN Produccion_hornos_recetas ON Alimentacion_hornos_recetas.idAlimentacion_hornos=Produccion_hornos_recetas.idAlimentacion_hornos
                    WHERE Alimentacion_hornos_recetas.idHorno='$idHorno' AND Produccion_hornos_recetas.idProduccion_hornos IS NULL
                    ORDER BY FechaAlimentacion";
					$resul_alimentacion=sqlsrv_query($conn,$SQL_ALIMENTACION,$params,$options);
			    	$rows_alimentacion = sqlsrv_num_rows($resul_alimentacion);
			    	$fecha_alimentacion_td = '';
			    	$hora_alimentacion_td = '';
					if($rows_alimentacion>0){
						while($aa = sqlsrv_fetch_array($resul_alimentacion)){
							$fecha_alimentacion_td = date_format($aa['FechaAlimentacion'],'d/m');
							$hora_alimentacion_td = date_format($aa['FechaAlimentacion'],'H:i');
							$date_alimentacion = date_format($aa['FechaAlimentacion'],'Y-m-d H:i:s');
							$indice_carga = number_format($aa['Indice_carga'],2);
						}
						$date_actual_numbers = strtotime($fecha_registro);
						$NuevaFechaAlimentacion = strtotime ( '+'.$tiempo_coquizacion.' hour' , strtotime ($date_alimentacion) ) ;
						//echo '<b>Horno '.$NombreHorno.'</b><br> <b>Fecha Actual: </b>'.$fecha_registro.'<br> <b>Fecha Alimentación: </b>'.$date_alimentacion.'<br>';
                        if($iEstado==2){
                            $onclick = '';
                            $css = '';
                            $style = 'style="background-color: #639B39"';
                        }else{
                            $onclick = 'onclick="marcar(\''.$idHorno.'\')"';
                            $style='';
    						if($date_actual_numbers<=$NuevaFechaAlimentacion){
    							$css = 'btn-warning';
    						}else{
    							$css = 'btn-danger';
    						}
                        }
					}else{
                        $SQL_ALIMENTACION = "SELECT TOP 1(Produccion_hornos_recetas.FechaProduccion) FROM Alimentacion_hornos_recetas
                        LEFT JOIN Produccion_hornos_recetas ON Alimentacion_hornos_recetas.idAlimentacion_hornos=Produccion_hornos_recetas.idAlimentacion_hornos
                        WHERE Alimentacion_hornos_recetas.idHorno='$idHorno' ORDER BY Produccion_hornos_recetas.FechaProduccion DESC";
                        $resul_alimentacion=sqlsrv_query($conn,$SQL_ALIMENTACION,$params,$options);
                        $rows_alimentacion = sqlsrv_num_rows($resul_alimentacion);
                        $fecha_alimentacion_td = '';
                        $hora_alimentacion_td = '';
                        if($rows_alimentacion>0){
                            while($aa = sqlsrv_fetch_array($resul_alimentacion)){
                                $fecha_alimentacion_td = date_format($aa['FechaProduccion'],'d/m');
                                $hora_alimentacion_td = date_format($aa['FechaProduccion'],'H:i');
                                $date_alimentacion = date_format($aa['FechaProduccion'],'Y-m-d H:i:s');
                                //$indice_carga = number_format($aa['Indice_carga'],2);
                            }
                        }
                        if($iEstado==2){
                            $onclick = '';
                            $css = '';
                            $style = 'style="background-color: #639B39"';
                        }else{
                            $onclick = 'onclick="marcar(\''.$idHorno.'\')"';
						    $css = 'btn-secondary';
                            $style='';
                        }
					}
					if($count_hornos <> $rowspan_bateria){
						$txt_baterias.='<td align="center" '.$onclick.' id="'.$idHorno.'_td" class="'.$tipoHorno.'_'.$NombreBateria.' '.$css.'" '.$style.'>
						<input type="checkbox" name="check" id="'.$idHorno.'-check" value="'.$idHorno.'" style="display:none" class="'.$tipoHorno.'_input_'.$NombreBateria.'">
						<a><b style="color: "># '.$AliasHorno.' </b></a><br>
						<b style="font-size: 13px; color: black; text-decoration: underline;">'.$fecha_alimentacion_td.'</b><br>
						<b style="font-size: 13px; color: black; text-decoration: underline;">'.$hora_alimentacion_td.'</b><br>
						<!--<b style="font-size: 13px">A: '.$indice_carga.'</b><br>'.$txt_alimentacion.'
						<b style="font-size: 13px">D: '.$indice_deshorne.'</b>--></td>';
					}else{
						$txt_baterias.='</tr>
							<tr><td align="center" '.$onclick.' id="'.$idHorno.'_td" class="'.$tipoHorno.'_'.$NombreBateria.' '.$css.'" '.$style.'>
							<input type="checkbox" name="check" id="'.$idHorno.'-check" value="'.$idHorno.'" style="display:none" class="'.$tipoHorno.'_input_'.$NombreBateria.'">
							<a><b style="color: "># '.$AliasHorno.' </b></a><br>
							<b style="font-size: 13px; color: black; text-decoration: underline">'.$fecha_alimentacion_td.'</b><br>
							<b style="font-size: 13px; color: black; text-decoration: underline;">'.$hora_alimentacion_td.'</b><br>
							<!--<b style="font-size: 13px">A: '.$indice_carga.'</b><br>'.$txt_alimentacion.'
							<b style="font-size: 13px">D: '.$indice_deshorne.'</b>--></td>';
					}
					$count_hornos++;
				}
				$txt_baterias.='</tr>
				    		</table></center>
				    	</div>
				    </div>
			    </div>';
			}
			$txt_baterias.='</form>';
		}
	}
	echo $txt_baterias;
}elseif($_POST['band']==3){ //CARGA EL MODULO DE ESTRUCTURA DE HORNOS
	$txt_baterias = '';
	$lugar_trabajo = $_POST['lugar_trabajo'];
	////////////////////// VARIABLES PARA VALORES //////////////////////////
	$idPlanta = '';
	$jornada_planta = '';
	$estado_planta = null;
	$idReceta = '';
	$idClasificacion = '';
	$idClasificacionProducida = '';
	///////////////////////////////////// SECCIÓN DATOS DE LA PLANTA ///////////////////////////////////////////////////
	///////////////////////////////////// SECCIÓN DATOS DE LA PLANTA ///////////////////////////////////////////////////
	$SQL_PLANTAS = "SELECT * FROM Plantas WHERE idDestinoAsociado='$lugar_trabajo'";
	$resul_plantas=sqlsrv_query($conn,$SQL_PLANTAS,$params,$options);
    $rows_plantas = sqlsrv_num_rows($resul_plantas);
	if($rows_plantas>0){
    	while($data_plantas = sqlsrv_fetch_array($resul_plantas)){
    		$idPlanta = $data_plantas['idDestino'];
    		$jornada_planta = $data_plantas['Jornada'];
    		$estado_planta = $data_plantas['Estado'];
    		$idReceta = $data_plantas['idReceta'];
    		$idClasificacion = $data_plantas['idClasificacion'];
    		$idClasificacionProducida = $data_plantas['idClasificacionProducida'];
    	}
    }
	$SQL_ESTADOS = "SELECT * FROM estadoHornos() ORDER BY Descripcion";
	$res_estados = sqlsrv_query($conn,$SQL_ESTADOS);
	$SQL_MEZCLAS = "SELECT * FROM Clasificacion WHERE Grupo='Mezclas' ORDER BY Descripcion";
	$res_mezclas = sqlsrv_query($conn,$SQL_MEZCLAS);
	///////////////////////////////////// SECCIÓN DATOS DE LA PLANTA ///////////////////////////////////////////////////
	///////////////////////////////////// SECCIÓN DATOS DE LA PLANTA ///////////////////////////////////////////////////
	$txt_baterias.='
	<div class="row" style="border-bottom: 1px solid; text-align: left;">
		<div class="col-sm-12"><label style="margin-left: 5px;">Datos Planta</label></div>
	</div><br>
	<input type="hidden" name="idPlanta" id="idPlanta" value="'.$idPlanta.'" disabled>
	<div class="row">
	  	<div class="col-xs-4 col-sm-4 col-md-2 col-lg-2">
	  		<label>Coquización (Hrs.)</label>
	      	<input type="text" name="jornada_planta" id="jornada_planta" value="'.$jornada_planta.'" class="form-control" aria-label="...">
	  	</div><!-- /.col-lg-2 -->
	  	<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
	  		<label>Estado Planta</label>
	  		<select class="form-control" name="estado_planta" id="estado_planta">
	  			<option value="" disabled selected>Seleccione</option>';
	  			while($data_estado = sqlsrv_fetch_array($res_estados)){
	  				if($estado_planta == $data_estado['iEstado']){
	  					$txt_baterias.='<option value="'.$data_estado['iEstado'].'" selected>'.utf8_encode($data_estado['Descripcion']).'</option>';
	  				}else{
	  					$txt_baterias.='<option value="'.$data_estado['iEstado'].'">'.utf8_encode($data_estado['Descripcion']).'</option>';
	  				}
	  			}
	  		$txt_baterias.='</select>
	  	</div><!-- /.col-lg-2 -->
	  	<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
	  		<label>Mezcla Alimentada</label>
	  		<select class="form-control" name="clasificacion_planta" id="clasificacion_planta" onchange="load_recetas_produccion(\'coquizacion\')">
	  			<option value="" disabled selected>Seleccione</option>';
	  			while($data_mezclas = sqlsrv_fetch_array($res_mezclas)){
	  				if($idClasificacion == $data_mezclas['idClasificacion']){
	  					$txt_baterias.='<option value="'.$data_mezclas['idClasificacion'].'" selected>'.utf8_encode($data_mezclas['Descripcion']).'</option>';
	  				}else{
	  					$txt_baterias.='<option value="'.$data_mezclas['idClasificacion'].'">'.utf8_encode($data_mezclas['Descripcion']).'</option>';
	  				}
	  			}
	  		$txt_baterias.='</select>
	  	</div><!-- /.col-lg-2 -->
	  	<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
	  		<label>Receta</label>
			<select class="form-control" id="receta_planta">';
				if($idClasificacion<>''){
					$txt_baterias.='<option value="0" disabled selected>Seleccione</option>';
					$sql = "SELECT * FROM Recetas_produccion WHERE idClasificacion='$idClasificacion' 
						ORDER BY Descripcion";
		            $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
		            $filas=sqlsrv_num_rows($resultado);
		            if($filas>0){
			            while($aa = sqlsrv_fetch_array($resultado)){
			            	if($idReceta == $aa['idReceta']){
								$txt_baterias.='<option value="'.$aa['idReceta'].'" selected>'.utf8_encode($aa['Descripcion']).'</option>';
			            	}else{
			            		if($aa['Habilitado']==1){
			            			$txt_baterias.='<option value="'.$aa['idReceta'].'">'.utf8_encode($aa['Descripcion']).'</option>';
			            		}
			            	}
						}
					}
				}else{
					$txt_baterias.='<option value="">Seleccione una clasificacion</option>';
				}
$txt_baterias.='</select>
	  	</div>
	  	<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
	  		<label>Material Producido</label>
			<select class="form-control" id="clasificacionProducida_planta">';
				$txt_baterias.='<option value="0" disabled selected>Seleccione</option>';
				$sql = "SELECT * FROM Clasificacion WHERE Grupo='Coque bruto' ORDER BY Descripcion";
	            $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	            $filas=sqlsrv_num_rows($resultado);
	            if($filas>0){
		            while($aa = sqlsrv_fetch_array($resultado)){
		            	if($idClasificacionProducida == $aa['idClasificacion']){
		            		$txt_baterias.='<option value="'.$aa['idClasificacion'].'" selected>'.utf8_encode($aa['Descripcion']).'</option>';
		            	}else{
		            		$txt_baterias.='<option value="'.$aa['idClasificacion'].'">'.utf8_encode($aa['Descripcion']).'</option>';
		            	}
					}
				}
$txt_baterias.='</select>
	  	</div>';
if(isset($_SESSION['Array_empresa']['ESTRUCTURA_HORNOS'])){
$txt_baterias.='<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
	  		<label>Guardar cambios</label><br>
	  		<button type="button" class="btn btn-success" onclick="SAVE_plantas();"><span class="glyphicon glyphicon-ok"></span></button>
	  	</div>';
}
$txt_baterias.='</div>
	<br>';
	///////////////////////////////////////////////////// SECCIÓN ESTRUCTURA DE LA PLANTA ////////////////////////////////////////////////////////
	///////////////////////////////////////////////////// SECCIÓN ESTRUCTURA DE LA PLANTA ////////////////////////////////////////////////////////
	if($idPlanta <> ''){
		$txt_baterias.='<div class="row" style="border-bottom: 1px solid; text-align: left;">
			<div class="col-sm-12"><label style="margin-left: 5px;">Estructura Planta</label></div>
		</div>';
		$SQL_BATERIAS = "SELECT * FROM Baterias WHERE idPlanta='$idPlanta' ORDER BY NumBateria,Descripcion";
		$resul_baterias=sqlsrv_query($conn,$SQL_BATERIAS,$params,$options);
		$rows_baterias = sqlsrv_num_rows($resul_baterias);
		if($rows_baterias>0){
			while($data_baterias = sqlsrv_fetch_array($resul_baterias)){
				$idBateria = $data_baterias['idBateria'];
                if(isset($_SESSION['Array_empresa']['ESTRUCTURA_HORNOS'])){
				    $NombreBateria = '<a href="#" onclick="ver_modal_creacion(\'Baterias\',\''.$idBateria.'\',\'0\')">BATERIA  '.utf8_encode($data_baterias['Descripcion']).'</a>';
                }else{
                    $NombreBateria = 'BATERIA  '.utf8_encode($data_baterias['Descripcion']);
                }

				$SQL_HORNOS = "SELECT * FROM Hornos WHERE idBateria='$idBateria' AND idPlanta='$idPlanta' ORDER BY NumHorno";
				$resul_hornos=sqlsrv_query($conn,$SQL_HORNOS,$params,$options);
				$rows_hornos = sqlsrv_num_rows($resul_hornos);
				if($rows_hornos>0){
					$rowspan_bateria = number_format(($rows_hornos/2),0);
					if($rowspan_bateria<1){
						$rowspan_bateria=1;
					}
					$count_hornos = 0;
					$txt_baterias.='
					<div class="row">
						<div class="col-sm-12">
					    	<div class="table-responsive">
					    		<b>'.$NombreBateria.' </b>';
					    		if(isset($_SESSION['Array_empresa']['ESTRUCTURA_HORNOS'])){
					    		$txt_baterias.='<button type="button" class="btn btn-warning btn-xs" onclick="edition_hornos(\''.utf8_encode($data_baterias['Descripcion']).'\')">Habilitar edición <span class="glyphicon glyphicon-pencil"></span></button>&nbsp;<button class="btn btn-xs" onclick="ver_modal_creacion(\'Hornos\',\''.$idBateria.'\',\'0\')" style="color: red"><b>CREAR HORNO</b></button>';
					    		}
					    		$txt_baterias.='<center><table class="table table-hover table-condensed table-bordered table-responsive table-striped">
					    			<tr>';

					while($data_hornos = sqlsrv_fetch_array($resul_hornos)){
						$idHorno= $data_hornos['idHorno'];
						$NombreHorno = '#'.utf8_encode($data_hornos['NumHorno']);
                        $AliasHorno = '#'.utf8_encode($data_hornos['Alias']);
						$indice_carga = number_format($data_hornos['Capacidad'],2);
						$indice_deshorne = number_format($data_hornos['CapacidadDeshorne'],2);
						if($count_hornos <> $rowspan_bateria){
							$txt_baterias.='<td style="background-color:#69BD57" align="center"><a><b style="color: white">'.$AliasHorno.' </b></a><button type="button" onclick="ver_modal_creacion(\'Hornos\',\''.$idBateria.'\',\''.$idHorno.'\')" class="btn btn-warning btn-xs btn_ocultar btn_ocultar'.$data_baterias['Descripcion'].'"><span class="glyphicon glyphicon-pencil"></span></button><br><b style="font-size: 13px">C: '.$indice_carga.'</b><br><b style="font-size: 13px">D: '.$indice_deshorne.'</b></td>';
						}else{
							$txt_baterias.='</tr>
								<tr><td style="background-color:#69BD57" align="center"><a><b style="color: white">'.$AliasHorno.' </b></a><button type="button" onclick="ver_modal_creacion(\'Hornos\',\''.$idBateria.'\',\''.$idHorno.'\')" class="btn btn-warning btn-xs btn_ocultar btn_ocultar'.$data_baterias['Descripcion'].'"><span class="glyphicon glyphicon-pencil"></span></button><br><b tyle="font-size: 13px">C: '.$indice_carga.'</b><br><b tyle="font-size: 13px">D: '.$indice_deshorne.'</b></td>';
						}
						$count_hornos++;
					}
					   $txt_baterias.='</tr>
					    		</table></center>
					    	</div>
					    </div>
				    </div>';
				}else{
					$rowspan_bateria = 2;
					$txt_baterias.='
					<div class="row">
						<div class="col-sm-1"></div>
						<div class="col-sm-6">
					    	<div class="table-responsive">
					    		<center><table class="table table-hover table-condensed table-bordered table-responsive table-striped">
					    			<tr>
					    				<td rowspan="1" align="center" style="vertical-align: middle;"><a href="#" onclick="ver_modal_creacion(\'Baterias\',\''.$idBateria.'\',\'0\')"><b style="color: green">'.$NombreBateria.'</b></a></td>
					    				<td><a href="#" onclick="ver_modal_creacion(\'Hornos\',\''.$idBateria.'\',\'0\')"><b style="color: red">CREAR HORNO</b></a></td>
					    			</tr>
					    		</table></center>
					    	</div>
					    </div>
				    </div>';
				}
			}
		}
		if(isset($_SESSION['Array_empresa']['ESTRUCTURA_HORNOS'])){
		$txt_baterias.='<div class="row">
			<div class="col-sm-1"></div>
			<div class="col-sm-6">
		    	<div class="table-responsive">
		    		<center><table class="table table-hover table-condensed table-bordered table-responsive table-striped">
		    			<tr>
		    				<td rowspan="1" align="center" style="vertical-align: middle;"><a href="#" onclick="ver_modal_creacion(\'Baterias\',\'0\',\'0\')"><b style="color: red">CREAR BATERIA</b></a></td>
		    				<td>HORNO #</td>
		    			</tr>
		    		</table></center>
		    	</div>
		    </div>
	    </div>';
		}
	    $txt_baterias.='<div class="row" style="border-bottom: 1px solid; text-align: left;">
			<div class="col-sm-12"><label style="margin-left: 5px;">Información Hornos</label></div>
		</div>
		<center><h3>Consultas</h3></center>
		<div class="row">
			<div class="col-sm-2">
				<label>Empresa</label>
				<select class="form-control" id="consulta_empresa">';
					$txt_array_empresa = implode("','",$_SESSION['Array_empresa']['COQUIZACION']);
					$sql_emp = "SELECT Proveedores.NombreCorto AS Empresa, Alimentacion_hornos_recetas.idEmpresa 
					FROM Alimentacion_hornos_recetas
						INNER JOIN Hornos ON Alimentacion_hornos_recetas.idHorno=Hornos.idHorno
						INNER JOIN Proveedores ON Alimentacion_hornos_recetas.idEmpresa=Proveedores.idProveedor
					WHERE Hornos.idPlanta='$idPlanta' AND Proveedores.idProveedor IN ('$txt_array_empresa')
					GROUP BY Proveedores.NombreCorto, Alimentacion_hornos_recetas.idEmpresa";
					$resul_emp=sqlsrv_query($conn,$sql_emp,$params,$options);
					$rows_emp = sqlsrv_num_rows($resul_emp);
					if($rows_emp>0){
						$txt_baterias.='<option value="0" selected>TODOS</option>';
						while($aa = sqlsrv_fetch_array($resul_emp)){
							$txt_baterias.='<option value="'.$aa['idEmpresa'].'">'.utf8_encode($aa['Empresa']).'</option>';
						}
					}else{
						$txt_baterias.='<option value="0" disabled selected>No hay registros</option>';
					}
$txt_baterias.='</select>
			</div>
			<div class="col-sm-2">
				<label>Clasificación Alimentada</label>
				<select class="form-control" id="consulta_clasificacion_alimentada">';
					$sql_clasif1 = "SELECT Clasificacion.Descripcion AS Clasificacion, Clasificacion.idClasificacion 
					FROM Alimentacion_hornos_recetas
						INNER JOIN Hornos ON Alimentacion_hornos_recetas.idHorno=Hornos.idHorno
						INNER JOIN Recetas_produccion ON Alimentacion_hornos_recetas.idReceta=Recetas_produccion.idReceta
						INNER JOIN Clasificacion ON Recetas_produccion.idClasificacion=Clasificacion.idClasificacion
						WHERE Hornos.idPlanta='$idPlanta'
					GROUP BY Clasificacion.Descripcion, Clasificacion.idClasificacion";
					$resul_clasif1=sqlsrv_query($conn,$sql_clasif1,$params,$options);
					$rows_clasif1 = sqlsrv_num_rows($resul_clasif1);
					if($rows_clasif1>0){
						$txt_baterias.='<option value="0" selected>TODOS</option>';
						while($aa = sqlsrv_fetch_array($resul_clasif1)){
							$txt_baterias.='<option value="'.$aa['idClasificacion'].'">'.utf8_encode($aa['Clasificacion']).'</option>';
						}
					}else{
						$txt_baterias.='<option value="0" disabled selected>No hay registros</option>';
					}
$txt_baterias.='</select>
			</div>
			<div class="col-sm-2">
                <label>Clasificación Producida</label>
                <select class="form-control" id="consulta_clasificacion_producida">';
                    $sql_clasif2 = "SELECT Clasificacion.Descripcion AS Clasificacion, Clasificacion.idClasificacion FROM Produccion_hornos_recetas 
                        INNER JOIN Clasificacion ON Produccion_hornos_recetas.idClasificacion=Clasificacion.idClasificacion
                        INNER JOIN Hornos ON Produccion_hornos_recetas.idHorno=Hornos.idHorno
                        WHERE Hornos.idPlanta='$idPlanta'
                    GROUP BY Clasificacion.Descripcion, Clasificacion.idClasificacion";
                    $resul_clasif2=sqlsrv_query($conn,$sql_clasif2,$params,$options);
                    $rows_clasif2 = sqlsrv_num_rows($resul_clasif2);
                    if($rows_clasif2>0){
                        $txt_baterias.='<option value="0" selected>TODOS</option>';
                        while($aa = sqlsrv_fetch_array($resul_clasif2)){
                            $txt_baterias.='<option value="'.$aa['idClasificacion'].'">'.utf8_encode($aa['Clasificacion']).'</option>';
                        }
                    }else{
                        $txt_baterias.='<option value="0" disabled selected>No hay registros</option>';
                    }
$txt_baterias.='</select>
            </div>
			<div class="col-sm-2">
				<label>Fecha Inicio</label>';
					$sql_date = "SELECT ISNULL(MIN(CAST(FechaAlimentacion AS date)),'1900-01-01') AS FechaAlimentacion 
					FROM Alimentacion_hornos_recetas
						INNER JOIN Hornos ON Alimentacion_hornos_recetas.idHorno=Hornos.idHorno
					WHERE Hornos.idPlanta='$idPlanta'";
					$res_date=sqlsrv_query($conn,$sql_date,$params,$options);
					while($aa = sqlsrv_fetch_array($res_date)){
						$fecha_min = date_format($aa['FechaAlimentacion'],'Y-m-d');
					}
	$txt_baterias.='<input class="form-control" id="consulta_fecha_ini" type="date" min="'.$fecha_min.'" max="'.date('Y-m-d').'">
			</div>
			<div class="col-sm-2">
				<label>Fecha Fin</label>
				<input class="form-control" id="consulta_fecha_fin" type="date" min="'.$fecha_min.'" max="'.date('Y-m-d').'">
			</div>
			<div class="col-sm-2">
				<button class="btn btn-primary" style="margin-top: 20px;" onclick="search_registers_coquizacion()">Buscar <span class="glyphicon glyphicon-search"></span></button>
			</div>
		</div>
		<div id="div_resultado_hornos"></div>
		<br><br><br>';
	}else{
		$txt_baterias.='<br><div class="row" style="border-bottom: 1px solid; text-align: left;">
			<div class="col-sm-12"><center><label style="margin-left: 5px;">Primero debes registrar la planta</label></center></div>
		</div><br>';
	}
    echo $txt_baterias;
}elseif($_POST['band']==4){ //CARGA EL MODULO DE INVENTARIOS
	$lugar_trabajo = $_POST['lugar_trabajo'];
	$sql_name = "SELECT * FROM Destino WHERE idDestino='$lugar_trabajo'";
	$resul_name=sqlsrv_query($conn,$sql_name,$params,$options);
    $rows = sqlsrv_num_rows($resul_name);
    if($rows>0){
    	while($aa = sqlsrv_fetch_array($resul_name)){
    		$name_lugar_trabajo = utf8_encode($aa['Descripcion']);
    	}
    }
    $echo = '';
    $date_min = '';
    $txt_array_empresa = implode("','",$_SESSION['Array_empresa']['INVENTARIOS']);
    $txt_array_empresa_new = "00000000-0000-0000-0000-000000000000";
    if(isset($_SESSION['Array_empresa']['REGISTRAR_INVENTARIOS'])){
        $txt_array_empresa_new = implode("','",$_SESSION['Array_empresa']['REGISTRAR_INVENTARIOS']);
    	$echo.='<div class="row">
    			<div class="col-sm-1"></div>
    			<div class="col-sm-10">
    				<center>
    					<button class="btn btn-success" onclick="load_history_inventory()">
    						Saldo Inventario <span class="glyphicon glyphicon-tent"></span>
    					</button>
    				</center>
    			</div>
    		</div><br>';
	}
	$echo.='<div class="row">
			<div class="col-xs-12 col-md-2 col-lg-1"></div>
            <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2">
				<label>Empresa</label>
				<select class="form-control" id="list_empresa_inventario">
					<!--<option value="0" disabled selected>Seleccione</option>-->';
				$sql_empresa = "SELECT Empresa,idEmpresa FROM vInventarioSaldo 
					WHERE idDestino='$lugar_trabajo' AND idEmpresa IN ('$txt_array_empresa')
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
                <select class="form-control" id="list_UnidadDeNegocio_inventario">
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
		<div class="col-xs-5 col-sm-3 col-md-2 col-lg-2">
            <label>Fecha Inicio</label>
            <input type="date" id="fechaInicio_inventario" class="form-control" max="'.date('Y-m-d').'" value="'.date('Y-m-d').'">
        </div>
        <div class="col-xs-5 col-sm-3 col-md-2 col-lg-2">
			<label>Fecha Fin</label>
			<input type="date" id="fechaFin_inventario" class="form-control" max="'.date('Y-m-d').'" value="'.date('Y-m-d').'">
		</div>
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
            <button class="btn btn-primary" id="btn_actualizar_inv" onclick="load_body_inventory()" style="margin-top: 25px;"><span class="glyphicon glyphicon-refresh"></span></button>
        </div>
		</div>
		<div class="row"><br>
			<div class="col-sm-12" id="div_inventario_body"></div>
		</div><br><br>';
	$echo.='</div></div>
	<div class="modal fade" id="modalInventoryBalance" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Saldo Inventario ('.$name_lugar_trabajo.')</h4>
                </div>
                <div class="modal-body">
                    <div style="border: 1px solid; border-radius: 5px;">
    	        		<div class="row form-group center-block">
    	        			<div class="col-sm-4"></div>
    	        			<div class="col-sm-4">
    		        			<label>Empresa</label>
    		        			<select class="form-control" id="empresa_inventario">
    		        				<option value="0" disabled selected>Seleccione</option>';
    		        				$sql_clasif = "SELECT * FROM Proveedores WHERE Empresa=1 AND idProveedor IN ('$txt_array_empresa_new') ORDER BY NombreCorto";
    							    $resul=sqlsrv_query($conn,$sql_clasif,$params,$options);
    							    $rows = sqlsrv_num_rows($resul);
    							    if($rows>0){
    							    	while($aa = sqlsrv_fetch_array($resul)){
    							    		$echo.='<option value="'.$aa['idProveedor'].'">'.utf8_encode($aa['NombreCorto']).'</option>';
    							    	}
    							    }
    		        	$echo.='</select>
    		        		</div>
    	        		</div>
    	        		<div class="row form-group center-block">
        	        		<div class="col-sm-3">
        	        			<label>Fecha Saldo</label>
        	        			<input type="date" class="form-control" min="'.$date_min.'" id="fecha_inventario">
        	        		</div>
        	        		<div class="col-sm-3">
        	        			<label>Producto</label>
        	        			<select class="form-control" id="producto_inventario" onchange="load_clasificacion_inventory()">
        	        				<option value="0" disabled selected>Seleccione</option>';
        	        				$sql_clasif = "SELECT * FROM Productos ORDER BY Descripcion";
        						    $resul=sqlsrv_query($conn,$sql_clasif,$params,$options);
        						    $rows = sqlsrv_num_rows($resul);
        						    if($rows>0){
        						    	while($aa = sqlsrv_fetch_array($resul)){
        						    		$echo.='<option value="'.$aa['idProducto'].'">'.utf8_encode($aa['Descripcion']).'</option>';
        						    	}
        						    }
        	        	$echo.='</select>
        	        		</div>
        	        		<div class="col-sm-4">
        	        			<label>Clasificación</label>
        	        			<select class="form-control" id="clasificacion_inventario">
        	        				<option value="0">Seleccione un producto</option>
        	        			</select>
        	        		</div>
        	        		<div class="col-sm-2">
        	        			<label>Saldo (TM)</label>
        	        			<input type="number" class="form-control" id="saldo_inventario">
        	        		</div>
                        </div>
                    </div>
                    <center><h4><b>Historial</b></h4></center>
                    <div id="div_history_inventory"></div>
                </div>
                <div class="modal-footer">
                	<button class="btn btn-default" data-dismiss="modal">Cerrar <span class="glyphicon glyphicon-remove"></span></button>
                    <button class="btn btn-primary" onclick="save_inventory_balance()">Guardar <span class="glyphicon glyphicon-save"></span></button>
                </div>
            </div>
        </div>
    </div>';
	echo $echo;
}elseif($_POST['band']==5){ //CARGA EL MODULO DE PREPARACION DE MEZCLA
    $echo = '';
    $lugar_trabajo_produccion = $_POST['lugar_trabajo'];
    $consu_patio = "SELECT Descripcion FROM Destino WHERE idDestino='$lugar_trabajo_produccion'";
    $res_patio = sqlsrv_query($conn,$consu_patio);
    while($aa_patio = sqlsrv_fetch_array($res_patio)){
        $nombre_patio = utf8_encode($aa_patio['Descripcion']);
    }
    $fecha_min_preparacion = "1900-01-01";
    $txt_array_empresa = implode("','", $_SESSION['Array_empresa']['PREPARACION_MEZCLA']);
    if(isset($_SESSION['Array_empresa']['PREPARACION_MEZCLA'])){
    	$echo.='<div style="border: 1px solid; margin-left: 5px; margin-right: 5px; border-radius: 5px;"><div class="row">
				<div class="col-sm-2">
					<label>Empresa</label>
					<select class="form-control" id="empresa_preparacion" onchange="load_recetas_produccion(\'preparacion_mezcla\')">
						<option value="0" disabled selected>Seleccione</option>';
						$sql = "SELECT * FROM Proveedores WHERE Empresa=1 AND idProveedor IN ('$txt_array_empresa') ORDER BY NombreCorto";
						$params = array();
			            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);     
			            $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
			            $filas=sqlsrv_num_rows($resultado);
			            while($aa = sqlsrv_fetch_array($resultado)){
							$echo.='<option value="'.$aa['idProveedor'].'">'.utf8_encode($aa['NombreCorto']).'</option>';
						}
			$echo.='</select><br>
					
				</div>
				<div class="col-sm-2">
					<label>Mezcla Producida</label>
					<select class="form-control" id="mezcla_producida" onchange="load_recetas_produccion(\'preparacion_mezcla\')">
						<option value="0" selected disabled>Seleccione</option>';
						$sql = "SELECT * FROM Clasificacion WHERE Grupo='Mezclas' ORDER BY Descripcion";
						$params = array();
			            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);     
			            $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
			            $filas=sqlsrv_num_rows($resultado);
			            while($aa = sqlsrv_fetch_array($resultado)){
							$echo.='<option value="'.$aa['idClasificacion'].'">'.utf8_encode($aa['Descripcion']).'</option>';
						}
		$echo.='	</select>
				</div>
				<div class="col-sm-2">
					<label>Fecha Preparación</label>
					<input type="date" id="fecha_preparacion" class="form-control" max="'.date('Y-m-d').'">
				</div>
                <div class="col-sm-2">
                    <label>Receta</label>
                    <select class="form-control" id="receta_preparacion" onchange="load_table_recetas_produccion()">
                        <option value="0" disabled selected>Seleccione</option>
                    </select>
                </div>
				<div class="col-sm-1">
					<label>Toneladas</label>
					<input type="number" id="toneladas_preparacion" class="form-control" onkeyup="load_table_recetas_produccion()">
				</div>
				<div class="col-sm-3" style="margin-top: 10px;" id="div_recetas_prod"><center><h4>Tabla recetas</h4></center></div>
			</div><br>
            <div class="row">
                <div class="col-sm-12">
                    <center>
                        <button type="button" class="btn btn-success" onclick="guardar_preparacion()">Guardar Preparación <span class="glyphicon glyphicon-save"></span></button>
                    </center>
                </div>
            </div></div><br>';
	}
		$echo.='<div style="border: 1px solid; margin-left: 5px; margin-right: 5px; border-radius: 5px;">
				<div class="row">
					<center><h3>Consultas</h3></center>
					<div class="col-sm-2">
						<label>Empresa</label>
						<select class="form-control" id="empresa_preparacion_serach">
							<option value="0">Todos</option>';
							$sql = "SELECT Proveedores.NombreCorto as Empresa, Proveedores.idProveedor 
								FROM Preparacion_recetas 
								INNER JOIN Proveedores ON Preparacion_recetas.idEmpresa=Proveedores.idProveedor
								WHERE Preparacion_recetas.idDestino='$lugar_trabajo_produccion' AND Proveedores.idProveedor IN ('$txt_array_empresa')
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
									WHERE Preparacion_recetas.idDestino='$lugar_trabajo_produccion'
								GROUP BY Clasificacion.idClasificacion,Clasificacion.Descripcion ORDER BY Clasificacion.Descripcion";
							$params = array();
				            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);     
				            $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
				            $filas=sqlsrv_num_rows($resultado);
				            while($aa = sqlsrv_fetch_array($resultado)){
								$echo.='<option value="'.$aa['idClasificacion'].'">'.utf8_encode($aa['Descripcion']).'</option>';
							}
			$echo.='	</select>
					</div>
					<div class="col-sm-2">
						<label>Fecha Inicio</label>';
						$sql_date = "SELECT ISNULL(MIN(CAST(FechaPreparacion AS date)),'1900-01-01') AS FechaPreparacion 
						FROM vPreparacion_recetas
						WHERE idDestino='$lugar_trabajo_produccion'";
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
}elseif($_POST['band']==6){ // CARGA EL MODAL PARA MODIFICAR BATERIAS U HORNOS EN EL MODULO ESTRUCTURA HORNOS
	$tipoCreacion = $_POST['tipoCreacion'];
	$lugar_trabajo = $_POST['lugar_trabajo'];
	$idBateria = $_POST['idBateria'];
	$idHorno = $_POST['idHorno'];
	$SQL = "SELECT * FROM Destino WHERE idDestino='$lugar_trabajo'";
	$res = sqlsrv_query($conn,utf8_decode($SQL));
	while($aa = sqlsrv_fetch_array($res)){
		$NombrePlanta = utf8_encode($aa['Descripcion']);
	}
	$SQL_PLANTAS = "SELECT * FROM Plantas WHERE idDestinoAsociado='$lugar_trabajo'";
	$resul_plantas=sqlsrv_query($conn,$SQL_PLANTAS,$params,$options);
    $rows_plantas = sqlsrv_num_rows($resul_plantas);
	if($rows_plantas>0){
		while($data_plantas = sqlsrv_fetch_array($resul_plantas)){
			$idPlanta = $data_plantas['idDestino'];
			$jornada_planta = $data_plantas['Jornada'];
			$iEstado_plantas = $data_plantas['Estado'];
			if($data_plantas['idClasificacion']<>''){
				$idClasificacion_plantas = $data_plantas['idClasificacion'];
			}
			if($data_plantas['idReceta']<>''){
				$idReceta_plantas = $data_plantas['idReceta'];
			}
		}
	}
	$jornada_bateria = $jornada_planta;
	$iEstado_baterias = $iEstado_plantas;
	$idClasificacion_baterias = $idClasificacion_plantas;
	$idReceta_baterias = $idReceta_plantas;
	$NombreBateria = '';
    $NumBateria = 0;
	if($idBateria <> '0'){
		$SQL_BATERIAS = "SELECT * FROM Baterias WHERE idBateria='$idBateria'";
		$resul_baterias=sqlsrv_query($conn,$SQL_BATERIAS,$params,$options);
		$rows_baterias = sqlsrv_num_rows($resul_baterias);
		if($rows_baterias>0){
			while ($data_baterias = sqlsrv_fetch_array($resul_baterias)){
                $NumBateria = $data_baterias['NumBateria'];
				$NombreBateria = utf8_encode($data_baterias['Descripcion']);
				$jornada_bateria = $data_baterias['Jornada'];
				$iEstado_baterias = $data_baterias['Estado'];
				if($data_baterias['idClasificacion']<>''){
					$idClasificacion_baterias = $data_baterias['idClasificacion'];
				}
				if($data_baterias['idReceta']<>''){
					$idReceta_baterias = $data_baterias['idReceta'];
				}
			}
		}
	}
	$jornada_horno = $jornada_bateria;
	$iEstado_hornos = $iEstado_baterias;
	$idClasificacion_hornos = $idClasificacion_baterias;
	$idReceta_hornos = $idReceta_baterias;
	$indice_carga = '';
	$indice_deshorne = '';
	$NombreHorno = '';
	$alias_horno = '';
	if($idHorno <> '0'){
		$SQL_HORNOS = "SELECT * FROM Hornos WHERE idHorno='$idHorno'";
		$resul_hornos=sqlsrv_query($conn,$SQL_HORNOS,$params,$options);
		$rows_hornos = sqlsrv_num_rows($resul_hornos);
		if($rows_hornos>0){
			while ($data_hornos = sqlsrv_fetch_array($resul_hornos)) {
				$NombreHorno = utf8_encode($data_hornos['NumHorno']);
				$alias_horno = utf8_encode($data_hornos['Alias']);
				$jornada_horno = $data_hornos['Jornada'];
				$iEstado_hornos = $data_hornos['iEstado'];
				if($data_hornos['idClasificacion']<>''){
					$idClasificacion_hornos = $data_hornos['idClasificacion'];
				}
				if($data_hornos['idReceta']<>''){
					$idReceta_hornos = $data_hornos['idReceta'];
				}
				$indice_carga = number_format($data_hornos['Capacidad'],2);
				$indice_deshorne = number_format($data_hornos['CapacidadDeshorne'],2);
			}
		}
	}
	$SQL_ESTADOS = "SELECT * FROM estadoHornos() ORDER BY Descripcion";
    $res_estados = sqlsrv_query($conn,$SQL_ESTADOS);
	if($tipoCreacion == 'Baterias'){
		$tittle_modal = '<b>'.$NombrePlanta.'</b> - '.'Creación de '.$tipoCreacion;	
		$body_modal = '<div class="modal-body">
	        <div class="row form-group center-block">
	        	<div class="col-sm-3">
                    <label>Posición Bateria</label>
                    <input type="text" name="num_bateria" id="num_bateria" class="form-control" placeholder="Ej: 1,2,3,4" value="'.$NumBateria.'">
                </div>
                <div class="col-sm-3">
	        		<label>Nombre</label>
	        		<input type="text" name="descripcion_bateria" id="descripcion_bateria" class="form-control" placeholder="Ej: A,B,C,etc." value="'.$NombreBateria.'">
	        	</div>
	        	<div class="col-sm-3">
	        		<label>Estado</label>
	        		<select class="form-control" id="estado_bateria">
		  			<option value="">Seleccione</option>';
		  			while($data_estado = sqlsrv_fetch_array($res_estados)){
		  				//$body_modal.='<option value="'.$data_estado['iEstado'].'">'.utf8_encode($data_estado['Descripcion']).'</option>';
		  				if($iEstado_baterias == $data_estado['iEstado']){
		  					$body_modal.='<option value="'.$data_estado['iEstado'].'" selected>'.utf8_encode($data_estado['Descripcion']).'</option>';
		  				}else{
		  					$body_modal.='<option value="'.$data_estado['iEstado'].'">'.utf8_encode($data_estado['Descripcion']).'</option>';
		  				}
		  			}
		  		$body_modal.='</select>
	        	</div>
	        	<div class="col-sm-3">
	        		<label>Jornada (Hrs.)</label>
	        		<input type="number" name="jornada_bateria" id="jornada_bateria" class="form-control" value="'.$jornada_bateria.'">
	        	</div>
	        </div>
	        <div class="row form-group center-block">
	        	<div class="col-sm-6">
	        		<label>Clasificación</label>
					<select class="form-control" id="clasificacion_bateria" onchange="load_recetas_produccion(\'crear_baterias\')">
						<option value="0" disabled selected>Seleccione</option>';
						$sql = "SELECT * FROM Clasificacion WHERE Grupo='Mezclas' ORDER BY Descripcion";
			            $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
			            $filas=sqlsrv_num_rows($resultado);
			            while($aa = sqlsrv_fetch_array($resultado)){
		            		if($idClasificacion_baterias == $aa['idClasificacion']){
								$body_modal.='<option value="'.$aa['idClasificacion'].'" selected>'.utf8_encode($aa['Descripcion']).'</option>';
			            	}else{
			            		$body_modal.='<option value="'.$aa['idClasificacion'].'">'.utf8_encode($aa['Descripcion']).'</option>';
			            	}
						}
		$body_modal.='</select>
	        	</div>
	        	<div class="col-sm-6">
	        		<label>Receta</label>
					<select class="form-control" id="receta_bateria">
						<option value="0" disabled selected>Seleccione</option>';
						$sql = "SELECT * FROM Recetas_produccion WHERE idClasificacion='$idClasificacion_baterias' 
							AND Habilitado=1 ORDER BY Descripcion";
			            $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
			            $filas=sqlsrv_num_rows($resultado);
			            while($aa = sqlsrv_fetch_array($resultado)){
			            	if($idReceta_baterias == $aa['idReceta']){
								$body_modal.='<option value="'.$aa['idReceta'].'" selected>'.utf8_encode($aa['Descripcion']).'</option>';
			            	}else{
			            		$body_modal.='<option value="'.$aa['idReceta'].'">'.utf8_encode($aa['Descripcion']).'</option>';
			            	}
						}
		$body_modal.='</select>
	        	</div>

	    </div>
	    <div class="modal-footer">
	        <button type="button" class="btn btn-warning" onclick="SAVE_baterias(\''.$idPlanta.'\',\''.$idBateria.'\')">
	            Guardar Baterias
	        </button>
	    </div>';
	}elseif($tipoCreacion == 'Hornos'){
		$tittle_modal = '<b>'.$NombrePlanta.' - BATERIA '.$NombreBateria.'</b> - '.'Creación de '.$tipoCreacion;
		$body_modal = '<div class="modal-body">
	        <div class="row form-group center-block">
	        	<div class="col-sm-3">
	        		<label># Hornos <input type="checkbox" title="crear varios hornos" id="checkbox_hornos" onclick="block_alias()"';
                    if($idHorno<>'0')
                        $body_modal.='disabled';
                    $body_modal.='></label>
	        		<input type="number" name="descripcion_horno" id="descripcion_horno" class="form-control" placeholder="Ej: 1,2,3,etc." value="'.$NombreHorno.'"';
                    if($idHorno<>'0')
                        $body_modal.='disabled';
                    $body_modal.='>
	        	</div>
	        	<div class="col-sm-3">
	        		<label>Alias Horno</label>
	        		<input type="text" name="alias_horno" id="alias_horno" class="form-control" placeholder="Ej: A-1,B-1,C-1,etc." value="'.$alias_horno.'">
	        	</div>
	        	<div class="col-sm-3">
	        		<label>Estado Bateria</label>
	        		<select class="form-control" id="estado_horno">
		  			<option value="">Seleccione</option>';
		  			while($data_estado = sqlsrv_fetch_array($res_estados)){
		  				//$body_modal.='<option value="'.$data_estado['iEstado'].'">'.utf8_encode($data_estado['Descripcion']).'</option>';
		  				if($iEstado_hornos == $data_estado['iEstado']){
		  					$body_modal.='<option value="'.$data_estado['iEstado'].'" selected>'.utf8_encode($data_estado['Descripcion']).'</option>';
		  				}else{
		  					$body_modal.='<option value="'.$data_estado['iEstado'].'">'.utf8_encode($data_estado['Descripcion']).'</option>';
		  				}
		  			}
		  		$body_modal.='</select>
	        	</div>
	        	<div class="col-sm-3">
	        		<label>Jornada (Hrs.)</label>
	        		<input type="number" name="jornada_horno" id="jornada_horno" class="form-control" value="'.$jornada_horno.'">
	        	</div>
	        	</div>
			        <div class="row form-group center-block">
			        	<div class="col-sm-6">
			        		<label>Clasificación</label>
							<select class="form-control" id="clasificacion_horno" onchange="load_recetas_produccion(\'crear_horno\')">
								<option value="0" disabled selected>Seleccione</option>';
								$sql = "SELECT * FROM Clasificacion WHERE Grupo='Mezclas' ORDER BY Descripcion";
					            $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
					            $filas=sqlsrv_num_rows($resultado);
					            while($aa = sqlsrv_fetch_array($resultado)){
				            		if($idClasificacion_hornos == $aa['idClasificacion']){
										$body_modal.='<option value="'.$aa['idClasificacion'].'" selected>'.utf8_encode($aa['Descripcion']).'</option>';
					            	}else{
					            		$body_modal.='<option value="'.$aa['idClasificacion'].'">'.utf8_encode($aa['Descripcion']).'</option>';
					            	}
								}
				$body_modal.='</select>
			        	</div>
			        	<div class="col-sm-6">
			        		<label>Receta</label>
							<select class="form-control" id="receta_horno">
								<option value="0" disabled selected>Seleccione</option>';
								$sql = "SELECT * FROM Recetas_produccion WHERE idClasificacion='$idClasificacion_hornos'
									AND Habilitado=1
									ORDER BY Descripcion";
					            $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
					            $filas=sqlsrv_num_rows($resultado);
					            while($aa = sqlsrv_fetch_array($resultado)){
					            	if($idReceta_hornos == $aa['idReceta']){
										$body_modal.='<option value="'.$aa['idReceta'].'" selected>'.utf8_encode($aa['Descripcion']).'</option>';
					            	}else{
					            		$body_modal.='<option value="'.$aa['idReceta'].'">'.utf8_encode($aa['Descripcion']).'</option>';
					            	}
								}
				$body_modal.='</select>
			        	</div>
			        </div>
	        	<div class="row" style="border-bottom: 1px solid; text-align: left;">
					<div class="col-sm-12"><br><label style="margin-left: 5px;">Estructura Horno</label></div>
				</div><br>
				<div class="col-sm-2"></div>
				<div class="col-sm-4">
					<label>Indice Carga</label>
					<input type="number" class="form-control" name="indice_carga" id="indice_carga" placeholder="Ej: 4,4.5,3,etc." value="'.$indice_carga.'">
				</div>
				<div class="col-sm-4">
					<label>Indice Deshorne</label>
					<input type="number" class="form-control" name="indice_deshorne" id="indice_deshorne" placeholder="Ej: 4,4.5,3,etc." value="'.$indice_deshorne.'">
				</div>
	        </div>
	    </div>
	    <div class="modal-footer">
	        <button type="button" class="btn btn-warning" onclick="SAVE_hornos(\''.$idPlanta.'\',\''.$idBateria.'\',\''.$idHorno.'\')">
	            Guardar Horno
	        </button>
	    </div>';
	}
    echo $tittle_modal.'||'.$body_modal;
}elseif($_POST['band']==7){ /////////////////////////////////// CREACION DE PLANTAS /////////////////////////////////////////////
	$lugar_trabajo = $_POST['lugar_trabajo'];
	$jornada = $_POST['jornada'];
	$estado = $_POST['estado'];
	$receta = $_POST['receta'];
	$clasificacion_planta = $_POST['clasificacion_planta'];
	$clasificacionProducida_planta = $_POST['clasificacionProducida_planta'];
	$params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$lugar_trabajo = $_POST['lugar_trabajo'];
	$SQL_PLANTAS = "SELECT * FROM Plantas WHERE idDestinoAsociado='$lugar_trabajo'";
	$resul_plantas=sqlsrv_query($conn,$SQL_PLANTAS,$params,$options);
    $rows_plantas = sqlsrv_num_rows($resul_plantas);
	if($rows_plantas>0){
    	while($data_plantas = sqlsrv_fetch_array($resul_plantas)){
    		$idPlanta = $data_plantas['idDestino'];
    	}
    	$UPDATE_PLANTAS = "UPDATE Plantas SET Jornada='$jornada', Estado='$estado', idReceta='$receta', idClasificacion='$clasificacion_planta', idClasificacionProducida='$clasificacionProducida_planta' WHERE idDestino='$idPlanta'";
    	$res_update_PLANTAS = sqlsrv_query($conn,$UPDATE_PLANTAS);
    	if($res_update_PLANTAS){
    		$UPDATE_BATERIAS = "UPDATE Baterias SET Jornada='$jornada', Estado='$estado', idReceta='$receta', idClasificacion='$clasificacion_planta', idClasificacionProducida='$clasificacionProducida_planta' WHERE idPlanta='$idPlanta'";
	    	$res_update_BATERIAS = sqlsrv_query($conn,$UPDATE_BATERIAS);
	    	if($res_update_BATERIAS){
	    		$UPDATE_HORNOS = "UPDATE Hornos SET Jornada='$jornada', iEstado='$estado', idReceta='$receta', idClasificacion='$clasificacion_planta', idClasificacionProducida='$clasificacionProducida_planta' WHERE idPlanta='$idPlanta'";
		    	$res_update_HORNOS = sqlsrv_query($conn,$UPDATE_HORNOS);
		    	if($res_update_HORNOS){
		    		echo 1;
		    	}
	    	}
    	}
    }else{
    	$SQL = "SELECT * FROM Destino WHERE idDestino='$lugar_trabajo'";
    	$res = sqlsrv_query($conn,$SQL);
    	while($aa = sqlsrv_fetch_array($res)){
    		$NombrePlanta = utf8_encode($aa['Descripcion']);
    	}
    	$INSERT_PLANTAS = "INSERT INTO Plantas (idDestino,NombrePlanta,idDestinoAsociado,Jornada,Estado,idReceta,idClasificacion) VALUES (NEWID(),'$NombrePlanta','$lugar_trabajo',$jornada,$estado,'$receta','$clasificacion_planta')";
    	$res_insert = sqlsrv_query($conn,$INSERT_PLANTAS);
    	if($res_insert){
    		echo 1;
    	}
    }
}elseif($_POST['band']==8){ ////////////////////////// CREACION DE BATERIAS ///////////////////////////////////////
	$idPlanta = $_POST['idPlanta'];
	$idBateria = $_POST['idBateria'];
	$lugar_trabajo = $_POST['lugar_trabajo'];
    $num_bateria = $_POST['num_bateria'];
	$descripcion_bateria = $_POST['descripcion_bateria'];
	$jornada = $_POST['jornada'];
	$estado = $_POST['estado'];
	$clasificacion_bateria = $_POST['clasificacion_bateria'];
	$receta = $_POST['receta'];

	$SQL_PLANTAS = "SELECT * FROM Plantas WHERE idDestino='$idPlanta' AND idDestinoAsociado='$lugar_trabajo'";
	$resul_plantas=sqlsrv_query($conn,$SQL_PLANTAS,$params,$options);
    $rows_plantas = sqlsrv_num_rows($resul_plantas);
    if($rows_plantas>0){
    	if($idBateria=='0'){
	    	$SQL_BATERIAS = "SELECT * FROM Baterias WHERE idPlanta='$idPlanta' AND Descripcion='$descripcion_bateria'";
	    	$resul_baterias=sqlsrv_query($conn,$SQL_BATERIAS,$params,$options);
	    	$rows_baterias = sqlsrv_num_rows($resul_baterias);
	    	if($rows_baterias==0){
	    		$INSERT = "INSERT INTO Baterias (idPlanta,idBateria,NumBateria,Descripcion,Jornada,Estado,idReceta,idClasificacion,ajuste) VALUES ('$idPlanta',NEWID(),'$num_bateria','$descripcion_bateria','$jornada','$estado','$receta','$clasificacion_bateria',0)";
	    		$res_insert = sqlsrv_query($conn,$INSERT);
	    		if($res_insert){
	    			echo 1;
	    		}else{
	    			echo 0;
	    		}
	    	}
	    }elseif($idBateria<>'0'){
			$UPDATE = "UPDATE Baterias SET NumBateria='$num_bateria',Descripcion='$descripcion_bateria',Estado='$estado',Jornada='$jornada',idReceta='$receta',idClasificacion='$clasificacion_bateria' WHERE idBateria='$idBateria' AND idPlanta='$idPlanta'";
			$res_update = sqlsrv_query($conn,$UPDATE);
			if($res_update){
				echo 1;
			}else{
				echo 0;
			}
    	}
    }
}elseif($_POST['band']==9){ //OPCION PARA CREAR HORNOS EN EL MODULO ESTRUCTURA HORNOS
	$idPlanta = $_POST['idPlanta'];
	$idBateria = $_POST['idBateria'];
	$idHorno = $_POST['idHorno'];
	$lugar_trabajo = $_POST['lugar_trabajo'];
	$descripcion_horno = $_POST['descripcion_horno'];
	$alias_horno = $_POST['alias_horno'];
	$jornada = $_POST['jornada'];
	$estado = $_POST['estado'];
	$clasificacion_horno = $_POST['clasificacion_horno'];
	$receta_horno = $_POST['receta_horno'];
	$indice_carga = $_POST['indice_carga'];
	$indice_deshorne = $_POST['indice_deshorne'];
	$checkbox_hornos = $_POST['checkbox_hornos'];
	$devolver_echo = 0;
    $nom_bateria = '';

	$SQL_PLANTAS = "SELECT * FROM Plantas WHERE idDestino='$idPlanta' AND idDestinoAsociado='$lugar_trabajo'";
	$resul_plantas=sqlsrv_query($conn,$SQL_PLANTAS,$params,$options);
    $rows_plantas = sqlsrv_num_rows($resul_plantas);
    if($rows_plantas>0){
    	$SQL_BATERIAS = "SELECT * FROM Baterias WHERE idPlanta='$idPlanta' AND idBateria='$idBateria'";
    	$resul_baterias=sqlsrv_query($conn,$SQL_BATERIAS,$params,$options);
    	$rows_baterias = sqlsrv_num_rows($resul_baterias);
    	if($rows_baterias>0){
    		if($idHorno=='0'){
    			if($checkbox_hornos == 1){
	    			for ($i=1; $i <= $descripcion_horno; $i++){
	    				$SQL_HORNOS = "SELECT * FROM Hornos WHERE idPlanta='$idPlanta' AND idBateria='$idBateria' AND Alias='$i' AND NumHorno='$i'";
			    		$resul_hornos=sqlsrv_query($conn,$SQL_HORNOS,$params,$options);
			    		$rows_hornos = sqlsrv_num_rows($resul_hornos);
			    		if($rows_hornos==0){
			    			$INSERT = "INSERT INTO Hornos (idHorno,Alias,NumHorno,Capacidad,idPlanta,idBateria,iEstado,Jornada,CapacidadDeshorne,idReceta,idClasificacion,ajuste) VALUES(NEWID(),'$i','$i','$indice_carga','$idPlanta','$idBateria','$estado','$jornada','$indice_deshorne','$receta_horno','$clasificacion_horno',0)";
			    			$res_insert = sqlsrv_query($conn,$INSERT);
			    			if($res_insert){
			    				$devolver_echo++;
			    			}
			    		}
	    			}
	    		}else{
		    		$SQL_HORNOS = "SELECT * FROM Hornos WHERE idPlanta='$idPlanta' AND idBateria='$idBateria' AND Alias='$alias_horno' AND NumHorno='$descripcion_horno'";
		    		$resul_hornos=sqlsrv_query($conn,$SQL_HORNOS,$params,$options);
		    		$rows_hornos = sqlsrv_num_rows($resul_hornos);
		    		if($rows_hornos==0){
		    			$INSERT = "INSERT INTO Hornos (idHorno,Alias,NumHorno,Capacidad,idPlanta,idBateria,iEstado,Jornada,CapacidadDeshorne,idReceta,idClasificacion,ajuste) VALUES(NEWID(),'$alias_horno','$descripcion_horno','$indice_carga','$idPlanta','$idBateria','$estado','$jornada','$indice_deshorne','$receta_horno','$clasificacion_horno',0)";
		    			$res_insert = sqlsrv_query($conn,$INSERT);
		    			if($res_insert){
		    				$devolver_echo++;
		    			}else{
		    				$devolver_echo=0;
		    			}
		    		}
		    	}
	    	}elseif($idHorno<>'0'){
                $sql_bateria = "SELECT * FROM Baterias WHERE idBateria='$idBateria'";
                $res = sqlsrv_query($conn,$sql_bateria);
                while($aa = sqlsrv_fetch_array($res)){
                    $nom_bateria = $aa['Descripcion'];
                }

				$UPDATE = "UPDATE Hornos SET Alias='$alias_horno',NumHorno='$descripcion_horno',Capacidad='$indice_carga',iEstado='$estado',Jornada='$jornada',CapacidadDeshorne='$indice_deshorne', idReceta='$receta_horno', idClasificacion='$clasificacion_horno' WHERE idHorno='$idHorno' AND idBateria='$idBateria' AND idPlanta='$idPlanta'";
				$res_update = sqlsrv_query($conn,$UPDATE);
				if($res_update){
					$devolver_echo++;
				}else{
					$devolver_echo=0;
				}
    		}
    	}
    }
    echo $devolver_echo.'||'.$nom_bateria;
}/*elseif($_POST['band']==10){
	$check_array = $_POST['check_array'];
	$idHorno = $_POST['idHorno'];
	if($check_array <> ''){
		$check_array = explode(",", $check_array);
		$check_array = implode("','", $check_array);
		$SQL_BASE = "SELECT * FROM Hornos WHERE idHorno IN ('$check_array')";
		$resul_base=sqlsrv_query($conn,$SQL_BASE,$params,$options);
	   	$rows_base = sqlsrv_num_rows($resul_base);
	   	if($rows_base>0){
	   		while($data_base = sqlsrv_fetch_array($resul_base)){
	   			$indice_carga_base = $data_base['Capacidad'];
	   			$indice_deshorne_base = $data_base['CapacidadDeshorne'];
	   		}
	   	}
	   	$SQL_NEW = "SELECT * FROM Hornos WHERE idHorno='$idHorno'";
	   	$resul_new=sqlsrv_query($conn,$SQL_NEW,$params,$options);
	   	$rows_new = sqlsrv_num_rows($resul_new);
	   	if($rows_new>0){
	   		while($data_new = sqlsrv_fetch_array($resul_new)){
	   			$NombreHorno = $data_new['NumHorno'];
	   			$indice_carga_new = $data_new['Capacidad'];
	   			$indice_deshorne_new = $data_new['CapacidadDeshorne'];
	   		}
	   	}
	   	if($indice_carga_base == $indice_carga_new && $indice_deshorne_base == $indice_deshorne_new){
	   		echo 0;
	   	}else{
	   		echo 'El horno # '.$NombreHorno.' no tiene las mismas caracteristicas a los anteriores hornos';
	   	}
	}else{
		echo 0;
	}
}*/elseif($_POST['band']==11){ // CARGA EL MODAL PARA REGISTRAR ALIMENTACION A HORNOS EN EL MODULO COQUIZACION
	$check_array = $_POST['check_array'];
	$check_array = explode(",", $check_array);
	$check_array = implode("','", $check_array);
	$sql_recetaHorno = "SELECT * FROM Recetas_produccion 
		WHERE idReceta IN (SELECT idReceta FROM Hornos WHERE idHorno in ('$check_array')) AND Habilitado=1
		ORDER BY Descripcion";
	$resul_recetaHorno=sqlsrv_query($conn,$sql_recetaHorno,$params,$options);
	$rows_recetaHorno = sqlsrv_num_rows($resul_recetaHorno);
	if($rows_recetaHorno>0){
		while($data_recetaHorno = sqlsrv_fetch_array($resul_recetaHorno)){
			$idReceta_horno = $data_recetaHorno['idReceta'];
		}
	}
	$txt_array_empresa = implode("','", $_SESSION['Array_empresa']['COQUIZACION']);
	$txt_hornos = '<div class="modal-body">
	        <div class="row form-group center-block">
	        	<div class="row">
					<div class="col-sm-12"><center><label style="text-decoration: underline;color: #4F87B3">Datos Alimentación</label></center></div>
				</div>
				<div class="row">
					<div class="col-sm-4"></div>
					<div class="col-sm-4">
						<label>Empresa</label>
						<select class="form-control" id="empresa_alimentacion" onchange="load_recetas_produccion(\'alimentacion_hornos\')">
							<option value="0" disabled selected>Seleccione</option>';
					$sql_empresa = "SELECT * FROM Proveedores WHERE Empresa=1 AND idProveedor IN ('$txt_array_empresa') ORDER BY NombreCorto";
					$resul_empresa=sqlsrv_query($conn,$sql_empresa,$params,$options);
					$rows_empresa = sqlsrv_num_rows($resul_empresa);
					if($rows_empresa>0){
						while($data_empresa = sqlsrv_fetch_array($resul_empresa)){
							$txt_hornos.='<option value="'.$data_empresa['idProveedor'].'">'.utf8_encode($data_empresa['NombreCorto']).'</option>';
						}
					}
                    //value="'.date('Y-m-d').'"
                    //value="'.date('H:i:s').'"
			$txt_hornos.='</select>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3">
						<label>Fecha</label>
						<input type="date" class="form-control" name="fecha_alimentacion" id="fecha_alimentacion" max="'.date('Y-m-d').'">
					</div>
					<div class="col-sm-3">
						<label>Hora</label>
						<input type="time" class="form-control" name="hora_alimentacion" id="hora_alimentacion">
					</div>
					<div class="col-sm-3">
						<label>Mezcla Alimentada</label>
						<select class="form-control" id="clasificacion_alimentacion" onchange="load_recetas_produccion(\'alimentacion_hornos\')">';
						$sql_clasificacion = "SELECT * FROM Clasificacion 
							WHERE idClasificacion IN (SELECT idClasificacion FROM Hornos WHERE idHorno in ('$check_array'))
							ORDER BY Descripcion";
						$resul_clasificacion=sqlsrv_query($conn,$sql_clasificacion,$params,$options);
						$rows_clasificacion = sqlsrv_num_rows($resul_clasificacion);
						if($rows_clasificacion>0){
							while($data_clasificacion = sqlsrv_fetch_array($resul_clasificacion)){
								$idClasificacion_horno = $data_clasificacion['idClasificacion'];
								$txt_hornos.='<option value="'.$data_clasificacion['idClasificacion'].'">'.utf8_encode($data_clasificacion['Descripcion']).'</option>';
							}
						}
	$txt_hornos.='		</select>
					</div>
					<div class="col-sm-3">
						<label>Receta</label>
						<select class="form-control" id="receta_alimentacion">';
						$sql_receta = "SELECT * FROM Recetas_produccion WHERE idClasificacion='$idClasificacion_horno'
							AND Habilitado=1 
						ORDER BY Descripcion";
						$resul_receta=sqlsrv_query($conn,$sql_receta,$params,$options);
						$rows_receta = sqlsrv_num_rows($resul_receta);
						if($rows_recetaHorno>0){
							while($data_receta = sqlsrv_fetch_array($resul_receta)){
								if($idReceta_horno==$data_receta['idReceta']){
									$txt_hornos.='<option value="'.$data_receta['idReceta'].'" selected>'.utf8_encode($data_receta['Descripcion']).'</option>';
								}else{
									$txt_hornos.='<option value="'.$data_receta['idReceta'].'">'.utf8_encode($data_receta['Descripcion']).'</option>';
								}
							}
						}else{
							$txt_hornos.='<option value="0">No hay recetas disponibles</option>';
						}
	$txt_hornos.='		</select>
					</div>
				</div><br>
				<div class="row">
					<div class="col-sm-12">
                        <center>
                            <label style="text-decoration: underline;color: #4F87B3">Hornos Seleccionados </label> 
                            <input type="number" id="tm_tara_hornos" class="input-sm" title="tm total / hornos" onkeypress="calcular_tara(this,\'alimentacion\')">
                            <button class="btn btn-success btn-xs" onclick="calcular_tara(1,\'alimentacion\')">Calcular</button>
                        </center>
                    </div>
				</div>
	        	<div class="row">';
	        	$SQL_HORNOS = "SELECT Hornos.*, Baterias.Descripcion AS Bateria FROM Hornos INNER JOIN Baterias ON Hornos.idBateria=Baterias.idBateria 
                WHERE Hornos.idHorno in ('$check_array') ORDER BY Baterias.Descripcion,Hornos.NumHorno";
				$resul_hornos=sqlsrv_query($conn,$SQL_HORNOS,$params,$options);
				$rows_hornos = sqlsrv_num_rows($resul_hornos);
				if($rows_hornos>0){
	        		$txt_hornos.='<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
						<div class="row">
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<center><label>Horno</label></center>
							</div>
							<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
								<center><label>Carga (TM)</label></center>
							</div>
						</div>
					</div>';
					if($rows_hornos>1){
						$txt_hornos.='<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
							<div class="row">
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
									<center><label>Horno</label></center>
								</div>
								<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
									<center><label>Carga (TM)</label></center>
								</div>
							</div>
						</div>';
					}
					if($rows_hornos>2){
						$txt_hornos.='<div class="col-xs-hidden col-sm-hidden col-md-4 col-lg-4">
							<div class="row">
								<div class="col-md-4 col-lg-4">
									<center><label>Horno</label></center>
								</div>
								<div class="col-md-8 col-lg-8">
									<center><label>Carga (TM)</label></center>
								</div>
							</div>
						</div>';
					}
	        		$txt_hornos.='</div>
	        		<div clas="row"><form id="form_alimentacion"  method="POST">';
	//for ($i=0; $i < count($check_array); $i++){
		
					while($data_hornos = sqlsrv_fetch_array($resul_hornos)){
						$NombreHorno = $data_hornos['NumHorno'];
                        $AliasHorno = $data_hornos['Alias'];
						$idHorno = $data_hornos['idHorno'];
						$Capacidad = number_format($data_hornos['Capacidad'],2);
						$txt_hornos.='<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
						<div class="row">
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<button type="button" class="btn" style="margin-top: 5px;"># '.$data_hornos['Bateria'].'-'.$AliasHorno.'</button>
							</div>
							<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
								<input type="number" name="'.$AliasHorno.'" id="'.$idHorno.'" class="form-control list_horno_val" value="'.$Capacidad.'" onkeypress="calcular_tm_total(this,\'alimentacion\')">
							</div>
						</div>
						</div>';
					}
				}
	$txt_hornos.='</form>
				</div>
			</div>
		</div>
		<div class="modal-footer">
	        <button type="button" id="registrar_alimentacion_horno" class="btn btn-warning" onclick="registrar_alimentacion()">
	            Registrar Alimentación
	        </button>
	    </div>';
	//}
	echo $txt_hornos;
}elseif($_POST['band']==12){ //OPCION PARA REGISTRAR LA ALIMENTACION A HORNOS EN EL MODULO COQUIZACION
	$FechaRegistro = date('Y-m-d H:i:s');
	$array_id = explode(",",$_POST['array_id']);
	$array_name = explode(",",$_POST['array_name']);
	$array_value = explode(",",$_POST['array_value']);
	$fecha_alimentacion = $_POST['fecha_alimentacion'];
	$hora_alimentacion = $_POST['hora_alimentacion'];
	$receta_alimentacion = $_POST['receta_alimentacion'];
	$empresa_alimentacion = $_POST['empresa_alimentacion'];
	$fecha_compuesta = $fecha_alimentacion.' '.$hora_alimentacion;
	$count_error = 0;
	$count_true = 0;
	for ($i=0; $i < count($array_id); $i++){
		$arr1 = str_split($array_id[$i]);
		if(count($arr1)==36){
			$SQL_HORNOS = "SELECT Hornos.*, Baterias.idReceta FROM Hornos 
                INNER JOIN Baterias ON Hornos.idBateria=Baterias.idBateria 
			WHERE idHorno='$array_id[$i]'";
			$resul_hornos=sqlsrv_query($conn,$SQL_HORNOS,$params,$options);
			$rows_hornos = sqlsrv_num_rows($resul_hornos);
			if($rows_hornos>0){
				while($data_hornos = sqlsrv_fetch_array($resul_hornos)){
					$capacidad = number_format($data_hornos['Capacidad'],2);
					$idReceta = $data_hornos['idReceta'];
				}
				$SQL_DATES = "SELECT ISNULL(MAX(FechaProduccion),'1900-01-01 00:00:00') AS FechaProduccion FROM Produccion_hornos_recetas WHERE idHorno='$array_id[$i]'";
				$resul_dates=sqlsrv_query($conn,$SQL_DATES,$params,$options);
				$rows_dates = sqlsrv_num_rows($resul_dates);
				while($date_limit = sqlsrv_fetch_array($resul_dates)){
					$FechaProduccion = date_format($date_limit['FechaProduccion'],'Y-m-d');
				}
				$date_actual_numbers = strtotime($FechaProduccion);
				$NuevaFechaAlimentacion = strtotime($fecha_alimentacion);
				if($date_actual_numbers<=$NuevaFechaAlimentacion){
                    $sql_validate_alimentacion = "SELECT * FROM Alimentacion_hornos_recetas WHERE idHorno='$array_id[$i]' AND FechaAlimentacion='$fecha_compuesta'";
                    $resul_validate_alimentacion=sqlsrv_query($conn,$sql_validate_alimentacion,$params,$options);
                    $rows_validate_alimentacion = sqlsrv_num_rows($resul_validate_alimentacion);
                    if($rows_validate_alimentacion==0){
    					$INSERT_ALIMENTACION = "INSERT INTO Alimentacion_hornos_recetas (idAlimentacion_hornos,idHorno,idReceta,FechaAlimentacion/*,FechaProduccion*/,Capacidad,Indice_carga,FechaRegistro,idUsuario,idEmpresa) VALUES (NEWID(),'$array_id[$i]','$receta_alimentacion','$fecha_compuesta'/*,'1900/01/01 00:00:00'*/,$capacidad,$array_value[$i],'$FechaRegistro','$idUsuario','$empresa_alimentacion')";
    					$res_insert_alimentacion = sqlsrv_query($conn,$INSERT_ALIMENTACION);
    					if($res_insert_alimentacion){
    						$count_true++;
    					}
                    }else{
                        $array_errors[$count_error] = $array_name[$i].'||'.$array_value[$i]."||El horno ya tiene una alimentación vigente";
                        $count_error++;
                    }
				}else{
					$array_errors[$count_error] = $array_name[$i].'||'.$array_value[$i]."||La fecha es menor a la última de producción";
					$count_error++;
				}
			}else{
				$array_errors[$count_error] = $array_name[$i].'||'.$array_value[$i]."||No existe";
				$count_error++;
			}
		}else{
			$array_errors[$count_error] = $array_name[$i].'||'.$array_value[$i]."||Ha sido alterado su código";
			$count_error++;
		}
	}
	if($count_true == count($array_id)){
		echo 'a';
	}else{
		//echo $count_true;
        if(isset($array_errors)){
            echo implode("--", $array_errors);
            //print_r($array_errors);
            /*for ($i=0; $i < count($array_errors); $i++) { 
                $positions = explode("||", $array_errors[$i]);
                echo "El horno ".$positions[0]." ".$positions[2]." <br>";
            }*/
        }
	}
}elseif($_POST['band']==13){ //CARGA MODAL PARA REGISTRAR PRODUCCION EN EL MODULO COQUIZACION
	$check_array = $_POST['check_array'];
    if($_POST['variable_modal']==0){
        $FechaSeleccion = $_POST['fecha_produccion'];
    }
    $variable_modal = $_POST['variable_modal'];
	$check_array = explode(",", $check_array);
	$check_array = implode("','", $check_array);
	$txt_hornos = '';
    if($variable_modal == 1){
        $SQL_TEST = "SELECT Clasificacion.UnidadDeNegocio FROM Alimentacion_hornos_recetas 
            LEFT JOIN Produccion_hornos_recetas ON Alimentacion_hornos_recetas.idAlimentacion_hornos=Produccion_hornos_recetas.idAlimentacion_hornos
            INNER JOIN Recetas_produccion ON Alimentacion_hornos_recetas.idReceta=Recetas_produccion.idReceta
            INNER JOIN Clasificacion ON Recetas_produccion.idClasificacion=Clasificacion.idClasificacion
        WHERE Alimentacion_hornos_recetas.idHorno in ('$check_array') AND Produccion_hornos_recetas.idProduccion_hornos IS NULL";
        $res = sqlsrv_query($conn,$SQL_TEST);
        while($bb = sqlsrv_fetch_array($res)){
            $UnidadDeNegocio = $bb['UnidadDeNegocio'];
        }
        //value="'.date('Y-m-d').'"
        //value="'.date('H:i:s').'"
        $txt_hornos.='<div class="modal-body">
	        <div class="row form-group center-block">
	        	<div class="row">
					<div class="col-sm-12"><center><label style="text-decoration: underline;color: #4F87B3">Datos Producción</label></center></div>
				</div>
				<div class="row">
					<div class="col-sm-4">
						<label>Fecha</label>
						<input type="date" class="form-control" name="fecha_produccion" id="fecha_produccion" max="'.date('Y-m-d').'" onchange="reload_comments()">
					</div>
					<div class="col-sm-4">
						<label>Hora</label>
						<input type="time" class="form-control" name="hora_produccion" id="hora_produccion">
					</div>
					<div class="col-sm-4">
						<label>Material Producido</label>
						<select class="form-control" id="clasificacion_produccion">
                            <option value="0" selected disabled>Seleccione</option>';

						$sql_clasificacion = "SELECT * FROM Clasificacion 
                            WHERE (Grupo='Coque Bruto' OR idClasificacion IN ('56211C70-52ED-43FF-B584-79F2C5983D9F','4D95C4AA-0FA1-481E-B4F4-8021CE7D4359','DD29044D-3D4A-43ED-98BD-60A3CD5DE3AA',
                                'FE200966-BDB9-4217-8151-F21A0BC390C5')) AND UnidadDeNegocio='$UnidadDeNegocio'
                            ORDER BY Descripcion";
						$resul_clasificacion=sqlsrv_query($conn,$sql_clasificacion,$params,$options);
						$rows_clasificacion = sqlsrv_num_rows($resul_clasificacion);
						if($rows_clasificacion>0){
							while($data_clasificacion = sqlsrv_fetch_array($resul_clasificacion)){
								$txt_hornos.='<option value="'.$data_clasificacion['idClasificacion'].'">'.utf8_encode($data_clasificacion['Descripcion']).'</option>';
							}
						}
	   	  $txt_hornos.='</select>
	   				</div>
				</div><br>
				<div class="row" id="div_comment_group" style="display: flex; align-items: center;">
					<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
						<label>Observaciones:</label>
						<textarea rows="4" class="form-control" id="textarea_comment"></textarea>
					</div>
					<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
						<span class="glyphicon glyphicon-ok" style="color: green;" onclick="load_comment()"></span>
					</div>
				</div><br>
				<div class="row">
					<div class="col-sm-12">
                        <center>
                            <label>TM Total &nbsp;</label><input type="number" id="tm_tara_hornos" class="input-sm" title="tm total / hornos" onkeypress="calcular_tara(this,\'produccion\')">
                            <button class="btn btn-success btn-xs" onclick="calcular_tara(1,\'produccion\')">Calcular</button><br><br>
                            <label style="text-decoration: underline;color: #4F87B3">Hornos Seleccionados </label><br>
                        </center>
                    </div>
				</div>
                <div id="div_form_produccion">';
    }
	$txt_hornos.='<div class="row">';
	        	//echo $SQL_HORNOS = "SELECT * FROM Hornos WHERE idHorno in ('$check_array') ORDER BY NumHorno";
	        	$SQL_HORNOS = "SELECT Hornos.idHorno, Hornos.Alias, Hornos.Capacidad, Hornos.CapacidadDeshorne, Hornos.idBateria, Baterias.Descripcion AS Bateria,
						Hornos.idPlanta, Hornos.iEstado, Hornos.Jornada, Hornos.NumHorno, MAX(Alimentacion_hornos_recetas.FechaAlimentacion) AS FechaAlimentacion 
	        		FROM Hornos INNER JOIN Alimentacion_hornos_recetas ON Hornos.idHorno=Alimentacion_hornos_recetas.idHorno
                        INNER JOIN Baterias ON Hornos.idBateria=Baterias.idBateria
					WHERE Hornos.idHorno in ('$check_array')
					GROUP BY Hornos.idHorno, Hornos.Alias, Hornos.Capacidad, Hornos.CapacidadDeshorne, Hornos.idBateria, Baterias.Descripcion,
						Hornos.idPlanta, Hornos.iEstado, Hornos.Jornada, Hornos.NumHorno
					ORDER BY Baterias.Descripcion, Hornos.NumHorno";
				$resul_hornos=sqlsrv_query($conn,$SQL_HORNOS,$params,$options);
				$rows_hornos = sqlsrv_num_rows($resul_hornos);
				if($rows_hornos>0){
	        		$txt_hornos.='<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
						<div class="row">
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<center><label>Horno</label></center>
							</div>
							<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
								<center><label>Descargue (TM)</label></center>
							</div>
						</div>
					</div>';
					if($rows_hornos>1){
						$txt_hornos.='<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
							<div class="row">
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
									<center><label>Horno</label></center>
								</div>
								<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
									<center><label>Descargue (TM)</label></center>
								</div>
							</div>
						</div>';
					}
					if($rows_hornos>2){
						$txt_hornos.='<div class="col-xs-hidden col-sm-hidden col-md-4 col-lg-4">
							<div class="row">
								<div class="col-md-4 col-lg-4">
									<center><label>Horno</label></center>
								</div>
								<div class="col-md-8 col-lg-8">
									<center><label>Descargue (TM)</label></center>
								</div>
							</div>
						</div>';
					}
	        		
	        	$txt_hornos.='</div><div clas="row"><form id="form_produccion" method="POST">';
				while($data_hornos = sqlsrv_fetch_array($resul_hornos)){
					$NombreHorno = $data_hornos['NumHorno'];
                    $AliasHorno = $data_hornos['Alias'];
					$idHorno = $data_hornos['idHorno'];
					$tiempo_coquizacion = number_format(($data_hornos['Jornada']/24),0);
					$CapacidadDeshorne = number_format($data_hornos['CapacidadDeshorne'],2);
                    $FechaAlimentacion = date_format($data_hornos['FechaAlimentacion'],'Y-m-d');
                    $fecha_registro = date('Y-m-d');
                    if($variable_modal==0){
                        $fecha_registro=$FechaSeleccion;
                    }
					$date_actual_numbers = strtotime($fecha_registro);
					$NuevaFechaAlimentacion = strtotime('+'.$tiempo_coquizacion.' days',strtotime($FechaAlimentacion)) ;
					//echo '<b>Horno '.$NombreHorno.'</b><br> <b>Fecha Actual: </b>'.$fecha_registro.'<br> <b>Fecha Alimentación: </b>'.$FechaAlimentacion.'<br>'.date('Y-m-d',$date_actual_numbers).'<br>'.date('Y-m-d',$NuevaFechaAlimentacion);
					$txt_hornos.='<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
					<div class="row comment_group" id="div_'.$idHorno.'_group" style="border-radius: 5px; display: flex; align-items: center; align-contents: left;">
						<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
							<button type="button" class="btn" style="margin-top: 3px;"';
                    if($date_actual_numbers>$NuevaFechaAlimentacion){
                        $txt_hornos.='onclick="select_horno_comments(\''.$idHorno.'\')"';
                    }
                    $txt_hornos.='># '.$data_hornos['Bateria'].'-'.$AliasHorno.'</button>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							<input type="number" style="margin-top: 1px;" name="'.$data_hornos['Bateria'].'-'.$AliasHorno.'" id="'.$idHorno.'" class="form-control list_horno_val" value="'.$CapacidadDeshorne.'" onkeypress="calcular_tm_total(this,\'produccion\')">
						</div>';
					if($date_actual_numbers>$NuevaFechaAlimentacion){
						$txt_hornos.='<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="margin-left: -15px;">
							<span id="'.$idHorno.'_span" class="glyphicon glyphicon-comment btn" onclick="open_comment_timelast(\''.$idHorno.'\')"></span><input type="hidden" name="'.$data_hornos['Bateria'].'-'.$AliasHorno.'_comment" id="'.$idHorno.'_comment">
						</div>';
					}
					$txt_hornos.='</div>
					</div>';
				}
			}
	$txt_hornos.='</form>
				</div>
                </div>';
    if($variable_modal==1){
$txt_hornos.='</div>
		</div>
		<div class="modal-footer">
	        <button type="button" class="btn btn-warning" onclick="registrar_produccion()" id="registrar_produccion_horno">
	            Registrar Producción
	        </button>
	    </div>';
	}
	echo $txt_hornos;
}elseif($_POST['band']==14){ //OPCION PARA REGISTRAR PRODUCCION DE HORNOS EN EL MODULO COQUIZACION
	$FechaRegistro = date('Y-m-d H:i:s');
	$array_id = explode(",",$_POST['array_id']);
	$array_name = explode(",",$_POST['array_name']);
	$array_value = explode(",",$_POST['array_value']);
	$array_value_comment = explode(",",$_POST['array_value_comment']);
	$fecha_produccion = $_POST['fecha_produccion'];
	$hora_produccion = $_POST['hora_produccion'];
	$clasificacion_produccion = $_POST['clasificacion_produccion'];
	$fecha_compuesta = $fecha_produccion.' '.$hora_produccion;
	$count_error = 0;
	$count_true = 0;
	for ($i=0; $i < count($array_id); $i++){
		$arr1 = str_split($array_id[$i]);
		if(count($arr1)==36){
			$SQL_HORNOS = "SELECT Hornos.*, Baterias.idReceta, Alimentacion_hornos_recetas.idAlimentacion_hornos, Alimentacion_hornos_recetas.FechaAlimentacion
				FROM Hornos INNER JOIN Baterias ON Hornos.idBateria=Baterias.idBateria 
					INNER JOIN Alimentacion_hornos_recetas ON Hornos.idHorno=Alimentacion_hornos_recetas.idHorno 
                    LEFT JOIN Produccion_hornos_recetas ON Alimentacion_hornos_recetas.idAlimentacion_hornos=Produccion_hornos_recetas.idAlimentacion_hornos
				WHERE Hornos.idHorno='$array_id[$i]' AND Produccion_hornos_recetas.idProduccion_hornos IS NULL
                ORDER BY FechaAlimentacion";
			$resul_hornos=sqlsrv_query($conn,$SQL_HORNOS,$params,$options);
			$rows_hornos = sqlsrv_num_rows($resul_hornos);
			if($rows_hornos>0){
				while($data_hornos = sqlsrv_fetch_array($resul_hornos)){
					$CapacidadDeshorne = number_format($data_hornos['CapacidadDeshorne'],2);
					$idAlimentacion_hornos = $data_hornos['idAlimentacion_hornos'];
					$FechaAlimentacion = date_format($data_hornos['FechaAlimentacion'],'Y-m-d');
				}
				$date_actual_numbers2 = strtotime($FechaAlimentacion);
				$NuevaFechaAlimentacion2 = strtotime($fecha_produccion);
				if($NuevaFechaAlimentacion2>=$date_actual_numbers2){
					/*$UPDATE_ALIMENTACION = "UPDATE Alimentacion_hornos_recetas SET FechaProduccion='$fecha_compuesta' 
						WHERE idHorno='$array_id[$i]' AND CAST(FechaProduccion AS DATE)='1900-01-01'";
					$res_update_alimentacion = sqlsrv_query($conn,$UPDATE_ALIMENTACION);
					if($res_update_alimentacion){*/
						$INSERT_PRODUCCION = "INSERT INTO Produccion_hornos_recetas (idAlimentacion_hornos,idProduccion_hornos,FechaProduccion,idHorno,CapacidadDeshorne,Indice_deshorne,FechaRegistro,idUsuario,idClasificacion) VALUES ('$idAlimentacion_hornos',NEWID(),'$fecha_compuesta','$array_id[$i]','$CapacidadDeshorne','$array_value[$i]','$FechaRegistro','$idUsuario','$clasificacion_produccion')";
						$res_insert_produccion = sqlsrv_query($conn,$INSERT_PRODUCCION);
						if($res_insert_produccion){
							$count_true++;
							if(isset($array_value_comment[$i])){
								$comment = $array_value_comment[$i];
								$INSERT_COMMENT = "INSERT INTO Observaciones_produccion_hornos (idObservacion,idHorno,FechaRegistro,Descripcion,idUsuario) VALUES (NEWID(),'$array_id[$i]','$fecha_compuesta','$comment','$idUsuario')";
								$res_insert_comment = sqlsrv_query($conn,$INSERT_COMMENT);
							}
						}
					/*}*/
				}else{
					$array_errors[$count_error] = $array_name[$i]."||".$array_value[$i]."||La fecha es menor a la de alimentación";
					$count_error++;
					//echo $array_id[$i]; echo ' NO PASA <br> \n';
				}
			}else{
				$array_errors[$count_error] = $array_name[$i]."||".$array_value[$i]."||No tiene una produccion";
				$count_error++;
			}
		}else{
			if($array_name[$i] != ''){
				$array_errors[$count_error] = $array_name[$i]."||".$array_value[$i]."||Ha sido alterado su código";
				$count_error++;
			}
		}
	}
	if($count_true == count($array_id)){
		echo 1;
	}else{
		if(isset($array_errors)){
			echo implode("--", $array_errors);
			//print_r($array_errors);
			/*for ($i=0; $i < count($array_errors); $i++) { 
				$positions = explode("||", $array_errors[$i]);
				echo "El horno ".$positions[0]." ".$positions[2]." <br>";
			}*/
		}
	}
}elseif($_POST['band']==15){ //OPCION PARA ESCRIBIR LAS OBSERVACIONES SOBRE HORNOS PERDIDOS
	$horno = $_POST['horno'];
	$arr1 = str_split($horno);
	if(count($arr1)==36){
		$SQL_HORNOS = "SELECT * FROM Hornos WHERE idHorno='$horno'";
		$resul_hornos=sqlsrv_query($conn,$SQL_HORNOS,$params,$options);
		$rows_hornos = sqlsrv_num_rows($resul_hornos);
		if($rows_hornos>0){
			while($data_hornos = sqlsrv_fetch_array($resul_hornos)){
				$NombreHorno = $data_hornos['Alias'];
				$NumHorno = $data_hornos['NumHorno'];
			}
		}
	}
	echo $NombreHorno;
}elseif($_POST['band']==16){ //OPCIÓN PARA GUARDAR LAS PREPARACIONES DE MEZCLA
	$empresa_preparacion = $_POST['empresa_preparacion'];
	$lugar_trabajo_produccion = $_POST['lugar_trabajo_produccion'];
    $fecha_preparacion = $_POST['fecha_preparacion'];
    $receta_preparacion = $_POST['receta_preparacion'];
    $pasa = 0;
    $toneladas_preparacion = $_POST['toneladas_preparacion'];
    $array_id = explode(",",$_POST['array_id_prep']);
    $array_value = explode(",",$_POST['array_value_prep']);

    $sql_NEWID = "SELECT NEWID() AS NEWID";
    $resul_NEWID=sqlsrv_query($conn,$sql_NEWID,$params,$options);
	while($aa = sqlsrv_fetch_array($resul_NEWID)){
		$NEWID = $aa['NEWID'];
	}

    $INSERT = "INSERT INTO Preparacion_recetas (idPreparacion,idReceta,idDestino,Toneladas,FechaRegistro,FechaPreparacion,idUsuario,idEmpresa) 
    	VALUES ('$NEWID','$receta_preparacion','$lugar_trabajo_produccion','$toneladas_preparacion','$fecha_registro','$fecha_preparacion','$idUsuario','$empresa_preparacion')";
    $res = sqlsrv_query($conn,$INSERT);
    if($res){
        if(isset($_SESSION['Array_empresa']['ELIMINAR_PREPARACION_MEZCLA'])){
            $rows=count($array_id);
            for ($i=0; $i < $rows; $i++){
                $idClasificacion = ENCR::descript($array_id[$i]);
                $insert_detalle = "INSERT INTO Preparacion_recetas_detalle (idPreparacion,idReceta,idClasificacion,Toneladas) 
                        VALUES ('$NEWID','$receta_preparacion','$idClasificacion',$array_value[$i])";
                $res = sqlsrv_query($conn,$insert_detalle);
                if($res){
                    $pasa++;
                }
            }
        }else{
            $sql_receta = "SELECT * FROM Recetas_produccion_detalle WHERE idReceta='$receta_preparacion'";
            $resul=sqlsrv_query($conn,$sql_receta,$params,$options);
    		$rows = sqlsrv_num_rows($resul);
    		if($rows>0){
    			while($aa = sqlsrv_fetch_array($resul)){
    				$idClasificacion = $aa['idClasificacion'];
    				$tm_clasif = ($toneladas_preparacion*$aa['porcentaje'])/100;
    				$insert_detalle = "INSERT INTO Preparacion_recetas_detalle (idPreparacion,idReceta,idClasificacion,Toneladas) 
    					VALUES ('$NEWID','$receta_preparacion','$idClasificacion',$tm_clasif)";
    				$res = sqlsrv_query($conn,$insert_detalle);
    				if($res){
    					$pasa++;
    				}
    			}
    		}
        }
    }
    if($pasa==$rows){
        echo 1;
    }else{
        echo 0;
    }
}elseif($_POST['band'] == 17){ //OPCION PARA CONSULTAR RECETAS POR EMPRESA,LUGAR DE TRABAJO Y MEZCLA
	$mezcla_producida = $_POST['mezcla_producida'];
	$lugar_trabajo_produccion = $_POST['lugar_trabajo_produccion'];
	$empresa_preparacion = $_POST['empresa_preparacion'];
    $entorno = $_POST['entorno'];
	$echo = '<option value="0" selected disabled>Seleccione</option>';
    if($entorno=='preparacion_mezcla'){
	   $sql = "SELECT * FROM Recetas_produccion WHERE idClasificacion='$mezcla_producida' AND idEmpresa='$empresa_preparacion' AND idDestino='$lugar_trabajo_produccion' AND Habilitado=1";
    }else{
        $sql = "SELECT * FROM Recetas_produccion WHERE idClasificacion='$mezcla_producida' AND idEmpresa='$empresa_preparacion' AND Habilitado=1";
    }
	$resul=sqlsrv_query($conn,$sql,$params,$options);
	$rows = sqlsrv_num_rows($resul);
	if($rows>0){
		while($aa = sqlsrv_fetch_array($resul)){
            $title_select = '';
            $idReceta = $aa['idReceta'];
            $sql_detalle = "SELECT Clasificacion.Descripcion,Recetas_produccion_detalle.porcentaje FROM Recetas_produccion_detalle INNER JOIN Clasificacion ON Recetas_produccion_detalle.idClasificacion=Clasificacion.idClasificacion 
            WHERE idReceta='$idReceta'";
            $res = sqlsrv_query($conn,$sql_detalle);
            while($aa_detalle = sqlsrv_fetch_array($res)){
                $title_select.='('.utf8_encode($aa_detalle['Descripcion']).'-'.$aa_detalle['porcentaje'].'%) ';
            }
			$echo.='<option value="'.$aa['idReceta'].'" title="'.$title_select.'">'.$aa['Descripcion'].'</option>';
		}
	}else{
		$echo='<option value="0" selected disabled>No hay recetas disponibles</option>';
	}
	echo $echo;
}elseif($_POST['band'] == 18){ //OPCION PARA MOSTRAR LA TABLA CON LOS PORCENTAJES DE LA RECETA SELECCIONADA POR EMPRESA
	$receta_preparacion = $_POST['receta_preparacion'];
	$toneladas_preparacion = $_POST['toneladas_preparacion'];
    $tm_total = 0;
	$echo = '<center><h4>Tabla recetas</h4></center><table class="table table-bordered"><tr>';
	$sql = "SELECT Recetas_produccion_detalle.*, Clasificacion.Descripcion AS Clasificacion 
		FROM Recetas_produccion_detalle 
		INNER JOIN Clasificacion on Recetas_produccion_detalle.idClasificacion=Clasificacion.idClasificacion
		WHERE idReceta='$receta_preparacion' ORDER BY Clasificacion.Descripcion";
	$resul=sqlsrv_query($conn,$sql,$params,$options);
	$resul_2=sqlsrv_query($conn,$sql,$params,$options);
	$rows = sqlsrv_num_rows($resul);
	if($rows>0){
		while($aa = sqlsrv_fetch_array($resul)){
            $porcentaje = $aa['porcentaje'];
			$echo.='<th style="text-align: center">'.$aa['Clasificacion'].' ('.$porcentaje.'%)</th>';
		}
		$echo.='</tr><tr id="tr_details_receta">';
        $disabled = "disabled";
        if(isset($_SESSION['Array_empresa']['ELIMINAR_PREPARACION_MEZCLA'])){
            $disabled = "";
        }
		while($aa = sqlsrv_fetch_array($resul_2)){
			$porcentaje = $aa['porcentaje'];
            $idClasificacion = ENCR::encript($aa['idClasificacion']);
			$tm_porcentaje = ($toneladas_preparacion*$porcentaje)/100;
            $tm_total+=number_format($tm_porcentaje,2);
            if($toneladas_preparacion<$tm_total){
                $tm_porcentaje=($tm_porcentaje-($tm_total-$toneladas_preparacion));
            }
			$echo.='<td style="text-align: center"><input class="form-control" id="'.$idClasificacion.'" value="'.number_format($tm_porcentaje,2,'.','').'" '.$disabled.'></td>';
		}
		$echo.='</tr></table>';
	}else{
		$echo='<option value="0" selected disabled>No hay recetas disponibles</option>';
	}
	echo $echo;
}elseif($_POST['band'] == 19){ //OPCION PARA MOSTRAR LAS CLASIFICACIONES DISPONIBLES EN LA PREPARACION DE RECETAS
	$clasificacion = $_POST['clasificacion'];
	$echo = '<option value="0" selected disabled>Seleccione</option>';
	$sql = "SELECT * FROM Recetas_produccion WHERE idClasificacion='$clasificacion' AND Habilitado=1 
		ORDER BY Descripcion";
	$resul=sqlsrv_query($conn,$sql,$params,$options);
	$rows = sqlsrv_num_rows($resul);
	if($rows>0){
		while($aa = sqlsrv_fetch_array($resul)){
			$echo.='<option value="'.$aa['idReceta'].'">'.$aa['Descripcion'].'</option>';
		}
	}else{
		$echo='<option value="0" selected disabled>No hay recetas disponibles</option>';
	}
	echo $echo;
}elseif($_POST['band'] == 20){ //OPCION PARA MOSTRAR CLASIFICACIONES POR PRODUCTO EN EL MODULO INVENTARIOS
	$producto_inventario = $_POST['producto_inventario'];
	$echo = '<option value="0" selected disabled>Seleccione</option>';
	$sql = "SELECT * FROM Clasificacion WHERE idProducto='$producto_inventario' ORDER BY Descripcion";
	$resul=sqlsrv_query($conn,$sql,$params,$options);
	$rows = sqlsrv_num_rows($resul);
	if($rows>0){
		while($aa = sqlsrv_fetch_array($resul)){
			$echo.='<option value="'.$aa['idClasificacion'].'">'.utf8_encode($aa['Descripcion']).'</option>';
		}
	}
	echo $echo;
}elseif($_POST['band'] == 21){ //OPCION PARA REGISTRAR INVENTARIOS
	$empresa_inventario = $_POST['empresa_inventario'];
	$fecha_inventario = $_POST['fecha_inventario'];
	$producto_inventario = $_POST['producto_inventario'];
	$clasificacion_inventario = $_POST['clasificacion_inventario'];
	$saldo_inventario = $_POST['saldo_inventario'];
	$site_work = $_POST['site_work'];

    $sql_validate = "SELECT * FROM InventarioSaldo 
        WHERE idEmpresa='$empresa_inventario' AND idDestino='$site_work' AND idClasificacion='$clasificacion_inventario' AND FechaSaldo='$fecha_inventario'";
    $resul_validate=sqlsrv_query($conn,$sql_validate,$params,$options);
    $rows_validate = sqlsrv_num_rows($resul_validate);
    if($rows_validate==0){
        $insert = "INSERT INTO InventarioSaldo (idEmpresa,FechaSaldo,idDestino,idClasificacion,Saldo,FechaRegistro,idUsuario) 
            VALUES ('$empresa_inventario','$fecha_inventario','$site_work','$clasificacion_inventario',$saldo_inventario,'$fecha_registro','$idUsuario')";
        $resul=sqlsrv_query($conn,$insert);
        if($resul){
            echo 1;
        }
    }else{
        echo 2;
    }
}elseif($_POST['band'] == 22){ //CARGA LOS INVENTARIOS CONSULTADOS EN EL MODULO INVENTARIOS
	$idEmpresa = $_POST['idEmpresa'];
    $idUnidadNegocio = $_POST['idUnidadNegocio'];
    $txt_UnidadDeNegocio = "";
    if($idUnidadNegocio!='0'){
        $txt_UnidadDeNegocio=" WHERE (Clasificacion.UnidadDeNegocio='$idUnidadNegocio' OR Clasificacion.UnidadDeNegocio IS NULL)";
    }
	$idDestino = $_POST['idDestino'];
    $FechaInicioSaldo = $_POST['FechaInicioSaldo'];
	$FechaFinSaldo = $_POST['FechaFinSaldo'];
	$echo = '<div class="table-responsive1">';
    $sql_group = "SELECT a.Proceso,a.PositionProceso,a.TransaccionDetalle 
    FROM dbo.Get_SaldoInventarioDestino_v2('$idEmpresa','$idDestino','$FechaInicioSaldo','$FechaFinSaldo') AS a 
    INNER JOIN Clasificacion ON a.idClasificacion=Clasificacion.idClasificacion $txt_UnidadDeNegocio
    GROUP BY a.Proceso,a.PositionProceso,a.TransaccionDetalle ORDER BY a.PositionProceso,a.TransaccionDetalle";
    $resul_group=sqlsrv_query($conn,utf8_decode($sql_group),$params,$options);

	$sql = "SELECT a.Empresa, a.idEmpresa, a.Destino, a.idDestino, a.Clasificacion, a.idClasificacion, a.FechaSaldo, a.SaldoInicial, a.Proceso, a.PositionProceso, 
    a.iTransacion, a.TransaccionDetalle, a.PositionTransaccion, sum(a.ToneladasProceso) as ToneladasProceso, a.FechaFinSaldo, a.SaldoFinal 
    FROM dbo.Get_SaldoInventarioDestino_v2('$idEmpresa','$idDestino','$FechaInicioSaldo','$FechaFinSaldo') AS a
    INNER JOIN Clasificacion ON a.idClasificacion=Clasificacion.idClasificacion 
    LEFT JOIN UnidadDeNegocio ON Clasificacion.UnidadDeNegocio=UnidadDeNegocio.idUnidadNegocio $txt_UnidadDeNegocio
    GROUP BY a.Empresa, a.idEmpresa, a.Destino, a.idDestino,UnidadDeNegocio.Descripcion, a.Clasificacion, a.idClasificacion, a.FechaSaldo, a.SaldoInicial, a.Proceso, 
     a.PositionProceso, a.iTransacion, a.TransaccionDetalle, a.PositionTransaccion, a.FechaFinSaldo, a.SaldoFinal
     ORDER BY a.Destino,UnidadDeNegocio.Descripcion,a.Clasificacion,a.PositionProceso,a.TransaccionDetalle";
	$resul=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
	$rows = sqlsrv_num_rows($resul_group);
	if($rows>0){
        $echo.='<br><table class="table table-bordered">
            <thead>
                <tr>';
        $BAND_CUENTA_SUBNIVEL = 0;
        $const_proceso = '';
        $const_idDestino = '';
        $const_idClasificacion = '';

        $var_position_proceso=0;
        $col_proceso = 0;
        $var_position_transaccion = 0;
        $rowspan_general = 2;
		while($aa = sqlsrv_fetch_array($resul_group)){
            $proceso = utf8_encode($aa['Proceso']);
            $transaccion = utf8_encode($aa['TransaccionDetalle']);

            if($const_proceso == '' || $const_proceso==$proceso){
                $const_proceso = $proceso;
                $array_transacciones[$const_proceso][$var_position_transaccion] = $transaccion;
                $col_proceso++;
                $var_position_transaccion++;
                $BAND_CUENTA_SUBNIVEL++;
            }else{
                $array_proceso[$var_position_proceso] = $const_proceso;
                $var_position_proceso++;
                $col_proceso = 0;
                $var_position_transaccion = 0;
                if($const_proceso <> 'Clasificación' && $const_proceso <> 'Preparación mezcla' && $const_proceso <> 'Coquización'){
                    $BAND_CUENTA_SUBNIVEL++;
                }
                $const_proceso = $proceso;
                $array_transacciones[$const_proceso][$var_position_transaccion] = $transaccion;
                $col_proceso++;
                $var_position_transaccion++;
                $BAND_CUENTA_SUBNIVEL++;
            }
		}
        $array_proceso[$var_position_proceso] = $const_proceso;
        $var_position_proceso++;
        if(isset($array_transacciones)){
            $rowspan_general++;
        }
        if($const_proceso <> 'Clasificación' && $const_proceso <> 'Preparación mezcla' && $const_proceso <> 'Coquización'){
            $BAND_CUENTA_SUBNIVEL++;
        }
        $colspan=$BAND_CUENTA_SUBNIVEL;
        $echo.='<th rowspan="'.$rowspan_general.'" style="vertical-align: middle; text-align: center;">Clasificación</th>
                <th rowspan="'.$rowspan_general.'" style="vertical-align: middle; text-align: center;">Fecha corte inicial</th>
                <th rowspan="'.$rowspan_general.'" style="vertical-align: middle; text-align: center;">Saldo Inicial (TM)</th>';
        if($colspan<>0){
            $echo.='<th colspan="'.$colspan.'" style="background-color: #B4A694; vertical-align: middle; text-align: center;">Procesos</th>';
        }
        $echo.='<th rowspan="'.$rowspan_general.'" style="vertical-align: middle; text-align: center;">Fecha corte final</th>
            <th rowspan="'.$rowspan_general.'" style="vertical-align: middle; text-align: center;">Saldo final (TM)</th>
        </tr><tr>';

        $array_colores_proceso['Entradas'] = '#C7AB32';
        $array_colores_proceso['Salidas'] = '#B3AA86';
        $array_colores_proceso['Preparación mezcla'] = '#E6AC50';
        $array_colores_proceso['Clasificación'] = '#87CEE0';
        $array_colores_proceso['Coquización'] = '#39B3AB';

        for ($i=0; $i < count($array_proceso); $i++){
            if($array_proceso[$i] <> 'Clasificación' && $array_proceso[$i] <> 'Preparación mezcla' && $array_proceso[$i] <> 'Coquización'){
                $colspan_title = count($array_transacciones[$array_proceso[$i]])+1;
            }else{
                $colspan_title = count($array_transacciones[$array_proceso[$i]]);
            }
            $echo.='<th colspan="'.$colspan_title.'" style="background-color: '.$array_colores_proceso[$array_proceso[$i]].'; vertical-align: middle; text-align: center;">'.$array_proceso[$i].'</th>';
        }
        $echo.='</tr>';
        $echo.='<tr>';
        $const_proceso = '';
        for ($i=0; $i < count($array_proceso); $i++){
            for ($j=0; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                if($const_proceso == '' || $const_proceso == $array_proceso[$i]){
                    $const_proceso = $array_proceso[$i];
                    $echo.='<th style="background-color: '.$array_colores_proceso[$const_proceso].'; vertical-align: middle; text-align: center;">'.$array_transacciones[$array_proceso[$i]][$j].'</th>';
                }elseif($const_proceso<>$array_proceso[$i]){
                    if($const_proceso <> 'Clasificación' && $const_proceso <> 'Preparación mezcla' && $const_proceso <> 'Coquización'){
                        $echo.='<th style="background-color: '.$array_colores_proceso[$const_proceso].'; vertical-align: middle; text-align: center;">Total '.$const_proceso.'</th>';
                    }
                    $const_proceso = $array_proceso[$i];
                    $echo.='<th style="background-color: '.$array_colores_proceso[$const_proceso].'; vertical-align: middle; text-align: center;">'.$array_transacciones[$array_proceso[$i]][$j].'</th>';
                }
            }
        }
        if($i==count($array_proceso)){
            if($const_proceso <> 'Clasificación' && $const_proceso <> 'Preparación mezcla' && $const_proceso <> 'Coquización'){
                $echo.='<th style="background-color: '.$array_colores_proceso[$const_proceso].'; vertical-align: middle; text-align: center;">Total '.$const_proceso.'</th>';
            }
        }
        /*echo '<pre>';
        print_r($array_proceso);
        print_r($array_transacciones);
        echo '</pre>';*/
        $echo.='</tr>';
        $echo.='</thead>';
        $const_clasificacion = '';
        $search_position_proceso = 0;
        $search_position_transaccion = 0;
        $const_SaldoFinal = 0;
        $const_proceso = '';
        $const_idDestino = '';
        $const_idClasificacion = '';
        $sum_tm_proceso = 0;
        while($aa = sqlsrv_fetch_array($resul)){
            $idEmpresa = $aa['idEmpresa'];
            $idDestino = $aa['idDestino'];
            $idClasificacion = $aa['idClasificacion'];
            $FechaSaldo = date_format($aa['FechaSaldo'],'Y-m-d');
            $FechaFinSaldo = date_format($aa['FechaFinSaldo'],'Y-m-d');
            $Clasificacion = utf8_encode($aa['Clasificacion']);
            $SaldoInicial = number_format($aa['SaldoInicial'],2);
            $Proceso = utf8_encode($aa['Proceso']);
            $TransaccionDetalle = utf8_encode($aa['TransaccionDetalle']);
            $SaldoFinal = number_format($aa['SaldoFinal'],2);

            if($const_proceso == '' || $const_proceso==$proceso){
                $const_proceso=$Proceso;
            }
            if($const_clasificacion==''){
                $const_clasificacion = $Clasificacion;
                $const_idClasificacion = $idClasificacion;
                $const_SaldoFinal = $SaldoFinal;
                $echo.='<tr>';
                $echo.='<td>'.utf8_encode($aa['Clasificacion']).'</td>';
                $echo.='<td>'.$FechaSaldo.'</td>';
                $echo.='<td>'.number_format($aa['SaldoInicial'],2).'</td>';

                for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                    $proceso_foreach = $array_proceso[$i];
                    for ($j=0; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                        $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                        if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                            $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                            $a_href_fin = '</a>';
                            $echo.='<td>'.$a_href_ini.number_format($aa['ToneladasProceso'],2).$a_href_fin.'</td>';
                            $search_position_proceso = array_search($Proceso, $array_proceso);
                            $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                            $sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                            break 2;
                        }else{
                            $echo.='<td>0.00</td>';
                        }
                    }
                    if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                        if($sum_tm_proceso>0){
                            $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                            $a_href_fin = '</a>';
                        }else{
                            $a_href_ini = '';
                            $a_href_fin = '';
                        }
                        $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                        $sum_tm_proceso = 0;
                    }
                }
            }elseif($const_clasificacion==$Clasificacion){
                for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                    $proceso_foreach = $array_proceso[$i];
                    if($search_position_proceso==$i){
                        $search_position_transaccion=$search_position_transaccion+1;
                    }else{
                        $search_position_transaccion=0;
                    }
                    if($search_position_transaccion!=count($array_transacciones[$array_proceso[$i]])){
                        for ($j=$search_position_transaccion; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                            $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                            if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                                $a_href_fin = '</a>';
                                $echo.='<td>'.$a_href_ini.number_format($aa['ToneladasProceso'],2).$a_href_fin.'</td>';
                                $search_position_proceso = array_search($Proceso, $array_proceso);
                                $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                                $sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                                break 2;
                            }else{
                                $echo.='<td>0.00</td>';
                            }
                        }
                    }
                    if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                        if($sum_tm_proceso>0){
                            $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                            $a_href_fin = '</a>';
                        }else{
                            $a_href_ini = '';
                            $a_href_fin = '';
                        }
                        $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                        $sum_tm_proceso = 0;
                    }
                }
            }elseif($const_clasificacion<>$Clasificacion){
                for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                    $proceso_foreach = $array_proceso[$i];
                    if($search_position_proceso==$i){
                        $search_position_transaccion=$search_position_transaccion+1;
                    }else{
                        $search_position_transaccion=0;
                    }
                    if($search_position_transaccion!=count($array_transacciones[$array_proceso[$i]])){
                        for ($j=$search_position_transaccion; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                            $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                            if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                                $a_href_fin = '</a>';
                                $echo.='<td>0.00</td>';
                                $search_position_proceso = array_search($Proceso, $array_proceso);
                                $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                                //$sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                                //break 2;
                            }else{
                                $echo.='<td>0.00</td>';
                            }
                        }
                    }
                    if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                        if($sum_tm_proceso>0){
                            $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                            $a_href_fin = '</a>';
                        }else{
                            $a_href_ini = '';
                            $a_href_fin = '';
                        }
                        $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                        $sum_tm_proceso = 0;
                    }
                }
                $echo.='<td>'.$FechaFinSaldo.'</td>';
                $echo.='<td>'.$const_SaldoFinal.'</td>';
                $echo.='</tr>';
                $sum_tm_proceso = 0;
                /*********************************************/
                $const_clasificacion = $Clasificacion;
                $const_idClasificacion = $idClasificacion;
                $const_SaldoFinal = $SaldoFinal;
                $search_position_proceso = 0;
                $search_position_transaccion = 0;
                $echo.='<tr>';
                $echo.='<td>'.utf8_encode($aa['Clasificacion']).'</td>';
                $echo.='<td>'.$FechaSaldo.'</td>';
                $echo.='<td>'.number_format($aa['SaldoInicial'],2).'</td>';

                for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                    $proceso_foreach = $array_proceso[$i];
                    for ($j=0; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                        $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                        if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                            $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                            $a_href_fin = '</a>';
                            $echo.='<td>'.$a_href_ini.number_format($aa['ToneladasProceso'],2).$a_href_fin.'</td>';
                            $search_position_proceso = array_search($Proceso, $array_proceso);
                            $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                            $sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                            break 2;
                        }else{
                            $echo.='<td>0.00</td>';
                        }
                    }
                    if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                        if($sum_tm_proceso>0){
                            $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                            $a_href_fin = '</a>';
                        }else{
                            $a_href_ini = '';
                            $a_href_fin = '';
                        }
                        $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                        $sum_tm_proceso = 0;
                    }
                }
            }
        }
        for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
            $proceso_foreach = $array_proceso[$i];
            if($search_position_proceso==$i){
                $search_position_transaccion=$search_position_transaccion+1;
            }else{
                $search_position_transaccion=0;
            }
            if($search_position_transaccion!=count($array_transacciones[$array_proceso[$i]])){
                for ($j=$search_position_transaccion; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                    $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                    if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                        $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                        $a_href_fin = '</a>';
                        $echo.='<td>'.$a_href_ini.number_format($aa['ToneladasProceso'],2).$a_href_fin.'</td>';
                        $search_position_proceso = array_search($Proceso, $array_proceso);
                        $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                        $sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                        break 2;
                    }else{
                        $echo.='<td>0.00</td>';
                    }
                }
            }
            if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                if($sum_tm_proceso>0){
                    $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                    $a_href_fin = '</a>';
                }else{
                    $a_href_ini = '';
                    $a_href_fin = '';
                }
                $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                $sum_tm_proceso = 0;
            }
        }
        $echo.='<td>'.$FechaFinSaldo.'</td>';
        $echo.='<td>'.$const_SaldoFinal.'</td>';
        $echo.='</tr>';
    }
	$echo.='</div>';
	echo $echo;
}elseif($_POST['band'] == 23){ //CONSULTA LAS PREPARACIONES DE MEZCLA
	$lugar_trabajo = $_POST['lugar_trabajo_produccion'];
	$empresa = $_POST['empresa_preparacion_serach'];
	if($empresa == '0'){
		$txt_empresa = " ";
	}else{
		$txt_empresa = "AND idEmpresa='$empresa' ";
	}
	$mezcla = $_POST['mezcla_producida_search'];
	if($mezcla != '0' && $mezcla != '0'){
		$txt_mezcla = "AND idClasificacionProducido='$mezcla' ";
	}else{
		$txt_mezcla = " ";
	}
	$fecha_inicio = $_POST['fecha_inicio_search'];
	$fecha_fin = $_POST['fecha_fin_search'];

	$resul_array= array();
	$resul_array_clasif = array();
	$resul_array_produc = array();
	$i = 0;
	$var = 0;
	/******************** BUSCA LOS MATERIALES ALIMENTADOS ********************/
	$sql_clasif = "SELECT MaterialAlimentado FROM vPreparacion_recetas
		WHERE idDestino='$lugar_trabajo' $txt_empresa $txt_mezcla AND FechaPreparacion BETWEEN '$fecha_inicio' AND '$fecha_fin'
		GROUP BY MaterialAlimentado ORDER BY MaterialAlimentado";
	$resul=sqlsrv_query($conn,$sql_clasif,$params,$options);
	$rows = sqlsrv_num_rows($resul);
	if($rows>0){
		while ($rows=sqlsrv_fetch_array($resul)){
            $resul_array[$i] = '['.utf8_encode($rows['MaterialAlimentado']).']';
            $resul_array_clasif[$i] = utf8_encode($rows['MaterialAlimentado']);
            $i++;
        }
        /***************** BUSCA LOS MATERIALES PRODUCIDOS *****************/
        $sql_producido = "SELECT MaterialProducido FROM vPreparacion_recetas
		WHERE idDestino='$lugar_trabajo' $txt_empresa $txt_mezcla AND FechaPreparacion BETWEEN '$fecha_inicio' AND '$fecha_fin'
		GROUP BY MaterialProducido ORDER BY MaterialProducido";
		$resul_produc=sqlsrv_query($conn,$sql_producido,$params,$options);
		$rows_produc = sqlsrv_num_rows($resul_produc);
		if($rows_produc>0){
			while ($aa_produc=sqlsrv_fetch_array($resul_produc)){
	            $resul_array_produc[$var] = utf8_encode($aa_produc['MaterialProducido']);
	            $var++;
	        }
	    }
	}else{
		$resul_array[$i]='[0]';
		$resul_array_clasif[$i] = '';
	}
	$cadena= implode(',',$resul_array);
	$sql_pivot = "SELECT * 
	FROM (SELECT idReceta,Receta,idPreparacion,Empresa,FechaPreparacion,MaterialProducido,ToneladasProducido,MaterialAlimentado,ToneladasAlimentado,Usuario,FechaRegistro
			FROM vPreparacion_recetas 
		WHERE idDestino='$lugar_trabajo' $txt_empresa $txt_mezcla AND FechaPreparacion BETWEEN '$fecha_inicio' AND '$fecha_fin'
		GROUP BY idReceta,Receta,idPreparacion,Empresa,FechaPreparacion,MaterialProducido,ToneladasProducido,MaterialAlimentado,ToneladasAlimentado,Usuario,FechaRegistro
		) AS SourceTable PIVOT(SUM([ToneladasAlimentado]) FOR [MaterialAlimentado] IN(".$cadena.")) AS PivotTable
	ORDER BY FechaPreparacion DESC,Empresa,Receta,[MaterialProducido]";
	$resul_pivot=sqlsrv_query($conn,$sql_pivot,$params,$options);
    $rows = sqlsrv_num_rows($resul_pivot);
    if($rows>0){
    	$tabla_resultados='<div class="table-responsive1"><div class="col-sm-12">';
        $tabla_resultados.='<table class="table table-bordered" border="1" align="center"><thead><tr>';
        $tabla_resultados.= '<th rowspan="2" style="vertical-align: middle; background-color: #82DBB8;" align="center"><b>Fecha preparación</b></th>';
        $tabla_resultados.= '<th rowspan="2" style="vertical-align: middle; background-color: #82DBB8;" align="center"><b>Empresa</b></th>';
        $tabla_resultados.= '<th rowspan="2" style="vertical-align: middle; background-color: #82DBB8;" align="center"><b>Receta</b></th>';
        $tabla_resultados.= '<th colspan="'.count($resul_array_produc).'" style="background-color: #82DBB8;" align="center"><b>Material Producido</b></th>';
        $tabla_resultados.= '<th colspan="'.count($resul_array_clasif).'" style="background-color: #82DBB8;" align="center"><b>Material Alimentado</b></th>';
        if(isset($_SESSION['Array_empresa']['ELIMINAR_PREPARACION_MEZCLA'])){
            $tabla_resultados.='<th rowspan="2" style="vertical-align: middle; background-color: #82DBB8;" align="center">Acciones</th>';
        }
        $tabla_resultados.= '</tr><tr>';
        for ($i=0; $i < count($resul_array_produc); $i++){
        	$tabla_resultados.= '<th style="vertical-align: middle; background-color: #82DBB8;" align="center" align="center"><b>'.$resul_array_produc[$i].'</b></th>';
        }
        for ($i=0; $i < count($resul_array_clasif); $i++){
        	$tabla_resultados.= '<th align="center" style="background-color: #82DBB8;"><b>'.$resul_array_clasif[$i].'</b></th>';
        }
        $tabla_resultados.='</tr></thead>';
        while($aa = sqlsrv_fetch_array($resul_pivot)){
        	$tabla_resultados.='<tr>';
        	$tabla_resultados.='<td title="Registrado por '.utf8_encode($aa['Usuario']).' el dia '.date_format($aa['FechaRegistro'],'d-m-Y').' a las '.date_format($aa['FechaRegistro'],'H:i').'">'.date_format($aa['FechaPreparacion'],'Y-m-d').'</td>';
        	$tabla_resultados.='<td>'.utf8_encode($aa['Empresa']).'</td>';
            $idReceta=$aa['idReceta'];
            $sql_detalle = "SELECT MaterialAlimentado,porcentaje FROM vPreparacion_recetas WHERE idReceta='$idReceta' GROUP BY MaterialAlimentado,porcentaje";
            $res = sqlsrv_query($conn,$sql_detalle);
            $title_receta = '';
            while($aa_detail = sqlsrv_fetch_array($res)){
                $array_porcentaje[$aa_detail['MaterialAlimentado']] = $aa_detail['porcentaje'];
                $title_receta.= '('.utf8_encode($aa_detail['MaterialAlimentado']).'-'.$aa_detail['porcentaje'].'%) ';
            }
            $tabla_resultados.='<td><p title="'.$title_receta.'">'.utf8_encode($aa['Receta']).'</p></td>';
        	//$tabla_resultados.='<td>'.utf8_encode($aa['Material producido']).'</td>';
        	$search_position = array_search(utf8_encode($aa['MaterialProducido']),$resul_array_produc);
        	for($j=0; $j<count($resul_array_produc); $j++){
        		if($resul_array_produc[$j]==utf8_encode($aa['MaterialProducido'])){
        			$tm_producido = $aa['ToneladasProducido'];
        			$css = "";
        		}else{
        			$tm_producido=0;
        			$css = "color: red";
        		}
        		$tabla_resultados.='<td style="'.$css.'">'.number_format($tm_producido,2).'</td>';
        	}
        	for($j=0; $j<count($resul_array_clasif); $j++){
                if($aa[$resul_array_clasif[$j]] == ''){
                    $tm_array_clasi = number_format(0,2);
                    $css = "color: red";
                    $txt_porcentaje = '';
                }else{
                    $tm_array_clasi = number_format($aa[$resul_array_clasif[$j]],2);
                    $css = "";
                    $txt_porcentaje = '  <b>('.$array_porcentaje[$resul_array_clasif[$j]].'%)</b>';
                }
                $tabla_resultados.= '<td align="center" style="'.$css.'">'.$tm_array_clasi.$txt_porcentaje.'</td>';
            }
            if(isset($_SESSION['Array_empresa']['ELIMINAR_PREPARACION_MEZCLA'])){
                $tabla_resultados.='<td style="text-align: center;"><button class="btn btn-danger" onclick="delete_preparacion_mezcla(\''.$aa['idPreparacion'].'\')"><span class="glyphicon glyphicon-trash"></span></button></td>';
            }
            $tabla_resultados.= '</tr>';
        }
        $tabla_resultados.='</table></div></div>';
    }else{
    	$tabla_resultados = '';
    }
    echo $tabla_resultados;
}elseif($_POST['band']==24){ //CONSULTA LAS ALIMENTACIONES Y PRODUCCIONES EN EL MODULO ESTRUCTURA HORNOS
	$idPlanta = $_POST['idPlanta'];
	$empresa = $_POST['empresa'];
	if($empresa == '0'){
		$txt_empresa = " ";
	}else{
		$txt_empresa = "AND idEmpresa='$empresa' ";
	}
	$clasificacion_alimentada = $_POST['clasificacion_alimentada'];
	if($clasificacion_alimentada != '0' && $clasificacion_alimentada != '0'){
		$txt_clasificacion_alimentada = "AND idClasificacionAlimentacion='$clasificacion_alimentada' ";
	}else{
		$txt_clasificacion_alimentada = " ";
	}
	$clasificacion_producida = $_POST['clasificacion_producida'];
    //$clasificacion_producida = '0';
	if($clasificacion_producida != '0' && $clasificacion_producida != '0'){
		$txt_clasificacion_producida = "AND idClasificacionProduccion='$clasificacion_producida' ";
	}else{
		$txt_clasificacion_producida = "";
	}
	$fecha_ini = $_POST['fecha_ini'];
    $fecha_fin = $_POST['fecha_fin'];
    if(($clasificacion_alimentada != '0' && $clasificacion_alimentada != '0') && ($clasificacion_producida == '0' && $clasificacion_producida == '0')){
        $txt_fecha = "(CAST(FechaAlimentacion AS date) BETWEEN '$fecha_ini' AND '$fecha_fin') AND";
    }elseif(($clasificacion_alimentada == '0' && $clasificacion_alimentada == '0') && ($clasificacion_producida != '0' && $clasificacion_producida != '0')){
        $txt_fecha = "(CAST(FechaProduccion AS date) BETWEEN '$fecha_ini' AND '$fecha_fin') AND";
    }else{
        $txt_fecha = "(CAST(FechaAlimentacion AS date) BETWEEN '$fecha_ini' AND '$fecha_fin' OR CAST(FechaProduccion AS date) BETWEEN '$fecha_ini' AND '$fecha_fin') AND";
    }
	$tabla_resultados = '<br><div class="table-responsive1">';
	$sql = "SELECT Empresa,idEmpresa,CAST(FechaAlimentacion as date) AS FechaAlimentacion, idBateria,Bateria,
			COUNT(idAlimentacion_hornos) AS Hornos,UsuarioAlimentacion,ClasificacionAlimentacion,idClasificacionAlimentacion,
			ROUND(AVG(Capacidad),2) AS Capacidad_AVG,
			SUM(Capacidad) AS SUMA_Capacidad,
			ROUND(AVG(Indice_carga),2) AS Indice_carga_AVG,
			SUM(Indice_carga) AS SUMA_Indice_carga,
			CAST(FechaProduccion AS date) AS FechaProduccion,
			COUNT(idProduccion_hornos) AS HornosProduccion,UsuarioProduccion,
			ClasificacionProduccion,
			idClasificacionProduccion,
			ROUND(AVG(CapacidadDeshorne),2) AS CapacidadDeshorne_AVG,
			SUM(CapacidadDeshorne) AS SUMA_CapacidadDeshorne,
			ROUND(AVG(Indice_deshorne),2) AS Indice_deshorne_AVG,
			SUM(Indice_deshorne) AS SUMA_Indice_deshorne
		FROM vCoquizacion
		WHERE $txt_fecha idPlanta='$idPlanta'
			$txt_empresa $txt_clasificacion_alimentada $txt_clasificacion_producida
		GROUP BY Empresa,idEmpresa,CAST(FechaAlimentacion as date),UsuarioAlimentacion,idBateria,Bateria,ClasificacionAlimentacion,idClasificacionAlimentacion,
			CAST(FechaProduccion AS date),UsuarioProduccion,ClasificacionProduccion,idClasificacionProduccion
		ORDER BY Empresa,Bateria,CAST(FechaAlimentacion AS date) DESC,ClasificacionAlimentacion,ClasificacionProduccion";
	$resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);
    if($rows>0){
        $count_colspan=6;
        if(isset($_SESSION['Array_empresa']['ELIMINAR_COQUIZACION'])){
            $count_colspan++;
        }
    	$tabla_resultados.='<div class="col-sm-12">';
        $tabla_resultados.='<table class="table table-bordered" border="1" align="center"><thead><tr>';
        $tabla_resultados.= '<th rowspan="2" style="vertical-align: middle;" align="center"><b>Empresa</b></th>';
        $tabla_resultados.= '<th colspan="'.$count_colspan.'" align="center" style="background-color: #778EC2;"><b>Alimentación</b></th>';
        $tabla_resultados.= '<th colspan="'.$count_colspan .'" align="center" style="background-color: #C2777D;"><b>Producción</b></th>';
        if(isset($_SESSION['Array_empresa']['ELIMINAR_COQUIZACION'])){
            $tabla_resultados.='<th rowspan="2" style="vertical-align: middle;" align="center">Acciones</th>';
        }
        $tabla_resultados.= '</tr><tr>';
        if(isset($_SESSION['Array_empresa']['ELIMINAR_COQUIZACION'])){
            $tabla_resultados.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Usuario</b></th>';    
        }
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Fecha</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Bateria</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Hornos</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Clasificación</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Capacidad (TM)</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Cargue (TM)</b></th>';
        if(isset($_SESSION['Array_empresa']['ELIMINAR_COQUIZACION'])){
            $tabla_resultados.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Usuario</b></th>';
        }
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Fecha</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Bateria</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Hornos</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Clasificación</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Capacidad (TM)</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Deshorne (TM)</b></th>';
        $tabla_resultados.='</tr></thead>';
        $band_fisrt_date = '';
    	while($aa = sqlsrv_fetch_array($resul)){
            $idBateria = $aa['idBateria'];
    		$tabla_resultados.='<tr>';
    		$tabla_resultados.='<td>'.utf8_encode($aa['Empresa']).'</td>';
            if(isset($_SESSION['Array_empresa']['ELIMINAR_COQUIZACION'])){
                $tabla_resultados.='<td>'.utf8_encode($aa['UsuarioAlimentacion']).'</td>';
            }
    		$tabla_resultados.='<td>'.date_format($aa['FechaAlimentacion'],'Y-m-d').'</td>';
    		$tabla_resultados.='<td>'.$aa['Bateria'].'</td>';
            $tabla_resultados.='<td>'.$aa['Hornos'];
            //if($aa['Hornos']>1){
                $tabla_resultados.=' &nbsp; <button class="btn btn-xs" onclick="search_registers_coquizacion_details(\''.$aa['idEmpresa'].'\',\''.date_format($aa['FechaAlimentacion'],'Y-m-d').'\',\''.$idBateria.'\',\''.$aa['idClasificacionAlimentacion'].'\',\'Alimentación\')"><span class="glyphicon glyphicon-eye-open"></span></button></td>';
            /*}else{
                $tabla_resultados.='</td>';
            }*/
    		$tabla_resultados.='<td>'.utf8_encode($aa['ClasificacionAlimentacion']).'</td>';
    		$tabla_resultados.='<td>'.number_format($aa['Capacidad_AVG'],2).' ('.number_format($aa['SUMA_Capacidad'],2).')</td>';
    		$tabla_resultados.='<td>'.number_format($aa['Indice_carga_AVG'],2).' ('.number_format($aa['SUMA_Indice_carga'],2).')</td>';
    		if($aa['ClasificacionProduccion'] == null || $aa['ClasificacionProduccion'] == ''){
    			$tabla_resultados.='<td colspan="'.$count_colspan.'" align="center"><b style="color: red">PENDIENTE</b></td>';	
    		}else{
                if(isset($_SESSION['Array_empresa']['ELIMINAR_COQUIZACION'])){
                    $tabla_resultados.='<td>'.utf8_encode($aa['UsuarioProduccion']).'</td>';
                }
	    		$tabla_resultados.='<td>'.date_format($aa['FechaProduccion'],'Y-m-d').'</td>';
	    		$tabla_resultados.='<td>'.$aa['Bateria'].'</td>';
                $tabla_resultados.='<td>'.$aa['HornosProduccion'].'</td>';
	    		$tabla_resultados.='<td>'.utf8_encode($aa['ClasificacionProduccion']).'</td>';
	    		$tabla_resultados.='<td>'.number_format($aa['CapacidadDeshorne_AVG'],2).' ('.number_format($aa['SUMA_CapacidadDeshorne'],2).')</td>';
	    		$tabla_resultados.='<td>'.number_format($aa['Indice_deshorne_AVG'],2).' ('.number_format($aa['SUMA_Indice_deshorne'],2).')</td>';
	    	}
            if(isset($_SESSION['Array_empresa']['ELIMINAR_COQUIZACION'])){
                $tabla_resultados.='<td style="text-align: center;"><button class="btn btn-danger" onclick="delete_coquizacion(\''.$aa['idEmpresa'].'\',\''.$idPlanta.'\',\''.$idBateria.'\',\''.$aa['idClasificacionAlimentacion'].'\',\''.date_format($aa['FechaAlimentacion'],'Y-m-d').'\')"><span class="glyphicon glyphicon-trash"></span></button></td>';
            }
    		$tabla_resultados.='</tr>';
    	}
    	$tabla_resultados.='</table></div></div>';
    }
    echo $tabla_resultados;
}elseif($_POST['band']==25){ //OPCION PARA MOSTRAR EL DETALLE DE LOS PROCESOS EN EL MODULO INVENTARIOS
	$idEmpresa = $_POST['idEmpresa'];
    $sql_empresa = "SELECT * FROM Proveedores WHERE idProveedor='$idEmpresa'";
    $resul_empresa=sqlsrv_query($conn,$sql_empresa,$params,$options);
    $rows_empresa = sqlsrv_num_rows($resul_empresa);
    while($aa_empresa = sqlsrv_fetch_array($resul_empresa)){
        $nom_empresa = utf8_encode($aa_empresa['Alias']);
    }
	$idDestino = $_POST['idDestino'];
    $sql_destino = "SELECT * FROM Destino WHERE idDestino='$idDestino'";
    $resul_destino=sqlsrv_query($conn,$sql_destino,$params,$options);
    $rows_destino = sqlsrv_num_rows($resul_destino);
    while($aa_destino = sqlsrv_fetch_array($resul_destino)){
        $nom_destino = utf8_encode($aa_destino['Descripcion']);
    }
	$idClasificacion = $_POST['idClasificacion'];
    $sql_clasificacion = "SELECT * FROM Clasificacion WHERE idClasificacion='$idClasificacion'";
    $resul_clasificacion=sqlsrv_query($conn,$sql_clasificacion,$params,$options);
    $rows_clasificacion = sqlsrv_num_rows($resul_clasificacion);
    while($aa_clasificacion = sqlsrv_fetch_array($resul_clasificacion)){
        $nom_clasificacion = utf8_encode($aa_clasificacion['Descripcion']);
    }
	$fechaIni = $_POST['fechaIni'];
	$fechaFin = $_POST['fechaFin'];
	$proceso = $_POST['proceso'];
    $transaccion = $_POST['transaccion'];
    $txt_transaccion = "";
    if($transaccion<>'N/A'){
        $txt_transaccion = "AND A.Descripcion='$transaccion'";
    }
	$echo = '<br>';
    $echo.='<center> ('.$nom_empresa.')'.' || ('.$nom_destino.')'.' || ('.$nom_clasificacion.') <button type="button" id="btn_download_excel_detail" class="btn btn-success btn-xs" onclick="load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$idClasificacion.'\',\''.$fechaIni.'\',\''.$fechaFin.'\',\'Excel-'.$proceso.'\',\''.$transaccion.'\')">Excel <span class="glyphicon glyphicon-save"></span></button></center>';
    $echo.='<form method="POST" id="form_excel">
            <input type="hidden" id="tabla_excel" name="tabla_excel">
            <input type="hidden" id="TipoMovimiento_tabla_excel" name="TipoMovimiento_tabla_excel">
            <div id="div_tabla_excel"></div>
        </form>';
	if($proceso == 'Entradas'){
    $sql="SELECT tz_MovimientoTransporte.FechaRegistro,tz_MovimientoTransporte.Proveedor,
        tz_MovimientoTransporte.Origen,A.Descripcion AS Transaccion,SUM(tz_MovimientoTransporte.Toneladas) AS Toneladas
    FROM dbo.tz_MovimientoTransporte 
        INNER JOIN Movimiento ON dbo.tz_MovimientoTransporte.NumeroTransaccion=Movimiento.NumeroTransaccion
        INNER JOIN Productos on Movimiento.idProducto=Productos.idProducto
        INNER JOIN Origenes ON Movimiento.idOrigen=Origenes.idOrigen
        LEFT JOIN MovimientoTransaccion ON Movimiento.NumeroTransaccion=MovimientoTransaccion.NumeroTransaccion
        LEFT JOIN dbo.TransaccionesMovimiento() AS A ON MovimientoTransaccion.iTransaccion=A.iTransaccion
    WHERE Movimiento.idEmpresa='$idEmpresa' AND Movimiento.idDestino='$idDestino' AND Movimiento.idDestinoAcopio IS NULL
        AND idClasificacion='$idClasificacion' AND CAST(Movimiento.FechaRegistro AS date)>'$fechaIni' AND CAST(Movimiento.FechaRegistro AS date)<='$fechaFin' $txt_transaccion
    GROUP BY tz_MovimientoTransporte.FechaRegistro,tz_MovimientoTransporte.Origen,tz_MovimientoTransporte.Proveedor,A.Descripcion
    UNION 
    SELECT tz_MovimientoTransporte.SalidaDestino AS FechaRegistro,tz_MovimientoTransporte.Proveedor,
        tz_MovimientoTransporte.Origen,A.Descripcion AS Transaccion,SUM(tz_MovimientoTransporte.ToneladasRecepcion) AS Toneladas
    FROM dbo.tz_MovimientoTransporte
        INNER JOIN Movimiento ON dbo.tz_MovimientoTransporte.NumeroTransaccion=Movimiento.NumeroTransaccion
        INNER JOIN Productos on Movimiento.idProducto=Productos.idProducto
        INNER JOIN Origenes ON Movimiento.idOrigen=Origenes.idOrigen
        LEFT JOIN MovimientoTransaccion ON Movimiento.NumeroTransaccion=MovimientoTransaccion.NumeroTransaccion
        LEFT JOIN dbo.TransaccionesMovimiento() AS A ON MovimientoTransaccion.iTransaccion=A.iTransaccion
    WHERE Movimiento.idEmpresa='$idEmpresa' AND Movimiento.idDestino='$idDestino'
        AND idClasificacion='$idClasificacion' AND CAST(tz_MovimientoTransporte.SalidaDestino AS date)>'$fechaIni' AND CAST(tz_MovimientoTransporte.SalidaDestino AS date)<='$fechaFin' 
        AND tz_MovimientoTransporte.ToneladasRecepcion<>0 $txt_transaccion
    GROUP BY tz_MovimientoTransporte.SalidaDestino,tz_MovimientoTransporte.Origen,tz_MovimientoTransporte.Proveedor,A.Descripcion
    ORDER BY tz_MovimientoTransporte.FechaRegistro DESC,tz_MovimientoTransporte.Proveedor,tz_MovimientoTransporte.Origen";
	}
	if($proceso == 'Salidas'){
        $sql="SELECT tz_MovimientoTransporte.FechaRegistro,tz_MovimientoTransporte.RecepcionadoEn,A.Descripcion AS Transaccion,SUM(tz_MovimientoTransporte.Toneladas) AS Toneladas
    FROM dbo.tz_MovimientoTransporte
        INNER JOIN Movimiento ON dbo.tz_MovimientoTransporte.NumeroTransaccion=Movimiento.NumeroTransaccion
        INNER JOIN Productos on Movimiento.idProducto=Productos.idProducto
        INNER JOIN Origenes ON Movimiento.idOrigen=Origenes.idOrigen
        LEFT JOIN MovimientoTransaccion ON Movimiento.NumeroTransaccion=MovimientoTransaccion.NumeroTransaccion
        LEFT JOIN dbo.TransaccionesMovimiento() AS A ON MovimientoTransaccion.iTransaccion=A.iTransaccion
    WHERE Movimiento.idEmpresa='$idEmpresa' AND Movimiento.idDestinoAcopio='$idDestino' AND idClasificacion='$idClasificacion' 
        AND CAST(Movimiento.FechaRegistro AS date)>'$fechaIni' AND CAST(Movimiento.FechaRegistro AS date)<='$fechaFin' $txt_transaccion
    GROUP BY tz_MovimientoTransporte.FechaRegistro,tz_MovimientoTransporte.RecepcionadoEn,A.Descripcion
    ORDER BY tz_MovimientoTransporte.FechaRegistro DESC,tz_MovimientoTransporte.RecepcionadoEn";
	}
	if($proceso == 'Entradas'){
		$resul=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    	$rows = sqlsrv_num_rows($resul);
        //echo 'asasd '.$rows;
		$echo.='<div class="table-responsive1">
		<div class="row"><div class="col-sm-12">
		<table class="table table-hover table-bordered">
		<thead class="thead-dark">
		<tr>';
		$echo.='<th style="background-color: #8BCAF9; font-weight: bold;" class="header" scope="col">Fecha Registro</th>';
		$echo.='<th style="background-color: #8BCAF9; font-weight: bold;" class="header" scope="col">Proveedor</th>';
        $echo.='<th style="background-color: #8BCAF9; font-weight: bold;" class="header" scope="col">Origen</th>';
        $echo.='<th style="background-color: #8BCAF9; font-weight: bold;" class="header" scope="col">Transaccion</th>';
		$echo.='<th style="background-color: #8BCAF9; font-weight: bold;" class="header" scope="col">TM Netas</th>';
		$echo.='</tr></thead><tbody>';
        $var_fecha = '1900-01-01';
        $sum_tm_dia = 0;
        $cant_registers_dia = 0;
		while($corre = sqlsrv_fetch_array($resul)){
            $FechaRegistro = date_format($corre['FechaRegistro'],'Y-m-d');
            $Toneladas = number_format($corre['Toneladas'],'2');
            if($var_fecha == '1900-01-01' || $var_fecha==$FechaRegistro){
                $var_fecha=$FechaRegistro;
                $echo.='<tr>';
                $echo.='<td>'.$FechaRegistro.'</td>';
                $echo.='<td>'.utf8_encode($corre['Proveedor']).'</td>';
                $echo.='<td>'.utf8_encode($corre['Origen']).'</td>';
                $echo.='<td>'.utf8_encode($corre['Transaccion']).'</td>';
                $echo.='<td>'.$Toneladas.'</td>';
                $echo.='</tr>';
                $sum_tm_dia+=$corre['Toneladas'];
                $cant_registers_dia++;
            }elseif($var_fecha<>$FechaRegistro){
                if($cant_registers_dia>1){
                    $echo.='<tr style="background-color: powderblue;">';
                    $echo.='<td colspan="4" style="text-align: center;"><b>'.$var_fecha.'</b></td>';
                    $echo.='<td><b>'.number_format($sum_tm_dia,2).'</b></td>';
                    $echo.='</tr>';
                }
                $var_fecha=$FechaRegistro;
                $sum_tm_dia = 0;
                $cant_registers_dia = 0;
                $echo.='<tr>';
                $echo.='<td>'.$FechaRegistro.'</td>';
                $echo.='<td>'.utf8_encode($corre['Proveedor']).'</td>';
                $echo.='<td>'.utf8_encode($corre['Origen']).'</td>';
                $echo.='<td>'.utf8_encode($corre['Transaccion']).'</td>';
                $echo.='<td>'.$Toneladas.'</td>';
                $echo.='</tr>';
                $sum_tm_dia+=$corre['Toneladas'];
                $cant_registers_dia++;
            }
		}
        if($cant_registers_dia>1){
            $echo.='<tr style="background-color: powderblue;">';
            $echo.='<td colspan="4" style="text-align: center;"><b>'.$var_fecha.'</b></td>';
            $echo.='<td><b>'.number_format($sum_tm_dia,2).'</b></td>';
            $echo.='</tr>';
        }
		$echo.='</tbody></table></div></div></div>';
	}
    if($proceso == 'Salidas'){
        $resul=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
        $rows = sqlsrv_num_rows($resul);
        $echo.='<div class="table-responsive1">
        <div class="row"><div class="col-sm-12">
        <table class="table table-hover table-bordered">
        <thead class="thead-dark">
        <tr>';
        $echo.='<th style="background-color: #8BCAF9; font-weight: bold;" class="header" scope="col">Fecha Registro</th>';
        $echo.='<th style="background-color: #8BCAF9; font-weight: bold;" class="header" scope="col">Recepcionado En</th>';
        $echo.='<th style="background-color: #8BCAF9; font-weight: bold;" class="header" scope="col">Transaccion</th>';
        $echo.='<th style="background-color: #8BCAF9; font-weight: bold;" class="header" scope="col">TM Netas</th>';
        $echo.='</tr></thead><tbody>';
        $var_fecha = '1900-01-01';
        $sum_tm_dia = 0;
        $cant_registers_dia = 0;
        while($corre = sqlsrv_fetch_array($resul)){
            $FechaRegistro = date_format($corre['FechaRegistro'],'Y-m-d');
            $Toneladas = number_format($corre['Toneladas'],'2');
            if($var_fecha == '1900-01-01' || $var_fecha==$FechaRegistro){
                $var_fecha=$FechaRegistro;
                $echo.='<tr>';
                $echo.='<td>'.$FechaRegistro.'</td>';
                $echo.='<td>'.utf8_encode($corre['RecepcionadoEn']).'</td>';
                $echo.='<td>'.utf8_encode($corre['Transaccion']).'</td>';
                $echo.='<td>'.$Toneladas.'</td>';
                $echo.='</tr>';
                $sum_tm_dia+=$corre['Toneladas'];
                $cant_registers_dia++;
            }elseif($var_fecha<>$FechaRegistro){
                if($cant_registers_dia>1){
                    $echo.='<tr style="background-color: powderblue;">';
                    $echo.='<td colspan="3" style="text-align: center;"><b>'.$var_fecha.'</b></td>';
                    $echo.='<td><b>'.number_format($sum_tm_dia,2).'</b></td>';
                    $echo.='</tr>';
                }
                $var_fecha=$FechaRegistro;
                $sum_tm_dia = 0;
                $cant_registers_dia = 0;
                $echo.='<tr>';
                $echo.='<td>'.$FechaRegistro.'</td>';
                $echo.='<td>'.utf8_encode($corre['RecepcionadoEn']).'</td>';
                $echo.='<td>'.utf8_encode($corre['Transaccion']).'</td>';
                $echo.='<td>'.$Toneladas.'</td>';
                $echo.='</tr>';
                $sum_tm_dia+=$corre['Toneladas'];
                $cant_registers_dia++;
            }
        }
        if($cant_registers_dia>1){
            $echo.='<tr style="background-color: powderblue;">';
            $echo.='<td colspan="3" style="text-align: center;"><b>'.$var_fecha.'</b></td>';
            $echo.='<td><b>'.number_format($sum_tm_dia,2).'</b></td>';
            $echo.='</tr>';
        }
        $echo.='</tbody></table></div></div></div>';
    }
	if($proceso == 'Preparación mezcla'){
		$resul_array= array();
		$resul_array_clasif = array();
		$resul_array_produc = array();
		$i = 0;
		$var = 0;
		/******************** BUSCA LOS MATERIALES ALIMENTADOS ********************/
		$sql_clasif = "SELECT MaterialAlimentado FROM vPreparacion_recetas
			WHERE idEmpresa='$idEmpresa' AND idDestino='$idDestino' AND (idClasificacionProducido='$idClasificacion' 
				OR idClasificacionAlimentado='$idClasificacion') AND CAST(FechaPreparacion AS date)>'$fechaIni' AND CAST(FechaPreparacion AS date)<='$fechaFin'
			GROUP BY MaterialAlimentado ORDER BY MaterialAlimentado";
		$resul=sqlsrv_query($conn,$sql_clasif,$params,$options);
		$rows = sqlsrv_num_rows($resul);
		if($rows>0){
			while ($rows=sqlsrv_fetch_array($resul)){
	            $resul_array[$i] = '['.utf8_encode($rows['MaterialAlimentado']).']';
	            $resul_array_clasif[$i] = utf8_encode($rows['MaterialAlimentado']);
	            $i++;
	        }
	        /***************** BUSCA LOS MATERIALES PRODUCIDOS *****************/
	        $sql_producido = "SELECT MaterialProducido FROM vPreparacion_recetas
			WHERE idEmpresa='$idEmpresa' AND idDestino='$idDestino' AND (idClasificacionProducido='$idClasificacion' 
				OR idClasificacionAlimentado='$idClasificacion') AND CAST(FechaPreparacion AS date)>'$fechaIni' AND CAST(FechaPreparacion AS date)<='$fechaFin'
			GROUP BY MaterialProducido ORDER BY MaterialProducido";
			$resul_produc=sqlsrv_query($conn,$sql_producido,$params,$options);
			$rows_produc = sqlsrv_num_rows($resul_produc);
			if($rows_produc>0){
				while ($aa_produc=sqlsrv_fetch_array($resul_produc)){
		            $resul_array_produc[$var] = utf8_encode($aa_produc['MaterialProducido']);
		            $var++;
		        }
		    }
		}else{
			$resul_array[$i]='[0]';
			$resul_array_clasif[$i] = '';
		}
		$cadena= implode(',',$resul_array);
		$sql_pivot = "SELECT * 
		FROM (SELECT idPreparacion,Empresa,FechaPreparacion,MaterialProducido,ToneladasProducido,MaterialAlimentado,ToneladasAlimentado 
				FROM vPreparacion_recetas 
			WHERE idEmpresa='$idEmpresa' AND idDestino='$idDestino' AND CAST(FechaPreparacion AS date)>'$fechaIni' AND CAST(FechaPreparacion AS date)<='$fechaFin'
				AND (idClasificacionProducido='$idClasificacion' OR idClasificacionAlimentado='$idClasificacion')
			GROUP BY idPreparacion,Empresa,FechaPreparacion,MaterialProducido,ToneladasProducido,MaterialAlimentado,ToneladasAlimentado
			) AS SourceTable PIVOT(SUM([ToneladasAlimentado]) FOR [MaterialAlimentado] IN(".$cadena.")) AS PivotTable
		ORDER BY FechaPreparacion DESC,Empresa,[MaterialProducido]";
		$resul_pivot=sqlsrv_query($conn,$sql_pivot,$params,$options);
	    $rows = sqlsrv_num_rows($resul_pivot);
	    if($rows>0){
	    	$echo.='<div class="table-responsive1">
				<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">';
	        $echo.='<table class="table table-bordered" border="1" align="center"><thead><tr>';
	        $echo.= '<th class="header" scope="col" rowspan="2" style="vertical-align: middle; background-color: #82DBB8;" align="center"><b>Fecha preparación</b></th>';
	        $echo.= '<th class="header" scope="col" rowspan="2" style="vertical-align: middle; background-color: #82DBB8;" align="center"><b>Empresa</b></th>';
	        $echo.= '<th class="header" scope="col" colspan="'.count($resul_array_produc).'" align="center" style="background-color: #82DBB8;"><b>Material Producido</b></th>';
	        $echo.= '<th class="header" scope="col" colspan="'.count($resul_array_clasif).'" align="center" style="background-color: #82DBB8;"><b>Material Alimentado</b></th>';
	        $echo.= '</tr><tr>';
	        for ($i=0; $i < count($resul_array_produc); $i++){
	        	$echo.= '<th class="header" scope="col" style="vertical-align: middle; background-color: #82DBB8;" align="center" align="center"><b>'.$resul_array_produc[$i].'</b></th>';
	        }
	        for ($i=0; $i < count($resul_array_clasif); $i++){
	        	$echo.= '<th class="header" scope="col" align="center" style="background-color: #82DBB8;"><b>'.$resul_array_clasif[$i].'</b></th>';
	        }
	        $echo.='</tr></thead><tbody>';
	        while($aa = sqlsrv_fetch_array($resul_pivot)){
	        	$echo.='<tr>';
	        	$echo.='<td>'.date_format($aa['FechaPreparacion'],'Y-m-d').'</td>';
	        	$echo.='<td>'.utf8_encode($aa['Empresa']).'</td>';
	        	$search_position = array_search(utf8_encode($aa['MaterialProducido']),$resul_array_produc);
	        	for($j=0; $j<count($resul_array_produc); $j++){
	        		if($resul_array_produc[$j]==utf8_encode($aa['MaterialProducido'])){
	        			$tm_producido = $aa['ToneladasProducido'];
	        			$css = "";
	        		}else{
	        			$tm_producido=0;
	        			$css = "color: red";
	        		}
	        		$echo.='<td style="'.$css.'">'.number_format($tm_producido,2).'</td>';
	        	}
	        	for($j=0; $j<count($resul_array_clasif); $j++){
	                if($aa[$resul_array_clasif[$j]] == ''){
	                    $tm_array_clasi = number_format(0,2);
	                    $css = "color: red";
	                }else{
	                    $tm_array_clasi = number_format($aa[$resul_array_clasif[$j]],2);
	                    $css = "";
	                }
	                $echo.= '<td align="center" style="'.$css.'">'.$tm_array_clasi.'</td>';
	            }
	            $echo.= '</tr>';
	        }
	        $echo.='</tbody></table></div>';
	    }else{
	    	$echo = '';
	    }
	}
	if($proceso == 'Clasificación'){
	    $resul_array= array();
	    $i=0;//consulta para sacar las clasificaicones de material ese rango de fecha
	    $sql_clasi="SELECT '['+Clasificacion.Descripcion+']' as material
	        FROM servicio_clasificacion INNER JOIN
	            servicio_clasificacion_detalle ON servicio_clasificacion.id_clasif=servicio_clasificacion_detalle.id_clasif INNER JOIN
	            Clasificacion ON servicio_clasificacion_detalle.material=Clasificacion.idClasificacion INNER JOIN
	            Equipos ON servicio_clasificacion.equipo=Equipos.idEquipo
	        WHERE servicio_clasificacion.empresa='$idEmpresa' AND servicio_clasificacion.patio='$idDestino' 
                AND (servicio_clasificacion_detalle.material='$idClasificacion' OR servicio_clasificacion.material='$idClasificacion')
	        	AND CAST(servicio_clasificacion.fecha AS DATE)>'$fechaIni' AND CAST(servicio_clasificacion.fecha AS DATE)<='$fechaFin'
	        GROUP by Clasificacion.Descripcion";
	    $params = array();
	    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	    $resul_cla=sqlsrv_query($conn,$sql_clasi,$params,$options);
	    $rows = sqlsrv_num_rows($resul_cla);
	    if($rows==0)
	         $resul_array[$i]='[0]';
	    else{
	        while ($rows=sqlsrv_fetch_array($resul_cla)) {
	            $resul_array[$i] =$rows['material'];
	            $i++;
	        }
	    }
	    $count_materiales=count($resul_array);
	    $cadena= implode(',',$resul_array);

	    $resul_array_clasi= array();
	  
	    $sql_clasi="SELECT Clasificacion.Descripcion as material/*, servicio_clasificacion_detalle.objetivo*/
	        FROM servicio_clasificacion INNER JOIN
	            servicio_clasificacion_detalle ON servicio_clasificacion.id_clasif=servicio_clasificacion_detalle.id_clasif INNER JOIN
	            Clasificacion ON servicio_clasificacion_detalle.material=Clasificacion.idClasificacion INNER JOIN
	            Equipos ON servicio_clasificacion.equipo=Equipos.idEquipo
	        WHERE servicio_clasificacion.empresa='$idEmpresa' AND servicio_clasificacion.patio='$idDestino' 
                AND (servicio_clasificacion_detalle.material='$idClasificacion' OR servicio_clasificacion.material='$idClasificacion')
	        	AND CAST(servicio_clasificacion.fecha AS DATE)>'$fechaIni' AND CAST(servicio_clasificacion.fecha AS DATE)<='$fechaFin'
	        GROUP by Clasificacion.Descripcion";
	    $resul_cla= sqlsrv_query($conn, $sql_clasi);
	    $k=0;
	    while ($rows=sqlsrv_fetch_array($resul_cla)) {
	        $resul_array_clasi[$k] =$rows['material'];
	        $k++;
	    }

	    $sql_pivot="SELECT *
	    FROM
	    (   SELECT [servicio_clasificacion].[id_clasif], [servicio_clasificacion].[num_recibo], [servicio_clasificacion].[fecha],
	        [servicio_clasificacion].[horas_equipo], [servicio_clasificacion].[tm_aliment], [Pilas].[Descripcion] AS pila, [servicio_clasificacion].[semana], 
	        [servicio_clasificacion_detalle].[tm], [Proveedores_1].[Alias] as empresa, [Proveedores].[RazonSocial] AS proveedor,
	        [Equipos].[Descripcion]+' - '+[Equipos].[Identificacion] as equipo,[Actividades].[Descripcion] AS actividad, 
	        [UsuariosDetalle].[Nombre1]+' '+[UsuariosDetalle].[Apellido1] as usuario, [Clasificacion].[Descripcion] AS material_alimen,
	        [Clas_obj].[Descripcion] AS material_objetivo, [Clasificacion_1].[Descripcion] AS material_producido
	    FROM Clasificacion INNER JOIN
	        servicio_clasificacion INNER JOIN
	        servicio_clasificacion_detalle ON servicio_clasificacion.id_clasif = servicio_clasificacion_detalle.id_clasif INNER JOIN
	        Proveedores AS Proveedores_1 ON servicio_clasificacion.empresa = Proveedores_1.idProveedor INNER JOIN
	        Proveedores ON servicio_clasificacion.proveedor = Proveedores.idProveedor INNER JOIN
	        Equipos ON servicio_clasificacion.equipo = Equipos.idEquipo INNER JOIN
	        Actividades ON servicio_clasificacion.actividad = Actividades.idActividad INNER JOIN
	        UsuariosDetalle ON servicio_clasificacion.usuario = UsuariosDetalle.idUsuario ON Clasificacion.idClasificacion = servicio_clasificacion.material INNER JOIN
	        Clasificacion AS Clasificacion_1 ON servicio_clasificacion_detalle.material = Clasificacion_1.idClasificacion INNER JOIN
	        Clasificacion AS Clas_obj ON servicio_clasificacion.objetivo = Clas_obj.idClasificacion INNER JOIN 
            Pilas ON servicio_clasificacion.pila=Pilas.idPila
	        WHERE servicio_clasificacion.empresa='$idEmpresa' AND servicio_clasificacion.patio='$idDestino' 
	        	AND CAST(servicio_clasificacion.fecha AS DATE)>'$fechaIni' AND CAST(servicio_clasificacion.fecha AS DATE)<='$fechaFin'
	    ) AS SourceTable PIVOT(SUM([tm]) FOR [material_producido] IN(".$cadena.")) AS PivotTable 
        /*WHERE ".$cadena." IS NOT NULL*/
	    ORDER BY [fecha] DESC";

	    $params = array();
	    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	    $resul_pivot=sqlsrv_query($conn,$sql_pivot,$params,$options);
	    $rows = sqlsrv_num_rows($resul_pivot);
	    if($rows==0){
	        echo 1;
	    }else{
	        $echo.='<div class="row table-responsive1"><div class="col-sm-12">';
	        $echo.='<table class="table table-bordered table-condensed" border="1" align="center"><thead><tr>';
	        $echo.= '<th  style="background-color: #82DBB8;" align ="center"><b>Nº Recibo </b></th>';
	        $echo.= '<th  style="background-color: #82DBB8;" align="center" width="7%" ><b>Fecha </b></th>';
	        $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Semana</b></th>';
	        $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Mes</b></th>';
	        //$echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Empresa</b></th>';
	        $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Actividad</b></th>';
	        $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Equipo</b></th>';
	        $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Pila</b></th>';
	        $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Material</b></th>';
	        $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Proveedor Equipo</b></th>';
	        $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Material Objetivo</b></th>';
	        $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Hrs. Equipo</b></th>';
	        $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Tm Alimentado</b></th>';
	        for($j=0; $j<$count_materiales; $j++){
	            $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>'.utf8_encode($resul_array_clasi[$j]).'</b></th>';
	        }
	        $echo.= '</tr></thead>';
	        $k=0;
	        while($rows1=sqlsrv_fetch_array($resul_pivot)){
	            $id_clasif = $rows1['id_clasif'];
	            $txt_id_clasif = "'".$id_clasif."'";
	            $recibo = $rows1['num_recibo'];
	            $txt_recibo = "'".$recibo."'";
	            $fecha=date_format($rows1['fecha'],'d-m-Y');
	            $mes = date_format($rows1['fecha'],'m');
	            $semana = date_format($rows1['fecha'],'W');
	            $empresa = utf8_encode($rows1['empresa']);
	            $actividad = utf8_encode($rows1['actividad']);
	            $equipo = utf8_encode($rows1['equipo']);
	            $pila = utf8_encode($rows1['pila']);
	            $proveedor = utf8_encode($rows1['proveedor']);
	            $material_alimen = utf8_encode($rows1['material_alimen']);
	            $material_objetivo = utf8_encode($rows1['material_objetivo']);
	            $horas_equipo = number_format($rows1['horas_equipo'],1);
	            $tm_alimen = number_format($rows1['tm_aliment'],2);

	            $echo.= '<tr>';
	            $echo.= '<td align ="center">'.$recibo.'</td>';
	            $echo.= '<td align ="center">'.$fecha.'</td>';
	            $echo.= '<td align ="center">'.$semana.'</td>';
	            $echo.= '<td align ="center">'.$mes.'</td>';
	            //$echo.= '<td align ="center">'.$empresa.'</td>';
	            $echo.= '<td align ="center">'.$actividad.'</td>';
	            $echo.= '<td align ="center">'.$equipo.'</td>';
	            $echo.= '<td align ="center">'.$pila.'</td>';
	            $echo.= '<td align ="center">'.$material_alimen.'</td>';
	            $echo.= '<td align ="center">'.$proveedor.'</td>';
	            $echo.= '<td align ="center">'.$material_objetivo.'</td>';
	           
	            $echo.= '<td align ="center">'.$horas_equipo.'</td>';
	            $echo.= '<td align ="center">'.$tm_alimen.'</td>';
	            for($j=0; $j<$count_materiales; $j++){
	                if($rows1[$resul_array_clasi[$j]] == ''){
	                    $tm_array_clasi = number_format(0,2);
	                    $css="color: red;";
	                }else{
	                    $tm_array_clasi = number_format($rows1[$resul_array_clasi[$j]],2);
	                    $css="";
	                }
	                $echo.= '<td align="center" style="'.$css.'">'.$tm_array_clasi.'</td>';
	            }
	            $echo.= '</tr>';
	            $k++;
	        }
	        $echo.='</table></div></div>';
	    }    
	}
	if($proceso == 'Coquización'){
		$sql = "SELECT Empresa,idEmpresa,CAST(FechaAlimentacion as date) AS FechaAlimentacion,
				COUNT(idAlimentacion_hornos) AS Hornos,ClasificacionAlimentacion,idClasificacionAlimentacion,
				ROUND(AVG(Capacidad),2) AS Capacidad_AVG,
				SUM(Capacidad) AS SUMA_Capacidad,
				ROUND(AVG(Indice_carga),2) AS Indice_carga_AVG,
				SUM(Indice_carga) AS SUMA_Indice_carga,
				CAST(FechaProduccion AS date) AS FechaProduccion,
				COUNT(idProduccion_hornos) AS HornosProduccion,
				ClasificacionProduccion,
				idClasificacionProduccion,
				ROUND(AVG(CapacidadDeshorne),2) AS CapacidadDeshorne_AVG,
				SUM(CapacidadDeshorne) AS SUMA_CapacidadDeshorne,
				ROUND(AVG(Indice_deshorne),2) AS Indice_deshorne_AVG,
				SUM(Indice_deshorne) AS SUMA_Indice_deshorne
			FROM vCoquizacion
			WHERE CAST(FechaAlimentacion AS date)> '$fechaIni' AND CAST(FechaAlimentacion AS date)<='$fechaFin' AND idDestinoAsociado='$idDestino'
				AND idEmpresa='$idEmpresa' AND (idClasificacionAlimentacion='$idClasificacion' OR idClasificacionProduccion='$idClasificacion')
			GROUP BY Empresa,idEmpresa,CAST(FechaAlimentacion as date),ClasificacionAlimentacion,idClasificacionAlimentacion,
				CAST(FechaProduccion AS date),ClasificacionProduccion,idClasificacionProduccion
			ORDER BY Empresa,CAST(FechaAlimentacion AS date) DESC,ClasificacionAlimentacion,ClasificacionProduccion";
		$resul=sqlsrv_query($conn,$sql,$params,$options);
	    $rows = sqlsrv_num_rows($resul);
	    if($rows>0){
	    	$echo.='<div class="row table-responsive1"><div class="col-sm-12">';
	        $echo.='<table class="table table-bordered" border="1" align="center"><thead><tr>';
	        $echo.= '<th rowspan="2" style="vertical-align: middle;" align="center"><b>Empresa</b></th>';
	        $echo.= '<th colspan="5" align="center" style="background-color: #778EC2;"><b>Alimentación</b></th>';
	        $echo.= '<th colspan="5" align="center" style="background-color: #C2777D;"><b>Producción</b></th>';
	        $echo.= '</tr><tr>';
	        $echo.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Fecha</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Hornos</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Clasificación</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Capacidad/Cargue (TM)</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Toneladas</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Fecha</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Hornos</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Clasificación</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Capacidad/Deshorne (TM)</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Toneladas</b></th>';
	        $echo.='</tr></thead>';
	    	while($aa = sqlsrv_fetch_array($resul)){
	    		$echo.='<tr>';
                $echo.='<td>'.utf8_encode($aa['Empresa']).'</td>';
                $echo.='<td>'.date_format($aa['FechaAlimentacion'],'Y-m-d').'</td>';
                $echo.='<td>'.$aa['Hornos'].'</td>';
                $echo.='<td>'.utf8_encode($aa['ClasificacionAlimentacion']).'</td>';
                $echo.='<td>'.number_format($aa['Capacidad_AVG']).' / '.number_format($aa['Indice_carga_AVG']).'</td>';
                $echo.='<td>'.number_format($aa['SUMA_Indice_carga'],2).'</td>';
                if($aa['ClasificacionProduccion'] == null || $aa['ClasificacionProduccion'] == ''){
                    $echo.='<td colspan="5" align="center"><b style="color: red">PENDIENTE</b></td>';   
                }else{
                    $echo.='<td>'.date_format($aa['FechaProduccion'],'Y-m-d').'</td>';
                    $echo.='<td>'.$aa['HornosProduccion'].'</td>';
                    $echo.='<td>'.utf8_encode($aa['ClasificacionProduccion']).'</td>';
                    $echo.='<td>'.number_format($aa['CapacidadDeshorne_AVG']).' / '.number_format($aa['Indice_deshorne_AVG']).'</td>';
                    $echo.='<td>'.number_format($aa['SUMA_Indice_deshorne'],2).'</td>';
                }
	    		$echo.='</tr>';
	    	}
	    	$echo.='</table></div></div>';
	    }
	}
	echo $echo;
}elseif($_POST['band']==26){ //BUSCA LOS INVENTARIOS REGISTRADOS POR CENTRO DE TRABAJO
    $lugar_trabajo = $_POST['lugar_trabajo'];
    $echo = '';
    $sql_inv = "SELECT * FROM vInventarioSaldo WHERE idDestino='$lugar_trabajo' ORDER BY Empresa,Destino,Clasificacion";
    $resul_inv=sqlsrv_query($conn,$sql_inv,$params,$options);
    $rows = sqlsrv_num_rows($resul_inv);
    if($rows>0){
        $echo.='<div class="row"><div class="col-sm-12">';
        $echo.='<table class="table table-bordered">';
        $echo.='<thead><tr>';
        $echo.='<th>Empresa</th>';
        $echo.='<th>Clasificación</th>';
        $echo.='<th>Fecha</th>';
        $echo.='<th>Saldo</th>';
        $echo.='<th>Usuario</th>';
        $echo.='<th>FechaRegistro</th>';
        $echo.='<th>Acciones</th>';
        $echo.='</tr></thead><tbody>';
        while($aa_inv = sqlsrv_fetch_array($resul_inv)){
            $echo.='<tr>';
            $echo.='<td>'.utf8_encode($aa_inv['Empresa']).'</td>';
            $echo.='<td>'.utf8_encode($aa_inv['Clasificacion']).'</td>';
            $echo.='<td>'.date_format($aa_inv['FechaSaldo'],'Y-m-d').'</td>';
            $echo.='<td>'.number_format($aa_inv['Saldo'],2).'</td>';
            $echo.='<td>'.utf8_encode($aa_inv['Usuario']).'</td>';
            $echo.='<td>'.date_format($aa_inv['FechaRegistro'],'Y-m-d').'</td>';
            $string_function = "'".$aa_inv['idEmpresa']."','".$lugar_trabajo."','".$aa_inv['idClasificacion']."','".date_format($aa_inv['FechaSaldo'],'Y-m-d')."'";
            $echo.='<td><center><button class="btn btn-danger" onclick="delete_inventory_balance('.$string_function.')"><span class="glyphicon glyphicon-trash"></span></button></center></td>';
            $echo.='</tr>';
        }
        $echo.='</tbody></table></div></div>';
    }else{
        $echo.='<div class="row"><div class="col-sm-12"><center>No hay registros aún.</center></div></div>';
    }
    echo $echo;
}elseif($_POST['band']==27){ //ELIMINA LOS CORTES DE INVENTARIO
    $idEmpresa = $_POST['idEmpresa'];
    $idDestino = $_POST['idDestino'];
    $idClasificacion = $_POST['idClasificacion'];
    $Fecha = $_POST['Fecha'];

    $sql_delete = "DELETE FROM InventarioSaldo WHERE idEmpresa='$idEmpresa' AND idDestino='$idDestino' AND idClasificacion='$idClasificacion' AND FechaSaldo='$Fecha'";
    $resul=sqlsrv_query($conn,$sql_delete);
    if($resul){
        echo 1;
    }
}elseif($_POST['band']==28){ //ELIMINA UN REGISTRO DE PREPARACION DE MEZCLA
    $idPreparacion = $_POST['idPreparacion'];
    $sql_delete_preparacion_detalle = "DELETE FROM preparacion_recetas_detalle WHERE idPreparacion='$idPreparacion'";
    $resul=sqlsrv_query($conn,$sql_delete_preparacion_detalle);
    if($resul){
        $sql_delete_preparacion = "DELETE FROM preparacion_recetas WHERE idPreparacion='$idPreparacion'";
        $resul=sqlsrv_query($conn,$sql_delete_preparacion);
        if($resul){
            echo 1;
        }
    }
}elseif($_POST['band']==29){ //ELIMINA UN REGISTRO DE COQUIZACION (ALIMENTACION Y PRODUCCION POR DIA)
    $idEmpresa=$_POST['idEmpresa'];
    $idPlanta = $_POST['idPlanta'];
    $idBateria = $_POST['idBateria'];
    $idClasificacionAlimentacion = $_POST['idClasificacionAlimentacion'];
    $FechaAlimentacion = $_POST['FechaAlimentacion'];
    $var_position=0;
    $sql = "SELECT * FROM vCoquizacion WHERE idEmpresa='$idEmpresa' AND idPlanta='$idPlanta' AND idBateria='$idBateria' AND idClasificacionAlimentacion='$idClasificacionAlimentacion' 
        AND CAST(FechaAlimentacion AS date)='$FechaAlimentacion'";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);
    if($rows>0){
        while($aa = sqlsrv_fetch_array($resul)){
            $array_idAlimentacion[$var_position] = $aa['idAlimentacion_hornos'];
            $var_position++;
        }
        $array_idAlimentacion = implode("','", $array_idAlimentacion);
        $sql_delete_produccion = "DELETE FROM Produccion_hornos_recetas WHERE idAlimentacion_hornos IN ('$array_idAlimentacion')";
        $res = sqlsrv_query($conn,$sql_delete_produccion);
        
        $sql_delete_alimentacion = "DELETE FROM Alimentacion_hornos_recetas WHERE idAlimentacion_hornos IN ('$array_idAlimentacion')";
        $res = sqlsrv_query($conn,$sql_delete_alimentacion);
        if($res){
            echo 1;
        }
    }
}elseif($_POST['band']==30){ //GENERA LA DESCARGA DE EXCEL PARA EL DETALLE DE LOS PROCESOS DE INVENTARIOS
    $idEmpresa = $_POST['idEmpresa'];
    $idDestino = $_POST['idDestino'];
    $idClasificacion = $_POST['idClasificacion'];
    $fechaIni = $_POST['fechaIni'];
    $fechaFin = $_POST['fechaFin'];
    $proceso = explode("-",$_POST['proceso']);
    $transaccion = $_POST['transaccion'];
    $txt_transaccion = "";
    if($transaccion<>'N/A'){
        $txt_transaccion = "AND A.Descripcion='$transaccion'";
    }
    $echo='';
    if($proceso[1] == 'Entradas'){
       $sql = "SELECT tz_MovimientoTransporte.FechaRegistro, tz_MovimientoTransporte.Clasificacion, tz_MovimientoTransporte.DespachadoDesde, tz_MovimientoTransporte.Origen, tz_MovimientoTransporte.TiqueteEmpresa, 
        tz_MovimientoTransporte.Empresa, Productos.Descripcion AS Material, tz_MovimientoTransporte.Remision, tz_MovimientoTransporte.Proveedor, tz_MovimientoTransporte.Placa_a, tz_MovimientoTransporte.Placa_b, 
        tz_MovimientoTransporte.Conductor, tz_MovimientoTransporte.Telefonos, tz_MovimientoTransporte.ToneladasBrutas, tz_MovimientoTransporte.ToneladasVacio, tz_MovimientoTransporte.Toneladas, 
        tz_MovimientoTransporte.RecepcionadoEn, tz_MovimientoTransporte.SalidaDestino, tz_MovimientoTransporte.TiqueteRecepcion, tz_MovimientoTransporte.ToneladasRecepcionBrutas, tz_MovimientoTransporte.ToneladasRecepcionvacio, 
        tz_MovimientoTransporte.ToneladasRecepcion, tz_MovimientoTransporte.Transportador, tz_MovimientoTransporte.PILA, tz_MovimientoTransporte.Lote, tz_MovimientoTransporte.[Patio en Puerto] , 'N/A' AS zona_origen , 
        tz_MovimientoTransporte.Zona AS zona_recepcionado, A.Descripcion AS Transaccion
        FROM dbo.tz_MovimientoTransporte
        INNER JOIN Movimiento ON dbo.tz_MovimientoTransporte.NumeroTransaccion=Movimiento.NumeroTransaccion 
        INNER JOIN Productos on Movimiento.idProducto=Productos.idProducto 
        INNER JOIN Origenes ON Movimiento.idOrigen=Origenes.idOrigen 
        LEFT JOIN MovimientoTransaccion ON Movimiento.NumeroTransaccion=MovimientoTransaccion.NumeroTransaccion
        LEFT JOIN dbo.TransaccionesMovimiento() AS A ON MovimientoTransaccion.iTransaccion=A.iTransaccion
        WHERE Movimiento.idEmpresa='$idEmpresa' AND Movimiento.idDestino='$idDestino' AND Movimiento.idDestinoAcopio IS NULL $txt_transaccion
            AND idClasificacion='$idClasificacion' AND CAST(Movimiento.FechaRegistro AS date)>'$fechaIni' AND CAST(Movimiento.FechaRegistro AS date)<='$fechaFin'
        UNION 
        SELECT tz_MovimientoTransporte.SalidaDestino AS FechaRegistro, tz_MovimientoTransporte.Clasificacion, tz_MovimientoTransporte.DespachadoDesde, tz_MovimientoTransporte.Origen, 
            tz_MovimientoTransporte.TiqueteEmpresa, 
        tz_MovimientoTransporte.Empresa, Productos.Descripcion AS Material, tz_MovimientoTransporte.Remision, tz_MovimientoTransporte.Proveedor, tz_MovimientoTransporte.Placa_a, tz_MovimientoTransporte.Placa_b, 
        tz_MovimientoTransporte.Conductor, tz_MovimientoTransporte.Telefonos, tz_MovimientoTransporte.ToneladasBrutas, tz_MovimientoTransporte.ToneladasVacio, tz_MovimientoTransporte.Toneladas, 
        tz_MovimientoTransporte.RecepcionadoEn, tz_MovimientoTransporte.SalidaDestino, tz_MovimientoTransporte.TiqueteRecepcion, tz_MovimientoTransporte.ToneladasRecepcionBrutas, tz_MovimientoTransporte.ToneladasRecepcionvacio, 
        tz_MovimientoTransporte.ToneladasRecepcion, tz_MovimientoTransporte.Transportador, tz_MovimientoTransporte.PILA, tz_MovimientoTransporte.Lote, tz_MovimientoTransporte.[Patio en Puerto] , 'N/A' AS zona_origen , 
        tz_MovimientoTransporte.Zona AS zona_recepcionado, A.Descripcion AS Transaccion
        FROM dbo.tz_MovimientoTransporte
        INNER JOIN Movimiento ON dbo.tz_MovimientoTransporte.NumeroTransaccion=Movimiento.NumeroTransaccion 
        INNER JOIN Productos on Movimiento.idProducto=Productos.idProducto 
        INNER JOIN Origenes ON Movimiento.idOrigen=Origenes.idOrigen 
        LEFT JOIN MovimientoTransaccion ON Movimiento.NumeroTransaccion=MovimientoTransaccion.NumeroTransaccion
        LEFT JOIN dbo.TransaccionesMovimiento() AS A ON MovimientoTransaccion.iTransaccion=A.iTransaccion
        WHERE Movimiento.idEmpresa='$idEmpresa' AND Movimiento.idDestino='$idDestino' AND tz_MovimientoTransporte.ToneladasRecepcion<>0 $txt_transaccion
            AND idClasificacion='$idClasificacion' AND CAST(tz_MovimientoTransporte.SalidaDestino AS date)>'$fechaIni' AND CAST(tz_MovimientoTransporte.SalidaDestino AS date)<='$fechaFin'
        ORDER BY tz_MovimientoTransporte.FechaRegistro DESC";
    }
    if($proceso[1] == 'Salidas'){
        $sql = "SELECT tz_MovimientoTransporte.FechaRegistro, tz_MovimientoTransporte.Clasificacion, tz_MovimientoTransporte.DespachadoDesde, tz_MovimientoTransporte.Origen, tz_MovimientoTransporte.TiqueteEmpresa, 
        tz_MovimientoTransporte.Empresa, Productos.Descripcion AS Material, tz_MovimientoTransporte.Remision, tz_MovimientoTransporte.Proveedor, tz_MovimientoTransporte.Placa_a, tz_MovimientoTransporte.Placa_b, 
        tz_MovimientoTransporte.Conductor, tz_MovimientoTransporte.Telefonos, tz_MovimientoTransporte.ToneladasBrutas, tz_MovimientoTransporte.ToneladasVacio, tz_MovimientoTransporte.Toneladas, 
        tz_MovimientoTransporte.RecepcionadoEn, tz_MovimientoTransporte.SalidaDestino, tz_MovimientoTransporte.TiqueteRecepcion, tz_MovimientoTransporte.ToneladasRecepcionBrutas, tz_MovimientoTransporte.ToneladasRecepcionvacio, 
        tz_MovimientoTransporte.ToneladasRecepcion, tz_MovimientoTransporte.Transportador, tz_MovimientoTransporte.PILA, tz_MovimientoTransporte.Lote, tz_MovimientoTransporte.[Patio en Puerto] , 
        tz_MovimientoTransporte.Zona AS zona_recepcionado , isnull(DestinoZonas.Zona,'N/A') AS zona_origen, A.Descripcion AS Transaccion
        FROM dbo.tz_MovimientoTransporte
        INNER JOIN Movimiento ON dbo.tz_MovimientoTransporte.NumeroTransaccion=Movimiento.NumeroTransaccion 
        INNER JOIN Productos ON Movimiento.idProducto=Productos.idProducto 
        INNER JOIN Origenes ON Movimiento.idOrigen=Origenes.idOrigen 
        LEFT JOIN MovimientoPuertoZona ON tz_MovimientoTransporte.NumeroTransaccion=MovimientoPuertoZona.NumeroTransaccion 
        LEFT JOIN DestinoZonas ON MovimientoPuertoZona.idZona=DestinoZonas.idZona 
        LEFT JOIN MovimientoTransaccion ON Movimiento.NumeroTransaccion=MovimientoTransaccion.NumeroTransaccion
        LEFT JOIN dbo.TransaccionesMovimiento() AS A ON MovimientoTransaccion.iTransaccion=A.iTransaccion
        WHERE Movimiento.idEmpresa='$idEmpresa' AND Movimiento.idDestinoAcopio='$idDestino' AND idClasificacion='$idClasificacion' $txt_transaccion
            AND CAST(Movimiento.FechaRegistro AS date)>'$fechaIni' AND CAST(Movimiento.FechaRegistro AS date)<='$fechaFin'
        ORDER BY tz_MovimientoTransporte.FechaRegistro DESC";
    }
    if($proceso[1] == 'Entradas' || $proceso[1] == 'Salidas'){
        $resul=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
        $rows = sqlsrv_num_rows($resul);
        $echo.= '<table style="border-collapse: collapse; border-spacing: 1px; border: 1px solid #000;" border ="1"><thead><tr style="background-color: #8BCAF9; font-weight: bold;">';
        $echo.='<th>Fecha Registro</th>';
        $echo.='<th>Clasificacion</th>';
        $echo.='<th>Despachado Desde</th>';
        $echo.='<th>Origen</th>';
        $echo.='<th>Zona Origen</th>';
        $echo.='<th>Tiquete Empresa</th>';
        $echo.='<th>Empresa</th>';
        $echo.='<th>Remision</th>';
        $echo.='<th>Proveedor</th>';
        $echo.='<th>Placa</th>';
        $echo.='<th>Remolque</th>';
        $echo.='<th>Conductor</th>';
        $echo.='<th>Telefono</th>';
        $echo.='<th>TM Brutas</th>';
        $echo.='<th>TM Vacio</th>';
        $echo.='<th>TM Netas</th>';
        $echo.='<th>Recepcionado En</th>';
        $echo.='<th>Zona Recepcionado En</th>';
        $echo.='<th>Fecha Llegada</th>';
        $echo.='<th>Tiquete Puerto</th>';
        $echo.='<th>Bruto Recepcion</th>';
        $echo.='<th>Vacio Recepcion</th>';
        $echo.='<th>Neto Recepcion</th>';
        $echo.='<th>Transportador</th>';
        $echo.='<th>Pila</th>';
        $echo.='<th>Lote</th>';
        $echo.='<th>Patio en puerto</th>';
        $echo.='<th>Transacción</th>';
        $echo.='</tr></thead><tbody>';
        
        while($corre = sqlsrv_fetch_array($resul)){
            $echo.='<tr>';
            /////////////////////////////////////////
            $echo.='<td>'.date_format($corre['FechaRegistro'],'Y-m-d').'</td>';
            $echo.='<td>'.utf8_encode($corre['Clasificacion']).'</td>';
            $echo.='<td>'.utf8_encode($corre['DespachadoDesde']).'</td>';
            $echo.='<td>'.utf8_encode($corre['Origen']).'</td>';
            $echo.='<td>'.utf8_encode($corre['zona_origen']).'</td>';
            $echo.='<td>'.utf8_encode($corre['TiqueteEmpresa']).'</td>';
            $echo.='<td>'.utf8_encode($corre['Empresa']).'</td>';
            $echo.='<td>'.utf8_encode($corre['Remision']).'</td>';
            $echo.='<td>'.utf8_encode($corre['Proveedor']).'</td>';
            $echo.='<td>'.utf8_encode($corre['Placa_a']).'</td>';
            $echo.='<td>'.utf8_encode($corre['Placa_b']).'</td>';
            $echo.='<td>'.utf8_encode($corre['Conductor']).'</td>';
            $echo.='<td>'.utf8_encode($corre['Telefonos']).'</td>';
            $echo.='<td>'.number_format($corre['ToneladasBrutas'],'2').'</td>';
            $echo.='<td>'.number_format($corre['ToneladasVacio'],'2').'</td>';
            $echo.='<td>'.number_format($corre['Toneladas'],'2').'</td>';
            $echo.='<td>'.utf8_encode($corre['RecepcionadoEn']).'</td>';
            $echo.='<td>'.utf8_encode($corre['zona_recepcionado']).'</td>';
            $echo.='<td>'.date_format($corre['SalidaDestino'],'Y-m-d').'</td>';
            $echo.='<td>'.utf8_encode($corre['TiqueteRecepcion']).'</td>';
            $echo.='<td>'.number_format($corre['ToneladasRecepcionBrutas'],'2').'</td>';
            $echo.='<td>'.number_format($corre['ToneladasRecepcionvacio'],'2').'</td>';
            $echo.='<td>'.number_format($corre['ToneladasRecepcion'],'2').'</td>';
            $echo.='<td>'.utf8_encode($corre['Transportador']).'</td>';
            $echo.='<td>'.utf8_encode($corre['PILA']).'</td>';
            $echo.='<td>'.utf8_encode($corre['Lote']).'</td>';
            $echo.='<td>'.utf8_encode($corre['Patio en Puerto']).'</td>';
            $echo.='<td>'.utf8_encode($corre['Transaccion']).'</td>';
            $echo.='</tr>';
        }
        $echo.='</tbody></table>';
    }
    if($proceso[1] == 'Preparación mezcla'){
        $resul_array= array();
        $resul_array_clasif = array();
        $resul_array_produc = array();
        $i = 0;
        $var = 0;
        /******************** BUSCA LOS MATERIALES ALIMENTADOS ********************/
        $sql_clasif = "SELECT MaterialAlimentado FROM vPreparacion_recetas
            WHERE idEmpresa='$idEmpresa' AND idDestino='$idDestino' AND (idClasificacionProducido='$idClasificacion' 
                OR idClasificacionAlimentado='$idClasificacion') AND CAST(FechaPreparacion AS date)>'$fechaIni' AND CAST(FechaPreparacion AS date)<='$fechaFin'
            GROUP BY MaterialAlimentado ORDER BY MaterialAlimentado";
        $resul=sqlsrv_query($conn,$sql_clasif,$params,$options);
        $rows = sqlsrv_num_rows($resul);
        if($rows>0){
            while ($rows=sqlsrv_fetch_array($resul)){
                $resul_array[$i] = '['.utf8_encode($rows['MaterialAlimentado']).']';
                $resul_array_clasif[$i] = utf8_encode($rows['MaterialAlimentado']);
                $i++;
            }
            /***************** BUSCA LOS MATERIALES PRODUCIDOS *****************/
            $sql_producido = "SELECT MaterialProducido FROM vPreparacion_recetas
            WHERE idEmpresa='$idEmpresa' AND idDestino='$idDestino' AND (idClasificacionProducido='$idClasificacion' 
                OR idClasificacionAlimentado='$idClasificacion') AND CAST(FechaPreparacion AS date)>'$fechaIni' AND CAST(FechaPreparacion AS date)<='$fechaFin'
            GROUP BY MaterialProducido ORDER BY MaterialProducido";
            $resul_produc=sqlsrv_query($conn,$sql_producido,$params,$options);
            $rows_produc = sqlsrv_num_rows($resul_produc);
            if($rows_produc>0){
                while ($aa_produc=sqlsrv_fetch_array($resul_produc)){
                    $resul_array_produc[$var] = utf8_encode($aa_produc['MaterialProducido']);
                    $var++;
                }
            }
        }else{
            $resul_array[$i]='[0]';
            $resul_array_clasif[$i] = '';
        }
        $cadena= implode(',',$resul_array);
        $sql_pivot = "SELECT * 
        FROM (SELECT idPreparacion,Empresa,FechaPreparacion,MaterialProducido,ToneladasProducido,MaterialAlimentado,ToneladasAlimentado 
                FROM vPreparacion_recetas 
            WHERE idEmpresa='$idEmpresa' AND idDestino='$idDestino' AND CAST(FechaPreparacion AS date)>'$fechaIni' AND CAST(FechaPreparacion AS date)<='$fechaFin'
                AND (idClasificacionProducido='$idClasificacion' OR idClasificacionAlimentado='$idClasificacion')
            GROUP BY idPreparacion,Empresa,FechaPreparacion,MaterialProducido,ToneladasProducido,MaterialAlimentado,ToneladasAlimentado
            ) AS SourceTable PIVOT(SUM([ToneladasAlimentado]) FOR [MaterialAlimentado] IN(".$cadena.")) AS PivotTable
        ORDER BY FechaPreparacion DESC,Empresa,[MaterialProducido]";
        $resul_pivot=sqlsrv_query($conn,$sql_pivot,$params,$options);
        $rows = sqlsrv_num_rows($resul_pivot);
        if($rows>0){
            $echo.='<table style="border-collapse: collapse; border-spacing: 1px; border: 1px solid #000;" border ="1"><thead><tr style="background-color: #8BCAF9; font-weight: bold;"><thead><tr>';
            $echo.= '<th class="header" scope="col" rowspan="2" style="vertical-align: middle; background-color: #82DBB8;" align="center"><b>Fecha preparación</b></th>';
            $echo.= '<th class="header" scope="col" rowspan="2" style="vertical-align: middle; background-color: #82DBB8;" align="center"><b>Empresa</b></th>';
            $echo.= '<th class="header" scope="col" colspan="'.count($resul_array_produc).'" align="center" style="background-color: #82DBB8;"><b>Material Producido</b></th>';
            $echo.= '<th class="header" scope="col" colspan="'.count($resul_array_clasif).'" align="center" style="background-color: #82DBB8;"><b>Material Alimentado</b></th>';
            $echo.= '</tr><tr>';
            for ($i=0; $i < count($resul_array_produc); $i++){
                $echo.= '<th class="header" scope="col" style="vertical-align: middle; background-color: #82DBB8;" align="center" align="center"><b>'.$resul_array_produc[$i].'</b></th>';
            }
            for ($i=0; $i < count($resul_array_clasif); $i++){
                $echo.= '<th class="header" scope="col" align="center" style="background-color: #82DBB8;"><b>'.$resul_array_clasif[$i].'</b></th>';
            }
            $echo.='</tr></thead><tbody>';
            while($aa = sqlsrv_fetch_array($resul_pivot)){
                $echo.='<tr>';
                $echo.='<td>'.date_format($aa['FechaPreparacion'],'Y-m-d').'</td>';
                $echo.='<td>'.utf8_encode($aa['Empresa']).'</td>';
                $search_position = array_search(utf8_encode($aa['MaterialProducido']),$resul_array_produc);
                for($j=0; $j<count($resul_array_produc); $j++){
                    if($resul_array_produc[$j]==utf8_encode($aa['MaterialProducido'])){
                        $tm_producido = $aa['ToneladasProducido'];
                        $css = "";
                    }else{
                        $tm_producido=0;
                        $css = "color: red";
                    }
                    $echo.='<td style="'.$css.'">'.number_format($tm_producido,2).'</td>';
                }
                for($j=0; $j<count($resul_array_clasif); $j++){
                    if($aa[$resul_array_clasif[$j]] == ''){
                        $tm_array_clasi = number_format(0,2);
                        $css = "color: red";
                    }else{
                        $tm_array_clasi = number_format($aa[$resul_array_clasif[$j]],2);
                        $css = "";
                    }
                    $echo.= '<td align="center" style="'.$css.'">'.$tm_array_clasi.'</td>';
                }
                $echo.= '</tr>';
            }
            $echo.='</tbody></table>';
        }else{
            $echo = '';
        }
    }
    if($proceso[1] == 'Clasificación'){
        $resul_array= array();
        $i=0;//consulta para sacar las clasificaicones de material ese rango de fecha
        $sql_clasi="SELECT '['+Clasificacion.Descripcion+']' as material
            FROM servicio_clasificacion INNER JOIN
                servicio_clasificacion_detalle ON servicio_clasificacion.id_clasif=servicio_clasificacion_detalle.id_clasif INNER JOIN
                Clasificacion ON servicio_clasificacion_detalle.material=Clasificacion.idClasificacion INNER JOIN
                Equipos ON servicio_clasificacion.equipo=Equipos.idEquipo
            WHERE servicio_clasificacion.empresa='$idEmpresa' AND servicio_clasificacion.patio='$idDestino' 
                AND (servicio_clasificacion_detalle.material='$idClasificacion' OR servicio_clasificacion.material='$idClasificacion')
                AND CAST(servicio_clasificacion.fecha AS DATE)>'$fechaIni' AND CAST(servicio_clasificacion.fecha AS DATE)<='$fechaFin'
            GROUP by Clasificacion.Descripcion";
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
        $resul_cla=sqlsrv_query($conn,$sql_clasi,$params,$options);
        $rows = sqlsrv_num_rows($resul_cla);
        if($rows==0)
             $resul_array[$i]='[0]';
        else{
            while ($rows=sqlsrv_fetch_array($resul_cla)) {
                $resul_array[$i] =$rows['material'];
                $i++;
            }
        }
        $count_materiales=count($resul_array);
        $cadena= implode(',',$resul_array);

        $resul_array_clasi= array();
      
        $sql_clasi="SELECT Clasificacion.Descripcion as material/*, servicio_clasificacion_detalle.objetivo*/
            FROM servicio_clasificacion INNER JOIN
                servicio_clasificacion_detalle ON servicio_clasificacion.id_clasif=servicio_clasificacion_detalle.id_clasif INNER JOIN
                Clasificacion ON servicio_clasificacion_detalle.material=Clasificacion.idClasificacion INNER JOIN
                Equipos ON servicio_clasificacion.equipo=Equipos.idEquipo
            WHERE servicio_clasificacion.empresa='$idEmpresa' AND servicio_clasificacion.patio='$idDestino' 
                AND (servicio_clasificacion_detalle.material='$idClasificacion' OR servicio_clasificacion.material='$idClasificacion')
                AND CAST(servicio_clasificacion.fecha AS DATE)>'$fechaIni' AND CAST(servicio_clasificacion.fecha AS DATE)<='$fechaFin'
            GROUP by Clasificacion.Descripcion";
        $resul_cla= sqlsrv_query($conn, $sql_clasi);
        $k=0;
        while ($rows=sqlsrv_fetch_array($resul_cla)) {
            $resul_array_clasi[$k] =$rows['material'];
            $k++;
        }

        $sql_pivot="SELECT *
        FROM
        (   SELECT [servicio_clasificacion].[id_clasif], [servicio_clasificacion].[num_recibo], [servicio_clasificacion].[fecha],
            [servicio_clasificacion].[horas_equipo], [servicio_clasificacion].[tm_aliment], [Pilas].[Descripcion] AS pila, [servicio_clasificacion].[semana], 
            [servicio_clasificacion_detalle].[tm], [Proveedores_1].[Alias] as empresa, [Proveedores].[RazonSocial] AS proveedor,
            [Equipos].[Descripcion]+' - '+[Equipos].[Identificacion] as equipo,[Actividades].[Descripcion] AS actividad, 
            [UsuariosDetalle].[Nombre1]+' '+[UsuariosDetalle].[Apellido1] as usuario, [Clasificacion].[Descripcion] AS material_alimen,
            [Clas_obj].[Descripcion] AS material_objetivo, [Clasificacion_1].[Descripcion] AS material_producido
        FROM Clasificacion INNER JOIN
            servicio_clasificacion INNER JOIN
            servicio_clasificacion_detalle ON servicio_clasificacion.id_clasif = servicio_clasificacion_detalle.id_clasif INNER JOIN
            Proveedores AS Proveedores_1 ON servicio_clasificacion.empresa = Proveedores_1.idProveedor INNER JOIN
            Proveedores ON servicio_clasificacion.proveedor = Proveedores.idProveedor INNER JOIN
            Equipos ON servicio_clasificacion.equipo = Equipos.idEquipo INNER JOIN
            Actividades ON servicio_clasificacion.actividad = Actividades.idActividad INNER JOIN
            UsuariosDetalle ON servicio_clasificacion.usuario = UsuariosDetalle.idUsuario ON Clasificacion.idClasificacion = servicio_clasificacion.material INNER JOIN
            Clasificacion AS Clasificacion_1 ON servicio_clasificacion_detalle.material = Clasificacion_1.idClasificacion INNER JOIN
            Clasificacion AS Clas_obj ON servicio_clasificacion.objetivo = Clas_obj.idClasificacion INNER JOIN 
            Pilas ON servicio_clasificacion.pila=Pilas.idPila
            WHERE servicio_clasificacion.empresa='$idEmpresa' AND servicio_clasificacion.patio='$idDestino' 
                AND CAST(servicio_clasificacion.fecha AS DATE)>'$fechaIni' AND CAST(servicio_clasificacion.fecha AS DATE)<='$fechaFin'
        ) AS SourceTable PIVOT(SUM([tm]) FOR [material_producido] IN(".$cadena.")) AS PivotTable 
        /*WHERE ".$cadena." IS NOT NULL*/
        ORDER BY [fecha] DESC";

        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
        $resul_pivot=sqlsrv_query($conn,$sql_pivot,$params,$options);
        $rows = sqlsrv_num_rows($resul_pivot);
        if($rows==0){
            echo 1;
        }else{
            $echo.='<table style="border-collapse: collapse; border-spacing: 1px; border: 1px solid #000;" border ="1"><thead><tr style="background-color: #8BCAF9; font-weight: bold;"><thead><tr>';
            $echo.= '<th  style="background-color: #82DBB8;" align ="center"><b>Nº Recibo </b></th>';
            $echo.= '<th  style="background-color: #82DBB8;" align="center" width="7%" ><b>Fecha </b></th>';
            $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Semana</b></th>';
            $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Mes</b></th>';
            //$echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Empresa</b></th>';
            $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Actividad</b></th>';
            $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Equipo</b></th>';
            $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Pila</b></th>';
            $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Material</b></th>';
            $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Proveedor Equipo</b></th>';
            $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Material Objetivo</b></th>';
            $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Hrs. Equipo</b></th>';
            $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>Tm Alimentado</b></th>';
            for($j=0; $j<$count_materiales; $j++){
                $echo.= '<th  style="background-color: #82DBB8;" align="center"><b>'.utf8_encode($resul_array_clasi[$j]).'</b></th>';
            }
            $echo.= '</tr></thead>';
            $k=0;
            while($rows1=sqlsrv_fetch_array($resul_pivot)){
                $id_clasif = $rows1['id_clasif'];
                $txt_id_clasif = "'".$id_clasif."'";
                $recibo = $rows1['num_recibo'];
                $txt_recibo = "'".$recibo."'";
                $fecha=date_format($rows1['fecha'],'d-m-Y');
                $mes = date_format($rows1['fecha'],'m');
                $semana = date_format($rows1['fecha'],'W');
                $empresa = utf8_encode($rows1['empresa']);
                $actividad = utf8_encode($rows1['actividad']);
                $equipo = utf8_encode($rows1['equipo']);
                $pila = utf8_encode($rows1['pila']);
                $proveedor = utf8_encode($rows1['proveedor']);
                $material_alimen = utf8_encode($rows1['material_alimen']);
                $material_objetivo = utf8_encode($rows1['material_objetivo']);
                $horas_equipo = number_format($rows1['horas_equipo'],1);
                $tm_alimen = number_format($rows1['tm_aliment'],2);

                $echo.= '<tr>';
                $echo.= '<td align ="center">'.$recibo.'</td>';
                $echo.= '<td align ="center">'.$fecha.'</td>';
                $echo.= '<td align ="center">'.$semana.'</td>';
                $echo.= '<td align ="center">'.$mes.'</td>';
                //$echo.= '<td align ="center">'.$empresa.'</td>';
                $echo.= '<td align ="center">'.$actividad.'</td>';
                $echo.= '<td align ="center">'.$equipo.'</td>';
                $echo.= '<td align ="center">'.$pila.'</td>';
                $echo.= '<td align ="center">'.$material_alimen.'</td>';
                $echo.= '<td align ="center">'.$proveedor.'</td>';
                $echo.= '<td align ="center">'.$material_objetivo.'</td>';
               
                $echo.= '<td align ="center">'.$horas_equipo.'</td>';
                $echo.= '<td align ="center">'.$tm_alimen.'</td>';
                for($j=0; $j<$count_materiales; $j++){
                    if($rows1[$resul_array_clasi[$j]] == ''){
                        $tm_array_clasi = number_format(0,2);
                        $css="color: red;";
                    }else{
                        $tm_array_clasi = number_format($rows1[$resul_array_clasi[$j]],2);
                        $css="";
                    }
                    $echo.= '<td align="center" style="'.$css.'">'.$tm_array_clasi.'</td>';
                }
                $echo.= '</tr>';
                $k++;
            }
            $echo.='</table>';
        }    
    }
    if($proceso[1] == 'Coquización'){
        $sql = "SELECT Empresa,idEmpresa,CAST(FechaAlimentacion as date) AS FechaAlimentacion,
                COUNT(idAlimentacion_hornos) AS Hornos,ClasificacionAlimentacion,idClasificacionAlimentacion,
                ROUND(AVG(Capacidad),2) AS Capacidad_AVG,
                SUM(Capacidad) AS SUMA_Capacidad,
                ROUND(AVG(Indice_carga),2) AS Indice_carga_AVG,
                SUM(Indice_carga) AS SUMA_Indice_carga,
                CAST(FechaProduccion AS date) AS FechaProduccion,
                COUNT(idProduccion_hornos) AS HornosProduccion,
                ClasificacionProduccion,
                idClasificacionProduccion,
                ROUND(AVG(CapacidadDeshorne),2) AS CapacidadDeshorne_AVG,
                SUM(CapacidadDeshorne) AS SUMA_CapacidadDeshorne,
                ROUND(AVG(Indice_deshorne),2) AS Indice_deshorne_AVG,
                SUM(Indice_deshorne) AS SUMA_Indice_deshorne
            FROM vCoquizacion
            WHERE CAST(FechaAlimentacion AS date)> '$fechaIni' AND CAST(FechaAlimentacion AS date)<='$fechaFin' AND idDestinoAsociado='$idDestino'
                AND idEmpresa='$idEmpresa' AND (idClasificacionAlimentacion='$idClasificacion' OR idClasificacionProduccion='$idClasificacion')
            GROUP BY Empresa,idEmpresa,CAST(FechaAlimentacion as date),ClasificacionAlimentacion,idClasificacionAlimentacion,
                CAST(FechaProduccion AS date),ClasificacionProduccion,idClasificacionProduccion
            ORDER BY Empresa,CAST(FechaAlimentacion AS date) DESC,ClasificacionAlimentacion,ClasificacionProduccion";
        $resul=sqlsrv_query($conn,$sql,$params,$options);
        $rows = sqlsrv_num_rows($resul);
        if($rows>0){
            $echo.='<table style="border-collapse: collapse; border-spacing: 1px; border: 1px solid #000;" border ="1"><thead><tr style="background-color: #8BCAF9; font-weight: bold;"><thead><tr>';
            $echo.= '<th rowspan="2" style="vertical-align: middle;" align="center"><b>Empresa</b></th>';
            $echo.= '<th colspan="6" align="center" style="background-color: #778EC2;"><b>Alimentación</b></th>';
            $echo.= '<th colspan="6" align="center" style="background-color: #C2777D;"><b>Producción</b></th>';
            $echo.= '</tr><tr>';
            $echo.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Fecha</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Hornos</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Clasificación</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Capacidad/Cargue (TM)</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Toneladas</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Fecha</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Hornos</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Capacidad/Descargue</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Clasificación</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Capacidad/Deshorne (TM)</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Toneladas</b></th>';
            $echo.='</tr></thead>';
            while($aa = sqlsrv_fetch_array($resul)){
                $echo.='<tr>';
                $echo.='<td>'.utf8_encode($aa['Empresa']).'</td>';
                $echo.='<td>'.date_format($aa['FechaAlimentacion'],'Y-m-d').'</td>';
                $echo.='<td>'.$aa['Hornos'].'</td>';
                $echo.='<td>'.utf8_encode($aa['ClasificacionAlimentacion']).'</td>';
                $echo.='<td>'.number_format($aa['Capacidad_AVG']).' / '.number_format($aa['Indice_carga_AVG']).'</td>';
                $echo.='<td>'.number_format($aa['SUMA_Indice_carga'],2).'</td>';
                if($aa['ClasificacionProduccion'] == null || $aa['ClasificacionProduccion'] == ''){
                    $echo.='<td colspan="5" align="center"><b style="color: red">PENDIENTE</b></td>';   
                }else{
                    $echo.='<td>'.date_format($aa['FechaProduccion'],'Y-m-d').'</td>';
                    $echo.='<td>'.$aa['HornosProduccion'].'</td>';
                    $echo.='<td>'.utf8_encode($aa['ClasificacionProduccion']).'</td>';
                    $echo.='<td>'.number_format($aa['CapacidadDeshorne_AVG']).' / '.number_format($aa['Indice_deshorne_AVG']).'</td>';
                    $echo.='<td>'.number_format($aa['SUMA_Indice_deshorne'],2).'</td>';
                }
                $echo.='</tr>';
            }
            $echo.='</table>';
        }
    }
    echo $echo;
}elseif($_POST['band']==31){
    ini_set('max_execution_time',0);
    $FechaInicioSaldo = $_POST['FechaInicioSaldo'];
    $FechaFinSaldo = $_POST['FechaFinSaldo'];
    $echo = '';
    $sql_ingresos = "SELECT UnidadDeNegocio.Descripcion AS UnidadDeNegocio,Departamentos.Descripcion as Departamento,tz_MovimientoTransporte.Empresa,tz_MovimientoTransporte.DespachadoDesde,tz_MovimientoTransporte.RecepcionadoEn,Clase.Descripcion as Clase
    ,tz_MovimientoTransporte.Proveedor,Productos.Descripcion AS Producto,tz_MovimientoTransporte.Clasificacion,ISNULL(SUM(tz_MovimientoTransporte.Toneladas),0) AS Toneladas,ISNULL(SUM(tz_MovimientoTransporte.ToneladasRecepcion),0) AS ToneladasLlegadas
    ,tz_MovimientoTransporte.TiqueteEmpresa,tz_MovimientoTransporte.FechaRegistro
    ,'COMPRAS' AS Operacion
FROM tz_MovimientoTransporte 
    INNER JOIN Movimiento ON tz_MovimientoTransporte.NumeroTransaccion= Movimiento.NumeroTransaccion
    INNER JOIN Clasificacion ON Movimiento.idClasificacion= Clasificacion.idClasificacion
    INNER JOIN UnidadDeNegocio ON Clasificacion.UnidadDeNegocio= UnidadDeNegocio.idUnidadNegocio
    INNER JOIN Productos ON Clasificacion.idProducto=Productos.idProducto
    INNER JOIN Origenes ON Movimiento.idOrigen= Origenes.idOrigen
    INNER JOIN Destino AS Destino ON tz_MovimientoTransporte.idDestino= Destino.idDestino
    INNER JOIN Clase ON Destino.idClase= Clase.idClase
    INNER JOIN Departamentos ON Destino.idDepartamento= Departamentos.idDepartamento
WHERE CAST(tz_MovimientoTransporte.FechaRegistro AS DATE)>'$FechaInicioSaldo' AND CAST(tz_MovimientoTransporte.FechaRegistro AS DATE)<='$FechaFinSaldo' 
    AND tz_MovimientoTransporte.idEmpresaREAL IN('24B7153E-AB4C-4DB7-81BD-67BC87AF014C' ,'1BEA8058-7000-479D-B562-AD71D60C68B7') AND TipoMovimiento='Recepción' AND tz_MovimientoTransporte.idEmpresaREAL<>Origenes.idProveedor
GROUP BY UnidadDeNegocio.Descripcion,Departamentos.Descripcion,tz_MovimientoTransporte.Empresa,tz_MovimientoTransporte.DespachadoDesde,tz_MovimientoTransporte.RecepcionadoEn,Clase.Descripcion,tz_MovimientoTransporte.Proveedor,
    Productos.Descripcion,tz_MovimientoTransporte.Clasificacion
    ,tz_MovimientoTransporte.TiqueteEmpresa,tz_MovimientoTransporte.FechaRegistro
ORDER BY UnidadDeNegocio.Descripcion,Departamentos.Descripcion,tz_MovimientoTransporte.Empresa,tz_MovimientoTransporte.DespachadoDesde,tz_MovimientoTransporte.RecepcionadoEn,Clase.Descripcion,tz_MovimientoTransporte.Proveedor,
    Productos.Descripcion,tz_MovimientoTransporte.Clasificacion,tz_MovimientoTransporte.FechaRegistro";
    $resul_ingresos=sqlsrv_query($conn,utf8_decode($sql_ingresos),$params,$options);
    $rows_ingresos = sqlsrv_num_rows($resul_ingresos);
    if($rows_ingresos>0){
        $echo.='<table style="border-collapse: collapse; border-spacing: 1px; border: 1px solid #000;" border ="1">';
        $echo.='<thead><tr style="font-weight: bold;"><tr>';
        $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>UNIDAD DE NEGOCIO</b></th>';
        $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>DEPARTAMENTO</b></th>';
        $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>EMPRESA</b></th>';
        $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>DESPACHADO DESDE</b></th>';
        $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>RECEPCIONADO EN</b></th>';
        $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>CLASE</b></th>';
        $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>PROVEEDOR / CLIENTE</b></th>';
        $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>PRODUCTO</b></th>';
        $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>CLASIFICACION</b></th>';
        $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>TM</b></th>';
        $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>TM LLEGADA </b></th>';
        $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>TIQUETE EMPRESA</b></th>';
        $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>FECHA REGISTRO</b></th>';
        $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>OPERACION</b></th>';
        $echo.='</tr></thead><tbody>';
        while($aa_ingresos = sqlsrv_fetch_array($resul_ingresos)){
            $echo.='<tr>';
            $echo.='<td>'.utf8_encode($aa_ingresos['UnidadDeNegocio']).'</td>';
            $echo.='<td>'.utf8_encode($aa_ingresos['Departamento']).'</td>';
            $echo.='<td>'.utf8_encode($aa_ingresos['Empresa']).'</td>';
            $echo.='<td>'.utf8_encode($aa_ingresos['DespachadoDesde']).'</td>';
            $echo.='<td>'.utf8_encode($aa_ingresos['RecepcionadoEn']).'</td>';
            $echo.='<td>'.utf8_encode($aa_ingresos['Clase']).'</td>';
            $echo.='<td>'.utf8_encode($aa_ingresos['Proveedor']).'</td>';
            $echo.='<td>'.utf8_encode($aa_ingresos['Producto']).'</td>';
            $echo.='<td>'.utf8_encode($aa_ingresos['Clasificacion']).'</td>';
            $echo.='<td>'.number_format($aa_ingresos['Toneladas'],2).'</td>';
            $echo.='<td>'.number_format($aa_ingresos['ToneladasLlegadas'],2).'</td>';
            $echo.='<td>'.utf8_encode($aa_ingresos['TiqueteEmpresa']).'</td>';
            $echo.='<td>'.date_format($aa_ingresos['FechaRegistro'],'Y-m-d').'</td>';
            $echo.='<td>'.utf8_encode($aa_ingresos['Operacion']).'</td>';
            $echo.='</tr>';
        }
    }
    $sql_traslados = "SELECT UnidadDeNegocio.Descripcion AS UnidadDeNegocio,Departamentos.Descripcion as Departamento,tz_MovimientoTransporte.Empresa,tz_MovimientoTransporte.DespachadoDesde,tz_MovimientoTransporte.RecepcionadoEn,Clase.Descripcion as Clase,Proveedores.NombreCorto as Cliente,
    Productos.Descripcion AS Producto,tz_MovimientoTransporte.Clasificacion,ISNULL(SUM(tz_MovimientoTransporte.Toneladas),0) AS Toneladas,ISNULL(SUM(tz_MovimientoTransporte.ToneladasRecepcion),0) AS ToneladasLlegadas
    ,tz_MovimientoTransporte.TiqueteEmpresa,tz_MovimientoTransporte.FechaRegistro
    ,(CASE WHEN (tz_MovimientoTransporte.Empresa<>Proveedores.NombreCorto) THEN 'VENTAS' ELSE (CASE WHEN (Clase.Descripcion='Puertos') THEN 'PUERTOS' ELSE 'SALIDAS' END) END) AS Operacion
FROM tz_MovimientoTransporte 
    INNER JOIN Movimiento ON tz_MovimientoTransporte.NumeroTransaccion= Movimiento.NumeroTransaccion
    INNER JOIN Clasificacion ON Movimiento.idClasificacion= Clasificacion.idClasificacion
    INNER JOIN UnidadDeNegocio ON Clasificacion.UnidadDeNegocio= UnidadDeNegocio.idUnidadNegocio 
    INNER JOIN Productos ON Clasificacion.idProducto= Productos.idProducto
    INNER JOIN Destino ON tz_MovimientoTransporte.idDestino= Destino.idDestino
    INNER JOIN Destino AS Destino_2 ON tz_MovimientoTransporte.idDestinoAcopio= Destino_2.idDestino
    INNER JOIN Departamentos ON Destino_2.idDepartamento= Departamentos.idDepartamento
    INNER JOIN Clase ON Destino.idClase= Clase.idClase
    LEFT JOIN Proveedores ON Movimiento.idCliente= Proveedores.idProveedor
WHERE CAST(tz_MovimientoTransporte.FechaRegistro AS DATE)>'$FechaInicioSaldo' AND CAST(tz_MovimientoTransporte.FechaRegistro AS DATE)<='$FechaFinSaldo' 
    AND tz_MovimientoTransporte.idEmpresaREAL IN('24B7153E-AB4C-4DB7-81BD-67BC87AF014C' ,'1BEA8058-7000-479D-B562-AD71D60C68B7') AND TipoMovimiento='Traslado'
    AND Destino.idClase IN ('A56DA92B-A411-43A1-A80F-BCFB9ED27C5B','0DC1FDD5-B80E-4BC8-B6E4-7853C8C80C6D','E2BAD9F6-9C90-4190-89DD-8187C87B78BF')
GROUP BY tz_MovimientoTransporte.Empresa,tz_MovimientoTransporte.DespachadoDesde,Departamentos.Descripcion,RecepcionadoEn,Clase.Descripcion,Proveedores.NombreCorto,Proveedores.RazonSocial,Productos.Descripcion,tz_MovimientoTransporte.Clasificacion,UnidadDeNegocio.Descripcion
    ,tz_MovimientoTransporte.TiqueteEmpresa,tz_MovimientoTransporte.FechaRegistro
ORDER BY UnidadDeNegocio.Descripcion,Departamentos.Descripcion,tz_MovimientoTransporte.Empresa,tz_MovimientoTransporte.DespachadoDesde,RecepcionadoEn,Clase.Descripcion,Proveedores.NombreCorto,Proveedores.RazonSocial,Productos.Descripcion,tz_MovimientoTransporte.Clasificacion,tz_MovimientoTransporte.FechaRegistro";
    $resul_traslados=sqlsrv_query($conn,$sql_traslados,$params,$options);
    $rows_traslados = sqlsrv_num_rows($resul_traslados);
    if($rows_traslados>0){
        if($rows_ingresos==0){
            $echo.='<table style="border-collapse: collapse; border-spacing: 1px; border: 1px solid #000;" border ="1">';
            $echo.='<thead><tr style="font-weight: bold;"><tr>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>UNIDAD DE NEGOCIO</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>DEPARTAMENTO</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>EMPRESA</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>DESPACHADO DESDE</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>RECEPCIONADO EN</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>CLASE</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>PROVEEDOR / CLIENTE</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>PRODUCTO</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>CLASIFICACION</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>TM</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>TM LLEGADA </b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>TIQUETE EMPRESA</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>FECHA REGISTRO</b></th>';
            $echo.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>OPERACION</b></th>';
            $echo.='</tr></thead><tbody>';
        }
        while($aa_traslados = sqlsrv_fetch_array($resul_traslados)){
            $echo.='<tr>';
            $echo.='<td>'.utf8_encode($aa_traslados['UnidadDeNegocio']).'</td>';
            $echo.='<td>'.utf8_encode($aa_traslados['Departamento']).'</td>';
            $echo.='<td>'.utf8_encode($aa_traslados['Empresa']).'</td>';
            $echo.='<td>'.utf8_encode($aa_traslados['DespachadoDesde']).'</td>';
            $echo.='<td>'.utf8_encode($aa_traslados['RecepcionadoEn']).'</td>';
            $echo.='<td>'.utf8_encode($aa_traslados['Clase']).'</td>';
            $echo.='<td>'.utf8_encode($aa_traslados['Cliente']).'</td>';
            $echo.='<td>'.utf8_encode($aa_traslados['Producto']).'</td>';
            $echo.='<td>'.utf8_encode($aa_traslados['Clasificacion']).'</td>';
            $echo.='<td>'.number_format($aa_traslados['Toneladas'],2).'</td>';
            $echo.='<td>'.number_format($aa_traslados['ToneladasLlegadas'],2).'</td>';
            $echo.='<td>'.utf8_encode($aa_traslados['TiqueteEmpresa']).'</td>';
            $echo.='<td>'.date_format($aa_traslados['FechaRegistro'],'Y-m-d').'</td>';
            $echo.='<td>'.utf8_encode($aa_traslados['Operacion']).'</td>';
            $echo.='</tr>';
        }
    }
    echo $echo;
}elseif($_POST['band']==32){
    $lugar_trabajo = $_POST['lugar_trabajo'];
    $echo = '<div class="container-fluid" style=" padding: 20px;" id="cuerpo">';
        if(isset($_SESSION['Array_empresa']['CARGADORES'])){
            $echo.='<button class="btn btn-success navbar-right" style="margin-right: 15px;" onclick="load_create_tiquete()">
                Crear tiquete <span class="glyphicon glyphicon-plus"></span>
            </button>';
        }
        /*<!--<button type="button" class="btn btn-primary navbar-right" style="margin-right: 15px;" title="Reporte de falla" data-toggle="modal" data-target="#modalReportar">
            <img src="../Imagenes/señal-alerta-png-4.png" width="30">
        </button>-->*/
    $echo.='</div>';
        $sql = "SELECT vTiquetesCargadores.idRegistro,codReporte,remision,Cliente,fechaTiquete,idDestino,idEquipo,idUsuario,fechaApertura,fechaCierre,estado,idProveedor,
            Proveedor,Equipo,Usuario,TiempoTiquete AS Tiempo,idFacturaCargador
        FROM vTiquetesCargadores 
            LEFT JOIN facturaCargadorDetalle ON vTiquetesCargadores.idRegistro=facturaCargadorDetalle.idRegistro
        WHERE idDestino='$lugar_trabajo' AND (idActividad='00000000-0000-0000-0000-000000000000' OR idActividad IS NULL)
        GROUP BY vTiquetesCargadores.idRegistro,codReporte,remision,Cliente,fechaTiquete,idDestino,idEquipo,idUsuario,fechaApertura,fechaCierre,estado,idProveedor,Proveedor,
            Equipo,Usuario,TiempoTiquete,idFacturaCargador ORDER BY fechaTiquete DESC, codReporte DESC";

    $resultado=sqlsrv_query($conn,($sql),$params,$options);
    $rows=sqlsrv_num_rows($resultado);
    if($rows>0){
        $echo.='<table id="example1" class="table table-bordered row-border hover order-column compact">
            <thead>
                <tr>
                    <th style="width: 7%">N° tiquete</th>
                    <th>Fecha Tiquete</th>
                    <th style="width: 7%">Remision</th>
                    <th>Cliente</th>
                    <th>Proveedor</th>
                    <th style="width: 9%">Cargador</th>
                    <th style="width: 5%">Hrs.</th>
                    <th style="width: 8%">Estado</th>';
        if(isset($_SESSION['Array_empresa']['CARGADORES'])){
            $echo.='<th style="width: 10%"><center>Acciones</center></th>';
        }
        $echo.='</tr>
            </thead>
            <tbody>';
            $count_horas = 0;
            while ($datos = sqlsrv_fetch_array($resultado)){
                $Tiempo = $datos['Tiempo'];
                $codReporte = $datos['codReporte'];
                $idRegistro = ENCR::encript($datos['idRegistro']);
                $is_liquidado = $datos['idFacturaCargador'];
                $title='Registrado por '.utf8_encode($datos['Usuario']).' abierto '.date_format($datos['fechaApertura'],'Y-m-d H:i').' cerrado '.date_format($datos['fechaCierre'],'Y-m-d H:i');
                $echo.='<tr>';
                    if($datos['estado'] == 3){
                        $echo.='<td><center><a onclick="ver_tiquete_anexo(\''.$idRegistro.'\',\''.$codReporte.'\')" title="'.$title.'">'.$datos['codReporte'].'</a></center></td>';
                    }else{
                        $echo.='<td><center>'.$datos['codReporte'].'</center></td>';
                    }
                    $echo.='<td>'.date_format($datos['fechaTiquete'],'Y-m-d').'</td>';
                    $echo.='<td><center>'.$datos['remision'].'</center></td>';
                    $echo.='<td>'.utf8_encode($datos['Cliente']).'</td>';
                    $echo.='<td>'.utf8_encode($datos['Proveedor']).'</td>';
                    $echo.='<td>'.$datos['Equipo'].'</td>';
                    $echo.='<td><center>'.number_format($Tiempo,1,',','.').'</center></td>';
                    //$echo.='<td><center>N/A</center></td>';
                    if ($datos['estado'] == 1){
                        $echo.='<td style="background-color: #E15A5A;">
                            <center><b>Activo</b></center>';
                    }elseif ($datos['estado'] == 2){
                        $echo.='<td style="background-color: #F0AF44;">
                            <center><b>Por Distribuir</b></center>';
                    }elseif ($datos['estado'] == 3){
                        $echo.='<td style="background-color: #94C27C;">
                            <center><b>Finalizado<b></center>';
                    }elseif ($datos['estado'] == 4){
                        $echo.='<td style="background-color: #B57CC2;">
                            <center><b>Corrección</b></center>';
                    }
                    $echo.='</td>';
                    $echo.='<td><center>';
                    if(isset($_SESSION['Array_empresa']['CARGADORES'])){
                        if (date_format($datos['fechaCierre'],'Y-m-d') == '1900-01-01'){
                            $sql = "SELECT fechaApertura_horometro,fechaCierre_horometro,idActividad,horometroInicial,horometroFinal,Tiempo FROM vTiquetesCargadores WHERE idRegistro='".$datos['idRegistro']."' AND (horometroFinal=0)";
                            $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
                            $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
                            $rows_horometro=sqlsrv_num_rows($res);
                            if($rows_horometro>0){
                                while ($horo = sqlsrv_fetch_array($res)){
                                    //$id_horometro = ENCR::encript($horo['id_horometro']);
                                    $idActividad=ENCR::encript($horo['idActividad']);
                                }
                                $echo.='<button class="btn btn-danger" title="Finalizar actividad" onclick="load_horometro_tiquete(\''.$idRegistro.'\',\''.$idActividad.'\')">
                                        <span class="glyphicon glyphicon-minus"></span>
                                    </button>';
                            }
                            $sql = "SELECT fechaApertura_horometro,fechaCierre_horometro,idActividad,horometroInicial,horometroFinal,Tiempo 
                            FROM vTiquetesCargadores WHERE idRegistro='".$datos['idRegistro']."'";
                            $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
                            $var_tiene_horometro=sqlsrv_num_rows($res);
                            $sql = "SELECT Mantenimiento_equipos.idxid, Mantenimiento_equipos.idEquipo, Mantenimiento_equipos.horo_mtto_inicial,
                                            Mantenimiento_equipos.horo_mtto_final, Mantenimiento_equipos.total_horas, Mantenimiento_equipos.fecha_inicial_mtto, 
                                            Mantenimiento_equipos.fecha_final_mtto, Mantenimiento_equipos.observaciones, Equipos.Descripcion, Equipos.Identificacion, 
                                            Proveedores.NombreCorto, EquiposGrupos.Descripcion AS Categoria, Usuarios.NombreUsuarioLargo, Mantenimiento_equipos.id_usuario
                                    FROM Mantenimiento_equipos INNER JOIN 
                                        Equipos ON Mantenimiento_equipos.idEquipo = Equipos.idEquipo INNER JOIN
                                        Proveedores ON Equipos.idPropietario = Proveedores.idProveedor INNER JOIN
                                        EquiposGrupos ON Equipos.clase_equipo = EquiposGrupos.idGrupo INNER JOIN
                                        Usuarios ON Mantenimiento_equipos.id_usuario = Usuarios.idUsuario
                                    WHERE Mantenimiento_equipos.idEquipo='".$datos['idEquipo']."' AND horo_mtto_final=0";
                            $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
                            $res=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
                            $rows_mantto=sqlsrv_num_rows($res);
                            if ($rows_mantto>0){
                                while($mantto = sqlsrv_fetch_array($res)){
                                    if ($mantto['horo_mtto_final'] == 0){
                                        $valores1 = $mantto['idxid']."||".
                                                    date_format($mantto['fecha_inicial_mtto'],'Y-m-d H:i:s')."||".
                                                    $mantto['horo_mtto_inicial']."||".
                                                    $mantto['Descripcion']."||".
                                                    $mantto['Identificacion']."||".
                                                    $mantto['NombreCorto']."||".
                                                    $mantto['Categoria']."||".
                                                    $mantto['NombreUsuarioLargo']."||".
                                                    $datos['id_registro']."||".
                                                    $mantto['idEquipo'];
                                        $iniciado_por = $mantto['id_usuario'];
                                    }
                                }
                                if ($iniciado_por == $usuario){
                                $echo.='<button class="btn btn-danger" title="Hay un mantenimiento en proceso" data-toggle="modal" data-target="#modalFinalizarMtto" onclick="FinalizarActividad(\''.$valores1.'\')">
                                        <span class="glyphicon glyphicon-wrench"></span>
                                    </button>';
                                }else{
                                $echo.='<button class="btn btn-danger" title="Hay un mantenimiento en proceso">
                                        <span class="glyphicon glyphicon-wrench"></span>
                                    </button>';
                                }
                            }
                            $disabled = "";
                            if($rows_horometro==0 && $rows_mantto==0){
                                $echo.='<button class="btn btn-info" title="Iniciar actividad" onclick="load_horometro_tiquete(\''.$idRegistro.'\',1)">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>';
                            }else{
                                $disabled = "disabled";
                            }
                            /*if($var_tiene_horometro==0){
                                'asda';
                            }*/
                            $echo.='<button id="button" title="Finalizar tiquete" class="btn btn-success" onclick="load_tiquete_cargador(\''.$idRegistro.'\',\''.$codReporte.'\',1)" '.$disabled.'>
                                <span class="glyphicon glyphicon-ok"></span>
                            </button>';
                            $echo.='<button class="btn btn-default" title="ver horometros" onclick="ver_horometros_tiquete(\''.$idRegistro.'\')">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>
                        </center>';
                        }else{
                            $echo.='<button class="btn btn-default" title="ver horometros" onclick="ver_horometros_tiquete(\''.$idRegistro.'\')">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>';
                            if ($datos['estado'] == 2){
                                $echo.='<button class="btn btn-warning" onclick="asignacion_tiempos_tiquete(\''.$idRegistro.'\',0)"><span class="glyphicon glyphicon-th-list"></span></button>';
                            }elseif ($datos['estado'] == 3){
                                $echo.='<button class="btn btn-default" data-toggle="modal" data-target="#modalInforme" onclick="informe(\''.$idRegistro.'\')"><span class="glyphicon glyphicon-floppy-save"></span></button>';
                            }elseif ($datos['estado'] == 4){
                                $echo.='<td style="background-color: #B57CC2;">
                                    <center><b>Corrección</b></center>';
                            }
                        }
                    }else{
                        $echo.='<button class="btn btn-default" title="ver horometros" onclick="ver_horometros_tiquete(\''.$idRegistro.'\')">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </button>';
                    }
                    if(isset($_SESSION['Array_empresa']['ELIMINAR_CARGADORES'])){
                        if($is_liquidado==NULL){
                            $echo.='<button class="btn btn-danger" onclick="delete_tiquete_cargador(\''.$idRegistro.'\','.$datos['codReporte'].')"><span class="glyphicon glyphicon-trash"></span></button>';
                        }
                    }
                    $echo.='</center></td>';
                $echo.='</tr>';
            }
            $echo.='</tbody>
                <td  colspan="5"></td>
                <td><b>Total horas:</b></td>';
            //$echo.='<td><center><b>'.number_format($count_horas,1,',','.').'</b></center></td>';
            $echo.='<td><center><b>N/A</b></center></td>';
        $echo.='</table>';
    } else {
        echo "<br><br><br><br><center>No hay tiquetes registrados por el momento</center>";
    }
    echo $echo;
}elseif($_POST['band']==33){
    $lugar_trabajo = $_POST['lugar_trabajo'];
    $sql_trabajo = "SELECT * FROM Destino WHERE idDestino='$lugar_trabajo'";
    $res = sqlsrv_query($conn,$sql_trabajo);
    while($aa = sqlsrv_fetch_array($res)){
        $name_site_work = utf8_encode($aa['Descripcion']);
    }
    $sql = "SELECT MAX(codReporte) as Maximo FROM TiquetesCargadores";
    $result = sqlsrv_query($conn, $sql);
    if($result){
        while($row = sqlsrv_fetch_array($result)){
            $valor = $row['Maximo'] + 1;
        }
    }
    $echo='<div class="modal-body">
            <div class="row form-group">';
            $echo.='<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <label>Cliente</label>
                <select class="form-control" id="cliente">
                <option value="0">Seleccione</option>';
                $sql = "SELECT Proveedores.* FROM ProveedoresGrupos INNER JOIN Proveedores ON ProveedoresGrupos.idProveedor=Proveedores.idProveedor
                WHERE idAgrupacion='0E2B352C-AD00-489D-8581-FF3B14A2C8AF' ORDER BY Proveedores.Alias";
                $result = sqlsrv_query($conn,$sql);
                while($cliente = sqlsrv_fetch_array($result)){
                    $echo.='<option value="'.$cliente['idProveedor'].'">'.utf8_encode($cliente['Alias']).'</option>';
                }
            $echo.='</select></div>';
            $echo.='<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <label>Fecha</label>
                <input type="date" class="form-control" id="fecha" max="'.date('Y-m-d').'">';
            $echo.='</div>';
            $echo.='<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <label>Remisión</label>
                <input type="text" class="form-control" id="remision" placeholder="Digite el tiquete de remisión">
                </div>';
            $echo.='</div><div class="row form-group">';
            $echo.='<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <label>Proveedor Equipo</label>
                <select class="form-control" id="proveedor_cargador" onchange="load_equipo_proveedor()">
                <option value="0">Seleccione</option>';
                $sql_proveedores="SELECT Proveedores.RazonSocial, Proveedores.idProveedor FROM Equipos 
                    INNER JOIN Proveedores ON Equipos.idPropietario=Proveedores.idProveedor
                         AND Equipos.clase_equipo='7A975CD6-2672-430D-B29E-7149A03D9410'
                    GROUP BY Proveedores.RazonSocial, Proveedores.idProveedor ORDER BY Proveedores.RazonSocial";
                $result = sqlsrv_query($conn,$sql_proveedores);
                while($cliente = sqlsrv_fetch_array($result)){
                    $echo.='<option value="'.$cliente['idProveedor'].'">'.utf8_encode($cliente['RazonSocial']).'</option>';
                }
            $echo.='</select></div>';
            $echo.='<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                <label>Equipo</label>
                <select class="form-control" id="equipo_cargador" onchange="load_horometro_cargador()">
                <option value="0" selected disabled>Seleccione</option>';
            $echo.='</select></div>';
            $echo.='<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                <label>Horometro</label>
                <input type="number" class="form-control" id="horometro" readonly>
                </div>';
    $echo.='</div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" onclick="save_tiquete_cargador()">Guardar tiquete</button>
        </div>';
    echo $valor.'||'.$echo.'||'.$name_site_work;
}elseif($_POST['band']==33.5){
    $equipo_cargador =  $_POST['equipo_cargador'];
    $sql = "SELECT Equipos.horometro_final FROM Equipos WHERE idEquipo='$equipo_cargador'";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);
    if($rows>0){
        while($aa = sqlsrv_fetch_array($resul)){
            $horometro_final = $aa['horometro_final'];
        }
    }
    echo $horometro_final;
}elseif($_POST['band']==34){
    $proveedor_cargador = $_POST['proveedor_cargador'];
        $sql_equipo = "SELECT * FROM Equipos WHERE idPropietario='$proveedor_cargador'";
        $result = sqlsrv_query($conn,$sql_equipo);
        $echo = '<option value="0">Seleccione</option>';
        while($equipo = sqlsrv_fetch_array($result)){
            $echo.='<option value="'.$equipo['idEquipo'].'">'.utf8_encode($equipo['Descripcion'].' '.$equipo['Identificacion']).'</option>';
        }
    echo $echo;
}elseif($_POST['band']==35){
    $lugar_trabajo = $_POST['lugar_trabajo'];
    $cliente = $_POST['cliente'];
    $fecha = $_POST['fecha'];
    $remision = $_POST['remision'];
    $proveedor_cargador = $_POST['proveedor_cargador'];
    $equipo_cargador = $_POST['equipo_cargador'];

    $insert_tiquete = "INSERT INTO TiquetesCargadores (idRegistro,remision,fechaTiquete,idDestino,idCliente,idProveedor,idEquipo,fechaApertura,fechaCierre,estado,idUsuario) 
    VALUES (NEWID(),'$remision','$fecha','$lugar_trabajo','$cliente','$proveedor_cargador','$equipo_cargador',GETDATE(),'1900-01-01 00:00:00',1,'$idUsuario')";
    $result = sqlsrv_query($conn,$insert_tiquete);
    if($result){
        echo 1;
    }else{
        echo 'No se pudo crear correctamente el tiquete';
    }
}elseif($_POST['band']==36){
    $idRegistro = ENCR::descript($_POST['idRegistro']);
    $idActividad = ENCR::descript($_POST['idActividad']);
    $echo_title = '';
    $echo_body='<div class="modal-body">';
    if($_POST['idActividad']==1){
        $sql = "SELECT ISNULL(MAX(horometroFinal),0) AS horometroFinal,codReporte FROM vTiquetesCargadores WHERE idRegistro='$idRegistro' GROUP BY codReporte";
        $resul=sqlsrv_query($conn,$sql,$params,$options);
        $rows = sqlsrv_num_rows($resul);
        if($rows>0){
            while($aa = sqlsrv_fetch_array($resul)){
                $horometroFinal = $aa['horometroFinal'];
                $codReporte = $aa['codReporte'];
            }
        }
        $echo_title.='<b>Tiquete #'.$codReporte.'</b> - Iniciar actividad';
        $echo_body.='<div class="row form-group center-block">';
            /*$echo_body.='<div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <label>Unidad De Negocio</label>
                            <select class="form-control" id="UnidadDeNegocio" onchange="load_producto_negocio()">
                                <option value="0" selected disabled>Seleccione</option>';
                                $sql = "SELECT * FROM UnidadDeNegocio ORDER BY Descripcion";
                                $res = sqlsrv_query($conn,$sql);
                                while($aa = sqlsrv_fetch_array($res)){
                                    $echo_body.='<option value="'.$aa['idUnidadNegocio'].'">'.utf8_encode($aa['Descripcion']).'</option>';
                                }
                            $echo_body.='</select>
                        </div>
                        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <label>Producto</label>
                            <select class="form-control" id="producto">
                                <option value="0" selected disabled>Seleccione</option>
                            </select>
                        </div>';*/
            $echo_body.='<div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <label>Horometro inicial:</label>
                            <input  type="number" id="horometro_inicial" value="'.$horometroFinal.'" class=" form-control">
                        </div>
                        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <label>Hora inicio actividad</label>
                            <input type="text" id="FechaHora_ini" value="'.date('Y-m-d H:i').'" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="save_horometro_tiquete(\''.$idRegistro.'\',1)">Iniciar</button>
                </div>';
    }else{
        $sql = "SELECT codReporte,horometroInicial,fechaApertura_horometro,idUnidadNegocio,UnidadDeNegocio,idProducto,Producto FROM vTiquetesCargadores 
            WHERE idRegistro='$idRegistro' AND idActividad='00000000-0000-0000-0000-000000000000' AND CAST(fechaCierre_horometro AS date)='1900-01-01'";

        $resul=sqlsrv_query($conn,$sql,$params,$options);
        $rows = sqlsrv_num_rows($resul);
        if($rows>0){
            while($aa = sqlsrv_fetch_array($resul)){
                $horometroInicial = $aa['horometroInicial'];
                $fechaApertura_horometro = date_format($aa['fechaApertura_horometro'],'Y-m-d H:i');
                $codReporte = $aa['codReporte'];
                $UnidadDeNegocio = utf8_encode($aa['UnidadDeNegocio']);
                $Producto = utf8_encode($aa['Producto']);
            }
        }
        $echo_title.='<b>Tiquete #'.$codReporte.'</b> - Finalizar Actividad';
        $echo_body.='<div class="row form-group center-block">';
            /*$echo_body.='<div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <label>Unidad De Negocio</label>
                            <input class="form-control" id="UnidadDeNegocio" value="'.$UnidadDeNegocio.'" readonly>
                        </div>
                        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <label>Producto</label>
                            <input class="form-control" id="producto" value="'.$Producto.'" readonly>
                        </div>';*/
            $echo_body.='<div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <label>Fecha Inicio</label>
                            <input type="text" id="fecha_inicio" value="'.$fechaApertura_horometro.'" class="form-control" readonly="">
                        </div>
                        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <label>Fecha Cierre</label>
                            <input type="text" id="fecha_fin" value="'.date('Y-m-d H:i').'" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-4">
                            <label>Horometro inicial:</label>
                            <input  type="number" id="horometro_inicio" value="'.$horometroInicial.'" class=" form-control" readonly="">
                        </div>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            <label>Horometro final:</label>
                            <input  type="number" id="horometro_final" class="form-control" autofocus="autofocus">
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="save_horometro_tiquete(\''.$idRegistro.'\',\'00000000-0000-0000-0000-000000000000\')">Finalizar</button>
                </div>';
    }
    echo $echo_title.'||'.$echo_body;
}elseif($_POST['band']==36.5){
    $UnidadDeNegocio = $_POST['UnidadDeNegocio'];
    $sql = "SELECT Productos.idProducto,Productos.Descripcion FROM Clasificacion 
        INNER JOIN Productos ON Clasificacion.idProducto=Productos.idProducto
    WHERE UnidadDeNegocio='$UnidadDeNegocio'
    GROUP BY Productos.idProducto,Productos.Descripcion";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);
    $echo = '<option value=0 selected disabled>Seleccione Producto</option>';
    if($rows>0){
        while($aa = sqlsrv_fetch_array($resul)){
            $echo.='<option value="'.$aa['idProducto'].'">'.utf8_encode($aa['Descripcion']).'</option>';
        }
    }
    echo $echo;
}elseif($_POST['band']==37){
    $idRegistro = $_POST['idRegistro'];
    $idActividad = $_POST['idActividad'];
    $horometro = $_POST['horometro'];
    //$idUnidadDeNegocio = $_POST['UnidadDeNegocio'];
    //$idProducto = $_POST['producto'];
    if($idActividad==1){
        /*,'$idUnidadDeNegocio','$idProducto'*/
        $sql = "INSERT INTO TiquetesCargadores_horometro (idRegistro,idActividad,idUnidadNegocio,idProducto,horometroInicial,horometroFinal,Tiempo,idUsuario,fechaApertura,fechaCierre,descuento,observaciones) 
        VALUES ('$idRegistro','00000000-0000-0000-0000-000000000000','00000000-0000-0000-0000-000000000000','00000000-0000-0000-0000-000000000000',$horometro,0,0,'$idUsuario','$fecha_registro','1900-01-01',0,'')";
    }else{
        $sql_data = "SELECT horometroInicial FROM TiquetesCargadores_horometro WHERE idRegistro='$idRegistro' AND idActividad='00000000-0000-0000-0000-000000000000' AND CAST(fechaCierre AS date)='1900-01-01'";
        $resul_data=sqlsrv_query($conn,$sql_data,$params,$options);
        $rows = sqlsrv_num_rows($resul_data);
        if($rows>0){
            while($aa = sqlsrv_fetch_array($resul_data)){
                $horometroInicial = $aa['horometroInicial'];
            }
            $Tiempo = number_format(($horometro-$horometroInicial),2);
            $sql = "UPDATE TiquetesCargadores_horometro SET horometroFinal=$horometro, Tiempo=$Tiempo, fechaCierre='$fecha_registro' 
            WHERE idRegistro='$idRegistro' AND idActividad='00000000-0000-0000-0000-000000000000' AND CAST(fechaCierre AS date)='1900-01-01'";
        }
    }
    $res = sqlsrv_query($conn,$sql);
    if($res){
        echo 1;
    }
}elseif($_POST['band']==37.5){
    $idRegistro = ENCR::descript($_POST['idRegistro']);
    $echo_title = '';
    $echo_body='<div class="modal-body">
        <table class="table table-bordered">
        <thead>
            <th>UnidadDeNegocio</th>
            <th>Producto</th>
            <th>Actividad</th>
            <th>HorometroInicial</th>
            <th>HorometroFinal</th>
            <th>Tiempo</th>
        </thead>
        <tbody>';
    $sql = "SELECT * FROM vTiquetesCargadores WHERE idRegistro='$idRegistro' ORDER BY Actividad,horometroInicial DESC";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);
    if($rows>0){
        while($aa = sqlsrv_fetch_array($resul)){
            $codReporte = $aa['codReporte'];
            $b_ini = '';
            $b_fin = '';
            if($aa['idActividad']<>'00000000-0000-0000-0000-000000000000'){
                $echo_body.='<tr>';
            }else{
                $b_ini='<b>';
                $b_fin='</b>';
                $echo_body.='<tr style="background-color: #DA9D41">';
            }
            if($aa['Actividad']==null){
                $echo_body.='<td colspan="3"><center>'.$b_ini.'TIEMPO PARA ASIGNAR'.$b_fin.'</center></td>';
            }else{
                if($aa['descuento']==0){
                    $echo_body.='<td>'.$b_ini.utf8_encode($aa['UnidadDeNegocio']).$b_fin.'</td>';
                    $echo_body.='<td>'.$b_ini.utf8_encode($aa['Producto']).$b_fin.'</td>';
                    $echo_body.='<td>'.$b_ini.utf8_encode($aa['Actividad']).$b_fin.'</td>';
                }else{
                    $b_ini='<b>';
                    $b_fin='</b>';
                    //$echo_body.='<td colspan="3" align="center"><b>'.utf8_encode($aa['Actividad']).'</b></td>';
                    $echo_body.='<td colspan="3" align="center">'.$b_ini.utf8_encode($aa['Actividad']).$b_fin.'</td>';
                }
            }
            $echo_body.='<td>'.$b_ini.number_format($aa['horometroInicial'],1).$b_fin.'</td>';
            if($aa['horometroFinal']<>0){
                $echo_body.='<td>'.$b_ini.number_format($aa['horometroFinal'],1).$b_fin.'</td>';
            }else{
                $echo_body.='<td>'.$b_ini.'0'.$b_fin.'</td>';
            }
            $echo_body.='<td>'.$b_ini.number_format($aa['Tiempo'],1).$b_fin.'</td>';
            $echo_body.='</tr>';
        }
    }
    $echo_title.='<b>Tiquete #'.$codReporte.'</b> - Detalle horometros';
    $echo_body.='</tbody></table></div>';
    echo $echo_title.'||'.$echo_body;
}elseif($_POST['band']==38){
    $lugar_trabajo = $_POST['lugar_trabajo'];
    $zona = $_POST['zona'];
    $empresa = $_POST['empresa'];
    $material_alimentado = $_POST['material_alimentado'];

    $sql="SELECT Pilas.Descripcion,Pilas.idPila FROM Movimiento 
        INNER JOIN PilasDetalle ON Movimiento.NumeroTransaccion=PilasDetalle.NumeroTransaccion
        INNER JOIN Pilas ON PilasDetalle.idPila=Pilas.idPila
    WHERE  ((Movimiento.idDestino='$lugar_trabajo' OR Movimiento.idDestinoAcopio='$lugar_trabajo') AND Movimiento.idEmpresa='$empresa' AND (Movimiento.idZona='$zona' AND Movimiento.idClasificacion='$material_alimentado')) OR Pilas.idPila='2693FC3F-DBD2-44EE-B7DE-4B53C752D3F9' 
    GROUP BY Pilas.Descripcion,Pilas.idPila ORDER BY Pilas.Descripcion";
    $resultado=sqlsrv_query($conn,$sql,$params,$options);
    $filas=sqlsrv_num_rows($resultado);
    if($filas>0){
        $echo = '<option value="0" selected disabled>Seleccione</option>';
        while ($aa = sqlsrv_fetch_array($resultado)){
            $echo.='<option value="'.$aa['idPila'].'">'.utf8_encode($aa['Descripcion']).'</option>';
        }
    }else{
        $echo = '<option value="0" selected disabled>No hay pilas</option>';
    }
    echo $echo;
}elseif($_POST['band']==38.5){
    $idRegistro = ENCR::descript($_POST['idRegistro']);
    $sql = "SELECT idDestino,codReporte,ISNULL(SUM(Tiempo),0) AS TiempoAsignado 
    FROM vTiquetesCargadores WHERE idRegistro='$idRegistro' AND idActividad='00000000-0000-0000-0000-000000000000'
    GROUP BY idDestino,codReporte";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);
    if($rows>0){
        while($aa = sqlsrv_fetch_array($resul)){
            $codReporte = $aa['codReporte'];
            $idDestino = $aa['idDestino'];
            $TiempoAsignado = number_format($aa['TiempoAsignado'],1);
        }
    }
    $sql = "SELECT ISNULL(SUM(Tiempo),0) AS TiempoConsumido 
    FROM vTiquetesCargadores WHERE idRegistro='$idRegistro' AND idActividad<>'00000000-0000-0000-0000-000000000000'";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);    
    if($rows>0){
        while($aa = sqlsrv_fetch_array($resul)){
            $TiempoConsumido = number_format($aa['TiempoConsumido'],1);
        }
    }
    $TiempoRestante = $TiempoAsignado-$TiempoConsumido;
    if($TiempoRestante==0){
        $sql = "UPDATE TiquetesCargadores SET estado=3 WHERE idRegistro='$idRegistro'";
        $res = sqlsrv_query($conn,$sql);
        if($res){
            echo 1;
        }
    }else{
        $sql = "UPDATE TiquetesCargadores SET fechaCierre=GETDATE(),estado=2 WHERE idRegistro='$idRegistro'";
        $res = sqlsrv_query($conn,$sql);
        if($res){
            echo 1;
        }
    }
}elseif($_POST['band']==39){
    $idEmpresa = $_POST['idEmpresa'];
    $fecha = $_POST['fecha'];
    $idBateria = $_POST['idBateria'];
    $idClasificacion = $_POST['idClasificacion'];
    $actividad = $_POST['actividad'];
    $tabla_resultados = '<br><div class="table-responsive1">';
    if($actividad == 'Alimentación'){
        $sql = "SELECT * FROM vCoquizacion 
            WHERE idEmpresa='$idEmpresa' AND CAST(FechaAlimentacion AS date)='$fecha' AND idBateria='$idBateria' 
                AND idClasificacionAlimentacion='$idClasificacion' ORDER BY Bateria,Horno";
        
    }/*elseif($actividad == ''){
        echo "nada";
    }*/
    $resultado=sqlsrv_query($conn,$sql,$params,$options);
    $filas=sqlsrv_num_rows($resultado);
    if($filas>0){
        $tabla_resultados.='<div class="col-sm-12">';
        $tabla_resultados.='<table class="table table-bordered" border="1" align="center"><thead><tr>';
        $tabla_resultados.= '<th rowspan="2" style="vertical-align: middle;" align="center"><b>Empresa</b></th>';
        $tabla_resultados.= '<th colspan="6" align="center" style="background-color: #778EC2;"><b>Alimentación</b></th>';
        $tabla_resultados.= '<th colspan="5" align="center" style="background-color: #C2777D;"><b>Producción</b></th>';
        if(isset($_SESSION['Array_empresa']['ELIMINAR_COQUIZACION'])){
            $tabla_resultados.='<th rowspan="2" style="vertical-align: middle;" align="center">Acciones</th>';
        }
        $tabla_resultados.= '</tr><tr>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Fecha</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Bateria</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b># Horno</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Clasificación</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Capacidad (TM)</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #778EC2;" align="center" align="center"><b>Cargue (TM)</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Fecha</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Bateria</b></th>';
        //$tabla_resultados.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b># Horno</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Clasificación</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Capacidad (TM)</b></th>';
        $tabla_resultados.= '<th style="vertical-align: middle; background-color: #C2777D;" align="center" align="center"><b>Deshorne (TM)</b></th>';
        $tabla_resultados.='</tr></thead>';
        while($aa = sqlsrv_fetch_array($resultado)){
            $idPlanta = $aa['idPlanta'];
            $idBateria = $aa['idBateria'];
            $tabla_resultados.='<tr>'; 
            $tabla_resultados.='<td>'.utf8_encode($aa['Empresa']).'</td>';
            $tabla_resultados.='<td>'.date_format($aa['FechaAlimentacion'],'Y-m-d').'</td>';
            $tabla_resultados.='<td>'.$aa['Bateria'].'</td>';
            $tabla_resultados.='<td>'.$aa['Horno'];
            $tabla_resultados.='<td>'.utf8_encode($aa['ClasificacionAlimentacion']).'</td>';
            $tabla_resultados.='<td>'.number_format($aa['Capacidad'],2).'</td>';
            $tabla_resultados.='<td>'.number_format($aa['Indice_carga'],2).'</td>';
            if($aa['ClasificacionProduccion'] == null || $aa['ClasificacionProduccion'] == ''){
                $tabla_resultados.='<td colspan="5" align="center"><b style="color: red">PENDIENTE</b></td>';
            }else{
                $tabla_resultados.='<td>'.date_format($aa['FechaProduccion'],'Y-m-d').'</td>';
                $tabla_resultados.='<td>'.$aa['Bateria'].'</td>';
                //$tabla_resultados.='<td>'.$aa['HornosProduccion'].'</td>';
                $tabla_resultados.='<td>'.utf8_encode($aa['ClasificacionProduccion']).'</td>';
                $tabla_resultados.='<td>'.number_format($aa['CapacidadDeshorne'],2).'</td>';
                $tabla_resultados.='<td>'.number_format($aa['Indice_deshorne'],2).'</td>';
            }
            if(isset($_SESSION['Array_empresa']['ELIMINAR_COQUIZACION'])){
                $tabla_resultados.='<td style="text-align: center;"><button class="btn btn-danger" onclick="delete_coquizacion_details(\''.$aa['idEmpresa'].'\',\''.$idPlanta.'\',\''.$idBateria.'\',\''.$aa['idClasificacionAlimentacion'].'\',\''.date_format($aa['FechaAlimentacion'],'Y-m-d').'\',\''.$aa['idHorno'].'\')"><span class="glyphicon glyphicon-trash"></span></button></td>';
            }
            $tabla_resultados.='</tr>';
        }
        $tabla_resultados.='</table></div></div>';
    }
    echo $tabla_resultados;
}elseif($_POST['band']==40){
    $idEmpresa=$_POST['idEmpresa'];
    $idPlanta = $_POST['idPlanta'];
    $idBateria = $_POST['idBateria'];
    $idClasificacionAlimentacion = $_POST['idClasificacionAlimentacion'];
    $FechaAlimentacion = $_POST['FechaAlimentacion'];
    $idHorno = $_POST['idHorno'];
    $var_position=0;
    $sql = "SELECT * FROM vCoquizacion WHERE idEmpresa='$idEmpresa' AND idPlanta='$idPlanta' AND idBateria='$idBateria' AND idClasificacionAlimentacion='$idClasificacionAlimentacion' 
        AND CAST(FechaAlimentacion AS date)='$FechaAlimentacion' AND idHorno='$idHorno'";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);
    if($rows>0){
        while($aa = sqlsrv_fetch_array($resul)){
            $array_idAlimentacion[$var_position] = $aa['idAlimentacion_hornos'];
            $var_position++;
        }
        $array_idAlimentacion = implode("','", $array_idAlimentacion);
        $sql_delete_produccion = "DELETE FROM Produccion_hornos_recetas WHERE idAlimentacion_hornos IN ('$array_idAlimentacion')";
        $res = sqlsrv_query($conn,$sql_delete_produccion);
        
        $sql_delete_alimentacion = "DELETE FROM Alimentacion_hornos_recetas WHERE idAlimentacion_hornos IN ('$array_idAlimentacion')";
        $res = sqlsrv_query($conn,$sql_delete_alimentacion);
        if($res){
            echo 1;
        }
    }
}elseif($_POST['band']==41){
    $idRegistro = ENCR::descript($_POST['idRegistro']);
    $echo_title = '';
    $echo_body='<div class="modal-body">';
    $sql = "SELECT idDestino,codReporte,ISNULL(SUM(Tiempo),0) AS TiempoAsignado 
    FROM vTiquetesCargadores WHERE idRegistro='$idRegistro' AND idActividad='00000000-0000-0000-0000-000000000000'
    GROUP BY idDestino,codReporte";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);    
    if($rows>0){
        while($aa = sqlsrv_fetch_array($resul)){
            $codReporte = $aa['codReporte'];
            $idDestino = $aa['idDestino'];
            $TiempoAsignado = number_format($aa['TiempoAsignado'],1);
        }
    }
    $sql = "SELECT ISNULL(SUM(Tiempo),0) AS TiempoConsumido 
    FROM vTiquetesCargadores WHERE idRegistro='$idRegistro' AND idActividad<>'00000000-0000-0000-0000-000000000000'";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);    
    if($rows>0){
        while($aa = sqlsrv_fetch_array($resul)){
            $TiempoConsumido = number_format($aa['TiempoConsumido'],1);
        }
    }
    $TiempoRestante = $TiempoAsignado-$TiempoConsumido;
    $disabled="";
    if($TiempoRestante==0){
        $echo_body.='<center><h4 style="text-decoration: underline;"><b>No hay horas por asignar</b></h4></center>';
    }else{
        $disabled="disabled";
        $echo_body.='<center><h4 style="background-color: powderblue; text-decoration: underline;">Falta por asignar <b>'.$TiempoRestante.'</b> horas</h4></center>';
    }
    $echo_body.='<table class="table table-bordered">
        <thead>
            <th>UnidadDeNegocio</th>
            <th>Producto</th>
            <th>Actividad</th>
            <th>Tiempo</th>
            <th>Observaciones</th>';
    if(isset($_SESSION['Array_empresa']['ELIMINAR_CARGADORES'])){
        $echo_body.='<th>Acciones</th>';
    }
    $echo_body.='</thead>
        <tbody>';
    $sql = "SELECT * FROM vTiquetesCargadores WHERE idRegistro='$idRegistro' AND idActividad<>'00000000-0000-0000-0000-000000000000' ORDER BY horometroInicial DESC";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);
    $hay_descuento=0;
    if($rows>0){
        while($aa = sqlsrv_fetch_array($resul)){
            $codReporte = $aa['codReporte'];
            $idUnidadNegocio = $aa['idUnidadNegocio'];
            $idProducto = $aa['idProducto'];
            $idActividad = $aa['idActividad'];
            if($aa['observaciones']==''){
                $observaciones = 'N/A';
            }else{
                $observaciones = utf8_encode($aa['observaciones']);
            }
            $datos = $_POST['idRegistro'].",".
                    $idUnidadNegocio.",".
                    $idProducto.",".
                    $idActividad.",".
                    strtoupper(utf8_encode($aa['Actividad'])).",".
                    strtoupper(utf8_encode($aa['UnidadDeNegocio'])).",".
                    strtoupper(utf8_encode($aa['Producto']));
            $echo_body.='<tr>';
            
            if($aa['descuento']==0){
                $echo_body.='<td>'.utf8_encode($aa['UnidadDeNegocio']).'</td>';
                $echo_body.='<td>'.utf8_encode($aa['Producto']).'</td>';
                $echo_body.='<td>'.utf8_encode($aa['Actividad']).'</td>';
            }else{
                $hay_descuento++;
                $echo_body.='<td colspan="3" align="center"><b>'.utf8_encode($aa['Actividad']).'</b></td>';
            }
            $echo_body.='<td>'.number_format($aa['Tiempo'],1).'</td>';
            $echo_body.='<td align="center">'.$observaciones.'</td>';
            if(isset($_SESSION['Array_empresa']['ELIMINAR_CARGADORES'])){
                $echo_body.='<td align="center"><button class="btn btn-danger" onclick="delete_distribucion_cargador(\''.$datos.'\')"><span class="glyphicon glyphicon-trash"></span></button></td>';
            }
            $echo_body.='</tr>';
        }
    }else{
        $echo_body.='<tr>';
        if(isset($_SESSION['Array_empresa']['ELIMINAR_CARGADORES'])){
            $echo_body.='<td colspan="5" align="center">No hay actividades asignadas</td>';
        }else{
            $echo_body.='<td colspan="4" align="center">No hay actividades asignadas</td>';
        }
        $echo_body.='</tr>';
    }
    $echo_body.='</tbody></table><br>';
    if($TiempoRestante<>0){
    $echo_body.='<center><button class="btn btn-secondary">Actividades <span class="glyphicon glyphicon-eye-open"></span></button></center>
        <div class="row form-group center-block"><br>
        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
            <label>Unidad De Negocio</label>
            <select class="form-control" id="UnidadDeNegocio" onchange="load_producto_negocio()">
                <option value="0" selected disabled>Seleccione</option>';
                $sql = "SELECT * FROM UnidadDeNegocio ORDER BY Descripcion";
                $res = sqlsrv_query($conn,$sql);
                while($aa = sqlsrv_fetch_array($res)){
                    $echo_body.='<option value="'.$aa['idUnidadNegocio'].'">'.utf8_encode($aa['Descripcion']).'</option>';
                }
            $echo_body.='</select>
        </div>
        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
            <label>Producto</label>
            <select class="form-control" id="producto" onchange="load_actividad_cargador(\''.$_POST['idRegistro'].'\')">
                <option value="0" selected disabled>Seleccione</option>
            </select>
        </div>
        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
            <label>Actividad</label>
            <select class="form-control" id="Actividad">
                <option value="0" selected disabled>Seleccione un producto</option>
            </select>
        </div>
        <div class="col-xs-2  col-sm-2  col-md-2  col-lg-2  col-xl-2">
            <label>Tiempo</label>
            <input class="form-control" id="Tiempo">
        </div>  
        <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
            <button class="btn btn-success" style="margin-top: 25px;" onclick="save_distribucion_cargador(\''.$_POST['idRegistro'].'\',0)"><span class="glyphicon glyphicon-ok"></span></button>
        </div>
        </div>';
        if($hay_descuento==0){
            $echo_body.='<center><button class="btn btn-secondary">Descuento <span class="glyphicon glyphicon-eye-open"></span></button></center><br>
            <div class="row form-group center-block">
                <div class="col-xs-9  col-sm-9  col-md-9  col-lg-9  col-xl-9">
                    <label>Motivo del descuento</label>
                    <textarea class="form-control" id="descuento"></textarea>
                </div>
                <div class="col-xs-2  col-sm-2  col-md-2  col-lg-2  col-xl-2">
                    <label>Tiempo</label>
                    <input class="form-control" id="Tiempo_descuento">
                </div>  
                <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                    <button class="btn btn-success" style="margin-top: 25px;" onclick="save_distribucion_cargador(\''.$_POST['idRegistro'].'\',1)"><span class="glyphicon glyphicon-ok"></span></button>
                </div>
            </div>';
        }
    }
    $echo_title.='<b>Tiquete #'.$codReporte.'</b> - Distribución de horas';
    $echo_body.='</div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="load_tiquete_cargador(\''.$_POST['idRegistro'].'\',\''.$codReporte.'\',2)" '.$disabled.'>Finalizar Asignación <span class="glyphicon glyphicon-floppy-save"></span></button>
                </div>';
    echo $echo_title.'||'.$echo_body;
}elseif($_POST['band']==42){
    $idRegistro = ENCR::descript($_POST['idRegistro']);
    $UnidadDeNegocio = $_POST['UnidadDeNegocio'];
    $Producto = $_POST['Producto'];
    $idDestino = $_POST['idDestino'];
    $echo = '';
    $sql = "SELECT Actividades.idActividad,Actividades.Descripcion FROM Actividades_cargadores_destinos 
        INNER JOIN Actividades ON Actividades_cargadores_destinos.idActividad=Actividades.idActividad 
    WHERE idDestino='$idDestino' AND Estado=1 AND Actividades.idActividad NOT IN (SELECT idActividad FROM TiquetesCargadores_horometro WHERE idRegistro='$idRegistro' AND idUnidadNegocio='$UnidadDeNegocio' AND idProducto='$Producto') ORDER BY Descripcion";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);
    if($rows>0){
        $echo.='<option value="0" selected disabled>Seleccione</option>';
        while($aa = sqlsrv_fetch_array($resul)){
            $echo.='<option value="'.$aa['idActividad'].'">'.utf8_encode($aa['Descripcion']).'</option>';
        }
    }else{
        $echo.='<option value="0" selected disabled>No hay actividades disponibles</option>';
    }
    echo $echo;
}elseif($_POST['band']==43){
    $idRegistro = ENCR::descript($_POST['idRegistro']);
    $iDescuento = $_POST['iDescuento'];
    if($iDescuento==0){
        $UnidadDeNegocio = $_POST['UnidadDeNegocio'];
        $Producto = $_POST['Producto'];
        $Actividad = $_POST['Actividad'];
    }else{
        $Descuento = $_POST['Descuento'];
    }
    $Tiempo = $_POST['Tiempo'];

    $sql = "SELECT idDestino,codReporte,ISNULL(SUM(Tiempo),0) AS TiempoAsignado 
    FROM vTiquetesCargadores WHERE idRegistro='$idRegistro' AND idActividad='00000000-0000-0000-0000-000000000000'
    GROUP BY idDestino,codReporte";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);    
    if($rows>0){
        while($aa = sqlsrv_fetch_array($resul)){
            $codReporte = $aa['codReporte'];
            $idDestino = $aa['idDestino'];
            $TiempoAsignado = number_format($aa['TiempoAsignado'],1);
        }
    }
    $sql = "SELECT ISNULL(SUM(Tiempo),0) AS TiempoConsumido 
    FROM vTiquetesCargadores WHERE idRegistro='$idRegistro' AND idActividad<>'00000000-0000-0000-0000-000000000000'";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);    
    if($rows>0){
        while($aa = sqlsrv_fetch_array($resul)){
            $TiempoConsumido = number_format($aa['TiempoConsumido'],1);
        }
    }
    $TiempoRestante = $TiempoAsignado-$TiempoConsumido;
    if((number_format($TiempoRestante,2)-$Tiempo)>=0){
        if($iDescuento==0){
            $sql_insert = "INSERT INTO TiquetesCargadores_horometro (idRegistro,idActividad,idUnidadNegocio,idProducto,horometroInicial,horometroFinal,Tiempo,idUsuario,fechaApertura,fechaCierre,descuento,observaciones) 
            VALUES ('$idRegistro','$Actividad','$UnidadDeNegocio','$Producto',0,0,$Tiempo,'$idUsuario','$fecha_registro','$fecha_registro',$iDescuento,'')";
        }else{
            $sql_insert = "INSERT INTO TiquetesCargadores_horometro (idRegistro,idActividad,idUnidadNegocio,idProducto,horometroInicial,horometroFinal,Tiempo,idUsuario,fechaApertura,fechaCierre,descuento,observaciones) 
            VALUES ('$idRegistro','1055C2AD-3EED-422F-B50A-E72C0DEEB864','00000000-0000-0000-0000-000000000000','00000000-0000-0000-0000-000000000000',0,0,$Tiempo,'$idUsuario','$fecha_registro','$fecha_registro',$iDescuento,'$Descuento')";
        }
        $res_insert = sqlsrv_query($conn,$sql_insert);
        if($res_insert){
            echo 1;
        }else{
            echo 0;
        }
    }else{
        echo 2;
    }
}elseif($_POST['band']==44){
    $idRegistro = ENCR::descript($_POST['idRegistro']);
    $UnidadDeNegocio = $_POST['UnidadDeNegocio'];
    $Producto = $_POST['Producto'];
    $Actividad = $_POST['Actividad'];

    $sql="SELECT * FROM TiquetesCargadores WHERE idRegistro='$idRegistro' AND estado=2";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);    
    if($rows>0){
        $sql_delete = "DELETE FROM TiquetesCargadores_horometro 
        WHERE idRegistro='$idRegistro' AND idUnidadNegocio='$UnidadDeNegocio' AND idProducto='$Producto' AND idActividad='$Actividad'";
        $res_delete = sqlsrv_query($conn,$sql_delete);
        if($res_delete){
            echo 1;
        }
    }else{
        echo 'No se puede modificar la info del Tiquete';
    }
}elseif($_POST['band']==45){
    $idRegistro = ENCR::descript($_POST['idRegistro']);
    $delete_detalle = "DELETE FROM TiquetesCargadores_horometro WHERE idRegistro='$idRegistro'";
    $res_detalle = sqlsrv_query($conn,$delete_detalle);
    if($res_detalle){
        $delete = "DELETE FROM TiquetesCargadores WHERE idRegistro='$idRegistro'";
        $res = sqlsrv_query($conn,$delete);
        if($res){
            echo 1;
        }
    }
}elseif($_POST['band']==46){
    $informe = $_POST['informe'];
    $echo = '';
    $txt_array_empresa = implode("','", $_SESSION['Array_empresa']['CONSULTAS']);
    if($informe=='cargador'){
        $echo.='<div class="row">
                <div class="col-xs-12 col-sm-4 col-md-2 col-lg-1"></div>
                <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2">
                    <label>Proveedor</label>
                    <select class="form-control" id="list_proveedor_inventario">
                        <option value="0">TODOS</option>';
                    $sql="SELECT idProveedor,Proveedor FROM vTiquetesCargadores GROUP BY idProveedor,Proveedor ORDER BY Proveedor";
                    $resul=sqlsrv_query($conn,$sql,$params,$options);
                    $rows = sqlsrv_num_rows($resul);
                    if($rows>0){
                        while($aa = sqlsrv_fetch_array($resul)){
                            $echo.='<option value="'.$aa['idProveedor'].'">'.utf8_encode($aa['Proveedor']).'</option>';
                        }
                    }
        $echo.='</select>
            </div>
            <div class="col-xs-5 col-sm-3 col-md-2 col-lg-2">
                <label>Unidad De Negocio</label>
                <select class="form-control" id="list_unidad_negocio">
                    <option value="0">TODOS</option>';
                $sql="SELECT * FROM UnidadDeNegocio ORDER BY Descripcion";
                $resul=sqlsrv_query($conn,$sql,$params,$options);
                $rows = sqlsrv_num_rows($resul);
                if($rows>0){
                    while($aa = sqlsrv_fetch_array($resul)){
                        $echo.='<option value="'.$aa['idUnidadNegocio'].'">'.utf8_encode($aa['Descripcion']).'</option>';
                    }
                }
        $echo.='</select>
            </div>
            <div class="col-xs-5 col-sm-3 col-md-2 col-lg-2">
                <label>Centro de trabajo</label>
                <select class="form-control" id="list_destino_consulta">
                    <option value="0">TODOS</option>';
                $sql="SELECT idDestino,Destino FROM vTiquetesCargadores GROUP BY idDestino,Destino ORDER BY Destino";
                $resul=sqlsrv_query($conn,$sql,$params,$options);
                $rows = sqlsrv_num_rows($resul);
                if($rows>0){
                    while($aa = sqlsrv_fetch_array($resul)){
                        $echo.='<option value="'.$aa['idDestino'].'">'.utf8_encode($aa['Destino']).'</option>';
                    }
                }
        $echo.='</select>
            </div>
            <div class="col-xs-5 col-sm-3 col-md-2 col-lg-2">
                <label>Fecha Inicio</label>
                <input type="date" id="fechaInicio" class="form-control" max="'.date('Y-m-d').'" value="'.date('Y-m-d').'">
            </div>
            <div class="col-xs-5 col-sm-3 col-md-2 col-lg-2">
                <label>Fecha Fin</label>
                <input type="date" id="fechaFin" class="form-control" max="'.date('Y-m-d').'" value="'.date('Y-m-d').'">
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-1">
                <button class="btn btn-primary" id="btn_search_carg" onclick="search_cargadores()" style="margin-top: 25px;"><span class="glyphicon glyphicon-search"></span></button>
            </div>
            </div>
            <div class="row">
                <!--<div class="col-sm-1"></div>-->
                <div class="col-sm-12" id="div_consulta_body"></div>
            </div><br><br>';
    }else{

    }
    echo $echo;
}elseif($_POST['band']==47){
    $Proveedor = $_POST['proveedor'];
    $UnidadDeNegocio = $_POST['UnidadDeNegocio'];
    $Destino = $_POST['Destino'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
    $txt_where="";
    if($Proveedor!='0'){
        $txt_where.=" AND idProveedor='$Proveedor'";
    }
    if($UnidadDeNegocio!='0'){
        $txt_where.=" AND idUnidadNegocio='$UnidadDeNegocio'";
    }
    if($Destino!='0'){
        $txt_where.=" AND idDestino='$Destino'";
    }
    $echo = '';
    $sql = "SELECT Proveedor,UnidadDeNegocio,Destino,Actividad,SUM(Tiempo) AS Tiempo FROM vtiquetescargadores 
    WHERE idActividad!='00000000-0000-0000-0000-000000000000' AND fechaTiquete BETWEEN '$fechaInicio' AND '$fechaFin' $txt_where
    GROUP BY Proveedor,UnidadDeNegocio,Destino,Actividad
    ORDER BY Proveedor,UnidadDeNegocio,Destino,Actividad";
    $resul=sqlsrv_query($conn,$sql,$params,$options);
    $rows = sqlsrv_num_rows($resul);
    if($rows>0){
        $echo.='<br><br><table class="table table-bordered">
            <thead>
            <th>Proveedor</th>
            <th>Unidad de negocio</th>
            <th>Centro trabajo</th>
            <th>Actividad</th>
            <th>Tiempo</th>
            </thead>
            <tbody>';
        while($aa=sqlsrv_fetch_array($resul)){
            $echo.='<tr>';
            $echo.='<td>'.utf8_encode($aa['Proveedor']).'</td>';
            $echo.='<td>'.utf8_encode($aa['UnidadDeNegocio']).'</td>';
            $echo.='<td>'.utf8_encode($aa['Destino']).'</td>';
            $echo.='<td>'.utf8_encode($aa['Actividad']).'</td>';
            $echo.='<td>'.number_format($aa['Tiempo'],2).'</td>';
            $echo.='</tr>';
        }        
    $echo.='</tbody>';
    }
    echo $echo;
}elseif($_POST['band'] == 2222){ //CARGA LOS INVENTARIOS CONSULTADOS EN EL MODULO INVENTARIOS
    $idEmpresa = $_POST['idEmpresa'];
    $idDestino = $_POST['idDestino'];
    $FechaInicioSaldo = $_POST['FechaInicioSaldo'];
    $FechaFinSaldo = $_POST['FechaFinSaldo'];
    $echo = '<div class="table-responsive1">';

    $sql_group = "SELECT Proceso,PositionProceso,TransaccionDetalle FROM dbo.Get_SaldoInventarioDestino_v2('$idEmpresa','$idDestino','$FechaInicioSaldo','$FechaFinSaldo') 
        GROUP BY Proceso,PositionProceso,TransaccionDetalle ORDER BY PositionProceso,TransaccionDetalle";
    $resul_group=sqlsrv_query($conn,utf8_decode($sql_group),$params,$options);

    $sql = "SELECT Empresa, idEmpresa, Destino, idDestino, Clasificacion, idClasificacion, FechaSaldo, SaldoInicial, Proceso, PositionProceso, iTransacion, TransaccionDetalle, 
    PositionTransaccion, sum(ToneladasProceso) as ToneladasProceso, FechaFinSaldo, 
    SaldoFinal
     FROM dbo.Get_SaldoInventarioDestino_v2('$idEmpresa','$idDestino','$FechaInicioSaldo','$FechaFinSaldo') 
     GROUP BY Empresa, idEmpresa, Destino, idDestino, Clasificacion, idClasificacion, FechaSaldo, SaldoInicial, Proceso, PositionProceso, iTransacion, TransaccionDetalle, 
    PositionTransaccion, FechaFinSaldo, SaldoFinal
     ORDER BY Destino,Clasificacion,PositionProceso,TransaccionDetalle";
    $resul=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
    $rows = sqlsrv_num_rows($resul_group);
    if($rows>0){
        $echo.='<br><table class="table table-bordered">
            <thead>
                <tr>';
        $BAND_CUENTA_SUBNIVEL = 0;
        $const_proceso = '';
        $const_idDestino = '';
        $const_idClasificacion = '';

        $var_position_proceso=0;
        $col_proceso = 0;
        $var_position_transaccion = 0;
        $rowspan_general = 2;
        while($aa = sqlsrv_fetch_array($resul_group)){
            $proceso = utf8_encode($aa['Proceso']);
            $transaccion = utf8_encode($aa['TransaccionDetalle']);

            if($const_proceso == '' || $const_proceso==$proceso){
                $const_proceso = $proceso;
                $array_transacciones[$const_proceso][$var_position_transaccion] = $transaccion;
                $col_proceso++;
                $var_position_transaccion++;
                $BAND_CUENTA_SUBNIVEL++;
            }else{
                $array_proceso[$var_position_proceso] = $const_proceso;
                $var_position_proceso++;
                $col_proceso = 0;
                $var_position_transaccion = 0;
                if($const_proceso <> 'Clasificación' && $const_proceso <> 'Preparación mezcla' && $const_proceso <> 'Coquización'){
                    $BAND_CUENTA_SUBNIVEL++;
                }
                $const_proceso = $proceso;
                $array_transacciones[$const_proceso][$var_position_transaccion] = $transaccion;
                $col_proceso++;
                $var_position_transaccion++;
                $BAND_CUENTA_SUBNIVEL++;
            }
        }
        $array_proceso[$var_position_proceso] = $const_proceso;
        $var_position_proceso++;
        if(isset($array_transacciones)){
            $rowspan_general++;
        }
        if($const_proceso <> 'Clasificación' && $const_proceso <> 'Preparación mezcla' && $const_proceso <> 'Coquización'){
            $BAND_CUENTA_SUBNIVEL++;
        }
        $colspan=$BAND_CUENTA_SUBNIVEL;
        $echo.='<th rowspan="'.$rowspan_general.'" style="vertical-align: middle; text-align: center;">Destino</th>
                <th rowspan="'.$rowspan_general.'" style="vertical-align: middle; text-align: center;">Clasificación</th>
                <th rowspan="'.$rowspan_general.'" style="vertical-align: middle; text-align: center;">Fecha corte inicial</th>
                <th rowspan="'.$rowspan_general.'" style="vertical-align: middle; text-align: center;">Saldo Inicial (TM)</th>';
        if($colspan<>0){
            $echo.='<th colspan="'.$colspan.'" style="background-color: #B4A694; vertical-align: middle; text-align: center;">Procesos</th>';
        }
        $echo.='<th rowspan="'.$rowspan_general.'" style="vertical-align: middle; text-align: center;">Fecha corte final</th>
            <th rowspan="'.$rowspan_general.'" style="vertical-align: middle; text-align: center;">Saldo final (TM)</th>
        </tr><tr>';

        $array_colores_proceso['Entradas'] = '#C7AB32';
        $array_colores_proceso['Salidas'] = '#B3AA86';
        $array_colores_proceso['Preparación mezcla'] = '#E6AC50';
        $array_colores_proceso['Clasificación'] = '#87CEE0';
        $array_colores_proceso['Coquización'] = '#39B3AB';

        for ($i=0; $i < count($array_proceso); $i++){
            if($array_proceso[$i] <> 'Clasificación' && $array_proceso[$i] <> 'Preparación mezcla' && $array_proceso[$i] <> 'Coquización'){
                $colspan_title = count($array_transacciones[$array_proceso[$i]])+1;
            }else{
                $colspan_title = count($array_transacciones[$array_proceso[$i]]);
            }
            $echo.='<th colspan="'.$colspan_title.'" style="background-color: '.$array_colores_proceso[$array_proceso[$i]].'; vertical-align: middle; text-align: center;">'.$array_proceso[$i].'</th>';
        }
        $echo.='</tr>';
        $echo.='<tr>';
        $const_proceso = '';
        for ($i=0; $i < count($array_proceso); $i++){
            for ($j=0; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                if($const_proceso == '' || $const_proceso == $array_proceso[$i]){
                    $const_proceso = $array_proceso[$i];
                    $echo.='<th style="background-color: '.$array_colores_proceso[$const_proceso].'; vertical-align: middle; text-align: center;">'.$array_transacciones[$array_proceso[$i]][$j].'</th>';
                }elseif($const_proceso<>$array_proceso[$i]){
                    if($const_proceso <> 'Clasificación' && $const_proceso <> 'Preparación mezcla' && $const_proceso <> 'Coquización'){
                        $echo.='<th style="background-color: '.$array_colores_proceso[$const_proceso].'; vertical-align: middle; text-align: center;">Total '.$const_proceso.'</th>';
                    }
                    $const_proceso = $array_proceso[$i];
                    $echo.='<th style="background-color: '.$array_colores_proceso[$const_proceso].'; vertical-align: middle; text-align: center;">'.$array_transacciones[$array_proceso[$i]][$j].'</th>';
                }
            }
        }
        if($i==count($array_proceso)){
            if($const_proceso <> 'Clasificación' && $const_proceso <> 'Preparación mezcla' && $const_proceso <> 'Coquización'){
                $echo.='<th style="background-color: '.$array_colores_proceso[$const_proceso].'; vertical-align: middle; text-align: center;">Total '.$const_proceso.'</th>';
            }
        }
        $echo.='</tr>';
        $echo.='</thead>';
        $const_destino = '';
        $const_clasificacion = '';
        $search_position_proceso = 0;
        $search_position_transaccion = 0;
        $const_SaldoFinal = 0;
        $const_proceso = '';
        $const_idDestino = '';
        $const_idClasificacion = '';
        $sum_tm_proceso = 0;
        while($aa = sqlsrv_fetch_array($resul)){
            $idEmpresa = $aa['idEmpresa'];
            $idDestino = $aa['idDestino'];
            $idClasificacion = $aa['idClasificacion'];
            $FechaSaldo = date_format($aa['FechaSaldo'],'Y-m-d');
            $FechaFinSaldo = date_format($aa['FechaFinSaldo'],'Y-m-d');
            $Destino = utf8_encode($aa['Destino']);
            $Clasificacion = utf8_encode($aa['Clasificacion']);
            $SaldoInicial = number_format($aa['SaldoInicial'],2);
            $Proceso = utf8_encode($aa['Proceso']);
            $TransaccionDetalle = utf8_encode($aa['TransaccionDetalle']);
            $SaldoFinal = number_format($aa['SaldoFinal'],2);

            if($const_proceso == '' || $const_proceso==$proceso){
                $const_proceso=$Proceso;
            }
            if($const_destino=''){
                if($const_clasificacion==''){
                    $const_clasificacion = $Clasificacion;
                    $const_idClasificacion = $idClasificacion;
                    $const_SaldoFinal = $SaldoFinal;
                    $echo.='<tr>';
                    $echo.='<td>'.utf8_encode($aa['Destino']).'</td>';
                    $echo.='<td>'.utf8_encode($aa['Clasificacion']).'</td>';
                    $echo.='<td>'.$FechaSaldo.'</td>';
                    $echo.='<td>'.number_format($aa['SaldoInicial'],2).'</td>';

                    for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                        $proceso_foreach = $array_proceso[$i];
                        for ($j=0; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                            $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                            if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                                $a_href_fin = '</a>';
                                $echo.='<td>'.$a_href_ini.number_format($aa['ToneladasProceso'],2).$a_href_fin.'</td>';
                                $search_position_proceso = array_search($Proceso, $array_proceso);
                                $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                                $sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                                break 2;
                            }else{
                                $echo.='<td>0.00</td>';
                            }
                        }
                        if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                            if($sum_tm_proceso>0){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                                $a_href_fin = '</a>';
                            }else{
                                $a_href_ini = '';
                                $a_href_fin = '';
                            }
                            $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                            $sum_tm_proceso = 0;
                        }
                    }
                }elseif($const_clasificacion==$Clasificacion){
                    for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                        $proceso_foreach = $array_proceso[$i];
                        if($search_position_proceso==$i){
                            $search_position_transaccion=$search_position_transaccion+1;
                        }else{
                            $search_position_transaccion=0;
                        }
                        if($search_position_transaccion!=count($array_transacciones[$array_proceso[$i]])){
                            for ($j=$search_position_transaccion; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                                $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                                if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                                    $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                                    $a_href_fin = '</a>';
                                    $echo.='<td>'.$a_href_ini.number_format($aa['ToneladasProceso'],2).$a_href_fin.'</td>';
                                    $search_position_proceso = array_search($Proceso, $array_proceso);
                                    $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                                    $sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                                    break 2;
                                }else{
                                    $echo.='<td>0.00</td>';
                                }
                            }
                        }
                        if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                            if($sum_tm_proceso>0){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                                $a_href_fin = '</a>';
                            }else{
                                $a_href_ini = '';
                                $a_href_fin = '';
                            }
                            $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                            $sum_tm_proceso = 0;
                        }
                    }
                }elseif($const_clasificacion<>$Clasificacion){
                    for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                        $proceso_foreach = $array_proceso[$i];
                        if($search_position_proceso==$i){
                            $search_position_transaccion=$search_position_transaccion+1;
                        }else{
                            $search_position_transaccion=0;
                        }
                        if($search_position_transaccion!=count($array_transacciones[$array_proceso[$i]])){
                            for ($j=$search_position_transaccion; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                                $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                                if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                                    $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                                    $a_href_fin = '</a>';
                                    $echo.='<td>0.00</td>';
                                    $search_position_proceso = array_search($Proceso, $array_proceso);
                                    $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                                    //$sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                                    //break 2;
                                }else{
                                    $echo.='<td>0.00</td>';
                                }
                            }
                        }
                        if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                            if($sum_tm_proceso>0){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                                $a_href_fin = '</a>';
                            }else{
                                $a_href_ini = '';
                                $a_href_fin = '';
                            }
                            $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                            $sum_tm_proceso = 0;
                        }
                    }
                    $echo.='<td>'.$FechaFinSaldo.'</td>';
                    $echo.='<td>'.$const_SaldoFinal.'</td>';
                    $echo.='</tr>';
                    $sum_tm_proceso = 0;
                    /*********************************************/
                    $const_clasificacion = $Clasificacion;
                    $const_idClasificacion = $idClasificacion;
                    $const_SaldoFinal = $SaldoFinal;
                    $search_position_proceso = 0;
                    $search_position_transaccion = 0;
                    $echo.='<tr>';
                    $echo.='<td>'.utf8_encode($aa['Destino']).'</td>';
                    $echo.='<td>'.utf8_encode($aa['Clasificacion']).'</td>';
                    $echo.='<td>'.$FechaSaldo.'</td>';
                    $echo.='<td>'.number_format($aa['SaldoInicial'],2).'</td>';

                    for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                        $proceso_foreach = $array_proceso[$i];
                        for ($j=0; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                            $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                            if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                                $a_href_fin = '</a>';
                                $echo.='<td>'.$a_href_ini.number_format($aa['ToneladasProceso'],2).$a_href_fin.'</td>';
                                $search_position_proceso = array_search($Proceso, $array_proceso);
                                $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                                $sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                                break 2;
                            }else{
                                $echo.='<td>0.00</td>';
                            }
                        }
                        if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                            if($sum_tm_proceso>0){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                                $a_href_fin = '</a>';
                            }else{
                                $a_href_ini = '';
                                $a_href_fin = '';
                            }
                            $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                            $sum_tm_proceso = 0;
                        }
                    }
                }
            }elseif($const_destino=$Destino){
                if($const_clasificacion==''){
                    $const_clasificacion = $Clasificacion;
                    $const_idClasificacion = $idClasificacion;
                    $const_SaldoFinal = $SaldoFinal;
                    $echo.='<tr>';
                    $echo.='<td>'.utf8_encode($aa['Destino']).'</td>';
                    $echo.='<td>'.utf8_encode($aa['Clasificacion']).'</td>';
                    $echo.='<td>'.$FechaSaldo.'</td>';
                    $echo.='<td>'.number_format($aa['SaldoInicial'],2).'</td>';

                    for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                        $proceso_foreach = $array_proceso[$i];
                        for ($j=0; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                            $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                            if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                                $a_href_fin = '</a>';
                                $echo.='<td>'.$a_href_ini.number_format($aa['ToneladasProceso'],2).$a_href_fin.'</td>';
                                $search_position_proceso = array_search($Proceso, $array_proceso);
                                $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                                $sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                                break 2;
                            }else{
                                $echo.='<td>0.00</td>';
                            }
                        }
                        if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                            if($sum_tm_proceso>0){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                                $a_href_fin = '</a>';
                            }else{
                                $a_href_ini = '';
                                $a_href_fin = '';
                            }
                            $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                            $sum_tm_proceso = 0;
                        }
                    }
                }elseif($const_clasificacion==$Clasificacion){
                    for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                        $proceso_foreach = $array_proceso[$i];
                        if($search_position_proceso==$i){
                            $search_position_transaccion=$search_position_transaccion+1;
                        }else{
                            $search_position_transaccion=0;
                        }
                        if($search_position_transaccion!=count($array_transacciones[$array_proceso[$i]])){
                            for ($j=$search_position_transaccion; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                                $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                                if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                                    $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                                    $a_href_fin = '</a>';
                                    $echo.='<td>'.$a_href_ini.number_format($aa['ToneladasProceso'],2).$a_href_fin.'</td>';
                                    $search_position_proceso = array_search($Proceso, $array_proceso);
                                    $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                                    $sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                                    break 2;
                                }else{
                                    $echo.='<td>0.00</td>';
                                }
                            }
                        }
                        if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                            if($sum_tm_proceso>0){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                                $a_href_fin = '</a>';
                            }else{
                                $a_href_ini = '';
                                $a_href_fin = '';
                            }
                            $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                            $sum_tm_proceso = 0;
                        }
                    }
                }elseif($const_clasificacion<>$Clasificacion){
                    for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                        $proceso_foreach = $array_proceso[$i];
                        if($search_position_proceso==$i){
                            $search_position_transaccion=$search_position_transaccion+1;
                        }else{
                            $search_position_transaccion=0;
                        }
                        if($search_position_transaccion!=count($array_transacciones[$array_proceso[$i]])){
                            for ($j=$search_position_transaccion; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                                $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                                if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                                    $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                                    $a_href_fin = '</a>';
                                    $echo.='<td>0.00</td>';
                                    $search_position_proceso = array_search($Proceso, $array_proceso);
                                    $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                                    //$sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                                    //break 2;
                                }else{
                                    $echo.='<td>0.00</td>';
                                }
                            }
                        }
                        if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                            if($sum_tm_proceso>0){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                                $a_href_fin = '</a>';
                            }else{
                                $a_href_ini = '';
                                $a_href_fin = '';
                            }
                            $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                            $sum_tm_proceso = 0;
                        }
                    }
                    $echo.='<td>'.$FechaFinSaldo.'</td>';
                    $echo.='<td>'.$const_SaldoFinal.'</td>';
                    $echo.='</tr>';
                    $sum_tm_proceso = 0;
                    /*********************************************/
                    $const_clasificacion = $Clasificacion;
                    $const_idClasificacion = $idClasificacion;
                    $const_SaldoFinal = $SaldoFinal;
                    $search_position_proceso = 0;
                    $search_position_transaccion = 0;
                    $echo.='<tr>';
                    $echo.='<td>'.utf8_encode($aa['Destino']).'</td>';
                    $echo.='<td>'.utf8_encode($aa['Clasificacion']).'</td>';
                    $echo.='<td>'.$FechaSaldo.'</td>';
                    $echo.='<td>'.number_format($aa['SaldoInicial'],2).'</td>';

                    for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                        $proceso_foreach = $array_proceso[$i];
                        for ($j=0; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                            $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                            if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                                $a_href_fin = '</a>';
                                $echo.='<td>'.$a_href_ini.number_format($aa['ToneladasProceso'],2).$a_href_fin.'</td>';
                                $search_position_proceso = array_search($Proceso, $array_proceso);
                                $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                                $sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                                break 2;
                            }else{
                                $echo.='<td>0.00</td>';
                            }
                        }
                        if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                            if($sum_tm_proceso>0){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                                $a_href_fin = '</a>';
                            }else{
                                $a_href_ini = '';
                                $a_href_fin = '';
                            }
                            $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                            $sum_tm_proceso = 0;
                        }
                    }
                }
            }elseif($const_destino!=$Destino){
                /****************************************************/
                for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                        $proceso_foreach = $array_proceso[$i];
                        if($search_position_proceso==$i){
                            $search_position_transaccion=$search_position_transaccion+1;
                        }else{
                            $search_position_transaccion=0;
                        }
                        if($search_position_transaccion!=count($array_transacciones[$array_proceso[$i]])){
                            for ($j=$search_position_transaccion; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                                $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                                if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                                    $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                                    $a_href_fin = '</a>';
                                    $echo.='<td>0.00</td>';
                                    $search_position_proceso = array_search($Proceso, $array_proceso);
                                    $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                                    //$sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                                    //break 2;
                                }else{
                                    $echo.='<td>0.00</td>';
                                }
                            }
                        }
                        if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                            if($sum_tm_proceso>0){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                                $a_href_fin = '</a>';
                            }else{
                                $a_href_ini = '';
                                $a_href_fin = '';
                            }
                            $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                            $sum_tm_proceso = 0;
                        }
                    }
                    $echo.='<td>'.$FechaFinSaldo.'</td>';
                    $echo.='<td>'.$const_SaldoFinal.'</td>';
                    $echo.='</tr>';
                    $sum_tm_proceso = 0;
                    /*********************************************/
                    $const_clasificacion = $Clasificacion;
                    $const_idClasificacion = $idClasificacion;
                    $const_SaldoFinal = $SaldoFinal;
                    $search_position_proceso = 0;
                    $search_position_transaccion = 0;
                    $echo.='<tr>';
                    $echo.='<td>'.utf8_encode($aa['Destino']).'</td>';
                    $echo.='<td>'.utf8_encode($aa['Clasificacion']).'</td>';
                    $echo.='<td>'.$FechaSaldo.'</td>';
                    $echo.='<td>'.number_format($aa['SaldoInicial'],2).'</td>';

                    for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                        $proceso_foreach = $array_proceso[$i];
                        for ($j=0; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                            $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                            if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                                $a_href_fin = '</a>';
                                $echo.='<td>'.$a_href_ini.number_format($aa['ToneladasProceso'],2).$a_href_fin.'</td>';
                                $search_position_proceso = array_search($Proceso, $array_proceso);
                                $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                                $sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                                break 2;
                            }else{
                                $echo.='<td>0.00</td>';
                            }
                        }
                        if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                            if($sum_tm_proceso>0){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                                $a_href_fin = '</a>';
                            }else{
                                $a_href_ini = '';
                                $a_href_fin = '';
                            }
                            $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                            $sum_tm_proceso = 0;
                        }
                    }
                /****************************************************/
                if($const_clasificacion==''){
                    $const_clasificacion = $Clasificacion;
                    $const_idClasificacion = $idClasificacion;
                    $const_SaldoFinal = $SaldoFinal;
                    $echo.='<tr>';
                    $echo.='<td>'.utf8_encode($aa['Destino']).'</td>';
                    $echo.='<td>'.utf8_encode($aa['Clasificacion']).'</td>';
                    $echo.='<td>'.$FechaSaldo.'</td>';
                    $echo.='<td>'.number_format($aa['SaldoInicial'],2).'</td>';

                    for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                        $proceso_foreach = $array_proceso[$i];
                        for ($j=0; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                            $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                            if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                                $a_href_fin = '</a>';
                                $echo.='<td>'.$a_href_ini.number_format($aa['ToneladasProceso'],2).$a_href_fin.'</td>';
                                $search_position_proceso = array_search($Proceso, $array_proceso);
                                $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                                $sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                                break 2;
                            }else{
                                $echo.='<td>0.00</td>';
                            }
                        }
                        if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                            if($sum_tm_proceso>0){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                                $a_href_fin = '</a>';
                            }else{
                                $a_href_ini = '';
                                $a_href_fin = '';
                            }
                            $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                            $sum_tm_proceso = 0;
                        }
                    }
                }elseif($const_clasificacion==$Clasificacion){
                    for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                        $proceso_foreach = $array_proceso[$i];
                        if($search_position_proceso==$i){
                            $search_position_transaccion=$search_position_transaccion+1;
                        }else{
                            $search_position_transaccion=0;
                        }
                        if($search_position_transaccion!=count($array_transacciones[$array_proceso[$i]])){
                            for ($j=$search_position_transaccion; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                                $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                                if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                                    $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                                    $a_href_fin = '</a>';
                                    $echo.='<td>'.$a_href_ini.number_format($aa['ToneladasProceso'],2).$a_href_fin.'</td>';
                                    $search_position_proceso = array_search($Proceso, $array_proceso);
                                    $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                                    $sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                                    break 2;
                                }else{
                                    $echo.='<td>0.00</td>';
                                }
                            }
                        }
                        if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                            if($sum_tm_proceso>0){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                                $a_href_fin = '</a>';
                            }else{
                                $a_href_ini = '';
                                $a_href_fin = '';
                            }
                            $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                            $sum_tm_proceso = 0;
                        }
                    }
                }elseif($const_clasificacion<>$Clasificacion){
                    for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                        $proceso_foreach = $array_proceso[$i];
                        if($search_position_proceso==$i){
                            $search_position_transaccion=$search_position_transaccion+1;
                        }else{
                            $search_position_transaccion=0;
                        }
                        if($search_position_transaccion!=count($array_transacciones[$array_proceso[$i]])){
                            for ($j=$search_position_transaccion; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                                $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                                if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                                    $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                                    $a_href_fin = '</a>';
                                    $echo.='<td>0.00</td>';
                                    $search_position_proceso = array_search($Proceso, $array_proceso);
                                    $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                                    //$sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                                    //break 2;
                                }else{
                                    $echo.='<td>0.00</td>';
                                }
                            }
                        }
                        if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                            if($sum_tm_proceso>0){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                                $a_href_fin = '</a>';
                            }else{
                                $a_href_ini = '';
                                $a_href_fin = '';
                            }
                            $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                            $sum_tm_proceso = 0;
                        }
                    }
                    $echo.='<td>'.$FechaFinSaldo.'</td>';
                    $echo.='<td>'.$const_SaldoFinal.'</td>';
                    $echo.='</tr>';
                    $sum_tm_proceso = 0;
                    /*********************************************/
                    $const_clasificacion = $Clasificacion;
                    $const_idClasificacion = $idClasificacion;
                    $const_SaldoFinal = $SaldoFinal;
                    $search_position_proceso = 0;
                    $search_position_transaccion = 0;
                    $echo.='<tr>';
                    $echo.='<td>'.utf8_encode($aa['Destino']).'</td>';
                    $echo.='<td>'.utf8_encode($aa['Clasificacion']).'</td>';
                    $echo.='<td>'.$FechaSaldo.'</td>';
                    $echo.='<td>'.number_format($aa['SaldoInicial'],2).'</td>';

                    for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
                        $proceso_foreach = $array_proceso[$i];
                        for ($j=0; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                            $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                            if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                                $a_href_fin = '</a>';
                                $echo.='<td>'.$a_href_ini.number_format($aa['ToneladasProceso'],2).$a_href_fin.'</td>';
                                $search_position_proceso = array_search($Proceso, $array_proceso);
                                $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                                $sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                                break 2;
                            }else{
                                $echo.='<td>0.00</td>';
                            }
                        }
                        if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                            if($sum_tm_proceso>0){
                                $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                                $a_href_fin = '</a>';
                            }else{
                                $a_href_ini = '';
                                $a_href_fin = '';
                            }
                            $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                            $sum_tm_proceso = 0;
                        }
                    }
                }
            }
        }
        for ($i=$search_position_proceso; $i < count($array_proceso); $i++){
            $proceso_foreach = $array_proceso[$i];
            if($search_position_proceso==$i){
                $search_position_transaccion=$search_position_transaccion+1;
            }else{
                $search_position_transaccion=0;
            }
            if($search_position_transaccion!=count($array_transacciones[$array_proceso[$i]])){
                for ($j=$search_position_transaccion; $j < count($array_transacciones[$array_proceso[$i]]); $j++){
                    $transaccion_foreach = $array_transacciones[$array_proceso[$i]][$j];
                    if($Proceso==$proceso_foreach && $TransaccionDetalle==$transaccion_foreach){
                        $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$Proceso.'\',\''.$TransaccionDetalle.'\')">';
                        $a_href_fin = '</a>';
                        $echo.='<td>'.$a_href_ini.number_format($aa['ToneladasProceso'],2).$a_href_fin.'</td>';
                        $search_position_proceso = array_search($Proceso, $array_proceso);
                        $search_position_transaccion = array_search($TransaccionDetalle, $array_transacciones[$array_proceso[$i]]);
                        $sum_tm_proceso = $sum_tm_proceso + $aa['ToneladasProceso'];
                        break 2;
                    }else{
                        $echo.='<td>0.00</td>';
                    }
                }
            }
            if($proceso_foreach <> 'Clasificación' && $proceso_foreach <> 'Preparación mezcla' && $proceso_foreach <> 'Coquización'){
                if($sum_tm_proceso>0){
                    $a_href_ini = '<a href="javascript:load_details_inventory(\''.$idEmpresa.'\',\''.$idDestino.'\',\''.$const_idClasificacion.'\',\''.$FechaSaldo.'\',\''.$FechaFinSaldo.'\',\''.$proceso_foreach.'\',\'N/A\')">';
                    $a_href_fin = '</a>';
                }else{
                    $a_href_ini = '';
                    $a_href_fin = '';
                }
                $echo.='<td>'.$a_href_ini.number_format($sum_tm_proceso,2).$a_href_fin.'</td>';
                $sum_tm_proceso = 0;
            }
        }
        $echo.='<td>'.$FechaFinSaldo.'</td>';
        $echo.='<td>'.$const_SaldoFinal.'</td>';
        $echo.='</tr>';
    }
    $echo.='</div>';
    echo $echo;
}
?>