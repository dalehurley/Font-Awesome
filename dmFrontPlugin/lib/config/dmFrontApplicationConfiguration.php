<?php

require_once(realpath(dirname(__FILE__).'/../../../dmCorePlugin/lib/config/dmApplicationConfiguration.php'));

/**
 * sfConfiguration represents a configuration for a symfony application.
 *
 * @package    symfony
 * @subpackage config
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfApplicationConfiguration.class.php 13947 2008-12-11 14:15:32Z fabien $
 */
abstract class dmFrontApplicationConfiguration extends dmApplicationConfiguration
{

  public function setup()
  {
    parent::setup();
    
    $this->enablePlugins('dmFrontPlugin');
  }

  /**
   * @see sfProjectConfiguration
   */
  public function initConfiguration()
  {
    require_once(sfConfig::get('dm_front_dir').'/lib/config/dmFrontModuleManagerConfigHandler.php');
    
    parent::initConfiguration();
  }

  // envoi d'email quand un dmContact est envoyé
  public function configure()
  {
    $this->dispatcher->connect('dm_contact.saved', array($this, 'listenToContactSavedEvent'));
    $this->dispatcher->connect('sid_contact_data.saved', array($this, 'listenToSidContactDataSavedEvent'));
  }

  public function listenToContactSavedEvent(sfEvent $e)
  {
    $contact = $e['contact'];
    dm::enableMailer(); // car  enable_mailer:false dans config.yml (pour des raisons de performance)
    // do something with the freshly saved $contact
    $message = '';
    $message .= "Contact ".$contact->id."
";
    $message .= $contact->title.' '.$contact->name.' '.$contact->firstname."
";
    $message .= $contact->adresse.' '.$contact->postalcode.' '.$contact->ville.' '."
";
    $message .= $contact->email."
";
    $message .= "Téléphone: ".$contact->phone."
";
    $message .= "Fax: ".$contact->fax."
";
    $message .= "Fonction: ".$contact->function."
";
    $message .= "Message: ".$contact->body."
";
    
    if (sfConfig::get('sf_environment') == 'prod') {
      sfContext::getInstance()->getMailer()->composeAndSend(array(dmConfig::get('site_email_sender') => dmConfig::get('site_name')),dmConfig::get('site_email'), dmConfig::get('site_name').' - Contact', $message);
    }
    
  }

  public function listenToSidContactDataSavedEvent(sfEvent $e)
  {
    $contact = $e['contact_data'];

    dm::enableMailer(); // car  enable_mailer:false dans config.yml (pour des raisons de performance)
    // do something with the freshly saved $contact
    $message = '';
    $infos = json_decode($contact->infos, true);
    
    // calcul de la plus longue $key
    $keysArray = array_keys($infos);
    $lengthsKeysArray = array_map('strlen', $keysArray);
    $longuestKey = max($lengthsKeysArray);
    // ajout d'espace après le libellé du champ pour aligner les ":"
    foreach ($infos as $key => $value) {
      $message .= $key.str_repeat(' ', $longuestKey - strlen($key))." :".$value."
";
    }

    // si le champ "destinataire" est ésent dans le contact alors 
    if (isset($infos['destinataire']) && $infos['destinataire'] != '') {
      $destEmail = $infos['destinataire'];  // on l'envoie au destinataire choisi dan sle formulaire de contact
    } else {
      $destEmail = dmConfig::get('site_email'); // sinon on envoie au site_email
    }

    if (sfConfig::get('sf_environment') == 'prod') {
      sfContext::getInstance()->getMailer()->composeAndSend(array(dmConfig::get('site_email_sender') => dmConfig::get('site_name')),$destEmail, dmConfig::get('site_name').' - Contact', $message);
    }

  }

}