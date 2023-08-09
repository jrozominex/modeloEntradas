<?php
if(!isset($_SESSION["logueado"])){
	session_start();
}
require_once '../modelo/conexion.php';
$idUsuario = $_SESSION['idUsuario'];
if(!isset($_SESSION['Array_empresa']['PRODUCCION'])){
	?>
  <script type="text/javascript">
      //self.location='Admin.php';
      //alert('No tienes permiso para acceder a este ambiente');
  </script>
  <?php
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="initial-scale=0.7">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
	<title>Modulo Producción</title>
	<script src="../../libreria/jquery-3.3.1.min.js"></script>
	<script src="../../libreria/jquery.min.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="../../libreria/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../../libreria/alertifyjs/css/alertify.css">
	<link rel="stylesheet" type="text/css" href="../../libreria/alertifyjs/css/themes/bootstrap.css">
	<link rel="stylesheet" href="../../libreria/css/estilo_esperar.css">
	<link rel="stylesheet" href="../librerias/shadowbox.css">
	<script src="../librerias/shadowbox.js"></script>
	<script src="../../libreria/bootstrap/js/bootstrap.js"></script>
	<script src="../../libreria/alertifyjs/alertify.js"></script>
	<script src="../librerias/moment.js" type="text/javascript"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
	<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>-->
	<script type="text/javascript" src="https://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.css" />
  <!-- <script src="http://trazapp.minex.com.co:81/financiero/cajaMenor/users.js"></script> -->
	<style type="text/css">
		.black_letters{
			font-weight: bolder;
			color: #000000 !important;
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

		/*thead tr th{
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #ffffff;
        text-align: center;
        vertical-align: middle;
    }*/
    .table-responsive1{ 
        height:75vh;
        overflow:scroll;
    }
		.blue{
			border-radius: 5px;
			background-color: #64C7CD;
		}
		.select_others{
			border-radius: 5px;
			background-color: #CF8727;
		}
		.loader{
		  font-size: 20px;
		  margin: 45% auto;
		  width: 1em;
		  height: 1em;
		  border-radius: 50%;
		  position: center;
		  text-indent: -9999em;
		  -webkit-animation: load4 1.3s infinite linear;
		  animation: load 4 1.3s infinite linear;
		}
		@-webkit-keyframes load4 {
		  0%,
		  100% {
		    box-shadow: 0em -3em 0em 0.2em #DFB12D, 2em -2em 0 0em #DFB12D, 3em 0em 0 -0.5em #DFB12D, 2em 2em 0 -0.5em #DFB12D, 0em 3em 0 -0.5em #DFB12D, -2em 2em 0 -0.5em #DFB12D, -3em 0em 0 -0.5em #DFB12D, -2em -2em 0 0em #DFB12D;
		  }
		  12.5% {
		    box-shadow: 0em -3em 0em 0em #ffffff, 2em -2em 0 0.2em #ffffff, 3em 0em 0 0em #ffffff, 2em 2em 0 -0.5em #ffffff, 0em 3em 0 -0.5em #ffffff, -2em 2em 0 -0.5em #ffffff, -3em 0em 0 -0.5em #ffffff, -2em -2em 0 -0.5em #ffffff;
		  }
		  25% {
		    box-shadow: 0em -3em 0em -0.5em #ffffff, 2em -2em 0 0em #ffffff, 3em 0em 0 0.2em #ffffff, 2em 2em 0 0em #ffffff, 0em 3em 0 -0.5em #ffffff, -2em 2em 0 -0.5em #ffffff, -3em 0em 0 -0.5em #ffffff, -2em -2em 0 -0.5em #ffffff;
		  }
		  37.5% {
		    box-shadow: 0em -3em 0em -0.5em #ffffff, 2em -2em 0 -0.5em #ffffff, 3em 0em 0 0em #ffffff, 2em 2em 0 0.2em #ffffff, 0em 3em 0 0em #ffffff, -2em 2em 0 -0.5em #ffffff, -3em 0em 0 -0.5em #ffffff, -2em -2em 0 -0.5em #ffffff;
		  }
		  50% {
		    box-shadow: 0em -3em 0em -0.5em #ffffff, 2em -2em 0 -0.5em #ffffff, 3em 0em 0 -0.5em #ffffff, 2em 2em 0 0em #ffffff, 0em 3em 0 0.2em #ffffff, -2em 2em 0 0em #ffffff, -3em 0em 0 -0.5em #ffffff, -2em -2em 0 -0.5em #ffffff;
		  }
		  62.5% {
		    box-shadow: 0em -3em 0em -0.5em #ffffff, 2em -2em 0 -0.5em #ffffff, 3em 0em 0 -0.5em #ffffff, 2em 2em 0 -0.5em #ffffff, 0em 3em 0 0em #ffffff, -2em 2em 0 0.2em #ffffff, -3em 0em 0 0em #ffffff, -2em -2em 0 -0.5em #ffffff;
		  }
		  75% {
		    box-shadow: 0em -3em 0em -0.5em #ffffff, 2em -2em 0 -0.5em #ffffff, 3em 0em 0 -0.5em #ffffff, 2em 2em 0 -0.5em #ffffff, 0em 3em 0 -0.5em #ffffff, -2em 2em 0 0em #ffffff, -3em 0em 0 0.2em #ffffff, -2em -2em 0 0em #ffffff;
		  }
		  87.5% {
		    box-shadow: 0em -3em 0em 0em #ffffff, 2em -2em 0 -0.5em #ffffff, 3em 0em 0 -0.5em #ffffff, 2em 2em 0 -0.5em #ffffff, 0em 3em 0 -0.5em #ffffff, -2em 2em 0 0em #ffffff, -3em 0em 0 0em #ffffff, -2em -2em 0 0.2em #ffffff;
		  }
		}
		@keyframes load4 {
		  0%,
		  100% {
		    box-shadow: 0em -3em 0em 0.2em #DFB12D, 2em -2em 0 0em #DFB12D, 3em 0em 0 -0.5em #DFB12D, 2em 2em 0 -0.5em #DFB12D, 0em 3em 0 -0.5em #DFB12D, -2em 2em 0 -0.5em #DFB12D, -3em 0em 0 -0.5em #DFB12D, -2em -2em 0 0em #DFB12D;
		  }
		  12.5% {
		    box-shadow: 0em -3em 0em 0em #DF932D, 2em -2em 0 0.2em #DF932D, 3em 0em 0 0em #DF932D, 2em 2em 0 -0.5em #DF932D, 0em 3em 0 -0.5em #DF932D, -2em 2em 0 -0.5em #DF932D, -3em 0em 0 -0.5em #DF932D, -2em -2em 0 -0.5em #DF932D;
		  }
		  25% {
		    box-shadow: 0em -3em 0em -0.5em #DF632D, 2em -2em 0 0em #DF632D, 3em 0em 0 0.2em #DF632D, 2em 2em 0 0em #DF632D, 0em 3em 0 -0.5em #DF632D, -2em 2em 0 -0.5em #DF632D, -3em 0em 0 -0.5em #DF632D, -2em -2em 0 -0.5em #DF632D;
		  }
		  37.5% {
		    box-shadow: 0em -3em 0em -0.5em #DF502D, 2em -2em 0 -0.5em #DF502D, 3em 0em 0 0em #DF502D, 2em 2em 0 0.2em #DF502D, 0em 3em 0 0em #DF502D, -2em 2em 0 -0.5em #DF502D, -3em 0em 0 -0.5em #DF502D, -2em -2em 0 -0.5em #DF502D;
		  }
		  50% {
		    box-shadow: 0em -3em 0em -0.5em #DF632D, 2em -2em 0 -0.5em #DF632D, 3em 0em 0 -0.5em #DF632D, 2em 2em 0 0em #DF632D, 0em 3em 0 0.2em #DF632D, -2em 2em 0 0em #DF632D, -3em 0em 0 -0.5em #DF632D, -2em -2em 0 -0.5em #DF632D;
		  }
		  62.5% {
		    box-shadow: 0em -3em 0em -0.5em #DF932D, 2em -2em 0 -0.5em #DF932D, 3em 0em 0 -0.5em #DF932D, 2em 2em 0 -0.5em #DF932D, 0em 3em 0 0em #DF932D, -2em 2em 0 0.2em #DF932D, -3em 0em 0 0em #DF932D, -2em -2em 0 -0.5em #DF932D;
		  }
		  75% {
		    box-shadow: 0em -3em 0em -0.5em #DFB12D, 2em -2em 0 -0.5em #DFB12D, 3em 0em 0 -0.5em #DFB12D, 2em 2em 0 -0.5em #DFB12D, 0em 3em 0 -0.5em #DFB12D, -2em 2em 0 0em #DFB12D, -3em 0em 0 0.2em #DFB12D, -2em -2em 0 0em #DFB12D;
		  }
		  87.5% {
		    box-shadow: 0em -3em 0em 0em #DFC42D, 2em -2em 0 -0.5em #DFC42D, 3em 0em 0 -0.5em #DFC42D, 2em 2em 0 -0.5em #DFC42D, 0em 3em 0 -0.5em #DFC42D, -2em 2em 0 0em #DFC42D, -3em 0em 0 0em #DFC42D, -2em -2em 0 0.2em #DFC42D;
		  }
		}
	</style>
	<script language="JavaScript">
		$(document).ready(function(){
			$('#nav_menu_opciones').hide();
			$('#btn_abrir_produccion').hide();
			$('#btn_abrir_alimentacion').hide();
			$('#div_informativo').hide();
		});
		function datatable(){
      var table_example1 = $('#example1').DataTable({
          "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
          "language": {
              "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
          },
          "order": [ 0, 'desc' ],
          "ordering": true,
          "info":     true,
          stateSave: true/*,
          //scrollY:        '50vh',
          //"scrollX": true,
          //"scrollCollapse": true,
          //"paging":         true*/
      });
    }
		function guardar_lugar_trabajo(){
			var lugar_trabajo = $('#lugar_trabajo').val();
			if(lugar_trabajo == '' || lugar_trabajo == null){
				alert('Seleccione una planta');
			}else{
				cadena = "band=" + 1 +
						"&lugar_trabajo=" + lugar_trabajo;
				$.ajax({
            type: "POST",
            url: "buscar_v2.php",
            data: cadena,
            success: function (r){
            	separ = r.split("||");
            	$('#div_menu_body').html('').fadeIn();
            	if(separ[0] != '0'){
            		$('#btn_seleccionar_planta').hide();
            		$('#nav_menu_opciones').show();
            		$('#modalSeleccionarPatio').modal('hide');
        				$('#lugar_trabajo_produccion').val(separ[0]);
        				$('#nav_menu_opciones').html(separ[1]);
        				$('#div_informativo').html(separ[2]);
            	}else{
        			$('#modalSeleccionarPatio').modal('show');
        		}
            }
        });
			}
		}
		function cargar_menu_body(ioption,constante){
			$('.onclick_options').removeClass('black_letters')
			$('.'+ioption).addClass('black_letters')
			var site_work = $('#lugar_trabajo_produccion').val()
			if(site_work != 0){
				if(ioption!=2){
					$('#div_informativo').hide();
				}else{
					$('#div_informativo').show();
				}
				$('#btn_abrir_alimentacion').hide();
				$('#btn_abrir_produccion').hide();
				cadena = "band=" + ioption +
						"&lugar_trabajo=" + $('#lugar_trabajo_produccion').val();
				$.ajax({
            type: "POST",
            url: "buscar_v2.php",
            data: cadena,
            success: function (r){
            	//console.log(r)
            	$('#div_menu_body').html(r).fadeIn();
            	if(ioption==32){
	            	datatable()
	            }
            	$('.btn_ocultar').hide();
            	if(ioption == 0){
            		$('#div_modificar_prod').hide();
		            //$('#div_consulta_clasif').hide();
		            //$('#div_registro_clasif').hide();
            	}else if(ioption == 2){
	            	var checked_hornos = $('#checked_hornos').val();
	            	if(checked_hornos != ''){
	            		checked_hornos = checked_hornos.split(",");
	            		for (var i = 0; i < checked_hornos.length; i++){
	            			marcar(checked_hornos[i]);
	            		}
	            	}
	            }else{
	            	$('#checked_hornos').val('');
	            }
	            if(constante!='0'){
	            	edition_hornos(constante)
	            }
            }
        });
			}
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
		function load_body_inventory(){
			$('#btn_actualizar_inv').addClass('loader_min')
			$('#btn_actualizar_inv').prop("disabled",true)
			$('#div_inventario_body').html('')
			let idEmpresa = $('#list_empresa_inventario').val()
			let idUnidadNegocio = $('#list_UnidadDeNegocio_inventario').val()
			var idDestino = ''
			if($('#lugar_trabajo_produccion').val()==-1){
				idDestino = $('#idDestinoReport').val()
			}else{
				idDestino = $('#lugar_trabajo_produccion').val()
			}
			let FechaInicioSaldo = $('#fechaInicio_inventario').val()
			let FechaFinSaldo = $('#fechaFin_inventario').val()
			if(FechaInicioSaldo<=FechaFinSaldo){
				cadena = "band=" + 22 +
						"&idEmpresa=" + idEmpresa +
						"&idUnidadNegocio=" + idUnidadNegocio +
						"&idDestino=" + idDestino +
						"&FechaInicioSaldo=" + FechaInicioSaldo +
						"&FechaFinSaldo=" + FechaFinSaldo;
				$.ajax({
	          type: "POST",
	          url: "buscar_v2.php",
	          data: cadena,
	          success: function(r){
	          	//console.log(r)
	          	$('#btn_actualizar_inv').removeClass('loader_min')
	          	$('#btn_actualizar_inv').prop("disabled",false)
	          	$('#div_inventario_body').html(r)
	          	$('#tabla_excel').val(r)
	          	var table = $('#table_inventory_detail').DataTable({
				          "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
				          "language": {
				              "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
				          },
				          /*"ordering": true,
				          "info":     true,
				          stateSave: true/*,
				          scrollY:        '50vh',
				          "scrollX": true,
				          "scrollCollapse": true,
				          "paging":         true*/
				      });
	          }
	      });
			}else{
				alertify.error('La fecha inicial debe ser menor');
				$('#btn_actualizar_inv').removeClass('loader_min')
	      $('#btn_actualizar_inv').prop("disabled",false)
			}
		}
		function load_body_inventory_pila(){
			$('#btn_actualizar_inv').addClass('loader_min')
			$('#btn_actualizar_inv').prop("disabled",true)
			$('#div_inventario_body').html('')
			let idEmpresa = $('#list_empresa_inventario').val()
			let idUnidadNegocio = $('#list_UnidadDeNegocio_inventario').val()
			let idPila = $('#list_pila_inventario').val()
			var idDestino = ''
			if($('#lugar_trabajo_produccion').val()==-1){
				idDestino = $('#idDestinoReport').val()
			}else{
				idDestino = $('#lugar_trabajo_produccion').val()
			}
			let FechaInicioSaldo = $('#fechaInicio_inventario').val()
			let FechaFinSaldo = $('#fechaFin_inventario').val()
			if(FechaInicioSaldo<=FechaFinSaldo){
				cadena = "band=" + 50 +
						"&idEmpresa=" + idEmpresa +
						"&idUnidadNegocio=" + idUnidadNegocio +
						"&idPila=" + idPila +
						"&idDestino=" + idDestino +
						"&FechaInicioSaldo=" + FechaInicioSaldo +
						"&FechaFinSaldo=" + FechaFinSaldo;
				$.ajax({
	          type: "POST",
	          url: "buscar_v2.php",
	          data: cadena,
	          success: function(r){
	          	let response =r.split("||");
	          	 console.log(r)
	          	global_excel = JSON.parse(response[1]);

	          	$('#btn_actualizar_inv').removeClass('loader_min')
	          	$('#btn_actualizar_inv').prop("disabled",false)
	          	let json = JSON.parse(response[0]);
	          	$().w2destroy('grid');
	          	let grid = new w2grid({
							    name: 'grid',
							    box: '#div_inventario_body',
							    searches: [
							        { field: 'lname', text: 'Last Name', type: 'text' },
							        { field: 'fname', text: 'First Name', type: 'text' },
							        { field: 'email', text: 'Email', type: 'text' },
							    ],
							    show: { footer: true },
							    sortData: [ { field: 'recid', direction: 'asc' } ],
							    columnGroups:json.columnsGroups,
							    columns: json.columns,
							    records: json.records
							})
	          	//$('#div_inventario_body').html(r) //DESCOMENTAR

	          	
	          	/*var table = $('#table_inventory_detail').DataTable({
				          "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
				          "language": {
				              "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
				          },
				          "ordering": true,
				          "info":     true
				      });*/
	          }
	      });
			}else{
				alertify.error('La fecha inicial debe ser menor');
				$('#btn_actualizar_inv').removeClass('loader_min')
	      $('#btn_actualizar_inv').prop("disabled",false)
			}
		}
		function load_pila_inv(){
			let idEmpresa = $('#list_empresa_inventario').val()
			let idUnidadNegocio = $('#list_UnidadDeNegocio_inventario').val()
			var idDestino = ''
			if($('#lugar_trabajo_produccion').val()==-1){
				idDestino = $('#idDestinoReport').val()
			}else{
				idDestino = $('#lugar_trabajo_produccion').val()
			}
			let FechaInicioSaldo = $('#fechaInicio_inventario').val()
			let FechaFinSaldo = $('#fechaFin_inventario').val()
			if(FechaInicioSaldo<=FechaFinSaldo){
				cadena = "band=" + 51 +
						"&idEmpresa=" + idEmpresa +
						"&idUnidadNegocio=" + idUnidadNegocio +
						"&idDestino=" + idDestino +
						"&FechaInicioSaldo=" + FechaInicioSaldo +
						"&FechaFinSaldo=" + FechaFinSaldo;
				$.ajax({
	          type: "POST",
	          url: "buscar_v2.php",
	          data: cadena,
	          success: function(r){
	          	//console.log(r)
	          	$('#list_pila_inventario').html(r).fadeIn()
	          }
	      });
			}else{
				alertify.error('La fecha inicial debe ser menor');
			}
		}

		//
		function load_pila_saldo_inventario(pila){
				
				cadena = "band=" + 52 +
						"&pila=" + pila;
				$.ajax({
	          type: "POST",
	          url: "buscar_v2.php",
	          data: cadena,
	          success: function(r){
	          	 
	          	 $('#pila_saldo_inventario').html(r).fadeIn()
	          	 setTimeout(()=>{ $('#pila_saldo_inventario').removeAttr("style"); },500);	          	          	
	          }
	      });
			
		}
		//

		function load_pila_clasificacion(pila){
				
				cadena = "band=" + 52 +
						"&pila=" + pila;
				$.ajax({
	          type: "POST",
	          url: "buscar_v2.php",
	          data: cadena,
	          success: function(r){
	          	 
	          	 $('#pila_saldo_inventario').html(r).fadeIn()
	          	

	          	 setTimeout(()=>{
	          	  $('#pila_saldo_inventario').removeAttr("style");
	          	   $('#pila2').val($('#pila').val())
	          	  },500);	          	          	
	          }
	      });
			
		}

			function load_pila_clasificacion2(pila){
				
				cadena = "band=" + 52 +
						"&pila=" + pila;
				$.ajax({
	          type: "POST",
	          url: "buscar_v2.php",
	          data: cadena,
	          success: function(r){
	          	 
	          	 $('#pila_saldo_inventario2').html(r).fadeIn()
	          	

	          	 setTimeout(()=>{
	          	  $('#pila_saldo_inventario2').removeAttr("style");
	          	   // $('#pila2').val($('#pila').val())
	          	  },500);	          	          	
	          }
	      });
			
		}

		function load_pila(buscar,idelemento){
				cadena = "band=" + 52 +
						"&pila=" + buscar;
				$.ajax({
	          type: "POST",
	          url: "buscar_v2.php",
	          data: cadena,
	          success: function(r){
	          	  console.log(r)
	          	 $('#'+idelemento).html(r).fadeIn()
	          	 setTimeout(()=>{
	          	  $('#'+idelemento).removeAttr("style");          	
	          	  },500);	          	          	
	          }
	      });
			
		}



		function ver_modal_creacion(iTipo,idBateria,idHorno){
			cadena = "band=" + 6 +
					"&tipoCreacion=" + iTipo +
					"&idBateria=" + idBateria +
					"&idHorno=" + idHorno +
					"&lugar_trabajo=" + $('#lugar_trabajo_produccion').val();
			$.ajax({
	            type: "POST",
	            url: "buscar_v2.php",
	            data: cadena,
	            success: function (r){
	            	//console.log(r)
	            	//cargar_menu_body(3);
	            	array_data = r.split("||");
	            	$('#tittle_modal_creacion').html(array_data[0]);
	            	$('#div_menu_modal_creacion').html(array_data[1]).fadeIn();
	            	$('#modalCreacionEstructura').modal('show');
	            }
	        });
		}
		function load_clasificacion_produccion(iTipo){
			if(iTipo=='crear_horno')
				producto=$('#producto_horno').val()
			else
				producto=$('#producto_bateria').val()
			cadena = "band=" + 6.5 +
					"&producto=" + producto;
			$.ajax({
	            type: "POST",
	            url: "buscar_v2.php",
	            data: cadena,
	            success: function (r){
	            	if(iTipo=='crear_horno')
	            		$('#clasificacion_horno').html(r).fadeIn();
	            	else
	            		$('#clasificacion_bateria').html(r).fadeIn();
	            }
	        });
		}
		function SAVE_plantas(){
			cadena = "band=" + 7 +
					"&lugar_trabajo=" + $('#lugar_trabajo_produccion').val() +
					"&jornada=" + $('#jornada_planta').val() +
					"&estado=" + $('#estado_planta').val() +
					"&clasificacion_planta=" + $('#clasificacion_planta').val() +
					"&clasificacionProducida_planta=" + $('#clasificacionProducida_planta').val() +
					"&receta=" + $('#receta_planta').val();
			$.ajax({
	            type: "POST",
	            url: "buscar_v2.php",
	            data: cadena,
	            success: function (r){
	            	//console.log(r)
	            	if(r == 1){	
	            		cargar_menu_body(3,0);
	            	}
	            }
	        });
		}
		function SAVE_baterias(idPlanta,idBateria){
			cadena = "band=" + 8 +
					"&num_bateria=" + $('#num_bateria').val() +
					"&descripcion_bateria=" + $('#descripcion_bateria').val() +
					"&idPlanta=" + idPlanta +
					"&idBateria=" + idBateria +
					"&lugar_trabajo=" + $('#lugar_trabajo_produccion').val() +
					"&jornada=" + $('#jornada_bateria').val() +
					"&estado=" + $('#estado_bateria').val() +
					"&clasificacion_bateria=" + $('#clasificacion_bateria').val() +
					"&receta=" + $('#receta_bateria').val();
			$.ajax({
	            type: "POST",
	            url: "buscar_v2.php",
	            data: cadena,
	            success: function (r){
	            	//console.log(r);
	            	if(r == 1){
	            		$('#modalCreacionEstructura').modal('hide');
	            		cargar_menu_body(3,0);
	            	}
	            }
	        });
		}
		function block_alias(){
			if($('#checkbox_hornos').is(':checked')){
				$('#alias_horno').prop('disabled', true)
			}else{
				$('#alias_horno').prop('disabled', false)
			}
		}
		function SAVE_hornos(idPlanta,idBateria,idHorno){
			checkbox = 0;
			if($('#checkbox_hornos').is(':checked')){
				checkbox = 1
			}
			cadena = "band=" + 9 +
					"&descripcion_horno=" + $('#descripcion_horno').val() +
					"&alias_horno=" + $('#alias_horno').val() +
					"&idPlanta=" + idPlanta +
					"&idBateria=" + idBateria +
					"&idHorno=" + idHorno +
					"&lugar_trabajo=" + $('#lugar_trabajo_produccion').val() +
					"&jornada=" + $('#jornada_horno').val() +
					"&indice_carga=" + $('#indice_carga').val() +
					"&indice_deshorne=" + $('#indice_deshorne').val() +
					"&estado=" + $('#estado_horno').val() +
					"&clasificacion_horno=" + $('#clasificacion_horno').val() +
					//"&receta_horno=" + $('#receta_horno').val() +
					"&checkbox_hornos=" + checkbox;
			//console.log(cadena)
			$.ajax({
	            type: "POST",
	            url: "buscar_v2.php",
	            data: cadena,
	            success: function (r){
	            	//console.log(r);
	            	separ = r.split("||");
	            	if(separ[0]>0){
	            		$('#modalCreacionEstructura').modal('hide');
	            		if(idHorno!='0'){
	            			cargar_menu_body(3,separ[1]);
	            			//alert(separ[1])
	            			$(".btn_ocultar" + separ[1]).show()
	            		}else{
	            			cargar_menu_body(3,0);
	            		}

	            	}
	            }
	        });
		}
		function marcar(id){
			if($("#" + id + '-check').attr('checked')!='checked'){
				var array_hornos = return_array_selected();
				var option = '';
				if(array_hornos.length > 0){
					for (var i = 0; i < array_hornos.length; i++){
						if($('#' + array_hornos[i] + '_td').hasClass('btn-secondary')){
							option = 'btn-secondary';
						}else{
							option = 'btn-warning';
						}
					}
				}else{
					option = 'nada';
				}
				if($('#' + id + '_td').hasClass('btn-secondary')){
					if(option == 'btn-secondary' || option == 'nada'){
						$('#' + id + '_td').prop("style","background-color:#64C7CD");
						$("#" + id + '-check').attr('checked', 'checked');
						$('#btn_abrir_alimentacion').show();
						$('#btn_abrir_produccion').hide();	
					}
				}else{
					if(option == 'btn-warning' || option == 'nada'){
						$('#' + id + '_td').prop("style","background-color:#64C7CD");
						$("#" + id + '-check').attr('checked', 'checked');
						$('#btn_abrir_alimentacion').hide();
						$('#btn_abrir_produccion').show();
					}
				} 
			}else{
				$('#' + id + '_td').prop("style","background-color:");
				$("#" + id + '-check').removeAttr('checked');
			}
			validar_selecciones();
			var array_hornos = return_array_selected();
			$('#cant_hornos_selected').html('Hornos seleccionados: ' + array_hornos.length)
			//console.log(return_array_selected())
		}
		function seleccionar_pares(bateria){
			//$(".par_" + bateria).prop("style","background-color:#9EE1F7");
			if($(".par_input_" + bateria).attr('checked')!='checked'){
				$(".par_" + bateria).prop("style","background-color:#64C7CD");
				$(".par_input_" + bateria).attr('checked', 'checked'); 
				//$("#imagen").prop("style","display:block");
			}else{
				$(".par_" + bateria).prop("style","background-color:");
				$(".par_input_" + bateria).removeAttr('checked'); 
			}
			validar_selecciones();
		}
		function seleccionar_impares(bateria){
			$(".impar_" + bateria).prop("style","background-color:#64C7CD");
			if($(".impar_input_" + bateria).attr('checked')!='checked'){
				$(".impar_" + bateria).prop("style","background-color:#64C7CD");
				$(".impar_input_" + bateria).attr('checked', 'checked');
				//$("#imagen").prop("style","display:block");
			}else{
				$(".impar_" + bateria).prop("style","background-color:");
				$(".impar_input_" + bateria).removeAttr('checked');
			}
			validar_selecciones()
		}
		function validar_selecciones(){
			var checkboxes = document.getElementById("form");
			var cont = 0;
			for(var x=0; x < checkboxes.length; x++){
			 	if (checkboxes[x].checked){
			  		cont = cont + 1;
			 	}
			}
			if(cont==0){
				//$("#imagen").prop("style","display:none");
				$('#btn_abrir_produccion').hide();
				$('#btn_abrir_alimentacion').hide();
			}
		}
		function return_array_selected(){
			var checkboxes = document.getElementById("form");
			var cont = 0;
			var check_array = new Array();
			for(var x=0; x < checkboxes.length; x++){
			 	if(checkboxes[x].checked){
			 		check_array[cont]= checkboxes[x].value;
			  		cont = cont + 1;
			 	}
			}
			return check_array;
		}
		function abrir_modal_alimentacion(){
			check_array = return_array_selected();
			$('#checked_hornos').val(check_array.toString());
			//console.log(check_array.toString());
			cadena = "band=" + 11 +
					"&check_array=" + check_array;
			$.ajax({
	            type: "POST",
	            url: "buscar_v2.php",
	            data: cadena,
	            success: function (r){
	            	//console.log(r);
	            	$('#tittle_modal_asignacion').html('Registro de Alimentación');
	            	$('#div_menu_modal_asignacion').html(r).fadeIn();
	            	calcular_tm_total(1,'alimentacion')
	            }
	        });
			$('#modalAsignacionDatos').modal('show');
		}
		function registrar_alimentacion(get){
			$('#registrar_alimentacion_horno').addClass('loader_min')
			$('#registrar_alimentacion_horno').prop("disabled",true)
			var fechaActual = $('#fecha_abcde').val();

			var inputs = document.getElementById("form_alimentacion");
			var fecha_alimentacion = $('#fecha_alimentacion').val();
			var hora_alimentacion = $('#hora_alimentacion').val();
			//var receta_alimentacion = $('#receta_alimentacion').val();
			var empresa_alimentacion = $('#empresa_alimentacion').val()
			var pila = $('#inpila_horno').val();
			var cont = 0;
			var array_id = new Array();
			var array_name = new Array();
			var array_value = new Array();
			var iError = 0;
			$("#form_alimentacion").find(':input').each(function() {
       	var elemento= this;
       	if(elemento.id != ''){
       		if(elemento.value != '' && elemento.value != '0'){
       			$("#" + elemento.id).prop("style","border: 1px solid; border-color: #ccc");
       			array_id[cont] = elemento.id;
       			array_name[cont] = elemento.name;
       			array_value[cont] = elemento.value;
       			cont = cont + 1;
       		}else{
       			alert("El hornos # " + elemento.name + " no tiene una tonelada de carga asignada");
       			//////////////////////////////////////////////////////////////////////
       			$("#" + elemento.id).prop("style","border: 1px solid; border-color: red");
       			iError = iError + 1;
       		}
       	}
      });
      if(fecha_alimentacion == '' || fecha_alimentacion == null || fecha_alimentacion>fechaActual){
      	$("#fecha_alimentacion").prop("style","border: 1px solid; border-color: red");
      	iError = iError + 1;
      }else{
      	$("#fecha_alimentacion").prop("style","border: 1px solid; border-color: #ccc");
      }
      if(hora_alimentacion == '' || hora_alimentacion == null){
      	$("#hora_alimentacion").prop("style","border: 1px solid; border-color: red");
      	iError = iError + 1;
      }else{
      	$("#hora_alimentacion").prop("style","border: 1px solid; border-color: #ccc");
      }
      // if(receta_alimentacion == '0' || receta_alimentacion == '' || receta_alimentacion == 0){
      // 	$("#receta_alimentacion").prop("style","border: 1px solid; border-color: red");
      // 	iError = iError + 1;
      // }else{
      // 	$("#receta_alimentacion").prop("style","border: 1px solid; border-color: #ccc");
      // }
      if(empresa_alimentacion == '0' || empresa_alimentacion == ''){
      	$("#empresa_alimentacion").prop("style","border: 1px solid; border-color: red");
      	iError = iError + 1;
      }else{
      	$("#empresa_alimentacion").prop("style","border: 1px solid; border-color: #ccc");
      }
      if(pila == '0' || pila == '' || pila== ' '){
      	$("#inpila_horno").prop("style","border: 1px solid; border-color: red");
      	iError = iError + 1;
      }else{
      	$("#inpila_horno").prop("style","border: 1px solid; border-color: #ccc");
      }
      if(fecha_alimentacion>fechaActual){
      	alertify.error('La fecha seleccionada es mayor a la actual')
      }else{
	      if(iError == 0){
	      	let clasificacion_alimentacion = $('#clasificacion_alimentacion').val()
	      	let lugar_trabajo_produccion = $('#lugar_trabajo').val()
	      	let tm_tara_hornos = $('#tm_tara_hornos').val()
	        cadena = "band=" + 12 +
						"&array_id=" + array_id +
						"&array_name=" + array_name +
						"&array_value=" + array_value +
						"&fecha_alimentacion=" + fecha_alimentacion +
						//"&receta_alimentacion=" + receta_alimentacion +
						"&empresa_alimentacion=" + empresa_alimentacion +
						"&hora_alimentacion=" + hora_alimentacion +
						"&clasificacion_alimentacion=" + clasificacion_alimentacion +
						"&lugar_trabajo_produccion=" + lugar_trabajo_produccion +
						"&tm_tara_hornos=" + tm_tara_hornos +
						"&get_without_inv=" + get +
						"&pila=" + pila;
					//console.log(cadena);
					$.ajax({
			            type: "POST",
			            url: "buscar_v2.php",
			            data: cadena,
			            success: function (r){
			            	//console.log(r);
			            	if(r=='no'){
			            		//alertify.error('No hay inventario disponible');
			            		$('#registrar_alimentacion_horno').removeClass('loader_min')
											$('#registrar_alimentacion_horno').prop("disabled",false)

											swal("No hay inventario disponible. ¿Desea registrarlo igualmente?", {
									        icon: "warning",
									        buttons: {
									            cancel: "Cancelar",
									            catch: {
									                text: 'ACEPTAR',
									                value: "catch",
									            },
									            /*defeat: {
									                text: 'texto',
									            },*/
									        },dangerMode: false,
									    })
									    .then((value) => {
									        switch (value) {
									     
									        case "catch":
									            var band = 27;
									            registrar_alimentacion(1)
									            break;
									        default:
									            //swal("Got away safely!");
									        }
									    });
									  }else if(r=='inv'){
			            		alertify.error('El periodo está cerrado');
			            		$('#registrar_alimentacion_horno').removeClass('loader_min')
											$('#registrar_alimentacion_horno').prop("disabled",false)
			            	}else if(r == 'a'){
			            		cargar_menu_body(2,0);
			            		alertify.success('Se ha guardado correctamente');
			            		$('#modalAsignacionDatos').modal('hide');
			            	}else{
			            		var split = r.split("--");
			            		var txt = '';
			            		for (var i = 0; i < split.length; i++) {
			            			positions = split[i].split("||");
			            			txt+= "El horno " + positions[0] + " " + positions[2] + "\n";
			            		}
			            		alert(txt);
			            		$('#registrar_alimentacion_horno').removeClass('loader_min')
											$('#registrar_alimentacion_horno').prop("disabled",false)
			            	}
			            }
			        });
				}else{
					alertify.error('Falta completar datos');
					$('#registrar_alimentacion_horno').removeClass('loader_min')
					$('#registrar_alimentacion_horno').prop("disabled",false)
				}
			}
		}
		function abrir_modal_produccion(){
			check_array = return_array_selected();
			$('#checked_hornos').val(check_array.toString());
			let lugar_trabajo_produccion = $('#lugar_trabajo').val()
			cadena = "band=" + 13 +
					"&variable_modal=" + 1 +
					"&check_array=" + check_array +
					"&lugar_trabajo_produccion=" + lugar_trabajo_produccion;
			$.ajax({
          type: "POST",
          url: "buscar_v2.php",
          data: cadena,
          success: function (r){
          	//console.log(r);
          	$('#tittle_modal_asignacion').html('Registro de Producción');
          	$('#div_menu_modal_asignacion').html(r).fadeIn();
          	$('#div_comment_group').hide();
          	//reload_comments()
          	calcular_tm_total(1,'produccion')
          }
      });
			$('#modalAsignacionDatos').modal('show');
		}
		function reload_comments(){
			check_array = return_array_selected();
			$('#checked_hornos').val(check_array.toString());
			var fecha_produccion = $('#fecha_produccion').val()
			cadena = "band=" + 13 +
					"&variable_modal=" + 0 +
					"&fecha_produccion=" + fecha_produccion +
					"&check_array=" + check_array;
			$.ajax({
          type: "POST",
          url: "buscar_v2.php",
          data: cadena,
          success: function (r){
          	//console.log(r);
          	$('#div_form_produccion').html('')
          	$('#div_form_produccion').html(r)
          }
      });
		}
		function registrar_produccion(){
			$('#registrar_produccion_horno').addClass('loader_min')
			$('#registrar_produccion_horno').prop("disabled",true)
			//const fechaActual = new Date();
			var fechaActual = $('#fecha_abcde').val();

			var inputs = document.getElementById("form_produccion")
			var fecha_produccion = $('#fecha_produccion').val()
			var hora_produccion = $('#hora_produccion').val()
			var clasificacion_produccion = $('#clasificacion_produccion').val()
			var pila = $('#inpila_horno').val();
			var cont = 0
			var array_id = new Array()
			var array_name = new Array()
			var array_value = new Array()
			var array_value_comment = new Array()
			var iError = 0
			var MessageError = '';
			$("#form_produccion").find(':input').each(function(){
       	var elemento= this
       	if(elemento.id != ''){
       		if(elemento.value != '' && elemento.value != '0'){
       			if(elemento.type == 'number'){
         			$("#" + elemento.id).prop("style","border: 1px solid; border-color: #ccc")
         			array_id[cont] = elemento.id
         			array_name[cont] = elemento.name
         			array_value[cont] = elemento.value
         		}else if(elemento.type == 'hidden'){
         			var subStrId = elemento.id.substring(0, 36)
         			$("#div_" + subStrId + "_group").prop("style","background-color: ")
         			cont = cont-1;
         			array_value_comment[cont] = elemento.value
         		}
       			cont = cont + 1
       		}else{
       			if(elemento.type == 'text'){
         			//alert("El horno # " + elemento.name + " no tiene una tonelada de deshorne asignada")
         			MessageError = MessageError + 'El horno # ' + elemento.name + ' no tiene una tonelada de deshorne asignada \n'
         			$("#" + elemento.id).prop("style","border: 1px solid; border-color: red")
         		}else if(elemento.type == 'hidden'){
         			var subStrId = elemento.id.substring(0, 36)
         			var subStrName = elemento.name.split("_")
         			//alert("El horno # " + subStrName[0] + " se pasó, porfavor agregue el motivo en las observaciones")
         			MessageError = MessageError + 'El horno # ' + subStrName[0] + ' se pasó, porfavor agregue el motivo en las observaciones \n'
         			$("#div_" + subStrId + "_group").prop("style","border: 1px solid; border-color: #BF2020; border-radius: 5px; background-color: #BF2020")
         		}
       			iError = iError + 1
       		}
       	}
      });
      if(MessageError!=''){
      	alert(MessageError)
      }
      if(fecha_produccion == '' || fecha_produccion == null || fecha_produccion>fechaActual){
      	$("#fecha_produccion").prop("style","border: 1px solid; border-color: red")
      	iError = iError + 1
      }else{
      	$("#fecha_produccion").prop("style","border: 1px solid; border-color: #ccc")
      }
      if(hora_produccion == '' || hora_produccion == null){
      	$("#hora_produccion").prop("style","border: 1px solid; border-color: red")
      	iError = iError + 1
      }else{
      	$("#hora_produccion").prop("style","border: 1px solid; border-color: #ccc")
      }
      if(pila == '0' || pila == '' || pila == ' '){
      	$("#inpila_horno").prop("style","border: 1px solid; border-color: red");
      	iError = iError + 1;
      }else{
      	$("#inpila_horno").prop("style","border: 1px solid; border-color: #ccc");
      }
      if(fecha_produccion>fechaActual){
      	alertify.error('La fecha seleccionada es mayor a la fecha actual')
      }else{
        if(iError == 0){
	        cadena = "band=" + 14 +
						"&array_id=" + array_id +
						"&array_name=" + array_name +
						"&array_value=" + array_value +
						"&array_value_comment=" + array_value_comment +
						"&fecha_produccion=" + fecha_produccion +
						"&hora_produccion=" + hora_produccion +
						"&clasificacion_produccion=" + clasificacion_produccion +
						"&pila=" + pila;
					//console.log(cadena);
					$.ajax({
            type: "POST",
            url: "buscar_v2.php",
            data: cadena,
            success: function (r){
            	//console.log(r);
            	if(r == 1){
            		cargar_menu_body(2,0);
            		alertify.success('Se ha guardado correctamente');
            		$('#modalAsignacionDatos').modal('hide');
            	}else if(r=='a'){
            		alertify.error('El periodo se encuentra cerrado')
	            }else{
            		var split = r.split("--");
            		var txt = '';
            		for (var i = 0; i < split.length; i++) {
            			positions = split[i].split("||");
            			txt+= "El horno " + positions[0] + " " + positions[2] + "\n";
            		}
            		alert(txt);
            		$('#registrar_produccion_horno').removeClass('loader_min')
								$('#registrar_produccion_horno').prop("disabled",false)
            	}
            }
        	});
				}else{
					alertify.error('Falta completar campos')
					$('#registrar_produccion_horno').removeClass('loader_min')
					$('#registrar_produccion_horno').prop("disabled",false)
				}
			}
		}
		function select_horno_comments(horno){
			$(".comment_group").removeClass("blue")
    	$("#div_" + horno + "_group").prop("style","background-color: ")
    	$("#div_" + horno + "_group").addClass("select_others")
    	$('#div_comment_group').show(500)
		}
		function open_comment_timelast(horno){
        	var comment = $('#' + horno + '_comment').val()
        	$('#textarea_comment').val(comment)
        	$(".comment_group").removeClass("blue")
        	$(".comment_group").removeClass("select_others")
        	$("#div_" + horno + "_group").prop("style","background-color: ")
        	$("#div_" + horno + "_group").addClass("blue")
        	$('#div_comment_group').show(500)
		}
		function load_comment(){
			var textarea_comment = $('#textarea_comment').val()
			if(textarea_comment != ''){
				$("#textarea_comment").prop("style","border: 1px solid; border-color: #ccc")
				$(".blue").find(':input[type = "hidden"]').each(function(){
        	var elemento = this
        	$('#' + elemento.id).val(textarea_comment)
        	$("#div_" + elemento.id + "_group").prop("style","background-color: ")
        	$('#textarea_comment').val('')
        	$(".comment_group").removeClass("blue")
		    })
		    $(".select_others").find(':input[type = "hidden"]').each(function(){
        	var elemento = this
        	$('#' + elemento.id).val(textarea_comment)
        	$("#div_" + elemento.id + "_group").prop("style","background-color: ")
        	$('#textarea_comment').val('')
        	$(".comment_group").removeClass("blue")
		    })
		    $('#div_comment_group').hide(500);
		  }else{
		    $("#textarea_comment").prop("style","border: 1px solid; border-color: red")
		  }
		}
		function calcular_tara(n,variable){
			var ev = (n) ? n : event;
      var code=(ev.which) ? ev.which : event.keyCode;
      if(code == 13 || n == 1){
				let tm_tara_hornos = $('#tm_tara_hornos').val()
				let count_hornos_seleccionados = 0
				if(tm_tara_hornos != 0){
					count_hornos_seleccionados = $('.list_horno_val').length
					tm_tara_hornos = tm_tara_hornos/count_hornos_seleccionados
					//tm_tara_hornos = tm_tara_hornos.toFixed(2)
					$("#form_" + variable).find(':input[type = "number"]').each(function(){
	        	var elemento = this
	        	$('#' + elemento.id).val(tm_tara_hornos)
			    })
			  }else{
			  	calcular_tm_total(1,variable)
			  }
			}
		}
		function calcular_tm_total(n,variable){
			var ev = (n) ? n : event;
      var code=(ev.which) ? ev.which : event.keyCode;
      if(code == 13 || n == 1){
				var tm_total = 0
		  	$("#form_" + variable).find(':input[type = "number"]').each(function(){
	      	var elemento = this
	      	if(parseFloat(elemento.value)!= null && parseFloat(elemento.value) != ''){
	      		tm_total = parseFloat(tm_total) + parseFloat(elemento.value)
	      	}
		    })
		    $('#tm_tara_hornos').val(tm_total)
		  }
		}
		function guardar_preparacion(get){
			$('.btn-success').addClass('loader_min')
			$('.btn-success').prop("disabled",true)
			let empresa_preparacion = $('#empresa_preparacion').val()
			let lugar_trabajo_produccion = $('#lugar_trabajo_produccion').val();
			let fecha_preparacion = $('#fecha_preparacion').val();
			let toneladas_preparacion = $('#toneladas_preparacion').val();
			let receta_preparacion = $('#receta_preparacion').val();
			let pasa = 0;
			let array_id_prep = new Array()
			let array_value_prep = new Array()
			let count_array = 0
			$('#tr_details_receta').find(':input').each(function(){
				let elemento = this
				if(elemento.value!=''){
					array_id_prep[count_array] = elemento.id
					array_value_prep[count_array] = elemento.value
					count_array = count_array+1
				}else{
					pasa = pasa+1
				}
			})
			let total_details = 0
			array_value_prep.forEach(function(a){total_details += parseFloat(a);});
			if(parseFloat(toneladas_preparacion)!=parseFloat(total_details).toFixed(2)){
				pasa = pasa+1
				//console.log(total_details)
			}
			if(fecha_preparacion == ''){
				pasa = pasa+1
			}
			if(toneladas_preparacion == ''){
				pasa = pasa+1
			}
			if(receta_preparacion == '0'){
				pasa = pasa+1
			}
			if(pasa == 0){
				cadena = "band=" + 16 +
						"&empresa_preparacion=" + empresa_preparacion + 
						"&lugar_trabajo_produccion=" + lugar_trabajo_produccion +
						"&fecha_preparacion=" + fecha_preparacion +
						"&receta_preparacion=" + receta_preparacion +
						"&toneladas_preparacion=" + toneladas_preparacion +
						"&array_id_prep=" + array_id_prep +
						"&array_value_prep=" + array_value_prep +
						"&get_without_inv=" + get;
				$.ajax({
		            type: "POST",
		            url: "buscar_v2.php",
		            data: cadena,
		            success: function (r){
		            	//console.log(r)
			        		if(r==1){
			        			$('#fecha_preparacion').val('');
			        			$('#toneladas_preparacion').val('');
			        			cargar_menu_body(5,0);
			        		}else{
			        			array_errors = ['Error al registrar','Se registró correctamente','No hay inventario disponible','El periodo está cerrado'];
			        			if (r==2){
			        				swal("No hay inventario disponible. ¿Desea registrarlo igualmente?", {
									        icon: "warning",
									        buttons: {
									            cancel: "Cancelar",
									            catch: {
									                text: 'ACEPTAR',
									                value: "catch",
									            },
									            /*defeat: {
									                text: 'texto',
									            },*/
									        },dangerMode: false,
									    })
									    .then((value) => {
									        switch (value) {
									     
									        case "catch":
									            guardar_preparacion(1)
									            break;
									        default:
									        	///values
									        }
									    });
									    $('.btn-success').removeClass('loader_min')
											$('.btn-success').prop("disabled",false)
			        			}else{
			        				alertify.error(array_errors[r]);
			        			}
			        		}
		            }
		        });
			}else{
				alertify.error("Faltan datos por seleccionar");
			}
		}		/*
        function control_time(){
	        var fecha= new Date();
	        var m = moment();
	        var horas= fecha.getHours();
	        var minutos = fecha.getMinutes();
	        var segundos = fecha.getSeconds();
	        document.getElementById('valoresFecha').innerHTML=' '+horas+':'+minutos+':'+segundos+'';
	        document.getElementById('abcd').value=' '+horas+':'+minutos+':'+segundos+'';
	        document.getElementById('valoresFechaInput').value=''+'<?php echo $Fecha = date('Y-m-d'); ?>'+' '+horas+':'+minutos+':'+segundos+'';
	        setTimeout('control_time()',1000);
        }*/
        function load_recetas_produccion(entorno){
        	if(entorno=='preparacion_mezcla' || entorno=='alimentacion_hornos'){
		        if(entorno=='preparacion_mezcla'){
		        	mezcla_producida = $('#mezcla_producida').val()
		        	empresa_preparacion = $('#empresa_preparacion').val()
		        	lugar_trabajo_produccion = $('#lugar_trabajo_produccion').val()
		        }
		        if(entorno=='alimentacion_hornos'){
		        	mezcla_producida = $('#clasificacion_alimentacion').val()
		        	empresa_preparacion = $('#empresa_alimentacion').val()
		        	lugar_trabajo_produccion = $('#lugar_trabajo_produccion').val()
		        }

	        	cadena = "band=" + 17 +
						"&lugar_trabajo_produccion=" + lugar_trabajo_produccion +
						"&empresa_preparacion=" + empresa_preparacion +
						"&entorno=" + entorno +
						"&mezcla_producida=" + mezcla_producida;
						$.ajax({
		            type: "POST",
		            url: "buscar_v2.php",
		            data: cadena,
		            success: function (r){
		        		//console.log(r);
		        		if(entorno=='preparacion_mezcla'){
		        			$('#receta_preparacion').html(r).fadeIn()
		        		}
		        		if(entorno=='alimentacion_hornos'){
		        			$('#receta_alimentacion').html(r).fadeIn()
		        		}
		        	}
		        });
					}else{
						if(entorno=='coquizacion'){
							var clasificacion = $('#clasificacion_planta').val()
						}else if(entorno=='crear_baterias'){
							var clasificacion = $('#clasificacion_bateria').val()
						}else if(entorno=='crear_horno'){
							var clasificacion = $('#clasificacion_horno').val()
						}
						cadena = "band=" + 19 +
								"&clasificacion=" + clasificacion;
						$.ajax({
		          type: "POST",
		          url: "buscar_v2.php",
		          data: cadena,
		          success: function (r){
		      		//console.log(r);
		      		if(entorno=='coquizacion'){
								$('#receta_planta').html(r).fadeIn()
							}else if(entorno=='crear_baterias'){
								$('#receta_bateria').html(r).fadeIn()
							}else if(entorno=='crear_horno'){
								$('#receta_horno').html(r).fadeIn()
							}
				      	}
				      });
					}
        }
        function load_table_recetas_produccion(){
        	var receta_preparacion = $('#receta_preparacion').val()
        	var toneladas_preparacion = $('#toneladas_preparacion').val()
        	if(toneladas_preparacion==0 || toneladas_preparacion=='0' || toneladas_preparacion==''){
        		//alertify.error('Debe asignar toneladas para la receta')
        		$("#toneladas_preparacion").prop("style","border: 1px solid; border-color: red")
        	}else{
        		$("#toneladas_preparacion").prop("style","border: 1px solid; border-color: #ccc")
        		if(receta_preparacion != '0' && receta_preparacion != 'null' && receta_preparacion != 0 && receta_preparacion != null){
		        	cadena = "band=" + 18 +
							"&receta_preparacion=" + receta_preparacion +
							"&toneladas_preparacion=" + toneladas_preparacion;
							$.ajax({
			            type: "POST",
			            url: "buscar_v2.php",
			            data: cadena,
			            success: function (r){
			        		//console.log(r);
			        		$('#div_recetas_prod').html(r)
			        	}
			        });
						}
					}
        }
        function load_clasificacion_inventory(){
        	var producto_inventario = $('#producto_inventario').val()
        	cadena = "band=" + 20 +
					"&producto_inventario=" + producto_inventario;
					$.ajax({
			            type: "POST",
			            url: "buscar_v2.php",
			            data: cadena,
			            success: function (r){
			        		// console.log(r);
			        		$('#clasificacion_inventario').html(r)
			        	}
			        });
		        }
		    function load_history_inventory(){
		    	$("#modalInventoryBalance").modal("show")
		    	var lugar_trabajo = $('#lugar_trabajo_produccion').val()
		    	var cadena = "band=" + 26 +
								"&lugar_trabajo=" + lugar_trabajo;
					$.ajax({
						type: "POST",
						url: "buscar_v2.php",
						data: cadena,
						success: function (r){							
							$('#div_history_inventory').html(r)
						}
					})
		    }

		     function load_history_inventory_pilas(){
		    	$("#modalInventoryBalance").modal("show")
		    	var lugar_trabajo = $('#lugar_trabajo_produccion').val()
		    	var cadena = "band=" + 53 +
								"&lugar_trabajo=" + lugar_trabajo;
					$.ajax({
						type: "POST",
						url: "buscar_v2.php",
						data: cadena,
						success: function (r){
							//console.log(r)
							 $('#div_history_inventory').html(r)
						}
					})
		    }

        function save_inventory_balance(){
        	var empresa_inventario = $('#empresa_inventario').val()
        	var fecha_inventario = $('#fecha_inventario').val()
					var producto_inventario = $('#producto_inventario').val()
					var clasificacion_inventario = $('#clasificacion_inventario').val()				
					var saldo_inventario = $('#saldo_inventario').val()
					var site_work = $('#lugar_trabajo_produccion').val()

					var cadena = "band=" + 21 +
								"&empresa_inventario=" + empresa_inventario +
								"&fecha_inventario=" + fecha_inventario +
								"&producto_inventario=" + producto_inventario +
								"&clasificacion_inventario=" + clasificacion_inventario +								
								"&saldo_inventario=" + saldo_inventario +
								"&site_work=" + site_work;
					$.ajax({
						type: "POST",
						url: "buscar_v2.php",
						data: cadena,
						success: function (r){
							if(r==1){
								//$('#modalInventoryBalance').modal('hide');
								//setTimeout(cargar_menu_body(4),1000);
								load_history_inventory()
							}else if(r==2){
								alertify.error('Ya hay un inventario registrado en esta misma fecha.')
							}
						}
					})
        }

         function save_inventory_balance_xpila(){
        	var empresa_inventario = $('#empresa_inventario').val()
        	var fecha_inventario = $('#fecha_inventario').val()
					var producto_inventario = $('#producto_inventario').val()
					var clasificacion_inventario = $('#clasificacion_inventario').val()
					var unidadnegocio_inventario = $('#unidadnegocio_inventario').val()
					var pila = $('#pilas_carga').val()
					var saldo_inventario = $('#saldo_inventario').val()
					var site_work = $('#lugar_trabajo_produccion').val()

					var cadena = "band=" + 54 +
								"&empresa_inventario=" + empresa_inventario +
								"&fecha_inventario=" + fecha_inventario +
								"&producto_inventario=" + producto_inventario +
								"&clasificacion_inventario=" + clasificacion_inventario +
								"&unidadnegocio_inventario=" + unidadnegocio_inventario +
								"&pila=" + pila +
								"&saldo_inventario=" + saldo_inventario +
								"&site_work=" + site_work;
					$.ajax({
						type: "POST",
						url: "buscar_v2.php",
						data: cadena,
						success: function (r){
							//console.log(r)
							if(r==1){
								//$('#modalInventoryBalance').modal('hide');
								//setTimeout(cargar_menu_body(4),1000);
								load_history_inventory_pilas()
							}else if(r==2){
								alertify.error('Ya hay un inventario registrado en esta misma fecha.')
							}
						}
					})
        }


        function delete_inventory_balance(idEmpresa,idDestino,idClasificacion,Fecha){
			    swal("¿Desea eliminar el corte de inventario?", {
			        icon: "warning",
			        buttons: {
			            cancel: "Cancelar",
			            catch: {
			                text: 'Eliminar',
			                value: "catch",
			            },
			            /*defeat: {
			                text: 'texto',
			            },*/
			        },dangerMode: false,
			    })
			    .then((value) => {
			        switch (value) {
			     
			        case "catch":
			            var band = 27;
			            break;
			        default:
			            //swal("Got away safely!");
			        }
			        cadena = "idEmpresa=" + idEmpresa +
			        				"&idDestino=" + idDestino +
			        				"&idClasificacion=" + idClasificacion +
			        				"&Fecha=" + Fecha +
			                "&band=" + band;
			        $.ajax({
			            type: "POST",
			            url: "buscar_v2.php",
			            data: cadena,
			            success: function (r){
			                //console.log(r);
			                if (r == 1){
			                    swal("Poof!", "Ha sido eliminado!", "success");
			                    load_history_inventory()
			                }
			            }
			        });
			    });
				}

				function delete_inventory_xpila(idEmpresa,idDestino,idClasificacion,pila,Fecha){
			    swal("¿Desea eliminar el corte de inventario?", {
			        icon: "warning",
			        buttons: {
			            cancel: "Cancelar",
			            catch: {
			                text: 'Eliminar',
			                value: "catch",
			            },
			            /*defeat: {
			                text: 'texto',
			            },*/
			        },dangerMode: false,
			    })
			    .then((value) => {
			        switch (value) {
			     
			        case "catch":
			            var band = 55;
			            break;
			        default:
			            //swal("Got away safely!");
			        }
			        cadena = "idEmpresa=" + idEmpresa +
			        				"&idDestino=" + idDestino +
			        				"&idClasificacion=" + idClasificacion +
			        				"&idPila=" + pila +
			        				"&Fecha=" + Fecha +
			                "&band=" + band;
			        $.ajax({
			            type: "POST",
			            url: "buscar_v2.php",
			            data: cadena,
			            success: function (r){
			                console.log(r);
			                if (r == 1){
			                    swal("Poof!", "Ha sido eliminado!", "success");
			                    load_history_inventory_pilas()
			                }
			            }
			        });
			    });
				}


        function search_registers_preparacion(){
        	$('#btn_search_prep').addClass('loader_min')
        	var lugar_trabajo_produccion = $('#lugar_trabajo_produccion').val()
        	var empresa_preparacion_serach = $('#empresa_preparacion_serach').val()
					var mezcla_producida_search = $('#mezcla_producida_search').val()
					var fecha_inicio_search = $('#fecha_inicio_search').val()
					var fecha_fin_search = $('#fecha_fin_search').val()

					cadena = "band=" + 23 +
							"&lugar_trabajo_produccion=" + lugar_trabajo_produccion +
							"&empresa_preparacion_serach=" + empresa_preparacion_serach +
							"&mezcla_producida_search=" + mezcla_producida_search +
							"&fecha_inicio_search=" + fecha_inicio_search +
							"&fecha_fin_search=" + fecha_fin_search;
					// $.ajax({
					// 	type: "POST",
					// 	url: "buscar_v2.php",
					// 	data: cadena,
					// 	success: function (r){
					// 		$('#div_resultados_preparacion').html(r)
					// 		$('#btn_search_prep').removeClass('loader_min')
					// 	}
					// })
					$.ajax({
          	type: "POST",
          	url: "buscar_v2.php",
          	data: cadena,
          	success: function(r){
          		console.log(r)
          		$('#btn_search_prep').removeClass('loader_min')
          		$('#div_resultados_preparacion').prop("style","width: 99vw; height: 42vh;");
          		let split = r.split("||");
          		global_excel = JSON.parse(split[1]);
          		let json = JSON.parse(split[0]);
	          		
          		$().w2destroy('grid');
          		let grid = new w2grid({
				    		name: 'grid',
				    		box: '#div_resultados_preparacion',
				    		show    : {
					        expandColumn: true,
					        search: true
					    	},
					    	searches: [
					        { field: 'lempresa', text: 'Empresa', type: 'text' },
					        { field: 'ldestino', text: 'Destino', type: 'text' }
					        //{ field: 'lfechapreparacion', text: 'Fecha preparacion', type: 'text' }
					    	],
					    	show: { footer: true },
					    	//sortData: [ { field: 'recid', direction: 'asc' } ],
					    	//columnGroups:json.columnsGroups,
					    	columns: json.columns,
					    	records: json.records
							})
          	}
	      	});
        }

        function delete_preparacion_mezcla(idPreparacion){
			    swal("¿Desea eliminar la preparación de mezcla?", {
			        icon: "warning",
			        buttons: {
			            cancel: "Cancelar",
			            catch: {
			                text: 'Eliminar',
			                value: "catch",
			            },
			            /*defeat: {
			                text: 'texto',
			            },*/
			        },dangerMode: false,
			    })
			    .then((value)=>{
			        switch (value){
			     
			        case "catch":
			            var band = 28;
			            break;
			        default:
			            //swal("Got away safely!");
			        }
			        cadena = "idPreparacion=" + idPreparacion +
			                "&band=" + band;
			        $.ajax({
			            type: "POST",
			            url: "buscar_v2.php",
			            data: cadena,
			            success: function (r){
			               //console.log(r);
			               if (r == 1){
			                search_registers_preparacion()
			              }
			            }
			        });
			    });
				}
        function search_registers_coquizacion(){
        	var idPlanta = $('#idPlanta').val()
        	var consulta_empresa = $('#consulta_empresa').val()
					var consulta_clasificacion_alimentada = $('#consulta_clasificacion_alimentada').val()
					var consulta_clasificacion_producida = $('#consulta_clasificacion_producida').val()
					var consulta_fecha_ini = $('#consulta_fecha_ini').val()
					var consulta_fecha_fin = $('#consulta_fecha_fin').val()

					cadena = "band=" + 24 +
							"&idPlanta=" + idPlanta +
							"&empresa=" + consulta_empresa +
							"&clasificacion_alimentada=" + consulta_clasificacion_alimentada +
							"&clasificacion_producida=" + consulta_clasificacion_producida +
							"&fecha_ini=" + consulta_fecha_ini +
							"&fecha_fin=" + consulta_fecha_fin;
					$.ajax({
						type: "POST",
						url: "buscar_v2.php",
						data: cadena,
						success: function (r){
							$('#div_resultado_hornos').html(r)
						}
					})
        }
        function search_registers_coquizacion_details(array_data){//idEmpresa,fecha,idBateria,idClasificacion,actividad,idPilaAlimentacion){
					cadena = "band=" + 39 +
							"&array_data=" + array_data;
							/*"&idEmpresa=" + idEmpresa +
							"&fecha=" + fecha +
							"&idBateria=" + idBateria +
							"&idClasificacion=" + idClasificacion +
							"&actividad=" + actividad;*/
					$.ajax({
						type: "POST",
						url: "buscar_v2.php",
						data: cadena,
						success: function (r){
							console.log(r)
							$('#tittle_modal_asignacion').html('Consulta por hornos');
          		$('#div_menu_modal_asignacion').html(r).fadeIn();
							$('#modalAsignacionDatos').modal('show')
						}
					})
        }
        function delete_coquizacion(array_data,ioption){//idEmpresa,idPlanta,idBateria,idClasificacionAlimentacion,FechaAlimentacion){
				  if(ioption==0){
				    swal("¿Desea eliminar la coquización?", {
				        icon: "warning",
				        buttons: {
				            cancel: "Cancelar",
				            catch: {
				                text: 'Eliminar',
				                value: "catch",
				            },
				        },dangerMode: false,
				    })
				    .then((value)=>{
				        switch (value){
				     
				        case "catch":
				            var band = 29;
				            break;
				        default:
				            //swal("Got away safely!");
				        }
				        cadena = "band=" + band +
				                "&array_data=" + array_data;
				        $.ajax({
				            type: "POST",
				            url: "buscar_v2.php",
				            data: cadena,
				            success: function (r){
				              //console.log(r);
				              if (r == 1){
				                search_registers_coquizacion()
				              }
				            }
				        });
				    });
				  }else{
				  	swal("¿Desea eliminar solamente producción?", {
				        icon: "warning",
				        buttons: {
				            cancel: "Cancelar",
				            catch: {
				                text: 'Eliminar',
				                value: "catch",
				            },
				        },dangerMode: false,
				    })
				    .then((value)=>{
				        switch (value){
				     
				        case "catch":
				            var band = 29.5;
				            break;
				        default:
				            //swal("Got away safely!");
				        }
				        cadena = "band=" + band +
				                "&array_data=" + array_data;
				        $.ajax({
				            type: "POST",
				            url: "buscar_v2.php",
				            data: cadena,
				            success: function (r){
				              //console.log(r);
				              if (r == 1){
				                search_registers_coquizacion()
				              }
				            }
				        });
				    });
				  }
				}
				function delete_coquizacion_details(idEmpresa,idPlanta,idBateria,idClasificacionAlimentacion,FechaAlimentacion,idHorno){
			    swal("¿Desea eliminar la preparación de mezcla?", {
			        icon: "warning",
			        buttons: {
			            cancel: "Cancelar",
			            catch: {
			                text: 'Eliminar',
			                value: "catch",
			            },
			            /*defeat: {
			                text: 'texto',
			            },*/
			        },dangerMode: false,
			    })
			    .then((value)=>{
			        switch (value){
			     
			        case "catch":
			            var band = 40;
			            break;
			        default:
			            //swal("Got away safely!");
			        }
			        cadena = "idEmpresa=" + idEmpresa +
			        				"&idPlanta=" + idPlanta +
			        				"&idBateria=" + idBateria +
			        				"&idClasificacionAlimentacion=" + idClasificacionAlimentacion +
			        				"&FechaAlimentacion=" + FechaAlimentacion +
			        				"&idHorno=" + idHorno +
			                "&band=" + band;
			        $.ajax({
			            type: "POST",
			            url: "buscar_v2.php",
			            data: cadena,
			            success: function (r){
			              //console.log(r);
			              if(r == 1){
			              	$('#modalAsignacionDatos').modal('hide')
			                //search_registers_coquizacion()
			                search_registers_coquizacion_details(idEmpresa,FechaAlimentacion,idBateria,idClasificacion,'Alimentación')
			              }
			            }
			        });
			    });
				}
        function load_details_inventory(idEmpresa,idDestino,idClasificacion,fechaIni,fechaFin,proceso,transaccion){
					var split = proceso.split("-");
					if(split[0]=='Excel'){
						band = 30;
						$('#btn_download_excel_detail').addClass('loader_min');
					}else{
						$('#modalAsignacionDatos').modal('show');
	        	$('#tittle_modal_asignacion').html('Detalle ' + proceso + 's').fadeIn()
	        	$('#div_menu_modal_asignacion').html('').fadeIn();
						$('#div_menu_modal_asignacion').addClass('loader');
						band = 25;
					}
        	cadena = "band=" + band +
						"&idEmpresa=" + idEmpresa +
						"&idDestino=" + idDestino +
						"&idClasificacion=" + idClasificacion +
						"&fechaIni=" + fechaIni +
						"&fechaFin=" + fechaFin +
						"&proceso=" + proceso +
						"&transaccion=" + transaccion;
					$.ajax({
						type: "POST",
						url: "buscar_v2.php",
						data: cadena,
						success: function (r){
							if(split[0]=='Excel'){
								$('#tabla_excel').val(r);
								$('#TipoMovimiento_tabla_excel').val(split[1]);
								$("#form_excel").attr("action", "descarga_EXCEL.php");
							  $("#form_excel").submit();
							  $('#btn_download_excel_detail').removeClass('loader_min');
							}else{
								$('#div_menu_modal_asignacion').removeClass('loader');
								$('#div_menu_modal_asignacion').html(r).fadeIn();
							}
						}
					})
        }
        function load_details_inventory_pila(Empresa,Destino,Clasificacion,Pila,fechaIni,fechaFin,proceso,transaccion){
					var split = proceso.split("-");
					if(split[0]=='Excel'){
						band = 30;
						$('#btn_download_excel_detail').addClass('loader_min');
					}else{
						$('#modalAsignacionDatos').modal('show');
	        	$('#tittle_modal_asignacion').html('Detalle ' + proceso).fadeIn()
	        	$('#div_menu_modal_asignacion').html('').fadeIn();
						$('#div_menu_modal_asignacion').addClass('loader');
						band = 56;
					}
        	cadena = "band=" + band +
						"&idEmpresa=" + Empresa +
						"&idDestino=" + Destino +
						"&idClasificacion=" + Clasificacion +
						"&idPila=" + Pila +
						"&fechaIni=" + fechaIni +
						"&fechaFin=" + fechaFin +
						"&proceso=" + proceso +
						"&transaccion=" + transaccion;
					$.ajax({
						type: "POST",
						url: "buscar_v2.php",
						data: cadena,
						success: function (r){
							if(split[0]=='Excel'){
								$('#tabla_excel').val(r);
								$('#TipoMovimiento_tabla_excel').val(split[1]);
								$("#form_excel").attr("action", "descarga_EXCEL.php");
							  $("#form_excel").submit();
							  $('#btn_download_excel_detail').removeClass('loader_min');
							}else{
								$('#div_menu_modal_asignacion').removeClass('loader');
								$('#div_menu_modal_asignacion').html(r).fadeIn();
							}
						}
					})
        }
        function load_pila_consumo(){
        	let lugar_trabajo = $('#lugar_trabajo').val()
        	let empresa = $('#empresa').val()
        	let material_alimentado = $('#material_alimentado').val()
        	let zona = $('#zona').val()
        	if(lugar_trabajo != null && empresa != null && material_alimentado != null && zona != null){
	        	var cadena = "band=" + 38 +
					"&lugar_trabajo=" + lugar_trabajo +
					"&empresa=" + empresa +
					"&material_alimentado=" + material_alimentado +
					"&zona=" + zona;
				$.ajax({
					type: "POST",
					url: "buscar_v2.php",
					data: cadena,
					success: function (r){
						// console.log(r)
						$('#pila').html(r).fadeIn();
					}
				})
			}
        }
        function edition_hornos(NomBateria){
        	$(".btn_ocultar" + NomBateria).show()
        	$('#val_change_option_edition').val(1)
        }
        function load_create_tiquete(){
        	$('#modalCargadoresData').modal('show')
					$('#div_menu_modal_cargadores').html('')
					var lugar_trabajo = $('#lugar_trabajo').val()
		    	var cadena = "band=" + 33 +
								"&lugar_trabajo=" + lugar_trabajo;
					$.ajax({
						type: "POST",
						url: "buscar_v2.php",
						data: cadena,
						success: function (r){
							//console.log(r)
							split = r.split("||");
							$('#tittle_modal_cargadores').html('Crear tiquete <b># ' + split[0] + ' (' + split[2] + ')</b>')
							$('#div_menu_modal_cargadores').html(split[1])
						}
					})
        }
        function load_horometro_cargador(){
        	let equipo_cargador = $('#equipo_cargador').val()
        	cadena = "equipo_cargador=" + equipo_cargador +
        					"&band=" + 33.5;
        	$.ajax({
        		type: "POST",
        		url: "buscar_v2.php",
        		data: cadena,
        		success: function (r){
        			//console.log(r)
        			$('#horometro').val(r)
        		}
        	})
        }
        function load_equipo_proveedor(){
        	var proveedor_cargador = $('#proveedor_cargador').val()
        	var cadena = "band=" + 34 +
								"&proveedor_cargador=" + proveedor_cargador;
					$.ajax({
						type: "POST",
						url: "buscar_v2.php",
						data: cadena,
						success: function (r){
							//console.log(r)
							$('#equipo_cargador').html(r).fadeIn();
						}
					})
        }
        function save_tiquete_cargador(){
        	let lugar_trabajo = $('#lugar_trabajo').val()
        	let ClaseTiquete = $('#ClaseTiquete').val()
        	let cliente = $('#cliente').val()
        	let fecha = $('#fecha').val()
        	let remision = $('#remision').val()
        	let proveedor_cargador = $('#proveedor_cargador').val()
        	let equipo_cargador = $('#equipo_cargador').val()
        	let iError = 0
        	if(cliente == '' || cliente == null || cliente == '0'){
		      	$("#cliente").prop("style","border: 1px solid; border-color: red")
		      	iError = iError + 1
		      }else{
		      	$("#cliente").prop("style","border: 1px solid; border-color: #ccc")
		      }
		      if(fecha == '' || fecha == null || fecha == '0'){
		      	$("#fecha").prop("style","border: 1px solid; border-color: red")
		      	iError = iError + 1
		      }else{
		      	$("#fecha").prop("style","border: 1px solid; border-color: #ccc")
		      }
		      if(remision == '' || remision == null || remision == '0'){
		      	$("#remision").prop("style","border: 1px solid; border-color: red")
		      	iError = iError + 1
		      }else{
		      	$("#remision").prop("style","border: 1px solid; border-color: #ccc")
		      }
		      if(proveedor_cargador == '' || proveedor_cargador == null || proveedor_cargador == '0'){
		      	$("#proveedor_cargador").prop("style","border: 1px solid; border-color: red")
		      	iError = iError + 1
		      }else{
		      	$("#proveedor_cargador").prop("style","border: 1px solid; border-color: #ccc")
		      }
		      if(equipo_cargador == '' || equipo_cargador == null || equipo_cargador == '0'){
		      	$("#equipo_cargador").prop("style","border: 1px solid; border-color: red")
		      	iError = iError + 1
		      }else{
		      	$("#equipo_cargador").prop("style","border: 1px solid; border-color: #ccc")
		      }
		      if(iError!=0){
		      	alertfity.error('Complete los campos marcados')
		      }else{
				    cadena = "band=" + 35 +
					            "&lugar_trabajo=" + lugar_trabajo +
					            "&ClaseTiquete=" + ClaseTiquete +
					            "&cliente=" + cliente +
					            "&fecha=" + fecha +
					            "&remision=" + remision +
					            "&proveedor_cargador=" + proveedor_cargador +
					            "&equipo_cargador=" + equipo_cargador;
					    $.ajax({
							type: "POST",
							url: "buscar_v2.php",
							data: cadena,
							success: function(r){
								// console.log(r)
								if(r == 1){
									$('#modalCargadoresData').modal('hide')
									cargar_menu_body(32,0)
								}else{
									array_errors = ['Error al registrar','Se registró correctamente','No hay inventario disponible','El periodo está cerrado'];
									alertify.error(array_errors[r])
								}
							}
						})
					}
				}
				function load_horometro_tiquete(idRegistro,idActividad){
					$('#modalCargadoresData').modal('show')
					$('#div_menu_modal_cargadores').html('')
					//let lugar_trabajo = $('#lugar_trabajo').val()
		    	let cadena = "band=" + 36 +
								"&idRegistro=" + idRegistro +
								"&idActividad=" + idActividad;
					$.ajax({
						type: "POST",
						url: "buscar_v2.php",
						data: cadena,
						success: function (r){
							//console.log(r)
							split = r.split("||");
							$('#tittle_modal_cargadores').html(split[0])
							$('#div_menu_modal_cargadores').html(split[1])
						}
					})
				}
				function load_producto_negocio(){
					let UnidadDeNegocio = $('#UnidadDeNegocio').val()
					let cadena = "band=" + 36.5 +
										"&UnidadDeNegocio=" + UnidadDeNegocio;
					$.ajax({
						type: "POST",
						url: "buscar_v2.php",
						data: cadena,
						success: function(r){
							//console.log(r)
							$('#producto').html(r).fadeIn()
						}
					})
				}
				function save_horometro_tiquete(idRegistro,idActividad){
					let horometro = 0
					if(idActividad==1){
						horometro = $('#horometro_inicial').val()
					}else{
						horometro = $('#horometro_final').val()
					}
					//let UnidadDeNegocio = $('#UnidadDeNegocio').val()
					//let producto = $('#producto').val()
					if(horometro != '' && horometro != null){
			    	let cadena = "band=" + 37 +
									"&idRegistro=" + idRegistro +
									"&horometro=" + horometro +
									"&idActividad=" + idActividad;
									//"&UnidadDeNegocio=" + UnidadDeNegocio +
									//"&producto=" + producto;
						$.ajax({
							type: "POST",
							url: "buscar_v2.php",
							data: cadena,
							success: function (r){
								//console.log(r)
								if(r == 1){
									$('#modalCargadoresData').modal('hide')
									cargar_menu_body(32,0)
								}
							}
						})
					}else{
						alertify.error('Complete el campo faltante')
					}
				}
				function ver_horometros_tiquete(idRegistro){
					$('#modalCargadoresData').modal('show')
					$('#div_menu_modal_cargadores').html('')
					//let lugar_trabajo = $('#lugar_trabajo').val()
		    	let cadena = "band=" + 37.5 +
								"&idRegistro=" + idRegistro;
					$.ajax({
						type: "POST",
						url: "buscar_v2.php",
						data: cadena,
						success: function (r){
							//console.log(r)
							split = r.split("||");
							$('#tittle_modal_cargadores').html(split[0])
							$('#div_menu_modal_cargadores').html(split[1])
						}
					})
				}
				$(document).on('submit', '#frmEnviarFoto', (e) => {
				    var data=getFiles();
				    data=getFormData("frmEnviarFoto",data);
				    $.ajax({
				        url:"recibe.php",
				        type:"POST",
				        data:data,
				        dataType:"json",
				        contentType:false,
				        processData:false,
				        cache:false
				    }).done(function(data){
				    		//console.log(data)
				        if(data.ok==1){
				        	$('#modalCreacionEstructura').modal('hide')
									$('#tittle_modal_creacion').html('')
									$('#div_menu_modal_creacion').html('')
				        	cargar_menu_body(32,0)
				          //  alert("datos enviados correctamente\n\n"+data.message);
				        }else{
				            alert("ha habido algun error\n\n"+data.message);
				        }
				    });
					return false;
				});
				 
				/**
				 * Función que pone el archivo en un FormData
				 * @return FormData
				 */
				function getFiles(){
					var idFiles=document.getElementById("idFiles");
					// Obtenemos el listado de archivos en un array
					var archivos=idFiles.files;
					// Creamos un objeto FormData, que nos permitira enviar un formulario
					// Este objeto, ya tiene la propiedad multipart/form-data
					var data=new FormData();
					// Recorremos todo el array de archivos y lo vamos añadiendo all
					// objeto data
					for(var i=0;i<archivos.length;i++){
						// Al objeto data, le pasamos clave,valor
						data.append("archivo"+i,archivos[i]);
					}
					return data;
				}
				 
				/**
				 * Función que recorre todo el formulario para apadir en el FormData los valores del formulario
				 * @param string id hace referencia al id del formulario
				 * @param FormData data hace referencia al FormData
				 * @return FormData
				 */
				function getFormData(id,data){
					$("#"+id).find("input,select").each(function(i,v) {
				        if(v.type!=="file") {
				            if(v.type==="checkbox" && v.checked===true) {
				                data.append(v.name,"on");
				            }else{
				                data.append(v.name,v.value);
				            }
				        }
					});
					return data;
				}
				function load_tiquete_cargador(idRegistro,Tiquete,variable){
					let texto = ""
					let txt_btn = ""
					if(variable==1){
						texto = "¿Desea finalizar el tiquete # "
						txt_btn = "Finalizar"
						swal(texto + Tiquete + '?', {
				        icon: "warning",
				        buttons: {
				            cancel: "Cancelar",
				            catch: {
				                text: txt_btn,
				                value: "catch",
				            },
				        },dangerMode: false,
				    })
				    .then((value)=>{
				        switch (value){
				     
				        case "catch":
				            var band = 38.5;
				            cadena = "idRegistro=" + idRegistro +
				                "&band=" + band;
						        $.ajax({
						            type: "POST",
						            url: "buscar_v2.php",
						            data: cadena,
						            success: function (r){
						              //console.log(r);
						              if(r == 1){
						              	if(variable==2){
						              		$('#modalCargadoresData').modal('hide')
						              		$('#tittle_modal_cargadores').html('')
															$('#div_menu_modal_cargadores').html('')
						              	}
						              	cargar_menu_body(32,0)
						              }else{
						              	alertify.error(r)
						              }
						            }
						        });
				            break;
				        default:
				            //swal("Got away safely!");
				        }
				    });
			  	}else if(variable==2){
						//texto = "¿Desea cerrar la asignación del tiquete # "
						//txt_btn = "Sí"
						$('#modalCargadoresData').modal('hide')
						$('#modalCreacionEstructura').modal('show')
						$('#tittle_modal_creacion').html('<b> Tiquete #'+Tiquete+'</b> - Asignar foto')
						let body = `<form id="frmEnviarFoto">
								<center><label>Seleccionar foto</label></center>
								<input type="file" id="idFiles" name="file" class="form-control">
								<input type="hidden" id="idRegistro" name="idRegistro" value="`+idRegistro+`"><br>
						    <input type="submit" value="enviar" class="form-control btn-success">
								</form>`
						$('#div_menu_modal_creacion').html('<div class="modal-body">'+body+'</div>')
					}
				}
				function ver_tiquete_anexo(idRegistro,Tiquete){
					Shadowbox.init({ language: "es", players:  ['img', 'html', 'iframe', 'qt', 'wmp', 'swf', 'flv'] });
					setTimeout(function() {
							ancho = screen.width;
	        		alto = screen.height;
	        		contenido= '<embed src="imagen_mostrar.php?id=\'' + idRegistro + '" width="100%" height="100%"></embed>';
	        		Shadowbox.open({
				        content: '<div class="embed-container" style ="position: absolute; top:0;  left: 0; width: 100%;  height: 100%;">' + contenido +'</div>',
				        player: "html",
				        title: 'Tiquete #'+Tiquete,
				      	width: ancho,
				        height: alto
				    	});   
					}, 500);
				}
				function asignacion_tiempos_tiquete(idRegistro,variable){ 
					if(variable==0){
						$('#modalCargadoresData').modal('show')
						$('#div_menu_modal_cargadores').html('')
					}
		    	let cadena = "band=" + 41 +
								"&idRegistro=" + idRegistro;
					$.ajax({
						type: "POST",
						url: "buscar_v2.php",
						data: cadena,
						success: function (r){
							//console.log(r)
							split = r.split("||");
							$('#tittle_modal_cargadores').html(split[0])
							$('#div_menu_modal_cargadores').html(split[1])
						}
					})
				}
				function load_actividad_cargador(idRegistro){
					let UnidadDeNegocio = $('#UnidadDeNegocio').val()
					let Producto = $('#producto').val()
					let idDestino = $('#lugar_trabajo').val();
					let cadena = "band=" + 42 +
								"&idRegistro=" + idRegistro +
								"&UnidadDeNegocio=" + UnidadDeNegocio +
								"&Producto=" + Producto +
								"&idDestino=" + idDestino;
					$.ajax({
						type: "POST",
						url: "buscar_v2.php",
						data: cadena,
						success: function (r){
							//console.log(r)
							$('#Actividad').html(r).fadeIn()
						}
					})
				}
				function save_distribucion_cargador(idRegistro,iDescuento){
					let iError = 0
					if(iDescuento==0){
						var UnidadDeNegocio = $('#UnidadDeNegocio').val()
						var Producto = $('#producto').val()
						var Actividad = $('#Actividad').val()
						var Tiempo = $('#Tiempo').val()
						if(UnidadDeNegocio==0 || UnidadDeNegocio=='' || UnidadDeNegocio==null){
							$("#UnidadDeNegocio").prop("style","border: 1px solid; border-color: red")
	      			iError = iError + 1
						}else{
			      	$("#UnidadDeNegocio").prop("style","border: 1px solid; border-color: #ccc")
			      }
						if(Producto==0 || Producto=='' || Producto==null){
							$("#producto").prop("style","border: 1px solid; border-color: red")
	      			iError = iError + 1
						}else{
			      	$("#producto").prop("style","border: 1px solid; border-color: #ccc")
			      }
			      if(Actividad==0 || Actividad=='' || Actividad==null){
							$("#Actividad").prop("style","border: 1px solid; border-color: red")
	      			iError = iError + 1
						}else{
			      	$("#Actividad").prop("style","border: 1px solid; border-color: #ccc")
			      }
			      if(Tiempo==0 || Tiempo=='' || Tiempo==null){
							$("#Tiempo").prop("style","border: 1px solid; border-color: red")
	      			iError = iError + 1
						}else{
			      	$("#Tiempo").prop("style","border: 1px solid; border-color: #ccc")
			      }
			    }else{
			    	var Descuento = $('#descuento').val()
			    	var Tiempo = $('#Tiempo_descuento').val()
			    	if(Descuento==0 || Descuento=='' || Descuento==null){
							$("#descuento").prop("style","border: 1px solid; border-color: red")
	      			iError = iError + 1
						}else{
			      	$("#descuento").prop("style","border: 1px solid; border-color: #ccc")
			      }
			      if(Tiempo==0 || Tiempo=='' || Tiempo==null){
							$("#Tiempo_descuento").prop("style","border: 1px solid; border-color: red")
	      			iError = iError + 1
						}else{
			      	$("#Tiempo_descuento").prop("style","border: 1px solid; border-color: #ccc")
			      }
			    }
					if(iError>0){
						alertify.error('Complete los campos marcados')
					}else{
						if(iDescuento==0){
							var cadena = "band=" + 43 +
									"&idRegistro=" + idRegistro +
									"&UnidadDeNegocio=" + UnidadDeNegocio +
									"&Producto=" + Producto +
									"&Actividad=" + Actividad +
									"&iDescuento=" + iDescuento +
									"&Tiempo=" + Tiempo;
						}else{
							var cadena = "band=" + 43 +
									"&idRegistro=" + idRegistro +
									"&Descuento=" + Descuento +
									"&iDescuento=" + iDescuento +
									"&Tiempo=" + Tiempo;
						}
						$.ajax({
							type: "POST",
							url: "buscar_v2.php",
							data: cadena,
							success: function (r){
								console.log(r)
								if(r==1){
									asignacion_tiempos_tiquete(idRegistro,1)
								}else{
									if(r==2){
										alertify.warning('El tiempo asignado supera al disponible')
									}else if(r==0){
										alertify.error('El tiempo asignado supera al disponible')
									}
								}
							}
						})
					}
				}
				function delete_distribucion_cargador(datos){
					split = datos.split(",");
					swal("¿Desea eliminar la actividad de " + split[4] + ' por la unidad de negocio ' + split[5] + ' y el producto ' + split[6] + '?', {
			        //icon: "warning",
			        buttons: {
			            cancel: "Cancelar",
			            catch: {
			                text: 'Eliminar',
			                value: "catch",
			            },
			            /*defeat: {
			                text: 'texto',
			            },*/
			        },dangerMode: false,
			    })
			    .then((value)=>{
			        switch (value){
			     
			        case "catch":
			            var band = 44;
			            cadena = "idRegistro=" + split[0] +
					        				"&UnidadDeNegocio=" + split[1] +
					        				"&Producto=" + split[2] +
					        				"&Actividad=" + split[3] +
					                "&band=" + band;
					        $.ajax({
					            type: "POST",
					            url: "buscar_v2.php",
					            data: cadena,
					            success: function (r){
					              //console.log(r);
					              if(r==1){
													asignacion_tiempos_tiquete(split[0],1)
					              }else{
					              	alertfity.error(r)
					              }
					            }
					        });
			            break;
			        default:
			            //swal("Got away safely!");
			        }
			    });
				}
				function delete_tiquete_cargador(idRegistro,tiquete){
					swal("¿Desea eliminar el tiquete # " + tiquete + '?', {
			        //icon: "warning",
			        buttons: {
			            cancel: "Cancelar",
			            catch: {
			                text: 'Eliminar',
			                value: "catch",
			            },
			            /*defeat: {
			                text: 'texto',
			            },*/
			        },dangerMode: false,
			    })
			    .then((value)=>{
			        switch (value){
			     
			        case "catch":
			            var band = 45;
			            cadena = "idRegistro=" + idRegistro +
					                "&band=" + band;
					        $.ajax({
					            type: "POST",
					            url: "buscar_v2.php",
					            data: cadena,
					            success: function (r){
					              //console.log(r);
					              if(r==1){
													cargar_menu_body(32,0)
					              }else{
					              	alertfity.error('No se pudo eliminar el tiquete #'+tiquete)
					              }
					            }
					        });
			            break;
			        default:
			            //swal("Got away safely!");
			        }
			    });
				}
		function load_report(informe){
			let lugar_trabajo = $('#lugar_trabajo').val()
			$('.onclick_report').removeClass('black_letters')
			$('.'+informe).addClass('black_letters')
			cadena = "informe=" + informe +
              "&band=" + 46 +
              "&lugar_trabajo=" + lugar_trabajo;
      // console.log(cadena);
      $.ajax({
          type: "POST",
          url: "buscar_v2.php",
          data: cadena,
          success: function (r){
            // console.log(r);
            $('#div_informativo').hide();
						$('#btn_abrir_alimentacion').hide();
						$('#btn_abrir_produccion').hide();
						$('#div_menu_body').html(r).fadeIn();
						$('.btn_ocultar').hide();
						$('#div_modificar_prod').hide();
          }
      });
		}
		function search_cargadores(){
			$('#btn_search_carg').addClass('loader_min')
			//let list_empresa = $('#list_empresa_inventario').val()
			let list_unidad_negocio = $('#list_unidad_negocio').val()
			let list_destino_consulta = $('#list_destino_consulta').val()
			let list_proveedor_consulta = $('#list_proveedor_consulta').val()
			let fechaInicio = $('#fechaInicio').val()
			let fechaFin = $('#fechaFin').val()
			cadena = "band=" + 47 + 
              //"&Empresa=" + list_empresa +
              "&UnidadDeNegocio=" + list_unidad_negocio +
              "&Destino=" + list_destino_consulta +
              "&Proveedor=" + list_proveedor_consulta +
              "&fechaInicio=" + fechaInicio +
              "&fechaFin=" + fechaFin;
      $.ajax({
          type: "POST",
          url: "buscar_v2.php",
          data: cadena,
          success: function (r){
            //console.log(r);
						$('#div_consulta_body').html(r).fadeIn()
						$('#tabla_excel').val(r)
						$('#btn_search_carg').removeClass('loader_min')
          }
      });
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
    function exportar (Data, type, showFields){

  // console.log('datos '+Data)
        // Data       : {}. Can be any data you want to export (records, columns, custom, etc...).
        // type       : string. Extension of file name 'xls' or 'csv' are possible. By default 'excel' format is done on array
        // showFields : boolean (optional). Insert field names on top of the file data. By default 'false'

            var arrData = typeof Data != 'object' ? JSON.parse(Data) : Data;
            fileName = 'ExportData.' + type;
            var Data = '';
            // show fields on first row ?
            if (showFields) {
                var row = "";
                for (var index in arrData[0]) {
                    if (row !="" && type =='csv') row +=',';
                    row += index + '\t';
                }
                row = row.slice(0, -1);
                Data += row + '\r\n';
            }
            // Prepare array data format
            for (var i = 0; i < arrData.length; i++) {
                var row = "";
                for (var index in arrData[i]) {
                    if (row !="" && type =='csv') row +=',';
                    row += (type == 'xls') ? '"' + arrData[i][index] + '"\t' :  arrData[i][index] + '\t'
                }
                row.slice(0, row.length - 1);
                Data += row + '\r\n';
            }
            // No data?
            if (Data == '') {
                w2alert('No Data Found');
                return;
            }
            // console.log(Data)
            var link = document.createElement("a");
            // browser with HTML5 support download attribute
            if (link.download !== undefined) {
                var uri = 'data:application/vnd.ms-excel,' + escape(Data);
                link.setAttribute ( 'href', uri);
                link.setAttribute('style', "visibility:hidden");
                link.setAttribute ('download', fileName);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
            // IE 10,11+
            else if (navigator.msSaveBlob) {
                var blob = new Blob([Data], {
                    "type": "text/csv;charset=utf8;"      
                });
                navigator.msSaveBlob(blob, fileName);
            }
            // old IE 9-  remove this part ?? deprecated browsers ??
            var ua = window.navigator.userAgent;
            var ie = ua.indexOf('MSIE ');
            if ((ie > -1)) {
                if (document.execCommand) {
                    var oWin = window.open("about:blank","_blank");
                    oWin.document.write(Data);
                    oWin.document.close();
                    var success = oWin.document.execCommand('SaveAs', true, fileName)
                    oWin.close();
                }
            }
}
	</script>
</head>
<!--onload="control_time()"-->
<body>
	<?php include './Header.php';?>
	<input type="hidden" name="lugar_trabajo_produccion" id="lugar_trabajo_produccion">
	<div class="container-fluid" style="margin-left: 5px; margin-right: 5px;">
		<div class="row">
			<input id="val_change_option_edition" type="hidden">
			<div class="col-sm-2"><a href="INSTRUCTIVO-produccion.pdf" target="_blank"><img src="../Imagenes/pdf-solictud.png"></a><br><b>Manual</b></div>
			<div class="col-sm-2"></div>
			<div class="col-sm-4">
				<center>
					<h3 style="margin-top: -10px;">Centro Trabajo</h3>
					<select class="form-control" id="lugar_trabajo" name="lugar_trabajo" onchange="guardar_lugar_trabajo()">
						<option value="0" selected="" disabled="">Seleccione</option>
						<?php
						if(isset($_SESSION['Array_empresa']['CONSULTAS'])){
							?><option value="-1">TODOS</option><?php
						}
						$txt_array_destino = "";
						$params = array();
						$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
						$sql_permiso = "SELECT UsuariosPermisos_detalle.tabla, UsuariosPermisos_detalle.idVariable 
						FROM UsuariosPermisos_detalle 
								LEFT JOIN Permisos ON UsuariosPermisos_detalle.idPermiso = Permisos.idPermiso
						WHERE idUsuario='$idUsuario' AND Permiso='PRODUCCION' AND tabla='DespachadoDesde'";
						$resul_permiso=sqlsrv_query($conn,utf8_decode($sql_permiso),$params,$options);
						$row_permiso = sqlsrv_num_rows($resul_permiso);
						if($row_permiso>0){
							$band_per = 0;
							while($per = sqlsrv_fetch_array($resul_permiso)){
								$array_permisos[$band_per] = $per['idVariable'];
								$band_per++;
							}
							$abcd = implode("','",$array_permisos);
							$txt_array_destino = " AND idDestino IN ('$abcd')";
						}

						$sql = "SELECT Destino,idDestino FROM vActividades_cargadores_destinos WHERE Produccion=1 $txt_array_destino GROUP BY Destino,idDestino ORDER BY Destino";
						$params = array();
			            $options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);     
			            $resultado=sqlsrv_query($conn,utf8_decode($sql),$params,$options);
			            $filas=sqlsrv_num_rows($resultado);
			            while($aa = sqlsrv_fetch_array($resultado)){
							?><option value="<?php echo $aa['idDestino']; ?>"><?php echo utf8_encode($aa['Destino']); ?></option><?php
						}?>
					</select><br>
				</center>
			</div>
		</div>
		<nav id="nav_menu_opciones" class="navbar navbar-default" role="navigation" style="background-color: #D9DAE1;"></nav>
    <div class="row" id="div_informativo"></div>
		<div class="row" id="div_menu_body"></div>
	</div>
    <!-------------------------------------- MODAL WINDOW FOR CREATE ESTRUCTURE OF THE UNK  ------------------------------------>
    <div class="modal fade" id="modalCreacionEstructura" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="tittle_modal_creacion"></h4>
                </div>
                <div id="div_menu_modal_creacion"></div>
            </div>
        </div>
    </div>
    <!-------------------------------------- MODAL CARGADORES DATA ------------------------------------>
    <div class="modal fade" id="modalCargadoresData" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="tittle_modal_cargadores"></h4>
                </div>
                <div id="div_menu_modal_cargadores"></div>
            </div>
        </div>
    </div>
    <!-----------------    MODAL WINDOW FOR ALIMENTATION OR PRODUCTION OR INVENTORY BALANCE   ----------------------------->
    <div class="modal fade" id="modalAsignacionDatos" tabindex="0" role="dialog" style="z-index: 5000;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="tittle_modal_asignacion"></h4>
            </div>
            <div id="div_menu_modal_asignacion"></div>
        </div>
    </div>
    <input type="hidden" id="fecha_abcde" value="<?php echo date('Y-m-d'); ?>">
</body>
<script type="text/javascript">

	function descargar_informe(frm) {
        document.getElementById('form1').target ="_blank";
        document.getElementById('form1').method ="POST";
        document.getElementById('form1').action ="buscar.php";
        document.getElementById('form1').submit();
    }
	/************************************************************************/
	function llenar_sel_equipo(){
        var tipo_maquinaria = $('#tipo_maquinaria').val();
        var actividad = $('#actividad').val();
        var proveedor = $('#proveedor').val();
        var lugar_trabajo = $('#lugar_trabajo').val();
        if(tipo_maquinaria!='0' && proveedor!='0'){
            band = 1;
            $.post("buscar.php", {tipo_maquinaria: tipo_maquinaria, actividad: actividad, proveedor: proveedor, band: band}, 
            function(mensaje){
              //  console.log(mensaje);
                $('#Equipo').html(mensaje).fadeIn();
            });
        }else{
            $('#Equipo').html('<option value="0">No hay maquinaria</option>').fadeIn();
        }
    }
    function llenar_sel_equipo_consulta(){
        var tipo_maquinaria = $('#tipo_maquinaria_1').val();
        var lugar_trabajo = $('#lugar_trabajo').val();
        band = 12;
        $.post("buscar.php", {tipo_maquinaria: tipo_maquinaria, lugar_trabajo: lugar_trabajo, band: band}, 
        function(mensaje){
            //console.log(mensaje);
            $('#Equipo_1').html(mensaje).fadeIn();
        });
    }
    function activar_botton(){
        var empresa = $('#empresa_1').val(); 
        if(empresa==0){
            $('#generar').prop('disabled', true);
            $('#descargar').prop('disabled', true);
        }else{
            $('#generar').prop('disabled', false);
            $('#descargar').prop('disabled', false);
        }
    }
    function generar_informe(){
        var fecha_1 = $('#fecha_1').val(); 
        var fecha_2 = $('#fecha_2').val(); 
        bandera=0;
        if(fecha_1=="") {
            $('#fecha_1').css({'border-color':"#F26F37"});
            bandera=1;
        }else{ 
            $('#fecha_1').css({'border-color':"#A8B0AF"});
        }
        if (fecha_2==""){
            $('#fecha_2').css({'border-color':"#F26F37"});
            bandera=1;
        }else{ 
            $('#fecha_2').css({'border-color':"#A8B0AF"});
        }
        if(bandera ==1)
            alertify.error('Complete los campos....');
        else{
        		$('#generar').addClass('loader_min')
            var empresa_1 = $('#empresa_1').val(); 
            var proveedor_1 = $('#proveedor_1').val(); 
            if($('#lugar_trabajo').val()==-1)
            	var patio_1 = $('#patio_1').val(); 
            else
            	var patio_1 = $('#lugar_trabajo').val(); 
            var actividad_1 = $('#actividad_1').val(); 
            var tipo_maquinaria_1 = $('#tipo_maquinaria_1').val(); 
            var Equipo_1 = $('#Equipo_1').val(); 
            var grupo_material_1 = $('#grupo_material_1').val();
            var material_alimentado_1 = $('#material_alimentado_1').val(); 
            band=10;
            cadena = "band=" + band +
                "&fecha_ini=" + fecha_1 +
                "&fecha_fin=" + fecha_2 +
                "&empresa_1=" + empresa_1 +
                "&proveedor_1=" + proveedor_1 +
                "&patio_1=" + patio_1 +
                "&actividad_1=" + actividad_1 +
                "&tipo_maquinaria_1=" + tipo_maquinaria_1 +
                "&Equipo_1=" + Equipo_1 +
                "&grupo_material_1=" + grupo_material_1 +
                "&material_alimentado_1=" + material_alimentado_1;
            $.ajax({
                type: "POST",
                url: "buscar.php",
                data: cadena,
                success: function (r){
                 //   console.log(r);
                    if(r!=1){
                        $('#div_resultados').show();
                        $('#div_resultados').html(r);
                    }else{
                        alertify.error('No existen registros...  Verifique filtros');
                        $('#div_resultados').hide();
                    }
                    $('#generar').removeClass('loader_min')
                }
            });
        }
    }
    var position_array_clasif = 0
    var array_clasif_name = new Array()
    var array_clasif_id = new Array()
    var array_clasif_tm = new Array()
    var array_clasif_pila = new Array();
    var idClasificacion_mod = ''
    function validar_registros_clasif(val){
    		let recibo = $('#recibo').val();
	     	let fecha = $('#fecha').val();
	     	let semana = $('#semana').val();
	     	let empresa = $('#empresa').val();
	     	let proveedor = $('#proveedor').val();
	     	let usuario = $('#usuario').val();
	     	let actividad = $('#actividad').val();
	     let Equipo = $('#Equipo').val();
	     let patio = $('#lugar_trabajo').val();
	     let pila = $('#pila').val();
	     let tm_alimen = $('#tm_alimen').val();
	     let horas_alimen = $('#horas_alimen').val();
	     let material_alimentado = $('#material_alimentado').val();
	     let material_objetivo = $('#material_objetivo').val();
	     /////////////////////////////////////////////////////////////////////
	     let material = $('#materia_prod').val();
	     let txt_material = $('#materia_prod option:selected').html()
	     var tm = $('#tm_producido').val();
	     
	    	let txt_error = ''
	     if(fecha == '' || fecha == null){
	         txt_error = txt_error + ' *Fecha \n' 
	     }
	     if(empresa == '0' || empresa == null){
	         txt_error = txt_error + ' *Empresa \n'
	     }
	     if(proveedor == '0' || proveedor ==  null){
	         txt_error = txt_error + ' *Proveedor equipo \n'
	     }
	     if(usuario == '0' || usuario ==  null){
	         txt_error = txt_error + ' *Usuario \n'
	     }
	     if(actividad == '0' || actividad == null){
	         txt_error = txt_error + ' *Actividad \n'
	     }
	     if(Equipo == '0' || Equipo == null){
	         txt_error = txt_error + ' *Equipo \n'
	     }
	     if(patio == '0' || patio == null){
	         txt_error = txt_error + ' *Centro de trabajo \n'
	     }
	     if(material_alimentado == '0' || material_alimentado == null){
	         txt_error = txt_error + ' *Material alimentado \n'
	     }
	     if(material_objetivo == '0' || material_objetivo == null){
	         txt_error = txt_error + ' *Material objetivo \n'
	     }
	     if(pila == '' || pila == null){
	         txt_error = txt_error + ' *Pila \n'
	     }
	      if(horas_alimen == '0' || horas_alimen == ''){
	          txt_error = txt_error + ' *Horas equipo \n'
	      }
	     if(tm_alimen == 0 || tm_alimen == ''){
	         txt_error = txt_error + ' *TM alimentadas \n'
	     }
	     	if(val==1){
		     if(material == '0' || material == null){
		        txt_error = txt_error + ' *Material producido \n'
		     }
		     if(tm == 0 || tm == ''){
		         txt_error = txt_error + ' *TM producidas \n'
		     }
		  }
		  return txt_error
    }
    function material_producido(n){
        /////////////////////////////////////////////////////////////////////
        let tm_alimen = $('#tm_alimen').val();
        let material = $('#materia_prod').val();
        let txt_material = $('#materia_prod option:selected').html()
        var tm = $('#tm_producido').val();
        var pila_producida =  $('#pila2').val();
       	let txt_error = ''
       	txt_error = validar_registros_clasif(1)
        if(n == '0'){
				txt_error = ''
        }
        if(txt_error != ''){
            alert('Complete todos los campos: \n' + txt_error);
        }else{
        		let tm_acumulado = 0
        		let porcent_acumulado = 0
        		if(position_array_clasif==0){
        			$('.block').prop("disabled",true)
        		}else if(position_array_clasif>0){
        			for (var i = 0; i < 	array_clasif_tm.length; i++){
        				tm_acumulado = parseFloat(tm_acumulado) + parseFloat(array_clasif_tm[i])
        				porcent_acumulado = parseFloat(porcent_acumulado) + ((parseFloat(array_clasif_tm[i])/parseFloat(tm_alimen))*100)
        			}
        		}
        		if(tm != '' && tm != null && tm != '0' && tm != 0){
        			tm_acumulado = parseFloat(tm_acumulado) + parseFloat(tm)
        			tm_acumulado = tm_acumulado.toFixed(2)
        			porcent_acumulado = parseFloat(porcent_acumulado) + ((parseFloat(tm)/parseFloat(tm_alimen))*100)
        			porcent_acumulado = porcent_acumulado.toFixed(2)
        		}
            if(parseFloat(tm_acumulado) <= parseFloat(tm_alimen)){
            	if(tm != '' && tm != null && tm != '0' && tm != 0){
	            	array_clasif_name[position_array_clasif] = txt_material
	            	array_clasif_id[position_array_clasif] = material
	        			array_clasif_tm[position_array_clasif] = tm
	        			array_clasif_pila[position_array_clasif] = pila_producida
	        			// console.log(array_clasif_name,'-',array_clasif_id,'-',array_clasif_tm);
	        			position_array_clasif = position_array_clasif + 1
	        		}
        			let txt_array_clasif_id = array_clasif_id.join("','")
        			load_material_producido(txt_array_clasif_id)
        			$('#tm_producido').val('');
        			let bodyAsignados = ''
        			for (var i = 0; i < 	array_clasif_id.length; i++){
        				let porcent_tm = (parseFloat(array_clasif_tm[i])/parseFloat(tm_alimen))*100
        				bodyAsignados += `
                    <div class="row">
                        <div class="col-sm-3">
                            <a href="javascript:carga_modificar('` + array_clasif_id[i] + `')">` + array_clasif_name[i] + `</a>
                        </div>
                        <div class="col-sm-3">` + array_clasif_pila[i] + `</div>
                        <div class="col-sm-3">` + array_clasif_tm[i] + `</div>
                        <div class="col-sm-3">` + porcent_tm + `%</div>                        
                    </div>`
        			}
        			$('#div_material_prod').html(bodyAsignados);
        			$('#tm_acumulado').html(tm_acumulado)
        			$('#porcen_acumulado').html(porcent_acumulado + ' %');
        			if(tm_acumulado==parseFloat(tm_alimen)){
	               document.getElementById('guardar').disabled="";
	            }else{
	               document.getElementById('guardar').disabled="true";
	            }
            }else{
                alertify.error('Hay más material producido que alimentado');
            } 
        }
    }
    function llenar_sel_material(){ 
        var numerotransaccion = $('#numerotransaccion').val();
        var grupo = $('#grupo_material').val();
        band = 4;
        $.post("buscar.php", {grupo: grupo, band: band, numerotransaccion: numerotransaccion}, 
        function(mensaje) {
            split = mensaje.split("||");
            //console.log(mensaje);
            $('#material_alimentado').html(split[0]).fadeIn();
            $('#material_objetivo').html(split[0]).fadeIn();
            $('#materia_prod').html(split[0]).fadeIn();
            $('#materia_prod_mod').html(split[0]).fadeIn();
            if(split[1] != 0){
                $('#div_material_prod').html('');
                $('#tm_acumulado').html('');
                $('#tm_acumulado1').val('0');
                $('#porcen_acumulado').html('');
                alert('Se ha cambiado el material alimentado');
                document.getElementById('guardar').disabled="true";
            }
        });
    }
    function llenar_sel_material_consul(){
        var grupo = $('#grupo_material_1').val();
        lugar_trabajo = $('#lugar_trabajo').val();
        band = 14; 
        $.post("buscar.php", {grupo: grupo, lugar_trabajo: lugar_trabajo, band: band}, 
        function(mensaje) {
          //  console.log(mensaje);
            $('#material_alimentado_1').html(mensaje).fadeIn();
        });
    }
    function carga_modificar(idClasificacion){
        band = 5; 
        idClasificacion_mod = idClasificacion
        let txt_array_detalle = array_clasif_id.join("','")
        let position = array_clasif_id.indexOf(idClasificacion)
        var material_alimentado = $('#material_alimentado').val()
        var grupo_material = $('#grupo_material').val()

        $.post("buscar.php", {idClasificacion: idClasificacion, txt_array_clasificacion: txt_array_detalle, band: band, grupo_material: grupo_material, material_alimentado: material_alimentado}, 
        function(mensaje) {
            //console.log(mensaje);
            //split = mensaje.split("||");
            $('#materia_prod_mod').html(mensaje).fadeIn();
            $('#tm_producido_mod').val(array_clasif_tm[position]);
            //$('#tm_producido_mod_1').val(split[1]);
            //$('#id_detalle_mod').val(id_detalle);
            $('#div_modificar_prod').show();
        });
    }
    function modificar_producido(){
        let material_mod = $('#materia_prod_mod').val()
        let txt_material_mod = $('#materia_prod_mod option:selected').html()
        let tm_mod = $('#tm_producido_mod').val()
        let tm_alimen = $('#tm_alimen').val()
        //var tm_mod_original = $('#tm_producido_mod_1').val();
        //var band = 6;
        let position = array_clasif_id.indexOf(idClasificacion_mod)
        if(tm_mod != '0' && tm_mod != '' && tm_mod != null){
        		let tm_acumulado = 0
	  			for (var i = 0; i < 	array_clasif_tm.length; i++){
	  				if(array_clasif_id[i]!=idClasificacion_mod){
	  					tm_acumulado = parseFloat(tm_acumulado) + parseFloat(array_clasif_tm[i])
	  					//tm_acumulado = tm_acumulado.toFixed(2)
	  				}
	  			}
		  		tm_acumulado = parseFloat(tm_acumulado) + parseFloat(tm_mod)
		  		tm_acumulado = tm_acumulado.toFixed(2)
		  		if(parseFloat(tm_acumulado)<=parseFloat(tm_alimen)){
	        		array_clasif_id[position] = material_mod
		        	array_clasif_name[position] = txt_material_mod
		        	array_clasif_tm[position] = tm_mod
		        	material_producido(0)
        			$('#div_modificar_prod').hide()
		      }else{
		      	alertify.error('Hay más material producido que alimentado')
		      }
        }else{
	        	array_clasif_id[position] = ''
	        	array_clasif_name[position] = ''
	        	array_clasif_tm[position] = ''
	        	array_clasif_id = array_clasif_id.filter(a => a != '')
	        	array_clasif_name = array_clasif_name.filter(a => a != '')
	        	array_clasif_tm = array_clasif_tm.filter(a => a != '')
	        	position_array_clasif=array_clasif_id.length
	        	material_producido(0)
        		$('#div_modificar_prod').hide()
	     }
    }
    function guardar_registro(get){
    		$('.btn-success').addClass('loader_min')
    		$('.btn-success').prop("disabled",true)
        var recibo = $('#recibo').val();
        var fecha = $('#fecha').val();
        var semana = $('#semana').val();
        var empresa = $('#empresa').val();
        var proveedor = $('#proveedor').val();
        var usuario = $('#usuario').val();
        var actividad = $('#actividad').val();
        var Equipo = $('#Equipo').val();
        var patio = $('#lugar_trabajo').val();
        var pila = $('#pila').val();
        var tm_alimen = $('#tm_alimen').val();
        var material_alimentado = $('#material_alimentado').val();
        var material_objetivo = $('#material_objetivo').val();
        var horas_equipo = $('#horas_alimen').val();
        band = 7;
        let txt_error = ''
       	txt_error = validar_registros_clasif(0)
        if(txt_error != ''){
            alert('Complete todos los campos \n' + txt_error);
        }else{
          cadena = "recibo=" + recibo +
                 "&fecha=" + fecha +
                 "&semana=" + semana +
                 "&empresa=" + empresa +
                 "&proveedor=" + proveedor +
                 "&actividad=" + actividad +
                 "&Equipo=" + Equipo +
                 "&patio=" + patio +
                 "&material_alimentado=" + material_alimentado +
                 "&material_objetivo=" + material_objetivo +
                 "&horas_equipo=" + horas_equipo +
                 "&pila=" + pila +
                 "&tm_alimen=" + tm_alimen +
                 "&array_clasif_id=" + array_clasif_id.join("','") +
                 "&array_clasif_name=" + array_clasif_name.join("','") +
                 "&array_clasif_tm=" + array_clasif_tm.join("','") +
                 "&array_clasif_pila=" + array_clasif_pila.join("','") +
                 "&band=" + band +
                 "&get_without_inv=" + get;
            $.ajax({
                type: "POST",
                url: "buscar.php",
                data: cadena,
                success: function (r){
                    //console.log(r);
                    $('.btn-success').removeClass('loader_min')
    								$('.btn-success').prop("disabled",false)
                    array_errors = ['Error al registrar','Se registró correctamente','No hay inventario disponible','El periodo está cerrado'];
                    if(r==1){
                    	alert('Se guardó correctamente');
                      array_clasif_id.length = 0
						        	array_clasif_name.length = 0
						        	array_clasif_tm.length = 0
						        	position_array_clasif=array_clasif_id.length
                      cargar_menu_body(0,0)
                    }else if(r==2){
	                    swal("No hay inventario disponible. ¿Desea registrarlo igualmente?", {
									        icon: "warning",
									        buttons: {
									            cancel: "Cancelar",
									            catch: {
									                text: 'ACEPTAR',
									                value: "catch",
									            },
									            /*defeat: {
									                text: 'texto',
									            },*/
									        },dangerMode: false,
									    })
									    .then((value) => {
									        switch (value) {
									     
									        case "catch":
									            guardar_registro(1)
									            break;
									        default:
									            //swal("Got away safely!");
									        }
									    });
									  }else{
									  	alertify.error(array_errors[r])
									  }
                }
            });
        }
    }
    function opcion(){
        if($('#select_opcion').val() == 0){
            $('#div_consulta_clasif').show();
            //$('#div_registro_clasif').hide();
        }else if($('#select_opcion').val() == 1){
            $('#div_registro_clasif').show();
            //$('#div_consulta_clasif').hide();
        }
    }
    function periodos(){
        var fecha = $('#fecha').val();
        band = 8;
        $.post("buscar.php", {fecha: fecha, band: band}, 
        function(mensaje) {
            //console.log(mensaje);
            split = mensaje.split("||");
            if(split[0] == 1){
                $('#semana').val(split[1]);
                if($('#numerotransaccion').val() != '0'){
                    document.getElementById('Nuevo_reg').disabled="";
                    document.getElementById('guardar').disabled="";
                }
            }else{
                $('#semana').val(split[1]);
                alertify.error('El periodo está cerrado en esta fecha');
                document.getElementById('Nuevo_reg').disabled="true";
                document.getElementById('guardar').disabled="true";
            }
            //$('#materia_prod').html(mensaje).fadeIn();
        });
    }
    function load_actividad(){
        var patio = $('#lugar_trabajo').val()
        var proveedor = $('#proveedor').val()
        cadena = "patio=" + patio +
        				"&proveedor=" + proveedor +
                "&band=" + 15;
        $.ajax({
            type: "POST",
            url: "buscar.php",
            data: cadena,
            success: function (r){
                //console.log(r);
                $('#actividad').html(r).fadeIn()
                load_clase_equipo()
            }
        });
    }
    function load_clase_equipo(){
        var proveedor = $('#proveedor').val()
        var actividad = $('#actividad').val()
        if(proveedor!='0' && actividad!='0'){
            cadena = "proveedor=" + proveedor +
                    "&actividad=" + actividad +
                     "&band=" + 16;
            $.ajax({
                type: "POST",
                url: "buscar.php",
                data: cadena,
                success: function (r){
                    //console.log(r);
                    $('#tipo_maquinaria').html(r).fadeIn()
                    llenar_sel_equipo()
                }
            });
        }else{
            $('#tipo_maquinaria').html('<option value="0">No hay registros</option>').fadeIn()
            llenar_sel_equipo()
        }
    }
    function delete_tiquete_clasificacion(id_clasif,num_recibo){
        swal("    ¿Deseas eliminar el recibo # " + num_recibo + "? \n\n ¡Una vez eliminado, no podrás recuperar este registro!", {
            icon: "warning",
            buttons: {
                cancel: "Cancelar",
                catch: {
                    text: "Eliminar!",
                    value: "catch",
                },
                /*defeat: {
                    text: 'texto',
                },*/
            },dangerMode: false,
        })
        .then((value)=>{
            switch (value){
         
            /*case "defeat":
                var band = 9;
            break;*/
         
            case "catch":
                var band = 17;
                break;

            default:
                //swal("Got away safely!");
            }
            
            cadena = "id_clasif=" + id_clasif +
                    "&num_recibo=" + num_recibo +
                    "&band=" + band;
            $.ajax({
                type: "POST",
                url: "buscar.php",
                data: cadena,
                success: function (r){
                    //console.log(r);
                    if (r == 1){
                        if(band == 17){
                            swal("Poof!", "Ha sido eliminado!", "success");
                        }
                        generar_informe();
                    }
                }
            });
        });
    }
    function load_material_producido(variable){
        var material_alimentado = $('#material_alimentado').val()
        var grupo_material = $('#grupo_material').val()
        cadena = "material_alimentado=" + material_alimentado +
                "&grupo_material=" + grupo_material +
                "&variable=" + variable +
                "&band=" + 19;
        $.ajax({
            type: "POST",
            url: "buscar.php",
            data: cadena,
            success: function (r){
                //console.log(r);
                if(variable=='0'){
                	$('#material_objetivo').html(r).fadeIn()
                }
                $('#materia_prod').html(r).fadeIn()
                //$('#materia_prod_mod').html(r).fadeIn()
            }
        });
    }
</script>
</html>