<?php

/**
 * sidAddedPagesLevel2Admin admin form
 *
 * @package    ec-tenor
 * @subpackage sidAddedPagesLevel2Admin
 * @author     Your name here
 */
class SidAddedPagesLevel2AdminForm extends BaseSidAddedPagesLevel2Form
{
  protected static $addedPageGroup = array(); // variable pour récupérer les groupes
  protected static $addedPageLevel1 = array(); // variable pour récupérer les niveau1 d'un groupe
  protected static $validateAddedPageLevel1 = array(); // variable pour récupérer les id des pages de niveau 1 pour le validator
  
  public function configure()
  {
    parent::configure();
            // Récupération des différents groupes
            $groupPages = dmDb::table('SidAddedPagesGroups')
                    ->createQuery('a')
                    ->where('a.is_active =?', true)
                    ->orderBy('a.position')
                    ->execute();
            foreach ($groupPages as $groupId) {
                // récupération des noms des pages de niveau 1 du groupe
                $level1Page = dmDb::table('SidAddedPagesLevel1')
                        ->createQuery('p')
                        ->where('p.is_active = ? AND p.group_id = ?', array(true, $groupId->id))
                        ->orderBy('p.position')
                        ->execute();

                foreach ($level1Page as $title) {
                    self::$addedPageLevel1[$title->id] = ucfirst($title->getTitle());
                    self::$validateAddedPageLevel1[$title->id] = $title->id;
                    };
          echo '22222<pre>';print_r(self::$addedPageLevel1);echo'</pre>';
                self::$addedPageGroup[$groupId->getTitle()] = self::$addedPageLevel1; 
                self::$addedPageLevel1 = array();
                }
            
        echo '33333<pre>';print_r(self::$addedPageGroup);echo'</pre>'; 
            $this->widgetSchema['level1_id'] = new sfWidgetFormChoice(array(
                    'choices' => self::$addedPageGroup,
                ));
            $this->validatorSchema['level1_id'] = new sfValidatorChoice(array('choices' => array_keys(self::$validateAddedPageLevel1)));
  }

    protected function createMediaFormForImage() {
        $form = parent::createMediaFormForImage();
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