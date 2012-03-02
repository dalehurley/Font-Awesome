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
<div id="dm_page"<?php $isEditMode && print ' class="edit"' ?>>

	<div class="dm_layout container">
		<div class="row">	
	      		<?php echo $helper->renderArea('layout.top', '#dm_header.span12') ?>
   		</div>
		<div class="row">
			<?php echo $helper->renderArea('layout.customTop', '#dm_custom_top.span12') ?>
          	</div>
		<div class="row">
			<?php echo $helper->renderArea('layout.left','#dm_sidebar_left.span2') ?>
			<?php echo $helper->renderArea('page.content','#dm_page_content.span7') ?>
			<?php echo $helper->renderArea('layout.right','#dm_sidebar_right.span3') ?>
		</div>
		<div class="row">		
			<?php echo $helper->renderArea('layout.customBottom', '#dm_custom_bottom.span12') ?>      
		</div>
		<div class="row">
			<?php echo $helper->renderArea('layout.bottom', '#dm_footer.span12') ?>	
		</div>
	</div>
  </div>
</div>
