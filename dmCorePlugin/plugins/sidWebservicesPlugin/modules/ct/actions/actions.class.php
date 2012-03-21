<?php

/**
 * ct actions.
 *
 * @package    serveurws
 * @subpackage ct
 * @author     SID Presse
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ctActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  
    
    public function executeIndex($request)
  {
    $this->form = new CtForm();
    $this->viewResultat=false;
   
    if ($request->isMethod('POST'))
    {
     $this->form->bind($request->getParameter($this->form->getName()));
    if ($this->form->isValid())
      {
       
        $this->viewResultat=true;
        $client=new SoapClient(sfConfig::get('app_soapService_adr').sfConfig::get('app_soapService_wsdl'));
        $authData=new AuthData();
        $authData->username=sfConfig::get('app_identification_login');
        $authData->password=sfConfig::get('app_identification_password');

        $authHeader=new SoapHeader(sfConfig::get('app_soapService_adr'),'AuthHeaderElement',$authData);
        
        
        $results['capital'] =$this->form->getValue('capital'); 
        $results['versements']  =$this->form->getValue('versements');  
        $results['periodicite']=$this->form->getValue('periodicite');  
        $results['debut'] =$this->form->getValue('debut');  
        $results['nbversements'] =$this->form->getValue('nbversements');  
        $results['soap']=$client->__soapCall('calculCT',array($results['capital'], $results['versements'], $results['nbversements'], $results['periodicite'], $results['debut']),null,array($authHeader));
        $this->getUser()->setFlash('results',$results);
        return sfView::SUCCESS;

      }

    }
  $this->forms['maform']=$this->form;
  } //fin executeIndex
 
}
