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

class ExtractAAE
{
    const sheetName = "AAE";

    private $worksheet;
    private $highestRow;
    public $data;
    public $comments;
    public function readAAE(Spreadsheet $xlsReader)
    {
        $xlsReader->setActiveSheetIndexByName(self::sheetName);
        $this->worksheet = $xlsReader->getActiveSheet();
        $this->highestRow = $this->worksheet->getHighestRow();

        $index = 6;
        for($index;$index<=$this->highestRow;$index+=3){
            // Col #3 = Col C Local name mandatory for AAE
        $value = $this->worksheet->getCellByColumnAndRow(3,$index)->getCalculatedValue();
            if(strlen($value)>0){
                $this->data[] = $this->worksheet->rangeToArray("B".$index.":Q".($index+3),'',true,true,false);
            }
        }
        $this->comments = $this->worksheet->rangeToArray("T6:T".$index,'',true,true,false);
        return true;
    }
}