<?php

/**
 * renseignementsAdmin admin form
 *
 * @package    sitev3-demo3
 * @subpackage renseignementsAdmin
 * @author     Your name here
 */
class SidCoordNameAdminForm extends BaseSidCoordNameForm
{
  public function configure()
  {
    parent::configure();
    $this->widgetSchema->setHelp('adresse_google', 'A remplir si la carte Google n\'affiche pas la bonne adresse');
  }
}