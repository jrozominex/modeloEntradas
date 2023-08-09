$(document).ready(function () {
    $('#man_pro').hide();
    $('#title').hide();
    $('#ActMantto').hide();
    $('#div_horo_inicial').hide();
    $('#div_life_machine').hide();
    $('#div_mantto').hide();
    $('#divReporte').hide();
    $('#divMantto').hide();
});

function cambiarDiv(val){
    if (val == 'r'){
        $('#'+val).removeClass('btn-default');
        $('#'+val).addClass('btn-primary');
        $('#m').removeClass('btn-primary');
        $('#m').addClass('btn-default');
        $('#divReporte').show();
        $('#divMantto').hide();
    }else if(val == 'm'){
        $('#'+val).removeClass('btn-default');
        $('#'+val).addClass('btn-primary');
        $('#r').removeClass('btn-primary');
        $('#r').addClass('btn-default');
        $('#divReporte').hide();
        $('#divMantto').show();
    }
}

function Seleccionar_todo(){
    if($('#var').val() == 0){
        for(i=0;i<document.form3.elements.length;i++)
        {   if(document.form3.elements[i].type=="checkbox")
            {   document.form3.elements[i].checked=1;   }
        }
        $('#var').val('1');
    }else if($('#var').val() == 1){
        for(i=0;i<document.form3.elements.length;i++)
        {   if(document.form3.elements[i].type=="checkbox")
            {   document.form3.elements[i].checked=0;   }
        }
        $('#var').val('0');
    }
}

function Tipo_maquina(){
    var tipo_maquina = document.getElementById("maquinaria").value;
    if (tipo_maquina == 1){
        $('#select_cargador').show();
    }else{
        $('#select_cargador').hide();
    }
}

function maquina (vari){
    var band = 4;
    var valor_maquina = vari;
    cadena = "valor_maquina=" + valor_maquina +
            "&band=" + band;
    $.ajax({
        type: "POST",
        url: "../modelo/mantenimiento.php",
        data: cadena,
        success: function (a) {
            $('#div_horo_inicial').show();
            $('#div_life_machine').show();
            $('#div_mantto').show();
        	data = a.split('||');
        	$('#horometro_inicial').val(data[0]);
        	$('#horometro_vida').val(data[1]);
        	$('#horometro_mantto').val(data[2]);
            if (data[3] != 0){
                alert('Hay una actividad sin finalizar en esta maquinaria');
                self.location = "mantenimiento.php";
                $('#title').hide();
                $('#ActMantto').hide();
            }else{
                $('#title').show();
                $('#ActMantto').show();
            }
        }
    });
}

