<?php

class dmAdminMenu extends dmMenu {

    public function build() {
        $this
                ->name('Admin menu')
                ->ulClass('ui-helper-reset level0')
                ->addModules();

        $this->serviceContainer->getService('dispatcher')->notify(new sfEvent($this, 'dm.admin.menu', array()));

        return $this;
    }

    protected function addModules() {

        // Tri des modules par ordre alphabétique pour affichage dans admin
        $typeModule = $this->serviceContainer->getService('module_manager')->getTypes();
        ksort($typeModule);
//      foreach($this->serviceContainer->getService('module_manager')->getTypes() as $typeName => $type)
        foreach ($typeModule as $typeName => $type) {
            $typeMenu = $this->addChild($type->getPublicName())
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

        return $this;
    }

    public function renderLabel() {
        return '<a>' . parent::renderLabel() . '</a>';
    }

}