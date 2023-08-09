<!--<nav class="navbar  navbar-fixed-top"  style=" height: 56.4px; border-radius: 0px; border: 0px; box-shadow:0px 10px 30px -10px gray; background-color: white;"  >
    <div class="container-fluid" style="padding-left: 0px;">
        <div class="navbar-header">                  
            <ul class="nav navbar-nav navbar-left">
                <li style="margin-left: 15px; margin-top: 15px;"><?php echo ($_SESSION['NombreUsuario']); ?></li>
            </ul>
        </div>
        <div id="navbar" class="navbar-collapse collapse" style=" padding-top: 3px;">
            <ul class="nav navbar-nav navbar-right">
                <?php if ($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA_2'){
                    ?><li><a href="../facturacion/agregar.php">Facturacion</a></li><?php
                }
                if ($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA_2'){
                    ?><li><a href="tarifas.php">Tarifas</a></li><?php
                }
                if ($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA_2' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES' || $_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO'){
                    ?><li><a href="Admin.php">Inicio</a></li><?php
                }
                if ($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR' || $_SESSION['permisoIngresar'] == 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES'){ ?>
                <li><a href="MantenimientoCargadores.php">Mantenimiento</a></li>
            <?php }
             if ($_SESSION['permisoIngresar'] == 'MTTO_CARGADORES'){?><li><a href="maquinaria.php">Maquinaria</a></li>
               <?php }
               /*if ($_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO'){
                    ?>  <li><a class="w3-button"  href="inicio_patio.php">
                        <i class="fa fa-home" style=" margin-right: 4px;"></i>Inicio</a></li>
                    <?php 
                }else*/if($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){
                    ?>
                    <li><a class="w3-button"  href="inicio.php">
                        <i class="fa fa-home" style=" margin-right: 4px;"></i>Inicio</a></li>
                    <?php
                }?>
                <li><a href="../modelo/login-salir.php"><span class="glyphicon glyphicon-user"></span></a></li>
                <li role="separator" class="divider"></li>
            </ul>
        </div>
    </div>
</nav>
<br><br>-->
<nav class="navbar navbar-default" role="navigation">
  <!-- El logotipo y el icono que despliega el menú se agrupan
       para mostrarlos mejor en los dispositivos móviles -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse"
            data-target=".navbar-ex1-collapse">
      <span class="sr-only">Desplegar navegación</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand xs" href="#"><h6><?php echo ($_SESSION['NombreUsuario']); ?></h6></a>
  </div>

  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav">
        <?php if ($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA_2'){
            ?><li><a href="../facturacion/agregar.php">Facturacion</a></li><?php
        }
        if ($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA_2'){
            ?><li><a href="../vista/tarifas.php">Tarifas</a></li><?php
        }
        if ($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA_2' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES' || $_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO'){
            ?><li><a href="../vista/Admin.php">Inicio</a></li><?php
        }
        if ($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR' || $_SESSION['permisoIngresar'] == 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES'){ ?>
        <li><a href="MantenimientoCargadores.php">Mantenimiento</a></li>
    <?php }
     if ($_SESSION['permisoIngresar'] == 'MTTO_CARGADORES'){?><li><a href="maquinaria.php">Maquinaria</a></li>
       <?php }
       /*if ($_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO'){
            ?>  <li><a class="w3-button"  href="inicio_patio.php">
                <i class="fa fa-home" style=" margin-right: 4px;"></i>Inicio</a></li>
            <?php 
        }else*/if($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR'){
            ?>
            <li><a class="w3-button"  href="../vista/inicio.php">
                <i class="fa fa-home" style=" margin-right: 4px;"></i>Inicio</a></li>
            <?php
        }?>
        <li><a href="../modelo/login-salir.php"><span class="glyphicon glyphicon-user"></span> Cerrar Sesión</a></li>
      <!--<li class="active"><a href="#">Enlace #1</a></li>
      <li><a href="#">Enlace #2</a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          Menú #1 <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
          <li><a href="#">Acción #1</a></li>
          <li><a href="#">Acción #2</a></li>
          <li><a href="#">Acción #3</a></li>
          <li class="divider"></li>
          <li><a href="#">Acción #4</a></li>
          <li class="divider"></li>
          <li><a href="#">Acción #5</a></li>
        </ul>
      </li>
    </ul>

    <form class="navbar-form navbar-left" role="search">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Buscar">
      </div>
      <button type="submit" class="btn btn-default">Enviar</button>
    </form>

    <ul class="nav navbar-nav navbar-right">
      <li><a href="#">Enlace #3</a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          Menú #2 <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
          <li><a href="#">Acción #1</a></li>
          <li><a href="#">Acción #2</a></li>
          <li><a href="#">Acción #3</a></li>
          <li class="divider"></li>
          <li><a href="#">Acción #4</a></li>
        </ul>
      </li>-->
    </ul>
  </div>
</nav>