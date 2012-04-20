<?php

/**
 * Responsible for loading default data in the project
 * Such as superadmin user, basice pages and translations
 *
 */
class dmDataLoad
{
  protected
  $dispatcher,
  $i18n,
  $configuration,
  $logCallable,
  $datas = array(
    "permissions",
    "groups",
    "users",
    "settings",
    "layouts",
    "pages",
    "i18n",
    "media",
    "mail_templates"
  );

  public function __construct(sfEventDispatcher $dispatcher, dmI18n $i18n)
  {
    $this->dispatcher     = $dispatcher;
    $this->i18n           = $i18n;
  }

  public function setConfiguration(sfApplicationConfiguration $configuration)
  {
    $this->configuration = $configuration;

    return $this;
  }

  public function setLogCallable($callable)
  {
    $this->logCallable = $callable;

    return $this;
  }

  public function execute()
  {
    if(!$this->configuration)
    {
      throw new dmException('Please set an application configuration with ->setConfiguration()');
    }
    
    $this->log('load basic data');

    $this->dispatcher->notify(new sfEvent($this, 'dm.data.before'));

    foreach($this->datas as $data)
    {
      $this->{'load'.dmString::camelize($data)}();
    }

    $this->dispatcher->notify(new sfEvent($this, 'dm.data.after'));
  }

  protected function loadMailTemplates()
  {
    $array = array(
      'dm_user_forgot_password' => array(
        'description' => 'Sent to a user that requests a new password',
        'vars'        => 'username, email, step2_url',
        'from_email'  => 'webmaster@domain.com',
        'to_email'    => '%email%',
        'subject'     => dmConfig::get('site_name').': change your password',
        'body'        => 'Hello %username%'."\n".'You can choose a new password at %step2_url%'
      )
    );

    $table = dmDb::table('DmMailTemplate');

    foreach($array as $name => $data)
    {
      if(!$mailTemplate = $table->findOneByNameWithI18n($name))
      {
        $table->create(array_merge($data, array('name' => $name)))->save();
      }
      elseif(!$mailTemplate->hasCurrentTranslation())
      {
        /*
         * Try to find an existing config from another culture
         */
        $existing = dmDb::query('DmMailTemplate t')
        ->where('t.id = ?', $mailTemplate->id)
        ->limit(1)
        ->fetchArray();

        if($existing = dmArray::first($existing))
        {
          $data = $existing;
          unset($data['id'], $data['lang']);
        }

        $mailTemplate->fromArray($data)->getCurrentTranslation()->save();
      }
    }
  }

