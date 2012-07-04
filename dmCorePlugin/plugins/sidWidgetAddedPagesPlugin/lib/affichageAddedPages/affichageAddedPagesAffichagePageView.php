<?php
class affichageAddedPagesAffichagePageView extends dmWidgetPluginView {

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
        if ($vars['recordId'] == '') { // donc page contextuel
            $record = sfcontext::getInstance()->getPage() ? sfcontext::getInstance()->getPage()->getRecord() : false;
            $recordId = $record->id;
        } else {
            $recordId = $vars['recordId'];
        }
        $sidAddedPages = dmDb::table('SidAddedPages') 
                ->createQuery('a')
                ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                ->where('a.id = ? ', $recordId)
                ->orderBy('aTranslation.updated_at DESC')->limit(1)->execute();
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
        return $this->getHelper()->renderPartial('affichageAddedPages', 'affichagePage', array(
                    'sidAddedPages' => $sidAddedPages[0],
                    'withImage'     => $vars['withImage'],
                    'widthImage'    => $vars['widthImage'],
                    'heightImage'    => $vars['heightImage'],
                    'withDate'      => $vars['withDate'],
                    'withResume'    => $vars['withResume'],
                    'theme'         => $theme
                ));
    }


}