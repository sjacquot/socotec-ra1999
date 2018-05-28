<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * \class ShockmachineAdmin
 * @package AppBundle\Admin
 */
class ShockmachineAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('brand',null ,array('label'=>'marque'))
            ->add('type')
            ->add('serialNumber',null ,array('label'=>'Numéro de série'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('brand',null ,array('label'=>'marque'))
            ->add('type')
            ->add('serialNumber',null ,array('label'=>'Numéro de série'))
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
            ->with("Machine à chocs")
            ->add('brand',null ,array('label'=>'marque'))
            ->add('type')
            ->add('serialNumber',null ,array('label'=>'Numéro de série'))
            ->end();
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('brand',null ,array('label'=>'marque'))
            ->add('type')
            ->add('serialNumber',null ,array('label'=>'Numéro de série'))
        ;
    }
}
