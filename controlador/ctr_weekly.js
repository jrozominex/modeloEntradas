function tipo_informe(){
    $('#bandera').val(1);
    var select_informe = $('#select_informe').val();
    if(select_informe == 1){
        //$('#inv_cucuta').show();
        $('#div_productos').show();
        $('#clientes').hide();
        $('#div_cliente').hide();
        $('#cambio_semanas').show();
    }else if(select_informe == 2){
        $('#inv_cucuta').hide();
        $('#div_productos').hide();
        //$('#clientes').show();
        $('#div_cliente').show();
        $('#cambio_semanas').show();
    }else{
        $('#inv_cucuta').hide();
        $('#div_productos').hide();
        $('#clientes').hide();
        $('#div_cliente').hide();
        $('#cambio_semanas').hide();
    }
}
function tipo_prod(){
    $('#bandera').val(1);
    var select_productos = $('#select_productos').val();
    var fecha_inicio = $('#fecha_inicio').val();
    var fecha_fin = $('#fecha_fin').val();
    var semana_actual = $('#input_week').val();
    if(select_productos != 0){
        $('#inv_cucuta').show();
        $('#div_productos').show();
        if(select_productos == 'CARBON'){
            $('#div_inventory_palmarejo').show();
            $('#div_inventory_in_transit').show();
            for (var i = 1; i < 6; i++) {
                $('#td_palmarejo'+i).show();
                $('#td_transit'+i).show();
            }
            //CARBON
            band = 1;
            cadena = "fecha_inicio=" + fecha_inicio +
                    "&fecha_fin=" + fecha_fin +
                    "&semana_actual=" + semana_actual +
                    "&band=" + band;
            $.ajax({
                type: "POST",
                url: "../modelo/consultas_weekly1.php",
                data: cadena,
                success: function (r) {
                    let bodySubActividad = '';
                    let bodySubActividad1 = '';
                    console.log(r);
                    d = r.split("||");
                    document.getElementById('INITIAL_INVENTORY').innerHTML = d[0];
                    document.getElementById('summary_cucuta').innerHTML = d[0];
                    document.getElementById('Inventary_expo_cucuta').innerHTML = d[0];
                    document.getElementById('Total_Purchases').innerHTML = d[1];
                    document.getElementById('input_cucuta').innerHTML = d[1];
                    document.getElementById('inputs_expo_cucuta').innerHTML = d[1];
                    //INFORMACION DE PUERTO BRISA
                    document.getElementById('INVENTORY_PTO_BRISA').innerHTML = d[8];
                    document.getElementById('summary_pto_brisa').innerHTML = d[8];
                    document.getElementById('Inventary_pto_brisa').innerHTML = d[8];
                    document.getElementById('Coal_Sold_loaded_exported').innerHTML = d[9];
                    document.getElementById('output_pto_brisa').innerHTML = d[9];
                    document.getElementById('outputs_pto_brisa').innerHTML = d[9];
                    document.getElementById('resumen_current_pto_brisa').innerHTML = d[10];
                    document.getElementById('CURRENT_INVENTORY_PTO_BRISA').innerHTML = d[10];
                    document.getElementById('TOTAL_CURRENT_PTO_BRISA').innerHTML = d[10];
                    if (d[2] != ""){
                        const datosActividad = JSON.parse(d[2]);
                        datosActividad.forEach(data => {
                          bodySubActividad += `
                          <div class="row">
                          <div class="col-sm-10" style="text-align: left;">
                            <p>${data.Proveedor}</p>
                          </div>
                          <div class="col-sm-2" style="text-align: right;">
                            <p style="text-align: right;">${data.Tm}</p>
                          </div>
                          </div>`
                        });
                        $('#Purchases').html(bodySubActividad);
                        if(d[3] > 0){
                            bodyOthers = '<div class="row"><div class="col-sm-10"><p>Others <button class="btn btn-default btn-xs">+</button></p></div><div class="col-sm-2"><p>'+d[3]+'</p></div></div>';
                            $('#Others').html(bodyOthers);
                        }
                    }
                    document.getElementById('Total_Coal_Trucked').innerHTML = d[4];
                    document.getElementById('input_pto_brisa').innerHTML = d[4];
                    document.getElementById('inputs_expo_pto_brisa').innerHTML = d[4];
                    document.getElementById('output_cucuta').innerHTML = d[4];
                    document.getElementById('outputs_cliente_cucuta').innerHTML = d[4];
                    $('#input_Coal_Received_from_Cucuta').val(d[4]);
                    document.getElementById('Coal_Received_from_Cucuta').innerHTML = d[4];
                    if (d[5] != 0){
                        const datosActividad1 = JSON.parse(d[5]);
                        datosActividad1.forEach(data1 => {
                          bodySubActividad1 += `
                          <div class="row">
                          <div class="col-sm-10" style="text-align: left;">
                            <p>${data1.Proveedor}</p>
                          </div>
                          <div class="col-sm-2" style="text-align: right;">
                            <p style="text-align: right;">${data1.Tm}</p>
                          </div>
                          </div>`
                        });
                        $('#Trucked').html(bodySubActividad1);
                    }
                    document.getElementById('CURRENT_INVENTORY_CUCUTA').innerHTML = d[6];
                    document.getElementById('resumen_current_cucuta').innerHTML = d[6];
                    document.getElementById('TOTAL_CURRENT_CUCUTA').innerHTML = d[6];
                    document.getElementById('RENDIMIENTO_INVENTORY_CUCUTA').innerHTML = d[7];
                    if(d[7] <= 0){
                        if($('#span_rendimiento').hasClass('glyphicon glyphicon-arrow-up')){
                            $('#span_rendimiento').removeClass('glyphicon glyphicon-arrow-up');
                        }
                        $('#span_rendimiento').addClass('glyphicon glyphicon-arrow-down');
                        document.getElementById('span_rendimiento').style = 'color: red;';
                        document.getElementById('RENDIMIENTO_INVENTORY_CUCUTA').style = 'color: red;';
                    }else{
                        if($('#span_rendimiento').hasClass('glyphicon glyphicon-arrow-down')){
                            $('#span_rendimiento').removeClass('glyphicon glyphicon-arrow-down');
                        }
                        $('#span_rendimiento').addClass('glyphicon glyphicon-arrow-up');
                        document.getElementById('span_rendimiento').style = 'color: green;';
                        document.getElementById('RENDIMIENTO_INVENTORY_CUCUTA').style = 'color: green;';
                    }
                    buques();
                    puerto_brisa();
                }
            });
            //$('#div_clasif').show();
        }else if(select_productos == 'COQUE'){
            //COQUE
            $('#div_clasif').hide();
            $('#div_inventory_palmarejo').hide();
            $('#div_inventory_in_transit').hide();
            for (var i = 1; i < 6; i++) {
                $('#td_palmarejo'+i).hide();
                $('#td_transit'+i).hide();
            }
            band = 2;
            cadena = "fecha_inicio=" + fecha_inicio +
                    "&fecha_fin=" + fecha_fin +
                    "&semana_actual=" + semana_actual +
                    "&band=" + band;
            $.ajax({
                type: "POST",
                url: "../modelo/consultas_weekly1.php",
                data: cadena,
                success: function (r) {
                    let bodySubActividad = '';
                    let bodySubActividad1 = '';
                    console.log(r);
                    d = r.split("||");
                    document.getElementById('INITIAL_INVENTORY').innerHTML = d[0];
                    document.getElementById('summary_cucuta').innerHTML = d[0];
                    document.getElementById('Inventary_expo_cucuta').innerHTML = d[0];
                    document.getElementById('Total_Purchases').innerHTML = d[1];
                    document.getElementById('input_cucuta').innerHTML = d[1];
                    document.getElementById('inputs_expo_cucuta').innerHTML = d[1];
                    //INFORMACION DE PUERTO BRISA COQUE
                    document.getElementById('INVENTORY_PTO_BRISA').innerHTML = d[7];
                    document.getElementById('summary_pto_brisa').innerHTML = d[7];
                    document.getElementById('Inventary_pto_brisa').innerHTML = d[7];
                    document.getElementById('Coal_Sold_loaded_exported').innerHTML = d[8];
                    document.getElementById('output_pto_brisa').innerHTML = d[8];
                    document.getElementById('outputs_pto_brisa').innerHTML = d[8];
                    document.getElementById('resumen_current_pto_brisa').innerHTML = d[9];
                    document.getElementById('CURRENT_INVENTORY_PTO_BRISA').innerHTML = d[9];
                    document.getElementById('TOTAL_CURRENT_PTO_BRISA').innerHTML = d[9];
                    if (d[2] != "a"){
                        const datosActividad = JSON.parse(d[2]);
                        datosActividad.forEach(data => {
                          bodySubActividad += `
                          <div class="row">
                          <div class="col-sm-10" style="text-align: left;">
                            <p>${data.Proveedor}</p>
                          </div>
                          <div class="col-sm-2" style="text-align: right;">
                            <p style="text-align: right;">${data.Tm}</p>
                          </div>
                          </div>`
                        });
                        $('#Purchases').html(bodySubActividad);
                        /*if(d[3] > 0){
                            bodyOthers = '<div class="row"><div class="col-sm-10"><p>Others <button class="btn btn-default btn-xs">+</button></p></div><div class="col-sm-2"><p>'+d[3]+'</p></div></div>';
                            $('#Others').html(bodyOthers);
                        }*/
                    }else{
                        $('#Purchases').html(bodySubActividad);
                         $('#Others').html(bodySubActividad);
                    }
                    document.getElementById('Total_Coal_Trucked').innerHTML = d[3];
                    document.getElementById('input_pto_brisa').innerHTML = d[3];
                    document.getElementById('inputs_expo_pto_brisa').innerHTML = d[3];
                    document.getElementById('output_cucuta').innerHTML = d[3];
                    document.getElementById('outputs_cliente_cucuta').innerHTML = d[3];
                    $('#input_Coal_Received_from_Cucuta').val(d[3]);
                    document.getElementById('Coal_Received_from_Cucuta').innerHTML = d[3];
                    if (d[4] != 0){
                        const datosActividad1 = JSON.parse(d[4]);
                        datosActividad1.forEach(data1 => {
                          bodySubActividad1 += `
                          <div class="row">
                          <div class="col-sm-10" style="text-align: left;">
                            <p>${data1.Proveedor}</p>
                          </div>
                          <div class="col-sm-2" style="text-align: right;">
                            <p style="text-align: right;">${data1.Tm}</p>
                          </div>
                          </div>`
                        });
                        $('#Trucked').html(bodySubActividad1);
                    }
                    document.getElementById('CURRENT_INVENTORY_CUCUTA').innerHTML = d[5];
                    document.getElementById('resumen_current_cucuta').innerHTML = d[5];
                    document.getElementById('TOTAL_CURRENT_CUCUTA').innerHTML = d[5];
                    document.getElementById('RENDIMIENTO_INVENTORY_CUCUTA').innerHTML = d[6];
                    if(d[6] <= 0){
                        if($('#span_rendimiento').hasClass('glyphicon glyphicon-arrow-up')){
                            $('#span_rendimiento').removeClass('glyphicon glyphicon-arrow-up');
                        }
                        $('#span_rendimiento').addClass('glyphicon glyphicon-arrow-down');
                        document.getElementById('span_rendimiento').style = 'color: red;';
                        document.getElementById('RENDIMIENTO_INVENTORY_CUCUTA').style = 'color: red;';
                    }else{
                        if($('#span_rendimiento').hasClass('glyphicon glyphicon-arrow-down')){
                            $('#span_rendimiento').removeClass('glyphicon glyphicon-arrow-down');
                        }
                        $('#span_rendimiento').addClass('glyphicon glyphicon-arrow-up');
                        document.getElementById('span_rendimiento').style = 'color: green;';
                        document.getElementById('RENDIMIENTO_INVENTORY_CUCUTA').style = 'color: green;';
                    }
                }
            });
            //
        }else if(select_productos == 'GRANULADO'){
            //GRANULADO
            $('#div_inventory_palmarejo').hide();
            $('#div_inventory_in_transit').hide();
        }
    }else{
        $('#inv_cucuta').hide();
        //$('#div_productos').hide();
        $('#div_clasif').hide();
    }

}

