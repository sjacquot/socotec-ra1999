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
     * @var
     */
    private $pathCharts;
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
        $this->pathCharts = $this->container->getParameter("path_document")."/charts/";
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

        $SheetNames = $spreadSheet->getSheetNames();

        $extractResult = new ExtractResults();
        $dataResult = $extractResult->readResults($spreadSheet);

        if($dataResult){
            $this->UploadResults($operation, $dataResult);
        }

        $matches  = preg_grep ('/^A\((\d+)\)/i', $SheetNames);
        foreach ($matches as $sheet){
                // BAI
            $extractBAI = new ExtractBAI();
            $extractBAI->extractBAI($spreadSheet, $sheet,$this->pathCharts);
            if(!is_null($extractBAI->idOfSheet)){
                $AerienEntity = $this->UploadAerien($operation, $extractBAI);
                $operation->addAerien($AerienEntity);
            }
        }
        $matches  = preg_grep ('/^F\((\d+)\)/i', $SheetNames);
        foreach ($matches as $sheet){
            // BAE
            $extractBAE = new ExtractBAE();
            $extractBAE->extractBAE($spreadSheet, $sheet,$this->pathCharts);
            if(!is_null($extractBAE->idOfSheet)){
                $ForeignEntity = $this->UploadForeigner($operation, $extractBAE);
                $operation->addForeigner($ForeignEntity);
            }
        }

        $matches  = preg_grep ('/^C\((\d+)\)/i', $SheetNames);
        foreach ($matches as $sheet){
            // BAE
            $extractBC = new ExtractBC();
            $extractBC->extractBC($spreadSheet, $sheet,$this->pathCharts);
            if(!is_null($extractBC->idOfSheet)){
                $ShockEntity = $this->UploadShock($operation, $extractBC);
                $operation->addShock($ShockEntity);
            }
        }

        $Equipement = new ExtractEquipments();
        if($Equipement->readEquipment($spreadSheet)){
            $this->UploadEquipement($operation, $Equipement);
        }

        $AAE = new ExtractAAE();
        if($AAE->readAAE($spreadSheet)) {
            $this->UploadAAE($operation, $AAE);
        }
    }

    /**
     * Create or upload the AAE(s) for an opeartion
     *
     * @param Operation $operation
     * @param $data
     * @return Aae
     */
    private function UploadAAE(Operation $operation, ExtractAAE $data){

        $aae = $this->entityManager->getRepository(Aae::class)->findOneByOperation($operation);

        if(is_null($aae)){
            // here it's if the aae doesn't exist already it created it and set the basic info that already uptodate un existing aae
            $aae = new Aae();
            $aae->setOperation($operation);
        }
        $aae->setData($data->data);
        $aae->setComments($data->comments);

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
    private function UploadEquipement(Operation $operation, ExtractEquipments $data){

        $equipement = $this->entityManager->getRepository(Equipement::class)->findOneByOperation($operation);

        if(is_null($equipement)){
            // here it's if the Equipement doesn't exist already it created it and set the basic info that already uptodate un existing Equipement
            $equipement = new Equipement();
            $equipement->setOperation($operation);
            }
        $equipement->setType1($data->type1);
        $equipement->setType1AmbiantNoise($data->type1AmbiantNoise);
        $equipement->setType1Comments($data->type1Comments);
        $equipement->setType2($data->type2);
        $equipement->setType2AmbiantNoise($data->type2AmbiantNoise);
        $equipement->setType2Comments($data->type2Comments);

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

        $aerien = $this->entityManager->getRepository(Aerien::class)->findOneByIdOfSheetAndOperation($operation, $data->idOfSheet);

        if(is_null($aerien)){
            // here it's if the aerien doesn't exist already it created it and set the basic info that already uptodate un existing aerien
            $aerien = new Aerien();
            $aerien->setIdOfSheet($data->idOfSheet);
            $aerien->setOperation($operation);
        }
        $aerien->setLocalEmissionName($data->localEmissionName);
        $aerien->setLocalEmissionVolume($data->localEmissionVolume);
        $aerien->setLocalReceptionName($data->localReceptionName);
        $aerien->setLocalReceptionVolume($data->localReceptionVolume);

        $aerien->setSeparatingNatureWall($data->separatingNatureWall);
        $aerien->setSeparatingDubbingNatureWall($data->separatingDubbingNatureWall);
        $aerien->setSeparatingThicknessWall($data->separatingThicknessWall);

        $aerien->setDoorNumber($data->doorNumber);

        $aerien->setExtractionMouth($data->extractionMouth);

        $aerien->setFacadeDoublingNature($data->facadeDoublingNature);
        $aerien->setFacadeDoublingThickness($data->facadeDoublingThickness);

        $aerien->setTransmissionType($data->transmissionType);

        $aerien->setLocalReceptionSurface($data->localReceptionSurface);
        $aerien->setComment($data->comment);

        $aerien->setWeightedStandardizedAcousticIsolation($data->weightedStandardizedAcousticIsolation);
        $aerien->setObjectifRa1999($data->objectifRa1999);

        $aerien->setTestResult($data->testResult);

        $aerien->setPassRa1999($data->PassRa1999);
        $aerien->setData($data->data);
        $aerien->setFileChart($data->fileChart);

        $aerien->setMeasureDate($data->MeasureDate);
        $aerien->setMeasureTTX($data->MeasureTTX);

        $this->entityManager->persist($aerien);

        return $aerien;
    }


    /**
     * Create or upload the foreigner(s) for an opeartion
     *
     * @param Operation $operation
     * @param $data
     * @return Foreigner
     */
    private function UploadForeigner(Operation $operation, $data){

        $foreigner = $this->entityManager->getRepository(Foreigner::class)->findOneByIdOfSheetAndOperation($operation, $data->idOfSheet);

        if(is_null($foreigner)){
            // here it's if the aerien doesn't exist already it created it and set the basic info that already uptodate un existing aerien
            $foreigner = new Foreigner();
            $foreigner->setIdOfSheet($data->idOfSheet);
            $foreigner->setOperation($operation);
        }
        $foreigner->setLocalEmissionName($data->localEmissionName);
        $foreigner->setLocalEmissionType($data->localEmissionType);
        $foreigner->setLocalReceptionName($data->localReceptionName);
        $foreigner->setLocalReceptionVolume($data->localReceptionVolume);

        $foreigner->setSeparatingNatureWall($data->separatingNatureWall);
        $foreigner->setSeparatingThicknessWall($data->separatingThicknessWall);
        $foreigner->setSeparatingDubbingNatureWall($data->separatingDubbingNatureWall);
        $foreigner->setSeparatingDubbingThicknessWall($data->separatingDubbingThicknessWall);

        $foreigner->setCarpentryMaterial($data->carpentryMaterial);
        $foreigner->setCarpentryOpening($data->carpentryOpening);
        $foreigner->setCarpentryOpeningType($data->carpentryOpeningType);
//        $data->carpentryOpeningNumber;
        $foreigner->setRollingShutterBox($data->rollingShutterBox);

        $foreigner->setVmcAirIntakeNumber($data->vmcAirIntakeNumber);
        $foreigner->setVmcAirIntakePosition($data->vmcAirIntakePosition);
        $foreigner->setVmcAirIntakeType($data->vmcAirIntakeType);

        $foreigner->setBoilerSuctionCup($data->boilerSuctionCup);

        $foreigner->setComment($data->comment);

        $foreigner->setWeightedStandardizedAcousticIsolation($data->weightedStandardizedAcousticIsolation);
        $foreigner->setObjectifRa1999($data->objectifRa1999);
        $foreigner->setPassRa1999($data->PassRa1999);
        $foreigner->setMeasureDate($data->MeasureDate);
        $foreigner->setMeasureTTX($data->MeasureTTX);

        $foreigner->setTestResult($data->testResult);

        $foreigner->setData($data->data);

        $foreigner->setFileChart($data->fileChart);
        $this->entityManager->persist($foreigner);

        return $foreigner;
    }

    /**
     * Create or upload the shock(s) for an opeartion
     *
     * @param Operation $operation
     * @param $data
     * @return Shock
     */
    private function UploadShock(Operation $operation, $data){

       $shock = $this->entityManager->getRepository(Shock::class)->findOneByIdOfSheetAndOperation($operation, $data->idOfSheet);

        if(is_null($shock)){
            // here it's if the aerien doesn't exist already it created it and set the basic info that already uptodate un existing aerien
            $shock = new Shock();
            $shock->setIdOfSheet($data->idOfSheet);
            $shock->setOperation($operation);
        }
        $shock->setLocalEmissionName($data->localEmissionName);
        $shock->setLocalEmissionVolume($data->localEmissionVolume);

        $shock->setLocalReceptionName($data->localReceptionName);
        $shock->setLocalReceptionVolume($data->localReceptionVolume);

        $shock->setSeparatingNatureFloor($data->separatingNatureFloor);
        $shock->setSeparatingThicknessFloor($data->separatingThicknessFloor);

        $shock->setFlooringNature($data->flooringNature);
        $shock->setFlooringAcousticTreatment($data->flooringAcousticTreatment);

        $shock->setTransmissionType($data->transmissionType);

        $shock->setNbShockMachines($data->nbShockMachines);

        $shock->setComment($data->comment);

        $shock->setWeightedStandardizedShockNoise($data->weightedStandardizedShockNoise);
        $shock->setObjectifRa1999($data->objectifRa1999);
        $shock->setPassRa1999($data->PassRa1999);

        $shock->setTestResult($data->testResult);
        $shock->setData($data->data);
        $shock->setFileChart($data->fileChart);

        $shock->setMeasureDate($data->MeasureDate);
        $shock->setMeasureTTX($data->MeasureTTX);

        $this->entityManager->persist($shock);

        return $shock;
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