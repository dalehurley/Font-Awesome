<?php

require_once realpath(dirname(__FILE__).'/..').'/lib/BasedmContactMeActions.class.php';

/**
 * Contact actions
 */
class dmContactMeActions extends BasedmContactMeActions
{
    public function executeFormWidget(dmWebRequest $request) {
        $form = new DmContactMeForm();

        if ($request->hasParameter($form->getName())) {
            $data = $request->getParameter($form->getName());

            if ($form->isCaptchaEnabled()) {
                $data = array_merge($data, array('captcha' => array(
                                'recaptcha_challenge_field' => $request->getParameter('recaptcha_challenge_field'),
                                'recaptcha_response_field' => $request->getParameter('recaptcha_response_field'),
                                )));
            }

            $form->bind($data, $request->getFiles($form->getName()));

            if ($form->isValid()) {

                if ($form->isQaptchaEnabled()) {

                    $sessionQaptcha = $this->getUser()->getAttribute('iQaptcha');

                    if (isset($_POST['iQapTcha']) && empty($_POST['iQapTcha']) && isset($sessionQaptcha) && $sessionQaptcha) {
                        //mail can be sent
                        $form->save();

                        $this->getUser()->setFlash('contact_form_valid', true);

                        $this->getService('dispatcher')->notify(new sfEvent($this, 'dm_contact.saved', array(
                                    'contact' => $form->getObject()
                                )));

                        $this->redirectBack();
                    } else {
                        //mail can't be sent
                        $this->getUser()->setFlash('bad_qaptcha', true);

                        $this->redirectBack();
                    }
                } else {

                    $form->save();

                    $this->getUser()->setFlash('contact_form_valid', true);

                    $this->getService('dispatcher')->notify(new sfEvent($this, 'dm_contact.saved', array(
                                'contact' => $form->getObject()
                            )));

                    $this->redirectBack();
                }
            }
        }

        $this->forms['DmContactMe'] = $form;
    }

    /*
     * Fonction appelé en ajax par le QapTcha.jaquery.js
     */
    public function executeAjax(dmWebRequest $request) {

        if ($request->isXmlHttpRequest()) {

            $aResponse['error'] = false;
            $this->getUser()->setAttribute('iQaptcha', false);

            if (isset($_POST['action'])) {
                if (htmlentities($_POST['action'], ENT_QUOTES, 'UTF-8') == 'qaptcha') {
                    $this->getUser()->setAttribute('iQaptcha', true);
                    if ($this->getUser()->getAttribute('iQaptcha'))
                        $message = json_encode($aResponse);
                    else {
                        $aResponse['error'] = true;
                        $message = json_encode($aResponse);
                    }
                } else {
                    $aResponse['error'] = true;
                    $message = json_encode($aResponse);
                }
            } else {
                $aResponse['error'] = true;
                $message = json_encode($aResponse);
            }

            return $this->renderText($message);
        }
    }
}
