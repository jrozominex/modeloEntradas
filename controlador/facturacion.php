<script type="text/javascript">
	$(document).ready(function(){
		verificar();
	})

	function verificar(){
		<?php 
		if(isset($_POST['operacion'])){
			?>
			document.getElementById('Nuevo_reg').disabled=false;
			<?php
		}else{
			?>
			document.getElementById('Nuevo_reg').disabled=true;
			<?php
		}
		?>
	}

	function Nueva_liquidacion(){
		var b = 1;
   		texto="Seleccione:<br><br>\n";
   		if($('#empresa').val()=='0'){
   			texto+= "\n- Una empresa<br>";
			b=0;
   		}
   		if($('#cliente').val()=='0')
   		{	texto+= "\n- Un Cliente<br>";
			b=0;  	}

		if(b==0)
		{  alert(texto);  }
		else
		{	$('#form2').submit();
			$('#operacion').val(2);
			$('#consultas').submit();	

   		}
	}

	function validar()
   	{ 	centinela=1;
   		var empresa = $('#empresa').val();
   		var cliente = $('#cliente').val();
   		var factura = $('#select_fact').val();
   		var clasificacion = $('#selec_produtos').val();
   		var fecha_ingreso = $('#fecha_ingreso').val();
   		var fecha_salida = $('#fecha_salida').val();
   		texto="Seleccione estos campos:<br><br>\n";

		if(empresa == '0')
		{   texto+= "\n- Seleccione una empresa<br>";
			centinela=0;
		}
		if(fecha_ingreso == '')
		{   texto+= "\n- Seleccione una fecha de ingreso<br>";
			centinela=0;
		}
		if(fecha_salida == '')
		{   texto+= "\n- Seleccione una fecha de salida<br>";
			centinela=0;
		}
		if(fecha_ingreso > fecha_salida)
		{   texto+= "\n- Fecha ingreso mayor que: "+fecha_salida+"<br>";
			centinela=0;
		}
		if(centinela!=0)
		{  $('#operacion').val(1);
			$('#consultas').submit();	
		}
	}
</script>