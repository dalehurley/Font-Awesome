<?php

/**
 * vt actions.
 *
 * @package    serveurws
 * @subpackage vt
 * @author     SID Presse
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
 class vtActions extends sfActions
{
 public function executeIndex($request)
  {
    $this->form = new VtForm();
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

        
        $results['departement'] =$this->form->getValue('departement'); 

        $results['soap']=$client->__soapCall('calculVT',array($results['departement']) ,null,array($authHeader));
        $this->getUser()->setFlash('results',$results);
        return sfView::SUCCESS;

      }

    }
  $this->forms['maform']=$this->form;
  } //fin executeIndex
}
