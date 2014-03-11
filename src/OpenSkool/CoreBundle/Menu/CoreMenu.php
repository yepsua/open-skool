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
        
            $coreMenu->addChild($menuManager->newItem('mnu.institucion',array('route' => 'instituto')));
        
            //ADMIN_MENU
            $adminMenu = $menuManager->getItem('mnu.admin', array('icon' => 'glyphicon glyphicon-align-justify'));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.titulo'));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.menciones'));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.carreras'));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.facultad'));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.asignaturas'));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.periodos'));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.turnos'));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.secciones'));
            $adminMenu->addChild($menuManager->newItem('mnu.admin.grupos'));
            

            $coreMenu->addChild($adminMenu);
            
            //PLAN_ESTUDIOS_MENU
            $planEstudiosMenu = $menuManager->getItem('mnu.plan.estudios');
            $planEstudiosMenu->addChild($menuManager->newItem('mnu.plan.estudios.admin'));
            $planEstudiosMenu->addChild($menuManager->newItem('mnu.plan.estudios.etapas'));
            $planEstudiosMenu->addChild($menuManager->newItem('mnu.plan.estudios.grupos'));
            $coreMenu->addChild($planEstudiosMenu);
            
            //OFERTA_ACADEMICA_MENU
            $ofertaMenu = $menuManager->getItem('mnu.oferta');
            
            $ofertaMenu->addChild($menuManager->newItem('mnu.oferta.academica'));
            $ofertaMenu->addChild($menuManager->newItem('mnu.oferta.seccion'));
            $ofertaMenu->addChild($menuManager->newItem('mnu.oferta.grupos'));
            $coreMenu->addChild($ofertaMenu);
            
            //OFERTA_ACADEMICA_MENU
            $personasMenu = $menuManager->getItem('mnu.persona', array('icon' => 'glyphicon glyphicon-user'));
            $personasMenu->addChild($menuManager->newItem('mnu.persona.users',array('route' => 'user')));
            $personasMenu->addChild($menuManager->newItem('mnu.persona.groups',array('route' => 'group')));
            $personasMenu->addChild($menuManager->newItem('mnu.persona.roles',array('route' => 'role')));
            
            $coreMenu->addChild($personasMenu);
            
            //INSCRIPCION_MENU
            $inscripcionMenu = $menuManager->getItem('mnu.inscripcion');
            $inscripcionMenu->addChild($menuManager->newItem('mnu.inscripcion.pre'));
            $inscripcionMenu->addChild($menuManager->newItem('mnu.inscripcion.post'));
            $coreMenu->addChild($inscripcionMenu);
            
            
            //CONFIGURATION_MENU
            $configMenu = $menuManager->getItem('mnu.settings', array('icon' => 'glyphicon glyphicon-cog'));
            $configMenu->addChild($menuManager->newItem('mnu.settings.general'));
                
                //LOCALIZATION_MENU
                $localizationMenu = $menuManager->getItem('mnu.settings.localization');
                $localizationMenu->addChild($menuManager->newItem('mnu.settings.localization.country',array('route' => 'pais')));
                $localizationMenu->addChild($menuManager->newItem('mnu.settings.localization.estate'));
                $localizationMenu->addChild($menuManager->newItem('mnu.settings.localization.municipios'));
                $configMenu->addChild($localizationMenu);
                
            $coreMenu->addChild($configMenu);

            // PROFILE_MENU
            $profileMenu = $menuManager->getItem('mnu.profile', array('label' => 'Mi Perfil','icon' => 'glyphicon glyphicon-user'));
            
            $profileMenu->setAttribute('style', 'float:right');
            $profileMenu->addChild($menuManager->newItem('mnu.profile.detail'));
            $profileMenu->addChild($menuManager->newItem('mnu.profile.settings'));
            $profileMenu->addChild($menuManager->newItem('mnu.profile.bloq'));
            $profileMenu->addChild($menuManager->newItem('mnu.profile.logout',array('route' => 'fos_user_security_logout')));
            $coreMenu->addChild($profileMenu);
        
        $menuManager->append($coreMenu);
    }
}