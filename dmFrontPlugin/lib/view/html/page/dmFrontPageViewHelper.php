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
                //ajout stef
                if (is_object($const)) {
                  $content = $const->getContent();
                  // gestion de constantes contenant du php
                  if (strpos($content, '<?')===false){
                    // Pas de php, on laisse le contenu tel quel                    
                  } else {
                    // il y a du code php, on l'évalue
                    if (strpos($content, '?>')===false){
                                     
                    } else {
                      $p = new PhpStringParser();   // un classe bien sympa...
                      $content = $p->parse($content);
                    }
                  }
                  $existContent = true;
                } else {
                  $existContent = false;
                }
                // ajout stef
                if ($existContent) {
                    //$infosDev .= '[' . $matchDB . ' -> '.$const->getContent().']<br>';
                    if ($isMaj){  // on met la première lettre en majuscule si spécifié
                        $replaceConstante = ucfirst($content);
                    } else {
                        $replaceConstante = $content;
                    }
                    $html = str_replace($match, $replaceConstante , $html);
                } else {
                    // on n'affiche pas la constante inconnue
                    $html = str_replace($match, '' , $html);
                    // on affiche un message que pour l'environnement de dev
                    if (sfConfig::get('sf_environment') == 'dev') {
                        $html .= debugTools::infoDebug(array('Constante' => $match . ' n\'existe pas. Merci de l\'ajouter dans l\'administration du site > Outils > Constantes.'), 'warning');
                    }
                }
            }

            
        } else {
            // pas de constantes {{constantes}} dans ce widget
            //$html = '[pas de match]' . $html;
        }

        // fin ajout lionel
    
    return $html;
  }
}