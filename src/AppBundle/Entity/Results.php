<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Results
 *
 * @ORM\Table(name="results")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResultsRepository")
 */
class Results
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
     * Many Results have One Operation.
     * @ORM\ManyToOne(targetEntity="Operation", inversedBy="results")
     * @ORM\JoinColumn(name="operation_id", referencedColumnName="id")
     */
    private $operation;

    /**
     * a json of all the line of the resultats de l'essai table
     *
     * @var json
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $data;

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
     * @return json
     */
    public function getData()
    {
        return json_decode($this->data);
    }

    /**
     * @param json $data
     */
    public function setData($data)
    {
        $this->data = json_encode($data);
    }
}
