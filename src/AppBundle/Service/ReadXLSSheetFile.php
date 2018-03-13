<?php
/**
 * Created by PhpStorm.
 * User: paolocastro
 * Date: 04/03/2018
 * Time: 15:57
 */

namespace AppBundle\Service;


use AppBundle\Entity\Operation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Settings;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ReadXLSSheetFile
{

    private $container;

    /**
     * ReadXLSSheetFile constructor.
     * @param $container
     */
    public function __construct(ContainerInterface $container)
    {
        Settings::setChartRenderer(\PhpOffice\PhpSpreadsheet\Chart\Renderer\JpGraph::class);
        $this->container = $container;
    }


    /**
     * MANAGE ALL READINGS FROM XLS MEASURE SHEET
     * @param Operation $operation
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function readXLSSheetFile(Operation $operation){
        $doc = $operation->getDocument();
        if(isset($doc)){
            $inputFileName = $this->container->getParameter('path_document').'/'.$operation->getDocument()->getPathDocXml();
            $inputFileType = IOFactory::identify($inputFileName);
            $reader = IOFactory::createReader($inputFileType);
            $reader->setIncludeCharts(true);
            $spreadsheet = $reader->load($inputFileName);

            return $spreadsheet;
        } else {
            return null;
        }
    }
}