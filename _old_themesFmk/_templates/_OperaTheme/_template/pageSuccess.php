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
    $currentGabarit = sidSPLessCss::getLessParam('templateGabarit');
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
		<!-- ajout opera -->
		<?php //echo $helper->renderArea('layout.top-link', '.clearfix') ?>
		<!-- fin ajout opera -->
	
		<?php echo $helper->renderArea('layout.top', '.clearfix') ?>

		<!-- ajout opera -->
		<!--
		<div id="box_ombre">
			<div id="ombre_body"></div>
		</div>
		<div id="bg_toolbar"></div>
		-->
		<!-- fin ajout opera -->

		<!-- ajout opera -->
		<!--
		<div id="content">
			<div id="box_toolbar">
				<div id="toolbar">
					<?php //echo $helper->renderArea('layout.toolbar', '.clearfix') ?>
				</div>
			</div>
		-->
	    <!-- fin ajout opera  -->	    

	    <div id="dm_main" class="dm_layout clearfix">
			<div id="dm_main_inner" class="clearfix">
				<?php //echo $helper->renderArea('layout.centerTop', '.clearfix') ?>
				<div class="dm_area dm_layout_centerTop clearfix">
					<?php echo $helper->renderArea('layout.centerTopTop', '.clearfix') ?>
					<?php echo $helper->renderArea('layout.centerTopBottom', '.clearfix') ?>
				</div>
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
				<?php echo $helper->renderArea('layout.centerBottom', '.clearfix') ?>
			</div>
	    </div>

        <!--    ajout opera -->
		<!--
        </div>
        <div id="spacer"></div>
        -->
		<!-- fin ajout opera -->

		<?php echo $helper->renderArea('layout.bottom', '.clearfix') ?>

		<!--    ajout opera -->
		<!--
        <div id="footer_01">
			<?php // echo $helper->renderArea('layout.bottom-1', '.clearfix') ?>
        </div>

        <div id="footer_02">
			<?php // echo $helper->renderArea('layout.bottom-2', '.clearfix') ?>
        </div>
		-->
		<!-- fin ajout opera -->
		
    </div>
</div>