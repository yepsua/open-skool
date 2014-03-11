<?php

namespace Utopia\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Utopia\CoreBundle\Entity\Persona;
use Utopia\CoreBundle\Form\PersonaType;

use Yepsua\GeneratorBundle\UI\Grid;
use Yepsua\CommonsBundle\Persistence\Dao;
use Yepsua\CommonsBundle\IO\ObjectUtil;
use Yepsua\SmarTwigBundle\UI\Message\Notification;

use \YsJQuery as JQuery;
use \YsJQueryConstant as JQueryConstant;
use \YsGridResponse as GridResponse;
use \YsGridRow as GridRow;


/**
 * Persona controller.
 *
 * @Route("/persona")
 */
class PersonaController extends Controller
{
    /**
     * Lists all Persona entities.
     *
     * @Route("/", name="persona")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $grid = new Grid('persona','list.view.grid.title');
        $grid->setUrl($this->generateUrl('persona_data'));
        $grid->setTranslator($this->get('translator'));
        $grid->createView();
        $grid->setEntityManager($this->getDoctrine()->getManager());
        
        $fields = array(
          'persona.id' => array('hidden' => true),      
          'persona.nombre' => 'Nombre',      
          'persona.apellido' => 'Apellido',      
          'persona.edad' => 'Edad',              
          'foto.id' => array('title' => 'Foto', 'association' => 'Utopia\CoreBundle\Entity\Foto')
        );
  
        $grid->setArrayGridField($fields);
        
        return array(
            'grid' => $grid
        );
    }
  
    /**
     * Public service - All Persona entities
     * @Route("/data", name="persona_data")
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
            
            $repository = $em->getRepository('UtopiaCoreBundle:Persona');
            $count = Dao::count($repository);
            
            if($count > 0){
                $query = Dao::buildQuery($repository, 'persona', $orderBy, $sord, $filters);
                $query = $query->leftJoin('persona.foto','foto');
                $query->setMaxResults($rows)->setFirstResult(($page - 1) * $rows);
                $entities = $query->getQuery()->getResult();
                
                foreach ($entities as $entitie){
                    $row = new GridRow();
                    $row->setId($entitie->getId());                                            
                    $row->newCell($entitie->getId());                                                                            
                    $row->newCell($entitie->getNombre());                                                                                            
                    $row->newCell($entitie->getApellido());                                                            
                    $row->newCell($entitie->getEdad());          
                    $row->newCell(ObjectUtil::__toString__($entitie->getFoto()));    
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
     * Creates a new Persona entity.
     *
     * @Route("/create", name="persona_create")
     * @Method("POST")
     * @Template("UtopiaCoreBundle:Persona:new.html.twig")
     */
    public function createAction(Request $request)
    {
        try{
            $entity  = new Persona();
            $form = $this->createForm(new PersonaType(), $entity);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                
                if($this->getRequest()->get('_loop_create')){
                    $form = $this->createForm(new PersonaType(), new Persona());
                    return $this->render('UtopiaCoreBundle:Persona:new.html.twig',array(
                      'entity'      => $entity,
                      'form' => $form->createView())
                    ); 
                }else{
                    $deleteForm = $this->createDeleteForm($entity->getId());
                    return $this->render('UtopiaCoreBundle:Persona:show.html.twig',array(
                      'entity' => $entity,
                      'delete_form' => $deleteForm->createView(),)
                    );
                }
            }
            
            return $this->render('UtopiaCoreBundle:Persona:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ), new Response(null, 202));
                        
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Displays a form to create a new Persona entity.
     *
     * @Route("/new", name="persona_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        try{
            $entity = new Persona();
            $form   = $this->createForm(new PersonaType(), $entity);

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
     * Finds and displays a Persona entity.
     *
     * @Route("/{id}", name="persona_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        try{
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('UtopiaCoreBundle:Persona')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Persona entity.');
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
     * Displays a form to edit an existing Persona entity.
     *
     * @Route("/{id}/edit", name="persona_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        try{
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('UtopiaCoreBundle:Persona')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Persona entity.');
            }

            $editForm = $this->createForm(new PersonaType(), $entity);
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
     * Edits an existing Persona entity.
     *
     * @Route("/{id}", name="persona_update")
     * @Method("PUT")
     * @Template("UtopiaCoreBundle:Persona:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        try{
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('UtopiaCoreBundle:Persona')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Persona entity.');
            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createForm(new PersonaType(), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $em->persist($entity);
                $em->flush();

                return $this->render('UtopiaCoreBundle:Persona:show.html.twig',array(
                  'entity'      => $entity,
                  'delete_form' => $deleteForm->createView(),)
                );
            }

            return $this->render('UtopiaCoreBundle:Persona:edit.html.twig', array(
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
     * Deletes a Persona entity.
     *
     * @Route("/{id}", name="persona_delete")
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
                $entities = $em->getRepository('UtopiaCoreBundle:Persona')->findById($id);

                foreach ($entities as $entity){
                  if (!$entity) {
                    throw $this->createNotFoundException('Unable to find Persona entity.');
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
     * Creates a form to delete a Persona entity by id.
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
