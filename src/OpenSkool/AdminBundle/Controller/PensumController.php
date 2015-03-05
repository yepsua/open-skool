<?php

namespace OpenSkool\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OpenSkool\AdminBundle\Entity\Pensum;
use OpenSkool\AdminBundle\Form\PensumType;

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
 * Pensum controller.
 *
 * @Route("/pensum")
 */
class PensumController extends Controller
{
    const REPOSITORY_NAMESPACE = 'OpenSkoolAdminBundle:Pensum';
  
    /**
     * Lists all Pensum entities.
     *
     * @Route("/", name="pensum")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $grid = new Grid('pensum','list.view.grid.title');
        $grid->setUrl($this->generateUrl('pensum_data'));
        $grid->setTranslator($this->get('translator'), 'OpenSkoolAdminBundle_Pensum');
        $grid->createView();
        $grid->setEntityManager($this->getDoctrine()->getManager());
        
        $fields = array(
          'pensum.id' => array('hidden' => true),              
          'instituto.id' => array('title' => 'Instituto', 'width'=> 300, 'association' => 'OpenSkool\AdminBundle\Entity\Instituto'),        
          'carrera.id' => array('title' => 'Carrera', 'width'=> 200, 'association' => 'OpenSkool\AdminBundle\Entity\Carrera'),
          'pensumPadre.id' => array('title' => 'Pensumpadre', 'width'=> 300, 'association' => 'OpenSkool\AdminBundle\Entity\Pensum'),  
        );
  
        $grid->setArrayGridField($fields);
        
        return array(
            'grid' => $grid
        );
    }
  
    /**
     * Public service - All Pensum entities
     * @Route("/data", name="pensum_data")
     * @Method("GET")
     */
    public function dataAction()
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('pensum'));
            }
            
            JQuery::useComponent(JQueryConstant::COMPONENT_JQGRID);
            $orderBy = $this->getRequest()->get('sidx');
            $page = $this->getRequest()->get("page", 1);
            $rows = $this->getRequest()->get("rows", 1);
            $sord = $this->getRequest()->get('sord', 'ASC');
            $filters = $this->getRequest()->get('filters', null);
            $response = new GridResponse();
            
            $repository = $this->getEntityRepository();
            $count = Dao::count($repository);
            
            if($count > 0){
                $query = Dao::buildQuery($repository, 'pensum', $orderBy, $sord, $filters);
                $query = $query->leftJoin('pensum.instituto','instituto');
                $query = $query->leftJoin('pensum.carrera','carrera');
                $query = $query->leftJoin('pensum.pensumPadre','pensumPadre');
                $query->setMaxResults($rows)->setFirstResult(($page - 1) * $rows);
                $entities = $query->getQuery()->getResult();
                
                foreach ($entities as $entitie){
                    $row = new GridRow();
                    $row->setId($entitie->getId());
                    $row->newCell($entitie->getId());          
                    $row->newCell(ObjectUtil::__toString__($entitie->getInstituto()));        
                    $row->newCell(ObjectUtil::__toString__($entitie->getCarrera()));
                    $row->newCell(ObjectUtil::__toString__($entitie->getPensumPadre())); 
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
     * Creates a new Pensum entity.
     *
     * @Route("/create", name="pensum_create")
     * @Method("POST")
     * @Template("OpenSkoolAdminBundle:Pensum:new.html.twig")
     */
    public function createAction(Request $request)
    {
        try{
            $entity  = new Pensum();
            $pensumType = new PensumType();
            $pensumType->setFormType(PensumType::FORM_TYPE_NEW);
            $form = $this->createForm($pensumType, $entity);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                
                if($this->getRequest()->get('_loop_create')){
                    $form = $this->createForm($pensumType, new Pensum());
                    return $this->render('OpenSkoolAdminBundle:Pensum:new.html.twig',array(
                      'entity'      => $entity,
                      'form' => $form->createView())
                    ); 
                }else{
                    $deleteForm = $this->createDeleteForm($entity->getId());
                    return $this->render('OpenSkoolAdminBundle:Pensum:show.html.twig',array(
                      'entity' => $entity,
                      'delete_form' => $deleteForm->createView(),)
                    );
                }
            }
            
            return $this->render('OpenSkoolAdminBundle:Pensum:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ), new Response(null, 202));
                        
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Displays a form to create a new Pensum entity.
     *
     * @Route("/new", name="pensum_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('pensum'));
            }
        
            $entity = new Pensum();
            $pensumType = new PensumType();
            $pensumType->setFormType(PensumType::FORM_TYPE_NEW);
            $form   = $this->createForm($pensumType, $entity);

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
     * Finds and displays a Pensum entity.
     *
     * @Route("/{id}", name="pensum_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('pensum'));
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
     * Displays a form to edit an existing Pensum entity.
     *
     * @Route("/{id}/edit", name="pensum_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('pensum'));
            }
            
            $entity = $this->getEntityRepository()->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $editForm = $this->createForm(new PensumType(), $entity);
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
     * Edits an existing Pensum entity.
     *
     * @Route("/{id}", name="pensum_update")
     * @Method("PUT")
     * @Template("OpenSkoolAdminBundle:Pensum:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        try{
            $entity = $this->getEntityRepository()->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createForm(new PensumType(), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->render('OpenSkoolAdminBundle:Pensum:show.html.twig',array(
                  'entity'      => $entity,
                  'delete_form' => $deleteForm->createView(),)
                );
            }

            return $this->render('OpenSkoolAdminBundle:Pensum:edit.html.twig', array(
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
     * Deletes a Pensum entity.
     *
     * @Route("/{id}", name="pensum_delete")
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
                $entities = $this->getEntityRepository()->findById($id);

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
