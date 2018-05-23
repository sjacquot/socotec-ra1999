<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 05/03/2018
 * Time: 09:38
 */

namespace AppBundle\Service;

use AppBundle\Entity\Aerien;
use AppBundle\Entity\Foreigner;
use AppBundle\Entity\NoiseSource;
use AppBundle\Entity\Operation;
use AppBundle\Entity\Pictures;
use AppBundle\Entity\ReverbAccessory;
use AppBundle\Entity\Shock;
use AppBundle\Entity\Equipement;
use AppBundle\Entity\Aae;

use AppBundle\Entity\Shockmachine;
use AppBundle\Entity\Software;
use AppBundle\Entity\Sonometer;
use PhpOffice\PhpWord\TemplateProcessor;


/**
 * Class GenerateReport
 * @package AppBundle\Service
 */
class GenerateReport extends WordGenerator
{
    /**
     * @var
     */
    private $ressourcepath;

    /**
     * @param Operation $operation
     * @return string
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function generateReport(Operation $operation)
    {
        $templateFile = $this->container->getParameter('path_template_report');
        $templateFile = realpath($templateFile);

        $this->ressourcepath = dirname($templateFile);
        $templateProcessor = new TemplateProcessor($templateFile);
        // Data from Operation
        $this->fillTplOperation($templateProcessor,$operation);
        // Data from Results
        $this->fillTplResuls($templateProcessor,$operation->getResults()->getData());
        $templateProcessor->setValue('COUNTMEASURE',$this->countMeasure);
        $AerienArray = $operation->getAerien();
        $nbclone = count($AerienArray);
        if($nbclone > 0){
            $templateProcessor->cloneBlock("BLOCK_A",$nbclone);
            $index = 1;
            foreach ($AerienArray->toArray() as $Aerial){
                $this->tplGenerateA($templateProcessor,$Aerial,$index);
                $index++;
            }
        } else {
            $templateProcessor->deleteBlock("BLOCK_A");
        }
        $ForeignerArray = $operation->getForeigner();
        $nbclone = count($ForeignerArray);
        if($nbclone > 0) {
            $templateProcessor->cloneBlock("BLOCK_F", $nbclone);
            $index = 1;
            foreach ($ForeignerArray->toArray() as $Facade){
                $this->tplGenerateF($templateProcessor,$Facade,$index);
                $index++;
            }
        } else {
            $templateProcessor->deleteBlock("BLOCK_F");
        }
        $ShockArray = $operation->getShock();
        $nbclone = count($ShockArray);
        if($nbclone > 0) {
            $templateProcessor->cloneBlock("BLOCK_C", $nbclone);
            $index = 1;
            foreach ($ShockArray->toArray() as $Choc){
               $this->tplGenerateC($templateProcessor,$Choc,$index);
               if ($index == $nbclone) {
                   $templateProcessor->deleteBlock("SP#".$index);
               } else{
                    $templateProcessor->cloneBlock("SP#".$index,1);
               }
                $index++;
            }
        } else {
            $templateProcessor->deleteBlock("BLOCK_C");
        }

        $equipmentArray = $operation->getEquipement();
        if(count($equipmentArray) > 0){
            // Un seul... mais bon au cas où... ou a revoir / factoriser
            foreach ($equipmentArray->toArray() as $equipment){
                $this->tplGenerateEquipment($templateProcessor,$equipment);
            }
        } else{
            $this->fillArrayOfValues($templateProcessor,
                ['EQ1B','EQ1C','EQ1D','EQ1E','EQ1F','EQ1G','EQ1H','EQ1I','EQ1J','EQ1K','EQ1M','EQ1N','EQ1O','EQ1P','EQ1Q','EQ1V','EQ1W','NOTEQ1W'],
                ["NA"]);
            $this->fillArrayOfValues($templateProcessor,
                ['EQ2B','EQ2C','EQ2D','EQ2E','EQ2F','EQ2I','EQ2J','EQ2K','EQ2M','EQ2N','EQ2O','EQ2P','EQ2Q','EQ2V','EQ2W','NOTEQ2W'],
                ["NA"]);
        }

        $aaeArray = $operation->getAae();
        if(count($aaeArray) > 0){
            // Un seul... mais bon au cas où... ou a revoir / factoriser
            foreach ($aaeArray->toArray() as $aae){
                $this->tplGenerateAAE($templateProcessor,$aae);
            }
        } else {
                $this->fillArrayOfValues($templateProcessor,
                    ['AAE','AAE1','AAE3-1','AAE5-1','AAE7-1','AAE8','AAE9','AAE10','AAE11','NOTAAE11',
                        'AAE3-2','AAE5-2','AAE7-2','AAE3-3','AAE5-3','AAE7-3'],
                    ["NA"]);
                }
        $templateProcessor->setValue('DATELIST',implode(', ',$this->dateList));
        $this->tplAddPlan($templateProcessor,$operation);

        $SonoArray = $operation->getSonometer();
        $nbclone = count($SonoArray);
        if($nbclone > 0) {
            $templateProcessor->cloneRow("SONO", $nbclone);
            $index = 1;
            foreach ($SonoArray->toArray() as $Sono){
                $this->tplGenerateSono($templateProcessor,$Sono,$index);
                $index++;
            }
        } else {
            $templateProcessor->deleteRow("SONO");
        }

        $NoiseSourceArray = $operation->getNoiseSource();
        $nbclone = count($NoiseSourceArray);
        if($nbclone > 0) {
            $templateProcessor->cloneRow("NOISE", $nbclone);
            $index = 1;
            foreach ($NoiseSourceArray->toArray() as $NS){
                $this->tplGenerateNoise($templateProcessor,$NS,$index);
                $index++;
            }
        } else {
            $templateProcessor->deleteRow("NOISE");
        }
        $ShockMachineArray = $operation->getShockmachine();
        $nbclone = count($ShockMachineArray);
        if($nbclone > 0) {
            $templateProcessor->cloneRow("MAC", $nbclone);
            $index = 1;
            foreach ($ShockMachineArray->toArray() as $SM){
                $this->tplGenerateShockMachine($templateProcessor,$SM,$index);
                $index++;
            }
        } else {
            $templateProcessor->deleteRow("MAC");
        }
        $RevAccArray = $operation->getReverbAccessory();
        $nbclone = count($RevAccArray);
        if($nbclone > 0) {
            $templateProcessor->cloneRow("REVTOOL", $nbclone);
            $index = 1;
            foreach ($RevAccArray->toArray() as $RevAcc){
                $this->tplGenerateReverb($templateProcessor,$RevAcc,$index);
                $index++;
            }
        } else {
            $templateProcessor->deleteRow("REVTOOL");
        }
        $SoftArray = $operation->getSoftware();
        $nbclone = count($SoftArray);
        if($nbclone > 0) {
            $templateProcessor->cloneRow("SOFT", $nbclone);
            $index = 1;
            foreach ($SoftArray->toArray() as $Soft){
                $this->tplGenerateSoft($templateProcessor,$Soft,$index);
                $index++;
            }
        } else {
            $templateProcessor->deleteRow("SOFT");
        }
        $templateProcessor->setValue('XLS',$operation->getDocument()->getName());


        $reportFilePath = $this->container->getParameter('path_document').'/report';

        $reportFilePath = realpath($reportFilePath);
        $date = date ( "Y-m-d_His");
        $reportFileName = "Rapport-".$operation->getName().'-'.$operation->getReportReference()."-".$operation->getCaseReference()."-".$date.".docx";
        $reportFileName = $this->sanitize($reportFileName,true);

        $reportFilePath .= '/'.$reportFileName;

        $templateProcessor->saveAs($reportFilePath);

        return $reportFileName;
    }

    /**
     * @param TemplateProcessor $templateProcessor
     * @param Equipement $equipement
     */
    private function tplGenerateEquipment(TemplateProcessor $templateProcessor, Equipement $equipement){
        $data = $equipement->getType1();
        $nbLigne = count($data);
        if ($nbLigne>0){
            $templateProcessor->cloneRow('EQ1B', $nbLigne);
            for ($index = 0; $index < $nbLigne; $index++){
                $row = $index+1;
                $templateProcessor->setValue('EQ1B#'.$row, $data[$index][0][0]);

                $templateProcessor->setValue('EQ1C#'.$row,$data[$index][0][1]);
                $templateProcessor->setValue('EQ1D#'.$row,$data[$index][0][2]);
                $templateProcessor->setValue('EQ1E#'.$row,$data[$index][0][3]);
                $templateProcessor->setValue('EQ1F#'.$row,$data[$index][0][4]);
                $templateProcessor->setValue('EQ1G#'.$row,$data[$index][0][5]);
                $templateProcessor->setValue('EQ1H#'.$row,$data[$index][0][6]);
                $templateProcessor->setValue('EQ1I#'.$row,$data[$index][0][7]);
                $templateProcessor->setValue('EQ1J#'.$row,$data[$index][0][8]);
                $templateProcessor->setValue('EQ1K#'.$row,$data[$index][0][9]);
                //$templateProcessor->setValue('EQ1L#'.$row,$data[$index][0][10]);
                $templateProcessor->setValue('EQ1M#'.$row,$data[$index][0][11]);
                $templateProcessor->setValue('EQ1N#'.$row, $data[$index][0][12]);
                $templateProcessor->setValue('EQ1O#'.$row,$data[$index][0][13]);
                $templateProcessor->setValue('EQ1P#'.$row,$data[$index][0][14]);
                $templateProcessor->setValue('EQ1Q#'.$row,$data[$index][0][15]);
                $templateProcessor->setValue('EQ1V#'.$row,$data[$index][0][20]);
                if(strpos($data[$index][0][21],'NON')!==false){
                    $templateProcessor->setValue('NOTEQ1W#'.$row,$data[$index][0][21]);
                    $templateProcessor->setValue('EQ1W#'.$row,'');

                }else{
                    $templateProcessor->setValue('EQ1W#'.$row,$data[$index][0][21]);
                    $templateProcessor->setValue('NOTEQ1W#'.$row,'');
                }
            }

        } else{
            $this->fillArrayOfValues($templateProcessor,
                ['EQ1B', 'EQ1C', 'EQ1D',	'EQ1E',	'EQ1F',	'EQ1G',	'EQ1H', 'EQ1I',	'EQ1J', 'EQ1K', 'EQ1M',	'EQ1N',	'EQ1O',	'EQ1P',	'EQ1Q',	'EQ1V',	'EQ1W'],
                ['NA']);
        }
        $templateProcessor->setValue('EQ1Ambiant',$equipement->getType1AmbiantNoise());
        $data = $equipement->getType2();
        $nbLigne = count($data);
        if ($nbLigne>0){
            $templateProcessor->cloneRow('EQ2B', $nbLigne);
            for ($index = 0; $index < $nbLigne; $index++){
                $row = $index+1;
                $templateProcessor->setValue('EQ2B#'.$row, $data[$index][0][0]);
                $templateProcessor->setValue('EQ2C#'.$row,$data[$index][0][1]);
                $templateProcessor->setValue('EQ2D#'.$row,$data[$index][0][2]);
                $templateProcessor->setValue('EQ2E#'.$row,$data[$index][0][3]);
                $templateProcessor->setValue('EQ2F#'.$row,$data[$index][0][4]);
                //$templateProcessor->setValue('EQ2G#'.$row,$data[$index][0][5]);
                //$templateProcessor->setValue('EQ2H#'.$row,$data[$index][0][6]);
                $templateProcessor->setValue('EQ2I#'.$row,$data[$index][0][7]);
                $templateProcessor->setValue('EQ2J#'.$row,$data[$index][0][8]);
                $templateProcessor->setValue('EQ2K#'.$row,$data[$index][0][9]);
                //$templateProcessor->setValue('EQ2L#'.$row,$data[$index][0][10]);
                $templateProcessor->setValue('EQ2M#'.$row,$data[$index][0][11]);
                $templateProcessor->setValue('EQ2N#'.$row, $data[$index][0][12]);
                $templateProcessor->setValue('EQ2O#'.$row,$data[$index][0][13]);
                $templateProcessor->setValue('EQ2P#'.$row,$data[$index][0][14]);
                $templateProcessor->setValue('EQ2Q#'.$row,$data[$index][0][15]);
                $templateProcessor->setValue('EQ2V#'.$row,$data[$index][0][20]);
                if(strpos($data[$index][0][21],'NON')!==false){
                    $templateProcessor->setValue('NOTEQ2W#'.$row,$data[$index][0][21]);
                    $templateProcessor->setValue('EQ2W#'.$row,'');

                }else{
                    $templateProcessor->setValue('EQ2W#'.$row,$data[$index][0][21]);
                    $templateProcessor->setValue('NOTEQ2W#'.$row,'');
                }
            }

        } else{
            $this->fillArrayOfValues($templateProcessor,
                ['EQ2B', 'EQ12', 'EQ2D',	'EQ2E',	'EQ2F', 'EQ2I',	'EQ2J', 'EQ2K', 'EQ2M',	'EQ2N',	'EQ2O',	'EQ2P',	'EQ2Q',	'EQ2V',	'EQ2W'],
                ['NA']);
        }
        $templateProcessor->setValue('EQ2Ambiant',$equipement->getType2AmbiantNoise());
    }
    /**
     * @param TemplateProcessor $templateProcessor
     * @param Aerien $Aerial
     * @param $index
     */
    private function tplGenerateA(TemplateProcessor $templateProcessor, Aerien $Aerial, $index){
        $templateProcessor->setValue('A#'.$index, $Aerial->getIdOfSheet());

        $templateProcessor->setValue('AMEASUREDATE#'.$index, $Aerial->getMeasureDate());
        $this->AddDate( $Aerial->getMeasureDate());

        $templateProcessor->setValue('AMEASURETTXDATE#'.$index, $Aerial->getMeasureTTX());

        $templateProcessor->setValue('ALocEmit-Name#'.$index, $Aerial->getLocalEmissionName());
        $templateProcessor->setValue('ALocEmit-Vol#'.$index, $Aerial->getLocalEmissionVolume());
        $templateProcessor->setValue('ALocRecieve-Name#'.$index, $Aerial->getLocalReceptionName());
        $templateProcessor->setValue('ALocRecieve-Vol#'.$index, $Aerial->getLocalReceptionVolume());
        $templateProcessor->setValue('AType#'.$index, $Aerial->getTransmissionType());
        $templateProcessor->setValue('AW#'.$index, $Aerial->getWeightedStandardizedAcousticIsolation());
        $templateProcessor->setValue('AObj#'.$index, $Aerial->getObjectifRa1999());
//        $templateProcessor->setValue('APassRa1999#'.$index, $Aerial->getPassRa1999());
        $passornot = $Aerial->getPassRa1999();
        if(strpos($passornot,'NON')!==false){
            $templateProcessor->setValue('APassRa1999#'.$index, '');
            $templateProcessor->setValue('ANotPassRa1999#'.$index, $passornot);
        } else {
            $templateProcessor->setValue('APassRa1999#'.$index, $passornot);
            $templateProcessor->setValue('ANotPassRa1999#'.$index, '');
        }

        $templateProcessor->setValue('ASepWal-Nature#'.$index, $Aerial->getSeparatingNatureWall());
        $templateProcessor->setValue('ASepWal-Dub-Nature#'.$index, $Aerial->getSeparatingDubbingNatureWall());
        $templateProcessor->setValue('ASepWal-Thick#'.$index, $Aerial->getSeparatingThicknessWall());

        $templateProcessor->setValue('ANb-Door#'.$index, $Aerial->getDoorNumber());

        $templateProcessor->setValue('AExtraction-Mouth#'.$index, $Aerial->getExtractionMouth());

        $templateProcessor->setValue('AFacade-Nature#'.$index, $Aerial->getFacadeDoublingNature());
        $templateProcessor->setValue('AFacade-Thick#'.$index, $Aerial->getFacadeDoublingThickness());


        $Results = $Aerial->getTestResult();
        $idLigne = 1;
        foreach ($Results as $line) {
            $templateProcessor->setValue('ATest_C'.$idLigne.'#'.$index, $line["C"]);
            $templateProcessor->setValue('ATest_D'.$idLigne.'#'.$index, $line["D"]);
            $templateProcessor->setValue('ATest_E'.$idLigne.'#'.$index, $line["E"]);
            $templateProcessor->setValue('ATest_F'.$idLigne.'#'.$index, $line["F"]);
            $templateProcessor->setValue('ATest_G'.$idLigne.'#'.$index, $line["G"]);
            $templateProcessor->setValue('ATest_H'.$idLigne.'#'.$index, $line["H"]);
            $idLigne++;
        }

        $ChartsFilePath = $this->container->getParameter('path_document').'/charts/';
        $Chartfilename = $ChartsFilePath.$Aerial->getFileChart();
        if(realpath($Chartfilename)){
            $templateProcessor->setImg('ACHART#'.$index,['src'=>$Chartfilename,'swh'=>575]);
            $templateProcessor->setImg('CHARTLEGENDA#'.$index,['src'=>$this->ressourcepath."/legendeA.jpg"]);
        }
    }

