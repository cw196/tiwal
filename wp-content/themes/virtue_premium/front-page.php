	<?php global $virtue_premium; 
	$detect = new Mobile_Detect_Virtue; if($detect->isMobile() && !$detect->isTablet() && $virtue_premium['mobile_switch'] == '1') {
		 		$slider = $virtue_premium['choose_mobile_slider'];
					if ($slider == "rev") {
					get_template_part('templates/mobile_home/mobilerev', 'slider');
				}
				else if ($slider == "flex") {
					get_template_part('templates/mobile_home/mobileflex', 'slider');
				}
				else if ($slider == "video") {
					get_template_part('templates/mobile_home/mobilevideo', 'block');
				}
				else if ($slider == "cyclone") {
					get_template_part('templates/mobile_home/cyclone', 'slider');
				}
			} else { 
			  $slider = $virtue_premium['choose_slider'];
					if ($slider == "rev") {
						if($virtue_premium['above_header_slider'] != 1) {
							get_template_part('templates/home/rev', 'slider');
						}
				}
				else if ($slider == "ktslider") {
						if($virtue_premium['above_header_slider'] != 1) {
							get_template_part('templates/home/kt', 'slider');
						}
				}
				else if ($slider == "flex") {
					get_template_part('templates/home/flex', 'slider');
				}
				else if ($slider == "carousel") {
					get_template_part('templates/home/carousel', 'slider');
				}
				else if ($slider == "imgcarousel") {
					get_template_part('templates/home/image', 'carousel');
				}
				else if ($slider == "latest") {
					get_template_part('templates/home/latest', 'slider');
				}
				else if ($slider == "thumbs") {
					get_template_part('templates/home/thumb', 'slider');
				}
				else if ($slider == "cyclone") {
					if($virtue_premium['above_header_slider'] != 1) {
						get_template_part('templates/home/cyclone', 'slider');
					}
				}
				else if ($slider == "fullwidth") {
					get_template_part('templates/home/fullwidth', 'slider');
				}
				else if ($slider == "video") {
					get_template_part('templates/home/video', 'block');
				}
			}
	$show_pagetitle = false;
	if(isset($virtue_premium['homepage_layout']['enabled'])){
		$i = 0;
		foreach ($virtue_premium['homepage_layout']['enabled'] as $key=>$value) {
			if($key == "block_one") {
				$show_pagetitle = true;
			}
			$i++;
			if($i==2) break;
		}
	} 
	if($show_pagetitle == true) {
		?><div id="homeheader" class="welcomeclass">
								<div class="container">
									<?php get_template_part('templates/page', 'header'); ?>
								</div>
							</div><!--titleclass-->
	<?php } ?>
    <div id="content" class="container homepagecontent">
   		<div class="row">
          <div class="main <?php echo kadence_main_class(); ?>" role="main">

      	<?php if(isset($virtue_premium['homepage_layout']['enabled'])) { $layout = $virtue_premium['homepage_layout']['enabled']; } else {$layout = array("block_one" => "block_one", "block_four" => "block_four");}

				if ($layout):

				foreach ($layout as $key=>$value) {

				    switch($key) {

				    	case 'block_one':?>
				    	<?php if($show_pagetitle == false) {?>
				    	  <div id="homeheader" class="welcomeclass">
									<?php get_template_part('templates/page', 'header'); ?>
							</div><!--titleclass-->
							<?php }?>
					    <?php 
					    break;
					    case 'block_two': 
				    		get_template_part('templates/home/image', 'menu');
					    break;
						case 'block_three':
							get_template_part('templates/home/product', 'carousel');
						break;
						case 'block_four': ?>
							<?php if(is_home()) { ?>
							<?php if(kadence_display_sidebar()) {$display_sidebar = true; $fullclass = '';} else {$display_sidebar = false; $fullclass = 'fullwidth';} ?>
								<?php global $virtue_premium; if(isset($virtue_premium['home_post_summery']) and ($virtue_premium['home_post_summery'] == 'full')) {$summery = "full"; $postclass = "single-article fullpost";} else {$summery = "summery"; $postclass = "postlist";} 
								if(isset($virtue_premium['home_post_grid']) && $virtue_premium['home_post_grid'] == '1') {$grid = true; $contentid = 'kad-blog-grid'; $postclass = "postlist rowtight";} else {$grid = false; $contentid = 'homelatestposts';}
								if(isset($virtue_premium['virtue_animate_in']) && $virtue_premium['virtue_animate_in'] == 1) {$animate = 1;} else {$animate = 0;}  
								if($grid == true) {
									$blog_grid_column = $virtue_premium['home_post_grid_columns'];
									if ($blog_grid_column == 'twocolumn') {$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12';} else if ($blog_grid_column == 'threecolumn'){ $itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12';} else {$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12';}
								}
								if(isset($virtue_premium['blog_infinitescroll']) && $virtue_premium['blog_infinitescroll'] == 1) {$infinitescroll = true;} else {$infinitescroll = false;}?>
								<div id="<?php echo $contentid;?>" class="homecontent <?php echo $fullclass; ?>  <?php echo $postclass; ?> clearfix home-margin"  data-fade-in="<?php echo $animate;?>"> 
							<?php while (have_posts()) : the_post(); ?>
							  <?php  if($summery == 'full') {
											if($display_sidebar){
												get_template_part('templates/content', 'fullpost'); 
											} else {
												get_template_part('templates/content', 'fullpostfull');
											}
									} else {
										if($grid == true) {
												if($blog_grid_column == 'twocolumn') { ?>
													<div class="<?php echo $itemsize;?> b_item kad_blog_item">
													<?php get_template_part('templates/content', 'twogrid'); ?>
													</div>
													<?php } else {?>
													<div class="<?php echo $itemsize;?> b_item kad_blog_item">
													<?php get_template_part('templates/content', 'fourgrid');?>
													</div>
												<?php }
										} else {
											if($display_sidebar){
											 	get_template_part('templates/content', get_post_format()); 
											 } else {
											 	get_template_part('templates/content', 'fullwidth');
											 }
										}
									}?>
							<?php endwhile; ?>
							</div> 
							<?php if ($wp_query->max_num_pages > 1) : ?>
							        <?php if(function_exists('kad_wp_pagenavi')) { ?>
							              <?php kad_wp_pagenavi(); ?>   
							            <?php } else { ?>      
							              <nav class="post-nav">
							                <ul class="pager">
							                  <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'virtue')); ?></li>
							                  <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'virtue')); ?></li>
							                </ul>
							              </nav>
							            <?php } ?> 
							        <?php endif; ?>
							        <?php if($grid == true) {?>
							    	<script type="text/javascript">jQuery(document).ready(function ($) {var $container = $('#kad-blog-grid');$container.imagesLoadedn( function(){$container.isotopeb({masonry: {columnWidth: ".b_item"}, transitionDuration: "0.8s"});if($('#kad-blog-grid').attr('data-fade-in') == 1) {$('#kad-blog-grid .kad_blog_fade_in').css('opacity',0); $('#kad-blog-grid .kad_blog_fade_in').each(function(i){$(this).delay(i*150).animate({'opacity':1},350);});}}); });</script>				
									<?php } ?>
									<?php if ($infinitescroll) { ?>
							        	 <?php if($grid == true) {?>
												<script type="text/javascript">jQuery(document).ready(function ($) {
													var $container = $('#kad-blog-grid');
													$('.homecontent').infinitescroll({
													    nextSelector: ".wp-pagenavi a.next",
													    navSelector: ".wp-pagenavi",
													    itemSelector: ".kad_blog_item",
													    loading: {
													    		msgText: "",
													            finishedMsg: '',
													            img: "<?php echo get_template_directory_uri() . '/assets/img/loader.gif'; ?>"
													        }
													    },
													    function( newElements ) {
												         var $newElems = jQuery( newElements ).hide(); // hide to begin with
												  			// ensure that images load before adding to masonry layout
														  $newElems.imagesLoadedn(function(){
														    $newElems.fadeIn(); // fade in when ready
														    $container.isotopeb( 'appended', $newElems );
														    if($container.attr('data-fade-in') == 1) {
																	//fadeIn items one by one
																		$newElems.each(function() {
																	    $(this).find('.kad_blog_fade_in').delay($(this).attr('data-delay')).animate({'opacity' : 1, 'top' : 0},800,'swing');},{accX: 0, accY: -85},'easeInCubic');
																	 
																	} 
														  });
														 });
												});
												</script>
														  <?php } else { ?>
												<script type="text/javascript">jQuery(document).ready(function ($) {
												$('.homecontent').infinitescroll({
												    nextSelector: ".wp-pagenavi a.next",
												    navSelector: ".wp-pagenavi",
												    itemSelector: ".kad_blog_item",
												    loading: {
												    		msgText: "",
												            finishedMsg: '',
												            img: "<?php echo get_template_directory_uri() . '/assets/img/loader.gif'; ?>"
												        }
												    },
												        function( newElements ) {
												         var $newElems = jQuery( newElements ); // hide to begin with
														    if($newElems.attr('data-animation') == 'fade-in') {
																		//fadeIn items one by one
																		$newElems.each(function() {
																	    $(this).appear(function() {
																	    $(this).delay($(this).attr('data-delay')).animate({'opacity' : 1, 'top' : 0},800,'swing');},{accX: 0, accY: -85},'easeInCubic');
																	    });
																	} 
												    
														  }); 
														

												});	
												</script>
										<?php }} ?>
							
						<?php } else { ?>
						<div class="homecontent clearfix home-margin"> 
							<?php get_template_part('templates/content', 'page'); ?>
						</div>
						<?php 	}
						break;
						case 'block_five':
						 global $virtue_premium; if(isset($virtue_premium['home_post_column']) and ($virtue_premium['home_post_column'] == '1')) {
						 	get_template_part('templates/home/blog', 'home-onecolumn');
						 } else {
						 	get_template_part('templates/home/blog', 'home');
						 }  
						break;
						case 'block_six':
								get_template_part('templates/home/portfolio', 'carousel');		 
						break; 
						case 'block_seven':
								get_template_part('templates/home/icon', 'menu');		 
						break;
						case 'block_eight':
								get_template_part('templates/home/portfolio', 'full');		 
						break; 
						case 'block_nine':
								get_template_part('templates/home/product-sale', 'carousel');		 
						break; 
						case 'block_ten':
								get_template_part('templates/home/product-best', 'carousel');		 
						break; 
						case 'block_eleven':
								get_template_part('templates/home/custom', 'carousel');		 
						break; 
						case 'block_twelve':
								get_template_part('templates/home/widget', 'box');		 
						break;  
					    }

}
endif; ?>   


</div><!-- /.main -->