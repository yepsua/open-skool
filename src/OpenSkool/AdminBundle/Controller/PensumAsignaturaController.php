<?php

namespace OpenSkool\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\Request,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
  
    OpenSkool\AdminBundle\Entity\PensumAsignatura,
    OpenSkool\AdminBundle\Form\PensumAsignaturaType,

    Yepsua\RADBundle\Controller\Controller,
    Yepsua\GeneratorBundle\UI\Grid,
    Yepsua\CommonsBundle\Persistence\Dao,
    Yepsua\CommonsBundle\IO\ObjectUtil,
    Yepsua\SmarTwigBundle\UI\Message\Notification,

    \YsJQuery as JQuery,
    \YsJQueryConstant as JQueryConstant,
    \YsGridResponse as GridResponse,
    \YsGridRow as GridRow;
    
/**
 * PensumAsignatura controller.
 *
 * @Route("/pensum_asignatura")
 */
class PensumAsignaturaController extends Controller
{
    const REPOSITORY_NAMESPACE = 'OpenSkoolAdminBundle:PensumAsignatura';
    
    /**
     * Lists all PensumAsignatura entities.
     *
     * @Route("/", name="pensum_asignatura")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $grid = new Grid('pensumasignatura','list.view.grid.title');
        $grid->setUrl($this->generateUrl('pensum_asignatura_data'));
        $grid->setTranslator($this->get('translator'), 'OpenSkoolAdminBundle_PensumAsignatura');
        $grid->createView();
        $grid->setEntityManager($this->getDoctrine()->getManager());
        
        $fields = array(
          'pensumasignatura.id' => array('hidden' => true),      
          'pensumasignatura.codigo' => 'Codigo',      
          'pensumasignatura.codigoCurricular' => 'Codigocurricular',      
          'pensumasignatura.electiva' => array('title' => 'Electiva','formatter' => 'checkbox'),      
          'pensumasignatura.unidadesCredito' => 'Unidadescredito',                 
          'pensum.id' => array('title' => 'Pensum', 'association' => 'OpenSkool\AdminBundle\Entity\Pensum'),        
          'asignatura.id' => array('title' => 'Asignatura', 'association' => 'OpenSkool\AdminBundle\Entity\Asignatura'),  
        );
  
        $grid->setArrayGridField($fields);
        
        return array(
            'grid' => $grid
        );
    }
  
    /**
     * Public service - All PensumAsignatura entities
     * @Route("/data", name="pensum_asignatura_data")
     * @Method("GET")
     */
    public function dataAction()
    {
        try{
            $request = $this->getRequest();
            if(!$request->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('pensum_asignatura'));
            }
            
            JQuery::useComponent(JQueryConstant::COMPONENT_JQGRID);
            $orderBy = $request->get('sidx');
            $page = $request->get("page", 1);
            $rows = $request->get("rows", 1);
            $sord = $request->get('sord', 'ASC');
            $filters = $request->get('filters', null);
            $response = new GridResponse();
            
            $repository = $this->getEntityRepository();
            $count = Dao::count($repository);
            
            if($count > 0){
                $query = Dao::buildQuery($repository, 'pensumasignatura', $orderBy, $sord, $filters);
                $query = $query->leftJoin('pensumasignatura.pensum','pensum');
                $query = $query->leftJoin('pensumasignatura.asignatura','asignatura');
                $query->setMaxResults($rows)->setFirstResult(($page - 1) * $rows);
                $entities = $query->getQuery()->getResult();
                
                foreach ($entities as $entitie){
                    $row = new GridRow();
                    $row->setId($entitie->getId());
                    $row->newCell($entitie->getId());
                    $row->newCell($entitie->getCodigo());
                    $row->newCell($entitie->getCodigoCurricular());      
                    $row->newCell($entitie->isElectiva());
                    $row->newCell($entitie->getUnidadesCredito());          
                    $row->newCell(ObjectUtil::__toString__($entitie->getPensum()));          
                    $row->newCell(ObjectUtil::__toString__($entitie->getAsignatura()));    
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
     * Creates a new PensumAsignatura entity.
     *
     * @Route("/create", name="pensum_asignatura_create")
     * @Method("POST")
     * @Template("OpenSkoolAdminBundle:PensumAsignatura:new.html.twig")
     */
    public function createAction(Request $request)
    {
        try{
            $entity  = new PensumAsignatura();
            $form = $this->createForm(new PensumAsignaturaType(), $entity);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();
                
                if($this->getRequest()->get('_loop_create')){
                    $form = $this->createForm(new PensumAsignaturaType(), new PensumAsignatura());
                    return $this->render('OpenSkoolAdminBundle:PensumAsignatura:new.html.twig',array(
                      'entity'      => $entity,
                      'form' => $form->createView())
                    ); 
                }else{
                    $deleteForm = $this->createDeleteForm($entity->getId());
                    return $this->render('OpenSkoolAdminBundle:PensumAsignatura:show.html.twig',array(
                      'entity' => $entity,
                      'delete_form' => $deleteForm->createView(),)
                    );
                }
            }
            
            return $this->render('OpenSkoolAdminBundle:PensumAsignatura:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ), new Response(null, 202));
                        
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Displays a form to create a new PensumAsignatura entity.
     *
     * @Route("/new", name="pensum_asignatura_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('pensum_asignatura'));
            }
        
            $entity = new PensumAsignatura();
            $form   = $this->createForm(new PensumAsignaturaType(), $entity);

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
     * Finds and displays a PensumAsignatura entity.
     *
     * @Route("/{id}", name="pensum_asignatura_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('pensum_asignatura'));
            }
            
            $entity = $this->getEntityRepository()->find($id);

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
     * Displays a form to edit an existing PensumAsignatura entity.
     *
     * @Route("/{id}/edit", name="pensum_asignatura_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('pensum_asignatura'));
            }

            $entity = $this->getEntityRepository()->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $editForm = $this->createForm(new PensumAsignaturaType(), $entity);
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
     * Edits an existing PensumAsignatura entity.
     *
     * @Route("/{id}", name="pensum_asignatura_update")
     * @Method("PUT")
     * @Template("OpenSkoolAdminBundle:PensumAsignatura:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        try{
            $entity = $this->getEntityRepository()->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createForm(new PensumAsignaturaType(), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();

                return $this->render('OpenSkoolAdminBundle:PensumAsignatura:show.html.twig',array(
                  'entity'      => $entity,
                  'delete_form' => $deleteForm->createView(),)
                );
            }

            return $this->render('OpenSkoolAdminBundle:PensumAsignatura:edit.html.twig', array(
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
     * Deletes a PensumAsignatura entity.
     *
     * @Route("/{id}", name="pensum_asignatura_delete")
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
                $entities = $this->getEntityRepository()->findById($id);

                foreach ($entities as $entity){
                  if (!$entity) {
                    throw $this->createNotFoundException('msg.unable.to.find.entity');
                  }
                  $em = $this->getEntityManager();
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
