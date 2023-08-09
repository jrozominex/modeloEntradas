<?php
session_start();
require_once '../modelo/conexion.php';
//error_reporting(0);
if ($_SESSION["logueado"] == TRUE && isset($_SESSION['Array_empresa']['CARGADORES-SERVER'])){
    $usuario = $_SESSION['usuario'];
    $Fecha = date('Y-m-d');
}else{
    ?>
    <script type="text/javascript">
        self.location='Admin.php';
        alert('No tienes permiso para acceder a este ambiente');
    </script>
    <?php
    die();
}
?>
<!DOCTYPE html>
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
    <?php include './libreria.php'; ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <meta name="viewport" content="initial-scale=0.8">
</head>
<body>
    <?php include './Header.php'; ?>
    <div class="container-fluid">
        <div class="row" style="margin-left: 10px; margin-right: 10px; margin-top: 5px;">
            <div class="col-sm-4"></div>
            <div class="col-sm-4" style="background-color: powderblue; border-radius: 5px;">
                <center><label>MODO DE LIQUIDACIÓN</label></center>
                <select class="form-control" id="select_liquidador" onchange="load_body_tarifas()">
                    <option selected="" disabled="">Seleccione</option>
                    <option value="-1"><b>Cargadores</option>
                    <?php 
                    $sql = "SELECT * FROM Liquidaciones()";
                    $res = sqlsrv_query($conn,$sql);
                    while($aa=sqlsrv_fetch_array($res)){
                        ?><option value="<?php echo $aa['idLiquidacion']; ?>"><?php echo utf8_encode($aa['Descripcion']); ?></option><?php
                    }?>
                </select><br>
            </div>
        </div>
        <div id="body_tarifas_data"></div>
    </div>
    <div class="modal fade" tabindex="0" id="Historial" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Historial Tarifas</h4>
                </div>
                <div class="modal-body" id="div_modal_historial_tarifa"></div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <!-- MODAL PARA REGISTRAR DATOS DEL TIQUETE-->
    <!-- MODAL PARA REGISTRAR DATOS DEL TIQUETE-->
    <div class="modal fade" tabindex="0" id="modalInsertar" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Registro Tarifa</h4>
                </div>
                <div id="div_modal_registrar"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function load_body_tarifas(){
            cadena = "band=" + 0 +
                    "&select_liquidador=" + $('#select_liquidador').val(); 
            $.ajax({
                type: "POST",
                url: "../modelo/tarifas.php",
                data: cadena,
                success: function(r){
                    $('#body_tarifas_data').html(r).fadeIn()
                    load_tarifa_vigente()
                }
            })
        }
        function load_tarifa_vigente(){
            cadena = "band=" + 3 +
                    "&select_liquidador=" + $('#select_liquidador').val();
            $.ajax({
                type: "POST",
                url: "../modelo/tarifas.php",
                data: cadena,
                success: function (r){
                    //console.log(r);
                    $('#tbody_tarifas').html(r).fadeIn()
                    datatable();
                }
            });
        }
        function open_modal_tarifas(){
            $('#modalInsertar').modal('show')
            cadena = "band=" + 4 +
                    "&select_liquidador=" + $('#select_liquidador').val();
            $.ajax({
                type: "POST",
                url: "../modelo/tarifas.php",
                data: cadena,
                success: function (r){
                    //console.log(r);
                    $('#div_modal_registrar').html(r).fadeIn()
                    
                }
            });
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
            load_tarifa_vigente()
        })
        function load_cargador_proveedor(){
            proveedor = $('#proveedor').val()
            cadena = "band=" + 5 +
                    "&proveedor=" + proveedor;
            $.ajax({
                type: "POST",
                url: "../modelo/tarifas.php",
                data: cadena,
                success: function (r){
                    //console.log(r);
                    $('#equipo').html(r).fadeIn()
                }
            });
        }
        function load_date_limit(){
            equipo = $('#equipo').val()
            cadena = "band=" + 6 +
                    "&equipo=" + equipo;

            $.ajax({
                type: "POST",
                url: "../modelo/tarifas.php",
                data: cadena,
                success: function (r){
                    $('#vigente_desde').prop("min",r)
                    $('#vigente_desde').val('')
                }
            })
        }
        function ver(){
            var tipo_tarifa = $('#tipo_tarifa').val();
            if(tipo_tarifa == '1'){
                $('#title_tm').show();
                $('#title_horo').show();
                $('#trf_tm').show();
                $('#trf_horo').show();
            }else if(tipo_tarifa == '2'){
                $('#title_tm').show();
                $('#trf_tm').show();
                $('#title_horo').hide();
                $('#trf_horo').hide();
            }else if(tipo_tarifa == '3'){
                $('#title_tm').hide();
                $('#trf_tm').hide();
                $('#title_horo').show();
                $('#trf_horo').show();
            }
        }

        function historial(val){
            select_liquidador = $('#select_liquidador').val()
            if(select_liquidador==-1){
                var equipo = val
                cadena = "equipo=" + equipo +
                        "&band=" + 2 +
                        "&select_liquidador=" + select_liquidador;
            }else{
                parse_val = val.split('||')
                cadena = "band=" + 2 +
                        "&select_liquidador=" + select_liquidador +
                        "&idLiquidacion=" + parse_val[0] +
                        "&id=" + parse_val[1] +
                        "&idProveedor=" + parse_val[2];
            }
            $.ajax({
                type: "POST",
                url: "../modelo/tarifas.php",
                data: cadena,
                success: function (r){
                    //console.log(r);
                    parse = r.split('||')
                    $('#div_modal_historial_tarifa').html(parse[1])
                    $('#machine').html(parse[0])
                }
            });
        }

        $(document).on('click', '#registrar', (e) => {
            proveedor = $('#proveedor').val()
            equipo = $('#equipo').val()
            vigente_desde = $('#vigente_desde').val()
            iva = $('#iva').val()
            tipo_tarifa = $('#tipo_tarifa').val()
            trf_tm = $('#trf_tm').val()
            trf_horo = $('#trf_horo').val()
            band = 1;

            cadena = "equipo=" + equipo +
                    "&proveedor=" + proveedor +
                    "&vigente_desde=" + vigente_desde +
                    "&iva=" + iva +
                    "&tipo_tarifa=" + tipo_tarifa +
                    "&trf_tm=" + trf_tm +
                    "&trf_horo=" + trf_horo +
                    "&band=" + band;
            $.ajax({
                type: "POST",
                url: "../modelo/tarifas.php",
                data: cadena,
                success: function (r){
                    //console.log(r)
                    if(r == 1){
                        alertify.success("se registró la tarifa")
                        $('#modalInsertar').modal('hide')
                        load_tarifa_vigente()
                    }else if(r == 2){
                        alertify.error("La fecha que intentas registrar es menor a la ultima tarifa");
                    }else{
                        alertify.error("No se pudo registrar la tarifa");
                    }
                }
            });
        });
        $(document).on('click', '#registrar_special', (e) => {
            proveedor = $('#proveedor').val()
            select_liquidador = $('#select_liquidador').val()
            reporte_variable = $('#reporte_variable').val()
            vigente_desde = $('#vigente_desde').val()
            tarifa = $('#tarifa').val()
            band = 7;

            cadena = "reporte_variable=" + reporte_variable +
                    "&select_liquidador=" + select_liquidador +
                    "&proveedor=" + proveedor +
                    "&vigente_desde=" + vigente_desde +
                    "&tarifa=" + tarifa +
                    "&band=" + band;
            $.ajax({
                type: "POST",
                url: "../modelo/tarifas.php",
                data: cadena,
                success: function (r){
                    //console.log(r)
                    if(r == 1){
                        alertify.success("se registró la tarifa")
                        $('#modalInsertar').modal('hide')
                        load_tarifa_vigente()
                    }else if(r == 2){
                        alertify.error("La fecha que intentas registrar es menor a la ultima tarifa");
                    }else{
                        alertify.error("No se pudo registrar la tarifa");
                    }
                }
            });
        });
    </script>
</body>
</html>