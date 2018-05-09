<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Operation;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class ReportAdmin
 * @package AppBundle\Admin
 */
class ReportAdmin extends AbstractAdmin
{
    /**
     * @var array
     */
    protected $perPageOptions = array(16, 32, 64, 128, 192, 'All');
    /**
     * @var Container
     */
    protected $container;
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * OperationAdmin constructor.
     * @param $code
     * @param $class
     * @param $baseControllerName
     * @param Container $container
     */
    public function __construct($code, $class, $baseControllerName, Container $container, EntityManager $entityManager)
    {
        parent::__construct($code, $class, $baseControllerName);

        /**
         * getFilter for list if there is the cookie
         */
        $this->container = $container;
        $this->entityManager = $entityManager;
    }
    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['edit','create']);
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('reportReference')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('reportReference')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with("Génération du rapport de mesure détaillé");
        if(isset($_GET['operation']) && is_numeric($_GET['operation'])) {
            if ($this->isCurrentRoute('edit')) {
                $formMapper
                    ->add('reportReference',null ,['label'=>'Référence du rapport de mesure détaillé','required' => false]);
                }
               $formMapper
                   ->add('operation', EntityType::class, [
                    'class' => Operation::class,
                    'label' => "Nom de l'Opération/Chantier",
                    'query_builder' => function (EntityRepository $er){
                        return $er->createQueryBuilder('o')
                            ->where('o.id = :id')
                            ->setParameter('id', $_GET['operation']);
                    }
                ])
            ;
        } else {
            if ($this->isCurrentRoute('edit')) {
                $formMapper
                    ->add('reportReference',null ,['label'=>'Référence du rapport de mesure détaillé','required' => false]);
            }
            $formMapper
                ->add('operation', EntityType::class, [
                    'class' => Operation::class,
                ])
            ;
        }
        $formMapper->end();

    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('reportReference')
        ;
    }



    /**
     * @param $report
     * @throws \Exception
     */
    public function postUpdate($report)
    {
        parent::postUpdate($report);

        $this->exportReport($report->getOperation());
    }

    /**
     * @param $report
     * @throws \Exception
     */
    public function postPersist($report)
    {
        parent::postPersist($report);

        $operation = $report->getOperation();

        $operation->setReportReference($report);

        $this->exportReport($operation);
    }

    /**
     * @param $operation
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function exportReport($operation){


        $pathToReport = $this->createReport($operation);

        $name = $operation->getDocument()->getPathReport();

        //echo $this->container->getParameter('path_report').'/'.$name;die();

        header('Content-Type: application/docx');


        //No cache
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        header('Content-Disposition: attachment; filename="'.$name.'"');
//Define file size
        header('Content-Length: ' . filesize($pathToReport));

        ob_clean();
        flush();
        readfile($pathToReport);
        exit();

    }

    /**
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function createReport($operation){
        $em = $this->entityManager;
        $pathDocReport = $this->container->get('app.generate_report')->generateReport($operation);

        $document = $operation->getDocument();
        $document->setPathReport($pathDocReport);

        $em->persist($document);
        $em->flush();

        return $this->container->getParameter('path_document') . '/report/'.$pathDocReport;
    }

    /**
     * @param $name
     * @return mixed|null|string
     */
    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'CRUD/edit.html.twig';
                break;
            default:
            return parent::getTemplate($name);
                break;
        }
    }

}

