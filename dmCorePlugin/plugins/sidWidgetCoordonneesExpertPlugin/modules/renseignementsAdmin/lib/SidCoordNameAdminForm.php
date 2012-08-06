<?php

/**
 * renseignementsAdmin admin form
 *
 * @package    sitev3-demo3
 * @subpackage renseignementsAdmin
 * @author     Your name here
 */
class SidCoordNameAdminForm extends BaseSidCoordNameForm {

    public function configure() {
        parent::configure();
        $this->widgetSchema->setHelps(array(
            'adresse_google'=> 'A remplir si la carte Google n\'affiche pas la bonne adresse',
            'file_form' => 'Vous pouvez insérer un fichier des formats suivants : .pdf, doc, .docx, .xls, .xlsx, .odt, .ods, .zip.',
            'title_file' => 'Nouveau nom de votre fichier',));
        //$this->validatorSchema['code_postal'] = new sfValidatorRegex(array('pattern'=>'#^[0-9]{4,5}$#'),array('invalid'=>'The zip code must have min 5 numbers'))
;    }

    protected function createMediaFormForImage() {
        $form = parent::createMediaFormForImage();
        unset($form['author'], $form['license']);
        return $form;
    }

    protected function createMediaFormForFile() {
        $form = parent::createMediaFormForFile();
        unset($form['legend'], $form['author'], $form['license']);

        // choose mime types allowed
        $form->setMimeTypeWhiteList(array(
            'application/pdf', // .pdf
            'application/msword', // .doc
            'application/vnd.oasis.opendocument.text', // .odt
            'web_images',
            'application/zip', // .zip
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
            'application/vnd.ms-excel', // .xls
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
            'application/vnd.oasis.opendocument.spreadsheet', // .ods
        ));
        return $form;
    }

}