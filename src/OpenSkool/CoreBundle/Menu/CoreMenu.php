<?php

namespace OpenSkool\CoreBundle\Menu;

use Yepsua\MenuBundle\Event\ConfigureMenuEvent;
use Yepsua\MenuBundle\Menu\MenuTree;

class CoreMenu extends MenuTree
{
    /**
     * Generates the menu items for the Core Menu.
     * @param \Acme\DemoBundle\Event\ConfigureMenuEvent $event
     */
    public function configure(ConfigureMenuEvent $event)
    {
        $menuManager = $this->getMenuManager($event);

        // CORE_MENU
        $coreMenu  = $menuManager->getItem('mnu.core');
        
            $instutoMenu = $menuManager->getItem('mnu.institucion',array('route' => 'instituto'));
            $instutoMenu->addChild($menuManager->newItem('mnu.institucion.change', array('route' => 'change_instituto')));
              $pensum = $menuManager->getItem('mnu.institucion.pensum', array('route' => 'pensum'));
              $pensum->addChild($menuManager->newItem('mnu.institucion.pensum.asignaturas', array('route' => 'pensum_asignatura')));
            $instutoMenu->addChild($pensum);
            
            $coreMenu->addChild($instutoMenu);
        
            //ADMIN_MENU
            $adminMenu = $menuManager->getItem('mnu.admin', array('icon' => 'glyphicon glyphicon-align-justify'));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.titulo', array('route' => 'titulo')));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.mencion', array('route' => 'mencion')));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.carrera', array('route' => 'carrera')));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.facultad', array('route' => 'facultad')));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.asignatura', array('route' => 'asignatura')));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.periodo', array('route' => 'periodo')));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.turno', array('route' => 'turno')));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.seccion',array('route' => 'seccion')));
            

            $coreMenu->addChild($adminMenu);
            
            //PLAN_ESTUDIOS_MENU
            $planEstudiosMenu = $menuManager->getItem('mnu.plan.estudios');
            $planEstudiosMenu->addChild($menuManager->newItem('mnu.plan.estudios.admin', array('route' => 'admin_plan_estudios')));
            $planEstudiosMenu->addChild($menuManager->newItem('mnu.plan.estudios.etapas', array('route' => 'not_implemented')));
            $planEstudiosMenu->addChild($menuManager->newItem('mnu.plan.estudios.grupos', array('route' => 'not_implemented')));
            $coreMenu->addChild($planEstudiosMenu);
            
            //OFERTA_ACADEMICA_MENU
            $ofertaMenu = $menuManager->getItem('mnu.oferta');
            
            $ofertaMenu->addChild($menuManager->newItem('mnu.oferta.academica', array('route' => 'not_implemented')));
            $ofertaMenu->addChild($menuManager->newItem('mnu.oferta.seccion', array('route' => 'not_implemented')));
            $ofertaMenu->addChild($menuManager->newItem('mnu.oferta.grupos', array('route' => 'not_implemented')));
            $coreMenu->addChild($ofertaMenu);
            
            //OFERTA_ACADEMICA_MENU
            $personasMenu = $menuManager->getItem('mnu.persona', array('icon' => 'glyphicon glyphicon-user','route' => 'person'));
            $personasMenu->addChild($menuManager->newItem('mnu.persona.users',array('route' => 'user')));
            $personasMenu->addChild($menuManager->newItem('mnu.persona.groups',array('route' => 'group')));
            $personasMenu->addChild($menuManager->newItem('mnu.persona.roles',array('route' => 'role')));
            
            $coreMenu->addChild($personasMenu);
            
            //INSCRIPCION_MENU
            $inscripcionMenu = $menuManager->getItem('mnu.inscripcion');
            $inscripcionMenu->addChild($menuManager->newItem('mnu.inscripcion.pre', array('route' => 'not_implemented')));
            $inscripcionMenu->addChild($menuManager->newItem('mnu.inscripcion.post', array('route' => 'not_implemented')));
            $coreMenu->addChild($inscripcionMenu);
            
            
            //CONFIGURATION_MENU
            $configMenu = $menuManager->getItem('mnu.settings', array('icon' => 'glyphicon glyphicon-cog'));
            $configMenu->addChild($menuManager->newItem('mnu.settings.general', array('route' => 'not_implemented')));
                
                //LOV_MENU
                $lovMenu = $menuManager->getItem('mnu.settings.lov');
                $lovMenu->addChild($menuManager->newItem('mnu.settings.lov.values', array('route' => 'list_of_values')));
                $lovMenu->addChild($menuManager->newItem('mnu.settings.lov.group', array('route' => 'list_of_values_group')));
                $configMenu->addChild($lovMenu);
                
                //LOCALIZATION_MENU
                $localizationMenu = $menuManager->getItem('mnu.settings.locality');
                $localizationMenu->addChild($menuManager->newItem('mnu.settings.locality.country',array('route' => 'country')));
                $localizationMenu->addChild($menuManager->newItem('mnu.settings.locality.locality', array('route' => 'locality')));
                $localizationMenu->addChild($menuManager->newItem('mnu.settings.locality.city', array('route' => 'city')));
                $configMenu->addChild($localizationMenu);
                
            $coreMenu->addChild($configMenu);

            // PROFILE_MENU
            $profileMenu = $menuManager->getItem('mnu.profile', array('icon' => 'glyphicon glyphicon-user'));
            
            $profileMenu->setAttribute('style', 'float:right');
            $profileMenu->addChild($menuManager->newItem('mnu.profile.detail',array('route' => 'user_detail')));
            $profileMenu->addChild($menuManager->newItem('mnu.profile.settings', array('route' => 'not_implemented')));
            //$profileMenu->addChild($menuManager->newItem('mnu.profile.bloq'));
            $profileMenu->addChild($menuManager->newItem('mnu.profile.logout',array('route' => 'fos_user_security_logout')));
            $coreMenu->addChild($profileMenu);
        
        $menuManager->append($coreMenu);
    }
}