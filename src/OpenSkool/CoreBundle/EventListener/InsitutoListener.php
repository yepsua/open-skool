<?php

namespace OpenSkool\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\WebProfilerBundle\Controller\ProfilerController;
use FOS\UserBundle\Controller\SecurityController;
/**
 * Description of InsitutoListener
 *
 * @author omar.yepez@yepsua.com
 */
class InsitutoListener {
    
    /**
     *
     * @var Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected $router;
    
    protected $forceRedirect;
    
    public function __construct($router)
    {
        $this->router = $router;
    }
    
    public function onKernelController(FilterControllerEvent $event)
    {
      $controller = $event->getController();
      $this->forceRedirect = false;
      if ($this->verifyController($controller[0]) && !$event->getRequest()->isXmlHttpRequest()) {
        $this->forceRedirect = true;
      }
    }
    
    public function onKernelResponse(FilterResponseEvent $event)
    {
      if($this->forceRedirect && !$event->getRequest()->getSession()->has('instituto')){
        $event->setResponse(new RedirectResponse($this->router->generate('core')));
      }
    }
    
    protected function verifyController($controller){
        return ! ($controller instanceof \OpenSkool\AdminBundle\Controller\InstitutoController 
               || $controller instanceof \OpenSkool\CoreBundle\Controller\WelcomeController
               || $controller instanceof \Symfony\Bundle\WebProfilerBundle\Controller\ProfilerController
               || $controller instanceof \FOS\UserBundle\Controller\SecurityController);
    }
    
    public function setRouter($router) {
        $this->router = $router;
    }
}