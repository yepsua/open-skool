<?php

namespace OpenSkool\AdminBundle\Form;

use Yepsua\RADBundle\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PensumType extends AbstractType
{     
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('instituto')
            ->add('carrera')
            ->add('pensumPadre')
            /*->add('asignaturas', 'ui_entity', array(
              'class' => 'OpenSkoolAdminBundle:Asignatura',
              'multiple' => true,
              'widget_options' => array(
                'height' => 250
              )
            ))*/
        ;
        if ($this->isFormTypeNew()){
            $builder->add('CreatePlanEstudio','checkbox', array('mapped' => false,'required' => false));
            $builder->add('CodigoPlanEstudio',null, array('mapped' => false, 'required' => false));
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OpenSkool\AdminBundle\Entity\Pensum',
            'translation_domain' => 'OpenSkoolAdminBundle_Pensum'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'openskool_adminbundle_pensum';
    }
}
