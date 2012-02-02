<?php

/**
 * pageCabinetAdmin admin form
 *
 * @package    sitev3-demo3
 * @subpackage pageCabinetAdmin
 * @author     Your name here
 */
class SidCabinetPageCabinetAdminForm extends BaseSidCabinetPageCabinetForm
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
  
  public function setup() {
        parent::setup();
        $this->widgetSchema->setHelps(array(
            'text' => '<u>Information</u>: <br /> - pour un retour à la ligne : faire Maj + ENTREE en même temps <br /> - pour un nouveau paragraphe : faire ENTREE',
            'resume' => '<u>Information</u>: <br /> - pour un retour à la ligne : faire Maj + ENTREE en même temps <br /> - pour un nouveau paragraphe : faire ENTREE',
            'title' => '<b>Ce libellé apparaitra dans le menu et dans le fil d\'ariane</b>',
            'image_form' => 'Illustrez votre actualité avec une photo',
            'remove' =>'Cochez la case pour supprimer votre image'));
        $this->validatorSchema['title'] = new sfValidatorString(array('max_length'=>'100'));
        }
}