/*function buques(){
    band1 = 2;
    cadena = "band=" + band1;
    $.ajax({
        type: "POST",
        url: "../modelo/consultas_weekly.php",
        data: cadena,
        success: function (r){
            d = r.split("||");
            let bodySubActividad = '';
            let bodySubActividad1 = '<tr><td colspan="2" align="center">TOTAL '+d[2]+'</td><td align="right">'+d[1]+'</td></tr>';
            $('#tfoot_buque').html(bodySubActividad1);
            //console.log(r);
            if (d[0] != ""){
                const datosActividad = JSON.parse(d[0]);
                datosActividad.forEach(data => {
                  bodySubActividad += `
                  <tr>
                    <td>${data.NombreMotoNave}</td>
                    <td>${data.FechaPartida}</td>
                    <td align="right">${data.PesoAforo}</td>
                  </tr>`
                });
                $('#tbody_buques').html(bodySubActividad);
            }
        }
    });
}*/

/*function puerto_brisa(){
    band2 = 3;
    var fecha_inicio = $('#fecha_inicio').val();
    var fecha_fin = $('#fecha_fin').val();
    var semana_actual = $('#input_week').val();
    cadena = "fecha_inicio=" + fecha_inicio +
            "&fecha_fin=" + fecha_fin +
            "&semana_actual=" + semana_actual +
            "&band=" + band2;
    $.ajax({
        type: "POST",
        url: "../modelo/consultas_weekly.php",
        data: cadena,
        success: function (r){
            //console.log(r);
            d = r.split("||");
            document.getElementById('INVENTORY_PTO_BRISA').innerHTML = d[0];
            document.getElementById('summary_pto_brisa').innerHTML = d[0];
            document.getElementById('Inventary_pto_brisa').innerHTML = d[0];
            document.getElementById('Coal_Sold_loaded_exported').innerHTML = d[1];
            document.getElementById('output_pto_brisa').innerHTML = d[1];
            document.getElementById('outputs_pto_brisa').innerHTML = d[1];
            var ingresos = $('#input_Coal_Received_from_Cucuta').val();
            //alert(ingresos);
            document.getElementById('CURRENT_INVENTORY_PTO_BRISA').innerHTML = d[2];
            document.getElementById('resumen_current_pto_brisa').innerHTML = d[2];
            document.getElementById('TOTAL_CURRENT_PTO_BRISA').innerHTML = d[2];
            /*let bodySubActividad = '';
            let bodySubActividad1 = '<tr><td colspan="2" align="center">TOTAL '+d[2]+'</td><td align="right">'+d[1]+'</td></tr>';
            $('#tfoot_buque').html(bodySubActividad1);
            console.log(r);
            if (d[0] != ""){
                const datosActividad = JSON.parse(d[0]);
                datosActividad.forEach(data => {
                  bodySubActividad += `
                  <tr>
                    <td>${data.NombreMotoNave}</td>
                    <td>${data.FechaPartida}</td>
                    <td align="right">${data.PesoAforo}</td>
                  </tr>`
                });
                $('#tbody_buques').html(bodySubActividad);
            }//
        }
    });
}*/