  protected function loadSettings()
  {
    $array = array(
      'site_name' => array(
        'default_value' => dmString::humanize(dmProject::getKey()),
        'description' => 'The site name',
        'group_name' =>'site'
      ),
      'site_ndd' => array(
        'default_value' => '',
        'type' => 'text',
        'params' => 'readonly="readonly"',
        'description' => 'The site ndd can be change by task dm:change-ndd',
        'group_name' =>'site'
      ),      
      'site_active' => array(
        'type' => 'boolean',
        'default_value' => 1,
        'description' => 'Is the site ready for visitors ?',
        'group_name' =>'site'
      ),
      'site_indexable' => array(
        'type' => 'boolean',
        'default_value' => 0,
        'description' => 'Is the site ready for search engine crawlers ?',
        'group_name' =>'site'
      ),
      // lioshi : seems to be useless
      // 'site_working_copy' => array(
      //   'type' => 'boolean',
      //   'default_value' => 1,
      //   'description' => 'Is this site the current working copy ?',
      //   'group_name' =>'site'
      // ),
      'ga_key' => array(
        'description' => 'The google analytics key without javascript stuff ( e.g. UA-9876614-1 )',
        'group_name' => 'tracking',
        'credentials' => 'google_analytics'
      ),
      'ga_token' => array(
        'description' => 'Auth token gor Google Analytics, computed from password',
        'group_name' => 'internal',
        'credentials' => 'google_analytics'
      ),
      'gwt_key' => array(
        'description' => 'The google webmaster tools filename without google and .html ( e.g. a913b555ba9b4f13 )',
        'group_name' =>'tracking',
        'credentials' => 'google_webmaster_tools'
      ),
      'xiti_code' => array(
        'type' => 'textarea',
        'description' => 'The xiti html code',
        'group_name' => 'tracking',
        'credentials' => 'xiti'
      ),
      'search_stop_words' => array(
        'type' => 'textarea',
        'description' => 'Words to exclude from searches (e.g. the, a, to )',
        'group_name' =>'search engine',
        'credentials' => 'search_engine'
      ),
      'base_urls' => array(
        'type' => 'textarea',
        'description' => 'Diem base urls for different applications/environments/cultures',
        'group_name' => 'internal',
        'credentials' => 'system'
      ),
      'image_resize_method' => array(
        'type' => 'select',
        'default_value' => 'center',
        'description' => 'Default method when an image needs to be resized',
        'params' => 'fit=Fit scale=Scale inflate=Inflate top=Top right=Right left=Left bottom=Bottom center=Center',
        'group_name' => 'interface',
        'credentials' => 'interface_settings'
      ),
      'image_resize_quality' => array(
        'type' => 'number',
        'default_value' => 95,
        'description' => 'Jpeg default quality when generating thumbnails',
        'group_name' => 'interface',
        'credentials' => 'interface_settings'
      ),
      'link_external_blank' => array(
        'type' => 'boolean',
        'default_value' => 0,
        'description' => 'Links to other domain get automatically a _blank target',
        'group_name' => 'interface',
        'credentials' => 'interface_settings'
      ),
      'link_current_span' => array(
        'type' => 'boolean',
        'default_value' => 1,
        'description' => 'Links to current page are changed from <a> to <span>',
        'group_name' => 'interface',
        'credentials' => 'interface_settings'
      ),
      'link_use_page_title' => array(
        'type' => 'boolean',
        'default_value' => 1,
        'description' => 'Add an automatic title on link based on the target page title',
        'group_name' => 'interface',
        'credentials' => 'interface_settings'
      ),
      'title_prefix' => array(
        'default_value' => '',
        'description' => 'Append something at the beginning of all pages title',
        'group_name' =>'seo',
        'credentials' => 'manual_metas'
      ),
      'title_suffix' => array(
        //'default_value' => ' | '.dmString::humanize(dmProject::getKey()),
        'default_value' => ' | '.dmString::humanize(dmConfig::get('site_name')),
        'description' => 'Append something at the end of all pages title',
        'group_name' =>'seo',
        'credentials' => 'manual_metas'
      ),
      'smart_404' => array(
        'type' => 'boolean',
        'default_value' => 1,
        'description' => 'When a page is not found, user is redirect to a similar page. The internal search index is used to find the best page for requested url.',
        'group_name' => 'seo',
        'credentials' => 'url_redirection'
      ),
      'client_type' => array(
        'default_value' => '',
        'description' => 'The client type',
        'group_name' =>'site'
      ),
      'site_type' => array(
        'default_value' => '',
        'description' => 'The site type',
        'group_name' =>'site'
      ),
      'site_email_sender' => array(
        'default_value' => '',
        'description' => 'The site email sender',
        'group_name' =>'site'
      ),
      'site_email' => array(
        'default_value' => '',
        'description' => 'The site email recepter',
        'group_name' =>'site'
      ),
      'site_theme_version' => array(
        'default_value' => '',
        'type' => 'text',
        'params' => 'readonly="readonly"',
        'description' => 'The site_theme_version can be change by task theme:install',
        'group_name' =>'site'
      ),
      'site_theme' => array(
        'default_value' => '',
        'type' => 'text',
        'params' => 'readonly="readonly"',
        'description' => 'The site_theme can be change by task theme:install',
        'group_name' =>'site'
      ),
    );

    $existingSettings = dmDb::query('DmSetting s INDEXBY s.name')
    ->withI18n()
    ->fetchRecords();

    foreach($array as $name => $config)
    {
      if (!isset($existingSettings[$name]))
      {
        $setting = new DmSetting;
        $setting->set('name', $name);
        $setting->fromArray($config);

        $setting->save();
      }
      /*
       * The setting exists, but the default culture has no value!
       * This can happen when the default culture changes
       */
      elseif(!$existingSettings[$name]->hasCurrentTranslation())
      {
        /*
         * Try to find an existing config from another culture
         */
        $existing = dmDb::query('DmSettingTranslation s')
        ->where('s.id = ?', $existingSettings[$name]->id)
        ->limit(1)
        ->fetchArray();
        
        if($existing = dmArray::first($existing))
        {
          $config = $existing;
          unset($config['id'], $config['lang']);
        }
        
        $existingSettings[$name]->fromArray($config)->getCurrentTranslation()->save();
      }
    }

    dmConfig::load(false);
  }

  protected function loadMedia()
  {
    dmDb::table('DmMediaFolder')->checkRoot()->sync();
  }

  protected function loadUsers()
  {
    if (!$superAdmin = dmDb::query('DmUser u')->where('u.is_super_admin = ?', true)->count())
    {
      $password = Doctrine_Manager::connection()->getOption('password');

      if (empty($password))
      {
        $password = 'admin';
      }

      dmDb::create('DmUser', array(
        'is_super_admin' => true,
        'username' => 'admin',
        'password' => $password,
        'email' => 'admin@'.dmProject::getKey().'.com'
      ))->save();
    }
  }

