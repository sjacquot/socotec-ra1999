<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 02/03/2018
 * Time: 11:29
 */

namespace AppBundle\Admin;

use AppBundle\Entity\Operation;
use AppBundle\Service\ExtractData;
use AppBundle\Service\FileUploader;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Extension\Core\Type\FileType;


/**
 * Class OperationAdmin
 * @package AppBundle\Admin
 */
class OperationAdmin extends Admin
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
     * OperationAdmin constructor.
     * @param $code
     * @param $class
     * @param $baseControllerName
     * @param Container $container
     */
    public function __construct($code, $class, $baseControllerName, Container $container)
    {
        parent::__construct($code, $class, $baseControllerName);

        /**
         * getFilter for list if there is the cookie
         */
        $this->container = $container;
    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('report', $this->getRouterIdParameter().'/report');
        $collection->add('certificate', $this->getRouterIdParameter().'/certificate');
    }

    /**
     * @return array
     */
    public function getExportFormats()
    {
        return ['csv'];
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->with('Operation/Chantier', array('class' => 'col-md-9'))
                ->add('caseReferance',null)
                ->add('reportReference')
                ->add('document', EntityType::class, array(
                    'multiple' => false,
                    'required' => false,
                    'label' => 'Documents already updated',
                    'class' => 'AppBundle\Entity\Document',
                ))
                ->add('documents', FileType::class, array('data_class' => null, 'multiple' => false, 'required' => false, 'mapped' => false, 'label' => 'Add Document'))
            ->end();
        if ($this->isCurrentRoute('edit')) {
            $formMapper
                ->with('Metadata', array('class' => 'col-md-9'))
                ->add('name')
                ->add('status')
                ->add('measureCompany')
                ->add('measureAuthor')
                ->add('info')
                ->add('operationAddress')
                ->add('operationCity')
                ->add('operationObjective')
                ->add('operationMeasureRef')
                ->add('measureReport')
                ->add('measureCert')
                ->add('measureDate', DatePickerType::class, array(
                    'required' => false,
                    'label' => 'Expiration Date',
                    'dp_side_by_side' => true,
                    'dp_use_current' => true,
                    'format' => 'dd/MM/yyyy',
                ))
                ->end();
        }


    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name', null, ['global_search' => true]);
        $datagridMapper->add('caseReferance', null, ['global_search' => true]);
        $datagridMapper->add('status', null, ['global_search' => true]);
        $datagridMapper->add('measureCompany', null, ['global_search' => true]);
        $datagridMapper->add('measureAuthor', null, ['global_search' => true]);
        $datagridMapper->add('info', null, ['global_search' => true]);
        $datagridMapper->add('operationAddress', null, ['global_search' => true]);
        $datagridMapper->add('operationCity', null, ['global_search' => true]);
        $datagridMapper->add('operationObjective', null, ['global_search' => true]);
        $datagridMapper->add('operationMeasureRef', null, ['global_search' => true]);
        $datagridMapper->add('measureReport', null, ['global_search' => true]);
        $datagridMapper->add('measureCert', null, ['global_search' => true]);
        $datagridMapper->add('measureDate', null, array(
            'field_type'=>DatePickerType::class,
            'global_search' => true
        ), null, [
            'dp_view_mode'          => 'days',
            'dp_min_view_mode'      => 'days',
            'format' => 'dd/MM/yyyy'
        ]);
        $datagridMapper->add('document', null, ['global_search' => true]);
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name')
                    ->add('caseReferance')
                    ->add('reportReference')
                    ->add('measureAuthor')
                    ->add('measureDate')
                    ->add('document', 'string', array('template' => 'LIST/list_url_upload_file.html.twig'))
                    ->add('_action', null, [
                    'actions' => [
                        'show' => [],
                        'edit' => [],
                        'delete' => [],
                        'repport' => [
                            'template' => 'CRUD/list__action_report.html.twig'
                        ],
                        'certificate' => [
                            'template' => 'CRUD/list__action_certificate.html.twig'
                        ],
                    ]
                ]);
    }

    /**
     * @param ShowMapper $showMapper
     */
    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->tab('General') // the tab call is optional
                ->add('caseReferance')
                ->add('reportReference')
                ->add('measureCompany')
                ->add('measureAuthor')
                ->add('info')
                ->add('operationAddress')
                ->add('operationCity')
                ->add('operationObjective')
                ->add('operationMeasureRef')
                ->add('measureReport')
                ->add('measureCert')
                ->add('measureDate')
                ->add('document')
                ->add('status')
            ->end()
        ;
    }

    /**
     * @param $operation
     * @throws \Exception
     */
    public function preValidate($operation)
    {
        $file = $this->getForm()->get('documents')->getData();
        if(!is_null($file)){
            $fileUploader = $this->container->get(FileUploader::class);
            $document = $fileUploader->upload($file);
            if($document){
                $operation->setDocument($document);
            }
        }
    }

    /**
     * name use on notification and flash bag
     *
     * @param mixed $object
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof Operation
            ? $object->getName()
            : 'Operation'; // shown in the breadcrumb on the create view
    }

    /**
     * @param $operation
     * @throws \Exception
     */
    public function postUpdate($operation)
    {
        parent::postUpdate($operation);
        $file = $this->getForm()->get('documents')->getData();
        if(!is_null($file)){
            $this->container->get('app.extract_data')->extractData($operation);
        }
    }

    /**
     * @param $operation
     * @throws \Exception
     */
    public function postPersist($operation)
    {
        parent::postPersist($operation); // TODO: Change the autogenerated stub
        $this->container->get('app.extract_data')->extractData($operation);
    }


}
