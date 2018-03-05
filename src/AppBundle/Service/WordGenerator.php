<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 05/03/2018
 * Time: 17:55
 */

namespace AppBundle\Service;

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
     * @param $dataArray
     * @param $needle
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    protected function fillClonedValues(TemplateProcessor $templateProcessor, $dataArray, $needle){
    /*    if(count($dataArray) <= 1){
            $arrayneedle[0] = $needle;
            for($i=1;$i<count($dataArray[0]);$i++){
                $arrayneedle[$i] = $needle.'-'.$i;
            }
            $this->fillArrayOfValues($templateProcessor,$arrayneedle,$dataArray[0]);
        } else { */
    //if ($needle == "BEIIL"){
            $templateProcessor->cloneRow($needle, count($dataArray));
    //    echo "NB LIGNES = ".count($dataArray)."<br>";
            for ($index= 0; $index < count($dataArray); $index++) {
    //            echo "Loop I = ".$index."<br>";
                $row = $index+1;
     //           echo "TPL ROW = ".$row."<br>";
                // Cas du 1re item
                $templateProcessor->setValue($needle."#". $row, $this->cleanValues($dataArray[$index][0]));
     //           echo "1ere Col Ancre : ".$needle."#". $row." => Value : ".$this->cleanValues($dataArray[$index][0])."<br>";
                for ($col = 1; $col < count($dataArray[$index]); $col++) {
                    $templateProcessor->setValue($needle."-".$col. "#" . $row, $this->cleanValues($dataArray[$index][$col]));
     //               echo "Col # ".$col." Ancre : ".$needle."-".$col."#". $row." => Value : ".$this->cleanValues($dataArray[$index][$col])."<br>";
                }
     //       }
     //       die();
    }
        /* } */
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
    private function cleanValues($value){
        if(is_null($value)) return "";
        if(is_string($value) && ($value == "#REF!")) return "";
        return str_replace("<br>"," ", $value);
    }

}