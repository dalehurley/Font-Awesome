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
    protected static $dmPageList = array();
    
  public function configure()
  {
    parent::configure();
  }
  
  public function setup() {
        parent::setup();
        $this->widgetSchema['behavior'] = new sfWidgetFormChoice(array('choices' => array('scroll' => 'défilement', 'alternate' => 'va et vient')));
//        $this->widgetSchema['direction'] = new sfWidgetFormChoice(array('choices' => array('left' => 'de droite à gauche', 'right' => 'de gauche à droite', 'down' => 'de haut en bas', 'up' => 'de bas en haut')));
        $this->widgetSchema['direction'] = new sfWidgetFormChoice(array('choices' => array('left' => 'de droite à gauche', 'right' => 'de gauche à droite')));
        $this->widgetSchema['scrollamount'] = new sfWidgetFormSelectRadio(array('choices' => array('1' => 'lent', '3' => 'normal', '5' => 'rapide'))); 
        
        $this->widgetSchema->setHelps(array(
            'boucle' => '<b>(0 pour infini)</b>', 
            'behavior' => '<b>va et vient : votre message "rebondit" sur les bords du cadre, <br />défilement : votre message défile sur la page dans le sens de votre choix</b>',
            'title' => 'Inscrivez le message que vous souhaitez voir sur les pages de la catégorie sélectionnée ci-dessous') );
        
    
//        if (sfContext::getInstance()->getUser()->hasCredential('admin_bandeau_lite') && !sfContext::getInstance()->getUser()->isSuperAdmin()){
//            
//                $this->widgetSchema['groupe_bandeau_id'] = new sfWidgetFormInputHidden();
//                $this->validatorSchema['groupe_bandeau_id'] = new sfValidatorDoctrineChoice(array('model' => 'SidBandeau'));
//$nomBandeau = dmDb::table('DmUser')->findOneById(sfContext::getInstance()->getUser()->id);           
//                $this->widgetSchema['groupe_bandeau_id_2'] = new sfWidgetFormInputText(array('default' => $nomBandeau));
//                $this->validatorSchema['groupe_bandeau_id_2'] = new sfValidatorString(array(
//                    'required' => false
//                ));
////                
//        }
    }
}