<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @see sfAnsiColorFormatter provides methods to colorize text to be displayed on a console.
 */
class lioshiAnsiColorFormatter extends sfAnsiColorFormatter
{
  protected
    $styles = array(
      'ERROR'    => array('bg' => 'red', 'fg' => 'white', 'bold' => true),
      'INFO'     => array('fg' => 'green', 'bold' => true),
      'COMMENT'  => array('fg' => 'yellow'),
      'QUESTION' => array('bg' => 'cyan', 'fg' => 'black', 'bold' => false),
    ),
    $options    = array('bold' => 1, 'underscore' => 4, 'blink' => 5, 'reverse' => 7, 'conceal' => 8),
    $foreground = array('black' => 30, 'red' => 31, 'green' => 32, 'yellow' => 33, 'blue' => 34, 'magenta' => 35, 'cyan' => 36, 'white' => 37),
    $background = array('black' => 40, 'red' => 41, 'green' => 42, 'yellow' => 43, 'blue' => 44, 'magenta' => 45, 'cyan' => 46, 'white' => 47);

   /**
   * @see sfAnsicolorFormatter
   */
  public function formatSection($section, $text, $size = null, $style = 'COMMENT')
  {
    if (null === $size)
    {
      $size = $this->size;
    }

    $style = array_key_exists($style, $this->styles) ? $style : 'COMMENT';
    $width = 6 + strlen($this->format('', $style));

    return sprintf("  %-{$width}s %s", $this->format($section, $style), $this->excerpt($text, $size - 4 - (strlen($section) > 6 ? strlen($section) : 6)));
  }
 
}
