<?php

/**
 * sidActuArticleAdmin admin form
 *
 * @package    sitev3-demo3
 * @subpackage sidActuArticleAdmin
 * @author     Your name here
 */
class SidActuArticleAdminForm extends BaseSidActuArticleForm {

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
	    'application/pdf',
	    'application/msword', // .doc
	    'application/vnd.oasis.opendocument.text', // .odt
	    'web_images'
	));


	return $form;
    }

// @TODO Modifier les models pour n'afficher que les types actifs
//  public function setup() {
//        parent::setup();
//            $this->widgetSchema['sid_actu_type_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'SidActuType', 'expanded' => true, 'default' => 2));
//        }

    public function setup() {
	parent::setup();
        $this->widgetSchema['title_file']->setAttributes(array('class' => 'input_short'));
//	$this->widgetSchema['m_sections_list'] = new sfWidgetFormDoctrineChoice(array(
//		    'model' => 'SidSection',
//		    'method' => 'show_rubrique_section',
//		    'multiple' => true,
//		    'expanded' => true,  // pour avoir des cases à cocher
//		    //'add_empty' => '-- Sections --'
//		));
        $this->widgetSchema->setHelps(array(
            'file_form' => 'Vous pouvez insérer des fichiers : Pdf, Word, OpenOffice ainsi que des images',
            'title_file' => 'nouveau nom de votre fichier',
	    'm_sections_list' => 'Vous pouvez lier ce membre de votre equipe à une rubrique/section (Affichage contextuel).',
            'text' => '<u>Information</u>: <br /> - pour un retour à la ligne : faire Maj + ENTREE en même temps <br /> - pour un nouveau paragraphe : faire ENTREE',
            'tags_list' => 'Sélectionnez les mots en rapport avec votre lien pour les faire apparaitre dans les pages les plus adéquates de votre site',
	));
        $this->widgetSchema['m_rubriques_list'] = new sfWidgetFormDoctrineChoice(array(
		    'model' => 'SidRubrique',
		    'method' => 'show_list_rubrique',
		    'multiple' => true,
		    'expanded' => true,  // pour avoir des cases à cocher
		    //'add_empty' => '-- Sections --'
		));
      
        if(sfContext::getInstance()->getUser()->isSuperAdmin()){
        $this->widgetSchema['sid_actu_type_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'SidActuType', 'expanded' => true));
    }
    else if(!sfContext::getInstance()->getUser()->isSuperAdmin()){
        $actuType = dmDb::table('SidActuType')->findOne();
        (!is_object($actuType)) ? $actuTypeId = '':$actuTypeId = $actuType->id;
        $this->widgetSchema['sid_actu_type_list'] = new sfWidgetFormInputHidden(array(),array('value' => $actuTypeId));
    }

    }
    

}