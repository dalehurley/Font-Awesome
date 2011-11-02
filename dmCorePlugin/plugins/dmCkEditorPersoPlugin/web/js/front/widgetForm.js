$.fn.dmWidgetPersoContentCkEditorPersoForm = function(widget)
{

    var self = this, $textarea = self.find('textarea.dm_ckeditor'),

        formName = self.metadata().form_name,

  $form = self.find('form:first')
        .append('<input type="hidden" name="'+formName+'[widget_width]" value="'+widget.element.width()+'" />'),

        $tabs = $form.find('div.dm_tabbed_form').dmCoreTabForm();


    //Kill all existing instances before loading
    //ckeditor again or it will not work with ajax request
    if ( $textarea.attr('id') in CKEDITOR.instances )
    {
        CKEDITOR.remove(CKEDITOR.instances[$textarea.attr('id')]);
    }

    $textarea.ckeditor(function(){}, $textarea.metadata());

    self.find('input.submit').click(function() {

        $textarea.text($textarea.val());

    });
};

window.CKEDITOR_BASEPATH = dm_configuration.relative_url_root+'/dmCkEditorPersoPlugin/js/ckeditor/';