function Pasos(rol){
    if (rol == 'mecanico'){
        var aplica = new Array();
        var inputs = new Array();
        var v = 0;
        for(i=0;i<document.form3.elements.length;i++)
        {   if(document.form3.elements[i].type=="checkbox")
            {   if(document.form3.elements[i].checked==1)
                {   aplica[i] = (document.form3.elements[i].value);   
                    /*inputs[i] = $('#'+document.form3.elements[i].value+'i').val();
                    if ($('#'+document.form3.elements[i].value+'i').val() == null){
                        v++;
                    }*/
                }
            }
        }
        if(aplica.length != 0){
            //if(v == 0){
                cadena =aplica.toString();
                $('#seleccionados').val(cadena);
                //cadena_inp = inputs.toString();
                textareai = $('#textareai').val();
                //$('#personal').val(cadena_inp);
                id_mantto = $('#id_mantto').val();
                band = 1;
                //console.log(cadena);
                //console.log(cadena_inp);
                //if ($('#realizado_por').val() == $('#Password_usu').val()){
                    id_usuario = $('#id_usuario').val();
                    observaciones = $('#observaciones').val();
                    cadena = "band=" + band +
                            "&id_mantto=" + id_mantto +
                            "&SubActividad=" + cadena +
                            "&id_usuario=" + id_usuario +
                            "&observaciones=" + observaciones +
                            //"&Personal=" + cadena_inp;
                            "&Personal=" + textareai;
                    $.ajax({
                        type: "POST",
                        url: "../modelo/mantenimiento.php",
                        data: cadena,
                        success: function (r) {
                            console.log(r);
                            if (r == 1){
                                alert("Se finalizó correctamente");
                                redireccionar();
                            }
                        }
                    });
                /*}else{
                    alert('La contraseña es incorrecta');
                }*/
            /*}else{
                alert('Coloque un responsable para la actividad');
            }*/
        }else{
            alert('No ha seleccionado nada');
        }
    }else if (rol == 'jefe_mantto'){
        if ($('#verificado_por').val() == $('#Password_usu').val()){
            band = 2;
            id_mantto = $('#id_mantto').val();
            id_usuario = $('#id_usuario').val();
            observaciones = $('#observaciones').val();
            cadena = "band=" + band +
                    "&id_mantto=" + id_mantto +
                    "&id_usuario=" + id_usuario +
                    "&observaciones=" + observaciones;
            $.ajax({
                type: "POST",
                url: "../modelo/mantenimiento.php",
                data: cadena,
                success: function (r) {
                    console.log(r);
                    if (r == 1){
                        alert("Se finalizó correctamente");
                        redireccionar();
                    }
                }
            });
        }else{
            alert('La contraseña es incorrecta');
        }
    }else if(rol == 'operador'){
        if ($('#aprobado_por').val() == $('#Password_usu').val()){
            band = 3;
            id_mantto = $('#id_mantto').val();
            id_usuario = $('#id_usuario').val();
            observaciones = $('#observaciones').val();
            cadena = "band=" + band +
                    "&id_mantto=" + id_mantto +
                    "&id_usuario=" + id_usuario +
                    "&observaciones=" + observaciones;
            $.ajax({
                type: "POST",
                url: "../modelo/mantenimiento.php",
                data: cadena,
                success: function (r) {
                    //console.log(r);
                    if (r == 1){
                        alert("Se finalizó correctamente");
                        redireccionar();
                    }
                }
            });
        }else{
            alert('La contraseña es incorrecta');
        }
    }else if (rol == 'Mtto_rutina'){
        var aplica = new Array();
        var inputs = new Array();
        var v = 0;
        for(i=0;i<document.form3.elements.length;i++)
        {   if(document.form3.elements[i].type=="checkbox")
            {   if(document.form3.elements[i].checked==1)
                {   aplica[i] = (document.form3.elements[i].value);   
                    /*inputs[i] = $('#'+document.form3.elements[i].value+'i').val();
                    if ($('#'+document.form3.elements[i].value+'i').val() == null){
                        v++;
                    }*/
                }
            }
        }
        if(aplica.length != 0){
            //if(v == 0){
                cadena =aplica.toString();
                $('#seleccionados').val(cadena);
                //cadena_inp = inputs.toString();
                //$('#personal').val(cadena_inp);
                textareai = $('#textareai').val();
                id_mantto = $('#id_mantto').val();
                band = 8;
                //console.log(cadena);
                //console.log(cadena_inp);
                if ($('#aprobado_por').val() == $('#Password_usu').val()){
                    id_usuario = $('#id_usuario').val();
                    observaciones = $('#observaciones').val();
                    cadena = "band=" + band +
                            "&id_mantto=" + id_mantto +
                            "&SubActividad=" + cadena +
                            "&id_usuario=" + id_usuario +
                            "&observaciones=" + observaciones +
                            //"&Personal=" + cadena_inp;
                            "&Personal=" + textareai;
                    $.ajax({
                        type: "POST",
                        url: "../modelo/mantenimiento.php",
                        data: cadena,
                        success: function(r){
                            alert(r);
                            if (r == 1){
                                alert("Se finalizó correctamente");
                                redireccionar();
                            }
                        }
                    });
                }else{
                    alert('La contraseña es incorrecta');
                }
            /*}else{
                alert('Coloque un responsable para la actividad');
            }*/
        }else{
            alert('No ha seleccionado nada');
        }
    }
}

