<?php
/**
 * Created by PhpStorm.
 * User: paolocastro
 * Date: 25/04/2018
 * Time: 20:32
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sonometer
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
}