    /**
     * @param TemplateProcessor $templateProcessor
     * @param Foreigner $foreigner
     * @param $index
     */
    private function tplGenerateF(TemplateProcessor $templateProcessor, Foreigner $foreigner, $index)
    {
        $templateProcessor->setValue('F#'.$index, $foreigner->getIdOfSheet());

        $templateProcessor->setValue('FMEASUREDATE#'.$index, $foreigner->getMeasureDate());
        $this->AddDate( $foreigner->getMeasureDate());

        $templateProcessor->setValue('FMEASURETTXDATE#'.$index, $foreigner->getMeasureTTX());

        $templateProcessor->setValue('FEmitName#'.$index, $foreigner->getLocalEmissionName());
        $templateProcessor->setValue('FEmitType#'.$index, $foreigner->getLocalEmissionType());
        $templateProcessor->setValue('FRecieveName#'.$index, $foreigner->getLocalReceptionName());
        $templateProcessor->setValue('FRecieveVol#'.$index, $foreigner->getLocalReceptionVolume());

        $templateProcessor->setValue('FSepWal-Nature#'.$index, $foreigner->getSeparatingNatureWall());
        $templateProcessor->setValue('FSepWal-Thick#'.$index, $foreigner->getSeparatingThicknessWall());
        $templateProcessor->setValue('FSepWal-Dub-Nature#'.$index, $foreigner->getSeparatingDubbingNatureWall());
        $templateProcessor->setValue('FSepWal-Dub-thick#'.$index, $foreigner->getSeparatingDubbingThicknessWall());

        $templateProcessor->setValue('FWoodWorkNature#'.$index, $foreigner->getCarpentryMaterial());
        $templateProcessor->setValue('FWoodWorkOpening#'.$index, $foreigner->getCarpentryOpening());
        $templateProcessor->setValue('FWoodWorkType#'.$index, $foreigner->getCarpentryOpeningType());
        $templateProcessor->setValue('FShutterBox#'.$index, $foreigner->getRollingShutterBox());

        $templateProcessor->setValue('FVMC-Number#'.$index, $foreigner->getVmcAirIntakeNumber());
        $templateProcessor->setValue('FVMC-Position#'.$index, $foreigner->getVmcAirIntakePosition());
        $templateProcessor->setValue('FVMC-Type#'.$index, $foreigner->getVmcAirIntakeType());

        $templateProcessor->setValue('FBoilerCup#'.$index, $foreigner->getBoilerSuctionCup());
        $Results = $foreigner->getTestResult();

        $idLigne = 1;
        foreach ($Results as $line) {
            $templateProcessor->setValue('FTest_C'.$idLigne.'#'.$index, $line["C"]);
            $templateProcessor->setValue('FTest_D'.$idLigne.'#'.$index, $line["D"]);
            $templateProcessor->setValue('FTest_E'.$idLigne.'#'.$index, $line["E"]);
            $templateProcessor->setValue('FTest_F'.$idLigne.'#'.$index, $line["F"]);
            $templateProcessor->setValue('FTest_G'.$idLigne.'#'.$index, $line["G"]);
            $templateProcessor->setValue('FTest_H'.$idLigne.'#'.$index, $line["H"]);
            $idLigne++;
        }
        $templateProcessor->setValue('FW#'.$index, $foreigner->getWeightedStandardizedAcousticIsolation());
        $templateProcessor->setValue('FObj#'.$index, $foreigner->getObjectifRa1999());
// New red for not pass text
        $passornot = $foreigner->getPassRa1999();
        if(strpos($passornot,'NON')!==false){
            $templateProcessor->setValue('FPassRa1999#'.$index, '');
            $templateProcessor->setValue('FNotPassRa1999#'.$index, $passornot);
        } else {
            $templateProcessor->setValue('FPassRa1999#'.$index, $passornot);
            $templateProcessor->setValue('FNotPassRa1999#'.$index, '');
        }
        $ChartsFilePath = $this->container->getParameter('path_document').'/charts/';
        $Chartfilename = $ChartsFilePath.$foreigner->getFileChart();
        if(realpath($Chartfilename)){
            $templateProcessor->setImg('FCHART#'.$index,['src'=>$Chartfilename,'swh'=>575]);
            $templateProcessor->setImg('CHARTLEGENDF#'.$index,['src'=>$this->ressourcepath."/legendeF.jpg"]);
        }

    }

