<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipement
 *
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
     * @ORM\JoinColumn(name="operation_id", referencedColumnName="id")
     */
    private $operation;

    /**
     * Title of the array like
     * Fiche de traitement de mesure : EQUIPEMENTS TECHNIQUES de type 1
     *
     * Because there is a title
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $equipementType;

    /**
     * N° de la mesure
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $measureNumber;

    /**
     * Local réception
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localReception;

    /**
     * Equipement mesuré
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $measureEquipement;

    /**
     * Emplacement
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $location;

    /**
     * L'équipement mesuré est… (Equipement Type 2)
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $position;

    /**
     * LAS,max en dBA Essai 1 (Equipement Type 1)
     *
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $LASmaxTry1;

    /**
     * LAS,max en dBA Essai 2 (Equipement Type 1)
     *
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $LASmaxTry2;

    /**
     * LAS,max en dBA Essai 3 (Equipement Type 1)
     *
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $LASmaxTry3;

    /**
     * LAS,moyen en dBA (Equipement Type 1)
     *
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $LASavg;

    /**
     * Ln équipt en dBA (Equipement Type 1)
     *
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $LnEquipement;

    /**
     * Ln bruit de fond en dBA
     *
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $LnBackgroundNoise;

    /**
     * Mesure affectée par le bruit de fond
     *
     * @var string
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $MeasureAffectedByBackgroundNoise;

    /**
     * Tr 250 Hz en s
     *
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $tr250;

    /**
     * Tr 500 Hz en s
     *
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $tr500;

    /**
     * Tr 1000 Hz en s
     *
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $tr1000;

    /**
     * Tr 2000 Hz en s
     *
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $tr2000;

    /**
     * Correction de Tr en dBA
     *
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $correctionTr;

    /**
     * LnA,T en dBA
     *
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $LnAT;

    /**
     * LnA,T objectif RA 1999 en dBA
     *
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $LnATobjectifRa1999;

    /**
     * LnA,T objectif RA 1999 en dBA Appreciation
     *
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentLnATobjectifRa1999;

    /**
     * LnA,T objectif QUALITEL en dBA
     *
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $LnATobjectifQualitel;

    /**
     * LnA,T objectif QUALITEL en dBA Appréciation
     *
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentLnATobjectifQualitel;

    /**
     * LnA,T objectif RA 1999 en dBA Seconde column
     *
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $LnATobjectifRa1999Second;

    /**
     * LnA,T objectif RA 1999 en dBA Appreciation Seconde column
     *
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentLnATobjectifRa1999Second;

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
    public function getEquipementType()
    {
        return $this->equipementType;
    }

    /**
     * @param string $equipementType
     */
    public function setEquipementType($equipementType)
    {
        $this->equipementType = $equipementType;
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
    public function getLocalReception()
    {
        return $this->localReception;
    }

    /**
     * @param string $localReception
     */
    public function setLocalReception($localReception)
    {
        $this->localReception = $localReception;
    }

    /**
     * @return string
     */
    public function getMeasureEquipement()
    {
        return $this->measureEquipement;
    }

    /**
     * @param string $measureEquipement
     */
    public function setMeasureEquipement($measureEquipement)
    {
        $this->measureEquipement = $measureEquipement;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getLASmaxTry1()
    {
        return $this->LASmaxTry1;
    }

    /**
     * @param string $LASmaxTry1
     */
    public function setLASmaxTry1($LASmaxTry1)
    {
        $this->LASmaxTry1 = $LASmaxTry1;
    }

    /**
     * @return string
     */
    public function getLASmaxTry2()
    {
        return $this->LASmaxTry2;
    }

    /**
     * @param string $LASmaxTry2
     */
    public function setLASmaxTry2($LASmaxTry2)
    {
        $this->LASmaxTry2 = $LASmaxTry2;
    }

    /**
     * @return string
     */
    public function getLASmaxTry3()
    {
        return $this->LASmaxTry3;
    }

    /**
     * @param string $LASmaxTry3
     */
    public function setLASmaxTry3($LASmaxTry3)
    {
        $this->LASmaxTry3 = $LASmaxTry3;
    }

    /**
     * @return string
     */
    public function getLASavg()
    {
        return $this->LASavg;
    }

    /**
     * @param string $LASavg
     */
    public function setLASavg($LASavg)
    {
        $this->LASavg = $LASavg;
    }

    /**
     * @return string
     */
    public function getLnEquipement()
    {
        return $this->LnEquipement;
    }

    /**
     * @param string $LnEquipement
     */
    public function setLnEquipement($LnEquipement)
    {
        $this->LnEquipement = $LnEquipement;
    }

    /**
     * @return string
     */
    public function getLnBackgroundNoise()
    {
        return $this->LnBackgroundNoise;
    }

    /**
     * @param string $LnBackgroundNoise
     */
    public function setLnBackgroundNoise($LnBackgroundNoise)
    {
        $this->LnBackgroundNoise = $LnBackgroundNoise;
    }

    /**
     * @return string
     */
    public function getMeasureAffectedByBackgroundNoise()
    {
        return $this->MeasureAffectedByBackgroundNoise;
    }

    /**
     * @param string $MeasureAffectedByBackgroundNoise
     */
    public function setMeasureAffectedByBackgroundNoise($MeasureAffectedByBackgroundNoise)
    {
        $this->MeasureAffectedByBackgroundNoise = $MeasureAffectedByBackgroundNoise;
    }

    /**
     * @return string
     */
    public function getTr250()
    {
        return $this->tr250;
    }

    /**
     * @param string $tr250
     */
    public function setTr250($tr250)
    {
        $this->tr250 = $tr250;
    }

    /**
     * @return string
     */
    public function getTr500()
    {
        return $this->tr500;
    }

    /**
     * @param string $tr500
     */
    public function setTr500($tr500)
    {
        $this->tr500 = $tr500;
    }

    /**
     * @return string
     */
    public function getTr1000()
    {
        return $this->tr1000;
    }

    /**
     * @param string $tr1000
     */
    public function setTr1000($tr1000)
    {
        $this->tr1000 = $tr1000;
    }

    /**
     * @return string
     */
    public function getTr2000()
    {
        return $this->tr2000;
    }

    /**
     * @param string $tr2000
     */
    public function setTr2000($tr2000)
    {
        $this->tr2000 = $tr2000;
    }

    /**
     * @return string
     */
    public function getCorrectionTr()
    {
        return $this->correctionTr;
    }

    /**
     * @param string $correctionTr
     */
    public function setCorrectionTr($correctionTr)
    {
        $this->correctionTr = $correctionTr;
    }

    /**
     * @return string
     */
    public function getLnAT()
    {
        return $this->LnAT;
    }

    /**
     * @param string $LnAT
     */
    public function setLnAT($LnAT)
    {
        $this->LnAT = $LnAT;
    }

    /**
     * @return string
     */
    public function getLnATobjectifRa1999()
    {
        return $this->LnATobjectifRa1999;
    }

    /**
     * @param string $LnATobjectifRa1999
     */
    public function setLnATobjectifRa1999($LnATobjectifRa1999)
    {
        $this->LnATobjectifRa1999 = $LnATobjectifRa1999;
    }

    /**
     * @return text
     */
    public function getCommentLnATobjectifRa1999()
    {
        return $this->commentLnATobjectifRa1999;
    }

    /**
     * @param text $commentLnATobjectifRa1999
     */
    public function setCommentLnATobjectifRa1999($commentLnATobjectifRa1999)
    {
        $this->commentLnATobjectifRa1999 = $commentLnATobjectifRa1999;
    }

    /**
     * @return string
     */
    public function getLnATobjectifQualitel()
    {
        return $this->LnATobjectifQualitel;
    }

    /**
     * @param string $LnATobjectifQualitel
     */
    public function setLnATobjectifQualitel($LnATobjectifQualitel)
    {
        $this->LnATobjectifQualitel = $LnATobjectifQualitel;
    }

    /**
     * @return text
     */
    public function getCommentLnATobjectifQualitel()
    {
        return $this->commentLnATobjectifQualitel;
    }

    /**
     * @param text $commentLnATobjectifQualitel
     */
    public function setCommentLnATobjectifQualitel($commentLnATobjectifQualitel)
    {
        $this->commentLnATobjectifQualitel = $commentLnATobjectifQualitel;
    }

    /**
     * @return string
     */
    public function getLnATobjectifRa1999Second()
    {
        return $this->LnATobjectifRa1999Second;
    }

    /**
     * @param string $LnATobjectifRa1999Second
     */
    public function setLnATobjectifRa1999Second($LnATobjectifRa1999Second)
    {
        $this->LnATobjectifRa1999Second = $LnATobjectifRa1999Second;
    }

    /**
     * @return text
     */
    public function getCommentLnATobjectifRa1999Second()
    {
        return $this->commentLnATobjectifRa1999Second;
    }

    /**
     * @param text $commentLnATobjectifRa1999Second
     */
    public function setCommentLnATobjectifRa1999Second($commentLnATobjectifRa1999Second)
    {
        $this->commentLnATobjectifRa1999Second = $commentLnATobjectifRa1999Second;
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
