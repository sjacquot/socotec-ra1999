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
        $certFilePath = $this->container->getParameter('path_document').'/certificate/';
        $date = date ( "Y-m-d_His");
        $certFileName = $operation->getName().'-'.$operation->getReportReference()."-".$operation->getCaseReferance()."-".$date.".docx";
        $certFilePath .= $certFileName;

        $templateProcessor->saveAs($certFilePath);
        return $certFileName;
    }

}
