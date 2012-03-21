<?php

/**
 * cjo actions.
 *
 * @package    serveurws
 * @subpackage cjo
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cjoActions extends sfActions
{
 public function executeIndex($request)
  {
    $this->form = new CjoForm();
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


        $results['nbjours1'] =$this->form->getValue('nbjours1'); 
        $results['nbjours2'] =$this->form->getValue('nbjours2');  

        $results['soap']=$client->__soapCall('calculCJO',array($results['nbjours1'],$results['nbjours2']),null,array($authHeader));
        
        $this->getUser()->setFlash('results',$results);
        return sfView::SUCCESS;

      }

    }
    $this->forms['maform']=$this->form;
  } //fin executeIndex
  
}
