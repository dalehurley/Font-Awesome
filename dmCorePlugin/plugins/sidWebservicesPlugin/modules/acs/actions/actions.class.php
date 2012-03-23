<?php

class acsActions extends myFrontModuleActions {
    
    /**
     * executeFormWidget description
     * @param  dmWebRequest $request description
     * @return type
     */
    public function executeFormWidget(dmWebRequest $request) {

        $form = new acsForm();
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
                $results['nbjours'] =$form->getValue('nbjours'); 

                // envoi soap
                $results['soap']=$client->__soapCall('calculACS',array($results['nbjours']),null,array($authHeader));

                $this->getUser()->setFlash('results', $results);
            }
        }
        $this->forms['acsForm'] = $form; // pass the form to the component using the form manager  
    }
}
