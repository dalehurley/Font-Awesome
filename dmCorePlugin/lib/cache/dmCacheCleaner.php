<?php

class dmCacheCleaner extends dmConfigurable
{
  protected
  $cacheManager,
  $dispatcher,
  $queue;
  
  const
  TEMPLATE = 'template';
  
  public function __construct(dmCacheManager $cacheManager, sfEventDispatcher $dispatcher, array $options = array())
  {
    $this->cacheManager = $cacheManager;
    $this->dispatcher   = $dispatcher;
    
    $this->initialize($options);
  }
  
  protected function initialize(array $options = array())
  {
    $this->configure($options);
    
    $this->queue = array();
  }
  
  public function getDefaultOptions()
  {
    return array(
      'applications' => array('admin', 'front'),
      'environments' => array('prod', 'dev'),
      'safe_models' => array('DmSentMail', 'DmError', 'DmRedirect', 'DmUser', 'DmPermission', 'DmGroup', 'DmGroupPermission', 'DmUserPermission', 'DmUserGroup', 'DmRememberKey')
    );
  }
  
  public function connect()
  {
    $this->dispatcher->connect('dm.controller.redirect', array($this, 'listenToControllerRedirectionEvent'));
    
    $this->dispatcher->connect('dm.record.modification', array($this, 'listenToRecordModificationEvent'));
  }
  
  public function addToQueue($cachePart)
  {
    $this->queue[] = $cachePart;
  }
  
  public function listenToRecordModificationEvent(sfEvent $event)
  {
    if (!$this->isModelSafe(get_class($event->getSubject())))
    {
      // lioshi : supprime tout le cache template, il faudrait ne supprimer que le cache relatif aux pages utilisant l'objet
      // $eee = $event->setReturnValue(dmDb::table('DmPage')->findOneByRecordWithI18n($event->getSubject()));

      // On a l'objet qui vient d'etre modifié: $event->getSubject()
      // On a sa classe : get_class($event->getSubject())
      // il faut récupérer les pages qui présente/affiche cet objet (en passant par les widgets qui sont susceptibles d'afficher l'obejt)

      // if APC then clear slug entrie from APC use entries
      // else addToQueue

      $this->addToQueue(self::TEMPLATE);
      //dmDb::table('DmPage')->findOneByRecordWithI18n($event->getSubject())
    }
  }
  
  public function isModelSafe($model)
  {
    return in_array($model, $this->getOption('safe_models'));
  }
  
  public function listenToControllerRedirectionEvent(sfEvent $event)
  {
    $this->processQueue();
  }
  
  public function processQueue()
  {
    $queue = array_unique($this->queue);
    
    foreach($queue as $cachePart)
    {
      switch($cachePart)
      {
        case self::TEMPLATE:
          $this->clearTemplate();
          break;
        default:
          throw new dmException(sprintf('%s is not a valid cachePart.', $cachePart));
      }
    }
  }
  
  public function clearTemplate()
  {
    foreach($this->getOption('applications') as $app)
    {
      foreach($this->getOption('environments') as $env)
      {
        // $this->cacheManager->getCache(sprintf('%s/%s/template/fr/sitediem_com/all/dmFront/page/slug/le-cabinet', $app, $env))->clear();
        $this->cacheManager->getCache(sprintf('%s/%s/template', $app, $env))->clear();
      }
    }

    $this->dispatcher->notify(new sfEvent($this, 'dm.cache.clear_template', array()));
  }
  
}