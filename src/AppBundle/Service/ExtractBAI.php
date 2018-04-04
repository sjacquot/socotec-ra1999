<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 06/03/2018
 * Time: 00:24
 */

namespace AppBundle\Service;

use AppBundle\Entity\GraphRA1999;
use PhpOffice\PhpSpreadsheet\Chart\Renderer\JpGraph;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Class ExtractBAI Extract data from A(#) worksheet
 * @package AppBundle\Service
 */
class ExtractBAI
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
    public $separatingNatureWall;

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
    public $separatingThicknessWall;

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
    public $localReceptionSurface;

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
     * Version du doc Excel
     *
     * @var string
     */
    public $version;

    /**
     * @var json(type="json", nullable=true)
     */
    public $testTemplateCurve;

    public function extractBAI(Spreadsheet $xlsReader, $sheetName, $pathCharts){

        $xlsReader->setActiveSheetIndexByName($sheetName);
        $worksheet = $xlsReader->getActiveSheet();
        $this->idOfSheet = $sheetName;
        $this->version = $worksheet->getCell("B2")->getFormattedValue();

        $dateUS = $worksheet->getCell("I7")->getFormattedValue();
        $this->MeasureDate = (strlen($dateUS)>0)? strftime('%d %B %Y', strtotime($dateUS)):"";

        $dateUS = $worksheet->getCell("L7")->getFormattedValue();
        $this->MeasureTTX = (strlen($dateUS)>0)? strftime('%d %B %Y', strtotime($dateUS)):"";

        $this->localEmissionName = $worksheet->getCell('I15')->getCalculatedValue();
        $this->localEmissionVolume = $worksheet->getCell('I16')->getCalculatedValue();
        $this->localReceptionName = $worksheet->getCell('I19')->getCalculatedValue();
        $this->localReceptionVolume = $worksheet->getCell('I20')->getCalculatedValue();

        $this->separatingNatureWall = $worksheet->getCell('I23')->getCalculatedValue();
        $this->separatingDubbingNatureWall = $worksheet->getCell('I24')->getCalculatedValue();
        $this->separatingThicknessWall = $worksheet->getCell('I25')->getCalculatedValue();

        $this->doorNumber = $worksheet->getCell('I28')->getCalculatedValue();

        $this->extractionMouth = $worksheet->getCell('I30')->getCalculatedValue();

        $this->facadeDoublingNature = $worksheet->getCell('I33')->getCalculatedValue();
        $this->facadeDoublingThickness = $worksheet->getCell('I34')->getCalculatedValue();

        $this->transmissionType = $worksheet->getCell('I36')->getCalculatedValue();

        $this->localReceptionSurface = $worksheet->getCell('Q20')->getCalculatedValue();
        $this->comment = $worksheet->getCell('Q23')->getCalculatedValue();

        //$this->weightedStandardizedAcousticIsolation = $worksheet->getCell('H46')->getCalculatedValue();
        $this->objectifRa1999 = $worksheet->getCell('H51')->getCalculatedValue();

        $this->testResult = $worksheet->rangeToArray('B40:L45', '', true, true, true);
    // Rounding issue when TR is average of to value...
        $trUtils = $worksheet->rangeToArray('F40:F45', '', true, false, true);
        for($index = 40; $index <= 45; $index++){
            $this->testResult[$index]['F'] = sprintf('%.2f',round($trUtils[$index]['F'],2));
        }
        $this->PassRa1999  = $worksheet->getCell('D52')->getCalculatedValue();

        $this->data = $worksheet->rangeToArray('N2:T17', '', true, true, true);

        $chart = new GraphRA1999($pathCharts);

        $dataTest =  $worksheet->rangeToArray('H40:H45', '', true, false, false);

        $this->testTemplateCurve = $worksheet->rangeToArray('U40:U44', '', true, true, false);

        $data["TEST"] = $this->ArrayToFloat($dataTest);
//        $data["TEMPLATE"] = $this->ArrayToFloat($this->testTemplateCurve);
        $result = $chart->createA($data);
        if($result !==false){
            $this->fileChart = $result["src"];
            $this->CalcWeightedStandardizedAcousticIsolation($result);
            $this->weightedStandardizedAcousticIsolation = $this->CalcWeightedStandardizedAcousticIsolation($result);
            $this->testTemplateCurve = $result["TEMPLATE"];
        }
       return true;


    }
    private function ArrayToFloat($dataXLS){
        foreach ($dataXLS as $item)
        {
            $data[] = floatval($item[0]);
        }
        return $data;
    }
    private function CalcWeightedStandardizedAcousticIsolation($data){
        //(-10*LOG(10^((-21-H40)/10)+10^((-14-H41)/10)+10^((-8-H42)/10)+10^((-5-H43)/10)+10^((-4-H44)/10)))-AG7
        $MeasureData = $data["TEST"];
        $MasterVal = $data["TEMPLATE"][2];
        $sum  = pow(10,(-21-$MeasureData[0])/10);
        $sum += pow(10,(-14-$MeasureData[1])/10);
        $sum += pow(10,( -8-$MeasureData[2])/10);
        $sum += pow(10,( -5-$MeasureData[3])/10);
        $sum += pow(10,( -4-$MeasureData[4])/10);
        $correction = (-10*log10($sum))-$MasterVal;
        return $MasterVal+round($correction);
    }

}