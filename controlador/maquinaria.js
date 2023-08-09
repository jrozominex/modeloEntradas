function load_table_equipos(){
    cadena = "band=" + 0;
     $.ajax({
        type: "POST",
        url: "../modelo/maquinaria_insertar.php",
        data: cadena,
        success: function (r){
            //console.log(r)
            $('#tbody_equipos').html(r)
            datatable()
        }
    });
}
function registro_horo_mantto(){
     horometro = $('#horo_mantto').val();
     equipo = $('#equipos').val();
     band = 4;
     cadena = "horometro=" + horometro +
              "&equipo=" + equipo +
              "&band=" + band;
     $.ajax({
        type: "POST",
        url: "../modelo/maquinaria_insertar.php",
        data: cadena,
        success: function (r){
            //console.log(r)
            if (r == 1) {
                alert("agregado con exito");
                self.location = "maquinaria.php";
            } else {
                alert("No se pudo registrar la maquinaria");
            }
        }
    });
}
function AgregarMaquinaria(){
    marca = $('#marca').val();
    //modelo = $('#modelo').val();
    id = $('#id').val();
    grupo = $('#grupo').val();
    propietario = $('#propietario').val();
    band = 1;
    cadena = "marca=" + marca +
             //"&modelo=" + modelo +
             "&id=" + id +
             "&grupo=" + grupo +
             "&propietario=" + propietario +
             "&band=" + band;
    $.ajax({
        type: "POST",
        url: "../modelo/maquinaria_insertar.php",
        data: cadena,
        success: function (r){
            console.log(r);
            if (r == 1) {
                alert("agregado con exito");
                load_table_equipos()
            } else {
                alert("No se pudo registrar la maquinaria");
            }
        }
    });
}
/*function VerMantto(datos){
    data = datos.split("||");
    $('#idEquiposMantto').val(data[0]);
    $('#propietarioMantto').val(data[1]);
    $('#TipoMantto').val(data[2]);
    $('#ModeloMantto').val(data[3]);
    $('#Horo_vidaMantto').val(data[4]);
    $('#HoroMantto').val(data[5]);
}*/
function load_activities(idEquipo){
    var cadena = "idEquipos=" + idEquipo +
                "&band=" + 5;
    $.ajax({
        type: "POST",
        url: "../modelo/maquinaria_insertar.php",
        data: cadena,
        success: function (r) {
            $('#body_activities').html(r)
            select_activities(idEquipo)
            $('#idEquipo_actividad').val(idEquipo)
        }
    });
}
function select_activities(idEquipo){
    var cadena = "idEquipos=" + idEquipo +
                "&band=" + 6;
    $.ajax({
        type: "POST",
        url: "../modelo/maquinaria_insertar.php",
        data: cadena,
        success: function (r) {
            $('#actividad_maquinas').html(r)
        }
    });
}
function add_activitie_machine(){
    var idEquipo_actividad = $('#idEquipo_actividad').val()
    var actividad_maquinas = $('#actividad_maquinas').val()

    var cadena = "idEquipo_actividad=" + idEquipo_actividad +
                "&actividad_maquinas=" + actividad_maquinas +
                "&band=" + 7;
    $.ajax({
        type: "POST",
        url: "../modelo/maquinaria_insertar.php",
        data: cadena,
        success: function (r) {
            //console.log(r)
            if(r == 1)
                load_activities(idEquipo_actividad)
        }
    });
}
function delete_activitie_machine(idEquipo,idActividad){
    var cadena = "idEquipo=" + idEquipo +
                "&idActividad=" + idActividad +
                "&band=" + 8;
    $.ajax({
        type: "POST",
        url: "../modelo/maquinaria_insertar.php",
        data: cadena,
        success: function (r) {
            //console.log(r)
            if(r == 1)
                load_activities(idEquipo)
        }
    });
}
function load_propietarios(){
    grupo = $('#grupo').val()
    var cadena = "grupo=" + grupo +
                "&band=" + 9;
    $.ajax({
        type: "POST",
        url: "../modelo/maquinaria_insertar.php",
        data: cadena,
        success: function (r){
            //console.log(r)
            $('#propietario').html(r).fadeIn()
        }
    });
}
function ver(id_registro){
    registro = id_registro;
    $('#registro').val(registro);
}
function ver1(id_registro){
    registro = id_registro;
    $('#registro1').val(registro);
}
function verHoroMantto(a){
    $('#equipos').val(a);
}
function vincular_equipo(idEquipo){
    var cadena = "idEquipo=" + idEquipo +
                "&band=" + 10;
    $.ajax({
        type: "POST",
        url: "../modelo/maquinaria_insertar.php",
        data: cadena,
        success: function (r){
            //console.log(r)
            $('#modalGrupoMaquinaria').modal('show')
            $('#bodyEquipo_agrupacion').html(r)
            //$('#propietario').html(r).fadeIn()
        }
    });
}
function load_listEquipo(){
    idEquipo_agrupacion = $('#idEquipo_agrupacion').val()
    listGrupo_agrupacion = $('#listGrupo_agrupacion').val()
    var cadena = "listGrupo_agrupacion=" + listGrupo_agrupacion +
                "&idEquipo_agrupacion=" + idEquipo_agrupacion +
                "&band=" + 11;
    $.ajax({
        type: "POST",
        url: "../modelo/maquinaria_insertar.php",
        data: cadena,
        success: function (r){
            //console.log(r)
            $('#listEquipo_vinculado').html(r).fadeIn()
        }
    });
}
function add_group_machine(){
    idEquipo_agrupacion = $('#idEquipo_agrupacion').val()
    listEquipo_vinculado = $('#listEquipo_vinculado').val()
    var cadena = "listEquipo_vinculado=" + listEquipo_vinculado +
                "&idEquipo_agrupacion=" + idEquipo_agrupacion +
                "&band=" + 12;
    $.ajax({
        type: "POST",
        url: "../modelo/maquinaria_insertar.php",
        data: cadena,
        success: function (r){
            //console.log(r)
            if(r == 1){
                vincular_equipo(idEquipo_agrupacion)
            }
        }
    });
}
function delete_group_machine(idEquipo,idEquipoVinculado){
    var cadena = "band=" + 13 +
                "&idEquipo=" + idEquipo + 
                "&idEquipoVinculado=" + idEquipoVinculado;
     $.ajax({
        type: "POST",
        url: "../modelo/maquinaria_insertar.php",
        data: cadena,
        success: function (r){
            //console.log(r)
            if(r == 1){
                vincular_equipo(idEquipo)
            }
        }
    });
}