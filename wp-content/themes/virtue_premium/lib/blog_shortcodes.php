<?php 
//Shortcode for Blog Posts
function kad_blog_shortcode_function( $atts, $content) {
	extract(shortcode_atts(array(
		'orderby' => '',
		'type' =>'',
		'speed' =>'',
		'height' =>'',
		'width' =>'',
		'cat' => '',
		'word_count' => '',
		'items' => ''
), $atts));
	$carousel_rn = (rand(10,100));
	if(empty($orderby)) {$orderby = 'date';}
	if($orderby == 'menu_order') {$order = 'ASC';} else {$order = 'DESC';} 
	if(empty($items)) {$items = '4';}
	if(empty($word_count)) {$word_count = '36';}
	if(empty($cat)) {$cat = '';}

	if(!empty($type) && $type == 'slider') {

ob_start(); ?>
		<div class="sliderclass">
  <?php  global $virtue_premium; 
         if(!empty($height)) {$slideheight = $height;} else { $slideheight = 400; }
         if(!empty($width)) {$slidewidth = $width;} else { $slidewidth = 1140; }
          if(empty($speed)) {$speed = '7000';}
         ?>
          <div class="flexslider kt-flexslider loading" style="max-width:<?php echo $slidewidth;?>px; margin-left: auto; margin-right:auto;" data-flex-speed="<?php echo $speed;?>" data-flex-initdelay="0" data-flex-anim-speed="400" data-flex-animation="fade" data-flex-auto="true">
            <ul class="slides">
    <?php $wp_query = null; 
				$wp_query = new WP_Query();
				$wp_query->query(array('orderby' => $orderby,'order' => $order,'post_type' => 'post','category_name'=>$cat,'posts_per_page' => $items));
						if ( $wp_query ) :  while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
                  global $post; if (has_post_thumbnail( $post->ID ) ) {
                              $image_url = wp_get_attachment_image_src( 
                              get_post_thumbnail_id( $post->ID ), 'full' ); 
                              $thumbnailURL = $image_url[0]; 
                              $image = aq_resize($thumbnailURL, $slidewidth, $slideheight, true);
                              if(empty($image)) { $image = $thumbnailURL; } 
                  	} else {
                               $thumbnailURL = virtue_post_default_placeholder();
                                  $image = aq_resize($thumbnailURL, $slidewidth, $slideheight, true);
                                  if(empty($image)) { $image = $thumbnailURL; } ?>
                  <?php  } ?>
                      <li> 
                        <a href="<?php the_permalink(); ?>">
                          <img src="<?php echo $image; ?>" alt="<?php the_title(); ?>" />
                                <div class="flex-caption">
                                <div class="captiontitle headerfont"><?php the_title(); ?></div>
                                </div> 
                        </a>
                      </li>
                  <?php endwhile; else: ?>
            <li class="error-not-found"><?php _e('Sorry, no blog entries found.', 'virtue'); ?></li>
          <?php endif; ?>
        <?php $wp_query = null; // Reset ?>
        <?php wp_reset_query(); ?>
        </ul>
      </div> <!--Flex Slides-->
  </div> <!--Slider Class-->
<?php  $output = ob_get_contents();
		ob_end_clean();
	return $output;
	} else {
ob_start(); ?>
				<div class="home_blog kad-animation" data-animation="fade-in" data-delay="0">
				<?php if(kadence_display_sidebar()) {$home_sidebar = true; $img_width = 407; $postwidthclass = 'col-md-6 col-sm-6 home-sidebar';} else {$home_sidebar = false; $img_width = 270; $postwidthclass = 'col-md-6 col-sm-6';}?>
				<div class="row">
            		<?php 
				$wp_query = null; 
				$wp_query = new WP_Query();
				$wp_query->query(array('orderby' => $orderby,'order' => $order,'post_type' => 'post','category_name'=>$cat,'posts_per_page' => $items));
						if ( $wp_query ) :  while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
						<div class="<?php echo $postwidthclass; ?> clearclass<?php echo ($xyz++%2); ?>">
				  	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	                    <div class="rowtight">
	                    <?php global $post, $virtue_premium; if(isset($virtue_premium['post_summery_default']) && ($virtue_premium['post_summery_default'] != 'text')) {
							if($home_sidebar == true) {$textsize = 'tcol-md-12 tcol-sm-12 tcol-ss-12'; $imagesize = 'tcol-md-12 tcol-sm-12 tcol-ss-12';} else {$textsize = 'tcol-md-7 tcol-sm-12 tcol-ss-12'; $imagesize = 'tcol-md-5 tcol-sm-12 tcol-ss-12';}
								if (has_post_thumbnail( $post->ID ) ) {
										$image_url = wp_get_attachment_image_src( 
											get_post_thumbnail_id( $post->ID ), 'full' ); 
										$thumbnailURL = $image_url[0]; 
										$image = aq_resize($thumbnailURL, $img_width, 270, true);
										if(empty($image)) { $image = $thumbnailURL; }
									} else {
								 		$thumbnailURL = virtue_post_default_placeholder();
										$image = aq_resize($thumbnailURL, $img_width, 270, true);
										if(empty($image)) { $image = $thumbnailURL; }
							 		} ?>
								 <div class="<?php echo $imagesize;?>">
									 <div class="imghoverclass">
		                           		<a href="<?php the_permalink()  ?>" title="<?php the_title(); ?>">
		                           			<img src="<?php echo $image ?>" alt="<?php the_title(); ?>" class="iconhover" style="display:block;">
		                           		</a> 
		                             </div>
		                         </div>

                           		<?php $image = null; $thumbnailURL = null; ?> 
                        <?php } else { 
                          		if (has_post_thumbnail( $post->ID ) ) {
	                    				if($home_sidebar == true) {$textsize = 'tcol-md-12 tcol-sm-12 tcol-ss-12'; $imagesize = 'tcol-md-12 tcol-sm-12 tcol-ss-12';} else {$textsize = 'tcol-md-7 tcol-sm-12 tcol-ss-12'; $imagesize = 'tcol-md-5 tcol-sm-12 tcol-ss-12';}
										$image_url = wp_get_attachment_image_src( 
											get_post_thumbnail_id( $post->ID ), 'full' ); 
										$thumbnailURL = $image_url[0]; 
										$image = aq_resize($thumbnailURL, $img_width, 270, true);
										if(empty($image)) { $image = $thumbnailURL; }
										 ?>
								 		<div class="<?php echo $imagesize;?>">
										 <div class="imghoverclass">
			                           		<a href="<?php the_permalink()  ?>" title="<?php the_title(); ?>">
			                           			<img src="<?php echo $image ?>" alt="<?php the_title(); ?>"  width="<?php echo $img_width;?>" height="270" class="iconhover" style="display:block;">
			                           		</a> 
			                             </div>
			                         	</div>
 										<?php $image = null; $thumbnailURL = null; ?> 
		                       <?php } else { $textsize = 'tcol-md-12 tcol-ss-12';} }?>
	                       		<div class="<?php echo $textsize;?> postcontent">
	                       			<div class="postmeta color_gray">
				                        	<div class="postdate bg-lightgray headerfont">
				                        		<span class="postday"><?php echo get_the_date('j'); ?></span>
				                        		<?php echo get_the_date('M Y');?>
				                        	</div>
				                            
				                        </div>
				                    <header class="home_blog_title">
			                          <a href="<?php the_permalink() ?>"><h5 class="entry-title"><?php the_title(); ?></h5></a>
			                          <div class="subhead color_gray">
			                          	<span class="postauthortop" rel="tooltip" data-placement="top" data-original-title="<?php echo get_the_author() ?>">
			                          		<i class="icon-user"></i>
			                          	</span>
			                          	<span class="kad-hidepostauthortop"> | </span>
			                          		<?php $post_category = get_the_category($post->ID); if (!empty($post_category)) { ?> 
			                          		<span class="postedintop" rel="tooltip" data-placement="top" data-original-title="<?php 
			                          			foreach ($post_category as $category)  { 
			                          				echo $category->name .'&nbsp;'; 
			                          			} ?>"><i class="icon-folder"></i></span>
			                          		 <?php }?>
			                          	<span class="kad-hidepostedin">|</span>
			                        	<span class="postcommentscount" rel="tooltip" data-placement="top" data-original-title="<?php comments_number( '0', '1', '%' ); ?>">
			                        		<i class="icon-bubbles"></i>
			                        	</span>
			                        </div>
			                        </header>
		                        	<div class="entry-content">
		                          		<p><?php echo virtue_excerpt($word_count); ?> <a href="<?php the_permalink() ?>">
		                          			<?php global $virtue_premium; if(!empty($virtue_premium['post_readmore_text'])) {$readmore = $virtue_premium['post_readmore_text'];} else {$readmore = __('Read More', 'virtue');} echo $readmore; ?></a></p>
		                        	</div>
		                      		<footer>
                       				</footer>
							</div>
	                   	</div>
                    </article>
                </div>
                    <?php endwhile; else: ?>
						<li class="error-not-found"><?php _e('Sorry, no blog entries found.', 'virtue');?></li>
					<?php endif; ?>
				<?php $wp_query = null; wp_reset_query(); ?>

	</div>
</div> <!--home-blog -->
            		

	<?php  $output = ob_get_contents();
		ob_end_clean();
	return $output;
	}
}


