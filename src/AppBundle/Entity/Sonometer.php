<?php
/**
 * Created by PhpStorm.
 * User: paolocastro
 * Date: 25/04/2018
 * Time: 20:32
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @defgroup Materiel Matériel
 * Les entités gérants les différents types de matériels d'une agence pour effectuer des mesures
 */



/**
 * \class Sonometer
 * Agency's sonometer
 * @ingroup Materiel
 *
 * @ORM\Table(name="sonometer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SonometerRepository")
 */
class Sonometer
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
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $type;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $serialNumber;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $preamplifierType;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $preamplifierSerialNumber;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $microphoneType;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $MicrophoneSerialNumber;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $calibratorType;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $calibratorSerialNumber;
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"}, nullable=true)
     */
    private $endOfValidity;
    /**
     * Many Sonometer have One Agency.
     * @ORM\ManyToOne(targetEntity="Agency", inversedBy="sonometer")
     * @ORM\JoinColumn(name="agency_id", referencedColumnName="id")
     */
    private $agency;
    /**
     * Many Sonometer have Many Operation.
     * @ORM\ManyToMany(targetEntity="Operation", mappedBy="sonometer")
     */
    private $operation;

    public function __construct() {
        $this->operation = new ArrayCollection();
    }

    public function __toString()
    {
        $sonometer =  "Sonomètre : ".$this->type." n° : ".$this->serialNumber;
        $sonometer .= " Préamplificateur : ".$this->preamplifierType." n° : ".$this->preamplifierSerialNumber;
        $sonometer .= " Microphone : ".$this->microphoneType." n° : ".$this->MicrophoneSerialNumber;
        $sonometer .= " Calibreur : ".$this->calibratorType." n° : ".$this->calibratorSerialNumber;
        //$sonometer .= " Date de validité : ".$this->endOfValidity->format('m/Y');
        $dataEnd = $this->endOfValidity;
        if (is_null($dataEnd)){
            $sonometer .= "Erreur de date";
        }else{
            $sonometer .= " Date de validité : ".$dataEnd->format('m/Y');
        }

        return $sonometer;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    /**
     * @param string $serialNumber
     */
    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;
    }

    /**
     * @return string
     */
    public function getPreamplifierType()
    {
        return $this->preamplifierType;
    }

    /**
     * @param string $preamplifierType
     */
    public function setPreamplifierType($preamplifierType)
    {
        $this->preamplifierType = $preamplifierType;
    }

    /**
     * @return string
     */
    public function getPreamplifierSerialNumber()
    {
        return $this->preamplifierSerialNumber;
    }

    /**
     * @param string $preamplifierSerialNumber
     */
    public function setPreamplifierSerialNumber($preamplifierSerialNumber)
    {
        $this->preamplifierSerialNumber = $preamplifierSerialNumber;
    }

    /**
     * @return string
     */
    public function getMicrophoneType()
    {
        return $this->microphoneType;
    }

    /**
     * @param string $microphoneType
     */
    public function setMicrophoneType($microphoneType)
    {
        $this->microphoneType = $microphoneType;
    }

    /**
     * @return string
     */
    public function getMicrophoneSerialNumber()
    {
        return $this->MicrophoneSerialNumber;
    }

    /**
     * @param string $MicrophoneSerialNumber
     */
    public function setMicrophoneSerialNumber($MicrophoneSerialNumber)
    {
        $this->MicrophoneSerialNumber = $MicrophoneSerialNumber;
    }

    /**
     * @return string
     */
    public function getCalibratorType()
    {
        return $this->calibratorType;
    }

    /**
     * @param string $calibratorType
     */
    public function setCalibratorType($calibratorType)
    {
        $this->calibratorType = $calibratorType;
    }

    /**
     * @return string
     */
    public function getCalibratorSerialNumber()
    {
        return $this->calibratorSerialNumber;
    }

    /**
     * @param string $calibratorSerialNumber
     */
    public function setCalibratorSerialNumber($calibratorSerialNumber)
    {
        $this->calibratorSerialNumber = $calibratorSerialNumber;
    }

    /**
     * @return \DateTime
     */
    public function getEndOfValidity()
    {
        return $this->endOfValidity;
    }

    /**
     * @param \DateTime $endOfValidity
     */
    public function setEndOfValidity($endOfValidity)
    {
        $this->endOfValidity = $endOfValidity;
    }

    /**
     * @return mixed
     */
    public function getAgency()
    {
        return $this->agency;
    }

    /**
     * @param mixed $agency
     */
    public function setAgency($agency)
    {
        $this->agency = $agency;
    }
}