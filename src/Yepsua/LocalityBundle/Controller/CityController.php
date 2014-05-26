<?php

namespace Yepsua\LocalityBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Yepsua\LocalityBundle\Entity\City;
use Yepsua\LocalityBundle\Form\CityType;

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
 * City controller.
 *
 * @Route("/city")
 */
class CityController extends Controller
{
    /**
     * Lists all City entities.
     *
     * @Route("/", name="city")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $grid = new Grid('city','list.view.grid.title');
        $grid->setUrl($this->generateUrl('city_data'));
        $grid->setTranslator($this->get('translator'), 'YepsuaLocalityBundle_City');
        $grid->createView();
        $grid->setEntityManager($this->getDoctrine()->getManager());
        
        $fields = array(
          'city.name' => 'Name',      
          'city.code' => 'Code',      
          'city.id' => array('hidden' => true),              
          'locality.id' => array('title' => 'Locality', 'association' => 'Yepsua\LocalityBundle\Entity\Locality'),  
        );
  
        $grid->setArrayGridField($fields);
        
        return array(
            'grid' => $grid
        );
    }
  
    /**
     * Public service - All City entities
     * @Route("/data", name="city_data")
     * @Method("GET")
     */
    public function dataAction()
    {
        try{
            $request = $this->getRequest();
            if(!$request->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('city'));
            }
            
            JQuery::useComponent(JQueryConstant::COMPONENT_JQGRID);
            $em = $this->getDoctrine()->getManager();
            $orderBy = $request->get('sidx');
            $page = $request->get("page", 1);
            $rows = $request->get("rows", 1);
            $sord = $request->get('sord', 'ASC');
            $filters = $request->get('filters', null);
            $response = new GridResponse();
            
            $repository = $em->getRepository('YepsuaLocalityBundle:City');
            $count = Dao::count($repository);
            
            if($count > 0){
                $query = Dao::buildQuery($repository, 'city', $orderBy, $sord, $filters);
                $query = $query->leftJoin('city.locality','locality');
                $query->setMaxResults($rows)->setFirstResult(($page - 1) * $rows);
                $entities = $query->getQuery()->getResult();
                
                foreach ($entities as $entitie){
                    $row = new GridRow();
                    $row->setId($entitie->getId());
                    $row->newCell($entitie->getName());
                    $row->newCell($entitie->getCode());
                    $row->newCell($entitie->getId());          
                    $row->newCell(ObjectUtil::__toString__($entitie->getLocality()));    
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
     * Creates a new City entity.
     *
     * @Route("/create", name="city_create")
     * @Method("POST")
     * @Template("YepsuaLocalityBundle:City:new.html.twig")
     */
    public function createAction(Request $request)
    {
        try{
            $entity  = new City();
            $form = $this->createForm(new CityType(), $entity);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                
                if($this->getRequest()->get('_loop_create')){
                    $form = $this->createForm(new CityType(), new City());
                    return $this->render('YepsuaLocalityBundle:City:new.html.twig',array(
                      'entity'      => $entity,
                      'form' => $form->createView())
                    ); 
                }else{
                    $deleteForm = $this->createDeleteForm($entity->getId());
                    return $this->render('YepsuaLocalityBundle:City:show.html.twig',array(
                      'entity' => $entity,
                      'delete_form' => $deleteForm->createView(),)
                    );
                }
            }
            
            return $this->render('YepsuaLocalityBundle:City:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ), new Response(null, 202));
                        
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Displays a form to create a new City entity.
     *
     * @Route("/new", name="city_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('city'));
            }
        
            $entity = new City();
            $form   = $this->createForm(new CityType(), $entity);

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
     * Finds and displays a City entity.
     *
     * @Route("/{id}", name="city_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('city'));
            }
        
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('YepsuaLocalityBundle:City')->find($id);

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
     * Displays a form to edit an existing City entity.
     *
     * @Route("/{id}/edit", name="city_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('city'));
            }
            
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('YepsuaLocalityBundle:City')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $editForm = $this->createForm(new CityType(), $entity);
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
     * Edits an existing City entity.
     *
     * @Route("/{id}", name="city_update")
     * @Method("PUT")
     * @Template("YepsuaLocalityBundle:City:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('YepsuaLocalityBundle:City')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createForm(new CityType(), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->render('YepsuaLocalityBundle:City:show.html.twig',array(
                  'entity'      => $entity,
                  'delete_form' => $deleteForm->createView(),)
                );
            }

            return $this->render('YepsuaLocalityBundle:City:edit.html.twig', array(
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
     * Deletes a City entity.
     *
     * @Route("/{id}", name="city_delete")
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
                $entities = $em->getRepository('YepsuaLocalityBundle:City')->findById($id);

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
    
    
    /**
     * Lists all Locality entities.
     *
     * @Route("/api/html/{id}", name="locality_html_api")
     * @Method("GET")
     * @Template()
     */
    public function apiHTMLAction(Request $request, $id){
      try{
          
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('YepsuaLocalityBundle:City');
        $localities = $repository->findAllByLocality($id);
        
        $options = array();
        
        foreach ($localities as $locality){
          $options[] = array('label' => $locality->getName(),'value' => $locality->getId());
        }
        
        return $this->render(
          'YepsuaRADBundle:Form:option_item.html.twig',
           array('options' => $options)
        );    
      }catch(\Exception $e){
        $this->get('logger')->crit($e->getMessage());
        return new Response(Notification::error($e->getMessage()), 203);
      }
    }
}
