<?php

namespace OpenSkool\PeopleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use OpenSkool\PeopleBundle\Form\PersonType;
use OpenSkool\PeopleBundle\Entity\Person;

/**
 * Person controller.
 *
 * @Route("/person")
 */
class PersonController extends Controller
{
    /**
     * Show the user detail.
     *
     * @Route("/detail", name="user_detail")
     * @Method("GET")
     * @Template()
     */
    public function detailAction()
    {
        
        /*$userDetail = $this->getUser()->getUserDetail();
        if($userDetail === null){
            $userDetail = new \OpenSkool\PeopleBundle\Entity\Person();
            $userDetail->setFirstName('omar');
            $userDetail->setLastName('yepez');
            $userDetail->setIdType('1');
            $userDetail->setIdNumber(17961911);
            $userDetail->setBirthdate(new \DateTime());
            $user = $this->getUser();
            $user->setUserDetail($userDetail);
            $userDetail->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($userDetail);
            $em->flush();
        }else{
            echo 'nombre:'. $userDetail->getFirstName() . '<br/>';
            echo 'apellido:'. $userDetail->getLastName();
        }
        */
        $userDetail = $this->getUser()->getUserDetail();
        
        if($userDetail !== null){
            $frmUserDetail = $this->createForm(new PersonType(), $userDetail);
        }else{
            $frmUserDetail = $this->createForm(new PersonType(), new Person());
        }
        
        return array('form' => $frmUserDetail->createView());
    }
    
    /**
     * Save a Persona entity.
     *
     * @Route("/{id}", name="save_user_detail")
     * @Method("PUT")
     * @Template("OpenSkoolAdminBundle:Asignatura:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OpenSkoolAdminBundle:Asignatura')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Asignatura entity.');
            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createForm(new AsignaturaType(), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->render('OpenSkoolAdminBundle:Asignatura:show.html.twig',array(
                  'entity'      => $entity,
                  'delete_form' => $deleteForm->createView(),)
                );
            }

            return $this->render('OpenSkoolAdminBundle:Asignatura:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ), new Response(null, 202));
            
        }catch(\Exception $e){
            $this->get('logger')->crit($e->getMessage());
            return new Response(Notification::error($e->getMessage()), 203);
        }
    }
}
