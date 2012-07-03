<?php

class dmSitemapMenu extends dmMenu
{
  public
    $hide = array();

  public function build()
  {
    $pages = $this->getPagesQuery()->execute(array(), Doctrine_Core::HYDRATE_RECORD_HIERARCHY);

    $this->addPage($pages[0]);

    return $this;
  }

  protected function addPage(DmPage $page)
  {
    $pageMenu = $this->addChild('page-'.$page->get('id'), $page)
    ->label(dmString::escape($page->get('name')))
    ->secure($page->get('is_secure'))
    ->credentials($page->get('credentials'));

    foreach($page->get('__children') as $child)
    {
       !in_array($child->id,$this->hide) && $pageMenu->addPage($child);
    }
  }

  protected function getPagesQuery()
  {
    $keyImplode = "";
    $valueImplode = "";
    $key = "";
    $value = "";
    $arrayKeys = array();
    $arrayValues = array();
    // pour récupérer les tableaux des pages à exclure du sitemap
    foreach (sfConfig::get('app_pages-for-sitemap_not-visibles') as $key => $page) {
        // Récupération des noms des MODULES et ACTIONS
        foreach ($page as $module => $action) {
            // Stockage en 1 variable string des noms des modules
//            if (!strpos($keyImplode, $module)) {
//                $keyImplode .= "'" . $module . "'";
//            };
            $arrayKeys[$module] = $module;
            
            // Stockage en 1 variable string des noms des actions
//            if (!strpos($valueImplode, $action)) {
//                $valueImplode .= "'" . $action . "'";
//            };
            $arrayValues[$action] = $action;
        };
//        $key = str_replace("''", "','", $keyImplode);
//        $value = str_replace("''", "','", $valueImplode);
    }
             
              
    return dmDb::query('DmPage p')
    ->withI18n()
    ->where('pTranslation.is_active = ?', true)
     // modif stef le 14/12/2011       
//    ->where('pTranslation.is_visible_bread_crumb = ?', true)
//    ->andWhereNotIn('p.module' , $key)
//    ->andWhereNotIn('p.action', $value)
            ->andWhere('(p.module NOT IN ?) OR ( p.action NOT IN ?)', array($arrayKeys, $arrayValues))
     // modif stef le 14/12/2011
    //->andWhere('p.module != ? OR ( p.action != ? AND p.action != ? AND p.action != ?)', array('main', 'error404', 'search', 'signin'))
    ->select('p.*, pTranslation.slug, pTranslation.name, pTranslation.title, pTranslation.is_secure, pTranslation.is_active')
    ->orderBy('p.lft ASC')
    ;
  }
}
