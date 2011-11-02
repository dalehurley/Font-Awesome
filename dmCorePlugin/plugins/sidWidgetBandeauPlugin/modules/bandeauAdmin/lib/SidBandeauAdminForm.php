<?php

/**
 * bandeauAdmin admin form
 *
 * @package    sitev3-demo-newjazz
 * @subpackage bandeauAdmin
 * @author     Your name here
 */
class SidBandeauAdminForm extends BaseSidBandeauForm
{
  public function configure()
  {
    parent::configure();
  }
  
      public function setup() {
        parent::setup();
        $this->widgetSchema['behavior'] = new sfWidgetFormChoice(array('choices' => array('scroll' => 'défilement', 'alternate' => 'va et vient')));
//        $this->widgetSchema['direction'] = new sfWidgetFormChoice(array('choices' => array('left' => 'de droite à gauche', 'right' => 'de gauche à droite', 'down' => 'de haut en bas', 'up' => 'de bas en haut')));
        $this->widgetSchema['direction'] = new sfWidgetFormChoice(array('choices' => array('left' => 'de droite à gauche', 'right' => 'de gauche à droite')));
        $this->widgetSchema['scrollamount'] = new sfWidgetFormSelectRadio(array('choices' => array('2' => 'lent', '5' => 'normal', '10' => 'rapide'))); 
        
        $this->widgetSchema->setHelps(array(
            'boucle' => '<b>(0 pour infini)</b>', 
            'behavior' => '<b>va et vient : votre message "rebondit" sur les bords du cadre, <br />défilement : votre message défile sur la page dans le sens de votre choix</b>') );
        
    
        if (sfContext::getInstance()->getUser()->hasCredential('admin_bandeau_lite') && !sfContext::getInstance()->getUser()->isSuperAdmin()){
                $this->widgetSchema['groupe_bandeau_id'] = new sfWidgetFormInputHidden();
        }
        
        
    }
}