<?php
require("DAO.php");

//Creamos una instancia de nuestro "modelo"
$DAO = new DAO();
if (empty($_POST["operacion"])) 
{	$operacion=1;
} else 
{   $operacion=$_POST["operacion"];
   // $vez=1;
}

switch($operacion)
{	case 1 : // GENERAR 
		$vez=0;
	    $empresa=$DAO->buscar_empresa();
	    $destino=$DAO->buscar_destino();
		require ("agregar.php");
	break;

	case 20:
		$empresa=$DAO->buscar_empresa();
	    $destino=$DAO->buscar_destino();
	    $id_tiquetes = $_POST['array'];
	    $id_factura_venta = $_POST['id_factura_venta'];
		$tok = explode(',',$id_tiquetes);
		$count = count($tok);
		if($_POST['band'] ==1){
			$var = 0;
			for ($i = 0; $i<$count; $i++){
				if ($tok[$i] <> '')
				{ 	$result=$DAO->ingresar_fact($tok[$i],$id_factura_venta);
					if($result=1){
						$var = 1;
					}
				}
			}
			if($var == 1){
				echo $var;
			}
		}elseif($_POST['band'] == 2){
			$var = 0;
			for ($i = 0; $i<$count; $i++){
				if ($tok[$i] <> '')
				{ 	$result=$DAO->sacar_fact($tok[$i]);
					if($result){
						$var = 1;
					}
				}
			}
			if($var == 1){
				echo $var;
			}
		}
	break;
	
	case 30:
		$id_empresa=$_POST["empresa"];
		$id_cliente=$_POST["cliente"];
		$fecha_ini=$_POST["fecha_ingreso"];
		$fecha_fin=$_POST["fecha_salida"];
		if(isset($_POST['fact_pendientes']))
			$pre_fact=$_POST['fact_pendientes'];
		else
			$pre_fact=0;
		if($pre_fact<>'0')
		{	$lista_preFac=$DAO->buscar_pre_liquid($pre_fact);	}
		
		$viajes=$DAO->buscar_viajes($id_empresa,$id_cliente,$fecha_ini,$fecha_fin);	

	    $empresa=$DAO->buscar_empresa();
	    $destino=$DAO->buscar_destino();
	    require ("agregar.php");
	break;

	case 40:
		$id_empresa=$_POST["empresa"];
		$id_cliente=$_POST["cliente"];
		//$id_destino=$_POST["destino"];
		$fecha_ini=$_POST["fecha_ingreso"];
		$fecha_fin=$_POST["fecha_salida"];
		//$pre_fact=$_POST['fact_pendientes'];
		//$clasificacion=$_POST['selec_produtos'];

		$nueva_preFac=$DAO->generar_pre_liquid($id_empresa,$id_cliente);
		$viajes=$DAO->buscar_viajes($id_empresa,$id_cliente,$fecha_ini,$fecha_fin);

	    //$empresa=$DAO->buscar_empresa();
	    //$destino=$DAO->buscar_destino();
	    require ("agregar.php");
	break;

	case 50:
		$id_factura_venta=$_POST["id_factura_venta"];
		$fecha_fact_asentada=$_POST["fecha_fact_asentada"];
		$fact_asociada = $_POST['fact_asociada'];
		$tm_despacho=$_POST["tm_despacho"];
		$tm_llegada=$_POST["tm_llegada"];
		$tm_llegada1=$_POST["tm_llegada1"];
		$id_empresa=$_POST["empresa"];

		$asentar_preFac=$DAO->asentar_pre_liquid($id_empresa,$id_factura_venta,$fecha_fact_asentada,$tm_despacho,$tm_llegada,$tm_llegada1,$fact_asociada);
		echo $asentar_preFac;
		//$viajes=$DAO->buscar_viajes($id_empresa,$id_cliente,$id_destino,$fecha_ini,$fecha_fin,$clasificacion);	

	    //$empresa=$DAO->buscar_empresa();
	    //$destino=$DAO->buscar_destino();
	    //require ("agregar.php");
	break;

	default:
         die("Error ::: !!! No se encontro ninguna operación, por lo tanto no es posible realizar acción alguna !!! ");
    break;	
}
?>