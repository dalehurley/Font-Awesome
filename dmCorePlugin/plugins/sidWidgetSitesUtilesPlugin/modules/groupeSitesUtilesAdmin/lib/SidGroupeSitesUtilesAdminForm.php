<?php

/**
 * groupeSitesUtilesAdmin admin form
 *
 * @package    sitev3-demo1
 * @subpackage groupeSitesUtilesAdmin
 * @author     Your name here
 */
class SidGroupeSitesUtilesAdminForm extends BaseSidGroupeSitesUtilesForm
{
  public function configure()
  {
    parent::configure();
  }
  
          public function setup() {
        parent::setup();
        
//        $this->widgetSchema->setHelps(array(
//            'description' => '<u>Information</u>: <br /> - pour un retour à la ligne : faire Maj + ENTREE en même temps <br /> - pour un nouveau paragraphe : faire ENTREE') );
        }
}