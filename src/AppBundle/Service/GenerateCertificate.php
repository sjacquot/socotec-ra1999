<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 05/03/2018
 * Time: 09:47
 */

namespace AppBundle\Service;

use AppBundle\AppBundle;
use AppBundle\Entity\Operation;
use PhpOffice\PhpSpreadsheet\Calculation\DateTime;
use PhpOffice\PhpWord\TemplateProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class GenerateCertificate
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
    private $container;
    /**
     * @var EntityManager
     */
    private $entityManager;

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
     * @param Operation $operation
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
              $data = $resultsData->{self::BAI};
              $templateProcessor->cloneRow('BAI', count($data));
              for ($index = 1; $index < count($data); $index++) {
                  $row = $index++;
                  $templateProcessor->setValue('BAI' . $row, $data[$index][0]);
                  $templateProcessor->setValue('BAIType' . $row, $data[$index][1]);
                  $templateProcessor->setValue('BAIEmint' . $row, $data[$index][2]);
                  $templateProcessor->setValue('BAIRecieve' . $row, $data[$index][3]);
                  $templateProcessor->setValue('BAIFound' . $row, $data[$index][4]);
                  $templateProcessor->setValue('BAIObjective' . $row, $data[$index][5]);
                  $templateProcessor->setValue('BAIDelta' . $row, $data[$index][6]);
                  $templateProcessor->setValue('BAIRA1999' . $row, $data[$index][7]);
                  $templateProcessor->setValue('BAIComment' . $row, $data[$index][8]);
              }
          } else {
              $templateProcessor->setValue('BAI', "NA");
              $templateProcessor->setValue('BAIType', "");
              $templateProcessor->setValue('BAIEmit', "");
              $templateProcessor->setValue('BAIRecieve', "");
              $templateProcessor->setValue('BAIFound', "");
              $templateProcessor->setValue('BAIObjective', "");
              $templateProcessor->setValue('BAIDelta', "");
              $templateProcessor->setValue('BAIRA1999', "");
              $templateProcessor->setValue('BAIComment', "");
          }
         if (isset($resultsData->{self::BAE})) {
              $data = $resultsData->{self::BAE};
              $templateProcessor->cloneRow('BAE', count($data));
              for ($index = 1; $index < count($data); $index++) {
                  $row = $index++;
                  $templateProcessor->setValue('BAE' . $row, $data[$index][0]);
                  $templateProcessor->setValue('BAEType' . $row, $data[$index][1]);
                  $templateProcessor->setValue('BAEEmit' . $row, $data[$index][2]);
                  $templateProcessor->setValue('BAERecieve' . $row, $data[$index][3]);
                  $templateProcessor->setValue('BAEFound' . $row, $data[$index][4]);
                  $templateProcessor->setValue('BAEObjective' . $row, $data[$index][5]);
                  $templateProcessor->setValue('BAEDelta' . $row, $data[$index][6]);
                  $templateProcessor->setValue('BAERA1999' . $row, $data[$index][7]);
                  $templateProcessor->setValue('BAEComment' . $row, $data[$index][8]);
              }
          } else {
              $templateProcessor->setValue('BAE', "NA");
              $templateProcessor->setValue('BAEType', "");
              $templateProcessor->setValue('BAEEmit', "");
              $templateProcessor->setValue('BAERecieve', "");
              $templateProcessor->setValue('BAEFound', "");
              $templateProcessor->setValue('BAEObjective', "");
              $templateProcessor->setValue('BAEDelta', "");
              $templateProcessor->setValue('BAERA1999', "");
              $templateProcessor->setValue('BAEComment', "");
          }
          if (isset($resultsData->{self::BC})) {
              $data = $resultsData->{self::BC};
              $templateProcessor->cloneRow('BC', count($data));
              for ($index = 1; $index < count($data); $index++) {
                  $row = $index++;
                  $templateProcessor->setValue('BC' . $row, $data[$index][0]);
                  $templateProcessor->setValue('BC' . $row, $data[$index][1]);
                  $templateProcessor->setValue('BCEmint' . $row, $data[$index][2]);
                  $templateProcessor->setValue('BCRecieve' . $row, $data[$index][3]);
                  $templateProcessor->setValue('BCFound' . $row, $data[$index][4]);
                  $templateProcessor->setValue('BCObjective' . $row, $data[$index][5]);
                  $templateProcessor->setValue('BCDelta' . $row, $data[$index][6]);
                  $templateProcessor->setValue('BCRA1999' . $row, $data[$index][7]);
                  $templateProcessor->setValue('BCComment' . $row, $data[$index][8]);
              }
          } else {
              $templateProcessor->setValue('BC', "NA");
              $templateProcessor->setValue('BCType', "");
              $templateProcessor->setValue('BAEEmit', "");
              $templateProcessor->setValue('BCRecieve', "");
              $templateProcessor->setValue('BCFound', "");
              $templateProcessor->setValue('BCObjective', "");
              $templateProcessor->setValue('BCDelta', "");
              $templateProcessor->setValue('BCRA1999', "");
              $templateProcessor->setValue('BCComment', "");
          }
        if (isset($resultsData->{self::BEIIL})) {
            $data = $resultsData->{self::BEIIL};
            $templateProcessor->cloneRow('BEIIL', count($data));
            for ($index = 1; $index < count($data); $index++) {
                $row = $index++;
                $templateProcessor->setValue('BEIIL' . $row, $data[$index][0]);
                $templateProcessor->setValue('BEIILEmit' . $row, $data[$index][1]);
                $templateProcessor->setValue('BEIILRecieve' . $row, $data[$index][2]);
                $templateProcessor->setValue('BEIILFound' . $row, $data[$index][3]);
                $templateProcessor->setValue('BEIILObjective' . $row, $data[$index][4]);
                $templateProcessor->setValue('BEIILDelta' . $row, $data[$index][5]);
                $templateProcessor->setValue('BEIILRA1999' . $row, $data[$index][6]);
                $templateProcessor->setValue('BEIILComment' . $row, $data[$index][7]);
            }
        } else {
            $templateProcessor->setValue('BEIIL', "NA");
            $templateProcessor->setValue('BEIILEEmit', "");
            $templateProcessor->setValue('BEIILRecieve', "");
            $templateProcessor->setValue('BEIILFound', "");
            $templateProcessor->setValue('BEIILObjective', "");
            $templateProcessor->setValue('BEIILDelta', "");
            $templateProcessor->setValue('BEIILRA1999', "");
            $templateProcessor->setValue('BEIILComment', "");
        }
        if (isset($resultsData->{self::BEVMC})) {
            $data = $resultsData->{self::BEVMC};
            $templateProcessor->cloneRow('BEVMC', count($data));
            for ($index = 1; $index < count($data); $index++) {
                $row = $index++;
                $templateProcessor->setValue('BEVMC' . $row, $data[$index][0]);
                $templateProcessor->setValue('BEVMCEmit' . $row, $data[$index][1]);
                $templateProcessor->setValue('BEVMCRecieve' . $row, $data[$index][2]);
                $templateProcessor->setValue('BEVMCFound' . $row, $data[$index][3]);
                $templateProcessor->setValue('BEVMCObjective' . $row, $data[$index][4]);
                $templateProcessor->setValue('BEVMCDelta' . $row, $data[$index][5]);
                $templateProcessor->setValue('BEVMCRA1999' . $row, $data[$index][6]);
                $templateProcessor->setValue('BEVMCComment' . $row, $data[$index][7]);
            }
        } else {
            $templateProcessor->setValue('BEVMC', "NA");
            $templateProcessor->setValue('BEVMCEEmit', "");
            $templateProcessor->setValue('BEVMCRecieve', "");
            $templateProcessor->setValue('BEVMCFound', "");
            $templateProcessor->setValue('BEVMCObjective', "");
            $templateProcessor->setValue('BEVMCDelta', "");
            $templateProcessor->setValue('BEVMCRA1999', "");
            $templateProcessor->setValue('BEVMCComment', "");
        }
        if (isset($resultsData->{self::BEC})) {
            $data = $resultsData->{self::BEC};
            $templateProcessor->cloneRow('BEC', count($data));
            for ($index = 1; $index < count($data); $index++) {
                $row = $index++;
                $templateProcessor->setValue('BEC' . $row, $data[$index][0]);
                $templateProcessor->setValue('BECEmit' . $row, $data[$index][1]);
                $templateProcessor->setValue('BECRecieve' . $row, $data[$index][2]);
                $templateProcessor->setValue('BECFound' . $row, $data[$index][3]);
                $templateProcessor->setValue('BECObjective' . $row, $data[$index][4]);
                $templateProcessor->setValue('BECDelta' . $row, $data[$index][5]);
                $templateProcessor->setValue('BECRA1999' . $row, $data[$index][6]);
                $templateProcessor->setValue('BECComment' . $row, $data[$index][7]);
            }
        } else {
            $templateProcessor->setValue('BEC', "NA");
            $templateProcessor->setValue('BECEEmit', "");
            $templateProcessor->setValue('BECRecieve', "");
            $templateProcessor->setValue('BECFound', "");
            $templateProcessor->setValue('BECObjective', "");
            $templateProcessor->setValue('BECDelta', "");
            $templateProcessor->setValue('BECRA1999', "");
            $templateProcessor->setValue('BECComment', "");
        }
        if (isset($resultsData->{self::AAE})) {
            $data = $resultsData->{self::AAE};
            $templateProcessor->cloneRow('AAE', count($data));
            for ($index = 1; $index < count($data); $index++) {
                $row = $index++;
                $templateProcessor->setValue('AAE' . $row, $data[$index][0]);
                $templateProcessor->setValue('AAELocation' . $row, $data[$index][1]);
                $templateProcessor->setValue('AAEFound' . $row, $data[$index][2]);
                $templateProcessor->setValue('AAEObjective' . $row, $data[$index][3]);
                $templateProcessor->setValue('AAERA1999' . $row, $data[$index][4]);
                $templateProcessor->setValue('AAEComment' . $row, $data[$index][5]);
            }
        } else {
            $templateProcessor->setValue('AAE', "NA");
            $templateProcessor->setValue('AAELocation', "");
            $templateProcessor->setValue('AAEFound', "");
            $templateProcessor->setValue('AAEObjective', "");
            $templateProcessor->setValue('AAERA1999', "");
            $templateProcessor->setValue('AAEComment', "");
        }
        $certFilePath = $this->container->getParameter('path_document').'/certificate/';
        $date = date ( "Y-m-d_His");
        $certFileName = $operation->getName().'-'.$operation->getReportReference()."-".$operation->getCaseReferance()."-".$date.".docx";
        $certFilePath .= $certFileName;

        $templateProcessor->saveAs($certFilePath);
        return $certFileName;
    }
}
