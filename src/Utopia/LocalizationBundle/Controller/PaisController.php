<?php

namespace Utopia\LocalizationBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Utopia\LocalizationBundle\Entity\Pais;
use Utopia\LocalizationBundle\Form\PaisType;

use Yepsua\GeneratorBundle\UI\Grid;
use Yepsua\CommonsBundle\Persistence\Dao;
use Yepsua\SmarTwigBundle\UI\Message\Notification;

use \YsJQuery as JQuery;
use \YsJQueryConstant as JQueryConstant;
use \YsGridResponse as GridResponse;
use \YsGridRow as GridRow;


/**
 * Pais controller.
 *
 * @Route("/pais")
 */
class PaisController extends Controller
{
    /**
     * Lists all Pais entities.
     *
     * @Route("/", name="pais")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $grid = new Grid('pais','list.view.grid.title');
        $grid->setUrl($this->generateUrl('pais_data'));
        $grid->setTranslator($this->get('translator'), 'UtopiaLocalizationBundle_Pais');
        $grid->createView();
        
        $fields = array(
          'pais.id' => array('hidden' => true),      
          'pais.nombre' => 'Nombre',        );
  
        $grid->setArrayGridField($fields);
        
        return array(
            'grid' => $grid
        );
    }
  
    /**
     * Public service - All Pais entities
     * @Route("/data", name="pais_data")
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
            
            $repository = $em->getRepository('UtopiaLocalizationBundle:Pais');
            $count = Dao::count($repository);
            
            if($count > 0){
                $query = Dao::buildQuery($repository, 'pais', $orderBy, $sord, $filters);
                $query->setMaxResults($rows)->setFirstResult(($page - 1) * $rows);
                $entities = $query->getQuery()->getResult();
                
                foreach ($entities as $entitie){
                    $row = new GridRow();
                    $row->setId($entitie->getId());                                            
                    $row->newCell($entitie->getId());                                                                            
                    $row->newCell($entitie->getNombre());    
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
     * Lists all Pais entities.
     *
     * @Route("/kanban", name="pais_kanban")
     * @Method("GET")
     * @Template()
     */
    public function kanbanAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UtopiaLocalizationBundle:Pais')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Pais entity.
     *
     * @Route("/create", name="pais_create")
     * @Method("POST")
     * @Template("UtopiaLocalizationBundle:Pais:new.html.twig")
     */
    public function createAction(Request $request)
    {
        try{
            $entity  = new Pais();
            $form = $this->createForm(new PaisType(), $entity);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                
                if($this->getRequest()->get('_loop_create')){
                    $form = $this->createForm(new PaisType(), new Pais());
                    return $this->render('UtopiaLocalizationBundle:Pais:new.html.twig',array(
                      'entity'      => $entity,
                      'form' => $form->createView())
                    ); 
                }else{
                    $deleteForm = $this->createDeleteForm($entity->getId());
                    return $this->render('UtopiaLocalizationBundle:Pais:show.html.twig',array(
                      'entity' => $entity,
                      'delete_form' => $deleteForm->createView(),)
                    );
                }
            }
            
            return $this->render('UtopiaLocalizationBundle:Pais:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ), new Response(null, 202));
                        
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Displays a form to create a new Pais entity.
     *
     * @Route("/new", name="pais_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('pais'));
            }
        
            $entity = new Pais();
            $form   = $this->createForm(new PaisType(), $entity);

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
     * Finds and displays a Pais entity.
     *
     * @Route("/{id}", name="pais_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('pais'));
            }
        
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('UtopiaLocalizationBundle:Pais')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pais entity.');
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
     * Displays a form to edit an existing Pais entity.
     *
     * @Route("/{id}/edit", name="pais_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('pais'));
            }
            
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('UtopiaLocalizationBundle:Pais')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pais entity.');
            }

            $editForm = $this->createForm(new PaisType(), $entity);
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
     * Edits an existing Pais entity.
     *
     * @Route("/{id}", name="pais_update")
     * @Method("POST")
     * @Template("UtopiaLocalizationBundle:Pais:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UtopiaLocalizationBundle:Pais')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pais entity.');
            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createForm(new PaisType(), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->render('UtopiaLocalizationBundle:Pais:show.html.twig',array(
                  'entity'      => $entity,
                  'delete_form' => $deleteForm->createView(),)
                );
            }

            return $this->render('UtopiaLocalizationBundle:Pais:edit.html.twig', array(
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
     * Deletes a Pais entity.
     *
     * @Route("/{id}/delete", name="pais_delete")
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
                $entities = $em->getRepository('UtopiaLocalizationBundle:Pais')->findById($id);

                foreach ($entities as $entity){
                  if (!$entity) {
                    throw $this->createNotFoundException('Unable to find Pais entity.');
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
     * Creates a form to delete a Pais entity by id.
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
