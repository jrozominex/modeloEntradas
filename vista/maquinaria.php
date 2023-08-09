<?php
if(!isset($_SESSION["logueado"])){
    session_start();
}
require_once '../modelo/conexion.php';
include('../modelo/security.php');
//error_reporting(0);
if(!isset($_SESSION['Array_empresa']['MAQUINARIA'])){
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <meta name="viewport" content="width=auto, initial-scale=0.8">
    <script type="text/javascript" src="../controlador/maquinaria.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            load_table_equipos()
        });
    </script>
</head>
<body>
    <?php include 'Header.php'; ?>
    <div class="container-fluid">
        <center>
            <h2>Maquinaria / Equipos</h2>
        </center>
        <div class="row" id="divMantto">
            <button class="btn btn-success navbar-right" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertar">
                Registrar maquinaria <span class="glyphicon glyphicon-plus"></span>
            </button>
            <br><br>
        </div>
        <div class="row">
            <div class="col-sm-12 table-responsive">
                <input type="hidden" id="vista">
                <table id="example1" class="table table-hover table-condensed table-bordered table-responsive table-striped">
                    <thead>
                        <th rowspan="2" style="margin-top: 10px; vertical-align: middle; text-align: center;">Propietario</th>
                        <th rowspan="2"  style="margin-top: 10px; vertical-align: middle; text-align: center;">Tipo Maquinaria</th>
                        <th rowspan="2"  style="margin-top: 10px; vertical-align: middle; text-align: center;">Nombre Maquinaria</th>
                        <th rowspan="2"  style="margin-top: 10px; vertical-align: middle; text-align: center;">Activities</th>
                        <th rowspan="2"  style="margin-top: 10px; vertical-align: middle; text-align: center;">CV</th>
                        <th rowspan="2"  style="margin-top: 10px; vertical-align: middle; text-align: center;">Vincular <br>Maq. / Equipo</th>
                        <th colspan="3" style="width: 18%;"><center>Horometro</center>
                            <tr>
                                <th>Final</th>
                                <th>Vida</th>
                                <th>Mantto</th>
                            </tr>
                        </th>
                    </thead>
                    <tbody id="tbody_equipos"></tbody>
                </table>
                <input type="hidden" id="num" value="<?php echo $num; ?>">
            </div>
        </div>
    </div>
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <div class="modal fade" id="ModalMantto1" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Horometro Mantenimiento programado</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="equipos">
                    <div class="row form-group center-block">
                        <div class="col-sm-12">
                            <label>Horometro Faltante para el proximo mantenimiento programado</label>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <div class="col-sm-6">
                            <input type="text" id="horo_mantto" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="registro_horo_mantto()">Registrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL PARA INGRESAR DE DATOS DEL HOROMETRO-->
    <div class="modal fade" id="modalInsertar" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Registrar maquinaria</h4>
                </div>
                <div class="modal-body">
                    <div class="row form-group center-block">
                        <div class="col-sm-4">
                            <label>Tipo maquinaria</label>
                            <select class="form-control" id="grupo" onchange="load_propietarios()">
                                <option value="0">--- Seleccione ---</option>
                                <?php
                                $sql = "SELECT * FROM EquiposGrupos ORDER BY Descripcion";
                                $res = sqlsrv_query($conn,$sql);
                                while($grupo = sqlsrv_fetch_array($res)){
                                ?>
                                    <option value="<?php echo $grupo['idGrupo']; ?>"><?php echo $grupo['Descripcion']; ?></option>
                                <?php
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <label>Propietario</label>
                            <select class="form-control" id="propietario">
                                <option value="0" selected="" disabled="">--- Seleccione ---</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group center-block" style="background-color: powderblue;"><p></p></div>
                    <div class="row form-group center-block">
                        <div class="col-sm-4">
                            <label>Nombre Equipo</label>
                            <input type="text" id="marca" class="form-control" placeholder="Ej: CAT..">
                        </div>
                        <div class="col-sm-4">
                            <label>Identificador</label>
                            <input type="text" id="id" class="form-control" placeholder="Ej: C06..">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" id="AgregarMaquinaria">
                        Agregar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalConfirmar" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirmar identidad</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="horometro">
                    <div class="row form-group center-block">
                        <div class="col-sm-12">
                            <label>Digite su contraseña:</label>
                            <input  type="password" id="contrasena" class=" form-control">
                            <input type="hidden" id="clave" value="<?php echo $password; ?>">
                            <input type="hidden" id="registro">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="confirmar">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalHoroFinal" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Horometro de la maquina</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="horometro">
                    <div class="row form-group center-block">
                        <div class="col-sm-12">
                            <label>Digite el horometro</label>
                            <input  type="text" id="contrasena1" class=" form-control">
                            <input type="hidden" id="registro1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="confirmarHoro">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalActivities" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Actividades de Maquinaria</h4>
                    <input type="hidden" id="idEquipo_actividad">
                </div>
                <div class="modal-body">
                    <div id="body_activities"></div>
                    <div class="row form-group center-block" style="background-color: powderblue;"><p></p></div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Actividad</label>
                            <select class="form-control" id="actividad_maquinas">
                                <option value="0">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-12"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="add_activitie_machine()">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalGrupoMaquinaria" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Agrupación de Maquinaria</h4>
                </div>
                <div class="modal-body">
                    <div id="bodyEquipo_agrupacion"></div>
                    <div class="row form-group center-block" style="background-color: powderblue;"><p></p></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Clase Equipo</label>
                            <select class="form-control" id="listGrupo_agrupacion" onchange="load_listEquipo()">
                                <option value="0" selected disabled>--- Seleccione ---</option>
                                <?php
                                $sql = "SELECT * FROM EquiposGrupos ORDER BY Descripcion";
                                $res = sqlsrv_query($conn,$sql);
                                while($grupo = sqlsrv_fetch_array($res)){
                                ?>
                                    <option value="<?php echo $grupo['idGrupo']; ?>"><?php echo $grupo['Descripcion']; ?></option>
                                <?php
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label>Equipo</label>
                            <select class="form-control" id="listEquipo_vinculado">
                                <option value="0">Seleccione una clase</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="add_group_machine()">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
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
        $('#AgregarMaquinaria').click(function (){
            AgregarMaquinaria();
        });
        $('#confirmar').click(function (){
            if ($('#clave').val() != $('#contrasena').val()){
                alert('La contraseña es incorrecta');
            }else{
                registro = $('#registro').val();
                contrasena = $('#contrasena').val();
                clave = $('#clave').val();
                band = 2;
                cadena = "registro=" + registro +
                        "&contrasena=" + contrasena +
                        "&band=" + band;
                $.ajax({
                    type: "POST",
                    url: "../modelo/maquinaria_insertar.php",
                    data: cadena,
                    success: function (r) {
                        //alert(r);
                        if (r == 1) {
                            alert("Se reestableció el horometro correctamente");
                            self.location = "maquinaria.php";
                        }else{
                            alertify.error("No se pudo reestablecer");
                        }
                    }
                });
            }
        });
        $('#confirmarHoro').click(function (){
            if ($('#contrasena1').val() == ""){
                alert('Ingrese un horometro');
            }else{
                registro = $('#registro1').val();
                contrasena = $('#contrasena1').val();
                band = 3;
                cadena = "registro=" + registro +
                        "&contrasena=" + contrasena +
                        "&band=" + band;
                $.ajax({
                    type: "POST",
                    url: "../modelo/maquinaria_insertar.php",
                    data: cadena,
                    success: function (r) {
                        if (r == 1) {
                            alert("Se registró el horometro correctamente");
                            self.location = "maquinaria.php";
                        }else{
                            alertify.error("No se pudo registrar");
                        }
                    }
                });
            }
        });
        
    </script>
</body>
</html>