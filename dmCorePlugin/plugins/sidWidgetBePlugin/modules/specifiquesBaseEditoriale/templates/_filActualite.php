<?php
// vars : $section, $titreBloc, $titreLien, $longueurTexte, $articles, $arrayRubrique, $photo
if (count($articles)) { // si nous avons des actu articles

    echo _tag('h2.title', $titreBloc);
    echo _open('ul.elements');
    foreach ($articles as $article){

	include_partial("objectPartials/filActualite", array("article" => $article,"textLength" => $longueurTexte,"textEnd" => '(...)','titreBloc' => $titreBloc, "titreLien" => $titreLien,"arrayRubrique" => $arrayRubrique,"photo" => $photo));

    }
    echo _close('ul');
    
  
}

?>
    wwwwwwwwwwwwwwwwwww
    

    <link rel="stylesheet" href="http://jqueryui.com/themes/base/jquery.ui.dialog.css">
    	<script>
	$(function() {
		$( "#dialog" ).dialog({ resizable: true });
	});
	</script>
    
        <div id="dialog" title="Basic dialog">
	<p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
</div>
