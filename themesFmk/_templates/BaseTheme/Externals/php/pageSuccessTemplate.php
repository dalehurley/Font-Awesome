<?php
/*
 * Render a page.
 * Layout areas and page content area are rendered.
 *
 * Available vars :
 * - dmFrontPageHelper $helper      ( page_helper service )
 * - boolean           $isEditMode  ( whether the user is allowed to edit page )
 * 
 * 
 * Ce fichier reste dans le core, il est appelé en include dans les sites par les fichiers XXXXSuccess.php
 * 
 * 
 */
?>

<?php
//Gabarit de la page
$currentGabarit = $sf_context->getPage()->get('gabarit');
if ($currentGabarit == 'default' || $currentGabarit == '') {
    $currentGabarit = spLessCss::getLessParam('templateGabarit');
}

$currentGabarit = 'no-sidebar';

//Valeurs par défaut de configuration de la page
$pageOptionsDefault = array(
							'idDev'				=> ((sfConfig::get('sf_environment') == 'dev') ? true : false),
							'currentGabarit'	=> $currentGabarit,
							'areas'				=> array(
								/*'dm_custom_top'		=>	array(
														'areaName'	=> 'customTop',
														'isActive'	=> true,
														'isPage'	=> false,
														'clearfix'	=> true
													),*/
								'dm_content'		=>	array(
														'areaName'	=> 'content',
														'isActive'	=> true,
														'isPage'	=> true,
														'clearfix'	=> false
													),
								'dm_sidebar_left'	=>	array(
														'areaName'	=> 'left',
														'isActive'	=> (($currentGabarit == 'two-sidebars' || $currentGabarit == 'sidebar-left') ? true : false),
														'isPage'	=> false,
														'clearfix'	=> false
													),
								'dm_sidebar_right'	=>	array(
														'areaName'	=> 'right',
														'isActive'	=> (($currentGabarit == 'two-sidebars' || $currentGabarit == 'sidebar-right') ? true : false),
														'isPage'	=> false,
														'clearfix'	=> false
													)/*,
								'dm_custom_bottom'	=>	array(
														'areaName'	=> 'customBottom',
														'isActive'	=> true,
														'isPage'	=> false,
														'clearfix'	=> true
													)*/
									)
					);


//Personnalisation des valeurs de la page
//$pageOptionsCustom['areas']['dm_content']['clearfix'] = true;

//ajout d'un nouvelle Area
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





//AJOUT DE VARIABLES DISPONIBLES DANS LA PAGE





//affichage du widget de DEBUG du framework
if ($pageOptions['idDev']) echo dm_get_widget('sidSPLessCss', 'debug', array());


?>

<div id="dm_page">
	<div id="dm_page_inner">
		
		<header id="dm_header" class="clearfix">
			dm_header
		</header>
		
		<div id="dm_main" class="dm_layout clearfix">
			<div id="dm_main_inner" class="clearfix">
				
				<?php
					
					//génération des areas de la page
					echo "Génération des areas de la page";
					echo "<br/>";
					
					foreach ($pageOptions['areas'] as $key => $value) {
						echo "ID area :".$key;
						echo "<br/>";
						echo "areaName :".$value['areaName'];
						echo "<br/>";
						echo "isActive :".$value['isActive'];
						echo "<br/>";
						echo "clearfix :".$value['clearfix'];
						echo "<br/>";
						echo "<br/>";
					}
					
				?>
				
				
				
				<div id="dm_custom_top" class="clearfix">
					dm_custom_top
				</div>
				
				<section id="dm_content">
					dm_content
					<br/><br/>
					$pageOptions : <?php var_dump($pageOptions); ?>
				</section>
				
				<aside id="dm_sidebar_left">
					dm_sidebar_left
				</aside>
				
				<aside id="dm_sidebar_right">
					dm_sidebar_right
				</aside>
				
				<div id="dm_custom_bottom" class="clearfix">
					dm_custom_bottom
				</div>
				
			</div>
		</div>
		
		<footer id="dm_footer" class="clearfix">
			dm_footer
		</footer>
	</div>
</div>
