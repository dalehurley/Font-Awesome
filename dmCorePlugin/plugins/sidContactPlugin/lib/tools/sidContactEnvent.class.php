<?php
/**
 * class sidContactEnvent
 */

class sidContactEnvent {
   
  public static function listenToSidContactDataSavedEvent(sfEvent $e)
  {
    $contact = $e['contact_data'];

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

    // si le champ "destinataire" est présent dans le contact alors 
    if (isset($infos['destinataire']) && $infos['destinataire'] != '') {
      $destEmail = $infos['destinataire'];  // on l'envoie au destinataire choisi dan sle formulaire de contact
    } else {
      $destEmail = dmConfig::get('site_email'); // sinon on envoie au site_email
    }

    dm::enableMailer();
    try {
      sfContext::getInstance()->getMailer()->composeAndSend(
        array(
          dmConfig::get('site_email_sender') => dmConfig::get('site_name')),
          explode(',',$destEmail), 
          dmConfig::get('site_name').' - Contact', $message
        );
      sfContext::getInstance()->getUser()->setFlash('mail', 'ok');
    }
    catch (Exception $e) {
      $exceptionMessage = 'Error '.$e->getMessage().'Code: '.$e->getCode().'.File: '.$e->getFile().':'.$e->getLine();
      sfContext::getInstance()->getUser()->setFlash('mail', 'error');
      sfContext::getInstance()->getUser()->setFlash('mail_exception', $exceptionMessage);
    }
  }

}
