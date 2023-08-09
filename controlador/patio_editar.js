function ver_info_tique(){
    combo = $('#combo_vista').val();
    if(combo == 0){
        $('#div_distribucion').hide();
        $('#div_info_tique').show();
        $('#combo_vista').val(1);
        $('#ver_info_tique').hide();
        $('#ver_distr_tique').show();
        info_tique();
    }else if(combo == 1){
        $('#div_distribucion').show();
        $('#div_info_tique').hide();
        $('#combo_vista').val(0);
        $('#ver_info_tique').show();
        $('#ver_distr_tique').hide();
    }
}

function info_tique(){
    idRegistro = $('#registroHorometro_edit').val();
    //console.log(idRegistro);
    band = 25;
    $.post("../modelo/consu_subactividades_patio_editar.php", {band: band, idRegistro: idRegistro}, 
        function(mensaje){
            //console.log(mensaje);
            mensaje1 = mensaje.split("||");
            $('#select_patio_edit').html(mensaje1[0]).fadeIn();
            $('#select_proveedor_edit').html(mensaje1[1]).fadeIn();
            $('#numero_remision_edit').val(mensaje1[2]);
            $('#select_maquinaria_edit').val(mensaje1[3]);
            $('#fecha_apertura_edit').val(mensaje1[4]);
            $('#fecha_cierre_edit').val(mensaje1[5]);
        });
}

function actualiza_info_tique(){
    band = 26;
    patio = $('#select_patio_edit').val();
    proveedor = $('#select_proveedor_edit').val();
    remision = $('#numero_remision_edit').val();
    fecha_apertura = $('#fecha_apertura_edit').val();
    fecha_cierre = $('#fecha_cierre_edit').val();
    idRegistro = $('#registroHorometro_edit').val();

    cadena = "patio=" + patio +
            "&band=" + band +
            "&proveedor=" + proveedor +
            "&remision=" + remision +
            "&fecha_apertura=" + fecha_apertura +
            "&fecha_cierre=" + fecha_cierre +
            "&idRegistro=" + idRegistro;
    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio_editar.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            if(r == 1){
                $('#div_distribucion').show();
                $('#div_info_tique').hide();
                $('#combo_vista').val(0);
                $('#ver_info_tique').show();
                $('#ver_distr_tique').hide();
            }
        }
    });
}

function cerrar_edit(){
    for(i=0;i<document.form_edit.elements.length; i++)
    {  if(document.form_edit.elements[i].type=="button")
        {   $('#'+document.form_edit.elements[i].value+'_edit').removeClass('btn-success'); 
            $('#'+document.form_edit.elements[i].value+'_edit').addClass('btn-default'); 
        }
    }
    $('#checked_edit').hide();
    $('#divDescuento_edit').hide();
    $('#divStandby_edit').hide();
    $('#volverAct_edit').hide();
    $('#volverDesc_edit').show();
    $('#FaltaAsignar_edit').show();
    $('#ActDetallado_edit').show();
    $('#divProducto_edit').hide();
    $('#bordeAsignados_edit').hide();
    $('#divAsignados_edit').hide();
    $('#borde_edit').hide();
    $('#div_subactividad_edit').hide();
    $('#bordePlantilla_edit').hide();
    $('#plantilla_cargue_edit').hide();
    $('#calculado_cargue_edit').hide();
    $('#plantilla_paladas_edit').hide();
    $('#plantilla_apila_entra_edit').hide();
    $('#calculado_edit').hide();
    $('#GrabarDatos_edit').hide();
    $('#GrabarDatos1_edit').hide();
    $('#GrabarDatos2_edit').hide();
    $('#ModificarDatos_edit').hide();
    $('#ModificarDatos1_edit').hide();
    $('#ModificarDatos2_edit').hide();
    $('#div_equipos_edit').hide();
    $('#div_equipos1_edit').hide();
    $('#div_clasif_edit').hide();
    $('#volverStandby_edit').hide();
    $('#div_pila_edit').hide();
    self.location = "inicio_patio.php";
}
$(document).ready(function () {
    $('#div_red_edit').hide();
    $('#div_info_tique').hide();
    $('#ver_distr_tique').hide();
    //
    $('#DestinoLabel_edit').hide();
    $('#DestinoSelect_edit').hide();
    $('#div_pila_edit').hide();
    $('#checked_edit').hide();
    $('#divDescuento_edit').hide();
    $('#divStandby_edit').hide();
    $('#volverStandby_edit').hide();
    $('#volverAct_edit').hide();
    $('#volverDesc_edit').show();
    $('#FaltaAsignar_edit').show();
    $('#ActDetallado_edit').show();
    $('#divProducto_edit').hide();
    $('#bordeAsignados_edit').hide();
    $('#divAsignados_edit').hide();
    $('#divAsignados1_edit').hide();
    $('#borde_edit').hide();
    $('#div_subactividad_edit').hide();
    $('#bordePlantilla_edit').hide();
    $('#plantilla_cargue_edit').hide();
    $('#calculado_cargue_edit').hide();
    $('#plantilla_paladas_edit').hide();
    $('#plantilla_apila_entra_edit').hide();
    $('#calculado_edit').hide();
    $('#GrabarDatos_edit').hide();
    $('#GrabarDatos1_edit').hide();
    $('#GrabarDatos2_edit').hide();
    $('#ModificarDatos_edit').hide();
    $('#ModificarDatos1_edit').hide();
    $('#ModificarDatos2_edit').hide();
    $('#div_equipos_edit').hide();
    $('#div_equipos1_edit').hide();
    $('#div_clasif_edit').hide();
})

function GuardarObservaciones_edit(){
    if ($('#observacionesTiquete_edit').val() != ""){
        tiquete = $('#registroHorometro_edit').val();
        observaciones = $('#observacionesTiquete_edit').val();
        band = 12;

        cadena = "tiquete=" + tiquete +
                "&band=" + band +
                "&observaciones=" + observaciones;
        $.ajax({
            type: "POST",
            url: "../modelo/consu_subactividades_patio_editar.php",
            data: cadena,
            success: function (r){
                //console.log(r);
                if(r == 1){
                    FaltaAsignar_edit();
                    //alert('Se registró correctemente los comentarios');
                    //self.location="inicio_patio.php";
                }else{
                    alert('No se pudo registrar')
                }
            }
        });
    }/*else{
        alert('Escriba observaciones');
    }*/
}
function datos_edit(id_registro){
    registro = id_registro;
    $('#registroHorometro_edit').val(registro);
    FaltaAsignar_edit();
}

