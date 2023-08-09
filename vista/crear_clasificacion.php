<?php
if(!isset($_SESSION["logueado"])){
    session_start();
}
require_once '../modelo/conexion.php';
//include('../modelo/security.php');
include ("../../clase_encrip.php");
//error_reporting(0);
if(!isset($_SESSION['Array_empresa']['ADMINISTRACION'])){
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
    <!-- <script type="text/javascript" src="../controlador/actividad.js"></script> -->
</head>
<body>
    <?php include 'Header.php'; ?>
    <div class="container-fluid">
        <div class="row" id="div">
            <div class="col-sm-6">
                <label>Crear clasificacion:</label>
                <input type="text" id="" class="form-control">
            </div>
            <div class="col-sm-6">
                <button class="btn btn-primary" onclick="crear();">Crear</button>
            </div>
        </div>
        <div class="row" id="div_detail_clasif"></div>
    </div>
    <script type="text/javascript">
        function crear(){
            cadena = "band=cargar_estructura";
            //console.log(cadena)
            $.ajax({
                type: "POST",
                url: "crear_clasificacion_ctr.php",
                data: cadena,
                success: function (r){
                    //console.log(r);
                    $('#div_detail_clasif').html(r);
                    if(r == 1){
                        alertify.success("Se registró correctamente");
                        //self.location = "actividades.php";
                    }else{
                        alertify.error("No se pudo registrar");
                    }
                }
            });
        }
    </script>
</body>
</html>