<?php
/**
 * Created by PhpStorm.
 * User: paolocastro
 * Date: 19/06/2017
 * Time: 16:03
 */

namespace AppBundle\Service;

use AppBundle\Entity\Document;
use AppBundle\Entity\Operation;
use AppBundle\Entity\Pictures;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class PictureUploader
 * @package AppBundle\Service
 */
class PictureUploader
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
        $this->targetDir = $container->getParameter('path_picture');
        $this->entityManager = $entityManager;
    }

    /**
     * @param UploadedFile $file
     * @param Operation $operation
     * @return Pictures
     */
    public function upload(UploadedFile $file, Operation $operation)
    {
        $fileName = md5(uniqid()).'.'.$file->getClientOriginalExtension();

        $file->move($this->targetDir, $fileName);

        $picture = new Pictures();
        $picture->setName($file->getClientOriginalName());
        $picture->setPath($fileName);
        $picture->setOperation($operation);
        $picture->setUpdated();
        $this->entityManager->persist($picture);
        $this->entityManager->flush();

        return $picture;
    }

    /**
     * @return mixed
     */
    public function getTargetDir()
    {
        return $this->targetDir;
    }
}