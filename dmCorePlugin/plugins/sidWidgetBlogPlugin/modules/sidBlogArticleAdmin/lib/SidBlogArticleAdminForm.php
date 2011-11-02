<?php

/**
 * sidBlogArticleAdmin admin form
 *
 * @package    sitev3-trunk-stef
 * @subpackage sidBlogArticleAdmin
 * @author     Your name here
 */
class SidBlogArticleAdminForm extends BaseSidBlogArticleForm
{
  public function configure()
  {
    parent::configure();
  }

 protected function createMediaFormForFile()
  {
//  $this->validatorSchema['file'] = new sfValidatorFile(array('mime_types'=> array('web_images','application/pdf', 'application/zip'),'required'=>false));
//      $this->widgetSchema->setHelp('file',$this->getFile());
//    return DmMediaForRecordForm::factory($this->object, 'file', 'Files', $this->validatorSchema['file']->setOption('mime_types'=> array('web_images','application/pdf', 'application/zip')));
  // get the DmMedia form
    $form = parent::createMediaFormForFile();

    // choose mime types allowed
    $form->setMimeTypeWhiteList(array(
      'application/pdf',
      'application/msword', // .doc
      'application/vnd.oasis.opendocument.text', // .odt
      'web_images'
    ));

     
    return $form;
  }
}