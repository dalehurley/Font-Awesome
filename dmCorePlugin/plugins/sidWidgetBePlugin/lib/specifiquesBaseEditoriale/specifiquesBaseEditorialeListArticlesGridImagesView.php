<?php

class specifiquesBaseEditorialeListArticleGridImagesView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'options'
        ));

        $this->addJavascript(array(
       //     'sidWidgetBePlugin.transit',
            'sidWidgetBePlugin.transitmin',
            'sidWidgetBePlugin.gridrotator'
            )
        );
        $this->addStylesheet(array('sidWidgetBePlugin.style'));
    }

    protected function doRender() {
        $vars = $this->getViewVars();

        // calcul du nombres d'articles à récupérer en fonction du max d'images à afficher dans les différentes résolutions
        $options = $vars['options'];

        $titreBloc = $vars['titreBloc'];

        $arrayOptions = json_decode($options, true);

        $maxNbImages = '1';
        if ($arrayOptions){ // json well formated
            foreach ($arrayOptions as $key => $value) {
                if (substr($key, 0,1) == 'w'){
                    $maxNbImagesTmp = $value['columns']*$value['rows'];
                    if ($maxNbImagesTmp > $maxNbImages) $maxNbImages = $maxNbImagesTmp;
                }
            }
        } else {
            $maxNbImages = '30';
        }
        
        $articles = Doctrine_Query::create()
                ->from('SidArticle a')
                ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                ->where('a.is_dossier = ? ', array(false))
                ->orderBy('aTranslation.updated_at DESC')  // les derniers articles
                ->groupBy('a.filename')
                ->limit($maxNbImages*4)  // on récupère le quadruple du nombre d'images max à afficher: le remplacement d'une image par une autre est assuré
                ->execute();

        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'listArticlesGridImages', array(
                    'options' => $options,
                    'articles' => $articles,
                    'maxNbImages' => $maxNbImages,
                    'titreBloc' => $titreBloc
                ));


    }

}
