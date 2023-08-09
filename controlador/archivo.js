function recargar(){
    self.location = "inicio.php";    
}
function operacion(r){
    $('#servicio_clasif').val(r);
    if(r == 1){
        $('#opcion_1').removeClass('btn-default');
        $('#opcion_1').addClass('btn-primary');
        $('#opcion_2').removeClass('btn-primary');
        $('#opcion_2').addClass('btn-default');
    }else{
        $('#opcion_2').removeClass('btn-default');
        $('#opcion_2').addClass('btn-primary');
        $('#opcion_1').removeClass('btn-primary');
        $('#opcion_1').addClass('btn-default');
    }
}
function TipoConsulta()
{   var Tipo = document.getElementById("Tipo_consulta").value;
    if (Tipo == 1){
        document.getElementById("cliente_consulta").value="0";
        document.getElementById("patio_consulta").value="0";
        document.getElementById("cargador_consulta").value="0";
        document.getElementById("fecha_inicio_consulta").value="";
        document.getElementById("fecha_fin_consulta").value="";
        $('#resultado').hide();
        $('#div_tiquete').show();
        $('#div_cliente').hide();
        $('#div_cargador').hide();
        $('#div_buscar').show();
        $('#fechas').hide();
        $('#pdf_consulta').hide();
    }else if (Tipo == 2){
        document.getElementById("patio_consulta").value="0";
        document.getElementById("cargador_consulta").value="0";
        document.getElementById("tiquete_consulta").value="";
        $('#resultado').hide();
        $('#div_tiquete').hide();
        $('#div_cliente').show();
        $('#div_cargador').hide();
        $('#div_buscar').show();
        $('#fechas').show();
        $('#pdf_consulta').hide();
    }else if (Tipo == 3){
        document.getElementById("cliente_consulta").value="0";
        document.getElementById("tiquete_consulta").value="";
        $('#resultado').hide();
        $('#div_tiquete').hide();
        $('#div_cliente').hide();
        $('#div_cargador').show();
        $('#div_buscar').show();
        $('#fechas').show();
        $('#pdf_consulta').hide();
    }else{
        $('#resultado').hide();
        $('#div_tiquete').hide();
        $('#div_cliente').hide();
        $('#div_cargador').hide();
        $('#div_buscar').hide();
        $('#fechas').hide();
        $('#pdf_consulta').hide();
    }
}

function validar(frm,op){
    if (op == 1){
    frm.action="consulta_tiques-pdf.php";
    frm.target="_blank";
    frm.submit();
    }
}

function llenar_select(){
    var propietario = $('#propietario').val();
    var band = 1;
    $.post("../modelo/buscar.php", {propietario: propietario, band: band}, 
    function(mensaje) {
        //console.log(mensaje);
        $('#cargador').html(mensaje).fadeIn();
        $('#select_cargador').show();
    });
}

function proveedor_cargador(){
    var patio = $('#lugar').val();
    var cliente = $('#cliente').val();
    var id_maquina = $('#cargador').val();
    cadena = "id_maquina=" + id_maquina +
            "&patio=" + patio +
            "&cliente=" + cliente;
    $.ajax({
        type: "POST",
        url: "../modelo/consu_proveedores.php",
        data: cadena,
        success: function (r) {
            //console.log(r);
            data = r.split("||");
            if(data[0] != 0){
                alertify.error('La maquina ya está trabajando en otro patio');
                //alert('La maquina ya está trabajando en otro patio');
                recargar();
                //self.location = "inicio.php";
            }else if(data[1] != 0){
                alertify.error('La maquina ya está trabajando con dicho cliente');
                //alert('La maquina ya está trabajando con dicho cliente');
                recargar();
                //self.location = "inicio.php";
            }
            $('#buscar_proveedor').val(data[2]);
        }
    });
}

