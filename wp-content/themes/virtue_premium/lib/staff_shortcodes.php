<?php 
//Shortcode for staff Posts
function kad_staff_shortcode_function( $atts, $content) {
	extract(shortcode_atts(array(
		'orderby' => '',
		'cat' => '',
		'columns' => '',
		'height' => '',
		'items' => ''
), $atts));
	$rn = (rand(10,100));
	if(empty($orderby)) {$orderby = 'menu_order';}
	if($orderby == 'menu_order') {$order = 'ASC';} else {$order = 'DESC';} 
	if(empty($items)) {$items = '4';}
	if(empty($cat)) {$cat = '';}
	if(empty($columns)) {$columns = '3';}
						if ($columns == '2') {$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $slidewidth = 560; $slideheight = 560; $md = 2; $sm = 2; $xs = 1; $ss = 1;} 
						else if ($columns == '1') {$itemsize = 'tcol-md-12 tcol-sm-12 tcol-xs-12 tcol-ss-12'; $slidewidth = 560; $slideheight = 560; $md = 1; $sm = 1; $xs = 1; $ss = 1;} 
		                   else if ($columns == '3'){ $itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $slidewidth = 366; $slideheight = 366; $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
		                   else if ($columns == '6'){ $itemsize = 'tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $slidewidth = 240; $slideheight = 240; $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
		                   else if ($columns == '5'){ $itemsize = 'tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $slidewidth = 240; $slideheight = 240; $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
		                   else {$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $slidewidth = 270; $slideheight = 270; $md = 4; $sm = 3; $xs = 2; $ss = 1;}
		                	if(!empty($height)) {$slideheight = $height;}
		global $virtue_premium; if(isset($virtue_premium['virtue_animate_in']) && $virtue_premium['virtue_animate_in'] == 1) {$animate = 1;} else {$animate = 0;}
ob_start(); ?>
				<div class="home-staff">
						<div id="staffwrapper-<?php echo $rn;?>" class="rowtight init-isotope" data-fade-in="<?php echo $animate;?>" data-iso-selector=".s_item"> 
            <?php $wp_query = null; 
				  $wp_query = new WP_Query();
				  $wp_query->query(array('orderby' => $orderby,'order' => $order,'post_type' => 'staff','staff-group'=>$cat,'posts_per_page' => $items));
					$count =0;
					if ( $wp_query ) : 
					while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<div class="<?php echo $itemsize;?> s_item">
                	<div class="grid_item staff_item kt_item_fade_in kad_staff_fade_in postclass">
					
							<?php global $post; if (has_post_thumbnail( $post->ID ) ) {
									$image_url = wp_get_attachment_image_src( 
									get_post_thumbnail_id( $post->ID ), 'full' ); 
									$thumbnailURL = $image_url[0]; 
									$image = aq_resize($thumbnailURL, $slidewidth, $slideheight, true);
										if(empty($image)) {$image = $thumbnailURL;} 
									?>

									<div class="imghoverclass">
										<a href="<?php echo $thumbnailURL ?>" rel="lightbox[pp_gal]"  class="lightboxhover">
	                                       <img src="<?php echo $image ?>" alt="<?php the_title(); ?>" class="" style="display: block;">
	                                    </a> 
	                                </div>
                           				<?php $image = null; $thumbnailURL = null;?>
                            <?php } ?>
			             <div class="staff_item_info">   
			                <h3><?php the_title();?></h3>
			                <?php the_content(); ?>
			            </div>
                	</div>
                </div>
					<?php endwhile; else: ?>
					<li class="error-not-found"><?php _e('Sorry, no staff entries found.', 'virtue');?></li>
				<?php endif; ?>
                </div> <!-- staffwrapper -->
                    <?php $wp_query = null; wp_reset_query(); ?>
		</div><!-- /.home-staff -->
            		

	<?php  $output = ob_get_contents();
		ob_end_clean();
	return $output;
}