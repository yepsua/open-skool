<?php

namespace OpenSkool\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('OpenSkoolAdminBundle:Welcome:index.html.twig');
    }
}
