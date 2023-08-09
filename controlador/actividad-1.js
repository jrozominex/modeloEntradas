function TablaActividad(val){
    if(val == 'Operativo'){
        document.getElementById('titulo').innerHTML = "Actividades Operativas";
        for(i=0;i<document.formButton.elements.length; i++)
        {  if(document.formButton.elements[i].type=="button")
            {   $('#'+document.formButton.elements[i].value).removeClass('btn-success'); 
                $('#'+document.formButton.elements[i].value).addClass('btn-default'); 
            }
        }
        $('#operativo').show();
        $('#Mtto_eventual').hide();
        $('#Mtto_general').hide();
        $('#Mtto_lavado').hide();
        $('#Mtto_electrico').hide();
        $('#Reporte_fallas').hide();
        $('#Div_categorias').hide();
        $('#Div_pilas').hide();
        $('#Div_rendimientos').hide();
        $('#Div_ActividadDestinos').hide();
    }else if(val == 'Mtto eventual'){
        document.getElementById('titulo').innerHTML = val;
        for(i=0;i<document.formButton.elements.length; i++)
        {  if(document.formButton.elements[i].type=="button")
            {   $('#'+document.formButton.elements[i].value).removeClass('btn-success'); 
                $('#'+document.formButton.elements[i].value).addClass('btn-default'); 
            }
        }
        $('#'+val).removeClass('btn-default'); 
        $('#'+val).addClass('btn-success'); 
        $('#operativo').hide();
        $('#Mtto_eventual').show();
        $('#Mtto_general').hide();
        $('#Mtto_lavado').hide();
        $('#Mtto_electrico').hide();
        $('#Reporte_fallas').hide();
        $('#Div_categorias').hide();
        $('#Div_pilas').hide();
        $('#Div_rendimientos').hide();
        $('#Div_ActividadDestinos').hide();
    }else if(val == 'Mtto general'){
        document.getElementById('titulo').innerHTML = val;
        for(i=0;i<document.formButton.elements.length; i++)
        {  if(document.formButton.elements[i].type=="button")
            {   $('#'+document.formButton.elements[i].value).removeClass('btn-success'); 
                $('#'+document.formButton.elements[i].value).addClass('btn-default'); 
            }
        }
        $('#'+val).removeClass('btn-default'); 
        $('#'+val).addClass('btn-success'); 
        $('#operativo').hide();
        $('#Mtto_eventual').hide();
        $('#Mtto_general').show();
        $('#Mtto_lavado').hide();
        $('#Mtto_electrico').hide();
        $('#Reporte_fallas').hide();
        $('#Div_categorias').hide();
        $('#Div_pilas').hide();
        $('#Div_rendimientos').hide();
        $('#Div_ActividadDestinos').hide();
    }else if(val == 'Mtto lavado'){
        document.getElementById('titulo').innerHTML = val;
        for(i=0;i<document.formButton.elements.length; i++)
        {  if(document.formButton.elements[i].type=="button")
            {   $('#'+document.formButton.elements[i].value).removeClass('btn-success'); 
                $('#'+document.formButton.elements[i].value).addClass('btn-default'); 
            }
        }
        $('#'+val).removeClass('btn-default'); 
        $('#'+val).addClass('btn-success'); 
        $('#operativo').hide();
        $('#Mtto_eventual').hide();
        $('#Mtto_general').hide();
        $('#Mtto_lavado').show();
        $('#Mtto_electrico').hide();
        $('#Reporte_fallas').hide();
        $('#Div_categorias').hide();
        $('#Div_pilas').hide();
        $('#Div_rendimientos').hide();
        $('#Div_ActividadDestinos').hide();
    }else if(val == 'Mtto electrico'){
        document.getElementById('titulo').innerHTML = val;
        for(i=0;i<document.formButton.elements.length; i++)
        {  if(document.formButton.elements[i].type=="button")
            {   $('#'+document.formButton.elements[i].value).removeClass('btn-success'); 
                $('#'+document.formButton.elements[i].value).addClass('btn-default'); 
            }
        }
        $('#'+val).removeClass('btn-default'); 
        $('#'+val).addClass('btn-success'); 
        $('#operativo').hide();
        $('#Mtto_eventual').hide();
        $('#Mtto_general').hide();
        $('#Mtto_lavado').hide();
        $('#Mtto_electrico').show();
        $('#Reporte_fallas').hide();
        $('#Div_categorias').hide();
        $('#Div_pilas').hide();
        $('#Div_rendimientos').hide();
        $('#Div_ActividadDestinos').hide();
    }else if(val == 'Reporte fallas'){
        document.getElementById('titulo').innerHTML = val;
        for(i=0;i<document.formButton.elements.length; i++)
        {  if(document.formButton.elements[i].type=="button")
            {   $('#'+document.formButton.elements[i].value).removeClass('btn-success'); 
                $('#'+document.formButton.elements[i].value).addClass('btn-default'); 
            }
        }
        $('#'+val).removeClass('btn-default'); 
        $('#'+val).addClass('btn-success'); 
        $('#operativo').hide();
        $('#Mtto_eventual').hide();
        $('#Mtto_general').hide();
        $('#Mtto_lavado').hide();
        $('#Mtto_electrico').hide();
        $('#Reporte_fallas').show();
        $('#Div_categorias').hide();
        $('#Div_pilas').hide();
        $('#Div_rendimientos').hide();
        $('#Div_ActividadDestinos').hide();
    }else if(val == 'Categoria Maquinaria'){
        document.getElementById('titulo').innerHTML = val;
        for(i=0;i<document.formButton.elements.length; i++)
        {  if(document.formButton.elements[i].type=="button")
            {   $('#'+document.formButton.elements[i].value).removeClass('btn-success'); 
                $('#'+document.formButton.elements[i].value).addClass('btn-default'); 
            }
        }
        //$('#'+val).removeClass('btn-default'); 
        //$('#'+val).addClass('btn-success'); 
        $('#operativo').hide();
        $('#Mtto_eventual').hide();
        $('#Mtto_general').hide();
        $('#Mtto_lavado').hide();
        $('#Mtto_electrico').hide();
        $('#Reporte_fallas').hide();
        $('#Div_categorias').show();
        $('#Div_pilas').hide();
        $('#Div_rendimientos').hide();
        $('#Div_ActividadDestinos').hide();
    }else if(val == 'Pilas'){
        document.getElementById('titulo').innerHTML = val;
        for(i=0;i<document.formButton.elements.length; i++)
        {  if(document.formButton.elements[i].type=="button")
            {   $('#'+document.formButton.elements[i].value).removeClass('btn-success'); 
                $('#'+document.formButton.elements[i].value).addClass('btn-default'); 
            }
        }
        //$('#'+val).removeClass('btn-default'); 
        //$('#'+val).addClass('btn-success'); 
        $('#operativo').hide();
        $('#Mtto_eventual').hide();
        $('#Mtto_general').hide();
        $('#Mtto_lavado').hide();
        $('#Mtto_electrico').hide();
        $('#Reporte_fallas').hide();
        $('#Div_categorias').hide();
        $('#Div_pilas').show();
        $('#Div_rendimientos').hide();
        $('#Div_ActividadDestinos').hide();
    }else if(val == 'Rendimientos'){
        document.getElementById('titulo').innerHTML = val;
        for(i=0;i<document.formButton.elements.length; i++)
        {  if(document.formButton.elements[i].type=="button")
            {   $('#'+document.formButton.elements[i].value).removeClass('btn-success'); 
                $('#'+document.formButton.elements[i].value).addClass('btn-default'); 
            }
        }
        //$('#'+val).removeClass('btn-default'); 
        //$('#'+val).addClass('btn-success'); 
        $('#operativo').hide();
        $('#Mtto_eventual').hide();
        $('#Mtto_general').hide();
        $('#Mtto_lavado').hide();
        $('#Mtto_electrico').hide();
        $('#Reporte_fallas').hide();
        $('#Div_categorias').hide();
        $('#Div_pilas').hide();
        $('#Div_rendimientos').show();
        $('#Div_ActividadDestinos').hide();
    }else if(val == 'Actividad Destinos'){
        document.getElementById('titulo').innerHTML = val;
        for(i=0;i<document.formButton.elements.length; i++)
        {  if(document.formButton.elements[i].type=="button")
            {   $('#'+document.formButton.elements[i].value).removeClass('btn-success'); 
                $('#'+document.formButton.elements[i].value).addClass('btn-default'); 
            }
        }
        //$('#'+val).removeClass('btn-default'); 
        //$('#'+val).addClass('btn-success'); 
        $('#operativo').hide();
        $('#Mtto_eventual').hide();
        $('#Mtto_general').hide();
        $('#Mtto_lavado').hide();
        $('#Mtto_electrico').hide();
        $('#Reporte_fallas').hide();
        $('#Div_categorias').hide();
        $('#Div_pilas').hide();
        $('#Div_rendimientos').hide();
        $('#Div_ActividadDestinos').show();
    }
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
                    if (r == 1) {
                        alert("agregado con exito");
                        self.location = "actividades.php";
                    } else {
                        alert("No se pudo registrar la actividad")
                    }
                }
            });
        }else{
            alert('Seleccione una clase')
        }
    }
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
                //alert(r);
                if (r == 1) {
                    alert("agregado con exito");
                    self.location = "actividades.php";
                } else {
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
                    alert("Modificada con exito");
                    self.location = "actividades.php";
                } else {
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
                if (r == 1) {
                    alert("Modificada con exito");
                    self.location = "actividades.php";
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
    if(Descripcion != ''){
        var band = 5;
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
                    //self.location = "actividades.php";
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
                    //alertify.success("Se registró correctamente");
                    self.location = "actividades.php";
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
                    //alertify.success("Se registró correctamente");
                    self.location = "actividades.php";
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
    if(idActividad != '0' && idDestino != '0'){
        var band = 8;
        cadena = "idActividad=" + idActividad +
                "&idDestino=" + idDestino +
                "&band=" + band;
        $.ajax({
            type: "POST",
            url: "../modelo/actividad_insertar.php",
            data: cadena,
            success: function (r){
                console.log(r);
                if (r == 1) {
                    //alertify.success("Se registró correctamente");
                    self.location = "actividades.php";
                } else {
                    alertify.error("No se pudo registrar");
                }
            }
        });
    }
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
            cancel: "",
            catch: {
                text: "Eliminar!",
                value: "catch",
            },
            defeat: {
                text: texto,
            },
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
                console.log(r);
                if (r == 1){
                    if(band == 9){
                        swal("",texto_largo,"success");
                    }else if(band == 10){
                        swal("Poof!", "Ha sido eliminado!", "success");
                    }
                    //alertify.success("Se registró correctamente");
                    self.location = "actividades.php";
                }
            }
        });
    });
}