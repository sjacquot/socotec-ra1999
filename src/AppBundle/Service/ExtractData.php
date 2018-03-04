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
     * FileUploader constructor.
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
        $document = $operation->getDocument();
        $this->extractDataFromDocument($document, $operation);
    }

    /**
     * @param Document $document
     * @param Operation $operation
     */
    private function extractDataFromDocument(Document $document, Operation $operation){
        //TODO: traitemnet des datas

        $spreadSheet = $this->container->get('app.read_xls_sheetfile')->readXLSSheetFile($operation);

        $extractResult = new ExtractResults();


        $dataAAE = [];
        $dataResult = $extractResult->readResults($spreadSheet);
        $dataEquipement = [];
        $dataShock = [];
        $dataForeigner = [];
        $dataAerien = [];

        //loop to create the aae row
        foreach ($dataAAE as $data){
            //fucniton to create a new AAE
            $this->UploadAAE($operation, $data);
        }

        //loop to create the equipement row
        foreach ($dataEquipement as $data){
            //fucniton to create a new Equipement
            $this->UploadEquipement($operation, $data);
        }

        //loop to create the shock row
        foreach ($dataShock as $data){
            //fucniton to create a new shock
            $this->UploadShock($operation, $data);
        }

        //loop to create the foreigner row
        foreach ($dataForeigner as $data){
            //fucniton to create a new foreigner
            $this->UploadForeigner($operation, $data);
        }

        //loop to create the aerien row
        foreach ($dataAerien as $data){
            //fucniton to create a new aerien
            $this->UploadAerien($operation, $data);
        }

        $this->entityManager->flush();

    }

    /**
     * Create or upload the AAE(s) for an opeartion
     *
     * @param Operation $operation
     * @param $data
     * @return Aae
     */
    private function UploadAAE(Operation $operation, $data){

        $aae = $this->entityManager->getRepository(Aae::class)->findOneByMeasureNumberAndOperation($operation, $data['measure_number']);

        if(is_null($aae)){
            // here it's if the aae doesn't exist already it created it and set the basic info that already uptodate un existing aae
            $aae = new Aae();
            $aae->setMeasureNumber($data['measure_number']);
            $aae->setOperation($operation);
        }
        $aae->setAaeCalculation($data['aae_calculation']);
        //TODO: other data of AAE

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

        $equipement = $this->entityManager->getRepository(Equipement::class)->findOneByMeasureNumberAndOperation($operation, $data['measure_number'], $data['equipement_type']);

        if(is_null($equipement)){
            // here it's if the Equipement doesn't exist already it created it and set the basic info that already uptodate un existing Equipement
            $equipement = new Equipement();
            $equipement->setMeasureNumber($data['measure_number']);
            $equipement->setOperation($operation);
            $equipement->setEquipementType($data['equipement_type']);
        }
        $equipement->setCommentLnATobjectifQualitel($data['equipement_type']);
        //TODO: other data of equipement

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
        $aerien->setDoorNumber($data['door_number']);
        //TODO: other data of aerien

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
        $shock->setFlooringNature($data['flooring_nature']);
        //TODO: other data of shock

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
        $foreigner->setBoilerSuctionCup($data['boiler_suction_cup']);
        //TODO: other data of foreigner

        $this->entityManager->persist($foreigner);

        return $foreigner;
    }
}