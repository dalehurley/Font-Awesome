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
    $currentGabarit = myUser::getLessParam('templateGabarit');
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
                                    
                                    // une variable $pageRight peut être définie dans les pageSuccess.php du site (avant 
                                    // l'include du présent fichier) et permet, si elle est égale à TRUE, de rendre la colonne de droite en mode page
                                    // RAPPEL : 
                                    //   area en mode page : les widgets de cette area ne sont visibles que sur la page en cours
                                    //   area en mode layout : les widgets de cette area sont visibles dans toutes les pages utilisant ce layout et/ou cette area
                                    if (isset($pageRight) && $pageRight){
                                        echo $helper->renderArea('page.right');
                                    } else {
                                        echo $helper->renderArea('layout.right');
                                    }
					
				}
				?>
				<?php echo $helper->renderArea('layout.centerBottom', '.clearfix') ?>
			</div>
		</div>
		<?php echo $helper->renderArea('layout.bottom', '.clearfix') ?>
	</div>
</div>



