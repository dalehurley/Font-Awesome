<?php

class sidAccueilComponents extends dmAdminBaseComponents
{
  
  public function executeLittle()
  {
    $this->accueilKey = $this->name;
    $test = $this->getService('module_manager')->getModule($this->name)->getPlural();
    $this->link = $test;
//    foreach ($test as $typeName => $type) {
//            $typeMenu = $this->addChild($type->getPublicName())
//                    ->ulClass('ui-widget ui-widget-content level1')
//                    ->liClass('ui-corner-top ui-state-default');
//
//            if ($type->isProject()) {
//                $typeMenu->credentials('content');
//            }

//            foreach ($type->getSpaces() as $spaceName => $space) {
//                $this->link = _link($this->getServiceContainer()->getService('routing')->getModuleSpaceUrl($space))->text($space->getPublicName());
//                        ->ulClass('level2');

//                foreach ($space->getModules() as $moduleKey => $module) {
//                    if ($this->user->canAccessToModule($module)) {
//                        $spaceMenu->addChild($module->getPlural())->link('@' . $module->getUnderscore());
//                    }
//                }
//
//                if (!$spaceMenu->hasChildren()) {
//                    $typeMenu->removeChild($spaceMenu);
//                }
//            }

//            if (!$typeMenu->hasChildren()) {
//                $this->removeChild($typeMenu);
//            }
//        }
//    admin_module_space_menu
//    $this->accueil = $this->getService($this->name.'_accueil');
    
//    $this->accueilView = $this->getServiceContainer()
//    ->setParameter('accueil_view.class', get_class($this->accueil).'ViewLittle')
//    ->setParameter('accueil_view.accueil', $this->accueil)
//    ->getService('accueil_view');
  }
  
  public function executeLarge()
  {
    $this->accueilMessage = $this->name;
    $typeMenu = "";
    $this->getService('dispatcher')->notify(new sfEvent($this, 'dm.admin.menu', array()));
    $typeModule = $this->getService('module_manager')->getTypes();
        ksort($typeModule);
//      foreach($this->serviceContainer->getService('module_manager')->getTypes() as $typeName => $type)
        foreach ($typeModule as $typeName => $type) {
            $typeMenu .= _tag('h2',$type->getPublicName()).
                    _open('ul.elements').
                    _open('li.element');

            if ($type->isProject()) {
                $typeMenu->credentials('content');
            }

            foreach ($type->getSpaces() as $spaceName => $space) {
                $typeMenu .= _tag('h4',$space->getPublicName())._tag('ul.elements');

                
                foreach ($space->getModules() as $moduleKey => $module) {
                    if (sfContext::getInstance()->getUser()->hasCredential($module->getCredentials())) {
                        $typeMenu.=_link('@' . $module->getUnderscore())->text($module.' et credential : '.$module->getCredentials());
                    }
                }

               
            }

            
        }

        $this->link = $typeMenu;
  }
  
  public function executeTest()
  {
    $this->accueilMessage = $this->name;
    $menuAccueil = new dmAdminMenu('module_manager');
    $typeModule = $this->serviceContainer->getService('module_manager')->getTypes();
        ksort($typeModule);

//      foreach($this->serviceContainer->getService('module_manager')->getTypes() as $typeName => $type)
        foreach ($typeModule as $typeName => $type) {
            $typeMenu = $menuAccueil->addChild($type->getPublicName())
                    ->ulClass('ui-widget ui-widget-content level1')
                    ->liClass('ui-corner-top ui-state-default');

            if ($type->isProject()) {
                $typeMenu->credentials('content');
            }

            foreach ($type->getSpaces() as $spaceName => $space) {
                $spaceMenu = $typeMenu->addChild($space->getPublicName(),$this->serviceContainer->getService('routing')->getModuleSpaceUrl($space))
                        ->ulClass('level2');

                foreach ($space->getModules() as $moduleKey => $module) {
                    if ($this->user->canAccessToModule($module)) {
                        $spaceMenu->addChild($module->getPlural())->link('@' . $module->getUnderscore());
                    }
                }

                if (!$spaceMenu->hasChildren()) {
                    $typeMenu->removeChild($spaceMenu);
                }
            }

            if (!$typeMenu->hasChildren()) {
                $this->removeChild($typeMenu);
            }
        }
  }
  
}