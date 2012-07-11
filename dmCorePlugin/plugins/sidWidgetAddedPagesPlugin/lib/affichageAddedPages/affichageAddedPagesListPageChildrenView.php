<?php
class affichageAddedPagesListPageChildrenView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
//            'recordId',
            'withImage',
            'withDate',
            'withResume'
        ));
    }
    protected function doRender() {
        $vars = $this->getViewVars();
        $nbArticles = ($vars['nbArticles'] == 0) ? '' : $vars['nbArticles'];
        $length = ($vars['length'] == 0) ? '' : $vars['length'];
        $sidAddedPages = dmDb::table('SidAddedPages') 
                ->createQuery('a')
                ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                ->where('a.root_id = ? AND a.level = ? AND a.lft > ? AND a.rgt < ?',array($this->context->getPage()->getRecord()->root_id,$this->context->getPage()->getRecord()->level+1,$this->context->getPage()->getRecord()->lft, $this->context->getPage()->getRecord()->rgt))
                ->limit($nbArticles)
                ->execute();
        // récupération des dimensions des images pour l'affichage
        $theme = array();
        // "renommage du site_theme" pour lecture app
        foreach(sfConfig::get('app_site-theme_rename') as $key=>$nameTheme){
            if($nameTheme == dmConfig::get('site_theme')) {
                // récupération en tableau des dimensions selon le theme du site
                $theme = sfConfig::get('app_added-pages_'.$key);
                break;
            }
        }
        return $this->getHelper()->renderPartial('affichageAddedPages', 'listPageChildren', array(
                    'sidAddedPages' => $sidAddedPages,
                    'withImage'     => $vars['withImage'],
                    'widthImage'    => $vars['widthImage'],
                    'heightImage'    => $vars['heightImage'],
                    'withDate'      => $vars['withDate'],
                    'withResume'    => $vars['withResume'],
                    'theme'         => $theme,
                    'titreBloc'     => $vars['titreBloc'],
                    'length'        => $length,
                    'nbImages'      => $vars['nbImages']
                ));
    }


}