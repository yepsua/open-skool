<?php

namespace OpenSkool\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
                
    Yepsua\RADBundle\Controller\Controller;

/**
 * AdminPlanEstudiosController controller.
 *
 * @Route("/admin_plan_estudios")
 */
class AdminPlanEstudiosController extends Controller
{
    const REPOSITORY_NAMESPACE = 'OpenSkoolAdminBundle:PlanEstudios';
    
    /**
     * 
     *
     * @Route("/", name="admin_plan_estudios")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(){
      try{

        $repository = $this->getEntityRepository();
        $planEstudios = $repository->findAll();

        $grupoRepo = $this->getEntityRepository('OpenSkoolAdminBundle:Grupo');
        $grupos = $grupoRepo->findAll();

        $etapaRepo = $this->getEntityRepository('OpenSkoolAdminBundle:Etapa');
        $etapas = $etapaRepo->findAll();

        return array(
          'planEstudios' => $planEstudios, 
          'grupos' => $grupos, 
          'etapas' => $etapas
        );
      
      }catch(\Exception $ex){
        return $this->exceptionManagerNotification($ex);
      }
    }
    
    /**
     * 
     *
     * @Route("/{id}/detalle", name="detalle_admin_plan_estudios")
     * @Method("GET")
     * @Template()
     */
    public function etapasPorPlanEstudiosAction($id){
      try{
        $repositoryEtapas = $this->getEntityRepository('OpenSkoolAdminBundle:EtapaPlanEstudios');
        $tabNum = $this->getRequest()->get('tabNum', 0);
        $etapasNoAgrupadasId = array();
        $pensumAsignaturas = array();
        if($id !== null){
          $etapasPlan = $repositoryEtapas->getEtapasPorPlanEstudios($id);

          if($etapasPlan){
            $repositoryGrupos = $this->getEntityRepository('OpenSkoolAdminBundle:GrupoEtapaPlanEstudios');
            $etapasIds = array();
            $etapasIds2 = array();
            
            foreach($etapasPlan as $etapa){
              $etapasIds[] = $etapa->getId();
            }
            
            $gruposEtapasPlan = $repositoryGrupos->findBy(array('etapaPlanEstudios' => $etapasIds));
            foreach($gruposEtapasPlan as $grupo){
              $etapasIds2[] = $grupo->getEtapaPlanEstudios()->getId();
            }

            $etapasNoAgrupadasId = array_diff($etapasIds, $etapasIds2);

            $planEstudios = $this->getEntityRepository()->find($id);

            if($planEstudios){
              $repoPensumAsignaturas = $this->getEntityRepository('OpenSkoolAdminBundle:PensumAsignatura');
              $pensumAsignaturas = $repoPensumAsignaturas->findBy(array('pensum' => $planEstudios->getPensum()->getId()));
            }


          }else{
            $gruposEtapasPlan = array();
          }
        }else{
          $etapasPlan = array();
        }
        return array(
            'etapasPlan' => $etapasPlan, 
            'gruposEtapasPlan' => $gruposEtapasPlan,
            'etapasNoAgrupadasId' => $etapasNoAgrupadasId,
            'tabNum' => $tabNum,
            'pensumAsignaturas' => $pensumAsignaturas
        );
      }catch(\Exception $ex){
        return $this->exceptionManagerNotification($ex);
      }
    }
    
    /**
     * 
     *
     * @Route("plan_estudios/{planEstudiosId}/etapa/{etapaId}/asignatura", name="asignatura_por_etapa")
     * @Method("GET")
     * @Template()
     */
    public function asignaturasPorEtapasAction($planEstudiosId, $etapaId){
      try{
        $repo = $this->getEntityRepository('OpenSkoolAdminBundle:AsignaturaEtapaPlanEstudios');
        $pensumAsignaturas = array();
        $asignaturasPlan = array();

        if($etapaId !== null){
          $asignaturasPlan = $repo->findBy(array('etapaPlanEstudios' => $etapaId));
        }

        $planEstudios = $this->getEntityRepository()->find($planEstudiosId);

        if($planEstudios){
          $repoPensumAsignaturas = $this->getEntityRepository('OpenSkoolAdminBundle:PensumAsignatura');
          $pensumAsignaturas = $repoPensumAsignaturas->findBy(array('pensum' => $planEstudios->getPensum()->getId()));
        }
        return array(
            'asignaturasPlan' => $asignaturasPlan,
            'pensumAsignaturas' => $pensumAsignaturas
        );
      }catch(\Exception $ex){
        return $this->exceptionManagerNotification($ex);
      }
    }
    
