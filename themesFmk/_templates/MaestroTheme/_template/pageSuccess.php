<?php
/*
 * Render a page.
 * Layout areas and page content area are rendered.
 *
 * Available vars :
 * - dmFrontPageHelper $helper      ( page_helper service )
 * - boolean           $isEditMode  ( whether the user is allowed to edit page )
 */
?>

<?php
//AJOUT DE VARIABLES DISPONIBLES DANS LA PAGE
//Environnement de dev
$isDev = (sfConfig::get('sf_environment') == 'dev' || sfConfig::get('sf_environment') == 'less') ? true : false;

//Gabarit de la page visible en environnement de dev
$currentGabarit = $sf_context->getPage()->get('gabarit');
if ($currentGabarit == 'default' || $currentGabarit == '') {
    $currentGabarit = spLessCss::getLessParam('templateGabarit');
}
// affichage en div.lessDebug
if ($isDev) {
    echo dm_get_widget('main', 'lessDebug', array(
        'css_class' => ''
    ));
}
?>

<div id="dm_page"<?php $isEditMode && print ' class="edit"' ?>>
	<div id="dm_page_inner">
		<?php echo $helper->renderArea('layout.top', '.clearfix') ?>

		<div id="dm_main" class="dm_layout clearfix">
			<div id="dm_main_inner" class="clearfix">
				<?php echo $helper->renderArea('layout.centerTop', '.clearfix') ?>
				<?php
				//La zone de contenu est toujours insérée dans la page, quel que soit le gabarit choisi
				echo $helper->renderArea('page.content');
				if ($currentGabarit == 'two-sidebars' || $currentGabarit == 'sidebar-left') {
					echo $helper->renderArea('layout.left');
				}
				if ($currentGabarit == 'two-sidebars' || $currentGabarit == 'sidebar-right') {
					echo $helper->renderArea('layout.right');
				}
				?>
				<?php //echo $helper->renderArea('layout.centerBottom', '.clearfix') ?>
				<div class="dm_area dm_layout_centerBottom clearfix">
					<?php echo $helper->renderArea('layout.centerBottomLeft', '.clearfix') ?>
					<?php echo $helper->renderArea('layout.centerBottomRight', '.clearfix') ?>
				</div>
			</div>
		</div>
		<?php echo $helper->renderArea('layout.bottom', '.clearfix') ?>
	</div>
</div>