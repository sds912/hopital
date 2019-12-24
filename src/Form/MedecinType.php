<?php

namespace App\Form;

use App\Entity\Medecin;
use App\Entity\Service;
use App\Repository\MedecinRepository;
use App\Repository\ServiceRepository;
use App\Repository\SpecialiteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedecinType extends AbstractType
{
    private $serviceRepo;
    private $specialiteRepo;

    
    
    public function __construct(
    ServiceRepository $serviceRepo, 
    SpecialiteRepository $specialiteRepo
    
    
    )
        {
            $this->serviceRepo = $serviceRepo;
            $this->specialiteRepo = $specialiteRepo;  
        }
     /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('prenom',TextType::class,['required'=>false])
            ->add('nom',TextType::class, ['required'=>false])
            ->add('birthday', BirthdayType::class,[
                'widget' => 'single_text',
                'required'=>false
                ])
            ->add('email',EmailType::class, ['required'=>false])
            ->add('telephone',TelType::class, ['required'=>false]);
            $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
            $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    protected function addElements(FormInterface $form, Service $service = null) {
        // 4. Add the province element
        $form->add('service', EntityType::class, [
            'required' => true,
            'data' => $service,
            'placeholder' => 'Choisir un service...',
            'class' => 'App:Service',
            'required'=>false
        ]);

        $specialites = array();
        
        // If there is a city stored in the Person entity, load the neighborhoods of it
        if ($service) {
            // Fetch Neighborhoods of the City if there's a selected city
            //$specialiteRepo = $this->em->getRepository('App/Entity/Specialite');
            
            $specialites = $this->specialiteRepo->createQueryBuilder('s')
                ->where("s.service = :serviceid")
                ->setParameter("serviceid", $service->getId())
                ->getQuery()
                ->getResult();
        }

        $form->add('specialites', EntityType::class, array(
            'required' => true,
            'label' => 'Specialites',
            'class' => 'App:Specialite',
            'choices' => $specialites,
            'multiple' =>true,
            'by_reference' => false,
            'required'=>false
        ));


        

    }


    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        
        // Search for selected service and convert it into an Entity
        $service = $this->serviceRepo->find($data['service']);
        
        $this->addElements($form, $service);
    }

    function onPreSetData(FormEvent $event) {
        $medecin = $event->getData();
        $form = $event->getForm();

        // When you create a new person, the City is always empty
        $service = $medecin->getService() ? $medecin->getService() : null;
        
        $this->addElements($form, $service);
    }
    

    /**
     * {@inheritdoc}
     */

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Medecin::class,
        ]);
    }
}
