<?php

/*
 * This file is part of the sfLESSPlugin.
 * (c) 2010 Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWebDebugPanelLESS implements LESS web debug panel.
 *
 * @package    sfLESSPlugin
 * @subpackage debug
 * @author     Konstantin Kudryashov <ever.zet@gmail.com>
 * @version    1.0.0
 */
class sfWebDebugPanelLESS extends sfWebDebugPanel
{
  /**
   * Some files have been compiled
   */
  protected $compiled = false;

  /**
   * Some errors where triggered during the compilation
   */
  protected $errors = false;

  /**
   * Configuration
   */
  protected $config;

  /**
   * Listens to LoadDebugWebPanel event & adds this panel to the Web Debug toolbar
   *
   * @param   sfEvent $event
   */
  public static function listenToLoadDebugWebPanelEvent(sfEvent $event)
  {
    $event->getSubject()->setPanel(
      'documentation',
      new self($event->getSubject())
    );
  }

  /**
   * @see sfWebDebugPanel
   */
  public function getTitle()
  {
    return '<img src="/sfLESSPlugin/images/css_go.png" alt="LESS helper" height="16px"/> ';
  }

  /**
   * @see sfWebDebugPanel
   */
  public function getPanelTitle()
  {
    return 'LESS parameters';
  }

  /**
   * @see sfWebDebugPanel
   */
  public function getPanelContent()
  {
    $panel = $this->getConfigurationContent();
/*
    . '<table class="sfWebDebugLogs" style="width: 300px"><tr><th>less file</th><th>css file</th><th style="text-align:center;">time (ms)</th></tr>';
    
    $errorDescriptions = sfLESS::getCompileErrors();
    foreach (sfLESS::getCompileResults() as $info)
    {
      $info['error'] = isset($errorDescriptions[$info['lessFile']]) ? $errorDescriptions[$info['lessFile']] : false;
      $panel .= $this->getInfoContent($info);
    }
    $panel .= '</table>';

    //$this->setStatus($this->errors?sfLogger::ERR:($this->compiled?sfLogger::INFO:sfLogger::WARNING));
*/

/*
      <tr style="%s">
        <td class="sfWebDebugLogType">%s</td>
        <td class="sfWebDebugLogType">%s</td>
        <td class="sfWebDebugLogNumber" style="text-align:center;">%.2f</td>
      </tr>
*/

if (sfConfig::get('sf_app')=='front' && dmConfig::get('site_theme_version')=='v2'){

            // affichage de la page courante
        $idDmPage = sfContext::getInstance()->getPage()->id;
        $dmPage = dmDb::table('DmPage')->findOneById($idDmPage);
        $pageCurrent =  $dmPage->module.'/'.$dmPage->action;
        $recordId = $dmPage->record_id;
        // récupération du Layout de la page en cours
        $layoutPage = sfContext::getInstance()->getPage()->getPageView()->get('layout');

        //  array of info
        $tabInfos['Site theme'] = dmConfig::get('site_theme');
        $tabInfos['site theme version'] = dmConfig::get('site_theme_version');
        $tabInfos['Current page'] = $pageCurrent;
        $tabInfos['Layout'] = $layoutPage;
        $tabInfos['Page recordId'] = $recordId;
        $tabInfos['Grid Columns'] = '';
        $tabInfos['Grid Column Width'] = '';
        $tabInfos['Grid Gutter Width'] = '';      
        $tabInfos['Fluid Grid Column Width'] = '';
        $tabInfos['Fluid Grid Gutter Width'] = '';  
        $tabInfos['Base Font Size'] = '';  
        $tabInfos['Base Font Family'] = '';  
        $tabInfos['Base Line Height'] = '';                  
        // les couleurs affichent une span swatch qui est colorisée en css dans import.less
        $tabInfos['Link Color'] = '<span class="swatch"></span>';  
        $tabInfos['Link Color Hover'] = '<span class="swatch"></span>'; 
        $tabInfos['text Color'] = '<span class="swatch"></span>'; 

        $panel .= '<dl style="" id="less_debug">';
        foreach ($tabInfos as $lib => $value) {
          $panel .= '<dt style="float:left; width: 200px"><strong>'.$lib.'</strong></dt><dd class="less_debug_'.dmString::slugify($lib).'">'.$value.'</dd>';
        }
        $panel .= '</dl>';
}




    return $panel;
  }

  /**
   * Returns configuration information for LESS compiler
   *
   * @return  string
   */
  protected function getConfigurationContent()
  {
    $debugInfo = '<dl id="less_debug" style="display: none;">';
    $this->config = sfLESS::getConfig();
    foreach ($this->config->getDebugInfo() as $name => $value)
    {
      $debugInfo .= sprintf('<dt style="float:left; width: 100px"><strong>%s:</strong></dt>
      <dd>%s</dd>', $name, $value);
    }
    $debugInfo .= '</dl>';

    return sprintf(<<<EOF
      <h2>configuration %s</h2>
      %s<br/>
EOF
      ,$this->getToggler('less_debug', 'Toggle debug info')
      ,$debugInfo
    );
  }

  /**
   * Returns information row for LESS style compilation
   *
   * @param   array   $info info of compilation process
   * @return  string
   */
  protected function getInfoContent($info, $error = false)
  {
    // ID of error row
    $errorId = md5($info['lessFile']);

    // File link for preferred editor
    $fileLink = $this->formatFileLink(
      $info['lessFile'], 1, str_replace($this->config->getLessPaths(), '', $info['lessFile'])
    );

    // Checking compile & error statuses
    if ($info['isCompiled'])
    {
      $this->compiled = true;
      $trStyle = 'background-color:#a1d18d;';
    }
    elseif ($info['error'])
    {
      $this->errors = true;
      $trStyle = 'background-color:#f18c89;';
      $fileLink .= ' ' . $this->getToggler('less_error_' . $errorId, 'Toggle error info');
    }
    else
    {
      $trStyle = '';
    }

    // Generating info rows
    $infoRows = sprintf(<<<EOF
      <tr style="%s">
        <td class="sfWebDebugLogType">%s</td>
        <td class="sfWebDebugLogType">%s</td>
        <td class="sfWebDebugLogNumber" style="text-align:center;">%.2f</td>
      </tr>
      <tr id="less_error_%s" style="display:none;background-color:#f18c89;"><td style="padding-left:15px" colspan="2">%s<td></tr>
EOF
      ,$trStyle
      ,$fileLink
      ,str_replace($this->config->getCssPaths(), '', $info['cssFile'])
      ,($info['isCompiled'] ? $info['compTime'] * 1000 : 0)
      ,$errorId
      ,$info['error']
    );

    return $infoRows;
  }
}
