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
    
    protected function createMediaFormForFile() {
	$form = parent::createMediaFormForFile();
        unset($form['legend'], $form['author'], $form['license']); 

	// choose mime types allowed
	$form->setMimeTypeWhiteList(array(
	    'application/pdf', // .pdf
	    'application/msword', // .doc
	    'application/vnd.oasis.opendocument.text', // .odt
	    'web_images',
            'application/zip', // .zip
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
            'application/vnd.ms-excel', // .xls
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
            'application/vnd.oasis.opendocument.spreadsheet', // .ods
            
	));
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
//            'text' => '<u>Information</u>: <br /> - pour un retour à la ligne : faire Maj + ENTREE en même temps <br /> - pour un nouveau paragraphe : faire ENTREE',
            'file_form' => 'Vous pouvez insérer un fichier des formats suivants : .pdf, doc, .docx, .xls, .xlsx, .odt, .ods, .zip.',
            'title_file' => 'Nouveau nom de votre fichier',
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