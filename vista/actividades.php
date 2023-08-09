<?php
if(!isset($_SESSION["logueado"])){
    session_start();
}
require_once '../modelo/conexion.php';
//include('../modelo/security.php');
include ("../../clase_encrip.php");
//error_reporting(0);
//if(!isset($_SESSION['Array_empresa']['ADMINISTRACION'])&& !isset($_SESSION['PermisoDocus']['ActividadesDestino'])){
if(!isset($_SESSION['PermisoDocus']['ActividadesDestino'])){
?>
    <script type="text/javascript">
        self.location='Admin.php';
        alert('No tienes permiso para acceder a este ambiente');
    </script>
    <?php
}?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <?php include './libreria.php'; ?>
    <meta name="viewport" content="width=auto, initial-scale=0.8">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript" src="../controlador/actividad.js"></script>
</head>
<body>
    <?php include 'Header.php'; ?>
    <div class="container-fluid">
        <!--<center>
            <h2 id="titulo"></h2>
        </center>-->
        <a href="<?php if($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA'){   echo 'Admin.php'; }?>">
            <button class="btn btn-default navbar-right" style="margin-right: 7px; margin-bottom: 5px;">
                <span class="glyphicon glyphicon-home"></span>
            </button>
        </a>
        <div class="row" id="div">
            <div class="col-sm-12 col-lg-2 col-xs-12 col-2" id="div_submenu">
                <nav class="navbar navbar-default" role="navigation" id="navbar_menu1">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse1">
                            <span class="sr-only">Desplegar navegación</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand">Administración</a>
                    </div>
                    <div class="collapse navbar-collapse navbar-ex1-collapse1" id="navbar_submenu">
                        <form id="formButton" name="formButton">
                            <ul class="nav navbar-nav">
                                <?php 
                                $sql = "SELECT * FROM dbo.TipoActividad()";
                                $res = sqlsrv_query($conn,$sql);
                                while($act = sqlsrv_fetch_array($res)){
                                    if ($_SESSION["logueado"] == TRUE && ($_SESSION["permisoIngresar"] == 'PATIO_CARGADORES')){   
                                        if($act['Descripcion'] == 'Operativo'){
                                            ?>

                                <li style="margin-bottom: 5px;"><button type="button" class="btn btn-default" id="<?php echo $act['Descripcion']; ?>" onclick="TablaActividad('<?php echo $act['Descripcion']; ?>')" value="<?php echo $act['Descripcion']; ?>"><?php echo $act['Descripcion']; ?></button></li><br>
                                <?php
                                        }
                                    }else{
                                ?>
                                <li style="margin-bottom: 5px;"><button type="button" class="btn btn-default" id="<?php echo $act['Descripcion']; ?>" onclick="TablaActividad('<?php echo $act['Descripcion']; ?>')" value="<?php echo $act['Descripcion']; ?>"><?php echo $act['Descripcion']; ?></button></li><br>
                                <?php
                                    }
                                }?>


                                <?php if (1==0){  ?>

                                <li style="margin-bottom: 5px;">
                                    <button type="button" class="btn btn-default" id="CategoriaMaquinaria" onclick="TablaActividad('CategoriaMaquinaria')" value="CategoriaMaquinaria">
                                        Categoria Maquinaria
                                    </button>
                                </li><br>

                                <?php } ?>


                                <li style="margin-bottom: 5px;">
                                    <button type="button" class="btn btn-default" id="Pilas" onclick="TablaActividad('Pilas')" value="Pilas">
                                        Pilas
                                    </button>
                                </li><br>
                                <li style="margin-bottom: 5px;">
                                    <button type="button" class="btn btn-default" id="Rendimientos" onclick="TablaActividad('Rendimientos')" value="Rendimientos">
                                        Rendimientos
                                    </button>
                                </li><br>
                                <li style="margin-bottom: 5px;">
                                    <button type="button" class="btn btn-default" id="Recetas_produccion" onclick="TablaActividad('Recetas_produccion')" value="Recetas_produccion">
                                        Recetas produccion
                                    </button>
                                </li><br>
                                <li style="margin-bottom: 5px;">
                                    <button type="button" class="btn btn-default" id="ActividadDestinos" onclick="TablaActividad('ActividadDestinos')" value="ActividadDestinos">
                                        Actividades Destinos
                                    </button>
                                </li><br>
                                <li style="margin-bottom: 5px;">
                                    <button type="button" class="btn btn-default" id="codigos_destino" onclick="TablaActividad('codigos_destino')" value="codigos_destino">
                                        Códigos Destino
                                    </button>
                                </li><br>
                                <li style="margin-bottom: 5px;">
                                    <button type="button" class="btn btn-default" id="clasificacion_jerarquia" onclick="TablaActividad('clasificacion_jerarquia')" value="clasificacion_jerarquia">
                                        Jerarquia Clasificacion
                                    </button>
                                </li><br>
                                <li style="margin-bottom: 5px;">
                                    <button type="button" class="btn btn-default" id="clasificacion_crear" onclick="TablaActividad('clasificacion_crear')" value="clasificacion_crear">
                                        Crear Clasificacion
                                    </button>
                                </li><br>
                            </ul>
                        </form>
                    </div>
                </nav>
            </div>
            <div class="col-sm-10 table-responsive" id="div_tablas"></div>
        </div>
    </div>
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <div class="modal fade" id="modalInsertarCategoria" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Registrar Categoria de Maquinaria</h4>
                </div>
                <div class="modal-body">
                    <div class="row form-group center-block">
                        <div class="col-sm-5">
                            <label>Descripción</label>
                            <input type="" id="descrip_categoria" class="form-control">
                        </div>
                        <div class="col-sm-5">
                            <label>Agrupación proveedor</label>
                            <select class="form-control" id="list_agrupacion">
                                <option value="0">Seleccione</option>
                                <?php 
                                $sql = "SELECT * FROM Agrupaciones ORDER BY Descripcion";
                                $res = sqlsrv_query($conn,$sql);
                                while($agrup = sqlsrv_fetch_array($res)){
                                    ?>
                                    <option value="<?php echo $agrup['idAgrupacion']; ?>"><?php echo utf8_encode($agrup['Descripcion']); ?></option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal" onclick="agregar_categoria()">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <div class="modal fade" id="modalInsertarPila" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Registrar Pila</h4>
                </div>
                <div class="modal-body">
                    <div class="row form-group center-block">
                        <div class="col-sm-2">
                            <label>Descripción:</label>
                        </div>
                        <div class="col-sm-5">
                            <input type="" id="descrip_pila" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal" onclick="agregar_pila()">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <div class="modal fade" id="modalInsertarPilaRecetas" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="titlePilaRecetas">Registrar Pila</h4>
                </div>
                <div id="bodyPilaRecetas"></div>
            </div>
        </div>
    </div>
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <div class="modal fade" id="modalInsertarRendimiento" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Registrar Rendimiento</h4>
                </div>
                <div class="modal-body">
                    <div class="row form-group center-block">
                        <div class="col-sm-3">
                            <label>Propietario Cargador</label>
                            <select class="form-control" id="propietario" onchange="llenar_select()">
                                <option>--- Seleccione ---</option>
                                <?php 
                                $sql = "SELECT * FROM Proveedores WHERE idProveedor in (SELECT idProveedor FROM  vProveedoresInAgrupacion WHERE Alias='PC') ORDER BY RazonSocial";
                                $res = sqlsrv_query($conn,$sql);
                                while($propietario = sqlsrv_fetch_array($res)){
                                    ?>
                                    <option value="<?php echo $propietario['idProveedor']; ?>">
                                        <?php echo utf8_encode($propietario['RazonSocial']); ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Equipo:</label>
                            <select class="form-control" id="Equipo">
                                <option value="">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Clasifiación:</label>
                            <select class="form-control" id="Clasificacion">
                                <option value="">Seleccione</option>
                                <?php 
                                $sql = "SELECT DISTINCT(Clasificacion),Clasificacion.idClasificacion FROM tz_MovimientoTransporte  
                                        INNER JOIN Clasificacion ON tz_MovimientoTransporte.Clasificacion=Clasificacion.Descripcion
                                        WHERE year(FechaRegistro)>='$Year' ORDER BY Clasificacion";
                                $res = sqlsrv_query($conn,$sql);
                                while($clasificacion = sqlsrv_fetch_array($res)){
                                    ?>
                                    <option value="<?php echo $clasificacion['idClasificacion']; ?>"><?php echo utf8_encode($clasificacion['Clasificacion']); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Tara:</label>
                            <input type="number" style="text-align: right;" class="form-control" id="Tara">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal" onclick="agregar_rendimiento()">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <div class="modal fade" id="modalInsertar" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body">
                    <div class="row form-group center-block">
                        <div class="col-sm-4">
                            <input type="hidden" id="idActividad1">
                            <label>Actividad</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" id="decide">
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <center><textarea class="form-control" rows="4" id="actividad"></textarea></center>
                    </div>
                    <div class="row form-group center-block" style="background-color: powderblue;" id="title"><center><h4>Tipo Actividad</h4></center></div>
                    <div class="row">
                        <input type="hidden" id="TipoAct">
                        <form id="formTipo" name="formTipo">   
                        <?php 
                        $sql = "SELECT * FROM dbo.TipoActividad()";
                        $res = sqlsrv_query($conn,$sql);
                        while($act = sqlsrv_fetch_array($res)){
                            ?>
                            <div class="col-sm-3">
                                <button type="button" style="margin-bottom: 10px;" type="button" class="btn btn-default" id="<?php echo $act['idTipoActividad']; ?>" onclick="TipoAct('<?php echo $act['idTipoActividad']; ?>')" value="<?php echo $act['idTipoActividad']; ?>">
                                    <?php echo $act['Descripcion']; ?>
                                </button>
                            </div>
                            <?php
                        }
                        ?>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" id="AgregarActividad" onclick="decide()">
                        <b id="TituloBoton">Agregar<b>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <div class="modal fade" id="modalSub_actividad" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel1"></h4>
                </div>
                <div class="modal-body">
                    <div class="row form-group center-block">
                        <div class="col-sm-6">
                            <label>Actividad</label>
                            <input type="hidden" id="idActividad">
                            <input type="text" id="actividad1" class="form-control" readonly="">
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-4">
                            <input type="hidden" id="idSubactividad">
                            <label>SubActividad</label>
                        </div>
                        <div class="col-sm-4"></div>
                    </div>
                    <div class="row form-group center-block">
                        <center><textarea class="form-control" rows="4" id="subactividad"></textarea></center>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" id="AgregarSubActividad" onclick="decide1()">
                        Agregar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <div class="modal fade" id="modalInsertarActividadDestinos" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Actividades Destino</h4>
                </div>
                <div class="modal-body">
                    <div class="row form-group center-block">
                        <div class="col-sm-4">
                            <label>Destino</label>
                            <select class="form-control" id="idDestinoActividad" onchange="search_destinoActividad()">
                                <option value="0">Seleccione</option>
                                <?php 
                                $sql = "SELECT * FROM Destino ORDER BY Descripcion";
                                $res = sqlsrv_query($conn,$sql);
                                while($aa = sqlsrv_fetch_array($res)){
                                ?><option value="<?php echo $aa['idDestino']; ?>"><?php echo utf8_encode($aa['Descripcion']); ?></option><?php
                                }?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label>Actividad</label>
                            <select class="form-control" id="idActividadDestinos"></select>
                        </div>
                        <div class="col-sm-2">
                            <label>Interfaz Cargadores</label>
                            <input type="checkbox" id="checkbox_cargadores">
                        </div>
                        <div class="col-sm-2">
                            <label>Interfaz Producción</label>
                            <input type="checkbox" id="checkbox_produccion">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="AgregarActividadDestinos" onclick="guardar_ActividadDestinos()">
                        Agregar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <div class="modal fade" id="modalInsertarRecetasProduccion" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Receta mezclas</h4>
                </div>
                <div class="modal-body">
                    <div class="row form-group center-block">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <label>Nombre Receta</label>
                            <input type="text" id="nombre_receta_produccion" class="form-control" readonly="">
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-3">
                            <label>Empresa</label>
                            <select class="form-control" id="empresa_produccion" onchange="calculate_name_receta()">
                                <option value="0" selected="" disabled="">Seleccione</option>
                                <?php 
                                $sql = "SELECT * FROM Proveedores WHERE Empresa=1 ORDER BY NombreCorto";
                                $res = sqlsrv_query($conn,$sql);
                                while($aa = sqlsrv_fetch_array($res)){
                                ?><option value="<?php echo $aa['idProveedor']; ?>"><?php echo utf8_encode($aa['NombreCorto']); ?></option><?php
                                }?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Patio Preparación</label>
                            <select class="form-control" id="patio_produccion" onchange="calculate_name_receta()">
                                <option value="0" selected="" disabled="">Seleccione</option>
                                <?php 
                                $sql = "SELECT Destino,idDestino FROM vActividades_cargadores_destinos WHERE idActividad='DFE61B95-C48C-46A9-8695-8B1A4720E4A1' AND Produccion=1 ORDER BY Destino";
                                $res = sqlsrv_query($conn,$sql);
                                while($aa = sqlsrv_fetch_array($res)){
                                ?><option value="<?php echo $aa['idDestino']; ?>"><?php echo utf8_encode($aa['Destino']); ?></option><?php
                                }?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Mezcla producida</label>
                            <select class="form-control" id="mezcla_producida">
                                <option value="0" disabled="" selected="">Seleccione</option>
                                <?php 
                                $sql = "SELECT * FROM Clasificacion WHERE Grupo='Mezclas' ORDER BY Descripcion";
                                $res = sqlsrv_query($conn,$sql);
                                while($aa = sqlsrv_fetch_array($res)){
                                ?><option value="<?php echo $aa['idClasificacion']; ?>"><?php echo utf8_encode($aa['Descripcion']); ?></option><?php
                                }?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Pila</label>
                            <input type="text" id="pila_receta" list="list_pila_receta" class="form-control" onkeyup="search_pila_receta(this)">
                            <datalist id="list_pila_receta"></datalist>
                        </div>
                    </div>
                    <div class="row form-group center-block" style="background-color: powderblue;"><p></p></div>
                    <input type="hidden" id="text_clasif_recetas">
                    <input type="hidden" id="text_porcentaje_clasif_recetas">
                    <div class="row form-group center-block" id="div_clasif_recetas">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="AgregarRecetasProduccion" onclick="agregar_RecetasProduccion(0)">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
     <div class="modal fade" id="modalInsertarCodigosEmpresa" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row form-group center-block">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-5">
                            <label>Empresa</label>
                            <select class="form-control" id="select_codigos_empresa">
                                <option value="0" selected="" disabled="">Seleccione</option>
                                <?php 
                                $sql = "SELECT * FROM Proveedores WHERE Empresa=1 and idProveedor NOT IN (SELECT idEmpresa FROM Empresa_codigos) ORDER BY RazonSocial";
                                $res = sqlsrv_query($conn,$sql);
                                while($aa = sqlsrv_fetch_array($res)){
                                ?><option value="<?php echo ENCR::encript($aa['idProveedor']); ?>"><?php echo utf8_encode($aa['RazonSocial']); ?></option><?php
                                }?>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <label>Código</label>
                            <?php 
                                $sql = "SELECT MAX(codigo_empresa) as codigo from empresa_codigos";
                                $res = sqlsrv_query($conn,$sql);
                                while($aa = sqlsrv_fetch_array($res)){ 
                                   $codigo= $aa['codigo'];
                                   $codigo++;?>
                                    <input type="text" id="empresa" class="form-control" value="<?php echo $codigo; ?>" disabled>
                            <?php  }    ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" id="AgregarCodigoEmpresa" onclick="agregar_codigoDestino(0)">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalInsertarCodigosDestino" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row form-group center-block">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-5">
                            <label>Destino</label>
                            <select class="form-control" id="select_codigos_destino">
                                <option value="0" selected="" disabled="">Seleccione</option>
                                <?php 
                                $sql = "SELECT * FROM Destino WHERE idDestino NOT IN (SELECT idDestino FROM Destino_codigos) ORDER BY Descripcion";
                                $res = sqlsrv_query($conn,$sql);
                                while($aa = sqlsrv_fetch_array($res)){
                                ?><option value="<?php echo ENCR::encript($aa['idDestino']); ?>"><?php echo utf8_encode($aa['Descripcion']); ?></option><?php
                                }?>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <label>Código</label>
                            <?php 
                                $sql = "SELECT MAX(codigo_destino)+1 as codigo from Destino_codigos";
                                $res = sqlsrv_query($conn,$sql);
                                while($aa = sqlsrv_fetch_array($res)){ ?>
                                    <input type="text" id="codigo" class="form-control" value="<?php echo $aa['codigo']; ?>" disabled>
                            <?php  }    ?>
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" id="AgregarCodigoDestino" onclick="agregar_codigoDestino(1)">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalInsertarDestinoClasificacion" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Destino Clasificación</h4>
                </div>
                <div class="modal-body">
                    <div class="row form-group center-block">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-5">
                            <label>Destino</label><br>
                            <select class="form-control" id="select_destino_clasificacion">
                                <option value="0" selected="" disabled="">Seleccione</option>
                                <option>asd</option>
                                <?php 
                                $sql12 = "SELECT RecepcionadoEn, idDestino FROM tz_MovimientoTransporte 
                                        WHERE TipoMovimiento='Recepción' AND YEAR(FechaRegistro)>=2020 AND 
                                            /*idEmpresa='24B7153E-AB4C-4DB7-81BD-67BC87AF014C' AND*/
                                            idDestino NOT IN (SELECT idDestino FROM Destinogrupos)
                                        GROUP BY RecepcionadoEn, idDestino ORDER BY RecepcionadoEn";
                                $res12 = sqlsrv_query($conn,$sql12);
                                while($aa12 = sqlsrv_fetch_array($res12)){
                                ?><option value="<?php echo $aa12['idDestino']; ?>"><?php echo utf8_encode($aa12['RecepcionadoEn']); ?></option><?php
                                }?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" id="AgregarDestinoClasificacion" onclick="agregar_destinoClasificacion()">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function prueba(r){
            alert(r);
        }
        function datatable(){
            var table = $('#example1').DataTable( {
                "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                "ordering": true,
                "info":     true,
                stateSave: true,/*
                scrollY:        '50vh',
                //"scrollX": true,
                "scrollCollapse": true,
                "paging":         true*/
            } );
        }
        $(document).ready(function () {
            datatable();
            /*$('#example1 tbody').on('click', 'td', function () {
                var data = table.cell( this ).data();
                alert( 'LA TARA ES :  '+data);
            } );*/

            //document.getElementById('titulo').innerHTML = "Administración";
            $('#operativo').hide();
            $('#Mtto_eventual').hide();
            $('#Mtto_general').hide();
            $('#Mtto_lavado').hide();
            $('#Mtto_electrico').hide();
            $('#Reporte_fallas').hide();
            $('#Div_categorias').hide();
            $('#Div_pilas').hide();
            $('#Div_rendimientos').hide();
            $('#Div_ActividadDestinos').hide();
        });
        function llenar_select(){
            var propietario = $('#propietario').val();
            var band = 1;
            $.post("../modelo/buscar.php", {propietario: propietario, band: band}, 
            function(mensaje) {
                //console.log(mensaje);
                $('#Equipo').html(mensaje).fadeIn();
                $('#select_cargador').show();
            });
        }
    </script>
</body>
</html>