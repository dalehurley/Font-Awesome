<?php

class cceActions extends myFrontModuleActions {
    
    /**
     * executeFormWidget description
     * @param  dmWebRequest $request description
     * @return type
     */
    public function executeFormWidget(dmWebRequest $request) {

        $form = new cceForm();
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
                $results['remboursements'] =$this->form->getValue('remboursements'); 
                $results['nbremboursements'] =$this->form->getValue('nbremboursements');  
                $results['periodicite']=$this->form->getValue('periodicite');  
                $results['debut'] =$this->form->getValue('debut');  
                $results['taux'] =$this->form->getValue('taux');  
                
                // envoi soap
                $results['soap']=$client->__soapCall('calculCCE',array($results['remboursements'], $results['nbremboursements'], $results['periodicite'], $results['debut'], $results['taux']),null,array($authHeader));

                $this->getUser()->setFlash('results', $results);
            }
        }
        $this->forms['cceForm'] = $form; // pass the form to the component using the form manager  
    }
}
