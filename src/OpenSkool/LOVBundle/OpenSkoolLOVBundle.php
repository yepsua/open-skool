<?php

namespace OpenSkool\LOVBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OpenSkoolLOVBundle extends Bundle
{
    public function getParent()
    {
        return 'YepsuaLOVBundle';
    }
}
