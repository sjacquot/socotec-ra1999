<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Deal
 *
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
    private $path_doc_word;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Operation", mappedBy="document")
     */
    private $operation;

    /**
     * Document constructor.
     */
    public function __construct() {
        $this->operation = new ArrayCollection();
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
    public function getPathDocWord()
    {
        return $this->path_doc_word;
    }

    /**
     * @param string $path_doc_word
     */
    public function setPathDocWord($path_doc_word)
    {
        $this->path_doc_word = $path_doc_word;
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

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $operation
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
    }
}

