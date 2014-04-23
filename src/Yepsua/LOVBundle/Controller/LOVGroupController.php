<?php

namespace Yepsua\LOVBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Yepsua\LOVBundle\Entity\LOVGroup;
use Yepsua\LOVBundle\Form\LOVGroupType;

use Yepsua\RADBundle\Controller\Controller;
use Yepsua\GeneratorBundle\UI\Grid;
use Yepsua\CommonsBundle\Persistence\Dao;
use Yepsua\SmarTwigBundle\UI\Message\Notification;

use \YsJQuery as JQuery;
use \YsJQueryConstant as JQueryConstant;
use \YsGridResponse as GridResponse;
use \YsGridRow as GridRow;


/**
 * LOVGroup controller.
 *
 * @Route("/list_of_values_group")
 */
class LOVGroupController extends Controller
{
    /**
     * Lists all LOVGroup entities.
     *
     * @Route("/", name="list_of_values_group")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $grid = new Grid('lovgroup','list.view.grid.title');
        $grid->setUrl($this->generateUrl('list_of_values_group_data'));
        $grid->setTranslator($this->get('translator'), 'YepsuaLOVBundle_LOVGroup');
        $grid->createView();
        
        $fields = array(
          'lovgroup.id' => array('hidden' => true),      
          'lovgroup.groupName' => 'Groupname',        
        );
  
        $grid->setArrayGridField($fields);
        
        return array(
            'grid' => $grid
        );
    }
  
    /**
     * Public service - All LOVGroup entities
     * @Route("/data", name="list_of_values_group_data")
     * @Method("GET")
     */
    public function dataAction()
    {
        try{
            $request = $this->getRequest();
            if(!$request->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('list_of_values_group'));
            }
            
            JQuery::useComponent(JQueryConstant::COMPONENT_JQGRID);
            $em = $this->getDoctrine()->getManager();
            $orderBy = $request->get('sidx');
            $page = $request->get("page", 1);
            $rows = $request->get("rows", 1);
            $sord = $request->get('sord', 'ASC');
            $filters = $request->get('filters', null);
            $response = new GridResponse();
            
            $repository = $em->getRepository('YepsuaLOVBundle:LOVGroup');
            $count = Dao::count($repository);
            
            if($count > 0){
                $query = Dao::buildQuery($repository, 'lovgroup', $orderBy, $sord, $filters);
                $query->setMaxResults($rows)->setFirstResult(($page - 1) * $rows);
                $entities = $query->getQuery()->getResult();
                
                foreach ($entities as $entitie){
                    $row = new GridRow();
                    $row->setId($entitie->getId());
                    $row->newCell($entitie->getId());
                    $row->newCell($entitie->getGroupName());    
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
     * Creates a new LOVGroup entity.
     *
     * @Route("/create", name="list_of_values_group_create")
     * @Method("POST")
     * @Template("YepsuaLOVBundle:LOVGroup:new.html.twig")
     */
    public function createAction(Request $request)
    {
        try{
            $entity  = new LOVGroup();
            $form = $this->createForm(new LOVGroupType(), $entity);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                
                if($this->getRequest()->get('_loop_create')){
                    $form = $this->createForm(new LOVGroupType(), new LOVGroup());
                    return $this->render('YepsuaLOVBundle:LOVGroup:new.html.twig',array(
                      'entity'      => $entity,
                      'form' => $form->createView())
                    ); 
                }else{
                    $deleteForm = $this->createDeleteForm($entity->getId());
                    return $this->render('YepsuaLOVBundle:LOVGroup:show.html.twig',array(
                      'entity' => $entity,
                      'delete_form' => $deleteForm->createView(),)
                    );
                }
            }
            
            return $this->render('YepsuaLOVBundle:LOVGroup:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ), new Response(null, 202));
                        
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Displays a form to create a new LOVGroup entity.
     *
     * @Route("/new", name="list_of_values_group_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('list_of_values_group'));
            }
        
            $entity = new LOVGroup();
            $form   = $this->createForm(new LOVGroupType(), $entity);

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
     * Finds and displays a LOVGroup entity.
     *
     * @Route("/{id}", name="list_of_values_group_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('list_of_values_group'));
            }
        
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('YepsuaLOVBundle:LOVGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
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
     * Displays a form to edit an existing LOVGroup entity.
     *
     * @Route("/{id}/edit", name="list_of_values_group_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('list_of_values_group'));
            }
            
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('YepsuaLOVBundle:LOVGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $editForm = $this->createForm(new LOVGroupType(), $entity);
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
     * Edits an existing LOVGroup entity.
     *
     * @Route("/{id}", name="list_of_values_group_update")
     * @Method("PUT")
     * @Template("YepsuaLOVBundle:LOVGroup:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('YepsuaLOVBundle:LOVGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createForm(new LOVGroupType(), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->render('YepsuaLOVBundle:LOVGroup:show.html.twig',array(
                  'entity'      => $entity,
                  'delete_form' => $deleteForm->createView(),)
                );
            }

            return $this->render('YepsuaLOVBundle:LOVGroup:edit.html.twig', array(
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
     * Deletes a LOVGroup entity.
     *
     * @Route("/{id}", name="list_of_values_group_delete")
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
                $entities = $em->getRepository('YepsuaLOVBundle:LOVGroup')->findById($id);

                foreach ($entities as $entity){
                  if (!$entity) {
                    throw $this->createNotFoundException('msg.unable.to.find.entity');
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
}