    /**
     * 
     *
     * @Route("/plan_estudios/{planEstudiosId}/etapa/{etapaId}/agregar", name="agregar_etapa_plan_estudios")
     * @Method("PUT")
     */
    public function crearEtapaPlanEstudiosAction($planEstudiosId, $etapaId){
      try{
        $repo = $this->getEntityRepository('OpenSkoolAdminBundle:EtapaPlanEstudios');
        $repo->crearEtapaPlanEstudios($planEstudiosId, $etapaId);
        return $this->notify("Success");
      }catch(\Exception $ex){
        return $this->exceptionManagerNotification($ex);
      }
    }
    
    /**
     * 
     *
     * @Route("/etapa/grupo/{desagruparIds}/desagrupar", name="desagrupar_etapas_plan_estudios")
     * @Method({"DELETE", "POST"})
     */
    public function desagruparEtapasAction($desagruparIds){
      try{
          $repo = $this->getEntityRepository('OpenSkoolAdminBundle:GrupoEtapaPlanEstudios');
          $repo->desagruparEtapas($desagruparIds);
          return $this->notify("Success");
      }catch(\Exception $ex){
        return $this->exceptionManagerNotification($ex);
      }
    }
    
    /**
     * 
     *
     * @Route("/etapa/{etapasIds}/grupo/{grupoId}/agrupar", name="agrupar_etapas_plan_estudios")
     * @Method("PUT")
     */
    public function agruparEtapasAction($etapasIds,$grupoId){
      try{
          $repo = $this->getEntityRepository('OpenSkoolAdminBundle:GrupoEtapaPlanEstudios');
          $repo->agruparEtapas($grupoId,$etapasIds);
          return $this->notify("Success");
      }catch(\Exception $ex){
        return $this->exceptionManagerNotification($ex);
      }
    }
    
    /**
     * 
     *
     * @Route("/etapa/{etapasIds}/ordenar", name="ordenar_etapas_plan_estudios")
     * @Method("PUT")
     */
    public function ordenarEtapasAction($etapasIds){
      try{
          $repo = $this->getEntityRepository('OpenSkoolAdminBundle:EtapaPlanEstudios');
          $etapasIds = array_flip(explode(",", $etapasIds));
          $repo->ordenarEtapas($etapasIds);
          return $this->notify("Success");
      }catch(\Exception $ex){
        return $this->exceptionManagerNotification($ex);
      }
    }
    
    /**
     * 
     *
     * @Route("/etapa/{etapaId}/eliminar", name="eliminar_etapa_plan_estudios")
     * @Method({"DELETE", "POST"})
     */
    public function eliminarEtapaPlanEstudioAction($etapaId){
      try{
          $repo = $this->getEntityRepository('OpenSkoolAdminBundle:EtapaPlanEstudios');
          $repo->eliminarEtapa($etapaId);
          return $this->notify("Success");
      }catch(\Exception $ex){
        return $this->exceptionManagerNotification($ex);
      }
    }
    
    /**
     * 
     *
     * @Route("/etapa_plan_estudios/{etapaId}/activar/{activo}", 
     *   name="activar_etapa_plan_estudios", defaults={"activo" = true})
     * @Method({"DELETE", "POST"})
     */
    public function activarEtapaPlanEstudioAction($etapaId,$activo){
      try{
          $repo = $this->getEntityRepository('OpenSkoolAdminBundle:EtapaPlanEstudios');
          $repo->activarEtapa($etapaId, $activo);
          return $this->notify("Success");
      }catch(\Exception $ex){
        return $this->exceptionManagerNotification($ex);
      }
    }
    
    /**
     * 
     *
     * @Route("/etapa/{etapaId}/asignatura/{asignaturaId}/agregar", name="agregar_etapa_asignatura")
     * @Method("PUT")
     */
    public function agregarAsignaturaEtapasAction($etapaId, $asignaturaId){
      try{
          $repo = $this->getEntityRepository('OpenSkoolAdminBundle:AsignaturaEtapaPlanEstudios');
          $repo->crearPorIds($etapaId, $asignaturaId);
          return $this->notify("Success");
      }catch(\Exception $ex){
        return $this->exceptionManagerNotification($ex);
      }
    }
    
