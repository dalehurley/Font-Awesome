<?php

class ctrActions extends myFrontModuleActions {
    
    /**
     * executeFormWidget description
     * @param  dmWebRequest $request description
     * @return type
     */
    public function executeFormWidget(dmWebRequest $request) {

        $form = new ctrForm();
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
                $results['montant'] =$form->getValue('montant'); 
                $results['depot']  =$form->getValue('depot'); 
                $results['remboursements']  =$form->getValue('remboursements'); 
                $results['nbloyers']  =$form->getValue('nbloyers');
                $results['periodicite']=$form->getValue('periodicite');  
                $results['debut'] =$form->getValue('debut');  
                $results['valeur'] =$form->getValue('valeur');  

                // envoi soap
                $results['soap']=$client->__soapCall('calculCTR',array($results['montant'], $results['depot'], $results['remboursements'], $results['nbloyers'], $results['periodicite'], $results['debut'], $results['valeur']),null,array($authHeader));
        
                $this->getUser()->setFlash('results', $results);
            }
        }
        $this->forms['ctrForm'] = $form; // pass the form to the component using the form manager  
    }
}
