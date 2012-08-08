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
 * to use grid system of bootstrap
 * <?php echo $helper->renderArea('layout.top', '#dm_header.span3.offset1') ?> 
 * in that case you must define manually the spanX and offsetX
 *
 * See variables.less to define GRID parameters
 * 
 */

?>

<div id="dm_page">

	<div class="dm_layout container-fluid">
		<div class="row-fluid">	
	    	<?php echo $helper->renderArea('layout.top', '#dm_header.span12') ?>
      </div>
		<div class="row-fluid">
			<?php echo $helper->renderArea('page.content','#dm_page_content.span8') ?>
			<?php echo $helper->renderArea('layout.right','#dm_sidebar_right.span4') ?>
		</div>
		<div class="row-fluid">
			<?php echo $helper->renderArea('layout.bottom', '#dm_footer.span12') ?>	
		</div>
	</div>
  </div>
</div>