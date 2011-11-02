<?php

/**
 * indexSitesUtilesAdmin admin form
 *
 * @package    sitev3-demo1
 * @subpackage indexSitesUtilesAdmin
 * @author     Your name here
 */
class SidIndexSitesUtilesAdminForm extends BaseSidIndexSitesUtilesForm
{
  public function configure()
  {
    parent::configure();
  }
  
        public function setup() {
        parent::setup();
        
        $this->widgetSchema->setHelps(array(
            'description' => '<u>Information</u>: <br /> - pour un retour à la ligne : faire Maj + ENTREE en même temps <br /> - pour un nouveau paragraphe : faire ENTREE',));
            }

}