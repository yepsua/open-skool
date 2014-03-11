<?php

namespace Yepsua\SecurityBundle\Form;

class UserRequiredType extends UserType
{
    
    protected function isRequiredPassword(){
      return true;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'yepsua_securitybundle_user_required';
    }
}
