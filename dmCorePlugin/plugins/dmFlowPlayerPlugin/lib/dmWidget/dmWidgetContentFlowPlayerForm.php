<?php

class dmWidgetContentFlowPlayerForm extends dmWidgetContentBaseMediaForm
{

  public function configure()
  {
    parent::configure();
    
    unset($this['legend']);
    
    $this->widgetSchema['autoplay'] = new sfWidgetFormInputCheckbox();
    $this->validatorSchema['autoplay'] = new sfValidatorBoolean();
    
    $this->widgetSchema['autoplay']->setLabel('Play automatically');
    
    $this->widgetSchema['control'] = new sfWidgetFormInputCheckbox();
    $this->validatorSchema['control'] = new sfValidatorBoolean();
    
    $this->widgetSchema['control']->setLabel('Show controls');

    /* ajout de champs pour l'apparence du scrollbar */
    $controlUrls = $this->getI18n()->translateArray($this->getControlUrls());
    $this->widgetSchema['controlUrl'] = new sfWidgetFormSelect(array(
      'choices' => $controlUrls
    ));
    $this->validatorSchema['controlUrl'] = new sfValidatorChoice(array(
      'choices' => array_keys($controlUrls),
      'required' => false
    ));
    $this->widgetSchema['controlUrl']->setLabel('Type scrollbar');

   /* ajout du champ pour un fichier externe */
    $this->widgetSchema['externalUrl'] = new sfWidgetFormInputText();
    $this->validatorSchema['externalUrl'] = new sfValidatorRegex(array(
      'pattern' => '/\.flv$|\.mp4$/',
      'required' => false
    ));
    $this->widgetSchema['externalUrl']->setLabel('Or external URL');
    $this->widgetSchema->setHelp('externalUrl','Format requis: FLV, MP4');


    $methods = $this->getI18n()->translateArray($this->getResizeMethods());

    $this->widgetSchema['method'] = new sfWidgetFormSelect(array(
      'choices' => $methods
    ));
    $this->validatorSchema['method'] = new sfValidatorChoice(array(
      'choices' => array_keys($methods),
      'required' => false
    ));
    
    $this->widgetSchema['flashConfig'] = new sfWidgetFormTextarea(array(), array(
      'rows' => 8
    ));
    $this->validatorSchema['flashConfig'] = new dmValidatorYaml(array(
      'required' => false
    ));
    
    $this->widgetSchema['flashVars'] = new sfWidgetFormTextarea(array(), array(
      'rows' => 8
    ));
    $this->validatorSchema['flashVars'] = new dmValidatorYaml(array(
      'required' => false
    ));

    /* si l'externalUrl est spécifié on oublie l'id media */
/*    if ($this->getValueOrDefault('mediaId') && $this->getValueOrDefault('externalUrl')){
         $this->widgetSchema['mediaId'] = new sfWidgetFormInputHidden(array());
         $this->validatorSchema['mediaId'] = new sfValidatorString(array(
            'required' => false
        ));
    }
*/
    
    $this->configureSplashMediaFields();
  }

  public function getJavascripts()
  {
    return array_merge(parent::getJavascripts(), array(
      'lib.ui-tabs',
      'core.tabForm',
      'dmFlowPlayerPlugin.widgetForm'
    ));
  }

  public function getStylesheets()
  {
    return array_merge(parent::getStylesheets(), array(
      'lib.ui-tabs'
    ));
  }
  
  protected function configureSplashMediaFields()
  {
    if($mediaId = $this->getValueOrDefault('splashMediaId'))
    {
      $media = dmDb::table('DmMedia')->findOneByIdWithFolder($mediaId);
    }
    else
    {
      $media = null;
    }

    $this->widgetSchema['splashMediaName'] = new sfWidgetFormInputText(array(), array(
      'readonly'  => true,
      'class'     => 'dm_splash_media_receiver'
    ));
    $this->validatorSchema['splashMediaName'] = new sfValidatorPass();

    $this->widgetSchema->setLabel('splashMediaName', 'Use media');
    
    if ($media)
    {
      $this->setDefault('splashMediaName', $media->getRelPath());
    }
    else
    {
      $this->setDefault('splashMediaName', $this->__('Drag & Drop an image here'));
    }

    $this->widgetSchema['splashMediaId'] = new sfWidgetFormInputHidden(array());
    $this->validatorSchema['splashMediaId'] = new sfValidatorDoctrineChoice(array(
      'model'    => 'DmMedia',
      'required' => false
    ));

    $this->widgetSchema['splashFile'] = new sfWidgetFormDmInputFile();
    $this->validatorSchema['splashFile'] = new sfValidatorFile(array(
      'required' => false
    ));
    $this->widgetSchema->setLabel('splashFile', 'Or upload a file');
    
    $this->widgetSchema['splashAlt'] = new sfWidgetFormInputText();
    $this->validatorSchema['splashAlt'] = new sfValidatorString(array(
      'required' => false
    ));
    
    $this->widgetSchema['splashAlt']->setLabel('Splash alt');
    
    $this->widgetSchema['removeSplash'] = new sfWidgetFormInputCheckbox();
    $this->validatorSchema['removeSplash'] = new sfValidatorBoolean();
    
    $this->widgetSchema['removeSplash']->setLabel('Remove splash');
  }

