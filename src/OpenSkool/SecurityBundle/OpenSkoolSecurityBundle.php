<?php

namespace OpenSkool\SecurityBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OpenSkoolSecurityBundle extends Bundle
{
    public function getParent() {
        return 'YepsuaSecurityBundle';
    }
}