    /**
     * @param TemplateProcessor $templateProcessor
     * @param Shock $choc
     * @param $index
     */
    private function tplGenerateC(TemplateProcessor $templateProcessor, Shock $choc, $index)
    {
        $templateProcessor->setValue('C#'.$index, $choc->getIdOfSheet());
        $templateProcessor->setValue('CMEASUREDATE#'.$index, $choc->getMeasureDate());
        $this->AddDate( $choc->getMeasureDate());

        $templateProcessor->setValue('CMEASURETTXDATE#'.$index, $choc->getMeasureTTX());

        $templateProcessor->setValue('CLocEmit-Name#'.$index, $choc->getLocalEmissionName());
        $templateProcessor->setValue('CLocEmit-Vol#'.$index, $choc->getLocalEmissionVolume());
        $templateProcessor->setValue('CLocRecieve-Name#'.$index, $choc->getLocalReceptionName());
        $templateProcessor->setValue('CLocRecieve-Vol#'.$index, $choc->getLocalReceptionVolume());
        $templateProcessor->setValue('CType#'.$index, $choc->getTransmissionType());

        $templateProcessor->setValue('CFloor-Nature#'.$index, $choc->getSeparatingNatureFloor());
        $templateProcessor->setValue('CFloor-Thick#'.$index, $choc->getSeparatingThicknessFloor());

        $templateProcessor->setValue('CFloorCover-Nature#'.$index, $choc->getFlooringNature());
        $templateProcessor->setValue('CFloor-TTX#'.$index, $choc->getFlooringAcousticTreatment());

        $templateProcessor->setValue('CNbPosMAC#'.$index, $choc->getNbShockMachines());

        $templateProcessor->setValue('CW#'.$index, $choc->getWeightedStandardizedShockNoise());
        $templateProcessor->setValue('CObj#'.$index, $choc->getObjectifRa1999());
//        $templateProcessor->setValue('CPassRa1999#'.$index, $choc->getPassRa1999());
// New red for not pass text
        $passornot = $choc->getPassRa1999();
        if(strpos($passornot,'NON')!==false){
            $templateProcessor->setValue('CPassRa1999#'.$index, '');
            $templateProcessor->setValue('CNotPassRa1999#'.$index, $passornot);
        } else {
            $templateProcessor->setValue('CPassRa1999#'.$index, $passornot);
            $templateProcessor->setValue('CNotPassRa1999#'.$index, '');
        }

        $Results = $choc->getTestResult();

        $idLigne = 1;
        foreach ($Results as $line) {
            $templateProcessor->setValue('CTest_C'.$idLigne.'#'.$index, $line["C"]);
            $templateProcessor->setValue('CTest_D'.$idLigne.'#'.$index, $line["D"]);
           // $templateProcessor->setValue('CTest_E'.$idLigne.'#'.$index, $line["E"]);
            $templateProcessor->setValue('CTest_F'.$idLigne.'#'.$index, $line["F"]);
            $templateProcessor->setValue('CTest_G'.$idLigne.'#'.$index, $line["G"]);
            $templateProcessor->setValue('CTest_H'.$idLigne.'#'.$index, $line["H"]);
            $idLigne++;
        }
        $ChartsFilePath = $this->container->getParameter('path_document').'/charts/';
        $Chartfilename = $ChartsFilePath.$choc->getFileChart();
        if(realpath($Chartfilename)){
            $templateProcessor->setImg('CCHART#'.$index,['src'=>$Chartfilename,'swh'=>575]);
            $templateProcessor->setImg('CHARTLEGENDC#'.$index,['src'=>$this->ressourcepath."/legendeC.jpg"]);
        }

    }


