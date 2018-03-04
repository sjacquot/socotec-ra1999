<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Operation;
use AppBundle\Entity\Results;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \PhpOffice\PhpSpreadsheet\IOFactory;

class IndexController extends Controller
{
    /**
     * @Route("/list")
     */
    public function listAction()
    {
        return $this->render('Index/list.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/generateReport")
     */
    public function generateReportAction()
    {
        $inputFileType = 'Xls';
        $reader = IOFactory::createReader($inputFileType);
        $path = $this->container->getParameter('kernel.root_dir')."/../web/uploads/test/test2-mod.xls";
        $filePath = realpath("$path");
        var_dump($filePath);

        $spreadsheet = $reader->load($filePath);

        $operation = new Operation();
        $operation->readOperationData($spreadsheet);
        $results = new Results();
        $results->readResults($spreadsheet);
        echo "<pre>";
        var_dump($results);
        echo "</pre>";
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
