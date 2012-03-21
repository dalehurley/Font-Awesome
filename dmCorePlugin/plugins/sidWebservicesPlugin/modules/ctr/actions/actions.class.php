<?php

/**
 * ctr actions.
 *
 * @package    serveurws
 * @subpackage ctr
 * @author     SID Presse
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ctrActions extends sfActions
{
 public function executeIndex($request)
  {
    $this->form = new CtrForm();
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
        

        $results['montant'] =$this->form->getValue('montant'); 
        $results['depot']  =$this->form->getValue('depot'); 
        $results['remboursements']  =$this->form->getValue('remboursements'); 
        $results['nbloyers']  =$this->form->getValue('nbloyers');
        $results['periodicite']=$this->form->getValue('periodicite');  
        $results['debut'] =$this->form->getValue('debut');  
        $results['valeur'] =$this->form->getValue('valeur');  
        $results['soap']=$client->__soapCall('calculVACPICPDD',array($results['montant'], $results['depot'], $results['remboursements'], $results['nbloyers'], $results['periodicite'], $results['debut'], $results['valeur']),null,array($authHeader));
        
        $this->getUser()->setFlash('results',$results);
        return sfView::SUCCESS;

      }

    }
    $this->forms['maform']=$this->form;
  } //fin executeIndex
}
