<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aerien
 *
 * @ORM\Table(name="aerien")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AerienRepository")
 */
class Aerien
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
     * Many Aerien have One Operation.
     * @ORM\ManyToOne(targetEntity="Operation", inversedBy="aerien")
     * @ORM\JoinColumn(name="operation_id", referencedColumnName="id")
     */
    private $operation;

    /**
     * Fiche de traitement de mesure n° A(1)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $idOfSheet;

    /**
     * LOCAL EMISSION Nom
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localEmissionName;

    /**
     * LOCAL EMISSION Volume in (m3)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localEmissionVolume;

    /**
     * LOCAL RECEPTION Nom
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localReceptionName;

    /**
     * LOCAL RECEPTION Volume (m3)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localReceptionVolume;

    /**
     * Paroi Separative (Nature Paroi)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $separatingNatureWall;

    /**
     * Paroi separative Nature Doublage
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $separatingDubbingNatureWall;

    /**
     * Paroi separative Eapaisseur (cm)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $separatingThicknessWall;

    /**
     * Nombre de porte (emission / reception)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $doorNumber;

    /**
     * Bouche Extraction
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $extractionMouth;

    /**
     * Doublage Facade Nature
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $facadeDoublingNature;

    /**
     * Doublage Facade Epaisseru (cm)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $facadeDoublingThickness;

    /**
     * Type transmission
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
    private $localReceptionSurface;

    /**
     * observation Eventuelle
     *
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * Isolement Acoustique standardisé pndéré
     *
     * String because you have the  prefix dB
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $weightedStandardizedAcousticIsolation;

    /**
     * Objectif RA 1999
     *
     * String because you have the  prefix dB
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $objectifRa1999;

    /**
     * a json of all the line of the resultats de l'essai table
     *
     * @var json
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $testResult;

    /**
     * Appreciation de la mesure
     *
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $PassRa1999;

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
    public function getSeparatingNatureWall()
    {
        return $this->separatingNatureWall;
    }

    /**
     * @param string $separatingNatureWall
     */
    public function setSeparatingNatureWall($separatingNatureWall)
    {
        $this->separatingNatureWall = $separatingNatureWall;
    }

    /**
     * @return string
     */
    public function getSeparatingDubbingNatureWall()
    {
        return $this->separatingDubbingNatureWall;
    }

    /**
     * @param string $separatingDubbingNatureWall
     */
    public function setSeparatingDubbingNatureWall($separatingDubbingNatureWall)
    {
        $this->separatingDubbingNatureWall = $separatingDubbingNatureWall;
    }

    /**
     * @return string
     */
    public function getSeparatingThicknessWall()
    {
        return $this->separatingThicknessWall;
    }

    /**
     * @param string $separatingThicknessWall
     */
    public function setSeparatingThicknessWall($separatingThicknessWall)
    {
        $this->separatingThicknessWall = $separatingThicknessWall;
    }

    /**
     * @return string
     */
    public function getDoorNumber()
    {
        return $this->doorNumber;
    }

    /**
     * @param string $doorNumber
     */
    public function setDoorNumber($doorNumber)
    {
        $this->doorNumber = $doorNumber;
    }

    /**
     * @return string
     */
    public function getExtractionMouth()
    {
        return $this->extractionMouth;
    }

    /**
     * @param string $extractionMouth
     */
    public function setExtractionMouth($extractionMouth)
    {
        $this->extractionMouth = $extractionMouth;
    }

    /**
     * @return string
     */
    public function getFacadeDoublingNature()
    {
        return $this->facadeDoublingNature;
    }

    /**
     * @param string $facadeDoublingNature
     */
    public function setFacadeDoublingNature($facadeDoublingNature)
    {
        $this->facadeDoublingNature = $facadeDoublingNature;
    }

    /**
     * @return string
     */
    public function getFacadeDoublingThickness()
    {
        return $this->facadeDoublingThickness;
    }

    /**
     * @param string $facadeDoublingThickness
     */
    public function setFacadeDoublingThickness($facadeDoublingThickness)
    {
        $this->facadeDoublingThickness = $facadeDoublingThickness;
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
    public function getLocalReceptionSurface()
    {
        return $this->localReceptionSurface;
    }

    /**
     * @param string $localReceptionSurface
     */
    public function setLocalReceptionSurface($localReceptionSurface)
    {
        $this->localReceptionSurface = $localReceptionSurface;
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
    public function getWeightedStandardizedAcousticIsolation()
    {
        return $this->weightedStandardizedAcousticIsolation;
    }

    /**
     * @param string $weightedStandardizedAcousticIsolation
     */
    public function setWeightedStandardizedAcousticIsolation($weightedStandardizedAcousticIsolation)
    {
        $this->weightedStandardizedAcousticIsolation = $weightedStandardizedAcousticIsolation;
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
        return json_decode($this->testResult,true);
    }

    /**
     * @param json $testResult
     */
    public function setTestResult($testResult)
    {
        $this->testResult = json_encode($testResult);
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


}
