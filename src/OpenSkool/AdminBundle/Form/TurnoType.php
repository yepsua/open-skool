<?php

namespace OpenSkool\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TurnoType extends AbstractType
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
            ->add('descripcion')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OpenSkool\AdminBundle\Entity\Turno',
            'translation_domain' => 'OpenSkoolAdminBundle_Turno'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'openskool_adminbundle_turno';
    }
}
