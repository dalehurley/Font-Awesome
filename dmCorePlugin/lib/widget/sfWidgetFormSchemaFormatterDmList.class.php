<?php

class sfWidgetFormSchemaFormatterDmList extends sfWidgetFormSchemaFormatter {

    protected
    $rowFormat = "<li class=\"dm_form_element clearfix\">\n  %error%%label%\n  %field%%help%\n%hidden_fields%</li>\n",
    $errorRowFormat = "<li class=\"clearfix\">\n%errors%</li>\n",
    $helpFormat = '<div class="dm_help_wrap">%help%</div>',
    $decoratorFormat = "<ul class=\"dm_form_elements\">\n  %content%</ul>";

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
        }
     
        $s = parent::generateLabel($name, $attributes);
     
        return $s;
      }



}