<?php

class trsvcActions extends myFrontModuleActions {
    
    /**
     * executeFormWidget description
     * @param  dmWebRequest $request description
     * @return type
     */
    public function executeFormWidget(dmWebRequest $request) {

        $form = new trsvcForm();
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
                $results['versements'] =$form->getValue('versements'); 
                $results['nbversements']  =$form->getValue('nbversements');  
                $results['periodicite']=$form->getValue('periodicite');  
                $results['debut'] =$form->getValue('debut');  
                $results['capital'] =$form->getValue('capital');  

                // envoi soap
                $results['soap']=$client->__soapCall('calculTRSVC',array($results['versements'], $results['nbversements'], $results['periodicite'], $results['debut'], $results['capital']),null,array($authHeader));

                $this->getUser()->setFlash('results', $results);
            }
        }
        $this->forms['trsvcForm'] = $form; // pass the form to the component using the form manager  
    }
}