function actividad(val){
    $('#ActDistribucion').hide();
    for(i=0;i<document.form.elements.length; i++)
    {  if(document.form.elements[i].type=="button")
        {   $('#'+document.form.elements[i].value).removeClass('btn-success'); 
            $('#'+document.form.elements[i].value).addClass('btn-default'); 
        }
    }
    $('#sub_actividad').val("");
    var actividad = $('#'+val).val();
    $('#'+val).removeClass('btn-default');
    $('#'+val).addClass('btn-success');
    //alert(actividad);
    //var actividad = document.getElementById("actividad1").value;
    $('#actividad').val(actividad);
    cadena = "actividad=" + actividad;
    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades.php",
        data: cadena,
        success: function (r) {
            const datosActividad = JSON.parse(r);
            let bodySubActividad = '';
            datosActividad.forEach(act => {
              bodySubActividad += `
              <div class="col-sm-4" style="margin-top: 20px;">
                <button type="button" id="${act.idSubactividad}" class="btn btn-default" value="${act.idSubactividad}" onclick='seleccionado("${act.idSubactividad}")'>${act.Descripcion}</button>
              </div>`
            });
            $('#frm').html(bodySubActividad);
        }
    });
    $('#borde').show();
    $('#div_subactividad').show();
    
}

function seleccionado (val){
    for(i=0;i<document.frm.elements.length; i++)
    {  if(document.frm.elements[i].type=="button")
        {   $('#'+document.frm.elements[i].value).removeClass('btn-success'); 
            $('#'+document.frm.elements[i].value).addClass('btn-default'); 
        }
    }
    var a = $('#'+val).val();
    $('#sub_actividad').val(a);
    $('#'+val).removeClass('btn-default');
    $('#'+val).addClass('btn-success');
}

