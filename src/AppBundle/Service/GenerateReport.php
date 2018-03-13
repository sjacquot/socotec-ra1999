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
        echo var_dump($ForeignerArray);
        echo "<hr>";
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
        die();

        $reportFilePath = $this->container->getParameter('path_document').'/report';
        $reportFilePath = realpath($reportFilePath);
        $date = date ( "Y-m-d_His");
        $reportFileName = "Rapport-".$operation->getName().'-'.$operation->getReportReference()."-".$operation->getCaseReferance()."-".$date.".docx";
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
        //Options
        $options = ["ASepWal-Nature"=>$this->cleanValues($Aerial->getSeparatingNatureWall()),
            "ASepWal-Dub-Nature"=>$this->cleanValues($Aerial->getSeparatingDubbingNatureWall()),
            "ASepWal-Thick"=>$this->cleanValues($Aerial->getSeparatingThicknessWall())];
        $nbvalue = 0;
        $export = false;
        foreach ($options as $key => $value){
            if($value !== '') {
                $export = true;
            }
            $nbvalue++;
            $templateProcessor->setValue($key.'#'.$index, $value);
        }
        if(!$export){
            $templateProcessor->deleteBlock('AOPTION1-1#'.$index);
            $templateProcessor->deleteBlock('AOPTION1-2#'.$index);
            $templateProcessor->deleteBlock('AOPTION1-3#'.$index);
            $templateProcessor->deleteBlock('AOPTION1-4#'.$index);
        } else {
            $templateProcessor->setBlock('AOPTION1-1#'.$index, $templateProcessor->getBlock('AOPTION1-1#'.$index),1);
            $templateProcessor->setBlock('AOPTION1-2#'.$index, $templateProcessor->getBlock('AOPTION1-2#'.$index),1);
            $templateProcessor->setBlock('AOPTION1-3#'.$index, $templateProcessor->getBlock('AOPTION1-3#'.$index),1);
            $templateProcessor->setBlock('AOPTION1-4#'.$index, $templateProcessor->getBlock('AOPTION1-4#'.$index),1);
        }
        $options = ["ANb-Door"=>$this->cleanValues($Aerial->getDoorNumber())];
        $nbvalue = 0;
        $export = false;
        foreach ($options as $key => $value){
            if($value !== '') {
                $export = true;
            }
            $nbvalue++;
            $templateProcessor->setValue($key.'#'.$index, $value);
        }
        if(!$export){
            $templateProcessor->deleteBlock('AOPTION2-1#'.$index);
            $templateProcessor->deleteBlock('AOPTION2-2#'.$index);
        } else {
            $templateProcessor->setBlock('AOPTION2-1#'.$index, $templateProcessor->getBlock('AOPTION2-1#'.$index),1);
            $templateProcessor->setBlock('AOPTION2-1#'.$index, $templateProcessor->getBlock('AOPTION2-2#'.$index),1);
        }
        $options = ["AExtraction-Mouth"=>$this->cleanValues($Aerial->getExtractionMouth())];
        $nbvalue = 0;
        $export = false;
        foreach ($options as $key => $value){
            if($value !== '') {
                $export = true;
            }
            $nbvalue++;
            $templateProcessor->setValue($key.'#'.$index, $value);
        }
        if(!$export){
            $templateProcessor->deleteBlock('AOPTION3-1#'.$index);
        } else {
            $templateProcessor->setBlock('AOPTION3-1#'.$index, $templateProcessor->getBlock('AOPTION3-1#'.$index),1);
        }
        //Options
        $options = ["AFacade-Nature"=>$this->cleanValues($Aerial->getFacadeDoublingNature()),
            "AFacade-Thick"=>$this->cleanValues($Aerial->getFacadeDoublingThickness())];
        $nbvalue = 0;
        $export = false;
        foreach ($options as $key => $value){
            if($value !== '') {
                $export = true;
            }
            $nbvalue++;
            $templateProcessor->setValue($key.'#'.$index, $value);
        }
        if(!$export){
            $templateProcessor->deleteBlock('AOPTION4-1#'.$index);
            $templateProcessor->deleteBlock('AOPTION4-2#'.$index);
            $templateProcessor->deleteBlock('AOPTION4-3#'.$index);
        } else {
            $templateProcessor->setBlock('AOPTION4-1#'.$index, $templateProcessor->getBlock('AOPTION4-1#'.$index),1);
            $templateProcessor->setBlock('AOPTION4-2#'.$index, $templateProcessor->getBlock('AOPTION4-2#'.$index),1);
            $templateProcessor->setBlock('AOPTION4-2#'.$index, $templateProcessor->getBlock('AOPTION4-3#'.$index),1);
        }
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
        echo "<h2>Facade Resultats</h2>";
        echo "<pre>";
        echo var_dump($foreigner->getTestResult());
        echo "</pre>";
    }

    /**
     * @param TemplateProcessor $templateProcessor
     * @param Shock $choc
     * @param $index
     */
    private function tplGenerateC(TemplateProcessor $templateProcessor, Shock $choc, $index)
    {
        echo "<h2>Choc Resultats</h2>";
        echo "<pre>";
        echo var_dump($choc->getTestResult());
        echo "</pre>";
    }


}