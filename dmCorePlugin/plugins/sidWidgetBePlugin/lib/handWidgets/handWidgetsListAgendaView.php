<?php

class handWidgetsListAgendaView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'nbArticles',
            'title',
            'lien',
            'length'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();
        $arrayArticle = array();
        // Je récupère le nom de la rubrique en Bdd
        $nameRubrique = dmDb::table('SidRubrique')->findOneByTitle('ec_echeancier');
        // Je récupère son nom de PAGE 
        $rubrique = dmDb::table('DmPage') //->findAllBySectionId($vars['section']);
                ->createQuery('p')
                ->where('p.module = ? and action=? and record_id=?', array('rubrique', 'show', $nameRubrique->id))
                ->limit(1)
                ->execute();

        $arrayAgenda = array();
        // je cherche le répertoire de Agenda qui correspond au mois en cours
        $date = date('m');
        $reps = transfertTools::scandirServeur(sfConfig::get('app_rep-local') . 'ec_echeancier/');
        // mise en tableau du nom des fichier ( avec le .xml à la fin)
        foreach ($reps as $rep) {
            if (substr($rep, -2) == $date) {
                $articleAgendas = transfertTools::scandirServeur(sfConfig::get('app_rep-local') . 'ec_echeancier/' . $rep . '/');
            }
        }
        // j'enlève le .xml du nom du fichier et je récupère les objets aynat le même filename dans la Bdd
        foreach ($articleAgendas as $articleAgenda) {
            $filename = substr($articleAgenda, 0, strpos($articleAgenda, '.xml'));
            $agenda = dmDb::table('SidArticle')->findOneByFilename($filename);
            $arrayAgenda[] = $agenda;
        }

        // je trie TOUS les articles de la rubrique par ordre de mise à jour
        foreach ($arrayAgenda as $key => $value) {
            $updated[$key] = $value['updated_at'];
        }
        if (isset($updated))
            array_multisort($updated, SORT_DESC, $arrayAgenda);
        $arrayAgenda = array_slice($arrayAgenda, 0, $vars['nbArticles']);

        // Je personnalise le titre du widget
        if ($vars['title'] == '') {
            $title = $rubrique[0]->name;
        }
        else
            $title = $vars['title'];

        return $this->getHelper()->renderPartial('handWidgets', 'listAgenda', array(
                    'agendas' => $arrayAgenda, // tableau d'objet
                    'nbArticles' => $vars['nbArticles'],
                    'rubrique' => '/' . $rubrique[0]->slug, // pour aller sur la page de la rubrique
                    'rubriqueTitle' => $title, // pour avoir le TITRE de la page  
                    'lien' => $vars['lien'],
                    'length' => $vars['length']
                ));
    }

}
