<?php

class sitemapPlanDuSiteView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();
        $menu = $this->context->get('sitemap_menu')->build();
        return $this->getHelper()->renderPartial('sitemap', 'planDuSite', array(
                    'menu' => $menu
                ));
    }

}
