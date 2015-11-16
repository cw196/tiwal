<div class="home-product home-margin carousel_outerrim home-padding kad-animation" data-animation="fade-in" data-delay="0">
		<?php global $virtue_premium; if(!empty($virtue_premium['product_best_title'])) {$product_best_title = $virtue_premium['product_best_title'];} else {$product_best_title = 'Best Selling Products';}
			if(!empty($virtue_premium['home_product_best_column'])) {$product_tcolumn = $virtue_premium['home_product_best_column'];} else {$product_tcolumn = '4';}
				if ($product_tcolumn == '2') {$md = 2; $sm = 2; $xs = 1; $ss = 1;} 
		        else if ($product_tcolumn == '3'){ $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
	            else if ($product_tcolumn == '6'){ $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
		        else if ($product_tcolumn == '5'){ $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
		        else { $md = 4; $sm = 3; $xs = 2; $ss = 1;} 
		if(!empty($virtue_premium['home_product_best_count'])) {$hp_probcount = $virtue_premium['home_product_best_count'];} else {$hp_probcount = '6';}
		if(!empty($virtue_premium['home_product_best_speed'])) {$hp_bestspeed = $virtue_premium['home_product_best_speed'].'000';} else {$hp_bestspeed = '9000';}
		if(isset($virtue_premium['home_product_best_scroll']) && $virtue_premium['home_product_best_scroll'] == 'all' ) {$hp_bestscroll = '';} else {$hp_bestscroll = 'items:1,';}?>
		<div class="clearfix"><h3 class="hometitle"><?php echo $product_best_title; ?></h3></div>
		<div class="fredcarousel">
		<div id="hpb_carouselcontainer" class="rowtight fadein-carousel">
		<div id="home-product-best-carousel" class="products caroufedselclass clearfix">
        <?php global $woocommerce_loop;
        		$temp = $wp_query; 
				  $wp_query = null; 
				  $wp_query = new WP_Query();
				  $wp_query->query(array(
					'post_type' => 'product',
					 'meta_key'=> 'total_sales',
		            'orderby' => 'meta_value_num',
					'post_status' => 'publish',
					'posts_per_page' => $hp_probcount));
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
		            <a id="prevport_product_best" class="prev_carousel icon-arrow-left" href="#"></a>
					<a id="nextport_product_best" class="next_carousel icon-arrow-right" href="#"></a>
		</div>
	</div>
	<script type="text/javascript">
	 jQuery( window ).load(function () {
	 	var $wcontainer = jQuery('#hpb_carouselcontainer');
	 	var $container = jQuery('#home-product-best-carousel');
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
					function initCarousel_bestproduct() {
	 					$container.carouFredSel({
							scroll: {<?php echo $hp_bestscroll ?> easing: "swing", duration: 700, pauseOnHover : true},
							auto: {play: true, timeoutDuration: <?php echo $hp_bestspeed;?>},
							prev: '#prevport_product_best',
							next: '#nextport_product_best',
							pagination: false,
							swipe: true,
								items: {visible: null
								}
						});
		 			}
		 			setWidths();
		 			initCarousel_bestproduct();
		 				jQuery(window).on("debouncedresize", function( event ) {
							$container.trigger("destroy");
							setWidths();
							initCarousel_bestproduct();
						});
		 			$wcontainer.animate({'opacity' : 1});
});
</script>