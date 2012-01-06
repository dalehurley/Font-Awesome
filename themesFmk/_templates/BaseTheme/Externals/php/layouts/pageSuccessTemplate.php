<?php
/*
 * Render a page.
 * Layout areas and page content area are rendered.
 *
 * Available vars :
 * - dmFrontPageHelper $helper      ( page_helper service )
 * - boolean           $isEditMode  ( whether the user is allowed to edit page )
 * 
 * Ce fichier reste dans le core, il est appelé en include dans les sites par les fichiers XXXXSuccess.php
 * 
 */
//Debug ancienne zone pour déplacement anciens widgets
//$pageOptionsCustom['areas']['centerTop'] = array('index' => 0, 'areaName' => 'centerTop', 'isActive' => true, 'isPage' => false, 'clearfix' => true);
//Ajout de nouvelles zones
$pageOptionsCustom['areas']['dm_custom_top'] = array('index' => 0, 'areaName' => 'customTop', 'isActive' => true, 'isPage' => false, 'clearfix' => true);
$pageOptionsCustom['areas']['dm_custom_bottom'] = array('areaName' => 'customBottom', 'isActive' => true, 'isPage' => false, 'clearfix' => true);

//Initialisation de la page et récupération des options de la page (avec fusion des options personnalisées)
$pageOptions = spLessCss::pageInit($pageOptionsCustom);

//À rajouter pour tester quelques sprites
/*
<div class="clearfix">
	<div class="sprite-test sprite-internet-home-X">
	</div>
	<div class="sprite-test sprite-internet-search-X">
	</div>
	<div class="sprite-test sprite-internet-back-X">
	</div>
	<div class="sprite-test sprite-internet-forward-X">
	</div>
	<div class="sprite-test sprite-internet-email-X">
	</div>
	<div class="sprite-test sprite-internet-like-X">
	</div>
</div>
*/
?>
<div id="dm_page" data-role="page">
	<div id="dm_page_inner">
		
		<?php echo $helper->renderArea('layout.top', '#dm_header.clearfix data-role="header"') ?>
		
		<div id="dm_main" class="dm_layout clearfix">
			<div id="dm_main_inner" class="clearfix">
				<?php
					foreach ($pageOptions['areas'] as $id => $area) {
						//composition des options de la Area à afficher
						$areaType = ($area['isPage']) ? 'page' : 'layout';
						$areaName = $area['areaName'];
						$areaClass = ($area['clearfix']) ? 'clearfix' : null;
						$areaRole = ($area['areaName'] == "content") ? ' data-role="content"' : null;
						
						//affichage de la zone
						echo $helper->renderArea($areaType . '.' . $areaName, '#' . $id . '.' . $areaClass . $areaRole);
						
						//div vides de test pour la mise en page
						//echo '<div id="' . (($id === 'dm_page_content') ? 'dm_content' : $id) . '" class="' . (($area['clearfix']) ? 'clearfix ' . $areaName : $areaName) .'">' . $areaName . '</div>';
					}
				?>
			</div>
		</div>
		
		<?php echo $helper->renderArea('layout.bottom', '#dm_footer data-role="footer"') ?>
		
	</div>
</div>