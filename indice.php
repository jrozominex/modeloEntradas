
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />	
	<title>Correción viajes</title>
	
	<link rel="stylesheet" type="text/css" href="libreria/bootstrap/css/bootstrap.css">
	
	<!--<script src="../librerias/moment.js" type="text/javascript"></script>-->
	
	<style type="text/css">
		thead tr th{ 
	        position: sticky;
	        top: 0;
	        z-index: 10;
	        background-color: #72A3CE;
	        text-align: center;
	        vertical-align: middle;
	    }
	    .table-responsive1{ 
	        height:75vh;
	        overflow:scroll;
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
   </style>


</head>
<body>
	<center>
		<h1>Transacciones</h1>
	</center>
	<div class="container-fluid">
		<div style="border: 1px solid; -webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;">
			<div class="row">
				<div class="col-sm-2">
					<label style="">&nbsp; Empresa</label>
					<select style="margin-left: 4px;" class="form-control input-sm" id="select_empresa" onchange="invocar_buscar();"></select>
				</div>
				<div class="col-sm-2">
					<label style="text-align: middle"> Tipo Movimiento</label>
					<select class="form-control" id="select_tipoMovimiento" onchange="invocar_buscar();">
						<option value="0" selected disabled>Seleccione</option>
						<option value="Recepción">Recepción</option>
						<option value="Traslado">Traslado</option>
					</select>
				</div>
				<div class="col-sm-2">
					<label>Fecha Inicio</label>
					<input type="date" class="form-control" id="fecha_inicio" onchange="invocar_buscar();" max="<?php echo date('Y-m-d'); ?>">
				</div>
				<div class="col-sm-2">
					<label>Fecha Final</label>
					<input type="date" class="form-control" id="fecha_final" onchange="invocar_buscar();" max="<?php echo date('Y-m-d'); ?>">
				</div>				
				
			</div><br>	
		
			  <center>
      <button class="btn btn-success" style="margin-right: 15px;" data-toggle="modal" data-target="#modalInsertarActividadDestinos">
          Registrar Agrupación 
          <span class="glyphicon glyphicon-plus"></span>
      </button>
  </center>

			
		</div>
		<div class="row" id="contenedor">
    


      <div class="col-sm-10 table-responsive" id="div_tablas">
      

  <br><div class="row">
      <div class="col-sm-1"></div>
      <div class="col-sm-10">
          <table class="table table-hover table-condensed table-bordered table-responsive table-striped">
              <thead>
                  <tr><th>Destino</th>
                  <th>Actividad</th>
                  <th>Cargadores</th>
                    <th>Produccón</th>
                  <th>Acciones</th>
              </tr></thead>
              <tbody><tr>
                              <td>222</td>
                                <td>ACOPIO</td>
                              <td><center><input type="checkbox" onclick="change_habilitar('Estado','97525C03-E53D-4BAE-A974-AF5B54109FEA','1')" id="habilita_cargador" checked=""></center></td>
                                <td><center><input type="checkbox" onclick="change_habilitar('Produccion','97525C03-E53D-4BAE-A974-AF5B54109FEA','1')" id="habilita_cargador" checked=""></center></td>
                              <td>
                                  <button class="btn btn-danger btn-xs" title="Editar Agrupación" onclick="editar_ActividadDestinos('97525C03-E53D-4BAE-A974-AF5B54109FEA','1')"><span class="glyphicon glyphicon-trash"></span></button>
                              </td>
                          </tr><tr>
                              <td>222</td>
                                <td>CLASIFICACIÓN</td>
                              <td><center><input type="checkbox" onclick="change_habilitar('Estado','C15792AC-B9E4-4BAB-8BB1-DE5750761F45','1')" id="habilita_cargador" checked=""></center></td>
                                <td><center><input type="checkbox" onclick="change_habilitar('Produccion','C15792AC-B9E4-4BAB-8BB1-DE5750761F45','1')" id="habilita_cargador" checked=""></center></td>
                              <td>
                                  <button class="btn btn-danger btn-xs" title="Editar Agrupación" onclick="editar_ActividadDestinos('C15792AC-B9E4-4BAB-8BB1-DE5750761F45','1')"><span class="glyphicon glyphicon-trash"></span></button>
                              </td>
                          </tr><tr>
                              <td>222</td>
                                <td>COQUIZACIÓN</td>
                              <td><center><input type="checkbox" onclick="change_habilitar('Estado','577E3F18-BCCB-41D0-934D-97207601BC99','1')" id="habilita_cargador" checked=""></center></td>
                                <td><center><input type="checkbox" onclick="change_habilitar('Produccion','577E3F18-BCCB-41D0-934D-97207601BC99','1')" id="habilita_cargador" checked=""></center></td>
                              <td>
                                  <button class="btn btn-danger btn-xs" title="Editar Agrupación" onclick="editar_ActividadDestinos('577E3F18-BCCB-41D0-934D-97207601BC99','1')"><span class="glyphicon glyphicon-trash"></span></button>
                              </td>
                          </tr><tr>
                              <td>222</td>
                                <td>DESPACHO</td>
                              <td><center><input type="checkbox" onclick="change_habilitar('Estado','4D4D4A23-2734-48FF-AA34-120BFE879418','1')" id="habilita_cargador" checked=""></center></td>
                                <td><center><input type="checkbox" onclick="change_habilitar('Produccion','4D4D4A23-2734-48FF-AA34-120BFE879418','0')" id="habilita_cargador"></center></td>
                              <td>
                                  <button class="btn btn-danger btn-xs" title="Editar Agrupación" onclick="editar_ActividadDestinos('4D4D4A23-2734-48FF-AA34-120BFE879418','1')"><span class="glyphicon glyphicon-trash"></span></button>
                              </td>
                          </tr><tr>
                              <td>222</td>
                                <td>LAVADO</td>
                              <td><center><input type="checkbox" onclick="change_habilitar('Estado','4B3FC4CC-3EAE-4E0C-A01C-17E111022973','0')" id="habilita_cargador"></center></td>
                                <td><center><input type="checkbox" onclick="change_habilitar('Produccion','4B3FC4CC-3EAE-4E0C-A01C-17E111022973','1')" id="habilita_cargador" checked=""></center></td>
                              <td>
                                  <button class="btn btn-danger btn-xs" title="Editar Agrupación" onclick="editar_ActividadDestinos('4B3FC4CC-3EAE-4E0C-A01C-17E111022973','0')"><span class="glyphicon glyphicon-trash"></span></button>
                              </td>
                          </tr><tr>
                              <td>222</td>
                                <td>MOLIENDA</td>
                              <td><center><input type="checkbox" onclick="change_habilitar('Estado','2FD75D82-150E-4B02-8423-FC43637819D5','1')" id="habilita_cargador" checked=""></center></td>
                                <td><center><input type="checkbox" onclick="change_habilitar('Produccion','2FD75D82-150E-4B02-8423-FC43637819D5','0')" id="habilita_cargador"></center></td>
                              <td>
                                  <button class="btn btn-danger btn-xs" title="Editar Agrupación" onclick="editar_ActividadDestinos('2FD75D82-150E-4B02-8423-FC43637819D5','1')"><span class="glyphicon glyphicon-trash"></span></button>
                              </td>



    </div>
	</div><br>
		
	
</body>
</html>