    /**
     * 
     *
     * @Route("etapa/asignatura/{asignaturaEtapaId}/eliminar", name="eliminar_etapa_asignatura")
     * @Method({"DELETE", "POST"})
     */
    public function eliminarAsignaturaEtapasAction($asignaturaEtapaId){
      try{
          $repo = $this->getEntityRepository('OpenSkoolAdminBundle:AsignaturaEtapaPlanEstudios');
          $repo->eliminarPorId($asignaturaEtapaId);
          return $this->notify("Success");
      }catch(\Exception $ex){
        return $this->exceptionManagerNotification($ex);
      }
    }
    
    /**
     * 
     *
     * @Route("etapa/asignatura/{asignaturaEtapaId}/prelaciones", name="mostrar_asignatura_prelaciones")
     * @Method("GET")
     * @Template()
     */
    public function asignaturaPrelacionesAction($asignaturaEtapaId){
      try{
        return $this->detallePrelacion($asignaturaEtapaId);
      }catch(\Exception $ex){
        return $this->exceptionManagerNotification($ex);
      }
    }
    
    private function detallePrelacion($asignaturaEtapaId){
        $repoAsignaturaEtapa = $this->getEntityRepository('OpenSkoolAdminBundle:AsignaturaEtapaPlanEstudios');
        $asignaturaPlan = $repoAsignaturaEtapa->find($asignaturaEtapaId);
        $asignaturasPensum = array();
        $prelaciones = array();
        
        if($asignaturaPlan){
          $repoPrelacion = $this->getEntityRepository('OpenSkoolAdminBundle:Prelacion');
          $prelaciones = $repoPrelacion->obtenerPrelacionesAsignatura($asignaturaEtapaId);
          
          if($prelaciones){
            $asignaturasEtapaId = array();
            $asignaturasEtapaId[] = $asignaturaEtapaId;
            
            foreach ($prelaciones as $prelacion){
              $asignaturasEtapaId[] = $prelacion->getPrelacion()->getId();
            }
            
          }else{
            $asignaturasEtapaId = $asignaturaEtapaId;
          }

          $asignaturasPensum = $repoAsignaturaEtapa->obtenerPorPlanEstudios($asignaturaPlan->getEtapaPlanEstudios()->getPlanEstudios()->getId(), $asignaturasEtapaId);
        }
        
        return array(
          'asignaturaPlan' => $asignaturaPlan,
          'asignaturasPensum' => $asignaturasPensum,
          'prelaciones' => $prelaciones
        );
    }
    
    /**
     * 
     *
     * @Route("etapa/asignatura/{asignaturaEtapaId}/prelacion/{asignaturaPrelacionId}/agregar", name="agregar_asignatura_prelacion")
     * @Method("GET")
     * @Template(template="OpenSkoolAdminBundle:AdminPlanEstudios:detalle_prelacion.html.twig")
     */
    public function agregarAsignaturaPrelacionAction($asignaturaEtapaId, $asignaturaPrelacionId){
      try{
        $repoPrelacion = $this->getEntityRepository('OpenSkoolAdminBundle:Prelacion');
        $repoPrelacion->crearPrelacion($asignaturaEtapaId,$asignaturaPrelacionId);
        return $this->detallePrelacion($asignaturaEtapaId);
      }catch(\Exception $ex){
        return $this->exceptionManagerNotification($ex);
      }
    }
    
    /**
     * 
     *
     * @Route("/prelacion/{prelacionId}/eliminar", name="eliminar_asignatura_prelacion")
     * @Method("GET")
     * @Template(template="OpenSkoolAdminBundle:AdminPlanEstudios:detalle_prelacion.html.twig")
     */
    public function eliminarPrelacionAction($prelacionId){
      try{
        $repoPrelacion = $this->getEntityRepository('OpenSkoolAdminBundle:Prelacion');
        $prelacion = $repoPrelacion->find($prelacionId);
        if($prelacion){
          $asignaturaEtapaId = $prelacion->getAsignatura()->getId();
          $repoPrelacion->eliminarPrelacion($prelacion);
          return $this->detallePrelacion($asignaturaEtapaId);
        }else{
          $this->throwNotFoundException();
        }
      }catch(\Exception $ex){
        return $this->exceptionManagerNotification($ex);
      }
    }
    
}
