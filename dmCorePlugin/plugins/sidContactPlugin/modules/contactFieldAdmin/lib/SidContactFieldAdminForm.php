<?php

/**
 * contactFieldAdmin admin form
 *
 * @package    sitediem
 * @subpackage contactFieldAdmin
 * @author     Your name here
 */
class SidContactFieldAdminForm extends BaseSidContactFieldForm
{
  public function configure()
  {
    parent::configure();

    $widgetTypeChoices = array(
        'sfWidgetFormInputText' => 'sfWidgetFormInputText' ,
        'sfWidgetFormTextarea' => 'sfWidgetFormTextarea',
        'sfWidgetFormChoice' => 'sfWidgetFormChoice',
        'sfWidgetFormDate' => 'sfWidgetFormDate'
    );
    $validatorTypeChoices = array(
        'sfValidatorString' => 'sfValidatorString' ,
        'sfValidatorChoice' => 'sfValidatorChoice',
        'sfValidatorRegex' => 'sfValidatorRegex',
        'sfValidatorDate' => 'sfValidatorDate',
        'sfValidatorEmail' => 'sfValidatorEmail',
        'sfValidatorUrl' => 'sfValidatorUrl',
        'sfValidatorInteger' => 'sfValidatorInteger',
        'sfValidatorNumber' => 'sfValidatorNumber' 
    );

    // helps
    $widget_options_help = 'sfWidgetFormChoice:
* choices: An array of possible choices (required)
* multiple: true if the select tag must allow multiple selections
* expanded: true to display an expanded widget
if expanded is false, then the widget will be a select
if expanded is true and multiple is false, then the widget will be a list of radio
if expanded is true and multiple is true, then the widget will be a list of checkbox 



';

    $widget_attributes_help = '';
    $validator_options_help = '';
    $validator_messages_help = '';    


    $this->widgetSchema['widget_type'] = new sfWidgetFormChoice(array(
      'choices' => $widgetTypeChoices
    ));
    $this->validatorSchema['widget_type'] = new sfValidatorChoice(array(
      'choices' => array_keys($widgetTypeChoices),
      'required' => true
    ));

    $this->widgetSchema['validator_type'] = new sfWidgetFormChoice(array(
      'choices' => $validatorTypeChoices
    ));
    $this->validatorSchema['validator_type'] = new sfValidatorChoice(array(
      'choices' => array_keys($validatorTypeChoices),
      'required' => true
    ));

    // widget et validator fields
    $this->widgetSchema['widget_options'] = new sfWidgetFormTextarea(array(
    ));
    $this->validatorSchema['widget_options'] = new sfValidatorString(array(
      'required' => false
    ));
    $this->widgetSchema['widget_attributes'] = new sfWidgetFormTextarea(array(
    ));
    $this->validatorSchema['widget_attributes'] = new sfValidatorString(array(
      'required' => false
    ));
    $this->widgetSchema['validator_options'] = new sfWidgetFormTextarea(array(
    ));
    $this->validatorSchema['validator_options'] = new sfValidatorString(array(
      'required' => false
    ));
    $this->widgetSchema['validator_messages'] = new sfWidgetFormTextarea(array(
    ));
    $this->validatorSchema['validator_messages'] = new sfValidatorString(array(
      'required' => false
    ));    

    // helps
    $this->widgetSchema->setHelps(array(
      'widget_options'     => $widget_options_help,    
      'widget_attributes'  => $widget_attributes_help,
      'validator_options'  => $validator_options_help,
      'validator_messages' => $validator_messages_help
      )
    );

  }
}