    /**
     * @param TemplateProcessor $templateProcessor
     * @param Aae $aae
     */
    private function tplGenerateAAE(TemplateProcessor $templateProcessor, Aae $aae){
        $data = $aae->getData();
        $data = $data[0];
        if (is_array($data)){
            $nbLigne = count($data);
            if ($nbLigne>0){
                $templateProcessor->cloneRow('AAE', (int) ($nbLigne/3));
                for ($index = 0; $index < $nbLigne; $index++){
                    $row = $index+1;
                    $templateProcessor->setValue('AAE#'.$row, $data[$index][0]);

                    $templateProcessor->setValue('AAE1#'.$row,$data[$index][1]);
                    $templateProcessor->setValue('AAE3-1#'.$row,$data[$index][3]);
                    $templateProcessor->setValue('AAE5-1#'.$row,$data[$index][5]);
                    $templateProcessor->setValue('AAE7-1#'.$row,$data[$index][7]);
                    $templateProcessor->setValue('AAE8#'.$row,$data[$index][8]);
                    $templateProcessor->setValue('AAE9#'.$row,$data[$index][9]);
                    $templateProcessor->setValue('AAE10#'.$row,$data[$index][14]);
                    if(strpos($data[$index][15],'NON')!==false){
                        $templateProcessor->setValue('NOTAAE11#'.$row,$data[$index][15]);
                        $templateProcessor->setValue('AAE11#'.$row,'');

                    }else{
                        $templateProcessor->setValue('AAE11#'.$row,$data[$index][15]);
                        $templateProcessor->setValue('NOTAAE11#'.$row,'');
                    }
                    $index++;
                    if ($index < $nbLigne){
                        $templateProcessor->setValue('AAE3-2#'.$row,$data[$index][3]);
                        $templateProcessor->setValue('AAE5-2#'.$row,$data[$index][5]);
                        $templateProcessor->setValue('AAE7-2#'.$row,$data[$index][7]);
                        $index++;
                        if ($index < $nbLigne){
                            $templateProcessor->setValue('AAE3-3#'.$row,$data[$index][3]);
                            $templateProcessor->setValue('AAE5-3#'.$row,$data[$index][5]);
                            $templateProcessor->setValue('AAE7-3#'.$row,$data[$index][7]);
                        }
                    }
                }

            } else{
                $this->fillArrayOfValues($templateProcessor,
                    ['AAE', 'AAE1', 'AAE3-1', 'AAE3-2', 'AAE3-3', 'AAE5-1', 'AAE5-2', 'AAE5-3', 'AAE7-1', 'AAE7-2', 'AAE7-3', 'AAE8', 'AAE9', 'AAE10', 'AAE11'],
                    ['NA']);
            }
        }else{
            $this->fillArrayOfValues($templateProcessor,
                ['AAE', 'AAE1', 'AAE3-1', 'AAE3-2', 'AAE3-3', 'AAE5-1', 'AAE5-2', 'AAE5-3', 'AAE7-1', 'AAE7-2', 'AAE7-3', 'AAE8', 'AAE9', 'AAE10', 'AAE11'],
                ['NA']);
        }

    }

