<?php
/**
 * Created by PhpStorm.
 * User: miguel.llabres
 * Date: 09/03/2017
 * Time: 17:19
 */
session_start();
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');
//DEBUG
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . './../PHPExcel-1.8/Classes/PHPExcel.php';
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()
    ->setCreator($_SESSION['author'])
    ->setLastModifiedBy($_SESSION['author'])
    ->setTitle($_SESSION['title'])
    ->setDescription($_SESSION['description'])
    ->setCategory($_SESSION['category']);



$con = mysqli_connect($_SESSION['host'], $_SESSION['user'], $_SESSION['pass'], $_SESSION['database'], $_SESSION['port']);

$tablas = json_decode(stripslashes($_SESSION['tables_array']));
$cont = 0;
foreach($tablas as $vista){
    $sql2 = 'SHOW COLUMNS FROM '.$vista;
    $res2 = $con->query($sql2);
    //PARA CADA TUPLA DE LA VIEW
    $tupla = 1;

    //buscamos todos los campos de cada vista
    $objPHPExcel->setActiveSheetIndex($cont);
    $cont++;

    $objPHPExcel->getActiveSheet()->setTitle(substr($vista,0,30));
    // titulos de columnas
    $objPHPExcel->getActiveSheet()
        ->setCellValue('B1', "Field")
        ->setCellValue('C1', "Type")
        ->setCellValue('D1', "Null")
        ->setCellValue('E1', "Key")
        ->setCellValue('F1', "Default")
        ->setCellValue('G1', "Extra");
    //color celdas titulo
    $objPHPExcel->getActiveSheet()->getStyle('B1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('808080');






    while($row = $res2->fetch_assoc()) {
        $tupla++;
        $objPHPExcel->getActiveSheet()
            ->setCellValue('B' . $tupla, $row['Field'])
            ->setCellValue('C' . $tupla, $row['Type'])
            ->setCellValue('D' . $tupla, $row['Null'])
            ->setCellValue('E' . $tupla, $row['Key'])
            ->setCellValue('F' . $tupla, $row['Default'])
            ->setCellValue('G' . $tupla, $row['Extra']);
        //marcamos con color los campos importantes
        if(substr($row['Field'], -3) == "_id"){
            //link a otra clase, añadir color amarillo
            $objPHPExcel->getActiveSheet()->getStyle('B'.$tupla.':G'.$tupla)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');
        }
        if(substr($row['Field'], -13) == "_friendlyname"){
            //link a otra clase, añadir color amarillo
            $objPHPExcel->getActiveSheet()->getStyle('B'.$tupla.':G'.$tupla)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');
        }
        if($row['Field'] == "id"){
            //campo clave, añadir color verde
            $objPHPExcel->getActiveSheet()->getStyle('B'.$tupla.':G'.$tupla)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00CC00');
        }
    }

    //ancho automatico de las columnas
    foreach (range('B', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
        $objPHPExcel->getActiveSheet()
            ->getColumnDimension($col)
            ->setAutoSize(true);
    }

    $objPHPExcel->getActiveSheet()->getStyle("B1:G".$tupla)->applyFromArray(
        array(
            'borders' => array(
                'outline' => array(
                    'style' => "medium",
                    'color' => array('rgb' => '000000')
                )
            )
        )
    );

    $objPHPExcel->addSheet(new PHPExcel_Worksheet());
}





// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$objPHPExcel->getProperties()->getTitle().'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
