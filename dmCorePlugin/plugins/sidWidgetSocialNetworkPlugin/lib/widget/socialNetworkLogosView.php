<?php

class socialNetworkLogosView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'twitter',
            'facebook',
            'googleplus'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();

        $arrayLogos = array(
            'twitter' => $vars['twitter'],
            'facebook' => $vars['facebook'],
            'googleplus' => $vars['googleplus']
            );

       return $this->getHelper()->renderPartial('widget', 'socialNetworkLogos', array(
                    'logos' => $arrayLogos,
                ));
    }
}
