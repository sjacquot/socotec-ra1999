<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Shock
 *
 * @ORM\Table(name="Shock")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShockRepository")
 */
class Shock
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Many Shock have One Operation.
     * @ORM\ManyToOne(targetEntity="Operation", inversedBy="shock")
     * @ORM\JoinColumn(name="operation_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $operation;

    /**
     * Fiche de traitement de mesure n° C(1)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $idOfSheet;

    /**
     * Local emission Nom
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localEmissionName;

    /**
     * Local emission volume (m3)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localEmissionVolume;

    /**
     * Local reception nom
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localReceptionName;

    /**
     * Local reception (m3)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localReceptionVolume;

    /**
     * Plancher support Nature
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $separatingNatureFloor;

    /**
     * Plancher Epaisseur (cm)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $separatingThicknessFloor;

    /**
     * REVETEMENT DE SOL Nature revêtement
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $flooringNature;

    /**
     * REVETEMENT DE SOL Traitement acoustique
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $flooringAcousticTreatment;

    /**
     * Type Transmition
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $transmissionType;

    /**
     * Surface du local de reception
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $nbShockMachines;

    /**
     * obeservation eventuelle
     *
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * Niveau de pression pondéré du bruit de choc standardisé :
     * String because you have the  prefix dB
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $weightedStandardizedShockNoise;

    /**
     * Objectif RA 1999 :
     * String because you have the  prefix dB
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $objectifRa1999;

    /**
     * Appreciation de la mesure
     *
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $PassRa1999;

    /**
     * a json of all the lines of the acoustic test table
     *
     * @var json
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $testResult;

    /**
     * a json of all the values of temptlate line of chart
     *
     * @var json
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $testTemplateCurve;

    /**
     * a json of all the line of the resultats de l'essai table
     *
     * @var json
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $data;

    /**
     * fichier image du graph de la mesure
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    public $fileChart;

    /**
     * Date de mesure
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    public $MeasureDate;
    /**
     * Date d'analyse
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    public $MeasureTTX;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param mixed $operation
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
    }

    /**
     * @return string
     */
    public function getIdOfSheet()
    {
        return $this->idOfSheet;
    }

    /**
     * @param string $idOfSheet
     */
    public function setIdOfSheet($idOfSheet)
    {
        $this->idOfSheet = $idOfSheet;
    }

    /**
     * @return string
     */
    public function getLocalEmissionName()
    {
        return $this->localEmissionName;
    }

    /**
     * @param string $localEmissionName
     */
    public function setLocalEmissionName($localEmissionName)
    {
        $this->localEmissionName = $localEmissionName;
    }

    /**
     * @return string
     */
    public function getLocalEmissionVolume()
    {
        return $this->localEmissionVolume;
    }

    /**
     * @param string $localEmissionVolume
     */
    public function setLocalEmissionVolume($localEmissionVolume)
    {
        $this->localEmissionVolume = $localEmissionVolume;
    }

    /**
     * @return string
     */
    public function getLocalReceptionName()
    {
        return $this->localReceptionName;
    }

    /**
     * @param string $localReceptionName
     */
    public function setLocalReceptionName($localReceptionName)
    {
        $this->localReceptionName = $localReceptionName;
    }

    /**
     * @return string
     */
    public function getLocalReceptionVolume()
    {
        return $this->localReceptionVolume;
    }

    /**
     * @param string $localReceptionVolume
     */
    public function setLocalReceptionVolume($localReceptionVolume)
    {
        $this->localReceptionVolume = $localReceptionVolume;
    }

    /**
     * @return string
     */
    public function getSeparatingNatureFloor()
    {
        return $this->separatingNatureFloor;
    }

    /**
     * @param string $separatingNatureFloor
     */
    public function setSeparatingNatureFloor($separatingNatureFloor)
    {
        $this->separatingNatureFloor = $separatingNatureFloor;
    }

    /**
     * @return string
     */
    public function getSeparatingThicknessFloor()
    {
        return $this->separatingThicknessFloor;
    }

    /**
     * @param string $separatingThicknessFloor
     */
    public function setSeparatingThicknessFloor($separatingThicknessFloor)
    {
        $this->separatingThicknessFloor = $separatingThicknessFloor;
    }

    /**
     * @return string
     */
    public function getFlooringNature()
    {
        return $this->flooringNature;
    }

    /**
     * @param string $flooringNature
     */
    public function setFlooringNature($flooringNature)
    {
        $this->flooringNature = $flooringNature;
    }

    /**
     * @return string
     */
    public function getFlooringAcousticTreatment()
    {
        return $this->flooringAcousticTreatment;
    }

    /**
     * @param string $flooringAcousticTreatment
     */
    public function setFlooringAcousticTreatment($flooringAcousticTreatment)
    {
        $this->flooringAcousticTreatment = $flooringAcousticTreatment;
    }

    /**
     * @return string
     */
    public function getTransmissionType()
    {
        return $this->transmissionType;
    }

    /**
     * @param string $transmissionType
     */
    public function setTransmissionType($transmissionType)
    {
        $this->transmissionType = $transmissionType;
    }

    /**
     * @return string
     */
    public function getNbShockMachines()
    {
        return $this->nbShockMachines;
    }

    /**
     * @param string $nbShockMachines
     */
    public function setNbShockMachines($nbShockMachines)
    {
        $this->nbShockMachines = $nbShockMachines;
    }

    /**
     * @return text
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param text $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return string
     */
    public function getWeightedStandardizedShockNoise()
    {
        return $this->weightedStandardizedShockNoise;
    }

    /**
     * @param string $weightedStandardizedShockNoise
     */
    public function setWeightedStandardizedShockNoise($weightedStandardizedShockNoise)
    {
        $this->weightedStandardizedShockNoise = $weightedStandardizedShockNoise;
    }

    /**
     * @return string
     */
    public function getObjectifRa1999()
    {
        return $this->objectifRa1999;
    }

    /**
     * @param string $objectifRa1999
     */
    public function setObjectifRa1999($objectifRa1999)
    {
        $this->objectifRa1999 = $objectifRa1999;
    }

    /**
     * @return json
     */
    public function getTestResult()
    {
        return $this->testResult;
    }

    /**
     * @param json $testResult
     */
    public function setTestResult($testResult)
    {
        $this->testResult = $testResult;
    }

    /**
     * @return json
     */
    public function getData()
    {
        return json_decode($this->data);
    }

    /**
     * @param json $data
     */
    public function setData($data)
    {
        $this->data = json_encode($data);
    }

    /**
     * @return text
     */
    public function getPassRa1999()
    {
        return $this->PassRa1999;
    }

    /**
     * @param text $PassRa1999
     */
    public function setPassRa1999($PassRa1999)
    {
        $this->PassRa1999 = $PassRa1999;
    }

    /**
     * @return string
     */
    public function getFileChart()
    {
        return $this->fileChart;
    }

    /**
     * @param string $fileChart
     */
    public function setFileChart($fileChart)
    {
        $this->fileChart = $fileChart;
    }

    /**
     * @return date
     */
    public function getMeasureDate()
    {
        return $this->MeasureDate;
    }

    /**
     * @param date $MeasureDate
     */
    public function setMeasureDate($MeasureDate)
    {
        $this->MeasureDate = $MeasureDate;
    }

    /**
     * @return date
     */
    public function getMeasureTTX()
    {
        return $this->MeasureTTX;
    }

    /**
     * @param date $MeasureTTX
     */
    public function setMeasureTTX($MeasureTTX)
    {
        $this->MeasureTTX = $MeasureTTX;
    }

    /**
     * @return json
     */
    public function getTestTemplateCurve()
    {
        return $this->testTemplateCurve;
    }

    /**
     * @param json $testTemplateCurve
     */
    public function setTestTemplateCurve($testTemplateCurve)
    {
        $this->testTemplateCurve = $testTemplateCurve;
    }

}
