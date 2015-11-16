<div class="home-product home-margin carousel_outerrim home-padding kad-animation" data-animation="fade-in" data-delay="0">
		<?php global $virtue_premium; $product_title = $virtue_premium['product_title'];
		if(!empty($product_title)) {$ptitle = $product_title; } else { $ptitle = 'Featured Products'; }
		if(!empty($virtue_premium['home_product_feat_column'])) {$product_tcolumn = $virtue_premium['home_product_feat_column'];} else {$product_tcolumn = '4';}
				if ($product_tcolumn == '2') {$md = 2; $sm = 2; $xs = 1; $ss = 1;} 
		        else if ($product_tcolumn == '3'){ $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
	            else if ($product_tcolumn == '6'){ $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
		        else if ($product_tcolumn == '5'){ $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
		        else { $md = 4; $sm = 3; $xs = 2; $ss = 1;} 
		if(!empty($virtue_premium['home_product_count'])) {$hp_procount = $virtue_premium['home_product_count'];} else {$hp_procount = '6';}
		if(!empty($virtue_premium['home_product_feat_speed'])) {$hp_featspeed = $virtue_premium['home_product_feat_speed'].'000';} else {$hp_featspeed = '9000';} 
		if(isset($virtue_premium['home_product_feat_scroll']) && $virtue_premium['home_product_feat_scroll'] == 'all' ) {$hp_featscroll = '';} else {$hp_featscroll = 'items:1,';}?>
		<div class="clearfix"><h3 class="hometitle"><?php echo $ptitle; ?></h3></div>
		<div class=" fredcarousel">
		<div id="hp_carouselcontainer" class="rowtight fadein-carousel">
		<div id="home-product-carousel" class="products caroufedselclass clearfix">
		<?php    global $woocommerce_loop;
		          $temp = $wp_query; 
				  $wp_query = null; 
				  $wp_query = new WP_Query();
				  $wp_query->query(array(
					'post_type' => 'product',
					'meta_key' => '_featured',
					'meta_value' => 'yes',
					'post_status' => 'publish',
					'orderby' => 'menu_order',
					'order' => 'ASC',
					'posts_per_page' => $hp_procount));
					$count =0;
					$woocommerce_loop['columns'] = $product_tcolumn;
					if ( $wp_query ) : 
							 
					while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<?php wc_get_template_part( 'content', 'product' ); ?>
					<?php endwhile; // end of the loop. ?>
						
					<?php endif; ?>
                                    
                    <?php 
                      $wp_query = null; 
                      $wp_query = $temp;  // Reset
                    ?>
                    <?php wp_reset_query(); ?>
</div>
</div>
<div class="clearfix"></div>
            <a id="prevport_product" class="prev_carousel icon-arrow-left" href="#"></a>
			<a id="nextport_product" class="next_carousel icon-arrow-right" href="#"></a>
</div>
	</div>
	<script type="text/javascript">
	 jQuery( window ).load(function () {
	 	var $wcontainer = jQuery('#hp_carouselcontainer');
	 	var $container = jQuery('#home-product-carousel');
	 				function getUnitWidth() {
							var width;
							if(jQuery(window).width() <= 480) {
							width = $wcontainer.width() / <?php echo $ss;?>;
								} else if(jQuery(window).width() <= 768) {
							width = $wcontainer.width() / <?php echo $xs;?>;
								} else if(jQuery(window).width() <= 990) {
							width = $wcontainer.width() / <?php echo $sm;?>;
								} else {
							width = $wcontainer.width() / <?php echo $md;?>;
								}
							return width;
						}
						function setWidths() {
							var unitWidth = getUnitWidth() -1;
							$container.children().css({ width: unitWidth });
						}
	 				function initCarousel_product() {
		 					$container.carouFredSel({
								scroll: { <?php echo $hp_featscroll ?> easing: "swing", duration: 700, pauseOnHover : true},
								auto: {play: true, timeoutDuration: <?php echo $hp_featspeed; ?>},
								prev: '#prevport_product',
								next: '#nextport_product',
								pagination: false,
								swipe: true,
								items: {visible:null},
							});
		 				}
		 			setWidths();
		 			initCarousel_product();
		 			jQuery(window).on("debouncedresize", function( event ) {
		 				$container.trigger("destroy");
						setWidths();
						initCarousel_product();
					});
		 			$wcontainer.animate({'opacity' : 1});
});
</script>