    /**
     * Import buildings floor plans into word template as IMG attachment
     * @param TemplateProcessor $templateProcessor
     * @param Operation $operation
     * @throws \ImagickException
     */
    private function tplAddPlan(TemplateProcessor $templateProcessor, Operation $operation){
        $pictures = $this->entityManager->getRepository(Pictures::class)->getPictureByOperationOrder($operation);
        $PictFilePath = realpath($this->container->getParameter('path_picture'));
        $PictFilePath .= '/';
        if (!is_null($pictures) && ( count($pictures) > 0)){
            $nbPlan = count($pictures);
            $templateProcessor->cloneRow('PLAN', $nbPlan);
            $index = 1;
            foreach ($pictures as $pict){
                $src = $PictFilePath.$pict->getPath();
                $arrayfilepath = explode(".", $src);
                $type = end($arrayfilepath);
                if ($type == "pdf" || $type == "pdf"){
                        $templateProcessor->setFixedSizedImages('PLAN#' . $index++, $this->generateImagesFromPDF($src));
                } else {
                    ;

                    $templateProcessor->setFixedSizedImages('PLAN#' . $index++, array($this->getImageBestFitParameters($src,18,24)));
                }
            }
        } else {
            $templateProcessor->setValue('PLAN',"Aucun plan fourni.");
        }
        die();
    }

