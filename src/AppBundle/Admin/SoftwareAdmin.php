<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * \class SoftwareAdmin
 * @package AppBundle\Admin
 */
class SoftwareAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name',null,['label'=>'nom'])
            ->add('brand',null,['label'=>'marque']);

    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name',null,['label'=>'nom'])
            ->add('version')
            ->add('brand',null,['label'=>'marque'])
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
            ->with("Logiciel")
            ->add('name',ChoiceType::class,[
                'label'=>'Nom',
                'choices' => array(
                    'dBBati' => 'dBBati',
                    'dBTrait' => 'dBTrait',
                    'dBInside' => 'dBInside',
                    'NorReview' => 'NorReview',
                    'NorBuild' => 'NorBuild'
                ),
                'data'=> $this->getSubject()->getName(),
                'multiple' => false,
                'expanded' => false,
                'mapped' => true,
                'required' => true,
            ])
            ->add('version')
            ->add('brand',ChoiceType::class,[
                'label'=>'Marque',
                'choices' => array(
                    'NORSONIC' => 'NORSONIC',
                    '01dB-acoem' => '01dB-acoem',
                ),
                'data'=> $this->getSubject()->getBrand(),
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
            ->add('name',null,['label'=>'nom'])
            ->add('version')
            ->add('brand',null,['label'=>'marque']);
    }
}
