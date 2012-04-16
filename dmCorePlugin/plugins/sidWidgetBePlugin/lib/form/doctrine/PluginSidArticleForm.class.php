<?php

/**
 * PluginSidArticle form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id$
 */
abstract class PluginSidArticleForm extends BaseSidArticleForm
{
  public function setup()
  {
    parent::setup();
	}

	public function configure(){
		parent::configure();
    $this->widgetSchema['widthImage'] = new sfWidgetFormInputText(array('label' => 'Largeur en px'));
    $this->validatorSchema['widthImage'] = new dmValidatorCssSize(array(
                  'required' => false
                ));

    $this->widgetSchema['withImage'] = new sfWidgetFormInputCheckbox(array('default'=> true, 'label' => 'Image visible dans les articles'));
    $this->validatorSchema['withImage']  = new sfValidatorBoolean();
  }
}