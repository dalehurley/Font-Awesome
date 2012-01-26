<?php
// vars : $flash,$width,$height
$html = '';
if (count($flash)) { // si nous avons des pubs
    $html.= _media('/'.$flash)  
		->size($width, $height)      // resize to 300px width, 200px height  
		->flashConfig(array(  // configure flash rendering  
		  'bgcolor' => '#000000',  
		  'wmode' => 'opaque',  
		  'quality' => 'high'  
		));
}

//Affichage html de sortie
echo $html;