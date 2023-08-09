<?php
//include('modelo/conexion.php');
//header("Location: ../Inicio/index.php");
echo '<script language = javascript>
        self.location = "../Inicio/index.php";
        </script>';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=auto, initial-scale=0.9">
    <title>Inicia sesión</title>
    <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/themes/default.css">

    <script src="librerias/jquery-3.3.1.min.js"></script>
    <script src="librerias/bootstrap/js/bootstrap.js"></script>
    <script src="librerias/alertifyjs/alertify.js"></script>
</head>
<body style="background-color: whitesmoke">
    <!-- INICIAR SESION -->
    <!-- INICIAR SESION -->
    <div class="container">
        <div class="row">
            <div class="col-sm-4"></div>    
            <div class="col-sm-4">
                <img src="Imagenes/1.png" width="400" height="300">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <center>
                    <form role="form" action="modelo/login.php" method="post">
                        <div class="form-group">
                            <label for="usrname"><span class="glyphicon glyphicon-user"></span> Usuario</label>
                            <input type="text" name="usuario" class="form-control" placeholder="Usuario...">
                        </div>
                        <div class="form-group">
                            <label for="psw"><span class="glyphicon glyphicon-eye-open"></span> Contraseña</label>
                            <input type="password" class="form-control" name="contrasena" placeholder="Contraseña"> 
                        </div>
                        <button type="submit" class="btn btn-success btn-block"><span class="glyphicon glyphicon-off"></span> Iniciar sesión</button>
                    </form>
                </center>
            </div>
            <div class="col-sm-4">
                <!-- Falta contenido-->
            </div>
        </div>
    </div>
</body>
</html>
