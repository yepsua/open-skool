<?php

namespace OpenSkool\PeopleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Yepsua\LOVBundle\Form\EventDispatcher\AddLOVEventSubscriber;

class AddressType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lineOne')
            ->add('lineTwo')
            ->add('zipcode')
        ;
        
        $suscriber = new \Yepsua\RADBundle\Form\EventDispatcher\RelatedEntityEventSubscriber();
        
        $suscriber->add('country', array(
          'class'=> 'YepsuaLocalityBundle:Country',
          'placeholder' => 'form.choice.empty.country'
        ));
        
        $suscriber->add('locality', array(
          'class'=> 'YepsuaLocalityBundle:Locality',
          'placeholder' => 'form.choice.empty.locality',
          'query_builder' => array(
              'method' => '_findAllByCountry',
              'args' => array(
                'data.getCity().getLocality().getCountry().getId()' => 0
              )
        )));
        
        $suscriber->add('city', array(
          'class'=> 'YepsuaLocalityBundle:City',
          'placeholder' => 'form.choice.empty.city',
          'query_builder' => array(
              'method' => '_findAllByLocality',
              'args' => array(
                'data.getCity().getLocality().getId()' => 0
              )
        )));
        
        $builder
            //->addEventSubscriber(new AddLocalityEventSubscriber())
            ->addEventSubscriber($suscriber)
            ->addEventSubscriber(new AddLOVEventSubscriber('PERSON_ADDRESS_TYPE'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OpenSkool\PeopleBundle\Entity\Address',
            'translation_domain' => 'OpenSkoolPeopleBundle_Address'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'openskool_peoplebundle_address';
    }
}
