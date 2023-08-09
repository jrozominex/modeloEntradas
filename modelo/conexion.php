<?php
$Servidor = "10.1.1.3";
$Usuario = "programador";
$Password = "P123456";  
$BaseDeDatos="Traz";
$connectionInfo = array("Database"=>$BaseDeDatos,"UID"=>$Usuario, "PWD"=>$Password);
$conn=sqlsrv_connect($Servidor,$connectionInfo) or die(" Error ::: !!! El servidor no puede conectar con la base de datos, verifique que los datos del servidor, usuario y password sean los correctos !!! ");
?>