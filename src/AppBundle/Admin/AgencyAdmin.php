<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 04/04/2018
 * Time: 07:14
 */

namespace AppBundle\Admin;

use AppBundle\Entity\NoiseSource;
use AppBundle\Entity\ReverbAccessory;
use AppBundle\Entity\Shockmachine;
use AppBundle\Entity\Software;
use AppBundle\Entity\Sonometer;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Serializer\Tests\Model;


/**
 * \class AgencyAdmin
 * @package AppBundle\Admin
 */
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
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('batch');
        $collection->remove('export');
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

        $this->addElementOnEventBtnAdd();

        $sonometer = $this->getAgencySonometer();
        $noise_source = $this->getAgencyNoiseSources();
        $shockmachine = $this->getAgencyShockmachines();
        $revAcc = $this->getAgencyReverbAccessory();
        $soft = $this->getAgencySoftware();
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
            ->end();
        /** you cannot create and add materiel if the agency didn't has an id */
        if (!$this->isCurrentRoute('create')) {
            $formMapper
            ->with("Matériel", array('class' => 'col-md-9', 'tab' => true))
                ->with("Sonomètre")
                    ->add('sonometer', ModelType::class, [
                        'query' => $sonometer,
                        'btn_add' => true,
                        'multiple' => true,
                        'expanded' => true,
                        'label' => 'Sonomètre',
                    ])
                ->end()
                ->with("Source de bruit", array('class' => 'col-md-6'))
                ->add('noise_source', ModelType::class, [
                    'query' => $noise_source, //GET all sonometer with out agency and they must seleted
                    'btn_add' => true,
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Source de bruit',
                ])
                ->end()
                ->with("Machine à chocs", array('class' => 'col-md-6'))
                ->add('shockmachine', ModelType::class, [
                    'query' => $shockmachine, //GET all M.a.C with out agency and they must seleted
                    'btn_add' => true,
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Machine à chocs',
                ])
                ->end()
                ->with("Accessoire pour la mesure de durée de réverbération", array('class' => 'col-md-6'))
                ->add('reverb_accessory', ModelType::class, [
                    'query' => $revAcc, //GET all M.a.C with out agency and they must seleted
                    'btn_add' => true,
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Accessoire pour la mesure de durée de réverbération',
                ])
                ->end()
                ->with("Logiciel", array('class' => 'col-md-6'))
                ->add('software', ModelType::class, [
                    'query' => $soft, //GET all M.a.C with out agency and they must seleted
                    'btn_add' => true,
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Logiciel',
                ])
                ->end()
            ->end();
        }

    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name', null, ['global_search' => true, 'label'=> 'Nom']);
        $datagridMapper->add('city', null, ['global_search' => true, 'label'=> 'Ville']);
        $datagridMapper->add('mail', null, ['global_search' => true, 'label'=> 'Adresse email de contact']);
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
            ->add('mail',null,['label'=>'Adresse email de contact'])
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => []],
                ]);
        $listMapper->remove('batch');
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
            ->add('mail', null, ['label'=>'Adresse email de contact'])
            ->end()
        ;
    }

    /**
     * @return array
     */
    public function getFormTheme()
    {
        return array_merge(
            parent::getFormTheme(),
            array('theme.html.twig')
        );
    }

    /**
     * @param $object
     */
    public function preValidate($object)
    {
        if ($this->getForm()->has('sonometer')) {
            $dataSono = $this->getForm()->get('sonometer')->getData();
            foreach ($dataSono as $sono){
                $sono->setAgency($object);
            }
        }
        if ($this->getForm()->has('noise_source')) {
            $dataNS = $this->getForm()->get('noise_source')->getData();
            foreach ($dataNS as $NS){
                $NS->setAgency($object);
            }
        }
        if ($this->getForm()->has('shockmachine')) {
            $dataSM = $this->getForm()->get('shockmachine')->getData();
            foreach ($dataSM as $SM) {
                $SM->setAgency($object);
            }
        }
        if ($this->getForm()->has('reverb_accessory')) {
            $dataRevAcc = $this->getForm()->get('reverb_accessory')->getData();
            foreach ($dataRevAcc as $revAcc) {
                $revAcc->setAgency($object);
            }
        }
        if ($this->getForm()->has('software')) {
            $dataSoft = $this->getForm()->get('software')->getData();
            foreach ($dataSoft as $soft) {
                $soft->setAgency($object);
            }
        }
        parent::preValidate($object); // TODO: Change the autogenerated stub
    }

    /**
     * on add_btn action the sonometer is not add by default because choices get values
     * So we just add the last entries when the Js event is trigger and pass trough the form, witch add the nex sonometer
     *
     * @throws \Exception
     */
    public function addElementOnEventBtnAdd(){

        if ($this->request->get('_route') == 'sonata_admin_retrieve_form_element') {

            $em = $this->container->get('doctrine')->getEntityManager();
            // Sonometer
            $sonoRepo = $em->getRepository(Sonometer::class);

            if((isset($_GET['code']) && strpos($_GET['code'], 'sonometer')) OR (isset($_GET['elementId']) && strpos($_GET['elementId'], 'sonometer'))  ){

                $sono = $sonoRepo->findOneBy(
                    [],
                    array('id' => 'DESC')
                );
                $sono->setAgency($this->getSubject());
                $em->persist($sono);
                $em->flush();
            }
            // NoiseSource
            $NoiseSourceRepo = $em->getRepository(NoiseSource::class);

            if((isset($_GET['code']) && strpos($_GET['code'], 'noise_source')) OR (isset($_GET['elementId']) && strpos($_GET['elementId'], 'noise_source'))  ){

                $noiseSource = $NoiseSourceRepo->findOneBy(
                    [],
                    array('id' => 'DESC')
                );
                $noiseSource->setAgency($this->getSubject());
                $em->persist($noiseSource);
                $em->flush();
            }
            // Shockmachine
            $SmRepo = $em->getRepository(Shockmachine::class);

            if((isset($_GET['code']) && strpos($_GET['code'], 'shockmachine')) OR (isset($_GET['elementId']) && strpos($_GET['elementId'], 'shockmachine'))  ){

                $Sm = $SmRepo->findOneBy(
                    [],
                    array('id' => 'DESC')
                );
                $Sm->setAgency($this->getSubject());
                $em->persist($Sm);
                $em->flush();
            }
            // ReverbAccessory
            $RaRepo = $em->getRepository(ReverbAccessory::class);

            if((isset($_GET['code']) && strpos($_GET['code'], 'reverb_accessory')) OR (isset($_GET['elementId']) && strpos($_GET['elementId'], 'reverb_accessory'))  ){

                $RevAcc = $RaRepo->findOneBy(
                    [],
                    array('id' => 'DESC')
                );
                $RevAcc->setAgency($this->getSubject());
                $em->persist($RevAcc);
                $em->flush();
            }
            // Software
            $SoftwareRepo = $em->getRepository(Software::class);

            if((isset($_GET['code']) && strpos($_GET['code'], 'software')) OR (isset($_GET['elementId']) && strpos($_GET['elementId'], 'software'))  ){

                $soft = $SoftwareRepo->findOneBy(
                    [],
                    array('id' => 'DESC')
                );
                $soft->setAgency($this->getSubject());
                $em->persist($soft);
                $em->flush();
            }

        }
    }

    /**
     * Get the Sonometer from this agency to display in form
     *
     * @return mixed
     * @throws \Exception
     */
    public function getAgencySonometer(){
        $em = $this->container->get('doctrine')->getEntityManager();
        $sonoRepo = $em->getRepository(Sonometer::class);

        if ($this->isCurrentRoute('edit') || $this->request->get('_route') == 'sonata_admin_retrieve_form_element') {
            return $sonoRepo->createQueryBuilder('s')
                ->where('s.agency = :agency')
                ->setParameter('agency', $this->getSubject())
                ->getQuery();
        }
        return $sonoRepo->createQueryBuilder('s')
            ->where('s.agency IS NULL')
            ->getQuery();
    }
    /**
     * Get the NoiseSource from this agency to display in form
     *
     * @return mixed
     * @throws \Exception
     */
    public function getAgencyNoiseSources(){
        $em = $this->container->get('doctrine')->getEntityManager();
        $NoiseSourceRepo = $em->getRepository(NoiseSource::class);

        if ($this->isCurrentRoute('edit') || $this->request->get('_route') == 'sonata_admin_retrieve_form_element') {
            return $NoiseSourceRepo->createQueryBuilder('s')
                ->where('s.agency = :agency')
                ->setParameter('agency', $this->getSubject())
                ->getQuery();
        }
        return $NoiseSourceRepo->createQueryBuilder('s')
            ->where('s.agency IS NULL')
            ->getQuery();
    }
    /**
     * Get the NoiseSource from this agency to display in form
     *
     * @return mixed
     * @throws \Exception
     */
    public function getAgencyShockmachines(){
        $em = $this->container->get('doctrine')->getEntityManager();
        $ShockMachineRepo = $em->getRepository(Shockmachine::class);

        if ($this->isCurrentRoute('edit') || $this->request->get('_route') == 'sonata_admin_retrieve_form_element') {
            return $ShockMachineRepo->createQueryBuilder('s')
                ->where('s.agency = :agency')
                ->setParameter('agency', $this->getSubject())
                ->getQuery();
        }
        return $ShockMachineRepo->createQueryBuilder('s')
            ->where('s.agency IS NULL')
            ->getQuery();
    }
    /**
     * Get the reverbAccessory fro this agency to display in form
     *
     * @return mixed
     * @throws \Exception
     */
    public function getAgencyReverbAccessory(){
        $em = $this->container->get('doctrine')->getEntityManager();
        $RARepo = $em->getRepository(ReverbAccessory::class);

        if ($this->isCurrentRoute('edit') || $this->request->get('_route') == 'sonata_admin_retrieve_form_element') {
            return $RARepo->createQueryBuilder('s')
                ->where('s.agency = :agency')
                ->setParameter('agency', $this->getSubject())
                ->getQuery();
        }
        return $RARepo->createQueryBuilder('s')
            ->where('s.agency IS NULL')
            ->getQuery();
    }
    /**
     * Get the Software fro this agency to display in form
     *
     * @return mixed
     * @throws \Exception
     */
    public function getAgencySoftware(){
        $em = $this->container->get('doctrine')->getEntityManager();
        $SoftRepo = $em->getRepository(Software::class);

        if ($this->isCurrentRoute('edit') || $this->request->get('_route') == 'sonata_admin_retrieve_form_element') {
            return $SoftRepo->createQueryBuilder('s')
                ->where('s.agency = :agency')
                ->setParameter('agency', $this->getSubject())
                ->getQuery();
        }
        return $SoftRepo->createQueryBuilder('s')
            ->where('s.agency IS NULL')
            ->getQuery();
    }
}