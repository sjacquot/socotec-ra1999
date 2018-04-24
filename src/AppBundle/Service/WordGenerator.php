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
     * @var array
     */
    protected $dateList;
    /**
     * Generate constructor.
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
        setlocale(LC_ALL, $this->container->getParameter('locale_server_xls'));
    }

    /**
     * @param TemplateProcessor $templateProcessor
     * @param \stdClass $resultsData
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    protected function fillTplResuls(TemplateProcessor $templateProcessor, \stdClass $resultsData){
        if (isset($resultsData->{self::BAI})) {
            $count = $this->fillClonedValues($templateProcessor,$resultsData->{self::BAI},'BAI');
            $grade = $this->getGradeSocoTec($resultsData->{self::BAI},7);
            $templateProcessor->setValue('BAIAPPREC',$this->APPRECIATION[$grade]);
            $this->TplGradeRecap($templateProcessor,$grade,'BAI');
            $templateProcessor->setValue('BAI-NUM',$count);
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BAI','BAI-1','BAI-2','BAI-3','BAI-4','BAI-5','BAI-6','BAI-7','BAI-8'],
                ["NA"]);
            $templateProcessor->setValue('BAIAPPREC',$this->APPRECIATION['NA']);
            $this->TplGradeRecap($templateProcessor,self::GRADE_NA,'BAI');
            $templateProcessor->setValue('BAI-NUM',0);
        }
        if (isset($resultsData->{self::BAE})) {
            $count = $this->fillClonedValues($templateProcessor,$resultsData->{self::BAE},'BAE');
            $grade = $this->getGradeSocoTec($resultsData->{self::BAE},7);
            $templateProcessor->setValue('BAEAPPREC',$this->APPRECIATION[$grade]);
            $this->TplGradeRecap($templateProcessor,$grade,'BAE');
            $templateProcessor->setValue('BAE-NUM',$count);
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BAE','BAE-1','BAE-2','BAE-3','BAE-4','BAE-5','BAE-6','BAE-7','BAE-8'],
                ["NA"]);
            $templateProcessor->setValue('BAEAPPREC',$this->APPRECIATION['NA']);
            $this->TplGradeRecap($templateProcessor,self::GRADE_NA,'BAE');
            $templateProcessor->setValue('BAE-NUM',0);
        }
        if (isset($resultsData->{self::BC})) {
            $count = $this->fillClonedValues($templateProcessor,$resultsData->{self::BC},'BC');
            $grade = $this->getGradeSocoTec($resultsData->{self::BC},7);
            $templateProcessor->setValue('BCAPPREC',$this->APPRECIATION[$grade]);
            $this->TplGradeRecap($templateProcessor,$grade,'BC');
            $templateProcessor->setValue('BC-NUM',$count);
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BC','BC-1','BC-2','BC-3','BC-4','BC-5','BC-6','BC-7','BC-8'],
                ["NA"]);
            $templateProcessor->setValue('BCAPPREC',$this->APPRECIATION['NA']);
            $this->TplGradeRecap($templateProcessor,self::GRADE_NA,'BC');
            $templateProcessor->setValue('BC-NUM',0);
        }
        if (isset($resultsData->{self::BEIIL})) {
            $count = $this->fillClonedValues($templateProcessor,$resultsData->{self::BEIIL},'BEIIL');
            $grade = $this->getGradeSocoTec($resultsData->{self::BEIIL},6);
            $templateProcessor->setValue('BEIILAPPREC',$this->APPRECIATION[$grade]);
            $this->TplGradeRecap($templateProcessor,$grade,'BEIIL');
            $templateProcessor->setValue('BEIIL-NUM',$count);
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BEIIL','BEIIL-1','BEIIL-2','BEIIL-3','BEIIL-4','BEIIL-5','BEIIL-6','BEIIL-7'],
                ["NA"]);
            $templateProcessor->setValue('BEIILAPPREC',$this->APPRECIATION['NA']);
            $this->TplGradeRecap($templateProcessor,self::GRADE_NA,'BEIIL');
            $templateProcessor->setValue('BEIIL-NUM',0);

        }
        if (isset($resultsData->{self::BEIEL})) {
            $count = $this->fillClonedValues($templateProcessor,$resultsData->{self::BEIEL},'BEIEL');
            $grade = $this->getGradeSocoTec($resultsData->{self::BEIEL},6);
            $templateProcessor->setValue('BEIELAPPREC',$this->APPRECIATION[$grade]);
            $this->TplGradeRecap($templateProcessor,$grade,'BEIEL');
            $templateProcessor->setValue('BEIEL-NUM',$count);
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BEIEL','BEIEL-1','BEIEL-2','BEIEL-3','BEIEL-4','BEIEL-5','BEIEL-6','BEIEL-7'],
                ["NA"]);
            $templateProcessor->setValue('BEIELAPPREC',$this->APPRECIATION['NA']);
            $this->TplGradeRecap($templateProcessor,self::GRADE_NA,'BEIEL');
            $templateProcessor->setValue('BEIEL-NUM',0);
        }
        if (isset($resultsData->{self::BEVMC})) {
            $count = $this->fillClonedValues($templateProcessor,$resultsData->{self::BEVMC},'BEVMC');
            $grade = $this->getGradeSocoTec($resultsData->{self::BEVMC},6);
            $templateProcessor->setValue('BEVMCAPPREC',$this->APPRECIATION[$grade]);
            $this->TplGradeRecap($templateProcessor,$grade,'BEVMC');
            $templateProcessor->setValue('BEVMC-NUM',$count);
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BEVMC','BEVMC-1','BEVMC-2','BEVMC-3','BEVMC-4','BEVMC-5','BEVMC-6','BEVMC-7'],
                ["NA"]);
            $templateProcessor->setValue('BEVMCAPPREC',$this->APPRECIATION['NA']);
            $this->TplGradeRecap($templateProcessor,self::GRADE_NA,'BEVMC');
            $templateProcessor->setValue('BEVMC-NUM',0);
        }
        if (isset($resultsData->{self::BEC})) {
            $count = $this->fillClonedValues($templateProcessor,$resultsData->{self::BEC},'BEC');
            $grade = $this->getGradeSocoTec($resultsData->{self::BEC},6);
            $templateProcessor->setValue('BECAPPREC',$this->APPRECIATION[$grade]);
            $this->TplGradeRecap($templateProcessor,$grade,'BEC');
            $templateProcessor->setValue('BEC-NUM',$count);
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BEC','BEC-1','BEC-2','BEC-3','BEC-4','BEC-5','BEC-6','BEC-7'],
                ["NA"]);
            $templateProcessor->setValue('BECAPPREC',$this->APPRECIATION['NA']);
            $this->TplGradeRecap($templateProcessor,self::GRADE_NA,'BEC');
            $templateProcessor->setValue('BEC-NUM',0);
        }
        if (isset($resultsData->{self::AAE})) {
            $count = $this->fillClonedValues($templateProcessor,$resultsData->{self::AAE},'AAE');
            $grade = $this->getGradeSocoTec($resultsData->{self::AAE},6);
            $templateProcessor->setValue('AAEAPPREC',$this->APPRECIATION["AAE-".$grade]);
            $this->TplGradeRecap($templateProcessor,$grade,'AAE');
            $templateProcessor->setValue('AAE-NUM',$count);
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['AAE','AAE-1','AAE-2','AAE-3','AAE-4','AAE-5','AAE-6','AAE-7','AAE-8'],
                ["NA"]);
            $templateProcessor->setValue('AAEAPPREC',$this->APPRECIATION['NA']);
            $this->TplGradeRecap($templateProcessor,self::GRADE_NA,'AAE');
            $templateProcessor->setValue('AAE-NUM',0);

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
        $templateProcessor->setValue('Version', $operation->getInfo());
        $templateProcessor->setValue('REPORTREF', $operation->getReportReference());
        $templateProcessor->setValue('CERTREF', $operation->getCertifReference());
        // Agence
        if (!is_null($operation->getAgency())){
            $templateProcessor->setValue('AGNAME',$operation->getAgency()->getName());
            $templateProcessor->setValue('AGCITY',$operation->getAgency()->getCity());
            $templateProcessor->setValue('AGCP',$operation->getAgency()->getCp());
            $templateProcessor->setValue('AGADD',$operation->getAgency()->getAddress());
            $templateProcessor->setValue('AGTEL',$operation->getAgency()->getTel());
            $templateProcessor->setValue('AGMAIL',$operation->getAgency()->getMail());
        } else {
            $templateProcessor->setValue('AGNAME','');
            $templateProcessor->setValue('AGCITY','');
            $templateProcessor->setValue('AGCP','');
            $templateProcessor->setValue('AGADD','');
            $templateProcessor->setValue('AGTEL','');
            $templateProcessor->setValue('AGMAIL','');

        }
        $value = (int) floatval($operation->getOperationNbCollectif())+ (int) floatval($operation->getOperationNbIndividuel());
        $templateProcessor->setValue('OPNBCOL',$operation->getOperationNbCollectif());
        $templateProcessor->setValue('OPNBIND', $operation->getOperationNbIndividuel());
        $templateProcessor->setValue('OPENBFLAT', $value);

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
        if($operation->isOperationVMCDouple()){
            $templateProcessor->setValue('ISDOUBLEFLUX',self::cbChecked);
        }else{
            $templateProcessor->setValue('ISDOUBLEFLUX',self::cbUnchecked);
        }
        if($operation->isOperationVMCSimple()){
            $templateProcessor->setValue('ISSIMPLEFLUX',self::cbChecked);
        }else{
            $templateProcessor->setValue('ISSIMPLEFLUX',self::cbUnchecked);
        }
        $date = date ( "d/m/Y");
        $templateProcessor->setValue('REPORTDATE', $date);
        $templateProcessor->setValue('CASEREF', $operation->getCaseReference());
        $templateProcessor->setValue('MEASURECOMP', $operation->getMeasureCompany());
        // TODO: Unification des Balises AUTHOR
        $templateProcessor->setValue('COMPSPEAKER',$operation->getCompanySpeaker());
        $templateProcessor->setValue('DOCAUTHOR',$operation->getDocAuthor());
        $templateProcessor->setValue('DOCAUTHORMAIL',$operation->getDocAuthorEmail());
        $templateProcessor->setValue('OPEAUTHOR', $operation->getMeasureAuthor());
        $templateProcessor->setValue('MEASUREAUTHOR', $operation->getMeasureAuthor());
        $templateProcessor->setValue('OPECITY', $operation->getOperationCity());
        $templateProcessor->setValue('OPEADDR', $operation->getOperationAddress());
        $templateProcessor->setValue('OPECP', $operation->getOperationCP());
        $templateProcessor->setValue('OPELABEL', $operation->getOperationLabel());
        $templateProcessor->setValue('OPMINMEASURE',$operation->getNbMeasure());
        // DELEGATE MO
        $templateProcessor->setValue('DELMO',$operation->getDelegateMO());
        $templateProcessor->setValue('DELMOADDR',$operation->getDelegateMOAddress());
        // Maitre oeuvre
        $templateProcessor->setValue('ME',$operation->getMEName());
        $templateProcessor->setValue('MEADDR',$operation->getMEAddress());
        $templateProcessor->setValue('MEMIS',$operation->getMEMission());
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
        $date = (!is_null($operation->getPcRequestDate()))? $operation->getPcRequestDate()->format("d / m / Y"):"";
        $templateProcessor->setValue('PCRDATE', $date);
        $templateProcessor->setValue('PCCURPHASE', $operation->getPcCurrentPhase());
        $templateProcessor->setValue('PCREF', $operation->getPcReference());
        $templateProcessor->setValue('PCNBPHASE', $operation->getPcNbPhase());
        $date = (!is_null($operation->getPcDate()))? $operation->getPcDate()->format("d / m / Y"):"";
        $templateProcessor->setValue('PCDATE', $date);
        // Cal
        $date = (!is_null($operation->getCalStartDate()))? $operation->getCalStartDate()->format("d / m / Y"):"";
        $templateProcessor->setValue('CALSTARTDATE', $date);
        $date = (!is_null($operation->getCalEndDate()))? $operation->getCalEndDate()->format("d / m / Y"):"";
        $templateProcessor->setValue('CALENDDATE', $date);

        $this->AddDate( $operation->getMeasureDate());

        // Transport terrestre
        $data = $operation->getOperationRoute300();
        $dataMask = [1,2,3,4,5];
        if(in_array(null,$data,true)||(count($data)==0)){
            $templateProcessor->setValue('ISROUTENULL',self::cbChecked);
            $templateProcessor->setValue('ISROUTE',self::cbUnchecked);
            $templateProcessor->setValue('ISROUTE1',self::cbUnchecked);
            $templateProcessor->setValue('ISROUTE2',self::cbUnchecked);
            $templateProcessor->setValue('ISROUTE3',self::cbUnchecked);
            $templateProcessor->setValue('ISROUTE4',self::cbUnchecked);
            $templateProcessor->setValue('ISROUTE5',self::cbUnchecked);
        } else {
            foreach ($dataMask as $value){
                if(in_array($value,$data,true)){
                    $templateProcessor->setValue('ISROUTE'.$value, self::cbChecked);
                }else{
                    $templateProcessor->setValue('ISROUTE'.$value, self::cbUnchecked);
                }
            }
            $templateProcessor->setValue('ISROUTE',self::cbChecked);
            $templateProcessor->setValue('ISROUTENULL',self::cbUnchecked);
        }
        // Transport terrestre
        $data = $operation->getOperationZonePEB();
        $dataMask = ['A','B','C','D'];
        if(in_array(null,$data,true)||(count($data)==0)){
            $templateProcessor->setValue('ISAIRPORTNULL',self::cbChecked);
            $templateProcessor->setValue('ISAIRPORT',self::cbUnchecked);
            $templateProcessor->setValue('ISAIRPORTA',self::cbUnchecked);
            $templateProcessor->setValue('ISAIRPORTB',self::cbUnchecked);
            $templateProcessor->setValue('ISAIRPORTC',self::cbUnchecked);
            $templateProcessor->setValue('ISAIRPORTD',self::cbUnchecked);
            } else {
            foreach ($dataMask as $value){
                if(in_array($value,$data,true)){
                    $templateProcessor->setValue('ISAIRPORT'.$value, self::cbChecked);
                }else{
                    $templateProcessor->setValue('ISAIRPORT'.$value, self::cbUnchecked);
                }
            }
            $templateProcessor->setValue('ISAIRPORT',self::cbChecked);
            $templateProcessor->setValue('ISAIRPORTNULL',self::cbUnchecked);
        }
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
        return $count;
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
    protected function TplGradeRecap($templateProcessor,$grade,$needle){
        switch ($grade){
            case self::GRADE_C:
            case self::GRADE_CT:
                $templateProcessor->setValue($needle."-".self::GRADE_C,self::cbChecked);
                $templateProcessor->setValue($needle."-".self::GRADE_NC,self::cbUnchecked);
                $templateProcessor->setValue($needle."-".self::GRADE_NA,self::cbUnchecked);
                break;
            case self::GRADE_NC:
                $templateProcessor->setValue($needle."-".self::GRADE_NC,self::cbChecked);
                $templateProcessor->setValue($needle."-".self::GRADE_C,self::cbUnchecked);
                $templateProcessor->setValue($needle."-".self::GRADE_NA,self::cbUnchecked);
                break;
            case self::GRADE_NA:
                $templateProcessor->setValue($needle."-".self::GRADE_NA,self::cbChecked);
                $templateProcessor->setValue($needle."-".self::GRADE_C,self::cbUnchecked);
                $templateProcessor->setValue($needle."-".self::GRADE_NC,self::cbUnchecked);
                break;
        }

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

    /**
     * @param string $string
     * @param bool $is_filename
     * @return mixed|null|string|string[]
     */

    protected function sanitize($string = '', $is_filename = FALSE)
    {
        // Replace all weird characters with dashes
        $string = preg_replace('/[^\w\-'. ($is_filename ? '~_\.' : ''). ']+/u', '-', $string);

        // Only allow one dash separator at a time (and make string lowercase)
        return mb_strtolower(preg_replace('/--+/u', '-', $string), 'UTF-8');
    }

    /**
     * Add Measure date if not in datelist
     * @param $date
     */
    protected function AddDate($date){
        if(strlen($date)<=1)return;
        if(strpos($date,'/')){
            $datearray = explode('/',$date);
            $ts = mktime(12,0,0,$datearray[0],$datearray[1],$datearray[2]);
            $date = strftime('%d %B %Y',$ts);
        }
        if(is_null($this->dateList)){
            $this->dateList[] = $date;
        }elseif(!in_array($date,$this->dateList))
            $this->dateList[] = $date;
    }

}