function actividad_edit(val){
    //alert(val);
    $('#DestinoLabel_edit').hide();
    $('#DestinoSelect_edit').hide();
    $('#div_red_edit').show();
    $('#n_vehiculos_edit').val('');
    $('#TempXvehiculo_edit').val('');
    $('#n_paladas_edit').val('');
    $('#TempXpalada_edit').val('');
    $('#TM_Palada_edit').val('');
    $('#TempReEst_edit').val('');
    $('#TempMaquinaEstVehiculo_edit').val('');
    $('#TempMaquinaEst_edit').val('');
    $('#TotalTM_edit').val('');
    $('#TotalTM_cargue_edit').val('');
    $('#idDistribucion_edit').val('');
    $('#plantilla_paladas_edit').hide();
    $('#plantilla_cargue_edit').hide();
    $('#plantilla_apila_entra_edit').hide();
    $('#time_est_edit').val('');
    $('#totalize_tm_edit').val('');
    $('#calculado_edit').hide();
    $('#calculado_cargue_edit').hide();
    $('#GrabarDatos_edit').hide();
    $('#GrabarDatos1_edit').hide();
    $('#ModificarDatos_edit').hide();
    $('#ModificarDatos1_edit').hide();
    $('#ModificarDatos2_edit').hide();
    $('#div_equipos_edit').hide();
    $('#div_equipos1_edit').hide();
    $('#div_clasif_edit').hide();
    for(i=0;i<document.form_edit.elements.length; i++)
    {  if(document.form_edit.elements[i].type=="button")
        {   //document.form.elements[i].classList.remove('btn-success');
            //document.form.elements[i].classList.add('btn-default');
            $('#'+document.form.elements[i].value+'_edit').removeClass('btn-success'); 
            $('#'+document.form.elements[i].value+'_edit').addClass('btn-default');
            $('#bordePlantilla_edit').hide();
            $('#plantilla_paladas_edit').hide();
            $('#plantilla_cargue_edit').hide();
            $('#plantilla_apila_entra_edit').hide();
            $('#div_equipos_edit').hide();
            $('#div_equipos1_edit').hide();
            //console.log(document.form.elements[i].value);
        }
    } 
    $('#sub_actividad_edit').val("");
    var actividad2 = $('#'+val+'_edit').val();
    $('#'+val+'_edit').removeClass('btn-default');
    $('#'+val+'_edit').addClass('btn-success');
    idRegistro2 = $('#registroHorometro_edit').val();
    $('#actividad_edit').val(actividad2);
    band = 2;
    if(actividad2 == '404D205C-7D03-4E84-9AC1-03EE5A8D83F9' || actividad2 == '6A173460-B5ED-4CB6-B73A-ECDDF5BE3734' || actividad2 == '0FFB9BAF-3DB6-47B2-8E7F-272D2C118AD0'){
        $('#div_pila_edit').show();
    }else{
        $('#div_pila_edit').hide();
    }
    cadena = "actividad=" + actividad2 +
            "&idRegistro=" + idRegistro2 +
            "&band=" + band;
    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio_editar.php",
        data: cadena,
        success: function (r) {
            let bodySubActividad = '';
            //console.log(r);
            d = r.split("||");
            if (d[2] == 0){
                
                bodySubActividad = '<div clas="col-sm-8"></div><div clas="col-sm-3"><center><h5>--- Ya se asociaron todas la SubActividades ---</h5></center></div>';
                $('#bordePlantilla_edit').hide();
                $('#plantilla_paladas_edit').hide();
                $('#plantilla_cargue_edit').hide();
                $('#plantilla_apila_entra_edit').hide();
                $('#div_equipos_edit').hide();
                $('#div_equipos1_edit').hide();
                $('#div_clasif_edit').hide();
                $('#frm_edit').html(bodySubActividad);
                document.getElementById('TM_alimentadas_edit').innerHTML=d[0];
                document.getElementById('TM_apiladas_edit').innerHTML=d[1];
                $('#TM_alimentadas1_edit').val(d[0]);
                $('#TM_apiladas1_edit').val(d[1]);
                asignados_edit();
            }else{
                const datosActividad = JSON.parse(d[2]);
                datosActividad.forEach(act => {
                  bodySubActividad += `
                  <div class="col-sm-12">
                  <div class="row">
                  <div class="col-sm-3"></div>
                  <div class="col-sm-6">
                  <input type="hidden" id="${act.idSubactividad}1_edit" value="${act.Descripcion}">
                    <button type="button" id="${act.idSubactividad}_edit" style="margin-bottom: 5px;" class="btn btn-default btn-sm" value="${act.idSubactividad}" onclick='seleccionado_edit("${act.idSubactividad}")'>${act.Descripcion}</button>
                  </div>
                  </div>
                  </div>`
                });
                $('#frm_edit').html(bodySubActividad);
                document.getElementById('TM_alimentadas_edit').innerHTML=d[0];
                document.getElementById('TM_apiladas_edit').innerHTML=d[1];
                $('#TM_alimentadas1_edit').val(d[0]);
                $('#TM_apiladas1_edit').val(d[1]);
                asignados_edit();
            }
        }
    });
    if($('#'+val+'_edit').val() == $('#encrypt_edit').val()){
        $('#divProducto_edit').show();
        $('#ProductoLabel_edit').show();
        $('#ProductoSelect_edit').show();
        $('#borde_edit').show();
        $('#div_subactividad_edit').show();
        //console.log($('#encrypt').val());
    }else if ($('#'+val+'_edit').val() != $('#encrypt1_edit').val()/* || $('#'+val).val() == '0ADD1931-4C6A-4258-B515-8F016516606C'*/){
        $('#divProducto_edit').show();
        $('#ProductoLabel_edit').show();
        $('#ProductoSelect_edit').show();
        if($('#'+val+'_edit').val() == '7E51DEAF-1782-46A1-BE54-4418C8F1D80B'){
            $('#borde_edit').show();
            $('#div_subactividad_edit').show();
        }else if($('#'+val+'_edit').val() == 'D082C2F1-438D-4421-AE55-A99311435AF1'){
            $('#borde_edit').show();
            $('#div_subactividad_edit').show();
            $('#divProducto_edit').hide();
        }else if($('#'+val+'_edit').val() == '26BDE387-3522-42CB-9A29-D51385A041C5'){
            $('#DestinoLabel_edit').show();
            $('#DestinoSelect_edit').show();
        }else{
            $('#borde_edit').hide();
            $('#div_subactividad_edit').hide();
        }
        //console.log($('#'+val).val());
    }else{
        $('#divProducto_edit').hide();
        $('#ProductoLabel_edit').hide();
        $('#ProductoSelect_edit').hide();
        $('#borde_edit').show();
        $('#div_subactividad_edit').show();
        //$('#YaAsignados').show();
    }
}

function asignados_edit(){
    idRegistro1 = $('#registroHorometro_edit').val();
    actividad1 = $('#actividad_edit').val();
    if(actividad1 == '404D205C-7D03-4E84-9AC1-03EE5A8D83F9' || actividad1 == '6A173460-B5ED-4CB6-B73A-ECDDF5BE3734' || actividad1 == '0FFB9BAF-3DB6-47B2-8E7F-272D2C118AD0'){
        band1 = 19;
    }else{
        band1 = 5;
    }
    cadena = "idRegistro=" + idRegistro1 +
            "&actividad=" + actividad1 +
            "&band=" + band1;

    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio_editar.php",
        data: cadena,
        success: function (r) {
            //console.log(r);
            d = r.split("||");
            //console.log(d[0]);
            if(d[0] == 1){
                if (d[1] != 0){
                    const datosAsignados = JSON.parse(d[1]);
                    let bodyAsignados = '';
                    datosAsignados.forEach(asign => {
                      bodyAsignados += `
                      <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-6">
                              <input type="hidden" id="${asign.idDistribucion}2_edit" value="${asign.sub_actividad}">
                              <input type="hidden" id="${asign.idDistribucion}1_edit" value="${asign.idSubactividad}">
                              <div class="col-sm-6">
                                <button type="button" style="margin-bottom: 5px;" id="${asign.idDistribucion}_edit" class="btn btn-default btn-sm" value="${asign.idDistribucion}" onclick='seleccionado_asignar_edit("${asign.idDistribucion}")'>${asign.Descripcion} -- ${asign.sub_actividad}</button>
                              </div>
                            </div>
                        </div>
                      </div>`
                    });
                    $('#AsignadosAgg_edit').html(bodyAsignados);
                    $('#divAsignados1_edit').hide();
                    $('#divAsignados_edit').show();
                    $('#bordeAsignados_edit').show();
                    $('#YaAsignados_edit').show();
                }else{
                    $('#divAsignados1_edit').hide();
                    $('#divAsignados_edit').hide();
                    $('#bordeAsignados_edit').hide();
                    $('#divAsignados_edit').hide();
                }
            }else if(d[0] == 2){
                if (d[1] != 0 && d[2] != 0){
                    const datosAsignados = JSON.parse(d[1]);
                    let bodyAsignados = '';
                    datosAsignados.forEach(asign => {
                      bodyAsignados += `
                        <div class="row center-block">
                              <input type="hidden" id="${asign.idDistribucion}2_edit" value="${asign.sub_actividad}">
                              <input type="hidden" id="${asign.idDistribucion}1_edit" value="${asign.idSubactividad}">
                            <div class="col-sm-6">
                                <button type="button" style="margin-bottom: 5px;" id="${asign.idDistribucion}_edit" class="btn btn-default btn-sm" value="${asign.idDistribucion}" onclick='seleccionado_asignar_edit("${asign.idDistribucion}")'>${asign.Descripcion} <br> ${asign.equipo}</button>
                            </div>
                        </div>`
                    });
                    $('#AsignadosAgg1_edit').html(bodyAsignados);
                    const datosAsignados1 = JSON.parse(d[2]);
                    let bodyAsignados1 = '';
                    datosAsignados1.forEach(asign1 => {
                      bodyAsignados1 += `
                        <div class="row center-block">
                              <input type="hidden" id="${asign1.idDistribucion}2_edit" value="${asign1.sub_actividad}">
                              <input type="hidden" id="${asign1.idDistribucion}1_edit" value="${asign1.idSubactividad}">
                            <div class="col-sm-6">
                                <button type="button" style="margin-bottom: 5px;" id="${asign1.idDistribucion}_edit" class="btn btn-default btn-sm" value="${asign1.idDistribucion}" onclick='seleccionado_asignar_edit("${asign1.idDistribucion}")'>${asign1.Descripcion} <br> ${asign1.equipo}</button>
                            </div>
                        </div>`
                    });
                    $('#AsignadosAgg2_edit').html(bodyAsignados1);
                    $('#divAsignados1_edit').show();
                    $('#divAsignados_edit').hide();
                    $('#bordeAsignados_edit').show();
                    $('#YaAsignados_edit').show();
                }else{
                    $('#divAsignados1_edit').hide();
                    $('#divAsignados_edit').hide();
                }
            }
        }
    });
}

