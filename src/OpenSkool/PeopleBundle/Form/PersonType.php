<?php

namespace OpenSkool\PeopleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Yepsua\LOVBundle\Form\LOVType;
use Yepsua\SecurityBundle\Form\UserRequiredType;

class PersonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('idType', 'entity' , LOVType::_byGroupNameFormOptions('ID_TYPE'))
            ->add('idNumber')
            ->add('birthdate','ui_datepicker')
            ->add('user', new UserRequiredType())
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OpenSkool\PeopleBundle\Entity\Person'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'openskool_peoplebundle_person';
    }
}
