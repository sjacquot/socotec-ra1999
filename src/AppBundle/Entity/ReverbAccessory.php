<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * \class ReverbAccessory
 *
 * @ORM\Table(name="reverb_accessory")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReverbAccessoryRepository")
 */
class ReverbAccessory
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
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;
    private $choices =  array('pistolet d’alarme 6 mm', 'pistolet d’alarme 9 mm', 'claquoir','ballons de baudruche', 'source de bruit rose');
    private $label;
    /**
     * Many ReverbAcessory have One Agency.
     * @ORM\ManyToOne(targetEntity="Agency", inversedBy="reverb_accessory")
     * @ORM\JoinColumn(name="agency_id", referencedColumnName="id")
     */
    private $agency;
    /**
     * Many ReverbAccessory have Many Operation.
     * @ORM\ManyToMany(targetEntity="Operation", mappedBy="reverb_accessory")
     */
    private $operation;

    public function __construct() {
        $this->operation = new ArrayCollection();
    }

    public function __toString()
    {
        $ReverbAccessory =  $this->choices[$this->type]." (mesure de la durée de réverbération)";
        return $ReverbAccessory;
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
     * Set type.
     *
     * @param int $type
     *
     * @return ReverbAccessory
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $agency
     */
    public function setAgency($agency)
    {
        $this->agency = $agency;
    }

    public function getLabel(){
        return $this->choices[$this->type];
    }

}
