<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 03/03/2018
 * Time: 18:45
 */

namespace AppBundle\Entity;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class Results
 * Manage all results from XLS 'Résultats Sheet'
 * @package AppBundle\Entity
 */
class Results
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
     * @var array $data
     * result read from xls sheet
     */
    private $data;

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
    /**
     * Read Results from file
     *
     * @param IOFactory
     */
    public function readResults(IOFactory $xlsReader){
        $xlsReader->setActiveSheetIndexByName(self::sheetName);
        $worksheet = $xlsReader->getActiveSheet();
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $dataInCols = [2,3,4,5,6,9,12,15,16];
        $resultsArray = array();
        for ($row = 1; $row <= $highestRow; $row++){
            $value = $worksheet->getCellByColumnAndRow(2 /* col B */, $row)->getValue();
            if(srtpos($value, self::BAI) !== false){ // STR COMP With Type
                $resultsArray["self::BAI"] = array();
                $value = $worksheet->getCellByColumnAndRow(2 /* col B */, $row += 2)->getValue();
                while(strlen($value)>= 1){
                    $data = array();
                    $value = $worksheet->getCellByColumnAndRow(2 /* col B */, $row ++)->getValue();
                    foreach ($dataInCols as $col ){
                        $data[] = $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                    }
                }
            }
            if(srtpos($value, self::BAE) !== false){ // STR COMP With Types

            }
            if(srtpos($value, self::BC) !== false){ // STR COMP With Types

            }
            if(srtpos($value, self::BEVMC) !== false){ // STR COMP With Types

            }
            if(srtpos($value, self::BEIEL) !== false){ // STR COMP With Types

            }
            if(srtpos($value, self::BEC) !== false){ // STR COMP With Types

            }
            if(srtpos($value, self::AAE) !== false){ // STR COMP With Types

            }
        }
    }
}