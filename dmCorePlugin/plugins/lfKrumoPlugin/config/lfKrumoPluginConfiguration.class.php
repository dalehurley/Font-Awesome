<?php

/*
 * This file is part of the lfKrumoPlugin package.
 * (c) Diego Marangoni <diegomarangoni@msn.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * lfKrumoPluginConfiguration configuration.
 *
 * @package    lfKrumoPlugin
 * @subpackage config
 * @author     Diego Marangoni <diegomarangoni@msn.com>
 */
class lfKrumoPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see lfPluginConfiguration
   */
  public function configure()
  {
    require_once(dirname(__FILE__).'/../lib/krumo/class.krumo.php');
    require_once(dirname(__FILE__).'/../lib/helper/KrumoHelper.php');
  }
}
