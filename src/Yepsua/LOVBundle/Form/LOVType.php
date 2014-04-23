<?php

namespace Yepsua\LOVBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class LOVType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('group')
            ->add('value')
            ->add('description')
            ->add('order_by')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yepsua\LOVBundle\Entity\LOV'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yepsua_lovbundle_lov';
    }
    
    public static function _byGroupNameFormOptions($groupId){
      return array(
        'class' => 'YepsuaLOVBundle:LOV',
        'query_builder' => function(EntityRepository $er) use ($groupId) {
            return $er->_findAllByGroupName($groupId);
      });
    }
}
