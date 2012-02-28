<?php

class dmWidgetFeedReaderShowView extends dmWidgetPluginView
{
  
  public function configure()
  {
    parent::configure();
    
    $this->addRequiredVar(array('url', 'nbArticles', 'life_time', 'title','length'));
  }

  protected function filterViewVars(array $vars = array())
  {
    $vars = parent::filterViewVars($vars);
    // ajout stef le 27-02/2012 
    if(($vars['title'] == '') || ($vars['title'] == ' ')){
       $vars['title'] = sfContext::getInstance()->getPage()->getName();
      }
    ($vars['length'] == 0 ) ? $length = '' : $length = $vars['length'];

    $vars['items'] = $this->getItems($vars['url'], $vars['nbArticles'], $vars['life_time'],$vars['title']);
    // ajout stef le 27-02/2012 
    return $vars;
  }
  
  protected function doRenderForIndex()
  {
    $items = array();

    $viewVars = $this->getViewVars();
    
    foreach($viewVars['items'] as $item)
    {
      $items[] = $item['title'];
    }
    
    return implode(', ', $items);
  }

  protected function getItems($url, $nb, $lifeTime, $title)
  {
    $cache = $this->getService('cache_manager')->getCache('dm_widget_feed_reader');
    
    $cacheKey = md5($url.$nb);

    if ($cache->has($cacheKey))
    {
      $items = $cache->get($cacheKey);
    }
    else
    {
      $items = array();
    
      $collection = $this->getItemCollection($url);

      $collection = array_slice($collection, 0, $nb);
      
      foreach($collection as $item)
      {
        $items[] = $this->feedItemToArray($item);
      }

      $items = $this->context->getEventDispatcher()->filter(
        new sfEvent($this, 'dm.widget_twitter_feed_reader_show.items', array('url' => $url, 'nb' => $nb)),
        array($items,$title))->getReturnValue();
      
      $cache->set($cacheKey, $items, $lifeTime);
    }

    return $items;
  }

  protected function getItemCollection($url)
  {
    $browser = $this->getService('web_browser');

    if($browser->get($url)->responseIsError())
    {
      throw new dmException(sprintf('The given URL (%s) returns an error (%s: %s)', $url, $browser->getResponseCode(), $browser->getResponseMessage()));
    }

    return sfFeedPeer::createFromXml($browser->getResponseText(), $url)->getItems();
  }

  protected function feedItemToArray(sfFeedItem $item)
  {
    return array(
      'title'       => $item->getTitle(),
      'length'      => $length,
      'link'        => $item->getLink(),
      'content'     => $item->getDescription() ? $item->getDescription() : $item->getContent(),
      'pub_date'    => $item->getPubDate(),
      'author_name' => $item->getAuthorName(),
      'author_link' => $item->getAuthorLink(),
      'author_email' => $item->getAuthorEmail(),
      
    );
  }
  
}