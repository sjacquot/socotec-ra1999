<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\DatePickerType;

class SonometerAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('type')
            ->add('serialNumber')
            ->add('preamplifierType')
            ->add('preamplifierSerialNumber')
            ->add('microphoneType')
            ->add('MicrophoneSerialNumber')
            ->add('calibratorType')
            ->add('calibratorSerialNumber')
            ->add('endOfValidity')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('type')
            ->add('serialNumber')
            ->add('preamplifierType')
            ->add('preamplifierSerialNumber')
            ->add('microphoneType')
            ->add('MicrophoneSerialNumber')
            ->add('calibratorType')
            ->add('calibratorSerialNumber')
            ->add('endOfValidity')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with("Sonomètre")
                ->add('type')
                ->add('serialNumber')
                ->add('endOfValidity', DatePickerType::class, array(
                    'required' => false,
                    'label' => 'Fin de validité',
                    'dp_side_by_side' => true,
                    'dp_use_current' => true,
                    'format' => 'dd/MM/yyyy',
                ))
            ->end()
            ->with("Préamplificateur")
                ->add('preamplifierType')
                ->add('preamplifierSerialNumber')
            ->end()
            ->with("Micro")
                ->add('microphoneType')
                ->add('MicrophoneSerialNumber')
            ->end()
            ->with("Calibreur")
                ->add('calibratorType')
                ->add('calibratorSerialNumber')
            ->end()
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('type')
            ->add('serialNumber')
            ->add('preamplifierType')
            ->add('preamplifierSerialNumber')
            ->add('microphoneType')
            ->add('MicrophoneSerialNumber')
            ->add('calibratorType')
            ->add('calibratorSerialNumber')
            ->add('endOfValidity')
        ;
    }
}
