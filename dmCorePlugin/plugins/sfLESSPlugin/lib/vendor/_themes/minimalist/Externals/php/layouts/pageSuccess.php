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

  <div class="dm_layout">

  	<?php echo $helper->renderArea('layout.top', '#dm_header.clearfix data-role="header"') ?>

    <div class="dm_layout_center clearfix">

      <?php echo $helper->renderArea('layout.customTop', '#dm_custom_top.clearfix') ?>
      
      <?php echo $helper->renderArea('layout.left','#dm_sidebar_left') ?>

      <?php echo $helper->renderArea('page.content','#dm_page_content') ?>

      <?php echo $helper->renderArea('layout.right','#dm_sidebar_right') ?>

      <?php echo $helper->renderArea('layout.customBottom', '#dm_custom_bottom.clearfix') ?>      

    </div>

    <?php echo $helper->renderArea('layout.bottom', '.clearfix') ?>

  </div>

</div>


<hr>


<div id="dm_page"<?php $isEditMode && print ' class="edit"' ?>>

  <div class="dm_layout">

  	<?php echo $helper->renderArea('layout.top', '#dm_header.clearfix data-role="header"') ?>

    <div class="dm_layout_center clearfix">

      <?php echo $helper->renderArea('layout.customTop', '#dm_custom_top.clearfix') ?>
      
      <?php echo $helper->renderArea('layout.left','#dm_sidebar_left') ?>

      <?php echo $helper->renderArea('page.content','#dm_page_content') ?>

      <?php echo $helper->renderArea('layout.right','#dm_sidebar_right') ?>

      <?php echo $helper->renderArea('layout.customBottom', '#dm_custom_bottom.clearfix') ?>      

    </div>

    <?php echo $helper->renderArea('layout.bottom', '.clearfix') ?>

  </div>

</div>

