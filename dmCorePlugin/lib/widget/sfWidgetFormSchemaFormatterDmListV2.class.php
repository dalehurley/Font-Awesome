<?php

class sfWidgetFormSchemaFormatterDmListV2 extends sfWidgetFormSchemaFormatter {

      // $rowFormat = "<li class=\"dm_form_element clearfix\">\n  %error%%label%\n  %field%%help%\n%hidden_fields%</li>\n",
      // $errorRowFormat = "<li class=\"clearfix\">\n%errors%</li>\n",
      // $helpFormat = '<div class="dm_help_wrap">%help%</div>',
      // $decoratorFormat = "<ul class=\"dm_form_elements\">\n  %content%</ul>";
      protected
      // $rowFormat                 = "<li class=\"dm_form_element clearfix\">\n  %error%%label%\n  %field%%help%\n%hidden_fields%</li>\n",
      // $helpFormat                = '%help%',
      // $errorRowFormat            = '%errors%',
      // $errorListFormatInARow     = "  <ul class=\"error_list\">\n%errors%  </ul>\n",
      // $errorRowFormatInARow      = "    <li>%error%</li>\n",
      // $namedErrorRowFormatInARow = "    <li>%name%: %error%</li>\n";


      $rowFormat      = "<div class=\"control-group%groupclass%\">\n  %label%\n  <div class=\"controls\">%field% %error% %help%\n%hidden_fields% </div></div>\n",
      $errorListFormatInARow     = "  <span class=\"help-inline\">\n%errors%</span>\n",
      $errorRowFormatInARow      = "    %error%</br>\n",
      $namedErrorRowFormatInARow = "    %name%: %error%</br>\n",
      $helpFormat     = '<p class="help-block">%help%</p>';
      //$decoratorFormat = "<ul class=\"dm_form_elements\">\n  %content%</ul>";


          // <li class="dm_form_element clearfix">
          //     <ul class="error_list">
          //     <li>Ce champ ne devrait pas être vide</li>
          //   </ul>
          // <label for="sid_contact_data_form_email" class="required">Votre courriel</label>
          //   <input type="text" id="sid_contact_data_form_email" value="" name="sid_contact_data_form[email]">Votre courriel ne sera jamais publié
          // </li>


          // <div class="control-group">
          //   <label for="input01" class="control-label">Text input</label>
          //   <div class="controls">
          //     <input type="text" id="input01" class="input-xlarge">
          //     <span class="help-inline">Please correct the error</span>
          //     <p class="help-block">In addition to freeform text, any HTML5 text-based input appears like so.</p>
          //   </div>
          // </div>






    protected $validatorSchema = null;
     
    protected $params = array();
     
      /**
       * Constructor
       * @param sfWidgetFormSchema $widgetSchema
       * @param sfValidatorSchema $validatorSchema
       * @param array $params
       */
      public function __construct(sfWidgetFormSchema $widgetSchema, sfValidatorSchema $validatorSchema, $params = array())
      {
        $this->validatorSchema = $validatorSchema;
        $this->params = $params;
        parent::__construct($widgetSchema);
      }

      public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null)
      {
        $groupclass = '';
        if (count($errors)) $groupclass = ' error';

        return strtr($this->getRowFormat(), array(
          '%groupclass%'    => $groupclass,
          '%label%'         => $label,
          '%field%'         => $field,
          '%error%'         => $this->formatErrorsForRow($errors),
          '%help%'          => $this->formatHelp($help),
          '%hidden_fields%' => null === $hiddenFields ? '%hidden_fields%' : $hiddenFields,
        ));
      }
     
      /**
       * Generates a label for the given field name.
       *
       * @param  string $name        The field name
       * @param  array  $attributes  Optional html attributes for the label tag
       *
       * @return string The label tag
       */
      public function generateLabel($name, $attributes = array())
      {
        $is_required = false;
        if ( $this->validatorSchema and isset($this->validatorSchema[$name]) )
        {
          $validator = $this->validatorSchema[$name];
     
          if ( $validator->getOption('required') )
          {
            $class_name = 'required';
            if (isset($attributes['class'])) $attributes['class'] .= ' '.$class_name; else $attributes['class'] = $class_name;
          }

          // add control-label from Bootstrap
          if (isset($attributes['class'])) $attributes['class'] .= ' control-label'; else $attributes['class'] = 'control-label';
        }
     
        $s = parent::generateLabel($name, $attributes);
     
        return $s;
      }



}