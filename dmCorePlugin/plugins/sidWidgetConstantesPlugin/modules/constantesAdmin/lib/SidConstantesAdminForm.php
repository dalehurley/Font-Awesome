<?php

/**
 * constantesAdmin admin form
 *
 * @package    sitev3-demo1
 * @subpackage constantesAdmin
 * @author     Your name here
 */
class SidConstantesAdminForm extends BaseSidConstantesForm {

    public function configure() {
        parent::configure();
        $this->widgetSchema->setHelp('name', '<b>Le nom de la constante doit être écrit sans espace et seulement avec des lettres minuscules ou des chiffres</b>
             <hr>
             Pour utiliser la constante dans un widget ajouter des doubles accolades autour : {{nom_de_la_constante}} ou {{Nom_de_la_constante}} pour avoir une majuscule.
             ');
        $this->validatorSchema['name'] =
                new sfValidatorRegex(array('pattern' => '/^[a-z0-9_]*$/'))
        ;

        $this->widgetSchema->setHelp('content', '<b>Ne mettre une majuscule en début de ligne que si l\'on souhaite toujours voir la majuscule</b>');
    }

}