<?php

namespace OpenSkool\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstitutoType extends AbstractType
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
            ->add('acronimo')
            ->add('descripcion','textarea')
            ->add('direccion','textarea')
            ->add('imagenFile')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OpenSkool\AdminBundle\Entity\Instituto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'openskool_adminbundle_instituto';
    }
}