    /**
     * Generate images from a pdf file and returns array suitable for template processing
     * @param $pdfFile
     * @return array
     * @throws \ImagickException
     */
    private function generateImagesFromPDF($pdfFile){
        $imagick = new \Imagick();
        $imagick->readImage($pdfFile);
        echo 'ORIG '.$pdfFile.'<br>';
        echo '<pre>';
        var_dump($imagick->getSize());
        echo '</pre>';
        //$this->autoRotateImage($imagick);
        $imagick->rotateimage("#FFF", 90);
        $imagick->flattenImages();
        $imagick->trimImage(0);
        echo 'Image W '.$imagick->getImageWidth().'<br>';
        echo 'Image H '.$imagick->getImageHeight().'<br>';
        //$imagick->writeImages($pdfFile.'.jpg',false);
        $imagick->writeImages($pdfFile.'.png',false);

        $index = 0;
        $result = [];
        //while(file_exists($pdfFile.'-'.$index.'.jpg')){
        while(file_exists($pdfFile.'-'.$index.'.png')){
//            $result[] = $this->getImageBestFitParameters($pdfFile.'-'.$index.'.jpg',18,24);
            $result[] = $this->getImageBestFitParameters($pdfFile.'-'.$index.'.png',18,24);
            $index++;
        }
        if ($index==0){
           // $result[] = $this->getImageBestFitParameters($pdfFile.'.jpg',18,24);
            $result[] = $this->getImageBestFitParameters($pdfFile.'.png',18,24);
        }
        return $result;
    }