function Producto_edit(){
    if($('#actividad_edit').val() != '3F001906-CFF3-437F-A4C5-67CDADDFD904'){
        if($('#producto_edit').val() != '00000000-0000-0000-0000-000000000000'){
            $('#borde_edit').show();
            $('#div_subactividad_edit').show();
        }else{
            $('#borde_edit').hide();
            //$('#div_subactividad_edit').hide();
        }
    }else{
        $('#borde_edit').show();
        $('#div_subactividad_edit').show();
    }
    var idRegistro5 = $('#registroHorometro_edit').val();
    var producto = $('#producto_edit').val();
    var band5 = 20;

    cadena = "idRegistro=" + idRegistro5 +
            "&producto=" + producto +
            "&band=" + band5;
    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio_editar.php",
        data: cadena,
        success: function (r) {
            //console.log(r);
            $('#TM_Palada_edit').val(r);
        }
    });
    $('#producto_sec_edit').val($('#producto_edit').val());
    $('#n_vehiculos_edit').val('');
    $('#TempXvehiculo_edit').val('');
    $('#n_paladas_edit').val('');
    $('#TempXpalada_edit').val('');
    $('#TempReEst_edit').val('');
    $('#TempMaquinaEstVehiculo_edit').val('');
    $('#TempMaquinaEst_edit').val('');
    $('#TotalTM_edit').val('');
    $('#TotalTM_cargue_edit').val('');
    $('#idDistribucion_edit').val('');
    //$('#calculado_edit').hide();
    $('#calculado_cargue_edit').hide();
    //$('#bordePlantilla_edit').hide();
    //$('#plantilla_paladas_edit').hide();
    //$('#plantilla_cargue_edit').hide();
    //$('#plantilla_apila_entra_edit').hide();
    $('#time_est_edit').val('');
    $('#totalize_tm_edit').val('');
    $('#GrabarDatos_edit').hide();
    $('#GrabarDatos1_edit').hide();
    $('#ModificarDatos_edit').hide();
    $('#ModificarDatos1_edit').hide();
    $('#ModificarDatos2_edit').hide();
    //$('#div_equipos_edit').hide();
    //$('#div_equipos1_edit').hide();
    //$('#div_clasif_edit').hide();
}

function seleccionado_edit(val){
    $('#n_vehiculos_edit').val('');
    $('#TempXvehiculo_edit').val('');
    $('#n_paladas_edit').val('');
    $('#TempXpalada_edit').val('');
    $('#tempo_clasif_edit').val('');
    $('#TempReEst_edit').val('');
    $('#TempMaquinaEstVehiculo_edit').val('');
    $('#TempMaquinaEst_edit').val('');
    $('#TotalTM_edit').val('');
    $('#TotalTM_cargue_edit').val('');
    $('#idDistribucion').val('');
    $('#time_est_edit').val('');
    $('#totalize_tm_edit').val('');
    $('#calculado_edit').hide();
    $('#calculado_cargue_edit').hide();
    for(i=0;i<document.frm_edit.elements.length; i++)
    {  if(document.frm_edit.elements[i].type=="button")
        {   $('#'+document.frm_edit.elements[i].value+'_edit').removeClass('btn-success'); 
            $('#'+document.frm_edit.elements[i].value+'_edit').addClass('btn-default'); 
        }
    }
    if($('#actividad_edit').val() == '3F001906-CFF3-437F-A4C5-67CDADDFD904'){
        document.getElementById('bordePlantilla_edit').innerHTML='<center><h4>Apilamiento Entradas</h4></center>';
        $('#bordePlantilla_edit').show();
        $('#plantilla_cargue_edit').hide();
        $('#plantilla_paladas_edit').hide();
        $('#plantilla_apila_entra_edit').show();
        if ($('#'+val+'1_edit').val() == 'MVTO. X CALIDAD' || $('#'+val+'1_edit').val() == 'OFICIOS VARIOS'){
            $('#totalize_tm_edit').val(0)
            $('#totalize_tm_edit').hide()
            $('#totalize_tm_title_edit').hide()
        }else{
            $('#totalize_tm_edit').show()
            $('#totalize_tm_title_edit').show()
        }
        $('#btn_calcularData1_edit').hide();
        $('#btn_calcularData_edit').hide();
        $('#btn_calcularModificar_edit').hide();
        $('#btn_calcularModificar1_edit').hide();
        $('#GrabarDatos_edit').hide();
        $('#GrabarDatos1_edit').hide();
        //if ($('#ValoresAsignados_edit').val() == 1){
            $('#GrabarDatos2_edit').show();
        /*}else{
            $('#GrabarDatos2_edit').hide();
        }*/
        $('#ModificarDatos_edit').hide();
        $('#ModificarDatos1_edit').hide();
        $('#ModificarDatos2_edit').hide();
        $('#div_equipos_edit').hide();
        $('#div_equipos1_edit').hide();
        $('#div_clasif_edit').hide();
    }else if (($('#'+val+'1_edit').val() == 'ALIMENTAR') || ($('#'+val+'1_edit').val() == 'APILAR')){
        $('#div_equipos_edit').show();
        $('#div_equipos1_edit').show();
        if (($('#'+val+'1_edit').val() == 'ALIMENTAR')){
            $('#div_clasif_edit').show();
        }else{
            $('#div_clasif_edit').hide();
        }
        document.getElementById('bordePlantilla_edit').innerHTML='<center><h4>Distribución de tiempos por clasificación</h4></center>';
        $('#bordePlantilla_edit').show();
        $('#plantilla_cargue_edit').hide();
        $('#plantilla_apila_entra_edit').hide();
        $('#plantilla_paladas_edit').show();
        $('#calculado_edit').show();
        $('#btn_calcularData1_edit').hide();
        $('#btn_calcularData_edit').show();
        $('#btn_calcularModificar_edit').hide();
        $('#btn_calcularModificar1_edit').hide();
        $('#GrabarDatos_edit').hide();
        //$('#GrabarDatos1').hide();
        //if ($('#ValoresAsignados_edit').val() == 1){
            $('#GrabarDatos1_edit').show();
        /*}else{
            $('#GrabarDatos1_edit').hide();
        }*/
        $('#GrabarDatos2_edit').hide();
        $('#ModificarDatos_edit').hide();
        $('#ModificarDatos1_edit').hide();
        $('#ModificarDatos2_edit').hide();
    }else if (($('#'+val+'1_edit').val() == 'CARGAR DESPACHO')){
        $('#div_equipos_edit').hide();
        $('#div_equipos1_edit').hide();
        document.getElementById('bordePlantilla_edit').innerHTML='<center><h4>Distribución de tiempos por cargue</center></h4>';
        $('#bordePlantilla_edit').show();
        $('#plantilla_paladas_edit').hide();
        $('#plantilla_cargue_edit').hide();
        $('#plantilla_apila_entra_edit').show();
        $('#btn_calcularData1_edit').show();
        $('#totalize_tm_title_edit').show();
        $('#totalize_tm_edit').show();
        $('#btn_calcularData_edit').hide();
        $('#btn_calcularModificar_edit').hide();
        $('#btn_calcularModificar1_edit').hide();
        $('#GrabarDatos_edit').hide();
        $('#GrabarDatos1_edit').hide();
        //if ($('#ValoresAsignados_edit').val() == 1){
            $('#GrabarDatos2_edit').show();
        /*}else{
            $('#GrabarDatos2_edit').hide();
        }*/
        $('#ModificarDatos_edit').hide();
        $('#ModificarDatos1_edit').hide();
        $('#ModificarDatos2_edit').hide();
    }else if($('#'+val+'1_edit').val() == 'STANDBY'){
        document.getElementById('bordePlantilla_edit').innerHTML='<center><h4>Tiempos de Standbay</h4></center>';
        $('#bordePlantilla_edit').show();
        $('#plantilla_cargue_edit').hide();
        $('#plantilla_paladas_edit').hide();
        $('#plantilla_apila_entra_edit').show();
        $('#totalize_tm_edit').val(0)
        $('#totalize_tm_edit').hide()
        $('#totalize_tm_title_edit').hide()
        $('#btn_calcularData1_edit').hide();
        $('#btn_calcularData_edit').hide();
        $('#btn_calcularModificar_edit').hide();
        $('#btn_calcularModificar1_edit').hide();
        $('#GrabarDatos_edit').hide();
        $('#GrabarDatos1_edit').hide();
        //if ($('#ValoresAsignados_edit').val() == 1){
            $('#GrabarDatos2_edit').show();
        /*}else{
            $('#GrabarDatos2_edit').hide();
        }*/
        $('#ModificarDatos_edit').hide();
        $('#ModificarDatos1_edit').hide();
        $('#ModificarDatos2_edit').hide();
        $('#div_equipos_edit').hide();
        $('#div_equipos1_edit').hide();
        $('#div_clasif_edit').hide();
    }else if($('#'+val+'1_edit').val() == 'TRABAJOS MCOS.'){
        document.getElementById('bordePlantilla_edit').innerHTML='<center><h4>VARIOS</h4></center>';
        $('#bordePlantilla_edit').show();
        $('#plantilla_cargue_edit').hide();
        $('#plantilla_paladas_edit').hide();
        $('#plantilla_apila_entra_edit').show();
        //if ( || $('#'+val+'1').val() == 'OFICIOS VARIOS'){
            $('#totalize_tm_edit').val(0)
            $('#totalize_tm_edit').hide()
            $('#totalize_tm_title_edit').hide()
        /*}else{
            $('#totalize_tm').show()
            $('#totalize_tm_title').show()
        }*/
        $('#btn_calcularData1_edit').hide();
        $('#btn_calcularData_edit').hide();
        $('#btn_calcularModificar_edit').hide();
        $('#btn_calcularModificar1_edit').hide();
        $('#GrabarDatos_edit').hide();
        $('#GrabarDatos1_edit').hide();
        //if ($('#ValoresAsignados_edit').val() == 1){
            $('#GrabarDatos2_edit').show();
        /*}else{
            $('#GrabarDatos2_edit').hide();
        }*/
        $('#ModificarDatos_edit').hide();
        $('#ModificarDatos1_edit').hide();
        $('#ModificarDatos2_edit').hide();
        $('#div_equipos_edit').hide();
        $('#div_equipos1_edit').hide();
        $('#div_clasif_edit').hide();
    }
    var a = $('#'+val+'_edit').val();
    $('#sub_actividad_edit').val(a);
    $('#'+val+'_edit').removeClass('btn-default');
    $('#'+val+'_edit').addClass('btn-success');
}

