<?php

/*
 * This file is part of the sfLESSPlugin.
 * (c) 2010 Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * lessCompileTask compiles LESS files thru symfony cli task system.
 *
 * @package    sfLESSPlugin
 * @subpackage tasks
 * @author     Konstantin Kudryashov <ever.zet@gmail.com>
 * @version    1.0.0
 */
class lessCompileTask extends lioshiBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('file', sfCommandArgument::OPTIONAL, 'LESS file to compile')
    ));

    $this->addOptions(array(
      new sfCommandOption(
        'application',  null, sfCommandOption::PARAMETER_OPTIONAL,
        'The application name', null
      ),
      new sfCommandOption(
        'env',          null, sfCommandOption::PARAMETER_REQUIRED,
        'The environment', 'prod'
      ),
      new sfCommandOption(
        'clean',        null, sfCommandOption::PARAMETER_NONE,
        'Removing all compiled CSS in web/css before compile'
      ),
      new sfCommandOption(
        'compress',     null, sfCommandOption::PARAMETER_NONE,
        'Compress final CSS file'
      ),
      new sfCommandOption(
        'debug',        null, sfCommandOption::PARAMETER_NONE,
        'Outputs debug info'
      )
    ));

    $this->namespace            = 'less';
    $this->name                 = 'compile';
    $this->briefDescription     = 'Recompiles LESS styles into web/css';
    $this->detailedDescription  = <<<EOF
The [less:compile|INFO] task recompiles LESS styles and puts compiled CSS into web/css folder.
Call it with:

  [php symfony less:compile|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {

    if(dmConfig::get('site_theme_version')=='v1' ){ // lessphp only for graphical system v1
          $this->logSection(
            'compilation mode',
            'lessphp', null, 'INFO'
          );
    } else {
          $this->logSection(
            'compilation mode',
            'less.js on server side', null, 'INFO'
          );      
    }

    // Inits sfLESS instance for compilation help
    $less = new sfLESS(new sfLESSConfig(
      false, isset($options['compress']) && $options['compress']
    ));

    // Remove old CSS files if --clean option specified
    if (isset($options['clean']) && $options['clean'])
    {
      foreach ($less->findCssFiles() as $cssFile)
      {
        if (!isset($arguments['file']) || (false !== strpos($cssFile, $arguments['file'] . '.css')))
        {
          unlink($cssFile);
          $this->logSection(
            'removed',
            str_replace(sfLESS::getConfig()->getCssPaths(), '', $cssFile), null, 'COMMAND'
          );
        }
      }
    }

    // Outputs debug info
    if (isset($options['debug']) && $options['debug'])
    {
      foreach (sfLESS::getConfig()->getDebugInfo() as $key => $value)
      {
        $this->logSection('debug', sprintf("%s:\t%s", $key, $value), null, 'COMMAND');
      }
    }

    // Compiles LESS files
    $timerTotal = new sfTimer;

    if (!count($less->findLessFiles())) {
        $this->logSection(
              'Compilation Less ERROR',
              'No less file compiled...',
              null,
              'ERROR'
            );      
    }


    foreach ($less->findLessFiles() as $lessFile)
    {
      if (!isset($arguments['file']) || (false !== strpos($lessFile, $arguments['file'] . '.less')))
      {
        $timer = new sfTimer;
        if ($less->compile($lessFile))
        {
          if (isset($options['debug']) && $options['debug'])
          {
            $this->logSection('compiled', sprintf("(%s s) %s => %s",
              $timer->getElapsedTime(),
              sfLESSUtils::getProjectRelativePath($lessFile),
              sfLESSUtils::getProjectRelativePath($less->getCssPathOfLess($lessFile))
            ), null, 'COMMAND');
          }
          else
          {
            $this->logSection(
              'compiled',
              str_replace(sfLESS::getConfig()->getLessPaths(), '', $lessFile),
              null,
              'COMMAND'
            );
          }
        } else {
          $this->logSection(
              'Compilation Less ERROR',
              $lessFile,
              null,
              'ERROR'
            );                
        }
      }
    }
    $this->logSection(
              'All compiled files in ',
              $timerTotal->getElapsedTime().' s',
              null,
              'COMMAND'
            );

    // A la fin du traitement on donne accès à tous les fichiers propriété d'apache: chmod 777 sur toute l'arborescence juste créée par le mkdir recursif
    //$dir = sfConfig::get('sf_web_dir').'/theme/css';
    $dir = sfLESS::getConfig()->getCssPaths();
    if (is_dir($dir)){
        $dirFinder = sfFinder::type('dir');
        $fileFinder = sfFinder::type('file');
        @$this->getFilesystem()->chmod($dirFinder->in($dir), 0777);
        @$this->getFilesystem()->chmod($fileFinder->in($dir), 0666);
    }

  }
}