//Shortcode for Blog Posts Full and Simple
function kad_blog_simple_shortcode_function( $atts, $content) {
	extract(shortcode_atts(array(
		'orderby' => 'date',
		'cat' => '',
		'fullpost' => false,
		'items' => '8'
), $atts));
	if($orderby == 'menu_order') {$order = 'ASC';} else {$order = 'DESC';} 
	if(empty($cat)) {$cat = '';}
ob_start(); ?>
	<?php if(kadence_display_sidebar()) {$display_sidebar = true; $fullclass = '';} else {$display_sidebar = false; $fullclass = 'fullwidth';}
   		  if($fullpost) {$summery = 'full'; $postclass = "single-article fullpost";} else {$summery = 'normal'; $postclass = 'postlist';} ?>
      <div class="<?php echo $postclass .' '. $fullclass; ?>">
				<?php  
					if(!empty($wp_query)) {$temp = $wp_query; }
					$wp_query = null; 
					$wp_query = new WP_Query();
					$wp_query->query(array('orderby' => $orderby,'order' => $order,
						'category_name'=>$cat,
						'posts_per_page' => $items));
					$count =0;
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<?php if($fullpost) {
							if($display_sidebar) {
								get_template_part('templates/content', 'fullpost'); 
							} else {
								get_template_part('templates/content', 'fullpostfull');
							}
						} else {
							if($display_sidebar) {
						 	get_template_part('templates/content', get_post_format()); 
						 } else {
						 	get_template_part('templates/content', 'fullwidth');
						 }
						} 
                    endwhile; else: ?>
						<li class="error-not-found"><?php _e('Sorry, no blog entries found.', 'virtue'); ?></li>
					<?php endif; ?>
               
				<?php $wp_query = null; 
				if(!empty($temp)) {$wp_query = $temp; } // Reset ?>
				<?php wp_reset_query(); ?>
            		</div>

	<?php  $output = ob_get_contents();
		ob_end_clean();
	return $output;
}

