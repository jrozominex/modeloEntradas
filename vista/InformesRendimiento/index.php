<?php



?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rendimiento</title>

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">




    <link rel="stylesheet" type="text/css" href="../libreria/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="../libreria/alertifyjs/css/alertify.css">
<link rel="stylesheet" type="text/css" href="../libreria/alertifyjs/css/themes/default.css">
<link rel="stylesheet" href="../libreria/css/bootstrap.min.css">
<link rel="stylesheet" href="../libreria/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="../libreria/css/estilos.css">
<!-- Buttons DataTables -->
<link rel="stylesheet" href="../libreria/css/buttons.bootstrap.min.css">
<link rel="stylesheet" href="../libreria/css/font-awesome.min.css">

<script src="../libreria/jquery-3.3.1.min.js"></script>
<script src="../libreria/bootstrap/js/bootstrap.js"></script>
<script src="../libreria/alertifyjs/alertify.js"></script>
<script src="../libreria/jquery.min.js" type="text/javascript"></script>
<script src="../libreria/js/jquery.dataTables.min.js"></script>
<script src="../libreria/js/dataTables.bootstrap.js"></script>
<script src="../libreria/js/dataTables.buttons.min.js"></script>
<script src="../libreria/js/buttons.bootstrap.min.js"></script>    
<!-- <script src="../controlador/precinto.js" type="text/javascript"></script> -->
<style type="text/css">

.opaco{
    pointer-events: none;
    opacity: 1;
}

.campo_necesario{
     outline: 3px solid #ef0a45 !important;
}
  
.loader {
          font-size: 20px;
          margin: 18% auto;
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
   

</head>
<body>

    <div class="container">
         
             <div class="row" id="rows">

                        <center>
                            <h2 id="titulo">Informes De Rendimiento</h2>
                        </center>
                        <br><br>

						 <div class="col-sm-3"></div>

			             <div class="col-sm-3">
			            	 <label>Fecha Inicial</label>
			            	 <input type="date" id="fecha1" name="fecha1" class="form-control" >
			          	 </div>

			        	  <div class="col-sm-3">
			           		  <label>Fecha Final*</label>
			            	  <input type="date" id="fecha2" name="fecha2" class="form-control"  >
			         	  </div>

			           	  <div class="col-sm-3"> 
			           	   	  <button  id="buscar" name="buscar" class="btn btn-success " style="margin-top: 25px; margin-right: 7px;">
			                  Buscar <span class="glyphicon glyphicon-search"></span>
			             	  </button>
			         	  </div>
                        <br><br>

             


                   
                        <div id="documento_insertar"> </div>
                        <div id="loader" > </div>
              </div>
 
    </div>

     <!-- <div id="Div_tabla"></div> -->

     <script type="text/javascript">

     	 $(document).on('click', '#buscar', (e) => {
;
	     // $('#Div_tabla').html('') 
	     $('#documento_insertar').html('')
	     $('#rows').addClass('opaco') 
	     $('#loader').addClass('loader')
	     alert($('#fecha1').val()+' '+ $('#fecha2').val());
	     cadena = 'tipo=1&&fecha1='+ $('#fecha1').val() +'&&fecha2='+ $('#fecha2').val()
	        $.ajax({
	            type: "POST",
	            url: "controller.php",
	            data: cadena,
	            success: function (r){   
	             console.log(r);                          
	              $('#documento_insertar').html(r).fadeIn(); 
	              $('#loader').removeClass('loader')
	              $('#rows').removeClass('opaco') 

	        }
	      }); 
		});

     </script>


<?php


?>