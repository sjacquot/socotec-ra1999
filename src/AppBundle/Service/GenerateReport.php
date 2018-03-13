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
use AppBundle\Entity\Operation;
use AppBundle\Entity\Shock;
use PhpOffice\PhpWord\TemplateProcessor;


/**
 * Class GenerateReport
 * @package AppBundle\Service
 */
class GenerateReport extends WordGenerator
{
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

        $templateProcessor = new TemplateProcessor($templateFile);
        // Data from Operation
        $this->fillTplOperation($templateProcessor,$operation);
        // Data from Results
        $this->fillTplResuls($templateProcessor,$operation->getResults()->getData());
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
                $index++;
            }
        } else {
            $templateProcessor->deleteBlock("BLOCK_C");
        }

        $reportFilePath = $this->container->getParameter('path_document').'/report';
        $reportFilePath = realpath($reportFilePath);
        $date = date ( "Y-m-d_His");
        $reportFileName = "Rapport-".$operation->getName().'-'.$operation->getReportReference()."-".$operation->getCaseReference()."-".$date.".docx";
        $reportFilePath .= '/'.$reportFileName;

        $templateProcessor->saveAs($reportFilePath);
        return $reportFileName;
    }

    /**
     * @param TemplateProcessor $templateProcessor
     * @param Aerien $Aerial
     * @param $index
     */
    private function tplGenerateA(TemplateProcessor $templateProcessor, Aerien $Aerial, $index){
        $templateProcessor->setValue('A#'.$index, $Aerial->getIdOfSheet());
        $templateProcessor->setValue('ALocEmit-Name#'.$index, $Aerial->getLocalEmissionName());
        $templateProcessor->setValue('ALocEmit-Vol#'.$index, $Aerial->getLocalEmissionVolume());
        $templateProcessor->setValue('ALocRecieve-Name#'.$index, $Aerial->getLocalReceptionName());
        $templateProcessor->setValue('ALocRecieve-Vol#'.$index, $Aerial->getLocalReceptionVolume());
        $templateProcessor->setValue('AType#'.$index, $Aerial->getTransmissionType());
        $templateProcessor->setValue('AW#'.$index, $Aerial->getWeightedStandardizedAcousticIsolation());
        $templateProcessor->setValue('AObj#'.$index, $Aerial->getObjectifRa1999());
        $templateProcessor->setValue('APassRa1999#'.$index, $Aerial->getPassRa1999());

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
    }

    /**
     * @param TemplateProcessor $templateProcessor
     * @param Foreigner $foreigner
     * @param $index
     */
    private function tplGenerateF(TemplateProcessor $templateProcessor, Foreigner $foreigner, $index)
    {
        $templateProcessor->setValue('F#'.$index, $foreigner->getIdOfSheet());
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
        $templateProcessor->setValue('FPassRa1999#'.$index, $foreigner->getPassRa1999());

    }

    /**
     * @param TemplateProcessor $templateProcessor
     * @param Shock $choc
     * @param $index
     */
    private function tplGenerateC(TemplateProcessor $templateProcessor, Shock $choc, $index)
    {
        $templateProcessor->setValue('C#'.$index, $choc->getIdOfSheet());
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
        $templateProcessor->setValue('CPassRa1999#'.$index, $choc->getPassRa1999());

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
    }


}