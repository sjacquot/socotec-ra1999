<?php

namespace AppBundle\Entity;

use AppBundle\Repository\AaeRepository;
use AppBundle\Service\ExtractAAE;
use Doctrine\ORM\Mapping as ORM;

/**
 * \class Aae
 * AAE Manage DB I/O For AAE data : "Aire d'Absorption Equivalente" \n
 * ExcelSheet : AAE \n
 *
 * @ingroup Acoustique
 *
 * Information ORM : \n
 * @ORM\Table(name="aae")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AaeRepository")
 */
class Aae
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
     * Many AAE results have One Operation.
     * @ORM\ManyToOne(targetEntity="Operation", inversedBy="aae")
     * @ORM\JoinColumn(name="operation_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $operation;


    /**
     * Observations éventuelles
     *
     * @var json
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $comments;

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
    public function getComments()
    {
        return json_decode($this->comments);
    }

    /**
     * @param json $comments
     */
    public function setComments($comments)
    {
        $this->comments = json_encode($comments);
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
