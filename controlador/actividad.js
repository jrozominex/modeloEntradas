function TablaActividad(val){
    //document.getElementById('titulo').innerHTML = val;
    for(i=0;i<document.formButton.elements.length; i++)
    {  if(document.formButton.elements[i].type=="button")
        {   $('#'+document.formButton.elements[i].value).removeClass('btn-success'); 
            $('#'+document.formButton.elements[i].value).addClass('btn-default'); 
        }
    }
    if(val == 'Operativo' || val == 1){
        //document.getElementById('titulo').innerHTML = "Actividades Operativas";
        var bandera = 1;
        $('#' + val).removeClass('btn-default'); 
        $('#' + val).addClass('btn-success');
    }else if(val == 'Mtto eventual' || val == 2){
        var bandera = 2;
        $('#' + val).removeClass('btn-default'); 
        $('#' + val).addClass('btn-success');
    }else if(val == 'Mtto general'  || val == 3){
        var bandera = 3;
        $('#' + val).removeClass('btn-default'); 
        $('#' + val).addClass('btn-success');
    }else if(val == 'Mtto lavado'  || val == 4){
        var bandera = 4;
        $('#' + val).removeClass('btn-default'); 
        $('#' + val).addClass('btn-success');
    }else if(val == 'Mtto electrico'  || val == 5){
        var bandera = 5;
        $('#' + val).removeClass('btn-default'); 
        $('#' + val).addClass('btn-success');
    }else if(val == 'Reporte fallas'  || val == 6){
        var bandera = 6;
        $('#' + val).removeClass('btn-default'); 
        $('#' + val).addClass('btn-success');
    }else if(val == 'CategoriaMaquinaria'  || val == 8){
        //document.getElementById('titulo').innerHTML = "Categoria Maquinaria";
        var bandera = 7;
        $('#' + val).removeClass('btn-default'); 
        $('#' + val).addClass('btn-success');
    }else if(val == 'Pilas' || val == 9){
        var bandera = 8;
        $('#' + val).removeClass('btn-default'); 
        $('#' + val).addClass('btn-success');
    }else if(val == 'Rendimientos' || val == 10){
        var bandera = 9;
        $('#' + val).removeClass('btn-default'); 
        $('#' + val).addClass('btn-success');
    }else if(val == 'ActividadDestinos' || val == 11){
        //document.getElementById('titulo').innerHTML = "Actividades Destinos";
        var bandera = 10;
        $('#' + val).removeClass('btn-default'); 
        $('#' + val).addClass('btn-success');
    }else if(val == 'Recetas_produccion' || val == 12){
        //document.getElementById('titulo').innerHTML = "Recetas Producción";
        var bandera = 11;
        $('#' + val).removeClass('btn-default'); 
        $('#' + val).addClass('btn-success');
    }else if(val == 'codigos_destino' || val == 13){
        //document.getElementById('titulo').innerHTML = "Códigos Destino";
        var bandera = 12;
        $('#' + val).removeClass('btn-default'); 
        $('#' + val).addClass('btn-success');
    }else if(val == 'clasificacion_jerarquia' || val == 14){
        //document.getElementById('titulo').innerHTML = "Destinos Clasificación";
        var bandera = 13;
        $('#' + val).removeClass('btn-default'); 
        $('#' + val).addClass('btn-success');
    }
    cadena = "bandera=" + bandera;
    $.ajax({
        type: "POST",
        url: "../modelo/actividad_vistas.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            $('#div_tablas').html(r);
            datatable();
        }
    });
}
function decide(){
    if ($('#decide').val() == 0){
        AgregarActividad();
    }else{
        ModificarActividad();
    }
}
function decide1(){
    if ($('#idSubactividad').val() == 0){
        AgregarSubActividad();
    }else{
        ModificarSubActividad();
    }
}
function AgregarActividad() {
    $('#decide').val(0);
    var Actividad = document.getElementById("actividad").value;
    if (Actividad == ""){
        alert('No escribiste nada.')
    }else{
        if ($('#TipoAct').val() != 0){
            actividad = $('#actividad').val();
            TipoAct = $('#TipoAct').val();
            band = 1;
            cadena = "actividad=" + actividad +
                     "&TipoAct=" + TipoAct +
                     "&band=" + band;
            $.ajax({
                type: "POST",
                url: "../modelo/actividad_insertar.php",
                data: cadena,
                success: function (r){
                    //alert(r);
                    if(r == 1){
                        //alert("agregado con exito");
                        swal("", "Agregado con exito!", "success");
                        var ultimoCaracter = TipoAct.charAt(TipoAct.length - 1);
                        TablaActividad(ultimoCaracter);
                    }else{
                        alert("No se pudo registrar la actividad")
                    }
                }
            });
        }else{
            alert('Seleccione una clase')
        }
    }
}
function calculate_name_receta(){
    let empresa_produccion = $('#empresa_produccion').val()
    let patio_produccion = $('#patio_produccion').val()
    cadena = "empresa_produccion=" + empresa_produccion +
             "&patio_produccion=" + patio_produccion +
             "&bandera=" + 15;
    $.ajax({
        type: "POST",
        url: "../modelo/actividad_vistas.php",
        data: cadena,
        success: function (r){
            //console.log(r)
            $('#nombre_receta_produccion').val(r)
        }
    });
}
function sub_actividad(id,nombre){
    document.getElementById('myModalLabel1').innerHTML = 'Registrar SubActividad';
    $('#idActividad').val(id);
    $('#actividad1').val(nombre);
    $('#idSubactividad').val(0);
    $('#subactividad').val('');
}
function AgregarSubActividad(){
    idActividad = $('#idActividad').val();
    subactividad = $('#subactividad').val();
    if (subactividad != null){
        band = 2;
        cadena = "idActividad=" + idActividad +
                "&subactividad=" + subactividad +
                "&band=" + band;
        $.ajax({
            type: "POST",
            url: "../modelo/actividad_insertar.php",
            data: cadena,
            success: function (r){
                d = r.split("||");
                //alert(r);
                TipoAct = d[1];
                if (d[0] == 1){
                    //alert("agregado con exito");
                    swal("", "Agregado con exito!", "success");
                    var ultimoCaracter = TipoAct.charAt(TipoAct.length - 1);
                    TablaActividad(ultimoCaracter);
                    //self.location = "actividades.php";
                }else{
                    alert("No se pudo registrar la subactividad");
                }
            }
        });
    }else{
        alert('No escribiste nada.')
    }
}
function Limpiar(){
    document.getElementById('myModalLabel').innerHTML = 'Registrar Actividad';
    $('#idActividad1').val('');
    $('#actividad').val('');
    $('#decide').val(0);
    document.getElementById('TituloBoton').innerHTML = 'Guardar';
}
function VerActividad(idActividad,Descripcion){
    document.getElementById('myModalLabel').innerHTML ='Modificar Actividad';
    $('#decide').val(1);
    $('#idActividad1').val(idActividad);
    $('#actividad').val(Descripcion);
    document.getElementById('TituloBoton').innerHTML = 'Modificar';
}
function ModificarActividad(){
    idActividad = $('#idActividad1').val();
    Descripcion = $('#actividad').val();
    if (Descripcion != null){
        TipoAct = $('#TipoAct').val();
        band = 3;
        cadena = "idActividad=" + idActividad +
                "&Descripcion=" + Descripcion +
                "&TipoAct=" + TipoAct +
                "&band=" + band;
        $.ajax({
            type: "POST",
            url: "../modelo/actividad_insertar.php",
            data: cadena,
            success: function (r){
                //alert(r);
                if (r == 1) {
                    //alert("Modificada con exito");
                    swal("", "Modificado con exito!", "success");
                    var ultimoCaracter = TipoAct.charAt(TipoAct.length - 1);
                    TablaActividad(ultimoCaracter);
                }else{
                    alert("No se pudo modificar la actividad");
                }
            }
        });
    }else{
        alert('No escribiste nada.')
    }
}
function ModificarSubActividad(){
    idActividad = $('#idActividad').val();
    idSubActividad = $('#idSubactividad').val();
    Descripcion = $('#subactividad').val();
    if (Descripcion != null){
        band = 4;
        cadena = "idActividad=" + idActividad +
                "&idSubActividad=" + idSubActividad +
                "&Descripcion=" + Descripcion +
                "&band=" + band;
        $.ajax({
            type: "POST",
            url: "../modelo/actividad_insertar.php",
            data: cadena,
            success: function (r){
                //alert(r);
                d = r.split("||");
                TipoAct = d[1];
                if (d[0] == 1) {
                    //alert("Modificada con exito");
                    swal("", "Modificado con exito!", "success");
                    var ultimoCaracter = TipoAct.charAt(TipoAct.length - 1);
                    TablaActividad(ultimoCaracter);
                    //self.location = "actividades.php";
                } else {
                    alert("No se pudo modificar la SubActividad");
                }
            }
        });
    }else{
        alert('No escribiste nada.');
    }
}
function VerSubActividad(a,a1,b,b1){
    document.getElementById('myModalLabel1').innerHTML = 'Modificar SubActividad';
    $('#idActividad').val(a);
    $('#actividad1').val(a1);
    $('#idSubactividad').val(b);
    $('#subactividad').val(b1);
}
function TipoAct(a){
    $('#TipoAct').val(a);
    for(i=0;i<document.formTipo.elements.length; i++)
    {  if(document.formTipo.elements[i].type=="button")
        {   $('#'+document.formTipo.elements[i].value).removeClass('btn-success'); 
            $('#'+document.formTipo.elements[i].value).addClass('btn-default'); 
        }
    }
    $('#'+a).removeClass('btn-default');
    $('#'+a).addClass('btn-success');
}
function agregar_categoria(){
    var Descripcion = $('#descrip_categoria').val();
    var list_agrupacion = $('#list_agrupacion').val()
    if(Descripcion != '' && list_agrupacion != '0' && list_agrupacion != 0){
        var band = 5;
        cadena = "Descripcion=" + Descripcion +
                "&list_agrupacion=" + list_agrupacion +
                "&band=" + band;
        $.ajax({
            type: "POST",
            url: "../modelo/actividad_insertar.php",
            data: cadena,
            success: function (r){
                //alert(r);
                if (r == 1) {
                    alertify.success("Se registró correctamente");
                    TablaActividad(8);
                } else {
                    alertify.error("No se pudo registrar");
                }
            }
        });
    }else{
        alertify.warning("Escriba el nombre de la categoria");
    }
}
function agregar_pila(){
    var Descripcion = $('#descrip_pila').val();
    if(Descripcion != ''){
        var band = 6;
        cadena = "Descripcion=" + Descripcion +
                "&band=" + band;
        $.ajax({
            type: "POST",
            url: "../modelo/actividad_insertar.php",
            data: cadena,
            success: function (r){
                //alert(r);
                if (r == 1) {
                    alertify.success("Se registró correctamente");
                    TablaActividad(9);
                    //self.location = "actividades.php";
                } else {
                    alertify.error("No se pudo registrar");
                }
            }
        });
    }else{
        alertify.warning("Escriba el nombre de la Pila");
    }
}
function agregar_rendimiento(){
    var equipo = $('#Equipo').val();
    var clasificacion = $('#Clasificacion').val();
    var tara = $('#Tara').val();
    if(equipo != '' && clasificacion != '' && tara != ''){
        var band = 7;
        cadena = "equipo=" + equipo +
                "&clasificacion=" + clasificacion +
                "&tara=" + tara +
                "&band=" + band;
        $.ajax({
            type: "POST",
            url: "../modelo/actividad_insertar.php",
            data: cadena,
            success: function (r){
                //console.log(r);
                if (r == 1) {
                    alertify.success("Se registró correctamente");
                    TablaActividad(10);
                    //self.location = "actividades.php";
                } else {
                    alertify.error("No se pudo registrar");
                }
            }
        });
    }else{
        alertify.warning("Complete todos los datos");
    }
}
function guardar_ActividadDestinos(){
    var idActividad = $('#idActividadDestinos').val();
    var idDestino = $('#idDestinoActividad').val();
    if(document.getElementById('checkbox_cargadores').checked){
        var checkbox_cargadores = 1;
    }else{
        var checkbox_cargadores = 0;
    }
    if(document.getElementById('checkbox_produccion').checked){
        var checkbox_produccion = 1;
    }else{
        var checkbox_produccion = 0;
    }
    if(idActividad != '0' && idDestino != '0'){
        var band = 8;
        cadena = "idActividad=" + idActividad +
                "&idDestino=" + idDestino +
                "&checkbox_cargadores=" + checkbox_cargadores +
                "&checkbox_produccion=" + checkbox_produccion +
                "&band=" + band;
        $.ajax({
            type: "POST",
            url: "../modelo/actividad_insertar.php",
            data: cadena,
            success: function (r){
                //console.log(r);
                if(r == 1){
                    alertify.success("Se registró correctamente");
                    TablaActividad(11);
                    //self.location = "actividades.php";
                }else{
                    alertify.error("No se pudo registrar");
                }
            }
        });
    }
}
function change_habilitar(action,idxid,estado){
    cadena = "idxid=" + idxid +
            "&action=" + action +
            "&estado=" + estado +
            "&band=" + 13;
    //console.log(cadena)
    $.ajax({
        type: "POST",
        url: "../modelo/actividad_insertar.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            if(r == 1){
                alertify.success("Se registró correctamente");
                TablaActividad(11);
                //self.location = "actividades.php";
            }else{
                alertify.error("No se pudo registrar");
            }
        }
    });
}
function habilitar_grupo(idxid,estado){
    cadena = "idxid=" + idxid +
            "&estado=" + estado +
            "&band=" + 15;
    //console.log(cadena)
    $.ajax({
        type: "POST",
        url: "../modelo/actividad_insertar.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            if(r == 1){
                alertify.success("Se registró correctamente");
                TablaActividad(8);
                //self.location = "actividades.php";
            }else{
                alertify.error("No se pudo registrar");
            }
        }
    });
}
function editar_ActividadDestinos(idxid,estado){
    /*swal({
      //title: "Are you sure?",
      text: "¡Una vez eliminado, no podrás recuperar este registro!",
      icon: "warning",
      buttons:  ["Inhabilitar!", "Eliminar!"],
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        swal("Poof! ha sido eliminado!", {
          icon: "success",
        });
      } else {
        swal("ha sido inhabilitado!");
      }
    });*/
    if(estado == 1){
        var texto = "Inhabilitar";
        var texto_largo = "Ha sido inhabilitado!";
    }else if(estado == 0){
        var texto = "Habilitar";
        var texto_largo = "Ha sido habilitado!";
    }
    swal("¡Una vez eliminado, no podrás recuperar este registro!", {
        icon: "warning",
        buttons: {
            //cancel: "",
            catch: {
                text: "Eliminar!",
                value: "catch",
            }/*,
            defeat: {
                text: texto,
            },*/
        },dangerMode: false,
    })
    .then((value) => {
        switch (value) {
     
        case "defeat":
            var band = 9;
        break;
     
        case "catch":
            var band = 10;
            break;

        default:
            //swal("Got away safely!");
        }
        cadena = "idxid=" + idxid +
                "&estado=" + estado +
                "&band=" + band;
        $.ajax({
            type: "POST",
            url: "../modelo/actividad_insertar.php",
            data: cadena,
            success: function (r){
                //console.log(r);
                if (r == 1){
                    if(band == 9){
                        swal("",texto_largo,"success");
                    }else if(band == 10){
                        swal("Poof!", "Ha sido eliminado!", "success");
                    }
                    TablaActividad(11);
                    //self.location = "actividades.php";
                }
            }
        });
    });
}
function load_pilas_receta(idReceta,option){
    cadena = "idReceta=" + idReceta +
            "&band=" + 16;
    $.ajax({
        type: "POST",
        url: "../modelo/actividad_vistas.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            $('#bodyPilaRecetas').html(r).fadeIn()
            if(option==0){
                $('#modalInsertarPilaRecetas').modal('show')
            }
        }
    });
}
function load_min_dates(idClasificacion,idDestino){
    var pila = $('#pila_' + idClasificacion).val()
    cadena = "idClasificacion=" + idClasificacion +
            "&idDestino=" + idDestino +
            "&pila=" + pila +
            "&band=" + 18;
    $.ajax({
        type : "POST",
        url : "../modelo/actividad_vistas.php",
        data : cadena,
        success: function (r){
            //console.log(r)
            $('#fechaInicio_' + idClasificacion).prop("min",r)
            $('#fechaFin_' + idClasificacion).prop("min",r)
        }
    })
}
function save_pilas_receta(idReceta,idClasificacion,idPila){
    if(idPila=='00000000-0000-0000-0000-000000000000'){
        var pila = $('#pila_' + idClasificacion).val()
        var porcentaje = $('#porcentaje_' + idClasificacion).val()
        var fechaInicio = $('#fechaInicio_' + idClasificacion).val()
        var fechaFin = $('#fechaFin_' + idClasificacion).val()
    }else{
        var pila = $('#pila_' + idPila).val()
        var porcentaje = $('#porcentaje_' + idPila).val()
        var fechaInicio = $('#fechaInicio_' + idPila).val()
        var fechaFin = $('#fechaFin_' + idPila).val()
    }
    /*var cont = 0;
    var input_id = new Array();
    var input_value = new Array();
    $(".1").each(function(index){
        var id = $(this).attr("id")
        var value = $('#' + id).val()
        input_id[cont]= id
        input_value[cont]= value
        cont = cont + 1
    });
    cadena = "input_id=" + input_id +
            "&input_value=" + input_value +
            "&idReceta=" + idReceta +
            "&band=" + 16;*/

    cadena = "idReceta=" + idReceta +
            "&idClasificacion=" + idClasificacion +
            "&idPila=" + idPila +
            "&pila=" + pila +
            "&porcentaje=" + porcentaje +
            "&fechaInicio=" + fechaInicio +
            "&fechaFin=" + fechaFin +
            "&band=" + 16;
    //console.log(cadena)
    $.ajax({
        type: "POST",
        url: "../modelo/actividad_insertar.php",
        data: cadena,
        success: function (r){
            console.log(r)
            if(r==2){
                alertify.error('El porcentaje de la pila supera el maximo permitido por la clasificación');
            }else{
                //if(r == 1 || r == 2){
                    //$('#modalInsertarPilaRecetas').modal('hide')
                    load_pilas_receta(idReceta)
                /*}else{
                    separ = r.split(",");
                    for(var x=0; x < separ.length; x++){
                        $('#pila_' + separ[x]).prop("style","border: 1px solid; border-color: red;")
                        $('#fechaInicio_' + separ[x]).prop("style","border: 1px solid; border-color: red;")
                        $('#fechaFin_' + separ[x]).prop("style","border: 1px solid; border-color: red;")
                    }
                    alert('Debe completar los campos marcados')
                }*/
            }
        }
    });
}
function open_historial_pilas(idReceta,idClasificacion){
    if($('#span_' + idClasificacion).hasClass('glyphicon-eye-open')){
        cadena = "idReceta=" + idReceta +
            "&idClasificacion=" + idClasificacion +
            "&band=" + 17;
        $.ajax({
            type: "POST",
            url: "../modelo/actividad_vistas.php",
            data: cadena,
            success: function (r){
                //console.log(r);
                $('#div_' + idClasificacion).html(r).fadeIn()
                $('#span_' + idClasificacion).removeClass('glyphicon-eye-open')
                $('#span_' + idClasificacion).addClass('glyphicon-eye-close')
            }
        });
    }else{
        $('#div_' + idClasificacion).html('').fadeIn()
        $('#span_' + idClasificacion).removeClass('glyphicon-eye-close')
        $('#span_' + idClasificacion).addClass('glyphicon-eye-open')
    }
}
function search_destinoActividad(){
    var idDestino = $('#idDestinoActividad').val();
    cadena = "band=" + 6 +
            "&idDestino=" + idDestino;
    $.ajax({
        type: "POST",
        url: "../modelo/buscar.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            $('#idActividadDestinos').html(r).fadeIn();
        }
    });
}
function cargar_clasif_recetas(){
    $('#div_clasif_recetas').html('');
    $('#text_clasif_recetas').val('');
    $('#text_porcentaje_clasif_recetas').val('');
    cadena = "band=" + 2;
    $.ajax({
        type: "POST",
        url: "../modelo/buscar.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            $('#div_clasif_recetas').html(r);
            $('#AgregarRecetasProduccion').prop('disabled',true);
        }
    });
}
function agregar_clasif_recetas(NomClasif){
    select_clasif_recetas = $('#select_clasif_recetas').val();
    text_clasif_recetas = $('#text_clasif_recetas').val();
    text_porcentaje_clasif_recetas = $('#text_porcentaje_clasif_recetas').val();
    porcentaje_clasif_recetas = $('#porcentaje_clasif_recetas').val();
    if((porcentaje_clasif_recetas == 0 || porcentaje_clasif_recetas == '0' || porcentaje_clasif_recetas == '') && NomClasif == '0'){
        alert('Agregue un valor al porcentaje')
    }else{
        if(NomClasif != '0'){
            select_clasif_recetas = '';
        }
        cadena = "band=" + 3 +
                "&text_clasif_recetas=" + text_clasif_recetas +
                "&text_porcentaje_clasif_recetas=" + text_porcentaje_clasif_recetas +
                "&select_clasif_recetas=" + select_clasif_recetas +
                "&Eliminar_lista=" + NomClasif +
                "&porcentaje_clasif_recetas=" + porcentaje_clasif_recetas;
        
        $.ajax({
            type: "POST",
            url: "../modelo/buscar.php",
            data: cadena,
            success: function (r){
                //console.log(r);
                separ = r.split("!!");
                if(separ[3]<=100){
                    $('#div_clasif_recetas').html(separ[0]);
                    $('#text_clasif_recetas').val(separ[1]);
                    $('#text_porcentaje_clasif_recetas').val(separ[2]);
                }else{
                    alert('El porcentaje supera al 100%');
                }
                if(separ[3]!=100){
                    $('#AgregarRecetasProduccion').prop('disabled',true);
                }else{
                    $('#AgregarRecetasProduccion').prop('disabled',false);
                }
            }
        });
    }
}
/*function eliminar_clasif_recetas(evn,variable){
    var text_eventos = $('#text_eventos').val();
    var select_patios = $('#select_patios_2').val();
    cadena = "operacion=" + 13 +
            "&select_patios=" + select_patios +
            "&fecha=" + fecha +
            "&text_eventos=" + text_eventos +
            "&evn=" + evn +
            "&variable=" + variable;
    $.ajax({
        type: "POST",
        url: "ctr.php",
        data: cadena,
        success: function (r){
            console.log(r);
            separ = r.split("!!");
            $('#select_eventos').html(separ[0]);
            $('#text_eventos').val(separ[1]);
        }
    });
}*/
function agregar_RecetasProduccion(n){
    text_clasif_recetas = $('#text_clasif_recetas').val();
    text_porcentaje_clasif_recetas = $('#text_porcentaje_clasif_recetas').val();
    nombre_receta_produccion = $('#nombre_receta_produccion').val();
    mezcla_producida = $('#mezcla_producida').val();
    pila_receta = $('#pila_receta').val();
    empresa = $('#empresa_produccion').val();
    patio = $('#patio_produccion').val();
    //destino_mezcla_producida = $('#destino_mezcla_producida').val();
    let iError = 0;
    if(nombre_receta_produccion=='' || nombre_receta_produccion==null || nombre_receta_produccion=='0'){
        $("#nombre_receta_produccion").prop("style","border: 1px solid; border-color: red");
    iError = iError + 1;
    }else{
        $("#nombre_receta_produccion").prop("style","border: 1px solid; border-color: #ccc");
    }
    if(empresa=='' || empresa==null || empresa=='0' || empresa==0){
        $("#empresa_produccion").prop("style","border: 1px solid; border-color: red");
    iError = iError + 1;
    }else{
        $("#empresa_produccion").prop("style","border: 1px solid; border-color: #ccc");
    }
    if(patio=='' || patio==null || patio=='0' || patio==0){
        $("#patio_produccion").prop("style","border: 1px solid; border-color: red");
    iError = iError + 1;
    }else{
        $("#patio_produccion").prop("style","border: 1px solid; border-color: #ccc");
    }
    if(mezcla_producida=='' || mezcla_producida==null || mezcla_producida=='0'){
        $("#mezcla_producida").prop("style","border: 1px solid; border-color: red");
    iError = iError + 1;
    }else{
        $("#mezcla_producida").prop("style","border: 1px solid; border-color: #ccc");
    }
    if(pila_receta=='' || pila_receta==null || pila_receta=='0'){
        $("#pila_receta").prop("style","border: 1px solid; border-color: red");
    iError = iError + 1;
    }else{
        $("#pila_receta").prop("style","border: 1px solid; border-color: #ccc");
    }
    if(iError>0){
        alertify.error('Falta completar los datos marcados')
    }else{
        cadena = "band=" + 4 +
                "&text_clasif_recetas=" + text_clasif_recetas +
                "&text_porcentaje_clasif_recetas=" + text_porcentaje_clasif_recetas +
                "&nombre_receta_produccion=" + nombre_receta_produccion +
                "&empresa=" + empresa +
                "&patio=" + patio +
                "&mezcla_producida=" + mezcla_producida + 
                "&pila_receta=" + pila_receta +
                "&permite_crear=" + n;
        $.ajax({
            type: "POST",
            url: "../modelo/buscar.php",
            data: cadena,
            success: function (r){
                //console.log(r);
                if(r=='nnn'){
                    swal("¿Quiere crear una nueva pila?", {
                        icon: "warning",
                        buttons: {
                            cancel: "Cancelar",
                            catch: {
                                text: 'ACEPTAR',
                                value: "catch",
                            },
                        },dangerMode: false,
                    })
                    .then((value) => {
                        switch (value) {
                     
                        case "catch":
                            var band = 14;
                            agregar_RecetasProduccion(1)
                            break;

                        default:
                            //swal("Got away safely!");
                        }
                    });
                }else if(r == 1){
                    $('#modalInsertarRecetasProduccion').modal('hide');
                    TablaActividad(12);
                }else{
                    alert('No se pudo guardar la receta');
                }
                //separ = r.split("!!");
            }
        });
    }
}
function delete_receta_produccion(idReceta){
    swal("¡Una vez eliminado, no podrás recuperar este registro!", {
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
            var band = 11;
            break;

        default:
            //swal("Got away safely!");
        }
        
        cadena = "idReceta=" + idReceta +
                "&band=" + band;
        $.ajax({
            type: "POST",
            url: "../modelo/actividad_insertar.php",
            data: cadena,
            success: function (r){
                //console.log(r);
                if (r == 1){
                    if(band == 11){
                        swal("Poof!", "Ha sido eliminado!", "success");
                    }
                    TablaActividad(12);
                    //self.location = "actividades.php";
                }
            }
        });
    });
}
function agregar_codigoDestino(op){    
    if (op == 1){
        var destino_codigo = $('#select_codigos_destino').val();
        var codigo = $('#codigo').val();
        if(destino_codigo != '0' && codigo != ''){
            cadena = "band=" + 5 +
                "&destino_codigo=" + destino_codigo +
                "&codigo=" + codigo;
            $.ajax({
                type: "POST",
                url: "../modelo/buscar.php",
                data: cadena,
                success: function (r){
                  //  console.log(r);
                    if(r == 1){
                        TablaActividad(13);
                        alertify.success('Registrado..');
                    }else{
                        alert('No se pudo guardar el código del destino');
                    }
                }
            });
        }
    }else{
        var id_empresa = $('#select_codigos_empresa').val();
        var codigo_empresa = $('#empresa').val();
        if(id_empresa != '0' && codigo_empresa != ''){
        //    alert(1)
            cadena = "band=" + 7 +
                "&id_empresa=" + id_empresa +
                "&codigo_empresa=" + codigo_empresa;
            $.ajax({
                type: "POST",
                url: "../modelo/buscar.php",
                data: cadena,
                success: function (r){
                    console.log(r);
                    if(r == 1){
                        TablaActividad(13);
                        alertify.success('Registrado..');
                    }else{
                        alert('No se pudo guardar el código de la Empresa');
                    }
                }
            });
        }
    }
}

