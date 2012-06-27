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
  protected static $addedPageGroup = array(); // variable pour récupérer les groupes
  protected static $addedPageLevel1 = array(); // variable pour récupérer les niveau1 d'un groupe
  protected static $addedPageLevel2 = array(); // variable pour récupérer les niveau1 d'un groupe
  protected static $validateAddedPageLevel2 = array(); // variable pour récupérer les id des pages de niveau 1 pour le validator
  
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

                foreach ($level1Page as $level1) {
                    // récupération des noms des pages de niveau 1 du groupe
                    $level2Page = dmDb::table('SidAddedPagesLevel2')
                        ->createQuery('p')
                        ->where('p.is_active = ? AND p.level1_id = ?', array(true, $level1->id))
                        ->orderBy('p.position')
                        ->execute();
                    
                    foreach ($level2Page as $title){
                        self::$addedPageLevel2[$title->id] = ucfirst($title->getTitle());
                        self::$validateAddedPageLevel2[$title->id] = $title->id;
                    }
                    self::$addedPageLevel1[$level1->getTitle()] = self::$addedPageLevel2;
                    self::$addedPageLevel2 = array();      
                    
                }
                self::$addedPageGroup[$groupId->getTitle()] = self::$addedPageLevel1; 
                self::$addedPageLevel1 = array();
                
            }   
                echo '<pre>';print_r(self::$addedPageGroup);echo '</pre>';
            $this->widgetSchema['level2_id'] = new sfWidgetFormSelectOptGroup(array(
                    'choices' => self::$addedPageGroup));
            $this->validatorSchema['level2_id'] = new sfValidatorChoice(array('choices' => array_keys(self::$validateAddedPageLevel2)));
//    $this->widgetSchema['level2_id'] = new sfWidgetFormDoctrineChoiceGrouped(array(
//            'model' => 'SidAddedPagesLevel2',
//            'multiple' => false,
//            'expanded' => false,
//            'method' => 'getLevel1->getName()',
//            'group_by' => 'level1_id'));
//        $this->validatorSchema['level2_id'] = new sfValidatorDoctrineChoice(array('model' => 'SidAddedPagesLevel2', 'multiple' => false, 'required' => true));
  
            
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