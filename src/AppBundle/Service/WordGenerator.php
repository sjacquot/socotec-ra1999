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
     *
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
     * @var
     */
    protected $container;
    /**
     * @var EntityManager
     */
    protected $entityManager;

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
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BAI','BAI-1','BAI-2','BAI-3','BAI-4','BAI-5','BAI-6','BAI-7','BAI-8'],
                ["NA"]);
        }
        if (isset($resultsData->{self::BAE})) {
            $this->fillClonedValues($templateProcessor,$resultsData->{self::BAE},'BAE');
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BAE','BAE-1','BAE-2','BAE-3','BAE-4','BAE-5','BAE-6','BAE-7','BAE-8'],
                ["NA"]);
        }
        if (isset($resultsData->{self::BC})) {
            $this->fillClonedValues($templateProcessor,$resultsData->{self::BC},'BC');
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BC','BC-1','BC-2','BC-3','BC-4','BC-5','BC-6','BC-7','BC-8'],
                ["NA"]);
        }
        if (isset($resultsData->{self::BEIIL})) {
            $this->fillClonedValues($templateProcessor,$resultsData->{self::BEIIL},'BEIIL');
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BEIIL','BEIIL-1','BEIIL-2','BEIIL-3','BEIIL-4','BEIIL-5','BEIIL-6','BEIIL-7'],
                ["NA"]);
        }
        if (isset($resultsData->{self::BEIEL})) {
            $this->fillClonedValues($templateProcessor,$resultsData->{self::BEIEL},'BEIEL');
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BEIEL','BEIEL-1','BEIEL-2','BEIEL-3','BEIEL-4','BEIEL-5','BEIEL-6','BEIEL-7'],
                ["NA"]);
        }
        if (isset($resultsData->{self::BEVMC})) {
            $this->fillClonedValues($templateProcessor,$resultsData->{self::BEVMC},'BEVMC');
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BEVMC','BEVMC-1','BEVMC-2','BEVMC-3','BEVMC-4','BEVMC-5','BEVMC-6','BEVMC-7'],
                ["NA"]);
        }
        if (isset($resultsData->{self::BEC})) {
            $this->fillClonedValues($templateProcessor,$resultsData->{self::BEC},'BEC');
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['BEC','BEC-1','BEC-2','BEC-3','BEC-4','BEC-5','BEC-6','BEC-7'],
                ["NA"]);
        }
        if (isset($resultsData->{self::AAE})) {
            $this->fillClonedValues($templateProcessor,$resultsData->{self::AAE},'AAE');
        } else {
            $this->fillArrayOfValues($templateProcessor,
                ['AAE','AAE-1','AAE-2','AAE-3','AAE-4','AAE-5','AAE-6','AAE-7'],
                ["NA"]);
        }
    }

    /**
     * @param TemplateProcessor $templateProcessor
     * @param Operation $operation
     */
    protected function fillTplOperation(TemplateProcessor $templateProcessor, Operation $operation){

        $templateProcessor->setValue('MO', "A AJOUTER A OPERATION");
        $templateProcessor->setValue('OPENAME', $operation->getName());
        $templateProcessor->setValue('OPEINFO', $operation->getInfo());
        $templateProcessor->setValue('REPORTREF', $operation->getReportReference());
        $date = date ( "d/m/Y");
        $templateProcessor->setValue('REPORTDATE', $date);
        $templateProcessor->setValue('CASEREF', $operation->getCaseReference());
        $templateProcessor->setValue('MEASURECOMP', $operation->getMeasureCompany());
        $templateProcessor->setValue('OPEAUTHOR', $operation->getMeasureAuthor());

}
    /**
     * @param TemplateProcessor $templateProcessor
     * @param $dataArray
     * @param $needle
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    protected function fillClonedValues(TemplateProcessor $templateProcessor, $dataArray, $needle){
        $templateProcessor->cloneRow($needle, count($dataArray));
        for ($index= 0; $index < count($dataArray); $index++) {
            $row = $index+1;
            // Cas du 1re item
            $templateProcessor->setValue($needle."#". $row, $this->cleanValues($dataArray[$index][0]));
            for ($col = 1; $col < count($dataArray[$index]); $col++) {
                $templateProcessor->setValue($needle."-".$col. "#" . $row, $this->cleanValues($dataArray[$index][$col]));
            }

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
        return str_replace("<br>"," ", $value);
    }

}