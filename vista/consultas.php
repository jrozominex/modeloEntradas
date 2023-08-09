<?php
if(!isset($_SESSION["logueado"])){
    session_start();
}
require_once '../modelo/conexion.php';
$txt_array_empresa = implode("','",$_SESSION['Array_empresa']['INVENTARIOS']);
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
//error_reporting(0);
if(!isset($_SESSION['Array_empresa']['CONSULTAS'])){
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
    <script src="../librerias/jquery-3.3.1.min.js"></script>
    <script src="../librerias/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="../librerias/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../librerias/alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="../librerias/alertifyjs/css/themes/bootstrap.css">
    <link rel="stylesheet" href="../librerias/shadowbox.css">
    <script src="../librerias/shadowbox.js"></script>
    <script src="../librerias/bootstrap/js/bootstrap.js"></script>
    <script src="../librerias/alertifyjs/alertify.js"></script>
    <script src="../librerias/moment.js" type="text/javascript"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
  <script type="text/javascript" src="https://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.css" />

    <script type="text/javascript" src="../../introjs/js/intro.js"></script>
    <link rel="stylesheet" type="text/css" href="../../introjs/css/main.css" />
    <link rel="stylesheet" type="text/css" href="../../introjs/css/introjs.css" />
  <!--
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
    <style type="text/css">
        thead tr th{
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #ffffff;
            text-align: center;
            vertical-align: middle;
            border: 2px solid;
        }
        .loader_min {
                /*float: right; margin-top: 5px;*/
                font-size: 4px;
                margin-right: 15px;
                /*margin: 18% auto;*/
                text-indent: -9999em;
                width: 11em;
                height: 11em;
                border-radius: 50%;
                background: #ffffff;
                background: -moz-linear-gradient(left, #EFEF0F 10%, rgba(239, 239, 15, 0) 42%);
                background: -webkit-linear-gradient(left, #EFA10F 10%, rgba(239, 161, 15, 0) 42%);
                background: -o-linear-gradient(left, #EF670F 10%, rgba(239, 103, 15, 0) 42%);
                background: -ms-linear-gradient(left, #EF3F0F 10%, rgba(239, 63, 15, 0) 42%);
                background: linear-gradient(to right, #D72F27 10%, rgba(215, 47, 39, 0) 42%);
                position: relative;
                -webkit-animation: load3 1.4s infinite linear;
                animation: load3 1.4s infinite linear;
            }
            .loader_min:before {
              width: 50%;
              height: 50%;
              background: #FFF;
              border-radius: 100% 0 0 0;
              position: absolute;
              top: 0;
              left: 0;
              content: '';
            }
            .loader_min:after {
              background: #FFF;
              width: 75%;
              height: 75%;
              border-radius: 50%;
              content: '';
              margin: auto;
              position: absolute;
              top: 0;
              left: 0;
              bottom: 0;
              right: 0;
            }
            @-webkit-keyframes load3 {
              0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
                background: -moz-linear-gradient(left, #EFEF0F 10%, rgba(239, 239, 15, 0) 42%);
                background: -webkit-linear-gradient(left, #EFEF0F 10%, rgba(239, 239, 15, 0) 42%);
              }
              25% {
                -webkit-transform: rotate(90deg);
                transform: rotate(90deg);
                background: -moz-linear-gradient(left, #EFA10F 10%, rgba(239, 161, 15, 0) 42%);
                background: -webkit-linear-gradient(left, #EFA10F 10%, rgba(239, 161, 15, 0) 42%);
              }
              50% {
                -webkit-transform: rotate(180deg);
                transform: rotate(180deg);
                background: -moz-linear-gradient(left, #EF670F 10%, rgba(239, 103, 15, 0) 42%);
                background: -webkit-linear-gradient(left, #EF670F 10%, rgba(239, 103, 15, 0) 42%);
              }
              75% {
                -webkit-transform: rotate(270deg);
                transform: rotate(270deg);
                background: -moz-linear-gradient(left, #EF3F0F 10%, rgba(239, 63, 15, 0) 42%);
                background: -webkit-linear-gradient(left, #EF3F0F 10%, rgba(239, 63, 15, 0) 42%);
              }
              100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
                background: -moz-linear-gradient(left, #D72F27 10%, rgba(215, 47, 39, 0) 42%);
                background: -webkit-linear-gradient(left, #D72F27 10%, rgba(215, 47, 39, 0) 42%);
              }
            }
            @keyframes load3 {
              0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
                background: -moz-linear-gradient(left, #EFEF0F 10%, rgba(239, 239, 15, 0) 42%);
                background: -webkit-linear-gradient(left, #EFEF0F 10%, rgba(239, 239, 15, 0) 42%);
              }
              25% {
                -webkit-transform: rotate(90deg);
                transform: rotate(90deg);
                background: -moz-linear-gradient(left, #EFA10F 10%, rgba(239, 161, 15, 0) 42%);
                background: -webkit-linear-gradient(left, #EFA10F 10%, rgba(239, 161, 15, 0) 42%);
              }
              50% {
                -webkit-transform: rotate(180deg);
                transform: rotate(180deg);
                background: -moz-linear-gradient(left, #EF670F 10%, rgba(239, 103, 15, 0) 42%);
                background: -webkit-linear-gradient(left, #EF670F 10%, rgba(239, 103, 15, 0) 42%);
              }
              75% {
                -webkit-transform: rotate(270deg);
                transform: rotate(270deg);
                background: -moz-linear-gradient(left, #EF3F0F 10%, rgba(239, 63, 15, 0) 42%);
                background: -webkit-linear-gradient(left, #EF3F0F 10%, rgba(239, 63, 15, 0) 42%);
              }
              100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
                background: -moz-linear-gradient(left, #D72F27 10%, rgba(215, 47, 39, 0) 42%);
                background: -webkit-linear-gradient(left, #D72F27 10%, rgba(215, 47, 39, 0) 42%);
              }
            }
        #wrapper {
            padding-left: 0;
            -webkit-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
            transition: all 0.5s ease;
        }

        #wrapper.toggled {
            padding-left: 250px;
        }

        #sidebar-wrapper {
            z-index: 1000;
            position: fixed;
            left: 250px;
            width: 0;
            height: 100%;
            margin-left: -250px;
            overflow-y: auto;
            background: #000;
            -webkit-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
            transition: all 0.5s ease;
        }

        #wrapper.toggled #sidebar-wrapper {
            width: 250px;
        }

        #page-content-wrapper {
            width: 100%;
            position: absolute;
            padding: 15px;
        }

        #wrapper.toggled #page-content-wrapper {
            position: absolute;
            margin-right: -250px;
        }

        /* Sidebar Styles */

        .sidebar-nav {
            position: absolute;
            top: 0;
            width: 250px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .sidebar-nav li {
            text-indent: 20px;
            line-height: 40px;
        }

        .sidebar-nav li a {
            display: block;
            text-decoration: none;
            color: #999999;
        }

        .sidebar-nav li a:hover {
            text-decoration: none;
            color: #fff;
            background: rgba(255,255,255,0.2);
        }

        .sidebar-nav li a:active,
        .sidebar-nav li a:focus {
            text-decoration: none;
        }

        .sidebar-nav > .sidebar-brand {
            height: 65px;
            font-size: 18px;
            line-height: 60px;
        }

        .sidebar-nav > .sidebar-brand a {
            color: #999999;
        }

        .sidebar-nav > .sidebar-brand a:hover {
            color: #fff;
            background: none;
        }

        @media(min-width:768px) {
            #wrapper {
                padding-left: 250px;
            }

            #wrapper.toggled {
                padding-left: 0;
            }

            #sidebar-wrapper {
                width: 250px;
            }

            #wrapper.toggled #sidebar-wrapper {
                width: 0;
            }

            #page-content-wrapper {
                padding: 20px;
                position: relative;
            }

            #wrapper.toggled #page-content-wrapper {
                position: relative;
                margin-right: 0;
            }
        }
    </style>
    <script type="module">
        import { w2grid,w2utils,w2form } from 'https://rawgit.com/vitmalina/w2ui/master/dist/w2ui.es6.min.js'
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            //hide_vertical_navbar()
            introJs().start();
        })
        function hide_vertical_navbar(){
           $("#wrapper").toggleClass("toggled"); 
        }
        function datatable(n){
            var table = $('#table_' + n).DataTable( {
                "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                "ordering": true,
                "info":     true,
                stateSave: true,
                scrollY:        '50vh',
                //"scrollX": true,
                "scrollCollapse": true,
                "paging":         true
            });
        }
        function load_body_menu(n){
            if(n=='nn'){
                $('#div_menu_body').html('<iframe src="http://trazapp.minex.com.co:81/VariacionPrecios/ventas.php" style="overflow:hidden;height:85vh;width:100%" allow="fullscreen" frameborder="0"></iframe>').fadeIn();
                $("#wrapper").toggleClass("toggled");
            }else{
                cadena = "band=" + n;
                $.ajax({
                    type: "POST",
                    url: "../modelo/consultas.php",
                    data: cadena,
                    success: function (r){
                    //console.log(r);
                    if(n==19){
                        $().w2destroy('layout')
                        let pstyle = 'border: 1px solid #efefef; padding: 5px;';
                        query(() => {
                            new w2layout({
                                box: query('#div_menu_body')[0],
                                name: 'layout',
                                panels: [
                                    { type: 'top', style: pstyle, html: '' },
                                    { type: 'left', size: '50%', style: pstyle, title: 'Datos Despacho' },
                                    { type: 'right', size: '50%', style: pstyle, title: 'Datos Recepción' }//,
                                    //{ type: 'preview',  size: '60%', resizable: true, style: pstyle, html: '',title: 'Descuentos' }
                                ]
                            });
                        });
                        w2ui.layout.html('top',r)
                        $().w2destroy('gridTable_left');
                        $().w2destroy('gridTable_right');
                        var txtDataTableD = ``;
                        var txtDataTableR = ``;
                        txtDataTableD = `{"searches":[
                              {"field":"lDespachadoDesde","label":"DespachadoDesde","type":"text"},
                              {"field":"lRecepcionadoEn","label":"RecepcionadoEn","type":"text"},
                              {"field":"lTransaccion","label":"Transacción","type":"text"},
                              {"field":"lToneladas","label":"Toneladas","type":"money"},
                              {"field":"lViajes","label":"Cant. viajes","type":"money"}
                           ],
                           "columns":[
                              {"field":"lDespachadoDesde","text":"DespachadoDesde","type":"text", "sortable": "true"},
                              {"field":"lRecepcionadoEn","text":"RecepcionadoEn","type":"text", "sortable": "true"},
                              {"field":"lTransaccion","text":"Transacción","type":"text", "sortable": "true"},
                              {"field":"lToneladas","text":"Toneladas","type":"money", "sortable": "true"},
                              {"field":"lViajes","text":"Cant. viajes","type":"money", "sortable": "true"}
                           ]
                        }`;
                        txtDataTableR = `{"searches":[
                              {"field":"lRecepcionadoEn","label":"RecepcionadoEn","type":"text"},
                              {"field":"lOrigen","label":"Origen","type":"text"},
                              {"field":"lProveedor","label":"Proveedor","type":"text"},
                              {"field":"lTransaccion","label":"Transacción","type":"text"},
                              {"field":"lToneladas","label":"Toneladas","type":"money"},
                              {"field":"lViajes","label":"Cant. viajes","type":"money"}
                           ],
                           "columns":[
                              {"field":"lRecepcionadoEn","text":"RecepcionadoEn","type":"text", "sortable": "true"},
                              {"field":"lOrigen","text":"Origen","type":"text", "sortable": "true"},
                              {"field":"lProveedor","text":"Proveedor","type":"text", "sortable": "true"},
                              {"field":"lTransaccion","text":"Transacción","type":"text", "sortable": "true"},
                              {"field":"lToneladas","text":"Toneladas","type":"money", "sortable": "true"},
                              {"field":"lViajes","text":"Cant. viajes","type":"money", "sortable": "true"}
                           ]
                        }`;
                        txtDataTableD = JSON.parse(txtDataTableD);
                        txtDataTableR = JSON.parse(txtDataTableR);
                        let gridTable_left = new w2grid({
                            name: 'gridTable_left',
                            show: {
                                toolbar: true
                                //footer: true
                            },
                            multiSearch: true,
                            searches: txtDataTableD.searches,
                            columns: txtDataTableD.columns
                        })
                        let gridTable_right = new w2grid({
                            name: 'gridTable_right',
                            show: {
                                toolbar: true
                                //footer: true
                            },
                            multiSearch: true,
                            searches: txtDataTableR.searches,
                            columns: txtDataTableR.columns
                        })
                        w2ui.layout.html('left',gridTable_left)
                        w2ui.layout.html('right',gridTable_right)
                    }else{
                        $('#div_menu_body').html(r).fadeIn();
                    }
                    $("#wrapper").toggleClass("toggled");
                  }
              });
            }
        }
        function search_transito_acopio(){
            empresa = $('#empresa').val()
            fecha_ini = $('#fecha_ini').val()
            fecha_fin = $('#fecha_fin').val()
            cadena = "band=" + 20 +
                    "&empresa=" + empresa +
                    "&fecha_ini=" + fecha_ini +
                    "&fecha_fin=" + fecha_fin;
            $.ajax({
                type: "POST",
                url: "../modelo/consultas.php",
                data: cadena,
                success: function (r){
                    let json = JSON.parse(r)
                    w2ui.gridTable_left.records=json.Despachos.records
                    w2ui.gridTable_left.reload();
                    w2ui.gridTable_right.records=json.Recepcion.records
                    w2ui.gridTable_right.reload();
                }
            })
        }
        function search_registers_coquizacion(){
            var empresa = $('#empresa').val()
            var centro_trabajo = $('#centro_trabajo').val()
            var clasificacion_alimentada = $('#clasificacion_alimentada').val()
            var clasificacion_producida = $('#clasificacion_producida').val()
            var fecha_ini = $('#fecha_ini').val()
            var fecha_fin = $('#fecha_fin').val()

            cadena = "band=" + 7 +
                    "&centro_trabajo=" + centro_trabajo +
                    "&empresa=" + empresa +
                    "&clasificacion_alimentada=" + clasificacion_alimentada +
                    "&clasificacion_producida=" + clasificacion_producida +
                    "&fecha_ini=" + fecha_ini +
                    "&fecha_fin=" + fecha_fin;
            $.ajax({
                type: "POST",
                url: "../modelo/consultas.php",
                data: cadena,
                success: function (r){
                    $('#div_resultado_hornos').html(r)
                    datatable('ali')
                    datatable('prod')
                }
            })
        }
        function load_body_inventory(n){
            $('#btn_actualizar_inv').addClass('loader_min')
            $('#btn_actualizar_inv').prop("disabled",true)
            if(n==1){
                $('#div_inventario_body').html('')
                let idEmpresa = $('#empresa').val()
                let idUnidadNegocio = $('#idUnidadNegocio').val()
                var idDestino = $('#centro_trabajo').val()
                let FechaInicioSaldo = $('#fecha_ini').val()
                let FechaFinSaldo = $('#fecha_fin').val()
                if(FechaInicioSaldo<=FechaFinSaldo){
                    cadena = "band=" + 9 +
                            "&idEmpresa=" + idEmpresa +
                            "&idUnidadNegocio=" + idUnidadNegocio +
                            "&idDestino=" + idDestino +
                            "&FechaInicioSaldo=" + FechaInicioSaldo +
                            "&FechaFinSaldo=" + FechaFinSaldo;
                    $.ajax({
                        type: "POST",
                        url: "../modelo/consultas.php",
                        data: cadena,
                        success: function(r){
                            //separ = r.split("||");
                            //console.log(r)
                            $('#btn_actualizar_inv').removeClass('loader_min')
                            $('#btn_actualizar_inv').prop("disabled",false)
                            $('#div_inventario_body').html(r)
                            $('#tabla_excel').val(r)
                        }
                    });
                }else{
                    alertify.error('La fecha inicial debe ser menor');
                    $('#btn_actualizar_inv').removeClass('loader_min')
                    $('#btn_actualizar_inv').prop("disabled",false)
                }
            }else if(n==2){
                let idEmpresa = $('#empresa').val()
                let idUnidadNegocio = $('#idUnidadNegocio').val()
                let idDestino = $('#centro_trabajo').val()
                let fecha_desde = $('#fecha_desde').val()
                let fecha_hasta = $('#fecha_hasta').val()
                //let anno = $('#anno_inv').val()
                //let semana = $('#semana_inv').val()
                cadena = "band=" + 12 +
                        "&idEmpresa=" + idEmpresa +
                        "&idUnidadNegocio=" + idUnidadNegocio +
                        "&idDestino=" + idDestino +
                        "&fecha_desde=" + fecha_desde +
                        "&fecha_hasta=" + fecha_hasta;
                        //"&anno=" + anno +
                        //"&semana=" + semana;
                $.ajax({
                    type: "POST",
                    url: "../modelo/consultas.php",
                    data: cadena,
                    success: function(r){
                        separ = r.split("||");
                        //console.log(r)
                        $('#btn_actualizar_inv').removeClass('loader_min')
                        $('#btn_actualizar_inv').prop("disabled",false)
                        $('#div_inventario_body').html(r)
                        $('#tabla_excel').val(r)
                        /*var table = $('#table_inventory_detail').DataTable({
                            //"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
                            "language": {
                                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                            },
                            "ordering": false,
                            "info":     false,
                            stateSave: false,
                            scrollY:        '60vh',
                            "scrollX": true,
                            "scrollCollapse": true,
                            "paging":         false,
                        });*/
                    }
                });
            }else if(n==3){
                $('#div_inventario_body').html('')
                let idEmpresa = $('#empresa').val()
                let idUnidadNegocio = $('#idUnidadNegocio').val()
                var idDestino = $('#centro_trabajo').val()
                //let FechaInicioSaldo = $('#fecha_ini').val()
                let FechaFinSaldo = $('#fecha_fin').val()
                //if(FechaInicioSaldo<=FechaFinSaldo){
                    cadena = "band=" + 24 +
                            "&idEmpresa=" + idEmpresa +
                            "&idUnidadNegocio=" + idUnidadNegocio +
                            "&idDestino=" + idDestino +
                            //"&FechaInicioSaldo=" + FechaInicioSaldo +
                            "&FechaFinSaldo=" + FechaFinSaldo;
                    $.ajax({
                        type: "POST",
                        url: "../modelo/consultas.php",
                        data: cadena,
                        success: function(r){
                            //separ = r.split("||");
                            //console.log(r)
                            $('#btn_actualizar_inv').removeClass('loader_min')
                            $('#btn_actualizar_inv').prop("disabled",false)
                            $('#div_inventario_body').html(r)
                            $('#tabla_excel').val(r)
                        }
                    });
                // }else{
                //     alertify.error('La fecha inicial debe ser menor');
                //     $('#btn_actualizar_inv').removeClass('loader_min')
                //     $('#btn_actualizar_inv').prop("disabled",false)
                // }
            }
        }
        function load_body_inventory_pila(){
            $('#btn_actualizar_inv').addClass('loader_min')
            $('#btn_actualizar_inv').prop("disabled",true)
            let idEmpresa = $('#empresa').val()
            let idUnidadNegocio = $('#idUnidadNegocio').val()
            let idDestino = $('#centro_trabajo').val()
            let fecha_desde = $('#fecha_desde').val()
            let fecha_hasta = $('#fecha_hasta').val()

            if(fecha_desde<=fecha_hasta){
                cadena = "band=" + 26 +
                        "&idEmpresa=" + idEmpresa +
                        "&idUnidadNegocio=" + idUnidadNegocio +
                        "&idDestino=" + idDestino +
                        "&fecha_desde=" + fecha_desde +
                        "&fecha_hasta=" + fecha_hasta;
                        //"&anno=" + anno +
                        //"&semana=" + semana;
                $.ajax({
                    type: "POST",
                    url: "../modelo/consultas.php",
                    data: cadena,
                    success: function(r){
                        separ = r.split("||");
                        console.log(r)
                        $('#btn_actualizar_inv').removeClass('loader_min')
                        $('#btn_actualizar_inv').prop("disabled",false)
                        $('#div_inventario_body').html(r)
                        $('#tabla_excel').val(r)
                        /*var table = $('#table_inventory_detail').DataTable({
                            //"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
                            "language": {
                                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                            },
                            "ordering": false,
                            "info":     false,
                            stateSave: false,
                            scrollY:        '60vh',
                            "scrollX": true,
                            "scrollCollapse": true,
                            "paging":         false,
                        });*/
                    }
                });
            }else{
                alertify.error('La fecha inicial debe ser menor');
                $('#btn_actualizar_inv').removeClass('loader_min')
                $('#btn_actualizar_inv').prop("disabled",false)
            }
        }
        function load_data_search(){
            $('#btn_search').addClass('loader_min')
            $('#btn_search').prop("disabled",true)
            let idEmpresa = $('#empresa').val()
            let idProveedor = $('#proveedor').val()
            let idUnidadNegocio = $('#idUnidadNegocio').val()
            var idDestino = $('#centro_trabajo').val()
            let FechaInicioSaldo = $('#fecha_ini').val()
            let FechaFinSaldo = $('#fecha_fin').val()

            cadena = "band=" + 13 +
                    "&idEmpresa=" + idEmpresa +
                    "&idProveedor=" + idProveedor +
                    "&idUnidadNegocio=" + idUnidadNegocio +
                    "&idDestino=" + idDestino +
                    "&FechaInicioSaldo=" + FechaInicioSaldo +
                    "&FechaFinSaldo=" + FechaFinSaldo;
            $.ajax({
                type: "POST",
                url: "../modelo/consultas.php",
                data: cadena,
                success: function(r){
                    //separ = r.split("||");
                    // console.log(r)
                    $('#div_inventario_body').html(r)
                    $('#tabla_excel').val(r)
                    $('#btn_search').removeClass('loader_min')
                    $('#btn_search').prop("disabled",false)
                }
            });
        }
        function change_anno(){
            let anno_inv = $('#anno_inv').val()
            cadena = "band=" + 11 +
                    "&anno_inv=" + anno_inv;
            $.ajax({
                type: "POST",
                url: "../modelo/consultas.php",
                data: cadena,
                success: function(r){
                    $('#semana_inv').html(r).fadeIn()
                    $('#semana_inv_llegada').html(r).fadeIn()
                }
            })
        }
        function generar_excel(){
            $('#btn_excel').addClass('loader_min')
            $('#btn_excel').prop("disabled",true)
            if($('#tabla_excel').val()!=''){
                $("#form_excel").attr("action", "descarga_EXCEL.php")
                $("#form_excel").submit()
            }
            $('#btn_excel').removeClass('loader_min')
            $('#btn_excel').prop("disabled",false)
        }
        function search_consumos(){
            $('#btn_loader_consumos').addClass('loader_min')
            $('#btn_loader_consumos').prop("disabled",true)
            let idEmpresa = $('#empresa').val()
            //let idUnidadNegocio = $('#idUnidadNegocio').val()
            var idDestino = $('#centro_trabajo').val()
            let FechaInicioSaldo = $('#fecha_ini').val()
            let FechaFinSaldo = $('#fecha_fin').val()

            cadena = "band=" + 15 +
                    "&idEmpresa=" + idEmpresa +
                    //"&idUnidadNegocio=" + idUnidadNegocio +
                    "&idDestino=" + idDestino +
                    "&FechaInicioSaldo=" + FechaInicioSaldo +
                    "&FechaFinSaldo=" + FechaFinSaldo;
                    console.log(cadena)
            $.ajax({
                type: "POST",
                url: "../modelo/consultas.php",
                data: cadena,
                success: function(r){
                    //separ = r.split("||");
                    console.log(r)
                    $('#div_inventario_body').html(r)
                    $('#tabla_excel').val(r)
                    $('#btn_loader_consumos').removeClass('loader_min')
                    $('#btn_loader_consumos').prop("disabled",false)
                }
            });
        }
        function search_transitos(){
            $('#btn_search').addClass('loader_min')
            $('#btn_search').prop("disabled",true)
            let idEmpresa = $('#empresa').val()
            let idUnidadNegocio = $('#idUnidadNegocio').val()
            let idDestino = $('#centro_trabajo').val()
            let anno = $('#anno_inv').val()
            let semana = $('#semana_inv').val()
            let semana_llegada = $('#semana_inv_llegada').val()
            cadena = "band=" + 17 +
                    "&idEmpresa=" + idEmpresa +
                    "&idUnidadNegocio=" + idUnidadNegocio +
                    "&idDestino=" + idDestino +
                    "&anno=" + anno +
                    "&semana=" + semana + 
                    "&semana_llegada=" + semana_llegada;
            $.ajax({
                type: "POST",
                url: "../modelo/consultas.php",
                data: cadena,
                success: function(r){
                    separ = r.split("||");
                    console.log(r)
                    $('#btn_search').removeClass('loader_min')
                    $('#btn_search').prop("disabled",false)
                    $('#div_inventario_body').html(r)
                    $('#tabla_excel').val(r)
                }
            });
        }
        function search_transitos_details(data){
            $('#modalDetailsTransito').modal('show');
            $('#div_menu_modal_creacion').addClass('loader_min')
            cadena = "band=" + 18 +
                    "&data=" + data;
            $.ajax({
                type: "POST",
                url: "../modelo/consultas.php",
                data: cadena,
                success: function(r){
                    separ = r.split("||");
                    //console.log(r)
                    $('#div_menu_modal_creacion').removeClass('loader_min')
                    $('#div_menu_modal_creacion').html(r)
                    //$('#tabla_excel_details').val(r)
                }
            });
        }


        //
         function reporte_movimiento_pila (){
            $('#btn_consultar_movxpila').addClass('loader_min')
            $('#btn_consultar_movxpila').prop("disabled",true)
            
                $('#div_movimientos_body').html('')
                let idEmpresa = $('#empresa').val()         
                var idpatio = $('#patio').val()
                let FechaInicioSaldo = $('#fecha_ini').val()
                let FechaFinSaldo = $('#fecha_fin').val()

                if(FechaInicioSaldo<=FechaFinSaldo){
                    cadena = "band=" + 22 +
                            "&idEmpresa=" + idEmpresa +
                            "&idPatio=" + idpatio +
                            "&FechaInicioSaldo=" + FechaInicioSaldo +
                            "&FechaFinSaldo=" + FechaFinSaldo;
                    $.ajax({
                        type: "POST",
                        url: "../modelo/consultas.php",
                        data: cadena,
                        success: function(r){
                            //separ = r.split("||");
                            console.log(r)
                            $('#btn_consultar_movxpila').removeClass('loader_min')
                            $('#btn_consultar_movxpila').prop("disabled",false)
                            $('#div_movimientos_body').html(r)
                            $('#tabla_excel').val(r)
                         
                        }
                    });
                }else{
                    alertify.error('La fecha inicial debe ser menor');
                    $('#btn_consultar_movxpila').removeClass('loader_min')
                    $('#btn_consultar_movxpila').prop("disabled",false)
                }
            
        }
        //



    </script>
</head>
<body>
    <?php include './Header.php';?>
    <button class="btn btn-default" id="menu-toggle" onclick="hide_vertical_navbar()"><span class="glyphicon glyphicon-menu-hamburger"></span></button>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                        Menú
                    </a>
                </li>
                <ul id="cargadores">
                    <a href="#">Cargadores</a>
                    <li>
                        <a href="#" onclick="load_body_menu(1)">Reporte horas "Pend."</a>
                    </li>
                    <li>
                        <a href="#" onclick="load_body_menu(2)">Liquidaciones</a>
                    </li>
                    <li>
                        <a href="#" onclick="load_body_menu(3)">Promedio cargadores  "Pend."</a>
                    </li>
                </ul>
                <ul>
                    <a href="#">Producción</a>
                    <li>
                        <a href="#" onclick="load_body_menu(1)">Preparación mezcla  "Pend."</a> <!-- 4 -->
                    </li>
                    <li>
                        <a href="#" onclick="load_body_menu(5)">Coquización</a>
                    </li>
                    <li>
                        <a href="#" onclick="load_body_menu(6)">Clasificación ó Molienda  "Pend."</a>
                    </li>
                    <li>
                        <a href="#" onclick="load_body_menu(8)">Saldos Inventarios</a>
                    </li>
                    <li>
                        <a href="#" onclick="load_body_menu(23)">Saldos Inventarios v2 (pilas)</a>
                    </li>
                    <li>
                        <a href="#" onclick="load_body_menu(10)">Inventarios</a>
                    </li>
                    <li>
                        <a href="#" onclick="load_body_menu(25)">Inventarios v2 (pilas)</a>
                    </li>
                    <li>
                        <a href="#" onclick="load_body_menu(21)">Movimiento X Pila</a>
                    </li>
                    <li>
                        <a href="#" onclick="load_body_menu(14)">Consumos y Prod. Maquilas</a>
                    </li>
                    <li>
                        <a href="#" onclick="load_body_menu(16)">Transitos</a>
                    </li>
                </ul>
                <ul>
                    <a href="#">Control</a>
                    <?php 
                    if(isset($_SESSION['Array_empresa']['CONSULTA_VARIACION_PRECIOS'])){
                        ?>
                        <li>
                            <a href="#" onclick="load_body_menu('nn')">Variación Precios</a>
                        </li>
                        <?php
                    }
                    ?>
                    <li>
                        <a href="#" onclick="load_body_menu(19)">Transitos entre acopios</a>
                    </li>
                </ul>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div>
                <div id="div_menu_body" style="width: 98vw; height:84vh;"></div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
</body>

</html>