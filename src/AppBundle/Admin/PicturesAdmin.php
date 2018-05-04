<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 04/04/2018
 * Time: 07:14
 */

namespace AppBundle\Admin;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Serializer\Tests\Model;
use Sonata\AdminBundle\Route\RouteCollection;


/**
 * Class AgencyAdmin
 * @package AppBundle\Admin
 */
class PicturesAdmin  extends AbstractAdmin
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
     * @var EntityManager
     */
    protected $entityManager;

    protected $datagridValues = [
        '_sort_order' => 'ASC',
        '_sort_by' => 'position',
    ];

    /**
     * OperationAdmin constructor.
     * @param $code
     * @param $class
     * @param $baseControllerName
     * @param Container $container
     */
    public function __construct($code, $class, $baseControllerName, Container $container, EntityManager $entityManager)
    {
        parent::__construct($code, $class, $baseControllerName);

        /**
         * getFilter for list if there is the cookie
         */
        $this->container = $container;
        $this->entityManager = $entityManager;
    }
    /**
     * @return array
     */
    public function getExportFormats()
    {
        return null;
    }
    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'delete']);

//        $collection->add('delete', $this->getRouterIdParameter());
    }

    public function createQuery($context = 'list')
    {
        $id = $this->getRequest()->get('id');

        $query = parent::createQuery($context);
        $query->andWhere(
            $query->expr()->eq($query->getRootAliases()[0] . '.operation', ':operation')
        );
        $query->setParameter('operation', $id );
        return $query;

    }
    /**
     * @param FormMapper $formMapper
     * @throws \Exception
     */
    protected function configureFormFields(FormMapper $formMapper) {
//        $formMapper->add('name')->end();

    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        //$datagridMapper->add("name");
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {

            $listMapper
                ->remove('batch')
                ->add('name')
                ->add('position')
                ->add('operation')
                ->add('_action', null, [
                    'actions' => [
                        'delete' => [],
                    ],

                ]);

    }

    /**
     * @param ShowMapper $showMapper
     */
    public function configureShowFields(ShowMapper $showMapper)
    {
        //$showMapper->add('name')->end();
    }


}