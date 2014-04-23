<?php

namespace OpenSkool\PeopleBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OpenSkool\PeopleBundle\Entity\Person;
use OpenSkool\PeopleBundle\Form\PersonType;

use Yepsua\RADBundle\Controller\Controller;
use Yepsua\GeneratorBundle\UI\Grid;
use Yepsua\CommonsBundle\Persistence\Dao;
use Yepsua\CommonsBundle\IO\ObjectUtil;
use Yepsua\SmarTwigBundle\UI\Message\Notification;
use Yepsua\SecurityBundle\Form\UserType;

use \YsJQuery as JQuery;
use \YsJQueryConstant as JQueryConstant;
use \YsGridResponse as GridResponse;
use \YsGridRow as GridRow;


/**
 * Person controller.
 *
 * @Route("/person")
 */
class PersonController extends Controller
{
    private $validateIsXmlHttpRequest = true;
  
    /**
     * Lists all Person entities.
     *
     * @Route("/", name="person")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $grid = new Grid('person','list.view.grid.title');
        $grid->setUrl($this->generateUrl('person_data'));
        $grid->setTranslator($this->get('translator'), 'OpenSkoolPeopleBundle_Person');
        $grid->createView();
        $grid->setEntityManager($this->getDoctrine()->getManager());
        
        $fields = array(
          'person.id' => array('hidden' => true),      
          'person.firstName' => 'Firstname',      
          'person.lastName' => 'Lastname',      
          'person.idType' => 'Idtype',      
          'person.idNumber' => 'Idnumber',      
          'person.birthdate' => 'Birthdate',              
          'user.id' => array('title' => 'User', 'search' => false),  
        );
        
        $grid->setArrayGridField($fields);
        
        return array(
            'grid' => $grid
        );
    }
  
    /**
     * Public service - All Person entities
     * @Route("/data", name="person_data")
     * @Method("GET")
     */
    public function dataAction()
    {
        try{
            $request = $this->getRequest();
            if(!$request->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('person'));
            }
            
            JQuery::useComponent(JQueryConstant::COMPONENT_JQGRID);
            $em = $this->getDoctrine()->getManager();
            $orderBy = $request->get('sidx');
            $page = $request->get("page", 1);
            $rows = $request->get("rows", 1);
            $sord = $request->get('sord', 'ASC');
            $filters = $request->get('filters', null);
            $response = new GridResponse();
            
            $repository = $em->getRepository('OpenSkoolPeopleBundle:Person');
            $count = Dao::count($repository);
            
            if($count > 0){
                $query = Dao::buildQuery($repository, 'person', $orderBy, $sord, $filters);
                $query = $query->leftJoin('person.user','user');
                $query->setMaxResults($rows)->setFirstResult(($page - 1) * $rows);
                $entities = $query->getQuery()->getResult();
                
                foreach ($entities as $entitie){
                    $row = new GridRow();
                    $row->setId($entitie->getId());
                    $row->newCell($entitie->getId());
                    $row->newCell($entitie->getFirstName());
                    $row->newCell($entitie->getLastName());
                    $row->newCell(ObjectUtil::__toString__($entitie->getIdType()));
                    $row->newCell($entitie->getIdNumber());      
                    if($entitie->getBirthdate() !== null){
                        $row->newCell($entitie->getBirthdate()->format('Y-m-d H:i:s'));
                    }else{
                        $row->newCell($entitie->getBirthdate());
                    }          
                    $row->newCell(ObjectUtil::__toString__($entitie->getUser()));    
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
     * Creates a new Person entity.
     *
     * @Route("/create", name="person_create")
     * @Method("POST")
     * @Template("OpenSkoolPeopleBundle:Person:new.html.twig")
     */
    public function createAction(Request $request)
    {
        try{
            $entity  = new Person();
            $form = $this->createForm(new PersonType(), $entity);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if($entity->getUser() != null){
                  $entity->getUser()->setPlainPassword($entity->getUser()->getPassword());
                }
                
                $em->persist($entity);
                $em->flush();
                
                if($this->getRequest()->get('_loop_create')){
                    $form = $this->createForm(new PersonType(), new Person());
                    return $this->render('OpenSkoolPeopleBundle:Person:new.html.twig',array(
                      'entity'      => $entity,
                      'form' => $form->createView())
                    ); 
                }else{
                    $deleteForm = $this->createDeleteForm($entity->getId());
                    return $this->render('OpenSkoolPeopleBundle:Person:show.html.twig',array(
                      'entity' => $entity,
                      'delete_form' => $deleteForm->createView(),)
                    );
                }
            }
            
            return $this->render('OpenSkoolPeopleBundle:Person:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ), new Response(null, 202));
                        
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Displays a form to create a new Person entity.
     *
     * @Route("/new", name="person_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('person'));
            }
        
            $entity = new Person();
            $form   = $this->createForm(new PersonType(), $entity);

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
     * Finds and displays a Person entity.
     *
     * @Route("/{id}", name="person_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        try{
            $request = $this->getRequest();
            if(!$request->isXmlHttpRequest() && $this->validateIsXmlHttpRequest){
                return $this->redirect($this->generateUrl('person'));
            }
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('OpenSkoolPeopleBundle:Person')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $deleteForm = $this->createDeleteForm($id);
            
            $addresData = $this->getAddressData($request, $entity);
            $userData = $this->getUserData($request, $entity->getUser());
            
            $personData = array(
                'entity'      => $entity,
                'delete_form' => $deleteForm->createView());
            
            

            return array_merge($personData,$addresData,$userData);
            
        }catch(\Exception $e){
            $this->get('logger')->crit($e->getMessage());
            return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Displays a form to edit an existing Person entity.
     *
     * @Route("/{id}/edit", name="person_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        try{
            if(!$this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('person'));
            }
            
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('OpenSkoolPeopleBundle:Person')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $editForm = $this->createForm(new PersonType(), $entity);
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
     * Edits an existing Person entity.
     *
     * @Route("/{id}", name="person_update")
     * @Method("PUT")
     * @Template("OpenSkoolPeopleBundle:Person:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OpenSkoolPeopleBundle:Person')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('msg.unable.to.find.entity');
            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createForm(new PersonType(), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if($entity->getUser() != null){
                  $entity->getUser()->setPlainPassword($entity->getUser()->getPassword());
                }
                $em->persist($entity);
                $em->flush();

                return $this->render('OpenSkoolPeopleBundle:Person:show.html.twig',array(
                  'entity'      => $entity,
                  'delete_form' => $deleteForm->createView(),)
                );
            }

            return $this->render('OpenSkoolPeopleBundle:Person:edit.html.twig', array(
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
     * Deletes a Person entity.
     *
     * @Route("/{id}", name="person_delete")
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
                $entities = $em->getRepository('OpenSkoolPeopleBundle:Person')->findById($id);

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
     * Show the user detail.
     *
     * @Route("/detail/current_user/", name="user_detail")
     * @Method("GET")
     * @Template()
     */
    public function detailAction()
    {
      $userDetail = $this->getUser()->getUserDetail();
      if($userDetail !== null){
        $this->validateIsXmlHttpRequest = false;
        return $this->showAction($userDetail->getId());
      }else{
        return $this->redirect($this->generateUrl('core_home'));
      }
    }
    
    /**
     * Get the address data from the AddressController object.
     * @param type $request
     * @param type $userDetail
     * @return type
     */
    private function getAddressData($request, $userDetail){            
            $addressController = new AddressController();
            $addressController->setContainer($this->container);
            $addresData = $addressController->indexAction();
            $addresData = $this->changeArrayKeys($addresData, 'address_');
            
            $request->getSession()->set('owner_id', $userDetail->getId());

            $grid = $addresData['address_grid'];
            $grid->setPostData(array('owner_id' => $userDetail->getId()));
            $grid->renewNavigator();
            $grid->createView(true);
            $addresData['address_grid'] = $grid;
            return $addresData;
    }
    
    /**
     * Get the address data from the AddressController object.
     * @param type $request
     * @param type $userDetail
     * @return type
     */
    private function getUserData($request, $user){
            $userForm = $this->createForm(new UserType(), $user);
            return array(
                'user_form' => $userForm->createView()
            );
    }
    
    /**
     * Changes the keys index with the prefix and suffix
     * @param type $arrayValues
     * @param type $prefixKey
     * @param type $sufixKey
     * @return type
     */
    private function changeArrayKeys($arrayValues, $prefixKey = null, $suffixKey= null){
        $newArray = array();
        foreach ($arrayValues as $key => $value) {
            $newArray[sprintf('%s%s%s',$prefixKey, $key , $suffixKey)] = $value;
        }
        return $newArray;
    }
}
