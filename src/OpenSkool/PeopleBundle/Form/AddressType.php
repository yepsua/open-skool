<?php

namespace OpenSkool\PeopleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Yepsua\LOVBundle\Form\EventListener\AddLOVEventSubscriber;
use Yepsua\LocalityBundle\Form\EventListener\AddLocalityEventSubscriber;

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
            ->add('country','entity', array('mapped' => false,'class' => 'YepsuaLocalityBundle:Country'))
            //->add('locality','entity', array('mapped' => false,'class' => 'YepsuaLocalityBundle:Locality'))
            ->add('city')
        ;
        
        $builder
            ->addEventSubscriber(new AddLOVEventSubscriber('PERSON_ADDRESS_TYPE'))
            ->addEventSubscriber(new AddLocalityEventSubscriber())   
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OpenSkool\PeopleBundle\Entity\Address'
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
