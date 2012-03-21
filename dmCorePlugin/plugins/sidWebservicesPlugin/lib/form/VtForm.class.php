<?php

class VtForm extends BaseForm
{
	
	 public function configure()
	{

		$this->setWidgets(array(
	      'departement'    => new sfWidgetFormInputText(),
	      
	     	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'departement'    => 'Le num&eacute;ro de votre d&eacute;partement',
		  
		 
		));
		$this->widgetSchema->setNameFormat('maform[%s]');
	     $this->setValidators(array(
	      'departement'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le num&eacute;ro de votre d&eacute;partement est obligatoire.',
	      		'invalid'=>'Le num&eacute;ro de votre d&eacute;partement doit être',
		      )),

	     
    )); 
   
	}



}