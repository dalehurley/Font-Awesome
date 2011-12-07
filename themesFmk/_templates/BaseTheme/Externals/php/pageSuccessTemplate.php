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
		
		<header id="dm_header">
			dm_header
		</header>
		
		<div id="dm_main" class="dm_layout clearfix">
			<div id="dm_main_inner" class="clearfix">
				
				<div id="dm_custom_top">
					dm_custom_top
				</div>
				
				<section id="dm_content">
					dm_content
				</section>
				
				<aside id="dm_sidebar_left">
					dm_sidebar_left
				</aside>
				
				<aside id="dm_sidebar_right">
					dm_sidebar_right
				</aside>
				
			</div>
		</div>
		
		<footer id="dm_footer">
			dm_footer
		</footer>
	</div>
</div>
