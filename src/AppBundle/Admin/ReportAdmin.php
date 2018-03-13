<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Operation;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
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
        if(isset($_GET['operation']) && is_numeric($_GET['operation'])) {
            $formMapper
                ->add('reportReference')
                ->add('operation', EntityType::class, [
                    'class' => Operation::class,
                    'query_builder' => function (EntityRepository $er){
                        return $er->createQueryBuilder('o')
                            ->where('o.id = :id')
                            ->setParameter('id', $_GET['operation']);
                    }
                ])
            ;
        }else{
            $formMapper
                ->add('reportReference')
                ->add('operation', EntityType::class, [
                    'class' => Operation::class,
                ])
            ;
        }
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

        $this->exportReport($report->getOperation());
    }

    /**
     * @param $operation
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function exportReport($operation){

        $pathToReport = $this->createReport($operation);

        header('Content-Type: application/docx');

        $name = $operation->getDocument()->getPathReport();

        header('Content-Disposition: attachment; filename="'.$name.'"');

        readfile($pathToReport);

    }

    /**
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function createReport($operation){
        $em = $this->entityManager;
//        //TODO: Finish report generation in AppBundle\Service\GenerateCertificate
        $pathDocReport = $this->container->get('app.generate_report')->generateReport($operation);
        //TODO: get the report path

        $document = $operation->getDocument();
        $document->setPathReport($pathDocReport);

        $em->persist($document);
        $em->flush();

        return $this->container->getParameter('path_document') . '/report/'.$pathDocReport;
    }

}
