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
            $arrayAgenda = array();
            $articleAgendas = array();

            // Je récupère les données de la rubrique ec_echeancier
            $nameRubrique = dmDb::table('SidRubrique')->findOneByTitle('ec_echeancier');

            // Je récupère son nom de PAGE 
            $rubrique = dmDb::table('DmPage')
                    ->createQuery('p')
                    ->where('p.module = ? and action=? and record_id=?', array('rubrique', 'show', $nameRubrique->id))
                    ->limit(1)
                    ->execute();


            // je cherche le répertoire de Agenda qui correspond au mois en cours
            $month = date('m');
            $year = date('Y');
            $reps = transfertTools::scandirServeur(sfConfig::get('app_rep-local') . 'ec_echeancier/');
            // mise en tableau des fichiers ( avec le .xml à la fin) classé par ordre ASC des dates indiquée dans le xml Document/Data/Introduction/Heading/Info1
            foreach ($reps as $rep) {
                if ($rep == $year . $month) {
                    $filenameAgendas = transfertTools::scandirServeur(sfConfig::get('app_rep-local') . 'ec_echeancier/' . $rep . '/');
                    foreach ($filenameAgendas as $filenameAgenda) {
                        $xml = new DOMDocument();
                        $xmlFile = $xml->load(sfConfig::get('app_rep-local') . 'ec_echeancier/' . $rep . '/' . $filenameAgenda);
                        $filename = $xml->getElementsByTagName('Code')->item(0)->nodeValue;
                        $date = $xml->getElementsByTagName('Info1')->item(0)->nodeValue;
                        $arrayArticleAgenda['filename'] = $filename;
                        $arrayArticleAgenda['date'] = $date;
                        $arrayGlobalAgendas[] = $arrayArticleAgenda;
                        $arrayArticleAgenda = array();
                    }
                    foreach ($arrayGlobalAgendas as $key => $value) {
                        $updated[$key] = $value['date'];
                    }
                    if (isset($updated)) {
                        array_multisort($updated, SORT_ASC, $arrayGlobalAgendas);
                    }

                    $noRep = false;
                }
            }

            if ($noRep == false) {

                if ($vars['pageCentrale'] == false) {
                    // je cherche les $vars['nbArticles'] à partir du jour actuel pour afficher les échéances les plus proches du jour
                    foreach ($arrayGlobalAgendas as $arrayGlobalAgenda) {
                        if ($arrayGlobalAgenda['date'] > date('Y-m-d')) {
                            if (count($articleAgendas) < $vars['nbArticles']) {
                                $articleAgendas[] = $arrayGlobalAgenda['filename'];
                            }
                        }
                    }

                    // je récupère les objets ayant le même filename dans la Bdd et is_active = true
                    foreach ($articleAgendas as $articleAgenda) {

                        $agendas = dmDb::table('SidArticle')->createQuery()
                                ->where('filename LIKE ? and is_active = ?', array($articleAgenda, true))
                                ->execute();

                        foreach ($agendas as $agenda) {
                            $arrayAgenda[] = $agenda;
                        }
                    }

                    // Je personnalise le titre du widget
                    if ($vars['title'] == '') {
                        $title = $rubrique[0]->name;
                    }
                    else
                        $title = $vars['title'];
                }

                else
                // modifs pour affichage sur page centrale
                if ($vars['pageCentrale'] == true) {
                    // s'affiche uniquement sur la page de l'échéancier
                    if ($nomRubrique == 'ec_echeancier') {
                    // j'affiche toutes les dates du mois en cours
                    foreach ($arrayGlobalAgendas as $arrayGlobalAgenda) {
                        $agenda = dmDb::table('SidArticle')->findOneByFilename($arrayGlobalAgenda['filename']);
                        $arrayAgenda[] = $agenda;
                    }
                    $title = $rubrique[0]->name;
                    $vars['lien'] = "";
                    }
                    else {
                        $title = "";
                        $vars['lien'] = "";}
                    
                }
                return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'listAgenda', array(
                            'agendas' => $arrayAgenda, // tableau d'objet
                            'nbArticles' => $vars['nbArticles'],
                            'rubrique' => '/' . $rubrique[0]->slug, // pour aller sur la page de la rubrique
                            'rubriqueTitle' => $title, // pour avoir le TITRE de la page  
                            'lien' => $vars['lien'],
                            'length' => $vars['length']
                        ));
                
            } else
            if ($noRep == true) {
                $arrayAgenda = array();
                return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'listAgenda', array(
                            'agendas' => $arrayAgenda, // tableau d'objet
                        ));
            }
        }
    

}
