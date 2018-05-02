<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Software
 *
 * @ORM\Table(name="software")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SoftwareRepository")
 */
class Software
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=255)
     */
    private $version;

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=255)
     */
    private $brand;

    /**
     * Many Sonometer have One Agency.
     * @ORM\ManyToOne(targetEntity="Agency", inversedBy="software")
     * @ORM\JoinColumn(name="agency_id", referencedColumnName="id")
     */
    private $agency;
    /**
     * Many Sonometer have Many Operation.
     * @ORM\ManyToMany(targetEntity="Operation", mappedBy="software")
     */
    private $operation;

    public function __construct() {
        $this->operation = new ArrayCollection();
    }
    /**
     *
    */
    public function __toString()
    {
        return 'Logiciel '. $this->name.', version '.$this->version.' de la société '.$this->brand;
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
     * Set name.
     *
     * @param string $name
     *
     * @return Software
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set version.
     *
     * @param string $version
     *
     * @return Software
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set brand.
     *
     * @param string $brand
     *
     * @return Software
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
