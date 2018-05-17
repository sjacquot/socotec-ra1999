<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReverbAccessoryAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('type');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('type')
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
            ->with("Accessoires")
            ->add('type',ChoiceType::class,[
                'label'=>'Type d\'accessoire',
                'choices' => array(
                    'pistolet d’alarme 6 mm' => 0,
                    'pistolet d’alarme 9 mm' => 1,
                    'claquoir' => 2,
                    'ballons de baudruche' => 3,
                    'source de bruit rose' => 4
                ),
                'data'=> $this->getSubject()->getType(),
                'multiple' => false,
                'expanded' => false,
                'mapped' => true,
                'required' => true,
            ])
            ->end();
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('type',null ,array('label'=>'type'));
    }
}
