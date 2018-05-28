<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * \class Equipement
 * Equipement Manage Manage DB I/O For Equipement data \n
 * ExcelSheet : EQUIPEMENTS \n
 *
 * @ingroup Acoustique
 *
 * Information ORM : \n
 * @ORM\Table(name="equipement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EquipementRepository")
 */
class Equipement
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
     * Many Equipement results have One Operation.
     * @ORM\ManyToOne(targetEntity="Operation", inversedBy="equipement")
     * @ORM\JoinColumn(name="operation_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $operation;

    /**
     * a json of all the line of the resultats de l'essai table
     *
     * @var json
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $type1;
    /**
     * a json of all the line of the resultats de l'essai table
     *
     * @var json
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $type2;

    /**
     * AmbianteNoise modify results for type1
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    /**
     * a json of all the line of the resultats de l'essai table
     *
     * @var json
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $type1Comments;
    /**
     * a json of all the line of the resultats de l'essai table
     *
     * @var json
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $type2Comments;

    /**
     * AmbianteNoise modify results for type1
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $type1AmbiantNoise;
    /**
     * AmbianteNoise modify results for type2
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $type2AmbiantNoise;
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
    public function getType1()
    {
        return json_decode($this->type1);
    }

    /**
     * @param json $type1
     */
    public function setType1($type1)
    {
        $this->type1 = json_encode($type1);
    }

    /**
     * @return json
     */
    public function getType2()
    {
        return json_decode($this->type2);
    }

    /**
     * @param json $type2
     */
    public function setType2($type2)
    {
        $this->type2 = json_encode($type2);
    }

    /**
     * @return string
     */
    public function getType1AmbiantNoise()
    {
        return $this->type1AmbiantNoise;
    }

    /**
     * @param string $type1AmbiantNoise
     */
    public function setType1AmbiantNoise($type1AmbiantNoise)
    {
        $this->type1AmbiantNoise = $type1AmbiantNoise;
    }

    /**
     * @return string
     */
    public function getType2AmbiantNoise()
    {
        return $this->type2AmbiantNoise;
    }

    /**
     * @param string $type2AmbiantNoise
     */
    public function setType2AmbiantNoise($type2AmbiantNoise)
    {
        $this->type2AmbiantNoise = $type2AmbiantNoise;
    }

    /**
     * @return json
     */
    public function getType1Comments()
    {
        return json_decode($this->type1Comments);
    }

    /**
     * @param json $type1Comments
     */
    public function setType1Comments($type1Comments)
    {
        $this->type1Comments = json_encode($type1Comments);
    }

    /**
     * @return json
     */
    public function getType2Comments()
    {
        return json_decode($this->type2Comments);
    }

    /**
     * @param json $type2Comments
     */
    public function setType2Comments($type2Comments)
    {
        $this->type2Comments = json_encode($type2Comments);
    }


}
