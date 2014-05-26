<?php

namespace Yepsua\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    private $user;
  
    public function __construct($user = null){
      $this->user = $user;
    }
  
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('password', 'repeated', array(
                'type' => 'password',
                'required' => $this->isRequiredPassword(),
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'RepeatPassword'),
            ))
            ->add('enabled')
            ->add('groups')
        ;
    }
    
    protected function isRequiredPassword(){
      return false;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yepsua\SecurityBundle\Entity\User',
            'translation_domain' => 'user'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yepsua_securitybundle_user';
    }
}
