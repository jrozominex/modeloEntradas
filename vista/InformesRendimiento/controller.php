<?php
require_once '../../modelo/conexion.php';
include ("../../../modelo/clase_encrip.php");
$echo = '';


if(isset($_GET['id'])){

	
	if($_GET['id']=='1'){// agregar precinto


		 $fecha11 =  explode('/',$_GET['fecha1'])[2].'/' .explode('/',$_GET['fecha1'])[1] .'/'.explode('/',$_GET['fecha1'])[0] ;
		 $fecha12 =  explode('/',$_GET['fecha2'])[2].'/' .explode('/',$_GET['fecha2'])[1] .'/'.explode('/',$_GET['fecha2'])[0] ;
		 $where = '';

			if($_GET['idDepartamento']!='0'){ 
				$where = " and Departamentos.idDepartamento = '".$_GET['idDepartamento']."'";
			}

		   $sql = "SELECT Departamentos.Descripcion AS Departamento,Plantas.NombrePlanta,COUNT(Hornos.idHorno) as Hornos,Hornos.Jornada,(Hornos.Jornada/24) as Dias,(SUM(Capacidad)/COUNT(Hornos.idHorno)) as Cargue,
	        SUM(((DATEDIFF(DAY, '$fecha11', '$fecha12')+1)/(Hornos.Jornada/24))*Capacidad) AS Consumo_mezcla,(SUM(CapacidadDeshorne)/COUNT(Hornos.idHorno)) as Deshorne,
	        SUM(((DATEDIFF(DAY, '$fecha11', '$fecha12')+1)/(Hornos.Jornada/24))*CapacidadDeshorne) AS Produccion_coque,'$fecha11' as FechaInicio,'$fecha12' as FechaFin
			FROM Hornos INNER JOIN plantas ON Hornos.idPlanta=Plantas.idDestino
			INNER JOIN Destino ON Plantas.idDestinoAsociado=Destino.idDestino
			INNER JOIN Departamentos ON Destino.idDepartamento=Departamentos.idDepartamento ".$where."
			WHERE Hornos.Alias NOT LIKE '%AJUSTE%' and ajuste=0 AND NombrePlanta!='222'
			GROUP BY Departamentos.Descripcion,Plantas.NombrePlanta,Hornos.Jornada
			ORDER BY Departamentos.Descripcion,Plantas.NombrePlanta";

		 $result = sqlsrv_query($conn,$sql);
		 // $rows=sqlsrv_num_rows($result);


       
      	 $echoo='';
      	$iteracion_num =0 ;
         while ($iteracion = sqlsrv_fetch_array($result)){

         		  if($iteracion_num!=0) $echoo .=',';
         		  $iteracion_num ++;
                  
                  $echoo .='{"Departamento":"'.$iteracion['Departamento'].'",';
                  $echoo .='"Nombre_Planta":"'.$iteracion['NombrePlanta'].'",';
                  $echoo .='"Hornos":'.$iteracion['Hornos'].',';
                  $echoo.='"Jornada":"'.$iteracion['Jornada'].'",';
                  $echoo .='"Dias":"'.$iteracion['Dias'].'",';
                  $echoo .='"Cargue":"'.number_format($iteracion['Cargue'],2).'",';
                  $echoo .='"Consumo_mezcla":"'.number_format($iteracion['Consumo_mezcla'],2).'",';
                  $echoo .='"Deshorne":"'.number_format($iteracion['Deshorne'],2).'",';
                  $echoo .='"Produccion_coque":"'.number_format($iteracion['Produccion_coque'],2).'",';
                  $echoo .='"FechaInicio":"'.$iteracion['FechaInicio'].'",';
                  $echoo .='"FechaFin":"'.$iteracion['FechaFin'].'"}';
                  
          }

            $echo .='{
    			"total": '.$iteracion_num.',
   			     "records": [
      			  '.$echoo;

           $echo .=']}';

       }elseif($_GET['id']=='2'){// agregar precinto

  
       	  $fecha11 =  explode('/',$_GET['fecha1'])[2].'/' .explode('/',$_GET['fecha1'])[1] .'/'.explode('/',$_GET['fecha1'])[0] ;
		  $fecha12 =  explode('/',$_GET['fecha2'])[2].'/' .explode('/',$_GET['fecha2'])[1] .'/'.explode('/',$_GET['fecha2'])[0] ;


		   $sql = "SELECT idDepartamento,Departamento,NombrePlanta,AVG(Indice_carga) AS [Prom. cargue estimado],SUM(Capacidad) AS [Consumo_mezcla estimado],SUM(Indice_carga) AS [Consumo_mezcla real],
			AVG(Indice_deshorne) AS [Prom. produccion estimado],SUM(CapacidadDeshorne) AS [Produccion_coque estimado],SUM(Indice_deshorne) AS [Produccion_coque real],MIN(CAST(FechaAlimentacion AS date)) AS FechaInicio,
			MAX(CAST(FechaAlimentacion AS date)) AS FechaFin
			FROM vCoquizacion
			WHERE (CAST(FechaAlimentacion AS date) BETWEEN '$fecha11' AND '$fecha12') 
			GROUP BY idDepartamento,Departamento,vCoquizacion.NombrePlanta 
			ORDER BY idDepartamento,Departamento,NombrePlanta";

		 $result = sqlsrv_query($conn,$sql);
       
      	 $echoo='';
      	 $iteracion_num =0 ;
         


         	if($_GET['idDepartamento']!='0'){        

         		while ($iteracion = sqlsrv_fetch_array($result)){			   	   

	         		if($iteracion['idDepartamento']==$_GET['idDepartamento']){

	         		  if($iteracion_num!=0) $echoo .=',';
	         		  $iteracion_num ++;         		  
	                  
	                  $echoo .='{"Departamento":"'.$iteracion['Departamento'].'",';
	                  $echoo .='"Nombre_Planta":"'.$iteracion['NombrePlanta'].'",';
	                  $echoo .='"cargue_estimado":'.number_format($iteracion['Prom. cargue estimado'],2).',';
	                  $echoo.='"mezcla_estimado":"'.number_format($iteracion['Consumo_mezcla estimado'],2).'",';
	                  $echoo .='"Consumo_mezcla_real":"'.number_format($iteracion['Consumo_mezcla real'],2).'",';
	                  $echoo .='"produccion_estimado":"'.number_format($iteracion['Prom. produccion estimado'],2).'",';
	                  $echoo .='"coque_estimado":"'.number_format($iteracion['Produccion_coque estimado'],2).'",';
	                  $echoo .='"coque_real":"'.number_format($iteracion['Produccion_coque real'],2).'",';
	                  $echoo .='"Fecha_Inicio":"'.date_format($iteracion['FechaInicio'], 'Y-m-d').'",';
	                  $echoo .='"Fecha_Fin":"'.date_format($iteracion['FechaFin'], 'Y-m-d').'"}';

	                  }

	            }
             }else{
             	while ($iteracion = sqlsrv_fetch_array($result)){

 					  if($iteracion_num!=0) $echoo .=',';
	         		  $iteracion_num ++;         		  
	                  
	                  $echoo .='{"Departamento":"'.$iteracion['Departamento'].'",';
	                  $echoo .='"Nombre_Planta":"'.$iteracion['NombrePlanta'].'",';
	                  $echoo .='"cargue_estimado":'.number_format($iteracion['Prom. cargue estimado'],2).',';
	                  $echoo.='"mezcla_estimado":"'.number_format($iteracion['Consumo_mezcla estimado'],2).'",';
	                  $echoo .='"Consumo_mezcla_real":"'.number_format($iteracion['Consumo_mezcla real'],2).'",';
	                  $echoo .='"produccion_estimado":"'.number_format($iteracion['Prom. produccion estimado'],2).'",';
	                  $echoo .='"coque_estimado":"'.number_format($iteracion['Produccion_coque estimado'],2).'",';
	                  $echoo .='"coque_real":"'.number_format($iteracion['Produccion_coque real'],2).'",';
	                  $echoo .='"Fecha_Inicio":"'.date_format($iteracion['FechaInicio'], 'Y-m-d').'",';
	                  $echoo .='"Fecha_Fin":"'.date_format($iteracion['FechaFin'], 'Y-m-d').'"}';

	             }

             }
                  
          

            $echo .='{
    			"total": '.$iteracion_num.',
   			     "records": [
      			  '.$echoo;

           $echo .=']}';

	   }elseif($_GET['id']=='3'){

	   
	   	 $sql = "SELECT d.idDepartamento,d.Descripcion
	   	 FROM vDepartamentos d
		 WHERE idPais='DB3F42D7-8E8A-40D8-91EA-F463785E31D2'";
		 $iteracion_num =0 ;
		 $result = sqlsrv_query($conn,$sql);
		 $echo.= '[';

		   while ($iteracion = sqlsrv_fetch_array($result)){

		   	 if($iteracion_num!=0) $echo .=',';
         		$iteracion_num ++; 

		   			$echo.= '{ "id": "'.$iteracion['idDepartamento'].'", "text": "'.strtoupper(utf8_encode($iteracion['Descripcion'])).'" }';
		   }
		   $echo.= ']';


	   }


}
           echo $echo;


?>