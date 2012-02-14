<?php

class dmWidgetContentImageView extends dmWidgetContentBaseMediaView
{
  
  public function configure()
  {
    parent::configure();

    $this->addRequiredVar('method');
  }

  protected function filterViewVars(array $vars = array())
  {
    $vars = parent::filterViewVars($vars);
    
    if ($vars['mediaTag'])
    {
		//Modif Arnaud
		//on récupère les dimensions passées dans la baseline
		if($vars['media_bl']) {
			//on récupère les dimensions de l'image depuis le système de grille
			$getHeight = spLessCss::gridGetHeight($vars['media_bl']);
			//calcul du ratio
			$ratio = $vars['mediaTag']->getWidth() / $vars['mediaTag']->getHeight();
			//application des dimensions
			$vars['height'] = $getHeight;
			$vars['width'] = $getHeight * $ratio;
			
			$vars['mediaTag']->size($vars['width'], $vars['height']);
		}
		
		
      if ($vars['legend'])
      {
        $vars['mediaTag']->alt($vars['legend']);
      }
      
      $vars['mediaTag']->method($vars['method']);
  
      if ($vars['method'] === 'fit')
      {
        $vars['mediaTag']->background($vars['background']);
      }
      
      if ($quality = dmArray::get($vars, 'quality'))
      {
        $vars['mediaTag']->quality($quality);
      }
      
      if ($vars['legend'])
      {
        $vars['mediaTag']->alt($vars['legend']);
      }
    }

    return $vars;
  }
  
  protected function doRender()
  {
    if ($this->isCachable() && $cache = $this->getCache())
    {
      return $cache;
    }

    $vars = $this->getViewVars();

    if (!$vars['mediaTag'])
    {
      $html = '';
    }
    else
    {
		
		
      $html = $vars['mediaTag']->render();
    }

    if($link = dmArray::get($vars, 'link'))
    {
      $html = $this->getHelper()->link($link)->text($html)->render();
    }

    if ($this->isCachable())
    {
      $this->setCache($html);
    }

    return $html;
  }
}