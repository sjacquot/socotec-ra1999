<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * \class NoiseSource
 * Agency's Noise Source
 * @ingroup Materiel
 *
 * @ORM\Table(name="noise_source")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NoiseSourceRepository")
 */
class NoiseSource
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
     * @ORM\Column(name="brand", type="string", length=255)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="serialNumber", type="string", length=255)
     */
    private $serialNumber;
    /**
     * Many Sonometer have One Agency.
     * @ORM\ManyToOne(targetEntity="Agency", inversedBy="noise_source")
     * @ORM\JoinColumn(name="agency_id", referencedColumnName="id")
     */
    private $agency;
    /**
     * Many Sonometer have Many Operation.
     * @ORM\ManyToMany(targetEntity="Operation", mappedBy="noise_source")
     */
    private $operation;

    public function __construct() {
        $this->operation = new ArrayCollection();
    }

    public function __toString()
    {
        $noisesource =  "Source de bruit, ".$this->brand.", type ".$this->type.", n° ".$this->serialNumber;
        return $noisesource;
    }

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
     * Set brand.
     *
     * @param string $brand
     *
     * @return NoiseSource
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand.
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return NoiseSource
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set serialNumber.
     *
     * @param string $serialNumber
     *
     * @return NoiseSource
     */
    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    /**
     * Get serialNumber.
     *
     * @return string
     */
    public function getSerialNumber()
    {
        return $this->serialNumber;
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
