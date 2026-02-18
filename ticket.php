<?php
setlocale(LC_CTYPE, 'es_MX');

include "core/controller/Core.php";
include "core/controller/Database.php";
include "core/controller/Executor.php";
include "core/controller/Model.php";

include "core/app/model/UserData.php";
include "core/app/model/SellData.php";
include "core/app/model/OperationData.php";
include "core/app/model/ProductData.php";
include "core/app/model/StockData.php";
include "core/app/model/ConfigurationData.php";
include "core/app/model/PersonData.php";
include "fpdf/fpdf.php";

function fix_mayus($texto) {
    if (empty($texto)) return "";
    
    // Diccionario de conversión manual para evitar fallos de librerías
    $minusculas = array('ñ', 'á', 'é', 'í', 'ó', 'ú', 'â', 'ê', 'î', 'ô', 'û');
    $mayusculas = array('Ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Â', 'Ê', 'Î', 'Ô', 'Û');
    
    // Pasamos a mayúsculas básicas (a-z)
    $t = strtoupper($texto);
    
    // Cambiamos las eñes y acentos usando el diccionario
    $t = str_replace($minusculas, $mayusculas, $t);
    
    // En lugar de utf8_decode, usamos iconv si está disponible o una conversión manual
    // Esto es lo que evita que el PDF salga en blanco en PHP 8
    if (function_exists('iconv')) {
        return iconv('UTF-8', 'windows-1252//IGNORE', $t);
    } else {
        // Opción de reserva si iconv no está activo
        return mb_convert_encoding($t, 'ISO-8859-1', 'UTF-8');
    }
}
session_start();
if(isset($_SESSION["user_id"])){ Core::$user = UserData::getById($_SESSION["user_id"]); }
$symbol = ConfigurationData::getByPreffix("currency")->val;
if($symbol=="€"){ $symbol=chr(128); }
else if($symbol=="₡"){ 
//echo intval("€");
	$symbol=    '₡';



}


$title = ConfigurationData::getByPreffix("ticket_title")->val;
$iva_val = ConfigurationData::getByPreffix("imp-val")->val;
$ticket_image = ConfigurationData::getByPreffix("ticket_image")->val;

$stock = StockData::getPrincipal();
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$user = $sell->getUser();



$pdf = new FPDF($orientation='P',$unit='mm', array(45,350));

$pdf->AddPage();
$pdf->SetFont('Arial','B',8);    //Letra Arial, negrita (Bold), tam. 20
//$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

//$pdf->SetFont('DejaVu','',8);

//$pdf->setXY(5,0);
$plusforimage =0;
if($ticket_image!=""){
	$src = "storage/configuration/".$ticket_image;
	if(file_exists($src)){
		$pdf->Image($src,12,2,15);		
		$plusforimage=25;
	}
}

$textypos = 11+$plusforimage;
$pdf->setY(2);
$pdf->setX(2);
//$pdf->Cell(5,$textypos,strtoupper($title));
//$pdf->Cell(5, $textypos, fix_mayus($stock->field1));

$textypos = 11 + $plusforimage;
$pdf->setY(2);
$pdf->setX(2);

// Primero el Título del Sistema (Inventio Max)
$pdf->Cell(5, $textypos, fix_mayus($title));

$pdf->SetFont('Arial', '', 5);
$textypos += 6;
$pdf->setX(2);

// Luego el nombre del Almacén (DISEÑARTE)
$pdf->Cell(5,$textypos, fix_mayus($stock->field1));
//$pdf->SetFont('DejaVu','',5);    //Letra Arial, negrita (Bold), tam. 20
$pdf->SetFont('Arial','',5);    //Letra Arial, negrita (Bold), tam. 20
//$textypos+=6;
//$pdf->setX(2);
$pdf->Cell(5,$textypos,strtoupper($stock->field1));
//$textypos+=6;
//$pdf->setX(2);
$pdf->Cell(5,$textypos,strtoupper($stock->address));
$textypos+=6;
if($stock->phone!=""){
$pdf->setX(2);
$pdf->Cell(5,$textypos,"TEL. ".strtoupper($stock->phone));
}
$textypos+=6;
$pdf->setX(2);
$pdf->Cell(5,$textypos,"FOLIO: ".$sell->id.' - FECHA: '.$sell->created_at);
if($sell->person_id!=null){
$textypos+=6;
$pdf->setX(2);
$pdf->Cell(5,$textypos,'-----------------------CLIENTE--------------------------------');

$client=	PersonData::getById($sell->person_id);
$textypos+=6;
$pdf->setX(2);
$pdf->Cell(5,$textypos,"NIT/RFC: ".strtoupper($client->no));

$textypos+=6;
$pdf->setX(2);
$pdf->Cell(5,$textypos,"NOMBRE: ".strtoupper($client->name.' '.$client->lastname));

}

