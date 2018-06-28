<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 05/03/2018
 * Time: 09:47
 */

namespace AppBundle\Service;

use AppBundle\Entity\Operation;
use PhpOffice\PhpWord\PhpWord;
use AppBundle\Entity\PhpOfficeWord;


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
//        $templateProcessor = new TemplateProcessor($templateFile);
        $templateProcessor = new PhpOfficeWord($templateFile);

        // Data from Operation
        $this->fillTplOperation($templateProcessor,$operation);
        // Data from Results
        $this->fillTplResuls($templateProcessor,$operation->getResults()->getData());// also update $this->countMeasure value
        $templateProcessor->setValue('COUNTMEASURE',$this->countMeasure);

        $certFilePath = $this->container->getParameter('path_document').'/certificate';
        $certFilePath = realpath($certFilePath);

        $date = date ( "Y-m-d_His");
        $certFileName = "Attestation-".$operation->getName().'-'.$operation->getReportReference()."-".$operation->getCaseReference()."-".$date.".docx";
        $certFileName = $this->sanitize($certFileName,true);
        $certFilePath .= "/".$certFileName;
        $templateProcessor->saveAs($certFilePath);
        return $certFileName;
    }
    // TODO: Explorer pour updateFields en auto...
    //        $phpWord = \PhpOffice\PhpWord\IOFactory::load($certFilePath);
    //        $phpWord->getSettings()->setUpdateFields(true);
    //        $phpWord->save($certFilePath,'Word2007',true);

}
