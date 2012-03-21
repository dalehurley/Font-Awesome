<?php

/**
 * trc actions.
 *
 * @package    serveurws
 * @subpackage trc
 * @author     SID Presse
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class trcActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
 
    public function executeIndex($request)
  {
    $this->form = new TrcForm();
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
        $results['duree']  =$this->form->getValue('duree');  
        $results['periodicite']=$this->form->getValue('periodicite');  
        $results['debut'] =$this->form->getValue('debut');  
        $results['capitalacquis'] =$this->form->getValue('capitalacquis');  
        $results['soap']=$client->__soapCall('calculTRC',array($results['capital'], $results['periodicite'], $results['duree'], $results['debut'], $results['capitalacquis']) ,null,array($authHeader));
        $this->getUser()->setFlash('results',$results);
        return sfView::SUCCESS;

      }

    }
  $this->forms['maform']=$this->form;
  } //fin executeIndex
}
