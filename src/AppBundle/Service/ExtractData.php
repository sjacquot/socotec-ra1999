<?php
/**
 * Created by PhpStorm.
 * User: paolocastro
 * Date: 04/03/2018
 * Time: 13:37
 */

namespace AppBundle\Service;


use AppBundle\Entity\Aae;
use AppBundle\Entity\Document;
use AppBundle\Entity\Operation;
use AppBundle\Entity\Results;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ExtractData
{
    /**
     * @var
     */
    private $container;
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
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    public function extractData(Operation $operation){
        $allDocument = $operation->getDocument();
        foreach ($allDocument as $document){
            $this->extractDataFromDocuement($document);
        }
    }

    private function extractDataFromDocuement(Document $document, Operation $operation){
        $path = $document->getPathDocXml();
        //TODO: traitemnet de AAE
        $dataAAE = [];

        //loop to create the aae row
        foreach ($dataAAE as $data){
            //fucniton to create a new AAE
            $aae = $this->UploadAAE($data);
            //add aae to the operation
            $operation->addAae($aae);
        }
        $this->entityManager->persist($operation);


        $this->entityManager->flush();

    }

    private function UploadAAE($data){
        $aae = new Aae();
        $aae->setMeasureNumber($data['mesure_number']);
        //TODO: ather data of AAE
        $this->entityManager->persist($aae);

        return $aae;
    }



}