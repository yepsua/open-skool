<?php

namespace OpenSkool\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CoreController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $repo = $this->getDoctrine()
            ->getManager()
            ->getRepository('OpenSkoolAdminBundle:Instituto')
        ;
        
        if($repo->isDataNoFound()){
             return $this->render('OpenSkoolCoreBundle:Core:no_instituto.html.twig');
        }

        return array();
    }
}
