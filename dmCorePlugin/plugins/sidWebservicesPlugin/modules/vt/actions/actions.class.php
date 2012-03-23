<?php

class vtActions extends myFrontModuleActions {
    
    /**
     * executeFormWidget description
     * @param  dmWebRequest $request description
     * @return type
     */
    public function executeFormWidget(dmWebRequest $request) {

        $form = new vtForm();
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
                $results['departement'] =$form->getValue('departement'); 

                // envoi soap
                $results['soap']=$client->__soapCall('calculVT',array($results['departement']) ,null,array($authHeader));

                $this->getUser()->setFlash('results', $results);
            }
        }
        $this->forms['vtForm'] = $form; // pass the form to the component using the form manager  
    }
}
