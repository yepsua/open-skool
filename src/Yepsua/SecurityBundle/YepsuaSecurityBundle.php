<?php

namespace Yepsua\SecurityBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class YepsuaSecurityBundle extends Bundle
{
    public function getParent() {
        return 'FOSUserBundle';
    }
}
