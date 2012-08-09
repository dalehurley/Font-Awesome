<?php

class specifiquesBaseEditorialeListArticleGridImagesView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'nbLines',
            'nbImagesByLine',
            'containerWidth',
            'interval',
            'animSpeed',
            'maxStep'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();

        // calcul des paramètres


        
        // $actuArticles = Doctrine_Query::create()
        //         ->from('SidArticle a')
        //         ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
        //         ->where('a.is_active = ? and  a.id <> ? and a.section_id = ? '.$andWhere.$andWhereDossier, array(true, $dmPage->record_id,$recordId))
        //         ->orderBy($orderBy)
        //         ->limit($nbArticles)
        //         ->execute();


        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'listArticlesGridImages', array(
                    'articles' => $vars['nbLines'],
                ));


    }

}