function calcularHoras_edit(r){
    //$('#ModificarDatos').hide();
    if (r == 'palada'){
        //alert('bbb');
        var SubActividad = $('#sub_actividad_edit').val();
        if (($('#n_paladas_edit').val() != "") /*&& ($('#TempXpalada').val() != "")*/ && ($('#TM_Palada_edit').val() != "")){
            N_paladas = $('#n_paladas_edit').val();
            //TempEst = $('#TempXpalada').val();
            TM = $('#TM_Palada_edit').val();
            TotalTM = N_paladas*TM;
            var roundTM = Math.round(TotalTM* 100) / 100;
            var TM_alimentadas = $('#TM_alimentadas1_edit').val();
            var TM_apiladas = $('#TM_apiladas1_edit').val();
            var suma = (parseInt(TotalTM) + parseInt(TM_apiladas));
            var resta = (parseInt(suma) - parseInt(TM_alimentadas));
            $('#TotalTM_edit').val(roundTM);
            //$('#calculado').show();
            //if ($('#ValoresAsignados_edit').val() == 1){
                //$('#GrabarDatos1').show();
            /*}else{
                $('#GrabarDatos1_edit').hide();
            }*/
            //$('#GrabarDatos1').show();
        }/*else{
            $('#GrabarDatos1').hide();
        }*/
    }else if(r == 'tonelada'){
        if (($('#TotalTM_edit').val() != "") /*&& ($('#TempXpalada').val() != "")*/ && ($('#TM_Palada_edit').val() != "")){
            TotalTM = $('#TotalTM_edit').val();
            //TempEst = $('#TempXpalada').val();
            TM = $('#TM_Palada_edit').val();
            N_paladas = TotalTM/TM;
            var roundPalada = Math.round(N_paladas* 1) / 1;
            var TM_alimentadas = $('#TM_alimentadas1_edit').val();
            var TM_apiladas = $('#TM_apiladas1_edit').val();
            var suma = (parseInt(TotalTM) + parseInt(TM_apiladas));
            var resta = (parseInt(suma) - parseInt(TM_alimentadas));
            $('#n_paladas_edit').val(roundPalada);
            //$('#calculado').show();
            //if ($('#ValoresAsignados_edit').val() == 1){
                //$('#GrabarDatos1').show();
            /*}else{
                $('#GrabarDatos1_edit').hide();
            }*/
            //$('#GrabarDatos1').show();
        }/*else{
            $('#GrabarDatos1').hide();
        }*/
    }
}

function calcularHorasMod_edit(r){
    $('#GrabarDatos_edit').hide();
    $('#GrabarDatos1_edit').hide();
    if (r == 'Cargue'){
        if (($('#n_vehiculos_edit').val() != "")&& ($('#TempXvehiculo_edit').val() != "")){
            n_vehiculos = $('#n_vehiculos_edit').val();
            TempEst = $('#TempXvehiculo_edit').val();
            var HoraMaquina = (n_vehiculos*TempEst)/60;
            var round = Math.round(HoraMaquina* 10) / 10;
            var MinReloj = (n_vehiculos*TempEst);
            let horas = Math.floor(MinReloj / 60),
            minutosSobrantes = MinReloj % 60;
            var roundReloj = Math.round(minutosSobrantes* 1) / 1;
            Reloj = horas+":"+roundReloj;
            $('#TempReEstVehiculo_edit').val(Reloj);
            $('#TempMaquinaEstVehiculo_edit').val(round);
            $('#calculado_cargue_edit').show();
            $('#ModificarDatos_edit').hide();
            $('#ModificarDatos2_edit').hide();
            //if ($('#ValoresAsignados_edit').val() == 1){
                $('#ModificarDatos1_edit').show();
            /*}else{
                $('#ModificarDatos1_edit').hide();
            }*/
        }else{
            alert('Ingrese valores para calcular.');
        }
    }else if (r == 'Clasificar'){
        //console.log(r);
        if (($('#n_paladas_edit').val() != "") /*&& ($('#TempXpalada').val() != "")*/&& ($('#TM_Palada_edit').val() != "")){
            N_paladas = $('#n_paladas_edit').val();
            TempEst = $('#TempXpalada_edit').val();
            TM = $('#TM_Palada_edit').val();
            TotalTM = N_paladas*TM;
            var roundTM = Math.round(TotalTM* 100) / 100;
            /*var HoraMaquina = (N_paladas*TempEst)/3600;
            var SegReloj = (N_paladas*TempEst);
            var MinReloj = SegReloj/60;
            let horas = Math.floor(MinReloj / 60),
            minutosSobrantes = MinReloj % 60;
            var roundReloj = Math.round(minutosSobrantes* 1) / 1;
            Reloj = horas+":"+roundReloj;
            var round = Math.round(HoraMaquina* 10) / 10;
            $('#TempReEst').val(Reloj);
            $('#TempMaquinaEst').val(round);*/
            $('#TotalTM_edit').val(roundTM);
            $('#calculado_edit').show();
            //if ($('#ValoresAsignados_edit').val() == 1){
                $('#ModificarDatos_edit').show();
            /*}else{
                $('#ModificarDatos_edit').hide();
            }*/
            $('#ModificarDatos1_edit').hide();
            $('#ModificarDatos2_edit').hide();
        }else{
            alert('Ingrese valores para calcular.');
        }
    }
}

