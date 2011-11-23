<?php

class sfWidgetFormSchemaFormatterDmList extends sfWidgetFormSchemaFormatter {

    protected
    $rowFormat = "<li class=\"dm_form_element clearfix %required_class%\">\n  %error%%formated_label%\n  %field%%help%\n%hidden_fields%</li>\n",
    $errorRowFormat = "<li>\n%errors%</li>\n",
    $helpFormat = '<div class="dm_help_wrap">%help%</div>',
    $decoratorFormat = "<ul class=\"dm_form_elements\">\n  %content%</ul>";

    public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null) {
        
        $row = parent::formatRow(
                        $label, $field, $errors, $help, $hiddenFields
        );

        // Dans dmCorePlugin/lib/form/dmFormDoctrine.php on ajoute un * au label required
        // Ici on analyse le label, s'il a une étoile on ajoute la class required au li le contenant
        $requiredClass = '';
        $pos = strpos($label, ' *'); 
        if ($pos === false) {
        } else {
            $requiredClass = 'required';
            $label = str_replace(' *', '', $label); // on retire l'étoile
        }

        
        return strtr($row, array(
                    '%required_class%' => $requiredClass,
                    '%formated_label%' => $label
                ));
    }

}