function FinalizarActividad(datos,r){
    if (r == 1){
        data = datos.split(",");
        if (data[0] == 1){
            registro  = $('#registroHorometro1').val(data[1]);
            reporte = $('#horo_inicia').val(data[2]);
            fecha = $('#Activida').val(data[3]);
            horometro = $('#horometro1').val(data[4]);
            fecha_ini = $('#fecha_inic').val(data[5]);
            fecha = $('#SubActivida').val(data[6]);
            fecha = $('#valoresFechaInput').val();
            fecha_inic = $('#fecha_inic').val();
        }else if (data[0] == 0){
            registro  = $('#registroHorometro1').val(data[1]);
            reporte = $('#horo_inicia').val(data[2]);
            $('#title_act').hide();
            $('#Activida').hide();
            horometro = $('#horometro1').val(data[3]);
            fecha_ini = $('#fecha_inic').val(data[4]);
            $('#title_subact').hide();
            $('#SubActivida').hide();
            fecha = $('#valoresFechaInput').val();
            fecha_inic = $('#fecha_inic').val();
        }
        
        //console.log(fecha_inic);
        horometro_inicial = $('#horo_inicia').val();
        var parse = parseFloat(horometro_inicial);
        var n = moment(fecha_inic); 
        //console.log(n);
        var m = moment(fecha); 
        //console.log(m);
        var minutes_inic = (n.hour()*60) + n.minute(); 
        var minutes_fin = (m.hour()*60) + m.minute(); 
        //console.log(minutes_inic);
        //console.log(minutes_fin);
        var total = (minutes_fin-minutes_inic)/60;
        var a = parse + total;
        var round = Math.round(a* 10) / 10;
        //console.log(total);
        //document.getElementById('horo_fina').placeholder= horometro_inicial+' + '+round+' Aprox.';
        document.getElementById('horo_fina').placeholder= round+' Aprox.';
        $('#horo_fina').val(round);
    }else if(r == 2){
        data = datos.split("||");
        $('#idMantto').val(data[0]);
        $('#fecha_inicMtto').val(data[1]);
        $('#horo_iniciaMtto').val(data[2]);
        $('#MaquinariaMtto').val(data[6]);
        $('#cargadorMtto').val(data[3]+" - "+data[4]);
        $('#propietarioMtto').val(data[5]);
        $('#operadorMtto').val(data[7]);
        $('#registroHorometroMtto').val(data[8]);
        $('#idMaquinaria').val(data[9]);

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
}

function FinalizarActividad1(datos,r){
    if (r == 1){
        data = datos.split(",");
        if (data[0] == 1){
            registro  = $('#registroHorometro11').val(data[1]);
            reporte = $('#horo_inicia1').val(data[2]);
            fecha = $('#Activida1').val(data[3]);
            horometro = $('#horometro11').val(data[4]);
            fecha_ini = $('#fecha_inic1').val(data[5]);
            fecha = $('#SubActivida1').val(data[6]);
            fecha = $('#valoresFechaInput').val();
            fecha_inic = $('#fecha_inic1').val();
        }else if (data[0] == 0){
            registro  = $('#registroHorometro11').val(data[1]);
            reporte = $('#horo_inicia1').val(data[2]);
            $('#title_act1').hide();
            $('#Activida1').hide();
            horometro = $('#horometro11').val(data[3]);
            fecha_ini = $('#fecha_inic1').val(data[4]);
            $('#title_subact1').hide();
            $('#SubActivida1').hide();
            fecha = $('#valoresFechaInput').val();
            fecha_inic = $('#fecha_inic1').val();
        }
        
        //console.log(fecha_inic);
        horometro_inicial = $('#horo_inicia1').val();
        var parse = parseFloat(horometro_inicial);
        var n = moment(fecha_inic); 
        //console.log(n);
        var m = moment(fecha); 
        //console.log(m);
        var minutes_inic = (n.hour()*60) + n.minute(); 
        var minutes_fin = (m.hour()*60) + m.minute(); 
        //console.log(minutes_inic);
        //console.log(minutes_fin);
        var total = (minutes_fin-minutes_inic)/60;
        var a = parse + total;
        var round = Math.round(a* 10) / 10;
        //console.log(total);
        //document.getElementById('horo_fina').placeholder= horometro_inicial+' + '+round+' Aprox.';
        document.getElementById('horo_fina1').placeholder= round+' Aprox.';
        $('#horo_fina1').val(round);
    }else if(r == 2){
        data = datos.split("||");
        $('#idMantto').val(data[0]);
        $('#fecha_inicMtto').val(data[1]);
        $('#horo_iniciaMtto').val(data[2]);
        $('#MaquinariaMtto').val(data[6]);
        $('#cargadorMtto').val(data[3]+" - "+data[4]);
        $('#propietarioMtto').val(data[5]);
        $('#operadorMtto').val(data[7]);
        $('#registroHorometroMtto').val(data[8]);
        $('#idMaquinaria').val(data[9]);

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
}

function FinalizarMtto(){
    registro  = $('#registroHorometroMtto').val();
    id_mantto = $('#idMantto').val();
    fecha_inicMtto = $('#fecha_inicMtto').val();
    fechaMtto = $('#fechaMtto').val();
    horo_iniciaMtto = $('#horo_iniciaMtto').val();
    horo_finaMtto = $('#horo_finaMtto').val();
    idMaquinaria = $('#idMaquinaria').val();

    band = 3;
    cadena = "id_mantto=" + id_mantto +
            "&registro=" + registro + 
            "&fechaMtto=" + fechaMtto +
            "&horo_iniciaMtto=" + horo_iniciaMtto +
            "&horo_finaMtto=" + horo_finaMtto +
            "&idMaquinaria=" + idMaquinaria +
            "&band=" + band;

    $.ajax({
        type: "POST",
        url: "../modelo/horometro_insertar.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            if (r == 1) {
                alertify.success('Actividad finalizada con exito');
                //alert("Actividad finalizada con exito");
                //window.close();
                recargar();
                //self.location = "inicio.php";
            } else if(r == 3) {
                alertify.warning("Hay una actividad sin finalizar")
                //alert("Hay una actividad sin finalizar");
            } else if(r == 4) {
                alertify.error("El horometro que intentas ingresar es inferior o igual al horometro de la maquina");
                //alert("El horometro que intentas ingresar es inferior o igual al horometro de la maquina");
            /*} else if(r == 5) {
                alert("El horometro que intentas registrar es mayor al tiempo empleado en esta actividad");
            */}else{
                alertify.error("Te falta completar datos para el horometro");
                //alert("Te falta completar datos para el horometro");
            }
        }
    });
    
}

