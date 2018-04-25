<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

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
                ->add('endOfValidity')
            ->end()
            ->with("Sonomètre")
                ->add('preamplifierType')
                ->add('preamplifierSerialNumber')
            ->end()
            ->with("Sonomètre")
                ->add('microphoneType')
                ->add('MicrophoneSerialNumber')
            ->end()
            ->with("Sonomètre")
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
