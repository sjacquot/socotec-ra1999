<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 07/03/2018
 * Time: 16:54
 */

namespace AppBundle\Service;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExtractAAE extends ExtractService
{
    const sheetName = "AAE";

    private $worksheet;
    private $highestRow;
    public $data;
    public $comments;
    /**
     * Version du doc Excel
     *
     * @var string
     */
    public $version;

    /**
     * Read AAE Data \n
     * and update sheet to transmit data to Results Sheet
     * @param Spreadsheet $xlsReader
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function readAAE(Spreadsheet $xlsReader)
    {
        $xlsReader->setActiveSheetIndexByName(self::sheetName);
        $this->worksheet = $xlsReader->getActiveSheet();
        $this->highestRow = $this->worksheet->getHighestRow();
        $this->version = $this->worksheet->getCell("B2")->getFormattedValue();

        $index = 6;
        for($index;$index<=$this->highestRow;$index+=3){
            // Col #3 = Col C Local name mandatory for AAE
            $value = $this->worksheet->getCellByColumnAndRow(3,$index)->getCalculatedValue();
            if(strlen($value)>0){
                //$data = $this->worksheet->rangeToArray("B".$index.":Q".($index+2),'',true,true,false);
                $data = $this->worksheet->rangeToArray("B".$index.":Q".($index+2),'',true,false,false);
                $data = $this->computeAAE($data);
                $this->worksheet->setCellValue('K'.$index,$data[0][9]);
                $this->worksheet->setCellValue('Q'.$index,$data[0][15]);
                $this->data[] = $data;
            }
        }
        $this->comments = $this->worksheet->rangeToArray("T6:T".$index,'',true,true,false);
        return true;
    }

    /**
     * Recompute AAE value from raw data to avoid rounding issue with PHPSpreadsheet \n
     * Update data for score evaluation
     * @param $data
     * @return mixed
     */
    private function computeAAE($data){
        $coeff = 0;
        for ($i = 0;$i < count($data);$i++){
            $coeff += floatval($data[$i][5])*floatval($data[$i][7]);
        }
        $coeff = ($coeff / floatval($data[0][8]))*100;

        $data[0][9] =  round($coeff,0) ;
        if ($coeff < (floatval($data[0][14])-0.5)){
            $res = 'NON COHERENT';
        } else{
            $res =  'COHERENT';
        }
        $data[0][15] = $res;
        return $data;

    }
}