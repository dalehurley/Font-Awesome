<?php

class handWidgetsEquipesContextuelView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'titreLien',	    
            'nb'
        ));
    }

    /**
     * On affiche NB articles Actu selon 3 types:
     * 1) il est sur une dmPage automatique de type "Rubrique" : on affiche les articles qui sont dans les sections de cette rubrique
     * 2) il est sur une dmPage automatique de type "Section" : on affiche les articles qui sont dans cette section
     * 3) il est sur une page ni Rubrique ni Section, automatique ou pas : on affiche les articles de la rubrique choisie par défaut
     *  
     */
    protected function doRender() {
        $vars = $this->getViewVars();
        $arrayArticle = array();

	$idDmPage = sfContext::getInstance()->getPage()->id;
	$dmPage = dmDb::table('DmPage')->findOneById($idDmPage);
	//$arrayArticle[] = $dmPage->module.' - '.$dmPage->action.' - '.$dmPage->record_id;
	
	switch ($dmPage->module) {
	    case 'section':
		$actuArticles = dmDb::table('SidCabinetEquipe')
			->createQuery('p')
			->leftJoin('p.SidCabinetEquipeSidSection sas')
			->leftJoin('sas.SidSection s')
			->where('s.id = ? ', array($dmPage->record_id))
			->limit($vars['nb'])
			->execute();
		foreach($actuArticles as $actuArticle){ // on stock les NB actu article 
		    $arrayArticle[] = $actuArticle;
		}
		break;
	    case 'rubrique':
		// toutes les sections de la rubrique contextuelle
		$sections = dmDb::table('SidSection')->findByRubriqueId($dmPage->record_id);
		// on parcourt les sections pour extraire les articles
		foreach ($sections as $section) {
		    $actuArticles = dmDb::table('SidCabinetEquipe')
			    ->createQuery('p')
			    ->leftJoin('p.SidCabinetEquipeSidSection sas')
			    ->leftJoin('sas.SidSection s')
			    ->where('s.id = ? ', array($section->id))
			    ->limit($vars['nb'])
			    ->execute();
		    foreach ($actuArticles as $actuArticle) { // on stock les NB actu article 
			$arrayArticle[$actuArticle->id] = $actuArticle;

		    }
		}
		break;
	    default:
		// hors context, on ne renvoie aucun article
	}
        
        $pageEquipe = dmDb::table('dmPage')->createQuery('a')->where('a.module = ? and a.action = ? and a.record_id = ?', array('pageCabinet', 'equipe',0 ))->execute();
        
        return $this->getHelper()->renderPartial('handWidgets', 'equipesContextuel', array(
                    'equipes' => $arrayArticle,
                    'nb' => $vars['nb'],
                    'titreBloc' => $vars['titreBloc'],
                    'titreLien' => $vars['titreLien'],
                    'pageEquipe' => $pageEquipe[0]
                ));
    }

}
