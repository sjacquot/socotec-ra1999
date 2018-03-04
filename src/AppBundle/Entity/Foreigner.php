<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Foreigner
 *
 * @ORM\Table(name="foreigner")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ForeignerRepository")
 */
class Foreigner
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
     * Many Aerien have One Operation.
     * @ORM\ManyToOne(targetEntity="Operation", inversedBy="aerien")
     * @ORM\JoinColumn(name="operation_id", referencedColumnName="id")
     */
    private $operation;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localEmissionName;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $localEmissionVolume;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localEmissionType;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $localReceptionName;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $localReceptionVolume;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $separatingNatureWall;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $separatingThicknessWall;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $separatingDubbingNatureWall;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $separatingDubbingThicknessWall;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $carpentryMaterial;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $carpentryOpening;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $carpentryOpeningType;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $carpentryOpeningNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $rollingShutterBox;

    /**
     * @var string
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $rollingShutterBoxNumber;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $vmcAirIntakeNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $vmcAirIntakePosition;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $vmcAirIntakeType;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $boilerSuctionCup;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * String because you have the  prefix dB
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $weightedStandardizedAcousticIsolation;

    /**
     * String because you have the  prefix dB
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $objectifRa1999;

    /**
     * @var json
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $testResult;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
