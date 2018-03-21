<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 05/03/2018
 * Time: 17:55
 */

namespace AppBundle\Service;

use AppBundle\Entity\Operation;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class WordGenerator
 * @package AppBundle\Service
 */
class WordGenerator
{
    /**
     * Constants to find our way throuht the data
     */
    const BAI = "Bruits Aériens Intérieurs";
    const BAE = "Bruits Aériens Extérieurs";
    const BC = "Bruits de Chocs";
    const BEVMC = "Bruit des Equipements de VMC";
    const BEIEL = "Bruit des Equipements Individuels Extérieurs au Logement contrôlé";
    const BEIIL = "Bruit des Equipements Individuels de chauffage, climatisation ou de production d'ECS Intérieurs au Logement contrôlé";
    const BEC = "Bruit des Equipements Collectifs (hors VMC)";
    const AAE = "Aire d'Absorption Equivalente";
    /**
     * Constant Grades
     */
    const GRADE_C = "C";
    const GRADE_CT = "CT";
    const GRADE_NC = "NC";
    const GRADE_NA = "NA";
    /**
     * CONST FOR TEMPLATE UPDATE
     */
    const WORDLINEBR = '<w:br/>';
    const cbChecked = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
    const cbUnchecked = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
    /**
     * Templated output strings
     */
    protected $APPRECIATION = array("C"=> "Les résultats obtenus sont cohérents avec la réglementation acoustique.",
        "CT"=>"Les résultats obtenus sont cohérents avec la réglementation acoustique, dont certains avec utilisation de l’incertitude admise sur les mesures.",
        "NC"=>"Certains résultats ne sont pas cohérents avec la réglementation acoustique, même avec utilisation de l’incertitude admise sur les mesures.",
        "NA"=>"Sans objet.",
        "Err"=>"Erreur",
        "AAE-C"=> "Les résultats obtenus sont cohérents avec la réglementation acoustique.",
        "AAE-NC"=>"Certains résultats ne sont pas cohérents avec la réglementation acoustique.",);
    /**
     * @var
     */
    protected $container;
    /**
     * @var EntityManager
     */
    protected $entityManager;
    /**
     * @var integer
     */
    protected $countMeasure;

