<?php

class ctActions extends myFrontModuleActions {
    
    /**
     * executeFormWidget description
     * @param  dmWebRequest $request description
     * @return type
     */
    public function executeFormWidget(dmWebRequest $request) {

        $form = new ctForm();
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
                $results['capital'] =$form->getValue('capital'); 
                $results['versements']  =$form->getValue('versements');  
                $results['periodicite']=$form->getValue('periodicite');  
                $results['debut'] =$form->getValue('debut');  
                $results['nbversements'] =$form->getValue('nbversements');  
                
                // envoi soap
                $results['soap']=$client->__soapCall('calculCT',array($results['capital'], $results['versements'], $results['nbversements'], $results['periodicite'], $results['debut']),null,array($authHeader));

                $this->getUser()->setFlash('results', $results);
            }
        }
        $this->forms['ctForm'] = $form; // pass the form to the component using the form manager  
    }
}
