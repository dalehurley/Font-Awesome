<?php

class dmFrontPageViewHelper extends dmFrontPageBaseHelper
{
  
 /**
  * Override of render widget, to add a variable system
  * 
  * @param array $widget
  * @return string 
  */   
    
    
  public function renderWidgetInner(array $widget)
  {
    try
    {
      $renderer = $this->serviceContainer
      ->setParameter('widget_renderer.widget', $widget)
      ->getService('widget_renderer');
      
      $html = $renderer->getHtml();
    
      foreach($renderer->getJavascripts() as $javascript)
      {
        $this->serviceContainer->getService('response')->addJavascript($javascript);
      }
      
      foreach($renderer->getStylesheets() as $stylesheet => $options)
      {
        $this->serviceContainer->getService('response')->addStylesheet($stylesheet, '', $options);
      }
    }
    catch(Exception $e)
    {
      if (sfConfig::get('dm_debug') || 'test' === sfConfig::get('sf_environment'))
      {
        throw $e;
      }
      elseif (sfConfig::get('sf_debug'))
      {
        $html = $this->helper->link($this->page)
        ->currentSpan(false)
        ->param('dm_debug', 1)
        ->text(sprintf('[%s/%s] : %s', $widget['module'], $widget['action'], $e->getMessage()))
        ->title('Click to see the exception details')
        ->set('.dm_exception.s16.s16_error')
        ->render();
      }
      else
      {
        $html = '';
      }
    }

    // ajout lionel - gestion des constantes, entourées par {{nom_de_la_constante}}
    // Si la première lettre est en majuscule alors on la laisse.
        if (preg_match_all('/\{\{[A-Za-z0-9_]*\}\}/', $html, $matches)) {   // on récupère le nom de la base de données à dumper, la base locale au site
            $infosDev = '<b>Constantes:</b><br>';
            foreach ($matches[0] as $match) {
                $matchDB = str_replace('{{', '', $match);
                $matchDB = str_replace('}}', '', $matchDB);
                
                // Ajouter une majuscule
                if (ucfirst($matchDB) == $matchDB) {
                    $isMaj = true;
                } else {
                    $isMaj = false;
                }
                
                $const = dmDb::table('SidConstantes')->findOneByName(strtolower($matchDB));  // les sidConstantes.name sont en minuscules
                if (isset($const->content)) {
                    $infosDev .= '[' . $matchDB . ' -> '.$const->content.']<br>';
                    if ($isMaj){  // on met la première lettre en majuscule si spécifié
                        $replaceConstante = ucfirst($const->content);
                    } else {
                        $replaceConstante = $const->content;
                    }
                    $html = str_replace($match, $replaceConstante , $html);
                } else {
                    // on affiche un message que pour l'environnement de dev
                    if (sfConfig::get('sf_environment') == 'dev') {
                        $html .= _tag(
                                'div.warning', 
                                array(
                                    'onClick' => '$(this).hide();',
                                    ), 
                                    '<b>WARNING:</b><br>La constante <b>' . $match . '</b> n\'existe pas. Merci de l\'ajouter dans l\'administration du site > Outils > Constantes.'
                        );
                    }
                }
            }
                    // on affiche un message que pour l'environnement de dev
                    if (sfConfig::get('sf_environment') == 'dev') {
                        /* $html .= _tag(
                                'div.debug', 
                                array(
                                    'onClick' => '$(this).hide();'), 
                                    $infosDev
                        );*/
                    }
            
        } else {
            // pas de constantes ##constantes## dans ce widget
            //$html = '[pas de match]' . $html;
        }

        // fin ajout lionel
    
    return $html;
  }
}