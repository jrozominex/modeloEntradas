<?php 
require_once '../modelo/conexion.php'; 
$sql_empresa = "SELECT idProveedor, Alias from Proveedores where Empresa=1 ORDER BY Alias";
$resul_empresa = sqlsrv_query($conn,$sql_empresa);

$sql_recibo= "SELECT isnull(max(num_recibo),0) as numero from servicio_clasificacion";
$resul_recibo = sqlsrv_query($conn,$sql_recibo);
while($rows = sqlsrv_fetch_array($resul_recibo)) 
{   $numero=$rows['numero']+1; }

$slq_proveedores="SELECT Proveedores.RazonSocial, Proveedores.idProveedor from Proveedores inner join ProveedoresGrupos on Proveedores.idProveedor= ProveedoresGrupos.idProveedor
    inner join Agrupaciones on ProveedoresGrupos.idAgrupacion = Agrupaciones.idAgrupacion where Agrupaciones.Alias='SC' order by Proveedores.RazonSocial";
$resul_proveedores = sqlsrv_query($conn,$slq_proveedores);                    
$sql_equipos="SELECT idGrupo, Descripcion from EquiposGrupos ORDER BY Descripcion";
$resul_equi_grupo = sqlsrv_query($conn,$sql_equipos);

$sql_clasificacion="SELECT * FROM Materiales ORDER BY Descripcion";
$resul_clasificacion = sqlsrv_query($conn,$sql_clasificacion);

$sql_actividad="SELECT idActividad, Descripcion from Actividades where idTipoActividad='00000000-0000-0000-0000-000000000007'";
$resul_actividad = sqlsrv_query($conn,$sql_actividad);

$sql_destino="SELECT * from vdestinogrupos";
$resul_destino = sqlsrv_query($conn,$sql_destino);
?>
<script type="text/javascript">
     function descargar_informe(frm) {
            document.getElementById('form1').target ="_blank";
            document.getElementById('form1').method ="POST";
            document.getElementById('form1').action ="buscar.php";
            document.getElementById('form1').submit();
        }
    
</script>
<div class="container">
<div class="row">
    <br>
    <div class="col-sm-12" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; margin-bottom: 8px;">
        <center>
            <label><h5><b>Consultas</b></h5></label>
        </center>
        <div class="row">
        <form name="form1" id="form1">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-2"><h6>Fecha desde: </h6></div>
                    <div class="col-sm-4"><input type="date" id="fecha_1" name="fecha_1" class="form-control input-sm" max="<?php echo date('Y-m-d'); ?>"></div>
                    <div class="col-sm-2"><h6>Fecha hasta: </h6></div>
                    <div class="col-sm-4"><input type="date" id="fecha_2" name="fecha_2" class="form-control  input-sm" max="<?php echo date('Y-m-d'); ?>"></div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <div class="col-sm-3"><h6>Empresa: </h6></div>
                    <div class="col-sm-8">
                         <input type="hidden" name="input_empresa" id="input_empresa_1" value="">
                        <select id="empresa_1" name="empresa_1" class="form-control" onchange="activar_botton();">
                            <option value="0">Seleccione</option>
                            <?php  
                            while($rows = sqlsrv_fetch_array($resul_empresa)){
                                $id_empresa=$rows['idProveedor'];
                                $nom_empresa=$rows['Alias'];
                                ?><option value="<?php echo $id_empresa; ?>"><?php echo $nom_empresa ?></option>
                            <?php }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <div class="col-sm-3"><h6>Proveedor: </h6></div>
                    <div class="col-sm-8">
                        <select id="proveedor_1" name="proveedor_1" class="form-control">
                            <option value="0">Todos</option>
                            <?php  
                            while($rows = sqlsrv_fetch_array($resul_proveedores)){
                                $idProveedor=$rows['idProveedor'];
                                $nom_proveedor=utf8_encode($rows['RazonSocial']);
                                ?><option value="<?php echo $idProveedor; ?>"><?php echo $nom_proveedor ?></option>
                            <?php }
                            ?>
                        </select> 
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <div class="col-sm-3"><h6>Patio: </h6></div>
                    <div class="col-sm-8">
                        <select id="patio_1" name="patio_1" class="form-control">
                            <option value="0">Todos</option>
                            <?php  
                            while($rows = sqlsrv_fetch_array($resul_destino)){   
                                $iddestino=$rows['iddestino'];
                                $nom_destino=utf8_encode($rows['Destino']);
                                ?><option value="<?php echo $iddestino; ?>"><?php echo $nom_destino ?></option>
                            <?php }
                            ?>
                        </select> 
                    </div>
                </div>
                <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                    <div class="col-sm-3"><h6>Activdad: </h6></div>
                     <div class="col-sm-8">
                        <select id="actividad_1" name="actividad_1" class="form-control">
                            <option value="0">Todos</option>
                        <?php  
                        while($rows = sqlsrv_fetch_array($resul_actividad)){   
                            $idActividad=$rows['idActividad'];
                            $nom_actividad=utf8_encode($rows['Descripcion']);
                            ?><option value="<?php echo $idActividad; ?>"><?php echo $nom_actividad ?></option>
                        <?php }
                        ?>
                        </select> 
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-3"><h6>Clase Equipo: </h6></div>
                    <div class="col-sm-8">
                        <input type="hidden" name="input_tipo_maquina_1" id="input_tipo_maquina_1" value="<?php if(isset($_POST['tipo_maquinaria'])){if($_POST['tipo_maquinaria'] != '0'){  echo $_POST['tipo_maquinaria'];   }} ?>">
                        <select id="tipo_maquinaria_1" name="tipo_maquinaria_1" class="form-control" onchange=" llenar_sel_equipo_consulta();">
                            <option value="0">Todos</option>
                            <?php  
                            while($rows = sqlsrv_fetch_array($resul_equi_grupo)){
                               $idGrupo=$rows['idGrupo'];
                                $nom_grupo=utf8_encode($rows['Descripcion']);
                                ?><option value="<?php echo $idGrupo; ?>"><?php echo $nom_grupo ?></option>
                            <?php }
                            ?>
                        </select> 
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
                            <option value="0">Todos</option>
                            <?php  
                            while($rows = sqlsrv_fetch_array($resul_clasificacion)){
                                $idClasificacion1=$rows['idMaterial'];
                                $nom_clasiif1=utf8_encode($rows['Descripcion']);
                                ?><option value="<?php echo $idClasificacion1; ?>"><?php echo $nom_clasiif1 ?></option>
                            <?php }
                            ?>
                        </select> 
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
                        <button type="button" class="btn btn-primary" id="descargar" disabled onclick="descargar_informe()"> EXCEL</button>
                        <input type="hidden" name="band" id="band" value="100">                       
                    </div>
                </div>           
            </div>
        </form>
        </div>
    </div><br>
</div>
</div>
<div id="div_resultados"></div>
