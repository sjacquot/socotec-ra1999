<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 06/03/2018
 * Time: 10:26
 */

namespace AppBundle\Service;

use AppBundle\Entity\GraphRA1999;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


/**
 * Class ExtractBAE Extract data from F(#) worksheet
 * @package AppBundle\Service
 */

class ExtractBAE extends ExtractService
{
    /**
     * Fiche de traitement de mesure n° F(1)
     *
     * @var string(type="string", nullable=true)
     */
    public $idOfSheet;

    /**
     * Emission Name
     *
     * @var string(type="string", nullable=true)
     */
    public $localEmissionName;

    /**
     * Emission Name
     *
     * @var float(type="float", nullable=true)
     */
    public $localEmissionVolume;

    /**
     * Emission Type
     *
     * @var string(type="string", nullable=true)
     */
    public $localEmissionType;

    /**
     * LOCAL RECEPTION Nom
     *
     * @var string(type="string", nullable=true)
     */
    public $localReceptionName;

    /**
     * Local reception volume (m3)
     *
     * @var float(type="float", nullable=true)
     */
    public $localReceptionVolume;

    /**
     * Paroi separative Nature Paroi
     *
     * @var string(type="string", nullable=true)
     */
    public $separatingNatureWall;

    /**
     * Paroi separative Epaisseur (cm)
     *
     * @var float(type="float", nullable=true)
     */
    public $separatingThicknessWall;

    /**
     * paroi separative Nature doublage
     *
     * @var string(type="string", nullable=true)
     */
    public $separatingDubbingNatureWall;

    /**
     * paroi separative (Doublage) Epaisseur(cm)
     *
     * @var string(type="string", nullable=true)
     */
    public $separatingDubbingThicknessWall;

    /**
     * Menuserie Matériaux
     *
     * @var string(type="string", nullable=true)
     */
    public $carpentryMaterial;

    /**
     * Menuserie Ouvrant
     *
     * @var string(type="string", nullable=true)
     */
    public $carpentryOpening;

    /**
     * Menuserie (Ouvrant) Type
     *
     * @var string(type="string", nullable=true)
     */
    public $carpentryOpeningType;

    /**
     * Menuserie (Ouvrant) Number
     *
     * @var string(type="string", nullable=true)
     */
    public $carpentryOpeningNumber;

    /**
     * Menuserie Coffre volet Roulant
     *
     * @var string(type="boolean", nullable=true)
     */
    public $rollingShutterBox;

    /**
     * ENTRÉE D'AIR VMC Nombre
     *
     * @var float(type="float", nullable=true)
     */
    public $vmcAirIntakeNumber;

    /**
     * ENTRÉE D'AIR VMC Position
     *
     * @var string(type="string", nullable=true)
     */
    public $vmcAirIntakePosition;

    /**
     * ENTRÉE D'AIR VMC Type
     *
     * @var string(type="string", nullable=true)
     */
    public $vmcAirIntakeType;

    /**
     * VENTOUSE CHAUDIERE
     *
     * @var string(type="string", nullable=true)
     */
    public $boilerSuctionCup;

    /**
     * OBSERVATION(S) EVENTUELLE(S) :
     *
     * @var text(type="text", nullable=true)
     */
    public $comment;

    /**
     * Isolement acoustique standardisé pondéré :
     *
     * String because you have the  prefix dB
     *
     * @var string(type="string", nullable=true)
     */
    public $weightedStandardizedAcousticIsolation;

    /**
     * Objectif RA 1999 :
     * String because you have the  prefix dB
     *
     * @var string(type="string", nullable=true)
     */
    public $objectifRa1999;

    /**
     * @var json(type="json", nullable=true)
     */
    public $testResult;

    /**
     * @var json(type="json", nullable=true)
     */
    public $testTemplateCurve;

    /**
     * a json of all the line of the resultats de l'essai table
     *
     * @var json(type="json", nullable=true)
     */
    public $data;

