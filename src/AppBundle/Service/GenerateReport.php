<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 05/03/2018
 * Time: 09:38
 */

namespace AppBundle\Service;

use AppBundle\Entity\Operation;
use PhpOffice\PhpWord\TemplateProcessor;


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

        $reportFilePath = $this->container->getParameter('path_document').'/report';
        $reportFilePath = realpath($reportFilePath);
        $date = date ( "Y-m-d_His");
        $reportFileName = "Rapport-".$operation->getName().'-'.$operation->getReportReference()."-".$operation->getCaseReferance()."-".$date.".docx";
        $reportFilePath .= '/'.$reportFileName;

        $templateProcessor->saveAs($reportFilePath);
        return $reportFileName;
    }

}