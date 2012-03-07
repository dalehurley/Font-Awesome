<?php
/**
 * Contact actions
 */
class BaseVacpicpddActions extends myFrontModuleActions
{

  public function executeFormWidget(dmWebRequest $request)
  {
    $form = new vacpicpddForm();

    if ($request->isMethod('POST'))
    {

      $form->bind($request->getParameter($form->getName()));

      if ($form->isValid())
      {
        $this->getUser()->setFlash('results', true);

        $this->redirectBack();
      } 

    }

    //$this->getUser()->setFlash('vacpicpdd_form_valid', true);

    $this->forms['vacpicpddForm'] = $form;
     
    // $this->form = new VacpicpddForm();

    // if ($request->isMethod('POST'))
    // {
    //   $this->form->bind($request->getParameter('vacpicpdd'));
    //   if ($this->form->isValid())
    //   {
    //     $this->redirect('vacpicpdd/index?'.http_build_query($this->form->getValues()));
    //   }
    // }
    // if ($request->isMethod('GET')&&($request->getParameter('capital')!=''))
    // {
      
    //   $this->viewResultat=true;
    //   $client=new SoapClient(sfConfig::get('app_soapService_adr').sfConfig::get('app_soapService_wsdl'));
    //   $authData=new AuthData();
    //   $authData->username=sfConfig::get('app_identification_login');
    //   $authData->password=sfConfig::get('app_identification_password');

    //   $authHeader=new SoapHeader(sfConfig::get('app_soapService_adr'),'AuthHeaderElement',$authData);

    //   $capital =$request->getParameter('capital');   
    //   $duree  =$request->getParameter('duree');  
    //   $periodicite=$request->getParameter('periodicite');  
    //   $debut =$request->getParameter('debut');  
    //   $taux =$request->getParameter('taux');  
        
    //   $this->resulat=$client->__soapCall('calculVACPICPDD',array($capital ,$duree,$periodicite,$debut,$taux),null,array($authHeader));

    //   return sfView::SUCCESS; 
    // }   
  }

}
