<?php

namespace Utopia\ThemeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class UtopiaThemeBundle extends Bundle
{
    public function getParent()
    {
        return 'YepsuaThemeBundle';
    }
}
