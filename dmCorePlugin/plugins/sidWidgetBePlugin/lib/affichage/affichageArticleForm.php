<?php

class affichageArticleForm extends dmWidgetPluginForm {

    public function configure() {
        /*
         * Record id
         */
$this->widgetSchema['recordId'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidArticle',
                    'add_empty' => sprintf('(%s) %s', $this->__('contextual'), $this->getAutoRecord()->__toString())  // affichage du libellé de l'objet
                ));
        $this->widgetSchema['recordId']->setLabel('Article');

        $this->validatorSchema['recordId'] = new sfValidatorDoctrineChoice(array(
                    'model' => 'SidArticle',
                    'required' => false
                ));

//    $this->setDefaults($this->getDefaultsFromLastUpdated());
//
//    if (!$this->allowAutoRecordId() && !$this->getDefault('recordId'))
//    {
//      $this->setDefault('recordId', dmArray::first(array_keys($this->widgetSchema['recordId']->getChoices())));
//    }

        parent::configure();
    }

    /*
     * return record linked to the page
     */

    protected function getAutoRecord() {
        $record = sfcontext::getInstance()->getPage() ? sfcontext::getInstance()->getPage()->getRecord() : false;
        // return ancestor of 
        return $record ? $record->getAncestorRecord('SidArticle') : false;
    }

    public function getStylesheets() {
        return array(
            'lib.ui-tabs'
        );
    }

    public function getJavascripts() {
        return array(
            'lib.ui-tabs',
            'core.tabForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('affichage', 'articleForm', array(
                    'form' => $this,
                    'id' => 'sid_widget_affichage_article' . $this->dmWidget->get('id')
                ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}