function GrabarDatos_edit(r){
    if (r == 'Clasificar'){
        idRegistro3 = $('#registroHorometro_edit').val();
        producto3 = $('#producto_sec_edit').val();
        actividad3 = $('#actividad_edit').val();
        sub_actividad3 = $('#sub_actividad_edit').val();
        N_paladas3 = $('#n_paladas_edit').val();
        TM_Palada3 = $('#TM_Palada_edit').val();
        if($('#tempo_clasif_edit').val() != ""){
            tempo_clasif = $('#tempo_clasif_edit').val();
        }else{
            tempo_clasif = 0;
        }
        /*TempXpalada3 = $('#TempXpalada').val();
        TempReEst3 = $('#TempReEst').val();*/
        TempMaquinaEst3 = $('#TempMaquinaEst_edit').val();
        TotalTM = $('#TotalTM_edit').val();
        var TM_alimentadas = $('#TM_alimentadas1_edit').val();
        var TM_apiladas = $('#TM_apiladas1_edit').val();
        pila = $('#pila_edit').val();
        equipo = $('#equipo_edit').val();
        zona = $('#zona_acopio_edit').val();
        band3 = 3;

        cadena = "idRegistro=" + idRegistro3 +
                "&producto=" + producto3 +
                "&actividad=" + actividad3 +
                "&sub_actividad=" + sub_actividad3 +
                "&n_paladas=" + N_paladas3 +
                "&TM_Palada=" + TM_Palada3 +
                "&TM_alimentadas=" + TM_alimentadas +
                "&TM_apiladas=" + TM_apiladas +
                "&TempMaquinaEst=" + TempMaquinaEst3 +
                "&TotalTM=" + TotalTM +
                "&pila=" + pila +
                "&equipo=" + equipo +
                "&zona=" + zona +
                "&tempo_clasif=" + tempo_clasif +
                "&band=" + band3;

        v = 0;
        texto = '';
        if(pila == '0'){
            texto+='- Seleccione una pila \n';
            v = 1;
        }
        if(producto3 == '00000000-0000-0000-0000-000000000000'){
            texto+='- Seleccione un producto \n';   
            v = 1;
        }
        if(equipo == '0'){
            texto+='- Seleccione un equipo de clasificación \n';
            v = 1;
        }
        if(zona == '0'){
            texto+='- Seleccione una zona \n';
            v = 1;
        }
        if(sub_actividad3 != '326D6364-121D-41B7-A188-9EE90B1E5F8B' && sub_actividad3 != '36D45DE3-9E29-47C4-9B18-960FF8C485CE' && sub_actividad3 != 'B6E8C730-CCB4-45E5-80A8-4DA9EF894B56'){
            if(TempMaquinaEst3 == '0' || TempMaquinaEst3 == ''){
                texto+='- Defina un tiempo de trabajo \n';
                v = 1;
            }
        }

    }else if (r == 'Cargue'){
        idRegistro3 = $('#registroHorometro_edit').val();
        producto3 = $('#producto_sec_edit').val();
        actividad3 = $('#actividad_edit').val();
        sub_actividad3 = $('#sub_actividad_edit').val();
        N_vehiculos3 = $('#n_vehiculos_edit').val();
        TempXvehiculo3 = $('#TempXvehiculo_edit').val();
        TempReEstVehiculo3 = $('#TempReEstVehiculo_edit').val();
        TempMaquinaEstVehiculo3 = $('#TempMaquinaEstVehiculo_edit').val();
        TotalTM = $('#TotalTM_cargue_edit').val();
        band3 = 4;

        cadena = "idRegistro=" + idRegistro3 +
                "&producto=" + producto3 +
                "&actividad=" + actividad3 +
                "&sub_actividad=" + sub_actividad3 +
                "&n_vehiculos=" + N_vehiculos3 +
                "&TempXvehiculo=" + TempXvehiculo3 +
                "&TempReEstVehiculo=" + TempReEstVehiculo3 +
                "&TempMaquinaEstVehiculo=" + TempMaquinaEstVehiculo3 +
                "&TotalTM=" + TotalTM +
                "&band=" + band3;
        if(TempMaquinaEstVehiculo3 != '0'){
            v = 1;
        }else{
            v = 0;
        }

    }else if(r == 'Apilar_entra'){
        idRegistro3 = $('#registroHorometro_edit').val();
        actividad3 = $('#actividad_edit').val();
        sub_actividad3 = $('#sub_actividad_edit').val();
        if(sub_actividad3 == 'A70B0905-E285-4DE6-B84D-7F2727137F05' || sub_actividad3 == '2BFB8ECB-6E93-4437-9440-DFFE24845013'){
            producto3 = '';
        }else{
            producto3 = $('#producto_sec_edit').val();    
        }
        destino = $('#destino_edit').val();
        time_est = $('#time_est_edit').val();
        totalize_tm = $('#totalize_tm_edit').val();
        band3 = 14;
        estado = 0;

        cadena = "idRegistro=" + idRegistro3 +
                "&producto=" + producto3 +
                "&actividad=" + actividad3 +
                "&sub_actividad=" + sub_actividad3 +
                "&destino=" + destino +
                "&time_est=" + time_est +
                "&totalize_tm=" + totalize_tm +
                "&band=" + band3 +
                "&estado=" + estado;

        texto = '';
        v = 0;
        if(sub_actividad3 == 'FD78D664-C2B4-4994-A72A-4D55A62FB462'){
            if(producto3 == '00000000-0000-0000-0000-000000000000'){
                texto+='- Seleccione un producto \n';
                v = 1;
            }
            if(destino == '00000000-0000-0000-0000-000000000000'){
                texto+='- Seleccione un destino \n';
                v = 1;
            }
        }else if(sub_actividad3 != 'A70B0905-E285-4DE6-B84D-7F2727137F05' && sub_actividad3 != '2BFB8ECB-6E93-4437-9440-DFFE24845013' && 
                 sub_actividad3 != 'FD78D664-C2B4-4994-A72A-4D55A62FB462' && sub_actividad3 != '0F76F82B-C1F7-4F49-A17B-9F0A09A60B18'){
            if(producto3 == '00000000-0000-0000-0000-000000000000'){
                texto+='- Seleccione un producto \n';   
                v = 1;
            }
        }
        if(time_est == '0' || time_est == ''){
            texto+='- Defina un tiempo de trabajo \n';
            v = 1;
        }
    }
    //console.log(band3);
    cadena1 = "idRegistro=" + idRegistro3 +
                  "&band=" + 21;
    if(v == 1){
        alert(texto);
    }else if(v == 0){
        $.ajax({
            type: "POST",
            url: "../modelo/consu_subactividades_patio_editar.php",
            data: cadena1,
            success: function (r) {
                //console.log(r);
                if(r == 0){
                    estado = 0;
                }else{
                    estado = 1;
                }
                if(sub_actividad3 == 'FD78D664-C2B4-4994-A72A-4D55A62FB462'){
                    cadena = "idRegistro=" + idRegistro3 +
                                "&producto=" + producto3 +
                                "&actividad=" + actividad3 +
                                "&sub_actividad=" + sub_actividad3 +
                                "&destino=" + destino +
                                "&time_est=" + time_est +
                                "&totalize_tm=" + totalize_tm +
                                "&estado=" + estado +
                                "&band=" + band3;
                }
                //
                $.ajax({
                    type: "POST",
                    url: "../modelo/consu_subactividades_patio_editar.php",
                    data: cadena,
                    success: function (r) {
                        console.log(r);
                        if (r == 1){
                            alert('El tiempo que intenta registrar es mayor al tiempo disponible para distribución');
                        }else if(r == 2){
                            alert('Las TM de APILAMIENTO superan a las TM ALIMENTADAS');
                        }else if(r == 3){
                            alert('Emergency');
                        }else{
                            $('#n_vehiculos_edit').val('');
                            $('#TempXvehiculo_edit').val('');
                            $('#n_paladas_edit').val('');
                            $('#TempXpalada_edit').val('');
                            $('#TM_Palada_edit').val('');
                            $('#TempReEst_edit').val('');
                            $('#TempMaquinaEstVehiculo_edit').val('');
                            $('#TempMaquinaEst_edit').val('');
                            $('#TotalTM_edit').val('');
                            $('#TotalTM_cargue_edit').val('');
                            $('#idDistribucion_edit').val('');
                            $('#plantilla_paladas_edit').hide();
                            $('#plantilla_cargue_edit').hide();
                            $('#plantilla_apila_entra_edit').hide();
                            $('#time_est_edit').val('');
                            $('#totalize_tm_edit').val('');
                            $('#calculado_edit').hide();
                            $('#calculado_cargue_edit').hide();
                            $('#bordePlantilla_edit').hide();
                            $('#div_equipos_edit').hide();
                            $('#div_equipos1_edit').hide();
                            $('#div_clasif_edit').hide();
                            FaltaAsignar_edit();
                            asignados_edit();
                            actividad_edit(actividad3);
                            GuardarObservaciones_edit();
                        }
                    }
                });
            }
        });
    }
}

function FaltaAsignar_edit(){
    idRegistro = $('#registroHorometro_edit').val();
    band = 1;
    cadena = "idRegistro=" + idRegistro +
            "&band=" + band;

    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio_editar.php",
        data: cadena,
        success: function (r) {
            //console.log(r);
            d = r.split("||");
            $('#ValoresAsignados_edit').val(d[1]);
            if ($('#ValoresAsignados_edit').val() == 1){
                $('#AgregarHorometro_edit').hide();
            }else{
                $('#AgregarHorometro_edit').show();
            }
            $('#observacionesTiquete_edit').val(d[2]);
            $('#idPatioLejia_edit').val(d[3]);
            $('#form_edit').html(d[5]);
            if (d[1] == 2){
                document.getElementById('FaltaAsignar_edit').innerHTML='<h5><b>'+d[0]+'</b></h5>';
                $('#volverStandby_edit').show();
            }else if(d[1] == 3){
                document.getElementById('FaltaAsignar_edit').innerHTML='<h5><b>'+d[0]+'</b></h5>';
                $('#volverStandby_edit').show();
            }else{
                document.getElementById('FaltaAsignar_edit').innerHTML='<h5>'+d[0]+'</h5>';
                $('#volverStandby_edit').hide();
            }
            if(d[4] != ""){
                const DatosZona = JSON.parse(d[4]);
                let bodyZonas = '';
                DatosZona.forEach(zone => {
                  bodyZonas += `
                    <option value="${zone.idZona}">${zone.Zona}</option>`
                });
                $('#zona_acopio_edit').html(bodyZonas);
            }
        }
    });
}

