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
    
        $this->widgetSchema['title_file']->setAttributes(array('class' => 'input_short'));

        
//        $this->widgetSchema['debut_date']->getOptions(array('required' => false));
//        $this->validatorSchema['debut_date']->getOptions(array('required' => false, 'date_format' => 'Y-m-d','date_format_error' => 'Y-m-d'));
//        
//        $this->widgetSchema['fin_date']->getOptions(array('required' => false));
//        $this->validatorSchema['fin_date']->getOptions(array('required' => false,'date_format' => 'Y-m-d','date_format_error' => 'Y-m-d'));

        $this->widgetSchema['m_rubriques_list'] = new sfWidgetFormDoctrineChoice(array(
		    'model' => 'SidRubrique',
		    'method' => 'show_list_rubrique',
		    'multiple' => true,
		    'expanded' => true,  // pour avoir des cases à cocher
                    
		    //'add_empty' => '-- Sections --'
		  ));
      
        if(sfContext::getInstance()->getUser()->isSuperAdmin()){
        $this->widgetSchema['sid_actu_type_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'SidActuType', 'expanded' => true));
        $this->widgetSchema->setHelp('debut_date', 'Utilisé par le widget actuArticleContextuel');
        }
        else if(!sfContext::getInstance()->getUser()->isSuperAdmin()){
            $actuType = dmDb::table('SidActuType')->findOne();
            (!is_object($actuType)) ? $actuTypeId = '':$actuTypeId = $actuType->id;
            $this->widgetSchema['sid_actu_type_list'] = new sfWidgetFormInputHidden(array(),array('value' => $actuTypeId));
        }
    
    $this->widgetSchema->setHelps(array(
            'file_form' => 'Vous pouvez insérer un fichier des formats suivants : .pdf, doc, .docx, .xls, .xlsx, .odt, .ods, .zip.',
            'title_file' => 'Nouveau nom de votre fichier',
            'm_sections_list' => 'Vous pouvez lier ce membre de votre equipe à une rubrique/section (Affichage contextuel).',
//            'text' => '<u>Information</u>: <br /> - pour un retour à la ligne : faire Maj + ENTREE en même temps <br /> - pour un nouveau paragraphe : faire ENTREE',
            'tags_list' => 'Sélectionnez les mots en rapport avec votre lien pour les faire apparaitre dans les pages les plus adéquates de votre site',
            'fin_date' => 'Assurez-vous que la date de fin soit postérieure à la date de début'
	       ));

    if(sfContext::getInstance()->getUser()->isSuperAdmin()){
        $this->widgetSchema->setHelp('debut_date', 'Utilisé par le widget actuArticleContextuel');
        }

	
    }
    protected function createMediaFormForImage() {
        $form = parent::createMediaFormForImage();
        unset($form['author'], $form['license']);
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

// @TODO Modifier les models pour n'afficher que les types actifs
//  public function setup() {
//        parent::setup();
//            $this->widgetSchema['sid_actu_type_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'SidActuType', 'expanded' => true, 'default' => 2));
//        }

}