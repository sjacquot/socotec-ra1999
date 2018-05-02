<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 02/03/2018
 * Time: 11:29
 */

namespace AppBundle\Admin;

use AppBundle\Entity\Aae;
use AppBundle\Entity\Aerien;
use AppBundle\Entity\Agency;
use AppBundle\Entity\Certificate;
use AppBundle\Entity\Equipement;
use AppBundle\Entity\NoiseSource;
use AppBundle\Entity\Operation;
use AppBundle\Entity\Pictures;
use AppBundle\Entity\Report;
use AppBundle\Entity\Results;
use AppBundle\Entity\ReverbAccessory;
use AppBundle\Entity\Shock;
use AppBundle\Entity\Shockmachine;
use AppBundle\Entity\Software;
use AppBundle\Entity\Sonometer;
use AppBundle\Service\ExtractData;
use AppBundle\Service\FileUploader;
use AppBundle\Service\PictureUploader;
use Doctrine\ORM\EntityRepository;
use Proxies\__CG__\AppBundle\Entity\Foreigner;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


/**
 * Class OperationAdmin
 * @package AppBundle\Admin
 */
class OperationAdmin extends Admin
{
    /**
     * @var array
     */
    protected $perPageOptions = array(16, 32, 64, 128, 192, 'All');
    /**
     * @var Container
     */
    protected $container;

    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by' => 'updatedAt',
    ];
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
        $collection->add('report', $this->getRouterIdParameter().'/report');
        $collection->add('certificate', $this->getRouterIdParameter().'/certificate');
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
    protected function configureFormFields(FormMapper $formMapper)
    {
        if ($this->isCurrentRoute('create')) {
            $formMapper
                ->with('Opération/Chantier', array('class' => 'col-md-9'))
                    ->add('caseReference', null, ['label' => 'Référence dossier', 'required' => true])
                    ->add('documents', FileType::class, array('data_class' => null, 'multiple' => false, 'required' => false, 'mapped' => false, 'label' => 'Ajouter une fiche de mesure'))
                    ->end()
                ->end();
        } else {
            $this->setFormFieldOperation($formMapper);
            $this->setFormFieldSocotec($formMapper);
            $this->setFormFieldMO_MOE($formMapper);
            $this->setFormFieldPCCalendar($formMapper);
            $this->setFormFieldAgency($formMapper);
            $this->setFormFieldPlan($formMapper);
        }

    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('agency', null, ['global_search' => true, 'label'=>'Agence']);
        $datagridMapper->add('measureAuthor', null, ['global_search' => true, 'label'=>'Auteur(s)']);
        $datagridMapper->add('measureDate', null, array(
            'field_type'=>DatePickerType::class,
            'global_search' => true, 'label'=>'Date de la mesure'
        ), null, [
            'dp_view_mode'          => 'days',
            'dp_min_view_mode'      => 'days',
            'format' => 'dd/MM/yyyy'
        ]);
        $datagridMapper->add('name', null, ['global_search' => true, 'label'=> 'Nom']);
        $datagridMapper->add('caseReference', null, ['global_search' => true, 'label'=>'Référence dossier']);
        $datagridMapper->add('operationCity', null, ['global_search' => true, 'label'=>'Ville']);
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', null, ['label'=>'Nom'])
                    ->add('caseReference',null,['label'=>'Référence dossier'])
                    ->add('measureAuthor',null,['label'=>'Auteur(s)'])
                    ->add('measureDate','date',['label'=>'Date de la mesure', 'format'=> 'd/m/Y'])
                    ->add('document', 'string', array('template' => 'LIST/list_url_upload_file.html.twig',null,'label'=>'Fiche de mesure (XLS)'))
                    ->add('_action', null, [
                    'actions' => [
                        'show' => [],
                        'edit' => [],
                        'delete' => [],
                        'report' => [
                            'template' => 'CRUD/list__action_report.html.twig'
                        ],
                        'certificate' => [
                            'template' => 'CRUD/list__action_certificate.html.twig'
                        ],
                    ]
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
                ->add('caseReference',null,['label'=>'Référence dossier'])
                ->add('measureCompany', null,['label'=>'Société'])
                ->add('measureAuthor', null,['label'=>'Auteur(s)'])
                ->add('info', null,['label'=>'Information'])
                ->add('operationAddress', null,['label'=>'Adresse'])
                ->add('operationCity', null,['label'=>'Ville'])
                ->add('operationObjective', null,['label'=>'Objectif'])
                ->add('operationMeasureRef', null,['label'=>'Référentiel de mesure'])
                ->add('measureReport', null,['label'=>'Rapport de mesure'])
                ->add('measureCert', null,['label'=>'Attestation'])
                ->add('measureDate', null,['label'=>'Date de mesure'])
                ->add('document', null,['label'=>'Fiche de mesure (XLS)'])
                ->add('status', null,['label'=>'Etat'])
            ->end()
        ;
    }

    /**
     * @param $operation
     * @throws \Exception
     */
    public function preValidate($operation)
    {
        if($this->getForm()->has('operationRoute')){
            $operationRoute = $this->getForm()->get('operationRoute')->getData();
            $operation->setOperationRoute300($operationRoute);
        }
        if($this->getForm()->has('operationTrain')){
            $operationTrain = $this->getForm()->get('operationTrain')->getData();
            $operation->setOperationTrain300($operationTrain);
        }
        if($this->getForm()->has('operationPEB')){
            $operationPEB = $this->getForm()->get('operationPEB')->getData();
            $operation->setOperationZonePEB($operationPEB);
        }

        $file = $this->getForm()->get('documents')->getData();
        if(!is_null($file)){
            $fileUploader = $this->container->get(FileUploader::class);
            $document = $fileUploader->upload($file);
            if($document){
                $operation->setDocument($document);
            }
        }

        if($this->getForm()->has('picturesUploaded')){
            $picutres = $this->getForm()->get('picturesUploaded')->getData();
            foreach($picutres as $picture){
                $fileUploader = $this->container->get(PictureUploader::class);
                $picture = $fileUploader->upload($picture, $operation);
            }
        }

        if(isset($_POST['upload_picture'])){
            $pictures = $_POST['upload_picture'];
            $em = $this->container->get('doctrine')->getEntityManager();
            $pictureRepo = $em->getRepository(Pictures::class);
            $i = 1;
            foreach($pictures as $picture) {
                $image = $pictureRepo->findOneByName($picture, $this->getSubject());
                if(!is_null($image)){
                    $image->setPosition($i);
                    $em->persist($image);
                    $i++;
                }
            }
            $em->flush();
        }
    }

    /**
     * name use on notification and flash bag
     *
     * @param mixed $object
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof Operation
            ? $object->getName()
            : 'Operation'; // shown in the breadcrumb on the create view
    }

    /**
     * @param $operation
     * @throws \Exception
     */
    public function postUpdate($operation)
    {
        parent::postUpdate($operation);
        $file = $this->getForm()->get('documents')->getData();
        if(!is_null($file)){
            $this->removeXLSData($operation);
            $this->container->get('app.extract_data')->extractData($operation);
        }
    }

    /**
     * @param $operation
     * @throws \Exception
     */
    public function postPersist($operation)
    {
        parent::postPersist($operation);
        if(!empty($operation->getDocument())){
            $this->container->get('app.extract_data')->extractData($operation);
        }
    }

    private function removeXLSData($operation){
        $em = $this->container->get('doctrine')->getEntityManager();
        $Arepo = $em->getRepository(Aerien::class);
        $AllA = $Arepo->findAllByOperation($operation);
        if(!is_null($AllA)) {
            foreach ($AllA as $A) {
                $em->remove($A);
            }
        }
        $Frepo = $em->getRepository(Foreigner::class);
        $AllF = $Frepo->findAllByOperation($operation);
        if(!is_null($AllF)){
            foreach ($AllF as $F){
                $em->remove($F);
            }
        }
        $Srepo = $em->getRepository(Shock::class);
        $AllS = $Srepo->findAllByOperation($operation);
        if(!is_null($AllS)){
            foreach ($AllS as $S){
                $em->remove($S);
            }
        }
        /*$ResRepo = $em->getRepository(Results::class);
        $R = $ResRepo->findOneByOperation($operation);
        if(!is_null($R)){
            $em->remove($R);
        }
        $AAERepo = $em->getRepository(Aae::class);
        $AAE = $AAERepo->findOneByOperation($operation);
        if(!is_null($AAE)){
            $em->remove($AAE);
        }
        $EquipRepo = $em->getRepository(Equipement::class);
        $Equip = $EquipRepo->findOneByOperation($operation);
        if(!is_null($Equip)){
            $em->remove($Equip);
        }*/
        $em->flush();

    }
    private function setFormFieldOperation(FormMapper $formMapper){
        $formMapper->with('Opération/Chantier', array('class' => 'col-md-9', 'tab'=>true))
            ->with('Fiches de mesure')
              ->add('documents', FileType::class, array('data_class' => null, 'multiple' => false, 'required' => false, 'mapped' => false, 'label' => 'Ajouter une fiche de mesure'))
            ->end()
            ->with('Coordonnées', array('class' => 'col-md-6'))
                ->add('name', null, ['label'=>"Nom de l'opération"])
                ->add('operationAddress',null,['label'=>"Adresse de l'opération"])
                ->add('operationCP',null,['label'=>"Code postal de l'opération"])
                ->add('operationCity',null,['label'=>"Commune de l'opération"])
            ->end()
            ->with('Logements et bâtiments', array('class' => 'col-md-6'))
                ->add('operationIndividuel',null,['label'=>'Logement individuel'])
                ->add('operationCollectif',null,['label'=>'Logement collectif'])
                ->add('operationNbIndividuel',null,['label'=>'Nombre de Logements individuels'])
                ->add('operationNbCollectif',null,['label'=>'Nombre de Logements collectifs'])
                ->add('operationNbBuilding',null,['label'=>'Nombre de bâtiments'])
                ->add('NbMeasure', null, ['label' => "Nombre de mesure minimum obligatoire"])
                ->add('operationLabel',null,['label'=>"Label, certification ou démarche qualité"])
                ->add('operationVMCSimple',null,array('label'=>"VMC simple flux"))
                ->add('operationVMCDouple',null,array('label'=>"VMC double flux"))
            ->end()
            ->with('Contexte', array('class' => 'col-md-6'))
                ->add('operationObjective',null,['label'=>'Objectif de la mesure'])
                ->add('operationMeasureRef',null,['label'=>'Référentiel de mesure'])
            ->end()
            ->with('Environnement/Voisinage',array('class' => 'col-md-6'))
                ->add('operationRoute',ChoiceType::class,[
                    'label'=>'Infrastructures de transport terrestre à moins de 300m',
                    'choices' => array(
                        1 => 1,
                        2 => 2,
                        3 => 3,
                        4 => 4,
                        5 => 5,
                        'Sans objet' => null
                    ),
                    'choice_attr' => [
                        'Sans objet' => ['data-info' => 'null'],
                    ],
                    'data'=> $this->getSubject()->getOperationRoute300(),
                    'multiple' => true,
                    'expanded' => true,
                    'mapped' => false,
                    'required' => false,
                ])
                ->add('operationPEB',ChoiceType::class,[
                    'label'=>"Zone de bruit du PEB d'un aérodrome",
                    'choices' => array(
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D',
                        'Sans objet' => null
                    ),
                    'choice_attr' => [
                        'Sans objet' => ['data-info' => 'null'],
                    ],
                    'data'=> $this->getSubject()->getOperationZonePEB(),
                    'multiple' => true,
                    'expanded' => true,
                    'mapped' => false,
                    'required' => false,
                ])
                ->end()
            ->end();
    }
    private function setFormFieldSocotec(FormMapper $formMapper){
        $formMapper
            ->with('SOCOTEC', array('class' => 'col-md-9', 'tab'=>true))
                ->with('Opération/SOCOTEC', array('class' => 'col-md-6'))
                    ->add('measureAuthor',null,['label'=>'Auteur(s) de la mesure'])
                    ->add('CompanySpeaker', null, ['label' => "Nom de l'interlocuteur SOCOTEC"])
                    ->add('DocAuthor', null, ['label' => "Nom auteur de l'attestation"])
                    ->add('DocAuthorEmail', null, ['label' => "Email auteur de l'attestation"])
                ->end()
                ->with('Opération/Informations', array('class' => 'col-md-6'))
                    ->add('caseReference', null, ['label' => 'Référence dossier'])
                    ->add('reportReference', EntityType::class, [
                        'label' => 'Référence du rapport de mesures détaillées',
                        'class' => Report::class,
                        'query_builder' => function (EntityRepository $er){
                            return $er->createQueryBuilder('r')
                                ->where('r.operation = :operation')
                                ->setParameter('operation', $this->getSubject());
                        }
                    ])
                    ->add('certifReference', EntityType::class, [
                        'label' => "Référence de l'attestation RA1999",
                        'class' => Certificate::class,
                        'query_builder' => function (EntityRepository $er){
                            return $er->createQueryBuilder('r')
                                ->where('r.operation = :operation')
                                ->setParameter('operation', $this->getSubject());
                        }
                    ])
                    ->add('measureCompany',null,['label'=>'Sociéte en charge de la mesure'])
                ->end()
            ->end();
    }

    private function setFormFieldMO_MOE(FormMapper $formMapper){
        $formMapper
        ->with("MO/MOE", array('class' => 'col-md-9', 'tab'=>true))
            ->with("Opération/Maîtrise d'Ouvrage", array('class' => 'col-md-6'))
                ->add("moName", null, ['label' => "Nom Maître d'Ouvrage"])
                ->add("moDest", null, ['label' => "Destinataire Maître d'Ouvrage"])
                ->add("moAddress", null, ['label' => "Adresse Maître d'Ouvrage"])
                ->add("moAddressComp", null, ['label' => "Complément d'adresse Maître d'Ouvrage"])
                ->add("moCP", null, ['label' => "Code postal Maître d'Ouvrage"])
                ->add("moCity", null, ['label' => "Commune Maître d'Ouvrage"])
                ->add("moTel", null, ['label' => "Tel Maître d'Ouvrage"])
                ->add("moEmail", null, ['label' => "Email Maître d'Ouvrage"])
            ->end()
            ->with("Opération/Intervenants & Equipe", array('class' => 'col-md-6'))
                ->add("delegateMO", null, ['label' => "Maître d'ouvrage délégué (le cas échéant)"])
                ->add("delegateMOAddress", null, ['label' => "Adresse Maître d'ouvrage délégué"])
                ->add("MEName", null, ['label' => "Nom du maître d'oeuvre"])
                ->add("MEAddress", null, ['label' => "Adresse du maître d'œuvre"])
                ->add("MEMission", null, ['label' => "Mission du maître d'œuvre"])
                ->add("OtherMEName", null, ['label' => "Nom autre maître d'œuvre"])
                ->add("OtherMEMission", null, ['label' => "Mission autre maître d'œuvre"])
                ->add("BETStructureName", null, ['label' => "Nom BET Structure"])
                ->add("BETStructureMission", null, ['label' => "Mission BET Structure"])
                ->add("BETFluidName", null, ['label' => "Nom BET Fluides"])
                ->add("BETFluidMission", null, ['label' => "Mission BET Fluides"])
                ->add("BETThermalName", null, ['label' => "Nom BET Thermique"])
                ->add("BETThermalMission", null, ['label' => "Mission BET Thermique"])
                ->add("BETAudioName", null, ['label' => "Nom BET Acoustique"])
                ->add("BETAudioMission", null, ['label' => "Mission BET acoustique"])
                ->add("OtherBET_AMOName", null, ['label' => "Nom autre BET ou AMO"])
                ->add("OtherBET_AMOMission", null, ['label' => "Mission autre BET ou AMO"])
            ->end()
        ->end();
    }

    private function setFormFieldPCCalendar(FormMapper $formMapper){
        $formMapper
        ->with('PC & Calendrier', array('class' => 'col-md-9', 'tab'=>true))
            ->with('Permis de construire', array('class' => 'col-md-6'))
            ->add("pcRequestDate", DatePickerType::class, array(
                'required' => false,
                'label' => 'Date dépôt demande du permis de construire',
                'dp_side_by_side' => true,
                'dp_use_current' => true,
                'format' => 'dd/MM/yyyy',
            ))
            ->add("pcReference", null, ['label' => "Numéro de permis de construire"])
            ->add("pcDate", DatePickerType::class, array(
                'required' => false,
                'label' => 'Date de délivrance du permis de construire',
                'dp_side_by_side' => true,
                'dp_use_current' => true,
                'format' => 'dd/MM/yyyy',
            ))
            ->add("pcNbPhase", null, ['label' => "Nombre de tranches de l'opération"])
            ->add("pcCurrentPhase", null, ['label' => "Numéro de tranche"])
            ->end()
            ->with("Calendrier de construction", array('class' => 'col-md-6'))
            ->add('calStartDate', DatePickerType::class, array(
                'required' => false,
                'label' => 'Date ouverture chantier',
                'dp_side_by_side' => true,
                'dp_use_current' => true,
                'format' => 'dd/MM/yyyy',
            ))
            ->add('calEndDate', DatePickerType::class, array(
                'required' => false,
                'label' => 'Date achèvement travaux',
                'dp_side_by_side' => true,
                'dp_use_current' => true,
                'format' => 'dd/MM/yyyy',
            ))
            ->end()
            ->end();
    }
    private function setFormFieldPlan(FormMapper $formMapper){
        $pictureResult = $this->container->get('doctrine')->getEntityManager()->getRepository(Pictures::class)->createQueryBuilder('r')
            ->where('r.operation = :operation')
            ->setParameter('operation', $this->getSubject())
            ->orderBy('r.position')->getQuery()->getResult();
        $pictureOrder = [];

        $i = 1;
        foreach ($pictureResult as $picture){
            $pictureOrder[$picture->getName()] = $picture->getName();
            $i++;
        }
        $formMapper
            ->with("Plans des locaux", array('class' => 'col-md-9', 'tab'=>true))
                ->add('picturesUploaded', FileType::class, array('data_class' => null, 'multiple' => true, 'required' => false, 'mapped' => false, 'label' => 'Ajouter une fiche de mesure'))
                ->add('picturesOrder', ChoiceType::class, [
                    'choices' =>  $pictureOrder,
                    'required' => false,
                    'mapped' => false,
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Ordre d\'affichage des plans'
                ])
            ->end()
            ->end();
    }

    private function setFormFieldAgency(FormMapper $formMapper){
        $this->agency = $this->getSubject()->getAgency();

        if(isset($_GET['agency'])){
            if(is_numeric($_GET['agency'])){
                $this->agency = $_GET['agency'];
            }
        }
        $formMapper
            ->with("Agence/Matériel", array('class' => 'col-md-9', 'tab'=>true))
            ->with('Agence')
            ->add('agency', EntityType::class, [
                'label' => "Agence",
                'class' => Agency::class,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('a')
                        ->where('1 = 1');
                }
            ])
            ->end()
            ->with('Matériels')
            ->add('sonometer', EntityType::class, [
                'class' => Sonometer::class,
                'query_builder' => function (EntityRepository $er){

                    $queryBuilder = $er->createQueryBuilder('a');
                    $query = $queryBuilder;

                    if(isset($_GET['agency']) && is_numeric($_GET['agency'])){
                        $this->agency = $_GET['agency'];

                        $query = $queryBuilder
                            ->where('a.agency = :agency')
                            ->setParameter('agency', $this->agency);
                    }

                    return $query;
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label' => "Sonomètre",
            ])
            ->add('noise_source', EntityType::class, [
                'class' => NoiseSource::class,
                'query_builder' => function (EntityRepository $er){

                    $queryBuilder = $er->createQueryBuilder('a');
                    $query = $queryBuilder;

                    if(isset($_GET['agency']) && is_numeric($_GET['agency'])){
                        $this->agency = $_GET['agency'];

                        $query = $queryBuilder
                            ->where('a.agency = :agency')
                            ->setParameter('agency', $this->agency);
                    }

                    return $query;
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label' => "Source de bruit",
            ])
            ->add('shockmachine', EntityType::class, [
                'class' => Shockmachine::class,
                'query_builder' => function (EntityRepository $er){

                    $queryBuilder = $er->createQueryBuilder('a');
                    $query = $queryBuilder;

                    if(isset($_GET['agency']) && is_numeric($_GET['agency'])){
                        $this->agency = $_GET['agency'];

                        $query = $queryBuilder
                            ->where('a.agency = :agency')
                            ->setParameter('agency', $this->agency);
                    }

                    return $query;
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label' => "Machine à chocs",
            ])
            ->add('reverb_accessory', EntityType::class, [
                'class' => ReverbAccessory::class,
                'query_builder' => function (EntityRepository $er){

                    $queryBuilder = $er->createQueryBuilder('a');
                    $query = $queryBuilder;

                    if(isset($_GET['agency']) && is_numeric($_GET['agency'])){
                        $this->agency = $_GET['agency'];

                        $query = $queryBuilder
                            ->where('a.agency = :agency')
                            ->setParameter('agency', $this->agency);
                    }

                    return $query;
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label' => "Accessoire pour la réverbération",
            ])
            ->add('software', EntityType::class, [
                'class' => Software::class,
                'query_builder' => function (EntityRepository $er){

                    $queryBuilder = $er->createQueryBuilder('a');
                    $query = $queryBuilder;

                    if(isset($_GET['agency']) && is_numeric($_GET['agency'])){
                        $this->agency = $_GET['agency'];

                        $query = $queryBuilder
                            ->where('a.agency = :agency')
                            ->setParameter('agency', $this->agency);
                    }

                    return $query;
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label' => "Logiciel",
            ])
            ->end()
            ->end();
    }
}
