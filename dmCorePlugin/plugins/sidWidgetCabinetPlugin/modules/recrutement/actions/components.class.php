<?php
/**
 * Recrutement components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 * 
 * 
 * 
 * 
 */
class recrutementComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->recrutementPager = $this->getPager($query);
    
    $this->recrutementPager->setOption('ajax', true);
    //    $request = Doctrine_Query::create()->from('SidCabinetRecrutement a')
    //                        ->where('a.is_active = ?', true)
    //                        ->execute();
    //      $this->recrutes = $request;
  }

  public function executeAnnonceRecrutement()
  {
    //          $requeteAnnonce = dmDb::table('SidCabinetRecrutement')->findOneByOnHome(true)->fetchArray();
        $requeteAnnonce = Doctrine_Query::create()->from('SidCabinetRecrutement a')
                ->where('a.on_home = ?', true)
                ->andWhere('a.is_active = ?', true)
                ->orderBy('a.updated_at DESC')
                ->execute();
        $this->annonces = $requeteAnnonce;
        $dmPage = Dmdb::table('DmPage')->createQuery('a')->where('a.module = ? and a.action = ? and a.record_id = ?', array('recrutement', 'list',0 ))->execute();
        $this->dmPage = $dmPage[0]->getName();
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->recrutement = $this->getRecord($query);
  }


}
