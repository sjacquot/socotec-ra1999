<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 06/03/2018
 * Time: 00:24
 */

namespace AppBundle\Service;

use AppBundle\Entity\GraphRA1999;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Class ExtractBC Extract data from C(#) worksheet
 * @package AppBundle\Service
 */
class ExtractBC extends ExtractService
{
    public $idOfSheet;

    /**
     * LOCAL EMISSION Nom
     *
     * @var string
     *
     */
    public $localEmissionName;

    /**
     * LOCAL EMISSION Volume in (m3)
     *
     * @var float
     *
     */
    public $localEmissionVolume;

    /**
     * LOCAL RECEPTION Nom
     *
     * @var string
     *
     */
    public $localReceptionName;

    /**
     * LOCAL RECEPTION Volume (m3)
     *
     * @var float
     *
     */
    public $localReceptionVolume;

    /**
     * Paroi Separative (Nature Paroi)
     *
     * @var string
     *
     */
    public $separatingNatureFloor;

    /**
     * Paroi separative Nature Doublage
     *
     * @var string
     *

     */
    public $separatingDubbingNatureWall;


    /**
     * Paroi separative Eapaisseur (cm)
     *
     * @var float
     *
     */
    public $separatingThicknessFloor;

    /**
     * Nombre de porte (emission / reception)
     *
     * @var string
     *
     */
    public $doorNumber;

    /**
     * Bouche Extraction
     *
     * @var string
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $extractionMouth;

    /**
     * Doublage Facade Nature
     *
     * @var string
     *
     */
    public $facadeDoublingNature;

    /**
     * Doublage Facade Epaisseru (cm)
     *
     * @var float
     *
     */
    public $facadeDoublingThickness;

    /**
     * Type transmission
     *
     * @var string
     *
     */
    public $transmissionType;

    /**
     * Surface du local de reception
     *
     * @var string
     *
     */
    public $nbShockMachines;

    /**
     * observation Eventuelle
     *
     * @var text
     *
     */
    public $comment;

    /**
     * Isolement Acoustique standardisé pndéré
     *
     * String because you have the  prefix dB
     *
     * @var string
     *
     */
    public $weightedStandardizedAcousticIsolation;

    /**
     * Objectif RA 1999
     *
     * String because you have the  prefix dB
     *
     * @var string
     *
     */
    public $objectifRa1999;

    /**
     * a json of all the line of the resultats de l'essai table
     *
     * @var json
     *
     */
    public $testResult;

    /**
     * Appreciation de la mesure
     *
     * @var text
     *
     */
    public $PassRa1999;

    /**
     * a json of all the line of the resultats de l'essai table
     *
     * @var json
     *
     */
    public $data;

    /**
     * fichier image du graph de la mesure
     *
     * @var string(type="string", nullable=true)
     */
    public $fileChart;
    /* Date de mesure
    *
    * @var date
    */
    public $MeasureDate;
    /**
     * Date d'analyse
     *
     * @var date
     */
    public $MeasureTTX;
    /**
     * @var json(type="json", nullable=true)
     */
    public $testTemplateCurve;
    /**
     * Version du doc Excel
     *
     * @var string
     */
    public $version;

    /**
     * Extract all meaningful values from C(#) Sheets and generate C(#) Curve chart
     * @param Spreadsheet $xlsReader
     * @param $sheetName
     * @param $pathCharts
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function extractBC(Spreadsheet $xlsReader, $sheetName, $pathCharts){
        $xlsReader->setActiveSheetIndexByName($sheetName);
        $worksheet = $xlsReader->getActiveSheet();
        $this->idOfSheet = $sheetName;
        $this->version = $worksheet->getCell("B2")->getFormattedValue();

        $dateUS = $worksheet->getCell("I7")->getFormattedValue();
        $dateUS = $this->checkDate($dateUS);
        $this->MeasureDate = (!is_null($dateUS))? strftime('%d %B %Y', $dateUS->getTimestamp()):"";

        $dateUS = $worksheet->getCell("L7")->getFormattedValue();
        $dateUS = $this->checkDate($dateUS);
        $this->MeasureTTX = (!is_null($dateUS))? strftime('%d %B %Y', $dateUS->getTimestamp()):"";

        $this->localEmissionName = $worksheet->getCell('I15')->getCalculatedValue();
        $this->localEmissionVolume = $worksheet->getCell('I16')->getCalculatedValue();

        $this->localReceptionName = $worksheet->getCell('I19')->getCalculatedValue();
        $this->localReceptionVolume = $worksheet->getCell('I20')->getCalculatedValue();

        $this->separatingNatureFloor = $worksheet->getCell('I23')->getCalculatedValue();
        $this->separatingThicknessFloor = $worksheet->getCell('I24')->getCalculatedValue();

        $this->flooringNature = $worksheet->getCell('I27')->getCalculatedValue();
        $this->flooringAcousticTreatment = $worksheet->getCell('I28')->getCalculatedValue();

        $this->transmissionType = $worksheet->getCell('I31')->getCalculatedValue();
        // NB MACHINE A CHOCs
        $this->nbShockMachines = $worksheet->getCell('I34')->getCalculatedValue();

        $this->comment = $worksheet->getCell('IQ23')->getCalculatedValue();

        $this->objectifRa1999 = $worksheet->getCell('H51')->getCalculatedValue();
        $this->PassRa1999 = $worksheet->getCell('D52')->getCalculatedValue();

        $this->testResult = $worksheet->rangeToArray('B40:H45', '', true, true, true);
        // Rounding issue...
        $trUtils = $worksheet->rangeToArray('F40:F45', '', true, false, true);
        for($index = 40; $index <= 45; $index++){
            $this->testResult[$index]['F'] = sprintf('%.2f',round($trUtils[$index]['F'],2));
        }
        $this->data = $worksheet->rangeToArray('N2:S17', '', true, true, true);

        $chart = new GraphRA1999($pathCharts);

        $dataTest =  $worksheet->rangeToArray('H40:H45', '', true, false, false);
        $data["TEST"] = $this->ArrayToFloat($dataTest);
        $result = $chart->createC($data);
        if($result !==false){
            $this->fileChart = $result["src"];
            $this->weightedStandardizedShockNoise = $this->CalcweightedStandardizedShockNoise($result["TEMPLATE"]);
            $this->testTemplateCurve = $result["TEMPLATE"];
        }

        return true;

    }

    /**
     * Compute Weighted Standardized Value for C (Shock Noise) Curve \n
     * Value = Curve value for 500 hz minus 5 dB.
     * @param $curveData
     * @return int
     */
    private function CalcweightedStandardizedShockNoise($curveData){
            return $curveData[2]-5;
    }

}