<?php

/**
 * vasvc actions.
 *
 * @package    serveurws
 * @subpackage vasvc
 * @author     SID Presse
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class vasvcActions extends sfActions
{
 
  
    public function executeIndex($request)
  {
    $this->form = new VasvcForm();
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

        $results['versements'] =$this->form->getValue('versements'); 
        $results['nbversements']  =$this->form->getValue('nbversements');  
        $results['periodicite']=$this->form->getValue('periodicite');  
        $results['debut'] =$this->form->getValue('debut');  
        $results['taux'] =$this->form->getValue('taux');  
        $results['soap']=$client->__soapCall('calculVASVC',array($results['versements'], $results['nbversements'], $results['periodicite'], $results['debut'], $results['taux']) ,null,array($authHeader));
        $this->getUser()->setFlash('results',$results);
        return sfView::SUCCESS;

      }
      
    }
   $this->forms['maform']=$this->form;
  } //fin executeIndex
 
}
