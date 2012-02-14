<?php

/**
 * missionAdmin admin form
 *
 * @package    sitev3-demo3
 * @subpackage missionAdmin
 * @author     Your name here
 */
class SidCabinetMissionAdminForm extends BaseSidCabinetMissionForm {

    public function configure() {
	parent::configure();
    }

    protected function createMediaFormForImage() {
        $form = parent::createMediaFormForImage();
        unset($form['legend'], $form['author'], $form['license']);
        return $form;
    }
    
    public function setup() {
	parent::setup();
//        $this->widgetSchema['tags_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'DmTag', 'expanded' => false));
//        $this->validatorSchema['tags_list'] = new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'DmTag', 'required' => false)); 
	// garder la valeur si on n'affiche pas le titre
//	if (sfContext::getInstance()->getUser()->isSuperAdmin() == false) {
//	    $this->widgetSchema['title'] = new sfWidgetFormInputHidden();
//	}

	$this->widgetSchema['m_sections_list'] = new sfWidgetFormDoctrineChoice(array(
		    'model' => 'SidSection',
		    'method' => 'show_rubrique_section',
		    'multiple' => true,
		    'expanded' => true,  // pour avoir des cases à cocher
		    //'add_empty' => '-- Sections --'
		));
	        
        $this->widgetSchema->setHelps(array(
            'm_sections_list' => 'Vous pouvez lier cet mission à une rubrique/section (Affichage contextuel).',   
            'title' => '<b>Ce libellé apparaitra dans le menu et dans le fil d\'ariane</b>', 
            'tags_list' => 'Sélectionnez les mots en rapport avec votre lien pour les faire apparaitre dans les pages les plus adéquates de votre site',
            'text' => '<u>Information</u>: <br /> - pour un retour à la ligne : faire Maj + ENTREE en même temps <br /> - pour un nouveau paragraphe : faire ENTREE',
        ));
                $this->widgetSchema['m_rubriques_list'] = new sfWidgetFormDoctrineChoice(array(
		    'model' => 'SidRubrique',
		    'method' => 'show_list_rubrique',
		    'multiple' => true,
		    'expanded' => true,  // pour avoir des cases à cocher
		    //'add_empty' => '-- Sections --'
		));
    }    
    

}