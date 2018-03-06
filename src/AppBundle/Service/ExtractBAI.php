<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 06/03/2018
 * Time: 00:24
 */

namespace AppBundle\Service;

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
     * a json of all the line of the resultats de l'essai table
     *
     * @var json
     *
     */
    public $data;

    public function extractBAI($xlsReader, $sheetName){

        $xlsReader->setActiveSheetIndexByName($sheetName);
        $worksheet = $xlsReader->getActiveSheet();
        $this->idOfSheet = $sheetName;
//        $title = $worksheet->getCell('D2')->getCalculatedValue();
//        $this->idOfSheet = str_replace("#VALUE!", $sheetName,$title);

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

        $this->weightedStandardizedAcousticIsolation = $worksheet->getCell('L46')->getCalculatedValue();
        $this->objectifRa1999 = $worksheet->getCell('L47')->getCalculatedValue();

        $this->testResult = $worksheet->rangeToArray('B40:L45', '', true, true, true);

        $this->data = $worksheet->rangeToArray('N2:T17', '', true, true, true);

    }

}