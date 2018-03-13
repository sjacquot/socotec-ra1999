<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 02/03/2018
 * Time: 11:29
 */

namespace AppBundle\Admin;

use AppBundle\Entity\Operation;
use AppBundle\Service\ExtractData;
use AppBundle\Service\FileUploader;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Sonata\AdminBundle\Form\ChoiceList;


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
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        if ($this->isCurrentRoute('create')) {
            $formMapper
                ->with('Opération/Chantier', array('class' => 'col-md-9'))
                ->add('caseReference', null, ['label' => 'Référence dossier'])
//                ->add('reportReference', null, ['label' => 'Référence rapport'])
/*                ->add('document', EntityType::class, array(
                    'multiple' => false,
                    'required' => false,
                    'label' => 'Fiche de mesure (XLS)',
                    'class' => 'AppBundle\Entity\Document',
                ))*/
                ->add('documents', FileType::class, array('data_class' => null, 'multiple' => false, 'required' => false, 'mapped' => false, 'label' => 'Ajouter une fiche de mesure'))
                ->end();
        }
        if ($this->isCurrentRoute('edit')) {
            $formMapper
                ->with('Chantier', array('class' => 'col-md-9', 'tab'=>true))
                ->with('Opération/Chantier')
                ->add('documents', FileType::class, array('data_class' => null, 'multiple' => false, 'required' => false, 'mapped' => false, 'label' => 'Ajouter une fiche de mesure'))
                ->add('name', null, ['label'=>"Nom de l'opération"])
                ->add('operationAddress',null,['label'=>"Adresse de l'opération"])
                ->add('operationCP',null,['label'=>"Code postal de l'opération"])
                ->add('operationCity',null,['label'=>"Commune de l'opération"])
                ->add('operationIndividuel',null,['label'=>'Logement individuel'])
                ->add('operationCollectif',null,['label'=>'Logement collectif'])

                ->add('operationNbIndividuel',null,['label'=>'Nombre de Logements individuels'])
                ->add('operationNbCollectif',null,['label'=>'Nombre de Logements collectifs'])

               // ->add('operationNbFlat',null,['label'=>'Nombre de logements'])
                ->add('operationNbBuilding',null,['label'=>'Nombre de bâtiments'])

                ->add('NbMeasure', null, ['label' => "Nombre de mesure minimum obligatoire"])
                ->add('operationRoute300',null,['label'=>'Classement de la ou des voies routières à moins de 300m'])
                ->add('operationTrain300',null,['label'=>'Classement de la ou des voies ferrées à moins de 300m'])
                ->add('operationZonePEB',null,['label'=>"Zone de bruit du PEB d'un aérodrome"])
                ->add('operationLabel',null,['label'=>"Label, certification ou démarche qualité"])
                ->add('operationVMCSimple',null,array('label'=>"VMC simple flux"))
                ->add('operationVMCDouple',null,array('label'=>"VMC double flux"))

                ->add('operationObjective',null,['label'=>'Objectif de la mesure'])
                ->add('operationMeasureRef',null,['label'=>'Référentiel de mesure'])
                ->end()
                ->end()
                ->with('SOCOTEC', array('class' => 'col-md-9', 'tab'=>true))
                ->with('Opération/SOCOTEC')
                ->add('caseReference', null, ['label' => 'Référence dossier'])
                ->add('DocChronoRef', null, ['label' => 'Numéro de chrono du dossier'])
                ->add('reportReference', null, ['label' => 'Référence du rapport de mesures détaillées'])
                ->add('CertifReference', null, ['label' => "Référence de l'attestation RA1999"])
                ->add('measureCompany',null,['label'=>'Sociéte en charge de la mesure'])
                ->add('measureAuthor',null,['label'=>'Auteur(s) de la mesure'])
                ->add('CompanySpeaker', null, ['label' => "Nom de l'interlocuteur SOCOTEC"])
                ->add('DocAuthor', null, ['label' => "Nom auteur de l'attestation"])
                ->add('DocAuthorEmail', null, ['label' => "Email auteur de l'attestation"])
                ->end()
                ->end()
                ->with("Maîtrise d'Ouvrage", array('class' => 'col-md-9', 'tab'=>true))
                ->with("Opération/Maîtrise d'Ouvrage")
                ->add("moName", null, ['label' => "Nom Maître d'Ouvrage"])
                ->add("moDest", null, ['label' => "Destinataire Maître d'Ouvrage"])
                ->add("moAddress", null, ['label' => "Adresse Maître d'Ouvrage"])
                ->add("moAddressComp", null, ['label' => "Complément d'adresse Maître d'Ouvrage"])
                ->add("moCP", null, ['label' => "Code postal Maître d'Ouvrage"])
                ->add("moCity", null, ['label' => "Commune Maître d'Ouvrage"])
                ->add("moTel", null, ['label' => "Tel Maître d'Ouvrage"])
                ->add("moEmail", null, ['label' => "Email Maître d'Ouvrage"])
                ->end()->end()
                ->with('Permis de construire', array('class' => 'col-md-9', 'tab'=>true))
                ->with('Opération/Permis de construire')
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
                ->end()->end()
                ->with("Calendrier de construction", array('class' => 'col-md-9', 'tab'=>true))
                ->with("Opération/Calendrier de construction")
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
                ->end()->end()
                ->with("Intervenants & Equipe", array('class' => 'col-md-9', 'tab'=>true))
                ->with("Opération/Intervenants & Equipe")
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


    }
    // - ZONE DEPOT CODE EN COURS DE REFONTE
