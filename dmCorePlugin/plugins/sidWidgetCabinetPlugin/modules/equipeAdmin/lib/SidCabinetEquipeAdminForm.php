<?php

/**
 * equipeAdmin admin form
 *
 * @package    sitev3-demo3
 * @subpackage equipeAdmin
 * @author     Your name here
 */
class SidCabinetEquipeAdminForm extends BaseSidCabinetEquipeForm {

    public function configure() {
	parent::configure();
    }
    protected function createMediaFormForImage() {
        $form = parent::createMediaFormForImage();
        unset($form['legend'], $form['author'], $form['license']);
        return $form;
    }
    public function setup() {
	parent::setup();
	$this->widgetSchema['m_sections_list'] = new sfWidgetFormDoctrineChoice(array(
		    'model' => 'SidSection',
		    'method' => 'show_rubrique_section',
		    'multiple' => true,
		    'expanded' => true, // pour avoir des cases à cocher
			//'add_empty' => '-- Sections --'
		));

	$this->widgetSchema->setHelps(array(
	    'm_sections_list' => 'Vous pouvez lier ce membre de votre equipe à une rubrique/section (Affichage contextuel).',
            'text' => '<u>Information</u>: <br /> - pour un retour à la ligne : faire Maj + ENTREE en même temps <br /> - pour un nouveau paragraphe : faire ENTREE',
            'tags_list' => 'Sélectionnez les mots en rapport avec votre lien pour les faire apparaitre dans les pages les plus adéquates de votre site',
	));
                    $this->widgetSchema['m_rubriques_list'] = new sfWidgetFormDoctrineChoice(array(
		    'model' => 'SidRubrique',
		    'method' => 'show_list_rubrique',
		    'multiple' => true,
		    'expanded' => true,  // pour avoir des cases à cocher
		    //'add_empty' => '-- Sections --'
		));
    }

}