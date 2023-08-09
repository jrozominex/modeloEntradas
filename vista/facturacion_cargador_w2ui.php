<?php
if(!isset($_SESSION["logueado"])){
	session_start();
	require_once '../modelo/conexion.php';
}
$idUsuario = $_SESSION['idUsuario'];
if(!isset($_SESSION['Array_empresa']['PRODUCCION'])){
	?>
  <script type="text/javascript">
      self.location='Admin.php';
      alert('No tienes permiso para acceder a este ambiente');
  </script>
  <?php
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="initial-scale=0.8">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />	
	<title>Facturación</title>
	<?php include './libreria.php'; ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="https://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    	.select_tiquete_out {
    		background-color: #C7CE3E !important;
    	}
    	.select_tiquete_in {
    		background-color: #C7CE3E !important;
    	}
    	.without_scroll {
    		overflow: hidden !important;
    	}
    </style>
    <script type="module">
	    import { w2grid,w2utils,w2form } from 'https://rawgit.com/vitmalina/w2ui/master/dist/w2ui.es6.min.js'
	</script>
	<script language="JavaScript">
		$(document).ready(function(){
	        create_grids()
	  //       jQuery.event.special.touchstart = {
			//   setup: function( _, ns, handle ) {
			//       this.addEventListener("touchstart", handle, { passive: !ns.includes("noPreventDefault") });
			//   }
			// };
	    })
	    function create_grids(){
	        $('.navbar-default').prop("style","margin-bottom: 5px !important") //Elimina border del navbar
	        $().w2destroy('layout')
	        let pstyle = 'border: 1px solid #efefef; padding: 5px;';
	        query(() => {
	            new w2layout({
	                box: query('#layout')[0],
	                name: 'layout',
	                panels: [
	                    { type: 'top', style: pstyle, html: '' },
	                    { type: 'left', size: '50%', style: pstyle, title: 'Datos liquidación' },
	                    { type: 'right', size: '50%', style: pstyle, title: 'Datos consulta' }//,
	                    //{ type: 'preview',  size: '60%', resizable: true, style: pstyle, html: '',title: 'Descuentos' }
	                ]
	            });
	            cargar_listado()
	        });
	    }
	    function cargar_listado(){
	        let xhr = new XMLHttpRequest();
	        let url = "../modelo/facturacion_cargador_w2ui.php?band=-1";
	        xhr.open("POST", url, true);
	        let param = {select_liquidador:$('#select_liquidador').val()};
        	let data = JSON.stringify(param);
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
	    function load_body_liquidaciones(){
	    	//00000000-0000-0000-0000-000000000001
	    	let select_liquidador = $('#select_liquidador').val();
	    	$().w2destroy('layout_left')
	    	$().w2destroy('layout_right')
	    	query(() => {
                let pstyle = 'border: 1px solid #efefef; padding: 5px;';
                //let pstyle = '';
                let w2layout_left = new w2layout({
                    name: 'layout_left',
                    panels: [
                        { type: 'main', size: '30%', style: pstyle, html: ''},
                        { type: 'preview', size: '70%', resizable: true, style: pstyle, html: ''}
                    ]
                });
                let w2layout_right = new w2layout({
                    name: 'layout_right',
                    panels: [
                        { type: 'main', size: '30%', style: pstyle, html: ''},
                        { type: 'preview', size: '70%', resizable: true, style: pstyle, html: ''}
                    ]
                });
                w2ui.layout.html('left',w2layout_left)
                w2ui.layout.html('right',w2layout_right)
            });
			let xhr = new XMLHttpRequest();
	        let url = "../modelo/facturacion_cargador_w2ui.php?band=6";
	        xhr.open("POST", url, true);
	        let param = {select_liquidador:select_liquidador};
        	let data = JSON.stringify(param);
	        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
	        xhr.onreadystatechange = function (){
	            if (xhr.readyState === 4 && xhr.status === 200){
	                let response_grid = (this.responseText)
	                response_grid = response_grid.split("||");
					w2ui.layout_left.html('main','<div id="div_left"></div>')
					w2ui.layout_right.html('main','<div id="div_right"></div>')
					$('#div_left').html(response_grid[0])
					$('#div_right').html(response_grid[1])
					$('#btn_load_liq').hide()
					$('[name=layout_left]').addClass('without_scroll')
					$('[name=layout_right]').addClass('without_scroll')
					$().w2destroy('gridTable_left');
					$().w2destroy('gridTable_right');
					var txtDataTable = ``;
					if (select_liquidador=='-1'){
						txtDataTable = `{"searches":[
						      {"field":"ltiquete","label":"Tiquete","type":"text"},
						      {"field":"lfecha","label":"Fecha","type":"date"},
						      {"field":"lremision","label":"Remisión","type":"text"},
						      {"field":"lpatio","label":"Patio","type":"text"},
						      {"field":"lcargador","label":"Cargador","type":"text"},
						      {"field":"lhoras","label":"Hrs.","type":"money"},
						      {"field":"ltarifa","label":"Tarifa","type":"money"},
						      {"field":"ltotal","label":"Total","type":"money"}
						   ],
						   "columns":[
						      {"field":"ltiquete","text":"Tiquete","type":"text", "sortable": "true"},
						      {"field":"lfecha","text":"Fecha","type":"date", "sortable": "true"},
						      {"field":"lremision","text":"Remisión","type":"text", "sortable": "true"},
						      {"field":"lpatio","text":"Patio","type":"text", "sortable": "true"},
						      {"field":"lcargador","text":"Cargador","type":"text", "sortable": "true"},
						      {"field":"lhoras","text":"Hrs.","type":"money", "sortable": "true"},
						      {"field":"ltarifa","text":"Tarifa","type":"money", "sortable": "true"},
						      {"field":"ltotal","text":"Total","type":"money", "sortable": "true"}
						   ]
						}`;
					}else{
						txtDataTable = `{"searches":[
						      {"field":"lfecha","label":"Fecha","type":"date"},
						      {"field":"ltiquete","label":"Tiquete","type":"text"},
						      {"field":"lempresa","label":"Empresa","type":"text"},
						      {"field":"lpatio","label":"Patio","type":"text"},
						      {"field":"ltransaccion","label":"Transacción","type":"text"},
						      {"field":"ltm","label":"Tm","type":"money"},
						      {"field":"ltarifa","label":"Tarifa","type":"money"},
						      {"field":"ltotal","label":"Total","type":"money"}
						   ],
						   "columns":[
						      {"field":"lfecha","text":"Fecha","type":"date", "sortable": "true"},
						      {"field":"ltiquete","text":"Tiquete","type":"text", "sortable": "true"},
						      {"field":"lempresa","text":"Empresa","type":"text", "sortable": "true"},
						      {"field":"lpatio","text":"Patio","type":"text", "sortable": "true"},
						      {"field":"ltransaccion","text":"Transacción","type":"text", "sortable": "true"},
						      {"field":"ltm","text":"Tm","type":"money", "sortable": "true"},
						      {"field":"ltarifa","text":"Tarifa","type":"money", "sortable": "true"},
						      {"field":"ltotal","text":"Total","type":"money", "sortable": "true"}
						   ]
						}`;
					}
					txtDataTable = JSON.parse(txtDataTable);
					let gridTable_left = new w2grid({
					    name: 'gridTable_left',
					    show: {
					        toolbar: true
					        //footer: true
					    },
					    toolbar: {
					        items: [
					            { type: 'break' },
					            { type: 'button', id: 'MoveOut', text: 'Sacar Tiquetes', style:'color: red; font-weight: bold', icon: 'fa fa-arrow-right' }
					        ],
					        onClick: function (target, data) {
					            //console.log(target,gridTable_left.getSelection());
					            mov_tiquete('out',gridTable_left.getSelection())
					        }
					    },
					    multiSearch: true,
					    searches: txtDataTable.searches,
					    columns: txtDataTable.columns
					})
					let gridTable_right = new w2grid({
					    name: 'gridTable_right',
					    show: {
					        toolbar: true
					        //footer: true
					    },
					    toolbar: {
					        items: [
					            { type: 'break' },
					            { type: 'button', id: 'MoveIn', text: 'Incluir Tiquetes', style:'color: green; font-weight: bold', icon: 'fa fa-arrow-left' }
					        ],
					        onClick: function (target, data) {
					            //console.log(target,gridTable_right.getSelection());
					            mov_tiquete('in',gridTable_right.getSelection())
					        }
					    },
					    multiSearch: true,
					    searches: txtDataTable.searches,
					    columns: txtDataTable.columns
					})
					w2ui.layout_left.html('preview',gridTable_left)
					w2ui.layout_right.html('preview',gridTable_right)
	            }
	        };
	        xhr.send(data);
		}
		function load_pre_liquidaciones(val){
			$('#pre_liquidacion').prop("disabled",true)
			$('#btn_load_liq').hide()
			let cliente = $('#cliente').val()
			let proveedor = $('#proveedor').val()
			if(cliente!='0' && cliente !=null && proveedor != '0' && proveedor !=null){
				w2ui.gridTable_left.records = '[{}]';
				w2ui.gridTable_left.reload();
				w2ui.gridTable_right.records = '[{}]';
				w2ui.gridTable_right.reload();
		        let xhr = new XMLHttpRequest();
		        let url = "../modelo/facturacion_cargador_w2ui.php?band=0";
		        xhr.open("POST", url, true);
		        let param = {cliente:cliente,proveedor:proveedor};
		        let data = JSON.stringify(param);
		        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
		        xhr.onreadystatechange = function (){
		            if (xhr.readyState === 4 && xhr.status === 200){
		                //console.log(this.responseText)
		                let response = (this.responseText)
		                array = response.split("||");
		            	$('#pre_liquidacion').html(array[0]).fadeIn()
		            	if(array[1]!=0){
		            		$('#pre_liquidacion').prop("disabled",false)
		            	}
		            	if(val!=0){
		            		$('#pre_liquidacion').val(val)
		            	}
		            	if(array[2]!=0){
		            		$('#btn_load_liq').show()
		            	}
		            	load_data_pre_liquidacion()
		            }
		        };
		        xhr.send(data);
			}
		}
		function load_pre_liquidaciones_new(val){
			$('#pre_liquidacion').prop("disabled",true)
			$('#btn_load_liq').hide()
			let select_liquidador = $('#select_liquidador').val()
			let empresa = $('#empresa').val()
			let proveedor = $('#proveedor').val()
			let reporte_variable = $('#reporte_variable').val()
			if(empresa!='0' && empresa !=null && proveedor != '0' && proveedor !=null && reporte_variable!='0' && reporte_variable!=null){
		        let xhr = new XMLHttpRequest();
		        let url = "../modelo/facturacion_cargador_w2ui.php?band=8";
		        xhr.open("POST", url, true);
		        let param = {empresa:empresa,proveedor:proveedor,liquidador:select_liquidador,reporte_variable:reporte_variable};
		        let data = JSON.stringify(param);
		        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
		        xhr.onreadystatechange = function (){
		            if (xhr.readyState === 4 && xhr.status === 200){
		                let response = (this.responseText)
		                //console.log(response)
		                array = response.split("||");
		            	$('#pre_liquidacion').html(array[0]).fadeIn()
		            	if(array[1]!=0){
		            		$('#pre_liquidacion').prop("disabled",false)
		            	}
		            	if(val!=0){	
		            		$('#pre_liquidacion').val(val)
		            	}
		            	if(array[2]!=0){
		            		$('#btn_load_liq').show()
		            	}
		            	load_data_pre_liquidacion_new()
		            }
		        };
		        xhr.send(data);
		    }
		}
		function search_tiquetes(){
			$('#btn_search').addClass('loader_min')
			let cliente = $('#cliente').val()
			let proveedor = $('#proveedor').val()
			let fechaIni = $('#fechaIni').val()
			let fechaFin = $('#fechaFin').val()
			let pre_liquidacion = $('#pre_liquidacion').val()
			let iError = 0
			if(cliente==0 || cliente=='' || cliente==null){
				$("#cliente").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#cliente").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(proveedor==0 || proveedor=='' || proveedor==null){
				$("#proveedor").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#proveedor").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(fechaIni==0 || fechaIni=='' || fechaIni==null){
				$("#fechaIni").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#fechaIni").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(fechaFin==0 || fechaFin=='' || fechaFin==null){
				$("#fechaFin").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#fechaFin").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(iError!=0){
      			alertify.error('Complete los campos marcados')
      			$('#btn_search').removeClass('loader_min')
      			$('#btn_search_new').removeClass('loader_min')
      		}else{
				let xhr = new XMLHttpRequest();
		        let url = "../modelo/facturacion_cargador_w2ui.php?band=1";
		        xhr.open("POST", url, true);
		        let param = {cliente:cliente,proveedor:proveedor,fechaIni:fechaIni,fechaFin:fechaFin};
		        let data = JSON.stringify(param);
		        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
		        xhr.onreadystatechange = function (){
		            if (xhr.readyState === 4 && xhr.status === 200){
		                let response = (this.responseText)
		                array = response.split("||");
		                records = JSON.parse(array[0])
		                w2ui.gridTable_right.records = records.records;
		                w2ui.gridTable_right.reload();
		            	$('#tbody_tiquete').html(array[0])
						if(pre_liquidacion==0 || pre_liquidacion=='' || pre_liquidacion==null){
							$('#guardar').prop("disabled",true)
						}else{
							$('#tipo_factura').prop("disabled",true)
							$('#Nuevo_reg').prop("disabled",true)
							$('#out_tiquete').prop("disabled",false)
						}
						if(array[1]!=0){
							$('#tipo_factura').prop("disabled",false)
							$('#Nuevo_reg').prop("disabled",false)
						}else{
							$('#out_tiquete').prop("disabled",true)
						}
						$('#btn_search').removeClass('loader_min')
		            }
		        };
		        xhr.send(data);
      		}
		}
		function search_tiquetes_new(){
			$('#btn_search_new').addClass('loader_min')
			let select_liquidador = $('#select_liquidador').val()
			let proveedor = $('#proveedor').val()
			let reporte_variable = $('#reporte_variable').val()
			let fechaIni = $('#fechaIni').val()
			let fechaFin = $('#fechaFin').val()
			let pre_liquidacion = $('#pre_liquidacion').val()
			let iError = 0
			if(proveedor==0 || proveedor=='' || proveedor==null){
				$("#proveedor").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#proveedor").prop("style","border: 1px solid; border-color: #ccc")
      		}
			if(reporte_variable==0 || reporte_variable=='' || reporte_variable==null){
				$("#reporte_variable").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#reporte_variable").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(fechaIni==0 || fechaIni=='' || fechaIni==null){
				$("#fechaIni").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#fechaIni").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(fechaFin==0 || fechaFin=='' || fechaFin==null){
				$("#fechaFin").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#fechaFin").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(iError!=0){
      			alertify.error('Complete los campos marcados')
      			$('#btn_search_new').removeClass('loader_min')
      		}else{
				let xhr = new XMLHttpRequest();
		        let url = "../modelo/facturacion_cargador_w2ui.php?band=7";
		        xhr.open("POST", url, true);
		        let param = {reporte_variable:reporte_variable,proveedor:proveedor,select_liquidador:select_liquidador,fechaIni:fechaIni,fechaFin:fechaFin};
		        let data = JSON.stringify(param);
		        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
		        xhr.onreadystatechange = function (){
		            if (xhr.readyState === 4 && xhr.status === 200){
		                let response = (this.responseText)
		                array = response.split("||");
						records = JSON.parse(array[0])
		                w2ui.gridTable_right.records = records.records;
		                w2ui.gridTable_right.reload();
						if(pre_liquidacion==0 || pre_liquidacion=='' || pre_liquidacion==null){
							$('#guardar').prop("disabled",true)
						}else{
							$('#Nuevo_reg').prop("disabled",true)
							$('#out_tiquete').prop("disabled",false)
						}
						if(array[1]!=0){
							$('#Nuevo_reg').prop("disabled",false)
							$('#out_tiquete').prop("disabled",false)
						}else{
							$('#out_tiquete').prop("disabled",true)
						}
						$('#btn_search_new').removeClass('loader_min')
		            }
		        }
		        xhr.send(data);
      		}
		}
		function Nueva_liquidacion(){
			let cliente = $('#cliente').val()
			let proveedor = $('#proveedor').val()
			let tipo_factura = $('#tipo_factura').val()
			let iError = 0
			if(tipo_factura=='' || tipo_factura==null){
				$("#tipo_factura").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#tipo_factura").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(cliente==0 || cliente=='' || cliente==null){
				$("#cliente").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#cliente").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(proveedor==0 || proveedor=='' || proveedor==null){
				$("#proveedor").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#proveedor").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(iError!=0){
      			alertify.error('Complete los campos marcados')
      		}else{
				let xhr = new XMLHttpRequest();
		        let url = "../modelo/facturacion_cargador_w2ui.php?band=2";
		        xhr.open("POST", url, true);
		        let param = {tipo_factura:tipo_factura,cliente:cliente,proveedor:proveedor};
		        let data = JSON.stringify(param);
		        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
		        xhr.onreadystatechange = function (){
		            if (xhr.readyState === 4 && xhr.status === 200){
		                let response = (this.responseText)
		                if(response==0){
							alertify.error('Ya hay una pre-liquidación vigente')
						}else if(response==2){
							alertify.warning('No se pudo guardar correctamente')
						}else{
							load_pre_liquidaciones(response)
						}
		            }
		        };
		        xhr.send(data);
      		}
		}
		function Nueva_liquidacion_new(){
			let proveedor = $('#proveedor').val()
			let select_liquidador = $('#select_liquidador').val()
			let reporte_variable = $('#reporte_variable').val()
			let iError = 0
      		if(proveedor==0 || proveedor=='' || proveedor==null){
				$("#proveedor").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#proveedor").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(reporte_variable==0 || reporte_variable=='' || reporte_variable==null){
				$("#reporte_variable").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#reporte_variable").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(iError!=0){
      			alertify.error('Complete los campos marcados')
      		}else{
				let xhr = new XMLHttpRequest();
		        let url = "../modelo/facturacion_cargador_w2ui.php?band=9";
		        xhr.open("POST", url, true);
		        let param = {select_liquidador:select_liquidador,reporte_variable:reporte_variable,proveedor:proveedor};
		        let data = JSON.stringify(param);
		        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
		        xhr.onreadystatechange = function (){
		            if (xhr.readyState === 4 && xhr.status === 200){
		                let response = (this.responseText)
		                if(response==0){
							alertify.error('Ya hay una pre-liquidación vigente')
						}else if(response==2){
							alertify.warning('No se pudo guardar correctamente')
						}else{
							load_pre_liquidaciones_new(response)
						}
		            }
		        };
		        xhr.send(data);
      		}
		}
		function load_data_pre_liquidacion(){
			let pre_liquidacion = $('#pre_liquidacion').val()
			if(pre_liquidacion==0){
				$('#tipo_factura').prop("disabled",false)
				$('#Nuevo_reg').prop("disabled",false)
				$('#out_tiquete').prop("disabled",true)
				$('#title_horas').val('')
				$('#title_tiquete').val('')
				$('#title_valor').val('')
				$('#title_pre_liquidacion').html('Pre-liquidacion NUEVA').fadeIn()
				$('#title_factura').html('Pre-liquidacion NUEVA').fadeIn()
				w2ui.gridTable_left.records = '[{}]';
                w2ui.gridTable_left.reload();
			}else if(pre_liquidacion==-1){
				$('#guardar').prop("disabled",true)
				$('#fecha_fact_asentada').prop("disabled",true)
				$('#fact_asociada').prop("disabled",true)
				$('#in_tiquete').prop("disabled",true)
				$('#tipo_factura').prop("disabled",true)
				$('#Nuevo_reg').prop("disabled",true)
				$('#out_tiquete').prop("disabled",true)
				$('#title_horas').val('')
				$('#title_tiquete').val('')
				$('#title_valor').val('')
				$('#title_pre_liquidacion').html('Pre-liquidacion NUEVA').fadeIn()
				$('#title_factura').html('Pre-liquidacion NUEVA').fadeIn()
				w2ui.gridTable_left.records = '[{}]';
                w2ui.gridTable_left.reload();
			}else{
				let xhr = new XMLHttpRequest();
		        let url = "../modelo/facturacion_cargador_w2ui.php?band=3";
		        xhr.open("POST", url, true);
		        let param = {pre_liquidacion:pre_liquidacion};
		        let data = JSON.stringify(param);
		        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
		        xhr.onreadystatechange = function (){
		            if (xhr.readyState === 4 && xhr.status === 200){
		                let response = (this.responseText)
		                if(response==0){
							$('#guardar').prop("disabled",true)
							$('#fecha_fact_asentada').prop("disabled",true)
							$('#fact_asociada').prop("disabled",true)
							$('#in_tiquete').prop("disabled",true)
							$('#tipo_factura').prop("disabled",true)
							$('#Nuevo_reg').prop("disabled",true)
							$('#out_tiquete').prop("disabled",true)
							$('#title_horas').val('')
							$('#title_tiquete').val('')
							$('#title_valor').val('')
							$('#title_pre_liquidacion').html('Pre-liquidacion NUEVA').fadeIn()
							$('#title_factura').html('Pre-liquidacion NUEVA').fadeIn()
							w2ui.gridTable_left.records = '[{}]';
			                w2ui.gridTable_left.reload();
						}else{
							array = response.split("||")
							$('#fecha_pre_liquidacion').val(array[0])
							$('#tipo_factura').val(array[1])
							$('#title_horas').val(array[2])
							$('#title_tiquete').val(array[3])
							$('#title_valor').val(array[4])
							$('#title_pre_liquidacion').html('Pre-liquidacion # '+array[5]).fadeIn()
							$('#title_factura').html('Pre-liquidacion # '+array[5]).fadeIn()
							records = JSON.parse(array[6]);
			                w2ui.gridTable_left.records = records.records;
			                w2ui.gridTable_left.reload();
							$('#tipo_factura').prop("disabled",true)
							$('#Nuevo_reg').prop("disabled",true)
							$('#out_tiquete').prop("disabled",false)
							if(array[3]!=0){
								$('#guardar').prop("disabled",false)
								$('#fecha_fact_asentada').prop("disabled",false)
								$('#fact_asociada').prop("disabled",false)
								$('#in_tiquete').prop("disabled",false)
							}else{
								$('#guardar').prop("disabled",true)
								$('#fecha_fact_asentada').prop("disabled",true)
								$('#fact_asociada').prop("disabled",true)
								$('#in_tiquete').prop("disabled",true)
							}
						}
		            }
		        };
		        xhr.send(data);
	  		}
		}
		function load_data_pre_liquidacion_new(){
			let pre_liquidacion = $('#pre_liquidacion').val()
			if(pre_liquidacion==0){
				$('#Nuevo_reg').prop("disabled",false)
				$('#guardar').prop("disabled",true)
				$('#title_horas').val('')
				$('#title_tiquete').val('')
				$('#title_valor').val('')
				$('#title_pre_liquidacion').html('Pre-liquidacion NUEVA').fadeIn()
				$('#title_factura').html('FPre-liquidacion NUEVA').fadeIn()
				w2ui.gridTable_left.records = '[{}]';
                w2ui.gridTable_left.reload();
			}else if(pre_liquidacion==-1){
				$('#Nuevo_reg').prop("disabled",true)
				$('#guardar').prop("disabled",true)
				$('#fecha_fact_asentada').prop("disabled",true)
				$('#fact_asociada').prop("disabled",true)
				$('#title_horas').val('')
				$('#title_tiquete').val('')
				$('#title_valor').val('')
				$('#title_pre_liquidacion').html('Pre-liquidacion NUEVA').fadeIn()
				$('#title_factura').html('FPre-liquidacion NUEVA').fadeIn()
				w2ui.gridTable_left.records = '[{}]';
                w2ui.gridTable_left.reload();
			}else{
				let xhr = new XMLHttpRequest();
		        let url = "../modelo/facturacion_cargador_w2ui.php?band=10";
		        xhr.open("POST", url, true);
		        let param = {pre_liquidacion:pre_liquidacion};
		        let data = JSON.stringify(param);
		        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
		        xhr.onreadystatechange = function (){
		            if (xhr.readyState === 4 && xhr.status === 200){
		                let response = (this.responseText)
		                //console.log(response)
		                if(response==0){
							$('#Nuevo_reg').prop("disabled",true)
							$('#guardar').prop("disabled",true)
							$('#fecha_fact_asentada').prop("disabled",true)
							$('#fact_asociada').prop("disabled",true)
							$('#title_horas').val('')
							$('#title_tiquete').val('')
							$('#title_valor').val('')
							$('#title_pre_liquidacion').html('Pre-liquidacion NUEVA').fadeIn()
							$('#title_factura').html('FPre-liquidacion NUEVA').fadeIn()
							w2ui.gridTable_left.records = '[{}]';
			                w2ui.gridTable_left.reload();
						}else{
							array = response.split("||")
							$('#fecha_pre_liquidacion').val(array[0])
							$('#title_horas').val(array[1])
							$('#title_tiquete').val(array[2])
							$('#title_valor').val(array[3])
							$('#title_pre_liquidacion').html('Pre-liquidacion # '+array[4]).fadeIn()
							$('#title_factura').html('Pre-liquidacion # '+array[4]).fadeIn()
							records = JSON.parse(array[5]);
			                w2ui.gridTable_left.records = records.records;
			                w2ui.gridTable_left.reload();
							$('#Nuevo_reg').prop("disabled",true)
							if(array[2]!=0){
								$('#guardar').prop("disabled",false)
								$('#fecha_fact_asentada').prop("disabled",false)
								$('#fact_asociada').prop("disabled",false)
							}else{
								$('#guardar').prop("disabled",true)
								$('#fecha_fact_asentada').prop("disabled",true)
								$('#fact_asociada').prop("disabled",true)
							}
						}
		            }
		        };
		        xhr.send(data);
	  		}
		}
		function mov_tiquete(ioption,registers){
			let pre_liquidacion = $('#pre_liquidacion').val()
       		if($('#select_liquidador').val()==-1){
       			band = 4
       		}else{
       			band = 11
       		}
			let xhr = new XMLHttpRequest();
	        let url = "../modelo/facturacion_cargador_w2ui.php?band=" + band;
	        xhr.open("POST", url, true);
	        let param = {pre_liquidacion:pre_liquidacion,array_id:registers,ioption:ioption};
	        let data = JSON.stringify(param);
	        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
	        xhr.onreadystatechange = function (){
	            if (xhr.readyState === 4 && xhr.status === 200){
	                let response = (this.responseText)
	                //console.log(response)
	                if(response==1){
						if($('#select_liquidador').val()==-1){
						// 	search_tiquetes()
							load_data_pre_liquidacion()
						}else{
						// 	search_tiquetes_new()
							load_data_pre_liquidacion_new()
						}
						for (var i = registers.length - 1; i >= 0; i--) {
							if(ioption=='in'){
								//w2ui.gridTable_right
								w2ui.gridTable_left.add(w2utils.extend({}, w2ui.gridTable_right.get(registers[i]), { selected : false }))
					            w2ui.gridTable_right.selectNone()
					            w2ui.gridTable_right.remove(registers[i])
							}else if(ioption=='out'){
								//w2ui.gridTable_left
								w2ui.gridTable_right.add(w2utils.extend({}, w2ui.gridTable_left.get(registers[i]), { selected : false }) )
					            w2ui.gridTable_left.selectNone()
					            w2ui.gridTable_left.remove(registers[i])
							}
						}
						if(w2ui.gridTable_left.total>0){
							$('#guardar').prop("disabled",false)
						}else{
							$('#guardar').prop("disabled",true)
						}
					}else{
						alertify.error('No se pudo guardar correctamente')
					}
	            }
	        }
	        xhr.send(data);
		}
		function asentar_liquidacion(){
			let fecha_fact_asentada = $('#fecha_fact_asentada').val()
			let fact_asociada = $('#fact_asociada').val()
			let pre_liquidacion = $('#pre_liquidacion').val()
			let proveedor = $('#proveedor').val()
			let select_liquidador = $('#select_liquidador').val()
			let iError = 0
			if(fecha_fact_asentada=='' || fecha_fact_asentada==null){
				$("#fecha_fact_asentada").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#fecha_fact_asentada").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(fact_asociada=='' || fact_asociada==null){
				$("#fact_asociada").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#fact_asociada").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(pre_liquidacion=='' || pre_liquidacion==null){
				$("#pre_liquidacion").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#pre_liquidacion").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(proveedor=='' || proveedor==null){
				$("#proveedor").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#proveedor").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(select_liquidador=='' || select_liquidador==null){
				$("#select_liquidador").prop("style","border: 1px solid; border-color: red")
				iError = iError + 1
			}else{
      			$("#select_liquidador").prop("style","border: 1px solid; border-color: #ccc")
      		}
      		if(select_liquidador==-1){
      			var cliente = $('#cliente').val()
	      		if(cliente=='' || cliente==null){
					$("#cliente").prop("style","border: 1px solid; border-color: red")
					iError = iError + 1
				}else{
	      			$("#cliente").prop("style","border: 1px solid; border-color: #ccc")
	      		}
      		}else{
	      		var empresa = $('#empresa').val()
	      		if(empresa=='' || empresa==null){
					$("#empresa").prop("style","border: 1px solid; border-color: red")
					iError = iError + 1
				}else{
	      			$("#reporte_variable").prop("style","border: 1px solid; border-color: #ccc")
	      		}
	      		var reporte_variable = $('#reporte_variable').val()
	      		if(reporte_variable=='' || reporte_variable==null){
					$("#reporte_variable").prop("style","border: 1px solid; border-color: red")
					iError = iError + 1
				}else{
	      			$("#reporte_variable").prop("style","border: 1px solid; border-color: #ccc")
	      		}
	      	}
      		if(iError!=0){
      			alertify.error('Complete los campos marcados')
      		}else{
      			$('#modalHistorialLiquidaciones').modal('show')
      			$('#tittle_modal_historial').html('')
      			$('#div_menu_modal_historial').html('')
      			let param = ''
      			if(select_liquidador==-1){
      				//band = 5;
      				band = 16;
	  				param = {fecha_fact_asentada:fecha_fact_asentada,fact_asociada:fact_asociada,pre_liquidacion:pre_liquidacion};
      			}else{
      				//band = 12;
      				band = 17;
	  				param = {fecha_fact_asentada:fecha_fact_asentada,fact_asociada:fact_asociada,pre_liquidacion:pre_liquidacion,proveedor:proveedor,select_liquidador:select_liquidador,reporte_variable:reporte_variable,empresa:empresa};
      			}
				let xhr = new XMLHttpRequest(); 
		        let url = "../modelo/facturacion_cargador_w2ui.php?band=" + band;
		        xhr.open("POST", url, true);
		        let data = JSON.stringify(param);
		        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
		        xhr.onreadystatechange = function (){
		            if (xhr.readyState === 4 && xhr.status === 200){
		                let response = (this.responseText)
		                console.log(response)
		                response = response.split("||");
		                $('#tittle_modal_historial').html(response[0])
		                $('#div_menu_modal_historial').html(response[1]).fadeIn()
		                /*if(response==1){
							alert('Se liquidó correctamente')
							load_body_liquidaciones()
						}else if(response==0){
							alertify.warning('No coincide la pre-liquidación con los valores seleccionados')
						}else{
							alertify.error('No se pudo guardar correctamente')
						}*/
		            }
		        }
		        xhr.send(data);
      		}
		}
		function descargar_pdf_historial(idLiquidacion){
			let select_liquidador = $('#select_liquidador').val()
			let xhr = new XMLHttpRequest();
	        let url = "../modelo/facturacion_cargador_w2ui.php?band=13";
	        xhr.open("POST", url, true);
	        let param = {select_liquidador:select_liquidador,pre_liquidacion:idLiquidacion};
	        let data = JSON.stringify(param);
	        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
	        xhr.onreadystatechange = function (){
	            if (xhr.readyState === 4 && xhr.status === 200){
	                let response = (this.responseText)
	                $('#input_contenido_op').val(response);
				    $("#form_pdf").attr("target", "blank");
					$("#form_pdf").attr("action", "../modelo/descargar_op_pdf.php");
				   	$("#form_pdf").submit();
	            }
	        }
	        xhr.send(data);
		}
		function search_historial_liquidaciones(){
			$('#div_menu_modal_historial').html('').fadeIn()
			let select_liquidador = $('#select_liquidador').val()
			let proveedor = $('#proveedor').val()
			let variable = ''
			if(select_liquidador==-1){
				variable = $('#cliente').val()
			}else{
				variable = $('#reporte_variable').val()
			}
			let xhr = new XMLHttpRequest();
	        let url = "../modelo/facturacion_cargador_w2ui.php?band=14";
	        xhr.open("POST", url, true);
	        let param = {select_liquidador:select_liquidador,proveedor:proveedor,variable:variable};
	        let data = JSON.stringify(param);
	        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
	        xhr.onreadystatechange = function (){
	            if (xhr.readyState === 4 && xhr.status === 200){
	                let response = (this.responseText)
	                //console.log(response)
	                $('#div_menu_modal_historial').html(response).fadeIn()
	            }
	        }
	        xhr.send(data);
		}
		function desasentar(idLiquidacion){
			let select_liquidador = $('#select_liquidador').val()
			let xhr = new XMLHttpRequest();
	        let url = "../modelo/facturacion_cargador_w2ui.php?band=15";
	        xhr.open("POST", url, true);
	        let param = {select_liquidador:select_liquidador,variable:idLiquidacion};
	        let data = JSON.stringify(param);
	        xhr.setRequestHeader("Content-Type", 'application/json; charset=utf-8');           
	        xhr.onreadystatechange = function (){
	            if (xhr.readyState === 4 && xhr.status === 200){
	                let response = (this.responseText)
	                //console.log(response)
	                if(response==1){
	                	alertify.success('Se desasentó la factura')
	                	search_historial_liquidaciones()
	                	load_pre_liquidaciones_new(0)
	                }else{
	                	alertify.error('No se pudo ejecutar')
	                }
	            }
	        }
	        xhr.send(data);
		}
	</script>
</head>
<body>
	<?php include './Header.php';?>
	<div id="layout" style="width: 100vw; height:94vh;"></div>
	<div class="modal fade" id="modalHistorialLiquidaciones" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="tittle_modal_historial"></h4>
            </div>
            <div id="div_menu_modal_historial"></div>
        </div>
	</div>
	<form  name="form_pdf" id="form_pdf"  method="post" enctype="multipart/form-data">
		<!--<input name="operacion" id="operacion" type="hidden" value="">-->
		<input type="hidden" name="input_contenido" id="input_contenido">
		<input type="hidden" name="input_contenido_op" id="input_contenido_op">
	</form>
</body>
</html>