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
//Valeurs par défaut de configuration de la page
$pageOptionsDefault = spLessCss::pageTemplateGetOptionsDefault();

//Ajout de nouvelles zones
$pageOptionsCustom['areas']['dm_custom_top'] = array(
											'index'		=> 0,
											'areaName'	=> 'customTop',
											'isActive'	=> true,
											'isPage'	=> false,
											'clearfix'	=> true
											);
$pageOptionsCustom['areas']['dm_custom_bottom'] = array(
											'areaName'	=> 'customBottom',
											'isActive'	=> true,
											'isPage'	=> false,
											'clearfix'	=> true
											);

//On remplace les valeurs par défaut que si la variable de remplissage existe
$pageOptions = (isset($pageOptionsCustom)) ? spLessCss::pageTemplateCustomOptions($pageOptionsDefault, $pageOptionsCustom) : $pageOptionsDefault;

//affichage du widget de DEBUG du framework
if ($pageOptions['idDev']) echo dm_get_widget('sidSPLessCss', 'debug', array());
?>

<div id="dm_page">
	<div id="dm_page_inner">
		
		<?php echo $helper->renderArea('layout.top', '#dm_header.clearfix') ?>
		
		<div id="dm_main" class="dm_layout clearfix">
			<div id="dm_main_inner" class="clearfix">
				
				<?php
					foreach ($pageOptions['areas'] as $id => $area) {
						//composition des options de la Area à afficher
						$areaType = ($area['isPage']) ? 'page' : 'layout';
						$areaName = $area['areaName'];
						$areaClass = ($area['clearfix']) ? 'clearfix' : '';
						
						//affichage de la zone
						//echo $helper->renderArea($areaType . '.' . $areaName, '#' . $id . '.' . $areaClass);
						
						//div vides de test pour la mise en page
						echo '<div id="' . (($id === 'dm_page_content') ? 'dm_content' : $id) . '" class="' . (($area['clearfix']) ? 'clearfix ' . $areaName : $areaName) .'">' . $areaName . '</div>';
					}
				?>
			</div>
		</div>
		
		<?php echo $helper->renderArea('layout.bottom', '#dm_footer.clearfix') ?>
		
	</div>
</div>