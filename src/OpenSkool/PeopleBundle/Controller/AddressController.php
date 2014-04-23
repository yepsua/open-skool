<?php

namespace OpenSkool\PeopleBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OpenSkool\PeopleBundle\Entity\Address;
use OpenSkool\PeopleBundle\Form\AddressType;

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
 * Address controller.
 *
 * @Route("/address")
 */
class AddressController extends Controller
{
    const TRANSLATOR_DOMAIN = 'OpenSkoolPeopleBundle_Addres';
    
    /**
     * Lists all Address entities.
     *
     * @Route("/", name="address")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $grid = new Grid('address','list.view.grid.title');
        $grid->setUrl($this->generateUrl('address_data'));
        $grid->setTranslator($this->get('translator'), self::TRANSLATOR_DOMAIN);
        //$grid->setPostData(array('test' => '1'));
        $grid->createView();

        $grid->setEntityManager($this->getDoctrine()->getManager());
        
        $fields = array(
          'address.addresssType' => 'AddresssType',  
          'address.lineOne' => 'Lineone',      
          'address.lineTwo' => 'Linetwo',      
          'address.zipcode' => 'Zipcode',      
          'address.id' => array('hidden' => true),              
          'owners.id' => array('title' => 'Owners', 'search' => false, 'association' => 'OpenSkool\PeopleBundle\Entity\Person'),  
        );
  
        $grid->setArrayGridField($fields);
        
        return array(
            'grid' => $grid
        );
    }
  
    /**
     * Public service - All Address entities
     * @Route("/data", name="address_data")
     * @Method("GET")
     */
    public function dataAction()
    {
        try{
            $request = $this->getRequest();
            if(!$request->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('address'));
            }
            
            JQuery::useComponent(JQueryConstant::COMPONENT_JQGRID);
            $em = $this->getDoctrine()->getManager();
            $orderBy = $request->get('sidx');
            $page = $request->get("page", 1);
            $rows = $request->get("rows", 1);
            $sord = $request->get('sord', 'ASC');
            $filters = $request->get('filters', null);
            $response = new GridResponse();
            
            $repository = $em->getRepository('OpenSkoolPeopleBundle:Address');
            $count = Dao::count($repository);
            
            if($count > 0){
                $query = $repository->getAddressesByUserId($request->get('owner_id'));
                
                $query->setMaxResults($rows)->setFirstResult(($page - 1) * $rows);
                $entities = $query->getQuery()->getResult();
                
                foreach ($entities as $entitie){
                    $row = new GridRow();
                    $row->setId($entitie->getId());
                    $row->newCell(ObjectUtil::__toString__($entitie->getAddressType()));
                    $row->newCell($entitie->getLineOne());
                    $row->newCell($entitie->getLineTwo());
                    $row->newCell($entitie->getZipcode());
                    $row->newCell($entitie->getId());
                    $row->newCell(ObjectUtil::__toString__($entitie->getOwners()));    
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
     * Creates a new Address entity.
     *
     * @Route("/create", name="address_create")
     * @Method("POST")
     * @Template("OpenSkoolPeopleBundle:Address:new.html.twig")
     */
    public function createAction(Request $request)
    {
        try{
            $entity  = new Address();
            $form = $this->createForm(new AddressType(), $entity);
            $form->bind($request);
            $request = $this->getRequest();
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $ownerId = $request->getSession()->get('owner_id');
                
                if($ownerId !== null){
                    $repository = $em->getRepository('OpenSkoolPeopleBundle:Address');
                    $entity = $repository->associateAddressesWithUser($ownerId, $entity);
                }

                $em->persist($entity);
                $em->flush();
                
                if($request->get('_loop_create')){
                    $form = $this->createForm(new AddressType(), new Address());
                    return $this->render('OpenSkoolPeopleBundle:Address:new.html.twig',array(
                      'entity'      => $entity,
                      'form' => $form->createView())
                    ); 
                }else{
                    $deleteForm = $this->createDeleteForm($entity->getId());
                    return $this->render('OpenSkoolPeopleBundle:Address:show.html.twig',array(
                      'entity' => $entity,
                      'delete_form' => $deleteForm->createView(),)
                    );
                }
            }
            
            return $this->render('OpenSkoolPeopleBundle:Address:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ), new Response(null, 202));
                        
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Displays a form to create a new Address entity.
     *
     * @Route("/new", name="address_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        try{
            $request = $this->getRequest();
            if(!$request->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('address'));
            }
            
            /*$userDetail = $this->getUser()->getUserDetail();
            
            if($userDetail != null){
                $request->getSession()->set('owner_id', $userDetail->getId());
            }*/

            $entity = new Address();
            $form   = $this->createForm(new AddressType(), $entity);

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
     * Finds and displays a Address entity.
     *
     * @Route("/{id}", name="address_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('address'));
            }
        
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('OpenSkoolPeopleBundle:Address')->find($id);

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
     * Displays a form to edit an existing Address entity.
     *
     * @Route("/{id}/edit", name="address_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('address'));
            }
            
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('OpenSkoolPeopleBundle:Address')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $editForm = $this->createForm(new AddressType(), $entity);
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
     * Edits an existing Address entity.
     *
     * @Route("/{id}", name="address_update")
     * @Method("PUT")
     * @Template("OpenSkoolPeopleBundle:Address:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OpenSkoolPeopleBundle:Address')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createForm(new AddressType(), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->render('OpenSkoolPeopleBundle:Address:show.html.twig',array(
                  'entity'      => $entity,
                  'delete_form' => $deleteForm->createView(),)
                );
            }

            return $this->render('OpenSkoolPeopleBundle:Address:edit.html.twig', array(
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
     * Deletes a Address entity.
     *
     * @Route("/{id}", name="address_delete")
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
                $entities = $em->getRepository('OpenSkoolPeopleBundle:Address')->findById($id);

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
