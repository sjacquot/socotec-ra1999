<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 02/03/2018
 * Time: 11:29
 */

namespace AppBundle\Admin;

use AppBundle\Entity\Operation;
use AppBundle\Service\FileUploader;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
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
            ->add('name')
            ->add('caseReferance',null)
            ->add('reportReference')
            ->end()
            ->with('Metadata', array('class' => 'col-md-9'))
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
                'dp_side_by_side'       => true,
                'dp_use_current'        => true,
                'format' => 'dd/MM/yyyy',
            ))

            ->add('document', EntityType::class, array(
                'multiple' => true,
                'required' => false,
                'label' => 'Documents already updated',
                'class' => 'AppBundle\Entity\Document',
            ))
            ->add('documents', FileType::class, array('data_class' => null, 'multiple' => true, 'required' => false, 'mapped' => false, 'label' => 'Add Document'))
            ->end()
        ;


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
                    ->add('status', 'choice', [
                        'editable' => true,
                        'class' => 'Vendor\ExampleBundle\Entity\ExampleStatus',
                        'choices' => [
                            1 => 'Active',
                            2 => 'Inactive',
                            3 => 'Draft',
                        ],
                    ])
                    ->add('_action', null, [
                    'actions' => [
                        'show' => [],
                        'edit' => [],
                        'delete' => [],
                    ]
                ]);
    }

    /**
     * @param $site
     * @throws \Exception
     */
    public function preValidate($site)
    {
        $files = $this->getForm()->get('documents')->getData();
        $fileUploader = $this->container->get(FileUploader::class);
        foreach ($files as $file) {
            $document = $fileUploader->upload($file);

            //TODO: traitement du doc
            //TODO: Je te conseil de le mettre dans une class appart pour le traitement
            //TODO: tu le met dans web/upload/doc et quand tu as le nom avec l'extension
            //TODO: tu fait
            // $document->setPathDocWord($fileName);
            // $this->entityManager->persist($document);
            // $this->entityManager->flush();

            if($document){
                $site->addDocument($document);
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
}
