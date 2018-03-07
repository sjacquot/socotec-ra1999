<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\IOFactory;



/**
 * Class Operation
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

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="case_referance", type="string", length=255)
     */
    private $caseReferance;

    /**
     * @var string
     *
     * @ORM\Column(name="report_reference", type="string", length=255, nullable=true)
     */
    private $reportReference;
    /**
     * @var string
     *
     * @ORM\Column(name="sheet_date", type="string", length=255, nullable=true)
     */
    private $sheetDate;

    /**
     * @var string
     *
     * @ORM\Column(name="measure_company", type="string", length=255, nullable=true)
     */
    private $measureCompany;

    /**
     * @var string
     *
     * @ORM\Column(name="measure_author", type="string", length=255, nullable=true)
     */
    private $measureAuthor;

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
     * @ORM\JoinColumn(name="document_id", referencedColumnName="id")
     */
    private $document;

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
     * Operation constructor.
     */
    public function __construct()
    {
        $this->createdAt= new \DateTime();
        $this->updatedAt= new \DateTime();
        $this->document = new ArrayCollection();
        $this->shock = new ArrayCollection();
        $this->aerien = new ArrayCollection();
        $this->foreigner = new ArrayCollection();
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
     * Set caseReferance
     *
     * @param string $caseReferance
     *
     * @return Operation
     */
    public function setCaseReferance($caseReferance)
    {
        $this->caseReferance = $caseReferance;

        return $this;
    }

    /**
     * Get caseReferance
     *
     * @return string
     */
    public function getCaseReferance()
    {
        return $this->caseReferance;
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
     * @return Aerien
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
     * @return string
     */
    public function getSheetDate()
    {
        return $this->sheetDate;
    }

    /**
     * @param string $sheetDate
     */
    public function setSheetDate($sheetDate)
    {
        $this->sheetDate = $sheetDate;
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
    public function readOperationData($xlsReader){
        $xlsReader->setActiveSheetIndexByName(self::sheetName);

        $workSheet = $xlsReader->getActiveSheet();

        $this->setMeasureCompany($workSheet->getCell("D6")->getCalculatedValue());
        $this->setMeasureAuthor($workSheet->getCell("D7")->getCalculatedValue());
        $this->setMeasureDate($workSheet->getCell("D8")->getCalculatedValue());
        $this->setSheetDate($workSheet->getCell("D9")->getCalculatedValue());
        $this->setName($workSheet->getCell("D10")->getCalculatedValue());
        $this->setInfo($workSheet->getCell("D11")->getCalculatedValue());
        $address = array();
        $address[0] = $workSheet->getCell("D11")->getCalculatedValue();
        $address[1] = $workSheet->getCell("D12")->getCalculatedValue();
        $this->setOperationAddress(json_encode($address));
        $this->setOperationCity($workSheet->getCell("D13")->getCalculatedValue());
        $this->setOperationObjective($workSheet->getCell("D15")->getCalculatedValue());
        $this->setOperationMeasureRef($workSheet->getCell("D16")->getCalculatedValue());
    }

}

