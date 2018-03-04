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
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localEmissionName;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $localEmissionVolume;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localReceptionName;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $localReceptionVolume;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $separatingNatureWall;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $separatingDubbingNatureWall;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $separatingThicknessWall;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $doorNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $extractionMouth;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $facadeDoublingNature;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $facadeDoublingThickness;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $transmissionType;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localReceptionSurface;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * String because you have the  prefix dB
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $weightedStandardizedAcousticIsolation;

    /**
     * String because you have the  prefix dB
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $objectifRa1999;

    /**
     * @var json
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $testResult;

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
     * @return float
     */
    public function getLocalEmissionVolume()
    {
        return $this->localEmissionVolume;
    }

    /**
     * @param float $localEmissionVolume
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
     * @return float
     */
    public function getLocalReceptionVolume()
    {
        return $this->localReceptionVolume;
    }

    /**
     * @param float $localReceptionVolume
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
     * @return float
     */
    public function getSeparatingThicknessWall()
    {
        return $this->separatingThicknessWall;
    }

    /**
     * @param float $separatingThicknessWall
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
     * @return float
     */
    public function getFacadeDoublingThickness()
    {
        return $this->facadeDoublingThickness;
    }

    /**
     * @param float $facadeDoublingThickness
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
        return $this->testResult;
    }

    /**
     * @param json $testResult
     */
    public function setTestResult($testResult)
    {
        $this->testResult = $testResult;
    }
}