function conjuntos(nombre) {
    if (nombre == 'INVENTORY CUCUTA') {
        if($('#span_1').hasClass('glyphicon-minus')){
            $('#INVENTORY_CUCUTA').hide();
            $('#span_1').removeClass('glyphicon-minus');
            $('#span_1').addClass('glyphicon-plus');
        }else{
            $('#INVENTORY_CUCUTA').show();
            $('#span_1').removeClass('glyphicon-plus');
            $('#span_1').addClass('glyphicon-minus');
        }
    }else if(nombre == 'INVENTORY IN TRANSIT'){
        if($('#span_2').hasClass('glyphicon-minus')){
            $('#INVENTORY_IN_TRANSIT').hide();
            $('#span_2').removeClass('glyphicon-minus');
            $('#span_2').addClass('glyphicon-plus');
        }else{
            $('#INVENTORY_IN_TRANSIT').show();
            $('#span_2').removeClass('glyphicon-plus');
            $('#span_2').addClass('glyphicon-minus');
        }
    }else if(nombre == 'DIVISIONS'){
        if($('#span_3').hasClass('glyphicon-minus')){
            $('#DIVISIONS').hide();
            $('#span_3').removeClass('glyphicon-minus');
            $('#span_3').addClass('glyphicon-plus');
        }else{
            $('#DIVISIONS').show();
            $('#span_3').removeClass('glyphicon-plus');
            $('#span_3').addClass('glyphicon-minus');
        }
    }
}
function mover(movimiento){
    
    year = $('#year').val();
    numero_semanas = $('#num_weeks').val();
    semana_actual = $('#input_week').val();

    if(movimiento == 'atras'){
        if((semana_actual-1) > 0){
            if((semana_actual-1) < 10){
                var week = '0' + (semana_actual-1);
            }else{
                var week = (semana_actual-1);
            }
            //console.log(week);
            $('#input_week').val(week);
            document.getElementById('semanas').innerHTML = year + ' - Week ' + (week);
            $('#form_week').submit();
        }
    }else if(movimiento == 'adelante'){
        if((semana_actual+1) <= numero_semanas){
            if((parseInt(semana_actual)+1) < 10){
                var week = '0' + (parseInt(semana_actual)+1);
            }else{
                var week = (parseInt(semana_actual)+1);
            }
            //console.log(week);
            $('#input_week').val(week);
            document.getElementById('semanas').innerHTML = year + ' - Week ' + (week);
            $('#form_week').submit();
        }
    }else if(movimiento == 'recargo'){
        document.getElementById('semanas').innerHTML = year + ' - Week ' + (semana_actual);
    }
    $('#bandera').val(1);
}

