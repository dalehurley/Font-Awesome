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
                $results['montant'] =$this->form->getValue('montant'); 
                $results['depot']  =$this->form->getValue('depot'); 
                $results['remboursements']  =$this->form->getValue('remboursements'); 
                $results['nbloyers']  =$this->form->getValue('nbloyers');
                $results['periodicite']=$this->form->getValue('periodicite');  
                $results['debut'] =$this->form->getValue('debut');  
                $results['valeur'] =$this->form->getValue('valeur');  

                // envoi soap
                $results['soap']=$client->__soapCall('calculVACPICPDD',array($results['montant'], $results['depot'], $results['remboursements'], $results['nbloyers'], $results['periodicite'], $results['debut'], $results['valeur']),null,array($authHeader));
        
                $this->getUser()->setFlash('results', $results);
            }
        }
        $this->forms['ctrForm'] = $form; // pass the form to the component using the form manager  
    }
}
