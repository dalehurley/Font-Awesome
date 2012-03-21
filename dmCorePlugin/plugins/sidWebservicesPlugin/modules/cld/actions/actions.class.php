<?php

/**
 * cld actions.
 *
 * @package    serveurws
 * @subpackage cld
 * @author     SID Presse
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cldActions extends sfActions
{
 public function executeIndex($request)
  {
    $this->form = new CldForm();
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
        $results['valeur']  =$this->form->getValue('valeur'); 
        $results['periodicite']=$this->form->getValue('periodicite');  
        $results['debut'] =$this->form->getValue('debut');  
        $results['taux'] =$this->form->getValue('taux');  
        $results['soap']=$client->__soapCall('calculCLD',array($results['montant'], $results['depot'], $results['remboursements'], $results['periodicite'], $results['debut'], $results['valeur'], $results['taux']),null,array($authHeader));
        
        $this->getUser()->setFlash('results',$results);
        return sfView::SUCCESS;

      }

    }
   $this->forms['maform']=$this->form;
  } //fin executeIndex
}
