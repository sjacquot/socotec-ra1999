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
        $this->container = $container;
    }


    /**
     * MANAGE ALL READINGS FROM XLS MEASURE SHEET
     * @param Operation $operation
     * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function readXLSSheetFile(Operation $operation){
        $inputFileType = 'Xls';
        $reader = IOFactory::createReader($inputFileType);
        $doc = $operation->getDocument();
        if(isset($doc)){
            $spreadsheet = $reader->load($this->container->getParameter('path_document').'/'.$operation->getDocument()->getPathDocXml());
            return $spreadsheet;
        } else {
            return null;
        }

        }
}