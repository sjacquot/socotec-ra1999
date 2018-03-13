<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Deal
 *
 * @ORM\Table(name="report")
 * @ORM\Entity
 */
class Report
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
     * @ORM\Column(name="report_reference", type="string", length=255, nullable=true)
     */
    private $reportReference;

    /**
     * One Operation has One certificate Reference.
     * @ORM\OneToOne(targetEntity="Operation", inversedBy="certifReport")
     * @ORM\JoinColumn(name="operation_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $operation;

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
    public function getReportReference()
    {
        return $this->reportReference;
    }

    /**
     * @param string $reportReference
     */
    public function setReportReference($reportReference)
    {
        $this->reportReference = $reportReference;
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

