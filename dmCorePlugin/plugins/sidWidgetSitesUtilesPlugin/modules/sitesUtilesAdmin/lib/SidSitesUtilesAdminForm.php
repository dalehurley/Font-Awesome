<?php

/**
 * sitesUtilesAdmin admin form
 *
 * @package    sitev3-demo1
 * @subpackage sitesUtilesAdmin
 * @author     Your name here
 */
class SidSitesUtilesAdminForm extends BaseSidSitesUtilesForm {

    public function configure() {
        parent::configure();
    }

    protected function createMediaFormForImageId() {
        $form = parent::createMediaFormForImageId();
        unset($form['author'], $form['license']);
        return $form;
    }

    public function setup() {
        parent::setup();

        $this->widgetSchema->setHelps(array(
            'groupe_sites_utiles_id' => 'Sélectionnez un groupe afin d\'affiner votre classement de sites utiles',
//            'description' => '<u>Information</u>: <br /> - pour un retour à la ligne : faire Maj + ENTREE en même temps <br /> - pour un nouveau paragraphe : faire ENTREE',
            'tags_list' => 'Sélectionnez les mots en rapport avec votre lien pour les faire apparaitre dans les pages les plus adéquates de votre site',
            'url' => 'Noter "http://" avant l\'adresse internet de votre lien'));
    }

}