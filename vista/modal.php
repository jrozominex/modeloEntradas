<div class="modal fade" id="modalAsignarTemp" tabindex="0" role="dialog" style="z-index: 5000;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cerrar()"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Distribuir Tiempos Por Actividad</h4>
            </div>
            <div class="" id="modal_body">
<!--tiquete--><input type="hidden" id="registroHorometro" value="0">
<!--actividad--><input type="hidden" id="actividad" value="0">
<!--producto--><input type="hidden" id="producto_sec" value="00000000-0000-0000-0000-000000000000">
<!--subactividad--><input type="hidden" id="sub_actividad" value="0">
<!--patio Lejia--><input type="hidden" id="idPatioLejia" value="0">
<!--usuario--><input type="hidden" id="usuario" value="<?php echo $usuario; ?>">
                <div class="row form-group center-block">
                    <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                        <button id="volverStandby" class="btn btn-dark navbar-left" onclick="MostrarOcultar('3')">Standby <span class="glyphicon glyphicon-time"></span></button>
                        <button id="volverAct" class="btn btn-primary navbar-left" onclick="MostrarOcultar('1')">Asignar Tiempos <span class="glyphicon glyphicon-back"></span></button>
                    </div>
                    <div class="col-xs-5  col-sm-6  col-md-6  col-lg-6  col-xl-6">
                        <center><h4 id="FaltaAsignar"></h4></center>
                        <input type="hidden" id="ValoresAsignados">
                    </div>
                    <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                        <button id="checked" class="btn btn-success btn-xs navbar-right" style="margin-right: 3px;" title="Se guardó los datos correctamente"><span class="glyphicon glyphicon-ok"></span></button>
                        <button id="volverDesc" class="btn btn-warning navbar-right" onclick="MostrarOcultar('2')">Descuentos <span class="glyphicon glyphicon-cog"></span></button>
                    </div>
                </div>
                <div class="row form-group center-block" style="background-color: powderblue;"><center><h4 id="act">Actividades</h4></center></div>
                <div id="divStandby">
                    <div class="row">
                        <div class="col-xs-2  col-sm-2  col-md-2  col-lg-2  col-xl-2"></div>
                        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <label>Tiempo de Descuento</label>
                        </div>
                        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <input type="number" style="text-align:right;" id="ValorStandby" class="form-control">
                        </div>
                        <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                            <button class="btn btn-default btn-xs">Horo.</button>
                        </div>
                    </div>
                    <br>
                    <!--<input type="hidden" id="motivoStandby">-->
                    <div class="row">
                        <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4"></div>
                        <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                            <label>Motivo del Standby</label>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <center><textarea class="form-control" rows="4" id="motivoStandby"></textarea></center>
                    </div>
                    <div class="row">
                        <center>
                            <button id="btn_apliStandby" class="btn btn-success" onclick="AplicarStandby()">Aplicar Standby</button>
                            <button id="btn_modStandby" class="btn btn-success" onclick="ModificarStandby()">Modificar Standby</button>
                        </center>
                    </div>
                </div>
                <div class="row form-group center-block" id="divDescuento">
                    <div class="row">
                        <div class="col-xs-2  col-sm-2  col-md-2  col-lg-2  col-xl-2"></div>
                        <?php// if($tablet_browser == 0 && $mobile_browser == 0){
                            ?>
                        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <?php
                        /*}else{
                            ?>
                        <div class="col-sm-4">
                            <?php
                        } */?>
                            <br>
                            <label>Tiempo de Descuento</label>
                        </div>
                        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <br>
                            <input type="number" style="text-align:right;" id="ValorDescuento" class="form-control">
                        </div>
                        <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                            <br>
                            <button class="btn btn-default btn-xs">Horo.</button>
                        </div>
                        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3" id="divTM_despacho">
                            <label>TM Despacho</label>
                            <input type="number" id="TotalTM_Despacho" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4"></div>
                        <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                            <label>Motivo del descuento</label>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <center><textarea class="form-control" rows="4" id="motivoDescuento"></textarea></center>
                    </div>
                    <div class="row">
                        <center>
                            <button id="btn_apliDesc" class="btn btn-success" onclick="AplicarDescuento()">Aplicar Descuento</button>
                            <button id="btn_modDesc" class="btn btn-success" onclick="ModificarDescuento()">Modificar Descuento</button>
                        </center>
                    </div>
                </div>
                <div class="row" id="ActDetallado">
                    <input type="hidden" id="encrypt" value="<?php echo $encrypt = '3F001906-CFF3-437F-A4C5-67CDADDFD904'; ?>">
                    <input type="hidden" id="encrypt1" value="<?php echo $encrypt1 = '525242E8-D5DE-479C-8799-F2DE6F8527C7'; ?>">
                    <form id="form" name="form">
                        <?php
                        $sql = "SELECT * FROM Actividades WHERE idTipoActividad='00000000-0000-0000-0000-000000000001' AND Descripcion not in('STANDBY')";
                        $resul = sqlsrv_query($conn,$sql);
                        while ($actividad = sqlsrv_fetch_array($resul)) { ?>
                            <div class="col-xs-6  col-sm-6  col-md-6  col-lg-4  col-xl-4" style="margin-bottom: 15px;">
                                <center><button type="button" id="<?php echo $actividad['idActividad']; ?>" class="btn btn-default" value="<?php echo $actividad['idActividad']; ?>" onclick="actividad('<?php echo $actividad['idActividad']; ?>')" ><?php echo utf8_encode($actividad['Descripcion']) ?></button>
                                </center>
                            </div>
                        <?php
                        }
                        ?>
                    </form>
                </div>
                <div class="row form-group center-block" style="margin-bottom: 5px;">
                    <!--<br>-->
                    <div class="col-xs-12  col-sm-5  col-md-5  col-lg-5  col-xl-5">
                        <div class="row form-group center-block" style="background-color: powderblue;" id="borde"><center><h4>SubActividades</h4></center></div>
                        <div class="row form-group center-block" id="div_subactividad">
                                <form id="frm" name="frm"></form>
                        </div>
                    </div>
                    <div class="col-xs-12  col-sm-7  col-md-7  col-lg-7  col-xl-7">
                        <div class="row form-group center-block" style="background-color: #F7934E;" id="bordeAsignados"><center><h4>Tiempos Asignados</h4></center></div>
                        <div class="row form-group center-block" id="divAsignados" style="margin-top: -10px;">
                            <form id="AsignadosAgg" name="AsignadosAgg"></form>
                        </div>
                        <div class="row form-group center-block" id="divAsignados1" style="margin-top: -10px;">
                            <div class="col-sm-1  col-md-1  col-lg-1  col-xl-1"></div>
                            <div class="col-xs-6  col-sm-5  col-md-5  col-lg-5  col-xl-5" style="border: 3px solid #DCDCDC;">
                                <!-- style="border: 3px solid #DCDCDC;"-->
                                <center><h5><b>ALIMENTAR</b></h5></center>
                                <form id="AsignadosAgg1" name="AsignadosAgg"></form>
                            </div>
                            <div class="col-xs-6  col-sm-5  col-md-5  col-lg-5  col-xl-5" style="border: 3px solid #DCDCDC;">
                                <center><h5><b>APILAR</b></h5></center>
                                <form id="AsignadosAgg2" name="AsignadosAgg1"></form>
                            </div>
                            <div class="col-sm-1  col-md-1  col-lg-1  col-xl-1"></div>
                        </div>
                    </div>
                </div>
                <div class="row form-group center-block" style="background-color: powderblue;" id="div_equipos1"><center><h4>Equipo empleado</h4></center></div>
                <div class="row form-group center-block" id="div_equipos">
                    <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                        <label>Equipo:</label>
                    </div>
                    <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                        <select class="form-control" id="equipo">
                            <option value="0">--- Seleccione ---</option>
                            <?php 
                            $sql = "SELECT * FROM Equipos where idEquipo in (select idEquipos from detalle_equipos where clase_equipo != '7A975CD6-2672-430D-B29E-7149A03D9410')";
                            $result = sqlsrv_query($conn,$sql);
                            while ($cargador = sqlsrv_fetch_array($result)){
                                ?>
                                <option value="<?php echo $cargador['idEquipo']; ?>"><?php echo utf8_encode($cargador['Descripcion']." - ".$cargador['Identificacion']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div id="div_clasif">
                        <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                            <label>Tempo Clasif.:</label>
                        </div>
                        <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <input type="number" id="tempo_clasif" class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                        <label>Zona:</label>
                    </div>
                    <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                        <select class="form-control" id="zona_acopio"></select>
                    </div>
                </div>
                <div class="row form-group center-block" id="div_red" style="background-color: red; margin-top: -10px;"><center><h6></h6></center></div>
                <div class="row form-group center-block" id="divProducto" style="margin-top: -10px;">
                    <div id="div_pila">
                        <div class="col-xs-2  col-sm-3  col-md-1  col-lg-1  col-xl-1" id="">
                            <label>Tipo Material:</label>
                        </div>
                        <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3" id="">
                            <select id="pila" class="form-control">
                                <option value="0">Seleccione</option>
                                <?php 
                                $sql = "SELECT * FROM Pila_informes";
                                $res = sqlsrv_query($conn,$sql);
                                while($pilas = sqlsrv_fetch_array($res)){
                                    ?>
                                    <option value="<?php echo $pilas['id_pila']; ?>"><?php echo utf8_encode($pilas['descripcion']); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-2  col-sm-3  col-md-1  col-lg-1  col-xl-1" id="ProductoLabel">
                        <label>Producto:</label>
                    </div>
                    <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3" id="ProductoSelect">
                        <select id="producto" class="form-control" onchange="Producto()">
                            <option value="00000000-0000-0000-0000-000000000000">Ninguno</option>
                            <?php 
                            $sql = "SELECT DISTINCT(Clasificacion),Clasificacion.idClasificacion FROM tz_MovimientoTransporte  
                                    INNER JOIN Clasificacion ON tz_MovimientoTransporte.Clasificacion=Clasificacion.Descripcion
                                    WHERE year(FechaRegistro)>='$Year' ORDER BY Clasificacion";
                            $res = sqlsrv_query($conn,$sql);
                            while($clasificacion = sqlsrv_fetch_array($res)){
                                ?>
                                <option value="<?php echo $clasificacion['idClasificacion']; ?>"><?php echo utf8_encode($clasificacion['Clasificacion']); ?></option>
                                <?php
                            }
                            ?>
                        </select><br>
                    </div>
                    <div class="col-xs-2  col-sm-3  col-md-1  col-lg-1  col-xl-1" id="ProductoObjetivoLabel">
                        <label>Producto Objetivo:</label>
                    </div>
                    <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3" id="ProductoObjetivoSelect">
                        <!--<input type="hidden" id="ProductoObjetivo" value="00000000-0000-0000-0000-000000000000">-->
                        <select id="ProductoObjetivo" class="form-control" onchange="">
                            <option value="00000000-0000-0000-0000-000000000000">Ninguno</option>
                            <?php 
                            $sql = "SELECT DISTINCT(Clasificacion),Clasificacion.idClasificacion FROM tz_MovimientoTransporte  
                                    INNER JOIN Clasificacion ON tz_MovimientoTransporte.Clasificacion=Clasificacion.Descripcion
                                    WHERE year(FechaRegistro)>='$Year' ORDER BY Clasificacion";
                            $res = sqlsrv_query($conn,$sql);
                            while($clasificacion = sqlsrv_fetch_array($res)){
                                ?>
                                <option value="<?php echo $clasificacion['idClasificacion']; ?>"><?php echo utf8_encode($clasificacion['Clasificacion']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1" id="DestinoLabel">
                        <label>Destino:</label>
                    </div>
                    <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3" id="DestinoSelect">
                        <select id="destino" class="form-control">
                            <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
                            <?php 
                            $sql = "SELECT DISTINCT tz_MovimientoTransporte.RecepcionadoEn, Destino.idDestino
                              FROM tz_MovimientoTransporte INNER JOIN
                                   Destino ON tz_MovimientoTransporte.RecepcionadoEn = Destino.Descripcion
                              WHERE (YEAR(tz_MovimientoTransporte.FechaRegistro) >= $Year)
                              order by RecepcionadoEn";
                            $res = sqlsrv_query($conn,$sql);
                            while($clasificacion = sqlsrv_fetch_array($res)){
                                ?>
                                <option value="<?php echo $clasificacion['idDestino']; ?>"><?php echo utf8_encode($clasificacion['RecepcionadoEn']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row form-group center-block" style="background-color: powderblue;" id="bordePlantilla"><center><h4 class="modal-title" id="title"></h4></center></div>
                <div class="row form-group center-block" id="plantilla_cargue">
                    <div class="col-xs-6  col-sm-6  col-md-6  col-lg-6  col-xl-6">
                        <div class="row">
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <label>N° de vehiculos:</label>
                            </div>
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <input type="number" style="text-align:right;" id="n_vehiculos" class="form-control">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <button class="btn btn-default btn-xs">Und.</button>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <label>Tiempo Prom. Por Vehiculo:</label>
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <input type="number" style="text-align:right;" id="TempXvehiculo" class="form-control">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <button class="btn btn-default btn-xs">Min.</button>
                            </div>
                        </div>
                        <div class="row">
                            <br>
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <label>Total TM Cargue:</label>
                            </div>
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <input type="number" style="text-align:right;" id="TotalTM_cargue" class="form-control">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <button class="btn btn-default btn-xs">TM.</button>
                            </div>
                        </div>
                        <div class="row">
                            <br>
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4"></div>
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <button id="btn_calcularData1" class="btn btn-warning" onclick="calcularHoras('Cargue')">Calcular &nbsp;<span class="glyphicon glyphicon-th"></span></button>
                                <button id="btn_calcularModificar1" class="btn btn-warning" onclick="calcularHorasMod('Cargue')">Calcular &nbsp;<span class="glyphicon glyphicon-th"></span></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6  col-sm-6  col-md-6  col-lg-6  col-xl-6" id="calculado_cargue">
                        <div class="row">
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <label>Tiempo Reloj Est.</label>
                            </div>
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <input type="text" style="text-align:right;" id="TempReEstVehiculo" class="form-control" readonly="">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <button class="btn btn-default btn-xs">Hrs.</button>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <label>Tiempo Maquina Est.</label>
                            </div>
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <input type="text" style="text-align:right;" id="TempMaquinaEstVehiculo" class="form-control" readonly="">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <button class="btn btn-default btn-xs">Horo.</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- PLANTILLA PARA CONTEO POR PALADAS -->
                <div class="row form-group center-block" id="plantilla_paladas">
                    <center>
                        TM alimentadas: <b id="TM_alimentadas"></b>
                        <input type="hidden" id="TM_alimentadas1">
                        TM apiladas: <b id="TM_apiladas"></b>
                        <input type="hidden" id="TM_apiladas1">
                    </center>
                    <br>
                    <div class="col-xs-6  col-sm-6  col-md-6  col-lg-6  col-xl-6">
                        <div class="row">
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <label>N° de paladas:</label>
                            </div>
                            <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                                <input type="number" style="text-align:right;" id="n_paladas" class="form-control"  align="right" onkeyup="calcularHoras('palada');">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <button class="btn btn-default btn-xs">Und.</button>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <label>Tara:</label>
                            </div>
                            <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                                <input type="number" style="text-align:right;" id="TM_Palada" class="form-control" readonly="">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <button class="btn btn-default btn-xs">TM</button>
                            </div>
                        </div>
                        <!--<div class="row">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4">
                                <button id="btn_calcularData" class="btn btn-warning" onclick="calcularHoras('Clasificar')">Calcular &nbsp;<span class="glyphicon glyphicon-th"></span></button>
                                <button id="btn_calcularModificar" class="btn btn-warning" onclick="calcularHorasMod('Clasificar')">Calcular &nbsp;<span class="glyphicon glyphicon-th"></span></button>
                            </div>
                        </div>-->
                    </div>
                    <div class="col-xs-6  col-sm-6  col-md-6  col-lg-6  col-xl-6" id="calculado">
                        <div class="row">
                            <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                                <label>Total TM:</label>
                            </div>
                            <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                                <input type="number" style="text-align:right;" id="TotalTM" class="form-control" onkeyup="calcularHoras('tonelada')">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <button class="btn btn-default btn-xs">TM.</button>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                                <!--<label>Tiempo Est. Por Palada:</label>-->
                                <label>Tiempo Maquina Est.</label>
                            </div>
                            <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                                <!--<input type="number" style="text-align:right;" id="TempXpalada" class="form-control">-->
                                <input type="text" style="text-align:right;" id="TempMaquinaEst" class="form-control">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <!--<button class="btn btn-default btn-xs">Seg.</button>-->
                                <button class="btn btn-default btn-xs">Horo.</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group center-block" id="plantilla_apila_entra">
                    <div class="col-xs-2  col-sm-2  col-md-2  col-lg-2  col-xl-2"></div>
                    <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                        <label>Tiempo Estimado.</label>
                        <input type="number" id="time_est" class="form-control">
                    </div>
                    <div class="col-xs-5  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                        <label id="totalize_tm_title">Total TM</label>
                        <input type="number" id="totalize_tm" class="form-control">
                    </div>
                </div>
                <div class="row form-group center-block" style="background-color: powderblue;" style="margin-top: -15px;">
                    <center><h4 class="modal-title">Observaciones</h4></center>
                </div>
                <!--<div class="row form-group center-block">
                    <center><textarea class="form-control" rows="4" id="observacionesTiquete"></textarea></center>
                </div>-->
                <div class="row form-group center-block">
                    <center><textarea class="form-control" rows="4" id="observacionesTiquete"></textarea></center>
                </div>
                <div class="row form-group center-block">
                    <center><button class="btn btn-success btn-xs" onclick="GuardarObservaciones()">Grabar observaciones</button></center>
                </div>
            </div>
            <div class="loader" id="loader">Loading...</div>
            <div class="modal-footer">
                <input type="hidden" id="idDistribucion">
                <input type="hidden" id="idHorometro">
                <button id="GrabarDatos" class="btn btn-info" onclick="GrabarDatos('Cargue')">Guardar &nbsp;<span class="glyphicon glyphicon-save"></span></button>
                <button id="GrabarDatos1" class="btn btn-info" onclick="GrabarDatos('Clasificar')">Guardar &nbsp;<span class="glyphicon glyphicon-save"></span></button>
                <button id="GrabarDatos2" class="btn btn-info" onclick="GrabarDatos('Apilar_entra')">Guardar &nbsp;<span class="glyphicon glyphicon-save"></span></button>
                <button id="ModificarDatos" class="btn btn-secondary" onclick="ModificarDatos('Clasificar')">Modificar &nbsp;<span class="glyphicon glyphicon-save"></span></button>
                <button id="ModificarDatos1" class="btn btn-secondary" onclick="ModificarDatos('Cargue')">Modificar &nbsp;<span class="glyphicon glyphicon-save"></span></button>
                <button id="ModificarDatos2" class="btn btn-secondary" onclick="ModificarDatos('Apilar_entra')">Modificar &nbsp;<span class="glyphicon glyphicon-save"></span></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="AgregarHorometro" onclick="cerrar_tiempos()">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
<!---------------------------------------------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------------------------------------------------->

<div class="modal fade" id="modalAsignarTemp_edit" tabindex="0" role="dialog" style="z-index: 5000;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cerrar_edit()"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel_edit">Distribuir Tiempos Por Actividad</h4>
            </div>
            <div class="modal-body" id="div_distribucion">
<!--tiquete--------><input type="hidden" id="registroHorometro_edit" value="0">
<!--actividad------><input type="hidden" id="actividad_edit" value="0">
<!--producto-------><input type="hidden" id="producto_sec_edit" value="00000000-0000-0000-0000-000000000000">
<!--subactividad---><input type="hidden" id="sub_actividad_edit" value="0">
<!--patio Lejia----><input type="hidden" id="idPatioLejia_edit" value="0">
<!--usuario--------><input type="hidden" id="usuario_edit" value="<?php echo $usuario; ?>">
                <div class="row form-group center-block">
                    <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                        <button id="volverStandby_edit" class="btn btn-dark navbar-left" onclick="MostrarOcultar_edit('3')">Standby <span class="glyphicon glyphicon-time"></span></button>
                        <button id="volverAct_edit" class="btn btn-primary navbar-left" onclick="MostrarOcultar_edit('1')">Asignar Tiempos <span class="glyphicon glyphicon-back"></span></button>
                    </div>
                    <div class="col-xs-6  col-sm-6  col-md-6  col-lg-6  col-xl-6">
                        <center><h4 id="FaltaAsignar_edit"></h4></center>
                        <input type="hidden" id="ValoresAsignados_edit">
                    </div>
                    <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                        <button id="checked_edit" class="btn btn-success btn-xs navbar-right" style="margin-right: 3px;" title="Se guardó los datos correctamente"><span class="glyphicon glyphicon-ok"></span></button>
                        <button id="volverDesc_edit" class="btn btn-warning navbar-right" onclick="MostrarOcultar_edit('2')">Descuentos <span class="glyphicon glyphicon-cog"></span></button>
                    </div>
                </div>
                <div class="row form-group center-block" style="background-color: powderblue;"><center><h4 id="act_edit">Actividades</h4></center></div>
                <div class="row form-group center-block" id="divStandby_edit">
                    <div class="row">
                        <div class="col-xs-2  col-sm-2  col-md-2  col-lg-2  col-xl-2"></div>
                        <?php //if($tablet_browser == 0 && $mobile_browser == 0){
                            ?>
                        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <?php
                        /*}else{
                            ?>
                        <div class="col-sm-4">
                            <?php
                        } */?>
                            <br>
                            <label>Tiempo de Descuento</label>
                        </div>
                        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <br>
                            <input type="number" style="text-align:right;" id="ValorStandby_edit" class="form-control">
                        </div>
                        <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                            <br>
                            <button class="btn btn-default btn-xs">Horo.</button>
                        </div>
                    </div>
                    <br>
                    <!--<input type="hidden" id="motivoStandby">-->
                    <div class="row">
                        <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4"></div>
                        <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                            <label>Motivo del Standby</label>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <center><textarea class="form-control" rows="4" id="motivoStandby_edit"></textarea></center>
                    </div>
                    <div class="row">
                        <center>
                            <button id="btn_apliStandby_edit" class="btn btn-success" onclick="AplicarStandby_edit()">Aplicar Standby</button>
                            <button id="btn_modStandby_edit" class="btn btn-success" onclick="ModificarStandby_edit()">Modificar Standby</button>
                        </center>
                    </div>
                </div>
                <div class="row form-group center-block" id="divDescuento_edit">
                    <div class="row">
                        <div class="col-xs-2  col-sm-2  col-md-2  col-lg-2  col-xl-2"></div>
                        <?php// if($tablet_browser == 0 && $mobile_browser == 0){
                            ?>
                        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <?php
                        /*}else{
                            ?>
                        <div class="col-sm-4">
                            <?php
                        } */?>
                            <br>
                            <label>Tiempo de Descuento</label>
                        </div>
                        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <br>
                            <input type="number" style="text-align:right;" id="ValorDescuento_edit" class="form-control">
                        </div>
                        <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                            <br>
                            <button class="btn btn-default btn-xs">Horo.</button>
                        </div>
                        <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3" id="divTM_despacho_edit">
                            <label>TM Despacho</label>
                            <input type="number" id="TotalTM_Despacho_edit" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4"></div>
                        <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                            <label>Motivo del descuento</label>
                        </div>
                    </div>
                    <div class="row form-group center-block">
                        <center><textarea class="form-control" rows="4" id="motivoDescuento_edit"></textarea></center>
                    </div>
                    <div class="row">
                        <center>
                            <button id="btn_apliDesc_edit" class="btn btn-success" onclick="AplicarDescuento_edit()">Aplicar Descuento</button>
                            <button id="btn_modDesc_edit" class="btn btn-success" onclick="ModificarDescuento_edit()">Modificar Descuento</button>
                        </center>
                    </div>
                </div>
                <div class="row form-group center-block" id="ActDetallado_edit">
                    <input type="hidden" id="encrypt_edit" value="<?php echo $encrypt = '3F001906-CFF3-437F-A4C5-67CDADDFD904'; ?>">
                    <input type="hidden" id="encrypt1_edit" value="<?php echo $encrypt1 = '525242E8-D5DE-479C-8799-F2DE6F8527C7'; ?>">
                    <form id="form_edit" name="form_edit">
                        <?php
                        $sql = "SELECT * FROM Actividades WHERE idTipoActividad='00000000-0000-0000-0000-000000000001' AND Descripcion not in('STANDBY')";
                        $resul = sqlsrv_query($conn,$sql);
                        while ($actividad = sqlsrv_fetch_array($resul)) {
                        ?>
                            <div class="col-xs-6  col-sm-4  col-md-4  col-lg-4  col-xl-4" style="margin-bottom: 15px;">
                                <center><button type="button" id="<?php echo $actividad['idActividad']; ?>_edit" class="btn btn-default" value="<?php echo $actividad['idActividad']; ?>" onclick="actividad_edit('<?php echo $actividad['idActividad']; ?>')" ><?php echo utf8_encode($actividad['Descripcion']) ?></button>
                                </center>
                            </div>
                        <?php
                        }
                        ?>
                    </form>
                </div>
                <div class="row form-group center-block" id="div_red_edit" style="background-color: red; margin-top: -10px;"><center><h6></h6></center></div>
                <div class="row form-group center-block" id="divProducto_edit" style="margin-top: -10px;">
                    <div id="div_pila_edit">
                        <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                            <label>Tipo Material:</label>
                        </div>
                        <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <select id="pila_edit" class="form-control">
                                <option value="0">Seleccione</option>
                                <?php 
                                $sql = "SELECT * FROM Pila_informes";
                                $res = sqlsrv_query($conn,$sql);
                                while($pilas = sqlsrv_fetch_array($res)){
                                    ?>
                                    <option value="<?php echo $pilas['id_pila']; ?>"><?php echo utf8_encode($pilas['descripcion']); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1" id="ProductoLabel_edit">
                        <label>Producto:</label>
                    </div>
                    <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3" id="ProductoSelect_edit">
                        <select id="producto_edit" class="form-control" onchange="Producto_edit()">
                            <option value="00000000-0000-0000-0000-000000000000">Ninguno</option>
                            <?php 
                            $sql = "SELECT DISTINCT(Clasificacion),Clasificacion.idClasificacion FROM tz_MovimientoTransporte  
                                    INNER JOIN Clasificacion ON tz_MovimientoTransporte.Clasificacion=Clasificacion.Descripcion
                                    WHERE year(FechaRegistro)>='$Year' ORDER BY Clasificacion";
                            $res = sqlsrv_query($conn,$sql);
                            while($clasificacion = sqlsrv_fetch_array($res)){
                                ?>
                                <option value="<?php echo $clasificacion['idClasificacion']; ?>"><?php echo utf8_encode($clasificacion['Clasificacion']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1" id="DestinoLabel_edit">
                        <label>Destino:</label>
                    </div>
                    <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3" id="DestinoSelect_edit">
                        <select id="destino_edit" class="form-control">
                            <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
                            <?php 
                            $sql = "SELECT DISTINCT tz_MovimientoTransporte.RecepcionadoEn, Destino.idDestino
                              FROM tz_MovimientoTransporte INNER JOIN
                                   Destino ON tz_MovimientoTransporte.RecepcionadoEn = Destino.Descripcion
                              WHERE (YEAR(tz_MovimientoTransporte.FechaRegistro) >= $Year)
                              order by RecepcionadoEn";
                            $res = sqlsrv_query($conn,$sql);
                            while($clasificacion = sqlsrv_fetch_array($res)){
                                ?>
                                <option value="<?php echo $clasificacion['idDestino']; ?>"><?php echo utf8_encode($clasificacion['RecepcionadoEn']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 5px;">
                    <!--<br>-->
                    <div class="col-xs-12  col-sm-5  col-md-5  col-lg-5  col-xl-5">
                        <div class="row form-group center-block" style="background-color: powderblue;" id="borde_edit"><center><h4>SubActividades</h4></center></div>
                        <div class="row form-group center-block" id="div_subactividad_edit">
                                <form id="frm_edit" name="frm_edit"></form>
                        </div>
                    </div>
                    <div class="col-xs-12  col-sm-7  col-md-7  col-lg-7  col-xl-7">
                        <div class="row form-group center-block" style="background-color: #F7934E;" id="bordeAsignados_edit"><center><h4>Tiempos Asignados</h4></center></div>
                        <div class="row" id="divAsignados_edit" style="margin-top: -10px;">
                            <form id="AsignadosAgg_edit" name="AsignadosAgg_edit"></form>
                        </div>
                        <div class="row" id="divAsignados1_edit" style="margin-top: -10px;">
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1"></div>
                            <div class="col-xs-5  col-sm-5  col-md-5  col-lg-5  col-xl-5" style="border: 3px solid #DCDCDC; margin-right: 5px;">
                                <!-- style="border: 3px solid #DCDCDC;"-->
                                <center><h5><b>ALIMENTAR</b></h5></center>
                                <form id="AsignadosAgg1_edit" name="AsignadosAgg_edit"></form>
                            </div>
                            <div class="col-xs-5  col-sm-5  col-md-5  col-lg-5  col-xl-5" style="border: 3px solid #DCDCDC; margin-left: 5px;">
                                <center><h5><b>APILAR</b></h5></center>
                                <form id="AsignadosAgg2_edit" name="AsignadosAgg1_edit"></form>
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1"></div>
                        </div>
                    </div>
                </div>
                <div class="row form-group center-block" style="background-color: powderblue;" id="div_equipos1_edit"><center><h4>Equipo empleado</h4></center></div>
                <div class="row form-group center-block" id="div_equipos_edit">
                    <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                        <label>Equipo:</label>
                    </div>
                    <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                        <select class="form-control" id="equipo_edit">
                            <option value="0">--- Seleccione ---</option>
                            <?php 
                            $sql = "SELECT * FROM Equipos where idEquipo in (select idEquipos from detalle_equipos where clase_equipo != '7A975CD6-2672-430D-B29E-7149A03D9410')";
                            $result = sqlsrv_query($conn,$sql);
                            while ($cargador = sqlsrv_fetch_array($result)){
                                ?>
                                <option value="<?php echo $cargador['idEquipo']; ?>"><?php echo utf8_encode($cargador['Descripcion']." - ".$cargador['Identificacion']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div id="div_clasif_edit">
                        <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                            <label>Tempo Clasif.:</label>
                        </div>
                        <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                            <input type="number" id="tempo_clasif_edit" class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-xs-2  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                        <label>Zona:</label>
                    </div>
                    <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                        <select class="form-control" id="zona_acopio_edit"></select>
                    </div>
                </div>
                <div class="row form-group center-block" style="background-color: powderblue;" id="bordePlantilla_edit"><center><h4 class="modal-title" id="title_edit"></h4></center></div>
                <div class="row form-group center-block" id="plantilla_cargue_edit">
                    <div class="col-xs-6  col-sm-6  col-md-6  col-lg-6  col-xl-6">
                        <div class="row">
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <label>N° de vehiculos:</label>
                            </div>
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <input type="number" style="text-align:right;" id="n_vehiculos_edit" class="form-control">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <button class="btn btn-default btn-xs">Und.</button>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <label>Tiempo Prom. Por Vehiculo:</label>
                            </div>
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <input type="number" style="text-align:right;" id="TempXvehiculo_edit" class="form-control">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <button class="btn btn-default btn-xs">Min.</button>
                            </div>
                        </div>
                        <div class="row">
                            <br>
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <label>Total TM Cargue:</label>
                            </div>
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <input type="number" style="text-align:right;" id="TotalTM_cargue_edit" class="form-control">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <button class="btn btn-default btn-xs">TM.</button>
                            </div>
                        </div>
                        <div class="row">
                            <br>
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4"></div>
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <button id="btn_calcularData1_edit" class="btn btn-warning" onclick="calcularHoras_edit('Cargue')">Calcular &nbsp;<span class="glyphicon glyphicon-th"></span></button>
                                <button id="btn_calcularModificar1_edit" class="btn btn-warning" onclick="calcularHorasMod_edit('Cargue')">Calcular &nbsp;<span class="glyphicon glyphicon-th"></span></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6" id="calculado_cargue_edit">
                        <div class="row">
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <label>Tiempo Reloj Est.</label>
                            </div>
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <input type="text" style="text-align:right;" id="TempReEstVehiculo_edit" class="form-control" readonly="">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <button class="btn btn-default btn-xs">Hrs.</button>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <label>Tiempo Maquina Est.</label>
                            </div>
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <input type="text" style="text-align:right;" id="TempMaquinaEstVehiculo_edit" class="form-control" readonly="">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <button class="btn btn-default btn-xs">Horo.</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group center-block" id="plantilla_paladas_edit">
                    <center>
                        TM alimentadas: <b id="TM_alimentadas_edit"></b>
                        <input type="hidden" id="TM_alimentadas1_edit">
                        TM apiladas: <b id="TM_apiladas_edit"></b>
                        <input type="hidden" id="TM_apiladas1_edit">
                    </center>
                    <br>
                    <div class="col-xs-6  col-sm-6  col-md-6  col-lg-6  col-xl-6">
                        <div class="row">
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <label>N° de paladas:</label>
                            </div>
                            <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                                <input type="number" style="text-align:right;" id="n_paladas_edit" class="form-control"  align="right" onkeyup="calcularHoras_edit('palada');">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <button class="btn btn-default btn-xs">Und.</button>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                                <label>TM x Palada:</label>
                            </div>
                            <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                                <input type="number" style="text-align:right;" id="TM_Palada_edit" class="form-control" readonly="">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <button class="btn btn-default btn-xs">TM</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6  col-sm-6  col-md-6  col-lg-6  col-xl-6" id="calculado_edit">
                        <div class="row">
                            <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                                <label>Total TM:</label>
                            </div>
                            <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                                <input type="number" style="text-align:right;" id="TotalTM_edit" class="form-control" onkeyup="calcularHoras_edit('tonelada')">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <button class="btn btn-default btn-xs">TM.</button>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                                <!--<label>Tiempo Est. Por Palada:</label>-->
                                <label>Tiempo Maquina Est.</label>
                            </div>
                            <div class="col-xs-4  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                                <!--<input type="number" style="text-align:right;" id="TempXpalada" class="form-control">-->
                                <input type="text" style="text-align:right;" id="TempMaquinaEst_edit" class="form-control">
                            </div>
                            <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                                <!--<button class="btn btn-default btn-xs">Seg.</button>-->
                                <button class="btn btn-default btn-xs">Horo.</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group center-block" id="plantilla_apila_entra_edit">
                    <div class="col-xs-4  col-sm-2  col-md-2  col-lg-2  col-xl-2"></div>
                    <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                        <label>Tiempo Estimado.</label>
                        <input type="number" id="time_est_edit" class="form-control">
                    </div>
                    <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                        <label id="totalize_tm_title_edit">Total TM</label>
                        <input type="number" id="totalize_tm_edit" class="form-control">
                    </div>
                </div>
                <div class="row form-group center-block" style="background-color: powderblue;" style="margin-top: -15px;">
                    <center><h4 class="modal-title">Observaciones</h4></center>
                </div>
                <!--<div class="row form-group center-block">
                    <center><textarea class="form-control" rows="4" id="observacionesTiquete"></textarea></center>
                </div>-->
                <div class="row form-group center-block">
                    <center><textarea class="form-control" rows="4" id="observacionesTiquete_edit"></textarea></center>
                </div>
                <div class="row form-group center-block">
                    <center><button class="btn btn-success btn-xs" onclick="GuardarObservaciones_edit()">Grabar observaciones</button></center>
                </div>
            </div>
            <div class="modal-body" id="div_info_tique">
                <div class="row">
                    <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1">
                        <label>Remisión:</label>
                    </div>
                    <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1"></div>
                    <div class="col-xs-2  col-sm-2  col-md-2  col-lg-2  col-xl-2">
                        <input type="number" class="form-control" id="numero_remision_edit">
                    </div>
                    <div class="col-xs-2  col-sm-2  col-md-2  col-lg-2  col-xl-2"></div>
                    <div class="col-xs-2  col-sm-2  col-md-2  col-lg-2  col-xl-2">
                        <label>Maquinaria:</label>
                    </div>
                    <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                        <input type="text" class="form-control" id="select_maquinaria_edit" readonly="">
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-xs-2  col-sm-2  col-md-2  col-lg-2  col-xl-2">
                        <label>Fecha Apertura:</label>
                    </div>
                    <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                        <input type="date" class="form-control" id="fecha_apertura_edit">
                    </div>
                    <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1"></div>
                    <div class="col-xs-2  col-sm-2  col-md-2  col-lg-2  col-xl-2">
                        <label>Fecha Cierre:</label>
                    </div>
                    <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                        <input type="date" class="form-control" id="fecha_cierre_edit">
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-xs-2  col-sm-2  col-md-2  col-lg-2  col-xl-2">
                        <label>Lugar:</label>
                    </div>
                    <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                        <select class="form-control" id="select_patio_edit">
                            <option>Seleecione</option>
                        </select>
                    </div>
                    <div class="col-xs-1  col-sm-1  col-md-1  col-lg-1  col-xl-1"></div>
                    <div class="col-xs-2  col-sm-2  col-md-2  col-lg-2  col-xl-2">
                        <label>Proveedor:</label>
                    </div>
                    <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3">
                        <select class="form-control" id="select_proveedor_edit">
                            <option>Seleecione</option>
                        </select>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4"></div>
                    <div class="col-xs-4  col-sm-4  col-md-4  col-lg-4  col-xl-4">
                        <center><button class="btn btn-success" onclick="actualiza_info_tique()">Guardar <span class="glyphicon glyphicon-file"></span></button></center>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <input type="hidden" id="combo_vista" value="0">
                    <div class="col-xs-4  col-sm-6  col-md-6  col-lg-6  col-xl-6" id="ver_info_tique" style="text-align: left">
                        <button type="button" class="btn btn-dark" onclick="ver_info_tique()">
                            Ver &nbsp;<span class="glyphicon glyphicon-list-alt"></span>
                        </button>
                    </div>
                    <div class="col-xs-4  col-sm-6  col-md-6  col-lg-6  col-xl-6" id="ver_distr_tique" style="text-align: left">
                        <button type="button" class="btn btn-dark" onclick="ver_info_tique()">
                            Volver &nbsp;<span class="glyphicon glyphicon-cog"></span>
                        </button>
                    </div>
                    <div class="col-xs-8  col-sm-6  col-md-6  col-lg-6  col-xl-6" style="text-align: right">
                        <input type="hidden" id="idDistribucion_edit">
                        <input type="hidden" id="idHorometro_edit">
                        <button id="GrabarDatos_edit" class="btn btn-info" onclick="GrabarDatos_edit('Cargue')">Guardar &nbsp;<span class="glyphicon glyphicon-save"></span></button>
                        <button id="GrabarDatos1_edit" class="btn btn-info" onclick="GrabarDatos_edit('Clasificar')">Guardar &nbsp;<span class="glyphicon glyphicon-save"></span></button>
                        <button id="GrabarDatos2_edit" class="btn btn-info" onclick="GrabarDatos_edit('Apilar_entra')">Guardar &nbsp;<span class="glyphicon glyphicon-save"></span></button>
                        <button id="ModificarDatos_edit" class="btn btn-secondary" onclick="ModificarDatos_edit('Clasificar')">Modificar &nbsp;<span class="glyphicon glyphicon-save"></span></button>
                        <button id="ModificarDatos1_edit" class="btn btn-secondary" onclick="ModificarDatos_edit('Cargue')">Modificar &nbsp;<span class="glyphicon glyphicon-save"></span></button>
                        <button id="ModificarDatos2_edit" class="btn btn-secondary" onclick="ModificarDatos_edit('Apilar_entra')">Modificar &nbsp;<span class="glyphicon glyphicon-save"></span></button>
                        <button type="button" class="btn btn-dark" data-dismiss="modal" id="AgregarHorometro_edit" onclick="actualizar_edit()">
                            Actualizar &nbsp;<span class="glyphicon glyphicon-floppy-save"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!---------------------------------------------------------------------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------------------------------------------------->