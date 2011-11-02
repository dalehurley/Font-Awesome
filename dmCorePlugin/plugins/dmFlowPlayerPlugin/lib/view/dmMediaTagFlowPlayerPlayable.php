<?php

/*
 * Abstract class for audio and video
 */
abstract class dmMediaTagFlowPlayerPlayable extends dmMediaTagBaseFlowPlayer
{
  public function getDefaultOptions()
  {
    return array_merge(parent::getDefaultOptions(), array(
      'autoplay'        => false,
      'player_web_path' => $this->context->getHelper()->getOtherAssetWebPath('dmFlowPlayerPlugin.swfPlayer'),
      'resize_method'   => 'scale',
      'control'         => true
    ));
  }

  /*
   * Wether or not the video will start automatically on page load
   */
  public function autoplay($val)
  {
    return $this->setOption('autoplay', (bool) $val);
  }
  
  /*
   * Wether or not to show the player controls
   */
  public function control($val)
  {
    return $this->setOption('control', (bool) $val);
  }

  /*
   * Wether or not to show the player controls URL
   */
  public function controlUrl($val)
  {
    return $this->setOption('controlUrl', $val);
  }

  /*
   * Wether or not to show the player controls URL
   */
  public function externalUrl($val)
  {
    return $this->setOption('externalUrl', $val);
  }

  /*
   * Change the player web path
   */
  public function player($val)
  {
    return $this->setOption('player_web_path', (string) $val);
  }
  
  /*
   * Change the scaling method
   */
  public function method($method)
  {
    if (!in_array($method, $this->getAvailableMethods()))
    {
      throw new dmException(sprintf('%s is not a valid method. These are : %s',
      $method,
      implode(', ', $this->getAvailableMethods())
      ));
    }

    return $this->setOption('resize_method', $method);
  }

  protected function jsonifyAttributes(array $attributes)
  {
    $flowPlayerOptions = $this->getFlowplayerOptions($attributes);

    foreach(array('src', 'mimeGroup', 'autoplay', 'player_web_path', 'resize_method', 'control', 'controlUrl', 'externalUrl') as $jsonAttribute)
    {
      unset($attributes[$jsonAttribute]);
    }

    $attributes['class'][] = json_encode($flowPlayerOptions);

    return $attributes;
  }

  protected function getFlowPlayerOptions(array $attributes)
  {

      $pureWhite = array(
            'buttonColor' => 'rgba(0, 0, 0, 0.9)',
			'buttonOverColor' => '#000000',
			'backgroundColor' => '#D7D7D7',
			'backgroundGradient' => 'medium',
			'sliderColor' => '#FFFFFF',

			'sliderBorder' => '1px solid #808080',
			'volumeSliderColor' => '#FFFFFF',
			'volumeBorder' => '1px solid #808080',

			'timeColor' => '#000000',
			'durationColor' => '#535353'
          );
        $pureTransparent = array(
            'backgroundColor' => 'transparent',
			'backgroundGradient' => 'none',
			'sliderColor' => '#FFFFFF',
			'sliderBorder' => '1.5px solid rgba(160,160,160,0.7)',
			'volumeSliderColor' => '#FFFFFF',
			'volumeBorder' => '1.5px solid rgba(160,160,160,0.7)',

			'timeColor' => '#ffffff',
			'durationColor' => '#535353',

			'tooltipColor' => 'rgba(255, 255, 255, 0.7)',
			'tooltipTextColor' => '#000000'
          );


    if ($attributes['control']) {
            if ($attributes['controlUrl'] == 'pureWhite'){
                $controlPlus = $pureWhite;
                $attributes['controlUrl'] = 'pure';
            } elseif ($attributes['controlUrl'] == 'pureTransparent'){
                $controlPlus = $pureTransparent;
                $attributes['controlUrl'] = 'pure';
            } else {
                $controlPlus = array();
            }
            $control = array_merge (array(
                'url' => 'flowplayer.controls-'.$attributes['controlUrl'].'-3.2.5.swf'   // air , pure , skinless , tube
            ),$controlPlus);
            //$control = 1;

        } else {
            $control = null;
        }

    if ($attributes['externalUrl']){
            $src = $attributes['externalUrl'];
        } else {
            $src = $attributes['src'];
        }

        return $this->filterFlowPlayerOptions(array(
      'clip' => array(
        //'url' => $attributes['src'],
        //'url' => 'http://www.lioshi.com/test.flv',
        'url' => $src,
        'autoPlay' => $attributes['autoplay'],
        'scaling' => $attributes['resize_method'],
        'autoBuffering' => true  // afficher la première frame de lma video
      ),
      'play' => array(
        //'controls' => $attributes['control']
        'replayLabel' => ''
      ),
      'plugins' => array(
        //'controls' => $attributes['control']
        'controls' => $control,
      ),
      'player_web_path' => $attributes['player_web_path'],
      'mimeGroup' => $attributes['mimeGroup']
    ), $attributes);
  }

  public function getAvailableMethods()
  {
    return array('fit', 'scale', 'half', 'orig');
  }
}