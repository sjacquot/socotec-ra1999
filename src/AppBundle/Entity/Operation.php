<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\IOFactory;
use DateTime;

/**
 * \class Operation
 *
 * @ORM\Table(name="operation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OperationRepository")
 */
class Operation
{
/**
 * Constants value for Status values can be
 * Draft
 * DraftWithSheet | ReportGenerated | CertGenerated (binary or operation)
 * Closed
 *
 */
    /**
     * Operation is draft (new)
     */
    const Draft = 0;
    /**
     * Operation with SpreadSheet Workbook loaded
     */
    const DraftWithSheet = 1;
    /**
     * Operation with RA199 measuring report
     */
    const ReportGenerated = 2;
    /**
     * Operation with RA199 Certificate
     */
    const CertGenerated = 4;
    /**
     *   Operation finished
     */
    const Closed = 8;

    /**
     * @var integer
     */
    private $SheetCount;
    /**
     * @var array
     */
    private $sheetNames;
    /**
     * @var array
     */
    const sheetName = "Renseignements";
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    // Champs OPERATION
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="operation_address", type="text", nullable=true)
     */
    private $operationAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="operation_city", type="string", length=255, nullable=true)
     */
    private $operationCity;
    /**
     * @var integer
     *
     * @ORM\Column(name="operation_nb_building", type="integer", length=255, nullable=true)
     */
    private $operationNbBuilding = 1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="operation_indiv", type="boolean", nullable=true)
     */
    private $operationIndividuel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="operation_collec", type="boolean", nullable=true)
     */
    private $operationCollectif;
    /**
     * @var integer
     *
     * @ORM\Column(name="operation_nb_indiv", type="integer", nullable=true)
     */
    private $operationNbIndividuel;

    /**
     * @var integer
     *
     * @ORM\Column(name="operation_nb_collec", type="integer", nullable=true)
     */
    private $operationNbCollectif;

    /**
     * @var string
     *
     * @ORM\Column(name="operation_route300", type="array", length=255, nullable=true)
     */
    private $operationRoute300 = array();
    /**
     * @var string
     *
     * @ORM\Column(name="operation_train300", type="array", length=255, nullable=true)
     */
    private $operationTrain300 = array();
    /**
     * @var string
     *
     * @ORM\Column(name="operation_zone_peb", type="array", length=255, nullable=true)
     */
    private $operationZonePEB = array();
    /**
     * @var string
     *
     * @ORM\Column(name="operation_cp", type="string", length=255, nullable=true)
     */
    private $operationCP;

    /**
     * @var string
     *
     * @ORM\Column(name="operation_label", type="string", length=255, nullable=true)
     */
    private $operationLabel;
    /**
     * @var boolean
     *
     * @ORM\Column(name="operation_vmc_simple", type="boolean", length=255, nullable=true)
     */
    private $operationVMCSimple;
    /**
     * @var boolean
     *
     * @ORM\Column(name="operation_vmc_double", type="boolean", length=255, nullable=true)
     */
    private $operationVMCDouple;
    // Champs MO
    /**
     * @var string
     *
     * @ORM\Column(name="mo_name", type="string", length=255, nullable=true)
     */
    private $moName;
    /**
     * @var string
     *
     * @ORM\Column(name="mo_dest", type="string", length=255, nullable=true)
     */
    private $moDest;
    /**
     * @var string
     *
     * @ORM\Column(name="mo_address", type="string", length=255, nullable=true)
     */
    private $moAddress;
    /**
     * @var string
     *
     * @ORM\Column(name="mo_address_comp", type="string", length=255, nullable=true)
     */
    private $moAddressComp;
    /**
     * @var string
     *
     * @ORM\Column(name="mo_cp", type="string", length=255, nullable=true)
     */
    private $moCP;
    /**
     * @var string
     *
     * @ORM\Column(name="mo_city", type="string", length=255, nullable=true)
     */
    private $moCity;
    /**
     * @var string
     *
     * @ORM\Column(name="mo_tel", type="string", length=255, nullable=true)
     */
    private $moTel;
    /**
     * @var string
     *
     * @ORM\Column(name="mo_email", type="string", length=255, nullable=true)
     */
    private $moEmail;
    // Champs PC
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pc_request_date", type="datetime", options={"default": "CURRENT_TIMESTAMP"}, nullable=true)
     */
    private $pcRequestDate;
    /**
     * @var string
     *
     * @ORM\Column(name="pc_reference", type="string", length=255, nullable=true)
     */
    private $pcReference;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pc_date", type="datetime", options={"default": "CURRENT_TIMESTAMP"}, nullable=true)
     */
    private $pcDate;
    /**
     * @var string
     *
     * @ORM\Column(name="pc_nb_phase", type="string", length=255, nullable=true)
     */
    private $pcNbPhase;
    /**
     * @var string
     *
     * @ORM\Column(name="pc_current_phase", type="string", length=255, nullable=true)
     */
    private $pcCurrentPhase;
    // Champs Calendar
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cal_start_date", type="datetime", options={"default": "CURRENT_TIMESTAMP"}, nullable=true)
     */
    private $calStartDate;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cal_end_date", type="datetime", options={"default": "CURRENT_TIMESTAMP"}, nullable=true)
     */
    private $calEndDate;
    // Champs SOCOTEC
    /**
     * @var string
     *
     * @ORM\Column(name="case_reference", type="string", length=255, nullable=true)
     */
    private $caseReference;
    /**
     * @var string
     *
     * @ORM\Column(name="measure_author", type="string", length=255, nullable=true)
     */
    private $measureAuthor;
    /**
     * @var string
     *
     * @ORM\Column(name="measure_company", type="string", length=255, nullable=true)
     */
    private $measureCompany = 'SOCOTEC';
    /**
     * @var string
     *
     * @ORM\Column(name="sheet_date", type="datetime", options={"default": "CURRENT_TIMESTAMP"}, nullable=true)
     */
    private $sheetDate;
    /**
     * @var string
     *
     * @ORM\Column(name="doc_author", type="string", length=255, nullable=true)
     */
    private $DocAuthor;
    /**
     * @var string
     *
     * @ORM\Column(name="doc_author_email", type="string", length=255, nullable=true)
     */
    private $DocAuthorEmail;
    /**
     * @var string
     *
     * @ORM\Column(name="doc_chrono_ref", type="string", length=255, nullable=true)
     */
    private $DocChronoRef;
    /**
     * @var string
     *
     * @ORM\Column(name="nb_measure", type="string", length=255, nullable=true)
     */
    private $NbMeasure;
    /**
     * @var string
     *
     * @ORM\Column(name="company_speaker", type="string", length=255, nullable=true)
     */
    private $CompanySpeaker;
    /**
     * @var string
     *
     * @ORM\OneToOne(targetEntity="Report", mappedBy="operation")
     */
    private $reportReference;

    /**
     * @var string
     *
     * @ORM\OneToOne(targetEntity="Certificate", mappedBy="operation")
     */
    private $certifReference;

    // Champs Intervenants/Equipe
    /**
     * @var string
     *
     * @ORM\Column(name="delegate_mo", type="string", length=255, nullable=true)
     */
    private $delegateMO;
    /**
     * @var string
     *
     * @ORM\Column(name="delegate_mo_address", type="string", length=255, nullable=true)
     */
    private $delegateMOAddress;
    /**
     * @var string
     *
     * @ORM\Column(name="me_name", type="string", length=255, nullable=true)
     */
    private $MEName;
    /**
     * @var string
     *
     * @ORM\Column(name="me_address", type="string", length=255, nullable=true)
     */
    private $MEAddress;
    /**
     * @var string
     *
     * @ORM\Column(name="me_mission", type="string", length=255, nullable=true)
     */
    private $MEMission;
    /**
     * @var string
     *
     * @ORM\Column(name="other_me_name", type="string", length=255, nullable=true)
     */
    private $OtherMEName;
    /**
     * @var string
     *
     * @ORM\Column(name="other_me_mission", type="string", length=255, nullable=true)
     */
    private $OtherMEMission;
    /**
     * @var string
     *
     * @ORM\Column(name="bet_structure_name", type="string", length=255, nullable=true)
     */
    private $BETStructureName;
    /**
     * @var string
     *
     * @ORM\Column(name="bet_structure_mission", type="string", length=255, nullable=true)
     */
    private $BETStructureMission;
    /**
     * @var string
     *
     * @ORM\Column(name="bet_fluid_name", type="string", length=255, nullable=true)
     */
    private $BETFluidName;
    /**
     * @var string
     *
     * @ORM\Column(name="bet_fluid_mission", type="string", length=255, nullable=true)
     */
    private $BETFluidMission;
    /**
     * @var string
     *
     * @ORM\Column(name="bet_thermal_name", type="string", length=255, nullable=true)
     */
    private $BETThermalName;
    /**
     * @var string
     *
     * @ORM\Column(name="bet_thermal_mission", type="string", length=255, nullable=true)
     */
    private $BETThermalMission;
    /**
     * @var string
     *
     * @ORM\Column(name="bet_audio_name", type="string", length=255, nullable=true)
     */
    private $BETAudioName;
    /**
     * @var string
     *
     * @ORM\Column(name="bet_audio_mission", type="string", length=255, nullable=true)
     */
    private $BETAudioMission;
    /**
     * @var string
     *
     * @ORM\Column(name="other_bet_amo_name", type="string", length=255, nullable=true)
     */
    private $OtherBET_AMOName;
    /**
     * @var string
     *
     * @ORM\Column(name="other_bet_amo_mission", type="string", length=255, nullable=true)
     */
    private $OtherBET_AMOMission;


    // Utility fields / XLS Extract

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="measure_date", type="datetime", options={"default": "CURRENT_TIMESTAMP"}, nullable=true)
     */
    private $measureDate;

    /**
     * @var string
     *
     * @ORM\Column(name="info", type="text", nullable=true)
     */
    private $info;

    /**
     * @var string
     *
     * @ORM\Column(name="operation_objective", type="string", length=255, nullable=true)
     */
    private $operationObjective;

    /**
     * @var string
     *
     * @ORM\Column(name="operation_measure_ref", type="string", length=255, nullable=true)
     */
    private $operationMeasureRef;


    /**
     * @var string
     *
     * @ORM\Column(name="measure_report", type="string", length=255, nullable=true)
     */
    private $measureReport;

    /**
     * @var string
     *
     * @ORM\Column(name="measure_cert", type="string", length=255, nullable=true)
     */
    private $measureCert;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $updatedAt;


    /**
     * One Product has One Shipment.
     * @ORM\OneToOne(targetEntity="Document")
     * @ORM\JoinColumn(name="document_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    private $document;


    /**
     * One Operation has Many Pictures.
     * @ORM\OneToMany(targetEntity="Pictures", mappedBy="operation")
     */
    private $pictures;

    /**
     * One Operation has Many Aerien.
     * @ORM\OneToMany(targetEntity="Aerien", mappedBy="operation")
     */
    private $aerien;

    /**
     * One Operation has Many Foreigner (Aérien exterieur).
     * @ORM\OneToMany(targetEntity="Foreigner", mappedBy="operation")
     */
    private $foreigner;

    /**
     * One Operation has Many Shock.
     * @ORM\OneToMany(targetEntity="Shock", mappedBy="operation")
     */
    private $shock;

    /**
     * One Operation has Many Equipement result.
     * @ORM\OneToMany(targetEntity="Equipement", mappedBy="operation")
     */
    private $equipement;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Agency", inversedBy="operation")
     * @ORM\JoinColumn(name="agency_id", referencedColumnName="id", nullable=true)
     */
    private $agency;

    /**
     * One Operation has One Results set.
     * @ORM\OneToOne(targetEntity="Results", mappedBy="operation")
     */
    private $results;

    /**
     * One Operation has One Aae result.
     * @ORM\OneToMany(targetEntity="Aae", mappedBy="operation")
     */
    private $aae;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status = self::Draft;

    /**
     * Many Operation have Many Sonometer.
     * @ORM\ManyToMany(targetEntity="Sonometer", inversedBy="operation")
     * @ORM\JoinTable(name="operation_sonometer")
     */
    private $sonometer;
    /**
     * Many Operation have Many NoiseSource.
     * @ORM\ManyToMany(targetEntity="NoiseSource", inversedBy="operation")
     * @ORM\JoinTable(name="operation_noise_source")
     */
    private $noise_source;
    /**
     * Many Operation have Many Shockmachine.
     * @ORM\ManyToMany(targetEntity="Shockmachine", inversedBy="operation")
     * @ORM\JoinTable(name="operation_shockmachine")
     */
    private $shockmachine;
    /**
     * Many Operation have Many ReverbAcessory.
     * @ORM\ManyToMany(targetEntity="ReverbAccessory", inversedBy="operation")
     * @ORM\JoinTable(name="operation_reverb_accessory")
     */
    private $reverb_accessory;
    /**
     * Many Operation have Many Software.
     * @ORM\ManyToMany(targetEntity="Software", inversedBy="operation")
     * @ORM\JoinTable(name="operation_software")
     */
    private $software;


    /**
     * Operation constructor.
     */
    public function __construct()
    {
        $this->createdAt= new \DateTime();
        $this->updatedAt= new \DateTime();
        $this->shock = new ArrayCollection();
        $this->aerien = new ArrayCollection();
        $this->foreigner = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->sonometer = new ArrayCollection();
        $this->noise_source = new ArrayCollection();
        $this->shockmachine = new ArrayCollection();
        $this->reverb_accessory = new ArrayCollection();
        $this->software = new ArrayCollection();
    }

    public function __toString()
    {
        return ($this->caseReference)?$this->caseReference:'';
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
     * @return bool
     */
    public function isOperationIndividuel()
    {
        return $this->operationIndividuel;
    }

    /**
     * @param bool $operationIndividuel
     */
    public function setOperationIndividuel($operationIndividuel)
    {
        $this->operationIndividuel = $operationIndividuel;
    }

    /**
     * @return bool
     */
    public function isOperationCollectif()
    {
        return $this->operationCollectif;
    }

    /**
     * @param bool $operationCollectif
     */
    public function setOperationCollectif($operationCollectif)
    {
        $this->operationCollectif = $operationCollectif;
    }

    /**
     * @return string
     */
    public function getOperationNbBuilding()
    {
        return $this->operationNbBuilding;
    }

    /**
     * @param string $operationNbBuilding
     */
    public function setOperationNbBuilding($operationNbBuilding)
    {
        $this->operationNbBuilding = $operationNbBuilding;
    }

    /**
     * @return string
     */
    public function getOperationRoute300()
    {
        return $this->operationRoute300;
    }

    /**
     * @param string $operationRoute300
     */
    public function setOperationRoute300($operationRoute300)
    {
        $this->operationRoute300 = $operationRoute300;
    }

    /**
     * @return string
     */
    public function getOperationTrain300()
    {
        return $this->operationTrain300;
    }

    /**
     * @param string $operationTrain300
     */
    public function setOperationTrain300($operationTrain300)
    {
        $this->operationTrain300 = $operationTrain300;
    }

    /**
     * @return string
     */
    public function getOperationZonePEB()
    {
        return $this->operationZonePEB;
    }

    /**
     * @param string $operationZonePEB
     */
    public function setOperationZonePEB($operationZonePEB)
    {
        $this->operationZonePEB = $operationZonePEB;
    }

    /**
     * @return string
     */
    public function getOperationLabel()
    {
        return $this->operationLabel;
    }

    /**
     * @param string $operationLabel
     */
    public function setOperationLabel($operationLabel)
    {
        $this->operationLabel = $operationLabel;
    }

    /**
     * @return int
     */
    public function getOperationNbIndividuel()
    {
        return $this->operationNbIndividuel;
    }

    /**
     * @param int $operationNbIndividuel
     */
    public function setOperationNbIndividuel($operationNbIndividuel)
    {
        $this->operationNbIndividuel = $operationNbIndividuel;
    }

    /**
     * @return int
     */
    public function getOperationNbCollectif()
    {
        return $this->operationNbCollectif;
    }

    /**
     * @param int $operationNbCollectif
     */
    public function setOperationNbCollectif($operationNbCollectif)
    {
        $this->operationNbCollectif = $operationNbCollectif;
    }

    /**
     * @return bool
     */
    public function isOperationVMCSimple()
    {
        return $this->operationVMCSimple;
    }

    /**
     * @param bool $operationVMCSimple
     */
    public function setOperationVMCSimple($operationVMCSimple)
    {
        $this->operationVMCSimple = $operationVMCSimple;
    }

    /**
     * @return bool
     */
    public function isOperationVMCDouple()
    {
        return $this->operationVMCDouple;
    }

    /**
     * @param bool $operationVMCDouple
     */
    public function setOperationVMCDouple($operationVMCDouple)
    {
        $this->operationVMCDouple = $operationVMCDouple;
    }

    /**
     * @return string
     */
    public function getOperationCP()
    {
        return $this->operationCP;
    }

    /**
     * @param string $operationCP
     */
    public function setOperationCP($operationCP)
    {
        $this->operationCP = $operationCP;
    }

    /**
     * @return string
     */
    public function getMoName()
    {
        return $this->moName;
    }

    /**
     * @param string $moName
     */
    public function setMoName($moName)
    {
        $this->moName = $moName;
    }

    /**
     * @return string
     */
    public function getMoDest()
    {
        return $this->moDest;
    }

    /**
     * @param string $moDest
     */
    public function setMoDest($moDest)
    {
        $this->moDest = $moDest;
    }

    /**
     * @return string
     */
    public function getMoAddress()
    {
        return $this->moAddress;
    }

    /**
     * @param string $moAddress
     */
    public function setMoAddress($moAddress)
    {
        $this->moAddress = $moAddress;
    }

    /**
     * @return string
     */
    public function getMoAddressComp()
    {
        return $this->moAddressComp;
    }

    /**
     * @param string $moAddressComp
     */
    public function setMoAddressComp($moAddressComp)
    {
        $this->moAddressComp = $moAddressComp;
    }

    /**
     * @return string
     */
    public function getMoCP()
    {
        return $this->moCP;
    }

    /**
     * @param string $moCP
     */
    public function setMoCP($moCP)
    {
        $this->moCP = $moCP;
    }

    /**
     * @return string
     */
    public function getMoCity()
    {
        return $this->moCity;
    }

    /**
     * @param string $moCity
     */
    public function setMoCity($moCity)
    {
        $this->moCity = $moCity;
    }

    /**
     * @return string
     */
    public function getMoTel()
    {
        return $this->moTel;
    }

    /**
     * @param string $moTel
     */
    public function setMoTel($moTel)
    {
        $this->moTel = $moTel;
    }

    /**
     * @return string
     */
    public function getMoEmail()
    {
        return $this->moEmail;
    }

    /**
     * @param string $moEmail
     */
    public function setMoEmail($moEmail)
    {
        $this->moEmail = $moEmail;
    }

    /**
     * @return \DateTime
     */
    public function getPcRequestDate()
    {
        return $this->pcRequestDate;
    }

    /**
     * @param \DateTime $pcRequestDate
     */
    public function setPcRequestDate($pcRequestDate)
    {
        $this->pcRequestDate = $pcRequestDate;
    }

    /**
     * @return string
     */
    public function getPcReference()
    {
        return $this->pcReference;
    }

    /**
     * @param string $pcReference
     */
    public function setPcReference($pcReference)
    {
        $this->pcReference = $pcReference;
    }

    /**
     * @return \DateTime
     */
    public function getPcDate()
    {
        return $this->pcDate;
    }

    /**
     * @param \DateTime $pcDate
     */
    public function setPcDate($pcDate)
    {
        $this->pcDate = $pcDate;
    }

    /**
     * @return string
     */
    public function getPcNbPhase()
    {
        return $this->pcNbPhase;
    }

    /**
     * @param string $pcNbPhase
     */
    public function setPcNbPhase($pcNbPhase)
    {
        $this->pcNbPhase = $pcNbPhase;
    }

    /**
     * @return string
     */
    public function getPcCurrentPhase()
    {
        return $this->pcCurrentPhase;
    }

    /**
     * @param string $pcCurrentPhase
     */
    public function setPcCurrentPhase($pcCurrentPhase)
    {
        $this->pcCurrentPhase = $pcCurrentPhase;
    }

    /**
     * @return \DateTime
     */
    public function getCalStartDate()
    {
        return $this->calStartDate;
    }

    /**
     * @param \DateTime $calStartDate
     */
    public function setCalStartDate($calStartDate)
    {
        $this->calStartDate = $calStartDate;
    }

    /**
     * @return \DateTime
     */
    public function getCalEndDate()
    {
        return $this->calEndDate;
    }

    /**
     * @param \DateTime $calEndDate
     */
    public function setCalEndDate($calEndDate)
    {
        $this->calEndDate = $calEndDate;
    }

    /**
     * @return string
     */
    public function getDocAuthor()
    {
        return $this->DocAuthor;
    }

    /**
     * @param string $DocAuthor
     */
    public function setDocAuthor($DocAuthor)
    {
        $this->DocAuthor = $DocAuthor;
    }

    /**
     * @return string
     */
    public function getDocAuthorEmail()
    {
        return $this->DocAuthorEmail;
    }

    /**
     * @param string $DocAuthorEmail
     */
    public function setDocAuthorEmail($DocAuthorEmail)
    {
        $this->DocAuthorEmail = $DocAuthorEmail;
    }

    /**
     * @return string
     */
    public function getDocChronoRef()
    {
        return $this->DocChronoRef;
    }

    /**
     * @param string $DocChronoRef
     */
    public function setDocChronoRef($DocChronoRef)
    {
        $this->DocChronoRef = $DocChronoRef;
    }

    /**
     * @return string
     */
    public function getNbMeasure()
    {
        return $this->NbMeasure;
    }

    /**
     * @param string $NbMeasure
     */
    public function setNbMeasure($NbMeasure)
    {
        $this->NbMeasure = $NbMeasure;
    }

    /**
     * @return string
     */
    public function getCompanySpeaker()
    {
        return $this->CompanySpeaker;
    }

    /**
     * @param string $CompanySpeaker
     */
    public function setCompanySpeaker($CompanySpeaker)
    {
        $this->CompanySpeaker = $CompanySpeaker;
    }

    /**
     * @return string
     */
    public function getDelegateMO()
    {
        return $this->delegateMO;
    }

    /**
     * @param string $delegateMO
     */
    public function setDelegateMO($delegateMO)
    {
        $this->delegateMO = $delegateMO;
    }

    /**
     * @return string
     */
    public function getDelegateMOAddress()
    {
        return $this->delegateMOAddress;
    }

    /**
     * @param string $delegateMOAddress
     */
    public function setDelegateMOAddress($delegateMOAddress)
    {
        $this->delegateMOAddress = $delegateMOAddress;
    }

    /**
     * @return string
     */
    public function getMEName()
    {
        return $this->MEName;
    }

    /**
     * @param string $MEName
     */
    public function setMEName($MEName)
    {
        $this->MEName = $MEName;
    }

    /**
     * @return string
     */
    public function getMEAddress()
    {
        return $this->MEAddress;
    }

    /**
     * @param string $MEAddress
     */
    public function setMEAddress($MEAddress)
    {
        $this->MEAddress = $MEAddress;
    }

    /**
     * @return string
     */
    public function getMEMission()
    {
        return $this->MEMission;
    }

    /**
     * @param string $MEMission
     */
    public function setMEMission($MEMission)
    {
        $this->MEMission = $MEMission;
    }

    /**
     * @return string
     */
    public function getOtherMEName()
    {
        return $this->OtherMEName;
    }

    /**
     * @param string $OtherMEName
     */
    public function setOtherMEName($OtherMEName)
    {
        $this->OtherMEName = $OtherMEName;
    }

    /**
     * @return string
     */
    public function getOtherMEMission()
    {
        return $this->OtherMEMission;
    }

    /**
     * @param string $OtherMEMission
     */
    public function setOtherMEMission($OtherMEMission)
    {
        $this->OtherMEMission = $OtherMEMission;
    }

    /**
     * @return string
     */
    public function getBETStructureName()
    {
        return $this->BETStructureName;
    }

    /**
     * @param string $BETStructureName
     */
    public function setBETStructureName($BETStructureName)
    {
        $this->BETStructureName = $BETStructureName;
    }

    /**
     * @return string
     */
    public function getBETStructureMission()
    {
        return $this->BETStructureMission;
    }

    /**
     * @param string $BETStructureMission
     */
    public function setBETStructureMission($BETStructureMission)
    {
        $this->BETStructureMission = $BETStructureMission;
    }

    /**
     * @return string
     */
    public function getBETFluidName()
    {
        return $this->BETFluidName;
    }

    /**
     * @param string $BETFluidName
     */
    public function setBETFluidName($BETFluidName)
    {
        $this->BETFluidName = $BETFluidName;
    }

    /**
     * @return string
     */
    public function getBETFluidMission()
    {
        return $this->BETFluidMission;
    }

    /**
     * @param string $BETFluidMission
     */
    public function setBETFluidMission($BETFluidMission)
    {
        $this->BETFluidMission = $BETFluidMission;
    }

    /**
     * @return string
     */
    public function getBETThermalName()
    {
        return $this->BETThermalName;
    }

    /**
     * @param string $BETThermalName
     */
    public function setBETThermalName($BETThermalName)
    {
        $this->BETThermalName = $BETThermalName;
    }

    /**
     * @return string
     */
    public function getBETThermalMission()
    {
        return $this->BETThermalMission;
    }

    /**
     * @param string $BETThermalMission
     */
    public function setBETThermalMission($BETThermalMission)
    {
        $this->BETThermalMission = $BETThermalMission;
    }

    /**
     * @return string
     */
    public function getBETAudioName()
    {
        return $this->BETAudioName;
    }

    /**
     * @param string $BETAudioName
     */
    public function setBETAudioName($BETAudioName)
    {
        $this->BETAudioName = $BETAudioName;
    }

    /**
     * @return string
     */
    public function getBETAudioMission()
    {
        return $this->BETAudioMission;
    }

    /**
     * @param string $BETAudioMission
     */
    public function setBETAudioMission($BETAudioMission)
    {
        $this->BETAudioMission = $BETAudioMission;
    }

    /**
     * @return string
     */
    public function getOtherBETAMOName()
    {
        return $this->OtherBET_AMOName;
    }

    /**
     * @param string $OtherBET_AMOName
     */
    public function setOtherBETAMOName($OtherBET_AMOName)
    {
        $this->OtherBET_AMOName = $OtherBET_AMOName;
    }

    /**
     * @return string
     */
    public function getOtherBETAMOMission()
    {
        return $this->OtherBET_AMOMission;
    }

    /**
     * @param string $OtherBET_AMOMission
     */
    public function setOtherBETAMOMission($OtherBET_AMOMission)
    {
        $this->OtherBET_AMOMission = $OtherBET_AMOMission;
    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Operation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set caseReference
     *
     * @param string $caseReference
     *
     * @return Operation
     */
    public function setCaseReference($caseReference)
    {
        $this->caseReference = $caseReference;

        return $this;
    }

    /**
     * Get caseReference
     *
     * @return string
     */
    public function getCaseReference()
    {
        return $this->caseReference;
    }

    /**
     * Set reportReference
     *
     * @param string $reportReference
     *
     * @return Operation
     */
    public function setReportReference($reportReference)
    {
        $this->reportReference = $reportReference;

        return $this;
    }

    /**
     * Get reportReference
     *
     * @return string
     */
    public function getReportReference()
    {
        return $this->reportReference;
    }

    /**
     * Set measureCompany
     *
     * @param string $measureCompany
     *
     * @return Operation
     */
    public function setMeasureCompany($measureCompany)
    {
        $this->measureCompany = $measureCompany;

        return $this;
    }

    /**
     * Get measureCompany
     *
     * @return string
     */
    public function getMeasureCompany()
    {
        return $this->measureCompany;
    }

    /**
     * Set measureAuthor
     *
     * @param string $measureAuthor
     *
     * @return Operation
     */
    public function setMeasureAuthor($measureAuthor)
    {
        $this->measureAuthor = $measureAuthor;

        return $this;
    }

    /**
     * Get measureAuthor
     *
     * @return string
     */
    public function getMeasureAuthor()
    {
        return $this->measureAuthor;
    }

    /**
     * Set measureDate
     *
     * @param \DateTime $measureDate
     *
     * @return Operation
     */
    public function setMeasureDate($measureDate)
    {
        $this->measureDate = $measureDate;

        return $this;
    }

    /**
     * Get measureDate
     *
     * @return \DateTime
     */
    public function getMeasureDate()
    {
        return $this->measureDate;
    }

    /**
     * Get XLSM Sheet Date
     * @return \DateTime
     */
    public function getSheetDate()
    {
        return $this->sheetDate;
    }

    /**
     * Set XLSM Sheet date
     * @param \DateTime $sheetDate
     * @return Operation
     */
    public function setSheetDate($sheetDate)
    {
        $this->sheetDate = $sheetDate;
        return $this;
    }

    /**
     * Set info
     *
     * @param string $info
     *
     * @return Operation
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set operationAddress
     *
     * @param string $operationAddress
     *
     * @return Operation
     */
    public function setOperationAddress($operationAddress)
    {
        $this->operationAddress = $operationAddress;

        return $this;
    }

    /**
     * Get operationAddress
     *
     * @return string
     */
    public function getOperationAddress()
    {
        return $this->operationAddress;
    }

    /**
     * Set operationCity
     *
     * @param string $operationCity
     *
     * @return Operation
     */
    public function setOperationCity($operationCity)
    {
        $this->operationCity = $operationCity;

        return $this;
    }

    /**
     * Get operationCity
     *
     * @return string
     */
    public function getOperationCity()
    {
        return $this->operationCity;
    }

    /**
     * Set operationObjective
     *
     * @param string $operationObjective
     *
     * @return Operation
     */
    public function setOperationObjective($operationObjective)
    {
        $this->operationObjective = $operationObjective;

        return $this;
    }

    /**
     * Get operationObjective
     *
     * @return string
     */
    public function getOperationObjective()
    {
        return $this->operationObjective;
    }

    /**
     * Set operationMeasureRef
     *
     * @param string $operationMeasureRef
     *
     * @return Operation
     */
    public function setOperationMeasureRef($operationMeasureRef)
    {
        $this->operationMeasureRef = $operationMeasureRef;

        return $this;
    }

    /**
     * Get operationMeasureRef
     *
     * @return string
     */
    public function getOperationMeasureRef()
    {
        return $this->operationMeasureRef;
    }

    /**
     * Set measureReport
     *
     * @param string $measureReport
     *
     * @return Operation
     */
    public function setMeasureReport($measureReport)
    {
        $this->measureReport = $measureReport;

        return $this;
    }

    /**
     * Get measureReport
     *
     * @return string
     */
    public function getMeasureReport()
    {
        return $this->measureReport;
    }

    /**
     * Set measureCert
     *
     * @param string $measureCert
     *
     * @return Operation
     */
    public function setMeasureCert($measureCert)
    {
        $this->measureCert = $measureCert;

        return $this;
    }

    /**
     * Get measureCert
     *
     * @return string
     */
    public function getMeasureCert()
    {
        return $this->measureCert;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Operation
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Operation
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Operation
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param mixed $document
     */
    public function setDocument($document)
    {
        $this->document = $document;
    }

    /**
     * @return mixed
     */
    public function getAerien()
    {
        return $this->aerien;
    }
    /**
     * @param Aerien $aerien
     * @return Aerien
     */
    public function addAerien(Aerien $aerien)
    {
        $this->aerien[] = $aerien;

        return $aerien;
    }

    /**
     * @param Foreigner $foreigner
     * @return Foreigner
     */
    public function addForeigner(Foreigner $foreigner)
    {
        $this->foreigner[] = $foreigner;

        return $foreigner;
    }

    /**
     * @return mixed
     */
    public function getShock()
    {
        return $this->shock;
    }

    /**
     * @param Shock $shock
     * @return Shock
     */
    public function addShock(Shock $shock)
    {
        $this->shock[] = $shock;

        return $shock;
    }

    /**
     * @return mixed
     */
    public function getEquipement()
    {
        return $this->equipement;
    }

    /**
     * @param Equipement $equipement
     * @return Equipement
     */
    public function addEquipement(Equipement $equipement)
    {
        $this->equipement[] = $equipement;

        return $equipement;
    }

    /**
     * @return mixed
     */
    public function getAae()
    {
        return $this->aae;
    }

    /**
     * @param Aae $aae
     * @return Aae
     */
    public function addAae(Aae $aae)
    {
        $this->aae[] = $aae;

        return $aae;
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param Results $results
     * @return Results
     */
    public function addResults(Results $results)
    {
        $this->results[] = $results;

        return $results;
    }

    /**
     * @return int
     */
    public function getSheetCount()
    {
        return $this->SheetCount;
    }

    /**
     * @param integer $SheetCount
     */
    public function setSheetCount($SheetCount)
    {
        $this->SheetCount = $SheetCount;
    }

    /**
     * @param array $sheetNames
     */
    public function setSheetNames($sheetNames)
    {
        $this->sheetNames = $sheetNames;
    }
    /**
     * @return array
     */
    public function getSheetNames()
    {
        return $this->sheetNames;
    }

    /**
     * @return mixed
     */
    public function getForeigner()
    {
        return $this->foreigner;
    }

    /**
     * @param mixed $foreigner
     */
    public function setForeigner($foreigner)
    {
        $this->foreigner = $foreigner;
    }

    /**
     * Read Operation from file
     *
     * @param IOFactory
     */
    public function readOperationData($xlsReader)
    {
        $xlsReader->setActiveSheetIndexByName(self::sheetName);

        $workSheet = $xlsReader->getActiveSheet();

        $this->setMeasureCompany($workSheet->getCell("D6")->getCalculatedValue());
        $this->setMeasureAuthor($workSheet->getCell("D7")->getCalculatedValue());

        // Date input parse
        $datestr = $workSheet->getCell("D8")->getFormattedValue();
        $datestr = $this->checkDate($datestr);
        $this->setMeasureDate($datestr);
        //$this->setMeasureDate($workSheet->getCell("D8")->getFormattedValue());

        $datestr = $workSheet->getCell("D9")->getFormattedValue();
        $datestr = $this->checkDate($datestr);
        $this->setSheetDate($datestr);
        //$this->setSheetDate($workSheet->getCell("D9")->getFormattedValue());
        // /Date input parse

        $this->setName($workSheet->getCell("D10")->getCalculatedValue());
        $this->setInfo("");

        $this->setOperationAddress($workSheet->getCell("D12")->getCalculatedValue());
        $citystr = $workSheet->getCell("D13")->getCalculatedValue();
        preg_match('/(?P<city>\w+) (?P<cp>\d+)/', $citystr, $matches);
        if (count($matches) == 0)
            preg_match('/(?P<cp>\d+) (?P<city>\w+)/', $citystr, $matches);
        if (count($matches) != 0) {
            $this->setOperationCity($matches['city']);
            $this->setOperationCP($matches['cp']);
        } else
            $this->setOperationCity($citystr);
        $this->setOperationObjective($workSheet->getCell("D15")->getCalculatedValue());
        $this->setOperationMeasureRef($workSheet->getCell("D16")->getCalculatedValue());
    }

    /**
     * @param $datestr
     * @return null|DateTime
     */
     private function checkDate($datestr){
         if(strlen($datestr)==0) return null;
         if(strtotime($datestr)!==false){
             $date = new DateTime();
             $date->setTimestamp(strtotime($datestr));
         } else {
             $dateArray = explode(' ', $datestr);
             if(count($dateArray) > 1) {
                 for($i=0;$i<count($dateArray);$i++){
                     $datetest = explode('/',$dateArray[$i]);
                     if(count($datetest) == 3){
                         return $date = DateTime::createFromFormat('d/m/Y',$dateArray[$i]);
                     }
                 }
                 return null;
             } else { // plain direct date from XLS Sheet UK Format
                 $date = DateTime::createFromFormat('m/d/Y', $datestr);
             }
         }
         return $date;
     }

    /**
     * @return mixed
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * @param mixed $pictures
     */
    public function setPictures($pictures)
    {
        $this->pictures = $pictures;
    }

    /**
     * @return mixed
     */
    public function getAgency()
    {
        return $this->agency;
    }

    /**
     * @param mixed $agency
     */
    public function setAgency($agency)
    {
        $this->agency = $agency;
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

    /**
     * @return mixed
     */
    public function getNoiseSource()
    {
        return $this->noise_source;
    }

    /**
     * @param mixed $noiseSource
     */
    public function setNoiseSource($noiseSource)
    {
        $this->noise_source = $noiseSource;
    }

    /**
     * @return mixed
     */
    public function getShockmachine()
    {
        return $this->shockmachine;
    }

    /**
     * @param mixed $shockmachine
     */
    public function setShockmachine($shockmachine)
    {
        $this->shockmachine = $shockmachine;
    }

    /**
     * @return mixed
     */
    public function getReverbAccessory()
    {
        return $this->reverb_accessory;
    }

    /**
     * @param mixed $reverb_accessory
     */
    public function setReverbAccessory($reverb_accessory)
    {
        $this->reverb_accessory = $reverb_accessory;
    }

    /**
     * @return mixed
     */
    public function getSoftware()
    {
        return $this->software;
    }

    /**
     * @param mixed $software
     */
    public function setSoftware($software)
    {
        $this->software = $software;
    }


}

