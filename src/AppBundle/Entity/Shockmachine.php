<?php

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * \class Shockmachine
 *
 * @ORM\Table(name="shockmachine")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShockmachineRepository")
 */
class Shockmachine
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
     * @ORM\ManyToOne(targetEntity="Agency", inversedBy="shockmachine")
     * @ORM\JoinColumn(name="agency_id", referencedColumnName="id")
     */
    private $agency;
    /**
     * Many Sonometer have Many Operation.
     * @ORM\ManyToMany(targetEntity="Operation", mappedBy="shockmachine")
     */
    private $operation;

    public function __construct() {
        $this->operation = new ArrayCollection();
    }

    public function __toString()
    {
        $ShockMachine =  "Machine à chocs de marque ".$this->brand.", type ".$this->type.", n° ".$this->serialNumber;
        return $ShockMachine;
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
     * @return Shockmachine
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
     * @return Shockmachine
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
     * @return Shockmachine
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
     * @param mixed $agency
     */
    public function setAgency($agency)
    {
        $this->agency = $agency;
    }

}
