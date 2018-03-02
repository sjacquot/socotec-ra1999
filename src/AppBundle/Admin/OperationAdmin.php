<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 02/03/2018
 * Time: 11:29
 */

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;


class OperationAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
/*        $formMapper->add('name', 'text');
        $formMapper->add('caseReferance', 'text');
        $formMapper->add('reportReference', 'text');
*/
        $formMapper
            ->with('Operation/Chantier', array('class' => 'col-md-9'))
            ->add('name')
            ->add('caseReferance',null,)
            ->add('reportReference')
            ->end()
            ->with('Metadata', array('class' => 'col-md-9'))
            ->add('status')
            ->add('createdAt')
            ->end()
        ;


    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
        $datagridMapper->add('caseReferance');
        $datagridMapper->add('reportReference');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name');
        $listMapper->add('caseReferance');
        $listMapper->add('reportReference');
    }
}