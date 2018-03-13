<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Operation;
use AppBundle\Entity\Results;
use AppBundle\Service\ExtractEquipments;
use AppBundle\Service\ExtractResults;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    /**
     * @Route("/list")
     */
    public function listAction()
    {
        $haystack = array (
            'A(1)',
            'AAE',
            'A(2)',
            'A()',
            'F(1)'
        );

        $matches  = preg_grep ('/^A\((\d+)\)/i', $haystack);

        print_r ($matches);
        die();
        return $this->render('Index/list.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/generateReport/{file}", name="debugresults" )
     */
    public function generateReportAction($file)
    {
        $inputFileType = 'Xls';
        $reader = IOFactory::createReader($inputFileType);
        $path = realpath($this->container->getParameter('kernel.root_dir')."/../web/uploads/test/".$file);
        if($path == false){
            return new Response('',404);
        }
        $filePath = realpath("$path");
        var_dump($filePath);

        $spreadsheet = $reader->load($filePath);

        $matches  = preg_grep ('/^A\((\d+)\)/i', $SheetNames);
        foreach ($matches as $sheet){
            // BAI
            $extractBAI = new ExtractBAI();
            $extractBAI->extractBAI($spreadSheet, $sheet);
            echo $sheet."<br>";
        }

        $matches  = preg_grep ('/^F\((\d+)\)/i', $SheetNames);
        foreach ($matches as $sheet){
            // BAE
            $extractBAE = new ExtractBAE();
            $extractBAE->extractBAE($spreadSheet, $sheet);
            echo $sheet."<br>";

        }

        $matches  = preg_grep ('/^C\((\d+)\)/i', $SheetNames);
        foreach ($matches as $sheet){
            // BAE
            $extractBC = new ExtractBC();
            $extractBC->extractBC($spreadSheet, $sheet);
            echo $sheet."<br>";

        }



        /*        $operation = new Operation();
                $operation->readOperationData($spreadsheet);
                $results = new ExtractResults();
                $arrayResults = $results->readResults($spreadsheet); */
        /*$Equip = new ExtractEquipments();
        $Equip->readEquipment($spreadsheet);
        echo "<pre>";
        //var_dump($Equip->type1);
        echo "<br>Ambiance 1= ".$Equip->type1AmbiantNoise."<hr>";
        //var_dump($Equip->type2);
        echo "<br>Ambiance 2= ".$Equip->type2AmbiantNoise."<hr>";
        echo "</pre>";*/
        die();
        return $this->render('Index/generate_report.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/generateCert")
     */
    public function generateCertAction()
    {
        return $this->render('Index/generate_cert.html.twig', array(
            // ...
        ));
    }

}