//Shortcode for Blog Post in a grid Grid 
function kad_blog_grid_shortcode_function( $atts, $content) {
	extract(shortcode_atts(array(
		'orderby' => 'date',
		'cat' => '',
		'columns' => '3',
		'items' => '8'
), $atts));
	if($orderby == 'menu_order') {$order = 'ASC';} else {$order = 'DESC';} 
	if(empty($cat)) {$cat = '';}
	if ($columns == '2') {$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12';} else if ($columns == '3'){ $itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12';} else {$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12';}
	global $virtue_premium;
	if(isset($virtue_premium['virtue_animate_in']) && $virtue_premium['virtue_animate_in'] == 1) {$animate = 1;} else {$animate = 0;}
ob_start(); ?>
<div id="kad-blog-grid" class="shortcode_blog_grid_content rowtight init-isotope" data-fade-in="<?php echo $animate;?>" data-iso-selector=".b_item"> 
				<?php  
					if(!empty($wp_query)) {$temp = $wp_query; }
					$wp_query = null; 
					$wp_query = new WP_Query();
					$wp_query->query(array('orderby' => $orderby,'order' => $order,
						'category_name'=>$cat,
						'posts_per_page' => $items));
					$count =0;
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
					if($columns == '2') { ?>
						<div class="<?php echo $itemsize;?> b_item kad_blog_item">
							<?php get_template_part('templates/content', 'twogrid'); ?>
						</div>
					<?php } else {?>
						<div class="<?php echo $itemsize;?> b_item kad_blog_item">
							<?php get_template_part('templates/content', 'fourgrid');?>
						</div>
					<?php }
                    endwhile; else: ?>
						<li class="error-not-found"><?php _e('Sorry, no blog entries found.', 'virtue'); ?></li>
					<?php endif; ?>
               
				<?php $wp_query = null; 
				if(!empty($temp)) {$wp_query = $temp; } // Reset ?>
				<?php wp_reset_query(); ?>
            		</div>

	<?php  $output = ob_get_contents();
		ob_end_clean();
	return $output;
}