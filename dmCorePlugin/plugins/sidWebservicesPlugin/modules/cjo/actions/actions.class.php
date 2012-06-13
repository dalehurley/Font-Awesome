<?php

class cjoActions extends myFrontModuleActions {
    
    /**
     * executeFormWidget description
     * @param  dmWebRequest $request description
     * @return type
     */
    public function executeFormWidget(dmWebRequest $request) {

        $form = new cjoForm();
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
                $results['nbjours1'] =$form->getValue('nbjours1'); 
                $results['nbjours2'] =$form->getValue('nbjours2');  
                if ($results['nbjours1']=='') $results['nbjours1']=0;
                if ($results['nbjours2']=='') $results['nbjours2']=0;
                // envoi soap
                $results['soap']=$client->__soapCall('calculCJO',array($results['nbjours1'],$results['nbjours2']),null,array($authHeader));

                $this->getUser()->setFlash('results', $results);
            }
        }
        $this->forms['cjoForm'] = $form; // pass the form to the component using the form manager  
    }
}
