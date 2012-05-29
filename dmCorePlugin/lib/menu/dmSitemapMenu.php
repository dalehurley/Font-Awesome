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
    return dmDb::query('DmPage p')
    ->withI18n()
//    ->where('pTranslation.is_active = ?', true)
     // modif stef le 14/12/2011       
    ->where('pTranslation.is_visible_bread_crumb = ?', true)
    ->andWhere('(p.module != ? AND p.module != ?) OR ( p.action != ? AND p.action != ? AND p.action != ? AND p.action != ?)', array('article', 'main', 'error404', 'search', 'signin', 'show'))
     // modif stef le 14/12/2011
    //->andWhere('p.module != ? OR ( p.action != ? AND p.action != ? AND p.action != ?)', array('main', 'error404', 'search', 'signin'))
    ->select('p.*, pTranslation.slug, pTranslation.name, pTranslation.title, pTranslation.is_secure, pTranslation.is_active')
    ->orderBy('p.lft ASC')
    ;
  }
}