function load_clasificacion_jerarquia(){
    var producto_jerarquia = $('#producto_jerarquia').val();
    cadena = "producto_jerarquia=" + producto_jerarquia +
            "&bandera=" + 14;
    $.ajax({
        type: "POST",
        url: "../modelo/actividad_vistas.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            $('#div_clasificacion_jerarquia').html(r).fadeIn()
        }
    });
}
function return_array_selected(){
    var cont = 0;
    var input_id = new Array();
    var input_value = new Array();
    $("#form_clasificacion >div >div >input").each(function(index){
        var id = $(this).attr("id")
        //var value = $(this).attr("value");
        var value = $('#' + id).val()
        input_id[cont]= id
        input_value[cont]= value
        cont = cont + 1
        //$(this).css(/* apply styles */);
    });
    cadena = "input_id=" + input_id +
            "&input_value=" + input_value +
            "&band=" + 12;
    $.ajax({
        type: "POST",
        url: "../modelo/actividad_insertar.php",
        data: cadena,
        success: function (r){
            //console.log(r)
            if(r == 1){
                load_clasificacion_jerarquia()
            }else{
                separ = r.split(",");
                for(var x=0; x < separ.length; x++){
                    $('#' + separ[x]).prop("style","border: 1px solid; border-color: red;")
                }
                alert('Debe asignar diferentes consecutivos')
            }
        }
    });
}
function inhabilitar_receta(idReceta,status){
    if(status==0){
        texto = "Habilitar";
    }else{
        texto = "Inhabilitar";
    }
    swal("¿Desea " + texto + " la receta?", {
        icon: "warning",
        buttons: {
            cancel: "Cancelar",
            catch: {
                text: texto,
                value: "catch",
            },
            /*defeat: {
                text: 'texto',
            },*/
        },dangerMode: false,
    })
    .then((value) => {
        switch (value) {
     
        case "catch":
            var band = 14;
            break;

        default:
            //swal("Got away safely!");
        }
        
        cadena = "idReceta=" + idReceta +
                "&band=" + band;
        $.ajax({
            type: "POST",
            url: "../modelo/actividad_insertar.php",
            data: cadena,
            success: function (r){
                //console.log(r);
                if (r == 1){
                    swal("Poof!", "Ha sido inhabilitada!", "success");
                    TablaActividad(12);
                }
            }
        });
    });
}
function search_pila_receta(object){
    cadena = "band=" + 52 +
            "&pila=" + object.value;
    $.ajax({
        type: "POST",
        url: "buscar_v2.php",
        data: cadena,
        success: function(r){
            console.log(r)
            $('#'+object.list.id).html(r).fadeIn()
            setTimeout(()=>{
                $('#'+object.list.id).removeAttr("style");            
            },500);                           
        }
    });
}
/*********************************************************/
/*function evdragstart(ev) {
    ev.dataTransfer.setData("text",ev.target.id);    
}
function evdragover (ev) {
    ev.preventDefault();
}
function evdrop(ev,el) {
    ev.stopPropagation();
    ev.preventDefault();
    data=ev.dataTransfer.getData("text");
    ev.target.appendChild(document.getElementById(data));
}*/