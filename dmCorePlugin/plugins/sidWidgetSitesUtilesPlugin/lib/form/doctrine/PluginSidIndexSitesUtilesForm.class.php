<?php

/**
 * PluginSidIndexSitesUtiles form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id$
 */
abstract class PluginSidIndexSitesUtilesForm extends BaseSidIndexSitesUtilesForm
{
  public function setup()
  {
    parent::setup();
    /*
     * Here, the plugin form code
     */
    $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText(array('default' => 'Dossiers'));
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => true
                ));
  }
}