    /**
     * Compute Image size to a relative area
     * @param $file
     * @param $width
     * @param $height
     * @return array Array of parameters for TemplateProcessor setFixedSizedImages
     */
    private function getImageBestFitParameters($file, $width, $height){
        $imagick = new \Imagick();
        $imagick->readImage($file);
        $imgH = $imagick->getImageHeight();
        $imgW = $imagick->getImageWidth();

        $imgR = $imgW/$imgH;
        if($imgW >= $imgH){
            $imgH = $width / $imgR;
            $imgW = $width;
        } else {
            $imgH = $height;
            $imgW = $height*$imgR;
        }
       return array('src'=> $file,'w'=>$imgW,'h'=>$imgH);
    }
    /**
     * @param $image
     */
    private function autoRotateImage($image) {
        $orientation = $image->getImageOrientation();

        switch($orientation) {
            case \imagick::ORIENTATION_BOTTOMRIGHT:
                $image->rotateimage("#FFF", 180); // rotate 180 degrees
                break;

            case \imagick::ORIENTATION_RIGHTTOP:
                $image->rotateimage("#FFF", 90); // rotate 90 degrees CW
                break;

            case \imagick::ORIENTATION_LEFTBOTTOM:
                $image->rotateimage("#FFF", -90); // rotate 90 degrees CCW
                break;
        }

        // Now that it's auto-rotated, make sure the EXIF data is correct in case the EXIF gets saved with the image!
        $image->setImageOrientation(\imagick::ORIENTATION_TOPLEFT);
    }


