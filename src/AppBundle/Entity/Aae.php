<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aae
 *
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
     * @ORM\JoinColumn(name="operation_id", referencedColumnName="id")
     */
    private $operation;

    /**
     * N° de la mesure
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $measureNumber;

    /**
     * Local
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $local;

    /**
     * Matériaux absorbants mis en place (αw ≥ 0,1) (M1)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $absorbentMaterialsM1;

    /**
     * Matériaux absorbants mis en place (αw ≥ 0,1) (M2)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $absorbentMaterialsM2;

    /**
     * Matériaux absorbants mis en place (αw ≥ 0,1) (M3)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $absorbentMaterialsM3;

    /**
     * Indice unique d'absorption αw (w1)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $absorptionIndexW1;

    /**
     * Indice unique d'absorption αw (w2)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $absorptionIndexW2;

    /**
     * Indice unique d'absorption αw (w3)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $absorptionIndexW3;

    /**
     * Surface d'absorbant mesurée en m2 (SA1)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $absorberArea1;

    /**
     * Surface d'absorbant mesurée en m2 (SA2)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $absorberArea2;

    /**
     * Surface d'absorbant mesurée en m2 (SA3)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $absorberArea3;

    /**
     * Surface au sol totale mesurée en m2
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $totalFloorArea;

    /**
     * AAE calculée en %
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $aaeCalculation;

    /**
     * AAE objectif RA 1999 en dBA
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $AaeObjectifRa1999;

    /**
     * AAE objectif RA 1999 en % Appreciation
     *
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentAaeObjectifRa1999;

    /**
     * AAE objectif QUALITEL en %
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $AaeObjectifQualitel;

    /**
     * AAE objectif QUALITEL en % Appréciation
     *
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentAaeObjectifQualitel;

    /**
     * AAE objectif RA 1999 en % Seconde column
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $AaeObjectifRa1999Second;

    /**
     * AAE objectif RA 1999 en % Appreciation Seconde column
     *
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentAaeObjectifRa1999Second;

    /**
     * Observations éventuelles
     *
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

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
     * @return string
     */
    public function getMeasureNumber()
    {
        return $this->measureNumber;
    }

    /**
     * @param string $measureNumber
     */
    public function setMeasureNumber($measureNumber)
    {
        $this->measureNumber = $measureNumber;
    }

    /**
     * @return string
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * @param string $local
     */
    public function setLocal($local)
    {
        $this->local = $local;
    }

    /**
     * @return string
     */
    public function getAbsorbentMaterialsM1()
    {
        return $this->absorbentMaterialsM1;
    }

    /**
     * @param string $absorbentMaterialsM1
     */
    public function setAbsorbentMaterialsM1($absorbentMaterialsM1)
    {
        $this->absorbentMaterialsM1 = $absorbentMaterialsM1;
    }

    /**
     * @return string
     */
    public function getAbsorbentMaterialsM2()
    {
        return $this->absorbentMaterialsM2;
    }

    /**
     * @param string $absorbentMaterialsM2
     */
    public function setAbsorbentMaterialsM2($absorbentMaterialsM2)
    {
        $this->absorbentMaterialsM2 = $absorbentMaterialsM2;
    }

    /**
     * @return string
     */
    public function getAbsorbentMaterialsM3()
    {
        return $this->absorbentMaterialsM3;
    }

    /**
     * @param string $absorbentMaterialsM3
     */
    public function setAbsorbentMaterialsM3($absorbentMaterialsM3)
    {
        $this->absorbentMaterialsM3 = $absorbentMaterialsM3;
    }

    /**
     * @return string
     */
    public function getAbsorptionIndexW1()
    {
        return $this->absorptionIndexW1;
    }

    /**
     * @param string $absorptionIndexW1
     */
    public function setAbsorptionIndexW1($absorptionIndexW1)
    {
        $this->absorptionIndexW1 = $absorptionIndexW1;
    }

    /**
     * @return string
     */
    public function getAbsorptionIndexW2()
    {
        return $this->absorptionIndexW2;
    }

    /**
     * @param string $absorptionIndexW2
     */
    public function setAbsorptionIndexW2($absorptionIndexW2)
    {
        $this->absorptionIndexW2 = $absorptionIndexW2;
    }

    /**
     * @return string
     */
    public function getAbsorptionIndexW3()
    {
        return $this->absorptionIndexW3;
    }

    /**
     * @param string $absorptionIndexW3
     */
    public function setAbsorptionIndexW3($absorptionIndexW3)
    {
        $this->absorptionIndexW3 = $absorptionIndexW3;
    }

    /**
     * @return string
     */
    public function getAbsorberArea1()
    {
        return $this->absorberArea1;
    }

    /**
     * @param string $absorberArea1
     */
    public function setAbsorberArea1($absorberArea1)
    {
        $this->absorberArea1 = $absorberArea1;
    }

    /**
     * @return string
     */
    public function getAbsorberArea2()
    {
        return $this->absorberArea2;
    }

    /**
     * @param string $absorberArea2
     */
    public function setAbsorberArea2($absorberArea2)
    {
        $this->absorberArea2 = $absorberArea2;
    }

    /**
     * @return string
     */
    public function getAbsorberArea3()
    {
        return $this->absorberArea3;
    }

    /**
     * @param string $absorberArea3
     */
    public function setAbsorberArea3($absorberArea3)
    {
        $this->absorberArea3 = $absorberArea3;
    }

    /**
     * @return string
     */
    public function getTotalFloorArea()
    {
        return $this->totalFloorArea;
    }

    /**
     * @param string $totalFloorArea
     */
    public function setTotalFloorArea($totalFloorArea)
    {
        $this->totalFloorArea = $totalFloorArea;
    }

    /**
     * @return string
     */
    public function getAaeCalculation()
    {
        return $this->aaeCalculation;
    }

    /**
     * @param string $aaeCalculation
     */
    public function setAaeCalculation($aaeCalculation)
    {
        $this->aaeCalculation = $aaeCalculation;
    }

    /**
     * @return string
     */
    public function getAaeObjectifRa1999()
    {
        return $this->AaeObjectifRa1999;
    }

    /**
     * @param string $AaeObjectifRa1999
     */
    public function setAaeObjectifRa1999($AaeObjectifRa1999)
    {
        $this->AaeObjectifRa1999 = $AaeObjectifRa1999;
    }

    /**
     * @return text
     */
    public function getCommentAaeObjectifRa1999()
    {
        return $this->commentAaeObjectifRa1999;
    }

    /**
     * @param text $commentAaeObjectifRa1999
     */
    public function setCommentAaeObjectifRa1999($commentAaeObjectifRa1999)
    {
        $this->commentAaeObjectifRa1999 = $commentAaeObjectifRa1999;
    }

    /**
     * @return string
     */
    public function getAaeObjectifQualitel()
    {
        return $this->AaeObjectifQualitel;
    }

    /**
     * @param string $AaeObjectifQualitel
     */
    public function setAaeObjectifQualitel($AaeObjectifQualitel)
    {
        $this->AaeObjectifQualitel = $AaeObjectifQualitel;
    }

    /**
     * @return text
     */
    public function getCommentAaeObjectifQualitel()
    {
        return $this->commentAaeObjectifQualitel;
    }

    /**
     * @param text $commentAaeObjectifQualitel
     */
    public function setCommentAaeObjectifQualitel($commentAaeObjectifQualitel)
    {
        $this->commentAaeObjectifQualitel = $commentAaeObjectifQualitel;
    }

    /**
     * @return string
     */
    public function getAaeObjectifRa1999Second()
    {
        return $this->AaeObjectifRa1999Second;
    }

    /**
     * @param string $AaeObjectifRa1999Second
     */
    public function setAaeObjectifRa1999Second($AaeObjectifRa1999Second)
    {
        $this->AaeObjectifRa1999Second = $AaeObjectifRa1999Second;
    }

    /**
     * @return text
     */
    public function getCommentAaeObjectifRa1999Second()
    {
        return $this->commentAaeObjectifRa1999Second;
    }

    /**
     * @param text $commentAaeObjectifRa1999Second
     */
    public function setCommentAaeObjectifRa1999Second($commentAaeObjectifRa1999Second)
    {
        $this->commentAaeObjectifRa1999Second = $commentAaeObjectifRa1999Second;
    }

    /**
     * @return text
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param text $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
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