    /**
     * Appreciation de la mesure
     *
     * @var string(type="string", nullable=true)
     */
    public $PassRa1999;

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
     * Extract all meaningful values from F(#) Sheets (Foreign/Facade) and generate F(#) Curve chart
     * @param Spreadsheet $xlsReader
     * @param $sheetName
     * @param $pathCharts
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function extractBAE(Spreadsheet $xlsReader, $sheetName,$pathCharts){

        $xlsReader->setActiveSheetIndexByName($sheetName);
        $worksheet = $xlsReader->getActiveSheet();
        $this->idOfSheet = $sheetName;
        $this->version = $worksheet->getCell("B2")->getFormattedValue();

        $dateUS = $worksheet->getCell("I7")->getFormattedValue();
        $dateUS = $this->checkDate($dateUS);
        //$strDate = $dateUS->format('m/d/Y');
        $this->MeasureDate = (!is_null($dateUS))? strftime('%d %B %Y', $dateUS->getTimestamp()):"";
        //        $this->MeasureDate = (strlen($dateUS)>0)? strftime('%d %B %Y', strtotime($dateUS)):"";

        $dateUS = $worksheet->getCell("L7")->getFormattedValue();
        $dateUS = $this->checkDate($dateUS);
        $this->MeasureTTX = (!is_null($dateUS))? strftime('%d %B %Y', $dateUS->getTimestamp()):"";
        //$this->MeasureTTX = (strlen($dateUS)>0)? strftime('%d %B %Y', strtotime($dateUS)):"";


        $this->localEmissionName = $worksheet->getCell('I14')->getCalculatedValue();
        $this->localEmissionType = $worksheet->getCell('K14')->getCalculatedValue();
        $this->localReceptionName = $worksheet->getCell('I16')->getCalculatedValue();
        $this->localReceptionVolume = $worksheet->getCell('I17')->getCalculatedValue();

        $this->separatingNatureWall = $worksheet->getCell('I20')->getCalculatedValue();
        $this->separatingThicknessWall = $worksheet->getCell('I21')->getCalculatedValue();
        $this->separatingDubbingNatureWall = $worksheet->getCell('I22')->getCalculatedValue();
        $this->separatingDubbingThicknessWall = $worksheet->getCell('I23')->getCalculatedValue();

        $this->carpentryMaterial = $worksheet->getCell('I26')->getCalculatedValue();
        $this->carpentryOpening = $worksheet->getCell('I27')->getCalculatedValue();
        $this->carpentryOpeningType = $worksheet->getCell('I28')->getCalculatedValue();
//        $this->carpentryOpeningNumber;
        $this->rollingShutterBox = $worksheet->getCell('I29')->getCalculatedValue();

        $this->vmcAirIntakeNumber = $worksheet->getCell('I32')->getCalculatedValue();
        $this->vmcAirIntakePosition = $worksheet->getCell('I33')->getCalculatedValue();
        $this->vmcAirIntakeType = $worksheet->getCell('I34')->getCalculatedValue();

        $this->boilerSuctionCup = $worksheet->getCell('I36')->getCalculatedValue();

        $this->comment = $worksheet->getCell('Q23')->getCalculatedValue();

        $this->weightedStandardizedAcousticIsolation = $worksheet->getCell('H46')->getCalculatedValue();

        $this->objectifRa1999 = $worksheet->getCell('H53')->getCalculatedValue();
        $this->PassRa1999  = $worksheet->getCell('D54')->getCalculatedValue();

        $this->testResult = $worksheet->rangeToArray('B40:H45', '', true, true, true);
       // Rounding Issue
        $trUtils = $worksheet->rangeToArray('F40:F45', '', true, false, true);
        for($index = 40; $index <= 45; $index++){
            $this->testResult[$index]['F'] = sprintf('%.2f',round($trUtils[$index]['F'],2));
        }

        $this->data = $worksheet->rangeToArray('N2:T17', '', true, true, true);

        $chart = new GraphRA1999($pathCharts);

        $dataTest =  $worksheet->rangeToArray('H40:H45', '', true, false, false);
        $this->testTemplateCurve = $worksheet->rangeToArray('U40:U44', '', true, true, false);

        $data["TEST"] = $this->ArrayToFloat($dataTest);

        $result = $chart->createF($data);
        if($result !==false){
            $this->fileChart = $result["src"];
            $this->weightedStandardizedAcousticIsolation = $this->CalcWeightedStandardizedAcousticIsolation($result);
            $this->testTemplateCurve = $result["TEMPLATE"];
        }

        return true;

    }

    /**
     * Compute Weighted Standardized Isolation Value for F (Foreign/Facade Noise) Curve \n
     * Value = Template Curve value for 500 hz minus As MasterVal + Correction Coefficient in dB. \n
     * Coef = (-10*LOG(10^((-14-CurveValue[125Hz])/10)+10^((-14-CurveValue[250Hz])/10)+10^((-7-CurveValue[500Hz])/10)+10^((-4-CurveValue[1kHz])/10)+10^((-6-CurveValue[2kHz])/10)))-MasterVal
     * @param $data
     * @return float
     */
    private function CalcWeightedStandardizedAcousticIsolation($data){
        $MeasureData = $data["TEST"];
        $MasterVal = $data["TEMPLATE"][2];
        $sum  = pow(10,(-14-$MeasureData[0])/10);
        $sum += pow(10,(-10-$MeasureData[1])/10);
        $sum += pow(10,( -7-$MeasureData[2])/10);
        $sum += pow(10,( -4-$MeasureData[3])/10);
        $sum += pow(10,( -6-$MeasureData[4])/10);
        $correction = (-10*log10($sum))-$MasterVal;
        return round($MasterVal+$correction);
    }

}