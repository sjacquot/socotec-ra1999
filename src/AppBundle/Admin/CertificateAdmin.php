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
 * Class CertificateAdmin
 * @package AppBundle\Admin
 */
class CertificateAdmin extends AbstractAdmin
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
            ->add('certifReference')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('certifReference')
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
                ->add('certifReference')
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
                ->add('certifReference')
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
            ->add('certifReference')
        ;
    }



    /**
     * @param $certificate
     * @throws \Exception
     */
    public function postUpdate($certificate)
    {
        parent::postUpdate($certificate);

        $this->exportCertificate($certificate->getOperation());
    }

    /**
     * @param $certificate
     * @throws \Exception
     */
    public function postPersist($certificate)
    {
        parent::postPersist($certificate);

        $this->exportCertificate($certificate->getOperation());
    }

    /**
     * @param $operation
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function exportCertificate($operation){

        $pathToCertif = $this->createCertificate($operation);

        header('Content-Type: application/docx');

        $name = $operation->getDocument()->getPathCertificate();

        //No cache
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        header('Content-Disposition: attachment; filename="'.$name.'"');
//Define file size
        header('Content-Disposition: attachment; filename="'.$name.'"');

        ob_clean();
        flush();
        readfile($pathToCertif);
        exit();

    }

    /**
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function createCertificate($operation){
        $em = $this->entityManager;
//        //TODO: Finish certificate generation in AppBundle\Service\GenerateCertificate
        $pathDocCertificate = $this->container->get('app.generate_certificate')->generateCertificate($operation);

        $document = $operation->getDocument();
        $document->setPathCertificate($pathDocCertificate);

        $em->persist($document);
        $em->flush();

        return $this->container->getParameter('path_document') . '/certificate/'.$pathDocCertificate;
    }

}
