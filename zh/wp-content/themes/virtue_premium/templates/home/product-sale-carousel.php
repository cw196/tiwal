<div class="home-product home-margin carousel_outerrim home-padding kad-animation" data-animation="fade-in" data-delay="0">
		<?php global $virtue_premium; if(!empty($virtue_premium['product_sale_title'])) {$product_sale_title = $virtue_premium['product_sale_title'];} else {$product_sale_title = 'Products on Sale';}
		if(!empty($virtue_premium['home_product_sale_column'])) {$product_tcolumn = $virtue_premium['home_product_sale_column'];} else {$product_tcolumn = '4';}
				if ($product_tcolumn == '2') {$md = 2; $sm = 2; $xs = 1; $ss = 1;} 
		        else if ($product_tcolumn == '3'){ $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
	            else if ($product_tcolumn == '6'){ $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
		        else if ($product_tcolumn == '5'){ $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
		        else { $md = 4; $sm = 3; $xs = 2; $ss = 1;} 
		if(!empty($virtue_premium['home_product_sale_count'])) {$hp_proscount = $virtue_premium['home_product_sale_count'];} else {$hp_proscount = '6';}
		if(!empty($virtue_premium['home_product_sale_speed'])) {$hp_salespeed = $virtue_premium['home_product_sale_speed'].'000';} else {$hp_salespeed = '9000';} 
		if(isset($virtue_premium['home_product_sale_scroll']) && $virtue_premium['home_product_sale_scroll'] == 'all' ) {$hp_salescroll = '';} else {$hp_salescroll = 'items:1,';}?> 
		<div class="clearfix"><h3 class="hometitle"><?php echo $product_sale_title; ?></h3></div>
		<div class=" fredcarousel">
		<div id="hps_carouselcontainer" class="rowtight fadein-carousel">
		<div id="home-product-sale-carousel" class="products caroufedselclass clearfix">
		<?php 
		if (class_exists('woocommerce')) {
			global $woocommerce, $woocommerce_loop;
			$product_ids_on_sale = woocommerce_get_product_ids_on_sale(); $product_ids_on_sale[] = 0;
			$meta_query = array();
		          $meta_query[] = $woocommerce->query->visibility_meta_query();
		          $meta_query[] = $woocommerce->query->stock_status_meta_query();
      			}
				$temp = $wp_query; 
				  $wp_query = null; 
				  $wp_query = new WP_Query();
				  $wp_query->query(array(
					'post_type' => 'product',
					 'meta_query' => $meta_query,
 		            'post__in' => $product_ids_on_sale,
					'post_status' => 'publish',
					'orderby' => 'menu_order',
					'posts_per_page' => $hp_proscount));
					$count =0;
					$woocommerce_loop['columns'] = $product_tcolumn;
					 if ( $wp_query ) : 
							 
					while ( $wp_query->have_posts() ) : $wp_query->the_post();
						wc_get_template_part( 'content', 'product' );
					endwhile; 
					endif;
                      $wp_query = null; 
                      $wp_query = $temp;  // Reset
                    ?>
                    <?php wp_reset_query(); ?>
		</div>
	</div>
		<div class="clearfix"></div>
		            <a id="prevport_product_sale" class="prev_carousel icon-arrow-left" href="#"></a>
					<a id="nextport_product_sale" class="next_carousel icon-arrow-right" href="#"></a>
		</div>
	</div>
	<script type="text/javascript">
	 jQuery( window ).load(function () {
	 	var $wcontainer = jQuery('#hps_carouselcontainer');
	 	var $container = jQuery('#home-product-sale-carousel');
	 				function getUnitWidth() {
						var width;
						if(jQuery(window).width() <= 480) {
						width = $wcontainer.width() / <?php echo $ss;?>;
						} else if(jQuery(window).width() <= 767) {
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
	 				function initCarousel_saleproduct() {
	 					$container.carouFredSel({
							scroll: {<?php echo $hp_salescroll ?> easing: "swing", duration: 700, pauseOnHover : true},
							auto: {play: true, timeoutDuration: <?php echo $hp_salespeed; ?>},
							prev: '#prevport_product_sale',
							next: '#nextport_product_sale',
							pagination: false,
							swipe: true,
								items: {visible: null
								}
						});
		 			}
		 			setWidths();
		 			initCarousel_saleproduct();
		 			jQuery(window).on("debouncedresize", function( event ) {
						$container.trigger("destroy");
						setWidths();
						initCarousel_saleproduct();
					});
		 			$wcontainer.animate({'opacity' : 1});
});
</script>