$textypos+=6;
$pdf->setX(2);
$pdf->Cell(5,$textypos,'-------------------------------------------------------------------');
$textypos+=6;
$pdf->setX(2);
$pdf->Cell(5,$textypos,'CANT.  ARTICULO       PRECIO               TOTAL');

$total =0;
$off = $textypos+6;
foreach($operations as $op){
$product = $op->getProduct();
$pdf->setX(2);
$pdf->Cell(5,$off,"$op->q");
$pdf->setX(6);
$pdf->Cell(35,$off,  strtoupper(substr($product->name, 0,12)) );
$pdf->setX(20);
$pdf->Cell(11,$off,  "$symbol ".number_format($product->price_out,2,".",",") ,0,0,"R");
$pdf->setX(32);
$pdf->Cell(11,$off,  "$symbol ".number_format($op->q*$product->price_out,2,".",",") ,0,0,"R");

//    ".."  ".number_format($op->q*$product->price_out,2,".",","));
$total += $op->q*$product->price_out;
$off+=6;
}
$textypos=$off;
//////////////////////////////////////////////
if(Core::$plus_iva==0){
$pdf->setX(2);
$pdf->Cell(5,$off+6,"SUBTOTAL:  " );
$pdf->setX(38);
$pdf->Cell(5,$off+6,"$symbol ".number_format(($total)/(1 + ($iva_val/100) ),2,".",","),0,0,"R");
$pdf->setX(2);
$pdf->Cell(5,$off+12,"DESCUENTO: " );
$pdf->setX(38);
$pdf->Cell(5,$off+12,"$symbol ".number_format($sell->discount,2,".",","),0,0,"R");

$pdf->setX(2);
$pdf->Cell(5,$off+18,"IMPUESTO: " );
$pdf->setX(38);
$pdf->Cell(5,$off+18,"$symbol ".number_format(( ($total)/(1 + ($iva_val/100) )) *($iva_val/100),2,'.',','),0,0,"R");


$pdf->setX(2);
$pdf->Cell(5,$off+5+18,"TOTAL: " );
$pdf->setX(38);
$pdf->Cell(5,$off+5+18,"$symbol ".number_format($total,2,".",","),0,0,"R");
}
else if(Core::$plus_iva==1){
	$total=$sell->total; 
$iva_calc = ( ($total)/(1 + ($iva_val/100) )) *($iva_val/100);
$pdf->setX(2);
$pdf->Cell(5,$off+6,"SUBTOTAL:  " );
$pdf->setX(38);
$pdf->Cell(5,$off+6,"$symbol ".number_format(($total+$sell->discount-$iva_calc),2,".",","),0,0,"R");
$pdf->setX(2);
$pdf->Cell(5,$off+12,"DESCUENTO: " );
$pdf->setX(38);
$pdf->Cell(5,$off+12,"$symbol ".number_format($sell->discount,2,".",","),0,0,"R");

$pdf->setX(2);
$pdf->Cell(5,$off+18,"IMPUESTO: " );
$pdf->setX(38);
$pdf->Cell(5,$off+18,"$symbol ".number_format($iva_calc,2,'.',','),0,0,"R");


$pdf->setX(2);
$pdf->Cell(5,$off+5+18,"TOTAL: " );
$pdf->setX(38);
$pdf->Cell(5,$off+5+18,"$symbol ".number_format($total,2,".",","),0,0,"R");
}
//////////////////////////////////////////
$pdf->setX(2);
$pdf->Cell(5,$off+5+24,"EFECTIVO: " );
$pdf->setX(38);
$pdf->Cell(5,$off+5+24,"$symbol ".number_format($sell->cash,2,".",","),0,0,"R");
$cambio = $sell->cash-($total );
if($sell->cash>$sell->total){
$pdf->setX(2);
$pdf->Cell(5,$off+5+30,"CAMBIO: " );
$pdf->setX(38);
$pdf->Cell(5,$off+5+30,"$symbol ".number_format($cambio,2,".",","),0,0,"R");
}
$pdf->setX(2);
$pdf->Cell(5,$off+5+36,'-------------------------------------------------------------------');
$pdf->setX(2);
$pdf->Cell(5,$off+5+42,"COD/NIT: ".strtoupper($stock->code));
$pdf->setX(2);
//$pdf->Cell(5,$off+5+48,"SUCURSAL: ".strtoupper($stock->name));
//$pdf->setX(2);
//$pdf->Cell(5,$off+5+54,"FOLIO: ".$sell->id.' - FECHA: '.$sell->created_at);
$pdf->setX(2);
$pdf->Cell(5,$off+5+48,'ATENDIDO POR '.strtoupper($user->name." ".$user->lastname));
//$pdf->setX(2);
//$pdf->Cell(5,$off+5+68,$stock->field2);
//$pdf->setX(2);
$pdf->Cell(5,$off+5+54,'***** GRACIAS POR TU COMPRA ***** ');


$pdf->output();
