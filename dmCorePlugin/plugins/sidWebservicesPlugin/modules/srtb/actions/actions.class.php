<?php

/**
 * srtb actions.
 *
 * @package    serveurws
 * @subpackage srtb
 * @author     SID Presse
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class srtbActions extends sfActions
{
 public function executeIndex($request)
  {
    $this->form = new SrtbForm();
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

        

        $results['marge'] =$this->form->getValue('marge'); 
        $results['chargevar']  =$this->form->getValue('chargevar');  
        $results['chargestruct']=$this->form->getValue('chargestruct');  
        $results['remuneration'] =$this->form->getValue('remuneration');  
        $results['ca'] =$this->form->getValue('ca');  
        $results['soap']=$client->__soapCall('calculSRTB',array($results['marge'], $results['chargevar'], $results['chargestruct'], $results['remuneration'], $results['ca']),null,array($authHeader));
        $this->getUser()->setFlash('results',$results);
        return sfView::SUCCESS;

      }

    }
  $this->forms['maform']=$this->form;
  } //fin executeIndex
}