    /**
     * @param TemplateProcessor $templateProcessor
     * @param Sonometer $sono
     * @param integer $index
     */
    private function tplGenerateSono(TemplateProcessor $templateProcessor, Sonometer $sono, $index){
        $templateProcessor->setValue('SONO#'.$index,$sono->getType());
        $templateProcessor->setValue('SONO-N#'.$index,$sono->getSerialNumber());
        $templateProcessor->setValue('SONOP-T#'.$index,$sono->getPreamplifierType());
        $templateProcessor->setValue('SONOP-N#'.$index,$sono->getPreamplifierSerialNumber());
        $templateProcessor->setValue('SONOM-T#'.$index,$sono->getMicrophoneType());
        $templateProcessor->setValue('SONOM-N#'.$index,$sono->getMicrophoneSerialNumber());
        $templateProcessor->setValue('SONOC-T#'.$index,$sono->getCalibratorType());
        $templateProcessor->setValue('SONOC-N#'.$index,$sono->getCalibratorSerialNumber());
        $templateProcessor->setValue('SONO-D#'.$index,$sono->getEndOfValidity()->format('m/Y'));
    }
    /**
     * @param TemplateProcessor $templateProcessor
     * @param NoiseSource $noise
     * @param integer $index
     */
    private function tplGenerateNoise(TemplateProcessor $templateProcessor, NoiseSource $noise, $index){
        $templateProcessor->setValue('NOISE#'.$index,$noise->__toString());
    }
    /**
     * @param TemplateProcessor $templateProcessor
     * @param Shockmachine $sm
     * @param integer $index
     */
    private function tplGenerateShockMachine(TemplateProcessor $templateProcessor, Shockmachine $sm, $index){
        $templateProcessor->setValue('MAC#'.$index,$sm->__toString());
    }
    /**
     * @param TemplateProcessor $templateProcessor
     * @param ReverbAccessory $ra
     * @param integer $index
     */
    private function tplGenerateReverb(TemplateProcessor $templateProcessor, ReverbAccessory $ra, $index){
        $templateProcessor->setValue('REVTOOL#'.$index,$ra->getLabel());
    }
    /**
     * @param TemplateProcessor $templateProcessor
     * @param Software $soft
     * @param integer $index
     */
    private function tplGenerateSoft(TemplateProcessor $templateProcessor, Software $soft, $index){
        $templateProcessor->setValue('SOFT#'.$index,$soft->__toString());
    }

}