<?php

class dmFrontAddMenu extends dmMenu
{

  public function build()
  {
    $this
    ->name('Front add menu')
    ->ulClass('ui-helper-reset level0')
    ->addChild('Add')
    ->setOption('root_add', true)
    ->ulClass('ui-widget ui-widget-content level1')
    ->liClass('ui-corner-bottom ui-state-default')
    ->addClipboard()
    ->addWidgets();
    
    $this->serviceContainer->getService('dispatcher')->notify(new sfEvent($this, 'dm.front.add_menu', array()));

    return $this;
  }

  public function addClipboard()
  {
    if($widget = $this->serviceContainer->getService('front_clipboard')->getWidget())
    {
      $this
      ->addChild('Clipboard')->credentials('widget_add')->ulClass('clearfix level2')->liClass('dm_droppable_widgets')
      ->addChild($this->serviceContainer->get('widget_type_manager')->getWidgetType($widget)->getName())
      ->setOption('clipboard_widget', $widget)
      ->setOption('clipboard_method', $this->serviceContainer->getService('front_clipboard')->getMethod());
    }

    return $this;
  }
  
  public function addWidgets()
  {
    $moduleManager = $this->serviceContainer->getService('module_manager');
    
    $widgets = $this->serviceContainer->get('widget_type_manager')->getWidgetTypes();

    //ksort($widgets); // tri par spacename
    $spaceNamesAff = array();
    //var_dump($widgets);
    
    // chargement du tableau de filtrage des widgets, s'il en existe un ou plusieurs pour le ou les groupes du user en cours
    if (!sfContext::getInstance()->getUser()->isSuperAdmin()){
      // récupérration des groups du user
      $userGroups = sfContext::getInstance()->getUser()->getGroupNames();
      $globalWidgetsToDisplay = array();
      foreach ($userGroups as $group) {
        $widgetsToDisplay = sfConfig::get('app_filtre-affichage-widget-module-action_'.$group);
        if (is_array($widgetsToDisplay) && count($widgetsToDisplay)>1){ // si le tableau de widget à afficher existe et est rempli
          $globalWidgetsToDisplay = array_merge($globalWidgetsToDisplay, $widgetsToDisplay); // on l'ajoute au tableau global
        }
      }
    }  
    
    foreach($widgets as $space => $widgetTypes)
    {
      $spaceName = ($module = $moduleManager->getModuleOrNull($space))
      ? $module->getName()
      : dmString::humanize(str_replace('dmWidget', '', $space));

      // lioshi: afficher une seule fois un spacename
      if (in_array($spaceName, $spaceNamesAff)) {
        $spaceName = '';
      } else {
        $spaceNamesAff[] = $spaceName;
      }
      
      $spaceMenu = $this->addChild($space)
      ->label($this->getI18n()->__($spaceName))
      ->ulClass('clearfix level2')
      ->liClass('dm_droppable_widgets');
      
      foreach($widgetTypes as $key => $widgetType)
      {
        // affichage de seulement quelques widgets
        if (!sfContext::getInstance()->getUser()->isSuperAdmin()){
          if (is_array($globalWidgetsToDisplay) && count($globalWidgetsToDisplay)>1){
            if (in_array($widgetType->getModule().'-'.$widgetType->getAction(), $globalWidgetsToDisplay)){
              $displayWidgetButton = true; // on n'affiche que les widgets listés
            } else {
              $displayWidgetButton = false;
            }
          } else { // sinon pas de tableau "filtre-affichage-widget_module-action" dans le app.yml, on affiche tous les widgets
            $displayWidgetButton = true;
          }
        } else {
          $displayWidgetButton = true;
        }

        if ($displayWidgetButton) {
          $spaceMenu
          ->addChild($widgetType->getName())
          ->label($this->getI18n()->__($widgetType->getName()))
          ->setOption('widget_type', $widgetType);
        }
      }

      if(!$spaceMenu->hasChildren())
      {
        $this->removeChild($spaceMenu);
      }
    }
    
    return $this;
  }

  public function renderLabel()
  {
    if($widgetType = $this->getOption('widget_type'))
    {
      return sprintf('<span class="tipable widget_add move" id="dmwa_%s-%s" title="%s">%s</span>',
        $widgetType->getModule(),
        $widgetType->getAction(),
        $widgetType->getPublicName() . ' ('.$widgetType->getModule().'-'.$widgetType->getAction().')' . '&#10;- View Class: ' .$widgetType->getViewClass(). '&#10;- Form Class: ' .$widgetType->getFormClass(),
        dmString::strtolower(parent::renderLabel())              
                );
    }
    elseif($widget = $this->getOption('clipboard_widget'))
    {
      return sprintf('<span class="widget_paste move dm_%s" id="dmwp_%d">%s</span>',
        $this->getOption('clipboard_method'),
        $widget->get('id'),
        dmString::strtolower(parent::renderLabel())
      );
    }
    elseif($this->getOption('root_add'))
    {
      return '<a class="tipable s24block s24_add widget24" title="'.$this->__('Add widgets').'"></a>';
    }
    
    return '<a>'.dmString::strtolower(parent::renderLabel()).'</a>';
  }
}