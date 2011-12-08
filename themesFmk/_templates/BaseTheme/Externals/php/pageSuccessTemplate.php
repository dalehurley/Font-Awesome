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
//Valeurs par défaut de configuration de la page
$pageOptionsDefault = array(
							'areas'	=> array(
								'dm_custom_top'		=>	array(
														//'index'		=> 1,
														'areaName'	=> 'customTop',
														'isActive'	=> true,
														'isPage'	=> false,
														'clearfix'	=> true
													),
								'dm_content'		=>	array(
														//'index'		=> 2,
														'areaName'	=> 'content',
														'isActive'	=> true,
														'isPage'	=> true,
														'clearfix'	=> false
													),
								'dm_sidebar_left'	=>	array(
														//'index'		=> 3,
														'areaName'	=> 'left',
														'isActive'	=> true,
														'isPage'	=> false,
														'clearfix'	=> false
													),
								'dm_sidebar_right'	=>	array(
														//'index'		=> 4,
														'areaName'	=> 'right',
														'isActive'	=> true,
														'isPage'	=> false,
														'clearfix'	=> false
													),
								'dm_custom_bottom'	=>	array(
														//'index'		=> 5,
														'areaName'	=> 'customBottom',
														'isActive'	=> true,
														'isPage'	=> false,
														'clearfix'	=> true
													)
									)
					);

//Personnalisation des valeurs de la pages
$pageOptionsCustom['areas']['dm_content']['clearfix'] = true;

$pageOptionsCustom['areas']['dm_content_test'] = array(
														//'index'		=> 6,
														'areaName'	=> 'contentTest',
														'isActive'	=> true,
														'isPage'	=> false,
														'clearfix'	=> false
													);


//On remplace les valeurs par défaut que si la variable de remplissage existe
$pageOptions = (isset($pageOptionsCustom)) ? array_replace_recursive($pageOptionsDefault, $pageOptionsCustom) : $pageOptionsDefault;

//ajout d'un nouvelle Area
$pageNewArea['dm_content_test_bis'] = array(
						'areaName'	=> 'contentTestBis',
						'isActive'	=> true,
						'isPage'	=> false,
						'clearfix'	=> false
					);
//position d'insertion (commence à zéro)
$insInd = 2;
$firstPart = array_slice($pageOptions['areas'], 0, $insInd, true);
$lastPart = array_slice($pageOptions['areas'], $insInd, (count($pageOptions['areas']) - $insInd), true);
$pageOptions['areas'] = array_merge($firstPart,$pageNewArea,$lastPart);


//AJOUT DE VARIABLES DISPONIBLES DANS LA PAGE
//Environnement de dev
$isDev = (sfConfig::get('sf_environment') == 'dev') ? true : false;

//Gabarit de la page visible en environnement de dev
$currentGabarit = $sf_context->getPage()->get('gabarit');
if ($currentGabarit == 'default' || $currentGabarit == '') {
    $currentGabarit = spLessCss::getLessParam('templateGabarit');
}
//affichage du widget de DEBUG du framework
if ($isDev) echo dm_get_widget('sidSPLessCss', 'debug', array());
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
						echo "<br/>";
					}
					
				?>
				
				
				
				<div id="dm_custom_top" class="clearfix">
					dm_custom_top
				</div>
				
				<section id="dm_content">
					dm_content
					<br/><br/>
					$pageOptionsDefault : <?php var_dump($pageOptionsDefault); ?>
					<br/><br/>
					$pageOptionsCustom : <?php var_dump($pageOptionsCustom); ?>
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
