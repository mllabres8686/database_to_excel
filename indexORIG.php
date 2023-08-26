<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/PHPExcel-1.8/Classes/PHPExcel.php';



// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
    ->setLastModifiedBy("Maarten Balliauw")
    ->setTitle("VISTAS DE LA MAQUINA VIRTUAL")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Test result file");


//VIRTUAL
$host="172.31.132.235";
$user ="root";
$pass="adminuser";
$ddbb="iTOP";
//PRO
$host="172.25.25.24";
$user ="Ihgitop1";
$pass="iHg1t0pi";
$ddbb="hgitop";
    $con = mysqli_connect($host, $user, $pass, $ddbb);
// Check connection
    if(!$con){
        echo "Error: No se pudo conectar a MySQL." . PHP_EOL."<br>";
        echo "errno de depuracion: " . mysqli_connect_errno() . PHP_EOL."<br>";
        echo "error de depuracion: " . mysqli_connect_error() . PHP_EOL."<br>";

    }
    if (mysqli_connect_errno())
    {
        $con = "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    /*BUSCAMOS TODAS LAS VISTAS LE LA BASE DE DATOS*/
    $sql1 = "SHOW FULL TABLES IN ".$ddbb." WHERE TABLE_TYPE LIKE 'VIEW'";
    $res1 = $con->query($sql1);
    $cont=0;
    while($row = $res1->fetch_assoc()){
        $tupla = 1;
        //buscamos todos los campos de cada vista
        $vista = $row['Tables_in_'.$ddbb];
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

        $sql2 = 'SHOW COLUMNS FROM '.$vista;
        $res2 = $con->query($sql2);
        //PARA CADA TUPLA DE LA VIEW
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
exit;
