<?php 
//Shortcode for Carousels
function kad_carousel_shortcode_function( $atts, $content) {
	extract(shortcode_atts(array(
		'type' => '',
		'columns' => '4',
		'orderby' => '',
		'speed' => '9000',
		'scroll' => '',
		'cat' => '',
		'readmore' => false,
		'items' => '8'
), $atts));
	$carousel_rn = (rand(10,100));
	if(empty($type)) {$type = 'post';}
	if(empty($orderby)) {$orderby = 'menu_order';}
	if($orderby == 'menu_order') {$order = 'ASC';} else {$order = 'DESC';} 
	if(empty($cat)) {$cat = '';}
	if(empty($scroll) || $scroll == 1) {$scroll = 'items:1,';} else {$scroll = '';}

ob_start(); ?>
				<div class="carousel_outerrim kad-animation" data-animation="fade-in" data-delay="0">
				<div class="home-margin fredcarousel">
				<div id="carouselcontainer-<?php echo $carousel_rn; ?>" class="rowtight fadein-carousel">
				<div id="carousel-<?php echo $carousel_rn; ?>" class="clearfix caroufedselclass products">
	<?php if ($type == "portfolio") {  
						if ($columns == '2') {$itemsize = 'tcol-lg-6 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $slidewidth = 560; $slideheight = 560; $md = 2; $sm = 2; $xs = 1; $ss = 1;}
						else if ($columns == '1') {$itemsize = 'tcol-lg-12 tcol-md-12 tcol-sm-12 tcol-xs-12 tcol-ss-12'; $slidewidth = 560; $slideheight = 560; $md = 1; $sm = 1; $xs = 1; $ss = 1;} 
		                else if ($columns == '3'){ $itemsize = 'tcol-lg-4 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $slidewidth = 400; $slideheight = 400; $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
		                else if ($columns == '6'){ $itemsize = 'tcol-lg-2 tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $slidewidth = 240; $slideheight = 240; $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
		                else if ($columns == '5'){ $itemsize = 'tcol-lg-25 tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $slidewidth = 240; $slideheight = 240; $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
		                else {$itemsize = 'tcol-lg-3 tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $slidewidth = 300; $slideheight = 300; $md = 4; $sm = 3; $xs = 2; $ss = 1;} 
		                if(!empty($cat)){$portfolio_category = $cat;} else {$portfolio_category = '';}
				$wp_query = null; 
				$wp_query = new WP_Query();
						$wp_query->query(array('orderby' => $orderby,'order' => $order,'post_type' => 'portfolio','portfolio-type'=>$portfolio_category,'posts_per_page' => $items));
						if ( $wp_query ) :  while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
							<div class="<?php echo $itemsize; ?> kad_product">
							<div class="grid_item portfolio_item postclass">
                        	<?php global $post; $postsummery = get_post_meta( $post->ID, '_kad_post_summery', true );
						    if ($postsummery == 'slider') { ?>
                           		<div class="flexslider imghoverclass clearfix">
                       			<ul class="slides">
                       			<?php $image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
								$attachments = array_filter( explode( ',', $image_gallery ) );
                    			if ($attachments) {
									foreach ($attachments as $attachment) {
										$attachment_url = wp_get_attachment_url($attachment , 'full');
										$image = aq_resize($attachment_url, $slidewidth, $slideheight, true);
											if(empty($image)) {$image = $attachment_url;}
												echo '<li><img src="'.$image.'" width="'.$slidewidth.'" height="'.$slideheight.'"/></li>';
											}
										}
                    			 ?>  
								</ul>
              					</div> <!--Flex Slides-->
              			<script type="text/javascript">jQuery(window).load(function () {jQuery('.flexslider').flexslider({animation: "fade",animationSpeed: 400,slideshow: true,slideshowSpeed: 7000, before: function(slider) {slider.removeClass('loading');}  }); });</script>
              <?php } else {
						if (has_post_thumbnail( $post->ID ) ) { $image_url = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'full' ); 
								$thumbnailURL = $image_url[0]; $image = aq_resize($thumbnailURL, $slidewidth, $slideheight, true); 
								if(empty($image)) { $image = $thumbnailURL;} ?>
									<div class="imghoverclass"><a href="<?php the_permalink()  ?>" title="<?php the_title(); ?>"><img src="<?php echo $image ?>" alt="<?php the_title(); ?>" width="<?php echo $slidewidth;?>" height="<?php echo $slideheight;?>" class="lightboxhover" style="display: block;"></a> </div>
                           			<?php $image = null; $thumbnailURL = null;?>
                        <?php } } ?>
              		<a href="<?php the_permalink() ?>" class="portfoliolink"><div class="piteminfo"><h5><?php the_title();?></h5></div></a>
                	</div></div>
					<?php endwhile; else: ?>
					<li class="error-not-found"><?php _e('Sorry, no portfolio entries found.', 'virtue');?></li>
				<?php endif; $wp_query = null; wp_reset_query(); ?>
            </div></div>

            <?php } else if($type == "post") {
            		if ($columns == '3'){ $itemsize = 'tcol-lg-4 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $slidewidth = 400; $slideheight = 400; $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
		                else if ($columns == '5'){ $itemsize = 'tcol-lg-25 tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $slidewidth = 240; $slideheight = 240; $md = 5; $sm = 4; $xs = 3; $ss = 2;}
		                else if ($columns == '2'){ $itemsize = 'tcol-lg-6 tcol-md-6 tcol-sm-6 tcol-xs-6 tcol-ss-12'; $slidewidth = 400; $slideheight = 400; $md = 2; $sm = 2; $xs = 2; $ss = 1;}
		                else if ($columns == '1'){ $itemsize = 'tcol-lg-12 tcol-md-12 tcol-sm-12 tcol-xs-12 tcol-ss-12'; $slidewidth = 400; $slideheight = 400; $md = 1; $sm = 1; $xs = 1; $ss = 1;} 
		                else {$itemsize = 'tcol-lg-3 tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $slidewidth = 300; $slideheight = 300; $md = 4; $sm = 3; $xs = 2; $ss = 1;} 
				$wp_query = null; 
				$wp_query = new WP_Query();
				$wp_query->query(array('orderby' => $orderby,'order' => $order,'post_type' => 'post','category_name'=>$cat,'posts_per_page' => $items));
						if ( $wp_query ) :  while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
						<div class="<?php echo $itemsize;?> kad_product">
                			<div <?php global $post; post_class('blog_item grid_item'); ?>>
	                    		<?php if (has_post_thumbnail( $post->ID ) ) {
										$image_url = wp_get_attachment_image_src( 
										get_post_thumbnail_id( $post->ID ), 'full' ); 
										$thumbnailURL = $image_url[0]; 
										$image = aq_resize($thumbnailURL, $slidewidth, $slideheight, true); 
										if(empty($image)) {$image = $thumbnailURL;}
									} else {
                               $thumbnailURL = virtue_post_default_placeholder();
                                  $image = aq_resize($thumbnailURL, $slidewidth, $slideheight, true); 
                                  if(empty($image)) { $image = $thumbnailURL; } ?>
                             <?php  } ?>
									 <div class="imghoverclass">
		                           		<a href="<?php the_permalink()  ?>" title="<?php the_title(); ?>">
		                           			<img src="<?php echo $image ?>" alt="<?php the_title(); ?>" class="iconhover" style="display:block;">
		                           		</a> 
		                         	</div>
                           		<?php $image = null; $thumbnailURL = null; ?>
              					<a href="<?php the_permalink() ?>" class="bcarousellink">
				                    <header>
			                          <h5 class="entry-title"><?php the_title(); ?></h5>
			                          <div class="subhead">
			                          	<span class="postday kad-hidedate"><?php echo get_the_date('j M Y'); ?></span>
			                        </div>	
			                        </header>
		                        	<div class="entry-content color_body">
		                          		<p><?php echo strip_tags(virtue_excerpt(16)); ?><?php if($readmore) {global $virtue_premium; if(!empty($virtue_premium['post_readmore_text'])) {$readmoret = $virtue_premium['post_readmore_text'];} else {$readmoret = __('Read More', 'virtue');} echo $readmoret; }?>
		                          	</p>
		                        	</div>
                           		</a>
               		 </div>
				</div>
				<?php endwhile; else: ?>
				<div class="error-not-found"><?php _e('Sorry, no post entries found.', 'virtue');?></div>
				<?php endif; $wp_query = null; wp_reset_query(); ?>								
				</div>
				</div>
            <?php } else if($type == "featured-products") {
				  global $woocommerce_loop;
				  if($columns == 1) {$md = 1; $sm = 1; $xs = 1; $ss = 1; $woocommerce_loop['columns'] = 3;
				   }else {
				  	$woocommerce_loop['columns'] = $columns;
					if ($columns == '2') {$md = 2; $sm = 2; $xs = 1; $ss = 1;} 
			        else if ($columns == '3'){ $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
		            else if ($columns == '6'){ $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
			        else if ($columns == '5'){ $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
			        else { $md = 4; $sm = 3; $xs = 3; $ss = 1;} 
			    	}
				  $wp_query = null; 
				  $wp_query = new WP_Query();
				  $wp_query->query(array('post_type' => 'product','meta_key' => '_featured','meta_value' => 'yes','post_status' => 'publish','orderby' => 'menu_order', 'posts_per_page' => $items));
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<?php woocommerce_get_template_part( 'content', 'product' ); ?>
					<?php endwhile; ?>
					<?php endif; ?>         
                    <?php $wp_query = null;  wp_reset_query(); ?>
				</div>
				</div>
           <?php  } else if($type == "sale-products") {
           			if (class_exists('woocommerce')) {
					  global $woocommerce, $woocommerce_loop;
						$product_ids_on_sale = woocommerce_get_product_ids_on_sale(); $product_ids_on_sale[] = 0;
						$meta_query = array();
			          $meta_query[] = $woocommerce->query->visibility_meta_query();
			          $meta_query[] = $woocommerce->query->stock_status_meta_query();
      				}
      				if($columns == 1) {$md = 1; $sm = 1; $xs = 1; $ss = 1; $woocommerce_loop['columns'] = 3;
				   }else {
				  $woocommerce_loop['columns'] = $columns;
					if ($columns == '2') {$md = 2; $sm = 2; $xs = 1; $ss = 1;} 
			        else if ($columns == '3'){ $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
		            else if ($columns == '6'){ $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
			        else if ($columns == '5'){ $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
			        else { $md = 4; $sm = 3; $xs = 3; $ss = 1;} 
			    	}
			    	if(!empty($cat)){$product_category = $cat;} else {$product_category = '';}
				  $wp_query = null; 
				  $wp_query = new WP_Query();
				  $wp_query->query(array('post_type' => 'product','meta_query' => $meta_query,'post__in' => $product_ids_on_sale,'post_status' => 'publish','product_cat'=>$product_category,'orderby' => 'menu_order', 'posts_per_page' => $items));
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<?php woocommerce_get_template_part( 'content', 'product' ); ?>
					<?php endwhile; ?>
					<?php endif; ?>         
                    <?php $wp_query = null;  wp_reset_query(); ?>
				</div>
				</div>
           <?php } else if($type == "best-products") {
					  global $woocommerce_loop;
					if($columns == 1) {$md = 1; $sm = 1; $xs = 1; $ss = 1; $woocommerce_loop['columns'] = 3;
				   }else {
					  $woocommerce_loop['columns'] = $columns;
						if ($columns == '2') {$md = 2; $sm = 2; $xs = 1; $ss = 1;} 
				        else if ($columns == '3'){ $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
			            else if ($columns == '6'){ $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
				        else if ($columns == '5'){ $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
				        else { $md = 4; $sm = 3; $xs = 3; $ss = 1;} 
			    	}
				  $wp_query = null; 
				  $wp_query = new WP_Query();
				  $wp_query->query(array('post_type' => 'product','meta_key'=> 'total_sales','orderby' => 'meta_value_num','post_status' => 'publish','posts_per_page' => $items));
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<?php woocommerce_get_template_part( 'content', 'product' ); ?>
					<?php endwhile; ?>
					<?php endif; ?>         
                    <?php $wp_query = null;  wp_reset_query(); ?>
				</div>
				</div>
            <?php } else if($type == "cat-products") {
					  global $woocommerce_loop;
					if($columns == 1) {$md = 1; $sm = 1; $xs = 1; $ss = 1; $woocommerce_loop['columns'] = 3;
				   }else {
					  $woocommerce_loop['columns'] = $columns;
						if ($columns == '2') {$md = 2; $sm = 2; $xs = 1; $ss = 1;} 
				        else if ($columns == '3'){ $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
			            else if ($columns == '6'){ $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
				        else if ($columns == '5'){ $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
				        else { $md = 4; $sm = 3; $xs = 3; $ss = 1;} 
			    	}
			    	if(!empty($cat)){$product_category = $cat;} else {$product_category = '';}
				  $wp_query = null; 
				  $wp_query = new WP_Query();
				  $wp_query->query(array('post_type' => 'product','orderby' => $orderby, 'order' => $order, 'product_cat'=>$product_category, 'post_status' => 'publish','posts_per_page' => $items));
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<?php woocommerce_get_template_part( 'content', 'product' ); ?>
					<?php endwhile; ?>
					<?php endif; ?>         
                    <?php $wp_query = null;  wp_reset_query(); ?>
				</div>
				</div>
           <?php } ?>
			<div class="clearfix"></div>
            <a id="prevport-<?php echo $carousel_rn; ?>" class="prev_carousel icon-arrow-left" href="#"></a>
			<a id="nextport-<?php echo $carousel_rn; ?>" class="next_carousel icon-arrow-right" href="#"></a>
			</div></div>
			<script type="text/javascript"> jQuery( window ).load(function () {var $wcontainer = jQuery('#carouselcontainer-<?php echo $carousel_rn; ?>'); var $container = jQuery('#carousel-<?php echo $carousel_rn; ?>');
	 				setWidths(); 
	 				function init_Carousel_widget() {
	 					$container.carouFredSel({
							scroll: { <?php echo $scroll; ?> easing: "swing", duration: 700, pauseOnHover : true}, auto: {play: true, timeoutDuration: <?php echo $speed; ?>},
							prev: '#prevport-<?php echo $carousel_rn; ?>', next: '#nextport-<?php echo $carousel_rn; ?>', pagination: false, swipe: true, items: {visible: null}
						});
	 					}
	 					init_Carousel_widget();
		 				jQuery(window).on("debouncedresize", function( event ) {
		 					$container.trigger("destroy");
		 					setWidths();
		 					init_Carousel_widget();
						});
		 			$wcontainer.animate({'opacity' : 1});
					function getUnitWidth() {var width;
					if(jQuery(window).width() <= 540) {
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
					} });
			</script>				

	<?php  $output = ob_get_contents();
		ob_end_clean();
	return $output;
}