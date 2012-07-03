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
        
        $this->widgetSchema['m_rubriques_list'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidRubrique',
                    'method' => 'show_list_rubrique',
                    'multiple' => true,
                    'expanded' => true, // pour avoir des cases à cocher
                        //'add_empty' => '-- Sections --'
                ));

        $this->widgetSchema['coord_name_id'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidCoordName',
                    'method' => 'show_list_ville',
                    'multiple' => false,
                    'expanded' => false,
                ));

        $this->widgetSchema['title'] = new sfWidgetFormChoice(array( 'expanded' => true, 'choices' => array('Mr' => 'Monsieur', 'Mrs' => 'Madame', 'Miss' => 'Mademoiselle' ), 'default' => 'Mr'), array('class' => 'civilite'));
        $this->validatorSchema['coord_name_id'] = new sfValidatorDoctrineChoice(array('model' => 'SidCoordName'));
        $this->validatorSchema['m_rubriques_list'] = new sfValidatorDoctrineChoice(array('model' => 'SidRubrique','required' => false,'multiple' =>true));

        $this->widgetSchema->setHelps(array(
            'm_rubriques_list' => 'Vous pouvez lier ce membre de votre equipe à une rubrique (Affichage contextuel).',
//            'text' => '<u>Information</u>: <br /> - pour un retour à la ligne : faire Maj + ENTREE en même temps <br /> - pour un nouveau paragraphe : faire ENTREE',
            'tags_list' => 'Sélectionnez les mots en rapport avec votre lien pour les faire apparaitre dans les pages les plus adéquates de votre site',
        ));
    }

    protected function createMediaFormForImage() {
        
        $form = parent::createMediaFormForImage();
        unset($form['author'], $form['license']);
//        if(!sfContext::getInstance()->getUser()->isSuperAdmin()){
//            unset($form['file']);
//        }
        return $form;
    }

    public function setup() {
        parent::setup();
        // pour rendre l'image non requise
//        if(!sfContext::getInstance()->getUser()->isSuperAdmin()){
//           $this->validatorSchema['image_form']->setOption('required',false);
//        }
//        $this->widgetSchema['m_sections_list'] = new sfWidgetFormDoctrineChoice(array(
//                    'model' => 'SidSection',
//                    'method' => 'show_rubrique_section',
//                    'multiple' => true,
//                    'expanded' => true, // pour avoir des cases à cocher
                        //'add_empty' => '-- Sections --'
            
//                ));
        
    }

}