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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class GenerateCertificate
{
    /**
     * @var
     */
    private $container;
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * FileUploader constructor.
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    public function CreateCertificate(Operation $operation){
        $templateFile = $this->container->getParameter('path_template_certificate');
        $templateFile = realpath($templateFile);
        $templateProcessor = new TemplateProcessor($templateFile);


// Variables on different parts of document
/*
        $templateProcessor->setValue('weekday', date('l'));            // On section/content
        $templateProcessor->setValue('time', date('H:i'));             // On footer
        $templateProcessor->setValue('serverName', realpath(__DIR__)); // On header
*/
    }
}