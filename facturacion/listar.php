<?php 
    include ("../conectar.php");
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>jQuery table multiselect plugin demo</title>
    <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link href="../estilos/estilo.css" type="text/css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" >
</head>
<body>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
<div class="container-fluid">
    <div class="row">
        
    <div class="col-sm-6">
        <h1>FACTURACION</h1>
        <form name="form2" id="form2">
        <table   class="table table-hover table-condensed table-bordered table-responsive table-striped breadcrumb">
            <thead>
            <tr>
                <th >N° Tiquete</th>
                <th>FechaRegistro</th>
                <th>Horas trabajadas</th>
                <th>Fecha Cierre</th>
                <th>Estado</th>
                <th>Empresa</th>
                <th>Patio</th>
                <th>Maquinaria</th>
                <th>Operador</th>
            </tr>
            </thead>
            <tbody id="table3">
                <?php
                $sql="SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte,  Registro_tique_cargadores.horas_trabajadas, 
Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado, Registro_tique_cargadores.fecha_cierre_tique, 
Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, Usuarios.NombreUsuarioLargo
FROM Registro_tique_cargadores 
LEFT JOIN Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor 
LEFT JOIN Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino 
LEFT JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo 
LEFT JOIN Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario 
LEFT JOIN detalle_equipos ON Equipos.idEquipo = detalle_equipos.idEquipos 
WHERE Registro_tique_cargadores.Estado='5' ORDER BY Registro_tique_cargadores.cod_reporte DESC";
            $r = sqlsrv_query($conn,$sql);
            while($user = sqlsrv_fetch_array($r)){
                ?>
            <tr id="<?php echo $a;?>">
                <td hidden=""><input type="checkbox" name="empresa2[]" value="<?php echo $user['id_registro']; ?>"></td>
                <td style="width:10%"><?php echo $user['cod_reporte']; ?></td>
                <td><?php echo date_format($user['fecha_apertura_tique'],'Y-m-d'); ?></td>
                <td><?php echo $user['horas_trabajadas']; ?></td>
                <td><?php echo date_format($user['fecha_cierre_tique'],'Y-m-d'); ?></td>
                <td><?php echo $user['estado']; ?></td>
                <td><?php echo $user['NombreCorto']; ?></td>
                <td><?php echo $user['Descripcion']; ?></td>
                <td><?php echo $user['NombreCargador']." - ".$user['Identificacion']; ?></td>
                <td><?php echo $user['NombreUsuarioLargo']; ?></td>
            </tr>
         <?php
                }
                ?>
            </tbody>
            <tbody id="table2">
            </tbody>
        </table>
        </form>
    </div>
    <div class="col-sm-6">
        <h1>VIAJES</h1>
        <form name="form3" id="form3">
        <table  class="table table-hover table-condensed table-bordered table-responsive table-striped breadcrumb">
            <thead>
                <th >N° Tiquete</th>
                <th>FechaRegistro</th>
                <th>Horas trabajadas</th>
                <th>Fecha Cierre</th>
                <th>Estado</th>
                <th>Empresa</th>
                <th>Patio</th>
                <th>Maquinaria</th>
                <th>Operador</th>
            </thead>
            <tbody id="table1">
            <?php
            $a = 0;
            /*$sql = "SELECT * FROM tz_MovimientoTransporte where FechaRegistro='2019-08-21' and tipomovimiento='Traslado' 
            and Producto='Coque'";*/
            $sql="SELECT Registro_tique_cargadores.id_registro, Registro_tique_cargadores.cod_reporte,  Registro_tique_cargadores.horas_trabajadas, 
