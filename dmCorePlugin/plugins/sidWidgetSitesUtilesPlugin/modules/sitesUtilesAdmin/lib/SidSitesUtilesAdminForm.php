<?php

/**
 * sitesUtilesAdmin admin form
 *
 * @package    sitev3-demo1
 * @subpackage sitesUtilesAdmin
 * @author     Your name here
 */
class SidSitesUtilesAdminForm extends BaseSidSitesUtilesForm {

    protected static $arrayMimeType = 
        array(
	    'application/pdf', // .pdf
	    'application/msword', // .doc
	    'application/vnd.oasis.opendocument.text', // .odt
	    'web_images',
            'application/zip', // .zip
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
            'application/vnd.ms-excel', // .xls
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
            'application/vnd.oasis.opendocument.spreadsheet', // .ods
	); // tableau pour les autorisations de download de fichiers
    
    public function configure() {
        parent::configure();
    }

    protected function createMediaFormForImageId() {
        $form = parent::createMediaFormForImageId();
        unset($form['author'], $form['license']);
        return $form;
    }
    
    protected function createMediaFormForFile1() {
	$form = parent::createMediaFormForFile1();
        unset($form['author'], $form['license']); 

	// choose mime types allowed
	$form->setMimeTypeWhiteList(self::$arrayMimeType);
        return $form;
    }
  protected function createMediaFormForFile2() {
	$form = parent::createMediaFormForFile2();
        unset($form['author'], $form['license']); 

	// choose mime types allowed
	$form->setMimeTypeWhiteList(self::$arrayMimeType);
        return $form;
    }
  protected function createMediaFormForFile3() {
	$form = parent::createMediaFormForFile3();
        unset($form['author'], $form['license']); 

	// choose mime types allowed
	$form->setMimeTypeWhiteList(self::$arrayMimeType);
        return $form;
    }
  protected function createMediaFormForFile4() {
	$form = parent::createMediaFormForFile4();
        unset($form['author'], $form['license']); 

	// choose mime types allowed
	$form->setMimeTypeWhiteList(self::$arrayMimeType);
        return $form;
    }
  protected function createMediaFormForFile5() {
	$form = parent::createMediaFormForFile5();
        unset($form['author'], $form['license']); 

	// choose mime types allowed
	$form->setMimeTypeWhiteList(self::$arrayMimeType);
        return $form;
    }

    public function setup() {
        parent::setup();

        $this->widgetSchema->setHelps(array(
            'groupe_sites_utiles_id' => 'Sélectionnez un groupe afin d\'affiner votre classement de sites utiles',
//            'description' => '<u>Information</u>: <br /> - pour un retour à la ligne : faire Maj + ENTREE en même temps <br /> - pour un nouveau paragraphe : faire ENTREE',
            'tags_list' => 'Sélectionnez les mots en rapport avec votre lien pour les faire apparaitre dans les pages les plus adéquates de votre site',
            'url' => 'Noter "http://" avant l\'adresse internet de votre lien',
            'file1_form' => 'Vous pouvez insérer un fichier des formats suivants : .pdf, doc, .docx, .xls, .xlsx, .odt, .ods, .zip.',
            'file2_form' => 'Vous pouvez insérer un fichier des formats suivants : .pdf, doc, .docx, .xls, .xlsx, .odt, .ods, .zip.',
            'file3_form' => 'Vous pouvez insérer un fichier des formats suivants : .pdf, doc, .docx, .xls, .xlsx, .odt, .ods, .zip.',
            'file4_form' => 'Vous pouvez insérer un fichier des formats suivants : .pdf, doc, .docx, .xls, .xlsx, .odt, .ods, .zip.',
            'file5_form' => 'Vous pouvez insérer un fichier des formats suivants : .pdf, doc, .docx, .xls, .xlsx, .odt, .ods, .zip.',
	       ));
    }

}