<?php

abstract class dmAdminModelGeneratorHelper extends sfModelGeneratorHelper
{
	/**
	 * @var DmModule
	 */
  protected $module;
  
  /**
   * @var dmAdminBaseGeneratedModuleActions
   */
  protected $action;
  
  public function __construct(dmModule $module)
  {
    $this->module = $module;
  }

  public function getModule()
  {
    return $this->module;
  }
  
  public function setAction($action)
  {
  	$this->action = $action;
  }
  
  public function getAction()
  {
  	return $this->action;
  }

  public function getI18nCatalogue()
  {
    return $this->getModule()->getOption('i18n_catalogue', 'dm');
  }

  public function linkToViewPage($object, $params)
  {
    try
    {
      $page = $object->getDmPage();
    }
    catch(Exception $e)
    {
      if (sfConfig::get('dm_debug'))
      {
        throw $e;
      }

      return '';
    }

    if (!$page)
    {
      return '';
    }

    sfConfig::get('dm_i18n_prefix_url') === true ? $culturePrefix = $page->getLang().'/' : $culturePrefix = null;
    
    return
    '<li class="sf_admin_action_view_page">'.
    _link('app:front/'.$culturePrefix.$page->get('slug'))
    ->title(__($params['title'], array('%1%' => dmString::strtolower(__($this->getModule()->getName()))), $this->getI18nCatalogue()))
    ->text(__($params['label'], array(), 'dm'))
    ->set('.s16.s16_file_html.sf_admin_action')
    ->target('blank').
    '</li>';
  }

  public function linkToNew($params)
  {
// version 0.9.2
/*    if (!$this->isActionAccess('new')) {
            return '';   // no access in credentials
        } else {         // return the link
            return link_to1(
                    __($params['label']), $this->getRouteArrayForAction('new'), array(
                'class' => 'sf_admin_action_new sf_admin_action s16 s16_add',
                'title' => __($params['title'], array('%1%' => dmString::strtolower(__($this->getModule()->getName()))))
            ));
        }
*/  
    if($this->module->getSecurityManager()->userHasCredentials('new'))
    {
      return link_to1(
      __($params['label'], array(), $this->getI18nCatalogue()), $this->getRouteArrayForAction('new'),
      array(
      'class' => 'sf_admin_action_new sf_admin_action s16 s16_add',
      'title' => __($params['title'], array('%1%' => dmString::strtolower(__($this->getModule()->getName()))), 'dm')
      ));
    }
    return '';
  }

  public function linkToDelete($object, $params)
  {
// version 0.9.2
/*        if (!$this->isActionAccess('delete')) {
            return '';   // no access in credentials
        } else {         // return the link
            $title = __($params['title'], array('%1%' => dmString::strtolower(__($this->getModule()->getName()))));
            return '<li class="sf_admin_action_delete">' . link_to1(__($params['label']), $this->getRouteArrayForAction('delete', $object), array(
                'class' => 's16 s16_delete dm_delete_link sf_admin_action',
                'title' => $title,
                'method' => 'delete',
                'confirm' => $title . ' ?'
            )) . '</li>';
        }
*/  
    if($this->module->getSecurityManager()->userHasCredentials('delete', $object))
    {
      $title = __(isset($params['title']) ? $params['title'] : $params['label'], array('%1%' => dmString::strtolower(__($this->getModule()->getName()))), 'dm');
      return '<li class="sf_admin_action_delete">'.link_to1(__($params['label'], array(), $this->getI18nCatalogue()), $this->getRouteArrayForAction('delete', $object),
      array(
    'class' => 's16 s16_delete dm_delete_link sf_admin_action',
    'title' => $title,
    'method' => 'delete',
    'confirm' => $title.' ?'
    )).'</li>';
    }
    return '';
  }

  public function linkToEdit($object, $params)
  {
    if($this->module->getSecurityManager()->userHasCredentials('edit', $object))
    {
      $title = __(isset($params['title']) ? $params['title'] : $params['label'], array('%1%' => dmString::strtolower(__($this->getModule()->getName()))), 'dm');
      return '<li class="sf_admin_action_edit">'.link_to1(__($params['label'], array(), $this->getI18nCatalogue()), $this->getRouteArrayForAction('edit', $object),
      array(
      'class' => 's16 s16_edit dm_edit_link sf_admin_action',
      'title' => $title,
      'method' => 'get'
      )).'</li>';
    }
    return '';
  }

  public function linkToList($params)
  {
    if($this->module->getSecurityManager()->userHasCredentials('index'))
    {
      return '<li class="sf_admin_action_list">'.link_to1(__($params['label'], array(), 'dm'), $this->getRouteArrayForAction('list'), array('class' => 's16 s16_arrow_left')).'</li>';
    }
  }

