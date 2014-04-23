<?php

namespace OpenSkool\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Bundle\WebProfilerBundle\Controller\ProfilerController;
use FOS\UserBundle\Controller\SecurityController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Description of InsitutoListener
 *
 * @author omar.yepez@yepsua.com
 */
class InsitutoListener {
    
    protected $router;
    
    public function __construct($router)
    {
        $this->router = $router;
    }
    
    public function onKernelController(FilterControllerEvent $event)
    {
        /*$controller = $event->getController();
        
        if (!is_array($controller)) {
            return;
        }
        if (!$this->verifyController($controller[0])) {
            try {
                $request = $event->getRequest();
                $objInst = $request->getSession()->get('instituto');
                if($objInst === null){
                    $url = $this->router->generate('change_instituto');
                    $route = $request->get('_route');
                    if ($route !== 'change_instituto'){
                        $event->setController(function() use ($url) {
                            return new RedirectResponse($url);
                        }); 
                    }else{
                        return;
                    }

                }
            } catch (Exception $e) {
                return;
            }
        }*/
        
    }
    
    public function onKernelResponse(FilterResponseEvent $event)
    {
        /*$objInst = $event->getRequest()->getSession()->get('instituto');
        $route = $event->getRequest()->get('_route');
        if($objInst === null && $route !== 'change_instituto'){
            $url = $this->router->generate('change_instituto');
            $response = new RedirectResponse($url);
            //$event->setResponse($response);
        }*/
    }
    
    protected function verifyController($controller){
        return $controller instanceof ProfilerController || 
               $controller instanceof SecurityController;
    }
    
    public function setRouter($router) {
        $this->router = $router;
    }    
}