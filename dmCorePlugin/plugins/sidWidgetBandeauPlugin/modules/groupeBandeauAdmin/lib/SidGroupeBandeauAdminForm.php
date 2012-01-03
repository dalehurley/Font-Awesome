<?php

/**
 * groupeBandeauAdmin admin form
 *
 * @package    sitev3-demo-newjazz
 * @subpackage groupeBandeauAdmin
 * @author     Your name here
 */
class SidGroupeBandeauAdminForm extends BaseSidGroupeBandeauForm
{
  protected static $dmPageList = array();
    
    public function configure()
  {
    parent::configure();
  }
  
  public function setup() {
        parent::setup();
        
        // on récupère le name de la page de la rubrique, plutot que le title de la rubrique, ou section...
        $dmPageGroupe = dmDb::table('DmPage') //->findAllBySectionId($vars['section']);
                ->createQuery('p')
                ->where('p.action LIKE ?','list')
                ->orWhere('p.module LIKE ? and p.action LIKE ?',array('rubrique', 'show'))
                ->orWhere('p.module LIKE ? and p.action LIKE ?', array('main', 'root'))
                ->orderBy('lft')
                ->execute();
        
       foreach($dmPageGroupe as $dmPageId){
           // ajout du préfixe : autant de tirets que de niveaux
           $prefixe = str_repeat(' - ', $dmPageId->level);
           self::$dmPageList[$dmPageId->id] = $prefixe.$dmPageId->getTitle();
       }
        
        $this->widgetSchema['dm_page_id'] = new sfWidgetFormchoice(array('choices' => self::$dmPageList)); 
        $this->validatorSchema['dm_page_id'] = new sfValidatorChoice(array('choices' => array_keys(self::$dmPageList)));
}
}