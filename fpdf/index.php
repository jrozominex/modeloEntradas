<?php
session_start();
require_once('../modelo/conexion.php');
if ($_SESSION["logueado"] == TRUE && $_SESSION["usuario"] && ($_SESSION['permisoIngresar'] == 'ADMIN-PRECINTOS' || $_SESSION['permisoIngresar'] == 'CONSU-PRECINTOS')) {
	header("Location: ../vista/inicio.php");
} elseif ($_SESSION["logueado"] == TRUE && $_SESSION["usuario"] && $_SESSION['permisoAsignar'] == 'USO-PRECINTOS') {
    header("Location: ../vista/inicio1.php");
    die();
}else{
    header("Location: ../index.php");
    die();
}