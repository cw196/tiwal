<?php get_template_part('templates/head'); ?>
<?php global $virtue_premium; 
  if(isset($virtue_premium["smooth_scrolling"]) && $virtue_premium["smooth_scrolling"] == '1') {$scrolling = '1';} else {$scrolling = '0';}
  if(isset($virtue_premium["smooth_scrolling_hide"]) && $virtue_premium["smooth_scrolling_hide"] == '1') {$scrolling_hide = '1';} else {$scrolling_hide = '0';} 
  if(isset($virtue_premium['virtue_animate_in']) && $virtue_premium['virtue_animate_in'] == '1') {$animate = '1';} else {$animate = '0';}
  if(isset($virtue_premium['sticky_header']) && $virtue_premium['sticky_header'] == '1') {$sticky = '1';} else {$sticky = '0';}
  if(isset($virtue_premium['product_tabs_scroll']) && $virtue_premium['product_tabs_scroll'] == '1') {$pscroll = '1';} else {$pscroll = '0';}
  if(isset($virtue_premium['header_style'])) {$header_style = $virtue_premium['header_style'];} else {$header_style = 'standard';}
  if(isset($virtue_premium['select2_select'])) {$select2_select = $virtue_premium['select2_select'];} else {$select2_select = '1';}
  ?>
<body <?php body_class(); ?> data-smooth-scrolling="<?php echo $scrolling;?>" data-smooth-scrolling-hide="<?php echo $scrolling_hide;?>" data-jsselect="<?php echo $select2_select;?>" data-product-tab-scroll="<?php echo $pscroll; ?>" data-animate="<?php echo $animate;?>" data-sticky="<?php echo $sticky;?>">
<div id="wrapper" class="container">
  <!--[if lt IE 8]><div class="alert"> <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'virtue'); ?></div><![endif]-->

  <?php
    do_action('get_header');
    if($header_style == 'center') {
      get_template_part('templates/header-style-two');
    } else if ($header_style == 'shrink') {
      get_template_part('templates/header-style-three');
    } else {
      get_template_part('templates/header');
    }
  ?>

  <div class="wrap contentclass" role="document">

        <?php include kadence_template_path(); ?>
        
      <?php if (kadence_display_sidebar()) : ?>
      <aside id="ktsidebar" class="<?php echo kadence_sidebar_class(); ?> kad-sidebar" role="complementary">
        <div class="sidebar">
          <?php include kadence_sidebar_path(); ?>
        </div><!-- /.sidebar -->
      </aside><!-- /aside -->
      <?php endif; ?>
      </div><!-- /.row-->
    </div><!-- /.content -->
  </div><!-- /.wrap -->

  <?php do_action('get_footer');
  get_template_part('templates/footer'); ?>
</div><!--Wrapper-->
</body>
</html>
