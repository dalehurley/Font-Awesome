<?php if ($ckeip){ 
// CKEIP : http://www.bitsntuts.com/jquery/ckeditor-edit-in-place-jquery-plugin
?>

<script type="text/javascript" src="/dmCkEditorPersoPlugin/ckeip/ckeip.js"></script>
<script type="text/javascript" src="/dmCkEditorPersoPlugin/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/dmCkEditorPersoPlugin/js/ckeditor/adapters/jquery.js"></script>

<script type="text/javascript">
$(document).ready(function() {

        // masquer la tool_bar admin
        

        $('#textWidget<?php echo $widgetId; ?>').ckeip({

            e_url: '/dmCkEditorPersoAjax',
            data: {
                widgetId : '<?php echo $widgetId; ?>',
                culture : '<?php echo DmUserPermission::getDefaultCulture(); ?>'
            },
            e_hover_color:'#8FBC8F',
            e_outline_color: '#B0C4DE',

            ckeditor_config : <?php echo json_encode($configPerso); ?>

		},

        function (response) {
			//alert(response);
        }

    );
});
</script>
<link href="/dmCkEditorPersoPlugin/css/ckeip.css" rel="stylesheet" type="text/css" media="screen" />

<?php }

if ($bandeau) { ?>

<script type="text/javascript" src="/dmCkEditorPersoPlugin/js/jquery.marquee.js"></script>
<script type="text/javascript">
$(document).ready(function() {

       $('#textWidgetMarquee<?php echo $widgetId; ?>').marquee();

});
</script>

<?php
    $html = strip_tags($html);
    $html = str_replace(CHR(10),"",$html);
    $html = str_replace(CHR(13),"",$html);

    /* Exemple de paramètres
    <marquee behavior="scroll" direction="down" scrollamount="2" height="100" width="350">
    <marquee loop="3" behavior="slide" direction="right" width="350">
    <marquee scrollamount="2" behavior="alternate" direction="right" width="350">
    <marquee behavior="scroll" scrollamount="1" direction="left" width="350">

    scrollamount (integer)
    behavior (scroll|slide|alternate)
    direction (down|right|left)
    height (integer)
    width (integer)
    loop  (integer)
    */
    echo '<div id="textWidget'.$widgetId.'"><div id="textWidgetMarquee'.$widgetId.'" '.$optionbandeau.'>'.$html.'</div></div>';

} else {
    echo '<div id="textWidget'.$widgetId.'">'.$html.'</div>';
}


?>









