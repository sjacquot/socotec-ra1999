<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 04/04/2018
 * Time: 07:14
 */

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Serializer\Tests\Model;


class AgencyAdmin  extends AbstractAdmin
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
     * @throws \Exception
     */
    protected function configureFormFields(FormMapper $formMapper) {

            $formMapper
                ->with('Agence', array('class' => 'col-md-9', 'tab'=>true))
                    ->with("Agence")
                        ->add('name', null, ['label' => 'Nom'])
                        ->add('address', null, ['label' => 'Adresse'])
                        ->add('city', null, ['label' => 'Ville'])
                        ->add('cp', null, ['label' => 'Code postal'])
                        ->add('tel', null, ['label' => 'Téléphone'])
                        ->add('mail', null, ['label' => 'Adresse email de contact'])
                    ->end()
                ->end()
                //TODO: only the one already in the agency
                //TODO: change the label with all the data, look if html becon ok
                ->with("Matériel", array('class' => 'col-md-9', 'tab'=>true))
                    ->with("Sonomètre")
                        ->add('sonometer', ModelType::class, [
                            'btn_add' =>true,
                            'multiple' => true,
                            'expanded' => true,
                        ])
                    ->end()
                ->end();
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name', null, ['global_search' => true, 'label'=> 'Nom']);
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', null, ['label'=>'Nom'])
            ->add('city',null,['label'=>'Ville'])
            ->add('cp',null,['label'=>'Code postal'])
            ->add('tel',null,['label'=>'Téléphone'])
            ->add('mail',null,['label'=>'contact'])
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => []],
                ]);
    }

    /**
     * @param ShowMapper $showMapper
     */
    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->tab('General') // the tab call is optional
            ->add('name', null, ['label'=>'Nom'])
            ->add('city', null, ['label'=>'Ville'])
            ->end()
        ;
    }


}