Registro_tique_cargadores.fecha_apertura_tique, Registro_tique_cargadores.estado, Registro_tique_cargadores.fecha_cierre_tique, 
Proveedores.NombreCorto, Destino.Descripcion, Equipos.Descripcion AS NombreCargador, Equipos.Identificacion, Usuarios.NombreUsuarioLargo
FROM Registro_tique_cargadores 
LEFT JOIN Proveedores ON Registro_tique_cargadores.id_proveedor = Proveedores.idProveedor 
LEFT JOIN Destino ON Registro_tique_cargadores.id_patio = Destino.idDestino 
LEFT JOIN Equipos ON Registro_tique_cargadores.id_maquinaria = Equipos.idEquipo 
LEFT JOIN Usuarios ON Registro_tique_cargadores.id_usuario = Usuarios.idUsuario 
LEFT JOIN detalle_equipos ON Equipos.idEquipo = detalle_equipos.idEquipos 
WHERE Registro_tique_cargadores.Estado='4' ORDER BY Registro_tique_cargadores.cod_reporte DESC";
            $r = sqlsrv_query($conn,$sql);
            while($user = sqlsrv_fetch_array($r)){
                ?>
            <tr id="<?php echo $a;?>">
                <td hidden=""><input type="checkbox" name="empresa1[]" value="<?php echo $user['id_registro']; ?>"></td>
                <td style="width:10%"><?php echo $user['cod_reporte']; ?></td>
                <td><?php echo date_format($user['fecha_apertura_tique'],'Y-m-d'); ?></td>
                <td><?php echo $user['horas_trabajadas']; ?></td>
                <td><?php echo date_format($user['fecha_cierre_tique'],'Y-m-d'); ?></td>
                <td><?php echo $user['estado']; ?></td>
                <td><?php echo $user['NombreCorto']; ?></td>
                <td><?php echo $user['Descripcion']; ?></td>
                <td><?php echo $user['NombreCargador']." - ".$user['Identificacion']; ?></td>
                <td><?php echo $user['NombreUsuarioLargo']; ?></td>
            </tr>
         <?php
                }
                ?>
            </tbody>
            <tbody id="table4">
            </tbody>
        </table>
    </div>
    </form>
    </div>
</div>
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="multiselect.js" type="text/javascript"></script>
<script type="text/javascript">
    if (window.document.addEventListener){
       window.document.addEventListener("keydown", myFunction2, false);
    } else {
       window.document.attachEvent("onkeydown", myFunction2);
    }
    function myFunction2(evnt)
    {   var band = 0;
        var band1 = 0;
        var operacion = 20;
        var ev = (evnt) ? evnt : event;
        var code=(ev.which) ? ev.which : event.keyCode;
        if (code == 13 ){
            var aplica = new Array();
            for (i=0;i<document.form2.elements.length;i++)
            {   if(document.form2.elements[i].name == "empresa1[]"){
                    aplica[i] = "'"+(document.form2.elements[i].value)+"'"; 
                    band = 1;                        
                }
            }
            for (i=0;i<document.form3.elements.length;i++)
            {   if(document.form3.elements[i].name == "empresa2[]"){
                    aplica[i] = "'"+(document.form3.elements[i].value)+"'"; 
                    band = 2;                       
                }
            }
            if(band == 1){
                var array =aplica.toString();
                cadena = "array=" + array+
                        "&band=" + band+
                        "&operacion=" + operacion;
            }else if(band == 2){
                var array =aplica.toString();
                cadena = "array=" + array+
                        "&band=" + band+
                        "&operacion=" + operacion;
            }
            //alert(cadena);
            $.ajax({
                type: "POST",
                url: "ctr.php",
                data: cadena,
                success: function (r) {
                   // alert(r);
                    if(r == 1){
                        self.location = "listar.php";
                    }else{
                        alert('No se pudo actualizar');
                    }                    
                }
            });            
        }
    }
    $(function () {
        var valores = '';
        $('#table1').multiSelect({
            actcls: 'info', 
            selector: 'tr', 
            except: ['tbody'], 
            statics: ['.danger', '[data-no="1"]'], 
            callback: function (items){
                        //document.form1.elements[i].checked=1;
                //data+=items;
               // var tr=$(items).parents("tr").appendTo("#table2 tbody");
                $('#table2').empty().append(items.clone().removeClass('info').addClass('warning'));
            }
        });
    })
    $(function () {
        var valores = '';
        $('#table3').multiSelect({
            actcls: 'info', 
            selector: 'tr', 
            except: ['tbody'], 
            statics: ['.danger', '[data-no="1"]'], 
            callback: function (items){
                //data+=items;
               // var tr=$(items).parents("tr").appendTo("#table2 tbody");
                $('#table4').empty().append(items.clone().removeClass('info').addClass('success'));
            }
        });
    })    

</script>
</body>
</html>