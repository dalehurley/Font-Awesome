<?php

class dmWidgetPersoContentCkEditorPersoForm extends dmWidgetPluginForm
{

  public function configure()
  {

    parent::configure();

    $this->widgetSchema['html'] = new sfWidgetFormTextareaDmCkEditorPerso(array(
      'ckeditor' => $this->getService('ckeditor')
    ));
    $this->validatorSchema['html'] = new sfValidatorString();

    $this->widgetSchema['perso'] = new sfWidgetFormChoice(
            array(
                'choices' => array('non', 'oui'),
                'label' => 'Personnalisable?'
              ));
    $this->validatorSchema['perso'] = new sfValidatorChoice(array(
                'required' => false,
                'choices' => array(0, 1)
              ));
    $this->widgetSchema->setHelp('perso', 'Edition "en place" par le client');

    $this->widgetSchema['bandeau'] = new sfWidgetFormChoice(
            array(
                'choices' => array('non', 'oui'),
                'label' => 'Texte défilant?'
              ));
    $this->validatorSchema['bandeau'] = new sfValidatorChoice(array(
                'required' => false,
                'choices' => array(0, 1)
              ));
    $this->widgetSchema->setHelp('bandeau', 'Le contenu sera nettoyé avant affichage, tout code html sera ôté.');

    $this->widgetSchema['optionbandeauloop'] = new sfWidgetFormInputText(array('label' => 'Nombre de cycles'));
    $this->validatorSchema['optionbandeauloop'] = new sfValidatorInteger(array('required' => false));
    $this->widgetSchema->setHelp('optionbandeauloop', ' ');

    $this->widgetSchema['optionbandeaubehavior'] = new sfWidgetFormChoice(
            array(
                'choices' => array('scroll' => 'scroll', 'slide' => 'slide', 'alternate' => 'alternate'),
                'label' => 'Comportment'
              ));
    $this->validatorSchema['optionbandeaubehavior'] = new sfValidatorChoice(array(
                'required' => false,
                'choices' => array('scroll', 'slide', 'alternate')
              ));
    $this->widgetSchema->setHelp('optionbandeaubehavior', ' ');

    $this->widgetSchema['optionbandeaudirection'] = new sfWidgetFormChoice(
            array(
                'choices' => array('left' => 'left', 'down' => 'down', 'right' => 'right'),
                'label' => 'direction'
              ));
    $this->validatorSchema['optionbandeaudirection'] = new sfValidatorChoice(array(
                'required' => false,
                'choices' => array('left', 'down', 'right')
              ));
    $this->widgetSchema->setHelp('optionbandeaudirection', ' ');

    $this->widgetSchema['optionbandeauscrollamount'] = new sfWidgetFormInputText(array('label' => 'Vitesse'));
    $this->validatorSchema['optionbandeauscrollamount'] = new sfValidatorInteger(array('required' => false));
    $this->widgetSchema->setHelp('optionbandeauscrollamount', 'Le moins rapide = 1. Par défaut = 2.');

    $this->widgetSchema['optionbandeauheight'] = new sfWidgetFormInputText(array('label' => 'Hauteur visible'));
    $this->validatorSchema['optionbandeauheight'] = new sfValidatorInteger(array('required' => false));
    $this->widgetSchema->setHelp('optionbandeauheight', ' ');

    $this->widgetSchema['optionbandeauwidth'] = new sfWidgetFormInputText(array('label' => 'Largeur visible'));
    $this->validatorSchema['optionbandeauwidth'] = new sfValidatorInteger(array('required' => false));
    $this->widgetSchema->setHelp('optionbandeauwidth', ' ');

    // this input is created with javascript
    $this->validatorSchema['widget_width'] = new sfValidatorInteger(array('required' => false));

    /*
    $this->widgetSchema['perso'] = new sfWidgetFormInputText();
    $this->widgetSchema->setLabel('perso', 'Personnalisable?');
    $this->widgetSchema->setHelp('perso', 'Edition "en place" par le client');
    $this->validatorSchema['perso'] = new sfValidatorString();
*/

  }

  public function getStylesheets()
  {
    return array_merge(parent::getStylesheets(), array(
      'dmCkEditorPersoPlugin.widgetForm',
      'lib.ui-tabs'
    ));
  }

  public function getJavascripts()
  {
    $javascripts = parent::getJavascripts();
    array_unshift($javascripts,
            'lib.ui-tabs',
            'core.tabForm',
            'dmCkEditorPersoPlugin.widgetForm'
            );
    return $javascripts;
  }



  protected function renderContent($attributes)
  {
/*
      return $this->getHelper()->tag('ul.dm_form_elements',
                 $this->getHelper()->tag('li.dm_form_element.clearfix', $this['html']->field()->error())
                .$this->getHelper()->tag('li.dm_form_element.clearfix', $this['perso']->label()->field()->help()->error())
                .$this->getHelper()->tag('li.dm_form_element.clearfix', $this['bandeau']->label()->field()->help()->error())
                .$this->getHelper()->tag('li.dm_form_element.clearfix', $this['optionbandeau']->label()->field()->error())
                .$this->getHelper()->tag('li.dm_form_element.clearfix', $this['optionbandeau']->help())
        //. $this['cssClass']->renderRow()
        );
*/

       return $this->getHelper()->renderPartial('dmWidget', 'form', array(
            'form' => $this,
            'baseTabId' => 'dm_widget_ckeditorperso_' . $this->dmWidget->get('id')
        ));

  }
}