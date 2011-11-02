<?php

class dmWidgetPersoContentTextareaView extends dmWidgetPluginView
{


  public function configure()
  {
    parent::configure();

    $this->addRequiredVar(array('libelle'));

  }


  protected function filterViewVars(array $vars = array())
  {
    $vars = parent::filterViewVars($vars);

    $vars['libelle'] = $vars['libelle'];

    /* affichage  par défaut */
    $vars['aloha'] = false;
    $vars['jeditable'] = false;
    $vars['ckeip'] = false; 

    $arrayUserPerms = sfContext::getInstance()->getUser()->getAllPermissionNames(); // récupération des permissions du user

    if (in_array('aloha_widget_textarea', $arrayUserPerms)){
        $vars['aloha'] = false; // aloha editor
        $vars['jeditable'] = false; // jeditable editor
        $vars['ckeip'] = true;  // ckeditor in place editor
    }

    $vars['widgetId'] = dmArray::get($this->widget, 'id'); // récupération de l'id du widget en cours

    return $vars;
  }



}