function tipo_cliente(){
    $('#bandera').val(1);
    $('#clientes').show();
    //VARIABLES
    var select_cliente = $('#select_cliente').val();
    document.getElementById('title_inventory_despacho').innerHTML = 'INVENTORY ' + select_cliente;
    var fecha_inicio = $('#fecha_inicio').val();
    var fecha_fin = $('#fecha_fin').val();
    var semana_actual = $('#input_week').val();
    if(select_cliente == 'MINA CALENTURITAS'){
        var bandera = 4;
    }else{
        var bandera = 5;
    }
    if(select_cliente != '0'){

        cadena = "fecha_inicio=" + fecha_inicio +
            "&fecha_fin=" + fecha_fin +
            "&semana_actual=" + semana_actual +
            "&select_cliente=" + select_cliente +
            "&band=" + bandera;
    $.ajax({
        type: "POST",
        url: "../modelo/consultas_weekly.php",
        data: cadena,
        success: function (r){
           // console.log(r);
            d = r.split("||");
            document.getElementById('Total_Purchases_clientes').innerHTML = d[0];
            let bodySubActividad = '';
            const datosActividad = JSON.parse(d[1]);
            datosActividad.forEach(data => {
              bodySubActividad += `
              <div class="row">
              <div class="col-sm-10" style="text-align: left;">
                <p>${data.Proveedor}</p>
              </div>
              <div class="col-sm-2" style="text-align: right;">
                <p style="text-align: right;">${data.Tm}</p>
              </div>
              </div>`
            });
            $('#Purchases_calenturitas').html(bodySubActividad);
            if(select_cliente == 'MINA CALENTURITAS'){
                //COLOCA EL INVENTARIO
            }
            document.getElementById('name_trucked_clientes').innerHTML = 'COAL TRUCKED ' + select_cliente;
            document.getElementById('label_summary_inventory').innerHTML = select_cliente;
            document.getElementById('value_trucked_clientes').innerHTML = d[2];
            document.getElementById('Total_Coal_Trucked_ventas_cliente').innerHTML = d[2];
            document.getElementById('CURRENT_INVENTORY_CLIENTE').innerHTML = d[3];
            document.getElementById('Coal_Received_from_Cucuta_cliente').innerHTML = d[0];
            document.getElementById('inputs_cliente').innerHTML = d[0];
            document.getElementById('inputs_cliente_cucuta').innerHTML = d[0];
            document.getElementById('outputs_cliente_cucuta').innerHTML = d[2];
        }
    });

    }else{
        alert('Seleccione un cliente');
        $('#clientes').hide();
    }
}