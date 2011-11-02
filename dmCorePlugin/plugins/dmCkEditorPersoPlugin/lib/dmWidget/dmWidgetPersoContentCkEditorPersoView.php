<?php

class dmWidgetPersoContentCkEditorPersoView extends dmWidgetPluginView
{
  public function configure()
  {
    parent::configure();

    $this->addRequiredVar(array('html'));
   
  }


protected function filterViewVars(array $vars = array())
  {
    $vars = parent::filterViewVars($vars);

    //$vars['html'] = $vars['html'];
    if (!isset($vars['perso'])) $vars['perso'] = false;  // widget personnalisable "in place"
    if (!isset($vars['bandeau'])) $vars['bandeau'] = false;
    // option du bandeau
    $vars['optionbandeau'] = '';
    if (isset($vars['optionbandeauloop'])) $vars['optionbandeau'] .= ' loop="'.$vars['optionbandeauloop'].'"';
    if (isset($vars['optionbandeaubehavior'])) $vars['optionbandeau'] .= ' behavior="'.$vars['optionbandeaubehavior'].'"';
    if (isset($vars['optionbandeaudirection'])) $vars['optionbandeau'] .= ' direction="'.$vars['optionbandeaudirection'].'"';
    if (isset($vars['optionbandeauscrollamount'])) $vars['optionbandeau'] .= ' scrollamount="'.$vars['optionbandeauscrollamount'].'"';
    if (isset($vars['optionbandeauheight'])) $vars['optionbandeau'] .= ' height="'.$vars['optionbandeauheight'].'"';
    if (isset($vars['optionbandeauwidth'])) $vars['optionbandeau'] .= ' width="'.$vars['optionbandeauwidth'].'"';


    // affichage  par défaut
    $vars['ckeip'] = false;

    $arrayUserPerms = sfContext::getInstance()->getUser()->getAllPermissionNames(); // récupération des permissions du user

    if (in_array('in_place_widget_edit', $arrayUserPerms) && $vars['perso']) {
            $vars['ckeip'] = true;  // ckeditor in place editor
            $vars['configPerso'] = array_merge(array(
                        'language' => dmDoctrineRecord::getDefaultCulture(),
                            ), sfConfig::get('dm_ckeditorperso_config'));

            // cas du bandeau déroulant
            if ($vars['bandeau']) {
                $vars['configPerso'] = array_merge(array(
                            'plugins' => 'wysiwygarea',
                            'height' => 40,
                                ), $vars['configPerso']);
            }

    }

    $vars['widgetId'] = dmArray::get($this->widget, 'id'); // récupération de l'id du widget en cours

    return $vars;
  }


/*
  protected function doRender()
  {
    $vars = $this->getViewVars();

    return $vars['html'];
  }

  public function doRenderForIndex()
  {
    return strip_tags($this->compiledVars['html']);
  }
 
 */
}