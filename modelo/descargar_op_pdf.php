<?php
 	require_once '../librerias/MPDF57/mpdf.php';
	$var = $_POST['input_contenido_op'];
	$mode=''; // (Blanco para establecer el valor por defecto)<br>
    /* Se especifica un tamaño de página predefinido o como un arreglo<br>
     * de ancho y altura en milímetros */   
   $format=array(220,280);
    // Establecemos el tamaño de la fuente por defecto para el documento (pt)<br>
    $font_s='8'; // (Blanco para establecer el valor por defecto)<br>
    // Establecemos el tipo de letra familia a aplicar en el documento<br>
    $font_f='Arial'; // (Blanco para establecer el valor por defecto)<br>
    // Establecemos los márgenes de las páginas en milímetros para el documento<br>
    $marg_l=5;  // Margen izquierdo<br>
    $marg_r=5;  // Margen derecho<br>
    $marg_t=9; // Margen superior<br>
    $marg_b=10; // Margen inferior<br>
    $marg_h=5;  // Margen de la cabecera de la página<br>
    $marg_f=5;  // Margen del pie de página<br>
   
    //--- \\ AQUÍ APLICAMOS MPDF // --- <br>
 //  echo $echo;
    // Creamos un objeto de clase mPDF para crear el documento.<br>
    $mpdf=new mPDF($mode,$format ,$font_s,$font_f,$marg_l,$marg_r,$marg_t,$marg_b,$marg_h,$marg_f);

//  $mpdf = new mPDF();
	$mpdf -> writeHTML($var);
	$mpdf -> Output();
?>