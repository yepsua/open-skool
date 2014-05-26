<?php

namespace OpenSkool\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use OpenSkool\CoreBundle\Util\Constant;

class CoreController extends Controller
{
    /**
     * @Route("/", name="core")
     * @Template()
     */
    public function indexAction()
    {
        $repo = $this->getDoctrine()
            ->getManager()
            ->getRepository('OpenSkoolAdminBundle:Instituto')
        ;
        
        if($repo->isDataNotFound()){
          $this->get('session')->remove(Constant::INSTITUTO_SESSION_NAME);
          return $this->render('OpenSkoolCoreBundle:Core:noInstituto.html.twig');
        }
        
        $objInst = $this->get('session')->get(Constant::INSTITUTO_SESSION_NAME);
        
        if($objInst === null){
            return $this->forward('OpenSkoolCoreBundle:Core:changeInstituto');
        }
        return array();
    }
    
    /**
     * @Route("/instituto/change", name="change_instituto")
     * @Method("GET")
     * @Template()
     */
    public function changeInstitutoAction(){
        $repo = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('OpenSkoolAdminBundle:Instituto');
        
        $arrInst = $repo->getInstitutos();
        
        if(sizeof($arrInst) === 1 ){
            $this->getRequest()->getSession()->set(Constant::INSTITUTO_SESSION_NAME, $arrInst[0]);
            return $this->render('OpenSkoolCoreBundle:Core:index.html.twig');
        }
        
        if(sizeof($arrInst) === 0){
          $this->get('session')->remove(Constant::INSTITUTO_SESSION_NAME);
          return $this->render('OpenSkoolCoreBundle:Core:noInstituto.html.twig');
        }
        
        return array('institutos' => $arrInst);
    }
    
    /**
     * @Route("/instituto/change/{id}", name="changed_instituto")
     * @Method("GET")
     */
    public function changedInstitutoAction($id){
        
        $repo = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('OpenSkoolAdminBundle:Instituto');
        $instituto = $repo->findOneByCriteria(array('id' => $id));
        if($instituto !== null){
            $this->get('session')->set(Constant::INSTITUTO_SESSION_NAME, $instituto);
            $_in = $this->get('session')->get(Constant::INSTITUTO_SESSION_NAME);
            if( $_in->getImagen()!== null){
                $_in->getImagen()->getURL();
            }
        }else{
            return $this->forward('OpenSkoolCoreBundle:Core:changeInstituto');
        }
        return $this->redirect($this->generateUrl('core'));
    }
}
