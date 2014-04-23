<?php

namespace OpenSkool\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OpenSkool\AdminBundle\Entity\Asignatura;
use OpenSkool\AdminBundle\Form\AsignaturaType;

use Yepsua\RADBundle\Controller\Controller;
use Yepsua\GeneratorBundle\UI\Grid;
use Yepsua\CommonsBundle\Persistence\Dao;
use Yepsua\CommonsBundle\IO\ObjectUtil;
use Yepsua\SmarTwigBundle\UI\Message\Notification;

use \YsJQuery as JQuery;
use \YsJQueryConstant as JQueryConstant;
use \YsGridResponse as GridResponse;
use \YsGridRow as GridRow;


/**
 * Asignatura controller.
 *
 * @Route("/asignatura")
 * @author Omar Yepez <omar.yepez@yepsua.com>
 */
class AsignaturaController extends Controller
{
    /**
     * Lists all Asignatura entities.
     *
     * @Route("/", name="asignatura")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $grid = new Grid('asignatura','list.view.grid.title');
        $grid->setUrl($this->generateUrl('asignatura_data'));
        $grid->setTranslator($this->get('translator'), 'OpenSkoolAdminBundle_Asignatura');
        $grid->createView();
        $grid->setEntityManager($this->getDoctrine()->getManager());
        
        $fields = array(
          'asignatura.id' => array('hidden' => true),      
          'asignatura.codigo' => 'Codigo',      
          'asignatura.nombre' => 'Nombre',              
          'asignaturaPadre.id' => array('title' => 'Asignaturapadre', 'association' => 'OpenSkool\AdminBundle\Entity\Asignatura'),  
        );
  
        $grid->setArrayGridField($fields);
        
        return array(
            'grid' => $grid
        );
    }
  
    /**
     * Public service - All Asignatura entities
     * @Route("/data", name="asignatura_data")
     * @Method("GET")
     */
    public function dataAction()
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('asignatura'));
            }
            
            JQuery::useComponent(JQueryConstant::COMPONENT_JQGRID);
            $em = $this->getDoctrine()->getManager();
            $orderBy = $this->getRequest()->get('sidx');
            $page = $this->getRequest()->get("page", 1);
            $rows = $this->getRequest()->get("rows", 1);
            $sord = $this->getRequest()->get('sord', 'ASC');
            $filters = $this->getRequest()->get('filters', null);
            $response = new GridResponse();
            
            $repository = $em->getRepository('OpenSkoolAdminBundle:Asignatura');
            $count = Dao::count($repository);
            
            if($count > 0){
                $query = Dao::buildQuery($repository, 'asignatura', $orderBy, $sord, $filters);
                $query = $query->leftJoin('asignatura.asignaturaPadre','asignaturaPadre');
                $query->setMaxResults($rows)->setFirstResult(($page - 1) * $rows);
                $entities = $query->getQuery()->getResult();
                
                foreach ($entities as $entitie){
                    $row = new GridRow();
                    $row->setId($entitie->getId());
                    $row->newCell($entitie->getId());
                    $row->newCell($entitie->getCodigo());
                    $row->newCell($entitie->getNombre());          
                    $row->newCell(ObjectUtil::__toString__($entitie->getAsignaturapadre()));    
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
     * Creates a new Asignatura entity.
     *
     * @Route("/create", name="asignatura_create")
     * @Method("POST")
     * @Template("OpenSkoolAdminBundle:Asignatura:new.html.twig")
     */
    public function createAction(Request $request)
    {
        try{
            $entity  = new Asignatura();
            $form = $this->createForm(new AsignaturaType(), $entity);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                
                if($this->getRequest()->get('_loop_create')){
                    $form = $this->createForm(new AsignaturaType(), new Asignatura());
                    return $this->render('OpenSkoolAdminBundle:Asignatura:new.html.twig',array(
                      'entity'      => $entity,
                      'form' => $form->createView())
                    ); 
                }else{
                    $deleteForm = $this->createDeleteForm($entity->getId());
                    return $this->render('OpenSkoolAdminBundle:Asignatura:show.html.twig',array(
                      'entity' => $entity,
                      'delete_form' => $deleteForm->createView(),)
                    );
                }
            }
            
            return $this->render('OpenSkoolAdminBundle:Asignatura:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ), new Response(null, 202));
                        
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Displays a form to create a new Asignatura entity.
     *
     * @Route("/new", name="asignatura_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('asignatura'));
            }
        
            $entity = new Asignatura();
            $form   = $this->createForm(new AsignaturaType(), $entity);

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
     * Finds and displays a Asignatura entity.
     *
     * @Route("/{id}", name="asignatura_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('asignatura'));
            }
        
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('OpenSkoolAdminBundle:Asignatura')->find($id);

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
     * Displays a form to edit an existing Asignatura entity.
     *
     * @Route("/{id}/edit", name="asignatura_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('asignatura'));
            }
            
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('OpenSkoolAdminBundle:Asignatura')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $editForm = $this->createForm(new AsignaturaType(), $entity);
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
     * Edits an existing Asignatura entity.
     *
     * @Route("/{id}", name="asignatura_update")
     * @Method("PUT")
     * @Template("OpenSkoolAdminBundle:Asignatura:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OpenSkoolAdminBundle:Asignatura')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
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

    /**
     * Deletes a Asignatura entity.
     *
     * @Route("/{id}", name="asignatura_delete")
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
                $entities = $em->getRepository('OpenSkoolAdminBundle:Asignatura')->findById($id);

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