  public function getResizeMethods()
  {
    return array(
      'scale'   => 'Scale to fill all available space',
      'fit'     => 'Fit by preserving the aspect ratio',
      'half'    => 'Half-size (preserves aspect ratio)',
      'orig'    => 'Use the file original dimensions'
    );
  }

  public function getControlUrls()
  {
    return array(
      'air'   => 'Air',
      'pure'     => 'Pure',
      'pureTransparent'     => 'Pure transparent',
      'pureWhite'     => 'Pure blanc',
      'tube'    => 'Tube'
    );
  }
  
  protected function getFirstDefaults()
  {
    return array_merge(parent::getFirstDefaults(), array(
      'width'   => 300,
      'height'  => 300,
      'method'  => 'scale',
      'control' => true
    ));
  }
  
  public function getWidgetValues()
  {
    $values = parent::getWidgetValues();
    
    if ($values['splashFile'])
    {
      $this->createSplashMediaFromUploadedFile($values);
    }
    elseif($values['removeSplash'])
    {
      $values['splashMediaId'] = null;
    }

    unset($values['splashMediaName'], $values['splashFile'], $values['removeSplash']);
    
    if (empty($values['width']))
    {
      if ($values['widget_width'])
      {
        $values['width'] = $values['widget_width'];
      }
      else
      {
        $values['width'] = 300;
      }
      
      $values['height'] = dmArray::get($values, 'height', (int) ($values['width'] * 2/3), true);
    }
    elseif (empty($values['height']))
    {
      $values['height'] = (int) ($values['width'] * 2/3);
    }

    $values['method'] = dmArray::get($values, 'method', $this->getFirstDefault('method'), true);
    
    unset($values['widget_width']);

    return $values;
  }

    public function checkMediaSource($validator, $values)
  {
    if (!$values['mediaId'] && !$values['file'] && !$values['externalUrl'])
    {
      throw new sfValidatorError($validator, 'You must use a media, upload a file or write external file s Url');
    }

    return $values;
  }
  
  protected function createSplashMediaFromUploadedFile(array &$values)
  {
    $file   = $values['splashFile'];
    $folder = dmDb::table('DmMediaFolder')->findOneByRelPathOrCreate('widget');

    $media = dmDb::table('DmMedia')->findOneByFileAndDmMediaFolderId(
      dmOs::sanitizeFileName($file->getOriginalName()),
      $folder->id
    );

    if (!$media)
    {
      $media = dmDb::create('DmMedia', array(
        'dm_media_folder_id' => $folder->id
      ))
      ->create($file)
      ->saveGet();
    }

    $values['splashMediaId'] = $media->id;
  }

  protected function renderContent($attributes)
  {
    $media = ($mediaId = $this->getValueOrDefault('mediaId'))
    ? dmDb::table('DmMedia')->find($mediaId)
    : null;
    
    if(!$media && !$this->getValueOrDefault('externalUrl'))
    {
      $template = 'formEmpty';
    }
    elseif ($this->getValueOrDefault('externalUrl')){
        $template = 'formAudioVideo';
    }
    else
    {
      if(in_array($media->getMimeGroup(), array('audio', 'video')))
      {
        $template = 'formAudioVideo';
      }
      else
      {
        $template = 'formApplication';
      }
    }

    return $this->getHelper()->renderPartial('dmWidgetContentFlowPlayer', $template, array(
      'form' => $this,
      'hasSplashMedia' => (boolean) $this->getValueOrDefault('splashMediaId'),
      'baseTabId' => 'dm_widget_flow_player_'.$this->dmWidget->get('id')
    ));
  }

}