function MostrarAct(r){
    if (r == 1){
        $('#punto').val(1);
        $('#btn_1').removeClass('btn-default'); 
        $('#btn_1').addClass('btn-warning');
        $('#btn_2').removeClass('btn-warning'); 
        $('#btn_2').addClass('btn-default'); 
        $('#btn_3').removeClass('btn-warning'); 
        $('#btn_3').addClass('btn-default');
        $('#ActDetallado').show();
        $('#ActDistribucion').hide();
        $('#ActMantto').hide();
    }else if (r == 2){
        $('#punto').val(2);
        for(i=0;i<document.form.elements.length; i++)
        {  if(document.form.elements[i].type=="button")
            {   $('#'+document.form.elements[i].value).removeClass('btn-success'); 
                $('#'+document.form.elements[i].value).addClass('btn-default'); 
            }
        }
        $('#btn_2').removeClass('btn-default'); 
        $('#btn_2').addClass('btn-warning');
        $('#btn_1').removeClass('btn-warning'); 
        $('#btn_1').addClass('btn-default'); 
        $('#btn_3').removeClass('btn-warning'); 
        $('#btn_3').addClass('btn-default');
        $('#ActDistribucion').show();
        document.getElementById('conten-actividad').innerHTML = "Se registrarán varias actividades";
        $('#ActDetallado').hide();
        $('#div_subactividad').hide();
        $('#borde').hide();
        $('#ActMantto').hide();
    }else if (r == 3){
        $('#punto').val(3);
        for(i=0;i<document.form.elements.length; i++)
        {  if(document.form.elements[i].type=="button")
            {   $('#'+document.form.elements[i].value).removeClass('btn-success'); 
                $('#'+document.form.elements[i].value).addClass('btn-default'); 
            }
        }
        $('#btn_3').removeClass('btn-default'); 
        $('#btn_3').addClass('btn-warning');
        $('#btn_2').removeClass('btn-warning'); 
        $('#btn_2').addClass('btn-default');
        $('#btn_1').removeClass('btn-warning'); 
        $('#btn_1').addClass('btn-default'); 
        $('#ActDistribucion').hide();
        $('#ActMantto').show();
        $('#ActDetallado').hide();
        $('#div_subactividad').hide();
        $('#borde').hide();
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function insertar(operador, registro, lugar, cargador, cliente, servicio_clasif){
    //console.log(servicio_clasif);
    cadena = "operador=" + operador +
            "&registro=" + registro +
            "&lugar=" + lugar +
            "&cargador=" + cargador +
            "&cliente=" + cliente +
            "&servicio_clasif=" + servicio_clasif;

    $.ajax({
        type: "POST",
        url: "../modelo/tiquete_insertar.php",
        data: cadena,
        success: function (r) {
            console.log(r);
            if (r == 1) {
                alertify.success("Se registró el tiquete correctamente");
                //alert("Se registró el tiquete correctamente");
                recargar();
                //self.location = "inicio.php";
            }else{
                alertify.error("Te falta completar datos para el registro del tiquete");
                //alert("Te falta completar datos para el registro del tiquete");
            }
        }
    });
}
function datos(id_registro,horo_final,valor){
    registro = id_registro;
    horo_final = horo_final;
    valor = valor;
    //console.log(registro);
    if (valor == 1){
        $('#registroHorometro_h').val(registro);
        $('#horo_inicial_h').val(horo_final);
    }else{
       $('#registroHorometro').val(registro);
        $('#horo_inicial').val(horo_final); 
    }
    
}
function AgregarHorometro() {
    variable = $('#var').val();
    //console.log(variable);
    if (variable == 10){
        registro = $('#registroHorometro_h').val();
        hora_inicial = $('#horo_inicial_h').val();
        actividad = $('#actividad_h').val();
    }else{
        registro = $('#registroHorometro').val();
        hora_inicial = $('#horo_inicial').val();
        actividad = $('#actividad').val();
        sub_actividad = $('#sub_actividad').val();
        idUsuario = $('#idUsuario').val();
    }
    //console.log(actividad);
    //console.log($('#punto').val());
    fecha_horaIni = $('#FechaHora_ini').val();
    if ($('#punto').val() == 1){
        band = 1;
        cadena = "registro=" + registro + 
            "&hora_inicial=" + hora_inicial +
            "&fecha_horaIni=" + fecha_horaIni +
            "&band=" + band +
            "&actividad=" + actividad +
            "&sub_actividad=" + sub_actividad;
        $.ajax({
            type: "POST",
            url: "../modelo/horometro_insertar.php",
            data: cadena,
            success: function (r){
                console.log(r);
                data = r.split("||");
                if (data[0] == 1){
                    alertify.success("Se registró correctamente");
                    //alert("Se registró correctamente");
                    if(data[2] != 0){
                        if (data[1] <= 15){
                            alertify.warning("Faltan "+data[1]+" horas para el proximo mantenimiento programado");
                            //alert("Faltan "+data[1]+" horas para el proximo mantenimiento programado");
                        }
                    }
                    recargar();
                    //self.location = "inicio.php";
                }else if(data[0] == 2){
                    alertify.error("La maquina ya cumplió su tiempo maximo de trabajo por dia");
                    //alert("La maquina ya cumplió su tiempo maximo de trabajo por dia");
                }else if(data[0] == 3){
                    alertify.warning("Hay una actividad sin finalizar del cargador");
                    //alert("Hay una actividad sin finalizar del cargador");
                }else if(data[0] == 4){
                    alertify.warning("El horometro que intentas ingresar no coincide con el de la maquina");
                    //alert("El horometro que intentas ingresar no coincide con el de la maquina");
                }else{
                    alertify.error("Te falta completar datos para el horometro");
                    //alert("Te falta completar datos para el horometro");
                }
            }
        });
    }else if($('#punto').val() == 2){
        band = 10;
        cadena = "registro=" + registro + 
            "&hora_inicial=" + hora_inicial +
            "&fecha_horaIni=" + fecha_horaIni +
            "&band=" + band;
        $.ajax({
            type: "POST",
            url: "../modelo/horometro_insertar.php",
            data: cadena,
            success: function (r){
                console.log(r);
                data = r.split("||");
                if (data[0] == 1) {
                    alertify.success("Se registró correctamente");
                    //alert("Se registró correctamente");
                    if(data[2] != 0){
                        if (data[1] <= 15){
                            alertify.warning("Faltan "+data[1]+" horas para el proximo mantenimiento programado");
                            //alert("Faltan "+data[1]+" horas para el proximo mantenimiento programado");
                        }
                    }
                    recargar();
                    //self.location = "inicio.php";
                }else if(data[0] == 2){
                    alertify.error("La maquina ya cumplió su tiempo maximo de trabajo por dia");
                    //alert("La maquina ya cumplió su tiempo maximo de trabajo por dia");
                }else if(data[0] == 3){
                    alertify.warning("Hay una actividad sin finalizar del cargador");
                    //alert("Hay una actividad sin finalizar del cargador");
                }else if(data[0] == 4){
                    alertify.error("El horometro que intentas ingresar no coincide con el de la maquina");
                    //alert("El horometro que intentas ingresar no coincide con el de la maquina");
                }else{
                    alertify.error("Te falta completar datos para el horometro");
                    //alert("Te falta completar datos para el horometro");
                }
            }
        });
    }else if($('#punto').val() == 3){
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
        }else{
            alertify.warning('No ha seleccionado nada');
            //alert('No ha seleccionado nada');
        }
        if (v == 1){
            band = 4;
            cadena = "registro=" + registro +
                    "&band=" + band;
            $.ajax({
                type: "POST",
                url: "../modelo/horometro_insertar.php",
                data: cadena,
                success: function (r){
                    //console.log(r);
                    if (r != 0){
                        alertify.confirm('Mantenimiento Programado', '¿Estás seguro de iniciar un mantenimiento programado?       Faltan: '+r+' horas para el proximo mantenimiento',
                        function () {
                            band = 20;
                            tipo = $('#TipoMantto').val();
                            //console.log(tipo);
                            cadena = "registro=" + registro + 
                                "&hora_inicial=" + hora_inicial +
                                "&fecha_horaIni=" + fecha_horaIni +
                                "&idUsuario=" + idUsuario +
                                "&tipoMantto=" + tipo +
                                "&band=" + band;
                            $.ajax({
                                type: "POST",
                                url: "../modelo/horometro_insertar.php",
                                data: cadena,
                                success: function (r){
                                    //console.log(r);
                                    data = r.split("||");
                                    if (data[0] == 1) {
                                        alertify("Se registró correctamente");
                                        //alert("Se registró correctamente");
                                        if(data[2] != 0){
                                            if (data[1] <= 15){
                                                alertify.warning("Faltan "+data[1]+" horas para el proximo mantenimiento programado");
                                                //alert("Faltan "+data[1]+" horas para el proximo mantenimiento programado");
                                            }
                                        }
                                        recargar();
                                        //self.location = "inicio.php";
                                    } else if(data[0] == 3) {
                                        alertify.warning("Hay una actividad sin finalizar del cargador");
                                        //alert("Hay una actividad sin finalizar del cargador");
                                    } else if(data[0] == 4) {
                                        alertify.error("El horometro que intentas ingresar no coincide con el de la maquina");
                                        //alert("El horometro que intentas ingresar no coincide con el de la maquina");
                                    }else{
                                        alertify.error("Te falta completar datos para el horometro");
                                        //alert("Te falta completar datos para el horometro");
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
            band = 20;
            tipo = $('#TipoMantto').val();
            cadena1 = "registro=" + registro + 
                "&hora_inicial=" + hora_inicial +
                "&fecha_horaIni=" + fecha_horaIni +
                "&idUsuario=" + idUsuario +
                "&tipoMantto=" + tipo +
                "&band=" + band;
            $.ajax({
                type: "POST",
                url: "../modelo/horometro_insertar.php",
                data: cadena1,
                success: function (r){
                    //console.log(d);
                    data = r.split("||");
                    if (data[0] == 1) {
                        alertify.success("Se registró correctamente");
                        //alert("Se registró correctamente");
                        if(data[2] != 0){
                            if (data[1] <= 15){
                                alertify.warning("Faltan "+data[1]+" horas para el proximo mantenimiento programado");
                                //alert("Faltan "+data[1]+" horas para el proximo mantenimiento programado");
                            }
                        }
                        recargar();
                        //self.location = "inicio.php";
                    } else if(data[0] == 3) {
                        alertify.warning("Hay una actividad sin finalizar del cargador");
                        //alert("Hay una actividad sin finalizar del cargador");
                    } else if(data[0] == 4) {
                        alertify.error("El horometro que intentas ingresar no coincide con el de la maquina");
                        //alert("El horometro que intentas ingresar no coincide con el de la maquina");
                    }else{
                        alertify.error("Te falta completar datos para el horometro");
                        //alert("Te falta completar datos para el horometro");
                    }
                }
            });
        }
    }
}
function datos1(datos){
    data = datos.split(",");
    registro  = $('#registroHorometro1').val(data[0]);
    reporte = $('#horo_inicia').val(data[1]);
    fecha = $('#Activida').val(data[2]);
    horometro = $('#horometro1').val(data[3]);
    fecha_ini = $('#fecha_inic').val(data[4]);
    fecha = $('#SubActivida').val(data[5]);
    fecha = $('#fecha').val();
    fecha_inic = $('#fecha_inic').val();
    horometro_inicial = $('#horo_inicia').val();
    var parse = parseFloat(horometro_inicial);
    var n = moment(fecha_inic); 
    var m = moment(fecha); 
    //console.log(n);
    var minutes_inic = (n.hour()*60) + n.minute(); 
    var minutes_fin = (m.hour()*60) + m.minute(); 

    var total = (minutes_fin-minutes_inic)/60;
    var a = parse + total;
    var round = Math.round(a* 10) / 10;
    //console.log(a);
    //document.getElementById('horo_fina').placeholder= horometro_inicial+' + '+round+' Aprox.';
    document.getElementById('horo_fina').placeholder= round+' Aprox.';
    $('#horo_fina').val(round);
    //console.log(total);
    /*
    registro  = $('#registroHorometro1').val(a);
    reporte = $('#horo_inicia').val(b);
    fecha = $('#Activida').val(d);
    horometro = $('#horometro1').val(c);*/
}

function AgregarHorometroUpdate() {
    horometro = $('#horometro1').val();
    registro = $('#registroHorometro1').val();
    hora_inicial = $('#horo_inicia').val();
    //console.log(hora_inicial);
    hora_final = $('#horo_fina').val();
    console.log(hora_final);
    fecha = $('#fecha').val();
    fecha_inic = $('#fecha_inic').val();
    fecha_cierre = $('#fecha').val();
    fecha_horaIni = $('#fecha_horaIni').val();

    var n = moment(fecha_inic); 
    var m = moment(fecha); 
    //console.log(n);
    var minutes_inic = (n.hour()*60) + n.minute(); 
    var minutes_fin = (m.hour()*60) + m.minute(); 

    var total = (minutes_fin-minutes_inic)/60;
    //console.log(total);
    band = 2;

    cadena = "registro=" + registro + 
            "&hora_inicial=" + hora_inicial +
            "&hora_final=" + hora_final +
            "&fecha_horaIni=" + fecha_horaIni +
            "&horometro=" + horometro +
            "&band=" + band+
            "&total=" + total+
            "&fecha_cierre=" + fecha_cierre;

    $.ajax({
        type: "POST",
        url: "../modelo/horometro_insertar.php",
        data: cadena,
        success: function (r){
            data = r.split("||");
            if (data[0] == 1){
                alertify.success("Actividad finalizada con exito");
                //alert("Actividad finalizada con exito");
                if(data[2] != 0){
                    if (data[1] <= 15){
                        alertify.warning("Faltan "+data[1]+" horas para el proximo mantenimiento programado");
                        //alert("Faltan "+data[1]+" horas para el proximo mantenimiento programado");
                    }
                }
                recargar();
                //self.location = "inicio.php";
            }else if(data[0] == 3){
                alertify.warning("Hay una actividad sin finalizar");
                //alert("Hay una actividad sin finalizar");
            }else if(data[0] == 4){
                alertify.error("El horometro que intentas ingresar es inferior o igual al horometro de la maquina");
                //alert("El horometro que intentas ingresar es inferior o igual al horometro de la maquina");
            }else if(data[0] == 5){
                alertify.error("El horometro que intentas ingresar es mayor al tiempo posible para laborar");
                //alert("El horometro que intentas ingresar es mayor al tiempo posible para laborar");
            }else if(data[0] == 6){
                alertify.error("El tiempo total del tiquete es mayor al tiempo posible por jornada");
                //alert("El tiempo total del tiquete es mayor al tiempo posible por jornada");
            }else{
                alertify.error("Te falta completar datos para el horometro");
                //alert("Te falta completar datos para el horometro");
            }
        }
    });
}

function AgregarHorometroUpdate1(){
    horometro = $('#horometro11').val();
    registro = $('#registroHorometro11').val();
    hora_inicial = $('#horo_inicia1').val();
    //console.log(hora_inicial);
    hora_final = $('#horo_fina1').val();
    fecha = $('#fecha1').val();
    fecha_inic = $('#fecha_inic1').val();
    fecha_cierre = $('#fecha1').val();
    fecha_horaIni = $('#fecha_horaIni1').val();
    if ($('#observacionesHoro').val() != 0){
        observaciones = $('#observacionesHoro').val();

        var n = moment(fecha_inic); 
        var m = moment(fecha); 
        var minutes_inic = (n.hour()*60) + n.minute(); 
        var minutes_fin = (m.hour()*60) + m.minute(); 

        var total = (minutes_fin-minutes_inic)/60;
        band = 5;

        cadena = "registro=" + registro + 
                "&hora_inicial=" + hora_inicial +
                "&hora_final=" + hora_final +
                "&fecha_horaIni=" + fecha_horaIni +
                "&horometro=" + horometro +
                "&band=" + band+
                "&total=" + total+
                "&fecha_cierre=" + fecha_cierre +
                "&observaciones=" + observaciones;

        $.ajax({
            type: "POST",
            url: "../modelo/horometro_insertar.php",
            data: cadena,
            success: function (r){
                data = r.split("||");
                if (data[0] == 1){
                    alertify.success("Actividad finalizada con exito");
                    //alert("Actividad finalizada con exito");
                    if(data[2] != 0){
                        if (data[1] <= 15){
                            alertify.warning("Faltan "+data[1]+" horas para el proximo mantenimiento programado");
                            //alert("Faltan "+data[1]+" horas para el proximo mantenimiento programado");
                        }
                    }
                    recargar();
                    //self.location = "inicio.php";
                }else if(data[0] == 3) {
                    alertify.warning("Hay una actividad sin finalizar");
                    //alert("Hay una actividad sin finalizar");
                }else if(data[0] == 4) {
                    alertify.error("El horometro que intenta ingresar es inferior o igual al horometro de la maquina");
                    //alert("El horometro que intenta ingresar es inferior o igual al horometro de la maquina");
                }else if(data[0] == 5){
                    alertify.error("El horometro que intenta ingresar es mayor al tiempo posible para laborar");
                    //alert("El horometro que intenta ingresar es mayor al tiempo posible para laborar");
                }else if(data[0] == 6){
                    alertify.error("El tiempo total del tiquete es mayor al tiempo posible por jornada");
                    //alert("El tiempo total del tiquete es mayor al tiempo posible por jornada");
                }else {
                    alertify.error("Te falta completar datos para el horometro");
                    //alert("Te falta completar datos para el horometro");
                }
            }
        });
    }else{
        alertify.warning('Defina lo realizado en el lapso de tiempo');
        //alert('Defina lo realizado en el lapso de tiempo');
    }
}

function ver(dato) {
    d = dato.split('||');
    registro  = $('#registro_fin').val(d[0]);
    reporte = $('#reporte_fin').val(d[1]);
    fecha = $('#fecha_fin').val(d[2]);
    lugar = $('#lugar_fin').val(d[3]);
    cargador = $('#cargador_fin').val(d[4]+' - '+d[8]);
    jornada_inicial = $('#jornada_inicial_fin').val(d[5]);
    cliente = $('#cliente_fin').val(d[6]);
    proveedor = d[7];
    band = 13;
    cadena = "id_proveedor=" + proveedor +
            "&band=" + band;
    $.ajax({
        type: "POST",
        url: "../modelo/consu_subactividades_patio.php",
        data: cadena,
        success: function (r) {
            $('#proveedor').val(r);
        }
    });
}

function FinalizarTiquete(){
    registro = $('#registro_fin').val();
    band = 1;
    cadena = "registro=" + registro +
            "&band=" + band;
    $.ajax({
        type: "POST",
        url: "../modelo/tiquete_finalizar.php",
        data: cadena,
        success: function (r){
            if (r == 1){
                alertify.success("Finalizado con exito");
                //alert("Finalizado con exito");
                recargar();
                //self.location = "inicio.php";
            }else if(r == 2){
                //alert('aca 2');
                let question = confirm("¿Quieres eliminar este tiquete?");
                //alert( question ); // true if OK is pressed
                if(question){
                    band = 3;
                    cadena = "registro=" + registro +
                            "&band=" + band;
                    $.ajax({
                    type: "POST",
                    url: "../modelo/tiquete_finalizar.php",
                    data: cadena,
                    success: function (r){
                        if(r == 1){
                            alertify.success("El tiquete ha sido eliminado");
                            //alert("El tiquete ha sido eliminado");
                            recargar();
                            //self.location = "inicio.php";
                        }
                    }
                    });
                }
            }else if(r == 3){
                alertify.warning("Hay actividades sin finalizar");
                //alert("Hay actividades sin finalizar");
            }else{
                alertify.error("La información que ingresaste no se pudo actualizar");
            }
        }
    });
}