  public function linkToSave($object, $params)
  {
    if($this->module->getSecurityManager()->userHasCredentials('edit', $object))
    {
      return '<li class="sf_admin_action_save"><input class="green" type="submit" value="'.__($params['label'], array(), 'dm').'" /></li>';
    }
  }

  public function linkToAdd($params)
  {
    if($this->module->getSecurityManager()->userHasCredentials('new'))
    {
      return '<li class="sf_admin_action_add">'.$this->linkToNew($params).'</li>';
    }
    return '';
  }

  public function linkToSaveAndAdd($object, $params)
  {
    if($this->module->getSecurityManager()->userHasCredentials('edit', $object) && $this->module->getSecurityManager()->userHasCredentials('new'))
    {
      return '<li class="sf_admin_action_save_and_add"><input class="green" type="submit" value="'.__($params['label'], array(), 'dm').'" name="_save_and_add" /></li>';
    }
    return '';
  }

  public function linkToSaveAndList($object, $params)
  {
    if($this->module->getSecurityManager()->userHasCredentials('edit', $object) && $this->module->getSecurityManager()->userHasCredentials('index'))
    {
      return '<li class="sf_admin_action_save_and_list"><input class="green" type="submit" value="'.__($params['label'], array(), 'dm').'" name="_save_and_list" /></li>';
    }
    return '';
  }

  public function linkToSaveAndNext($object, $params)
  {
    if($this->module->getSecurityManager()->userHasCredentials('edit', $object))
    return '<li class="sf_admin_action_save_and_next"><input class="green" type="submit" value="'.__($params['label'], array(), 'dm').'" name="_save_and_next" /></li>';
  }

  public function linkToHistory($object, $params)
  {
    if (!$object->getTable()->isVersionable() || ! $this->module->getSecurityManager()->userHasCredentials('history', $object))
    {
      return '';
    }

    return '<li class="sf_admin_action_history">'.
    link_to1(
    __($params['label'], array(), 'dm'), $this->getRouteArrayForAction('history', $object),
    array(
        'class' => 'sf_admin_action s16 s16_clock_history',
        'title' => __($params['title'], array('%1%' => dmString::strtolower(__($this->getModule()->getName()))), 'dm')
    )
    ).
    '</li>';
  }

/**
     * Retourne true si l'action $action du module en cours est accessible par l'user 
     * @return boolean 
     */
    public function isActionAccess($actionToAccess) {
        //ajout lionel
        $moduleName = sfContext::getInstance()->getModuleName();

        $newConf = '$this->configuration = new ' . $moduleName . 'GeneratorConfiguration();';
        eval($newConf);

        $creds = $this->configuration->getCredentials($actionToAccess);

//        $arrayUserPerms = sfContext::getInstance()->getUser()->getAllPermissionNames(); // récupération des permissions du user
//        print_r($arrayUserPerms);
//        print_r($creds);
        
        $actionAccess = false;
        
        if (sfContext::getInstance()->getUser()->isSuperAdmin()) $actionAccess = true;

        if (count($creds) == 0) {  // no credentials on this action
            $actionAccess = true;
        } else {
            foreach ($creds as $cred) {
                if (is_array($cred)) {
                    foreach ($cred as $credOR) {  // si $cred est encore un tableau alors on est dans un credential du type OR : [[super_admin, admin]], il faut avoir l'un ou l'autre des credential
                        if (sfContext::getInstance()->getUser()->hasCredential($credOR)) {
                            $actionAccess = true;
                        } // pas de else car on est dans le OR
                    }
                } elseif (sfContext::getInstance()->getUser()->hasCredential($cred)) {  // on est dans le cas d'un credential AND : [super_admin, admin], il faut avoir les deux credentials
                    $actionAccess = true;
                } else {
                    $actionAccess = false;
                    break;
                }
            }
        }
        return $actionAccess;
        
//        $moduleName = sfContext::getInstance()->getModuleName();
//        $newConf = '$this->configuration = new ' . $moduleName . 'GeneratorConfiguration();';
//        eval($newConf);  // on evalue la configuration du module
//
//        $actionAccess = false;       
//        
//                //    echo 'eee'.$this->configuration->get('form.actions');
//        
//                print_r($this->configuration->getValue('list.batch_actions'));
//                exit;
//        
//        if ($listActions = $this->configuration->getValue('list.batch_actions')) {
//
// 
//            foreach ((array) $listActions as $action => $params) {
//                if ($actionToAccess == $action) {
//                    $actionAccess = $this->addCredentialCondition(true, $params);
//                    break;
//                }
//            }
//        }
//        return $actionAccess;
    }
  
}