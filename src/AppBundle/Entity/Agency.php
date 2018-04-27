<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * Agency
 *
 * @ORM\Table(name="agency")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgencyRepository")
 */
class Agency
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @var string|null
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cp", type="string", length=255, nullable=true)
     */
    private $cp;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=true)
     */
    private $tel;
    /**
     * @var string|null
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * One Agency has Many Sonometer.
     * @ORM\OneToMany(targetEntity="Sonometer", mappedBy="agency")
     */
    private $sonometer;

    /**
     * One Agency has Many Operation.
     * @ORM\OneToMany(targetEntity="Operation", mappedBy="agency")
     */
    private $operation;

    /**
     * Agency constructor.
     */
    public function __construct() {
        $this->sonometer = new ArrayCollection();
        $this->operation = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return ($this->name)?$this->name:'';
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
     * @return Agency
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
     * Set address.
     *
     * @param string|null $address
     *
     * @return Agency
     */
    public function setAddress($address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string|null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city.
     *
     * @param string|null $city
     *
     * @return Agency
     */
    public function setCity($city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string|null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set cp.
     *
     * @param string|null $cp
     *
     * @return Agency
     */
    public function setCp($cp = null)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp.
     *
     * @return string|null
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set tel.
     *
     * @param string|null $tel
     *
     * @return Agency
     */
    public function setTel($tel = null)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel.
     *
     * @return string|null
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @return null|string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param null|string $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    /**
     * @return mixed
     */
    public function getSonometer()
    {
        return $this->sonometer;
    }

    /**
     * @param mixed $sonometer
     */
    public function setSonometer($sonometer)
    {
        $this->sonometer = $sonometer;
    }
}
