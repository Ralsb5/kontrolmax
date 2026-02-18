<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/PaymentData.php";

/** Include PHPExcel */
include "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$objPHPExcel = new Spreadsheet();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Evilnapsis")
							 ->setLastModifiedBy("Evilnapsis")
							 ->setTitle("Inventio Max v9.0")
							 ->setSubject("Inventio Max v9.0")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");


// Add some data
$sheet = $objPHPExcel->setActiveSheetIndex(0);
$products = PersonData::getClients();

$sheet->setCellValue('A1', 'Creditos - Inventio Max')
->setCellValue('A2', 'Id')
->setCellValue('B2', 'Nombre')
->setCellValue('C2', 'Direccion')
->setCellValue('D2', 'Telefono')
->setCellValue('E2', 'Email')
->setCellValue('F2', 'Saldo Pendiente');

$start = 3;
foreach($products as $product){
$sheet->setCellValue('A'.$start, $product->id)
->setCellValue('B'.$start, $product->name." ".$product->lastname)
->setCellValue('C'.$start, $product->address1)
->setCellValue('D'.$start, $product->phone1)
->setCellValue('E'.$start, $product->email1)
->setCellValue('F'.$start, "$". number_format(PaymentData::sumByClientId($product->id)->total,2,".",","));
$start++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
////////////////////////////////////////////////////
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="credit-'.time().'.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
//////////////////////////////////////////////////////
$writer = new Xlsx($objPHPExcel);
$writer->save('php://output');