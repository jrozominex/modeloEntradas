function GuardarObservaciones(){
    if ($('#observacionesTiquete').val() != ""){
        tiquete = $('#registroHorometro').val();
        observaciones = $('#observacionesTiquete').val();
        band = 12;

        cadena = "tiquete=" + tiquete +
                "&band=" + band +
                "&observaciones=" + observaciones;
        $.ajax({
            type: "POST",
            url: "../modelo/consu_subactividades_patio.php",
            data: cadena,
            success: function (r){
                //console.log(r);
                if(r == 1){
                    FaltaAsignar();
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

function datos(id_registro){
    registro = id_registro;
    $('#registroHorometro').val(registro);
    FaltaAsignar();
}

function actividad(val){
    document.getElementById('pila').style.borderColor="#ccc";
    document.getElementById('producto_sec').style.borderColor="#ccc";
    document.getElementById('equipo').style.borderColor="#ccc";
    document.getElementById('zona_acopio').style.borderColor="#ccc";
    document.getElementById('tempo_clasif').style.borderColor="#ccc";
    document.getElementById('n_paladas').style.borderColor="#ccc";
    document.getElementById('TotalTM').style.borderColor="#ccc";
    document.getElementById('TempMaquinaEst').style.borderColor="#ccc";
    //
    document.getElementById('destino').style.borderColor="#ccc";
    document.getElementById('time_est').style.borderColor="#ccc";
    document.getElementById('totalize_tm').style.borderColor="#ccc";
    document.getElementById('ProductoObjetivo').style.borderColor="#ccc";
    document.getElementById('producto').style.borderColor="#ccc";
    //alert(val);
    $('#DestinoLabel').hide();
    $('#DestinoSelect').hide();
    $('#div_red').show();
    $('#n_vehiculos').val('');
    $('#TempXvehiculo').val('');
    $('#n_paladas').val('');
    $('#TempXpalada').val('');
    $('#TM_Palada').val('');
    $('#TempReEst').val('');
    $('#TempMaquinaEstVehiculo').val('');
    $('#TempMaquinaEst').val('');
    $('#TotalTM').val('');
    $('#TotalTM_cargue').val('');
    $('#idDistribucion').val('');
    $('#plantilla_paladas').hide();
    $('#plantilla_cargue').hide();
    $('#plantilla_apila_entra').hide();
    $('#time_est').val('');
    $('#totalize_tm').val('');
    $('#calculado').hide();
    $('#calculado_cargue').hide();
    $('#GrabarDatos').hide();
    $('#GrabarDatos1').hide();
    $('#ModificarDatos').hide();
    $('#ModificarDatos1').hide();
    $('#ModificarDatos2').hide();
    $('#div_equipos').hide();
    $('#div_equipos1').hide();
    $('#div_clasif').hide();
    for(i=0;i<document.form.elements.length; i++)
    {  if(document.form.elements[i].type=="button")
        {   //document.form.elements[i].classList.remove('btn-success');
            //document.form.elements[i].classList.add('btn-default');
            $('#'+document.form.elements[i].value).removeClass('btn-success'); 
            $('#'+document.form.elements[i].value).addClass('btn-default');
            $('#bordePlantilla').hide();
            $('#plantilla_paladas').hide();
            $('#plantilla_cargue').hide();
            $('#plantilla_apila_entra').hide();
            $('#div_equipos').hide();
            $('#div_equipos1').hide();
            //console.log(document.form.elements[i].value);
        }
    } 
    $('#sub_actividad').val("");
    var actividad2 = $('#'+val+'').val();
    $('#'+val).removeClass('btn-default');
    $('#'+val).addClass('btn-success');
    idRegistro2 = $('#registroHorometro').val();
    $('#actividad').val(actividad2);
    band = 2;
    if(actividad2 == '404D205C-7D03-4E84-9AC1-03EE5A8D83F9' || actividad2 == '6A173460-B5ED-4CB6-B73A-ECDDF5BE3734' || actividad2 == '0FFB9BAF-3DB6-47B2-8E7F-272D2C118AD0'){
        $('#div_pila').show();
        //$('#ProductoObjetivoLabel').show();
        //$('#ProductoObjetivoSelect').show();
    }else{
        $('#div_pila').hide();
        $('#ProductoObjetivoLabel').hide();
        $('#ProductoObjetivoSelect').hide();
    }
    cadena = "actividad=" + actividad2 +
            "&idRegistro=" + idRegistro2 +
            "&band=" + band;
    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio.php",
        data: cadena,
        success: function (r) {
            let bodySubActividad = '';
            //console.log(r);
            d = r.split("||");
            if (d[2] == 0){
                
                bodySubActividad = '<div clas="col-xs-8  col-sm-8  col-md-8  col-lg-8  col-xl-8"></div><div clas="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3"><center><h5>--- Ya se asociaron todas la SubActividades ---</h5></center></div>';
                $('#bordePlantilla').hide();
                $('#plantilla_paladas').hide();
                $('#plantilla_cargue').hide();
                $('#plantilla_apila_entra').hide();
                $('#div_equipos').hide();
                $('#div_equipos1').hide();
                $('#div_clasif').hide();
                $('#frm').html(bodySubActividad);
                document.getElementById('TM_alimentadas').innerHTML=d[0];
                document.getElementById('TM_apiladas').innerHTML=d[1];
                $('#TM_alimentadas1').val(d[0]);
                $('#TM_apiladas1').val(d[1]);
                asignados();
            }else{
                const datosActividad = JSON.parse(d[2]);
                datosActividad.forEach(act => {
                  bodySubActividad += `
                  <div class="col-xs-12  col-sm-12  col-md-12  col-lg-12  col-xl-12">
                  <div class="row">
                  <div class="col-xs-3  col-sm-3  col-md-3  col-lg-3  col-xl-3"></div>
                  <div class="col-xs-6  col-sm-6  col-md-6  col-lg-6  col-xl-6">
                  <input type="hidden" id="${act.idSubactividad}1" value="${act.Descripcion}">
                    <button type="button" id="${act.idSubactividad}" style="margin-bottom: 5px;" class="btn btn-default btn-sm" value="${act.idSubactividad}" onclick='seleccionado("${act.idSubactividad}")'>${act.Descripcion}</button>
                  </div>
                  </div>
                  </div>`
                });
                $('#frm').html(bodySubActividad);
                document.getElementById('TM_alimentadas').innerHTML=d[0];
                document.getElementById('TM_apiladas').innerHTML=d[1];
                $('#TM_alimentadas1').val(d[0]);
                $('#TM_apiladas1').val(d[1]);
                asignados();
            }
        }
    });
    if($('#'+val).val() == $('#encrypt').val()){
        $('#divProducto').show();
        $('#ProductoLabel').show();
        $('#ProductoSelect').show();
        $('#ProductoObjetivoLabel').hide();
        $('#ProductoObjetivoSelect').hide();
        $('#borde').show();
        $('#div_subactividad').show();
        //console.log($('#encrypt').val());
    }else if ($('#'+val).val() != $('#encrypt1').val()/* || $('#'+val).val() == '0ADD1931-4C6A-4258-B515-8F016516606C'*/){
        $('#divProducto').show();
        $('#ProductoLabel').show();
        $('#ProductoSelect').show();
        //$('#ProductoObjetivoLabel').show();
        //$('#ProductoObjetivoSelect').show();
        if($('#'+val).val() == '7E51DEAF-1782-46A1-BE54-4418C8F1D80B'){
            $('#borde').show();
            $('#div_subactividad').show();
        }else if($('#'+val).val() == 'D082C2F1-438D-4421-AE55-A99311435AF1'){
            $('#borde').show();
            $('#div_subactividad').show();
            $('#divProducto').hide();
        }else if($('#'+val).val() == '26BDE387-3522-42CB-9A29-D51385A041C5'){
            $('#DestinoLabel').show();
            $('#DestinoSelect').show();
            $('#ProductoObjetivoLabel').hide();
            $('#ProductoObjetivoSelect').hide();
        }else{
            //$('#borde').hide();
            //$('#div_subactividad').hide();
            $('#borde').show();
            $('#div_subactividad').show();
        }
        //console.log($('#'+val).val());
    }else{
        $('#divProducto').hide();
        $('#ProductoLabel').hide();
        $('#ProductoSelect').hide();
        $('#ProductoObjetivoLabel').hide();
        $('#ProductoObjetivoSelect').hide();
        $('#borde').show();
        $('#div_subactividad').show();
        //$('#YaAsignados').show();
    }
}

function asignados(){
    idRegistro1 = $('#registroHorometro').val();
    actividad1 = $('#actividad').val();
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
        url: "../modelo/consu_subactividades_patio.php",
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
                      <div class="col-xs-12  col-sm-12  col-md-12  col-lg-12  col-xl-12">
                        <div class="row">
                            <div class="col-xs-2  col-sm-2  col-md-2  col-lg-2  col-xl-2"></div>
                            <div class="col-xs-6  col-sm-6  col-md-6  col-lg-6  col-xl-6">
                              <input type="hidden" id="${asign.idDistribucion}2" value="${asign.sub_actividad}">
                              <input type="hidden" id="${asign.idDistribucion}1" value="${asign.idSubactividad}">
                              <div class="col-xs-6  col-sm-6  col-md-6  col-lg-6  col-xl-6">
                                <button type="button" style="margin-bottom: 5px;" id="${asign.idDistribucion}" class="btn btn-default btn-sm" value="${asign.idDistribucion}" onclick='seleccionado_asignar("${asign.idDistribucion}")'>${asign.Descripcion} -- ${asign.sub_actividad}</button>
                              </div>
                            </div>
                        </div>
                      </div>`
                    });
                    $('#AsignadosAgg').html(bodyAsignados);
                    $('#divAsignados1').hide();
                    $('#divAsignados').show();
                    $('#bordeAsignados').show();
                    $('#YaAsignados').show();
                }else{
                    $('#divAsignados1').hide();
                    $('#divAsignados').hide();
                    $('#bordeAsignados').hide();
                    $('#divAsignados').hide();
                }
            }else if(d[0] == 2){
                if (d[1] != 0 && d[2] != 0){
                    const datosAsignados = JSON.parse(d[1]);
                    let bodyAsignados = '';
                    datosAsignados.forEach(asign => {
                      bodyAsignados += `
                        <div class="row center-block">
                              <input type="hidden" id="${asign.idDistribucion}2" value="${asign.sub_actividad}">
                              <input type="hidden" id="${asign.idDistribucion}1" value="${asign.idSubactividad}">
                            <div class="col-xs-6  col-sm-6  col-md-6  col-lg-6  col-xl-6">
                                <button type="button" style="margin-bottom: 5px;" id="${asign.idDistribucion}" class="btn btn-default btn-sm" value="${asign.idDistribucion}" onclick='seleccionado_asignar("${asign.idDistribucion}")'>${asign.Descripcion} <br> ${asign.equipo}</button>
                            </div>
                        </div>`
                    });
                    $('#AsignadosAgg1').html(bodyAsignados);
                    const datosAsignados1 = JSON.parse(d[2]);
                    let bodyAsignados1 = '';
                    datosAsignados1.forEach(asign1 => {
                      bodyAsignados1 += `
                        <div class="row center-block">
                              <input type="hidden" id="${asign1.idDistribucion}2" value="${asign1.sub_actividad}">
                              <input type="hidden" id="${asign1.idDistribucion}1" value="${asign1.idSubactividad}">
                            <div class="col-xs-6  col-sm-6  col-md-6  col-lg-6  col-xl-6">
                                <button type="button" style="margin-bottom: 5px;" id="${asign1.idDistribucion}" class="btn btn-default btn-sm" value="${asign1.idDistribucion}" onclick='seleccionado_asignar("${asign1.idDistribucion}")'>${asign1.Descripcion} <br> ${asign1.equipo}</button>
                            </div>
                        </div>`
                    });
                    $('#AsignadosAgg2').html(bodyAsignados1);
                    $('#divAsignados1').show();
                    $('#divAsignados').hide();
                    $('#bordeAsignados').show();
                    $('#YaAsignados').show();
                }else{
                    $('#divAsignados1').hide();
                    $('#divAsignados').hide();
                }
            }
        }
    });
}

function Producto(){
    if($('#actividad').val() != '3F001906-CFF3-437F-A4C5-67CDADDFD904'){
        if($('#producto').val() != '00000000-0000-0000-0000-000000000000'){
            $('#borde').show();
            $('#div_subactividad').show();
        }else{
            $('#borde').hide();
            //$('#div_subactividad').hide();
        }
    }else{
        $('#borde').show();
        $('#div_subactividad').show();
    }
    var idRegistro5 = $('#registroHorometro').val();
    var producto = $('#producto').val();
    var band5 = 20;

    cadena = "idRegistro=" + idRegistro5 +
            "&producto=" + producto +
            "&band=" + band5;
    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio.php",
        data: cadena,
        success: function (r) {
            //console.log(r);
            $('#TM_Palada').val(r);
        }
    });
    $('#producto_sec').val($('#producto').val());
    calcularHoras('palada');
    /*$('#n_vehiculos').val('');
    $('#TempXvehiculo').val('');
    $('#n_paladas').val('');
    $('#TempXpalada').val('');
    $('#TempReEst').val('');
    $('#TempMaquinaEstVehiculo').val('');
    $('#TempMaquinaEst').val('');
    $('#TotalTM').val('');
    $('#TotalTM_cargue').val('');
    $('#idDistribucion').val('');
    //$('#calculado').hide();
    $('#calculado_cargue').hide();
    //$('#bordePlantilla').hide();
    //$('#plantilla_paladas').hide();
    //$('#plantilla_cargue').hide();
    //$('#plantilla_apila_entra').hide();
    $('#time_est').val('');
    $('#totalize_tm').val('');
    $('#GrabarDatos').hide();
    $('#GrabarDatos1').hide();
    $('#ModificarDatos').hide();
    $('#ModificarDatos1').hide();
    $('#ModificarDatos2').hide();
    //$('#div_equipos').hide();
    //$('#div_equipos1').hide();
    //$('#div_clasif').hide();*/
}

function seleccionado(val){
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $('#n_vehiculos').val('');
    $('#TempXvehiculo').val('');
    $('#n_paladas').val('');
    $('#TempXpalada').val('');
    //$('#TM_Palada').val('');
    $('#TempReEst').val('');
    $('#TempMaquinaEstVehiculo').val('');
    $('#TempMaquinaEst').val('');
    $('#TotalTM').val('');
    $('#TotalTM_cargue').val('');
    $('#idDistribucion').val('');
    $('#time_est').val('');
    $('#totalize_tm').val('');
    $('#calculado').hide();
    $('#calculado_cargue').hide();
    for(i=0;i<document.frm.elements.length; i++)
    {  if(document.frm.elements[i].type=="button")
        {   $('#'+document.frm.elements[i].value).removeClass('btn-success'); 
            $('#'+document.frm.elements[i].value).addClass('btn-default'); 
        }
    }
    if($('#actividad').val() == '3F001906-CFF3-437F-A4C5-67CDADDFD904'){
        document.getElementById('bordePlantilla').innerHTML='<center><h4>Apilamiento Entradas</h4></center>';
        $('#bordePlantilla').show();
        $('#plantilla_cargue').hide();
        $('#plantilla_paladas').hide();
        $('#plantilla_apila_entra').show();
        if ($('#'+val+'1').val() == 'MVTO. X CALIDAD' || $('#'+val+'1').val() == 'OFICIOS VARIOS'){
            $('#totalize_tm').val(0)
            $('#totalize_tm').hide()
            $('#totalize_tm_title').hide()
        }else{
            $('#totalize_tm').show()
            $('#totalize_tm_title').show()
        }
        $('#btn_calcularData1').hide();
        $('#btn_calcularData').hide();
        $('#btn_calcularModificar').hide();
        $('#btn_calcularModificar1').hide();
        $('#GrabarDatos').hide();
        $('#GrabarDatos1').hide();
        //if ($('#ValoresAsignados').val() == 1){
            $('#GrabarDatos2').show();
        /*}else{
            $('#GrabarDatos2').hide();
        }*/
        $('#ModificarDatos').hide();
        $('#ModificarDatos1').hide();
        $('#ModificarDatos2').hide();
        $('#div_equipos').hide();
        $('#div_equipos1').hide();
        $('#div_clasif').hide();
    }else if (($('#'+val+'1').val() == 'ALIMENTAR') || ($('#'+val+'1').val() == 'APILAR')){
        $('#div_equipos').show();
        $('#div_equipos1').show();
        if (($('#'+val+'1').val() == 'ALIMENTAR')){
            $('#div_clasif').show();
            $('#ProductoObjetivoLabel').show();
            $('#ProductoObjetivoSelect').show();
        }else{
            $('#div_clasif').hide();
            $('#ProductoObjetivoLabel').hide();
            $('#ProductoObjetivoSelect').hide();
            buscar_alimentacion();
        }
        document.getElementById('bordePlantilla').innerHTML='<center><h4>Distribución de tiempos por clasificación</h4></center>';
        $('#bordePlantilla').show();
        $('#plantilla_cargue').hide();
        $('#plantilla_apila_entra').hide();
        $('#plantilla_paladas').show();
        $('#calculado').show();
        $('#btn_calcularData1').hide();
        $('#btn_calcularData').show();
        $('#btn_calcularModificar').hide();
        $('#btn_calcularModificar1').hide();
        $('#GrabarDatos').hide();
        //$('#GrabarDatos1').hide();
        //if ($('#ValoresAsignados').val() == 1){
            $('#GrabarDatos1').show();
        /*}else{
            $('#GrabarDatos1').hide();
        }*/
        $('#GrabarDatos2').hide();
        $('#ModificarDatos').hide();
        $('#ModificarDatos1').hide();
        $('#ModificarDatos2').hide();
    }else if (($('#'+val+'1').val() == 'CARGAR DESPACHO')){
        $('#div_equipos').hide();
        $('#div_equipos1').hide();
        document.getElementById('bordePlantilla').innerHTML='<center><h4>Distribución de tiempos por cargue</center></h4>';
        $('#bordePlantilla').show();
        $('#plantilla_paladas').hide();
        $('#plantilla_cargue').hide();
        $('#plantilla_apila_entra').show();
        $('#btn_calcularData1').show();
        $('#totalize_tm_title').show();
        $('#totalize_tm').show();
        $('#btn_calcularData').hide();
        $('#btn_calcularModificar').hide();
        $('#btn_calcularModificar1').hide();
        $('#GrabarDatos').hide();
        $('#GrabarDatos1').hide();
        //if ($('#ValoresAsignados').val() == 1){
            $('#GrabarDatos2').show();
        /*}else{
            $('#GrabarDatos2').hide();
        }*/
        $('#ModificarDatos').hide();
        $('#ModificarDatos1').hide();
        $('#ModificarDatos2').hide();
    }else if($('#'+val+'1').val() == 'STANDBY'){
        document.getElementById('bordePlantilla').innerHTML='<center><h4>Tiempos de Standbay</h4></center>';
        $('#bordePlantilla').show();
        $('#plantilla_cargue').hide();
        $('#plantilla_paladas').hide();
        $('#plantilla_apila_entra').show();
        $('#totalize_tm').val(0)
        $('#totalize_tm').hide()
        $('#totalize_tm_title').hide()
        $('#btn_calcularData1').hide();
        $('#btn_calcularData').hide();
        $('#btn_calcularModificar').hide();
        $('#btn_calcularModificar1').hide();
        $('#GrabarDatos').hide();
        $('#GrabarDatos1').hide();
        //if ($('#ValoresAsignados').val() == 1){
            $('#GrabarDatos2').show();
        /*}else{
            $('#GrabarDatos2').hide();
        }*/
        $('#ModificarDatos').hide();
        $('#ModificarDatos1').hide();
        $('#ModificarDatos2').hide();
        $('#div_equipos').hide();
        $('#div_equipos1').hide();
        $('#div_clasif').hide();
    }else if($('#'+val+'1').val() == 'TRABAJOS MCOS.'){
        document.getElementById('bordePlantilla').innerHTML='<center><h4>VARIOS</h4></center>';
        $('#bordePlantilla').show();
        $('#plantilla_cargue').hide();
        $('#plantilla_paladas').hide();
        $('#plantilla_apila_entra').show();
        //if ( || $('#'+val+'1').val() == 'OFICIOS VARIOS'){
            $('#totalize_tm').val(0)
            $('#totalize_tm').hide()
            $('#totalize_tm_title').hide()
        /*}else{
            $('#totalize_tm').show()
            $('#totalize_tm_title').show()
        }*/
        $('#btn_calcularData1').hide();
        $('#btn_calcularData').hide();
        $('#btn_calcularModificar').hide();
        $('#btn_calcularModificar1').hide();
        $('#GrabarDatos').hide();
        $('#GrabarDatos1').hide();
        //if ($('#ValoresAsignados').val() == 1){
            $('#GrabarDatos2').show();
        /*}else{
            $('#GrabarDatos2').hide();
        }*/
        $('#ModificarDatos').hide();
        $('#ModificarDatos1').hide();
        $('#ModificarDatos2').hide();
        $('#div_equipos').hide();
        $('#div_equipos1').hide();
        $('#div_clasif').hide();
    }
    var a = $('#'+val).val();
    $('#sub_actividad').val(a);
    $('#'+val).removeClass('btn-default');
    $('#'+val).addClass('btn-success');
}

function calcularHoras(r){
    //$('#ModificarDatos').hide();
    if (r == 'palada'){
        //alert('bbb');
        var SubActividad = $('#sub_actividad').val();
        if (($('#n_paladas').val() != "") /*&& ($('#TempXpalada').val() != "")*/ && ($('#TM_Palada').val() != "")){
            N_paladas = $('#n_paladas').val();
            //TempEst = $('#TempXpalada').val();
            TM = $('#TM_Palada').val();
            TotalTM = N_paladas*TM;
            var roundTM = Math.round(TotalTM* 100) / 100;
            var TM_alimentadas = $('#TM_alimentadas1').val();
            var TM_apiladas = $('#TM_apiladas1').val();
            var suma = (parseInt(TotalTM) + parseInt(TM_apiladas));
            var resta = (parseInt(suma) - parseInt(TM_alimentadas));
            $('#TotalTM').val(roundTM);
            //$('#calculado').show();
            /*if ($('#ValoresAsignados').val() == 1){
                //$('#GrabarDatos1').show();
            }else{
                $('#GrabarDatos1').hide();
            }*/
            //$('#GrabarDatos1').show();
        }/*else{
            $('#GrabarDatos1').hide();
        }*/
    }else if(r == 'tonelada'){
        if (($('#TotalTM').val() != "") /*&& ($('#TempXpalada').val() != "")*/ && ($('#TM_Palada').val() != "")){
            TotalTM = $('#TotalTM').val();
            //TempEst = $('#TempXpalada').val();
            TM = $('#TM_Palada').val();
            N_paladas = TotalTM/TM;
            var roundPalada = Math.round(N_paladas* 1) / 1;
            var TM_alimentadas = $('#TM_alimentadas1').val();
            var TM_apiladas = $('#TM_apiladas1').val();
            var suma = (parseInt(TotalTM) + parseInt(TM_apiladas));
            var resta = (parseInt(suma) - parseInt(TM_alimentadas));
            $('#n_paladas').val(roundPalada);
            //$('#calculado').show();
            /*if ($('#ValoresAsignados').val() == 1){
                //$('#GrabarDatos1').show();
            }else{
                $('#GrabarDatos1').hide();
            }*/
            //$('#GrabarDatos1').show();
        }/*else{
            $('#GrabarDatos1').hide();
        }*/
    }
}

function calcularHorasMod(r){
    $('#GrabarDatos').hide();
    $('#GrabarDatos1').hide();
    if (r == 'Cargue'){
        if (($('#n_vehiculos').val() != "")&& ($('#TempXvehiculo').val() != "")){
            n_vehiculos = $('#n_vehiculos').val();
            TempEst = $('#TempXvehiculo').val();
            var HoraMaquina = (n_vehiculos*TempEst)/60;
            var round = Math.round(HoraMaquina* 10) / 10;
            var MinReloj = (n_vehiculos*TempEst);
            let horas = Math.floor(MinReloj / 60),
            minutosSobrantes = MinReloj % 60;
            var roundReloj = Math.round(minutosSobrantes* 1) / 1;
            Reloj = horas+":"+roundReloj;
            $('#TempReEstVehiculo').val(Reloj);
            $('#TempMaquinaEstVehiculo').val(round);
            $('#calculado_cargue').show();
            $('#ModificarDatos').hide();
            $('#ModificarDatos2').hide();
            //if ($('#ValoresAsignados').val() == 1){
                $('#ModificarDatos1').show();
            /*}else{
                $('#ModificarDatos1').hide();
            }*/
        }else{
            alert('Ingrese valores para calcular.');
        }
    }else if (r == 'Clasificar'){
        //console.log(r);
        if (($('#n_paladas').val() != "") /*&& ($('#TempXpalada').val() != "")*/&& ($('#TM_Palada').val() != "")){
            N_paladas = $('#n_paladas').val();
            TempEst = $('#TempXpalada').val();
            TM = $('#TM_Palada').val();
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
            $('#TotalTM').val(roundTM);
            $('#calculado').show();
            //if ($('#ValoresAsignados').val() == 1){
                $('#ModificarDatos').show();
            /*}else{
                $('#ModificarDatos').hide();
            }*/
            $('#ModificarDatos1').hide();
            $('#ModificarDatos2').hide();
        }else{
            alert('Ingrese valores para calcular.');
        }
    }
}

function GrabarDatos(r){
    $('#loader').show();
    $('#modal_body').hide();
    if (r == 'Clasificar'){
        idRegistro3 = $('#registroHorometro').val();
        producto3 = $('#producto_sec').val();
        ProductoObjetivo = $('#ProductoObjetivo').val();
        actividad3 = $('#actividad').val();
        sub_actividad3 = $('#sub_actividad').val();
        N_paladas3 = $('#n_paladas').val();
        TM_Palada3 = $('#TM_Palada').val();

        if($('#tempo_clasif').val() != ""){
            tempo_clasif = $('#tempo_clasif').val();
        }else{
            tempo_clasif = 0;
        }
        /*TempXpalada3 = $('#TempXpalada').val();
        TempReEst3 = $('#TempReEst').val();*/
        TempMaquinaEst3 = $('#TempMaquinaEst').val();
        TotalTM = $('#TotalTM').val();
        var TM_alimentadas = $('#TM_alimentadas1').val();
        var TM_apiladas = $('#TM_apiladas1').val();
        pila = $('#pila').val();
        equipo = $('#equipo').val();
        zona = $('#zona_acopio').val();
        band3 = 3;

        cadena = "idRegistro=" + idRegistro3 +
                "&producto=" + producto3 +
                "&ProductoObjetivo=" + ProductoObjetivo +
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
        //////////////////////////////////////////////////////////////////////
        produc = $('#producto').val();
        if(sub_actividad3 == '77DE71D6-2FE4-46C3-8615-9B1C3D8F94D0' || sub_actividad3 == 'DCEB2375-E476-45F6-9D59-FF9C0B49A5B5' || sub_actividad3 == '5A6F9DFF-4AC5-47D6-B3DA-6FEE1308DD7D'){
            if(ProductoObjetivo == '00000000-0000-0000-0000-000000000000'){
                texto+='- Seleccione un tipo de material \n';
                v = 1;
                document.getElementById("ProductoObjetivo").style.border = "1px solid";
                document.getElementById('ProductoObjetivo').style.borderColor="red";
            }
            if(tempo_clasif == '' || tempo_clasif == '0'){
                texto+='- Defina un tiempo de clasificacion \n';
                v = 1;
                document.getElementById("tempo_clasif").style.border = "1px solid";
                document.getElementById('tempo_clasif').style.borderColor="red";
            }
        }
        if(pila == '0'){
            texto+='- Seleccione un tipo de material \n';
            v = 1;
            document.getElementById("pila").style.border = "1px solid";
            document.getElementById('pila').style.borderColor="red";
        }
        if(producto3 == '00000000-0000-0000-0000-000000000000'){
            texto+='- Seleccione un producto \n';   
            v = 1;
            document.getElementById("producto").style.border = "1px solid";
            document.getElementById('producto').style.borderColor="red";
        }
        if(equipo == '0'){
            texto+='- Seleccione un equipo de clasificación \n';
            v = 1;
            document.getElementById("equipo").style.border = "1px solid";
            document.getElementById('equipo').style.borderColor="red";
        }
        if(zona == '0'){
            texto+='- Seleccione una zona \n';
            v = 1;
            document.getElementById("zona_acopio").style.border = "1px solid";
            document.getElementById('zona_acopio').style.borderColor="red";
        }
        if(N_paladas3 == '' || N_paladas3 == '0'){
            texto+='- Defina una cantidad de paladas \n';
            v = 1;
            document.getElementById("n_paladas").style.border = "1px solid";
            document.getElementById('n_paladas').style.borderColor="red";
        }
        if(TotalTM == '' || TotalTM == '0'){
            texto+='- Defina una cantidad de TM \n';
            v = 1;
            document.getElementById("TotalTM").style.border = "1px solid";
            document.getElementById('TotalTM').style.borderColor="red";
        }
        if(sub_actividad3 != '326D6364-121D-41B7-A188-9EE90B1E5F8B' && sub_actividad3 != '36D45DE3-9E29-47C4-9B18-960FF8C485CE' && sub_actividad3 != 'B6E8C730-CCB4-45E5-80A8-4DA9EF894B56'){
            if(TempMaquinaEst3 == '0' || TempMaquinaEst3 == ''){
                texto+='- Defina un tiempo de trabajo \n';
                v = 1;
                document.getElementById("TempMaquinaEst").style.border = "1px solid";
                document.getElementById('TempMaquinaEst').style.borderColor="red";
            }
        }

    }else if (r == 'Cargue'){
        idRegistro3 = $('#registroHorometro').val();
        producto3 = $('#producto_sec').val();
        actividad3 = $('#actividad').val();
        sub_actividad3 = $('#sub_actividad').val();
        N_vehiculos3 = $('#n_vehiculos').val();
        TempXvehiculo3 = $('#TempXvehiculo').val();
        TempReEstVehiculo3 = $('#TempReEstVehiculo').val();
        TempMaquinaEstVehiculo3 = $('#TempMaquinaEstVehiculo').val();
        TotalTM = $('#TotalTM_cargue').val();
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
        idRegistro3 = $('#registroHorometro').val();
        actividad3 = $('#actividad').val();
        sub_actividad3 = $('#sub_actividad').val();
        if(sub_actividad3 == 'A70B0905-E285-4DE6-B84D-7F2727137F05' || sub_actividad3 == '2BFB8ECB-6E93-4437-9440-DFFE24845013' || sub_actividad3 == '0F76F82B-C1F7-4F49-A17B-9F0A09A60B18'){
            producto3 = '';
        }else{
            producto3 = $('#producto_sec').val();    
        }
        destino = $('#destino').val();
        time_est = $('#time_est').val();
        totalize_tm = $('#totalize_tm').val();
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
            if(producto3 == '00000000-0000-0000-0000-000000000000' || producto3 == ''){
                texto+='- Seleccione un producto \n';
                v = 1;
                document.getElementById("producto_sec").style.border = "1px solid";
                document.getElementById('producto_sec').style.borderColor="red";
            }
            if(destino == '00000000-0000-0000-0000-000000000000'){
                texto+='- Seleccione un destino \n';
                v = 1;
                document.getElementById("destino").style.border = "1px solid";
                document.getElementById('destino').style.borderColor="red";
            }
            if(totalize_tm == '0' || totalize_tm == ''){
                texto+='- Defina un tiempo de trabajo \n';
                v = 1;
                document.getElementById("totalize_tm").style.border = "1px solid";
                document.getElementById('totalize_tm').style.borderColor="red";
            }
        }else if(sub_actividad3 != 'A70B0905-E285-4DE6-B84D-7F2727137F05' && sub_actividad3 != '2BFB8ECB-6E93-4437-9440-DFFE24845013' && sub_actividad3 != 'FD78D664-C2B4-4994-A72A-4D55A62FB462'){
            if(producto3 == '00000000-0000-0000-0000-000000000000'){
                texto+='- Seleccione un producto \n';   
                v = 1;
                document.getElementById("producto_sec").style.border = "1px solid";
                document.getElementById('producto_sec').style.borderColor="red";
            }
        }
        if(time_est == '0' || time_est == ''){
            texto+='- Defina un tiempo de trabajo \n';
            v = 1;
            document.getElementById("time_est").style.border = "1px solid";
            document.getElementById('time_est').style.borderColor="red";
        }
    }
    //console.log(band3);
    cadena1 = "idRegistro=" + idRegistro3 +
                  "&band=" + 21;
    if(v == 1){
        //alert(texto);
        alert('Complete los campos marcados');
        $('#loader').hide();
        $('#modal_body').show();
    }else if(v == 0){
        document.getElementById('pila').style.borderColor="#ccc";
        document.getElementById('producto_sec').style.borderColor="#ccc";
        document.getElementById('equipo').style.borderColor="#ccc";
        document.getElementById('zona_acopio').style.borderColor="#ccc";
        document.getElementById('tempo_clasif').style.borderColor="#ccc";
        document.getElementById('n_paladas').style.borderColor="#ccc";
        document.getElementById('TotalTM').style.borderColor="#ccc";
        document.getElementById('TempMaquinaEst').style.borderColor="#ccc";
        //
        document.getElementById('destino').style.borderColor="#ccc";
        $.ajax({
            type: "POST",
            url: "../modelo/consu_subactividades_patio.php",
            data: cadena1,
            success: function (r) {
                console.log(r);
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
                //console.log(cadena);
                $.ajax({
                    type: "POST",
                    url: "../modelo/consu_subactividades_patio.php",
                    data: cadena,
                    success: function (r) {
                        //console.log(r);
                        if (r == 1){
                            alert('El tiempo que intenta registrar es mayor al tiempo disponible para distribución');
                        }else if(r == 2){
                            alert('Las TM de APILAMIENTO superan a las TM ALIMENTADAS');
                        }else if(r == 3){
                            alert('Emergency');
                        }else{
                            $('#n_vehiculos').val('');
                            $('#TempXvehiculo').val('');
                            $('#n_paladas').val('');
                            $('#TempXpalada').val('');
                            $('#TM_Palada').val('');
                            $('#TempReEst').val('');
                            $('#TempMaquinaEstVehiculo').val('');
                            $('#TempMaquinaEst').val('');
                            $('#TotalTM').val('');
                            $('#TotalTM_cargue').val('');
                            $('#idDistribucion').val('');
                            $('#plantilla_paladas').hide();
                            $('#plantilla_cargue').hide();
                            $('#plantilla_apila_entra').hide();
                            $('#time_est').val('');
                            $('#totalize_tm').val('');
                            $('#calculado').hide();
                            $('#calculado_cargue').hide();
                            $('#bordePlantilla').hide();
                            $('#div_equipos').hide();
                            $('#div_equipos1').hide();
                            $('#div_clasif').hide();
                            FaltaAsignar();
                            asignados();
                            actividad(actividad3);
                            GuardarObservaciones();
                        }
                        $('#loader').hide();
                        $('#modal_body').show();
                    }
                });
            }
        });
    }
}

function FaltaAsignar(){
    idRegistro = $('#registroHorometro').val();
    band = 1;
    cadena = "idRegistro=" + idRegistro +
            "&band=" + band;

    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio.php",
        data: cadena,
        success: function (r) {
            //console.log(r);
            d = r.split("||");
            $('#ValoresAsignados').val(d[1]);
            if ($('#ValoresAsignados').val() == 1){
                $('#AgregarHorometro').hide();
            }else{
                $('#AgregarHorometro').show();
            }
            $('#observacionesTiquete').val(d[2]);
            $('#idPatioLejia').val(d[3]);
            $('#form').html(d[5]);
            if (d[1] == 2){
                document.getElementById('FaltaAsignar').innerHTML='<h5><b>'+d[0]+'</b></h5>';
                $('#volverStandby').show();
            }else if(d[1] == 3){
                document.getElementById('FaltaAsignar').innerHTML='<h5><b>'+d[0]+'</b></h5>';
                $('#volverStandby').show();
            }else{
                document.getElementById('FaltaAsignar').innerHTML='<h5>'+d[0]+'</h5>';
                $('#volverStandby').hide();
            }
            //console.log(d[4]);
            if(d[4] != ""){
                const DatosZona = JSON.parse(d[4]);
                let bodyZonas = '';
                DatosZona.forEach(zone => {
                  bodyZonas += `
                    <option value="${zone.idZona}">${zone.Zona}</option>`
                });
                $('#zona_acopio').html(bodyZonas);
            }
        }
    });
}

function seleccionado_asignar(valor){
    if($('#actividad').val() == '3F001906-CFF3-437F-A4C5-67CDADDFD904'){
        document.getElementById('bordePlantilla').innerHTML='<center><h4>Apilamiento Entradas</h4></center>';
        $('#plantilla_apila_entra').show();
        $('#bordePlantilla').show();
        $('#plantilla_cargue').hide();
        $('#plantilla_paladas').hide();
        $('#btn_calcularData1').hide();
        $('#btn_calcularData').hide();
        //$('#btn_calcularModificar').show();
        $('#btn_calcularModificar').hide();
        $('#btn_calcularModificar1').hide();
        $('#GrabarDatos').hide();
        $('#GrabarDatos1').hide();
        $('#GrabarDatos2').hide();
        $('#ModificarDatos').hide();
        $('#ModificarDatos1').hide();
        $('#ModificarDatos2').hide();
        $('#div_equipos').hide();
        $('#div_equipos1').hide();
        $('#div_clasif').hide();
    }else if (($('#'+valor+'2').val() == 'ALIMENTAR') || ($('#'+valor+'2').val() == 'APILAR')){
        $('#div_equipos').show();
        $('#div_equipos1').show();
        if (($('#'+valor+'2').val() == 'ALIMENTAR')){
            $('#div_clasif').show();
            $('#ProductoObjetivoLabel').show();
            $('#ProductoObjetivoSelect').show();
        }else{
            $('#div_clasif').hide();
            $('#ProductoObjetivoLabel').hide();
            $('#ProductoObjetivoSelect').hide();
        }
        document.getElementById('bordePlantilla').innerHTML='<center><h4>Distribución de tiempos por clasificación</h4></center>';
        $('#bordePlantilla').show();
        $('#plantilla_cargue').hide();
        $('#plantilla_paladas').show();
        $('#plantilla_apila_entra').hide();
        $('#btn_calcularData1').hide();
        $('#btn_calcularData').hide();
        //if ($('#ValoresAsignados').val() == 1){
            $('#btn_calcularModificar').show();
        /*}else{
            $('#btn_calcularModificar').hide();
        }*/
        $('#btn_calcularModificar1').hide();
        $('#GrabarDatos').hide();
        $('#GrabarDatos1').hide();
        $('#GrabarDatos2').hide();
        $('#ModificarDatos').hide();
        $('#ModificarDatos1').hide();
        $('#ModificarDatos2').hide();
    }else if (($('#'+valor+'2').val() == 'CARGAR DESPACHO')){
        document.getElementById('bordePlantilla').innerHTML='<center><h4>Distribución de tiempos por cargue</center></h4>';
        $('#bordePlantilla').show();
        $('#plantilla_paladas').hide();
        $('#plantilla_cargue').show();
        $('#plantilla_apila_entra').hide();
        $('#btn_calcularData1').hide();
        $('#btn_calcularData').hide();
        $('#btn_calcularModificar').hide();
        //if ($('#ValoresAsignados').val() == 1){
            $('#btn_calcularModificar1').show();
        /*}else{
            $('#btn_calcularModificar').hide();
        }*/
        $('#GrabarDatos').hide();
        $('#GrabarDatos1').hide();
        $('#GrabarDatos2').hide();
        $('#ModificarDatos').hide();
        $('#ModificarDatos1').hide();
        $('#ModificarDatos2').hide();
        $('#div_equipos').hide();
        $('#div_equipos1').hide();
        $('#div_clasif').hide();
    }
    sub =  $('#'+valor+'1').val();
    $('#sub_actividad').val(sub);
    /*$('#'+valor).removeClass('btn-default');
    $('#'+valor).addClass('btn-success');*/
    idDistribucion = valor;
    band = 6;
    cadena = "idDistribucion=" + idDistribucion +
            "&band=" + band;

    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio.php",
        data: cadena,
        success: function (r) {
            band = 23;
            idRegistro = $('#registroHorometro').val();
            $.post("../modelo/consu_subactividades_patio.php", {band: band, seleccionado: valor, idRegistro: idRegistro}, 
                function(mensaje){
                    //console.log(mensaje);
                    mensaje1 = mensaje.split("||");
                    $('#pila').html(mensaje1[0]).fadeIn();
                    $('#producto').html(mensaje1[1]).fadeIn();
                    $('#equipo').html(mensaje1[2]).fadeIn();
                    $('#zona_acopio').html(mensaje1[3]).fadeIn();
                    $('#destino').html(mensaje1[4]).fadeIn();
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    $('#producto_sec').val(mensaje1[6]);
                    /*$('#').html(mensaje1[]).fadeIn();
                    $('#').html(mensaje1[]).fadeIn();
                    $('#').html(mensaje1[]).fadeIn();
                    $('#').html(mensaje1[]).fadeIn();*/
                });
            d = r.split("||");
            for(i=0;i<document.frm.elements.length; i++)
            {  if(document.frm.elements[i].type=="button")
                {   $('#'+document.frm.elements[i].value).removeClass('btn-success'); 
                    $('#'+document.frm.elements[i].value).addClass('btn-default'); 
                }
            }
            if (d[0] == 1){
                $('#n_paladas').val(d[1]);
                $('#TM_Palada').val(d[2]);
                $('#TempXpalada').val(d[3]);
                $('#TempReEst').val(d[4]);
                $('#TempMaquinaEst').val(d[5]);
                $('#idDistribucion').val(d[6]);
                $('#idHorometro').val(d[8]);
                $('#tempo_clasif').val(d[9]);
                //total = d[1]*d[2];
                $('#TotalTM').val(d[7]);
                document.getElementById('bordePlantilla').innerHTML='<center><h4>Distribución de tiempos por clasificación</h4></center>';
                $('#bordePlantilla').show();
                $('#plantilla_paladas').show();
                $('#plantilla_cargue').hide();
                $('#plantilla_apila_entra').hide();
                $('#calculado').show();
                $('#calculado_cargue').hide();
                $('#GrabarDatos').hide();
                $('#GrabarDatos1').hide();
                $('#GrabarDatos2').hide();
                //if ($('#ValoresAsignados').val() == 1){
                    $('#ModificarDatos').show();
                /*}else{
                    $('#ModificarDatos').hide();
                }*/
                $('#ModificarDatos1').hide();
                $('#ModificarDatos2').hide();
                //$('#div_equipos').hide();
                //$('#div_equipos1').hide();
                //$('#div_clasif').hide();
            }else if (d[0] == 2){
                $('#n_vehiculos').val(d[1]);
                $('#TempReEstVehiculo').val(d[2]);
                $('#TempXvehiculo').val(d[3]);
                $('#TempMaquinaEstVehiculo').val(d[4]);
                $('#idDistribucion').val(d[5]);
                $('#TotalTM_cargue').val(d[6]);
                $('#idHorometro').val(d[7]);
                document.getElementById('bordePlantilla').innerHTML='<center><h4>Distribución de tiempos por Cargue</h4></center>';
                $('#bordePlantilla').show();
                $('#plantilla_paladas').hide();
                $('#plantilla_cargue').show();
                $('#plantilla_apila_entra').hide();
                $('#calculado').hide();
                $('#calculado_cargue').show();
                $('#GrabarDatos').hide();
                $('#GrabarDatos1').hide();
                $('#GrabarDatos2').hide();
                $('#ModificarDatos').hide();
                $('#div_equipos').hide();
                $('#div_equipos1').hide();
                $('#div_clasif').hide();
                //if ($('#ValoresAsignados').val() == 1){
                    $('#ModificarDatos1').show();
                /*}else{
                    $('#ModificarDatos1').hide();
                }*/
                $('#ModificarDatos2').hide();
            }else if(d[0] == 3){
                $('#time_est').val(d[1]);
                $('#idDistribucion').val(d[2]);
                $('#totalize_tm').val(d[3]);
                $('#idHorometro').val(d[4]);
                document.getElementById('bordePlantilla').innerHTML='<center><h4>Apilamiento Entradas</h4></center>';
                $('#plantilla_apila_entra').show();
                $('#bordePlantilla').show();
                $('#plantilla_cargue').hide();
                $('#plantilla_paladas').hide();
                $('#btn_calcularData1').hide();
                $('#btn_calcularData').hide();
                //$('#btn_calcularModificar').show();
                $('#btn_calcularModificar').hide();
                $('#btn_calcularModificar1').hide();
                $('#GrabarDatos').hide();
                $('#GrabarDatos1').hide();
                $('#GrabarDatos2').hide();
                $('#ModificarDatos').hide();
                $('#ModificarDatos1').hide();
                $('#div_equipos').hide();
                $('#div_equipos1').hide();
                $('#div_clasif').hide();
                //if ($('#ValoresAsignados').val() == 1){
                    $('#ModificarDatos2').show();
                /*}else{
                    $('#ModificarDatos2').hide();
                }*/
            }
        }
    });
}

function ModificarDatos(r){
    $('#loader').show();
    $('#modal_body').hide();
    if (r == 'Clasificar'){
        idDistribucion = $('#idDistribucion').val();
        idHorometro = $('#idHorometro').val();
        idRegistro4 = $('#registroHorometro').val();
        producto4 = $('#producto_sec').val();
        actividad4 = $('#actividad').val();
        sub_actividad4 = $('#sub_actividad').val();
        N_paladas4 = $('#n_paladas').val();
        TM_Palada4 = $('#TM_Palada').val();
        TotalTM4 = $('#TotalTM').val();
        /*TempXpalada4 = $('#TempXpalada').val();
        TempReEst4 = $('#TempReEst').val();*/
        TempMaquinaEst4 = $('#TempMaquinaEst').val();
        band4 = 7;

        pila4 = $('#pila').val();
        equipo4 = $('#equipo').val();
        zona4 = $('#zona_acopio').val();
        tempo_clasif4 = $('#tempo_clasif').val();
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
        idDistribucion = $('#idDistribucion').val();
        idHorometro = $('#idHorometro').val();
        idRegistro4 = $('#registroHorometro').val();
        producto4 = $('#producto_sec').val();
        actividad4 = $('#actividad').val();
        sub_actividad4 = $('#sub_actividad').val();
        N_vehiculos4 = $('#n_vehiculos').val();
        TempXvehiculo4 = $('#TempXvehiculo').val();
        TempReEstVehiculo4 = $('#TempReEstVehiculo').val();
        TempMaquinaEstVehiculo4 = $('#TempMaquinaEstVehiculo').val();
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
        idDistribucion = $('#idDistribucion').val();
        idHorometro = $('#idHorometro').val();
        idRegistro4 = $('#registroHorometro').val();
        producto4 = $('#producto_sec').val();
        actividad4 = $('#actividad').val();
        sub_actividad4 = $('#sub_actividad').val();
        time_est = $('#time_est').val();
        totalize_tm = $('#totalize_tm').val();
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
            url: "../modelo/consu_subactividades_patio.php",
            data: cadena,
            success: function (r) {
                //console.log(r);
                if (r == 0 || r == null){
                    alert('El tiempo que intenta registrar es mayor al tiempo disponible para distribución');
                }else{
                    $('#idDistribucion').val('');
                    $('#n_vehiculos').val('');
                    $('#TempXvehiculo').val('');
                    $('#n_paladas').val('');
                    $('#TempXpalada').val('');
                    $('#TM_Palada').val('');
                    $('#TempReEst').val('');
                    $('#TempMaquinaEstVehiculo').val('');
                    $('#TempMaquinaEst').val('');
                    $('#TotalTM').val('');
                    $('#TotalTM_cargue').val('');
                    $('#plantilla_paladas').hide();
                    $('#plantilla_cargue').hide();
                    $('#calculado').hide();
                    $('#calculado_cargue').hide();
                    $('#bordePlantilla').hide();
                    $('#plantilla_apila_entra').hide();
                    $('#time_est').val('');
                    $('#totalize_tm').val('');
                    $('#div_equipos').hide();
                    $('#div_equipos1').hide();
                    $('#div_clasif').hide();
                    FaltaAsignar();
                    asignados();
                    actividad(actividad4);
                }
                $('#loader').hide();
                $('#modal_body').show();
            }
        });
    }
}
function MostrarOcultar(r){
    if (r == 1){
        document.getElementById('act').innerHTML='<center><h4>Actividades</h4></center>';
        $('#checked').hide();
        $('#divDescuento').hide();
        $('#divStandby').hide();
        $('#volverAct').hide();
        //if($('#ValoresAsignados').val() == 2 || $('#ValoresAsignados').val() == 3){
            $('#volverStandby').show();
        /*}else{
            $('#volverStandby').hide();
        }*/
        $('#volverDesc').show();
        $('#FaltaAsignar').show();
        $('#ActDetallado').show();
        $('#divProducto').hide();
        $('#bordeAsignados').hide();
        $('#divAsignados').hide();
        $('#divAsignados1').hide();
        $('#borde').hide();
        $('#div_subactividad').hide();
        $('#bordePlantilla').hide();
        $('#plantilla_cargue').hide();
        $('#calculado_cargue').hide();
        $('#plantilla_paladas').hide();
        $('#plantilla_apila_entra').hide();
        $('#GrabarDatos2').hide();
        $('#ModificarDatos2').hide();
        $('#calculado').hide();
        $('#GrabarDatos').hide();
        $('#GrabarDatos1').hide();
        $('#ModificarDatos').hide();
        $('#ModificarDatos1').hide();
        $('#ModificarDatos2').hide();
        $('#div_equipos').hide();
        $('#div_equipos1').hide();
        $('#div_clasif').hide();
    }else if (r == 2){
        for(i=0;i<document.form.elements.length; i++)
        {  if(document.form.elements[i].type=="button")
            {   $('#'+document.form.elements[i].value).removeClass('btn-success'); 
                $('#'+document.form.elements[i].value).addClass('btn-default');
            }
        }
        $('#div_red').hide();
        document.getElementById('act').innerHTML='<center><h4>Descuentos</h4></center>';
        $('#checked').hide();
        $('#divDescuento').show();
        $('#divStandby').hide();
        $('#volverAct').show();
        $('#volverDesc').hide();
        $('#volverStandby').hide();
        $('#FaltaAsignar').show();
        $('#ActDetallado').hide();
        $('#divProducto').hide();
        $('#bordeAsignados').hide();
        $('#divAsignados').hide();
        $('#divAsignados1').hide();
        $('#borde').hide();
        $('#div_subactividad').hide();
        $('#bordePlantilla').hide();
        $('#plantilla_cargue').hide();
        $('#calculado_cargue').hide();
        $('#plantilla_paladas').hide();
        $('#plantilla_apila_entra').hide();
        $('#GrabarDatos2').hide();
        $('#ModificarDatos2').hide();
        $('#calculado').hide();
        $('#GrabarDatos').hide();
        $('#GrabarDatos1').hide();
        $('#ModificarDatos').hide();
        $('#ModificarDatos1').hide();
        $('#ModificarDatos2').hide();
        $('#div_equipos').hide();
        $('#div_equipos1').hide();
        $('#div_clasif').hide();
        $('#divTM_despacho').hide();
        DescuentosAsignados();
    }else if (r == 3){
        for(i=0;i<document.form.elements.length; i++)
        {  if(document.form.elements[i].type=="button")
            {   $('#'+document.form.elements[i].value).removeClass('btn-success'); 
                $('#'+document.form.elements[i].value).addClass('btn-default');
            }
        }
        document.getElementById('act').innerHTML='<center><h4>Standby</h4></center>';
        $('#checked').hide();
        $('#divDescuento').hide();
        $('#divStandby').show();
        $('#volverAct').show();
        $('#volverDesc').hide();
        $('#volverStandby').hide();
        $('#FaltaAsignar').show();
        $('#ActDetallado').hide();
        $('#divProducto').hide();
        $('#bordeAsignados').hide();
        $('#divAsignados').hide();
        $('#divAsignados1').hide();
        $('#borde').hide();
        $('#div_subactividad').hide();
        $('#bordePlantilla').hide();
        $('#plantilla_cargue').hide();
        $('#calculado_cargue').hide();
        $('#plantilla_paladas').hide();
        $('#plantilla_apila_entra').hide();
        $('#GrabarDatos2').hide();
        $('#ModificarDatos2').hide();
        $('#calculado').hide();
        $('#GrabarDatos').hide();
        $('#GrabarDatos1').hide();
        $('#ModificarDatos').hide();
        $('#ModificarDatos1').hide();
        $('#ModificarDatos2').hide();
        $('#div_equipos').hide();
        $('#div_equipos1').hide();
        $('#div_clasif').hide();
        StandbyAsignados();
    }
    
}

function AplicarDescuento(){
    idRegistro = $('#registroHorometro').val();
    ValorDescuento = $('#ValorDescuento').val();
    motivoDescuento = $('#motivoDescuento').val();
    TotalTM_Despacho = $('#TotalTM_Despacho').val();
    usuario = $('#usuario').val();
    band = 9;
    cadena = "idRegistro=" + idRegistro +
            "&ValorDescuento=" + ValorDescuento +
            "&motivoDescuento=" + motivoDescuento +
            "&usuario=" + usuario +
            "&TotalTM_Despacho=" + TotalTM_Despacho +
            "&band=" + band;

    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio.php",
        data: cadena,
        success: function (r) {
            //console.log(r);
            if (r == 1){
                $('#checked').show();
                DescuentosAsignados();
                FaltaAsignar();
            }else if (r == 2){
                alert('El descuento que intentas realizar es mayor al tiempo disponible para distribución');
            }else{
                $('#checked').hide();
            }
        }
    });
}

function AplicarStandby(){
    idRegistro = $('#registroHorometro').val();
    ValorStandby = $('#ValorStandby').val();
    motivoStandby = $('#motivoStandby').val();
    usuario = $('#usuario').val();
    band = 17;
    cadena = "idRegistro=" + idRegistro +
            "&ValorStandby=" + ValorStandby +
            "&motivoStandby=" + motivoStandby +
            "&usuario=" + usuario +
            "&band=" + band;
    //console.log(motivoStandby);
    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio.php",
        data: cadena,
        success: function (r) {
            //console.log(r);
            if (r == 1){
                $('#checked').show();
                StandbyAsignados();
                FaltaAsignar();
            }else if (r == 2){
                alert('El Standby que intentas realizar es mayor al tiempo disponible para distribución');
            }else{
                $('#checked').hide();
            }
        }
    });
}

function StandbyAsignados(){
    idRegistro2 = $('#registroHorometro').val();
    usuario2 = $('#usuario').val();
    band5 = 16;
    cadena = "idRegistro=" + idRegistro2 +
            "&usuario=" + usuario2 +
            "&band=" + band5;
    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio.php",
        data: cadena,
        success: function (r){
            //alert(r);
            if (r != 0){
                data = r.split("||");
                $('#ValorStandby').val(data[0]);
                $('#motivoStandby').val(data[1]);
                $('#btn_apliStandby').hide();
                $('#btn_modStandby').show();
            }else{
                $('#ValorStandby').val('');
                $('#motivoStandby').val('');
                $('#btn_apliStandby').show();
                $('#btn_modStandby').hide();
            }
        }
    });
}

function DescuentosAsignados(){
    idRegistro1 = $('#registroHorometro').val();
    usuario1 = $('#usuario').val();
    band1 = 10;
    cadena = "idRegistro=" + idRegistro1 +
            "&usuario=" + usuario1 +
            "&band=" + band1;
    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            if (r != 0){
                //if ($('#ValoresAsignados').val() == 1){
                    $('#btn_modDesc').show();
                    //console.log(1);
                /*}else if ($('#ValoresAsignados').val() == 3){
                    //console.log(3);
                    $('#btn_apliDesc').hide();
                    $('#btn_modDesc').show();
                }else{
                    //console.log('otro');
                    $('#btn_modDesc').hide();
                }*/
                $('#btn_apliDesc').hide();
                data = r.split("||");
                $('#ValorDescuento').val(data[1]);
                $('#motivoDescuento').val(data[2]);
                $('#TotalTM_Despacho').val(data[3]);
            }else{
                //if ($('#ValoresAsignados').val() == 1){
                    $('#btn_apliDesc').show();
                /*}else if ($('#ValoresAsignados').val() == 3){
                    $('#btn_apliDesc').show();
                }else{
                    $('#btn_apliDesc').hide();
                }*/
                $('#btn_modDesc').hide();
            }
        }
    });
}

function ModificarDescuento(){
    idRegistro = $('#registroHorometro').val();
    ValorDescuento = $('#ValorDescuento').val();
    motivoDescuento = $('#motivoDescuento').val();
    TotalTM_Despacho = $('#TotalTM_Despacho').val();
    band = 11;
    cadena = "idRegistro=" + idRegistro +
            "&ValorDescuento=" + ValorDescuento +
            "&motivoDescuento=" + motivoDescuento +
            "&TotalTM_Despacho=" + TotalTM_Despacho +
            "&band=" + band;

    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            if (r == 1){
                $('#checked').show();
                DescuentosAsignados();
                FaltaAsignar();
            }else if (r == 2){
                alert('El descuento que intentas realizar es mayor al tiempo disponible para distribución');
            }else{
                $('#checked').hide();
            }
        }
    });
}

function ModificarStandby(){
    idRegistro = $('#registroHorometro').val();
    ValorStandby = $('#ValorStandby').val();
    motivoStandby = $('#motivoStandby').val();
    band = 18;
    cadena = "idRegistro=" + idRegistro +
            "&ValorStandby=" + ValorStandby +
            "&motivoStandby=" + motivoStandby +
            "&band=" + band;

    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            if (r == 1){
                $('#checked').show();
                StandbyAsignados();
                FaltaAsignar();
            }else if (r == 2){
                alert('El descuento que intentas realizar es mayor al tiempo disponible para distribución');
            }else{
                $('#checked').hide();
            }
        }
    });
}

function cerrar_tiempos(){
    idRegistro = $('#registroHorometro').val();
    band = 22;
    cadena = "idRegistro=" + idRegistro +
            "&band=" + band;
    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio.php",
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

function buscar_alimentacion(){
    var id_Registro = $('#registroHorometro').val();
    var band = 24;
    cadena = "idRegistro=" + id_Registro +
            "&band=" + band;
    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio.php",
        data: cadena,
        success: function (r){
            console.log(r);
            //mensaje1 = mensaje.split("||");
            $('#producto').html(r).fadeIn();
        }
    });
}