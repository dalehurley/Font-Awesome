<?php

class sidWelcomeComponents extends dmAdminBaseComponents
{
  
  public function executeLittle()
  {
    $this->chartKey = $this->name;
    $name = $this->name;
//    foreach ($typeModule as $typeName => $type) {
//            foreach($type as $i => $data){
//                echo 'i : '.$i.' et data : '.$data.'<br />';
//            };
//            $typeMenu = $this->addChild($type->getPublicName())
//                    ->ulClass('ui-widget ui-widget-content level1 ')
//                    ->liClass('ui-corner-top ui-state-default');
//
//            if ($type->isProject()) {
//                $typeMenu->credentials('content');
//            }
//
//            foreach ($type->getSpaces() as $spaceName => $space) {
//                $spaceMenu = $typeMenu->addChild($space->getPublicName(), '/admin_dev.php/content/base-editoriale/sections/index')
//                        ->ulClass('level2 ');
//
//                foreach ($space->getModules() as $moduleKey => $module) {
//                    if ($this->user->canAccessToModule($module)) {
//                        $spaceMenu->addChild($module->getPlural())->link('@' . $module->getUnderscore())->ulClass($module->getParent()->getPublicName());
//                    }
//                }
//
//                if (!$spaceMenu->hasChildren()) {
//                    $typeMenu->removeChild($spaceMenu);
//                }
//            }
//
//            if (!$typeMenu->hasChildren()) {
//                $this->removeChild($typeMenu);
//            }
//        }
  }
  
  
}