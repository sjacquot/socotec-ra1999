<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * \class Document
 *  @ingroup Office
 **
 * @ORM\Table(name="document")
 * @ORM\Entity
 */
class Document
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
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $path_doc_xml;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $path_report;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $path_certificate;

    /**
     *
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     *
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * Document constructor.
     */
    public function __construct() {
        $this->created = new \DateTime();
    }

    /**
     * @return string
     */
    public function __toString(){
        return (string) $this->getName();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     *
     */
    public function setUpdated()
    {
        $this->updated = new \DateTime();
    }

    /**
     * @return string
     */
    public function getPathDocXml()
    {
        return $this->path_doc_xml;
    }

    /**
     * @param string $path_doc_xml
     */
    public function setPathDocXml($path_doc_xml)
    {
        $this->path_doc_xml = $path_doc_xml;
    }

    /**
     * @return string
     */
    public function getPathReport()
    {
        return $this->path_report;
    }

    /**
     * @param string $path_report
     */
    public function setPathReport($path_report)
    {
        $this->path_report = $path_report;
    }

    /**
     * @return string
     */
    public function getPathCertificate()
    {
        return $this->path_certificate;
    }

    /**
     * @param string $path_certificate
     */
    public function setPathCertificate($path_certificate)
    {
        $this->path_certificate = $path_certificate;
    }
    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }
}

