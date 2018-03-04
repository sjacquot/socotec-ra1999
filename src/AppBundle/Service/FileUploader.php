<?php
/**
 * Created by PhpStorm.
 * User: paolocastro
 * Date: 19/06/2017
 * Time: 16:03
 */

namespace AppBundle\Service;

use AppBundle\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUploader
 * @package AppBundle\Service
 */
class FileUploader
{
    /**
     * @var
     */
    private $targetDir;
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * FileUploader constructor.
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager)
    {
        $this->targetDir = $container->getParameter('path_document');
        $this->entityManager = $entityManager;
    }

    /**
     * @param UploadedFile $file
     * @return Document
     */
    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->getClientOriginalExtension();

        $file->move($this->targetDir, $fileName);

        $document = new Document();
        $document->setName($file->getClientOriginalName());
        $document->setPathDocXml($fileName);
        $document->setUpdated();
        $this->entityManager->persist($document);
        $this->entityManager->flush();

        return $document;
    }

    /**
     * @return mixed
     */
    public function getTargetDir()
    {
        return $this->targetDir;
    }
}