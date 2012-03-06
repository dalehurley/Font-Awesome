<?php
/*
 * Render a page.
 * Layout areas and page content area are rendered.
 * 
 * Available vars :
 * - dmFrontPageHelper $helper      ( page_helper service )
 * - boolean           $isEditMode  ( whether the user is allowed to edit page )
 * 
 * to use grid system of bootstrap
 * <?php echo $helper->renderArea('layout.top', '#dm_header') ?> in that case you must define manually the span and offset of #elements
 * ---------------
 * theme.less
 *  * // layout style
 * .default { // default's layout
 * 	#dm_header 			{ .span(11); .offset(1) }
 * 	#dm_custom_top		{ .span(12) }
 * 	#dm_sidebar_left 	{ .span(2) }
 * 	#dm_page_content 	{ .span(7) }
 * 	#dm_sidebar_right 	{ .span(3) }
 * 	#dm_custom_bottom 	{ .span(12) }
 * 	#dm_footer 			{ .span(12) }
 * }
 * .default-fluid { // default-fluid layout
 * 	#dm_header                    { .span-fluid(12); }
 * 	#dm_custom_top                { .span-fluid(12)  }
 * 	#dm_sidebar_left              { .span-fluid(2)   }
 * 	#dm_page_content              { .span-fluid(7)   }
 * 	#dm_sidebar_right             { .span-fluid(3)   }
 * 	#dm_custom_bottom             { .span-fluid(12)  }
 * 	#dm_footer                    { .span-fluid(12)  }
 * 
 * 	#dm_header:first-child, 
 * 	#dm_custom_top:first-child, 
 * 	#dm_sidebar_left:first-child, 
 * 	#dm_page_content:first-child, 
 * 	#dm_sidebar_right:first-child, 
 * 	#dm_custom_bottom:first-child, 
 * 	#dm_footer:first-child { margin-left: 0; } // 
 * }
 * ---------------
 * 
 */

?>

<div id="dm_page"<?php $isEditMode && print ' class="edit"' ?>>

	<div class="dm_layout container">
		<div class="row">	
	      		<?php echo $helper->renderArea('layout.top', '#dm_header') ?>
   		</div>
		<div class="row">
			<?php echo $helper->renderArea('layout.customTop', '#dm_custom_top') ?>
          	</div>
		<div class="row">
			<?php echo $helper->renderArea('layout.left','#dm_sidebar_left') ?>
			<?php echo $helper->renderArea('page.content','#dm_page_content') ?>
			<?php echo $helper->renderArea('layout.right','#dm_sidebar_right') ?>
		</div>
		<div class="row">		
			<?php echo $helper->renderArea('layout.customBottom', '#dm_custom_bottom') ?>      
		</div>
		<div class="row">
			<?php echo $helper->renderArea('layout.bottom', '#dm_footer') ?>	
		</div>
	</div>
  </div>
</div>
