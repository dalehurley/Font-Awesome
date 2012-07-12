<?php

/**
 * contactFieldAdmin admin form
 *
 * @package    sitediem
 * @subpackage contactFieldAdmin
 * @author     Your name here
 */
class SidContactFieldAdminForm extends BaseSidContactFieldForm
{
  public function configure()
  {
    parent::configure();

    $widgetTypeChoices = array(
        'sfWidgetFormInputText' => 'sfWidgetFormInputText' ,
        'sfWidgetFormTextarea' => 'sfWidgetFormTextarea',
        'sfWidgetFormChoice' => 'sfWidgetFormChoice',
        'sfWidgetFormDate' => 'sfWidgetFormDate',
        'sfWidgetFormDoctrineChoice' => 'sfWidgetFormDoctrineChoice'
    );
    $validatorTypeChoices = array(
        'sfValidatorString' => 'sfValidatorString' ,
        'sfValidatorChoice' => 'sfValidatorChoice',
        'sfValidatorRegex' => 'sfValidatorRegex',
        'sfValidatorDate' => 'sfValidatorDate',
        'sfValidatorEmail' => 'sfValidatorEmail',
        'sfValidatorUrl' => 'sfValidatorUrl',
        'sfValidatorInteger' => 'sfValidatorInteger',
        'sfValidatorNumber' => 'sfValidatorNumber',
        'sfValidatorDoctrineChoice' => 'sfValidatorDoctrineChoice'
    );

    // helps
    $widget_options_help = '
<h2>Les options des widgets possibles</h2>
<b>Il faut saisir les options dans un tableau au format json. Exemple : <br/>
<i>{"type" : "text", "value" : "valeur par défaut"}</i><br/>
Pour saisir une option qui demande un tableau, comme "choices" du sfWidgetFormChoices, il suffit de saisir un tableau pour la valeur de l\'option, comme suit:<br/>
<i>{"choices" : {"choix1" : "Je choisis ça", "choix2" : "Je choisis plutôt ça"}}</i><br/>
<br/></b>

<u>sfWidgetFormInputText:</u><br/>
<b>type</b>: text par défaut<br/>
<b>is_hidden</b>: false par défaut<br/>
<br/>

<u>sfWidgetFormTextarea:</u><br/>
No option.<br/>
<br/>

<u>sfWidgetFormChoice:</u><br/>
<b>choices</b>:  Un tableau des choix, exemple <i>{"choices" : {choix1":"Je choisis ça", "choix2":"Je choisis plutôt ça"}}</i><br/>
<b>multiple</b>: Permet le choix multiple, exemple <i>{"multiple" : true}</i> <br/>
<b>expanded</b>: Affichage d\'un widget étendu, exemple <i>{"expanded" : false}</i><br/>
Si expanded = false, alors le widget est un select<br/>
Si expanded = true et multiple = false, on aura une liste de boutons radio<br/>
Si expanded = true et multiple = true, on aura une liste de checkboxes<br/>
<br/>

<u>sfWidgetFormDate:</u><br/>
<b>format</b>:       The date format string (%month%/%day%/%year% by default)<br/>
<b>years</b>:        An array of years for the year select tag (optional). Be careful that the keys must be the years, and the values what will be displayed to the user.<br/>
<b>months</b>:       An array of months for the month select tag (optional).<br/>
<b>days</b>:         An array of days for the day select tag (optional).<br/>
<b>can_be_empty</b>: Whether the widget accept an empty value (true by default).<br/>
<b>empty_values</b>: An array of values to use for the empty value (empty string for year, month, and day by default).<br/>
<br/>

<u>sfWidgetFormDoctrineChoice:</u><br/>
<b>model</b>:        The model class (required)<br/>
<b>add_empty</b>:    Whether to add a first empty value or not (false by default). If the option is not a Boolean, the value will be used as the text value<br/>
<b>method</b>:       The method to use to display object values (__toString by default)<br/>
<b>key_method</b>:   The method to use to display the object keys (getPrimaryKey by default)<br/>
<b>order_by</b>:     An array composed of two fields:<br/>
                   * The column to order by the results (must be in the PhpName format)<br/>
                   * asc or desc<br/>
<b>query</b>:        A query to use when retrieving objects<br/>
<b>multiple</b>:     true if the select tag must allow multiple selections<br/>
<b>table_method</b>: A method to return either a query, collection or single object<br/>
<br/>
<br/>

<h2>Exemples d\'utilisation spécifique:</h2>
<b><u>Mettre la liste des destinataires</u></b><br/>
<b>Mettre "Destinataire" dans le nom du champ</b> (l\'ajout du champ est automatique, lié au modèle SidCabinetEquipe pour les objets actifs et ayant un email)
<br/>
<h3>Pour accèder à un destinataire précis (objet de SidCabinetEquipe) il faut appeler la page comme suit:</h3>
http://www.example.com/contact?dest=8  (où SidCabinetEquipe.id = 8)<br/>
<br/>
';

    $widget_attributes_help = '
<h2>Les attributs des widgets possibles</h2>
<u>Idem pour tous les sfWidgets:</u><br/>
Ajout d\'attributs HTML, exemple : <i>{"style" : "width: 290px;color: red", "value" : "valeur par défaut" , "rows" : "4", "cols" : "30"}</i>
';
    
    $validator_options_help = '
<h2>Les options des validateurs possibles</h2>
<u>sfValidatorString:</u><br/>
<b>max_length</b>: The maximum length of the string<br/>
<b>min_length</b>: The minimum length of the string<br/>
<br/>

<u>sfValidatorChoice:</u><br/>
<b>choices</b>:  An array of expected values (required)<br/>
<b>multiple</b>: true if the select tag must allow multiple selections<br/>
<b>min</b>:      The minimum number of values that need to be selected (this option is only active if multiple is true)<br/>
<b>max</b>:      The maximum number of values that need to be selected (this option is only active if multiple is true)<br/>
<br/>

<u>sfValidatorRegex:</u><br/>
<b>pattern</b>:    A regex pattern compatible with PCRE or {@link sfCallable} that returns one (required)<br/>
<b>must_match</b>: Whether the regex must match or not (true by default)<br/>
<br/>

<u>sfValidatorDate:</u><br/>
<b>date_format</b>:             A regular expression that dates must match. Note that the regular expression must use named subpatterns like (?P<year>). Working example: ~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~<br/>
<b>with_time</b>:               true if the validator must return a time, false otherwise<br/>
<b>date_output</b>:             The format to use when returning a date (default to Y-m-d)<br/>
<b>datetime_output</b>:         The format to use when returning a date with time (default to Y-m-d H:i:s)<br/>
<b>date_format_error</b>:       The date format to use when displaying an error for a bad_format error (use date_format if not provided)<br/>
<b>max</b>:                     The maximum date allowed (as a timestamp or accecpted date() format)<br/>
<b>min</b>:                     The minimum date allowed (as a timestamp or accecpted date() format)<br/>
<b>date_format_range_error</b>: The date format to use when displaying an error for min/max (default to d/m/Y H:i:s)<br/>
<br/>

<u>sfValidatorEmail:</u><br/>
Same options as sfValidatorRegex. CONSTANTE in sfWidgetEmail is REGEX_EMAIL = \'/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i\' <br/>
<br/>

<u>sfValidatorUrl:</u><br/>
<b>protocols</b>: An array of acceptable URL protocols (http, https, ftp and ftps by default)<br/>
<br/>

<u>sfValidatorInteger:</u><br/>
<b>max</b>: The maximum value allowed<br/>
<b>min</b>: The minimum value allowed<br/>
<br/>

<u>sfValidatorNumber:</u><br/>
<b>max</b>: The maximum value allowed<br/>
<b>min</b>: The minimum value allowed<br/>
<br/>

<u>sfValidatorDoctrineChoice:</u><br/>
<b>model</b>:      The model class (required)<br/>
<b>query</b>:      A query to use when retrieving objects<br/>
<b>column</b>:     The column name (null by default which means we use the primary key). Must be in field name format<br/>
<b>multiple</b>:   true if the select tag must allow multiple selections<br/>
<b>min</b>:        The minimum number of values that need to be selected (this option is only active if multiple is true)<br/>
<b>max</b>:        The maximum number of values that need to be selected (this option is only active if multiple is true)<br/>
<br/>

';

    $validator_messages_help = '
<h2>Les messages des validateurs possibles</h2>
<u>Pour tous les sfWidgets:</u><br/>
<b>invalid</b>: Default value : Invalid.<br/>
<b>required</b>: Default value : Required.<br/>
<br/>

<u>sfValidatorString:</u><br/>
<b>max_length</b>: Default value : "%value%" is too long (%max_length% characters max).<br/>
<b>min_length</b>: Default value : "%value%" is too short (%min_length% characters min).<br/>
<br/>

<u>sfValidatorChoice:</u><br/>
<b>min</b>: Default value : At least %min% values must be selected (%count% values selected).<br/>
<b>max</b>: Default value : At most %max% values must be selected (%count% values selected).<br/>
<br/>

<u>sfValidatorDate:</u><br/>
<b>bad_format</b>: Default value : "%value%" does not match the date format (%date_format%).<br/>
<b>max</b>: Default value : The date must be before %max%.<br/>
<b>min</b>: Default value : The date must be after %min%.<br/>
<br/>

<u>sfValidatorInteger:</u><br/>
<b>max</b>: Default value : "%value%" must be at most %max%.<br/>
<b>min</b>: Default value : "%value%" must be at least %min%.<br/>
<b>invalid</b>: Default value : "%value%" is not an integer.<br/>
<br/>

<u>sfValidatorNumber:</u><br/>
<b>max</b>: Default value : "%value%" must be at most %max%.<br/>
<b>min</b>: Default value : "%value%" must be at least %min%.<br/>
<b>invalid</b>: Default value : "%value%" is not an number.<br/>
<br/>

<u>sfValidatorDoctrineChoice:</u><br/>
<b>min</b>: Default value : At least %min% values must be selected (%count% values selected).<br/>
<b>max</b>: Default value : At most %max% values must be selected (%count% values selected).<br/>
<br/>

';    


    $this->widgetSchema['widget_type'] = new sfWidgetFormChoice(array(
      'choices' => $widgetTypeChoices
    ));
    $this->validatorSchema['widget_type'] = new sfValidatorChoice(array(
      'choices' => array_keys($widgetTypeChoices),
      'required' => true
    ));

    $this->widgetSchema['validator_type'] = new sfWidgetFormChoice(array(
      'choices' => $validatorTypeChoices
    ));
    $this->validatorSchema['validator_type'] = new sfValidatorChoice(array(
      'choices' => array_keys($validatorTypeChoices),
      'required' => true
    ));

    // widget et validator fields
    $this->widgetSchema['widget_options'] = new sfWidgetFormTextarea(array(
    ));
    $this->validatorSchema['widget_options'] = new sfValidatorString(array(
      'required' => false
    ));
    $this->widgetSchema['widget_attributes'] = new sfWidgetFormTextarea(array(
    ));
    $this->validatorSchema['widget_attributes'] = new sfValidatorString(array(
      'required' => false
    ));
    $this->widgetSchema['validator_options'] = new sfWidgetFormTextarea(array(
    ));
    $this->validatorSchema['validator_options'] = new sfValidatorString(array(
      'required' => false
    ));
    $this->widgetSchema['validator_messages'] = new sfWidgetFormTextarea(array(
    ));
    $this->validatorSchema['validator_messages'] = new sfValidatorString(array(
      'required' => false
    ));    

    // helps
    $this->widgetSchema->setHelps(array(
      'widget_options'     => $widget_options_help,    
      'widget_attributes'  => $widget_attributes_help,
      'validator_options'  => $validator_options_help,
      'validator_messages' => $validator_messages_help
      )
    );

  }
}