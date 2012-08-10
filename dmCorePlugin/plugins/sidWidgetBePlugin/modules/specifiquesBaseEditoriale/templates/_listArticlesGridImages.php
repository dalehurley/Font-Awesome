<?php

if (!json_decode($options)) echo debugTools::infoDebug(array('Erreur' => 'Options du widget mal formatée'),'error');

echo debugTools::infoDebug(array('Nb d\'images nécessaires' => $maxNbImages),'info');

$html = '
		<img src="/sidWidgetBePlugin/images/loading.gif" style="display:none;"/>


				<div id="ri-grid" class="ri-grid ri-grid-size-3 ri-shadow">
					<ul>';

$nbArticlesWithImage = '0';
foreach ($articles as $article) {
	$imageLink = '/_images/lea' . $article->filename . '-p.jpg';
	if (is_file(sfConfig::get('sf_web_dir').$imageLink)){ 
		$html .= _tag('li',_link($article)->text('<img src="'.$imageLink.'"/>'));
		$nbArticlesWithImage++;
	} else {
		//echo _tag('li',_link($article)->text('<img src="/sidWidgetBePlugin/images/transp.png"/>'));
	}
}
				
$html .= '<li title="zerer"></li>
					</ul>
				</div>

		<script type="text/javascript">	
			
			$(document).ready(function() {
				
				$( \'#ri-grid\' ).gridrotator( '
					.$options.			
				' );

			});
		</script>
';

$typeInfo = 'success';
if ($nbArticlesWithImage < $maxNbImages) $typeInfo = 'error';
echo debugTools::infoDebug(array('Nb articles avec image' => $nbArticlesWithImage),$typeInfo);

echo $html;

