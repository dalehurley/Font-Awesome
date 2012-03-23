<?php

class srtbActions extends myFrontModuleActions {
    
    /**
     * executeFormWidget description
     * @param  dmWebRequest $request description
     * @return type
     */
    public function executeFormWidget(dmWebRequest $request) {

        $form = new srtbForm();
        $form->removeCsrfProtection();

        if ($request->isMethod('POST')) {

            $form->bind($request->getParameter($form->getName()));

            if ($form->isValid()) {

                // instanciation soap / connexion
                $client = new SoapClient(sfConfig::get('app_soapService_adr') . sfConfig::get('app_soapService_wsdl'));
                $authData = new AuthData();
                $authData->username = sfConfig::get('app_identification_login');
                $authData->password = sfConfig::get('app_identification_password');
                $authHeader = new SoapHeader(sfConfig::get('app_soapService_adr') , 'AuthHeaderElement', $authData);

                // les valeurs postees
                $results['marge'] =$form->getValue('marge'); 
                $results['chargevar']  =$form->getValue('chargevar');  
                $results['chargestruct']=$form->getValue('chargestruct');  
                $results['remuneration'] =$form->getValue('remuneration');  
                $results['ca'] =$form->getValue('ca');  

                // envoi soap
                $results['soap']=$client->__soapCall('calculSRTB',array($results['marge'], $results['chargevar'], $results['chargestruct'], $results['remuneration'], $results['ca']),null,array($authHeader));

                $this->getUser()->setFlash('results', $results);
            }
        }
        $this->forms['srtbForm'] = $form; // pass the form to the component using the form manager  
    }
}
