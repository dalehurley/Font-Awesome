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
  }

  public function listenToContactSavedEvent(sfEvent $e)
  {
    $contact = $e['contact'];
    dm::enableMailer(); // car  enable_mailer:false dans config.yml (pour des raisons de performance)
    // do something with the freshly saved $contact
    $message = '';
    $message .= "Contact ".$contact->id."
    ";
    $message .= $contact->title.' '.$contact->name.' '.$contact->firstname;
    $message .= $contact->adresse.' '.$contact->postalcode.' '.$contact->ville.' '."
    ";
    $message .= $contact->email."
    ";
    $message .= $contact->phone."
    ";
    $message .= $contact->fax."
    ";
    $message .= "Fonction :".$contact->function."
    ";
    $message .= "Message :".$contact->body."
    ";

    sfContext::getInstance()->getMailer()->composeAndSend(array(dmConfig::get('site_email_sender') => dmConfig::get('site_name')),dmConfig::get('site_email'), 'Contact reçu', $message);

  }


}