<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @defgroup Office Documents office
 * Les entités gérants les différents types de matériels d'une agence pour effectuer des mesures
 */


/**
 * \class Certificate
 *
 *  @ingroup Office
 *
 * @ORM\Table(name="certificate")
 * @ORM\Entity
 */
class Certificate
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="certif_reference", type="string", length=255, nullable=true)
     */
    private $certifReference;

    /**
     * One Operation has One certificate Reference.
     * @ORM\OneToOne(targetEntity="Operation", inversedBy="certifReference")
     * @ORM\JoinColumn(name="operation_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $operation;

    public function __toString()
    {
        return ($this->certifReference)?$this->certifReference:'';
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
    public function getCertifReference()
    {
        return $this->certifReference;
    }

    /**
     * @param string $certifReference
     */
    public function setCertifReference($certifReference)
    {
        $this->certifReference = $certifReference;
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
}

