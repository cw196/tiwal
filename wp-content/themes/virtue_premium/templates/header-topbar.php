<section id="topbar" class="topclass">
    <div class="container">
      <div class="row">


        <div class="col-md-4 col-sm-4">
          <div class="topbarmenu clearfix">
          <?php if (has_nav_menu('topbar_navigation')) :
              wp_nav_menu(array('theme_location' => 'topbar_navigation', 'menu_class' => 'sf-menu'));
            endif;?>
            <?php if(kadence_display_topbar_icons()) : ?>
            <div class="topbar_social">
              <ul>
                <?php global $virtue_premium; $top_icons = $virtue_premium['topbar_icon_menu'];
                $i = 1;
                foreach ($top_icons as $top_icon) {
                  if(!empty($top_icon['target']) && $top_icon['target'] == 1) {$target = '_blank';} else {$target = '_self';}
                  echo '<li><a href="'.$top_icon['link'].'" data-toggle="tooltip" data-placement="bottom" target="'.$target.'" class="topbar-icon-'.$i.'" data-original-title="'.esc_attr($top_icon['title']).'">';
                  if($top_icon['url'] != '') echo '<img src="'.esc_url($top_icon['url']).'"/>' ; else echo '<i class="'.$top_icon['icon_o'].'"></i>';
                  echo '</a></li>';
                $i ++;
                } ?>
              </ul>
            </div>
          <?php endif; ?>
            <?php global $virtue_premium; if(isset($virtue_premium['show_cartcount'])) {
               if($virtue_premium['show_cartcount'] == '1') { 
                if (class_exists('woocommerce')) {
                 global $virtue_premium, $woocommerce;
                  ?>
                    <ul class="kad-cart-total">
                      <li>
                      <a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php esc_attr_e('View your shopping cart', 'virtue'); ?>">
                          <i class="icon-basket" style="padding-right:5px;"></i> <?php if(!empty($virtue_premium['cart_placeholder_text'])) {echo $virtue_premium['cart_placeholder_text'];} else {echo __('Your Cart', 'virtue');}  ?> <span class="kad-cart-dash">-</span> <?php echo $woocommerce->cart->get_cart_total(); ?>
                      </a>
                    </li>
                  </ul>
                <?php } } }?>
          </div>
        </div><!-- close col-md-6 -->

<div class="col-md-5 col-sm-5">
<div style="float:right">
<a href="http://tiwal.online/zh" style="
    margin-right: 10px;
"><img src="http://tiwal.online/wp-content/uploads/2015/10/China.png"></img></a><a href="http://tiwal.online"><img src="http://tiwal.online/wp-content/uploads/2015/10/nz.png"></img></a>
</div>	
</div><!--colse col-md-4-->


        <div class="col-md-3 col-sm-3">
          <div id="topbar-search" class="topbar-widget">
            <?php if(kadence_display_topbar_widget()) { if(is_active_sidebar('topbarright')) { dynamic_sidebar('topbarright'); } 
              } else { if(kadence_display_top_search()) {get_search_form();} 
          } ?>
        </div>
        </div> <!-- close col-md-6-->



	



      </div> <!-- Close Row -->
    </div> <!-- Close Container -->
  </section>