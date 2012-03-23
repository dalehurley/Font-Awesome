<?php

/**
 * recrutementAdmin admin form
 *
 * @package    sitev3-demo3
 * @subpackage recrutementAdmin
 * @author     Your name here
 */
class SidCabinetRecrutementAdminForm extends BaseSidCabinetRecrutementForm
{
  public function configure()
  {
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
        $this->widgetSchema['title_file']->setAttributes(array('class' => 'input_short'));
        $this->widgetSchema->setHelps(array(
//            'title_entete_page' => '<b>Cet intitulé sera le titre de la page (100 caractères MAXI)</b>',
            'text' => '<u>Information</u>: <br /> - pour un retour à la ligne : faire Maj + ENTREE en même temps <br /> - pour un nouveau paragraphe : faire ENTREE',
            'file_form' => 'Vous pouvez insérer un fichier des formats suivants : .pdf, doc, .docx, .xls, .xlsx, .odt, .ods, .zip.',
            'title_file' => 'Nouveau nom de votre fichier',));
        
        }
}