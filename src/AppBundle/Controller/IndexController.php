<?php

namespace AppBundle\Controller;

use AppBundle\Entity\GraphRA1999;
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
/*        $arrayres = $this->generateImages("/var/www/html/socotec/web/uploads/mytest.pdf");
        print_r($arrayres);die();
        return $this->render('Index/list.html.twig', array(
            // ...
        )); */
    }
    private function generateImages($pdfFile){
        $imagick = new \Imagick();
            $imagick->readImage($pdfFile);
            $imagick->flattenImages();
            $imagick->writeImages($pdfFile.'.jpg',false);
        $index = 0;
        while(file_exists($pdfFile.'-'.$index.'.jpg')){
            $result[] = array('src' => $pdfFile.'-'.$index.'.jpg', 'swh'=> 1024);
            $index++;
        }
        return $result;
    }

    /**
     * @Route("/generateReport/{file}", name="debugresults" )
     * @param string $file
     */
    public function generateReportAction( $file  )
    {
        set_time_limit(0);
        $path = realpath($this->container->getParameter('kernel.root_dir')."/../web/uploads/test/".$file);
        if($path == false){
            return new Response('',404);
        }
        $inputFileType = 'Xls';
        $reader = IOFactory::createReader($inputFileType);
        $spreadSheet = $reader->load($path);

        $calc = $spreadSheet->getCalculationEngine();
        $calc->flushInstance();
        $calc->setLocale('fr');

        $spreadSheet->setActiveSheetIndexByName('Résultats');
        $value = $spreadSheet->getActiveSheet()->getCell('F37')->getCalculatedValue();
        $msg = "AVANT : ".$value."<br>";
        $value = $spreadSheet->getActiveSheet()->getCell('F37')->getOldCalculatedValue();
        $msg .= "OLD : ".$value."<br>";

        $value = $spreadSheet->getActiveSheet()->getCell('F37')->getCalculatedValue();
        $msg .= "APRES : ".$value."<br>";

        /*$writer = IOFactory::createWriter($spreadSheet,'Xlsx');

        $writer->save($path.'x'); */
        return new Response($msg,200);

        /*       $filePath = realpath("$path");
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

               } */


//        return $this->render('Index/generate_report.html.twig', array(
            // ...
//        ));
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
