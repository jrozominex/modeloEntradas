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
    </style>
	<script language="JavaScript">
		function datatable_tiquete(){
            var table = $('#table_tiquete').DataTable( {
                "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                "ordering": true,
                "info":     false,
                stateSave: true,
                scrollY:        '50vh',
                //"scrollX": true,
                "scrollCollapse": true/*,
                "paging":         true*/
            });
        }
        function datatable_factura(){
            var table = $('#table_factura').DataTable( {
                "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Todos"]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                "ordering": true,
                "info":     false,
                stateSave: true,
                scrollY:        '50vh',
                //"scrollX": true,
                "scrollCollapse": true/*,
                "paging":         true*/
            });
        }
	   	$(document).ready(function(){
	   		//llenar_pre_fact();
		});
		function load_pre_liquidaciones(val){
			$('#pre_liquidacion').prop("disabled",true)
			$('#btn_load_liq').hide()
			let cliente = $('#cliente').val()
			let proveedor = $('#proveedor').val()
			if(cliente!='0' && cliente !=null && proveedor != '0' && proveedor !=null){
				cadena = "band=" + 0 +
						"&cliente=" + cliente +
						"&proveedor=" + proveedor;
				$.ajax({
		            type: "POST",
		            url: "../modelo/facturacion_cargador.php",
		            data: cadena,
		            success: function(r){
		            	//console.log(r)
		            	array = r.split("||");
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
		        });
			}
		}
		function load_pre_liquidaciones_new(val){
			$('#pre_liquidacion').prop("disabled",true)
			$('#btn_load_liq').hide()
			let select_liquidador = $('#select_liquidador').val()
			let proveedor = $('#proveedor').val()
			cadena = "band=" + 8 +
					"&proveedor=" + proveedor +
					"&liquidador=" + select_liquidador;
			$.ajax({
	            type: "POST",
	            url: "../modelo/facturacion_cargador.php",
	            data: cadena,
	            success: function(r){
	            	//console.log(r)
	            	array = r.split("||");
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
	        });
		}
		function search_tiquetes(){
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
      			$('#btn_search_new').removeClass('loader_min')
      		}else{
      			cadena = "band=" + 1 +
      					"&cliente=" + cliente +
						"&proveedor=" + proveedor +
						"&fechaIni=" + fechaIni +
						"&fechaFin=" + fechaFin;
				$.ajax({
					type: "POST",
					url: "../modelo/facturacion_cargador.php",
					data: cadena,
					success: function(r){
						//console.log(r)
						array = r.split("||");
						$('#tbody_tiquete').html(array[0])
						datatable_tiquete()
						if(pre_liquidacion==0 || pre_liquidacion=='' || pre_liquidacion==null){
							$('#guardar').prop("disabled",true)
							//$('#out_tiquete').prop("disabled",true)
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
					}
				})
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
      			cadena = "band=" + 7 +
      					"&reporte_variable=" + reporte_variable +
      					"&proveedor=" + proveedor +
      					"&select_liquidador=" + select_liquidador +
						"&fechaIni=" + fechaIni +
						"&fechaFin=" + fechaFin;
				$.ajax({
					type: "POST",
					url: "../modelo/facturacion_cargador.php",
					data: cadena,
					success: function(r){
						//console.log(r)
						array = r.split("||");
						$('#tbody_tiquete').html(array[0])
						datatable_tiquete()
						if(pre_liquidacion==0 || pre_liquidacion=='' || pre_liquidacion==null){
							$('#guardar').prop("disabled",true)
							//$('#out_tiquete').prop("disabled",true)
						}else{
							$('#Nuevo_reg').prop("disabled",true)
							$('#out_tiquete').prop("disabled",false)
						}
						if(array[1]!=0){
							$('#Nuevo_reg').prop("disabled",false)
						}else{
							$('#out_tiquete').prop("disabled",true)
						}
						$('#btn_search_new').removeClass('loader_min')
					}
				})
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
      			cadena = "band=" + 2 +
      					"&tipo_factura=" + tipo_factura +
      					"&cliente=" + cliente +
      					"&proveedor=" + proveedor;
      			$.ajax({
					type: "POST",
					url: "../modelo/facturacion_cargador.php",
					data: cadena,
					success: function(r){
						//console.log(r)
						if(r==0){
							alertify.error('Ya hay una pre-liquidación vigente')
						}else if(r==2){
							alertify.warning('No se pudo guardar correctamente')
						}else{
							load_pre_liquidaciones(r)
						}
					}
				})
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
      			cadena = "band=" + 9 +
      					"&select_liquidador=" + select_liquidador +
      					"&reporte_variable=" + reporte_variable +
      					"&proveedor=" + proveedor;
      			$.ajax({
					type: "POST",
					url: "../modelo/facturacion_cargador.php",
					data: cadena,
					success: function(r){
						//console.log(r)
						if(r==0){
							alertify.error('Ya hay una pre-liquidación vigente')
						}else if(r==2){
							alertify.warning('No se pudo guardar correctamente')
						}else{
							load_pre_liquidaciones_new(r)
						}
					}
				})
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
				$('#tbody_factura').html('').fadeIn()
			}else{
				cadena = "band=" + 3 +
	  					"&pre_liquidacion=" + pre_liquidacion;
	  			$.ajax({
					type: "POST",
					url: "../modelo/facturacion_cargador.php",
					data: cadena,
					success: function(r){
						//console.log(r)
						if(r==0){
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
							$('#tbody_factura').html('').fadeIn()
							$('#tbody_tiquete').html('')
						}else{
							array = r.split("||")
							$('#fecha_pre_liquidacion').val(array[0])
							$('#tipo_factura').val(array[1])
							$('#title_horas').val(array[2])
							$('#title_tiquete').val(array[3])
							$('#title_valor').val(array[4])
							$('#title_pre_liquidacion').html('Pre-liquidacion # '+array[5]).fadeIn()
							$('#title_factura').html('Pre-liquidacion # '+array[5]).fadeIn()
							$('#tbody_factura').html(array[6]).fadeIn()
							datatable_factura()
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
				})
	  		}
		}
		function load_data_pre_liquidacion_new(){
			let pre_liquidacion = $('#pre_liquidacion').val()
			if(pre_liquidacion==0){
				$('#Nuevo_reg').prop("disabled",false)
				$('#out_tiquete').prop("disabled",true)
				$('#title_horas').val('')
				$('#title_tiquete').val('')
				$('#title_valor').val('')
				$('#title_pre_liquidacion').html('Pre-liquidacion NUEVA').fadeIn()
				$('#title_factura').html('FPre-liquidacion NUEVA').fadeIn()
				$('#tbody_factura').html('').fadeIn()
			}else{
				cadena = "band=" + 10 +
	  					"&pre_liquidacion=" + pre_liquidacion;
	  			$.ajax({
					type: "POST",
					url: "../modelo/facturacion_cargador.php",
					data: cadena,
					success: function(r){
						//console.log(r)
						if(r==0){
							$('#Nuevo_reg').prop("disabled",true)
							$('#guardar').prop("disabled",true)
							$('#fecha_fact_asentada').prop("disabled",true)
							$('#fact_asociada').prop("disabled",true)
							$('#in_tiquete').prop("disabled",true)
							$('#out_tiquete').prop("disabled",true)
							$('#title_horas').val('')
							$('#title_tiquete').val('')
							$('#title_valor').val('')
							$('#title_pre_liquidacion').html('Pre-liquidacion NUEVA').fadeIn()
							$('#title_factura').html('FPre-liquidacion NUEVA').fadeIn()
							$('#tbody_factura').html('').fadeIn()
							$('#tbody_tiquete').html('').fadeIn()
						}else{
							array = r.split("||")
							$('#fecha_pre_liquidacion').val(array[0])
							$('#title_horas').val(array[1])
							$('#title_tiquete').val(array[2])
							$('#title_valor').val(array[3])
							$('#title_pre_liquidacion').html('Pre-liquidacion # '+array[4]).fadeIn()
							$('#title_factura').html('Pre-liquidacion # '+array[4]).fadeIn()
							$('#tbody_factura').html(array[5]).fadeIn()
							datatable_factura()
							$('#Nuevo_reg').prop("disabled",true)
							$('#out_tiquete').prop("disabled",false)
							if(array[2]!=0){
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
				})
	  		}
		}
		function select_tiquete(idRegistro,val){
			if(val=='out'){
				if($('#'+idRegistro).hasClass('select_tiquete_out')){
					$('#'+idRegistro).removeClass('select_tiquete_out')
				}else{
					$('#'+idRegistro).addClass('select_tiquete_out')
				}
			}else if(val=='in'){
				if($('#'+idRegistro).hasClass('select_tiquete_in')){
					$('#'+idRegistro).removeClass('select_tiquete_in')
				}else{
					$('#'+idRegistro).addClass('select_tiquete_in')
				}
			}
		}
		function mov_tiquete(ioption){
			$('#'+ioption+'_tiquete').addClass('loader_min')
			let cont = 0
			let array_id = new Array()
			let pre_liquidacion = $('#pre_liquidacion').val()
			$(".select_tiquete_"+ioption).each(function(){
       			var elemento= this
       			if(elemento.id != ''){
       				array_id[cont] = elemento.id
       				cont = cont + 1
       			}
       		})
       		if($('#select_liquidador').val()==-1){
       			band = 4
       		}else{
       			band = 11
       		}
       		cadena = "band=" + band +
  					"&pre_liquidacion=" + pre_liquidacion +
  					"&array_id=" + array_id +
  					"&ioption=" + ioption;
  			$.ajax({
				type: "POST",
				url: "../modelo/facturacion_cargador.php",
				data: cadena,
				success: function(r){
					console.log(r)
					if(r==1){
						if($('#select_liquidador').val()==-1){
							search_tiquetes()
							load_data_pre_liquidacion()
						}else{
							search_tiquetes_new()
							load_data_pre_liquidacion_new()
						}
					}else{
						alertify.error('No se pudo guardar correctamente')
					}
					$('#'+ioption+'_tiquete').removeClass('loader_min')
				}
			})
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
      			if(select_liquidador==-1){
      				band = 5
      				cadena = "band=" + band +
	  					"&fecha_fact_asentada=" + fecha_fact_asentada +
	  					"&fact_asociada=" + fact_asociada +
	  					"&pre_liquidacion=" + pre_liquidacion;
      			}else{
      				band = 12
      				cadena = "band=" + band +
	  					"&fecha_fact_asentada=" + fecha_fact_asentada +
	  					"&fact_asociada=" + fact_asociada +
	  					"&pre_liquidacion=" + pre_liquidacion +
	  					"&proveedor=" + proveedor +
	  					"&select_liquidador=" + select_liquidador +
	  					"&reporte_variable=" + reporte_variable;
      			}
	  			$.ajax({
					type: "POST",
					url: "../modelo/facturacion_cargador.php",
					data: cadena,
					success: function(r){
						//console.log(r)
						if(r==1){
							alert('Se liquidó correctamente')
							//self.location='facturacion_cargador.php'
							load_body_liquidaciones()
						}else if(r==0){
							alertify.warning('No coincide la pre-liquidación con los valores seleccionados')
						}else{
							alertify.error('No se pudo guardar correctamente')
						}
					}
				})
      		}
		}
		function load_body_liquidaciones(){
			$('#body_liquidaciones_data').html('')
			$('#body_liquidaciones_data').addClass('loader_min')
			cadena = "band=" + 6 +
					"&select_liquidador=" + $('#select_liquidador').val(); 
			$.ajax({
				type: "POST",
				url: "../modelo/facturacion_cargador.php",
				data: cadena,
				success: function(r){
					$('#body_liquidaciones_data').removeClass('loader_min')
					$('#body_liquidaciones_data').html(r).fadeIn()
					$('#btn_load_liq').hide()
				}
			})
		}
		function descargar_pdf(){
			let select_liquidador = $('#select_liquidador').val()
			let pre_liquidacion = $('#pre_liquidacion').val()
			band=13
			$.post("../modelo/facturacion_cargador.php",{	band: band, select_liquidador: select_liquidador, pre_liquidacion:pre_liquidacion},
				function(mensaje){
					console.log(mensaje);
					$('#input_contenido_op').val(mensaje);
				    $("#form_pdf").attr("target", "blank");
					$("#form_pdf").attr("action", "../modelo/descargar_op_pdf.php");
				   	$("#form_pdf").submit();
			});
		}
		function search_historial_liquidaciones(){
			//$('#modalHistorialLiquidaciones').modal('show')
			//$('#btn_load_liq').addClass('loader_min')
			//$('#btn_load_liq').prop("disabled",true)
			let select_liquidador = $('#select_liquidador').val()
			let proveedor = $('#proveedor').val()
			let variable = ''
			if(select_liquidador==-1){
				variable = $('#cliente').val()
			}else{
				variable = $('#reporte_variable').val()
			}

			cadena = "band=" + 14 +
					"&select_liquidador=" + select_liquidador +
					"&proveedor=" + proveedor +
					"&variable=" + variable;
			$.ajax({
				type: "POST",
				url: "../modelo/facturacion_cargador.php",
				data: cadena,
				success: function(r){
					//console.log(r)
					$('#div_menu_modal_historial').html(r).fadeIn()
					//$('#btn_load_liq').removeClass('loader_min')
					//$('#btn_load_liq').prop("disabled",false)
				}
			})
		}
		function descargar_pdf_historial(idLiquidacion){
			let select_liquidador = $('#select_liquidador').val()
			band=13
			$.post("../modelo/facturacion_cargador.php",{	band: band, select_liquidador: select_liquidador, pre_liquidacion:idLiquidacion},
				function(mensaje){
					//console.log(mensaje);
					$('#input_contenido_op').val(mensaje);
				    $("#form_pdf").attr("target", "blank");
					$("#form_pdf").attr("action", "../modelo/descargar_op_pdf.php");
				   	$("#form_pdf").submit();
			});
		}
		function select_all_tiquetes(n){
			if(n=='in'){
				var tbody = 'tbody_factura'
			}else{
				var tbody = 'tbody_tiquete'
			}
			let elements = document.querySelectorAll('#' + tbody + ' >tr');
			//console.log(elements)
			//let elements = document.querySelectorAll('ul > li:last-child');
			for (let elem of elements) {
				//console.log(elem.id); // "test", "passed"
				select_tiquete(elem.id,n)
			}
		}
	</script>
</head>
<body>
	<?php include './Header.php';?>
<div class="container-fluid"> 
    <div class="row" style="margin-left: 10px; margin-right: 10px; margin-top: 5px;">
    	<div class="col-sm-4"></div>
    	<div class="col-sm-4" style="background-color: powderblue; border-radius: 5px;">
    		<center><label>MODO DE LIQUIDACIÓN</label></center>
    		<select class="form-control" id="select_liquidador" onchange="load_body_liquidaciones()">
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
    <div id="body_liquidaciones_data"></div>
    <form  name="form_pdf" id="form_pdf"  method="post" enctype="multipart/form-data">
		<!--<input name="operacion" id="operacion" type="hidden" value="">-->
		<input type="hidden" name="input_contenido" id="input_contenido">
		<input type="hidden" name="input_contenido_op" id="input_contenido_op">
	</form>
</div>
<div class="modal fade" id="modalHistorialLiquidaciones" tabindex="0" role="dialog" style="z-index: 5000;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="tittle_modal_historial"></h4>
            </div>
            <div id="div_menu_modal_historial"></div>
        </div>
    </div>
</div>
</body>
</html>