<?php

namespace OpenSkool\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OpenSkool\AdminBundle\Entity\Instituto;
use OpenSkool\AdminBundle\Form\InstitutoType;

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
 * Instituto controller.
 *
 * @Route("/instituto")
 */
class InstitutoController extends Controller
{
    /**
     * Lists all Instituto entities.
     *
     * @Route("/", name="instituto")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $grid = new Grid('instituto','list.view.grid.title');
        $grid->setUrl($this->generateUrl('instituto_data'));
        $grid->setTranslator($this->get('translator'), 'OpenSkoolAdminBundle_Instituto');
        $grid->createView();
        $grid->setEntityManager($this->getDoctrine()->getManager());
        
        $fields = array(
          'instituto.id' => array('hidden' => true),      
          'instituto.codigo' => array('title' => 'Codigo', 'width' => 50, 'align' => 'center'),      
          'instituto.nombre' => array('title' => 'Nombre', 'width' => 250),      
          'instituto.acronimo' => 'Acronimo',   
          'instituto.descripcion' => 'Descripcion',
          'instituto.direccion' => 'Direccion',
          'imagen.id' => array('title' => 'Imagen', 'search' => false, 'association' => 'OpenSkool\AdminBundle\Entity\Imagen')
        );

        $grid->setArrayGridField($fields);
        
        return array(
            'grid' => $grid
        );
    }
  
    /**
     * Public service - All Instituto entities
     * @Route("/data", name="instituto_data")
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
            
            $repository = $em->getRepository('OpenSkoolAdminBundle:Instituto');
            $count = Dao::count($repository);
            
            if($count > 0){
                $query = Dao::buildQuery($repository, 'instituto', $orderBy, $sord, $filters);
                $query = $query->leftJoin('instituto.imagen','imagen');
                $query->setMaxResults($rows)->setFirstResult(($page - 1) * $rows);
                $entities = $query->getQuery()->getResult();
                
                foreach ($entities as $entitie){
                    $row = new GridRow();
                    $row->setId($entitie->getId());                                            
                    $row->newCell($entitie->getId());                                                                            
                    $row->newCell($entitie->getCodigo());                                                                            
                    $row->newCell($entitie->getNombre()); 
                    $row->newCell($entitie->getAcronimo()); 
                    $row->newCell($entitie->getDescripcion());                                                                                                    
                    $row->newCell($entitie->getDireccion());
                    $row->newCell(ObjectUtil::__toString__($entitie->getImagen()));    
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
     * Lists all Instituto entities.
     *
     * @Route("/kanban", name="instituto_kanban")
     * @Method("GET")
     * @Template()
     */
    public function kanbanAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OpenSkoolAdminBundle:Instituto')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Instituto entity.
     *
     * @Route("/create", name="instituto_create")
     * @Method("POST")
     * @Template("OpenSkoolAdminBundle:Instituto:new.html.twig")
     */
    public function createAction(Request $request)
    {
        try{
            $entity  = new Instituto();
            $form = $this->createForm(new InstitutoType(), $entity);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                
                $repository = $em->getRepository('OpenSkoolAdminBundle:Carrera');
                $repository->synchronizeInstitutos($entity);
                
                if($this->getRequest()->get('_loop_create')){
                    $form = $this->createForm(new InstitutoType(), new Instituto());
                    return $this->render('OpenSkoolAdminBundle:Instituto:new.html.twig',array(
                      'entity'      => $entity,
                      'form' => $form->createView())
                    ); 
                }else{
                    $deleteForm = $this->createDeleteForm($entity->getId());
                    return $this->render('OpenSkoolAdminBundle:Instituto:show.html.twig',array(
                      'entity' => $entity,
                      'delete_form' => $deleteForm->createView(),)
                    );
                }
            }
            
            return $this->render('OpenSkoolAdminBundle:Instituto:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ), new Response(null, 202));
                        
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Displays a form to create a new Instituto entity.
     *
     * @Route("/new", name="instituto_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('instituto'));
            }
            
            
            $entity = new Instituto();
            $form   = $this->createForm(new InstitutoType(), $entity);

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
     * Finds and displays a Instituto entity.
     *
     * @Route("/{id}", name="instituto_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('instituto'));
            }
            
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('OpenSkoolAdminBundle:Instituto')->find($id);

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
     * Displays a form to edit an existing Instituto entity.
     *
     * @Route("/{id}/edit", name="instituto_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('instituto'));
            }
            
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('OpenSkoolAdminBundle:Instituto')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $editForm = $this->createForm(new InstitutoType(), $entity);
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
     * Edits an existing Instituto entity.
     *
     * @Route("/{id}", name="instituto_update")
     * @Method("POST")
     * @Template("OpenSkoolAdminBundle:Instituto:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OpenSkoolAdminBundle:Instituto')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createForm(new InstitutoType(), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->render('OpenSkoolAdminBundle:Instituto:show.html.twig',array(
                  'entity'      => $entity,
                  'delete_form' => $deleteForm->createView(),)
                );
            }

            return $this->render('OpenSkoolAdminBundle:Instituto:edit.html.twig', array(
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
     * Deletes a Instituto entity.
     *
     * @Route("/{id}/delete", name="instituto_delete")
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
                $entities = $em->getRepository('OpenSkoolAdminBundle:Instituto')->findById($id);

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