  protected function loadLayouts()
  {
    if (!dmDb::table('DmLayout')->count())
    {
      dmDb::create('DmLayout', array('name' => 'Global'))->saveGet()->refresh(true);
    }
  }

  protected function loadPages()
  {
    dmDb::table('DmPage')->checkBasicPages();
  }

  protected function loadI18n()
  {
    $this->loadI18nCatalogue();
    $this->loadI18nTransUnit();
  }

  protected function loadI18nCatalogue()
  {
    foreach($this->i18n->getCultures() as $culture)
    {
      /*
       * English to $culture
       */
      if ($culture != 'en' && !dmDb::table('DmCatalogue')->retrieveBySourceTargetSpace('en', $culture, 'messages'))
      {
        dmDb::create('DmCatalogue', array(
          'source_lang' => 'en',
          'target_lang' => $culture,
          'name' => 'messages.'.$culture
        ))->save();
      }

      if ($culture != 'en' && !dmDb::table('DmCatalogue')->retrieveBySourceTargetSpace('en', $culture, 'dm'))
      {
        dmDb::create('DmCatalogue', array(
          'source_lang' => 'en',
          'target_lang' => $culture,
          'name' => 'dm.'.$culture
        ))->save();
      }

      /*
       * Default culture to $culture
       */
      if ($culture != sfConfig::get('sf_default_culture'))
      {
        if (!dmDb::table('DmCatalogue')->retrieveBySourceTargetSpace(sfConfig::get('sf_default_culture'), $culture, 'messages'))
        {
          dmDb::create('DmCatalogue', array(
            'source_lang' => sfConfig::get('sf_default_culture'),
            'target_lang' => $culture,
            'name' => 'messages.'.$culture
          ))->save();
        }
      }
    }
  }

  protected function loadI18nTransUnit()
  {
    foreach( $this->i18n->getCultures() as $culture)
    {
      if ($culture == 'en')
      {
        continue;
      }
      /*
       * English to $culture
       */
      $catalogue = dmDb::table('DmCatalogue')->retrieveBySourceTargetSpace('en', $culture, 'dm');
      $dataFiles = $this->configuration->getConfigPaths('data/dm/i18n/en_'.$culture.'.yml');

      $table = dmDb::table('DmTransUnit');

      $existQuery = $table->createQuery('t')
      ->select('t.id, t.source, t.target, t.created_at, t.updated_at')
      ->where('t.dm_catalogue_id = ? AND t.source = ?');
      $catalogueId = $catalogue->get('id');

      $nbAdded = 0;
      $nbUpdated = 0;

      foreach($dataFiles as $dataFile)
      {
        if (!is_array($data = sfYaml::load(file_get_contents($dataFile))))
        {
          continue;
        }

        $addedTranslations = new Doctrine_Collection($table);
        $line = 0;
        foreach($data as $source => $target)
        {
          ++$line;

          if (!is_string($source) || !is_string($target))
          {
            $this->log('Error line '.$line.': '.dmProject::unrootify($dataFile));
          }
          else
          {
            $existing = $existQuery->fetchOneArray(array($catalogueId, $source));

            if (!empty($existing) && $existing['source'] === $source)
            {
              if ($existing['target'] !== $target)
              {
                //$this->log(sprintf('%s -> %s', $existing['target'], $target));
                // don't overwrite user modified translations
                if ($existing['created_at'] === $existing['updated_at'])
                {
                  $table->createQuery()
                  ->update('DmTransUnit')
                  ->set('target', '?', array($target))
                  ->where('id = ?', $existing['id'])
                  ->execute();

                  ++$nbUpdated;
                }
              }
            }
            elseif(empty($existing))
            {
              $addedTranslations->add(dmDb::create('DmTransUnit', array(
                'dm_catalogue_id' => $catalogue->get('id'),
                'source' => $source,
                'target' => $target
              )));

              ++$nbAdded;
            }
          }
        }

        $addedTranslations->save();
      }

      if ($nbAdded)
      {
        $this->log(sprintf('%s: added %d translation(s)', $culture, $nbAdded));
      }
      if ($nbUpdated)
      {
        $this->log(sprintf('%s: updated %d translation(s)', $culture, $nbUpdated));
      }
    }

  }

  protected function loadPermissions()
  {
      // les permissions ne s'effacent pas dans la base - DONC on efface la table DmPermission pour réinjecter le tableau à jour
      // 
      dmDB::query()->from('DmPermission')->delete()->execute();
      
      // injection du tableau qui est dans le app
    $array = sfConfig::get('app_permissions_list');

    $existingPermissions = dmDb::query('DmPermission p INDEXBY p.name')
    ->select('p.name')
    ->fetchArray();

    $addedPermissions = new myDoctrineCollection('DmPermission');
    foreach($array as $name => $description)
    {
      if (!isset($existingPermissions[$name]))
      {
        $addedPermissions->add(dmDb::create('DmPermission', array(
          'name' => $name,
          'description' => $description
        )));
      }
    }
    $addedPermissions->save();
  }

