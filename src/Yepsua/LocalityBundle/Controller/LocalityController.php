<?php

namespace Yepsua\LocalityBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Yepsua\LocalityBundle\Entity\Locality;
use Yepsua\LocalityBundle\Form\LocalityType;

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
 * Locality controller.
 *
 * @Route("/locality")
 */
class LocalityController extends Controller
{
    /**
     * Lists all Locality entities.
     *
     * @Route("/", name="locality")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $grid = new Grid('locality','list.view.grid.title');
        $grid->setUrl($this->generateUrl('locality_data'));
        $grid->setTranslator($this->get('translator'), 'YepsuaLocalityBundle_Locality');
        $grid->createView();
        $grid->setEntityManager($this->getDoctrine()->getManager());
        
        $fields = array(
          'locality.name' => 'Name',      
          'locality.code' => 'Code',      
          'locality.id' => array('hidden' => true),              
          'cities.id' => array('title' => 'Cities', 'association' => 'Yepsua\LocalityBundle\Entity\City'),        
          'country.id' => array('title' => 'Country', 'association' => 'Yepsua\LocalityBundle\Entity\Country'),  
        );
  
        $grid->setArrayGridField($fields);
        
        return array(
            'grid' => $grid
        );
    }
  
    /**
     * Public service - All Locality entities
     * @Route("/data", name="locality_data")
     * @Method("GET")
     */
    public function dataAction()
    {
        try{
            $request = $this->getRequest();
            if(!$request->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('locality'));
            }
            
            JQuery::useComponent(JQueryConstant::COMPONENT_JQGRID);
            $em = $this->getDoctrine()->getManager();
            $orderBy = $request->get('sidx');
            $page = $request->get("page", 1);
            $rows = $request->get("rows", 1);
            $sord = $request->get('sord', 'ASC');
            $filters = $request->get('filters', null);
            $response = new GridResponse();
            
            $repository = $em->getRepository('YepsuaLocalityBundle:Locality');
            $count = Dao::count($repository);
            
            if($count > 0){
                $query = Dao::buildQuery($repository, 'locality', $orderBy, $sord, $filters);
                $query = $query->leftJoin('locality.cities','cities');
                $query = $query->leftJoin('locality.country','country');
                $query->setMaxResults($rows)->setFirstResult(($page - 1) * $rows);
                $entities = $query->getQuery()->getResult();
                
                foreach ($entities as $entitie){
                    $row = new GridRow();
                    $row->setId($entitie->getId());
                    $row->newCell($entitie->getName());
                    $row->newCell($entitie->getCode());
                    $row->newCell($entitie->getId());          
                    $row->newCell(ObjectUtil::__toString__($entitie->getCities()));          
                    $row->newCell(ObjectUtil::__toString__($entitie->getCountry()));    
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
     * Creates a new Locality entity.
     *
     * @Route("/create", name="locality_create")
     * @Method("POST")
     * @Template("YepsuaLocalityBundle:Locality:new.html.twig")
     */
    public function createAction(Request $request)
    {
        try{
            $entity  = new Locality();
            $form = $this->createForm(new LocalityType(), $entity);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                
                if($this->getRequest()->get('_loop_create')){
                    $form = $this->createForm(new LocalityType(), new Locality());
                    return $this->render('YepsuaLocalityBundle:Locality:new.html.twig',array(
                      'entity'      => $entity,
                      'form' => $form->createView())
                    ); 
                }else{
                    $deleteForm = $this->createDeleteForm($entity->getId());
                    return $this->render('YepsuaLocalityBundle:Locality:show.html.twig',array(
                      'entity' => $entity,
                      'delete_form' => $deleteForm->createView(),)
                    );
                }
            }
            
            return $this->render('YepsuaLocalityBundle:Locality:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ), new Response(null, 202));
                        
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Displays a form to create a new Locality entity.
     *
     * @Route("/new", name="locality_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('locality'));
            }
        
            $entity = new Locality();
            $form   = $this->createForm(new LocalityType(), $entity);

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
     * Finds and displays a Locality entity.
     *
     * @Route("/{id}", name="locality_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('locality'));
            }
        
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('YepsuaLocalityBundle:Locality')->find($id);

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
     * Displays a form to edit an existing Locality entity.
     *
     * @Route("/{id}/edit", name="locality_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('locality'));
            }
            
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('YepsuaLocalityBundle:Locality')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $editForm = $this->createForm(new LocalityType(), $entity);
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
     * Edits an existing Locality entity.
     *
     * @Route("/{id}", name="locality_update")
     * @Method("PUT")
     * @Template("YepsuaLocalityBundle:Locality:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('YepsuaLocalityBundle:Locality')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createForm(new LocalityType(), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->render('YepsuaLocalityBundle:Locality:show.html.twig',array(
                  'entity'      => $entity,
                  'delete_form' => $deleteForm->createView(),)
                );
            }

            return $this->render('YepsuaLocalityBundle:Locality:edit.html.twig', array(
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
     * Deletes a Locality entity.
     *
     * @Route("/{id}", name="locality_delete")
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
                $entities = $em->getRepository('YepsuaLocalityBundle:Locality')->findById($id);

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
