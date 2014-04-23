<?php

namespace OpenSkool\LocalityBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OpenSkoolLocalityBundle extends Bundle
{
    public function getParent() {
        return 'YepsuaLocalityBundle';
    }
}
