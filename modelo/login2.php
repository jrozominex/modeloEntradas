<?php
require_once './conexion.php';
$usuario = utf8_decode($_POST['usuario']);
$contrasena = $_POST['contrasena'];
$band = 0;
$sql = "SELECT NombreUsuarioLargo,Password, idUsuario, NombreUsuario FROM Usuarios WHERE NombreUsuario ='$usuario'";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );  
$result = sqlsrv_query($conn,$sql,$params,$options);
$rows=sqlsrv_num_rows($result);
if ($rows > 0){
    while ($row = sqlsrv_fetch_array($result)) 
    {   $idUsu = $row["idUsuario"];
        $usu = utf8_decode($row['NombreUsuario']);
        $usu1 = utf8_encode($row['NombreUsuarioLargo']);
        $contra = $row["Password"];

        $sql = "SELECT Permisos.idPermiso, UsuariosPermisos.Activo, Permisos.Descripcion, Permisos.Aplicacion, UsuariosPermisos.idUsuario, UsuariosPermisos.idEmpresa, Permisos.Permiso, UsuariosPermisos.Activo
        FROM UsuariosPermisos RIGHT OUTER JOIN
            Permisos ON UsuariosPermisos.idPermiso = Permisos.idPermiso
        WHERE UsuariosPermisos.idUsuario ='$idUsu' AND (Permisos.Aplicacion = 'CARGADORES') AND UsuariosPermisos.Activo='1'";
        $res = sqlsrv_query($conn,$sql);
        while($empresa = sqlsrv_fetch_array($res)){
            $Arr_permiso[$empresa['Permiso']][$band]=$empresa['idEmpresa'];
            $band++;
        }
        /*$sql = "SELECT Permisos.idPermiso, UsuariosPermisos.Activo, Permisos.Descripcion, Permisos.Aplicacion, UsuariosPermisos.idUsuario, UsuariosPermisos.idEmpresa, Permisos.Permiso, UsuariosPermisos.Activo
        FROM UsuariosPermisos RIGHT OUTER JOIN
            Permisos ON UsuariosPermisos.idPermiso = Permisos.idPermiso
        WHERE UsuariosPermisos.idUsuario ='$idUsu' AND (Permisos.Aplicacion = 'PHP') AND UsuariosPermisos.Activo='1'";
//        $sql = "SELECT * FROM PermisoXUsuario WHERE Aplicacion='PHP' AND idUsuario='$usuario' and Activo=1";*/
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );  
        $result_permiso = sqlsrv_query($conn,$sql,$params,$options);
        $rows=sqlsrv_num_rows($result_permiso);
        if ($rows==0)
        {
          echo '<script language = javascript>
                alert("El usuario no tiene permisos");
                self.location = "../index.php";
                </script>';
        }else
        {
          while ($row = sqlsrv_fetch_array($result_permiso)) 
          {   $permiso = $row['Permiso'];

              if (isset($usuario) && isset($contrasena)) 
              {  if ($contrasena == $contra && ($permiso == 'ADMIN_CARGADORES' || $permiso == 'CONSULTAS_OFICINA' || $permiso == 'PATIO_CARGADORES')) 
                 {  session_start();
                    $_SESSION["logueado"] = TRUE;
                    $_SESSION['usuario'] = $idUsu;
                    $_SESSION['contrasena'] = $contra;
                    $_SESSION['permisoIngresar'] = $permiso;
                    $_SESSION['NombreUsuario'] = $usu1;
                    $_SESSION['Array_empresa'] = $Arr_permiso;
                    header("Location: ../vista/Admin.php");
                 }elseif ($contrasena == $contra && $permiso == 'OPERADOR_CARGADOR') 
                 {  session_start();
                    $_SESSION["logueado"] = TRUE;
                    $_SESSION['usuario'] = $idUsu;
                    $_SESSION['contrasena'] = $contra;
                    $_SESSION['permisoIngresar'] = $permiso;
                    $_SESSION['NombreUsuario'] = $usu1;
                    $_SESSION['Array_empresa'] = $Arr_permiso;
                    header("Location: ../vista/inicio.php");
                 }elseif ($contrasena == $contra && $permiso == 'AUXILIAR_PATIO'){ 
                  echo 3;
                    session_start();
                    $_SESSION["logueado"] = TRUE;
                    $_SESSION['usuario'] = $idUsu;
                    $_SESSION['contrasena'] = $contra;
                    $_SESSION['permisoIngresar'] = $permiso;
                    $_SESSION['NombreUsuario'] = $usu1;
                    $_SESSION['Array_empresa'] = $Arr_permiso;
                    header("Location: ../vista/Admin.php");
                 }elseif ($contrasena == $contra && $permiso == 'MTTO_CARGADORES'){ 
                    session_start();  
                    $_SESSION["logueado"] = TRUE;
                    $_SESSION['usuario'] = $idUsu;
                    $_SESSION['contrasena'] = $contra;
                    $_SESSION['permisoIngresar'] = $permiso;
                    $_SESSION['NombreUsuario'] = $usu1;
                    $_SESSION['Array_empresa'] = $Arr_permiso;
                    header("Location: ../vista/Admin.php");
                 }elseif ($contrasena == $contra && $permiso == 'MECANICO_CARGADORES'){  //USUARIO ADMINISTRADOR 
                    session_start();  
                    $_SESSION["logueado"] = TRUE;
                    $_SESSION['usuario'] = $idUsu;
                    $_SESSION['contrasena'] = $contra;
                    $_SESSION['permisoIngresar'] = $permiso;
                    $_SESSION['NombreUsuario'] = $usu1;
                    $_SESSION['Array_empresa'] = $Arr_permiso;
                    header("Location: ../vista/Admin.php");
                 }elseif ($usuario == $usu && $contrasena != $contra) {
                      echo '<script language = javascript>
                      alert("La contraseña es incorrecta");
                      self.location = "../index.php";
                      </script>';
                  } elseif ($usuario != $usu && $contrasena != $contra) {
                      echo '<script language = javascript>
                      alert("El nombre de usuario es incorrecto");
                      self.location = "../index.php";
                      </script>';
                  }else{
                    echo '<script language = javascript>
                        alert("No tienes permiso para ingresar a este aplicativo");
                        self.location = "../index.php";
                        </script>';
                  }
     // echo 'no entro';
              }
          }
        }
    }
  }else{
    echo '<script language = javascript>
          alert("Los datos ingresados son incorrectos");
          self.location = "../index.php";
          </script>';
  }
  ?>