<?php

class handWidgetsActuArticlesContextuelView extends dmWidgetPluginView {

    public function configure() {
	parent::configure();

	$this->addRequiredVar(array(
	    'titreBloc',
	    'titreLien',	    
	    'nbArticles'
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

	switch ($dmPage->module.'/'.$dmPage->action) {
	    case 'section/show':
                            // il faut que je récupère l'id de la rubrique de la section
                            // je récupère donc l'ancestor de la page courante pour extraire le record_id de ce dernier afin de retrouver la rubrique
                            $ancestors = $this->context->getPage()->getNode()->getAncestors();
                            $recordId = $ancestors[count($ancestors)-1]->getRecordId();
                            $actuArticles = Doctrine_Query::create()->from('SidActuArticle a')
                                    ->leftJoin('a.SidActuArticleSidRubrique sas')
                                    ->leftJoin('sas.SidRubrique s')
                                    ->where('s.id = ? ', array($recordId))
                                    ->andWhere('a.is_active = ?', true)
                                    ->limit($vars['nbArticles'])
                                    ->execute();
                            foreach ($actuArticles as $actuArticle) { // on stock les NB actu article 
                                $arrayArticle[$actuArticle->id] = $actuArticle;
                            }
                            break;
	    case 'rubrique/show':
		// toutes les sections de la rubrique contextuelle
		$rubriques = dmDb::table('SidRubrique')->findById($dmPage->record_id);
		// on parcourt les sections pour extraire les articles
		foreach ($rubriques as $rubrique) {
		    $actuArticles = Doctrine_Query::create()->from('SidActuArticle a')
			    ->leftJoin('a.SidActuArticleSidRubrique sas')
			    ->leftJoin('sas.SidRubrique s')
			    ->where('s.id = ? ', array($rubrique->id))
			    ->andWhere('a.is_active = ?', true)
			    ->limit($vars['nbArticles'])
			    ->execute();
		    foreach ($actuArticles as $actuArticle) { // on stock les NB actu article 
			$arrayArticle[$actuArticle->id] = $actuArticle;
		    }
		}
		break;
	    case 'main/root':
		// dans la page d'accueil, on renvoie l'actu article mis en avant en home
		$actuArticles = Doctrine_Query::create()->from('SidActuArticle a')
			->where('a.on_home = ?', true)
			->andWhere('a.is_active = ?', true)
			->orderBy('a.updated_at DESC')
			->limit($vars['nbArticles'])
			->execute();
		foreach ($actuArticles as $actuArticle) { // on stock les NB actu article 
		    $arrayArticle[$actuArticle->id] = $actuArticle;
		}
		break;
	    case 'sidActuArticle/show':
		// dans la page d'affichage des actu article on n'affiche pas l'article qui est affiché dans le page.content
		$actuArticles = Doctrine_Query::create()->from('SidActuArticle a')
			->where('a.is_active = ?', true)
			->andWhere('a.id <> ?', $dmPage->record_id)
			->orderBy('a.updated_at DESC')
			->limit($vars['nbArticles'])
			->execute();
		foreach ($actuArticles as $actuArticle) { // on stock les NB actu article 
		    $arrayArticle[$actuArticle->id] = $actuArticle;
		}
		break;		
	    default:
		// hors context, on renvoie la dernière actu mise à jour
		$actuArticles = Doctrine_Query::create()->from('SidActuArticle a')
			->Where('a.is_active = ?', true)
			->orderBy('a.updated_at DESC')
			->limit($vars['nbArticles'])
			->execute();
		foreach ($actuArticles as $actuArticle) { // on stock les NB actu article 
		    $arrayArticle[$actuArticle->id] = $actuArticle;
		}
	}

	return $this->getHelper()->renderPartial('handWidgets', 'actuArticlesContextuel', array(
	    'articles' => $arrayArticle,
	    'nbArticles' => $vars['nbArticles'],
	    'titreBloc' => $vars['titreBloc'],
	    'titreLien' => $vars['titreLien']
	));
    }

}
