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
//Configuration des zones du template
$pageOptionsCustom['areas']['dm_custom_top'] = array('index' => 0, 'areaName' => 'customTop', 'isActive' => true, 'isPage' => false, 'clearfix' => true);
$pageOptionsCustom['areas']['dm_sidebar_left']['isActive'] = false;

//Initialisation de la page et récupération des options de la page (avec fusion des options personnalisées)
$pageOptions = spLessCss::pageInit($pageOptionsCustom);
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
						$areaClass = ($area['clearfix']) ? '.clearfix' : null;
						$areaRole = ($area['areaName'] == "content") ? ' data-role="content"' : null;
						
						//affichage de la zone si active
						if ($area['isActive']) echo $helper->renderArea($areaType . '.' . $areaName, '#' . $id . $areaClass . $areaRole);
						
						//div vides de test pour la mise en page
						// echo '<div id="' . $id . '" class="' . (($area['clearfix']) ? 'clearfix ' . $areaName : $areaName) .'">' . $areaName . '</div>';
					}
				?>
			</div>
		</div>
		
		<?php echo $helper->renderArea('layout.bottom', '#dm_footer data-role="footer"') ?>
		
	</div>
</div>