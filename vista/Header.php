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
    <a class="navbar-brand" href="#"><?php echo ($_SESSION['NombreUsuario']); ?></a>
  </div>
  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav">
        <?php if (isset($_SESSION['Array_empresa']['LIQUIDAR_CARGADORES'])){
            ?><li><a href="../../Cargadores-server_copia/vista/facturacion_cargador_w2ui.php">Facturacion</a></li><?php
        }
        if (isset($_SESSION['Array_empresa']['TARIFA_CARGADORES'])){
            ?><li><a href="../../Cargadores-server_copia/vista/w2ui.php">Tarifas</a></li><?php
        }
        ?><li><a href="Admin.php"><span class="glyphicon glyphicon-home"></span> Inicio</a></li>
        <li><a href="../modelo/login-salir.php"><span class="glyphicon glyphicon-user"></span> Cerrar Sesión</a></li>
    </ul>
  </div>
</nav>