function seleccionado_asignar_edit(valor){
    if($('#actividad_edit').val() == '3F001906-CFF3-437F-A4C5-67CDADDFD904'){
        document.getElementById('bordePlantilla_edit').innerHTML='<center><h4>Apilamiento Entradas</h4></center>';
        $('#plantilla_apila_entra_edit').show();
        $('#bordePlantilla_edit').show();
        $('#plantilla_cargue_edit').hide();
        $('#plantilla_paladas_edit').hide();
        $('#btn_calcularData1_edit').hide();
        $('#btn_calcularData_edit').hide();
        //$('#btn_calcularModificar').show();
        $('#btn_calcularModificar_edit').hide();
        $('#btn_calcularModificar1_edit').hide();
        $('#GrabarDatos_edit').hide();
        $('#GrabarDatos1_edit').hide();
        $('#GrabarDatos2_edit').hide();
        $('#ModificarDatos_edit').hide();
        $('#ModificarDatos1_edit').hide();
        $('#ModificarDatos2_edit').hide();
        $('#div_equipos_edit').hide();
        $('#div_equipos1_edit').hide();
        $('#div_clasif_edit').hide();
    }else if (($('#'+valor+'2_edit').val() == 'ALIMENTAR') || ($('#'+valor+'2_edit').val() == 'APILAR')){
        $('#div_equipos_edit').show();
        $('#div_equipos1_edit').show();
        //console.log($('#'+valor+'2_edit').val());
        if (($('#'+valor+'2_edit').val() == 'ALIMENTAR')){
            $('#div_clasif_edit').show();
        }else{
            $('#div_clasif_edit').hide();
        }
        document.getElementById('bordePlantilla_edit').innerHTML='<center><h4>Distribución de tiempos por clasificación</h4></center>';
        $('#bordePlantilla_edit').show();
        $('#plantilla_cargue_edit').hide();
        $('#plantilla_paladas_edit').show();
        $('#plantilla_apila_entra_edit').hide();
        $('#btn_calcularData1_edit').hide();
        $('#btn_calcularData_edit').hide();
        //if ($('#ValoresAsignados_edit').val() == 1){
            $('#btn_calcularModificar_edit').show();
        /*}else{
            $('#btn_calcularModificar_edit').hide();
        }*/
        $('#btn_calcularModificar1_edit').hide();
        $('#GrabarDatos_edit').hide();
        $('#GrabarDatos1_edit').hide();
        $('#GrabarDatos2_edit').hide();
        $('#ModificarDatos_edit').hide();
        $('#ModificarDatos1_edit').hide();
        $('#ModificarDatos2_edit').hide();
    }else if (($('#'+valor+'2_edit').val() == 'CARGAR DESPACHO')){
        document.getElementById('bordePlantilla_edit').innerHTML='<center><h4>Distribución de tiempos por cargue</center></h4>';
        $('#bordePlantilla_edit').show();
        $('#plantilla_paladas_edit').hide();
        $('#plantilla_cargue_edit').show();
        $('#plantilla_apila_entra_edit').hide();
        $('#btn_calcularData1_edit').hide();
        $('#btn_calcularData_edit').hide();
        $('#btn_calcularModificar_edit').hide();
        //if ($('#ValoresAsignados_edit').val() == 1){
            $('#btn_calcularModificar1_edit').show();
        /*}else{
            $('#btn_calcularModificar_edit').hide();
        }*/
        $('#GrabarDatos_edit').hide();
        $('#GrabarDatos1_edit').hide();
        $('#GrabarDatos2_edit').hide();
        $('#ModificarDatos_edit').hide();
        $('#ModificarDatos1_edit').hide();
        $('#ModificarDatos2_edit').hide();
        $('#div_equipos_edit').hide();
        $('#div_equipos1_edit').hide();
        $('#div_clasif_edit').hide();
    }
    sub =  $('#'+valor+'1_edit').val();
    $('#sub_actividad_edit').val(sub);
    /*$('#'+valor).removeClass('btn-default');
    $('#'+valor).addClass('btn-success');*/
    idDistribucion = valor;
    band = 6;
    cadena = "idDistribucion=" + idDistribucion +
            "&band=" + band;

    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio_editar.php",
        data: cadena,
        success: function (r) {
            band = 24;
            idRegistro = $('#registroHorometro_edit').val();
            $.post("../modelo/consu_subactividades_patio_editar.php", {band: band, seleccionado: valor, idRegistro: idRegistro}, 
                function(mensaje){
                    //console.log(mensaje);
                    mensaje1 = mensaje.split("||");
                    $('#pila_edit').html(mensaje1[0]).fadeIn();
                    $('#producto_edit').html(mensaje1[1]).fadeIn();
                    $('#equipo_edit').html(mensaje1[2]).fadeIn();
                    $('#zona_acopio_edit').html(mensaje1[3]).fadeIn();
                    $('#destino_edit').html(mensaje1[4]).fadeIn();
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    $('#producto_sec_edit').val(mensaje1[6]);
                    //console.log(mensaje1[2]);
                    /*$('#').html(mensaje1[]).fadeIn();
                    $('#').html(mensaje1[]).fadeIn();
                    $('#').html(mensaje1[]).fadeIn();
                    $('#').html(mensaje1[]).fadeIn();*/
                });
            //console.log(r);
            d = r.split("||");
            for(i=0;i<document.frm_edit.elements.length; i++)
            {  if(document.frm_edit.elements[i].type=="button")
                {   $('#'+document.frm_edit.elements[i].value+'_edit').removeClass('btn-success'); 
                    $('#'+document.frm_edit.elements[i].value+'_edit').addClass('btn-default'); 
                }
            }
            if (d[0] == 1){
                $('#n_paladas_edit').val(d[1]);
                $('#TM_Palada_edit').val(d[2]);
                $('#TempXpalada_edit').val(d[3]);
                $('#TempReEst_edit').val(d[4]);
                $('#TempMaquinaEst_edit').val(d[5]);
                $('#idDistribucion_edit').val(d[6]);
                $('#idHorometro_edit').val(d[8]);
                $('#tempo_clasif_edit').val(d[9]);
                //total = d[1]*d[2];
                $('#TotalTM_edit').val(d[7]);
                document.getElementById('bordePlantilla_edit').innerHTML='<center><h4>Distribución de tiempos por clasificación</h4></center>';
                $('#bordePlantilla_edit').show();
                $('#plantilla_paladas_edit').show();
                $('#plantilla_cargue_edit').hide();
                $('#plantilla_apila_entra_edit').hide();
                $('#calculado_edit').show();
                $('#calculado_cargue_edit').hide();
                $('#GrabarDatos_edit').hide();
                $('#GrabarDatos1_edit').hide();
                $('#GrabarDatos2_edit').hide();
                //if ($('#ValoresAsignados_edit').val() == 1){
                    $('#ModificarDatos_edit').show();
                /*}else{
                    $('#ModificarDatos_edit').hide();
                }*/
                $('#ModificarDatos1_edit').hide();
                $('#ModificarDatos2_edit').hide();
                //$('#div_equipos_edit').hide();
                //$('#div_equipos1_edit').hide();
                //$('#div_clasif_edit').hide();
            }else if (d[0] == 2){
                $('#n_vehiculos_edit').val(d[1]);
                $('#TempReEstVehiculo_edit').val(d[2]);
                $('#TempXvehiculo_edit').val(d[3]);
                $('#TempMaquinaEstVehiculo_edit').val(d[4]);
                $('#idDistribucion_edit').val(d[5]);
                $('#TotalTM_cargue_edit').val(d[6]);
                $('#idHorometro_edit').val(d[7]);
                document.getElementById('bordePlantilla_edit').innerHTML='<center><h4>Distribución de tiempos por Cargue</h4></center>';
                $('#bordePlantilla_edit').show();
                $('#plantilla_paladas_edit').hide();
                $('#plantilla_cargue_edit').show();
                $('#plantilla_apila_entra_edit').hide();
                $('#calculado_edit').hide();
                $('#calculado_cargue_edit').show();
                $('#GrabarDatos_edit').hide();
                $('#GrabarDatos1_edit').hide();
                $('#GrabarDatos2_edit').hide();
                $('#ModificarDatos_edit').hide();
                $('#div_equipos_edit').hide();
                $('#div_equipos1_edit').hide();
                $('#div_clasif_edit').hide();
                //if ($('#ValoresAsignados_edit').val() == 1){
                    $('#ModificarDatos1_edit').show();
                /*}else{
                    $('#ModificarDatos1_edit').hide();
                }*/
                $('#ModificarDatos2_edit').hide();
            }else if(d[0] == 3){
                $('#time_est_edit').val(d[1]);
                $('#idDistribucion_edit').val(d[2]);
                $('#totalize_tm_edit').val(d[3]);
                $('#idHorometro_edit').val(d[4]);
                document.getElementById('bordePlantilla_edit').innerHTML='<center><h4>Apilamiento Entradas</h4></center>';
                $('#plantilla_apila_entra_edit').show();
                $('#bordePlantilla_edit').show();
                $('#plantilla_cargue_edit').hide();
                $('#plantilla_paladas_edit').hide();
                $('#btn_calcularData1_edit').hide();
                $('#btn_calcularData_edit').hide();
                //$('#btn_calcularModificar').show();
                $('#btn_calcularModificar_edit').hide();
                $('#btn_calcularModificar1_edit').hide();
                $('#GrabarDatos_edit').hide();
                $('#GrabarDatos1_edit').hide();
                $('#GrabarDatos2_edit').hide();
                $('#ModificarDatos_edit').hide();
                $('#ModificarDatos1_edit').hide();
                $('#div_equipos_edit').hide();
                $('#div_equipos1_edit').hide();
                $('#div_clasif_edit').hide();
                //if ($('#ValoresAsignados_edit').val() == 1){
                    $('#ModificarDatos2_edit').show();
                /*}else{
                    $('#ModificarDatos2_edit').hide();
                }*/
            }
        }
    });
}

