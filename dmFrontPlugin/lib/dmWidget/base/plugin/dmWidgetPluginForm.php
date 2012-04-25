<?php

abstract class dmWidgetPluginForm extends dmWidgetBaseForm
{
 public function configure()
  {

    $this->widgetSchema['widthImage'] = new sfWidgetFormInputText(array('label' => 'Largeur en px'));
    $this->validatorSchema['widthImage'] = new dmValidatorCssSize(array(
                  'required' => false
                ));

    $this->widgetSchema['heightImage'] = new sfWidgetFormInputText(array( 'label' => 'Hauteur en px'));
    $this->validatorSchema['heightImage'] = new dmValidatorCssSize(array(
                  'required' => false
                 ));

    $this->widgetSchema['length'] = new sfWidgetFormInputText(array('default' => 0, 'label' => 'Longueur du texte à afficher'));
    $this->validatorSchema['length'] = new sfValidatorInteger(array(
                'required' => false
                ));
        
    $this->widgetSchema['withImage'] = new sfWidgetFormInputCheckbox(array('default'=> true, 'label' => 'Image visible dans les articles'));
    $this->validatorSchema['withImage']  = new sfValidatorBoolean();
    
    
    $this->widgetSchema['nbArticles'] = new sfWidgetFormInputText(array('label' => 'Nbre d\'article', 'default' => 1));
    $this->validatorSchema['nbArticles'] = new sfValidatorInteger(array(
                'required' => false,
                ));
    
    $this->widgetSchema['lien'] = new sfWidgetFormInputText(array());
    $this->validatorSchema['lien'] = new sfValidatorString(array(
                'required' => false
                ));
    
    $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText();
    $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                'required' => false
                ));
        
    $this->widgetSchema['chapo'] = new sfWidgetFormSelectRadio(array('choices' => array('chapeau','texte'),'default'=>1));
    $this->validatorSchema['chapo']  = new sfValidatorChoice(array('choices' =>array(0,1),'multiple' => false,'required' => false));
    
    $this->widgetSchema->setHelps(array(
                'widthImage' => 'Largeur de l\'image',
                'heightImage' => 'Hauteur de l\'image (inutilisé pour l\'instant en attente d\'étude de dmMédia)',
                'nbArticles' => 'Le nombre maximum d\'articles affichés - 0 pour afficher la liste complète.',
                'length' => 'Longueur du texte avant de le tronquer',
                'withImage' => 'affiche ou pas la photo',
//                'lien' => 'lien vers la page parent de l\'article',
                'titreBloc' => 'Le titre optionnel du bloc.',
                'chapo' => 'Choisir si on veut afficher le résumé de la page ou le contenu entier de la page'
                
            ));
        parent::configure();
  }
}