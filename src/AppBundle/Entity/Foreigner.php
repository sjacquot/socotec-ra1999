<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Foreigner
 *
 * @ORM\Table(name="foreigner")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ForeignerRepository")
 */
class Foreigner
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
     * Fiche de traitement de mesure n° F(1)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $idOfSheet;

    /**
     * Emission Name
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localEmissionName;

    /**
     * Emission Name
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $localEmissionVolume;

    /**
     * Emission Type
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localEmissionType;

    /**
     * LOCAL RECEPTION Nom
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localReceptionName;

    /**
     * Local reception volume (m3)
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $localReceptionVolume;

    /**
     * Paroi separative Nature Paroi
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $separatingNatureWall;

    /**
     * Paroi separative Epaisseur (cm)
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $separatingThicknessWall;

    /**
     * paroi separative Nature doublage
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $separatingDubbingNatureWall;

    /**
     * paroi separative (Doublage) Epaisseur(cm)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $separatingDubbingThicknessWall;

    /**
     * Menuserie Matériaux
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $carpentryMaterial;

    /**
     * Menuserie Ouvrant
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $carpentryOpening;

    /**
     * Menuserie (Ouvrant) Type
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $carpentryOpeningType;

    /**
     * Menuserie (Ouvrant) Number
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $carpentryOpeningNumber;

    /**
     * Menuserie Coffre volet Roulant
     *
     * @var string
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $rollingShutterBox;

    /**
     * ENTRÉE D'AIR VMC Nombre
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $vmcAirIntakeNumber;

    /**
     * ENTRÉE D'AIR VMC Position
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $vmcAirIntakePosition;

    /**
     * ENTRÉE D'AIR VMC Type
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $vmcAirIntakeType;

    /**
     * VENTOUSE CHAUDIERE
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $boilerSuctionCup;

    /**
     * OBSERVATION(S) EVENTUELLE(S) :
     *
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * Isolement acoustique standardisé pondéré :
     *
     * String because you have the  prefix dB
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $weightedStandardizedAcousticIsolation;

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
    public function getLocalEmissionType()
    {
        return $this->localEmissionType;
    }

    /**
     * @param string $localEmissionType
     */
    public function setLocalEmissionType($localEmissionType)
    {
        $this->localEmissionType = $localEmissionType;
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
    public function getSeparatingDubbingThicknessWall()
    {
        return $this->separatingDubbingThicknessWall;
    }

    /**
     * @param string $separatingDubbingThicknessWall
     */
    public function setSeparatingDubbingThicknessWall($separatingDubbingThicknessWall)
    {
        $this->separatingDubbingThicknessWall = $separatingDubbingThicknessWall;
    }

    /**
     * @return string
     */
    public function getCarpentryMaterial()
    {
        return $this->carpentryMaterial;
    }

    /**
     * @param string $carpentryMaterial
     */
    public function setCarpentryMaterial($carpentryMaterial)
    {
        $this->carpentryMaterial = $carpentryMaterial;
    }

    /**
     * @return string
     */
    public function getCarpentryOpening()
    {
        return $this->carpentryOpening;
    }

    /**
     * @param string $carpentryOpening
     */
    public function setCarpentryOpening($carpentryOpening)
    {
        $this->carpentryOpening = $carpentryOpening;
    }

    /**
     * @return string
     */
    public function getCarpentryOpeningType()
    {
        return $this->carpentryOpeningType;
    }

    /**
     * @param string $carpentryOpeningType
     */
    public function setCarpentryOpeningType($carpentryOpeningType)
    {
        $this->carpentryOpeningType = $carpentryOpeningType;
    }

    /**
     * @return string
     */
    public function getCarpentryOpeningNumber()
    {
        return $this->carpentryOpeningNumber;
    }

    /**
     * @param string $carpentryOpeningNumber
     */
    public function setCarpentryOpeningNumber($carpentryOpeningNumber)
    {
        $this->carpentryOpeningNumber = $carpentryOpeningNumber;
    }

    /**
     * @return string
     */
    public function getRollingShutterBox()
    {
        return $this->rollingShutterBox;
    }

    /**
     * @param string $rollingShutterBox
     */
    public function setRollingShutterBox($rollingShutterBox)
    {
        $this->rollingShutterBox = $rollingShutterBox;
    }

    /**
     * @return float
     */
    public function getVmcAirIntakeNumber()
    {
        return $this->vmcAirIntakeNumber;
    }

    /**
     * @param float $vmcAirIntakeNumber
     */
    public function setVmcAirIntakeNumber($vmcAirIntakeNumber)
    {
        $this->vmcAirIntakeNumber = $vmcAirIntakeNumber;
    }

    /**
     * @return string
     */
    public function getVmcAirIntakePosition()
    {
        return $this->vmcAirIntakePosition;
    }

    /**
     * @param string $vmcAirIntakePosition
     */
    public function setVmcAirIntakePosition($vmcAirIntakePosition)
    {
        $this->vmcAirIntakePosition = $vmcAirIntakePosition;
    }

    /**
     * @return string
     */
    public function getVmcAirIntakeType()
    {
        return $this->vmcAirIntakeType;
    }

    /**
     * @param string $vmcAirIntakeType
     */
    public function setVmcAirIntakeType($vmcAirIntakeType)
    {
        $this->vmcAirIntakeType = $vmcAirIntakeType;
    }

    /**
     * @return string
     */
    public function getBoilerSuctionCup()
    {
        return $this->boilerSuctionCup;
    }

    /**
     * @param string $boilerSuctionCup
     */
    public function setBoilerSuctionCup($boilerSuctionCup)
    {
        $this->boilerSuctionCup = $boilerSuctionCup;
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
