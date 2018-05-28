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
 * \class CertificateAdmin
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
        $formMapper
            ->with("Génération de l'attestation RA1999");
        if(isset($_GET['operation']) && is_numeric($_GET['operation'])) {
            if ($this->isCurrentRoute('edit')) {
                $formMapper
                    ->add('certifReference', null, ['label' => 'Référence de l\'attestation RA1999', 'required' => false]);
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
                ]);
        } else {
            if ($this->isCurrentRoute('edit')) {
                $formMapper
                    ->add('certifReference', null, ['label' => 'Référence de l\'attestation RA1999', 'required' => false]);
            }
            $formMapper
                    ->add('operation', EntityType::class, [
                        'class' => Operation::class,
                    ]);
            }
        $formMapper->end();

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

        $operation = $certificate->getOperation();

        $operation->setCertifReference($certificate);

        $this->exportCertificate($operation);
    }

    /**
     * @param $operation
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function exportCertificate($operation){

        $pathToCertif = $this->createCertificate($operation);

        $name = $operation->getDocument()->getPathCertificate();
        //echo $this->container->getParameter('path_certificate').'/'.$name;die();

        header('Content-Type: application/docx');


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
        $pathDocCertificate = $this->container->get('app.generate_certificate')->generateCertificate($operation);

        $document = $operation->getDocument();
        $document->setPathCertificate($pathDocCertificate);

        $em->persist($document);
        $em->flush();

        return $this->container->getParameter('path_document') . '/certificate/'.$pathDocCertificate;
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

