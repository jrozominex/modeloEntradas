<?php
session_start();
require_once '../modelo/conexion.php';
if ($_SESSION["logueado"] == TRUE && $_SESSION["permisoIngresar"] != 'AUXILIAR_PATIO') {
    $usuario = $_SESSION['usuario'];
    $Fecha = date('Y-m-d');
    $Fecha_actual = date('Y-m-d',strtotime($Fecha . ' - 15 days'));
    ini_set('date.timezone', 'America/Bogota');
    $hora = date("g:i A");
    $sql_usuario = "SELECT * FROM Usuarios WHERE idUsuario='$usuario'";
    $result = sqlsrv_query($conn,$sql_usuario);
    while ($row = sqlsrv_fetch_array($result)){
      $Nombre = $row['NombreUsuarioLargo'];
      $password = $row['Password'];
    }
}elseif($_SESSION['permisoIngresar'] == 'AUXILIAR_PATIO'){
    //header("Location: inicio_patio.php");
    ?>
    <script type="text/javascript">
        self.location='inicio_patio.php';
        alert('No tienes permiso para acceder a este ambiente');
    </script>
    <?php
}else{
    //header("Location: ../index.php");
    ?>
    <script type="text/javascript">
        self.location='../index.php';
        alert('Inicia sesión primero');
    </script>
    <?php
    die();
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include './libreria.php'; ?>
    <meta name="viewport" content="width=auto, initial-scale=0.8">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>Servicio Clasificaciòn</title>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#div_modificar_prod').hide();
            $('#div_consulta_clasif').hide();
            $('#div_registro_clasif').hide();
        })

        function llenar_sel_equipo(){
            var tipo_maquinaria = $('#tipo_maquinaria').val();
            //var selected = $('#input_tipo_maquina').val();
            var proveedor = $('#proveedor').val();
            if(tipo_maquinaria!='0' && proveedor!='0'){
                band = 1;
                $.post("buscar.php", {tipo_maquinaria: tipo_maquinaria, /*seleccionado: selected,*/ proveedor: proveedor, band: band}, 
                function(mensaje){
                  //  console.log(mensaje);
                    $('#Equipo').html(mensaje).fadeIn();
                });
            }else{
                $('#Equipo').html('<option value="0">No hay maquinaria</option>').fadeIn();
            }
        }
        
        function llenar_sel_equipo_consulta(){
            var tipo_maquinaria = $('#tipo_maquinaria_1').val();
            //var selected = $('#input_tipo_maquina_1').val();
            band = 12;
            $.post("buscar.php", {tipo_maquinaria: tipo_maquinaria, /*seleccionado: selected,*/ band: band}, 
            function(mensaje){
                //console.log(mensaje);
                $('#Equipo_1').html(mensaje).fadeIn();
            });
        }

        function activar_botton(){
            var empresa = $('#empresa_1').val(); 
            if(empresa==0){
                $('#generar').prop('disabled', true);
                $('#descargar').prop('disabled', true);
            }else{
                $('#generar').prop('disabled', false);
                $('#descargar').prop('disabled', false);
            }
        }

        function generar_informe(){ 
            var fecha_1 = $('#fecha_1').val(); 
            var fecha_2 = $('#fecha_2').val(); 
            bandera=0;
            if(fecha_1=="") {
                $('#fecha_1').css({'border-color':"#F26F37"});
                bandera=1;
            }else{ 
                $('#fecha_1').css({'border-color':"#A8B0AF"});
            }
            if (fecha_2==""){
                $('#fecha_2').css({'border-color':"#F26F37"});
                bandera=1;
            }else{ 
                $('#fecha_2').css({'border-color':"#A8B0AF"});
            }
            if(bandera ==1)
                alertify.error('Complete los campos....');
            else{
                var empresa_1 = $('#empresa_1').val(); 
                var proveedor_1 = $('#proveedor_1').val(); 
                var patio_1 = $('#patio_1').val(); 
                var actividad_1 = $('#actividad_1').val(); 
                var tipo_maquinaria_1 = $('#tipo_maquinaria_1').val(); 
                var Equipo_1 = $('#Equipo_1').val(); 
                var grupo_material_1 = $('#grupo_material_1').val();
                var material_alimentado_1 = $('#material_alimentado_1').val(); 
                band=10;
                cadena = "band=" + band +
                    "&fecha_ini=" + fecha_1 +
                    "&fecha_fin=" + fecha_2 +
                    "&empresa_1=" + empresa_1 +
                    "&proveedor_1=" + proveedor_1 +
                    "&patio_1=" + patio_1 +
                    "&actividad_1=" + actividad_1 +
                    "&tipo_maquinaria_1=" + tipo_maquinaria_1 +
                    "&Equipo_1=" + Equipo_1 +
                    "&grupo_material_1=" + grupo_material_1 +
                    "&material_alimentado_1=" + material_alimentado_1;
                $.ajax({
                    type: "POST",
                    url: "buscar.php",
                    data: cadena,
                    success: function (r){
                     //   console.log(r);
                        if(r!=1){
                            $('#div_resultados').show();
                            $('#div_resultados').html(r);
                        }else{
                            alertify.error('No existen registros...  Verifique filtros');
                            $('#div_resultados').hide();
                        }
                    }
                });
            }
        }

        function material_producido(){
            var numerotransaccion = $('#numerotransaccion').val();
            var recibo = $('#recibo').val();
            var fecha = $('#fecha').val();
            var semana = $('#semana').val();
            var empresa = $('#empresa').val();
            var proveedor = $('#proveedor').val();
            var usuario = $('#usuario').val();
            var actividad = $('#actividad').val();
            var Equipo = $('#Equipo').val();
            var patio = $('#patio').val();
            var pila = $('#pila').val();
            var tm_alimen = $('#tm_alimen').val();
            var horas_alimen = $('#horas_alimen').val();
            var material_alimentado = $('#material_alimentado').val();
            var material_objetivo = $('#material_objetivo').val();
            /////////////////////////////////////////////////////////////////////
            var material =$('#materia_prod').val();
            var tm = $('#tm_producido').val();
            
            band = 3;
            var v = 0;
            if(fecha == '' || fecha == null){
                v = 1;
            }
            if(empresa == '0'){
                v = 2;
            }
            if(proveedor == '0'){
                v = 3;
            }
            if(usuario == '0'){
                v = 4;
            }
            if(actividad == '0'){
                v = 5;
            }
            if(Equipo == '0'){
                v = 6;
            }
            if(patio == '0'){
                v = 7;
            }
            if(pila == ''){
                v = 8;
            }
            if(tm_alimen == 0){
                v = 9;
            }
            if(material_alimentado == '0'){
                v = 10;
            }
            if(material_objetivo == '0'){
                v = 11;
            }
            if(material == '0'){
                v = 12;
            }
            if(tm == 0){
                v = 13;
            }
            if(proveedor==empresa){
                if(horas_alimen == '' || horas_alimen == 0){
                    v = 14;
                }
            }
            if(v != 0){
                //alert(v);
                alert('Complete todos los campos "' + v + '"');
            }else{
                //alert('addf');
                var acumulado = parseFloat($('#tm_acumulado1').val())+parseFloat(tm);
                if(acumulado <= tm_alimen){
                    cadena = "recibo=" + recibo +
                             "&fecha=" + fecha +
                             "&semana=" + semana +
                             "&empresa=" + empresa +
                             "&proveedor=" + proveedor +
                             "&usuario=" + usuario +
                             "&actividad=" + actividad +
                             "&Equipo=" + Equipo +
                             "&patio=" + patio +
                             "&material_alimentado=" + material_alimentado +
                             "&material_objetivo=" + material_objetivo +
                             "&pila=" + pila +
                             "&tm_alimen=" + tm_alimen +
                             "&horas_alimen=" + horas_alimen +
                             "&material_producido=" + material +
                             "&tm_producido=" + tm +
                             "&numerotransaccion=" + numerotransaccion +
                             "&band=" + band;
                    $.ajax({
                        type: "POST",
                        url: "buscar.php",
                        data: cadena,
                        success: function (r){
                            //console.log(r);
                            split = r.split("||");
                            if(split[1] != ''){
                                $('#numerotransaccion').val(split[0]);
                                //var text_tm = 'Toneladas ' + split[2];
                                var text_porcen = split[3] + ' %';
                                $('#tm_acumulado').html(split[2]);
                                $('#tm_acumulado1').val(split[2]);
                                $('#porcen_acumulado').html(text_porcen);
                                const datosAsignados = JSON.parse(split[1]);
                                let bodyAsignados = '';
                                datosAsignados.forEach(asign => {
                                  bodyAsignados += `
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <a href="javascript:carga_modificar('${asign.id_detalle}')">${asign.Material}</a>
                                        </div>
                                        <div class="col-sm-3">${asign.tm}</div>
                                        <div class="col-sm-4">${asign.porcentaje} %</div>
                                    </div>`
                                });
                                $('#div_material_prod').html(bodyAsignados);
                                if($('#tm_acumulado1').val()==tm_alimen){
                                    document.getElementById('guardar').disabled="";
                                }else{
                                    document.getElementById('guardar').disabled="true";
                                }
                                //load_select_materia_producido();
                                $('#tm_producido').val('');
                            }else{
                                alert('El material ya ha sido registrado');
                            }
                        }
                    });
                }else{
                    alert('Hay más material producido que alimentado');
                } 
            }
        }
        /*function load_select_materia_producido(){
            var numerotransaccion = $('#numerotransaccion').val();
            var grupo = $('#grupo_material').val();
            band = 18;
            $.post("buscar.php", {grupo: grupo, band: band, numerotransaccion: numerotransaccion}, 
            function(mensaje) {
                console.log(mensaje);
                $('#materia_prod').html(mensaje).fadeIn();
            });
        }*/
        function llenar_sel_material(){
            var numerotransaccion = $('#numerotransaccion').val();
            var grupo = $('#grupo_material').val();
            band = 4;
            $.post("buscar.php", {grupo: grupo, band: band, numerotransaccion: numerotransaccion}, 
            function(mensaje) {
                split = mensaje.split("||");
                //console.log(mensaje);
                $('#material_alimentado').html(split[0]).fadeIn();
                $('#material_objetivo').html(split[0]).fadeIn();
                $('#materia_prod').html(split[0]).fadeIn();
                if(split[1] != 0){
                    $('#div_material_prod').html('');
                    $('#tm_acumulado').html('');
                    $('#tm_acumulado1').val('0');
                    $('#porcen_acumulado').html('');
                    alert('Se ha cambiado el material alimentado');
                    document.getElementById('guardar').disabled="true";
                }
            });
        }

        function llenar_sel_material_consul(){
            var grupo = $('#grupo_material_1').val();
            band = 14; 
            $.post("buscar.php", {grupo: grupo, band: band}, 
            function(mensaje) {
              //  console.log(mensaje);
                $('#material_alimentado_1').html(mensaje).fadeIn();
            });
        }

        function carga_modificar(id_detalle){
            band = 5; 
            var grupo = $('#grupo_material').val();
            $.post("buscar.php", {id_detalle: id_detalle, band: band, grupo: grupo}, 
            function(mensaje) {
                //console.log(mensaje);
                split = mensaje.split("||");
                $('#materia_prod_mod').html(split[0]).fadeIn();
                $('#tm_producido_mod').val(split[1]);
                $('#tm_producido_mod_1').val(split[1]);
                $('#id_detalle_mod').val(id_detalle);
                $('#div_modificar_prod').show();
            });
        }

        function modificar_producido(){
            var id_detalle_mod = $('#id_detalle_mod').val();
            var material_mod = $('#materia_prod_mod').val();
            var tm_mod = $('#tm_producido_mod').val();
            var tm_mod_original = $('#tm_producido_mod_1').val();
            var band = 6;
            var numerotransaccion = $('#numerotransaccion').val();
            var tm_alimen = $('#tm_alimen').val();
            cadena = "id_detalle=" + id_detalle_mod +
                     "&material=" + material_mod +
                     "&tm=" + tm_mod +
                     "&numerotransaccion=" + numerotransaccion +
                     "&tm_alimen=" + tm_alimen +
                     "&band=" + band;
            var tm_acumulado = $('#tm_acumulado1').val();
            var suma_tot = parseInt(tm_acumulado)-parseInt(tm_mod_original)+parseInt(tm_mod);
            //alert(suma_tot);
            if(suma_tot <= tm_alimen){
                $.ajax({
                    type: "POST",
                    url: "buscar.php",
                    data: cadena,
                    success: function (r){
                        //console.log(r);
                        let bodyAsignados = '';
                        split = r.split("||");
                        //var text_tm = 'Toneladas ' + split[1];
                        if(split[0] != '' && split[0] != '1'){
                            var text_porcen = split[2] + ' %';
                            $('#tm_acumulado').html(split[1]);
                            $('#tm_acumulado1').val(split[1]);
                            $('#porcen_acumulado').html(text_porcen);
                            const datosAsignados = JSON.parse(split[0]);
                            datosAsignados.forEach(asign => {
                            bodyAsignados += `
                                <div class="row">
                                    <div class="col-sm-5">
                                        <a href="javascript:carga_modificar('${asign.id_detalle}')">${asign.Material}</a>
                                    </div>
                                    <div class="col-sm-3">${asign.tm}</div>
                                    <div class="col-sm-4">${asign.porcentaje} %</div>
                                </div>`
                            });
                            $('#div_material_prod').html(bodyAsignados);
                            $('#div_modificar_prod').hide();
                            //load_select_materia_producido();
                            if($('#tm_acumulado1').val()==tm_alimen){
                                document.getElementById('guardar').disabled="";
                            }else{
                                document.getElementById('guardar').disabled="true";
                            }
                        }else if(split[0] != '1'){
                            $('#div_material_prod').html('');
                            $('#tm_acumulado').html('');
                            $('#tm_acumulado1').val('');
                            $('#porcen_acumulado').html('');
                            $('#div_material_prod').html(bodyAsignados);
                            $('#div_modificar_prod').hide();
                            document.getElementById('guardar').disabled="true";
                        }else{
                            alert('El material ya ha sido registrado');
                        }
                        
                    }
                });
            }else{
                alert('Las toneladas a modificar exceden las alimentadas')
            }
        }
        function guardar_registro(){
            var numerotransaccion = $('#numerotransaccion').val();
            var recibo = $('#recibo').val();
            var fecha = $('#fecha').val();
            var semana = $('#semana').val();
            var empresa = $('#empresa').val();
            var proveedor = $('#proveedor').val();
            var usuario = $('#usuario').val();
            var actividad = $('#actividad').val();
            var Equipo = $('#Equipo').val();
            var patio = $('#patio').val();
            var pila = $('#pila').val();
            var tm_alimen = $('#tm_alimen').val();
            var material_alimentado = $('#material_alimentado').val();
            var material_objetivo = $('#material_objetivo').val();
            var horas_equipo = $('#horas_alimen').val();
            band = 7;
            var v = 0;
            if(fecha == '' || fecha == null){
                v = 1;
            }
            if(empresa == '0'){
                v = 2;
            }
            if(proveedor == '0'){
                v = 3;
            }
            if(usuario == '0'){
                v = 4;
            }
            if(actividad == '0'){
                v = 5;
            }
            if(Equipo == '0'){
                v = 6;
            }
            if(patio == '0'){
                v = 7;
            }
            if(pila == ''){
                v = 8;
            }
            if(tm_alimen == 0){
                v = 9;
            }
            if(material_alimentado == '0'){
                v = 10;
            }
            if(material_objetivo == '0'){
                v = 11;
            }
            if(proveedor==empresa){
                if(horas_equipo == '0'){
                    v = 12;
                }
            }
            if(v != 0){
                alert('Complete todos los campos "' + v + '"');
            }else{
                cadena = "recibo=" + recibo +
                         "&fecha=" + fecha +
                         "&semana=" + semana +
                         "&empresa=" + empresa +
                         "&proveedor=" + proveedor +
                         "&usuario=" + usuario +
                         "&actividad=" + actividad +
                         "&Equipo=" + Equipo +
                         "&patio=" + patio +
                         "&material_alimentado=" + material_alimentado +
                         "&material_objetivo=" + material_objetivo +
                         "&horas_equipo=" + horas_equipo +
                         "&pila=" + pila +
                         "&tm_alimen=" + tm_alimen +
                         "&numerotransaccion=" + numerotransaccion +
                         "&band=" + band;
                $.ajax({
                    type: "POST",
                    url: "buscar.php",
                    data: cadena,
                    success: function (r){
                        //console.log(r);
                        if(r == 1){
                            self.location = "servicio_clasificacion.php";
                            alert('Se guardó correctamente');
                        }else{
                            alert('No se pudo almacenar la información');
                        }
                    }
                });
            }
        }
        function opcion(){
            if($('#select_opcion').val() == 0){
                $('#div_consulta_clasif').show();
                $('#div_registro_clasif').hide();
            }else if($('#select_opcion').val() == 1){
                $('#div_registro_clasif').show();
                $('#div_consulta_clasif').hide();
            }
        }
        function periodos(){
            var fecha = $('#fecha').val();
            band = 8;
            $.post("buscar.php", {fecha: fecha, band: band}, 
            function(mensaje) {
                //console.log(mensaje);
                split = mensaje.split("||");
                if(split[0] == 1){
                    $('#semana').val(split[1]);
                    if($('#numerotransaccion').val() != '0'){
                        document.getElementById('Nuevo_reg').disabled="";
                        document.getElementById('guardar').disabled="";
                    }
                }else{
                    $('#semana').val(split[1]);
                    alertify.error('El periodo está cerrado en esta fecha');
                    document.getElementById('Nuevo_reg').disabled="true";
                    document.getElementById('guardar').disabled="true";
                }
                //$('#materia_prod').html(mensaje).fadeIn();
            });
        }
        function load_actividad(){
            var patio = $('#patio').val()
            cadena = "patio=" + patio +
                     "&band=" + 15;
            $.ajax({
                type: "POST",
                url: "buscar.php",
                data: cadena,
                success: function (r){
                    //console.log(r);
                    $('#actividad').html(r).fadeIn()
                    load_clase_equipo()
                }
            });
        }
        function load_clase_equipo(){
            var proveedor = $('#proveedor').val()
            var actividad = $('#actividad').val()
            if(proveedor!='0' && actividad!='0'){
                cadena = "proveedor=" + proveedor +
                        "&actividad=" + actividad +
                         "&band=" + 16;
                $.ajax({
                    type: "POST",
                    url: "buscar.php",
                    data: cadena,
                    success: function (r){
                        //console.log(r);
                        $('#tipo_maquinaria').html(r).fadeIn()
                        llenar_sel_equipo()
                    }
                });
            }else{
                $('#tipo_maquinaria').html('<option value="0">No hay registros</option>').fadeIn()
                llenar_sel_equipo()
            }
        }
        function delete_tiquete_clasificacion(id_clasif,num_recibo){
            swal("    ¿Deseas eliminar el recibo # " + num_recibo + "? \n\n ¡Una vez eliminado, no podrás recuperar este registro!", {
                icon: "warning",
                buttons: {
                    cancel: "Cancelar",
                    catch: {
                        text: "Eliminar!",
                        value: "catch",
                    },
                    /*defeat: {
                        text: 'texto',
                    },*/
                },dangerMode: false,
            })
            .then((value) => {
                switch (value) {
             
                /*case "defeat":
                    var band = 9;
                break;*/
             
                case "catch":
                    var band = 17;
                    break;

                default:
                    //swal("Got away safely!");
                }
                
                cadena = "id_clasif=" + id_clasif +
                        "&num_recibo=" + num_recibo +
                        "&band=" + band;
                $.ajax({
                    type: "POST",
                    url: "buscar.php",
                    data: cadena,
                    success: function (r){
                        //console.log(r);
                        if (r == 1){
                            if(band == 17){
                                swal("Poof!", "Ha sido eliminado!", "success");
                            }
                            generar_informe();
                        }
                    }
                });
            });
        }
        function load_material_producido(){
            var material_alimentado = $('#material_alimentado').val()
            var grupo_material = $('#grupo_material').val()
            cadena = "material_alimentado=" + material_alimentado +
                    "&grupo_material=" + grupo_material +
                    "&band=" + 19;
            $.ajax({
                type: "POST",
                url: "buscar.php",
                data: cadena,
                success: function (r){
                    console.log(r);
                    $('#material_objetivo').html(r).fadeIn()
                    $('#materia_prod').html(r).fadeIn()
                }
            });
        }
    </script>

    <style type="text/css">
        .grow:hover
        {
        -webkit-transform: scale(1.1);
        -ms-transform: scale(1.1);
        transform: scale(1.1);
        }
    </style>
