<?php

class dmWidgetContentFlowPlayerView extends dmWidgetContentBaseMediaView
{
  public function configure()
  {
    parent::configure();

    $this->addRequiredVar('method');

    $this->addJavascript(array(
      'dmFlowPlayerPlugin.flowPlayer',
      'dmFlowPlayerPlugin.dmFlowPlayer',
      'dmFlowPlayerPlugin.launcher'
    ));
  }
  
	public function getStylesheets() {
		//on créé un nouveau tableau car c'est un nouveau widget (si c'est une extension utiliser $stylesheets = parent::getStylesheets();)
		$stylesheets = parent::getStylesheets();
		
		//lien vers le js associé au menu
		$cssLink = sfConfig::get('sf_css_path_template'). '/Widgets/ContentFlowPlayer/ContentFlowPlayer.css';
		//chargement de la CSS si existante
		if (is_file(sfConfig::get('sf_web_dir') . $cssLink)) $stylesheets[] = $cssLink;
		
		return $stylesheets;
	}

  protected function filterViewVars(array $vars = array())
  {
    $vars = parent::filterViewVars($vars);
/*
var_dump($vars);

    if ($vars['externalUrl']){
        $mediaTag = new dmMediaTagFlowPlayerPlayable();
        $media = new dmMedia();
    }
    elseif (!empty($vars['mediaId']) || $this->isRequiredVar('mediaId'))
    {
      $media = dmDb::table('DmMedia')->findOneByIdWithFolder($vars['mediaId']);
      
      if (!$media instanceof DmMedia)
      {
        throw new dmException('No DmMedia found for media id : '.$vars['mediaId']);
      }
      
      $mediaTag = $this->getHelper()->media($media);
  
      if (!empty($vars['width']) || !empty($vars['height']))
      {
        $mediaTag->size(dmArray::get($vars, 'width'), dmArray::get($vars, 'height'));
      }
    }
    else
    {
      $media    = null;
      $mediaTag = null;
    }
  
    $vars['media'] = $media;
    $vars['mediaTag'] = $mediaTag;

var_dump($vars);
*/

    
    $vars['mediaTag']->addClass('dm_widget_content_flow_player');
    
    if ($vars['mediaTag'] instanceof dmMediaTagFlowPlayerPlayable)
    {
      if ($vars['splashMediaId'])
      {
        $splashMedia = dmDb::table('DmMedia')->findOneByIdWithFolder($vars['splashMediaId']);
        
        if (!$splashMedia instanceof DmMedia)
        {
          throw new dmException('No DmMedia found for media id : '.$vars['splashMediaId']);
        }
        
        $splashTag = $this->getHelper()->media($splashMedia)->alt($vars['splashAlt']);
        
        $vars['mediaTag']->splash($splashTag);
      }
      
      $vars['mediaTag']
      ->autoplay($vars['autoplay'])
      ->method($vars['method'])
      ->control($vars['control'])
      ->controlUrl($vars['controlUrl'])
      ->externalUrl($vars['externalUrl']);
    }
    
    if ($vars['mediaTag'] instanceof dmMediaTagFlowPlayerApplication)
    {
      foreach(array('flashConfig', 'flashVars') as $key)
      {
        $array = empty($vars[$key]) ? array() : sfYaml::load($vars[$key]);
        
        if (!empty($array))
        {
          $vars['mediaTag']->$key($array);
        }
      }
    }
    
    unset($vars['autoplay'], $vars['method'], $vars['legend'], $vars['splashMediaId'], $vars['splashAlt'], $vars['flashConfig'], $vars['flashVars']);
    
    return $vars;
  }
}