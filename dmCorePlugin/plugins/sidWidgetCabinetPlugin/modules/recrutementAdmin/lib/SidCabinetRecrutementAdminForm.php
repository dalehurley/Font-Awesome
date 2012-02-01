<?php

/**
 * recrutementAdmin admin form
 *
 * @package    sitev3-demo3
 * @subpackage recrutementAdmin
 * @author     Your name here
 */
class SidCabinetRecrutementAdminForm extends BaseSidCabinetRecrutementForm
{
  public function configure()
  {
    parent::configure();
  }
  
    public function setup() {
        parent::setup();
        $this->widgetSchema->setHelps(array(
//            'title_entete_page' => '<b>Cet intitulé sera le titre de la page (100 caractères MAXI)</b>',
            'text' => '<u>Information</u>: <br /> - pour un retour à la ligne : faire Maj + ENTREE en même temps <br /> - pour un nouveau paragraphe : faire ENTREE',));
        }
}