    /**
     * Generate constructor.
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    /**
     * @param TemplateProcessor $templateProcessor
     * @param \stdClass $resultsData
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    protected function fillTplResuls(TemplateProcessor $templateProcessor, \stdClass $resultsData){
        if (isset($resultsData->{self::BAI})) {
            $this->fillClonedValues($templateProcessor,$resultsData->{self::BAI},'BAI');
            $grade = $this->getGradeSocoTec($resultsData->{self::BAI},7);
            $templateProcessor->setValue('BAIAPPREC',$this->APPRECIATION[$grade]);
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BAI','BAI-1','BAI-2','BAI-3','BAI-4','BAI-5','BAI-6','BAI-7','BAI-8'],
                ["NA"]);
            $templateProcessor->setValue('BAIAPPREC',$this->APPRECIATION['NA']);
        }
        if (isset($resultsData->{self::BAE})) {
            $this->fillClonedValues($templateProcessor,$resultsData->{self::BAE},'BAE');
            $grade = $this->getGradeSocoTec($resultsData->{self::BAE},7);
            $templateProcessor->setValue('BAEAPPREC',$this->APPRECIATION[$grade]);
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BAE','BAE-1','BAE-2','BAE-3','BAE-4','BAE-5','BAE-6','BAE-7','BAE-8'],
                ["NA"]);
            $templateProcessor->setValue('BAEAPPREC',$this->APPRECIATION['NA']);
        }
        if (isset($resultsData->{self::BC})) {
            $this->fillClonedValues($templateProcessor,$resultsData->{self::BC},'BC');
            $grade = $this->getGradeSocoTec($resultsData->{self::BC},7);
            $templateProcessor->setValue('BCAPPREC',$this->APPRECIATION[$grade]);
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BC','BC-1','BC-2','BC-3','BC-4','BC-5','BC-6','BC-7','BC-8'],
                ["NA"]);
            $templateProcessor->setValue('BCAPPREC',$this->APPRECIATION['NA']);
        }
        if (isset($resultsData->{self::BEIIL})) {
            $this->fillClonedValues($templateProcessor,$resultsData->{self::BEIIL},'BEIIL');
            $grade = $this->getGradeSocoTec($resultsData->{self::BEIIL},6);
            $templateProcessor->setValue('BEIILAPPREC',$this->APPRECIATION[$grade]);
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BEIIL','BEIIL-1','BEIIL-2','BEIIL-3','BEIIL-4','BEIIL-5','BEIIL-6','BEIIL-7'],
                ["NA"]);
            $templateProcessor->setValue('BEIILAPPREC',$this->APPRECIATION['NA']);

        }
        if (isset($resultsData->{self::BEIEL})) {
            $this->fillClonedValues($templateProcessor,$resultsData->{self::BEIEL},'BEIEL');
            $grade = $this->getGradeSocoTec($resultsData->{self::BEIEL},6);
            $templateProcessor->setValue('BEIELAPPREC',$this->APPRECIATION['NA']);
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BEIEL','BEIEL-1','BEIEL-2','BEIEL-3','BEIEL-4','BEIEL-5','BEIEL-6','BEIEL-7'],
                ["NA"]);
            $templateProcessor->setValue('BEIELAPPREC',$this->APPRECIATION['NA']);
        }
        if (isset($resultsData->{self::BEVMC})) {
            $this->fillClonedValues($templateProcessor,$resultsData->{self::BEVMC},'BEVMC');
            $grade = $this->getGradeSocoTec($resultsData->{self::BEVMC},6);
            $templateProcessor->setValue('BEVMCAPPREC',$this->APPRECIATION[$grade]);
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BEVMC','BEVMC-1','BEVMC-2','BEVMC-3','BEVMC-4','BEVMC-5','BEVMC-6','BEVMC-7'],
                ["NA"]);
            $templateProcessor->setValue('BVMCAPPREC',$this->APPRECIATION['NA']);

        }
        if (isset($resultsData->{self::BEC})) {
            $this->fillClonedValues($templateProcessor,$resultsData->{self::BEC},'BEC');
            $grade = $this->getGradeSocoTec($resultsData->{self::BEC},6);
            $templateProcessor->setValue('BECAPPREC',$this->APPRECIATION[$grade]);
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BEC','BEC-1','BEC-2','BEC-3','BEC-4','BEC-5','BEC-6','BEC-7'],
                ["NA"]);
            $templateProcessor->setValue('BECAPPREC',$this->APPRECIATION['NA']);

        }
        if (isset($resultsData->{self::AAE})) {
            $this->fillClonedValues($templateProcessor,$resultsData->{self::AAE},'AAE');
            $grade = $this->getGradeSocoTec($resultsData->{self::AAE},6);
            $templateProcessor->setValue('AAEAPPREC',$this->APPRECIATION["AAE-".$grade]);
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['AAE','AAE-1','AAE-2','AAE-3','AAE-4','AAE-5','AAE-6','AAE-7'],
                ["NA"]);
            $templateProcessor->setValue('AAEAPPREC',$this->APPRECIATION['NA']);

        }
    }

    /**
     * @param TemplateProcessor $templateProcessor
     * @param Operation $operation
     */
    protected function fillTplOperation(TemplateProcessor $templateProcessor, Operation $operation){

        // MO
        $templateProcessor->setValue('MO', $operation->getMoName());
        $templateProcessor->setValue('MOADDR', $operation->getMoAddress());
        $templateProcessor->setValue('MOADDRCOMP', $operation->getMoAddressComp());
        $templateProcessor->setValue('MOCITY', $operation->getMoCity());
        $templateProcessor->setValue('MOCP', $operation->getMoCP());
        $templateProcessor->setValue('MODEST', $operation->getMoDest());
        $templateProcessor->setValue('MOEMAIL', $operation->getMoEmail());
        $templateProcessor->setValue('MOTEL', $operation->getMoTel());
        // OPE + UTILS
        $templateProcessor->setValue('OPENAME', $operation->getName());
        $templateProcessor->setValue('OPEINFO', $operation->getInfo());
        $templateProcessor->setValue('REPORTREF', $operation->getReportReference());
        $templateProcessor->setValue('OPENBFLAT', $operation->getOperationNbFlat());
        $templateProcessor->setValue('OPENBBAT', $operation->getOperationNbBuilding());
        $templateProcessor->setValue('OPEADDRESS', $operation->getOperationAddress());
        if($operation->isOperationCollectif()){
            $templateProcessor->setValue('OPISCOL', self::cbChecked);
        }else{
            $templateProcessor->setValue('OPISCOL', self::cbUnchecked);
        }
        if($operation->isOperationIndividuel()){
            $templateProcessor->setValue('OPISIND', self::cbChecked);
        }else{
            $templateProcessor->setValue('OPISIND', self::cbUnchecked);
        }
        $date = date ( "d/m/Y");
        $templateProcessor->setValue('REPORTDATE', $date);
        $templateProcessor->setValue('CASEREF', $operation->getCaseReference());
        $templateProcessor->setValue('MEASURECOMP', $operation->getMeasureCompany());
        // TODO: Unification des Balises AUTHOR
        $templateProcessor->setValue('OPEAUTHOR', $operation->getMeasureAuthor());
        $templateProcessor->setValue('MEASUREAUTHOR', $operation->getMeasureAuthor());
        $templateProcessor->setValue('OPECITY', $operation->getOperationCity());
        $templateProcessor->setValue('OPEADDR', $operation->getOperationAddress());
        $templateProcessor->setValue('OPECP', $operation->getOperationCP());
        // BET
        $templateProcessor->setValue('BETAM', $operation->getBETAudioMission());
        $templateProcessor->setValue('BETAN', $operation->getBETAudioName());
        $templateProcessor->setValue('BETSM', $operation->getBETStructureMission());
        $templateProcessor->setValue('BETSN', $operation->getBETStructureName());
        $templateProcessor->setValue('BETFM', $operation->getBETFluidMission());
        $templateProcessor->setValue('BETFN', $operation->getBETFluidName());
        $templateProcessor->setValue('BETTM', $operation->getBETThermalMission());
        $templateProcessor->setValue('BETTN', $operation->getBETThermalName());
        $templateProcessor->setValue('BETOAMOM', $operation->getOtherBETAMOMission());
        $templateProcessor->setValue('BETOAMON', $operation->getOtherBETAMOName());
        // PC
        $date = (!is_null($operation->getPcRequestDate()))? $operation->getPcRequestDate()->format("d/m/Y"):"";
        $templateProcessor->setValue('PCRDATE', $date);
        $templateProcessor->setValue('PCCURPHASE', $operation->getPcCurrentPhase());
        $templateProcessor->setValue('PCREF', $operation->getPcReference());
        $templateProcessor->setValue('PCNBPHASE', $operation->getPcNbPhase());
        $date = (!is_null($operation->getPcDate()))? $operation->getPcDate()->format("d/m/Y"):"";
        $templateProcessor->setValue('PCDATE', $date);
        // Cal
        $date = (!is_null($operation->getCalStartDate()))? $operation->getCalStartDate()->format("d/m/Y"):"";
        $templateProcessor->setValue('CALSTARTDATE', $date);
        $date = (!is_null($operation->getCalEndDate()))? $operation->getCalEndDate()->format("d/m/Y"):"";
        $templateProcessor->setValue('CALENDDATE', $date);




    }
    /**
     * @param TemplateProcessor $templateProcessor
     * @param $dataArray
     * @param $needle
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    protected function fillClonedValues(TemplateProcessor $templateProcessor, $dataArray, $needle){
        $count = count($dataArray);
        $templateProcessor->cloneRow($needle, $count);
        for ($index= 0; $index < $count; $index++) {
            $row = $index+1;
            // Cas du 1re item
            $templateProcessor->setValue($needle."#". $row, $this->cleanValues($dataArray[$index][0]));
            for ($col = 1; $col < count($dataArray[$index]); $col++) {
                $templateProcessor->setValue($needle."-".$col. "#" . $row, $this->cleanValues($dataArray[$index][$col]));
            }

        }
        $this->countMeasure += $count;
    }

    protected function getGradeSocoTec($DataArray,$col){
        $Grade =  self::GRADE_C;
        foreach ($DataArray as $Line){
            $case = $Line[$col];
            switch ($case){
                case "C":
                    break;
                case "CT":
                    $Grade =  self::GRADE_CT;
                    break;
                case "NC":
                    $Grade =  self::GRADE_NC;
                    return $Grade;
                    break;
                default:
                    $Grade =  "Err";
                    return $Grade;
            }
        }
        return $Grade;
    }
    /**
     * @param TemplateProcessor $templateProcessor
     * @param $ArrayNeedle
     * @param $ArrayFill
     */
    protected function fillArrayOfValues(TemplateProcessor $templateProcessor, $ArrayNeedle, $ArrayFill){
        for ($index = 0; $index < count($ArrayNeedle); $index++) {
            if (isset($ArrayFill[$index])){
                $templateProcessor->setValue($ArrayNeedle[$index], $this->cleanValues($ArrayFill[$index]));
            } else {
                $templateProcessor->setValue($ArrayNeedle[$index], '');
            }
        }
    }

    /**
     * @param $value
     * @return mixed|string
     */
    protected function cleanValues($value){
        if(is_null($value)) return "";
        if(is_string($value) && ($value == "#REF!")) return "";
        if(is_string($value) && ($value == "#VALUE!")) return "";
        if(is_string($value) && ($value == "#NULL")) return "";
        return str_replace("<br>",self::WORDLINEBR, $value);
    }
    /**
     * @param $value
     * @return string
     */
    protected function FloatValues($value){
        if(is_null($value)) return "";
        return str_replace(".",",", $value);
    }

}