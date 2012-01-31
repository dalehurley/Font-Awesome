<?php

class specifiquesBaseEditorialeListAgendaView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'nbArticles',
            'title',
            'lien',
            'length',
            'pageCentrale'
        ));
    }
	
	public function getStylesheets() {
		//on créé un nouveau tableau car c'est un nouveau widget (si c'est une extension utiliser $stylesheets = parent::getStylesheets();)
		$stylesheets = array();
		
		//lien vers le js associé au menu
		$cssLink = sidSPLessCss::getCssPathTemplate(). '/Widgets/SpecifiquesBaseEditorialeListAgenda/SpecifiquesBaseEditorialeListAgenda.css';
		//chargement de la CSS si existante
		if (is_file(sfConfig::get('sf_web_dir') . $cssLink)) $stylesheets[] = $cssLink;
		
		return $stylesheets;
    }

    protected function doRender() {

        $vars = $this->getViewVars();
        // variable pour vérifier qu'il y un rep correspondant au mois en cours
        $noRep = true;
        //$namePage = $this->context->getPage()->getName();
        $recordPage = $this->context->getPage()->getRecordId();
        $nomRubrique = dmDb::table('SidRubrique')->findOneById($recordPage);
        // initialisation des tableaux
        $arrayArticle = array();
        $arrayArticleAgenda = array();
        $arrayGlobalAgendas = array();
        $arrayAgendas = array();
        $articleAgendas = array();

        // Je récupère les données de la rubrique ec_echeancier
        $nameRubrique = dmDb::table('SidRubrique')->findOneByTitle('ec_echeancier');

        // Je récupère son nom de PAGE 
        $rubrique = dmDb::table('DmPage')
                ->createQuery('p')
                ->where('p.module = ? and p.action=? and p.record_id=?', array('rubrique', 'show', $nameRubrique->id))
                ->limit(1)
                ->execute();

        // je cherche le répertoire de Agenda qui correspond au mois en cours
        $month = date('m');
        $year = date('Y');

        // scanne du rep ec_echeancier
        $reps = transfertTools::scandirServeur(sfConfig::get('app_rep-local') . 'ec_echeancier/');

        // mise en tableau des fichiers ( avec le .xml à la fin) classé par ordre ASC des dates indiquée dans le xml Document/Data/Introduction/Heading/Info1
        foreach ($reps as $rep) {
            $filenameAgendas = transfertTools::scandirServeur(sfConfig::get('app_rep-local') . 'ec_echeancier/' . $rep . '/');
            foreach ($filenameAgendas as $filenameAgenda) {
                $xml = new DOMDocument();
                $xmlFile = $xml->load(sfConfig::get('app_rep-local') . 'ec_echeancier/' . $rep . '/' . $filenameAgenda);
                $filename = $xml->getElementsByTagName('Code')->item(0)->nodeValue;
                $date = $xml->getElementsByTagName('Info1')->item(0)->nodeValue;
                $arrayArticleAgenda['filename'] = $filename;
                $arrayArticleAgenda['date'] = $date;
                $arrayArticleAgenda['section'] = $rep;
                $arrayGlobalAgendas[] = $arrayArticleAgenda;
                $arrayArticleAgenda = array();
            }

            foreach ($arrayGlobalAgendas as $key => $value) {
                $updated[$key] = $value['date'];
            }
            if (isset($updated)) {
                array_multisort($updated, SORT_ASC, $arrayGlobalAgendas);
            }

            // je vérifie qu'il existe un rep du mois en cours
            if ($rep == $year . $month) {
                $noRep = false;
            }
        }

        // si le répertoire du mois en cours existe ...
        if ($noRep == false) {
            if ($vars['pageCentrale'] == false) {
                // je cherche les $vars['nbArticles'] à partir du jour actuel pour afficher les échéances les plus proches du jour
                foreach ($arrayGlobalAgendas as $arrayGlobalAgenda) {
                    if ($arrayGlobalAgenda['section'] == $year . $month) {
                        if ($arrayGlobalAgenda['date'] > date('Y-m-d')) {
                            if (count($articleAgendas) < $vars['nbArticles']) {
                                $articleAgendas[] = $arrayGlobalAgenda['filename'];
                            }
                        }
                    }
                }
                // je récupère les objets ayant le même filename dans la Bdd et is_active = true
                foreach ($articleAgendas as $articleAgenda) {

                    $agendas = dmDb::table('SidArticle')->createQuery('a')
                            ->leftJoin('a.Section b')
                            ->where('a.filename LIKE ? and a.is_active = ? and b.is_active = ?', array($articleAgenda, true, true))
                            ->execute();

                    foreach ($agendas as $agenda) {
                        $arrayAgendas[] = $agenda;
                    }
                }

                // Je personnalise le titre du widget
                if ($vars['title'] == '') {
                    $title = $rubrique[0]->name;
                }
                else
                    $title = $vars['title'];

                return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'listAgenda', array(
                            'agendas' => $arrayAgendas, // tableau d'objet
                            'nbArticles' => $vars['nbArticles'],
                            'rubrique' => '/' . $rubrique[0]->slug, // pour aller sur la page de la rubrique
                            'rubriqueTitle' => $title, // pour avoir le TITRE de la page  
                            'lien' => $vars['lien'],
                            'length' => $vars['length']
                        ));
            }
        }
        // si le répertoire n'existe pas ...
        else if ($noRep == true && ($vars['pageCentrale'] == false)) {
            $arrayAgendas = array();
            $title = '';
            return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'listAgenda', array(
                        'agendas' => $arrayAgendas, // tableau d'objet
                    ));
        }


        // modifs pour affichage sur page centrale
        if ($vars['pageCentrale'] == true) {
            // s'affiche uniquement sur la page de l'échéancier
            if ($nomRubrique == 'ec_echeancier') {
                // j'affiche toutes les dates du mois en cours
                foreach ($arrayGlobalAgendas as $arrayGlobalAgenda) {

                    $agendas = dmDb::table('SidArticle')->createQuery('a')
                            ->leftJoin('a.Section b')
                            ->where('a.filename LIKE ? and a.is_active = ? and b.is_active = ?', array($arrayGlobalAgenda['filename'], true, true))
                            ->execute();
                    $arrayAgendas = $agendas;

                    // Je personnalise le titre du widget
                    if ($vars['title'] == '') {
                        $title = $rubrique[0]->name;
                    } else {
                        $title = $vars['title'];
                    }
                }
                 return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'listAgenda', array(
                        'agendas' => $arrayAgendas, // tableau d'objet
                        'nbArticles' => $vars['nbArticles'],
                        'rubrique' => '/' . $rubrique[0]->slug, // pour aller sur la page de la rubrique
                        'rubriqueTitle' => $title, // pour avoir le TITRE de la page  
                        'lien' => $vars['lien'],
                        'length' => $vars['length']
                    ));
            }
           
        }
    }

}
