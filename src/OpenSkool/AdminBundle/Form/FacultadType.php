<?php

namespace OpenSkool\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FacultadType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo')
            ->add('nombre')
            ->add('instituto')
            ->add('carrera')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OpenSkool\AdminBundle\Entity\Facultad',
            'translation_domain' => 'OpenSkoolAdminBundle_Facultad'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'openskool_adminbundle_facultad';
    }
}
