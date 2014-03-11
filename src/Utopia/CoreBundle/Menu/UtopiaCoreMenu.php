<?php

namespace Utopia\CoreBundle\Menu;

use Yepsua\MenuBundle\Event\ConfigureMenuEvent;
use Yepsua\MenuBundle\Menu\MenuTree;

class UtopiaCoreMenu extends MenuTree
{
    /**
     * Generates the menu items for the Core Menu.
     * @param \Acme\DemoBundle\Event\ConfigureMenuEvent $event
     */
    public function configure(ConfigureMenuEvent $event)
    {
        $menuManager = $this->getMenuManager($event);
        
        // CORE_MENU
        $coreMenu  = $menuManager->getItem('core_menu');
        
            $coreMenu->addChild('Instituciónes');
        
            //ADMIN_MENU
            $adminMenu = $menuManager->getItem('admin_menu', array('label'=> 'Administración','icon' => 'glyphicon glyphicon-align-justify'));
            $adminMenu->addChild($menuManager->newItem('Titulos', array('icon' => 'glyphicon glyphicon-align-justify','route' => 'frontend')));
            $adminMenu->addChild($menuManager->newItem('Menciones'));
            $adminMenu->addChild($menuManager->newItem('Carreras'));
            $adminMenu->addChild($menuManager->newItem('Facultad'));
            $adminMenu->addChild($menuManager->newItem('Asignaturas'));
            $adminMenu->addChild($menuManager->newItem('Periodos'));
            $adminMenu->addChild($menuManager->newItem('Turnos'));
            $adminMenu->addChild($menuManager->newItem('Secciones'));
            $adminMenu->addChild($menuManager->newItem('Grupos'));
            

            $coreMenu->addChild($adminMenu);
            
            //PLAN_ESTUDIOS_MENU
            $planEstudiosMenu = $menuManager->getItem('plan_estudios_menu', array('label'=> 'Plan de Estudios'));
            $planEstudiosMenu->addChild($menuManager->newItem('Administrar'));
            $planEstudiosMenu->addChild($menuManager->newItem('Etapas'));
            $planEstudiosMenu->addChild($menuManager->newItem('Grupos'));
            $coreMenu->addChild($planEstudiosMenu);
            
            //OFERTA_ACADEMICA_MENU
            $ofertaMenu = $menuManager->getItem('oferta_academica_menu', array('label'=> 'Ofertas'));
            
            $ofertaMenu->addChild($menuManager->newItem('Oferta Academica'));
            $ofertaMenu->addChild($menuManager->newItem('Secciones'));
            $ofertaMenu->addChild($menuManager->newItem('Grupos'));
            $coreMenu->addChild($ofertaMenu);
            
            //OFERTA_ACADEMICA_MENU
            $personasMenu = $menuManager->getItem('personas_menu', array('label'=> 'Personas','route' => 'frontend','linkAttributes' => array('target' => '_blank')));

            $coreMenu->addChild($personasMenu);
            
            //INSCRIPCION_MENU
            $inscripcionMenu = $menuManager->getItem('inscripcion_menu', array('label'=> 'Inscripciones'));
            $inscripcionMenu->addChild($menuManager->newItem('Pre-Inscripciones', array('icon' => 'glyphicon glyphicon-user')));
            $inscripcionMenu->addChild($menuManager->newItem('Inscripciones'));
            $coreMenu->addChild($inscripcionMenu);
            
            
            //CONFIGURATION_MENU
            $configMenu = $menuManager->getItem('configuration_menu', array('label'=> 'Ajustes','icon' => 'glyphicon glyphicon-cog'));
            $configMenu->addChild($menuManager->newItem('Generales'));
                
                //LOCALIZATION_MENU
                $localizationMenu = $menuManager->getItem('localization_menu', array('label'=> 'Localizacion'));
                $localizationMenu->addChild($menuManager->newItem('Paises',array('route' => 'pais')));
                $localizationMenu->addChild($menuManager->newItem('Estados'));
                $localizationMenu->addChild($menuManager->newItem('Municipios'));
                $configMenu->addChild($localizationMenu);
                
            $coreMenu->addChild($configMenu);

            // PROFILE_MENU
            $profileMenu = $menuManager->getItem('profile_menu', array('label'=> 'Mi Perfil','icon' => 'glyphicon glyphicon-user','route' => 'frontend'));
            
            $profileMenu->setAttribute('style', 'float:right');
            $profileMenu->addChild($menuManager->newItem('Perfil'));
            $profileMenu->addChild($menuManager->newItem('Configuración'));
            $profileMenu->addChild($menuManager->newItem('Bloquear'));
            $profileMenu->addChild($menuManager->newItem('Cerrar Sesión',array('route' => 'fos_user_security_logout')));
            $coreMenu->addChild($profileMenu);
        
        $menuManager->append($coreMenu);
    }
}