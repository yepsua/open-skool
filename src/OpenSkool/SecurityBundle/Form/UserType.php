<?php

namespace OpenSkool\SecurityBundle\SecurityBundle\Form;

use Yepsua\SecurityBundle\Form\UserType as BaseUserType;


class UserType extends BaseUserType
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
      $builder = parent::buildForm($builder, $options);
    }
}
