<?php

namespace Yepsua\SecurityBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Yepsua\SecurityBundle\Entity\User;
use Yepsua\SecurityBundle\Form\UserType;
use Yepsua\SecurityBundle\Form\UserRequiredType;


use Yepsua\GeneratorBundle\UI\Grid;
use Yepsua\CommonsBundle\Persistence\Dao;
use Yepsua\CommonsBundle\IO\ObjectUtil;
use Yepsua\SmarTwigBundle\UI\Message\Notification;

use \YsJQuery as JQuery;
use \YsJQueryConstant as JQueryConstant;
use \YsGridResponse as GridResponse;
use \YsGridRow as GridRow;


/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Route("/", name="user")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $grid = new Grid('user','list.view.grid.title');
        $grid->setUrl($this->generateUrl('user_data'));
        $grid->setTranslator($this->get('translator'), 'SecurityBundleUser');
        $grid->createView();
        $grid->setEntityManager($this->getDoctrine()->getManager());
        
        $fields = array(
          'user.id' => array('hidden' => true),  
          'user.username' => 'Username',            
          'user.email' => 'Email',         
          'user.enabled' => array('title' => 'Enabled','width' => 75),   
          'user.lastLogin' => 'Lastlogin',    
          'user.locked' => array('title' => 'Locked','width' => 75),    
          'user.expired' => array('title' => 'Expired','width' => 75),     
          'user.roles' => 'Roles',                   
          'groups.id' => array('title' => 'Groups', 'association' => 'Yepsua\SecurityBundle\Entity\Group'),
        );
  
        $grid->setArrayGridField($fields);
        
        return array(
            'grid' => $grid
        );
    }
  
    /**
     * Public service - All User entities
     * @Route("/data", name="user_data")
     * @Method("GET")
     */
    public function dataAction()
    {
        try{
            JQuery::useComponent(JQueryConstant::COMPONENT_JQGRID);
            
            $em = $this->getDoctrine()->getManager();
            $orderBy = $this->getRequest()->get('sidx');
            $page = $this->getRequest()->get("page", 1);
            $rows = $this->getRequest()->get("rows", 1);
            $sord = $this->getRequest()->get('sord', 'ASC');
            $filters = $this->getRequest()->get('filters', null);
            $response = new GridResponse();
            
            $repository = $em->getRepository('YepsuaSecurityBundle:User');
            $count = Dao::count($repository);
            
            if($count > 0){
                $query = Dao::buildQuery($repository, 'user', $orderBy, $sord, $filters)
                            ->leftJoin('user.groups','groups');
                $query->setMaxResults($rows)->setFirstResult(($page - 1) * $rows);
                $entities = $query->getQuery()->getResult();
                
                foreach ($entities as $entitie){
                    $row = new GridRow();
                    $row->setId($entitie->getId());
                    $row->newCell($entitie->getId());
                    $row->newCell($entitie->getUsername());
                    $row->newCell($entitie->getEmail());
                    $row->newCell($entitie->isEnabled());
                    if($entitie->getLastLogin() !== null){
                        $row->newCell($entitie->getLastLogin()->format('Y-m-d H:i:s'));
                    }else{
                        $row->newCell($entitie->getLastLogin());
                    }
                    $row->newCell($entitie->isLocked());
                    $row->newCell($entitie->isExpired());
                    $row->newCell($entitie->getRoles());
                    $row->newCell(ObjectUtil::__toString__($entitie->getGroups()));
                    $response->addGridRow($row);
                }
            }
            
            $totalRows = $count / $rows;
            $totalRows = is_real($totalRows) ? intval($totalRows) + 1 : intval($totalRows);
            $response->setTotal($totalRows);
            $response->setPage($page);
            $response->setRecords($count);

            return new Response($response->buildResponseAsJSON());
        }catch(\Exception $e){
            $this->get('logger')->crit($e->getMessage());
            return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/create", name="user_create")
     * @Method("POST")
     * @Template("YepsuaSecurityBundle:User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        try{
            $entity  = new User();
            $form = $this->createForm(new UserRequiredType(), $entity);
            $form->bind($request);

            if ($form->isValid()) {
                
                //$manipulator = $this->get('fos_user.util.user_manipulator');
                //$manipulator->create($entity->getUsername(), $entity->getPassword(), $entity->getEmail(), true, false);
                
                $em = $this->getDoctrine()->getManager();
                $entity->setPlainPassword($entity->getPassword());
                $em->persist($entity);
                $em->flush();
                
                if($this->getRequest()->get('_loop_create')){
                    $form = $this->createForm(new UserType(), new User());
                    return $this->render('YepsuaSecurityBundle:User:new.html.twig',array(
                      'entity'      => $entity,
                      'form' => $form->createView())
                    ); 
                }else{
                    $deleteForm = $this->createDeleteForm($entity->getId());
                    return $this->render('YepsuaSecurityBundle:User:show.html.twig',array(
                      'entity' => $entity,
                      'delete_form' => $deleteForm->createView(),)
                    );
                }
            }
            
            return $this->render('YepsuaSecurityBundle:User:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ), new Response(null, 202));
                        
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new", name="user_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        try{
            $entity = new User();
            $form   = $this->createForm(new UserRequiredType() , $entity);

            return array(
                'entity' => $entity,
                'form'   => $form->createView(),
            );
        }catch(\Exception $e){
            $this->get('logger')->crit($e->getMessage());
            return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        try{
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('YepsuaSecurityBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity'      => $entity,
                'delete_form' => $deleteForm->createView(),
        );
        }catch(\Exception $e){
            $this->get('logger')->crit($e->getMessage());
            return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        try{
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('YepsuaSecurityBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $editForm = $this->createForm(new UserType(), $entity);
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}", name="user_update")
     * @Method("PUT")
     * @Template("YepsuaSecurityBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        try{
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('YepsuaSecurityBundle:User')->find($id);
            
            $password = $entity->getPassword();

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createForm(new UserType(), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                if($entity->getPassword() !== null){
                  $userManager = $this->get('fos_user.user_manager');
                  $entity->setPlainPassword($entity->getPassword());
                  $userManager->updateUser($entity);
                }else{
                  $entity->setPassword($password);
                  $em->persist($entity);
                }
                $em->flush();
                return $this->render('YepsuaSecurityBundle:User:show.html.twig',array(
                  'entity'      => $entity,
                  'delete_form' => $deleteForm->createView(),)
                );
            }

            return $this->render('YepsuaSecurityBundle:User:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ), new Response(null, 202));
            
        }catch(\Exception $e){
            $this->get('logger')->crit($e->getMessage());
            return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method({"DELETE", "POST"})
     */
    public function deleteAction(Request $request, $id)
    {
        try{
            if(strtoupper($request->getMethod()) == "DELETE"){
              $form = $this->createDeleteForm($id);
            }else{
              $form = $this->createDeleteForm($id,array('csrf_protection' => false));
            }
            $form->bind($request);
            $id = strpos($id,',') ? explode(',', $id) : array($id);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $entities = $em->getRepository('YepsuaSecurityBundle:User')->findById($id);

                foreach ($entities as $entity){
                  if (!$entity) {
                    throw $this->createNotFoundException('Unable to find User entity.');
                  }
                  $em->remove($entity);
                  $em->flush();
                }
            }
            return new Response();
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id, array $options = array())
    {
        return $this->createFormBuilder(array('id' => $id), $options)
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
