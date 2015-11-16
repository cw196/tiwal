<?php global $virtue_premium; if(isset($virtue_premium['header_height'])) {$header_height = $virtue_premium['header_height'];} else {$header_height = 90;}
      if(isset($virtue_premium['m_sticky_header']) && $virtue_premium['m_sticky_header'] == '1') {$msticky = '1'; $mstickyclass = 'mobile-stickyheader';} else {$msticky = '0'; $mstickyclass = '';}
            if(isset($virtue_premium['logo_layout'])) {
            if($virtue_premium['logo_layout'] == 'logohalf') {$logocclass = 'col-md-6'; $menulclass = 'col-md-6';}
            else {$logocclass = 'col-md-4'; $menulclass = 'col-md-8';}
          }
          else {$logocclass = 'col-md-4'; $menulclass = 'col-md-8';} ?>
<header id="kad-banner" class="banner headerclass kad-header-style-three <?php echo $mstickyclass;?>" role="banner" data-header-shrink="1" data-mobile-sticky="<?php echo $msticky;?>" data-header-base-height="<?php echo $header_height;?>">
<?php if (kadence_display_topbar()) : ?> 
 <?php get_template_part('templates/header', 'topbar'); ?>
<?php endif; ?>

          <style type="text/css"> .kad-header-style-three #nav-main ul.sf-menu > li > a {line-height:<?php echo $header_height;?>px; }  </style>
  <div id="kad-shrinkheader" class="container" style="height:<?php echo $header_height;?>px; line-height:<?php echo $header_height;?>px; ">
    <div class="row">
          <div class="<?php echo $logocclass; ?> clearfix kad-header-left">
            <div id="logo" class="logocase">
              <a class="brand logofont" style="height:<?php echo $header_height;?>px; line-height:<?php echo $header_height;?>px; display:block;" href="<?php echo home_url(); ?>/">
                       <?php global $virtue_premium; if (!empty($virtue_premium['x1_virtue_logo_upload']['url'])) { ?> <div id="thelogo" style="height:<?php echo $header_height;?>px; line-height:<?php echo $header_height;?>px;"><img src="<?php echo $virtue_premium['x1_virtue_logo_upload']['url']; ?>" alt="<?php  bloginfo('name');?>" style="max-height:<?php echo $header_height;?>px" class="kad-standard-logo" />
                         <?php if(!empty($virtue_premium['x2_virtue_logo_upload']['url'])) {?> <img src="<?php echo $virtue_premium['x2_virtue_logo_upload']['url'];?>" alt="<?php  bloginfo('name');?>" class="kad-retina-logo" style="max-height:<?php echo $virtue_premium['x1_virtue_logo_upload']['height'];?>px" /> <?php } ?>
                        </div> <?php } else { bloginfo('name'); } ?>
              </a>
           </div> <!-- Close #logo -->
       </div><!-- close col-md-4 -->

       <div class="<?php echo $menulclass; ?> kad-header-right">
         <nav id="nav-main" class="clearfix" role="navigation">
          <?php
            if (has_nav_menu('primary_navigation')) :
              wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'sf-menu')); 
            endif;
           ?>
         </nav> 
        </div> <!-- Close span7 -->       
    </div> <!-- Close Row -->
  </div> <!-- Close Container -->
  <?php if (has_nav_menu('mobile_navigation')) : ?>
  <div class="container kad-nav-three" >
           <div id="mobile-nav-trigger" class="nav-trigger">
              <a class="nav-trigger-case" data-toggle="collapse" rel="nofollow" data-target=".kad-nav-collapse">
                <div class="kad-navbtn mobileclass clearfix"><i class="icon-menu"></i></div>
                <?php global $virtue_premium; if(!empty($virtue_premium['mobile_menu_text'])) {$menu_text = $virtue_premium['mobile_menu_text'];} else {$menu_text = __('Menu', 'virtue');} ?>
                <div class="kad-menu-name mobileclass"><?php echo $menu_text; ?></div>
              </a>
            </div>
            <div id="kad-mobile-nav" class="kad-mobile-nav">
              <div class="kad-nav-inner mobileclass">
                <div id="mobile_menu_collapse" class="kad-nav-collapse collapse mobile_menu_collapse">
                 <?php wp_nav_menu( array('theme_location' => 'mobile_navigation','items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>', 'menu_class' => 'kad-mnav')); ?>
               </div>
            </div>
          </div>
          </div> <!-- Close Container -->
          <?php  endif; ?> 
</header>