function ModificarDatos_edit(r){
    if (r == 'Clasificar'){
        idDistribucion = $('#idDistribucion_edit').val();
        idHorometro = $('#idHorometro_edit').val();
        idRegistro4 = $('#registroHorometro_edit').val();
        producto4 = $('#producto_sec_edit').val();
        actividad4 = $('#actividad_edit').val();
        sub_actividad4 = $('#sub_actividad_edit').val();
        N_paladas4 = $('#n_paladas_edit').val();
        TM_Palada4 = $('#TM_Palada_edit').val();
        TotalTM4 = $('#TotalTM_edit').val();
        /*TempXpalada4 = $('#TempXpalada').val();
        TempReEst4 = $('#TempReEst').val();*/
        TempMaquinaEst4 = $('#TempMaquinaEst_edit').val();
        band4 = 7;

        pila4 = $('#pila_edit').val();
        equipo4 = $('#equipo_edit').val();
        zona4 = $('#zona_acopio_edit').val();
        tempo_clasif4 = $('#tempo_clasif_edit').val();
        v = 0;
        texto = '';
        if(TempMaquinaEst4 != '0'){
            if(pila4 == '0'){
                texto+='- Seleccione una pila \n';
                v = 1;
            }
            if(producto4 == '00000000-0000-0000-0000-000000000000'){
                texto+='- Seleccione un producto \n';   
                v = 1;
            }
            if(equipo4 == '0'){
                texto+='- Seleccione un equipo de clasificación \n';
                v = 1;
            }
            if(zona4 == '0'){
                texto+='- Seleccione una zona \n';
                v = 1;
            }
        }
        //alert(texto);

        cadena = "idRegistro=" + idRegistro4 +
                "&producto=" + producto4 +
                "&actividad=" + actividad4 +
                "&sub_actividad=" + sub_actividad4 +
                "&n_paladas=" + N_paladas4 +
                "&TM_Palada=" + TM_Palada4 +
                "&TotalTM=" + TotalTM4 +
                "&pila=" + pila4 +
                "&equipo=" + equipo4 +
                "&zona=" + zona4 +
                "&tempo_clasif=" + tempo_clasif4 +
                "&idDistribucion=" + idDistribucion +
                "&idHorometro=" + idHorometro +
                "&TempMaquinaEst=" + TempMaquinaEst4 +
                "&band=" + band4;
    }else if (r == 'Cargue'){
        idDistribucion = $('#idDistribucion_edit').val();
        idHorometro = $('#idHorometro_edit').val();
        idRegistro4 = $('#registroHorometro_edit').val();
        producto4 = $('#producto_sec_edit').val();
        actividad4 = $('#actividad_edit').val();
        sub_actividad4 = $('#sub_actividad_edit').val();
        N_vehiculos4 = $('#n_vehiculos_edit').val();
        TempXvehiculo4 = $('#TempXvehiculo_edit').val();
        TempReEstVehiculo4 = $('#TempReEstVehiculo_edit').val();
        TempMaquinaEstVehiculo4 = $('#TempMaquinaEstVehiculo_edit').val();
        band4 = 8;
        v = 0;
        cadena = "idRegistro=" + idRegistro4 +
                "&producto=" + producto4 +
                "&actividad=" + actividad4 +
                "&sub_actividad=" + sub_actividad4 +
                "&n_vehiculos=" + N_vehiculos4 +
                "&TempXvehiculo=" + TempXvehiculo4 +
                "&idDistribucion=" + idDistribucion +
                "&idHorometro=" + idHorometro +
                "&TempReEstVehiculo=" + TempReEstVehiculo4 +
                "&TempMaquinaEstVehiculo=" + TempMaquinaEstVehiculo4 +
                "&band=" + band4;
    }else if(r == 'Apilar_entra'){
        idDistribucion = $('#idDistribucion_edit').val();
        idHorometro = $('#idHorometro_edit').val();
        idRegistro4 = $('#registroHorometro_edit').val();
        producto4 = $('#producto_sec_edit').val();
        actividad4 = $('#actividad_edit').val();
        sub_actividad4 = $('#sub_actividad_edit').val();
        time_est = $('#time_est_edit').val();
        totalize_tm = $('#totalize_tm_edit').val();
        band4 = 15;
        destino4 = $('#destino_edit').val();
        cadena = "idRegistro=" + idRegistro4 +
                "&producto=" + producto4 +
                "&actividad=" + actividad4 +
                "&sub_actividad=" + sub_actividad4 +
                "&time_est=" + time_est +
                "&totalize_tm=" + totalize_tm +
                "&idDestino=" + destino4 +
                "&idDistribucion=" + idDistribucion +
                "&idHorometro=" + idHorometro +
                "&band=" + band4;
        texto = '';
        v = 0;
        if(time_est != '0'){
            if(sub_actividad4 == 'FD78D664-C2B4-4994-A72A-4D55A62FB462'){
                if(producto4 == '00000000-0000-0000-0000-000000000000'){
                    texto+='- Seleccione un producto \n';
                    v = 1;
                }
                if(destino4 == '00000000-0000-0000-0000-000000000000'){
                    texto+='- Seleccione un destino \n';
                    v = 1;
                }
            }else if(sub_actividad4 != 'A70B0905-E285-4DE6-B84D-7F2727137F05' && sub_actividad4 != '2BFB8ECB-6E93-4437-9440-DFFE24845013' && sub_actividad4 != 'FD78D664-C2B4-4994-A72A-4D55A62FB462'){
                if(producto4 == '00000000-0000-0000-0000-000000000000'){
                    texto+='- Seleccione un producto \n';   
                    v = 1;
                }
            }
        }
    }
    //console.log(band4);
    if(v == 1){
        alert(texto);
    }else{
        $.ajax({
            type: "POST",
            url: "../modelo/consu_subactividades_patio_editar.php",
            data: cadena,
            success: function (r) {
                console.log(r);
                if (r == 0 || r == null){
                    alert('El tiempo que intenta registrar es mayor al tiempo disponible para distribución');
                }else{
                    $('#idDistribucion_edit').val('');
                    $('#n_vehiculos_edit').val('');
                    $('#TempXvehiculo_edit').val('');
                    $('#n_paladas_edit').val('');
                    $('#TempXpalada_edit').val('');
                    $('#TM_Palada_edit').val('');
                    $('#TempReEst_edit').val('');
                    $('#TempMaquinaEstVehiculo_edit').val('');
                    $('#TempMaquinaEst_edit').val('');
                    $('#TotalTM_edit').val('');
                    $('#TotalTM_cargue_edit').val('');
                    $('#plantilla_paladas_edit').hide();
                    $('#plantilla_cargue_edit').hide();
                    $('#calculado_edit').hide();
                    $('#calculado_cargue_edit').hide();
                    $('#bordePlantilla_edit').hide();
                    $('#plantilla_apila_entra_edit').hide();
                    $('#time_est_edit').val('');
                    $('#totalize_tm_edit').val('');
                    $('#div_equipos_edit').hide();
                    $('#div_equipos1_edit').hide();
                    $('#div_clasif_edit').hide();
                    FaltaAsignar_edit();
                    asignados_edit();
                    actividad_edit(actividad4);
                }
            }
        });
    }
}
function MostrarOcultar_edit(r){
    if (r == 1){
        document.getElementById('act_edit').innerHTML='<center><h4>Actividades</h4></center>';
        $('#checked_edit').hide();
        $('#divDescuento_edit').hide();
        $('#divStandby_edit').hide();
        $('#volverAct_edit').hide();
        $('#volverStandby_edit').show();
        $('#volverDesc_edit').show();
        $('#FaltaAsignar_edit').show();
        $('#ActDetallado_edit').show();
        $('#divProducto_edit').hide();
        $('#bordeAsignados_edit').hide();
        $('#divAsignados_edit').hide();
        $('#divAsignados1_edit').hide();
        $('#borde_edit').hide();
        $('#div_subactividad_edit').hide();
        $('#bordePlantilla_edit').hide();
        $('#plantilla_cargue_edit').hide();
        $('#calculado_cargue_edit').hide();
        $('#plantilla_paladas_edit').hide();
        $('#plantilla_apila_entra_edit').hide();
        $('#GrabarDatos2_edit').hide();
        $('#ModificarDatos2_edit').hide();
        $('#calculado_edit').hide();
        $('#GrabarDatos_edit').hide();
        $('#GrabarDatos1_edit').hide();
        $('#ModificarDatos_edit').hide();
        $('#ModificarDatos1_edit').hide();
        $('#ModificarDatos2_edit').hide();
        $('#div_equipos_edit').hide();
        $('#div_equipos1_edit').hide();
        $('#div_clasif_edit').hide();
    }else if (r == 2){
        for(i=0;i<document.form_edit.elements.length; i++)
        {  if(document.form_edit.elements[i].type=="button")
            {   $('#'+document.form.elements[i].value+'_edit').removeClass('btn-success'); 
                $('#'+document.form.elements[i].value+'_edit').addClass('btn-default');
            }
        }
        $('#div_red_edit').hide();
        document.getElementById('act_edit').innerHTML='<center><h4>Descuentos</h4></center>';
        $('#checked_edit').hide();
        $('#divDescuento_edit').show();
        $('#divStandby_edit').hide();
        $('#volverAct_edit').show();
        $('#volverDesc_edit').hide();
        $('#volverStandby_edit').hide();
        $('#FaltaAsignar_edit').show();
        $('#ActDetallado_edit').hide();
        $('#divProducto_edit').hide();
        $('#bordeAsignados_edit').hide();
        $('#divAsignados_edit').hide();
        $('#divAsignados1_edit').hide();
        $('#borde_edit').hide();
        $('#div_subactividad_edit').hide();
        $('#bordePlantilla_edit').hide();
        $('#plantilla_cargue_edit').hide();
        $('#calculado_cargue_edit').hide();
        $('#plantilla_paladas_edit').hide();
        $('#plantilla_apila_entra_edit').hide();
        $('#GrabarDatos2_edit').hide();
        $('#ModificarDatos2_edit').hide();
        $('#calculado_edit').hide();
        $('#GrabarDatos_edit').hide();
        $('#GrabarDatos1_edit').hide();
        $('#ModificarDatos_edit').hide();
        $('#ModificarDatos1_edit').hide();
        $('#ModificarDatos2_edit').hide();
        $('#div_equipos_edit').hide();
        $('#div_equipos1_edit').hide();
        $('#div_clasif_edit').hide();
        $('#divTM_despacho_edit').hide();
        DescuentosAsignados_edit();
    }else if (r == 3){
        for(i=0;i<document.form_edit.elements.length; i++)
        {  if(document.form_edit.elements[i].type=="button")
            {   $('#'+document.form_edit.elements[i].value+'_edit').removeClass('btn-success'); 
                $('#'+document.form_edit.elements[i].value+'_edit').addClass('btn-default');
            }
        }
        document.getElementById('act_edit').innerHTML='<center><h4>Standby</h4></center>';
        $('#checked_edit').hide();
        $('#divDescuento_edit').hide();
        $('#divStandby_edit').show();
        $('#volverAct_edit').show();
        $('#volverDesc_edit').hide();
        $('#volverStandby_edit').hide();
        $('#FaltaAsignar_edit').show();
        $('#ActDetallado_edit').hide();
        $('#divProducto_edit').hide();
        $('#bordeAsignados_edit').hide();
        $('#divAsignados_edit').hide();
        $('#divAsignados1_edit').hide();
        $('#borde_edit').hide();
        $('#div_subactividad_edit').hide();
        $('#bordePlantilla_edit').hide();
        $('#plantilla_cargue_edit').hide();
        $('#calculado_cargue_edit').hide();
        $('#plantilla_paladas_edit').hide();
        $('#plantilla_apila_entra_edit').hide();
        $('#GrabarDatos2_edit').hide();
        $('#ModificarDatos2_edit').hide();
        $('#calculado_edit').hide();
        $('#GrabarDatos_edit').hide();
        $('#GrabarDatos1_edit').hide();
        $('#ModificarDatos_edit').hide();
        $('#ModificarDatos1_edit').hide();
        $('#ModificarDatos2_edit').hide();
        $('#div_equipos_edit').hide();
        $('#div_equipos1_edit').hide();
        $('#div_clasif_edit').hide();
        StandbyAsignados_edit();
    }
    
}

