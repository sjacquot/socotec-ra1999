<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 07/03/2018
 * Time: 13:41
 */

namespace AppBundle\Service;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class ExtractEquipments extends ExtractService
{
    /**
     * sheetName of the spreadsheet workbook where results are to be found
     */
    const sheetName = "EQUIPEMENTS";
    const Mark1 = "type 1";
    const Mark2 = "type 2";
    const MarkV = "Version";

    private $worksheet;
    private $highestRow;
    public $type1;
    public $type1AmbiantNoise;
    public $type2;
    public $type2AmbiantNoise;
    public $type1Comments;
    public $type2Comments;
    /**
     * Version du doc Excel
     *
     * @var string
     */
    public $version;

    public function readEquipment(Spreadsheet $xlsReader)
    {

        $xlsReader->setActiveSheetIndexByName(self::sheetName);
        $this->worksheet = $xlsReader->getActiveSheet();
        $this->highestRow = $this->worksheet->getHighestRow();
        $index = 1;

        for ($index; $index <= $this->highestRow; $index++) {
            // Search in column D of worksheet to find "Type 1"
            $value = $this->worksheet->getCellByColumnAndRow(4, $index)->getCalculatedValue();
            if (strpos($value, self::Mark1) !== false) {
                $this->version = $this->worksheet->getCell("B".$index)->getFormattedValue();
                $index += 3;
                $this->type1 = $DataArray[self::Mark1] = $this->readTypeLine($index);
                $bottom = $index + count($DataArray[self::Mark1]);
                $this->type1Comments = $this->worksheet->rangeToArray("Z".$index.":Z".$bottom, true, true, false);
                $index = $bottom;
                $this->type1AmbiantNoise = $this->getAmbiantNote($index);
            }
            if (strpos($value, self::Mark2) !== false) {
                if(!isset($this->version)||is_null($this->version)){
                    $this->version = $this->worksheet->getCell("B".$index)->getFormattedValue();
                }
                $index += 3;
                $this->type2 = $DataArray[self::Mark2] = $this->readTypeLine($index);
                $bottom = $index + count($DataArray[self::Mark1]);
                $this->type2Comments = $this->worksheet->rangeToArray("Z".$index.":Z".$bottom, true, true, false);
                $index = $bottom;
                $this->type2AmbiantNoise = $this->getAmbiantNote($index);
            }

        }
        return true;
    }
    private function readTypeLine($index){
        $value = $this->worksheet->getCellByColumnAndRow(2, $index)->getCalculatedValue();
        $DataArray = array();
        while (!is_null($value)) {
            $DataArray[] = $this->worksheet->rangeToArray("B" . $index . ":W" . $index, "", true, true, false);
            $value = $this->worksheet->getCellByColumnAndRow(2, ++$index)->getCalculatedValue();
        }
        return $DataArray;
    }
    private function getAmbiantNote($index){
        $value ="";
        do{
            $value = $this->worksheet->getCellByColumnAndRow(2, ++$index)->getCalculatedValue();
        } while(is_null($value) && $index <= $this->highestRow);
        return $value;
    }
}