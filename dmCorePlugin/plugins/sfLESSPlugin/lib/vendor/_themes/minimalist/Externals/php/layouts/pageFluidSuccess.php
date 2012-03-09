<div id="dm_page"<?php $isEditMode && print ' class="edit"' ?>>

	<div class="dm_layout container-fluid">
		<div class="row-fluid">	
	      		<?php echo $helper->renderArea('layout.top', '#dm_header') ?>
   		</div>
		<div class="row-fluid">
			<?php echo $helper->renderArea('layout.customTop', '#dm_custom_top') ?>
          	</div>
		<div class="row-fluid">
			<?php echo $helper->renderArea('layout.left','#dm_sidebar_left') ?>
			<?php echo $helper->renderArea('page.content','#dm_page_content') ?>
			<?php echo $helper->renderArea('layout.right','#dm_sidebar_right') ?>
		</div>
		<div class="row-fluid">		
			<?php echo $helper->renderArea('layout.customBottom', '#dm_custom_bottom') ?>      
		</div>
		<div class="row-fluid">
			<?php echo $helper->renderArea('layout.bottom', '#dm_footer') ?>	
		</div>
	</div>
  </div>
</div>