  protected function loadGroups()
  {
    
        $permission = sfConfig::get('app_modules-specifiques_'.dmConfig::get('client_type').'-'.dmConfig::get('site_type'));
        if(count($permission) == 0) $permission = array();
        $permissionsClientWeb = array_merge(sfConfig::get('app_groupes-permissions-client-web_permissions'),$permission);
        $permissionsBaseDoc = array_merge(sfConfig::get('app_groupes-permissions-base-doc_permissions'),$permission);
      
      $array = array(
/*      "developer" => array(
        'description' => "Able to read and update source code",
        'permissions' => array(
          'system'
        )
      ),
      "seo" => array(
        'description' => "Seo knowledge",
        'permissions' => array(
          'admin',
          'sitemap',
          'automatic_metas',
          'manual_metas',
          'url_redirection',
          'google_analytics',
          'use_google_analytics',
          'google_webmaster_tools',
          'use_google_webmaster_tools',
          'tool_bar_admin',
          'page_bar_admin',
          'tool_bar_front',
          'page_bar_front',
          'see_log',
          'config_panel',
          'site_view',
          'see_chart'
        )
      ),
      "integrator" => array(
        'description' => "Integrator",
        'permissions' => array(
          'admin',
          'content',
          'code_editor',
          'media_library',
          'loremize',
          'export_table',
          'tool_bar_admin',
          'page_bar_admin',
          'media_bar_admin',
          'tool_bar_front',
          'page_bar_front',
          'media_bar_front',
          'zone_add',
          'zone_edit',
          'zone_delete',
          'widget_add',
          'widget_edit',
          'widget_delete',
          'page_add',
          'page_edit',
          'page_delete',
          'config_panel',
          'translation',
          'layout',
          'interface_settings',
          'site_view',
          'see_chart',
          'see_log'
        )
      ),
      "webmaster 1" => array(
        'description' => "Webmaster level 1",
        'permissions' => array(
          'admin',
          'content',
          'tool_bar_admin',
          'page_bar_admin',
          'media_bar_admin',
          'tool_bar_front',
          'page_bar_front',
          'media_bar_front',
          'search_engine',
          'see_log',
          'config_panel',
          'translation',
          'site_view',
          'see_chart'
        )
      ),
      "writer" => array(
        'description' => "Writer",
        'permissions' => array(
          'admin',
          'content',
          'tool_bar_admin',
          'page_bar_admin',
          'media_bar_admin',
          'see_log',
          'site_view',
          'see_chart'
        )
      ),
      "front_editor" => array(
        'description' => "Can fast edit front widgets",
        'permissions' => array(
          'widget_edit_fast',
          'widget_edit_fast_record',
          'widget_edit_fast_content_title',
          'widget_edit_fast_content_link',
          'widget_edit_fast_content_text',
          'widget_edit_fast_content_image',
          'widget_edit_fast_navigation_menu'
        )
      ),
 * 
 * Ajout SID : les groupes d'autorisations à l'installation pour le client
 */
        // droit pour les sites
        "web_client" => array(
                'description' => sfConfig::get('app_groupes-permissions-client-web_description'),
                'permissions' => $permissionsClientWeb
                  
            ),
        // droit pour les bases doc
        "basedoc_client" => array(
                'description' => sfConfig::get('app_groupes-permissions-base-doc_description'),
                'permissions' => $permissionsBaseDoc
            ),
        );
      
    $permissions = dmDb::query('DmPermission p INDEXBY name')->select('p.name')->fetchArray();

    $groups = new Doctrine_Collection(dmDb::table('DmGroup'));
    foreach($array as $name => $params)
    {
      if (!$group = dmDb::query('DmGroup g')->where('g.name = ?', $name)->fetchRecord())
      {
        $group = dmDb::create('DmGroup', array(
          'name' => $name,
          'description' => $params['description']
        ))->saveGet();
      }
      $groups->add($group);

      $groupPermissions = array();
      foreach($params['permissions'] as $permissionName)
      {
        if (!isset($permissions[$permissionName]))
        {
          throw new dmException('There is no permission called '.$permissionName);
        }
        $groupPermissions[] = $permissions[$permissionName]['id'];
      }
      $group->link('Permissions', $groupPermissions);
    }
    $groups->save();
  }

  protected function log($msg)
  {
    if(is_callable($this->logCallable))
    {
      call_user_func($this->logCallable, $msg);
    }
  }
}