</head>
<body>
    <?php include 'Header.php'; ?><br>
    <center>
        <h3>Registro de Clasificación</h3>
        <div class="row">
            <div class="col-sm-5"></div>
            <div class="col-sm-2">
                <select id="select_opcion" class="form-control" onchange="opcion()">
                    <option selected="" disabled="">Seleccione</option>
                    <option value="0">Consultas</option>
                    <option value="1">Registro</option>
                    <option value="2">Correción</option>
                </select>
            </div>
            <div class="col-sm-5"></div>
    </center>
    <div id="div_consulta_clasif">
        <?php include('consulta_servicio_clasificacion.php'); ?>
    </div>
    <div class="container" id="div_registro_clasif">
        <br><br>
        <div class="row" id="div">
            <?php
            if ($_SESSION['permisoIngresar'] == 'CONSULTAS_OFICINA' || $_SESSION['permisoIngresar'] == 'ADMIN_CARGADORES' || $_SESSION['permisoIngresar'] == 'PATIO_CARGADORES'){
                $sql_empresa = "SELECT idProveedor, Alias FROM Proveedores WHERE Empresa=1 ORDER BY Alias";
                $resul_empresa = sqlsrv_query($conn,$sql_empresa);

                $sql_recibo= "SELECT ISNULL(MAX(num_recibo),0) AS numero FROM servicio_clasificacion";
                $resul_recibo = sqlsrv_query($conn,$sql_recibo);
                while($rows = sqlsrv_fetch_array($resul_recibo)) 
                {   $numero=$rows['numero']+1; }
                $slq_proveedores="SELECT Proveedores.RazonSocial, Proveedores.idProveedor FROM Equipos 
                    INNER JOIN detalle_equipos on Equipos.idEquipo=detalle_equipos.idEquipos
                    INNER JOIN Proveedores on Equipos.idPropietario=Proveedores.idProveedor
                    WHERE clase_equipo IN ('B91B4F78-EF10-4406-A941-2D1DF812C783','569D8AD0-A401-4AFE-BD52-A91974C7D2B0')
                    GROUP BY Proveedores.RazonSocial, Proveedores.idProveedor";
                $resul_proveedores = sqlsrv_query($conn,$slq_proveedores);                    

                $sql_equipos="SELECT idGrupo, Descripcion from EquiposGrupos ORDER BY Descripcion";
                $resul_equi_grupo = sqlsrv_query($conn,$sql_equipos);

                $sql_clasificacion="SELECT * FROM Productos ORDER BY Descripcion";
                $resul_clasificacion = sqlsrv_query($conn,$sql_clasificacion);

                $sql_actividad="SELECT idActividad, Descripcion from Actividades where idTipoActividad='00000000-0000-0000-0000-000000000007'";
                $resul_actividad = sqlsrv_query($conn,$sql_actividad);

                $sql_destino="SELECT * from vdestinogrupos";
                $resul_destino = sqlsrv_query($conn,$sql_destino);
                
            ?>
            <div class="col-sm-12" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; margin-bottom: 8px;">
                <input type="hidden" id="numerotransaccion" value="0">
                <center>
                    <input type="hidden" id="recibo" value="<?php echo $numero; ?>">
                    <label><h5><b>Recibo N° <?php echo $numero; ?></b></h5></label>
                </center>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-2"><h6>Fecha: </h6></div>
                            <div class="col-sm-4"><input type="date" id="fecha" class="form-control input-sm" onchange="periodos()" max="<?php echo date('Y-m-d'); ?>"></div>
                            <div class="col-sm-2" style="margin-left: 25px;"><h6>Semana: </h6></div>
                            <div class="col-sm-3"><input type="text" id="semana" class="form-control input-sm" readonly=""></div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-3"><h6>Empresa: </h6></div>
                            <div class="col-sm-8">
                                <select id="empresa" class="form-control">
                                    <option value="0">Seleccione</option>
                                    <?php  
                                    while($rows = sqlsrv_fetch_array($resul_empresa)){
                                        $id_empresa=$rows['idProveedor'];
                                        $nom_empresa=$rows['Alias'];
                                        ?><option value="<?php echo $id_empresa; ?>"><?php echo $nom_empresa ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-3"><h6>Proveedor: </h6></div>
                            <div class="col-sm-8">
                                <select id="proveedor" class="form-control" onchange="load_clase_equipo()">
                                    <option value="0">Seleccione</option>
                                    <?php  
                                    while($rows = sqlsrv_fetch_array($resul_proveedores)){
                                        $idProveedor=$rows['idProveedor'];
                                        $nom_proveedor=utf8_encode($rows['RazonSocial']);
                                        ?><option value="<?php echo $idProveedor; ?>"><?php echo $nom_proveedor ?></option>
                                    <?php }
                                    ?>
                                </select> 
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <input type="hidden" id="usuario" value="<?php echo $usuario; ?>">
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-3"><h6>Patio: </h6></div>
                            <div class="col-sm-8">
                                <select id="patio" class="form-control" onchange="load_actividad()">
                                    <option value="0">Seleccione</option>
                                    <?php  
                                    while($rows = sqlsrv_fetch_array($resul_destino)){   
                                        $iddestino=$rows['iddestino'];
                                        $nom_destino=utf8_encode($rows['Destino']);
                                        ?><option value="<?php echo $iddestino; ?>"><?php echo $nom_destino ?></option>
                                    <?php }
                                    ?>
                                </select> 
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-3"><h6>Activdad: </h6></div>
                             <div class="col-sm-8">
                                <select id="actividad" class="form-control" onchange="load_clase_equipo()">
                                    <option value="0">Seleccione</option>
                                </select> 
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-3"><h6>Clase Equipo: </h6></div>
                             <div class="col-sm-8">
                                <input type="hidden" name="input_tipo_maquina" id="input_tipo_maquina" value="<?php if(isset($_POST['tipo_maquinaria'])){if($_POST['tipo_maquinaria'] != '0'){  echo $_POST['tipo_maquinaria'];   }} ?>">
                                <select id="tipo_maquinaria" class="form-control" onchange="llenar_sel_equipo();">
                                    <option value="0">Seleccione</option>
                                </select> 
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3"><h6>Equipo: </h6></div>
                            <div class="col-sm-8">
                                <select id="Equipo" class="form-control">
                                    <option value="0">Seleccione</option>
                                </select> 
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3"><h6>Grupo material: </h6></div>
                            <div class="col-sm-8">
                                <select id="grupo_material" class="form-control" onchange="llenar_sel_material()">
                                    <option value="0">Seleccione</option>
                                    <?php  
                                    while($rows = sqlsrv_fetch_array($resul_clasificacion)){
                                        $idClasificacion1=$rows['idProducto'];
                                        $nom_clasiif1=utf8_encode($rows['Descripcion']);
                                        ?><option value="<?php echo $idClasificacion1; ?>"><?php echo $nom_clasiif1 ?></option>
                                    <?php }
                                    ?>
                                </select> 
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3"><h6>Material Alimen: </h6></div>
                            <div class="col-sm-8">
                                <select id="material_alimentado" class="form-control" onchange="load_material_producido()">
                                    <option value="0">Seleccione</option>
                                </select> 
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3"><h6>Material Objetivo: </h6></div>
                            <div class="col-sm-8">
                                <select id="material_objetivo" class="form-control">
                                    <option value="0">Seleccione</option>
                                </select> 
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3"><h6>Pila:</h6></div>
                            <div class="col-sm-8"><input type="text" id="pila" class="form-control"> </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3"><h6>Horas Equipo.:</h6></div>
                            <div class="col-sm-8"><input type="number" id="horas_alimen" class="form-control"></div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3"><h6>Toneladas Alimen.:</h6></div>
                            <div class="col-sm-8"><input type="number" id="tm_alimen" class="form-control"></div>
                        </div>                                                
                    </div>
                </div>
            </div>
            <br><br>
            <div class="col-sm-12" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; margin-bottom: 8px;">
                <div class="row">                 
                    <div class="col-sm-4">
                        <div class="row" style="margin-top: 15px;">                       
                            <div class="col-sm-5"><h6>Material Producido: </h6></div>
                            <div class="col-sm-7">
                                <select id="materia_prod" class="form-control">
                                    <option value="0" selected="" disabled=""> Seleccione</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-5"><h6>Toneladas :</h6></div>
                             <div class="col-sm-7"><input type="number" id="tm_producido" class="form-control"> </div>                     
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-12">
                                <center>
                                    <button id="Nuevo_reg" class="btn btn-primary"  onclick="material_producido()">Nuevo Producto 
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </center>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="col-sm-4" style="margin-top: 15px;">
                        <form name="formulario" id="formulario" action="" method="POST">
                            <center>
                                <input type="hidden" id="tm_acumulado1" value="0">
                                <label>Material Producido</label>
                                <div class="row">
                                    <div class="col-sm-5" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; background-color: #33C7A8"> <center><b>Material</b></center></div>
                                    <div class="col-sm-3" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; background-color: #33C7A8"> <center>Toneladas</b></center></div>
                                    <div class="col-sm-4" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; background-color: #33C7A8"> <center><b>Rendimiento</b></center></div>
                                </div>
                                <div id="div_material_prod"></div>
                                <div class="row">
                                    <div class="col-sm-5" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; background-color: #33C7A8"> <center></center></div>
                                    <div class="col-sm-3" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; background-color: #33C7A8"> <center><b id="tm_acumulado"></b></center></div>
                                    <div class="col-sm-4" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px; background-color: #33C7A8"> <center><b id="porcen_acumulado"></b></center></div>
                                </div>
                            </center>
                        </form>
                    </div>
                    <div class="col-sm-4" id="div_modificar_prod" style="border: 1px solid #A9AAA6; border-radius: 5px 5px 5px 5px;">
                        <div class="row" style="margin-top: 15px;">                       
                            <div class="col-sm-5"><h6>Material Producido: </h6></div>
                            <div class="col-sm-7">
                                <select id="materia_prod_mod" class="form-control">
                                    <option value="0"> Seleccione</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-5"><h6>Toneladas :</h6></div>
                             <div class="col-sm-7"><input type="number" id="tm_producido_mod" class="form-control"><input type="hidden" id="tm_producido_mod_1" readonly=""></div>                     
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-12">
                                <b style="color: red">Nota:</b>
                                <h6 style="color: red"> Para eliminar un material producido, se coloca <b>0</b> en las toneladas y dar click en Modificar Producto</h6>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-12">
                                <input type="hidden" id="id_detalle_mod">
                                <center>
                                    <button id="Nuevo_reg_mod" class="btn btn-warning"  onclick="modificar_producido()">Modificar Producto 
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </center>
                            </div>
                        </div>
                        <br>
                    </div>           
                </div>                
            </div>
        </div>
            <div class="row" style="margin-top: 5px;">
                <div class="col-sm-12">
                    <center>
                        <button id="guardar" class="btn btn-success" onclick="guardar_registro()" disabled="">Guardar registro <span class="glyphicon glyphicon-save"></span></button>
                    </center>
                </div>
            </div>
        </div>
    <?php }?>
    </div>
</body>
</html>

