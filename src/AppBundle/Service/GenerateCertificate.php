<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 05/03/2018
 * Time: 09:47
 */

namespace AppBundle\Service;

use AppBundle\Entity\Operation;
use PhpOffice\PhpWord\TemplateProcessor;


/**
 * Class GenerateCertificate
 * @package AppBundle\Service
 */
class GenerateCertificate extends WordGenerator
{
    /**
     * @param Operation $operation
     * @return string
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function generateCertificate(Operation $operation)
    {

        $templateFile = $this->container->getParameter('path_template_certificate');
        $templateFile = realpath($templateFile);

        $templateProcessor = new TemplateProcessor($templateFile);
        $templateProcessor->setValue('MO', "A AJOUTER A OPERATION");
        $templateProcessor->setValue('OPENAME', $operation->getName());
        $templateProcessor->setValue('OPEINFO', $operation->getInfo());
        $templateProcessor->setValue('OPREF', $operation->getReportReference());
        $templateProcessor->setValue('OPCASE', $operation->getCaseReferance());
        $templateProcessor->setValue('MEASURECOMP', $operation->getMeasureCompany());
        $templateProcessor->setValue('OPEAUTHOR', $operation->getMeasureAuthor());

        $results = $operation->getResults();
        $resultsData = $results->getData();
        $this->fillTplResuls($templateProcessor,$resultsData);

        $certFilePath = $this->container->getParameter('path_document').'/certificate/';
        $date = date ( "Y-m-d_His");
        $certFileName = $operation->getName().'-'.$operation->getReportReference()."-".$operation->getCaseReferance()."-".$date.".docx";
        $certFilePath .= $certFileName;

        $templateProcessor->saveAs($certFilePath);
        return $certFileName;
    }

}
