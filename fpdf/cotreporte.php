<?php
include "../Conexion/conexion.php";
require './mc_table.php';
class PDF extends PDF_MC_Table {
	protected $B = 0;
	protected $I = 0;
	protected $U = 0;
	protected $HREF = '';
	protected $nomAsesor='';
	protected $celAsesor='';
	protected $emailAsesor='';
	protected $criterio='';
	protected $cotizacion='';
	function WriteHTML($html) {
		// Intérprete de HTML
		$html = str_replace("\n",' ',$html);
		$a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e) {
			if($i%2==0) {
				// Text
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
				else
					$this->Write(5,$e);
			}
			else {
				// Etiqueta
				if($e[0]=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
				else {
					// Extraer atributos
					$a2 = explode(' ',$e);
					$tag = strtoupper(array_shift($a2));
					$attr = array();
					foreach($a2 as $v) {
						if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
							$attr[strtoupper($a3[1])] = $a3[2];
					}
					$this->OpenTag($tag,$attr);
				}
			}
		}
	}
	function OpenTag($tag, $attr) {
		// Etiqueta de apertura
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,true);
		if($tag=='A')
			$this->HREF = $attr['HREF'];
		if($tag=='BR')
			$this->Ln(5);
	}
	function CloseTag($tag) {
		// Etiqueta de cierre
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF = '';
	}
	function SetStyle($tag, $enable) {
		// Modificar estilo y escoger la fuente correspondiente
		$this->$tag += ($enable ? 1 : -1);
		$style = '';
		foreach(array('B', 'I', 'U') as $s) {
			if($this->$s>0)
				$style .= $s;
		}
		$this->SetFont('',$style);
	}
	function PutLink($URL, $txt) {
		// Escribir un hiper-enlace
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}
	function BasicTable($st,$iva,$total,$abono,$saldo) {
		$asesor="Asesora Comercial: ".$this->nomAsesor." - ".$this->celAsesor." - ".$this->emailAsesor;
		$this->Cell(240,6,utf8_decode('El plazo aproximado de entrega es de veinticinco  (25) a treinta (30) días hábiles todo lo relacionado con los uniformes de línea, cuando'),0);
		$this->Cell(15,6,'Subtotal',1);
		$this->Cell(20,6,"$ ".number_format($st),1,0,'R');
		$this->Ln();
		$this->Cell(240,6,utf8_decode('sea dotación para el persona femenino administrativos será de cuarenta y cinco (45) días hábiles estimando de acuerdo a la cantidad.'),0);
		$this->Cell(15,6,'IVA 19%',1);
		$this->Cell(20,6,"$ ".number_format($iva),1,0,'R');
		$this->Ln();
		$this->Cell(240,6,utf8_decode('Los pantalones  de vestir si desean que vayan  con ruedo tienen  un costo adicional de $3,500.'),0);
		$this->Cell(15,6,'Total',1);
		$this->Cell(20,6,"$ ".number_format($total),1,0,'R');
		$this->Ln();
		$this->Cell(240,6,utf8_decode('Gerente Comercial: Laura Patiño - 310 2569693 - E-mail: lpatino@uniformesuno.com - ventas@uniformesuno.com - uniformesuno@gmail.com '),0);
		$this->Cell(15,6,'Abonos',1);
		$this->Cell(20,6,"$ ".number_format($abono),1,0,'R');
		$this->Ln();
		$this->Cell(240,6,$asesor,0);
		$this->Cell(15,6,'Saldo',1);
		$this->Cell(20,6,"$ ".number_format($saldo),1,0,'R');
		$this->Ln(10);
	}
	function LeyendaTable() {
		$this->Cell(270,4,'CONDICIONES GENERALES',0);
		$this->Ln();
		$this->Cell(270,4,'Datos fiscales',0);
		$this->Ln();
		$this->Cell(270,4,utf8_decode('NIT: 1.258.310-0. Dirección: Calle 1N # 0-19E Quinta Bosch - Cúcuta'),0);
		$this->Ln();
		$this->Cell(270,4,utf8_decode('Razón Social: Distribuciones y Representaciones UNO -  Luis Gonzalo Patiño Ramírez'),0);
		$this->Ln();
		$this->Cell(270,4,utf8_decode('Característica Tributaria: Régimen Común. Tarifa ICA: 5 X mil. Descripción: Fabricación Prendas de Vestir. Código CIIU: D180.000'),0);
		$this->Ln();
		$this->Cell(270,4,utf8_decode('Cuenta corriente #: 066060037612 de Davivienda'),0);
		$this->Ln();
		$this->Cell(270,4,utf8_decode('E-mail:contabilidad@uniformesuno.com  -  uniformesuno@hotmail.com  Teléfonos: 5742415  300-653 69 43'),0);
		$this->Ln(10);
		$this->Cell(270,6,'MODALIDADES DE PEDIDO Y PRECIO',0);
		$this->Ln();
		$this->Cell(270,4,utf8_decode('1. Durante el proceso de compra, el cliente declara aceptar plenamente y sin reservas la totalidad de las presentes Condiciones Generales de Venta. '),0);
		$this->Ln();
		$this->Cell(270,4,utf8_decode('2. Se confirmará su pedido a través del envío de un correo electrónico y/o con la Representante de ventas emitiendo una Orden de Pedido.'),0);
		$this->Ln();
		$this->Cell(270,4,utf8_decode('3. Uniformes por tallas (Se miden nuestro tallaje para confirmar con exactitud la talla solicitada)'),0);
		$this->Ln();
		$this->Cell(270,4,utf8_decode('4. En los precios de nuestros productos no están incluido el impuesto I.V.A del 19%.'),0);
		$this->Ln();
		$this->Cell(270,4,utf8_decode('5. Uniformes SOBREMEDIDAS se incrementan en $21,000 c/u incluyendo los uniformes de embarazo'),0);
		$this->Ln();
		$this->MultiCell(270,4,utf8_decode('6. Se reserva el derecho a modificar sus precios en cualquier momento, pero los productos se facturarán sobre la base de las tarifas en vigor en el momento del registro de los pedidos (a reserva de la disponibilidad que haya del producto).'),0);
		$this->Ln();
		$this->Cell(270,4,'DISPONIBILIDAD',0);
		$this->Ln();
		$this->MultiCell(270,4,utf8_decode('Nuestro proveedores tiene como principio mantener un inventario de telas para la elaboración del productos disponible para su entrega, de un promedio de dos (2) a cinco (5) días,  en caso de que no tengan existencia el color solicitado, y después de haberse realizado el pedido el usuario será informado de la posibilidad de realizar un cambio.'),0);
		$this->Ln();
		$this->Cell(270,4,'PAGO',0);
		$this->Ln();
		$this->Cell(270,4,'50% de anticipo - 50% contra entrega.',0);
		$this->Ln();
		$this->Cell(270,4,utf8_decode('Una vez tengamos confirmación de que el pago ha sido realizado procederemos a la solicitud de telas a nuestros proveedores.'),0);
		$this->Ln(10);
		$this->Cell(270,4,'ENTREGA',0);
		$this->Ln();
		$this->MultiCell(270,4,utf8_decode('1. Uniformes UNO enviara la(s) mercancía(s) a la dirección que haya solicitado el comprador. El plazo aproximado de entrega es de veinticinco  (25) a treinta (30) días hábiles todo lo relacionado con los uniformes de línea, cuando sea dotación para el persona femenino administrativos será de cuarenta y cinco (45) días hábiles.  Si  surgiera algún imprevisto el cliente será contactado inmediatamente para ofrecerle una solución o un cambio de producto.'),0);
		$this->Ln();
		$this->MultiCell(270,4,utf8_decode('2. Uniformes UNO informará al cliente de la salida del pedido del almacén de distribución, mediante llamada telefónica o el envío de un email, facilitando también al cliente un número de contacto telefónico y un número para saber sobre el estado de su pedido.'),0);
		$this->Ln();
		$this->Cell(270,4,utf8_decode('3. Al entregar al vendedor el pedido se considera materializada la transacción comercial con Uniformes UNO.'),0);
		$this->Ln();
		$this->MultiCell(270,4,utf8_decode('4.  Corresponde al destinatario comprobar el pedido en el momento de la entrega y hacer entonces todas las reservas y reclamaciones que aparecieran justificadas. Las reservas y reclamaciones deben ir dirigidas a lpatino@uniformesuno.com y uniformesuno@hotmail.com'),0);
		$this->Ln();
	}
	function getCotizacion() {
		return $this->cotizacion;
	}
	function getCodcot() {
		return $this->criterio;
	}
	// Tabla encabezado
	function EncabezadoTable() {
		
                
		$this->Cell(20,6,'',0);
		$this->Cell(95,6,'',0);
		$this->Cell(20,6,'',0);
		$this->Cell(75,6,'',0);
		$this->Cell(20,6,'',0);
		$this->Cell(45,6,utf8_decode('Versión 7'),0,0,'C');
		$this->Ln();
		$this->Cell(20,6,'',0);
		$this->Cell(95,6,'',0);
		$this->Cell(20,6,'',0);
		$this->Cell(75,6,'',0);
		$this->Cell(20,6,'',0);
		$this->Cell(45,6,'Septiembre',0,0,'C');
		$this->Ln();
		$this->Cell(20,6,'',0);
		$this->Cell(95,6,'',0);
		$this->Cell(20,6,'',0);
		$this->Cell(75,6,'',0);
		$this->Cell(20,6,'',0);
		$this->Cell(45,6,'2015',0,0,'C');
		$this->Ln();
		$this->Cell(20,6,'',0);
		$this->Cell(95,6,'',0);
		$this->Cell(20,6,'',0);
		$this->Cell(75,6,'',0);
		$this->Cell(20,6,utf8_decode('Página:'),0,0,'L');
		$this->Cell(45,6,'1',0,0,'C');
		$this->Ln();
		$this->Cell(20,6,'Cliente:',0);
		$this->Cell(95,6,'',0,0,'L');
		$this->Cell(20,6,'Nit:',0,0,'L');
		$this->Cell(75,6,'',0,0,'L');
		$this->Cell(20,6,'Fecha',0,0,'L');
		$this->Cell(45,6,'17/12/2016',0,0,'C');
		$this->Ln();
		$this->Cell(20,6,utf8_decode('Atención:'),0);
		$this->Cell(95,6,'',0,0,'L');
		$this->Cell(20,6,utf8_decode('Teléfono:'),0,0,'L');
		$this->Cell(75,6,'',0,0,'L');
		$this->Cell(20,6,utf8_decode('Cotización:'),0,0,'L');
		$this->Cell(45,6,'121212',0,0,'C');
		$this->Ln();
		$this->Cell(20,6,'Email:',0);
		$this->Cell(95,6,'',0,0,'J');
		$this->Cell(20,6,utf8_decode('Dirección:'),0,0,'L');
		$this->Cell(75,6,'',0,0,'L');
		$this->Cell(20,6,'',0,0,'L');
		$this->Cell(45,6,'',0,0,'C');
		$this->Ln();
		$this->Cell(20,6,'Productos:',0);
		$this->Cell(95,6,'',0);
		$this->Cell(20,6,'',0);
		$this->Cell(75,6,'',0);
		$this->Cell(20,6,'SC:',0,0,'L');
		$this->Cell(45,6,'143546',0,0,'C');
		$this->Ln(10);
	}
}
$html = 'CONDICIONES GENERALES: Ahora puede imprimir fácilmente texto mezclando diferentes estilos: <b>negrita</b>, <i>itálica</i>,
<u>subrayado</u>, o ¡ <b><i><u>todos a la vez</u></i></b>!<br><br>También puede incluir enlaces en el
texto, como <a href="http://www.fpdf.org">www.fpdf.org</a>, o en una imagen: pulse en el logotipo.';
$pdf=new PDF('L','mm','A4');
//Primera página
$pdf->AddPage();
$pdf->Image('../Imagenes/siga_logo.png',21,10,10,0,'');
$pdf->Ln(0);
$pdf->Image('../Imagenes/siga_logo.png',34,10,46,0,'');
$pdf->Ln(0);
$pdf->SetLeftMargin(15);

$pdf->SetFont('Arial','',10);
$pdf->EncabezadoTable();

$pdf->SetFont('Arial','',8);

//Table with 20 rows and 4 columns
$pdf->SetWidths(array(10,20,20,30,20,15,25,15,20,20,15,15,15,15,20));
srand(microtime()*1000000);

$pdf->Row1(array('Item','Cargo','Prenda','Imagen','Referencia','Manga',utf8_decode('Descripción'),'Bordado','Tela','Combinado',utf8_decode('Número Empleado'),'Cantidad uniformes','Total prendas','Valor unitario antes IVA','Valor total antes de IVA'));

//Segunda página
$pdf->SetLeftMargin(15);
$pdf->SetFont('Arial','',12);
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->LeyendaTable();

$pdf->Output("I","Cotización_".$pdf->getCotizacion(),true);
?>