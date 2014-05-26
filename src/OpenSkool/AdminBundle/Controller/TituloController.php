<?php

namespace OpenSkool\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OpenSkool\AdminBundle\Entity\Titulo;
use OpenSkool\AdminBundle\Form\TituloType;

use Yepsua\RADBundle\Controller\Controller;
use Yepsua\GeneratorBundle\UI\Grid;
use Yepsua\CommonsBundle\Persistence\Dao;
use Yepsua\SmarTwigBundle\UI\Message\Notification;

use \YsJQuery as JQuery;
use \YsJQueryConstant as JQueryConstant;
use \YsGridResponse as GridResponse;
use \YsGridRow as GridRow;


/**
 * Titulo controller.
 *
 * @Route("/titulo")
 */
class TituloController extends Controller
{
    /**
     * Lists all Titulo entities.
     *
     * @Route("/", name="titulo")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $grid = new Grid('titulo','list.view.grid.title');
        $grid->setUrl($this->generateUrl('titulo_data'));
        $grid->setTranslator($this->get('translator'), 'OpenSkoolAdminBundle_Titulo');
        $grid->createView();
        
        $fields = array(
          'titulo.id' => array('hidden' => true),      
          'titulo.codigo' => 'Codigo',      
          'titulo.nombre' => 'Nombre',      
          'titulo.descripcion' => 'Descripcion',      
          'titulo.abreviatura' => 'Abreviatura',        
        );
        
        //$grid->setOnRightClickRow(\YsJsFunction::newInstance("alert(rowid);jQuery('#tituloGrid').jqGrid('setSelection',rowid); e.preventDefault();return false;","rowid, iRow, iCol, e"));
  
        $grid->setArrayGridField($fields);
        
        return array(
            'grid' => $grid
        );
    }
  
    /**
     * Public service - All Titulo entities
     * @Route("/data", name="titulo_data")
     * @Method("GET")
     */
    public function dataAction()
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('titulo'));
            }
            
            JQuery::useComponent(JQueryConstant::COMPONENT_JQGRID);
            $em = $this->getDoctrine()->getManager();
            $orderBy = $this->getRequest()->get('sidx');
            $page = $this->getRequest()->get("page", 1);
            $rows = $this->getRequest()->get("rows", 1);
            $sord = $this->getRequest()->get('sord', 'ASC');
            $filters = $this->getRequest()->get('filters', null);
            $response = new GridResponse();
            
            $repository = $em->getRepository('OpenSkoolAdminBundle:Titulo');
            $count = Dao::count($repository);
            
            if($count > 0){
                $query = Dao::buildQuery($repository, 'titulo', $orderBy, $sord, $filters);
                $query->setMaxResults($rows)->setFirstResult(($page - 1) * $rows);
                $entities = $query->getQuery()->getResult();
                
                foreach ($entities as $entitie){
                    $row = new GridRow();
                    $row->setId($entitie->getId());
                    $row->newCell($entitie->getId());
                    $row->newCell($entitie->getCodigo());
                    $row->newCell($entitie->getNombre());
                    $row->newCell($entitie->getDescripcion());
                    $row->newCell($entitie->getAbreviatura());    
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
     * Creates a new Titulo entity.
     *
     * @Route("/create", name="titulo_create")
     * @Method("POST")
     * @Template("OpenSkoolAdminBundle:Titulo:new.html.twig")
     */
    public function createAction(Request $request)
    {
        try{
            $entity  = new Titulo();
            $form = $this->createForm(new TituloType(), $entity);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                
                if($this->getRequest()->get('_loop_create')){
                    $form = $this->createForm(new TituloType(), new Titulo());
                    return $this->render('OpenSkoolAdminBundle:Titulo:new.html.twig',array(
                      'entity'      => $entity,
                      'form' => $form->createView())
                    ); 
                }else{
                    $deleteForm = $this->createDeleteForm($entity->getId());
                    return $this->render('OpenSkoolAdminBundle:Titulo:show.html.twig',array(
                      'entity' => $entity,
                      'delete_form' => $deleteForm->createView(),)
                    );
                }
            }
            
            return $this->render('OpenSkoolAdminBundle:Titulo:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ), new Response(null, 202));
                        
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Displays a form to create a new Titulo entity.
     *
     * @Route("/new", name="titulo_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('titulo'));
            }
        
            $entity = new Titulo();
            $form   = $this->createForm(new TituloType(), $entity);

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
     * Finds and displays a Titulo entity.
     *
     * @Route("/{id}", name="titulo_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('titulo'));
            }
        
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('OpenSkoolAdminBundle:Titulo')->find($id);

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
     * Displays a form to edit an existing Titulo entity.
     *
     * @Route("/{id}/edit", name="titulo_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('titulo'));
            }
            
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('OpenSkoolAdminBundle:Titulo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $editForm = $this->createForm(new TituloType(), $entity);
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
     * Edits an existing Titulo entity.
     *
     * @Route("/{id}", name="titulo_update")
     * @Method("PUT")
     * @Template("OpenSkoolAdminBundle:Titulo:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OpenSkoolAdminBundle:Titulo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createForm(new TituloType(), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->render('OpenSkoolAdminBundle:Titulo:show.html.twig',array(
                  'entity'      => $entity,
                  'delete_form' => $deleteForm->createView(),)
                );
            }

            return $this->render('OpenSkoolAdminBundle:Titulo:edit.html.twig', array(
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
     * Deletes a Titulo entity.
     *
     * @Route("/{id}", name="titulo_delete")
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
                $entities = $em->getRepository('OpenSkoolAdminBundle:Titulo')->findById($id);

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
