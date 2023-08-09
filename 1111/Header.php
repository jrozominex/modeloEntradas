<nav class="navbar  navbar-fixed-top"  style=" height: 56.4px; border-radius: 0px; border: 0px; box-shadow:0px 10px 30px -10px gray; background-color: white;"  >
    <div class="container-fluid" style="padding-left: 0px;">
        <div class="navbar-header">                  
            <ul class="nav navbar-nav navbar-left">
                <li style="margin-left: 15px; margin-top: 15px;"><?php echo ($_SESSION['NombreUsuario']); ?></li>
            </ul>
        </div>
        <div id="navbar" class="navbar-collapse collapse" style=" padding-top: 3px;">
            <ul class="nav navbar-nav navbar-right">
                <?php if ($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA'){
                    ?><li><a href="facturacion.php">Facturacion</a></li><?php
                }
                if ($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA'){
                    ?><li><a href="../vista/tarifas.php">Tarifas</a></li><?php
                }
                if ($_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES' || $_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO'){
                    ?><li><a href="../vista/Admin.php">Inicio</a></li><?php
                }
                if ($_SESSION['permisoIngresar'] == 'OPERADOR_CARGADOR' || $_SESSION['permisoIngresar'] == 'MTTO_CARGADORES' || $_SESSION['permisoIngresar'] == 'MECANICO_CARGADORES'){ ?>
                <li><a href="../vista/MantenimientoCargadores.php">Mantenimiento</a></li>
            <?php }
             if ($_SESSION['permisoIngresar'] == 'MTTO_CARGADORES'){?><li><a href="../vista/maquinaria.php">Maquinaria</a></li>
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
                <li><a href="../modelo/login-salir.php"><span class="glyphicon glyphicon-user"></span></a></li>
                <li role="separator" class="divider"></li>
            </ul>
        </div>
    </div>
</nav>
<br><br><br><br>