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
            ->add('type',null,['label'=>'type'])
            ->add('serialNumber',null,['label'=>'N° de série'])
            ->add('preamplifierType',null,['label'=>'Préamplificateur : type'])
            ->add('preamplifierSerialNumber',null,['label'=>'Préamplificateur : N° de série'])
            ->add('microphoneType',null,['label'=>'Micro : type'])
            ->add('MicrophoneSerialNumber',null,['label'=>'Micro : N° de série'])
            ->add('calibratorType',null,['label'=>'Calibreur : type'])
            ->add('calibratorSerialNumber',null,['Calibreur : label'=>'N° de série'])
            ->add('endOfValidity',null,['label'=>'Fin de validité métrologique', 'format'=> 'm/Y'])
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('type',null,['label'=>'type'])
            ->add('serialNumber',null,['label'=>'N° de série'])
            ->add('preamplifierType',null,['label'=>'Préamplificateur : type'])
            ->add('preamplifierSerialNumber',null,['label'=>'Préamplificateur : N° de série'])
            ->add('microphoneType',null,['label'=>'Micro : type'])
            ->add('MicrophoneSerialNumber',null,['label'=>'Micro : N° de série'])
            ->add('calibratorType',null,['label'=>'Calibreur : type'])
            ->add('calibratorSerialNumber',null,['Calibreur : label'=>'N° de série'])
            ->add('endOfValidity',null,['label'=>'Fin de validité métrologique', 'format'=> 'm/Y'])
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
                ->add('type',null,['label'=>'type'])
                ->add('serialNumber',null,['label'=>'N° de série'])
                ->add('endOfValidity', DatePickerType::class, array(
                    'required' => false,
                    'label' => 'Fin de validité métrologique',
                    'dp_side_by_side' => true,
                    'dp_use_current' => true,
                    'format' => 'MM/yyyy',
                ))
            ->end()
            ->with("Préamplificateur")
                ->add('preamplifierType',null,['label'=>'type'])
                ->add('preamplifierSerialNumber',null,['label'=>'N° de série'])
            ->end()
            ->with("Micro")
                ->add('microphoneType',null,['label'=>'type'])
                ->add('MicrophoneSerialNumber',null,['label'=>'N° de série'])
            ->end()
            ->with("Calibreur")
                ->add('calibratorType',null,['label'=>'type'])
                ->add('calibratorSerialNumber',null,['label'=>'N° de série'])
            ->end()
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with("Sonomètre")
            ->add('type',null,['label'=>'type'])
            ->add('serialNumber',null,['label'=>'N° de série'])
            ->add('endOfValidity',null,['label'=>'Fin de validité métrologique', 'format'=> 'm/Y'])
            ->end()
            ->with("Préamplificateur")
            ->add('preamplifierType',null,['label'=>'type'])
            ->add('preamplifierSerialNumber',null,['label'=>'N° de série'])
            ->end()
            ->with("Micro")
                ->add('microphoneType',null,['label'=>'type'])
                ->add('MicrophoneSerialNumber',null,['label'=>'N° de série'])
            ->end()
            ->with("Calibreur")
                ->add('calibratorType',null,['label'=>'type'])
                ->add('calibratorSerialNumber',null,['label'=>'N° de série'])
            ->end();
    }
}
