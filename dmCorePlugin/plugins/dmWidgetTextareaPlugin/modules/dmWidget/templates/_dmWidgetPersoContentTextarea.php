<?php if ($aloha){ ?>
<script type="text/javascript" src="/dmWidgetTextareaPlugin/aloha/aloha.js"></script>
<script type="text/javascript" src="/dmWidgetTextareaPlugin/aloha/plugins/com.gentics.aloha.plugins.Format/plugin.js"></script>
<script type="text/javascript" src="/dmWidgetTextareaPlugin/aloha/plugins/com.gentics.aloha.plugins.Table/plugin.js"></script>
<script type="text/javascript" src="/dmWidgetTextareaPlugin/aloha/plugins/com.gentics.aloha.plugins.List/plugin.js"></script>
<script type="text/javascript" src="/dmWidgetTextareaPlugin/aloha/plugins/com.gentics.aloha.plugins.Link/plugin.js"></script>
<script type="text/javascript" src="/dmWidgetTextareaPlugin/aloha/plugins/com.gentics.aloha.plugins.HighlightEditables/plugin.js"></script>
<script type="text/javascript" src="/dmWidgetTextareaPlugin/aloha/plugins/com.gentics.aloha.plugins.TOC/plugin.js"></script>
<script type="text/javascript" src="/dmWidgetTextareaPlugin/aloha/plugins/com.gentics.aloha.plugins.Link/delicious.js"></script>
<script type="text/javascript" src="/dmWidgetTextareaPlugin/aloha/plugins/com.gentics.aloha.plugins.Link/LinkList.js"></script>
<script type="text/javascript" src="/dmWidgetTextareaPlugin/aloha/plugins/com.gentics.aloha.plugins.Paste/plugin.js"></script>
<script type="text/javascript" src="/dmWidgetTextareaPlugin/aloha/plugins/com.gentics.aloha.plugins.Paste/wordpastehandler.js"></script>

<!-- turn an element into editable Aloha continuous text -->
<script type="text/javascript">
    function saveEditable(event, eventProperties) {
/*
            alert (
            'id HTML: '+eventProperties.editable.getId()+'\n\n'+
                'contenu : '+eventProperties.editable.getContents()+'\n\n'+
                'id widget: <?php echo $widgetId; ?>');
*/

        $.ajax({
            url: "/dmWidgetTextareaAjax",
            data: "content="+eventProperties.editable.getContents()+"&widgetId=<?php echo $widgetId; ?>&culture=<?php echo DmUserPermission::getDefaultCulture(); ?>",
            success: function(msg){
                alert(msg);
            }
        });



	}


GENTICS.Aloha.settings = {
	logLevels: {'error': true, 'warn': true, 'info': true, 'debug': false},
	errorhandling : false,
	ribbon: false,
	"i18n": {
		"current": "<?php echo DmUserPermission::getDefaultCulture(); ?>"
	},
	"plugins": {
	 	"com.gentics.aloha.plugins.Format": {
		 	// all elements with no specific configuration get this configuration
            //[ 'b', 'i', 'p', 'del', 'sub', 'sup', 'title', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'pre', 'removeFormat']
			config : [ 'b', 'i', 'p', 'del', 'title', 'h1', 'h2', 'h3' ],
            "freakShow" : false

		}
  	}
};

$(document).ready(function() {
	$('#textWidget').aloha();
	GENTICS.Aloha.EventRegistry.subscribe(GENTICS.Aloha, "editableDeactivated", saveEditable);

    //GENTICS.Aloha.settings.plugins["com.gentics.aloha.plugins.Format"].freakShow;

});






</script>
<?php } ?>




<?php if ($jeditable){ ?>

A faire JEDITABLE : => http://www.appelsiini.net/projects/jeditable/default.html

<?php } ?>



<?php if ($ckeip){ ?>

A faire CKEIP : http://www.bitsntuts.com/jquery/ckeditor-edit-in-place-jquery-plugin








<?php } ?>




<div id="textWidget"><?php echo $libelle; ?></div>








