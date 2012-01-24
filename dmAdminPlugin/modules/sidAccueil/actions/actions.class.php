<?php

class sidAccueilActions extends dmAdminBaseActions
{
  protected function getAccueils()
  {
//    $accueils = array();
//    
//    $sc = $this->getServiceContainer();
//    
//    foreach($sc->getServiceIds() as $serviceId)
//    {
//      if (substr($serviceId, -4) === '_accueil')
//      {
//        $accueil = $sc->getService($serviceId);
//        
//        if($accueil instanceof dmAccueil)
//        {
//          $accueils[substr($serviceId, 0, strlen($serviceId)-4)] = $accueil;
//        }
//      }
//    }
//    
//    return $accueils;
  }
  
  public function executeIndex(dmWebRequest $request)
  {
//    $this->accueils = $this->getAccueils();
//    
//    $this->selectedIndex = array_search($request->getParameter('name'), array_keys($this->accueils));
  }
  
  public function executeShow(dmWebRequest $request)
  {
//    $this->forward404Unless(
//      $this->accueil = $this->getService($request->getParameter('name').'_accueil')
//    );
//    
//    $this->accueilView = $this->getServiceContainer()
//    ->setParameter('accueil_view.class', get_class($this->accueil).'View')
//    ->setParameter('accueil_view.accueil', $this->accueil)
//    ->getService('accueil_view')
//    ->setMax(200);
  }
  
  public function executeClear(dmWebRequest $request)
  {
//    $this->forward404Unless(
//      $this->accueil = $this->getService($request->getParameter('name').'_accueil')
//    );
//    
//    $this->accueil->clear();
//    $this->getUser()->accueilInfo($this->getI18n()->__('Accueil cleared'));
//    
//    return $this->redirect('dmAccueil/index');
  }
  
  public function executeRefresh(dmWebRequest $request)
  {
//    $data = array();
//    
//    $nbEntries = array(
//      'request' => 8,
//      'event'   => 8
//    );
//    
//    foreach(array('request', 'event') as $accueilKey)
//    {
//      $accueil = $this->getService($accueilKey.'_accueil');
//      
//      $view = $this->getServiceContainer()
//      ->setParameter('accueil_view.class', get_class($accueil).'ViewLittle')
//      ->setParameter('accueil_view.accueil', $accueil)
//      ->getService('accueil_view')
//      ->setMax($nbEntries[$accueilKey]);
//      
//      $hash = $view->getHash();
//      
//      if ($hash != $request->getParameter($accueilKey{0}.'h'))
//      {
//        $data[$accueilKey] = array(
//          'hash' => $hash,
//          'html' => $view->renderBody($nbEntries)
//        );
//      }
//    }
//    
//    return $this->renderJson($data);
  }
  
}