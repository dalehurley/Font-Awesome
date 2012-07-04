<?php

/**
 * sidAddedPagesAdmin admin form
 *
 * @package    ec-tenor
 * @subpackage sidAddedPagesAdmin
 * @author     Your name here
 */
class SidAddedPagesAdminForm extends BaseSidAddedPagesForm
{
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
    public function configure()
  {
    parent::configure();
    
    $this->widgetSchema['nested_set_parent_id']->setOption('query',Doctrine_Core::getTable('SidAddedPages')->createQuery()->addWhere('id <> ?',$this->object->getPrimaryKey()));
    if(!sfContext::getInstance()->getUser()->isSuperAdmin() && sfContext::getInstance()->getUser()->getUsername() != 'admin-co-pilotes'){
        $this->widgetSchema['nested_set_parent_id']->setOption('add_empty',false);
    };
    $this->widgetSchema->setHelps(array(
            'file1_form' => 'Vous pouvez insérer un fichier des formats suivants : .pdf, doc, .docx, .xls, .xlsx, .odt, .ods, .zip.',
            'file2_form' => 'Vous pouvez insérer un fichier des formats suivants : .pdf, doc, .docx, .xls, .xlsx, .odt, .ods, .zip.',
            'file3_form' => 'Vous pouvez insérer un fichier des formats suivants : .pdf, doc, .docx, .xls, .xlsx, .odt, .ods, .zip.',
            'file4_form' => 'Vous pouvez insérer un fichier des formats suivants : .pdf, doc, .docx, .xls, .xlsx, .odt, .ods, .zip.',
            'file5_form' => 'Vous pouvez insérer un fichier des formats suivants : .pdf, doc, .docx, .xls, .xlsx, .odt, .ods, .zip.',
	       ));
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
    
//    
//  public function getChoices()
//  {
//      parent::getChoices();
//
//    if (null === $this->getOption('table_method'))
//    {
//      $query = null === $this->getOption('query') ? Doctrine_Core::getTable($this->getOption('model'))->createQuery() : $this->getOption('query');
//      $query->addWhere('id <> ?',$this->object->getPrimaryKey());
//      if ($order = $this->getOption('order_by'))
//      {
//        $query->addOrderBy($order[0] . ' ' . $order[1]);
//      }
//      echo '************'.$query;
//      $objects = $query->execute();
//    }
//  }
}