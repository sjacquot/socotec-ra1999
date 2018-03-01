<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