//                ->add('status')
    //              ->add('info',null,['label'=>'Informations'])
//                ->add('measureReport',null,['label'=>'Rapport de mesure détaillé'])
//                ->add('measureCert',null,['label'=>'Attestation de conformité'])
    /*                ->add('measureDate', DatePickerType::class, array(
                        'required' => false,
                        'label' => 'Date de la mesure',
                        'dp_side_by_side' => true,
                        'dp_use_current' => true,
                        'format' => 'dd/MM/yyyy',
                    ))*/
    // - /ZONE DEPOT CODE EN COURS DE REFONTE

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name', null, ['global_search' => true, 'label'=> 'Nom']);
        $datagridMapper->add('caseReference', null, ['global_search' => true, 'label'=>'Référence dossier']);
        $datagridMapper->add('reportReference',null,['global_search' => true, 'label'=>'Référence rapport']);
        $datagridMapper->add('certifReference',null,['global_search' => true, 'label'=>'Référence rapport']);
        $datagridMapper->add('document', null, ['global_search' => true, 'label'=>'Fiche de mesure (XLS)']);

        $datagridMapper->add('status', null, ['global_search' => true, 'label'=>'Etat de traitement']);
        $datagridMapper->add('measureCompany', null, ['global_search' => true, 'label'=>'Société']);
        $datagridMapper->add('measureAuthor', null, ['global_search' => true, 'label'=>'Auteur(s)']);
        $datagridMapper->add('info', null, ['global_search' => true, 'label'=>'informations']);
        $datagridMapper->add('operationAddress', null, ['global_search' => true, 'label'=>'Adresse']);
        $datagridMapper->add('operationCity', null, ['global_search' => true, 'label'=>'Ville']);
        $datagridMapper->add('operationObjective', null, ['global_search' => true, 'label'=>'Objectif']);
        $datagridMapper->add('operationMeasureRef', null, ['global_search' => true, 'label'=>'Référentiel mesure']);
        $datagridMapper->add('measureReport', null, ['global_search' => false, 'label'=>'Rapport détaillé']);
        $datagridMapper->add('measureCert', null, ['global_search' => false, 'label'=>'Attestation']);
        $datagridMapper->add('measureDate', null, array(
            'field_type'=>DatePickerType::class,
            'global_search' => true, 'label'=>'Date de la mesure'
        ), null, [
            'dp_view_mode'          => 'days',
            'dp_min_view_mode'      => 'days',
            'format' => 'dd/MM/yyyy'
        ]);
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name', null, ['label'=>'Nom'])
                    ->add('caseReference',null,['label'=>'Référence dossier'])
                    ->add('reportReference',null,['label'=>'Référence rapport'])
                    ->add('certifReference',null,['label'=>'Référence certif'])
                    ->add('measureAuthor',null,['label'=>'Auteur(s)'])
                    ->add('measureDate',null,['label'=>'Date de la mesure'])
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
                ->add('reportReference', null,['label'=>'Référence Rapport'])
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
        $file = $this->getForm()->get('documents')->getData();
        if(!is_null($file)){
            $fileUploader = $this->container->get(FileUploader::class);
            $document = $fileUploader->upload($file);
            if($document){
                $operation->setDocument($document);
            }
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
            $this->container->get('app.extract_data')->extractData($operation);
        }
    }

    /**
     * @param $operation
     * @throws \Exception
     */
    public function postPersist($operation)
    {
        parent::postPersist($operation); // TODO: Change the autogenerated stub
        $this->container->get('app.extract_data')->extractData($operation);
    }


}
