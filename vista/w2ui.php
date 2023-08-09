<?php
session_start();
require_once '../modelo/conexion.php';
//error_reporting(0);


// TARIFA_CARGADORES
// }


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
<html>
<head>
    <title>Tarifas</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=0.7">
     <?php include './libreria.php'; ?>
    <!--<script type="text/javascript" src="../w2ui/w2ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../w2ui/w2ui.min.css" />-->
    <script src="../../libreria/jquery-3.3.1.min.js"></script>
    <script src="../../libreria/jquery.min.js" type="text/javascript"></script>
    <link href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script type="text/javascript" src="https://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.css" />
    <style type="text/css">
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
    </style>
</head>
<body>
    <?php include './Header.php'; ?>
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
    <div id="layout" style="width: 100vw; height:94vh;"></div>
<script type="module">
    import { w2grid,w2utils,w2form } from 'https://rawgit.com/vitmalina/w2ui/master/dist/w2ui.es6.min.js'
</script>
<script type="text/javascript">
    $(document).ready(function(){
        create_grids()
    })
    function open_modal_tarifas(){
        $('#div_modal_registrar').html('<div class="modal-body" id="loader_min"></div>').fadeIn();
        $('#loader_min').addClass('loader_min');
        let xhr = new XMLHttpRequest();
        let url = "../modelo/controller.php?band=-1";
        xhr.open("POST", url, true);
        let param = {select_liquidador:$('#select_liquidador').val()};
        let data = JSON.stringify(param);
        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
        xhr.onreadystatechange = function (){
            if (xhr.readyState === 4 && xhr.status === 200){                                                  
                //console.log(this.responseText)
                let r = this.responseText;
                $('#div_modal_registrar').html(r).fadeIn()
            }
        };
        xhr.send(data);
    }
    function load_cargador_proveedor(){
        proveedor = $('#proveedor').val()
        empresa = $('#empresa').val()
        let xhr = new XMLHttpRequest();
        let url = "../modelo/controller.php?band=11";
        xhr.open("POST", url, true);
        let param = {proveedor:proveedor,empresa:empresa};
        let data = JSON.stringify(param);
        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
        xhr.onreadystatechange = function (){
            if (xhr.readyState === 4 && xhr.status === 200){                                                  
                console.log(this.responseText)
                let r = this.responseText;
                $('#equipo').html(r).fadeIn()
            }
        };
        xhr.send(data);
    }
    $(document).on('click','#registrar_special',(e)=>{
        select_liquidador = $('#select_liquidador').val();
        empresa=$('#empresa').val();
        proveedor=$('#proveedor').val();
        if(select_liquidador==-1){
            variable=$('#equipo').val();
        }else{
            variable=$('#reporte_variable').val();
        }
        let xhr = new XMLHttpRequest();
        let url = "../modelo/controller.php?band=10";
        xhr.open("POST", url, true);
        let param = {select_liquidador:select_liquidador,proveedor:proveedor,variable:variable,empresa:empresa};
        let data = JSON.stringify(param);
        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
        xhr.onreadystatechange = function (){
            if (xhr.readyState === 4 && xhr.status === 200){                                                  
                //console.log(this.responseText)
                if(this.responseText==1){
                    load_body_documents();
                }
            }
        };
        xhr.send(data);
    });
    function create_grids(){
        $('.navbar-default').prop("style","margin-bottom: 5px !important") //Elimina border del navbar
        $().w2destroy('layout')
        let pstyle = 'border: 1px solid #efefef; padding: 5px;';
        query(() => {
            new w2layout({
                box: query('#layout')[0],
                name: 'layout',
                panels: [
                    { type: 'top', resizable: true, style: pstyle, html: '' },
                    { type: 'left', size: '40%', resizable: true, style: pstyle, html: '', title: 'Documentos' },
                    { type: 'main', size: '40%',  resizable: true, style: pstyle, html: '', title: 'Tarifas' },
                    { type: 'preview',  size: '60%', resizable: true, style: pstyle, html: '',title: 'Descuentos' }
                ]
            });
            cargar_listado()
        });
    }
    function cargar_listado(){
        let xhr = new XMLHttpRequest();
        let url = "../modelo/controller.php?band=0";
        xhr.open("POST", url, true);
        let data = '';
        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
        xhr.onreadystatechange = function (){
            if (xhr.readyState === 4 && xhr.status === 200){                                                  
                //console.log(this.responseText)
                let response_grid = (this.responseText)
                w2ui.layout.html('top',response_grid)
            }
        };
        xhr.send(data);
    }
    function load_body_documents(){
        select_liquidador=$('#select_liquidador').val();
        let xhr = new XMLHttpRequest();
        let url = "../modelo/controller.php?band=1";
        xhr.open("POST", url, true);
        let param = {select_liquidador:select_liquidador};
        let data = JSON.stringify(param);
        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
        xhr.onreadystatechange = function (){
            if (xhr.readyState === 4 && xhr.status === 200){                                                  
                //console.log(this.responseText)
                let response_grid = JSON.parse(this.responseText)
                $().w2destroy('grid');
                $().w2destroy('layout_left');
                let grid = new w2grid({
                    name: 'grid',
                    sortData: [{field: 'fproveedor', direction: 'asc'}],
                    columns: response_grid.columns,
                    records: response_grid.records,
                    onClick(event){
                        let idDocumentoLiquidacion = event.detail.recid;
                        let class_document = event.owner;
                        search_list('ConceptoAjuste');
                        search_list('Modo');
                        if(select_liquidador==-1){
                            search_list('ModoTarifa');
                        }else{
                            load_details_documents(idDocumentoLiquidacion)
                        }
                        load_tarifas_documents(idDocumentoLiquidacion)
                        load_descuentos_documents(idDocumentoLiquidacion) //TEMPORALMENTE COMENTADO (DESCOMENTAR AL ARREGLAR)
                        const ind = grid.get(idDocumentoLiquidacion, true)
                        const record = grid.records[ind]
                    }
                })
                if(select_liquidador==-1){
                    w2ui.layout.html('left',grid)
                }else{
                    query(() => {
                        let pstyle = 'border: 1px solid #efefef; padding: 5px;';
                        //let pstyle = '';
                        let w2layout_left = new w2layout({
                            name: 'layout_left',
                            panels: [
                                { type: 'main',  size: '30%', resizable: true, style: pstyle, html: ''},
                                { type: 'preview',  size: '70%', resizable: true, style: pstyle, html: ''}
                            ]
                        });
                        w2ui.layout.html('left',w2layout_left)
                        w2ui.layout_left.html('main', grid)
                    });
                }
            }
        };
        xhr.send(data);
    }
    function load_details_documents(idDocumentoLiquidacion){
        let xhr = new XMLHttpRequest();
        let url = "../modelo/controller.php?band=2";
        xhr.open("POST", url, true);
        let param = {idDocumentoLiquidacion:idDocumentoLiquidacion};
        let data = JSON.stringify(param);
        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
        xhr.onreadystatechange = function (){
            if (xhr.readyState === 4 && xhr.status === 200){
                //console.log(this.responseText)
                let response_grid = (this.responseText)
                w2ui.layout_left.html('preview',response_grid)
            }
        };
        xhr.send(data);
    }
    function load_tarifas_documents(idDocumentoLiquidacion){
        let select_liquidador=$('#select_liquidador').val();
        let ModoTarifa = JSON.parse(sessionStorage.getItem("ModoTarifa"))
        let xhr = new XMLHttpRequest();
        let url = "../modelo/controller.php?band=3";
        xhr.open("POST", url, true);
        let param = {idDocumentoLiquidacion:idDocumentoLiquidacion,select_liquidador:select_liquidador};
        let data = JSON.stringify(param);
        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
        xhr.onreadystatechange = function (){
            if (xhr.readyState === 4 && xhr.status === 200){
                //console.log(this.responseText)
                let response_grid_tarifas = JSON.parse(this.responseText)
                $().w2destroy('layout_main');
                $().w2destroy('grid_tarifas');
                
                let grid_tarifas = new w2grid({
                    name: 'grid_tarifas',
                    show: {
                        toolbar: true,
                        //footer: true,
                        //toolbarSave: true,
                        toolbarDelete: true
                    },
                    //columns: type_columns,
                    onSave: function(event) {
                        let getChanges = grid_tarifas.getChanges();
                        //console.log(getChanges);//console.log(event.detail.changes);
                        save_tarifas(getChanges,idDocumentoLiquidacion)
                    },
                    onDelete: function (event){
                        if(event.detail.force){
                            let getSelection= grid_tarifas.getSelection();
                            delete_tarifas(getSelection);
                            //console.log(event,getSelection);
                        }
                    },
                    toolbar: {
                        items: [
                            /*{ id: 'delete', type: 'button', text: 'Eliminar', icon: 'w2ui-icon-cross' },
                            { type: 'break' },*/
                            { id: 'save', type: 'button', text: 'Guardar', icon: 'w2ui-icon-check' },
                            { type: 'break' },
                            { id: 'add', type: 'button', text: 'Agregar', icon: 'w2ui-icon-plus' },
                            { type: 'break' }
                        ],
                        onClick(event) {
                            //console.log(grid.getChanges())
                            if (event.target == 'add') {
                                let recid = grid_tarifas.records.length + 1
                                this.owner.add({ recid });
                                this.owner.scrollIntoView(recid);
                                this.owner.editField(recid, 1)
                                let idDocumentoLiquidacion = w2ui.grid.getSelection()
                                w2ui.grid_tarifas.editField(recid,"FechaDesde","1/1/2022")
                                //console.log(grid_tarifas.records)
                                //load_new_tarifas(recid)
                            }else if(event.target=='delete'){
                                if(event.detail.force){
                                    let getSelection= grid_tarifas.getSelection();
                                    delete_tarifas(getSelection);
                                }
                            }else if(event.target=='save'){
                                let getChanges = grid_tarifas.getChanges();
                                //console.log(getChanges);//console.log(event.detail.changes);
                                save_tarifas(getChanges,idDocumentoLiquidacion)
                            }
                            if(event.target == 'showChanges'){
                                showChanged()
                            }
                        }
                    }
                })
                if(select_liquidador==-1){
                    w2ui.grid_tarifas.columns=[{"field":"Tipo_Tarifa", "text": "Modo", "editable": {"type":"combo","items":ModoTarifa,"filter":"true"}},{"field":"Fecha_Desde", "text":"Fecha Desde", "render":"date","editable": { "type": "date" }},{"field":"Fecha_Hasta", "text":"Fecha Hasta", "render":"date","editable": { "type": "date" }},{"field":"Tarifa_Horometro", "text":"Tarifa", "render":"money","editable": { "type": "money" }}];
                }else{
                    w2ui.grid_tarifas.columns=response_grid_tarifas.columns;
                }
                w2ui.grid_tarifas.records=response_grid_tarifas.records;

                query(() => {
                    let pstyle = 'border: 1px solid #efefef; padding: 5px;';
                    let w2layout_main = new w2layout({
                        name: 'layout_main',
                        panels: [
                            { type: 'left',  size: '80%', resizable: true, style: pstyle}
                        ]
                    });
                    w2ui.layout.html('main',w2layout_main)
                    w2ui.layout_main.html('left',grid_tarifas)
                });
                $().w2destroy('form_create_date');
            }
        };
        xhr.send(data);
    }
    function save_tarifas(getChanges,idDocumentoLiquidacion){
        select_liquidador=$('#select_liquidador').val()
        if(select_liquidador==-1){
            let Tipo_Tarifa = JSON.parse(sessionStorage.getItem("ModoTarifa"))
            getChanges.forEach(posx =>{
                if(posx.Tipo_Tarifa){
                    let get_id = Tipo_Tarifa.filter((fields)=> fields.text==posx.Tipo_Tarifa)
                    posx.Tipo_Tarifa = get_id[0].id
                }
            })
        }
        let xhr = new XMLHttpRequest();
        let url = "../modelo/controller.php?band=4";
        xhr.open("POST", url, true);
        let param = {getChanges:getChanges,idDocumentoLiquidacion:idDocumentoLiquidacion,select_liquidador:select_liquidador};
        let data = JSON.stringify(param);
        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
        xhr.onreadystatechange = function (){
            if (xhr.readyState === 4 && xhr.status === 200){
                console.log(this.responseText)
                if(this.responseText==1){
                    load_tarifas_documents(idDocumentoLiquidacion)
                }
            }
        }
        xhr.send(data);
    }
    function load_descuentos_documents(idDocumentoLiquidacion){
        let select_liquidador = $('#select_liquidador').val();
        let ConceptoAjuste = JSON.parse(sessionStorage.getItem("ConceptoAjuste"))
        let Modo = JSON.parse(sessionStorage.getItem("Modo"))
        let xhr = new XMLHttpRequest();
        let url = "../modelo/controller.php?band=5";
        xhr.open("POST", url, true);
        let param = {idDocumentoLiquidacion:idDocumentoLiquidacion,select_liquidador:select_liquidador};
        let data = JSON.stringify(param);
        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
        xhr.onreadystatechange = function (){
            if (xhr.readyState === 4 && xhr.status === 200){
                let response_grid_descuentos = JSON.parse(this.responseText)
                $().w2destroy('layout_preview');
                $().w2destroy('grid_descuentos');
                let grid_descuentos = new w2grid({
                    name: 'grid_descuentos',
                    show: {
                        toolbar: true,
                        //footer: true,
                        //toolbarSave: true,
                        toolbarDelete: true
                    },
                    //columns: response_grid_descuentos.columns,
                    columns: [{"field":"iconcepto", "text": "Concepto", "editable": {"type":"combo","items":ConceptoAjuste,"filter":"true"}},{"field":"Referencia", "text":"Referencia", "editable": {"type":"text"}},{"field":"modo", "text": "Modo", "editable": {"type":"combo","items":Modo,"filter":"true"}},{"field":"Vigente_desde", "text":"Fecha Desde", "render":"date","editable": { "type": "date" }},{"field":"Vigente_hasta", "text":"Fecha Hasta", "render":"date","editable": { "type": "date" }},{"field":"valor", "text":"Tarifa", "render":"money","editable": { "type": "money" }}],
                    onSave: function(event) {
                        let getChanges = grid_descuentos.getChanges();
                        save_descuentos(getChanges,idDocumentoLiquidacion)
                    },
                    onDelete: function (event){
                        if(event.detail.force){
                            let getSelection= grid_descuentos.getSelection();
                            delete_descuentos(getSelection);
                            //console.log(event,getSelection);
                        }
                    },
                    toolbar: {
                        items: [
                            /*{ id: 'delete', type: 'button', text: 'Eliminar', icon: 'w2ui-icon-cross' },
                            { type: 'break' },*/
                            { id: 'save', type: 'button', text: 'Guardar', icon: 'w2ui-icon-check' },
                            { type: 'break' },
                            { id: 'add', type: 'button', text: 'Agregar', icon: 'w2ui-icon-plus' },
                            { type: 'break' }
                        ],
                        onClick(event) {
                            //console.log(grid.getChanges())
                            if (event.target == 'add') {
                                let recid = grid_descuentos.records.length + 1
                                this.owner.add({ recid });
                                this.owner.scrollIntoView(recid);
                                this.owner.editField(recid, 1)
                            }else if(event.target=='delete'){
                                let getSelection= grid_descuentos.getSelection();
                                delete_descuentos(getSelection);
                                //console.log(event,getSelection);
                            }else if(event.target=='save'){
                                let getChanges = grid_descuentos.getChanges();
                                save_descuentos(getChanges,idDocumentoLiquidacion)
                            }
                            if (event.target == 'showChanges') {
                                showChanged()
                            }
                        }
                    },
                    records: response_grid_descuentos.records
                })
                w2ui.layout.html('preview',grid_descuentos)
                //$().w2destroy('form_create_date');
            }
        };
        xhr.send(data);
    }
    function save_descuentos(getChanges,idDocumentoLiquidacion){
        let ConceptoAjuste = JSON.parse(sessionStorage.getItem("ConceptoAjuste"))
        let Modo = JSON.parse(sessionStorage.getItem("Modo"))
        select_liquidador = $('#select_liquidador').val()
        getChanges.forEach(posx =>{
            if(posx.iconcepto){
                let get_id = ConceptoAjuste.filter((fields)=> fields.text==posx.iconcepto);
                posx.iconcepto = get_id[0].id
            }
            if(posx.modo){
                let get_id = Modo.filter((fields)=> fields.text==posx.modo)
                posx.modo = get_id[0].id
            }
        })
        let xhr = new XMLHttpRequest();
        let url = "../modelo/controller.php?band=6";
        xhr.open("POST", url, true);
        let param = {getChanges:getChanges,idDocumentoLiquidacion:idDocumentoLiquidacion,select_liquidador:select_liquidador};
        let data = JSON.stringify(param);
        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
        xhr.onreadystatechange = function (){
            if (xhr.readyState === 4 && xhr.status === 200){
                console.log(this.responseText)
                if(this.responseText==1){
                    load_descuentos_documents(idDocumentoLiquidacion)
                }
            }
        }
        xhr.send(data);
    }
    function search_list(type_list){
        let xhr = new XMLHttpRequest();
        let url = "../modelo/controller.php?band=7";
        xhr.open("POST", url, true);
        let param = {type_list:type_list};
        let data = JSON.stringify(param);
        let response = ''
        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
        xhr.onreadystatechange = function (){
            if (xhr.readyState === 4 && xhr.status === 200){
                response = (this.responseText);
                //console.log(response)
                sessionStorage.setItem(type_list,response);
            }
        }
        xhr.send(data);
    }
    function delete_tarifas(variable){
        let idDocumentoLiquidacion = w2ui.grid.getSelection();
        //console.log(idDocumentoLiquidacion)
        select_liquidador = $('#select_liquidador').val()
        let xhr = new XMLHttpRequest();
        let url = "../modelo/controller.php?band=8";
        xhr.open("POST", url, true);
        let param = {variable:variable[0],idDocumentoLiquidacion:idDocumentoLiquidacion[0],select_liquidador:select_liquidador};
        let data = JSON.stringify(param);
        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
        xhr.onreadystatechange = function (){
            if (xhr.readyState === 4 && xhr.status === 200){
                console.log(this.responseText);
                if(this.responseText==1){
                    load_tarifas_documents(idDocumentoLiquidacion[0])
                }
            }
        }
        xhr.send(data);
    }
    function delete_descuentos(variable){
        let idDocumentoLiquidacion = w2ui.grid.getSelection();
        //console.log(idDocumentoLiquidacion)
        select_liquidador = $('#select_liquidador').val()
        let xhr = new XMLHttpRequest();
        let url = "../modelo/controller.php?band=9";
        xhr.open("POST", url, true);
        let param = {variable:variable[0],idDocumentoLiquidacion:idDocumentoLiquidacion[0],select_liquidador:select_liquidador};
        let data = JSON.stringify(param);
        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
        xhr.onreadystatechange = function (){
            if (xhr.readyState === 4 && xhr.status === 200){
                //console.log(this.responseText);
                if(this.responseText==1){
                    load_descuentos_documents(idDocumentoLiquidacion[0])
                }
            }
        }
        xhr.send(data);
    }
</script>
</body>
</html>