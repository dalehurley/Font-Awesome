<?php

/**
 * sidAddedPagesLevel3Admin admin form
 *
 * @package    ec-tenor
 * @subpackage sidAddedPagesLevel3Admin
 * @author     Your name here
 */
class SidAddedPagesLevel3AdminForm extends BaseSidAddedPagesLevel3Form
{
  public function configure()
  {
    parent::configure();
  }

    protected function createMediaFormForImage1() {
        $form = parent::createMediaFormForImage1();
        unset($form['author'], $form['license']);
        return $form;
    }
  protected function createMediaFormForImage2() {
        $form = parent::createMediaFormForImage2();
        unset($form['author'], $form['license']);
        return $form;
    }
  protected function createMediaFormForImage3() {
        $form = parent::createMediaFormForImage3();
        unset($form['author'], $form['license']);
        return $form;
    }
  protected function createMediaFormForImage4() {
        $form = parent::createMediaFormForImage4();
        unset($form['author'], $form['license']);
        return $form;
    }
  protected function createMediaFormForImage5() {
        $form = parent::createMediaFormForImage5();
        unset($form['author'], $form['license']);
        return $form;
    }
  protected function createMediaFormForFile1() {
	$form = parent::createMediaFormForFile1();
        unset($form['author'], $form['license']); 

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
  protected function createMediaFormForFile2() {
	$form = parent::createMediaFormForFile2();
        unset($form['author'], $form['license']); 

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
  protected function createMediaFormForFile3() {
	$form = parent::createMediaFormForFile3();
        unset($form['author'], $form['license']); 

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
  protected function createMediaFormForFile4() {
	$form = parent::createMediaFormForFile4();
        unset($form['author'], $form['license']); 

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
  protected function createMediaFormForFile5() {
	$form = parent::createMediaFormForFile5();
        unset($form['author'], $form['license']); 

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
}