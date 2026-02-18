<?php

/** Error reporting */
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/CategoryData.php";

include "../vendor/autoload.php";

/** Include PHPExcel */
//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
//require_once '../core/controller/PHPExcel/Classes/PHPExcel.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//require __DIR__ . '/../Header.php';

//$spreadsheet = new Spreadsheet();
// Create new PHPExcel object
$objPHPExcel = new Spreadsheet();
$products = PersonData::getProviders();

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

$sheet->setCellValue('A1', 'Reporte de Proveedores - Inventio Max')
->setCellValue('A2', 'Id')
->setCellValue('B2', 'RFC/RUT')
->setCellValue('C2', 'Nombre completo')
->setCellValue('D2', 'Direccion')
->setCellValue('E2', 'Telefono')
->setCellValue('F2', 'Email');

$start = 3;
foreach($products as $product){
$sheet->setCellValue('A'.$start, $product->id)
->setCellValue('B'.$start, $product->no)
->setCellValue('C'.$start, $product->name." ".$product->lastname)
->setCellValue('D'.$start, $product->address1)
->setCellValue('E'.$start, $product->phone1)
->setCellValue('F'.$start, $product->email1);

$start++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


//$sheet->setCellValue('A5', 'Hello World !');
////////////////////////////////////////////////////
  // Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="providers-'.time().'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
//////////////////////////////////////////////////////
$writer = new Xlsx($objPHPExcel);
$writer->save('php://output');

