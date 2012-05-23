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
// $pageOptionsCustom['areas']['dm_custom_bottomTEST'] = array('areaName' => 'customBottom', 'isActive' => true, 'isPage' => false, 'clearfix' => true);

//création de zones supplémentaires
$pageOptionsCustom['areas']['dm_custom_bottom_left'] = array('areaName' => 'customBottomLeft', 'isActive' => true, 'isPage' => false, 'clearfix' => true);
$pageOptionsCustom['areas']['dm_custom_bottom_right'] = array('areaName' => 'customBottomRight', 'isActive' => true, 'isPage' => false, 'clearfix' => true);

//à réactiver pour vider cette zone parasite
// $pageOptionsCustom['areas']['dm_center_bottom'] = array('areaName' => 'centerBottom', 'isActive' => true, 'isPage' => false, 'clearfix' => true);

$pageOptionsCustom['areas']['dm_sidebar_right']['isActive'] = false;

//Initialisation de la page et récupération des options de la page (avec fusion des options personnalisées)
$pageOptions = spLessCss::pageInit($pageOptionsCustom);
?>
<div id="dm_page" data-role="page">
	<div id="dm_page_inner">
		
		<?php echo $helper->renderArea('layout.top', '#dm_header.clearfix data-role="header"') ?>
		
		<div id="dm_main" class="dm_layout clearfix">
			<div id="dm_main_inner" class="clearfix">
				<?php
					//Maestro : création variable de remplissage de la zone spécifique dm_custom_bottom
					$html_customBottom = '';

					foreach ($pageOptions['areas'] as $id => $area) {
						//composition des options de la Area à afficher
						$areaType = ($area['isPage']) ? 'page' : 'layout';
						$areaName = $area['areaName'];
						$areaClass = ($area['clearfix']) ? '.clearfix' : null;
						$areaRole = ($area['areaName'] == "content") ? ' data-role="content"' : null;
						
						//affichage de la zone si active
						// if ($area['isActive']) echo $helper->renderArea($areaType . '.' . $areaName, '#' . $id . $areaClass . $areaRole);

						//Maestro : exclusion de customBottomLeft et customBottomRight
						if ($area['isActive']) {
							//rendu de l'area dans une variable
							$areaRendering = $helper->renderArea($areaType . '.' . $areaName, '#' . $id . $areaClass . $areaRole);
							//supprimer customBottom lorsque terminé
							if ($area['areaName'] == 'customBottomLeft' || $area['areaName'] == 'customBottomRight' || $area['areaName'] == 'customBottom'){
								$html_customBottom.= $areaRendering;
							}else{
								echo $areaRendering;
							}
						}

						//div vides de test pour la mise en page
						// echo '<div id="' . $id . '" class="' . (($area['clearfix']) ? 'clearfix ' . $areaName : $areaName) .'">' . $areaName . '</div>';
					}

					//Maestro : création d'un container contenant deux zones spéfiques
					// if($html_customBottom != ''){
					echo '<div id="dm_custom_bottom" class="clearfix dm_area dm_layout_customBottom">' .
							'<div class="dm_zones clearfix">' .
								$html_customBottom .
							'</div>' .
						'</div>';
					// }
				?>
			</div>
		</div>

		<?php echo $helper->renderArea('layout.bottom', '#dm_footer data-role="footer"') ?>
		
	</div>
</div>