function AggMantenimiento(){
    var maquina = $('#cargador').val();
    var horo_inicial = $('#horometro_inicial').val();
    var fecha_horaIni = $('#FechaHora_ini').val();
    var idUsuario = $('#idUsuario').val();
    var aplica = new Array();
    var v = 0;
    for(i=0;i<document.form3.elements.length;i++)
    {   if(document.form3.elements[i].type=="checkbox")
        {   if(document.form3.elements[i].checked==1)
            {   aplica[i] = (document.form3.elements[i].value);   
                if (document.form3.elements[i].value == 2    || document.form3.elements[i].value == 3 || document.form3.elements[i].value == 4){
                    v = 1;
                }
            }
        }
    }

    if(aplica.length != 0){
        cadena =aplica.toString();
        $('#TipoMantto').val(cadena);
        tipo = $('#TipoMantto').val();
    }else{
        alert('No ha seleccionado nada');
    }
    if (v == 1){
        band = 5;
        cadena = "band=" + band +
            "&maquina=" + maquina +
            "&horo_inicial=" + horo_inicial;
        $.ajax({
            type: "POST",
            url: "../modelo/mantenimiento.php",
            data: cadena,
            success: function (r){
                //console.log(r);
                if (r != 0){
                    alertify.confirm('Mantenimiento Programado', '¿Está seguro de iniciar un mantenimiento programado?       Faltan: '+r+' horas para el mantenimiento',
                    function () {
                        band = 6;
                        cadena = "horo_inicial=" + horo_inicial +
                                "&maquina=" + maquina +
                                "&fecha_horaIni=" + fecha_horaIni +
                                "&idUsuario=" + idUsuario +
                                "&tipoMantto=" + tipo +
                                "&band=" + band;
                        $.ajax({
                            type: "POST",
                            url: "../modelo/mantenimiento.php",
                            data: cadena,
                            success: function (r){
                                //console.log(r);
                                if (r == 1) {
                                    alert("Se registró correctamente");
                                    redireccionar();
                                } else if(r == 3) {
                                    alert("Hay una actividad sin finalizar del cargador");
                                } else if(r == 4) {
                                    alert("El horometro que intenta ingresar no coincide con el de la maquina");
                                }else {
                                    alert("Falta completar datos para el horometro");
                                }
                            }
                        });
                    }
                    , function () {
                        alertify.error('Se canceló')
                    });
                }
            }
        });
    }else{
        band = 6;
        tipo = $('#TipoMantto').val();
        //console.log(tipo);
        cadena = "horo_inicial=" + horo_inicial +
                "&maquina=" + maquina +
                "&fecha_horaIni=" + fecha_horaIni +
                "&idUsuario=" + idUsuario +
                "&tipoMantto=" + tipo +
                "&band=" + band;
        $.ajax({
            type: "POST",
            url: "../modelo/mantenimiento.php",
            data: cadena,
            success: function (r){
                //console.log(r);
                if (r == 1) {
                    alert("Se registró correctamente");
                    redireccionar();
                } else if(r == 3) {
                    alert("Hay una actividad sin finalizar del cargador");
                } else if(r == 4) {
                    alert("El horometro que intenta ingresar no coincide con el de la maquina");
                }else {
                    alert("Falta completar datos para el horometro");
                }
            }
        });
    }
}

function VerMtto(datos){
    data = datos.split("||");
    $('#idMantto').val(data[0]);
    $('#fecha_inicMtto').val(data[1]);
    $('#horo_iniciaMtto').val(data[2]);
    $('#MaquinariaMtto').val(data[6]);
    $('#cargadorMtto').val(data[3]+" - "+data[4]);
    $('#propietarioMtto').val(data[5]);
    $('#operadorMtto').val(data[7]);
    //$('#registroHorometroMtto').val(data[8]);
    $('#idMaquinaria').val(data[8]);

    fecha1 = $('#valoresFechaInput').val();
    fecha_inic1 = $('#fecha_inicMtto').val();
    horometro_inicialMtto1 = $('#horo_iniciaMtto').val();
    var parse1 = parseFloat(horometro_inicialMtto1);
    var n1 = moment(fecha_inic1); 
    //console.log(n1);
    var m1 = moment(fecha1); 
    //console.log(m);
    var minutes_inic1 = (n1.hour()*60) + n1.minute(); 
    var minutes_fin1 = (m1.hour()*60) + m1.minute(); 
    //console.log(minutes_inic);
    //console.log(minutes_fin);
    var total1 = (minutes_fin1-minutes_inic1)/60;
    var a1 = parse1 + total1;
    var round1 = Math.round(a1* 10) / 10;
    //console.log(total1);
    //document.getElementById('horo_fina').placeholder= horometro_inicial+' + '+round+' Aprox.';
    document.getElementById('horo_finaMtto').placeholder= round1+' Aprox.';
    $('#horo_finaMtto').val(round1);
}

function FinalizarMtto(){
    id_mantto = $('#idMantto').val();
    fecha_inicMtto = $('#fecha_inicMtto').val();
    fechaMtto = $('#fechaMtto').val();
    horo_iniciaMtto = $('#horo_iniciaMtto').val();
    horo_finaMtto = $('#horo_finaMtto').val();
    idMaquinaria = $('#idMaquinaria').val();

    band = 7;
    cadena = "id_mantto=" + id_mantto +
            "&fechaMtto=" + fechaMtto +
            "&horo_iniciaMtto=" + horo_iniciaMtto +
            "&horo_finaMtto=" + horo_finaMtto +
            "&idMaquinaria=" + idMaquinaria +
            "&band=" + band;

    $.ajax({
        type: "POST",
        url: "../modelo/mantenimiento.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            if (r == 1) {
                alert("Mantenimiento finalizado con exito");
                redireccionar();
            } else if(r == 3) {
                alert("Hay una actividad sin finalizar");
            } else if(r == 4) {
                alert("El horometro que intenta ingresar es inferior o igual al horometro de la maquina");
            /*} else if(r == 5) {
                alert("El horometro que intentas registrar es mayor al tiempo empleado en esta actividad");
            */}else {
                alert("Falta completar datos para el horometro");
            }
        }
    });   
}