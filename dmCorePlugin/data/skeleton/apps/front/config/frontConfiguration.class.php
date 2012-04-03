<?php

require_once(dm::getDir().'/dmFrontPlugin/lib/config/dmFrontApplicationConfiguration.php');

class frontConfiguration extends dmFrontApplicationConfiguration
{
  // public function configure()
  // {
  //   $this->dispatcher->connect('dm_contact.saved', array($this, 'listenToContactSavedEvent'));
  // }

  // public function listenToContactSavedEvent(sfEvent $e)
  // {
  //   $contact = $e['contact'];
  //   dm::enableMailer(); // car  enable_mailer:false dans config.yml (pour des raisons de performance)
  //   // do something with the freshly saved $contact
  //   sfContext::getInstance()->getMailer()->composeAndSend('contact@expert-clients.fr','lionel.fenneteau@gmail.com', 'contact reçu', $contact);

  // }
}