function AplicarDescuento_edit(){
    idRegistro = $('#registroHorometro_edit').val();
    ValorDescuento = $('#ValorDescuento_edit').val();
    motivoDescuento = $('#motivoDescuento_edit').val();
    TotalTM_Despacho = $('#TotalTM_Despacho_edit').val();
    usuario = $('#usuario_edit').val();
    band = 9;
    cadena = "idRegistro=" + idRegistro +
            "&ValorDescuento=" + ValorDescuento +
            "&motivoDescuento=" + motivoDescuento +
            "&usuario=" + usuario +
            "&TotalTM_Despacho=" + TotalTM_Despacho +
            "&band=" + band;

    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio_editar.php",
        data: cadena,
        success: function (r) {
            //console.log(r);
            if (r == 1){
                $('#checked_edit').show();
                DescuentosAsignados_edit();
                FaltaAsignar_edit();
            }else if (r == 2){
                alert('El descuento que intentas realizar es mayor al tiempo disponible para distribución');
            }else{
                $('#checked_edit').hide();
            }
        }
    });
}

function AplicarStandby_edit(){
    idRegistro = $('#registroHorometro_edit').val();
    ValorStandby = $('#ValorStandby_edit').val();
    motivoStandby = $('#motivoStandby_edit').val();
    usuario = $('#usuario_edit').val();
    band = 17;
    cadena = "idRegistro=" + idRegistro +
            "&ValorStandby=" + ValorStandby +
            "&motivoStandby=" + motivoStandby +
            "&usuario=" + usuario +
            "&band=" + band;
    //console.log(motivoStandby);
    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio_editar.php",
        data: cadena,
        success: function (r) {
            //console.log(r);
            if (r == 1){
                $('#checked_edit').show();
                StandbyAsignados_edit();
                FaltaAsignar_edit();
            }else if (r == 2){
                alert('El Standby que intentas realizar es mayor al tiempo disponible para distribución');
            }else{
                $('#checked_edit').hide();
            }
        }
    });
}

function StandbyAsignados_edit(){
    idRegistro2 = $('#registroHorometro_edit').val();
    usuario2 = $('#usuario_edit').val();
    band5 = 16;
    cadena = "idRegistro=" + idRegistro2 +
            "&usuario=" + usuario2 +
            "&band=" + band5;
    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio_editar.php",
        data: cadena,
        success: function (r){
            //alert(r);
            if (r != 0){
                data = r.split("||");
                $('#ValorStandby_edit').val(data[0]);
                $('#motivoStandby_edit').val(data[1]);
                $('#btn_apliStandby_edit').hide();
                $('#btn_modStandby_edit').show();
            }else{
                $('#ValorStandby_edit').val('');
                $('#motivoStandby_edit').val('');
                $('#btn_apliStandby_edit').show();
                $('#btn_modStandby_edit').hide();
            }
        }
    });
}

function DescuentosAsignados_edit(){
    idRegistro1 = $('#registroHorometro_edit').val();
    usuario1 = $('#usuario_edit').val();
    band1 = 10;
    cadena = "idRegistro=" + idRegistro1 +
            "&usuario=" + usuario1 +
            "&band=" + band1;
    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio_editar.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            if (r != 0){
                //if ($('#ValoresAsignados_edit').val() == 1){
                    $('#btn_modDesc_edit').show();
                    //console.log(1);
                /*}else if ($('#ValoresAsignados_edit').val() == 3){
                    //console.log(3);
                    $('#btn_apliDesc_edit').hide();
                    $('#btn_modDesc_edit').show();
                }else{
                    //console.log('otro');
                    $('#btn_modDesc_edit').hide();
                }*/
                $('#btn_apliDesc_edit').hide();
                data = r.split("||");
                $('#ValorDescuento_edit').val(data[1]);
                $('#motivoDescuento_edit').val(data[2]);
                $('#TotalTM_Despacho_edit').val(data[3]);
            }else{
                //if ($('#ValoresAsignados_edit').val() == 1){
                    $('#btn_apliDesc_edit').show();
                /*}else if ($('#ValoresAsignados_edit').val() == 3){
                    $('#btn_apliDesc_edit').show();
                }else{
                    $('#btn_apliDesc_edit').hide();
                }*/
                $('#btn_modDesc_edit').hide();
            }
        }
    });
}

function ModificarDescuento_edit(){
    idRegistro = $('#registroHorometro_edit').val();
    ValorDescuento = $('#ValorDescuento_edit').val();
    motivoDescuento = $('#motivoDescuento_edit').val();
    TotalTM_Despacho = $('#TotalTM_Despacho_edit').val();
    band = 11;
    cadena = "idRegistro=" + idRegistro +
            "&ValorDescuento=" + ValorDescuento +
            "&motivoDescuento=" + motivoDescuento +
            "&TotalTM_Despacho=" + TotalTM_Despacho +
            "&band=" + band;

    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio_editar.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            if (r == 1){
                $('#checked_edit').show();
                DescuentosAsignados_edit();
                FaltaAsignar_edit();
            }else if (r == 2){
                alert('El descuento que intentas realizar es mayor al tiempo disponible para distribución');
            }else{
                $('#checked_edit').hide();
            }
        }
    });
}

function ModificarStandby_edit(){
    idRegistro = $('#registroHorometro_edit').val();
    ValorStandby = $('#ValorStandby_edit').val();
    motivoStandby = $('#motivoStandby_edit').val();
    band = 18;
    cadena = "idRegistro=" + idRegistro +
            "&ValorStandby=" + ValorStandby +
            "&motivoStandby=" + motivoStandby +
            "&band=" + band;

    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio_editar.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            if (r == 1){
                $('#checked_edit').show();
                StandbyAsignados_edit();
                FaltaAsignar_edit();
            }else if (r == 2){
                alert('El descuento que intentas realizar es mayor al tiempo disponible para distribución');
            }else{
                $('#checked_edit').hide();
            }
        }
    });
}

function editar_tiquete(id_tiquete,val){
    if(val == 1){
        let question = confirm("¿Quieres editar este tiquete?");
        if(question){
            idRegistro = id_tiquete;
            band = 22;
            cadena = "idRegistro=" + idRegistro +
                    "&band=" + band;
            $.ajax({
                type: "POST",
                url: "../modelo/consu_subactividades_patio_editar.php",
                data: cadena,
                success: function (r){
                    //alert(r);
                    if(r == 1){
                        $('#modalAsignarTemp_edit').modal('show');
                        datos_edit(id_tiquete);            
                    }
                }
            });
        }
    }else if(val == 2){
        $('#modalAsignarTemp_edit').modal('show');
        datos_edit(id_tiquete);
    }
}

function actualizar_edit(){
    idRegistro = $('#registroHorometro_edit').val();
    band = 23;
    cadena = "idRegistro=" + idRegistro +
            "&band=" + band;
    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio_editar.php",
        data: cadena,
        success: function (r){
            //alert(r);
            if(r == 1){
                alert("Finalizado con exito");
                self.location = "inicio_patio.php";         
            }
        }
    });
}