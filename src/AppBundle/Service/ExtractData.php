<?php
/**
 * Created by PhpStorm.
 * User: paolocastro
 * Date: 04/03/2018
 * Time: 13:37
 */

namespace AppBundle\Service;


use AppBundle\Entity\Aae;
use AppBundle\Entity\Aerien;
use AppBundle\Entity\Document;
use AppBundle\Entity\Equipement;
use AppBundle\Entity\Foreigner;
use AppBundle\Entity\Operation;
use AppBundle\Entity\Results;
use AppBundle\Entity\Shock;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ExtractData
 * @package AppBundle\Service
 */
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
     * ExtractData constructor.
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Operation $operation
     */
    public function extractData(Operation $operation){
        $spreadSheet = $this->container->get('app.read_xls_sheetfile')->readXLSSheetFile($operation);
        if($spreadSheet){
            /**
             *  Read all data to fill Operation Entity
             */
            $operation->readOperationData($spreadSheet);
            /**
             * Extract other file data
             */
            $this->extractDataFromDocument($operation, $spreadSheet);

            $this->entityManager->persist($operation);
            $this->entityManager->flush();
        }
    }

    /**
     * @param Operation $operation
     * @param $spreadSheet
     */
    private function extractDataFromDocument(Operation $operation, $spreadSheet){

        $extractResult = new ExtractResults();
        $dataResult = $extractResult->readResults($spreadSheet);

        $dataAerien = [];

        $dataForeigner = [];

        $dataShock = [];

        $dataEquipement = [];

        $dataAAE = [];

        if($dataResult){
            $this->UploadResults($operation, $dataResult);
        }

        if($dataAerien){
            $this->UploadAerien($operation, $dataAerien);
        }

        if($dataForeigner){
            $this->UploadForeigner($operation, $dataForeigner);
        }

        if($dataShock){
            $this->UploadShock($operation, $dataShock);
        }

        if($dataEquipement){
            $this->UploadEquipement($operation, $dataEquipement);
        }

        if($dataAAE){
            $this->UploadAAE($operation, $dataAAE);
        }
    }

    /**
     * Create or upload the AAE(s) for an opeartion
     *
     * @param Operation $operation
     * @param $data
     * @return Aae
     */
    private function UploadAAE(Operation $operation, $data){

        //$aae = $this->entityManager->getRepository(Aae::class)->findOneByMeasureNumberAndOperation($operation, $data['measure_number']);
        $aae = $this->entityManager->getRepository(Aae::class)->findOneByOperation($operation);

        if(is_null($aae)){
            // here it's if the aae doesn't exist already it created it and set the basic info that already uptodate un existing aae
            $aae = new Aae();
            //$aae->setMeasureNumber($data['measure_number']);
            $aae->setOperation($operation);
        }
        //$aae->setAaeCalculation($data['aae_calculation']);
        $aae->setData($data);

        $this->entityManager->persist($aae);

        return $aae;
    }

    /**
     * Create or upload the Equipement(s) for an opeartion
     *
     * @param Operation $operation
     * @param $data
     * @return Equipement
     */
    private function UploadEquipement(Operation $operation, $data){

        //$equipement = $this->entityManager->getRepository(Equipement::class)->findOneByMeasureNumberAndOperation($operation, $data['measure_number'], $data['equipement_type']);
        $equipement = $this->entityManager->getRepository(Equipement::class)->findOneByOperation($operation);

        if(is_null($equipement)){
            // here it's if the Equipement doesn't exist already it created it and set the basic info that already uptodate un existing Equipement
            $equipement = new Equipement();
            //$equipement->setMeasureNumber($data['measure_number']);
            $equipement->setOperation($operation);
            //$equipement->setEquipementType($data['equipement_type']);
        }
//        $equipement->setCommentLnATobjectifQualitel($data['equipement_type']);
        $equipement->setData($data);

        $this->entityManager->persist($equipement);

        return $equipement;
    }

    /**
     * Create or upload the Aerien(s) for an opeartion
     *
     * @param Operation $operation
     * @param $data
     * @return Aerien
     */
    private function UploadAerien(Operation $operation, $data){

        $aerien = $this->entityManager->getRepository(Aerien::class)->findOneByIdOfSheetAndOperation($operation, $data['idOfSheet']);

        if(is_null($aerien)){
            // here it's if the aerien doesn't exist already it created it and set the basic info that already uptodate un existing aerien
            $aerien = new Aerien();
            $aerien->setIdOfSheet($data['idOfSheet']);
            $aerien->setOperation($operation);
        }
        $aerien->setData($data['data']);

        $this->entityManager->persist($aerien);

        return $aerien;
    }

    /**
     * Create or upload the shock(s) for an opeartion
     *
     * @param Operation $operation
     * @param $data
     * @return Shock
     */
    private function UploadShock(Operation $operation, $data){

        $shock = $this->entityManager->getRepository(Shock::class)->findOneByIdOfSheetAndOperation($operation, $data['idOfSheet']);

        if(is_null($shock)){
            // here it's if the aerien doesn't exist already it created it and set the basic info that already uptodate un existing aerien
            $shock = new Shock();
            $shock->setIdOfSheet($data['idOfSheet']);
            $shock->setOperation($operation);
        }
        $shock->setData($data['data']);

        $this->entityManager->persist($shock);

        return $shock;
    }

    /**
     * Create or upload the foreigner(s) for an opeartion
     *
     * @param Operation $operation
     * @param $data
     * @return Shock
     */
    private function UploadForeigner(Operation $operation, $data){

        $foreigner = $this->entityManager->getRepository(Foreigner::class)->findOneByIdOfSheetAndOperation($operation, $data['idOfSheet']);

        if(is_null($foreigner)){
            // here it's if the aerien doesn't exist already it created it and set the basic info that already uptodate un existing aerien
            $foreigner = new Foreigner();
            $foreigner->setIdOfSheet($data['idOfSheet']);
            $foreigner->setOperation($operation);
        }
        $foreigner->setData($data['data']);

        $this->entityManager->persist($foreigner);

        return $foreigner;
    }

    /**
     * Create or upload the foreigner(s) for an opeartion
     *
     * @param Operation $operation
     * @param $data
     * @return Results
     */
    private function UploadResults(Operation $operation, $data){

        $results = $this->entityManager->getRepository(Results::class)->findOneByOperation($operation);

        if(is_null($results)){
            // here it's if the aerien doesn't exist already it created it and set the basic info that already uptodate un existing aerien
            $results = new Results();
            $results->setOperation($operation);
        }
        $results->setData($data);

        $this->entityManager->persist($results);

        return $results;
    }
}