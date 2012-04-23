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
        $this->widgetSchema->setHelp('name','Le nom de la constante doit être écrit sans espace et seulement avec des lettres minuscules ou des chiffres<br/><br/>
             Pour utiliser la constante dans un widget ajouter des doubles accolades autour : <b>{{nom_de_la_constante}}</b> ou <b>{{Nom_de_la_constante}}</b> pour avoir une majuscule.
             ');
        $this->validatorSchema['name'] =
                new sfValidatorRegex(array('pattern' => '/^[a-z0-9_]*$/'))
        ;
        $this->widgetSchema['name']->setAttributes(array('class' => 'input_short'));
        
        $this->widgetSchema->setHelp('content', 'Ne mettre une majuscule en début de ligne que si l\'on souhaite toujours voir la majuscule<br/>
CODE PHP : Vous pouvez utiliser du code PHP en l\'encadrant par <b>&lsaquo;?=</b> et <b>?&rsaquo;</b> ou <b>&lsaquo;?php</b> et <b>?&rsaquo;</b>. Exemple: <b>&lsaquo;?php date(\'Y\'); ?&rsaquo;</b>


            ');
    }

}