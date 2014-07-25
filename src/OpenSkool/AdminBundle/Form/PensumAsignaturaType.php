<?php

namespace OpenSkool\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Yepsua\LOVBundle\Form\LOVType;

class PensumAsignaturaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo')
            ->add('codigoCurricular')
            ->add('electiva',null,array('required' => false))
            ->add('unidadesCredito')
            ->add('horasTeoricas')
            ->add('horasPracticas')
            ->add('tipoUnidadCurricular', 'entity' , LOVType::_byGroupNameFormOptions('UNIDAD_CURRICULAR_TYPE'))
            ->add('pensum')
            ->add('asignatura')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OpenSkool\AdminBundle\Entity\PensumAsignatura'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'openskool_adminbundle_pensumasignatura';
    }
}
