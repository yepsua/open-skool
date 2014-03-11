<?php

namespace OpenSkool\ThemeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OpenSkoolThemeBundle extends Bundle
{
    public function getParent()
    {
        return 'YepsuaThemeBundle';
    }
}
