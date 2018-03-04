<?php
/**
 * Created by PhpStorm.
 * User: paolocastro
 * Date: 04/03/2018
 * Time: 15:45
 */

namespace AppBundle\Service;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExtractResults
{
    /**
     * sheetName of the spreadsheet workbook where results are to be found
     */
    const sheetName = "Résultats";

    /**
     * $type possible values
     */
    const BAI = "Bruits Aériens Intérieurs";
    const BAE = "Bruits Aériens Extérieurs";
    const BC = "Bruits de Chocs";
    const BEVMC = "Bruit des Equipements de VMC";
    const BEIEL = "Bruit des Equipements Individuels Extérieurs au Logement contrôlé";
    const BEC = "Bruit des Equipements Collectifs (hors VMC)";
    const AAE = "Aire d'Absorption Equivalente";

    /**
     * Read Results from file
     * @param $xlsReader
     * @return array
     */
    public function readResults($xlsReader){
        $xlsReader->setActiveSheetIndexByName(self::sheetName);
        $worksheet = $xlsReader->getActiveSheet();
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $dataInCols = [2,3,4,5,6,9,12,15,16];
        $resultsArray = array();
        for ($row = 1; $row <= $highestRow; $row++){
            $value = $worksheet->getCellByColumnAndRow(2 /* col B */, $row)->getCalculatedValue();
            if(strpos($value, self::BAI) !== false){ // STR COMP With Type
                $value = $worksheet->getCellByColumnAndRow(2 /* col B */, $row += 7)->getCalculatedValue();
                while(strlen($value)>= 1){
                    $data = array();
                    foreach ($dataInCols as $col ){
                        $data[] = $worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                    }
                    if(strlen($data[0])>0){
                        $resultsArray[self::BAI][] = $data;
                    }
                    $value = $worksheet->getCellByColumnAndRow(2 /* col B */, $row++)->getCalculatedValue();
                }
            }
            if(strpos($value, self::BAE) !== false){ // STR COMP With Types
                $value = $worksheet->getCellByColumnAndRow(2 /* col B */, $row += 7)->getCalculatedValue();
                while(strlen($value)>= 1){
                    $data = array();
                    foreach ($dataInCols as $col ){
                        $data[] = $worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                    }
                    if(strlen($data[0])>0){
                        $resultsArray[self::BAE][] = $data;
                    }
                    $value = $worksheet->getCellByColumnAndRow(2 /* col B */, $row++)->getCalculatedValue();
                }
            }
            if(strpos($value, self::BC) !== false){ // STR COMP With Types
                $value = $worksheet->getCellByColumnAndRow(2 /* col B */, $row += 7)->getCalculatedValue();
                while(strlen($value)>= 1){
                    $data = array();
                    foreach ($dataInCols as $col ){
                        $data[] = $worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                    }
                    if(strlen($data[0])>0){
                        $resultsArray[self::BC][] = $data;
                    }
                    $value = $worksheet->getCellByColumnAndRow(2 /* col B */, $row++)->getCalculatedValue();
                }
            }
            if(strpos($value, self::BEVMC) !== false){ // STR COMP With Types
                $value = $worksheet->getCellByColumnAndRow(2 /* col B */, $row += 7)->getCalculatedValue();
                while(strlen($value)>= 1){
                    $data = array();
                    foreach ($dataInCols as $col ){
                        $Celldata = $worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                        if($Celldata !== null){
                            $data[] = $Celldata;
                        }
                    }
                    $data[1] = $data[1].'<br>'.$worksheet->getCellByColumnAndRow(4 /* col D */, $row++)->getCalculatedValue();
                    if(strlen($data[0])>0){
                        $resultsArray[self::BEVMC][] = $data;
                    }
                    $value = $worksheet->getCellByColumnAndRow(2 /* col B */, $row++)->getCalculatedValue();
                    echo "Line ".$row."Value";
                }
            }
            if(strpos($value, self::BEIEL) !== false){ // STR COMP With Types

            }
            if(strpos($value, self::BEC) !== false){ // STR COMP With Types

            }
            if(strpos($value, self::AAE) !== false){ // STR COMP With Types
                $value = $worksheet->getCellByColumnAndRow(2 /* col B */, $row += 4)->getCalculatedValue();
                while(strlen($value)>= 1){
                    $data = array();
                    foreach ($dataInCols as $col ){
                        $data[] = $worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                    }
                    if(strlen($data[0])>0){
                        $resultsArray[self::AAE][] = $data;
                    }
                    $value = $worksheet->getCellByColumnAndRow(2 /* col B */, $row++)->getCalculatedValue();
                }

            }
        }
        return $resultsArray;
    }
}